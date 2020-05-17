<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110619
 * Desc:		This class handles the autoscout24 data exchange
 *********************************************************************************/
include_once('classes/CL_DATEX_ABS.php');

include_once('default/models/default/db_insTrovit.php');
include_once('default/models/default/db_selTrovit.php');
include_once('default/models/default/db_updTrovit.php');

include_once('default/models/car/db_selCarAd.php');	
include_once('default/models/car/db_insCarAds.php');	
include_once('default/models/car/db_updCarAds.php');
include_once('default/models/car/db_selCarBrand.php');
include_once('default/models/car/db_selCarModel.php');
include_once('default/models/car/db_insCar2Ext.php');
include_once('default/models/car/db_selCarExt.php');

include_once('default/models/bike/db_selBikeAd.php');
include_once('default/models/bike/db_insBikeAds.php');
include_once('default/models/bike/db_updBikeAds.php');
include_once('default/models/bike/db_selBikeBrand.php');
include_once('default/models/bike/db_selBikeModel.php');
include_once('default/models/bike/db_insBike2Ext.php');
include_once('default/models/bike/db_selBikeExt.php');

include_once('default/models/truck/db_selTruckAd.php');
include_once('default/models/truck/db_insTruckAds.php');
include_once('default/models/truck/db_updTruckAds.php');
include_once('default/models/truck/db_selTruckBrand.php');
include_once('default/models/truck/db_selTruckModel.php');
include_once('default/models/truck/db_insTruck2Ext.php');
include_once('default/models/truck/db_selTruckExt.php');

include_once('default/views/filters/FilterValidAS24EZ.php');
include_once('default/views/filters/FilterMySQLInt.php');
include_once('default/views/filters/FilterStringXX.php');
include_once('default/views/filters/FilterEncUTF8.php');
include_once('default/views/filters/FilterIsEmptyString.php');

class CL_TROVITCAR extends CL_DATEX_ABSTRACT{
	const DATEX_ID = 'TROVITCAR';
	
	private $datexIntfParam;
	
	private $prot;
	
	public function __construct($p = null){
		parent::__construct($p);
		
		foreach (System_Properties::$DATA_INTF_FILE as $key => $kVal){
			if (strtolower(CL_AS24::DATEX_ID) == strtolower($key)){
				$this -> datexIntfParam = $kVal;
				break;
			}
		}
	}
	
	public function handleDatexImp($p){
		$lang = $this -> lang;
		$this -> prot = array('ERROR' => array(), 'INFO' => array());
		$fileCntrl = null;
		if(isset($p['FILE_CNTRL'])){
			$fileCntrl = $p['FILE_CNTRL'];
		}
		
		if (isset($p['USER_DATA'])){
			$this -> userData = $p['USER_DATA'];
		}
		
		if (isset($p['DOC_ROOT'])){
			$this -> docRoot = $p['DOC_ROOT'];
		}
		
		$fileName = null;
		if (isset($p['FILE_NAME'])){
			$fileName = $p['FILE_NAME'];
		}elseif ($fileCntrl != null){
			$fileName = $fileCntrl -> getFileName();
			$p['FILE_NAME'] = $fileName;
		}
		
		if (file_exists($fileName)){
			$fileNameComp = explode('.', basename($fileName));
			$fileExtension  = $fileNameComp[count($fileNameComp)-1];
			$fileExtension = strtolower($fileExtension);
			
			//invoke ZIP processing method
			if (strtolower($fileExtension) == 'zip'){
				$this -> handleZIPFileImp($p);
			}
			//invoke XML processing method
			elseif (strtolower($fileExtension) == 'xml'){
				$this -> handleXMLFileImp($p);
			}
		}else{
			array_push($this -> prot['ERROR'], $lang['ERR_43'].' '.$fileName);
		}
		
		$p['PROT'] = $this -> prot;
		
		return $p;
	}
	
	/**
	 * hanlde only CSV file 
	 */
	private function handleXMLFileImp($p){		
		include_once('default/models/member/db_selUser.php');		
		
		$lang = $this -> lang;
		
		$fUTF8 = new FilterEncUTF8();
		
		$fileName = null;
		if (isset($p['FILE_NAME'])){
			$fileName = $p['FILE_NAME'];
		}
		
		$user = null;
		if (isset($this -> userNS -> userData)){
			$userTMP = $this -> userNS -> userData;
			if (isset($userTMP['userID'])){
				$userTMP = db_selUser(array('userID' => $userTMP['userID']));
				if (($userTMP != false) && is_array($userTMP) && (count($userTMP) > 0)){
					$user = $userTMP[0];
				}
			}
		}elseif ($this -> userData != null){
			$user = $this -> userData;
		}
		if (($fileName != null) && ($user != null)){
			
			//read file and create XMl iterator for root element
			$fileHandler = file($fileName);
			$xmlRoot = new SimpleXmlIterator(implode(' ', $fileHandler));
			
			//iterate over XML elements
			for($xmlRoot -> rewind(); $xmlRoot -> valid(); $xmlRoot -> next()){
				//analyze only ad-elements
				$currentNodeName = strtolower($xmlRoot->current()->getName());				
				if(($currentNodeName == 'ad') && $xmlRoot -> hasChildren()){
					$trovitAd = $this -> getInitialTrovitStruc();
					
					foreach($xmlRoot -> getChildren() as $key => $kVal){
						$key = strtolower($key);
						//Process pictures
						if($key == 'pictures'){
							$trovitAd[$key] = array();
							for ($kVal -> rewind(); $kVal -> valid(); $kVal -> next()){
								//extra picture informations
								$trovitAdPic = array();
								foreach($kVal -> getChildren() as $key2 => $kVal2){
									$key2 = strtolower($key2);
									$trovitAdPic[$key2] = $kVal2;
								}								
								array_push($trovitAd[$key], $trovitAdPic);
							}
						}else{
							$trovitAd[$key] = $kVal;
						}
					}
					
					$trovitAd = $fUTF8 -> filter($trovitAd);
					$trovitAd = $this -> fillAssoziativeArr($trovitAd);
					//$csvData = $this -> checkManFieldsAS24Action(array('DATA' => $csvData));
					$trovitAd = array('DATA'=>$trovitAd);
					if (!isset($trovitAd['error']) && isset($trovitAd['DATA'])){
						$trovitAd = $trovitAd['DATA'];
						$trovitAd['trovitNew'] = 1;
						//check if data set already exists
						$trovitData = db_selTrovit(array( 'id' => $trovitAd['id']
														, 'vType' => $p['vType']
														, 'userID' => $user['userID']
														//, 'print'=>true
														));
						if (($trovitData != false) && is_array($trovitData) && (count($trovitData) > 0)){
							$trovitData = $trovitData[0];
							$updTrovit = db_updTrovit(array(System_Properties::SQL_SET => $trovitData
															, System_Properties::SQL_WHERE => array('id' => $trovitData['id'])
															//, 'print'=>true
															));
															//array_push($this -> prot['INFO'], $lang['INFO_11'].$as24Data['B']);
						}else{
							$trovitData['userID'] = $user['userID'];
							if (isset($p['vType'])){
								$trovitData['vType'] = $p['vType'];
							}
							//$csvData['print'] = true;
							//print_r($csvData);
							$trovitID = db_insTrovit($trovitData);
							if (is_numeric($trovitID)){
								//array_push($this -> prot['INFO'], $lang['INFO_10'].$csvData['B']);
							}else{
								array_push($this -> prot['ERROR'], $lang['ERR_44'].': '.$trovitData['make']);
							}
						}
					}else{
						array_push($this -> prot['ERROR'], $trovitData['make']);
					}
				}
			}			
			
			//adjust imported advertisments
			$this -> adjustImpAd($p);
		}
	}
	
