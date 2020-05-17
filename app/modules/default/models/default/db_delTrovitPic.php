<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');

function db_delTrovitPic($p=null){
	$return = false;
	if (is_array($p)){
		$db = DB::getInstance();
		
		$query = '	DELETE FROM trovitPic
					WHERE (trovitPicID > 0) ' ;
		
		if (isset($p['trovitPicID'])){
		if (is_array($p['trovitPicID'])){
		$query .= 'AND (trovitPicID IN ("'.implode('","', $db -> escape($p['trovitPicID'])).'"))';
					}else{
		$query .= 'AND (trovitPicID = '.$db -> escape($p['trovitPicID']).')';
					}
		}
		
		if (isset($p['trovitID'])){
			if (is_array($p['trovitID'])){
				$query .= 'AND (trovitID IN ("'.implode('","', $db -> escape($p['trovitID'])).'"))';
			}else{
				$query .= 'AND (trovitID = '.$db -> escape($p['trovitID']).')';
			}
		}
		
		$return = $db->execQuery(array('q'=>$query));
	}	

	return $return;
}
?>
