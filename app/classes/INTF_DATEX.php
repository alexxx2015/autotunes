<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110619
 * Desc:		Interface for datat exchange classes, like AS24 or mobile.de
 *********************************************************************************/
interface INTF_DATEX{
	public function handleDatexImp($p);
	public function handleDatexExp($p);
}
?>