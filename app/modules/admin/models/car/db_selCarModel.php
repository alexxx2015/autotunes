<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Car brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCarModel($p=null){
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
	
	
	$query = '	SELECT cm.carModelID, cm.carModelName, cm.carBrandID, cm.lft, cm.rgt, cb.brandID, COUNT(*)-1 AS level, ROUND((cm.rgt - cm.lft - 1) / 2) AS children, cm.erased
				FROM carModel AS cm, carModel AS cm2, carBrand AS cb
				WHERE (cm.carBrandID = cb.carBrandID) 
						AND (cm.erased = "'.$db -> escape($p['modErased']).'")
						AND (cm.lft BETWEEN cm2.lft AND cm2.rgt) 
						AND EXISTS ( 	SELECT brandName
										FROM brand AS b
										WHERE cb.brandID = b.brandID
												AND b.erased = "'.$db -> escape($p['erased']).'" 
									) ';
	
	if(isset($p['carBrandID'])){		
		if (is_array($p['carBrandID'])){
			$p['carBrandID'] = $db -> escape($p['carBrandID']);
			$query .= 'AND (cm.carBrandID IN ("'.implode('","',$p['carBrandID']).'") )';
		}else{
			$query .= 'AND (cm.carBrandID = '.$db -> escape($p['carBrandID']).') ';
		}
	}
	
	//carModel
	if(isset($p['carModelID'])){
		if (is_array($p['carModelID'])){
			$p['carModelID'] = $db -> escape($p['carModelID']);
			$query .= 'AND (cm.carModelID IN ("'.implode('","',$p['carModelID']).'") )';
		}else{
			$query .= 'AND (cm.carModelID = '.$db -> escape($p['carModelID']).') ';
		}
	} 
	
	if(isset($p['carModelName'])){
		$query .= 'AND (cm.carModelName = "'.$db -> escape($p['carModelName']).'") ';
	}elseif(isset($p['carModelNameL'])){
		$query .= 'AND (cm.carModelName = LOWER("'.$db -> escape($p['carModelNameL']).'") ) ';
	} 
	
	//active
	if (isset($p['active'])){
		$query .= ' AND (cb.active = '.$db -> escape($p['active']).') ';
	}else{
		$query .= ' AND (cb.active = "1") ';
	} 
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (cm.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	else if (isset($p['lftLEq'])){
		$query .= 'AND (cm.lft <= "'.$db -> escape($p['lftLEq']).'") ';
	}
	else if (isset($p['lftLE'])){
		$query .= 'AND (cm.lft < "'.$db -> escape($p['lftLE']).'") ';
	}
	else if (isset($p['lftBEq'])){
		$query .= 'AND (cm.lft >= "'.$db -> escape($p['lftBEq']).'") ';
	}
	else if (isset($p['lftBE'])){
		$query .= 'AND (cm.lft > "'.$db -> escape($p['lftBE']).'") ';
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (cm.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	else if (isset($p['rgtBEq'])){
		$query .= 'AND (cm.rgt >= "'.$db -> escape($p['rgtBEq']).'") ';
	}
	else if (isset($p['rgtBE'])){
		$query .= 'AND (cm.rgt > "'.$db -> escape($p['rgtBE']).'") ';
	}
	else if (isset($p['rgtLEq'])){
		$query .= 'AND (cm.rgt <= "'.$db -> escape($p['rgtLEq']).'") ';
	}
	else if (isset($p['rgtLE'])){
		$query .= 'AND (cm.rgt < "'.$db -> escape($p['rgtLE']).'") ';
	}
	
	$query .= 'GROUP BY (cm.lft) ';
	
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
