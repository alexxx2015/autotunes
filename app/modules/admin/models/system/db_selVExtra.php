<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical extras
 *********************************************************************************/
include_once('classes/DB.php');

function db_selVExtra($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	//extra erased
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
		
	$query = '	SELECT v1.vextID, v1.erased, 
					(	SELECT IF ((SELECT COUNT(ce.carExtID)
									FROM carExt AS ce
									WHERE (ce.vextID = v1.vextID) AND (ce.active = "1"))
									,1,0)) AS carExtExist,
					(	SELECT IF ((SELECT COUNT(be.bikeExtID)
									FROM bikeExt AS be
									WHERE (be.vextID = v1.vextID) AND (be.active = "1"))
									,1,0)) AS bikeExtExist,
					(	SELECT IF ((SELECT COUNT(te.truckExtID)
									FROM truckExt AS te
									WHERE (te.vextID = v1.vextID) AND (te.active = "1"))
									,1,0)) AS truckExtExist
				FROM vext AS v1
				WHERE (v1.erased = "'.$p['erased'].'") ';
	
	//process vextID
	if (isset($p['vextID'])){
		if(is_array($p['vextID'])){
			$query .= ' AND (v1.vextID IN ('.$db -> escape(implode(',', $p['vextID'])).')) ';
		}
		else{
			$query .= ' AND (v1.vextID = '.$db -> escape($p['vextID']).') ';
		}
	}
	
	//process notVextID
	if (isset($p['notVextID'])){
		if (is_array($p['notVextID'])){
			$query .= ' AND (v1.vextID NOT IN ('.$db -> escape(implode(',', $p['notVextID'])).')) ';
		}elseif (is_numeric($p['notVextID'])){
			$query .= ' AND NOT( v1.vextID = '.$db -> escape($p['notVextID']).') ';
		}
	}
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
