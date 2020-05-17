<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');
include_once('Zend/Json.php');

function db_insVisitor($p=null){
	$return = false;
	if (is_array($p)){
		$db = DB::getInstance();
		
		$query = '	INSERT INTO visitor (
						 url
						, referer
						, userAgent
						, timestam
						, ip
					) 
					VALUES (  "'.$db -> escape($p['url']).'"
							, "'.$db -> escape($p['referer']).'"
							, "'.$db -> escape($p['userAgent']).'"
							, UNIX_TIMESTAMP()
							, "'.System_Properties::getIP().'"
							)' ;
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
