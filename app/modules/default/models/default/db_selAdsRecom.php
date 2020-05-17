<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
  * Date: 		20111108
 * Desc:		Select from advertisment relationships
*********************************************************************************/
include_once('classes/DB.php');

function db_selAdsRecom($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT *
				FROM adsRecom as ar
				';
	
	$where = false;

	//vID1
	if (isset($p['vID1'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= '  (ar.vID1 = "'.$db -> escape($p['vID1']).'") ';
	}
	
	//vID2
	if (isset($p['vID2'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= '  (ar.vID2 = "'.$db -> escape($p['vID2']).'") ';
	}
	
	//vType
	if (isset($p['vType'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= '  (ar.vType = "'.$db -> escape($p['vType']).'") ';
	}
	
	//timestam
	if (isset($p['timestam']) || isset($p['timestamL']) || isset($p['timestamH'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		//timestam low
		if (isset($p['timestamL'])){
			$query .= ' (ar.timestam < '.$db -> escape($p['timestamL']).' ) ';	
		}
		
		//timestam high
		if (isset($p['timestamH'])){
			$query .= ' (ar.timestam > '.$db -> escape($p['timestamH']).' ) ';	
		}
	
		//timestam array
		if (isset($p['timestam']) && is_array($p['timestam'])){
			$query .= ' (ar.timestam IN ("'.implode('","', $db -> escape($p['timestam'])).'") ';	
		}
		elseif (isset($p['timestam'])){
			$query .= ' (ar.timestam = '.$db -> escape($p['timestam']).' ) ';	
		}
	}
	
	//ip
	if (isset($p['ip'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= '  (ar.ip = "'.$db -> escape($p['ip']).'") ';
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
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
