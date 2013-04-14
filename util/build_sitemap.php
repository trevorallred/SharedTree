<?
require_once("../config.php");
require_once("../inc/main.php");

# Used to calculate birth years for individuals
echo "building site map<br>";


$dest_dir = "/var/www/sharedtree.com/";
$dest_dir = "/home/tallred/domains/openwillow.net/htdocs/";
$dest_dir = "sitemaps/";
$file_count = 0;
$query_limit = 5000;
$file_limit = 40000;

$x = 0; // track how many rows we've written
$next_pid = 0; // The next person_id we need to read from

foreach (glob($dest_dir."*") as $filename) {
   echo "removing $filename size " . filesize($filename) . "<br>";
   unlink($filename);
}

$read = 1;
while ($read > 0) {
	echo "Read $limit records starting from person_id &gt; $next_pid<br>";
	$sql = "SELECT person_id, DATE_FORMAT(max(update_date),'%Y-%m-%d') update_date FROM (
				SELECT p.person_id, p.update_date FROM tree_person p
				WHERE ".actualClause("p")."
				AND p.public_flag = 1 AND p.person_id > $next_pid
				UNION
				SELECT key_id person_id, update_date FROM tree_event
				WHERE table_type = 'P' AND update_date is not null AND ".actualClause()."
				and key_id > $next_pid
				and key_id IN (select person_id from tree_person where ".actualClause()." and public_flag = 1)
			) t group by person_id LIMIT 0, $query_limit";
	$data = $db->select($sql);
	$read = count($data); // count results returned, if > 0 then we'll read again later
	echo " found $read record(s)<br>";

	foreach ($data as $val) {
		if ($x >= $file_limit) {
			# Close this file and reopen a new one
			stopFile();
			$x = 0;
		}
		if ($x == 0) {
			# Open a new file before we write any new rows
			startFile();
		}
		writeToFile("<url><loc>http://www.sharedtree.com/person/".$val["person_id"]."</loc>
	      <lastmod>".$val["update_date"]."</lastmod>
	      <priority>.3</priority><changefreq>monthly</changefreq></url>");
		$next_pid = $val["person_id"];
		$x++;
	}
	unset($data);
	//if ($next_pid > 30000) $read = 0;
}
stopFile();

$today = date('Y-m-d');
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
   <sitemap><loc>http://www.sharedtree.com/sitemanual.xml</loc></sitemap>";
for($i=1; $i <= $file_count; $i++) {
	$xml .= "<sitemap><loc>http://www.sharedtree.com/sitemap$i.xml.gz</loc><lastmod>$today</lastmod></sitemap>";
}
$xml .= "</sitemapindex>";
file_put_contents ($dest_dir."sitemap.xml", $xml);
echo "saved index <br>DONE";

function startFile() {
	global $file_count, $f, $dest_dir, $filename;
	$file_count++;
	$filename = $dest_dir."sitemap$file_count.xml";
	// open file $file_count
	echo "opening file ${dest_dir}sitemap$file_count.xml<br>";
	$f = fopen($filename, "w");
	writeToFile("<?xml version=\"1.0\" encoding=\"UTF-8\"?><urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">");
}
function stopFile() {
	global $f, $filename;
	if ($filename == "") return false;
	echo "closing file<br>";
	writeToFile("</urlset>");
	fclose($f);
	# Compress file now
	file_put_contents("$filename.gz", gzencode( file_get_contents($filename),9));
	echo "compressed file $filename.gz<br>";
}
function writeToFile($text) {
	global $f;
	if ($text > "") fwrite($f, $text, strlen($text));
}

?>
