<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select bike categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBikeBrand2Cat($p=null){
	$return = false;
	
	$db = DB::getInstance();
		
	$query = '	SELECT *
				FROM bikeBrand2Cat as cb2c
				';
	
	$where = false;
			
	//bikeBrandID
	if (isset($p['bikeBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if(is_array($p['bikeBrandID'])){
			$query .= ' (cb2c.bikeBrandID IN ("'.$db -> escape(implode('","',$p['bikeBrandID'])).'")) ';
		}else{
			$query .= ' (cb2c.bikeBrandID = '.$db -> escape($p['bikeBrandID']).') ';
		}
	}
	
	//bikeCatID
	if (isset($p['bikeCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if(is_array($p['bikeCatID'])){
			$query .= ' (cb2c.bikeCatID IN ("'.$db -> escape(implode('","',$p['bikeCatID'])).'")) ';
		}else{
			$query .= ' (cb2c.bikeCatID = '.$db -> escape($p['bikeCatID']).') ';
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
