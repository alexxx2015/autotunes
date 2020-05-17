<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select car details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delCarExt($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM carExt ';
	
	$where = false;
	//Add carExtID
	if (isset($p['carExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		if (is_array($p['carExtID'])){
			$query .= ' ( carExtID IN ("'.implode('","', $p['carExtID']).'")) ';	
		}else{
			$query .= ' ( carExtID = "'.$db -> escape($p['carExtID']).'" ) ';
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
