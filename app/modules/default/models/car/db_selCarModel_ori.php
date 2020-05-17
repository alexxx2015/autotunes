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
	
	$query = '	SELECT cm.carModelID, cm.carModelName, cm.carBrandID, cb.brandID
				FROM carModel AS cm, carBrand AS cb
				WHERE (cm.carBrandID = cb.carBrandID) ';
	
	if(isset($p['carBrandID'])){			
		if (is_array($p['carBrandID'])){
			$p['carBrandID'] = $db -> escape($p['carBrandID']);
			$query .= 'AND (cm.carBrandID IN ("'.implode('","',$p['carBrandID']).'") )';
		}else{
			$query .= 'AND (cm.carBrandID = '.$db -> escape($p['carBrandID']).') ';
		}
	}
	
	if(isset($p['carModelID'])){
		$query .= 'AND (cm.carModelID = '.$db -> escape($p['carModelID']).') ';
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
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
