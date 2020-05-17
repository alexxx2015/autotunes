<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100818
 * Desc:		This filter check if a string is a AS24 EZ
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class FilterValidAS24EZ implements Zend_Filter_Interface{

	public function filter($p){
		$treffer = preg_match('/[0-9]{2}\.[0-9]{4}/i',$p);

		if($treffer<=0){
			return false;
		}

		return true;
	}
}

?>
