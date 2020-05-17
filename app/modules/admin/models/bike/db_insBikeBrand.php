<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_insBikeBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	if (isset($p['brandID'])){
		$query = '	INSERT INTO bikeBrand (brandID, active)
					VALUES ("'.$db -> escape($p['brandID']).'", "'.$db -> escape($p['active']).'" )';
		$return = $db->execQuery(array('q'=>$query));
	}

	return $return;
}
?>