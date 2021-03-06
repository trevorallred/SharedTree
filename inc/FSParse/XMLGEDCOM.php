<?php
/**
 * This library will parse the FamilySearch API XML results
 * into a data model.  It will also allow for the conversion
 * of the model into GEDCOM 5.5.1.
 *
 * PHP FamilySearch API Parser
 * Copyright (C) 2007  Neumont University
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * See LICENSE.txt for the full license.  If you did not receive a
 * copy of the license with this code, you may find a copy online
 * at http://www.opensource.org/licenses/lgpl-license.php\
 *
 * @see XMLGEDCOMLib.vsd or XMLGEDCOMLib.gif for a class diagram
 * @author Cameron Thompson
 * @author John Condrey
 * @author Aeris Forrest
 * @author John Finlay
 */

/**
 * Set the GEDCOM compliance level for the GEDCOM to be generated.
 * Possible values are 5.5 or 5.6.  Setting to 5.5 will produce the 
 * most compatible GEDCOM for use with other programs.  Using 5.6 will
 * ensure the least amount of data loss.
 */
define('GEDCOM_COMPLIANCE_LEVEL', 5.6);

/**
 * This is the main class, all work is done through this
 * send it XML with parseXML($theXMLasAString)
 * then get the GEDCOM with getGEDCOM()
 */
class XmlGedcom {

	var $persons = array();
	var $handler = array(); // store method names
	var $tagStack = array(); //store
	var $lastTagName;
	var $lastType;
	var $error = null;
	var $proxy = null;
	var $xml;
	var $tempId = null;
	
	var $gedcomRecords = array();

	/**
	 * constructor, set up class
	 */
	function XmlGedcom() {
		//methods to call for opening("start") Person tag
		$this->handler["NOTE"]["tag"] = "openNote";
		$this->handler["CITATION"]["tag"] = "openCitation";
		$this->handler["PERSON"]["tag"] = "openPerson";
		$this->handler["CHILD"]["tag"] = "openChild";
		$this->handler["PARENT"]["tag"] = "openParent";
		$this->handler["SPOUSE"]["tag"] = "openSpouse";
		$this->handler["ORDINANCE"]["tag"] = "openOrdinance";
		$this->handler["FACT"]["tag"] = "openFact";
		$this->handler["EVENT"]["tag"] = "openEvent";
		$this->handler["RELATIONSHIP"]["tag"] = "openRelationship";
		$this->handler["NAME"]["tag"] = "openName";
		$this->handler["GENDER"]["tag"] = "openGender";
		$this->handler["PLACE"]["tag"] = "openPlace";
		$this->handler["DATE"]["tag"] = "openDate";
		$this->handler["FORM"]["tag"] = "openForm";
		$this->handler["PIECES"]["tag"] = "openPieces";
		$this->handler["CONTRIBUTOR"]["tag"] = "openContributor";
		$this->handler["ERROR"]["tag"] = "openError";
		$this->handler["NS2:ERROR"]["tag"] = "openError";
		$this->handler["ID"]["tag"] = "openId";

		$this->handler["NOTE"]["data"] = "dataNote";
		$this->handler["CITATION"]["data"] = "dataCitation";
		$this->handler["ORIGINAL"]["data"] = "dataOriginal";
		$this->handler["FULLTEXT"]["data"] = "dataFullText";
		$this->handler["NORMALIZED"]["data"] = "dataNormalized";
		$this->handler["DETAIL"]["data"] = "dataDetail";
		$this->handler["VALUE"]["data"] = "dataValue";
		$this->handler["AGE"]["data"] = "dataAge";
		$this->handler["TITLE"]["data"] = "dataTitle";
		$this->handler["DESCRIPTION"]["data"] = "dataDescription";
		$this->handler["ERROR"]["data"] = "dataError";
		$this->handler["NS2:ERROR"]["data"] = "dataError";
		$this->handler["MESSAGE"]["data"] = "dataError";
		$this->handler["CODE"]["data"] = "dataCode";
		$this->handler["ID"]["data"] = "dataId";
		$this->handler["LIVING"]["data"] = "dataLiving";
		//-- for summary gender
		$this->handler["GENDER"]["data"] = "valueGender";

		$this->handler["xg_gender"] = "valueGender";
		$this->handler["xg_namepieces"] = "valuePieces";

		$this->handler["prefix"] = "piecePrefix";
		$this->handler["suffix"] = "pieceSuffix";
		$this->handler["given"] = "pieceGiven";
		$this->handler["family"] = "pieceFamily";
		$this->handler["other"] = "pieceOther";
	}
	
	/**
	 * returns an array of all of the persons who were parsed from the XML
	 *
	 * @return array
	 */
	function getPersons(){
		return $this->persons;
	}

	/**
	 * Get an XG_Person object for the person with the given ID that has been parsed from XML
	 *
	 * @param string $id
	 * @return XG_Person
	 */
	function &getPerson($id) {
		$person = null;
		//print "|$id|";
		//foreach($this->persons as $key=>$p) print "[$key] ";
		if (isset($this->persons[$id])) {
			return $this->persons[$id];
		}
		if ($this->proxy!=null) {
			$result = $this->proxy->getPersonById($id,"view=summary");
			$this->parseXml($result);
			if (!empty($this->error)) {
				print "<b style=\"color:red;\">".$this->error->getMessage()."</b><br />";
				print htmlentities($result);
			}
			if (isset($this->persons[$id])) return $this->persons[$id];
		}
		return $person;
	}

	function setProxy(&$proxy) {
		$this->proxy=$proxy;
	}

	function getProxy() {
		return $this->proxy;
	}

	function clearPersons() {
		$this->persons = array();
	}

