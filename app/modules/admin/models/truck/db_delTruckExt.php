<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select truck details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delTruckExt($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM truckExt ';
	
	$where = false;
	//Add truckExtID
	if (isset($p['truckExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		if (is_array($p['truckExtID'])){
			$query .= ' ( truckExtID IN ("'.implode('","', $p['truckExtID']).'")) ';	
		}else{
			$query .= ' ( truckExtID = "'.$db -> escape($p['truckExtID']).'" ) ';
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
	

	//Add vextID
	if (isset($p['vextID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( vextID = "'.$db -> escape($p['vextID']).'" ) ';
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
