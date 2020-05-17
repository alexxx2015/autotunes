<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100816
 * Desc:		This filter transform a value into kW or PS
 *********************************************************************************/
include_once('Zend/Filter/Interface.php');

class FilterPower implements Zend_Filter_Interface{	
	
	const KW = 'kw';
	const PS = 'ps';
	
	private $kind;
	
	public function __construct($p){
		if (isset($p['kind'])){
			$this -> kind = $p['kind'];
		}
	}
	
	public function filter($p){
		switch ($this -> kind){
			case FilterPower::KW : $p = 66*$p/90;
									break;
			case FilterPower::PS : $p = 90*$p/66;
									break;
			default: $p = false;
		}
		return $p;
	}
}

?>
