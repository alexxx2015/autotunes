<?php
/***********************************************************
 *	File: EscapePHP.php
 *  Created: 28.01.2010
 * 	Author: Frommm Alexander
 * 	Desc: This filter remove all HTML/PHP tags from the string
 ************************************************************/
require_once('Zend/Filter/Interface.php');

class RemoveHTMLPHP implements Zend_Filter_Interface{

	private $exception = array();

	public function filter($p_string){
		if(count($this -> exception) > 0)
		return strip_tags($p_string, implode(',', $this -> exception));
		else
		return strip_tags($p_string);
	}

	public function setException($p_exception){
		$this -> exception[$p_exception] = $p_exception;
	}

	public function removeException($p_exception){
		$newException = array();

		foreach($this -> exception as $val){
			if(strtolower($val) != strtolower($p_exception)){
				$newException[$val] = $val;
			}
		}
		$this -> exception =  $newException;
	}
}

?>
