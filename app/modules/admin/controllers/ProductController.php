<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for the administration INDEX
 *********************************************************************************/
include_once('classes/AbstractController.php');
include_once('modules/default/views/filters/FilterIsEmptyString.php');

include_once(System_Properties::ADMIN_MOD_PATH.'/models/product/db_selProdCat.php');
include_once(System_Properties::ADMIN_MOD_PATH.'/models/product/db_updProdCat.php');
include_once(System_Properties::ADMIN_MOD_PATH.'/models/product/db_insProdCat.php');

include_once(System_Properties::ADMIN_MOD_PATH.'/models/product/db_selProdCatProp.php');
include_once(System_Properties::ADMIN_MOD_PATH.'/models/product/db_insProdCatProp.php');
include_once(System_Properties::ADMIN_MOD_PATH.'/models/product/db_updProdCatProp.php');

include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selLang.php');

class Admin_ProductController extends AbstractController{
	
	public function preDispatch(){
		parent::preDispatch();				
		
		$this -> view -> tmpl = $this->tmpl;//$this -> getFrontController() -> getParam(System_Properties::TMPL);
		$this -> view -> lang = $this -> lang;
		
		if (!isset($this -> adminNS -> adminData)
			|| !isset($this -> adminNS -> adminLogged)
			|| ($this -> adminNS -> adminLogged != true)) {
			$this -> _forward('index', 'notlogged');
		}
	}
	
	public function indexAction(){
		
	}
	
	public function prodcatAction(){
		$lang = $this -> lang;
		$p = $this -> getRequest() -> getParams();
		
		$error = '';
		$info = '';
		$posLang = db_selLang(array('langAbrv'=>'DE'));
				
		//create new product category
		if (is_array($p) && isset($p['prodCatCreate'])){
			$p['POS_LANG'] = $posLang; 
			$ret = $this -> insertprodcatAction($p);
			if (is_array($ret) && isset($ret['ERROR'])){
				$error = $ret['ERROR'];
			}
			if (is_array($ret) && isset($ret['INFO'])){
				$info = $ret['INFO'];
				$lang = $this -> lang;
				$this -> view -> lang = $lang;
			}
		}
		
		$this -> view -> error = $error;
		$this -> view -> info = $info;
		$this -> view -> prodCat = $p;
		$this -> view -> prodCats = db_selProdCat(array('orderby' => array(array('col' => 'lft'))
													//, 'print' => true
													));
		$this -> view -> posLang = $posLang;
	}
	
