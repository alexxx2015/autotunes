<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select product category from the database
 *********************************************************************************/
include_once('classes/DB.php');

function db_selProdCat($p=array()){
	$return = false;
	
	$db = DB::getInstance();
	
	if (!isset($p['active'])){
		$p['active'] = '1';
	}
	
	$query = '	SELECT p1.prodCatID, p1.lft, p1.rgt, p1.active, COUNT(*)-1 AS level, ROUND((p1.rgt - p1.lft - 1) / 2) AS children
				FROM prodCat as p1, prodCat as p2 
				WHERE (p1.active = "'.$db -> escape($p['active']).'")  
						AND (p2.active = "'.$db -> escape($p['active']).'")  
						AND (p1.lft BETWEEN p2.lft AND p2.rgt) ';
	
	//prodCatID
	if (isset($p['prodCatID'])
		&& ($p['prodCatID'] != null)){
		if (is_array($p['prodCatID'])){
			$query .= ' AND (p1.prodCatID IN ("'.implode('","', $db -> escape($p['prodCatID'])).'")) ';	
		}else{
			$query .= ' AND (p1.prodCatID = "'.$db -> escape($p['prodCatID']).'") ';
		}
	}
	
	//tagID
	if (isset($p['tagID'])
		&& ($p['tagID'] != null)){
		$query .= ' AND (p1.tagID = "'.$db -> escape($p['tagID']).'") ';
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (p1.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (p1.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (p1.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (p1.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (p1.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (p1.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (p1.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (p1.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (p1.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (p1.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}
	
	$query .= ' GROUP BY (p1.lft) ';
	
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