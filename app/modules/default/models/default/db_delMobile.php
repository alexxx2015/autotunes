<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select car details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delMobile($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM mobile ';
	
	$where = false;	
	
	//Add mobileID
	if (isset($p['mobileID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['mobileID'])){
			$query .= ' ( mobileID IN ( "'.$db -> escape(implode('","',$p['mobileID'])).'" )) ';
			
		}else{
			$query .= ' ( mobileID = "'.$db -> escape($p['mobileID']).'" ) ';
		}
	}
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
