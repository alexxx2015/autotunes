<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for INDEX
 *********************************************************************************/
include_once('classes/AbstractController.php');
include_once('securimage/securimage.php');
include_once('default/models/default/db_selVPic.php');	

class DealerController extends AbstractController{
	
	private $actParam;
	private $user;
	private $dealerNS;
	
	public function preDispatch(){		
		parent::preDispatch();
		
		include_once('default/models/member/db_selUser.php');
		
		$action = $this -> getRequest() -> getActionName();
		if ($action == 'index'){
			//$this -> _forward('index','index');
		}
		/*	
		elseif(($action == 'login')
			&& ($this->userNS->userLogged == true)){
			$this -> _forward('index');				
		}
		*/
		$this -> view -> tmpl = $this -> tmpl;//getFrontController() -> getParam(System_Properties::TMPL);		
		$this -> view -> lang = $this -> lang;
	
		$p = $this -> getRequest() -> getParams();
		if (isset($p['dealerID'])){
			$dealerID = $p['dealerID'];
			$user = db_selUser(array('userID' => $dealerID));
			if(($user != false) && is_array($user) && (count($user) > 0)){
				$user = $user[0];
				$this -> user = $user;
				$this -> view -> user = $user;				
			}else{
				$this -> _forward('index','index');
			}
		}else{
			$this -> _forward('index','index');
		}
		$this -> dealerNS = new Zend_Session_Namespace(System_Properties::DEALER_NS);
	}
	
	public function indexAction(){
		include_once('default/models/default/db_selEMail.php');
		include_once('default/models/default/db_insEMail.php');
		
		$lang = $this -> lang;
		$p = $this -> getRequest() -> getParams();
		$user = $this -> user;
		
		//User send a contact email to advertiser
		if (isset($p['sendEMail'])){
			$pHelp = $this -> filterEMailContact($p);
			$secImg = new Securimage();
			
			if (isset($pHelp['error'])){
				$this -> view -> error = $pHelp['error'];
			}
			elseif (!isset($p['captcha_code']) || ($secImg -> check($p['captcha_code']) == false)){
				$this -> view -> error = $lang['ERR_51'];
			}	
			else{
				$allowEMailSend = false;
				$email = db_selEMail(array(	'vType' => System_Properties::IMP_ABRV
											, 'senderEMailAddress' => $pHelp['emailAddress'] 
											, 'orderby' => array(
																array(	'col' => 'timestam'
																		, 'desc' => true
																	)	
																)
											)
										);
				if ($email == false){
					$allowEMailSend = true;
				}else{
					
					if (is_array($email) && isset($email[0]['timestam'])){								
						if ((time() - $email[0]['timestam']) >= 300){
							//echo (time() - $email[0]['timestam']);
							$allowEMailSend = true;
						}
					}
					
				}

				if($allowEMailSend == true){
					$senderID = 'null';
					if (isset($this -> userNS -> userData['userID'])){
						$senderID = $this -> userNS -> userData['userID'];
					}
					$emailSend = db_insEMail(array(	'senderID' => $senderID
													, 'eMailText' => $pHelp['emailText']
													, 'senderEMailAddress' => $pHelp['emailAddress']
													, 'senderEMailName' => $pHelp['emailName']
													, 'receiverID' => $user['userID']
													, 'receiverEMailAddress' => $user['userEMail']
													, 'vID' => 0
													, 'vType' => System_Properties::IMP_ABRV
												)
											);
					if ($emailSend != false){
						$this -> view -> info = $lang['INFO_2'];
					}
					else{
						$this -> view -> error = $lang['ERR_9'];
					}
				}else{
					$this -> view -> error = $lang['ERR_9'].' '.$lang['ERR_10'];
				}
			}
		}
		$this -> view -> p = $p;
	}
	
