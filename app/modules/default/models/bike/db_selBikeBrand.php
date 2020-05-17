<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all bike brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBikeBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT b.brandName, b.brandID, bb.bikeBrandID
				FROM bikeBrand AS bb, brand AS b 
				WHERE (bb.brandID = b.brandID) AND (b.erased = "0") AND (bb.active = "1") ';
	

	//bikeBrandID
	if (isset($p['bikeBrandID'])){
		$query .= ' AND (bb.bikeBrandID = '.$db -> escape($p['bikeBrandID']).') ';
	}
	
	//brandID
	if (isset($p['brandID'])){
		$query .= ' AND (b.brandID = '.$db -> escape($p['brandID']).') ';
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
