<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select group data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selGroup($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT g.*
				FROM grouup AS g 
				WHERE (g.erased = 0) ';
	
	if (isset($p['groupID'])
		&& ($p['groupID'] != null)){
		$query .= ' AND (g.groupID = '.$db -> escape($p['groupID']).') ';
	}
	
	if (isset($p['groupName'])
		&& ($p['groupName'] != null)){
		$query .= ' AND (g.groupName = "'.$db -> escape($p['groupName']).'")';
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>