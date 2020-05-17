<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110619
 * Desc:		This class handles the autoscout24 data exchange
 *********************************************************************************/
include_once ('classes/CL_DATEX_ABS.php');
include_once ('classes/System_Properties.php');

include_once (System_Properties::ADMIN_MOD_PATH . '/models/system/db_selSystem.php');

include_once ('default/models/default/db_insMobile.php');
include_once ('default/models/default/db_selMobile.php');
include_once ('default/models/default/db_updMobile.php');
include_once ('default/models/default/db_delMobile.php');

include_once ('default/models/default/db_delVPic.php');
include_once ('default/models/default/db_selVPic.php');
include_once ('default/models/default/db_insVPic.php');

include_once ('default/models/car/db_selCarAd.php');
include_once ('default/models/car/db_insCarAds.php');
include_once ('default/models/car/db_updCarAds.php');
include_once ('default/models/car/db_selCarBrand.php');
include_once ('default/models/car/db_selCarModel.php');
include_once ('default/models/car/db_insCar2Ext.php');
include_once ('default/models/car/db_selCarExt.php');
include_once ('default/models/car/db_delCarAds.php');
include_once ('default/models/car/db_delCar2Ext.php');

include_once ('default/models/bike/db_selBikeAd.php');
include_once ('default/models/bike/db_insBikeAds.php');
include_once ('default/models/bike/db_updBikeAds.php');
include_once ('default/models/bike/db_selBikeBrand.php');
include_once ('default/models/bike/db_selBikeModel.php');
include_once ('default/models/bike/db_insBike2Ext.php');
include_once ('default/models/bike/db_selBikeExt.php');
include_once ('default/models/bike/db_delBikeAds.php');
include_once ('default/models/bike/db_delBike2Ext.php');

include_once ('default/models/truck/db_selTruckAd.php');
include_once ('default/models/truck/db_insTruckAds.php');
include_once ('default/models/truck/db_updTruckAds.php');
include_once ('default/models/truck/db_selTruckBrand.php');
include_once ('default/models/truck/db_selTruckModel.php');
include_once ('default/models/truck/db_insTruck2Ext.php');
include_once ('default/models/truck/db_selTruckExt.php');
include_once ('default/models/truck/db_delTruckAds.php');
include_once ('default/models/truck/db_delTruck2Ext.php');

include_once ('default/views/filters/FilterValidAS24EZ.php');
include_once ('default/views/filters/FilterMySQLInt.php');
include_once ('default/views/filters/FilterStringXX.php');
include_once ('default/views/filters/FilterEncUTF8.php');
include_once ('default/views/filters/FilterIsEmptyString.php');

include_once ('default/models/member/db_selUser.php');
include_once ('default/models/member/db_insUser.php');
include_once ('default/models/member/db_updUser.php');
include_once ('default/views/filters/FilterValidEmail.php');
include_once ('default/views/filters/FilterEncUTF8.php');
include_once ('default/views/filters/FilterString100.php');
include_once ('default/views/filters/FilterString20.php');
include_once ('default/views/filters/FilterString10.php');
include_once ('default/views/filters/FilterStringXX.php');
include_once ('default/views/filters/FilterIsEmptyString.php');
include_once ('default/views/filters/FilterEncMD5.php');

include_once ('default/models/member/db_selFTPUser.php');
include_once ('default/models/member/db_insFTPUser.php');
class CL_MOBILE extends CL_DATEX_ABSTRACT {
	const DATEX_ID = 'MOBILE';
	private $datexIntfParam;
	private $prot;
	
	// specify if actual processing is run as a cronjob
	private $cronj;
	public function __construct($p = null) {
		parent::__construct ( $p );
		$this->cronj = false;
		if (isset ( $p ['CRONJ'] ) && ($p ['CRONJ'] == true)) {
			$this->cronj = true;
		}
		
		foreach ( System_Properties::$DATA_INTF_FILE as $key => $kVal ) {
			if (strtolower ( CL_MOBILE::DATEX_ID ) == strtolower ( $key )) {
				$this->datexIntfParam = $kVal;
				break;
			}
		}
	}
	public function handleDatexImp($p) {
		$lang = $this->lang;
		
		$this->prot = array (
				'ERROR' => array (),
				'INFO' => array () 
		);
		
		$fileCntrl = null;
		if (isset ( $p ['FILE_CNTRL'] )) {
			$fileCntrl = $p ['FILE_CNTRL'];
		}
		
		if (isset ( $p ['USER_DATA'] )) {
			$this->userData = $p ['USER_DATA'];
		} elseif (isset ( $this->userNS->userData )) {
			$userTMP = $this->userNS->userData;
			if (isset ( $userTMP ['userID'] )) {
				$userTMP = db_selUser ( array (
						'userID' => $userTMP ['userID'] 
				) );
				if (($userTMP != false) && is_array ( $userTMP ) && (count ( $userTMP ) > 0)) {
					$this->userData = $userTMP [0];
				}
			}
		}
		/*
		 * if (isset ( $p ['DOC_ROOT'] )) {
		 * $this->docRoot = $p ['DOC_ROOT'];
		 * } elseif (stristr ( $_SERVER ['DOCUMENT_ROOT'], '/t01/' )) {
		 * $this->docRoot = '/var/customers/webs/autotunes/t01/';
		 * } else {
		 * $this->docRoot = '/var/customers/webs/autotunes/www/';
		 * }
		 */
		
		$fileName = null;
		if (isset ( $p ['FILE_NAME'] )) {
			$fileName = $p ['FILE_NAME'];
		} elseif ($fileCntrl != null) {
			$fileName = $fileCntrl->getFileName ();
			$p ['FILE_NAME'] = $fileName;
		}
		
		if (file_exists ( $fileName )) {
			$fileNameComp = explode ( '.', basename ( $fileName ) );
			$fileExtension = $fileNameComp [count ( $fileNameComp ) - 1];
			$fileExtension = strtolower ( $fileExtension );
			
			// invoke ZIP processing method
			if (strtolower ( $fileExtension ) == 'zip') {
				$this->handleZIPFileImp ( $p );
			} // invoke CSV processing method
elseif (strtolower ( $fileExtension ) == 'csv') {
				$this->handleCSVFileImp ( $p );
			}
		} else {
			array_push ( $this->prot, $lang ['ERR_43'] . ' ' . $fileName );
		}
		
		if (count ( $this->prot ) > 0) {
			$p ['PROT'] = $this->prot;
		}
		return $p;
	}
	
	/**
	 * process CSV file
	 */
	private function handleCSVFileImp($p) {
		include_once ('default/models/car/db_selCarBrand.php');
		include_once ('default/views/filters/FilterEncUTF8.php');
		
		$fUTF8 = new FilterEncUTF8 ();
		
		$lang = $this->lang;
		$fileName = null;
		if (isset ( $p ['FILE_NAME'] )) {
			$fileName = $p ['FILE_NAME'];
		}
		
		$user = null;
		if (isset ( $p ['USER_DATA'] ) && is_array ( $p ['USER_DATA'] ) && (count ( $p ['USER_DATA'] ) > 0) && ($p ['USER_DATA'] != false)) {
			$user = $p ['USER_DATA'];
		} elseif (isset ( $this->userNS->userData )) {
			$userTMP = $this->userNS->userData;
			if (isset ( $userTMP ['userID'] )) {
				$userTMP = db_selUser ( array (
						'userID' => $userTMP ['userID'] 
				) );
				if (($userTMP != false) && is_array ( $userTMP ) && (count ( $userTMP ) > 0)) {
					$user = $userTMP [0];
				}
			}
		} elseif ($this->userData != null) {
			$user = $this->userData;
		}
		
		if (($fileName != null) && ($user != null)) {
			$fileHandler = fopen ( $fileName, 'r' );
			while ( ($csvData = fgetcsv ( $fileHandler, null, ';' )) !== false ) {
				$csvData = $fUTF8->filter ( $csvData );
				$csvData = $this->fillAssoziativeArr ( $csvData );
				// $csvData = $this -> checkManFieldsMobileAction(array('DATA' => $csvData));
				$csvData = array (
						'DATA' => $csvData 
				);
				if (! isset ( $csvData ['error'] ) && isset ( $csvData ['DATA'] )) {
					$csvData = $csvData ['DATA'];
					$csvData ['mobileNew'] = 1;
					// check if data set already exists
					$mobileData = db_selMobile ( array (
							'B' => $csvData ['B'],
							'userID' => $user ['userID'] 
					) );
					// , 'print'=>true
					
					if (($mobileData != false) && is_array ( $mobileData ) && (count ( $mobileData ) > 0)) {
						$mobileData = $mobileData [0];
						$updMOBILE = db_updMobile ( array (
								System_Properties::SQL_SET => $csvData,
								System_Properties::SQL_WHERE => array (
										'mobileID' => $mobileData ['mobileID'] 
								) 
						) );
						// , 'print'=>true
					} else {
						$csvData ['userID'] = $user ['userID'];
						
						if (isset ( $p ['vType'] )) {
							$csvData ['vType'] = $p ['vType'];
						}
						
						// determine CSV data
						if (($this->cronj == true) && ($this->userData != null) && is_array ( $this->userData ) && isset ( $this->userData ['userID'] ) && ($this->userData ['userID'] == 3)) {
							$csvData = $this->detVType ( $csvData );
						} elseif (! isset ( $csvData ['vType'] ) || ($csvData ['vType'] == false) || ($csvData ['vType'] == null)) {
							$csvData = $this->detVType ( $csvData );
						}
						
						// $csvData['print'] = true;//echo "<br><br>";
						$mobileID = db_insMobile ( $csvData );
					}
				}
			}
			
			// adjust imported advertisments
			$this->adjustImpAd ( $p );
		} else {
			array_push ( $this->prot, $lang ['ERR_42'] . ' ' . $fileName );
			array_push ( $this->prot, $lang ['ERR_43'] );
		}
	}
	
	/**
	 * Adjust the new content with existing advertisements
	 */
	private function adjustImpAd($p = null) {
		$lang = $this->lang;
		
		$user = null;
		if (isset ( $p ['USER_DATA'] ) && is_array ( $p ['USER_DATA'] ) && (count ( $p ['USER_DATA'] ) > 0) && ($p ['USER_DATA'] != false)) {
			$user = $p ['USER_DATA'];
		} elseif (isset ( $this->userNS->userData )) {
			$userTMP = $this->userNS->userData;
			if (isset ( $userTMP ['userID'] )) {
				$userTMP = db_selUser ( array (
						'userID' => $userTMP ['userID'] 
				) );
				if (($userTMP != false) && is_array ( $userTMP ) && (count ( $userTMP ) > 0)) {
					$user = $userTMP [0];
				}
			}
		} elseif ($this->userData != null) {
			$user = $this->userData;
		}
		if ($user != null) {
			// delete old vehicle i.e. all vehicles from user where mobileNew = 0
			$oldMobileData = db_selMobile ( array (
					'userID' => $user ['userID'],
					'mobileNew' => '0' 
			) );
			// , 'print' => true
			
			if (($oldMobileData != false) && is_array ( $oldMobileData ) && (count ( $oldMobileData ) > 0)) {
				foreach ( $oldMobileData as $key => $kVal ) {
					if (isset ( $kVal ['vType'] ) && isset ( $kVal ['vID'] ) && is_numeric ( $kVal ['vID'] )) {
						// update car
						if (strtolower ( $kVal ['vType'] ) == strtolower ( System_Properties::CAR_ABRV )) {
							// db_updCarAds(array( System_Properties::SQL_SET => array('erased' => '1')
							// , System_Properties::SQL_WHERE => array('carID' => $kVal['vID'])
							// ));
							// Delete all car advertisements
							db_delCarAds ( array (
									'carID' => $kVal ['vID'] 
							) );
							
							// Delete all car extras
							db_delCar2Ext ( array (
									'carID' => $kVal ['vID'] 
							) );
							// , 'print' => true
						} // update bike
						elseif (strtolower ( $kVal ['vType'] ) == strtolower ( System_Properties::BIKE_ABRV )) {
							// db_updBikeAds(array( System_Properties::SQL_SET => array('erased' => '1')
							// , System_Properties::SQL_WHERE => array('bikeID' => $kVal['vID'])
							// ));
							// Delete all bike advertisements
							db_delBikeAds ( array (
									'bikeID' => $kVal ['vID'] 
							) );
							// Delete all bike extras
							db_delBike2Ext ( array (
									'bikeID' => $kVal ['vID'] 
							) );
							// , 'print' => true
						} // update truck
						elseif (strtolower ( $kVal ['vType'] ) == strtolower ( System_Properties::TRUCK_ABRV )) {
							// db_updTruckAds(array( System_Properties::SQL_SET => array('erased' => '1')
							// , System_Properties::SQL_WHERE => array('truckID' => $kVal['vID'])
							// ));
							// Delete all truck advertisements
							db_delTruckAds ( array (
									'truckID' => $kVal ['vID'] 
							) );
							// Delete all bike extras
							db_delTruck2Ext ( array (
									'truckID' => $kVal ['vID'] 
							) );
							// , 'print' => true
						}
						// update mobile
						// db_updMobile(array(System_Properties::SQL_SET => array('erased' => '1')
						// , System_Properties::SQL_WHERE => array('mobileID' => $kVal['mobileID'])
						// ));
						
						$vPicID = array ();
						$vPic = db_selVPic ( array (
								'vID' => $kVal ['vID'],
								'vType' => $kVal ['vType'] 
						) );
						if (is_array ( $vPic )) {
							foreach ( $vPic as $key2 => $kVal2 ) {
								array_push ( $vPicID, $kVal2 ['vPicID'] );
							}
							if (count ( $vPicID ) > 0) {
								// delete pictures in the database
								if (db_delVPic ( array (
										'vPicID' => $vPicID 
								) ) != false) {
									// delete picutres from filesystem
									foreach ( $vPic as $key2 => $kVal2 ) {
										if (isset ( $kVal2 ['vType'] ) && isset ( $kVal2 ['vID'] ) && isset ( $kVal2 ['vPicID'] )) {
											// $picPath = '/var/customers/webs/autotunes/www/web' . System_Properties::PIC_PATH . '/' . strtolower ( $kVal2 ['vType'] ) . '_' . $kVal2 ['vID'] . '_' . $kVal2 ['vPicID'] . '.jpeg';
											$picPath = './' . System_Properties::PIC_PATH . '/' . strtolower ( $kVal2 ['vType'] ) . '_' . $kVal2 ['vID'] . '_' . $kVal2 ['vPicID'] . '.jpeg';
											unlink ( $picPath );
										}
									}
								}
							}
						}
					}
					db_delMobile ( array (
							'mobileID' => $kVal ['mobileID'] 
					) );
					// , 'print' => true
				}
				// $this ->cleanAction();
			}
			// fetch all new inserted mobile advertisments
			$mobileData = db_selMobile ( array (
					'userID' => $user ['userID'],
					'mobileNew' => '1' 
			) );
			
			if (($mobileData != false) && is_array ( $mobileData )) {
				foreach ( $mobileData as $key => $extData ) {
					if (! isset ( $extData ['vType'] ) || ($extData ['vType'] == false) || ($extData ['vType'] == null)) {
						$extData = $this->detVType ( $extData );
					}
					
					$extData ['USER_DATA'] = $user;
					$doInsert = true;
					
					// If vehicle ID is set then process an update
					if (isset ( $extData ['vID'] ) && is_numeric ( $extData ['vID'] ) && ($extData ['vID'] != null) && ($extData ['vID'] != 0)) {
						$doInsert = false;
						
						// CAR?
						if (isset ( $extData ['vType'] ) && ($extData ['vType'] == System_Properties::CAR_ABRV)) {
							$carDB = db_selCarAd ( array (
									'carID' => $extData ['vID'] 
							) );
							
							if (($carDB != false) && is_array ( $carDB ) && (count ( $carDB ) > 0)) {
								$carDB = $carDB [0];
								$car = $this->transformMobile2CarStruct ( $extData );
								$car = $this->filterCarData ( $car );
								if (! isset ( $car ['error'] )) {
									$p ['EXT_DATA'] = $extData;
									$p ['V_DATA'] = array (
											'vType' => System_Properties::CAR_ABRV,
											'vID' => $carDB ['carID'] 
									);
									
									$carUpd = db_updCarAds ( array (
											System_Properties::SQL_SET => $car,
											System_Properties::SQL_WHERE => array (
													'carID' => $carDB ['carID'] 
											) 
									) );
									if ($carUpd != false) {
										// update car extra
										$this->processMobileCarExtra ( $p );
									}
									// update pics
									$this->processAdsPic ( $p );
									array_push ( $this->prot ['INFO'], $lang ['INFO_11'] . $extData ['D'] );
								}
								db_updMobile ( array (
										System_Properties::SQL_SET => array (
												'mobileNew' => '0' 
										),
										System_Properties::SQL_WHERE => array (
												'mobileID' => $extData ['mobileID'] 
										) 
								) );
								// , 'print'=>true
							} else {
								$doInsert = true;
							}
						} 						

						// BIKE?
						else if (isset ( $extData ['vType'] ) && ($extData ['vType'] == System_Properties::BIKE_ABRV)) {
							$bikeDB = db_selBikeAd ( array (
									'bikeID' => $extData ['vID'] 
							) );
							
							if (($bikeDB != false) && is_array ( $bikeDB ) && (count ( $bikeDB ) > 0)) {
								$bikeDB = $bikeDB [0];
								$bike = $this->transformMobile2BikeStruct ( $extData );
								$bike = $this->filterBikeData ( $bike );
								if (! isset ( $bike ['error'] )) {
									$p ['EXT_DATA'] = $extData;
									$p ['V_DATA'] = array (
											'vType' => System_Properties::BIKE_ABRV,
											'vID' => $bikeDB ['bikeID'] 
									);
									
									$bikeUpd = db_updBikeAds ( array (
											System_Properties::SQL_SET => $bike,
											System_Properties::SQL_WHERE => array (
													'bikeID' => $bikeDB ['bikeID'] 
											) 
									) );
									if ($bikeUpd != false) {
										// update bike extra
										// $this -> processMobileBikeExtra($p);
									}
									
									// update pics
									$this->processAdsPic ( $p );
									array_push ( $this->prot ['INFO'], $lang ['INFO_11'] . $extData ['D'] );
								}
								db_updMobile ( array (
										System_Properties::SQL_SET => array (
												'mobileNew' => '0' 
										),
										System_Properties::SQL_WHERE => array (
												'mobileID' => $extData ['mobileID'] 
										) 
								) );
								// , 'print'=>true
							} else {
								$doInsert = true;
							}
						} 						

						// TRUCK?
						else if (isset ( $extData ['vType'] ) && ($extData ['vType'] == System_Properties::TRUCK_ABRV)) {
							$truckDB = db_selTruckAd ( array (
									'truckID' => $extData ['vID'] 
							) );
							
							if (($truckDB != false) && is_array ( $truckDB ) && (count ( $truckDB ) > 0)) {
								$truckDB = $truckDB [0];
								$truck = $this->transformMobile2TruckStruct ( $extData );
								$truck = $this->filterTruckData ( $truck );
								if (! isset ( $truck ['error'] )) {
									$p ['EXT_DATA'] = $extData;
									$p ['V_DATA'] = array (
											'vType' => System_Properties::TRUCK_ABRV,
											'vID' => $truckDB ['truckID'] 
									);
									
									$truckUpd = db_updTruckAds ( array (
											System_Properties::SQL_SET => $truck,
											System_Properties::SQL_WHERE => array (
													'truckID' => $truckDB ['truckID'] 
											) 
									) );
									if ($truckUpd != false) {
										// update truck extra
										// $this -> processMobileTruckExtra($p);
									}
									
									// update pics
									$this->processAdsPic ( $p );
									array_push ( $this->prot ['INFO'], $lang ['INFO_11'] . $extData ['D'] );
								}
								db_updMobile ( array (
										System_Properties::SQL_SET => array (
												'mobileNew' => '0' 
										),
										System_Properties::SQL_WHERE => array (
												'mobileID' => $extData ['mobileID'] 
										) 
								) );
								// , 'print'=>true
							} else {
								$doInsert = true;
							}
						}
					}
					// Process a new insertion
					if ($doInsert == true) {
						$updMobileSQLSet = array (
								'mobileNew' => '0' 
						);
						
						// CAR?
						if (isset ( $extData ['vType'] ) && ($extData ['vType'] == System_Properties::CAR_ABRV)) {
							$car = $this->transformMobile2CarStruct ( $extData );
							$car = $this->filterCarData ( $car );
							if (! isset ( $car ['error'] )) {
								$carID = db_insCarAds ( $car );
								if (($carID != false) && is_numeric ( $carID )) {
									$updMobileSQLSet ['vID'] = $carID;
									
									$p ['EXT_DATA'] = $extData;
									$p ['V_DATA'] = array (
											'vType' => System_Properties::CAR_ABRV,
											'vID' => $carID 
									);
									// update car extra
									$this->processMobileCarExtra ( $p );
									
									// update pics
									$this->processAdsPic ( $p );
									array_push ( $this->prot ['INFO'], $lang ['INFO_10'] . $extData ['D'] );
								}
							}
							
							if (count ( $updMobileSQLSet ) > 0) {
								db_updMobile ( array (
										System_Properties::SQL_SET => $updMobileSQLSet,
										System_Properties::SQL_WHERE => array (
												'mobileID' => $extData ['mobileID'] 
										) 
								) );
							}
						} // BIKE?
						elseif (isset ( $extData ['vType'] ) && ($extData ['vType'] == System_Properties::BIKE_ABRV)) {
							$bike = $this->transformMobile2BikeStruct ( $extData );
							$bike = $this->filterBikeData ( $bike );
							if (! isset ( $bike ['error'] )) {
								$bikeID = db_insBikeAds ( $bike );
								if (($bikeID != false) && is_numeric ( $bikeID )) {
									$updMobileSQLSet ['vID'] = $bikeID;
									
									$p ['EXT_DATA'] = $extData;
									$p ['V_DATA'] = array (
											'vType' => System_Properties::BIKE_ABRV,
											'vID' => $bikeID 
									);
									// update bike extra
									// $this -> processMobileBikeExtra($p);
									
									// update pics
									$this->processAdsPic ( $p );
									array_push ( $this->prot ['INFO'], $lang ['INFO_10'] . $extData ['D'] );
								}
							}
							
							if (count ( $updMobileSQLSet ) > 0) {
								db_updMobile ( array (
										System_Properties::SQL_SET => $updMobileSQLSet,
										System_Properties::SQL_WHERE => array (
												'mobileID' => $extData ['mobileID'] 
										) 
								) );
							}
						} // TRUCK?
						elseif (isset ( $extData ['vType'] ) && ($extData ['vType'] == System_Properties::TRUCK_ABRV)) {
							$truck = $this->transformMobile2TruckStruct ( $extData );
							$truck = $this->filterTruckData ( $truck );
							if (! isset ( $truck ['error'] )) {
								$truckID = db_insTruckAds ( $truck );
								if (($truckID != false) && is_numeric ( $truckID )) {
									$updMobileSQLSet ['vID'] = $truckID;
									
									$p ['EXT_DATA'] = $extData;
									$p ['V_DATA'] = array (
											'vType' => System_Properties::TRUCK_ABRV,
											'vID' => $truckID 
									);
									// update truck extra
									// $this -> processMobileTruckExtra($p);
									
									// update pics
									$this->processAdsPic ( $p );
									array_push ( $this->prot ['INFO'], $lang ['INFO_10'] . $extData ['D'] );
								}
							}
							
							if (count ( $updMobileSQLSet ) > 0) {
								db_updMobile ( array (
										System_Properties::SQL_SET => $updMobileSQLSet,
										System_Properties::SQL_WHERE => array (
												'mobileID' => $extData ['mobileID'] 
										) 
								) );
								// , 'print' => true
							}
						}
					}
				}
			}
		}
	}
	/*
	 * private function cleanAction() {
	 * // $this -> getFrontController() -> setParam('noViewRenderer', true);
	 * // include_once('default/models/default/db_delVPic.php');
	 * include_once ('default/models/default/db_selVPic.php');
	 * // include_once('default/models/default/db_insVPic.php');
	 * // $files = scandir('.'.System_Properties::PIC_PATH);
	 * $files = scandir ( '/var/customers/webs/autotunes/www/web' . System_Properties::PIC_PATH );
	 *
	 * if (is_array ( $files )) {
	 * foreach ( $files as $key => $file ) {
	 *
	 * if ($file != '.' && $file != '..') {
	 * $fileUnit = explode ( '.', $file );
	 * if (isset ( $fileUnit [0] )) {
	 * $parts = explode ( '_', $fileUnit [0] );
	 * if ((count ( $parts ) == 3) && isset ( $parts [2] )) {
	 * $picDB = db_selVPic ( array (
	 * 'vPicID' => trim ( $parts [2] )
	 * ) );
	 * if (($picDB == false) || (is_array ( $picDB ) && (count ( $picDB ) == 0))) {
	 * @unlink ( '.' . System_Properties::PIC_PATH . '/' . $file );
	 * }
	 * }
	 * }
	 * }
	 * }
	 * }
	 * }
	 */
	