	public function carAction(){
		include_once('default/models/car/db_selCarAds.php');
		include_once('default/models/car/db_selCarModel.php');
		
		if (!is_null($this -> user)){
			$lang = $this -> lang;
			$user = $this -> user;
			$p = $this -> getRequest() -> getParams();
			if (!array($p)){
				$p = array();
			}
			
			//set carSearchParam if search2 button is pressed
			if (isset($p['search2'])){
				$this -> dealerNS -> carSearchParam = $p; 
			}elseif(!isset($p['page'])){
				$this->dealerNS->carSearchParam = null;
			}
		
			$page = 1;
			if (isset($p['page'])){
				$page = $p['page'];
				if(($this->dealerNS->carSearchParam != null) && is_array($this->dealerNS->carSearchParam)){
					$p = $this->dealerNS->carSearchParam;
					$p['page'] = $page;
				}					
			}
			
			if (isset($p['carSort']) && ($p['carSort'] != -1)){
				switch ($p['carSort']) {
					//0 => Price => carPrice
					case '0': $p['orderby'] = array('col' => 'carPrice');
							break;
					//1 => Leistung => carPower
					case '1': $p['orderby'] = array('col' => 'carPower');
							break;				
					//2 => Laufleistung => carKM
					case '2': $p['orderby'] = array('col' => 'carKM');
							break;			
					//3 => Datum => timestam
					case '2': $p['orderby'] = array('col' => 'timestam');
							break;
				}
				//
				if (isset($p['orderby']) && is_array($p['orderby'])){
					$p['orderby']['desc'] = false;
					//1 => DESC
					if (isset($p['carSortOpt']) && ($p['carSortOpt'] == 1)){
						$p['orderby']['desc'] = true;
					}
					
					$p['orderby'] = array($p['orderby']);
				}
			}
			$p['userID'] = $user['userID']; 
			$p['notUserAds'] = 3;
			$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
					
			$this -> loadCarModelsBrands($p);
			$this -> view -> searchParam = $p;
			
			//carModel
			if (isset($p['carModel']) && isset($p['carBrand'])){
				$carModel = db_selCarModel(array('carModelID' => $p['carModel']));
				if (is_array($carModel) && (count($carModel) > 0)){
					$carModel2 = array();
					foreach($carModel as $key => $kVal){
						array_push($carModel2, $kVal['carBrandID']);
					}
					
					$carBrand2 = array();
					foreach ($p['carBrand'] as $key => $kVal){
						if (!in_array($kVal, $carModel2)){
							array_push($carBrand2, $kVal);
						}	
					}	
	
					$p['carBrand'] = $carBrand2;
				}
			}
			
			//$p['print'] = true;
			$carAds = db_selCarAds($p);
			if (!is_array($carAds) || ($carAds == false)){
				$this -> view -> info = $lang['ERR_4'];
			}
			elseif (is_array($carAds) && (count($carAds) < 1)){
				$this -> view -> info = $lang['ERR_4'];
			}else{
				if (is_array($carAds) && ($carAds != false)){
					$carAdsP = array('totalAds'=>$carAds['totalRows'], 'carAds' => array()
									,'numAds'=>System_Properties::NUM_ADS, 'actPage' => $page);
					
					//For each car ad select the correspondend car picutres
					foreach ($carAds AS $key => $carAd){
						if (is_numeric($key)){
							$carAd['carPics'] = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
																	, 'vID' => $carAd['carID']
																	, 'vPicTMP' => '0'
																)
															);					
							array_push($carAdsP['carAds'], $carAd);
						}				
					}
					$carAds = $carAdsP;
				}
			}
			
