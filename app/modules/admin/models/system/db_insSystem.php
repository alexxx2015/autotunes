<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function inset a user into database
 *********************************************************************************/
include_once('classes/DB.php');

function db_insSystem($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	INSERT INTO system (
									sysOnline,
									sysSiteName,
									sysRegister,
									sysStdGroup,
									sysLogin,
									sysCarMarket,
									sysBikeMarket,
									sysTruckMarket,
									sysEMail,
									sysTelNr,
									sysDataImp,
									sysDataExp,
									sysImp,
									sysDisc,
									timestam,
									ip
								) VALUES(
								"'.$db -> escape($p['sysOnline']).'",
								"'.$db -> escape($p['sysSiteName']).'",
								"'.$db -> escape($p['sysRegister']).'",
								"'.$db -> escape($p['sysStdGroup']).'",
								"'.$db -> escape($p['sysLogin']).'",
								"'.$db -> escape($p['sysCarMarket']).'",
								"'.$db -> escape($p['sysBikeMarket']).'",
								"'.$db -> escape($p['sysTruckMarket']).'",
								"'.$db -> escape($p['sysEMail']).'",
								"'.$db -> escape($p['sysTelNr']).'",
								"'.$db -> escape($p['sysDataImp']).'",
								"'.$db -> escape($p['sysDataExp']).'",
								"'.$db -> real_escape($p['sysImp']).'",
								"'.$db -> real_escape($p['sysDisc']).'",
								UNIX_TIMESTAMP(),
								"'.$db -> escape(System_Properties::getIP()).'"
								)';	
	
	$return = $db->execQuery(array('q'=>$query));	
	return $return;
}
?>