<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a string is not longer than 20 characters
 *********************************************************************************/
include_once('default/views/filters/AbstractStringLengthFilter.php');

class FilterString20 extends AbstractStringLengthFilter{
	
	public function FilterString20(){
		parent::AbstractStringLengthFilter(array('length'=>20));
	}
}

?>
