<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select car categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCarCat($p=null){
	$return = false;
	
	$db = DB::getInstance();

	//set default active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
		
	$query = '	SELECT cc1.carCatID, cc1.lft, cc1.rgt, cc1.active, cc1.vcatID, COUNT(*)-1 AS level, ROUND((cc1.rgt - cc1.lft - 1) / 2) AS children
				FROM carCat AS cc1, carCat AS cc2
				WHERE (cc1.active = "'.$db -> escape($p['active']).'")
						AND (cc2.active = "'.$db -> escape($p['active']).'")  
						AND (cc1.lft BETWEEN cc2.lft AND cc2.rgt) ';
	
	//carCatID
	if (isset($p['carCatID'])){
		if(is_array($p['carCatID'])){
			$query .= ' AND (cc1.carCatID IN ("'.implode('","',$db -> escape($p['carCatID'])).'")) ';
		}else{
			$query .= ' AND (cc1.carCatID = '.$db -> escape($p['carCatID']).') ';
		}
	}
	
	//vcatID
	if (isset($p['vcatID'])){
		if (is_array($p['vcatID'])){
			$query .= 'AND (cc1.vcatID IN ("'.implode('","', $db -> escape($p['vcatID'])).'")) ';
		}
		else{
			$query .= 'AND (cc1.vcatID = "'.$db -> escape($p['vcatID']).'") ';
		}
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (cc1.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (cc1.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (cc1.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (cc1.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (cc1.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (cc1.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (cc1.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (cc1.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (cc1.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (cc1.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}

	
	$query .= ' GROUP BY (cc1.lft) ';
	
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