	/**
	 * Adjust the new content with existing advertisements
	 */
	private function adjustImpAd($p = null){	
		$lang = $this -> lang;
		$user = null;
		if (isset($this -> userNS -> userData)){
			$userTMP = $this -> userNS -> userData;
			if (isset($userTMP['userID'])){
				$userTMP = db_selUser(array('userID' => $userTMP['userID']));
				if (($userTMP != false) && is_array($userTMP) && (count($userTMP) > 0)){
					$user = $userTMP[0];
				}
			}
		}elseif ($this -> userData != null){
			$user = $this -> userData;
		}
		
		if ($user != null){
			//fetch all new inserted as24 advertisments
			$trovitData = db_selTrovit(array('userID' => $user['userID']
											, 'trovitNew' => 1
											));
										
			if (($trovitData != false) && is_array($trovitData)){				
				foreach($trovitData as $key => $extData){
					//If vehicle ID is set then process an update
					if (isset($extData['vID']) && is_numeric($extData['vID'])){
						//Car
						if (isset($extData['vType']) && (strtolower($extData['vType']) == strtolower(System_Properties::CAR_ABRV))){
							$carDB = db_selCarAd(array('carID' => $extData['vID']));
									
							if( ($carDB != false) && is_array($carDB) && (count($carDB) > 0)){
								$carDB = $carDB[0];
								$car = $this -> transTrovit2CarStruc($extData);
								$car = $this -> filterCarData($car);
								//print_r($car);
								if (!isset($car['error'])){
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::CAR_ABRV
															, 'vID' => $carDB['carID']);
									
									$carUpd = db_updCarAds(array(System_Properties::SQL_SET => $car
															, System_Properties::SQL_WHERE => array('carID' => $carDB['carID'])
															//, 'print' => true
															));
									if ($carUpd != false){
										//update car extra
										//$this -> processTrovitCarExtra($p);//-->ToDo
									}
									//update pics
									$this -> processAdsPic($p);										
									array_push($this -> prot['INFO'], $lang['INFO_11'].$extData['B']);
								}
								db_updTrovit(array(System_Properties::SQL_SET => array('trovitNew' => '0')
											, System_Properties::SQL_WHERE => array('trovitID' => $extData['trovitID'])
											));
							}
						}/*
						//BIKE					
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::BIKE_ABRV)){
							$bikeDB = db_selBikeAd(array('bikeID' => $extData['vID']));
												
							if( ($bikeDB != false) && is_array($bikeDB) && (count($bikeDB) > 0)){
								$bikeDB = $bikeDB[0];
								$bike = $this -> transTrovit2BikeStruc($extData);//-->ToDo
								$bike = $this -> filterBikeData($bike);
								if (!isset($bike['error'])){
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::BIKE_ABRV
															, 'vID' => $bikeDB['bikeID']);
									
									$bikeUpd = db_updBikeAds(array(System_Properties::SQL_SET => $bike									
															, System_Properties::SQL_WHERE => array('bikeID' => $bikeDB['bikeID'])
															//, 'print'=> true
															));
									if ($bikeUpd != false){
										//update bike extra
										//$this -> processAS24BikeExtra($p);
									}
									//update pics
									$this -> processAdsPic($p);										
									array_push($this -> prot['INFO'], $lang['INFO_11'].$extData['B']);
								}
								db_updTrovit(array(System_Properties::SQL_SET => array('trovitNew' => '0')
											, System_Properties::SQL_WHERE => array('trovitID' => $extData['trovitID'])
											));
							}
						}	
						//Truck			
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::TRUCK_ABRV)){
							$truckDB = db_selTruckAd(array('truckID' => $extData['vID']));
												
							if( ($truckDB != false) && is_array($truckDB) && (count($truckDB) > 0)){
								$truckDB = $truckDB[0];
								$truck = $this -> transTrovit2TruckStruc($extData);//-->ToDo
								$truck = $this -> filterTruckData($truck);
								if (!isset($truck['error'])){
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::TRUCK_ABRV
															, 'vID' => $truckDB['truckID']);
									
									$truckUpd = db_updTruckAds(array(System_Properties::SQL_SET => $truck
															, System_Properties::SQL_WHERE => array('truckID' => $truckDB['truckID'])
															));
									if ($truckUpd != false){
										//update truck extra
										//$this -> processAS24TruckExtra($p);
									}
									//update pics
									$this -> processAdsPic($p);										
									array_push($this -> prot['INFO'], $lang['INFO_11'].$extData['B']);
								}
								db_updTrovit(array(System_Properties::SQL_SET => array('trovitNew' => '0')
											, System_Properties::SQL_WHERE => array('trovitID' => $extData['trovitID'])
											));
							}
						}*/
					}
					//Process a new insertion
					else{
						$updTrovitSQLSet = array('trovitNew' => '0'); 
						//Car
						if (isset($extData['vType']) && ($extData['vType'] == System_Properties::CAR_ABRV)){
							$car = $this -> transTrovit2CarStruc($extData);//-->ToDo
							$car = $this -> filterCarData($car);
							if (!isset($car['error'])){
								$carID = db_insCarAds($car);
								if (($carID != false) && is_numeric($carID)){
									$updTrovitSQLSet['vID'] = $carID;
									
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::CAR_ABRV
														, 'vID' => $carID);
									//update car extra
									//$this -> processTrovitCarExtra($p);
									
									//update pics
									$this -> processAdsPic($p);
									array_push($this -> prot['INFO'], $lang['INFO_10'].$extData['make']);
								}
							}
							
							if(count($updTrovitSQLSet)> 0){
								db_updTrovit(array(System_Properties::SQL_SET => $updTrovitSQLSet
												, System_Properties::SQL_WHERE => array('trovitID' => $extData['trovitID'])
												));
							}							
						}/*
						//BIKE					
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::BIKE_ABRV)){
							$bike = $this -> transTrovit2BikeStruc($extData);
							$bike = $this -> filterBikeData($bike);
							//print_r($bike);echo '<br><br>';
							if (!isset($bike['error'])){
								$bikeID = db_insBikeAds($bike);
								if (($bikeID != false) && is_numeric($bikeID)){
									$updTrovitSQLSet['vID'] = $bikeID;
									
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::BIKE_ABRV
														, 'vID' => $bikeID);
									//update bike extra
									//$this -> processAS24BikeExtra($p);
									
									//update pics
									$this -> processAdsPic($p);
									array_push($this -> prot['INFO'], $lang['INFO_10'].$extData['make']);
								}
							}
							
							if(count($updTrovitSQLSet)> 0){
								db_updTrovit(array(System_Properties::SQL_SET => $updTrovitSQLSet
												, System_Properties::SQL_WHERE => array('trovitID' => $extData['trovitID'])
												));
							}						
						}
						//TRUCK					
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::TRUCK_ABRV)){
							$truck = $this -> transTrovit2TruckStruc($extData);
							$truck = $this -> filterTruckData($truck);
							//print_r($truck);echo '<br><br>';
							if (!isset($truck['error'])){
								$truckID = db_insTruckAds($truck);
								if (($truckID != false) && is_numeric($truckID)){
									$updTrovitSQLSet['vID'] = $truckID;
									
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::TRUCK_ABRV
														, 'vID' => $truckID);
									//update truck extra
									//$this -> processAS24TruckExtra($p);
									
									//update pics
									$this -> processAdsPic($p);
									array_push($this -> prot['INFO'], $lang['INFO_10'].$extData['make']);
								}
							}
							
							if(count($updTrovitSQLSet)> 0){
								db_updTrovit(array(System_Properties::SQL_SET => $updTrovitSQLSet
												, System_Properties::SQL_WHERE => array('trovitID' => $extData['trovitID'])
												));
							}									
						}*/
					}
				}
			}			 
		}
	}
	
	/**
	 * This function extracts all vehicle extra information from a AS24 data set
	 */
	private function processTrovitCarExtra($p = null){
		$extData = null;
		$vID = null;
		$vType = null;
		
		$lang = $this -> lang;
		
		if (isset($p['EXT_DATA'])) {
			$extData = $p['EXT_DATA'];
		}
		
		if (isset($p['V_DATA']['vID'])) {
			$vID = $p['V_DATA']['vID'];
		}
		
		if (isset($p['V_DATA']['vType'])) {
			$vType = $p['V_DATA']['vType'];
		}
		
		if (($extData != null) && ($vID != null) && ($vType != null)){
			//delete car extra
			db_delCar2Ext(array('carID' => $vID));
			
			//fetch all car extra
			$carExtDB = db_selCarExt();
			
			if (($carExtDB != false) && is_array($carExtDB)){
				foreach ($carExtDB as $key => $kVal){
					if (isset($lang['V_EXTRA'][$kVal['vextID']]) && (($kVal['rgt'] - $kVal['lft']) <= 1)){
						$carExtTxt = $lang['V_EXTRA'][$kVal['vextID']];
					
						//Mwst. 
						if (isset($extData['AD']) && ($extData['AD'] == 1) && stristr($carExtTxt, 'mwst')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//klimaautomatik
						if (isset($extData['AG']) && ($extData['AG'] == 1) && stristr($carExtTxt, 'klima') && stristr($carExtTxt, 'auto')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						//klima
						elseif (isset($extData['AF']) && ($extData['AF'] == 1) && stristr($carExtTxt, 'klima')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//Lederausstattung
						if (isset($extData['AH']) && ($extData['AH'] == 1) && stristr($carExtTxt, 'lederaus')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//elektr. Fensterheber
						if (isset($extData['AI']) && ($extData['AI'] == 1) && stristr($carExtTxt, 'fensterhe')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//Navigationssystem
						if (isset($extData['AJ']) && ($extData['AJ'] == 1) && stristr($carExtTxt, 'navi')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//elektr. Sitze
						if (isset($extData['AK']) && ($extData['AK'] == 1) && stristr($carExtTxt, 'sitze') && stristr($carExtTxt, 'elekt')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//Schiebedach
						if (isset($extData['AL']) && ($extData['AL'] == 1) && stristr($carExtTxt, 'schiebedach')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//Sitzheizung
						if (isset($extData['AM']) && ($extData['AM'] == 1) && stristr($carExtTxt, 'sitz') && stristr($carExtTxt, 'heiz')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//Radio/CD
						/*
						if (isset($extData['AN']) && ($extData['AN'] == 1) && stristr($carExtTxt, 'radio')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']));
						}else*/if (isset($extData['AN']) && ($extData['AN'] == 1) && stristr($carExtTxt, 'CD')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//ABS
						if (isset($extData['AO']) && ($extData['AO'] == 1) && stristr($carExtTxt, 'abs')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//airbag
						if (isset($extData['AP']) && ($extData['AP'] == 1) && stristr($carExtTxt, 'airbag')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					
						//Beifahrerairbag
						if (isset($extData['AQ']) && ($extData['AQ'] == 1) && stristr($carExtTxt, 'airbag') && stristr($carExtTxt, 'beifahr')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Seitenairbag
						if (isset($extData['AR']) && ($extData['AR'] == 1) && stristr($carExtTxt, 'airbag') && stristr($carExtTxt, 'seite')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Xenonscheinwerfer
						if (isset($extData['AS']) && ($extData['AS'] == 1) && stristr($carExtTxt, 'xenon')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Zentralverriegelung
						if (isset($extData['AT']) && ($extData['AT'] == 1) && stristr($carExtTxt, 'zentral') && stristr($carExtTxt, 'riegel')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Alarmanlage
						if (isset($extData['AU']) && ($extData['AU'] == 1) && stristr($carExtTxt, 'alarm') && stristr($carExtTxt, 'anlage')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Wegfahrsperre
						if (isset($extData['AV']) && ($extData['AV'] == 1) && stristr($carExtTxt, 'fahr') && stristr($carExtTxt, 'sperre')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Traktionskontrolle
						if (isset($extData['AW']) && ($extData['AW'] == 1) && stristr($carExtTxt, 'traktion')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Allrad
						if (isset($extData['AX']) && ($extData['AX'] == 1) && stristr($carExtTxt, 'all') && stristr($carExtTxt, 'rad')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Tuning
						if (isset($extData['AY']) && ($extData['AY'] == 1) && stristr($carExtTxt, 'tuning')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Alufelgen
						if (isset($extData['AZ']) && ($extData['AZ'] == 1) && stristr($carExtTxt, 'alu') && stristr($carExtTxt, 'felgen')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Dachträger
						if (isset($extData['BA']) && ($extData['BA'] == 1) && stristr($carExtTxt, 'dach') && stristr($carExtTxt, 'träger')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Bordcomputer
						if (isset($extData['BB']) && ($extData['BB'] == 1) && stristr($carExtTxt, 'bord') && stristr($carExtTxt, 'compu')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//elektr. Einparkhilfe
						if (isset($extData['BC']) && ($extData['BC'] == 1) && stristr($carExtTxt, 'einpark') && stristr($carExtTxt, 'hilfe')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Nebelscheinwerfer
						if (isset($extData['BD']) && ($extData['BD'] == 1) && stristr($carExtTxt, 'nebel') && stristr($carExtTxt, 'scheinwerf')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Servolenkung
						if (isset($extData['BE']) && ($extData['BE'] == 1) && stristr($carExtTxt, 'servolenk')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Radio
						if (isset($extData['BF']) && ($extData['BF'] == 1) && stristr($carExtTxt, 'radio')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Anhängerkupplung
						if (isset($extData['BL']) && ($extData['BL'] == 1) && stristr($carExtTxt, 'anhäng') && stristr($carExtTxt, 'kupplung')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//ESP
						if (isset($extData['BM']) && ($extData['BM'] == 1) 
							&& ((stristr($carExtTxt, 'stab') && stristr($carExtTxt, 'program')) || stristr($carExtTxt, 'esp')) ) {
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Tempomat
						if (isset($extData['BN']) && ($extData['BN'] == 1) && stristr($carExtTxt, 'tempomat')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//behindertengerecht
						if (isset($extData['BO']) && ($extData['BO'] == 1) && stristr($carExtTxt, 'behindert') && stristr($carExtTxt, 'gerecht')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Dekra siegel
						if (isset($extData['BP']) && ($extData['BP'] == 1) && stristr($carExtTxt, 'dekra') && stristr($carExtTxt, 'siegel')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Garantie
						if (isset($extData['BU']) && ($extData['BU'] == 1) && stristr($carExtTxt, 'gebraucht')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Standheizung
						if (isset($extData['CG']) && ($extData['CG'] == 1) && stristr($carExtTxt, 'stand') && stristr($carExtTxt, 'heizung')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
						
						//Partikelfilter
						if (isset($extData['CH']) && ($extData['CH'] == 1) && stristr($carExtTxt, 'partikel') && stristr($carExtTxt, 'filter')){
							db_insCar2Ext(array('carExtID' => $kVal['carExtID']
												, 'carID' => $vID));
						}
					}
				}
			}
		}

		return $p;
	}
	
	/**
	 * Update or insert new photos for the advrtisement
	 */
	private function processAdsPic($p){
		$extData = null;
		$vType = null;
		$vID = null;
		$picNameUpload = null;
		$picNameDir = null;
		
		if (isset($p['EXT_DATA'])) {
			$extData = $p['EXT_DATA'];
		}
		
		if (isset($p['V_DATA']['vType'])){
			$vType = $p['V_DATA']['vType'];
		}
		
		if (isset($p['V_DATA']['vID'])){
			$vID = $p['V_DATA']['vID'];
		}
		
		if (isset($p['PIC_NAME']['FILE'])){
			$picNameUpload = $p['PIC_NAME']['FILE'];
		}		
		
		if (isset($p['PIC_NAME']['DIR'])){
			$picNameDir = $p['PIC_NAME']['DIR'];
		}
		
		if (($vType != null) && ($vID != null) && ($picNameDir != null) && ($extData != null) && isset($extData['B'])
			&& is_array($picNameUpload) && (count($picNameUpload) > 0)){
			$angebotsnr = $extData['B'];
			
			//select all current vehicle pictures and delete them
			$vPicID = array();												
			$vPic = db_selVPic(array('vID' => $vID
									, 'vType' => $vType));		
			if (is_array($vPic)){
				foreach($vPic as $key => $kVal){
					array_push($vPicID, $kVal['vPicID']);
				}
				if (count($vPicID) > 0){
					db_delVPic(array('vPicID' => $vPicID));
				}
			}
			
			//now insert all other pictures
			foreach ($picNameUpload as $key => $kVal){			
				$mimeTypeDetails = explode('.', basename($kVal));
				$mimeTypeDetails = strtolower($mimeTypeDetails[1]);
				if ((stristr($kVal, $angebotsnr) != false) && in_array($mimeTypeDetails, System_Properties::$PIC_EXT)){
					$fileSrc = $picNameDir.'/'.$kVal;
					if (file_exists($fileSrc)){
						$imgFilter = new ImageFilter(array(	'imgTrgWidth' => System_Properties::PIC_SIZE_W,
															'imgTrgHeight' => System_Properties::PIC_SIZE_H,
															'imgSrcExtension' => $mimeTypeDetails));
						$imgFilter -> filter($fileSrc);
						
						$vPicID = db_insVPic(array('vType' => $vType
												, 'vID' => $vID
												, 'vPicTMP' => 0));
						if (($vPicID != null) && is_numeric($vPicID)){
							if ($this -> docRoot != null){
								$fileDest = $this -> docRoot.'web/'.str_ireplace('/', '', System_Properties::PIC_PATH).'/'.$vType.'_'.$vID.'_'.$vPicID.'.jpeg';
							}else{
								$fileDest = './'.str_ireplace('/', '', System_Properties::PIC_PATH).'/'.$vType.'_'.$vID.'_'.$vPicID.'.jpeg';
							}
							if(copy($fileSrc, $fileDest)){
								@chown($fileDest, System_Properties::FTP_USER);
								@chgrp($fileDest, System_Properties::FTP_GROUP);
							}
						}		
					}
				}
			}
		}
	}
	
	/**
	 * This function filter all relevant data from the imported data set and fill a corresponding structure of CAR table
	 */
	private function transTrovit2CarStruc($p){
		$car = array();
		$lang = $this -> lang;
		$car['carModelVar'] = '';
		$car['carHSN'] = '';
		$car['carTSN'] = '';
		$car['carFIN'] = '';
		$car['carLocCountry'] = 'DE';
		
		//carBrandID
		if (isset($p['make'])){
			$carBrand = db_selCarBrand(array('brandNameL' => strtolower($p['make'])
											//, 'print' => true
											));
			if (($carBrand != false) && is_array($carBrand) && (count($carBrand) > 0)){
				$carBrand = $carBrand[0];
				$car['carBrandID'] = $carBrand['carBrandID'];
				$car['carBrand'] = $car['carBrandID'];
				
				//carModelID
				if (isset($p['model'])){
					$carModel = db_selCarModel(array('carModelNameL' => $p['model']
													, 'carBrandID' => $carBrand['carBrandID']
													//, 'p' => true
													));
					if (($carModel != false) && is_array($carModel) && (count($carModel) > 0)){
						$carModel = $carModel[0];
						$car['carModel'] = $carModel['carModelID'];
						$car['carModelID'] = $carModel['carModelID'];
					}
				}
			}
		}
		
		//carPrice
		if (isset($p['price']) && is_numeric($p['price'])){
			$car['carPrice'] = $p['price'];
		}
		
		//carPriceType
		$car['carPriceType'] = '0';
		
		//carPriceCurr
		if (isset($p['L'])){
			//see $lang['TXT_74'] for all currencies
			if (strtolower($p['L']) == 'eur'){
				$car['carPriceCurr'] = '0';
			}
			elseif (strtolower($p['L']) == 'rubel'){
				$car['carPriceCurr'] = 2;
			}
		}
		
		//carKM
		if (isset($p['mileage']) && is_numeric($p['mileage'])){
			$car['carKM'] = $p['mileage'];
					
			//carKMType -> see var. $lang['TXT_75']
			$car['carKMType'] = '0';
		}
		
		//carPower
		if (isset($p['power']) && is_numeric($p['power'])){
			$car['carPower'] = $p['power'];
			
			//carPowerType -> see var. $lang['TXT_72']
			$car['carPowerType'] = '0';
		}
		
		//carEZM, carEZY
		if (isset($p['year'])){
			/*
			$ddmmyyyy = explode('.', $p['date']);
			if (isset($ddmmyyyy[1]) && is_numeric($ddmmyyyy[1]) && ($ddmmyyyy[1] >= 1) && ($ddmmyyyy[1]<= 12)){
				$car['carEZM'] = $ddmmyyyy[1]; //carEZM
			}
			if (isset($ddmmyyyy[2]) && is_numeric($ddmmyyyy[2])){
				$car['carEZY'] = $ddmmyyyy[2]; //carEZY
			}*/
			
			$car['carEZM'] = 1;
			$car['carEZY'] = $p['year'];
		}
		/*
		//carTUVM, carTUVY, carAUM, carAUY
		if (isset($p['Y'])){
			$mmyyyy = explode('.', $p['Y']);
			if (isset($mmyyyy[0])){
				$car['carTUVM'] = $mmyyyy[0]; //carTUVM
				$car['carAUM'] = $mmyyyy[0]; //carAUM
			}
			if (isset($mmyyyy[1])){
				$car['carTUVY'] = $mmyyyy[1]; //carTUVY
				$car['carAUY'] = $mmyyyy[1]; //carAUY
			}
		}
		*/
		$car['carTUVY'] = -1;
		$car['carAUY'] = -1;
		
		//carShift -> see var. $lang['V_SHIFT']
		if (isset($p['transmission'])){
			//Manuel?
			if (stristr($p['transmission'], 'hand') != false){
				$car['carShift'] = '0';
			}
			//Automatik?
			elseif (stristr($p['transmission'], 'auto') != false){
				$car['carShift'] = 1;
			}
		}
		
		//carWeight
		if (isset($p['X']) && is_numeric($p['X'])){
			$car['carWeight'] = $p['X'];
		}
		
		if(isset($p['engine_size']) && is_numeric($p['engine_size'])){
			//carCyl
			if(($p['engine_size'] < 12) && is_int($p['engine_size'])){
				$car['carCyl'] = $p['engine_size'];
			}else{
				if(($p['engine_size'] < 12) && is_float($p['engine_size'])){
					$p['engine_size'] = $p['engine_size'] * 1000;
				}
				//carCub
				$car['carCub'] = $p['engine_size'];				
			}
		}
		
		//carDoor -> see var. $lang['CAR_DOOR']
		if (isset($p['doors']) && is_numeric($p['doors'])){
			if ($p['doors'] == 3){
				$car['carDoor'] = '0';				
			}elseif ($p['doors'] == 5){
				$car['carDoor'] = 1;
			}
		}
		/*
		//carUseIn
		if (isset($p['BQ']) && is_numeric(str_replace(',', '.', $p['BQ']))){
			$car['carUseIn'] = str_replace(',', '.', $p['BQ']);
		}
		
		//carUseOut
		if (isset($p['BR']) && is_numeric(str_replace(',', '.', $p['BR']))){
			$car['carUseOut'] = str_replace(',', '.', $p['BR']);
		}
		
		//carCO2
		if (isset($p['BT']) && is_numeric(str_replace(',', '.', $p['BT']))){
			$car['carCO2'] = str_replace(',', '.', $p['BT']);
		}
		*/
		
		//carState -> see var. $lang['V_STATE']
		if (isset($p['is_new']) && ($p['is_new'] == 1)){
			//Neuwagen
			$car['carState'] = '0';
		}elseif(isset($p['is_new']) && ($p['is_new'] == 0)){
			//Gebrauchtwagen
			$car['carState'] = '2';
		}
		
		//carCat -> see var. $lang['CAR_CAT']
		if (isset($p['car_type'])){
			$carCat = $this -> detCat(array_merge($p, array('vType' => System_Properties::CAR_ABRV)));
			if (($carCat != false) && isset($carCat['carCat'])){
				$car['carCat'] = $carCat['carCat'];
			}
		}
		
		//carFuel -> see var. $lang['V_FUEL']
		if (isset($p['fuel'])){
			if (stristr($p['fuel'], 'benzi') != false){				
				//Benzin
				$car['carFuel'] = '0';
			}elseif (stristr($p['fuel'], 'diese') != false){
				//diesel
				$car['carFuel'] = 1;
			}elseif (stristr($p['fuel'], 'gas') != false){
				//GAS
				$car['carFuel'] = 8;
			}elseif (stristr($p['fuel'], 'ethano') != false){
				//ethanol
				$car['carFuel'] = 4;
			}elseif (stristr($p['fuel'], 'elekt') != false){
				//elektro
				$car['carFuel'] = 2;
			}elseif (stristr($p['fuel'], 'lpg') != false){
				//LPG GAs
				$car['carFuel'] = 3;
			}elseif (stristr($p['fuel'], 'cng') != false){
				//CNG Gas
				$car['carFuel'] = 6;
			}elseif (stristr($p['fuel'], 'hybrid') != false){
				//Hybrid
				$car['carFuel'] = 7;
			}
		}
		
		//carClr -> see var. $lang['V_CLR']
		if (isset($p['color'])){
			foreach ($lang['V_CLR'] as $key => $kVal){
				if (stristr($kVal, $p['color']) != false){
					$car['carClr'] = $key;
					break;
				}
			}
		}
		/*
		//carClrMet
		if (isset($p['K']) && ($p['K'] == 1)){
			$car['carClrMet'] = 1;
		}
		
		//carEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset($p['CI'])){
			if (stristr($p['CI'], 1) != false){
				//EURO 1
				$car['carEmissionNorm'] = 1;
			}elseif (stristr($p['CI'], 2) != false){
				//EURO 2
				$car['carEmissionNorm'] = 2;
			}elseif (stristr($p['CI'], 3) != false){
				//EURO 3
				$car['carEmissionNorm'] = 3;
			}elseif (stristr($p['CI'], 4) != false){
				//EURO 4
				$car['carEmissionNorm'] = 4;
			}elseif (stristr($p['CI'], 5) != false){
				//EURO 5
				//$car['carEmissionNorm'] = 5;
			}elseif (stristr($p['CI'], 6) != false){
				//EURO 6
				//$car['carEmissionNorm'] = 5;
			}
		}
		
		//carEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset($p['CJ'])){
			if (stristr($p['CJ'], 'kein') != false){
				//Keine
				$car['carEcologicTag'] = '0';
			}elseif (stristr($p['CJ'], 'rot') != false){
				//ROT 
				$car['carEcologicTag'] = 1;
			}elseif (stristr($p['CJ'], 'gelb') != false){
				//Gelb
				$car['carEcologicTag'] = 2;
			}elseif (stristr($p['CJ'], 'grün') != false){
				//Grün
				$car['carEcologicTag'] = 3;
			}
		}
		
		//carKlima -> see var. $lang['V_KLIMA']
		if (isset($p['AF']) && ($p['AF'] == 1)){
			//Klima
			$car['carKlima'] = '0';
		}elseif (isset($p['AG']) && ($p['AG'] == 1)){
			//Klimaautomatik
			$car['carKlima'] = 1;
		}
		*/
		
		//carDesc
		if (isset($p['content'])){
			$car['carDesc'] = $p['content'];
		}
		
		//userAdsLength
		$car['userAdsLength'] = 12;
		
		$user = null;
		if (isset($this -> userNS -> userData)){
			$user = $this -> userNS -> userData;
		}elseif ($this -> userData != null){
			$user = $this -> userData;
		}
		
		if ($user != null){			
			//userAds -> see var. $lang['TXT_33']
			$car['userAds'] = '1';/*
			if (isset($user['userMode']) && ($user['userMode'] != 3)){
				$car['userAds'] = $user['userMode'];				
			}*/
			
			//userFirm
			if (isset($p['dealer'])){
				$car['userFirm'] = $p['dealer']; 
			}
			
			//userNName
			if (isset($user['userNName'])){
				$car['userNName'] = $user['userNName']; 
			}
			
			//userVName
			if (isset($user['userVName'])){
				$car['userVName'] = $user['userVName']; 
			}
			
			//userEMail
			if (isset($user['userEMail'])){
				$car['userEMail'] = $user['userEMail']; 
			}
			
			//userPLZ
			if (isset($p['postcode'])){
				$car['userPLZ'] = $p['postcode'];
				$car['carLocPLZ'] = $p['postcode']; 
			}
			
			//userOrt
			if (isset($p['city'])){
				$car['userOrt'] = $p['city'];
				$car['carLocOrt'] = $p['city']; 
			}
			
			//userTel1
			if (isset($user['userTel1'])){
				$car['userTel1'] = $user['userTel1']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$car['userTel2'] = $user['userTel2']; 
			}
			
			//userAdress
			if (isset($user['userAdress'])){
				$car['userAdress'] = $user['userAdress']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$car['userTel2'] = $user['userTel2']; 
			}
			
			//userID
			if (isset($user['userID'])){
				$car['userID'] = $user['userID']; 
			}
		}
		
		//extLink
		if(isset($p['url'])){
			$car['extLink'] = $p['url'];
		}
		
		return $car;
	}

	
	/**
	 * This function filter all relevant data from the imported data set and fill a corresponding structure of bike table
	 */
	private function transTrovit2BikeStruc($p){
		$bike = array();
		$lang = $this -> lang;
		$bike['bikeModelVar'] = '';
		$bike['bikeHSN'] = '';
		$bike['bikeTSN'] = '';
		$bike['bikeFIN'] = '';
		$bike['bikeLocCountry'] = 'DE';
		
		//bikeBrandID
		if (isset($p['D'])){
			$bikeBrand = db_selBikeBrand(array('brandNameL' => strtolower($p['D'])
											//, 'print' => true
											));
			if (($bikeBrand != false) && is_array($bikeBrand) && (count($bikeBrand) > 0)){
				$bikeBrand = $bikeBrand[0];
				$bike['bikeBrandID'] = $bikeBrand['bikeBrandID'];
				$bike['bikeBrand'] = $bike['bikeBrandID'];
				
				//bikeModelID
				if (isset($p['E'])){
					$bikeModel = db_selBikeModel(array('bikeModelNameL' => $p['E']
													, 'bikeBrandID' => $bikeBrand['bikeBrandID']
													//, 'p' => true
													));
					if (($bikeModel != false) && is_array($bikeModel) && (count($bikeModel) > 0)){
						$bikeModel = $bikeModel[0];
						$bike['bikeModel'] = $bikeModel['bikeModelID'];
						$bike['bikeModelID'] = $bikeModel['bikeModelID'];
					}
				}
			}
		}
		
		//bikePrice
		if (isset($p['P']) && is_numeric($p['P'])){
			$bike['bikePrice'] = $p['P'];
		}
		
		//bikePriceType
		$bike['bikePriceType'] = '0';
		
		//bikePriceCurr
		if (isset($p['L'])){
			//see $lang['TXT_74'] for all currencies
			if (strtolower($p['L']) == 'eur'){
				$bike['bikePriceCurr'] = '0';
			}
			elseif (strtolower($p['L']) == 'rubel'){
				$bike['bikePriceCurr'] = 2;
			}
		}
		
		//bikeKM
		if (isset($p['O']) && is_numeric($p['O'])){
			$bike['bikeKM'] = $p['O'];
					
			//bikeKMType -> see var. $lang['TXT_75']
			$bike['bikeKMType'] = '0';
		}
		
		//bikePower
		if (isset($p['T']) && is_numeric($p['T'])){
			$bike['bikePower'] = $p['T'];
			
			//bikePowerType -> see var. $lang['TXT_72']
			$bike['bikePowerType'] = '0';
		}
		
		//bikeEZM, bikeEZY
		if (isset($p['N'])){
			$mmyyyy = explode('.', $p['N']);
			if (isset($mmyyyy[0])){
				$bike['bikeEZM'] = $mmyyyy[0]; //bikeEZM
			}
			if (isset($mmyyyy[1])){
				$bike['bikeEZY'] = $mmyyyy[1]; //bikeEZY
			}
		}
		
		//bikeTUVM, bikeTUVY, bikeAUM, bikeAUY
		if (isset($p['Y'])){
			$mmyyyy = explode('.', $p['Y']);
			if (isset($mmyyyy[0])){
				$bike['bikeTUVM'] = $mmyyyy[0]; //bikeTUVM
				$bike['bikeAUM'] = $mmyyyy[0]; //bikeAUM
			}
			if (isset($mmyyyy[1])){
				$bike['bikeTUVY'] = $mmyyyy[1]; //bikeTUVY
				$bike['bikeAUY'] = $mmyyyy[1]; //bikeAUY
			}
		}
		
		//bikeShift -> see var. $lang['V_SHIFT']
		if (isset($p['H'])){
			//Manuel?
			if (stristr($p['H'], 'hand') != false){
				$bike['bikeShift'] = '0';
			}
			//Automatik?
			elseif (stristr($p['H'], 'auto') != false){
				$bike['bikeShift'] = 1;
			}
		}
		
		//bikeWeight
		if (isset($p['X']) && is_numeric($p['X'])){
			$bike['bikeWeight'] = $p['X'];
		}
		
		//bikeCyl
		if (isset($p['U']) && is_numeric($p['U'])){
			$bike['bikeCyl'] = $p['U'];
		}
		
		//bikeCub
		if (isset($p['S']) && is_numeric($p['S'])){
			$bike['bikeCub'] = $p['S'];
		}
		
		//bikeDoor -> see var. $lang['bike_DOOR']
		if (isset($p['Q']) && is_numeric($p['Q'])){
			if ($p['Q'] == 2){
				$bike['bikeDoor'] = '0';				
			}elseif ($p['Q'] == 4){
				$bike['bikeDoor'] = 1;
			}
		}
		
		//bikeUseIn
		if (isset($p['BQ']) && is_numeric(str_replace(',', '.', $p['BQ']))){
			$bike['bikeUseIn'] = str_replace(',', '.', $p['BQ']);
		}
		
		//bikeUseOut
		if (isset($p['BR']) && is_numeric(str_replace(',', '.', $p['BR']))){
			$bike['bikeUseOut'] = str_replace(',', '.', $p['BR']);
		}
		
		//bikeCO2
		if (isset($p['BT']) && is_numeric(str_replace(',', '.', $p['BT']))){
			$bike['bikeCO2'] = str_replace(',', '.', $p['BT']);
		}
		
		//bikeState -> see var. $lang['V_STATE']
		$as2Cat = $p['G'];
		if (isset($p['AE']) && ($p['AE'] == 1)){
			//Unfallfahrzeug
			$bike['bikeState'] = 4;
		}elseif (isset($as2cat) && (stristr($as2cat, 'gebraucht') != false)){
			//Gebruachtwagen
			$bike['bikeState'] = 2;
		}elseif (isset($as2cat) && (stristr($as2cat, 'jahres') != false)){
			//Jahreswagen
			$bike['bikeState'] = 1;
		}elseif (isset($as2cat) && (stristr($as2cat, 'neu') != false)){
			//Neuwagen
			$bike['bikeState'] = '0';
		}elseif (isset($as2cat) && (stristr($as2cat, 'vorf') != false)){
			//Vorführwagen
			$bike['bikeState'] = 5;
		}elseif (isset($as2cat) && (stristr($as2cat, 'oldtim') != false)){
			//Oldtimer
			$bike['bikeState'] = 6;
		}
		
		//bikeCat -> see var. $lang['bike_CAT']
		if (isset($p['I'])){
			$bikeCat = $this -> detCat(array_merge($p, array('vType' => System_Properties::BIKE_ABRV)));
			if (($bikeCat != false) && isset($bikeCat['bikeCat'])){
				$bike['bikeCat'] = $bikeCat['bikeCat'];
			}
		}
		
		//bikeFuel -> see var. $lang['V_FUEL']
		if (isset($p['M'])){
			if (stristr($p['M'], 'benzi') != false){				
				//Benzin
				$bike['bikeFuel'] = '0';
			}elseif (stristr($p['M'], 'diese') != false){
				//diesel
				$bike['bikeFuel'] = 1;
			}elseif (stristr($p['M'], 'gas') != false){
				//GAS
				$bike['bikeFuel'] = 8;
			}elseif (stristr($p['M'], 'ethano') != false){
				//ethanol
				$bike['bikeFuel'] = 4;
			}elseif (stristr($p['M'], 'elekt') != false){
				//elektro
				$bike['bikeFuel'] = 2;
			}elseif (stristr($p['M'], 'lpg') != false){
				//LPG GAs
				$bike['bikeFuel'] = 3;
			}elseif (stristr($p['M'], 'cng') != false){
				//CNG Gas
				$bike['bikeFuel'] = 6;
			}elseif (stristr($p['M'], 'hybrid') != false){
				//Hybrid
				$bike['bikeFuel'] = 7;
			}
		}
		
		//bikeClr -> see var. $lang['V_CLR']
		if (isset($p['CN'])){
			foreach ($lang['V_CLR'] as $key => $kVal){
				if (stristr($kVal, $p['CN']) != false){
					$bike['bikeClr'] = $key;
					break;
				}
			}
		}
		
		//bikeClrMet
		if (isset($p['K']) && ($p['K'] == 1)){
			$bike['bikeClrMet'] = 1;
		}
		
		//bikeEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset($p['CI'])){
			if (stristr($p['CI'], 1) != false){
				//EURO 1
				$bike['bikeEmissionNorm'] = 1;
			}elseif (stristr($p['CI'], 2) != false){
				//EURO 2
				$bike['bikeEmissionNorm'] = 2;
			}elseif (stristr($p['CI'], 3) != false){
				//EURO 3
				$bike['bikeEmissionNorm'] = 3;
			}elseif (stristr($p['CI'], 4) != false){
				//EURO 4
				$bike['bikeEmissionNorm'] = 4;
			}elseif (stristr($p['CI'], 5) != false){
				//EURO 5
				//$bike['bikeEmissionNorm'] = 5;
			}elseif (stristr($p['CI'], 6) != false){
				//EURO 6
				//$bike['bikeEmissionNorm'] = 5;
			}
		}
		
		//bikeEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset($p['CJ'])){
			if (stristr($p['CJ'], 'kein') != false){
				//Keine
				$bike['bikeEcologicTag'] = '0';
			}elseif (stristr($p['CJ'], 'rot') != false){
				//ROT 
				$bike['bikeEcologicTag'] = 1;
			}elseif (stristr($p['CJ'], 'gelb') != false){
				//Gelb
				$bike['bikeEcologicTag'] = 2;
			}elseif (stristr($p['CJ'], 'grün') != false){
				//Grün
				$bike['bikeEcologicTag'] = 3;
			}
		}
		
		//bikeKlima -> see var. $lang['V_KLIMA']
		if (isset($p['AF']) && ($p['AF'] == 1)){
			//Klima
			$bike['bikeKlima'] = '0';
		}elseif (isset($p['AG']) && ($p['AG'] == 1)){
			//Klimaautomatik
			$bike['bikeKlima'] = 1;
		}
		
		//bikeDesc
		if (isset($p['BG'])){
			$bike['bikeDesc'] = $p['BG'];
		}
		
		//userAdsLength
		$bike['userAdsLength'] = 12;
		
		$user = null;
		if (isset($this -> userNS -> userData)){
			$user = $this -> userNS -> userData;
		}elseif ($this -> userData != null){
			$user = $this -> userData;
		}
		
		if ($user != null){		
			
			//userAds -> see var. $lang['TXT_33']
			$bike['userAds'] = '-1';
			if (isset($user['userMode']) && ($user['userMode'] != 3)){
				$bike['userAds'] = $user['userMode'];				
			}
			
			//userFirm
			if (isset($user['userFirm'])){
				$bike['userFirm'] = $user['userFirm']; 
			}
			
			//userNName
			if (isset($user['userNName'])){
				$bike['userNName'] = $user['userNName']; 
			}
			
			//userVName
			if (isset($user['userVName'])){
				$bike['userVName'] = $user['userVName']; 
			}
			
			//userEMail
			if (isset($user['userEMail'])){
				$bike['userEMail'] = $user['userEMail']; 
			}
			
			//userPLZ
			if (isset($user['userPLZ'])){
				$bike['userPLZ'] = $user['userPLZ'];
				$bike['bikeLocPLZ'] = $user['userPLZ']; 
			}
			
			//userOrt
			if (isset($user['userOrt'])){
				$bike['userOrt'] = $user['userOrt'];
				$bike['bikeLocOrt'] = $user['userOrt']; 
			}
			
			//userTel1
			if (isset($user['userTel1'])){
				$bike['userTel1'] = $user['userTel1']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$bike['userTel2'] = $user['userTel2']; 
			}
			
			//userAdress
			if (isset($user['userAdress'])){
				$bike['userAdress'] = $user['userAdress']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$bike['userTel2'] = $user['userTel2']; 
			}
			
			//userID
			if (isset($user['userID'])){
				$bike['userID'] = $user['userID']; 
			}
		}
		
		return $bike;
	}

	
	/**
	 * This function filter all relevant data from the imported data set and fill a corresponding structure of truck table
	 */
	private function transTrovit2TruckStruc($p){
		$truck = array();
		$lang = $this -> lang;
		$truck['truckModelVar'] = '';
		$truck['truckHSN'] = '';
		$truck['truckTSN'] = '';
		$truck['truckFIN'] = '';
		$truck['truckLocCountry'] = 'DE';
		
		//truckBrandID
		if (isset($p['D'])){
			$truckBrand = db_selTruckBrand(array('brandNameL' => strtolower($p['D'])
											//, 'print' => true
											));
			if (($truckBrand != false) && is_array($truckBrand) && (count($truckBrand) > 0)){
				$truckBrand = $truckBrand[0];
				$truck['truckBrandID'] = $truckBrand['truckBrandID'];
				$truck['truckBrand'] = $truck['truckBrandID'];
				
				//truckModelID
				if (isset($p['E'])){
					$truckModel = db_selTruckModel(array('truckModelNameL' => $p['E']
													, 'truckBrandID' => $truckBrand['truckBrandID']
													//, 'p' => true
													));
					if (($truckModel != false) && is_array($truckModel) && (count($truckModel) > 0)){
						$truckModel = $truckModel[0];
						$truck['truckModel'] = $truckModel['truckModelID'];
						$truck['truckModelID'] = $truckModel['truckModelID'];
					}
				}
			}
		}
		
		//truckPrice
		if (isset($p['P']) && is_numeric($p['P'])){
			$truck['truckPrice'] = $p['P'];
		}
		
		//truckPriceType
		$truck['truckPriceType'] = '0';
		
		//truckPriceCurr
		if (isset($p['L'])){
			//see $lang['TXT_74'] for all currencies
			if (strtolower($p['L']) == 'eur'){
				$truck['truckPriceCurr'] = '0';
			}
			elseif (strtolower($p['L']) == 'rubel'){
				$truck['truckPriceCurr'] = 2;
			}
		}
		
		//truckKM
		if (isset($p['O']) && is_numeric($p['O'])){
			$truck['truckKM'] = $p['O'];
					
			//truckKMType -> see var. $lang['TXT_75']
			$truck['truckKMType'] = '0';
		}
		
		//truckPower
		if (isset($p['T']) && is_numeric($p['T'])){
			$truck['truckPower'] = $p['T'];
			
			//truckPowerType -> see var. $lang['TXT_72']
			$truck['truckPowerType'] = '0';
		}
		
		//truckEZM, truckEZY
		if (isset($p['N'])){
			$mmyyyy = explode('.', $p['N']);
			if (isset($mmyyyy[0])){
				$truck['truckEZM'] = $mmyyyy[0]; //truckEZM
			}
			if (isset($mmyyyy[1])){
				$truck['truckEZY'] = $mmyyyy[1]; //truckEZY
			}
		}
		
		//truckTUVM, truckTUVY, truckAUM, truckAUY
		if (isset($p['Y'])){
			$mmyyyy = explode('.', $p['Y']);
			if (isset($mmyyyy[0])){
				$truck['truckTUVM'] = $mmyyyy[0]; //truckTUVM
				$truck['truckAUM'] = $mmyyyy[0]; //truckAUM
			}
			if (isset($mmyyyy[1])){
				$truck['truckTUVY'] = $mmyyyy[1]; //truckTUVY
				$truck['truckAUY'] = $mmyyyy[1]; //truckAUY
			}
		}
		
		//truckShift -> see var. $lang['V_SHIFT']
		if (isset($p['H'])){
			//Manuel?
			if (stristr($p['H'], 'hand') != false){
				$truck['truckShift'] = '0';
			}
			//Automatik?
			elseif (stristr($p['H'], 'auto') != false){
				$truck['truckShift'] = 1;
			}
		}
		
		//truckWeight
		if (isset($p['X']) && is_numeric($p['X'])){
			$truck['truckWeight'] = $p['X'];
		}
		
		//truckCyl
		if (isset($p['U']) && is_numeric($p['U'])){
			$truck['truckCyl'] = $p['U'];
		}
		
		//truckCub
		if (isset($p['S']) && is_numeric($p['S'])){
			$truck['truckCub'] = $p['S'];
		}
		
		//truckDoor -> see var. $lang['truck_DOOR']
		if (isset($p['Q']) && is_numeric($p['Q'])){
			if ($p['Q'] == 2){
				$truck['truckDoor'] = '0';				
			}elseif ($p['Q'] == 4){
				$truck['truckDoor'] = 1;
			}
		}
		
		//truckUseIn
		if (isset($p['BQ']) && is_numeric(str_replace(',', '.', $p['BQ']))){
			$truck['truckUseIn'] = str_replace(',', '.', $p['BQ']);
		}
		
		//truckUseOut
		if (isset($p['BR']) && is_numeric(str_replace(',', '.', $p['BR']))){
			$truck['truckUseOut'] = str_replace(',', '.', $p['BR']);
		}
		
		//truckCO2
		if (isset($p['BT']) && is_numeric(str_replace(',', '.', $p['BT']))){
			$truck['truckCO2'] = str_replace(',', '.', $p['BT']);
		}
		
		//truckState -> see var. $lang['V_STATE']
		$as2Cat = $p['G'];
		if (isset($p['AE']) && ($p['AE'] == 1)){
			//Unfallfahrzeug
			$truck['truckState'] = 4;
		}elseif (isset($as2cat) && (stristr($as2cat, 'gebraucht') != false)){
			//Gebruachtwagen
			$truck['truckState'] = 2;
		}elseif (isset($as2cat) && (stristr($as2cat, 'jahres') != false)){
			//Jahreswagen
			$truck['truckState'] = 1;
		}elseif (isset($as2cat) && (stristr($as2cat, 'neu') != false)){
			//Neuwagen
			$truck['truckState'] = '0';
		}elseif (isset($as2cat) && (stristr($as2cat, 'vorf') != false)){
			//Vorführwagen
			$truck['truckState'] = 5;
		}elseif (isset($as2cat) && (stristr($as2cat, 'oldtim') != false)){
			//Oldtimer
			$truck['truckState'] = 6;
		}
		
		//truckCat -> see var. $lang['truck_CAT']
		if (isset($p['I'])){
			$truckCat = $this -> detCat(array_merge($p, array('vType' => System_Properties::TRUCK_ABRV)));
			if (($truckCat != false) && isset($truckCat['truckCat'])){
				$truck['truckCat'] = $truckCat['truckCat'];
			}
		}
		
		//truckFuel -> see var. $lang['V_FUEL']
		if (isset($p['M'])){
			if (stristr($p['M'], 'benzi') != false){				
				//Benzin
				$truck['truckFuel'] = '0';
			}elseif (stristr($p['M'], 'diese') != false){
				//diesel
				$truck['truckFuel'] = 1;
			}elseif (stristr($p['M'], 'gas') != false){
				//GAS
				$truck['truckFuel'] = 8;
			}elseif (stristr($p['M'], 'ethano') != false){
				//ethanol
				$truck['truckFuel'] = 4;
			}elseif (stristr($p['M'], 'elekt') != false){
				//elektro
				$truck['truckFuel'] = 2;
			}elseif (stristr($p['M'], 'lpg') != false){
				//LPG GAs
				$truck['truckFuel'] = 3;
			}elseif (stristr($p['M'], 'cng') != false){
				//CNG Gas
				$truck['truckFuel'] = 6;
			}elseif (stristr($p['M'], 'hybrid') != false){
				//Hybrid
				$truck['truckFuel'] = 7;
			}
		}
		
		//truckClr -> see var. $lang['V_CLR']
		if (isset($p['CN'])){
			foreach ($lang['V_CLR'] as $key => $kVal){
				if (stristr($kVal, $p['CN']) != false){
					$truck['truckClr'] = $key;
					break;
				}
			}
		}
		
		//truckClrMet
		if (isset($p['K']) && ($p['K'] == 1)){
			$truck['truckClrMet'] = 1;
		}
		
		//truckEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset($p['CI'])){
			if (stristr($p['CI'], 1) != false){
				//EURO 1
				$truck['truckEmissionNorm'] = 1;
			}elseif (stristr($p['CI'], 2) != false){
				//EURO 2
				$truck['truckEmissionNorm'] = 2;
			}elseif (stristr($p['CI'], 3) != false){
				//EURO 3
				$truck['truckEmissionNorm'] = 3;
			}elseif (stristr($p['CI'], 4) != false){
				//EURO 4
				$truck['truckEmissionNorm'] = 4;
			}elseif (stristr($p['CI'], 5) != false){
				//EURO 5
				//$truck['truckEmissionNorm'] = 5;
			}elseif (stristr($p['CI'], 6) != false){
				//EURO 6
				//$truck['truckEmissionNorm'] = 5;
			}
		}
		
		//truckEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset($p['CJ'])){
			if (stristr($p['CJ'], 'kein') != false){
				//Keine
				$truck['truckEcologicTag'] = '0';
			}elseif (stristr($p['CJ'], 'rot') != false){
				//ROT 
				$truck['truckEcologicTag'] = 1;
			}elseif (stristr($p['CJ'], 'gelb') != false){
				//Gelb
				$truck['truckEcologicTag'] = 2;
			}elseif (stristr($p['CJ'], 'grün') != false){
				//Grün
				$truck['truckEcologicTag'] = 3;
			}
		}
		
		//truckKlima -> see var. $lang['V_KLIMA']
		if (isset($p['AF']) && ($p['AF'] == 1)){
			//Klima
			$truck['truckKlima'] = '0';
		}elseif (isset($p['AG']) && ($p['AG'] == 1)){
			//Klimaautomatik
			$truck['truckKlima'] = 1;
		}
		
		//truckDesc
		if (isset($p['BG'])){
			$truck['truckDesc'] = $p['BG'];
		}
		
		//userAdsLength
		$truck['userAdsLength'] = 12;
		
		$user = null;
		if (isset($this -> userNS -> userData)){
			$user = $this -> userNS -> userData;
		}elseif ($this -> userData != null){
			$user = $this -> userData;
		}
		
		if ($user != null){		
			
			//userAds -> see var. $lang['TXT_33']
			$truck['userAds'] = '-1';
			if (isset($user['userMode']) && ($user['userMode'] != 3)){
				$truck['userAds'] = $user['userMode'];				
			}
			
			//userFirm
			if (isset($user['userFirm'])){
				$truck['userFirm'] = $user['userFirm']; 
			}
			
			//userNName
			if (isset($user['userNName'])){
				$truck['userNName'] = $user['userNName']; 
			}
			
			//userVName
			if (isset($user['userVName'])){
				$truck['userVName'] = $user['userVName']; 
			}
			
			//userEMail
			if (isset($user['userEMail'])){
				$truck['userEMail'] = $user['userEMail']; 
			}
			
			//userPLZ
			if (isset($user['userPLZ'])){
				$truck['userPLZ'] = $user['userPLZ'];
				$truck['truckLocPLZ'] = $user['userPLZ']; 
			}
			
			//userOrt
			if (isset($user['userOrt'])){
				$truck['userOrt'] = $user['userOrt'];
				$truck['truckLocOrt'] = $user['userOrt']; 
			}
			
			//userTel1
			if (isset($user['userTel1'])){
				$truck['userTel1'] = $user['userTel1']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$truck['userTel2'] = $user['userTel2']; 
			}
			
			//userAdress
			if (isset($user['userAdress'])){
				$truck['userAdress'] = $user['userAdress']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$truck['userTel2'] = $user['userTel2']; 
			}
			
			//userID
			if (isset($user['userID'])){
				$truck['userID'] = $user['userID']; 
			}
		}
		
		return $truck;
	}

	
	private function detCat($p){
		$ret = false;
		$lang = $this -> lang;
		if (isset($p['vType']) && ($p['vType'] == System_Properties::CAR_ABRV)){
			if (isset($p['I']) && !is_null($p['I']) 
				&& is_array($this -> carCat) && isset($lang['V_CAT'])){
				$as24Cat = $p['I'];
				foreach($this -> carCat as $key=>$kVal){
					if (isset($lang['V_CAT'][$kVal['vcatID']])){
						$vcatName = $lang['V_CAT'][$kVal['vcatID']];
						if(stristr($as24Cat, 'bus') && stristr($vcatName, 'bus')){
							//Kleinbus
							$ret['carCat'] = $kVal['carCatID'];
						}
						elseif (stristr($as24Cat, 'cab') && stristr($vcatName, 'cab')){
							//Cabrio
							$ret['carCat'] = $kVal['carCatID'];
						}
						elseif (stristr($as24Cat, 'coup') && stristr($vcatName, 'coup')){
							//Coupe
							$ret['carCat'] = $kVal['carCatID'];
						}
						elseif (stristr($as24Cat, 'ndewagen')  && stristr($vcatName, 'ndewagen')){
							//Geländewagen
							$ret['carCat'] = $kVal['carCatID'];
						}
						elseif ((stristr($as24Cat, 'kombi') && stristr($vcatName, 'kombi')) 
								|| (stristr($as24Cat, 'van')  && stristr($vcatName, 'van'))){
							//Kombi/Van
							$ret['carCat'] = $kVal['carCatID'];
						}
						elseif (stristr($as24Cat, 'liefer')  && stristr($vcatName, 'liefer')){
							//Lieferwagen
							$ret['carCat'] = $kVal['carCatID'];
						}
					}
				}
			}
		}elseif (isset($p['vType']) && ($p['vType'] == System_Properties::BIKE_ABRV)){
			if (isset($p['I']) && !is_null($p['I']) 
				&& is_array($this -> bikeCat) && isset($lang['V_CAT'])){
				$as24Cat = $p['I'];
				foreach($this -> bikeCat as $key=>$kVal){
					if (isset($lang['V_CAT'][$kVal['vcatID']])){
						$vcatName = $lang['V_CAT'][$kVal['vcatID']];
						if( (stristr($as24Cat, 'chopp') && stristr($vcatName, 'chopp'))
							|| (stristr($as24Cat, 'cruis') && stristr($vcatName, 'cruis')) ){
							//Chopper/Cruiser
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'endur') && stristr($vcatName, 'endur')){
							//Enduro
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'gespan') && stristr($vcatName, 'gespan')){
							//Gespann
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'leichtkraft')  && stristr($vcatName, 'leichtkraft')){
							//Leichtkraftrad
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif ((stristr($as24Cat, 'dirt') && stristr($vcatName, 'dirt')) 
								&& (stristr($as24Cat, 'bike')  && stristr($vcatName, 'bike'))){
							//Dirty Bike
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif ( (stristr($as24Cat, 'mofa')  && stristr($vcatName, 'mofa') )
								|| (stristr($as24Cat, 'moped')  && stristr($vcatName, 'moped')) ){
							//Mofa/Moped
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'naked')  && stristr($vcatName, 'naked')){
							//Naked
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'motorrad')  && stristr($vcatName, 'motorrad')){
							//Motorrad
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif ((stristr($as24Cat, 'pocket') && stristr($vcatName, 'pocket')) 
								&& (stristr($as24Cat, 'bike')  && stristr($vcatName, 'bike'))){
							//Pocket Bike
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'quad')  && stristr($vcatName, 'quad')){
							//Quad
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'rallye')  && stristr($vcatName, 'rallye')){
							//Rallye
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'sport')  && stristr($vcatName, 'sport')){
							//Sportler
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif ((stristr($as24Cat, 'street') && stristr($vcatName, 'street')) 
								&& (stristr($as24Cat, 'fight')  && stristr($vcatName, 'fight'))){
							//Streetfighter
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif ((stristr($as24Cat, 'super') && stristr($vcatName, 'super')) 
								&& (stristr($as24Cat, 'moto')  && stristr($vcatName, 'moto'))){
							//Super Moto
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'tour')  && stristr($vcatName, 'tour')){
							//Tourer
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
						elseif (stristr($as24Cat, 'rolle')  && stristr($vcatName, 'rolle')){
							//Roller
							$ret['bikeCat'] = $kVal['bikeCatID'];
						}
					}
				}
			}
		}elseif (isset($p['vType']) && ($p['vType'] == System_Properties::TRUCK_ABRV)){
			
		}
		return $ret;
	}
	
	/**
	 * Handle ZIP file import
	 */
	private function handleZIPFileImp($p){		
		$fileName = null;
		if (isset($p['FILE_NAME'])){
			$fileName = $p['FILE_NAME'];
		}
		
		if ($fileName != null){
			$fileNameDir = dirname($fileName);
			$zipXMLFile = array('DIR' => $fileNameDir
								, 'FILE' => array()
								);
			$zipPicFile = array('DIR' => $fileNameDir
								, 'FILE' => array()
								);
			
			$zipFile = new ZipArchive();
			$zipFile -> open($fileName);
			
			//sort file names
			for($i = '0'; $i < $zipFile -> numFiles; $i++){
				$zipFileNamePath = $zipFile -> getNameIndex($i);
				$zipFileName = basename($zipFileNamePath);
				$zipFileNameComp = explode('.', $zipFileName);
				if (isset($zipFileNameComp[count($zipFileNameComp)-1])){
					$zipFileExtension = strtoupper($zipFileNameComp[count($zipFileNameComp)-1]);
				
					if ($zipFileExtension == 'XML'){
						array_push($zipXMLFile['FILE'], $zipFileName);			
					}elseif (in_array(strtolower($zipFileExtension), System_Properties::$PIC_EXT)){
						array_push($zipPicFile['FILE'], $zipFileName);
					}
				}
			}
			$zipFile -> extractTo($fileNameDir, array_merge($zipXMLFile['FILE'], $zipPicFile['FILE']));
			$zipFile -> close();
			//process XML file
			foreach ($zipXMLFile['FILE'] AS $key => $csvFileName){
				$this -> handleXMLFileImp(array('FILE_NAME' => $fileNameDir.'/'.$csvFileName
												, 'PIC_NAME' => $zipPicFile
												, 'vType' => $p['vType']
												));
			}
			/*
			//delete all uploaded files in the ftp folder
			$folderFiles = scandir($fileNameDir);
			foreach ($folderFiles as $key => $fileName){				
				if (($fileName != '.') && ($fileName != '..')){
					$fileName = $fileNameDir.'/'.$fileName;
					if (is_file($fileName)){
						unlink($fileName);					
					}
					elseif (is_dir($fileName)){
						System_Properties::rec_rmdir($fileName);
					}
				}
			}*/
		}
	}
	
	/**
	 * Check if all mandatory fields are specified
	 */
	private function checkManFieldsAS24Action($p){
		$ret = array();
		$lang = $this -> lang;
		$error = null;
		$data = null;
		if (isset($p['DATA'])){
			$data = $p['DATA'];
		}
		
		if ($data != null){
			$fInt = new FilterMySQLInt();
			$fIntU = new FilterMySQLInt(array('unsigned' => true));
			$fString1 = new FilterStringXX(1);
			$fString10 = new FilterStringXX(10);
			$fString50 = new FilterStringXX(50);
			$fString30 = new FilterStringXX(30);
			$fString25 = new FilterStringXX(25);
			$fString80 = new FilterStringXX(80);
			$fString3000 = new FilterStringXX(3000);
			
			$fAS24EZ = new FilterValidAS24EZ();
			//Check as24 customer ID
			if (!isset($data['A']) || ($fIntU -> filter($data['A']) == false)){
				$error = $lang['ERR_18'].$lang['ERR_20'];
			}
			//Check as24 brand
			elseif (!isset($data['D']) || (db_selCarBrand(array('brandNameL' => strtolower($data['D']))) == false)){
				$error = $lang['ERR_18'].$lang['ERR_19'];
			}
			//check modell
			elseif (!isset($data['E'])){
				$error = $lang['ERR_18'].$lang['ERR_21'];
			}
			//check vehicle category
			elseif (!isset($data['I'])){
				$error = $lang['ERR_18'].$lang['ERR_22'];
			}
			//check currency
			elseif (!isset($data['L'])){
				$error = $lang['ERR_18'].$lang['ERR_23'];
			}
			//check fuel
			elseif (!isset($data['M'])){
				$error = $lang['ERR_18'].$lang['ERR_24'];
			}
			//check EZ
			elseif (!isset($data['N']) || ($fAS24EZ -> filter($data['N']) == false)){
				$error = $lang['ERR_18'].$lang['ERR_25'];
			}
			//check KM
			elseif (!isset($data['O']) || ($fInt -> filter($data['O']) == false)){
				$error = $lang['ERR_18'].$lang['ERR_26'];
			}
			//check price
			elseif (!isset($data['P']) || ($fInt -> filter($data['P']) == false)){
				$error = $lang['ERR_18'].$lang['ERR_27'];
			}
			//check KW
			elseif (!isset($data['T']) || ($fInt -> filter($data['T']) == false)){
				$error = $lang['ERR_18'].$lang['ERR_28'];
			}
			//check color
			elseif (!isset($data['CN'])){
				$error = $lang['ERR_18'].$lang['ERR_29'];
			}
			else{
				/*
				//check AS24 offering number
				$data['B'] = $fString50 -> filter($data['B']);
				
				//CHECK version
				$data['F'] = $fString30 -> filter($data['F']);
				
				//CHECK exterior colour
				$data['J'] = $fString25 -> filter($data['J']);
								
				//check exterior colour metallic
				$data['K'] = $fString1 -> filter($data['K']);
				
				//check door
				$data['Q'] = $fString10 -> filter($data['Q']);				
				
				//check seat
				$data['R'] = $fString10 -> filter($data['R']);				
				
				//check seat
				$data['R'] = $fString10 -> filter($data['R']);
				*/
				
			}
		}
		
		if ($data != null){
			$ret['DATA'] = $data;
		}
		
		if ($error != null){
			$ret['ERROR'] = $error;
		}
		return $ret;		
	}
	
	/**
	 * Transform the autoscout24 CSV data to 
	 */	
	private function fillAssoziativeArr($p){
		$fString10 = new FilterStringXX(10);
		$fString20 = new FilterStringXX(20);
		$fString100 = new FilterStringXX(100);
		
		$fIsEmpty = new FilterIsEmptyString();
		
		//price
		if(isset($p['price']) && ($p['price'] != null) && ($fIsEmpty -> filter($p['price']) == false)){
		  $p['price'] = $fString100 -> filter($p['price']);
		}else{
		  $p['price'] = null;
		}
		
		//postcode
		if(isset($p['postcode']) && ($p['postcode'] != null) && ($fIsEmpty -> filter($p['postcode']) == false)){
		  $p['postcode'] = $fString100 -> filter($p['postcode']);
		}else{
		  $p['postcode'] = null;
		}
		
		//year
		if(isset($p['year']) && ($p['year'] != null) && ($fIsEmpty -> filter($p['year']) == false)){
		  $p['year'] = $fString10 -> filter($p['year']);
		}else{
		  $p['year'] = null;
		}
		
		//mileage
		if(isset($p['mileage']) && ($p['mileage'] != null) && ($fIsEmpty -> filter($p['mileage']) == false)){
		  $p['mileage'] = $fString10 -> filter($p['mileage']);
		}else{
		  $p['mileage'] = null;
		}
		
		//doors
		if(isset($p['doors']) && ($p['doors'] != null) && ($fIsEmpty -> filter($p['doors']) == false)){
		  $p['doors'] = $fString10 -> filter($p['doors']);
		}else{
		  $p['doors'] = null;
		}
		
		//seats
		if(isset($p['seats']) && ($p['seats'] != null) && ($fIsEmpty -> filter($p['seats']) == false)){
		  $p['seats'] = $fString10 -> filter($p['seats']);
		}else{
		  $p['seats'] = null;
		}
		
		//engine_size
		if(isset($p['engine_size']) && ($p['engine_size'] != null) && ($fIsEmpty -> filter($p['engine_size']) == false)){
		  $p['engine_size'] = $fString10 -> filter($p['engine_size']);
		}else{
		  $p['engine_size'] = null;
		}
		
		//cylinders
		if(isset($p['cylinders']) && ($p['cylinders'] != null) && ($fIsEmpty -> filter($p['cylinders']) == false)){
		  $p['cylinders'] = $fString10 -> filter($p['cylinders']);
		}else{
		  $p['cylinders'] = null;
		}
		
		//gears
		if(isset($p['gears']) && ($p['gears'] != null) && ($fIsEmpty -> filter($p['gears']) == false)){
		  $p['gears'] = $fString10 -> filter($p['gears']);
		}else{
		  $p['gears'] = null;
		}
		
		//power
		if(isset($p['power']) && ($p['power'] != null) && ($fIsEmpty -> filter($p['power']) == false)){
		  $p['power'] = $fString10 -> filter($p['power']);
		}else{
		  $p['power'] = null;
		}
		
		//date
		if(isset($p['date']) && ($p['date'] != null) && ($fIsEmpty -> filter($p['date']) == false)){
		  $p['date'] = $fString20 -> filter($p['date']);
		}else{
		  $p['date'] = null;
		}
		
		//expiration_date
		if(isset($p['expiration_date']) && ($p['expiration_date'] != null) && ($fIsEmpty -> filter($p['expiration_date']) == false)){
		  $p['expiration_date'] = $fString20 -> filter($p['expiration_date']);
		}else{
		  $p['expiration_date'] = null;
		}
		
		//expiration_date
		if(isset($p['is_new']) && ($p['is_new'] != null) && ($fIsEmpty -> filter($p['is_new']) == false) && ($p['is_new'] == true)){
		  $p['is_new'] = 1;
		}else{
		  $p['is_new'] = false;
		}
		
		return $p;		
	}
	
	public function handleDatexExp($p){
		return $p;
	}
	private function transStruc2AS24($p){
		$vAd = $this -> getInitialAS24Struc();
		$lang = $this -> lang;
		
		$vAd['A'] = '';
		
		//vID
		if (isset($p['vID'])){
			$vAd['B'] = $p['vID'];
		}
		
		//vBrand
		if (isset($p['vBrandName'])){
			$vAd['D'] = $p['vBrandName'];
		}
		
		//vModelName
		if (isset($p['vModelName'])){
			$vAd['E'] = $p['vModelName'];
		}
		
		//vPrice
		if (isset($p['vPrice'])){
			$vAd['P'] = $p['vPrice'];
		}
		
		//carPriceType
		//$vAd['carPriceType'] = '0';
		
		//vPriceCurr
		if (isset($p['vPriceCurr'])){
			//EURO
			if ($p['vPriceCurr'] == '0'){
				$vAd['L'] = 'Euro';
			}
			//Rubel
			elseif ($p['vPriceCurr'] == 1){
				$vAd['L'] = 'Rubel';
			}
		}
		
		//vKM
		if (isset($p['vKM']) && is_numeric($p['vKM'])){
			$vAd['O'] = $p['vKM'];
					
			//carKMType -> see var. $lang['TXT_75']
			//$vAd['carKMType'] = '0';
		}
		
		//vPower
		if (isset($p['vPower']) && is_numeric($p['vPower'])){
			$vAd['T'] = $p['vPower'];
			
			//carPowerType -> see var. $lang['TXT_72']
			//$vAd['T'] = '0';
		}
		
		//vEZM, vEZY
		if (isset($p['vEZY']) && isset($p['vEZM']) && ($p['vEZY'] > 0) && ($p['vEZM'] > 0)){			
			$vAd['N'] = (strlen($p['vEZM']) < 2 ? '0':'').$p['vEZM'].'.'.$p['vEZY']; 
		}
		
		//vTUVM, vTUVY
		if (isset($p['vTUVM']) && isset($p['vTUVY']) && ($p['vTUVM'] > 0) && ($p['vTUVY'] > 0) ){	
			$vAd['Y'] = (strlen($p['vTUVM']) < 2 ? '0':'').$p['vTUVM'].'.'.$p['vTUVY']; 
		}
		//vAUM, vAUY
		elseif (isset($p['vAUM']) && isset($p['vAUY']) && ($p['vAUM'] > 0) && ($p['vAUY'] > 0)){
			$vAd['Y'] = (strlen($p['vAUM']) < 2 ? '0':'').$p['vAUM'].'.'.$p['vAUY']; 
		}
		
		//vShift -> see var. $lang['V_SHIFT']
		if (isset($p['vShift'])){
			//Manuel?
			if ($p['vShift'] == '0'){
				$vAd['H'] = 'Handschaltung';
			}
			//Automatik?
			elseif ($p['vShift'] == 1){
				$vAd['H'] = 'Automatik';
			}
		}
		
		//vWeight
		if (isset($p['vWeight']) && is_numeric($p['vWeight'])){
			$vAd['X'] = $p['vWeight'];
		}
		
		//vCyl
		if (isset($p['vCyl']) && is_numeric($p['vCyl'])){
			$vAd['U'] = $p['vCyl'];
		}
		
		//vCub
		if (isset($p['vCub']) && is_numeric($p['vCub'])){
			$vAd['S'] = $p['vCub'];
		}
		
		//vDoor -> see var. $lang['CAR_DOOR']
		if (isset($p['vDoor']) && is_numeric($p['vDoor'])){
			if ($p['vDoor'] == '0'){
				$vAd['Q'] = 2;				
			}elseif ($p['vDoor'] == 1){
				$vAd['Q'] = 4;
			}
		}
		
		//vUseIn
		if (isset($p['vUseIn']) && is_numeric($p['vUseIn'])){
			$vAd['BQ'] = str_replace('.', ',', $p['vUseIn']);
		}
		
		//vUseOut
		if (isset($p['vUseOut']) && is_numeric($p['vUseOut'])){
			$vAd['BR'] = str_replace('.', ',', $p['vUseOut']);
		}
		
		//vCO2
		if (isset($p['vCO2']) && is_numeric($p['vCO2'])){
			$vAd['BT'] = str_replace('.', ',', $p['vCO2']);
		}
		
		//vState
		if (isset($p['vState']) && ($p['vState'] == 4)){
			//Unfallfahrzeug
			$vAd['AE'] = 1;
		}
		elseif (isset($p['vState']) && ($p['vState'] == 2)){
			//Gebrauchtwagen
			$vAd['G'] = 'Gebrauchtwagen';
		}
		elseif (isset($p['vState']) && ($p['vState'] == 1)){
			//Jahreswagen
			$vAd['G'] = 'Jahreswagen';
		}
		elseif (isset($p['vState']) && ($p['vState'] == '0')){
			//Neuwagen
			$vAd['G'] = 'Neuwagen';
		}
		elseif (isset($p['vState']) && ($p['vState'] == 5)){
			//Vorführwagen
			$vAd['G'] = 'Vorführwagen';
		}
		elseif (isset($p['vState']) && ($p['vState'] == 6)){
			//Oldtimer
			$vAd['G'] = 'Oldtimer';
		}
		
		
		//vCat -> see var. $lang['CAR_CAT']
		if (isset($p['vCat']) && isset($lang['V_CAT'][$p['vCat']])){		
			$vcatName = $lang['V_CAT'][$p['vCat']];
			if(stristr($vcatName, 'bus')){
				//Kleinbus
				$vAd['I'] = 'Bus';
			}
			elseif(stristr($vcatName, 'cabr')){
				//Cabrio
				$vAd['I'] = 'Cabrio';
			}
			elseif(stristr($vcatName, 'ndewagen')){
				//Geländewagen
				$vAd['I'] = 'Geländewagen';
			}
			elseif(stristr($vcatName, 'kombi') || stristr($vcatName, 'van')){
				//Kombi/Van
				$vAd['I'] = 'Kombi/Van';
			}
			elseif(stristr($vcatName, 'liefer')){
				//Lieferewagen
				$vAd['I'] = 'Lieferwagen';
			}
			
			if (($vAd['I'] == '') && isset($p['vDoor'])){
				switch ($p['vDoor']) {
					case 0:	$vAd['I'] = '2/3-Türer';
							break;
					case 1:	$vAd['I'] = '4/5-Türer';
							break;
				}				
			}					
			
			if (($vAd['I'] == '')){
				$vAd['I'] = 'Sonstiges';
			}
		}
		
		//vFuel -> see var. $lang['V_FUEL']
		if (isset($p['vFuel'])){
			switch ($p['vFuel']){
				//Benzin
				case 0:	$vAd['M'] = 'Benzin';
						break;
				//Diesel
				case 1:	$vAd['M'] = 'Diesel';
						break;
				//Gas
				case 8:	$vAd['M'] = 'Gas';
						break;
				//Ethanol
				case 4:	$vAd['M'] = 'Ethanol';
						break;
				//Elektro
				case 5:	$vAd['M'] = 'Elektro';
						break;
				//LPG Gas
				case 3:	$vAd['M'] = 'LPG';
						break;
				//CNG Gas
				case 6:	$vAd['M'] = 'CNG';
						break;
				//Hybrid
				case 7:	$vAd['M'] = 'Hybrid';
						break;
			}
		}
		
		//vClr -> see var. $lang['V_CLR']
		if (isset($p['vClr']) && isset($lang['V_CLR'][$p['vClr']])){
			$vClr = $lang['V_CLR'][$p['vClr']];
			
			if(stristr($vClr, 'beige')){
				$vAd['CN'] = 'Beige';
			}
			elseif (stristr($vClr, 'blau') && stristr($vClr, 'hell')){
				$vAd['CN'] = 'Hellblau';
			}
			elseif (stristr($vClr, 'blau')){
				$p['CN'] = 'Blau';
			}
			elseif (stristr($vClr, 'braun')){
				$vAd['CN'] = 'Braun';
			}
			elseif (stristr($vClr, 'bronze')){
				$vAd['CN'] = 'Bronze';
			}
			elseif (stristr($vClr, 'dunk') && stristr($vClr, 'rot')){
				$vAd['CN'] = 'Dunkel Rot';
			}
			elseif (stristr($vClr, 'gelb')){
				$vAd['CN'] = 'Gelb';
			}
			elseif (stristr($vClr, 'gold')){
				$vAd['CN'] = 'Gold';
			}
			elseif (stristr($vClr, 'grau') && stristr($vClr, 'hell')){
				$vAd['CN'] = 'Hellgrau';
			}
			elseif (stristr($vClr, 'grau')){
				$vAd['CN'] = 'Grau';
			}
			elseif (stristr($vClr, 'grün') && stristr($vClr, 'hell')){
				$vAd['CN'] = 'Hellgrün';
			}
			elseif (stristr($vClr, 'grün')){
				$vAd['CN'] = 'Grün';
			}
			elseif (stristr($vClr, 'orang')){
				$vAd['CN'] = 'Orange';
			}
			elseif (stristr($vClr, 'ro')){
				$vAd['CN'] = 'Rot';
			}
			elseif (stristr($vClr, 'schwar')){
				$vAd['CN'] = 'Schwarz';
			}
			elseif (stristr($vClr, 'silb')){
				$vAd['CN'] = 'Silber';
			}
			elseif (stristr($vClr, 'viole')){
				$vAd['CN'] = 'Violett';
			}
			elseif (stristr($vClr, 'weiß')){
				$vAd['CN'] = 'Weiß';
			}
		}
		
		//vClrMet
		if (isset($p['vClrMet']) && ($p['vClrMet'] == 1)){
			$vAd['K'] = 1;
		}
		
		//vEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset($p['vEmissionNorm'])){
			switch ($p['vEmissionNorm']){
				case 1:	$vAd['CI'] = 'EURO 1';
						break;
				case 2:	$vAd['CI'] = 'EURO 2';
						break;
				case 3:	$vAd['CI'] = 'EURO 3';
						break;
				case 4:	$vAd['CI'] = 'EURO 4';
						break;
				case 5:	$vAd['CI'] = 'EURO 5';
						break;
				case 6:	$vAd['CI'] = 'EURO 6';
						break;
			}
		}
		
		//vEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset($p['vEcologicTag'])){
			switch ($p['vEcologicTag']) {
				case 0: $vAd['CJ'] = 'keine';
						break;
				case 1: $vAd['CJ'] = 'Rot';
						break;
				case 2: $vAd['CJ'] = 'Gelb';
						break;
				case 3: $vAd['CJ'] = 'Grün';
						break;
			}
		}
		
		//vKlima -> see var. $lang['V_KLIMA']
		if (isset($p['vKlima'])){
			switch ($p['vKlima']){
				//Klima
				case 0:	$vAd['AF'] = 1;
						break;
				//Klimaautomatik
				case 1: $vAd['AG'] = 1;
						break;
			}
		}
		
		//vDesc
		if (isset($p['vDesc'])){
			$vAd['BG'] = $p['vDesc'];
		}
		
		//userAdsLength
		//$vAd['userAdsLength'] = 4;
		
		/*
		$user = null;
		if (isset($this -> userNS -> userData)){
			$user = $this -> userNS -> userData;
			
			//userAds -> see var. $lang['TXT_33']
			$vAd['userAds'] = '-1';
			if (isset($user['userMode']) && ($user['userMode'] != 3)){
				$vAd['userAds'] = $user['userMode'];				
			}
			
			//userFirm
			if (isset($user['userFirm'])){
				$vAd['userFirm'] = $user['userFirm']; 
			}
			
			//userNName
			if (isset($user['userNName'])){
				$vAd['userNName'] = $user['userNName']; 
			}
			
			//userVName
			if (isset($user['userVName'])){
				$vAd['userVName'] = $user['userVName']; 
			}
			
			//userEMail
			if (isset($user['userEMail'])){
				$vAd['userEMail'] = $user['userEMail']; 
			}
			
			//userPLZ
			if (isset($user['userPLZ'])){
				$vAd['userPLZ'] = $user['userPLZ'];
				$vAd['carLocPLZ'] = $user['userPLZ']; 
			}
			
			//userOrt
			if (isset($user['userOrt'])){
				$vAd['userOrt'] = $user['userOrt'];
				$vAd['carLocOrt'] = $user['userOrt']; 
			}
			
			//userTel1
			if (isset($user['userTel1'])){
				$vAd['userTel1'] = $user['userTel1']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$vAd['userTel2'] = $user['userTel2']; 
			}
			
			//userAdress
			if (isset($user['userAdress'])){
				$vAd['userAdress'] = $user['userAdress']; 
			}
			
			//userTel2
			if (isset($user['userTel2'])){
				$vAd['userTel2'] = $user['userTel2']; 
			}
			
			//userID
			if (isset($user['userID'])){
				$vAd['userID'] = $user['userID']; 
			}
		}*/
		
		return $vAd;
	}
	
	private function getInitialTrovitStruc(){
		$ret = array('id' => null
					, 'url' => null
					, 'title' => null
					, 'content' => null
					, 'price' => null
					, 'car_type' => null
					, 'dealer' => null
					, 'neighborhood' => null
					, 'city_area' => null
					, 'city' => null
					, 'region' => null
					, 'postcode' => null
					, 'vin_database' => null
					, 'make' => null
					, 'model' => null
					, 'color' => null
					, 'year' => null
					, 'mileage' => null
					, 'doors' => null
					, 'seats' => null
					, 'warranty' => null
					, 'engine_size' => null
					, 'cylinders' => null
					, 'fuel' => null
					, 'transmission' => null
					, 'gears' => null
					, 'power' => null
					, 'pictures' => null
					, 'date' => null
					, 'expiration_date' => null
					, 'is_new' => null
					, 'trovitNew' => null
					, 'userID' =>  null
					, 'vID' => null
					, 'vType' => null
					);
		
		return $ret;
	}
}
/**
 * 	Assignment of letters to the particular number in the interface description
	A = 1
	B = 2
	C = 3
	D = 4
	E = 5
	F = 6
	G = 7
	H = 8
	I = 9
	J = 10
	K = 11
	L = 12
	M = 13
	N = 14
	O = 15
	P = 16
	Q = 17
	R = 18
	S = 19
	T = 20
	U = 21
	V = 22
	W = 23
	X = 24
	Y = 25
	Z = 26
	AA = 27
	AB = 28
	AC = 29
	AD = 30
	AE = 31
	AF = 32
	AG = 33
	AH = 34
	AI = 35
	AJ = 36
	AK = 37
	AL = 38
	AM = 39
	AN = 40
	AO = 41
	AP = 42
	AQ = 43
	AR = 44
	AS = 45
	AT = 46
	AU = 47
	AV = 48
	AW = 49
	AX = 50
	AY = 51
	AZ = 52
	BA = 53
	BB = 54
	BC = 55
	BD = 56
	BE = 57
	BF = 58
	BG = 59
	BH = 60
	BI = 61
	BJ = 62
	BK = 63
	BL = 64
	BM = 65
	BN = 66
	BO = 67
	BP = 68
	BQ = 69
	BR = 70
	BS = 71
	BT = 72
	BU = 73
	BV = 74
	BW = 75
	BX = 76
	BY = 77
	BZ = 78
	CA = 79
	CB = 80
	CC = 81
	CD = 82
	CE = 83
	CF = 84
	CG = 85
	CH = 86
	CI = 87
	CJ = 88
	CK = 89
	CL = 90
	CM = 91
	CN = 92
	CO = 93
	CP = 94
	CQ = 95
	CR = 96
	CS = 97
	CT = 98
	CU = 99
	CV = 100
	CW = 101
	CX = 102
	CY = 103
	CZ = 104
*/
?>