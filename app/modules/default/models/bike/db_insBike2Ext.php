<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a bike
 *********************************************************************************/
include_once('classes/DB.php');

function db_insBike2Ext($p){	
	$return = false;
	$db = DB::getInstance();
	
	if (isset($p['bikeID']) && is_numeric($p['bikeID']) && isset($p['bikeExtID']) && is_numeric($p['bikeExtID'])){
		$query = '	INSERT INTO bike2Ext (bikeID, bikeExtID)
					VALUES('.$db -> escape($p['bikeID']).','.$db -> escape($p['bikeExtID']).')';

		$db -> execQuery(array('q'=>$query));
	}	
	return $return;
}
?>
