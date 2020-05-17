<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select user data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selUser($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT u1.*, g.*
				FROM user AS u1, groupmember AS gm, grouup AS g 
				WHERE (u1.erased = 0) 
						AND (u1.userID = gm.userID) 
						AND (gm.groupID = g.groupID)';
	
	if (isset($p['userID'])
		&& ($p['userID'] != null)){
		$query .= ' AND (u1.userID = '.$db -> escape($p['userID']).') ';
	}
	
	if (isset($p['userEMail'])
		&& ($p['userEMail'] != null)){
		$query .= ' AND (u1.userEMail = "'.$db -> escape($p['userEMail']).'")';
	}
	
	if (isset($p['userNName'])
		&& ($p['userNName'] != null)){
		$query .= ' AND (u1.userNName = "'.$db -> escape($p['userNName']).'")';
	}
	
	if (isset($p['userVName'])
		&& ($p['userVName'] != null)){
		$query .= ' AND (u1.userVName = "'.$db -> escape($p['userVName']).'")';
	}
	
	if (isset($p['userPW'])
		&& ($p['userPW'] != null)){
		$query .= ' AND (u1.userPW = "'.$db -> escape($p['userPW']).'")';
	}
	
	if (isset($p['userPLZ'])
		&& ($p['userPLZ'] != null)){
		$query .= ' AND (u1.userPLZ = "'.$db -> escape($p['userPLZ']).'")';
	}
	
	if (isset($p['userOrt'])
		&& ($p['userOrt'] != null)){
		$query .= ' AND (u1.userOrt = "'.$db -> escape($p['userOrt']).'")';
	}
	
	if (isset($p['userExtID'])
		&& ($p['userExtID'] != null)){
		$query .= ' AND (u1.userExtID = "'.$db -> escape($p['userExtID']).'")';
	}
	
	if (isset($p['groupID'])
		&& ($p['groupID'] != null)){
		$query .= ' AND (g.groupID = '.$db -> escape($p['groupID']).')';
	}
	
	if (isset($p['groupName'])
		&& ($p['groupName'] != null)){
		$query .= ' AND (g.groupName = "'.$db -> escape($p['groupName']).'")';
	}
	
	if (isset($p['userGroup']) && ($p['userGroup'] != -1)){
		$query .= ' AND (gm.groupID = "'.$p['userGroup'].'") ';
	}
	
	//ORDER BY
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= ' ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $orderby['col'];
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC';
			}
			$query .= ',';
		}		
		$query = substr($query, 0, -1);
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>