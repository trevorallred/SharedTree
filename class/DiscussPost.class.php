<?
class DiscussPost
{
	static public function getPosts($where)
	{
		global $db;
		$where = ($where > "")?"WHERE $where":"";
		
		$sql = "SELECT p.post_id, p.creation_date, p.update_date, p.parent_id, p.post_text, p.post_type, c.meaning post_meaning, 
					p.created_by, u.given_name, u.family_name, u.creation_date join_date, u.city, u.state_code 
				FROM discuss_post p
				LEFT JOIN ref_codes c ON c.ref_type = 'DiscussionType' AND c.ref_code = p.post_type
				LEFT JOIN app_user u ON u.user_id = p.created_by
				$where ORDER BY p.creation_date DESC LIMIT 10 ";
		$data = $db->select($sql);
		return $data;
	}
	
	static public function save($data)
	{
		global $db, $user;
		$post_id = (int) $data['post_id'];
		$person_id = (int) $data['person_id'];
		$parent_id = (int) $data['parent_id'];
		$post_text = fixTick($data['post_text']);
		$post_type = fixTick($data['post_type']);
		if($post_id > 0) {
			$sql = "UPDATE discuss_post SET post_type = '$post_type', post_text = '$post_text',  update_date = Now(), updated_by = '$user->id', update_process = 'DiscussPost.class.php'
					WHERE post_id = '$post_id'";
			$db->sql_query($sql);
		} else {
			$sql = "INSERT INTO discuss_post SET post_text = '$post_text', person_id = '$person_id', parent_id = '$parent_id', update_date = Now(), updated_by = '$user->id', update_process = 'DiscussPost.class.php', creation_date = Now(), created_by = '$user->id' ";
			$db->sql_query($sql);
			# Send a notification to users watching this person
			include_class("Person");
			Person::mailWatch($person_id);
		}
		return true;
	}
	
	static public function delete($post_id)
	{
		global $db;
		$post_id = (int) $post_id;
		if (empty($post_id)) return true;
		
		# Don't delete posts that have children
		$sql = "SELECT count(*) as total FROM discuss_post WHERE parent_id = $post_id";
		$data = $db->sql_query($sql);
		if ($data[0]['total'] > 0) return false;
		# This post has no children, delete it now
		$sql = "DELETE FROM discuss_post WHERE post_id = $post_id";
		$db->sql_query($sql);
		return true;
	}
}
?>
