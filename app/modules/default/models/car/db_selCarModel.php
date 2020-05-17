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
	
	$query = '	SELECT cm.carModelID, cm.carModelName, cm.carBrandID, cm.lft, cm.rgt, cb.brandID, COUNT(*)-1 AS level
				FROM carModel AS cm, carModel AS cm2, carBrand AS cb
				WHERE (cm.carBrandID = cb.carBrandID) 
						AND (cm.lft BETWEEN cm2.lft AND cm2.rgt)
						AND (cm.erased = "0")
						AND EXISTS ( 	SELECT * 
										FROM brand AS b
										WHERE cb.brandID = b.brandID
												AND b.erased = "0" 
									) ';
	
	if(isset($p['carBrandID'])){		
		if (is_array($p['carBrandID'])){
			$p['carBrandID'] = $db -> escape($p['carBrandID']);
			$query .= 'AND (cm.carBrandID IN ("'.implode('","',$p['carBrandID']).'") )';
		}else{
			$query .= 'AND (cm.carBrandID = '.$db -> escape($p['carBrandID']).') ';
		}
	}
	
	if(isset($p['carModelID'])){
		$p['carModelID'] = $db -> escape($p['carModelID']);
		if (is_array($p['carModelID'])){
			$query .= 'AND (cm.carModelID IN ("'.implode('","',$p['carModelID']).'") )';
		}else{
			$query .= 'AND (cm.carModelID = '.$p['carModelID'].') ';
		}
	}elseif(isset($p['carModelIDRange'])){
		$p['carModelID'] = $db -> escape($p['carModelID']);
		
		if (is_array($p['carModelID'])){
			$p['carModelID'] = $db -> escape($p['carModelID']);
			$query .= 'AND (cm.carModelID IN ("'.implode('","',$p['carModelID']).'") )';
		}else{
			$query .= '	AND (cm.lft > (SELECT lft FROM carModel WHERE carModelID = "'.$db -> escape($p['carModelID']).'")) 
						AND (cm.rgt > (SELECT rgt FROM carModel WHERE carModelID = "'.$db -> escape($p['carModelID']).'")) ';
		}		
	}
	
	
	if(isset($p['carModelName'])){
		$query .= 'AND (cm.carModelName = "'.$db -> escape($p['carModelName']).'") ';
	}elseif(isset($p['carModelNameL'])){
		$query .= 'AND (cm.carModelName = LOWER("'.$db -> escape($p['carModelNameL']).'") ) ';
	} 
	
	//Left value
	if (isset($p['lft'])){
		$query .= 'AND (cm.lft = "'.$db -> escape($p['lft']).'") ';		
	}
	elseif (isset($p['lftBe'])){
		$query .= 'AND (cm.lft >= "'.$db -> escape($p['lftB']).'") ';		
	}
	elseif (isset($p['lftB'])){
		$query .= 'AND (cm.lft > "'.$db -> escape($p['lftB']).'") ';		
	}
	elseif (isset($p['lftLe'])){
		$query .= 'AND (cm.lft <= "'.$db -> escape($p['lftLe']).'") ';		
	}
	elseif (isset($p['lftL'])){
		$query .= 'AND (cm.lft < "'.$db -> escape($p['lftL']).'") ';		
	}
	
	//Right value
	if (isset($p['rgt'])){
		$query .= 'AND (cm.rgt = "'.$db -> escape($p['rgt']).'") ';		
	}
	elseif (isset($p['rgtBe'])){
		$query .= 'AND (cm.rgt >= "'.$db -> escape($p['rgtBe']).'") ';		
	}
	elseif (isset($p['rgtB'])){
		$query .= 'AND (cm.rgt > "'.$db -> escape($p['rgtB']).'") ';		
	}
	elseif (isset($p['rgtLe'])){
		$query .= 'AND (cm.rgt <= "'.$db -> escape($p['rgtLe']).'") ';		
	}
	elseif (isset($p['rgtL'])){
		$query .= 'AND (cm.rgt < "'.$db -> escape($p['rgtL']).'") ';		
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
