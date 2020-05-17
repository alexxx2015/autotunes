<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a car
 *********************************************************************************/
include_once('classes/DB.php');

function db_updCarBrand2Cat($p){	
	$return = false;
	$db = DB::getInstance();

	
	$query = '	UPDATE carBrand2Cat
				SET ';
	
	//carBrandID
	if(isset($p[System_Properties::SQL_SET]['carBrandID'])){		
		$query .= 'carBrandID = "'.$db -> escape($p[System_Properties::SQL_SET]['carBrandID']).'", ';
	}
	
	
	//carCatID
	if(isset($p[System_Properties::SQL_SET]['carCatID'])){		
		$query .= 'carCatID = "'.$db -> escape($p[System_Properties::SQL_SET]['carCatID']).'", ';
	}	
	$query = substr($query, 0, -2);
	
	//WHERE
	$where = false;
	
	//carCatID
	if (isset($p[System_Properties::SQL_WHERE]['carCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['carCatID'])){
			$query .= ' (carCatID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['carCatID'])).'") )';			
		}else{
			$query .= ' (carCatID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['carCatID']).'") ';
		}
	}		

	//carBrandID
	if (isset($p[System_Properties::SQL_WHERE]['carBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['carBrandID'])){
			$query .= ' (carBrandID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['carBrandID'])).'") )';			
		}else{
			$query .= ' (carBrandID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['carBrandID']).'") ';
		}
	}		
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	
	return $return;
}
?>