	/**
	 * This function extracts all vehicle extra information from a MOBILE data set
	 */
	private function processMobileCarExtra($p = null) {
		$extData = null;
		$vID = null;
		$vType = null;
		
		$lang = $this->lang;
		
		if (isset ( $p ['EXT_DATA'] )) {
			$extData = $p ['EXT_DATA'];
		}
		
		if (isset ( $p ['V_DATA'] ['vID'] )) {
			$vID = $p ['V_DATA'] ['vID'];
		}
		
		if (isset ( $p ['V_DATA'] ['vType'] )) {
			$vType = $p ['V_DATA'] ['vType'];
		}
		
		if (($extData != null) && ($vID != null) && ($vType != null)) {
			// delete car extra
			db_delCar2Ext ( array (
					'carID' => $vID 
			) );
			
			// fetch all car extra
			$carExtDB = db_selCarExt ();
			
			if (($carExtDB != false) && is_array ( $carExtDB )) {
				foreach ( $carExtDB as $key => $kVal ) {
					if (isset ( $lang ['V_EXTRA'] [$kVal ['vextID']] ) && (($kVal ['rgt'] - $kVal ['lft']) <= 1)) {
						$carExtTxt = $lang ['V_EXTRA'] [$kVal ['vextID']];
						
						// Mwst.
						if (isset ( $extData ['L'] ) && ($extData ['L'] == 1) && stristr ( $carExtTxt, 'mwst' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// oldtimer
						if (isset ( $extData ['N'] ) && ($extData ['N'] == 1) && stristr ( $carExtTxt, 'old' ) && stristr ( $carExtTxt, 'timer' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// klimaautomatik
						if (isset ( $extData ['R'] ) && ($extData ['AG'] == 2) && stristr ( $carExtTxt, 'klima' ) && stristr ( $carExtTxt, 'auto' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						} // klima
						elseif (isset ( $extData ['R'] ) && ($extData ['AF'] == 1) && stristr ( $carExtTxt, 'klima' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Taxi
						if (isset ( $extData ['S'] ) && ($extData ['S'] == 1) && stristr ( $carExtTxt, 'taxi' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Behindertengerecht
						if (isset ( $extData ['T'] ) && ($extData ['T'] == 1) && stristr ( $carExtTxt, 'behindert' ) && stristr ( $carExtTxt, 'gerecht' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// unsere empfehlung
						if (isset ( $extData ['W'] ) && ($extData ['W'] == 1) && stristr ( $carExtTxt, 'unser' ) && stristr ( $carExtTxt, 'empfehlung' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Garantie
						if (isset ( $extData ['AE'] ) && ($extData ['AE'] == 1) && stristr ( $carExtTxt, 'gebraucht' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Alufelgen
						if (isset ( $extData ['AF'] ) && ($extData ['AF'] == 1) && stristr ( $carExtTxt, 'alu' ) && stristr ( $carExtTxt, 'felgen' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Leichtmetallfelgen
						if (isset ( $extData ['AF'] ) && ($extData ['AF'] == 1) && stristr ( $carExtTxt, 'leicht' ) && stristr ( $carExtTxt, 'metal' ) && stristr ( $carExtTxt, 'felgen' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// ABS
						if (isset ( $extData ['AH'] ) && ($extData ['AH'] == 1) && stristr ( $carExtTxt, 'abs' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// ESP
						if (isset ( $extData ['AG'] ) && ($extData ['AG'] == 1) && ((stristr ( $carExtTxt, 'stab' ) && stristr ( $carExtTxt, 'program' )) || stristr ( $carExtTxt, 'esp' ))) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Anhängerkupplung
						if (isset ( $extData ['AI'] ) && ($extData ['AI'] == 1) && stristr ( $carExtTxt, 'anhäng' ) && stristr ( $carExtTxt, 'kupplung' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Lederausstattung
						if (isset ( $extData ['AJ'] ) && ($extData ['AJ'] == 1) && stristr ( $carExtTxt, 'lederaus' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Wegfahrsperre
						if (isset ( $extData ['AK'] ) && ($extData ['AK'] == 1) && stristr ( $carExtTxt, 'fahr' ) && stristr ( $carExtTxt, 'sperre' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Navigationssystem
						if (isset ( $extData ['AL'] ) && ($extData ['AL'] == 1) && stristr ( $carExtTxt, 'navi' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Schiebedach
						if (isset ( $extData ['AM'] ) && ($extData ['AM'] == 1) && stristr ( $carExtTxt, 'schiebedach' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Zentralverriegelung
						if (isset ( $extData ['AN'] ) && ($extData ['AN'] == 1) && stristr ( $carExtTxt, 'zentral' ) && stristr ( $carExtTxt, 'riegel' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// elektr. Fensterheber
						if (isset ( $extData ['AI'] ) && ($extData ['AI'] == 1) && stristr ( $carExtTxt, 'fensterhe' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Allrad
						if (isset ( $extData ['AP'] ) && ($extData ['AP'] == 1) && stristr ( $carExtTxt, 'all' ) && stristr ( $carExtTxt, 'rad' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Servolenkung
						if (isset ( $extData ['AR'] ) && ($extData ['AR'] == 1) && stristr ( $carExtTxt, 'servolenk' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Biodiesel geeignet
						if (isset ( $extData ['AT'] ) && ($extData ['AT'] == 1) && stristr ( $carExtTxt, 'bio' ) && stristr ( $carExtTxt, 'diesel' ) && stristr ( $carExtTxt, 'geeignet' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Schekcheft gepflegt
						if (isset ( $extData ['AU'] ) && ($extData ['AU'] == 1) && stristr ( $carExtTxt, 'scheckheft' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Katalysator
						if (isset ( $extData ['AV'] ) && ($extData ['AV'] == 1) && stristr ( $carExtTxt, 'katalysat' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Kickstarter
						if (isset ( $extData ['AW'] ) && ($extData ['AW'] == 1) && stristr ( $carExtTxt, 'kick' ) && stristr ( $carExtTxt, 'start' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// estarter
						if (isset ( $extData ['AX'] ) && ($extData ['AX'] == 1) && stristr ( $carExtTxt, 'estart' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Tempomat
						if (isset ( $extData ['BM'] ) && ($extData ['BM'] == 1) && stristr ( $carExtTxt, 'tempomat' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Standheizung
						if (isset ( $extData ['BN'] ) && ($extData ['BN'] == 1) && stristr ( $carExtTxt, 'stand' ) && stristr ( $carExtTxt, 'heizung' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Kabiine
						if (isset ( $extData ['BO'] ) && ($extData ['BO'] == 1) && stristr ( $carExtTxt, 'kabine' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Schutzdach
						if (isset ( $extData ['BP'] ) && ($extData ['BP'] == 1) && stristr ( $carExtTxt, 'schutz' ) && stristr ( $carExtTxt, 'dach' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Vollverkleidung
						if (isset ( $extData ['BQ'] ) && ($extData ['BQ'] == 1) && stristr ( $carExtTxt, 'voll' ) && stristr ( $carExtTxt, 'verkleidung' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Komunal
						if (isset ( $extData ['BR'] ) && ($extData ['BR'] == 1) && stristr ( $carExtTxt, 'Komunal' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Kran
						if (isset ( $extData ['BS'] ) && ($extData ['BS'] == 1) && stristr ( $carExtTxt, 'Kran' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Retarder
						if (isset ( $extData ['BT'] ) && ($extData ['BR'] == 1) && (stristr ( $carExtTxt, 'retard' ) || stristr ( $carExtTxt, 'intard' ))) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Schlafplatz
						if (isset ( $extData ['BU'] ) && ($extData ['BU'] == 1) && stristr ( $carExtTxt, 'schlaf' ) && stristr ( $carExtTxt, 'platz' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// TV
						if (isset ( $extData ['BV'] ) && ($extData ['BV'] == 1) && (strtolower ( $carExtTxt ) == 'tv')) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// WC
						if (isset ( $extData ['BW'] ) && ($extData ['BW'] == 1) && (strtolower ( $carExtTxt ) == 'wc')) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Ladeboardwand
						if (isset ( $extData ['BX'] ) && ($extData ['BX'] == 1) && stristr ( $carExtTxt, 'lade' ) && stristr ( $carExtTxt, 'bord' ) && stristr ( $carExtTxt, 'wand' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// schiebetür
						if (isset ( $extData ['BZ'] ) && ($extData ['BZ'] == 1) && stristr ( $carExtTxt, 'schiebe' ) && stristr ( $carExtTxt, 'tür' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Trennwand
						if (isset ( $extData ['CB'] ) && ($extData ['CB'] == 1) && stristr ( $carExtTxt, 'tren' ) && stristr ( $carExtTxt, 'wand' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// EBS
						if (isset ( $extData ['CC'] ) && ($extData ['CC'] == 1) && stristr ( $carExtTxt, 'ebs' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// vermitbar
						if (isset ( $extData ['CB'] ) && ($extData ['CB'] == 1) && stristr ( $carExtTxt, 'vermiet' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// kompressor
						if (isset ( $extData ['CE'] ) && ($extData ['CE'] == 1) && stristr ( $carExtTxt, 'kompresso' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// luftfederung
						if (isset ( $extData ['CF'] ) && ($extData ['CF'] == 1) && stristr ( $carExtTxt, 'luft' ) && stristr ( $carExtTxt, 'feder' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Scheibenbremse
						if (isset ( $extData ['CG'] ) && ($extData ['CG'] == 1) && stristr ( $carExtTxt, 'scheibe' ) && stristr ( $carExtTxt, 'bremse' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// fronthydraulik
						if (isset ( $extData ['CH'] ) && ($extData ['CH'] == 1) && stristr ( $carExtTxt, 'front' ) && stristr ( $carExtTxt, 'hydrauli' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// bss
						if (isset ( $extData ['CI'] ) && ($extData ['CI'] == 1) && stristr ( $carExtTxt, 'bss' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// schnellwechsel
						if (isset ( $extData ['CJ'] ) && ($extData ['CJ'] == 1) && stristr ( $carExtTxt, 'schnel' ) && stristr ( $carExtTxt, 'wechsel' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// zsa
						if (isset ( $extData ['CK'] ) && ($extData ['CK'] == 1) && stristr ( $carExtTxt, 'zsa' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// küche
						if (isset ( $extData ['CL'] ) && ($extData ['CL'] == 1) && stristr ( $carExtTxt, 'küche' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Kühlbox
						if (isset ( $extData ['CM'] ) && ($extData ['CM'] == 1) && stristr ( $carExtTxt, 'kühl' ) && stristr ( $carExtTxt, 'box' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Schlafsitze
						if (isset ( $extData ['CN'] ) && ($extData ['CN'] == 1) && stristr ( $carExtTxt, 'schlaf' ) && stristr ( $carExtTxt, 'sitz' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// frontheber
						if (isset ( $extData ['CO'] ) && ($extData ['CO'] == 1) && stristr ( $carExtTxt, 'front' ) && stristr ( $carExtTxt, 'heber' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						/*
						 * //sichtbar mnur für händler
						 * if (isset($extData['CO']) && ($extData['CO'] == 1)
						 * && stristr($carExtTxt, 'front') && stristr($carExtTxt, 'heber')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //envkv
						 * if (isset($extData['CR']) && ($extData['CR'] == 1)
						 * && stristr($carExtTxt, 'front') && stristr($carExtTxt, 'heber')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 */
						
						// Xenonscheinwerfer
						if (isset ( $extData ['CW'] ) && ($extData ['CW'] == 1) && stristr ( $carExtTxt, 'xenon' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Sitzheizung
						if (isset ( $extData ['CX'] ) && ($extData ['CX'] == 1) && stristr ( $carExtTxt, 'sitz' ) && stristr ( $carExtTxt, 'heizu' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Partikelfilter
						if (isset ( $extData ['CY'] ) && ($extData ['CY'] == 1) && stristr ( $carExtTxt, 'partikel' ) && stristr ( $carExtTxt, 'filter' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// elektr. Einparkhilfe
						if (isset ( $extData ['CZ'] ) && ($extData ['CZ'] == 1) && stristr ( $carExtTxt, 'einpark' ) && stristr ( $carExtTxt, 'hilfe' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Exportfahrzeug
						if (isset ( $extData ['DH'] ) && ($extData ['DH'] == 1) && stristr ( $carExtTxt, 'export' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Tageszulassung
						if (isset ( $extData ['DI'] ) && ($extData ['DI'] == 1) && stristr ( $carExtTxt, 'tag' ) && stristr ( $carExtTxt, 'zulassung' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Blickfänger
						if (isset ( $extData ['DJ'] ) && ($extData ['DJ'] == 1) && stristr ( $carExtTxt, 'blick' ) && stristr ( $carExtTxt, 'fänger' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						/*
						 * //seite 1 inserat
						 * if (isset($extData['DM']) && ($extData['DM'] == 1) && stristr($carExtTxt, 'blick') && stristr($carExtTxt, 'fänger')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 */
						
						// e10
						if (isset ( $extData ['DP'] ) && ($extData ['DP'] == 1) && stristr ( $carExtTxt, 'e10' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Pflanzenöl geeignet
						if (isset ( $extData ['DR'] ) && ($extData ['DR'] == 1) && stristr ( $carExtTxt, 'pflanz' ) && stristr ( $carExtTxt, 'öl' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Harnstofftank
						if (isset ( $extData ['DS'] ) && ($extData ['DS'] == 1) && stristr ( $carExtTxt, 'harn' ) && stristr ( $carExtTxt, 'stoff' ) && stristr ( $carExtTxt, 'tank' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Koffer
						if (isset ( $extData ['DT'] ) && ($extData ['DT'] == 1) && stristr ( $carExtTxt, 'koffer' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Sturzbügel
						if (isset ( $extData ['DU'] ) && ($extData ['DU'] == 1) && stristr ( $carExtTxt, 'sturz' ) && stristr ( $carExtTxt, 'bügel' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// scheibe
						if (isset ( $extData ['DV'] ) && ($extData ['DV'] == 1) && stristr ( $carExtTxt, 'scheibe' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// standklima
						if (isset ( $extData ['DW'] ) && ($extData ['DW'] == 1) && stristr ( $carExtTxt, 'stand' ) && stristr ( $carExtTxt, 'klima' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// s-s-bereifung
						if (isset ( $extData ['DX'] ) && ($extData ['DX'] == 1) && stristr ( $carExtTxt, 's' ) && stristr ( $carExtTxt, 'bereifung' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// straßenzulassung
						if (isset ( $extData ['DY'] ) && ($extData ['DY'] == 1) && stristr ( $carExtTxt, 'strasse' ) && stristr ( $carExtTxt, 'zulassung' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// etagenbett
						if (isset ( $extData ['DZ'] ) && ($extData ['DZ'] == 1) && stristr ( $carExtTxt, 'etage' ) && stristr ( $carExtTxt, 'bett' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// festbett
						if (isset ( $extData ['EA'] ) && ($extData ['EA'] == 1) && stristr ( $carExtTxt, 'fest' ) && stristr ( $carExtTxt, 'bett' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// heckgarage
						if (isset ( $extData ['EB'] ) && ($extData ['EB'] == 1) && stristr ( $carExtTxt, 'heck' ) && stristr ( $carExtTxt, 'garage' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// markise
						if (isset ( $extData ['EC'] ) && ($extData ['EC'] == 1) && stristr ( $carExtTxt, 'markise' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// sep-dusche
						if (isset ( $extData ['ED'] ) && ($extData ['ED'] == 1) && stristr ( $carExtTxt, 'dusch' ) && stristr ( $carExtTxt, 'sep' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// solaranlage
						if (isset ( $extData ['EE'] ) && ($extData ['EE'] == 1) && stristr ( $carExtTxt, 'solar' ) && stristr ( $carExtTxt, 'anlage' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// mittelsitzgruppe
						if (isset ( $extData ['EF'] ) && ($extData ['EF'] == 1) && stristr ( $carExtTxt, 'mittel' ) && stristr ( $carExtTxt, 'sitz' ) && stristr ( $carExtTxt, 'gruppe' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// rundsitzgruppe
						if (isset ( $extData ['EG'] ) && ($extData ['EG'] == 1) && stristr ( $carExtTxt, 'rund' ) && stristr ( $carExtTxt, 'sitz' ) && stristr ( $carExtTxt, 'gruppe' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// seitensitzgruppe
						if (isset ( $extData ['EH'] ) && ($extData ['EH'] == 1) && stristr ( $carExtTxt, 'seite' ) && stristr ( $carExtTxt, 'sitz' ) && stristr ( $carExtTxt, 'gruppe' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// hagelschaden
						if (isset ( $extData ['EI'] ) && ($extData ['EI'] == 1) && stristr ( $carExtTxt, 'hagel' ) && stristr ( $carExtTxt, 'schade' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// airbag
						if (isset ( $extData ['FP'] ) && ($extData ['FP'] == 2) && stristr ( $carExtTxt, 'airbag' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Beifahrerairbag
						if (isset ( $extData ['FP'] ) && ($extData ['FP'] == 3) && stristr ( $carExtTxt, 'airbag' ) && stristr ( $carExtTxt, 'beifahr' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						// Seitenairbag
						if (isset ( $extData ['FP'] ) && ($extData ['FP'] == 4) && stristr ( $carExtTxt, 'airbag' ) && stristr ( $carExtTxt, 'seite' )) {
							db_insCar2Ext ( array (
									'carExtID' => $kVal ['carExtID'],
									'carID' => $vID 
							) );
						}
						
						/*
						 * //Alarmanlage
						 * if (isset($extData['AU']) && ($extData['AU'] == 1) && stristr($carExtTxt, 'alarm') && stristr($carExtTxt, 'anlage')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Radio/CD
						 * /*
						 * if (isset($extData['AN']) && ($extData['AN'] == 1) && stristr($carExtTxt, 'radio')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']));
						 * }elseif (isset($extData['AN']) && ($extData['AN'] == 1) && stristr($carExtTxt, 'CD')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Traktionskontrolle
						 * if (isset($extData['AW']) && ($extData['AW'] == 1) && stristr($carExtTxt, 'traktion')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Tuning
						 * if (isset($extData['AY']) && ($extData['AY'] == 1) && stristr($carExtTxt, 'tuning')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Dachträger
						 * if (isset($extData['BA']) && ($extData['BA'] == 1) && stristr($carExtTxt, 'dach') && stristr($carExtTxt, 'träger')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Bordcomputer
						 * if (isset($extData['BB']) && ($extData['BB'] == 1) && stristr($carExtTxt, 'bord') && stristr($carExtTxt, 'compu')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Nebelscheinwerfer
						 * if (isset($extData['BD']) && ($extData['BD'] == 1) && stristr($carExtTxt, 'nebel') && stristr($carExtTxt, 'scheinwerf')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Radio
						 * if (isset($extData['BF']) && ($extData['BF'] == 1) && stristr($carExtTxt, 'radio')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //behindertengerecht
						 * if (isset($extData['BO']) && ($extData['BO'] == 1) && stristr($carExtTxt, 'behindert') && stristr($carExtTxt, 'gerecht')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 *
						 * //Dekra siegel
						 * if (isset($extData['BP']) && ($extData['BP'] == 1) && stristr($carExtTxt, 'dekra') && stristr($carExtTxt, 'siegel')){
						 * db_insCar2Ext(array('carExtID' => $kVal['carExtID']
						 * , 'carID' => $vID));
						 * }
						 */
					}
				}
			}
		}
		return $p;
	}
	
	/**
	 * Update or insert new photos for the advrtisement
	 */
	private function processAdsPic($p) {
		$extData = null;
		$vType = null;
		$vID = null;
		$picNameUpload = null;
		$picNameDir = null;
		
		if (isset ( $p ['EXT_DATA'] )) {
			$extData = $p ['EXT_DATA'];
		}
		
		if (isset ( $p ['V_DATA'] ['vType'] )) {
			$vType = $p ['V_DATA'] ['vType'];
		}
		
		if (isset ( $p ['V_DATA'] ['vID'] )) {
			$vID = $p ['V_DATA'] ['vID'];
		}
		
		if (isset ( $p ['PIC_NAME'] ['FILE'] )) {
			$picNameUpload = $p ['PIC_NAME'] ['FILE'];
		}
		
		if (isset ( $p ['PIC_NAME'] ['DIR'] )) {
			$picNameDir = $p ['PIC_NAME'] ['DIR'];
		}
		
		if (($vType != null) && ($vID != null) && ($picNameDir != null) && ($extData != null) && (isset ( $extData ['AA'] ) || isset ( $extData ['B'] )) && is_array ( $picNameUpload ) && (count ( $picNameUpload ) > 0)) {
			$extPicID = ($extData ['AA'] != null && $extData ['AA'] != false) ? $extData ['AA'] : $extData ['B'];
			
			// select all current vehicle pictures and delete them
			$vPicID = array ();
			$vPic = db_selVPic ( array (
					'vID' => $vID,
					'vType' => $vType 
			) );
			if (is_array ( $vPic )) {
				foreach ( $vPic as $key => $kVal ) {
					array_push ( $vPicID, $kVal ['vPicID'] );
				}
				if (count ( $vPicID ) > 0) {
					// delete pictures in the database
					if (db_delVPic ( array (
							'vPicID' => $vPicID 
					) ) != false) {
						// delete picutres from filesystem
						foreach ( $vPic as $key => $kVal ) {
							if (isset ( $kVal ['vType'] ) && isset ( $kVal ['vID'] ) && isset ( $kVal ['vPicID'] )) {
								// $picPath = '/var/customers/webs/autotunes/www/web' . System_Properties::PIC_PATH . '/' . strtolower ( $kVal ['vType'] ) . '_' . $kVal ['vID'] . '_' . $kVal ['vPicID'] . '.jpeg';
								$picPath = './' . System_Properties::PIC_PATH . '/' . strtolower ( $kVal ['vType'] ) . '_' . $kVal ['vID'] . '_' . $kVal ['vPicID'] . '.jpeg';
								unlink ( $picPath );
							}
						}
					}
				}
			}
			
			// now insert all other pictures
			foreach ( $picNameUpload as $key => $kVal ) {
				$mimeTypeDetails = explode ( '.', basename ( $kVal ) );
				$mimeTypeDetails = strtolower ( $mimeTypeDetails [1] );
				if ((stristr ( $kVal, $extPicID ) != false) && in_array ( $mimeTypeDetails, System_Properties::$PIC_EXT )) {
					$fileSrc = $picNameDir . '/' . $kVal;
					if (file_exists ( $fileSrc )) {
						$imgFilter = new ImageFilter ( array (
								'imgTrgWidth' => System_Properties::PIC_SIZE_W,
								'imgTrgHeight' => System_Properties::PIC_SIZE_H,
								'imgSrcExtension' => $mimeTypeDetails 
						) );
						$imgFilter->filter ( $fileSrc );
						
						$vPicID = db_insVPic ( array (
								'vType' => $vType,
								'vID' => $vID,
								'vPicTMP' => 0 
						) );
						if (($vPicID != null) && is_numeric ( $vPicID )) {
							/*
							 * if ($this->docRoot != null) {
							 * $fileDest = $this->docRoot . 'web/' . str_ireplace ( '/', '', System_Properties::PIC_PATH ) . '/' . $vType . '_' . $vID . '_' . $vPicID . '.jpeg';
							 * } else {
							 * $fileDest = './' . str_ireplace ( '/', '', System_Properties::PIC_PATH ) . '/' . $vType . '_' . $vID . '_' . $vPicID . '.jpeg';
							 * }
							 */
							$fileDest = './' . System_Properties::PIC_PATH . '/' . $vType . '_' . $vID . '_' . $vPicID . '.jpeg';
							
							if (copy ( $fileSrc, $fileDest )) {
								@chown ( $fileDest, System_Properties::FTP_USER );
								@chgrp ( $fileDest, System_Properties::FTP_GROUP );
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
	private function transformMobile2CarStruct($p) {
		$car = array ();
		$lang = $this->lang;
		$car ['carModelVar'] = '';
		$car ['carHSN'] = '';
		$car ['carTSN'] = '';
		$car ['carFIN'] = '';
		$car ['carLocCountry'] = 'DE';
		$car ['carKM'] = '0';
		// carBrandID
		if (isset ( $p ['D'] )) {
			$carBrand = db_selCarBrand ( array (
					'brandNameL' => strtolower ( $p ['D'] ) 
			) );
			// , 'print' => true
			
			if (($carBrand != false) && is_array ( $carBrand ) && (count ( $carBrand ) > 0)) {
				$carBrand = $carBrand [0];
				$car ['carBrandID'] = $carBrand ['carBrandID'];
				$car ['carBrand'] = $car ['carBrandID'];
				
				// carModelID
				if (isset ( $p ['E'] )) {
					$carModel = db_selCarModel ( array (
							'carModelNameL' => $p ['E'],
							'carBrandID' => $carBrand ['carBrandID'] 
					) );
					if (($carModel != false) && is_array ( $carModel ) && (count ( $carModel ) > 0)) {
						$carModel = $carModel [0];
						$car ['carModel'] = $carModel ['carModelID'];
						$car ['carModelID'] = $carModel ['carModelID'];
					} else {
						$carModel = db_selCarModel ( array (
								'carBrandID' => $carBrand ['carBrandID'] 
						) );
						if (($carModel != false) && is_array ( $carModel )) {
							$carModelLast = null;
							foreach ( $carModel as $key => $kVal ) {
								if (isset ( $kVal ['carModelName'] ) && stristr ( $p ['E'], $kVal ['carModelName'] ) && (strlen ( $carModelLast ) < strlen ( $kVal ['carModelName'] ))) {
									$car ['carModel'] = $kVal ['carModelID'];
									$car ['carModelID'] = $kVal ['carModelID'];
									$carModelLast = $kVal ['carModelName'];
									// break;
								}
							}
						}
					}
				}
			}
		}
		
		// carPrice
		if (isset ( $p ['K'] ) && is_numeric ( $p ['K'] )) {
			$car ['carPrice'] = $p ['K'];
		}
		
		// mwst
		if (isset ( $p ['L'] ) && is_numeric ( $p ['L'] )) {
			$car ['mwst'] = $p ['L'];
		}
		
		// mwstSatz
		if (isset ( $p ['AD'] ) && is_numeric ( $p ['AD'] )) {
			$car ['mwstSatz'] = $p ['AD'];
		}
		
		// carPriceType
		$car ['carPriceType'] = '0';
		
		// carPriceCurr
		if (isset ( $p ['AC'] )) {
			// see $lang['TXT_74'] for all currencies
			if (strtolower ( $p ['AC'] ) == 'eur') {
				$car ['carPriceCurr'] = '0';
			} elseif (strtolower ( $p ['AC'] ) == 'rubel') {
				$car ['carPriceCurr'] = 2;
			}
		}
		
		// carKM
		if (isset ( $p ['J'] ) && is_numeric ( $p ['J'] )) {
			$car ['carKM'] = $p ['J'];
			
			// carKMType -> see var. $lang['TXT_75']
			$car ['carKMType'] = '0';
		}
		
		// carPower
		if (isset ( $p ['F'] ) && is_numeric ( $p ['F'] )) {
			$car ['carPower'] = $p ['F'];
			
			// carPowerType -> see var. $lang['TXT_72']
			$car ['carPowerType'] = '0';
		}
		
		// carEZM, carEZY
		if (isset ( $p ['V'] ) && ($p ['V'] == '1')) {
			$car ['carEZM'] = '-1';
			$car ['carEZY'] = '9999';
		} elseif (isset ( $p ['I'] )) {
			$mmyyyy = explode ( '.', $p ['I'] );
			if (isset ( $mmyyyy [0] )) {
				$car ['carEZM'] = $mmyyyy [0]; // carEZM
			}
			if (isset ( $mmyyyy [1] )) {
				$car ['carEZY'] = $mmyyyy [1]; // carEZY
			}
		}
		
		// carTUVM, carTUVY
		if (isset ( $p ['G'] )) {
			$mmyyyy = explode ( '.', $p ['G'] );
			if (isset ( $mmyyyy [0] )) {
				$car ['carTUVM'] = $mmyyyy [0]; // carTUVM
			}
			if (isset ( $mmyyyy [1] )) {
				$car ['carTUVY'] = $mmyyyy [1]; // carTUVY
			}
		}
		
		// carAUM, carAUY
		if (isset ( $p ['H'] )) {
			$mmyyyy = explode ( '.', $p ['H'] );
			if (isset ( $mmyyyy [0] )) {
				$car ['carAUM'] = $mmyyyy [0]; // carAUM
			}
			if (isset ( $mmyyyy [1] )) {
				$car ['carAUY'] = $mmyyyy [1]; // carAUY
			}
		}
		
		// carShift -> see var. $lang['V_SHIFT']
		if (isset ( $p ['DG'] )) {
			// keine Angabe
			if ($p ['DG'] == - 1) {
				$car ['carShift'] = - 1;
			} // Manuel?
elseif ($p ['DG'] == 1) {
				$car ['carShift'] = '0';
			} // Automatik?
elseif ($p ['DG'] == 1) {
				$car ['carShift'] = 1;
			} // Halbautomatik
elseif ($p ['DG'] == 3) {
				$car ['carShift'] = 3;
			}
		}
		
		// carWeight
		if (isset ( $p ['BD'] ) && is_numeric ( $p ['BD'] )) {
			$car ['carWeight'] = $p ['BD'];
		}
		
		/*
		 * //carCyl
		 * if (isset($p['U']) && is_numeric($p['U'])){
		 * $car['carCyl'] = $p['U'];
		 * }
		 */
		
		// carCub
		if (isset ( $p ['BA'] ) && is_numeric ( $p ['BA'] )) {
			$car ['carCub'] = $p ['BA'];
		}
		
		// carDoor -> see var. $lang['CAR_DOOR']
		if (isset ( $p ['AQ'] ) && is_numeric ( $p ['AQ'] )) {
			if (isset ( $lang ['CAR_DOOR'] ) && is_array ( $lang ['CAR_DOOR'] )) {
				foreach ( $lang ['CAR_DOOR'] as $key => $kVal ) {
					if ($kVal == $p ['AQ']) {
						$car ['carDoor'] = $key;
						break;
					}
				}
			}
		}
		
		// carUseIn
		if (isset ( $p ['CS'] ) && is_numeric ( str_replace ( ',', '.', $p ['CS'] ) )) {
			$car ['carUseIn'] = str_replace ( ',', '.', $p ['CS'] );
		}
		
		// carUseOut
		if (isset ( $p ['CT'] ) && is_numeric ( str_replace ( ',', '.', $p ['CT'] ) )) {
			$car ['carUseOut'] = str_replace ( ',', '.', $p ['CT'] );
		}
		
		// carCO2
		if (isset ( $p ['CV'] ) && is_numeric ( str_replace ( ',', '.', $p ['CV'] ) )) {
			$car ['carCO2'] = str_replace ( ',', '.', $p ['CV'] );
		}
		
		$car ['carState'] = 2;
		// carState -> see var. $lang['V_STATE']
		if (isset ( $p ['P'] ) && ($p ['P'] == 1)) {
			// Blechschaden
			$car ['carState'] = 3;
		} /*
		   * elseif (isset($p['AE']) && ($p['AE'] == 1)){
		   * //Unfallfahrzeug
		   * $car['carState'] = 4;
		   * }
		   */
elseif (isset ( $p ['DI'] ) && ($p ['DI'] == 1)) {
			// Gebruachtwagen
			$car ['carState'] = 2;
		} elseif (isset ( $p ['U'] ) && ($p ['U'] == 1)) {
			// Jahreswagen
			$car ['carState'] = 1;
		} elseif (isset ( $p ['V'] ) && ($p ['V'] == 1)) {
			// Neuwagen
			$car ['carState'] = '0';
		} elseif (isset ( $p ['AY'] ) && ($p ['AY'] == 1)) {
			// Vorführwagen
			$car ['carState'] = 5;
		}
		
		// carCat -> see var. $lang['CAR_CAT']
		if (isset ( $p ['C'] )) {
			$cat = $this->detCat ( array_merge ( $p, array (
					'vType' => System_Properties::CAR_ABRV 
			) ) );
			if (($cat != false) && isset ( $cat ['cat'] ) && isset ( $cat ['vType'] )) {
				$car ['carCat'] = $cat ['cat'];
				if (! isset ( $car ['vType'] )) {
					$car ['vType'] = $cat ['vType'];
				}
			}
			/*
			 * if(stristr($p['T'], 'bus') != false){
			 * //Kleinbus
			 * $car['carCat'] = 6;
			 * }elseif (stristr($p['T'], 'cab') != false){
			 * //Cabrio
			 * $car['carCat'] = '0';
			 * }elseif (stristr($p['T'], 'coup') != false){
			 * //Coupe
			 * $car['carCat'] = 1;
			 * }elseif (stristr($p['T'], 'ndewagen') != false){
			 * //Geländewagen
			 * $car['carCat'] = 2;
			 * }elseif ((stristr($p['T'], 'kombi') != false) || (stristr($p['T'], 'van') != false)){
			 * //Kombi/Van
			 * $car['carCat'] = 4;
			 * }elseif (stristr($p['T'], 'liefer') != false){
			 * //Lieferwagen
			 * $car['carCat'] = 8;
			 * }
			 */
		}
		
		// carFuel -> see var. $lang['V_FUEL']
		if (isset ( $p ['DF'] )) {
			$fuel = $p ['DF'];
			if ($fuel == 1) {
				// Benzin
				$car ['carFuel'] = '0';
			} elseif ($fuel == 2) {
				// diesel
				$car ['carFuel'] = 1;
			} /*
			   * elseif ($fuel == 4){
			   * //GAS
			   * $car['carFuel'] = 8;
			   * }
			   */
elseif ($fuel == 9) {
				// ethanol
				$car ['carFuel'] = 4;
			} elseif ($fuel == 6) {
				// elektro
				$car ['carFuel'] = 2;
			} elseif ($fuel == 3) {
				// LPG GAs
				$car ['carFuel'] = 3;
			} elseif ($fuel == 4) {
				// CNG Gas
				$car ['carFuel'] = 6;
			} elseif ($fuel == 7) {
				// Hybrid
				$car ['carFuel'] = 7;
			} elseif ($fuel == 8) {
				// Wasserstoff
				$car ['carFuel'] = 5;
			}
		}
		
		// carClr -> see var. $lang['V_CLR']
		if (isset ( $p ['Q'] )) {
			foreach ( $lang ['V_CLR'] as $key => $kVal ) {
				if (stristr ( $kVal, $p ['Q'] ) != false) {
					$car ['carClr'] = $key;
					break;
				}
			}
		}
		
		// carClrMet
		if (isset ( $p ['AB'] ) && ($p ['AB'] == 1)) {
			$car ['carClrMet'] = 1;
		}
		
		// carEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset ( $p ['BJ'] )) {
			$emissionNorm = $p ['BJ'];
			if ($emissionNorm == 1) {
				// EURO 1
				$car ['carEmissionNorm'] = 1;
			} elseif ($emissionNorm == 2) {
				// EURO 2
				$car ['carEmissionNorm'] = 2;
			} elseif ($emissionNorm == 3) {
				// EURO 3
				$car ['carEmissionNorm'] = 3;
			} elseif ($emissionNorm == 4) {
				// EURO 4
				$car ['carEmissionNorm'] = 4;
			} elseif ($emissionNorm == 5) {
				// EURO 5
				// $car['carEmissionNorm'] = 5;
			} elseif (stristr ( $p ['CI'], 6 ) != false) {
				// EURO 6
				// $car['carEmissionNorm'] = 5;
			}
		}
		
		// carEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset ( $p ['AR'] )) {
			$ecologicTag = $p ['AR'];
			if ($ecologicTag == 1) {
				// Keine
				$car ['carEcologicTag'] = '0';
			} elseif ($ecologicTag == 2) {
				// ROT
				$car ['carEcologicTag'] = 1;
			} elseif ($ecologicTag == 3) {
				// Gelb
				$car ['carEcologicTag'] = 2;
			} elseif ($ecologicTag == 4) {
				// Grün
				$car ['carEcologicTag'] = 3;
			}
		}
		
		// carKlima -> see var. $lang['V_KLIMA']
		if (isset ( $p ['R'] ) && ($p ['R'] == 1)) {
			// Klima
			$car ['carKlima'] = '0';
		} elseif (isset ( $p ['R'] ) && ($p ['R'] == 2)) {
			// Klimaautomatik
			$car ['carKlima'] = 1;
		}
		
		if (isset ( $p ['O'] )) {
			$car ['carFIN'] = $p ['O'];
		}
		
		// carDesc
		if (isset ( $p ['Z'] )) {
			$car ['carDesc'] = $p ['Z'];
		}
		
		// carEEK
		if (isset ( $p ['FN'] )) {
			$car ['carEEK'] = $p ['FN'];
		}
		
		// userAdsLength
		$car ['userAdsLength'] = 12;
		
		$user = null;
		if (isset ( $p ['USER_DATA'] ) && is_array ( $p ['USER_DATA'] )) {
			$user = $p ['USER_DATA'];
		} elseif (isset ( $this->userNS->userData )) {
			$user = $this->userNS->userData;
		} elseif ($this->userData != null) {
			$user = $this->userData;
		}
		
		if ($user != null) {
			
			// userAds -> see var. $lang['TXT_33']
			$car ['userAds'] = '-1';
			if (isset ( $user ['userMode'] ) && ($user ['userMode'] != 3)) {
				$car ['userAds'] = $user ['userMode'];
			}
			
			// userFirm
			if (isset ( $user ['userFirm'] )) {
				$car ['userFirm'] = $user ['userFirm'];
			}
			
			// userNName
			if (isset ( $user ['userNName'] )) {
				$car ['userNName'] = $user ['userNName'];
			}
			
			// userVName
			if (isset ( $user ['userVName'] )) {
				$car ['userVName'] = $user ['userVName'];
			}
			
			// userEMail
			if (isset ( $user ['userEMail'] )) {
				$car ['userEMail'] = $user ['userEMail'];
			}
			
			// userPLZ
			if (isset ( $user ['userPLZ'] )) {
				$car ['userPLZ'] = $user ['userPLZ'];
				$car ['carLocPLZ'] = $user ['userPLZ'];
			}
			
			// userOrt
			if (isset ( $user ['userOrt'] )) {
				$car ['userOrt'] = $user ['userOrt'];
				$car ['carLocOrt'] = $user ['userOrt'];
			}
			
			// carLocCountry
			if (isset ( $user ['userCountry'] )) {
				$car ['carLocCountry'] = $user ['userCountry'];
			}
			
			// userTel1
			if (isset ( $user ['userTel1'] )) {
				$car ['userTel1'] = $user ['userTel1'];
			}
			
			// userTel2
			if (isset ( $user ['userTel2'] )) {
				$car ['userTel2'] = $user ['userTel2'];
			}
			
			// userAdress
			if (isset ( $user ['userAdress'] )) {
				$car ['userAdress'] = $user ['userAdress'];
			}
			
			// userTel2
			if (isset ( $user ['userTel2'] )) {
				$car ['userTel2'] = $user ['userTel2'];
			}
			
			// userID
			if (isset ( $user ['userID'] )) {
				$car ['userID'] = $user ['userID'];
			}
		}
		
		return $car;
	}
	
	/**
	 * This function filter all relevant data from the imported data set and fill a corresponding structure of BIKE table
	 */
	private function transformMobile2BikeStruct($p) {
		$bike = array ();
		$lang = $this->lang;
		$bike ['bikeModelVar'] = '';
		$bike ['bikeHSN'] = '';
		$bike ['bikeTSN'] = '';
		$bike ['bikeFIN'] = '';
		$bike ['bikeLocCountry'] = 'DE';
		// bikeBrandID
		if (isset ( $p ['D'] )) {
			$bikeBrand = db_selBikeBrand ( array (
					'brandNameL' => strtolower ( $p ['D'] ) 
			) );
			// , 'print' => true
			
			if (($bikeBrand != false) && is_array ( $bikeBrand ) && (count ( $bikeBrand ) > 0)) {
				$bikeBrand = $bikeBrand [0];
				$bike ['bikeBrandID'] = $bikeBrand ['bikeBrandID'];
				$bike ['bikeBrand'] = $bike ['bikeBrandID'];
				
				// bikeModelID
				if (isset ( $p ['E'] )) {
					$bikeModel = db_selBikeModel ( array (
							'bikeModelNameL' => $p ['E'],
							'bikeBrandID' => $bikeBrand ['bikeBrandID'] 
					) );
					if (($bikeModel != false) && is_array ( $bikeModel ) && (count ( $bikeModel ) > 0)) {
						$bikeModel = $bikeModel [0];
						$bike ['bikeModel'] = $bikeModel ['bikeModelID'];
						$bike ['bikeModelID'] = $bikeModel ['bikeModelID'];
					}
				}
			}
		}
		
		// bikePrice
		if (isset ( $p ['K'] ) && is_numeric ( $p ['K'] )) {
			$bike ['bikePrice'] = $p ['K'];
		}
		
		// mwst
		if (isset ( $p ['L'] ) && is_numeric ( $p ['L'] )) {
			$bike ['mwst'] = $p ['L'];
		}
		
		// mwstSatz
		if (isset ( $p ['AD'] ) && is_numeric ( $p ['AD'] )) {
			$bike ['mwstSatz'] = $p ['AD'];
		}
		
		// bikePriceType
		$bike ['bikePriceType'] = '0';
		
		// bikePriceCurr
		if (isset ( $p ['AC'] )) {
			// see $lang['TXT_74'] for all currencies
			if (strtolower ( $p ['AC'] ) == 'eur') {
				$bike ['bikePriceCurr'] = '0';
			} elseif (strtolower ( $p ['AC'] ) == 'rubel') {
				$bike ['bikePriceCurr'] = 2;
			}
		}
		
		// bikeKM
		if (isset ( $p ['J'] ) && is_numeric ( $p ['J'] )) {
			$bike ['bikeKM'] = $p ['J'];
			
			// bikeKMType -> see var. $lang['TXT_75']
			$bike ['bikeKMType'] = '0';
		}
		
		// bikePower
		if (isset ( $p ['F'] ) && is_numeric ( $p ['F'] )) {
			$bike ['bikePower'] = $p ['F'];
			
			// bikePowerType -> see var. $lang['TXT_72']
			$bike ['bikePowerType'] = '0';
		}
		
		// bikeEZM, bikeEZY
		if (isset ( $p ['V'] ) && ($p ['V'] == '1')) {
			$car ['bikeEZM'] = '-1';
			$car ['bikeEZY'] = '9999';
		} elseif (isset ( $p ['I'] )) {
			$mmyyyy = explode ( '.', $p ['I'] );
			if (isset ( $mmyyyy [0] )) {
				$bike ['bikeEZM'] = $mmyyyy [0]; // bikeEZM
			}
			if (isset ( $mmyyyy [1] )) {
				$bike ['bikeEZY'] = $mmyyyy [1]; // bikeEZY
			}
		}
		
		// bikeTUVM, bikeTUVY
		if (isset ( $p ['G'] )) {
			$mmyyyy = explode ( '.', $p ['G'] );
			if (isset ( $mmyyyy [0] )) {
				$bike ['bikeTUVM'] = $mmyyyy [0]; // bikeTUVM
			}
			if (isset ( $mmyyyy [1] )) {
				$bike ['bikeTUVY'] = $mmyyyy [1]; // bikeTUVY
			}
		}
		
		// bikeAUM, bikeAUY
		if (isset ( $p ['H'] )) {
			$mmyyyy = explode ( '.', $p ['H'] );
			if (isset ( $mmyyyy [0] )) {
				$bike ['bikeAUM'] = $mmyyyy [0]; // bikeAUM
			}
			if (isset ( $mmyyyy [1] )) {
				$bike ['bikeAUY'] = $mmyyyy [1]; // bikeAUY
			}
		}
		
		// bikeShift -> see var. $lang['V_SHIFT']
		if (isset ( $p ['DG'] )) {
			// keine Angabe
			if ($p ['DG'] == - 1) {
				$bike ['bikeShift'] = - 1;
			} // Manuel?
elseif ($p ['DG'] == 1) {
				$bike ['bikeShift'] = '0';
			} // Automatik?
elseif ($p ['DG'] == 1) {
				$bike ['bikeShift'] = 1;
			} // Halbautomatik
elseif ($p ['DG'] == 3) {
				$bike ['bikeShift'] = 3;
			}
		}
		
		// bikeWeight
		if (isset ( $p ['BD'] ) && is_numeric ( $p ['BD'] )) {
			$bike ['bikeWeight'] = $p ['BD'];
		}
		
		/*
		 * //bikeCyl
		 * if (isset($p['U']) && is_numeric($p['U'])){
		 * $bike['bikeCyl'] = $p['U'];
		 * }
		 */
		
		// bikeCub
		if (isset ( $p ['BA'] ) && is_numeric ( $p ['BA'] )) {
			$bike ['bikeCub'] = $p ['BA'];
		}
		
		// bikeDoor -> see var. $lang['BIKE_DOOR']
		if (isset ( $p ['AQ'] ) && is_numeric ( $p ['AQ'] )) {
			$bike ['bikeDoor'] = $p ['AQ'];
		}
		
		// bikeUseIn
		if (isset ( $p ['CS'] ) && is_numeric ( str_replace ( ',', '.', $p ['CS'] ) )) {
			$bike ['bikeUseIn'] = str_replace ( ',', '.', $p ['CS'] );
		}
		
		// bikeUseOut
		if (isset ( $p ['CT'] ) && is_numeric ( str_replace ( ',', '.', $p ['CT'] ) )) {
			$bike ['bikeUseOut'] = str_replace ( ',', '.', $p ['CT'] );
		}
		
		// bikeCO2
		if (isset ( $p ['CV'] ) && is_numeric ( str_replace ( ',', '.', $p ['CV'] ) )) {
			$bike ['bikeCO2'] = str_replace ( ',', '.', $p ['CV'] );
		}
		
		// bikeState -> see var. $lang['V_STATE']
		if (isset ( $p ['P'] ) && ($p ['P'] == 1)) {
			// Blechschaden
			$bike ['bikeState'] = 3;
		} elseif (isset ( $p ['AE'] ) && ($p ['AE'] == 1)) {
			// Unfallfahrzeug
			$bike ['bikeState'] = 4;
		} elseif (isset ( $p ['DI'] ) && ($p ['DI'] == 1)) {
			// Gebruachtwagen
			$bike ['bikeState'] = 2;
		} elseif (isset ( $p ['U'] ) && ($p ['U'] == 1)) {
			// Jahreswagen
			$bike ['bikeState'] = 1;
		} elseif (isset ( $p ['V'] ) && ($p ['V'] == 1)) {
			// Neuwagen
			$bike ['bikeState'] = '0';
		} elseif (isset ( $p ['AY'] ) && ($p ['AY'] == 1)) {
			// Vorführwagen
			$bike ['bikeState'] = 5;
		}
		
		// bikeCat -> see var. $lang['BIKE_CAT']
		if (isset ( $p ['C'] )) {
			$cat = $this->detCat ( array_merge ( $p, array (
					'vType' => System_Properties::BIKE_ABRV 
			) ) );
			if (($cat != false) && isset ( $cat ['cat'] ) && isset ( $cat ['vType'] )) {
				$bike ['bikeCat'] = $cat ['cat'];
				if (! isset ( $bike ['vType'] )) {
					$bike ['vType'] = $cat ['vType'];
				}
			}
			/*
			 * if(stristr($p['T'], 'bus') != false){
			 * //Kleinbus
			 * $bike['bikeCat'] = 6;
			 * }elseif (stristr($p['T'], 'cab') != false){
			 * //Cabrio
			 * $bike['bikeCat'] = '0';
			 * }elseif (stristr($p['T'], 'coup') != false){
			 * //Coupe
			 * $bike['bikeCat'] = 1;
			 * }elseif (stristr($p['T'], 'ndewagen') != false){
			 * //Geländewagen
			 * $bike['bikeCat'] = 2;
			 * }elseif ((stristr($p['T'], 'kombi') != false) || (stristr($p['T'], 'van') != false)){
			 * //Kombi/Van
			 * $bike['bikeCat'] = 4;
			 * }elseif (stristr($p['T'], 'liefer') != false){
			 * //Lieferwagen
			 * $bike['bikeCat'] = 8;
			 * }
			 */
		}
		
		// bikeFuel -> see var. $lang['V_FUEL']
		if (isset ( $p ['DF'] )) {
			$fuel = $p ['DF'];
			if ($fuel == 1) {
				// Benzin
				$bike ['bikeFuel'] = '0';
			} elseif ($fuel == 2) {
				// diesel
				$bike ['bikeFuel'] = 1;
			} /*
			   * elseif ($fuel == 4){
			   * //GAS
			   * $bike['bikeFuel'] = 8;
			   * }
			   */
elseif ($fuel == 9) {
				// ethanol
				$bike ['bikeFuel'] = 4;
			} elseif ($fuel == 6) {
				// elektro
				$bike ['bikeFuel'] = 2;
			} elseif ($fuel == 3) {
				// LPG GAs
				$bike ['bikeFuel'] = 3;
			} elseif ($fuel == 4) {
				// CNG Gas
				$bike ['bikeFuel'] = 6;
			} elseif ($fuel == 7) {
				// Hybrid
				$bike ['bikeFuel'] = 7;
			} elseif ($fuel == 8) {
				// Wasserstoff
				$bike ['bikeFuel'] = 5;
			}
		}
		
		// bikeClr -> see var. $lang['V_CLR']
		if (isset ( $p ['Q'] )) {
			foreach ( $lang ['V_CLR'] as $key => $kVal ) {
				if (stristr ( $kVal, $p ['Q'] ) != false) {
					$bike ['bikeFuel'] = $key;
					break;
				}
			}
		}
		
		// bikeClrMet
		if (isset ( $p ['AB'] ) && ($p ['AB'] == 1)) {
			$bike ['bikeClrMet'] = 1;
		}
		
		// bikeEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset ( $p ['BJ'] )) {
			$emissionNorm = $p ['BJ'];
			if ($emissionNorm == 1) {
				// EURO 1
				$bike ['bikeEmissionNorm'] = 1;
			} elseif ($emissionNorm == 2) {
				// EURO 2
				$bike ['bikeEmissionNorm'] = 2;
			} elseif ($emissionNorm == 3) {
				// EURO 3
				$bike ['bikeEmissionNorm'] = 3;
			} elseif ($emissionNorm == 4) {
				// EURO 4
				$bike ['bikeEmissionNorm'] = 4;
			} elseif ($emissionNorm == 5) {
				// EURO 5
				// $bike['bikeEmissionNorm'] = 5;
			} elseif (stristr ( $p ['CI'], 6 ) != false) {
				// EURO 6
				// $bike['bikeEmissionNorm'] = 5;
			}
		}
		
		// bikeEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset ( $p ['AR'] )) {
			$ecologicTag = $p ['AR'];
			if ($ecologicTag == 1) {
				// Keine
				$bike ['bikeEcologicTag'] = '0';
			} elseif ($ecologicTag == 2) {
				// ROT
				$bike ['bikeEcologicTag'] = 1;
			} elseif ($ecologicTag == 3) {
				// Gelb
				$bike ['bikeEcologicTag'] = 2;
			} elseif ($ecologicTag == 4) {
				// Grün
				$bike ['bikeEcologicTag'] = 3;
			}
		}
		
		// bikeKlima -> see var. $lang['V_KLIMA']
		if (isset ( $p ['R'] ) && ($p ['R'] == 1)) {
			// Klima
			$bike ['bikeKlima'] = '0';
		} elseif (isset ( $p ['R'] ) && ($p ['R'] == 2)) {
			// Klimaautomatik
			$bike ['bikeKlima'] = 1;
		}
		
		if (isset ( $p ['O'] )) {
			$bike ['bikeFIN'] = $p ['O'];
		}
		
		// bikeDesc
		if (isset ( $p ['Z'] )) {
			$bike ['bikeDesc'] = $p ['Z'];
		}
		
		// userAdsLength
		$bike ['userAdsLength'] = 12;
		
		$user = null;
		if (isset ( $p ['USER_DATA'] ) && is_array ( $p ['USER_DATA'] )) {
			$user = $p ['USER_DATA'];
		} elseif (isset ( $this->userNS->userData )) {
			$user = $this->userNS->userData;
		} elseif ($this->userData != null) {
			$user = $this->userData;
		}
		
		if ($user != null) {
			
			// userAds -> see var. $lang['TXT_33']
			$bike ['userAds'] = '-1';
			if (isset ( $user ['userMode'] ) && ($user ['userMode'] != 3)) {
				$bike ['userAds'] = $user ['userMode'];
			}
			
			// userFirm
			if (isset ( $user ['userFirm'] )) {
				$bike ['userFirm'] = $user ['userFirm'];
			}
			
			// userNName
			if (isset ( $user ['userNName'] )) {
				$bike ['userNName'] = $user ['userNName'];
			}
			
			// userVName
			if (isset ( $user ['userVName'] )) {
				$bike ['userVName'] = $user ['userVName'];
			}
			
			// userEMail
			if (isset ( $user ['userEMail'] )) {
				$bike ['userEMail'] = $user ['userEMail'];
			}
			
			// userPLZ
			if (isset ( $user ['userPLZ'] )) {
				$bike ['userPLZ'] = $user ['userPLZ'];
				$bike ['bikeLocPLZ'] = $user ['userPLZ'];
			}
			
			// userOrt
			if (isset ( $user ['userOrt'] )) {
				$bike ['userOrt'] = $user ['userOrt'];
				$bike ['bikeLocOrt'] = $user ['userOrt'];
			}
			
			// bikeLocCountry
			if (isset ( $user ['userCountry'] )) {
				$car ['bikeLocCountry'] = $user ['userCountry'];
			}
			
			// userTel1
			if (isset ( $user ['userTel1'] )) {
				$bike ['userTel1'] = $user ['userTel1'];
			}
			
			// userTel2
			if (isset ( $user ['userTel2'] )) {
				$bike ['userTel2'] = $user ['userTel2'];
			}
			
			// userAdress
			if (isset ( $user ['userAdress'] )) {
				$bike ['userAdress'] = $user ['userAdress'];
			}
			
			// userTel2
			if (isset ( $user ['userTel2'] )) {
				$bike ['userTel2'] = $user ['userTel2'];
			}
			
			// userID
			if (isset ( $user ['userID'] )) {
				$bike ['userID'] = $user ['userID'];
			}
		}
		
		return $bike;
	}
	
	/**
	 * This function filter all relevant data from the imported data set and fill a corresponding structure of TRUCK table
	 */
	private function transformMobile2TruckStruct($p) {
		$truck = array ();
		$lang = $this->lang;
		$truck ['truckModelVar'] = '';
		$truck ['truckHSN'] = '';
		$truck ['truckTSN'] = '';
		$truck ['truckFIN'] = '';
		$truck ['truckLocCountry'] = 'DE';
		// truckBrandID
		if (isset ( $p ['D'] )) {
			$truckBrand = db_selTruckBrand ( array (
					'brandNameL' => strtolower ( $p ['D'] ) 
			) );
			// , 'print' => true
			
			if (($truckBrand != false) && is_array ( $truckBrand ) && (count ( $truckBrand ) > 0)) {
				$truckBrand = $truckBrand [0];
				$truck ['truckBrandID'] = $truckBrand ['truckBrandID'];
				$truck ['truckBrand'] = $truck ['truckBrandID'];
				
				// truckModelID
				if (isset ( $p ['E'] )) {
					$truckModel = db_selTruckModel ( array (
							'truckModelNameL' => $p ['E'],
							'truckBrandID' => $truckBrand ['truckBrandID'] 
					) );
					if (($truckModel != false) && is_array ( $truckModel ) && (count ( $truckModel ) > 0)) {
						$truckModel = $truckModel [0];
						$truck ['truckModel'] = $truckModel ['truckModelID'];
						$truck ['truckModelID'] = $truckModel ['truckModelID'];
					}
				}
			}
		}
		
		// truckPrice
		if (isset ( $p ['K'] ) && is_numeric ( $p ['K'] )) {
			$truck ['truckPrice'] = $p ['K'];
		}
		
		// mwst
		if (isset ( $p ['L'] ) && is_numeric ( $p ['L'] )) {
			$truck ['mwst'] = $p ['L'];
		}
		
		// mwstSatz
		if (isset ( $p ['AD'] ) && is_numeric ( $p ['AD'] )) {
			$truck ['mwstSatz'] = $p ['AD'];
		}
		
		// truckPriceType
		$truck ['truckPriceType'] = '0';
		
		// truckPriceCurr
		if (isset ( $p ['AC'] )) {
			// see $lang['TXT_74'] for all currencies
			if (strtolower ( $p ['AC'] ) == 'eur') {
				$truck ['truckPriceCurr'] = '0';
			} elseif (strtolower ( $p ['AC'] ) == 'rubel') {
				$truck ['truckPriceCurr'] = 2;
			}
		}
		
		// truckKM
		if (isset ( $p ['J'] ) && is_numeric ( $p ['J'] )) {
			$truck ['truckKM'] = $p ['J'];
			
			// truckKMType -> see var. $lang['TXT_75']
			$truck ['truckKMType'] = '0';
		}
		
		// truckPower
		if (isset ( $p ['F'] ) && is_numeric ( $p ['F'] )) {
			$truck ['truckPower'] = $p ['F'];
			
			// truckPowerType -> see var. $lang['TXT_72']
			$truck ['truckPowerType'] = '0';
		}
		
		// truckEZM, truckEZY
		if (isset ( $p ['V'] ) && ($p ['V'] == '1')) {
			$car ['truckEZM'] = '-1';
			$car ['truckEZY'] = '9999';
		} elseif (isset ( $p ['I'] )) {
			$mmyyyy = explode ( '.', $p ['I'] );
			if (isset ( $mmyyyy [0] )) {
				$truck ['truckEZM'] = $mmyyyy [0]; // truckEZM
			}
			if (isset ( $mmyyyy [1] )) {
				$truck ['truckEZY'] = $mmyyyy [1]; // truckEZY
			}
		}
		
		// truckTUVM, truckTUVY
		if (isset ( $p ['G'] )) {
			$mmyyyy = explode ( '.', $p ['G'] );
			if (isset ( $mmyyyy [0] )) {
				$truck ['truckTUVM'] = $mmyyyy [0]; // truckTUVM
			}
			if (isset ( $mmyyyy [1] )) {
				$truck ['truckTUVY'] = $mmyyyy [1]; // truckTUVY
			}
		}
		
		// truckAUM, truckAUY
		if (isset ( $p ['H'] )) {
			$mmyyyy = explode ( '.', $p ['H'] );
			if (isset ( $mmyyyy [0] )) {
				$truck ['truckAUM'] = $mmyyyy [0]; // truckAUM
			}
			if (isset ( $mmyyyy [1] )) {
				$truck ['truckAUY'] = $mmyyyy [1]; // truckAUY
			}
		}
		
		// truckShift -> see var. $lang['V_SHIFT']
		if (isset ( $p ['DG'] )) {
			// keine Angabe
			if ($p ['DG'] == - 1) {
				$truck ['truckShift'] = - 1;
			} // Manuel?
elseif ($p ['DG'] == 1) {
				$truck ['truckShift'] = '0';
			} // Automatik?
elseif ($p ['DG'] == 1) {
				$truck ['truckShift'] = 1;
			} // Halbautomatik
elseif ($p ['DG'] == 3) {
				$truck ['truckShift'] = 3;
			}
		}
		
		// truckWeight
		if (isset ( $p ['BD'] ) && is_numeric ( $p ['BD'] )) {
			$truck ['truckWeight'] = $p ['BD'];
		}
		
		/*
		 * //truckCyl
		 * if (isset($p['U']) && is_numeric($p['U'])){
		 * $truck['truckCyl'] = $p['U'];
		 * }
		 */
		
		// truckCub
		if (isset ( $p ['BA'] ) && is_numeric ( $p ['BA'] )) {
			$truck ['truckCub'] = $p ['BA'];
		}
		
		// truckDoor -> see var. $lang['TRUCK_DOOR']
		if (isset ( $p ['AQ'] ) && is_numeric ( $p ['AQ'] )) {
			$truck ['truckDoor'] = $p ['AQ'];
		}
		
		// truckUseIn
		if (isset ( $p ['CS'] ) && is_numeric ( str_replace ( ',', '.', $p ['CS'] ) )) {
			$truck ['truckUseIn'] = str_replace ( ',', '.', $p ['CS'] );
		}
		
		// truckUseOut
		if (isset ( $p ['CT'] ) && is_numeric ( str_replace ( ',', '.', $p ['CT'] ) )) {
			$truck ['truckUseOut'] = str_replace ( ',', '.', $p ['CT'] );
		}
		
		// truckCO2
		if (isset ( $p ['CV'] ) && is_numeric ( str_replace ( ',', '.', $p ['CV'] ) )) {
			$truck ['truckCO2'] = str_replace ( ',', '.', $p ['CV'] );
		}
		
		// truckState -> see var. $lang['V_STATE']
		if (isset ( $p ['P'] ) && ($p ['P'] == 1)) {
			// Blechschaden
			$truck ['truckState'] = 3;
		} elseif (isset ( $p ['AE'] ) && ($p ['AE'] == 1)) {
			// Unfallfahrzeug
			$truck ['truckState'] = 4;
		} elseif (isset ( $p ['DI'] ) && ($p ['DI'] == 1)) {
			// Gebruachtwagen
			$truck ['truckState'] = 2;
		} elseif (isset ( $p ['U'] ) && ($p ['U'] == 1)) {
			// Jahreswagen
			$truck ['truckState'] = 1;
		} elseif (isset ( $p ['V'] ) && ($p ['V'] == 1)) {
			// Neuwagen
			$truck ['truckState'] = '0';
		} elseif (isset ( $p ['AY'] ) && ($p ['AY'] == 1)) {
			// Vorführwagen
			$truck ['truckState'] = 5;
		} else {
			$truck ['truckState'] = 0;
		}
		
		// truckCat -> see var. $lang['TRUCK_CAT']
		if (isset ( $p ['C'] )) {
			$cat = $this->detCat ( array_merge ( $p, array (
					'vType' => System_Properties::TRUCK_ABRV 
			) ) );
			if (($cat != false) && isset ( $cat ['cat'] ) && isset ( $cat ['vType'] )) {
				$truck ['truckCat'] = $cat ['cat'];
				if (! isset ( $truck ['vType'] )) {
					$truck ['vType'] = $cat ['vType'];
				}
			}
			/*
			 * if(stristr($p['T'], 'bus') != false){
			 * //Kleinbus
			 * $truck['truckCat'] = 6;
			 * }elseif (stristr($p['T'], 'cab') != false){
			 * //Cabrio
			 * $truck['truckCat'] = '0';
			 * }elseif (stristr($p['T'], 'coup') != false){
			 * //Coupe
			 * $truck['truckCat'] = 1;
			 * }elseif (stristr($p['T'], 'ndewagen') != false){
			 * //Geländewagen
			 * $truck['truckCat'] = 2;
			 * }elseif ((stristr($p['T'], 'kombi') != false) || (stristr($p['T'], 'van') != false)){
			 * //Kombi/Van
			 * $truck['truckCat'] = 4;
			 * }elseif (stristr($p['T'], 'liefer') != false){
			 * //Lieferwagen
			 * $truck['truckCat'] = 8;
			 * }
			 */
		}
		
		// truckFuel -> see var. $lang['V_FUEL']
		if (isset ( $p ['DF'] )) {
			$fuel = $p ['DF'];
			if ($fuel == 1) {
				// Benzin
				$truck ['truckFuel'] = '0';
			} elseif ($fuel == 2) {
				// diesel
				$truck ['truckFuel'] = 1;
			} /*
			   * elseif ($fuel == 4){
			   * //GAS
			   * $truck['truckFuel'] = 8;
			   * }
			   */
elseif ($fuel == 9) {
				// ethanol
				$truck ['truckFuel'] = 4;
			} elseif ($fuel == 6) {
				// elektro
				$truck ['truckFuel'] = 2;
			} elseif ($fuel == 3) {
				// LPG GAs
				$truck ['truckFuel'] = 3;
			} elseif ($fuel == 4) {
				// CNG Gas
				$truck ['truckFuel'] = 6;
			} elseif ($fuel == 7) {
				// Hybrid
				$truck ['truckFuel'] = 7;
			} elseif ($fuel == 8) {
				// Wasserstoff
				$truck ['truckFuel'] = 5;
			}
		}
		
		// truckClr -> see var. $lang['V_CLR']
		if (isset ( $p ['Q'] )) {
			foreach ( $lang ['V_CLR'] as $key => $kVal ) {
				if (stristr ( $kVal, $p ['Q'] ) != false) {
					$truck ['truckFuel'] = $key;
					break;
				}
			}
		}
		
		// truckClrMet
		if (isset ( $p ['AB'] ) && ($p ['AB'] == 1)) {
			$truck ['truckClrMet'] = 1;
		}
		
		// truckEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset ( $p ['BJ'] )) {
			$emissionNorm = $p ['BJ'];
			if ($emissionNorm == 1) {
				// EURO 1
				$truck ['truckEmissionNorm'] = 1;
			} elseif ($emissionNorm == 2) {
				// EURO 2
				$truck ['truckEmissionNorm'] = 2;
			} elseif ($emissionNorm == 3) {
				// EURO 3
				$truck ['truckEmissionNorm'] = 3;
			} elseif ($emissionNorm == 4) {
				// EURO 4
				$truck ['truckEmissionNorm'] = 4;
			} elseif ($emissionNorm == 5) {
				// EURO 5
				// $truck['truckEmissionNorm'] = 5;
			} elseif (stristr ( $p ['CI'], 6 ) != false) {
				// EURO 6
				// $truck['truckEmissionNorm'] = 5;
			}
		}
		
		// truckEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset ( $p ['AR'] )) {
			$ecologicTag = $p ['AR'];
			if ($ecologicTag == 1) {
				// Keine
				$truck ['truckEcologicTag'] = '0';
			} elseif ($ecologicTag == 2) {
				// ROT
				$truck ['truckEcologicTag'] = 1;
			} elseif ($ecologicTag == 3) {
				// Gelb
				$truck ['truckEcologicTag'] = 2;
			} elseif ($ecologicTag == 4) {
				// Grün
				$truck ['truckEcologicTag'] = 3;
			}
		}
		
		// truckKlima -> see var. $lang['V_KLIMA']
		if (isset ( $p ['R'] ) && ($p ['R'] == 1)) {
			// Klima
			$truck ['truckKlima'] = '0';
		} elseif (isset ( $p ['R'] ) && ($p ['R'] == 2)) {
			// Klimaautomatik
			$truck ['truckKlima'] = 1;
		}
		
		if (isset ( $p ['O'] )) {
			$truck ['truckFIN'] = $p ['O'];
		}
		
		// truckDesc
		if (isset ( $p ['Z'] )) {
			$truck ['truckDesc'] = $p ['Z'];
		}
		
		// userAdsLength
		$truck ['userAdsLength'] = 12;
		
		$user = null;
		if (isset ( $p ['USER_DATA'] ) && is_array ( $p ['USER_DATA'] )) {
			$user = $p ['USER_DATA'];
		} elseif (isset ( $this->userNS->userData )) {
			$user = $this->userNS->userData;
		} elseif ($this->userData != null) {
			$user = $this->userData;
		}
		
		if ($user != null) {
			
			// userAds -> see var. $lang['TXT_33']
			$truck ['userAds'] = '-1';
			if (isset ( $user ['userMode'] ) && ($user ['userMode'] != 3)) {
				$truck ['userAds'] = $user ['userMode'];
			}
			
			// userFirm
			if (isset ( $user ['userFirm'] )) {
				$truck ['userFirm'] = $user ['userFirm'];
			}
			
			// userNName
			if (isset ( $user ['userNName'] )) {
				$truck ['userNName'] = $user ['userNName'];
			}
			
			// userVName
			if (isset ( $user ['userVName'] )) {
				$truck ['userVName'] = $user ['userVName'];
			}
			
			// userEMail
			if (isset ( $user ['userEMail'] )) {
				$truck ['userEMail'] = $user ['userEMail'];
			}
			
			// userPLZ
			if (isset ( $user ['userPLZ'] )) {
				$truck ['userPLZ'] = $user ['userPLZ'];
				$truck ['truckLocPLZ'] = $user ['userPLZ'];
			}
			
			// userOrt
			if (isset ( $user ['userOrt'] )) {
				$truck ['userOrt'] = $user ['userOrt'];
				$truck ['truckLocOrt'] = $user ['userOrt'];
			}
			
			// truckLocCountry
			if (isset ( $user ['userCountry'] )) {
				$car ['truckLocCountry'] = $user ['userCountry'];
			}
			
			// userTel1
			if (isset ( $user ['userTel1'] )) {
				$truck ['userTel1'] = $user ['userTel1'];
			}
			
			// userTel2
			if (isset ( $user ['userTel2'] )) {
				$truck ['userTel2'] = $user ['userTel2'];
			}
			
			// userAdress
			if (isset ( $user ['userAdress'] )) {
				$truck ['userAdress'] = $user ['userAdress'];
			}
			
			// userTel2
			if (isset ( $user ['userTel2'] )) {
				$truck ['userTel2'] = $user ['userTel2'];
			}
			
			// userID
			if (isset ( $user ['userID'] )) {
				$truck ['userID'] = $user ['userID'];
			}
		}
		
		return $truck;
	}
	
	/**
	 * Determine vehicle type based on vehicle categroy
	 */
	private function detVType($p) {
		if (isset ( $p ['C'] )) {
			// Maybe vehicle is a car?
			if (stristr ( $p ['C'], 'cabrio' ) || stristr ( $p ['C'], 'roadster' ) || stristr ( $p ['C'], 'gelaendew' ) || stristr ( $p ['C'], 'pickup' ) || stristr ( $p ['C'], 'kleinwage' ) || stristr ( $p ['C'], 'kombi' ) || stristr ( $p ['C'], 'limousine' ) || (stristr ( $p ['C'], 'sportwagen' ) && ! stristr ( $p ['C'], 'pferdetransportwagen' )) || stristr ( $p ['C'], 'coupe' ) || stristr ( $p ['C'], 'van' ) || stristr ( $p ['C'], 'kleinbus' ) || stristr ( $p ['C'], 'kasten' ) || (stristr ( $p ['C'], 'andere' ) && stristr ( $p ['C'], 'pkw' ))) {
				$p ['vType'] = System_Properties::CAR_ABRV;
			} // Maybe vehicle is a bike
elseif (stristr ( $p ['C'], 'chopper' ) || stristr ( $p ['C'], 'cruiser' ) || (stristr ( $p ['C'], 'dirt' ) && stristr ( $p ['C'], 'bike' )) || stristr ( $p ['C'], 'enduro' ) || stristr ( $p ['C'], 'gespann' ) || stristr ( $p ['C'], 'Seitenwage' ) || stristr ( $p ['C'], 'klein' ) || stristr ( $p ['C'], 'leichtkraftrad' ) || stristr ( $p ['C'], 'mofa' ) || stristr ( $p ['C'], 'mokick' ) || stristr ( $p ['C'], 'moped' ) || stristr ( $p ['C'], 'motorrad' ) || (stristr ( $p ['C'], 'naked' ) && stristr ( $p ['C'], 'bike' )) || (stristr ( $p ['C'], 'pocket' ) && stristr ( $p ['C'], 'bike' )) || stristr ( $p ['C'], 'quad' ) || stristr ( $p ['C'], 'rallye' ) || stristr ( $p ['C'], 'cross' ) || stristr ( $p ['C'], 'rennsport' ) || stristr ( $p ['C'], 'roller' ) || stristr ( $p ['C'], 'scooter' ) || stristr ( $p ['C'], 'sportler' ) || stristr ( $p ['C'], 'tourer' ) || stristr ( $p ['C'], 'streetfighter' ) || (stristr ( $p ['C'], 'super' ) && stristr ( $p ['C'], 'moto' )) || stristr ( $p ['C'], 'trike' ) || (stristr ( $p ['C'], 'andere' ) && stristr ( $p ['C'], 'motorra' ))) {
				$p ['vType'] = System_Properties::BIKE_ABRV;
			}  // Vehicle is a truck
else {
				$p ['vType'] = System_Properties::TRUCK_ABRV;
			}
		}
		return $p;
	}
	
	/**
	 * determine vehicle type by car and category
	 */
	private function detCat($p) {
		$ret = null;
		$carCat = $this->carCat;
		$bikeCat = $this->bikeCat;
		$truckCat = $this->truckCat;
		
		$lang = $this->lang;
		if (isset ( $p ['C'] ) && isset ( $p ['D'] ) && is_array ( $lang )) {
			$ret = array (
					'cat' => null,
					'vType' => null 
			);
			$mobileCat = $p ['C'];
			$brand = $p ['D'];
			if (isset ( $lang ['V_CAT'] )) {
				$vcat = $lang ['V_CAT'];
			}
			// echo $mobileCat.' '.$brand.' ';
			
			// check car categories
			if ($carCat != null) {
				foreach ( $carCat as $key => $kVal ) {
					if (isset ( $vcat [$kVal ['vcatID']] )) {
						$vcatName = $vcat [$kVal ['vcatID']];
						
						// check Cabrio/Roadster
						if (stristr ( $mobileCat, 'cabrio' ) && stristr ( $vcatName, 'cabrio' )) {
							$ret ['cat'] = $kVal ['carCatID'];
						} // check Geländewagen/pickUp
elseif (stristr ( $mobileCat, 'pickup' ) && stristr ( $vcatName, 'pickup' )) {
							$ret ['cat'] = $kVal ['carCatID'];
						} // check Kleinwagen
elseif (stristr ( $mobileCat, 'kleinwage' ) && stristr ( $vcatName, 'kleinwage' )) {
							$ret ['cat'] = $kVal ['carCatID'];
						} // check Kombi
elseif (stristr ( $mobileCat, 'kombi' ) && stristr ( $vcatName, 'kombi' )) {
							$ret ['cat'] = $kVal ['carCatID'];
						} // check Limousine
elseif (stristr ( $mobileCat, 'limo' ) && stristr ( $vcatName, 'limo' )) {
							$ret ['cat'] = $kVal ['carCatID'];
						} // check Sportwagen
elseif ((stristr ( $mobileCat, 'sportwa' ) && stristr ( $vcatName, 'sportwa' )) || (stristr ( $mobileCat, 'coup' ) && stristr ( $vcatName, 'coup' ))) {
							$ret ['cat'] = $kVal ['carCatID'];
						} // check Van/Kleinbus
elseif ((stristr ( $mobileCat, 'van' ) && stristr ( $vcatName, 'van' )) || (stristr ( $mobileCat, 'kleinbu' ) && stristr ( $vcatName, 'kleinbu' ))) {
							$ret ['cat'] = $kVal ['carCatID'];
						}
					}
				}
				if ($ret ['cat'] != null) {
					$ret ['vType'] = System_Properties::CAR_ABRV;
				}
			}
			
			// if no category could be found than check bike categories
			if (($ret ['cat'] == null) && ($bikeCat != null)) {
				foreach ( $bikeCat as $key => $kVal ) {
					if (isset ( $vcat [$kVal ['vcatID']] )) {
						$vcatName = $vcat [$kVal ['vcatID']];
						// check Chopper/Cruiser
						if (stristr ( $mobileCat, 'chopper' ) && stristr ( $vcatName, 'chopper' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check dirt bike
elseif (stristr ( $mobileCat, 'dirt' ) && stristr ( $vcatName, 'dirt' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Enduro/Reiseenduro
elseif (stristr ( $mobileCat, 'enduro' ) && stristr ( $vcatName, 'enduro' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Gespann/Seitenwagen
elseif (stristr ( $mobileCat, 'seiten' ) && stristr ( $vcatName, 'seiten' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Klein/Leichtkraftrad
elseif (stristr ( $mobileCat, 'leichtkraft' ) && stristr ( $vcatName, 'leichtkraft' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Mofa/Mokick
elseif (stristr ( $mobileCat, 'mofa' ) && stristr ( $vcatName, 'mofa' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Moped
elseif (stristr ( $mobileCat, 'moped' ) && stristr ( $vcatName, 'moped' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Motorrad
elseif (stristr ( $mobileCat, 'motorrad' ) && stristr ( $vcatName, 'motorrad' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Naked Bike
elseif (stristr ( $mobileCat, 'naked' ) && stristr ( $vcatName, 'naked' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Pocketbike
elseif (stristr ( $mobileCat, 'pocket' ) && stristr ( $vcatName, 'pocket' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Quad
elseif (stristr ( $mobileCat, 'quad' ) && stristr ( $vcatName, 'quad' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Rallye/Cross
elseif (stristr ( $mobileCat, 'rally' ) && stristr ( $vcatName, 'rally' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Rennsport
elseif (stristr ( $mobileCat, 'rennspo' ) && stristr ( $vcatName, 'rennspo' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Roller/Scooter
elseif (stristr ( $mobileCat, 'roller' ) && stristr ( $vcatName, 'roller' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Sportler/Supersportler
elseif (stristr ( $mobileCat, 'sportler' ) && stristr ( $vcatName, 'sportler' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Sporttourer
elseif (stristr ( $mobileCat, 'sporttour' ) && stristr ( $vcatName, 'sporttou' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Streetfighter
elseif (stristr ( $mobileCat, 'streetfight' ) && stristr ( $vcatName, 'streetfight' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Super Moto
elseif ((stristr ( $mobileCat, 'super' ) || stristr ( $mobileCat, 'moto' )) && (stristr ( $vcatName, 'super' ) || stristr ( $vcatName, 'moto' ))) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Tourer
elseif (stristr ( $mobileCat, 'tourer' ) && stristr ( $vcatName, 'tourer' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						} // check Trike
elseif (stristr ( $mobileCat, 'trike' ) && stristr ( $vcatName, 'trike' )) {
							$ret ['cat'] = $kVal ['bikeCatID'];
						}
					}
				}
				if ($ret ['cat'] != null) {
					$ret ['vType'] = System_Properties::BIKE_ABRV;
				}
			}
			
			// if no category could be found than check TRUCK categories
			if (($ret ['cat'] == null) && ($truckCat != null)) {
				foreach ( $truckCat as $key => $kVal ) {
					$umlautArr = Array (
							'/ä/',
							'/ö/',
							'/ü/',
							'/Ä/',
							'/Ö/',
							'/Ü/' 
					);
					$replaceArr = Array (
							'ae',
							'oe',
							'ue',
							'AE',
							'OE',
							'UE' 
					);
					
					// check normal
					if (isset ( $vcat [$kVal ['vcatID']] )) {
						$vcatName = $vcat [$kVal ['vcatID']];
						if (stristr ( $mobileCat, $vcatName )) {
							$ret ['cat'] = $key;
							break;
						} // replace umlauts and compare
elseif (stristr ( preg_replace ( $umlautArr, $replaceArr, $mobileCat ), preg_replace ( $umlautArr, $replaceArr, $vcatName ) )) {
							$ret ['cat'] = $key;
							break;
						}
					}
				}
				if ($ret ['cat'] != null) {
					$ret ['vType'] = System_Properties::TRUCK_ABRV;
				}
			}
		}
		return $ret;
	}
	
	/**
	 * Handle ZIP file import
	 */
	private function handleZIPFileImp($p) {
		$fileName = null;
		if (isset ( $p ['FILE_NAME'] )) {
			$fileName = $p ['FILE_NAME'];
		}
		
		if ($fileName != null) {
			$fileNameDir = dirname ( $fileName );
			$zipCSVFile = array (
					'DIR' => $fileNameDir,
					'FILE' => array () 
			);
			$zipPicFile = array (
					'DIR' => $fileNameDir,
					'FILE' => array () 
			);
			// Need for userID = 3, CARIX accumulative account
			$zipCSVDealerFile = array (
					'DIR' => $fileNameDir,
					'FILE' => array () 
			);
			
			$zipFile = new ZipArchive ();
			$zipFile->open ( $fileName );
			
			// sort file names
			for($i = '0'; $i < $zipFile->numFiles; $i ++) {
				$zipFileNamePath = $zipFile->getNameIndex ( $i );
				$zipFileName = basename ( $zipFileNamePath );
				$zipFileNameComp = explode ( '.', $zipFileName );
				if (isset ( $zipFileNameComp [count ( $zipFileNameComp ) - 1] )) {
					$zipFileExtension = strtoupper ( $zipFileNameComp [count ( $zipFileNameComp ) - 1] );
					
					if (($zipFileExtension == 'CSV') && stristr ( $zipFileName, 'dealer' )) {
						array_push ( $zipCSVDealerFile ['FILE'], $zipFileName );
					} elseif ($zipFileExtension == 'CSV') {
						array_push ( $zipCSVFile ['FILE'], $zipFileName );
					} elseif (in_array ( strtolower ( $zipFileExtension ), System_Properties::$PIC_EXT )) {
						array_push ( $zipPicFile ['FILE'], $zipFileName );
					}
				}
			}
			$zipFile->extractTo ( $fileNameDir, array_merge ( $zipCSVFile ['FILE'], $zipPicFile ['FILE'], $zipCSVDealerFile ['FILE'] ) );
			$zipFile->close ();
			
			// userID = 3, CARIX accumulative account
			$user = false;
			if (($this->cronj == true) && is_array ( $zipCSVDealerFile ['FILE'] ) && (count ( $zipCSVDealerFile ['FILE'] ) > 0) && is_array ( $this->userData ) && isset ( $this->userData ['userID'] ) && ($this->userData ['userID'] == 3)) {
				$user = $this->importDealerData ( $zipCSVDealerFile );
			}
			
			// process CSV file
			foreach ( $zipCSVFile ['FILE'] as $key => $csvFileName ) {
				$this->handleCSVFileImp ( array (
						'FILE_NAME' => $fileNameDir . '/' . $csvFileName,
						'PIC_NAME' => $zipPicFile,
						
						// , 'vType' => $p['vType']
						'USER_DATA' => $user 
				) );
			}
			/*
			 * //delete all uploaded files in the ftp folder
			 * $folderFiles = scandir($fileNameDir);
			 * foreach ($folderFiles as $key => $fileName){
			 * if (($fileName != '.') && ($fileName != '..')){
			 * $fileName = $fileNameDir.'/'.$fileName;
			 * if (is_file($fileName)){
			 * unlink($fileName);
			 * }
			 * elseif (is_dir($fileName)){
			 * System_Properties::rec_rmdir($fileName);
			 * }
			 * }
			 * }
			 */
		}
	}
	private function importDealerData($p) {
		$lang = $this->lang;
		$user = false;
		if (isset ( $p ['FILE'] ) && is_array ( $p ['FILE'] ) && isset ( $p ['DIR'] )) {
			$fEMail = new FilterValidEmail ();
			$fString100 = new FilterString100 ();
			$fString20 = new FilterString20 ();
			$fString10 = new FilterString10 ();
			$fString5 = new FilterStringXX ( 5 );
			$fString500 = new FilterStringXX ( 500 );
			$fIsEmptyStr = new FilterIsEmptyString ();
			$fUTF8 = new FilterEncUTF8 ();
			$fMD5 = new FilterEncMD5 ();
			
			$p ['FILE'] = $p ['FILE'] [0];
			$fileName = $p ['DIR'] . '/' . $p ['FILE'];
			$fileHandler = fopen ( $fileName, 'r' );
			while ( ($csvData = fgetcsv ( $fileHandler, null, ';' )) !== false ) {
				$csvData = $fUTF8->filter ( $csvData );
				$user = array (
						'userExtID' => null,
						'userFirm' => null,
						'userAdress' => null,
						'userCountry' => null,
						'userPLZ' => null,
						'userOrt' => null,
						'userTel1' => null,
						'userTel2' => null,
						'userEMail' => null,
						'userVName' => null,
						'userNName' => null,
						'userURL' => null 
				);
				// userExtID
				if (isset ( $csvData [0] )) {
					$user ['userExtID'] = $csvData [0];
				}
				
				// userFirm
				if (isset ( $csvData [1] )) {
					$user ['userFirm'] = $csvData [1];
				}
				
				// userAdress
				if (isset ( $csvData [2] )) {
					$user ['userAdress'] = $csvData [2];
				}
				
				// userCountry
				if (isset ( $csvData [3] )) {
					$user ['userCountry'] = $csvData [3];
				}
				
				// userPLZ
				if (isset ( $csvData [4] )) {
					$user ['userPLZ'] = $csvData [4];
				}
				
				// userOrt
				if (isset ( $csvData [5] )) {
					$user ['userOrt'] = $csvData [5];
				}
				
				// userTel1
				if (isset ( $csvData [6] )) {
					$user ['userTel1'] = $csvData [6];
				}
				
				// userTel2
				if (isset ( $csvData [7] )) {
					$user ['userTel2'] = $csvData [7];
				}
				
				// userEMail
				if (isset ( $csvData [8] )) {
					$user ['userEMail'] = $csvData [8];
				}
				
				// userVName
				if (isset ( $csvData [9] )) {
					$user ['userVName'] = $csvData [9];
				}
				
				// userNName
				if (isset ( $csvData [10] )) {
					$user ['userNName'] = $csvData [10];
				}
				
				// userURL
				if (isset ( $csvData [11] )) {
					$user ['userURL'] = $csvData [11];
				}
				
				$userByExtID = db_selUser ( array (
						'userExtID' => $user ['userExtID'] 
				) );
				$userByEmail = db_selUser ( array (
						'userEMail' => $user ['userEMail'] 
				) );
				if (($userByExtID != false) && is_array ( $userByExtID ) && (count ( $userByExtID ) > 0)) {
					if (isset ( $userByExtID [0] ) && isset ( $userByExtID [0] ['userEMail'] ) && ($userByExtID [0] ['userEMail'] == $user ['userEMail'])) {
						$user = $userByExtID [0];
					} elseif (isset ( $userByExtID [0] ) && isset ( $userByExtID [0] ['userEMail'] ) && ($userByExtID [0] ['userEMail'] != $user ['userEMail']) && (($userByEmail == false) || (is_array ( $userByEmail ) && (count ( $userByEmail ) <= 0)))) {
						$user ['userID'] = $userByExtID [0] ['userID'];
						$updUser = db_updateUser ( $user );
						if ($updUser != false) {
							$user = db_selUser ( array (
									'userID' => $userByExtID [0] ['userID'] 
							) );
							if (($user != false) && is_array ( $user ) && (count ( $user ) > 0)) {
								$user = $user [0];
							}
						}
					} elseif (($userByEmail != false) && is_array ( $userByEmail ) && (count ( $userByEmail ) > 0) && isset ( $userByEmail [0] ['userID'] )) {
						$user ['userID'] = $userByEmail [0] ['userID'];
						$updUser = db_updateUser ( $user );
						if ($updUser != false) {
							$user = db_selUser ( array (
									'userID' => $userByEmail [0] ['userID'] 
							) );
							if (($user != false) && is_array ( $user ) && (count ( $user ) > 0)) {
								$user = $user [0];
							}
						}
					}
				} else {
					if (($userByEmail != false) && is_array ( $userByEmail ) && (count ( $userByEmail ) > 0) && isset ( $userByEmail [0] ['userID'] )) {
						$user ['userID'] = $userByEmail [0] ['userID'];
						$updUser = db_updateUser ( $user );
						if ($updUser != false) {
							$user = db_selUser ( array (
									'userID' => $userByEmail [0] ['userID'] 
							) );
							if (($user != false) && is_array ( $user ) && (count ( $user ) > 0)) {
								$user = $user [0];
							}
						}
					} else {
						$system = db_selSystem ( array (
								'orderby' => array (
										array (
												'col' => 'timestam',
												'desc' => true 
										) 
								),
								'limit' => array (
										'start' => 0,
										'num' => 1 
								) 
						) );
						if (($system != false) && is_array ( $system ) && (count ( $system ) > 0)) {
							$system = $system [0];
						} else {
							$system = false;
						}
						
						if ((($user ['userFirm'] != null) && ($user ['userEMail'] != null) && ($fEMail->filter ( $user ['userEMail'] ) != false) && ($system != false)) || (($user ['userVName'] != null) && ($user ['userNName'] != null) && ($user ['userEMail'] != null) && ($fEMail->filter ( $user ['userEMail'] ) != false) && ($system != false))) {
							
							// check userFirm
							isset ( $user ['userFirm'] ) ? $user ['userFirm'] = $fString100->filter ( $user ['userFirm'] ) : $user ['userFirm'] = '';
							
							// check userNName
							isset ( $user ['userNName'] ) ? $user ['userNName'] = $fString100->filter ( $user ['userNName'] ) : $user ['userNName'] = '';
							
							// check userVName
							isset ( $user ['userVName'] ) ? $user ['userVName'] = $fString100->filter ( $user ['userVName'] ) : $user ['userVName'] = '';
							
							// userPLZ
							isset ( $user ['userPLZ'] ) ? $user ['userPLZ'] = $fString20->filter ( $user ['userPLZ'] ) : $user ['userPLZ'] = '';
							
							// userOrt
							isset ( $user ['userOrt'] ) ? $user ['userOrt'] = $fString100->filter ( $user ['userOrt'] ) : $user ['userOrt'] = '';
							
							// userTel1
							isset ( $user ['userTel1'] ) ? $user ['userTel1'] = $fString100->filter ( $user ['userTel1'] ) : $user ['userTel1'] = '';
							
							// userTel2
							isset ( $user ['userTel2'] ) ? $user ['userTel2'] = $fString100->filter ( $user ['userTel2'] ) : $user ['userTel2'] = '';
							
							// userAdress
							isset ( $user ['userAdress'] ) ? $user ['userAdress'] = $fString100->filter ( $user ['userAdress'] ) : $user ['userAdress'] = '';
							
							// userExtID
							isset ( $user ['userExtID'] ) ? $user ['userExtID'] = $fString10->filter ( $user ['userExtID'] ) : $user ['userExtID'] = '';
							
							// userCountry
							isset ( $user ['userCountry'] ) ? $user ['userCountry'] = $fString5->filter ( $user ['userCountry'] ) : $user ['userCountry'] = '';
							
							// userURL
							isset ( $user ['userURL'] ) ? $user ['userURL'] = $fString500->filter ( $user ['userURL'] ) : $user ['userURL'] = '';
							
							$user ['userStat'] = 1;
							$user ['userAGB'] = 1;
							$user ['userNews'] = 0;
							$user ['userMode'] = 1; // 1 = Händler
							$user ['groupID'] = $system ['sysStdGroup'];
							$pwFound = false;
							while ( $pwFound == false ) {
								$p ['userPW1'] = System_Properties::generatePW ();
								$md5PW = $fMD5->filter ( $p ['userPW1'] );
								$checkPW = db_selUser ( array (
										'userPW' => $md5PW 
								) );
								if (($checkPW == false) || (is_array ( $checkPW ) && (count ( $checkPW ) <= 0))) {
									$user ['userPW'] = $md5PW;
									$pwFound = true;
								}
							}
							// Insert user
							$userID = db_insUser ( $user );
							if (($userID != false) && is_numeric ( $userID )) {
								$user = db_selUser ( array (
										'userID' => $userID 
								) );
								if (($user != false) && is_array ( $user ) && (count ( $user ) > 0)) {
									$user = $user [0];
									// insert FTP user
									$ftpUser = @db_selFTPUser ( array (
											'USERNAME' => $user ['userEMail'] 
									) );
									// , 'CUSTOMERID' => '1'
									
									if (($ftpUser == false) || ! is_array ( $ftpUser )) {
										// /var/customers/webs/autotunes/t01/web/app/ftp/16/upload/
										// $homeDir = $this->docRoot . 'app/ftp/' . $user ['userID'] . '/upload/';
										$homeDir = '../app/ftp/' . $user ['userID'] . '/upload/';
										$insFTPUser = db_insFTPUser ( array (
												'USERNAME' => $user ['userEMail'],
												'PASSWORD' => $p ['userPW1'],
												'LOGIN_ENABLED' => 'N',
												'HOMEDIR' => $homeDir,
												'UID' => System_Properties::FTP_UID,
												'GID' => System_Properties::FTP_GID 
										) );
										if ($insFTPUser != false) {
											if (! file_exists ( $homeDir )) {
												if (mkdir ( $homeDir, 0775, true ) == true) {
													/*
													 * echo "KK";
													 * if(chown($homeDir, System_Properties::FTP_USER_ID)){
													 * echo "UU";
													 * }
													 */
													chgrp ( $homeDir, System_Properties::FTP_GROUP );
													chmod ( $homeDir, 0775 );
												}
											}
										}
									}
									// send registration mail
									$p ['USER_PW'] = $p ['userPW1'];
									$p ['EMAIL_SENDER'] = System_Properties::$SYS_INFO_EMAIL;
									$p ['EMAIL_RECEIVER'] = $user ['userEMail'];
									$p ['EMAIL_FROM'] = $system ['sysSiteName'];
									
									$emailMessage = str_ireplace ( '{-WEBSITE_NAME-}', $system ['sysSiteName'], $lang ['TXT_241'] );
									$emailMessage = str_ireplace ( '{-USER_PW-}', $p ['USER_PW'], $emailMessage );
									System_Properties::sendEmail ( array (
											'EMAIL_SENDER' => $p ['EMAIL_SENDER'],
											'EMAIL_RECEIVER' => $p ['EMAIL_RECEIVER'],
											'EMAIL_MESSAGE' => $emailMessage,
											'EMAIL_SUBJECT' => $lang ['TXT_242'] . ' ' . $system ['sysSiteName'],
											'EMAIL_FROM' => $p ['EMAIL_FROM'],
											'EMAIL_REPLYTO' => $p ['EMAIL_SENDER'] 
									) );
								}
							}
						}
					}
				}
				break;
			}
			fclose ( $fileHandler );
		}
		return $user;
	}
	
	/**
	 * Check if all mandatory fields are specified
	 */
	private function checkManFieldsMobileAction($p) {
		$ret = array ();
		$lang = $this->lang;
		$error = null;
		$data = null;
		if (isset ( $p ['DATA'] )) {
			$data = $p ['DATA'];
		}
		
		if ($data != null) {
			$fInt = new FilterMySQLInt ();
			$fIntU = new FilterMySQLInt ( array (
					'unsigned' => true 
			) );
			$fString1 = new FilterStringXX ( 1 );
			$fString10 = new FilterStringXX ( 10 );
			$fString30 = new FilterStringXX ( 30 );
			$fString40 = new FilterStringXX ( 40 );
			$fString50 = new FilterStringXX ( 50 );
			$fString25 = new FilterStringXX ( 25 );
			$fString80 = new FilterStringXX ( 80 );
			$fString3000 = new FilterStringXX ( 3000 );
			
			$fMobileEZ = new FilterValidMobileEZ ();
			// Check mobile internal numer
			if (! isset ( $data ['B'] ) || ($fString40->filter ( $data ['B'] ) == false)) {
				$error = $lang ['ERR_18'] . $lang ['ERR_20'];
			} // Check mobile catgory
elseif (! isset ( $data ['C'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_22'];
			} // check mobile brand
elseif (! isset ( $data ['D'] ) || (db_selCarBrand ( array (
					'brandNameL' => strtolower ( $data ['D'] ) 
			) ) == false)) {
				$error = $lang ['ERR_18'] . $lang ['ERR_19'];
			} // check mobile model
elseif (! isset ( $data ['E'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_21'];
			} // check KW
elseif (! isset ( $data ['F'] ) || ($fInt->filter ( $data ['F'] ) == false)) {
				$error = $lang ['ERR_18'] . $lang ['ERR_28'];
			} // check EZ
elseif (! isset ( $data ['I'] ) || ($fMobileEZ->filter ( $data ['I'] ) == false)) {
				$error = $lang ['ERR_18'] . $lang ['ERR_25'];
			} // check KM
elseif (! isset ( $data ['J'] ) || ($fInt->filter ( $data ['J'] ) == false)) {
				$error = $lang ['ERR_18'] . $lang ['ERR_26'];
			} // check price
elseif (! isset ( $data ['K'] ) || ($fInt->filter ( $data ['K'] ) == false)) {
				$error = $lang ['ERR_18'] . $lang ['ERR_27'];
			} // check mwst
elseif (! isset ( $data ['L'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_30'];
			} // check beschädigt
elseif (! isset ( $data ['P'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_31'];
			} // check jahreswagen
elseif (! isset ( $data ['U'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_32'];
			} // check neufahrzeug
elseif (! isset ( $data ['V'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_33'];
			} // unsere empfahlung
elseif (! isset ( $data ['W'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_34'];
			} // Metalic
elseif (! isset ( $data ['AB'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_35'];
			} // vorführfahrzeug
elseif (! isset ( $data ['AY'] )) {
				$error = $lang ['ERR_18'] . $lang ['ERR_36'];
			}  /*
			   * //check currency
			   * elseif (!isset($data['L'])){
			   * $error = $lang['ERR_18'].$lang['ERR_23'];
			   * }
			   * //check fuel
			   * elseif (!isset($data['M'])){
			   * $error = $lang['ERR_18'].$lang['ERR_24'];
			   * }
			   * //check color
			   * elseif (!isset($data['CN'])){
			   * $error = $lang['ERR_18'].$lang['ERR_29'];
			   * }
			   */
else {
				/*
				 * //check MOBILE offering number
				 * $data['B'] = $fString50 -> filter($data['B']);
				 *
				 * //CHECK version
				 * $data['F'] = $fString30 -> filter($data['F']);
				 *
				 * //CHECK exterior colour
				 * $data['J'] = $fString25 -> filter($data['J']);
				 *
				 * //check exterior colour metallic
				 * $data['K'] = $fString1 -> filter($data['K']);
				 *
				 * //check door
				 * $data['Q'] = $fString10 -> filter($data['Q']);
				 *
				 * //check seat
				 * $data['R'] = $fString10 -> filter($data['R']);
				 *
				 * //check seat
				 * $data['R'] = $fString10 -> filter($data['R']);
				 */
			}
		}
		
		if ($data != null) {
			$ret ['DATA'] = $data;
		}
		
		if ($error != null) {
			$ret ['ERROR'] = $error;
		}
		return $ret;
	}
	
	/**
	 * Transform the mobile.de CSV data to
	 */
	private function fillAssoziativeArr($p) {
		$fString1 = new FilterStringXX ( 1 );
		$fString2 = new FilterStringXX ( 2 );
		$fString4 = new FilterStringXX ( 4 );
		$fString5 = new FilterStringXX ( 5 );
		$fString6 = new FilterStringXX ( 6 );
		$fString7 = new FilterStringXX ( 7 );
		$fString10 = new FilterStringXX ( 10 );
		$fString20 = new FilterStringXX ( 20 );
		$fString25 = new FilterStringXX ( 25 );
		$fString30 = new FilterStringXX ( 30 );
		$fString40 = new FilterStringXX ( 40 );
		$fString50 = new FilterStringXX ( 50 );
		$fString80 = new FilterStringXX ( 80 );
		$fString500 = new FilterStringXX ( 500 );
		$fString3000 = new FilterStringXX ( 3000 );
		
		$fIsEmpty = new FilterIsEmptyString ();
		
		// 0
		if (isset ( $p [0] ) && ($p [0] != null) && ($fIsEmpty->filter ( $p [0] ) == false)) {
			$ret ['A'] = $fString10->filter ( $p [0] );
		} else {
			$ret ['A'] = null;
		}
		
		// 1
		if (isset ( $p [1] ) && ($p [1] != null) && ($fIsEmpty->filter ( $p [1] ) == false)) {
			$ret ['B'] = $fString40->filter ( $p [1] );
		} else {
			$ret ['B'] = null;
		}
		
		// 2
		if (isset ( $p [2] ) && ($p [2] != null) && ($fIsEmpty->filter ( $p [2] ) == false)) {
			$ret ['C'] = $p [2];
		} else {
			$ret ['C'] = null;
		}
		
		// 3
		if (isset ( $p [3] ) && ($p [3] != null) && ($fIsEmpty->filter ( $p [3] ) == false)) {
			$ret ['D'] = $p [3];
		} else {
			$ret ['D'] = null;
		}
		
		// 4
		if (isset ( $p [4] ) && ($p [4] != null) && ($fIsEmpty->filter ( $p [4] ) == false)) {
			$ret ['E'] = $p [4];
		} else {
			$ret ['E'] = null;
		}
		
		// 5
		if (isset ( $p [5] ) && ($p [5] != null) && ($fIsEmpty->filter ( $p [5] ) == false)) {
			$ret ['F'] = $fString10->filter ( $p [5] );
		} else {
			$ret ['F'] = null;
		}
		
		// 6
		if (isset ( $p [6] ) && ($p [6] != null) && ($fIsEmpty->filter ( $p [6] ) == false)) {
			$ret ['G'] = $fString7->filter ( $p [6] );
		} else {
			$ret ['G'] = null;
		}
		
		// 7
		if (isset ( $p [7] ) && ($p [7] != null) && ($fIsEmpty->filter ( $p [7] ) == false)) {
			$ret ['H'] = $fString7->filter ( $p [7] );
		} else {
			$ret ['H'] = null;
		}
		
		// 8
		if (isset ( $p [8] ) && ($p [8] != null) && ($fIsEmpty->filter ( $p [8] ) == false)) {
			$ret ['I'] = $fString7->filter ( $p [8] );
		} else {
			$ret ['I'] = null;
		}
		
		// 9
		if (isset ( $p [9] ) && ($p [9] != null) && ($fIsEmpty->filter ( $p [9] ) == false)) {
			$ret ['J'] = $fString10->filter ( $p [9] );
		} else {
			$ret ['J'] = null;
		}
		
		// 10
		if (isset ( $p [10] ) && ($p [10] != null) && ($fIsEmpty->filter ( $p [10] ) == false)) {
			$ret ['K'] = $fString10->filter ( intval ( $p [10] ) );
		} else {
			$ret ['K'] = null;
		}
		
		// 11
		if (isset ( $p [11] ) && ($p [11] != null) && ($fIsEmpty->filter ( $p [11] ) == false)) {
			$ret ['L'] = $fString1->filter ( $p [11] );
		} else {
			$ret ['L'] = null;
		}
		
		// 12
		if (isset ( $p [12] ) && ($p [12] != null) && ($fIsEmpty->filter ( $p [12] ) == false)) {
			$ret ['M'] = null; // $p[12];//reserviert
		} else {
			$ret ['M'] = null;
		}
		
		// 13
		if (isset ( $p [13] ) && ($p [13] != null) && ($fIsEmpty->filter ( $p [13] ) == false)) {
			$ret ['N'] = $fString1->filter ( $p [13] );
		} else {
			$ret ['N'] = null;
		}
		
		// 14
		if (isset ( $p [14] ) && ($p [14] != null) && ($fIsEmpty->filter ( $p [14] ) == false)) {
			$ret ['O'] = $p [14];
		} else {
			$ret ['O'] = null;
		}
		
		// 15
		if (isset ( $p [15] ) && ($p [15] != null) && ($fIsEmpty->filter ( $p [15] ) == false)) {
			$ret ['P'] = $fString1->filter ( $p [15] );
		} else {
			$ret ['P'] = null;
		}
		
		// 16
		if (isset ( $p [16] ) && ($p [16] != null) && ($fIsEmpty->filter ( $p [16] ) == false)) {
			$ret ['Q'] = $p [16];
		} else {
			$ret ['Q'] = null;
		}
		
		// 17
		if (isset ( $p [17] ) && ($p [17] != null) && ($fIsEmpty->filter ( $p [17] ) == false)) {
			$ret ['R'] = $fString1->filter ( $p [17] );
		} else {
			$ret ['R'] = null;
		}
		
		// 18
		if (isset ( $p [18] ) && ($p [18] != null) && ($fIsEmpty->filter ( $p [18] ) == false)) {
			$ret ['S'] = $fString1->filter ( $p [18] );
		} else {
			$ret ['S'] = null;
		}
		
		// 19
		if (isset ( $p [19] ) && ($p [19] != null) && ($fIsEmpty->filter ( $p [19] ) == false)) {
			$ret ['T'] = $fString1->filter ( $p [19] );
		} else {
			$ret ['T'] = null;
		}
		
		// 20
		if (isset ( $p [20] ) && ($p [20] != null) && ($fIsEmpty->filter ( $p [20] ) == false)) {
			$ret ['U'] = $fString1->filter ( $p [20] );
		} else {
			$ret ['U'] = null;
		}
		
		// 21
		if (isset ( $p [21] ) && ($p [21] != null) && ($fIsEmpty->filter ( $p [21] ) == false)) {
			$ret ['V'] = $fString1->filter ( $p [21] );
		} else {
			$ret ['V'] = null;
		}
		
		// 22
		if (isset ( $p [22] ) && ($p [22] != null) && ($fIsEmpty->filter ( $p [22] ) == false)) {
			$ret ['W'] = $fString1->filter ( $p [22] );
		} else {
			$ret ['W'] = null;
		}
		
		// 23
		if (isset ( $p [23] ) && ($p [23] != null) && ($fIsEmpty->filter ( $p [23] ) == false)) {
			$ret ['X'] = $fString10->filter ( intval ( $p [23] ) );
		} else {
			$ret ['X'] = null;
		}
		
		// 24
		if (isset ( $p [24] ) && ($p [24] != null) && ($fIsEmpty->filter ( $p [24] ) == false)) {
			$ret ['Y'] = $p [24];
		} else {
			$ret ['Y'] = null;
		}
		
		// 25
		if (isset ( $p [25] ) && ($p [25] != null) && ($fIsEmpty->filter ( $p [25] ) == false)) {
			$ret ['Z'] = trim ( str_ireplace ( '\\', '<br>', $p [25] ) );
		} else {
			$ret ['Z'] = null;
		}
		
		// 26
		if (isset ( $p [26] ) && ($p [26] != null) && ($fIsEmpty->filter ( $p [26] ) == false)) {
			$ret ['AA'] = $p [26];
		} else {
			$ret ['AA'] = null;
		}
		
		// 27
		if (isset ( $p [27] ) && ($p [27] != null) && ($fIsEmpty->filter ( $p [27] ) == false)) {
			$ret ['AB'] = $fString1->filter ( $p [27] );
		} else {
			$ret ['AB'] = null;
		}
		
		// 28
		if (isset ( $p [28] ) && ($p [28] != null) && ($fIsEmpty->filter ( $p [28] ) == false)) {
			$ret ['AC'] = $p [28];
		} else {
			$ret ['AC'] = null;
		}
		
		// 29
		if (isset ( $p [29] ) && ($p [29] != null) && ($fIsEmpty->filter ( $p [29] ) == false)) {
			$ret ['AD'] = $fString6->filter ( $p [29] );
		} else {
			$ret ['AD'] = null;
		}
		
		// 30
		if (isset ( $p [30] ) && ($p [30] != null) && ($fIsEmpty->filter ( $p [30] ) == false)) {
			$ret ['AE'] = $fString1->filter ( $p [30] );
		} else {
			$ret ['AE'] = null;
		}
		
		// 31
		if (isset ( $p [31] ) && ($p [31] != null) && ($fIsEmpty->filter ( $p [31] ) == false)) {
			$ret ['AF'] = $fString1->filter ( $p [31] );
		} else {
			$ret ['AF'] = null;
		}
		
		// 32
		if (isset ( $p [32] ) && ($p [32] != null) && ($fIsEmpty->filter ( $p [32] ) == false)) {
			$ret ['AG'] = $fString1->filter ( $p [32] );
		} else {
			$ret ['AG'] = null;
		}
		
		// 33
		if (isset ( $p [33] ) && ($p [33] != null) && ($fIsEmpty->filter ( $p [33] ) == false)) {
			$ret ['AH'] = $fString1->filter ( $p [33] );
		} else {
			$ret ['AH'] = null;
		}
		
		// 34
		if (isset ( $p [34] ) && ($p [34] != null) && ($fIsEmpty->filter ( $p [34] ) == false)) {
			$ret ['AI'] = $fString1->filter ( $p [34] );
		} else {
			$ret ['AI'] = null;
		}
		
		// 35
		if (isset ( $p [35] ) && ($p [35] != null) && ($fIsEmpty->filter ( $p [35] ) == false)) {
			$ret ['AJ'] = $fString1->filter ( $p [35] );
		} else {
			$ret ['AJ'] = null;
		}
		
		// 36
		if (isset ( $p [36] ) && ($p [36] != null) && ($fIsEmpty->filter ( $p [36] ) == false)) {
			$ret ['AK'] = $fString1->filter ( $p [36] );
		} else {
			$ret ['AK'] = null;
		}
		
		// 37
		if (isset ( $p [37] ) && ($p [37] != null) && ($fIsEmpty->filter ( $p [37] ) == false)) {
			$ret ['AL'] = $fString1->filter ( $p [37] );
		} else {
			$ret ['AL'] = null;
		}
		
		// 38
		if (isset ( $p [38] ) && ($p [38] != null) && ($fIsEmpty->filter ( $p [38] ) == false)) {
			$ret ['AM'] = $fString1->filter ( $p [38] );
		} else {
			$ret ['AM'] = null;
		}
		
		// 39
		if (isset ( $p [39] ) && ($p [39] != null) && ($fIsEmpty->filter ( $p [39] ) == false)) {
			$ret ['AN'] = $fString1->filter ( $p [39] );
		} else {
			$ret ['AN'] = null;
		}
		
		// 40
		if (isset ( $p [40] ) && ($p [40] != null) && ($fIsEmpty->filter ( $p [40] ) == false)) {
			$ret ['AO'] = $fString1->filter ( $p [40] );
		} else {
			$ret ['AO'] = null;
		}
		
		// 41
		if (isset ( $p [41] ) && ($p [41] != null) && ($fIsEmpty->filter ( $p [41] ) == false)) {
			$ret ['AP'] = $fString1->filter ( $p [41] );
		} else {
			$ret ['AP'] = null;
		}
		
		// 42
		if (isset ( $p [42] ) && ($p [42] != null) && ($fIsEmpty->filter ( $p [42] ) == false)) {
			$ret ['AQ'] = $fString10->filter ( $p [42] );
		} else {
			$ret ['AQ'] = null;
		}
		
		// 43
		if (isset ( $p [43] ) && ($p [43] != null) && ($fIsEmpty->filter ( $p [43] ) == false)) {
			$ret ['AR'] = $fString4->filter ( $p [43] );
		} else {
			$ret ['AR'] = null;
		}
		
		// 44
		if (isset ( $p [44] ) && ($p [44] != null) && ($fIsEmpty->filter ( $p [44] ) == false)) {
			$ret ['AS1'] = $fString1->filter ( $p [44] );
		} else {
			$ret ['AS1'] = null;
		}
		
		// 45
		if (isset ( $p [45] ) && ($p [45] != null) && ($fIsEmpty->filter ( $p [45] ) == false)) {
			$ret ['AT'] = $fString1->filter ( $p [45] );
		} else {
			$ret ['AT'] = null;
		}
		
		// 46
		if (isset ( $p [46] ) && ($p [46] != null) && ($fIsEmpty->filter ( $p [46] ) == false)) {
			$ret ['AU'] = $fString1->filter ( $p [46] );
		} else {
			$ret ['AU'] = null;
		}
		
		// 47
		if (isset ( $p [47] ) && ($p [47] != null) && ($fIsEmpty->filter ( $p [47] ) == false)) {
			$ret ['AV'] = $fString1->filter ( $p [47] );
		} else {
			$ret ['AV'] = null;
		}
		
		// 48
		if (isset ( $p [48] ) && ($p [48] != null) && ($fIsEmpty->filter ( $p [48] ) == false)) {
			$ret ['AW'] = $fString1->filter ( $p [48] );
		} else {
			$ret ['AW'] = null;
		}
		
		// 49
		if (isset ( $p [49] ) && ($p [49] != null) && ($fIsEmpty->filter ( $p [49] ) == false)) {
			$ret ['AX'] = $fString1->filter ( $p [49] );
		} else {
			$ret ['AX'] = null;
		}
		
		// 50
		if (isset ( $p [50] ) && ($p [50] != null) && ($fIsEmpty->filter ( $p [50] ) == false)) {
			$ret ['AY'] = $fString1->filter ( $p [50] );
		} else {
			$ret ['AY'] = null;
		}
		
		// 51
		if (isset ( $p [51] ) && ($p [51] != null) && ($fIsEmpty->filter ( $p [51] ) == false)) {
			$ret ['AZ'] = $fString4->filter ( $p [51] );
		} else {
			$ret ['AZ'] = null;
		}
		
		// 52
		if (isset ( $p [52] ) && ($p [52] != null) && ($fIsEmpty->filter ( $p [52] ) == false)) {
			$ret ['BA'] = $fString10->filter ( $p [52] );
		} else {
			$ret ['BA'] = null;
		}
		
		// 53
		if (isset ( $p [53] ) && ($p [53] != null) && ($fIsEmpty->filter ( $p [53] ) == false)) {
			$ret ['BB'] = $fString10->filter ( $p [53] );
		} else {
			$ret ['BB'] = null;
		}
		
		// 54
		if (isset ( $p [54] ) && ($p [54] != null) && ($fIsEmpty->filter ( $p [54] ) == false)) {
			$ret ['BC'] = $fString10->filter ( $p [54] );
		} else {
			$ret ['BC'] = null;
		}
		
		// 55
		if (isset ( $p [55] ) && ($p [55] != null) && ($fIsEmpty->filter ( $p [55] ) == false)) {
			$ret ['BD'] = $fString10->filter ( $p [55] );
		} else {
			$ret ['BD'] = null;
		}
		
		// 56
		if (isset ( $p [56] ) && ($p [56] != null) && ($fIsEmpty->filter ( $p [56] ) == false)) {
			$ret ['BE'] = $fString10->filter ( $p [56] );
		} else {
			$ret ['BE'] = null;
		}
		
		// 57
		if (isset ( $p [57] ) && ($p [57] != null) && ($fIsEmpty->filter ( $p [57] ) == false)) {
			$ret ['BF'] = $fString10->filter ( $p [57] );
		} else {
			$ret ['BF'] = null;
		}
		
		// 58
		if (isset ( $p [58] ) && ($p [58] != null) && ($fIsEmpty->filter ( $p [58] ) == false)) {
			$ret ['BG'] = $fString10->filter ( $p [58] );
		} else {
			$ret ['BG'] = null;
		}
		
		// 59
		if (isset ( $p [59] ) && ($p [59] != null) && ($fIsEmpty->filter ( $p [59] ) == false)) {
			$ret ['BH'] = $fString10->filter ( $p [59] );
		} else {
			$ret ['BH'] = null;
		}
		
		// 60
		if (isset ( $p [60] ) && ($p [60] != null) && ($fIsEmpty->filter ( $p [60] ) == false)) {
			$ret ['BI'] = $fString4->filter ( $p [60] );
		} else {
			$ret ['BI'] = null;
		}
		
		// 61
		if (isset ( $p [61] ) && ($p [61] != null) && ($fIsEmpty->filter ( $p [61] ) == false)) {
			$ret ['BJ'] = $fString4->filter ( $p [61] );
		} else {
			$ret ['BJ'] = null;
		}
		
		// 62
		if (isset ( $p [62] ) && ($p [62] != null) && ($fIsEmpty->filter ( $p [62] ) == false)) {
			$ret ['BK'] = $fString4->filter ( $p [62] );
		} else {
			$ret ['BK'] = null;
		}
		
		// 63
		if (isset ( $p [63] ) && ($p [63] != null) && ($fIsEmpty->filter ( $p [63] ) == false)) {
			$ret ['BL'] = $fString10->filter ( $p [63] );
		} else {
			$ret ['BL'] = null;
		}
		
		// 64
		if (isset ( $p [64] ) && ($p [64] != null) && ($fIsEmpty->filter ( $p [64] ) == false)) {
			$ret ['BM'] = $fString1->filter ( $p [64] );
		} else {
			$ret ['BM'] = null;
		}
		
		// 65
		if (isset ( $p [65] ) && ($p [65] != null) && ($fIsEmpty->filter ( $p [65] ) == false)) {
			$ret ['BN'] = $fString1->filter ( $p [65] );
		} else {
			$ret ['BN'] = null;
		}
		
		// 66
		if (isset ( $p [66] ) && ($p [66] != null) && ($fIsEmpty->filter ( $p [66] ) == false)) {
			$ret ['BO'] = $fString1->filter ( $p [66] );
		} else {
			$ret ['BO'] = null;
		}
		
		// 67
		if (isset ( $p [67] ) && ($p [67] != null) && ($fIsEmpty->filter ( $p [67] ) == false)) {
			$ret ['BP'] = $fString1->filter ( $p [67] );
		} else {
			$ret ['BP'] = null;
		}
		
		// 68
		if (isset ( $p [68] ) && ($p [68] != null) && ($fIsEmpty->filter ( $p [68] ) == false)) {
			$ret ['BQ'] = $fString1->filter ( $p [68] );
		} else {
			$ret ['BQ'] = null;
		}
		
		// 69
		if (isset ( $p [69] ) && ($p [69] != null) && ($fIsEmpty->filter ( $p [69] ) == false)) {
			$ret ['BR'] = $fString1->filter ( $p [69] );
		} else {
			$ret ['BR'] = null;
		}
		
		// 70
		if (isset ( $p [70] ) && ($p [70] != null) && ($fIsEmpty->filter ( $p [70] ) == false)) {
			$ret ['BS'] = $fString1->filter ( $p [70] );
		} else {
			$ret ['BS'] = null;
		}
		
		// 71
		if (isset ( $p [71] ) && ($p [71] != null) && ($fIsEmpty->filter ( $p [71] ) == false)) {
			$ret ['BT'] = $fString1->filter ( $p [71] );
		} else {
			$ret ['BT'] = null;
		}
		
		// 72
		if (isset ( $p [72] ) && ($p [72] != null) && ($fIsEmpty->filter ( $p [72] ) == false)) {
			$ret ['BU'] = $fString1->filter ( $p [72] );
		} else {
			$ret ['BU'] = null;
		}
		
		// 73
		if (isset ( $p [73] ) && ($p [73] != null) && ($fIsEmpty->filter ( $p [73] ) == false)) {
			$ret ['BV'] = $fString1->filter ( $p [73] );
		} else {
			$ret ['BV'] = null;
		}
		
		// 74
		if (isset ( $p [74] ) && ($p [74] != null) && ($fIsEmpty->filter ( $p [74] ) == false)) {
			$ret ['BW'] = $fString1->filter ( $p [74] );
		} else {
			$ret ['BW'] = null;
		}
		
		// 75
		if (isset ( $p [75] ) && ($p [75] != null) && ($fIsEmpty->filter ( $p [75] ) == false)) {
			$ret ['BX'] = $fString1->filter ( $p [75] );
		} else {
			$ret ['BX'] = null;
		}
		
		// 76
		if (isset ( $p [76] ) && ($p [76] != null) && ($fIsEmpty->filter ( $p [76] ) == false)) {
			$ret ['BY1'] = $fString4->filter ( $p [76] );
		} else {
			$ret ['BY1'] = null;
		}
		
		// 77
		if (isset ( $p [77] ) && ($p [77] != null) && ($fIsEmpty->filter ( $p [77] ) == false)) {
			$ret ['BZ'] = $fString1->filter ( $p [77] );
		} else {
			$ret ['BZ'] = null;
		}
		
		// 78
		if (isset ( $p [78] ) && ($p [78] != null) && ($fIsEmpty->filter ( $p [78] ) == false)) {
			$ret ['CA'] = $fString4->filter ( $p [78] );
		} else {
			$ret ['CA'] = null;
		}
		
		// 79
		if (isset ( $p [79] ) && ($p [79] != null) && ($fIsEmpty->filter ( $p [79] ) == false)) {
			$ret ['CB'] = $fString1->filter ( $p [79] );
		} else {
			$ret ['CB'] = null;
		}
		
		// 80
		if (isset ( $p [80] ) && ($p [80] != null) && ($fIsEmpty->filter ( $p [80] ) == false)) {
			$ret ['CC'] = $fString1->filter ( $p [80] );
		} else {
			$ret ['CC'] = null;
		}
		
		// 81
		if (isset ( $p [81] ) && ($p [81] != null) && ($fIsEmpty->filter ( $p [81] ) == false)) {
			$ret ['CD'] = $fString1->filter ( $p [81] );
		} else {
			$ret ['CD'] = null;
		}
		
		// 82
		if (isset ( $p [82] ) && ($p [82] != null) && ($fIsEmpty->filter ( $p [82] ) == false)) {
			$ret ['CE'] = $fString1->filter ( $p [82] );
		} else {
			$ret ['CE'] = null;
		}
		
		// 83
		if (isset ( $p [83] ) && ($p [83] != null) && ($fIsEmpty->filter ( $p [83] ) == false)) {
			$ret ['CF'] = $fString1->filter ( $p [83] );
		} else {
			$ret ['CF'] = null;
		}
		
		// 84
		if (isset ( $p [84] ) && ($p [84] != null) && ($fIsEmpty->filter ( $p [84] ) == false)) {
			$ret ['CG'] = $fString1->filter ( $p [84] );
		} else {
			$ret ['CG'] = null;
		}
		
		// 85
		if (isset ( $p [85] ) && ($p [85] != null) && ($fIsEmpty->filter ( $p [85] ) == false)) {
			$ret ['CH'] = $fString1->filter ( $p [85] );
		} else {
			$ret ['CH'] = null;
		}
		
		// 86
		if (isset ( $p [86] ) && ($p [86] != null) && ($fIsEmpty->filter ( $p [86] ) == false)) {
			$ret ['CI'] = $fString1->filter ( $p [86] );
		} else {
			$ret ['CI'] = null;
		}
		
		// 87
		if (isset ( $p [87] ) && ($p [87] != null) && ($fIsEmpty->filter ( $p [87] ) == false)) {
			$ret ['CJ'] = $fString1->filter ( $p [87] );
		} else {
			$ret ['CJ'] = null;
		}
		
		// 88
		if (isset ( $p [88] ) && ($p [88] != null) && ($fIsEmpty->filter ( $p [88] ) == false)) {
			$ret ['CK'] = $fString1->filter ( $p [88] );
		} else {
			$ret ['CK'] = null;
		}
		
		// 89
		if (isset ( $p [89] ) && ($p [89] != null) && ($fIsEmpty->filter ( $p [89] ) == false)) {
			$ret ['CL'] = $fString1->filter ( $p [89] );
		} else {
			$ret ['CL'] = null;
		}
		
		// 90
		if (isset ( $p [90] ) && ($p [90] != null) && ($fIsEmpty->filter ( $p [90] ) == false)) {
			$ret ['CM'] = $fString1->filter ( $p [90] );
		} else {
			$ret ['CM'] = null;
		}
		
		// 91
		if (isset ( $p [91] ) && ($p [91] != null) && ($fIsEmpty->filter ( $p [91] ) == false)) {
			$ret ['CN'] = $fString1->filter ( $p [91] );
		} else {
			$ret ['CN'] = null;
		}
		
		// 92
		if (isset ( $p [92] ) && ($p [92] != null) && ($fIsEmpty->filter ( $p [92] ) == false)) {
			$ret ['CO'] = $fString1->filter ( $p [92] );
		} else {
			$ret ['CO'] = null;
		}
		
		// 93
		if (isset ( $p [93] ) && ($p [93] != null) && ($fIsEmpty->filter ( $p [93] ) == false)) {
			$ret ['CP'] = $fString1->filter ( $p [93] );
		} else {
			$ret ['CP'] = null;
		}
		
		// 94
		if (isset ( $p [94] ) && ($p [94] != null) && ($fIsEmpty->filter ( $p [94] ) == false)) {
			$ret ['CQ'] = $fString1->filter ( $p [94] );
		} else {
			$ret ['CQ'] = null;
		}
		
		// 95
		if (isset ( $p [95] ) && ($p [95] != null) && ($fIsEmpty->filter ( $p [95] ) == false)) {
			$ret ['CR'] = $fString1->filter ( $p [95] );
		} else {
			$ret ['CR'] = null;
		}
		
		// 96
		if (isset ( $p [96] ) && ($p [96] != null) && ($fIsEmpty->filter ( $p [96] ) == false)) {
			$ret ['CS'] = $fString10->filter ( $p [96] );
		} else {
			$ret ['CS'] = null;
		}
		
		// 97
		if (isset ( $p [97] ) && ($p [97] != null) && ($fIsEmpty->filter ( $p [97] ) == false)) {
			$ret ['CT'] = $fString10->filter ( $p [97] );
		} else {
			$ret ['CT'] = null;
		}
		
		// 98
		if (isset ( $p [98] ) && ($p [98] != null) && ($fIsEmpty->filter ( $p [98] ) == false)) {
			$ret ['CU'] = $fString10->filter ( $p [98] );
		} else {
			$ret ['CU'] = null;
		}
		
		// 99
		if (isset ( $p [99] ) && ($p [99] != null) && ($fIsEmpty->filter ( $p [99] ) == false)) {
			$ret ['CV'] = $fString10->filter ( $p [99] );
		} else {
			$ret ['CV'] = null;
		}
		
		// 100
		if (isset ( $p [100] ) && ($p [100] != null) && ($fIsEmpty->filter ( $p [100] ) == false)) {
			$ret ['CW'] = $fString1->filter ( $p [100] );
		} else {
			$ret ['CW'] = null;
		}
		
		// 101
		if (isset ( $p [101] ) && ($p [101] != null) && ($fIsEmpty->filter ( $p [101] ) == false)) {
			$ret ['CX'] = $fString1->filter ( $p [101] );
		} else {
			$ret ['CX'] = null;
		}
		
		// 102
		if (isset ( $p [102] ) && ($p [102] != null) && ($fIsEmpty->filter ( $p [102] ) == false)) {
			$ret ['CY'] = $fString1->filter ( $p [102] );
		} else {
			$ret ['CY'] = null;
		}
		
		// 103
		if (isset ( $p [103] ) && ($p [103] != null) && ($fIsEmpty->filter ( $p [103] ) == false)) {
			$ret ['CZ'] = $fString1->filter ( $p [103] );
		} else {
			$ret ['CZ'] = null;
		}
		
		// 104
		if (isset ( $p [104] ) && ($p [104] != null) && ($fIsEmpty->filter ( $p [104] ) == false)) {
			$ret ['DA'] = $fString10->filter ( $p [104] );
		} else {
			$ret ['DA'] = null;
		}
		
		// 105
		if (isset ( $p [105] ) && ($p [105] != null) && ($fIsEmpty->filter ( $p [105] ) == false)) {
			$ret ['DB'] = $fString10->filter ( $p [105] );
		} else {
			$ret ['DB'] = null;
		}
		
		// 106
		if (isset ( $p [106] ) && ($p [106] != null) && ($fIsEmpty->filter ( $p [106] ) == false)) {
			$ret ['DC'] = $fString6->filter ( $p [106] );
		} else {
			$ret ['DC'] = null;
		}
		
		// 107
		if (isset ( $p [107] ) && ($p [107] != null) && ($fIsEmpty->filter ( $p [107] ) == false)) {
			$ret ['DD'] = $fString10->filter ( $p [107] );
		} else {
			$ret ['DD'] = null;
		}
		
		// 108
		if (isset ( $p [108] ) && ($p [108] != null) && ($fIsEmpty->filter ( $p [108] ) == false)) {
			$ret ['DE'] = $fString1->filter ( $p [108] );
		} else {
			$ret ['DE'] = null;
		}
		
		// 109
		if (isset ( $p [109] ) && ($p [109] != null) && ($fIsEmpty->filter ( $p [109] ) == false)) {
			$ret ['DF'] = $fString4->filter ( $p [109] );
		} else {
			$ret ['DF'] = null;
		}
		
		// 110
		if (isset ( $p [110] ) && ($p [110] != null) && ($fIsEmpty->filter ( $p [110] ) == false)) {
			$ret ['DG'] = $fString4->filter ( $p [110] );
		} else {
			$ret ['DG'] = null;
		}
		
		// 111
		if (isset ( $p [111] ) && ($p [111] != null) && ($fIsEmpty->filter ( $p [111] ) == false)) {
			$ret ['DH'] = $fString1->filter ( $p [111] );
		} else {
			$ret ['DH'] = null;
		}
		
		// 112
		if (isset ( $p [112] ) && ($p [112] != null) && ($fIsEmpty->filter ( $p [112] ) == false)) {
			$ret ['DI'] = $fString1->filter ( $p [112] );
		} else {
			$ret ['DI'] = null;
		}
		
		// 113
		if (isset ( $p [113] ) && ($p [113] != null) && ($fIsEmpty->filter ( $p [113] ) == false)) {
			$ret ['DJ'] = $fString1->filter ( $p [113] );
		} else {
			$ret ['DJ'] = null;
		}
		
		// 114
		if (isset ( $p [114] ) && ($p [114] != null) && ($fIsEmpty->filter ( $p [114] ) == false)) {
			$ret ['DK'] = $fString10->filter ( $p [114] );
		} else {
			$ret ['DK'] = null;
		}
		
		// 115
		if (isset ( $p [115] ) && ($p [115] != null) && ($fIsEmpty->filter ( $p [115] ) == false)) {
			$ret ['DL'] = $fString50->filter ( $p [115] );
		} else {
			$ret ['DL'] = null;
		}
		
		// 116
		if (isset ( $p [116] ) && ($p [116] != null) && ($fIsEmpty->filter ( $p [116] ) == false)) {
			$ret ['DM'] = $fString1->filter ( $p [116] );
		} else {
			$ret ['DM'] = null;
		}
		
		// 117
		if (isset ( $p [117] ) && ($p [117] != null) && ($fIsEmpty->filter ( $p [117] ) == false)) {
			$ret ['DN'] = null; // $p[117];//reserviert
		} else {
			$ret ['DN'] = null;
		}
		
		// 118
		if (isset ( $p [118] ) && ($p [118] != null) && ($fIsEmpty->filter ( $p [118] ) == false)) {
			$ret ['DO'] = null; // $p[118];//reserviert
		} else {
			$ret ['DO'] = null;
		}
		
		// 119
		if (isset ( $p [119] ) && ($p [119] != null) && ($fIsEmpty->filter ( $p [119] ) == false)) {
			$ret ['DP'] = $fString1->filter ( $p [119] );
		} else {
			$ret ['DP'] = null;
		}
		
		// 120
		if (isset ( $p [120] ) && ($p [120] != null) && ($fIsEmpty->filter ( $p [120] ) == false)) {
			$ret ['DQ'] = $fString4->filter ( $p [120] );
		} else {
			$ret ['DQ'] = null;
		}
		
		// 121
		if (isset ( $p [121] ) && ($p [121] != null) && ($fIsEmpty->filter ( $p [121] ) == false)) {
			$ret ['DR'] = $fString1->filter ( $p [121] );
		} else {
			$ret ['DR'] = null;
		}
		
		// 122
		if (isset ( $p [122] ) && ($p [122] != null) && ($fIsEmpty->filter ( $p [122] ) == false)) {
			$ret ['DS'] = $fString1->filter ( $p [122] );
		} else {
			$ret ['DS'] = null;
		}
		
		// 123
		if (isset ( $p [123] ) && ($p [123] != null) && ($fIsEmpty->filter ( $p [123] ) == false)) {
			$ret ['DT'] = $fString1->filter ( $p [123] );
		} else {
			$ret ['DT'] = null;
		}
		
		// 124
		if (isset ( $p [124] ) && ($p [124] != null) && ($fIsEmpty->filter ( $p [124] ) == false)) {
			$ret ['DU'] = $fString1->filter ( $p [124] );
		} else {
			$ret ['DU'] = null;
		}
		
		// 125
		if (isset ( $p [125] ) && ($p [125] != null) && ($fIsEmpty->filter ( $p [125] ) == false)) {
			$ret ['DV'] = $fString1->filter ( $p [125] );
		} else {
			$ret ['DV'] = null;
		}
		
		// 126
		if (isset ( $p [126] ) && ($p [126] != null) && ($fIsEmpty->filter ( $p [126] ) == false)) {
			$ret ['DW'] = $fString1->filter ( $p [126] );
		} else {
			$ret ['DW'] = null;
		}
		
		// 127
		if (isset ( $p [127] ) && ($p [127] != null) && ($fIsEmpty->filter ( $p [127] ) == false)) {
			$ret ['DX'] = $fString1->filter ( $p [127] );
		} else {
			$ret ['DX'] = null;
		}
		
		// 128
		if (isset ( $p [128] ) && ($p [128] != null) && ($fIsEmpty->filter ( $p [128] ) == false)) {
			$ret ['DY'] = $fString1->filter ( $p [128] );
		} else {
			$ret ['DY'] = null;
		}
		
		// 129
		if (isset ( $p [129] ) && ($p [129] != null) && ($fIsEmpty->filter ( $p [129] ) == false)) {
			$ret ['DZ'] = $fString1->filter ( $p [129] );
		} else {
			$ret ['DZ'] = null;
		}
		
		// 130
		if (isset ( $p [130] ) && ($p [130] != null) && ($fIsEmpty->filter ( $p [130] ) == false)) {
			$ret ['EA'] = $fString1->filter ( $p [130] );
		} else {
			$ret ['EA'] = null;
		}
		
		// 131
		if (isset ( $p [131] ) && ($p [131] != null) && ($fIsEmpty->filter ( $p [131] ) == false)) {
			$ret ['EB'] = $fString1->filter ( $p [131] );
		} else {
			$ret ['EB'] = null;
		}
		
		// 132
		if (isset ( $p [132] ) && ($p [132] != null) && ($fIsEmpty->filter ( $p [132] ) == false)) {
			$ret ['EC'] = $fString1->filter ( $p [132] );
		} else {
			$ret ['EC'] = null;
		}
		
		// 133
		if (isset ( $p [133] ) && ($p [133] != null) && ($fIsEmpty->filter ( $p [133] ) == false)) {
			$ret ['ED'] = $fString1->filter ( $p [133] );
		} else {
			$ret ['ED'] = null;
		}
		
		// 134
		if (isset ( $p [134] ) && ($p [134] != null) && ($fIsEmpty->filter ( $p [134] ) == false)) {
			$ret ['EE'] = $fString1->filter ( $p [134] );
		} else {
			$ret ['EE'] = null;
		}
		
		// 135
		if (isset ( $p [135] ) && ($p [135] != null) && ($fIsEmpty->filter ( $p [135] ) == false)) {
			$ret ['EF'] = $fString1->filter ( $p [135] );
		} else {
			$ret ['EF'] = null;
		}
		
		// 136
		if (isset ( $p [136] ) && ($p [136] != null) && ($fIsEmpty->filter ( $p [136] ) == false)) {
			$ret ['EG'] = $fString1->filter ( $p [136] );
		} else {
			$ret ['EG'] = null;
		}
		
		// 137
		if (isset ( $p [137] ) && ($p [137] != null) && ($fIsEmpty->filter ( $p [137] ) == false)) {
			$ret ['EH'] = $fString1->filter ( $p [137] );
		} else {
			$ret ['EH'] = null;
		}
		
		// 138
		if (isset ( $p [138] ) && ($p [138] != null) && ($fIsEmpty->filter ( $p [138] ) == false)) {
			$ret ['EI'] = $fString1->filter ( $p [138] );
		} else {
			$ret ['EI'] = null;
		}
		
		// 139
		if (isset ( $p [139] ) && ($p [139] != null) && ($fIsEmpty->filter ( $p [139] ) == false)) {
			$ret ['EJ'] = $fString4->filter ( $p [139] );
		} else {
			$ret ['EJ'] = null;
		}
		
		// 140
		if (isset ( $p [140] ) && ($p [140] != null) && ($fIsEmpty->filter ( $p [140] ) == false)) {
			$ret ['EK'] = $fString10->filter ( $p [140] );
		} else {
			$ret ['EK'] = null;
		}
		
		// 141
		if (isset ( $p [141] ) && ($p [141] != null) && ($fIsEmpty->filter ( $p [141] ) == false)) {
			$ret ['EL'] = $fString10->filter ( $p [141] );
		} else {
			$ret ['EL'] = null;
		}
		
		// 142
		if (isset ( $p [142] ) && ($p [142] != null) && ($fIsEmpty->filter ( $p [142] ) == false)) {
			$ret ['EM'] = $fString10->filter ( $p [142] );
		} else {
			$ret ['EM'] = null;
		}
		
		// 143
		if (isset ( $p [143] ) && ($p [143] != null) && ($fIsEmpty->filter ( $p [143] ) == false)) {
			$ret ['EN'] = $fString10->filter ( $p [143] );
		} else {
			$ret ['EN'] = null;
		}
		
		// 144
		if (isset ( $p [144] ) && ($p [144] != null) && ($fIsEmpty->filter ( $p [144] ) == false)) {
			$ret ['EO'] = $fString10->filter ( $p [144] );
		} else {
			$ret ['EO'] = null;
		}
		
		// 145
		if (isset ( $p [145] ) && ($p [145] != null) && ($fIsEmpty->filter ( $p [145] ) == false)) {
			$ret ['EP'] = $fString10->filter ( $p [145] );
		} else {
			$ret ['EP'] = null;
		}
		
		// 146
		if (isset ( $p [146] ) && ($p [146] != null) && ($fIsEmpty->filter ( $p [146] ) == false)) {
			$ret ['EQ'] = $fString10->filter ( $p [146] );
		} else {
			$ret ['EQ'] = null;
		}
		
		// 147
		if (isset ( $p [147] ) && ($p [147] != null) && ($fIsEmpty->filter ( $p [147] ) == false)) {
			$ret ['ER'] = $fString10->filter ( $p [147] );
		} else {
			$ret ['ER'] = null;
		}
		
		// 148
		if (isset ( $p [148] ) && ($p [148] != null) && ($fIsEmpty->filter ( $p [148] ) == false)) {
			$ret ['ES'] = $fString1->filter ( $p [148] );
		} else {
			$ret ['ES'] = null;
		}
		
		// 149
		if (isset ( $p [149] ) && ($p [149] != null) && ($fIsEmpty->filter ( $p [149] ) == false)) {
			$ret ['ET'] = $fString10->filter ( $p [149] );
		} else {
			$ret ['ET'] = null;
		}
		
		// 150
		if (isset ( $p [150] ) && ($p [150] != null) && ($fIsEmpty->filter ( $p [150] ) == false)) {
			$ret ['EU'] = $fString10->filter ( $p [150] );
		} else {
			$ret ['EU'] = null;
		}
		
		// 151
		if (isset ( $p [151] ) && ($p [151] != null) && ($fIsEmpty->filter ( $p [151] ) == false)) {
			$ret ['EV'] = $fString4->filter ( $p [151] );
		} else {
			$ret ['EV'] = null;
		}
		
		// 152
		if (isset ( $p [152] ) && ($p [152] != null) && ($fIsEmpty->filter ( $p [152] ) == false)) {
			$ret ['EW'] = $fString10->filter ( $p [152] );
		} else {
			$ret ['EW'] = null;
		}
		
		// 153
		if (isset ( $p [153] ) && ($p [153] != null) && ($fIsEmpty->filter ( $p [153] ) == false)) {
			$ret ['EX'] = $fString10->filter ( $p [153] );
		} else {
			$ret ['EX'] = null;
		}
		
		// 154
		if (isset ( $p [154] ) && ($p [154] != null) && ($fIsEmpty->filter ( $p [154] ) == false)) {
			$ret ['EY'] = $fString1->filter ( $p [154] );
		} else {
			$ret ['EY'] = null;
		}
		
		// 155
		if (isset ( $p [155] ) && ($p [155] != null) && ($fIsEmpty->filter ( $p [155] ) == false)) {
			$ret ['EZ'] = $fString4->filter ( $p [155] );
		} else {
			$ret ['EZ'] = null;
		}
		
		// 156
		if (isset ( $p [156] ) && ($p [156] != null) && ($fIsEmpty->filter ( $p [156] ) == false)) {
			$ret ['FA'] = $fString4->filter ( $p [156] );
		} else {
			$ret ['FA'] = null;
		}
		
		// 157
		if (isset ( $p [157] ) && ($p [157] != null) && ($fIsEmpty->filter ( $p [157] ) == false)) {
			$ret ['FB'] = $fString4->filter ( $p [157] );
		} else {
			$ret ['FB'] = null;
		}
		
		// 158
		if (isset ( $p [158] ) && ($p [158] != null) && ($fIsEmpty->filter ( $p [158] ) == false)) {
			$ret ['FC'] = $fString4->filter ( $p [158] );
		} else {
			$ret ['FC'] = null;
		}
		
		// 159
		if (isset ( $p [159] ) && ($p [159] != null) && ($fIsEmpty->filter ( $p [159] ) == false)) {
			$ret ['FD'] = $fString4->filter ( $p [159] );
		} else {
			$ret ['FD'] = null;
		}
		
		// 160
		if (isset ( $p [160] ) && ($p [160] != null) && ($fIsEmpty->filter ( $p [160] ) == false)) {
			$ret ['FE'] = $fString20->filter ( $p [160] );
		} else {
			$ret ['FE'] = null;
		}
		
		// 161
		if (isset ( $p [161] ) && ($p [161] != null) && ($fIsEmpty->filter ( $p [161] ) == false)) {
			$ret ['FF'] = $fString20->filter ( $p [161] );
		} else {
			$ret ['FF'] = null;
		}
		
		// 162
		if (isset ( $p [162] ) && ($p [162] != null) && ($fIsEmpty->filter ( $p [162] ) == false)) {
			$ret ['FG'] = $fString20->filter ( $p [162] );
		} else {
			$ret ['FG'] = null;
		}
		
		// 163
		if (isset ( $p [163] ) && ($p [163] != null) && ($fIsEmpty->filter ( $p [163] ) == false)) {
			$ret ['FH'] = $fString20->filter ( $p [163] );
		} else {
			$ret ['FH'] = null;
		}
		
		// 164
		if (isset ( $p [164] ) && ($p [164] != null) && ($fIsEmpty->filter ( $p [164] ) == false)) {
			$ret ['FI'] = $fString30->filter ( $p [164] );
		} else {
			$ret ['FI'] = null;
		}
		
		// 165
		if (isset ( $p [165] ) && ($p [165] != null) && ($fIsEmpty->filter ( $p [165] ) == false)) {
			$ret ['FJ'] = $fString10->filter ( $p [165] );
		} else {
			$ret ['FJ'] = null;
		}
		
		// 166
		if (isset ( $p [166] ) && ($p [166] != null) && ($fIsEmpty->filter ( $p [166] ) == false)) {
			$ret ['FK'] = $fString4->filter ( $p [166] );
		} else {
			$ret ['FK'] = null;
		}
		
		// 167
		if (isset ( $p [167] ) && ($p [167] != null) && ($fIsEmpty->filter ( $p [167] ) == false)) {
			$ret ['FL'] = $fString5->filter ( $p [167] );
		} else {
			$ret ['FL'] = null;
		}
		
		// 168
		if (isset ( $p [158] ) && ($p [168] != null) && ($fIsEmpty->filter ( $p [168] ) == false)) {
			$ret ['FM'] = $fString500->filter ( $p [168] );
		} else {
			$ret ['FM'] = null;
		}
		
		// 169
		if (isset ( $p [169] ) && ($p [169] != null) && ($fIsEmpty->filter ( $p [169] ) == false)) {
			$ret ['FN'] = $fString2->filter ( $p [169] );
		} else {
			$ret ['FN'] = null;
		}
		
		// 170
		if (isset ( $p [170] ) && ($p [170] != null) && ($fIsEmpty->filter ( $p [170] ) == false)) {
			$ret ['FO'] = $fString20->filter ( $p [170] );
		} else {
			$ret ['FO'] = null;
		}
		
		return $ret;
	}
	public function handleDatexExp($p) {
		include_once ('default/models/default/db_selVPic.php');
		
		$this->prot = array (
				'ERROR' => array (),
				'INFO' => array (),
				'EXPORTED' => array (),
				'NOT_EXPORTED' => array () 
		);
		
		$lang = $this->lang;
		$user = null;
		$vPhoto = null;
		if (isset ( $this->userNS->userData ) && isset ( $this->userNS->userLogged ) && ($this->userNS->userLogged == true)) {
			$user = $this->userNS->userData;
		} elseif ($this->userData != null) {
			$user = $this->userData;
		} else {
			$p ['ERROR'] = $lang ['ERR_42'];
		}
		
		$vType = null;
		if (isset ( $p ['vType'] )) {
			$p ['vType'] = strtolower ( $p ['vType'] );
			switch ($p ['vType']) {
				case System_Properties::CAR_ABRV :
					$vType = System_Properties::CAR_ABRV;
					break;
				case System_Properties::BIKE_ABRV :
					$vType = System_Properties::BIKE_ABRV;
					break;
				case System_Properties::TRUCK_ABRV :
					$vType = System_Properties::TRUCK_ABRV;
					break;
			}
		} else {
			$p ['ERROR'] = $lang ['ERR_47'];
		}
		
		if (isset ( $p ['V_ADS'] ) && ($user != null) && ($vType != null)) {
			
			$vAds = $this->transA2VStruc ( $p ['V_ADS'] );
			if (is_array ( $vAds )) {
				$downloadFolder = $this->createDownloadFolder ( array (
						'userID' => $user ['userID'] 
				) );
				
				$fileCSVName = $downloadFolder . '/' . $vType . '_' . $user ['userID'] . '.csv';
				$fileZIPName = $downloadFolder . '/' . $vType . '_' . $user ['userID'] . '.zip';
				
				// Create CSV File
				$csvExpFileHandler = fopen ( $fileCSVName, 'w' );
				if ($csvExpFileHandler != false) {
					fputs ( $csvExpFileHandler, "\xEF\xBB\xBF" );
					foreach ( $vAds as $key => $kVal ) {
						$mobileStruc = $this->transStruc2Mobile ( $kVal );
						if ($mobileStruc != false) {
							if (isset ( $kVal ['vID'] )) {
								$mobileStruc ['B'] = $vType . '_' . $kVal ['vID'];
							} /*
							   * $mobileStrucOld = array();
							   * foreach ($mobileStruc as $key2 => $kVal2){
							   * $mobileStrucOld[$key2] = utf8_encode($kVal2);
							   * }
							   * $mobileStruc = $mobileStrucOld;
							   */
							if (fputcsv ( $csvExpFileHandler, $mobileStruc, ';' ) != false) {
								array_push ( $this->prot ['EXPORTED'], $kVal ['vID'] );
							} else {
								array_push ( $this->prot ['NOT_EXPORTED'], $kVal ['vID'] );
							}
						} else {
							array_push ( $this->prot ['NOT_EXPORTED'], $kVal ['vID'] );
						}
					}
					fclose ( $csvExpFileHandler );
					array_push ( $this->prot ['INFO'], $lang ['INFO_12'] );
					
					// determine photo and create zip file
					if (isset ( $p ['fileExpFoto'] ) && ($p ['fileExpFoto'] == 1)) {
						$vIdPhoto = array ();
						foreach ( $vAds as $key => $kVal ) {
							if (isset ( $kVal ['vID'] )) {
								array_push ( $vIdPhoto, $kVal ['vID'] );
							}
						}
						
						if (count ( $vIdPhoto ) > 0) {
							$vPhoto = db_selVPic ( array (
									'vID' => $vIdPhoto,
									'vType' => $vType 
							) );
							if (is_array ( $vPhoto ) && (count ( $vPhoto ) > 0)) {
								$zipExpFileHandler = new ZipArchive ();
								$zipExpFileHandler->open ( $fileZIPName, ZipArchive::CREATE );
								if ($zipExpFileHandler == true) {
									$zipExpFileHandler->addFile ( $fileCSVName, basename ( $fileCSVName ) );
									
									$i = null;
									$lastVID = null;
									foreach ( $vPhoto as $key => $kVal ) {
										if (is_array ( $kVal ) && isset ( $kVal ['vPicID'] ) && isset ( $kVal ['vType'] ) && isset ( $kVal ['vID'] )) {
											$filePhoto = './' . System_Properties::PIC_PATH . '/' . $kVal ['vType'] . '_' . $kVal ['vID'] . '_' . $kVal ['vPicID'] . '.' . System_Properties::$STD_PIC_EXT;
											if (file_exists ( $filePhoto )) {
												if ($kVal ['vID'] != $lastVID) {
													$i = '0';
													$lastVID = $kVal ['vID'];
												}
												$i ++;
												$localFilePhoto = $kVal ['vType'] . '_' . $kVal ['vID'] . '_' . ($i < 10 ? '0' : '') . $i . '.' . System_Properties::$STD_PIC_EXT;
												$zipExpFileHandler->addFile ( $filePhoto, $localFilePhoto );
											}
										}
									}
									
									$zipExpFileHandler->close ();
								}
							}
						}
					}
				} else {
					$p ['ERROR'] = $lang ['ERR_48'];
				}
			} else {
				array_push ( $this->prot ['ERROR'], $lang ['ERR_45'] );
			}
		} else {
			array_push ( $this->prot ['ERROR'], $lang ['ERR_45'] );
		}
		
		$p ['PROT'] = $this->prot;
		return $p;
	}
	private function transStruc2Mobile($p) {
		$vAd = $this->getInitialMobildeStruc ();
		$lang = $this->lang;
		
		$vAd ['A'] = '';
		
		// vID
		if (isset ( $p ['vID'] )) {
			$vAd ['B'] = $p ['vID'];
		}
		
		// vBrand
		if (isset ( $p ['vBrandName'] )) {
			$vAd ['D'] = $p ['vBrandName'];
		}
		
		// vModelName
		if (isset ( $p ['vModelName'] )) {
			$vAd ['E'] = $p ['vModelName'];
		}
		
		// vPrice
		if (isset ( $p ['vPrice'] )) {
			$vAd ['K'] = $p ['vPrice'];
		}
		
		// carPriceType
		// $vAd['carPriceType'] = '0';
		
		// vPriceCurr
		if (isset ( $p ['vPriceCurr'] )) {
			// EURO
			if ($p ['vPriceCurr'] == '0') {
				$vAd ['AC'] = 'Euro';
			} /*
			   * //Rubel
			   * elseif ($p['vPriceCurr'] == 1){
			   * $vAd['L'] = 'Rubel';
			   * }
			   */
		}
		
		// mwst
		if (isset ( $p ['mwst'] )) {
			$vAd ['L'] = '1';
			if ($p ['mwst'] == '1') {
				$vAd ['L'] = '0';
			}
		}
		
		// mwstSatz
		if (isset ( $p ['mwstSatz'] )) {
			$vAd ['AD'] = $p ['mwstSatz'];
		}
		
		// vKM
		if (isset ( $p ['vKM'] ) && is_numeric ( $p ['vKM'] )) {
			$vAd ['J'] = $p ['vKM'];
			
			// carKMType -> see var. $lang['TXT_75']
			// $vAd['carKMType'] = '0';
		}
		
		// vPower
		if (isset ( $p ['vPower'] ) && is_numeric ( $p ['vPower'] )) {
			$vAd ['F'] = $p ['vPower'];
			
			// carPowerType -> see var. $lang['TXT_72']
			// $vAd['T'] = '0';
		}
		
		// vEZM, vEZY
		if (isset ( $p ['vEZY'] ) && isset ( $p ['vEZM'] ) && ($p ['vEZY'] > 0) && ($p ['vEZM'] > 0)) {
			$vAd ['I'] = (strlen ( $p ['vEZM'] ) < 2 ? '0' : '') . $p ['vEZM'] . '.' . $p ['vEZY'];
		}
		
		// vTUVM, vTUVY
		if (isset ( $p ['vTUVM'] ) && isset ( $p ['vTUVY'] ) && ($p ['vTUVM'] > 0) && ($p ['vTUVY'] > 0)) {
			$vAd ['G'] = (strlen ( $p ['vTUVM'] ) < 2 ? '0' : '') . $p ['vTUVM'] . '.' . $p ['vTUVY'];
		}
		// vAUM, vAUY
		if (isset ( $p ['vAUM'] ) && isset ( $p ['vAUY'] ) && ($p ['vAUM'] > 0) && ($p ['vAUY'] > 0)) {
			$vAd ['H'] = (strlen ( $p ['vAUM'] ) < 2 ? '0' : '') . $p ['vAUM'] . '.' . $p ['vAUY'];
		}
		
		// vShift -> see var. $lang['V_SHIFT']
		if (isset ( $p ['vShift'] )) {
			// Manuel?
			if ($p ['vShift'] == '0') {
				$vAd ['DG'] = 1;
			} // Automatik?
elseif ($p ['vShift'] == 1) {
				$vAd ['H'] = 3;
			} // Halbautomatik?
elseif ($p ['vShift'] == 1) {
				$vAd ['H'] = 3;
			}
		}
		
		// vWeight
		if (isset ( $p ['vWeight'] ) && is_numeric ( $p ['vWeight'] )) {
			$vAd ['BD'] = $p ['vWeight'];
		}
		/*
		 * //vCyl
		 * if (isset($p['vCyl']) && is_numeric($p['vCyl'])){
		 * $vAd['U'] = $p['vCyl'];
		 * }
		 */
		// vCub
		if (isset ( $p ['vCub'] ) && is_numeric ( $p ['vCub'] )) {
			$vAd ['BA'] = $p ['vCub'];
		}
		
		// vDoor -> see var. $lang['CAR_DOOR']
		if (isset ( $p ['vDoor'] ) && is_numeric ( $p ['vDoor'] )) {
			if ($p ['vDoor'] == '0') {
				$vAd ['AQ'] = 2;
			} elseif ($p ['vDoor'] == 1) {
				$vAd ['AQ'] = 4;
			}
		}
		
		// vUseIn
		if (isset ( $p ['vUseIn'] ) && is_numeric ( $p ['vUseIn'] )) {
			$vAd ['CS'] = str_replace ( '.', ',', $p ['vUseIn'] );
		}
		
		// vUseOut
		if (isset ( $p ['vUseOut'] ) && is_numeric ( $p ['vUseOut'] )) {
			$vAd ['CT'] = str_replace ( '.', ',', $p ['vUseOut'] );
		}
		
		// vCO2
		if (isset ( $p ['vCO2'] ) && is_numeric ( $p ['vCO2'] )) {
			$vAd ['CV'] = str_replace ( '.', ',', $p ['vCO2'] );
		}
		
		// vState
		if (isset ( $p ['vState'] ) && ($p ['vState'] == 3)) {
			// Blechschaden
			$vAd ['P'] = 1;
		} elseif (isset ( $p ['vState'] ) && ($p ['vState'] == 4)) {
			// Unfallfahrzeug
			$vAd ['AE'] = 1;
		} elseif (isset ( $p ['vState'] ) && ($p ['vState'] == 2)) {
			// Gebrauchtwagen
			$vAd ['DI'] = 1;
		} elseif (isset ( $p ['vState'] ) && ($p ['vState'] == 1)) {
			// Jahrewagen
			$vAd ['U'] = 1;
		} elseif (isset ( $p ['vState'] ) && ($p ['vState'] == '0')) {
			// Neuwagen
			$vAd ['V'] = 1;
		} elseif (isset ( $p ['vState'] ) && ($p ['vState'] == 6)) {
			// Vorführwagen
			$vAd ['AY'] = 1;
		}
		
		// vCat -> see var. $lang['CAR_CAT']
		if (isset ( $p ['vCat'] ) && isset ( $lang ['V_CAT'] [$p ['vCat']] )) {
			$vcatName = $lang ['V_CAT'] [$p ['vCat']];
			if (stristr ( $vcatName, 'bus' )) {
				// Kleinbus
				$vAd ['C'] = 'Bus';
			} elseif (stristr ( $vcatName, 'cabr' )) {
				// Cabrio
				$vAd ['C'] = 'Cabrio';
			} elseif (stristr ( $vcatName, 'ndewagen' )) {
				// Geländewagen
				$vAd ['C'] = 'Geländewagen';
			} elseif (stristr ( $vcatName, 'kombi' ) || stristr ( $vcatName, 'van' )) {
				// Kombi/Van
				$vAd ['C'] = 'Kombi/Van';
			} elseif (stristr ( $vcatName, 'liefer' )) {
				// Lieferewagen
				$vAd ['C'] = 'Lieferwagen';
			}
			
			if (($vAd ['C'] == '') && isset ( $p ['vDoor'] )) {
				switch ($p ['vDoor']) {
					case 0 :
						$vAd ['C'] = '2/3-Türer';
						break;
					case 1 :
						$vAd ['C'] = '4/5-Türer';
						break;
				}
			}
			
			if (($vAd ['C'] == '')) {
				$vAd ['C'] = 'Sonstiges';
			}
		}
		
		// vFuel -> see var. $lang['V_FUEL']
		if (isset ( $p ['vFuel'] )) {
			switch ($p ['vFuel']) {
				// Benzin
				case 0 :
					$vAd ['DF'] = 1;
					break;
				// Diesel
				case 1 :
					$vAd ['DF'] = 2;
					break;
				// Gas
				case 8 :
					$vAd ['DF'] = 4;
					break;
				// Ethanol
				case 4 :
					$vAd ['DF'] = 9;
					break;
				// Elektro
				case 5 :
					$vAd ['DF'] = 6;
					break;
				// LPG Gas
				case 3 :
					$vAd ['DF'] = 3;
					break;
				// CNG Gas
				case 6 :
					$vAd ['DF'] = 3;
					break;
				// Hybrid
				case 7 :
					$vAd ['DF'] = 7;
					break;
			}
		}
		
		// vClr -> see var. $lang['V_CLR']
		if (isset ( $p ['vClr'] ) && isset ( $lang ['V_CLR'] [$p ['vClr']] )) {
			$vClr = $lang ['V_CLR'] [$p ['vClr']];
			$vAd ['Q'] = $vClr;
			/*
			 * if(stristr($vClr, 'beige')){
			 * $vAd['CN'] = 'Beige';
			 * }
			 * elseif (stristr($vClr, 'blau') && stristr($vClr, 'hell')){
			 * $vAd['CN'] = 'Hellblau';
			 * }
			 * elseif (stristr($vClr, 'blau')){
			 * $p['CN'] = 'Blau';
			 * }
			 * elseif (stristr($vClr, 'braun')){
			 * $vAd['CN'] = 'Braun';
			 * }
			 * elseif (stristr($vClr, 'bronze')){
			 * $vAd['CN'] = 'Bronze';
			 * }
			 * elseif (stristr($vClr, 'dunk') && stristr($vClr, 'rot')){
			 * $vAd['CN'] = 'Dunkel Rot';
			 * }
			 * elseif (stristr($vClr, 'gelb')){
			 * $vAd['CN'] = 'Gelb';
			 * }
			 * elseif (stristr($vClr, 'gold')){
			 * $vAd['CN'] = 'Gold';
			 * }
			 * elseif (stristr($vClr, 'grau') && stristr($vClr, 'hell')){
			 * $vAd['CN'] = 'Hellgrau';
			 * }
			 * elseif (stristr($vClr, 'grau')){
			 * $vAd['CN'] = 'Grau';
			 * }
			 * elseif (stristr($vClr, 'grün') && stristr($vClr, 'hell')){
			 * $vAd['CN'] = 'Hellgrün';
			 * }
			 * elseif (stristr($vClr, 'grün')){
			 * $vAd['CN'] = 'Grün';
			 * }
			 * elseif (stristr($vClr, 'orang')){
			 * $vAd['CN'] = 'Orange';
			 * }
			 * elseif (stristr($vClr, 'ro')){
			 * $vAd['CN'] = 'Rot';
			 * }
			 * elseif (stristr($vClr, 'schwar')){
			 * $vAd['CN'] = 'Schwarz';
			 * }
			 * elseif (stristr($vClr, 'silb')){
			 * $vAd['CN'] = 'Silber';
			 * }
			 * elseif (stristr($vClr, 'viole')){
			 * $vAd['CN'] = 'Violett';
			 * }
			 * elseif (stristr($vClr, 'weiß')){
			 * $vAd['CN'] = 'Weiß';
			 * }
			 */
		}
		
		// vClrMet
		if (isset ( $p ['vClrMet'] ) && ($p ['vClrMet'] == 1)) {
			$vAd ['AB'] = 1;
		}
		
		// vEmissionNorm -> see var. $lang['V_EMISSION_NORM']
		if (isset ( $p ['vEmissionNorm'] )) {
			switch ($p ['vEmissionNorm']) {
				case 1 :
					$vAd ['BJ'] = 1;
					break;
				case 2 :
					$vAd ['BJ'] = 2;
					break;
				case 3 :
					$vAd ['BJ'] = 3;
					break;
				case 4 :
					$vAd ['BJ'] = 4;
					break;
				case 5 :
					$vAd ['BJ'] = 5;
					break;
				case 6 :
					$vAd ['BJ'] = 5;
					break;
			}
		}
		
		// vEcologicTag -> see var. $lang['V_ECOLOGIC_TAG']
		if (isset ( $p ['vEcologicTag'] )) {
			switch ($p ['vEcologicTag']) {
				case 0 :
					$vAd ['AR'] = '0';
					break;
				case 1 :
					$vAd ['AR'] = 1;
					break;
				case 2 :
					$vAd ['AR'] = 2;
					break;
				case 3 :
					$vAd ['AR'] = 3;
					break;
			}
		}
		
		// vKlima -> see var. $lang['V_KLIMA']
		if (isset ( $p ['vKlima'] )) {
			switch ($p ['vKlima']) {
				// Klima
				case 0 :
					$vAd ['R'] = 1;
					break;
				// Klimaautomatik
				case 1 :
					$vAd ['R'] = 2;
					break;
			}
		}
		
		// vDesc
		if (isset ( $p ['vDesc'] )) {
			$vAd ['Z'] = $p ['vDesc'];
		}
		
		// vEEK
		if (isset ( $p ['vEEK'] )) {
			$vAd ['FN'] = $p ['vEEK'];
		}
		
		// userAdsLength
		// $vAd['userAdsLength'] = 4;
		
		/*
		 * $user = null;
		 * if (isset($this -> userNS -> userData)){
		 * $user = $this -> userNS -> userData;
		 *
		 * //userAds -> see var. $lang['TXT_33']
		 * $vAd['userAds'] = '-1';
		 * if (isset($user['userMode']) && ($user['userMode'] != 3)){
		 * $vAd['userAds'] = $user['userMode'];
		 * }
		 *
		 * //userFirm
		 * if (isset($user['userFirm'])){
		 * $vAd['userFirm'] = $user['userFirm'];
		 * }
		 *
		 * //userNName
		 * if (isset($user['userNName'])){
		 * $vAd['userNName'] = $user['userNName'];
		 * }
		 *
		 * //userVName
		 * if (isset($user['userVName'])){
		 * $vAd['userVName'] = $user['userVName'];
		 * }
		 *
		 * //userEMail
		 * if (isset($user['userEMail'])){
		 * $vAd['userEMail'] = $user['userEMail'];
		 * }
		 *
		 * //userPLZ
		 * if (isset($user['userPLZ'])){
		 * $vAd['userPLZ'] = $user['userPLZ'];
		 * $vAd['carLocPLZ'] = $user['userPLZ'];
		 * }
		 *
		 * //userOrt
		 * if (isset($user['userOrt'])){
		 * $vAd['userOrt'] = $user['userOrt'];
		 * $vAd['carLocOrt'] = $user['userOrt'];
		 * }
		 *
		 * //userTel1
		 * if (isset($user['userTel1'])){
		 * $vAd['userTel1'] = $user['userTel1'];
		 * }
		 *
		 * //userTel2
		 * if (isset($user['userTel2'])){
		 * $vAd['userTel2'] = $user['userTel2'];
		 * }
		 *
		 * //userAdress
		 * if (isset($user['userAdress'])){
		 * $vAd['userAdress'] = $user['userAdress'];
		 * }
		 *
		 * //userTel2
		 * if (isset($user['userTel2'])){
		 * $vAd['userTel2'] = $user['userTel2'];
		 * }
		 *
		 * //userID
		 * if (isset($user['userID'])){
		 * $vAd['userID'] = $user['userID'];
		 * }
		 * }
		 */
		
		return $vAd;
	}
	private function getInitialMobildeStruc() {
		$ret = array ();
		$ret ['A'] = '';
		$ret ['B'] = '';
		$ret ['C'] = '';
		$ret ['D'] = '';
		$ret ['E'] = '';
		$ret ['F'] = '';
		$ret ['G'] = '';
		$ret ['H'] = '';
		$ret ['I'] = '';
		$ret ['J'] = '';
		$ret ['K'] = '';
		$ret ['L'] = '';
		$ret ['M'] = '';
		$ret ['N'] = '';
		$ret ['O'] = '';
		$ret ['P'] = '';
		$ret ['Q'] = '';
		$ret ['R'] = '';
		$ret ['S'] = '';
		$ret ['T'] = '';
		$ret ['U'] = '';
		$ret ['V'] = '';
		$ret ['W'] = '';
		$ret ['X'] = '';
		$ret ['Y'] = '';
		$ret ['Z'] = '';
		$ret ['AA'] = '';
		$ret ['AB'] = '';
		$ret ['AC'] = '';
		$ret ['AD'] = '';
		$ret ['AE'] = '';
		$ret ['AF'] = '';
		$ret ['AG'] = '';
		$ret ['AH'] = '';
		$ret ['AI'] = '';
		$ret ['AJ'] = '';
		$ret ['AK'] = '';
		$ret ['AL'] = '';
		$ret ['AM'] = '';
		$ret ['AN'] = '';
		$ret ['AO'] = '';
		$ret ['AP'] = '';
		$ret ['AQ'] = '';
		$ret ['AR'] = '';
		$ret ['AS'] = '';
		$ret ['AT'] = '';
		$ret ['AU'] = '';
		$ret ['AV'] = '';
		$ret ['AW'] = '';
		$ret ['AX'] = '';
		$ret ['AY'] = '';
		$ret ['AZ'] = '';
		$ret ['BA'] = '';
		$ret ['BB'] = '';
		$ret ['BC'] = '';
		$ret ['BD'] = '';
		$ret ['BE'] = '';
		$ret ['BF'] = '';
		$ret ['BG'] = '';
		$ret ['BH'] = '';
		$ret ['BI'] = '';
		$ret ['BJ'] = '';
		$ret ['BK'] = '';
		$ret ['BL'] = '';
		$ret ['BM'] = '';
		$ret ['BN'] = '';
		$ret ['BO'] = '';
		$ret ['BP'] = '';
		$ret ['BQ'] = '';
		$ret ['BR'] = '';
		$ret ['BS'] = '';
		$ret ['BT'] = '';
		$ret ['BU'] = '';
		$ret ['BV'] = '';
		$ret ['BW'] = '';
		$ret ['BX'] = '';
		$ret ['BY'] = '';
		$ret ['BZ'] = '';
		$ret ['CA'] = '';
		$ret ['CB'] = '';
		$ret ['CC'] = '';
		$ret ['CD'] = '';
		$ret ['CE'] = '';
		$ret ['CF'] = '';
		$ret ['CG'] = '';
		$ret ['CH'] = '';
		$ret ['CI'] = '';
		$ret ['CJ'] = '';
		$ret ['CK'] = '';
		$ret ['CL'] = '';
		$ret ['CM'] = '';
		$ret ['CN'] = '';
		$ret ['CO'] = '';
		$ret ['CP'] = '';
		$ret ['CQ'] = '';
		$ret ['CR'] = '';
		$ret ['CS'] = '';
		$ret ['CT'] = '';
		$ret ['CU'] = '';
		$ret ['CV'] = '';
		$ret ['CW'] = '';
		$ret ['CX'] = '';
		$ret ['CY'] = '';
		$ret ['CZ'] = '';
		$ret ['DA'] = '';
		$ret ['DB'] = '';
		$ret ['DC'] = '';
		$ret ['DD'] = '';
		$ret ['DE'] = '';
		$ret ['DF'] = '';
		$ret ['DG'] = '';
		$ret ['DH'] = '';
		$ret ['DI'] = '';
		$ret ['DJ'] = '';
		$ret ['DK'] = '';
		$ret ['DL'] = '';
		$ret ['DM'] = '';
		$ret ['DN'] = '';
		$ret ['DO'] = '';
		$ret ['DP'] = '';
		$ret ['DQ'] = '';
		$ret ['DR'] = '';
		$ret ['DS'] = '';
		$ret ['DT'] = '';
		$ret ['DU'] = '';
		$ret ['DV'] = '';
		$ret ['DW'] = '';
		$ret ['DX'] = '';
		$ret ['DY'] = '';
		$ret ['DZ'] = '';
		$ret ['EA'] = '';
		$ret ['EB'] = '';
		$ret ['EC'] = '';
		$ret ['ED'] = '';
		$ret ['EE'] = '';
		$ret ['EF'] = '';
		$ret ['EG'] = '';
		$ret ['EH'] = '';
		$ret ['EI'] = '';
		$ret ['EJ'] = '';
		$ret ['EK'] = '';
		$ret ['EL'] = '';
		$ret ['EM'] = '';
		$ret ['EN'] = '';
		$ret ['EO'] = '';
		$ret ['EP'] = '';
		$ret ['EQ'] = '';
		$ret ['ER'] = '';
		$ret ['ES'] = '';
		$ret ['ET'] = '';
		$ret ['EU'] = '';
		$ret ['EV'] = '';
		$ret ['EW'] = '';
		$ret ['EX'] = '';
		$ret ['EY'] = '';
		$ret ['EZ'] = '';
		$ret ['FA'] = '';
		$ret ['FB'] = '';
		$ret ['FC'] = '';
		$ret ['FD'] = '';
		$ret ['FE'] = '';
		$ret ['FF'] = '';
		$ret ['FG'] = '';
		$ret ['FH'] = '';
		$ret ['FI'] = '';
		$ret ['FJ'] = '';
		$ret ['FK'] = '';
		$ret ['FL'] = '';
		$ret ['FM'] = '';
		$ret ['FN'] = '';
		$ret ['FO'] = '';
		
		return $ret;
	}
	
	/**
	 * This function filter a truck advertisement
	 *
	 * @param $p: this
	 *        	contains the parameter of a truck advertisement
	 */
	protected function filterTruckData($p) {
		$lang = $this->lang;
		
		include_once ('default/models/truck/db_selTruckBrand.php');
		include_once ('default/models/truck/db_selTruckModel.php');
		// Process only if cins is pressed
		include_once ('default/views/filters/FilterMySQLInt.php');
		include_once ('default/views/filters/FilterMySQLMInt.php');
		include_once ('default/views/filters/FilterMonth.php');
		include_once ('default/views/filters/FilterYear.php');
		include_once ('default/views/filters/FilterValidEmail.php');
		include_once ('default/views/filters/FilterString100.php');
		include_once ('default/views/filters/FilterString20.php');
		
		$fInt = new FilterMySQLInt ();
		$fMInt = new FilterMySQLMInt ();
		$fMonth = new FilterMonth ();
		$fYear = new FilterYear ();
		$fEMail = new FilterValidEmail ();
		$fString100 = new FilterString100 ();
		$fString20 = new FilterString20 ();
		
		// Check truckBrand
		if (! isset ( $p ['truckBrand'] )) {
			$p ['error'] = $lang ['ERR_2'];
		} elseif (($truckBrand = db_selTruckBrand ( array (
				'truckBrandID' => $p ['truckBrand'] 
		) )) == false) {
			$p ['error'] = $lang ['ERR_2'];
		}  // Check truckPrice
else if (! isset ( $p ['truckPrice'] ) || ($fInt->isValid ( $p ['truckPrice'] ) == false)) {
			$p ['error'] = $lang ['ERR_2'];
		}  /*
		   * //Check truckPower
		   * else if (!isset($p['truckPower']) || ($fMInt -> isValid($p['truckPower']) == false)){
		   * $p['error'] = $lang['ERR_2'];
		   * }
		   * //Check truckKM
		   * else if (!isset($p['truckKM']) || ($fMInt -> isValid($p['truckKM']) == false)){
		   * $p['error'] = $lang['ERR_2'];
		   * }
		   */
		// Check truckEZ
		else if ((! isset ( $p ['truckEZM'] ) || ! isset ( $p ['truckEZY'] ) || ($fMonth->isValid ( $p ['truckEZM'] ) == false) || ($fYear->isValid ( $p ['truckEZY'] ) == false)) && ($p ['truckState'] != '0')) {
			$p ['error'] = $lang ['ERR_2'];
		} // Check userNName
elseif (! isset ( $p ['userNName'] ) || ($fString100->isValid ( $p ['userNName'] ) == false)) {
			$p ['error'] = $lang ['ERR_2'];
		}  // Check userVName
else if (! isset ( $p ['userVName'] ) || ($fString100->isValid ( $p ['userVName'] ) == false)) {
			$p ['error'] = $lang ['ERR_2'];
		}  // Check userEmail
else if (! isset ( $p ['userEMail'] ) || $fEMail->filter ( $p ['userEMail'] ) == false) {
			$p ['error'] = $lang ['ERR_2'];
		}  /*
		   * //Check truckLocPLZ
		   * else if ( !isset($p['truckLocPLZ']) || $fString20->filter($p['truckLocPLZ']) == false){
		   * $p['error'] = $lang['ERR_2'];
		   * }
		   */
else {
			
			// Include and instantiate diverse filter
			include_once ('default/views/filters/FilterMySQLSInt.php');
			include_once ('default/views/filters/FilterMySQLTInt.php');
			include_once ('default/views/filters/FilterString10.php');
			include_once ('default/views/filters/FilterString50.php');
			include_once ('default/views/filters/FilterString1000.php');
			$fSInt = new FilterMySQLSInt ();
			$fTInt = new FilterMySQLTInt ();
			$fString10 = new FilterString10 ();
			$fString50 = new FilterString50 ();
			$fString1000 = new FilterString1000 ();
			
			// Check Model
			$p ['truckModelTxt'] = null;
			if (isset ( $p ['truckModel'] ) && ($p ['truckModel'] != - 1)) {
				$truckModel = db_selTruckModel ( array (
						'truckBrandID' => $truckBrand [0] ['truckBrandID'],
						'truckModelID' => $p ['truckModel'] 
				) );
				if ($truckModel != false) {
					$p ['truckModelTxt'] = $truckModel [0] ['truckModelName'];
				}
			} else {
				$p ['truckModel'] = - 1;
			}
			
			if (isset ( $p ['truckModelVar'] )) {
				$p ['truckModelVar'] = $fString100->filter ( $p ['truckModelVar'] );
			} else {
				$p ['truckModelVar'] = '';
			}
			
			$p ['truckBrandTxt'] = $truckBrand [0] ['brandName'];
			
			if (! isset ( $p ['truckPriceType'] ) || ($p ['truckPriceType'] == null)) {
				$p ['truckPriceType'] = 0;
			} else {
				$p ['truckPriceType'] = $fTInt->filter ( $p ['truckPriceType'] );
				if (! isset ( $lang ['TXT_70'] [$p ['truckPriceType']] )) {
					$p ['truckPriceType'] = 0;
				}
			}
			
			if (! isset ( $p ['truckPriceCurr'] ) || ($p ['truckPriceCurr'] == null)) {
				$p ['truckPriceCurr'] = 0;
			} else {
				$p ['truckPriceCurr'] = $fTInt->filter ( $p ['truckPriceCurr'] );
				if (! isset ( $lang ['TXT_74'] [$p ['truckPriceCurr']] )) {
					$p ['truckPriceCurr'] = 0;
				}
			}
			
			// MwSt
			if (isset ( $p ['mwst'] )) {
				$p ['mwst'] = 1;
			} else {
				$p ['mwst'] = 0;
			}
			if (isset ( $p ['mwstSatz'] ) && isset ( $lang ['V_MWST'] ) && is_array ( $lang ['V_MWST'] ) && isset ( $p ['mwst'] ) && ($p ['mwst'] == 1)) {
				$p ['mwstSatz'] = str_replace ( ',', '.', $p ['mwstSatz'] );
				if (! in_array ( $p ['mwstSatz'], $lang ['V_MWST'] ) && ($p ['mwst'] === '0')) {
					$p ['mwstSatz'] = 19;
				}
			} else {
				$p ['mwstSatz'] = '-1';
			}
			
			// Check truckPower
			if (! isset ( $p ['truckPower'] ) || ($fMInt->isValid ( $p ['truckPower'] ) == false)) {
				$p ['truckPower'] = '0';
			}
			
			if (! isset ( $p ['truckPowerType'] ) || ($p ['truckPowerType'] == null)) {
				$p ['truckPowerType'] = 0;
			} else {
				$p ['truckPowerType'] = $fMInt->filter ( $p ['truckPowerType'] );
				if (! isset ( $lang ['TXT_72'] [$p ['truckPowerType']] )) {
					$p ['truckPowerType'] = 0;
				}
			}
			
			// Check truckKM
			if (! isset ( $p ['truckKM'] ) || ($fMInt->isValid ( $p ['truckKM'] ) == false)) {
				$p ['truckKM'] = '0';
			}
			
			if (! isset ( $p ['truckKMType'] ) || ($p ['truckKMType'] == null)) {
				$p ['truckKMType'] = 0;
			} else {
				$p ['truckKMType'] = $fTInt->filter ( $p ['truckKMType'] );
				if (! isset ( $lang ['TXT_75'] [$p ['truckKMType']] )) {
					$p ['truckKMType'] = 0;
				}
			}
			
			if (isset ( $p ['truckHSN'] )) {
				$p ['truckHSN'] = $fString10->filter ( $p ['truckHSN'] );
			}
			
			if (isset ( $p ['truckTSN'] )) {
				$p ['truckTSN'] = $fString10->filter ( $p ['truckTSN'] );
			}
			
			if (isset ( $p ['truckFIN'] )) {
				$p ['truckFIN'] = $fString20->filter ( $p ['truckFIN'] );
			}
			
			if (! isset ( $p ['truckTUVM'] ) || ($p ['truckTUVM'] == null)) {
				$p ['truckTUVM'] = 1;
			} else {
				$p ['truckTUVM'] = $fMonth->filter ( $p ['truckTUVM'] );
			}
			if (! isset ( $p ['truckTUVY'] ) || ($p ['truckTUVY'] == null)) {
				$p ['truckTUVY'] = date ( 'Y' );
			} else {
				$p ['truckTUVY'] = $fYear->filter ( $p ['truckTUVY'] );
			}
			
			if (! isset ( $p ['truckAUM'] ) || ($p ['truckAUM'] == null)) {
				$p ['truckAUM'] = 1;
			} else {
				$p ['truckAUM'] = $fMonth->filter ( $p ['truckAUM'] );
			}
			if (! isset ( $p ['truckAUY'] ) || ($p ['truckAUY'] == null)) {
				$p ['truckAUY'] = date ( 'Y' );
			} else {
				$p ['truckAUY'] = $fYear->filter ( $p ['truckAUY'] );
			}
			
			if (! isset ( $p ['truckShift'] ) || ($p ['truckShift'] == null)) {
				$p ['truckShift'] = - 1;
			} else {
				$p ['truckShift'] = $fTInt->filter ( $p ['truckShift'] );
			}
			
			if (! isset ( $p ['truckWeight'] ) || ($p ['truckWeight'] == null)) {
				$p ['truckWeight'] = 0;
			} else {
				$p ['truckWeight'] = $fMInt->filter ( $p ['truckWeight'] );
			}
			
			if (! isset ( $p ['truckCyl'] ) || ($p ['truckCyl'] == null)) {
				$p ['truckCyl'] = 0;
			} else {
				$p ['truckCyl'] = $fTInt->filter ( $p ['truckCyl'] );
			}
			
			if (! isset ( $p ['truckCub'] ) || ($p ['truckCub'] == null)) {
				$p ['truckCub'] = 0;
			} else {
				$p ['truckCub'] = $fSInt->filter ( $p ['truckCub'] );
			}
			
			if (isset ( $p ['truckUseIn'] )) {
				$p ['truckUseIn'] = $fString50->filter ( $p ['truckUseIn'] );
			} else {
				$p ['truckUseIn'] = '';
			}
			
			if (isset ( $p ['truckUseOut'] )) {
				$p ['truckUseOut'] = $fString50->filter ( $p ['truckUseOut'] );
			} else {
				$p ['truckUseOut'] = '';
			}
			
			if (isset ( $p ['truckCO2'] )) {
				$p ['truckCO2'] = $fString50->filter ( $p ['truckCO2'] );
			} else {
				$p ['truckCO2'] = '';
			}
			
			if (! isset ( $p ['truckState'] ) || ($p ['truckState'] == null)) {
				$p ['truckState'] = - 1;
			} else {
				$p ['truckState'] = $fTInt->filter ( $p ['truckState'] );
			}
			
			if (! isset ( $p ['truckCat'] ) || ($p ['truckCat'] == null)) {
				$p ['truckCat'] = - 1;
			} else {
				$p ['truckCat'] = $fTInt->filter ( $p ['truckCat'] );
			}
			
			if (! isset ( $p ['truckFuel'] ) || ($p ['truckFuel'] == null)) {
				$p ['truckFuel'] = - 1;
			} else {
				$p ['truckFuel'] = $fTInt->filter ( $p ['truckFuel'] );
			}
			
			if (! isset ( $p ['truckClr'] ) || ($p ['truckClr'] == null)) {
				$p ['truckClr'] = - 1;
			} else {
				$p ['truckClr'] = $fTInt->filter ( $p ['truckClr'] );
			}
			if (isset ( $p ['truckClrMet'] )) {
				$p ['truckClrMet'] = '1';
			} else {
				$p ['truckClrMet'] = '0';
			}
			
			if (! isset ( $p ['truckEmissionNorm'] ) || ($p ['truckEmissionNorm'] == null)) {
				$p ['truckEmissionNorm'] = - 1;
			} else {
				$p ['truckEmissionNorm'] = $fTInt->filter ( $p ['truckEmissionNorm'] );
			}
			
			if (! isset ( $p ['truckEcologicTag'] ) || ($p ['truckEcologicTag'] == null)) {
				$p ['truckEcologicTag'] = - 1;
			} else {
				$p ['truckEcologicTag'] = $fTInt->isValid ( $p ['truckEcologicTag'] );
			}
			
			if (! isset ( $p ['truckKlima'] ) || ($p ['truckKlima'] == null)) {
				$p ['truckKlima'] = - 1;
			} else {
				$p ['truckKlima'] = $fTInt->isValid ( $p ['truckKlima'] );
			}
			
			$p ['truckDesc'] = $fString1000->filter ( $p ['truckDesc'] );
			
			if (! isset ( $p ['userAds'] ) || ($p ['userAds'] == null) || ($p ['userAds'] == - 1)) {
				$p ['userAds'] = - 1;
			} else {
				if (! isset ( $lang ['TXT_33'] [$p ['userAds']] )) {
					$p ['userAds'] = - 1;
					// $p['userAds'] = $fTInt->isValid($p['userAds']);
				}
			}
			
			// check userFirm
			isset ( $p ['userFirm'] ) ? $p ['userFirm'] = $fString100->filter ( $p ['userFirm'] ) : $p ['userFirm'] = '';
			
			// check userNName
			isset ( $p ['userNName'] ) ? $p ['userNName'] = $fString100->filter ( $p ['userNName'] ) : $p ['userNName'] = '';
			
			// check userVName
			isset ( $p ['userVName'] ) ? $p ['userVName'] = $fString100->filter ( $p ['userVName'] ) : $p ['userVName'] = '';
			
			// userPLZ
			isset ( $p ['userPLZ'] ) ? $p ['userPLZ'] = $fString20->filter ( $p ['userPLZ'] ) : $p ['userPLZ'] = '';
			
			// userOrt
			isset ( $p ['userOrt'] ) ? $p ['userOrt'] = $fString100->filter ( $p ['userOrt'] ) : $p ['userOrt'] = '';
			
			// userTel1
			isset ( $p ['userTel1'] ) ? $p ['userTel1'] = $fString100->filter ( $p ['userTel1'] ) : $p ['userTel1'] = '';
			
			// userTel2
			isset ( $p ['userTel2'] ) ? $p ['userTel2'] = $fString100->filter ( $p ['userTel2'] ) : $p ['userTel2'] = '';
			
			// userAdress
			isset ( $p ['userAdress'] ) ? $p ['userAdress'] = $fString100->filter ( $p ['userAdress'] ) : $p ['userAdress'] = '';
			
			// check truckLocPLZ
			// isset($p['truckLocOrt']) ? $p['truckLocOrt'] = $fString100->filter($p['truckLocOrt']) : $p['truckLocOrt'] = '';
			if (isset ( $p ['truckLocPLZ'] )) {
				$p ['truckLocPLZ'] = $fString20->filter ( $p ['truckLocPLZ'] );
			}
			
			// Check truckLocOrt
			if (isset ( $p ['truckLocOrt'] )) {
				$p ['truckLocOrt'] = $fString100->filter ( $p ['truckLocOrt'] );
			}
			
			// Check truckLocCountry
			if (! isset ( $p ['truckLocCountry'] ) || ! isset ( $lang ['COUNTRY'] [$p ['truckLocCountry']] )) {
				$p ['truckLocCountry'] = 'DE';
			}
			
			// check userAdsLength
			if (! isset ( $p ['userAdsLength'] ) || ! in_array ( $p ['userAdsLength'], $lang ['USER_ADS_LENGTH'] )) {
				$p ['userAdsLength'] = $lang ['USER_ADS_LENGTH'] [count ( $lang ['USER_ADS_LENGTH'] ) - 1];
			}
			
			// Truck Extra
			$truckExt = '';
			if (isset ( $p ['truckExt'] )) {
				include_once ('default/models/truck/db_selTruckExt.php');
				$truckExt = db_selTruckExt ( array (
						'vextID' => $p ['truckExt'] 
				) );
			}
			$p ['truckExtDB'] = $truckExt;
		}
		return $p;
	}
}
/**
 * private function detCat($p){
 * $ret = null;
 * $carCat = $this -> carCat;
 * $bikeCat = $this -> bikeCat;
 * $truckCat = $this -> truckCat;
 *
 * $lang = $this -> lang;
 * if (isset($p['C']) && isset($p['D']) && is_array($lang)){
 * $ret = array('cat' => null, 'vType' => null);
 * $mobileCat = $p['C'];
 * $brand = $p['D'];
 * if (isset($lang['V_CAT'])){
 * $vcat = $lang['V_CAT'];
 * }
 * //echo $mobileCat.' '.$brand.' ';
 *
 * //check car categories
 * if ($carCat != null){
 * foreach ($carCat as $key => $kVal){
 * if (isset($vcat[$kVal['vcatID']])){
 * $vcatName = $vcat[$kVal['vcatID']];
 *
 * //check Cabrio/Roadster
 * if (stristr($mobileCat, 'cabrio') && stristr($vcatName, 'cabrio')){
 * $ret['cat'] = $kVal['carCatID'];
 * }
 * //check Geländewagen/pickUp
 * elseif (stristr($mobileCat, 'pickup') && stristr($vcatName, 'pickup')){
 * $ret['cat'] = $kVal['carCatID'];
 * }
 * //check Kleinwagen
 * elseif (stristr($mobileCat, 'kleinwage') && stristr($vcatName, 'kleinwage')){
 * $ret['cat'] = $kVal['carCatID'];
 * }
 * //check Kombi
 * elseif (stristr($mobileCat, 'kombi') && stristr($vcatName, 'kombi')){
 * $ret['cat'] = $kVal['carCatID'];
 * }
 * //check Limousine
 * elseif (stristr($mobileCat, 'limo') && stristr($vcatName, 'limo')){
 * $ret['cat'] = $kVal['carCatID'];
 * }
 * //check Sportwagen
 * elseif ( (stristr($mobileCat, 'sportwa') && stristr($vcatName, 'sportwa') )
 * || ( stristr($mobileCat, 'coup') && stristr($vcatName, 'coup')) ){
 * $ret['cat'] = $kVal['carCatID'];
 * }
 * //check Van/Kleinbus
 * elseif ( (stristr($mobileCat, 'van') && stristr($vcatName, 'van'))
 * || ( stristr($mobileCat, 'kleinbu') && stristr($vcatName, 'kleinbu')) ){
 * $ret['cat'] = $kVal['carCatID'];
 * }
 * }
 * }
 * if ($ret['cat'] != null){
 * $ret['vType'] = System_Properties::CAR_ABRV;
 * }
 * }
 *
 * //if no category could be found than check bike categories
 * if (($ret['cat'] == null) && ($bikeCat != null)){
 * foreach ($bikeCat as $key => $kVal){
 * if (isset($vcat[$kVal['vcatID']])){
 * $vcatName = $vcat[$kVal['vcatID']];
 * //check Chopper/Cruiser
 * if (stristr($mobileCat, 'chopper') && stristr($vcatName, 'chopper')){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check dirt bike
 * elseif (stristr($mobileCat, 'dirt') && stristr($vcatName, 'dirt')){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Enduro/Reiseenduro
 * elseif (stristr($mobileCat, 'enduro') && stristr($vcatName, 'enduro')){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Gespann/Seitenwagen
 * elseif (stristr($mobileCat, 'seiten') && stristr($vcatName, 'seiten')){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Klein/Leichtkraftrad
 * elseif (stristr($mobileCat, 'leichtkraft') && stristr($vcatName, 'leichtkraft')){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Mofa/Mokick
 * elseif ( stristr($mobileCat, 'mofa') && stristr($vcatName, 'mofa') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Moped
 * elseif ( stristr($mobileCat, 'moped') && stristr($vcatName, 'moped') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Motorrad
 * elseif ( stristr($mobileCat, 'motorrad') && stristr($vcatName, 'motorrad') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Naked Bike
 * elseif ( stristr($mobileCat, 'naked') && stristr($vcatName, 'naked') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Pocketbike
 * elseif ( stristr($mobileCat, 'pocket') && stristr($vcatName, 'pocket') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Quad
 * elseif ( stristr($mobileCat, 'quad') && stristr($vcatName, 'quad') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Rallye/Cross
 * elseif ( stristr($mobileCat, 'rally') && stristr($vcatName, 'rally') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Rennsport
 * elseif ( stristr($mobileCat, 'rennspo') && stristr($vcatName, 'rennspo') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Roller/Scooter
 * elseif ( stristr($mobileCat, 'roller') && stristr($vcatName, 'roller') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Sportler/Supersportler
 * elseif ( stristr($mobileCat, 'sportler') && stristr($vcatName, 'sportler') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Sporttourer
 * elseif ( stristr($mobileCat, 'sporttour') && stristr($vcatName, 'sporttou') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Streetfighter
 * elseif ( stristr($mobileCat, 'streetfight') && stristr($vcatName, 'streetfight') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Super Moto
 * elseif( ( stristr($mobileCat, 'super') || stristr($mobileCat, 'moto') )
 * && (stristr($vcatName, 'super') || stristr($vcatName, 'moto') )){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Tourer
 * elseif ( stristr($mobileCat, 'tourer') && stristr($vcatName, 'tourer') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * //check Trike
 * elseif ( stristr($mobileCat, 'trike') && stristr($vcatName, 'trike') ){
 * $ret['cat'] = $kVal['bikeCatID'];
 * }
 * }
 * }
 * if ($ret['cat'] != null){
 * $ret['vType'] = System_Properties::BIKE_ABRV;
 * }
 * }
 *
 * //if no category could be found than check TRUCK categories
 * if (($ret['cat'] == null) && ($truckCat != null)){
 * foreach ($bikeCat as $key => $kVal){
 * //check Chopper/Cruiser
 * if (stristr($mobileCat, 'chopper') && stristr($kVal, 'chopper')){
 * $ret['cat'] = $key;
 * }
 * }
 * if ($ret['cat'] != null){
 * $ret['vType'] = System_Properties::BIKE_ABRV;
 * }
 * }
 *
 * }
 * return $ret;
 * }
 * Assignment of letters to the particular number in the interface description
 * A = 1
 * B = 2
 * C = 3
 * D = 4
 * E = 5
 * F = 6
 * G = 7
 * H = 8
 * I = 9
 * J = 10
 * K = 11
 * L = 12
 * M = 13
 * N = 14
 * O = 15
 * P = 16
 * Q = 17
 * R = 18
 * S = 19
 * T = 20
 * U = 21
 * V = 22
 * W = 23
 * X = 24
 * Y = 25
 * Z = 26
 * AA = 27
 * AB = 28
 * AC = 29
 * AD = 30
 * AE = 31
 * AF = 32
 * AG = 33
 * AH = 34
 * AI = 35
 * AJ = 36
 * AK = 37
 * AL = 38
 * AM = 39
 * AN = 40
 * AO = 41
 * AP = 42
 * AQ = 43
 * AR = 44
 * AS = 45
 * AT = 46
 * AU = 47
 * AV = 48
 * AW = 49
 * AX = 50
 * AY = 51
 * AZ = 52
 * BA = 53
 * BB = 54
 * BC = 55
 * BD = 56
 * BE = 57
 * BF = 58
 * BG = 59
 * BH = 60
 * BI = 61
 * BJ = 62
 * BK = 63
 * BL = 64
 * BM = 65
 * BN = 66
 * BO = 67
 * BP = 68
 * BQ = 69
 * BR = 70
 * BS = 71
 * BT = 72
 * BU = 73
 * BV = 74
 * BW = 75
 * BX = 76
 * BY = 77
 * BZ = 78
 * CA = 79
 * CB = 80
 * CC = 81
 * CD = 82
 * CE = 83
 * CF = 84
 * CG = 85
 * CH = 86
 * CI = 87
 * CJ = 88
 * CK = 89
 * CL = 90
 * CM = 91
 * CN = 92
 * CO = 93
 * CP = 94
 * CQ = 95
 * CR = 96
 * CS = 97
 * CT = 98
 * CU = 99
 * CV = 100
 * CW = 101
 * CX = 102
 * CY = 103
 * CZ = 104
 */
?>