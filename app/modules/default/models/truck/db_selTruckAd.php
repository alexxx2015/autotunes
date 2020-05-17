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
	
	$erased = '0';
	if(isset($p['erased']) && ($p['erased'] == true)){
		$erased = '1';
	}
	
	$query = '	SELECT	t.*,
						(	SELECT brandName
							FROM brand AS b, truckBrand AS tb
							WHERE (b.brandID = tb.brandID)
									AND (tb.truckBrandID = t.truckBrandID)
						) AS truckBrandName,
						(	SELECT truckModelName
							FROM truckModel AS tm
							WHERE (tm.truckModelID = t.truckModelID)
						) AS truckModelName,
						(
							SELECT u.userLinkAds
							FROM user AS u
							WHERE t.userID = u.userID
						) AS userLinkAds
				FROM truck AS t
				WHERE (t.erased = '.$erased.') 
						AND EXISTS(	SELECT brandName
									FROM brand AS b, truckBrand AS tb
									WHERE (b.brandID = tb.brandID)
											AND (tb.truckBrandID = t.truckBrandID)
											AND (b.erased = "0")
											AND (tb.active = "1")
								) ';
	
	
	//Add truckBrand
	if (isset($p['truckID'])){
		$query .= ' AND ( t.truckID = '.$db -> escape($p['truckID']).') ';
	}
	
	//Add userID
	if (isset($p['userID'])){
		$query .= ' AND ( t.userID = "'.$db -> escape($p['userID']).'") ';
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
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
