<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an car advertisement in table CAR
 *********************************************************************************/
include_once('classes/DB.php');

function db_updCarAds($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	UPDATE car SET  ';
	
	//Car Brand ID
	if(isset($p[System_Properties::SQL_SET]['carBrandID'])){		
		$query .= 'carBrandID = "'.$db -> escape($p[System_Properties::SQL_SET]['carBrandID']).'", ';
	}
	
	//Car Model ID
	if(isset($p[System_Properties::SQL_SET]['carModel'])){
		$query .= 'carModelID = "'.$db -> escape($p[System_Properties::SQL_SET]['carModel']).'", ';
	}
	
	//Car Model Var
	if(isset($p[System_Properties::SQL_SET]['carModelVar'])){
		$query .= 'carModelVar = "'.$db -> escape($p[System_Properties::SQL_SET]['carModelVar']).'", ';
	}
	
	//Car price
	if (isset($p[System_Properties::SQL_SET]['carPrice'])){
		$query .= 'carPrice = "'.$db -> escape($p[System_Properties::SQL_SET]['carPrice']).'", ';
	}
	
	//Car price type
	if (isset($p[System_Properties::SQL_SET]['carPriceType'])){
		$query .= 'carPriceType = "'.$db -> escape($p[System_Properties::SQL_SET]['carPriceType']).'", ';
	}
	
	//Car mwst
	if (isset($p[System_Properties::SQL_SET]['mwst'])){
		$query .= 'mwst = "'.$db -> escape($p[System_Properties::SQL_SET]['mwst']).'", ';
	}
	
	//Car mwstSatz
	if (isset($p[System_Properties::SQL_SET]['mwstSatz'])){
		$query .= 'mwstSatz = "'.$db -> escape($p[System_Properties::SQL_SET]['mwstSatz']).'", ';
	}
	
	//Car km
	if (isset($p[System_Properties::SQL_SET]['carKM'])){
		$query .= 'carKM = "'.$db -> escape($p[System_Properties::SQL_SET]['carKM']).'", ';
	}
	
	//Car km
	if (isset($p[System_Properties::SQL_SET]['carKMType'])){
		$query .= 'carKMType = "'.$db -> escape($p[System_Properties::SQL_SET]['carKMType']).'", ';
	}
	
	//Car power
	if (isset($p[System_Properties::SQL_SET]['carPower'])){
		$query .= 'carPower = "'.$db -> escape($p[System_Properties::SQL_SET]['carPower']).'", ';
	}
	
	//Car power type
	if (isset($p[System_Properties::SQL_SET]['carPowerType'])){
		$query .= 'carPowerType = "'.$db -> escape($p[System_Properties::SQL_SET]['carPowerType']).'", ';
	}
	
	//Car ezm
	if (isset($p[System_Properties::SQL_SET]['carEZM'])){
		$query .= 'carEZM = "'.$db -> escape($p[System_Properties::SQL_SET]['carEZM']).'", ';
	}
	
	//Car ezy
	if (isset($p[System_Properties::SQL_SET]['carEZY'])){
		$query .= 'carEZY = "'.$db -> escape($p[System_Properties::SQL_SET]['carEZY']).'", ';
	}
	
	//CAR_HSN
	if (isset($p[System_Properties::SQL_SET]['carHSN'])){
		$query .= 'carHSN = "'.$db -> escape($p[System_Properties::SQL_SET]['carHSN']).'", ';
	}
	
	//CAR_TSN
	if (isset($p[System_Properties::SQL_SET]['carTSN'])){
		$query .= 'carTSN = "'.$db -> escape($p[System_Properties::SQL_SET]['carTSN']).'", ';
	}
	
	//CAR_FIN
	if (isset($p[System_Properties::SQL_SET]['carFIN'])){
		$query .= 'carFIN = "'.$db -> escape($p[System_Properties::SQL_SET]['carFIN']).'", ';
	}
	
	//Car tuvm
	if (isset($p[System_Properties::SQL_SET]['carTUVM'])){
		$query .= 'carTUVM = "'.$db -> escape($p[System_Properties::SQL_SET]['carTUVM']).'", ';
	}
	
	//Car TUVY
	if (isset($p[System_Properties::SQL_SET]['carTUVY'])){
		$query .= 'carTUVY = "'.$db -> escape($p[System_Properties::SQL_SET]['carTUVY']).'", ';
	}
	
	//Car aum
	if (isset($p[System_Properties::SQL_SET]['carAUM'])){
		$query .= 'carAUM = "'.$db -> escape($p[System_Properties::SQL_SET]['carAUM']).'", ';
	}
	
	//Car auy
	if (isset($p[System_Properties::SQL_SET]['carAUY'])){
		$query .= 'carAUY = "'.$db -> escape($p[System_Properties::SQL_SET]['carAUY']).'", ';
	}
	
	//Car shift
	if (isset($p[System_Properties::SQL_SET]['carShift'])){
		$query .= 'carShift = "'.$db -> escape($p[System_Properties::SQL_SET]['carShift']).'", ';
	}
	
	//Car weight
	if (isset($p[System_Properties::SQL_SET]['carWeight'])){
		$query .= 'carWeight = "'.$db -> escape($p[System_Properties::SQL_SET]['carWeight']).'", ';
	}
	
	//Car cyl
	if (isset($p[System_Properties::SQL_SET]['carCyl'])){
		$query .= 'carCyl = "'.$db -> escape($p[System_Properties::SQL_SET]['carCyl']).'", ';
	}
	
	//Car cub
	if (isset($p[System_Properties::SQL_SET]['carCub'])){
		$query .= 'carCub = "'.$db -> escape($p[System_Properties::SQL_SET]['carCub']).'", ';
	}
	
	//Car door
	if (isset($p[System_Properties::SQL_SET]['carDoor'])){
		$query .= 'carDoor = "'.$db -> escape($p[System_Properties::SQL_SET]['carDoor']).'", ';
	}
	
	//Car useIN
	if (isset($p[System_Properties::SQL_SET]['carUseIn'])){
		$query .= 'carUseIn = "'.$db -> escape($p[System_Properties::SQL_SET]['carUseIn']).'", ';
	}
	
	//Car UseOut
	if (isset($p[System_Properties::SQL_SET]['carUseOut'])){
		$query .= 'carUseOut = "'.$db -> escape($p[System_Properties::SQL_SET]['carUseOut']).'", ';
	}
	
	//Car CO2
	if (isset($p[System_Properties::SQL_SET]['carCO2'])){
		$query .= 'carCO2 = "'.$db -> escape($p[System_Properties::SQL_SET]['carCO2']).'", ';
	}
	
	//Car EEK
	if (isset($p[System_Properties::SQL_SET]['carEEK'])){
		$query .= 'carEEK = "'.$db -> escape($p[System_Properties::SQL_SET]['carEEK']).'", ';
	}
	
	//Car state
	if (isset($p[System_Properties::SQL_SET]['carState'])){
		$query .= 'carState = "'.$db -> escape($p[System_Properties::SQL_SET]['carState']).'", ';
	}
	
	//Car cat
	if (isset($p[System_Properties::SQL_SET]['carCat'])){
		$query .= 'carCat = "'.$db -> escape($p[System_Properties::SQL_SET]['carCat']).'", ';
	}
	
	//Car fuel
	if (isset($p[System_Properties::SQL_SET]['carFuel'])){
		$query .= 'carFuel = "'.$db -> escape($p[System_Properties::SQL_SET]['carFuel']).'", ';
	}
	
	//Car Clr
	if (isset($p[System_Properties::SQL_SET]['carClr'])){
		$query .= 'carClr = "'.$db -> escape($p[System_Properties::SQL_SET]['carClr']).'", ';
	}
	
	//Car ClrMet
	if (isset($p[System_Properties::SQL_SET]['carClrMet'])){
		$query .= 'carClrMet = "'.$db -> escape($p[System_Properties::SQL_SET]['carClrMet']).'", ';
	}
	
	//Car EmissionNorm
	if (isset($p[System_Properties::SQL_SET]['carEmissionNorm'])){
		$query .= 'carEmissionNorm = "'.$db -> escape($p[System_Properties::SQL_SET]['carEmissionNorm']).'", ';
	}
	
	//Car EcologicTag
	if (isset($p[System_Properties::SQL_SET]['carEcologicTag'])){
		$query .= 'carEcologicTag = "'.$db -> escape($p[System_Properties::SQL_SET]['carEcologicTag']).'", ';
	}
	
	//Car Klima
	if (isset($p[System_Properties::SQL_SET]['carKlima'])){
		$query .= 'carKlima = "'.$db -> escape($p[System_Properties::SQL_SET]['carKlima']).'", ';
	}
	
	//Car Desc
	if (isset($p[System_Properties::SQL_SET]['carDesc'])){
		$query .= 'carDesc = "'.$db -> escape($p[System_Properties::SQL_SET]['carDesc']).'", ';
	}
	
	//Car LocPLZ
	if (isset($p[System_Properties::SQL_SET]['carLocPLZ'])){
		$query .= 'carLocPLZ = "'.$db -> escape($p[System_Properties::SQL_SET]['carLocPLZ']).'", ';
	}
	
	//Car LocOrt
	if (isset($p[System_Properties::SQL_SET]['carLocOrt'])){
		$query .= 'carLocOrt = "'.$db -> escape($p[System_Properties::SQL_SET]['carLocOrt']).'", ';
	}
	
	//Car carLocCountry
	if (isset($p[System_Properties::SQL_SET]['carLocCountry'])){
		$query .= 'carLocCountry = "'.$db -> escape($p[System_Properties::SQL_SET]['carLocCountry']).'", ';
	}
	
	//Car extLink
	if (isset($p[System_Properties::SQL_SET]['extLink'])){
		$query .= 'extLink = "'.$db -> escape($p[System_Properties::SQL_SET]['extLink']).'", ';
	}
	
	//Car userAds
	if (isset($p[System_Properties::SQL_SET]['userAds'])){
		$query .= 'userAds = "'.$db -> escape($p[System_Properties::SQL_SET]['userAds']).'", ';
	}
	
	//Car userAdsLength
	if (isset($p[System_Properties::SQL_SET]['userAdsLength'])){
		$query .= 'userAdsLength = "'.$db -> escape($p[System_Properties::SQL_SET]['userAdsLength']).'", ';
	}
	
	//Car userAdsLength
	if (isset($p[System_Properties::SQL_SET]['userFirm'])){
		$query .= 'userFirm = "'.$db -> escape($p[System_Properties::SQL_SET]['userFirm']).'", ';
	}
	
	//Car userNName
	if (isset($p[System_Properties::SQL_SET]['userNName'])){
		$query .= 'userNName = "'.$db -> escape($p[System_Properties::SQL_SET]['userNName']).'", ';
	}
	
	//Car userVName
	if (isset($p[System_Properties::SQL_SET]['userVName'])){
		$query .= 'userVName = "'.$db -> escape($p[System_Properties::SQL_SET]['userVName']).'", ';
	}
	
	//Car userEMail
	if (isset($p[System_Properties::SQL_SET]['userEMail'])){
		$query .= 'userEMail = "'.$db -> escape($p[System_Properties::SQL_SET]['userEMail']).'", ';
	}
	
	//Car userPLZ
	if (isset($p[System_Properties::SQL_SET]['userPLZ'])){
		$query .= 'userPLZ = "'.$db -> escape($p[System_Properties::SQL_SET]['userPLZ']).'", ';
	}
	
	//Car userOrt
	if (isset($p[System_Properties::SQL_SET]['userOrt'])){
		$query .= 'userOrt = "'.$db -> escape($p[System_Properties::SQL_SET]['userOrt']).'", ';
	}
	
	//Car userTel1
	if (isset($p[System_Properties::SQL_SET]['userTel1'])){
		$query .= 'userTel1 = "'.$db -> escape($p[System_Properties::SQL_SET]['userTel1']).'", ';
	}
	
	//Car userTel2
	if (isset($p[System_Properties::SQL_SET]['userTel2'])){
		$query .= 'userTel2 = "'.$db -> escape($p[System_Properties::SQL_SET]['userTel2']).'", ';
	}
	
	//Car userAdress
	if (isset($p[System_Properties::SQL_SET]['userAdress'])){
		$query .= 'userAdress = "'.$db -> escape($p[System_Properties::SQL_SET]['userAdress']).'", ';
	}
	
	//timestam
	if (isset($p[System_Properties::SQL_SET]['timestam'])){
		$query .= 'timestam = UNIX_TIMESTAMP(), ';
	}
	
	if (isset($p[System_Properties::SQL_SET]['erased'])){
		$query .= 'erased = "'.$db -> escape($p[System_Properties::SQL_SET]['erased']).'", ';
	}
	
	//Car hitCounter
	if (isset($p[System_Properties::SQL_SET]['incHitCounter']) && is_numeric($p[System_Properties::SQL_SET]['incHitCounter'])){
		$query .= 'hitCounter = hitCounter + '.$db -> escape($p[System_Properties::SQL_SET]['incHitCounter']).', ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['carID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (carID = "'.$p[System_Properties::SQL_WHERE]['carID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['carBrandID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (carBrandID = "'.$p[System_Properties::SQL_WHERE]['carBrandID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['carCat'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (carCat = "'.$p[System_Properties::SQL_WHERE]['carCat'].'") ';
	}
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
