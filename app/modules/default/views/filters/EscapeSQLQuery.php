<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100901
 * Desc:		This filter escape all SQL instruction
 *********************************************************************************/
include_once('Zend/Filter/Interface.php');

class EscapeSQLQuery implements Zend_Filter_Interface{

	public function filter($p_string){
		$ret = $p_string;
		if(is_array($ret)){
			$ret = $this -> filterArr($p_string);
		}
		else{
			$ret = mysql_real_escape_string($p_string);
		}
		return $ret;
	}

	private function filterArr($p_arr){
		foreach($p_arr as $key => $val){
			if(is_array($val)){
				$this -> filterArr($val);
			}
			else{
				$p_arr[$key] = mysql_real_escape_string($val);
			}
		}
		return $p_arr;
	}
}

?>
