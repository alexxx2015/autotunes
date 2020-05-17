<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Truck brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTruckModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	//brand erased
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
	//model erased
	if (!isset($p['modErased'])){
		$p['modErased'] = 0;
	}
	
	
	$query = '	SELECT tm.truckModelID, tm.truckModelName, tm.truckBrandID, tm.lft, tm.rgt, tb.brandID, COUNT(*)-1 AS level, ROUND((tm.rgt - tm.lft - 1) / 2) AS children, tm.erased 
				FROM truckModel AS tm, truckModel AS tm2, truckBrand AS tb
				WHERE (tm.truckBrandID = tb.truckBrandID) 
						AND (tm.erased = "'.$db -> escape($p['modErased']).'")
						AND (tm.lft BETWEEN tm2.lft AND tm2.rgt) 
						AND EXISTS ( 	SELECT brandName
										FROM brand AS b
										WHERE tb.brandID = b.brandID
												AND b.erased = "'.$db -> escape($p['erased']).'" 
									) ';
	
	if(isset($p['truckBrandID'])){		
		if (is_array($p['truckBrandID'])){
			$p['truckBrandID'] = $db -> escape($p['truckBrandID']);
			$query .= 'AND (tm.truckBrandID IN ("'.implode('","',$p['truckBrandID']).'") )';
		}else{
			$query .= 'AND (tm.truckBrandID = '.$db -> escape($p['truckBrandID']).') ';
		}
	}

	if(isset($p['truckModelID'])){
		if (is_array($p['truckModelID'])){
			$p['truckModelID'] = $db -> escape($p['truckModelID']);
			$query .= 'AND (tm.truckModelID IN ("'.implode('","',$p['truckModelID']).'") )';
		}else{
			$query .= 'AND (tm.truckModelID = '.$db -> escape($p['truckModelID']).') ';
		}
	}
	
	if(isset($p['truckModelName'])){
		$query .= 'AND (tm.truckModelName = "'.$db -> escape($p['truckModelName']).'") ';
	}elseif(isset($p['truckModelNameL'])){
		$query .= 'AND (tm.truckModelName = LOWER("'.$db -> escape($p['truckModelNameL']).'") ) ';
	} 
	
	//active
	if (isset($p['active'])){
		$query .= ' AND (tb.active = '.$db -> escape($p['active']).') ';
	}else{
		$query .= ' AND (tb.active = "1") ';
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (tm.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (tm.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (tm.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (tm.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (tm.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (tm.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (tm.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (tm.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (tm.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (tm.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}
	
	$query .= 'GROUP BY (tm.lft) ';
	
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
	
	if (isset($p['p'])){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
