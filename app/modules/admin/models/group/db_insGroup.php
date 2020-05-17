<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select group data
 *********************************************************************************/
include_once('classes/DB.php');

function db_insGroup($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	INSERT INTO grouup (groupName, timestam, ip, erased)
				VALUES("'.$db -> escape($p['groupName']).'", UNIX_TIMESTAMP(), "'.System_Properties::getIP().'", "0")';
	
	$return = $db->execQuery(array('q'=>$query));
	/*
	if ($return != false){
		$groupID = $return;
		foreach ($p['authorityID'] as $authorityID){
			$query = 'INSERT INTO authorityMapping (groupID, authorityID) VALUES("'.$groupID.'", "'.$authorityID.'")';
			$db->execQuery(array('q'=>$query));
		}
	}
	*/

	return $return;
}
?>