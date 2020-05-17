<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a number is in the range of a mysql integer.
 * 				max: 8388607
 * 				min: -8388608
 *********************************************************************************/
include_once('default/views/filters/AbstractNumRangeFilter.php');

class FilterMySQLTInt extends AbstractNumRangeFilter{
	
	public function FilterMySQLTInt($p = null){
		if(!is_array($p)){
			$p = array('unsigned' => false);
		}
		$p['max'] = 127;
		$p['min'] = -128;
		parent::AbstractRangeFilter($p);
	}
}

?>
