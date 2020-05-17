<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all bike categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_insBikeBrand2Cat($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO bikeBrand2Cat (bikeBrandID, bikeCatID)
				VALUES(	"'.$db -> escape($p['bikeBrandID']).'"
						,"'.$db -> escape($p['bikeCatID']).'"
						)';
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	return $return;
}
?>
