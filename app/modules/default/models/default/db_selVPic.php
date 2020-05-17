<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function select all picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_selVPic($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT *
				FROM vPic as v1 
				WHERE (	v1.vType = "'.System_Properties::CAR_ABRV.'"
						or v1.vType = "'.System_Properties::BIKE_ABRV.'"
						or v1.vType = "'.System_Properties::TRUCK_ABRV.'") ';

	if(isset($p['vPicID'])){
		$query .= 'AND ';
		if (is_array($p['vPicID'])){
			$query .= ' (v1.vPicID IN ( "'.implode('","',$db -> escape($p['vPicID'])).'" ) )';
		}else{
			$query .= ' (v1.vPicID = '.$db -> escape($p['vPicID']).') ';
		}
	}
	if(isset($p['notVPicID'])){
		$query .= 'AND ';
		if (is_array($p['notVPicID'])){
			$query .= ' (v1.vPicID NOT IN ( "'.implode('","',$db -> escape($p['notVPicID'])).'" ) )';
		}else{
			$query .= ' NOT(v1.vPicID = '.$db -> escape($p['notVPicID']).') ';
		}
	}
	
	if (isset($p['vType'])){
		$query .= 'AND (v1.vType = "'.$db -> escape($p['vType']).'") ';
	}
	
	if (isset($p['vID'])){
		$p['vID'] = $db -> escape($p['vID']);
		if (is_array($p['vID'])){
			$query .= 'AND (v1.vID IN ("'.implode('","', $p['vID']).'") ) ';
		}else{
			$query .= 'AND (v1.vID = '.$p['vID'].') ';			
		}
	}
	
	if (isset($p['vPicTMP'])){
		$query .= 'AND (v1.vPicTMP = "'.$db -> escape($p['vPicTMP']).'") ';
	}
	
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= ' ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $orderby['col'];
				
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC';
			}
			$query .= ',';
		}
		$query = substr($query, 0, -1);
	}
	
	if(isset($p['limit']) && is_array($p['limit'])
			&& isset($p['limit']['start']) && isset($p['limit']['num'])){
		$query .= ' LIMIT '.$p['limit']['start'].', '.$p['limit']['num'];
	}
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
