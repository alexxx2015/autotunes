<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a parameter is a valid month
 *********************************************************************************/
include_once('default/views/filters/AbstractNumRangeFilter.php');

class FilterMonth extends AbstractNumRangeFilter{
	
	public function FilterMonth($p = null){
		if(!is_array($p)){
			$p = array();
		}
		$p['max'] = 12;
		$p['min'] = 1;
		parent::AbstractRangeFilter($p);
	}
	
	public function isValid($p){
		$return = false;
		if((parent::isValid($p) == true) && ($p != -1)){
			$return = true;
		}
		return $return;
	}
}

?>
