<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110619
 * Desc:		This is an abstract class of the datex functionaliy
 *********************************************************************************/
include_once('classes/INTF_DATEX.php');
include_once('Zend/Session.php');

include_once('default/views/filters/FilterMySQLInt.php');
include_once('default/views/filters/FilterStringXX.php');
include_once('default/views/filters/FilterIsEmptyString.php');
include_once('default/views/filters/ImageFilter.php');

include_once('default/models/car/db_insCarAds.php');
include_once('default/models/car/db_selCarAd.php');
include_once('default/models/car/db_updCarAds.php');
include_once('default/models/car/db_selCarBrand.php');
include_once('default/models/car/db_selCarModel.php');

include_once('default/models/bike/db_selBikeAd.php');

include_once('default/models/default/db_selVPic.php');
include_once('default/models/default/db_delVPic.php');
include_once('default/models/default/db_insVPic.php');

include_once('default/models/car/db_selCarExt.php');
include_once('default/models/car/db_delCar2Ext.php');
include_once('default/models/car/db_insCar2Ext.php');

include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');

abstract class CL_DATEX_ABSTRACT implements INTF_DATEX{
	protected $userNS;
	protected $lang;	
	
	protected $carCat;
	protected $bikeCat;
	protected $truckCat;
	
	protected $userData;
	
	protected $docRoot;
	
	public function __construct($p = null){
		//set user namespace		
		$this -> userNS = new Zend_Session_Namespace(System_Properties::USER_NS);
		
		//set language
		if (is_array($p) && isset($p['LANG'])){
			$this -> lang = $p['LANG'];
		}
		
		$this -> carCat = db_selCarCat();
		$this -> bikeCat = db_selBikeCat();
		$this -> truckCat = db_selTruckCat();
	}
		
