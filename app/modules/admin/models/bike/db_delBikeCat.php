<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select bike details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delBikeCat($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM bikeCat ';
	
	$where = false;
	//Add bikeCatID
	if (isset($p['bikeCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		if (is_array($p['bikeCatID'])){
			$query .= ' ( bikeCatID IN ("'.implode('","', $p['bikeCatID']).'")) ';	
		}else{
			$query .= ' ( bikeCatID = "'.$db -> escape($p['bikeCatID']).'" ) ';
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
		$query .= ' ( bikeID = "'.$db -> escape($p['bikeID']).'" ) ';
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
