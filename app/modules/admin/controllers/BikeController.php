<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101216
 * Desc:		This is the administrator controller for maintanence groups
 *********************************************************************************/
require_once('classes/AbstractController.php');
class Admin_BikeController extends AbstractController{
	private $actParam;
	
	
	public function preDispatch(){
		parent::preDispatch();			
			
		//Set session namespace properties
		include_once('Zend/Session/Namespace.php');		
		
		if (!isset($this -> adminNS -> adminData)
			|| !isset($this -> adminNS -> adminLogged)
			|| ($this -> adminNS -> adminLogged != true)) {
			$this -> _forward('index', 'notlogged');
		}
		
	
		$action = $this -> getRequest() -> getActionName();
		$req = $this -> getRequest();
		//Check Authority for "BIKE_EDIT" action
		if(($action == 'detail') && ($req -> __isset('bikeSafe'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		elseif(($action == 'aful')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		elseif(($action == 'agfe')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		//Check Authority for "BIKE_DELETE" action
		elseif(($action == 'detail') && ($req -> __isset('bikeDel'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "BIKE_CREATE" action
		else if(($action == 'insert') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
	
		//check authority BIKE_EXTRA_CREATE
		else if(($action == 'bikeextra') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EXTRA_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority BIKE_EXTRA_EDIT
		else if(($action == 'bikeextraedit') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EXTRA_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority BIKE_EXTRA_ERASE
		else if(($action == 'bikeextraerase') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EXTRA_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
	
	
		//check authority BIKE_CAT_CREATE
		else if(($action == 'bikecat') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CAT_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority BIKE_CAT_EDIT
		else if(($action == 'bikecatedit') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CAT_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//check authority BIKE_CAT_ERASE
		else if(($action == 'bikecaterase') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CAT_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this->tmpl;//$this -> getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;			
	}
	
	public function indexAction(){
		$this -> loadBikeModelsBrands();
	}
	
	
	/**
	 * This function load bike brands and their corresponding bike modelss
	 */
	private function loadBikeModelsBrands($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		
		$bikeBrand = db_selBikeBrand(array('orderby'=>array(array('col' => 'brandName'))
											,'active' => 1));
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
			$bikeModel = db_selBikeModel(array('bikeBrandID' => $bikeBrandID));
		}
		
		$this -> view -> bikeBrand = $bikeBrand;
		$this -> view -> bikeModel = $bikeModel;
	}
	
	/**
	 * This AJAX method return for a specific bike brand the corresponding bike models
	 * Input parameter:
	 * @param	cid:	this parameter specify the bike id
	 * 
	 * Output parameter:
	 * @param	r:	indicate if a request ist successfully processed (true) or not (false)
	 * @param	cm: compose with the name and id of a bike model orderer by model name
	 * 			@param	cmn:	bike model name
	 * 			@param	cmid:	bike model id
	 */
	public function ajagmAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$req = $this -> getRequest();
		
		$return = array('r' => false, 'bm' => array());
		
		$r_bikeBrandID = $req -> getParam('bid');
		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		$bikeBrand = db_selBikeBrand(array('bikeBrandID' => $r_bikeBrandID, 'active'=>'1'));
		
		if (($bikeBrand != false) && is_array($bikeBrand)){
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
			$bikeModels = db_selBikeModel(array('bikeBrandID' => $bikeBrand[0]['bikeBrandID'],
												'orderby' => array(
															array('col' => 'lft')
															, array('col' => 'bikeModelName')
												)
										)
						);
			if (($bikeModels != false) && is_array($bikeModels)){
				$return['r'] = true;
				foreach ($bikeModels as $bikeModel) {
					$bm = array('bmn' => $bikeModel['bikeModelName'],
								'bmid' => $bikeModel['bikeModelID']
								, 'bml' => $bikeModel['level']);
					array_push($return['bm'], $bm);
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
		$bikeSearchParam = $this -> adminNS -> bikeSearchParam;
		$p = $req -> getParams();
		
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		if ($req -> __isset('search1')){
			$p = $req -> getParams();
			$this -> adminNS -> bikeSearchParam = $p;
		}elseif (is_array($bikeSearchParam)){
			$p = $bikeSearchParam;
		}		
		
		/*
		 * 
				if (is_array($bikeSearchParam)){
					$p = $bikeSearchParam;			
					//Set old page
					$p['page'] = $page;
				}*/
		
		//Search bike advertisement in database
		if (isset($p['adNum']) && is_numeric($p['adNum'])){
			$bikeAds = $this -> searchBikeAds(array('bikeID' => $p['adNum']));
		}else{			
			//Sort options
			if (isset($p['bikeSort']) && ($p['bikeSort'] != -1)){
				$p['orderby'] = array();
				
				//Determine col
				switch ($p['bikeSort']) {
					//Price
					case 0: $col = 'bikePrice';
							break;						
					//Leistung
					case 1: $col = 'bikePower';
							break;
					//Laufleistung
					case 2: $col = 'bikeKM';
							break;
					//Datum
					case 3: $col = 'timestam';
							break;
					default: $col = 'bikePrice';
							break;
				}
				
				//Determine sort orientation
				$desc = false;
				if (isset($p['bikeSortOpt']) && ($p['bikeSortOpt'] == 1)){
					$desc = true;
				}
				
				array_push($p['orderby'], array('col' => $col
												, 'desc' => $desc
												)
											);
				
				
			}
			
			$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
			$bikeAds = $this -> searchBikeAds($p);
		}
			
		//Search process successfully passed and found some matches
		
		if (is_array($bikeAds) && (count($bikeAds) > 0)){
			//Check if javascript is active
			if(isset($p['jsActive']) && ($p['jsActive'] == 'on')){
				$bikeAds['jsActive'] = $p['jsActive'];
			}
			
			$bikeAds['numAds'] = System_Properties::NUM_ADS;
			$bikeAds['actPage'] = $page;
			
			$this -> actParam = $p;
			$this -> view -> searchParam = $p;
			
			$this -> view -> bikeAds = $bikeAds;
		}		
		else{
			$this -> view -> error = $lang['ERR_4'];
			
			$this -> actParam = $p;
			$this -> view -> searchParam = $p;				
			
			//$this -> indexAction();
			//$this -> render('index');
		}
		$this -> loadBikeModelsBrands($p);
	}
	
	/**
	 * This function perform an update of a bike advertisement
	 */
	private function updatebikeAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBike2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBike2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_delVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_updVPic.php');
		
		$p = $this -> filterRecvParam($p);
		
		if (!isset($p['error'])) {
			$p['erased'] = 0;
			$bikeID = $p['bikeID'];
			$updBike = db_updBikeAds(array(System_Properties::SQL_SET => $p
										, System_Properties::SQL_WHERE => array('bikeID' => $bikeID)
										));
			//update BIKE extra
			db_delBike2Ext(array('bikeID' => $p['bikeID']));
			if(isset($p['bikeExtDB']) && is_array($p['bikeExtDB'])){
				foreach ($p['bikeExtDB'] as $key=>$kVal){
					db_insBike2Ext(array('bikeID'=>$p['bikeID']
										, 'bikeExtID'=>$kVal['bikeExtID']
										));
				}
			}
			
			//Update fotos
			$bikePicNS = $this -> adminNS -> bikePhoto;
			if (is_array($bikePicNS)){
				$vPicID = array();
				foreach ($bikePicNS as $key => $kVal){
					array_push($vPicID, $kVal['vPicID']);
				}
				db_updVPic(array('vPicID' => $vPicID
								, 'vPicTMP' => '0'
								));
								
				$notUpdPic = db_selVPic(array('notVPicID' => $vPicID
											, 'vID' => $bikeID
											));
											
				if (($notUpdPic != false) && is_array($notUpdPic) && (count($notUpdPic) > 0)){
					$vPicID = array();
					foreach ($notUpdPic as $key => $kVal) {
						array_push($vPicID, $kVal['vPicID']);
					}
					db_delVPic(array('vPicID'=>$vPicID));
				}
				/*
				foreach ($bikePicNS as $cpns){
					//Should a photo be deleted?
					if (isset($cpns['del']) && ($cpns['del'] == true ) && ($cpns['vID'] == $p['bikeID'])){
						$db_delVPic = db_delVPic(array('vPicID' => $cpns['vPicID']));
						if ($db_delVPic != false){							
							$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.System_Properties::BIKE_ABRV.'_'.$cpns['vID'].'_'.$cpns['vPicID'].'.jpeg';
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
	private function searchBikeAds($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		
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
		
		$p['priceWMwst'] = true;
		
		$bikeAds = db_selBikeAds($p);
		//Search process successfully passed and found some matches
		if (is_array($bikeAds) && ($bikeAds != false)){
			$bikeAdsP = array('totalAds'=>$bikeAds['totalRows'], 'bikeAds' => array());
			/*
			//Check if javascript is active
			if(isset($p['jsActive']) && ($p['jsActive'] == 'on')){
				$bikeAdsP['jsActive'] = $p['jsActive'];
			}
			*/
			
			//For each bike ad select the correspondend bike picutres
			foreach ($bikeAds AS $key => $bikeAd){
				if (is_numeric($key)){
					$bikeAd['bikePics'] = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV,
															'vID' => $bikeAd['bikeID']));					
					array_push($bikeAdsP['bikeAds'], $bikeAd);
				}				
			}
			$bikeAds = $bikeAdsP;
					/*
			$bikeAdsP['numAds'] = System_Properties::NUM_ADS;
			$bikeAdsP['actPage'] = $page;
			$this -> view -> bikeAds = $bikeAdsP;		
			$this -> render('search2');
		}		
		else{
			$lang = $this -> view -> tmpl -> getLang();
			$this -> view -> error = $lang['ERR_4'];
			$this -> search1Action();*/
		}
		return $bikeAds;
	}
	
	/**
	 * This function load all bike brands and models
	 */
	public function detailAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBike2Ext.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		$req = $this -> getRequest();
		$lang = $this -> lang;
		$p = $req -> getParams();
		
		if (isset($p['id'])){			
			$bikeDetails = db_selBikeAd(array('bikeID' => $p['id']));
			if (($bikeDetails != false) && is_array($bikeDetails) && (count($bikeDetails) == 1)){				
				$bikeDetails = $bikeDetails[0];
				
				if (is_array($this -> adminNS -> bikeAds) && isset($this -> adminNS -> bikeAds['bikeID'])
					&& ($this -> adminNS -> bikeAds['bikeID'] !=  $bikeDetails['bikeID'])){
					$this -> resetnsAction();
				}
				//Safe changes
				if (isset($p['bikeSafe'])){
					$p['bikeID'] = $bikeDetails['bikeID'];
					$p['bikeBrandID'] = $p['bikeBrand'];
					$p = $this -> updatebikeAction($p);
					if (isset($p['error'])){
						$this -> view -> error = $p['error'];
					}else{
						$bikeDetails2 = db_selBikeAd(array('bikeID' => $p['id']));
						if (($bikeDetails2 != false) && is_array($bikeDetails2) && (count($bikeDetails2) == 1)){
							$bikeDetails  = $bikeDetails2[0];
						}
						$this -> view -> info = $lang['AINFO_4'];
					}
				}
				//Delete advertisement
				else if (isset($p['bikeDel'])){
					$bikeAd = db_updBikeAds(array(System_Properties::SQL_WHERE => array('bikeID' => $bikeDetails['bikeID'])
												, System_Properties::SQL_SET => array('erased' => 1)
												)
											);
					if ($bikeAd != false){
						$this -> _forward('index');
					}
				}
				//Load all bike extra
				$this -> loadBikeExt();
				
				//Load bike extra
				$bikeExt = db_selBike2Ext(array('bikeID' => $bikeDetails['bikeID']));
				if (($bikeExt != false) && is_array($bikeExt) && (count($bikeExt) > 0)){
					$bikeDetails['bikeExt'] = array();
					foreach ($bikeExt as $key => $val){
						array_push($bikeDetails['bikeExt'], $val['vextID']);
					}					
				}				
				
				//Fetch all bike pics
				if (isset($this -> adminNS -> bikePhoto) && is_array($this -> adminNS -> bikePhoto)){
					$bikePhoto = $this -> adminNS -> bikePhoto;
				}	
				else{
					$bikePhoto = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV,
													'vID' => $bikeDetails['bikeID']));
					$bikeDetails['bikePhoto'] = array();
					foreach($bikePhoto as $key => $kVal){
						$bikeDetails['bikePhoto'][$kVal['vPicID']] = $kVal;
					}
					$bikePhoto = $bikeDetails['bikePhoto'];
				}
				$bikeDetails['bikePhoto'] = $bikePhoto;
				
				//Load brands and models				
				$this -> loadBikeModelsBrands(array('bikeBrand' => $bikeDetails['bikeBrandID']));
				//Load all possible bike extra
				$this -> loadBikeExt();
				$this -> loadBikeCat();
				$this -> view -> bike = $bikeDetails;
				
				$this -> adminNS -> bikeAds = $bikeDetails;
				$this -> adminNS -> bikePhoto = $bikePhoto;
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
	 * This function load all bike categories.
	 */
	private function loadBikeCat(){
		include_once ('default/models/bike/db_selBikeCat.php');
		$bikeCat = db_selBikeCat();
		if($bikeCat != false){
			$this -> view -> bikeCat = $bikeCat;
		}
		return $bikeCat;
	}
	/**
	 * This function load all bike extras.
	 */
	private function loadBikeExt(){
		/*
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selExtra.php');
		$bikeExt = db_selExtra(array('vType' => 'c'));
		if($bikeExt != false){
			$this -> view -> bikeExt = $bikeExt;
		}
		*/
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		$bikeExt = db_selBikeExt(array('lftBE'=>1));
		if($bikeExt != false){
			$this -> view -> bikeExt = $bikeExt;
		}
	}
	
	/**
	 * This function filter a bike advertisement
	 * @param $p:	this variable contains the parameter of a bike advertisement
	 */
	private function filterRecvParam($p){
		$lang = $this -> lang;
		$bikeCatDB = $this -> loadBikeCat();
		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		
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
		$bikeBrand = db_selBikeBrand(array('bikeBrandID'=>$p['bikeBrand']
									, 'active' => 1));
		
		//Check bikeBrand
		if (!isset($p['bikeBrand'])
			|| ($bikeBrand == false)
			){
				$p['error'] = $lang['ERR_2'];
		}
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
		else if (!isset($p['bikeEZM']) || !isset($p['bikeEZY'])
				|| ($fMonth -> isValid($p['bikeEZM']) == false)
				|| ($fYear -> isValid($p['bikeEZY']) == false)){
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
			
			$p['bikeBrandID'] = $bikeBrand[0]['bikeBrandID'];
			
			//Check Model
			$p['bikeModelTxt'] = null;
			if (isset($p['bikeModel']) && ($p['bikeModel'] != -1)){				
				$bikeModel = db_selBikeModel(array(	'bikeBrandID' => $bikeBrand[0]['bikeBrandID'],
													'bikeModelID' => $p['bikeModel'])
											);
				if(($bikeModel != false) && is_array($bikeModel) && (count($bikeModel) > 0)){
					$bikeModel = $bikeModel[0];
					$p['bikeModelTxt'] = $bikeModel['bikeModelName'];
					$p['bikeModelID'] = $bikeModel['bikeModelID'];
				}
			}else{
				$p['bikeModel'] = -1;
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
			if(isset($p['mwst']) && ($p['mwst'] !== '0')){
				$p['mwst'] = 1;
			}
			if(isset($p['mwstSatz']) && isset($lang['V_MWST']) && is_array($lang['V_MWST'])){
				$p['mwstSatz'] = str_replace(',', '.', $p['mwstSatz']);
				if(!in_array($p['mwstSatz'], $lang['V_MWST']) && ($p['mwst'] === '0')){
					$p['mwstSatz'] = 19;
				}
				
			}
			
			if(!isset($p['bikePowerType']) || ($p['bikePowerType'] == null)){
				$p['bikePowerType'] = 0;
			}else{
				$p['bikePowerType'] = $fMInt->filter($p['bikePowerType']);
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
			
			$p['bikeUseIn'] = $fString50->filter($p['bikeUseIn']);
			$p['bikeUseOut'] = $fString50->filter($p['bikeUseOut']);
			$p['bikeCO2'] = $fString50->filter($p['bikeCO2']);
			
			if(!isset($p['bikeState']) || ($p['bikeState'] == null)){
				$p['bikeState'] = -1;
			}else{
				$p['bikeState'] = $fTInt->filter($p['bikeState']);
			}
			
			if(!isset($p['bikeCat']) || ($p['bikeCat'] == null)){
				$p['bikeCat'] = -1;
			}else{
				$found = false;
				$p['bikeCat'] = $fTInt->filter($p['bikeCat']);
				foreach ($bikeCatDB as $key=>$kVal){
					if ($kVal['bikeCatID'] == $p['bikeCat']){
						$found = true;
						break;						
					}
				}
				if ($found == false){
					$p['bikeCat'] = -1;
				}
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
			
			//check bikeLocOrt
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
				include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');			
				$bikeExt = db_selBikeExt(array('vextID'=>$p['bikeExt']));
			}
			$p['bikeExtDB'] = $bikeExt;
		}
		return $p;
	}
	
	/**
	 * This is an ajax complete function which save an uploaded photo
	 * Input paramter: 
	 * @param	cid:	this parameter specify the idetifier of the bike advertisement 
	 * 
	 * Output parameter: 
	 * @param	r:	indicate if a request is successfully processed (true) or not (false)
	 * @param	tu: this parameter specify the URL to the uploaded picture
	 * @param	h: 	this parameter specify the hash code of modified picture
	 */
	public function ajafulAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAd.php');
		
		//Initialize returning parameter
		$return = array('r' => false);
		$req = $this -> getRequest();
		$adminDetails = $this -> adminNS -> adminData;
		if ($req -> __isset('bid') && $req->__isset('t')){
			$bikeID = $req -> getParam('bid');
			$bikeDetails = db_selBikeAd(array('bikeID' => $bikeID));
			if (($bikeDetails != false)){
				$uploadRes = $this -> uploadPhoto(array('bikeID' => $bikeDetails[0]['bikeID']
														, 'bikeDetail' => $bikeDetails
														)
													);
				if (($uploadRes != false) 
					&& is_array($uploadRes)
					&& isset($uploadRes['r'])
					&& ($uploadRes['r'] == true)){
					
					if (!isset($this -> adminNS -> bikePhoto) || !is_array($this -> adminNS -> bikePhoto)){
						$this -> adminNS -> bikePhoto = array();
					}
					//array_push($this -> adminNS -> bikePhoto, $uploadRes['bikePhoto']);
					$this -> adminNS -> bikePhoto[$uploadRes['bikePhoto']['vPicID']] = $uploadRes['bikePhoto'];
					
					
					$return['r'] = true;		
					$return['h'] = $uploadRes['bikePhoto']['vPicID'];
					$return['bid'] = $bikeID;
					$return['t'] = $req->getParam('t');
					$return['tu'] = $uploadRes['bikePhoto']['destURI'];				
				}
			}
		}
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}

	
	
	/**
	 * This function erase an uploaded bike picture while inserting a bike advertisement
	 * 
	 * Input parameter:
	 * @param pid:		this parameter specify the temporary bike picture id
	 * 
	 * Output parameter:
	 * @param r:		this parameter specify if that the request is processed successfully
	 * 
	 * 
	 */
	public function ajagfeAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAd.php');
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
							
					$newBikePhoto = array();
					$bikePhoto = $this -> adminNS -> bikePhoto;
					foreach ($bikePhoto as $key=>$val){
						if ($key != $vPicID){
							$newBikePhoto[$key] = $val;
						}else{							
							//delete picutres from filesystem
							if(isset($vPic['vType']) && isset($vPic['vID']) && isset($vPic['vPicID'])){
								$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($vPic['vType']).'_'.$vPic['vID'].'_'.$vPic['vPicID'].'.jpeg';
								@unlink($picPath);
							}
						}
					}
					$this -> adminNS -> bikePhoto = $newBikePhoto;
					$return['r'] = true;
					$return['pid'] = $vPicID;
					
					//Log system activity, photo upload delete successful
					/*
					$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_PHOTO_PHOTO_DELETE
													,'activityRes' => 1
													, 'systemLogData' => $p
													, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
													));*/
				}
			}
		}else{
			//Log system activity, photo upload delete unsuccessful
			/*
			$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_PHOTO_PHOTO_DELETE
											,'activityRes' => 0
											, 'systemLogData' => $p
											, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
											));
											*/
		}
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	private function obsolete_agfeAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		
		$return = array('r' => false);
		$adminDetails = $this -> adminNS -> adminData;
		
		if (isset($this -> adminNS -> bikeAds) && is_array($this -> adminNS -> bikeAds)
			&& isset($this -> adminNS -> bikeAds['bikeID'])){
				
			$bikeDetails = db_selBikeAd(array('bikeID' => $this -> adminNS -> bikeAds['bikeID']));
			if (($bikeDetails != false) && is_array($bikeDetails) && (count($bikeDetails) == 1) ){
					
				$bikeDetails = $bikeDetails[0];
				$vPicID = $this -> getRequest() -> getParam('pid');
				
				$vPicDetails = db_selVPic(array('vPicID' => $vPicID
												, 'vID' => $bikeDetails['bikeID']
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

					$newBikePhoto = array();
					$bikePhoto = $this -> adminNS -> bikePhoto;
					foreach ($bikePhoto as $key=>$val){
						if ($val['vPicID'] != $vPicID){
							$newBikePhoto[$val['vPicID']] = $val;
						}
					}
					$this -> adminNS -> bikePhoto = $newBikePhoto;
					*/
					
					$bikePhotoNS = $this -> adminNS -> bikePhoto;			
					$this -> adminNS -> bikePhoto = array();
					foreach ($bikePhotoNS AS $bikePhoto){
						if ($bikePhoto['vPicID'] == $vPicDetails['vPicID']) {
							$bikePhoto['del'] = true;
						}
						array_push($this -> adminNS -> bikePhoto, $bikePhoto);
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
					$newBikePhoto = array();
					$bikePhoto = $this -> bikeNS -> bikePhoto;
					foreach ($bikePhoto as $key=>$val){
						if ($key != $vPic['vPicID']){
							$newBikePhoto[$key] = $val;
						}
					}
					$this -> bikeNS -> bikePhoto = $newBikePhoto;
					
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
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_delVPic.php');
		
		$return = array('r' => false);	
		$imgCtrl = new Zend_File_Transfer_Adapter_Http();
		
		//This is the bike advertisement
		//$p = $this -> bikeNS -> bikeAds;			
		if(isset($p['bikeID']) && ($imgCtrl->isUploaded() == true)){
			if (!isset($p['bikeDetail'])){
				$bikeDetail = db_selBikeAd(array('bikeID'=>$p['bikeID']));				
			}else{
				$bikeDetail = $p['bikeDetail'];
			}
			
			//Check if bike advertisement exists
			if (($bikeDetail != false) && (count($bikeDetail) == 1)){
				$bikeDetail = $bikeDetail[0];
				$bikeID = $bikeDetail['bikeID'];										
				$vPicID = db_insVPic(array(	'vType' => System_Properties::BIKE_ABRV
									 		, 'vID' => $bikeID
											, 'vPicTMP' => '1'
											)
										);
				if (($vPicID != false) && is_numeric($vPicID)){		
					$imgCtrl -> setOptions(array('useByteString'  => false));
					$imgCtrl -> addValidator('FilesSize', false, System_Properties::PIC_FILE_SIZE);
					$imgCtrl -> addValidator('Extension', false, System_Properties::$PIC_EXT);
					
					//$imgHash = session_id().'_'.$imgCtrl -> getHash();
					$destURI = System_Properties::PIC_PATH.'/'.System_Properties::BIKE_ABRV.'_'.$bikeID.'_'.$vPicID.'.jpeg';				
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
					//if (!isset($this -> bikeNS -> bikePhoto[$imgHash]) && $imgCtrl -> receive()){	
					if (in_array(strtolower($mimeTypeDetails), System_Properties::$PIC_EXT) 
						&& ($imgCtrl -> receive())){
						$bikePhoto = db_selVPic(array('vPicID' => $vPicID
													, 'vPicTMP' => '1'
													)
												);
						if($bikePhoto != false){
							$bikePhoto = $bikePhoto[0];
							$bikePhoto['destURI'] = $destURI;
							$bikePhoto['vPicNew'] = true;
							$return['r'] = true;
							$return['bikePhoto'] = $bikePhoto;
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
		$this -> adminNS -> bikeAds = null;
		$this -> adminNS -> bikePhoto = null;
		$this -> adminNS -> bikeInsert = false;
		*/
		
		$this -> adminNS -> __unset('bikeAds');
		$this -> adminNS -> __unset('bikePhoto');
		$this -> adminNS -> __unset('bikeInsert');		
	}
	
	public function insertAction(){
		//$this -> resetnsAction();
		$req = $this->getRequest();
		//First insert
		if ($req -> __isset('ins2') && (!isset($this -> adminNS -> bikeInsert) || ($this -> adminNS -> bikeInsert == false))){
			$this -> insert2Action();
		}
		//User confirm the correctness of the advertisment
		else if ($req -> __isset('ins3') && (!isset($this -> adminNS -> bikeInsert) || ($this -> adminNS -> bikeInsert == false))){
			$this -> insert3Action();
		}
		//Advertisment is committed to database and the user safe all fotos.
		else if (isset($this -> adminNS -> bikeInsert)
					&& ($this -> adminNS -> bikeInsert == true)
					&& isset($this -> adminNS -> bikeAds)
					&& ($req -> __isset('safeFoto'))){
			$this -> insert7Action();
		}
		//Advertisment is commited to database, so show the inserted advertisement
		else if (isset($this -> adminNS -> bikeInsert) 
					&& ($this -> adminNS -> bikeInsert == true) 
					&& isset($this -> adminNS -> bikeAds)){
			$this -> insert4Action();
		}
		else{
			$this -> resetnsAction();
			$this -> insert1Action();
		}
	}
	
	private function insert1Action(){
		$this -> loadBikeModelsBrands();
		$this -> loadBikeExt();
		$this -> loadBikeCat();
		if (isset($this -> actParam) && ($this -> actParam != null)){
			$this -> loadBikeModelsBrands(array('bikeBrand' => $this -> actParam['bikeBrand']));
			$this -> view -> bike = $this -> actParam;
		}
		$this -> render('insert1');
	}
	
	private function insert2Action(){
		//First check the receveid parameters
		$p = $this -> filterRecvParam($this -> getRequest() -> getParams());
		$this -> actParam = $p;
		//Forward only if an error occurs
		if(isset($p['error'])){		
			//$this -> loadBikeModelsBrands();
			$this -> view -> error = $p['error'];
			//$this -> render('insert1');
			//$this -> _forward('insert', null, null, array('error' => $error));
			$this -> insert1Action();
		}		
		else{			
			//Set session namespace
			$this -> loadBikeCat();
			$this -> adminNS -> bikeAds = $p;			
			$this -> view -> bike = $p;
			//$this -> view -> bikePhoto = $this -> bikeNS -> bikePhoto;
			
			$this -> render('insert2');	
		}	
	}
	
	private function insert3Action(){
		if(isset($this -> adminNS -> bikeAds)){
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeAds.php');
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBike2Ext.php');
			include_once (System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
			$p = $this -> adminNS -> bikeAds;
			$user = $this -> adminNS -> adminData;
			$this -> actParam = $p;
			$lang = $this -> lang;
			
			//Filter the bike advertisement
			$p = $this -> filterRecvParam($p);
			
			if (!isset($p['error']) && is_array($user) && isset($user['userID'])){
				//Advertising is successful
				$p['userID'] = $user['userID'];
				$p['userAds'] = 3; //3 = System advertisment
				$bikeID = db_insBikeAds($p);
				if(($bikeID != false) && is_numeric($bikeID)){
					//insert bike extra if bikeExtDB extist
					if (isset($p['bikeExtDB']) && is_array($p['bikeExtDB'])){
						foreach ($p['bikeExtDB'] as $key=>$kVal){
							if (is_array($kVal) && isset($kVal['vextID'])){
								$bikeExt = db_selBikeExt(array('vextID' => $kVal['vextID']));
								if (($bikeExt != false) && is_array($bikeExt) && (count($bikeExt) > 0)){
									$bikeExt = $bikeExt[0];
									if (isset($bikeExt['bikeExtID'])){
										db_insBike2Ext(array('bikeID'=>$bikeID
															, 'bikeExtID' =>$bikeExt['bikeExtID'] 
															));
									}
								}
							}
						}
					}				
						
					$p['bikeID'] = $bikeID;
					$this -> adminNS -> bikeAds = $p;
					
					//Set bikeInsert to true because the bike advertisment is successfully inserted.
					$this -> adminNS -> bikeInsert = true;	
						
					$this -> view -> bike = $p;	
					//$this -> view -> bikePhoto = $this -> bikeNS -> bikePhoto;
					$this -> render('insert3');
				}
				//Forward only if an error occurs
				else{
					if (!isset($p['error'])){
						$p['error'] = $lang['ERR_2'];
					}
					
					//$this -> loadBikeModelsBrands();
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
			//$this -> loadBikeModelsBrands();
			//$this -> render('insert1');
			$this -> insert1Action();
		}
	}
	
	/**
	 * Processed if the user press the refresh button
	 */
	private function insert4Action(){		
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/default/db_selVPic.php');
		$bike = $this -> adminNS -> bikeAds;
		$this -> actParam = $bike;		
		$this -> loadBikeCat();
					
		if (isset($bike['bikeID'])){
			$bikePhotos = db_selVPic(array(	'vType'=>System_Properties::BIKE_ABRV,
											'vID' => $bike['bikeID']
										)
									);
			if (($bikePhotos != false) && is_array($bikePhotos) && (count($bikePhotos) > 0)){
				$bikePhotoNew = array();
				foreach($bikePhotos as $key => $kVal){
					$bikePhotoNew[$kVal['vPicID']] = $kVal;
				}
				$this -> adminNS -> bikePhoto = $bikePhotoNew;
			}
			
			/*
			if (($bikePhotos != false) && is_array($bikePhotos) && (count($bikePhotos) > 0)){
				if (is_array($this -> bikeNS -> bikePhoto)){
					$this -> bikeNS -> bikePhoto = array_merge($this -> bikeNS -> bikePhoto, $bikePhotos);
				}else{
					$this -> bikeNS -> bikePhoto = $bikePhotos;
				}
			}
			*/
		}
		$bike['bikePhoto'] = $this -> adminNS -> bikePhoto;		
		$this -> view -> bike = $bike;
		$this -> render('insert3');			
	}
	
	/**
	 * This action controller insert all photos into database and close advertisment. It is finally closed.
	 * The user can't edit the advertisment anymore in the inserting process. But in the user cockipit it is possible to edit the ad.
	 */
	private function insert7Action(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_insVPic.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAd.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_updVPic.php');
		
		if (isset($this -> adminNS -> bikeAds) && is_array($this -> adminNS -> bikeAds)			
			&& isset($this -> adminNS -> bikeAds['bikeID'])){
			$bikeAdsNS = $this -> adminNS -> bikeAds;
			$bikeDetails = db_selBikeAd(array('bikeID' => $bikeAdsNS['bikeID']));
			if ($bikeDetails != false){
				$bikeDetails = $bikeDetails[0];
				
				if (isset($this -> adminNS -> bikePhoto) && is_array($this -> adminNS -> bikePhoto)){								
					foreach ($this -> adminNS -> bikePhoto as $bikePhoto){
						db_updVPic(array('vPicID' => $bikePhoto['vPicID']
										, 'vPicTMP' => '0'
										)
									);
					}
				}
				$this -> resetnsAction();
				$this -> _redirect('/bike/'.$bikeDetails['bikeID']);
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
	 * Manage bike extra 
	 */
	public function bikeextraAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		
		//fetch all request parameters
		$p = $this -> getRequest() -> getParams();
		
		//insert a new bike extra entry
		if (isset($p['bikeExtraNew'])){
			$this -> insertbikevextAction($p);
		}
		
		$bikeExt = db_selBikeExt();
		$vextID = null;
		if (($bikeExt != false) && is_array($bikeExt)){
			$vextID = array();
			foreach ($bikeExt as $key => $ceValue){
				array_push($vextID, $ceValue['vextID']);
			}
		}
		
		$vextra = db_selVExtra(array('notVextID'=>$vextID));		
		
		$this -> view -> vextra = $vextra;
		$this -> view -> bikeExt = $bikeExt;
	}
	
	private function insertbikevextAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeExt.php');

		$lang = $this -> lang;
		//Get vehicle extra details for new extra
		$vextraNew = null;
		$vextraParent = null;
		if (isset($p['vextraNew'])){
			$vextraNew = db_selVExtra(array('vextID'=>$p['vextraNew']));
			$bikeExt = db_selBikeExt(array('vextID'=>$p['vextraNew']));
			if ($bikeExt != false){
				$vextraNew = null;
			} 
		
			//get vehicle extra details for parent extra
			if ($p['vextraParent'] != $p['vextraNew']){
				if (isset($p['vextraParent']) && ($p['vextraParent'] == -1)){
					$vextraParent = db_selBikeExt(array('lft'=>1));
					if ($vextraParent == false){
						db_insBikeExt(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vextID' => 0));						
						$vextraParent = db_selBikeExt(array('lft'=>1));
					}
				}else{
					$vextraParent = db_selBikeExt(array('vextID' => $p['vextraParent']));
				}
			}else{
				$this -> view -> error = $lang['AERR_31'];
			}
		}
		
		if (($vextraNew != null) && ($vextraParent != null)
			&& is_array($vextraNew) && is_array($vextraParent)){
			$vextraParent = $vextraParent[0];
			$vextraNew = $vextraNew[0];
			db_updBikeExt(array(System_Properties::SQL_SET => array('incLft'=>2)
							, System_Properties::SQL_WHERE => array('lftBEq'=>$vextraParent['rgt'])
								)
						);
			db_updBikeExt(array(System_Properties::SQL_SET => array('incRgt'=>2)
							, System_Properties::SQL_WHERE => array('rgtBEq'=>$vextraParent['rgt'])
								)
						);
			db_insBikeExt(array('lft'=>$vextraParent['rgt']
							, 'rgt' => ($vextraParent['rgt'] +1)
							, 'active' => 1
							, 'vextID' => $vextraNew['vextID']
								)
						);
			$this -> view -> info = $lang['AINFO_12'];
		}				
	}

	/**
	 * Insert a new bike extra
	 */
	public function bikeextraeditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		
		$p = $this -> getRequest() -> getParams();
		
		//determine bike vehicle extra id
		$bikeVextID = null;
		if (isset($p['beid'])){
			$bikeVextID = $p['beid'];
		}
		
		if ($bikeVextID != null){
			$bikeVext = db_selBikeExt(array('bikeExtID' => $bikeVextID));
			if (($bikeVext != false) && is_array($bikeVext)){
					$bikeVext = $bikeVext[0];
			
					//Edit bike extra
					if (isset($p['bikeExtEdit'])){
						$p['bikeExtID'] = $bikeVextID;
						$p['bikeExt'] = $bikeVext;
						$this ->editbikeextraAction($p);
						$bikeVextH = db_selBikeExt(array('bikeExtID' => $bikeVextID));
						if (($bikeVextH != false) && is_array($bikeVextH) && (count($bikeVextH) > 0)){
							$bikeVext = $bikeVextH[0];
						}
					}
					
					$this -> view -> bikeVext = $bikeVext;
					if ($bikeVext['level']-1 > 0){
						$bikeVextParent = db_selBikeExt(array('level' => ($bikeVext['level']-1)
															, 'lftLEq' => $bikeVext['lft']
															, 'rgtBEq' => $bikeVext['rgt']
															)
													);
						if (($bikeVextParent != false) && is_array($bikeVextParent) && (count($bikeVextParent) > 0)){													
							$this -> view -> bikeVextParent = $bikeVextParent[0];
						}
					}
					
					$bikeVextAll = db_selBikeExt();
					$this -> view -> bikeVextAll = $bikeVextAll;
					
			}else{
				$this -> _forward('bikeextra');
			}
		}else{
			$this -> _forward('bikeextra');
		}
	}
	
	private function editbikeextraAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeExt.php');
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$vextraParent = null;
		$vextraParentAct = null;
		$bikeExt = null;
		
		
		if (isset($p['bikeExt']) && is_array($p['bikeExt']) && isset($p['bikeExt']['lft']) && isset($p['bikeExt']['rgt'])){
			$bikeExt = $p['bikeExt'];
			//determine children from current bike extra
			$bikeExtChild = db_selBikeExt(array('lftBEq' => $bikeExt['lft']
											, 'rgtLEq' => $bikeExt['rgt']
											)
										);			
			
			//determine new specified vehicle extra parent
			if (isset($p['vextraParent']) && ($p['vextraParent'] != $bikeExt['bikeExtID'])) {
				if ($p['vextraParent'] < 1){
					$vextraParent = db_selBikeExt(array('lft'=>'1'));
				}else{
					$vextraParent = db_selBikeExt(array('bikeExtID'=>$p['vextraParent']));
				}
								
				if (($vextraParent != false) && is_array($vextraParent) && (count($vextraParent) > 0)){
					$vextraParent = $vextraParent[0];
					
					if (($vextraParent['lft'] < $bikeExt['lft']) || ($vextraParent['rgt'] > $bikeExt['rgt'])){
						//Set LFT and RGT to 0 of all bike vext and his children 
						$lft = 0;
						db_updBikeExt(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$lft)
										, System_Properties::SQL_WHERE => array('lftBEq'=>$bikeExt['lft']
																				, 'rgtLEq' => $bikeExt['rgt'])));
						
						//Erase model from actual parent model
						//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($bikeExt['children'] +1) *2;
						db_updBikeExt(array(System_Properties::SQL_SET => array('decLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftB' => $bikeExt['rgt'])
											));			
						db_updBikeExt(array(System_Properties::SQL_SET => array('decRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtB' => $bikeExt['rgt'])
											));	
						
						//select again bike vextra parent					
						if ($vextraParent['lft'] <= 1){
							$vextraParentH = db_selBikeExt(array('lft'=>'1'));
						}else{
							$vextraParentH = db_selBikeExt(array('bikeExtID'=>$vextraParent['bikeExtID']));
						}
						
						if (($vextraParentH != false) && is_array($vextraParentH) && (count($vextraParentH) > 0)){
							$vextraParent = $vextraParentH[0];
						}					
						
						//Give way to new elements
						//inc LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($bikeExt['children'] +1) *2;
						db_updBikeExt(array(System_Properties::SQL_SET => array('incLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftBEq' => $vextraParent['rgt'])
											));			
						db_updBikeExt(array(System_Properties::SQL_SET => array('incRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtBEq' => $vextraParent['rgt'])
											));	
					
						$bikeExtH = array();
						foreach($bikeExtChild as $cec){
							$cec['diff'] = $cec['rgt'] - $cec['lft'];
							$bikeExtH[$cec['lft']] = $cec;
						}
						ksort($bikeExtH);
						$bikeExtChild = $bikeExtH;
						
						$lftStart = $vextraParent['rgt'];
						
						$prevKey = null;
						foreach ($bikeExtChild as $key=>$cec){
							//increment the left start value
							if ($prevKey != null){
								$lftStart += $key - $prevKey;
							}				
							
							$lft = $lftStart;
							$rgt = $lft + $cec['diff'];
							db_updBikeExt(array(System_Properties::SQL_SET => array('lft'=>$lft
																				, 'rgt'=>$rgt)
											, System_Properties::SQL_WHERE => array('bikeExtID'=>$cec['bikeExtID'])
											));				
							$prevKey = $key;										
						}	
					}else{
						$this -> view -> error = $lang['AERR_31'];
					}
				}else{
					$this -> view -> error = $lang['AERR_31'];
				}
			}
		} 
		
	}
	
	/**
	 * Manage the deletion of a bike extra
	 */
	public function bikeextraeraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_cntBike2Ext.php');
		
		$lang = $this -> lang;
		$p = $this -> getRequest() -> getParams();
		
		$bikeExtID = null;
		if (isset($p['beid'])){
			$bikeExtID = $p['beid'];
		}
		
		if ($bikeExtID != null){
				
			//erase bike extra completely
			if (isset($p['bikeExtErase2'])){
				$p['bikeExtID'] = $bikeExtID;
				$this -> erasebikeextraAction($p);
			}
				
			$bikeExt = db_selBikeExt(array('bikeExtID'=>$bikeExtID));
			if (($bikeExt != false) && is_array($bikeExt) && (count($bikeExt) > 0)){
				$bikeExt = $bikeExt[0];
				
				//select all bike advertisment which reference to this bike extra
				$cntBike2Ext = db_cntBike2Ext(array('bikeExtID'=>$bikeExt['bikeExtID']
												//, 'p' => true
												)
										);
										
				if ($cntBike2Ext != false){
					$cntBike2Ext = $cntBike2Ext[0];
				}
				
				$this -> view -> bikeExt = $bikeExt;
				$this -> view -> cntBike2Ext = $cntBike2Ext;
			}else{
				$this -> view -> error = $lang['AERR_32'];
				$this -> _forward('bikeextra');
			}
		}else{			
			$this -> _forward('bikeextra');
		}
	}
	
	private function erasebikeextraAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBike2Ext.php');
		
		$lang = $this -> lang;
		//bikeExt contains  the entry from bike extra
		if (isset($p['bikeExtID'])){
			$bikeExtID = $p['bikeExtID'];
			$bikeExt = db_selBikeExt(array('bikeExtID' => $bikeExtID));
			
			if (($bikeExt != false) && is_array($bikeExt) && (count($bikeExt) > 0)){
				$bikeExt = $bikeExt[0];
				//fetch all bikeExt entries between LFT and RGT value of parent
				if (isset($bikeExt['lft']) && isset($bikeExt['rgt'])){
					$bikeExtChildren = db_selBikeExt(array('lftBEq' => $bikeExt['lft']
														, 'rgtLEq' => $bikeExt['rgt']
														));
														
					if (($bikeExtChildren != false) && is_array($bikeExtChildren)){
						//extract all bikeExtID from selected bikeExt
						$bikeExtID = array();
						foreach ($bikeExtChildren as $key => $bikeExtChild){
							array_push($bikeExtID, $bikeExtChild['bikeExtID']);						
						}
						db_delBikeExt(array('bikeExtID'=>$bikeExtID));						
						db_updBikeExt(array(System_Properties::SQL_SET => array('decLft'=>2)
										, System_Properties::SQL_WHERE=>array('lftBEq'=>$bikeExt['lft']))
										);			
						db_updBikeExt(array(System_Properties::SQL_SET=>array('decRgt'=>2)
										, System_Properties::SQL_WHERE=>array('rgtBEq'=>$bikeExt['lft']))
										);
						
						db_delBike2Ext(array('bikeExtID'=>$bikeExtID));
				$this -> view -> info = $lang['AINFO_16'];
					}
				}
			}
		}
	}
	

	/**
	 * Manage bike cat 
	 */
	public function bikecatAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		
		//fetch all request parameters
		$p = $this -> getRequest() -> getParams();
		
		//insert a new bike cat entry
		if (isset($p['bikeCatNew'])){
			$this -> insertbikecatAction($p);
		}
		
		$bikeCat = db_selBikeCat();
		$vcatID = null;
		if (($bikeCat != false) && is_array($bikeCat)){
			$vcatID = array();
			foreach ($bikeCat as $key => $ceValue){
				array_push($vcatID, $ceValue['vcatID']);
			}
		}
		
		$vcat = db_selVCat(array('notVcatID'=>$vcatID));		
		
		$this -> view -> vcat = $vcat;
		$this -> view -> bikeCat = $bikeCat;
	}
	private function insertbikecatAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeCat.php');
		
		$lang = $this -> lang;
		//Get vehicle extra details for new category
		$vcatNew = null;
		$vcatParent = null;
		if (isset($p['vcatNew'])){
			$vcatNew = db_selVCat(array('vcatID'=>$p['vcatNew']));
			$bikeCat = db_selBikeCat(array('vcatID'=>$p['vcatNew']));
			if ($bikeCat != false){
				$vcatNew = null;
			}  
		
			//get vehicle cat details for parent cat
			if ($p['vcatParent'] != $p['vcatNew']){
				if (isset($p['vcatParent']) && ($p['vcatParent'] == -1)){
					$vcatParent = db_selBikeCat(array('lft'=>1));
					if ($vcatParent == false){
						db_insBikeCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
						$vcatParent = db_selBikeCat(array('lft'=>1));
					}
				}else{
					$vcatParent = db_selBikeCat(array('vcatID' => $p['vcatParent']));
				}
			}else{
				$this -> view -> error = $lang['AERR_34'];
			}
		}
		
		if (($vcatNew != null) && ($vcatParent != null)
			&& is_array($vcatNew) && is_array($vcatParent)){
			$vcatParent = $vcatParent[0];
			$vcatNew = $vcatNew[0];
			db_updBikeCat(array(System_Properties::SQL_SET => array('incLft'=>2)
							, System_Properties::SQL_WHERE => array('lftBEq'=>$vcatParent['rgt'])
								)
						);
			db_updBikeCat(array(System_Properties::SQL_SET => array('incRgt'=>2)
							, System_Properties::SQL_WHERE => array('rgtBEq'=>$vcatParent['rgt'])
								)
						);
			db_insBikeCat(array('lft'=>$vcatParent['rgt']
							, 'rgt' => ($vcatParent['rgt'] +1)
							, 'active' => 1
							, 'vcatID' => $vcatNew['vcatID']
								)
						);
			$this -> view -> info = $lang['AINFO_14'];
		}	else{
			$this -> view -> error = $lang['AERR_35'];
		}				
	}

	/**
	 * Edit a bike category
	 */
	public function bikecateditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		
		$lang = $this -> lang;
		$p = $this -> getRequest() -> getParams();
		
		//determine bike vehicle cat id
		$bikeVcatID = null;
		if (isset($p['bcid'])){
			$bikeVcatID = $p['bcid'];
		}
		
		if ($bikeVcatID != null){
			$bikeVcat = db_selBikeCat(array('bikeCatID' => $bikeVcatID));
			if (($bikeVcat != false) && is_array($bikeVcat)){
					$bikeVcat = $bikeVcat[0];
			
					//Edit bike category
					if (isset($p['bikeCatEdit'])){
						$p['bikeCatID'] = $bikeVcatID;
						$p['bikeCat'] = $bikeVcat;
						$this ->editbikecatAction($p);
						$bikeVcatH = db_selBikeCat(array('bikeCatID' => $bikeVcatID));
						if (($bikeVcatH != false) && is_array($bikeVcatH) && (count($bikeVcatH) > 0)){
							$bikeVcat = $bikeVcatH[0];
							$this -> view -> info = $lang['AINFO_15'];
						}
					}
					
					$this -> view -> bikeVcat = $bikeVcat;
					if ($bikeVcat['level']-1 > 0){
						$bikeVcatParent = db_selBikeCat(array('level' => ($bikeVcat['level']-1)
															, 'lftLEq' => $bikeVcat['lft']
															, 'rgtBEq' => $bikeVcat['rgt']
															)
													);
						if (($bikeVcatParent != false) && is_array($bikeVcatParent) && (count($bikeVcatParent) > 0)){													
							$this -> view -> bikeVcatParent = $bikeVcatParent[0];
						}
					}
					
					$bikeVcatAll = db_selBikeCat();
					$this -> view -> bikeVcatAll = $bikeVcatAll;
					
			}else{
				$this -> view -> error = $lang['AERR_36'];
				$this -> _forward('bikecat');
			}
		}else{
			$this -> view -> error = $lang['AERR_36'];
			$this -> _forward('bikecat');
		}
	}
	
	/**
	 * Manage the deletion of a bike category
	 */
	public function bikecateraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_cntBikeCat.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		$bikeCatID = null;
		if (isset($p['bcid'])){
			$bikeCatID = $p['bcid'];
		}
		
		if ($bikeCatID != null){
				
			//erase bike cat completely
			if (isset($p['bikeCatErase2'])){
				$p['bikeCatID'] = $bikeCatID;
				$this -> erasebikecatAction($p);
			}
				
			$bikeCat = db_selBikeCat(array('bikeCatID'=>$bikeCatID));
			if (($bikeCat != false) && is_array($bikeCat) && (count($bikeCat) > 0)){
				$bikeCat = $bikeCat[0];
				
				//select all bike advertisment which reference to this bike cat
				$cntBikeCat = db_cntBikeCat(array('bikeCat'=>$bikeCat['bikeCatID']
												//, 'p' => true
												)
										);
										
				if ($cntBikeCat != false){
					$cntBikeCat = $cntBikeCat[0];
				}
				
				$this -> view -> bikeCat = $bikeCat;
				$this -> view -> cntBikeCat = $cntBikeCat;
			}else{
				$this -> view -> error = $lang['AERR_36'];
				$this -> _forward('bikecat');
			}
		}else{			
			$this -> view -> error = $lang['AERR_36'];
			$this -> _forward('bikecat');
		}
	}
	
	private function editbikecatAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeCat.php');
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$vcatParent = null;
		$vcatParentAct = null;
		$bikeCat = null;
		$lang = $this -> lang;
		
		if (isset($p['bikeCat']) && is_array($p['bikeCat']) && isset($p['bikeCat']['lft']) && isset($p['bikeCat']['rgt'])){
			$bikeCat = $p['bikeCat'];
			//determine children from current bike cat
			$bikeCatChild = db_selBikeCat(array('lftBEq' => $bikeCat['lft']
											, 'rgtLEq' => $bikeCat['rgt']
											)
										);			
			
			//determine new specified vehicle cat parent
			if (isset($p['vcatParent']) && ($p['vcatParent'] != $bikeCat['bikeCatID'])) {
				if ($p['vcatParent'] < 1){
					$vcatParent = db_selBikeCat(array('lft'=>'1'));
				}else{
					$vcatParent = db_selBikeCat(array('bikeCatID'=>$p['vcatParent']));
				}
								
				if (($vcatParent != false) && is_array($vcatParent) && (count($vcatParent) > 0)){
					$vcatParent = $vcatParent[0];
					
					if( ($vcatParent['lft'] < $bikeCat['lft']) || ($vcatParent['rgt'] > $bikeCat['rgt'])){
						//Set LFT and RGT to 0 of all bike vcat and his children 
						$lft = 0;
						db_updBikeCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$lft)
										, System_Properties::SQL_WHERE => array('lftBEq'=>$bikeCat['lft']
																				, 'rgtLEq' => $bikeCat['rgt'])));
						
						//Erase model from actual parent model
						//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($bikeCat['children'] +1) *2;
						db_updBikeCat(array(System_Properties::SQL_SET => array('decLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftB' => $bikeCat['rgt'])
											));			
						db_updBikeCat(array(System_Properties::SQL_SET => array('decRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtB' => $bikeCat['rgt'])
											));	
						
						//select again bike vcat parent					
						if ($vcatParent['lft'] <= 1){
							$vcatParentH = db_selBikeCat(array('lft'=>'1'));
						}else{
							$vcatParentH = db_selBikeCat(array('bikeCatID'=>$vcatParent['bikeCatID']));
						}
						
						if (($vcatParentH != false) && is_array($vcatParentH) && (count($vcatParentH) > 0)){
							$vcatParent = $vcatParentH[0];
						}					
						
						//Give way to new elements
						//inc LFT and RGT values which are bigger than the the selected node RGT-value 
						$lft = ($bikeCat['children'] +1) *2;
						db_updBikeCat(array(System_Properties::SQL_SET => array('incLft' => $lft)
											, System_Properties::SQL_WHERE => array('lftBEq' => $vcatParent['rgt'])
											));			
						db_updBikeCat(array(System_Properties::SQL_SET => array('incRgt' => $lft)
											, System_Properties::SQL_WHERE => array('rgtBEq' => $vcatParent['rgt'])
											));	
					
						$bikeCatH = array();
						foreach($bikeCatChild as $cec){
							$cec['diff'] = $cec['rgt'] - $cec['lft'];
							$bikeCatH[$cec['lft']] = $cec;
						}
						ksort($bikeCatH);
						$bikeCatChild = $bikeCatH;
						
						$lftStart = $vcatParent['rgt'];
						
						$prevKey = null;
						foreach ($bikeCatChild as $key=>$cec){
							//increment the left start value
							if ($prevKey != null){
								$lftStart += $key - $prevKey;
							}				
							
							$lft = $lftStart;
							$rgt = $lft + $cec['diff'];
							db_updBikeCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																				, 'rgt'=>$rgt)
											, System_Properties::SQL_WHERE => array('bikeCatID'=>$cec['bikeCatID'])
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
	
	private function erasebikecatAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeAds.php');
		$lang = $this -> lang;
		//bikeCat contains  the entry from bike cat
		if (isset($p['bikeCatID'])){
			$bikeCatID = $p['bikeCatID'];
			$bikeCat = db_selBikeCat(array('bikeCatID' => $bikeCatID));
			
			if (($bikeCat != false) && is_array($bikeCat) && (count($bikeCat) > 0)){
				$bikeCat = $bikeCat[0];
				//fetch all bikeCat entries between LFT and RGT value of parent
				if (isset($bikeCat['lft']) && isset($bikeCat['rgt'])){
					$bikeCatChildren = db_selBikeCat(array('lftBEq' => $bikeCat['lft']
														, 'rgtLEq' => $bikeCat['rgt']
														));
														
					if (($bikeCatChildren != false) && is_array($bikeCatChildren)){
						//catct all bikeCatID from selected bikeCat
						$bikeCatID = array();
						foreach ($bikeCatChildren as $key => $bikeCatChild){
							array_push($bikeCatID, $bikeCatChild['bikeCatID']);						
						}
						
						db_delBikeCat(array('bikeCatID'=>$bikeCatID));						
						db_updBikeCat(array(System_Properties::SQL_SET => array('decLft'=>($bikeCat['children']+1)*2)
										, System_Properties::SQL_WHERE=>array('lftBEq'=>$bikeCat['lft']))
										);			
						db_updBikeCat(array(System_Properties::SQL_SET=>array('decRgt'=>($bikeCat['children']+1)*2)
										, System_Properties::SQL_WHERE=>array('rgtBEq'=>$bikeCat['lft']))
										);
						
						db_updBikeAds(array(System_Properties::SQL_SET => array('bikeCat' => -1)
										, System_Properties::SQL_WHERE => array('bikeCat' => $bikeCat['bikeCatID'])));
						//db_delBike2Cat(array('bikeCatID'=>$bikeCatID));
						$this -> view -> info = $lang['AINFO_17'];
					}
				}
			}
		}
	}
	
}
?>