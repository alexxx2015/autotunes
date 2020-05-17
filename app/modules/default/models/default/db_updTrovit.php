<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110623
 * Desc:		This function update an Trovit data set
 *********************************************************************************/
include_once('classes/DB.php');

function db_updTrovit($p=null){
	$return = false;
	
	$db = DB::getInstance();
	$query1 = '';
	$query2 = '';
	
	if (isset($p[System_Properties::SQL_SET])){
		$pSet = $p[System_Properties::SQL_SET];

		//id
		if(isset($pSet['id']) && ($pSet['id'] != null)){
		  $query1 .= ', id = "'.$db -> escape($pSet['id']).'"';
		}

		//url
		if(isset($pSet['url']) && ($pSet['url'] != null)){
		  $query1 .= ', url = "'.$db -> escape($pSet['url']).'"';
		}

		//title
		if(isset($pSet['title']) && ($pSet['title'] != null)){
		  $query1 .= ', title = "'.$db -> escape($pSet['title']).'"';
		}

		//content
		if(isset($pSet['content']) && ($pSet['content'] != null)){
		  $query1 .= ', content = "'.$db -> escape($pSet['content']).'"';
		}

		//price
		if(isset($pSet['price']) && ($pSet['price'] != null)){
		  $query1 .= ', price = "'.$db -> escape($pSet['price']).'"';
		}

		//car_type
		if(isset($pSet['car_type']) && ($pSet['car_type'] != null)){
		  $query1 .= ', car_type = "'.$db -> escape($pSet['car_type']).'"';
		}

		//dealer
		if(isset($pSet['dealer']) && ($pSet['dealer'] != null)){
		  $query1 .= ', dealer = "'.$db -> escape($pSet['dealer']).'"';
		}

		//neighborhood
		if(isset($pSet['neighborhood']) && ($pSet['neighborhood'] != null)){
		  $query1 .= ', neighborhood = "'.$db -> escape($pSet['neighborhood']).'"';
		}

		//city_area
		if(isset($pSet['city_area']) && ($pSet['city_area'] != null)){
		  $query1 .= ', city_area = "'.$db -> escape($pSet['city_area']).'"';
		}

		//city
		if(isset($pSet['city']) && ($pSet['city'] != null)){
		  $query1 .= ', city = "'.$db -> escape($pSet['city']).'"';
		}

		//region
		if(isset($pSet['region']) && ($pSet['region'] != null)){
		  $query1 .= ', region = "'.$db -> escape($pSet['region']).'"';
		}

		//postcode
		if(isset($pSet['postcode']) && ($pSet['postcode'] != null)){
		  $query1 .= ', postcode = "'.$db -> escape($pSet['postcode']).'"';
		}

		//vin_database
		if(isset($pSet['vin_database']) && ($pSet['vin_database'] != null)){
		  $query1 .= ', vin_database = "'.$db -> escape($pSet['vin_database']).'"';
		}

		//make
		if(isset($pSet['make']) && ($pSet['make'] != null)){
		  $query1 .= ', make = "'.$db -> escape($pSet['make']).'"';
		}

		//model
		if(isset($pSet['model']) && ($pSet['model'] != null)){
		  $query1 .= ', model = "'.$db -> escape($pSet['model']).'"';
		}

		//color
		if(isset($pSet['color']) && ($pSet['color'] != null)){
		  $query1 .= ', color = "'.$db -> escape($pSet['color']).'"';
		}

		//year
		if(isset($pSet['year']) && ($pSet['year'] != null)){
		  $query1 .= ', year = "'.$db -> escape($pSet['year']).'"';
		}

		//mileage
		if(isset($pSet['mileage']) && ($pSet['mileage'] != null)){
		  $query1 .= ', mileage = "'.$db -> escape($pSet['mileage']).'"';
		}

		//doors
		if(isset($pSet['doors']) && ($pSet['doors'] != null)){
		  $query1 .= ', doors = "'.$db -> escape($pSet['doors']).'"';
		}

		//seats
		if(isset($pSet['seats']) && ($pSet['seats'] != null)){
		  $query1 .= ', seats = "'.$db -> escape($pSet['seats']).'"';
		}

		//warranty
		if(isset($pSet['warranty']) && ($pSet['warranty'] != null)){
		  $query1 .= ', warranty = "'.$db -> escape($pSet['warranty']).'"';
		}

		//engine_size
		if(isset($pSet['engine_size']) && ($pSet['engine_size'] != null)){
		  $query1 .= ', engine_size = "'.$db -> escape($pSet['engine_size']).'"';
		}

		//cylinders
		if(isset($pSet['cylinders']) && ($pSet['cylinders'] != null)){
		  $query1 .= ', cylinders = "'.$db -> escape($pSet['cylinders']).'"';
		}

		//fuel
		if(isset($pSet['fuel']) && ($pSet['fuel'] != null)){
		  $query1 .= ', fuel = "'.$db -> escape($pSet['fuel']).'"';
		}

		//transmission
		if(isset($pSet['transmission']) && ($pSet['transmission'] != null)){
		  $query1 .= ', transmission = "'.$db -> escape($pSet['transmission']).'"';
		}

		//gears
		if(isset($pSet['gears']) && ($pSet['gears'] != null)){
		  $query1 .= ', gears = "'.$db -> escape($pSet['gears']).'"';
		}

		//power
		if(isset($pSet['power']) && ($pSet['power'] != null)){
		  $query1 .= ', power = "'.$db -> escape($pSet['power']).'"';
		}

		//date
		if(isset($pSet['date']) && ($pSet['date'] != null)){
		  $query1 .= ', date = "'.$db -> escape($pSet['date']).'"';
		}

		//expiration_date
		if(isset($pSet['expiration_date']) && ($pSet['expiration_date'] != null)){
		  $query1 .= ', expiration_date = "'.$db -> escape($pSet['expiration_date']).'"';
		}

		//is_new
		if(isset($pSet['is_new']) && ($pSet['is_new'] != null)){
		  $query1 .= ', is_new = "'.$db -> escape($pSet['is_new']).'"';
		}
		
		//vID
		if(isset($pSet['vID']) && ($pSet['vID'] != null)){
		  $query1 .= ', vID = "'.$db -> escape($pSet['vID']).'"';
		}
		
		//vType
		if(isset($pSet['vType']) && ($pSet['vType'] != null)){
		  $query1 .= ', vType = "'.$db -> escape($pSet['vType']).'"';
		}
		
		//trovitNew
		if(isset($pSet['trovitNew']) && ($pSet['trovitNew'] != null)){
		  $query1 .= ', trovitNew = "'.$db -> escape($pSet['trovitNew']).'"';
		}
	}
	
	if (isset($p[System_Properties::SQL_WHERE])){
		$pWhere = $p[System_Properties::SQL_WHERE];
		
		//ADD trovitID
		if (isset($pWhere['trovitID'])){
			$query2 = ' (trovitID = "'.$db -> escape($pWhere['trovitID']).'" ) AND ';
		}
		
		//ADD id
		if (isset($pWhere['id'])){
			$query2 = ' (id = "'.$db -> escape($pWhere['id']).'" ) AND ';
		}
		
		//ADD trovitNew
		if (isset($pWhere['trovitNew'])){
			$query2 = ' (trovitNew = "'.$db -> escape($pWhere['trovitNew']).'" ) AND ';
		}
	}
	
	if ($query1 != null){
		
		$query = '	UPDATE trovit
					SET '.substr($query1, 1);
		if ($query2 != null){
			$query .= ' WHERE '.substr($query2, 0, -4);
		}
		
		if (isset($p['print']) && ($p['print'] == true)){
			echo $query;
		}
		$return = $db->execQuery(array('q'=>$query));	
	}

	return $return;
}
?>
