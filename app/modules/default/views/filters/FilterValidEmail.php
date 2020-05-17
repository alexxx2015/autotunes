<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100818
 * Desc:		This filter check if a string is a valid email.
 * 				If not this filter return false, else it return back the string
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class FilterValidEmail implements Zend_Filter_Interface{

	public function filter($p_email){
		$treffer = preg_match('/[a-z0-9_-]+(\.[a-z0-9_-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4}|museum)/i',$p_email);

		if($treffer<=0){
			return false;
		}

		return true;
	}
}

?>
