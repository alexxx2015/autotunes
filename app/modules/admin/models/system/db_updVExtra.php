<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update the vehicle extra table
 *********************************************************************************/
include_once('classes/DB.php');

function db_updVExtra($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE vext SET  ';
	
	//erased
	if(isset($p[System_Properties::SQL_SET]['erased'])){		
		$query .= 'erased = "'.$db -> escape($p[System_Properties::SQL_SET]['erased']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	if (isset($p[System_Properties::SQL_WHERE]['vextID'])){
		$query .= ' WHERE vextID = "'.$p[System_Properties::SQL_WHERE]['vextID'].'"';
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
