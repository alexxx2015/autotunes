<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select user data
 *********************************************************************************/
include_once('classes/DB.php');

function db_delFTPUser($p=array()){
	$return = false;
	
	$db = DB::getInstance(array('DB_NAME' => DB::DB_FROXLOR_NAME
								, 'DB_USER' => DB::DB_FROXLOR_USER
								, 'DB_PW' => DB::DB_FROXLOR_PW
								, 'NEW_INSTANCE' => true
								));
								
	if (isset($p['CUSTOMERID'])){
		$query = 'DELETE FROM panel_customers WHERE customerid = "'.$db -> escape($p['CUSTOMERID']).'"';
		$db -> execQuery(array('q'=>$query));
		
		$query = 'DELETE FROM ftp_groups WHERE customerid = "'.$db -> escape($p['CUSTOMERID']).'"';
		$db -> execQuery(array('q'=>$query));
		
		$query = 'DELETE FROM ftp_users WHERE customerid = "'.$db -> escape($p['CUSTOMERID']).'"';
		$db -> execQuery(array('q'=>$query));
	}
	
	if (isset($p['USERNAME'])){
		$query = 'DELETE FROM panel_customers WHERE loginname = "'.$db -> escape($p['USERNAME']).'"';
		$db -> execQuery(array('q'=>$query));
		
		$query = 'DELETE FROM ftp_groups WHERE members = "'.$db -> escape($p['USERNAME']).'"';
		$db -> execQuery(array('q'=>$query));
		
		$query = 'DELETE FROM ftp_users WHERE username = "'.$db -> escape($p['USERNAME']).'"';
		$db -> execQuery(array('q'=>$query));
		
		$query = 'DELETE FROM ftp_quotatallies WHERE name = "'.$db -> escape($p['USERNAME']).'"';
		$db -> execQuery(array('q'=>$query));
		
	}

	return $return;
}
?>