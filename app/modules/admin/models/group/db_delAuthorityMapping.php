<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function delete entries from the table authorityMapping
 *********************************************************************************/
include_once('classes/DB.php');

function db_delAuthorityMapping($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM authorityMapping ';
	
	$where = false;
	if (isset($p['groupID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$query .= ' AND ';
		}
		$query .= 'groupID = "'.$db -> escape($p['groupID']).'", ';
	}
	
	if (isset($p['authorityID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$query .= ' AND ';
		}
		$query .= 'authorityID = "'.$db -> escape($p['authorityID']).'", ';
	}
	$query = substr($query, 0, -2);
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>