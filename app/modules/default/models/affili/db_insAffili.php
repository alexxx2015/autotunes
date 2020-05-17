<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insAffili($p=null){
	$return = false;
	$db = DB::getInstance();
	
	//perform escape
	$p = $db -> escape($p);
	
	$query1 = 'timestam, new';
	$query2 = 'UNIX_TIMESTAMP(), 1';
	
	//programID
	if(isset($p['programID']) && ($p['programID'] != null) && ($p['programID'] != '')){
	  $query1 .= ', programID';
	  $query2 .= ', "'.$db -> escape($p['programID']).'"';
	}
	
	//articleNumber
	if(isset($p['articleNumber']) && ($p['articleNumber'] != null) && ($p['articleNumber'] != '')){
	  $query1 .= ', articleNumber';
	  $query2 .= ', "'.$db -> escape($p['articleNumber']).'"';
	}
	
	//categoryID
	if(isset($p['categoryID']) && ($p['categoryID'] != null) && ($p['categoryID'] != '')){
	  $query1 .= ', categoryID';
	  $query2 .= ', "'.$db -> escape($p['categoryID']).'"';
	}
	
	//categoryPath
	if(isset($p['categoryPath']) && ($p['categoryPath'] != null) && ($p['categoryPath'] != '')){
	  $query1 .= ', categoryPath';
	  $query2 .= ', "'.$db -> escape($p['categoryPath']).'"';
	}
	
	//price
	if(isset($p['price']) && ($p['price'] != null) && ($p['price'] != '')){
	  $query1 .= ', price';
	  $query2 .= ', "'.$db -> escape($p['price']).'"';
	}
	
	//link
	if(isset($p['link']) && ($p['link'] != null) && ($p['link'] != '')){
	  $query1 .= ', link';
	  $query2 .= ', "'.$db -> escape($p['link']).'"';
	}
	
	//articleTitle
	if(isset($p['articleTitle']) && ($p['articleTitle'] != null) && ($p['articleTitle'] != '')){
	  $query1 .= ', articleTitle';
	  $query2 .= ', "'.$db -> escape($p['articleTitle']).'"';
	}
	
	//articleDesc
	if(isset($p['articleDesc']) && ($p['articleDesc'] != null) && ($p['articleDesc'] != '')){
	  $query1 .= ', articleDesc';
	  $query2 .= ', "'.$db -> escape($p['articleDesc']).'"';
	}
	
	//imgURL
	if(isset($p['imgURL']) && ($p['imgURL'] != null) && ($p['imgURL'] != '')){
	  $query1 .= ', imgURL';
	  $query2 .= ', "'.$db -> escape($p['imgURL']).'"';
	}
	
	//refData
	if(isset($p['refData']) && ($p['refData'] != null) && ($p['refData'] != '')){
	  $query1 .= ', refData';
	  $query2 .= ', "'.$db -> escape($p['refData']).'"';
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
	$query = '	INSERT INTO affili( '.$query1.' ) VALUES ( '.$query2.' )';

	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
