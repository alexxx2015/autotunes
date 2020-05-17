<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function update a single cronjob task
 *********************************************************************************/
include_once('classes/DB.php');

function db_updCronjTask($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	UPDATE cronjTask ';
	
	$set = false;
	if(isset($p[System_Properties::SQL_SET]['cronjTaskID'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'cronjTaskID = "'.$db -> escape($p[System_Properties::SQL_SET]['cronjTaskID']).'", ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['cronjID'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'cronjID = "'.$db -> escape($p[System_Properties::SQL_SET]['cronjID']).'", ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['cronjTaskStartTime'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'cronjTaskStartTime = "'.$db -> escape($p[System_Properties::SQL_SET]['cronjTaskStartTime']).'", ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['cronjTaskStopTime'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'cronjTaskStopTime = "'.$db -> escape($p[System_Properties::SQL_SET]['cronjTaskStopTime']).'", ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['cronjTaskResult'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'cronjTaskResult = "'.$db -> escape($p[System_Properties::SQL_SET]['cronjTaskResult']).'", ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['cronjTaskFinished'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'cronjTaskFinished = "'.$db -> escape($p[System_Properties::SQL_SET]['cronjTaskFinished']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	if (isset($p[System_Properties::SQL_WHERE]['cronjTaskID'])){
		$query .= ' WHERE ';
		if (is_array($p[System_Properties::SQL_WHERE]['cronjTaskID'])){
			$query .= '( cronjTaskID IN ( "'.implode('","',$db -> escape($p[System_Properties::SQL_WHERE]['cronjTaskID'])).'" ) )';
			
		}else{
			$query .= '( cronjTaskID = "'.$db -> escape($p[System_Properties::SQL_WHERE]['cronjTaskID']).'" )';
		}
		
		if (isset($p['print']) && ($p['print'] == true)){
			echo $query;
		}
		$return = $db->execQuery(array('q'=>$query));	
	}

	return $return;
}
?>
