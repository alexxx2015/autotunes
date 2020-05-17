<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101219
 * Desc:		This function select informations from table email
 *********************************************************************************/
include_once('classes/DB.php');

function db_selEMail($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT *
				FROM eMail as e1 
				WHERE (	e1.vType = "'.System_Properties::CAR_ABRV.'"
						or e1.vType = "'.System_Properties::BIKE_ABRV.'"
						or e1.vType = "'.System_Properties::TRUCK_ABRV.'"
						or e1.vType = "'.System_Properties::IMP_ABRV.'") ';
	
	if(isset($p['senderID'])){
		$query .= ' AND (e1.senderID = '.$db -> escape($p['senderID']).') ';
	}
	
	if(isset($p['receiverID'])){
		$query .= ' AND (e1.receiverID = '.$db -> escape($p['receiverID']).') ';
	}
	
	if (isset($p['eMailText'])){
		$query .= ' AND (e1.eMailText = "'.$db -> escape($p['eMailText']).'") ';
	}
	
	if (isset($p['senderEMailAddress'])){
		$query .= ' AND (e1.senderEMailAddress = "'.$db -> escape($p['senderEMailAddress']).'") ';
	}
	
	if (isset($p['receiverEMailAddress'])){
		$query .= ' AND (e1.receiverEMailAddress = "'.$db -> escape($p['receiverEMailAddress']).'") ';
	}
	
	if (isset($p['senderEMailName'])){
		$query .= ' AND (e1.senderEMailName = "'.$db -> escape($p['senderEMailName']).'") ';
	}
	
	if (isset($p['vID'])){
		$query .= ' AND (e1.vID = "'.$db -> escape($p['vID']).'") ';
	}
	
	if (isset($p['vType'])){
		$query .= ' AND (e1.vType = "'.$db -> escape($p['vType']).'") ';
	}
	
	//ORDER BY
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= 'ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $orderby['col'];
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC';
			}
			$query .= ',';
		}		
		$query = substr($query, 0, -1);
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
