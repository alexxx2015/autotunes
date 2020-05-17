<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101216
 * Desc:		This is the administrator controller for maintanence groups
 *********************************************************************************/
include_once('classes/AbstractController.php');
class Admin_GroupController extends AbstractController{
	
	public function preDispatch(){
		parent::preDispatch();				
		
				
		if (!isset($this -> adminNS -> adminData)
			|| !isset($this -> adminNS -> adminLogged)
			|| ($this -> adminNS -> adminLogged != true)) {
			$this -> _forward('index', 'notlogged');
		}
		
		$action = $this -> getRequest() -> getActionName();
		$req = $this -> getRequest();
		//Check Authority for "GROUP_SHOW" action
		if( ($action == 'showgroup')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::GROUP_SHOW
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "GROUP_EDIT" action
		elseif(($action == 'showgroup') && ($req -> __isset('groupEdit'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::GROUP_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "GROUP_DELETE" action
		if(($action == 'showgroup') && ($req -> __isset('groupDelete'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::GROUP_DELETE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "GROUP_CREATE" action
		else if(($action == 'newgroup') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::GROUP_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this->tmpl;//$this -> getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;
	}
	
	public function indexAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		$userGroup = db_selGroup();
		if (($userGroup != false)
			&& is_array($userGroup)
			&& (count($userGroup) > 0)) {
			$this -> view -> userGroup = $userGroup;
		}
		
	}
	
	public function newgroupAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selAuthority.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_insGroup.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_insAuthorityMapping.php');
		
		$req = $this -> getRequest();
		$lang = $this -> lang;
		if ($req -> __isset('groupNew')){
			$p = $req -> getParams();
			if (isset($p['groupName']) && isset($p['groupAuthority'])){
				$groupDetails = db_selGroup(array('groupName' => $p['groupName']));
				//$p['authorityID'] = $p['groupAuthority'];				
				//$authorityDetails = db_selAuthority($p);
				if ($groupDetails == false){ 
					//&& ($authorityDetails != false) 
					//&& is_array($authorityDetails) 
					//&& (count($authorityDetails) > 0)){
					$groupID = db_insGroup($p);
					if ($groupID != false){
						//Insert group authorities
						if (isset($p['groupAuthority']) && is_array($p['groupAuthority'])){
							foreach ($p['groupAuthority'] as $groupAuthority){
								$authorityDetails = db_selAuthority(array('authorityID' => $groupAuthority));
								if ($authorityDetails != false){
									db_insAuthorityMapping(array('groupID' => $groupID, 'authorityID' => $authorityDetails[0]['authorityID']));
								}
							}
						}
						$this -> view -> info = $lang['AINFO_1'];
						$this -> indexAction();
						$this -> render('index');				
					}else{
						$this -> view -> error = $lang['AERR_5'];
					}
				}else{
					$this -> view -> error = $lang['AERR_6'];
					$this -> indexAction();
					$this -> render('index');
				}
			}else{
				$this -> view -> error = $lang['AERR_5'];
			}
		}
		$this -> view -> groupAuthority = db_selAuthority();
	}
	
	public function showgroupAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selAuthority.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selAuthorityMapping.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_updGroup.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_delAuthorityMapping.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_insAuthorityMapping.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroupMember.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_delGroup.php');
		$req = $this -> getRequest();
		$lang = $this -> lang;
		if ($req -> __isset('id')){
			$p = $req -> getParams();
			$group = db_selGroup(array('groupID'=>$p['id']));
			
			if (($group != false) && is_array($group) && (count($group) == 1)){				
				$group = $group[0];

				//Update group
				if ($req -> __isset('groupEdit')){
					//First update group name
					if (isset($p['groupName']) && ($p['groupName'] != null)){
						$updateGroup = db_selGroup(array('groupName' => $p['groupName']));
						if ($updateGroup == false){
							db_updateGroup(array('groupName' => $p['groupName']
												, 'groupID' => $group['groupID'] 
												)
											);							
						}
					}					
					//Second update group authority
					db_delAuthorityMapping(array('groupID' => $group['groupID']));
					if (isset($p['groupAuthority']) && is_array($p['groupAuthority'])){
						foreach ($p['groupAuthority'] as $groupAuthority){
							$authority = db_selAuthority(array('authorityID' => $groupAuthority));
							if ($authority != false){
								db_insAuthorityMapping(array('groupID' => $group['groupID']
															, 'authorityID' => $authority[0]['authorityID']));
							}							
						}
					}	
					$this -> view -> info = $lang['AINFO_2'];	
					$group = db_selGroup(array('groupID'=>$group['groupID']));
					$group = $group[0];			
				}
				//Gruppe löschen
				else if ($req -> __isset('groupDelete')){
					$groupMember = db_selGroupMember(array('groupID' => $group['groupID']));
					if ($groupMember == false){
						$updateGroup = db_updateGroup(array('groupID' => $group['groupID']
															, 'erased'=>1
															)
													);
						
						if($updateGroup != false){
							db_delAuthorityMapping(array('groupID' => $group['groupID']));
							$this -> view -> error = $lang['AERR_8'];
							$this -> indexAction();
							$this -> render('index');
						}else{							
							$this -> view -> error = $lang['AERR_9'];
						}
					}
					else{
						$this -> view -> error = $lang['AERR_7'];
					}
				}
				
				$authority = db_selAuthorityMapping(array('groupID' => $group['groupID']));
				$authorityDetails = db_selAuthority();
				if ($authority != false){
					$group['authority'] = $authority;
				}
				$this -> view -> group = $group;
				$this -> view -> groupAuthority = db_selAuthority();
			}else{
				$this -> view -> error = $lang['AERR_2'];
				$this -> _forward('index');
			}
		}else{
			$this -> _forward('index');
		}
	}
}
?>