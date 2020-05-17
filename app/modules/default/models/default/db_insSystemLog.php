<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		2011-06-10
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');
include_once('Zend/Json.php');

function db_insSystemLog($p=null){
	$return = false;
	$db = DB::getInstance();
	
	if (isset($p['activityID']) && isset($p['activityRes']) && isset($p['visitorID'])){
		$query = 'INSERT INTO systemLog ( activityID, activityRes, visitorID';
		
		$query2 = ' VALUES ( "'.$db -> escape($p['activityID']).'"
							, "'.$db -> escape($p['activityRes']).'"
							, "'.$db -> escape($p['visitorID']).'"
							';
		
		//userID
		if (isset($p['userID']) && is_numeric($p['userID'])){
			$query .= ', userID ';
			$query2 .= ', "'.$db -> escape($p['userID']).'"';
		}
		
		//systemLogData
		if (isset($p['systemLogData'])){
			$query .= ', systemLogData ';
			$query2 .= ', "'.$db -> escape(Zend_Json::encode($p['systemLogData'])).'"';
		}
		
		$query = $query.') '.$query2.')';
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
