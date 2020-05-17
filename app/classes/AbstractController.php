<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for the administration INDEX
 *********************************************************************************/

//Template engine
include_once('classes/TMPL.php');
include_once('classes/System_Activity.php');
include_once('classes/System_Properties.php');	
		
//Set session namespace properties
include_once('Zend/Session/Namespace.php');

include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selSystem.php');

abstract class AbstractController extends Zend_Controller_Action{
	protected $tmpl;
	protected $lang;
	protected $system;
	protected $visitorID;
	
	protected $userNS;
	protected $adminNS;
	protected $adsRecomNS;
	
	protected $docRoot;

	public function preDispatch(){
		$fCtrl = $this -> getFrontController();
		$tmpTmpl =  $fCtrl -> getParam('TMPL');
		if (stristr($_SERVER['DOCUMENT_ROOT'],'/t01/')){
			$this -> docRoot = '/var/customers/webs/autotunes/t01/';
		}else{
			$this -> docRoot = '/var/customers/webs/autotunes/www/';
		}
		
		if ($tmpTmpl == null){
			$this -> logUser();	
			$tmpl = null;
			
			$modDirect = $this -> getFrontController() -> getModuleDirectory();
			//Default module?
			if (stripos($modDirect, 'default')){				
				//load the specific language file
				include_once('modules/default/lang/lang.php');
				//Create the template guarantor
				$tmpl = new TMPL(array(
					'startDelim' => '{-',
					'endDelim' => '-}',
					'tmplPath' => '../app/tmpl/',
					'mainTmpl' => 'default/main.html',
					'lang' => $lang
				));		
				$tmpl -> setValue('META_KEYWORDS', $lang['TXT_247']);
			}
			//Admin module?
			else if(stripos($modDirect, 'admin')){								
				$lang = $this -> getFrontController() -> getParam('LANG');
				if ($lang == null){
					//load specific language file for administration
					include_once('admin/lang/lang.php');
					$this -> getFrontController() -> setParam('LANG', $lang);
				}
				//Create the template guarantor		
				$tmpl = new TMPL(array(
					'startDelim' => '{-',
					'endDelim' => '-}',
					'tmplPath' => '../app/tmpl/',
					'mainTmpl' => 'admin/main.html',
					'lang' => $lang
				));
				
				$tmpl -> readTmplFile('MENU_HEADER', System_Properties::ADMIN_MOD_PATH.'/menu_header.html');
				$tmpl -> setValue('ADMIN_MOD_PATH', '/'.System_Properties::ADMIN_MOD_PATH);			
				$tmpl -> setValue('CSS_FILES', '<link rel="stylesheet" type="text/css" href="'.System_Properties::ADMIN_CSS_PATH.'/main.css" />');
			}
			$tmpl -> setValue('TITLE', '');
			$tmpl -> setValue('META_DESCRIPTION', '');
			//$tmpl -> setValue('META_KEYWORDS', '');
			$tmpl -> setValue('XMLNS', '');
			$tmpl -> setValue('OPEN_GRAPH', '');
			$fCtrl -> setParam('TMPL', $tmpl);
		}else{
			$tmpl = $tmpTmpl;//$fCtrl -> getParam('TMPL');
			$lang = $tmpl -> getLang();
		}
		
		//Set correct namespaces
		$modDirect = $this -> getFrontController() -> getModuleDirectory();	
		
		//Default module?
		if (stripos($modDirect, 'default')){
			//Check if user is logged in
			$this -> userNS = new Zend_Session_Namespace(System_Properties::USER_NS);
			if (!isset($this -> userNS -> userLogged) || ($this->userNS->userLogged==null)){
				$this -> userNS -> userLogged = false;
			}
			
			$this -> setDefaultModValues(array('TMPL'=>$tmpl));	
		}
		//Admin module?
		else if(stripos($modDirect, 'admin')){			
			$this -> adminNS = new Zend_Session_Namespace(System_Properties::ADMIN_NS);
			
			$this -> setAdminModValues(array('TMPL'=>$tmpl));
		}
		
		
		$tmpl -> setValue('JS_CODE', '');
		
		//Check if system is online
		$this -> system = db_selSystem(array('orderby' => array(array('col' => 'timestam'
																, 'desc' => true
																)
														),
										'limit' => array('start' => 0
														, 'num' => 1)
										)
								);
		$offline = true;
		if (($this -> system != false) && is_array($this -> system) && (count($this -> system) > 0)){
			$this -> system = $this -> system[0];
			$tmpl -> setValue('TITLE', $this -> system['sysSiteName'].' - '.$lang['TXT_231']);
			if (isset($this -> system['sysOnline']) && ($this -> system['sysOnline'] == 1)){
				$offline = false;
			}		
		}
		
						
		$action = $this -> getRequest() -> getActionName();
		if (($offline == true) && ($action != 'offline')){
			$modDirect = $this -> getFrontController() -> getModuleDirectory();
			//Default module?
			if (stripos($modDirect, 'default')){
				$this -> _forward('offline', 'index', 'default');
			}
		}
		
		$this -> tmpl = $tmpl;
		$this -> lang = $lang;
				
		//Set ADS_RECOM_NS
		$this -> adsRecomNS = new Zend_Session_Namespace(System_Properties::ADS_RECOM_NS);
	}
	
