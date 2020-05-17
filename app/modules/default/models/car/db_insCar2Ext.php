<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a car
 *********************************************************************************/
include_once('classes/DB.php');

function db_insCar2Ext($p){	
	$return = false;
	$db = DB::getInstance();
	
	if (isset($p['carID']) && is_numeric($p['carID']) && isset($p['carExtID']) && is_numeric($p['carExtID'])){
		$query = '	INSERT INTO car2Ext (carID, carExtID)
					VALUES('.$db -> escape($p['carID']).','.$db -> escape($p['carExtID']).')';

		$db -> execQuery(array('q'=>$query));
	}	
	return $return;
}
?>
