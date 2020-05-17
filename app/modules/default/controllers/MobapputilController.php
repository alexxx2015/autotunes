<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for INDEX
 *********************************************************************************/
include_once('classes/AbstractController.php');
include_once('modules/default/views/filters/FilterEncUTF8.php');

class MobapputilController extends AbstractController{
	
	public function preDispatch(){
		parent::preDispatch();	
		
		$this -> view -> tmpl = $this->tmpl;
		$this -> view -> lang = $this -> lang;	
	}
	
	//returns the list with all brands in the system
	public function loadcarbrandAction(){
		include_once('default/models/car/db_selCarBrand.php');		
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		$carBrand = db_selCarBrand();
		$return = array();
		if(is_array($carBrand)){
			foreach($carBrand as $key => $kVal){
				if(isset($kVal['brandName'])){
					array_push($return, array($kVal['carBrandID'],$kVal['brandName']));
				}
			}
		}
		$return = Zend_Json::encode($return);
		$this -> getResponse() -> setBody($return);
	}

	//returns the list with all brands in the system
	public function loadtruckbrandAction(){
		include_once('default/models/truck/db_selTruckBrand.php');
		$this -> getFrontController() -> setParam('noViewRenderer', true);
	
		$truckBrand = db_selTruckBrand();
		$return = array();
		if(is_array($truckBrand)){
			foreach($truckBrand as $key => $kVal){
				if(isset($kVal['brandName'])){
					array_push($return, array($kVal['truckBrandID'],$kVal['brandName']));
				}
			}
		}
		$return = Zend_Json::encode($return);
		$this -> getResponse() -> setBody($return);
	}
	
	public function loadbikebrandAction(){
		include_once('default/models/bike/db_selBikeBrand.php');		
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		$bikeBrand = db_selBikeBrand();
		$return = array();
		if(is_array($bikeBrand)){
			foreach($bikeBrand as $key => $kVal){
				if(isset($kVal['brandName'])){
					array_push($return, array($kVal['bikeBrandID'],$kVal['brandName']));
				}
			}
		}
		$return = Zend_Json::encode($return);
		$this -> getResponse() -> setBody($return);
	}
	
	//returns the list with all model to a brand
	public function loadcarmodelAction(){
		include_once('default/models/car/db_selCarModel.php');		
		include_once('default/models/car/db_selCarBrand.php');	
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		$p = $this -> getRequest() -> getParams();
		if(isset($p['cid'])){
			$cid = $p['cid'];
			$carBrand = db_selCarBrand(array('carBrandID' => $cid));
			if($carBrand != false){
				$carModel = db_selCarModel(array('carBrandID' => $cid));
				$return = array();
				if(is_array($carModel)){
					foreach($carModel as $key => $kVal){
						if(isset($kVal['carModelName'])){
							array_push($return, array($kVal['carModelID'],$kVal['carModelName']));
						}
					}
				}
				$return = Zend_Json::encode($return);
				$this -> getResponse() -> setBody($return);
// 				$this -> getResponse() -> setHeader('Content-Length',strlen($return));
				$this -> getResponse() -> setHeader('Content-type','application/json');
			}
		}					
	}


	//returns the list with all model to a brand
	public function loadtruckmodelAction(){
		include_once('default/models/truck/db_selTruckModel.php');
		include_once('default/models/truck/db_selTruckBrand.php');
		$this -> getFrontController() -> setParam('noViewRenderer', true);
	
		$p = $this -> getRequest() -> getParams();
		if(isset($p['cid'])){
			$cid = $p['cid'];
			$truckBrand = db_selTruckBrand(array('truckBrandID' => $cid));
			if($truckBrand != false){
				$truckModel = db_selTruckModel(array('truckBrandID' => $cid));
				$return = array();
				if(is_array($truckModel)){
					foreach($truckModel as $key => $kVal){
						if(isset($kVal['truckModelName'])){
							array_push($return, array($kVal['truckModelID'],$kVal['truckModelName']));
						}
					}
				}
				$return = Zend_Json::encode($return);
				$this -> getResponse() -> setBody($return);
				// 				$this -> getResponse() -> setHeader('Content-Length',strlen($return));
				$this -> getResponse() -> setHeader('Content-type','application/json');
			}
		}
	}
	
	//returns the list with all model to a brand
	public function loadbikemodelAction(){
		include_once('default/models/bike/db_selBikeModel.php');		
		include_once('default/models/bike/db_selBikeBrand.php');	
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		$p = $this -> getRequest() -> getParams();
		if(isset($p['bid'])){
			$bid = $p['bid'];
			$bikeBrand = db_selBikeBrand(array('bikeBrandID' => $bid));
			if($bikeBrand != false){
				$bikeModel = db_selBikeModel(array('bikeBrandID' => $bid));
				$return = array();
				if(is_array($bikeModel)){
					foreach($bikeModel as $key => $kVal){
						if(isset($kVal['bikeModelName'])){
							array_push($return, array($kVal['bikeModelID'],$kVal['bikeModelName']));
						}
					}
				}
				$return = Zend_Json::encode($return);
				
				$this -> getResponse() -> setBody($return);
// 				$this -> getResponse() -> setHeader('Content-Length',strlen($return));
				$this -> getResponse() -> setHeader('Content-type','application/json');
			} 	
		}					
	}
	
