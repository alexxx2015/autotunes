<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function update a group database
 *********************************************************************************/
include_once('classes/DB.php');

function db_updateGroup($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE grouup SET ';
	
	if (isset($p['groupName'])){
		$query .= 'groupName = "'.$db -> escape($p['groupName']).'", ';
	}
	
	if (isset($p['erased'])){
		$query .= 'erased = "1", ';
	}	
	
	$query = substr($query, 0, -2).' ';
	
	if (isset($p['groupID'])){
		$query .= 'WHERE ( groupID = "'.$db -> escape($p['groupID']).'" ) ';
	}
	
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>