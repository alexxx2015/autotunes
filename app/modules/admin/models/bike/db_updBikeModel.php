<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an bike advertisement in table BIKE
 *********************************************************************************/
include_once('classes/DB.php');

function db_updBikeModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE bikeModel SET  ';
	
	//bikeModelName
	if(isset($p[System_Properties::SQL_SET]['bikeModelName'])){		
		$query .= 'bikeModelName = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeModelName']).'", ';
	}
	
	//bikeBrandID
	if(isset($p[System_Properties::SQL_SET]['bikeBrandD'])){		
		$query .= 'bikeBrandID = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeBrandID']).'", ';
	}
	
	//lft
	if(isset($p[System_Properties::SQL_SET]['lft'])){		
		$query .= 'lft = "'.$db -> escape($p[System_Properties::SQL_SET]['lft']).'", ';
	}
	//incLft
	else if (isset($p[System_Properties::SQL_SET]['incLft'])){
		$query .= 'lft = lft+'.$db -> escape($p[System_Properties::SQL_SET]['incLft']).', ';
	}
	//decLft
	else if (isset($p[System_Properties::SQL_SET]['decLft'])){
		$query .= 'lft = lft-'.$db -> escape($p[System_Properties::SQL_SET]['decLft']).', ';
	}
	
	//rgt
	if(isset($p[System_Properties::SQL_SET]['rgt'])){		
		$query .= 'rgt = "'.$db -> escape($p[System_Properties::SQL_SET]['rgt']).'", ';
	}
	//incRgt
	else if (isset($p[System_Properties::SQL_SET]['incRgt'])){
		$query .= 'rgt = rgt + '.$db -> escape($p[System_Properties::SQL_SET]['incRgt']).', ';
	}
	//decRgt
	else if (isset($p[System_Properties::SQL_SET]['decRgt'])){
		$query .= 'rgt = rgt-'.$db -> escape($p[System_Properties::SQL_SET]['decRgt']).', ';
	}	
	
	//updated
	if (isset($p[System_Properties::SQL_SET]['erased'])){
		$query .= ' erased = "'.$db -> escape($p[System_Properties::SQL_SET]['erased']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	//WHERE
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['bikeBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (bikeBrandID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['bikeBrandID']).'") ';
	}		

	//lft bigger
	if (isset($p[System_Properties::SQL_WHERE]['lftB'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (lft > "'.$db -> escape($p[System_Properties::SQL_WHERE]['lftB']).'") ';
	}
	elseif (isset($p[System_Properties::SQL_WHERE]['lftBEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (lft >= "'.$db -> escape($p[System_Properties::SQL_WHERE]['lftBEq']).'") ';
	}	 
	//lft lower
	if (isset($p[System_Properties::SQL_WHERE]['lftLEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (lft <= "'.$db -> escape($p[System_Properties::SQL_WHERE]['lftLEq']).'") ';
	}	
	elseif (isset($p[System_Properties::SQL_WHERE]['lftL'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (lft < "'.$db -> escape($p[System_Properties::SQL_WHERE]['lftL']).'") ';
	}	
	
	//rgt bigger
	if (isset($p[System_Properties::SQL_WHERE]['rgtB'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (rgt > "'.$db -> escape($p[System_Properties::SQL_WHERE]['rgtB']).'") ';
	}	
	elseif (isset($p[System_Properties::SQL_WHERE]['rgtBEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (rgt >= "'.$db -> escape($p[System_Properties::SQL_WHERE]['rgtBEq']).'") ';
	}	
	//rgt lower
	if (isset($p[System_Properties::SQL_WHERE]['rgtLEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (rgt <= "'.$db -> escape($p[System_Properties::SQL_WHERE]['rgtLEq']).'") ';
	}	
	elseif (isset($p[System_Properties::SQL_WHERE]['rgtL'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (rgt < "'.$db -> escape($p[System_Properties::SQL_WHERE]['rgtL']).'") ';
	}	
	
	
	if (isset($p[System_Properties::SQL_WHERE]['notBikeModelID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' NOT(bikeModelID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['notBikeModelID']).'") ';
	}
	
	
	if (isset($p[System_Properties::SQL_WHERE]['bikeModelID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (bikeModelID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['bikeModelID']).'") ';
	}
	
	
	if (isset($p[System_Properties::SQL_WHERE]['updated'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (updated = "'.$db -> escape($p[System_Properties::SQL_WHERE]['updated']).'") ';
	}elseif (isset($p[System_Properties::SQL_WHERE]['notUpdated'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' NOT(updated = "'.$db -> escape($p[System_Properties::SQL_WHERE]['notUpdated']).'") ';
	}
	
	if (isset($p['p'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
