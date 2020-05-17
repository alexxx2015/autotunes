<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for PKW Area
 *********************************************************************************/
require_once('classes/AbstractController.php');
include_once('securimage/securimage.php');
class TruckController extends AbstractController{
	
	private $truckNS;
	private $actParam;
	
	public function preDispatch(){		
		parent::preDispatch();
		
		//Check if truck market is active or not
		if(!isset($this -> system['sysTruckMarket']) || ($this -> system['sysTruckMarket'] != 1)){
			$this -> _forward('index','index','default');
		}
		
		$action = $this -> getRequest() -> getActionName();
		if((($action == 'insert')
			|| ($action == 'aful')
			|| ($action == 'agfe')
			|| ($action == 'mytruckdetail'))
			&& ($this->userNS->userLogged != true)){
			$this -> _redirect('/member/login');				
		}
		
		$req = $this -> getRequest();
		$action = $req -> getActionName();
		//Check Authority for "TRUCK_EDIT" action
		/*if(($action == 'detail') && ($req -> __isset('truckSafe'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		else*/if(($action == 'aful')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		elseif(($action == 'agfe')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		//Check Authority for "TRUCK_CREATE" action
		else if(($action == 'insert') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "TRUCK_EDIT" action
		else if(($action == 'mytruckdetail') 
				&& ((System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_EDIT
														)) != true)
														
					|| (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_DELETE
														)) != true) )
													){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this -> tmpl;//getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;
		$this -> truckNS = new Zend_Session_Namespace(System_Properties::TRUCK_ADS_NS);
	}
	
	
	
	
	/*******************************************
	 * Action controller
	 *******************************************/	
	
	public function indexAction(){
		$this -> loadTruckModelsBrands();
		$this -> _forward('search');
		
	}
	
	//Bookmark an truck advertisement
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
			$truckID = $p['id'];
			
			//determine page
			$page = 1;
			if (isset($p['p'])){
				$page = $p['p'];
			}
			