	//Load all vehicle states
	public function loadvstateAction(){
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$return = array('r' => false, 'vs' => false);
		$lang = $this -> lang;
		if(isset($lang['V_STATE'])){
			$vState = array();
			foreach($lang['V_STATE'] as $key => $state){
				array_push($vState, array("k" => $key, "v" => $state));
			}
			if(count($vState) > 0){
				$return['vs'] = $vState;
				$return['r'] = true;
			}
		}
		$return = Zend_Json::encode($return);
		$this -> getResponse() -> setBody($return);
	}
	
	public function loaddataAction(){
		include_once('default/models/car/db_selCarCat.php');
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		$lang = $this -> lang;
		
		if(isset($lang['V_CAT']) && is_array($lang['V_CAT'])){
			foreach($lang['V_CAT'] as $key => $kVal){
				$lang['V_CAT'][$key] = array('t' => $kVal, 'i'=>'', 's'=>array());
			}
		
			$dbCarCat = db_selCarCat();
			if(($dbCarCat != null) && is_array($dbCarCat) && (count($dbCarCat) > 0)){
// 				$carCat = $lang['V_CAT'];
				foreach($dbCarCat as $key => $kVal){
					if(isset($kVal['vcatID']) && isset($lang['V_CAT'][$kVal['vcatID']])){
						$lang['V_CAT'][$kVal['vcatID']]['i'] = $kVal['carCatID'];
						array_push($lang['V_CAT'][$kVal['vcatID']]['s'], System_Properties::CAR_ABRV);
					}
				}
			}
		}
			
		$return = array('r' => true, 'l' => $lang);		
		
		$return = Zend_Json::encode($return);
		$this -> getResponse() -> setBody($return);
		$this -> getResponse() -> setHeader('Content-type', 'application/json', true);	
	}	
// 		if(isset($lang['TXT_33'])){
// 			$return['l']['TXT_33'] = $lang['TXT_33'];
// 		}		
// 		if(isset($lang['TXT_70'])){
// 			$return['l']['TXT_70'] = $lang['TXT_70'];
// 		}		
// 		if(isset($lang['TXT_72'])){
// 			$return['l']['TXT_72'] = $lang['TXT_72'];
// 		}		
// 		if(isset($lang['TXT_74'])){
// 			$return['l']['TXT_74'] = $lang['TXT_74'];
// 		}		
// 		if(isset($lang['TXT_75'])){
// 			$return['l']['TXT_75'] = $lang['TXT_75'];
// 		}		
// 		if(isset($lang['V_EEK'])){
// 			$return['l']['V_EEK'] = $lang['V_EEK'];
// 		}		
// 		if(isset($lang['V_MWST'])){
// 			$return['l']['V_MWST'] = $lang['V_MWST'];
// 		}		
// 		if(isset($lang['V_FUEL'])){
// 			$return['l']['V_FUEL'] = $lang['V_FUEL'];
// 		}		
// 		if(isset($lang['V_STATE'])){
// 			$return['l']['V_STATE'] = $lang['V_STATE'];
// 		}		
// 		if(isset($lang['V_CLR'])){
// 			$return['l']['V_CLR'] = $lang['V_CLR'];
// 		}		
// 		if(isset($lang['V_SHIFT'])){
// 			$return['l']['V_SHIFT'] = $lang['V_SHIFT'];
// 		}		
// 		if(isset($lang['V_EMISSION_NORM'])){
// 			$return['l']['V_EMISSION_NORM'] = $lang['V_EMISSION_NORM'];
// 		}		
// 		if(isset($lang['V_ECOLOGIC_TAG'])){
// 			$return['l']['V_ECOLOGIC_TAG'] = $lang['V_ECOLOGIC_TAG'];
// 		}		
// 		if(isset($lang['V_KLIMA'])){
// 			$return['l']['V_KLIMA'] = $lang['V_KLIMA'];
// 		}
// 		$utf8 = new FilterEncUTF8();
// 		$return = $utf8->filter($return);
		
// 	public function loadcatAction(){
// 		$this -> getFrontController() -> setParam('noViewRenderer', true);
// 		$p = $this -> getRequest() -> getParams();
// 		$lang = $this -> lang;
// 		$return = array('r' => true, 'c' => array());
// 		if(isset($p['cat'])){
// 			$cat = strtolower($p['cat']);
// 			if($cat == System_Properties::CAR_ABRV){
// 				include_once('default/models/car/db_selCarCat.php');
// 				$dbCarCat = db_selCarCat();
// 				if(($dbCarCat != null) && is_array($dbCarCat) && (count($dbCarCat) > 0)){
// 					foreach($dbCarCat as $key => $kVal){
// 						if(isset($kVal['vcatID']) && isset($lang['V_CAT'][$kVal['vcatID']]))
// 						array_push($return['c'], array('cci' => $kVal['carCatID'], 'ccv' => $lang['V_CAT'][$kVal['vcatID']]));
// 					}
// 				}		
// 			}
// 		}
// 		$return = Zend_Json::encode($return);
// 		$this -> getResponse() -> setBody($return);
// 		$this -> getResponse() -> setHeader('Content-type', 'application/json', true); 
// 	}
}
?>