	protected function setDefaultModValues($p=null){	
		if (isset($p['TMPL'])){
			$tmpl = $p['TMPL'];
			//Set some initial values
			$tmpl -> setValue('CSS_FILES', '<link rel="stylesheet" type="text/css" href="'.System_Properties::CSS_PATH.'/main.css" />');
			
			$tmpl -> setValue('JS_FILES', '<script type="text/javascript" src="'.System_Properties::JS_PATH.'/jquery_164.js"></script>
								<script type="text/javascript" src="'.System_Properties::JS_PATH.'/main.js"></script>');
			
			$userData = null;
			if (isset($this -> userNS -> userData)){
				$userData = $this -> userNS -> userData;
			}
			
			$userLogged = false;
			if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)){
				$userLogged = true;
			}
			
			//Handle main manu entries
			include('default/views/scripts/default/main_menu.phtml');
		}		
	}
	
	protected function setAdminModValues($p=null){	
		if (isset($p['TMPL'])){
			$tmpl = $p['TMPL'];
			//Set some initial values
			$tmpl -> setValue('CSS_FILES', '<link rel="stylesheet" type="text/css" href="'.System_Properties::ADMIN_CSS_PATH.'/main.css" />');
			$tmpl -> setValue('JS_FILES', '<script type="text/javascript" src="'.System_Properties::JS_PATH.'/jquery_164.js"></script>');
					
			
			
			$adminData = null;
			if (isset($this -> adminNS -> adminData)){
				$adminData = $this -> adminNS -> adminData;
			}
			
			$adminLogged = false;
			if (isset($this -> adminNS -> adminLogged) && ($this -> adminNS -> adminLogged == true)){
				$adminLogged = true;
			}
			
			//Handle main manu entries
			include('default/views/scripts/default/main_menu.phtml');
		}		
	}

	
	/**
	 * This function filter email properties and return the filtered paramters to the requestor
	 * The following paramters should be set:
	 * @param 	emailName:	The real name of the sender
	 * @param 	emailAddress: The email adress of the sender
	 * @param 	emailText: The message within an email
	 */
	protected function filterEMailContact($p){
		include_once('default/views/filters/FilterString1000.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterString50.php');
		include_once('default/views/filters/FilterValidEmail.php');
		
		$lang = $this -> lang;
		$fEMail = new FilterValidEmail();
		$fString50 = new FilterString50();
		$fString100 = new FilterString100();
		$fString1000 = new FilterString1000();
		
		if (!isset($p['emailAddress']) || ($fEMail -> filter($p['emailAddress']) == false) || ($fString100 -> isValid($p['emailAddress']) == false)){
			$p['error'] = $lang['ERR_2'];
		}
		else if (!isset($p['emailName'])){
			$p['error'] = $lang['ERR_7'];
		}
		else if (!isset($p['emailText'])){
			$p['error'] = $lang['ERR_8'];
		}
		else{
			$p['emailName'] = $fString50 -> filter($p['emailName']);
			$p['emailText'] = $fString1000 -> filter($p['emailText']);			
		}
		
		return $p;
	}
	
	protected  function logUser($p = null){
		include_once('default/models/default/db_insVisitor.php');
		if (!is_array($p) || ($p == null)){
			$p = array();
		}
		
		$p['url'] = '';
		if (isset($_SERVER['REQUEST_URI'])){			
			$p['url'] = $_SERVER['REQUEST_URI'];
		}
		
		$p['referer'] = '';
		if (isset($_SERVER['HTTP_REFERER'])){
			$p['referer'] = $_SERVER['HTTP_REFERER'];	
		}
		
		$p['userAgent'] = '';
		if (isset($_SERVER['HTTP_USER_AGENT'])){
			$p['userAgent'] = $_SERVER['HTTP_USER_AGENT'];	
		}
		
		//$p['reqParam'] = $this -> getRequest() -> getParams();
		
		$visitorID = db_insVisitor($p);
		if ($visitorID != false){
			$this -> visitorID = $visitorID;
		}
	}
	
	protected function logSystemActivity($p){
		include_once('default/models/default/db_selActivity.php');		
		include_once('default/models/default/db_insSystemLog.php');
		include_once('default/models/default/db_insSystemLog.php');
		
		if (isset($p['activityName']) && isset($p['activityRes']) && is_numeric($this -> visitorID)){
			$activity = db_selActivity(array('activityName' => $p['activityName']));
			
			if (($activity != false) && is_array($activity) && (count($activity) > 0)){
				$activity = $activity[0]; 
				$userID = null;
				if (isset($p['userID'])){
					$userID = $p['userID'];
				}
				
				$systemLogData = null;
				if (isset($p['systemLogData'])){
					$systemLogData = $p['systemLogData'];
				}
				db_insSystemLog(array('activityID' => $activity['activityID']
									, 'activityRes' => $p['activityRes']
									, 'visitorID' => $this -> visitorID
									, 'systemLogData' => $systemLogData								
									, 'userID' => $userID 
									));
			}
		}		
	}
	/*
	 * Send an email to advertisement offerer
	 * */
	protected function sendSellerMail($p){
		$system = $this -> system;
		$lang = $this -> lang;
		$emailMessage = $lang['TXT_239'];
		$return = false;
		
		if (!is_array($p)){}
		elseif(!isset($p['USER_VNNAME'])){}
		elseif(!isset($p['USER_ADS_LINK'])){}
		elseif(!isset($p['CONTACT_NAME'])){}
		elseif(!isset($p['MESSAGE'])){}
		elseif(!isset($p['EMAIL_SENDER'])){}
		elseif(!isset($p['EMAIL_RECEIVER'])){}
		elseif(!isset($p['EMAIL_FROM'])){}
		elseif(!isset($p['EMAIL_REPLYTO'])){}
		else{
			$emailMessage = str_ireplace('{-USER_VNNAME-}', $p['USER_VNNAME'], $emailMessage);
			$emailMessage = str_ireplace('{-USER_ADS_LINK-}', $p['USER_ADS_LINK'], $emailMessage);
			$emailMessage = str_ireplace('{-CONTACT_NAME-}', $p['CONTACT_NAME'], $emailMessage);
			$emailMessage = str_ireplace('{-MESSAGE-}', $p['MESSAGE'], $emailMessage);
			$emailMessage = str_ireplace('{-CONTACT_EMAIL-}', $p['EMAIL_REPLYTO'], $emailMessage);
			$emailMessage = str_ireplace('{-SENDER_NAME-}', $system['sysSiteName'], $emailMessage);
			
			$return = System_Properties::sendEmail(array('EMAIL_SENDER' => $p['EMAIL_SENDER']
											, 'EMAIL_RECEIVER' => $p['EMAIL_RECEIVER']
											, 'EMAIL_MESSAGE' => $emailMessage 
											, 'EMAIL_SUBJECT' => $lang['TXT_240']
											, 'EMAIL_FROM' => $p['EMAIL_FROM']
											, 'EMAIL_REPLYTO' => $p['EMAIL_REPLYTO']
											));
		}
		return $return;
	} 
	
	/*Log an entry for advertisement recommendations*/
	protected function logAdsRecom($p = null){
		include_once('default/models/default/db_selAdsRecom.php');
		include_once('default/models/default/db_insAdsRecom.php');
		
		if (!is_array($p)){
			$p = array();
		}		
		
		
		if (is_array($p)){
			if (!isset($p['vID2']) || !is_numeric($p['vID2'])){}
			elseif(!isset($p['vType'])){}
			else{
				
				if (!is_array($this -> adsRecomNS -> adsRecom)){
					$this -> adsRecomNS -> adsRecom = array();
				}
				
				if(isset($this -> adsRecomNS -> adsRecom[$p['vType']])){
				 	$p['vID1'] = $this -> adsRecomNS -> adsRecom[$p['vType']];
				
					$adsRecom = db_selAdsRecom(array('vID1' => $p['vID1']
													, 'vID2' => $p['vID2']
													, 'vType' => $p['vType']
													, 'timestamL' => ( time() - System_Properties::DELTA_ADS_RECOM )
													, 'ip' => System_Properties::getIP()
													//, 'print' => true
												));
					if ( ($adsRecom == false) || (is_array($adsRecom) && (count($adsRecom) <= 0)) ){
						if ($p['vID1'] != $p['vID2']){						
							db_insAdsRecom(array('vID1' => $p['vID1']
												, 'vID2' => $p['vID2']
												, 'vType' => $p['vType']
												, 'ip' => System_Properties::getIP()
												//, 'print' => true
											));
						}
					}
				}
				$this -> adsRecomNS -> adsRecom[$p['vType']] = $p['vID2'];
			}
		}
	}
}
?>