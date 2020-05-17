<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insTrovitPic($p=null){
	$return = false;
	if (is_array($p) && isset($p['vType']) && isset($p['vID'])){
		$db = DB::getInstance();
		
		$query = '	INSERT INTO trovitPic (trovitID, picture_url, picture_title)
					VALUES ("'.$db -> escape($p['trovitID']).'"
							, "'.$db -> escape($p['picture_url']).'"
							, "'.$db -> escape($p['picture_title']).'"
							)';
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
