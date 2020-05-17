<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110623
 * Desc:		This function select all AS24 data
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCronjTask($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT * 
				FROM cronjTask as c ';
	
	$where = false;
	//ADD cronjTaskID
	if (isset($p['cronjTaskID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskID = "'.$db -> escape($p['cronjTaskID']).'" ) ';
	}
	
	//ADD cronjID
	if (isset($p['cronjID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjID = "'.$db -> escape($p['cronjID']).'" ) ';
	}

	//ADD cronjTaskStartTime
	if (isset($p['cronjTaskStartTime'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskStartTime = "'.$db -> escape($p['cronjTaskStartTime']).'" ) ';
	}
	//ADD cronjTaskStartTimeLEq
	elseif (isset($p['cronjTaskStartTimeLEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskStartTime <= "'.$db -> escape($p['cronjTaskStartTimeLEq']).'" ) ';
	}	
	//ADD cronjTaskStartTimeBEq
	elseif (isset($p['cronjTaskStartTimeBEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskStartTime >= "'.$db -> escape($p['cronjTaskStartTimeBEq']).'" ) ';
	}	

	//ADD cronjTaskStopTime
	if (isset($p['cronjTaskStopTime'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskStopTime = "'.$db -> escape($p['cronjTaskStopTime']).'" ) ';
	}
	//ADD cronjTaskStopTimeLEq
	elseif (isset($p['cronjTaskStopTimeLEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskStopTime <= "'.$db -> escape($p['cronjTaskStopTimeLEq']).'" ) ';
	}	
	//ADD cronjTaskStopTimeBEq
	elseif (isset($p['cronjTaskStopTimeBEq'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskStopTime >= "'.$db -> escape($p['cronjTaskStopTimeBEq']).'" ) ';
	}	
	
	//Add cronjTaskFinished
	if (isset($p['cronjTaskFinished'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' ( c.cronjTaskFinished >= "'.$db -> escape($p['cronjTaskFinished']).'" ) ';
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
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	
	
	if (($return != false) && is_array($return)){
		$totalRows = count($return);
		//LIMIT
		if (isset($p['limit']) && is_array($p['limit'])){
			if (isset($p['limit']['start']) && isset($p['limit']['num'])){
				//$query .= ' LIMIT '.$p['limit']['start'].', '.$p['limit']['num'];
				$ret = array();
				for ($i = $p['limit']['start']; $i < $p['limit']['start']+$p['limit']['num']; $i++){
					if (isset($return[$i])){
						array_push($ret, $return[$i]);
					}
				}
				$return = $ret;
			}
		}
		$return['totalRows'] = $totalRows;		
	}
	return $return;
}
?>
