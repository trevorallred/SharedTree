<?
require_once("config.php");
require_once("inc/main.php");
include_class("Person");

############################################################################
# Set common variables and assign them to the smarty template
$person_id = (int)$_REQUEST['person_id'];
// $person_id = (int)substr($_SERVER['PATH_INFO'],1,16);
// phpinfo();
// echo $person_id;
// die();
$T->assign('person_id',$person_id);
$action = $_REQUEST['action'];
$T->assign('action',$action);
$time = $_REQUEST['time'];
$T->assign('time',$time);
if ($time) $T->assign('noindex',1); // don't index this page

$T->assign("show_lds", $user->data['show_lds']);
$T->assign("adbc", array(1=>"A.D.", 0=>"B.C."));
$T->assign('gender_options',array("M"=>"Male","F"=>"Female", "U"=>"Unknown"));

if (empty($person_id)) {
	$T->display("header.tpl");
	errorMessage("You must supply a person_id to view or edit an individual","search.php", false);
}

$person = new Person($person_id, $time);
if (empty($person->data)) {
	# We found no active records, so let's try this again by searching for the last deleted record
	$time = $person->getPersonDeleted($person_id);
}
## Check to see if the person can view this record
if ($person->data["trace"] == "") {
	$extended = $person->extendTree();
	if ($extended > 0) $person->getPerson();
	$T->assign("extendTree", $extended);
}
if ($person->restricted) privateRecord();
include_class("Log");
Log::track($person->id);

$T->assign('person',$person->data);
$T->assign("primary_person", $person->data['full_name']);

if (isset($_REQUEST["match"])) {
	include_class("Match");
	echo Match::find($person_id);
}

############################################################################
# Save the wiki (History/Biography/Notes)

if ($action == "wikiedit") {
	require_login();
	include_class("DiscussWiki");
	if (isset($_REQUEST['save'])) {
		include_class("Watch");
		Watch::watchPerson($person_id, $_REQUEST["watch"]);
		# Save a new or existing post
		if (DiscussWiki::save($_REQUEST['wiki'])) {
			// save is successful, redirect and exit
			redirect("/person/".$person_id);
		}
	}
	$history = DiscussWiki::getHistory($person_id);
	$T->assign('history', $history);

	$T->assign('update_date', $_REQUEST['update_date']);
	$pages = DiscussWiki::getWiki($person_id, $_REQUEST['update_date']);
	$pages[0]['person_id'] = $person_id;

	$T->assign('noindex',1); // don't index this page
	$T->assign('wiki', $pages[0]);
	$T->display('wiki_edit.tpl');
	exit();
}
############################################################################
# View, Edit, Save the discussion forum posts
function addReplies ($post_id, $posts) {
	if (empty($posts)) return array();
	foreach ($posts as $value) {
		if ($post_id == $value['parent_id']) {
			$replies = addReplies($value['post_id'], $posts);
			if (count($replies) > 0) $value['replies'] = $replies;
			$new_posts[] = $value;
		}
	}
	return $new_posts;
}
function formatDiscussion ($posts) {
	global $person_id, $user;
//	print_pre($user->id);
	$html = "";
	if (count($posts) == 0) return $html;
	foreach ($posts as $value) {
		$reply_html = formatDiscussion($value['replies']);
		$post_text = strip_tags($value['post_text'], "<b> <i>");
		$post_text = str_replace("\n", "<br \>", $post_text);
		$post_id = $value['post_id'];
		$html .= "
		<table class='grid'>
		<tr><td>
			<a name='post$post_id' />
			Posted by <a href='/profile.php?user_id=".$value['created_by']."'>".$value['given_name']." ".$value['family_name']." from ".$value['state_code']."</a> at ".$value['creation_date']."</td>
		<td align='right'>
		";
		if ($user->id == $value['created_by']) {
			if (empty($reply_html)) {
				$html .= "<a href='/person/$person_id&action=discussdelete&post_id=$post_id'>Delete</a>";
			}
			$html .= " <a href='/person/$person_id&action=discussedit&post_id=$post_id'>Edit</a>";
		}
		$html .= "
			<a href='/person/$person_id&action=discussedit&parent_id=$post_id'>Reply</a>
		</td></tr>
		<tr><td colspan=2>$post_text";
		if ($value['creation_date'] <> $value['update_date']) {
			$html .= "<div align='right'>Last changed: ".$value['update_date'] . "</div>";
		}
		$html .= "</td></tr></table>
		<ul class='reply'>$reply_html</ul>";
	}
//	$html .= "</table>";
	return $html;
}

