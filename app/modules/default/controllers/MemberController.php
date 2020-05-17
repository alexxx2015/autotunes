<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for PKW Area
 *********************************************************************************/
include_once('classes/AbstractController.php');
include_once('securimage/securimage.php');

class MemberController extends AbstractController{
	
	private $carNS;
	private $actParam;
	
	public function preDispatch(){		
		parent::preDispatch();
		
		$user = null;
		if (isset($this -> userNS -> userData)){
			$user = $this -> userNS -> userData;
		}
		
		$action = $this -> getRequest() -> getActionName();
		if(($action != 'login')
			&& ($action != 'logout')
			&& ($action != 'register')){
				
			if ($this->userNS->userLogged != true){
				$this -> _redirect('/member/login');
			}				
		}
		
		$lang = $this -> lang;

		if (($action == 'index') && ($this -> userNS -> userLogged != true)){
			$this -> _forward('login');
		}
		//check if login is active
		elseif($action == 'login'){
			if(!isset($this -> system['sysLogin']) || ($this -> system['sysLogin'] != 1)){
				$this -> _forward('logout');
			}
			elseif ($this -> userNS -> userLogged == true){
				$this -> _forward('index');
			}				
		}
		//check if register is active
		elseif($action =='register'){		
			if(!isset($this -> system['sysRegister']) || ($this -> system['sysRegister'] != 1)){
				$this -> _forward('index');
			}
		}
		//check if dateximp is active
		elseif($action =='dateximp'){		
			if(!isset($this -> system['sysDataImp']) || ($this -> system['sysDataImp'] != 1)){
				$this -> view -> error = $lang['ERR_38'];
				$this -> _forward('index');
			}
			elseif (($user == null) || !isset($user['datexImp']) || ($user['datexImp'] != 1)){
				$this -> view -> error = $lang['ERR_40'];
				$this -> _forward('index');
			}
		}
		//check if datexexp is active
		elseif($action =='datexexp'){		
			if(!isset($this -> system['sysDataExp']) || ($this -> system['sysDataExp'] != 1)){
				$this -> view -> error = $lang['ERR_39'];
				$this -> _forward('index');
			}
			elseif (($user == null) || !isset($user['datexExp']) || ($user['datexExp'] != 1)){
				$this -> view -> error = $lang['ERR_41'];
				$this -> _forward('index');
			}
		}
		//check if mycarads is active
		elseif ($action == 'mycarads'){
			if(!isset($this -> system['sysCarMarket']) || ($this -> system['sysCarMarket'] != 1)){
				$this -> _forward('index');
			}
		}
		//check if mybikeads is active
		elseif ($action == 'mybikeads'){
			if(!isset($this -> system['sysBikeMarket']) || ($this -> system['sysBikeMarket'] != 1)){
				$this -> _forward('index');
			}
		}
		//check if mytruckads is active
		elseif ($action == 'mytruckads'){
			if(!isset($this -> system['sysTruckMarket']) || ($this -> system['sysTruckMarket'] != 1)){
				$this -> _forward('index');
			}
		}
		$this -> view -> tmpl = $this -> tmpl;//getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;
	}/**
	 * This function load car brands and their corresponding car modelss
	 */
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
												, 'orderby' => array(array('col'=>'carModelName'))
											));
		}
		
		$this -> view -> carBrand = $carBrand;
		$this -> view -> carModel = $carModel;
	}
	/**
	 * This function load bike brands and their corresponding bike modelss
	 */
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
												, 'orderby' => array(array('col'=>'bikeModelName'))
											));
		}
		
		$this -> view -> bikeBrand = $bikeBrand;
		$this -> view -> bikeModel = $bikeModel;
	}/**
	 * This function load truck brands and their corresponding truck modelss
	 */
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
												, 'orderby' => array(array('col'=>'truckModelName'))
											));
		}
		$this -> view -> truckBrand = $truckBrand;
		$this -> view -> truckModel = $truckModel;
	}
	
	private function testAffili(){

		include_once('cronjob/cronj_imp_affili_ah24.php');
	}
	
	public function indexAction(){
		//$this->testAffili();
		include_once('default/models/car/db_selCarAds.php');
		include_once('default/models/bike/db_selBikeAds.php');
		include_once('default/models/truck/db_selTruckAds.php');
		
		include_once('default/models/default/db_selBookmark.php');
		include_once('default/models/default/db_delBookmark.php');
		
		include_once('default/models/default/db_selVPic.php');
		
		$p = $this -> getRequest() -> getParams();
		$userData = $this -> userNS -> userData;
				
		$page = 1;
		if (isset($p['p']) && is_numeric($p['p']) && ($p['p'] > 0)){
			$page = $p['p'];
		}
		
		$vehicleType = System_Properties::CAR_ABRV;
		if (isset($p['v'])){
			$p['v'] = strtolower($p['v']);
			switch ($p['v']){
				case System_Properties::CAR_ABRV:	$vehicleType = System_Properties::CAR_ABRV;
													break;
				case System_Properties::BIKE_ABRV:	$vehicleType = System_Properties::BIKE_ABRV;
													break;
				case System_Properties::TRUCK_ABRV:	$vehicleType = System_Properties::TRUCK_ABRV;
													break; 
			}
		}
		
		//delete a bookmark
		if (isset($p['d']) && isset($p['id']) && isset($userData['userID'])){
			db_delBookmark(array('vehicleType' => $vehicleType
								, 'vehicleID' => $p['id']
								, 'userID' => $userData['userID']
								)
							);
		}
		
		$numCarAds = db_selCarAds(array('userID'=>$userData['userID']));		
		$numBikeAds = db_selBikeAds(array('userID'=>$userData['userID']));	
		$numTruckAds = db_selTruckAds(array('userID'=>$userData['userID']));
		
		if (is_array($numCarAds) && isset($numCarAds['totalRows'])){
			$this -> view -> numCarAds = $numCarAds;
			$this -> view -> numCarAds['totalAds'] = $numCarAds['totalRows'];
		}
		
		if (is_array($numBikeAds) && isset($numBikeAds['totalRows'])){
			$this -> view -> numBikeAds = $numBikeAds;
			$this -> view -> numBikeAds['totalAds'] = $numBikeAds['totalRows'];
		}
		
		if (is_array($numTruckAds) && isset($numTruckAds['totalRows'])){
			$this -> view -> numTruckAds = $numTruckAds;
			$this -> view -> numTruckAds['totalAds'] = $numTruckAds['totalRows'];
		}
		
		$bookmarks = db_selBookmark(array('userID' => $userData['userID']
										, 'vehicleType' => $vehicleType
										)
									);
		$bookmarksCarID = array();
		$bookmarksBikeID = array();
		$bookmarksTruckID = array();
		if (is_array($bookmarks)){
			foreach($bookmarks as $key=>$kVal){
				if ($kVal['vehicleType'] == System_Properties::CAR_ABRV){
					array_push($bookmarksCarID, $kVal['vehicleID']);
				}elseif($kVal['vehicleType'] == System_Properties::BIKE_ABRV){
					array_push($bookmarksBikeID, $kVal['vehicleID']);
				}elseif($kVal['vehicleType'] == System_Properties::TRUCK_ABRV){
					array_push($bookmarksTruckID, $kVal['vehicleID']);
				}
			}
		}
		
		$bookmarksCarAds = db_selCarAds(array('carID'=>$bookmarksCarID));
		if (is_array($bookmarksCarAds) && ($bookmarksCarAds != false)){
			$bookmarksCarAdsP = array('totalAds'=>$bookmarksCarAds['totalRows'], 'carAds' => array());
			
			//For each car ad select the correspondend car picutres
			foreach ($bookmarksCarAds AS $key => $carAd){
				if (is_numeric($key)){
					$carAd['carPics'] = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
															, 'vID' => $carAd['carID']
															, 'vPicTMP' => '0'
														)
													);					
					array_push($bookmarksCarAdsP['carAds'], $carAd);
				}				
			}
			$bookmarksCarAds = $bookmarksCarAdsP;
		}
		
		$bookmarksBikeAds = db_selBikeAds(array('bikeID'=>$bookmarksBikeID));
		if (is_array($bookmarksBikeAds) && ($bookmarksBikeAds != false)){
			$bookmarksBikeAdsP = array('totalAds'=>$bookmarksBikeAds['totalRows'], 'bikeAds' => array());
			
			//For each bike ad select the correspondend bike picutres
			foreach ($bookmarksBikeAds AS $key => $bikeAd){
				if (is_numeric($key)){
					$bikeAd['bikePics'] = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV
															, 'vID' => $bikeAd['bikeID']
															, 'vPicTMP' => '0'
														)
													);					
					array_push($bookmarksBikeAdsP['bikeAds'], $bikeAd);
				}				
			}
			$bookmarksBikeAds = $bookmarksBikeAdsP;
		}
		
		$bookmarksTruckAds = db_selTruckAds(array('truckID'=>$bookmarksTruckID));
		
		$this -> view -> vehicleType = $vehicleType;
		
		$this -> view -> bookmarks = $bookmarks;
		$this -> view -> page = $page;
		
		if (count($bookmarksCarAds) > 0){
			$this -> view -> bookmarksCarAds = $bookmarksCarAds;
		}
		
		if (count($bookmarksBikeAds) > 0){
			$this -> view -> bookmarksBikeAds = $bookmarksBikeAds;
		}
		
		if (count($bookmarksTruckAds) > 0){
			$this -> view -> bookmarksTruckAds = $bookmarksTruckAds;
		}
		
	}
	
	public function datexconfAction(){
		include_once('default/models/member/db_selUser.php');
		include_once('default/models/member/db_updUser.php');
		include_once('default/models/member/db_updFTPUser.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selAuthorityMapping.php');
		
		$user = null;
		if (isset($this -> userNS -> userData)){
			$user = $this -> userNS -> userData;
		}
		
		$lang = $this -> lang;
		
		$p = $this -> getRequest() -> getParams();
		if (isset($p['datexSafe']) && ($user != null)) {
			$p = $this -> filterdatexconfAction($p);
			$ftpLoginEnabled = 'N';
			if (!isset($p['error'])){
				$updUserParam = array('userID' => $user['userID']
									, 'datexImp' => 0
									, 'datexFTPImp' => 0
									, 'datexFTPVType' => '');
				//datexImp
				if (isset($p['datexImp'])){
					$updUserParam['datexImp'] = $p['datexImp'];
					if (($p['datexImp'] != 0) && ($p['datexImp'] != false)){
						
						//datexFTPImp
						if (isset($p['datexFTPImp']) && ($p['datexFTPImp'] != 0) && isset($p['vType'])){
							if( ($p['vType'] == System_Properties::CAR_ABRV)
								|| ($p['vType'] == System_Properties::BIKE_ABRV)
								|| ($p['vType'] == System_Properties::TRUCK_ABRV) ){
								$ftpLoginEnabled = 'Y';
								$updUserParam['datexFTPImp'] = $p['datexFTPImp'];
								$updUserParam['datexFTPVType'] = $p['vType'];
							}
						}
					}
				}				
				
				//datexAutoImp
				if (isset($p['datexAutoImp'])){
					$updUserParam['datexAutoImp'] = $p['datexAutoImp'];
				}
				//datexExp
				if (isset($p['datexExp'])){
					$updUserParam['datexExp'] = $p['datexExp'];
				}			
				
				//datexFormat	
				$dataIntfFile = System_Properties::$DATA_INTF_FILE;
				if (isset($dataIntfFile[$p['datexFormat']])){
					$updUserParam['datexFormat'] = $p['datexFormat'];
				}else{
					$p['datexFormat'] = '-1';
				}
				
				//perform user update
				$userUPD = db_updateUser($updUserParam);
				
				if ($userUPD != false){
					$user = db_selUser(array('userID' => $user['userID']));
					if (($user != false) && is_array($user) && (count($user) > 0)){
						$user = $user[0];
						$userAuth = db_selAuthorityMapping(array('groupID' => $user['groupID']));
						$user['userAuthority'] = $userAuth;
						$this -> userNS -> userData = $user; 
						
						
						db_updFTPUser(array(System_Properties::SQL_SET => array('LOGIN_ENABLED' => $ftpLoginEnabled
																				)
										, System_Properties::SQL_WHERE => array('USERNAME' => $user['userEMail'])
										//, 'print'=>true
											));
					}
				}
				
				$this -> view -> info = $lang['INFO_9'];
			}
			else{
				$this -> view -> error = $p['error'];
			}
		}elseif($user == null){
			$this -> _forward('index');
		}
		
		$this -> view -> user = $user;
	}
	private function filterdatexconfAction($p){
		
		//Check datexImp
		if (isset($p['datexImp'])){
			$p['datexImp'] = 1;
		}else{
			$p['datexImp'] = 0;
		}
		
		//Check datexFTPImp
		if (isset($p['datexFTPImp']) && isset($p['datexImp']) && ($p['datexImp'] == 1)){
			$p['datexFTPImp'] = 1;
		}else{
			$p['datexFTPImp'] = 0;
		}
		
		//Check datexAutoImp
		if (isset($p['datexAutoImp']) && isset($p['datexAutoImp']) && ($p['datexImp'] == 1)){
			$p['datexAutoImp'] = 1;
		}else{
			$p['datexAutoImp'] = 0;
		}
		
		//Check datexExp
		if (isset($p['datexExp'])){
			$p['datexExp'] = 1;
		}else{
			$p['datexExp'] = 0;
		}
		return $p;
	}
	
	public function datexexpAction(){
		include_once('default/models/car/db_selExpCarAds.php');
		include_once('default/models/bike/db_selExpBikeAds.php');
		include_once('default/models/truck/db_selExpTruckAds.php');
		
		include_once('default/models/default/db_selDatex.php');
		include_once('default/models/default/db_insDatex.php');
		include_once('default/models/default/db_insDatexProt.php');
		
		$p = $this -> getRequest() -> getParams();
		
		$user = $this -> userNS -> userData;
				
		$vAds = null;
		$vTypeAbr = null;
		$vType = null;
		$p['fileFormat'] = $user['datexFormat'];
		if (isset($p['datexExp'])){
			if (isset($p['vType']) && isset($p['fileFormat']) 
				&& array_key_exists($p['fileFormat'], System_Properties::$DATA_INTF_FILE)){
				$p['vType'] = strtolower($p['vType']);
				
				switch ($p['vType']){
					case System_Properties::CAR_ABRV: 	$vAds = db_selExpCarAds(array('userID' => $user['userID']));
														$vTypeAbr = System_Properties::CAR_ABRV;	
														$vType = 'car';								
														break;
					case System_Properties::BIKE_ABRV: 	$vAds = db_selExpBikeAds(array('userID' => $user['userID']));
														$vTypeAbr = System_Properties::BIKE_ABRV;
														$vType = 'bike';
														break;
					case System_Properties::TRUCK_ABRV: $vAds = db_selExpTruckAds(array('userID' => $user['userID']));
														$vTypeAbr = System_Properties::TRUCK_ABRV;
														$vType = 'truck';
														break;
				}
				if ($vAds != null){
					$p['V_ADS'] = $vAds;
					$p['fileExpFoto'] = 1;
					//$p['LANG'] = $this -> lang;
					$p = System_Properties::handleDatexExp(array_merge($p, array('LANG' => $this -> lang)));
					
					if(isset($p['ERROR'])){
						$this -> view -> error = $p['ERROR'];
					}else{
						if (!isset($p['fileExpFoto'])){
							$p['fileExpFoto'] = 0;
						}
						
						$datexID = db_insDatex(array('datexType' => System_Properties::DATEX_EXP
													, 'userID' => $user['userID']
													, 'vType' => $vTypeAbr
													, 'datexFormat' => $p['fileFormat']
													, 'datexPic' => $p['fileExpFoto']
													));
						if ($datexID != false){							
						
							$adsExp = array('EXPORTED' => array()
											, 'NOT_EXPORTED' => array());
							if (isset($p['PROT']['EXPORTED']) && is_array($p['PROT']['EXPORTED'])){
								$adsExp['EXPORTED'] = $p['PROT']['EXPORTED'];
							}
							if (isset($p['PROT']['NOT_EXPORTED']) && is_array($p['PROT']['NOT_EXPORTED'])){
								$adsExp['NOT_EXPORTED'] = $p['PROT']['NOT_EXPORTED'];
							}
							
							$vTypeID = $vType.'ID';
							foreach ($vAds as $key => $kVal){							
								if (is_array($kVal) && isset($kVal[$vTypeID])){
									$datexProtVal = '';
									if (in_array($kVal[$vTypeID], $adsExp['EXPORTED'])){
										$datexProtVal = 'EXPORTED';
									}elseif (in_array($kVal[$vTypeID], $adsExp['EXPORTED'])){
										$datexProtVal = 'NOT_EXPORTED';
									}
									db_insDatexProt(array('vID' => $kVal[$vTypeID]
														, 'datexID' => $datexID
														, 'datexProt' => $datexProtVal));
								}
							}
						}
						
						if (isset($p['PROT']['INFO'])){
							$this -> view -> info = $p['PROT']['INFO'];
						}
						if (isset($p['PROT']['ERROR'])){
							$this -> view -> error = $p['PROT']['ERROR'];
						}
					}
				}
			}
		}
		//Download a file
		elseif (isset($p['ce']) || isset($p['be']) || isset($p['te'])){
			isset($p['ce']) ? $fileName = $p['ce'] : (isset($p['be']) ? $fileName = $p['be'] : (isset($p['te']) ? $fileName = $p['te'] : null ));
			$fileNameExp = basename($fileName);
			$fileNameExp = explode('.', $fileNameExp);
			$mimeType = 'text/'.$fileNameExp[count($fileNameExp)-1];
			$filePath = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/download/'.$fileName;
			
			if (file_exists($filePath)){
				$this -> getFrontController() -> setParam('noViewRenderer', true);
				header('Content-Type: '.$mimeType.'; charset=utf-8');
    			header('Content-Disposition: attachment; filename="'.$fileName.'"');
    			
				/*Header("Content-Type: text/comma-separated-values; charset=utf-8");
				Header("Content-Disposition: attachment;filename=\".$fileName.\"");
				Header("Content-Transfer-Encoding: 8bit");
				*/
    			readfile($filePath);
			}
		}	
		
		//fetch last car export
		$carDatex = db_selDatex(array('vType' => System_Properties::CAR_ABRV
									, 'userID' => $user['userID']
									, 'datexType' => System_Properties::DATEX_EXP
									, 'orderby' => array(array('col' => 'timestam'
															, 'desc' => true
															)
													)
									));
		if (($carDatex != false) && is_array($carDatex) && (count($carDatex) > 0)){
			$fileNameCSV = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/download/'.System_Properties::CAR_ABRV.'_'.$user['userID'].'.csv';
			$fileNameZIP = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/download/'.System_Properties::CAR_ABRV.'_'.$user['userID'].'.zip';
			if (file_exists($fileNameCSV) || file_exists($fileNameZIP)){
				
				$this -> view -> carDatex = $carDatex[0];
			}
		}		
				
		//fetch last bike export
		$bikeDatex = db_selDatex(array('vType' => System_Properties::BIKE_ABRV
									, 'userID' => $user['userID']
									, 'datexType' => System_Properties::DATEX_EXP
									, 'orderby' => array(array('col' => 'timestam'
															, 'desc' => true
															)
													)
									));
		if (($bikeDatex != false) && is_array($bikeDatex) && (count($bikeDatex) > 0)){
			$fileNameCSV = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/download/'.System_Properties::BIKE_ABRV.'_'.$user['userID'].'.csv';
			$fileNameZIP = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/download/'.System_Properties::BIKE_ABRV.'_'.$user['userID'].'.zip';
			
			if (file_exists($fileNameCSV) || file_exists($fileNameZIP)){
				$this -> view -> bikeDatex = $bikeDatex[0];
			}
		}		
		
		//fetch last truck export
		$truckDatex = db_selDatex(array('vType' => System_Properties::TRUCK_ABRV
									, 'userID' => $user['userID']
									, 'datexType' => System_Properties::DATEX_EXP
									, 'orderby' => array(array('col' => 'timestam'
															, 'desc' => true
															)
													)
									));
		if (($truckDatex != false) && is_array($truckDatex) && (count($truckDatex) > 0)){
			$fileNameCSV = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/download/'.System_Properties::TRUCK_ABRV.'_'.$user['userID'].'.csv';
			$fileNameZIP = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/download/'.System_Properties::TRUCK_ABRV.'_'.$user['userID'].'.zip';
			if (file_exists($fileNameCSV) || file_exists($fileNameZIP)){
				$this -> view -> truckDatex = $truckDatex[0];
			}
		}
		
		$this -> view -> user = $user;
	}
	
	public function dateximpAction(){
		include_once('Zend/File/Transfer/Adapter/Http.php');
		include_once('Zend/File/Transfer/Exception.php');
		
		$p = $this -> getRequest() -> getParams();
		
		$lang = $this -> lang;
		$user = $this -> userNS -> userData;
		$p['fileFormat'] = $user['datexFormat'];		
		//Datex import is activated
		if (isset($p['datexImp']) && isset($p['fileFormat'])){// && isset($p['vType'])){
// 			 	$p['vType'] = strtolower($p['vType']);
			if ($p['fileFormat'] == '-1'){
				$this -> view -> error = $lang['ERR_49'];
			}
// 			elseif ($p['vType'] == '-1'){
// 				$this -> view -> error = $lang['ERR_50'];
// 			}
// 			elseif (($p['vType'] != System_Properties::CAR_ABRV)
// 				&& ($p['vType'] != System_Properties::BIKE_ABRV)
// 				&& ($p['vType'] != System_Properties::TRUCK_ABRV)){
// 				$this -> view -> error = $lang['ERR_47'];
// 			}
			else{
				$user = $this -> userNS -> userData;
				
				//Check if file types are specified for this file format
				$dataIntfFile = System_Properties::$DATA_INTF_FILE;
				if (isset($dataIntfFile[$p['fileFormat']]['FILE_TYPES'])){
					//get file types
					$fileTypes = $dataIntfFile[$p['fileFormat']]['FILE_TYPES'];
					$fileCtrl = new Zend_File_Transfer_Adapter_Http();
					if ($fileCtrl -> isUploaded() == true){
						//fetch old max filesize of uploaded files
						//$upload_max_filesize = ini_get('upload_max_filesize');
						//ini_set('upload_max_filesize', (System_Properties::MAX_DATA_INTF_FILE_SIZE/1024/1024).'M');
						
						//Add filter
						$fileCtrl -> setOptions(array('useByteString' => false));
						$fileCtrl -> addValidator('FilesSize', false, System_Properties::MAX_DATA_INTF_FILE_SIZE);
						//$fileCtrl -> addValidator('Size', false, array('min' => 1, 'max' => System_Properties::MAX_DATA_INTF_FILE_SIZE));
						$fileCtrl -> addValidator('Extension', false, $fileTypes);
						//$fileCtrl -> addValidator('IsCompressed', true);
	
						$fileDestination = System_Properties::SYS_FTP_PATH.'/'.$user['userID'].'/upload';
						if (!is_dir($fileDestination)){
							mkdir($fileDestination, 0775, true);
							chmod($fileDestination, 0775);
						}
						$fileDestination .= '/'.basename($fileCtrl->getFileName());
						$fileCtrl -> addFilter('Rename', array(	'target' => $fileDestination, 'overwrite' => true));
						if ($fileCtrl -> receive()){
							//Handle data exchange
							$ret = System_Properties::handleDatexImp(array_merge($p, array('FILE_CNTRL' => $fileCtrl
																,'FILE_NAME' => $fileDestination
																, 'LANG' => $this -> lang   
																)));					
							if (isset($ret['PROT']) && is_array($ret['PROT'])){
								$this -> view -> prot = $ret['PROT'];
							}	
							
						}else{
							//echo implode('\n',$fileCtrl->getMessages());
						}
						
						//Now delete all files
						$fileDir = dirname($fileDestination);
						$files = scandir($fileDir);
			 			foreach ( $files as $key => $file) {
							if($file != '.' && $file != '..'){
								$file = $fileDir.'/'.$file;		
								//Lösche alle Dateien
								if (is_file($file)){
									unlink($file);					
								}
								elseif (is_dir($file)){
									System_Properties::rec_rmdir($file);
								}						
							}
						}
						
						//ini_set('upload_max_filesize', $upload_max_filesize);
					}
				}
			}
		}		
		$this -> view -> user = $user; 
	}
	
	public function registerAction(){
		$lang = $this -> lang;
		$req = $this -> getRequest();
		$p = $req -> getParams();
		
		$this -> actParam = $p;
		$this -> view -> user = $p;
		
		//Registration?
		if ($req -> __isset('memReg')){			
			
			if (!isset($p['captcha_code'])){
				$this -> view -> error = $lang['ERR_51'];
			}else{
				$secImg = new Securimage();
				if ($secImg -> check($p['captcha_code']) == false){
					$this -> view -> error = $lang['ERR_51'];
				}else{	
					$p = $this -> userregAction($p);
					if (isset($p['error'])){
						$this -> view -> error = $p['error'];
					}
					if (isset($p['info'])){
						$this -> view -> info = $p['info'];
					}
				}				
			}
			
		}
		$this -> render('register');
	}
	/**
	 * This method check the input parameter and insert a user if all required parameters are fullfilled.
	 */
	private function userregAction($p){
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterEncMD5.php');
		include_once('default/models/member/db_selUser.php');
		include_once('default/models/member/db_insUser.php');
		include_once('default/models/default/db_selSys.php');
		include_once('default/models/member/db_selFTPUser.php');
		include_once('default/models/member/db_insFTPUser.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		
		$system = $this -> system;
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$lang = $this -> lang;
		$stdGroup = null;
		if (isset($this -> system['sysStdGroup'])){
			$stdGroup = $this -> system['sysStdGroup'];
		}
		
		//Check userEmail
		if ( !isset($p['userEMail']) || $fEMail->filter($p['userEMail']) == false){
			$p['error'] = $lang['ERR_14'];
		}
		//check user
		else if(db_selUser(array('userEMail' => $p['userEMail'])) != false){
			$p['error'] = $lang['ERR_14'];
		}
		//Check userNName
		else if ( !isset($p['userNName']) || ($fString100 -> isValid($p['userNName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userVName
		else if ( !isset($p['userVName']) || ($fString100 -> isValid($p['userVName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		/*
		//Check userPW
		else if (!isset($p['userPW1']) || !isset($p['userPW2'])
				|| ($p['userPW1'] == null) || ($p['userPW1'] == '')){
			$p['error'] = $lang['ERR_12'];
		}
		else if ($p['userPW1'] != $p['userPW2']){
			$p['error'] = $lang['ERR_13'];
		}
		*/
		//Check AGB
		else if (!isset($p['userAGB'])){
			$p['error'] = $lang['ERR_15'];
		}
		//Check sysStdGroup
		else if (db_selGroup(array('groupID' => $stdGroup)) == false){
			$p['error'] = $lang['ERR_16'];
		}
		else{
			$fMD5 = new FilterEncMD5();
			
			$pwFound = false;
			while($pwFound == false){					
				$p['userPW1'] = System_Properties::generatePW();
				$md5PW = $fMD5 -> filter($p['userPW1']);
				$checkPW = db_selUser(array('userPW' => $md5PW));
				if (($checkPW == false) || (is_array($checkPW) && (count($checkPW) <= 0))){
					$pwFound = true;
				}
			}
					
			$p['groupID'] = $stdGroup;
			$p['userFirm'] = '';
			$p['userMode'] = -1;
			$p['userPW'] = $fMD5 -> filter($p['userPW1']);
			$p['userAdress'] = '';
			$p['userPLZ'] = '';
			$p['userOrt'] = '';
			$p['userTel1'] = '';
			$p['userTel2'] = '';
			if (isset($p['userAGB'])){
				$p['userAGB'] = 1;
			}else{
				$p['userAGB'] = 0;
			}
			if (isset($p['userNews'])){
				$p['userNews'] = 1;
			}else{
				$p['userNews'] = 0;
			}
			$p['userStat'] = 1;
			$p['userExtID'] = '0';
			$p['userCountry'] = 'DE';
			$p['userURL'] = '';
			
			$userID = db_insUser($p);
			if ($userID != false){
				
				$ftpUser = @db_selFTPUser(array('USERNAME' => $p['userEMail']
											//, 'CUSTOMERID' => '1'
											));
				if (($ftpUser == false) || !is_array($ftpUser)){
					$homeDir = $this -> docRoot.'app/ftp/'.$userID.'/upload/';
					$insFTPUser = db_insFTPUser(array('USERNAME' => $p['userEMail']
													, 'PASSWORD' => $p['userPW1']
													, 'LOGIN_ENABLED' => 'N'
													, 'HOMEDIR' => $homeDir
													, 'UID' => System_Properties::FTP_UID
													, 'GID' => System_Properties::FTP_GID
													));
					if ($insFTPUser != false){
						if (!file_exists($homeDir)){
							if(mkdir($homeDir, 0775, true) == true){
								/*
								echo "KK";
								if(chown($homeDir, System_Properties::FTP_USER_ID)){
									echo "UU";
								}*/
								chgrp($homeDir, System_Properties::FTP_GROUP);
								chmod($homeDir, 0775);
							}
						}
					}
				}
				
				
				
				$p['WEBSITE_NAME'] = $system['sysSiteName'];
				$p['USER_PW'] = $p['userPW1'];
				$p['EMAIL_SENDER'] = System_Properties::$SYS_INFO_EMAIL;
				$p['EMAIL_RECEIVER'] = $p['userEMail'];
				$p['EMAIL_FROM'] = $system['sysSiteName'];
				if($this -> sendRegisterMail($p)){
					$p['info'] = $lang['INFO_3'];
				}else{
					$p['error'] = $lang['ERR_53'];
				}
			}else{
				$p['error'] = $lang['ERR_16'];
			}
		}		
		return $p;
	}

	protected function sendRegisterMail($p){
		$system = $this -> system;
		$lang = $this -> lang;
		$emailMessage = $lang['TXT_241'];
		if (isset($p['EMAIL_MESSAGE'])){
			$emailMessage = $p['EMAIL_MESSAGE'];
		}
		$return = false;
		
		if (!is_array($p)){}
		elseif(!isset($p['WEBSITE_NAME'])){}
		elseif(!isset($p['USER_PW'])){}
		elseif(!isset($p['EMAIL_SENDER'])){}
		elseif(!isset($p['EMAIL_RECEIVER'])){}
		elseif(!isset($p['EMAIL_FROM'])){}
		else{
			if (!isset($p['EMAIL_REPLYTO'])){
				$p['EMAIL_REPLYTO'] = $p['EMAIL_FROM'];
			}
			$emailMessage = str_ireplace('{-WEBSITE_NAME-}', $p['WEBSITE_NAME'], $emailMessage);
			$emailMessage = str_ireplace('{-USER_PW-}', $p['USER_PW'], $emailMessage);
			$return = System_Properties::sendEmail(array('EMAIL_SENDER' => $p['EMAIL_SENDER']
											, 'EMAIL_RECEIVER' => $p['EMAIL_RECEIVER']
											, 'EMAIL_MESSAGE' => $emailMessage 
											, 'EMAIL_SUBJECT' => $lang['TXT_242'].' '.$system['sysSiteName']
											, 'EMAIL_FROM' => $p['EMAIL_FROM']
											, 'EMAIL_REPLYTO' => $p['EMAIL_REPLYTO']
											));
		}
		return $return;
	}
	
	public function loginAction(){
		include_once('default/models/member/db_selUser.php');
		include_once('default/models/member/db_updUser.php');
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterEncMD5.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		$system = $this -> system;
		
		//Login
		if (isset($p['memLog'])){
			$this -> actParam = $p;
			$this -> view -> user = $p;
			
			$p = $this -> userloginAction($p);
			if (is_array($p) && isset($p['error'])){
				$this -> view -> error = $p['error'];
				/*
				$this -> logSystemActivity(array('activityName' => System_Activity::USER_LOGGED
												,'activityRes' => 0
												, 'systemLogData' => $p
												, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
												));
												*/
			}else{
				$this -> userNS -> userData = $p;
				$this -> userNS -> userLogged = true;
				/*
				$this -> logSystemActivity(array('activityName' => System_Activity::USER_LOGGED
												,'activityRes' => 1
												, 'systemLogData' => $p
												, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
												));
				*/
				$this -> _forward('index');
			}
		}
		//Remember new password
		elseif(isset($p['remPW'])){
			$fEMail = new FilterValidEmail();
			$fMD5 = new FilterEncMD5();
			$secImg = new Securimage();
			
			//Check userEmail
			if ( !isset($p['userEMail2']) || $fEMail->filter($p['userEMail2']) == false){
				$p['error'] = $lang['ERR_14'];
			}
			elseif (!isset($p['captcha_code']) || ($secImg -> check($p['captcha_code']) == false)){
				$this -> view -> error = $lang['ERR_51'];
			}	
			else{
				$user = db_selUser(array('userEMail' => $p['userEMail2']));
				if (($user == false) || !is_array($user) || (count($user) <= 0)){
					$p['error'] = $lang['ERR_14'];
				}else{
					$user = $user[0];
					$pwFound = false;
					while($pwFound == false){					
						$genPW = System_Properties::generatePW();
						$md5PW = $fMD5 -> filter($genPW);
						$checkPW = db_selUser(array('userPW' => $md5PW));
						if (($checkPW == false) || (is_array($checkPW) && (count($checkPW) <= 0))){
							if(db_updateUser(array('userPW' => $md5PW
										, 'userID' => $user['userID']
										)) != false){							
								$p['WEBSITE_NAME'] = $system['sysSiteName'];
								$p['USER_PW'] = $genPW;
								$p['EMAIL_SENDER'] = System_Properties::$SYS_INFO_EMAIL;
								$p['EMAIL_RECEIVER'] = $user['userEMail'];
								$p['EMAIL_FROM'] = $system['sysSiteName'];
								$p['EMAIL_MESSAGE'] = $lang['TXT_243'];
								$this -> sendRegisterMail($p);																
							}
							$pwFound = true;
						}
					}
					$this -> view -> info = $lang['INFO_16'];
				}
			}
			
			if (isset($p['error'])){				
				$this -> view -> error = $p['error'];
			}
			if (isset($p['info'])){
				$this -> view -> info = $p['info'];
			}
		}
		
		$this -> view -> p = $p;
		/*
		else{
			$this -> render('login');	
		}
		*/
	}
	
	/**
	 * This method perform a user login
	 */
	private function userloginAction($p){
		include_once('default/models/member/db_selUser.php');
		include_once('default/views/filters/FilterEncMD5.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selAuthorityMapping.php');
		$lang = $this -> lang;
		$fMD5 = new FilterEncMD5();
		if (is_array($p) && isset($p['userPW'])){
			$p['userPW'] = $fMD5 -> filter($p['userPW']);
		}
		
		$user = db_selUser($p);
		if ($user != false){			
			$user = $user[0];
			
			//User must be activated and  not erased
			if (is_array($user) 
				&& isset($user['userStat']) && ($user['userStat'] == 1)
				&& isset($user['erased']) && ($user['erased'] == 0)){
				$userAuth = db_selAuthorityMapping(array('groupID' => $user['groupID']));
				$user['userAuthority'] = $userAuth;
				$p = $user;
			}else{
				$p['error'] = $lang['ERR_17'];
			}
		}else{
			$p['error'] = $lang['ERR_17'];
		}
		return $p;
	}
	
	public function logoutAction(){
		$this -> carNS = new Zend_Session_Namespace(System_Properties::CAR_ADS_NS);
		
		$this -> carNS -> unsetAll();
		$this -> userNS -> unsetAll();
		$this -> _forward('index', 'index');
	}
	
	
	
	public function mycaradsAction(){
		include_once('default/models/car/db_selCarAds.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/car/db_selCarModel.php');
		
		$param = $this -> getRequest() -> getParams();		
		$user = $this -> userNS -> userData;
		$lang = $this -> lang;
		
		$page = 1;
		if (isset($param['page'])){
			$page = $param['page'];
		}
		
		if (isset($param['carSort']) && ($param['carSort'] != -1)){
			switch ($param['carSort']) {
				//0 => Price => carPrice
				case '0': $param['orderby'] = array('col' => 'carPrice');
						break;
				//1 => Leistung => carPower
				case '1': $param['orderby'] = array('col' => 'carPower');
						break;				
				//2 => Laufleistung => carKM
				case '2': $param['orderby'] = array('col' => 'carKM');
						break;			
				//3 => Datum => timestam
				case '3': $param['orderby'] = array('col' => 'timestam');
						break;
			}
			//
			if (isset($param['orderby']) && is_array($param['orderby'])){
				$param['orderby']['desc'] = false;
				//1 => DESC
				if (isset($param['carSortOpt']) && ($param['carSortOpt'] == 1)){
					$param['orderby']['desc'] = true;
				}
				
				$param['orderby'] = array($param['orderby']);
			}
		}
		//carModel
		if (isset($param['carModel']) && isset($param['carBrand'])){
			$carModel = db_selCarModel(array('carModelID' => $param['carModel']));
			if (is_array($carModel) && (count($carModel) > 0)){
				$carModel2 = array();
				foreach($carModel as $key => $kVal){
					array_push($carModel2, $kVal['carBrandID']);
				}
				
				$carBrand2 = array();
				foreach ($param['carBrand'] as $key => $kVal){
					if (!in_array($kVal, $carModel2)){
						array_push($carBrand2, $kVal);
					}	
				}	

				$param['carBrand'] = $carBrand2;
			}
		}		
		
		$p = array();
		$p['userID'] = $user['userID'];
		$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS)
										, 'num' => System_Properties::NUM_ADS);
				
		if(isset($param['search2'])){
			$p = array_merge($param, $p);
		}
		
		$carAds = db_selCarAds($p);
		if (is_array($carAds) && (count($carAds) > 0)){
			
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
					$carAd['carPics'] = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
															, 'vID' => $carAd['carID']
															, 'vPicTMP' => '0'
														)
													);					
					array_push($carAdsP['carAds'], $carAd);
				}				
			}
			$carAds = $carAdsP;
			
			$carAds['numAds'] = System_Properties::NUM_ADS;
			$carAds['actPage'] = $page;
		}else{
			$this -> view -> info = $lang['INFO_8'];
		}
		
		$this -> loadCarModelsBrands($this -> getRequest() -> getParams());
		$this -> view -> searchParam = $this -> getRequest() -> getParams();
		$this -> view -> carAds = $carAds;
	}
	
	public function mybikeadsAction(){
		include_once('default/models/bike/db_selBikeAds.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/bike/db_selBikeModel.php');
		
		$param = $this -> getRequest() -> getParams();		
		$user = $this -> userNS -> userData;
		$lang = $this -> lang;
		
		$page = 1;
		if (isset($param['page'])){
			$page = $param['page'];
		}
	
		if (isset($param['bikeSort']) && ($param['bikeSort'] != -1)){
			switch ($param['bikeSort']) {
				//0 => Price => bikePrice
				case '0': $param['orderby'] = array('col' => 'bikePrice');
						break;
				//1 => Leistung => bikePower
				case '1': $param['orderby'] = array('col' => 'bikePower');
						break;				
				//2 => Laufleistung => bikeKM
				case '2': $param['orderby'] = array('col' => 'bikeKM');
						break;			
				//3 => Datum => timestam
				case '3': $param['orderby'] = array('col' => 'timestam');
						break;
			}
			
			if (isset($param['orderby']) && is_array($param['orderby'])){
				$param['orderby']['desc'] = false;
				//1 => DESC
				if (isset($param['bikeSortOpt']) && ($param['bikeSortOpt'] == 1)){
					$param['orderby']['desc'] = true;
				}
				
				$param['orderby'] = array($param['orderby']);
			}
		}
		//bikeModel
		if (isset($param['bikeModel']) && isset($param['bikeBrand'])){
			$bikeModel = db_selBikeModel(array('bikeModelID' => $param['bikeModel']));
			if (is_array($bikeModel) && (count($bikeModel) > 0)){
				$bikeModel2 = array();
				foreach($bikeModel as $key => $kVal){
					array_push($bikeModel2, $kVal['bikeBrandID']);
				}
				
				$bikeBrand2 = array();
				foreach ($param['bikeBrand'] as $key => $kVal){
					if (!in_array($kVal, $bikeModel2)){
						array_push($bikeBrand2, $kVal);
					}	
				}	

				$param['bikeBrand'] = $bikeBrand2;
			}
		}
		
		$p = array();
		$p['userID'] = $user['userID'];
		$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS)
										, 'num' => System_Properties::NUM_ADS);
		if(isset($param['search2'])){
			$p = array_merge($param, $p);
		}
		
		$bikeAds = db_selBikeAds($p);
		if (is_array($bikeAds) && (count($bikeAds) > 0)){
			
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
					$bikeAd['bikePics'] = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV
															, 'vID' => $bikeAd['bikeID']
															, 'vPicTMP' => '0'
														)
													);					
					array_push($bikeAdsP['bikeAds'], $bikeAd);
				}				
			}
			$bikeAds = $bikeAdsP;
			
			$bikeAds['numAds'] = System_Properties::NUM_ADS;
			$bikeAds['actPage'] = $page;
		}else{
			$this -> view -> info = $lang['INFO_8'];
		}
		
		$this -> loadBikeModelsBrands($this -> getRequest() -> getParams());
		$this -> view -> searchParam = $this -> getRequest() -> getParams();
		$this -> view -> bikeAds = $bikeAds;		
	}
	
	public function mytruckadsAction(){
		include_once('default/models/truck/db_selTruckAds.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/truck/db_selTruckModel.php');
		
		$param = $this -> getRequest() -> getParams();		
		$user = $this -> userNS -> userData;
		$lang = $this -> lang;
		
		$page = 1;
		if (isset($param['page'])){
			$page = $param['page'];
		}
		
		if (isset($param['truckSort']) && ($param['truckSort'] != -1)){
			switch ($param['truckSort']) {
				//0 => Price => truckPrice
				case '0': $param['orderby'] = array('col' => 'truckPrice');
						break;
				//1 => Leistung => truckPower
				case '1': $param['orderby'] = array('col' => 'truckPower');
						break;				
				//2 => Laufleistung => truckKM
				case '2': $param['orderby'] = array('col' => 'truckKM');
						break;			
				//3 => Datum => timestam
				case '3': $param['orderby'] = array('col' => 'timestam');
						break;
			}
			//
			if (isset($param['orderby']) && is_array($param['orderby'])){
				$param['orderby']['desc'] = false;
				//1 => DESC
				if (isset($param['truckSortOpt']) && ($param['truckSortOpt'] == 1)){
					$param['orderby']['desc'] = true;
				}
				
				$param['orderby'] = array($param['orderby']);
			}
		}
	
		//truckModel
		if (isset($param['truckModel']) && isset($param['truckBrand'])){
			$truckModel = db_selTruckModel(array('truckModelID' => $param['truckModel']));
			if (is_array($truckModel) && (count($truckModel) > 0)){
				$truckModel2 = array();
				foreach($truckModel as $key => $kVal){
					array_push($truckModel2, $kVal['truckBrandID']);
				}
				
				$truckBrand2 = array();
				foreach ($param['truckBrand'] as $key => $kVal){
					if (!in_array($kVal, $truckModel2)){
						array_push($truckBrand2, $kVal);
					}	
				}	

				$param['truckBrand'] = $truckBrand2;
			}
		}
		
		$p = array();
		$p['userID'] = $user['userID'];
		$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS)
										, 'num' => System_Properties::NUM_ADS);
		if(isset($param['search2'])){
			$p = array_merge($param, $p);
		}
		
		$truckAds = db_selTruckAds($p);
		if (is_array($truckAds) && (count($truckAds) > 0)){
			
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
					$truckAd['truckPics'] = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV
															, 'vID' => $truckAd['truckID']
															, 'vPicTMP' => '0'
														)
													);					
					array_push($truckAdsP['truckAds'], $truckAd);
				}				
			}
			$truckAds = $truckAdsP;
			
			$truckAds['numAds'] = System_Properties::NUM_ADS;
			$truckAds['actPage'] = $page;
		}else{
			$this -> view -> info = $lang['INFO_8'];
		}
		$this -> loadTruckModelsBrands($this -> getRequest() -> getParams());
		$this -> view -> searchParam = $this -> getRequest() -> getParams();
		$this -> view -> truckAds = $truckAds;
	}
	
	/**
	 * Change personal data
	 */
	public function mychangedataAction(){
		include_once('default/models/member/db_selUser.php');
		include_once('default/models/member/db_updUser.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selAuthorityMapping.php');
		
		$p = $this -> getRequest() -> getParams();
		
		$lang = $this -> lang;
		
		$currentUser = $this -> userNS -> userData;
		
		//change existing user data
		if (isset($p['userChange'])){
			$user = db_selUser(array('userID' => $currentUser['userID']));
			if (($user != false) && is_array($user) && (count($user) > 0)){
				$user = $user[0];
				$p = $this -> filteruserdataAction($p);
				if (!isset($p['error'])){
					$userStatus = 1;
					if ($p['userEMail'] != $user['userEMail']){
						$userStatus = 2;
					}
					$updUser = db_updateUser(array(	'userFirm' => $p['userFirm']
													, 'userNName' => $p['userNName']
													, 'userVName' => $p['userVName']
													//, 'userEMail' => $p['userEMail']
													, 'userTel1' => $p['userTel1']
													, 'userTel2' => $p['userTel2']
													, 'userAdress' => $p['userAdress']
													, 'userPLZ' => $p['userPLZ']
													, 'userOrt' => $p['userOrt']
													, 'userNews' => $p['userNews']
													, 'userStat' => $userStatus
													, 'userID' => $user['userID']
												));
					if ($updUser != false){
						if ($p['userEMail'] != $user['userEMail']){
							$this -> logoutAction();	
						}else{
							$user = db_selUser(array('userID' => $currentUser['userID']));
							if (($user != false) && is_array($user) && (count($user) > 0)){
								$user = $user[0];
								$userAuth = db_selAuthorityMapping(array('groupID' => $user['groupID']));
								$user['userAuthority'] = $userAuth;
								$this -> userNS -> userData = $user;
								$currentUser = $user;
							}
							$this -> view -> info = $lang['INFO_9'];
						}
					}
				}else{
					$this -> view -> error = $p['error'];
				}
			}else{
				$this -> view -> error = $lang['ERR_42'];
			}
			
		}
		
		$this -> view -> user = $currentUser;
	}
	
	private function filteruserdataAction($p){
		$lang = $this -> lang;
		//Process only if cins is pressed
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterString20.php');
		include_once('default/views/filters/FilterEncMD5.php');
		
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();
		$fEndMD5 = new FilterEncMD5();
		
		//Check userNName
		if ( !isset($p['userNName']) || ($fString100 -> isValid($p['userNName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userVName
		else if ( !isset($p['userVName']) || ($fString100 -> isValid($p['userVName']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userEmail
		else if ( !isset($p['userEMail']) || ($fEMail->filter($p['userEMail']) == false)){
			$p['error'] = $lang['ERR_2'];
		}/*
		//Check AGB
		else if ( !isset($p['userAGB'])){
			$p['error'] = $lang['ERR_6'];
		}*/
		//It is not permitted that userNName or userVName or userEMail have the same value as the 
		// System admin login
		else if(($p['userEMail'] == System_Properties::ADMIN_EMAIL)
				|| ($p['userNName'] == System_Properties::ADMIN_EMAIL)
				|| ($p['userVName'] == System_Properties::ADMIN_EMAIL)){
			$p['error'] = $lang['ERR_2'];
		} 
		else{		
			
			//Include and instantiate diverse filter
			include_once('default/views/filters/FilterMySQLTInt.php');
			$fTInt = new FilterMySQLTInt();
			/*
			if(!isset($p['userMode']) || ($p['userMode'] == null)){
				$p['userMode'] = -1;
			}else{
				$p['userMode'] = $fTInt->isValid($p['userMode']);
			}
			
			if (!isset($lang['TXT_33'][$p['userStat']])){
				$p['userStat'] = 1;
				//$p['userStat'] = $fTInt->isValid($p['userStat']);
			}*/
			
			if (isset($p['userNews'])){
				$p['userNews'] = 1;
			}else{
				$p['userNews'] = 0;
			}
			/*
			if (isset($p['userAGB'])){
				$p['userAGB'] = 1;
			}*/
			
			if (isset($p['userPW']) && ($p['userPW'] != null)){
				$p['userPW'] = $fEndMD5 -> filter($p['userPW']);
			}
				
			$p['userFirm'] = $fString100->filter($p['userFirm']);
			$p['userNName'] = $fString100->filter($p['userNName']);
			$p['userVName'] = $fString100->filter($p['userVName']);
			$p['userPLZ'] = $fString20->filter($p['userPLZ']);
			$p['userOrt'] = $fString100->filter($p['userOrt']);
			$p['userTel1'] = $fString100->filter($p['userTel1']);
			$p['userTel2'] = $fString100->filter($p['userTel2']);
			$p['userAdress'] = $fString100->filter($p['userAdress']);
		}
		return $p;
	}
	
	/**
	 * Change password
	 * Enter description here ...
	 */
	public function mychangepwAction(){
		include_once('default/models/member/db_selUser.php');
		include_once('default/models/member/db_updUser.php');
		include_once('default/models/member/db_updFTPUser.php');
		
		$lang = $this -> lang;
		
		$p = $this -> getRequest() -> getParams();
		
		if (isset($p['pwSafe'])){
			if (isset($p['userPWOld']) && isset($p['userPWNew1']) && isset($p['userPWNew2'])){			
				$currentUser = $this -> userNS -> userData;		
				$user = db_selUser(array('userID'=>$currentUser['userID']));
				
				if (($user != false) && is_array($user) && (count($user) > 0)){
					$user = $user[0];
					$oldMD5PW = md5($p['userPWOld']); 
					//Are the new passwords the same
					if ($p['userPWNew1'] != $p['userPWNew2']){
						$this -> view -> error = $lang['ERR_13'];
					}
					//check old password
					elseif ( $oldMD5PW != $user['userPW']){
						$this -> view -> error = $lang['ERR_12'];
					}else{
						$dbUPDUser = db_updateUser(array('userID'=>$user['userID']
														, 'userPW' => md5($p['userPWNew1'])
														));
						if ($dbUPDUser != false){
							db_updFTPUser(array(System_Properties::SQL_SET => array('PASSWORD' => $p['userPWNew1']
																				)
										, System_Properties::SQL_WHERE => array('USERNAME' => $user['userEMail'])
										//, 'print'=>true
											));
						}
						
						$this -> logoutAction();
					}
				}else{
					$this -> view -> error = $lang['ERR_42']; 
				}
			}
		}
	}
}
?>