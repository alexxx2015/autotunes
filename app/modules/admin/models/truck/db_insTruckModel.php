<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_insTruckModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	if (isset($p['truckModelName']) && isset($p['truckBrandID'])){
		$query = '	INSERT INTO truckModel (truckModelName, truckBrandID, lft, rgt, erased)
					VALUES ("'.$db -> escape($p['truckModelName']).'"
							, "'.$db -> escape($p['truckBrandID']).'"
							, "'.$db -> escape($p['lft']).'"
							, "'.$db -> escape($p['rgt']).'", "0") ';
		
		if (isset($p['print']) && ($p['print'] == true)){
			echo $query;
		}
		$return = $db->execQuery(array('q'=>$query));
	}

	return $return;
}
?>