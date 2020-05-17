<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all truck categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_insTruckCat($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO truckCat (lft, rgt, active, vcatID)
				VALUES(	"'.$db -> escape($p['lft']).'"
						,"'.$db -> escape($p['rgt']).'"
						,"'.$db -> escape($p['active']).'"
						,"'.$db -> escape($p['vcatID']).'"
						)';
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	return $return;
}
?>
