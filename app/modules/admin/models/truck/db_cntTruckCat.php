<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select all Truck extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_cntTruckCat($p=null){
	$return = false;
	$db = DB::getInstance();

	
	//truck extra active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
	
	$query = '	SELECT COUNT(*) AS count_num
				FROM truck ';
	
	$where = false;
	
	//truckID
	if (isset($p['truckID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (truckID = "'.$db -> escape($p['truckID']).'") ';
	}
	
	//truckCat
	if (isset($p['truckCat'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (truckCat = "'.$db -> escape($p['truckCat']).'") ';
	}
	
	
	if (isset($p['p'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
