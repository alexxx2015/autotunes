<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_insCarModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	if (isset($p['carModelName']) && isset($p['carBrandID'])){
		$query = '	INSERT INTO carModel (carModelName, carBrandID, lft, rgt, erased)
					VALUES ("'.$db -> escape($p['carModelName']).'"
							, "'.$db -> escape($p['carBrandID']).'"
							, "'.$db -> escape($p['lft']).'"
							, "'.$db -> escape($p['rgt']).'", "0") ';
		
		$return = $db->execQuery(array('q'=>$query));
	}

	return $return;
}
?>