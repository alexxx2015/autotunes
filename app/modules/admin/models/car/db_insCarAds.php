<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100822
 * Desc:		This function insert a car advertisement into the database tables
 *********************************************************************************/
include_once('classes/DB.php');
include_once('classes/System_Properties.php');

function db_insCarAds($p){
	$return = false;
	
	$db = DB::getInstance();
	
	$query1 = 'timestam, ip';
	$query2 = 'UNIX_TIMESTAMP(), "'.System_Properties::getIP().'"';
	
	//carBrandID
	if(isset($p['carBrandID']) && ($p['carBrandID'] != null) && ($p['carBrandID'] != '')){
	  $query1 .= ', carBrandID';
	  $query2 .= ', "'.$db -> escape($p['carBrandID']).'"';
	}
	
	//carModelID
	if(isset($p['carModelID']) && ($p['carModelID'] != null) && ($p['carModelID'] != '')){
	  $query1 .= ', carModelID';
	  $query2 .= ', "'.$db -> escape($p['carModelID']).'"';
	}
	
	//carModelVar
	if(isset($p['carModelVar']) && ($p['carModelVar'] != null) && ($p['carModelVar'] != '')){
	  $query1 .= ', carModelVar';
	  $query2 .= ', "'.$db -> escape($p['carModelVar']).'"';
	}
	
	//carPrice
	if(isset($p['carPrice']) && ($p['carPrice'] != null) && ($p['carPrice'] != '')){
	  $query1 .= ', carPrice';
	  $query2 .= ', "'.$db -> escape($p['carPrice']).'"';
	}
	
	//carPriceType
	if(isset($p['carPriceType'])){
	  $query1 .= ', carPriceType';
	  $query2 .= ', "'.$db -> escape($p['carPriceType']).'"';
	}
	
	//carPriceCurr
	if(isset($p['carPriceCurr'])){
	  $query1 .= ', carPriceCurr';
	  $query2 .= ', "'.$db -> escape($p['carPriceCurr']).'"';
	}
	
	//mwst
	if(isset($p['mwst'])){
	  $query1 .= ', mwst';
	  $query2 .= ', "'.$db -> escape($p['mwst']).'"';
	}
	
	//mwstSatz
	if(isset($p['mwstSatz'])){
	  $query1 .= ', mwstSatz';
	  $query2 .= ', "'.$db -> escape($p['mwstSatz']).'"';
	}
	
	//carKM
	if(isset($p['carKM']) && ($p['carKM'] != null) && ($p['carKM'] != '')){
	  $query1 .= ', carKM';
	  $query2 .= ', "'.$db -> escape($p['carKM']).'"';
	}
	
	//carKMType
	if(isset($p['carKMType']) && ($p['carKMType'] != null) && ($p['carKMType'] != '')){
	  $query1 .= ', carKMType';
	  $query2 .= ', "'.$db -> escape($p['carKMType']).'"';
	}
	
	//carPower
	if(isset($p['carPower']) && ($p['carPower'] != null) && ($p['carPower'] != '')){
	  $query1 .= ', carPower';
	  $query2 .= ', "'.$db -> escape($p['carPower']).'"';
	}
	
	//carPower
	if(isset($p['carPowerType']) && ($p['carPowerType'] != null) && ($p['carPowerType'] != '')){
	  $query1 .= ', carPowerType';
	  $query2 .= ', "'.$db -> escape($p['carPowerType']).'"';
	}
	
	//carEZM
	if(isset($p['carEZM']) && ($p['carEZM'] != null) && ($p['carEZM'] != '')){
	  $query1 .= ', carEZM';
	  $query2 .= ', "'.$db -> escape($p['carEZM']).'"';
	}
	
	//carEZY
	if(isset($p['carEZY']) && ($p['carEZY'] != null) && ($p['carEZY'] != '')){
	  $query1 .= ', carEZY';
	  $query2 .= ', "'.$db -> escape($p['carEZY']).'"';
	}
	
	//carHSN
	if(isset($p['carHSN']) && ($p['carHSN'] != null) && ($p['carHSN'] != '')){
	  $query1 .= ', carHSN';
	  $query2 .= ', "'.$db -> escape($p['carHSN']).'"';
	}
	
	//carTSN
	if(isset($p['carTSN']) && ($p['carTSN'] != null) && ($p['carTSN'] != '')){
	  $query1 .= ', carTSN';
	  $query2 .= ', "'.$db -> escape($p['carTSN']).'"';
	}
	
	//carFIN
	if(isset($p['carFIN']) && ($p['carFIN'] != null) && ($p['carFIN'] != '')){
	  $query1 .= ', carFIN';
	  $query2 .= ', "'.$db -> escape($p['carFIN']).'"';
	}
	
	//carTUVM
	if(isset($p['carTUVM']) && ($p['carTUVM'] != null) && ($p['carTUVM'] != '')){
	  $query1 .= ', carTUVM';
	  $query2 .= ', "'.$db -> escape($p['carTUVM']).'"';
	}
	
	//carTUVY
	if(isset($p['carTUVY']) && ($p['carTUVY'] != null) && ($p['carTUVY'] != '')){
	  $query1 .= ', carTUVY';
	  $query2 .= ', "'.$db -> escape($p['carTUVY']).'"';
	}
	
	//carAUM
	if(isset($p['carAUM']) && ($p['carAUM'] != null) && ($p['carAUM'] != '')){
	  $query1 .= ', carAUM';
	  $query2 .= ', "'.$db -> escape($p['carAUM']).'"';
	}
	
	//carAUY
	if(isset($p['carAUY']) && ($p['carAUY'] != null) && ($p['carAUY'] != '')){
	  $query1 .= ', carAUY';
	  $query2 .= ', "'.$db -> escape($p['carAUY']).'"';
	}
	
	//carShift
	if(isset($p['carShift']) && ($p['carShift'] != null) && ($p['carShift'] != '')){
	  $query1 .= ', carShift';
	  $query2 .= ', "'.$db -> escape($p['carShift']).'"';
	}
	
	//carWeight
	if(isset($p['carWeight']) && ($p['carWeight'] != null) && ($p['carWeight'] != '')){
	  $query1 .= ', carWeight';
	  $query2 .= ', "'.$db -> escape($p['carWeight']).'"';
	}
	
	//carCyl
	if(isset($p['carCyl']) && ($p['carCyl'] != null) && ($p['carCyl'] != '')){
	  $query1 .= ', carCyl';
	  $query2 .= ', "'.$db -> escape($p['carCyl']).'"';
	}
	
	//carCub
	if(isset($p['carCub']) && ($p['carCub'] != null) && ($p['carCub'] != '')){
	  $query1 .= ', carCub';
	  $query2 .= ', "'.$db -> escape($p['carCub']).'"';
	}
	
	//carDoor
	if(isset($p['carDoor']) && ($p['carDoor'] != null) && ($p['carDoor'] != '')){
	  $query1 .= ', carDoor';
	  $query2 .= ', "'.$db -> escape($p['carDoor']).'"';
	}
	
	//carUseIn
	if(isset($p['carUseIn']) && ($p['carUseIn'] != null) && ($p['carUseIn'] != '')){
	  $query1 .= ', carUseIn';
	  $query2 .= ', "'.$db -> escape($p['carUseIn']).'"';
	}
	
	//carUseOut
	if(isset($p['carUseOut']) && ($p['carUseOut'] != null) && ($p['carUseOut'] != '')){
	  $query1 .= ', carUseOut';
	  $query2 .= ', "'.$db -> escape($p['carUseOut']).'"';
	}
	
	//carCO2
	if(isset($p['carCO2']) && ($p['carCO2'] != null) && ($p['carCO2'] != '')){
	  $query1 .= ', carCO2';
	  $query2 .= ', "'.$db -> escape($p['carCO2']).'"';
	}
	
	//carEEK
	if(isset($p['carEEK']) && ($p['carEEK'] != null) && ($p['carEEK'] != '')){
	  $query1 .= ', carEEK';
	  $query2 .= ', "'.$db -> escape($p['carEEK']).'"';
	}
	
	//carState
	if(isset($p['carState']) && ($p['carState'] != null) && ($p['carState'] != '')){
	  $query1 .= ', carState';
	  $query2 .= ', "'.$db -> escape($p['carState']).'"';
	}
	
	//carCat
	if(isset($p['carCat']) && ($p['carCat'] != null) && ($p['carCat'] != '')){
	  $query1 .= ', carCat';
	  $query2 .= ', "'.$db -> escape($p['carCat']).'"';
	}
	
	//carFuel
	if(isset($p['carFuel']) && ($p['carFuel'] != null) && ($p['carFuel'] != '')){
	  $query1 .= ', carFuel';
	  $query2 .= ', "'.$db -> escape($p['carFuel']).'"';
	}
	
	//carClr
	if(isset($p['carClr']) && ($p['carClr'] != null) && ($p['carClr'] != '')){
	  $query1 .= ', carClr';
	  $query2 .= ', "'.$db -> escape($p['carClr']).'"';
	}
	
	//carClrMet
	if(isset($p['carClrMet']) && ($p['carClrMet'] != null) && ($p['carClrMet'] != '')){
	  $query1 .= ', carClrMet';
	  $query2 .= ', "'.$db -> escape($p['carClrMet']).'"';
	}
	
	//carEmissionNorm
	if(isset($p['carEmissionNorm']) && ($p['carEmissionNorm'] != null) && ($p['carEmissionNorm'] != '')){
	  $query1 .= ', carEmissionNorm';
	  $query2 .= ', "'.$db -> escape($p['carEmissionNorm']).'"';
	}
	
	//carEcologicTag
	if(isset($p['carEcologicTag']) && ($p['carEcologicTag'] != null) && ($p['carEcologicTag'] != '')){
	  $query1 .= ', carEcologicTag';
	  $query2 .= ', "'.$db -> escape($p['carEcologicTag']).'"';
	}
	
	//carKlima
	if(isset($p['carKlima']) && ($p['carKlima'] != null) && ($p['carKlima'] != '')){
	  $query1 .= ', carKlima';
	  $query2 .= ', "'.$db -> escape($p['carKlima']).'"';
	}
	
	//carDesc
	if(isset($p['carDesc']) && ($p['carDesc'] != null) && ($p['carDesc'] != '')){
	  $query1 .= ', carDesc';
	  $query2 .= ', "'.$db -> escape($p['carDesc']).'"';
	}
	
	//carLocPLZ
	if(isset($p['carLocPLZ']) && ($p['carLocPLZ'] != null) && ($p['carLocPLZ'] != '')){
	  $query1 .= ', carLocPLZ';
	  $query2 .= ', "'.$db -> escape($p['carLocPLZ']).'"';
	}
	
	//carLocOrt
	if(isset($p['carLocOrt']) && ($p['carLocOrt'] != null) && ($p['carLocOrt'] != '')){
	  $query1 .= ', carLocOrt';
	  $query2 .= ', "'.$db -> escape($p['carLocOrt']).'"';
	}
	
	//carLocCountry
	if(isset($p['carLocCountry']) && ($p['carLocCountry'] != null) && ($p['carLocCountry'] != '')){
	  $query1 .= ', carLocCountry';
	  $query2 .= ', "'.$db -> escape($p['carLocCountry']).'"';
	}
	
	//extLink
	if(isset($p['extLink']) && ($p['extLink'] != null) && ($p['extLink'] != '')){
	  $query1 .= ', extLink';
	  $query2 .= ', "'.$db -> escape($p['extLink']).'"';
	}
	
	//userID
	if(isset($p['userID']) && ($p['userID'] != null) && ($p['userID'] != '')){
	  $query1 .= ', userID';
	  $query2 .= ', "'.$db -> escape($p['userID']).'"';
	}
	
	//userAds
	if(isset($p['userAds']) && ($p['userAds'] != null) && ($p['userAds'] != '')){
	  $query1 .= ', userAds';
	  $query2 .= ', "'.$db -> escape($p['userAds']).'"';
	}
	
	//userAdsLength
	if(isset($p['userAdsLength']) && ($p['userAdsLength'] != null) && ($p['userAdsLength'] != '')){
	  $query1 .= ', userAdsLength';
	  $query2 .= ', "'.$db -> escape($p['userAdsLength']).'"';
	}
	
	//userFirm
	if(isset($p['userFirm']) && ($p['userFirm'] != null) && ($p['userFirm'] != '')){
	  $query1 .= ', userFirm';
	  $query2 .= ', "'.$db -> escape($p['userFirm']).'"';
	}
	
	//userNName
	if(isset($p['userNName']) && ($p['userNName'] != null) && ($p['userNName'] != '')){
	  $query1 .= ', userNName';
	  $query2 .= ', "'.$db -> escape($p['userNName']).'"';
	}
	
	//userVName
	if(isset($p['userVName']) && ($p['userVName'] != null) && ($p['userVName'] != '')){
	  $query1 .= ', userVName';
	  $query2 .= ', "'.$db -> escape($p['userVName']).'"';
	}
	
	//userEMail
	if(isset($p['userEMail']) && ($p['userEMail'] != null) && ($p['userEMail'] != '')){
	  $query1 .= ', userEMail';
	  $query2 .= ', "'.$db -> escape($p['userEMail']).'"';
	}
	
	//userPLZ
	if(isset($p['userPLZ']) && ($p['userPLZ'] != null) && ($p['userPLZ'] != '')){
	  $query1 .= ', userPLZ';
	  $query2 .= ', "'.$db -> escape($p['userPLZ']).'"';
	}
	
	//userOrt
	if(isset($p['userOrt']) && ($p['userOrt'] != null) && ($p['userOrt'] != '')){
	  $query1 .= ', userOrt';
	  $query2 .= ', "'.$db -> escape($p['userOrt']).'"';
	}
	
	//userTel1
	if(isset($p['userTel1']) && ($p['userTel1'] != null) && ($p['userTel1'] != '')){
	  $query1 .= ', userTel1';
	  $query2 .= ', "'.$db -> escape($p['userTel1']).'"';
	}
	
	//userTel2
	if(isset($p['userTel2']) && ($p['userTel2'] != null) && ($p['userTel2'] != '')){
	  $query1 .= ', userTel2';
	  $query2 .= ', "'.$db -> escape($p['userTel2']).'"';
	}
	
	//userAdress
	if(isset($p['userAdress']) && ($p['userAdress'] != null) && ($p['userAdress'] != '')){
	  $query1 .= ', userAdress';
	  $query2 .= ', "'.$db -> escape($p['userAdress']).'"';
	}
	
	//Build complete query
	$query = '	INSERT INTO car( '.$query1.' ) VALUES ( '.$query2.' )';
		
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}

	/*
function db_insCarAds($p){
	$return = false;
	
	$db = DB::getInstance();
	
	$query = '	INSERT INTO car (	carBrandID, 
									carModelID,
									carModelVar,
									carPrice,
									carPriceType,
									carPriceCurr,
									carKM,
									carKMType,
									carPower,
									carPowerType,
									carEZM,
									carEZY,
									carHSN,
									carTSN,
									carFIN,
									carTUVM,
									carTUVY,
									carAUM,
									carAUY,
									carShift,
									carWeight,
									carCyl,
									carCub,
									carDoor,
									carUseIn,
									carUseOut,
									carCO2,
									carState,
									carCat,
									carFuel,
									carClr,
									carClrMet,
									carEmissionNorm,
									carEcologicTag,
									carKlima,
									carDesc,
									carLocPLZ,
									carLocOrt,
									carLocCountry,
									userID,
									userAds,
									userAdsLength,
									userFirm,
									userNName,
									userVName,
									userEMail,
									userPLZ,
									userOrt,
									userTel1,
									userTel2,
									userAdress,
									timestam,
									ip) VALUES(';
	
	$query .= '	"'.$db -> escape($p['carBrand']).'",
				"'.$db -> escape($p['carModel']).'",
				"'.$db -> escape($p['carModelVar']).'",
				"'.$db -> escape($p['carPrice']).'",
				"'.$db -> escape($p['carPriceType']).'",
				"'.$db -> escape($p['carPriceCurr']).'",
				"'.$db -> escape($p['carKM']).'",
				"'.$db -> escape($p['carKMType']).'",
				"'.$db -> escape($p['carPower']).'",
				"'.$db -> escape($p['carPowerType']).'",
				"'.$db -> escape($p['carEZM']).'",
				"'.$db -> escape($p['carEZY']).'",
				"'.$db -> escape($p['carHSN']).'",
				"'.$db -> escape($p['carTSN']).'",
				"'.$db -> escape($p['carFIN']).'",
				"'.$db -> escape($p['carTUVM']).'",
				"'.$db -> escape($p['carTUVY']).'",
				"'.$db -> escape($p['carAUM']).'",
				"'.$db -> escape($p['carAUY']).'",
				"'.$db -> escape($p['carShift']).'",
				"'.$db -> escape($p['carWeight']).'",
				"'.$db -> escape($p['carCyl']).'",
				"'.$db -> escape($p['carCub']).'",
				"'.$db -> escape($p['carDoor']).'",
				"'.$db -> escape($p['carUseIn']).'",
				"'.$db -> escape($p['carUseOut']).'",
				"'.$db -> escape($p['carCO2']).'",
				"'.$db -> escape($p['carState']).'",
				"'.$db -> escape($p['carCat']).'",
				"'.$db -> escape($p['carFuel']).'",
				"'.$db -> escape($p['carClr']).'",
				"'.$db -> escape($p['carClrMet']).'",					
				"'.$db -> escape($p['carEmissionNorm']).'",
				"'.$db -> escape($p['carEcologicTag']).'",
				"'.$db -> escape($p['carKlima']).'",
				"'.$db -> escape($p['carDesc']).'",
				"'.$db -> escape($p['carLocPLZ']).'",
				"'.$db -> escape($p['carLocOrt']).'",
				"'.$db -> escape($p['carLocCountry']).'",
				"'.$db -> escape($p['userID']).'",
				"'.$db -> escape($p['userAds']).'",
				"'.$db -> escape($p['userAdsLength']).'",
				"'.$db -> escape($p['userFirm']).'",
				"'.$db -> escape($p['userNName']).'",
				"'.$db -> escape($p['userVName']).'",
				"'.$db -> escape($p['userEMail']).'",
				"'.$db -> escape($p['userPLZ']).'",
				"'.$db -> escape($p['userOrt']).'",
				"'.$db -> escape($p['userTel1']).'",
				"'.$db -> escape($p['userTel2']).'",
				"'.$db -> escape($p['userAdress']).'",
				UNIX_TIMESTAMP(), "'.System_Properties::getIP().'")';
	
	$return = $db->execQuery(array('q'=>$query));
	if(($return != false) && isset($p['carExtDB']) && is_array($p['carExtDB'])){
		$carID = $return;
		if($carID != false){
			foreach ($p['carExtDB'] as $carExt){
				$query = '	INSERT INTO carExt (carID, vextID)
							VALUES('.$carID.','.$db -> escape($carExt['vextID']).')';
				$db -> execQuery(array('q'=>$query, 'connect' => true));
			}
		}	
		$return = $carID;	
	}
	$query1 = '';
	foreach ($db -> escape($p as $key=>$value) {
		$query1 .= '"\'.$db -> escape($p[\''.$key.'\0.\'",<br>';
	}
	return $return;
}
*/
?>
