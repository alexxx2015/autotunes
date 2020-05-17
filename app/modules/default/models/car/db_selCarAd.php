<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select car details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCarAd($p){	
	$return = false;
	$db = DB::getInstance();
	
	$erased = '0';
	if(isset($p['erased']) && ($p['erased'] == true)){
		$erased = '1';
	}
	
	$query = '	SELECT	c.*,
						(	SELECT brandName
							FROM brand AS b, carBrand AS cb
							WHERE (b.brandID = cb.brandID)
									AND (cb.carBrandID = c.carBrandID)
						) AS carBrandName,
						(	SELECT carModelName
							FROM carModel AS cm
							WHERE (cm.carModelID = c.carModelID)
						) AS carModelName,
						(
							SELECT u.userLinkAds
							FROM user AS u
							WHERE c.userID = u.userID
						) AS userLinkAds
				FROM car AS c
				WHERE 	(c.erased = '.$erased.') 
						AND EXISTS(	SELECT brandName
									FROM brand AS b, carBrand AS cb
									WHERE (b.brandID = cb.brandID)
											AND (cb.carBrandID = c.carBrandID)
											AND (b.erased = "0")
											AND (cb.active = "1")
								) ';
	
	
	//Add carBrand
	if (isset($p['carID'])){
		$query .= ' AND ( c.carID = '.$db -> escape($p['carID']).') ';
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
	
	if(isset($p['print'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
