<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select car details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delCarAds($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM car ';
	
	$where = false;	
	
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
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
