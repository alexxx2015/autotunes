<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the Bike controller
 *********************************************************************************/
require_once('classes/AbstractController.php');
include_once('securimage/securimage.php');
class BikeController extends AbstractController{
	
	private $bikeNS;
	private $actParam;
	
	public function preDispatch(){		
		parent::preDispatch();

		//Check if bike market is active or not
		if(!isset($this -> system['sysBikeMarket']) || ($this -> system['sysBikeMarket'] != 1)){
			$this -> _forward('index','index','default');
		}
		
		$action = $this -> getRequest() -> getActionName();
		if((($action == 'insert')
			|| ($action == 'aful')
			|| ($action == 'agfe')
			|| ($action == 'mybikedetail'))
			&& ($this->userNS->userLogged != true)){
			$this -> _redirect('/member/login');				
		}
		
		
		$req = $this -> getRequest();
		$action = $req -> getActionName();
	//Check Authority for "BIKE_EDIT" action
		/*if(($action == 'detail') && ($req -> __isset('bikeSafe'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		else*/if(($action == 'aful')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		elseif(($action == 'agfe')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EDIT
														)) != true)
				 && (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CREATE
														)) != true)
													) {
			$this -> _forward('index');
		}
		//Check Authority for "BIKE_CREATE" action
		else if(($action == 'insert') 
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_CREATE
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check Authority for "BIKE_EDIT" action
		else if(($action == 'mybikedetail') 
				&& ((System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_EDIT
														)) != true)
														
					|| (System_Properties::checkAuthority(array('userAuth' => $this -> userNS -> userData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_DELETE
														)) != true) )
													){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this -> tmpl;
		$this -> view -> lang = $this -> lang;
		$this -> bikeNS = new Zend_Session_Namespace(System_Properties::BIKE_ADS_NS);
	}
	
	/**
	 * This action controler show a picture 
	 * @param	pid:	this parameter spcify the id of the picture
	 */
	public function picAction(){
		include_once('default/models/default/db_selVPic.php');
		$pid = $this -> getRequest() -> getParam('pid');
		
		$bikePic = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV,
									'vPicID' => $pid));	
		if($bikePic != false){
			$this -> view -> bikePic = $bikePic[0];
		}
	}
	
	//Bookmark an bike advertisement
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
			$bikeID = $p['id'];
			
			//determine page
			$page = 1;
			if (isset($p['p'])){
				$page = $p['p'];
			}
			
			if($userID != null){
				$bookmark = db_selBookmark(array('vehicleType' => System_Properties::BIKE_ABRV
												, 'vehicleID' => $bikeID
												, 'userID' => $userID
												));
												
				if (($bookmark == false) || !is_array($bookmark)){
					db_insBoookmark(array('vehicleType' => System_Properties::BIKE_ABRV
										, 'vehicleID' => $bikeID
										, 'userID' => $userID
										));									
				}
			}
			//$this -> _forward('detail', null, null, array('bikeID'=>$bikeID));
			$this -> _redirect('/bike/'.$bikeID.'/'.$page);
		}else{
			$this -> _forward('search');
		}
	}

	/**
	 * This AJAX method return for a specific bike brand the corresponding bike models
	 * Input parameter:
	 * @param	cid:	this parameter specify the bike id
	 * 
	 * Output parameter:
	 * @param	r:	indicate if a request ist successfully processed (true) or not (false)
	 * @param	bm: compose with the name and id of a bike model orderer by model name
	 * 			@param	bmn:	bike model name
	 * 			@param	bmid:	bike model id
	 */
	public function ajagmAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		include_once ('default/models/bike/db_selBikeBrand.php');
		include_once ('default/models/bike/db_selBikeModel.php');
		
		$req = $this -> getRequest();
		
		$return = array('r' => false, 'bm' => array());
		
		$r_bikeBrandID = $req -> getParam('bid');
		
		$bikeBrand = db_selBikeBrand(array('bikeBrandID' => $r_bikeBrandID));
		
		if (($bikeBrand != false) && is_array($bikeBrand)){
			$bikeModels = db_selBikeModel(array(	'bikeBrandID' => $bikeBrand[0]['bikeBrandID'],
													'orderby' => array(
															array('col' => 'lft')
															, array('col' => 'bikeModelName')
													)
											)
							);
			if (($bikeModels != false) && is_array($bikeModels)){
				$return['r'] = true;
				foreach ($bikeModels as $bikeModel) {
					$bm = array('bmn' => $bikeModel['bikeModelName']
								, 'bmid' => $bikeModel['bikeModelID']
								, 'bml' => $bikeModel['level']);
					array_push($return['bm'], $bm);
				}
			}
		}	
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}	
	
	public function ajasearchAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);		
		$return = array('r' => false);
	
		//Search bike advertisement in database
		$p = $this -> getRequest() -> getParams();
	
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		$bikeSearchParam = $this -> translateAjasearchParamAction($p);//$this -> bikeNS -> bikeSearchParam;
		if (is_array($bikeSearchParam)){
			$p = $bikeSearchParam;
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
		$bikeAds = $this -> searchBikeAds($p);
			
		//Search process successfully passed and found some matches
		if (is_array($bikeAds) && isset($bikeAds['totalAds']) && ($bikeAds['totalAds'] > 0) && isset($bikeAds['bikeAds'])){
			$return['r'] = true;
			//Search process successfully passed and found some matches
			if (is_array($bikeAds) && isset($bikeAds['totalAds']) && ($bikeAds['totalAds'] > 0)
					&& isset($bikeAds['bikeAds']) && is_array($bikeAds['bikeAds'])){
				$return['r'] = true;
				$bikeAds['bikeAds'] = $this->replaceBikeDetailValue($bikeAds['bikeAds']);
				$return['ads'] = $bikeAds;
			}
		}
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
		$this -> getResponse() -> setHeader('Content-type', 'application/json', true);
	}
	
	private function replaceBikeDetailValue($p_bike){
		include_once ('default/models/bike/db_selBikeCat.php');
		$lang = $this -> lang;
		$return = array();
		
		foreach($p_bike as $key => $bike){
			if(isset($lang['TXT_33']) && isset($bike['userAds'])
					&& is_array($lang['TXT_33']) && isset($lang['TXT_33'][$bike['userAds']])){
				$bike['userAds'] = $lang['TXT_33'][$bike['userAds']];
			}
			if(isset($lang['TXT_70']) && isset($bike['bikePriceType'])
					&& is_array($lang['TXT_70']) && isset($lang['TXT_70'][$bike['bikePriceType']])){
				$bike['bikePriceType'] = $lang['TXT_70'][$bike['bikePriceType']];
			}
			if(isset($lang['TXT_74']) && isset($bike['bikePriceCurr'])
					&& is_array($lang['TXT_74']) && isset($lang['TXT_74'][$bike['bikePriceCurr']])){
				$bike['bikePriceCurr'] = $lang['TXT_74'][$bike['bikePriceCurr']];
			}
			if(isset($lang['TXT_75']) && isset($bike['bikeKMType'])
					&& is_array($lang['TXT_75']) && isset($lang['TXT_75'][$bike['bikeKMType']])){
				$bike['bikeKMType'] = $lang['TXT_75'][$bike['bikeKMType']];
			}
			if(isset($lang['TXT_72']) && isset($bike['bikePowerType'])
					&& is_array($lang['TXT_72']) && isset($lang['TXT_72'][$bike['bikePowerType']])){
				$bike['bikePowerType'] = $lang['TXT_72'][$bike['bikePowerType']];
			}
			if(isset($lang['V_SHIFT']) && isset($bike['bikeShift'])
					&& is_array($lang['V_SHIFT']) && isset($lang['V_SHIFT'][$bike['bikeShift']])){
				$bike['bikeShift'] = $lang['V_SHIFT'][$bike['bikeShift']];
			}
			if(isset($lang['BIKE_DOOR']) && isset($bike['bikeDoor'])
					&& is_array($lang['BIKE_DOOR']) && isset($lang['BIKE_DOOR'][$bike['bikeDoor']])){
				$bike['bikeDoor'] = $lang['BIKE_DOOR'][$bike['bikeDoor']]."/".($lang['BIKE_DOOR'][$bike['bikeDoor']]+1);
			}
			if(isset($lang['V_EEK']) && isset($bike['bikeEEK'])
					&& is_array($lang['V_EEK']) && isset($lang['V_EEK'][$bike['bikeEEK']])){
				$bike['bikeEEK'] = $lang['V_EEK'][$bike['bikeEEK']];
			}
			if(isset($lang['V_STATE']) && isset($bike['bikeState'])
					&& is_array($lang['V_STATE']) && isset($lang['V_STATE'][$bike['bikeState']])){
				$bike['bikeState'] = $lang['V_STATE'][$bike['bikeState']];
			}
			if(isset($lang['V_CAT']) && is_array($lang['V_CAT']) && isset($bike['bikeCat'])){
				$bikeCat = db_selBikeCat(array('bikeCatID' => $bike['bikeCat']));
				if(($bikeCat != false) && (count($bikeCat) > 0)){
					$bikeCat = $bikeCat[0];
					if(isset($lang['V_CAT'][$bikeCat['vcatID']])){
						$bike['bikeCat'] = $lang['V_CAT'][$bikeCat['vcatID']];
					}else{
						$bike['bikeCat'] = null;
					}
				}else{
					$bike['bikeCat'] = null;
				}
			}
			if(isset($lang['V_FUEL']) && isset($bike['bikeFuel'])
					&& is_array($lang['V_FUEL']) && isset($lang['V_FUEL'][$bike['bikeFuel']])){
				$bike['bikeFuel'] = $lang['V_FUEL'][$bike['bikeFuel']];
			}
			if(isset($lang['V_CLR']) && isset($bike['bikeClr'])
					&& is_array($lang['V_CLR']) && isset($lang['V_CLR'][$bike['bikeClr']])){
				$bike['bikeClr'] = $lang['V_CLR'][$bike['bikeClr']];
			}
			if(isset($lang['V_EMISSION_NORM']) && isset($bike['bikeEmissionNorm'])
					&& is_array($lang['V_EMISSION_NORM']) && isset($lang['V_EMISSION_NORM'][$bike['bikeEmissionNorm']])){
				$bike['bikeEmissionNorm'] = $lang['V_EMISSION_NORM'][$bike['bikeEmissionNorm']];
			}
			if(isset($lang['V_ECOLOGIC_TAG']) && isset($bike['bikeEcologicTag'])
					&& is_array($lang['V_ECOLOGIC_TAG']) && isset($lang['V_ECOLOGIC_TAG'][$bike['bikeEcologicTag']])){
				if($bike['bikeEcologicTag'] === '0'){
					$bike['bikeEcologicTag'] = -1;
				}
				else{
					$bike['bikeEcologicTag'] = $lang['V_ECOLOGIC_TAG'][$bike['bikeEcologicTag']];
				}
			}
			if(isset($lang['V_KLIMA']) && isset($bike['bikeKlima'])
					&& is_array($lang['V_KLIMA']) && isset($lang['V_KLIMA'][$bike['bikeKlima']])){
				$bike['bikeKlima'] = $lang['V_KLIMA'][$bike['bikeKlima']];
			}
			
			if(isset($bike['bikeExt']) && is_array($bike['bikeExt'])){
				$bikeExt = array();
				foreach($bike['bikeExt'] as $key => $kVal){
					if(is_array($kVal) && isset($kVal['vextID'])){
						if(isset($lang['V_EXTRA']) && is_array($lang['V_EXTRA'])
								&& isset($lang['V_EXTRA'][$kVal['vextID']])){
							array_push($bikeExt, $lang['V_EXTRA'][$kVal['vextID']]);
						}
					}
				}
				$bike['bikeExt'] = $bikeExt;
			}
			
			if(isset($bike['bikePics']) && is_array($bike['bikePics'])){
				$bikePics = array();
				foreach($bike['bikePics'] as $key => $kVal){
					if(isset($kVal['vPicID']) && isset($kVal['vID'])){
						$picFile = '.'.System_Properties::PIC_PATH.'/'.System_Properties::BIKE_ABRV.'_'.$kVal['vID'].'_'.$kVal['vPicID'].'.jpeg';
						if (file_exists($picFile)){
							array_push($bikePics, array('vPicID' => $kVal['vPicID'], 'vID' => $kVal['vID']));
						}
					}
				}
				$bike['bikePics'] = $bikePics;
			}
			
			unset($bike['ip']);
			unset($bike['timestam']);
			unset($bike['erased']);
			unset($bike['userID']);
			unset($bike['hitCounter']);
			unset($bike['extLink']);
			unset($bike['userLinkAds']);
			
			array_push($return, $bike);
		}
		return $return;
	}
	

	public function ajagetdetailAction(){
		include_once('default/models/bike/db_selBikeAd.php');
		include_once('default/models/bike/db_selBike2Ext.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('Zend/Json.php');
	
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$return = array('r' => false);
		$p = $this -> getRequest() -> getParams();
	
		if(isset($p['bid'])){
			$bikeID = $p['bid'];
			$bike = db_selBikeAd(array('bikeID' => $bikeID));
			if ($bike != false){
				$bike = $bike[0];
	
				$bikeExtra = db_selBike2Ext(array('bikeID' => $bike['bikeID']));
				$bike['bikeExt'] = $bikeExtra;
	
				$bikePic = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV
						, 'vID' => $bike['bikeID']
						, 'vPicTMP' => '0'));
				$bike['bikePics'] = $bikePic;
	
				$bike = $this -> replaceBikeDetailValue(array($bike));
				if(is_array($bike) && isset($bike[0])){
					$bike = $bike[0];
				}
	
				$return['r'] = true;
				$return['v'] = $bike;
			}
		}
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
		$this -> getResponse() -> setHeader('Content-type', 'application/json', true);
	}

	
	/**
	 * This is an ajax complete function which save an uploaded photo
	 * Input paramter: 
	 * @param	bid:	this parameter specify the idetifier of the bike advertisement 
	 * 
	 * Output parameter: 
	 * @param	r:	indicate if a request is successfully processed (true) or not (false)
	 * @param	tu: this parameter specify the URL to the uploaded picture
	 * @param	h: 	this parameter specify the hash code of modified picture
	 */
	public function ajafulAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once('default/models/bike/db_selBikeAd.php');
		
		//Initialize returning parameter
		$return = array('r' => false);
		
		if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)
			&& isset($this -> userNS -> userData) && is_array($this -> userNS -> userData)){
			$userDetails = $this -> userNS -> userData;							
			
			//get all parameters
			$p = $this -> getRequest() -> getParams();			
			if (isset($p['bid']) && is_numeric($p['bid']) && isset($p['t']) 
				&& is_array($userDetails) && isset($userDetails['userID'])){
				$bikeID = $p['bid'];
				$bikeDetails = db_selBikeAd(array('bikeID' => $bikeID));
				if (($bikeDetails != false) && ($bikeDetails[0]['userID'] == $userDetails['userID'])){
					$uploadRes = $this -> uploadPhoto(array('bikeID' => $bikeDetails[0]['bikeID']
															, 'bikeDetail' => $bikeDetails
															)
														);
					if (($uploadRes != false) && is_array($uploadRes) 
						&& isset($uploadRes['r']) && ($uploadRes['r'] == true) ){
						if (!isset($this -> bikeNS -> bikePhoto) || !is_array($this -> bikeNS -> bikePhoto)){
							$this -> bikeNS -> bikePhoto = array();
						}
						$this -> bikeNS -> bikePhoto[$uploadRes['bikePhoto']['vPicID']] = $uploadRes['bikePhoto']; 
						
						$return['r'] = true;		
						$return['h'] = $uploadRes['bikePhoto']['vPicID'];
						$return['bid'] = $bikeID;
						$return['t'] = $p['t'];
						$return['tu'] = $uploadRes['bikePhoto']['destURI'];	
						
						//Log system activity, photo upload successful
						/*
						$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_PHOTO_UPLOAD
														,'activityRes' => 1
														, 'systemLogData' => $p
														, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
														));		*/
					}else{						
						//Log system activity, photo upload unsuccessful
						/*
						$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_PHOTO_UPLOAD
														,'activityRes' => 0
														, 'systemLogData' => $p
														, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
														));*/
					}
				}
			}else{
				//Log system activity, photo upload unsuccessful
				/*
				$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_PHOTO_UPLOAD
												,'activityRes' => 0
												, 'systemLogData' => $p
												, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
												));
												*/
			}
		}else{
			//Log system activity
			/*
			$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_PHOTO_UPLOAD
											,'activityRes' => 0
											, 'systemLogData' => array('userLogged'=>false
																	, 'userData' => (isset($this -> userNS -> userData)?$this -> userNS -> userData:'')
																	)
											, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
											));*/
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
		include_once('default/models/bike/db_selBikeAd.php');
		include_once('default/models/default/db_insVPic.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		
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
		include_once('default/models/bike/db_selBikeAd.php');
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
							
					$newBikePhoto = array();
					$bikePhoto = $this -> bikeNS -> bikePhoto;
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
					$this -> bikeNS -> bikePhoto = $newBikePhoto;
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
	
	/**
	 * This functin return the next bike search results
	 * Input parameter
	 * @param	rs:		this parameter contains the result set
	 * @param 	ars:	this parameter contains the actual results entries
	 * 
	 * Output param
	 * @param	r:		this parameter specify the execution success of a request
	 * @param 	ca:		In the successful case this parameter contains the bike advertisments
	 */
	public function ajagnsrAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);		
		$return = array('r' => false);
		
	
		//Search bike advertisement in database			
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
		
		$bikeSearchParam = $this -> bikeNS -> bikeSearchParam;
		if (is_array($bikeSearchParam)){
			$p = $bikeSearchParam;			
			//Set old page
			//$p['page'] = $page;
		}
		
		$p['limit'] = array('start' => $actResSet, 'num' => $resultSet);
		$bikeAds = $this -> searchBikeAds($p);
		
		//Search process successfully passed and found some matches
		if (is_array($bikeAds) && isset($bikeAds['bikeAds'])){
			$bikeAds = $bikeAds['bikeAds'];			
			$return['r'] = true;
			$return['ca'] = array();
			foreach ($bikeAds AS $ca){
				if ($ca['bikeModelName'] == null){
					$ca['bikeModelName'] = '';
				}
				array_push($return['ca'], $ca);
			}
		}				
		
		
		include_once('Zend/Json.php');
		$this -> getResponse() -> setBody(Zend_Json::encode($return));
	}
	
	public function detailAction(){
		include_once('default/views/filters/FilterMySQLInt.php');
		include_once('default/models/bike/db_selBikeAd.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/bike/db_selBike2Ext.php');
		include_once('default/models/default/db_insEMail.php');
		include_once('default/models/default/db_selEMail.php');
		include_once('default/models/default/db_selBookmark.php');
		
		include_once('default/models/bike/db_updBikeAds.php');
		
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
		
		$bikeID = $req -> getParam('bikeID');
		if ($intFilter -> isValid($bikeID) == false){
			$this -> view -> error = $lang['ERR_5'];
			$this -> search1Action();
		}
		else{
			$bookmark = false;
			$bike = db_selBikeAd(array('bikeID' => $bikeID));
			if (($bike != false) && is_array($bike)){
				$this -> loadBikeCat();
				$bike = $bike[0];
				
				db_updBikeAds(array(System_Properties::SQL_SET => array('incHitCounter' => '1')
									, System_Properties::SQL_WHERE => array('bikeID' => $bike['bikeID'])
									));
					
				//Add bookmark functionality
				if (isset($this -> userNS -> userLogged) && ($this -> userNS -> userLogged == true)){
					$db_bookmark = db_selBookmark(array('vehicleType' => System_Properties::BIKE_ABRV
														, 'vehicleID' => $bike['bikeID']
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
						$email = db_selEMail(array('vID' => $bike['bikeID']
													, 'vType' => System_Properties::BIKE_ABRV
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
															, 'receiverEMailAddress' => $bike['userEMail']
															, 'receiverID' => $bike['userID']
															, 'vID' => $bike['bikeID']
															, 'vType' => System_Properties::BIKE_ABRV
														)
													);
							if ($emailSend != false){
								$pHelp['USER_VNNAME'] = $bike['userNName'].' '.$bike['userVName'];
								$pHelp['USER_ADS_LINK'] = '<a href="http://www.autotunes.de/bike/'.$bike['bikeID'].'">'.(isset($bike['bikeModelName']) ? $bike['bikeBrandName'].' '.$bike['bikeModelName']: $bike['bikeBrandName']).'</a>';
								$pHelp['CONTACT_NAME'] = $pHelp['emailName'];
								$pHelp['MESSAGE'] = $pHelp['emailText'];
								$pHelp['EMAIL_SENDER'] = System_Properties::$SYS_INFO_EMAIL;
								$pHelp['EMAIL_RECEIVER'] = $bike['userEMail']; 
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
				$this -> logAdsRecom(array('vType' => System_Properties::BIKE_ABRV
										, 'vID2' => $bike['bikeID']));
				
				$bike['lastPicPID'] = $lastPicPID;
				$bike['page'] = $page;
				$bike['next'] = $next;
				$bike['prev'] = $prev;
				
				$bikeExtra = db_selBike2Ext(array('bikeID' => $bike['bikeID']));
				$bike['bikeExt'] = $bikeExtra;
				
				$bikePic = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV
											, 'vID' => $bike['bikeID']
											, 'vPicTMP' => '0'
											)
										);		
				$bike['bikePics'] = $bikePic;
				$this -> view -> bike = $bike;
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
	
	/*******************************************
	 * Action controller
	 *******************************************/	
	public function indexAction(){
		$this -> loadBikeModelsBrands();
		$this -> _forward('search');
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
												, 'orderby' => array(
													array('col' => 'lft')
													, array('col'=>'bikeModelName'))
											));
		}
		
		$this -> view -> bikeBrand = $bikeBrand;
		$this -> view -> bikeModel = $bikeModel;
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
		include_once ('default/models/default/db_selExtra.php');
		$bikeExt = db_selExtra(array('vType' => 'c'));
		if($bikeExt != false){
			$this -> view -> bikeExt = $bikeExt;
		}
		*/
		include_once ('default/models/bike/db_selBikeExt.php');
		$bikeExt = db_selBikeExt();
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
		
		include_once ('default/models/bike/db_selBikeBrand.php');
		include_once ('default/models/bike/db_selBikeModel.php');
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
		//$bikeBrand = db_selBikeBrand(array('bikeBrandID'=>$p['bikeBrand']));	
		
		//Check bikeBrand
		if (!isset($p['bikeBrand'])){
				$p['error'] = $lang['ERR_2'];
		}
		elseif (($bikeBrand = db_selBikeBrand(array('bikeBrandID'=>$p['bikeBrand']))) == false){
			$p['error'] = $lang['ERR_2'];
		}
		/*Check model if necessary
		else if (isset($p['bikeModel']) && ($p['bikeModel'] != -1) 
				&& (db_selBikeModel(array('bikeBrandID' => $p['bikeBrand'], 'bikeModelID' => $p['bikeModel'])) == false)){
					echo "KK";
			$p['error'] = $lang['ERR_2'];			
		}
		*/
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
				if (!isset($lang['TXT_70'][$p['bikePriceType']])){
					$p['bikePriceType'] = 0;
				}
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
			
			if(!isset($p['bikePowerType']) || ($p['bikePowerType'] == null)){
				$p['bikePowerType'] = 0;
			}else{
				$p['bikePowerType'] = $fMInt->filter($p['bikePowerType']);
				if (!isset($lang['TXT_72'][$p['bikePowerType']])){
					$p['bikePowerType'] = 0;
				}
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
		
			
			if (isset($p['bikeUseIn'])){
				$p['bikeUseIn'] = $fString50->filter($p['bikeUseIn']);
			}
			else{
				$p['bikeUseIn'] = '';
			}
			
			if (isset($p['bikeUseOut'])){
				$p['bikeUseOut'] = $fString50->filter($p['bikeUseOut']);	
			}else{
				$p['bikeUseOut'] = '';
			}
			
			if (isset($p['bikeCO2'])){
				$p['bikeCO2'] = $fString50->filter($p['bikeCO2']);
			}else{
				$p['bikeCO2'] = '';
			}
			
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
			
			//check bikeLocPLZ
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
				include_once ('default/models/bike/db_selBikeExt.php');			
				$bikeExt = db_selBikeExt(array('vextID'=>$p['bikeExt']
										));
			}
			$p['bikeExtDB'] = $bikeExt;
		}
		return $p;
	}
	
	public function mybikedetailAction(){
		include_once('default/models/bike/db_selBikeAd.php');
		
		include_once('default/models/bike/db_selBike2Ext.php');
		include_once('default/models/bike/db_delBike2Ext.php');
		include_once('default/models/bike/db_insBike2Ext.php');
		
		include_once('default/models/bike/db_updBikeAds.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_updVPic.php');
		
		$lang = $this -> lang;
		
		$p = $this -> getRequest() -> getParams();
		if (isset($p['id'])){
			$bikeID = $p['id'];
			$bikeDetails = db_selBikeAd(array('bikeID'=>$bikeID
											, 'userID' => $this -> userNS -> userData['userID']
										));
			if( ($bikeDetails != false) && is_array($bikeDetails) && (count($bikeDetails) > 0)){				
				$bikeDetails = $bikeDetails[0];
				$this -> loadBikeModelsBrands(array('bikeBrand' => $bikeDetails['bikeBrandID']));
				$this -> loadBikeExt();
				$this -> loadBikeCat();
				
				//Safe change
				if (isset($p['bikeSafe'])){
					$p = $this -> filterRecvParam($p);
					if (!isset($p['error'])){
						$p['bikeBrandID'] = $p['bikeBrand'];
						$updBikeAds = db_updBikeAds(array(System_Properties::SQL_SET => $p
														, System_Properties::SQL_WHERE => array('bikeID'=>$bikeDetails['bikeID'])
														) 
													);	
						if ($updBikeAds != false){
							$bikeDetails = db_selBikeAd(array('bikeID'=>$bikeID));
							if( ($bikeDetails != false) && is_array($bikeDetails) && (count($bikeDetails) > 0)){				
								$bikeDetails = $bikeDetails[0];
							}
						}
								
						//update bike extra
						db_delBike2Ext(array('bikeID' => $bikeID));
						if(isset($p['bikeExtDB']) && is_array($p['bikeExtDB'])){
							foreach ($p['bikeExtDB'] as $key=>$kVal){
								db_insBike2Ext(array('bikeID'=>$bikeID
													, 'bikeExtID'=>$kVal['bikeExtID']
													));
							}
						}
								
						if (isset($this -> bikeNS -> bikePhoto) && is_array($this -> bikeNS -> bikePhoto) ){								
							
							$vPicID = array();
							foreach ($this -> bikeNS -> bikePhoto as $key => $kVal){
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
						}
						$this -> view -> info = $lang['INFO_6'];
						/*}else{
							$this -> view -> error = $lang['ERR_37'];
						}*/
					}else{
						$this -> view -> error = $p['error'];
					}
				}
				//Delete bike advertisement 
				elseif (isset($p['bikeDel'])){
					$bikeAd = db_updBikeAds(array(System_Properties::SQL_WHERE => array('bikeID' => $bikeDetails['bikeID'])
												, System_Properties::SQL_SET => array('erased' => 1)
												)
											);
					if ($bikeAd != false){
						$this -> view -> info = $lang['INFO_7'];
						$this -> _forward('mybikeads', 'member');
					}					
				}
				
				//Load bike extra
				$bikeExt = db_selBike2Ext(array('bikeID' => $bikeDetails['bikeID']));
				if (($bikeExt != false) && is_array($bikeExt) && (count($bikeExt) > 0)){
					$bikeDetails['bikeExt'] = array();
					foreach ($bikeExt as $key => $val){
						array_push($bikeDetails['bikeExt'], $val['vextID']);
					}					
				}
				
				$bikePhoto = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV,
												'vID' => $bikeDetails['bikeID']));
				if (($bikePhoto != false) && is_array($bikePhoto) && (count($bikePhoto) > 0)){
					$bikeDetails['bikePhoto'] = array();
					foreach($bikePhoto as $key => $kVal){
						$bikeDetails['bikePhoto'][$kVal['vPicID']] = $kVal;
					}
					$bikePhoto = $bikeDetails['bikePhoto'];
				}
				
				$this -> view -> bike = $bikeDetails;
				$this -> bikeNS -> bikePhoto = $bikePhoto;
			} else{
				$this -> _forward('mybikeads','member');
			}
		} else{
			$this -> _forward('mybikeads','member');
		}
	}
	
	/**
	 * This action handle the searching process
	 */
	public function searchAction(){
		$req = $this -> getRequest();
		if ($req -> __isset('search2')){
			$this -> bikeNS -> bikeSearchParam = $req -> getParams();			
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
		$this -> loadBikeExt();
		$this -> loadBikeCat();
		
		$req = $this -> getRequest();
		if ($req -> __isset('cp') && isset($this -> bikeNS -> bikeSearchParam) && is_array($this -> bikeNS -> bikeSearchParam)){
			$this -> view -> searchParam = $this -> bikeNS -> bikeSearchParam;
			$this -> loadBikeModelsBrands($this -> bikeNS -> bikeSearchParam);
		}
		elseif (isset($this -> view -> searchParam)){
			$this -> loadBikeModelsBrands($this -> view -> searchParam);
		}
		else{
			$this -> loadBikeModelsBrands();
		}
		$this -> render('search1');
	}
	
	private function search2Action(){
		
		//Search bike advertisement in database			
		$req = $this -> getRequest();
		$p = $req -> getParams();
		
		$page = 1;
		if (isset($p['page'])){
			$page = $p['page'];
		}
		
		$bikeSearchParam = $this -> bikeNS -> bikeSearchParam;
		if (is_array($bikeSearchParam)){
			$p = $bikeSearchParam;			
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
		$bikeAds = $this -> searchBikeAds($p);
		
		$this -> view -> searchParam = $this -> bikeNS -> bikeSearchParam;
		
		//Search process successfully passed and found some matches
		if (is_array($bikeAds) && (count($bikeAds) > 0)){
			$this -> loadBikeModelsBrands($this -> bikeNS -> bikeSearchParam);
						
			$bikeAds['numAds'] = System_Properties::NUM_ADS;
			$bikeAds['actPage'] = $page;
			$this -> view -> bikeAds = $bikeAds;		
			$this -> render('search2');
		}		
		else{
			$lang = $this -> lang;
			$this -> view -> error = $lang['ERR_4'];
			$this -> search1Action();
		}
	}
	
	private function searchBikeAds($p){
		include_once('default/models/bike/db_selBikeAds.php');
		include_once('default/models/bike/db_selBikeExt.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_selPLZ.php');
		
		include_once('default/models/bike/db_selBikeModel.php');
		
		if (isset($p['bikeExt'])){
			$bikeExt = db_selBikeExt(array('vextID'=>$p['bikeExt']));
			$p['bikeExtDB'] = $bikeExt;
		}
		
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
				case '3': $p['orderby'] = array('col' => 'timestam');
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
		
		if (isset($p['bikePLZ'])){
			$bikePLZ = db_selPLZ(array('postal_code' => $p['bikePLZ']));
			if (($bikePLZ != false) && is_array($bikePLZ)){
				$p['bikePLZ'] = $bikePLZ;				
			}
		}	
		
		//bikeModel
		if (is_array($bikeModel) && (count($bikeModel) > 0)){
			$bikeBrandWithModel = array();
			$bikeModelChilds = array();				
			foreach($bikeModel as $key => $kVal){
				//fetch all childs  
				if(isset($kVal['lft']) && isset($kVal['rgt']) && (($kVal['rgt'] - $kVal['lft']) < 2 )){						
					array_push($bikeModelChilds, $kVal);
				}					
				array_push($bikeBrandWithModel, $kVal['bikeBrandID']);
			}
			
			//delete all parent model
			foreach($bikeModel as $key => $kVal){
				$add = true;
				if(count($bikeModelChilds) > 0){
					foreach($bikeModelChilds as $key2 => $kVal2){
						if(($kVal2['lft'] > $kVal['lft']) && ($kVal2['rgt'] < $kVal['rgt'])){
							$add = false;
							break;
						}
					}
				}						
				if($add == true){
					//select all childs
					$bikeModelDB = db_selbikeModel(array('lftBE' => $kVal['lft']
													, 'rgtLE' => $kVal['rgt']
													));
					if(is_array($bikeModelDB) && ($bikeModelDB != false) && (count($bikeModelDB) > 0)){
						foreach($bikeModelDB as $key2 => $kVal2){					
							array_push($bikeModelChilds, $kVal2);
						}
					}
				}
			}
			
			if(count($bikeModelChilds) > 0){
				$bikeModel = array();
				foreach($bikeModelChilds as $key => $kVal){
					array_push($bikeModel, $kVal['bikeModelID']);
				}
				$p['bikeModel'] = $bikeModel;
			}
			
			//Delete all brands without model specification
			$bikeBrand2 = array();
			foreach ($p['bikeBrand'] as $key => $kVal){
				if (!in_array($kVal, $bikeBrandWithModel)){
					array_push($bikeBrand2, $kVal);
				}	
			}	

			$p['bikeBrand'] = $bikeBrand2;
		}
			
		//$p['print'] = true;
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
					$bikeAd['bikePics'] = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV
															, 'vID' => $bikeAd['bikeID']
															, 'vPicTMP' => '0'
														)
													);					
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
	 * This is the main action for invoking the insertion process
	 */
	public function insertAction(){
		$req = $this -> getRequest();
		
		//The user has filled the form and want now to insert the advertisement
		if ($req -> __isset('ins2') && (!isset($this -> bikeNS -> bikeInsert) || ($this -> bikeNS -> bikeInsert == false))){
			$req -> setParam('ins2',null);
			$this -> insert2Action();
		}
		
		//User confirm the correctness of the advertisment
		else if ($req -> __isset('ins3') && (!isset($this -> bikeNS -> bikeInsert) || ($this -> bikeNS -> bikeInsert == false))){
			$this -> insert3Action();
		}
		
		//Advertisment is commited to database,
		//and the user want to delete some photos from the advertisement
		else if(isset($this -> bikeNS -> bikeInsert) 
					&& ($this -> bikeNS -> bikeInsert == true) 
					&& isset($this -> bikeNS -> bikeAds)
					&& ($req -> __isset('dp'))
					&& ($req -> __isset('i'))){
			$this -> insert6Action();			
		}
		
		//Advertisment is commited to database,
		//and the user want to add some photos to the advertisement
		else if (isset($this -> bikeNS -> bikeInsert) 
					&& ($this -> bikeNS -> bikeInsert == true) 
					&& isset($this -> bikeNS -> bikeAds)
					&& ($req -> __isset('photoUpload'))){
			$this -> insert5Action();
		}
		
		//Advertisment is committed to database and the user safe all fotos.
		else if (isset($this -> bikeNS -> bikeInsert)
					&& ($this -> bikeNS -> bikeInsert == true)
					&& isset($this -> bikeNS -> bikeAds)
					&& ($req -> __isset('safeFoto'))){
			$this -> insert7Action();
		}
		
		//Advertisment is commited to database, so show the inserted advertisement
		else if (isset($this -> bikeNS -> bikeInsert) 
					&& ($this -> bikeNS -> bikeInsert == true) 
					&& isset($this -> bikeNS -> bikeAds)){
			$this -> insert4Action();
		}
		
		else {
			//Set bikeInsert to false
			$this -> bikeNS -> bikeInsert = false;
			$this -> bikeNS -> bikePhoto = false;
			$this -> insert1Action();
		}
		
	}
	
	/**
	 * This action is invoked for inserting a bike advertisment
	 */
	private function insert1Action(){		
		$this -> resetAction();
		$this -> loadBikeModelsBrands();
		$this -> loadBikeExt();
		$this -> loadBikeCat();
		if (isset($this -> actParam) && ($this -> actParam != null)){
			$this -> loadBikeModelsBrands(array('bikeBrand' => $this -> actParam['bikeBrand']));
			$this -> view -> bike = $this -> actParam;
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
			//$this -> loadBikeModelsBrands();
			$this -> view -> error = $p['error'];
			//$this -> render('insert1');
			//$this -> _forward('insert', null, null, array('error' => $error));
			$this -> insert1Action();
		}		
		else{			
			$this -> loadBikeCat();
			//Set session namespace
			$this -> bikeNS -> bikeAds = $p;			
			$this -> view -> bike = $p;
			//$this -> view -> bikePhoto = $this -> bikeNS -> bikePhoto;
			
			$this -> render('insert2');	
		}
	}
	
	/**
	 * This function do the main job. It check the input parameter and insert the data on DB
	 */
	private function insert3Action(){
		if(isset($this -> bikeNS -> bikeAds)){
			include_once ('default/models/bike/db_insBikeAds.php');			
			include_once ('default/models/bike/db_insBike2Ext.php');
			include_once ('default/models/bike/db_selBikeExt.php');
	
			$p = $this -> bikeNS -> bikeAds;
			$this -> actParam = $p;
			$lang = $this -> lang;
			$user = $this -> userNS -> userData;
			
			//Filter the bike advertisement
			$p = $this -> filterRecvParam($p);
			
			if (!isset($p['error'])){
				//Advertising is successful
				$p['userID'] = $user['userID'];
				$bikeID = db_insBikeAds($p);
				if($bikeID != false){
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
					$this -> bikeNS -> bikeAds = $p;
					
					//Set bikeInsert to true because the bike advertisment is successfully inserted.
					$this -> bikeNS -> bikeInsert = true;	
						
					$this -> view -> bike = $p;	
					//$this -> view -> bikePhoto = $this -> bikeNS -> bikePhoto;
					
					//Log system activity, bike successful insert
					/* 
					$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_CREATE
													, 'activityRes' => 1
													, 'systemLogData' => $p
													, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
													));*/
					$this -> render('insert3');
				}
				//Forward only if an error occurs
				else{
					if (!isset($p['error'])){
						$p['error'] = $lang['ERR_2'];
					}
					//Log system activity, bike unseccussful insert
					/*
					$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_CREATE
													, 'activityRes' => 0
													, 'systemLogData' => $p
													, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
													));*/
					
					//$this -> loadBikeModelsBrands();
					$this -> view -> error = $p['error'];
					$this -> insert1Action();
					//$this -> render('insert1');
					//$this -> _forward('insert', null, null, array('error' => $error));
				}
			}else{
				//Log system activity, bike unseccussful insert
				/*
				$this -> logSystemActivity(array('activityName' => System_Activity::BIKE_CREATE
												, 'activityRes' => 0
												, 'systemLogData' => $p
												, 'userID' => (isset($this -> userNS -> userData['userID']) ? $this -> userNS -> userData['userID']:'')
												));
												*/
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
		include_once ('default/models/default/db_selVPic.php');
		$bike = $this -> bikeNS -> bikeAds;
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
				$this -> bikeNS -> bikePhoto = $bikePhotoNew;
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
		$bike['bikePhoto'] = $this -> bikeNS -> bikePhoto;		
		$this -> view -> bike = $bike;
		$this -> render('insert3');			
	}
	
	/**
	 * Processed if the user upload a photo
	 */
	private function insert5Action(){
		include_once('default/models/bike/db_selBikeAd.php');	
		$req = $this -> getRequest();
		$userDetails = $this -> userNS -> userData;
		if ($req -> __isset('id') && is_array($userDetails) && isset($userDetails['userID'])){
			$bikeID = $req -> getParam('id');
			$bikeDetails = db_selBikeAd(array('bikeID' => $bikeID));
			if(($bikeDetails != false) && ($bikeDetails[0]['userID'] == $userDetails['userID'])){
				$uploadRes = $this -> uploadPhoto(array('bikeID' => $bikeDetails[0]['bikeID']
														, 'bikeDetail' => $bikeDetails
														)
												);
												
				if (($uploadRes != false) 
					&& is_array($uploadRes)
					&& isset($uploadRes['r'])
					&& ($uploadRes['r'] == true)){
					
					if (!isset($this -> bikeNS -> bikePhoto) || !is_array($this -> bikeNS -> bikePhoto)){
						$this -> bikeNS -> bikePhoto = array();
					}
					$bikeAdsNS = array( 'vID' => $bikeDetails[0]['bikeID']
										, 'vPicID' => $uploadRes['bikePhoto']['hash']
										);
					$this -> bikeNS -> bikePhoto[$uploadRes['bikePhoto']['hash']] = $bikeAdsNS;				
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
		include_once('default/models/bike/db_selBikeAd.php');
		
		$req = $this -> getRequest();
		$p = $this -> getRequest() -> getParams();
		$userDetails = $this -> userNS -> userData;
		$bikeDetails = $this -> bikeNS -> bikeAds;
		if (is_array($userDetails) && isset($userDetails['userID'])
			&& is_array($bikeDetails) && isset($bikeDetails['bikeID'])){
			$p['vPicID'] = null;
			if (isset($p['i'])){
				$p['vPicID'] = $p['i'];
			}
			$bikeDetails = db_selBikeAd(array('bikeID' => $bikeDetails['bikeID']));
			if (($bikeDetails != false) && is_array($bikeDetails) && (count($bikeDetails) > 0)){
				$bikeDetails = $bikeDetails[0];
				$srcFileName = '.'.System_Properties::PIC_PATH.'/'.System_Properties::BIKE_ABRV.'_'.$bikeDetails['bikeID'].'_'.$p['vPicID'].'.jpeg';
				if (file_exists($srcFileName)){
					if (unlink($srcFileName) == true){
						$newBikePhoto = array();
						$bikePhoto = $this -> bikeNS -> bikePhoto;
						foreach ($bikePhoto as $key=>$val){
							if ($key != $p['vPicID']){
								$newBikePhoto[$key] = $val;
							}
						}
						$this -> bikeNS -> bikePhoto = $newBikePhoto;
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
			$fileName = '.'.System_Properties::PIC_PATH.'/'.System_Properties::BIKE_ABRV.'_'.$vPic['vID'].'_'.$vPic['vPicID'].'.jpeg';				
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
		include_once('default/models/bike/db_selBikeAd.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_updVPic.php');
		include_once('default/models/default/db_delVPic.php');
		
		if (isset($this -> bikeNS -> bikeAds) && is_array($this -> bikeNS -> bikeAds)			
			&& isset($this -> bikeNS -> bikeAds['bikeID'])){
			$bikeAdsNS = $this -> bikeNS -> bikeAds;
			$bikeDetails = db_selBikeAd(array('bikeID' => $bikeAdsNS['bikeID']));
			if ($bikeDetails != false){
				$bikeDetails = $bikeDetails[0];
				
				if (isset($this -> bikeNS -> bikePhoto) && is_array($this -> bikeNS -> bikePhoto)){								
					foreach ($this -> bikeNS -> bikePhoto as $bikePhoto){
						db_updVPic(array('vPicID' => $bikePhoto['vPicID']
										, 'vPicTMP' => '0'
										));
						/*
						$srcFileURI = '.'.System_Properties::PIC_TMP_PATH.'/'.$bikePhoto['vID'].'_'.$bikePhoto['vPicID'].'.jpeg';
						if (file_exists($srcFileURI)){
							$vPicID = db_insVPic(array(	'vType' => System_Properties::BIKE_ABRV,
											 			'vID' => $bikeDetails['bikeID']));
							if ($vPicID != false){
								$destFileURI = '.'.System_Properties::PIC_PATH.'/'.$bikeDetails['bikeID'].'_'.$vPicID.'.jpeg';
								if (copy($srcFileURI, $destFileURI) == true){
									unlink($srcFileURI);
								}
							}
						}
						*/	
					}
					$notUpdPic = db_selVPic(array('vID' => $bikeDetails['bikeID']
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
				$this -> _redirect('/bike/'.$bikeDetails['bikeID']);
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
		//Start a new bike advertisement
		//Set bikeInsert to false
		/*
		$this -> bikeNS -> bikeInsert = false;
		$this -> bikeNS -> bikePhoto = false;
		$this -> bikeNS -> bikeAds = false;
		*/
		
		
		$this -> bikeNS -> __unset('bikeAds');
		$this -> bikeNS -> __unset('bikePhoto');
		$this -> bikeNS -> __unset('bikeInsert');
	}
	
	public function viewprintAction(){
		include_once('default/models/bike/db_selBikeAd.php');
		include_once('default/models/bike/db_selBike2Ext.php');
		include_once('default/models/default/db_selVPic.php');
		
		$req = $this -> getRequest();
		if ($req -> __isset('id')){
			$id = $req -> getParam('id');
			$bike = db_selBikeAd(array('bikeID' => $id));
			if ($bike != false){
				$this -> loadBikeCat();
				$bike = $bike[0];
				
				$bikeExtra = db_selBike2Ext(array('bikeID' => $bike['bikeID']));
				$bike['bikeExt'] = $bikeExtra;
				
				$bikePic = db_selVPic(array(	'vType' => System_Properties::BIKE_ABRV
											, 'vID' => $bike['bikeID']
											, 'vPicTMP' => '0'));		
				$bike['bikePics'] = $bikePic;
				
				$this -> view -> bike = $bike;
			}	
		}
	}	
	private function translateAjasearchParamAction($p = array()){
		if(isset($p['b'])){
			$p['bikeBrand'] = $p['b'];
		}
		if(isset($p['m'])){
			$p['bikeModel'] = $p['m'];
		}
		if(isset($p['ezyf'])){
			$p['bikeEZF'] = $p['ezyf'];
		}
		if(isset($p['ezyt'])){
			$p['bikeEZT'] = $p['ezyt'];
		}
		if(isset($p['pricef'])){
			$p['bikePriceF'] = $p['pricef'];
		}
		if(isset($p['pricet'])){
			$p['bikePriceT'] = $p['pricet'];
		}
		if(isset($p['powerf'])){
			$p['bikePowerF'] = $p['powerf'];
		}
		if(isset($p['powert'])){
			$p['bikePowerT'] = $p['powert'];
		}
		if(isset($p['kmf'])){
			$p['bikeKMF'] = $p['kmf'];
		}
		if(isset($p['kmt'])){
			$p['bikeKMT'] = $p['kmt'];
		}
		if(isset($p['clima'])){
			$p['bikeKlima'] = $p['clima'];
		}
		if(isset($p['shift'])){
			$p['bikeShift'] = $p['shift'];
		}
		if(isset($p['door'])){
			$p['bikeDoor'] = $p['door'];
		}
		if(isset($p['clr'])){
			$p['bikeClr'] = array($p['clr']);
		}
		if(isset($p['fuel'])){
			$p['bikeFuel'] = array($p['fuel']);
		}
		if(isset($p['emission'])){
			$p['bikeEmissionNorm'] = array($p['emission']);
		}
		if(isset($p['ecotag'])){
			$p['bikeEcologicTag'] = array($p['ecotag']);
		}
		if(isset($p['state'])){
			$p['bikeState'] = array($p['state']);
		}
		if(isset($p['cat'])){
			$p['bikeCat'] = array($p['cat']);
		}
		return $p;
	}
	
}
?>