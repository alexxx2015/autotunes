<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select all Car extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_cntCar2Ext($p=null){
	$return = false;
	$db = DB::getInstance();

	
	//car extra active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
	
	$query = '	SELECT COUNT(*) AS count_num
				FROM car2Ext ';
	
	$where = false;
	
	//carID
	if (isset($p['carID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (carID = "'.$db -> escape($p['carID']).'") ';
	}
	
	//carExtID
	if (isset($p['carExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (carExtID = "'.$db -> escape($p['carExtID']).'") ';
	}
	
	
	if (isset($p['p'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
