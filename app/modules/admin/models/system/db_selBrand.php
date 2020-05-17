<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This function select system group
 *********************************************************************************/
include_once('classes/DB.php');

function db_selBrand($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	SELECT 	b.*,
						(SELECT IF ((SELECT COUNT(cb.carBrandID)
									FROM carBrand AS cb
									WHERE (cb.brandID = b.brandID) AND (cb.active = "1"))
									,1,0)) AS carBrand,
						(SELECT IF ((SELECT COUNT(bb.bikeBrandID)
									FROM bikeBrand AS bb
									WHERE (bb.brandID = b.brandID) AND (bb.active = "1"))
									,1,0)) AS bikeBrand,
						(SELECT IF ((SELECT COUNT(tb.truckBrandID)
									FROM truckBrand AS tb
									WHERE (tb.brandID = b.brandID) AND (tb.active = "1"))
									,1,0)) AS truckBrand
				FROM brand as b 
				WHERE ';
	
	//erased
	if (isset($p['erased'])){
		$query .= ' (b.erased = "'.$p['erased'].'") AND ';
	}else{
		$query .= ' (b.erased = "0") AND ';
	}
	
	//brandID
	if (isset($p['brandID'])){
		$query .= ' (b.brandID = "'.$p['brandID'].'") AND ';
	}
	
	//brandName
	if (isset($p['brandName'])){
		$query .= ' (b.brandName = "'.$p['brandName'].'") AND ';
	}
	$query = substr($query, 0, -4);
	

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