<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select truck details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTruckAd($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT	c.*,
						(	SELECT brandName
							FROM brand AS b, truckBrand AS cb
							WHERE (b.brandID = cb.brandID)
									AND (cb.truckBrandID = c.truckBrandID)
						) AS truckBrandName,
						(	SELECT truckModelName
							FROM truckModel AS cm
							WHERE (cm.truckModelID = c.truckModelID)
						) AS truckModelName,
						(
							SELECT u.userLinkAds
							FROM user AS u
							WHERE c.userID = u.userID
						) AS userLinkAds
				FROM truck AS c
				WHERE (c.erased = 0) 
						AND EXISTS(	SELECT brandName
									FROM brand AS b, truckBrand AS cb
									WHERE (b.brandID = cb.brandID)
											AND (cb.truckBrandID = c.truckBrandID)
											AND (b.erased = "0")
											AND (cb.active = "1")
								) ';
	
	
	//Add truckBrand
	if (isset($p['truckID'])){
		$query .= ' AND ( c.truckID = '.$db -> escape($p['truckID']).') ';
	}
	
	//Add userID
	if (isset($p['userID'])){
		$query .= ' AND ( c.userID = "'.$db -> escape($p['userID']).'") ';
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
	
	//LIMIT
	if (isset($p['limit']) && is_array($p['limit'])){
		if (isset($p['limit']['start']) && isset($p['limit']['num'])){
			$query .= ' LIMIT '.$p['limit']['start'].', '.$p['limit']['num'];
		}
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
