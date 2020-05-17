<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_delBikeModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM bikeModel ';
	
	$where = false;	
	//bikeModelID
	if (isset($p['bikeModelID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (bikeModelID = "'.$p['bikeModelID'].'") ';
	}
	
	//bikeModelName
	if (isset($p['bikeModelName'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (bikeModelName = "'.$p['bikeModelName'].'") ';
	}
	
	//bikeBrandID
	if (isset($p['bikeBrandID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (bikeBrandID = "'.$p['bikeBrandID'].'") ';
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>