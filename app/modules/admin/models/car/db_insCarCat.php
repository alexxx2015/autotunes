<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all car categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_insCarCat($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO carCat (lft, rgt, active, vcatID)
				VALUES(	"'.$db -> escape($p['lft']).'"
						,"'.$db -> escape($p['rgt']).'"
						,"'.$db -> escape($p['active']).'"
						,"'.$db -> escape($p['vcatID']).'"
						)';
	
	$db -> execQuery(array('q'=>$query));//, 'connect' => true));
	return $return;
}
?>
