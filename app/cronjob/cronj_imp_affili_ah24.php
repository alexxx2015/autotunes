<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20120121
 * Desc:		This class contains cronjob functionalities
 *********************************************************************************/
@ini_set("memory_limit",'200M');
$thisfile = str_ireplace("\\","/",__FILE__);
$cronjobPath = dirname($thisfile);
$appPath = dirname($cronjobPath);
$wwwPath = dirname($appPath);

$docRoot = $wwwPath;
$includePath = ini_get('include_path').PATH_SEPARATOR.$appPath.PATH_SEPARATOR.($appPath.'/modules').PATH_SEPARATOR.($wwwPath.'/web');

ini_set('include_path', $includePath);
chdir($wwwPath.'/web');

// if (stristr($_SERVER['PHP_SELF'],'/t01/')){
// 	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/t01/app:/var/customers/webs/autotunes/t01/app/modules:/var/customers/webs/autotunes/t01/web');
// 	$docRoot = '/var/customers/webs/autotunes/t01/web/';
// }else{
// 	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/www/app:/var/customers/webs/autotunes/www/app/modules:/var/customers/webs/autotunes/www/web');//ALEX
// 	$docRoot = '/var/customers/webs/autotunes/www/web/';
// }

include_once('Zend/Json.php');
include_once('classes/DB.php');
include_once('modules/default/models/cronj/db_insCronjTask.php');
include_once('modules/default/models/cronj/db_selCronjTask.php');
include_once('modules/default/models/cronj/db_updCronjTask.php');
include_once('modules/default/models/cronj/db_selCronj.php');

include_once('modules/default/lang/lang.php');
include_once('classes/System_Properties.php');
include_once('modules/default/models/member/db_selUser.php');

include_once('modules/default/models/affili/db_selAffiliProp.php');
include_once('modules/default/models/affili/db_insAffiliProp.php');
include_once('modules/default/models/affili/db_delAffiliProp.php');

include_once('modules/default/models/affili/db_selAffili.php');
include_once('modules/default/models/affili/db_insAffili.php');
include_once('modules/default/models/affili/db_updAffili.php');

include_once('modules/default/models/car/db_selCarBrand.php');
include_once('modules/default/models/car/db_selCarModel.php');
include_once('modules/default/models/car/db_selCarCat.php');

include_once('modules/default/models/car/db_selCarAd.php');
include_once('modules/default/models/car/db_insCarAds.php');
include_once('modules/default/models/car/db_updCarAds.php');

include_once('modules/default/models/default/db_selVPic.php');
include_once('modules/default/models/default/db_insVPic.php');
include_once('modules/default/models/default/db_delVPic.php');

include_once('default/views/filters/ImageFilter.php');	

class cl_cronj_imp_affili_ah24{
	
	const CRONJ_NAME = 'CIAH24';
	const PROGRAM_NAME = 'AUTOHAUS24';
	
	private $DB;
	private $lang;
	private $docRoot;
	
	private $destination;
	
	private $userData; 
// 	private $pw = '6a8p9qfjDREY7Jy31VEy';
	private $link = array('XMLZIP' => 'http://productdata-download.affili.net/affilinet_products_3911_582104.zip?auth=6a8p9qfjDREY7Jy31VEy&type=XML'
						, 'XML' => 'http://productdata-download.affili.net/affilinet_products_3911_582104.XML?auth=6a8p9qfjDREY7Jy31VEy&type=XML'
						, 'CSV' => 'http://productdata-download.affili.net/affilinet_products_3911_582104.CSV?auth=6a8p9qfjDREY7Jy31VEy&type=CSV'
						, 'CSVZIP' => 'http://productdata-download.affili.net/affilinet_products_3911_582104.zip?auth=6a8p9qfjDREY7Jy31VEy&type=CSV'
						);
	
	public function __construct($p = array()){
		$this -> DB = DB::getInstance();
		
		if (isset($p['LANG'])){
			$this -> lang = $p['LANG'];
		}
		
		if (isset($p['DOC_ROOT'])){
			$this -> docRoot = $p['DOC_ROOT'];
// 			$this -> destination = $this -> docRoot.'../app/cronjob/files/';
			$this -> destination = '../app/cronjob/files/';
			
			$this -> userData = array('userID' => 2
									, 'userEMail' => 'info@autohaus24.de'
									, 'userTel1' => '0800 123 74 98'
									, 'userAds' => '1'
									, 'ip' => System_Properties::getIP()
									, 'userAdsLength' => 12);
		}
	}
	
