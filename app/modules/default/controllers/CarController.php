<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for PKW Area
 *********************************************************************************/
include_once('classes/AbstractController.php');
include_once('securimage/securimage.php');
class CarController extends AbstractController{
	
	private $carNS;
	private $actParam;
	
	public function preDispatch(){		
		parent::preDispatch();
		
		//Check if car market is active or not
		if(!isset($this -> system['sysCarMarket']) || ($this -> system['sysCarMarket'] != 1)){
			$this -> _forward('index','index','default');
		}
		
		$action = $this -> getRequest() -> getActionName();
		if((($action == 'insert')
			|| ($action == 'aful')
			|| ($action == 'agfe')
			|| ($action == 'mycardetail'))
			&& ($this->userNS->userLogged != true)){
			$this -> _redirect('/member/login');				
		}
		
		$req = $this -> getRequest();
		$action = $req -> getActionName();
		//Check Authority for "CAR_EDIT" action
		/*if(($action == 'detail') && ($req -> __isset('carSafe'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		else*/if(($action == 'aful')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		elseif(($action == 'agfe')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		//Check Authority for "CAR_CREATE" action
		else if(($action == 'insert') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::CAR_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "CAR_EDIT" action
		else if(($action == 'mycardetail') 
				&& ((System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::CAR_EDIT
														)) != true)
														
					|| (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::CAR_DELETE
														)) != true) )
													){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this -> tmpl;//getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;
		$this -> carNS = new Zend_Session_Namespace(System_Properties::CAR_ADS_NS);
	}
	
	
	/*******************************************
	 * Action controller
	 *******************************************/	
	
	public function indexAction(){
		$this -> loadCarModelsBrands();
		$this -> _forward('search');
		
		/*
		$lang = $this -> view -> tmpl -> getLang();
		$query = '';	
		foreach ($lang['V_EXTRA'] as $key => $val){
			$query .= 'INSERT INTO vext (outsideID, car) VALUES('.$key.',1);<br/>';
			
		}
		echo $query;
		*/
	}
	
	//Bookmark an car advertisement
	public function bookmarkAction(){
		include_once('default/models/default/db_selBookmark.php');
		include_once('default/models/default/db_insBookmark.php');
		
		
		$userID = null;
		if (isset($this->userNS->userLogged) && ($this->userNS->userLogged == true)
			&& isset($this -> userNS -> userData['userID']) && is_numeric($this -> userNS -> userData['userID'])){
			$userID = $this -> userNS -> userData['userID'];
		}
		
		$p = $this -> getRequest() -> getParams();		
		if (isset($p['id'])){
			$carID = $p['id'];
			
			//determine page
			$page = 1;
			if (isset($p['p'])){
				$page = $p['p'];
			}
			
			if($userID != null){
				$bookmark = db_selBookmark(array('vehicleType' => System_Properties::CAR_ABRV
												, 'vehicleID' => $carID
												, 'userID' => $userID
												));
				if (($bookmark == false) || !is_array($bookmark)){
					db_insBoookmark(array('vehicleType' => System_Properties::CAR_ABRV
										, 'vehicleID' => $carID
										, 'userID' => $userID
										));									
				}
			}
			//$this -> _forward('detail', null, null, array('carID'=>$carID));
			$this -> _redirect('/car/'.$carID.'/'.$page);
		}else{
			$this -> _forward('search');
		}
	}
	
