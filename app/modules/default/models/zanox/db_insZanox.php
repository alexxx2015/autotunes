<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insZanox($p=null){
	$return = false;
	$db = DB::getInstance();
	
	//perform escape
	$p = $db -> escape($p);
	
	$query1 = 'timestam, new';
	$query2 = 'UNIX_TIMESTAMP(), 1';
	
	//zupid
	if(isset($p['zupid']) && ($p['zupid'] != null) && ($p['zupid'] != '')){
	  $query1 .= ', zupid';
	  $query2 .= ', "'.$db -> escape($p['zupid']).'"';
	}
	
	//name
	if(isset($p['name']) && ($p['name'] != null) && ($p['name'] != '')){
	  $query1 .= ', name';
	  $query2 .= ', "'.$db -> escape($p['name']).'"';
	}
	
	//program
	if(isset($p['program']) && ($p['program'] != null) && ($p['program'] != '')){
	  $query1 .= ', program';
	  $query2 .= ', "'.$db -> escape($p['program']).'"';
	}
	
	//number
	if(isset($p['number']) && ($p['number'] != null) && ($p['number'] != '')){
	  $query1 .= ', number';
	  $query2 .= ', "'.$db -> escape($p['number']).'"';
	}
	
	//description
	if(isset($p['description']) && ($p['description'] != null) && ($p['description'] != '')){
	  $query1 .= ', description';
	  $query2 .= ', "'.$db -> escape($p['description']).'"';
	}
	
	//longDescription
	if(isset($p['longDescription']) && ($p['longDescription'] != null) && ($p['longDescription'] != '')){
	  $query1 .= ', longDescription';
	  $query2 .= ', "'.$db -> escape($p['longDescription']).'"';
	}
	
	//manufacturer
	if(isset($p['manufacturer']) && ($p['manufacturer'] != null) && ($p['manufacturer'] != '')){
	  $query1 .= ', manufacturer';
	  $query2 .= ', "'.$db -> escape($p['manufacturer']).'"';
	}
	
	//price
	if(isset($p['price']) && ($p['price'] != null) && ($p['price'] != '')){
	  $query1 .= ', price';
	  $query2 .= ', "'.$db -> escape($p['price']).'"';
	}
	
	//terms
	if(isset($p['terms']) && ($p['terms'] != null) && ($p['terms'] != '')){
	  $query1 .= ', terms';
	  $query2 .= ', "'.$db -> escape($p['terms']).'"';
	}
	
	//shippingCosts
	if(isset($p['shippingCosts']) && ($p['shippingCosts'] != null) && ($p['shippingCosts'] != '')){
	  $query1 .= ', shippingCosts';
	  $query2 .= ', "'.$db -> escape($p['shippingCosts']).'"';
	}
	
	//lastModified
	if(isset($p['lastModified']) && ($p['lastModified'] != null) && ($p['lastModified'] != '')){
	  $query1 .= ', lastModified';
	  $query2 .= ', "'.$db -> escape($p['lastModified']).'"';
	}
	
	//largeImg
	if(isset($p['largeImg']) && ($p['largeImg'] != null) && ($p['largeImg'] != '')){
	  $query1 .= ', largeImg';
	  $query2 .= ', "'.$db -> escape($p['largeImg']).'"';
	}
	
	//deliveryTime
	if(isset($p['deliveryTime']) && ($p['deliveryTime'] != null) && ($p['deliveryTime'] != '')){
	  $query1 .= ', deliveryTime';
	  $query2 .= ', "'.$db -> escape($p['deliveryTime']).'"';
	}
	
	//currencyTime
	if(isset($p['currencyTime']) && ($p['currencyTime'] != null) && ($p['currencyTime'] != '')){
	  $query1 .= ', currencyTime';
	  $query2 .= ', "'.$db -> escape($p['currencyTime']).'"';
	}
	
	//extra1
	if(isset($p['extra1']) && ($p['extra1'] != null) && ($p['extra1'] != '')){
	  $query1 .= ', extra1';
	  $query2 .= ', "'.$db -> escape($p['extra1']).'"';
	}
	
	//extra2
	if(isset($p['extra2']) && ($p['extra2'] != null) && ($p['extra2'] != '')){
	  $query1 .= ', extra2';
	  $query2 .= ', "'.$db -> escape($p['extra2']).'"';
	}
	
	//extra3
	if(isset($p['extra3']) && ($p['extra3'] != null) && ($p['extra3'] != '')){
	  $query1 .= ', extra3';
	  $query2 .= ', "'.$db -> escape($p['extra3']).'"';
	}
	
	//merchantCategory
	if(isset($p['merchantCategory']) && ($p['merchantCategory'] != null) && ($p['merchantCategory'] != '')){
	  $query1 .= ', merchantCategory';
	  $query2 .= ', "'.$db -> escape($p['merchantCategory']).'"';
	}
	
	//deepLink
	if(isset($p['deepLink']) && ($p['deepLink'] != null) && ($p['deepLink'] != '')){
	  $query1 .= ', deepLink';
	  $query2 .= ', "'.$db -> escape($p['deepLink']).'"';
	}
	
	//programName
	if(isset($p['programName']) && ($p['programName'] != null) && ($p['programName'] != '')){
	  $query1 .= ', programName';
	  $query2 .= ', "'.$db -> escape($p['programName']).'"';
	}
	
	//Build complete query
	$query = '	INSERT INTO zanox( '.$query1.' ) VALUES ( '.$query2.' )';

	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
