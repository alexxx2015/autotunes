<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Car brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selAffiliProp($p=array()){
	$return = false;
	
	$db = DB::getInstance();
	
	
	$query = '	SELECT a.* 
				FROM affiliProp as a ';
	$where = false;
	
	//affiliPropID
	if (isset($p['affiliPropID'])){
		if(!$where){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$where .= ' AND ';
		}
		$query .= ' (a.affiliPropID = "'.$db -> escape($p['affiliPropID']).'") ';
	}
	
	//affiliID
	if (isset($p['affiliID'])){
		if(!$where){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$where .= ' AND ';
		}
		$query .= ' (a.affiliID = "'.$db -> escape($p['affiliID']).'") ';
	}
	
	//propName
	if (isset($p['propName'])){
		if(!$where){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$where .= ' AND ';
		}
		$query .= ' (a.propName = "'.$db -> escape($p['propName']).'") ';
	}
	
	//propValue
	if (isset($p['propValue'])){
		if(!where){
			$query .= ' WHERE ';
			$where = true;
		}
		else{
			$where .= ' AND ';
		}
		$query .= ' (a.propValue = "'.$db -> escape($p['propValue']).'") ';
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
