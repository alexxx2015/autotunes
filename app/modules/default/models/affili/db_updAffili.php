<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an affili database record
 *********************************************************************************/
include_once('classes/DB.php');

function db_updAffili($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	UPDATE affili SET  ';
	
	//programID
	if(isset($p[System_Properties::SQL_SET]['programID'])){		
		$query .= 'programID = "'.$db -> escape($p[System_Properties::SQL_SET]['programID']).'", ';
	}
	
	//articleNumber
	if(isset($p[System_Properties::SQL_SET]['articleNumber'])){
		$query .= 'articleNumber = "'.$db -> escape($p[System_Properties::SQL_SET]['articleNumber']).'", ';
	}
	
	//categoryID
	if(isset($p[System_Properties::SQL_SET]['categoryID'])){
		$query .= 'categoryID = "'.$db -> escape($p[System_Properties::SQL_SET]['categoryID']).'", ';
	}
	
	//categoryPath
	if (isset($p[System_Properties::SQL_SET]['categoryPath'])){
		$query .= 'categoryPath = "'.$db -> escape($p[System_Properties::SQL_SET]['categoryPath']).'", ';
	}
	
	//price
	if (isset($p[System_Properties::SQL_SET]['price'])){
		$query .= 'price = "'.$db -> escape($p[System_Properties::SQL_SET]['price']).'", ';
	}
	
	//link
	if (isset($p[System_Properties::SQL_SET]['link'])){
		$query .= 'link = "'.$db -> escape($p[System_Properties::SQL_SET]['link']).'", ';
	}
	
	//articleTitle
	if (isset($p[System_Properties::SQL_SET]['articleTitle'])){
		$query .= 'articleTitle = "'.$db -> escape($p[System_Properties::SQL_SET]['articleTitle']).'", ';
	}
	
	//articleDesc
	if (isset($p[System_Properties::SQL_SET]['articleDesc'])){
		$query .= 'articleDesc = "'.$db -> escape($p[System_Properties::SQL_SET]['articleDesc']).'", ';
	}
	
	//imgURL
	if (isset($p[System_Properties::SQL_SET]['imgURL'])){
		$query .= 'imgURL = "'.$db -> escape($p[System_Properties::SQL_SET]['imgURL']).'", ';
	}
	
	//refData
	if (isset($p[System_Properties::SQL_SET]['refData'])){
		$query .= 'refData = "'.$db -> escape($p[System_Properties::SQL_SET]['refData']).'", ';
	}
	
	//programName
	if (isset($p[System_Properties::SQL_SET]['programName'])){
		$query .= 'programName = "'.$db -> escape($p[System_Properties::SQL_SET]['programName']).'", ';
	}
	
	//timestam
	if (isset($p[System_Properties::SQL_SET]['timestam'])){
		$query .= 'timestam = UNIX_TIMESTAMP(), ';
	}
	
	//new
	if (isset($p[System_Properties::SQL_SET]['new'])){
		$query .= 'new = "'.$db -> escape($p[System_Properties::SQL_SET]['new']).'", ';
	}
	
	//refData
	if (isset($p[System_Properties::SQL_SET]['refData'])){
		$query .= 'refData = "'.$db -> escape($p[System_Properties::SQL_SET]['refData']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['affiliID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (affiliID = "'.$p[System_Properties::SQL_WHERE]['affiliID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['programID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (programID = "'.$p[System_Properties::SQL_WHERE]['programID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['articleNumber'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (articleNumber = "'.$p[System_Properties::SQL_WHERE]['articleNumber'].'") ';
	}
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
