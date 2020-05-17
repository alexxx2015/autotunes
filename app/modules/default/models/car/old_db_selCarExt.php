<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select all Car extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCarExt($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT c1.carExtID, v1.vextID, v1.outsideID 
				FROM carExt AS c1, vext AS v1 
				WHERE 	(c1.vextID = v1.vextID) 
						AND (v1.car = 1) ';
	
	if(isset($p['carID'])){
		$query .= 'AND (c1.carID = '.$db -> escape($p['carID']).') ';
	}
	
	if (isset($p['vextID'])){
		$query .= 'AND (v1.vextID = '.$db -> escape($p['vextID']).') ';
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
