<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Bike brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_seloBikeModel($p=null){
	$return = false;
	
	$db = DB::getInstance();

	//model erased
	if (!isset($p['modErased'])){
		$p['modErased'] = 0;
	}
	
	$query = '	SELECT bm.*
				FROM bikeModel AS bm 
				WHERE (bm.erased = "'.$db -> escape($p['modErased']).'") ';
	
	$where = false;
	if(isset($p['bikeModelID'])){
		$query .= ' AND (bm.bikeModelID = "'.$db -> escape($p['bikeModelID']).'") ';
	}

	if(isset($p['bikeModelName'])){		
		$query .= ' AND (bm.bikeModelName = "'.$db -> escape($p['bikeModelName']).'") ';
	}

	if(isset($p['bikeBrandID'])){
		$query .= ' AND (bm.bikeBrandID = "'.$db -> escape($p['bikeBrandID']).'") ';
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= ' AND (bm.lft = "'.$p['lft'].'") ';		
	}
	//Right value
	if (isset($p['rgt'])){
		$query .= ' AND (bm.rgt = "'.$p['rgt'].'") ';		
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
