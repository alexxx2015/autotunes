<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100805
 * Desc:		This is the template class. It concatenate and establish the HTML output
 *********************************************************************************/
class TMPL{
	/**
	 * Speichert die Variablen, die man später in dem Template ersetzen sollte
	 */
	private $values;

	/**
	 * Speichert das main Template
	 */
	private $result;
	 
	/**
	 * Start Delimiter
	 */
	private $startDelim;

	/**
	 * End Delimiter
	 */
	private $endDelim;
	
	/**
	 * Language strings
	 */
	private $lang;
	 
	/**
	 * Pfad wo die Templates abgelegt sind
	 */
	private $tmplPath;
	 
	function __construct($p){
		$this -> values = array();
		
		//Start delimiter
		$startDelim = '{-';
		if(isset($p['startDelim'])){
			$startDelim = $p['startDelim'];
		}
		$this -> startDelim = $startDelim;
		
		//End delimiter
		$endDelim = '-}';
		if(isset($p['endDelim'])){
			$endDelim = $p['endDelim'];
		}
		$this -> endDelim = $endDelim;
		
		//Path to the template directory
		$tmplPath = 'tmpl';
		if(isset($p['tmplPath'])){
			$tmplPath = $p['tmplPath'];
		}
		$this -> setTmplPath($tmplPath);
		
		//Set the main template file
		$mainTmpl = 'default/main.html';
		if(isset($p['mainTmpl'])){
			$mainTmpl = $p['mainTmpl'];
		}
		$this -> readTmplFile('main', $mainTmpl);
		
		$lang = null;
		if(isset($p['lang'])){
			$lang = $p['lang'];
		}
		$this -> setLang($lang);
	}
	/**
	 * Gibt den Wert unter einem Schlüssel zurück
	 */
	public function getValue($key){
		if(isset($this -> values[$this -> startDelim.strtoupper($key).$this -> endDelim]))
		return $this -> values[$this -> startDelim.strtoupper($key).$this -> endDelim];
		else
		return false;
	}
	 
	/**
	 * Liefert den Ende Delimiter zurück
	 */
	public function getEndDelim(){
		return $this -> endDelim;
	}

	/**
	 * Diese Funktion liefert das gerenderte Template zur�ck
	 * Ein erneutes Rendern wird nicht durchgeführt
	 */
	public function getResult(){
		return $this -> result;
	}

	/**
	 * Liefert den Star Delimiter zurück
	 */
	public function getStartDelim(){
		return $this -> startDelim;
	}
	 
	/**
	 * Liefert den Template Pfad
	 */
	public function getTmplPath(){
		return $this -> tmplPath;
	}
	
	/**
	 * Return the language variable
	 */
	public function getLang(){
		return $this -> lang;
	}


	/**
	 * Rendert das Template und ersetzt alle Variablen durch deren Werte
	 * Liefert das gerenderte Ergebnis zur�ck
	 */
	public function render(){
		$this -> result = $this -> getValue('main');
		//Suchmuster
		$search = '/'.$this -> startDelim.'[a-zA-Z0-9_]*'.$this -> endDelim.'/';
	  
		//Anzahl der Treffer
		$hits = preg_match_all($search, $this -> result, $treffer);
		
		//ersetzen
		while(($hits != 0) && ($hits != false)){
			//Speichert welche Elemente nicht in values enthalten sind
			$misses = array();

			for($i = 0; $i < $hits; $i++){
				//Wert in values enthalten
				if(array_key_exists($treffer[0][$i], $this -> values) == true){
					$this -> result = str_replace($treffer[0][$i], $this -> values[$treffer[0][$i]], $this -> result);
				}
				//Element nicht in values enthalten
				else{
					//Werte in lang variable enthalten
					$langTmpKey = str_replace($this->startDelim, '', str_replace($this->endDelim, '', $treffer[0][$i]));
					if(array_key_exists($langTmpKey, $this -> lang)){
						$this -> result = str_replace($treffer[0][$i], $this -> lang[$langTmpKey], $this -> result);
					}
					else{
						array_push($misses, $treffer[0][$i]);
					}
				}
			}
	   
			//Treffer liefern
			$hits = preg_match_all($search, $this -> result, $treffer);
	   
			if($hits - count($misses) <=0){
				break;
			}
		}
		return $this -> result;//utf8_encode($this -> result);
	}


	/**
	 * Liest den Inhalt einer Datei und speichert sie in $varName ab.
	 * Der Dateiname ist hierbei relativ zum base path.
	 * Der Dateiname muss mit Dateiendung erfolgen und ohne vorangestellten '/'
	 */
	public function readTmplFile($varName, $fileName){
		$fileURI = $this -> replaceSlashes($this -> tmplPath.'/'.$fileName);
		if(file_exists($fileURI)){
			$file = implode('', file($fileURI));
			$this -> setValue($varName, $file);
		}
	}
	 
	public function readFile($fileName){
		$fileURI = $this -> replaceSlashes($this -> tmplPath.'/'.$fileName);
		if(file_exists($fileURI)){
			$file = implode('', file($fileURI));
			return $file;
		}
		return null;
	}
	 
	public function replace($p_varName, $p_varValue, $p_tmpl){
		return str_replace($this -> startDelim.$p_varName.$this -> endDelim, $p_varValue, $p_tmpl);
	}
	 
	/**
	 * Setzt eine Wert für eine Variable
	 * überschreibt hierbei den urspr�nglichen Wert welcher unter $key eingetragen ist
	 */
	public function setValue($key, $value){
		$this -> values[$this -> startDelim.strtoupper($key).$this -> endDelim] = $value;
	}
	 
	/**
	 * Setzt den End Delimiter
	 */
	public function setEndDelim($p_endDelim){
		$this -> endDelim = $p_endDelim;
	}
	 
	/**
	 * Setzt den Start Delimiter
	 */
	public function setStartDelim($p_startDelim){
		$this -> startDelim = $p_startDelim;
	}
	 
	/**
	 * Setzt den Template Pfad neu.
	 * Pfadangabe muss hier ohne '/' am ende erfolgen
	 */
	public function setTmplPath($p_tmplPath){		
		$this -> tmplPath = $p_tmplPath;
	}
	
	/**
	 * Set the language variable
	 * Enter description here ...
	 * @param unknown_type $p_lang
	 */
	public function setLang($p_lang){
		$this -> lang = $p_lang;
	}
	
	private function replaceSlashes($string){
	
		while(strpos($string, '//') != false){
			$string = str_replace('//', '/', $string);
		}
		return $string;
	}
	
	public function tagMsg($p){
		//tag error message
		if (isset($p['ERROR_MSG'])){
			$p['RETURN'] = '<div class="error">'.(is_array($p['ERROR_MSG'])?implode(' ', $p['ERROR_MSG']):$p['ERROR_MSG']).'</div>';
		}
		
		//tag info message
		if (isset($p['INFO_MSG'])){
			$p['RETURN'] = '<div class="info">'.(is_array($p['INFO_MSG'])?implode(' ', $p['INFO_MSG']):$p['INFO_MSG']).'</div>';
		}
		return $p;
	}
}
?>