<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all car categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_insCarBrand2Cat($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO carBrand2Cat (carBrandID, carCatID)
				VALUES(	"'.$db -> escape($p['carBrandID']).'"
						,"'.$db -> escape($p['carCatID']).'"
						)';
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	return $return;
}
?>
