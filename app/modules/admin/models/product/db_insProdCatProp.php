<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert a single property for a product category
 *********************************************************************************/
include_once('classes/DB.php');

function db_insProdCatProp($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO prodCatProp (prodCatID, prodCatPropAbrv, lft, rgt, active)
				VALUES(	"'.$db -> escape($p['prodCatID']).'"
						,"'.$db -> escape($p['prodCatPropAbrv']).'"
						,"'.$db -> escape($p['lft']).'"
						,"'.$db -> escape($p['rgt']).'"
						,"'.$db -> escape($p['active']).'"
						)';
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db -> execQuery(array('q'=>$query));
	return $return;
}
?>