if (in_array($action, array("discuss", "discussedit", "discussdelete"))) {
	include_class("DiscussPost");
	include_class("RefCodes");
	if ($action == "discussdelete") {
		require_login();
		DiscussPost::delete($_REQUEST['post_id']);
		redirect("/person/".$person_id);
	}

	if ($action == "discussedit") {
		require_login();
		$to = $person->listContributors();
		$relatives = $person->userDescendants();
		foreach($relatives as $row) $to[$row["user_id"]] = 1;

		if (isset($_REQUEST['save'])) {
			include_class("Watch");
			// Determine if we should send emails out and to whom
			//print_pre($_REQUEST);
			switch($_REQUEST["email"]) {
				case "S":
					# submitter only
					$to = array();
					$to[] = $person->data["created_by"];
					break;
				case "A":
					# all contributers
					//$to = $person->listContributors();
					// We already have this in $to from above
				case "R":
					# Relatives
					$relatives = $person->userDescendants();
					foreach($relatives as $row) $to[$row["user_id"]] = 1;
					break;
				default:
					$to = array();
			}
			if (count($to)) {
				//print_pre($_REQUEST);
				$dtype = RefCodes::getList("DiscussionType", $_REQUEST["dpost"]["post_type"]);
			
				$subject = $dtype.": ".$person->data["full_name"] ." on SharedTree";
				$body = "The following message was sent from my account on SharedTree regarding ".$person->data["full_name"] ."
- Follow link for details: http://www.sharedtree.com/person/$person_id
-----------------------------------------------------------------------
".$_REQUEST["dpost"]["post_text"];
				//$to = array("1"=>1);
				print_pre($_REQUEST);
				foreach($to as $key=>$value) {
					// Send email to
					$sendUser = new User($key);
					$sendUser->sendMessage($subject, $body);
				}
			}
			if($_REQUEST['post_message']) {
				# Save a new or existing post
				Watch::watchPerson($person_id, $_REQUEST["watch"]);
				DiscussPost::save($_REQUEST['dpost']);
			}
			redirect("/person/".$person_id);
		}
		$posts = DiscussPost::getPosts("post_id = ".(int)$_REQUEST['post_id']);
		$posts[0]['person_id'] = $person_id;

		$T->assign("users", User::getUsers($to));
		$T->assign("dtypes", RefCodes::getList("DiscussionType") );
		$T->assign('noindex',1); // don't index this page
		$T->assign('dpost', $posts[0]);
		$T->display('discuss_edit.tpl');
	}
	exit();
}

# Display the Biography
include_class("DiscussWiki");
$wiki = DiscussWiki::getWiki($person_id);
$wiki[0]['wiki_text'] = DiscussWiki::convertToHTML($wiki[0]['wiki_text']);
$T->assign('wiki', $wiki[0]);
$person->saveCalc("biography_size", strlen($wiki[0]['wiki_text']));

# Track the view
$listviews = $person->listViews();
$T->assign("listviews", $listviews);
$person->recordView();

if ($time == "") {
	# Display the Discussion posts
	include_class("DiscussPost");
	$posts = DiscussPost::getPosts("p.person_id = $person_id");
	$T->assign("posts", $posts);
	$person->saveCalc("forum_count", count($posts));
	//print_debug($posts);
	//$new_posts = addReplies(0,$posts);
	//$html = formatDiscussion($new_posts);
	//$T->assign('post_text', $html);

	# Display auditing and duplicates
	$T->assign("duplicates", $person->findMatches());

	$show_this_is_you = 1;
	if ($user->data['person_id'] > 0) $show_this_is_you = 0;
	if ($person->data['e']['DEAT']['event_date']) $show_this_is_you = 0;
	if (empty($user->id)) $show_this_is_you = 0;
	$T->assign("show_this_is_you", $show_this_is_you);
}

$desc_users = $person->userDescendants();
$T->assign("desc_users", $desc_users);
$person->saveCalc("related_users", count($desc_users));

# Determine if we should invite this person or not
$invite = 0;
$year = date("Y");
$oldyear = date("Y") - 100;
$youngyear = date("Y") - 13;
if ($person->data["birth_year"] > $oldyear && $person->data["birth_year"] < $youngyear) {
	# This person is betwen 13 and 100, see if they have already joined
	$invite = 1;
	foreach($desc_users as $row) {
		if ($row["trace_code"] == "X") {
			$invite = 0;
		}
	}
	if (isset($person->data["e"]["DEAT"])) $invite = 0;
}
$T->assign("invite", $invite);

include_class("Group");
$groups = Group::personGroups($person_id);
//print_pre($groups);
$T->assign("groups", $groups);

$temp = $db->select("SELECT image_id, event_id, image_order FROM tree_image WHERE person_id = $person_id");
$photos = array();
foreach($temp as $row) {
	if ($row["event_id"] > 0) $photos[$row["event_id"]] = $row;
	else $photos["P".$row["image_order"]] = $row;
}
//print_pre($photos);
$T->assign("photos", $photos);
$person->saveCalc("photo_count", count($photos));

###################################################
# Get family info
include_class("Family");
$ancestor_count = 0;
$parent_family = new Family($person->data['bio_family_id'], $time);
if ($parent_family->data["person1_id"] > 0) {
	$father = new Person($parent_family->data["person1_id"], $time);
	$T->assign('father',$father->data);
	$ancestor_count = $ancestor_count + 1 + (int)$father->data["ancestor_count"];
}
if ($parent_family->data["person2_id"] > 0) {
	$mother = new Person($parent_family->data["person2_id"], $time);
	$T->assign('mother',$mother->data);
	$ancestor_count = $ancestor_count + 1 + (int)$mother->data["ancestor_count"];
}
$person->saveCalc("ancestor_count", $ancestor_count);

$siblings = $parent_family->getChildren();
$T->assign("siblings", $siblings);
//print_pre($siblings);

$marriages = $person->getMarriages(true);
//print_pre($marriages);
if (is_array($marriages)) {
	$T->assign('marriages', $marriages);
}

$title = $person->data['full_name'];
if ($person->data['birth_year'] > 0) $title .= " (".$person->data['birth_year'].")";
if ($person->data['birth_year'] < 0) $title .= " (".-1*$person->data['birth_year']." B.C.)";

$T->assign("primary_person", $title);
$T->assign("title", $title);
//print_pre($person->data);
if ($user->id == 1 && false) {
echo "self: ".$person->data["trace"].$person->data["trace_distance"]."<br>";
echo "father: ".$father->data["trace"].$father->data["trace_distance"]."<br>";
echo "mother: ".$mother->data["trace"].$mother->data["trace_distance"]."<br>";
foreach ($marriages as $row1) {
	echo "spouse: ".$row1["trace"].$row1["trace_distance"]."<br>";
	foreach($row1["children"] as $row2) {
		echo "child: ".$row2["trace"].$row2["trace_distance"]."<br>";
	}
}

die();
}
$T->display('person_view.tpl');

?>
