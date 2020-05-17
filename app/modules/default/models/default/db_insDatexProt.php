<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		2011-06-10
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');
include_once('Zend/Json.php');

function db_insDatexProt($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = 'INSERT INTO datexProt (vID
									, datexID
									, datexProt
									) 
							VALUES ( "'.$db -> escape($p['vID']).'"
									, "'.$db -> escape($p['datexID']).'"
									, "'.$db -> escape($p['datexProt']).'"
									)							
									';
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
