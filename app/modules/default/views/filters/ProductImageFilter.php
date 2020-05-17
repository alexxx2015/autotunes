<?php
/***********************************************************
 *	File: ProductImageFilter.php
 *  Created: 01.04.2010
 * 	Author: Frommm Alexander
 * 	Desc: This is the image filter for product photos
 ************************************************************/
require_once('Zend/Filter/Interface.php');

class ProductImageFilter implements Zend_Filter_Interface{
	private $_imgFileSrc;
	private $_imgFileDest;

	private $_imgTrgWidth;
	private $_imgTrgHeight;
	private $_imgTrgRatio;

	private $_imgSrcExtension;


	public function __construct($p_imgTrgWidth = 640, $p_imgTrgHeight = 480, $p_imgFileDest = null){
		$this -> _imgTrgWidth = $p_imgTrgWidth;
		$this -> _imgTrgHeight = $p_imgTrgHeight;
		$this -> _imgTrgRatio = $this -> _imgTrgWidth/$this -> _imgTrgHeight;

		if($p_imgFileDest != null){
			$this -> _imgFileDest = $p_imgFileDest;
		}
	}

	public function filter($p_imgFileSrc){
		/*
		 if (!file_exists($p_imgFileSrc)) {
		 require_once 'Zend/Filter/Exception.php';
		 throw new Zend_Filter_Exception("File '$value' not found");
		 }
		 */

		$this -> _imgFileSrc = $p_imgFileSrc;
		if($this -> _imgFileDest == null){
			$this -> _imgFileDest = $this -> _imgFileSrc;
		}

		if($this -> _imgSrcExtension == null){
			$this -> _imgSrcExtension = explode('.', basename($this -> _imgFileSrc));
			if(is_array($this -> _imgSrcExtension)){
				$this -> _imgSrcExtension = $this -> _imgSrcExtension[1];
			}
		}

		$imgSrc = false;
		switch($this -> _imgSrcExtension){
			case 'jpg': $imgSrc = imagecreatefromjpeg($this -> _imgFileSrc);
			break;
			case 'jpeg':$imgSrc = imagecreatefromjpeg($this -> _imgFileSrc);
			break;
			case 'png':	$imgSrc = imagecreatefrompng($this -> _imgFileSrc);
			break;
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
				$imgCpHeight =  $imgCpWidth/$imgSrcRatio;

				if($imgCpHeight > $this -> _imgTrgHeight){
					$imgCpHeight = $this -> _imgTrgHeight;
					$imgCpWidth = $imgCpHeight * $imgSrcRatio;
				}
			}
			else if($imgSrcHeight > $this -> _imgTrgHeight){
				$imgCpHeight = $this -> _imgTrgHeight;
				$imgCpWidth = $imgCpHeight * $imgSrcRatio;

				if($imgCpWidth > $this -> _imgTrgWidth){
					$imgCpWidth = $this -> _imgTrgWidth;
					$imgCpHeight =  $imgCpWidth/$imgSrcRatio;
				}
			}
				
			$imgCpX = 0;//($this -> _imgTrgWidth / 2) - ($imgCpWidth / 2);
			$imgCpY = 0;//($this -> _imgTrgHeight / 2) - ($imgCpHeight / 2);
				
			$imgTrg = imagecreatetruecolor($imgCpWidth, $imgCpHeight);//$this -> _imgTrgWidth, $this -> _imgTrgHeight);
			//$bg = imagecolorallocatealpha($imgTrg, 255, 255, 255, 75);
			//imagefill($imgTrg, 0, 0, $bg);
			//imagesavealpha($imgTrg, true);
			//imagecolortransparent($imgTrg, $bg);
			//imagegammacorrect($imgTrg, 1.0, 0.75);
				
			imagecopyresampled($imgTrg, $imgSrc, $imgCpX, $imgCpY, 0, 0, $imgCpWidth, $imgCpHeight, $imgSrcWidth, $imgSrcHeight);
			imagejpeg($imgTrg, $this -> _imgFileDest,100);
			imagedestroy($imgSrc);
			/*
			 switch($this -> _imgSrcExtension){
				case 'jpg': imagejpeg($imgTrg, $this -> _imgFileSrc);
				break;
				case 'jpeg':imagejpeg($imgTrg, $this -> _imgFileSrc);
				break;
				case 'png':	imagepng($imgTrg, $this -> _imgFileSrc);
				break;
				}
				*/
		}
	}
}

?>
