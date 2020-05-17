<?php
// $ads = null;
// $fileHandler = file('trovit.xml');
// $xmlRoot = new SimpleXmlIterator(implode(' ', $fileHandler));

// for($xmlRoot -> rewind(); $xmlRoot -> valid(); $xmlRoot -> next()){
// 	$currentNodeName = strtolower($xmlRoot->current()->getName());
// 	if(($currentNodeName == 'ad') && $xmlRoot -> hasChildren()){
		
// 		foreach($xmlRoot -> getChildren() as $key => $kVal){
// 			$key = strtolower($key);
// 			if($key == 'pictures'){
				
// 				for ($kVal -> rewind(); $kVal -> valid(); $kVal -> next()){
// 					foreach($kVal -> getChildren() as $key2 => $kVal2){
// 						echo $key2 .' '.$kVal2.'<br/>';	
// 					}
// 				}				
// 			}
// 		}
// 	}	
// }
echo phpInfo();
// header("Content-type: image/jpeg");
// filter("https://ah24public.s3.amazonaws.com/car_model_images/watermarked_p0504562.jpg");
// function filter($p_imgFileName){
// 	require_once 'Zend/Filter/Exception.php';
// 	if (!stristr($p_imgFileName, 'http://') || !stristr($p_imgFileName,'https://')){
// 		$curl = curl_init($p_imgFileName);
// 		curl_setopt($curl,CURLOPT_NOBODY,true);
// 		curl_exec($curl);
// 		$ret = curl_getinfo($curl,CURLINFO_HTTP_CODE);
// 		curl_close();
// 		if($ret != 200){
// 			throw new Zend_Filter_Exception("File  not found "+$p_imgFileName);
// 		}
// 	} else if(!file_exists($p_imgFileName)) {
// 		throw new Zend_Filter_Exception("File  not found "+$p_imgFileName);
// 	}
	
// 	$_imgSrcExtension = explode('.', basename($p_imgFileName));
// 	if(is_array($_imgSrcExtension)){
// 		$_imgSrcExtension = $_imgSrcExtension[1];
// 	}
	
// 	$imgSrc = false;
// 	switch($_imgSrcExtension){
// 		case 'jpg': $imgSrc = imagecreatefromjpeg($p_imgFileName);
// 		//$this -> _imgSrcExtension = 'jpeg';
// 		break;
// 		case 'jpeg':$imgSrc = imagecreatefromjpeg($p_imgFileName);
// 		break;
// 		case 'png':	$imgSrc = imagecreatefrompng($p_imgFileName);
// 		break;
// 	}

// 	if($imgSrc != false){
// 		$imgTrg = imagecreatetruecolor(640,480);
// 		$bg = imagecolorallocate($imgTrg, 0, 0, 0);
// 		imagefill($imgTrg, 0, 0, $bg);
		
// 		imagecopyresampled($imgTrg, $imgSrc, 0, 0, 0, 0, 640, 480, 640, 480);
// 		imagejpeg($imgTrg,"/var/customers/webs/autotunes/www/web/img1.jpg",100);
// // 		imagejpeg($imgTrg);
// 		echo "<img src='/img1.jpg'/>";
// 	}else echo ";;";
// 	}
?>