	private function insertprodcatAction($p = array()){
		
		$ret = array('ERROR'=>array(), 'INFO' => array());
		$lang = $this -> lang;
				
		$isEmptyString = new FilterIsEmptyString();
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			foreach($p['POS_LANG'] as $key => $posLang){
				if (is_array($posLang) && isset($posLang['langAbrv'])){
					$posLang = $posLang['langAbrv'];
	
					if (!isset($p['prodCatName_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatName_'.strtolower($posLang)]) == true)){					
						$ret['ERROR'][] = $lang['AERR_38'];
					}elseif (!isset($p['prodCatParent']) || ($isEmptyString->filter($p['prodCatParent']) == true)){
						$ret['ERROR'][] = $lang['AERR_39'];
					}elseif (!isset($p['prodCatKeyWord_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatKeyWord_'.strtolower($posLang)]) == true)){
						$ret['ERROR'][]= $lang['AERR_40'];
					}
				}
			}
		}
		
		if (count($ret['ERROR']) <= 0){
			//Fetch parent category
			if ($p['prodCatParent'] < 1){
				$p['prodCatParent'] = '1';
				$parentCat = db_selProdCat(array('prodCatID' => '1'
												//, 'print' => true
												));
				if ($parentCat == false){
					db_insProdCat(array('lft' => '1'
										, 'rgt' => '2'
										, 'active' => '1'));
				}
			}
			$parentCat = db_selProdCat(array('prodCatID' => $p['prodCatParent']
											//, 'print'=>TRUE
											));				
			if( ($parentCat != false) && is_array($parentCat) && (count($parentCat) > 0)){	
				$parentCat = $parentCat[0];
				db_updProdCat(array(System_Properties::SQL_SET => array('incLft'=>2)
								, System_Properties::SQL_WHERE => array('lftBEq'=>$parentCat['rgt'])
									)
							);
				db_updProdCat(array(System_Properties::SQL_SET => array('incRgt'=>2)
								, System_Properties::SQL_WHERE => array('rgtBEq'=>$parentCat['rgt'])
									)
							);				
				$prodCatID = db_insProdCat(array('lft' => $parentCat['rgt']
												, 'rgt' => $parentCat['rgt'] + 1
												, 'active' => 1
											));
											
				if( ($prodCatID != false) && is_numeric($prodCatID)){
					$ret['INFO'][] = $lang['AINFO_18'];
					
					$p['PROD_CAT_ID'] = $prodCatID;
					if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
						foreach($p['POS_LANG'] as $key => $posLang){
							if (is_array($posLang) && isset($posLang['langAbrv']) && isset($lang['LID'])
								&& (strtoupper($lang['LID']) == strtoupper($posLang['langAbrv']))){
								$posLang = $posLang['langAbrv'];
								if (isset($p['prodCatName_'.strtolower($posLang)]) && ($isEmptyString->filter($p['prodCatName_'.strtolower($posLang)]) != true)){
									$lang['PROD_CAT'][$prodCatID]['NAME'] = $p['prodCatName_'.strtolower($posLang)];
								}
								
								if (isset($p['prodCatKeyWord_'.strtolower($posLang)]) && ($isEmptyString->filter($p['prodCatKeyWord_'.strtolower($posLang)]) != true)){
									$lang['PROD_CAT'][$prodCatID]['KEYWORD'] = $p['prodCatKeyWord_'.strtolower($posLang)];
								}
								$this -> lang = $lang; 
							}
						}						
						/*
						foreach($p['POS_LANG'] as $key => $posLang){
							if (is_array($posLang) && isset($posLang['langAbrv'])){
								$posLang = $posLang['langAbrv'];
								$p['PROD_CAT_'.strtoupper($posLang)] = array();
								if (!isset($p['prodCatName_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatName_'.strtolower($posLang)]) != true)){
									$p['PROD_CAT_'.strtoupper($posLang)]['NAME'] = $p['prodCatName'];
								}
								
								if (!isset($p['prodCatKeyWord_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatKeyWord_'.strtolower($posLang)]) != true)){
									$p['PROD_CAT_'.strtoupper($posLang)]['KEYWORD'] = $p['prodCatKeyWord'];
								}
							}
						}*/
						$this -> writeProdCatFileAction($p);
					}
					
					//$this -> lang['PROD_CAT'][$p['vcatID']] = $p[$langProdcatID];
					//$this -> view -> lang = $this -> lang;
				}			
			}
		}
		return $ret;
	}
	
	//Read product category file
	private function readprodcatfileAction($p){
		$posLang = false;
		if (isset($p['POS_LANG'])){
			$posLang = $p['POS_LANG'];
		}
		if (is_array($posLang)){
			foreach ($posLang as $key => $value){
				$fileName = '../app/modules/default/lang/lang_prodcat_'.strtolower($value['langAbrv']).'.php';
				
				if (file_exists($fileName)){
					$file = file($fileName);					
					if (is_array($file)){
						$lang_var = array();
						foreach($file as $key => $row){
							if ($key > 10){
								array_push($lang_var, $row);
							}
						}
						//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
						eval(implode('',$lang_var));
						ksort($lang['PROD_CAT']);
						$p['return']['PROD_CAT_'.strtoupper($value['langAbrv'])] = $lang['PROD_CAT'];
					}
				}
			}
			//$this -> view -> lang = $this -> lang;	
		}
		elseif (is_string($posLang)){
			$fileName = '../app/modules/default/lang/lang_prodcat_'.strtolower($posLang).'.php';
				
			if (file_exists($fileName)){
				$file = file($fileName);					
				if (is_array($file)){
					$lang_var = array();
					foreach($file as $key => $row){
						if ($key > 10){
							array_push($lang_var, $row);
						}
					}
					//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
					
					eval(implode('',$lang_var));
					ksort($lang['PROD_CAT']);
					$p['return']['PROD_CAT_'.strtoupper($posLang)] = $lang['PROD_CAT'];
				}
			}	
		}
		return $p;
	}
	
	//Read product category file
	private function readprodcatpropfileAction($p){
		$posLang = false;
		if (isset($p['POS_LANG'])){
			$posLang = $p['POS_LANG'];
		}
		if (is_array($posLang)){
			foreach ($posLang as $key => $value){
				$fileName = '../app/modules/default/lang/lang_prodcatprop_'.strtolower($value['langAbrv']).'.php';
				
				if (file_exists($fileName)){
					$file = file($fileName);					
					if (is_array($file)){
						$lang_var = array();
						foreach($file as $key => $row){
							if ($key > 10){
								array_push($lang_var, $row);
							}
						}
						//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
						eval(implode('',$lang_var));
						ksort($lang['PROD_CAT_PROP']);
						$p['return']['PROD_CAT_PROP_'.strtoupper($value['langAbrv'])] = $lang['PROD_CAT_PROP'];
					}
				}
			}
			//$this -> view -> lang = $this -> lang;	
		}
		elseif (is_string($posLang)){
			$fileName = '../app/modules/default/lang/lang_prodcatprop_'.strtolower($posLang).'.php';
				
			if (file_exists($fileName)){
				$file = file($fileName);					
				if (is_array($file)){
					$lang_var = array();
					foreach($file as $key => $row){
						if ($key > 10){
							array_push($lang_var, $row);
						}
					}
					//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
					
					eval(implode('',$lang_var));
					ksort($lang['PROD_CAT_PROP']);
					$p['return']['PROD_CAT_PROP_'.strtoupper($posLang)] = $lang['PROD_CAT_PROP'];
				}
			}	
		}
		return $p;
	}
	
	/**
	 * Write product categorie files*/	
	private function writeProdCatFileAction($p){
		if (isset($p['PROD_CAT_ID']) && isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			//$posLang = db_selLang(array('langID'=>$p['lang']));
			foreach ($p['POS_LANG'] as $key => $posLang){
				if (is_array($posLang) && isset($posLang['langAbrv'])){
					$posLang = $posLang['langAbrv'];
					$prodCatName = '';
					$prodCatKeyWord = '';
					
					if (isset($p['prodCatName_'.strtolower($posLang)])){
						$prodCatName = $p['prodCatName_'.strtolower($posLang)];
					}
					
					if (isset($p['prodCatKeyWord_'.strtolower($posLang)])){
						$prodCatKeyWord = $p['prodCatKeyWord_'.strtolower($posLang)];
					}
					
					$fileName = '../app/modules/default/lang/lang_prodcat_'.strtolower($posLang).'.php';
					if (file_exists($fileName)){
						$file = file($fileName);
						//separate PROD_CAT lines from rest
						foreach ($file as $key => $val) {
							$file[$key] = str_ireplace('?>', '', $val);
						}
						//push new element at th end of the V_CAT array
						array_push($file, "\r\n\$lang['PROD_CAT'][".$p['PROD_CAT_ID']."]['NAME'] = '".$prodCatName."';\r\n\$lang['PROD_CAT'][".$p['PROD_CAT_ID']."]['KEYWORD'] = '".$prodCatKeyWord."';\r\n?>");
						
											
						//write the new array into file
						$fp = fopen($fileName, 'w');
						if($fp != false){
							fwrite($fp, implode("", $file));		
							fclose($fp);			
						}
					}
				}
			}
		}
	}
