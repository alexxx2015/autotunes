<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a number is in the range of a mysql integer.
 * 				max: 8388607
 * 				min: -8388608
 *********************************************************************************/
include_once('default/views/filters/AbstractNumRangeFilter.php');

class FilterMySQLInt extends AbstractNumRangeFilter{
	
	public function FilterMySQLInt($p=null){
		if(!is_array($p)){
			$p = array('unsigned' => false);
		}
		$p['max'] = 2147483647;
		$p['min'] = -2147483648;
		parent::AbstractRangeFilter($p);
	}
}

?>
