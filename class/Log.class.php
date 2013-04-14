<?
class Log
{
	static public function track($person_id=0)
	{
		# Basic setup first
		global $db, $user;
		$sql = "INSERT INTO app_log (person_id, user_id, visit_date) VALUES ('$person_id', '$user->id', NOW())";
		$db->sql_query($sql);
	}
	
	static public function load()
	{
		global $db;
		# Get the current time, so we keep using the same time each time we do a query below
		$now = getServerTime();
		// $now = '2009-03-01 10:06:40';
		// echo $now."<br>";
	
		# UPDATE tree_person.page_views
		$sql = "SELECT l.person_id, pc.page_views, count(*) total FROM app_log l
				JOIN tree_person p ON l.person_id = p.person_id AND ".actualClause("p")."
				JOIN tree_person_calc pc ON l.person_id = pc.person_id
				WHERE l.visit_date < '$now' GROUP BY l.person_id";
		echo "<br>".$sql;
		$data = $db->select($sql);

		foreach ($data as $row) {
			$x = $row["total"] + $row["page_views"];
			$sql = "UPDATE tree_person_calc SET page_views = $x
					WHERE person_id = ".$row["person_id"];
			echo "<br>".$sql;
			$db->sql_query($sql);
		}
		
		# POST to app_recent_view
		$sql = "SELECT l.person_id, l.user_id, max(l.visit_date) visit_date, max(v.last_update_date) last_update_date FROM app_log l
				LEFT JOIN app_recent_view v ON l.person_id = v.person_id AND (CASE l.user_id WHEN 0 THEN 3 ELSE l.user_id END) = v.user_id
				WHERE visit_date < '$now' GROUP BY l.person_id, l.user_id";
		echo "<br>".$sql;
		$data = $db->select($sql);
		foreach ($data as $row) {
			$sql = "";
			$user_id = ($row["user_id"] > 0) ? $row["user_id"] : 3; //3=anonymous
			if ($row["last_update_date"] == "") {
				$sql = "INSERT INTO app_recent_view (person_id, user_id, last_update_date) VALUES
						(".$row["person_id"].", $user_id, '".$row["visit_date"]."')";
				$db->sql_query($sql);
			} elseif ($row["last_update_date"] <> $row["visit_date"]) {
				$sql = "UPDATE app_recent_view SET last_update_date = '".$row["visit_date"]."' 
						WHERE person_id = ".$row["person_id"]." AND user_id = $user_id";
				$db->sql_query($sql);
			}
			echo "<br>".$sql;
		}
		# We aren't going to track user_id pageviews right now (at least this way)
		$sql = "SELECT user_id, count(*) total FROM app_log 
				WHERE visit_date < '$now' AND user_id > 0 GROUP BY user_id";

		# Remove processed logs
		$sql = "DELETE FROM app_log WHERE visit_date < '$now'";
		echo "<br>".$sql;
		$db->sql_query($sql);
	}
}

?>
