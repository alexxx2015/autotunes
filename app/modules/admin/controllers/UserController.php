<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101212
 * Desc:		This is the administrator controller for maintanence user
 *********************************************************************************/
include_once('classes/AbstractController.php');
class Admin_UserController extends AbstractController{
	
	private $user;
	
	public function preDispatch(){
		parent::preDispatch();		
		
		$this -> view -> tmpl = $this->tmpl;//$this -> getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;
			
		if (!isset($this -> adminNS -> adminData)
			|| !isset($this -> adminNS -> adminLogged)
			|| ($this -> adminNS -> adminLogged != true)) {
			$this -> _forward('index', 'notlogged');
		}		
		
		$action = $this -> getRequest() -> getActionName();
		$req = $this -> getRequest();
		//Check Authority for "ADMIN_USER_SHOW" action
		if( (($action == 'showuser') || ($action == 'usersearch')) 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::USER_SHOW
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "USER_EDIT" action
		elseif(($action == 'showuser') && ($req -> __isset('userSafe'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::USER_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "USER_DELETE" action
		if(($action == 'showuser') && ($req -> __isset('userDelete'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::USER_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "USER_CREATE" action
		else if(($action == 'newuser') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::USER_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
	}
	
	public function indexAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		$this -> view -> userGroup = db_selGroup();
	}
	/**
	 * This function search a new user
	 */
	public function usersearchAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_selUser.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		$req = $this -> getRequest();
		$p = $req -> getParams();
		//User search
		if ($req -> __isset('userSearch')){
			switch ($req -> getParam('userSort')){
				case 1 : 	$userSortCol = 'userNName';
							break;
				case 2 : 	$userSortCol = 'userVName';
							break;
				case 3 :	$userSortCol = 'userEMail';
							break;
				case 4 : 	$userSortCol = 'userPLZ';
							break;
				case 5 :	$userSortCol = 'userOrt';
							break;
				default: 	$userSortCol = 'userID';				
			}
			$userSortDesc = false;
			if ($req -> getParam('userSortDirect') == '2'){
				$userSortDesc = true;
			}
			$user = db_selUser(array('userNName' => $req -> getParam('userNName')
									, 'userVName' => $req -> getParam('userVName')
									, 'userEMail' => $req -> getParam('userEMail')
									, 'userPLZ' => $req -> getParam('userPLZ')
									, 'userOrt' => $req -> getParam('userOrt')
									, 'userGroup' => $req -> getParam('userGroup')
									, 'orderby' => array(array('col' => $userSortCol,
																'desc' => $userSortDesc
															)
														)
									)
								);
								
			if (($user != false)
				&& isset($user)
				&& (count($user) > 0)){
				$this -> view -> user = $user;
			}
		}
		$this -> view -> searchParam = $p;
		$this -> view -> userGroup = db_selGroup();
		$this -> render('usersearch');
	}
	
	/**
	 * This function safe a user
	 * Enter description here ...
	 */
	private function insertuserAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_selUser.php');		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_insUser.php');
		include_once('default/models/member/db_selFTPUser.php');
		include_once('default/models/member/db_insFTPUser.php');
		
		$this -> view -> userGroup = db_selGroup();
		$req = $this -> getRequest();
		$lang = $this -> lang;
		$p = $req -> getParams();
		$p['datexFTPVType'] = -1;
		if (isset($p['vType'])){
			$p['datexFTPVType'] = $p['vType'];
		}		
		$this -> user = $p;
		$this -> view -> user = $this -> user;
		
		/*
		$userGroup = $req -> getParam('userGroup');
		$userStat = $req -> getParam('userStat');
		$userMode = $req -> getParam('userMode');
		$userFirm = $req -> getParam('userFirm');
		$userNName = $req -> getParam('userNName');
		$userVName = $req -> getParam('userVName');
		$userEMail = $req -> getParam('userEMail');
		$userTelNr1 = $req -> getParam('userTelNr1');
		$userTelNr2 = $req -> getParam('userTelNr2');
		$userPLZ = $req -> getParam('userPLZ');
		$userOrt = $req -> getParam('userOrt');
		$userAdress = $req -> getParam('userAdress');
		*/
		
		if (!isset($p['userGroup'])
			|| (db_selGroup(array('groupID' => $p['userGroup'])) == false)){
			$this -> view -> error = $lang['AERR_2'];
			$this -> render('newuser');
		}else{
			$p = $this -> filteruserdataAction($p);
			if (isset($p['error'])){
				$this -> view -> error = $p['error'];
				$this -> render('newuser');
			}else{
				
				$userDetail = db_selUser(array('userEMail' => $p['userEMail']
												, 'userNName' => $p['userNName']
												, 'userVName' => $p['userVName']
											)
										);
										
				if ($userDetail == false){
					$p['groupID'] = $p['userGroup'];					
					!isset($p['userPW']) ? $p['userPW'] = '':'';
					$p['userExtID'] = '0';
					$p['userCountry'] = 'DE';
					$p['userURL'] = '';
					
					$insUserRes = db_insUser($p);
					if( ( $insUserRes != false) && is_numeric($insUserRes)){
						$userID = $insUserRes;
						$ftpUser = @db_selFTPUser(array('USERNAME' => $p['userEMail']
												//, 'CUSTOMERID' => '1'
												));
						if (($ftpUser == false) || !is_array($ftpUser)){
						
							//Update FTP account
							$ftpLoginEnabled = 'N';
							if (isset($p['datexFTPImp']) && ($p['datexFTPImp'] == 1)){
								$ftpLoginEnabled = 'Y';
							}
					
							$homeDir = $this -> docRoot.'app/ftp/'.$userID.'/upload/';
							$insFTPUser = db_insFTPUser(array('USERNAME' => $p['userEMail']
															, 'PASSWORD' => $p['userPW']
															, 'LOGIN_ENABLED' => $ftpLoginEnabled
															, 'HOMEDIR' => $homeDir
															, 'UID' => System_Properties::FTP_UID
															, 'GID' => System_Properties::FTP_GID
															));
							if ($insFTPUser != false){
								if (!file_exists($homeDir)){
									if(mkdir($homeDir, 0775, true) == true){
										if (chgrp($homeDir, System_Properties::FTP_GROUP)){
										}
										chmod($homeDir, 0775);
									}
								}
							}
						}
						
						$this->view->info = $lang['AINFO_5'];
					}else{
						$this -> view -> error = $lang['AERR_27'];
					}
				}else{
					$this->view->error = $lang['AERR_4'];
				}
				
				//$this -> render('newuser');
			}
		}		
	}
	
	private function filteruserdataAction($p){
		$lang = $this -> lang;
		//Process only if cins is pressed
		include_once('default/views/filters/FilterValidEmail.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterString20.php');
		include_once('default/views/filters/FilterEncMD5.php');
		include_once('default/views/filters/FilterIsEmptyString.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_selUser.php');
		include_once('default/models/member/db_selFTPUser.php');
		
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$fString20 = new FilterString20();
		$fEndMD5 = new FilterEncMD5();
		$fIsEmptyStr = new FilterIsEmptyString();
		
		$userByEMail = db_selUser(array('userEMail' => $p['userEMail']));
		if (($userByEMail != false) && is_array($userByEMail) && (count($userByEMail) > 0)){
			$userByEMail = $userByEMail[0];
		}
		
		$ftpUser = db_selFTPUser(array('USERNAME' => $p['userEMail']));
		if (($ftpUser != false) && is_array($ftpUser) && (count($ftpUser) > 0)){
			$ftpUser = $ftpUser[0];
		}
		
		//Check userNName
		if (( !isset($p['userNName']) || ($fString100 -> isValid($p['userNName']) == false)) && (($p['userMode'] == 2) || ($p['userMode'] == 3)) ){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userVName
		else if (( !isset($p['userVName']) || ($fString100 -> isValid($p['userVName']) == false)) && (($p['userMode'] == 2) || ($p['userMode'] == 3)) ){
			$p['error'] = $lang['ERR_2'];
		}
		//Check userFirm
		else if (( !isset($p['userFirm']) || ($fString100 -> isValid($p['userFirm']) == false)) && ($p['userMode'] == 1) ){
			$p['error'] = $lang['ERR_2'];
		}	
		//check userTel1
		elseif (isset($p['userMode']) && ($p['userMode'] == 1)
				&& ($fString100->isValid($p['userTel1']) == false
					|| $fIsEmptyStr->filter($p['userTel1']) == true)){
			$p['error'] = $lang['ERR_55'];
		}
		//check userPLZ
		elseif (isset($p['userMode']) && ($p['userMode'] == 1)
				&& ($fString20->isValid($p['userPLZ']) == false
					|| $fIsEmptyStr->filter($p['userPLZ']) == true)){
			$p['error'] = $lang['ERR_56'];
		}
		//check userORt
		elseif (isset($p['userMode']) && ($p['userMode'] == 1)
				&& ($fString100->isValid($p['userOrt']) == false
					|| $fIsEmptyStr->filter($p['userOrt']) == true)){
			$p['error'] = $lang['ERR_57'];
		}
		//Check userEmail
		else if ( !isset($p['userEMail']) || ($fEMail->filter($p['userEMail']) == false)){
			$p['error'] = $lang['ERR_2'];
		}elseif( ($userByEMail != false) && is_array($userByEMail) && isset($userByEMail['userID']) && ($p['userID'] != $userByEMail['userID'])){
			$p['error'] = $lang['ERR_2'];
		}elseif (($ftpUser != false) && is_array($ftpUser)){
			$p['error'] = $lang['ERR_2'];			
		}
		//Check AGB
		else if ( !isset($p['userAGB'])){
			$p['error'] = $lang['ERR_6'];
		}
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
			
			if(!isset($p['userMode']) || ($p['userMode'] == null)){
				$p['userMode'] = -1;
			}else{
				$p['userMode'] = $fTInt->isValid($p['userMode']);
			}
			
			if (!isset($lang['TXT_33'][$p['userStat']])){
				$p['userStat'] = 1;
				//$p['userStat'] = $fTInt->isValid($p['userStat']);
			}
			
			if (isset($p['userNews'])){
				$p['userNews'] = 1;
			}else{
				$p['userNews'] = 0;
			}
			
			if (isset($p['userAGB'])){
				$p['userAGB'] = 1;
			}else{
				$p['userAGB'] = 0;
			}
			
			if (isset($p['datexImp'])){
				$p['datexImp'] = 1;
			}else{
				$p['datexImp'] = 0;
			}
			
		
		
			//Check datexFTPImp
			if (isset($p['datexFTPImp']) && isset($p['datexImp']) && ($p['datexImp'] == 1)){
				if( ($p['vType'] == System_Properties::CAR_ABRV)
								|| ($p['vType'] == System_Properties::BIKE_ABRV)
								|| ($p['vType'] == System_Properties::TRUCK_ABRV) ){
					$p['datexFTPImp'] = 1;
					$p['datexFTPVType'] = $p['vType'];
				}
				else{
					$p['datexFTPImp'] = 0;
				}
			}else{
				$p['datexFTPImp'] = 0;
			}
			
			if (isset($p['datexAutoImp'])){
				$p['datexAutoImp'] = 1;
			}else{
				$p['datexAutoImp'] = 0;
			}
			
			if (isset($p['datexExp'])){
				$p['datexExp'] = 1;
			}else{
				$p['datexExp'] = 0;
			}			
				
			//datexFormat	
			$dataIntfFile = System_Properties::$DATA_INTF_FILE;
			if (!isset($dataIntfFile[$p['datexFormat']])){
				$p['datexFormat'] = '-1';
			}
			
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
	 * This functino process displaying the standard screen for 
	 * creation a new user
	 */
	public function newuserAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		$req = $this -> getRequest();
		//Use new
		if ($req -> __isset('userNew')){
			$this -> insertuserAction();
		}
		$this -> view -> userGroup = db_selGroup();
	}
	
	public function showuserAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_selUser.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		$lang = $this -> lang;
		$req = $this -> getRequest();
		$p = $req -> getParams();
		$userID = $req -> getParam('id');		
		$userDetails = db_selUser(array(
										'userID' => $userID
										)
									);
		if (($userDetails != false)
			&& is_array($userDetails)
			&& (count($userDetails) == 1)){
			$userDetails = $userDetails[0];
			
			//Administrator change user data and perform an update
			if ($req -> __isset('userSafe')){
				$p['userID'] = $userDetails['userID'];
				if (isset($p['userPW'])){
					if( ($p['userPW'] == $userDetails['userPW'])
						|| (md5($p['userPW']) == $userDetails['userPW'])
						|| ($p['userPW'] == ' ')){
						unset($p['userPW']);
					}
				}
				$updateRet = $this -> updateuserAction($p);
				if (isset($updateRet['error'])){
					$this -> view -> error = $updateRet['error'];
				}else if (isset($updateRet['info'])){	
					$userDetails = db_selUser(array(
													'userID' => $userID
													)
												);
					if( ($userDetails != false) && is_array($userDetails) && (count($userDetails) > 0)){
						$userDetails = $userDetails[0];
					}
					$this -> view -> info = $updateRet['info'];
				}
			}
			//Delete selected user
			else if ($req -> __isset('userDelete')){
				if ($this -> deleteuserAction($userDetails) != false){
					$this -> indexAction();
					$this -> view -> info = $lang['AINFO_3'];
					$this -> render('index');					
				}
			}
			$this -> view -> userGroup = db_selGroup();
			$this -> view -> user = $userDetails;
		}else{
			$this -> _forward('index');
		}
	}
	
	private function updateuserAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_updUser.php');
		include_once('default/models/member/db_updFTPUser.php');
		$lang = $this -> lang;
		
		
		if (!isset($p['userGroup'])
			|| (db_selGroup(array('groupID' => $p['userGroup'])) == false)){
			$p['error'] = $lang['AERR_2'];
		}else{
			$p['userPW1'] = null;
			if (isset($p['userPW'])){
				$p['userPW1'] = $p['userPW'];
			}
			$p = $this -> filteruserdataAction($p);
			if (!isset($p['error'])){
				if (isset($p['userEMail'])){
					$p['USERNAME'] = $p['userEMail'];
					unset($p['userEMail']);
				}
				$updateRet = db_updateUser($p);
				if ($updateRet == false){
					$p['error'] = $lang['ATXT_23'];
				}else{
					//Update FTP account
					$ftpLoginEnabled = 'N';
					if (isset($p['datexFTPImp']) && ($p['datexFTPImp'] == 1)){
						$ftpLoginEnabled = 'Y';
					}
					db_updFTPUser(array(System_Properties::SQL_SET => array('LOGIN_ENABLED' => $ftpLoginEnabled
																			, 'PASSWORD' => $p['userPW1']
																				)
										, System_Properties::SQL_WHERE => array('USERNAME' => $p['USERNAME'])
										//, 'print'=>true
											));
					$p['info'] = $lang['ATXT_24'];
				}
			}
		}
		return $p;
	}
	
	private function deleteuserAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_updUser.php');
		include_once('default/models/member/db_selFTPUser.php');
		include_once('default/models/member/db_delFTPUser.php');
		
		$delUser = db_updateUser(array(	'erased' => 1
										,'userID' => $p['userID'] 
										));
		if ($delUser != false){
			$ftpUser = db_selFTPUser(array('USERNAME'=>$p['userEMail']
										, 'DEACTIVATED' => array('1','0')));
			if (($ftpUser != false) && is_array($ftpUser) && (count($ftpUser) > 0)){
				$ftpUser = $ftpUser[0];
				db_delFTPUser(array('USERNAME' => $ftpUser['username']
									, 'CUSTOMERID' => $ftpUser['customerid']));
			}
		}
		return $delUser;
	}
	
	public function logoutAction(){
		//$this -> carNS = new Zend_Session_Namespace(System_Properties::CAR_ADS_NS);
		
		//$this -> carNS -> unsetAll();
		$this -> adminNS -> unsetAll();
		$this -> _forward('index', 'index');
	}
}
?>