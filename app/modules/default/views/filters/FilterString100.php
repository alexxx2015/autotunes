<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter check if a string is not longer than 100 characters
 *********************************************************************************/
include_once('default/views/filters/AbstractStringLengthFilter.php');

class FilterString100 extends AbstractStringLengthFilter{
	
	public function FilterString100(){
		parent::AbstractStringLengthFilter(array('length'=>100));
	}
}

?>
