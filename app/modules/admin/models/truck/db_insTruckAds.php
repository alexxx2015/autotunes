<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100822
 * Desc:		This function insert a truck advertisement into the database tables
 *********************************************************************************/
include_once('classes/DB.php');
include_once('classes/System_Properties.php');

function db_insTruckAds($p){
	$return = false;
	
	$db = DB::getInstance();
	
	$query1 = 'timestam, ip';
	$query2 = 'UNIX_TIMESTAMP(), "'.System_Properties::getIP().'"';	
	
	//truckBrandID
	if(isset($p['truckBrandID']) && ($p['truckBrandID'] != null) && ($p['truckBrandID'] != '')){
		$query1 .= ', truckBrandID';
		$query2 .= ', "'.$db -> escape($p['truckBrandID']).'"';
	}
	
	//truckModelID
	if(isset($p['truckModelID']) && ($p['truckModelID'] != null) && ($p['truckModelID'] != '')){
		$query1 .= ', truckModelID';
		$query2 .= ', "'.$db -> escape($p['truckModelID']).'"';
	}
	
	//truckModelVar
	if(isset($p['truckModelVar']) && ($p['truckModelVar'] != null) && ($p['truckModelVar'] != '')){
		$query1 .= ', truckModelVar';
		$query2 .= ', "'.$db -> escape($p['truckModelVar']).'"';
	}
	
	//truckPrice
	if(isset($p['truckPrice']) && ($p['truckPrice'] != null) && ($p['truckPrice'] != '')){
		$query1 .= ', truckPrice';
		$query2 .= ', "'.$db -> escape($p['truckPrice']).'"';
	}
	
	//truckPriceType
	if(isset($p['truckPriceType']) && ($p['truckPriceType'] != null) && ($p['truckPriceType'] != '')){
		$query1 .= ', truckPriceType';
		$query2 .= ', "'.$db -> escape($p['truckPriceType']).'"';
	}
	
	//truckPriceCurr
	if(isset($p['truckPriceCurr']) && ($p['truckPriceCurr'] != null) && ($p['truckPriceCurr'] != '')){
		$query1 .= ', truckPriceCurr';
		$query2 .= ', "'.$db -> escape($p['truckPriceCurr']).'"';
	}
	
	//mwst
	if(isset($p['mwst']) && ($p['mwst'] != null) && ($p['mwst'] != '')){
		$query1 .= ', mwst';
		$query2 .= ', "'.$db -> escape($p['mwst']).'"';
	}
	
	//mwstSatz
	if(isset($p['mwstSatz']) && ($p['mwstSatz'] != null) && ($p['mwstSatz'] != '')){
		$query1 .= ', mwstSatz';
		$query2 .= ', "'.$db -> escape($p['mwstSatz']).'"';
	}
	
	//truckKM
	if(isset($p['truckKM']) && ($p['truckKM'] != null) && ($p['truckKM'] != '')){
		$query1 .= ', truckKM';
		$query2 .= ', "'.$db -> escape($p['truckKM']).'"';
	}
	
	//truckKMType
	if(isset($p['truckKMType']) && ($p['truckKMType'] != null) && ($p['truckKMType'] != '')){
		$query1 .= ', truckKMType';
		$query2 .= ', "'.$db -> escape($p['truckKMType']).'"';
	}
	
	//truckPower
	if(isset($p['truckPower']) && ($p['truckPower'] != null) && ($p['truckPower'] != '')){
		$query1 .= ', truckPower';
		$query2 .= ', "'.$db -> escape($p['truckPower']).'"';
	}
	
	//truckPowerType
	if(isset($p['truckPowerType']) && ($p['truckPowerType'] != null) && ($p['truckPowerType'] != '')){
		$query1 .= ', truckPowerType';
		$query2 .= ', "'.$db -> escape($p['truckPowerType']).'"';
	}
	
	//truckEZM
	if(isset($p['truckEZM']) && ($p['truckEZM'] != null) && ($p['truckEZM'] != '')){
		$query1 .= ', truckEZM';
		$query2 .= ', "'.$db -> escape($p['truckEZM']).'"';
	}
	
	//truckEZY
	if(isset($p['truckEZY']) && ($p['truckEZY'] != null) && ($p['truckEZY'] != '')){
		$query1 .= ', truckEZY';
		$query2 .= ', "'.$db -> escape($p['truckEZY']).'"';
	}
	
	//truckHSN
	if(isset($p['truckHSN']) && ($p['truckHSN'] != null) && ($p['truckHSN'] != '')){
		$query1 .= ', truckHSN';
		$query2 .= ', "'.$db -> escape($p['truckHSN']).'"';
	}
	
	//truckTSN
	if(isset($p['truckTSN']) && ($p['truckTSN'] != null) && ($p['truckTSN'] != '')){
		$query1 .= ', truckTSN';
		$query2 .= ', "'.$db -> escape($p['truckTSN']).'"';
	}
	
	//truckFIN
	if(isset($p['truckFIN']) && ($p['truckFIN'] != null) && ($p['truckFIN'] != '')){
		$query1 .= ', truckFIN';
		$query2 .= ', "'.$db -> escape($p['truckFIN']).'"';
	}
	
	//truckTUVM
	if(isset($p['truckTUVM']) && ($p['truckTUVM'] != null) && ($p['truckTUVM'] != '')){
		$query1 .= ', truckTUVM';
		$query2 .= ', "'.$db -> escape($p['truckTUVM']).'"';
	}
	
	//truckTUVY
	if(isset($p['truckTUVY']) && ($p['truckTUVY'] != null) && ($p['truckTUVY'] != '')){
		$query1 .= ', truckTUVY';
		$query2 .= ', "'.$db -> escape($p['truckTUVY']).'"';
	}
	
	//truckAUM
	if(isset($p['truckAUM']) && ($p['truckAUM'] != null) && ($p['truckAUM'] != '')){
		$query1 .= ', truckAUM';
		$query2 .= ', "'.$db -> escape($p['truckAUM']).'"';
	}
	
	//truckAUY
	if(isset($p['truckAUY']) && ($p['truckAUY'] != null) && ($p['truckAUY'] != '')){
		$query1 .= ', truckAUY';
		$query2 .= ', "'.$db -> escape($p['truckAUY']).'"';
	}
	
	//truckShift
	if(isset($p['truckShift']) && ($p['truckShift'] != null) && ($p['truckShift'] != '')){
		$query1 .= ', truckShift';
		$query2 .= ', "'.$db -> escape($p['truckShift']).'"';
	}
	
	//truckWeight
	if(isset($p['truckWeight']) && ($p['truckWeight'] != null) && ($p['truckWeight'] != '')){
		$query1 .= ', truckWeight';
		$query2 .= ', "'.$db -> escape($p['truckWeight']).'"';
	}
	
	//truckCyl
	if(isset($p['truckCyl']) && ($p['truckCyl'] != null) && ($p['truckCyl'] != '')){
		$query1 .= ', truckCyl';
		$query2 .= ', "'.$db -> escape($p['truckCyl']).'"';
	}
	
	//truckCub
	if(isset($p['truckCub']) && ($p['truckCub'] != null) && ($p['truckCub'] != '')){
		$query1 .= ', truckCub';
		$query2 .= ', "'.$db -> escape($p['truckCub']).'"';
	}
	
	//truckUseIn
	if(isset($p['truckUseIn']) && ($p['truckUseIn'] != null) && ($p['truckUseIn'] != '')){
		$query1 .= ', truckUseIn';
		$query2 .= ', "'.$db -> escape($p['truckUseIn']).'"';
	}
	
	//truckUseOut
	if(isset($p['truckUseOut']) && ($p['truckUseOut'] != null) && ($p['truckUseOut'] != '')){
		$query1 .= ', truckUseOut';
		$query2 .= ', "'.$db -> escape($p['truckUseOut']).'"';
	}
	
	//truckCO2
	if(isset($p['truckCO2']) && ($p['truckCO2'] != null) && ($p['truckCO2'] != '')){
		$query1 .= ', truckCO2';
		$query2 .= ', "'.$db -> escape($p['truckCO2']).'"';
	}
	
	//truckState
	if(isset($p['truckState']) && ($p['truckState'] != null) && ($p['truckState'] != '')){
		$query1 .= ', truckState';
		$query2 .= ', "'.$db -> escape($p['truckState']).'"';
	}
	
	//truckCat
	if(isset($p['truckCat']) && ($p['truckCat'] != null) && ($p['truckCat'] != '')){
		$query1 .= ', truckCat';
		$query2 .= ', "'.$db -> escape($p['truckCat']).'"';
	}
	
	//truckFuel
	if(isset($p['truckFuel']) && ($p['truckFuel'] != null) && ($p['truckFuel'] != '')){
		$query1 .= ', truckFuel';
		$query2 .= ', "'.$db -> escape($p['truckFuel']).'"';
	}
	
	//truckClr
	if(isset($p['truckClr']) && ($p['truckClr'] != null) && ($p['truckClr'] != '')){
		$query1 .= ', truckClr';
		$query2 .= ', "'.$db -> escape($p['truckClr']).'"';
	}
	
	//truckClrMet
	if(isset($p['truckClrMet']) && ($p['truckClrMet'] != null) && ($p['truckClrMet'] != '')){
		$query1 .= ', truckClrMet';
		$query2 .= ', "'.$db -> escape($p['truckClrMet']).'"';
	}
	
	//truckEmissionNorm
	if(isset($p['truckEmissionNorm']) && ($p['truckEmissionNorm'] != null) && ($p['truckEmissionNorm'] != '')){
		$query1 .= ', truckEmissionNorm';
		$query2 .= ', "'.$db -> escape($p['truckEmissionNorm']).'"';
	}
	
	//truckEcologicTag
	if(isset($p['truckEcologicTag']) && ($p['truckEcologicTag'] != null) && ($p['truckEcologicTag'] != '')){
		$query1 .= ', truckEcologicTag';
		$query2 .= ', "'.$db -> escape($p['truckEcologicTag']).'"';
	}
	
	//truckKlima
	if(isset($p['truckKlima']) && ($p['truckKlima'] != null) && ($p['truckKlima'] != '')){
		$query1 .= ', truckKlima';
		$query2 .= ', "'.$db -> escape($p['truckKlima']).'"';
	}
	
	//truckDesc
	if(isset($p['truckDesc']) && ($p['truckDesc'] != null) && ($p['truckDesc'] != '')){
		$query1 .= ', truckDesc';
		$query2 .= ', "'.$db -> escape($p['truckDesc']).'"';
	}
	
	//truckLocPLZ
	if(isset($p['truckLocPLZ']) && ($p['truckLocPLZ'] != null) && ($p['truckLocPLZ'] != '')){
		$query1 .= ', truckLocPLZ';
		$query2 .= ', "'.$db -> escape($p['truckLocPLZ']).'"';
	}
	
	//truckLocOrt
	if(isset($p['truckLocOrt']) && ($p['truckLocOrt'] != null) && ($p['truckLocOrt'] != '')){
		$query1 .= ', truckLocOrt';
		$query2 .= ', "'.$db -> escape($p['truckLocOrt']).'"';
	}
	
	//truckLocCountry
	if(isset($p['truckLocCountry']) && ($p['truckLocCountry'] != null) && ($p['truckLocCountry'] != '')){
		$query1 .= ', truckLocCountry';
		$query2 .= ', "'.$db -> escape($p['truckLocCountry']).'"';
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
	$query = '	INSERT INTO truck( '.$query1.' ) VALUES ( '.$query2.' )';
		
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
