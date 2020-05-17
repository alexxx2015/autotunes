<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20111108
 * Desc:		Insert a relationship between two advertisement
 *********************************************************************************/
include_once('classes/DB.php');
include_once('classes/System_Properties.php');

function db_insCronjTask($p){
	$return = false;
	
	$db = DB::getInstance();
	
	if (isset($p['cronjID'])){	
		$query1 = ' INSERT INTO cronjTask (cronjID, ';
		$query2 = ' VALUES ("'.$db -> escape($p['cronjID']).'", ';
		
		//cronjTaskStartTime
		if (isset($p['cronjTaskStartTime'])){
			$query1 .= 'cronjTaskStartTime, ';
			$query2 .= '"'.$db -> escape($p['cronjTaskStartTime']).'", ';
		}else{
			$query1 .= 'cronjTaskStartTime, ';
			$query2 .= 'UNIX_TIMESTAMP(), ';
		}
		
		//cronjTaskStopTime
		if (isset($p['cronjTaskStopTime'])){
			$query1 .= 'cronjTaskStopTime, ';
			$query2 .= '"'.$db -> escape($p['cronjTaskStopTime']).'", ';
		}else{
			$query1 .= 'cronjTaskStopTime, ';
			$query2 .= 'UNIX_TIMESTAMP(), ';
		}
		
		//cronjTaskResult
		if (isset($p['cronjTaskResult'])){
			$query1 .= 'cronjTaskResult, ';
			$query2 .= '"'.$db -> escape($p['cronjTaskResult']).'", ';
		}
		
		//cronjTaskFinished
		if (isset($p['cronjTaskFinished'])){
			$query1 .= 'cronjTaskFinished, ';
			$query2 .= '"'.$db -> escape($p['cronjTaskFinished']).'", ';
		}
		
	
		$query = substr($query1,0,-2).') '.substr($query2,0,-2).')';
		
		if (isset($p['print']) && ($p['print'] == true)){
			echo $query;
		}
		$return = $db->execQuery(array('q'=>$query));
		return $return;
	}
}
?>