	public function exec_cronj($p=array()){
		$lang = $this -> lang;
		$cronjDef = db_selCronj(array('cronjName' => self::CRONJ_NAME));
		if( ($cronjDef != false) && is_array($cronjDef) && (count($cronjDef) > 0)){
			$cronjDef = $cronjDef[0];
															
			$cronjTaskResult = '';		
			
			$lastCronjTask = db_selCronjTask(array(	'cronjID' => $cronjDef['cronjID']
													, 'cronjTaskFinished' => '1'
													, 'orderby' => array(array('col' => 'cronjTaskStartTime'
																				, 'desc' => true)
																		)
													, 'limit' => array('start' => 0
																		, 'num' => 1)
													));
			if( ($lastCronjTask != false) && is_array($lastCronjTask) && (count($lastCronjTask) > 0)){
				$lastCronjTask = $lastCronjTask[0];
			}else{
				$lastCronjTask = array('cronjTaskStartTime' => time()
										, 'cronjID' => $cronjDef['cronjID']);
			}
			
			//Log cronjob start
			$cronjTaskID = db_insCronjTask(array('cronjID' => $cronjDef['cronjID']
												//, 'print' => true
												));

			$this -> run($p);
			
																
			//Log cronjob stop
			db_updCronjTask(array(System_Properties::SQL_SET => array('cronjTaskStopTime' => time()+1
																	, 'cronjTaskFinished' => '1'
																	, 'cronjTaskResult' => Zend_Json::encode($cronjTaskResult))
								, System_Properties::SQL_WHERE => array('cronjTaskID' => $cronjTaskID)
								//, 'print' => true
								));
		}														
	}
	
	private function run($p = array()){
		$output = null;
		//Download file
		if (is_array($this -> link) && isset($this->link['XML'])){
			
			//First delete all files from destination directory
			$files = scandir($this -> destination);
			if (is_array($files)){
				foreach ($files as $key => $kVal){
					if (($kVal != '.') && ($kVal != '..')){
						$kVal = $this -> destination.$kVal;
						if (is_dir($kVal)){
							System_Properties::rec_rmdir($kVal);
						}else{
							unlink($kVal);//ALEX
						}
					}
				}
			}
			
			//Download new file from server
			exec('wget -P '.$this -> destination.' '.$this->link['XML'], $output);//ALEX	
			
			$files = scandir($this -> destination);
			$file = null;
			if (is_array($files)){
				foreach ($files as $key => $kVal){
					$fileExt = explode('.', basename($kVal));
					if (is_array($fileExt) && isset($fileExt[1]) && ((stristr($fileExt[1], 'xml') != false) || (stristr($fileExt[1], 'zip') != false)) ){
						$file = $kVal;
						break;
					}
				}
			}
			//Import downloaded data
// 			$file = "affilinet_products_3911_314665_xml.gz";//ALEX
			if ($file != null){
				$destFile = $this -> destination.$file;
				if(stristr($file,".zip")){
					$destFile = $this -> destination.'ah24.xml';
					$destFileLines = null;
					//unzip file
					//$gz = gzopen($file, 'r');
					$file = $this -> destination.$file;
					$gzLines = gzfile($file);
					if (is_array($gzLines)){
						$destFileLines = implode('', $gzLines);
						$fp = fopen($destFile, 'w');
						if ($fp != false){
							fwrite($fp, $destFileLines);
							fclose($fp);
						}
					}
				}
				
				$p['FILE'] = $destFile;
				$this -> importData($p);

				$this -> processCarAds($p);
			}			
		
			
			//Third delete all files from destination directory
			$files = scandir($this -> destination);
			if (is_array($files)){
				foreach ($files as $key => $kVal){
					if (($kVal != '.') && ($kVal != '..')){
						$kVal = $this -> destination.$kVal;
						if (is_dir($kVal)){
							System_Properties::rec_rmdir($kVal);
						}else{
							unlink($kVal);//ALEX
						}
					}
				}
			}
		}
	}
	
