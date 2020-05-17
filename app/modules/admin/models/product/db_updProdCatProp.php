<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function update the product categories
 *********************************************************************************/
include_once('classes/DB.php');

function db_updProdCatProp($p){	
	$return = false;
	$db = DB::getInstance();

	
	if (!isset($p['active'])){
		$p['active'] = 0;
	}
	
	$query = '	UPDATE prodCatProp
				SET ';
	
	//active
	if(isset($p[System_Properties::SQL_SET]['active'])){		
		$query .= 'active = "'.$db -> escape($p[System_Properties::SQL_SET]['active']).'", ';
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
	
	//prodCatPropAbrv
	if(isset($p[System_Properties::SQL_SET]['prodCatPropAbrv'])){		
		$query .= 'prodCatPropAbrv = "'.$db -> escape($p[System_Properties::SQL_SET]['prodCatPropAbrv']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	//WHERE
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['prodCatPropID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p[System_Properties::SQL_WHERE]['prodCatPropID'])){
			$query .= ' (prodCatPropID IN ( "'.implode('","', $db -> escape($p[System_Properties::SQL_WHERE]['prodCatPropID'])).'") )';			
		}else{
			$query .= ' (prodCatPropID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['prodCatPropID']).'") ';
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
