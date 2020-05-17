<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100818
 * Desc:		This filter check if a string only contains spaces
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class FilterIsEmptyString implements Zend_Filter_Interface{

	public function filter($p){
		
		$treffer = preg_match('/^[\s]*$/',$p);

		if($treffer<=0){
			$return = false;
		}
		else{
			$return = true;
		}
		return $return;
	}

}

?>
