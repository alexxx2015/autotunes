<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a number is in the range of a mysql integer.
 * 				max: 8388607
 * 				min: -8388608
 *********************************************************************************/
include_once('default/views/filters/AbstractNumRangeFilter.php');

class FilterMySQLSInt extends AbstractNumRangeFilter{
	
	public function FilterMySQLSInt($p = null){
		if(!is_array($p)){
			$p = array('unsigned' => false);
		}
		$p['max'] = 32767;
		$p['min'] = -32768;
		parent::AbstractRangeFilter($p);
	}
}

?>
