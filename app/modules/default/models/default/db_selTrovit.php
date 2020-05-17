<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110623
 * Desc:		This function select all Trovit data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTrovit($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT * 
				FROM trovit AS t1 ';
	
	$where = false;
	//ADD trovitID
	if (isset($p['trovitID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( t1.trovitID = "'.$db -> escape($p['trovitID']).'" ) ';
	}
	
	//ADD id
	if (isset($p['id'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( t1.id = "'.$db -> escape($p['id']).'" ) ';
	}
		
	//ADD userID
	if (isset($p['userID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( t1.userID = "'.$db -> escape($p['userID']).'" ) ';
	}
	
	//trovitNew
	if (isset($p['trovitNew'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( t1.trovitNew = "'.$db -> escape($p['trovitNew']).'" ) ';
	}
	
	//vType
	if (isset($p['vType'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( t1.vType = "'.$db -> escape($p['vType']).'" ) ';
	}	
	
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
