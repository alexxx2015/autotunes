<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This class contains system properties
 *********************************************************************************/

include_once('classes/System_Authority.php');
class System_Properties{
	//This constant specify the size of an uploaded image in bytes
	const PIC_FILE_SIZE = 1024000;//512000;
	
	//This constant contains the allowable file extensions
	public static $PIC_EXT = array('jpeg', 'jpg', 'png');//, 'gif');
	public static $STD_PIC_EXT = 'jpeg';
	
	//System information E-Mail address
	public static $SYS_INFO_EMAIL = 'info@autotunes.de';
	
	public static $FB_ADMINS = array('100000278288807');
	
	//This constant contains the number of advertisments for a search result page
	const NUM_ADS = 20;
	
	//This constant specify the size of a picture in a vehicle advertisment
	const PIC_SIZE_W = 640;
	const PIC_SIZE_H = 480;
	
	const CAR_ABRV = 'c'; //car
	const TRUCK_ABRV = 't'; //truck
	const BIKE_ABRV = 'b'; //bike
	const IMP_ABRV = 'i'; //impressum
	
	// Path information for default module
	const PIC_PATH = '/pic';
	const PIC_TMP_PATH = '/picTMP';
	const CSS_PATH = '/css';
	const JS_PATH = '/js';
	const SYS_PIC_PATH = '/sysPic';
	const SYS_FTP_PATH = '../app/ftp'; 
	
	
	const MAX_PHOTO_GALLERY = 3;
	
	const NO_PIC_PATH = '/sysPic/noImg.png';
	
	CONST ADMIN_NS = 'ADMIN_NS';
	const ADS_RECOM_NS = 'ADS_RECOM_NS';
	const USER_NS = 'USER_NS';
	const CAR_ADS_NS = 'CAR_ADS_NS';
	const BIKE_ADS_NS = 'BIKE_ADS_NS';
	const TRUCK_ADS_NS = 'TRUCK_ADS_NS';
	const DEALER_NS = 'DEALER_NS';
	
	//Path information for admin module
	const ADMIN_CSS_PATH = '/css/admin';
	const ADMIN_JS_PATH = '/js/admin';
	const ADMIN_MOD_PATH = 'admin';
	
	//This constant specify the max result search set which will be delivered via ajax request
	const MAX_RESULT_SET = 99;
	
	
	//Admin access data
	const ADMIN_EMAIL = 'admin';
	const ADMIN_PW = '21232f297a57a5a743894a0e4a801fc3';
	
	//SQL abbreviations
	const SQL_WHERE = 'WHERE';
	const SQL_SET = 'SET';
	const SQL_ORDERBY = 'ORDERBY';
	const SQL_LIMIT = 'LIMIT';
	const SQL_GROUP = 'GROUP BY';
	
	/*constants for data exchange*/
	const DATEX_IMP = 'IMP';
	const DATEX_EXP = 'EXP';
	
	//difference between two sequenced ads recommendations
	const DELTA_ADS_RECOM = 300;//seconds
	
	
	//FTP Data
	const FTP_UID = '10000';
	const FTP_GID = '10000';
	
	const FTP_USER = 'autotunes';
	const FTP_GROUP = 'ftpautotunes';
	
	//Available customer diskspace in MB
	const FTP_DISKSPACE = 100;
	
	
	//System data interfaces
	public static $DATA_INTF_FILE = array(	//autoscout24
											'AS24' => array('FILE_TYPES' => array('csv', 'zip')
												, 'DATA_INTF_HANDLER' => array('CL_AS24')
												)
											
											//mobile.de
											, 'MOBILE' => array('FILE_TYPES' => array('csv', 'zip')
															, 'DATA_INTF_HANDLER' => array('CL_MOBILE')
													)
											
											//trovit.com
											, 'TROVITCAR' => array('FILE_TYPES' => array('xml', 'zip')
																	, 'DATA_INTF_HANDLER' => array('CL_TROVITCAR')
													)
										);
	
	const MAX_DATA_INTF_FILE_SIZE = 10485760000; // max file size of an uploaded data interface file
	
