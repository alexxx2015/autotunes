<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100822
 * Desc:		This function insert a bike advertisement into the database tables
 *********************************************************************************/
include_once('classes/DB.php');
include_once('classes/System_Properties.php');

function db_insBikeAds($p){
	$return = false;
	
	$db = DB::getInstance();
	
	$query1 = 'timestam, ip';
	$query2 = 'UNIX_TIMESTAMP(), "'.System_Properties::getIP().'"';	
	
	//bikeBrandID
	if(isset($p['bikeBrandID']) && ($p['bikeBrandID'] != null) && ($p['bikeBrandID'] != '')){
		$query1 .= ', bikeBrandID';
		$query2 .= ', "'.$db -> escape($p['bikeBrandID']).'"';
	}
	
	//bikeModelID
	if(isset($p['bikeModelID']) && ($p['bikeModelID'] != null) && ($p['bikeModelID'] != '')){
		$query1 .= ', bikeModelID';
		$query2 .= ', "'.$db -> escape($p['bikeModelID']).'"';
	}
	
	//bikeModelVar
	if(isset($p['bikeModelVar']) && ($p['bikeModelVar'] != null) && ($p['bikeModelVar'] != '')){
		$query1 .= ', bikeModelVar';
		$query2 .= ', "'.$db -> escape($p['bikeModelVar']).'"';
	}
	
	//bikePrice
	if(isset($p['bikePrice']) && ($p['bikePrice'] != null) && ($p['bikePrice'] != '')){
		$query1 .= ', bikePrice';
		$query2 .= ', "'.$db -> escape($p['bikePrice']).'"';
	}
	
	//bikePriceType
	if(isset($p['bikePriceType']) && ($p['bikePriceType'] != null) && ($p['bikePriceType'] != '')){
		$query1 .= ', bikePriceType';
		$query2 .= ', "'.$db -> escape($p['bikePriceType']).'"';
	}
	
	//bikePriceCurr
	if(isset($p['bikePriceCurr']) && ($p['bikePriceCurr'] != null) && ($p['bikePriceCurr'] != '')){
		$query1 .= ', bikePriceCurr';
		$query2 .= ', "'.$db -> escape($p['bikePriceCurr']).'"';
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
	
	//bikeKM
	if(isset($p['bikeKM']) && ($p['bikeKM'] != null) && ($p['bikeKM'] != '')){
		$query1 .= ', bikeKM';
		$query2 .= ', "'.$db -> escape($p['bikeKM']).'"';
	}
	
	//bikeKMType
	if(isset($p['bikeKMType']) && ($p['bikeKMType'] != null) && ($p['bikeKMType'] != '')){
		$query1 .= ', bikeKMType';
		$query2 .= ', "'.$db -> escape($p['bikeKMType']).'"';
	}
	
	//bikePower
	if(isset($p['bikePower']) && ($p['bikePower'] != null) && ($p['bikePower'] != '')){
		$query1 .= ', bikePower';
		$query2 .= ', "'.$db -> escape($p['bikePower']).'"';
	}
	
	//bikePowerType
	if(isset($p['bikePowerType']) && ($p['bikePowerType'] != null) && ($p['bikePowerType'] != '')){
		$query1 .= ', bikePowerType';
		$query2 .= ', "'.$db -> escape($p['bikePowerType']).'"';
	}
	
	//bikeEZM
	if(isset($p['bikeEZM']) && ($p['bikeEZM'] != null) && ($p['bikeEZM'] != '')){
		$query1 .= ', bikeEZM';
		$query2 .= ', "'.$db -> escape($p['bikeEZM']).'"';
	}
	
	//bikeEZY
	if(isset($p['bikeEZY']) && ($p['bikeEZY'] != null) && ($p['bikeEZY'] != '')){
		$query1 .= ', bikeEZY';
		$query2 .= ', "'.$db -> escape($p['bikeEZY']).'"';
	}
	
	//bikeHSN
	if(isset($p['bikeHSN']) && ($p['bikeHSN'] != null) && ($p['bikeHSN'] != '')){
		$query1 .= ', bikeHSN';
		$query2 .= ', "'.$db -> escape($p['bikeHSN']).'"';
	}
	
	//bikeTSN
	if(isset($p['bikeTSN']) && ($p['bikeTSN'] != null) && ($p['bikeTSN'] != '')){
		$query1 .= ', bikeTSN';
		$query2 .= ', "'.$db -> escape($p['bikeTSN']).'"';
	}
	
	//bikeFIN
	if(isset($p['bikeFIN']) && ($p['bikeFIN'] != null) && ($p['bikeFIN'] != '')){
		$query1 .= ', bikeFIN';
		$query2 .= ', "'.$db -> escape($p['bikeFIN']).'"';
	}
	
	//bikeTUVM
	if(isset($p['bikeTUVM']) && ($p['bikeTUVM'] != null) && ($p['bikeTUVM'] != '')){
		$query1 .= ', bikeTUVM';
		$query2 .= ', "'.$db -> escape($p['bikeTUVM']).'"';
	}
	
	//bikeTUVY
	if(isset($p['bikeTUVY']) && ($p['bikeTUVY'] != null) && ($p['bikeTUVY'] != '')){
		$query1 .= ', bikeTUVY';
		$query2 .= ', "'.$db -> escape($p['bikeTUVY']).'"';
	}
	
	//bikeAUM
	if(isset($p['bikeAUM']) && ($p['bikeAUM'] != null) && ($p['bikeAUM'] != '')){
		$query1 .= ', bikeAUM';
		$query2 .= ', "'.$db -> escape($p['bikeAUM']).'"';
	}
	
	//bikeAUY
	if(isset($p['bikeAUY']) && ($p['bikeAUY'] != null) && ($p['bikeAUY'] != '')){
		$query1 .= ', bikeAUY';
		$query2 .= ', "'.$db -> escape($p['bikeAUY']).'"';
	}
	
	//bikeShift
	if(isset($p['bikeShift']) && ($p['bikeShift'] != null) && ($p['bikeShift'] != '')){
		$query1 .= ', bikeShift';
		$query2 .= ', "'.$db -> escape($p['bikeShift']).'"';
	}
	
	//bikeWeight
	if(isset($p['bikeWeight']) && ($p['bikeWeight'] != null) && ($p['bikeWeight'] != '')){
		$query1 .= ', bikeWeight';
		$query2 .= ', "'.$db -> escape($p['bikeWeight']).'"';
	}
	
	//bikeCyl
	if(isset($p['bikeCyl']) && ($p['bikeCyl'] != null) && ($p['bikeCyl'] != '')){
		$query1 .= ', bikeCyl';
		$query2 .= ', "'.$db -> escape($p['bikeCyl']).'"';
	}
	
	//bikeCub
	if(isset($p['bikeCub']) && ($p['bikeCub'] != null) && ($p['bikeCub'] != '')){
		$query1 .= ', bikeCub';
		$query2 .= ', "'.$db -> escape($p['bikeCub']).'"';
	}
	
	//bikeUseIn
	if(isset($p['bikeUseIn']) && ($p['bikeUseIn'] != null) && ($p['bikeUseIn'] != '')){
		$query1 .= ', bikeUseIn';
		$query2 .= ', "'.$db -> escape($p['bikeUseIn']).'"';
	}
	
	//bikeUseOut
	if(isset($p['bikeUseOut']) && ($p['bikeUseOut'] != null) && ($p['bikeUseOut'] != '')){
		$query1 .= ', bikeUseOut';
		$query2 .= ', "'.$db -> escape($p['bikeUseOut']).'"';
	}
	
	//bikeCO2
	if(isset($p['bikeCO2']) && ($p['bikeCO2'] != null) && ($p['bikeCO2'] != '')){
		$query1 .= ', bikeCO2';
		$query2 .= ', "'.$db -> escape($p['bikeCO2']).'"';
	}
	
	//bikeState
	if(isset($p['bikeState']) && ($p['bikeState'] != null) && ($p['bikeState'] != '')){
		$query1 .= ', bikeState';
		$query2 .= ', "'.$db -> escape($p['bikeState']).'"';
	}
	
	//bikeCat
	if(isset($p['bikeCat']) && ($p['bikeCat'] != null) && ($p['bikeCat'] != '')){
		$query1 .= ', bikeCat';
		$query2 .= ', "'.$db -> escape($p['bikeCat']).'"';
	}
	
	//bikeFuel
	if(isset($p['bikeFuel']) && ($p['bikeFuel'] != null) && ($p['bikeFuel'] != '')){
		$query1 .= ', bikeFuel';
		$query2 .= ', "'.$db -> escape($p['bikeFuel']).'"';
	}
	
	//bikeClr
	if(isset($p['bikeClr']) && ($p['bikeClr'] != null) && ($p['bikeClr'] != '')){
		$query1 .= ', bikeClr';
		$query2 .= ', "'.$db -> escape($p['bikeClr']).'"';
	}
	
	//bikeClrMet
	if(isset($p['bikeClrMet']) && ($p['bikeClrMet'] != null) && ($p['bikeClrMet'] != '')){
		$query1 .= ', bikeClrMet';
		$query2 .= ', "'.$db -> escape($p['bikeClrMet']).'"';
	}
	
	//bikeEmissionNorm
	if(isset($p['bikeEmissionNorm']) && ($p['bikeEmissionNorm'] != null) && ($p['bikeEmissionNorm'] != '')){
		$query1 .= ', bikeEmissionNorm';
		$query2 .= ', "'.$db -> escape($p['bikeEmissionNorm']).'"';
	}
	
	//bikeEcologicTag
	if(isset($p['bikeEcologicTag']) && ($p['bikeEcologicTag'] != null) && ($p['bikeEcologicTag'] != '')){
		$query1 .= ', bikeEcologicTag';
		$query2 .= ', "'.$db -> escape($p['bikeEcologicTag']).'"';
	}
	
	//bikeDesc
	if(isset($p['bikeDesc']) && ($p['bikeDesc'] != null) && ($p['bikeDesc'] != '')){
		$query1 .= ', bikeDesc';
		$query2 .= ', "'.$db -> escape($p['bikeDesc']).'"';
	}
	
	//bikeLocPLZ
	if(isset($p['bikeLocPLZ']) && ($p['bikeLocPLZ'] != null) && ($p['bikeLocPLZ'] != '')){
		$query1 .= ', bikeLocPLZ';
		$query2 .= ', "'.$db -> escape($p['bikeLocPLZ']).'"';
	}
	
	//bikeLocOrt
	if(isset($p['bikeLocOrt']) && ($p['bikeLocOrt'] != null) && ($p['bikeLocOrt'] != '')){
		$query1 .= ', bikeLocOrt';
		$query2 .= ', "'.$db -> escape($p['bikeLocOrt']).'"';
	}
	
	//bikeLocCountry
	if(isset($p['bikeLocCountry']) && ($p['bikeLocCountry'] != null) && ($p['bikeLocCountry'] != '')){
		$query1 .= ', bikeLocCountry';
		$query2 .= ', "'.$db -> escape($p['bikeLocCountry']).'"';
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
	$query = '	INSERT INTO bike( '.$query1.' ) VALUES ( '.$query2.' )';
		
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
