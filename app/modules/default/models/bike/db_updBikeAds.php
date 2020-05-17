<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an bike advertisement in table BIKE
 *********************************************************************************/
include_once('classes/DB.php');

function db_updBikeAds($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE bike SET  ';
	
	//Bike Brand ID
	if(isset($p[System_Properties::SQL_SET]['bikeBrandID'])){		
		$query .= 'bikeBrandID = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeBrandID']).'", ';
	}
	
	//Bike Model ID
	if(isset($p[System_Properties::SQL_SET]['bikeModel'])){
		$query .= 'bikeModelID = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeModel']).'", ';
	}
	
	//Bike Model Var
	if(isset($p[System_Properties::SQL_SET]['bikeModelVar'])){
		$query .= 'bikeModelVar = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeModelVar']).'", ';
	}
	
	//Bike price
	if (isset($p[System_Properties::SQL_SET]['bikePrice'])){
		$query .= 'bikePrice = "'.$db -> escape($p[System_Properties::SQL_SET]['bikePrice']).'", ';
	}
	
	//Bike price type
	if (isset($p[System_Properties::SQL_SET]['bikePriceType'])){
		$query .= 'bikePriceType = "'.$db -> escape($p[System_Properties::SQL_SET]['bikePriceType']).'", ';
	}
	
	//Bike mwst
	if (isset($p[System_Properties::SQL_SET]['mwst'])){
		$query .= 'mwst = "'.$db -> escape($p[System_Properties::SQL_SET]['mwst']).'", ';
	}
	
	//Bike mwstSatz
	if (isset($p[System_Properties::SQL_SET]['mwstSatz'])){
		$query .= 'mwstSatz = "'.$db -> escape($p[System_Properties::SQL_SET]['mwstSatz']).'", ';
	}
	
	//Bike km
	if (isset($p[System_Properties::SQL_SET]['bikeKM'])){
		$query .= 'bikeKM = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeKM']).'", ';
	}
	
	//Bike kmType
	if (isset($p[System_Properties::SQL_SET]['bikeKMType'])){
		$query .= 'bikeKMType = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeKMType']).'", ';
	}
	
	//Bike power
	if (isset($p[System_Properties::SQL_SET]['bikePower'])){
		$query .= 'bikePower = "'.$db -> escape($p[System_Properties::SQL_SET]['bikePower']).'", ';
	}
	
	//Bike power type
	if (isset($p[System_Properties::SQL_SET]['bikePowerType'])){
		$query .= 'bikePowerType = "'.$db -> escape($p[System_Properties::SQL_SET]['bikePowerType']).'", ';
	}
	
	//Bike ezm
	if (isset($p[System_Properties::SQL_SET]['bikeEZM'])){
		$query .= 'bikeEZM = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeEZM']).'", ';
	}
	
	//Bike ezy
	if (isset($p[System_Properties::SQL_SET]['bikeEZY'])){
		$query .= 'bikeEZY = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeEZY']).'", ';
	}
	
	//BIKE_HSN
	if (isset($p[System_Properties::SQL_SET]['bikeHSN'])){
		$query .= 'bikeHSN = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeHSN']).'", ';
	}
	
	//BIKE_TSN
	if (isset($p[System_Properties::SQL_SET]['bikeTSN'])){
		$query .= 'bikeTSN = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeTSN']).'", ';
	}
	
	//BIKE_FIN
	if (isset($p[System_Properties::SQL_SET]['bikeFIN'])){
		$query .= 'bikeFIN = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeFIN']).'", ';
	}
	
	//Bike tuvm
	if (isset($p[System_Properties::SQL_SET]['bikeTUVM'])){
		$query .= 'bikeTUVM = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeTUVM']).'", ';
	}
	
	//Bike TUVY
	if (isset($p[System_Properties::SQL_SET]['bikeTUVY'])){
		$query .= 'bikeTUVY = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeTUVY']).'", ';
	}
	
	//Bike aum
	if (isset($p[System_Properties::SQL_SET]['bikeAUM'])){
		$query .= 'bikeAUM = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeAUM']).'", ';
	}
	
	//Bike auy
	if (isset($p[System_Properties::SQL_SET]['bikeAUY'])){
		$query .= 'bikeAUY = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeAUY']).'", ';
	}
	
	//Bike shift
	if (isset($p[System_Properties::SQL_SET]['bikeShift'])){
		$query .= 'bikeShift = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeShift']).'", ';
	}
	
	//Bike weight
	if (isset($p[System_Properties::SQL_SET]['bikeWeight'])){
		$query .= 'bikeWeight = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeWeight']).'", ';
	}
	
	//Bike cyl
	if (isset($p[System_Properties::SQL_SET]['bikeCyl'])){
		$query .= 'bikeCyl = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeCyl']).'", ';
	}
	
	//Bike cub
	if (isset($p[System_Properties::SQL_SET]['bikeCub'])){
		$query .= 'bikeCub = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeCub']).'", ';
	}
	
	//Bike useIN
	if (isset($p[System_Properties::SQL_SET]['bikeUseIn'])){
		$query .= 'bikeUseIn = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeUseIn']).'", ';
	}
	
	//Bike UseOut
	if (isset($p[System_Properties::SQL_SET]['bikeUseOut'])){
		$query .= 'bikeUseOut = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeUseOut']).'", ';
	}
	
	//Bike CO2
	if (isset($p[System_Properties::SQL_SET]['bikeCO2'])){
		$query .= 'bikeCO2 = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeCO2']).'", ';
	}
	
	//Bike EEK
	if (isset($p[System_Properties::SQL_SET]['bikeEEK'])){
		$query .= 'bikeEEK = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeEEK']).'", ';
	}
	
	//Bike state
	if (isset($p[System_Properties::SQL_SET]['bikeState'])){
		$query .= 'bikeState = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeState']).'", ';
	}
	
	//Bike cat
	if (isset($p[System_Properties::SQL_SET]['bikeCat'])){
		$query .= 'bikeCat = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeCat']).'", ';
	}
	
	//Bike fuel
	if (isset($p[System_Properties::SQL_SET]['bikeFuel'])){
		$query .= 'bikeFuel = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeFuel']).'", ';
	}
	
	//Bike Clr
	if (isset($p[System_Properties::SQL_SET]['bikeClr'])){
		$query .= 'bikeClr = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeClr']).'", ';
	}
	
	//Bike ClrMet
	if (isset($p[System_Properties::SQL_SET]['bikeClrMet']) && ($p[System_Properties::SQL_SET]['bikeClrMet'] == 1)){
		//$query .= 'bikeClrMet = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeClrMet']).'", ';
		$query .= 'bikeClrMet = "1", ';
	}else{
		$query .= 'bikeClrMet = "0", ';
	}
	
	//Bike EmissionNorm
	if (isset($p[System_Properties::SQL_SET]['bikeEmissionNorm'])){
		$query .= 'bikeEmissionNorm = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeEmissionNorm']).'", ';
	}
	
	//Bike EcologicTag
	if (isset($p[System_Properties::SQL_SET]['bikeEcologicTag'])){
		$query .= 'bikeEcologicTag = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeEcologicTag']).'", ';
	}
	
	//Bike Desc
	if (isset($p[System_Properties::SQL_SET]['bikeDesc'])){
		$query .= 'bikeDesc = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeDesc']).'", ';
	}
	
	//Bike LocPLZ
	if (isset($p[System_Properties::SQL_SET]['bikeLocPLZ'])){
		$query .= 'bikeLocPLZ = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeLocPLZ']).'", ';
	}
	
	//Bike LocOrt
	if (isset($p[System_Properties::SQL_SET]['bikeLocOrt'])){
		$query .= 'bikeLocOrt = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeLocOrt']).'", ';
	}
	
	//Bike bikeLocCountry
	if (isset($p[System_Properties::SQL_SET]['bikeLocCountry'])){
		$query .= 'bikeLocCountry = "'.$db -> escape($p[System_Properties::SQL_SET]['bikeLocCountry']).'", ';
	}
	
	//Bike userAds
	if (isset($p[System_Properties::SQL_SET]['userAds'])){
		$query .= 'userAds = "'.$db -> escape($p[System_Properties::SQL_SET]['userAds']).'", ';
	}
	
	//Bike userAdsLength
	if (isset($p[System_Properties::SQL_SET]['userAdsLength'])){
		$query .= 'userAdsLength = "'.$db -> escape($p[System_Properties::SQL_SET]['userAdsLength']).'", ';
	}
	
	//Bike userAdsLength
	if (isset($p[System_Properties::SQL_SET]['userFirm'])){
		$query .= 'userFirm = "'.$db -> escape($p[System_Properties::SQL_SET]['userFirm']).'", ';
	}
	
	//Bike userNName
	if (isset($p[System_Properties::SQL_SET]['userNName'])){
		$query .= 'userNName = "'.$db -> escape($p[System_Properties::SQL_SET]['userNName']).'", ';
	}
	
	//Bike userVName
	if (isset($p[System_Properties::SQL_SET]['userVName'])){
		$query .= 'userVName = "'.$db -> escape($p[System_Properties::SQL_SET]['userVName']).'", ';
	}
	
	//Bike userEMail
	if (isset($p[System_Properties::SQL_SET]['userEMail'])){
		$query .= 'userEMail = "'.$db -> escape($p[System_Properties::SQL_SET]['userEMail']).'", ';
	}
	
	//Bike userPLZ
	if (isset($p[System_Properties::SQL_SET]['userPLZ'])){
		$query .= 'userPLZ = "'.$db -> escape($p[System_Properties::SQL_SET]['userPLZ']).'", ';
	}
	
	//Bike userOrt
	if (isset($p[System_Properties::SQL_SET]['userOrt'])){
		$query .= 'userOrt = "'.$db -> escape($p[System_Properties::SQL_SET]['userOrt']).'", ';
	}
	
	//Bike userTel1
	if (isset($p[System_Properties::SQL_SET]['userTel1'])){
		$query .= 'userTel1 = "'.$db -> escape($p[System_Properties::SQL_SET]['userTel1']).'", ';
	}
	
	//Bike userTel2
	if (isset($p[System_Properties::SQL_SET]['userTel2'])){
		$query .= 'userTel2 = "'.$db -> escape($p[System_Properties::SQL_SET]['userTel2']).'", ';
	}
	
	//Bike userAdress
	if (isset($p[System_Properties::SQL_SET]['userAdress'])){
		$query .= 'userAdress = "'.$db -> escape($p[System_Properties::SQL_SET]['userAdress']).'", ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['erased'])){
		$query .= 'erased = "'.$db -> escape($p[System_Properties::SQL_SET]['erased']).'", ';
	}
	
	//Bike hitCounter
	if (isset($p[System_Properties::SQL_SET]['incHitCounter']) && is_numeric($p[System_Properties::SQL_SET]['incHitCounter'])){
		$query .= 'hitCounter = hitCounter + '.$db -> escape($p[System_Properties::SQL_SET]['incHitCounter']).', ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['bikeID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (bikeID = "'.$p[System_Properties::SQL_WHERE]['bikeID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['bikeBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (bikeBrandID = "'.$p[System_Properties::SQL_WHERE]['bikeBrandID'].'") ';
	}
	
	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
