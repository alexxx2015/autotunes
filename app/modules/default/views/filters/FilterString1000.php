<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a string is not longer than 100 characters
 *********************************************************************************/
include_once('default/views/filters/AbstractStringLengthFilter.php');

class FilterString1000 extends AbstractStringLengthFilter{
	
	public function FilterString1000(){
		parent::AbstractStringLengthFilter(array('length'=>1000));
	}
}

?>
