<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function delete entries from the table authorityMapping
 *********************************************************************************/
include_once('classes/DB.php');

function db_delGroup($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM grouup ';
	
	$where = false;
	if (isset($p['groupID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$query .= ' AND ';
		}
		$query .= ' groupID = "'.$db -> escape($p['groupID']).'", ';
	}
	
	if (isset($p['groupName'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$query .= ' AND ';
		}
		$query .= ' groupName = "'.$db -> escape($p['groupName']).'", ';
	}
	$query = substr($query, 0, -2);
	$return = $db->execQuery(array('q'=>$query));
	echo $query;

	return $return;
}
?>