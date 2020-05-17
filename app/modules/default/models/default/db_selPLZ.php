<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function select all picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_selPLZ($p=null){
	$return = false;
	$db = DB::getInstance();
	
	if (!isset($p['ok'])){
		$p['ok'] = 1;
	}
	
	$query = '	SELECT *
				FROM geonameplz as p 
				WHERE country_code IN ("DE","AT") ';
	
	$where = true;

	//postal_code
	if(isset($p['postal_code'])){
		if ($where == false){
			$query .= ' WHERE ' ;
			$where = true;	
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['postal_code'])){
			$query .= ' (p.postal_code IN ( "'.implode('","',$db -> escape($p['postal_code'])).'" ) )';
		}else{
			$query .= ' (p.postal_code = "'.$db -> escape($p['postal_code']).'") ';
		}
	}
	
	//place_name
	if(isset($p['place_name'])){
		if ($where == false){
			$query .= ' WHERE ' ;
			$where = true;	
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['place_name'])){
			$query .= ' (p.place_name IN ( "'.implode('","',$db -> escape($p['place_name'])).'" ) )';
		}else{
			$query .= ' (p.place_name = "'.$db -> escape($p['place_name']).'") ';
		}
	}
	
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
