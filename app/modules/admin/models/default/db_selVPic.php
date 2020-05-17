<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function select all picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_selVPic($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT *
				FROM vPic as v1 
				WHERE (	v1.vType = "'.System_Properties::CAR_ABRV.'"
						or v1.vType = "'.System_Properties::BIKE_ABRV.'"
						or v1.vType = "'.System_Properties::TRUCK_ABRV.'") ';
	
	if(isset($p['vPicID'])){
		$query .= 'AND ';
		if (is_array($p['vPicID'])){
			$query .= ' (v1.vPicID IN ( "'.implode('","',$db -> escape($p['vPicID'])).'" ) ) ';
		}else{
			$query .= ' (v1.vPicID = '.$db -> escape($p['vPicID']).') ';
		}
	}
	
	if(isset($p['notVPicID'])){
		$query .= 'AND ';
		if (is_array($p['notVPicID'])){
			$query .= ' (v1.vPicID NOT IN ( "'.implode('","',$db -> escape($p['notVPicID'])).'" ) ) ';
		}else{
			$query .= ' NOT(v1.vPicID = '.$db -> escape($p['notVPicID']).') ';
		}
	}
	
	if (isset($p['vType'])){
		$query .= 'AND (v1.vType = "'.$db -> escape($p['vType']).'") ';
	}
	
	if (isset($p['vID'])){
		$query .= 'AND (v1.vID = '.$db -> escape($p['vID']).') ';
	}
	
	if (isset($p['vPicTMP'])){
		$query .= 'AND (v1.vPicTMP = "'.$db -> escape($p['vPicTMP']).'") ';
	}//echo $query;
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
