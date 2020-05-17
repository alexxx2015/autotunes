<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select truck categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTruckCat($p=null){
	$return = false;
	
	$db = DB::getInstance();

	//set default active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
		
	$query = '	SELECT tc1.truckCatID, tc1.lft, tc1.rgt, tc1.active, tc1.vcatID, COUNT(*)-1 AS level, ROUND((tc1.rgt - tc1.lft - 1) / 2) AS children
				FROM truckCat AS tc1, truckCat AS tc2
				WHERE (tc1.active = "'.$db -> escape($p['active']).'") 
						AND (tc2.active = "'.$db -> escape($p['active']).'") 
						AND (tc1.lft BETWEEN tc2.lft AND tc2.rgt) ';
	
	//truckCatID
	if (isset($p['truckCatID'])){
		if(is_array($p['truckCatID'])){
			$query .= ' AND (tc1.truckCatID IN ("'.$db -> escape(implode('","',$p['truckCatID'])).'")) ';
		}else{
			$query .= ' AND (tc1.truckCatID = '.$db -> escape($p['truckCatID']).') ';
		}
	}
	
	//vcatID
	if (isset($p['vcatID'])){
		if (is_array($p['vcatID'])){
			$query .= 'AND (tc1.vcatID IN ("'.implode('","', $db -> escape($p['vcatID'])).'")) ';
		}
		else{
			$query .= 'AND (tc1.vcatID = "'.$db -> escape($p['vcatID']).'") ';
		}
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (tc1.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (tc1.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (tc1.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (tc1.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (tc1.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (tc1.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (tc1.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (tc1.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (tc1.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (tc1.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}

	
	$query .= 'GROUP BY (tc1.lft) ';
	
	//Level
	if (isset($p['level'])){
		$query .= 'HAVING (level = "'.$db -> escape($p['level']).'") ';
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
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
