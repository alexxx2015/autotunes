<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This class filters an uploaded image an put it in the right folder
 *********************************************************************************/
require_once('Zend/Filter/Interface.php');

class ImageFilter implements Zend_Filter_Interface{
	private $_imgFileName;

	private $_imgTrgWidth;
	private $_imgTrgHeight;
	private $_imgTrgRatio;

	private $_imgSrcExtension;
	
	private $_imgDestFile;


	public function __construct($p = array()){
		//$p_imgTrgWidth = 640, $p_imgTrgHeight = 480, $p_imgSrcExtension = null
		$p_imgTrgWidth = 640;
		if (isset($p['imgTrgWidth'])){
			$p_imgTrgWidth = $p['imgTrgWidth'];
		}
		$this -> _imgTrgWidth = $p_imgTrgWidth;
	
		$p_imgTrgHeight = 640;
		if (isset($p['imgTrgHeight'])){
			$p_imgTrgHeight = $p['imgTrgHeight'];
		}
		$this -> _imgTrgHeight = $p_imgTrgHeight;
	
		$p_imgSrcExtension = null;
		if (isset($p['imgSrcExtension'])){
			$p_imgSrcExtension = $p['imgSrcExtension'];
		}
		$this -> _imgSrcExtension = strtolower($p_imgSrcExtension);
		
		$this -> _imgTrgRatio = $this -> _imgTrgWidth/$this -> _imgTrgHeight;
		
		if (isset($p['imgDestFile'])){
			$this -> _imgDestFile = $p['imgDestFile'];
		}
	}

	public function filter($p_imgFileName){
		require_once 'Zend/Filter/Exception.php';
		if (stristr($p_imgFileName, 'http://') || stristr($p_imgFileName,'https://')){
			$curl = curl_init($p_imgFileName);
			curl_setopt($curl,CURLOPT_NOBODY,true);
			curl_exec($curl);
			$ret = curl_getinfo($curl,CURLINFO_HTTP_CODE);
			curl_close($curl);
			if($ret != 200){
				throw new Zend_Filter_Exception("File  not found "+$p_imgFileName);
			}
		} else if(!file_exists($p_imgFileName)) {
			throw new Zend_Filter_Exception("File  not found "+$p_imgFileName);
		}

		$this -> _imgFileName = $p_imgFileName;
		if ($this->_imgDestFile == null){
			$this->_imgDestFile = $this->_imgFileName;
		}

		if($this -> _imgSrcExtension == null){
			$this -> _imgSrcExtension = explode('.', basename($this -> _imgFileName));
			if(is_array($this -> _imgSrcExtension)){
				$this -> _imgSrcExtension = $this -> _imgSrcExtension[1];
			}
		}
		/*
		$imgSrc = false;
		switch($this -> _imgSrcExtension){
			case 'jpg': $imgSrc = imagecreatefromjpeg($this -> _imgFileName);
						//$this -> _imgSrcExtension = 'jpeg';
						break;
			case 'jpeg':$imgSrc = imagecreatefromjpeg($this -> _imgFileName);
						break;
			case 'png':	$imgSrc = imagecreatefrompng($this -> _imgFileName);
						break;
		}*/
		
		$imgAsString = file_get_contents($this -> _imgFileName);
		$imgSrc = null;
		if($imgAsString != false){
			$imgSrc = imagecreatefromstring($imgAsString);
		}

		if($imgSrc != false){
			$imgSrcWidth = imagesx($imgSrc);
			$imgSrcHeight = imagesy($imgSrc);
			$imgSrcRatio = $imgSrcWidth/$imgSrcHeight;
				
			$imgCpWidth = $imgSrcWidth;
			$imgCpHeight = $imgSrcHeight;
			$imgCpX = 0;
			$imgCpY = 0;
				
			if($imgSrcWidth > $this -> _imgTrgWidth){
				$imgCpWidth = $this -> _imgTrgWidth;
				//$imgCpHeight =  $imgCpWidth/$imgSrcRatio;
				$imgCpHeight = $imgCpWidth * $imgSrcHeight / $imgSrcWidth;

				if($imgCpHeight > $this -> _imgTrgHeight){
					$imgCpHeight = $this -> _imgTrgHeight;
					//$imgCpWidth = $imgCpHeight * $imgSrcRatio;
					$imgCpWidth = $imgSrcWidth * $imgCpHeight / $imgSrcHeight;
				}
			}
			else if($imgSrcHeight > $this -> _imgTrgHeight){
				$imgCpHeight = $this -> _imgTrgHeight;
				//$imgCpWidth = $imgCpHeight * $imgSrcRatio;
				$imgCpWidth = $imgSrcWidth * $imgCpHeight / $imgSrcHeight;

				if($imgCpWidth > $this -> _imgTrgWidth){
					$imgCpWidth = $this -> _imgTrgWidth;
					//$imgCpHeight =  $imgCpWidth/$imgSrcRatio;
					$imgCpHeight = $imgCpWidth * $imgSrcHeight / $imgSrcWidth;
				}
			}
				
			$imgCpX = ($this -> _imgTrgWidth / 2) - ($imgCpWidth / 2);
			$imgCpY = ($this -> _imgTrgHeight / 2) - ($imgCpHeight / 2);
				
			$imgTrg = imagecreatetruecolor($this -> _imgTrgWidth, $this -> _imgTrgHeight);
			$bg = imagecolorallocate($imgTrg, 0, 0, 0);
			imagefill($imgTrg, 0, 0, $bg);
			//imagesavealpha($imgTrg, true);
			//imagecolortransparent($imgTrg, $bg);
			//imagegammacorrect($imgTrg, 1.0, 0.75);
			
			imagecopyresampled($imgTrg, $imgSrc, $imgCpX, $imgCpY, 0, 0, $imgCpWidth, $imgCpHeight, $imgSrcWidth, $imgSrcHeight);
			imagejpeg($imgTrg, $this -> _imgDestFile,100);
			imagedestroy($imgSrc);
			/*
			 switch($this -> _imgSrcExtension){
				case 'jpg': imagejpeg($imgTrg, $this -> _imgFileName);
				break;
				case 'jpeg':imagejpeg($imgTrg, $this -> _imgFileName);
				break;
				case 'png':	imagepng($imgTrg, $this -> _imgFileName);
				break;
				}
				*/
		}
	}
}

?>
