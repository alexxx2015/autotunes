<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_delCarBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM carBrand ';
	
	$where = false;	
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
	
	//brandID
	if (isset($p['brandID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (brandID = "'.$p['brandID'].'") ';
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>