<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select truck details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delTruckAds($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM truck ';
	
	$where = false;	
	
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
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
