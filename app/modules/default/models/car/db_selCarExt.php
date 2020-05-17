<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical extras
 *********************************************************************************/
include_once('classes/DB.php');

function 	db_selCarExt($p=null){
	$return = false;
	
	$db = DB::getInstance();

	//set default active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
		
	$query = '	SELECT ce1.carExtID, ce1.lft, ce1.rgt, ce1.active, ce1.vextID, COUNT(*)-1 AS level, ROUND((ce1.rgt - ce1.lft - 1) / 2) AS children
				FROM carExt AS ce1, carExt AS ce2
				WHERE (ce1.active = "'.$db -> escape($p['active']).'") 
						AND (ce1.lft BETWEEN ce2.lft AND ce2.rgt) ';
	
	//carExtID
	if (isset($p['carExtID'])){
		if(is_array($p['carExtID'])){
			$query .= ' AND (ce1.carExtID IN ("'.$db -> escape(implode('","',$p['carExtID'])).'")) ';
		}else{
			$query .= ' AND (ce1.carExtID = '.$db -> escape($p['carExtID']).') ';
		}
	}
	
	//vextID
	if (isset($p['vextID'])){
		if (is_array($p['vextID'])){
			$query .= 'AND (ce1.vextID IN ("'.implode('","', $db -> escape($p['vextID'])).'")) ';
		}
		else{
			$query .= 'AND (ce1.vextID = "'.$db -> escape($p['vextID']).'") ';
		}
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (ce1.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (ce1.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (ce1.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (ce1.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (ce1.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (ce1.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (ce1.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (ce1.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (ce1.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (ce1.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}

	
	$query .= 'GROUP BY (ce1.lft) ';
	
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
