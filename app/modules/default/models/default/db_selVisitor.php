<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');

function db_selVisitor($p=null){
	$return = false;
	if (is_array($p)){
		$db = DB::getInstance();
		
		$query = '	SELECT *
					FROM visitor ';

		$where = false;
	
		//visitorID
		if (isset($p['visitorID'])){
			if ($where == false){
				$query .= ' WHERE ';
			}else{
				$query .= ' AND ';
			}
			$query .= ' (visitorID = "'.$db -> escape($p['visitorID']).'") ';
		}
		
		//ip
		if (isset($p['ip'])){
			if ($where == false){
				$query .= ' WHERE ';
			}else{
				$query .= ' AND ';
			}
			$query .= ' (ip = "'.$db -> escape($p['ip']).'") ';
		}		
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
