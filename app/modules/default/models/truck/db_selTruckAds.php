<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100901
 * Desc:		This function select appropriate truck ads from table truck
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTruckAds($p){
	$return = false;
	$db = DB::getInstance();
	
	$p = $db -> escape($p);
	
	$query = '	SELECT	t.truckID 
						, t.truckBrandID 
						, (	SELECT brandName
							FROM brand AS b, truckBrand AS tb
							WHERE (b.brandID = tb.brandID)
									AND (tb.truckBrandID = t.truckBrandID)
						) AS truckBrandName
						, t.truckModelID  
						, (	SELECT truckModelName
							FROM truckModel AS tm
							WHERE (tm.truckModelID = t.truckModelID)
						) AS truckModelName
						, t.truckPrice 
						, t.truckPriceType
						, t.truckPriceCurr
						, t.mwst
						, t.mwstSatz
						, t.truckKM 
						, t.truckKMType
						, t.truckPower 
						, t.truckPowerType 
						, t.truckEZM 
						, t.truckEZY 
						, t.truckState 
						, t.truckCat 
						, t.truckFuel 
						, t.truckClr 
						, t.truckClrMet
						, t.userFirm
						, t.userNName
						, t.userVName
						, t.truckLocPLZ
						, t.truckLocOrt
						, t.truckLocCountry
						, t.truckCub';	
	
	//ORDER BY price?
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$addPriceBrutto = false;
		$orderByArr = array();
		foreach ($p['orderby'] as $orderby){
			if(isset($orderby['col']) && ($orderby['col'] == 'truckPrice')){
				$orderby['col'] = 'truckPriceBrutto';
				$addPriceBrutto = true;
			}
			array_push($orderByArr, $orderby);
		}
		$p['orderby'] = $orderByArr;
		if($addPriceBrutto == true){
			$query .= ', (IF((t.mwst=1 and t.mwstSatz > 0),(t.truckPrice * (t.mwstSatz/100 + 1)),t.truckPrice)) AS truckPriceBrutto ';
		}
	}		
	$erased = '0';
	if(isset($p['erased']) && ($p['erased'] == true)){
		$erased = '1';
	}
		
	$query .= '
				FROM truck AS t
				WHERE (t.erased = '.$erased.') 
						AND EXISTS(	SELECT brandName
									FROM brand AS b, truckBrand AS tb
									WHERE (b.brandID = tb.brandID)
											AND (tb.truckBrandID = t.truckBrandID)
											AND (b.erased = "0")
											AND (tb.active = "1"))  ';

	//Add userID
	if (isset($p['userID']) && ($p['userID'] != null)){
		$query .= ' AND (t.userID = "'.$db -> escape($p['userID']).'") ';
	}
	
	//Add truckID
	if (isset($p['truckID'])){
		//$query .= ' AND ( t.truckID = '.$db -> escape($p['truckID']).') ';
		
		$query .= ' AND ';
		if (is_array($p['truckID'])){
			$query .= ' (truckID IN ( "'.implode('","', $p['truckID']).'") ) ';
		}else{
			$query .= ' (truckID = "'.$p['truckID'].'") ';
		}
	}
	

	//Add truckBrand & truckModel
	if (isset($p['truckBrand']) || isset($p['truckModel'])){
						
		//Add truckModel
		if (isset($p['truckModel']) && is_array($p['truckModel']) && (count($p['truckModel']) > 0)){
			$p['truckModel'] = $db -> escape($p['truckModel']);
			$query .= ' AND ( t.truckModelID IN ('.implode(',', $p['truckModel']).') ';
			
			if (is_array($p['truckBrand']) && (count($p['truckBrand']) > 0)){
				$p['truckBrand'] = $db -> escape($p['truckBrand']);
				$query .= ' OR t.truckBrandID IN ('.implode(',', $p['truckBrand']).') ';
			}
			$query .= ' ) ';
		}elseif (isset($p['truckBrand']) && is_array($p['truckBrand']) && (count($p['truckBrand']) > 0)){
			$p['truckBrand'] = $db -> escape($p['truckBrand']);
			$query .= ' AND ( t.truckBrandID IN ('.implode(',', $p['truckBrand']).')) ';
		}		
	}
	elseif (isset($p['truckModel'])){
		$query .= ' AND (t.truckModelID = "'.$db -> escape($p['truckModel']).'") ';
	}
		
	//Add truckPrice
	if(isset($p['priceWMwst']) && ($p['priceWMwst'] == true)){
		if (isset($p['truckPriceF']) && ($p['truckPriceF'] >= 0)){
			$p['truckPriceF'] = $db -> escape($p['truckPriceF']);
			$query .= ' AND ( (t.mwst = "1" and t.mwstSatz > 0 and (t.truckPrice * ((t.mwstSatz/100)+1) >= '.$p['truckPriceF'].'))
							or (t.mwst = "1" and (t.mwstSatz <= 0 or t.mwstSatz IS NULL) and (t.truckPrice >= '.$p['truckPriceF'].'))
							or (t.mwst = "0" and (t.truckPrice >= '.$p['truckPriceF'].')) ) ';
		}
		if (isset($p['truckPriceT']) && ($p['truckPriceT'] > 0)){
			$p['truckPriceT'] = $db -> escape($p['truckPriceT']);
			$query .= ' AND ( (t.mwst = "1" and t.mwstSatz > 0 and (t.truckPrice * ((t.mwstSatz/100)+1) <= '.$p['truckPriceT'].'))
							or (t.mwst = "1" and (t.mwstSatz <= 0 or t.mwstSatz IS NULL) and (t.truckPrice <= '.$p['truckPriceT'].'))
							or (t.mwst = "0" and (t.truckPrice <= '.$p['truckPriceT'].')) ) ';
		}
		
	}else{
		if (isset($p['truckPriceF']) && ($p['truckPriceF'] >= 0)){
			$p['truckPriceF'] = $db -> escape($p['truckPriceF']);
			$query .= ' AND (t.truckPrice >= '.$p['truckPriceF'].')';
		}
		if (isset($p['truckPriceT']) && ($p['truckPriceT'] > 0)){
			$p['truckPriceT'] = $db -> escape($p['truckPriceT']);
			$query .= ' AND (t.truckPrice <= '.$p['truckPriceT'].')';
		}
	}
	
	//Add truckKM
	if (isset($p['truckKMF']) && ($p['truckKMF'] >= 0)){
		$p['truckKMF'] = $db -> escape($p['truckKMF']);
		$query .= ' AND (t.truckKM >= '.$p['truckKMF'].')';
	}
	if (isset($p['truckKMT']) && ($p['truckKMT'] > 0)){
		$p['truckKMT'] = $db -> escape($p['truckKMT']);
		$query .= ' AND (t.truckKM <= '.$p['truckKMT'].')';
	}
	if (isset($p['truckKMType']) && ($p['truckKMType'] != -1)){
		$p['truckKMType'] = $db -> escape($p['truckKMType']);
		$query .= ' AND (t.truckKMType = '.$p['truckKMType'].')';		
	}
	
	//Add truckPower
	if (isset($p['truckPowerF']) && ($p['truckPowerF'] >= 0)){
		$p['truckPowerF'] = $db -> escape($p['truckPowerF']);
		$query .= ' AND (t.truckPower >= '.$p['truckPowerF'].')';
	}
	if (isset($p['truckPowerT']) && ($p['truckPowerT'] > 0)){
		$p['truckPowerT'] = $db -> escape($p['truckPowerT']);
		$query .= ' AND (t.truckPower <= '.$p['truckPowerT'].')';
	}
	
	//Add truckEZ
	if (isset($p['truckEZF']) && ($p['truckEZF'] >= 0)){
		$p['truckEZF'] = $db -> escape($p['truckEZF']);
		$query .= ' AND (t.truckEZY >= '.$p['truckEZF'].')';
	}
	if (isset($p['truckEZT']) && ($p['truckEZT'] >= 0)){
		$p['truckEZT'] = $db -> escape($p['truckEZT']);
		$query .= ' AND (t.truckEZY <= '.$p['truckEZT'].')';
	}
	
	//Add truckShift
	if (isset($p['truckShift']) && ($p['truckShift'] > 0)){
		$p['truckShift'] = $db -> escape($p['truckShift']);
		$query .= ' AND (t.truckShift = '.$p['truckShift'].')';
	}
	
	//Add truckKlima
	if (isset($p['truckKlima']) && ($p['truckKlima'] != -1)){
		$p['truckKlima'] = $db -> escape($p['truckKlima']);
		$query .= ' AND (t.truckKlima = '.$p['truckKlima'].')';
	}
	
	//Add truckAds
	if (isset($p['truckAds']) && ($p['truckAds'] > 0)){
		$p['truckAds'] = $db -> escape($p['truckAds']);
		$query .= ' AND (t.truckAds = '.$p['truckAds'].')';
	}
	
	//Add truckAge
	if (isset($p['truckAge']) && ($p['truckAge'] > 0)){
		$p['truckAge'] = $db -> escape($p['truckAge']);
		$query .= ' AND (t.timestam >= '.(time() - ($p['truckAge']*86400)).')';
	}
	
	//Add truckCat
	if (isset($p['truckCat'])){
		$p['truckCat'] = $db -> escape($p['truckCat']);
		if(is_array($p['truckCat'])){
			$truckCatImplode = implode(',', $p['truckCat']);
			$query .= ' AND ( t.truckCat IN ('.$truckCatImplode.')) ';
		}
	}
	
	//Add truckClr
	if (isset($p['truckClr'])){
		$p['truckClr'] = $db -> escape($p['truckClr']);
		if(is_array($p['truckClr'])){
			$truckClrImplode = implode(',', $p['truckClr']);
			$query .= ' AND ( t.truckClr IN ('.$truckClrImplode.')) ';
			
			if (isset($p['truckClrMet'])){
				$query .= ' AND (t.truckClrMet = 1)';
			}
		}
	}
	
	//Add truckFuel
	if (isset($p['truckFuel'])){
		$p['truckFuel'] = $db -> escape($p['truckFuel']);
		if(is_array($p['truckFuel'])){
			$truckFuelImplode = implode(',', $p['truckFuel']);
			$query .= ' AND ( t.truckFuel IN ('.$truckFuelImplode.')) ';
		}
	}
	
	//Add truckEmissionNorm
	if (isset($p['truckEmissionNorm'])){
		$p['truckEmissionNorm'] = $db -> escape($p['truckEmissionNorm']);
		if(is_array($p['truckEmissionNorm'])){
			$truckEmissNormImplode = implode(',', $p['truckEmissionNorm']);
			$query .= ' AND ( t.truckEmissionNorm IN ('.$truckEmissNormImplode.')) ';
		}
	}
	
	//Add truckEcologicTag
	if (isset($p['truckEcologicTag'])){
		$p['truckEcologicTag'] = $db -> escape($p['truckEcologicTag']);
		if(is_array($p['truckEcologicTag'])){
			$truckEcologicTag = implode(',', $p['truckEcologicTag']);
			$query .= ' AND ( t.truckEcologicTag IN ('.$truckEcologicTag.')) ';
		}
	}
	
	//Add truckState
	if (isset($p['truckState'])){
		$p['truckState'] = $db -> escape($p['truckState']);
		if(is_array($p['truckState'])){
			$truckState = implode(',', $p['truckState']);
			$query .= ' AND ( t.truckState IN ('.$truckState.')) ';
		}
	}
	
	//Add userAds
	if (isset($p['userAds']) && ($p['userAds'] != -1)){
		$query .= ' AND ( t.userAds = '.$db -> escape($p['userAds']).') ';
	}
	//Add notUserAds
	elseif (isset($p['notUserAds']) && ($p['notUserAds'] != -1)){
		$query .= ' AND ( t.userAds != '.$db -> escape($p['notUserAds']).') ';
	}
	
	//Add PLZ
	if (isset($p['truckPLZ'])){
		if (isset($p['truckCC']) && ($p['truckCC'] != -1) && is_numeric($p['truckCC'])){
			if (is_array($p['truckPLZ']) && (count($p['truckPLZ']) > 0)){
				$truckPLZ = $p['truckPLZ'][0];
			}else{
				$truckPLZ = $p['truckPLZ'];
			}
			$query .= ' AND (t.truckLocPLZ ) IN (SELECT 	postal_code
												FROM 	geonameplz 
												WHERE 	'.$p['truckCC'].' > ACOS(
															SIN(RADIANS(latitude)) * SIN(RADIANS('.$truckPLZ['latitude'].')) 
												         	+ COS(RADIANS(latitude)) * COS(RADIANS('.$truckPLZ['latitude'].')) * COS(RADIANS(longitude) - RADIANS('.$truckPLZ['longitude'].'))
												         ) * 6380
												)';			
		}else{
			if (is_array($p['truckPLZ'])){
				$truckPLZ = array();
				foreach($p['truckPLZ'] as $key => $kVal){
					array_push($truckPLZ, $kVal['postal_code']);
				}
				$query .= ' AND ( t.truckLocPLZ IN ("'.implode('","', $truckPLZ).'") OR t.userPLZ IN ("'.implode('","', $truckPLZ).'")) ';
			}elseif(strlen($p['truckPLZ']) > 0){
				$truckPLZ = $p['truckPLZ'];
				$query .= ' AND (t.truckLocPLZ = "'.$p['truckPLZ'].'" OR t.userPLZ = "'.$truckPLZ.'") ';
			}
		}
	}
	
	//Add truckExt
	if (isset($p['truckExtDB'])){
		$p['truckExtDB'] = $db -> escape($p['truckExtDB']);
		if(is_array($p['truckExtDB'])){
			$truckExtID = array();
			foreach($p['truckExtDB'] as $key => $kValue){
				array_push($truckExtID, $kValue['truckExtID']);
			}
			/*
			$query .= ' AND t.truckID IN (SELECT DISTINCT ce.truckID
										FROM truckExt AS ce
										WHERE ce.vextID IN ('.$truckExt.')) ';
			*/
			$query .= ' AND t.truckID IN (SELECT t2e.truckID
										FROM truck2Ext AS t2e
										WHERE t2e.truckExtID IN ("'.implode('","',$truckExtID).'")
										)';
			
			/*
			foreach ($p['truckExt'] as $val) {
				$query .= ' AND EXISTS (SELECT ce.truckExtID
										FROM truckExt AS ce
										WHERE 	(ce.vextID = '.$val.')
												AND (ce.truckID = t.truckID) 
										)';
			}
			*/
		}
	}
	
	//ORDER BY
	if(isset($p['orderby']) && is_array($p['orderby']) && (count($p['orderby']) > 0)){
		$query .= ' ORDER BY ';
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
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	return $return;
}
?>