	/**
	 * This function return the actual IP adress of the user
	 */
	public static function getIP(){
		return isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;
	}
	
	//Check user authority
	public static function checkAuthority($p){
		$return = false;
		if (isset($p['userAuth']) && is_array($p['userAuth'])){
			
			$userAuthority = array();			
			foreach($p['userAuth'] as $ua){
				array_push($userAuthority, $ua['authorityName']);
			}
								
			$desiredAuthority = $p['desAuth'];							
			if (is_array($userAuthority) && 
				(in_array($desiredAuthority, $userAuthority) || in_array(System_Authority::ROOT_ACCESS, $userAuthority))){
				$return = true;
			}
		}
		
		return $return;
	}
	
	/**
	 * Central funciton for handling 
	 */
	public static function handleDatexImp($p){
		$fileCntrl = null;
		if(isset($p['FILE_CNTRL'])){
			$fileCntrl = $p['FILE_CNTRL'];
		}
		
		$fileName = null;
		if (isset($p['FILE_NAME'])){ 
			$fileName = $p['FILE_NAME'];
		}elseif ($fileCntrl != null){
			$fileName = $fileCntrl -> getFileName();
			$p['FILE_NAME'] = $fileName;
		}					
		
		$reqParam = $p;
		
		if (($reqParam != null) && ($fileName != null)){
			$dataIntfFile = System_Properties::$DATA_INTF_FILE;
			if (isset($dataIntfFile[$reqParam['fileFormat']]['DATA_INTF_HANDLER'][0])){
				$dataIntfHandlerName = $dataIntfFile[$reqParam['fileFormat']]['DATA_INTF_HANDLER'][0];
				$dataIntfHandler = 'classes/'.$dataIntfHandlerName.'.php';
				include_once($dataIntfHandler);
				try{
					$handler = new $dataIntfHandlerName($p);
					$p = $handler -> handleDatexImp($p);
				}catch(Exception $e){}
			}
		}
		return $p;
	}
	
	/**
	 *	Central function for exporting data
	 */
	public static function handleDatexExp($p){
		//set language
		$lang = null;
		if (is_array($p) && isset($p['LANG'])){
			$lang = $p['LANG'];
		}
			
		if (isset($p['fileFormat']) && array_key_exists($p['fileFormat'], System_Properties::$DATA_INTF_FILE)){
			$dataIntfFile = System_Properties::$DATA_INTF_FILE[$p['fileFormat']];
			if (isset($dataIntfFile['DATA_INTF_HANDLER']) && (count($dataIntfFile['DATA_INTF_HANDLER']) > 0)){
				$dataIntfHandlerName = $dataIntfFile['DATA_INTF_HANDLER'][0];
				$dataIntfHandler = '../app/classes/'.$dataIntfHandlerName.'.php';
				if (file_exists($dataIntfHandler)){
					include_once($dataIntfHandler);
					$handler = new $dataIntfHandlerName($p);
					$p = $handler -> handleDatexExp($p);					
				}
			}
		}else{
			$p['ERROR'] = $lang['ERR_46'];
		}
		
		return $p;
	}
	
