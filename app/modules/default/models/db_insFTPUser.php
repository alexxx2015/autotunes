<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select user data
 *********************************************************************************/
include_once('classes/DB.php');

function db_insFTPUser($p=array()){
	$return = false;
	
	$db = DB::getInstance(array('DB_NAME' => DB::DB_FROXLOR_NAME
								, 'DB_USER' => DB::DB_FROXLOR_USER
								, 'DB_PW' => DB::DB_FROXLOR_PW
								, 'NEW_INSTANCE' => true
								));
			
	if (!isset($p['CUSTOMERID'])){
		$p['CUSTOMERID'] = '1';
	}			
	
	if (!isset($p['UID'])){
		$p['UID'] = '10000';
	}		
		
	if (!isset($p['GID'])){
		$p['GID'] = '10000';
	}		
		
	if (!isset($p['SHELL'])){
		$p['SHELL'] = '/bin/false';
	}
	
	$query = '	INSERT INTO ftp_users (	customerid
										, username
										, password
										, homedir
										, login_enabled
										, uid
										, gid
										, shell)
				VALUES("'.$db -> escape($p['CUSTOMERID']).'"
					, "'.$db -> escape($p['USERNAME']).'"
					, ENCRYPT("'.$p['PASSWORD'].'")
					, "'.$p['HOMEDIR'].'"
					, "'.$db -> escape($p['LOGIN_ENABLED']).'"
					, "'.$db -> escape($p['UID']).'"
					, "'.$db -> escape($p['GID']).'"
					, "'.$db -> escape($p['SHELL']).'"
					)';
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));
	
	if ($return != false){
		//Append user to FTP group
		$query = "	UPDATE ftp_groups 
					SET members = CONCAT_WS(',',members,'".$db -> escape($p['USERNAME'])."') 
					WHERE 	customerid = '".$db -> escape($p['CUSTOMERID'])."'
							AND gid = '".(int)$db -> escape($p['GID'])."'";
		$db -> execQuery(array('q'=>$query));
		
		//Append user to customers
		$query = "	UPDATE panel_customers 
					SET ftps_used = ftps_used +1
						, ftp_lastaccountnumber = ftp_lastaccountnumber +1 
					WHERE  customerid = '".$db -> escape($p['CUSTOMERID'])."'";
		$db -> execQuery(array('q'=>$query));

		//Add FTP quota limits
		$query = 'SELECT * FROM ftp_quotalimits WHERE name = "'.$db -> escape($p['USERNAME']).'"';
		$ftpQuotaLimit = $db->execQuery(array('q'=>$query));
		if ($ftpQuotaLimit == false){			
			$query = 'INSERT INTO ftp_quotalimits (name, quota_type, per_session, limit_type
												, bytes_in_avail, bytes_out_avail, bytes_xfer_avail
												, files_in_avail, files_out_avail, files_xfer_avail)
					VALUES ("'.$db -> escape($p['USERNAME']).'", "user", "false", "hard", 104857600, 0, 0, 2, 0, 0)';
			$db->execQuery(array('q'=>$query));			
		}
		
		//Add FTP quotatallies
		$query = 'SELECT * FROM ftp_quotatallies WHERE name = "'.$db -> escape($p['USERNAME']).'"';
		$ftpQuotaTallies = $db->execQuery(array('q'=>$query));
		if ($ftpQuotaTallies == false){
			$query = 'INSERT INTO ftp_quotatallies (name, quota_type
												, bytes_in_used, bytes_out_used, bytes_xfer_used
												, files_in_used, files_out_used, files_xfer_used) 
						VALUES ("'.$db -> escape($p['USERNAME']).'", "user", 0, 0, 0, 0, 0, 0)';
			$db->execQuery(array('q'=>$query));	
		}	
		/*
		//Add user to panel_customers
		$query = 'SELECT * FROM panel_customers WHERE loginname = "'.$db -> escape($p['USERNAME']).'"';
		$customerUser = $db->execQuery(array('q' => $query));
		if ($customerUser == false){
			$query = 'INSERT INTO panel_customers';
		}
		*/
	}

	return $return;
}
?>