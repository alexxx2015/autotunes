<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110623
 * Desc:		This function select all AS24 data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCronj($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT * 
				FROM cronj as c ';
	
	$where = false;
	//ADD cronjID
	if (isset($p['cronjID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjID = "'.$db -> escape($p['cronjID']).'" ) ';
	}
	//ADD cronjName
	if (isset($p['cronjName'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjName = "'.$db -> escape($p['cronjName']).'" ) ';
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
