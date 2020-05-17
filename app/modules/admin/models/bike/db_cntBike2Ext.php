<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select all Bike extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_cntBike2Ext($p=null){
	$return = false;
	$db = DB::getInstance();

	
	//bike extra active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
	
	$query = '	SELECT COUNT(*) AS count_num
				FROM bike2Ext ';
	
	$where = false;
	
	//bikeID
	if (isset($p['bikeID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (bikeID = "'.$db -> escape($p['bikeID']).'") ';
	}
	
	//bikeExtID
	if (isset($p['bikeExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (bikeExtID = "'.$db -> escape($p['bikeExtID']).'") ';
	}
	
	
	if (isset($p['p'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
