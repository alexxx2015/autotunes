<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function select language entries from database table
 *********************************************************************************/
include_once('classes/DB.php');

function db_selLang($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT *
				FROM lang AS l ';
	
	$where = false;
	if (isset($p['langID'])){
		if($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (l.langID = "'.$db -> escape($p['langID']).'") ';
	}
	
	if (isset($p['langAbrv'])){
		if($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (l.langAbrv = "'.$db -> escape($p['langAbrv']).'") ';
	}
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
