<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select car details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delCarCat($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM carCat ';
	
	$where = false;
	//Add carCatID
	if (isset($p['carCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		if (is_array($p['carCatID'])){
			$query .= ' ( carCatID IN ("'.implode('","', $p['carCatID']).'")) ';	
		}else{
			$query .= ' ( carCatID = "'.$db -> escape($p['carCatID']).'" ) ';
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
		$query .= ' ( carID = "'.$db -> escape($p['carID']).'" ) ';
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
