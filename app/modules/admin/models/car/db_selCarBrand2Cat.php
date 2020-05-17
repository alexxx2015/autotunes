<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select car categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCarBrand2Cat($p=null){
	$return = false;
	
	$db = DB::getInstance();
		
	$query = '	SELECT *
				FROM carBrand2Cat as cb2c
				';
	
	$where = false;
			
	//carBrandID
	if (isset($p['carBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if(is_array($p['carBrandID'])){
			$query .= ' (cb2c.carBrandID IN ("'.$db -> escape(implode('","',$p['carBrandID'])).'")) ';
		}else{
			$query .= ' (cb2c.carBrandID = '.$db -> escape($p['carBrandID']).') ';
		}
	}
	
	//carCatID
	if (isset($p['carCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if(is_array($p['carCatID'])){
			$query .= ' (cb2c.carCatID IN ("'.$db -> escape(implode('","',$p['carCatID'])).'")) ';
		}else{
			$query .= ' (cb2c.carCatID = '.$db -> escape($p['carCatID']).') ';
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
