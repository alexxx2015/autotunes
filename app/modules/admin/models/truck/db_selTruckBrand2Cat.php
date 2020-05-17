<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select truck categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTruckBrand2Cat($p=null){
	$return = false;
	
	$db = DB::getInstance();
		
	$query = '	SELECT *
				FROM truckBrand2Cat as cb2c
				';
	
	$where = false;
			
	//truckBrandID
	if (isset($p['truckBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if(is_array($p['truckBrandID'])){
			$query .= ' (cb2c.truckBrandID IN ("'.$db -> escape(implode('","',$p['truckBrandID'])).'")) ';
		}else{
			$query .= ' (cb2c.truckBrandID = '.$db -> escape($p['truckBrandID']).') ';
		}
	}
	
	//truckCatID
	if (isset($p['truckCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if(is_array($p['truckCatID'])){
			$query .= ' (cb2c.truckCatID IN ("'.$db -> escape(implode('","',$p['truckCatID'])).'")) ';
		}else{
			$query .= ' (cb2c.truckCatID = '.$db -> escape($p['truckCatID']).') ';
		}
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
