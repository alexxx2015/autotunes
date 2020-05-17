<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selExtra($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT v1.vextID, v1.outsideID, v1.car, v1.bike, v1.truck 
				FROM vext AS v1 ';
	
	$where = false;
	if (isset($p['vextID'])){
		$query .= 'WHERE ';
		$where = true;
		if(is_array($p['vextID'])){
			$query .= '(v1.vextID IN ('.$db -> escape(implode(',', $p['vextID'])).')) ';
		}
		else{
			$query .= '(v1.vextID = '.$db -> escape($p['vextID']).') ';
		}
	}
	
	if(isset($p['vType'])){
		if ($where == false){
			$query .= 'WHERE ';
		}
		else{
			$query .= 'AND ';
		}
		switch (strtolower($p['vType'])){
			case System_Properties::CAR_ABRV : 	$query .= '(v1.car = 1) ';
						break;
			case System_Properties::BIKE_ABRV : 	$query .= '(v1.bike = 1) ';
						break;
			case System_Properties::TRUCK_ABRV : 	$query .= '(v1.truck = 1) ';
						break;
		}		
	}
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
