<?
class DiscussWiki
{
	static public function getWiki($person_id, $update_date=0)
	{
		global $db;
		$person_id = (int) $person_id;
		$wiki_id = (int) $wiki_id;
		if ($update_date > '') {
			$sql = "SELECT w.wiki_id, w.person_id, h.wiki_text, u.user_id, u.given_name, u.family_name
					FROM discuss_wiki_history h
					JOIN discuss_wiki w ON h.wiki_id = w.wiki_id
					LEFT JOIN app_user u ON h.updated_by = u.user_id
					WHERE w.person_id = $person_id AND h.update_date = '".fixTick($update_date)."'";
		} else {
			$sql = "SELECT w.wiki_id, w.person_id, w.wiki_text, u.user_id, u.given_name, u.family_name
					FROM discuss_wiki w
					LEFT JOIN app_user u ON w.updated_by = u.user_id
					WHERE w.person_id = $person_id";
		}
		$data = $db->select($sql);
		return $data;
	}

	static public function getHistory($person_id)
	{
		global $db;
		$person_id = (int) $person_id;
		$sql = "SELECT h.update_date, u.user_id, u.given_name, u.family_name, length(h.wiki_text) text_length
				FROM discuss_wiki_history h
				JOIN discuss_wiki w ON h.wiki_id = w.wiki_id
				LEFT JOIN app_user u ON w.updated_by = u.user_id
				WHERE w.person_id = $person_id ORDER BY update_date DESC LIMIT 0,10";
		$data = $db->select($sql);
		return $data;
	}

	static public function convertToHTML($wiki_text)
	{
		include_class("WikiParser");
		$wParse = new WikiParser();
		$html_text = $wParse->parse($wiki_text);
		return $html_text;
	}

	static public function save($data)
	{
		global $db, $user;
		$wiki_id = (int) $data['wiki_id'];
		$person_id = (int) $data['person_id'];
		$wiki_text = fixTick($data['wiki_text']);
		if($wiki_id > 0) {
			$sql = "INSERT INTO discuss_wiki_history SELECT wiki_id, update_date, updated_by, wiki_text FROM discuss_wiki WHERE wiki_id = $wiki_id";
			$db->sql_query($sql);
			$sql = "UPDATE discuss_wiki SET wiki_text = '$wiki_text',  update_date = Now(), updated_by = '$user->id', update_process = 'DiscussWiki.class.php'
					WHERE wiki_id = '$wiki_id'";
			$db->sql_query($sql);
			
			# Prune history
			$sql = "DELETE FROM discuss_wiki_history WHERE wiki_id = $wiki_id AND update_date < DATE_ADD(Now(),INTERVAL -90 DAY)";
			$db->sql_query($sql);
		} else {
			$sql = "INSERT INTO discuss_wiki SET wiki_text = '$wiki_text', person_id = '$person_id', update_date = Now(), updated_by = '$user->id', update_process = 'DiscussWiki.class.php' ";
			$db->sql_query($sql);
		}
		# Send a notification to users watching this person
		include_class("Person");
		Person::mailWatch($person_id);
		return true;
	}
}
?>