	private function importData($p = array()){
		if (isset($p['FILE'])){
			
			$file = $p['FILE'];
			
			$xmlfile = simplexml_load_file($file);			
			
			$rootAttrib = $xmlfile->attributes();
			$children = $xmlfile -> children();
			foreach ($children as $child){			
				$affiliNew = array();
				
				$attrib = $child -> attributes();
				$affiliNew['programID'] = $attrib['ProgramID']->__toString();
				$affiliNew['articleNumber'] = $attrib['ArticleNumber']->__toString();
				$affiliNew['categoryID'] = $child->CategoryPath->ProductCategoryID->__toString();
				$affiliNew['categoryPath'] = $child->CategoryPath->ProductCategoryPath->__toString();
				$affiliNew['price'] = $child->Price->DisplayPrice->__toString();
				$affiliNew['link'] = $child->Deeplinks->Product->__toString();
				$affiliNew['articleTitle'] = $child->Details->Title->__toString();
				$affiliNew['articleDesc'] = $child->Details->DescriptionShort->__toString();
				$affiliNew['imgURL'] = $child->Images->Img->URL->__toString();
				
				$affiliNew['programName'] = self::PROGRAM_NAME;
				$affiliNew['new'] = '1';
				$affiliNew['timestam'] = true;
				$affiliNew['properties'] = array();
				foreach($child->Properties->Property as $property){
					$propAttrib = $property -> attributes();
					array_push($affiliNew['properties'], array('title' => $propAttrib['Title']->__toString(), 'text' => $propAttrib['Text']->__toString()));
				}
				
				$affiliOld = db_selAffili(array('programID' => $affiliNew['programID']
											, 'articleNumber' => $affiliNew['articleNumber']
											, 'new' => array('0','1')
											//, 'print' => true
										));
				$affiliId = false;
				if (($affiliOld == false) || (is_array($affiliOld) && (count($affiliOld) <= 0)) ){
					//$affiliNew['print'] = true;
					$affiliNew['refData'] = '';
					$affiliId = db_insAffili($affiliNew);
				} else{
					$affiliOld = $affiliOld[0];
					$affiliNew['timestam'] = true;
					db_updAffili(array(System_Properties::SQL_SET => $affiliNew
									, System_Properties::SQL_WHERE => array('affiliID' => $affiliOld['affiliID'])
									//, 'print' => true
								));
					db_delAffiliProp(array('affiliID' => $affiliOld['affiliID']
// 										, 'print' => true
									));
					$affiliId = $affiliOld['affiliID'];
				}
				
				if($affiliId != false && is_numeric($affiliId) && is_array($affiliNew['properties'])){
					foreach($affiliNew['properties'] as $property){
						$property['affiliID'] = $affiliId;
// 						$property['print'] = true;
						$property['propName'] = $property['title'];
						$property['propValue'] = $property['text'];
						db_insAffiliProp($property);
					}
				}
			}			
		}
	}	
	
	private function getAffiliProp($affiliId){
		$return = array();
		$affiliProp = db_selAffiliProp(array('affiliID' => $affiliId));
		if($affiliProp != false){
			foreach($affiliProp as $prop){
				$return[$prop['propName']] = $prop['propValue'];
			}
		}
		return $return;
	}
	
