<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select all Car extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCar2Ext($p=null){
	$return = false;
	$db = DB::getInstance();

	
	//car extra active
	if (!isset($p['active'])){
		$p['active'] = 1;
	}
	
	$query = '	SELECT c2e.*, ce.vextID
				FROM car2Ext as c2e, carExt as ce
				WHERE ( c2e.carExtID = ce.carExtID ) 
					AND (ce.active = '.$db -> escape($p['active']).') ';	

	//car2ExtID
	if(isset($p['car2ExtID'])){
		$query .= 'AND (c2e.car2ExtID = '.$db -> escape($p['car2ExtID']).') ';
	}	

	//carID
	if(isset($p['carID'])){
		$query .= 'AND (c2e.carID = '.$db -> escape($p['carID']).') ';
	}	

	//carExtID
	if(isset($p['carExtID'])){
		$query .= 'AND (c2e.carExtID = '.$db -> escape($p['carExtID']).') ';
	}

	//vextID
	if(isset($p['vextID'])){
		$query .= 'AND (ce.vextID = '.$db -> escape($p['vextID']).') ';
	}
	
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= 'ORDER BY ';
		foreach ($p['orderby'] as $orderby){
			$query .= $db -> escape($orderby['col']);
			if(isset($orderby['desc']) && ($orderby['desc'] == true)){
				$query .= ' DESC';
			}
			$query .= ',';
		}		
		$query = substr($query, 0, -1);
	}
	
	if (isset($p['p'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
