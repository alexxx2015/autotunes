<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update the vehicle cat table
 *********************************************************************************/
include_once('classes/DB.php');

function db_updVCat($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE vcat SET  ';
	
	//erased
	if(isset($p[System_Properties::SQL_SET]['erased'])){		
		$query .= 'erased = "'.$db -> escape($p[System_Properties::SQL_SET]['erased']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	if (isset($p[System_Properties::SQL_WHERE]['vcatID'])){
		$query .= ' WHERE vcatID = "'.$p[System_Properties::SQL_WHERE]['vcatID'].'"';
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
