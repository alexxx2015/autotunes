<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100817
 * Desc:		This filter check the length of a string.
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

abstract class AbstractStringLengthFilter implements Zend_Filter_Interface{
	
	private $length;
	
	public function AbstractStringLengthFilter($p = null){
		if(is_array($p) && isset($p['length'])){
			$this -> length = $p['length'];
		}
	}

	public function filter($p){
		if($this -> length != null){
			$p = substr($p, 0, $this->length);
		}
		
		return $p;
	}
	
	public function isValid($p){
		
		if (!is_string($p)){
			$p = false;
		}
		else if (count($p) > $this -> length){
			$p = false;
		}
		
		return $p;
	}
	
	public function getLength(){
		return $this -> length;
	}
	
	public function setLength($p){
		$this -> length = $p;
	}

}

?>
