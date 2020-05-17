<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insTrovit($p=null){
	$return = false;
	$db = DB::getInstance();
	
	//perform escape
	$p = $db -> escape($p);
	
	$query1 = 'timestam';
	$query2 = 'UNIX_TIMESTAMP()';
	
	//id
	if(isset($p['id']) && ($p['id'] != null) && ($p['id'] != '')){
	  $query1 .= ', id';
	  $query2 .= ', "'.$db -> escape($p['id']).'"';
	}
	
	//url
	if(isset($p['url']) && ($p['url'] != null) && ($p['url'] != '')){
	  $query1 .= ', url';
	  $query2 .= ', "'.$db -> escape($p['url']).'"';
	}
	
	//title
	if(isset($p['title']) && ($p['title'] != null) && ($p['title'] != '')){
	  $query1 .= ', title';
	  $query2 .= ', "'.$db -> escape($p['title']).'"';
	}
	
	//content
	if(isset($p['content']) && ($p['content'] != null) && ($p['content'] != '')){
	  $query1 .= ', content';
	  $query2 .= ', "'.$db -> escape($p['content']).'"';
	}
	
	//price
	if(isset($p['price']) && ($p['price'] != null) && ($p['price'] != '')){
	  $query1 .= ', price';
	  $query2 .= ', "'.$db -> escape($p['price']).'"';
	}
	
	//car_type
	if(isset($p['car_type']) && ($p['car_type'] != null) && ($p['car_type'] != '')){
	  $query1 .= ', car_type';
	  $query2 .= ', "'.$db -> escape($p['car_type']).'"';
	}
	
	//dealer
	if(isset($p['dealer']) && ($p['dealer'] != null) && ($p['dealer'] != '')){
	  $query1 .= ', dealer';
	  $query2 .= ', "'.$db -> escape($p['dealer']).'"';
	}
	
	//neighborhood
	if(isset($p['neighborhood']) && ($p['neighborhood'] != null) && ($p['neighborhood'] != '')){
	  $query1 .= ', neighborhood';
	  $query2 .= ', "'.$db -> escape($p['neighborhood']).'"';
	}
	
	//city_area
	if(isset($p['city_area']) && ($p['city_area'] != null) && ($p['city_area'] != '')){
	  $query1 .= ', city_area';
	  $query2 .= ', "'.$db -> escape($p['city_area']).'"';
	}
	
	//city
	if(isset($p['city']) && ($p['city'] != null) && ($p['city'] != '')){
	  $query1 .= ', city';
	  $query2 .= ', "'.$db -> escape($p['city']).'"';
	}
	
	//region
	if(isset($p['region']) && ($p['region'] != null) && ($p['region'] != '')){
	  $query1 .= ', region';
	  $query2 .= ', "'.$db -> escape($p['region']).'"';
	}
	
	//postcode
	if(isset($p['postcode']) && ($p['postcode'] != null) && ($p['postcode'] != '')){
	  $query1 .= ', postcode';
	  $query2 .= ', "'.$db -> escape($p['postcode']).'"';
	}
	
	//vin_database
	if(isset($p['vin_database']) && ($p['vin_database'] != null) && ($p['vin_database'] != '')){
	  $query1 .= ', vin_database';
	  $query2 .= ', "'.$db -> escape($p['vin_database']).'"';
	}
	
	//make
	if(isset($p['make']) && ($p['make'] != null) && ($p['make'] != '')){
	  $query1 .= ', make';
	  $query2 .= ', "'.$db -> escape($p['make']).'"';
	}
	
	//model
	if(isset($p['model']) && ($p['model'] != null) && ($p['model'] != '')){
	  $query1 .= ', model';
	  $query2 .= ', "'.$db -> escape($p['model']).'"';
	}
	
	//color
	if(isset($p['color']) && ($p['color'] != null) && ($p['color'] != '')){
	  $query1 .= ', color';
	  $query2 .= ', "'.$db -> escape($p['color']).'"';
	}
	
	//year
	if(isset($p['year']) && ($p['year'] != null) && ($p['year'] != '')){
	  $query1 .= ', year';
	  $query2 .= ', "'.$db -> escape($p['year']).'"';
	}
	
	//mileage
	if(isset($p['mileage']) && ($p['mileage'] != null) && ($p['mileage'] != '')){
	  $query1 .= ', mileage';
	  $query2 .= ', "'.$db -> escape($p['mileage']).'"';
	}
	
	//doors
	if(isset($p['doors']) && ($p['doors'] != null) && ($p['doors'] != '')){
	  $query1 .= ', doors';
	  $query2 .= ', "'.$db -> escape($p['doors']).'"';
	}
	
	//seats
	if(isset($p['seats']) && ($p['seats'] != null) && ($p['seats'] != '')){
	  $query1 .= ', seats';
	  $query2 .= ', "'.$db -> escape($p['seats']).'"';
	}
	
	//warranty
	if(isset($p['warranty']) && ($p['warranty'] != null) && ($p['warranty'] != '')){
	  $query1 .= ', warranty';
	  $query2 .= ', "'.$db -> escape($p['warranty']).'"';
	}
	
	//engine_size
	if(isset($p['engine_size']) && ($p['engine_size'] != null) && ($p['engine_size'] != '')){
	  $query1 .= ', engine_size';
	  $query2 .= ', "'.$db -> escape($p['engine_size']).'"';
	}
	
	//cylinders
	if(isset($p['cylinders']) && ($p['cylinders'] != null) && ($p['cylinders'] != '')){
	  $query1 .= ', cylinders';
	  $query2 .= ', "'.$db -> escape($p['cylinders']).'"';
	}
	
	//fuel
	if(isset($p['fuel']) && ($p['fuel'] != null) && ($p['fuel'] != '')){
	  $query1 .= ', fuel';
	  $query2 .= ', "'.$db -> escape($p['fuel']).'"';
	}
	
	//transmission
	if(isset($p['transmission']) && ($p['transmission'] != null) && ($p['transmission'] != '')){
	  $query1 .= ', transmission';
	  $query2 .= ', "'.$db -> escape($p['transmission']).'"';
	}
	
	//gears
	if(isset($p['gears']) && ($p['gears'] != null) && ($p['gears'] != '')){
	  $query1 .= ', gears';
	  $query2 .= ', "'.$db -> escape($p['gears']).'"';
	}
	
	//power
	if(isset($p['power']) && ($p['power'] != null) && ($p['power'] != '')){
	  $query1 .= ', power';
	  $query2 .= ', "'.$db -> escape($p['power']).'"';
	}
	
	//date
	if(isset($p['date']) && ($p['date'] != null) && ($p['date'] != '')){
	  $query1 .= ', date';
	  $query2 .= ', "'.$db -> escape($p['date']).'"';
	}
	
	//expiration_date
	if(isset($p['expiration_date']) && ($p['expiration_date'] != null) && ($p['expiration_date'] != '')){
	  $query1 .= ', expiration_date';
	  $query2 .= ', "'.$db -> escape($p['expiration_date']).'"';
	}
	
	//is_new
	if(isset($p['is_new']) && ($p['is_new'] != null) && ($p['is_new'] != '')){
	  $query1 .= ', is_new';
	  $query2 .= ', "'.$db -> escape($p['is_new']).'"';
	}
	
	//trovitNew
	if(isset($p['trovitNew']) && ($p['trovitNew'] != null) && ($p['trovitNew'] != '')){
	  $query1 .= ', trovitNew';
	  $query2 .= ', "'.$db -> escape($p['trovitNew']).'"';
	}
	
	//userID
	if(isset($p['userID']) && ($p['userID'] != null) && ($p['userID'] != '')){
	  $query1 .= ', userID';
	  $query2 .= ', "'.$db -> escape($p['userID']).'"';
	}
	
	//vType
	if(isset($p['vType']) && ($p['vType'] != null) && ($p['vType'] != '')){
	  $query1 .= ', vType';
	  $query2 .= ', "'.$db -> escape($p['vType']).'"';
	}
	
	//Build complete query
	$query = '	INSERT INTO trovit( '.$query1.' ) VALUES ( '.$query2.' )';

	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
