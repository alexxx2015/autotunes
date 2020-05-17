<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100901
 * Desc:		This function select appropriate car ads from table car
 *********************************************************************************/
include_once('classes/DB.php');

function db_selCarAds($p){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT	c.carID, 
						c.carBrandID, 
						(	SELECT brandName
							FROM brand AS b, carBrand AS cb
							WHERE (b.brandID = cb.brandID)
									AND (cb.carBrandID = c.carBrandID)
						) AS carBrandName,
						c.carModelID,  
						(	SELECT carModelName
							FROM carModel AS cm
							WHERE (cm.carModelID = c.carModelID)
						) AS carModelName,
						c.carPrice, 
						c.carPriceType,
						c.mwst,
						c.mwstSatz,
						c.carKM, 
						c.carPower, 
						c.carPowerType, 
						c.carEZM, 
						c.carEZY, 
						c.carState, 
						c.carCat, 
						c.carFuel, 
						c.carClr, 
						c.carClrMet ';
	
	
	//ORDER BY price?
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$addPriceBrutto = false;
		$orderByArr = array();
		foreach ($p['orderby'] as $orderby){
			if(isset($orderby['col']) && ($orderby['col'] == 'carPrice')){
					$orderby['col'] = 'carPriceBrutto';
					$addPriceBrutto = true;
			}
			array_push($orderByArr, $orderby);
		}
		$p['orderby'] = $orderByArr;
		if($addPriceBrutto == true){
			$query .= ', (IF((c.mwst=1 and c.mwstSatz > 0),(c.carPrice * (c.mwstSatz/100 + 1)),c.carPrice)) AS carPriceBrutto ';
		}
	}
		
	$query .= '
				FROM car AS c
				WHERE (c.erased = 0) 
						AND EXISTS(	SELECT brandName
									FROM brand AS b, carBrand AS cb
									WHERE (b.brandID = cb.brandID)
											AND (cb.carBrandID = c.carBrandID)
											AND (b.erased = "0")
											AND (cb.active = "1")
								) ';

	
	//Add userID
	if (isset($p['userID']) && ($p['userID'] != null)){
		$query .= ' AND (c.userID = "'.$db -> escape($p['userID']).'") ';
	}
	
	//Add carBrand
	if (isset($p['carID'])){
		$query .= ' AND ( c.carID = '.$db -> escape($p['carID']).') ';
	}
	
	//Add carBrand & carModel
	if (isset($p['carBrand']) || isset($p['carModel'])){					
		//Add carModel
		if (isset($p['carModel']) && is_array($p['carModel']) && (count($p['carModel']) > 0)){
			$p['carModel'] = $db -> escape($p['carModel']);
			$query .= ' AND ( c.carModelID IN ('.implode(',', $p['carModel']).') ';
			
			if (is_array($p['carBrand']) && (count($p['carBrand']) > 0)){
				$p['carBrand'] = $db -> escape($p['carBrand']);
				$query .= ' OR c.carBrandID IN ('.implode(',', $p['carBrand']).') ';
			}
			$query .= ' ) ';
		}elseif (isset($p['carBrand']) && is_array($p['carBrand']) && (count($p['carBrand']) > 0)){
			$p['carBrand'] = $db -> escape($p['carBrand']);
			$query .= ' AND ( c.carBrandID IN ('.implode(',', $p['carBrand']).')) ';
		}		
	}
	elseif (isset($p['carModel'])){
		$query .= ' AND (c.carModelID = "'.$db -> escape($p['carModel']).'") ';
	}
	
	//Add carPrice
	if(isset($p['priceWMwst']) && ($p['priceWMwst'] == true)){
		if (isset($p['carPriceF']) && ($p['carPriceF'] >= 0)){
			$p['carPriceF'] = $db -> escape($p['carPriceF']);
			$query .= ' AND ( (c.mwst = "1" and c.mwstSatz > 0 and (c.carPrice * ((c.mwstSatz/100)+1) >= '.$p['carPriceF'].'))
							or (c.mwst = "1" and (c.mwstSatz <= 0 or c.mwstSatz IS NULL) and (c.carPrice >= '.$p['carPriceF'].'))
							or (c.mwst = "0" and (c.carPrice >= '.$p['carPriceF'].')) ) ';
		}
		if (isset($p['carPriceT']) && ($p['carPriceT'] > 0)){
			$p['carPriceT'] = $db -> escape($p['carPriceT']);
			$query .= ' AND ( (c.mwst = "1" and c.mwstSatz > 0 and (c.carPrice * ((c.mwstSatz/100)+1) <= '.$p['carPriceT'].'))
							or (c.mwst = "1" and (c.mwstSatz <= 0 or c.mwstSatz IS NULL) and (c.carPrice <= '.$p['carPriceT'].'))
							or (c.mwst = "0" and (c.carPrice <= '.$p['carPriceT'].')) ) ';
		}
		
	}else{
		if (isset($p['carPriceF']) && ($p['carPriceF'] >= 0)){
			$p['carPriceF'] = $db -> escape($p['carPriceF']);
			$query .= ' AND (c.carPrice >= '.$p['carPriceF'].')';
		}
		if (isset($p['carPriceT']) && ($p['carPriceT'] > 0)){
			$p['carPriceT'] = $db -> escape($p['carPriceT']);
			$query .= ' AND (c.carPrice <= '.$p['carPriceT'].')';
		}
	}
	
	//Add carKM
	if (isset($p['carKMF']) && ($p['carKMF'] >= 0)){
		$p['carKMF'] = $db -> escape($p['carKMF']);
		$query .= ' AND (c.carKM >= '.$p['carKMF'].')';
	}
	if (isset($p['carKMT']) && ($p['carKMT'] > 0)){
		$p['carKMT'] = $db -> escape($p['carKMT']);
		$query .= ' AND (c.carKM <= '.$p['carKMT'].')';
	}
	if (isset($p['carKMType']) && ($p['carKMType'] != -1)){
		$p['carKMType'] = $db -> escape($p['carKMType']);
		$query .= ' AND (c.carKMType = '.$p['carKMType'].')';		
	}
	
	//Add carPower
	if (isset($p['carPowerF']) && ($p['carPowerF'] >= 0)){
		$p['carPowerF'] = $db -> escape($p['carPowerF']);
		$query .= ' AND (c.carPower >= '.$p['carPowerF'].')';
	}
	if (isset($p['carPowerT']) && ($p['carPowerT'] > 0)){
		$p['carPowerT'] = $db -> escape($p['carPowerT']);
		$query .= ' AND (c.carPower <= '.$p['carPowerT'].')';
	}
	
	//Add carEZ
	if (isset($p['carEZF']) && ($p['carEZF'] >= 0)){
		$p['carEZF'] = $db -> escape($p['carEZF']);
		$query .= ' AND (c.carEZY >= '.$p['carEZF'].')';
	}
	if (isset($p['carEZT']) && ($p['carEZT'] >= 0)){
		$p['carEZT'] = $db -> escape($p['carEZT']);
		$query .= ' AND (c.carEZY <= '.$p['carEZT'].')';
	}
	
	//Add carShift
	if (isset($p['carShift']) && ($p['carShift'] > 0)){
		$p['carShift'] = $db -> escape($p['carShift']);
		$query .= ' AND (c.carShift = '.$p['carShift'].')';
	}
	
	//Add carDoor
	if (isset($p['carDoor']) && ($p['carDoor'] > 0)){
		$p['carDoor'] = $db -> escape($p['carDoor']);
		$query .= ' AND (c.carDoor = '.$p['carDoor'].')';
	}
	
	//Add carKlima
	if (isset($p['carKlima']) && ($p['carKlima'] != -1)){
		$p['carKlima'] = $db -> escape($p['carKlima']);
		$query .= ' AND (c.carKlima = '.$p['carKlima'].')';
	}
	
	//Add carAds
	if (isset($p['carAds']) && ($p['carAds'] > 0)){
		$p['carAds'] = $db -> escape($p['carAds']);
		$query .= ' AND (c.carAds = '.$p['carAds'].')';
	}
	
	//Add carAge
	if (isset($p['carAge']) && ($p['carAge'] > 0)){
		$p['carAge'] = $db -> escape($p['carAge']);
		$query .= ' AND (c.timestam >= '.(time() - ($p['carAge']*86400)).')';
	}
	
	//Add carCat
	if (isset($p['carCat'])){
		$p['carCat'] = $db -> escape($p['carCat']);
		if(is_array($p['carCat'])){
			$carCatImplode = implode(',', $p['carCat']);
			$query .= ' AND ( c.carCat IN ('.$carCatImplode.')) ';
		}
	}
	
	//Add carClr
	if (isset($p['carClr'])){
		$p['carClr'] = $db -> escape($p['carClr']);
		if(is_array($p['carClr'])){
			$carClrImplode = implode(',', $p['carClr']);
			$query .= ' AND ( c.carClr IN ('.$carClrImplode.')) ';
			
			if (isset($p['carClrMet'])){
				$query .= ' AND (c.carClrMet = 1)';
			}
		}
	}
	
	//Add carFuel
	if (isset($p['carFuel'])){
		$p['carFuel'] = $db -> escape($p['carFuel']);
		if(is_array($p['carFuel'])){
			$carFuelImplode = implode(',', $p['carFuel']);
			$query .= ' AND ( c.carFuel IN ('.$carFuelImplode.')) ';
		}
	}
	
	//Add carEmissionNorm
	if (isset($p['carEmissionNorm'])){
		$p['carEmissionNorm'] = $db -> escape($p['carEmissionNorm']);
		if(is_array($p['carEmissionNorm'])){
			$carEmissNormImplode = implode(',', $p['carEmissionNorm']);
			$query .= ' AND ( c.carEmissionNorm IN ('.$carEmissNormImplode.')) ';
		}
	}
	
	//Add carEcologicTag
	if (isset($p['carEcologicTag'])){
		$p['carEcologicTag'] = $db -> escape($p['carEcologicTag']);
		if(is_array($p['carEcologicTag'])){
			$carEcologicTag = implode(',', $p['carEcologicTag']);
			$query .= ' AND ( c.carEcologicTag IN ('.$carEcologicTag.')) ';
		}
	}
	
	//Add carState
	if (isset($p['carState'])){
		$p['carState'] = $db -> escape($p['carState']);
		if(is_array($p['carState'])){
			$carState = implode(',', $p['carState']);
			$query .= ' AND ( c.carState IN ('.$carState.')) ';
		}
	}
	
	//Add userAds
	if (isset($p['userAds']) && ($p['userAds'] != -1)){
		$query .= ' AND ( c.userAds = '.$db -> escape($p['userAds']).') ';
	}
	
	//Add carExt
	if (isset($p['carExt'])){
		$p['carExt'] = $db -> escape($p['carExt']);
		if(is_array($p['carExt'])){
			$carExt = implode(',', $p['carExt']);/*
			$query .= ' AND c.carID IN (SELECT DISTINCT ce.carID
										FROM carExt AS ce
										WHERE ce.vextID IN ('.$carExt.')) ';
			*/
			$query .= ' AND c.carID IN (SELECT ce.carID
										FROM carExt AS ce
										WHERE ce.vextID IN ('.$carExt.')
										GROUP BY ce.carID
										HAVING COUNT(ce.carID) >= '.count($p['carExt']).'
										)';
			
			/*
			foreach ($p['carExt'] as $val) {
				$query .= ' AND EXISTS (SELECT ce.carExtID
										FROM carExt AS ce
										WHERE 	(ce.vextID = '.$val.')
												AND (ce.carID = c.carID) 
										)';
			}
			*/
		}
	}
	
	
	//Add PLZ
	
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
	
	$return = $db->execQuery(array('q'=>$query));

	if (($return != false) && is_array($return)){
		$totalRows = count($return);
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
		$return['totalRows'] = $totalRows;		
	}
	
	return $return;
}
?>
