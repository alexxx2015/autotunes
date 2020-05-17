<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_insBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	if (isset($p['brandName'])){
		$query = '	INSERT INTO brand (brandName, erased) VALUES ("'.$db -> escape($p['brandName']).'", "0")';			
		$return = $db->execQuery(array('q'=>$query));
	}

	return $return;
}
?>