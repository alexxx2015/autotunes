<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select car details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delCar2Ext($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM car2Ext ';
	
	$where = false;	

	//Add car2ExtID
	if (isset($p['car2ExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['car2ExtID'])){
			$query .= ' ( car2ExtID IN ( "'.$db -> escape(implode('","',$p['car2ExtID'])).'" )) ';
		}else{
			$query .= ' ( car2ExtID = "'.$db -> escape($p['car2ExtID']).'" ) ';
		}
	}
	
	//Add carID
	if (isset($p['carID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['carID'])){
			$query .= ' ( carID IN ( "'.$db -> escape(implode('","',$p['carID'])).'" )) ';
			
		}else{
			$query .= ' ( carID = "'.$db -> escape($p['carID']).'" ) ';
		}
	}
	
	//Add carExtID
	if (isset($p['carExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['carExtID'])){
			$query .= ' ( carExtID IN ( "'.$db -> escape(implode('","', $p['carExtID'])).'" )) ';
		}else{
			$query .= ' ( carExtID = "'.$db -> escape($p['carExtID']).'" ) ';
		}
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
