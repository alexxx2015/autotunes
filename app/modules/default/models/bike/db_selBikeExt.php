<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBikeExt($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	//extra erased
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
	
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
		
	$query = '	SELECT be1.bikeExtID, be1.lft, be1.rgt, be1.active, be1.vextID, COUNT(*)-1 AS level, ROUND((be1.rgt - be1.lft - 1) / 2) AS children
				FROM bikeExt AS be1, bikeExt AS be2
				WHERE (be1.active = "'.$db -> escape($p['active']).'") 
						AND (be1.lft BETWEEN be2.lft AND be2.rgt) ';
	
	//bikeExtID
	if (isset($p['bikeExtID'])){
		if(is_array($p['bikeExtID'])){
			$query .= ' AND (be1.bikeExtID IN ("'.$db -> escape(implode('","',$p['bikeExtID'])).'")) ';
		}else{
			$query .= ' AND (be1.bikeExtID = '.$db -> escape($p['bikeExtID']).') ';
		}
	}
	
	//vextID
	if (isset($p['vextID'])){
		if (is_array($p['vextID'])){
			$query .= 'AND (be1.vextID IN ("'.implode('","', $db -> escape($p['vextID'])).'")) ';
		}
		else{
			$query .= 'AND (be1.vextID = "'.$db -> escape($p['vextID']).'") ';
		}
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (be1.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (be1.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (be1.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (be1.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (be1.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (be1.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (be1.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (be1.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (be1.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (be1.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}

	
	$query .= 'GROUP BY (be1.lft) ';
	
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
	
	if (isset($p['print'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	

	return $return;
}
?>
