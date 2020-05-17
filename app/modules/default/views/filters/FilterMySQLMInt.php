<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a number is in the range of a mysql medium int.
 * 				max: 8388607
 * 				min: -8388608
 *********************************************************************************/
include_once('default/views/filters/AbstractNumRangeFilter.php');

class FilterMySQLMInt extends AbstractNumRangeFilter{
	
	public function FilterMySQLMInt($p = null){
		if(!is_array($p)){
			$p = array('unsigned' => false);
		}
		$p['max'] = 8388607;
		$p['min'] = -8388608;
		parent::AbstractRangeFilter($p);
	}
}

?>
