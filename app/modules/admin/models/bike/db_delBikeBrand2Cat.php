<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_delBikeBrand2Cat($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	DELETE FROM bikeBrand2Cat ';
	
	$where = false;	
	//bikeBrand2CatID
	if (isset($p['bikeBrand2CatID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		
		$query .= ' (bikeBrand2CatID = "'.$p['bikeBrand2CatID'].'") ';
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
	
	//bikeCatID
	if (isset($p['v'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (bikeCatID = "'.$p['bikeCatID'].'") ';
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>