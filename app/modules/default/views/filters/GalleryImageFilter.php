<?php
/***********************************************************
 *	File: GalleryImageFilter.php
 *  Created: 01.04.2010
 * 	Author: Frommm Alexander
 * 	Desc: This is the image filter for gallery photos
 ************************************************************/
require_once('Zend/Filter/Interface.php');

class GalleryImageFilter implements Zend_Filter_Interface{
	private $_imgFileName;

	private $_imgTrgWidth;
	private $_imgTrgHeight;
	private $_imgTrgRatio;

	private $_imgSrcExtension;


	public function __construct($p_imgTrgWidth = 640, $p_imgTrgHeight = 480, $p_imgSrcExtension = null){
		$this -> _imgTrgWidth = $p_imgTrgWidth;
		$this -> _imgTrgHeight = $p_imgTrgHeight;
		$this -> _imgTrgRatio = $this -> _imgTrgWidth/$this -> _imgTrgHeight;
		$this -> _imgSrcExtension = strtolower($p_imgSrcExtension);
	}

	public function filter($p_imgFileName){
		if (!file_exists($p_imgFileName)) {
			require_once 'Zend/Filter/Exception.php';
			throw new Zend_Filter_Exception("File '$value' not found");
		}

		$this -> _imgFileName = $p_imgFileName;

		if($this -> _imgSrcExtension == null){
			$this -> _imgSrcExtension = explode('.', basename($this -> _imgFileName));
			if(is_array($this -> _imgSrcExtension)){
				$this -> _imgSrcExtension = $this -> _imgSrcExtension[1];
			}
		}

		$imgSrc = false;
		switch($this -> _imgSrcExtension){
			case 'jpg': $imgSrc = imagecreatefromjpeg($this -> _imgFileName);
			break;
			case 'jpeg':$imgSrc = imagecreatefromjpeg($this -> _imgFileName);
			break;
			case 'png':	$imgSrc = imagecreatefrompng($this -> _imgFileName);
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
			imagejpeg($imgTrg, $this -> _imgFileName,80);
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
