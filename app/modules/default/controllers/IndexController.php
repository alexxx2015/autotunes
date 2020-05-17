<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for INDEX
 *********************************************************************************/
include_once('classes/AbstractController.php');
include_once('default/models/car/db_selCarAds.php');
class IndexController extends AbstractController{
	
	public function preDispatch(){
		parent::preDispatch();	
		
		$this -> view -> tmpl = $this->tmpl;
		$this -> view -> lang = $this -> lang;	
	}
	
	
	public function indexAction(){
		include_once ('default/models/default/db_selVPic.php');
		$this -> loadCarModelsBrands();
		
		$carAds = db_selCarAds(array('orderby' => array(array('col' => 'random'
															, 'desc' => true
															)
													)
									, 'limit' => array('num'=> 15
													, 'start' => 0													
													)
									//, 'print' => true
									, 'rand' => true
									)
								);
		$carAdsP = array();
		if (is_array($carAds)){
			foreach ($carAds AS $key => $carAd){
				if (is_numeric($key)){
					$carAd['carPics'] = db_selVPic(array(	'vType' => System_Properties::CAR_ABRV
															, 'vID' => $carAd['carID']
															, 'vPicTMP' => '0'
														)
													);					
					array_push($carAdsP, $carAd);
				}				
			}
		}else{
			$carAdsP = $carAds;
		}
		$this -> view -> carAds = $carAdsP;
	}
	
