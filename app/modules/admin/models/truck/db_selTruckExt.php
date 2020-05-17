<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTruckExt($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	//extra erased
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
	
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
		
	$query = '	SELECT te1.truckExtID, te1.lft, te1.rgt, te1.active, te1.vextID, COUNT(*)-1 AS level, ROUND((te1.rgt - te1.lft - 1) / 2) AS children
				FROM truckExt AS te1, truckExt AS te2
				WHERE (te1.active = "'.$db -> escape($p['active']).'") 
						AND (te2.active = "'.$db -> escape($p['active']).'") 
						AND (te1.lft BETWEEN te2.lft AND te2.rgt)
						';
	
	//truckExtID
	if (isset($p['truckExtID'])){
		if(is_array($p['truckExtID'])){
			$query .= ' AND (te1.truckExtID IN ("'.$db -> escape(implode('","',$p['truckExtID'])).'")) ';
		}else{
			$query .= ' AND (te1.truckExtID = '.$db -> escape($p['truckExtID']).') ';
		}
	}
	
	//vextID
	if (isset($p['vextID'])){
		if (is_array($p['vextID'])){
			$query .= 'AND (te1.vextID IN ("'.$db -> escape(implode('","', $p['vextID'])).'")) ';
		}
		else{
			$query .= 'AND (te1.vextID = "'.$db -> escape($p['vextID']).'") ';
		}
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (te1.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (te1.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (te1.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (te1.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (te1.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (te1.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (te1.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (te1.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (te1.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (te1.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}

	
	$query .= 'GROUP BY (te1.lft) ';
	
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
