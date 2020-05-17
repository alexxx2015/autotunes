<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select truck details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delTruck2Ext($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM truck2Ext ';
	
	$where = false;	

	//Add truck2ExtID
	if (isset($p['truck2ExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['truck2ExtID'])){
			$query .= ' ( truck2ExtID IN ( "'.$db -> escape(implode('","',$p['truck2ExtID'])).'" )) ';
		}else{
			$query .= ' ( truck2ExtID = "'.$db -> escape($p['truck2ExtID']).'" ) ';
		}
	}
	
	//Add truckID
	if (isset($p['truckID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['truckID'])){
			$query .= ' ( truckID IN ( "'.$db -> escape(implode('","',$p['truckID'])).'" )) ';
			
		}else{
			$query .= ' ( truckID = "'.$db -> escape($p['truckID']).'" ) ';
		}
	}
	
	//Add truckExtID
	if (isset($p['truckExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['truckExtID'])){
			$query .= ' ( truckExtID IN ( "'.$db -> escape(implode('","', $p['truckExtID'])).'" )) ';
		}else{
			$query .= ' ( truckExtID = "'.$db -> escape($p['truckExtID']).'" ) ';
		}
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
