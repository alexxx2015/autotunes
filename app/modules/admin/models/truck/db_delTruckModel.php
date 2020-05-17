<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_delTruckModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM truckModel ';
	
	$where = false;	
	//truckModelID
	if (isset($p['truckModelID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (truckModelID = "'.$p['truckModelID'].'") ';
	}
	
	//truckModelName
	if (isset($p['truckModelName'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (truckModelName = "'.$p['truckModelName'].'") ';
	}
	
	//truckBrandID
	if (isset($p['truckBrandID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (truckBrandID = "'.$p['truckBrandID'].'") ';
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>