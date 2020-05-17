<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function insert an entry into table authorityMapping
 *********************************************************************************/
include_once('classes/DB.php');

function db_insAuthorityMapping($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	INSERT INTO authorityMapping (groupID, authorityID)
				VALUES("'.$db -> escape($p['groupID']).'", "'.$db -> escape($p['authorityID']).'")';
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>