	/**
	 * This function filter a car advertisement
	 * @param $p:	this variable contains the parameter of a car advertisement
	 */
	protected function filterCarData($p){	
		//print_r($p);echo '<br><br>';
		$lang = $this -> lang;
		
		include_once ('default/models/car/db_selCarBrand.php');
		include_once ('default/models/car/db_selCarModel.php');
		//Process only if cins is pressed
		include_once('default/views/filters/FilterMySQLInt.php');
		include_once('default/views/filters/FilterMySQLMInt.php');
		include_once('default/views/filters/FilterMonth.php');
		include_once('default/views/filters/FilterYear.php');
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterString20.php');
		
		$fInt = new FilterMySQLInt();
		$fMInt = new FilterMySQLMInt();
		$fMonth = new FilterMonth();
		$fYear = new FilterYear();
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();
		
		//Check carBrand
		if (!isset($p['carBrand'])){
			$p['error'] = $lang['ERR_2'];
		}
		elseif (($carBrand = db_selCarBrand(array('carBrandID'=>$p['carBrand']))) == false){
			$p['error'] = $lang['ERR_2'];
		}
		/*Check model if necessary
		else if (isset($p['carModel']) && ($p['carModel'] != -1) 
				&& (db_selCarModel(array('carBrandID' => $p['carBrand'], 'carModelID' => $p['carModel'])) == false)){
					echo "KK";
			$p['error'] = $lang['ERR_2'];			
		}
		*/
		//Check carPrice
		else if (!isset($p['carPrice']) || ($fInt -> isValid($p['carPrice']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check carPower
		else if (!isset($p['carPower']) || ($fMInt -> isValid($p['carPower']) == false)){
			$p['error'] = $lang['ERR_2'];				
		}
		//Check carKM
		else if (!isset($p['carKM']) || ($fMInt -> isValid($p['carKM']) === false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check carEZ
		else if ((!isset($p['carEZM']) || !isset($p['carEZY'])
				|| ($fMonth -> isValid($p['carEZM']) == false)
				|| ($fYear -> isValid($p['carEZY']) == false)) && ($p['carState'] != '0')){
				$p['error'] = $lang['ERR_2'];
		}
		//Check userNName
		else if ( !isset($p['userNName']) || ($fString100 -> isValid($p['userNName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userVName
		else if ( !isset($p['userVName']) || ($fString100 -> isValid($p['userVName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userEmail
		else if ( !isset($p['userEMail']) || $fEMail->filter($p['userEMail']) == false){
			$p['error'] = $lang['ERR_2'];
		}/*
		//Check carLocPLZ
		else if ( !isset($p['carLocPLZ']) || $fString20->filter($p['carLocPLZ']) == false){
			$p['error'] = $lang['ERR_2'];
		}*/
		else{			
			//Include and instantiate diverse filter
			include_once('default/views/filters/FilterMySQLSInt.php');
			include_once('default/views/filters/FilterMySQLTInt.php');
			include_once('default/views/filters/FilterString50.php');
			include_once('default/views/filters/FilterString1000.php');
			$fSInt = new FilterMySQLSInt();
			$fTInt = new FilterMySQLTInt();
			$fString50 = new FilterString50();
			$fString1000 = new FilterString1000();
			
			//Check Model
			$p['carModelTxt'] = null;
			if (!isset($p['carModel']) || ($p['carModel'] == -1)){
				$p['carModel'] = -1;
			}			
			else{				
				$carModel = db_selCarModel(array(	'carBrandID' => $carBrand[0]['carBrandID'],
													'carModelID' => $p['carModel'])
											);
				if($carModel != false){
					$p['carModelTxt'] = $carModel[0]['carModelName'];
				}
			}
			
			$p['carBrandTxt'] = $carBrand[0]['brandName'];
			
			if(!isset($p['carPriceType']) || ($p['carPriceType'] == null)){
				$p['carPriceType'] = 0;
			}else{
				$p['carPriceType'] = $fTInt->filter($p['carPriceType']);
				if (!isset($lang['TXT_70'][$p['carPriceType']])){
					$p['carPriceType'] = 0;
				}
			}
			
			if (!isset($p['carPriceCurr']) || ($p['carPriceCurr'] == null)){
				$p['carPriceCurr'] = 0;
			}else{
				$p['carPriceCurr'] = $fTInt->filter($p['carPriceCurr']);
				if (!isset($lang['TXT_74'][$p['carPriceCurr']])){
					$p['carPriceCurr'] = 0;
				}
			}
			
			//MwSt
			if(isset($p['mwst'])){
				$p['mwst'] = 1;
			}else{
				$p['mwst'] = 0;
			}
			if(isset($p['mwstSatz']) && isset($lang['V_MWST']) && is_array($lang['V_MWST']) && isset($p['mwst']) && ($p['mwst'] == 1)){
				$p['mwstSatz'] = str_replace(',', '.', $p['mwstSatz']);
				if(!in_array($p['mwstSatz'], $lang['V_MWST']) && ($p['mwst'] === '0')){
					$p['mwstSatz'] = 19;
				}				
			}else{
				$p['mwstSatz'] = '-1';
			}
			
			if(!isset($p['carPowerType']) || ($p['carPowerType'] == null)){
				$p['carPowerType'] = 0;
			}else{
				$p['carPowerType'] = $fMInt->filter($p['carPowerType']);
				if (!isset($lang['TXT_72'][$p['carPowerType']])){
					$p['carPowerType'] = 0;
				}
			}
			
			if(!isset($p['carKMType']) || ($p['carKMType'] == null)){
				$p['carKMType'] = 0;
			}else{
				$p['carKMType'] = $fTInt->filter($p['carKMType']);
				if (!isset($lang['TXT_75'][$p['carKMType']])){
					$p['carKMType'] = 0;
				}
			}
				
			if(!isset($p['carTUVM']) || ($p['carTUVM'] == null)){
				$p['carTUVM'] = 1;
			}else{
				$p['carTUVM'] = $fMonth->filter($p['carTUVM']);		
			}		
			if(!isset($p['carTUVY']) || ($p['carTUVY'] == null)){
				$p['carTUVY'] = date('Y');
			}else{					
				$p['carTUVY'] = $fYear->filter($p['carTUVY']);
			}
					
			if(!isset($p['carAUM']) || ($p['carAUM'] == null)){
				$p['carAUM'] = 1;
			}else{
				$p['carAUM'] = $fMonth->filter($p['carAUM']);
			}
			if(!isset($p['carAUY']) || ($p['carAUY'] == null)){
				$p['carAUY'] = date('Y');
			}else{
				$p['carAUY'] = $fYear->filter($p['carAUY']);
			}
			
			if(!isset($p['carShift']) || ($p['carShift'] == null)){
				$p['carShift'] = -1;
			}else{
				$p['carShift'] = $fTInt->filter($p['carShift']);
			}
			
			if(!isset($p['carWeight']) || ($p['carWeight'] == null)){
				$p['carWeight'] = 0;
			}else{
				$p['carWeight'] = $fMInt->filter($p['carWeight']);
			}
			
			if(!isset($p['carCyl']) || ($p['carCyl'] == null)){
				$p['carCyl'] = 0;
			}else{
				$p['carCyl'] = $fTInt->filter($p['carCyl']);
			}
			
			if(!isset($p['carCub']) || ($p['carCub'] == null)){
				$p['carCub'] = 0;
			}else{
				$p['carCub'] = $fSInt->filter($p['carCub']);
			}
			
			if(!isset($p['carDoor']) || ($p['carDoor'] === null)){
				$p['carDoor'] = -1;
			}else{
				$p['carDoor'] = $fTInt->filter($p['carDoor']);
			}
			
			if (isset($p['carUseIn'])){
				$p['carUseIn'] = $fString50->filter($p['carUseIn']);
			}else{
				$p['carUseIn'] = '';
			}
			
			if (isset($p['carUseOut'])){
				$p['carUseOut'] = $fString50->filter($p['carUseOut']);
			}else{
				$p['carUseOut'] = '';
			}
			
			if (isset($p['carCO2'])){
				$p['carCO2'] = $fString50->filter($p['carCO2']);
			}else{
				$p['carCO2'] = '';
			}
			
			if(!isset($p['carState']) || ($p['carState'] == null)){
				$p['carState'] = -1;
			}else{
				$p['carState'] = $fTInt->filter($p['carState']);
			}
			
			if(!isset($p['carCat']) || ($p['carCat'] == null)){
				$p['carCat'] = -1;
			}else{
				$p['carCat'] = $fTInt->filter($p['carCat']);
			}
			
			if(!isset($p['carFuel']) || ($p['carFuel'] == null)){
				$p['carFuel'] = -1;
			}else{
				$p['carFuel'] = $fTInt->filter($p['carFuel']);
			}
			
			if(!isset($p['carClr']) || ($p['carClr'] == null)){
				$p['carClr'] = -1;
			}else{
				$p['carClr'] = $fTInt->filter($p['carClr']);
			}
			if(isset($p['carClrMet'])){
				$p['carClrMet'] = '1';
			}
			else{
				$p['carClrMet'] = '0';
			}
			
			if(!isset($p['carEmissionNorm']) || ($p['carEmissionNorm'] == null)){
				$p['carEmissionNorm'] = -1;
			}else{
				$p['carEmissionNorm'] = $fTInt->filter($p['carEmissionNorm']);
			}
			
			if(!isset($p['carEcologicTag']) || ($p['carEcologicTag'] == null)){
				$p['carEcologicTag'] = -1;
			}else{
				$p['carEcologicTag'] = $fTInt->isValid($p['carEcologicTag']);
			}
			
			if(!isset($p['carKlima']) || ($p['carKlima'] == null)){
				$p['carKlima'] = -1;
			}else{
				$p['carKlima'] = $fTInt->isValid($p['carKlima']);
			}
			
			if(isset($p['carDesc'])){
				$p['carDesc'] = $fString1000->filter($p['carDesc']);
			}
			
			if(!isset($p['userAds']) || ($p['userAds'] == null) || ($p['userAds'] == -1)){
				$p['userAds'] = -1;
			}else{
				if (!isset($lang['TXT_33'][$p['userAds']])){
					$p['userAds'] = -1;
					//$p['userAds'] = $fTInt->isValid($p['userAds']);
				}				
			}
			
			//car energy efficient class
			if(isset($p['carEEK']) && isset($lang['V_EEK']) && is_array($lang['V_EEK'])){
				$p['carEEK'] = strtoupper(trim($p['carEEK']));
				!in_array($p['carEEK'], $lang['V_EEK']) ? $p['carEEK'] == null : '';
			}
			
			//check userFirm
			isset($p['userFirm']) ? $p['userFirm'] = $fString100->filter($p['userFirm']) : $p['userFirm'] = '';
			
			//check userNName
			isset($p['userNName']) ? $p['userNName'] = $fString100->filter($p['userNName']) : $p['userNName'] = '';
			
			//check userVName
			isset($p['userVName']) ? $p['userVName'] = $fString100->filter($p['userVName']) : $p['userVName'] = '';
			
			//userPLZ
			isset($p['userPLZ']) ? $p['userPLZ'] = $fString20->filter($p['userPLZ']) : $p['userPLZ'] = '';
			
			//userOrt
			isset($p['userOrt']) ? $p['userOrt'] = $fString100->filter($p['userOrt']) : $p['userOrt'] = '';
			
			//userTel1
			isset($p['userTel1']) ? $p['userTel1'] = $fString100->filter($p['userTel1']) : $p['userTel1'] = '';
			
			//userTel2
			isset($p['userTel2']) ? $p['userTel2'] = $fString100->filter($p['userTel2']) : $p['userTel2'] = '';
			
			//userAdress
			isset($p['userAdress']) ? $p['userAdress'] = $fString100->filter($p['userAdress']) : $p['userAdress'] = '';;
			
			//check carLocPLZ
			isset($p['carLocOrt']) ? $p['carLocOrt'] = $fString100->filter($p['carLocOrt']) : $p['carLocOrt'] = '';
			
			//Check carLocPLZ
			isset($p['carLocPLZ'])?$p['carLocPLZ'] = $fString20->filter($p['carLocPLZ']):$p['carLocPLZ'] = '';
			
			//Car Extra
			$carExt = '';
			if (isset($p['carExt'])){
				include_once ('default/models/car/db_selCarExt.php');			
				$carExt = db_selCarExt(array('vextID'=>$p['carExt']));
			}
			$p['carExtDB'] = $carExt;
		}
		return $p;
	}

	/**
	 * This function filter a bike advertisement
	 * @param $p:	this variable contains the parameter of a bike advertisement
	 */
	protected function filterBikeData($p){
		$lang = $this -> lang;
		
		include_once ('default/models/bike/db_selBikeBrand.php');
		include_once ('default/models/bike/db_selBikeModel.php');
		//Process only if cins is pressed
		include_once('default/views/filters/FilterMySQLInt.php');
		include_once('default/views/filters/FilterMySQLMInt.php');
		include_once('default/views/filters/FilterMonth.php');
		include_once('default/views/filters/FilterYear.php');
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterString20.php');
		
		$fInt = new FilterMySQLInt();
		$fMInt = new FilterMySQLMInt();
		$fMonth = new FilterMonth();
		$fYear = new FilterYear();
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();	
		
		//Check bikeBrand
		if (!isset($p['bikeBrand'])){
				$p['error'] = $lang['ERR_2'];
		}
		elseif (($bikeBrand = db_selBikeBrand(array('bikeBrandID'=>$p['bikeBrand']))) == false){
			$p['error'] = $lang['ERR_2'];
		}
		/*Check model if necessary
		else if (isset($p['bikeModel']) && ($p['bikeModel'] != -1) 
				&& (db_selBikeModel(array('bikeBrandID' => $p['bikeBrand'], 'bikeModelID' => $p['bikeModel'])) == false)){
					echo "KK";
			$p['error'] = $lang['ERR_2'];			
		}
		*/
		//Check bikePrice
		else if (!isset($p['bikePrice']) || ($fInt -> isValid($p['bikePrice']) == false)){
				$p['error'] = $lang['ERR_2'];
		}
		//Check bikePower
		else if (!isset($p['bikePower']) || ($fMInt -> isValid($p['bikePower']) == false)){
				$p['error'] = $lang['ERR_2'];				
		}
		//Check bikeKM
		else if (!isset($p['bikeKM']) || ($fMInt -> isValid($p['bikeKM']) == false)){
				$p['error'] = $lang['ERR_2'];
		}
		//Check bikeEZ
		else if ((!isset($p['bikeEZM']) || !isset($p['bikeEZY'])
				|| ($fMonth -> isValid($p['bikeEZM']) == false)
				|| ($fYear -> isValid($p['bikeEZY']) == false))  && ($p['bikeState'] != '0')){
				$p['error'] = $lang['ERR_2'];
		}
		//Check userNName
		else if ( !isset($p['userNName']) || ($fString100 -> isValid($p['userNName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userVName
		else if ( !isset($p['userVName']) || ($fString100 -> isValid($p['userVName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userEmail
		else if ( !isset($p['userEMail']) || $fEMail->filter($p['userEMail']) == false){
			$p['error'] = $lang['ERR_2'];
		}/*
		//Check bikeLocPLZ
		else if ( !isset($p['bikeLocPLZ']) || $fString20->filter($p['bikeLocPLZ']) == false){
			$p['error'] = $lang['ERR_2'];
		}*/
		else{
			
			//Include and instantiate diverse filter
			include_once('default/views/filters/FilterMySQLSInt.php');
			include_once('default/views/filters/FilterMySQLTInt.php');
			include_once('default/views/filters/FilterString10.php');
			include_once('default/views/filters/FilterString50.php');
			include_once('default/views/filters/FilterString1000.php');
			$fSInt = new FilterMySQLSInt();
			$fTInt = new FilterMySQLTInt();
			$fString10 = new FilterString10();
			$fString50 = new FilterString50();
			$fString1000 = new FilterString1000();
			
			//Check Model
			$p['bikeModelTxt'] = null;
			if (isset($p['bikeModel']) && ($p['bikeModel'] != -1)){				
				$bikeModel = db_selBikeModel(array(	'bikeBrandID' => $bikeBrand[0]['bikeBrandID'],
													'bikeModelID' => $p['bikeModel'])
											);
				if($bikeModel != false){
					$p['bikeModelTxt'] = $bikeModel[0]['bikeModelName'];
				}
			}
			
			if (isset($p['bikeModelVar'])){
				$p['bikeModelVar'] = $fString100 -> filter($p['bikeModelVar']);
			}else{
				$p['bikeModelVar'] = '';
			}
			
			$p['bikeBrandTxt'] = $bikeBrand[0]['brandName'];
			
			if(!isset($p['bikePriceType']) || ($p['bikePriceType'] == null)){
				$p['bikePriceType'] = 0;
			}else{
				$p['bikePriceType'] = $fTInt->filter($p['bikePriceType']);
				if (!isset($lang['TXT_70'][$p['bikePriceType']])){
					$p['bikePriceType'] = 0;
				}
			}
			
			if (!isset($p['bikePriceCurr']) || ($p['bikePriceCurr'] == null)){
				$p['bikePriceCurr'] = 0;
			}else{
				$p['bikePriceCurr'] = $fTInt->filter($p['bikePriceCurr']);
				if (!isset($lang['TXT_74'][$p['bikePriceCurr']])){
					$p['bikePriceCurr'] = 0;
				}
			}
			
			//MwSt
			if(isset($p['mwst'])){
				$p['mwst'] = 1;
			}else{
				$p['mwst'] = 0;
			}
			if(isset($p['mwstSatz']) && isset($lang['V_MWST']) && is_array($lang['V_MWST']) && isset($p['mwst']) && ($p['mwst'] == 1)){
				$p['mwstSatz'] = str_replace(',', '.', $p['mwstSatz']);
				if(!in_array($p['mwstSatz'], $lang['V_MWST']) && ($p['mwst'] === '0')){
					$p['mwstSatz'] = 19;
				}				
			}else{
				$p['mwstSatz'] = '-1';
			}
			
			if(!isset($p['bikePowerType']) || ($p['bikePowerType'] == null)){
				$p['bikePowerType'] = 0;
			}else{
				$p['bikePowerType'] = $fMInt->filter($p['bikePowerType']);
				if (!isset($lang['TXT_72'][$p['bikePowerType']])){
					$p['bikePowerType'] = 0;
				}
			}
			
			if(!isset($p['bikeKMType']) || ($p['bikeKMType'] == null)){
				$p['bikeKMType'] = 0;
			}else{
				$p['bikeKMType'] = $fTInt->filter($p['bikeKMType']);
				if (!isset($lang['TXT_75'][$p['bikeKMType']])){
					$p['bikeKMType'] = 0;
				}
			}
		
			if (isset($p['bikeHSN'])){
				$p['bikeHSN'] = $fString10 -> filter($p['bikeHSN']);
			}
		
			if (isset($p['bikeTSN'])){
				$p['bikeTSN'] = $fString10 -> filter($p['bikeTSN']);
			}
		
			if (isset($p['bikeFIN'])){
				$p['bikeFIN'] = $fString20 -> filter($p['bikeFIN']);
			}
				
			if(!isset($p['bikeTUVM']) || ($p['bikeTUVM'] == null)){
				$p['bikeTUVM'] = 1;
			}else{
				$p['bikeTUVM'] = $fMonth->filter($p['bikeTUVM']);		
			}		
			if(!isset($p['bikeTUVY']) || ($p['bikeTUVY'] == null)){
				$p['bikeTUVY'] = date('Y');
			}else{					
				$p['bikeTUVY'] = $fYear->filter($p['bikeTUVY']);
			}
					
			if(!isset($p['bikeAUM']) || ($p['bikeAUM'] == null)){
				$p['bikeAUM'] = 1;
			}else{
				$p['bikeAUM'] = $fMonth->filter($p['bikeAUM']);
			}
			if(!isset($p['bikeAUY']) || ($p['bikeAUY'] == null)){
				$p['bikeAUY'] = date('Y');
			}else{
				$p['bikeAUY'] = $fYear->filter($p['bikeAUY']);
			}
			
			if(!isset($p['bikeShift']) || ($p['bikeShift'] == null)){
				$p['bikeShift'] = -1;
			}else{
				$p['bikeShift'] = $fTInt->filter($p['bikeShift']);
			}
			
			if(!isset($p['bikeWeight']) || ($p['bikeWeight'] == null)){
				$p['bikeWeight'] = 0;
			}else{
				$p['bikeWeight'] = $fMInt->filter($p['bikeWeight']);
			}
			
			if(!isset($p['bikeCyl']) || ($p['bikeCyl'] == null)){
				$p['bikeCyl'] = 0;
			}else{
				$p['bikeCyl'] = $fTInt->filter($p['bikeCyl']);
			}
			
			if(!isset($p['bikeCub']) || ($p['bikeCub'] == null)){
				$p['bikeCub'] = 0;
			}else{
				$p['bikeCub'] = $fSInt->filter($p['bikeCub']);
			}
			
			if (isset($p['bikeUseIn'])){
				$p['bikeUseIn'] = $fString50->filter($p['bikeUseIn']);
			}
			else{
				$p['bikeUseIn'] = '';
			}
			
			if (isset($p['bikeUseOut'])){
				$p['bikeUseOut'] = $fString50->filter($p['bikeUseOut']);	
			}else{
				$p['bikeUseOut'] = '';
			}
			
			if (isset($p['bikeCO2'])){
				$p['bikeCO2'] = $fString50->filter($p['bikeCO2']);
			}else{
				$p['bikeCO2'] = '';
			}
			
			if(!isset($p['bikeState']) || ($p['bikeState'] == null)){
				$p['bikeState'] = -1;
			}else{
				$p['bikeState'] = $fTInt->filter($p['bikeState']);
			}
			
			if(!isset($p['bikeCat']) || ($p['bikeCat'] == null)){
				$p['bikeCat'] = -1;
			}else{
				$p['bikeCat'] = $fTInt->filter($p['bikeCat']);
			}
			
			if(!isset($p['bikeFuel']) || ($p['bikeFuel'] == null)){
				$p['bikeFuel'] = -1;
			}else{
				$p['bikeFuel'] = $fTInt->filter($p['bikeFuel']);
			}
			
			if(!isset($p['bikeClr']) || ($p['bikeClr'] == null)){
				$p['bikeClr'] = -1;
			}else{
				$p['bikeClr'] = $fTInt->filter($p['bikeClr']);
			}
			if(isset($p['bikeClrMet'])){
				$p['bikeClrMet'] = '1';
			}
			else{
				$p['bikeClrMet'] = '0';
			}
			
			if(!isset($p['bikeEmissionNorm']) || ($p['bikeEmissionNorm'] == null)){
				$p['bikeEmissionNorm'] = -1;
			}else{
				$p['bikeEmissionNorm'] = $fTInt->filter($p['bikeEmissionNorm']);
			}
			
			if(!isset($p['bikeEcologicTag']) || ($p['bikeEcologicTag'] == null)){
				$p['bikeEcologicTag'] = -1;
			}else{
				$p['bikeEcologicTag'] = $fTInt->isValid($p['bikeEcologicTag']);
			}
			
			$p['bikeDesc'] = $fString1000->filter($p['bikeDesc']);
			
			if(!isset($p['userAds']) || ($p['userAds'] == null) || ($p['userAds'] == -1)){
				$p['userAds'] = -1;
			}else{
				if (!isset($lang['TXT_33'][$p['userAds']])){
					$p['userAds'] = -1;
					//$p['userAds'] = $fTInt->isValid($p['userAds']);
				}				
			}
			
			//check userFirm
			isset($p['userFirm']) ? $p['userFirm'] = $fString100->filter($p['userFirm']) : $p['userFirm'] = '';
			
			//check userNName
			isset($p['userNName']) ? $p['userNName'] = $fString100->filter($p['userNName']) : $p['userNName'] = '';
			
			//check userVName
			isset($p['userVName']) ? $p['userVName'] = $fString100->filter($p['userVName']) : $p['userVName'] = '';
			
			//userPLZ
			isset($p['userPLZ']) ? $p['userPLZ'] = $fString20->filter($p['userPLZ']) : $p['userPLZ'] = '';
			
			//userOrt
			isset($p['userOrt']) ? $p['userOrt'] = $fString100->filter($p['userOrt']) : $p['userOrt'] = '';
			
			//userTel1
			isset($p['userTel1']) ? $p['userTel1'] = $fString100->filter($p['userTel1']) : $p['userTel1'] = '';
			
			//userTel2
			isset($p['userTel2']) ? $p['userTel2'] = $fString100->filter($p['userTel2']) : $p['userTel2'] = '';
			
			//userAdress
			isset($p['userAdress']) ? $p['userAdress'] = $fString100->filter($p['userAdress']) : $p['userAdress'] = '';;
			
			//check bikeLocPLZ
			//isset($p['bikeLocOrt']) ? $p['bikeLocOrt'] = $fString100->filter($p['bikeLocOrt']) : $p['bikeLocOrt'] = '';			
			if ( isset($p['bikeLocPLZ']) ){
				$p['bikeLocPLZ'] = $fString20->filter($p['bikeLocPLZ']);
			}
			
			//Check bikeLocOrt
			if ( isset($p['bikeLocOrt']) ){
				$p['bikeLocOrt'] = $fString100->filter($p['bikeLocOrt']);
			}
			
			//Check bikeLocCountry
			if ( !isset($p['bikeLocCountry']) || !isset($lang['COUNTRY'][$p['bikeLocCountry']])){
				$p['bikeLocCountry'] = 'DE';
			}
			
			//check userAdsLength
			if (!isset($p['userAdsLength']) || !in_array($p['userAdsLength'], $lang['USER_ADS_LENGTH']) ){
				$p['userAdsLength'] = $lang['USER_ADS_LENGTH'][count($lang['USER_ADS_LENGTH'])-1];
			}
			
			//Bike Extra
			$bikeExt = '';
			if (isset($p['bikeExt'])){
				include_once ('default/models/bike/db_selBikeExt.php');			
				$bikeExt = db_selBikeExt(array('vextID'=>$p['bikeExt']
										));
			}
			$p['bikeExtDB'] = $bikeExt;
		}
		return $p;
	}
	
	/**
	 * This function filter a truck advertisement
	 * @param $p:	this variable contains the parameter of a truck advertisement
	 */
	protected function filterTruckData($p){
		$lang = $this -> lang;
		
		include_once ('default/models/truck/db_selTruckBrand.php');
		include_once ('default/models/truck/db_selTruckModel.php');
		//Process only if cins is pressed
		include_once('default/views/filters/FilterMySQLInt.php');
		include_once('default/views/filters/FilterMySQLMInt.php');
		include_once('default/views/filters/FilterMonth.php');
		include_once('default/views/filters/FilterYear.php');
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterString20.php');
		
		$fInt = new FilterMySQLInt();
		$fMInt = new FilterMySQLMInt();
		$fMonth = new FilterMonth();
		$fYear = new FilterYear();
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();
		
		//Check truckBrand
		if (!isset($p['truckBrand'])){
			$p['error'] = $lang['ERR_2'];
		}elseif (($truckBrand = db_selTruckBrand(array('truckBrandID'=>$p['truckBrand']))) == false){
			$p['error'] = $lang['ERR_2'];			
		}
		/*Check model if necessary
		else if (isset($p['truckModel']) && ($p['truckModel'] != -1) 
				&& (db_selTruckModel(array('truckBrandID' => $p['truckBrand'], 'truckModelID' => $p['truckModel'])) == false)){
					echo "KK";
			$p['error'] = $lang['ERR_2'];			
		}
		*/
		//Check truckPrice
		else if (!isset($p['truckPrice']) || ($fInt -> isValid($p['truckPrice']) == false)){
				$p['error'] = $lang['ERR_2'];
		}
		//Check truckPower
		else if (!isset($p['truckPower']) || ($fMInt -> isValid($p['truckPower']) == false)){
				$p['error'] = $lang['ERR_2'];				
		}
		//Check truckKM
		else if (!isset($p['truckKM']) || ($fMInt -> isValid($p['truckKM']) == false)){
				$p['error'] = $lang['ERR_2'];
		}
		//Check truckEZ
		else if ((!isset($p['truckEZM']) || !isset($p['truckEZY'])
				|| ($fMonth -> isValid($p['truckEZM']) == false)
				|| ($fYear -> isValid($p['truckEZY']) == false)) && ($p['truckState'] != '0')){
				$p['error'] = $lang['ERR_2'];
		}
		//Check userNName
		else if ( !isset($p['userNName']) || ($fString100 -> isValid($p['userNName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userVName
		else if ( !isset($p['userVName']) || ($fString100 -> isValid($p['userVName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userEmail
		else if ( !isset($p['userEMail']) || $fEMail->filter($p['userEMail']) == false){
			$p['error'] = $lang['ERR_2'];
		}/*
		//Check truckLocPLZ
		else if ( !isset($p['truckLocPLZ']) || $fString20->filter($p['truckLocPLZ']) == false){
			$p['error'] = $lang['ERR_2'];
		}*/
		else{
			//Include and instantiate diverse filter
			include_once('default/views/filters/FilterMySQLSInt.php');
			include_once('default/views/filters/FilterMySQLTInt.php');
			include_once('default/views/filters/FilterString10.php');
			include_once('default/views/filters/FilterString50.php');
			include_once('default/views/filters/FilterString1000.php');
			$fSInt = new FilterMySQLSInt();
			$fTInt = new FilterMySQLTInt();
			$fString10 = new FilterString10();
			$fString50 = new FilterString50();
			$fString1000 = new FilterString1000();
			
			//Check Model
			$p['truckModelTxt'] = null;
			if (isset($p['truckModel']) && ($p['truckModel'] != -1)){				
				$truckModel = db_selTruckModel(array('truckBrandID' => $truckBrand[0]['truckBrandID']
													, 'truckModelID' => $p['truckModel'])
											);
				if($truckModel != false){
					$p['truckModelTxt'] = $truckModel[0]['truckModelName'];
				}
			}else{
				$p['truckModel'] = -1;
			}
		
			
			if (isset($p['truckModelVar'])){
				$p['truckModelVar'] = $fString100 -> filter($p['truckModelVar']);
			}else{
				$p['truckModelVar'] = '';
			}
			
			$p['truckBrandTxt'] = $truckBrand[0]['brandName'];
			
			if(!isset($p['truckPriceType']) || ($p['truckPriceType'] == null)){
				$p['truckPriceType'] = 0;
			}else{
				$p['truckPriceType'] = $fTInt->filter($p['truckPriceType']);
				if (!isset($lang['TXT_70'][$p['truckPriceType']])){
					$p['truckPriceType'] = 0;
				}
			}
			
			if (!isset($p['truckPriceCurr']) || ($p['truckPriceCurr'] == null)){
				$p['truckPriceCurr'] = 0;
			}else{
				$p['truckPriceCurr'] = $fTInt->filter($p['truckPriceCurr']);
				if (!isset($lang['TXT_74'][$p['truckPriceCurr']])){
					$p['truckPriceCurr'] = 0;
				}
			}
			
			//MwSt
			if(isset($p['mwst'])){
				$p['mwst'] = 1;
			}else{
				$p['mwst'] = 0;
			}
			if(isset($p['mwstSatz']) && isset($lang['V_MWST']) && is_array($lang['V_MWST']) && isset($p['mwst']) && ($p['mwst'] == 1)){
				$p['mwstSatz'] = str_replace(',', '.', $p['mwstSatz']);
				if(!in_array($p['mwstSatz'], $lang['V_MWST']) && ($p['mwst'] === '0')){
					$p['mwstSatz'] = 19;
				}				
			}else{
				$p['mwstSatz'] = '-1';
			}
			
			if(!isset($p['truckPowerType']) || ($p['truckPowerType'] == null)){
				$p['truckPowerType'] = 0;
			}else{
				$p['truckPowerType'] = $fMInt->filter($p['truckPowerType']);
				if (!isset($lang['TXT_72'][$p['truckPowerType']])){
					$p['truckPowerType'] = 0;
				}
			}
			
			if(!isset($p['truckKMType']) || ($p['truckKMType'] == null)){
				$p['truckKMType'] = 0;
			}else{
				$p['truckKMType'] = $fTInt->filter($p['truckKMType']);
				if (!isset($lang['TXT_75'][$p['truckKMType']])){
					$p['truckKMType'] = 0;
				}
			}
		
			if (isset($p['truckHSN'])){
				$p['truckHSN'] = $fString10 -> filter($p['truckHSN']);
			}
		
			if (isset($p['truckTSN'])){
				$p['truckTSN'] = $fString10 -> filter($p['truckTSN']);
			}
		
			if (isset($p['truckFIN'])){
				$p['truckFIN'] = $fString20 -> filter($p['truckFIN']);
			}
				
			if(!isset($p['truckTUVM']) || ($p['truckTUVM'] == null)){
				$p['truckTUVM'] = 1;
			}else{
				$p['truckTUVM'] = $fMonth->filter($p['truckTUVM']);		
			}		
			if(!isset($p['truckTUVY']) || ($p['truckTUVY'] == null)){
				$p['truckTUVY'] = date('Y');
			}else{					
				$p['truckTUVY'] = $fYear->filter($p['truckTUVY']);
			}
					
			if(!isset($p['truckAUM']) || ($p['truckAUM'] == null)){
				$p['truckAUM'] = 1;
			}else{
				$p['truckAUM'] = $fMonth->filter($p['truckAUM']);
			}
			if(!isset($p['truckAUY']) || ($p['truckAUY'] == null)){
				$p['truckAUY'] = date('Y');
			}else{
				$p['truckAUY'] = $fYear->filter($p['truckAUY']);
			}
			
			if(!isset($p['truckShift']) || ($p['truckShift'] == null)){
				$p['truckShift'] = -1;
			}else{
				$p['truckShift'] = $fTInt->filter($p['truckShift']);
			}
			
			if(!isset($p['truckWeight']) || ($p['truckWeight'] == null)){
				$p['truckWeight'] = 0;
			}else{
				$p['truckWeight'] = $fMInt->filter($p['truckWeight']);
			}
			
			if(!isset($p['truckCyl']) || ($p['truckCyl'] == null)){
				$p['truckCyl'] = 0;
			}else{
				$p['truckCyl'] = $fTInt->filter($p['truckCyl']);
			}
			
			if(!isset($p['truckCub']) || ($p['truckCub'] == null)){
				$p['truckCub'] = 0;
			}else{
				$p['truckCub'] = $fSInt->filter($p['truckCub']);
			}
			
			if (isset($p['truckUseIn'])){
				$p['truckUseIn'] = $fString50->filter($p['truckUseIn']);
			}else{
				$p['truckUseIn'] = '';
			}
			
			if (isset($p['truckUseOut'])){
				$p['truckUseOut'] = $fString50->filter($p['truckUseOut']);
			}else{
				$p['truckUseOut'] = '';
			}
			
			if (isset($p['truckCO2'])){
				$p['truckCO2'] = $fString50->filter($p['truckCO2']);
			}else{
				$p['truckCO2'] = '';
			}
			
			if(!isset($p['truckState']) || ($p['truckState'] == null)){
				$p['truckState'] = -1;
			}else{
				$p['truckState'] = $fTInt->filter($p['truckState']);
			}
			
			if(!isset($p['truckCat']) || ($p['truckCat'] == null)){
				$p['truckCat'] = -1;
			}else{
				$p['truckCat'] = $fTInt->filter($p['truckCat']);
			}
			
			if(!isset($p['truckFuel']) || ($p['truckFuel'] == null)){
				$p['truckFuel'] = -1;
			}else{
				$p['truckFuel'] = $fTInt->filter($p['truckFuel']);
			}
			
			if(!isset($p['truckClr']) || ($p['truckClr'] == null)){
				$p['truckClr'] = -1;
			}else{
				$p['truckClr'] = $fTInt->filter($p['truckClr']);
			}
			if(isset($p['truckClrMet'])){
				$p['truckClrMet'] = '1';
			}
			else{
				$p['truckClrMet'] = '0';
			}
			
			if(!isset($p['truckEmissionNorm']) || ($p['truckEmissionNorm'] == null)){
				$p['truckEmissionNorm'] = -1;
			}else{
				$p['truckEmissionNorm'] = $fTInt->filter($p['truckEmissionNorm']);
			}
			
			if(!isset($p['truckEcologicTag']) || ($p['truckEcologicTag'] == null)){
				$p['truckEcologicTag'] = -1;
			}else{
				$p['truckEcologicTag'] = $fTInt->isValid($p['truckEcologicTag']);
			}
			
			if(!isset($p['truckKlima']) || ($p['truckKlima'] == null)){
				$p['truckKlima'] = -1;
			}else{
				$p['truckKlima'] = $fTInt->isValid($p['truckKlima']);
			}
			
			$p['truckDesc'] = $fString1000->filter($p['truckDesc']);
			
			if(!isset($p['userAds']) || ($p['userAds'] == null) || ($p['userAds'] == -1)){
				$p['userAds'] = -1;
			}else{
				if (!isset($lang['TXT_33'][$p['userAds']])){
					$p['userAds'] = -1;
					//$p['userAds'] = $fTInt->isValid($p['userAds']);
				}				
			}
			
			//check userFirm
			isset($p['userFirm']) ? $p['userFirm'] = $fString100->filter($p['userFirm']) : $p['userFirm'] = '';
			
			//check userNName
			isset($p['userNName']) ? $p['userNName'] = $fString100->filter($p['userNName']) : $p['userNName'] = '';
			
			//check userVName
			isset($p['userVName']) ? $p['userVName'] = $fString100->filter($p['userVName']) : $p['userVName'] = '';
			
			//userPLZ
			isset($p['userPLZ']) ? $p['userPLZ'] = $fString20->filter($p['userPLZ']) : $p['userPLZ'] = '';
			
			//userOrt
			isset($p['userOrt']) ? $p['userOrt'] = $fString100->filter($p['userOrt']) : $p['userOrt'] = '';
			
			//userTel1
			isset($p['userTel1']) ? $p['userTel1'] = $fString100->filter($p['userTel1']) : $p['userTel1'] = '';
			
			//userTel2
			isset($p['userTel2']) ? $p['userTel2'] = $fString100->filter($p['userTel2']) : $p['userTel2'] = '';
			
			//userAdress
			isset($p['userAdress']) ? $p['userAdress'] = $fString100->filter($p['userAdress']) : $p['userAdress'] = '';;
			
			//check truckLocPLZ
			//isset($p['truckLocOrt']) ? $p['truckLocOrt'] = $fString100->filter($p['truckLocOrt']) : $p['truckLocOrt'] = '';
			if ( isset($p['truckLocPLZ']) ){
				$p['truckLocPLZ'] = $fString20->filter($p['truckLocPLZ']);
			}
			
			//Check truckLocOrt
			if ( isset($p['truckLocOrt']) ){
				$p['truckLocOrt'] = $fString100->filter($p['truckLocOrt']);
			}
			
			//Check truckLocCountry
			if ( !isset($p['truckLocCountry']) || !isset($lang['COUNTRY'][$p['truckLocCountry']])){
				$p['truckLocCountry'] = 'DE';
			}
			
			//check userAdsLength
			if (!isset($p['userAdsLength']) || !in_array($p['userAdsLength'], $lang['USER_ADS_LENGTH']) ){
				$p['userAdsLength'] = $lang['USER_ADS_LENGTH'][count($lang['USER_ADS_LENGTH'])-1];
			}
			
			//Truck Extra
			$truckExt = '';
			if (isset($p['truckExt'])){
				include_once ('default/models/truck/db_selTruckExt.php');			
				$truckExt = db_selTruckExt(array('vextID'=>$p['truckExt']));
			}
			$p['truckExtDB'] = $truckExt;
		}
		return $p;
	}
	
	protected function transA2VStruc($p){
		$ret = array();
		if (is_array($p)) {
			foreach ($p as $key => $vAd){
				$vAdNew = array();
				if (is_numeric($key) && is_array($vAd)){
					foreach ($vAd as $fKey => $fVal){
						$fKey = str_replace('car', 'v', $fKey);
						$fKey = str_replace('bike', 'v', $fKey);
						$fKey = str_replace('truck', 'v', $fKey);
						$vAdNew[$fKey] = $fVal;
					}
					array_push($ret, $vAdNew);
				}	
			}
		}
		return $ret;
	}
	
	protected function createUploadFolder($p){
		$fileDestination = '../app/ftp/'.$p['userID'].'/upload';
		if (!is_dir($fileDestination)){
			mkdir($fileDestination, 0766, true);
		}
	}
	
	protected function createDownloadFolder($p){
		$fileDestination = '../app/ftp/'.$p['userID'].'/download';
		if (!is_dir($fileDestination)){
			mkdir($fileDestination, 0766, true);
		}
		return $fileDestination;
	}
}
?>