<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100901
 * Desc:		This function select appropriate bike ads from table bike
 *********************************************************************************/
include_once('classes/DB.php');

function db_selExpBikeAds($p){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT	b.*, 
						(	SELECT brandName
							FROM brand AS b, bikeBrand AS bb
							WHERE (b.brandID = bb.brandID)
									AND (bb.bikeBrandID = b.bikeBrandID)
						) AS bikeBrandName,
						(	SELECT bikeModelName
							FROM bikeModel AS bm
							WHERE (bm.bikeModelID = b.bikeModelID)
						) AS bikeModelName 
				FROM bike AS b
				WHERE (b.erased = 0) 
						AND EXISTS(	SELECT brandName
									FROM brand AS b, bikeBrand AS bb
									WHERE (b.brandID = bb.brandID)
											AND (bb.bikeBrandID = b.bikeBrandID)
											AND (b.erased = "0")
											AND (bb.active = "1")
								) ';

	
	//Add userID
	if (isset($p['userID']) && ($p['userID'] != null)){
		$query .= ' AND (b.userID = "'.$db -> escape($p['userID']).'") ';
	}

	//Add bikeID
	if (isset($p['bikeID'])){
		$query .= ' AND ';
		if (is_array($p['bikeID'])){
			$query .= ' (bikeID IN ( "'.implode('","', $p['bikeID']).'") ) ';
		}else{
			$query .= ' (bikeID = "'.$p['bikeID'].'") ';
		}
	}
	//Add bikeBrand
	if (isset($p['bikeBrand'])){
		$p['bikeBrand'] = $db -> escape($p['bikeBrand']);
		if(is_array($p['bikeBrand'])){
			$bikeBrandImplode = implode(',', $p['bikeBrand']);
			$query .= ' AND ( b.bikeBrandID IN ('.$bikeBrandImplode.')) ';
		
			//Add bikeModel
			if (isset($p['bikeModel'])){
				$p['bikeModel'] = $db -> escape($p['bikeModel']);
				if(is_array($p['bikeModel'])){
					$bikeModel = implode(',', $p['bikeModel']);
					$query .= ' AND (b.bikeModelID IN (	SELECT bm.bikeModelID
														FROM bikeModel AS bm
														WHERE 	(bm.bikeBrandID IN ('.$bikeBrandImplode.'))
																AND (bm.bikeModelID IN ('.$bikeModel.'))
													)
									)';
				}
			}
		}else{
			$query .= ' AND (b.bikeBrandID = "'.$p['bikeBrand'].'") ';
		}
	}
	elseif (isset($p['bikeModel'])){
		$query .= ' AND (b.bikeModelID = "'.$db -> escape($p['bikeModel']).'") ';
	}
	
	//Add bikePrice
	if (isset($p['bikePriceF']) && ($p['bikePriceF'] >= 0)){
		$p['bikePriceF'] = $db -> escape($p['bikePriceF']);
		$query .= ' AND (b.bikePrice >= '.$p['bikePriceF'].')';
	}
	if (isset($p['bikePriceT']) && ($p['bikePriceT'] >= 0)){
		$p['bikePriceT'] = $db -> escape($p['bikePriceT']);
		$query .= ' AND (b.bikePrice <= '.$p['bikePriceT'].')';
	}
	
	//Add bikeKM
	if (isset($p['bikeKMF']) && ($p['bikeKMF'] >= 0)){
		$p['bikeKMF'] = $db -> escape($p['bikeKMF']);
		$query .= ' AND (b.bikeKM >= '.$p['bikeKMF'].')';
	}
	if (isset($p['bikeKMT']) && ($p['bikeKMT'] >= 0)){
		$p['bikeKMT'] = $db -> escape($p['bikeKMT']);
		$query .= ' AND (b.bikeKM <= '.$p['bikeKMT'].')';
	}
	if (isset($p['bikeKMType']) && ($p['bikeKMType'] != -1)){
		$p['bikeKMType'] = $db -> escape($p['bikeKMType']);
		$query .= ' AND (b.bikeKMType = '.$p['bikeKMType'].')';		
	}
	
	//Add bikePower
	if (isset($p['bikePowerF']) && ($p['bikePowerF'] >= 0)){
		$p['bikePowerF'] = $db -> escape($p['bikePowerF']);
		$query .= ' AND (b.bikePower >= '.$p['bikePowerF'].')';
	}
	if (isset($p['bikePowerT']) && ($p['bikePowerT'] >= 0)){
		$p['bikePowerT'] = $db -> escape($p['bikePowerT']);
		$query .= ' AND (b.bikePower <= '.$p['bikePowerT'].')';
	}
	
	//Add bikeEZ
	if (isset($p['bikeEZF']) && ($p['bikeEZF'] >= 0)){
		$p['bikeEZF'] = $db -> escape($p['bikeEZF']);
		$query .= ' AND (b.bikeEZY >= '.$p['bikeEZF'].')';
	}
	if (isset($p['bikeEZT']) && ($p['bikeEZT'] >= 0)){
		$p['bikeEZT'] = $db -> escape($p['bikeEZT']);
		$query .= ' AND (b.bikeEZY <= '.$p['bikeEZT'].')';
	}
	
	//Add bikeShift
	if (isset($p['bikeShift']) && ($p['bikeShift'] > 0)){
		$p['bikeShift'] = $db -> escape($p['bikeShift']);
		$query .= ' AND (b.bikeShift = '.$p['bikeShift'].')';
	}
	
	//Add bikeDoor
	if (isset($p['bikeDoor']) && ($p['bikeDoor'] > 0)){
		$p['bikeDoor'] = $db -> escape($p['bikeDoor']);
		$query .= ' AND (b.bikeDoor = '.$p['bikeDoor'].')';
	}
	
	//Add bikeAge
	if (isset($p['bikeAge']) && ($p['bikeAge'] > 0)){
		$p['bikeAge'] = $db -> escape($p['bikeAge']);
		$query .= ' AND (b.timestam >= '.(time() - ($p['bikeAge']*86400)).')';
	}
	
	//Add bikeCat
	if (isset($p['bikeCat'])){
		$p['bikeCat'] = $db -> escape($p['bikeCat']);
		if(is_array($p['bikeCat'])){
			$bikeCatImplode = implode(',', $p['bikeCat']);
			$query .= ' AND ( b.bikeCat IN ('.$bikeCatImplode.')) ';
		}
	}
	
	//Add bikeClr
	if (isset($p['bikeClr'])){
		$p['bikeClr'] = $db -> escape($p['bikeClr']);
		if(is_array($p['bikeClr'])){
			$bikeClrImplode = implode(',', $p['bikeClr']);
			$query .= ' AND ( b.bikeClr IN ('.$bikeClrImplode.')) ';
			
			if (isset($p['bikeClrMet'])){
				$query .= ' AND (b.bikeClrMet = 1)';
			}
		}
	}
	
	//Add bikeFuel
	if (isset($p['bikeFuel'])){
		$p['bikeFuel'] = $db -> escape($p['bikeFuel']);
		if(is_array($p['bikeFuel'])){
			$bikeFuelImplode = implode(',', $p['bikeFuel']);
			$query .= ' AND ( b.bikeFuel IN ('.$bikeFuelImplode.')) ';
		}
	}
	
	//Add bikeEmissionNorm
	if (isset($p['bikeEmissionNorm'])){
		$p['bikeEmissionNorm'] = $db -> escape($p['bikeEmissionNorm']);
		if(is_array($p['bikeEmissionNorm'])){
			$bikeEmissNormImplode = implode(',', $p['bikeEmissionNorm']);
			$query .= ' AND ( b.bikeEmissionNorm IN ('.$bikeEmissNormImplode.')) ';
		}
	}
	
	//Add bikeEcologicTag
	if (isset($p['bikeEcologicTag'])){
		$p['bikeEcologicTag'] = $db -> escape($p['bikeEcologicTag']);
		if(is_array($p['bikeEcologicTag'])){
			$bikeEcologicTag = implode(',', $p['bikeEcologicTag']);
			$query .= ' AND ( b.bikeEcologicTag IN ('.$bikeEcologicTag.')) ';
		}
	}
	
	//Add bikeState
	if (isset($p['bikeState'])){
		$p['bikeState'] = $db -> escape($p['bikeState']);
		if(is_array($p['bikeState'])){
			$bikeState = implode(',', $p['bikeState']);
			$query .= ' AND ( b.bikeState IN ('.$bikeState.')) ';
		}
	}
	
	//Add userAds
	if (isset($p['userAds']) && ($p['userAds'] != -1)){
		$query .= ' AND ( b.userAds = '.$db -> escape($p['userAds']).') ';
	}
	
	//Add bikeExt
	if (isset($p['bikeExtDB'])){
		$p['bikeExtDB'] = $db -> escape($p['bikeExtDB']);
		if(is_array($p['bikeExtDB'])){
			$bikeExtID = array();
			foreach($p['bikeExtDB'] as $key => $kValue){
				array_push($bikeExtID, $kValue['bikeExtID']);
			}
			/*
			$query .= ' AND c.bikeID IN (SELECT DISTINCT ce.bikeID
										FROM bikeExt AS ce
										WHERE ce.vextID IN ('.$bikeExt.')) ';
			*/
			$query .= ' AND b.bikeID IN (SELECT b2e.bikeID
										FROM bike2Ext AS b2e
										WHERE b2e.bikeExtID IN ("'.implode('","',$bikeExtID).'")
										)';
			
			/*
			foreach ($p['bikeExt'] as $val) {
				$query .= ' AND EXISTS (SELECT ce.bikeExtID
										FROM bikeExt AS ce
										WHERE 	(ce.vextID = '.$val.')
												AND (ce.bikeID = c.bikeID) 
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
