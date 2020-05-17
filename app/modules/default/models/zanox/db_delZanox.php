<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select bike details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delZanox($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM zanox ';
	
	$where = false;	

	//Add zanoxID
	if (isset($p['zanoxID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['zanoxID'])){
			$query .= ' ( zanoxID IN ( "'.$db -> escape(implode('","',$p['zanoxID'])).'" )) ';
		}else{
			$query .= ' ( zanoxID = "'.$db -> escape($p['zanoxID']).'" ) ';
		}
	}
	
	if(isset($p['print'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
