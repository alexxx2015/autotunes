<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function select all picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBookmark($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT * 
				FROM bookmark AS b1 ';
	
	$where = false;
	
	//bookmarkID
	if (isset($p['bookmarkID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['bookmarkID'])){
			$query .= '(b1.bookmarkID IN ('.$db -> escape(implode(',', $p['bookmarkID'])).')) ';
		}
		else{
			$query .= '(b1.bookmarkID = '.$db -> escape($p['bookmarkID']).') ';
		}
	}
	
	//vehicleType
	if(isset($p['vehicleType'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['vehicleType'])){
			$query .= '(b1.vehicleType IN ("'.implode('","', $db -> escape($p['vehicleType'])).'")) ';
		}
		else{
			$query .= '(b1.vehicleType = "'.$db -> escape($p['vehicleType']).'") ';
		}
	}
	
	//vehicleID
	if(isset($p['vehicleID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['vehicleID'])){
			$query .= '(b1.vehicleID IN ("'.implode('","', $db -> escape($p['vehicleID'])).'")) ';
		}
		else{
			$query .= '(b1.vehicleID = "'.$db -> escape($p['vehicleID']).'") ';
		}
	}
	
	//userID
	if(isset($p['userID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['userID'])){
			$query .= '(b1.userID IN ("'.implode('","', $db -> escape($p['userID'])).'")) ';
		}
		else{
			$query .= '(b1.userID = "'.$db -> escape($p['userID']).'") ';
		}
	}
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
