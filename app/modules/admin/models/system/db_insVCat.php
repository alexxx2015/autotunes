<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		Insert an category entry into database
 *********************************************************************************/
include_once('classes/DB.php');

function db_insVCat($p=null){
	$return = false;
	
	$db = DB::getInstance();
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
	
	$query = '	INSERT INTO vcat (erased
								) VALUES(
								"'.$db -> escape($p['erased']).'")';	
	
	$return = $db->execQuery(array('q'=>$query));	
	return $return;
}
?>