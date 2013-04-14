<?

class RefCodes {
	static public function getList($ref_type, $value="") {
		global $db;
		if (empty($ref_type)) return false;
		
		if ($value > "") $where = "AND ref_code = '".fixTick($value)."'";
		else $where = "";

		$sql = "SELECT ref_code, meaning FROM ref_codes WHERE ref_type = '$ref_type' $where ORDER BY seq, meaning, ref_code";
		$data = $db->select($sql);
		if ($value > "") return $data[0]["meaning"];

		$data2 = array();
		foreach ($data as $value) {
			$data2[$value['ref_code']] = $value['meaning'];
		}
		return $data2;
	}
}
