<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function select all picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_selSys($p=null){
	$return = false;
	/*
	$db = DB::getInstance();
	
	$query = '	SELECT *
				FROM vPic as v1 
				WHERE (	v1.vType = "'.System_Properties::CAR_ABRV.'"
						or v1.vType = "'.System_Properties::BIKE_ABRV.'"
						or v1.vType = "'.System_Properties::TRUCK_ABRV.'") ';
	
	
	$return = $db->execQuery(array('q'=>$query));
	*/
	$return = array(
				'sysStdGroup' => 1
				);	

	return $return;
}
?>
