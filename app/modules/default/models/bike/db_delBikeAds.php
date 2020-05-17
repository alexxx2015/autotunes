<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select bike details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delBikeAds($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM bike ';
	
	$where = false;	
	
	//Add bikeID
	if (isset($p['bikeID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['bikeID'])){
			$query .= ' ( bikeID IN ( "'.$db -> escape(implode('","',$p['bikeID'])).'" )) ';
			
		}else{
			$query .= ' ( bikeID = "'.$db -> escape($p['bikeID']).'" ) ';
		}
	}
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
