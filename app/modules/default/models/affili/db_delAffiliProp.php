<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100906
 * Desc:		This function select bike details from database. It select only one advertisement
 *********************************************************************************/
include_once('classes/DB.php');

function db_delAffiliProp($p){	
	$return = false;
	$db = DB::getInstance();
	
	$query = '	DELETE FROM affiliProp ';
	
	$where = false;	

	//Add affiliID
	if (isset($p['affiliID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['affiliID'])){
			$query .= ' ( affiliID IN ( "'.$db -> escape(implode('","',$p['affiliID'])).'" )) ';
		}else{
			$query .= ' ( affiliID = "'.$db -> escape($p['affiliID']).'" ) ';
		}
	}
	
	//Add propName
	if (isset($p['propName'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['propName'])){
			$query .= ' ( propName IN ( "'.$db -> escape(implode('","',$p['propName'])).'" )) ';
			
		}else{
			$query .= ' ( propName = "'.$db -> escape($p['propName']).'" ) ';
		}
	}
	
	//Add propValue
	if (isset($p['propValue'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		
		if (is_array($p['propValue'])){
			$query .= ' ( propValue IN ( "'.$db -> escape(implode('","', $p['propValue'])).'" )) ';
		}else{
			$query .= ' ( propValue = "'.$db -> escape($p['propValue']).'" ) ';
		}
	}
	
	if(isset($p['print'])){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	return $return;
}
?>
