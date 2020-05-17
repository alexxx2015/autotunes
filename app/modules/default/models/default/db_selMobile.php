<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110623
 * Desc:		This function select all mobile.de data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selMobile($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	if(!isset($p['erased'])){
		$p['erased'] = '0';
	}
	
	$query = '	SELECT * 
				FROM mobile AS m1
				WHERE (m1.erased = "'.$p['erased'].'") ';
	
	$where = true;
	//ADD mobileID
	if (isset($p['mobileID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( m1.mobileID = "'.$db -> escape($p['mobileID']).'" ) ';
	}
	
	//ADD A
	if (isset($p['A'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( m1.A = "'.$db -> escape($p['A']).'" ) ';
	}
		
	//ADD B
	if (isset($p['B'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( m1.B = "'.$db -> escape($p['B']).'" ) ';
	}
		
	//ADD userID
	if (isset($p['userID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( m1.userID = "'.$db -> escape($p['userID']).'" ) ';
	}
	
	//mobileNew
	if (isset($p['mobileNew'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( m1.mobileNew = "'.$db -> escape($p['mobileNew']).'" ) ';
	}
	
	//vType
	if (isset($p['vType'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( m1.vType = "'.$db -> escape($p['vType']).'" ) ';
	}
	
	if(isset($p['limit']) && is_array($p['limit'])
		&& isset($p['limit']['start']) && isset($p['limit']['num'])){
		$query .= ' LIMIT '.$p['limit']['start'].', '.$p['limit']['num'];
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
