<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function inset a user into database
 *********************************************************************************/
include_once('classes/DB.php');

function db_updateUser($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE user SET ';
	
	if (isset($p['userMode'])){
		$query .= 'userMode = "'.$db -> escape($p['userMode']).'", ';
	}
	
	if (isset($p['userFirm'])){
		$query .= 'userFirm = "'.$db -> escape($p['userFirm']).'", ';
	}
	
	if (isset($p['userNName'])){
		$query .= 'userNName = "'.$db -> escape($p['userNName']).'", ';
	}
	
	if (isset($p['userVName'])){
		$query .= 'userVName = "'.$db -> escape($p['userVName']).'", ';
	}
	
	if (isset($p['userPW']) && ($p['userPW'] != null)){
		$query .= 'userPW = "'.$p['userPW'].'", ';
	}
	
	if (isset($p['userEMail'])){
		$query .= 'userEMail = "'.$db -> escape($p['userEMail']).'", ';
	}
	
	if (isset($p['userAdress'])){
		$query .= 'userAdress = "'.$db -> escape($p['userAdress']).'", ';
	}
	
	if (isset($p['userPLZ'])){
		$query .= 'userPLZ = "'.$db -> escape($p['userPLZ']).'", ';
	}
	
	if (isset($p['userOrt'])){
		$query .= 'userOrt = "'.$db -> escape($p['userOrt']).'", ';
	}
	
	if (isset($p['userTel1'])){
		$query .= 'userTel1 = "'.$db -> escape($p['userTel1']).'", ';
	}
	
	if (isset($p['userTel2'])){
		$query .= 'userTel2 = "'.$db -> escape($p['userTel2']).'", ';
	}
	
	if (isset($p['userNews'])){
		$query .= 'userNews = "'.$db -> escape($p['userNews']).'", ';
	}
	
	if (isset($p['userAGB'])){
		$query .= 'userAGB = "'.$db -> escape($p['userAGB']).'", ';
	}

	if (isset($p['erased']) && ($p['erased'] == 1)){
		$query .= 'erased = "'.$db -> escape($p['erased']).'", ';
	}
	
	if (isset($p['userStat'])){
		$query .= 'userStat = "'.$db -> escape($p['userStat']).'", ';
	}
	
	if (isset($p['userLinkAds'])){
		$query .= 'userLinkAds = "'.$db -> escape($p['userLinkAds']).'", ';
	}
	
	if (isset($p['datexImp'])){
		$query .= 'datexImp = "'.$db -> escape($p['datexImp']).'", ';
	}
	
	if (isset($p['datexFTPImp'])){
		$query .= 'datexFTPImp = "'.$db -> escape($p['datexFTPImp']).'", ';
	}
	
	if (isset($p['datexFTPVType'])){
		$query .= 'datexFTPVType = "'.$db -> escape($p['datexFTPVType']).'", ';
	}
	
	if (isset($p['datexAutoImp'])){
		$query .= 'datexAutoImp = "'.$db -> escape($p['datexAutoImp']).'", ';
	}
	
	if (isset($p['datexExp'])){
		$query .= 'datexExp = "'.$db -> escape($p['datexExp']).'", ';
	}	
	
	if (isset($p['datexFormat'])){
		$query .= 'datexFormat = "'.$db -> escape($p['datexFormat']).'", ';
	}	
	
	$query = substr($query, 0, -2).' ';
	
	if (isset($p['userID'])){
		$query .= 'WHERE ( userID = "'.$db -> escape($p['userID']).'" ) ';
	}
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	
	if ((isset($p['groupID']) || isset($p['userGroup'])) && isset($p['userID'])){
		isset($p['groupID'])?$groupID = $p['groupID']:isset($p['userGroup'])?$groupID = $p['userGroup']:$groupID = null;
		$query = '	UPDATE groupmember 
					SET groupID = "'.$groupID.'"
					WHERE userID = "'.$db -> escape($p['userID']).'"';
		$return = $db->execQuery(array('q'=>$query));
	}
	return $return;
}
?>