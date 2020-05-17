<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insVPic($p=null){
	$return = false;
	if (is_array($p) && isset($p['vType']) && isset($p['vID'])){
		$db = DB::getInstance();
		
		$query = '	INSERT INTO vPic (vType, vID, vPicTMP, timestam)
					VALUES ("'.$db -> escape($p['vType']).'", 
							"'.$db -> escape($p['vID']).'",
							"'.(isset($p['vPicTMP'])?$db -> escape($p['vPicTMP']):'1').'",  
							UNIX_TIMESTAMP())';
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
