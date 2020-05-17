<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101219
 * Desc:		This databse function insert an entry into the eMail database table
 *********************************************************************************/
include_once('classes/DB.php');
include_once('classes/System_Properties.php');

function db_insEMail($p){
	$return = false;
	
	$db = DB::getInstance();
	
	$query1 = ' INSERT INTO eMail ( ';
	$query2 = ' VALUES ( ';

	if (isset($p['senderID'])){
		$query1 .= 'senderID, ';
		$query2 .= '"'.$db -> escape($p['senderID']).'",';
	}

	if (isset($p['receiverID'])){
		$query1 .= 'receiverID, ';
		$query2 .= '"'.$db -> escape($p['receiverID']).'",';
	}
	
	if (isset($p['eMailText'])){
		$query1 .= 'eMailText, ';
		$query2 .= '"'.$db -> escape($p['eMailText']).'",';
	}
	
	if (isset($p['senderEMailAddress'])){
		$query1 .= 'senderEMailAddress, ';
		$query2 .= '"'.$db -> escape($p['senderEMailAddress']).'",';
	}
	
	if (isset($p['receiverEMailAddress'])){
		$query1 .= 'receiverEMailAddress, ';
		$query2 .= '"'.$db -> escape($p['receiverEMailAddress']).'",';
	}
	
	if (isset($p['senderEMailName'])){
		$query1 .= 'senderEMailName, ';
		$query2 .= '"'.$db -> escape($p['senderEMailName']).'",';
	}
	
	if (isset($p['vID'])){
		$query1 .= 'vID, ';
		$query2 .= '"'.$db -> escape($p['vID']).'",';
	}
	
	if (isset($p['vType'])){
		$query1 .= 'vType, ';
		$query2 .= '"'.$db -> escape($p['vType']).'",';
	}
	
	$query1 .= 'ip, timestam )';
	$query2 .= '"'.System_Properties::getIP().'", UNIX_TIMESTAMP() )';

	$query = $query1.' '.$query2;
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
