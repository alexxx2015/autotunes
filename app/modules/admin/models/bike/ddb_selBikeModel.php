<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This function select all Bike brands
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBikeModel($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	//erased
	if (!isset($p['erased'])){
		$p['erased'] = 0;
	}
	
	$query = '	SELECT bm.bikeModelID, bm.bikeModelName, bm.bikeBrandID, bb.brandID
				FROM bikeModel AS bm, bikeBrand AS bb
				WHERE (bm.bikeBrandID = bb.bikeBrandID) 
						AND EXISTS ( 	SELECT * 
										FROM brand AS b
										WHERE bb.brandID = b.brandID
												AND b.erased = "'.$db -> escape($p['erased']).'" 
									) ';
	
	if(isset($p['bikeBrandID'])){		
		if (is_array($p['bikeBrandID'])){
			$p['bikeBrandID'] = $db -> escape($p['bikeBrandID']);
			$query .= 'AND (bm.bikeBrandID IN ("'.implode('","',$p['bikeBrandID']).'") )';
		}else{
			$query .= 'AND (bm.bikeBrandID = '.$db -> escape($p['bikeBrandID']).') ';
		}
	}
	
	if(isset($p['bikeModelID'])){
		$query .= 'AND (bm.bikeModelID = '.$db -> escape($p['bikeModelID']).') ';
	} 
	
	//active
	if (isset($p['active'])){
		$query .= ' AND (bb.active = '.$db -> escape($p['active']).') ';
	}else{
		$query .= ' AND (bb.active = "1") ';
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
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