/**
	 * This function update a vehicle category in all language files
	 */	
	private function writeUpdateProdcatfileAction($p){
		//check if each possible language should be updated
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])) {
			foreach($p['POS_LANG'] as $lKey => $lVal){
				if (isset($lVal['langAbrv'])){
					//lang_XXX is parameter from screen
					if (isset($p['PROD_CAT_'.strtoupper($lVal['langAbrv'])])
						&& is_array($p['PROD_CAT_'.strtoupper($lVal['langAbrv'])])){
						$langVextID  = 'lang_'.strtolower($lVal['langAbrv']);
						$fileName = '../app/modules/default/lang/lang_prodcat_'.strtolower($lVal['langAbrv']).'.php';
						if (file_exists($fileName)){
							$fileOri = file($fileName);
							$file = array();							
							//separate V_CAT lines from rest
							foreach ($fileOri as $fKey => $fValue) {
								if ($fKey < 10){
									array_push($file, $fValue);
								}
							}
							
							array_push($file, "\$lang['PROD_CAT'] = Array();\r\n");
							foreach ($p['PROD_CAT_'.strtoupper($lVal['langAbrv'])] as $vKey => $vValue){
								if (is_array($vValue) && isset($vValue['NAME']) && isset($vValue['KEYWORD'])){
									array_push($file, "\$lang['PROD_CAT'][".$vKey."]['NAME'] = '".$vValue['NAME']."';\r\n\$lang['PROD_CAT'][".$vKey."]['KEYWORD'] = '".$vValue['KEYWORD']."';\r\n");
								}
							}
							array_push($file, "?>");
												
							//write the new array into file
							$fp = fopen($fileName, 'w');
							if($fp != false){
								fwrite($fp, implode("", $file));		
								fclose($fp);			
							}
						}						
						
					}
				}
			}
		}
	}
	

	private function writeUpdateProdcatPropFileAction($p){
		//check if each possible language should be updated
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])) {
			foreach($p['POS_LANG'] as $lKey => $lVal){
				if (isset($lVal['langAbrv'])){
					//lang_XXX is parameter from screen
					if (isset($p['PROD_CAT_PROP_'.strtoupper($lVal['langAbrv'])])
						&& is_array($p['PROD_CAT_PROP_'.strtoupper($lVal['langAbrv'])])){
						//$langVextID  = 'lang_'.strtolower($lVal['langAbrv']);
						$fileName = '../app/modules/default/lang/lang_prodcatprop_'.strtolower($lVal['langAbrv']).'.php';
						if (file_exists($fileName)){
							$fileOri = file($fileName);
							$file = array();							
							//separate V_CAT lines from rest
							foreach ($fileOri as $fKey => $fValue) {
								if ($fKey < 10){
									array_push($file, $fValue);
								}
							}
							
							array_push($file, "\$lang['PROD_CAT_PROP'] = Array();\r\n");
							foreach ($p['PROD_CAT_PROP_'.strtoupper($lVal['langAbrv'])] as $vKey => $vValue){
								if (is_array($vValue) && isset($vValue['NAME'])){
									array_push($file, "\$lang['PROD_CAT_PROP'][".$vKey."]['NAME'] = '".$vValue['NAME']."';\r\n");
								}
							}
							array_push($file, "?>");
												
							//write the new array into file
							$fp = fopen($fileName, 'w');
							if($fp != false){
								fwrite($fp, implode("", $file));		
								fclose($fp);			
							}
						}						
						
					}
				}
			}
		}
	}
	
	public function prodcateditAction(){
		$p = $this -> getRequest() -> getParams();
		if (isset($p['pid'])){
			$prodCatID = $p['pid'];
			$prodCat = db_selProdCat(array('prodCatID' => $prodCatID));
			if (($prodCat != false) && is_array($prodCat) && (count($prodCat) > 0)){
				$prodCat = $prodCat[0];
				$posLang = db_selLang(array('langAbrv'=>'DE'));
				$prodCatParent = db_selProdCat(array('level' => ($prodCat['level']-1)
													, 'lftLEq' => $prodCat['lft']
													, 'rgtBEq' => $prodCat['rgt']
													));
				if (($prodCatParent != false) && is_array($prodCatParent) && (count($prodCatParent) > 0)){
					$prodCatParent = $prodCatParent[0];
					$prodCat['prodCatParent'] = $prodCatParent;
				}
				
				//Edit product category
				if (isset($p['prodCatEdit'])){
					$p['POS_LANG'] = $posLang;
					$p['prodCat'] = $prodCat;
					$ret = $this -> updateprodcatAction($p);
					if (is_array($ret) && isset($ret['ERROR']) && is_array($ret['ERROR'])
						&& (count($ret['ERROR']) > 0)){
						$this -> view -> error = $ret['ERROR'];		
					}elseif (is_array($ret) && isset($ret['INFO']) && is_array($ret['INFO'])
						&& (count($ret['INFO']) > 0)){
						$this -> view -> info = $ret['INFO'];
						$this -> view -> lang = $this -> lang;
						$prodCat = db_selProdCat(array('prodCatID' => $prodCatID));
						if (($prodCat != false) && is_array($prodCat) && (count($prodCat) > 0)){
							$prodCat = $prodCat[0];
							$prodCatParent = db_selProdCat(array('level' => ($prodCat['level']-1)
																, 'lftLEq' => $prodCat['lft']
																, 'rgtBEq' => $prodCat['rgt']
																));
							if (($prodCatParent != false) && is_array($prodCatParent) && (count($prodCatParent) > 0)){
								$prodCatParent = $prodCatParent[0];
								$prodCat['prodCatParent'] = $prodCatParent;
							}	
						}
						
						if (is_array($posLang)){
							foreach($posLang as $key => $value){
								if (isset($value['langAbrv'])){
									//prodCatName_XXX and prodCatKeyWord are parameters from screen
									if (isset($p['prodCatName_'.strtolower($value['langAbrv'])]) && isset($p['prodCatKeyWord_'.strtolower($value['langAbrv'])])){
										//read V_CAT from file
										$return = $this -> readprodcatfileAction(array('POS_LANG' => $value['langAbrv']));
										if (isset($return['return']['PROD_CAT_'.strtoupper($value['langAbrv'])])){
											$langProdCat = $return['return']['PROD_CAT_'.strtoupper($value['langAbrv'])];
											
											//update vcat name
											$langProdCat[$prodCatID]['NAME'] = $p['prodCatName_'.strtolower($value['langAbrv'])];
											$langProdCat[$prodCatID]['KEYWORD'] = $p['prodCatKeyWord_'.strtolower($value['langAbrv'])];
											$p['PROD_CAT_'.strtoupper($value['langAbrv'])] = $langProdCat;
										}
									}
								}
							}
							$this -> writeUpdateProdcatfileAction($p);
						}
					}
				}
				
				//Add Product property value
				if (isset($p['prodCatProp'])){
					$p['POS_LANG'] = $posLang;
					$p['prodCat'] = $prodCat;
					$ret = $this -> insertprodcatpropAction($p);
					if (is_array($ret) && isset($ret['ERROR']) && is_array($ret['ERROR'])
						&& (count($ret['ERROR']) > 0)){
						$this -> view -> error = $ret['ERROR'];		
					}
					if (is_array($ret) && isset($ret['INFO'])){
						$info = $ret['INFO'];
						$lang = $this -> lang;
						$this -> view -> lang = $lang;
					}	
				}
				
				//Selekt product category properties
				$prodCatProp = db_selProdCatProp(array('prodCatID' => $prodCat['prodCatID']
														//, 'print' => true
														));
														
				$this -> view -> prodCatProp = $prodCatProp;
				
				
				$this -> view -> prodCat = $prodCat;
				$this -> view -> prodCats = db_selProdCat();
				$this -> view -> posLang = $posLang;
				$this -> view -> params = $p;
			}else{
				$this -> _forward('prodcat');
			}
		}else {
			$this -> _forward('prodcat');
		}
	}
	
	private function insertprodcatpropAction($p = array()){		
		$ret = array('ERROR'=>array(), 'INFO' => array());
		$lang = $this -> lang;
		
		$isEmptyString = new FilterIsEmptyString();	
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			foreach($p['POS_LANG'] as $key => $posLang){
				if (is_array($posLang) && isset($posLang['langAbrv'])){
					$posLang = $posLang['langAbrv'];
	
					if (!isset($p['prodCatPropName_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatPropName_'.strtolower($posLang)]) == true)){					
						$ret['ERROR'][] = $lang['AERR_38'];
					}elseif (!isset($p['prodCatPropParent']) || ($isEmptyString->filter($p['prodCatPropParent']) == true)){
						$ret['ERROR'][] = $lang['AERR_39'];
					}
				}
			}
		}
		
		//check product category
		if (!isset($p['prodCat']) || !is_array($p['prodCat']) || !isset($p['prodCat']['prodCatID'])){
			$ret['ERROR'][] = $lang['AERR_41'];
		}
		
		//check product category property abreviation
		isset($p['prodCatPropAbrv']) ? $p['prodCatPropAbrv'] = substr($p['prodCatPropAbrv'], 0, 5):'';
		$prodCatPropAbrv = db_selProdCatProp(array('prodCatPropAbrv' => $p['prodCatPropAbrv']
													//, 'print' => true
													));
		if ( ($prodCatPropAbrv != false) || (is_array($prodCatPropAbrv) && (count($prodCatPropAbrv) >= 1)) ){
			$ret['ERROR'][] = $lang['AERR_42'];
		}elseif ($isEmptyString -> filter($p['prodCatPropAbrv']) == true){
			$ret['ERROR'][] = $lang['AERR_42'];
		}
		
		if (count($ret['ERROR']) <= 0){
			$prodCatID = $p['prodCat']['prodCatID'];
			//Fetch parent category
			if ($p['prodCatPropParent'] < 1){
				$p['prodCatPropParent'] = '1';
				$parentCatProp = db_selProdCatProp(array('prodCatPropID' => '1'
												//, 'print' => true
												));
				if ($parentCatProp == false){
					db_insProdCatProp(array('lft' => '1'
										, 'rgt' => '2'
										, 'active' => '1'
										, 'prodCatPropAbrv' => strtoupper('null')
										, 'prodCatID' => $prodCatID
										//, 'print' => true
										));
				}
			}
			$parentCatProp = db_selProdCatProp(array('prodCatPropID' => $p['prodCatPropParent']
											//, 'print'=>TRUE
											));				
			if( ($parentCatProp != false) && is_array($parentCatProp) && (count($parentCatProp) > 0)){	
				$parentCatProp = $parentCatProp[0];
				db_updProdCatProp(array(System_Properties::SQL_SET => array('incLft'=>2)
								, System_Properties::SQL_WHERE => array('lftBEq'=>$parentCatProp['rgt'])
									)
							);
				db_updProdCatProp(array(System_Properties::SQL_SET => array('incRgt'=>2)
								, System_Properties::SQL_WHERE => array('rgtBEq'=>$parentCatProp['rgt'])
									)
							);				
				$prodCatPropID = db_insProdCatProp(array('lft' => $parentCatProp['rgt']
												, 'rgt' => $parentCatProp['rgt'] + 1
												, 'active' => 1
												, 'prodCatPropAbrv' => strtoupper($p['prodCatPropAbrv'])
												, 'prodCatID' => $prodCatID	
												//, 'print'=> true
											));
											
				if( ($prodCatPropID != false) && is_numeric($prodCatPropID)){
					$ret['INFO'][] = $lang['AINFO_18'];
					$p['PROD_CAT_PROP_ID'] = $prodCatPropID;
					
					if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
						foreach($p['POS_LANG'] as $key => $posLang){
							if (is_array($posLang) && isset($posLang['langAbrv']) && isset($lang['LID'])
								&& (strtoupper($lang['LID']) == strtoupper($posLang['langAbrv']))){
								$posLang = $posLang['langAbrv'];
								if (isset($p['prodCatPropName_'.strtolower($posLang)]) && ($isEmptyString->filter($p['prodCatPropName_'.strtolower($posLang)]) != true)){
									$lang['PROD_CAT_PROP'][$prodCatPropID]['NAME'] = $p['prodCatPropName_'.strtolower($posLang)];
								}
								$this -> lang = $lang; 
							}
						}		
							
						/*
						foreach($p['POS_LANG'] as $key => $posLang){
							if (is_array($posLang) && isset($posLang['langAbrv'])){
								$posLang = $posLang['langAbrv'];
								$p['PROD_CAT_'.strtoupper($posLang)] = array();
								if (!isset($p['prodCatName_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatName_'.strtolower($posLang)]) != true)){
									$p['PROD_CAT_'.strtoupper($posLang)]['NAME'] = $p['prodCatName'];
								}
								
								if (!isset($p['prodCatKeyWord_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatKeyWord_'.strtolower($posLang)]) != true)){
									$p['PROD_CAT_'.strtoupper($posLang)]['KEYWORD'] = $p['prodCatKeyWord'];
								}
							}
						}*/
						$this -> writeProdCatPropFileAction($p);
					}
					
					//$this -> lang['PROD_CAT'][$p['vcatID']] = $p[$langProdcatID];
					//$this -> view -> lang = $this -> lang;
				}			
			}
		}
		return $ret;
	}
	
	
	private function writeProdCatPropFileAction($p){
		if (isset($p['PROD_CAT_PROP_ID']) && isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			//$posLang = db_selLang(array('langID'=>$p['lang']));
			foreach ($p['POS_LANG'] as $key => $posLang){
				if (is_array($posLang) && isset($posLang['langAbrv'])){
					$posLang = $posLang['langAbrv'];
					$prodCatPropName = '';
					$prodCatKeyWord = '';
					
					if (isset($p['prodCatPropName_'.strtolower($posLang)])){
						$prodCatPropName = $p['prodCatPropName_'.strtolower($posLang)];
					}
					
					$fileName = '../app/modules/default/lang/lang_prodcatprop_'.strtolower($posLang).'.php';
					if (file_exists($fileName)){
						$file = file($fileName);
						//separate PROD_CAT lines from rest
						foreach ($file as $key => $val) {
							$file[$key] = str_ireplace('?>', '', $val);
						}
						//push new element at th end of the V_CAT array
						array_push($file, "\r\n\$lang['PROD_CAT_PROP'][".$p['PROD_CAT_PROP_ID']."]['NAME'] = '".$prodCatPropName."';\r\n?>");
						
											
						//write the new array into file
						$fp = fopen($fileName, 'w');
						if($fp != false){
							fwrite($fp, implode("", $file));		
							fclose($fp);			
						}
					}
				}
			}
		}	
	}
	
	private function updateprodcatAction($p = array()){
		$ret = array('ERROR'=>array(), 'INFO' => array());
		$lang = $this -> lang;
				
		$isEmptyString = new FilterIsEmptyString();
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			foreach($p['POS_LANG'] as $key => $posLang){
				if (is_array($posLang) && isset($posLang['langAbrv'])){
					$posLang = $posLang['langAbrv'];
	
					if (!isset($p['prodCatName_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatName_'.strtolower($posLang)]) == true)){					
						$ret['ERROR'][] = $lang['AERR_38'];
					}elseif (!isset($p['prodCatParent']) || ($isEmptyString->filter($p['prodCatParent']) == true)){
						$ret['ERROR'][] = $lang['AERR_39'];
					}elseif (!isset($p['prodCatKeyWord_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatKeyWord_'.strtolower($posLang)]) == true)){
						$ret['ERROR'][]= $lang['AERR_40'];
					}
				}
			}
		}
		
		if ((count($ret['ERROR']) <= 0) && isset($p['prodCat'])){
			$prodCat = $p['prodCat'];
			$prodCatChild = false;
			//determine children from current car extra
			if (is_array($prodCat) && isset($prodCat['lft']) && isset($prodCat['rgt'])){
				$prodCatChild = db_selProdCat(array('lftBEq' => $prodCat['lft']
													, 'rgtLEq' => $prodCat['rgt']
													));
			}	
										
			//Fetch parent category
			if ($p['prodCatParent'] < 1){
				$p['prodCatParent'] = '1';
				$parentCat = db_selProdCat(array('prodCatID' => '1'
												//, 'print' => true
												));
				if ($parentCat == false){
					db_insProdCat(array('lft' => '1'
										, 'rgt' => '2'
										, 'active' => '1'));
				}
			}
			$parentCat = db_selProdCat(array('prodCatID' => $p['prodCatParent']
											//, 'print'=>TRUE
											));		
													
			if( ($parentCat != false) && is_array($parentCat) && (count($parentCat) > 0) && ($prodCatChild != false)){	
				$parentCat = $parentCat[0];
				
				if( ($parentCat['lft'] < $prodCat['lft']) || ($parentCat['rgt'] > $prodCat['rgt'])){
					//Set LFT and RGT to 0 of product category and their children 
					$lft = 0;
					db_updProdCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																		, 'rgt'=>$lft)
									, System_Properties::SQL_WHERE => array('lftBEq'=>$prodCat['lft']
																			, 'rgtLEq' => $prodCat['rgt'])));
																			
					//Erase model from actual parent model
					//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
					$lft = ($prodCat['children'] +1) *2;
					db_updProdCat(array(System_Properties::SQL_SET => array('decLft' => $lft)
										, System_Properties::SQL_WHERE => array('lftB' => $prodCat['rgt'])
										));			
					db_updProdCat(array(System_Properties::SQL_SET => array('decRgt' => $lft)
										, System_Properties::SQL_WHERE => array('rgtB' => $prodCat['rgt'])
										));	
					
					//select again car vextra parent					
					if ($parentCat['lft'] <= 1){
						$parentCatH = db_selProdCat(array('lft'=>'1'));
					}else{
						$parentCatH = db_selProdCat(array('prodCatID'=>$parentCat['prodCatID']));
					}
					
					if (($parentCatH != false) && is_array($parentCatH) && (count($parentCatH) > 0)){
						$parentCat = $parentCatH[0];
					}		

					
					//inc LFT and RGT values which are bigger than the the selected node RGT-value 
					$lft = ($prodCat['children'] +1) *2;
					db_updProdCat(array(System_Properties::SQL_SET => array('incLft' => $lft)
										, System_Properties::SQL_WHERE => array('lftBEq' => $parentCat['rgt'])
										));			
					db_updProdCat(array(System_Properties::SQL_SET => array('incRgt' => $lft)
										, System_Properties::SQL_WHERE => array('rgtBEq' => $parentCat['rgt'])
										));	
				
					$prodCatH = array();
					foreach($prodCatChild as $cat){
						$cat['diff'] = $cat['rgt'] - $cat['lft'];
						$prodCatH[$cat['lft']] = $cat;
					}
					ksort($prodCatH);
					$prodCatChild = $prodCatH;
					
					$lftStart = $parentCat['rgt'];
					
					$prevKey = null;
					foreach ($prodCatChild as $key=>$cat){
						//increment the left start value
						if ($prevKey != null){
							$lftStart += $key - $prevKey;
						}				
						
						$lft = $lftStart;
						$rgt = $lft + $cat['diff'];
						db_updProdCat(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$rgt)
										, System_Properties::SQL_WHERE => array('prodCatID'=>$cat['prodCatID'])
										));				
						$prevKey = $key;										
					}
					
					if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
						$prodCatID = $prodCat['prodCatID'];
						foreach($p['POS_LANG'] as $key => $posLang){
							if (is_array($posLang) && isset($posLang['langAbrv']) && isset($lang['LID'])
								&& (strtoupper($lang['LID']) == strtoupper($posLang['langAbrv']))){
								$posLang = $posLang['langAbrv'];
								if (isset($p['prodCatName_'.strtolower($posLang)]) && ($isEmptyString->filter($p['prodCatName_'.strtolower($posLang)]) != true)){
									$lang['PROD_CAT'][$prodCatID]['NAME'] = $p['prodCatName_'.strtolower($posLang)];
								}
								
								if (isset($p['prodCatKeyWord_'.strtolower($posLang)]) && ($isEmptyString->filter($p['prodCatKeyWord_'.strtolower($posLang)]) != true)){
									$lang['PROD_CAT'][$prodCatID]['KEYWORD'] = $p['prodCatKeyWord_'.strtolower($posLang)];
								}
								$this -> lang = $lang; 
							}
						}
					}
					
					$ret['INFO'][] = $lang['AINFO_19'];
				}else{
					$ret['ERROR'][] = $lang['AERR_39'];
				}		
			}else{
				$ret['ERROR'][] = $lang['AERR_39'];
			}
		}
		return $ret;
	}
	
	public function prodcatpropeditAction(){
		$p = $this -> getRequest() -> getParams();
		
		if (isset($p['ppid'])){
			$prodCatPropID = $p['ppid'];
			$prodCatProp = db_selProdCatProp(array('prodCatPropID' => $prodCatPropID));
			if (is_array($prodCatProp) && (count($prodCatProp) > 0)){
				$prodCatProp = $prodCatProp[0];				
				$posLang = db_selLang(array('langAbrv'=>'DE'));
				$prodCatPropParents = db_selProdCatProp(array('prodCatID' => $prodCatProp['prodCatID']));
				
				$prodCatPropParent  = db_selProdCatProp(array('lftLE' => $prodCatProp['lft']
															, 'rgtBE' => $prodCatProp['rgt']
															, 'level' => ($prodCatProp['level']-1)
															//, 'print' => true
																));
				if (($prodCatPropParent != false) && is_array($prodCatPropParent) && (count($prodCatPropParent) > 0)){
					$prodCatPropParent = $prodCatPropParent[0];
					$prodCatProp['prodCatPropParent'] = $prodCatPropParent;
				}
				
				//Edit prodCatProp
				if (isset($p['prodCatPropEdit'])){				
					$p['POS_LANG'] = $posLang;
					$p['prodCatProp'] = $prodCatProp;
					$ret = $this -> updateprodcatpropAction($p);
					if (is_array($ret) && isset($ret['ERROR']) && is_array($ret['ERROR'])
						&& (count($ret['ERROR']) > 0)){
						$this -> view -> error = $ret['ERROR'];		
					}elseif (is_array($ret) && isset($ret['INFO']) && is_array($ret['INFO'])){
						if (isset($p['prodCatPropAbrv'])){
							db_updProdCatProp(array(System_Properties::SQL_SET => array('prodCatPropAbrv' => $p['prodCatPropAbrv'])
												, System_Properties::SQL_WHERE => array('prodCatPropID' => $prodCatProp['prodCatPropID'])
											));
							
						}
						
					
						$this -> view -> info = $ret['INFO'];
						$this -> view -> lang = $this -> lang;
						$prodCatProp = db_selProdCatProp(array('prodCatPropID' => $prodCatPropID));
						if (($prodCatProp != false) && is_array($prodCatProp) && (count($prodCatProp) > 0)){
							$prodCatProp = $prodCatProp[0];
							$prodCatPropParent = db_selProdCatProp(array('level' => ($prodCatProp['level']-1)
																, 'lftLEq' => $prodCatProp['lft']
																, 'rgtBEq' => $prodCatProp['rgt']
																));
							if (($prodCatPropParent != false) && is_array($prodCatPropParent) && (count($prodCatPropParent) > 0)){
								$prodCatPropParent = $prodCatPropParent[0];
								$prodCatProp['prodCatPropParent'] = $prodCatPropParent;
							}	
						}
						
						if (is_array($posLang)){
							foreach($posLang as $key => $value){ 
								if (isset($value['langAbrv'])){
									//prodCatPropName_XXX 
									if (isset($p['prodCatPropName_'.strtolower($value['langAbrv'])])){
										//read PROD_CAT_PROP_NAME from file
										$return = $this -> readprodcatpropfileAction(array('POS_LANG' => $value['langAbrv']));
										if (isset($return['return']['PROD_CAT_PROP_'.strtoupper($value['langAbrv'])])){
											$langProdCatProp = $return['return']['PROD_CAT_PROP_'.strtoupper($value['langAbrv'])];
											
											//update vcat name
											$langProdCatProp[$prodCatPropID]['NAME'] = $p['prodCatPropName_'.strtolower($value['langAbrv'])];
											$p['PROD_CAT_PROP_'.strtoupper($value['langAbrv'])] = $langProdCatProp;
										}
									}
								}
							}
							$this -> writeUpdateProdcatPropFileAction($p);
						}
						
					}
				}
				
				$this -> view -> posLang = $posLang;
				$this -> view -> prodCatProp = $prodCatProp;
				$this -> view -> prodCatPropParents = $prodCatPropParents;
			}else{
				$this -> _forward('prodcat');
			}
		}
		else{
			$this -> _forward('prodcat');
		}
	}
	
	private function updateprodcatpropAction($p){
		$ret = array('ERROR'=>array(), 'INFO' => array());
		$lang = $this -> lang;
				
		$isEmptyString = new FilterIsEmptyString();
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			foreach($p['POS_LANG'] as $key => $posLang){
				if (is_array($posLang) && isset($posLang['langAbrv'])){
					$posLang = $posLang['langAbrv'];
	
					if (!isset($p['prodCatPropName_'.strtolower($posLang)]) || ($isEmptyString->filter($p['prodCatPropName_'.strtolower($posLang)]) == true)){					
						$ret['ERROR'][] = $lang['AERR_38'];
					}
				}
			}
		}
		
		if ((count($ret['ERROR']) <= 0) && isset($p['prodCatProp'])){
			$prodCatProp = $p['prodCatProp'];
			$prodCatPropChild = false;
			//determine children from current car extra
			if (is_array($prodCatProp) && isset($prodCatProp['lft']) && isset($prodCatProp['rgt'])){
				$prodCatPropChild = db_selProdCatProp(array('lftBEq' => $prodCatProp['lft']
															, 'rgtLEq' => $prodCatProp['rgt']
															));
			}	
										
			//Fetch parent category
			if ($p['prodCatPropParent'] < 1){
				$p['prodCatPropParent'] = '1';
				$parentCatProp = db_selProdCatProp(array('prodCatPropID' => '1'
												//, 'print' => true
												));
				if ($parentCatProp == false){
					db_insProdCatProp(array('lft' => '1'
										, 'rgt' => '2'
										, 'active' => '1'));
				}
			}
			$parentCatProp = db_selProdCatProp(array('prodCatPropID' => $p['prodCatPropParent']
											//, 'print'=>TRUE
											));		
													
			if( ($parentCatProp != false) && is_array($parentCatProp) && (count($parentCatProp) > 0) && ($prodCatPropChild != false)){	
				$parentCatProp = $parentCatProp[0];
				
				if( ($parentCatProp['lft'] < $prodCatProp['lft']) || ($parentCatProp['rgt'] > $prodCatProp['rgt'])){
					//Set LFT and RGT to 0 of product category and their children 
					$lft = 0;
					db_updProdCatProp(array(System_Properties::SQL_SET => array('lft'=>$lft
																				, 'rgt'=>$lft)
									, System_Properties::SQL_WHERE => array('lftBEq'=>$prodCatProp['lft']
																			, 'rgtLEq' => $prodCatProp['rgt'])));
																			
					//Erase model from actual parent model
					//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
					$lft = ($prodCatProp['children'] +1) *2;
					db_updProdCatProp(array(System_Properties::SQL_SET => array('decLft' => $lft)
										, System_Properties::SQL_WHERE => array('lftB' => $prodCatProp['rgt'])
										));			
					db_updProdCatProp(array(System_Properties::SQL_SET => array('decRgt' => $lft)
										, System_Properties::SQL_WHERE => array('rgtB' => $prodCatProp['rgt'])
										));	
					
					//select again car vextra parent					
					if ($parentCatProp['lft'] <= 1){
						$parentCatPropH = db_selProdCatProp(array('lft'=>'1'));
					}else{
						$parentCatPropH = db_selProdCatProp(array('prodCatPropID'=>$parentCatProp['prodCatID']));
					}
					
					if (($parentCatPropH != false) && is_array($parentCatPropH) && (count($parentCatPropH) > 0)){
						$parentCatProp = $parentCatPropH[0];
					}		

					
					//inc LFT and RGT values which are bigger than the the selected node RGT-value 
					$lft = ($prodCatProp['children'] +1) *2;
					db_updProdCatProp(array(System_Properties::SQL_SET => array('incLft' => $lft)
										, System_Properties::SQL_WHERE => array('lftBEq' => $parentCatProp['rgt'])
										));			
					db_updProdCatProp(array(System_Properties::SQL_SET => array('incRgt' => $lft)
										, System_Properties::SQL_WHERE => array('rgtBEq' => $parentCatProp['rgt'])
										));	
				
					$prodCatPropH = array();
					foreach($prodCatPropChild as $catProp){
						$catProp['diff'] = $catProp['rgt'] - $catProp['lft'];
						$prodCatPropH[$catProp['lft']] = $catProp;
					}
					ksort($prodCatPropH);
					$prodCatPropChild = $prodCatPropH;
					
					$lftStart = $parentCatProp['rgt'];
					
					$prevKey = null;
					foreach ($prodCatPropChild as $key=>$catProp){
						//increment the left start value
						if ($prevKey != null){
							$lftStart += $key - $prevKey;
						}				
						
						$lft = $lftStart;
						$rgt = $lft + $catProp['diff'];
						db_updProdCatProp(array(System_Properties::SQL_SET => array('lft'=>$lft
																			, 'rgt'=>$rgt)
										, System_Properties::SQL_WHERE => array('prodCatPropID'=>$catProp['prodCatPropID'])
										));				
						$prevKey = $key;										
					}
					
					if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
						$prodCatPropID = $prodCatProp['prodCatPropID'];
						foreach($p['POS_LANG'] as $key => $posLang){
							if (is_array($posLang) && isset($posLang['langAbrv']) && isset($lang['LID'])
								&& (strtoupper($lang['LID']) == strtoupper($posLang['langAbrv']))){
								$posLang = $posLang['langAbrv'];
								if (isset($p['prodCatPropName_'.strtolower($posLang)]) && ($isEmptyString->filter($p['prodCatPropName_'.strtolower($posLang)]) != true)){
									$lang['PROD_CAT_PROP'][$prodCatPropID]['NAME'] = $p['prodCatPropName_'.strtolower($posLang)];
								}
								$this -> lang = $lang; 
							}
						}
					}
					
					$ret['INFO'][] = $lang['AINFO_19'];
				}else{
					$ret['ERROR'][] = $lang['AERR_39'];
				}		
			}else{
				$ret['ERROR'][] = $lang['AERR_39'];
			}
		}
		return $ret;
	}
}
?>