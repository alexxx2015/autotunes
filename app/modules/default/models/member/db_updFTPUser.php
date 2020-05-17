<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select user data
 *********************************************************************************/
include_once('classes/DB.php');

function db_updFTPUser($p=array()){
	$return = false;
	
	$db = DB::getInstance(array('DB_NAME' => DB::DB_FROXLOR_NAME
								, 'DB_USER' => DB::DB_FROXLOR_USER
								, 'DB_PW' => DB::DB_FROXLOR_PW
								, 'NEW_INSTANCE' => true
								));
								
	$query = '	UPDATE ftp_users 
				SET ';

	//ID
	if (isset($p[System_Properties::SQL_SET]['ID'])){
		$query .= 'id = "'.$db -> escape($p[System_Properties::SQL_SET]['ID']).'", ';
	}
	
	//LOGIN_ENABLED
	if (isset($p[System_Properties::SQL_SET]['LOGIN_ENABLED'])){
		$query .= 'login_enabled = "'.$db -> escape($p[System_Properties::SQL_SET]['LOGIN_ENABLED']).'", ';
	}
	
	//USERNAME
	if (isset($p[System_Properties::SQL_SET]['USERNAME'])){
		$query .= 'username = "'.$db -> escape($p[System_Properties::SQL_SET]['USERNAME']).'", ';
	}
	
	//PASSWORD	
	if (isset($p[System_Properties::SQL_SET]['PASSWORD']) && ($p[System_Properties::SQL_SET]['PASSWORD'] != false) && $p[System_Properties::SQL_SET]['PASSWORD'] != null){
		$query .= 'password = ENCRYPT("'.$p[System_Properties::SQL_SET]['PASSWORD'].'"), ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	//USERNAME
	if (isset($p[System_Properties::SQL_WHERE]['USERNAME'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (username = "'.$db -> escape($p[System_Properties::SQL_WHERE]['USERNAME']).'") ';
	}

	//ID
	if (isset($p[System_Properties::SQL_WHERE]['ID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (id = "'.$db -> escape($p[System_Properties::SQL_WHERE]['ID']).'") ';
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	if ($where != false){
		$return = $db->execQuery(array('q'=>$query));
	}
	
	if ($return != false){
		//Update Password in table panel_customers
		if (isset($p[System_Properties::SQL_SET]['PASSWORD'])){
			$query = 'UPDATE panel_customers SET password = "'.md5($p[System_Properties::SQL_SET]['PASSWORD']).'" ';
			if (isset($p[System_Properties::SQL_WHERE]['USERNAME'])){
				$query .= ' WHERE loginname = "'.$db -> escape($p[System_Properties::SQL_WHERE]['USERNAME']).'" ';
				$db->execQuery(array('q'=>$query));
			}
		}
	}

	return $return;
}
?>