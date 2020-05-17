<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select group data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selAuthority($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT a.*
				FROM authority as a 
				WHERE (a.authorityID > 0) ';
	
	if (isset($p['authorityID'])
		&& ($p['authorityID'] != null)){
		if (is_array($p['authorityID'])){
			$query .= ' AND (a.authorityID IN ("'.implode('","', $p['authorityID']).'")) ';	
		}else{
			$query .= ' AND (a.authorityID = '.$db -> escape($p['authorityID']).') ';
		}
	}
	
	if (isset($p['authorityName'])
		&& ($p['authorityName'] != null)){
		$query .= ' AND (a.authorityName = "'.$db -> escape($p['authorityName']).'") ';
	}
	/*
	if (isset($p['authorityValue'])
		&& ($p['authorityValue'] != null)){
		$query .= ' AND (a.authorityValue = "'.$db -> escape($p['authorityValue']).'")';
	}
	*/
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>