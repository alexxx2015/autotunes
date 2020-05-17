<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_delCarBrand2Cat($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM carBrand2Cat ';
	
	$where = false;	
	//carBrand2CatID
	if (isset($p['carBrand2CatID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		
		$query .= ' (carBrand2CatID = "'.$p['carBrand2CatID'].'") ';
	}
	
	//carBrandID
	if (isset($p['carBrandID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (carBrandID = "'.$p['carBrandID'].'") ';
	}
	
	//carCatID
	if (isset($p['v'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (carCatID = "'.$p['carCatID'].'") ';
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>