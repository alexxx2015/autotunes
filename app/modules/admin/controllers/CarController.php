<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101216
 * Desc:		This is the administrator controller for maintanence groups
 *********************************************************************************/
require_once('classes/AbstractController.php');
class Admin_CarController extends AbstractController{
	private $actParam;
	
	
	public function preDispatch(){
		parent::preDispatch();			
				
		
		if (!isset($this -> adminNS -> adminData)
			|| !isset($this -> adminNS -> adminLogged)
			|| ($this -> adminNS -> adminLogged != true)) {
			$this -> _forward('index', 'notlogged');
		}
		
	
		$action = $this -> getRequest() -> getActionName();
		$req = $this -> getRequest();
		//Check Authority for "CAR_EDIT" action
		if(($action == 'detail') && ($req -> __isset('carSafe'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		elseif(($action == 'aful')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		elseif(($action == 'agfe')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		//Check Authority for "CAR_DELETE" action
		elseif(($action == 'detail') && ($req -> __isset('carDel'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "CAR_CREATE" action
		else if(($action == 'insert') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
	
		//check authority CAR_EXTRA_CREATE
		else if(($action == 'carextra') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EXTRA_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority CAR_EXTRA_EDIT
		else if(($action == 'carextraedit') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EXTRA_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority CAR_EXTRA_ERASE
		else if(($action == 'carextraerase') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EXTRA_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
	
		//check authority CAR_CAT_CREATE
		else if(($action == 'carcat') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CAT_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority CAR_CAT_EDIT
		else if(($action == 'carcatedit') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CAT_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority CAR_CAT_ERASE
		else if(($action == 'carcaterase') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CAT_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this->tmpl;//$this -> getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;			
	}
	
	public function indexAction(){
		$this -> loadCarModelsBrands();
	}
	
	
	/**
	 * This function load car brands and their corresponding car modelss
	 */
	private function loadCarModelsBrands($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
		
		$carBrand = db_selCarBrand(array('orderby'=>array(array('col' => 'brandName'))
											,'active' => 1));
		$carModel = false;
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
												, array('col'=>'carModelName')
											)));
		}
		
		$this -> view -> carBrand = $carBrand;
		$this -> view -> carModel = $carModel;
	}
	
	/**
	 * This AJAX method return for a specific car brand the corresponding car models
	 * Input parameter:
	 * @param	cid:	this parameter specify the car id
	 * 
	 * Output parameter:
	 * @param	r:	indicate if a request ist successfully processed (true) or not (false)
	 * @param	cm: compose with the name and id of a car model orderer by model name
	 * 			@param	cmn:	car model name
	 * 			@param	cmid:	car model id
	 */
	public function ajagmAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$req = $this -> getRequest();
		
		$return = array('r' => false, 'cm' => array());
		
		$r_carBrandID = $req -> getParam('cid');
		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		$carBrand = db_selCarBrand(array('carBrandID' => $r_carBrandID, 'active'=>'1'));
		
		if (($carBrand != false) && is_array($carBrand)){
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
			$carModels = db_selCarModel(array(	'carBrandID' => $carBrand[0]['carBrandID'],
												'orderby' => array(
															array('col' => 'lft')
															, array('col' => 'carModelName')
												)
										)
						);
			if (($carModels != false) && is_array($carModels)){
				$return['r'] = true;
				foreach ($carModels as $carModel) {
					$cm = array('cmn' => $carModel['carModelName'],
								'cmid' => $carModel['carModelID']
								, 'cml' => $carModel['level']);
					array_push($return['cm'], $cm);
				}
			}
		}	
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	
	public function searchAction(){
		$this -> resetnsAction();
		$req = $this -> getRequest();
		$lang = $this -> lang;
		$p = $req -> getParams();
		$carSearchParam = $this -> adminNS -> carSearchParam;
		
		//Determine page
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		//New Search
		if (isset($p['search1'])){
			$p = $req -> getParams();
			$this -> adminNS -> carSearchParam = $p;
		}elseif (is_array($carSearchParam)){
			$p = $carSearchParam;
		}		
				
		//Search car advertisement
		if (isset($p['adNum']) && is_numeric($p['adNum'])){
			$carAds = $this -> searchCarAds(array('carID' => $p['adNum']));
		}
		else{				
			//Sort options
			if (isset($p['carSort']) && ($p['carSort'] != -1)){
				$p['orderby'] = array();
				
				//Determine col
				switch ($p['carSort']) {
					//Price
					case 0: $col = 'carPrice';
							break;						
					//Leistung
					case 1: $col = 'carPower';
							break;
					//Laufleistung
					case 2: $col = 'carKM';
							break;
					//Datum
					case 3: $col = 'timestam';
							break;
					default: $col = 'carPrice';
							break;
				}
				
				//Determine sort orientation
				$desc = false;
				if (isset($p['carSortOpt']) && ($p['carSortOpt'] == 1)){
					$desc = true;
				}
				
				array_push($p['orderby'], array('col' => $col
												, 'desc' => $desc
												)
											);
			}
			
			$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
			$carAds = $this -> searchCarAds($p);
		}
				
		//Search process successfully passed and found some matches			
		if (is_array($carAds) && (count($carAds) > 0)){
			//Check if javascript is active
			if(isset($p['jsActive']) && ($p['jsActive'] == 'on')){
				$carAds['jsActive'] = $p['jsActive'];
			}
			
			$carAds['numAds'] = System_Properties::NUM_ADS;
			$carAds['actPage'] = $page;
			
			$this -> actParam = $p;
			$this -> view -> searchParam = $p;
			
			$this -> view -> carAds = $carAds;
		}		
		else{
			$this -> view -> error = $lang['ERR_4'];
			
			$this -> actParam = $p;
			$this -> view -> searchParam = $p;				
			
			//$this -> indexAction();
			//$this -> render('index');
		}
		$this -> loadCarModelsBrands($p);		
	}
	
	/**
	 * This function perform an update of a car advertisement
	 */
	private function updatecarAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCar2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCar2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_delVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_updVPic.php');
		
		$p = $this -> filterRecvParam($p);
		
		if (!isset($p['error'])) {
			$p['erased'] = 0;
			$carID = $p['carID'];
			$updCar = db_updCarAds(array(System_Properties::SQL_SET => $p
										, System_Properties::SQL_WHERE => array('carID' => $carID)
										));
			
			//update car extra
			db_delCar2Ext(array('carID' => $p['carID']));
			if(isset($p['carExtDB']) && is_array($p['carExtDB'])){
				foreach ($p['carExtDB'] as $key=>$kVal){
					db_insCar2Ext(array('carID'=>$p['carID']
										, 'carExtID'=>$kVal['carExtID']
										));
				}
			}
			
			//Update fotos
			$carPicNS = $this -> adminNS -> carPhoto;
			if (is_array($carPicNS)){						
									
				$vPicID = array();
				foreach ($carPicNS as $key => $kVal){
					array_push($vPicID, $kVal['vPicID']);
				}
				db_updVPic(array('vPicID' => $vPicID
								, 'vPicTMP' => '0'
								));
								
				$notUpdPic = db_selVPic(array('notVPicID' => $vPicID
											, 'vID' => $carID
											));
											
				if (($notUpdPic != false) && is_array($notUpdPic) && (count($notUpdPic) > 0)){
					$vPicID = array();
					foreach ($notUpdPic as $key => $kVal) {
						array_push($vPicID, $kVal['vPicID']);
					}
					db_delVPic(array('vPicID'=>$vPicID));
				}
				
				/*
				foreach ($carPicNS as $cpns){
					//Should a photo be deleted?
					if (isset($cpns['del']) && ($cpns['del'] == true ) && ($cpns['vID'] == $p['carID'])){
						$db_delVPic = db_delVPic(array('vPicID' => $cpns['vPicID']));
						if ($db_delVPic != false){							
							$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$cpns['vID'].'_'.$cpns['vPicID'].'.jpeg';
							if (file_exists($srcFileURI)){
								unlink($srcFileURI);
							}
						}
					}
					//Else perfrom an update and set temporary flag to false
					//so this picture will be shown in advertisment details
					else{
						db_updVPic(array('vPicID' => $cpns['vPicID']
										, 'vPicTMP' => '0'
										)
									);
					}
				}
				*/
			}
		}
		return $p;
	}	
	private function searchCarAds($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
	
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
		
		$p['priceWMwst'] = true;
		
		$carAds = db_selCarAds($p);
		//Search process successfully passed and found some matches
		if (is_array($carAds) && ($carAds != false)){
			$carAdsP = array('totalAds'=>$carAds['totalRows'], 'carAds' => array());
			/*
			//Check if javascript is active
			if(isset($p['jsActive']) && ($p['jsActive'] == 'on')){
				$carAdsP['jsActive'] = $p['jsActive'];
			}
			*/
			
			//For each car ad select the correspondend car picutres
			foreach ($carAds AS $key => $carAd){
				if (is_numeric($key)){
					$carAd['carPics'] = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV,
															'vID' => $carAd['carID']));					
					array_push($carAdsP['carAds'], $carAd);
				}				
			}
			$carAds = $carAdsP;
					/*
			$carAdsP['numAds'] = System_Properties::NUM_ADS;
			$carAdsP['actPage'] = $page;
			$this -> view -> carAds = $carAdsP;		
			$this -> render('search2');
		}		
		else{
			$lang = $this -> view -> tmpl -> getLang();
			$this -> view -> error = $lang['ERR_4'];
			$this -> search1Action();*/
		}
		return $carAds;
	}
	
	/**
	 * This function load all car brands and models
	 */
	public function detailAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCar2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		$req = $this -> getRequest();
		$lang = $this -> lang;
		$p = $req -> getParams();
		
		if (isset($p['id'])){
			$carDetails = db_selCarAd(array('carID' => $p['id']));
			if (($carDetails != false) && is_array($carDetails) && (count($carDetails) == 1)){				
				$carDetails = $carDetails[0];
				
				if (is_array($this -> adminNS -> carAds) && isset($this -> adminNS -> carAds['carID'])
					&& ($this -> adminNS -> carAds['carID'] !=  $carDetails['carID'])){
					$this -> resetnsAction();
				}
				
				//Safe changes
				if (isset($p['carSafe'])){
					$p['carID'] = $carDetails['carID'];
					$p['carBrandID'] = $p['carBrand'];
					$p = $this -> updatecarAction($p);
					if (isset($p['error'])){
						$this -> view -> error = $p['error'];
					}else{
						$carDetails2 = db_selCarAd(array('carID' => $p['id']));
						if (($carDetails2 != false) && is_array($carDetails2) && (count($carDetails2) == 1)){
							$carDetails  = $carDetails2[0];
						}
						$this -> view -> info = $lang['AINFO_4'];
					}
				}
				//Delete advertisement
				else if (isset($p['carDel'])){
					$carAd = db_updCarAds(array(System_Properties::SQL_WHERE => array('carID' => $carDetails['carID'])
												, System_Properties::SQL_SET => array('erased' => 1)
												)
											);
					if ($carAd != false){
						$this -> _forward('index');
					}
				}
				//Load all car extra
				$this -> loadCarExt();
				
				//Load car extra
				$carExt = db_selCar2Ext(array('carID' => $carDetails['carID']));
				if (($carExt != false) && is_array($carExt) && (count($carExt) > 0)){
					$carDetails['carExt'] = array();
					foreach ($carExt as $key => $val){
						array_push($carDetails['carExt'], $val['vextID']);
					}					
				}				
				
				//Fetch all car pics
				if (isset($this -> adminNS -> carPhoto) && is_array($this -> adminNS -> carPhoto)){
					$carPhoto = $this -> adminNS -> carPhoto;
				}	
				else{
					$carPhoto = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV,
													'vID' => $carDetails['carID']));
					if (is_array($carPhoto)){
						$carDetails['carPhoto'] = array();
						foreach($carPhoto as $key => $kVal){
							$carDetails['carPhoto'][$kVal['vPicID']] = $kVal;
						}
						$carPhoto = $carDetails['carPhoto'];
					}
				}
				$carDetails['carPhoto'] = $carPhoto;
				
				//Load brands and models				
				$this -> loadCarModelsBrands(array('carBrand' => $carDetails['carBrandID']));
				//Load all possible car extra
				$this -> loadCarExt();
				$this -> loadCarCat();
				$this -> view -> car = $carDetails;
				
				$this -> adminNS -> carAds = $carDetails;
				
				$this -> adminNS -> carPhoto = $carPhoto;
			}else{
				$this -> indexAction();
				$this -> render('index');
			}
		}else{
			$this -> indexAction();
			$this -> render('index');
		}
	}
	/**
	 * This function load all car categories.
	 */
	private function loadCarCat(){
		include_once ('default/models/car/db_selCarCat.php');
		$carCat = db_selCarCat();
		if($carCat != false){
			$this -> view -> carCat = $carCat;
		}
		return $carCat;
	}
	/**
	 * This function load all car extras.
	 */
	private function loadCarExt(){
		/*
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_selExtra.php');
		$carExt = db_selExtra(array('vType' => 'c'));
		if($carExt != false){
			$this -> view -> carExt = $carExt;
		}
		*/
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		$carExt = db_selCarExt(array('lftBE'=>1));
		if($carExt != false){
			$this -> view -> carExt = $carExt;
		}
	}
	
	/**
	 * This function filter a car advertisement
	 * @param $p:	this variable contains the parameter of a car advertisement
	 */
	private function filterRecvParam($p){
		$carCatDB = $this -> loadCarCat();
		$lang = $this -> lang;
		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
		
		include_once('default/views/filters/FilterMySQLInt.php');
		include_once('default/views/filters/FilterMySQLMInt.php');
		include_once('default/views/filters/FilterMonth.php');
		include_once('default/views/filters/FilterYear.php');
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterString20.php');
		include_once('default/views/filters/FilterIsEmptyString.php');
		
		$fInt = new FilterMySQLInt();
		$fMInt = new FilterMySQLMInt();
		$fMonth = new FilterMonth();
		$fYear = new FilterYear();
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();
		$fIsEmptyStr = new FilterIsEmptyString();
		$carBrand = db_selCarBrand(array('carBrandID'=>$p['carBrand']
									, 'active' => 1));	
		
		//Check carBrand
		if (!isset($p['carBrand'])
			|| ($carBrand == false)
			){
				$p['error'] = $lang['ERR_2'];
		}
		//Check carPrice
		else if (!isset($p['carPrice']) || ($fInt -> isValid($p['carPrice']) == false)){
				$p['error'] = $lang['ERR_2'];
		}
		//Check carPower
		else if (!isset($p['carPower']) || ($fMInt -> isValid($p['carPower']) == false)){
				$p['error'] = $lang['ERR_2'];				
		}
		//Check carKM
		else if (!isset($p['carKM']) || ($fMInt -> isValid($p['carKM']) == false)){
				$p['error'] = $lang['ERR_2'];
		}
		//Check carEZ
		else if (!isset($p['carEZM']) || !isset($p['carEZY'])
				|| ($fMonth -> isValid($p['carEZM']) == false)
				|| ($fYear -> isValid($p['carEZY']) == false)){
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
		//Check userNName
		else if (isset($p['userAds']) && ($p['userAds'] == 2) 
				&& ( !isset($p['userNName']) || ($fString100 -> isValid($p['userNName']) == false)) ){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userVName
		else if (isset($p['userAds']) && ($p['userAds'] == 2) 
				&& ( !isset($p['userVName']) || ($fString100 -> isValid($p['userVName']) == false)) ){
			$p['error'] = $lang['ERR_2'];
		}
		//Check additional user information, if vendor is a vehicle dealer
		elseif (isset($p['userAds']) && ($p['userAds'] == 1)
				&& ($fString100->isValid($p['userFirm']) == false
					|| $fIsEmptyStr->filter($p['userFirm']) == true)){
			$p['error'] = $lang['ERR_54'];
		}
		elseif (isset($p['userAds']) && ($p['userAds'] == 1)
				&& ($fString100->isValid($p['userTel1']) == false
					|| $fIsEmptyStr->filter($p['userTel1']) == true)){
			$p['error'] = $lang['ERR_55'];
		}
		elseif (isset($p['userAds']) && ($p['userAds'] == 1)
				&& ($fString20->isValid($p['userPLZ']) == false
					|| $fIsEmptyStr->filter($p['userPLZ']) == true)){
			$p['error'] = $lang['ERR_56'];
		}
		elseif (isset($p['userAds']) && ($p['userAds'] == 1)
				&& ($fString100->isValid($p['userOrt']) == false
					|| $fIsEmptyStr->filter($p['userOrt']) == true)){
			$p['error'] = $lang['ERR_57'];
		}
		elseif (isset($p['userAds']) && ($p['userAds'] == 1)
				&& ($fString100->isValid($p['userAdress']) == false
					|| $fIsEmptyStr->filter($p['userAdress']) == true)){
			$p['error'] = $lang['ERR_58'];
		}
		else{
			/*
			//Include and instantiate diverse filter
			include_once('default/views/filters/FilterMySQLSInt.php');
			include_once('default/views/filters/FilterMySQLTInt.php');
			include_once('default/views/filters/FilterString20.php');
			include_once('default/views/filters/FilterString50.php');
			include_once('default/views/filters/FilterString1000.php');
			$fSInt = new FilterMySQLSInt();
			$fTInt = new FilterMySQLTInt();
			$fString20 = new FilterString20();
			$fString50 = new FilterString50();
			$fString1000 = new FilterString1000();
			
			$p = $req -> getParams();
			
			//Check Model
			include_once('default/models/car/db_selCarModel.php');				
			$carModel = db_selCarModel(array(	'carBrandID' => $carBrand[0]['carBrandID'],
												'carModelID' => $req -> getParam('carModel'))
										);
			$p['carModelTxt'] = null;
			if($carModel != false){
				$p['carModelTxt'] = $carModel[0]['carModelName'];
			}
			
			$p['carBrandTxt'] = $carBrand[0]['brandName'];
			$p['carPriceType'] = $fTInt->filter($p['carPriceType']);
			$p['carPowerType'] = $fMInt->filter($p['carPowerType']);
			$p['carTUVM'] = $fMonth->filter($p['carTUVM']);
			$p['carTUVY'] = $fYear->filter($p['carTUVY']);
			$p['carAUM'] = $fMonth->filter($p['carAUM']);
			$p['carAUY'] = $fYear->filter($p['carAUY']);
			$p['carShift'] = $fTInt->filter($p['carShift']);
			$p['carWeight'] = $fMInt->filter($p['carWeight']);
			$p['carCyl'] = $fTInt->filter($p['carCyl']);
			$p['carCub'] = $fSInt->filter($p['carCub']);
			$p['carDoor'] = $fTInt->filter($p['carDoor']);
			$p['carUseIn'] = $fString50->filter($p['carUseIn']);
			$p['carUseOut'] = $fString50->filter($p['carUseOut']);
			$p['carCO2'] = $fString50->filter($p['carCO2']);
			$p['carState'] = $fTInt->filter($p['carState']);
			$p['carCat'] = $fTInt->filter($p['carCat']);
			$p['carFuel'] = $fTInt->filter($p['carFuel']);
			$p['carClr'] = $fTInt->filter($p['carClr']);
			//$p['carClrMet'] = $fTInt->filter($p['carClrMet']);
			$p['carEmissionNorm'] = $fTInt->filter($p['carEmissionNorm']);
			$p['carEcologicTag'] = $fTInt->filter($p['carEcologicTag']);
			$p['carKlima'] = $fTInt->filter($p['carKlima']);
			$p['carDesc'] = $fString1000->filter($p['carDesc']);
			$p['userNName'] = $fString100->filter($p['userNName']);
			$p['userVName'] = $fString100->filter($p['userVName']);
			$p['userPLZ'] = $fString20->filter($p['userPLZ']);
			$p['userOrt'] = $fString100->filter($p['userOrt']);
			$p['userTel1'] = $fString100->filter($p['userTel1']);
			$p['userTel2'] = $fString100->filter($p['userTel2']);
			$p['userAdress'] = $fString100->filter($p['userAdress']);
			*/
			
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
			
			$p['carBrandID'] = $carBrand[0]['carBrandID'];
			
			//Check Model
			$p['carModelTxt'] = null;
			if (isset($p['carModel']) && ($p['carModel'] != -1)){				
				$carModel = db_selCarModel(array(	'carBrandID' => $carBrand[0]['carBrandID'],
													'carModelID' => $p['carModel'])
											);
				if(($carModel != false) && is_array($carModel) && (count($carModel) > 0)){
					$carModel = $carModel[0];
					$p['carModelTxt'] = $carModel['carModelName'];
					$p['carModelID'] = $carModel['carModelID'];
				}
			}else{
				$p['carModel'] = -1;
			}
			
			if (isset($p['carModelVar'])){
				$p['carModelVar'] = $fString100 -> filter($p['carModelVar']);
			}else{
				$p['carModelVar'] = '';
			}
			
			$p['carBrandTxt'] = $carBrand[0]['brandName'];
			
			if(!isset($p['carPriceType']) || ($p['carPriceType'] == null)){
				$p['carPriceType'] = 0;
			}else{
				$p['carPriceType'] = $fTInt->filter($p['carPriceType']);
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
			if(isset($p['mwst']) && ($p['mwst'] !== '0')){
				$p['mwst'] = 1;
			}
			if(isset($p['mwstSatz']) && isset($lang['V_MWST']) && is_array($lang['V_MWST'])){
				$p['mwstSatz'] = str_replace(',', '.', $p['mwstSatz']);
				if(!in_array($p['mwstSatz'], $lang['V_MWST']) && ($p['mwst'] === '0')){
					$p['mwstSatz'] = 19;
				}
				
			}
			
			if(!isset($p['carPowerType']) || ($p['carPowerType'] == null)){
				$p['carPowerType'] = 0;
			}else{
				$p['carPowerType'] = $fMInt->filter($p['carPowerType']);
			}
			
			if(!isset($p['carKMType']) || ($p['carKMType'] == null)){
				$p['carKMType'] = 0;
			}else{
				$p['carKMType'] = $fTInt->filter($p['carKMType']);
				if (!isset($lang['TXT_75'][$p['carKMType']])){
					$p['carKMType'] = 0;
				}
			}
		
			if (isset($p['carHSN'])){
				$p['carHSN'] = $fString10 -> filter($p['carHSN']);
			}
		
			if (isset($p['carTSN'])){
				$p['carTSN'] = $fString10 -> filter($p['carTSN']);
			}
		
			if (isset($p['carFIN'])){
				$p['carFIN'] = $fString20 -> filter($p['carFIN']);
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
			
			if(!isset($p['carDoor']) || ($p['carDoor'] == null)){
				$p['carDoor'] = -1;
			}else{
				$p['carDoor'] = $fTInt->filter($p['carDoor']);
			}
			
			$p['carUseIn'] = $fString50->filter($p['carUseIn']);
			$p['carUseOut'] = $fString50->filter($p['carUseOut']);
			$p['carCO2'] = $fString50->filter($p['carCO2']);
			
			if(!isset($p['carState']) || ($p['carState'] == null)){
				$p['carState'] = -1;
			}else{
				$p['carState'] = $fTInt->filter($p['carState']);
			}
			
			if(!isset($p['carCat']) || ($p['carCat'] == null)){
				$p['carCat'] = -1;
			}else{
				$found = false;
				$p['carCat'] = $fTInt->filter($p['carCat']);
				foreach ($carCatDB as $key=>$kVal){
					if ($kVal['carCatID'] == $p['carCat']){
						$found = true;
						break;						
					}
				}
				if ($found == false){
					$p['carCat'] = -1;
				}
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
			
			if(!isset($p['carEEK']) || ($p['carEEK'] == null) || !isset($lang['V_EEK'][$p['carEEK']])){
				$p['carEEK'] = -1;
			}
			
			$p['carDesc'] = $fString1000->filter($p['carDesc']);
			
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
			
			//check carLocOrt
			//isset($p['carLocOrt']) ? $p['carLocOrt'] = $fString100->filter($p['carLocOrt']) : $p['carLocOrt'] = '';
			if ( isset($p['carLocPLZ']) ){
				$p['carLocPLZ'] = $fString20->filter($p['carLocPLZ']);
			}
			
			//Check carLocOrt
			if ( isset($p['carLocOrt']) ){
				$p['carLocOrt'] = $fString100->filter($p['carLocOrt']);
			}
			
			//Check carLocCountry
			if ( !isset($p['carLocCountry']) || !isset($lang['COUNTRY'][$p['carLocCountry']])){
				$p['carLocCountry'] = 'DE';
			}
			
			//check userAdsLength
			if (!isset($p['userAdsLength']) || !in_array($p['userAdsLength'], $lang['USER_ADS_LENGTH']) ){
				$p['userAdsLength'] = $lang['USER_ADS_LENGTH'][count($lang['USER_ADS_LENGTH'])-1];
			}
			
			//Car Extra
			$carExt = '';
			if (isset($p['carExt'])){
				include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');			
				$carExt = db_selCarExt(array('vextID'=>$p['carExt']));
			}
			$p['carExtDB'] = $carExt;
		}
		return $p;
	}
	
	/**
	 * This is an ajax complete function which save an uploaded photo
	 * Input paramter: 
	 * @param	cid:	this parameter specify the idetifier of the car advertisement 
	 * 
	 * Output parameter: 
	 * @param	r:	indicate if a request is successfully processed (true) or not (false)
	 * @param	tu: this parameter specify the URL to the uploaded picture
	 * @param	h: 	this parameter specify the hash code of modified picture
	 */
	public function ajafulAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAd.php');
		
		//Initialize returning parameter
		$return = array('r' => false);
		$req = $this -> getRequest();
		$adminDetails = $this -> adminNS -> adminData;
		if ($req -> __isset('cid') && $req->__isset('t')){
			$carID = $req -> getParam('cid');
			$carDetails = db_selCarAd(array('carID' => $carID));
			if (($carDetails != false)){
				$uploadRes = $this -> uploadPhoto(array('carID' => $carDetails[0]['carID']
														, 'carDetail' => $carDetails
														)
													);
				if (($uploadRes != false) 
					&& is_array($uploadRes)
					&& isset($uploadRes['r'])
					&& ($uploadRes['r'] == true)){
					
					if (!isset($this -> adminNS -> carPhoto) || !is_array($this -> adminNS -> carPhoto)){
						$this -> adminNS -> carPhoto = array();
					}
					$this -> adminNS -> carPhoto[$uploadRes['carPhoto']['vPicID']] = $uploadRes['carPhoto']; 
					//array_push($this -> adminNS -> carPhoto, $uploadRes['carPhoto']);
					
					
					$return['r'] = true;		
					$return['h'] = $uploadRes['carPhoto']['vPicID'];
					$return['cid'] = $carID;
					$return['t'] = $req->getParam('t');
					$return['tu'] = $uploadRes['carPhoto']['destURI'];				
				}
			}
		}
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}

	
	
	/**
	 * This function erase an uploaded car picture while inserting a car advertisement
	 * 
	 * Input parameter:
	 * @param pid:		this parameter specify the temporary car picture id
	 * 
	 * Output parameter:
	 * @param r:		this parameter specify if that the request is processed successfully
	 * 
	 * 
	 */
	public function ajagfeAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_delVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		
		$return = array('r' => false);
		
		if (isset($this -> adminNS -> adminLogged) && ($this -> adminNS -> adminLogged == true)
			&& isset($this -> adminNS -> adminData) && is_array($this -> adminNS -> adminData)){	
			
			$vPicID = $this -> getRequest() -> getParam('pid');
			if (is_numeric($vPicID)){
				$vPic = db_selVPic(array('vPicID' => $vPicID));
				if (($vPic != false) && is_array($vPic) && (count($vPic) > 0)){
					$vPic = $vPic[0];
							
					$newCarPhoto = array();
					$carPhoto = $this -> adminNS -> carPhoto;
					foreach ($carPhoto as $key=>$val){
						if ($key != $vPicID){
							$newCarPhoto[$key] = $val;
						}else{							
							//delete picutres from filesystem
							if(isset($vPic['vType']) && isset($vPic['vID']) && isset($vPic['vPicID'])){
								$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($vPic['vType']).'_'.$vPic['vID'].'_'.$vPic['vPicID'].'.jpeg';
								@unlink($picPath);
							}
						}
					}
					
					$this -> adminNS -> carPhoto = $newCarPhoto;
					$return['r'] = true;
					$return['pid'] = $vPicID;
				}
			}
		}
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	private function obsolete_agfeAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		
		$return = array('r' => false);
		$adminDetails = $this -> adminNS -> adminData;
		
		if (isset($this -> adminNS -> carAds) && is_array($this -> adminNS -> carAds)
			&& isset($this -> adminNS -> carAds['carID'])){
				
			$carDetails = db_selCarAd(array('carID' => $this -> adminNS -> carAds['carID']));
			if (($carDetails != false) && is_array($carDetails) && (count($carDetails) == 1) ){
					
				$carDetails = $carDetails[0];
				$vPicID = $this -> getRequest() -> getParam('pid');
				
				$vPicDetails = db_selVPic(array('vPicID' => $vPicID
												, 'vID' => $carDetails['carID']
												)
										);				
				//If it is a temporary picture so delete origintal picture from file system
				if (($vPicDetails != false) && is_array($vPicDetails)){
					$vPicDetails = $vPicDetails[0];
					/*
					if (isset($vPicDetails['vPicTMP']) && ($vPicDetails['vPicTMP'] == 1)){					
						$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.$vPicDetails['vID'].'_'.$vPicDetails['vPicID'].'.jpeg';
						if (file_exists($srcFileURI)){
							unlink($srcFileURI);
						}
					}

					$newCarPhoto = array();
					$carPhoto = $this -> adminNS -> carPhoto;
					foreach ($carPhoto as $key=>$val){
						if ($val['vPicID'] != $vPicID){
							$newCarPhoto[$val['vPicID']] = $val;
						}
					}
					$this -> adminNS -> carPhoto = $newCarPhoto;
					*/
					
					$carPhotoNS = $this -> adminNS -> carPhoto;			
					$this -> adminNS -> carPhoto = array();
					foreach ($carPhotoNS AS $carPhoto){
						if ($carPhoto['vPicID'] == $vPicDetails['vPicID']) {
							$carPhoto['del'] = true;
						}
						array_push($this -> adminNS -> carPhoto, $carPhoto);
					}
					
					$return['r'] = true;
				}				
				
				
				/*
				$vPic = $this -> deletePhoto(array('vPicID'=>$vPicID));
				if (($vPic != false) 
					&& isset($vPic['r']) && isset($vPic['vPic'])
					&& is_array($vPic) && is_array($vPic['vPic'])
					&& ($vPic['r'] == true)){
					$vPic = $vPic['vPic'];
					$newCarPhoto = array();
					$carPhoto = $this -> carNS -> carPhoto;
					foreach ($carPhoto as $key=>$val){
						if ($key != $vPic['vPicID']){
							$newCarPhoto[$key] = $val;
						}
					}
					$this -> carNS -> carPhoto = $newCarPhoto;
					
					$return['r'] = true;
				}*/
			}
		}
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	/**
	 * This function upload a photo.
	 * Parameter returned
	 * @param	r:	specify the result of the upload process.
	 * 				if upload processed unsuccessful then FALSE
	 * @param 	result: if upload is successfull this parameter contains all relevant 
	 * 					information from the uploaded photo
	 */
	private function uploadPhoto($p){				
		include_once('Zend/File/Transfer/Adapter/Http.php');
		include_once('default/views/filters/ImageFilter.php');	
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_delVPic.php');
		
		$return = array('r' => false);	
		$imgCtrl = new Zend_File_Transfer_Adapter_Http();
		
		//This is the car advertisement
		//$p = $this -> carNS -> carAds;			
		if(isset($p['carID']) && ($imgCtrl->isUploaded() == true)){
			if (!isset($p['carDetail'])){
				$carDetail = db_selCarAd(array('carID'=>$p['carID']));				
			}else{
				$carDetail = $p['carDetail'];
			}
			
			//Check if car advertisement exists
			if (($carDetail != false) && (count($carDetail) == 1)){
				$carDetail = $carDetail[0];
				$carID = $carDetail['carID'];										
				$vPicID = db_insVPic(array(	'vType' => System_Properties::CAR_ABRV
									 		, 'vID' => $carID
											, 'vPicTMP' => '1'
											)
										);
				if (($vPicID != false) && is_numeric($vPicID)){		
					$imgCtrl -> setOptions(array('useByteString'  => false));
					$imgCtrl -> addValidator('FilesSize', false, System_Properties::PIC_FILE_SIZE);
					$imgCtrl -> addValidator('Extension', false, System_Properties::$PIC_EXT);
					
					//$imgHash = session_id().'_'.$imgCtrl -> getHash();
					$destURI = System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$carID.'_'.$vPicID.'.jpeg';				
					$imgCtrl -> addFilter('Rename', array('target' => '.'.$destURI, 'overwrite' => true));
					
					//$mimeTypeDetails = explode('/',$imgCtrl -> getMimeType());
					$mimeTypeDetails = explode('.', basename($imgCtrl -> getFileName()));
					$mimeTypeDetails = $mimeTypeDetails[1];
					$imgFilter = new ImageFilter(array(	'imgTrgWidth' => System_Properties::PIC_SIZE_W,
														'imgTrgHeight' => System_Properties::PIC_SIZE_H,
														'imgSrcExtension' => $mimeTypeDetails));
					$imgCtrl -> addFilter($imgFilter);
					
					
					//Upload photo
					//if ($imgCtrl -> isUploaded() && $imgCtrl -> isValid()){
					//if (!isset($this -> carNS -> carPhoto[$imgHash]) && $imgCtrl -> receive()){	
					if (in_array(strtolower($mimeTypeDetails), System_Properties::$PIC_EXT) 
						&& ($imgCtrl -> receive())){
						$carPhoto = db_selVPic(array('vPicID' => $vPicID
													, 'vPicTMP' => '1'
													)
												);
						if($carPhoto != false){
							$carPhoto = $carPhoto[0];
							$carPhoto['destURI'] = $destURI;
							$carPhoto['vPicNew'] = true;
							$return['r'] = true;
							$return['carPhoto'] = $carPhoto;
						}						
					}
					else{
						db_delVPic(array('vPicID'=>$vPicID));
					}
				}
			}
		}
		
		return $return;		
	}
	
	private function resetnsAction(){
		/*
		$this -> adminNS -> carAds = null;
		$this -> adminNS -> carPhoto = null;
		$this -> adminNS -> carInsert = false;
		*/
		
		$this -> adminNS -> __unset('carAds');
		$this -> adminNS -> __unset('carPhoto');
		$this -> adminNS -> __unset('carInsert');		
	}
	
	public function insertAction(){
		//$this -> resetnsAction();
		$req = $this->getRequest();
		//First insert
		if ($req -> __isset('ins2') && (!isset($this -> adminNS -> carInsert) || ($this -> adminNS -> carInsert == false))){
			$this -> insert2Action();
		}
		//User confirm the correctness of the advertisment
		else if ($req -> __isset('ins3') && (!isset($this -> adminNS -> carInsert) || ($this -> adminNS -> carInsert == false))){
			$this -> insert3Action();
		}
		//Advertisment is committed to database and the user safe all fotos.
		else if (isset($this -> adminNS -> carInsert)
					&& ($this -> adminNS -> carInsert == true)
					&& isset($this -> adminNS -> carAds)
					&& ($req -> __isset('safeFoto'))){
			$this -> insert7Action();
		}
		//Advertisment is commited to database, so show the inserted advertisement
		else if (isset($this -> adminNS -> carInsert) 
					&& ($this -> adminNS -> carInsert == true) 
					&& isset($this -> adminNS -> carAds)){
			$this -> insert4Action();
		}
		else{
			$this -> resetnsAction();
			$this -> insert1Action();
		}
	}
	
	private function insert1Action(){
		$this -> loadCarModelsBrands();
		$this -> loadCarExt();
		$this -> loadCarCat();
		if (isset($this -> actParam) && ($this -> actParam != null)){
			$this -> loadCarModelsBrands(array('carBrand' => $this -> actParam['carBrand']));
			$this -> view -> car = $this -> actParam;
		}
		$this -> render('insert1');
	}
	
	private function insert2Action(){
		//First check the receveid parameters
		$p = $this -> filterRecvParam($this -> getRequest() -> getParams());
		$this -> actParam = $p;
		//Forward only if an error occurs
		if(isset($p['error'])){		
			//$this -> loadCarModelsBrands();
			$this -> view -> error = $p['error'];
			//$this -> render('insert1');
			//$this -> _forward('insert', null, null, array('error' => $error));
			$this -> insert1Action();
		}		
		else{			
			$this -> loadCarCat();
			//Set session namespace
			$this -> adminNS -> carAds = $p;			
			$this -> view -> car = $p;
			//$this -> view -> carPhoto = $this -> carNS -> carPhoto;
			
			$this -> render('insert2');	
		}	
	}
	
	private function insert3Action(){
		if(isset($this -> adminNS -> carAds)){
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarAds.php');
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCar2Ext.php');
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
			$p = $this -> adminNS -> carAds;
			$user = $this -> adminNS -> adminData;
			$this -> actParam = $p;
			$lang = $this -> lang;
			
			//Filter the car advertisement
			$p = $this -> filterRecvParam($p);
			
			if (!isset($p['error']) && is_array($user) && isset($user['userID'])){
				//Advertising is successful
				$p['userID'] = $user['userID'];
				$p['userAds'] = 3; //3 = System advertisment
				$carID = db_insCarAds($p);
				if(($carID != false) && is_numeric($carID)){
					//insert car extra if carExtDB extist
					if (isset($p['carExtDB']) && is_array($p['carExtDB'])){
						foreach ($p['carExtDB'] as $key=>$kVal){
							if (is_array($kVal) && isset($kVal['vextID'])){
								$carExt = db_selCarExt(array('vextID' => $kVal['vextID']));
								if (($carExt != false) && is_array($carExt) && (count($carExt) > 0)){
									$carExt = $carExt[0];
									if (isset($carExt['carExtID'])){
										db_insCar2Ext(array('carID'=>$carID
															, 'carExtID' =>$carExt['carExtID'] 
															));
									}
								}
							}
						}
					}				
						
					$p['carID'] = $carID;
					$this -> adminNS -> carAds = $p;
					
					//Set carInsert to true because the car advertisment is successfully inserted.
					$this -> adminNS -> carInsert = true;	
						
					$this -> view -> car = $p;	
					//$this -> view -> carPhoto = $this -> carNS -> carPhoto;
					$this -> render('insert3');
				}
				//Forward only if an error occurs
				else{
					if (!isset($p['error'])){
						$p['error'] = $lang['ERR_2'];
					}
					
					//$this -> loadCarModelsBrands();
					$this -> view -> error = $p['error'];
					$this -> insert1Action();
					//$this -> render('insert1');
					//$this -> _forward('insert', null, null, array('error' => $error));
				}
			}else{
				$this -> insert1Action();
			}		
		}
		else{
			//$this -> loadCarModelsBrands();
			//$this -> render('insert1');
			$this -> insert1Action();
		}
	}
	
	/**
	 * Processed if the user press the refresh button
	 */
	private function insert4Action(){		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		$car = $this -> adminNS -> carAds;
		$this -> actParam = $car;	
		$this -> loadCarCat();	
					
		if (isset($car['carID'])){
			$carPhotos = db_selVPic(array(	'vType'=>System_Properties::CAR_ABRV,
											'vID' => $car['carID']
										)
									);
			if (($carPhotos != false) && is_array($carPhotos) && (count($carPhotos) > 0)){
				$carPhotoNew = array();
				foreach($carPhotos as $key => $kVal){
					$carPhotoNew[$kVal['vPicID']] = $kVal;
				}
				$this -> adminNS -> carPhoto = $carPhotoNew;
			}
			
			/*
			if (($carPhotos != false) && is_array($carPhotos) && (count($carPhotos) > 0)){
				$this -> adminNS -> carPhoto = $carPhotos;
			}
			if (($carPhotos != false) && is_array($carPhotos) && (count($carPhotos) > 0)){
				if (is_array($this -> carNS -> carPhoto)){
					$this -> carNS -> carPhoto = array_merge($this -> carNS -> carPhoto, $carPhotos);
				}else{
					$this -> carNS -> carPhoto = $carPhotos;
				}
			}
			*/
		}
		$car['carPhoto'] = $this -> adminNS -> carPhoto;		
		$this -> view -> car = $car;
		$this -> render('insert3');			
	}
	
	/**
	 * This action controller insert all photos into database and close advertisment. It is finally closed.
	 * The user can't edit the advertisment anymore in the inserting process. But in the user cockipit it is possible to edit the ad.
	 */
	private function insert7Action(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_updVPic.php');
		
		if (isset($this -> adminNS -> carAds) && is_array($this -> adminNS -> carAds)			
			&& isset($this -> adminNS -> carAds['carID'])){
			$carAdsNS = $this -> adminNS -> carAds;
			$carDetails = db_selCarAd(array('carID' => $carAdsNS['carID']));
			if ($carDetails != false){
				$carDetails = $carDetails[0];
				
				if (isset($this -> adminNS -> carPhoto) && is_array($this -> adminNS -> carPhoto)){								
					foreach ($this -> adminNS -> carPhoto as $carPhoto){
						db_updVPic(array('vPicID' => $carPhoto['vPicID']
										, 'vPicTMP' => '0'
										)
									);
					}
				}
				$this -> resetnsAction();
				$this -> _redirect('/car/'.$carDetails['carID']);
			}else{
				$this -> resetnsAction();
				$this -> indexAction();
			}
		}else{
			$this -> resetnsAction();
			$this -> indexAction();
		}		
	}
	
	/**
	 * Manage car extra 
	 */
	public function carextraAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		
		//fetch all request parameters
		$p = $this -> getRequest() -> getParams();
		
		//insert a new car extra entry
		if (isset($p['carExtraNew'])){
			$this -> insertcarextAction($p);
		}
		
		$carExt = db_selCarExt();
		$vextID = null;
		if (($carExt != false) && is_array($carExt)){
			$vextID = array();
			foreach ($carExt as $key => $ceValue){
				array_push($vextID, $ceValue['vextID']);
			}
		}
		
		$vextra = db_selVExtra(array('notVextID'=>$vextID));		
		
		$this -> view -> vextra = $vextra;
		$this -> view -> carExt = $carExt;
	}
	
	private function insertcarextAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarExt.php');
		
		$lang = $this -> lang;
		
		//Get vehicle extra details for new extra
		$vextraNew = null;
		$vextraParent = null;
		if (isset($p['vextraNew'])){
			$vextraNew = db_selVExtra(array('vextID'=>$p['vextraNew']));
			$carExt = db_selCarExt(array('vextID'=>$p['vextraNew']));
			if ($carExt != false){
				$vextraNew = null;
			}
		
			//get vehicle extra details for parent extra
			if ($p['vextraParent'] != $p['vextraNew']){
				if (isset($p['vextraParent']) && ($p['vextraParent'] == -1)){
					$vextraParent = db_selCarExt(array('lft'=>1));
					if ($vextraParent == false){
						db_insCarExt(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vextID' => 0));						
						$vextraParent = db_selCarExt(array('lft'=>1));
					}
				}else{
					$vextraParent = db_selCarExt(array('vextID' => $p['vextraParent']));
				}
			}else{
				$this -> view -> error = $lang['AERR_31'];
			}
		}
		
		if (($vextraNew != null) && ($vextraParent != null)
			&& is_array($vextraNew) && is_array($vextraParent)){
			$vextraParent = $vextraParent[0];
			$vextraNew = $vextraNew[0];
			db_updCarExt(array(System_Properties::SQL_SET => array('incLft'=>2)
							, System_Properties::SQL_WHERE => array('lftBEq'=>$vextraParent['rgt'])
								)
						);
			db_updCarExt(array(System_Properties::SQL_SET => array('incRgt'=>2)
							, System_Properties::SQL_WHERE => array('rgtBEq'=>$vextraParent['rgt'])
								)
						);
			db_insCarExt(array('lft'=>$vextraParent['rgt']
							, 'rgt' => ($vextraParent['rgt'] +1)
							, 'active' => 1
							, 'vextID' => $vextraNew['vextID']
								)
						);
			$this -> view -> info = $lang['AINFO_12'];
		}				
	}

	/**
	 * Insert a new car extra
	 */
	public function carextraeditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		//determine car vehicle extra id
		$carVextID = null;
		if (isset($p['ceid'])){
			$carVextID = $p['ceid'];
		}
		
		if ($carVextID != null){
			$carVext = db_selCarExt(array('carExtID' => $carVextID));
			if (($carVext != false) && is_array($carVext)){
				$carVext = $carVext[0];
		
				//Edit car extra
				if (isset($p['carExtEdit'])){
					$p['carExtID'] = $carVextID;
					$p['carExt'] = $carVext;
					$this ->editcarextraAction($p);
					$carVextH = db_selCarExt(array('carExtID' => $carVextID));
					if (($carVextH != false) && is_array($carVextH) && (count($carVextH) > 0)){
						$carVext = $carVextH[0];
					}
				}
				
				$this -> view -> carVext = $carVext;
				if ($carVext['level']-1 > 0){
					$carVextParent = db_selCarExt(array('level' => ($carVext['level']-1)
														, 'lftLEq' => $carVext['lft']
														, 'rgtBEq' => $carVext['rgt']
														)
												);
					if (($carVextParent != false) && is_array($carVextParent) && (count($carVextParent) > 0)){													
						$this -> view -> carVextParent = $carVextParent[0];
					}
				}
				
				$carVextAll = db_selCarExt();
				$this -> view -> carVextAll = $carVextAll;
					
			}else{
				$this -> view -> error = $lang['AERR_32'];
				$this -> _forward('carextra');
			}
		}else{
			$this -> view -> error = $lang['AERR_32'];
			$this -> _forward('carextra');
		}
	}
	
	private function editcarextraAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarExt.php');
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$lang = $this -> lang;
		$vextraParent = null;
		$vextraParentAct = null;
		$carExt = null;
		
		
		if (isset($p['carExt']) && is_array($p['carExt']) && isset($p['carExt']['lft']) && isset($p['carExt']['rgt'])){
			$carExt = $p['carExt'];
			//determine children from current car extra
			$carExtChild = db_selCarExt(array('lftBEq' => $carExt['lft']
											, 'rgtLEq' => $carExt['rgt']
											)
										);			
			
			//determine new specified vehicle extra parent
			if (isset($p['vextraParent']) && ($p['vextraParent'] != $carExt['carExtID'])) {
				if ($p['vextraParent'] < 1){
					$vextraParent = db_selCarExt(array('lft'=>'1'));
				}else{
					$vextraParent = db_selCarExt(array('carExtID'=>$p['vextraParent']));
				}
								
				if (($vextraParent != false) && is_array($vextraParent) && (count($vextraParent) > 0)){
					$vextraParent = $vextraParent[0];
					
					if( ($vextraParent['lft'] < $carExt['lft']) || ($vextraParent['rgt'] > $carExt['rgt'])){
						//Set LFT and RGT to 0 of all car vext and his children 
						$lft = 0;
						db_updCarExt(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$lft)
										, System_Properties::SQL_WHERE => array('lftBEq'=>$carExt['lft']
																				, 'rgtLEq' => $carExt['rgt'])));
						
						//Erase model from actual parent model
						//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($carExt['children'] +1) *2;
						db_updCarExt(array(System_Properties::SQL_SET => array('decLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftB' => $carExt['rgt'])
											));			
						db_updCarExt(array(System_Properties::SQL_SET => array('decRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtB' => $carExt['rgt'])
											));	
						
						//select again car vextra parent					
						if ($vextraParent['lft'] <= 1){
							$vextraParentH = db_selCarExt(array('lft'=>'1'));
						}else{
							$vextraParentH = db_selCarExt(array('carExtID'=>$vextraParent['carExtID']));
						}
						
						if (($vextraParentH != false) && is_array($vextraParentH) && (count($vextraParentH) > 0)){
							$vextraParent = $vextraParentH[0];
						}					
						
						//inc LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($carExt['children'] +1) *2;
						db_updCarExt(array(System_Properties::SQL_SET => array('incLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftBEq' => $vextraParent['rgt'])
											));			
						db_updCarExt(array(System_Properties::SQL_SET => array('incRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtBEq' => $vextraParent['rgt'])
											));	
					
						$carExtH = array();
						foreach($carExtChild as $cec){
							$cec['diff'] = $cec['rgt'] - $cec['lft'];
							$carExtH[$cec['lft']] = $cec;
						}
						ksort($carExtH);
						$carExtChild = $carExtH;
						
						$lftStart = $vextraParent['rgt'];
						
						$prevKey = null;
						foreach ($carExtChild as $key=>$cec){
							//increment the left start value
							if ($prevKey != null){
								$lftStart += $key - $prevKey;
							}				
							
							$lft = $lftStart;
							$rgt = $lft + $cec['diff'];
							db_updCarExt(array(System_Properties::SQL_SET => array('lft'=>$lft
																				, 'rgt'=>$rgt)
											, System_Properties::SQL_WHERE => array('carExtID'=>$cec['carExtID'])
											));				
							$prevKey = $key;										
						}	
						$this -> view -> info = $lang['AINFO_13'];
					}else{
						$this -> view -> error = $lang['AERR_31'];
					}
				}else{
					$this -> view -> error = $lang['AERR_31'];
				}
			}
		}else{
			$this -> view -> error = $lang['AERR_33'];
		} 
		
	}
	
	/**
	 * Manage the deletion of a car category
	 */
	public function carcateraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_cntCarCat.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		$carCatID = null;
		if (isset($p['ccid'])){
			$carCatID = $p['ccid'];
		}
		
		if ($carCatID != null){
				
			//erase car cat completely
			if (isset($p['carCatErase2'])){
				$p['carCatID'] = $carCatID;
				$this -> erasecarcatAction($p);
			}
				
			$carCat = db_selCarCat(array('carCatID'=>$carCatID));
			if (($carCat != false) && is_array($carCat) && (count($carCat) > 0)){
				$carCat = $carCat[0];
				
				//select all car advertisment which reference to this car cat
				$cntCarCat = db_cntCarCat(array('carCat'=>$carCat['carCatID']
												//, 'p' => true
												)
										);
										
				if ($cntCarCat != false){
					$cntCarCat = $cntCarCat[0];
				}
				
				$this -> view -> carCat = $carCat;
				$this -> view -> cntCarCat = $cntCarCat;
			}else{
				$this -> view -> error = $lang['AERR_36'];
				$this -> _forward('carcat');
			}
		}else{			
			$this -> view -> error = $lang['AERR_36'];
			$this -> _forward('carcat');
		}
	}
	
	private function editcarcatAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarCat.php');
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$lang = $this -> lang;
		$vcatParent = null;
		$vcatParentAct = null;
		$carCat = null;
		
		
		if (isset($p['carCat']) && is_array($p['carCat']) && isset($p['carCat']['lft']) && isset($p['carCat']['rgt'])){
			$carCat = $p['carCat'];
			//determine children from current car cat
			$carCatChild = db_selCarCat(array('lftBEq' => $carCat['lft']
											, 'rgtLEq' => $carCat['rgt']
											)
										);			
			
			//determine new specified vehicle cat parent
			if (isset($p['vcatParent']) && ($p['vcatParent'] != $carCat['carCatID'])) {
				if ($p['vcatParent'] < 1){
					$vcatParent = db_selCarCat(array('lft'=>'1'));
				}else{
					$vcatParent = db_selCarCat(array('carCatID'=>$p['vcatParent']));
				}
								
				if (($vcatParent != false) && is_array($vcatParent) && (count($vcatParent) > 0)){
					$vcatParent = $vcatParent[0];
					
					if (($vcatParent['lft'] < $carCat['lft']) || ($vcatParent['rgt'] > $carCat['rgt'])){						
					
						//Set LFT and RGT to 0 of all car vcat and his children 
						$lft = 0;
						db_updCarCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$lft)
										, System_Properties::SQL_WHERE => array('lftBEq'=>$carCat['lft']
																				, 'rgtLEq' => $carCat['rgt'])));
						
						//Erase model from actual parent model
						//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($carCat['children'] +1) *2;
						db_updCarCat(array(System_Properties::SQL_SET => array('decLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftB' => $carCat['rgt'])
											));			
						db_updCarCat(array(System_Properties::SQL_SET => array('decRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtB' => $carCat['rgt'])
											));	
						
						//select again car vcat parent					
						if ($vcatParent['lft'] <= 1){
							$vcatParentH = db_selCarCat(array('lft'=>'1'));
						}else{
							$vcatParentH = db_selCarCat(array('carCatID'=>$vcatParent['carCatID']));
						}
						
						if (($vcatParentH != false) && is_array($vcatParentH) && (count($vcatParentH) > 0)){
							$vcatParent = $vcatParentH[0];
						}					
						
						//Give way to new elements
						//inc LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($carCat['children'] +1) *2;
						db_updCarCat(array(System_Properties::SQL_SET => array('incLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftBEq' => $vcatParent['rgt'])
											));			
						db_updCarCat(array(System_Properties::SQL_SET => array('incRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtBEq' => $vcatParent['rgt'])
											));	
					
						$carCatH = array();
						foreach($carCatChild as $cec){
							$cec['diff'] = $cec['rgt'] - $cec['lft'];
							$carCatH[$cec['lft']] = $cec;
						}
						ksort($carCatH);
						$carCatChild = $carCatH;
						
						$lftStart = $vcatParent['rgt'];
						
						$prevKey = null;
						foreach ($carCatChild as $key=>$cec){
							//increment the left start value
							if ($prevKey != null){
								$lftStart += $key - $prevKey;
							}				
							
							$lft = $lftStart;
							$rgt = $lft + $cec['diff'];
							db_updCarCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																				, 'rgt'=>$rgt)
											, System_Properties::SQL_WHERE => array('carCatID'=>$cec['carCatID'])
											));				
							$prevKey = $key;										
						}	
					}else{
						$this -> view -> error = $lang['AERR_34'];						
					}
				}else{
					$this -> view -> error = $lang['AERR_34'];						
				}
			}else{
				$this -> view -> error = $lang['AERR_34'];						
			}
		} 
		
	}
	
	private function erasecarcatAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarAds.php');
		$lang = $this -> lang;
		//carCat contains  the entry from car cat
		if (isset($p['carCatID'])){
			$carCatID = $p['carCatID'];
			$carCat = db_selCarCat(array('carCatID' => $carCatID));
			
			if (($carCat != false) && is_array($carCat) && (count($carCat) > 0)){
				$carCat = $carCat[0];
				//fetch all carCat entries between LFT and RGT value of parent
				if (isset($carCat['lft']) && isset($carCat['rgt'])){
					$carCatChildren = db_selCarCat(array('lftBEq' => $carCat['lft']
														, 'rgtLEq' => $carCat['rgt']
														));
														
					if (($carCatChildren != false) && is_array($carCatChildren)){
						//catct all carCatID from selected carCat
						$carCatID = array();
						foreach ($carCatChildren as $key => $carCatChild){
							array_push($carCatID, $carCatChild['carCatID']);						
						}
						
						db_delCarCat(array('carCatID'=>$carCatID));						
						db_updCarCat(array(System_Properties::SQL_SET => array('decLft'=>($carCat['children']+1)*2)
										, System_Properties::SQL_WHERE=>array('lftBEq'=>$carCat['lft']))
										);			
						db_updCarCat(array(System_Properties::SQL_SET=>array('decRgt'=>($carCat['children']+1)*2)
										, System_Properties::SQL_WHERE=>array('rgtBEq'=>$carCat['lft']))
										);
						
						db_updCarAds(array(System_Properties::SQL_SET => array('carCat' => -1)
										, System_Properties::SQL_WHERE => array('carCat' => $carCat['carCatID'])));
						//db_delCar2Cat(array('carCatID'=>$carCatID));
						$this -> view -> info = $lang['AINFO_17'];
					}
				}
			}
		}
	}
	
	/**
	 * Manage the deletion of a car extra
	 */
	public function carextraeraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_cntCar2Ext.php');
		
		$lang = $this -> lang;
		$p = $this -> getRequest() -> getParams();
		
		$carExtID = null;
		if (isset($p['ceid'])){
			$carExtID = $p['ceid'];
		}
		
		if ($carExtID != null){
				
			//erase car extra completely
			if (isset($p['carExtErase2'])){
				$p['carExtID'] = $carExtID;
				$this -> erasecarextraAction($p);
			}
				
			$carExt = db_selCarExt(array('carExtID'=>$carExtID));
			if (($carExt != false) && is_array($carExt) && (count($carExt) > 0)){
				$carExt = $carExt[0];
				
				//select all car advertisment which reference to this car extra
				$cntCar2Ext = db_cntCar2Ext(array('carExtID'=>$carExt['carExtID']
												//, 'p' => true
												)
										);
										
				if ($cntCar2Ext != false){
					$cntCar2Ext = $cntCar2Ext[0];
				}
				
				$this -> view -> carExt = $carExt;
				$this -> view -> cntCar2Ext = $cntCar2Ext;
			}else{
				$this -> view -> error = $lang['AERR_32'];
				$this -> _forward('carextra');
			}
		}else{			
			$this -> _forward('carextra');
		}
	}
	
	private function erasecarextraAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCar2Ext.php');
		$lang = $this -> lang;
		//carExt contains  the entry from car extra
		if (isset($p['carExtID'])){
			$carExtID = $p['carExtID'];
			$carExt = db_selCarExt(array('carExtID' => $carExtID));
			
			if (($carExt != false) && is_array($carExt) && (count($carExt) > 0)){
				$carExt = $carExt[0];
				//fetch all carExt entries between LFT and RGT value of parent
				if (isset($carExt['lft']) && isset($carExt['rgt'])){
					$carExtChildren = db_selCarExt(array('lftBEq' => $carExt['lft']
														, 'rgtLEq' => $carExt['rgt']
														));
														
					if (($carExtChildren != false) && is_array($carExtChildren)){
						//extract all carExtID from selected carExt
						$carExtID = array();
						foreach ($carExtChildren as $key => $carExtChild){
							array_push($carExtID, $carExtChild['carExtID']);						
						}
						db_delCarExt(array('carExtID'=>$carExtID));						
						db_updCarExt(array(System_Properties::SQL_SET => array('decLft'=>($carExt['children'] +1) * 2)
										, System_Properties::SQL_WHERE=>array('lftBEq'=>$carExt['lft']))
										);			
						db_updCarExt(array(System_Properties::SQL_SET=>array('decRgt'=>($carExt['children'] +1)*2)
										, System_Properties::SQL_WHERE=>array('rgtBEq'=>$carExt['lft']))
										);
						
						db_delCar2Ext(array('carExtID'=>$carExtID));
						$this -> view -> info = $lang['AINFO_16'];
					}
				}
			}
		}
	}

	
	/**
	 * Manage car cat 
	 */
	public function carcatAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		
		//fetch all request parameters
		$p = $this -> getRequest() -> getParams();
		
		//insert a new car cat entry
		if (isset($p['carCatNew'])){
			$this -> insertcarcatAction($p);
		}
		
		$carCat = db_selCarCat();
		$vcatID = null;
		if (($carCat != false) && is_array($carCat)){
			$vcatID = array();
			foreach ($carCat as $key => $ceValue){
				array_push($vcatID, $ceValue['vcatID']);
			}
		}
		
		$vcat = db_selVCat(array('notVcatID'=>$vcatID));		
		
		$this -> view -> vcat = $vcat;
		$this -> view -> carCat = $carCat;
	}
	private function insertcarcatAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarCat.php');

		$lang = $this -> lang;
		
		//Get vehicle extra details for new category
		$vcatNew = null;
		$vcatParent = null;
		if (isset($p['vcatNew'])){
			$vcatNew = db_selVCat(array('vcatID'=>$p['vcatNew']));
			$carCat = db_selCarCat(array('vcatID'=>$p['vcatNew']));
			if ($carCat != false){
				$vcatNew = null;
			}  
		
			//get vehicle cat details for parent cat
			if ($p['vcatParent'] != $p['vcatNew']){
				if (isset($p['vcatParent']) && ($p['vcatParent'] == -1)){
					$vcatParent = db_selCarCat(array('lft'=>1
													//, 'print'=>true
													));
					if ($vcatParent == false){
						db_insCarCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
						$vcatParent = db_selCarCat(array('lft'=>1
														//, 'print'=>true
														));
					}
				}else{
					$vcatParent = db_selCarCat(array('vcatID' => $p['vcatParent']));
				}
			}else{
				$this -> view -> error = $lang['AERR_34'];
			}
		}
		
		if (($vcatNew != null) && ($vcatParent != null)
			&& is_array($vcatNew) && is_array($vcatParent)){
			$vcatParent = $vcatParent[0];
			$vcatNew = $vcatNew[0];
			db_updCarCat(array(System_Properties::SQL_SET => array('incLft'=>2)
							, System_Properties::SQL_WHERE => array('lftBEq'=>$vcatParent['rgt'])
								)
						);
			db_updCarCat(array(System_Properties::SQL_SET => array('incRgt'=>2)
							, System_Properties::SQL_WHERE => array('rgtBEq'=>$vcatParent['rgt'])
								)
						);
			db_insCarCat(array('lft'=>$vcatParent['rgt']
							, 'rgt' => ($vcatParent['rgt'] +1)
							, 'active' => 1
							, 'vcatID' => $vcatNew['vcatID']
								)
						);
			$this -> view -> info = $lang['AINFO_14'];
		}else{
			$this -> view -> error = $lang['AERR_35'];
		}				
	}

	/**
	 * Edit a car category
	 */
	public function carcateditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		
		$lang = $this -> lang;
		$p = $this -> getRequest() -> getParams();
		
		//determine car vehicle cat id
		$carVcatID = null;
		if (isset($p['ccid'])){
			$carVcatID = $p['ccid'];
		}
		
		if ($carVcatID != null){
			$carVcat = db_selCarCat(array('carCatID' => $carVcatID));
			if (($carVcat != false) && is_array($carVcat)){
					$carVcat = $carVcat[0];
			
					//Edit car category
					if (isset($p['carCatEdit'])){
						$p['carCatID'] = $carVcatID;
						$p['carCat'] = $carVcat;
						$this ->editcarcatAction($p);
						$carVcatH = db_selCarCat(array('carCatID' => $carVcatID));
						if (($carVcatH != false) && is_array($carVcatH) && (count($carVcatH) > 0)){
							$carVcat = $carVcatH[0];
							$this -> view -> info = $lang['AINFO_15'];
						}
					}
					
					$this -> view -> carVcat = $carVcat;
					if ($carVcat['level']-1 > 0){
						$carVcatParent = db_selCarCat(array('level' => ($carVcat['level']-1)
															, 'lftLEq' => $carVcat['lft']
															, 'rgtBEq' => $carVcat['rgt']
															)
													);
						if (($carVcatParent != false) && is_array($carVcatParent) && (count($carVcatParent) > 0)){													
							$this -> view -> carVcatParent = $carVcatParent[0];
						}
					}
					
					$carVcatAll = db_selCarCat();
					$this -> view -> carVcatAll = $carVcatAll;
					
			}else{
				$this -> view -> error = $lang['AERR_36'];
				$this -> _forward('carcat');
			}
		}else{
			$this -> view -> error = $lang['AERR_36'];
			$this -> _forward('carcat');
		}
	}
}
?>