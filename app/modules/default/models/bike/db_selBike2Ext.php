<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select all Bike extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBike2Ext($p=null){
	$return = false;
	$db = DB::getInstance();

	
	//bike extra active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
	
	$query = '	SELECT c2e.*, ce.vextID
				FROM bike2Ext as c2e, bikeExt as ce
				WHERE ( c2e.bikeExtID = ce.bikeExtID ) 
					AND (ce.active = '.$db -> escape($p['active']).') ';	

	//bike2ExtID
	if(isset($p['bike2ExtID'])){
		$query .= 'AND (c2e.bike2ExtID = '.$db -> escape($p['bike2ExtID']).') ';
	}	

	//bikeID
	if(isset($p['bikeID'])){
		$query .= 'AND (c2e.bikeID = '.$db -> escape($p['bikeID']).') ';
	}	

	//bikeExtID
	if(isset($p['bikeExtID'])){
		$query .= 'AND (c2e.bikeExtID = '.$db -> escape($p['bikeExtID']).') ';
	}

	//vextID
	if(isset($p['vextID'])){
		$query .= 'AND (ce.vextID = '.$db -> escape($p['vextID']).') ';
	}
	
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= 'ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $db -> escape($orderby['col']);
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC';
			}
			$query .= ',';
		}		
		$query = substr($query, 0, -1);
	}
	
	if (isset($p['p'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
