<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This class contains cronjob functionalities
 *********************************************************************************/
$thisfile = str_ireplace("\\","/",__FILE__);
$cronjobPath = dirname($thisfile);
$appPath = dirname($cronjobPath);
$wwwPath = dirname($appPath);

$docRoot = $wwwPath;
$includePath = ini_get('include_path').PATH_SEPARATOR.$appPath.PATH_SEPARATOR.($appPath.'/modules').PATH_SEPARATOR.($wwwPath.'/web');

ini_set('include_path', $includePath);

chdir($wwwPath.'/web');

/*
if (stristr($_SERVER['PHP_SELF'],'/t01/')){
	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/t01/app:/var/customers/webs/autotunes/t01/app/modules:/var/customers/webs/autotunes/t01/web');
	$docRoot = '/var/customers/webs/autotunes/t01/';
}else{
	ini_set('include_path', ini_get('include_path').':/var/customers/webs/autotunes/www/app:/var/customers/webs/autotunes/www/app/modules:/var/customers/webs/autotunes/www/web');
	$docRoot = '/var/customers/webs/autotunes/www/';
}
*/		
include_once('Zend/Json.php');
include_once('classes/DB.php');
include_once('modules/default/models/cronj/db_insCronjTask.php');
include_once('modules/default/models/cronj/db_selCronjTask.php');
include_once('modules/default/models/cronj/db_updCronjTask.php');
include_once('modules/default/models/cronj/db_selCronj.php');

include_once('modules/default/models/member/db_selFTPUser.php');
include_once('modules/default/models/member/db_updFTPUser.php');
include_once('modules/default/models/member/db_updFTPUserQuotatallies.php');

include_once('modules/default/models/member/db_selUser.php');

include_once('modules/default/lang/lang.php');

class cl_cronj_imp_ads{
	
	const CRONJ_NAME = 'CIA';
	
	private $DB;
	private $lang;
	private $docRoot;
	
