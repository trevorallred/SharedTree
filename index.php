<?php
/*
6 Jan 2012 ~ SharedTree will soon be back.
<br>
Trevor
<br><br><br><br><br><br><br><br><br><br><br><br>

*/
error_reporting(E_ERROR|E_WARNING|E_PARSE);
//error_reporting(E_ALL);

require_once("config.php");
require_once("inc/main.php");

if ($mobile) {
	require("mobile.php");
}
if (!is_logged_on()) {
	//include_class("Person");
	//$T->assign('people', Person::countPeople() );

	require("intro.html");
	exit();
}
# The user is logged in so show them a custom profile page just for them

// Create a Cache_Lite object
$st_cache = new Cache_Lite();


$T->assign('user', $user->data);
if ($user->data["person_id"] > 0) {
	$T->assign("person_id", $user->data["person_id"]);
	$T->assign("primary_person", $user->data["p_given_name"]." ".$user->data["p_family_name"]);
}

include_class("Person");
include_class("Family");


# Count the total number of families on the site
if ($relatives = $st_cache->get("relatives".$user->id)) {
	$relatives = unserialize($relatives);
} else {
	$sql = "SELECT l.trace, u.*
			FROM app_user_line_person l
			JOIN app_user u ON l.person_id = u.person_id AND u.user_id <> l.user_id
			WHERE l.user_id = '$user->id' ORDER BY last_visit_date DESC LIMIT 0,10";
	//echo $sql;
	$relatives = $db->select($sql);
	$st_cache->save(serialize($relatives), "relatives".$user->id);
}
$T->assign('relatives', $relatives);

$T->assign("person_changes", Person::recentChange(10));

include_class("Watch");
$T->assign("bookmarks", Watch::listUserBookmark());

$T->assign("myline", $user->countLine());
$T->assign("viewed", $user->recentViewed(10));


# Count the total number of families on the site
if ($relatives = $st_cache->get("relatives".$user->id)) {
	$relatives = unserialize($relatives);
} else {
	$sql = "SELECT l.trace, u.*
			FROM app_user_line_person l
			JOIN app_user u ON l.person_id = u.person_id AND u.user_id <> l.user_id
			WHERE l.user_id = '$user->id' ORDER BY last_visit_date DESC LIMIT 0,10";
	//echo $sql;
	$relatives = $db->select($sql);
	$st_cache->save(serialize($relatives), "relatives".$user->id);
}

# Get a list of recent photos
if ($photos = $st_cache->get("newphotos".$user->id)) {
	$photos = unserialize($photos);
} else {
	$sql = "SELECT i.image_id, p.person_id, p.given_name, p.family_name, p.birth_year, i.update_date, i.updated_by, u.given_name as updated_name
			FROM tree_image i
			JOIN tree_person p ON i.person_id = p.person_id AND ".actualClause("p")."
			JOIN app_user_line_person l ON l.person_id = p.person_id AND l.user_id = '$user->id'
			LEFT JOIN app_user u ON i.updated_by = u.user_id
			ORDER BY i.update_date DESC LIMIT 0, 10";
	//echo $sql;
	$photos = $db->select($sql);
	//print_pre($photos);

	$st_cache->save(serialize($photos), "newphotos".$user->id);
}
$T->assign("photos", $photos);

# Count the total number of families on the site
if ($data = $st_cache->get("recent_posts".$user->id)) {
	$data = unserialize($data);
} else {
	$sql = "SELECT pp.post_id, pp.update_date, p.person_id, p.given_name, p.family_name, u.user_id, u.given_name ugiven_name, u.family_name ufamily_name, pp.post_text
			FROM discuss_post pp
			JOIN app_user_line_person l ON pp.person_id = l.person_id AND l.user_id = '$user->id'
			JOIN tree_person p ON pp.person_id = p.person_id AND ".actualClause()."
			JOIN app_user u ON pp.updated_by = u.user_id
			ORDER BY pp.update_date DESC
			LIMIT 0, 10";
	$data = $db->select($sql);
	$st_cache->save(serialize($data), "recent_posts".$user->id);
}
$T->assign("posts", $data);

if ($data = $st_cache->get("recent_bios".$user->id)) {
	$data = unserialize($data);
} else {
	$sql = "SELECT pp.wiki_id, pp.update_date, p.person_id, p.given_name, p.family_name, u.user_id, u.given_name ugiven_name, u.family_name ufamily_name, pp.wiki_text post_text
			FROM discuss_wiki pp
			JOIN app_user_line_person l ON pp.person_id = l.person_id AND l.user_id = '$user->id'
			JOIN tree_person p ON pp.person_id = p.person_id AND ".actualClause()."
			JOIN app_user u ON pp.updated_by = u.user_id
			ORDER BY pp.update_date DESC
			LIMIT 0, 10";
	$data = $db->select($sql);
	$st_cache->save(serialize($data), "recent_bios".$user->id);
}
$T->assign("bios", $data);

# Count the total number of people on the site
if ($total_count = $st_cache->get("total_person_count")) {
} else {
	$sql = "SELECT count(*) total FROM tree_person";
	$temp = $db->select($sql);
	$total_count = $temp[0]["total"];
    $st_cache->save($total_count, "total_person_count");
}
$T->assign("count_person", $total_count);

# Count the total number of families on the site
if ($total_count = $st_cache->get("total_family_count")) {
} else {
	$sql = "SELECT count(*) total FROM tree_family";
	$temp = $db->select($sql);
	$total_count = $temp[0]["total"];
    $st_cache->save($total_count, "total_family_count");
}
$T->assign("count_family", $total_count);

# Count the total number of users on the site
if ($total_count = $st_cache->get("total_user_count")) {
} else {
	$sql = "SELECT count(*) total FROM app_user";
	$temp = $db->select($sql);
	$total_count = $temp[0]["total"];
    $st_cache->save($total_count, "total_user_count");
}
$T->assign("count_user", $total_count);
/*
// For now, use the phpBB forums to make announcements
$sql = "SELECT a.start_date, a.announcement, CONCAT(u.given_name, ' ', u.family_name) user_name
		FROM app_announcement a LEFT JOIN app_user u ON u.user_id = a.user_id
		WHERE a.start_date <= Now() AND (a.stop_date IS NULL OR a.stop_date > Now()) ORDER BY announcement_id DESC";
$announcements = $db->select($sql);
$T->assign("announcements", $announcements);
*/

$config["use_phpbb"] = true;
if ($config["use_phpbb"] == true) {
	$sql = "SELECT p.topic_id, p.post_subject, p.post_time, u.username, substr(p.post_text,1,40) post_text
			FROM phpbb_posts p LEFT JOIN phpbb_users u ON p.poster_id = u.user_id
			ORDER BY post_time DESC LIMIT 10";
	$sql = "SELECT topic_id, topic_last_post_id, topic_title, topic_last_post_time, topic_last_poster_name
			FROM phpbb_topics t ORDER BY topic_last_post_time DESC LIMIT 10";
	$recent_forum_posts = $db->select($sql);
	$T->assign("recent_forum_posts", $recent_forum_posts);
}

$T->display('mywillow.tpl');

?>