	public function detailAction(){
		include_once('default/views/filters/FilterMySQLInt.php');
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/car/db_selCar2Ext.php');
		include_once('default/models/default/db_insEMail.php');
		include_once('default/models/default/db_selEMail.php');
		include_once('default/models/default/db_selBookmark.php');
		
		include_once('default/models/car/db_updCarAds.php');
		
		$intFilter = new FilterMySQLInt(array('unsigned' => true));
		
		$lang = $this -> lang;
		$req = $this -> getRequest();		
		
		$page = $req -> getParam('page');
		if($page == null){
			$page = 1;
		}
		
		$next = $req -> getParam('next');
		$prev = $req -> getParam('prev');
		
		$lastPicPID = $req -> getParam('pid');
		if($lastPicPID == null){
			$lastPicPID = 0;
		}
		
		$carID = $req -> getParam('carID');
		if ($intFilter -> isValid($carID) == false){
			$this -> view -> error = $lang['ERR_5'];
			$this -> search1Action();
		}
		else{
			$bookmark = false;
			$car = db_selCarAd(array('carID' => $carID));
			if (($car != false) && is_array($car)){
				$this -> loadCarCat();
				$car = $car[0];

				db_updCarAds(array(System_Properties::SQL_SET => array('incHitCounter' => '1')
									, System_Properties::SQL_WHERE => array('carID' => $car['carID'])
									));
				
				//Add bookmark functionality
				if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)){
					$db_bookmark = db_selBookmark(array('vehicleType' => System_Properties::CAR_ABRV
														, 'vehicleID' => $car['carID']
														, 'userID' => $this -> userNS -> userData['userID']));
					if (is_array($db_bookmark) && (count($db_bookmark) > 0)){
						$bookmark = false;
					}else{
						$bookmark = true;
					}
				}
				
				//User send a contact email to advertiser
				if ($req -> __isset('sendEMail')){
					$p = $req -> getParams();
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
						$email = db_selEMail(array('vID' => $car['carID']
													, 'vType' => System_Properties::CAR_ABRV
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
															, 'receiverEMailAddress' => $car['userEMail']
															, 'receiverID' => $car['userID']
															, 'vID' => $car['carID']
															, 'vType' => System_Properties::CAR_ABRV
														)
													);
							if ($emailSend != false){
								$pHelp['USER_VNNAME'] = $car['userNName'].' '.$car['userVName'];
								$pHelp['USER_ADS_LINK'] = '<a href="http://www.autotunes.de/car/'.$car['carID'].'">'.(isset($car['carModelName']) ? $car['carBrandName'].' '.$car['carModelName']: $car['carBrandName']).'</a>';
								$pHelp['CONTACT_NAME'] = $pHelp['emailName'];
								$pHelp['MESSAGE'] = $pHelp['emailText'];
								$pHelp['EMAIL_SENDER'] = System_Properties::$SYS_INFO_EMAIL;
								$pHelp['EMAIL_RECEIVER'] = $car['userEMail']; 
								$pHelp['EMAIL_FROM'] = $this -> system['sysSiteName'];//System_Properties::$SYS_INFO_EMAIL;
								$pHelp['EMAIL_REPLYTO'] = $pHelp['emailAddress']; 
								$pHelp['MESSAGE'] = $pHelp['emailText'];
								if ($this -> sendSellerMail($pHelp)){
									$this -> view -> info = $lang['INFO_2'];	
								}else{
									$this -> view -> error = $lang['ERR_9'].' '.$lang['ERR_10'];
								}
							}
							else{
								$this -> view -> error = $lang['ERR_9'];
							}
						}else{
							$this -> view -> error = $lang['ERR_9'].' '.$lang['ERR_10'];
						}
					}
				}				
				
				//Add advertisement recommandation
				$this -> logAdsRecom(array('vType' => System_Properties::CAR_ABRV
										, 'vID2' => $car['carID']));
				
				$car['lastPicPID'] = $lastPicPID;
				$car['page'] = $page;
				$car['next'] = $next;
				$car['prev'] = $prev;
				
				$carExtra = db_selCar2Ext(array('carID' => $car['carID']));
				$car['carExt'] = $carExtra;
				
				$carPic = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
											, 'vID' => $car['carID']
											, 'vPicTMP' => '0'
											)
										);		
				$car['carPics'] = $carPic;
				$this -> view -> car = $car;
				$this -> view -> bookmark = $bookmark;
				$this -> view -> p = $this -> getRequest() -> getParams();
				$this -> view -> system = $this -> system;
			}
			else{
				$this -> view -> error = $lang['ERR_5'];
				$this -> search1Action();
			}
		}
	}
	
	public function mycardetailAction(){
		include_once('default/models/car/db_selCarAd.php');
		
		include_once('default/models/car/db_selCar2Ext.php');
		include_once('default/models/car/db_delCar2Ext.php');
		include_once('default/models/car/db_insCar2Ext.php');
		
		include_once('default/models/car/db_updCarAds.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_updVPic.php');
		
		$lang = $this -> lang;
		
		$p = $this -> getRequest() -> getParams();
		if (isset($p['id'])){
			$carID = $p['id'];
			$carDetails = db_selCarAd(array('carID' => $carID
											, 'userID' => $this -> userNS -> userData['userID']  
										));
			if( ($carDetails != false) && is_array($carDetails) && (count($carDetails) > 0)){				
				$carDetails = $carDetails[0];				
				$this -> loadCarModelsBrands(array('carBrand' => $carDetails['carBrandID']));
				$this -> loadCarExt();
				$this -> loadCarCat();
				
				//Safe change
				if (isset($p['carSafe'])){
					$p = $this -> filterRecvParam($p);
					if (!isset($p['error'])){
						$p['carBrandID'] = $p['carBrand'];
						//update car data
						$updCarAds = db_updCarAds(array(System_Properties::SQL_SET => $p
														, System_Properties::SQL_WHERE => array('carID'=>$carDetails['carID'])
														) 
													);	
						if ($updCarAds != false){
							$carDetails = db_selCarAd(array('carID'=>$carID));
							if( ($carDetails != false) && is_array($carDetails) && (count($carDetails) > 0)){				
								$carDetails = $carDetails[0];
							}
						}
								
						//update car extra
						db_delCar2Ext(array('carID' => $carID));
						if(isset($p['carExtDB']) && is_array($p['carExtDB'])){
							foreach ($p['carExtDB'] as $key=>$kVal){
								db_insCar2Ext(array('carID'=>$carID
													, 'carExtID'=>$kVal['carExtID']
													));
							}
						}			
								
						if (isset($this -> carNS -> carPhoto) && is_array($this -> carNS -> carPhoto) ){
							$vPicID = array();
							foreach ($this -> carNS -> carPhoto as $key => $kVal){
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
						}
						$this -> view -> info = $lang['INFO_6'];								
					/*}else{
						$this -> view -> error = $lang['ERR_37'];
					}*/		
					}else{
						$this -> view -> error = $p['error'];
					}
				}
				//Delete car advertisement 
				elseif (isset($p['carDel'])){
					$carAd = db_updCarAds(array(System_Properties::SQL_WHERE => array('carID' => $carDetails['carID'])
												, System_Properties::SQL_SET => array('erased' => 1)
												)
											);
					if ($carAd != false){
						$this -> view -> info = $lang['INFO_7'];
						$this -> _forward('mycarads', 'member');
					}					
				}
				
				//Load car extra
				$carExt = db_selCar2Ext(array('carID' => $carDetails['carID']));
				if (($carExt != false) && is_array($carExt) && (count($carExt) > 0)){
					$carDetails['carExt'] = array();
					foreach ($carExt as $key => $val){
						array_push($carDetails['carExt'], $val['vextID']);
					}					
				}
				
				$carPhoto = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV,
												'vID' => $carDetails['carID']));
				
				if (($carPhoto != false) && is_array($carPhoto) && (count($carPhoto) > 0)){
					$carDetails['carPhoto'] = array();
					foreach($carPhoto as $key => $kVal){
						$carDetails['carPhoto'][$kVal['vPicID']] = $kVal;
					}
					$carPhoto = $carDetails['carPhoto'];
				}
				
				$this -> view -> car = $carDetails;
				$this -> carNS -> carPhoto = $carPhoto;
			} else{
				$this -> _forward('mycarads','member');
			}
		} else{
			$this -> _forward('mycarads','member');
		}
	}
	
	/**
	 * This action handle the searching process
	 */
	public function searchAction(){
		$req = $this -> getRequest();
		if ($req -> __isset('search2')){
			$this -> carNS -> carSearchParam = $req -> getParams();			
			$this -> search2Action();	
		}
		else if($req -> __isset('page')){
			$this -> search2Action();
		}
		else{
			$this -> search1Action();
		}
	}
	
	private function search1Action(){
		include_once ('default/models/car/db_selCarCat.php');
		
		$this -> loadCarExt();
		$this -> loadCarCat();
		
		$req = $this -> getRequest();
		if ($req -> __isset('cp') && isset($this -> carNS -> carSearchParam) && is_array($this -> carNS -> carSearchParam)){
			$this -> view -> searchParam = $this -> carNS -> carSearchParam;
			$this -> loadCarModelsBrands($this -> carNS -> carSearchParam);
		}
		elseif (isset($this -> view -> searchParam)){
			$this -> loadCarModelsBrands($this -> view -> searchParam);
		}
		else{
			$this -> loadCarModelsBrands();	
		}
		
		$this -> render('search1');
	}
	
	private function search2Action(){
		
		//Search car advertisement in database			
		$req = $this -> getRequest();
		$p = $req -> getParams();
		
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		$carSearchParam = $this -> carNS -> carSearchParam;
		if (is_array($carSearchParam)){
			$p = $carSearchParam;			
			//Set old page
			$p['page'] = $page;
		}
		
		/*
		if (isset($p['jsActive']) && ($p['jsActive'] == 'on')){
			$num = (($page) * System_Properties::NUM_ADS);
			if ($num > 100){
				$num = 100;
			}
			$p['limit'] = array('start' => 0, 'num' => $num);			
		}else{
			*/
			$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
		//}
		//$p['print'] = true;
		$carAds = $this -> searchCarAds($p);
		
		$this -> view -> searchParam = $this -> carNS -> carSearchParam;
			
		//Search process successfully passed and found some matches
		if (is_array($carAds) && (count($carAds) > 0)){
			$this -> loadCarModelsBrands($this -> carNS -> carSearchParam);
						
			$carAds['numAds'] = System_Properties::NUM_ADS;
			$carAds['actPage'] = $page;
			$this -> view -> carAds = $carAds;		
			$this -> render('search2');
		}		
		else{
			$lang = $this -> lang;
			$this -> view -> error = $lang['ERR_4'];
			$this -> search1Action();
		}
	}
	
	private function searchCarAds($p){
		include_once('default/models/car/db_selCarAds.php');
		include_once('default/models/car/db_selCarExt.php');
		include_once('default/models/default/db_selVPic.php');		
		include_once('default/models/default/db_selPLZ.php');
		
		include_once('default/models/car/db_selCarModel.php');
		
		if (isset($p['carExt'])){
			$carExt = db_selCarExt(array('vextID'=>$p['carExt']));
			$p['carExtDB'] = $carExt;
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
				case '3': $p['orderby'] = array('col' => 'timestam');
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
		
		if (isset($p['carPLZ'])){
			$carPLZ = db_selPLZ(array('postal_code' => $p['carPLZ']));
			if (($carPLZ != false) && is_array($carPLZ)){
				$p['carPLZ'] = $carPLZ;				
			}
		}
		
		//carModel
		if (isset($p['carModel']) && isset($p['carBrand'])){
			$carModel = db_selCarModel(array('carModelID' => $p['carModel']
											, 'orderby' => array(array('col' => 'lft'
																	, 'desc' => false
																	)
																)
											));
			if (is_array($carModel) && (count($carModel) > 0)){
				$carBrandWithModel = array();
				$carModelChilds = array();				
				foreach($carModel as $key => $kVal){
					//Check if selected car model has chields
					if(isset($kVal['lft']) && isset($kVal['rgt']) && (($kVal['rgt'] - $kVal['lft']) < 2 )){						
						array_push($carModelChilds, $kVal);
					}
					array_push($carBrandWithModel, $kVal['carBrandID']);
				}
				
				foreach($carModel as $key => $kVal){
					$add = true;
					if(count($carModelChilds) > 0){
						foreach($carModelChilds as $key2 => $kVal2){
							if(($kVal2['lft'] > $kVal['lft']) && ($kVal2['rgt'] < $kVal['rgt'])){
								$add = false;
								break;
							}
						}
					}	
					//add just car model parents which are not specified by car child
					if($add == true){
						//select all childs
						$carModelDB = db_selCarModel(array('lftB' => $kVal['lft']
														, 'rgtL' => $kVal['rgt']
														));
						if(is_array($carModelDB) && ($carModelDB != false) && (count($carModelDB) > 0)){
							foreach($carModelDB as $key2 => $kVal2){					
								array_push($carModelChilds, $kVal2);
							}
						}
						array_push($carModelChilds, $kVal);
					}
				}
				
				if(count($carModelChilds) > 0){
					$carModel = array();
					foreach($carModelChilds as $key => $kVal){
						array_push($carModel, $kVal['carModelID']);
					}
					$p['carModel'] = $carModel;
				}
				
				//Delete all brands without model specification
				$carBrand2 = array();
				foreach ($p['carBrand'] as $key => $kVal){
					if (!in_array($kVal, $carBrandWithModel)){
						array_push($carBrand2, $kVal);
					}	
				}	

				$p['carBrand'] = $carBrand2;
			}
		}

// 		$p['print'] = true;
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
					$carAd['carPics'] = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
															, 'vID' => $carAd['carID']
															, 'vPicTMP' => '0'
														)
													);					
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
	 * This is the main action for invoking the insertion process
	 */
	public function insertAction(){
		$req = $this -> getRequest();
		
		//The user has filled the form and want now to insert the advertisement
		if ($req -> __isset('ins2') && (!isset($this -> carNS -> carInsert) || ($this -> carNS -> carInsert == false))){
			$req -> setParam('ins2',null);
			$this -> insert2Action();
		}
		//User confirm the correctness of the advertisment
		else if ($req -> __isset('ins3') && (!isset($this -> carNS -> carInsert) || ($this -> carNS -> carInsert == false))){
			$this -> insert3Action();
		}
		//Advertisment is commited to database,
		//and the user want to delete some photos from the advertisement
		else if(isset($this -> carNS -> carInsert) 
					&& ($this -> carNS -> carInsert == true) 
					&& isset($this -> carNS -> carAds)
					&& ($req -> __isset('dp'))
					&& ($req -> __isset('i'))){
			$this -> insert6Action();			
		}
		//Advertisment is commited to database,
		//and the user want to add some photos to the advertisement
		else if (isset($this -> carNS -> carInsert) 
					&& ($this -> carNS -> carInsert == true) 
					&& isset($this -> carNS -> carAds)
					&& ($req -> __isset('photoUpload'))){
			$this -> insert5Action();
		}
		//Advertisment is committed to database and the user safe all fotos.
		else if (isset($this -> carNS -> carInsert)
					&& ($this -> carNS -> carInsert == true)
					&& isset($this -> carNS -> carAds)
					&& ($req -> __isset('safeFoto'))){
			$this -> insert7Action();
		}
		//Advertisment is commited to database, so show the inserted advertisement
		else if (isset($this -> carNS -> carInsert) 
					&& ($this -> carNS -> carInsert == true) 
					&& isset($this -> carNS -> carAds)){
			$this -> insert4Action();
		}
		else {
			//Set carInsert to false
			$this -> carNS -> carInsert = false;
			$this -> carNS -> carPhoto = false;
			$this -> insert1Action();
		}
		
	}
	
	/**
	 * This action is invoked for inserting a car advertisment
	 */
	private function insert1Action(){		
		$this -> resetAction();
		$this -> loadCarModelsBrands();
		$this -> loadCarExt();
		$this -> loadCarCat();
		
		if (isset($this -> actParam) && ($this -> actParam != null)){
			$this -> loadCarModelsBrands(array('carBrand' => $this -> actParam['carBrand']));
			$this -> view -> car = $this -> actParam;
		}
		$this -> render('insert1');
	}
	
	/**
	 * This action is invoked for confirmation of the advertisment
	 */
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
			$this -> carNS -> carAds = $p;			
			$this -> view -> car = $p;
			//$this -> view -> carPhoto = $this -> carNS -> carPhoto;
			
			$this -> render('insert2');	
		}
	}
	
	/**
	 * This function do the main job. It check the input parameter and insert the data on DB
	 */
	private function insert3Action(){
		if(isset($this -> carNS -> carAds)){
			include_once ('default/models/car/db_insCarAds.php');			
			include_once ('default/models/car/db_insCar2Ext.php');
			include_once ('default/models/car/db_selCarExt.php');
			
			$p = $this -> carNS -> carAds;
			$this -> actParam = $p;
			$lang = $this -> lang;
			$user = $this -> userNS -> userData;
			
			//Filter the car advertisement
			$p = $this -> filterRecvParam($p);
			if (!isset($p['error'])){
				//Advertising is successful
				$p['userID'] = $user['userID'];
				$carID = db_insCarAds($p);
				if($carID != false){
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
					$this -> carNS -> carAds = $p;
					
					//Set carInsert to true because the car advertisment is successfully inserted.
					$this -> carNS -> carInsert = true;	
						
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
		include_once ('default/models/default/db_selVPic.php');
		$car = $this -> carNS -> carAds;
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
				$this -> carNS -> carPhoto = $carPhotoNew;
			}
			/*
			if (($carPhotos != false) && is_array($carPhotos) && (count($carPhotos) > 0)){
				if (is_array($this -> carNS -> carPhoto)){
					$this -> carNS -> carPhoto = array_merge($this -> carNS -> carPhoto, $carPhotos);
				}else{
					$this -> carNS -> carPhoto = $carPhotos;
				}
			}
			*/
		}
		$car['carPhoto'] = $this -> carNS -> carPhoto;		
		$this -> view -> car = $car;
		$this -> render('insert3');			
	}
	
	/**
	 * Processed if the user upload a photo
	 */
	private function insert5Action(){
		include_once('default/models/car/db_selCarAd.php');	
		$req = $this -> getRequest();
		$userDetails = $this -> userNS -> userData;
		if ($req -> __isset('id') && is_array($userDetails) && isset($userDetails['userID'])){
			$carID = $req -> getParam('id');
			$carDetails = db_selCarAd(array('carID' => $carID));
			if(($carDetails != false) && ($carDetails[0]['userID'] == $userDetails['userID'])){
				$uploadRes = $this -> uploadPhoto(array('carID' => $carDetails[0]['carID']
														, 'carDetail' => $carDetails
														)
												);
												
				if (($uploadRes != false) 
					&& is_array($uploadRes)
					&& isset($uploadRes['r'])
					&& ($uploadRes['r'] == true)){
					
					if (!isset($this -> carNS -> carPhoto) || !is_array($this -> carNS -> carPhoto)){
						$this -> carNS -> carPhoto = array();
					}
					$carAdsNS = array( 'vID' => $carDetails[0]['carID']
										, 'vPicID' => $uploadRes['carPhoto']['hash']
										);
					$this -> carNS -> carPhoto[$uploadRes['carPhoto']['hash']] = $carAdsNS;				
				}
				$this -> insert4Action();
			}else{
				$this -> insert1Action();
			}
		}else{
			$this -> insert1Action();
		}	
	}
	
	/**
	 * Processed if the user erase an uploaded photo
	 */
	private function insert6Action(){		
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/car/db_selCarAd.php');
		
		$req = $this -> getRequest();
		$p = $this -> getRequest() -> getParams();
		$userDetails = $this -> userNS -> userData;
		$carDetails = $this -> carNS -> carAds;
		if (is_array($userDetails) && isset($userDetails['userID'])
			&& is_array($carDetails) && isset($carDetails['carID'])){
			$p['vPicID'] = null;
			if (isset($p['i'])){
				$p['vPicID'] = $p['i'];
			}
			$carDetails = db_selCarAd(array('carID' => $carDetails['carID']));
			if (($carDetails != false) && is_array($carDetails) && (count($carDetails) > 0)){
				$carDetails = $carDetails[0];
				$srcFileName = '.'.System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$carDetails['carID'].'_'.$p['vPicID'].'.jpeg';
				if (file_exists($srcFileName)){
					if (unlink($srcFileName) == true){
						$newCarPhoto = array();
						$carPhoto = $this -> carNS -> carPhoto;
						foreach ($carPhoto as $key=>$val){
							if ($key != $p['vPicID']){
								$newCarPhoto[$key] = $val;
							}
						}
						$this -> carNS -> carPhoto = $newCarPhoto;
					}
				}
			}
			//$this -> deletePhoto($p);
			$this -> insert4Action();
		}else{
			$this -> insert1Action();
		}
	}
	
	private function deletePhoto($p){	
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		$return = array('r' => false);
		
		$vPicID = $p['vPicID'];
		
		$vPic = db_selVPic(array('vPicID'=>$vPicID));
		if ($vPic != null){
			$vPic = $vPic[0];
			
			//Erase picture from database table
			db_delVPic(array('vPicID'=>$vPic['vPicID']));
			
			//Erase picture from file system
			$fileName = '.'.System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$vPic['vID'].'_'.$vPic['vPicID'].'.jpeg';				
			if(file_exists($fileName)){
				//Erase image from hard disk
				unlink($fileName);
				
				$return['r'] = true;
				$return['vPic'] = $vPic;
			}
		}
		return $return;
	}
	
	/**
	 * This action controller insert all photos into database and close advertisment. It is finally closed.
	 * The user can't edit the advertisment anymore in the inserting process. But in the user cockipit it is possible to edit the ad.
	 */
	private function insert7Action(){
		include_once('default/models/default/db_insVPic.php');
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_updVPic.php');
		include_once('default/models/default/db_delVPic.php');
		
		if (isset($this -> carNS -> carAds) && is_array($this -> carNS -> carAds)			
			&& isset($this -> carNS -> carAds['carID'])){
			$carAdsNS = $this -> carNS -> carAds;
			$carDetails = db_selCarAd(array('carID' => $carAdsNS['carID']));
			if ($carDetails != false){
				$carDetails = $carDetails[0];
				
				if (isset($this -> carNS -> carPhoto) && is_array($this -> carNS -> carPhoto)){								
					foreach ($this -> carNS -> carPhoto as $carPhoto){
						db_updVPic(array('vPicID' => $carPhoto['vPicID']
										, 'vPicTMP' => '0'
										));
						/*
						$srcFileURI = '.'.System_Properties::PIC_TMP_PATH.'/'.$carPhoto['vID'].'_'.$carPhoto['vPicID'].'.jpeg';
						if (file_exists($srcFileURI)){
							$vPicID = db_insVPic(array(	'vType' => System_Properties::CAR_ABRV,
											 			'vID' => $carDetails['carID']));
							if ($vPicID != false){
								$destFileURI = '.'.System_Properties::PIC_PATH.'/'.$carDetails['carID'].'_'.$vPicID.'.jpeg';
								if (copy($srcFileURI, $destFileURI) == true){
									unlink($srcFileURI);
								}
							}
						}
						*/	
					}
					$notUpdPic = db_selVPic(array('vID' => $carDetails['carID']
												, 'vPicTMP' => '1'
												));
					if (($notUpdPic != false) && is_array($notUpdPic) && (count($notUpdPic) > 0)){
						$vPicID = array();
						foreach ($notUpdPic as $key => $kVal) {
							array_push($vPicID, $kVal['vPicID']);
						}
						db_delVPic(array('vPicID'=>$vPicID));
					}
					
				}
				$this -> resetAction();
				$this -> _redirect('/car/'.$carDetails['carID']);
			}else{
				$this -> resetAction();
				$this -> indexAction();
			}
		}else{
			$this -> resetAction();
			$this -> indexAction();
		}		
	}
	
	private function resetAction(){	
		//Start a new car advertisement
		//Set carInsert to false
		/*
		$this -> carNS -> carInsert = false;
		$this -> carNS -> carPhoto = false;
		$this -> carNS -> carAds = false;
		*/
		
		
		$this -> carNS -> __unset('carAds');
		$this -> carNS -> __unset('carPhoto');
		$this -> carNS -> __unset('carInsert');
	}
	/**
	 * This function upload a photo.
	 * Parameter returned
	 * @param	r:	specify the result of the upload process.
	 * 				if upload processed unsuccessful then FALSE
	 * @param 	result: if upload is successfull this parameter contains all relevant 
	 * 					information from the uploaded photo
	 */
	private function uploadPhoto_old($p){			
		include_once('Zend/File/Transfer/Adapter/Http.php');	
		include_once('default/models/car/db_selCarAd.php');
		
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
						
				$imgCtrl -> setOptions(array('useByteString'  => false));
				$imgCtrl -> addValidator('FilesSize', false, System_Properties::PIC_FILE_SIZE);
				$imgCtrl -> addValidator('Extension', false, System_Properties::$PIC_EXT);
				
				$imgHash = $imgCtrl -> getHash();//session_id().'_'.$imgCtrl -> getHash();
				$targetURI = System_Properties::PIC_PATH.'/'.$carID.'_'.$imgHash.'.jpeg';				
				$imgCtrl -> addFilter('Rename', array('target' => '.'.$targetURI, 'overwrite' => true));
				
				include_once('default/views/filters/ImageFilter.php');
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
				if ($imgCtrl -> receive()){
					$carPhoto = array(	'hash' => $imgHash
										, 'targetURI' => $targetURI
										);
					$return['r'] = true;
					$return['carPhoto'] = $carPhoto;
				}
			}
		}
		
		return $return;
		
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
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/default/db_insVPic.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		
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
	
	/*******************************************
	 * Auxiliary function for action controller
	 *******************************************/	
	/**
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
												, 'orderby' => array(
													array('col' => 'lft')
													, array('col'=>'carModelName'))
											));
		}
		
		$this -> view -> carBrand = $carBrand;
		$this -> view -> carModel = $carModel;
	}
	
	/**
	 * This function load all car extras.
	 */
	private function loadCarExt(){
		/*
		include_once ('default/models/default/db_selExtra.php');
		$carExt = db_selExtra(array('vType' => 'c'));
		if($carExt != false){
			$this -> view -> carExt = $carExt;
		}
		*/
		include_once ('default/models/car/db_selCarExt.php');
		$carExt = db_selCarExt();
		if($carExt != false){
			$this -> view -> carExt = $carExt;
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
	 * This function filter a car advertisement
	 * @param $p:	this variable contains the parameter of a car advertisement
	 */
	private function filterRecvParam($p){
		$carCatDB = $this -> loadCarCat();
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
		include_once('default/views/filters/FilterIsEmptyString.php');
		
		$fInt = new FilterMySQLInt();
		$fMInt = new FilterMySQLMInt();
		$fMonth = new FilterMonth();
		$fYear = new FilterYear();
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();
		$fIsEmptyStr = new FilterIsEmptyString();
		
		//Check carBrand
		if (!isset($p['carBrand'])){
			$p['error'] = $lang['ERR_2'];
		}elseif (($carBrand = db_selCarBrand(array('carBrandID'=>$p['carBrand']))) == false){
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
		}
		*/	
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
			
			if(!isset($p['carEEK']) || ($p['carEEK'] == null) || !isset($lang['V_EEK'][$p['carEEK']])){
				$p['carEEK'] = -1;
			}
			
			if(!isset($p['carState']) || ($p['carState'] == null)){
				$p['carState'] = -1;
			}else{
				$p['carState'] = $fTInt->filter($p['carState']);
			}
			
			if(!isset($p['carCat']) || ($p['carCat'] == null) || !is_array($carCatDB)){
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
			
			//check carLocPLZ
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
				include_once ('default/models/car/db_selCarExt.php');			
				$carExt = db_selCarExt(array('vextID'=>$p['carExt']));
			}
			$p['carExtDB'] = $carExt;
		}
		return $p;
	}
	
	/**
	 * This action controler show a picture 
	 * @param	pid:	this parameter spcify the id of the picture
	 */
	public function picAction(){
		include_once('default/models/default/db_selVPic.php');
		$pid = $this -> getRequest() -> getParam('pid');
		
		$carPic = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV,
									'vPicID' => $pid));	
		if($carPic != false){
			$this -> view -> carPic = $carPic[0];
		}
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
		
		include_once ('default/models/car/db_selCarBrand.php');
		include_once ('default/models/car/db_selCarModel.php');
		
		$req = $this -> getRequest();
		
		$return = array('r' => false, 'cm' => array());
		
		$r_carBrandID = $req -> getParam('cid');
		
		$carBrand = db_selCarBrand(array('carBrandID' => $r_carBrandID));
		
		if (($carBrand != false) && is_array($carBrand)){
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
					$cm = array('cmn' => $carModel['carModelName']
								, 'cmid' => $carModel['carModelID']
								, 'cml' => $carModel['level']
								);
					array_push($return['cm'], $cm);
				}
			}
		}	
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
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
		include_once('default/models/car/db_selCarAd.php');
		
		//Initialize returning parameter
		$return = array('r' => false);
		
		if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)
			&& isset($this -> userNS -> userData) && is_array($this -> userNS -> userData)){
			$userDetails = $this -> userNS -> userData;							
			
			//get all parameters
			$p = $this -> getRequest() -> getParams();		
			if (isset($p['cid']) && is_numeric($p['cid']) && isset($p['t']) 
				&& is_array($userDetails) && isset($userDetails['userID'])){
				$carID = $p['cid'];
				$carDetails = db_selCarAd(array('carID' => $carID));
				if (($carDetails != false) && ($carDetails[0]['userID'] == $userDetails['userID'])){
					$uploadRes = $this -> uploadPhoto(array('carID' => $carDetails[0]['carID']
															, 'carDetail' => $carDetails
															)
														);
					if (($uploadRes != false) && is_array($uploadRes) 
						&& isset($uploadRes['r']) && ($uploadRes['r'] == true) ){
						if (!isset($this -> carNS -> carPhoto) || !is_array($this -> carNS -> carPhoto)){
							$this -> carNS -> carPhoto = array();
						}
						$this -> carNS -> carPhoto[$uploadRes['carPhoto']['vPicID']] = $uploadRes['carPhoto']; 
						
						$return['r'] = true;		
						$return['h'] = $uploadRes['carPhoto']['vPicID'];
						$return['cid'] = $carID;
						$return['t'] = $p['t'];
						$return['tu'] = $uploadRes['carPhoto']['destURI'];				
					}
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
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_selVPic.php');
		
		$return = array('r' => false);
		
		if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)
			&& isset($this -> userNS -> userData) && is_array($this -> userNS -> userData)){
			$userDetails = $this -> userNS -> userData;	
			
			$vPicID = $this -> getRequest() -> getParam('pid');
			if (is_numeric($vPicID)){
				$vPic = db_selVPic(array('vPicID' => $vPicID));
				if (($vPic != false) && is_array($vPic) && (count($vPic) > 0)){
					$vPic = $vPic[0];
							
					$newCarPhoto = array();
					$carPhoto = $this -> carNS -> carPhoto;
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
					$this -> carNS -> carPhoto = $newCarPhoto;
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
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_selVPic.php');
		
		$return = array('r' => false);
		
		if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)
			&& isset($this -> userNS -> userData) && is_array($this -> userNS -> userData)){
			$userDetails = $this -> userNS -> userData;	
			
			$vPicID = $this -> getRequest() -> getParam('pid');
			if (is_numeric($vPicID)){
				$vPic = db_selVPic(array('vPicID' => $vPicID));
				if (($vPic != false) && is_array($vPic) && (count($vPic) > 0)){
					$vPic = $vPic[0];
					
					$carDetails = db_selCarAd(array('carID' => $vPic['vID']));
					if (($carDetails != false) && is_array($carDetails) 
						&& (count($carDetails) == 1) && ($carDetails[0]['userID'] == $userDetails['userID'])){							
						$carDetails = $carDetails[0];
						
						if ($vPic['vPicTMP'] == 1){
							$db_delVPic = db_delVPic(array(	'vPicID' => $vPic['vPicID']
															)
														);														
							if ($db_delVPic != false){
								$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$carDetails['carID'].'_'.$vPicID.'.jpeg';
								if (file_exists($srcFileURI)){
									unlink($srcFileURI);
								}	
							}
						}
							
						$newCarPhoto = array();
						$carPhoto = $this -> carNS -> carPhoto;
						foreach ($carPhoto as $key=>$val){
							if ($key != $vPicID){
								$newCarPhoto[$key] = $val;
							}
						}
						$this -> carNS -> carPhoto = $newCarPhoto;
						$return['r'] = true;
						
						/*else{
							$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.$carDetails['carID'].'_'.$vPicID.'.jpeg';
							if (file_exists($srcFileURI)){
								unlink($srcFileURI);
							}						
						}*/
						
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
			}
		}
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	
	/**
	 * This functin return the next car search results
	 * Input parameter
	 * @param	rs:		this parameter contains the result set
	 * @param 	ars:	this parameter contains the actual results entries
	 * 
	 * Output param
	 * @param	r:		this parameter specify the execution success of a request
	 * @param 	ca:		In the successful case this parameter contains the car advertisments
	 */
	public function ajagnsrAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);		
		$return = array('r' => false);
		
	
		//Search car advertisement in database			
		$req = $this -> getRequest();
		$p = $req -> getParams();
		$actResSet = $p['ars'];
		$resultSet = $p['rs'];
		if (($resultSet > System_Properties::MAX_RESULT_SET)
			|| ($resultSet <= 0)){
			$resultSet = System_Properties::MAX_RESULT_SET;
		}
		/*
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}*/
		
		$carSearchParam = $this -> carNS -> carSearchParam;
		if (is_array($carSearchParam)){
			$p = $carSearchParam;			
			//Set old page	
			//$p['page'] = $page;
		}
		
		$p['limit'] = array('start' => $actResSet, 'num' => $resultSet);
		$carAds = $this -> searchCarAds($p);
		
		//Search process successfully passed and found some matches
		if (is_array($carAds) && isset($carAds['carAds'])){
			$carAds = $carAds['carAds'];			
			$return['r'] = true;
			$return['ca'] = array();
			foreach ($carAds AS $ca){
				if ($ca['carModelName'] == null){
					$ca['carModelName'] = '';
				}
				array_push($return['ca'], $ca);
			}
		}				
		
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	
	public function ajasearchAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);		
		$return = array('r' => false);
	
		//Search car advertisement in database
		$p = $this -> getRequest() -> getParams();
	
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		$carSearchParam = $this -> translateAjasearchParamAction($p);//$this -> carNS -> carSearchParam;
		if (is_array($carSearchParam)){
			$p = $carSearchParam;
			//Set old page
			$p['page'] = $page;
		}
	
		/*
			if (isset($p['jsActive']) && ($p['jsActive'] == 'on')){
		$num = (($page) * System_Properties::NUM_ADS);
		if ($num > 100){
		$num = 100;
		}
		$p['limit'] = array('start' => 0, 'num' => $num);
		}else{
		*/
		$p['limit'] = array('start' => (($page-1) * System_Properties::NUM_ADS), 'num' => System_Properties::NUM_ADS);
		//}
		$carAds = $this -> searchCarAds($p);
			
		//Search process successfully passed and found some matches
		if (is_array($carAds) && isset($carAds['totalAds']) && ($carAds['totalAds'] > 0) && isset($carAds['carAds'])){
			$return['r'] = true;
			//Search process successfully passed and found some matches
			if (is_array($carAds) && isset($carAds['totalAds']) && ($carAds['totalAds'] > 0)
					&& isset($carAds['carAds']) && is_array($carAds['carAds'])){
				$return['r'] = true;
				$carAds['carAds'] = $this->replaceCarDetailValue($carAds['carAds']);
				$return['ads'] = $carAds;
			}
		}
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
		$this -> getResponse() -> setHeader('Content-type', 'application/json', true);
	}
	
	private function replaceCarDetailValue($p_car){
		include_once ('default/models/car/db_selCarCat.php');
		$lang = $this -> lang;
		$return = array();
		
		foreach($p_car as $key => $car){
			if(isset($lang['TXT_33']) && isset($car['userAds'])
					&& is_array($lang['TXT_33']) && isset($lang['TXT_33'][$car['userAds']])){
				$car['userAds'] = $lang['TXT_33'][$car['userAds']];
			}
			if(isset($lang['TXT_70']) && isset($car['carPriceType'])
					&& is_array($lang['TXT_70']) && isset($lang['TXT_70'][$car['carPriceType']])){
				$car['carPriceType'] = $lang['TXT_70'][$car['carPriceType']];
			}
			if(isset($lang['TXT_74']) && isset($car['carPriceCurr'])
					&& is_array($lang['TXT_74']) && isset($lang['TXT_74'][$car['carPriceCurr']])){
				$car['carPriceCurr'] = $lang['TXT_74'][$car['carPriceCurr']];
			}
			if(isset($lang['TXT_75']) && isset($car['carKMType'])
					&& is_array($lang['TXT_75']) && isset($lang['TXT_75'][$car['carKMType']])){
				$car['carKMType'] = $lang['TXT_75'][$car['carKMType']];
			}
			if(isset($lang['TXT_72']) && isset($car['carPowerType'])
					&& is_array($lang['TXT_72']) && isset($lang['TXT_72'][$car['carPowerType']])){
				$car['carPowerType'] = $lang['TXT_72'][$car['carPowerType']];
			}
			if(isset($lang['V_SHIFT']) && isset($car['carShift'])
					&& is_array($lang['V_SHIFT']) && isset($lang['V_SHIFT'][$car['carShift']])){
				$car['carShift'] = $lang['V_SHIFT'][$car['carShift']];
			}
			if(isset($lang['CAR_DOOR']) && isset($car['carDoor'])
					&& is_array($lang['CAR_DOOR']) && isset($lang['CAR_DOOR'][$car['carDoor']])){
				$car['carDoor'] = $lang['CAR_DOOR'][$car['carDoor']]."/".($lang['CAR_DOOR'][$car['carDoor']]+1);
			}
			if(isset($lang['V_EEK']) && isset($car['carEEK'])
					&& is_array($lang['V_EEK']) && isset($lang['V_EEK'][$car['carEEK']])){
				$car['carEEK'] = $lang['V_EEK'][$car['carEEK']];
			}
			if(isset($lang['V_STATE']) && isset($car['carState'])
					&& is_array($lang['V_STATE']) && isset($lang['V_STATE'][$car['carState']])){
				$car['carState'] = $lang['V_STATE'][$car['carState']];
			}
			if(isset($lang['V_CAT']) && is_array($lang['V_CAT']) && isset($car['carCat'])){
				$carCat = db_selCarCat(array('carCatID' => $car['carCat']));
				if(($carCat != false) && (count($carCat) > 0)){
					$carCat = $carCat[0];
					if(isset($lang['V_CAT'][$carCat['vcatID']])){
						$car['carCat'] = $lang['V_CAT'][$carCat['vcatID']];
					}else{
						$car['carCat'] = null;
					}
				}else{
					$car['carCat'] = null;
				}
			}
			if(isset($lang['V_FUEL']) && isset($car['carFuel'])
					&& is_array($lang['V_FUEL']) && isset($lang['V_FUEL'][$car['carFuel']])){
				$car['carFuel'] = $lang['V_FUEL'][$car['carFuel']];
			}
			if(isset($lang['V_CLR']) && isset($car['carClr'])
					&& is_array($lang['V_CLR']) && isset($lang['V_CLR'][$car['carClr']])){
				$car['carClr'] = $lang['V_CLR'][$car['carClr']];
			}
			if(isset($lang['V_EMISSION_NORM']) && isset($car['carEmissionNorm'])
					&& is_array($lang['V_EMISSION_NORM']) && isset($lang['V_EMISSION_NORM'][$car['carEmissionNorm']])){
				$car['carEmissionNorm'] = $lang['V_EMISSION_NORM'][$car['carEmissionNorm']];
			}
			if(isset($lang['V_ECOLOGIC_TAG']) && isset($car['carEcologicTag'])
					&& is_array($lang['V_ECOLOGIC_TAG']) && isset($lang['V_ECOLOGIC_TAG'][$car['carEcologicTag']])){
				if($car['carEcologicTag'] === '0'){
					$car['carEcologicTag'] = -1;
				}
				else{
					$car['carEcologicTag'] = $lang['V_ECOLOGIC_TAG'][$car['carEcologicTag']];
				}
			}
			if(isset($lang['V_KLIMA']) && isset($car['carKlima'])
					&& is_array($lang['V_KLIMA']) && isset($lang['V_KLIMA'][$car['carKlima']])){
				$car['carKlima'] = $lang['V_KLIMA'][$car['carKlima']];
			}
			
			if(isset($car['carExt']) && is_array($car['carExt'])){
				$carExt = array();
				foreach($car['carExt'] as $key => $kVal){
					if(is_array($kVal) && isset($kVal['vextID'])){
						if(isset($lang['V_EXTRA']) && is_array($lang['V_EXTRA'])
								&& isset($lang['V_EXTRA'][$kVal['vextID']])){
							array_push($carExt, $lang['V_EXTRA'][$kVal['vextID']]);
						}
					}
				}
				$car['carExt'] = $carExt;
			}
			
			if(isset($car['carPics']) && is_array($car['carPics'])){
				$carPics = array();
				foreach($car['carPics'] as $key => $kVal){
					if(isset($kVal['vPicID']) && isset($kVal['vID'])){
						$picFile = '.'.System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$kVal['vID'].'_'.$kVal['vPicID'].'.jpeg';
						if (file_exists($picFile)){
							array_push($carPics, array('vPicID' => $kVal['vPicID'], 'vID' => $kVal['vID']));
						}
					}
				}
				$car['carPics'] = $carPics;
			}
			
			unset($car['ip']);
			unset($car['timestam']);
			unset($car['erased']);
			unset($car['userID']);
			unset($car['hitCounter']);
			unset($car['extLink']);
			unset($car['userLinkAds']);
			
			array_push($return, $car);
		}
		return $return;
	}
	
	public function ajagetdetailAction(){
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/car/db_selCar2Ext.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('Zend/Json.php');
		
		$this -> getFrontController() -> setParam('noViewRenderer', true);		
		$return = array('r' => false);
		$p = $this -> getRequest() -> getParams();
		
		if(isset($p['cid'])){
			$carID = $p['cid'];
			$car = db_selCarAd(array('carID' => $carID));
			if ($car != false){
				$car = $car[0];
		
				$carExtra = db_selCar2Ext(array('carID' => $car['carID']));
				$car['carExt'] = $carExtra;
		
				$carPic = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
						, 'vID' => $car['carID']
						, 'vPicTMP' => '0'));
				$car['carPics'] = $carPic;
				
				$car = $this -> replaceCarDetailValue(array($car));
				if(is_array($car) && isset($car[0])){
					$car = $car[0];
				}
				
				$return['r'] = true;
				$return['v'] = $car;
			}
		}
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
		$this -> getResponse() -> setHeader('Content-type', 'application/json', true);
	}
	
	private function translateAjasearchParamAction($p = array()){
		if(isset($p['b'])){
			$p['carBrand'] = $p['b'];
		}
		if(isset($p['m'])){
			$p['carModel'] = $p['m'];
		}
		if(isset($p['ezyf'])){
			$p['carEZF'] = $p['ezyf'];
		}
		if(isset($p['ezyt'])){
			$p['carEZT'] = $p['ezyt'];
		}
		if(isset($p['pricef'])){
			$p['carPriceF'] = $p['pricef'];
		}
		if(isset($p['pricet'])){
			$p['carPriceT'] = $p['pricet'];
		}
		if(isset($p['powerf'])){
			$p['carPowerF'] = $p['powerf'];
		}
		if(isset($p['powert'])){
			$p['carPowerT'] = $p['powert'];
		}
		if(isset($p['kmf'])){
			$p['carKMF'] = $p['kmf'];
		}
		if(isset($p['kmt'])){
			$p['carKMT'] = $p['kmt'];
		}
		if(isset($p['clima'])){
			$p['carKlima'] = $p['clima'];
		}
		if(isset($p['shift'])){
			$p['carShift'] = $p['shift'];
		}
		if(isset($p['door'])){
			$p['carDoor'] = $p['door'];
		}
		if(isset($p['clr'])){
			$p['carClr'] = array($p['clr']);
		}
		if(isset($p['fuel'])){
			$p['carFuel'] = array($p['fuel']);
		}
		if(isset($p['emission'])){
			$p['carEmissionNorm'] = array($p['emission']);
		}
		if(isset($p['ecotag'])){
			$p['carEcologicTag'] = array($p['ecotag']);
		}
		if(isset($p['state'])){
			$p['carState'] = array($p['state']);
		}
		if(isset($p['cat'])){
			$p['carCat'] = array($p['cat']);
		}
		return $p;
	}
	
	public function viewprintAction(){
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/car/db_selCar2Ext.php');
		include_once('default/models/default/db_selVPic.php');
		
		$req = $this -> getRequest();
		if ($req -> __isset('id')){
			$id = $req -> getParam('id');
			$car = db_selCarAd(array('carID' => $id));
			if ($car != false){
				$this -> loadCarCat();
				$car = $car[0];
				
				$carExtra = db_selCar2Ext(array('carID' => $car['carID']));
				$car['carExt'] = $carExtra;
				
				$carPic = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
											, 'vID' => $car['carID']
											, 'vPicTMP' => '0'));		
				$car['carPics'] = $carPic;
				
				$this -> view -> car = $car;
			}	
		}
	}
}
		/*
		$vPic = db_selVPic(array('vPicID'=>$vPicID));
		if ($vPic != null){
			$vPic = $vPic[0];
			
			//Erase picture from database table
			db_delVPic(array('vPicID'=>$vPic['vPicID']));
			
			//Erase picture from file system
			$fileName = '.'.System_Properties::PIC_PATH.'/'.$vPic['vID'].'_'.$vPic['vPicID'].'.jpeg';				
			if(file_exists($fileName)){
				//$this -> view -> carPhoto = $this -> carNS -> carPhoto;
				$newCarPhoto = array();
				$carPhoto = $this -> carNS -> carPhoto;
				foreach ($carPhoto as $key=>$val){
					if ($key != $vPicID){
						$newCarPhoto[$key] = $val;
					}
				}
				$this -> carNS -> carPhoto = $newCarPhoto;
				
				//Erase image from hard disk
				unlink($fileName);
				
				$return['r'] = true;
			}
		}
		*/
?>