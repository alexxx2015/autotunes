<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select user data
 *********************************************************************************/
include_once('classes/DB.php');

function db_updFTPUserQuotatallies($p=array()){
	$return = false;
	
	$db = DB::getInstance(array('DB_NAME' => DB::DB_FROXLOR_NAME
								, 'DB_USER' => DB::DB_FROXLOR_USER
								, 'DB_PW' => DB::DB_FROXLOR_PW
								, 'NEW_INSTANCE' => true
								));
								
	$query = '	UPDATE ftp_quotatallies
				SET ';

	//name
	if (isset($p[System_Properties::SQL_SET]['NAME'])){
		$query .= 'name = "'.$db -> escape($p[System_Properties::SQL_SET]['NAME']).'", ';
	}
	
	//quota_type
	if (isset($p[System_Properties::SQL_SET]['QUOTA_TYPE'])){
		$query .= 'quota_type = "'.$db -> escape($p[System_Properties::SQL_SET]['QUOTA_TYPE']).'", ';
	}
	
	//bytes_in_used
	if (isset($p[System_Properties::SQL_SET]['BYTES_IN_USED'])){
		$query .= 'bytes_in_used = "'.$db -> escape($p[System_Properties::SQL_SET]['BYTES_IN_USED']).'", ';
	}	
	//bytes_in_used_sub
	elseif (isset($p[System_Properties::SQL_SET]['SUB_BYTES_IN_USED'])){
		$query .= 'bytes_in_used = bytes_in_used - '.$db -> escape($p[System_Properties::SQL_SET]['SUB_BYTES_IN_USED']).', ';
	}
	
	//bytes_out_used	
	if (isset($p[System_Properties::SQL_SET]['BYTES_OUT_USED'])){
		$query .= 'bytes_out_used = "'.$db -> escape($p[System_Properties::SQL_SET]['BYTES_OUT_USED']).'", ';
	}
	
	//bytes_xfer_used	
	if (isset($p[System_Properties::SQL_SET]['BYTES_XFER_USED'])){
		$query .= 'bytes_xfer_used = "'.$db -> escape($p[System_Properties::SQL_SET]['BYTES_XFER_USED']).'", ';
	}
	
	//files_in_used
	if (isset($p[System_Properties::SQL_SET]['FILES_IN_USED'])){
		$query .= 'files_in_used = "'.$db -> escape($p[System_Properties::SQL_SET]['FILES_IN_USED']).'", ';
	}
	
	//files_out_used	
	if (isset($p[System_Properties::SQL_SET]['FILES_OUT_USED'])){
		$query .= 'files_out_used = "'.$db -> escape($p[System_Properties::SQL_SET]['FILES_OUT_USED']).'", ';
	}
	
	//files_xfer_used	
	if (isset($p[System_Properties::SQL_SET]['FILES_XFER_USED'])){
		$query .= 'files_xfer_used = "'.$db -> escape($p[System_Properties::SQL_SET]['FILES_XFER_USED']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	//NAME
	if (isset($p[System_Properties::SQL_WHERE]['NAME'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (name = "'.$db -> escape($p[System_Properties::SQL_WHERE]['NAME']).'") ';
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	if($where != false){
		$return = $db->execQuery(array('q'=>$query));
	}

	return $return;
}
?>