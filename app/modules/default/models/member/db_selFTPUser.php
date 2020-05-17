<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select user data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selFTPUser($p=array()){
	$return = false;
	
	$db = DB::getInstance(array('DB_NAME' => DB::DB_FROXLOR_NAME
								, 'DB_USER' => DB::DB_FROXLOR_USER
								, 'DB_PW' => DB::DB_FROXLOR_PW
								, 'NEW_INSTANCE' => true
								));
	if(!isset($p['DEACTIVATED'])){
		$p['DEACTIVATED'] = 0;
	}
	
	$query = '	SELECT *
				FROM ftp_users as fu, panel_customers as pc
				WHERE pc.loginname = fu.username 
					AND pc.deactivated '.(is_array($p['DEACTIVATED'])?' IN ("'.implode('","',$p['DEACTIVATED']).'")':' = "'.$db -> escape($p['DEACTIVATED']).'"').'
				 ';
	
	$where = true;
	if (isset($p['CUSTOMERID'])
		&& ($p['CUSTOMERID'] != null)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.customerid = "'.$db -> escape($p['CUSTOMERID']).'") ';
	}
	
	if (isset($p['ID'])
		&& ($p['ID'] != null)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.id = "'.$db -> escape($p['ID']).'") ';
	}
	
	if (isset($p['LOGIN_ENABLED']) && ($p['LOGIN_ENABLED'] == true)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.login_enabled = "Y") ';
	}
	
	if (isset($p['USERNAME'])
		&& ($p['USERNAME'] != null)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.username = "'.$db -> escape($p['USERNAME']).'") ';
	}
	
	if (isset($p['LAST_LOGIN'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_login = "'.$db -> escape($p['LAST_LOGIN']).'") ';
	}	
	elseif (isset($p['LAST_LOGIN_BEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_login >= "'.$db -> escape($p['LAST_LOGIN_BEq']).'") ';
	}	
	elseif (isset($p['LAST_LOGIN_LEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_login <= "'.$db -> escape($p['LAST_LOGIN_LEq']).'") ';
	}

	
	if (isset($p['LAST_LOGOUT'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_logout = "'.$db -> escape($p['LAST_LOGOUT']).'") ';
	}	
	elseif (isset($p['LAST_LOGOUT_BEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_logout >= "'.$db -> escape($p['LAST_LOGOUT_BEq']).'") ';
	}	
	elseif (isset($p['LAST_LOGOUT_LEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_logout <= "'.$db -> escape($p['LAST_LOGOUT_LEq']).'") ';
	}

	if (isset($p['LAST_LOGIN_BEq_LOGOUT']) && ($p['LAST_LOGIN_BEq_LOGOUT'] == true)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_login >= fu.last_logout) ';
	}
	elseif (isset($p['LAST_LOGIN_LEq_LOGOUT']) && ($p['LAST_LOGIN_LEq_LOGOUT'] == true)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.last_login <= fu.last_logout) ';
	}
	
	//homedir
	if (isset($p['HOMEDIR_LIKE'])
		&& ($p['HOMEDIR_LIKE'] != null)){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (fu.homedir LIKE "%'.$db -> escape($p['HOMEDIR_LIKE']).'%") ';
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