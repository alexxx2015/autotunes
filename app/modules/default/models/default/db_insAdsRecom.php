<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20111108
 * Desc:		Insert a relationship between two advertisement
 *********************************************************************************/
include_once('classes/DB.php');
include_once('classes/System_Properties.php');

function db_insAdsRecom($p){
	$return = false;
	
	$db = DB::getInstance();
	
	$query1 = ' INSERT INTO adsRecom ( timestam, ';
	$query2 = ' VALUES ( UNIX_TIMESTAMP(), ';
	
	//vID1
	if (isset($p['vID1'])){
		$query1 .= 'vID1, ';
		$query2 .= '"'.$db -> escape($p['vID1']).'",';
	}
	
	//vID2
	if (isset($p['vID2'])){
		$query1 .= 'vID2, ';
		$query2 .= '"'.$db -> escape($p['vID2']).'",';
	}
	
	//vType
	if (isset($p['vType'])){
		$query1 .= 'vType, ';
		$query2 .= '"'.$db -> escape($p['vType']).'",';
	}
	
	$query1 .= 'ip )';
	$query2 .= '"'.System_Properties::getIP().'")';

	$query = $query1.' '.$query2;
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
