<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an car advertisement in table CAR
 *********************************************************************************/
include_once('classes/DB.php');

function db_updBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE brand SET  ';
	
	//brandName
	if(isset($p[System_Properties::SQL_SET]['brandName'])){		
		$query .= 'brandName = "'.$db -> escape($p[System_Properties::SQL_SET]['brandName']).'", ';
	}
	
	//erased
	if(isset($p[System_Properties::SQL_SET]['erased'])){		
		$query .= 'erased = "'.$db -> escape($p[System_Properties::SQL_SET]['erased']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	if (isset($p[System_Properties::SQL_WHERE]['brandID'])){
		$query .= ' WHERE brandID = "'.$p[System_Properties::SQL_WHERE]['brandID'].'"';
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
