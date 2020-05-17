<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select group member data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selGroupMember($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT g.*
				FROM groupmember as g ';
	$where = false;
	
	if (isset($p['groupID'])
		&& ($p['groupID'] != null)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (g.groupID = '.$db -> escape($p['groupID']).') ';
	}
	
	if (isset($p['userID'])
		&& ($p['userID'] != null)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (g.userID = "'.$db -> escape($p['userID']).'") ';
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>