			if($userID != null){
				$bookmark = db_selBookmark(array('vehicleType' => System_Properties::TRUCK_ABRV
												, 'vehicleID' => $truckID
												, 'userID' => $userID
												));
				if (($bookmark == false) || !is_array($bookmark)){
					db_insBoookmark(array('vehicleType' => System_Properties::TRUCK_ABRV
										, 'vehicleID' => $truckID
										, 'userID' => $userID
										));									
				}
			}
			//$this -> _forward('detail', null, null, array('truckID'=>$truckID));
			$this -> _redirect('/truck/'.$truckID.'/'.$page);
		}else{
			$this -> _forward('search');
		}
	}
	
	public function detailAction(){
		include_once('default/views/filters/FilterMySQLInt.php');
		include_once('default/models/truck/db_selTruckAd.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/truck/db_selTruck2Ext.php');
		include_once('default/models/default/db_insEMail.php');
		include_once('default/models/default/db_selEMail.php');
		include_once('default/models/default/db_selBookmark.php');
		
		include_once('default/models/truck/db_updTruckAds.php');
		
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
		
		$truckID = $req -> getParam('truckID');
		if ($intFilter -> isValid($truckID) == false){
			$this -> view -> error = $lang['ERR_5'];
			$this -> search1Action();
		}
		else{
			$bookmark = false;
			$truck = db_selTruckAd(array('truckID' => $truckID));
			if (($truck != false) && is_array($truck)){
				$this -> loadTruckCat();
				$truck = $truck[0];	
				
				db_updTruckAds(array(System_Properties::SQL_SET => array('incHitCounter' => '1')
									, System_Properties::SQL_WHERE => array('truckID' => $truck['truckID'])
									));
				
				//Add bookmark functionality
				if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)){
					$db_bookmark = db_selBookmark(array('vehicleType' => System_Properties::TRUCK_ABRV
														, 'vehicleID' => $truck['truckID']
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
						$email = db_selEMail(array('vID' => $truck['truckID']
													, 'vType' => System_Properties::TRUCK_ABRV
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
									echo (time() - $email[0]['timestam']);
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
															, 'receiverEMailAddress' => $truck['userEMail']
															, 'receiverID' => $truck['userID']
															, 'vID' => $truck['truckID']
															, 'vType' => System_Properties::TRUCK_ABRV
														)
													);
							if ($emailSend != false){
								$pHelp['USER_VNNAME'] = $truck['userNName'].' '.$truck['userVName'];
								$pHelp['USER_ADS_LINK'] = '<a href="http://www.autotunes.de/truck/'.$truck['truckID'].'">'.(isset($truck['truckModelName']) ? $truck['truckBrandName'].' '.$truck['truckModelName']: $truck['truckBrandName']).'</a>';
								$pHelp['CONTACT_NAME'] = $pHelp['emailName'];
								$pHelp['MESSAGE'] = $pHelp['emailText'];
								$pHelp['EMAIL_SENDER'] = System_Properties::$SYS_INFO_EMAIL;
								$pHelp['EMAIL_RECEIVER'] = $truck['userEMail']; 
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
				$this -> logAdsRecom(array('vType' => System_Properties::TRUCK_ABRV
										, 'vID2' => $truck['truckID']));
				
				$truck['lastPicPID'] = $lastPicPID;
				$truck['page'] = $page;
				$truck['next'] = $next;
				$truck['prev'] = $prev;
				
				$truckExtra = db_selTruck2Ext(array('truckID' => $truck['truckID']));
				$truck['truckExt'] = $truckExtra;
				
				$truckPic = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV
											, 'vID' => $truck['truckID']
											, 'vPicTMP' => '0'
											)
										);		
				$truck['truckPics'] = $truckPic;
				$this -> view -> truck = $truck;
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
	
	public function mytruckdetailAction(){
		include_once('default/models/truck/db_selTruckAd.php');
		
		include_once('default/models/truck/db_selTruck2Ext.php');
		include_once('default/models/truck/db_delTruck2Ext.php');
		include_once('default/models/truck/db_insTruck2Ext.php');
		
		include_once('default/models/truck/db_updTruckAds.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_updVPic.php');
		
		$lang = $this -> lang;
		
		$p = $this -> getRequest() -> getParams();
		if (isset($p['id'])){
			$truckID = $p['id'];
			$truckDetails = db_selTruckAd(array('truckID' => $truckID
											, 'userID' => $this -> userNS -> userData['userID']  
										));
			if( ($truckDetails != false) && is_array($truckDetails) && (count($truckDetails) > 0)){				
				$truckDetails = $truckDetails[0];				
				$this -> loadTruckModelsBrands(array('truckBrand' => $truckDetails['truckBrandID']));
				$this -> loadTruckExt();
				$this -> loadTruckCat();
				
				//Safe change
				if (isset($p['truckSafe'])){
					$p = $this -> filterRecvParam($p);
					if (!isset($p['error'])){
						$p['truckBrandID'] = $p['truckBrand'];
						$updTruckAds = db_updTruckAds(array(System_Properties::SQL_SET => $p
														, System_Properties::SQL_WHERE => array('truckID'=>$truckDetails['truckID'])
														) 
													);	
						if ($updTruckAds != false){
							$truckDetails = db_selTruckAd(array('truckID'=>$truckID));
							if( ($truckDetails != false) && is_array($truckDetails) && (count($truckDetails) > 0)){				
								$truckDetails = $truckDetails[0];
							}
						}
								
						//update truck extra
						db_delTruck2Ext(array('truckID' => $truckID));
						if(isset($p['truckExtDB']) && is_array($p['truckExtDB'])){
							foreach ($p['truckExtDB'] as $key=>$kVal){
								db_insTruck2Ext(array('truckID'=>$truckID
													, 'truckExtID'=>$kVal['truckExtID']
													));
							}
						}
								
						if (isset($this -> truckNS -> truckPhoto) && is_array($this -> truckNS -> truckPhoto) && (count($this -> truckNS -> truckPhoto) > 0)){							
							
							$vPicID = array();
							foreach ($this -> truckNS -> truckPhoto as $key => $kVal){
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
						}
						$this -> view -> info = $lang['INFO_6'];								
					/*}else{
						$this -> view -> error = $lang['ERR_37'];
					}*/				
					}else{
						$this -> view -> error = $p['error'];
					}
				}
				//Delete truck advertisement 
				elseif (isset($p['truckDel'])){
					$truckAd = db_updTruckAds(array(System_Properties::SQL_WHERE => array('truckID' => $truckDetails['truckID'])
												, System_Properties::SQL_SET => array('erased' => 1)
												)
											);
					if ($truckAd != false){
						
						$this -> view -> info = $lang['INFO_7'];
						$this -> _forward('mytruckads', 'member');
					}					
				}
				
				//Load truck extra
				$truckExt = db_selTruck2Ext(array('truckID' => $truckDetails['truckID']));
				if (($truckExt != false) && is_array($truckExt) && (count($truckExt) > 0)){
					$truckDetails['truckExt'] = array();
					foreach ($truckExt as $key => $val){
						array_push($truckDetails['truckExt'], $val['vextID']);
					}					
				}
				
				$truckPhoto = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV,
												'vID' => $truckDetails['truckID']));
				if (($truckPhoto != false) && is_array($truckPhoto) && (count($truckPhoto) > 0)){
					$truckDetails['truckPhoto'] = array();
					foreach($truckPhoto as $key => $kVal){
						$truckDetails['truckPhoto'][$kVal['vPicID']] = $kVal;
					}
					$truckPhoto = $truckDetails['truckPhoto'];
				}
				
				$this -> view -> truck = $truckDetails;
				$this -> truckNS -> truckPhoto = $truckPhoto;
			} else{
				$this -> _forward('mytruckads','member');
			}
		} else{
			$this -> _forward('mytruckads','member');
		}
	}
	
	/**
	 * This action handle the searching process
	 */
	public function searchAction(){
		$req = $this -> getRequest();
		if ($req -> __isset('search2')){
			$this -> truckNS -> truckSearchParam = $req -> getParams();			
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
		include_once ('default/models/truck/db_selTruckCat.php');
		
		$this -> loadTruckExt();
		$this -> loadTruckCat();
		
		$req = $this -> getRequest();
		if ($req -> __isset('cp') && isset($this -> truckNS -> truckSearchParam) && is_array($this -> truckNS -> truckSearchParam)){
			$this -> view -> searchParam = $this -> truckNS -> truckSearchParam;
			$this -> loadTruckModelsBrands($this -> truckNS -> truckSearchParam);
		}
		elseif (isset($this -> view -> searchParam)){
			$this -> loadTruckModelsBrands($this -> view -> searchParam);
		}
		else{
			$this -> loadTruckModelsBrands();
		}
		
		$this -> render('search1');
	}
	
	private function search2Action(){
		
		//Search truck advertisement in database			
		$req = $this -> getRequest();
		$p = $req -> getParams();
		
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		$truckSearchParam = $this -> truckNS -> truckSearchParam;
		if (is_array($truckSearchParam)){
			$p = $truckSearchParam;			
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
		$truckAds = $this -> searchTruckAds($p);
		
		$this -> view -> searchParam = $this -> truckNS -> truckSearchParam;
		
		//Search process successfully passed and found some matches
		if (is_array($truckAds) && (count($truckAds) > 0)){
			$this -> loadTruckModelsBrands($this -> truckNS -> truckSearchParam);
			
			$truckAds['numAds'] = System_Properties::NUM_ADS;
			$truckAds['actPage'] = $page;
			$this -> view -> truckAds = $truckAds;		
			$this -> render('search2');
		}		
		else{
			$lang = $this -> lang;
			$this -> view -> error = $lang['ERR_4'];
			$this -> search1Action();
		}
	}
	
	private function searchTruckAds($p){
		include_once('default/models/truck/db_selTruckAds.php');
		include_once('default/models/truck/db_selTruckExt.php');
		include_once('default/models/default/db_selVPic.php');		
		include_once('default/models/default/db_selPLZ.php');
		
		include_once('default/models/truck/db_selTruckModel.php');
		
		if (isset($p['truckExt'])){
			$truckExt = db_selTruckExt(array('vextID'=>$p['truckExt']));
			$p['truckExtDB'] = $truckExt;
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
				case '3': $p['orderby'] = array('col' => 'timestam');
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
		
		if (isset($p['truckPLZ'])){
			$truckPLZ = db_selPLZ(array('postal_code' => $p['truckPLZ']));
			if (($truckPLZ != false) && is_array($truckPLZ)){
				$p['truckPLZ'] = $truckPLZ;				
			}
		}
		
		//truckModel
		if (is_array($truckModel) && (count($truckModel) > 0)){
			$truckBrandWithModel = array();
			$truckModelChilds = array();				
			foreach($truckModel as $key => $kVal){
				//fetch all childs  
				if(isset($kVal['lft']) && isset($kVal['rgt']) && (($kVal['rgt'] - $kVal['lft']) < 2 )){						
					array_push($truckModelChilds, $kVal);
				}					
				array_push($truckBrandWithModel, $kVal['truckBrandID']);
			}
			
			//delete all parent model
			foreach($truckModel as $key => $kVal){
				$add = true;
				if(count($truckModelChilds) > 0){
					foreach($truckModelChilds as $key2 => $kVal2){
						if(($kVal2['lft'] > $kVal['lft']) && ($kVal2['rgt'] < $kVal['rgt'])){
							$add = false;
							break;
						}
					}
				}						
				if($add == true){
					//select all childs
					$truckModelDB = db_seltruckModel(array('lftBE' => $kVal['lft']
													, 'rgtLE' => $kVal['rgt']
													));
					if(is_array($truckModelDB) && ($truckModelDB != false) && (count($truckModelDB) > 0)){
						foreach($truckModelDB as $key2 => $kVal2){					
							array_push($truckModelChilds, $kVal2);
						}
					}
				}
			}
			
			if(count($truckModelChilds) > 0){
				$truckModel = array();
				foreach($truckModelChilds as $key => $kVal){
					array_push($truckModel, $kVal['truckModelID']);
				}
				$p['truckModel'] = $truckModel;
			}
			
			//Delete all brands without model specification
			$truckBrand2 = array();
			foreach ($p['truckBrand'] as $key => $kVal){
				if (!in_array($kVal, $truckBrandWithModel)){
					array_push($truckBrand2, $kVal);
				}	
			}	

			$p['truckBrand'] = $truckBrand2;
		}
		//$p['print'] = true;
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
					$truckAd['truckPics'] = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV
															, 'vID' => $truckAd['truckID']
															, 'vPicTMP' => '0'
														)
													);					
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
	 * This is the main action for invoking the insertion process
	 */
	public function insertAction(){
		$req = $this -> getRequest();
		
		//The user has filled the form and want now to insert the advertisement
		if ($req -> __isset('ins2') && (!isset($this -> truckNS -> truckInsert) || ($this -> truckNS -> truckInsert == false))){
			$req -> setParam('ins2',null);
			$this -> insert2Action();
		}
		//User confirm the correctness of the advertisment
		else if ($req -> __isset('ins3') && (!isset($this -> truckNS -> truckInsert) || ($this -> truckNS -> truckInsert == false))){
			$this -> insert3Action();
		}
		//Advertisment is commited to database,
		//and the user want to delete some photos from the advertisement
		else if(isset($this -> truckNS -> truckInsert) 
					&& ($this -> truckNS -> truckInsert == true) 
					&& isset($this -> truckNS -> truckAds)
					&& ($req -> __isset('dp'))
					&& ($req -> __isset('i'))){
			$this -> insert6Action();			
		}
		//Advertisment is commited to database,
		//and the user want to add some photos to the advertisement
		else if (isset($this -> truckNS -> truckInsert) 
					&& ($this -> truckNS -> truckInsert == true) 
					&& isset($this -> truckNS -> truckAds)
					&& ($req -> __isset('photoUpload'))){
			$this -> insert5Action();
		}
		//Advertisment is committed to database and the user safe all fotos.
		else if (isset($this -> truckNS -> truckInsert)
					&& ($this -> truckNS -> truckInsert == true)
					&& isset($this -> truckNS -> truckAds)
					&& ($req -> __isset('safeFoto'))){
			$this -> insert7Action();
		}
		//Advertisment is commited to database, so show the inserted advertisement
		else if (isset($this -> truckNS -> truckInsert) 
					&& ($this -> truckNS -> truckInsert == true) 
					&& isset($this -> truckNS -> truckAds)){
			$this -> insert4Action();
		}
		else {
			//Set truckInsert to false
			$this -> truckNS -> truckInsert = false;
			$this -> truckNS -> truckPhoto = false;
			$this -> insert1Action();
		}
		
	}
	
	/**
	 * This action is invoked for inserting a truck advertisment
	 */
	private function insert1Action(){		
		$this -> resetAction();
		$this -> loadTruckModelsBrands();
		$this -> loadTruckExt();
		$this -> loadTruckCat();
		if (isset($this -> actParam) && ($this -> actParam != null)){
			$this -> loadTruckModelsBrands(array('truckBrand' => $this -> actParam['truckBrand']));
			$this -> view -> truck = $this -> actParam;
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
			//$this -> loadTruckModelsBrands();
			$this -> view -> error = $p['error'];
			//$this -> render('insert1');
			//$this -> _forward('insert', null, null, array('error' => $error));
			$this -> insert1Action();
		}		
		else{			
			$this -> loadTruckCat();
			//Set session namespace
			$this -> truckNS -> truckAds = $p;			
			$this -> view -> truck = $p;
			//$this -> view -> truckPhoto = $this -> truckNS -> truckPhoto;
			
			$this -> render('insert2');	
		}
	}
	
	/**
	 * This function do the main job. It check the input parameter and insert the data on DB
	 */
	private function insert3Action(){
		if(isset($this -> truckNS -> truckAds)){
			include_once ('default/models/truck/db_insTruckAds.php');			
			include_once ('default/models/truck/db_insTruck2Ext.php');
			include_once ('default/models/truck/db_selTruckExt.php');
			
			$p = $this -> truckNS -> truckAds;
			$this -> actParam = $p;
			$lang = $this -> lang;
			$user = $this -> userNS -> userData;
			
			//Filter the truck advertisement
			$p = $this -> filterRecvParam($p);
			
			if (!isset($p['error'])){
				
				//Advertising is successful
				$p['userID'] = $user['userID'];
				$truckID = db_insTruckAds($p);
				if($truckID != false){
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
					$this -> truckNS -> truckAds = $p;
					
					//Set truckInsert to true because the truck advertisment is successfully inserted.
					$this -> truckNS -> truckInsert = true;	
						
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
		include_once ('default/models/default/db_selVPic.php');
		$truck = $this -> truckNS -> truckAds;
		$this -> actParam = $truck;		
		$this -> loadTruckCat();
					
		if (isset($truck['truckID'])){
			
			$truckPhotos = db_selVPic(array(	'vType'=>System_Properties::TRUCK_ABRV,
											'vID' => $truck['truckID']
										)
									);
			if (($truckPhotos != false) && is_array($truckPhotos) && (count($truckPhotos) > 0)){
				$this -> truckNS -> truckPhoto = $truckPhotos;
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
		$truck['truckPhoto'] = $this -> truckNS -> truckPhoto;		
		$this -> view -> truck = $truck;
		$this -> render('insert3');			
	}
	
	/**
	 * Processed if the user upload a photo
	 */
	private function insert5Action(){
		include_once('default/models/truck/db_selTruckAd.php');	
		$req = $this -> getRequest();
		$userDetails = $this -> userNS -> userData;
		if ($req -> __isset('id') && is_array($userDetails) && isset($userDetails['userID'])){
			$truckID = $req -> getParam('id');
			$truckDetails = db_selTruckAd(array('truckID' => $truckID));
			if(($truckDetails != false) && ($truckDetails[0]['userID'] == $userDetails['userID'])){
				$uploadRes = $this -> uploadPhoto(array('truckID' => $truckDetails[0]['truckID']
														, 'truckDetail' => $truckDetails
														)
												);
												
				if (($uploadRes != false) 
					&& is_array($uploadRes)
					&& isset($uploadRes['r'])
					&& ($uploadRes['r'] == true)){
					
					if (!isset($this -> truckNS -> truckPhoto) || !is_array($this -> truckNS -> truckPhoto)){
						$this -> truckNS -> truckPhoto = array();
					}
					$truckAdsNS = array( 'vID' => $truckDetails[0]['truckID']
										, 'vPicID' => $uploadRes['truckPhoto']['hash']
										);
					$this -> truckNS -> truckPhoto[$uploadRes['truckPhoto']['hash']] = $truckAdsNS;				
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
		include_once('default/models/truck/db_selTruckAd.php');
		
		$req = $this -> getRequest();
		$p = $this -> getRequest() -> getParams();
		$userDetails = $this -> userNS -> userData;
		$truckDetails = $this -> truckNS -> truckAds;
		if (is_array($userDetails) && isset($userDetails['userID'])
			&& is_array($truckDetails) && isset($truckDetails['truckID'])){
			$p['vPicID'] = null;
			if (isset($p['i'])){
				$p['vPicID'] = $p['i'];
			}
			$truckDetails = db_selTruckAd(array('truckID' => $truckDetails['truckID']));
			if (($truckDetails != false) && is_array($truckDetails) && (count($truckDetails) > 0)){
				$truckDetails = $truckDetails[0];
				$srcFileName = '.'.System_Properties::PIC_PATH.'/'.System_Properties::TRUCK_ABRV.'_'.$truckDetails['truckID'].'_'.$p['vPicID'].'.jpeg';
				if (file_exists($srcFileName)){
					if (unlink($srcFileName) == true){
						$newTruckPhoto = array();
						$truckPhoto = $this -> truckNS -> truckPhoto;
						foreach ($truckPhoto as $key=>$val){
							if ($key != $p['vPicID']){
								$newTruckPhoto[$key] = $val;
							}
						}
						$this -> truckNS -> truckPhoto = $newTruckPhoto;
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
			$fileName = '.'.System_Properties::PIC_PATH.'/'.System_Properties::TRUCK_ABRV.'_'.$vPic['vID'].'_'.$vPic['vPicID'].'.jpeg';				
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
		include_once('default/models/truck/db_selTruckAd.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_updVPic.php');
		include_once('default/models/default/db_delVPic.php');
		
		if (isset($this -> truckNS -> truckAds) && is_array($this -> truckNS -> truckAds)			
			&& isset($this -> truckNS -> truckAds['truckID'])){
			$truckAdsNS = $this -> truckNS -> truckAds;
			$truckDetails = db_selTruckAd(array('truckID' => $truckAdsNS['truckID']));
			if ($truckDetails != false){
				$truckDetails = $truckDetails[0];
				
				if (isset($this -> truckNS -> truckPhoto) && is_array($this -> truckNS -> truckPhoto)){								
					foreach ($this -> truckNS -> truckPhoto as $truckPhoto){
						db_updVPic(array('vPicID' => $truckPhoto['vPicID']
										, 'vPicTMP' => '0'
										));
						/*
						$srcFileURI = '.'.System_Properties::PIC_TMP_PATH.'/'.$truckPhoto['vID'].'_'.$truckPhoto['vPicID'].'.jpeg';
						if (file_exists($srcFileURI)){
							$vPicID = db_insVPic(array(	'vType' => System_Properties::TRUCK_ABRV,
											 			'vID' => $truckDetails['truckID']));
							if ($vPicID != false){
								$destFileURI = '.'.System_Properties::PIC_PATH.'/'.$truckDetails['truckID'].'_'.$vPicID.'.jpeg';
								if (copy($srcFileURI, $destFileURI) == true){
									unlink($srcFileURI);
								}
							}
						}
						*/	
					}
					$notUpdPic = db_selVPic(array('vID' => $truckDetails['truckID']
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
				$this -> _redirect('/truck/'.$truckDetails['truckID']);
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
		//Start a new truck advertisement
		//Set truckInsert to false
		/*
		$this -> truckNS -> truckInsert = false;
		$this -> truckNS -> truckPhoto = false;
		$this -> truckNS -> truckAds = false;
		*/
		
		
		$this -> truckNS -> __unset('truckAds');
		$this -> truckNS -> __unset('truckPhoto');
		$this -> truckNS -> __unset('truckInsert');
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
		include_once('default/models/truck/db_selTruckAd.php');
		
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
						
				$imgCtrl -> setOptions(array('useByteString'  => false));
				$imgCtrl -> addValidator('FilesSize', false, System_Properties::PIC_FILE_SIZE);
				$imgCtrl -> addValidator('Extension', false, System_Properties::$PIC_EXT);
				
				$imgHash = $imgCtrl -> getHash();//session_id().'_'.$imgCtrl -> getHash();
				$targetURI = System_Properties::PIC_PATH.'/'.$truckID.'_'.$imgHash.'.jpeg';				
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
				//if (!isset($this -> truckNS -> truckPhoto[$imgHash]) && $imgCtrl -> receive()){	
				if ($imgCtrl -> receive()){
					$truckPhoto = array(	'hash' => $imgHash
										, 'targetURI' => $targetURI
										);
					$return['r'] = true;
					$return['truckPhoto'] = $truckPhoto;
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
		include_once('default/models/truck/db_selTruckAd.php');
		include_once('default/models/default/db_insVPic.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		
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
	
	/*******************************************
	 * Auxiliary function for action controller
	 *******************************************/	
	/**
	 * This function load truck brands and their corresponding truck modelss
	 */
	private function loadTruckModelsBrands($p = null){
		include_once ('default/models/truck/db_selTruckBrand.php');
		include_once ('default/models/truck/db_selTruckModel.php');
		
		$truckBrand = db_selTruckBrand(array('orderby'=>array(array('col' => 'brandName'))
											//, 'print' => true
										));
		
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
												//, 'p'=>true
											));
		}
		$this -> view -> truckBrand = $truckBrand;
		$this -> view -> truckModel = $truckModel;
	}
	/**
	 * This function load all truck extras.
	 */
	private function loadTruckExt(){
		/*
		include_once ('default/models/default/db_selExtra.php');
		$truckExt = db_selExtra(array('vType' => 'c'));
		if($truckExt != false){
			$this -> view -> truckExt = $truckExt;
		}
		*/
		include_once ('default/models/truck/db_selTruckExt.php');
		$truckExt = db_selTruckExt();
		if($truckExt != false){
			$this -> view -> truckExt = $truckExt;
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
	 * This function filter a truck advertisement
	 * @param $p:	this variable contains the parameter of a truck advertisement
	 */
	private function filterRecvParam($p){
		$truckCatDB = $this -> loadTruckCat();
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
		include_once('default/views/filters/FilterIsEmptyString.php');
		
		$fInt = new FilterMySQLInt();
		$fMInt = new FilterMySQLMInt();
		$fMonth = new FilterMonth();
		$fYear = new FilterYear();
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();
		$fIsEmptyStr = new FilterIsEmptyString();
		
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
			
			$p['truckBrandID'] = $truckBrand[0]['truckBrandID'];
			
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
			
			if(!isset($p['truckCat']) || ($p['truckCat'] == null) || !is_array($truckCatDB)){
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
	
	/**
	 * This action controler show a picture 
	 * @param	pid:	this parameter spcify the id of the picture
	 */
	public function picAction(){
		include_once('default/models/default/db_selVPic.php');
		$pid = $this -> getRequest() -> getParam('pid');
		
		$truckPic = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV,
									'vPicID' => $pid));	
		if($truckPic != false){
			$this -> view -> truckPic = $truckPic[0];
		}
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
		
		include_once ('default/models/truck/db_selTruckBrand.php');
		include_once ('default/models/truck/db_selTruckModel.php');
		
		$req = $this -> getRequest();
		
		$return = array('r' => false, 'tm' => array());
		
		$r_truckBrandID = $req -> getParam('tid');
		
		$truckBrand = db_selTruckBrand(array('truckBrandID' => $r_truckBrandID));
		if (($truckBrand != false) && is_array($truckBrand)){
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
					$tm = array('tmn' => $truckModel['truckModelName']
								, 'tmid' => $truckModel['truckModelID']
								, 'tml' => $truckModel['level']);
					array_push($return['tm'], $tm);
				}
			}
		}	
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
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
		include_once('default/models/truck/db_selTruckAd.php');
		
		//Initialize returning parameter
		$return = array('r' => false);
		
		if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)
			&& isset($this -> userNS -> userData) && is_array($this -> userNS -> userData)){
			$userDetails = $this -> userNS -> userData;							
			
			//get all parameters
			$p = $this -> getRequest() -> getParams();			
			if (isset($p['tid']) && is_numeric($p['tid']) && isset($p['t']) 
				&& is_array($userDetails) && isset($userDetails['userID'])){
				$truckID = $p['tid'];
				$truckDetails = db_selTruckAd(array('truckID' => $truckID));
				
				if (($truckDetails != false) && ($truckDetails[0]['userID'] == $userDetails['userID'])){
					$uploadRes = $this -> uploadPhoto(array('truckID' => $truckDetails[0]['truckID']
															, 'truckDetail' => $truckDetails
															)
														);
					if (($uploadRes != false) && is_array($uploadRes) 
						&& isset($uploadRes['r']) && ($uploadRes['r'] == true) ){
						if (!isset($this -> truckNS -> truckPhoto) || !is_array($this -> truckNS -> truckPhoto)){
							$this -> truckNS -> truckPhoto = array();
						}
						$this -> truckNS -> truckPhoto[$uploadRes['truckPhoto']['vPicID']] = $uploadRes['truckPhoto']; 
						
						$return['r'] = true;		
						$return['h'] = $uploadRes['truckPhoto']['vPicID'];
						$return['tid'] = $truckID;
						$return['t'] = $p['t'];
						$return['tu'] = $uploadRes['truckPhoto']['destURI'];				
					}
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
		include_once('default/models/truck/db_selTruckAd.php');
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
							
					$newTruckPhoto = array();
					$truckPhoto = $this -> truckNS -> truckPhoto;
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
					$this -> truckNS -> truckPhoto = $newTruckPhoto;
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
		include_once('default/models/truck/db_selTruckAd.php');
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
					
					$truckDetails = db_selTruckAd(array('truckID' => $vPic['vID']));
					if (($truckDetails != false) && is_array($truckDetails) 
						&& (count($truckDetails) == 1) && ($truckDetails[0]['userID'] == $userDetails['userID'])){							
						$truckDetails = $truckDetails[0];
						
						if ($vPic['vPicTMP'] == 1){
							$db_delVPic = db_delVPic(array(	'vPicID' => $vPic['vPicID']
															)
														);														
							if ($db_delVPic != false){
								$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.System_Properties::TRUCK_ABRV.'_'.$truckDetails['truckID'].'_'.$vPicID.'.jpeg';
								if (file_exists($srcFileURI)){
									unlink($srcFileURI);
								}	
							}
						}
							
						$newTruckPhoto = array();
						$truckPhoto = $this -> truckNS -> truckPhoto;
						foreach ($truckPhoto as $key=>$val){
							if ($key != $vPicID){
								$newTruckPhoto[$key] = $val;
							}
						}
						$this -> truckNS -> truckPhoto = $newTruckPhoto;
						$return['r'] = true;
						
						/*else{
							$srcFileURI = '.'.System_Properties::PIC_PATH.'/'.$truckDetails['truckID'].'_'.$vPicID.'.jpeg';
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
			}
		}
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	
	/**
	 * This functin return the next truck search results
	 * Input parameter
	 * @param	rs:		this parameter contains the result set
	 * @param 	ars:	this parameter contains the actual results entries
	 * 
	 * Output param
	 * @param	r:		this parameter specify the execution success of a request
	 * @param 	ca:		In the successful case this parameter contains the truck advertisments
	 */
	public function ajagnsrAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);		
		$return = array('r' => false);
		
	
		//Search truck advertisement in database			
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
		
		$truckSearchParam = $this -> truckNS -> truckSearchParam;
		if (is_array($truckSearchParam)){
			$p = $truckSearchParam;			
			//Set old page
			//$p['page'] = $page;
		}
		
		$p['limit'] = array('start' => $actResSet, 'num' => $resultSet);
		$truckAds = $this -> searchTruckAds($p);
		
		//Search process successfully passed and found some matches
		if (is_array($truckAds) && isset($truckAds['truckAds'])){
			$truckAds = $truckAds['truckAds'];			
			$return['r'] = true;
			$return['ca'] = array();
			foreach ($truckAds AS $ca){
				if ($ca['truckModelName'] == null){
					$ca['truckModelName'] = '';
				}
				array_push($return['ca'], $ca);
			}
		}				
		
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	
	public function viewprintAction(){
		include_once('default/models/truck/db_selTruckAd.php');
		include_once('default/models/truck/db_selTruck2Ext.php');
		include_once('default/models/default/db_selVPic.php');
		
		$req = $this -> getRequest();
		if ($req -> __isset('id')){
			$id = $req -> getParam('id');
			$truck = db_selTruckAd(array('truckID' => $id));
			if ($truck != false){
				$this -> loadTruckCat();
				$truck = $truck[0];
				
				$truckExtra = db_selTruck2Ext(array('truckID' => $truck['truckID']));
				$truck['truckExt'] = $truckExtra;
				
				$truckPic = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV
											, 'vID' => $truck['truckID']
											, 'vPicTMP' => '0'));		
				$truck['truckPics'] = $truckPic;
				
				$this -> view -> truck = $truck;
			}	
		}
	}

	public function ajasearchAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$return = array('r' => false);
	
		//Search truck advertisement in database
		$p = $this -> getRequest() -> getParams();
	
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
	
		$truckSearchParam = $this -> translateAjasearchParamAction($p);//$this -> truckNS -> truckSearchParam;
		if (is_array($truckSearchParam)){
			$p = $truckSearchParam;
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
		$truckAds = $this -> searchTruckAds($p);
		//Search process successfully passed and found some matches
		if (is_array($truckAds) && isset($truckAds['totalAds']) && ($truckAds['totalAds'] > 0) && isset($truckAds['truckAds'])){
			$return['r'] = true;
			//Search process successfully passed and found some matches
			if (is_array($truckAds) && isset($truckAds['totalAds']) && ($truckAds['totalAds'] > 0)
					&& isset($truckAds['truckAds']) && is_array($truckAds['truckAds'])){
				$return['r'] = true;
				$truckAds['truckAds'] = $this->replaceTruckDetailValue($truckAds['truckAds']);
				$return['ads'] = $truckAds;
			}
		}
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
		$this -> getResponse() -> setHeader('Content-type', 'application/json', true);
	}
	
	private function replaceTruckDetailValue($p_truck){
		include_once ('default/models/truck/db_selTruckCat.php');
		$lang = $this -> lang;
		$return = array();
		
		foreach($p_truck as $key => $truck){
			if(isset($lang['TXT_33']) && isset($truck['userAds'])
					&& is_array($lang['TXT_33']) && isset($lang['TXT_33'][$truck['userAds']])){
				$truck['userAds'] = $lang['TXT_33'][$truck['userAds']];
			}
			if(isset($lang['TXT_70']) && isset($truck['truckPriceType'])
					&& is_array($lang['TXT_70']) && isset($lang['TXT_70'][$truck['truckPriceType']])){
				$truck['truckPriceType'] = $lang['TXT_70'][$truck['truckPriceType']];
			}
			if(isset($lang['TXT_74']) && isset($truck['truckPriceCurr'])
					&& is_array($lang['TXT_74']) && isset($lang['TXT_74'][$truck['truckPriceCurr']])){
				$truck['truckPriceCurr'] = $lang['TXT_74'][$truck['truckPriceCurr']];
			}
			if(isset($lang['TXT_75']) && isset($truck['truckKMType'])
					&& is_array($lang['TXT_75']) && isset($lang['TXT_75'][$truck['truckKMType']])){
				$truck['truckKMType'] = $lang['TXT_75'][$truck['truckKMType']];
			}
			if(isset($lang['TXT_72']) && isset($truck['truckPowerType'])
					&& is_array($lang['TXT_72']) && isset($lang['TXT_72'][$truck['truckPowerType']])){
				$truck['truckPowerType'] = $lang['TXT_72'][$truck['truckPowerType']];
			}
			if(isset($lang['V_SHIFT']) && isset($truck['truckShift'])
					&& is_array($lang['V_SHIFT']) && isset($lang['V_SHIFT'][$truck['truckShift']])){
				$truck['truckShift'] = $lang['V_SHIFT'][$truck['truckShift']];
			}
			if(isset($lang['TRUCK_DOOR']) && isset($truck['truckDoor'])
					&& is_array($lang['TRUCK_DOOR']) && isset($lang['TRUCK_DOOR'][$truck['truckDoor']])){
				$truck['truckDoor'] = $lang['TRUCK_DOOR'][$truck['truckDoor']]."/".($lang['TRUCK_DOOR'][$truck['truckDoor']]+1);
			}
			if(isset($lang['V_EEK']) && isset($truck['truckEEK'])
					&& is_array($lang['V_EEK']) && isset($lang['V_EEK'][$truck['truckEEK']])){
				$truck['truckEEK'] = $lang['V_EEK'][$truck['truckEEK']];
			}
			if(isset($lang['V_STATE']) && isset($truck['truckState'])
					&& is_array($lang['V_STATE']) && isset($lang['V_STATE'][$truck['truckState']])){
				$truck['truckState'] = $lang['V_STATE'][$truck['truckState']];
			}
			if(isset($lang['V_CAT']) && is_array($lang['V_CAT']) && isset($truck['truckCat'])){
				$truckCat = db_selTruckCat(array('truckCatID' => $truck['truckCat']));
				if(($truckCat != false) && (count($truckCat) > 0)){
					$truckCat = $truckCat[0];
					if(isset($lang['V_CAT'][$truckCat['vcatID']])){
						$truck['truckCat'] = $lang['V_CAT'][$truckCat['vcatID']];
					}else{
						$truck['truckCat'] = null;
					}
				}else{
					$truck['truckCat'] = null;
				}
			}
			if(isset($lang['V_FUEL']) && isset($truck['truckFuel'])
					&& is_array($lang['V_FUEL']) && isset($lang['V_FUEL'][$truck['truckFuel']])){
				$truck['truckFuel'] = $lang['V_FUEL'][$truck['truckFuel']];
			}
			if(isset($lang['V_CLR']) && isset($truck['truckClr'])
					&& is_array($lang['V_CLR']) && isset($lang['V_CLR'][$truck['truckClr']])){
				$truck['truckClr'] = $lang['V_CLR'][$truck['truckClr']];
			}
			if(isset($lang['V_EMISSION_NORM']) && isset($truck['truckEmissionNorm'])
					&& is_array($lang['V_EMISSION_NORM']) && isset($lang['V_EMISSION_NORM'][$truck['truckEmissionNorm']])){
				$truck['truckEmissionNorm'] = $lang['V_EMISSION_NORM'][$truck['truckEmissionNorm']];
			}
			if(isset($lang['V_ECOLOGIC_TAG']) && isset($truck['truckEcologicTag'])
					&& is_array($lang['V_ECOLOGIC_TAG']) && isset($lang['V_ECOLOGIC_TAG'][$truck['truckEcologicTag']])){
				if($truck['truckEcologicTag'] === '0'){
					$truck['truckEcologicTag'] = -1;
				}
				else{
					$truck['truckEcologicTag'] = $lang['V_ECOLOGIC_TAG'][$truck['truckEcologicTag']];
				}
			}
			if(isset($lang['V_KLIMA']) && isset($truck['truckKlima'])
					&& is_array($lang['V_KLIMA']) && isset($lang['V_KLIMA'][$truck['truckKlima']])){
				$truck['truckKlima'] = $lang['V_KLIMA'][$truck['truckKlima']];
			}
			
			if(isset($truck['truckExt']) && is_array($truck['truckExt'])){
				$truckExt = array();
				foreach($truck['truckExt'] as $key => $kVal){
					if(is_array($kVal) && isset($kVal['vextID'])){
						if(isset($lang['V_EXTRA']) && is_array($lang['V_EXTRA'])
								&& isset($lang['V_EXTRA'][$kVal['vextID']])){
							array_push($truckExt, $lang['V_EXTRA'][$kVal['vextID']]);
						}
					}
				}
				$truck['truckExt'] = $truckExt;
			}
			
			if(isset($truck['truckPics']) && is_array($truck['truckPics'])){
				$truckPics = array();
				foreach($truck['truckPics'] as $key => $kVal){
					if(isset($kVal['vPicID']) && isset($kVal['vID'])){
						$picFile = '.'.System_Properties::PIC_PATH.'/'.System_Properties::TRUCK_ABRV.'_'.$kVal['vID'].'_'.$kVal['vPicID'].'.jpeg';
						if (file_exists($picFile)){
							array_push($truckPics, array('vPicID' => $kVal['vPicID'], 'vID' => $kVal['vID']));
						}
					}
				}
				$truck['truckPics'] = $truckPics;
			}
			
			unset($truck['ip']);
			unset($truck['timestam']);
			unset($truck['erased']);
			unset($truck['userID']);
			unset($truck['hitCounter']);
			unset($truck['extLink']);
			unset($truck['userLinkAds']);
			
			array_push($return, $truck);
		}
		return $return;
	}

	public function ajagetdetailAction(){
		include_once('default/models/truck/db_selTruckAd.php');
		include_once('default/models/truck/db_selTruck2Ext.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('Zend/Json.php');
	
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$return = array('r' => false);
		$p = $this -> getRequest() -> getParams();
	
		if(isset($p['tid'])){
			$truckID = $p['tid'];
			$truck = db_selTruckAd(array('truckID' => $truckID));
			if ($truck != false){
				$truck = $truck[0];
	
				$truckExtra = db_selTruck2Ext(array('truckID' => $truck['truckID']));
				$truck['truckExt'] = $truckExtra;
	
				$truckPic = db_selVPic(array(	'vType' => System_Properties::TRUCK_ABRV
						, 'vID' => $truck['truckID']
						, 'vPicTMP' => '0'));
				$truck['truckPics'] = $truckPic;
	
				$truck = $this -> replaceTruckDetailValue(array($truck));
				if(is_array($truck) && isset($truck[0])){
					$truck = $truck[0];
				}
	
				$return['r'] = true;
				$return['v'] = $truck;
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
				//$this -> view -> truckPhoto = $this -> truckNS -> truckPhoto;
				$newTruckPhoto = array();
				$truckPhoto = $this -> truckNS -> truckPhoto;
				foreach ($truckPhoto as $key=>$val){
					if ($key != $vPicID){
						$newTruckPhoto[$key] = $val;
					}
				}
				$this -> truckNS -> truckPhoto = $newTruckPhoto;
				
				//Erase image from hard disk
				unlink($fileName);
				
				$return['r'] = true;
			}
		}
		*/
?>