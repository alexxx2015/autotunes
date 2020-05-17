<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert a single product category
 *********************************************************************************/
include_once('classes/DB.php');

function db_insProdCat($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO prodCat (lft, rgt, active)
				VALUES(	"'.$db -> escape($p['lft']).'"
						,"'.$db -> escape($p['rgt']).'"
						,"'.$db -> escape($p['active']).'"
						)';
	
	$return = $db -> execQuery(array('q'=>$query));
	return $return;
}
?>
