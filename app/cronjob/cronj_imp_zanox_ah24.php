<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20120121
 * Desc:		This class contains cronjob functionalities
 *********************************************************************************/
if (stristr($_SERVER['PHP_SELF'],'/t01/')){
	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/t01/app:/var/customers/webs/autotunes/t01/app/modules:/var/customers/webs/autotunes/t01/web');
	$docRoot = '/var/customers/webs/autotunes/t01/web/';
}else{
	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/www/app:/var/customers/webs/autotunes/www/app/modules:/var/customers/webs/autotunes/www/web');
	$docRoot = '/var/customers/webs/autotunes/www/web/';
}
		
include_once('Zend/Json.php');
include_once('classes/DB.php');
include_once('modules/default/models/cronj/db_insCronjTask.php');
include_once('modules/default/models/cronj/db_selCronjTask.php');
include_once('modules/default/models/cronj/db_updCronjTask.php');
include_once('modules/default/models/cronj/db_selCronj.php');

include_once('modules/default/lang/lang.php');
include_once('classes/System_Properties.php');
include_once('modules/default/models/member/db_selUser.php');

include_once('modules/default/models/zanox/db_selZanox.php');
include_once('modules/default/models/zanox/db_insZanox.php');
include_once('modules/default/models/zanox/db_updZanox.php');

include_once('modules/default/models/car/db_selCarBrand.php');
include_once('modules/default/models/car/db_selCarModel.php');
include_once('modules/default/models/car/db_selCarCat.php');

include_once('modules/default/models/car/db_selCarAd.php');
include_once('modules/default/models/car/db_insCarAds.php');
include_once('modules/default/models/car/db_updCarAds.php');

include_once('modules/default/models/default/db_selVPic.php');
include_once('modules/default/models/default/db_insVPic.php');

include_once('default/views/filters/ImageFilter.php');	

class cl_cronj_imp_zanox_ah24{
	
	const CRONJ_NAME = 'CIAH24';
	const PROGRAM_NAME = 'AUTOHAUS24';
	
	private $DB;
	private $lang;
	private $docRoot;
	
	private $destination;
	
	private $userData; 
	
	private $link = array('XMLGZ' => 'http://productdata.zanox.com/exportservice/v1/rest/20703273C1699856109.xml?ticket=661391472561713F9875A666542E9240AFB035B770263D40B2139BB5EA9CC142&gZipCompress=yes'
						, 'XML' => 'http://productdata.zanox.com/exportservice/v1/rest/20703273C1699856109.xml?ticket=661391472561713F9875A666542E9240AFB035B770263D40B2139BB5EA9CC142&gZipCompress=null'
						, 'CSV' => 'http://productdata.zanox.com/exportservice/v1/rest/20703273C1699856109.csv?ticket=661391472561713F9875A666542E9240AFB035B770263D40B2139BB5EA9CC142&columnDelimiter=,&textQualifier=DoubleQuote&nullOutputFormat=NullValue&dateFormat=dd/MM/yyyy HH:mm:ss&decimalSeparator=period&gZipCompress=null&id&nb&na&pp&cy&df&ds&mc&zi&ia&im&mn&lk&td&tm&is&sh&sn'
						, 'CSVGZ' => 'http://productdata.zanox.com/exportservice/v1/rest/20703273C1699856109.csv?ticket=661391472561713F9875A666542E9240AFB035B770263D40B2139BB5EA9CC142&columnDelimiter=,&textQualifier=DoubleQuote&nullOutputFormat=NullValue&dateFormat=dd/MM/yyyy HH:mm:ss&decimalSeparator=period&gZipCompress=yes&id&nb&na&pp&cy&df&ds&mc&zi&ia&im&mn&lk&td&tm&is&sh&sn'
						);
	
