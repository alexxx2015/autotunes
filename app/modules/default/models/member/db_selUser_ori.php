<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101122
 * Desc:		This function select user data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selUser($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT * 
				FROM user AS u1 
				WHERE (u1.erased = 0) ';
	
	if (isset($p['userID'])){
		$query .= ' AND (u1.userID = '.$p['userID'].') ';
	}
	$return = $db->execQuery(array('q'=>$query));	
	echo $query;

	return $return;
}
?>
