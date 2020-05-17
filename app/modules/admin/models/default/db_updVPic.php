<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function update picture data
 *********************************************************************************/
include_once('classes/DB.php');

function db_updVPic($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	UPDATE vPic ';
	
	$set = false;
	if(isset($p['vType'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'vType = "'.$db -> escape($p['vType']).'", ';
	}
	
	if (isset($p['vID'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'vID = "'.$db -> escape($p['vID']).'", ';
	}
	
	if (isset($p['vPicTMP'])){
		if ($set == false){
			$query .= 'SET ';
			$set = true;
		}
		$query .= 'vPicTMP = "'.$db -> escape($p['vPicTMP']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	if (isset($p['vPicID'])){
		$query .= ' WHERE ';
		if (is_array($p['vPicID'])){
			$query .= '( vPicID IN ( "'.implode('","',$db -> escape($p['vPicID'])).'" ) )';
			
		}else{
			$query .= '( vPicID = "'.$db -> escape($p['vPicID']).'" )';
		}
		
		$return = $db->execQuery(array('q'=>$query));	
	}

	return $return;
}
?>
