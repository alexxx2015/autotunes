<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a string is not longer than 50 characters
 *********************************************************************************/
include_once('default/views/filters/AbstractStringLengthFilter.php');

class FilterString50 extends AbstractStringLengthFilter{
	
	public function FilterString50(){
		parent::AbstractStringLengthFilter(array('length'=>50));
	}
}

?>
