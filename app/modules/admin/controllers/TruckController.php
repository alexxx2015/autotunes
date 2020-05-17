<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101216
 * Desc:		This is the administrator controller for maintanence groups
 *********************************************************************************/
require_once('classes/AbstractController.php');
class Admin_TruckController extends AbstractController{
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
		//Check Authority for "TRUCK_EDIT" action
		if(($action == 'detail') && ($req -> __isset('truckSafe'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		elseif(($action == 'aful')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		elseif(($action == 'agfe')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		//Check Authority for "TRUCK_DELETE" action
		elseif(($action == 'detail') && ($req -> __isset('truckDel'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "TRUCK_CREATE" action
		else if(($action == 'insert') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
	
		//check authority TRUCK_EXTRA_CREATE
		else if(($action == 'truckextra') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EXTRA_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority TRUCK_EXTRA_EDIT
		else if(($action == 'truckextraedit') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EXTRA_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority TRUCK_EXTRA_ERASE
		else if(($action == 'truckextraerase') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EXTRA_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
	
		//check authority TRUCK_CAT_CREATE
		else if(($action == 'truckcat') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CAT_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority TRUCK_CAT_EDIT
		else if(($action == 'truckcatedit') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CAT_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority TRUCK_CAT_ERASE
		else if(($action == 'truckcaterase') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CAT_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this->tmpl;//$this -> getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;			
	}
	
	public function indexAction(){
		$this -> loadTruckModelsBrands();
	}
	
	
	/**
	 * This function load truck brands and their corresponding truck modelss
	 */
	private function loadTruckModelsBrands($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		
		$truckBrand = db_selTruckBrand(array('orderby'=>array(array('col' => 'brandName'))
											,'active' => 1));
											
		$truckModel = false;
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
												, array('col'=>'truckModelName')
											)));
		}
		
		$this -> view -> truckBrand = $truckBrand;
		$this -> view -> truckModel = $truckModel;
	}
	
	/**
	 * This AJAX method return for a specific truck brand the corresponding truck models
	 * Input parameter:
	 * @param	cid:	this parameter specify the truck id
	 * 
	 * Output parameter:
	 * @param	r:	indicate if a request ist successfully processed (true) or not (false)
	 * @param	cm: compose with the name and id of a truck model orderer by model name
	 * 			@param	cmn:	truck model name
	 * 			@param	cmid:	truck model id
	 */
	public function ajagmAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$req = $this -> getRequest();
		
		$return = array('r' => false, 'tm' => array());
		
		$r_truckBrandID = $req -> getParam('tid');
		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		$truckBrand = db_selTruckBrand(array('truckBrandID' => $r_truckBrandID, 'active'=>'1'));
		if (($truckBrand != false) && is_array($truckBrand)){
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
			$truckModels = db_selTruckModel(array(	'truckBrandID' => $truckBrand[0]['truckBrandID'],
												'orderby' => array(
															array('col' => 'lft')
															, array('col' => 'truckModelName')
												)
										)
						);
			if (($truckModels != false) && is_array($truckModels)){
				$return['r'] = true;
				foreach ($truckModels as $truckModel) {
					$cm = array('tmn' => $truckModel['truckModelName'],
								'tmid' => $truckModel['truckModelID']
								, 'tml' => $truckModel['level']);
					array_push($return['tm'], $cm);
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
		$truckSearchParam = $this -> adminNS -> truckSearchParam;
		
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		//New Search
		if (isset($p['search1'])){
			$p = $req -> getParams();
			$this -> adminNS -> truckSearchParam = $p;
		}elseif (is_array($truckSearchParam)){
			$p = $truckSearchParam;
		}		
		
		if (isset($p['adNum']) && is_numeric($p['adNum'])){
			$truckAds = $this -> searchTruckAds(array('truckID' => $p['adNum']));
		}else{
			
			//Sort options
			if (isset($p['truckSort']) && ($p['truckSort'] != -1)){
				$p['orderby'] = array();
				
				//Determine col
				switch ($p['truckSort']) {
					//Price
					case 0: $col = 'truckPrice';
							break;						
					//Leistung
					case 1: $col = 'truckPower';
							break;
					//Laufleistung
					case 2: $col = 'truckKM';
							break;
					//Datum
					case 3: $col = 'timestam';
							break;
					default: $col = 'truckPrice';
							break;
				}
				
				//Determine sort orientation
				$desc = false;
				if (isset($p['truckSortOpt']) && ($p['truckSortOpt'] == 1)){
					$desc = true;
				}
				
				array_push($p['orderby'], array('col' => $col
												, 'desc' => $desc
												)
											);
				
				
			}
			
			$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
			$truckAds = $this -> searchTruckAds($p);
		}
			
		//Search process successfully passed and found some matches
		
		if (is_array($truckAds) && (count($truckAds) > 0)){
			//Check if javascript is active
			if(isset($p['jsActive']) && ($p['jsActive'] == 'on')){
				$truckAds['jsActive'] = $p['jsActive'];
			}
			
			$truckAds['numAds'] = System_Properties::NUM_ADS;
			$truckAds['actPage'] = $page;
			
			$this -> actParam = $p;
			$this -> view -> searchParam = $p;
			
			$this -> view -> truckAds = $truckAds;
		}		
		else{
			$this -> view -> error = $lang['ERR_4'];
			
			$this -> actParam = $p;
			$this -> view -> searchParam = $p;				
			
			//$this -> indexAction();
			//$this -> render('index');
		}
		$this -> loadTruckModelsBrands($p);
	}
	
	/**
	 * This function perform an update of a truck advertisement
	 */
	private function updatetruckAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruck2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruck2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_delVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_updVPic.php');
		
		$p = $this -> filterRecvParam($p);
		
		if (!isset($p['error'])) {
			$p['erased'] = 0;
			$truckID = $p['truckID'];
			$updTruck = db_updTruckAds(array(System_Properties::SQL_SET => $p
										, System_Properties::SQL_WHERE => array('truckID' => $truckID)
										));
			
			//update truck extra
			db_delTruck2Ext(array('truckID' => $p['truckID']));
			if(isset($p['truckExtDB']) && is_array($p['truckExtDB'])){
				foreach ($p['truckExtDB'] as $key=>$kVal){
					db_insTruck2Ext(array('truckID'=>$p['truckID']
										, 'truckExtID'=>$kVal['truckExtID']
										));
				}
			}
			
			//Update fotos
			$truckPicNS = $this -> adminNS -> truckPhoto;
			if (is_array($truckPicNS)){
							
				$vPicID = array();
				foreach ($truckPicNS as $key => $kVal){
					array_push($vPicID, $kVal['vPicID']);
				}
				db_updVPic(array('vPicID' => $vPicID
								, 'vPicTMP' => '0'
								));
								
				$notUpdPic = db_selVPic(array('notVPicID' => $vPicID
											, 'vID' => $truckID
											));
											
				if (($notUpdPic != false) && is_array($notUpdPic) && (count($notUpdPic) > 0)){
					$vPicID = array();
					foreach ($notUpdPic as $key => $kVal) {
						array_push($vPicID, $kVal['vPicID']);
					}
					db_delVPic(array('vPicID'=>$vPicID));
				}
				/*
				foreach ($truckPicNS as $cpns){
					//Should a photo be deleted?
					if (isset($cpns['del']) && ($cpns['del'] == true ) && ($cpns['vID'] == $p['truckID'])){
						$db_delVPic = db_delVPic(array('vPicID' => $cpns['vPicID']));
						if ($db_delVPic != false){							
							$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.System_Properties::TRUCK_ABRV.'_'.$cpns['vID'].'_'.$cpns['vPicID'].'.jpeg';
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
	private function searchTruckAds($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
	
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		
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
		
		$p['priceWMwst'] = true;
		
		$truckAds = db_selTruckAds($p);
		
		//Search process successfully passed and found some matches
		if (is_array($truckAds) && ($truckAds != false)){
			$truckAdsP = array('totalAds'=>$truckAds['totalRows'], 'truckAds' => array());
			/*
			//Check if javascript is active
			if(isset($p['jsActive']) && ($p['jsActive'] == 'on')){
				$truckAdsP['jsActive'] = $p['jsActive'];
			}
			*/
			
			//For each truck ad select the correspondend truck picutres
			foreach ($truckAds AS $key => $truckAd){
				if (is_numeric($key)){
					$truckAd['truckPics'] = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV,
															'vID' => $truckAd['truckID']));					
					array_push($truckAdsP['truckAds'], $truckAd);
				}				
			}
			$truckAds = $truckAdsP;
					/*
			$truckAdsP['numAds'] = System_Properties::NUM_ADS;
			$truckAdsP['actPage'] = $page;
			$this -> view -> truckAds = $truckAdsP;		
			$this -> render('search2');
		}		
		else{
			$lang = $this -> view -> tmpl -> getLang();
			$this -> view -> error = $lang['ERR_4'];
			$this -> search1Action();*/
		}
		return $truckAds;
	}
	
	/**
	 * This function load all truck brands and models
	 */
	public function detailAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruck2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		$req = $this -> getRequest();
		$lang = $this -> lang;
		$p = $req -> getParams();
		
		if (isset($p['id'])){			
			$truckDetails = db_selTruckAd(array('truckID' => $p['id']));
			if (($truckDetails != false) && is_array($truckDetails) && (count($truckDetails) == 1)){				
				$truckDetails = $truckDetails[0];
				
				if (is_array($this -> adminNS -> truckAds) && isset($this -> adminNS -> truckAds['truckID'])
					&& ($this -> adminNS -> truckAds['truckID'] !=  $truckDetails['truckID'])){
					$this -> resetnsAction();
				}
				//Safe changes
				if (isset($p['truckSafe'])){
					$p['truckID'] = $truckDetails['truckID'];
					$p['truckBrandID'] = $p['truckBrand'];
					$p = $this -> updatetruckAction($p);
					if (isset($p['error'])){
						$this -> view -> error = $p['error'];
					}else{
						$truckDetails2 = db_selTruckAd(array('truckID' => $p['id']));
						if (($truckDetails2 != false) && is_array($truckDetails2) && (count($truckDetails2) == 1)){
							$truckDetails  = $truckDetails2[0];
						}
						$this -> view -> info = $lang['AINFO_4'];
					}
				}
				//Delete advertisement
				else if (isset($p['truckDel'])){
					$truckAd = db_updTruckAds(array(System_Properties::SQL_WHERE => array('truckID' => $truckDetails['truckID'])
												, System_Properties::SQL_SET => array('erased' => 1)
												)
											);
					if ($truckAd != false){
						$this -> _forward('index');
					}
				}
				//Load all truck extra
				$this -> loadTruckExt();
				
				//Load truck extra
				$truckExt = db_selTruck2Ext(array('truckID' => $truckDetails['truckID']));
				if (($truckExt != false) && is_array($truckExt) && (count($truckExt) > 0)){
					$truckDetails['truckExt'] = array();
					foreach ($truckExt as $key => $val){
						array_push($truckDetails['truckExt'], $val['vextID']);
					}					
				}				
				
				//Fetch all truck pics
				if (isset($this -> adminNS -> truckPhoto) && is_array($this -> adminNS -> truckPhoto)){
					$truckPhoto = $this -> adminNS -> truckPhoto;
				}	
				else{
					$truckPhoto = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV,
													'vID' => $truckDetails['truckID']));
					if (is_array($truckPhoto)){
						$truckDetails['truckPhoto'] = array();
						foreach($truckPhoto as $key => $kVal){
							$truckDetails['truckPhoto'][$kVal['vPicID']] = $kVal;
						}
						$truckPhoto = $truckDetails['truckPhoto'];
					}
				}
				$truckDetails['truckPhoto'] = $truckPhoto;
				 
				//Load brands and models				
				$this -> loadTruckModelsBrands(array('truckBrand' => $truckDetails['truckBrandID']));
				//Load all possible truck extra
				$this -> loadTruckExt();
				$this -> loadTruckCat();
				$this -> view -> truck = $truckDetails;
				
				$this -> adminNS -> truckAds = $truckDetails;
				
				$this -> adminNS -> truckPhoto = $truckPhoto;
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
	 * This function load all truck categories.
	 */
	private function loadTruckCat(){
		include_once ('default/models/truck/db_selTruckCat.php');
		$truckCat = db_selTruckCat();
		if($truckCat != false){
			$this -> view -> truckCat = $truckCat;
		}
		return $truckCat;
	}
	/**
	 * This function load all truck extras.
	 */
	private function loadTruckExt(){
		/*
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selExtra.php');
		$truckExt = db_selExtra(array('vType' => 'c'));
		if($truckExt != false){
			$this -> view -> truckExt = $truckExt;
		}
		*/
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		$truckExt = db_selTruckExt(array('lftBE'=>1));
		if($truckExt != false){
			$this -> view -> truckExt = $truckExt;
		}
	}
	
	/**
	 * This function filter a truck advertisement
	 * @param $p:	this variable contains the parameter of a truck advertisement
	 */
	private function filterRecvParam($p){
		$truckCatDB = $this -> loadTruckCat();
		$lang = $this -> lang;
		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		
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
		$truckBrand = db_selTruckBrand(array('truckBrandID'=>$p['truckBrand']
									, 'active' => 1));	
		
		//Check truckBrand
		if (!isset($p['truckBrand'])
			|| ($truckBrand == false)
			){
				$p['error'] = $lang['ERR_2'];
		}
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
		else if (!isset($p['truckEZM']) || !isset($p['truckEZY'])
				|| ($fMonth -> isValid($p['truckEZM']) == false)
				|| ($fYear -> isValid($p['truckEZY']) == false)){
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
			include_once('default/models/truck/db_selTruckModel.php');				
			$truckModel = db_selTruckModel(array(	'truckBrandID' => $truckBrand[0]['truckBrandID'],
												'truckModelID' => $req -> getParam('truckModel'))
										);
			$p['truckModelTxt'] = null;
			if($truckModel != false){
				$p['truckModelTxt'] = $truckModel[0]['truckModelName'];
			}
			
			$p['truckBrandTxt'] = $truckBrand[0]['brandName'];
			$p['truckPriceType'] = $fTInt->filter($p['truckPriceType']);
			$p['truckPowerType'] = $fMInt->filter($p['truckPowerType']);
			$p['truckTUVM'] = $fMonth->filter($p['truckTUVM']);
			$p['truckTUVY'] = $fYear->filter($p['truckTUVY']);
			$p['truckAUM'] = $fMonth->filter($p['truckAUM']);
			$p['truckAUY'] = $fYear->filter($p['truckAUY']);
			$p['truckShift'] = $fTInt->filter($p['truckShift']);
			$p['truckWeight'] = $fMInt->filter($p['truckWeight']);
			$p['truckCyl'] = $fTInt->filter($p['truckCyl']);
			$p['truckCub'] = $fSInt->filter($p['truckCub']);
			$p['truckDoor'] = $fTInt->filter($p['truckDoor']);
			$p['truckUseIn'] = $fString50->filter($p['truckUseIn']);
			$p['truckUseOut'] = $fString50->filter($p['truckUseOut']);
			$p['truckCO2'] = $fString50->filter($p['truckCO2']);
			$p['truckState'] = $fTInt->filter($p['truckState']);
			$p['truckCat'] = $fTInt->filter($p['truckCat']);
			$p['truckFuel'] = $fTInt->filter($p['truckFuel']);
			$p['truckClr'] = $fTInt->filter($p['truckClr']);
			//$p['truckClrMet'] = $fTInt->filter($p['truckClrMet']);
			$p['truckEmissionNorm'] = $fTInt->filter($p['truckEmissionNorm']);
			$p['truckEcologicTag'] = $fTInt->filter($p['truckEcologicTag']);
			$p['truckKlima'] = $fTInt->filter($p['truckKlima']);
			$p['truckDesc'] = $fString1000->filter($p['truckDesc']);
			$p['userNName'] = $fString100->filter($p['userNName']);
			$p['userVName'] = $fString100->filter($p['userVName']);
			$p['userPLZ'] = $fString20->filter($p['userPLZ']);
			$p['userOrt'] = $fString100->filter($p['userOrt']);
			$p['userTel1'] = $fString100->filter($p['userTel1']);
			$p['userTel2'] = $fString100->filter($p['userTel2']);
			$p['userAdress'] = $fString100->filter($p['userAdress']);
			*/
			
			$p['truckBrandID'] = $truckBrand[0]['truckBrandID'];
			
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
				$truckModel = db_selTruckModel(array(	'truckBrandID' => $truckBrand[0]['truckBrandID'],
													'truckModelID' => $p['truckModel'])
											);
				if(($truckModel != false) && is_array($truckModel) && (count($truckModel) > 0)){
					$truckModel = $truckModel[0];
					$p['truckModelTxt'] = $truckModel['truckModelName'];
					$p['truckModelID'] = $truckModel['truckModelID'];
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
			if(isset($p['mwst']) && ($p['mwst'] !== '0')){
				$p['mwst'] = 1;
			}
			if(isset($p['mwstSatz']) && isset($lang['V_MWST']) && is_array($lang['V_MWST'])){
				$p['mwstSatz'] = str_replace(',', '.', $p['mwstSatz']);
				if(!in_array($p['mwstSatz'], $lang['V_MWST']) && ($p['mwst'] === '0')){
					$p['mwstSatz'] = 19;
				}
				
			}
			
			if(!isset($p['truckPowerType']) || ($p['truckPowerType'] == null)){
				$p['truckPowerType'] = 0;
			}else{
				$p['truckPowerType'] = $fMInt->filter($p['truckPowerType']);
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
			
			$p['truckUseIn'] = $fString50->filter($p['truckUseIn']);
			$p['truckUseOut'] = $fString50->filter($p['truckUseOut']);
			$p['truckCO2'] = $fString50->filter($p['truckCO2']);
			
			if(!isset($p['truckState']) || ($p['truckState'] == null)){
				$p['truckState'] = -1;
			}else{
				$p['truckState'] = $fTInt->filter($p['truckState']);
			}
			
			if(!isset($p['truckCat']) || ($p['truckCat'] == null)){
				$p['truckCat'] = -1;
			}else{
				$found = false;
				$p['truckCat'] = $fTInt->filter($p['truckCat']);
				foreach ($truckCatDB as $key=>$kVal){
					if ($kVal['truckCatID'] == $p['truckCat']){
						$found = true;
						break;						
					}
				}
				if ($found == false){
					$p['truckCat'] = -1;
				}
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
			
			//check truckLocOrt
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
				include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');			
				$truckExt = db_selTruckExt(array('vextID'=>$p['truckExt']));
			}
			$p['truckExtDB'] = $truckExt;
		}
		return $p;
	}
	
	/**
	 * This is an ajax complete function which save an uploaded photo
	 * Input paramter: 
	 * @param	cid:	this parameter specify the idetifier of the truck advertisement 
	 * 
	 * Output parameter: 
	 * @param	r:	indicate if a request is successfully processed (true) or not (false)
	 * @param	tu: this parameter specify the URL to the uploaded picture
	 * @param	h: 	this parameter specify the hash code of modified picture
	 */
	public function ajafulAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAd.php');
		
		//Initialize returning parameter
		$return = array('r' => false);
		$req = $this -> getRequest();
		$adminDetails = $this -> adminNS -> adminData;
		if ($req -> __isset('tid') && $req -> __isset('t')){
			$truckID = $req -> getParam('tid');
			$truckDetails = db_selTruckAd(array('truckID' => $truckID));
			if (($truckDetails != false)){
				$uploadRes = $this -> uploadPhoto(array('truckID' => $truckDetails[0]['truckID']
														, 'truckDetail' => $truckDetails
														)
													);
				if (($uploadRes != false) 
					&& is_array($uploadRes)
					&& isset($uploadRes['r'])
					&& ($uploadRes['r'] == true)){
					
					if (!isset($this -> adminNS -> truckPhoto) || !is_array($this -> adminNS -> truckPhoto)){
						$this -> adminNS -> truckPhoto = array();
					}
					//array_push($this -> adminNS -> truckPhoto, $uploadRes['truckPhoto']);
					$this -> adminNS -> truckPhoto[$uploadRes['truckPhoto']['vPicID']] = $uploadRes['truckPhoto'];
					
					
					$return['r'] = true;		
					$return['h'] = $uploadRes['truckPhoto']['vPicID'];
					$return['tid'] = $truckID;
					$return['t'] = $req -> getParam('t');
					$return['tu'] = $uploadRes['truckPhoto']['destURI'];				
				}
			}
		}
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}

	
	
	/**
	 * This function erase an uploaded truck picture while inserting a truck advertisement
	 * 
	 * Input parameter:
	 * @param pid:		this parameter specify the temporary truck picture id
	 * 
	 * Output parameter:
	 * @param r:		this parameter specify if that the request is processed successfully
	 * 
	 * 
	 */
	public function ajagfeAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAd.php');
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
							
					$newTruckPhoto = array();
					$truckPhoto = $this -> adminNS -> truckPhoto;
					foreach ($truckPhoto as $key=>$val){
						if ($key != $vPicID){
							$newTruckPhoto[$key] = $val;
						}else{							
							//delete picutres from filesystem
							if(isset($vPic['vType']) && isset($vPic['vID']) && isset($vPic['vPicID'])){
								$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($vPic['vType']).'_'.$vPic['vID'].'_'.$vPic['vPicID'].'.jpeg';
								@unlink($picPath);
							}
						}
					}
					
					$this -> adminNS -> truckPhoto = $newTruckPhoto;
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
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		
		$return = array('r' => false);
		$adminDetails = $this -> adminNS -> adminData;
		
		if (isset($this -> adminNS -> truckAds) && is_array($this -> adminNS -> truckAds)
			&& isset($this -> adminNS -> truckAds['truckID'])){
				
			$truckDetails = db_selTruckAd(array('truckID' => $this -> adminNS -> truckAds['truckID']));
			if (($truckDetails != false) && is_array($truckDetails) && (count($truckDetails) == 1) ){
					
				$truckDetails = $truckDetails[0];
				$vPicID = $this -> getRequest() -> getParam('pid');
				
				$vPicDetails = db_selVPic(array('vPicID' => $vPicID
												, 'vID' => $truckDetails['truckID']
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

					$newTruckPhoto = array();
					$truckPhoto = $this -> adminNS -> truckPhoto;
					foreach ($truckPhoto as $key=>$val){
						if ($val['vPicID'] != $vPicID){
							$newTruckPhoto[$val['vPicID']] = $val;
						}
					}
					$this -> adminNS -> truckPhoto = $newTruckPhoto;
					*/
					
					$truckPhotoNS = $this -> adminNS -> truckPhoto;			
					$this -> adminNS -> truckPhoto = array();
					foreach ($truckPhotoNS AS $truckPhoto){
						if ($truckPhoto['vPicID'] == $vPicDetails['vPicID']) {
							$truckPhoto['del'] = true;
						}
						array_push($this -> adminNS -> truckPhoto, $truckPhoto);
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
					$newTruckPhoto = array();
					$truckPhoto = $this -> truckNS -> truckPhoto;
					foreach ($truckPhoto as $key=>$val){
						if ($key != $vPic['vPicID']){
							$newTruckPhoto[$key] = $val;
						}
					}
					$this -> truckNS -> truckPhoto = $newTruckPhoto;
					
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
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_delVPic.php');
		
		$return = array('r' => false);	
		$imgCtrl = new Zend_File_Transfer_Adapter_Http();
		
		//This is the truck advertisement
		//$p = $this -> truckNS -> truckAds;			
		if(isset($p['truckID']) && ($imgCtrl->isUploaded() == true)){
			if (!isset($p['truckDetail'])){
				$truckDetail = db_selTruckAd(array('truckID'=>$p['truckID']));				
			}else{
				$truckDetail = $p['truckDetail'];
			}
			
			//Check if truck advertisement exists
			if (($truckDetail != false) && (count($truckDetail) == 1)){
				$truckDetail = $truckDetail[0];
				$truckID = $truckDetail['truckID'];										
				$vPicID = db_insVPic(array(	'vType' => System_Properties::TRUCK_ABRV
									 		, 'vID' => $truckID
											, 'vPicTMP' => '1'
											)
										);
				if (($vPicID != false) && is_numeric($vPicID)){		
					$imgCtrl -> setOptions(array('useByteString'  => false));
					$imgCtrl -> addValidator('FilesSize', false, System_Properties::PIC_FILE_SIZE);
					$imgCtrl -> addValidator('Extension', false, System_Properties::$PIC_EXT);
					
					//$imgHash = session_id().'_'.$imgCtrl -> getHash();
					$destURI = System_Properties::PIC_PATH.'/'.System_Properties::TRUCK_ABRV.'_'.$truckID.'_'.$vPicID.'.jpeg';				
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
					//if (!isset($this -> truckNS -> truckPhoto[$imgHash]) && $imgCtrl -> receive()){	
					if (in_array(strtolower($mimeTypeDetails), System_Properties::$PIC_EXT) 
						&& ($imgCtrl -> receive())){
						$truckPhoto = db_selVPic(array('vPicID' => $vPicID
													, 'vPicTMP' => '1'
													)
												);
						if($truckPhoto != false){
							$truckPhoto = $truckPhoto[0];
							$truckPhoto['destURI'] = $destURI;
							$truckPhoto['vPicNew'] = true;
							$return['r'] = true;
							$return['truckPhoto'] = $truckPhoto;
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
		$this -> adminNS -> truckAds = null;
		$this -> adminNS -> truckPhoto = null;
		$this -> adminNS -> truckInsert = false;
		*/
		
		$this -> adminNS -> __unset('truckAds');
		$this -> adminNS -> __unset('truckPhoto');
		$this -> adminNS -> __unset('truckInsert');		
	}
	
	public function insertAction(){
		//$this -> resetnsAction();
		$req = $this->getRequest();
		//First insert
		if ($req -> __isset('ins2') && (!isset($this -> adminNS -> truckInsert) || ($this -> adminNS -> truckInsert == false))){
			$this -> insert2Action();
		}
		//User confirm the correctness of the advertisment
		else if ($req -> __isset('ins3') && (!isset($this -> adminNS -> truckInsert) || ($this -> adminNS -> truckInsert == false))){
			$this -> insert3Action();
		}
		//Advertisment is committed to database and the user safe all fotos.
		else if (isset($this -> adminNS -> truckInsert)
					&& ($this -> adminNS -> truckInsert == true)
					&& isset($this -> adminNS -> truckAds)
					&& ($req -> __isset('safeFoto'))){
			$this -> insert7Action();
		}
		//Advertisment is commited to database, so show the inserted advertisement
		else if (isset($this -> adminNS -> truckInsert) 
					&& ($this -> adminNS -> truckInsert == true) 
					&& isset($this -> adminNS -> truckAds)){
			$this -> insert4Action();
		}
		else{
			$this -> resetnsAction();
			$this -> insert1Action();
		}
	}
	
	private function insert1Action(){
		$this -> loadTruckModelsBrands();
		$this -> loadTruckExt();
		$this -> loadTruckCat();
		if (isset($this -> actParam) && ($this -> actParam != null)){
			$this -> loadTruckModelsBrands(array('truckBrand' => $this -> actParam['truckBrand']));
			$this -> view -> truck = $this -> actParam;
		}
		$this -> render('insert1');
	}
	
	private function insert2Action(){
		//First check the receveid parameters
		$p = $this -> filterRecvParam($this -> getRequest() -> getParams());
		$this -> actParam = $p;
		//Forward only if an error occurs
		if(isset($p['error'])){		
			//$this -> loadTruckModelsBrands();
			$this -> view -> error = $p['error'];
			//$this -> render('insert1');
			//$this -> _forward('insert', null, null, array('error' => $error));
			$this -> insert1Action();
		}		
		else{			
			$this -> loadTruckCat();
			//Set session namespace
			$this -> adminNS -> truckAds = $p;			
			$this -> view -> truck = $p;
			//$this -> view -> truckPhoto = $this -> truckNS -> truckPhoto;
			
			$this -> render('insert2');	
		}	
	}
	
	private function insert3Action(){
		if(isset($this -> adminNS -> truckAds)){
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckAds.php');
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruck2Ext.php');
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
			$p = $this -> adminNS -> truckAds;
			$user = $this -> adminNS -> adminData;
			$this -> actParam = $p;
			$lang = $this -> lang;
			
			//Filter the truck advertisement
			$p = $this -> filterRecvParam($p);
			
			
			if (!isset($p['error']) && is_array($user) && isset($user['userID'])){
				//Advertising is successful
				$p['userID'] = $user['userID'];
				$p['userAds'] = 3; //3 = System advertisment
				$truckID = db_insTruckAds($p);
				if(($truckID != false) && is_numeric($truckID)){
					//insert truck extra if truckExtDB extist
					if (isset($p['truckExtDB']) && is_array($p['truckExtDB'])){
						foreach ($p['truckExtDB'] as $key=>$kVal){
							if (is_array($kVal) && isset($kVal['vextID'])){
								$truckExt = db_selTruckExt(array('vextID' => $kVal['vextID']));
								if (($truckExt != false) && is_array($truckExt) && (count($truckExt) > 0)){
									$truckExt = $truckExt[0];
									if (isset($truckExt['truckExtID'])){
										db_insTruck2Ext(array('truckID'=>$truckID
															, 'truckExtID' =>$truckExt['truckExtID'] 
															));
									}
								}
							}
						}
					}				
						
					$p['truckID'] = $truckID;
					$this -> adminNS -> truckAds = $p;
					
					//Set truckInsert to true because the truck advertisment is successfully inserted.
					$this -> adminNS -> truckInsert = true;	
						
					$this -> view -> truck = $p;	
					//$this -> view -> truckPhoto = $this -> truckNS -> truckPhoto;
					$this -> render('insert3');
				}
				//Forward only if an error occurs
				else{
					if (!isset($p['error'])){
						$p['error'] = $lang['ERR_2'];
					}
					
					//$this -> loadTruckModelsBrands();
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
			//$this -> loadTruckModelsBrands();
			//$this -> render('insert1');
			$this -> insert1Action();
		}
	}
	
	/**
	 * Processed if the user press the refresh button
	 */
	private function insert4Action(){		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		$truck = $this -> adminNS -> truckAds;
		$this -> actParam = $truck;	
		$this -> loadTruckCat();	
					
		if (isset($truck['truckID'])){
			$truckPhotos = db_selVPic(array(	'vType'=>System_Properties::TRUCK_ABRV,
											'vID' => $truck['truckID']
										)
									);
			if (($truckPhotos != false) && is_array($truckPhotos) && (count($truckPhotos) > 0)){
				$truckPhotoNew = array();
				foreach($truckPhotos as $key => $kVal){
					$truckPhotoNew[$kVal['vPicID']] = $kVal;
				}
				$this -> adminNS -> truckPhoto = $truckPhotoNew;
			}
			/*
			if (($truckPhotos != false) && is_array($truckPhotos) && (count($truckPhotos) > 0)){
				if (is_array($this -> truckNS -> truckPhoto)){
					$this -> truckNS -> truckPhoto = array_merge($this -> truckNS -> truckPhoto, $truckPhotos);
				}else{
					$this -> truckNS -> truckPhoto = $truckPhotos;
				}
			}
			*/
		}
		$truck['truckPhoto'] = $this -> adminNS -> truckPhoto;		
		$this -> view -> truck = $truck;
		$this -> render('insert3');			
	}
	
	/**
	 * This action controller insert all photos into database and close advertisment. It is finally closed.
	 * The user can't edit the advertisment anymore in the inserting process. But in the user cockipit it is possible to edit the ad.
	 */
	private function insert7Action(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_updVPic.php');
		
		if (isset($this -> adminNS -> truckAds) && is_array($this -> adminNS -> truckAds)			
			&& isset($this -> adminNS -> truckAds['truckID'])){
			$truckAdsNS = $this -> adminNS -> truckAds;
			$truckDetails = db_selTruckAd(array('truckID' => $truckAdsNS['truckID']));
			if ($truckDetails != false){
				$truckDetails = $truckDetails[0];
				
				if (isset($this -> adminNS -> truckPhoto) && is_array($this -> adminNS -> truckPhoto)){								
					foreach ($this -> adminNS -> truckPhoto as $truckPhoto){
						db_updVPic(array('vPicID' => $truckPhoto['vPicID']
										, 'vPicTMP' => '0'
										)
									);
					}
				}
				$this -> resetnsAction();
				$this -> _redirect('/truck/'.$truckDetails['truckID']);
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
	 * Manage truck extra 
	 */
	public function truckextraAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		
		//fetch all request parameters
		$p = $this -> getRequest() -> getParams();
		
		//insert a new truck extra entry
		if (isset($p['truckExtraNew'])){
			$this -> inserttruckextAction($p);
		}
		
		$truckExt = db_selTruckExt();
		$vextID = null;
		if (($truckExt != false) && is_array($truckExt)){
			$vextID = array();
			foreach ($truckExt as $key => $ceValue){
				array_push($vextID, $ceValue['vextID']);
			}
		}
		
		$vextra = db_selVExtra(array('notVextID'=>$vextID));		
		
		$this -> view -> vextra = $vextra;
		$this -> view -> truckExt = $truckExt;
	}
	
	private function inserttruckextAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckExt.php');

		$lang = $this -> lang;
		
		//Get vehicle extra details for new extra
		$vextraNew = null;
		$vextraParent = null;
		if (isset($p['vextraNew'])){
			$vextraNew = db_selVExtra(array('vextID'=>$p['vextraNew']));
			$truckExt = db_selTruckExt(array('vextID'=>$p['vextraNew']));
			if ($truckExt != false){
				$vextraNew = null;
			}  
		
			//get vehicle extra details for parent extra
			if ($p['vextraParent'] != $p['vextraNew']){
				if (isset($p['vextraParent']) && ($p['vextraParent'] == -1)){
					$vextraParent = db_selTruckExt(array('lft'=>1));
					if ($vextraParent == false){
						db_insTruckExt(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vextID' => 0));						
						$vextraParent = db_selTruckExt(array('lft'=>1));
					}
				}else{
					$vextraParent = db_selTruckExt(array('vextID' => $p['vextraParent']));
				}
			}else{
				$this -> view -> error = $lang['AERR_31'];
			}
		}
		
		if (($vextraNew != null) && ($vextraParent != null)
			&& is_array($vextraNew) && is_array($vextraParent)){
			$vextraParent = $vextraParent[0];
			$vextraNew = $vextraNew[0];
			db_updTruckExt(array(System_Properties::SQL_SET => array('incLft'=>2)
							, System_Properties::SQL_WHERE => array('lftBEq'=>$vextraParent['rgt'])
								)
						);
			db_updTruckExt(array(System_Properties::SQL_SET => array('incRgt'=>2)
							, System_Properties::SQL_WHERE => array('rgtBEq'=>$vextraParent['rgt'])
								)
						);
			db_insTruckExt(array('lft'=>$vextraParent['rgt']
							, 'rgt' => ($vextraParent['rgt'] +1)
							, 'active' => 1
							, 'vextID' => $vextraNew['vextID']
								)
						);
			$this -> view -> info = $lang['AINFO_12'];
		}				
	}

	/**
	 * Insert a new truck extra
	 */
	public function truckextraeditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		//determine truck vehicle extra id
		$truckVextID = null;
		if (isset($p['teid'])){
			$truckVextID = $p['teid'];
		}
		
		if ($truckVextID != null){
			$truckVext = db_selTruckExt(array('truckExtID' => $truckVextID));
			if (($truckVext != false) && is_array($truckVext)){
				$truckVext = $truckVext[0];
		
				//Edit truck extra
				if (isset($p['truckExtEdit'])){
					$p['truckExtID'] = $truckVextID;
					$p['truckExt'] = $truckVext;
					$this ->edittruckextraAction($p);
					$truckVextH = db_selTruckExt(array('truckExtID' => $truckVextID));
					if (($truckVextH != false) && is_array($truckVextH) && (count($truckVextH) > 0)){
						$truckVext = $truckVextH[0];
					}
				}
				
				$this -> view -> truckVext = $truckVext;
				if ($truckVext['level']-1 > 0){
					$truckVextParent = db_selTruckExt(array('level' => ($truckVext['level']-1)
														, 'lftLEq' => $truckVext['lft']
														, 'rgtBEq' => $truckVext['rgt']
														)
												);
					if (($truckVextParent != false) && is_array($truckVextParent) && (count($truckVextParent) > 0)){													
						$this -> view -> truckVextParent = $truckVextParent[0];
					}
				}
				
				$truckVextAll = db_selTruckExt();
				$this -> view -> truckVextAll = $truckVextAll;
					
			}else{
				$this -> view -> error = $lang['AERR_32'];
				$this -> _forward('truckextra');
			}
		}else{
			$this -> view -> error = $lang['AERR_32'];
			$this -> _forward('truckextra');
		}
	}
	
	private function edittruckextraAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckExt.php');
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$lang = $this -> lang;
		$vextraParent = null;
		$vextraParentAct = null;
		$truckExt = null;
		
		
		if (isset($p['truckExt']) && is_array($p['truckExt']) && isset($p['truckExt']['lft']) && isset($p['truckExt']['rgt'])){
			$truckExt = $p['truckExt'];
			//determine children from current truck extra
			$truckExtChild = db_selTruckExt(array('lftBEq' => $truckExt['lft']
											, 'rgtLEq' => $truckExt['rgt']
											)
										);			
			
			//determine new specified vehicle extra parent
			if (isset($p['vextraParent']) && ($p['vextraParent'] != $truckExt['truckExtID'])) {
				if ($p['vextraParent'] < 1){
					$vextraParent = db_selTruckExt(array('lft'=>'1'));
				}else{
					$vextraParent = db_selTruckExt(array('truckExtID'=>$p['vextraParent']));
				}
								
				if (($vextraParent != false) && is_array($vextraParent) && (count($vextraParent) > 0)){
					$vextraParent = $vextraParent[0];
					
					if (($vextraParent['lft'] < $truckExt['lft']) || ($vextraParent['rgt'] > $truckExt['rgt'])){
						//Set LFT and RGT to 0 of all truck vext and his children 
						$lft = 0;
						db_updTruckExt(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$lft)
										, System_Properties::SQL_WHERE => array('lftBEq'=>$truckExt['lft']
																				, 'rgtLEq' => $truckExt['rgt'])));
						
						//Erase model from actual parent model
						//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($truckExt['children'] +1) *2;
						db_updTruckExt(array(System_Properties::SQL_SET => array('decLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftB' => $truckExt['rgt'])
											));			
						db_updTruckExt(array(System_Properties::SQL_SET => array('decRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtB' => $truckExt['rgt'])
											));	
						
						//select again truck vextra parent					
						if ($vextraParent['lft'] <= 1){
							$vextraParentH = db_selTruckExt(array('lft'=>'1'));
						}else{
							$vextraParentH = db_selTruckExt(array('truckExtID'=>$vextraParent['truckExtID']));
						}
						
						if (($vextraParentH != false) && is_array($vextraParentH) && (count($vextraParentH) > 0)){
							$vextraParent = $vextraParentH[0];
						}					
						
						//Give way to new elements
						//inc LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($truckExt['children'] +1) *2;
						db_updTruckExt(array(System_Properties::SQL_SET => array('incLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftBEq' => $vextraParent['rgt'])
											));			
						db_updTruckExt(array(System_Properties::SQL_SET => array('incRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtBEq' => $vextraParent['rgt'])
											));	
					
						$truckExtH = array();
						foreach($truckExtChild as $cec){
							$cec['diff'] = $cec['rgt'] - $cec['lft'];
							$truckExtH[$cec['lft']] = $cec;
						}
						ksort($truckExtH);
						$truckExtChild = $truckExtH;
						
						$lftStart = $vextraParent['rgt'];
						
						$prevKey = null;
						foreach ($truckExtChild as $key=>$cec){
							//increment the left start value
							if ($prevKey != null){
								$lftStart += $key - $prevKey;
							}				
							
							$lft = $lftStart;
							$rgt = $lft + $cec['diff'];
							db_updTruckExt(array(System_Properties::SQL_SET => array('lft'=>$lft
																				, 'rgt'=>$rgt)
											, System_Properties::SQL_WHERE => array('truckExtID'=>$cec['truckExtID'])
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
		} else{
			$this -> view -> error = $lang['AERR_33'];
		}
		
	}
	
	/**
	 * Manage the deletion of a truck category
	 */
	public function truckcateraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_cntTruckCat.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		$truckCatID = null;
		if (isset($p['tcid'])){
			$truckCatID = $p['tcid'];
		}
		
		if ($truckCatID != null){
				
			//erase truck cat completely
			if (isset($p['truckCatErase2'])){
				$p['truckCatID'] = $truckCatID;
				$this -> erasetruckcatAction($p);
			}
				
			$truckCat = db_selTruckCat(array('truckCatID'=>$truckCatID));
			if (($truckCat != false) && is_array($truckCat) && (count($truckCat) > 0)){
				$truckCat = $truckCat[0];
				
				//select all truck advertisment which reference to this truck cat
				$cntTruckCat = db_cntTruckCat(array('truckCat'=>$truckCat['truckCatID']
												//, 'p' => true
												)
										);
										
				if ($cntTruckCat != false){
					$cntTruckCat = $cntTruckCat[0];
				}
				
				$this -> view -> truckCat = $truckCat;
				$this -> view -> cntTruckCat = $cntTruckCat;
			}else{
				$this -> view -> error = $lang['AERR_36'];
				$this -> _forward('truckcat');
			}
		}else{		
			$this -> view -> error = $lang['AERR_36'];	
			$this -> _forward('truckcat');
		}
	}
	
	private function edittruckcatAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckCat.php');
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$vcatParent = null;
		$vcatParentAct = null;
		$truckCat = null;
		$lang = $this -> lang;
		
		if (isset($p['truckCat']) && is_array($p['truckCat']) && isset($p['truckCat']['lft']) && isset($p['truckCat']['rgt'])){
			$truckCat = $p['truckCat'];
			//determine children from current truck cat
			$truckCatChild = db_selTruckCat(array('lftBEq' => $truckCat['lft']
											, 'rgtLEq' => $truckCat['rgt']
											)
										);			
			
			//determine new specified vehicle cat parent
			if (isset($p['vcatParent']) && ($p['vcatParent'] != $truckCat['truckCatID'])) {
				if ($p['vcatParent'] < 1){
					$vcatParent = db_selTruckCat(array('lft'=>'1'));
				}else{
					$vcatParent = db_selTruckCat(array('truckCatID'=>$p['vcatParent']));
				}
								
				if (($vcatParent != false) && is_array($vcatParent) && (count($vcatParent) > 0)){
					$vcatParent = $vcatParent[0];
					
					if (($vcatParent['lft'] < $truckCat['lft']) || ($vcatParent['rgt'] > $truckCat['rgt'])){
						//Set LFT and RGT to 0 of all truck vcat and his children 
						$lft = 0;
						db_updTruckCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$lft)
										, System_Properties::SQL_WHERE => array('lftBEq'=>$truckCat['lft']
																				, 'rgtLEq' => $truckCat['rgt'])));
						
						//Erase model from actual parent model
						//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($truckCat['children'] +1) *2;
						db_updTruckCat(array(System_Properties::SQL_SET => array('decLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftB' => $truckCat['rgt'])
											));			
						db_updTruckCat(array(System_Properties::SQL_SET => array('decRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtB' => $truckCat['rgt'])
											));	
						
						//select again truck vcat parent					
						if ($vcatParent['lft'] <= 1){
							$vcatParentH = db_selTruckCat(array('lft'=>'1'));
						}else{
							$vcatParentH = db_selTruckCat(array('truckCatID'=>$vcatParent['truckCatID']));
						}
						
						if (($vcatParentH != false) && is_array($vcatParentH) && (count($vcatParentH) > 0)){
							$vcatParent = $vcatParentH[0];
						}					
						
						//Give way to new elements
						//inc LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($truckCat['children'] +1) *2;
						db_updTruckCat(array(System_Properties::SQL_SET => array('incLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftBEq' => $vcatParent['rgt'])
											));			
						db_updTruckCat(array(System_Properties::SQL_SET => array('incRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtBEq' => $vcatParent['rgt'])
											));	
					
						$truckCatH = array();
						foreach($truckCatChild as $cec){
							$cec['diff'] = $cec['rgt'] - $cec['lft'];
							$truckCatH[$cec['lft']] = $cec;
						}
						ksort($truckCatH);
						$truckCatChild = $truckCatH;
						
						$lftStart = $vcatParent['rgt'];
						
						$prevKey = null;
						foreach ($truckCatChild as $key=>$cec){
							//increment the left start value
							if ($prevKey != null){
								$lftStart += $key - $prevKey;
							}				
							
							$lft = $lftStart;
							$rgt = $lft + $cec['diff'];
							db_updTruckCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																				, 'rgt'=>$rgt)
											, System_Properties::SQL_WHERE => array('truckCatID'=>$cec['truckCatID'])
											));				
							$prevKey = $key;										
						}	
					}else{
						$this -> view -> error = $lang['AERR_34'];						
					}
				}else{
					$this -> view -> error = $lang['AERR_34'];						
				}
			}
		} 
		
	}
	
	private function erasetruckcatAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruckCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckAds.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruckBrand2Cat.php');
		
		$lang = $this -> lang;
		
		//truckCat contains  the entry from truck cat
		if (isset($p['truckCatID'])){
			$truckCatID = $p['truckCatID'];
			$truckCat = db_selTruckCat(array('truckCatID' => $truckCatID));
			
			if (($truckCat != false) && is_array($truckCat) && (count($truckCat) > 0)){
				$truckCat = $truckCat[0];
				//fetch all truckCat entries between LFT and RGT value of parent
				if (isset($truckCat['lft']) && isset($truckCat['rgt'])){
					$truckCatChildren = db_selTruckCat(array('lftBEq' => $truckCat['lft']
														, 'rgtLEq' => $truckCat['rgt']
														));
														
					if (($truckCatChildren != false) && is_array($truckCatChildren)){
						//catct all truckCatID from selected truckCat
						$truckCatID = array();
						foreach ($truckCatChildren as $key => $truckCatChild){
							array_push($truckCatID, $truckCatChild['truckCatID']);						
						}
						
						db_delTruckCat(array('truckCatID'=>$truckCatID));						
						db_updTruckCat(array(System_Properties::SQL_SET => array('decLft'=>($truckCat['children']+1)*2)
										, System_Properties::SQL_WHERE=>array('lftBEq'=>$truckCat['lft']))
										);			
						db_updTruckCat(array(System_Properties::SQL_SET=>array('decRgt'=>($truckCat['children']+1)*2)
										, System_Properties::SQL_WHERE=>array('rgtBEq'=>$truckCat['lft']))
										);
						
						db_updTruckAds(array(System_Properties::SQL_SET => array('truckCat' => -1)
										, System_Properties::SQL_WHERE => array('truckCat' => $truckCat['truckCatID'])));
						//db_delTruck2Cat(array('truckCatID'=>$truckCatID));
						$this -> view -> info = $lang['AINFO_17'];
					}
				}
			}
		}
	}
	
	/**
	 * Manage the deletion of a truck extra
	 */
	public function truckextraeraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_cntTruck2Ext.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		$truckExtID = null;
		if (isset($p['teid'])){
			$truckExtID = $p['teid'];
		}
		
		if ($truckExtID != null){
				
			//erase truck extra completely
			if (isset($p['truckExtErase2'])){
				$p['truckExtID'] = $truckExtID;
				$this -> erasetruckextraAction($p);
			}
				
			$truckExt = db_selTruckExt(array('truckExtID'=>$truckExtID));
			if (($truckExt != false) && is_array($truckExt) && (count($truckExt) > 0)){
				$truckExt = $truckExt[0];
				
				//select all truck advertisment which reference to this truck extra
				$cntTruck2Ext = db_cntTruck2Ext(array('truckExtID'=>$truckExt['truckExtID']
												//, 'p' => true
												)
										);
										
				if ($cntTruck2Ext != false){
					$cntTruck2Ext = $cntTruck2Ext[0];
				}
				
				$this -> view -> truckExt = $truckExt;
				$this -> view -> cntTruck2Ext = $cntTruck2Ext;
			}else{
				$this -> view -> error = $lang['AERR_32'];
				$this -> _forward('truckextra');
			}
		}else{			
			$this -> _forward('truckextra');
		}
	}
	
	private function erasetruckextraAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruck2Ext.php');
		$lang = $this -> lang;
		//truckExt contains  the entry from truck extra
		if (isset($p['truckExtID'])){
			$truckExtID = $p['truckExtID'];
			$truckExt = db_selTruckExt(array('truckExtID' => $truckExtID));
			
			if (($truckExt != false) && is_array($truckExt) && (count($truckExt) > 0)){
				$truckExt = $truckExt[0];
				//fetch all truckExt entries between LFT and RGT value of parent
				if (isset($truckExt['lft']) && isset($truckExt['rgt'])){
					$truckExtChildren = db_selTruckExt(array('lftBEq' => $truckExt['lft']
														, 'rgtLEq' => $truckExt['rgt']
														));
														
					if (($truckExtChildren != false) && is_array($truckExtChildren)){
						//extract all truckExtID from selected truckExt
						$truckExtID = array();
						foreach ($truckExtChildren as $key => $truckExtChild){
							array_push($truckExtID, $truckExtChild['truckExtID']);						
						}
						db_delTruckExt(array('truckExtID'=>$truckExtID));						
						db_updTruckExt(array(System_Properties::SQL_SET => array('decLft'=>($truckExt['children'] +1) * 2)
										, System_Properties::SQL_WHERE=>array('lftBEq'=>$truckExt['lft']))
										);			
						db_updTruckExt(array(System_Properties::SQL_SET=>array('decRgt'=>($truckExt['children'] +1)*2)
										, System_Properties::SQL_WHERE=>array('rgtBEq'=>$truckExt['lft']))
										);
						
						db_delTruck2Ext(array('truckExtID'=>$truckExtID));
						$this -> view -> info = $lang['AINFO_16'];
					}
				}
			}
		}
	}

	
	/**
	 * Manage truck cat 
	 */
	public function truckcatAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		
		//fetch all request parameters
		$p = $this -> getRequest() -> getParams();
		
		//insert a new truck cat entry
		if (isset($p['truckCatNew'])){
			$this -> inserttruckcatAction($p);
		}
		
		$truckCat = db_selTruckCat();
		$vcatID = null;
		if (($truckCat != false) && is_array($truckCat)){
			$vcatID = array();
			foreach ($truckCat as $key => $ceValue){
				array_push($vcatID, $ceValue['vcatID']);
			}
		}
		
		$vcat = db_selVCat(array('notVcatID'=>$vcatID));		
		
		$this -> view -> vcat = $vcat;
		$this -> view -> truckCat = $truckCat;
	}
	private function inserttruckcatAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckCat.php');
		$lang = $this -> lang;
		//Get vehicle extra details for new category
		$vcatNew = null;
		$vcatParent = null;
		if (isset($p['vcatNew'])){
			$vcatNew = db_selVCat(array('vcatID'=>$p['vcatNew']));
			$truckCat = db_selTruckCat(array('vcatID'=>$p['vcatNew']));
			if ($truckCat != false){
				$vcatNew = null;
			}  
		
			//get vehicle cat details for parent cat
			if ($p['vcatParent'] != $p['vcatNew']){
				if (isset($p['vcatParent']) && ($p['vcatParent'] == -1)){
					$vcatParent = db_selTruckCat(array('lft'=>1));
					if ($vcatParent == false){
						db_insTruckCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
						$vcatParent = db_selTruckCat(array('lft'=>1));
					}
				}else{
					$vcatParent = db_selTruckCat(array('vcatID' => $p['vcatParent']));
				}
			}else{
				$this -> view -> error = $lang['AERR_34'];
			}
		}
		
		if (($vcatNew != null) && ($vcatParent != null)
			&& is_array($vcatNew) && is_array($vcatParent)){
			$vcatParent = $vcatParent[0];
			$vcatNew = $vcatNew[0];
			db_updTruckCat(array(System_Properties::SQL_SET => array('incLft'=>2)
							, System_Properties::SQL_WHERE => array('lftBEq'=>$vcatParent['rgt'])
								)
						);
			db_updTruckCat(array(System_Properties::SQL_SET => array('incRgt'=>2)
							, System_Properties::SQL_WHERE => array('rgtBEq'=>$vcatParent['rgt'])
								)
						);
			db_insTruckCat(array('lft'=>$vcatParent['rgt']
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
	 * Edit a truck category
	 */
	public function truckcateditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		//determine truck vehicle cat id
		$truckVcatID = null;
		if (isset($p['tcid'])){
			$truckVcatID = $p['tcid'];
		}
		
		if ($truckVcatID != null){
			$truckVcat = db_selTruckCat(array('truckCatID' => $truckVcatID));
			if (($truckVcat != false) && is_array($truckVcat)){
					$truckVcat = $truckVcat[0];
			
					//Edit truck category
					if (isset($p['truckCatEdit'])){
						$p['truckCatID'] = $truckVcatID;
						$p['truckCat'] = $truckVcat;
						$this ->edittruckcatAction($p);
						$truckVcatH = db_selTruckCat(array('truckCatID' => $truckVcatID));
						if (($truckVcatH != false) && is_array($truckVcatH) && (count($truckVcatH) > 0)){
							$truckVcat = $truckVcatH[0];
							$this -> view -> info = $lang['AINFO_15'];
						}
					}
					
					$this -> view -> truckVcat = $truckVcat;
					if ($truckVcat['level']-1 > 0){
						$truckVcatParent = db_selTruckCat(array('level' => ($truckVcat['level']-1)
															, 'lftLEq' => $truckVcat['lft']
															, 'rgtBEq' => $truckVcat['rgt']
															)
													);
						if (($truckVcatParent != false) && is_array($truckVcatParent) && (count($truckVcatParent) > 0)){													
							$this -> view -> truckVcatParent = $truckVcatParent[0];
						}
					}
					
					$truckVcatAll = db_selTruckCat();
					$this -> view -> truckVcatAll = $truckVcatAll;
					
			}else{
				$this -> _forward('truckcat');
			}
		}else{
			$this -> _forward('truckcat');
		}
	}
}
?>