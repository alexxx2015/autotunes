<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function inset a user into database
 *********************************************************************************/
include_once('classes/DB.php');

function db_insUser($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	INSERT INTO user (
									userMode,
									userFirm,
									userNName,
									userVName,
									userPW,
									userEMail,
									userAdress,
									userPLZ,
									userOrt,
									userTel1,
									userTel2,
									userNews,
									userAGB,
									userStat,
									timestam,
									ip,
									datexImp,
									datexFTPImp,
									datexFTPVType,
									datexExp,
									datexAutoImp,
									datexFormat,
									userExtID,
									userCountry,
									userURL		
								) VALUES(
								"'.$db -> escape($p['userMode']).'",
								"'.$db -> escape($p['userFirm']).'",
								"'.$db -> escape($p['userNName']).'",
								"'.$db -> escape($p['userVName']).'",
								"'.$p['userPW'].'",
								"'.$db -> escape($p['userEMail']).'",
								"'.$db -> escape($p['userAdress']).'",
								"'.$db -> escape($p['userPLZ']).'",
								"'.$db -> escape($p['userOrt']).'",
								"'.$db -> escape($p['userTel1']).'",
								"'.$db -> escape($p['userTel2']).'",
								"'.$db -> escape($p['userNews']).'",
								"'.$db -> escape($p['userAGB']).'",
								"'.$db -> escape($p['userStat']).'",
								UNIX_TIMESTAMP(),
								"'.System_Properties::getIP().'",
								"'.$db -> escape($p['datexImp']).'",
								"'.$db -> escape($p['datexFTPImp']).'",
								"'.$db -> escape($p['datexFTPVType']).'",
								"'.$db -> escape($p['datexExp']).'",
								"'.$db -> escape($p['datexAutoImp']).'",
								"'.$db -> escape($p['datexFormat']).'",
								"'.$db -> escape($p['userExtID']).'",
								"'.$db -> escape($p['userCountry']).'",
								"'.$db -> escape($p['userURL']).'"
								)';	
	
	$return = $db->execQuery(array('q'=>$query));	
	
	if (($return != false)
		&& is_numeric($return)){
		$userID = $return;
		$query = '	INSERT INTO groupmember (userID, groupID)
					VALUES ("'.$db -> escape($userID).'", "'.$db -> escape($p['groupID']).'")';
		$db->execQuery(array('q'=>$query));	
	}
	return $return;
}
?>