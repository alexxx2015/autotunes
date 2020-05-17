<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select bike details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delBike2Ext($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM bike2Ext ';
	
	$where = false;	

	//Add bike2ExtID
	if (isset($p['bike2ExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['bike2ExtID'])){
			$query .= ' ( bike2ExtID IN ( "'.$db -> escape(implode('","',$p['bike2ExtID'])).'" )) ';
		}else{
			$query .= ' ( bike2ExtID = "'.$db -> escape($p['bike2ExtID']).'" ) ';
		}
	}
	
	//Add bikeID
	if (isset($p['bikeID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['bikeID'])){
			$query .= ' ( bikeID IN ( "'.$db -> escape(implode('","',$p['bikeID'])).'" )) ';
			
		}else{
			$query .= ' ( bikeID = "'.$db -> escape($p['bikeID']).'" ) ';
		}
	}
	
	//Add bikeExtID
	if (isset($p['bikeExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['bikeExtID'])){
			$query .= ' ( bikeExtID IN ( "'.$db -> escape(implode('","', $p['bikeExtID'])).'" )) ';
		}else{
			$query .= ' ( bikeExtID = "'.$db -> escape($p['bikeExtID']).'" ) ';
		}
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
