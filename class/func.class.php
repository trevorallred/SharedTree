<?

abstract class func
{
	public function Show($arr)
	{
		$d = new dump($arr);
		echo $d->toHtml();
	}

	/**
	* @return string
	* @param mixed $arr
	* @param boolean $strip
	* @desc return debug information in a readable format
	*/
	public function getDebug($arr, $strip = false) {
		ob_start();
		func::Show($arr);
		$value = ob_get_clean();
		return ($strip) ? "<pre>" . htmlspecialchars($value) . "</pre>" : $value;
	}

	public function flatten($str) {
		return preg_replace("/\n|\r/",'\n',$str);
	}

	public function fixTick(&$str) {
		$str = str_replace("\'","'",$str);
		$str = str_replace("'","''",$str);
		return $str;
	}

	/**
	* @return array
	* @param object $classObj
	* @param function $functionName
	* @param limit $maxResults
	* @desc PHP Query Paging Function
	*/
	function nextPrevQuery($classObj,$functionName,$maxResults=10)
	{
		if(!isset($_REQUEST['page'])){
			$page = 1;
		} else {
			$page = $_REQUEST['page'];
		}

		// required to get the right variables out of the request uri
		$uri = parse_url($_SERVER['REQUEST_URI']);
		//$params = $this->getUriQueryVariables($uri,$uriQueryString);
		$from = (($page * $maxResults) - $maxResults);

		// Call the class and function
		$returnSet['qResults'] = $classObj->$functionName($from,$maxResults);

		$totalResults = $returnSet['qResults']['totalRows'];
		unset($returnSet['qResults']['totalRows']);

		$totalPages = ceil($totalResults / $maxResults);
		if($page>$totalPages){
			$from = $totalPages;
			$page = $totalPages;
		}
			//$returnSet['qResults'] = $this->$searchFunction($username,$folder,$from,$maxResults,$countOnly);

		if($totalPages==$page){
			$through = $totalResults;
		} else {
			//if((count($returnSet['qResults'])<$totalPages) && $through==''){
			$through = ($maxResults==1)?$page:($page*$maxResults);
		}

		//$this->debug('$count($returnSet[\'qResults\']) :: '.count($returnSet['qResults']));
		//$this->debug('$totalPages :: '.$totalPages);
		//$this->debug('$through :: '.$through);

		$from = (($page * $maxResults) - $maxResults);
		$from = ($from+1);

		// set the empty buttons
		$returnSet['buttons'] = array('viewing'=>'','prev'=>'','pageNum'=>'','next'=>'');
		$returnSet['buttons']['viewing'] = (($from<0)?'0':$from)." - ".$through." of ".$totalResults;

		if($page > 1){
			$prev = ($page - 1);
			$returnSet['buttons']['prev'] = "<a href=\"".$_SERVER['PHP_SELF']."?".func :: getUriQueryString()."&page=".$prev."\">&lt;&lt; Previous</a>&nbsp;";
		} else {
			$returnSet['buttons']['prev'] = "&lt;&lt; Previous&nbsp;";
		}


		// set the limit of pages diplayed in the prev / next area
		$limit = 5;
		if(($limit - (2 * floor($limit/2))) == 0){
			// is even
			$minusFirst = 1;
			$addSecond = 1;
		} else {
			// is odd
			$minusFirst = 2;
			$addSecond = 1;
		}

		$midLimit = round($limit/2,0);
		$lowPageNum = ($page - $midLimit);
		$highPageNum = ($page + $midLimit);

		if(($lowPageNum > 0) == false){
			$lowPageNum = 1;
			$highPageNum = $limit;
		} else {
			$highPageNum = $highPageNum - $minusFirst;
		}

		if($page >= (($totalPages-$midLimit)+$minusFirst)){
			$pageRange = ($totalPages-$page);
			$lowPageNum = ($totalPages-$limit) + $addSecond;
		}

		for($i = 1; $i <= $totalPages; $i++){
		// center the page count so only a limited number ever show
			if($page == $i){
				$returnSet['buttons']['pageNum'] .= (($i<=9)?"":"")."<b>".$i."</b>&nbsp;";
			} else {
				if($lowPageNum<=$i && $highPageNum>=$i){
					$returnSet['buttons']['pageNum'] .= (($i<=9)?"":"")."<a href=\"".$_SERVER['PHP_SELF']."?".func :: getUriQueryString()."&page=".$i."\">".$i."</a>&nbsp;";
					$limiter++;
				}
			}
		}

		if($page < $totalPages){
			$next = ($page + 1);
			$returnSet['buttons']['next'] = "<a href=\"".$_SERVER['PHP_SELF']."?".func :: getUriQueryString()."&page=".$next."\">Next &gt;&gt;</a>";
		} else {
			$returnSet['buttons']['next'] = "Next &gt;&gt;";
		}

		//$this->debug($returnSet,1);
		return $returnSet;
	}

