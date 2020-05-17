<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110619
 * Desc:		This class handles the autoscout24 data exchange
 *********************************************************************************/
include_once('classes/CL_DATEX_ABS.php');

include_once('default/models/default/db_insAS24.php');
include_once('default/models/default/db_selAS24.php');
include_once('default/models/default/db_updAS24.php');

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

class CL_TROVITPKW extends CL_DATEX_ABSTRACT{
	const DATEX_ID = 'AS24';
	
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
			//invoke CSV processing method
			elseif (strtolower($fileExtension) == 'csv'){
				$this -> handleCSVFileImp($p);
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
	private function handleCSVFileImp($p){		
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
			$fileHandler = fopen($fileName, 'r');
			while(($csvData = fgetcsv($fileHandler, null, ';')) !== false){
				$csvData = $fUTF8 -> filter($csvData);
				
				$csvData = $this -> fillAssoziativeArr($csvData);
				//$csvData = $this -> checkManFieldsAS24Action(array('DATA' => $csvData));
				$csvData = array('DATA'=>$csvData);
				if (!isset($csvData['error']) && isset($csvData['DATA'])){
					$csvData = $csvData['DATA'];
					$csvData['as24New'] = 1;
					//check if data set already exists
					$as24Data = db_selAS24(array( 'A' => $csvData['A']
												, 'B' => $csvData['B']
												, 'vType' => $p['vType']
												, 'userID' => $user['userID']
												//, 'print'=>true
												));
					if (($as24Data != false) && is_array($as24Data) && (count($as24Data) > 0)){
						$as24Data = $as24Data[0];
						$updAS24 = db_updAS24(array(System_Properties::SQL_SET => $csvData
												, System_Properties::SQL_WHERE => array('as24ID' => $as24Data['as24ID'])
												//, 'print'=>true
												));
						//array_push($this -> prot['INFO'], $lang['INFO_11'].$as24Data['B']);
					}else{
						$csvData['userID'] = $user['userID'];
						if (isset($p['vType'])){
							$csvData['vType'] = $p['vType'];
						}
						//$csvData['print'] = true;
						//print_r($csvData);
						$as24ID = db_insAS24($csvData);
						if (is_numeric($as24ID)){
							//array_push($this -> prot['INFO'], $lang['INFO_10'].$csvData['B']);
						}else{
							array_push($this -> prot['ERROR'], $lang['ERR_44'].': '.$csvData['B']);
						}
					}
				}else{
					array_push($this -> prot['ERROR'], $csvData['error']);
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
			$as24Data = db_selAS24(array('userID' => $user['userID']
										, 'as24New' => 1
										));
										
			if (($as24Data != false) && is_array($as24Data)){				
				foreach($as24Data as $key => $extData){
					//If vehicle ID is set then process an update
					if (isset($extData['vID']) && is_numeric($extData['vID'])){
						//Car
						if (isset($extData['vType']) && (strtolower($extData['vType']) == strtolower(System_Properties::CAR_ABRV))){
							$carDB = db_selCarAd(array('carID' => $extData['vID']));
									
							if( ($carDB != false) && is_array($carDB) && (count($carDB) > 0)){
								$carDB = $carDB[0];
								$car = $this -> transAS242CarStruc($extData);
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
										$this -> processAS24CarExtra($p);
									}
									//update pics
									$this -> processAdsPic($p);										
									array_push($this -> prot['INFO'], $lang['INFO_11'].$extData['B']);
								}
								db_updAS24(array(System_Properties::SQL_SET => array('as24New' => '0')
											, System_Properties::SQL_WHERE => array('as24ID' => $extData['as24ID'])
											));
							}
						}
						//BIKE					
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::BIKE_ABRV)){
							$bikeDB = db_selBikeAd(array('bikeID' => $extData['vID']));
												
							if( ($bikeDB != false) && is_array($bikeDB) && (count($bikeDB) > 0)){
								$bikeDB = $bikeDB[0];
								$bike = $this -> transAS242BikeStruc($extData);
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
								db_updAS24(array(System_Properties::SQL_SET => array('as24New' => '0')
											, System_Properties::SQL_WHERE => array('as24ID' => $extData['as24ID'])
											));
							}
						}	
						//Truck			
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::TRUCK_ABRV)){
							$truckDB = db_selTruckAd(array('truckID' => $extData['vID']));
												
							if( ($truckDB != false) && is_array($truckDB) && (count($truckDB) > 0)){
								$truckDB = $truckDB[0];
								$truck = $this -> transAS242TruckStruc($extData);
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
								db_updAS24(array(System_Properties::SQL_SET => array('as24New' => '0')
											, System_Properties::SQL_WHERE => array('as24ID' => $extData['as24ID'])
											));
							}
						}
					}
					//Process a new insertion
					else{
						$updAS24SQLSet = array('as24New' => '0'); 
						//Car
						if (isset($extData['vType']) && ($extData['vType'] == System_Properties::CAR_ABRV)){
							$car = $this -> transAS242CarStruc($extData);
							$car = $this -> filterCarData($car);
							if (!isset($car['error'])){
								$carID = db_insCarAds($car);
								if (($carID != false) && is_numeric($carID)){
									$updAS24SQLSet['vID'] = $carID;
									
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::CAR_ABRV
														, 'vID' => $carID);
									//update car extra
									$this -> processAS24CarExtra($p);
									
									//update pics
									$this -> processAdsPic($p);
									array_push($this -> prot['INFO'], $lang['INFO_10'].$extData['B']);
								}
							}
							
							if(count($updAS24SQLSet)> 0){
								db_updAS24(array(System_Properties::SQL_SET => $updAS24SQLSet
												, System_Properties::SQL_WHERE => array('as24ID' => $extData['as24ID'])
												));
							}							
						}
						//BIKE					
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::BIKE_ABRV)){
							$bike = $this -> transAS242BikeStruc($extData);
							$bike = $this -> filterBikeData($bike);
							//print_r($bike);echo '<br><br>';
							if (!isset($bike['error'])){
								$bikeID = db_insBikeAds($bike);
								if (($bikeID != false) && is_numeric($bikeID)){
									$updAS24SQLSet['vID'] = $bikeID;
									
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::BIKE_ABRV
														, 'vID' => $bikeID);
									//update bike extra
									//$this -> processAS24BikeExtra($p);
									
									//update pics
									$this -> processAdsPic($p);
									array_push($this -> prot['INFO'], $lang['INFO_10'].$extData['B']);
								}
							}
							
							if(count($updAS24SQLSet)> 0){
								db_updAS24(array(System_Properties::SQL_SET => $updAS24SQLSet
												, System_Properties::SQL_WHERE => array('as24ID' => $extData['as24ID'])
												));
							}						
						}
						//TRUCK					
						elseif (isset($extData['vType']) && ($extData['vType'] == System_Properties::TRUCK_ABRV)){
							$truck = $this -> transAS242TruckStruc($extData);
							$truck = $this -> filterTruckData($truck);
							//print_r($truck);echo '<br><br>';
							if (!isset($truck['error'])){
								$truckID = db_insTruckAds($truck);
								if (($truckID != false) && is_numeric($truckID)){
									$updAS24SQLSet['vID'] = $truckID;
									
									$p['EXT_DATA'] = $extData;
									$p['V_DATA'] = array('vType' => System_Properties::TRUCK_ABRV
														, 'vID' => $truckID);
									//update truck extra
									//$this -> processAS24TruckExtra($p);
									
									//update pics
									$this -> processAdsPic($p);
									array_push($this -> prot['INFO'], $lang['INFO_10'].$extData['B']);
								}
							}
							
							if(count($updAS24SQLSet)> 0){
								db_updAS24(array(System_Properties::SQL_SET => $updAS24SQLSet
												, System_Properties::SQL_WHERE => array('as24ID' => $extData['as24ID'])
												));
							}									
						}
					}
				}
			}			 
		}
	}
	
	/**
	 * This function extracts all vehicle extra information from a AS24 data set
	 */
	private function processAS24CarExtra($p = null){
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
	private function transAS242CarStruc($p){
		$car = array();
		$lang = $this -> lang;
		$car['carModelVar'] = '';
		$car['carHSN'] = '';
		$car['carTSN'] = '';
		$car['carFIN'] = '';
		$car['carLocCountry'] = 'DE';
		
		//carBrandID
		if (isset($p['D'])){
			$carBrand = db_selCarBrand(array('brandNameL' => strtolower($p['D'])
											//, 'print' => true
											));
			if (($carBrand != false) && is_array($carBrand) && (count($carBrand) > 0)){
				$carBrand = $carBrand[0];
				$car['carBrandID'] = $carBrand['carBrandID'];
				$car['carBrand'] = $car['carBrandID'];
				
				//carModelID
				if (isset($p['E'])){
					$carModel = db_selCarModel(array('carModelNameL' => $p['E']
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
		if (isset($p['P']) && is_numeric($p['P'])){
			$car['carPrice'] = $p['P'];
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
		if (isset($p['O']) && is_numeric($p['O'])){
			$car['carKM'] = $p['O'];
					
			//carKMType -> see var. $lang['TXT_75']
			$car['carKMType'] = '0';
		}
		
		//carPower
		if (isset($p['T']) && is_numeric($p['T'])){
			$car['carPower'] = $p['T'];
			
			//carPowerType -> see var. $lang['TXT_72']
			$car['carPowerType'] = '0';
		}
		
		//carEZM, carEZY
		if (isset($p['N'])){
			$mmyyyy = explode('.', $p['N']);
			if (isset($mmyyyy[0])){
				$car['carEZM'] = $mmyyyy[0]; //carEZM
			}
			if (isset($mmyyyy[1])){
				$car['carEZY'] = $mmyyyy[1]; //carEZY
			}
		}
		
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
		
		//carShift -> see var. $lang['V_SHIFT']
		if (isset($p['H'])){
			//Manuel?
			if (stristr($p['H'], 'hand') != false){
				$car['carShift'] = '0';
			}
			//Automatik?
			elseif (stristr($p['H'], 'auto') != false){
				$car['carShift'] = 1;
			}
		}
		
		//carWeight
		if (isset($p['X']) && is_numeric($p['X'])){
			$car['carWeight'] = $p['X'];
		}
		
		//carCyl
		if (isset($p['U']) && is_numeric($p['U'])){
			$car['carCyl'] = $p['U'];
		}
		
		//carCub
		if (isset($p['S']) && is_numeric($p['S'])){
			$car['carCub'] = $p['S'];
		}
		
		//carDoor -> see var. $lang['CAR_DOOR']
		if (isset($p['Q']) && is_numeric($p['Q'])){
			if ($p['Q'] == 2){
				$car['carDoor'] = '0';				
			}elseif ($p['Q'] == 4){
				$car['carDoor'] = 1;
			}
		}
		
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
		
		//carState -> see var. $lang['V_STATE']
		$as2Cat = $p['G'];
		if (isset($p['AE']) && ($p['AE'] == 1)){
			//Unfallfahrzeug
			$car['carState'] = 4;
		}elseif (isset($as2cat) && (stristr($as2cat, 'gebraucht') != false)){
			//Gebruachtwagen
			$car['carState'] = 2;
		}elseif (isset($as2cat) && (stristr($as2cat, 'jahres') != false)){
			//Jahreswagen
			$car['carState'] = 1;
		}elseif (isset($as2cat) && (stristr($as2cat, 'neu') != false)){
			//Neuwagen
			$car['carState'] = '0';
		}elseif (isset($as2cat) && (stristr($as2cat, 'vorf') != false)){
			//Vorführwagen
			$car['carState'] = 5;
		}elseif (isset($as2cat) && (stristr($as2cat, 'oldtim') != false)){
			//Oldtimer
			$car['carState'] = 6;
		}
		
		//carCat -> see var. $lang['CAR_CAT']
		if (isset($p['I'])){
			$carCat = $this -> detCat(array_merge($p, array('vType' => System_Properties::CAR_ABRV)));
			if (($carCat != false) && isset($carCat['carCat'])){
				$car['carCat'] = $carCat['carCat'];
			}
		}
		
		//carFuel -> see var. $lang['V_FUEL']
		if (isset($p['M'])){
			if (stristr($p['M'], 'benzi') != false){				
				//Benzin
				$car['carFuel'] = '0';
			}elseif (stristr($p['M'], 'diese') != false){
				//diesel
				$car['carFuel'] = 1;
			}elseif (stristr($p['M'], 'gas') != false){
				//GAS
				$car['carFuel'] = 8;
			}elseif (stristr($p['M'], 'ethano') != false){
				//ethanol
				$car['carFuel'] = 4;
			}elseif (stristr($p['M'], 'elekt') != false){
				//elektro
				$car['carFuel'] = 2;
			}elseif (stristr($p['M'], 'lpg') != false){
				//LPG GAs
				$car['carFuel'] = 3;
			}elseif (stristr($p['M'], 'cng') != false){
				//CNG Gas
				$car['carFuel'] = 6;
			}elseif (stristr($p['M'], 'hybrid') != false){
				//Hybrid
				$car['carFuel'] = 7;
			}
		}
		
		//carClr -> see var. $lang['V_CLR']
		if (isset($p['CN'])){
			foreach ($lang['V_CLR'] as $key => $kVal){
				if (stristr($kVal, $p['CN']) != false){
					$car['carClr'] = $key;
					break;
				}
			}
		}
		
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
		
		//carDesc
		if (isset($p['BG'])){
			$car['carDesc'] = $p['BG'];
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
			$car['userAds'] = '-1';
			if (isset($user['userMode']) && ($user['userMode'] != 3)){
				$car['userAds'] = $user['userMode'];				
			}
			
			//userFirm
			if (isset($user['userFirm'])){
				$car['userFirm'] = $user['userFirm']; 
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
			if (isset($user['userPLZ'])){
				$car['userPLZ'] = $user['userPLZ'];
				$car['carLocPLZ'] = $user['userPLZ']; 
			}
			
			//userOrt
			if (isset($user['userOrt'])){
				$car['userOrt'] = $user['userOrt'];
				$car['carLocOrt'] = $user['userOrt']; 
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
		
		return $car;
	}

	
	/**
	 * This function filter all relevant data from the imported data set and fill a corresponding structure of bike table
	 */
	private function transAS242BikeStruc($p){
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
	private function transAS242TruckStruc($p){
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
			$zipCSVFile = array('DIR' => $fileNameDir
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
				
					if ($zipFileExtension == 'CSV'){
						array_push($zipCSVFile['FILE'], $zipFileName);			
					}elseif (in_array(strtolower($zipFileExtension), System_Properties::$PIC_EXT)){
						array_push($zipPicFile['FILE'], $zipFileName);
					}
				}
			}
			$zipFile -> extractTo($fileNameDir, array_merge($zipCSVFile['FILE'], $zipPicFile['FILE']));
			$zipFile -> close();
			//process CSV file
			foreach ($zipCSVFile['FILE'] AS $key => $csvFileName){
				$this -> handleCSVFileImp(array('FILE_NAME' => $fileNameDir.'/'.$csvFileName
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
		$fString1 = new FilterStringXX(1);
		$fString6 = new FilterStringXX(6);
		$fString7 = new FilterStringXX(7);
		$fString10 = new FilterStringXX(10);
		$fString25 = new FilterStringXX(25);
		$fString30 = new FilterStringXX(30);
		$fString50 = new FilterStringXX(50);
		$fString80 = new FilterStringXX(80);
		$fString3000 = new FilterStringXX(3000);
		
		$fIsEmpty = new FilterIsEmptyString();
		
		//1
		if(isset($p[0]) && ($p[0] != null) && ($fIsEmpty -> filter($p[0]) == false)){
		  $ret['A'] = $fString10 -> filter($p[0]);
		}else{
		  $ret['A'] = null;
		}
		
		//2
		if(isset($p[1]) && ($p[1] != null) && ($fIsEmpty -> filter($p[1]) == false)){
		  $ret['B'] = $fString50 -> filter($p[1]);
		}else{
		  $ret['B'] = null;
		}
		
		//3
		if(isset($p[2]) && ($p[2] != null) && ($fIsEmpty -> filter($p[2]) == false)){
		  $ret['C'] = $p[2];
		}else{
		  $ret['C'] = null;
		}
		
		//4
		if(isset($p[3]) && ($p[3] != null) && ($fIsEmpty -> filter($p[3]) == false)){
		  $ret['D'] = $p[3];
		}else{
		  $ret['D'] = null;
		}
		
		//5
		if(isset($p[4]) && ($p[4] != null) && ($fIsEmpty -> filter($p[4]) == false)){
		  $ret['E'] = $p[4];
		}else{
		  $ret['E'] = null;
		}
		
		//6
		if(isset($p[5]) && ($p[5] != null) && ($fIsEmpty -> filter($p[5]) == false)){
		  $ret['F'] = $fString30 -> filter($p[5]);
		}else{
		  $ret['F'] = null;
		}
		
		//7
		if(isset($p[6]) && ($p[6] != null) && ($fIsEmpty -> filter($p[6]) == false)){
		  $ret['G'] = $p[6];
		}else{
		  $ret['G'] = null;
		}
		
		//8
		if(isset($p[7]) && ($p[7] != null) && ($fIsEmpty -> filter($p[7]) == false)){
		  $ret['H'] = $p[7];
		}else{
		  $ret['H'] = null;
		}
		
		//9
		if(isset($p[8]) && ($p[8] != null) && ($fIsEmpty -> filter($p[8]) == false)){
		  $ret['I'] = $p[8];
		}else{
		  $ret['I'] = null;
		}
		
		//10
		if(isset($p[9]) && ($p[9] != null) && ($fIsEmpty -> filter($p[9]) == false)){
		  $ret['J'] = $fString25 -> filter($p[9]);
		}else{
		  $ret['J'] = null;
		}
		
		//11
		if(isset($p[10]) && ($p[10] != null) && ($fIsEmpty -> filter($p[10]) == false)){
		  $ret['K'] = $fString1 -> filter($p[10]);
		}else{
		  $ret['K'] = null;
		}
		
		//12
		if(isset($p[11]) && ($p[11] != null) && ($fIsEmpty -> filter($p[11]) == false)){
		  $ret['L'] = $p[11];
		}else{
		  $ret['L'] = null;
		}
		
		//13
		if(isset($p[12]) && ($p[12] != null) && ($fIsEmpty -> filter($p[12]) == false)){
		  $ret['M'] = $p[12];
		}else{
		  $ret['M'] = null;
		}
		
		//14
		if(isset($p[13]) && ($p[13] != null) && ($fIsEmpty -> filter($p[13]) == false)){
		  $ret['N'] = $fString7 -> filter($p[13]);
		}else{
		  $ret['N'] = null;
		}
		
		//15
		if(isset($p[14]) && ($p[14] != null) && ($fIsEmpty -> filter($p[14]) == false)){
		  $ret['O'] = $fString10 -> filter($p[14]);
		}else{
		  $ret['O'] = null;
		}
		
		//16
		if(isset($p[15]) && ($p[15] != null) && ($fIsEmpty -> filter($p[15]) == false)){
		  $ret['P'] = $fString10 -> filter($p[15]);
		}else{
		  $ret['P'] = null;
		}
		
		//17
		if(isset($p[16]) && ($p[16] != null) && ($fIsEmpty -> filter($p[16]) == false)){
		  $ret['Q'] = $fString10 -> filter($p[16]);
		}else{
		  $ret['Q'] = null;
		}
		
		//18
		if(isset($p[17]) && ($p[17] != null) && ($fIsEmpty -> filter($p[17]) == false)){
		  $ret['R'] = $fString10 -> filter($p[17]);
		}else{
		  $ret['R'] = null;
		}
		
		//19
		if(isset($p[18]) && ($p[18] != null) && ($fIsEmpty -> filter($p[18]) == false)){
		  $ret['S'] = $fString10 -> filter($p[18]);
		}else{
		  $ret['S'] = null;
		}
		
		//20
		if(isset($p[19]) && ($p[19] != null) && ($fIsEmpty -> filter($p[19]) == false)){
		  $ret['T'] = $fString10 -> filter($p[19]);
		}else{
		  $ret['T'] = null;
		}
		
		//21
		if(isset($p[20]) && ($p[20] != null) && ($fIsEmpty -> filter($p[20]) == false)){
		  $ret['U'] = $fString10 -> filter($p[20]);
		}else{
		  $ret['U'] = null;
		}
		
		//22
		if(isset($p[21]) && ($p[21] != null) && ($fIsEmpty -> filter($p[21]) == false)){
		  $ret['V'] = $fString10 -> filter($p[21]);
		}else{
		  $ret['V'] = null;
		}
		
		//23
		if(isset($p[22]) && ($p[22] != null) && ($fIsEmpty -> filter($p[22]) == false)){
		  $ret['W'] = $fString10 -> filter($p[22]);
		}else{
		  $ret['W'] = null;
		}
		
		//24
		if(isset($p[23]) && ($p[23] != null) && ($fIsEmpty -> filter($p[23]) == false)){
		  $ret['X'] = $fString10 -> filter($p[23]);
		}else{
		  $ret['X'] = null;
		}
		
		//25
		if(isset($p[24]) && ($p[24] != null) && ($fIsEmpty -> filter($p[24]) == false)){
		  $ret['Y'] = $fString7 -> filter($p[24]);
		}else{
		  $ret['Y'] = null;
		}
		
		//26
		if(isset($p[25]) && ($p[25] != null) && ($fIsEmpty -> filter($p[25]) == false)){
		  $ret['Z'] = $fString10 -> filter($p[25]);
		}else{
		  $ret['Z'] = null;
		}
		
		//27
		if(isset($p[26]) && ($p[26] != null) && ($fIsEmpty -> filter($p[26]) == false)){
		  $ret['AA'] = $fString10 -> filter($p[26]);
		}else{
		  $ret['AA'] = null;
		}
		
		//28
		if(isset($p[27]) && ($p[27] != null) && ($fIsEmpty -> filter($p[27]) == false)){
		  $ret['AB'] = $fString10 -> filter($p[27]);
		}else{
		  $ret['AB'] = null;
		}
		
		//29
		if(isset($p[28]) && ($p[28] != null) && ($fIsEmpty -> filter($p[28]) == false)){
		  $ret['AC'] = $fString10 -> filter($p[28]);
		}else{
		  $ret['AC'] = null;
		}
		
		//30
		if(isset($p[29]) && ($p[29] != null) && ($fIsEmpty -> filter($p[29]) == false)){
		  $ret['AD'] = $fString1 -> filter($p[29]);
		}else{
		  $ret['AD'] = null;
		}
		
		//31
		if(isset($p[30]) && ($p[30] != null) && ($fIsEmpty -> filter($p[30]) == false)){
		  $ret['AE'] = $fString1 -> filter($p[30]);
		}else{
		  $ret['AE'] = null;
		}
		
		//32
		if(isset($p[31]) && ($p[31] != null) && ($fIsEmpty -> filter($p[31]) == false)){
		  $ret['AF'] = $fString1 -> filter($p[31]);
		}else{
		  $ret['AF'] = null;
		}
		
		//33
		if(isset($p[32]) && ($p[32] != null) && ($fIsEmpty -> filter($p[32]) == false)){
		  $ret['AG'] = $fString1 -> filter($p[32]);
		}else{
		  $ret['AG'] = null;
		}
		
		//34
		if(isset($p[33]) && ($p[33] != null) && ($fIsEmpty -> filter($p[33]) == false)){
		  $ret['AH'] = $fString1 -> filter($p[33]);
		}else{
		  $ret['AH'] = null;
		}
		
		//35
		if(isset($p[34]) && ($p[34] != null) && ($fIsEmpty -> filter($p[34]) == false)){
		  $ret['AI'] = $fString1 -> filter($p[34]);
		}else{
		  $ret['AI'] = null;
		}
		
		//36
		if(isset($p[35]) && ($p[35] != null) && ($fIsEmpty -> filter($p[35]) == false)){
		  $ret['AJ'] = $fString1 -> filter($p[35]);
		}else{
		  $ret['AJ'] = null;
		}
		
		//37
		if(isset($p[36]) && ($p[36] != null) && ($fIsEmpty -> filter($p[36]) == false)){
		  $ret['AK'] = $fString1 -> filter($p[36]);
		}else{
		  $ret['AK'] = null;
		}
		
		//38
		if(isset($p[37]) && ($p[37] != null) && ($fIsEmpty -> filter($p[37]) == false)){
		  $ret['AL'] = $fString1 -> filter($p[37]);
		}else{
		  $ret['AL'] = null;
		}
		
		//39
		if(isset($p[38]) && ($p[38] != null) && ($fIsEmpty -> filter($p[38]) == false)){
		  $ret['AM'] = $fString1 -> filter($p[38]);
		}else{
		  $ret['AM'] = null;
		}
		
		//40
		if(isset($p[39]) && ($p[39] != null) && ($fIsEmpty -> filter($p[39]) == false)){
		  $ret['AN'] = $fString1 -> filter($p[39]);
		}else{
		  $ret['AN'] = null;
		}
		
		//41
		if(isset($p[40]) && ($p[40] != null) && ($fIsEmpty -> filter($p[40]) == false)){
		  $ret['AO'] = $fString1 -> filter($p[40]);
		}else{
		  $ret['AO'] = null;
		}
		
		//42
		if(isset($p[41]) && ($p[41] != null) && ($fIsEmpty -> filter($p[41]) == false)){
		  $ret['AP'] = $fString1 -> filter($p[41]);
		}else{
		  $ret['AP'] = null;
		}
		
		//43
		if(isset($p[42]) && ($p[42] != null) && ($fIsEmpty -> filter($p[42]) == false)){
		  $ret['AQ'] = $fString1 -> filter($p[42]);
		}else{
		  $ret['AQ'] = null;
		}
		
		//44
		if(isset($p[43]) && ($p[43] != null) && ($fIsEmpty -> filter($p[43]) == false)){
		  $ret['AR'] = $fString1 -> filter($p[43]);
		}else{
		  $ret['AR'] = null;
		}
		
		//45
		if(isset($p[44]) && ($p[44] != null) && ($fIsEmpty -> filter($p[44]) == false)){
		  $ret['AS1'] = $fString1 -> filter($p[44]);
		}else{
		  $ret['AS1'] = null;
		}
		
		//46
		if(isset($p[45]) && ($p[45] != null) && ($fIsEmpty -> filter($p[45]) == false)){
		  $ret['AT'] = $fString1 -> filter($p[45]);
		}else{
		  $ret['AT'] = null;
		}
		
		//47
		if(isset($p[46]) && ($p[46] != null) && ($fIsEmpty -> filter($p[46]) == false)){
		  $ret['AU'] = $fString1 -> filter($p[46]);
		}else{
		  $ret['AU'] = null;
		}
		
		//48
		if(isset($p[47]) && ($p[47] != null) && ($fIsEmpty -> filter($p[47]) == false)){
		  $ret['AV'] = $fString1 -> filter($p[47]);
		}else{
		  $ret['AV'] = null;
		}
		
		//49
		if(isset($p[48]) && ($p[48] != null) && ($fIsEmpty -> filter($p[48]) == false)){
		  $ret['AW'] = $fString1 -> filter($p[48]);
		}else{
		  $ret['AW'] = null;
		}
		
		//50
		if(isset($p[49]) && ($p[49] != null) && ($fIsEmpty -> filter($p[49]) == false)){
		  $ret['AX'] = $fString1 -> filter($p[49]);
		}else{
		  $ret['AX'] = null;
		}
		
		//51
		if(isset($p[50]) && ($p[50] != null) && ($fIsEmpty -> filter($p[50]) == false)){
		  $ret['AY'] = $fString1 -> filter($p[50]);
		}else{
		  $ret['AY'] = null;
		}
		
		//52
		if(isset($p[51]) && ($p[51] != null) && ($fIsEmpty -> filter($p[51]) == false)){
		  $ret['AZ'] = $fString1 -> filter($p[51]);
		}else{
		  $ret['AZ'] = null;
		}
		
		//53
		if(isset($p[52]) && ($p[52] != null) && ($fIsEmpty -> filter($p[52]) == false)){
		  $ret['BA'] = $fString1 -> filter($p[52]);
		}else{
		  $ret['BA'] = null;
		}
		
		//54
		if(isset($p[53]) && ($p[53] != null) && ($fIsEmpty -> filter($p[53]) == false)){
		  $ret['BB'] = $fString1 -> filter($p[53]);
		}else{
		  $ret['BB'] = null;
		}
		
		//55
		if(isset($p[54]) && ($p[54] != null) && ($fIsEmpty -> filter($p[54]) == false)){
		  $ret['BC'] = $fString1 -> filter($p[54]);
		}else{
		  $ret['BC'] = null;
		}
		
		//56
		if(isset($p[55]) && ($p[55] != null) && ($fIsEmpty -> filter($p[55]) == false)){
		  $ret['BD'] = $fString1 -> filter($p[55]);
		}else{
		  $ret['BD'] = null;
		}
		
		//57
		if(isset($p[56]) && ($p[56] != null) && ($fIsEmpty -> filter($p[56]) == false)){
		  $ret['BE'] = $fString1 -> filter($p[56]);
		}else{
		  $ret['BE'] = null;
		}
		
		//58
		if(isset($p[57]) && ($p[57] != null) && ($fIsEmpty -> filter($p[57]) == false)){
		  $ret['BF'] = $fString1 -> filter($p[57]);
		}else{
		  $ret['BF'] = null;
		}
		
		//59
		if(isset($p[58]) && ($p[58] != null) && ($fIsEmpty -> filter($p[58]) == false)){
		  $ret['BG'] = $fString3000 -> filter($p[58]);
		}else{
		  $ret['BG'] = null;
		}
		
		//60
		if(isset($p[59]) && ($p[59] != null) && ($fIsEmpty -> filter($p[59]) == false)){
		  $ret['BH'] = $fString10 -> filter($p[59]);
		}else{
		  $ret['BH'] = null;
		}
		
		//61
		if(isset($p[60]) && ($p[60] != null) && ($fIsEmpty -> filter($p[60]) == false)){
		  $ret['BI'] = $fString1 -> filter($p[60]);
		}else{
		  $ret['BI'] = null;
		}
		
		//62
		if(isset($p[61]) && ($p[61] != null) && ($fIsEmpty -> filter($p[61]) == false)){
		  $ret['BJ'] = $fString1 -> filter($p[61]);
		}else{
		  $ret['BJ'] = null;
		}
		
		//63
		if(isset($p[62]) && ($p[62] != null) && ($fIsEmpty -> filter($p[62]) == false)){
		  $ret['BK'] = $fString1 -> filter($p[62]);
		}else{
		  $ret['BK'] = null;
		}
		
		//64
		if(isset($p[63]) && ($p[63] != null) && ($fIsEmpty -> filter($p[63]) == false)){
		  $ret['BL'] = $fString1 -> filter($p[63]);
		}else{
		  $ret['BL'] = null;
		}
		
		//65
		if(isset($p[64]) && ($p[64] != null) && ($fIsEmpty -> filter($p[64]) == false)){
		  $ret['BM'] = $fString1 -> filter($p[64]);
		}else{
		  $ret['BM'] = null;
		}
		
		//66
		if(isset($p[65]) && ($p[65] != null) && ($fIsEmpty -> filter($p[65]) == false)){
		  $ret['BN'] = $fString1 -> filter($p[65]);
		}else{
		  $ret['BN'] = null;
		}
		
		//67
		if(isset($p[66]) && ($p[66] != null) && ($fIsEmpty -> filter($p[66]) == false)){
		  $ret['BO'] = $fString1 -> filter($p[66]);
		}else{
		  $ret['BO'] = null;
		}
		
		//68
		if(isset($p[67]) && ($p[67] != null) && ($fIsEmpty -> filter($p[67]) == false)){
		  $ret['BP'] = $fString1 -> filter($p[67]);
		}else{
		  $ret['BP'] = null;
		}
		
		//69
		if(isset($p[68]) && ($p[68] != null) && ($fIsEmpty -> filter($p[68]) == false)){
		  $ret['BQ'] = $fString10 -> filter($p[68]);
		}else{
		  $ret['BQ'] = null;
		}
		
		//70
		if(isset($p[69]) && ($p[69] != null) && ($fIsEmpty -> filter($p[69]) == false)){
		  $ret['BR'] = $fString10 -> filter($p[69]);
		}else{
		  $ret['BR'] = null;
		}
		
		//71
		if(isset($p[70]) && ($p[70] != null) && ($fIsEmpty -> filter($p[70]) == false)){
		  $ret['BS'] = $fString10 -> filter($p[70]);
		}else{
		  $ret['BS'] = null;
		}
		
		//72
		if(isset($p[71]) && ($p[71] != null) && ($fIsEmpty -> filter($p[71]) == false)){
		  $ret['BT'] = $fString10 -> filter($p[71]);
		}else{
		  $ret['BT'] = null;
		}
		
		//73
		if(isset($p[72]) && ($p[72] != null) && ($fIsEmpty -> filter($p[72]) == false)){
		  $ret['BU'] = $fString1 -> filter($p[72]);
		}else{
		  $ret['BU'] = null;
		}
		
		//74
		if(isset($p[73]) && ($p[73] != null) && ($fIsEmpty -> filter($p[73]) == false)){
		  $ret['BV'] = $p[73];
		}else{
		  $ret['BV'] = null;
		}
		
		//75
		if(isset($p[74]) && ($p[74] != null) && ($fIsEmpty -> filter($p[74]) == false)){
		  $ret['BW'] = $fString10 -> filter($p[74]);
		}else{
		  $ret['BW'] = null;
		}
		
		//76
		if(isset($p[75]) && ($p[75] != null) && ($fIsEmpty -> filter($p[75]) == false)){
		  $ret['BX'] = $fString10 -> filter($p[75]);
		}else{
		  $ret['BX'] = null;
		}
		
		//77
		if(isset($p[76]) && ($p[76] != null) && ($fIsEmpty -> filter($p[76]) == false)){
		  $ret['BY1'] = $fString10 -> filter($p[76]);
		}else{
		  $ret['BY1'] = null;
		}
		
		//78
		if(isset($p[77]) && ($p[77] != null) && ($fIsEmpty -> filter($p[77]) == false)){
		  $ret['BZ'] = $fString6 -> filter($p[77]);
		}else{
		  $ret['BZ'] = null;
		}
		
		//79
		if(isset($p[78]) && ($p[78] != null) && ($fIsEmpty -> filter($p[78]) == false)){
		  $ret['CA'] = $p[78];
		}else{
		  $ret['CA'] = null;
		}
		
		//80
		if(isset($p[79]) && ($p[79] != null) && ($fIsEmpty -> filter($p[79]) == false)){
		  $ret['CB'] = $fString10 -> filter($p[79]);
		}else{
		  $ret['CB'] = null;
		}
		
		//81
		if(isset($p[80]) && ($p[80] != null) && ($fIsEmpty -> filter($p[80]) == false)){
		  $ret['CC'] = $fString80 -> filter($p[80]);
		}else{
		  $ret['CC'] = null;
		}
		
		//82
		if(isset($p[81]) && ($p[81] != null) && ($fIsEmpty -> filter($p[81]) == false)){
		  $ret['CD'] = $p[81];
		}else{
		  $ret['CD'] = null;
		}
		
		//83
		if(isset($p[82]) && ($p[82] != null) && ($fIsEmpty -> filter($p[82]) == false)){
		  $ret['CE'] = $fString1 -> filter($p[82]);
		}else{
		  $ret['CE'] = null;
		}
		
		//84
		if(isset($p[83]) && ($p[83] != null) && ($fIsEmpty -> filter($p[83]) == false)){
		  $ret['CF'] = $fString1 -> filter($p[83]);
		}else{
		  $ret['CF'] = null;
		}
		
		//85
		if(isset($p[84]) && ($p[84] != null) && ($fIsEmpty -> filter($p[84]) == false)){
		  $ret['CG'] = $fString1 -> filter($p[84]);
		}else{
		  $ret['CG'] = null;
		}
		
		//86
		if(isset($p[85]) && ($p[85] != null) && ($fIsEmpty -> filter($p[85]) == false)){
		  $ret['CH'] = $fString1 -> filter($p[85]);
		}else{
		  $ret['CH'] = null;
		}
		
		//87
		if(isset($p[86]) && ($p[86] != null) && ($fIsEmpty -> filter($p[86]) == false)){
		  $ret['CI'] = $p[86];
		}else{
		  $ret['CI'] = null;
		}
		
		//88
		if(isset($p[87]) && ($p[87] != null) && ($fIsEmpty -> filter($p[87]) == false)){
		  $ret['CJ'] = $p[87];
		}else{
		  $ret['CJ'] = null;
		}
		
		//89
		if(isset($p[88]) && ($p[88] != null) && ($fIsEmpty -> filter($p[88]) == false)){
		  $ret['CK'] = $fString10 -> filter($p[88]);
		}else{
		  $ret['CK'] = null;
		}
		
		//90
		if(isset($p[89]) && ($p[89] != null) && ($fIsEmpty -> filter($p[89]) == false)){
		  $ret['CL'] = $fString10 -> filter($p[89]);
		}else{
		  $ret['CL'] = null;
		}
		
		//91
		if(isset($p[90]) && ($p[90] != null) && ($fIsEmpty -> filter($p[90]) == false)){
		  $ret['CM'] = $p[90];
		}else{
		  $ret['CM'] = null;
		}
		
		//92
		if(isset($p[91]) && ($p[91] != null) && ($fIsEmpty -> filter($p[91]) == false)){
		  $ret['CN'] = $p[91];
		}else{
		  $ret['CN'] = null;
		}
		
		//93
		if(isset($p[92]) && ($p[92] != null) && ($fIsEmpty -> filter($p[92]) == false)){
		  $ret['CO'] = $fString10 -> filter($p[92]);
		}else{
		  $ret['CO'] = null;
		}
		
		//94
		if(isset($p[93]) && ($p[93] != null) && ($fIsEmpty -> filter($p[93]) == false)){
		  $ret['CP'] = $fString10 -> filter($p[93]);
		}else{
		  $ret['CP'] = null;
		}
		
		//95
		if(isset($p[94]) && ($p[94] != null) && ($fIsEmpty -> filter($p[94]) == false)){
		  $ret['CQ'] = $fString10 -> filter($p[94]);
		}else{
		  $ret['CQ'] = null;
		}
		
		//96
		if(isset($p[95]) && ($p[95] != null) && ($fIsEmpty -> filter($p[95]) == false)){
		  $ret['CR'] = $p[95];
		}else{
		  $ret['CR'] = null;
		}
		
		//97
		if(isset($p[96]) && ($p[96] != null) && ($fIsEmpty -> filter($p[96]) == false)){
		  $ret['CS'] = $fString10 -> filter($p[96]);
		}else{
		  $ret['CS'] = null;
		}
		
		//98
		if(isset($p[97]) && ($p[97] != null) && ($fIsEmpty -> filter($p[97]) == false)){
		  $ret['CT'] = $p[97];
		}else{
		  $ret['CT'] = null;
		}
		
		//99
		if(isset($p[98]) && ($p[98] != null) && ($fIsEmpty -> filter($p[98]) == false)){
		  $ret['CU'] = $fString10 -> filter($p[98]);
		}else{
		  $ret['CU'] = null;
		}
		
		//100
		if(isset($p[99]) && ($p[99] != null) && ($fIsEmpty -> filter($p[99]) == false)){
		  $ret['CV'] = $fString10 -> filter($p[99]);
		}else{
		  $ret['CV'] = null;
		}
		
		//101
		if(isset($p[100]) && ($p[100] != null) && ($fIsEmpty -> filter($p[100]) == false)){
		  $ret['CW'] = $fString10 -> filter($p[100]);
		}else{
		  $ret['CW'] = null;
		}
		
		//102
		if(isset($p[101]) && ($p[101] != null) && ($fIsEmpty -> filter($p[101]) == false)){
		  $ret['CX'] = $fString10 -> filter($p[101]);
		}else{
		  $ret['CX'] = null;
		}
		
		//103
		if(isset($p[102]) && ($p[102] != null) && ($fIsEmpty -> filter($p[102]) == false)){
		  $ret['CY'] = $p[102];
		}else{
		  $ret['CY'] = null;
		}
		
		//104
		if(isset($p[103]) && ($p[103] != null) && ($fIsEmpty -> filter($p[103]) == false)){
		  $ret['CZ'] = $fString10 -> filter($p[103]);
		}else{
		  $ret['CZ'] = null;
		}

		return $ret;		
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
	
	private function getInitialAS24Struc(){
		$ret = array();
		$ret['A'] = '';
		$ret['B'] = '';
		$ret['C'] = '';
		$ret['D'] = '';
		$ret['E'] = '';
		$ret['F'] = '';
		$ret['G'] = '';
		$ret['H'] = '';
		$ret['I'] = '';
		$ret['J'] = '';
		$ret['K'] = '';
		$ret['L'] = '';
		$ret['M'] = '';
		$ret['N'] = '';
		$ret['O'] = '';
		$ret['P'] = '';
		$ret['Q'] = '';
		$ret['R'] = '';
		$ret['S'] = '';
		$ret['T'] = '';
		$ret['U'] = '';
		$ret['V'] = '';
		$ret['W'] = '';
		$ret['X'] = '';
		$ret['Y'] = '';
		$ret['Z'] = '';
		$ret['AA'] = '';
		$ret['AB'] = '';
		$ret['AC'] = '';
		$ret['AD'] = '';
		$ret['AE'] = '';
		$ret['AF'] = '';
		$ret['AG'] = '';
		$ret['AH'] = '';
		$ret['AI'] = '';
		$ret['AJ'] = '';
		$ret['AK'] = '';
		$ret['AL'] = '';
		$ret['AM'] = '';
		$ret['AN'] = '';
		$ret['AO'] = '';
		$ret['AP'] = '';
		$ret['AQ'] = '';
		$ret['AR'] = '';
		$ret['AS'] = '';
		$ret['AT'] = '';
		$ret['AU'] = '';
		$ret['AV'] = '';
		$ret['AW'] = '';
		$ret['AX'] = '';
		$ret['AY'] = '';
		$ret['AZ'] = '';
		$ret['BA'] = '';
		$ret['BB'] = '';
		$ret['BC'] = '';
		$ret['BD'] = '';
		$ret['BE'] = '';
		$ret['BF'] = '';
		$ret['BG'] = '';
		$ret['BH'] = '';
		$ret['BI'] = '';
		$ret['BJ'] = '';
		$ret['BK'] = '';
		$ret['BL'] = '';
		$ret['BM'] = '';
		$ret['BN'] = '';
		$ret['BO'] = '';
		$ret['BP'] = '';
		$ret['BQ'] = '';
		$ret['BR'] = '';
		$ret['BS'] = '';
		$ret['BT'] = '';
		$ret['BU'] = '';
		$ret['BV'] = '';
		$ret['BW'] = '';
		$ret['BX'] = '';
		$ret['BY'] = '';
		$ret['BZ'] = '';
		$ret['CA'] = '';
		$ret['CB'] = '';
		$ret['CC'] = '';
		$ret['CD'] = '';
		$ret['CE'] = '';
		$ret['CF'] = '';
		$ret['CG'] = '';
		$ret['CH'] = '';
		$ret['CI'] = '';
		$ret['CJ'] = '';
		$ret['CK'] = '';
		$ret['CL'] = '';
		$ret['CM'] = '';
		$ret['CN'] = '';
		$ret['CO'] = '';
		$ret['CP'] = '';
		$ret['CQ'] = '';
		$ret['CR'] = '';
		$ret['CS'] = '';
		$ret['CT'] = '';
		$ret['CU'] = '';
		$ret['CV'] = '';
		$ret['CW'] = '';
		$ret['CX'] = '';
		$ret['CY'] = '';
		$ret['CZ'] = '';
		
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