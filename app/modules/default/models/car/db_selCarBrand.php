<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Car brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCarBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT b.brandName, cb.carBrandID 
				FROM carBrand AS cb, brand AS b 
				WHERE (cb.brandID = b.brandID) AND (cb.active = "1")  AND (b.erased = "0") ';
	
	if (isset($p['carBrandID'])){
		$query .= ' AND (cb.carBrandID = '.$db -> escape($p['carBrandID']).') ';
	}
	
	if (isset($p['brandName'])){
		$query .= ' AND (b.brandName = "'.$db -> escape($p['brandName']).'") ';
	}elseif (isset($p['brandNameL'])){
		$query .= ' AND (LOWER(b.brandName) = "'.$db -> escape($p['brandNameL']).'" ) ';
	}
	
	//ORDER BY
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= 'ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $orderby['col'];
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC ';
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