	function getUriQueryString()
	{
		return ereg_replace('(&page=[0-9])','',$_SERVER['QUERY_STRING']);
	}

	/**
	 * Send an email
	 *
	 * @todo Move this to another function library. It shouldn't be here in the db class
	 */
	function sendDebugEmail($to, $toName, $from, $fromName, $subject, $body) {
		global $HTTP_HOST;
		$domain = preg_replace("/(.*)\.(.*\..*)/","$2",$HTTP_HOST);
		$headers .= "From: $fromName <$from>\n";
		$headers .= "To: $toName <$to>\n";
		$headers .= "X-Sender: <postmaster@$domain>\n";
		$headers .= "X-Mailer: $domain Emailer\n";
		$headers .= "Return-Path: <postmaster@$domain>\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";

		mail($to, $subject, $body, $headers);
	}

function datediff($interval, $date1, $date2) {

	$seconds = $date2 - $date1;

   switch($interval) {
       case "y":
           list($year1, $month1, $day1) = split('-', date('Y-m-d', $date1));
           list($year2, $month2, $day2) = split('-', date('Y-m-d', $date2));
           $time1 = (date('H',$date1)*3600) + (date('i',$date1)*60) + (date('s',$date1));
           $time2 = (date('H',$date2)*3600) + (date('i',$date2)*60) + (date('s',$date2));
           $diff = $year2 - $year1;
           if($month1 > $month2) {
               $diff -= 1;
           } elseif($month1 == $month2) {
               if($day1 > $day2) {
                   $diff -= 1;
               } elseif($day1 == $day2) {
                   if($time1 > $time2) {
                       $diff -= 1;
                   }
               }
           }
           break;
       case "m":
           list($year1, $month1, $day1) = split('-', date('Y-m-d', $date1));
           list($year2, $month2, $day2) = split('-', date('Y-m-d', $date2));
           $time1 = (date('H',$date1)*3600) + (date('i',$date1)*60) + (date('s',$date1));
           $time2 = (date('H',$date2)*3600) + (date('i',$date2)*60) + (date('s',$date2));
           $diff = ($year2 * 12 + $month2) - ($year1 * 12 + $month1);
           if($day1 > $day2) {
               $diff -= 1;
           } elseif($day1 == $day2) {
               if($time1 > $time2) {
                   $diff -= 1;
               }
           }
           break;
       case "w":
           $diff = floor($seconds / 604800);
           break;
       case "d":
           $diff = floor($seconds / 86400);
           break;
       case "h":
           $diff = floor($seconds / 3600);
           break;
       case "i":
           $diff = floor($seconds / 60);
           break;
       case "s":
           $diff = $seconds;
           break;
   }
   return $diff;
}

function oracleDate( $time ){
	return date( "d-M-y", $time );
}

function oracleDateTime( $time ){
	return date( "d-M-y H:i:s", $time );
}

	/**
	 * Checks spelling of $string. Whole phrases can be sent in, too, and each word will be checked.
	 * Returns an associative array of mispellings and their suggested spellings
	 * @param string $string Phrase to be checked
	 * @return array
	 */
	function checkSpelling ( $string )
	{
		// Make word list based word boundries
		$wordlist = preg_split('/\s/',$string);

		// Filter words
		$words = array();
		for($i = 0; $i < count($wordlist); $i++)
		{
			$word = trim($wordlist[$i]);
			if(!preg_match('/[A-Za-z]/', $word))
				continue;
			$word = preg_replace('/[^\w\']*(.+)/', '\1', $word);
			$word = preg_replace('/([^\W]*)[^\w\']*$/', '\1', $word);
			$word = trim($word);
			if(!in_array($word, $words, true))
				$words[] = $word;

		}
		$misspelled = $return = array();
		$int = pspell_new('en');

		foreach ($words as $value)
			if (!pspell_check($int, $value))
				$misspelled[] = $value;

		foreach ($misspelled as $value)
			$return[$value] = pspell_suggest($int, $value);

		return $return;
	}

}
?>