<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Bike brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBikeModel($p=null){
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
	
	
	$query = '	SELECT bm.bikeModelID, bm.bikeModelName, bm.bikeBrandID, bm.lft, bm.rgt, bb.brandID, COUNT(*)-1 AS level, ROUND((bm.rgt - bm.lft - 1) / 2) AS children
				FROM bikeModel AS bm, bikeModel AS bm2, bikeBrand AS bb
				WHERE (bm.bikeBrandID = bb.bikeBrandID) 
						AND (bm.erased = "'.$db -> escape($p['modErased']).'")
						AND (bm.lft BETWEEN bm2.lft AND bm2.rgt) 
						AND EXISTS ( 	SELECT brandName
										FROM brand AS b
										WHERE bb.brandID = b.brandID
												AND b.erased = "'.$db -> escape($p['erased']).'" 
									) ';
	
	if(isset($p['bikeBrandID'])){		
		if (is_array($p['bikeBrandID'])){
			$p['bikeBrandID'] = $db -> escape($p['bikeBrandID']);
			$query .= 'AND (bm.bikeBrandID IN ("'.implode('","',$p['bikeBrandID']).'") )';
		}else{
			$query .= 'AND (bm.bikeBrandID = '.$db -> escape($p['bikeBrandID']).') ';
		}
	}

	if(isset($p['bikeModelID'])){
		if (is_array($p['bikeModelID'])){
			$p['bikeModelID'] = $db -> escape($p['bikeModelID']);
			$query .= 'AND (bm.bikeModelID IN ("'.implode('","',$p['bikeModelID']).'") )';
		}else{
			$query .= 'AND (bm.bikeModelID = '.$db -> escape($p['bikeModelID']).') ';
		}
	}
	
	if(isset($p['bikeModelName'])){
		$query .= 'AND (bm.bikeModelName = "'.$db -> escape($p['bikeModelName']).'") ';
	}elseif(isset($p['bikeModelNameL'])){
		$query .= 'AND (bm.bikeModelName = LOWER("'.$db -> escape($p['bikeModelNameL']).'") ) ';
	} 
		
	//active
	if (isset($p['active'])){
		$query .= ' AND (bb.active = '.$db -> escape($p['active']).') ';
	}else{
		$query .= ' AND (bb.active = "1") ';
	}
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (bm.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (bm.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (bm.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (bm.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (bm.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (bm.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (bm.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (bm.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (bm.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (bm.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}
	
	$query .= 'GROUP BY (bm.lft) ';
	
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