	/**
	 * send FamilySearch's XML into the class
	 * parsed using SAX
	 *
	 * @param String $xml - xml as one solid string
	 */
	function parseXml($xml){
		$xml_parser = xml_parser_create();
		@xml_set_object($xml_parser, $this);
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "data");
		if (!xml_parse($xml_parser, $xml)) {
			printf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser));
			print "problems parsing the following XML<br /><br />".htmlentities($xml);
		}
		xml_parser_free($xml_parser);
	}

	/**
	 * Convert XmlGedcom to Xml
	 */
	function toXml(){
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
		$xml.="<familytree version=\"1.0\" xmlns=\"http://api.familysearch.org/familytree/v1\">\n";
		if(!empty($this->persons)){
			$xml.="<persons>\n";
			foreach($this->persons as $person){
				$xml.=$person->toXml();
			}
			$xml.="</persons>\n";
		}
		$xml.="</familytree>";
		return $xml;
	}

	/**
	 *  gets the GEDCOM for the indi record as one solid string with linebreaks
	 */
	function getIndiGedcom(){
		$gedcom = "";
		/* @var $person XG_Person */
		foreach($this->persons as $person){
			$gedcom = $gedcom.$person->getIndiGedcom()."\r\n";
		}
		return $gedcom;
	}
	
	/**
	 * Gets a GEDCOM record for the given GEDCOM id
	 * This is a convenience method for working with the resulting GEDCOM
	 * data after it has been converted from FamilySearch
	 *
	 * @param String $id
	 * @return String
	 */
	function getGedcomRecord($id) {
		if (isset($this->gedcomRecords[$id])) return $this->gedcomRecords[$id];
		$gedcom = "";
		if ($id{0}=="I") $id = substr($id, 1);
		$person = $this->getPerson($id);
		if (!is_null($person)) {
			$gedcom = $person->getIndiGedcom();
			$this->gedcomRecords[$id] = $gedcom;
			$this->gedcomRecords["I".$id] = $gedcom;
			
			$fams = $person->getFamCGedcom();
			foreach($fams as $fam) {;
				$this->gedcomRecords[$fam['id']] = $fam['gedcom'];
			}
			$fams = $person->getFamSGedcom();
			foreach($fams as $fam) {;
				$this->gedcomRecords[$fam['id']] = $fam['gedcom'];
			}
		}
		return $gedcom;
	}

	/**
	 * Build a search/match query for the given person
	 *
	 * @param Person $person
	 * @return string
	 */
	function buildSearchQuery(&$person, $type = '') {
		if(!is_object($person)) return "";
		$id = $person->getXref();	//get the individual's id
		$personName = $person->getGivenNames();
		$query = $type."givenName=".urlencode($personName);
		$personName = $person->getSurname();
		$query .= "&".$type."familyName=".urlencode($personName);
		$birthdate = $person->getBirthDate(false);
		if (!empty($birthdate)) $query.="&".$type."birthDate=".urlencode($birthdate->date1->Format("A O E"));
		$birthPlace = $person->getBirthPlace();
		if (!empty($birthPlace)) $query.="&".$type."birthPlace=".urlencode($birthPlace);
		$deathdate = $person->getDeathDate(false);
		if (!empty($deathdate)) $query.="&".$type."deathDate=".urlencode($deathdate->date1->Format("A O E"));
		$deathPlace = $person->getDeathPlace();
		if (!empty($deathPlace)) $query.="&".$type."deathPlace=".urlencode($deathPlace);
		
		if (!empty($type)) return $query;
		
		$gender = $person->getSex();
		if ($gender=='M') $query.="&".$type."gender=male";
		if ($gender=='F') $query.="&".$type."gender=female";
		
		$spouse = $person->getCurrentSpouse();
		if (!is_null($spouse)) {
			$query .= "&".XmlGedcom::buildSearchQuery($spouse, 'spouse.');
		}
		
		$family = $person->getPrimaryChildFamily();
		if (!is_null($family)) {
			$father = $family->getHusband();
			if (!is_null($father)) {
				$query .= "&".XmlGedcom::buildSearchQuery($father, 'father.');
			}
			
			$mother = $family->getWife();
			if (!is_null($mother)) {
				$query .= "&".XmlGedcom::buildSearchQuery($mother, 'mother.');
			}
		}

		return $query;
	}

	/** 
	 * Convert person_class to XG_Person
	 * Add new XG_Person to Persons
	 * @param Person	The PGV person to convert
	 * @return XG_Person	An XG_Person representation of the PGV Person
	 */
	function &addPGVPerson(&$person_class){
		global $eventHandler;
		global $factHandler;
		global $ordinanceHandler;
		global $GEDCOM, $SERVER_URL, $username;

		$NewXGPerson = new XG_Person();
		$NewXGPerson->setTempId($person_class->getXref());
		
		if (empty($username)) {
			$username = '';
			if (!empty($this->proxy)) $username = $this->proxy->getUserName();
		}
		
		if (!empty($username)) $NewXGPerson->addAlternateId(array('id'=>$person_class->xref, 'type'=>$username.':GEDCOM:'.$GEDCOM));

		/* @var $person_class Person */
		/* @var $family Family*/
		/* @var $child Person*/
		
		if ($person_class->isDead()) $NewXGPerson->setLiving("false");
		else $NewXGPerson->setLiving("true");
		
		$person_class->add_family_facts(false);
		
		$globalfacts = $person_class->getGlobalFacts();
		foreach($globalfacts as $g=>$factObj) {
			/* XG_Name */
			/* @var $factObj Event */
			if ($factObj->getTag()=='NAME') {
				$XGAssertion = new XG_Name();
				$namerec = $factObj->getGedComRecord();
				$XGAssertion->setPerson($NewXGPerson);
				$XGAssertion->addRecordInfo($namerec, $person_class);
				$XGForm = new XG_Form();
				$XGNamePiece = new XG_NamePieces();
				$XGNamePiece->addFamily(get_gedcom_value("SURN",2,$namerec,'',false));
				$XGNamePiece->AddGiven(get_gedcom_value("GIVN",2,$namerec,'',false));
				$XGNamePiece->AddPrefix(get_gedcom_value("PREN",2,$namerec,'',false));
				$XGNamePiece->AddSuffix(get_gedcom_value("SUFN",2,$namerec,'',false));
				$XGForm->setFullText(get_gedcom_value("NAME",1,$namerec,'',false));
				$XGForm->setPieces($XGNamePiece);
				$XGAssertion->addForm($XGForm);
				$NewXGPerson->addAssertion($XGAssertion);
				/* /XG_Name */
			}
			if ($factObj->getTag()=='SEX') {
				/* XG_Gender */
				$XGAssertion = new XG_Gender();
				$genderec = $factObj->getGedComRecord();
				if ($person_class->getSex()=="M") $XGAssertion->setGender("Male");
				else if ($person_class->getSex()=="F") $XGAssertion->setGender("Female");
				else $XGAssertion->setGender("Unknown");
				$XGAssertion->setPerson($NewXGPerson);
				$XGAssertion->addRecordInfo($genderec, $person_class);
				$NewXGPerson->addAssertion($XGAssertion);
			/* /XG_Gender */
			}
		}

		$indifacts = $person_class->getIndiFacts();
		foreach($indifacts as $i=>$factObj) {

			/* @var $factObj Event */
			if (is_object($factObj)) $factrec = $factObj->getGedcomRecord();
			else $factrec = $fact[1];

			$fact = $factObj->getTag();
			if ($fact=='SSN') continue;
			
			/* Alternate Ids */
			if ($fact=="REFN") {
				$type = $factObj->getType();
				//-- only include REFNs with types
				if (!empty($type)) {
					$id = array('type'=>$type, 'id'=>$factObj->getDetail());
					$NewXGPerson->addAlternateId($id);
				}
				if ($type=="FamilySearch") {
					$NewXGPerson->setID($factObj->getDetail());
				}
			}
			
			/* GEDCOM GUID */
			if ($fact=="_UID") {
				$id = array('type'=>'GUID', 'id'=>$factObj->getDetail());
				$NewXGPerson->addAlternateId($id);
			}
			
			/* XG_Event*/
			$xmltype = array_search($fact, $eventHandler);
			if ($xmltype!==false) {
				$XGAssertion = new XG_Event();
				$XGAssertion->setPerson($NewXGPerson);
				$XGAssertion->addType($factrec, $person_class, $xmltype);
				$XGAssertion->addDate($factrec, $person_class);
				$XGAssertion->addPlace($factrec, $person_class);
				$XGAssertion->addSpouse($factrec, $person_class);
				$XGAssertion->addScope($factrec, $person_class);
				$XGAssertion->addTitle($factrec, $person_class);
				$XGAssertion->addRecordInfo($factrec, $person_class);
				$NewXGPerson->addAssertion($XGAssertion);
			}
			/* /XG_Event */

			/* XG_Ordinance */
			$xmltype = array_search($fact, $ordinanceHandler);
			if($xmltype!==false) {
				$XGAssertion = new XG_Ordinance();
				$XGAssertion->setPerson($NewXGPerson);
				$XGAssertion->addType($factrec, $person_class, $xmltype);
				$XGAssertion->addDate($factrec, $person_class);
				$XGAssertion->addPlace($factrec, $person_class);
				$XGAssertion->addSpouse($factrec, $person_class);
				$XGAssertion->addScope($factrec, $person_class);
				$temple = get_gedcom_value("TEMP", 2, $factrec, '', false);
				$XGAssertion->setTemple($temple);
				$XGAssertion->addRecordInfo($factrec, $person_class);
				$NewXGPerson->addAssertion($XGAssertion);
			}
			/* /XG_Ordinance */

			/* XG_Fact */
			$xmltype = array_search($fact, $factHandler);
			if ($xmltype!==false) {
				$XGAssertion = new XG_Fact();
				$XGAssertion->setPerson($NewXGPerson);
				$XGAssertion->addType($factrec, $person_class, $xmltype);
				$XGAssertion->addDate($factrec, $person_class);
				$XGAssertion->addPlace($factrec, $person_class);
				$XGAssertion->addSpouse($factrec, $person_class);
				$XGAssertion->addScope($factrec, $person_class);
				$XGAssertion->addTitle($factrec, $person_class);
				$XGAssertion->addRecordInfo($factrec, $person_class);
				$XGAssertion->setDetail($factObj->getDetail());
				$NewXGPerson->addAssertion($XGAssertion);
			}
			/* /XG_Fact */
		}

		/* XG_Note */
		$notes = $person_class->getOtherFacts();
		/* @var $note XG_Note
		foreach($notes as $n=>$factObj)
		{
			if (is_object($factObj)) $noterec = $factObj->getGedcomRecord();
			else $noterec = $fact[1];
			$note = get_gedcom_value("NOTE", 1, $noterec, '', false);
			if (!empty($note)) {
				$XGAssertion = new XG_Fact();
				$XGNote = new XG_Note();
				$XGNote->setNote($note);
				$XGAssertion->addNote($XGNote);
				$XGAssertion->setPerson($NewXGPerson);
				$XGAssertion->setType("Notes");
				$XGAssertion->addDate($noterec, $person_class);
				$XGAssertion->addPlace($noterec, $person_class);
				$XGAssertion->addSpouse($noterec, $person_class);
				$XGAssertion->addScope($noterec, $person_class);
				$XGAssertion->addTitle($noterec, $person_class);
				$XGAssertion->addRecordInfo($noterec, $person_class);
				$NewXGPerson->addAssertion($XGAssertion);
			}
		}
		/* /XG_Note */
		
		/* Lineage information*/
		$families = $person_class->getChildFamilies();
		foreach($families as $f=>$family){
			$father = $family->getHusband();
			$mother = $family->getWife();
			if(!empty($father)){
				$XGAssertion = new XG_Fact();
				$XGAssertion->setPerson($NewXGPerson);
				$parentRefF = new XG_PersonRef();
				$parentRefF->setRef($father->xref);
				$parentRefF->setRole("father");
				$XGAssertion->setParent($parentRefF);
				$XGAssertion->setScope("parent-child");
				$XGAssertion->setType("Lineage");
				$XGAssertion->addRecordInfo('', $family);						
				$NewXGPerson->addAssertion($XGAssertion);
				$NewXGPerson->addParent($parentRefF);
			}
			if(!empty($mother)){
				$XGAssertion = new XG_Fact();
				$XGAssertion->setPerson($NewXGPerson);
				$parentRefM = new XG_PersonRef();
				$parentRefM->setRef($mother->xref);
				$parentRefM->setRole("mother");
				$XGAssertion->setParent($parentRefM);
				$XGAssertion->setScope("parent-child");
				$XGAssertion->setType("Lineage");
				$XGAssertion->addRecordInfo('', $family);				
				$NewXGPerson->addAssertion($XGAssertion);
				$NewXGPerson->addParent($parentRefM);
			}
		}

		$families = $person_class->getSpouseFamilies();
		foreach($families as $f=>$family){
			/* Children */
			$children = $family->getChildren();
			foreach($children as $c=>$child){
				$XGAssertion = new XG_Fact();
				$XGAssertion->setPerson($NewXGPerson);
				$childRef = new XG_PersonRef();
				$childRef->setRef($child->xref);
				if($child->getSex() == "M")$childRef->setRole("son");
				if($child->getSex() == "F")$childRef->setRole("daughter");
				$XGAssertion->setParent($childRef);
				$XGAssertion->setScope("parent-child");
				$XGAssertion->setType("Lineage");
				$XGAssertion->addRecordInfo('', $family);
				$NewXGPerson->addAssertion($XGAssertion);
				$NewXGPerson->addChild($childRef);
			}
			/* Spouse */
			$spouse = $family->getSpouse($person_class);
			if(!empty($spouse)){
				$XGAssertion = new XG_Relationship();
				$XGAssertion->setPerson($NewXGPerson);
				$spouseRef = new XG_PersonRef();
				$spouseRef->setRef($spouse->xref);
				if($spouse->getSex() == "M")$spouseRef->setRole("man");
				if($spouse->getSex() == "F")$spouseRef->setRole("woman");
				$XGAssertion->setSpouse($spouseRef);
				$XGAssertion->setScope("couple");
				$XGAssertion->addRecordInfo('', $family);
				$NewXGPerson->addAssertion($XGAssertion);
				$NewXGPerson->addSpouse($spouseRef);
			}
		}
		$this->persons[$NewXGPerson->getTempId()] = $NewXGPerson;
		return $NewXGPerson;
	}

	/**
	 *  gets the GEDCOM for the fam record where person is a child as one solid string with linebreaks
	 */
	function getFamCGedcom(){
		$gedcom = "";
		$famids = array();
		/* @var $person XG_Person */
		foreach($this->persons as $person){
			$fams = $person->getFamCGedcom();
			foreach($fams as $fam) {
				if (!isset($famids[$fam['id']])) {
					$gedcom = $gedcom.$fam['gedcom'];
					$famids[$fam['id']] = true;
				}
			}
		}
		return $gedcom;
	}

	/**
	 *  gets the GEDCOM for the fam record where person is a spouse/parent as one solid string with linebreaks
	 */
	function getFamSGedcom(){
		$gedcom = "";
		$famids = array();
		foreach($this->persons as $person){
			$fams = $person->getFamSGedcom();
			foreach($fams as $f=>$fam) {
				if (!isset($famids[$fam['id']])) {
					$gedcom = $gedcom.$fam['gedcom'];
					$famids[$fam['id']] = true;
				}
			}
		}
		return $gedcom;
	}

	//main parser methods

	//Tag opened function
	function startElement($parser, $name, $attrs) {
		$this-> lastTagName = $name;
		
		if (isset($attrs["TYPE"])){
			$this->lastType = $attrs["TYPE"];
		}
		if (isset($this->handler[$name]["tag"])){
			call_user_func(array(&$this,$this->handler[$name]["tag"]), $attrs);
		}
	}

	//tag closed function
	function endElement ($parser, $name) {

		if ($name == "PERSON"){
			$person = end($this->tagStack);
			$this->persons[$person->getID()] = $person;
		}

		if ($name!="ID" && isset($this->handler[$name]["tag"])) {
			array_pop($this->tagStack);  // pop top object off the stack when finished with it.
		}
	}

	//tag data function
	function data($parser, $data) {

		if (isset($data) && trim($data) != ""){
			$tagName = $this->lastTagName;
			if (isset($this->handler[$tagName]["data"])){
				call_user_func(array(&$this, $this->handler[$tagName]["data"]),$data);
			}
		}
	}

	//function for each relevent open element of XML
	function openError($attrs) {
		$this->error = new XG_Error();
		$this->error->setCode($attrs["CODE"]);
	}
	function openNote($attrs){
		$class = end($this->tagStack); //get the object from the top of the stack
		$note = new XG_Note(); //create a new object (based on tag)
		if (!empty($attrs["ID"]))
		$note->setId($attrs["ID"]);	//set the value from the attribute if there
		if (!empty($attrs["TEMPID"]))
		$note->setTempId($attrs["TEMPID"]);	//set the value from the attribute if there
		$class->addNote($note); //add or set the new object to the object on top of the stack
		array_pop($this->tagStack); //remove and add the updated version of this object
		$this->tagStack[] = $class;
		$this->tagStack[] = $note; //push the new object onto the top of the stack
	}
	function openCitation($attrs){
		$class = end($this->tagStack);
		$citation = new XG_Citation();
		if (!empty($attrs["ID"]))
		$citation->setId($attrs["ID"]);
		if (!empty($attrs["TEMPID"]))
		$citation->setTempId($attrs["TEMPID"]);
		$class->addCitation($citation);
		array_pop($this->tagStack);
		$this->tagStack[] = $class;
		$this->tagStack[] = $citation;
	}
	function openPerson($attrs){
		$person = new XG_Person();
		$person->setXmlGed($this);
		if (!empty($attrs["VERSION"]))
		$person->setVersion($attrs["VERSION"]);
		if (!empty($attrs["ID"]))
		$person->setID($attrs["ID"]);
		if (!empty($attrs["MODIFIED"]))
		$person->setModified($attrs["MODIFIED"]);
		if (!empty($attrs["TEMPID"]))
		$person->setTempId($attrs["TEMPID"]);
		$this->tagStack[] = $person;
	}
	function openId($attrs) {
		$type = "familysearch";
		if (isset($attrs['TYPE'])) $type = $attrs['TYPE'];
		$this->tempId = array("type"=>$type, "id"=>"");
	}
	function openChild($attrs){
		$ordFactRel = end($this->tagStack);
		$personRef = new XG_PersonRef();
		if (!empty($attrs["REF"]))
		$personRef->setRef($attrs["REF"]);
		if (!empty($attrs["ROLE"]))
		$personRef->setRole($attrs["ROLE"]);
		if (get_class($ordFactRel)=="XG_Person") {
			$ordFactRel->addChild($personRef);
		}
		else {
			$ordFactRel->setChild($personRef);
		}
		
		array_pop($this->tagStack);
		$this->tagStack[] = $ordFactRel;
		$this->tagStack[] = $personRef;
	}
	function openParent($attrs){
		$ordFactRel = end($this->tagStack);
		$personRef = new XG_PersonRef();
		if (!empty($attrs["REF"]))
		$personRef->setRef($attrs["REF"]);
		if (!empty($attrs["ROLE"]))
		$personRef->setRole($attrs["ROLE"]);
		$type = strtolower(get_class($ordFactRel));
		if ($type == "xg_ordinance" || $type=="xg_person"){
			$ordFactRel->addParent($personRef);
		}else {
			$ordFactRel->setParent($personRef);
		}
		array_pop($this->tagStack);
		$this->tagStack[] = $ordFactRel;
		$this->tagStack[] = $personRef;
	}
	function openSpouse($attrs){
		$ordFactRel = end($this->tagStack);
		$personRef = new XG_PersonRef();
		if (!empty($attrs["REF"]))
		$personRef->setRef($attrs["REF"]);
		if (!empty($attrs["ROLE"]))
		$personRef->setRole($attrs["ROLE"]);
		//-- handle summary view
		if (get_class($ordFactRel)=="XG_Person") {
			$ordFactRel->addSpouse($personRef);
		}
		else {
			$ordFactRel->setSpouse($personRef);
		}
		array_pop($this->tagStack);
		$this->tagStack[] = $ordFactRel;
		$this->tagStack[] = $personRef;
	}
	function openOrdinance($attrs){
		$person = end($this->tagStack);
		$ordinance = new XG_Ordinance();
		if (!empty($attrs["ID"]))
		$ordinance->setId($attrs["ID"]);
		if (!empty($attrs["MODIFIED"]))
		$ordinance->setModified($attrs["MODIFIED"]);
		if (!empty($attrs["DISPUTING"]))
		$ordinance->setDisputing($attrs["DISPUTING"]);
		if (!empty($attrs["TEMPID"]))
		$ordinance->setTempId($attrs["TEMPID"]);
		if (!empty($attrs["VERSION"]))
		$ordinance->setVersion($attrs["VERSION"]);
		if (!empty($attrs["SCORE"]))
		$ordinance->setScope($attrs["SCOPE"]);
		if (!empty($attrs["TYPE"]))
		$ordinance->setType($attrs["TYPE"]);;
		if (!empty($attrs["TEMPLE"]))
		$ordinance->setTemple($attrs["TEMPLE"]);
		if (!empty($attrs["CONTRIBUTOR"])) {
			$cont = new XG_Contributor();
			$cont->setRef($attrs["CONTRIBUTOR"]);
			$ordinance->setContributor($cont);
		}
		$person->addAssertion($ordinance);
		array_pop($this->tagStack);
		$this->tagStack[] = $person;
		$this->tagStack[] = $ordinance;
	}
	function openFact($attrs){

		$person = end($this->tagStack);
		$fact = new XG_Fact();
		if (!empty($attrs["ID"]))
		$fact->setId($attrs["ID"]);
		if (!empty($attrs["MODIFIED"]))
		$fact->setModified($attrs["MODIFIED"]);
		if (!empty($attrs["DISPUTING"]))
		$fact->setDisputing($attrs["DISPUTING"]);
		if (!empty($attrs["TEMPID"]))
		$fact->setTempId($attrs["TEMPID"]);
		if (!empty($attrs["VERSION"]))
		$fact->setVersion($attrs["VERSION"]);
		if (!empty($attrs["SCOPE"]))
		$fact->setScope($attrs["SCOPE"]);
		if (!empty($attrs["TYPE"]))
		$fact->setType($attrs["TYPE"]);
		if (!empty($attrs["CONTRIBUTOR"])) {
			$cont = new XG_Contributor();
			$cont->setRef($attrs["CONTRIBUTOR"]);
			$fact->setContributor($cont);
		}
		$person->addAssertion($fact);
		array_pop($this->tagStack);
		$this->tagStack[] = $person;
		$this->tagStack[] = $fact;
	}
	// <event id=""...>
	function openEvent($attrs){
		$person = end($this->tagStack);
		$event = new XG_Event();
		if (!empty($attrs["ID"]))
			$event->setId($attrs["ID"]);
		if (!empty($attrs["MODIFIED"]))
			$event->setModified($attrs["MODIFIED"]);
		if (!empty($attrs["DISPUTING"]))
			$event->setDisputing($attrs["DISPUTING"]);
		if (!empty($attrs["TEMPID"]))
			$event->setTempId($attrs["TEMPID"]);
		if (!empty($attrs["VERSION"]))
			$event->setVersion($attrs["VERSION"]);
		if (!empty($attrs["SCOPE"]))
			$event->setScope($attrs["SCOPE"]);
		if (!empty($attrs["TYPE"]))
			$event->setType($attrs["TYPE"]);
		if (!empty($attrs["TITLE"]))
			$event->setTitle($attrs["TITLE"]);
		if (!empty($attrs["CONTRIBUTOR"])) {
			$cont = new XG_Contributor();
			$cont->setRef($attrs["CONTRIBUTOR"]);
			$event->setContributor($cont);
		}
		$person->addAssertion($event);
		array_pop($this->tagStack);
		$this->tagStack[] = $person;
		$this->tagStack[] = $event;
	}
	function openRelationship($attrs){

		$person = end($this->tagStack);
		$relationship = new XG_Relationship();
		if (!empty($attrs["ID"]))
		$relationship->setId($attrs["ID"]);
		if (!empty($attrs["MODIFIED"]))
		$relationship->setModified($attrs["MODIFIED"]);
		if (!empty($attrs["DISPUTING"]))
		$relationship->setDisputing($attrs["DISPUTING"]);
		if (!empty($attrs["TEMPID"]))
		$relationship->setTempId($attrs["TEMPID"]);
		if (!empty($attrs["VERSION"]))
		$relationship->setVersion($attrs["VERSION"]);
		if (!empty($attrs["SCOPE"]))
		$relationship->setScope($attrs["SCOPE"]);
		if (!empty($attrs["CONTRIBUTOR"])) {
			$cont = new XG_Contributor();
			$cont->setRef($attrs["CONTRIBUTOR"]);
			$relationship->setContributor($cont);
		}
		$person->addAssertion($relationship);
		array_pop($this->tagStack);
		$this->tagStack[] = $person;
		$this->tagStack[] = $relationship;
	}
	function openName($attrs){
		$person = end($this->tagStack);
		$name = new XG_Name();
		if (!empty($attrs["ID"]))
		$name->setId($attrs["ID"]);
		if (!empty($attrs["MODIFIED"]))
		$name->setModified($attrs["MODIFIED"]);
		if (!empty($attrs["DISPUTING"]))
		$name->setDisputing($attrs["DISPUTING"]);
		if (!empty($attrs["TEMPID"]))
		$name->setTempId($attrs["TEMPID"]);
		if (!empty($attrs["VERSION"]))
		$name->setVersion($attrs["VERSION"]);
		if (!empty($attrs["TYPE"]))
		$name->setType($attrs["TYPE"]);
		$person->addAssertion($name);
		array_pop($this->tagStack);
		$this->tagStack[] = $person;
		$this->tagStack[] = $name;
	}
	function openGender($attrs){

		$person = end($this->tagStack);
		$gender = new XG_Gender();
		if (!empty($attrs["ID"]))
		$gender->setId($attrs["ID"]);
		if (!empty($attrs["MODIFIED"]))
		$gender->setModified($attrs["MODIFIED"]);
		if (!empty($attrs["DISPUTING"]))
		$gender->setDisputing($attrs["DISPUTING"]);
		if (!empty($attrs["TEMPID"]))
		$gender->setTempId($attrs["TEMPID"]);
		if (!empty($attrs["VERSION"]))
		$gender->setVersion($attrs["VERSION"]);
		if (!empty($attrs["CONTRIBUTOR"])) {
			$cont = new XG_Contributor();
			$cont->setRef($attrs["CONTRIBUTOR"]);
			$gender->setContributor($cont);
		}
		$person->addAssertion($gender);
		array_pop($this->tagStack);
		$this->tagStack[] = $person;
		$this->tagStack[] = $gender;
	}
	function openPlace($attrs){
		$ordFacEve = end($this->tagStack);
		$place = new XG_Place();
		if(!empty($attrs["PLACEID"])){
			$place->setNormalizedId($attrs["PLACEID"]);
		}
		$ordFacEve->setPlace($place);
		array_pop($this->tagStack);
		$this->tagStack[] = $ordFacEve;
		$this->tagStack[] = $place;
	}
	function openDate($attrs){
		$ordFacEve = end($this->tagStack);
		$date = new XG_Date();
		$ordFacEve->setDate($date);
		array_pop($this->tagStack);
		$this->tagStack[] = $ordFacEve;
		$this->tagStack[] = $date;
	}
	function openForm($attrs){
		$name = end($this->tagStack);
		$form = new XG_Form();
		$name->addForm($form);
		array_pop($this->tagStack);
		$this->tagStack[] = $name;
		$this->tagStack[] = $form;
	}
	function openPieces($attrs){
		$form = end($this->tagStack);
		$pieces = new XG_NamePieces();
		$form->setPieces($pieces);
		array_pop($this->tagStack);
		$this->tagStack[] = $form;
		$this->tagStack[] = $pieces;

	}
	function openContributor($attrs){
		$assertion = end($this->tagStack);
		$contributor = new XG_Contributor();
		if (!empty($attrs["REF"])) $contributor->setRef($attrs["REF"]);
		$assertion->setContributor($contributor);
		array_pop($this->tagStack);
		$this->tagStack[] = $assertion;
		$this->tagStack[] = $contributor;
	}



	//function for each element that contains data
	function dataError($data) {
		$this->error->setMessage($this->error->getMessage().trim($data));
	}
	function dataCode($data) {
		if (!empty($this->error)) $this->error->setCode(trim($data));
	}
	function dataId($data) {
		if (!is_null($this->tempId)) $this->tempId['id'] = trim($data);
		$person = end($this->tagStack);
		if (get_class($person)=="XG_Person") $person->addAlternateId($this->tempId);
	}
	
	function dataLiving($data) {
		$person = end($this->tagStack);
		if (get_class($person)=="XG_Person") $person->setLiving($data);
	}
	
	function dataNote($data){
		$note = end($this->tagStack); // object on top of stack represents the last tag opened
		$note->setNote($note->getNote().trim($data)); // set the data
		array_pop($this->tagStack); //replace top object with updated object
		$this->tagStack[] = $note;
	}
	function dataCitation($data){
		$citation = end($this->tagStack);
		$citation->setCitation($citation->getCitation().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $citation;
	}
	function dataOriginal($data){
		$class = end($this->tagStack);
		$class->setOriginal($class->getOriginal().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $class;
	}
	function dataFullText($data){
		$class = end($this->tagStack);
		$class->setFullText($class->getFullText().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $class;
	}
	function dataNormalized($data){
		$class = end($this->tagStack);
		$class->setNormalized($class->getNormalized().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $class;
	}
	function dataDetail($data){
		$class = end($this->tagStack);
		$class->setDetail($class->getDetail().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $class;
	}
	function dataAge($data){
		$personRef = end($this->tagStack);
		$personRef->setAge($personRef->getAge().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $personRef;
	}
	function dataTitle($data){
		$event = end($this->tagStack);
		$event->setTitle($event->getTitle().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $event;
	}
	function dataDescription($data){
		$event = end($this->tagStack);
		$event->setDescription($event->getDescription().trim($data));
		array_pop($this->tagStack);
		$this->tagStack[] = $event;
	}
	function dataValue($data){
		$lastClass = end($this->tagStack);
		$class = strtolower(get_class($lastClass));
		if (!empty($this->handler[$class])){
			call_user_func(array(&$this, $this->handler[$class]), $data);
		}
	}

	//some tags data is inside of <value> tags, so these tags are split farther
	//function for each of the dataValues
	function valueGender($data){
		$gender = end($this->tagStack);
		$data = trim($data);
		if (!empty($data)) {
			$gender->setGender($data);
		}
		array_pop($this->tagStack);
		$this->tagStack[] = $gender;

	}
	//if data is spread among different lines it will create different pieces for each line
	function valuePieces($data){

		$type = strtolower($this->lastType);
		$namePiece = end($this->tagStack);
		call_user_func(array($this, $this->handler[strtolower($type)]), $data, $namePiece);
		array_pop($this->tagStack);
		$this->tagStack[] = $namePiece;
	}

	//the pieces are split up even farther
	//function for each of the valuePieces
	function piecePrefix($data, $namePiece){
		$namePiece->addPrefix($data);
	}
	function pieceSuffix($data, $namePiece){
		$namePiece->addSuffix($data);
	}
	function pieceGiven($data, $namePiece){
		$namePiece->addGiven($data);
	}
	function pieceFamily($data, $namePiece){
		$namePiece->addFamily($data);
	}
	function pieceOther($data, $namePiece){
		$namePiece->addOther($data);
	}
}

/**
 * returns the GEDCOM snippet for a place.
 * normalized place is ignored
 */
class XG_Place{
	//places variables and properties
	var $original="";
	var $normalized="";
	var $normalizedId;

	function toXml(){
		$xml='';
		if (empty($this->original) && empty($this->normalized)) return $xml;
		$xml.="\t<place>\n";
		if(!empty($this->normalized))$xml.="\t\t<normalized placeId=\"".$this->normalizedId."\">".$this->normalized."</normalized>\n";
		if(!empty($this->original))	$xml.="\t\t<original>".$this->original."</original>\n";
		$xml.="\t</place>\n";
		return $xml;
	}
	
	//getters
	function getOriginal(){return $this->original;}

	function getNormalized(){return $this->normalized;}

	function getNormalizedId(){
		return $this->normalizedId;
	}

	//settes
	function setOriginal($orig){
		$this->original = $orig;
	}

	function setNormalized($normalized){
		$this->normalized = $normalized;
	}

	function setNormalizedId($normalziedId){
		$this->normalizedId = $normalziedId;
	}


	/**
	 * gets the GEDCOM snippet from this place.
	 */
	function getGedcom(){
		$gedcom = "";
		if (!empty($this->original)){
			$gedcom="2 PLAC ".$this->original;
			$gedcom.="\r\n";
		}
		return $gedcom;
	}

}

/**
 * the gender of a person. Gender is an Assertion
 */
class XG_Gender extends XG_Assertion{
	//gender variables and properties
	var $gender;

	function toXml($forAdd=false){
		$xml='';
		$xml.="<gender";
		if (!$forAdd) {
			$xml.=" version=\"".$this->version."\" modified=\"".$this->modified."\" ";
			$xml.="disputing=\"".($this->disputing==false?"false":"true")."\" contributor=\"".$this->contributor."\"";
		}
		$xml.=">\n";
		if(!empty($this->citations)){
			$xml.="<citations>\n";
			foreach($this->citations as $citation){
				$xml.=$citation->toXml();
			}
			$xml.="</citations>\n";
		}
		if(!empty($this->notes)){
			$xml.="<notes>\n";
			foreach($this->notes as $note){
				$xml.=$note->toXml();
			}
			$xml.="</notes>\n";
		}
		$xml.="<value>".$this->gender."</value>\n";
		$xml.="</gender>\n";
		return $xml;
	}

	function setPerson(&$person) {
		parent::setPerson($person);
		$person->setGender($this);
	}

	//getter
	function getGender(){
		return $this->gender;
	}

	//setter
	function setGender($gender){
		$this->gender = $gender;
	}

	//functions (methods) of the Gender class

	/**
	 * gets the GEDCOM snippet from this gender.
	 */
	function getIndiGedcom(){
		$sex="";
		$gedcom="";
		if(!empty($this->gender)){
			if(strtolower($this->gender)=="male"){
				$sex="M";
			}
			else if (strtolower($this->gender)=="female"){
				$sex="F";
			}
			else{
				$sex="U";
			}
			$gedcom="1 SEX ".$sex."\r\n";
		}
		//add on the base gedcom
		$gedcom.= parent::getIndiGedcom(2);
		return $gedcom;
	}
}

/**
 * Name of a person. Name is an Assertion
 */
class XG_Name extends XG_Assertion {
	//gender variables and properties
	var $type;
	var $forms=array();
	var $lowerGedcom;

	/**
	 * Override the setPerson method for names so that the name of the person
	 * also gets set
	 *
	 * @param XG_Person $person
	 */
	function setPerson(&$person) {
		parent::setPerson($person);
		$person->setPrimaryName($this);
	}

	function toXml($forAdd=false){
		$xml = "<name type=\"Name\" ";
		if (!$forAdd) {
			$xml.="version=\"".$this->version."\" modified=\"".$this->modified."\" ";
			$xml.="id=\"".$this->id."\" disputing=\"".($this->disputing==false?"false":"true")."\" contributor=\"".$this->contributor."\"";
		}
		$xml.=">\n";
		if(!empty($this->citations)){
			$xml.="<citations>\n";
			foreach($this->citations as $citation){
				$xml.=$citation->toXml();
			}
			$xml.="</citations>\n";
		}
		if(!empty($this->notes)){
			$xml.="<notes>\n";
			foreach($this->notes as $note){
				$xml.=$note->toXml();
			}
			$xml.="</notes>\n";
		}
		if(!empty($this->forms)){
			$xml.="<forms>\n";
			foreach($this->forms as $form){
				$xml.=$form->toXml($forAdd);
			}
			$xml.="</forms>\n";
		}
		$xml.="</name>\n";
		return $xml;
	}

	//getters
	function getFullText() {
		foreach($this->forms as $f=>$form) {
			if (!empty($form)) {
				$original = $form->getFullText();
				if (!empty($original)) return $original;
			}
		}
		return "";
	}

	function getType(){
		return $this->type;
	}

	function getForms(){
		return $this->forms;
	}

	//setters
	function setType($value){
		$this->type = $value;
	}

	function addForm($value){
		$this->forms[] = $value;
	}

	/**
	 * gets the GEDCOM snippet from this name.
	 * achieved by getting the GEDCOM from each of
	 * the forms and concatonating them.
	 */
	function getIndiGedcom(){
		$gedcom = "";

		//get the gedcom for each form of a name
		foreach($this->forms as $form){
			$formGedcom = $form->getGedcom();
			$gedcom .= $formGedcom;
			if (!empty($this->type) && $this->type!="Name"){
				$gedcom .="2 TYPE ".$this->type."\r\n";
			}
		}
		$gedcom.=parent::getIndiGedcom(2);
		return $gedcom;
	}
}

/**
 * a person is made up of assertions (name, gender, events, facts, relationships, and ordinances).
 */
class XG_Person{
	//private fields
	var $assertions = array();
	var $notes = array();
	var $citations = array();
	var $id;
	var $tempId;
	var $version;
	var $modified;
	var $parents = array();
	var $children = array();
	var $spouses = array();
	var $name = null;
	var $birthAssertion = null;
	var $deathAssertion = null;
	var $marriageAssertion = null;
	var $gender = null;
	var $famcged = "";
	var $famsged = "";
	var $indiged = "";
	var $xmlGed = null;
	var $living = "false";
	var $altIds = array();

	function setXmlGed(&$xmlGed) {
		$this->xmlGed = $xmlGed;
	}

	function toXml($forAdd=false){
		$xml = "<person ";
		if (!$forAdd) {
			$xml .="version=\"".$this->version."\" ";
			$xml .= "modified=\"".$this->modified."\" id=\"".$this->id."\"";
		}
		else {
			$xml .= "tempId=\"".$this->tempId."\"";
		}
		$xml .=">\n";
		$xml.="<information>\n";
		if (count($this->altIds)>0) {
			$xml.="\t<alternateIds>\n";
			foreach($this->altIds as $id) {
				if (!empty($id)) $xml.="\t\t<id type=\"".$id['type']."\">".$id['id']."</id>\n";
			}
			$xml.="\t</alternateIds>\n";
		}
		if(!empty($this->gender)) $xml.="\t<gender>".$this->gender->getGender()."</gender>\n";
		if(is_string($this->living)) $living = $this->living;
		else {
			$living = "true";
			if (!$this->living) $living = "false";
		}
		$xml.="\t<living>".$living."</living>\n";
		$xml.="</information>\n";
		$xml.="<assertions>\n";

		if(!empty($this->assertions)){
			foreach($this->assertions as $assertion){
				//$xml.="[".get_class($assertion)."] ";
				$xml.=$assertion->toXml($forAdd);
				//$xml.="\n";
			}
		}
		$xml.="</assertions>\n";
		$xml.="</person>\n";
		return $xml;
	}

	function getXmlGed() {
		return $this->xmlGed;
	}

	//add functions for arrays
	function addAssertion(&$assertion){
		$assertion->setPerson($this);
		$this->assertions[] = $assertion;
	}

	function addNote(&$note){
		$this->notes[] = $note;
	}

	function addParent(&$parent) {
		if (!in_array($parent, $this->parents)) $this->parents[] = $parent;
	}

	function hasChild($childId) {
		$found = false;
		foreach($this->children as $c=>$cchild) {
			if ($cchild->getRef()==$childId) {
				$found = true;
				break;
			}
		}
		return $found;
	}

	function addChild(&$child) {
		if (!$this->hasChild($child->getRef())) $this->children[] = $child;
	}

	function addSpouse(&$spouse) {
		if (!in_array($spouse, $this->spouses)) $this->spouses[] = $spouse;
	}

	function getSpouses() {
		return $this->spouses;
	}

	function getParents() {
		return $this->parents;
	}

	function getChildren() {
		return $this->children;
	}

	function getPrimaryName() {
		return $this->name;
	}

	function setPrimaryName(&$name) {
		if ($this->name==null) $this->name = $name;
	}

	function setBirthAssertion(&$birth) {
		if ($this->birthAssertion==null) $this->birthAssertion=$birth;
	}

	/**
	 * Return the birth assertion
	 *
	 * @return XG_Event
	 */
	function getBirthAssertion() {
		return $this->birthAssertion;
	}

	function setDeathAssertion(&$death) {
		if ($this->deathAssertion==null) $this->deathAssertion=$death;
	}

	/**
	 * Return the death assertion
	 *
	 * @return XG_Event
	 */
	function getDeathAssertion() {
		return $this->deathAssertion;
	}
	
	function setMarriageAssertion(&$marriage) {
		$this->marriageAssertion=$marriage;
	}

	/**
	 * Return the death assertion
	 *
	 * @return XG_Event
	 */
	function getMarriageAssertion() {
		return $this->marriageAssertion;
	}

	/**
	 * Return the person's gender assertion
	 *
	 * @return XG_Gender
	 */
	function getGender() {
		return $this->gender;
	}

	function setGender(&$gender) {
		if (empty($this->gender) || is_null($this->gender->getGender())) $this->gender = $gender;
	}

	function getLiving(){
		return $this->living;
	}

	function setLiving($living){
		if ($this->living==null) $this->living = $living;
	}

	function getTempId(){
		return $this->tempId;
	}

	function setTempId($value){
		$this->tempId=$value;
	}

	function addCitation(&$citation){
		$this->citations[] = $citation;
	}

	/**
	 * Return an array of all of this person's assertions
	 *
	 * @return Array
	 */
	function getAssertions(){
		return $this->assertions;
	}

	function getNotes(){
		return $this->notes;
	}

	function getCitations(){
		return $this->citations;
	}

	function getID(){
		return $this->id;
	}
	
	function getAlternateIds() {
		return $this->altIds;
	}
	
	function addAlternateId($id) {
		$this->altIds[] = $id;
	}

	function getVersion(){
		return $this->version;
	}

	function getModified(){
		return $this->modified;
	}

	//setters
	function setID($newID){
		$this->id = $newID;
	}

	function setVersion($newVersion){
		$this->version = $newVersion;
	}

	function setModified($newModified){
		$this->modified=$newModified;
	}

	/**
	 * gets the GEDCOM for an individual from this person.
	 * achieved by getting the GEDCOM from each of
	 * it's subclasses and concatonating them.
	 */
	function getIndiGedcom(){
		if (!empty($this->indiged)) return $this->indiged;

		$gcLevel = 0;
		$gedcom = $gcLevel." @I".$this->id."@ INDI\r\n";

		//add on the GEDCOM for each assertion
		/* @var $assertion XG_Assertion */
		foreach($this->assertions as $assertion){
			$famc = $assertion->getFamCGedcom();
			$fams = $assertion->getFamSGedcom();
			//$gedcom .= "\r\n[".get_class($assertion)."]\r\n"; //TODO take out when finished

			if (!$assertion->getDisputing() && empty($famc) && empty($fams)){
				$gedcom .= $assertion->getIndiGedcom();
			}
		}
		
		// add any alternate ids
		foreach($this->altIds as $a=>$id) {
			$gedcom .= "1 REFN ".$id['id']."\r\n";
			$gedcom .= "2 TYPE ".$id['type']."\r\n";
		}
		
		//add on the GEDCOM for each note and source for
		//each citation and the last date changed for each modified
		foreach($this->notes as $note){
			$gedcom .= $note->getGedcom(1);
		}
		foreach($this->citations as $citation){
			$gedcom .= $citation->getGedcom(1);
		}
		//add references to family gedcoms if appliciable
		$famc = $this->getFamCGedcom();
		$fams = $this->getFamSGedcom();
		$usedFamc = array();
		$usedFams = array();
		foreach($famc as $fct=>$fam){
			if (!isset($usedFamc[$fam['id']])) {
				$gedcom .= "1 FAMC @F".$fam['id']."@\r\n";
				$usedFamc[$fam['id']] = $fam; 
			}
		}
		foreach($fams as $fct=>$fam){
			if (!isset($usedFams[$fam['id']])) {
				$gedcom .= "1 FAMS @F".$fam['id']."@\r\n";
				$usedFams[$fam['id']] = $fam; 
			}
		}
		if (!empty($this->modified)){
			$timeStamp = strtotime($this->modified);
			$date = date("d M Y", $timeStamp);
			$time = date("h:m:s", $timeStamp);
			$gedcom .= ($gcLevel+1)." CHAN\r\n";
			$gedcom .= ($gcLevel+2)." DATE ".$date."\r\n";
			$gedcom .= ($gcLevel+3)." TIME ".$time."\r\n";
			if (GEDCOM_COMPLIANCE_LEVEL>5.5) $gedcom .= ($gcLevel+2)." VERS ".$this->version."\r\n";
			//TODO where is person contributor?
			//$gedcom .= $this->contributor->getGedcom($gcLevel+2);
		}
		$this->indiged = $gedcom;
		return $gedcom;
	}

	/**
	 * gets the GEDCOM for a family where this person is a child.
	 * achieved by getting the GEDCOM from each of
	 * it's subclasses and concatonating them.
	 */
	function getFamCGedcom(){
		if (!empty($this->famcged)) return $this->famcged;
		$fams = array();
		//-- first try to get the FAMS GEDCOM from the parents where this person is a child
		//-- this is to ensure that we always use the same family ids   
		if (!is_null($this->xmlGed)) {
			foreach($this->parents as $p=>$parent) {
				$person = $this->xmlGed->getPerson($parent->getRef());
				if (!is_null($person)) {
					$pfams = $person->getFamSGedcom();
					foreach($pfams as $f=>$pfam) {
						if (preg_match("/CHIL @I".$this->id."@/", $pfam['gedcom'])>0) {
							$found = false;
							foreach($fams as $f=>$fam) {
								if ($fam['id']==$pfam['id']) {
									$found = true;
									break;
								}
							}
							if (!$found) $fams[] = $pfam;
						}
					}
				}
			}
		}
		//-- couldn't get the correct fam gedcom from the parents, so do your best
		if (count($fams)==0 && count($this->parents)>0) {
			//get GEDCOM for each assertion
			foreach($this->assertions as $assertion){
				if (!$assertion->getDisputing()){
					//make sure not to add duplicate entries
					$tempGedcom = $assertion->getFamCGedcom();
					if (!empty($tempGedcom)) {
						$husb = "";
						$wife = "";
						$ct=preg_match("/HUSB @I(.*)@/", $tempGedcom, $match);
						if ($ct) $husb = $match[1];
						$ct=preg_match("/WIFE @I(.*)@/", $tempGedcom, $match);
						if ($ct) $wife = $match[1];

						$fct = count($fams);
						$newfam = array();
						$newfam['gedcom']="1 CHIL @I".$this->id."@\r\n";
						$newfam['husb'] = false;
						$newfam['wife'] = false;

						for($f=0; $f<$fct; $f++) {
							$fam = $fams[$f];
							if ($fam['husb']===$husb) break;
							if ($fam['wife']===$wife) break;
						}
						if ($f==$fct) $fam = $newfam;
						if (!empty($husb)) $fam['husb']=$husb;
						if (!empty($wife)) $fam['wife']=$wife;
						$fam['gedcom'].= $tempGedcom;
						$fams[$f] = $fam;
					}
				}
			}

			$fct = count($fams);
			for($f=0; $f<$fct; $f++) {
				if (!empty($fams[$f]["husb"])||!empty($fams[$f]["wife"])) {
					$fams[$f]["gedcom"] = "0 @F".$fams[$f]["husb"].":".$fams[$f]["wife"]."@ FAM\r\n".$fams[$f]["gedcom"];
					$fams[$f]['id'] = $fams[$f]["husb"].":".$fams[$f]["wife"];
				}
				else {
					$fams[$f]["gedcom"] = "0 @F".$f.$this->id."@ FAM\r\n".$fams[$f]["gedcom"];
					$fams[$f]['id'] = $f.$this->id;
				}
			}
		}
		$this->famcged = $fams;
		return $fams;
	}

	/**
	 * gets the GEDCOM for a family where this person is a child.
	 * achieved by getting the GEDCOM from each of
	 * it's subclasses and concatonating them
	 */
	function getFamSGedcom(){
		if (!empty($this->famsged)) return $this->famsged;
		$gedcom = "";
		$fams = array();
		$hold = array();

		$spouseCode = "HUSB";
		if (!is_null($this->gender) && strtolower($this->gender->getGender())=="female") $spouseCode = "WIFE";

		//-- check for spouses in the spouses array, useful for summary view
		foreach($this->spouses as $s=>$spouseRef) {
			$altCode = "WIFE";
			if ($spouseCode=="WIFE") $altCode = "HUSB";
			$tempGedcom = "1 ".$altCode." @I".$spouseRef->getRef()."@\r\n";
			
			$newfam = array();
			$newfam['husb'] = false;
			$newfam['wife'] = false;
			$newfam[strtolower($spouseCode)] = $this->id;
			$newfam[strtolower($altCode)] = $spouseRef->getRef();
			$newfam['gedcom']="1 ".$spouseCode." @I".$this->id."@\r\n".$tempGedcom;
			$fams[] = $newfam;
		}
		
		//-- add the children from the children array, also useful for handling the summary view
		foreach($this->children as $c=>$childRef) {
			$tempGedcom = "1 CHIL @I".$childRef->getRef()."@\r\n";
			$hold[] = $tempGedcom;
		}
		
		//get GEDCOM for each assertion for the family where person is spouse
		foreach($this->assertions as $assertion){
			if (!$assertion->getDisputing()){
				//make sure not to add duplicate entries
				$tempGedcom = $assertion->getFamSGedcom();
				if (!empty($tempGedcom)) {
					$husb = "";
					$wife = "";
					$chil = "";
					$ct=preg_match("/HUSB @I(.*)@/", $tempGedcom, $match);
					if ($ct) $husb = $match[1];
					$ct=preg_match("/WIFE @I(.*)@/", $tempGedcom, $match);
					if ($ct) $wife = $match[1];
					$ct=preg_match("/CHIL @I(.*)@/", $tempGedcom, $match);
					if ($ct) $chil = $match[1];
					//-- hold the children till the end
					if (empty($husb) && empty($wife) && !empty($chil)) {
						$hold[] = $tempGedcom;
					}
					else {
						$fct = count($fams);
						$newfam = array();
						$newfam['gedcom']="1 ".$spouseCode." @I".$this->id."@\r\n";
						$newfam['husb'] = false;
						$newfam['wife'] = false;
						$newfam[strtolower($spouseCode)] = $this->id;
						//$tempGedcom .= "famcount ".$fct."\r\n";
						$found = false;
						for($f=0; $f<$fct; $f++) {
							$fam = $fams[$f];
							//$tempGedcom .= $f." husb[".$fam['husb']." = ".$husb."] ";
							//$tempGedcom .= " wife[".$fam['wife']." = ".$wife."] ";
							if ($fam['husb']===$husb) {
								$found = $f;
								break;
							}
							if ($fam['wife']===$wife) {
								$found = $f;
								break;
							}
						}
						if ($found===false) $fam = $newfam;
						else $fam = $fams[$found];
						//print "[".get_class($assertion)." ".$found." ".$tempGedcom."]";
						if (!empty($husb)) $fam['husb']=$husb;
						if (!empty($wife)) $fam['wife']=$wife;
						$lines = preg_split("/\r?\n/", $tempGedcom);
						foreach($lines as $l=>$line) {
							if (!empty($line)) {
								if (preg_match("/1 (HUSB)|(WIFE) @I(.*)@/", $line)) {
									if (preg_match("/".$line."/", $fam['gedcom'])==0) $fam['gedcom'].= trim($line)."\r\n";
								}
								else $fam['gedcom'].= trim($line)."\r\n";
							}
						}
						$fams[$f] = $fam;
					}
				}
			}
		}

		//-- put the children in the families
		foreach($hold as $h=>$tempGedcom) {
			$ct=preg_match("/CHIL @I(.*)@/", $tempGedcom, $match);
			if ($ct) $chil = $match[1];
			$fct = count($fams);
			$newfam = array();
			$newfam['gedcom']="1 ".$spouseCode." @I".$this->id."@\r\n".$tempGedcom;
			$newfam['husb'] = false;
			$newfam['wife'] = false;
			$newfam[strtolower($spouseCode)] = $this->id;
			$found = false;
			for($f=0; $f<$fct; $f++) {
				$fam = $fams[$f];

				//-- check if the child is already in the family
				if (preg_match("/CHIL @I".$chil."@/", $fams[$f]['gedcom'])>0) {
					$found = true;
					break;
				}
				if (!is_null($this->xmlGed)) {
					$ch = false;
					$cw = false;
					if (!empty($fam['husb'])) {
						$tperson = $this->xmlGed->getPerson($fam['husb']);
						if (!is_null($tperson)) $ch = $tperson->hasChild($chil);
					}
					if (!empty($fam['wife'])) {
						$tperson = $this->xmlGed->getPerson($fam['wife']);
						if (!is_null($tperson)) $cw = $tperson->hasChild($chil);
					}
					if ($ch && $cw) {
						$fams[$f]['gedcom'] .= $tempGedcom;
						$found = true;
						break;
					}
				}
			}
			if (!$found) $fams[] = $newfam;
		}

		//put it all together
		//		if (!empty($gedcom)){
		//			$gedcom = "0 @FS".$this->id."@ FAM\r\n".$gedcom;
		//			$fams[] = $gedcom;
		//		}

		$fct = count($fams);
		for($f=0; $f<$fct; $f++) {
			//$fams[$f]['gedcom'] .= "1 ".$spouseCode." @I".$this->id."@\r\n";
			if (empty($fams[$f][strtolower($spouseCode)])) $fams[$f][strtolower($spouseCode)] = $this->id;
			if (!empty($fams[$f]["husb"]) && !empty($fams[$f]["wife"])) {
				$fams[$f]["gedcom"] = "0 @F".$fams[$f]["husb"].":".$fams[$f]["wife"]."@ FAM\r\n".$fams[$f]["gedcom"];
				$fams[$f]['id'] = $fams[$f]["husb"].":".$fams[$f]["wife"];
			}
			else {
				$fams[$f]["gedcom"] = "0 @F".$f.$this->id."@ FAM\r\n".$fams[$f]["gedcom"];
				$fams[$f]['id'] = $f.$this->id;
			}
		}
		$this->famsged = $fams;
		return $fams;
	}
}

/**
 * each form of a name.
 * gets the gedcom for the original and the forms and puts them together.
 */
class XG_Form{
	//private fields
	var $fullText = "";
	var $pieces;

	//getters
	function getFullText(){
		return $this->fullText;
	}

	function getPieces(){
		return $this->pieces;
	}

	//setters
	function setFullText($original){
		$this->fullText = $original;
	}

	function setPieces($pieces){
		$this->pieces = $pieces;
	}

	function toXml(){
		$xml = "<form>\n";
		$xml.="\t<fullText>".$this->fullText."</fullText>\n";
		if(!empty($this->pieces)){
			$xml.="\t<pieces>\n";
				if (is_object($this->pieces))
					$xml.=$this->pieces->toXml();

			$xml.="\t</pieces>\n";
		}
		$xml.="</form>\n";
		return $xml;
	}
	/**
	 * gets the GEDCOM snippet for this form.
	 * achieved by getting the GEDCOM from each of
	 * it's pieces and concatonating them with the gedcom for
	 * the original.
	 * If there is only one name provided and none of the pieces
	 * provide a hint to determine if it is a last name or first
	 * name, it will assume it is a last name.
	 */
	function getGedcom(){
		//make sure that there is any info
		if (empty($this->fullText) && empty($this->pieces)){
			return "";
		}
		$gedcom = "1 NAME ";
		//format the original string so last name contains "/" aournd them
		if(!empty($this->fullText)){
			$lasts = $this->pieces->getFamilies();
			if (isset($lasts[0])) $last = $lasts[0];
			if (empty($last)) {
				$parts = split(" ", $this->fullText);
				$last = $parts[count($parts)-1];
			}
			$formatedName = str_replace($last, "/".$last."/", $this->fullText);
			$gedcom .=$formatedName;
		}
		$gedcom .= "\r\n";
		//include any of the pieces
		if (!empty($this->pieces)){
			$gedcom.=$this->pieces->getGedcom();
		}
		return $gedcom;
	}
}

/**
 * gets the GEDCOM snippet for a citation's source.
 */
class XG_Citation{

	//Fields
	var $citation;
	var $id;
	var $tempId;
	
	function toXml(){
		$xml='';
		$xml.="<citation id=\"".$this->id."\" />";
		return $xml;
	}

	//Getters and Setters

	function getCitation(){
		return $this->citation;
	}

	function setCitation($value){
		$this->citation=$value;
	}

	function getId(){
		return $this->id;
	}

	function setId($value){
		$this->id=$value;
	}

	function getTempId(){
		return $this->tempId;
	}

	function setTempId($value){
		$this->tempId=$value;
	}

	/**
	 * gets the GEDCOM to reference the citation id.
	 */
	function getGedcom($gcLevel){
		$gedcom = $gcLevel;
		$gedcom .= " SOUR @S".$this->id."@\r\n";

		return $gedcom;
	}

}

/**
 *  a note about the information.
 */
class XG_Note{

	//Fields
	var $note;
	var $id;
	var $tempId;
	
	function toXml(){
		$xml='';
		$xml.="<note id=\"".$this->id."\">";
		$xml.=$this->note;
		$xml.="\n</note>\n";
		return $xml;
	}

	//Getters and Setters

	function getNote(){
		return $this->note;
	}

	function setNote($value){
		$this->note=$value;
	}

	function getId(){
		return $this->id;
	}

	function setId($value){
		$this->id=$value;
	}

	function getTempId(){
		return $this->tempId;
	}

	function setTempId($value){
		$this->tempId=$value;
	}

	/**
	 * gets the GEDCOM snippet for this note
	 */
	function getGedcom($gcLevel){
		$gedcom= $gcLevel;
		$gedcom.=" NOTE ";
		$gedcom.=$this->note;
		$gedcom = $this->breakConts($gedcom);
		return $gedcom;
	}

	//break long lines into acceptable GEDCOM lengths
	function breakConts($newline, $level = "") {
		if (empty($level)){
			$level = $newline{0} + 1;
		}
		$newged = "";
		//-- convert returns to CONT lines and break up lines longer than 255 chars
		$newlines = preg_split("/\r?\n/", $newline);
		for($k=0; $k<count($newlines); $k++) {
			if ($k>0) $newlines[$k] = $level." CONT ".$newlines[$k];
			if (strlen($newlines[$k])>255) {
				while(strlen($newlines[$k])>255) {
					// Make sure this piece doesn't end on a blank
					// (Blanks belong at the start of the next piece)
					$thisPiece = rtrim(substr($newlines[$k], 0, 255));
					$newged .= $thisPiece."\r\n";
					$newlines[$k] = substr($newlines[$k], strlen($thisPiece));
					$newlines[$k] = $level." CONC ".$newlines[$k];
				}
				$newged .= trim($newlines[$k])."\r\n";
			}
			else {
				$newged .= trim($newlines[$k])."\r\n";
			}
		}
		return $newged;
	}
}

/**
 * the reference to a person and their role (man, woman, husband, wife, son, daughter)
 */
class XG_PersonRef{

	//Fields
	var $age;
	var $ref;
	var $role;

	var $table = array();


	//Getters and Setters
	function getAge(){
		return $this->age;
	}

	function setAge($value){
		$this->age=$value;
	}

	function getRef(){
		return $this->ref;
	}

	function setRef($value){
		$this->ref=$value;
	}

	function getRole(){
		return $this->role;
	}

	function setRole($value){
		$this->role=$value;
	}


	//constructor
	function XG_PersonRef(){
		$this->table["father"] = "HUSB";
		$this->table["mother"] = "WIFE";
		$this->table["man"] = "HUSB";
		$this->table["woman"] = "WIFE";
		$this->table["son"] = "CHIL";
		$this->table["daughter"] = "CHIL";
	}

	/**
	 * no GEDCOM info for an individual record from a person reference
	 */
	function getIndiGedcom(){
		//no Indi data
		return "";
	}

	/**
	 * get Gedcom portion for family where person is a spouse
	 */
	function getFamSGedcom($isChild, $isMale){
		$gedcom = "";

		//make sure there is info
		if (!empty($this->ref)){
			$gedcom .= "1 ";

			//child or spouse?
			if ($isChild){

				//verify valid role (not father or mother)
				if (!empty($this->role) && (strtolower($this->role) == "father" || strtolower($this->role) == "mother")){
					return "";
				}

				$gedcom .= "CHIL";

			} else {

				//verify valid role (not son or daughter)
				if (!empty($this->role) && (strtolower($this->role) == "son" || strtolower($this->role) == "daughter")){
					return "";
				}

				$gedcom .= $this->ParentCode($isMale); // get Gedcom from method
			}

			$gedcom .= " @I".$this->ref."@\r\n";
		}

		return $gedcom;
	}

	/**
	 *  get Gedcom portion for family where person is a child
	 */
	function getFamCGedcom($isMale){
		$gedcom = "";

		//check that there is info
		if (!empty($this->ref)){
			$gedcom .= "1 ";

			//verify valid role (not son or daughter)
			//			if (!empty($this->role) && (strtolower($this->role) != "son" && strtolower($this->role) != "daughter")){
			//				return "";
			//			}

			$gedcom .= $this->ParentCode($isMale); // get Gedcom from method
			$gedcom .= " @I".$this->ref."@\r\n";
		}

		//will send "" if no ref nr
		return $gedcom;



	}

	//helper function, finds the Gedcom code for a parent or spouse
	function ParentCode($isMale){
		$result = "";

		//does the data provide a role
		if (!empty($this->role)){

			// Gedcom code based on role
			$result = $this->table[strtolower($this->role)];
		} else {

			// Gedcom code based on method parameter
			if ($isMale){
				$result = "HUSB";
			} else {
				$result = "WIFE";
			}
		}

		return $result;
	}
}

class XG_Ordinance extends XG_Assertion{

	//Fields
	var $ordinance_handler = array();
	var $scope;
	var $type;
	var $official;
	var $date;
	var $place;
	var $spouse;
	var $parents=array();
	var $temple;

	/**
	 * Parse the GEDCOM
	 *
	 * @param string $factrec
	 */
	function parseGEDCOM($factrec) {
		parent::parseGEDCOM($factrec);

	}

	//Getters and Setters

	function setScope($value){
		$this->scope=$value;
	}

	function getScope(){
		return $this->scope;
	}

	function setType($value){
		$this->type=$value;
	}

	function getType(){
		return $this->type;
	}

	function setOfficial($value){
		$this->official=$value;
	}

	function getOfficial(){
		return $this->official;
	}

	function setDate($value){
		$this->date=$value;
	}

	function getDate(){
		return $this->date;
	}

	function setPlace($value){
		$this->place = $value;
	}

	function getPlace(){
		return $this->place;
	}

	function setSpouse($value){
		$this->person->addSpouse($value);
		$this->spouse=$value;
	}

	function getSpouse(){
		return $this->spouse;
	}

	function addParent($value){
		$this->person->addParent($value);
		$this->parents[]=$value;
	}

	function getParents(){
		return $this->parents;
	}

	function setTemple($value){
		$this->temple=$value;
	}

	function getTemple(){
		return $this->temple;
	}

	//Functions

	function getAssertionType() {
		return $this->type;
	}

	function getIndiGedcom(){

		$personId = "";
		$lowercase = strtolower($this->type);
		$info = "";

		//set the handler for each string found in the xml
		//the string is changed to the appropriate GEDCOM fact type names
		$this->ordinance_handler["baptism"] = "BAPL";
		$this->ordinance_handler["confirmation"] = "CONL";
		$this->ordinance_handler["endowment"] = "ENDL";
		$this->ordinance_handler["sealing to spouse"] = "SLGS";
		$this->ordinance_handler["sealing to parents"] = "SLGC";

		if(!empty($this->ordinance_handler[$lowercase])){
			if ($this->ordinance_handler[$lowercase]=="SLGS") return "";
			$info .= "1 ".$this->ordinance_handler[$lowercase]."\r\n";
		}
		else {
			$info .= "1 EVEN\r\n";
			if(!empty($this->type)){
				$info .= "2 TYPE ".$this->type."\r\n";
			}
		}
		if(!empty($this->scope)){
			$info .= "2 SCOP ".$this->scope."\r\n";
		}
		if(!empty($this->official)){
			$info .= "2 OFFI ".$this->official."\r\n";
		}
		if(!empty($this->date)){
			$info .= $this->date->getGedcom();
		}
		if(!empty($this->place)){
			$info .= $this->place->getGedcom();
		}
		//if(!empty($this->spouse)){
		//$info .= "1 HUSB @I".$this->spouse."@\r\n";
		//}
		if(!empty($this->temple)){
			$info .= "2 TEMP ".$this->temple."\r\n";
		}
		$info.=parent::getIndiGedcom(2);
		return $info;
	}

	//? parents (plural) /  events
	function getFamCGedcom($isMale = true){
		return "";
	}

	function getFamSGedcom($isMale = true){

		//set the handler for each string found in the xml
		//the string is changed to the appropriate GEDCOM fact type names
		$this->ordinance_handler["baptism"] = "";
		$this->ordinance_handler["confirmation"] = "";
		$this->ordinance_handler["endowment"] = "";
		$this->ordinance_handler["sealing to a spouse"] = "SLGS";
		$this->ordinance_handler["sealing to parents"] = "";

		$lowercase = strtolower($this->type);
		$info = "";

		if(empty($this->ordinance_handler[$lowercase])) return "";
		else {
			$info .= "1 ".$this->ordinance_handler[$lowercase]."\r\n";
		}
		if(!empty($this->scope)){
			$info .= "2 ".$this->scope."\r\n";
		}
		//		if(!empty($this->type)){
		//			$info .= "2 ".$this->type."\r\n";
		//		}
		//		if(!empty($this->official)){
		//			$info .= "2 FAMS @FS".$this->official."@\r\n";
		//		}
		if(!empty($this->date)){
			$info .= $this->date->getGedcom(2);
		}
		if(!empty($this->place)){
			$info .= $this->place->getGedcom(2);
		}
		if(!empty($this->temple)){
			$info .= "2 ".$this->temple."\r\n";
		}
		if(!$isMale){
			if(!empty($this->spouse)){
				$info .= $this->spouse->getFamSGedcom();
			}
		}
		else{
			if(!empty($this->spouse)){
				$info .= $this->spouse->getFamSGedcom(false, $isMale);
			}
		}

		return $info;
	}
	
	function toXml($forAdd=false){
		$xml='';
		//-- ordinances cannot be added
		if ($forAdd) return $xml;
		$xml.="<ordinance type=\"".$this->type."\" scope=\"".$this->scope."\"";
		if (!$forAdd) {
			$xml.=" version=\"".$this->version."\" modified=\"".$this->modified."\" ";
			$xml.="id=\"".$this->id."\" disputing=\"".($this->disputing==false?"false":"true")."\" contributor=\"".$this->contributor."\"";
		}
		$xml .=">\n";
		if(!empty($this->citations)){
			$xml.="<citations>\n";
			foreach($this->citations as $citation){
				$xml.=$citation->toXml();
			}
			$xml.="</citations>\n";
		}
		if(!empty($this->notes)){
			$xml.="<notes>\n";
			foreach($this->notes as $note){
				$xml.=$note->toXml();
			}
			$xml.="</notes>\n";
		}
		if(!empty($this->date))$xml.=$this->date->toXml();
		if(!empty($this->place))$xml.=$this->place->toXml();
		if(!empty($this->spouse)){
			$xml.="<spouse role=\"".$this->spouse->getRole()."\" ref=\"".$this->spouse->getRef()."\" />\n";
		}
		foreach($this->parents as $parent) {
			if (!empty($parent)) {
				$xml .= "<parent ref=\"".$parent->getRef()."\" />\n";
			}
		}
		if (!empty($this->temple)) {
			$xml .= "<temple>".$this->temple."</temple>\n";
		}
		$xml.="</ordinance>\n";
		return $xml;
	}
}

class XG_Assertion {

	//Fields
	var $notes=array();
	var $citations=array();
	var $id;
	var $tempId;
	var $version;
	var $modified;
	var $contributor;
	var $disputing = false;
	var $person = null;

	function XG_Assertion() {

	}

	/**
	 * set the person that this assertion belongs to
	 *
	 * @param Person $person
	 */
	function setPerson(&$person) {
		$this->person = $person;
	}

	function addRecordInfo($factrec, &$person_class){
		$chanDate = get_gedcom_value("CHAN:DATE", 2, $factrec, '', false);
		/* http://us2.php.net/manual/en/function.date.php  modified="2007-07-23T10:40:36.771-06:00"*/
		/* c	ISO 8601 date (added in PHP 5)	2004-02-12T15:19:21+00:00 */
		if (!empty($chanDate)) {
			$chanTime = get_gedcom_value("CHAN:DATE:TIME", 2, $factrec, '', false);
			$this->setModified(date("c",strtotime($chanDate." ".$chanTime)));
			$vers = get_gedcom_value("CHAN:VERS", 2, $factrec, '', false);
			if(!empty($vers)) $this->setVersion($vers);
			$cont = get_gedcom_value("CHAN:_PGVU", 2, $factrec, '', false);
			if(!empty($cont)) $this->setContributor($cont);
		}
		else {
			//$XGAssertion->setModified(date("c",strtotime($person_class->getLastchangeDate())));
			$chanDate = get_gedcom_value("CHAN:DATE", 1, $person_class->getGedcomRecord(), '', false);
			/* http://us2.php.net/manual/en/function.date.php  modified="2007-07-23T10:40:36.771-06:00"*/
			/* c	ISO 8601 date (added in PHP 5)	2004-02-12T15:19:21+00:00 */
			if (!empty($chanDate)) {
				$chanTime = get_gedcom_value("CHAN:DATE:TIME", 1, $person_class->getGedcomRecord(), '', false);
				$this->setModified(date("c",strtotime($chanDate." ".$chanTime)));
				$vers = get_gedcom_value("CHAN:VERS", 1, $person_class->getGedcomRecord(), '', false);
				if(!empty($vers)) $this->setVersion($vers);
				$cont = get_gedcom_value("CHAN:_PGVU", 1, $person_class->getGedcomRecord(), '', false);
				if(!empty($cont)) $this->setContributor($cont);
			}
		}
	}

	function addType($factrec, &$person_class, $xmltype){
		$chars = preg_split('/ /', $xmltype);
		$type="";
		foreach($chars as $char){
			$type.=ucfirst($char)." ";
		}
		$type=trim($type);
		$this->setType($type);
	}
	/**
	 * Add a date from a PGV date
	 *
	 * @param String $factrec
	 * @param Person $person_class
	 */
	function addDate($factrec, &$person_class){
		$date = get_gedcom_value("DATE", 2, $factrec, '', false);
		if(!empty($date) ){
			$XGDate = new XG_Date();
			$XGDate->setOriginal($date);
			$g=new GedcomDate($date); 
			$XGDate->setNormalized(unhtmlentities(strip_tags($g->Display(false))));
			$this->setDate($XGDate);
		}
	}
	function addPlace($factrec, &$person_class){
		$XGPlace = new XG_Place();
		$place = get_gedcom_value("PLAC", 2, $factrec, '', false);
		$XGPlace->setOriginal($place);
		/* $XGPLace->setNormalized()*/
		$this->setPlace($XGPlace);
	}
	function addSpouse($factrec, &$person_class){
		$XGSpouse = new XG_PersonRef();
		$spouse = get_gedcom_value("_PGVS", 1, $factrec, '', false);
		if(!empty($spouse)){
			$XGSpouse->setRef($spouse);
			$spouseObject = Person::getInstance($spouse);
			if(!empty($spouseObject)){
				if($spouseObject->getSex()=="M") $XGSpouse->setRole("man");
				if($spouseObject->getSex()=="F") $XGSpouse->setRole("woman");
			}
			$this->setSpouse($XGSpouse);
		}
	}
	function addScope($factrec, &$person_class){
		$famID = get_gedcom_value("_PGVFS", 1, $factrec, '', false);
		if(!empty($famID)){
			$this->setScope("couple");
		}
		else{
			$this->setScope("person");
		}
	}
	function addTitle($factrec, &$person_class){
		$title = get_gedcom_value("TYPE", 2, $factrec, '', false);
		if (!empty($title)) $this->setTitle($title);
	}
	/**
	 * Parse the GEDCOM
	 *
	 * @param string $factrec
	 */
	function parseGEDCOM($factrec) {
		$title = get_gedcom_value("TYPE", 2, $factrec, '', false);
		if (!empty($title)) $this->setTitle($type);

		$date = get_gedcom_value("DATE", 2, $factrec, '', false);
		/* $date = $fact.getDate();  Use this later*/
		if(!empty($date) ){
			$XGDate = new XG_Date();
			$XGDate->setOriginal($date);
			$XGDate->setNormalized(get_changed_date($date));
			$this->setDate($XGDate);
		}

		$XGPlace = new XG_Place();
		$place = get_gedcom_value("PLAC", 2, $factrec, '', false);
		$XGPlace->setOriginal($place);
		/* $XGPLace->setNormalized()*/
		$this->setPlace($XGPlace);

		$XGSpouse = new XG_PersonRef();
		$spouse = get_gedcom_value("_PGVS");
		if(!empty($spouse)){
			$XGSpouse->setRef($spouse);
			$spouseObject = Person::getInstance($spouse);
			if(!empty($spouseObject)){
				if($spouseObject->getSex()=="M") $XGSpouse->setRole("man");
				if($spouseObject->getSex()=="F") $XGSpouse->setRole("woman");
			}
			$this->setSpouse($XGSpouse);
		}

		$famID = get_gedcom_value("_PGVFS");
		if(!empty($famID)){
			$this->setScope("couple");
		}
		else{
			$this->setScope("person");
		}

		$chanDate = get_gedcom_value("CHAN:DATE", 2, $factrec, '', false);
		/* http://us2.php.net/manual/en/function.date.php  modified="2007-07-23T10:40:36.771-06:00"*/
		/* c	ISO 8601 date (added in PHP 5)	2004-02-12T15:19:21+00:00 */
		if (!empty($chanDate)) {
			$XGAssertion->setModified(gdate(c,$chanDate));
		}
		else {
			$XGAssertion->setModified(gdate(c,$person_class->getLastchangeDate()));
		}
		$NewXGPerson->addAssertion($XGAssertion);
	}

	/**
	 * get the person this assertion belongs to
	 */
	function getPerson() {
		return $this->person;
	}

	//Getters and addters
	function addNote($value){
		$this->notes[]=$value;
	}

	function getNotes(){
		return $this->notes;
	}

	function addCitation($value){
		$this->citations[]=$value;
	}

	function getCitations(){
		return $this->citations;
	}

	function setId($value){
		$this->id=$value;
	}

	function getId(){
		return $this->id;
	}

	function setTempId($value){
		$this->tempId = $value;
	}

	function getTempId(){
		return $this->tempId;
	}

	function setVersion($value){
		$this->version = $value;
	}

	function getVersion(){
		return $this->version;
	}

	function setModified($value){
		$this->modified=$value;
	}

	function getModified(){
		return $this->modified;
	}

	function setContributor(&$value){
		$this->contributor=$value;
	}

	function &getContributor(){
		return $this->contributor;
	}

	function setDisputing($value){
		if ($value=="false") $this->disputing=false;
		else $this->disputing=true;
	}

	function getDisputing(){
		return $this->disputing;
	}

	//default methods
	function getIndiGedcom($gcLevel=2){
		$person = "";
		$gedcom = "";

		//notes and citations
		foreach($this->notes as $note){
			$gedcom .= $note->getGedcom($gcLevel);
		}
		foreach($this->citations as $citation){
			$gedcom .= $citation->getGedcom($gcLevel);
		}
		if (GEDCOM_COMPLIANCE_LEVEL>5.5 && !empty($this->modified)){
			$timeStamp = strtotime($this->modified);
			$date = date("d M Y", $timeStamp);
			$time = date("h:m:s", $timeStamp);
			$gedcom .= ($gcLevel)." CHAN\r\n";
			$gedcom .= ($gcLevel+1)." DATE ".$date."\r\n";
			$gedcom .= ($gcLevel+2)." TIME ".$time."\r\n";
			$gedcom .= ($gcLevel+1)." VERS ".$this->getVersion()."\r\n";
			$gedcom .= ($gcLevel+1)." _FSID ".$this->getId()."\r\n";
			if (!is_null($this->contributor)) $gedcom .= $this->contributor->getGedcom($gcLevel+1);
		}
		return $gedcom;
	}

	function getFamCGedcom($isMale = true){
		return "";
	}

	function getFamSGedcom($isMale = true){
		return "";
	}

	function getAssertionType() {
		return substr(get_class($this), 3);
		/* events, facts, ordinances*/
		
		/*christa Edited */
	}

}

/**
 * a date
 */
class XG_Date{

	//Fields
	var $original="";
	var $normalized="";
	var $number=2;

	function toXml(){
		$xml='';
		$xml.="<date>\n";
		$xml.="<normalized>".$this->normalized."</normalized>\n";
		$xml.="<original>".$this->original."</original>\n";
		$xml.="</date>\n";
		return $xml;
	}

	//Getters and Setters
	function setOriginal($value){
		$this->original=$value;
	}

	function getOriginal(){
		return $this->original;
	}

	function setNormalized($value){
		$this->normalized=$value;
	}

	function getNormalized(){
		return $this->normalized;
	}

	/**
	 * gets the GEDCOM snippet for a date.
	 * normalized date is ignored
	 */
	function getGedcom(){
		$gedcom = "";
		if($this->original != ""){
			$gedcom=$this->number." DATE ".$this->original."\r\n";
		}
		return $gedcom;
	}
}


/**
 * contains the information for the pieces of a form of a name.
 */
class XG_NamePieces{

	//Fields
	var $prefixs = array();
	var $suffixs = array();
	var $givens = array();
	var $families = array();
	var $others = array();


	function toXml(){
		$xml='';
		if(!empty($this->prefixs)){
			foreach($this->prefixs as $prefix){
				if (!empty($prefix)) {
					$xml.="\t\t<piece type=\"Prefix\">";
					$xml.="<value>".$prefix."</value>";
					$xml.="</piece>\n";
				}
			}
		}
		if(!empty($this->suffixs)){
			foreach($this->suffixs as $suffix){
				if (!empty($suffix)) {
					$xml.="\t\t<piece type=\"Suffix\">";
					$xml.="<value>".$suffix."</value>";
					$xml.="</piece>\n";
				}
			}
		}
		if(!empty($this->givens)){
			foreach($this->givens as $given){
				if (!empty($given)) {
					$xml.="\t\t<piece type=\"Given\">";
					$xml.="<value>".$given."</value>";
					$xml.="</piece>\n";
				}
			}
		}
		if(!empty($this->families)){
			foreach($this->families as $family){
				if (!empty($family)) {
					$xml.="\t\t<piece type=\"Family\">";
					$xml.="<value>".$family."</value>";
					$xml.="</piece>\n";
				}
			}
		}
		if(!empty($this->others)){
			foreach($this->others as $other){
				if (!empty($other)) {
					$xml.="\t\t<piece type=\"Other\">";
					$xml.="<value>".$other."</value>";
					$xml.="</piece>\n";
				}
			}
		}
		return $xml;
	}
	//Getters and Setters
	function AddPrefix($prefix){
		if (!empty($prefix)) $this->prefixs[] = $prefix;
	}
	function getPrefixs(){
		return $this->prefixs;
	}
	function AddSuffix($suffix){
		if (!empty($suffix)) $this->suffixs[]= $suffix;
	}
	function getSuffixs(){
		return $this->suffixs;
	}
	function AddGiven($given){
		if (!empty($given)) $this->givens[] = $given;
	}
	function getGivens(){
		return $this->givens;
	}
	function addFamily($family){
		if (!empty($family)) $this->families[] = $family;
	}
	function getFamilies(){
		return $this->families;
	}
	function addOther($other){
		if (!empty($other)) $this->others[] = $other;
	}
	function getOthers(){
		return $this->others;
	}

	/**
	 * returns the GEDCOM snippet for the pieces
	 * One problem with current implimentation is that if
	 * There are nothing except others in this class it will
	 * not return anything at all for this snippet
	 *
	 */
	function getGedcom(){
		$gedcom = "";

		//put together the GEDCOM for the content of this class
		//no information on "other" will be displayed
		foreach($this->prefixs as $prefix){
			$gedcom .="2 NPFX ".$prefix."\r\n";
		}
		foreach($this->suffixs as $suffix){
			$gedcom .="2 SPFX ".$suffix."\r\n";
		}
		if (count($this->givens)>0) {
			$gedcom .="2 GIVN ";
			$g=0;
			foreach($this ->givens as $given){
				if ($g>0) $gedcom .= " ";
				$gedcom .= $given;
				$g++;
			}
			$gedcom .= "\r\n";
		}
		foreach($this->families as $family){
			$gedcom .="2 SURN ".$family."\r\n";
		}
		return $gedcom;

	}
}

class XG_Relationship extends XG_Assertion{

	/*@var $spouse XG_PersonRef */
	/*@var $parent XG_PersonRef */
	/*@var $child XG_PersonRef */
	//Field
	var $scope;
	var $spouse;
	var $parent;
	var $child;

	function toXml($forAdd=false){
		$xml='';
		if ($forAdd) return $xml;
		$xml.="<relationship scope=\"".$this->scope."\" version=\"".$this->version."\" modified=\"".$this->modified."\" ";
		$xml.="id=\"".$this->id."\" disputing=\"".($this->disputing==false?"false":"true")."\" contributor=\"".$this->contributor."\">\n";
		if(!empty($this->citations)){
			$xml.="<citations>\n";
			foreach($this->citations as $citation){
				$xml.=$citation->toXml();
			}
			$xml.="</citations>\n";
		}
		if(!empty($this->notes)){
			$xml.="<notes>\n";
			foreach($this->notes as $note){
				$xml.=$note->toXml();
			}
			$xml.="</notes>\n";
		}
		if(!empty($this->spouse))$xml.="<spouse role=\"".$this->spouse->getRole()."\" ref=\"".$this->spouse->getRef()."\" />\n";
		if(!empty($this->parent))$xml.="<parent role=\"".$this->parent->getRole()."\" ref=\"".$this->parent->getRef()."\" />\n";
		if(!empty($this->child))$xml.="<child role=\"".$this->child->getRole()."\" ref=\"".$this->child->getRef()."\" />\n";
		$xml.="</relationship>\n";
		
		return $xml;
	}
	
	//Getters and addters
	function setScope($value){
		$this->scope=$value;
	}

	function getScope(){
		return $this->scope;
	}

	function setSpouse($value){
		$this->person->addSpouse($value);
		$this->spouse=$value;
	}

	function getSpouse(){
		return $this->spouse;
	}

	function setParent($value){
		$this->person->addParent($value);
		$this->parent=$value;
	}

	function getParent(){
		return $this->parent;
	}

	function setChild($value){
		$this->person->addChild($value);
		$this->child=$value;
	}

	function getChild(){
		return $this->child;
	}

	//Functions

	function getIndiGedcom(){
		return "";
	}

	function getFamCGedcom($isMale = true){

		$result="";
		$role;

		if($isMale){
			$role="HUSB";
		}
		else{
			$role="WIFE";
		}



		if(!empty($this->parent)){
			$result .=$this->parent->getFamCGedcom($isMale);
		}

		if(parent::getDisputing() != null && parent::getDisputing()=="true"){
			return "";
		}
		return $result;
	}

	function getFamSGedcom($isMale = true){

		$result="";
		$role;

		if($isMale==true){
			$role="HUSB";
		}
		else{
			$role="WIFE";
		}

		if(!empty($this->spouse)){
			$result .=$this->spouse->getFamSGedcom(false, $isMale);
		}
		if(!empty($this->child)){
			$result .= $this->child->getFamSGedcom(true, $isMale);
		}

		if(parent::getDisputing() != null && parent::getDisputing()=="true"){
			return "";
		}
		return $result;
	}
}

class XG_Contributor{
	var $ref;

	function setRef($ref){
		$this->ref = $ref;
	}
	function getRef(){
		return $this->ref;
	}

	function getIndiGedcom(){return "";}
	function getFamCGedcom(){return "";}
	function getFamSGedcom(){return "";}

	function getGedcom($gcLevel=2){
		$result = "";
		if(!empty($this->ref)){
			$result = $gcLevel." _PGVU ".$this->ref."\r\n";
		}
		return $result;
	}
}


class XG_Fact extends XG_Assertion{

	/*@var $parent XG_PersonRef */
	/*@var $spouse XG_PersonRef */
	/*@var $child XG_PersonRef */
	//Fields
	var $scope;
	var $type;
	var $title;
	var $date;
	var $place;
	var $spouse;
	var $parent;
	var $child;
	var $detail;

	var $factHandler=array();

	function toXml($forAdd=false){
		$xml='';
		if ($forAdd && ($this->type=='Lineage' || !empty($this->parent) || !empty($this->spouse) || !empty($this->child))) return $xml;
		$xml.="<fact type=\"".$this->type."\" scope=\"".$this->scope."\"";
		if (!$forAdd) {
			$xml .=" version=\"".$this->version."\" ";
			$xml.="modified=\"".$this->modified."\" id=\"".$this->id."\" disputing=\"".($this->disputing==false?"false":"true")."\" contributor=\"".$this->contributor."\"";
		}
		$xml.=">\n";
		if(!empty($this->citations)){
			$xml.="<citations>";
			foreach($this->citations as $citation){
				$xml.=$citation->toXml();
			}
			$xml.="</citations>\n";
		}
		
		if(!empty($this->notes)){
			$xml.="<notes>\n";
			foreach($this->notes as $note){
				if (is_object($note)) $xml.=$note->toXml();
				else $xml .="WHAT?".print_r($note, true);
			}
			$xml.="</notes>\n";
		}
		if(!empty($this->detail)){
			$xml.="<detail>".$this->detail."</detail>\n";
		}
		if(!empty($this->parent)){
			//TODO: Make <detail> not static
			$xml.="<detail>Biological</detail>\n";
			$xml.="<parent role=\"".$this->parent->getRole()."\" ref=\"".$this->parent->getRef()."\" />\n";
		}
		if(!empty($this->spouse)){
			$xml.="<spouse role=\"".$this->spouse->getRole()."\" ref=\"".$this->spouse->getRef()."\" />\n";
		}
		if(!empty($this->child)){
			$xml.="<child role=\"".$this->child->getRole()."\" ref=\"".$this->child->getRef()."\" />\n";
		}
		$xml.="</fact>\n";
		return $xml;
	}
	
	//Getters and addters

	function setScope($value){
		$this->scope=$value;
	}

	function getScope(){
		return $this->scope;
	}

	function setType($value){
		$this->type=$value;
	}

	function getType(){
		return $this->type;
	}

	function setTitle($value){
		$this->title=$value;
	}

	function getTitle(){
		return $this->title;
	}

	function setDate($value){
		$this->date=$value;
	}

	function getDate(){
		return $this->date;
	}

	function setPlace($value){
		$this->place = $value;
	}

	function getPlace(){
		return $this->place;
	}

	function setSpouse($value){
		$this->person->addSpouse($value);
		$this->spouse=$value;
	}

	function getSpouse(){
		return $this->spouse;
	}

	function setParent($value){
		$this->person->addParent($value);
		$this->parent=$value;
	}

	function getParent(){
		return $this->parent;
	}

	function setChild($value){
		$this->person->addChild($value);
		$this->child=$value;
	}

	function getChild(){
		return $this->child;
	}

	function setDetail($value){
		$this->detail=$value;
	}

	function getDetail(){
		return $this->detail;
	}

	//constructor
	function XG_Fact(){

		$this->factHandler["caste name"]="CAST";
		$this->factHandler["national id"]="SSN";
		$this->factHandler["national origin"]="NATI";
		$this->factHandler["nobility title"]="TITL";
		$this->factHandler["occupation"]="OCCU";
		$this->factHandler["physical description"]="DSCR";
		$this->factHandler["race"]="NATI";
		$this->factHandler["religious affiliation"]="RELI";
		$this->factHandler["stillborn"]="STIL";
		$this->factHandler["common law marriage"]="_COML";
		$this->factHandler["notes"]="NOTE";
	}

	//Functions

	function getAssertionType() {
		return $this->type;
	}

	function getIndiGedcom(){

		// make sure the info does not belong in a fmaily record
		if (!empty ($this->spouse) || !empty($this->child) || !empty($this->parent)){
			return "";
		}

		$lowerType=strtolower($this->type);
		$result="";
		$result = $this->hlprGedcom();
		return $result;

	}

	function getFamCGedcom($isMale=true){
		$result="";

		//set the resulting GEDCOM for the spouse and child
		if (!empty($this->parent)) {
			$result = $this->parent->getFamCGedcom($isMale);

			$result .= $this->getIndiGedcom();
		}
		return $result;
	}

	function getFamSGedcom($isMale=true){
		$result="";

		//set the resulting GEDCOM for the spouse and child
		if (!empty($this->spouse)) {
			$result = $this->spouse->getFamSGedcom(false, $isMale);
		}
		if (!empty($this->child)) {
			$result = $this->child->getFamSGedcom(true, $isMale);
		}

		//add fact on the end if there is a spouse or child
		if (!empty($result)){
			$result .= $this->getIndiGedcom();
		}
		return $result;
	}

	function hlprGedcom(){
		$result ="";
		$lowerType = strtolower($this->type);
		if ($lowerType=="notes") {
			return parent::getIndiGedcom(1);
		}
		else if(!empty($this->factHandler[$lowerType])){
			$result .= "1 ".$this->factHandler[$lowerType]." ".$this->detail." \r\n";
		}
		else{
			if($lowerType!="lineage"){
				$result .="1 FACT ".$this->detail."\r\n";
				$result .="2 TYPE ".$this->type."\r\n";
			}
		}
		//ignoring the scope in GEDCOM
		//ignoring the title in GEDCOM
		if(!empty($this->date)){
			$result .=$this->date->getGedcom();
		}
		if(!empty($this->place)){
			$result .=$this->place->getGedcom();
		}
		$result.=parent::getIndiGedcom(2);
		return $result;
	}
}

global $eventHandler;
$eventHandler["adoption"]="ADOP";
$eventHandler["adult christening"]="CHRA";
$eventHandler["baptism"]="BAPM";
$eventHandler["bar mitzvah"]="BARM";
$eventHandler["bas mitzvah"]="BASM";
$eventHandler["birth"]="BIRT";
$eventHandler["blessing"]="BLES";
$eventHandler["burial"]="BURI";
$eventHandler["christening"]="CHR";
$eventHandler["cremation"]="CREM";
$eventHandler["death"]="DEAT";
$eventHandler["graduation"]="GRAD";
$eventHandler["immigration"]="IMMI";
$eventHandler["military service"]="_MILT";
$eventHandler["naturalization"]="NATU";
$eventHandler["probate"]="PROB";
$eventHandler["retirement"]="RETI";
$eventHandler["will"]="WILL";
$eventHandler["annulment"]="ANUL";
$eventHandler["divorce"]="DIV";
$eventHandler["divorce filing"]="DIVF";
$eventHandler["marriage"]="MARR";
$eventHandler["marriage banns"]="MARB";
$eventHandler["marriage contract"]="MARC";
$eventHandler["marriage license"]="MARL";
$eventHandler["other"]="EVEN";

global $factHandler;
$factHandler["caste name"]="CAST";
$factHandler["national id"]="SSN";
$factHandler["national origin"]="NATI";
$factHandler["nobility title"]="TITL";
$factHandler["occupation"]="OCCU";
$factHandler["physical description"]="DSCR";
$factHandler["race"]="NATI";
$factHandler["religious affiliation"]="RELI";
$factHandler["stillborn"]="STIL";
$factHandler["common law marriage"]="_COML";
$factHandler["notes"]="NOTE";

global $ordinanceHandler;
$ordinanceHandler["baptism"] = "BAPL";
$ordinanceHandler["confirmation"] = "CONL";
$ordinanceHandler["endowment"] = "ENDL";
$ordinanceHandler["sealing to spouse"] = "SLGS";
$ordinanceHandler["sealing to parents"] = "SLGC";

/**
 * an event. Based off of the type of event and the description.
 * ignoring the scope and title.
 */
class XG_Event extends XG_Assertion{

	/*@var $spouse XG_PersonRef */
	//Fields
	var $scope;
	var $type;
	var $title;
	var $date;
	var $place;
	var $description;
	var $spouse;

	var $lowerEvent;
	var $result="";

	var $famsEventHandler=array();
	
	function getAssertionType() {
		return $this->type;
	}

	/**
	 * Override the setPerson method for events, this allows the setting of specific person events
	 * @param XG_Person $person
	 */
	function setPerson(&$person) {
		parent::setPerson($person);
		if ($this->type=='Birth') $person->setBirthAssertion($this);
		if ($this->type=='Death') $person->setDeathAssertion($this);
		if ($this->type=='Marriage') $person->setMarriageAssertion($this);
	}

	function toXml($forAdd=false){
		$xml='';
		if ($forAdd && ($this->scope!='person' || !empty($this->spouse))) return '';
		$xml.="<event type=\"".$this->type."\" scope=\"".$this->scope."\"";
		if (!$forAdd) {
			$xml.=" version=\"".$this->version."\" modified=\"".$this->modified."\" ";
			$xml.="id=\"".$this->id."\" disputing=\"".($this->disputing==false?"false":"true")."\" contributor=\"".$this->contributor."\"";
		}
		$xml.=">\n";
		if(!empty($this->citations)){
			$xml.="<citations>\n";
			foreach($this->citations as $citation){
				$xml.=$citation->toXml();
			}
			$xml.="</citations>\n";
		}
		if(!empty($this->notes)){
			$xml.="<notes>\n";
			foreach($this->notes as $note){
				$xml.=$note->toXml();
			}
			$xml.="</notes>\n";
		}
		if(!empty($this->date))$xml.=$this->date->toXml();
		if(!empty($this->place))$xml.=$this->place->toXml();
		if(!empty($this->spouse)){
			$xml.="<spouse role=\"".$this->spouse->getRole()."\" ref=\"".$this->spouse->getRef()."\" />\n";
		}
		$xml.="</event>\n";
		return $xml;
	}

	//Getters and addters

	function setScope($value){
		$this->scope=$value;
	}

	function getScope(){
		return $this->scope;
	}

	function setType($value){
		$this->type=$value;
	}

	function getType(){
		return $this->type;
	}

	function setTitle($value){
		$this->title=$value;
	}

	function getTitle(){
		return $this->title;
	}

	function setDate($value){
		$this->date=$value;
	}

	/**
	 * Get the assertion's date
	 *
	 * @return XG_Date
	 */
	function getDate(){
		return $this->date;
	}

	function setPlace($value){
		$this->place=$value;
	}

	function getPlace(){
		return $this->place;
	}

	function setDescription($description){
		$this->description = $description;
	}

	function getDescription(){
		return $this->description;
	}

	function setSpouse($spouse){
		$this->spouse = $spouse;
	}

	function getSpouse(){
		return $this->spouse;
	}

	//Constructor
	function XG_Event(){
		parent::XG_Assertion();
	}

	/**
	 * get GEDCOM portion for this event from the type, description, date and place
	 */
	function getIndiGedcom(){
		global $eventHandler;

		$result="";
		$lowerType=strtolower($this->type);

		// get GEDCOM for the event
		if(!empty($eventHandler[$lowerType])){
			$result .= "1 ".$eventHandler[$lowerType]." \r\n";
		}else{
			$result .="1 EVEN \r\n";
			$result .="1 TYPE ".$this->type." ".$this->description."\r\n";
		}

		//add any dates and places
		if(!empty($this->date)){
			$result .= $this->date->getGedcom();
		}
		if(!empty($this->place)){
			$result .= $this->place->getGedcom();
		}

		//add GEDCOM from parent if any
		$result.=parent::getIndiGedcom(2);
		return $result;
		//mission
		//move
	}

	/**
	 *  gets the GEDCOM for the family record where person is a Spouse.
	 */
	function getFamSGedcom($isMale=true){
		$result="";
		if (!empty($this->spouse)) {
			$result = $this->spouse->getFamSGedcom(false, $isMale);

			$result .= $this->getIndiGedcom();
		}
		return $result;
	}
}

class XG_Error {
	var $message;
	var $code;

	function getMessage() {
		return $this->message;
	}

	function setMessage($message) {
		$this->message = $message;
	}

	function getCode() {
		return $this->code;
	}

	function setCode($code) {
		$this->code = $code;
	}
}
?>