	private function processCarAds($p = array()){
		$lang = $this -> lang;
		$carBrand = db_selCarBrand(array('orderby'=>array(array('col' => 'brandName'))
									));
		if (($carBrand != false) && is_array($carBrand) && (count($carBrand) > 0)){
					
			$affili = db_selAffili(array('new' => 1
									, 'programName' => self::PROGRAM_NAME
// 									, 'limit' => array('start' => 0, 'num' => 5)
// 									, 'print' => true
								));
			
			if (($affili != false) && is_array($affili) && (count($affili) > 0)){
				//Get car categories
				$carCat = db_selCarCat();	
				foreach ($affili as $key => $kVal){
					$affiliProp = $this -> getAffiliProp($kVal['affiliID']);
					$car = $this -> userData;
					
					$carModel = null;
					if (isset($kVal['refData']) && ($kVal['refData'] != '') && (strlen($kVal['refData']) > 0)){
						$refData = Zend_Json::decode($kVal['refData']);
						if (is_array($refData) && isset($refData['vType']) && isset($refData['vID'])
							&& (strtolower($refData['vType']) == strtolower(System_Properties::CAR_ABRV))){
							$carData = db_selCarAd(array('carID' => $refData['vID']));
							if (($carData != false) && is_array($carData) && (count($carData) > 0)){
								$car2 = $carData[0];
								$car = array_merge($car2,$car);
							}
						}
					}
					
					//Determine carBrand
					if (isset($affiliProp['CF_Energy efficiency label'])){
						foreach ($carBrand as $key2 => $kVal2){
							if($kVal2['brandName'] == 'VW'){
								$kVal2['brandName'] = 'Volkswagen';
							}
							if (stristr($affiliProp['CF_Energy efficiency label'], '/'.$kVal2['brandName'].'/') != false){								$car['carBrandID'] = $kVal2['carBrandID'];
								$carModel = db_selCarModel(array('carBrandID' => $car['carBrandID']));
								break;
							}
						}
					}
					//determine car model
					if (($carModel != null) && is_array($carModel) && (count($carModel) > 0) 
						&& isset($affiliProp['CF_Line'])){
						$affiliProp['CF_Line'] = trim($affiliProp['CF_Line']);
						foreach ($carModel as $key2 => $kVal2){
							if (isset($kVal2['carModelName'])){
								$kVal2['carModelName'] = trim($kVal2['carModelName']);
								if(stristr($affiliProp['CF_Line'], $kVal2['carModelName'])){
									if (isset($kVal2['carModelID'])){
										$car['carModelID'] = $kVal2['carModelID'];
										break;
									}
								}
							}
						}
					}
					
					//determine vehicle category
					if (($carCat != false) && is_array($carCat) && (count($carCat) > 0) 
						&& isset($kVal['categoryPath'])){
						$kVal['categoryPath'] = trim($kVal['categoryPath']);
						foreach ($carCat as $key2 => $kVal2){
							if (isset($kVal2['vcatID']) && isset($lang['V_CAT'][$kVal2['vcatID']])){
								if (stristr($kVal['categoryPath'], trim($lang['V_CAT'][$kVal2['vcatID']]))){
									if (isset($kVal2['carCatID'])){
										$car['carCat'] = $kVal2['carCatID'];
									}
								}
							}	
						}						
					}
					
					//set price
					if (isset($affiliProp['CF_ah24 Preis']) && is_numeric($affiliProp['CF_ah24 Preis'])){
						$car['carPrice'] = $affiliProp['CF_ah24 Preis'];
						$car['carPriceType'] = 2;
						$car['carPriceCurr'] = 0;
						$car['mwstSatz'] = '';
						$car['mwst'] = 1;
					}
					
					//TSN
					if (isset($affiliProp['CF_TSN'])){
						$car['carTSN'] = trim($affiliProp['CF_TSN']);
					}
					
					//HSN
					if (isset($affiliProp['CF_HSN'])){
						$car['carHSN'] = trim($affiliProp['CF_HSN']);
					}
					
					//carPower
					if(isset($affiliProp['CF_KW'])){
						$car['carPower'] = trim($affiliProp['CF_KW']);
						$car['carPowerType'] = '0';
					}
					elseif(isset($affiliProp['CF_PS'])){
						$car['carPower'] = trim($affiliProp['CF_PS']);
						$car['carPowerType'] = '1';
					}
					
					//carDesc
					if (isset($kVal['articleDesc'])){
						$car['carDesc'] = substr(trim($kVal['articleDesc']), 0, 5000);
					}
					
					//carDoor
					if(isset($affiliProp['CF_Anzahl Türen'])){
						$affiliProp['CF_Anzahl Türen'] = trim($affiliProp['CF_Anzahl Türen']);
						if(($affiliProp['CF_Anzahl Türen'] == '2') || ($affiliProp['CF_Anzahl Türen'] == '3')){
							$car['carDoor'] = 0;
						}
						elseif(($affiliProp['CF_Anzahl Türen'] == '4') || ($affiliProp['CF_Anzahl Türen'] == '5')){
							$car['carDoor'] = 1;
						}
					}
					
					//carUseOut
					if(isset($affiliProp['CF_Außerorts'])){
						$car['carUseOut'] = trim($affiliProp['CF_Außerorts']);
					}
					
					//carCO2
					if(isset($affiliProp['CF_Co2 emission'])){
						$car['carCO2'] = trim($affiliProp['CF_Co2 emission']);
					}

					//carEEK
					if(isset($affiliProp['CF_Energy efficiency'])){
						$car['carEEK'] = trim($affiliProp['CF_Energy efficiency']);
					}

					//carCub
					if(isset($affiliProp['CF_Hubraum'])){
						$car['carCub'] = trim($affiliProp['CF_Hubraum']);
					}

					//carUseIn
					if(isset($affiliProp['CF_Innerorts'])){
						$car['carUseIn'] = trim($affiliProp['CF_Innerorts']);
					}

					//carUseOut
					if(isset($affiliProp['CF_Kombiniert'])){
						$car['carUseOut'] = trim($affiliProp['CF_Kombiniert']);
					}

					//carFuel
					if(isset($affiliProp['CF_Krafstoffart'])){
						$affiliProp['CF_Krafstoffart'] = trim($affiliProp['CF_Krafstoffart']);
						if(stristr($affiliProp['CF_Krafstoffart'],"diesel")){ 
							$car['carFuel'] = '1';
						}
						elseif(stristr($affiliProp['CF_Krafstoffart'],"bleifrei")){ 
							$car['carFuel'] = '0';
						}
						elseif(stristr($affiliProp['CF_Krafstoffart'],"gas")){ 
							$car['carFuel'] = '5';
						}
						elseif(stristr($affiliProp['CF_Krafstoffart'],"hybrid")){ 
							$car['carFuel'] = '7';
						}
						elseif(stristr($affiliProp['CF_Krafstoffart'],"elektro")){ 
							$car['carFuel'] = '2';
						}
					}

					//carWeight
					if(isset($affiliProp['CF_Overall weight'])){
						$car['carWeight'] = trim($affiliProp['CF_Overall weight']);
					}

					//carEmissionNorm
					if(isset($affiliProp['CF_Schadstoffnorm'])){
						$affiliProp['CF_Schadstoffnorm'] = trim($affiliProp['CF_Schadstoffnorm']);
						if(stristr($affiliProp['CF_Schadstoffnorm'],'EU4')){
							$car['carEmissionNorm'] = '4';
						}
						elseif(stristr($affiliProp['CF_Schadstoffnorm'],'EU5')){
							$car['carEmissionNorm'] = '8';
						}
						elseif(stristr($affiliProp['CF_Schadstoffnorm'],'EU6')){
							$car['carEmissionNorm'] = '9';
						}
					}
					
					//extLink
					if (isset($kVal['link'])){
						$car['extLink'] = $kVal['link'];
					}
					
					//set carState
					$car['carState'] = '0';//new vehicle
					
					//set carKM
					$car['carKM'] = '0';
					$car['carKMType'] = '0';
					
					//set car registration date
					$car['carEZM'] = -1;
					$car['carEZY'] = 9999;//date('Y');
					
					
					
					//user data
					if (isset($affiliProp['CF_Impressum'])){
						//Autohaus 24 GmbH, Zugspitzstraße 1, 82049 Pullach, Deutschland
						$terms = explode(',', $affiliProp['CF_Impressum']);
						//set firm name
						if (isset($terms[0])){
							$car['userFirm'] = trim($terms[0]);
						}
						//set firm address
						if (isset($terms[1])){
							$car['userAdress'] = trim($terms[1]);
						} 
						//set plz and city name
						if(isset($terms[2])){
							$terms[2] = trim($terms[2]);
							$terms[2] = explode(' ', $terms[2]);
							if (isset($terms[2][0]) && is_numeric($terms[2][0])){
								$car['carLocPLZ'] = $terms[2][0];
								$car['userPLZ'] = $terms[2][0];
							}
							if (isset($terms[2][1])){
								$car['carLocOrt'] = $terms[2][1];
								$car['userOrt'] = $terms[2][1];
							}
						}
						//set country
						if (isset($terms[3]) && isset($lang['COUNTRY']) && is_array($lang['COUNTRY'])){
							$terms[3] = strtolower(trim($terms[3]));
							foreach ($lang['COUNTRY'] as $key2 => $kVal2){
								if (strtolower($kVal2) == $terms[3]){
									$car['carLocCountry'] = $key2;
									break;
								}
							}
						} 
					}
					
					$carID = null;
					if (isset($car['carID'])){
						$car['timestam'] = true;
						db_updCarAds(array(System_Properties::SQL_SET => $car
										, System_Properties::SQL_WHERE => array('carID' => $car['carID'])
										//, 'print'=>true
									));
						db_updAffili(array(System_Properties::SQL_SET => array('new' => '0')
										, System_Properties::SQL_WHERE => array('affiliID' => $kVal['affiliID'])
// 										, 'print' => true
						));
						
						$carID = $car['carID'];
					} else {
						$carID = db_insCarAds($car);
						if (is_numeric($carID) && ($carID != false) && isset($kVal['affiliID'])){
							$refData = array('vID' => $carID, 'vType' => System_Properties::CAR_ABRV);
							$refData = Zend_Json::encode($refData);							
							db_updAffili(array(System_Properties::SQL_SET => array('refData' => $refData
																				, 'new' => '0')
											, System_Properties::SQL_WHERE => array('affiliID' => $kVal['affiliID'])
// 											, 'print' => true
										));
						}
					}
					
					if(!isset($kVal['imgURL']) || ($kVal['imgURL'] == null) || ($kVal['imgURL'] == "")){
						$kVal['imgURL'] = "http://banners.webmasterplan.com/view.asp?ref=314665&site=11701&b=6";
					}
					
					//download Fotos from merchant server
					if (($carID != null) && isset($kVal['imgURL']) && (trim($kVal['imgURL']) != '')){
						$imgName = basename($kVal['imgURL']);
						$mimeTypeDetails = null;
						$vPicID = null;
						
						if (stristr($kVal['imgURL'], '.jpg') || stristr($kVal['imgURL'], '.jpeg')){
							$mimeTypeDetails = 'jpeg';
						}elseif (stristr($kVal['imgURL'], '.png')){
							$mimeTypeDetails = 'jpeg';//'png';
						}
						
						//if(((strpos($kVal['imgURL'], "http:") === false) || (strpos($kVal['imgURL'], "http:") > 0)) && ((strpos($kVal['imgURL'], "https:") === false) || (strpos($kVal['imgURL'], "https:") > 0))){
						$search = "http";
						$length = strlen($search);
						if(!(substr(trim($kVal['imgURL']),0,$length) === $search)){
							$mimeTypeDetails = null;
						}
						
						if( ($mimeTypeDetails != null)){// && stristr($p_imgFileName, 'http://')){
							//Insert vehicle picture
							$vPic = db_selVPic(array('vType' => System_Properties::CAR_ABRV
													, 'vID' => $carID));
							if (($vPic != null) && is_array($vPic) && (count($vPic) > 0)){
// 								$vPicID = $vPic[0]['vPicID'];
								foreach($vPic as $kVal2){
									if (db_delVPic ( array ('vPicID' => $kVal2['vPicID']) ) != false) {
										if (isset ( $kVal2 ['vType'] ) && isset ( $kVal2 ['vID'] ) && isset ( $kVal2 ['vPicID'] )) {
											// $picPath = '/var/customers/webs/autotunes/www/web' . System_Properties::PIC_PATH . '/' . strtolower ( $kVal2 ['vType'] ) . '_' . $kVal2 ['vID'] . '_' . $kVal2 ['vPicID'] . '.jpeg';
											$picPath = '.' . System_Properties::PIC_PATH . '/' . strtolower ( $kVal2 ['vType'] ) . '_' . $kVal2 ['vID'] . '_' . $kVal2 ['vPicID'] . '.jpeg';
// 											if(file_exists($picPath))
												unlink ( $picPath );
										}
									}
								}
							}
							$vPicID = db_insVPic(array('vType' => System_Properties::CAR_ABRV
														, 'vID' => $carID
														, 'vPicTMP' => '0'));
														
							if (is_numeric($vPicID) && ($vPicID != null)){
// 								$imgDestFile = substr($this -> docRoot,0,-1).System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$carID.'_'.$vPicID.'.jpeg';
								$imgDestFile = './'.System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$carID.'_'.$vPicID.'.jpeg';
								$img = new ImageFilter(array(	'imgTrgWidth' => System_Properties::PIC_SIZE_W,
																'imgTrgHeight' => System_Properties::PIC_SIZE_H,
																'imgSrcExtension' => $mimeTypeDetails
																, 'imgDestFile' => $imgDestFile 
																));
								try{
									$img -> filter($kVal['imgURL']);
								}catch(Exception $e){
									echo $e;
								}
							}
						}
					}
				}
			}
		}
		
	}
}

$cronj = new cl_cronj_imp_affili_ah24(array('LANG' => $lang
									, 'DOC_ROOT' => $docRoot));
$cronj -> exec_cronj();
?>