<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This is an abstract class for numeric range filters
 *********************************************************************************/
include_once('Zend/Filter/Interface.php');

abstract class AbstractNumRangeFilter implements Zend_Filter_Interface{
	private $max;
	private $min;
	
	public function AbstractRangeFilter($p = null){
		if(is_array($p) && isset($p['unsigned']) && ($p['unsigned'] == true)){
			$p['max'] = ($p['max'] * 2) + 1;
			$p['min'] = 0;
		}
		if (is_array($p) && isset($p['max'])){
			$this -> max = $p['max'];	
		}
		if (is_array($p) && isset($p['min'])){
			$this -> min = $p['min'];	
		}
	}

	public function filter($p){
		if($p != -1){
			if($p > $this -> max){
				$p = $this -> max;
			}
			else if ($p < $this -> min){
				$p = $this -> min;
			}
		}
		
		return $p;
	}

	public function isValid($p){
		if($p != -1){
			if(!is_numeric($p) || ($p > $this -> max) || ($p < $this -> min)){
				$p = false;
			}
			else{
				$p = $this -> filter($p);
			}
		}
		
		return $p;
	}
	
	public function getMin(){
		return $this -> min;
	}
	
	public function getMax(){
		return $this -> max;
	}

}

?>
