<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');
include_once('Zend/Json.php');

function db_insBoookmark($p=null){
	$return = false;
	if (is_array($p) && isset($p['vehicleType']) && isset($p['vehicleID']) && isset($p['userID'])){
		$db = DB::getInstance();
		
		$query = '	INSERT INTO bookmark (
						 vehicleType
						, vehicleID
						, userID
						, timestam
					) 
					VALUES (  "'.$db -> escape($p['vehicleType']).'"
							, "'.$db -> escape($p['vehicleID']).'"
							, "'.$db -> escape($p['userID']).'"
							, '.(isset($p['timestam'])?'"'.$db -> escape($p['timestam']).'"':'UNIX_TIMESTAMP()').'
							)' ;
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
