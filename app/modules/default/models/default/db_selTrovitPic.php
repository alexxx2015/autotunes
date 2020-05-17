<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function select all picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_selTrovitPic($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	SELECT *
				FROM trovitPic as tp1 
				WHERE (	tp1.vType = "'.System_Properties::CAR_ABRV.'"
						or tp1.vType = "'.System_Properties::BIKE_ABRV.'"
						or tp1.vType = "'.System_Properties::TRUCK_ABRV.'") ';

	if(isset($p['trovitPicID'])){
		$query .= 'AND ';
		if (is_array($p['trvoitPicID'])){
			$query .= ' (tp1.trvoitPicID IN ( "'.implode('","',$db -> escape($p['trvoitPicID'])).'" ) )';
		}else{
			$query .= ' (tp1.trvoitPicID = '.$db -> escape($p['trvoitPicID']).') ';
		}
	}
	
	if(isset($p['notTrovitPicID'])){
		$query .= 'AND ';
		if (is_array($p['notTrovitPicID'])){
			$query .= ' (tp1.trovitPicID NOT IN ( "'.implode('","',$db -> escape($p['notTrovitPicID'])).'" ) )';
		}else{
			$query .= ' NOT(tp1.trovitPicID = '.$db -> escape($p['notTrovitPicID']).') ';
		}
	}
	
	if(isset($p['trovitID'])){
		$query .= 'AND ';
		if (is_array($p['trovitID'])){
			$query .= ' (tp1.trovitID IN ( "'.implode('","',$db -> escape($p['trovitID'])).'" ) )';
		}else{
			$query .= ' (tp1.trovitID = '.$db -> escape($p['trovitID']).') ';
		}
	}
		
	if (isset($p['picture_url'])){
		$query .= 'AND (tp1.picture_url = "'.$db -> escape($p['picture_url']).'") ';
	}
	
	if (isset($p['picture_title'])){
		$query .= 'AND (tp1.picture_title = "'.$db -> escape($p['picture_title']).'") ';
	}
	
	$return = $db->execQuery(array('q'=>$query));	

	return $return;
}
?>
