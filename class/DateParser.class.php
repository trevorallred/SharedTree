<?
class DateParser
{
	static public function getEstimate(&$datetext, &$curEst) {
		$est["ABOUT"] = "ABT";
		$est["ABT"] = "ABT";
		$est["AFT"] = "AFT";
		$est["AFTER"] = "AFT";
		$est["PAST"] = "AFT";
		$est["BEF"] = "BEF";
		$est["BEFORE"] = "BEF";
		foreach($est as $key=>$value) {
			$datetext = str_replace($key, "", $datetext, $count);
			if ($count > 0) $curEst = $value;
		}
		$datetext = trim($datetext);
	}

	static public function parseDate($datetext) {
		$d = strtoupper($datetext);
		$d = str_replace("BET ", "", $d);
		$d = str_replace("FROM ", "", $d);
		$d = str_replace(" AND ", "/", $d);
		$d = trim($d);

		$fyr = "([0-9]+)\/?[0-9]?";
		$fyr4 = "([0-9]{4})";
		$fmon = "([A-Z]{3,}\.?|[0-1]?[0-9])\/?[A-Z]?";
		$fm1 = "([0-1]?[0-9])";
		$fday = "([0-3]?[0-9])";

		if (preg_match("/^$fyr4\/$fyr4$/i", $d, $p)) return DateParser::compileDate($p[1], "00", "00"); // 1678/1679
		if (preg_match("/^$fyr4$/i", $d, $p)) return DateParser::compileDate($p[1], "00", "00"); // 1678
		if (preg_match("/$fmon $fday,? $fyr/i", $d, $p)) return DateParser::compileDate($p[3], $p[1], $p[2]); // Jan 1, 1945
		if (preg_match("/([1-2][0-9]{3})([0-1][0-9])([0-3][0-9])/i", $d, $p)) return DateParser::compileDate($p[1], $p[2], $p[3]); // 19831231
		if (preg_match("/".$fm1."[-\/]".$fday."[-\/]".$fyr4."/i", $d, $p)) return DateParser::compileDate($p[3], $p[1], $p[2]);
		if (preg_match("/".$fyr4."[-\/]".$fm1."[-\/]".$fday."/i", $d, $p)) return DateParser::compileDate($p[1], $p[2], $p[3]); 
		if (preg_match("@$fday?[ -\/]*$fmon?[ -\/]*$fyr@i", $d, $p)) return DateParser::compileDate($p[3], $p[2], $p[1]);
		if (preg_match("/".$fyr."[-\/]?$fmon?[-\/]?$fday?/i", $d, $p)) return DateParser::compileDate($p[1], $p[2], $p[3]);
		//echo "no match<br>";
		return "";
	}

	static public function compileDate($year, $month, $day) {
		if (is_numeric($month)) {
			if (is_numeric($day)) {
				if ($day <= 12 && $month >= 13) {
					# Our days and months seem to be reversed
					$temp = $day;
					$day = $month;
					$month = $temp;
				}
			}
		} else {
			# Month is still a text value, convert it to a number (1-12)
			# We should support other languages in the future
			$months['JAN'] = 1;
			$months['FEB'] = 2;
			$months['MAR'] = 3;
			$months['APR'] = 4;
			$months['MAY'] = 5;
			$months['JUN'] = 6;
			$months['JUL'] = 7;
			$months['AUG'] = 8;
			$months['SEP'] = 9;
			$months['OCT'] = 10;
			$months['NOV'] = 11;
			$months['DEC'] = 12;
			foreach($months as $key=>$value) {
				// Look for JAN and replace it with 1
				if (strstr($month, $key)) $month = $value;
			}
		}
		$month = (int)$month;
		$day = (int)$day;
		$year = (int)$year;

		if ($month > 0 && $day > 0) {
			#Check for valid dates
			if ($day > 31) $day = 31;
			if ($day == 31 && in_array($month, array(4,6,9,11))) $day = 30;
			if ($day > 29 && $month == 2) $day = 29;
			$is_leap = false;
			if ($year%4 == 0) $is_leap = true;
			if ($year%100 == 0) $is_leap = false;
			if ($year%400 == 0) $is_leap = true;
			if (!$is_leap && $month == 2 && $day >= 29) $day = 28;
		}
		if (strlen($day) == 1) $day = "0".$day; //change 9 to 09
		if (strlen($month) == 1) $month = "0".$month; //change 9 to 09

		$newdate = "$year-$month-$day";
		//echo "$newdate <br>";
		return $newdate; // 1991-12-31
	}
}
?>