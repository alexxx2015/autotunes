<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select group data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selAuthorityMapping($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT am.groupID, a.*
				FROM authorityMapping as am, authority as a 
				WHERE (am.authorityID = a.authorityID) ';
	
	if (isset($p['groupID'])){
		$query .= ' AND (am.groupID = "'.$db -> escape($p['groupID']).'") ';
	}
	
	if (isset($p['authorityID'])
		&& ($p['authorityID'] != null)){
		$query .= ' AND (am.authorityID = "'.$db -> escape($p['authorityID']).'") ';
	}
	/*
	if (isset($p['authorityValue'])
		&& ($p['authorityValue'] != null)){
		$query .= ' AND (a.authorityValue = "'.$db -> escape($p['authorityValue']).'")';
	}
	*/
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>