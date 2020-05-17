<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This class contains cronjob functionalities
 *********************************************************************************/
if (stristr($_SERVER['PHP_SELF'],'/t01/')){
	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/t01/app');
}else{
	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/www/app');
}
		
include_once('classes/DB.php');
include_once('modules/default/models/cronj/db_insCronjTask.php');
include_once('modules/default/models/cronj/db_selCronj.php');
include_once('modules/default/models/cronj/db_updCronjTask.php');
include_once('Zend/Json.php');

class cl_cronj_erase_exp_ads{
	
	const CRONJ_NAME = 'CEEA';
	
	private $DB;
	
	public function __construct(){
		$this -> DB = DB::getInstance();
	}
	
	public function exec_cronj($p=array()){
		/*
		$query = 'SELECT * FROM system_cronj';
		$cronj = $this->DB->execQuery(array('q' => $query));
		if ($cronj != false){
			foreach ($cronj as $key => $value){
				
			}
		}
		*/
		
		$cronjDef = db_selCronj(array('cronjName' => self::CRONJ_NAME));
		if( ($cronjDef != false) && is_array($cronjDef) && (count($cronjDef) > 0)){
			$cronjDef = $cronjDef[0];
			
			//Log cronjob start
			$cronjTaskID = db_insCronjTask(array('cronjID' => $cronjDef['cronjID']
												//, 'print' => true
												));
		
			$updcar = $this -> update_car();
			if ($updcar == null){
				$updcar = 0;
			}
			
			$updbike = $this -> update_bike();
			if ($updbike == null){
				$updbike = 0;
			}
			
			$updtruck = $this -> update_truck();
			if ($updtruck == null){
				$updtruck = 0;
			}
			
			$cronjTaskResult = array('UPDATE' => array(System_Properties::CAR_ABRV => $updcar
													, System_Properties::BIKE_ABRV => $updbike
													, System_Properties::TRUCK_ABRV => $updtruck));
			
			//Log cronjob stop
			db_updCronjTask(array(System_Properties::SQL_SET => array('cronjTaskStopTime' => time()+1
																	, 'cronjTaskFinished' => '1'
																	, 'cronjTaskResult' => Zend_Json::encode($cronjTaskResult))
								, System_Properties::SQL_WHERE => array('cronjTaskID' => $cronjTaskID)
								//, 'print' => true
								));
		}
	}
	
	private function update_car(){
		$query = '	UPDATE car 
					SET erased = 1
					WHERE timestam < (UNIX_TIMESTAMP() - ( userAdsLength * 604800 ) ) 
						AND erased = 0 ';
		return $this->DB->execQuery(array('q'=>$query));
	}
	
	private function update_bike(){
		$query = '	UPDATE bike 
					SET erased = 1
					WHERE timestam < (UNIX_TIMESTAMP() - ( userAdsLength * 604800 ) ) 
						AND erased = 0 ';
		return $this->DB->execQuery(array('q'=>$query));
	}
	
	private function update_truck(){
		$query = '	UPDATE truck 
					SET erased = 1
					WHERE timestam < (UNIX_TIMESTAMP() - ( userAdsLength * 604800 ) ) 
						AND erased = 0 ';
		return $this->DB->execQuery(array('q'=>$query));
	}
}

$cronj = new cl_cronj_erase_exp_ads();
$cronj -> exec_cronj();
?>