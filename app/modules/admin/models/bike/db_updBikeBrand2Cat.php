<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a bike
 *********************************************************************************/
include_once('classes/DB.php');

function db_updBikeBrand2Cat($p){	
	$return = false;
	$db = DB::getInstance();

	
	$query = '	UPDATE bikeBrand2Cat
				SET ';
	
	//bikeBrandID
	if(isset($p[System_Properties::SQL_SET]['bikeBrandID'])){		
		$query .= 'bikeBrandID = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeBrandID']).'", ';
	}
	
	
	//bikeCatID
	if(isset($p[System_Properties::SQL_SET]['bikeCatID'])){		
		$query .= 'bikeCatID = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeCatID']).'", ';
	}	
	$query = substr($query, 0, -2);
	
	//WHERE
	$where = false;
	
	//bikeCatID
	if (isset($p[System_Properties::SQL_WHERE]['bikeCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['bikeCatID'])){
			$query .= ' (bikeCatID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['bikeCatID'])).'") )';			
		}else{
			$query .= ' (bikeCatID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['bikeCatID']).'") ';
		}
	}		

	//bikeBrandID
	if (isset($p[System_Properties::SQL_WHERE]['bikeBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['bikeBrandID'])){
			$query .= ' (bikeBrandID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['bikeBrandID'])).'") )';			
		}else{
			$query .= ' (bikeBrandID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['bikeBrandID']).'") ';
		}
	}		
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	
	return $return;
}
?>
