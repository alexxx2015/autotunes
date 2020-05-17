<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_delTruckBrand2Cat($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM truckBrand2Cat ';
	
	$where = false;	
	//truckBrand2CatID
	if (isset($p['truckBrand2CatID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		
		$query .= ' (truckBrand2CatID = "'.$p['truckBrand2CatID'].'") ';
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
	
	//truckCatID
	if (isset($p['v'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (truckCatID = "'.$p['truckCatID'].'") ';
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>