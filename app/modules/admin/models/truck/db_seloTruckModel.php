<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Truck brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_seloTruckModel($p=null){
	$return = false;
	
	$db = DB::getInstance();

	//model erased
	if (!isset($p['modErased'])){
		$p['modErased'] = 0;
	}
	
	$query = '	SELECT tm.*
				FROM truckModel AS tm 
				WHERE (tm.erased = "'.$db -> escape($p['modErased']).'") ';
	
	$where = false;
	if(isset($p['truckModelID'])){
		$query .= ' AND (tm.truckModelID = "'.$db -> escape($p['truckModelID']).'") ';
	}

	if(isset($p['truckModelName'])){		
		$query .= ' AND (tm.truckModelName = "'.$db -> escape($p['truckModelName']).'") ';
	}

	if(isset($p['truckBrandID'])){
		$query .= ' AND (tm.truckBrandID = "'.$db -> escape($p['truckBrandID']).'") ';
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= ' AND (tm.lft = "'.$p['lft'].'") ';		
	}
	//Right value
	if (isset($p['rgt'])){
		$query .= ' AND (tm.rgt = "'.$p['rgt'].'") ';		
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
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
