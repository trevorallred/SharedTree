<?php
/**
 * Very flexible and extensible table array class
 *
 * Use this for creating data arrays and passing those arrays to a smarty template
 * The idea for this is to replace data.pinc
 *
 */

class dataTable {
	/**
	 * The main array for the data table that contains all of the columns, 
	 * rows, and column attributes for this table of information.
	 * [column][number] (0)
	 *         [label] (1)
	 *         [alignment] (2)
	 *         [total] (3)
	 *         [data][row] (4)
	 *         [datatype] (5) Number or CaseInsensitiveString 
	 *
	 */
	private $xdata = array();
	private $current_column = 0;
	private $current_row = 0;
	
	const SORT_ISTRING 	= 'CaseInsensitiveString';
	const SORT_NUMBER	= 'Number';
	const SORT_NONE		= 'None';
	
	const IDNUM			= 0;
	const LABEL			= 1;
	const ALIGN			= 2;
	const TOTAL			= 3;
	const DATA			= 4;
	const DATATYPE		= 5;
	
	/**
	 * @param integer $column_num
	 * @todo
	 */
	public function setColNum($column_num=NULL) {
		if (is_null($column_num)) return; // Don't change columns
		if ($column_num >= 0) $this->current_column = $column_num;
		else {
			//Increase the size of the array by one
		 	$i = count($this->xdata);
		 	//echo $i . "<br>";
			$this->current_column = $i;
			$this->xdata[$this->current_column][self::IDNUM] = $this->current_column;
		}
	}
	public function setColName($name, $alignment="right", $column_num=-1, $sort_type="CaseInsensitiveString") {
		$this->setColNum($column_num);
		$this->xdata[$this->current_column][self::LABEL] = $name;
		$this->xdata[$this->current_column][self::ALIGN] = $alignment;
		$this->setTotal("");
		$this->setColType($sort_type, NULL);
	}
	public function setTotal($value, $column_num=NULL) {
		$this->setColNum($column_num);
		$this->xdata[$this->current_column][self::TOTAL] = $value;
	}

	public function setRowNum($row_num=NULL) {
		if (is_null($row_num)) return; // Don't change columns
		if ($row_num >= 0) $this->$current_row = $row_num;
		else {
			//Increase the size of the array by one
			$i = (array_key_exists(self::DATA, $this->xdata[$this->current_column])) ? count($this->xdata[$this->current_column][self::DATA]) : 0;
			$this->current_row = $i;
			$this->xdata[$this->current_column][self::IDNUM] = $this->current_row;
		}
	}
	public function AddRow() {
		$this->setRowNum(-1);
	}
	
	/**
	 * Insert all data into the table all at once
	 * @param array $data		associative array of data that must be in the following format:
	 *							$data[<row_number>][<column_number>]['value']
	 *																['column_num'] (optional)
	 */
	public function addData($data) {
		foreach ($data as $row) {
			$this->addFullRow($row);
		}
	}
	
	public function addFullRow($data) {
		$this->setRowNum(-1);
		foreach ($data as $colNum => $row) {
			$this->setCell($row, $colNum);
		}
	}
	
	public function setCell($value, $column_num=NULL, $row_num=NULL) {
		$this->setColNum($column_num);
		$this->setRowNum($row_num);
		$this->xdata[$this->current_column][self::DATA][$this->current_row] = $value;
		//echo $this->current_row . "<br>";
	}

	/**
	 * Set the column type used for sorting later on
	 * @param $sort_type If sorttype is blank then try to guess the column type 
	 *                  based on the information contained in the rows
	 * @param $column_num If column number is set, then change to that column before starting
	 *                    If the column number = "ALL" then try to guess ALL columns' data types
	 * @todo Support the ALL keyword to guess all column types
	 */
	public function setColType($sort_type="", $column_num=NULL) {
		//return;
		if ($column_num == "ALL") {
			// Process all columns...not supported yet and not recommended
		} else {
			$this->setColNum($column_num);
			if ( !empty($sort_type) ) {
				$this->xdata[$this->current_column][self::DATATYPE] = $sort_type;
			} else {
				foreach ($this->xdata[$this->current_column][self::DATA] as $key => $value) { 
					if(arraypos($attribs,$key) < 0 && $key != 'status' && $key) {
						$tvar = preg_replace("/[0-9]/is","",$this->xdata[$this->current_column][self::DATA][$key]);
						$tvar = preg_replace("/<[^>]*>/is","",$tvar);
						$tvar = preg_replace("/<\/[^>]*>/is","",$tvar);
						if (!trim(preg_replace("/[0-9]/is","",$tvar)))
							$this->xdata[$this->current_column][self::DATATYPE] = self::SORT_NUMBER;
						else
							$this->xdata[$this->current_column][self::DATATYPE] = self::SORT_ISTRING;
					}
				}
			}
		}
	}
	
	public function getColTypes() {
		$columns = '';
		$data = $this->getData();
		foreach ($data as $col) {
			$columns .= "\"{$col[self::DATATYPE]}\",";
		}
		$columns = ereg_replace(',$', '', $columns);
		return $columns;
	}
	
	/**
	 * Prints the preformatted array of data
	 * 
	 * This is especially useful in debugging
	 */
	public function print_data() {
		echo ("<pre>");
		print_r($this->xdata);
		echo ("</pre>");
	}
	
	public function getData() {
		return $this->xdata;
	}
}
?>