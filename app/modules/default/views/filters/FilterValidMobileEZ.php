<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100818
 * Desc:		This filter check if a string is a mobile EZ
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class FilterValidMobileEZ implements Zend_Filter_Interface{

	public function filter($p){
		$treffer = preg_match('/[0-9]{2}\.[0-9]{4}/i',$p);

		if($treffer<=0){
			return false;
		}

		return true;
	}
}

?>
