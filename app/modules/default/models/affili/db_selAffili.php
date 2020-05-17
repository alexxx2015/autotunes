<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Car brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selAffili($p=array()){
	$return = false;
	
	$db = DB::getInstance();
	
	if (!isset($p['new'])){
		$p['new'] = '0';
	}
	
	
	$query = '	SELECT a.* 
				FROM affili as a
				WHERE (a.new '.(is_array($p['new'])?' IN ("'.implode('","', $p['new']).'")' : '="'.$db -> escape($p['new']).'"').') ';
	
	//affiliID
	if (isset($p['affiliID'])){
		$query .= ' AND (a.affiliID = "'.$db -> escape($p['affiliID']).'") ';
	}
	
	//programID
	if (isset($p['programID'])){
		$query .= ' AND (a.programID = "'.$db -> escape($p['programID']).'") ';
	}
	
	//articleNumber
	if (isset($p['articleNumber'])){
		$query .= ' AND (a.articleNumber = "'.$db -> escape($p['articleNumber']).'") ';
	}
	
	//timestam
	if (isset($p['timestam'])){
		$query .= ' AND (a.timestam = "'.$db -> escape($p['timestam']).'") ';
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
