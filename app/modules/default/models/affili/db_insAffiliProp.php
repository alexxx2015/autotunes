<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insAffiliProp($p=null){
	$return = false;
	
	//affiliID
	if(!isset($p['affiliID']) || ($p['affiliID'] == null) || ($p['affiliID'] == '')){
		return $return;
	}
	
	$db = DB::getInstance();
	
	//perform escape
	$p = $db -> escape($p);
	
	$query1 = 'affiliID';
	$query2 = $p['affiliID'];
	
	//propName
	if(isset($p['propName']) && ($p['propName'] != null) && ($p['propName'] != '')){
	  $query1 .= ', propName';
	  $query2 .= ', "'.$db -> escape($p['propName']).'"';
	}
	
	//propValue
	if(isset($p['propValue']) && ($p['propValue'] != null) && ($p['propValue'] != '')){
	  $query1 .= ', propValue';
	  $query2 .= ', "'.$db -> escape($p['propValue']).'"';
	}
	
	//Build complete query
	$query = '	INSERT INTO affiliProp( '.$query1.' ) VALUES ( '.$query2.' )';

	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
