<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a truck
 *********************************************************************************/
include_once('classes/DB.php');

function db_updTruckBrand2Cat($p){	
	$return = false;
	$db = DB::getInstance();

	
	$query = '	UPDATE truckBrand2Cat
				SET ';
	
	//truckBrandID
	if(isset($p[System_Properties::SQL_SET]['truckBrandID'])){		
		$query .= 'truckBrandID = "'.$db -> escape($p[System_Properties::SQL_SET]['truckBrandID']).'", ';
	}
	
	
	//truckCatID
	if(isset($p[System_Properties::SQL_SET]['truckCatID'])){		
		$query .= 'truckCatID = "'.$db -> escape($p[System_Properties::SQL_SET]['truckCatID']).'", ';
	}	
	$query = substr($query, 0, -2);
	
	//WHERE
	$where = false;
	
	//truckCatID
	if (isset($p[System_Properties::SQL_WHERE]['truckCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['truckCatID'])){
			$query .= ' (truckCatID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['truckCatID'])).'") )';			
		}else{
			$query .= ' (truckCatID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['truckCatID']).'") ';
		}
	}		

	//truckBrandID
	if (isset($p[System_Properties::SQL_WHERE]['truckBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['truckBrandID'])){
			$query .= ' (truckBrandID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['truckBrandID'])).'") )';			
		}else{
			$query .= ' (truckBrandID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['truckBrandID']).'") ';
		}
	}		
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	
	return $return;
}
?>
