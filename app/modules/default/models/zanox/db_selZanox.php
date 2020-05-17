<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Car brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selZanox($p=array()){
	$return = false;
	
	$db = DB::getInstance();
	
	if (!isset($p['new'])){
		$p['new'] = '0';
	}
	
	
	$query = '	SELECT z.* 
				FROM zanox as z
				WHERE (z.new '.(is_array($p['new'])?' IN ("'.implode('","', $p['new']).'")' : '="'.$db -> escape($p['new']).'"').') ';
	
	//zanoxID
	if (isset($p['zanoxID'])){
		$query .= ' AND (z.zanoxID = "'.$db -> escape($p['zanoxID']).'") ';
	}
	
	//zupid
	if (isset($p['zupid'])){
		$query .= ' AND (z.zupid = "'.$db -> escape($p['zupid']).'") ';
	}
	
	//program
	if (isset($p['program'])){
		$query .= ' AND (z.program = "'.$db -> escape($p['program']).'") ';
	}
	
	//number
	if (isset($p['number'])){
		$query .= ' AND (z.number = "'.$db -> escape($p['number']).'") ';
	}
	
	//timestam
	if (isset($p['timestam'])){
		$query .= ' AND (z.timestam = "'.$db -> escape($p['timestam']).'") ';
	}
	
	//ORDER BY
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= 'ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $orderby['col'];
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC ';
			}
			$query .= ',';
		}		
		$query = substr($query, 0, -1);
	}		
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));
	
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
	
	return $return;
}
?>
