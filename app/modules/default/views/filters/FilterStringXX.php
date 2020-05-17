<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check the length of a string
 *********************************************************************************/
include_once('default/views/filters/AbstractStringLengthFilter.php');

class FilterStringXX extends AbstractStringLengthFilter{
	
	public function FilterStringXX($p_length = null){
		if ($p_length == null){
			$p_length = 50;
		}
		parent::AbstractStringLengthFilter(array('length'=>$p_length));
	}
}

?>