	public function offlineAction(){
	}
	
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
												, 'orderby' => array(array('col'=>'carModelName'))
											));
		}
		
		$this -> view -> carBrand = $carBrand;
		$this -> view -> carModel = $carModel;
	}
	
	public function impAction(){
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/system/db_selSystem.php');
		include_once('default/models/default/db_insEMail.php');
		include_once('default/models/default/db_selEMail.php');
		include_once('securimage/securimage.php');
		
		$p = $this -> getRequest() -> getParams();
		$lang = $this -> lang;
		
		$system = db_selSystem(array('orderby' => array(array('col' => 'timestam'
																, 'desc' => true
																)
														),
										'limit' => array('start' => 0
														, 'num' => 1)
										)
								);
								
		if (is_array($system) && (count($system) > 0)){
			$system = $system[0];
			//User send a contact email to advertiser
			if (isset($p['sendEMail'])){
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
					$email = db_selEMail(array(	'vType' => System_Properties::IMP_ABRV
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
														, 'receiverEMailAddress' => $system['sysEMail']
														, 'vID' => 0
														, 'vType' => System_Properties::IMP_ABRV
													)
												);
						if ($emailSend != false){							
							$pHelp['EMAIL_SENDER'] = System_Properties::$SYS_INFO_EMAIL;
							$pHelp['EMAIL_RECEIVER'] = $system['sysEMail'];
							$pHelp['EMAIL_REPLYTO'] = $pHelp['emailAddress'];
							if ($this -> sendImpEmail($pHelp) != false){							
								$this -> view -> info = $lang['INFO_2'];
							}else{
								$this -> view -> error = $lang['ERR_9'];
							}
						}
						else{
							$this -> view -> error = $lang['ERR_9'];
						}
					}else{
						$this -> view -> error = $lang['ERR_9'].' '.$lang['ERR_52'];
					}
				}
			}
			
			$this -> view -> system = $system;
		}
		$this -> view -> p = $p;
	}
	private function sendImpEmail($p){
		$lang = $this -> lang;
		$emailMessage = $lang['TXT_237'];
		$return = false;
		//CONTACT_NAME
		if (!is_array($p)){}
		elseif(!isset($p['emailName'])){}
		elseif(!isset($p['emailText'])){}
		elseif(!isset($p['EMAIL_SENDER'])){}
		elseif(!isset($p['EMAIL_RECEIVER'])){}
		else{
			$emailMessage = str_ireplace('{-CONTACT_NAME-}', $p['emailName'], $emailMessage);
			$emailMessage = str_ireplace('{-MESSAGE-}', $p['emailText'], $emailMessage);
			$emailMessage = str_ireplace('{-CONTACT_EMAIL-}', $p['EMAIL_SENDER'], $emailMessage);
			
			$emailReplyto = $p['EMAIL_SENDER'];
			if(isset($p['EMAIL_REPLYTO'])){
				$emailReplyto = $p['EMAIL_REPLYTO'];
			}
			
			$return = System_Properties::sendEmail(array('EMAIL_SENDER' => $p['EMAIL_SENDER']
											, 'EMAIL_RECEIVER' => $p['EMAIL_RECEIVER']
											, 'EMAIL_MESSAGE' => $emailMessage 
											, 'EMAIL_SUBJECT' => $lang['TXT_238']
											, 'EMAIL_FROM' => $p['emailName']
											, 'EMAIL_REPLYTO' => $emailReplyto
											));
		}
		return $return;
	}
	
	public function rulesAction(){
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/system/db_selSystem.php');
		$system = db_selSystem(array('orderby' => array(array('col' => 'timestam'
																, 'desc' => true
																)
														),
										'limit' => array('start' => 0
														, 'num' => 1)
										)
								);
								
		if (is_array($system) && (count($system) > 0)){
			$this -> view -> system = $system[0];
		}
		
	}
	
	public function faqAction(){
		include_once (System_Properties::ADMIN_MOD_PATH.'/models/system/db_selSystem.php');
		$system = db_selSystem(array('orderby' => array(array('col' => 'timestam'
																, 'desc' => true
																)
														),
										'limit' => array('start' => 0
														, 'num' => 1)
										)
								);
								
		if (is_array($system) && (count($system) > 0)){
			$this -> view -> system = $system[0];
		}
		
	}
	
	public function redirectAction(){
		include_once('default/models/car/db_selCarAd.php');
		include_once('default/models/bike/db_selBikeAd.php');
		include_once('default/models/truck/db_selTruckAd.php');
		
		$p = $this -> getRequest() -> getParams();
		$noRedirect = false;
		
		if (isset($p['vt']) && isset($p['vi'])) {
			$vType = strtolower($p['vt']);
			switch ($vType) {
				//handle car
				case strtolower(System_Properties::CAR_ABRV):
					$car = db_selCarAd(array('carID' => $p['vi']));
					if (($car != false) && is_array($car) && (count($car) > 0)){
						$car = $car[0];
						if (isset($car['extLink'])){
							$this -> view -> extLink = $car['extLink'];
						}else{
							$noRedirect = true;
						}
					}else{
						$noRedirect = true;
					}
				;break;
				
				//handle bike
				case strtolower(System_Properties::BIKE_ABRV):
					$bike = db_selBikeAd(array('bikeID' => $p['vi']));
					if (($bike != false) && is_array($bike) && (count($bike) > 0)){
						$bike = $bike[0];
						if (isset($bike['extLink'])){
							$this -> view -> extLink = $bike['extLink'];
						}else{
							$noRedirect = true;
						}
					}else{
						$noRedirect = true;
					}
				;break;
				
				//handle truck
				case strtolower(System_Properties::TRUCK_ABRV):
					$truck = db_selTruckAd(array('truckID' => $p['vi']));
					if (($truck != false) && is_array($truck) && (count($truck) > 0)){
						$truck = $truck[0];
						if (isset($truck['extLink'])){
							$this -> view -> extLink = $truck['extLink'];
						}else{
							$noRedirect = true;
						}
					}else{
						$noRedirect = true;
					}
				;break;
				
				default:	$noRedirect = true;break;
			}
			;
		}else{
			$noRedirect = true;
		}
		
		if ($noRedirect == true){
			$this -> render('offline');
		}else{
			$this -> view -> system = $this -> system;
		}
	}
	
	
	private function cleanAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/car/db_delCar2Ext.php');
		include_once('default/models/car/db_delCarAds.php');
		include_once('default/models/truck/db_delTruck2Ext.php');
		include_once('default/models/truck/db_delTruckAds.php');
		include_once('default/models/truck/db_selTruckAds.php');
		
		$files = scandir('.'.System_Properties::PIC_PATH);
		
		if (is_array($files)){
			foreach ($files as $key => $file) {
				
				if($file != '.' && $file != '..'){
					$fileUnit = explode('.',$file);
					if(isset($fileUnit[0])){
						$parts = explode('_',$fileUnit[0]);
						if((count($parts) == 3) && isset($parts[2])){
							$picDB = db_selVPic(array('vPicID' => trim($parts[2])));
							if(($picDB == false) || (is_array($picDB) && (count($picDB) == 0)) ){
								unlink('.'.System_Properties::PIC_PATH.'/'.$file);
								//echo '.'.System_Properties::PIC_PATH.'/'.$file;
								//echo $file.'<br>';
							}
						}
					}
				}
			}
		}		

		//Delete car advertisments
		$start = 0;
		$num = 1000;
		$run = true;
		while($run){
			$carAds = db_selCarAds(array('erased'=>true
					, 'limit' => array('num'=> $num
							, 'start' => $start
					)));
			foreach ($carAds as $key => $kVal){
				echo 'START DELETED CAR: '.$kVal['carID'].'<br/>';
				//Delete all pictures
				$vPic = db_selVPic(array('vID' => $kVal['carID']
						, 'vType' => 'c'
						// 										, 'print' => true
				));
		
				if (is_array($vPic)){
					$vPicID = array();
					foreach($vPic as $key2 => $kVal2){
						array_push($vPicID, $kVal2['vPicID']);
					}
					if (count($vPicID) > 0){
						if(db_delVPic(array('vPicID' => $vPicID)) != false){
							//delete pictures from filesystem
							foreach($vPic as $key2 => $kVal2){
								if(isset($kVal2['vType']) && isset($kVal2['vID']) && isset($kVal2['vPicID'])){
									$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($kVal2['vType']).'_'.$kVal2['vID'].'_'.$kVal2['vPicID'].'.jpeg';
									unlink($picPath);
								}
							}
						}
					}
				}
		
				//Delete all car extras
				db_delCar2Ext(array('carID' => $kVal['carID']
				// 									, 'print' => true
				));
		
				db_delCarAds(array('carID' => $kVal['carID']
				// 									, 'print' => true
				));
		
				echo 'END DELETED CAR: '.$kVal['carID'].'<br/>';
			}
			$start = $start + $num;
			if(is_array($carAds) && (count($carAds) > 1)){
				$run = true;
			}else{
				$run = false;
			}
		}
		

		//Delete truck advertisments
		$start = 0;
		$num = 1000;
		$run = true;
		while($run){
			$truckAds = db_selTruckAds(array('erased'=>true
					, 'limit' => array('num'=> $num
							, 'start' => $start
					)));
			foreach ($truckAds as $key => $kVal){
				echo 'START DELETED TRUCK: '.$kVal['truckID'].'<br/>';
				//Delete all pictures
				$vPic = db_selVPic(array('vID' => $kVal['truckID']
						, 'vType' => 't'
						// 										, 'print' => true
				));
		
				if (is_array($vPic)){
					$vPicID = array();
					foreach($vPic as $key2 => $kVal2){
						array_push($vPicID, $kVal2['vPicID']);
					}
					if (count($vPicID) > 0){
						if(db_delVPic(array('vPicID' => $vPicID)) != false){
							//delete pictures from filesystem
							foreach($vPic as $key2 => $kVal2){
								if(isset($kVal2['vType']) && isset($kVal2['vID']) && isset($kVal2['vPicID'])){
									$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($kVal2['vType']).'_'.$kVal2['vID'].'_'.$kVal2['vPicID'].'.jpeg';
									unlink($picPath);
								}
							}
						}
					}
				}
		
				//Delete all truck extras
				db_delTruck2Ext(array('truckID' => $kVal['truckID']
				// 									, 'print' => true
				));
		
				db_delTruckAds(array('truckID' => $kVal['truckID']
				// 									, 'print' => true
				));
		
				echo 'END DELETED TRUCK: '.$kVal['truckID'].'<br/>';
			}
			$start = $start + $num;
			if(is_array($truckAds) && (count($truckAds) > 1)){
				$run = true;
			}else{
				$run = false;
			}
		}
	}
	//Delete all expired truck advertisments
	private function test2Action(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once('default/models/truck/db_delTruck2Ext.php');
		include_once('default/models/truck/db_delTruckAds.php');
		include_once('default/models/truck/db_selTruckAds.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_selVPic.php');

		//Delete truck advertisments
		$start = 0;
		$num = 1000;
		$run = true;
		while($run){
			$truckAds = db_selTruckAds(array('erased'=>true
// 											, 'print' => true
											, 'limit' => array('num'=> $num
													, 'start' => $start
											)));
			foreach ($truckAds as $key => $kVal){
				echo 'START DELETED TRUCK: '.$kVal['truckID'].'<br/>';
				//Delete all pictures
				$vPic = db_selVPic(array('vID' => $kVal['truckID']
						, 'vType' => 't'
						// 										, 'print' => true
				));
		
				if (is_array($vPic)){
					$vPicID = array();
					foreach($vPic as $key2 => $kVal2){
						array_push($vPicID, $kVal2['vPicID']);
					}
					if (count($vPicID) > 0){
						if(db_delVPic(array('vPicID' => $vPicID)) != false){
							//delete pictures from filesystem
							foreach($vPic as $key2 => $kVal2){
								if(isset($kVal2['vType']) && isset($kVal2['vID']) && isset($kVal2['vPicID'])){
									$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($kVal2['vType']).'_'.$kVal2['vID'].'_'.$kVal2['vPicID'].'.jpeg';
									unlink($picPath);
								}
							}
						}
					}
				}
		
				//Delete all truck extras
				db_delTruck2Ext(array('truckID' => $kVal['truckID']
				// 									, 'print' => true
				));
		
				db_delTruckAds(array('truckID' => $kVal['truckID']
				// 									, 'print' => true
				));
		
				echo 'END DELETED TRUCK: '.$kVal['truckID'].'<br/>';
			}
			$start = $start + $num;
			if(is_array($truckAds) && (count($truckAds) > 1)){
				$run = true;
			}else{
				$run = false;
			}
		}
	}
	//Delete all expired car advertisments
	private function testAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once('default/models/car/db_delCar2Ext.php');
		include_once('default/models/car/db_delCarAds.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_selVPic.php');
		$start = 0;
		$num = 1000;
		$run = true;
		while($run){
			$carAds = db_selCarAds(array('erased'=>false
										, 'limit' => array('num'=> $num
															, 'start' => $start
									)));
			foreach ($carAds as $key => $kVal){
				echo 'START DELETED CAR: '.$kVal['carID'].'<br/>';
				//Delete all pictures
				$vPic = db_selVPic(array('vID' => $kVal['carID']
						, 'vType' => 'c'
						// 										, 'print' => true
				));
	
				if (is_array($vPic)){
					$vPicID = array();
					foreach($vPic as $key2 => $kVal2){
						array_push($vPicID, $kVal2['vPicID']);
					}
					if (count($vPicID) > 0){
						if(db_delVPic(array('vPicID' => $vPicID)) != false){
							//delete pictures from filesystem
							foreach($vPic as $key2 => $kVal2){
								if(isset($kVal2['vType']) && isset($kVal2['vID']) && isset($kVal2['vPicID'])){
									$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($kVal2['vType']).'_'.$kVal2['vID'].'_'.$kVal2['vPicID'].'.jpeg';
									unlink($picPath);
								}
							}
						}
					}
				}
	
				//Delete all car extras
				db_delCar2Ext(array('carID' => $kVal['carID']
				// 									, 'print' => true
				));
	
				db_delCarAds(array('carID' => $kVal['carID']
				// 									, 'print' => true
				));
	
				echo 'END DELETED CAR: '.$kVal['carID'].'<br/>';
			}
			$start = $start + $num;
			if(is_array($carAds) && (count($carAds) > 1)){
				$run = true;
			}else{
				$run = false;
			}
		}
	}
	
	//clean truck pics
	private function test3Action(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		include_once ('default/models/truck/db_selTruckAd.php');
			
		$start = 0;
		$num = 1000;
		$run = true;
		while($run){	
			$vPic = db_selVPic(array('vType' => 't'
									, 'print' => true
									, 'orderby' => array(
															array(	'col' => 'vPicID'
																	, 'desc' => true
																)	
															)
									, 'limit' => array('num'=> $num
													, 'start' => $start												
													)
									));
			if (is_array($vPic) && (count($vPic) > 0)){
				foreach($vPic as $key2 => $kVal2){
					$truckAd = db_selTruckAd(array('truckID'=>$kVal2['vID']));				
					if(($truckAd == null) || ($truckAd == false) || (is_array($truckAd) && (count($truckAd) <= 0)) ){
						if(db_delVPic(array('vPicID' => $kVal2['vPicID'])) != false){
							//delete pictures from filesystem
							if(isset($kVal2['vType']) && isset($kVal2['vID']) && isset($kVal2['vPicID'])){
								$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($kVal2['vType']).'_'.$kVal2['vID'].'_'.$kVal2['vPicID'].'.jpeg';
								unlink($picPath);
								echo "DEL: ".$picPath."<br/>";
							}
						}					
					}					
				}
			} else {
				$run = false;
			}
			$start = $start + $num;
			echo "NUM: ".$start."<br/>";
		}
	}
	//Adjust table mobile and delete all duplicates from car table
	private function test4Action(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		include_once('default/models/default/db_selMobile.php');
		include_once('default/models/default/db_delMobile.php');
		include_once('default/models/car/db_selCarAd.php');	
		include_once('default/models/car/db_selcarAds.php');
		include_once('default/models/car/db_delcar2Ext.php');
		include_once('default/models/car/db_delcarAds.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('default/models/default/db_selVPic.php');
		
		$start = 0;
		$num = 1000;
		$run = true;
		$mobileAds = array();
		$vType = 'c';
		while($run){			
			$mobileData = db_selMobile(array( 'mobileNew' => '0'
											, 'vType' => $vType
// 											, 'print'=>true											 
											, 'limit' => array('num'=> $num	
															, 'start' => $start												
															)
									));
			if(is_array($mobileData)){
				foreach($mobileData as $key=>$kVal){
					if(!isset($mobileAds[$kVal['userID']])){
						$mobileAds[$kVal['userID']] = array();
					}
					if(isset($kVal['vType']) && ($kVal['vType'] == $vType)){
						array_push($mobileAds[$kVal['userID']], $kVal);
					}
				}
				
				if(count($mobileAds) > 0){
					foreach($mobileAds as $key => $kVal){
						if($key != null){
							echo "USER: ".$key."<br/>";
							//Fetch all car advertisements
							$carAds = db_selcarAds(array('userID' => $key));
							if(is_array($carAds) && is_array($kVal) && (count($carAds) > 1) && (count($kVal) > 0)){
								foreach ($carAds as $key2 => $kVal2){
									if(strtolower($key2) == "totalrows"){
										continue;
									}
									$carID = $kVal2['carID'];
									$found = false;
									//look if advertisement is there or not
									foreach ($kVal as $key3 => $kVal3){
// 										echo $key3."<br/>";
										if($carID == $kVal3['vID']){
											echo "FOUND: ".$carID.": ".$key."<br/>";
											$found = true;
											break;
										}
									}
									if(!$found){
										echo "DEL: ".$carID.": ".$key."<br/>";
									}
									$found = true;
									//TODO delete advertisement with carID if found==false
									if(!$found){						
											echo 'START DELETED car: '.$carID.'<br/>';
											//Delete all pictures
											$vPic = db_selVPic(array('vID' => $carID
													, 'vType' => $vType
													// 										, 'print' => true
											));
								
											if (is_array($vPic)){
												$vPicID = array();
												foreach($vPic as $key3 => $kVal3){
													array_push($vPicID, $kVal3['vPicID']);
												}
												if (count($vPicID) > 0){
													if(db_delVPic(array('vPicID' => $vPicID)) != false){
														//delete pictures from filesystem
														foreach($vPic as $key3 => $kVal3){
															if(isset($kVal3['vType']) && isset($kVal3['vID']) && isset($kVal3['vPicID'])){
																$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($kVal3['vType']).'_'.$kVal3['vID'].'_'.$kVal3['vPicID'].'.jpeg';
																unlink($picPath);
															}
														}
													}
												}
											}
								
											//Delete all car extras
											db_delcar2Ext(array('carID' => $carID
											// 									, 'print' => true
											));
								
											db_delcarAds(array('carID' => $carID
											// 									, 'print' => true
											));
								
											echo 'END DELETED car: '.$carID.'<br/>';
	
									}
								}
							}
						}
					}
				}
			}
			$start = $start + $num;
			if(is_array($mobileData) && (count($mobileData) > 0)){
				$run = true;
			}else{
				$run = false;
			}	
			$run = false;
		}
	}
	
	//delete all zanox ads
	private function test5Action(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
// 		@ini_set("memory_limit",'400M');
		include_once('default/models/zanox/db_selZanox.php');
		include_once('default/models/zanox/db_delZanox.php');
		include_once('default/models/car/db_delCar2Ext.php');
		include_once('default/models/car/db_delCarAds.php');
		include_once('default/models/default/db_selVPic.php');
		include_once('default/models/default/db_delVPic.php');
		include_once('Zend/Json.php');
		
		$zanoxAds = db_selZanox(array('new' => array('0','1')));
		if($zanoxAds != false && is_array($zanoxAds)){
			foreach($zanoxAds as $zanoxAd){
				$refData = Zend_Json::decode($zanoxAd['refData']);
				if (is_array($refData) && isset($refData['vType']) && isset($refData['vID'])
						&& (strtolower($refData['vType']) == strtolower(System_Properties::CAR_ABRV))){

					//Delete all car advertisements
					db_delCarAds(array('carID' => $refData['vID']));
					
					//Delete all car extras
					db_delCar2Ext(array('carID' => $refData['vID']));
					$vPicID = array();
					$vPic = db_selVPic(array('vID' => $refData['vID']
							, 'vType' => $refData['vType']));
					if (is_array($vPic)){
						foreach($vPic as $key2 => $kVal2){
							array_push($vPicID, $kVal2['vPicID']);
						}
						if (count($vPicID) > 0){
							//delete pictures in the database
							if(db_delVPic(array('vPicID' => $vPicID)) != false){
								//delete picutres from filesystem
								foreach($vPic as $key2 => $kVal2){
									if(isset($kVal2['vType']) && isset($kVal2['vID']) && isset($kVal2['vPicID'])){
// 										$picPath = '/var/customers/webs/autotunes/www/web'.System_Properties::PIC_PATH.'/'.strtolower($kVal2['vType']).'_'.$kVal2['vID'].'_'.$kVal2['vPicID'].'.jpeg';
										$picPath = '.'.System_Properties::PIC_PATH.'/'.strtolower($kVal2['vType']).'_'.$kVal2['vID'].'_'.$kVal2['vPicID'].'.jpeg';
										if(unlink($picPath)){
											echo "DEL: ".$picPath.'<br>';
										}else{
											echo "NOTDEL: ".$picPath.'<br>';
										}
									}
								}
							}
						}
					}
				}
				db_delZanox(array('zanoxID' => $zanoxAd['zanoxID']));
			}
		}
	}

	/*
	
	//returns the list with all brands in the system
	public function getcarbrandAction(){
		include_once('default/models/car/db_selCarBrand.php');		
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		$carBrand = db_selCarBrand();
		$return = array();
		if(is_array($carBrand)){
			foreach($carBrand as $key => $kVal){
				if(isset($kVal['brandName'])){
					array_push($return, array($kVal['carBrandID'],$kVal['brandName']));
				}
			}
		}
		$return = Zend_Json::encode($return);
		$this -> getResponse() -> setBody($return);
	}
	
	//returns the list with all model to a brand
	public function getcarmodelAction(){
		include_once('default/models/car/db_selCarModel.php');		
		include_once('default/models/car/db_selCarBrand.php');	
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		$p = $this -> getRequest() -> getParams();
		if(isset($p['cid'])){
			$cid = $p['cid'];
			$carBrand = db_selCarBrand(array('carBrandID' => $cid));
			if($carBrand != false){
				$carModel = db_selCarModel(array('carBrandID' => $cid));
				$return = array();
				if(is_array($carModel)){
					foreach($carModel as $key => $kVal){
						if(isset($kVal['carModelName'])){
							array_push($return, array($kVal['carModelID'],$kVal['carModelName']));
						}
					}
				}
				$return = Zend_Json::encode($return);
				$this -> getResponse() -> setBody($return);
			}
		}			
		
	}*/
	
}
?>