<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		2011-06-10
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');
include_once('Zend/Json.php');

function db_insDatex($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = 'INSERT INTO datex ( datexType
								, timestam
								, ip
								, userID
								, vType
								, datexFormat
								, datexPic ) 
							VALUES ( "'.$db -> escape($p['datexType']).'"
									, UNIX_TIMESTAMP()
									, "'.System_Properties::getIP().'"
									, "'.$db -> escape($p['userID']).'"
									, "'.$db -> escape($p['vType']).'"
									, "'.$db -> escape($p['datexFormat']).'"
									, "'.$db -> escape($p['datexPic']).'"
									)							
									';
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
