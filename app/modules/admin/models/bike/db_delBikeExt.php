<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select bike details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delBikeExt($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM bikeExt ';
	
	$where = false;
	//Add bikeExtID
	if (isset($p['bikeExtID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		if (is_array($p['bikeExtID'])){
			$query .= ' ( bikeExtID IN ("'.implode('","', $p['bikeExtID']).'")) ';	
		}else{
			$query .= ' ( bikeExtID = "'.$db -> escape($p['bikeExtID']).'" ) ';
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
