<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100901
 * Desc:		This filter escape all HTML and SQL instruction
 *********************************************************************************/
include_once('Zend/Filter.php');
include_once('Zend/Filter/HtmlEntities.php');
include_once('default/views/filters/EscapeSQLQuery.php');

class EscapeSQLQuery4DB extends Zend_Filter{

	function __construct(){
		//Add HTML Filter
		//$this -> addFilter(new Zend_Filter_HtmlEntities());

		//Add SQL Filter
		$this -> addFilter(new EscapeSQLQuery());
	}
}

?>