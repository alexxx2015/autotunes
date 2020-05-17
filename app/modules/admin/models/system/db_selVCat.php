<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100826
 * Desc:		This function select vehical category
 *********************************************************************************/
include_once('classes/DB.php');

function db_selVCat($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	//extra erased
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
		
	$query = '	SELECT v1.vcatID, v1.erased, 
					(	SELECT IF ((SELECT COUNT(cc.carCatID)
									FROM carCat AS cc
									WHERE (cc.carCatID = v1.vcatID) AND (cc.active = "1"))
									,1,0)) AS carCatExist,
					(	SELECT IF ((SELECT COUNT(bc.bikeCatID)
									FROM bikeCat AS bc
									WHERE (bc.vcatID = v1.vcatID) AND (bc.active = "1"))
									,1,0)) AS bikeCatExist,
					(	SELECT IF ((SELECT COUNT(tc.truckCatID)
									FROM truckCat AS tc
									WHERE (tc.vcatID = v1.vcatID) AND (tc.active = "1"))
									,1,0)) AS truckCatExist
				FROM vcat AS v1
				WHERE (v1.erased = "'.$p['erased'].'") ';
	
	//process vcatID
	if (isset($p['vcatID'])){
		if(is_array($p['vcatID'])){
			$query .= ' AND (v1.vcatID IN ('.$db -> escape(implode(',', $p['vcatID'])).')) ';
		}
		else{
			$query .= ' AND (v1.vcatID = '.$db -> escape($p['vcatID']).') ';
		}
	}
	
	//process notVcatID
	if (isset($p['notVcatID'])){
		if (is_array($p['notVcatID'])){
			$query .= ' AND (v1.vcatID NOT IN ('.$db -> escape(implode(',', $p['notVcatID'])).')) ';
		}elseif (is_numeric($p['notVcatID'])){
			$query .= ' AND NOT( v1.vcatID = '.$db -> escape($p['notVcatID']).') ';
		}
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
