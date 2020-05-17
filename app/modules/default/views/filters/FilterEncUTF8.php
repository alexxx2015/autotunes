<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100818
 * Desc:		This filter encode a string in UTF-8
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class FilterEncUTF8 implements Zend_Filter_Interface{

	public function filter($p_string){
		if (is_array($p_string)){
			return $this -> filterArr($p_string);
		}else{
			return utf8_encode($p_string);
		}
	}
	
	private function filterArr($p){
		if (is_array($p)){
			foreach ($p as $key => $value){
				if (is_array($value)){
					$p[$key] = $this -> filterArr($value);
				}
				else{
					$p[$key] = utf8_encode($value);
				}
			}
		}else{
			$p = utf8_encode($p);
		}
		return $p;
	}

}

?>
