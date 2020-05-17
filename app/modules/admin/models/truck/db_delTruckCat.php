<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select truck details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delTruckCat($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM truckCat ';
	
	$where = false;
	//Add truckCatID
	if (isset($p['truckCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		if (is_array($p['truckCatID'])){
			$query .= ' ( truckCatID IN ("'.implode('","', $p['truckCatID']).'")) ';	
		}else{
			$query .= ' ( truckCatID = "'.$db -> escape($p['truckCatID']).'" ) ';
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
		$query .= ' ( truckID = "'.$db -> escape($p['truckID']).'" ) ';
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
