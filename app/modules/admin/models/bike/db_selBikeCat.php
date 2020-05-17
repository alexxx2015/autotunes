<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select bike categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBikeCat($p=null){
	$return = false;
	
	$db = DB::getInstance();

	//set default active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
		
	$query = '	SELECT bc1.bikeCatID, bc1.lft, bc1.rgt, bc1.active, bc1.vcatID, COUNT(*)-1 AS level, ROUND((bc1.rgt - bc1.lft - 1) / 2) AS children
				FROM bikeCat AS bc1, bikeCat AS bc2
				WHERE (bc1.active = "'.$db -> escape($p['active']).'") 
						AND (bc2.active = "'.$db -> escape($p['active']).'") 
						AND (bc1.lft BETWEEN bc2.lft AND bc2.rgt) ';
	
	//bikeCatID
	if (isset($p['bikeCatID'])){
		if(is_array($p['bikeCatID'])){
			$query .= ' AND (bc1.bikeCatID IN ("'.$db -> escape(implode('","',$p['bikeCatID'])).'")) ';
		}else{
			$query .= ' AND (bc1.bikeCatID = '.$db -> escape($p['bikeCatID']).') ';
		}
	}
	
	//vcatID
	if (isset($p['vcatID'])){
		if (is_array($p['vcatID'])){
			$query .= 'AND (bc1.vcatID IN ("'.implode('","', $db -> escape($p['vcatID'])).'")) ';
		}
		else{
			$query .= 'AND (bc1.vcatID = "'.$db -> escape($p['vcatID']).'") ';
		}
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (bc1.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (bc1.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (bc1.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (bc1.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (bc1.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (bc1.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (bc1.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (bc1.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (bc1.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (bc1.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}

	
	$query .= 'GROUP BY (bc1.lft) ';
	
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
