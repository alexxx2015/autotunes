<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function delete a picture from database
 *********************************************************************************/
include_once('classes/DB.php');

function db_delVPic($p=null){
	$return = false;
	if (is_array($p)){
		$db = DB::getInstance();
		
		$query = '	DELETE FROM vPic
					WHERE (vPicID > 0) ' ;
		
		if (isset($p['vPicID'])){
			if (is_array($p['vPicID'])){
				$query .= 'AND (vPicID IN ("'.implode('","', $db -> escape($p['vPicID'])).'"))';
			}else{
				$query .= 'AND (vPicID = '.$db -> escape($p['vPicID']).')';
			}
		}
		
		if (isset($p['vType'])){
			$query .= 'AND (vType = '.$db -> escape($p['vType']).')';
		}
		
		if (isset($p['vID'])){
			$query .= 'AND (vID = '.$db -> escape($p['vID']).')';
		}
		
		if (isset($p['vPicTMP'])){
			$query .= 'AND (vPicTMP = "'.$db -> escape($p['vPicTMP']).'")';
		}
		
		if(isset($p['print']) && ($p['print'] == true)){
			echo $query;
		}
		
		$return = $db->execQuery(array('q'=>$query));		
// 		$return = true;
	}	

	return $return;
}
?>
