<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function delete a bookmark vehicle
 *********************************************************************************/
include_once('classes/DB.php');

function db_delBookmark($p=null){
	$return = false;
	if (is_array($p)){
		$db = DB::getInstance();
		
		$query = '	DELETE FROM bookmark ' ;
		
		$where = false;
	
		//bookmarkID
		if (isset($p['bookmarkID'])){
			if ($where == false){
				$query .= ' WHERE ';
				$where = true; 
			}else{
				$query .= ' AND ';
			}
			$query .= ' (bookmarkID = '.$db -> escape($p['bookmarkID']).') ';
		}
	
		//vehicleType
		if (isset($p['vehicleType'])){
			if ($where == false){
				$query .= ' WHERE ';
				$where = true; 
			}else{
				$query .= ' AND ';
			}
			$query .= ' (vehicleType = "'.$db -> escape($p['vehicleType']).'") ';
		}
		
		//vehicleID
		if (isset($p['vehicleID'])){
			if ($where == false){
				$query .= ' WHERE ';
				$where = true; 
			}else{
				$query .= ' AND ';
			}
			$query .= ' (vehicleID = '.$db -> escape($p['vehicleID']).') ';
		}
		
		//userID
		if (isset($p['userID'])){
			if ($where == false){
				$query .= ' WHERE ';
				$where = true; 
			}else{
				$query .= ' AND ';
			}
			$query .= ' (userID = '.$db -> escape($p['userID']).') ';
		}
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
