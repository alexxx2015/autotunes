<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all truck categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_insTruckBrand2Cat($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO truckBrand2Cat (truckBrandID, truckCatID)
				VALUES(	"'.$db -> escape($p['truckBrandID']).'"
						,"'.$db -> escape($p['truckCatID']).'"
						)';
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	return $return;
}
?>
