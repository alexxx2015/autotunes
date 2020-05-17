<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_insBikeModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	if (isset($p['bikeModelName']) && isset($p['bikeBrandID'])){
		$query = '	INSERT INTO bikeModel (bikeModelName, bikeBrandID, lft, rgt, erased)
					VALUES ("'.$db -> escape($p['bikeModelName']).'"
							, "'.$db -> escape($p['bikeBrandID']).'"
							, "'.$db -> escape($p['lft']).'"
							, "'.$db -> escape($p['rgt']).'", "0") ';
		
		$return = $db->execQuery(array('q'=>$query));
	}

	return $return;
}
?>