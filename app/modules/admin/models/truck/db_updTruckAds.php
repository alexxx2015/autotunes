<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an truck advertisement in table TRUCK
 *********************************************************************************/
include_once('classes/DB.php');

function db_updTruckAds($p=null){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	UPDATE truck SET  ';
	
	//Truck Brand ID
	if(isset($p[System_Properties::SQL_SET]['truckBrandID'])){		
		$query .= 'truckBrandID = "'.$db -> escape($p[System_Properties::SQL_SET]['truckBrandID']).'", ';
	}
	
	//Truck Model ID
	if(isset($p[System_Properties::SQL_SET]['truckModel'])){
		$query .= 'truckModelID = "'.$db -> escape($p[System_Properties::SQL_SET]['truckModel']).'", ';
	}
	
	//Truck Model Var
	if(isset($p[System_Properties::SQL_SET]['truckModelVar'])){
		$query .= 'truckModelVar = "'.$db -> escape($p[System_Properties::SQL_SET]['truckModelVar']).'", ';
	}
	
	//Truck price
	if (isset($p[System_Properties::SQL_SET]['truckPrice'])){
		$query .= 'truckPrice = "'.$db -> escape($p[System_Properties::SQL_SET]['truckPrice']).'", ';
	}
	
	//Truck price type
	if (isset($p[System_Properties::SQL_SET]['truckPriceType'])){
		$query .= 'truckPriceType = "'.$db -> escape($p[System_Properties::SQL_SET]['truckPriceType']).'", ';
	}
	
	//Truck mwst
	if (isset($p[System_Properties::SQL_SET]['mwst'])){
		$query .= 'mwst = "'.$db -> escape($p[System_Properties::SQL_SET]['mwst']).'", ';
	}
	
	//Truck mwstSatz
	if (isset($p[System_Properties::SQL_SET]['mwstSatz'])){
		$query .= 'mwstSatz = "'.$db -> escape($p[System_Properties::SQL_SET]['mwstSatz']).'", ';
	}
	
	//Truck km
	if (isset($p[System_Properties::SQL_SET]['truckKM'])){
		$query .= 'truckKM = "'.$db -> escape($p[System_Properties::SQL_SET]['truckKM']).'", ';
	}
	
	//Truck km
	if (isset($p[System_Properties::SQL_SET]['truckKMType'])){
		$query .= 'truckKMType = "'.$db -> escape($p[System_Properties::SQL_SET]['truckKMType']).'", ';
	}
	
	//Truck power
	if (isset($p[System_Properties::SQL_SET]['truckPower'])){
		$query .= 'truckPower = "'.$db -> escape($p[System_Properties::SQL_SET]['truckPower']).'", ';
	}
	
	//Truck power type
	if (isset($p[System_Properties::SQL_SET]['truckPowerType'])){
		$query .= 'truckPowerType = "'.$db -> escape($p[System_Properties::SQL_SET]['truckPowerType']).'", ';
	}
	
	//Truck ezm
	if (isset($p[System_Properties::SQL_SET]['truckEZM'])){
		$query .= 'truckEZM = "'.$db -> escape($p[System_Properties::SQL_SET]['truckEZM']).'", ';
	}
	
	//Truck ezy
	if (isset($p[System_Properties::SQL_SET]['truckEZY'])){
		$query .= 'truckEZY = "'.$db -> escape($p[System_Properties::SQL_SET]['truckEZY']).'", ';
	}
	
	//TRUCK_HSN
	if (isset($p[System_Properties::SQL_SET]['truckHSN'])){
		$query .= 'truckHSN = "'.$db -> escape($p[System_Properties::SQL_SET]['truckHSN']).'", ';
	}
	
	//TRUCK_TSN
	if (isset($p[System_Properties::SQL_SET]['truckTSN'])){
		$query .= 'truckTSN = "'.$db -> escape($p[System_Properties::SQL_SET]['truckTSN']).'", ';
	}
	
	//TRUCK_FIN
	if (isset($p[System_Properties::SQL_SET]['truckFIN'])){
		$query .= 'truckFIN = "'.$db -> escape($p[System_Properties::SQL_SET]['truckFIN']).'", ';
	}
	
	//Truck tuvm
	if (isset($p[System_Properties::SQL_SET]['truckTUVM'])){
		$query .= 'truckTUVM = "'.$db -> escape($p[System_Properties::SQL_SET]['truckTUVM']).'", ';
	}
	
	//Truck TUVY
	if (isset($p[System_Properties::SQL_SET]['truckTUVY'])){
		$query .= 'truckTUVY = "'.$db -> escape($p[System_Properties::SQL_SET]['truckTUVY']).'", ';
	}
	
	//Truck aum
	if (isset($p[System_Properties::SQL_SET]['truckAUM'])){
		$query .= 'truckAUM = "'.$db -> escape($p[System_Properties::SQL_SET]['truckAUM']).'", ';
	}
	
	//Truck auy
	if (isset($p[System_Properties::SQL_SET]['truckAUY'])){
		$query .= 'truckAUY = "'.$db -> escape($p[System_Properties::SQL_SET]['truckAUY']).'", ';
	}
	
	//Truck shift
	if (isset($p[System_Properties::SQL_SET]['truckShift'])){
		$query .= 'truckShift = "'.$db -> escape($p[System_Properties::SQL_SET]['truckShift']).'", ';
	}
	
	//Truck weight
	if (isset($p[System_Properties::SQL_SET]['truckWeight'])){
		$query .= 'truckWeight = "'.$db -> escape($p[System_Properties::SQL_SET]['truckWeight']).'", ';
	}
	
	//Truck cyl
	if (isset($p[System_Properties::SQL_SET]['truckCyl'])){
		$query .= 'truckCyl = "'.$db -> escape($p[System_Properties::SQL_SET]['truckCyl']).'", ';
	}
	
	//Truck cub
	if (isset($p[System_Properties::SQL_SET]['truckCub'])){
		$query .= 'truckCub = "'.$db -> escape($p[System_Properties::SQL_SET]['truckCub']).'", ';
	}
	
	//Truck door
	if (isset($p[System_Properties::SQL_SET]['truckDoor'])){
		$query .= 'truckDoor = "'.$db -> escape($p[System_Properties::SQL_SET]['truckDoor']).'", ';
	}
	
	//Truck useIN
	if (isset($p[System_Properties::SQL_SET]['truckUseIn'])){
		$query .= 'truckUseIn = "'.$db -> escape($p[System_Properties::SQL_SET]['truckUseIn']).'", ';
	}
	
	//Truck UseOut
	if (isset($p[System_Properties::SQL_SET]['truckUseOut'])){
		$query .= 'truckUseOut = "'.$db -> escape($p[System_Properties::SQL_SET]['truckUseOut']).'", ';
	}
	
	//Truck CO2
	if (isset($p[System_Properties::SQL_SET]['truckCO2'])){
		$query .= 'truckCO2 = "'.$db -> escape($p[System_Properties::SQL_SET]['truckCO2']).'", ';
	}
	
	//Truck state
	if (isset($p[System_Properties::SQL_SET]['truckState'])){
		$query .= 'truckState = "'.$db -> escape($p[System_Properties::SQL_SET]['truckState']).'", ';
	}
	
	//Truck cat
	if (isset($p[System_Properties::SQL_SET]['truckCat'])){
		$query .= 'truckCat = "'.$db -> escape($p[System_Properties::SQL_SET]['truckCat']).'", ';
	}
	
	//Truck fuel
	if (isset($p[System_Properties::SQL_SET]['truckFuel'])){
		$query .= 'truckFuel = "'.$db -> escape($p[System_Properties::SQL_SET]['truckFuel']).'", ';
	}
	
	//Truck Clr
	if (isset($p[System_Properties::SQL_SET]['truckClr'])){
		$query .= 'truckClr = "'.$db -> escape($p[System_Properties::SQL_SET]['truckClr']).'", ';
	}
	
	//Truck ClrMet
	if (isset($p[System_Properties::SQL_SET]['truckClrMet'])){
		$query .= 'truckClrMet = "'.$db -> escape($p[System_Properties::SQL_SET]['truckClrMet']).'", ';
	}
	
	//Truck EmissionNorm
	if (isset($p[System_Properties::SQL_SET]['truckEmissionNorm'])){
		$query .= 'truckEmissionNorm = "'.$db -> escape($p[System_Properties::SQL_SET]['truckEmissionNorm']).'", ';
	}
	
	//Truck EcologicTag
	if (isset($p[System_Properties::SQL_SET]['truckEcologicTag'])){
		$query .= 'truckEcologicTag = "'.$db -> escape($p[System_Properties::SQL_SET]['truckEcologicTag']).'", ';
	}
	
	//Truck Klima
	if (isset($p[System_Properties::SQL_SET]['truckKlima'])){
		$query .= 'truckKlima = "'.$db -> escape($p[System_Properties::SQL_SET]['truckKlima']).'", ';
	}
	
	//Truck Desc
	if (isset($p[System_Properties::SQL_SET]['truckDesc'])){
		$query .= 'truckDesc = "'.$db -> escape($p[System_Properties::SQL_SET]['truckDesc']).'", ';
	}
	
	//Truck LocPLZ
	if (isset($p[System_Properties::SQL_SET]['truckLocPLZ'])){
		$query .= 'truckLocPLZ = "'.$db -> escape($p[System_Properties::SQL_SET]['truckLocPLZ']).'", ';
	}
	
	//Truck LocOrt
	if (isset($p[System_Properties::SQL_SET]['truckLocOrt'])){
		$query .= 'truckLocOrt = "'.$db -> escape($p[System_Properties::SQL_SET]['truckLocOrt']).'", ';
	}
	
	//Truck truckLocCountry
	if (isset($p[System_Properties::SQL_SET]['truckLocCountry'])){
		$query .= 'truckLocCountry = "'.$db -> escape($p[System_Properties::SQL_SET]['truckLocCountry']).'", ';
	}
	
	//Truck userAds
	if (isset($p[System_Properties::SQL_SET]['userAds'])){
		$query .= 'userAds = "'.$db -> escape($p[System_Properties::SQL_SET]['userAds']).'", ';
	}
	
	//Truck userAdsLength
	if (isset($p[System_Properties::SQL_SET]['userAdsLength'])){
		$query .= 'userAdsLength = "'.$db -> escape($p[System_Properties::SQL_SET]['userAdsLength']).'", ';
	}
	
	//Truck userAdsLength
	if (isset($p[System_Properties::SQL_SET]['userFirm'])){
		$query .= 'userFirm = "'.$db -> escape($p[System_Properties::SQL_SET]['userFirm']).'", ';
	}
	
	//Truck userNName
	if (isset($p[System_Properties::SQL_SET]['userNName'])){
		$query .= 'userNName = "'.$db -> escape($p[System_Properties::SQL_SET]['userNName']).'", ';
	}
	
	//Truck userVName
	if (isset($p[System_Properties::SQL_SET]['userVName'])){
		$query .= 'userVName = "'.$db -> escape($p[System_Properties::SQL_SET]['userVName']).'", ';
	}
	
	//Truck userEMail
	if (isset($p[System_Properties::SQL_SET]['userEMail'])){
		$query .= 'userEMail = "'.$db -> escape($p[System_Properties::SQL_SET]['userEMail']).'", ';
	}
	
	//Truck userPLZ
	if (isset($p[System_Properties::SQL_SET]['userPLZ'])){
		$query .= 'userPLZ = "'.$db -> escape($p[System_Properties::SQL_SET]['userPLZ']).'", ';
	}
	
	//Truck userOrt
	if (isset($p[System_Properties::SQL_SET]['userOrt'])){
		$query .= 'userOrt = "'.$db -> escape($p[System_Properties::SQL_SET]['userOrt']).'", ';
	}
	
	//Truck userTel1
	if (isset($p[System_Properties::SQL_SET]['userTel1'])){
		$query .= 'userTel1 = "'.$db -> escape($p[System_Properties::SQL_SET]['userTel1']).'", ';
	}
	
	//Truck userTel2
	if (isset($p[System_Properties::SQL_SET]['userTel2'])){
		$query .= 'userTel2 = "'.$db -> escape($p[System_Properties::SQL_SET]['userTel2']).'", ';
	}
	
	//Truck userAdress
	if (isset($p[System_Properties::SQL_SET]['userAdress'])){
		$query .= 'userAdress = "'.$db -> escape($p[System_Properties::SQL_SET]['userAdress']).'", ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['erased'])){
		$query .= 'erased = "'.$db -> escape($p[System_Properties::SQL_SET]['erased']).'", ';
	}
	
	//Truck hitCounter
	if (isset($p[System_Properties::SQL_SET]['incHitCounter']) && is_numeric($p[System_Properties::SQL_SET]['incHitCounter'])){
		$query .= 'hitCounter = hitCounter + '.$db -> escape($p[System_Properties::SQL_SET]['incHitCounter']).', ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['truckID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (truckID = "'.$p[System_Properties::SQL_WHERE]['truckID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['truckBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
		}else{
			$query .= ' AND ';
		}
		$query .= ' (truckBrandID = "'.$p[System_Properties::SQL_WHERE]['truckBrandID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['truckCat'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (truckCat = "'.$p[System_Properties::SQL_WHERE]['truckCat'].'") ';
	}
	
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
