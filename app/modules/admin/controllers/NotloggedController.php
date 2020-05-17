<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101011
 * Desc:		This controller is processed when a user is not logged in
 *******************************************************************************/
include_once('classes/AbstractController.php');
class Admin_NotloggedController extends AbstractController{
	
	public function preDispatch(){
		parent::preDispatch();		
		
		$this -> view -> tmpl = $this->tmpl;
		$this -> view -> lang = $this -> lang;		
	}
	
	public function indexAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/views/filters/FilterEncMD5.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/user/db_selUser.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selAuthorityMapping.php');
			
		$req = $this -> getRequest();
		$lang = $this -> lang;
		
		if ($req -> __isset('userLogIn')
			&& $req -> __isset('userEMail')
			&& $req -> __isset('userPW')){
				/*
			if (!isset($this -> adminNS -> adminData)
				&& !isset($this -> adminNS -> adminLogged)
				&& ($this -> adminNS -> adminData != null)
					&& ($this -> adminNS -> adminLogged != true)){
						*/
				$filterMD5 = new FilterEncMD5();
				$userEMail = $req -> getParam('userEMail');
				$userPW = $filterMD5 -> filter($req -> getParam('userPW'));
				if (($userEMail == System_Properties::ADMIN_EMAIL)
					&& ($userPW == System_Properties::ADMIN_PW)){
					$this -> adminNS -> adminData = array(	'userID' => 0
															, 'userEMail' => System_Properties::ADMIN_EMAIL
															, 'userAuthority' => array(array('authorityName' => System_Authority::ROOT_ACCESS))
															//,'userPW' => System_Properties::ADMIN_PW
															);
					$this -> adminNS -> adminLogged = true;
					$this -> _forward('index', 'index');
				}else{
					$userDetail = db_selUser(array(
													'userEMail' => $userEMail,
													'userPW' => $userPW 
													));
					if(($userDetail != false) && is_array($userDetail) && (count($userDetail) == 1)){
						$userDetail = $userDetail[0];
						$userAuth = db_selAuthorityMapping(array('groupID' => $userDetail['groupID']));
						if (is_array($userAuth)){
							$userDetail['userAuthority'] = $userAuth;
							if ((System_Properties::checkAuthority(array(
																		'userAuth' => $userDetail['userAuthority']
																		, 'desAuth' => System_Authority::ADMIN_ACCESS
																	)
																) == true)
								&& ($userDetail['userStat'] == 1)){
								$this -> adminNS -> adminData = $userDetail;
								$this -> adminNS -> adminLogged = true;
								$this -> _forward('index', 'index','admin');
							}else{
								$this -> view -> error = $lang['AERR_11'];								
							}
						}else{
							$this -> view -> error = $lang['AERR_11'];
						}
					}else{
						$this -> view -> error = $lang['AERR_1'];
					}
				}
			//}
		}
	}
}
?>