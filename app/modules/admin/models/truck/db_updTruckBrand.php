<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update a truck brand entry
 *********************************************************************************/
include_once('classes/DB.php');

function db_updTruckBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE truckBrand SET  ';
	
	//brandID
	if(isset($p[System_Properties::SQL_SET]['brandID'])){		
		$query .= 'brandID = "'.$db -> escape($p[System_Properties::SQL_SET]['brandID']).'", ';
	}
	
	//active
	if(isset($p[System_Properties::SQL_SET]['active'])){		
		$query .= 'active = "'.$db -> escape($p[System_Properties::SQL_SET]['active']).'", ';
	}
	
	$query = substr($query, 0, -2).' ';
	
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['brandID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (brandID = "'.$p[System_Properties::SQL_WHERE]['brandID'].'") ';
	}	
	
	if (isset($p[System_Properties::SQL_WHERE]['truckBrandID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}else{
			$query .= 'AND ';
		}
		$query .= ' (truckBrandID = "'.$p[System_Properties::SQL_WHERE]['truckBrandID'].'") ';
	}	
		
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
