<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_delCarModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM carModel ';
	
	$where = false;	
	//carModelID
	if (isset($p['carModelID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (carModelID = "'.$p['carModelID'].'") ';
	}
	
	//carModelName
	if (isset($p['carModelName'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (carModelName = "'.$p['carModelName'].'") ';
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
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>