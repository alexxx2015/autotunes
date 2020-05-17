<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selDatex($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT * 
				FROM datex as d1 ';
	
	$where = false;
	
	//datexID
	if (isset($p['datexID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['datexID'])){
			$query .= ' (d1.datexID IN ('.$db -> escape(implode(',', $p['datexID'])).')) ';
		}
		else{
			$query .= ' (d1.datexID = '.$db -> escape($p['datexID']).') ';
		}
	}
	
	//userID
	if (isset($p['userID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['userID'])){
			$query .= ' (d1.userID IN ('.$db -> escape(implode(',', $p['userID'])).')) ';
		}
		else{
			$query .= ' (d1.userID = '.$db -> escape($p['userID']).') ';
		}
	}
	
	//vType
	if (isset($p['vType'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['vType'])){
			$query .= ' (d1.vType IN ("'.$db -> escape(implode('","', $p['vType'])).'")) ';
		}
		else{
			$query .= ' (d1.vType = "'.$db -> escape($p['vType']).'") ';
		}
	}
	
	//datexFormat
	if (isset($p['datexFormat'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['datexFormat'])){
			$query .= ' (d1.datexFormat IN ("'.$db -> escape(implode('","', $p['datexFormat'])).'")) ';
		}
		else{
			$query .= ' (d1.datexFormat = '.$db -> escape($p['datexFormat']).') ';
		}
	}
	
	//datexPic
	if (isset($p['datexPic'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if ($p['datexPic'] == 1){
			$query .= ' (d1.datexPic = "1") ';
		}else{
			$query .= ' (d1.datexPic = "0") ';
		}		
	}
	
	//ORDER BY
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= 'ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $orderby['col'];
			
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC';
			}
			$query .= ',';
		}		
		$query = substr($query, 0, -1);
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
