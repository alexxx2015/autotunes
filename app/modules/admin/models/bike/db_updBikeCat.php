<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function insert all extras of a bike
 *********************************************************************************/
include_once('classes/DB.php');

function db_updBikeCat($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	UPDATE bikeCat
				SET ';
	
	//active
	if(isset($p[System_Properties::SQL_SET]['active'])){		
		$query .= 'active = "'.$db -> escape($p[System_Properties::SQL_SET]['active']).'", ';
	}
	
	//vcatID
	if(isset($p[System_Properties::SQL_SET]['vcatID'])){		
		$query .= 'vcatID = "'.$db -> escape($p[System_Properties::SQL_SET]['vcatID']).'", ';
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
	
	$query = substr($query, 0, -2);
	
	//WHERE
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['bikeCatID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['bikeCatID'])){
			$query .= ' (bikeCatID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['bikeCatID'])).'") )';			
		}else{
			$query .= ' (bikeCatID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['bikeCatID']).'") ';
		}
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
	
	$db -> execQuery(array('q'=>$query, 'connect' => true));
	
	return $return;
}
?>
