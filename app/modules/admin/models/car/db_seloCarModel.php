<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Car brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_seloCarModel($p=null){
	$return = false;
	
	$db = DB::getInstance();

	//model erased
	if (!isset($p['modErased'])){
		$p['modErased'] = 0;
	}
	
	$query = '	SELECT cm.*
				FROM carModel AS cm 
				WHERE (cm.erased = "'.$db -> escape($p['modErased']).'") ';
	
	$where = false;
	if(isset($p['carModelID'])){
		$query .= ' AND (cm.carModelID = "'.$db -> escape($p['carModelID']).'") ';
	}

	if(isset($p['carModelName'])){		
		$query .= ' AND (cm.carModelName = "'.$db -> escape($p['carModelName']).'") ';
	}

	if(isset($p['carBrandID'])){
		$query .= ' AND (cm.carBrandID = "'.$db -> escape($p['carBrandID']).'") ';
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= ' AND (cm.lft = "'.$p['lft'].'") ';		
	}
	//Right value
	if (isset($p['rgt'])){
		$query .= ' AND (cm.rgt = "'.$p['rgt'].'") ';		
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
