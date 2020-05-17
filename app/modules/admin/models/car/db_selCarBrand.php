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
	
	//erased
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
	
	$query = '	SELECT b.brandName, b.brandID, cb.carBrandID, cb.active
				FROM carBrand AS cb, brand AS b 
				WHERE (cb.brandID = b.brandID) AND (b.erased = "'.$db -> escape($p['erased']).'") ';
	
	
	//carBrandID
	if (isset($p['carBrandID'])){
		$query .= ' AND (cb.carBrandID = '.$db -> escape($p['carBrandID']).')';
	}
	
	//brandID
	if (isset($p['brandID'])){
		$query .= ' AND (b.brandID = '.$db -> escape($p['brandID']).')';
	}
	
	if (isset($p['brandName'])){
		$query .= ' AND (b.brandName = "'.$db -> escape($p['brandName']).'") ';
	}elseif (isset($p['brandNameL'])){
		$query .= ' AND (LOWER(b.brandName) = "'.$db -> escape($p['brandNameL']).'" ) ';
	}
	
	//active
	if (isset($p['active'])){
		$query .= ' AND (cb.active = '.$db -> escape($p['active']).')';
	}
	
	//ORDER BY
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= 'ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $orderby['col'];
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