	public function __construct($p = array()){
		$this -> DB = DB::getInstance();
		
		if (isset($p['LANG'])){
			$this -> lang = $p['LANG'];
		}
		
		if (isset($p['DOC_ROOT'])){
			$this -> docRoot = $p['DOC_ROOT'];
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
												
			//Fetch all ftp users since last cronjob run
			$ftpUsers = db_selFTPUser(array('LOGIN_ENABLED' => true
											, 'LAST_LOGIN_BEq' => $lastCronjTask['cronjTaskStartTime']
											, 'LAST_LOGIN_LEq_LOGOUT' => true
											, 'DEACTIVATED' => array('1','0')
											, 'HOMEDIR_LIKE' => $this->docRoot
											//, 'print' => true
											));
			
			if (($ftpUsers != false) && is_array($ftpUsers) && (count($ftpUsers) > 0)){
				foreach ($ftpUsers as $key => $ftpUser ){
					if (stristr($ftpUser['homedir'], 'ftp') && stristr($ftpUser['homedir'], 'upload') && ($ftpUser['username'] != 'autotunes')){
						//get original user
						$user = db_selUser(array('userEMail' => $ftpUser['username']
												//, 'print' => true
												));
						if ( ($user != false) && is_array($user) && (count($user) > 0)){
							$user = $user[0];
							
							if( ($user['datexFTPImp'] == 1) 
								&& ($user['datexFTPVType'] == System_Properties::CAR_ABRV
									|| $user['datexFTPVType'] == System_Properties::BIKE_ABRV
									|| $user['datexFTPVType'] == System_Properties::TRUCK_ABRV) ){
								//Lock user
								$lockFTPUser = db_updFTPUser(array(System_Properties::SQL_SET => array('LOGIN_ENABLED' => 'N')
																, System_Properties::SQL_WHERE => array('ID' => $ftpUser['id'])));
								if ($lockFTPUser != false){
								 	if (file_exists($ftpUser['homedir'])){
													
								 		//get all uploaded files from home directory
								 		$files = scandir($ftpUser['homedir']);
							 			$fileCSV = array();
							 			$fileZIP = array();	
							 			
										$fileBytes = 0;
								 		if (is_array($files)){
											foreach ($files as $key => $file) {
												if($file != '.' && $file != '..'){
													clearstatcache($ftpUser['homedir'].$file);
													$fileTime = filemtime($ftpUser['homedir'].$file);
													$fileBytes += filesize($ftpUser['homedir'].$file);	
													
													$fileExt = explode('.', $file);		
													$fileExt = $fileExt[count($fileExt)-1];
													if (strtolower($fileExt) == 'csv'){
														if (!isset($fileCSV[$fileTime])){
															$fileCSV[$fileTime] = $file;	
														}
													}elseif (strtolower($fileExt) == 'zip'){
														if (!isset($fileZIP[$fileTime])){
															$fileZIP[$fileTime] = $file;	
														}
													}
												}
											}
											$files = null;
											krsort($fileZIP);
											krsort($fileCSV);
											
											if (count($fileZIP) > 0){
												$files = $fileZIP;
											}elseif (count($fileCSV) > 0){
												$files = $fileCSV;
											}
											
											if ($files != null){	
												foreach($files as $key => $file){				
													//@shell_exec('cp -R '.$ftpUser['homedir'].$file.' '.$this->docRoot.$file.'_'.time());
								 					$p = array('LANG' => $lang
														, 'fileFormat' => $user['datexFormat']
														, 'vType' => $user['datexFTPVType']
														, 'FILE_NAME' => $ftpUser['homedir'].$file
														, 'USER_DATA' => $user
// 														, 'DOC_ROOT' => $this -> docRoot
														, 'CRONJ' => true // specify that import run is a cronjob
	 													);					
								 					
													$dataIntfFile = System_Properties::$DATA_INTF_FILE;
													if (isset($dataIntfFile[$p['fileFormat']]['FILE_TYPES'])){
														
														$ret = System_Properties::handleDatexImp($p);					
														if (isset($ret['PROT']) && is_array($ret['PROT'])){
															$cronjTaskResult = $ret['PROT'];
														}
													}
												}
											}
											
											if ($fileBytes > 0){
												db_updFTPUserQuotatallies(array(System_Properties::SQL_SET => array('SUB_BYTES_IN_USED'=>$fileBytes)
																			, System_Properties::SQL_WHERE => array('NAME'=>$ftpUser['username'])
																			//, 'print'=>true
																		));
											}
											
											//Now delete all files
											$files = scandir($ftpUser['homedir']);
								 			foreach ( $files as $key => $file) {
												if($file != '.' && $file != '..'){
													//Lösche alle Dateien
													if (is_file($ftpUser['homedir'].$file)){
														unlink($ftpUser['homedir'].$file);					
													}
													elseif (is_dir($ftpUser['homedir'].$file)){
														System_Properties::rec_rmdir($ftpUser['homedir'].$file);
													}						
												}
											}
										}
								 	}
								}
								
								//Unlock user
								db_updFTPUser(array(System_Properties::SQL_SET => array('LOGIN_ENABLED' => 'Y')
												, System_Properties::SQL_WHERE => array('ID' => $ftpUser['id'])));
							}
						}
					}
				}
			}
			
												
			
																
			//Log cronjob stop
			db_updCronjTask(array(System_Properties::SQL_SET => array('cronjTaskStopTime' => time()+1
																	, 'cronjTaskFinished' => '1'
																	, 'cronjTaskResult' => Zend_Json::encode($cronjTaskResult))
								, System_Properties::SQL_WHERE => array('cronjTaskID' => $cronjTaskID)
								//, 'print' => true
								));
		}														
	}
}

// chdir($docRoot);
$cronj = new cl_cronj_imp_ads(array('LANG' => $lang
									, 'DOC_ROOT' => $docRoot
								));
$cronj -> exec_cronj();
// $cronj->runtest();
?>
