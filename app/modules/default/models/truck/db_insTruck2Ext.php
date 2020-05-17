<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a truck
 *********************************************************************************/
include_once('classes/DB.php');

function db_insTruck2Ext($p){	
	$return = false;
	$db = DB::getInstance();
	
	if (isset($p['truckID']) && is_numeric($p['truckID']) && isset($p['truckExtID']) && is_numeric($p['truckExtID'])){
		$query = '	INSERT INTO truck2Ext (truckID, truckExtID)
					VALUES('.$db -> escape($p['truckID']).','.$db -> escape($p['truckExtID']).')';

		$db -> execQuery(array('q'=>$query));
	}	
	return $return;
}
?>