	/** rec_rmdir - loesche ein Verzeichnis rekursiv
	 *   0  - alles ok
	 *   -1 - kein Verzeichnis
	 *   -2 - Fehler beim Loeschen
	 *   -3 - Ein Eintrag eines Verzeichnisses war keine Datei und kein Verzeichnis und
	 *        kein Link
	 */
	public static function rec_rmdir ($path){
		// schau' nach, ob das ueberhaupt ein Verzeichnis ist
	    if (!is_dir ($path)) {
	        return -1;
	    }
	    // oeffne das Verzeichnis
	    $dir = @opendir ($path);
	    
	    // Fehler?
	    if (!$dir) {
	        return -2;
	    }
	    
	    // gehe durch das Verzeichnis
	    while (($entry = @readdir($dir)) !== false) {
	        // wenn der Eintrag das aktuelle Verzeichnis oder das Elternverzeichnis
	        // ist, ignoriere es
	        if ($entry == '.' || $entry == '..') continue;
	        // wenn der Eintrag ein Verzeichnis ist, dann 
	        if (is_dir ($path.'/'.$entry)) {
	            // rufe mich selbst auf
	            $res = System_Properties::rec_rmdir ($path.'/'.$entry);
	            // wenn ein Fehler aufgetreten ist
	            if ($res == -1) { // dies duerfte gar nicht passieren
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // normalen Fehler melden
	            } else if ($res == -2) { // Fehler?
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // Fehler weitergeben
	            } else if ($res == -3) { // nicht unterstuetzer Dateityp?
	                @closedir ($dir); // Verzeichnis schliessen
	                return -3; // Fehler weitergeben
	            } else if ($res != 0) { // das duerfe auch nicht passieren...
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // Fehler zurueck
	            }
	        } else if (is_file ($path.'/'.$entry) || is_link ($path.'/'.$entry)) {
	            // ansonsten loesche diese Datei / diesen Link
	            $res = @unlink ($path.'/'.$entry);
	            // Fehler?
	            if (!$res) {
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // melde ihn
	            }
	        } else {
	            // ein nicht unterstuetzer Dateityp
	            @closedir ($dir); // Verzeichnis schliessen
	            return -3; // tut mir schrecklich leid...
	        }
	    }
	    
	    // schliesse nun das Verzeichnis
	    @closedir ($dir);
	    
	    // versuche nun, das Verzeichnis zu loeschen
	    $res = @rmdir ($path);
	    
	    // gab's einen Fehler?
	    if (!$res) {
	        return -2; // melde ihn
	    }
	    
	    // alles ok
	    return 0;
	}
	
	public static function generatePW($p = null){
		$length = 8;
		
		if (is_array($p) && isset($p['PW_LENGTH'])){
			$length = $p['PW_LENGTH'];
		}
		$dummy = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'), array('#','&','@','$','_','%','?','+'));
 		 
		mt_srand((double)microtime()*1000000);
		for ($i = 1; $i <= (count($dummy)*2); $i++){
			$swap = mt_rand(0,count($dummy)-1);
			$tmp = $dummy[$swap];
			$dummy[$swap] = $dummy[0];
			$dummy[0] = $tmp;
		}
		 
		return substr(implode('',$dummy),0,$length);
	}
	
	/*This function send an Email
	 * */
	public function sendEmail($p){//$sender,$empfaenger,$betreff,$message){
		include_once('default/views/filters/FilterValidEmail.php');
		$emailFilter = new FilterValidEmail();
		
		$sender = null;
		$receiver = null;
		$subject = null;
		$message = null;
		$return = false;
		
		if(!is_array($p)){}
		elseif (!isset($p['EMAIL_SENDER']) || ($emailFilter -> filter($p['EMAIL_SENDER']) == false)){}
		elseif (!isset($p['EMAIL_RECEIVER']) || ($emailFilter -> filter($p['EMAIL_RECEIVER']) == false)){}
		//elseif (!isset($p['EMAIL_SUBJECT'])){}
		elseif (!isset($p['EMAIL_MESSAGE'])){}
		elseif (!isset($p['EMAIL_FROM'])){}
		else{
			$emailSender = $p['EMAIL_SENDER'];
			$emailReceiver = $p['EMAIL_RECEIVER'];
			$emailMessage = $p['EMAIL_MESSAGE'];
			$emailSubject = $p['EMAIL_SUBJECT'];
			$emailFrom =$p['EMAIL_FROM'];
			$emailReplyto = $p['EMAIL_FROM'];
			if (isset($p['EMAIL_REPLYTO'])){
				$emailReplyto = $p['EMAIL_REPLYTO'];
			}
			
			$emailHeader="MIME-Version: 1.0\n";
		    //$emailHeader.="Content-type: text/html; charset=iso-8859-1\n";
		    $emailHeader.="Content-type: text/html; charset=UTF-8;\n";
		    $emailHeader.="From:".$emailFrom."<".$emailSender.">;\n";
		    $emailHeader .= "Reply-To: ".$emailReplyto.";\n";    
		    
		    $return = @mail($emailReceiver,$emailSubject,$emailMessage,$emailHeader); 
		}
		return $return;
	}
	
}
?>