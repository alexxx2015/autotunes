<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100901
 * Desc:		This function select appropriate truck ads from table truck
 *********************************************************************************/
include_once('classes/DB.php');

function db_selExpTruckAds($p){
	$return = false;
	$db = DB::getInstance();
	
	$p = $db -> escape($p);
	
	$query = '	SELECT	t.*, 
						(	SELECT brandName
							FROM brand AS b, truckBrand AS tb
							WHERE (b.brandID = tb.brandID)
									AND (tb.truckBrandID = t.truckBrandID)
						) AS truckBrandName,  
						(	SELECT truckModelName
							FROM truckModel AS tm
							WHERE (tm.truckModelID = t.truckModelID)
						) AS truckModelName
						
				FROM truck AS t
				WHERE (t.erased = 0) 
						AND EXISTS(	SELECT brandName
									FROM brand AS b, truckBrand AS tb
									WHERE (b.brandID = tb.brandID)
											AND (tb.truckBrandID = t.truckBrandID)
											AND (b.erased = "0")
											AND (tb.active = "1")
								) ';

	
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
	
	//Add truckBrand
	if (isset($p['truckBrand'])){
		$p['truckBrand'] = $db -> escape($p['truckBrand']);
		if(is_array($p['truckBrand'])){
			$truckBrandImplode = implode(',', $p['truckBrand']);
			$query .= ' AND ( t.truckBrandID IN ('.$truckBrandImplode.')) ';
		
			//Add truckModel
			if (isset($p['truckModel'])){
				$p['truckModel'] = $db -> escape($p['truckModel']);
				if(is_array($p['truckModel'])){
					$truckModel = implode(',', $p['truckModel']);
					$query .= ' AND (t.truckModelID IN (	SELECT tm.truckModelID
														FROM truckModel AS tm
														WHERE 	(tm.truckBrandID IN ('.$truckBrandImplode.'))
																AND (tm.truckModelID IN ('.$truckModel.'))
													)
									)';
				}
			}
		}else{
			$query .= ' AND (t.truckBrandID = "'.$p['truckBrand'].'") ';
		}
	}
	elseif (isset($p['truckModel'])){
		$query .= ' AND (t.truckModelID = "'.$db -> escape($p['truckModel']).'") ';
	}
	
	//Add truckPrice
	if (isset($p['truckPriceF']) && ($p['truckPriceF'] >= 0)){
		$p['truckPriceF'] = $db -> escape($p['truckPriceF']);
		$query .= ' AND (t.truckPrice >= '.$p['truckPriceF'].')';
	}
	if (isset($p['truckPriceT']) && ($p['truckPriceT'] >= 0)){
		$p['truckPriceT'] = $db -> escape($p['truckPriceT']);
		$query .= ' AND (t.truckPrice <= '.$p['truckPriceT'].')';
	}
	
	//Add truckKM
	if (isset($p['truckKMF']) && ($p['truckKMF'] >= 0)){
		$p['truckKMF'] = $db -> escape($p['truckKMF']);
		$query .= ' AND (t.truckKM >= '.$p['truckKMF'].')';
	}
	if (isset($p['truckKMT']) && ($p['truckKMT'] >= 0)){
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
	if (isset($p['truckPowerT']) && ($p['truckPowerT'] >= 0)){
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
	
	//Add truckExt
	if (isset($p['truckExtDB'])){
		$p['truckExtDB'] = $db -> escape($p['truckExtDB']);
		if(is_array($p['truckExtDB'])){
			$truckExtID = array();
			foreach($p['truckExtDB'] as $key => $kValue){
				array_push($truckExtID, $kValue['truckExtID']);
			}
			/*
			$query .= ' AND c.truckID IN (SELECT DISTINCT ce.truckID
										FROM truckExt AS ce
										WHERE ce.vextID IN ('.$truckExt.')) ';
			*/
			$query .= ' AND c.truckID IN (SELECT t2e.truckID
										FROM truck2Ext AS t2e
										WHERE t2e.truckExtID IN ("'.implode('","',$truckExtID).'")
										)';
			
			/*
			foreach ($p['truckExt'] as $val) {
				$query .= ' AND EXISTS (SELECT ce.truckExtID
										FROM truckExt AS ce
										WHERE 	(ce.vextID = '.$val.')
												AND (ce.truckID = c.truckID) 
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
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	return $return;
}
?>