	public function __construct($p = array()){
		$this -> DB = DB::getInstance();
		
		if (isset($p['LANG'])){
			$this -> lang = $p['LANG'];
		}
		
		if (isset($p['DOC_ROOT'])){
			$this -> docRoot = $p['DOC_ROOT'];
			$this -> destination = $this -> docRoot.'../app/cronjob/files/';
			
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
		if (is_array($this -> link) && isset($this->link['XMLGZ'])){
			
			//First delete all files from destination directory
			$files = scandir($this -> destination);
			if (is_array($files)){
				foreach ($files as $key => $kVal){
					if (($kVal != '.') && ($kVal != '..')){
						$kVal = $this -> destination.$kVal;
						if (is_dir($kVal)){
							System_Properties::rec_rmdir($kVal);
						}else{
							unlink($kVal);
						}
					}
				}
			}
// 			print_r("STEP: WGET DESTINATION FILE into "+$this -> destination.' '.$this->link['XMLGZ']);
			
			//Download new file from server
			exec('wget -P '.$this -> destination.' '.$this->link['XMLGZ'], $output);			
			
			$files = scandir($this -> destination);
			$file = null;
			if (is_array($files)){
				foreach ($files as $key => $kVal){
					$fileExt = explode('.', basename($kVal));
					if (is_array($fileExt) && isset($fileExt[1]) && ((stristr($fileExt[1], 'xml') != false) || (stristr($fileExt[1], 'gz') != false)) ){
						$file = $kVal;
						break;
					}
				}
			}
			//Import downloaded data
			if ($file != null){
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
							unlink($kVal);
						}
					}
				}
			}
		}
	}
	
	private function importData($p = array()){
		if (isset($p['FILE'])){
// 			print_r("STEP: import data");
			
			$file = $p['FILE'];
			
			$xmlfile = simplexml_load_file($file);			
			
			$rootAttrib = $xmlfile->attributes();
			$children = $xmlfile -> children();
					
			foreach ($children as $child){			
				$zanoxNew = array();
				
				$attrib = $child -> attributes();
					
				$zanoxNew['zupid'] = $attrib['zupid'];
				$zanoxNew['name'] = $child -> name;
				$zanoxNew['program'] = $child -> program;
				$zanoxNew['number'] = $child -> number;
				$zanoxNew['description'] = $child -> description;
				$zanoxNew['longDescription'] = $child -> longDescription;
				$zanoxNew['manufacturer']  = $child -> manufacturer;
				$zanoxNew['price'] = $child -> price;
				$zanoxNew['terms'] = $child -> terms;
				$zanoxNew['shippingCosts'] = $child -> shippingCosts;
				$zanoxNew['lastModified'] = $child -> lastModified;
				$zanoxNew['largeImg'] = $child -> largeImage;
				$zanoxNew['deliveryTime'] = $child -> deliveryTime;
				$zanoxNew['currencyCode'] = $child -> currencyCode;
				$zanoxNew['extra1'] = $child -> extra1;
				$zanoxNew['extra2'] = $child -> extra2;
				$zanoxNew['extra3'] = $child -> extra3;
				$zanoxNew['merchantCategory'] = $child -> merchantCategory;
				$zanoxNew['deepLink'] = $child -> deepLink;
				$zanoxNew['programName'] = self::PROGRAM_NAME;
				$zanoxNew['new'] = '1';
				$zanoxNew['timestam'] = true;
				
				$zanoxOld = db_selZanox(array('zupid' => $zanoxNew['zupid']
											, 'program' => $zanoxNew['program']
											, 'number' => $zanoxNew['number']
											, 'new' => array('0','1')
											//, 'print' => true
										));
				
				if (($zanoxOld == false) || (is_array($zanoxOld) && (count($zanoxOld) <= 0)) ){
					//$zanoxNew['print'] = true;
					$zanoxNew['refData'] = '';
					db_insZanox($zanoxNew);
				} else{
					$zanoxOld = $zanoxOld[0];
					db_updZanox(array(System_Properties::SQL_SET => $zanoxNew
									, System_Properties::SQL_WHERE => array('zanoxID' => $zanoxOld['zanoxID'])
								));
				}
				
				/*
				$programID = $attrib['ProgramID'];
				$p = array();
				$p['productName'] = $child -> Details -> Title; 
				
				$p['productPrice'] = 0;
				$price = explode('EUR',$child -> Price -> DisplayPrice);
				if(is_array($price)){
					$price = trim($price[0]);
					$p['productPrice'] = $price;
				}
				
				$p['productDescription'] = $child -> Details -> DescriptionShort;
				$p['productShopArtNr'] = $attrib['ArticleNumber'];
				$p['productLink']= $child -> Deeplinks -> Product;
				$p['productShopID'] = $rootAttrib['ShopID'];
				$p['productImgLink'] = $child -> Images -> Img -> URL;	
				
				//Determine product color			
				$p['productColor'] = '';
				$prop = $child -> Properties -> Property;
				if ($prop != null){
					foreach ($prop as $pp){
						if ($pp['Title'] == 'color'){
							$p['productColor'] = $pp['Text'];
						}
					}
				}
				
				//Determine product sex
				$category = null;
				$categoryPath= $child->CategoryPath->ProductCategoryPath;
				$p['productCatPath'] = $categoryPath;
				$p['productSex'] = null;
				
				//this product is for woman, so determine a woman catgory
				$detailTitle= $child->Details->Title;
				$detailDesc= $child->Details->DescriptionShort;
				*/
			}			
		}
	}	
	
	private function processCarAds($p = array()){
// 		print_r("STEP: processCarAds");
		$lang = $this -> lang;
		$carBrand = db_selCarBrand(array('orderby'=>array(array('col' => 'brandName'))
									));
		if (($carBrand != false) && is_array($carBrand) && (count($carBrand) > 0)){
					
			$zanox = db_selZanox(array('new' => 1
									, 'programName' => self::PROGRAM_NAME
// 									, 'limit' => array('start' => 0, 'num' => 5)
// 									, 'print' => true
								));
			
			if (($zanox != false) && is_array($zanox) && (count($zanox) > 0)){
				//Get car categories
				$carCat = db_selCarCat();	
				foreach ($zanox as $key => $kVal){
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
					if (isset($kVal['manufacturer'])){
						if (strtolower($kVal['manufacturer']) == 'volkswagen'){
							$kVal['manufacturer'] = 'VW';
						}
						foreach ($carBrand as $key2 => $kVal2){
							if (stristr(strtolower($kVal['manufacturer']), strtolower($kVal2['brandName'])) != false){
								$car['carBrandID'] = $kVal2['carBrandID'];
								$carModel = db_selCarModel(array('carBrandID' => $car['carBrandID']));
								break;
							}
						}
					}
					//determine car model
					if (($carModel != null) && is_array($carModel) && (count($carModel) > 0) 
						&& isset($kVal['name'])){
						$kVal['name'] = trim($kVal['name']);
						foreach ($carModel as $key2 => $kVal2){
							if (isset($kVal2['carModelName'])){
								$kVal2['carModelName'] = trim($kVal2['carModelName']);
								if(stristr($kVal['name'], $kVal2['carModelName'])){
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
						&& isset($kVal['name'])){
						$kVal['name'] = trim($kVal['name']);
						foreach ($carCat as $key2 => $kVal2){
							if (isset($kVal2['vcatID']) && isset($lang['V_CAT'][$kVal2['vcatID']])){
								if (stristr($kVal['name'], trim($lang['V_CAT'][$kVal2['vcatID']]))){
									if (isset($kVal2['carCatID'])){
										$car['carCat'] = $kVal2['carCatID'];
									}
								}
							}	
						}						
					}
					
					//set price
					if (isset($kVal['price']) && is_numeric($kVal['price'])){
						$car['carPrice'] = $kVal['price'];
						$car['carPriceType'] = 2;
						$car['carPriceCurr'] = 0;
					}
					
					//HSN
					if (isset($kVal['extra1'])){
						$hsn = explode(':', $kVal['extra1']);
						if (is_array($hsn)){
							if (isset($hsn[0]) && (strtolower(trim($hsn[0])) == 'hsn')){
								if (isset($hsn[1])){
									$car['carHSN'] = trim($hsn[1]);
								}
							}
						}
					}
					
					//TSN
					if (isset($kVal['extra2'])){
						$tsn = explode(':', $kVal['extra2']);
						if (is_array($tsn)){
							if (isset($tsn[0]) && (strtolower(trim($tsn[0])) == 'tsn')){
								if (isset($tsn[1])){
									$car['carTSN'] = trim($tsn[1]);
								}
							}
						}
					}
					
					//user data
					if (isset($kVal['terms'])){
						//Autohaus 24 GmbH, Zugspitzstraße 1, 82049 Pullach, Deutschland
						$terms = explode(',', $kVal['terms']);
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
					
					if (isset($kVal['description'])){
						$car['carDesc'] = substr(trim($kVal['description']), 0, 5000);
					
						//set car power?
						$matches = null;
						$pregMatch = preg_match('/KW:[\s]*[0-9]+/i', trim($kVal['description']), $matches);
						if (($matches != null) && is_array($matches) && (count($matches) > 0)){
							$matches = explode(':', $matches[0]);
							if (is_array($matches) && isset($matches[1])){
								$car['carPower'] = trim($matches[1]);
								$car['carPowerType'] = '0';
							}
						}else{
							$pregMatch = preg_match('/PS:[\s]*[0-9]+/i', trim($kVal['description']), $matches);
							if (($matches != null) && is_array($matches) && (count($matches) > 0)){
								$matches = explode(':', $matches[0]);
								if (is_array($matches) && isset($matches[1])){
									$car['carPower'] = trim($matches[1]);
									$car['carPowerType'] = '1';
								}
							}	
						}
					}
					
					//set external link
					if (isset($kVal['deepLink'])){
						$car['extLink'] = $kVal['deepLink'];
					}
					
					//set carState
					$car['carState'] = '0';//new vehicle
					
					//set carKM
					$car['carKM'] = '0';
					$car['carKMType'] = '0';
					
					//set car registration date
					$car['carEZM'] = -1;
					$car['carEZY'] = 9999;//date('Y');
					
					$carID = null;
					if (isset($car['carID'])){
						db_updCarAds(array(System_Properties::SQL_SET => $car
										, System_Properties::SQL_WHERE => array('carID' => $car['carID'])
										//, 'print'=>true
									));
						db_updZanox(array(System_Properties::SQL_SET => array('new' => '0')
										, System_Properties::SQL_WHERE => array('zanoxID' => $kVal['zanoxID'])
// 										, 'print' => true
						));
						
						$carID = $car['carID'];
					} else {
						$carID = db_insCarAds($car);
						if (is_numeric($carID) && ($carID != false) && isset($kVal['zanoxID'])){
							$refData = array('vID' => $carID, 'vType' => System_Properties::CAR_ABRV);
							$refData = Zend_Json::encode($refData);							
							db_updZanox(array(System_Properties::SQL_SET => array('refData' => $refData
																				, 'new' => '0')
											, System_Properties::SQL_WHERE => array('zanoxID' => $kVal['zanoxID'])
// 											, 'print' => true
										));
						}
					}
					if(!isset($kVal['largeImg']) || ($kVal['largeImg'] == null) || ($kVal['largeImg'] == "")){
						$kVal['largeImg'] = "http://banners.webmasterplan.com/view.asp?ref=314665&site=11701&b=6";
					}
					
					//download Fotos from merchant server
					if (($carID != null) && isset($kVal['largeImg'])){
						$imgName = basename($kVal['largeImg']);
						$mimeTypeDetails = null;
						$vPicID = null;
						
						$kVal['largeImg'] = explode('?', $kVal['largeImg']);
						if (is_array($kVal['largeImg']) && (count($kVal['largeImg']) > 0)){
							$kVal['largeImg'] = $kVal['largeImg'][0];
						}
						
						if((strpos($kVal['largeImg'], "http:") === false) || (strpos($kVal['largeImg'], "http:") > 0)){
// 							$kVal['largeImg'] = "http:".$kVal['largeImg']; //AF-added: 2013-06-13
							$kVal['largeImg'] = $kVal['largeImg']; //AF-added: 2013-06-13
						}
						
						if (stristr($kVal['largeImg'], '.jpg') || stristr($kVal['largeImg'], '.jpeg')){
							$mimeTypeDetails = 'jpeg';
						}elseif (stristr($kVal['largeImg'], '.png')){
							$mimeTypeDetails = 'jpeg';//'png';
						}
						
						if( ($mimeTypeDetails != null)){// && stristr($p_imgFileName, 'http://')){
							//Insert vehicle picture
							$vPic = db_selVPic(array('vType' => System_Properties::CAR_ABRV
													, 'vID' => $carID));
							if (($vPic != null) && is_array($vPic) && (count($vPic) > 0)){
								$vPicID = $vPic[0]['vPicID'];
							}else{
								$vPicID = db_insVPic(array('vType' => System_Properties::CAR_ABRV
															, 'vID' => $carID
															, 'vPicTMP' => '0'));
							}
							
							if (is_numeric($vPicID) && ($vPicID != null)){
								$imgDestFile = substr($this -> docRoot,0,-1).System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$carID.'_'.$vPicID.'.jpeg';
								$img = new ImageFilter(array(	'imgTrgWidth' => System_Properties::PIC_SIZE_W,
																'imgTrgHeight' => System_Properties::PIC_SIZE_H,
																'imgSrcExtension' => $mimeTypeDetails
																, 'imgDestFile' => $imgDestFile 
																));
								try{
									$img -> filter($kVal['largeImg']);
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

$cronj = new cl_cronj_imp_zanox_ah24(array('LANG' => $lang
									, 'DOC_ROOT' => $docRoot));
$cronj -> exec_cronj();

/*
 * if (($carID != null) && isset($kVal['largeImg'])){
						$imgName = basename($kVal['largeImg']);
						$mimeTypeDetails = null;
						$vPicID = null;
						
						$kVal['largeImg'] = explode('?', $kVal['largeImg']);
						if (is_array($kVal['largeImg']) && (count($kVal['largeImg']) > 0)){
							$kVal['largeImg'] = $kVal['largeImg'][0];
						}
						
						if (stristr($kVal['largeImg'], '.jpg') || stristr($kVal['largeImg'], '.jpeg')){
							$mimeTypeDetails = 'jpeg';
						}elseif (stristr($kVal['largeImg'], '.png')){
							$mimeTypeDetails = 'jpeg';//'png';
						}
						
						if ($mimeTypeDetails != null){
							//Insert vehicle picture
							$vPic = db_selVPic(array('vType' => System_Properties::CAR_ABRV
													, 'vID' => $carID));
							if (($vPic != null) && is_array($vPic) && (count($vPic) > 0)){
								$vPicID = $vPic[0]['vPicID'];
							}else{
								$vPicID = db_insVPic(array('vType' => System_Properties::CAR_ABRV
															, 'vID' => $carID
															, 'vPicTMP' => '0'));
							}
							
							if (is_numeric($vPicID) && ($vPicID != null)){
								$imgDestFile = substr($this -> docRoot,0,-1).System_Properties::PIC_PATH.'/'.System_Properties::CAR_ABRV.'_'.$carID.'_'.$vPicID.'.jpeg';
								$img = new ImageFilter(array(	'imgTrgWidth' => System_Properties::PIC_SIZE_W,
																'imgTrgHeight' => System_Properties::PIC_SIZE_H,
																'imgSrcExtension' => $mimeTypeDetails
																, 'imgDestFile' => $imgDestFile 
																));
								$img -> filter($kVal['largeImg']);
							}
						}
					}*/
?>