			$this -> view -> car = $carAds;
		}
	}
	
	private function loadCarModelsBrands($p = null){
		include_once ('default/models/car/db_selCarBrand.php');
		include_once ('default/models/car/db_selCarModel.php');
		
		$carBrand = db_selCarBrand(array('orderby'=>array(array('col' => 'brandName'))
										));
		
		$carModel = false;
		/*
		if(is_array($carBrand) && (count($carBrand) > 0)){
			$carBrandID = $carBrand[0]['carBrandID'];
			if (isset($p['carBrand'])){
				$carBrandID = $p['carBrand'];
			}
			$carModel = db_selCarModel(array('carBrandID' => $carBrandID));
		}
		*/
		
		if(is_array($carBrand) && (count($carBrand) > 0)){
			$carBrandID = $carBrand[0]['carBrandID'];
			if (isset($p['carBrand']) && !is_array($p['carBrand'])){
				$carBrandID = $p['carBrand'];
			}
			elseif (isset($p['carBrand']) && is_array($p['carBrand']) && (count($p['carBrand']) > 0)){
				$carBrandID = array();
				foreach ($p['carBrand'] as $key => $val){
					array_push($carBrandID, $val);
				}
			}
				$carModel = db_selCarModel(array('carBrandID' => $carBrandID
												, 'orderby' => array(
													array('col' => 'lft')
													, array('col'=>'carModelName'))
											));
											
		}
		
		$this -> view -> carBrand = $carBrand;
		$this -> view -> carModel = $carModel;
	}
	
	public function bikeAction(){
		include_once('default/models/bike/db_selBikeAds.php');
		include_once('default/models/bike/db_selBikeModel.php');
		
		if (!is_null($this -> user)){
			$lang = $this -> lang;
			$user = $this -> user;
			$p = $this -> getRequest() -> getParams();
			if (!array($p)){
				$p = array();
			}
			
			//set bikeSearchParam if search2 button is pressed
			if (isset($p['search2'])){
				$this -> dealerNS -> bikeSearchParam = $p; 
			}elseif(!isset($p['page'])){
				$this->dealerNS->bikeSearchParam = null;
			}
		
			$page = 1;
			if (isset($p['page'])){
				$page = $p['page'];
				if(($this->dealerNS->bikeSearchParam != null) && is_array($this->dealerNS->bikeSearchParam)){
					$p = $this->dealerNS->bikeSearchParam;
					$p['page'] = $page;
				}					
			}
			
			if (isset($p['bikeSort']) && ($p['bikeSort'] != -1)){
				switch ($p['bikeSort']) {
					//0 => Price => bikePrice
					case '0': $p['orderby'] = array('col' => 'bikePrice');
							break;
					//1 => Leistung => bikePower
					case '1': $p['orderby'] = array('col' => 'bikePower');
							break;				
					//2 => Laufleistung => bikeKM
					case '2': $p['orderby'] = array('col' => 'bikeKM');
							break;			
					//3 => Datum => timestam
					case '2': $p['orderby'] = array('col' => 'timestam');
							break;
				}
				//
				if (isset($p['orderby']) && is_array($p['orderby'])){
					$p['orderby']['desc'] = false;
					//1 => DESC
					if (isset($p['bikeSortOpt']) && ($p['bikeSortOpt'] == 1)){
						$p['orderby']['desc'] = true;
					}
					
					$p['orderby'] = array($p['orderby']);
				}
			}
			$p['userID'] = $user['userID']; 
			$p['notUserAds'] = 3;
			$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
		
			$this -> loadBikeModelsBrands($p);
			$this -> view -> searchParam = $p;
		
			//bikeModel
			if (isset($p['bikeModel']) && isset($p['bikeBrand'])){
				$bikeModel = db_selBikeModel(array('bikeModelID' => $p['bikeModel']));
				if (is_array($bikeModel) && (count($bikeModel) > 0)){
					$bikeModel2 = array();
					foreach($bikeModel as $key => $kVal){
						array_push($bikeModel2, $kVal['bikeBrandID']);
					}
					
					$bikeBrand2 = array();
					foreach ($p['bikeBrand'] as $key => $kVal){
						if (!in_array($kVal, $bikeModel2)){
							array_push($bikeBrand2, $kVal);
						}	
					}	
	
					$p['bikeBrand'] = $bikeBrand2;
				}
			}
		
			$bikeAds = db_selBikeAds($p);
			if (!is_array($bikeAds) || ($bikeAds == false)){
				$this -> view -> info = $lang['ERR_4'];
			}
			elseif (is_array($bikeAds) && (count($bikeAds) < 1)){
				$this -> view -> info = $lang['ERR_4'];
			}else{
				$bikeAdsP = array('totalAds'=>$bikeAds['totalRows'], 'bikeAds' => array()
									,'numAds'=>System_Properties::NUM_ADS, 'actPage' => $page);
				
				//For each bike ad select the correspondend bike picutres
				foreach ($bikeAds AS $key => $bikeAd){
					if (is_numeric($key)){
						$bikeAd['bikePics'] = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV
																, 'vID' => $bikeAd['bikeID']
																, 'vPicTMP' => '0'
															)
														);					
						array_push($bikeAdsP['bikeAds'], $bikeAd);
					}				
				}
				$bikeAds = $bikeAdsP;
			}
			$this -> view -> bike = $bikeAds;
		}
		
	}

	private function loadBikeModelsBrands($p = null){
		include_once ('default/models/bike/db_selBikeBrand.php');
		include_once ('default/models/bike/db_selBikeModel.php');
		
		$bikeBrand = db_selBikeBrand(array('orderby'=>array(array('col' => 'brandName'))));
		
		$bikeModel = false;
		
		if(is_array($bikeBrand) && (count($bikeBrand) > 0)){
			$bikeBrandID = $bikeBrand[0]['bikeBrandID'];
			if (isset($p['bikeBrand']) && !is_array($p['bikeBrand'])){
				$bikeBrandID = $p['bikeBrand'];
			}
			elseif (isset($p['bikeBrand']) && is_array($p['bikeBrand']) && (count($p['bikeBrand']) > 0)){
				$bikeBrandID = array();
				foreach ($p['bikeBrand'] as $key => $val){
					array_push($bikeBrandID, $val);
				}
			}
				$bikeModel = db_selBikeModel(array('bikeBrandID' => $bikeBrandID
												, 'orderby' => array(
													array('col' => 'lft')
													, array('col'=>'bikeModelName'))
											));
		}
		
		$this -> view -> bikeBrand = $bikeBrand;
		$this -> view -> bikeModel = $bikeModel;
	}
	
	public function truckAction(){
		include_once('default/models/truck/db_selTruckAds.php');
		include_once('default/models/truck/db_selTruckModel.php');
		
		if (!is_null($this -> user)){
			$lang = $this -> lang;
			$user = $this -> user;
			$p = $this -> getRequest() -> getParams();
			if (!array($p)){
				$p = array();
			}
			
			//set truckSearchParam if search2 button is pressed
			if (isset($p['search2'])){
				$this -> dealerNS -> truckSearchParam = $p; 
			}elseif(!isset($p['page'])){
				$this->dealerNS->truckSearchParam = null;
			}
		
			$page = 1;
			if (isset($p['page'])){
				$page = $p['page'];
				if(($this->dealerNS->truckSearchParam != null) && is_array($this->dealerNS->truckSearchParam)){
					$p = $this->dealerNS->truckSearchParam;
					$p['page'] = $page;
				}					
			}
			
			if (isset($p['truckSort']) && ($p['truckSort'] != -1)){
				switch ($p['truckSort']) {
					//0 => Price => truckPrice
					case '0': $p['orderby'] = array('col' => 'truckPrice');
							break;
					//1 => Leistung => truckPower
					case '1': $p['orderby'] = array('col' => 'truckPower');
							break;				
					//2 => Laufleistung => truckKM
					case '2': $p['orderby'] = array('col' => 'truckKM');
							break;			
					//3 => Datum => timestam
					case '2': $p['orderby'] = array('col' => 'timestam');
							break;
				}
				//
				if (isset($p['orderby']) && is_array($p['orderby'])){
					$p['orderby']['desc'] = false;
					//1 => DESC
					if (isset($p['truckSortOpt']) && ($p['truckSortOpt'] == 1)){
						$p['orderby']['desc'] = true;
					}
					
					$p['orderby'] = array($p['orderby']);
				}
			}
			$p['userID'] = $user['userID']; 
			$p['notUserAds'] = 3;
			$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
			
			
			$this -> loadTruckModelsBrands($p);
			$this -> view -> searchParam = $p;
		
			//truckModel
			if (isset($p['truckModel']) && isset($p['truckBrand'])){
				$truckModel = db_selTruckModel(array('truckModelID' => $p['truckModel']));
				if (is_array($truckModel) && (count($truckModel) > 0)){
					$truckModel2 = array();
					foreach($truckModel as $key => $kVal){
						array_push($truckModel2, $kVal['truckBrandID']);
					}
					
					$truckBrand2 = array();
					foreach ($p['truckBrand'] as $key => $kVal){
						if (!in_array($kVal, $truckModel2)){
							array_push($truckBrand2, $kVal);
						}	
					}	
	
					$p['truckBrand'] = $truckBrand2;
				}
			}
		
			$truckAds = db_selTruckAds($p);
			if (!is_array($truckAds) || ($truckAds == false)){
				$this -> view -> info = $lang['ERR_4'];
			}
			elseif (is_array($truckAds) && (count($truckAds) < 1)){
				$this -> view -> info = $lang['ERR_4'];
			}else{
				$truckAdsP = array('totalAds'=>$truckAds['totalRows'], 'truckAds' => array()
									,'numAds'=>System_Properties::NUM_ADS, 'actPage' => $page);
				
				
				//For each truck ad select the correspondend truck picutres
				foreach ($truckAds AS $key => $truckAd){
					if (is_numeric($key)){
						$truckAd['truckPics'] = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV
																, 'vID' => $truckAd['truckID']
																, 'vPicTMP' => '0'
															)
														);					
						array_push($truckAdsP['truckAds'], $truckAd);
					}				
				}
				$truckAds = $truckAdsP;
			}
			
			$this -> view -> truck = $truckAds;
		}
		
	}
	private function loadTruckModelsBrands($p = null){
		include_once ('default/models/truck/db_selTruckBrand.php');
		include_once ('default/models/truck/db_selTruckModel.php');
		
		$truckBrand = db_selTruckBrand(array('orderby'=>array(array('col' => 'brandName'))));
		
		$truckModel = false;
		/*
		if(is_array($truckBrand) && (count($truckBrand) > 0)){
			$truckBrandID = $truckBrand[0]['truckBrandID'];
			if (isset($p['truckBrand'])){
				$truckBrandID = $p['truckBrand'];
			}
			$truckModel = db_selTruckModel(array('truckBrandID' => $truckBrandID));
		}
		*/
		
		if(is_array($truckBrand) && (count($truckBrand) > 0)){
			$truckBrandID = $truckBrand[0]['truckBrandID'];
			if (isset($p['truckBrand']) && !is_array($p['truckBrand'])){
				$truckBrandID = $p['truckBrand'];
			}
			elseif (isset($p['truckBrand']) && is_array($p['truckBrand']) && (count($p['truckBrand']) > 0)){
				$truckBrandID = array();
				foreach ($p['truckBrand'] as $key => $val){
					array_push($truckBrandID, $val);
				}
			}
				$truckModel = db_selTruckModel(array('truckBrandID' => $truckBrandID
												, 'orderby' => array(
													array('col' => 'lft')
													, array('col'=>'truckModelName'))
											));
		}
		$this -> view -> truckBrand = $truckBrand;
		$this -> view -> truckModel = $truckModel;
	}
}
?>