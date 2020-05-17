<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a parameter is a valid year
 *********************************************************************************/
include_once('default/views/filters/AbstractNumRangeFilter.php');

class FilterYear extends AbstractNumRangeFilter{
	
	public function FilterYear($p = null){
		if(!is_array($p)){
			$p = array();
		}
		$p['max'] = date('Y');
		$p['min'] = 1900;
		parent::AbstractRangeFilter($p);
	}
	
	public function isValid($p){
		$parent = parent::isValid($p);
		$return = false;
		if(( $parent== true) && ($p != -1)){
			$return = true;
		}
		return $return;
	}
}

?>
