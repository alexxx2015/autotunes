<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101222
 * Desc:		This function update an zanox database record
 *********************************************************************************/
include_once('classes/DB.php');

function db_updZanox($p=null){
	$return = false;
	$db = DB::getInstance();
	
	$query = '	UPDATE zanox SET  ';
	
	//zupid
	if(isset($p[System_Properties::SQL_SET]['zupid'])){		
		$query .= 'zupid = "'.$db -> escape($p[System_Properties::SQL_SET]['zupid']).'", ';
	}
	
	//name
	if(isset($p[System_Properties::SQL_SET]['name'])){
		$query .= 'name = "'.$db -> escape($p[System_Properties::SQL_SET]['name']).'", ';
	}
	
	//program
	if(isset($p[System_Properties::SQL_SET]['program'])){
		$query .= 'program = "'.$db -> escape($p[System_Properties::SQL_SET]['program']).'", ';
	}
	
	//number
	if (isset($p[System_Properties::SQL_SET]['number'])){
		$query .= 'number = "'.$db -> escape($p[System_Properties::SQL_SET]['number']).'", ';
	}
	
	//description
	if (isset($p[System_Properties::SQL_SET]['description'])){
		$query .= 'description = "'.$db -> escape($p[System_Properties::SQL_SET]['description']).'", ';
	}
	
	//longDescription
	if (isset($p[System_Properties::SQL_SET]['longDescription'])){
		$query .= 'longDescription = "'.$db -> escape($p[System_Properties::SQL_SET]['longDescription']).'", ';
	}
	
	//manufacturer
	if (isset($p[System_Properties::SQL_SET]['manufacturer'])){
		$query .= 'manufacturer = "'.$db -> escape($p[System_Properties::SQL_SET]['manufacturer']).'", ';
	}
	
	//price
	if (isset($p[System_Properties::SQL_SET]['price'])){
		$query .= 'price = "'.$db -> escape($p[System_Properties::SQL_SET]['price']).'", ';
	}
	
	//terms
	if (isset($p[System_Properties::SQL_SET]['terms'])){
		$query .= 'terms = "'.$db -> escape($p[System_Properties::SQL_SET]['terms']).'", ';
	}
	
	//shippingCosts
	if (isset($p[System_Properties::SQL_SET]['shippingCosts'])){
		$query .= 'shippingCosts = "'.$db -> escape($p[System_Properties::SQL_SET]['shippingCosts']).'", ';
	}
	
	//lastModified
	if (isset($p[System_Properties::SQL_SET]['lastModified'])){
		$query .= 'lastModified = "'.$db -> escape($p[System_Properties::SQL_SET]['lastModified']).'", ';
	}
	
	//largeImg
	if (isset($p[System_Properties::SQL_SET]['largeImg'])){
		$query .= 'largeImg = "'.$db -> escape($p[System_Properties::SQL_SET]['largeImg']).'", ';
	}
	
	//deliveryTime
	if (isset($p[System_Properties::SQL_SET]['deliveryTime'])){
		$query .= 'deliveryTime = "'.$db -> escape($p[System_Properties::SQL_SET]['deliveryTime']).'", ';
	}
	
	//currencyCode
	if (isset($p[System_Properties::SQL_SET]['currencyCode'])){
		$query .= 'currencyCode = "'.$db -> escape($p[System_Properties::SQL_SET]['currencyCode']).'", ';
	}
	
	//extra1
	if (isset($p[System_Properties::SQL_SET]['extra1'])){
		$query .= 'extra1 = "'.$db -> escape($p[System_Properties::SQL_SET]['extra1']).'", ';
	}
	
	//extra2
	if (isset($p[System_Properties::SQL_SET]['extra2'])){
		$query .= 'extra2 = "'.$db -> escape($p[System_Properties::SQL_SET]['extra2']).'", ';
	}
	
	//extra3
	if (isset($p[System_Properties::SQL_SET]['extra3'])){
		$query .= 'extra3 = "'.$db -> escape($p[System_Properties::SQL_SET]['extra3']).'", ';
	}
	
	//merchantCategory
	if (isset($p[System_Properties::SQL_SET]['merchantCategory'])){
		$query .= 'merchantCategory = "'.$db -> escape($p[System_Properties::SQL_SET]['merchantCategory']).'", ';
	}
	
	//deepLink
	if (isset($p[System_Properties::SQL_SET]['deepLink'])){
		$query .= 'deepLink = "'.$db -> escape($p[System_Properties::SQL_SET]['deepLink']).'", ';
	}
	
	//programName
	if (isset($p[System_Properties::SQL_SET]['programName'])){
		$query .= 'programName = "'.$db -> escape($p[System_Properties::SQL_SET]['programName']).'", ';
	}
	
	//Car cyl
	if (isset($p[System_Properties::SQL_SET]['carCyl'])){
		$query .= 'carCyl = "'.$db -> escape($p[System_Properties::SQL_SET]['carCyl']).'", ';
	}
	
	//timestam
	if (isset($p[System_Properties::SQL_SET]['timestam'])){
		$query .= 'timestam = UNIX_TIMESTAMP(), ';
	}
	
	//new
	if (isset($p[System_Properties::SQL_SET]['new'])){
		$query .= 'new = "'.$db -> escape($p[System_Properties::SQL_SET]['new']).'", ';
	}
	
	//refData
	if (isset($p[System_Properties::SQL_SET]['refData'])){
		$query .= 'refData = "'.$db -> escape($p[System_Properties::SQL_SET]['refData']).'", ';
	}
	
	$query = substr($query, 0, -2);
	
	$where = false;
	if (isset($p[System_Properties::SQL_WHERE]['zanoxID'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (zanoxID = "'.$p[System_Properties::SQL_WHERE]['zanoxID'].'") ';
	}
	
	if (isset($p[System_Properties::SQL_WHERE]['zupid'])){
		if ($where == false){
			$query .= ' WHERE ';
			$where = true;
		}else{
			$query .= ' AND ';
		}
		$query .= ' (zupid = "'.$p[System_Properties::SQL_WHERE]['zupid'].'") ';
	}
	
	if(isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	
	$return = $db->execQuery(array('q'=>$query));
	
	return $return;
}
?>
