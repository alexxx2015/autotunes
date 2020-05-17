<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100818
 * Desc:		This filter cipher a string in md5
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class FilterEncMD5 implements Zend_Filter_Interface{
	public function filter($p_string){
		return md5($p_string);
	}
}

?>
