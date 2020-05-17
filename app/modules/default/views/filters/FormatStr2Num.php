<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100907
 * Desc:		This filter format a string into a numeric representation
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class FormatSt2Num implements Zend_Filter_Interface{
	private $decPlace;
	private $decPlaceDelim;
	private $thousendDelim;
	
	public function __construct(){
		$this -> decPlace = 0;
		$this -> decPlaceDelim = ',';
		$this -> thousandDelim = '.';
	}
	public function filter($p){
		$val = $p;
		
		$tmpDecPlace = $this -> decPlace;
		$tmpDecPlaceDelim = $this -> decPlaceDelim;
		$tmpThousandDelim = $this -> thousandDelim;
		
		if (is_array($p)){
			if (isset($p['dec_place'])){
				$tmpDecPlace = $p['dec_place'];
			}
			
			if (isset($p['dec_place_delim'])){
				$tmpDecPlaceDelim = $p['dec_place_delim'];
			}
			
			if (isset($p['thousand_delim'])){
				$tmpThousandDelim = $p['thousand_delim'];
			}
			
			if (isset($p['val'])){
				$val = $p['val'];
			}
		}
		$val = str_ireplace(',', '.', $val);
		$val = number_format($val, $tmpDecPlace, $tmpDecPlaceDelim, $tmpThousandDelim);
		return $val;
	}

}

?>
