<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110623
 * Desc:		This function select all AS24 data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selAS24($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	if(!isset($p['erased'])){
		$p['erased'] = '0';
	}
	
	$query = '	SELECT * 
				FROM as24 AS a1 
				WHERE (a1.erased = '.$p['erased'].') ';
	
	$where = true;
	//ADD as24ID
	if (isset($p['as24ID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( a1.as24ID = "'.$db -> escape($p['as24ID']).'" ) ';
	}
	
	//ADD A
	if (isset($p['A'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( a1.A = "'.$db -> escape($p['A']).'" ) ';
	}
		
	//ADD B
	if (isset($p['B'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( a1.B = "'.$db -> escape($p['B']).'" ) ';
	}
		
	//ADD userID
	if (isset($p['userID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( a1.userID = "'.$db -> escape($p['userID']).'" ) ';
	}
	
	//as24New
	if (isset($p['as24New'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( a1.as24New = "'.$db -> escape($p['as24New']).'" ) ';
	}
	
	//vType
	if (isset($p['vType'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( a1.vType = "'.$db -> escape($p['vType']).'" ) ';
	}	
	
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
