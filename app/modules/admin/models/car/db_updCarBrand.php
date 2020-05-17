<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an car advertisement in table CAR
 *********************************************************************************/
include_once('classes/DB.php');

function db_updCarBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE carBrand SET  ';
	
	//brandID
	if(isset($p[System_Properties::SQL_SET]['brandID'])){		
		$query .= 'brandID = "'.$db -> escape($p[System_Properties::SQL_SET]['brandID']).'", ';
	}
	
	//active
	if(isset($p[System_Properties::SQL_SET]['active'])){		
		$query .= 'active = "'.$db -> escape($p[System_Properties::SQL_SET]['active']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['brandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (brandID = "'.$p[System_Properties::SQL_WHERE]['brandID'].'") ';
	}	
	
	if (isset($p[System_Properties::SQL_WHERE]['carBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (carBrandID = "'.$p[System_Properties::SQL_WHERE]['carBrandID'].'") ';
	}	
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
