<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a bike
 *********************************************************************************/
include_once('classes/DB.php');

function db_insBikeExt($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	INSERT INTO bikeExt (lft, rgt, active, vextID)
				VALUES(	"'.$db -> escape($p['lft']).'"
						,"'.$db -> escape($p['rgt']).'"
						,"'.$db -> escape($p['active']).'"
						,"'.$db -> escape($p['vextID']).'"
						)';
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	return $return;
}
?>
