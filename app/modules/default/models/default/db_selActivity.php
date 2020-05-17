<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selActivity($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT * 
				FROM activity AS a1 ';
	
	$where = false;
	
	//activityID
	if (isset($p['activityID'])){
		if ($where == false){
			$query .= 'WHERE ';
			$where = true;
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['activityID'])){
			$query .= '(a1.activityID IN ('.$db -> escape(implode(',', $p['activityID'])).')) ';
		}
		else{
			$query .= '(a1.activityID = '.$db -> escape($p['activityID']).') ';
		}
	}
	
	//activityName
	if(isset($p['activityName'])){
		if ($where == false){
			$query .= 'WHERE ';
		}
		else{
			$query .= 'AND ';
		}
		if(is_array($p['activityName'])){
			$query .= '(a1.activityName IN ("'.implode('","', $db -> escape($p['activityName'])).'")) ';
		}
		else{
			$query .= '(a1.activityName = "'.$db -> escape($p['activityName']).'") ';
		}
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
