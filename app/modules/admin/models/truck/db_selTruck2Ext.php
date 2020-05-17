<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select all Truck extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTruck2Ext($p=null){
	$return = false;
	$db = DB::getInstance();

	
	//truck extra active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
	
	$query = '	SELECT c2e.*, ce.vextID
				FROM truck2Ext as c2e, truckExt as ce
				WHERE ( c2e.truckExtID = ce.truckExtID ) 
					AND (ce.active = '.$db -> escape($p['active']).') ';	

	//truck2ExtID
	if(isset($p['truck2ExtID'])){
		$query .= 'AND (c2e.truck2ExtID = '.$db -> escape($p['truck2ExtID']).') ';
	}	

	//truckID
	if(isset($p['truckID'])){
		$query .= 'AND (c2e.truckID = '.$db -> escape($p['truckID']).') ';
	}	

	//truckExtID
	if(isset($p['truckExtID'])){
		$query .= 'AND (c2e.truckExtID = '.$db -> escape($p['truckExtID']).') ';
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
