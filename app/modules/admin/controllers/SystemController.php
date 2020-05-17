<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20101011
 * Desc:		This controller is processed when a user is not logged in
 *******************************************************************************/
include_once('classes/AbstractController.php');
include_once('Zend/Session/Namespace.php');		

include_once('default/views/filters/FilterIsEmptyString.php');

class Admin_SystemController extends AbstractController{
	
	public function preDispatch(){
		parent::preDispatch();		
		
			
		if (!isset($this -> adminNS -> adminData)
			|| !isset($this -> adminNS -> adminLogged)
			|| ($this -> adminNS -> adminLogged != true)) {
			$this -> _forward('index', 'notlogged');
		}
		
		$action = $this -> getRequest() -> getActionName();
		$req = $this -> getRequest();
		//Check Authority for "ADMIN_SYS_EDIT" 
		if( ($action == 'system')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::ADMIN_SYS_EDIT
														)) != true)
													){
			$this -> _forward('index');
		}
		//Check authority for "BRAND_CREATE" 
		elseif ( (($action == 'brandnew') || ($action == 'brandidx'))
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BRAND_CREATE
														)) != true) ){
			$this -> _forward('index');
		}
		//Check authority for "BRAND_EDIT" 
		elseif ( ($action == 'brandedit')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BRAND_EDIT
														)) != true) ){
			$this -> _forward('index');
		}
		//Check authority for "BRAND_DELETE" 
		elseif ( ($action == 'branderase')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BRAND_DELETE
														)) != true) ){
			$this -> _forward('index');
		}
		
		//check authority for 'CAR_MODEL_CREATE'
		elseif ( ($action == 'carbrandmodel')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_MODEL_CREATE
														)) != true) ){
			$this -> _forward('index');
		}		
		//check authority for 'CAR_MODEL_EDIT'
		elseif ( ($action == 'carbrandmodeledit')
				&& ( (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_MODEL_EDIT
														)) != true)
					|| (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::CAR_MODEL_DELETE
														)) != true)) ){
			$this -> _forward('index');
		}
		
		//check authority for 'BIKE_MODEL_CREATE'
		elseif ( ($action == 'bikebrandmodel')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_MODEL_CREATE
														)) != true) ){
			$this -> _forward('index');
		}		
		//check authority for 'BIKE_MODEL_EDIT'
		elseif ( ($action == 'bikebrandmodeledit')
				&& ( (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_MODEL_EDIT
														)) != true)
					|| (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::BIKE_MODEL_DELETE
														)) != true)) ){
			$this -> _forward('index');
		}
		
		//check authority for 'TRUCK_MODEL_CREATE'
		elseif ( ($action == 'truckbrandmodel')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_MODEL_CREATE
														)) != true) ){
			$this -> _forward('index');
		}		
		//check authority for 'TRUCK_MODEL_EDIT'
		elseif ( ($action == 'truckbrandmodeledit')
				&& ( (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_MODEL_EDIT
														)) != true)
					|| (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::TRUCK_MODEL_DELETE
														)) != true)) ){
			$this -> _forward('index');
		}
	
		//check authorit VEXT_CREATE
		elseif ( ($action == 'vext')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::VEXT_CREATE
														)) != true) ){
			$this -> _forward('index');
		}
		//check authority VEXT_EDIT
		elseif ( ($action == 'vextedit')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::VEXT_EDIT
														)) != true) ){
			$this -> _forward('index');
		}
		//check authorit VEXT_DELETE
		elseif ( ($action == 'vexterase')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::VEXT_DELETE
														)) != true) ){
			$this -> _forward('index');
		}
	
		//check authorit VCAT_CREATE
		elseif ( ($action == 'vcat')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::VCAT_CREATE
														)) != true) ){
			$this -> _forward('index');
		}
		//check authority VCAT_EDIT
		elseif ( ($action == 'vcatedit')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::VCAT_EDIT
														)) != true) ){
			$this -> _forward('index');
		}
		//check authority VCAT_DELETE
		elseif ( ($action == 'vcaterase')
				&& (System_Properties::checkAuthority(array('userAuth' => $this -> adminNS -> adminData['userAuthority']
														, 'desAuth' => System_Authority::VCAT_DELETE
														)) != true) ){
			$this -> _forward('index');
		}
		
		$this -> view -> tmpl = $this->tmpl;
		$this -> view -> lang = $this -> lang;	
	}
	
	public function indexAction(){
		
	}
	
	/**
	 * This action controller function afford the brand management
	 */
	public function brandidxAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');		

		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		
		$req = $this -> getRequest();
		//Safe new brand
		if ($req -> __isset('brandSafe')){
			$this -> insertbrandAction();
		}
		//Edit new brand
		elseif ($req -> __isset('brandEdit')){
			$this -> updatebrandAction();
		}
		
		$this -> view -> brand = db_selBrand(array('orderby' => array( array('col' => 'brandName'))));
		
		$carCat = db_selCarCat();
		if (is_array($carCat)){
			$catNew = array();
			foreach ($carCat as $key => $kVal){
				if ($kVal['children'] >= 1){
					array_push($catNew, $kVal);
				}
			}
			$carCat = $catNew;
		}
		$this -> view -> carCat = $carCat;
		
		$bikeCat = db_selBikeCat();
		if (is_array($bikeCat)){
			$catNew = array();
			foreach ($bikeCat as $key => $kVal){
				if ($kVal['children'] >= 1){
					array_push($catNew, $kVal);
				}
			}
			$bikeCat = $catNew;
		}
		$this -> view -> bikeCat = $bikeCat;
		
		$truckCat = db_selTruckCat();
		if (is_array($truckCat)){
			$catNew = array();
			foreach ($truckCat as $key => $kVal){
				if ($kVal['children'] >= 1){
					array_push($catNew, $kVal);
				}
			}
			$truckCat = $catNew;
		}
		$this -> view -> truckCat = $truckCat;		
	}
	
	private function insertbrandAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_insBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckCat.php');
		
		include_once('default/views/filters/FilterString50.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCarBrand2Cat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBikeBrand2Cat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruckBrand2Cat.php');
		
		$p = $this -> getRequest() -> getParams();
		$fString50 = new FilterString50();
		$lang = $this -> lang;
		
		if (!isset($p['brandName']) || ($fString50 -> isValid($p['brandName']) == false)){
			$this -> view -> error = $lang['AERR_21'];
		}
		else if(db_selBrand(array('brandName' => $p['brandName'])) != false){
			$this -> view -> error = $lang['AERR_23'];
		} 
		else{
			$brandID = db_insBrand($p);
			if (($brandID != false) && is_numeric($brandID)){
				$p['active'] = 0;
				//activate brand for cars
				if (isset($p['carBrandActive'])){
					$p['active'] = 1;
				}
				$carBrandID = db_insCarBrand(array('brandID' => $brandID, 'active' => $p['active']));			
					
				//check car categories
				if (isset($p['carCat']) && is_numeric($carBrandID) && ($p['active'] == 1)){
					if (in_array('-1', $p['carCat'])){
						$carCat = db_selCarCat(array('lft' => 1));	
						if ($carCat == false){
							db_insCarCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
							$carCat = db_selCarCat(array('lft'=>1));
						}									
					}else{
						$carCat = db_selCarCat(array('carCatID' => $p['carCat']));	
					}
					
					if (is_array($carCat)){
						$catNew = array();
						foreach($carCat AS $key => $kVal){
							if ((isset($kVal['children']) && ($kVal['children'] >= 1))
								|| ($kVal['lft'] == 1) ){
								array_push($catNew, $kVal);
							}
						}
						$carCat = $catNew;
						if (count($carCat) > 0){						
							db_delCarBrand2Cat(array('carBrandID' => $carBrandID));
						
							foreach($carCat AS $key => $kVal){
								db_insCarBrand2Cat(array('carBrandID' => $carBrandID
														, 'carCatID' => $kVal['carCatID']
														));
							}
						}
					}
				}
				
				$p['active'] = 0;
				if (isset($p['bikeBrandActive'])){
					$p['active'] = 1;
				}
				$bikeBrandID = db_insBikeBrand(array('brandID' => $brandID, 'active' => $p['active']));					
					
				//check bike categories
				if (isset($p['bikeCat']) && is_numeric($bikeBrandID) && ($p['active'] == 1)){
					if (in_array('-1', $p['bikeCat'])){
						$bikeCat = db_selBikeCat(array('lft' => 1));
						if ($bikeCat == false){
							db_insBikeCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
							$bikeCat = db_selBikeCat(array('lft'=>1));
						}								
					}else{
						$bikeCat = db_selBikeCat(array('bikeCatID' => $p['bikeCat']));	
					}
					
					if (is_array($bikeCat)){
						$catNew = array();
						foreach($bikeCat AS $key => $kVal){
							if ((isset($kVal['children']) && ($kVal['children'] >= 1))
								|| ($kVal['lft'] == 1) ){
								array_push($catNew, $kVal);
							}
						}
						$bikeCat = $catNew;
						if (count($bikeCat) > 0){			
							db_delBikeBrand2Cat(array('bikeBrandID' => $bikeBrandID));
						
							foreach($bikeCat AS $key => $kVal){
								db_insBikeBrand2Cat(array('bikeBrandID' => $bikeBrandID
														, 'bikeCatID' => $kVal['bikeCatID']
														));
							}
						}
					}
				}
				
				$p['active'] = 0;
				if (isset($p['truckBrandActive'])){
					$p['active'] = 1;
				}
				$truckBrandID = db_insTruckBrand(array('brandID' => $brandID, 'active' => $p['active']));					
					
				//check truck categories
				if (isset($p['truckCat']) && is_numeric($truckBrandID) && ($p['active'] == 1)){
					if (in_array('-1', $p['truckCat'])){
						$truckCat = db_selTruckCat(array('lft' => 1));
						if ($truckCat == false){
							db_insTruckCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
							$truckCat = db_selTruckCat(array('lft'=>1));
						}								
					}else{
						$truckCat = db_selTruckCat(array('truckCatID' => $p['truckCat']));	
					}
					
					if (is_array($truckCat)){
						$catNew = array();
						foreach($truckCat AS $key => $kVal){
							if ((isset($kVal['children']) && ($kVal['children'] >= 1))
								|| ($kVal['lft'] == 1) ){
								array_push($catNew, $kVal);
							}
						}
						
						$truckCat = $catNew;
						if (count($truckCat) > 0){			
							db_delTruckBrand2Cat(array('truckBrandID' => $truckBrandID));
						
							foreach($truckCat AS $key => $kVal){
								db_insTruckBrand2Cat(array('truckBrandID' => $truckBrandID
														, 'truckCatID' => $kVal['truckCatID']
														));
							}
						}
					}
				}
				
				$this -> view -> info = $lang['AINFO_6'];
			}
			else{
				$this -> view -> error = $lang['AERR_22'];
			}
		}
	}
	
	public function brandeditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');

		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');

		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand2Cat.php');
		
		$req = $this -> getRequest();
		$p = $req -> getParams();
		if (isset($p['bid'])){
			$brandDetails = db_selBrand(array('brandID' => $p['bid']));
			if ($brandDetails != false){
				$brandDetails = $brandDetails[0];
				
				//Delete brand
				if (isset($p['brandDel1'])){
					$this -> branddel1Action(array('brandID' => $p['bid']));
				}
				
				$carCat = db_selCarCat();
				if (is_array($carCat)){
					$catNew = array();
					foreach ($carCat as $key => $kVal){
						if ($kVal['children'] >= 1){
							array_push($catNew, $kVal);
						}
					}
					$carCat = $catNew;
				}
				$this -> view -> carCat = $carCat;
				
				$carCatActive = null;
				if (isset($brandDetails['carBrand']) && ($brandDetails['carBrand'] == 1)){
					$carBrand = db_selCarBrand(array('brandID' => $brandDetails['brandID']));
					if (is_array($carBrand) && (count($carBrand) > 0)){
						$carBrand = $carBrand[0]; 
						$carCatActive = db_selCarBrand2Cat(array('carBrandID' => $carBrand['carBrandID']));
					}
				}
				$this -> view -> carCatActive = $carCatActive;
				
				$bikeCat = db_selBikeCat();
				if (is_array($bikeCat)){
					$catNew = array();
					foreach ($bikeCat as $key => $kVal){
						if ($kVal['children'] >= 1){
							array_push($catNew, $kVal);
						}
					}
					$bikeCat = $catNew;
				}
				$this -> view -> bikeCat = $bikeCat;
				
				$bikeCatActive = null;
				if (isset($brandDetails['bikeBrand']) && ($brandDetails['bikeBrand'] == 1)){
					$bikeBrand = db_selBikeBrand(array('brandID' => $brandDetails['brandID']));
					if (is_array($bikeBrand) && (count($bikeBrand) > 0)){
						$bikeBrand = $bikeBrand[0]; 
						$bikeCatActive = db_selBikeBrand2Cat(array('bikeBrandID' => $bikeBrand['bikeBrandID']));
					}
				}
				$this -> view -> bikeCatActive = $bikeCatActive;
				
				$truckCat = db_selTruckCat();
				if (is_array($truckCat)){
					$catNew = array();
					foreach ($truckCat as $key => $kVal){
						if ($kVal['children'] >= 1){
							array_push($catNew, $kVal);
						}
					}
					$truckCat = $catNew;
				}
				$this -> view -> truckCat = $truckCat;
				
				$truckCatActive = null;
				if (isset($brandDetails['truckBrand']) && ($brandDetails['truckBrand'] == 1)){
					$truckBrand = db_selTruckBrand(array('brandID' => $brandDetails['brandID']));
					if (is_array($truckBrand) && (count($truckBrand) > 0)){
						$truckBrand = $truckBrand[0]; 
						$truckCatActive = db_selTruckBrand2Cat(array('truckBrandID' => $truckBrand['truckBrandID']));
					}
				}
				$this -> view -> truckCatActive = $truckCatActive;		
		
				$this -> view -> brand = $brandDetails;
			}else{
				$this -> _forward('brandidx');
			}
		}	
		else{
			$this -> _forward('brandidx');	
		}		
	}
	public function branderaseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_updBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckAds.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAds.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCarBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBikeBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruckBrand2Cat.php');
		
		$req = $this -> getRequest();
		$p = $req -> getParams();
		if (isset($p['bid']) && isset($p['brandDel1'])){
			$brandDetails = db_selBrand(array('brandID' => $p['bid']));
			if ($brandDetails != false){
				$brandDetails = $brandDetails[0];				
				
				//Determine amount of car advertisement
				$carBrandDetails = db_selCarBrand(array('brandID' => $brandDetails['brandID']));
				if (($carBrandDetails != false) && is_array($carBrandDetails) && (count($carBrandDetails) > 0)){
					$carBrandDetails = $carBrandDetails[0];
					$carAds = db_selCarAds(array('carBrand' => $carBrandDetails['carBrandID']));
					$this -> view -> carAds = $carAds;
					
					//Determine amount of car models
					$carModel = db_selCarModel(array('carBrandID' => $carBrandDetails['carBrandID']));
					$this -> view -> carModel = $carModel;
				}
				
				//$bikeBrandDetails = db_selBikeBrand(array('brandID' => $p['bid']));
				//Determine amount of bike models
				$bikeModel = db_selBikeModel(array('bikeBrandID' => $brandDetails['brandID']));
				$this -> view -> bikeModel = $bikeModel;
				
				//$truckBrandDetails = db_selTruckBrand(array('brandID' => $p['bid']));
				//Determine amount of bike models
				$truckModel = db_selTruckModel(array('truckBrandID' => $brandDetails['brandID']));
				$this -> view -> truckModel = $truckModel;
				
				$this -> view -> brand = $brandDetails;
			}else{
				$this -> _forward('brandidx');
			}
		}	
		else if(isset($p['bid']) && isset($p['brandDel2'])){
			$brandDetails = db_selBrand(array('brandID' => $p['bid']));
			if ($brandDetails != false){
				$brandDetails = $brandDetails[0];
				
				//Fetch all car brand
				$carBrandDetails = db_selCarBrand(array('brandID' => $brandDetails['brandID']));
				
				//Fetch all bike brand
				$bikeBrandDetails = db_selBikeBrand(array('brandID' => $brandDetails['brandID']));
				
				//Fetch all truck brand
				$truckBrandDetails = db_selTruckBrand(array('brandID' => $brandDetails['brandID']));
				
				//Delete brand
				$db_updBrand = db_updBrand(array(System_Properties::SQL_SET => array('erased' => '1')
												,System_Properties::SQL_WHERE => array('brandID' => $brandDetails['brandID']) 
											));
											
				if ($db_updBrand != false){					
					//Erase all car ads which corresponds to this brand
					if ($carBrandDetails != false){
						$carBrandDetails = $carBrandDetails[0];
						db_updCarAds(array(System_Properties::SQL_SET => array('erased' => '1')
										, System_Properties::SQL_WHERE => array('carBrandID' => $carBrandDetails['carBrandID'])
									));
						db_delCarBrand2Cat(array('carBrandID' => $carBrandDetails['carBrandID']));
					}
							
					//Erase all bike ads which corresponds to this brand
					if ($bikeBrandDetails != false){
						$bikeBrandDetails = $bikeBrandDetails[0];
						db_updBikeAds(array(System_Properties::SQL_SET => array('erased' => '1')
										, System_Properties::SQL_WHERE => array('bikeBrandID' => $bikeBrandDetails['bikeBrandID'])
									));
						db_delBikeBrand2Cat(array('bikeBrandID' => $bikeBrandDetails['bikeBrandID']));
					}
						/*	
					//Erase all truck ads which corresponds to this brand
					if ($truckBrandDetails != false){
						$truckBrandDetails = $truckBrandDetails[0];
						db_updTruckAds(array(System_Properties::SQL_SET => array('erased' => '1')
										, System_Properties::SQL_WHERE => array('truckBrandID' => $truckBrandDetails['truckBrandID'])
									));
					}*/
				}
							
				/*
				$carBrandDetails = db_selCarBrand(array('brandID' => $brandDetails['brandID']));
				if ($carBrandDetails != false){
					db_updCarBrand(array(System_Properties::SQL_SET => array('erased' => 1)
										, System_Properties::SQL_WHERE => array('brandID' => $brandDetails['brandID'])
									));
				}
				
				$bikeBrandDetails = db_selBikeBrand(array('brandID' => $brandDetails['brandID']));
				if ($bikeBrandDetails != false){
					db_updBikeBrand(array(System_Properties::SQL_SET => array('erased' => 1)
										, System_Properties::SQL_WHERE => array('brandID' => $brandDetails['brandID'])
									));
				}
				
				$truckBrandDetails = db_selTruckBrand(array('brandID' => $brandDetails['brandID']));
				if ($truckBrandDetails != false){
					db_updTruckBrand(array(System_Properties::SQL_SET => array('erased' => 1)
										, System_Properties::SQL_WHERE => array('brandID' => $brandDetails['brandID'])
									));
				}
				*/
			}
			$this -> _forward('brandidx');
		}
		else{
			$this -> _forward('brandidx');	
		}		
	}
	
	private function updatebrandAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_updBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckBrand.php');
		include_once('default/views/filters/FilterString50.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCarBrand2Cat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBikeBrand2Cat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand2Cat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruckBrand2Cat.php');
		
		$lang = $this -> lang;
		$p = $this -> getRequest() -> getParams();
		$fString50 = new FilterString50();		
		if (isset($p['bid'])){
			$p['brandID'] = $p['bid'];
		}
		
		if (!isset($p['brandName']) || ($fString50 -> isValid($p['brandName']) == false)){
			$this -> view -> error = $lang['AERR_21'];
		}
		else if(!isset($p['brandID'])){
			$this -> view -> error = $lang['AERR_24'];
		} 
		else{
			//Update central brand name
			$brandDetailsByID = db_selBrand(array('brandID' => $p['brandID'])); 
			if( $brandDetailsByID == false){
				$this -> view -> error = $lang['AERR_24'];				
			}
			else{
				$brandDetailsByID = $brandDetailsByID[0];
				$brandDetailsByName = db_selBrand(array('brandName' => $p['brandName'])); 
				if(($brandDetailsByName != false) && ($brandDetailsByID['brandID'] != $brandDetailsByName[0]['brandID'])){
					$this -> view -> error = $lang['AERR_23'];
				}else if ($brandDetailsByID['brandName'] != $p['brandName']){ 
					$brandID = db_updBrand(array(System_Properties::SQL_WHERE => $p, System_Properties::SQL_SET => $p));
				}
				
				//update or insert carBrandActive
				if (isset($p['carBrandActive'])){
					$p['carBrandActive'] = 1;
				}						
				else{
					$p['carBrandActive'] = 0;
				}
				$carBrandDetail = db_selCarBrand(array('brandID' => $p['brandID']));
				if (is_array($carBrandDetail) && (count($carBrandDetail) > 0)){
					$carBrandDetail = $carBrandDetail[0];
					$carBrandID = $carBrandDetail['carBrandID'];
					db_updCarBrand(array(System_Properties::SQL_WHERE=>array('brandID' => $p['brandID'])
										, System_Properties::SQL_SET=>array('active' => $p['carBrandActive'])
									));
				}else{
					$carBrandID = db_insCarBrand(array('brandID' => $p['brandID'], 'active' => $p['carBrandActive']));
				}				
				
				if ($p['carBrandActive'] == 1){
					//check car categories
					if (isset($p['carCat']) && is_numeric($carBrandID)){
						if (in_array('-1', $p['carCat'])){
							$carCat = db_selCarCat(array('lft' => 1));	
							if ($carCat == false){
								db_insCarCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
								$carCat = db_selCarCat(array('lft'=>1));
							}									
						}else{
							$carCat = db_selCarCat(array('carCatID' => $p['carCat']));	
						}	
						if (is_array($carCat)){
							$catNew = array();
							foreach($carCat AS $key => $kVal){
								if ((isset($kVal['children']) && ($kVal['children'] >= 1))
									|| ($kVal['lft'] == 1) ){
									array_push($catNew, $kVal);
								}
							}
							$carCat = $catNew;
							db_delCarBrand2Cat(array('carBrandID' => $carBrandID));
							
							if (count($carCat) > 0){						
								foreach($carCat AS $key => $kVal){
									db_insCarBrand2Cat(array('carBrandID' => $carBrandID
															, 'carCatID' => $kVal['carCatID']
															));
								}
							}
						}
					}
				}else{
					db_delCarBrand2Cat(array('carBrandID' => $carBrandID));
				}
				
				
				
				
				//bikeBrandActive
				if (isset($p['bikeBrandActive'])){
					$p['bikeBrandActive'] = 1;
				}						
				else{
					$p['bikeBrandActive'] = 0;
				}
				$bikeBrandDetail = db_selBikeBrand(array('brandID' => $p['brandID']));
				if (is_array($bikeBrandDetail) && (count($bikeBrandDetail) > 0)){
					$bikeBrandDetail = $bikeBrandDetail[0];
					$bikeBrandID = $bikeBrandDetail['bikeBrandID'];
					db_updBikeBrand(array(System_Properties::SQL_WHERE=>array('brandID' => $p['brandID'])
										, System_Properties::SQL_SET=>array('active' => $p['bikeBrandActive'])
									));
				}else{
					$bikeBrandID = db_insBikeBrand(array('brandID' => $p['brandID'], 'active' => $p['bikeBrandActive']));
				}					
				
				if ($p['bikeBrandActive'] == 1){
					//check bike categories
					if (isset($p['bikeCat']) && is_numeric($bikeBrandID)){
						if (in_array('-1', $p['bikeCat'])){
							$bikeCat = db_selBikeCat(array('lft' => 1));	
							if ($bikeCat == false){
								db_insBikeCat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
								$bikeCat = db_selBikeCat(array('lft'=>1));
							}									
						}else{
							$bikeCat = db_selBikeCat(array('bikeCatID' => $p['bikeCat']));	
						}	
						if (is_array($bikeCat)){
							$catNew = array();
							foreach($bikeCat AS $key => $kVal){
								if ((isset($kVal['children']) && ($kVal['children'] >= 1))
									|| ($kVal['lft'] == 1) ){
									array_push($catNew, $kVal);
								}
							}
							$bikeCat = $catNew;
							db_delBikeBrand2Cat(array('bikeBrandID' => $bikeBrandID));
							
							if (count($bikeCat) > 0){				
								foreach($bikeCat AS $key => $kVal){
									db_insBikeBrand2Cat(array('bikeBrandID' => $bikeBrandID
															, 'bikeCatID' => $kVal['bikeCatID']
															));
								}
							}
						}
					}
				}else{
					db_delBikeBrand2Cat(array('bikeBrandID' => $bikeBrandID));
				}
				
				
				//truckBrandActive
				if (isset($p['truckBrandActive'])){
					$p['truckBrandActive'] = 1;
				}						
				else{
					$p['truckBrandActive'] = 0;
				}
				$truckBrandDetail = db_selTruckBrand(array('brandID' => $p['brandID']));
				if (is_array($truckBrandDetail) && (count($truckBrandDetail) > 0)){
					$truckBrandDetail = $truckBrandDetail[0];
					$truckBrandID = $truckBrandDetail['truckBrandID'];
					db_updTruckBrand(array(System_Properties::SQL_WHERE=>array('brandID' => $p['brandID'])
										, System_Properties::SQL_SET=>array('active' => $p['truckBrandActive'])
									));
				}else{
					$truckBrandID = db_insTruckBrand(array('brandID' => $p['brandID'], 'active' => $p['truckBrandActive']));
				}		
				
				if ($p['truckBrandActive'] == 1){
					//check truck categories
					if (isset($p['truckCat']) && is_numeric($truckBrandID)){
						if (in_array('-1', $p['truckCat'])){
							$truckCat = db_selTruckCat(array('lft' => 1));	
							if ($truckCat == false){
								db_insTruckBrand2Cat(array('lft' => 1, 'rgt' => 2, 'active' => 1, 'vcatID' => 0));						
								$truckCat = db_selTruckCat(array('lft'=>1));
							}									
						}else{
							$truckCat = db_selTruckCat(array('truckCatID' => $p['truckCat']));	
						}	
						if (is_array($truckCat)){
							$catNew = array();
							foreach($truckCat AS $key => $kVal){
								if ((isset($kVal['children']) && ($kVal['children'] >= 1))
									|| ($kVal['lft'] == 1) ){
									array_push($catNew, $kVal);
								}
							}
							$truckCat = $catNew;
							db_delTruckBrand2Cat(array('truckBrandID' => $truckBrandID));
							
							if (count($truckCat) > 0){						
								foreach($truckCat AS $key => $kVal){
									db_insTruckBrand2Cat(array('truckBrandID' => $truckBrandID
															, 'truckCatID' => $kVal['truckCatID']
															));
								}
							}
						}
					}
				}else{
					db_delTruckBrand2Cat(array('truckBrandID' => $truckBrandID));
				}
			}		
		}
	}
	
	/**
	 * This action controller function afford the management of system properties
	 */
	public function systemAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selSystem.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_insSystem.php');
		
		$lang = $this -> lang;
		
		$req = $this -> getRequest();
		if ($req -> __isset('sysSafe')){
			$p = $req -> getParams();
			$p = $this -> filtersystemParam($p);
			if (isset($p['error'])){
				$this -> view -> error = $p['error'];
			}else{
				$systemID = db_insSystem($p);
				if ($systemID == false){
					$this -> view -> error = $lang['AERR_3'];
				}else{
					$this -> view -> info = $lang['AINFO_4'];
				}
			}
		}		
		
		$group = db_selGroup();
		$this -> view -> group = $group;
		
		$sysSystem = db_selSystem(array('orderby' => array(array('col' => 'timestam'
																, 'desc' => true
																)
														),
										'limit' => array('start' => 0
														, 'num' => 1)
										)
								);
								
		if (is_array($sysSystem) && (count($sysSystem) > 0)){
			$this -> view -> sysSystem = $sysSystem[0];
		}
	}
	
	private function filtersystemParam($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/group/db_selGroup.php');
		include_once('default/views/filters/FilterString100.php');
		include_once('default/views/filters/FilterValidEmail.php');
		
		$fEMail = new FilterValidEmail();
		$fString100 = new FilterString100();
		$lang = $this -> lang;
		
		//Check sysOnline
		if (!isset($p['sysOnline']) || (($p['sysOnline'] != 1) && ($p['sysOnline'] != 0))){
			$p['error'] = $lang['AERR_12'];
		}
		//Check sysSiteName
		else if ( !isset($p['sysSiteName']) || ($fString100 -> isValid($p['sysSiteName']) == false)){
			$p['error'] = $lang['AERR_13'];
		}
		//Check sysRegister
		else if (!isset($p['sysRegister']) || (($p['sysRegister'] != 1) && ($p['sysRegister'] != 0))){
			$p['error'] = $lang['AERR_14'];
		}
		//Check sysStdGroup
		else if (!isset($p['sysStdGroup'])){
			$p['error'] = $lang['AERR_15'];
		}
		// Check sysLogin
		else if (!isset($p['sysLogin']) || (($p['sysLogin'] != 1) && ($p['sysLogin'] != 0))){
			$p['error'] = $lang['AERR_16'];
		}
		// Check sysCarMarket
		else if (!isset($p['sysCarMarket']) || (($p['sysCarMarket'] != 1) && ($p['sysCarMarket'] != 0))){
			$p['error'] = $lang['AERR_17'];
		}
		// Check sysBikeMarket
		else if (!isset($p['sysBikeMarket']) || (($p['sysBikeMarket'] != 1) && ($p['sysBikeMarket'] != 0))){
			$p['error'] = $lang['AERR_18'];
		}
		// Check sysTruckMarket
		else if (!isset($p['sysTruckMarket']) || (($p['sysTruckMarket'] != 1) && ($p['sysTruckMarket'] != 0))){
			$p['error'] = $lang['AERR_19'];
		}
		//Check sysEMail
		else if ( !isset($p['sysEMail']) || $fEMail->filter($p['sysEMail']) == false){
			$p['error'] = $lang['AERR_20'];
		}
		//Check sysImp
		else if ( !isset($p['sysImp'])){// || $fEMail->filter($p['sysImp']) == false){
			$p['error'] = $lang['AERR_37'];
		}
		// Check sysDisc
		else if (!isset($p['sysDisc']) || (($p['sysDisc'] != 1) && ($p['sysDisc'] != 0))){
			$p['error'] = $lang['AERR_43'];
		}
		else{
			$groupDetails = db_selGroup(array('groupID' => $p['sysStdGroup']));
			if (($groupDetails == false) || (count($groupDetails) == 0)){
				$p['error'] = $lang['AERR_15'];					
			}
		}		
		
		return $p;
	}
	
	/**
	 * facilitate car model management
	 */
	public function carbrandmodelAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');	
		
		$carBrand = db_selCarBrand(array('active' => 1
										, 'orderby'=>array(array('col' => 'brandName'))
										));
										
		$req = $this -> getRequest();
		$p = $req -> getParams();
		if (($carBrand == false) || !is_array($carBrand) || (count($carBrand) == 0)){		
			$this -> _forward('brandidx');
		}
		else{
			//Check if brand id is set
			if (isset($p['bid'])){
				//Get brand details
				$carBrandDetail = db_selCarBrand(array('brandID' => $p['bid'], 'active' => 1));
			}else{
				$carBrandDetail = $carBrand;
			}
		
			if (($carBrandDetail == false) || !is_array($carBrandDetail) || (count($carBrandDetail) == 0)){		
				$this -> _forward('brandidx');
			}
			else{			
				$carBrandDetail = $carBrandDetail[0];
				$p['carBrandDetail'] = $carBrandDetail;
				
				//create a new brand model
				if (isset($p['carBrandModelNew'])){
					$p = $this -> insertcarbrandmodelAction($p);
					
					if (isset($p['error'])) {
						$this -> view -> error = $p['error'];
					}
					if (isset($p['info'])) {
						$this -> view -> info = $p['info'];
					}		
				}
				
				
				//get car brand details
				$carModel = db_selCarModel(array('carBrandID' => $carBrandDetail['carBrandID']
												, 'orderby' => array(array('col' => 'lft')
																	, array('col' => 'carModelName')
																	)
												//, 'p' => false												
												));
												
				if (isset($p['brandModelParent'])){
					$this -> view -> brandModelParent = $p['brandModelParent'];
				}												
				
				$this -> view -> carModel = $carModel;
				$this -> view -> brand = $carBrandDetail;
				$this -> view -> carBrand = $carBrand;
			}
		}			
	}
	private function insertcarbrandmodelAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_delCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_seloCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarModel.php');
		
		$lang = $this -> lang;		
		$fCheckSpace = new FilterIsEmptyString();
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		//Determine carBrandDetail
		if (!isset($p['carBrandDetail'])){
			$cb = db_selCarBrand(array('brandID' => $p['bid']));			
			if (($cb != false) && is_array($cb) && (count($cb) > 0)){
				$carBrandDetail = $cb[0];
			}
		}else{
			$carBrandDetail = $p['carBrandDetail'];
		}
		
		//Determine brandModelParent
		if (!isset($p['brandModelParent'])){
			$p['brandModelParent'] = -1;
		}
		
		if ($fCheckSpace -> filter($p['brandModelName']) != false){
			$this -> view -> error = $lang['AERR_30'];
		}
		elseif (($carBrandDetail != false) && is_array($carBrandDetail) && (count($carBrandDetail) > 0)){
			//Determine parent of brand model
			if (isset($p['brandModelParent']) && ($p['brandModelParent'] != -1)){
				$brandModelParent = db_selCarModel(array('carModelID' => $p['brandModelParent']
														, 'carBrandID' => $carBrandDetail['carBrandID']));
			}else{
				$brandModelParent = db_seloCarModel(array('lft' => 1));
				if ($brandModelParent == false){
					db_insCarModel(array('carModelName' => 'root', 'carBrandID' => 0, 'lft' => 1, 'rgt' => 2));
					$brandModelParent = db_seloCarModel(array('lft' => 1));
				}
			}			
			if (($brandModelParent != false) && is_array($brandModelParent) && (count($brandModelParent) > 0)){
				$brandModelParent = $brandModelParent[0];
			
				//Parent has children
				if (($brandModelParent['rgt'] - $brandModelParent['lft']) > 1){
					//Fetch all children on the same level
					if ($brandModelParent['lft'] == 1){
						$brandModelChild = db_selCarModel(array('level' => 1, 'carBrandID' => $carBrandDetail['carBrandID']));
					}else{
						$brandModelChild = db_selCarModel(array('level' => $brandModelParent['level']+1, 'carBrandID' => $carBrandDetail['carBrandID']));
					}
					
					//Sort all childs and find right position
					if (($brandModelChild != false) && is_array($brandModelChild) && (count($brandModelChild) > 0)){
						$brandModelChildH = array($p['brandModelName'] => $p['brandModelName']);
						foreach ($brandModelChild as $bmc){
							$brandModelChildH[$bmc['carModelName']] = $bmc;
						}
						ksort($brandModelChildH);
						$oldKey = false;
						$nextKey = false;
						foreach ($brandModelChildH as $key=>$val){
							if ($p['brandModelName'] == $key){
								if (isset($brandModelChildH[$oldKey])){
									$brandModelParent = $brandModelChildH[$oldKey];
									$brandModelParent['rgt'] = $brandModelParent['rgt']+1;
									break;
								}else{
									$nextKey = true;
								}
							}
							elseif($nextKey == true){
								$brandModelParent = $val;
								$brandModelParent['rgt'] = $brandModelParent['lft'];
								break;								
							}
							else{
								$oldKey = $key;
							}
						}
					}
				}
			}	
			
			$carModelDetail = db_selCarModel(array('carModelName' => $p['brandModelName']));
			if (($carModelDetail == false) 
				|| (is_array($carModelDetail) && (count($carModelDetail) > 0) && ($carModelDetail[0]['carBrandID'] != $carBrandDetail['carBrandID'])) ){
				$carModelID = db_insCarModel(array('carModelName' => $p['brandModelName']
										, 'carBrandID' => $carBrandDetail['carBrandID']
										, 'lft' => $brandModelParent['rgt']
										, 'rgt' => ($brandModelParent['rgt'] +1)));
				if ($carModelID != false){
					$carModelDetail = db_selCarModel(array('carModelID' => $carModelID));
					if (($carModelDetail != false) && is_array($carModelDetail) && (count($carModelDetail) > 0)){
						$carModelDetail = $carModelDetail[0];
						db_updCarModel(array(System_Properties::SQL_SET => array('incRgt'=>2
																				)
											, System_Properties::SQL_WHERE => array('notCarModelID' => $carModelDetail['carModelID']
																				, 'rgtBEq' => $brandModelParent['rgt'])
											));
						db_updCarModel(array(System_Properties::SQL_SET => array('incLft'=>2
																				)
											, System_Properties::SQL_WHERE => array('notCarModelID' => $carModelDetail['carModelID']
																				, 'lftBEq' => $brandModelParent['rgt'])
											));
												
						$p['info'] = $lang['AINFO_7'];
					}else{
						db_delCarModel(array('carModelID' => $carModelID));
						$p['error'] = $lang['AERR_25'];
					}
				}else{
					$p['error'] = $lang['AERR_25'];
				}
			}
			elseif (is_array($carModelDetail) && (count($carModelDetail) > 0) && ($carModelDetail[0]['carBrandID'] == $carBrandDetail['carBrandID'])){
				$p['error'] = $lang['AERR_26'];
			}
		}
		return $p;
	}
	
	/**
	 * @param 	mid: 	this variable specify the car model id
	 * Enter description here ...
	 */
	public function carbrandmodeleditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_seloCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarModel.php');
		
		$req = $this -> getRequest();
		$p = $req -> getParams();
		$mID = $req -> getParam('mid');
		$lang = $this -> lang;
		
		$carModel = db_selCarModel(array('carModelID' => $mID));
		if (($carModel != false) && is_array($carModel) && (count($carModel) > 0)) {
			$carModel = $carModel[0];
			
			//Update carModel details
			if (isset($p['modelEdit'])){
				//Update car brand model name
				$modelName = $p['brandModelName'];

				//check if model name exist within the target vehicle brand.
				$carModelCheck = db_selCarModel(array('carModelName' => $modelName
													, 'carBrandID' => $carModel['carBrandID'] 
												));
				if ($carModelCheck == false){
					$db_updCarModel = db_updCarModel(array(System_Properties::SQL_SET => array('carModelName' => $modelName)
														, System_Properties::SQL_WHERE => array('carModelID' => $carModel['carModelID'])
														));
					if ($db_updCarModel != false){
						$carModel = db_selCarModel(array('carModelID' => $carModel['carModelID']));
						if ($carModel != false) {
							$carModel = $carModel[0];
						}
					}
				}
				
				//update car model parent.
				$parentModelID = $p['brandModelParent'];
				if ($parentModelID < 1){
					$parentModel = db_seloCarModel(array('lft'=>1));
				}else{
					$parentModel = db_selCarModel(array('carModelID'=>$parentModelID));
				}
				if (($parentModel != false) && is_array($parentModel) && (count($parentModel) > 0)){
					$parentModel = $parentModel[0];
					if (($parentModel['carBrandID'] == $carModel['carBrandID'])
						|| ($parentModel['lft'] == 1)){
						//It is not allowed that parent is a child of actual node
						if(!($parentModel['lft']>$carModel['lft']) || !($parentModel['rgt']<$carModel['rgt'])){
							
							//select all child elements
							$carModels = db_selCarModel(array('lftBEq'=>$carModel['lft']
															, 'rgtLEq' => $carModel['rgt']));
															
							//set lft and rgt values of children to 0
							if ($carModels != false){
								/*
								foreach ($carModels as $cm){								
									$lft = 0;
									db_updCarModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																							, 'rgt'=>$lft)
														, System_Properties::SQL_WHERE => array('carModelID'=>$cm['carModelID'])));								
								}
								*/							
									$lft = 0;
									db_updCarModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																							, 'rgt'=>$lft)
														, System_Properties::SQL_WHERE => array('lftBEq'=>$carModel['lft']
																								, 'rgtLEq' => $carModel['rgt'])));								
								
							}
													
							//Erase model from actual parent model
							//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
							$lft = ($carModel['children'] +1) *2;
							db_updCarModel(array(System_Properties::SQL_SET => array('decLft' => $lft)
												, System_Properties::SQL_WHERE => array('lftB' => $carModel['rgt'])
												));			
							db_updCarModel(array(System_Properties::SQL_SET => array('decRgt' => $lft)
												, System_Properties::SQL_WHERE => array('rgtB' => $carModel['rgt'])
												));		
												
							//The information from actual parent model changed, so fetch the actual data
							if ($parentModelID < 1){
								$parentModel = db_seloCarModel(array('lft'=>1));
							}else{
								$parentModel = db_selCarModel(array('carModelID'=>$parentModelID));
							}
							if (($parentModel != false) && is_array($parentModel) && (count($parentModel) > 0)){
								$brandModelParent = $parentModel[0];
								//Parent has children
								if (($brandModelParent['rgt'] - $brandModelParent['lft']) > 1){
									//Fetch all children on the same level
									if ($brandModelParent['lft'] == 1){
										$brandModelChild = db_selCarModel(array('level' => 1, 'carBrandID' => $carModel['carBrandID']));
									}else{
										$brandModelChild = db_selCarModel(array('level' => $brandModelParent['level']+1
																			, 'carBrandID' => $carModel['carBrandID']
																			, 'lftBE'=>$brandModelParent['lft']
																			, 'rgtLE'=>$brandModelParent['rgt']));
									}
									
									//Sort all childs and find right position
									if (($brandModelChild != false) && is_array($brandModelChild) && (count($brandModelChild) > 0)){
										$brandModelChildH = array($carModel['carModelName'] => $carModel['carModelName']);
										foreach ($brandModelChild as $bmc){
											$brandModelChildH[$bmc['carModelName']] = $bmc;
										}
										ksort($brandModelChildH);
										$oldKey = false;
										$nextKey = false;
										foreach ($brandModelChildH as $key=>$val){
											if ($carModel['carModelName'] == $key){
												if (isset($brandModelChildH[$oldKey])){
													$brandModelParent = $brandModelChildH[$oldKey];
													$brandModelParent['rgt'] = $brandModelParent['rgt']+1;
													break;
												}else{
													$nextKey = true;
												}
											}
											elseif($nextKey == true){
												$brandModelParent = $val;
												$brandModelParent['rgt'] = $brandModelParent['lft'];
												break;								
											}
											else{
												$oldKey = $key;
											}
										}
									}
								}
								//raise space for new nodes	
									/*		
								print_r($parentModel);echo '<br>';
								print_r($brandModelParent);echo '<br>';
								print_r(db_selCarModel());
								*/
								$lft = ($carModel['children'] +1) *2;
								if ($brandModelParent['lft'] == $brandModelParent['rgt']){
									db_updCarModel(array(System_Properties::SQL_SET => array('incLft' => $lft)
														, System_Properties::SQL_WHERE => array('lftBEq' => $brandModelParent['rgt'])
														));
									db_updCarModel(array(System_Properties::SQL_SET => array('incRgt' => $lft)
														, System_Properties::SQL_WHERE => array('rgtBEq' => $brandModelParent['rgt'])
														));
									//Now move the selectd car model to the desired parent node
									if ($carModels != false){
										
										$carModelsH = array();
										foreach($carModels as $cm){
											$cm['diff'] = $cm['rgt'] - $cm['lft'];
											$carModelsH[$cm['lft']] = $cm;
										}
										ksort($carModelsH);
										$carModels = $carModelsH;
									
										$lftStart = $brandModelParent['rgt'];
										$prevKey = null;
										foreach ($carModels as $key=>$cm){
											//increment the left start value
											if ($prevKey != null){
												$lftStart += $key - $prevKey;
											}				
											
											$lft = $lftStart;
											$rgt = $lft + $cm['diff'];
											db_updCarModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																									, 'rgt'=>$rgt)
																, System_Properties::SQL_WHERE => array('carModelID'=>$cm['carModelID'])
																));				
											$prevKey = $key;										
										}
									}
								}else{
									db_updCarModel(array(System_Properties::SQL_SET => array('incLft' => $lft)
														, System_Properties::SQL_WHERE => array('lftBEq' => $brandModelParent['rgt'])
														));			
									db_updCarModel(array(System_Properties::SQL_SET => array('incRgt' => $lft)
														, System_Properties::SQL_WHERE => array('rgtBEq' => $brandModelParent['rgt'])
														));	
															
									//Now move the selectd car model to the desired parent node
									if ($carModels != false){
										$carModelsH = array();
										foreach($carModels as $cm){
											$cm['diff'] = $cm['rgt'] - $cm['lft'];
											$carModelsH[$cm['lft']] = $cm;
										}
										ksort($carModelsH);
										$carModels = $carModelsH;
									
										$lftStart = $brandModelParent['rgt'];
										$prevKey = null;
										foreach ($carModels as $key=>$cm){
											//increment the left start value
											if ($prevKey != null){
												$lftStart += $key - $prevKey;
											}				
											
											$lft = $lftStart;
											$rgt = $lft + $cm['diff'];
											db_updCarModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																									, 'rgt'=>$rgt)
																, System_Properties::SQL_WHERE => array('carModelID'=>$cm['carModelID'])
																));				
											$prevKey = $key;										
										}
									}							
								}	
								$this -> view -> info = $lang['AINFO_10'];
							}					
						}
					}else{
						$this -> view -> error = $lang['AERR_29'];
					}
				}
				$carModel = db_selCarModel(array('carModelID' => $mID));
				if ($carModel != false) {
					$carModel = $carModel[0];
				}
			}
			//delete car model
			elseif(isset($p['modelDel'])) {
				$carAds = db_selCarAds(array('carModel'=>$carModel['carModelID']));
				$modelChild = db_selCarModel(array('lftBE' => $carModel['lft'], 'rgtLE'=>$carModel['rgt']));
				$this -> view -> delCarAds = $carAds;
				$this -> view -> delModelChild = $modelChild;
			}elseif(isset($p['modelDel2'])){
				db_updCarModel(array(System_Properties::SQL_SET => array('erased'=>1)
									, System_Properties::SQL_WHERE => array('lftBEq' => $carModel['lft']
																			, 'rgtLEq' => $carModel['rgt'])
									));
			
				$carModel = db_selCarModel(array('carModelID' => $carModel['carModelID']));
				if ($carModel != false) {
					$carModel = $carModel[0];
				}
				
				$this -> view -> info = $lang['AINFO_11'];
			}
			
			if( ($carModel != false) && is_array($carModel) 
				&& isset($carModel['erased']) && ($carModel['erased'] != 1)){
					
				$level = $carModel['level'];
				if ($level > 1){
					$level--;
				}			
				$carModelParent = db_selCarModel(array('lftLE' => $carModel['lft']
													, 'rgtBE' => $carModel['rgt']
													, 'level' => $level
														));
				if (($carModelParent != false) && is_array($carModelParent) && (count($carModelParent) > 0)){
					$carModelParent = $carModelParent[0];
					$this -> view -> carModelParent = $carModelParent;			
				}													
				$carBrand = db_selCarBrand(array('active' => 1
											, 'carBrandID' => $carModel['carBrandID'])
											);
										
				if (($carBrand != false) && is_array($carBrand) && (count($carBrand) > 0)){
					$carBrand = $carBrand[0];
					$allCarModel = db_selCarModel(array('carBrandID' => $carBrand['carBrandID']));
					$this -> view -> carBrand = $carBrand;
					$this -> view -> carModel = $carModel;
					$this -> view -> allCarModel = $allCarModel;
				}
			}else{
				$this -> _forward('carbrandmodel');
			}
		}else{		
			$this -> _forward('brandidx');
		}
	}	

	
	/**
	 * facilitate truck model management
	 */
	public function truckbrandmodelAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');	
		
		$truckBrand = db_selTruckBrand(array('active' => 1
										, 'orderby'=>array(array('col' => 'brandName'))
										));
								
		$req = $this -> getRequest();
		$p = $req -> getParams();
		if (($truckBrand == false) || !is_array($truckBrand) || (count($truckBrand) == 0)){		
			$this -> _forward('brandidx');
		}
		else{
			//Check if brand id is set
			if (isset($p['bid'])){
				//Get brand details
				$truckBrandDetail = db_selTruckBrand(array('brandID' => $p['bid'], 'active' => 1));
			}else{
				$truckBrandDetail = $truckBrand;
			}
		
			if (($truckBrandDetail == false) || !is_array($truckBrandDetail) || (count($truckBrandDetail) == 0)){		
				$this -> _forward('brandidx');
			}
			else{			
				$truckBrandDetail = $truckBrandDetail[0];
				$p['truckBrandDetail'] = $truckBrandDetail;
				
				//create a new brand model
				if (isset($p['truckBrandModelNew'])){
					$p = $this -> inserttruckbrandmodelAction($p);
					
					if (isset($p['error'])) {
						$this -> view -> error = $p['error'];
					}
					if (isset($p['info'])) {
						$this -> view -> info = $p['info'];
					}		
				}
				
				
				//get truck brand details
				$truckModel = db_selTruckModel(array('truckBrandID' => $truckBrandDetail['truckBrandID']
													, 'orderby' => array(array('col' => 'lft')
																		, array('col' => 'truckModelName')
																		)
													));
												
				if (isset($p['brandModelParent'])){
					$this -> view -> brandModelParent = $p['brandModelParent'];
				}												
				
				$this -> view -> truckModel = $truckModel;
				$this -> view -> brand = $truckBrandDetail;
				$this -> view -> truckBrand = $truckBrand;
			}
		}			
	}
	private function inserttruckbrandmodelAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_delTruckModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_seloTruckModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckModel.php');
		
		$lang = $this -> lang;		
		$fCheckSpace = new FilterIsEmptyString();
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		//Determine truckBrandDetail
		if (!isset($p['truckBrandDetail'])){
			$cb = db_selTruckBrand(array('brandID' => $p['bid']));			
			if (($cb != false) && is_array($cb) && (count($cb) > 0)){
				$truckBrandDetail = $cb[0];
			}
		}else{
			$truckBrandDetail = $p['truckBrandDetail'];
		}
		 	
		//Determine brandModelParent
		if (!isset($p['brandModelParent'])){
			$p['brandModelParent'] = -1;
		}
	
		if ($fCheckSpace -> filter($p['brandModelName']) != false){
			$this -> view -> error = $lang['AERR_30'];
		}
		elseif (($truckBrandDetail != false) && is_array($truckBrandDetail) && (count($truckBrandDetail) > 0)){
			//Determine parent of brand model
			if (isset($p['brandModelParent']) && ($p['brandModelParent'] != -1)){
				$brandModelParent = db_selTruckModel(array('truckModelID' => $p['brandModelParent']
														, 'truckBrandID' => $truckBrandDetail['truckBrandID']));
			}else{
				$brandModelParent = db_seloTruckModel(array('lft' => 1));
				if ($brandModelParent == false){
					db_insTruckModel(array('truckModelName' => 'root', 'truckBrandID' => 0, 'lft' => 1, 'rgt' => 2));
					$brandModelParent = db_seloTruckModel(array('lft' => 1));
				}
			}			
			if (($brandModelParent != false) && is_array($brandModelParent) && (count($brandModelParent) > 0)){
				$brandModelParent = $brandModelParent[0];
			
				//Parent has children
				if (($brandModelParent['rgt'] - $brandModelParent['lft']) > 1){
					//Fetch all children on the same level
					if ($brandModelParent['lft'] == 1){
						$brandModelChild = db_selTruckModel(array('level' => 1, 'truckBrandID' => $truckBrandDetail['truckBrandID']));
					}else{
						$brandModelChild = db_selTruckModel(array('level' => $brandModelParent['level']+1, 'truckBrandID' => $truckBrandDetail['truckBrandID']));
					}
					
					//Sort all childs and find right position
					if (($brandModelChild != false) && is_array($brandModelChild) && (count($brandModelChild) > 0)){
						$brandModelChildH = array($p['brandModelName'] => $p['brandModelName']);
						foreach ($brandModelChild as $bmc){
							$brandModelChildH[$bmc['truckModelName']] = $bmc;
						}
						ksort($brandModelChildH);
						$oldKey = false;
						$nextKey = false;
						foreach ($brandModelChildH as $key=>$val){
							if ($p['brandModelName'] == $key){
								if (isset($brandModelChildH[$oldKey])){
									$brandModelParent = $brandModelChildH[$oldKey];
									$brandModelParent['rgt'] = $brandModelParent['rgt']+1;
									break;
								}else{
									$nextKey = true;
								}
							}
							elseif($nextKey == true){
								$brandModelParent = $val;
								$brandModelParent['rgt'] = $brandModelParent['lft'];
								break;								
							}
							else{
								$oldKey = $key;
							}
						}
					}
				}
			}				
			
			$truckModelDetail = db_selTruckModel(array('truckModelName' => $p['brandModelName']));
			if (($truckModelDetail == false) 
				|| (is_array($truckModelDetail) && (count($truckModelDetail) > 0) && ($truckModelDetail[0]['truckBrandID'] != $truckBrandDetail['truckBrandID'])) ){
				$truckModelID = db_insTruckModel(array('truckModelName' => $p['brandModelName']
										, 'truckBrandID' => $truckBrandDetail['truckBrandID']
										, 'lft' => $brandModelParent['rgt']
										, 'rgt' => ($brandModelParent['rgt'] +1)));
				if ($truckModelID != false){
					$truckModelDetail = db_selTruckModel(array('truckModelID' => $truckModelID));
					if (($truckModelDetail != false) && is_array($truckModelDetail) && (count($truckModelDetail) > 0)){
						$truckModelDetail = $truckModelDetail[0];
						db_updTruckModel(array(System_Properties::SQL_SET => array('incRgt'=>2
																				)
											, System_Properties::SQL_WHERE => array('notTruckModelID' => $truckModelDetail['truckModelID']
																				, 'rgtBEq' => $brandModelParent['rgt'])
											));
						db_updTruckModel(array(System_Properties::SQL_SET => array('incLft'=>2
																				)
											, System_Properties::SQL_WHERE => array('notTruckModelID' => $truckModelDetail['truckModelID']
																				, 'lftBEq' => $brandModelParent['rgt'])
											));
												
						$p['info'] = $lang['AINFO_7'];
					}else{
						db_delTruckModel(array('truckModelID' => $truckModelID));
						$p['error'] = $lang['AERR_25'];
					}
				}else{
					$p['error'] = $lang['AERR_25'];
				}
			}
			elseif (is_array($truckModelDetail) && (count($truckModelDetail) > 0) && ($truckModelDetail[0]['truckBrandID'] == $truckBrandDetail['truckBrandID'])){
				$p['error'] = $lang['AERR_26'];
			}
		}
		return $p;
	}
	
	/**
	 * @param 	mid: 	this variable specify the truck model ID
	 */
	public function truckbrandmodeleditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_seloTruckModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckModel.php');
		
		$lang = $this -> lang;
		$req = $this -> getRequest();
		$p = $req -> getParams();
		$mID = $req -> getParam('mid');
		
		$truckModel = db_selTruckModel(array('truckModelID' => $mID));
		if ($truckModel != false) {
			$truckModel = $truckModel[0];
			
			//Update truckModel details
			if (isset($p['modelEdit'])){
				//Update truck brand model name
				$modelName = $p['brandModelName'];				
				$truckModelCheck = db_selTruckModel(array('truckModelName' => $modelName
													, 'truckBrandID' => $truckModel['truckBrandID'] 
												));
				if ($truckModelCheck == false){
					$db_updTruckModel = db_updTruckModel(array(System_Properties::SQL_SET => array('truckModelName' => $modelName)
														, System_Properties::SQL_WHERE => array('truckModelID' => $truckModel['truckModelID'])
														));
					if ($db_updTruckModel != false){
						$truckModel = db_selTruckModel(array('truckModelID' => $truckModel['truckModelID']));
						if ($truckModel != false) {
							$truckModel = $truckModel[0];
						}
					}
				}
				
				//update truck model parent.
				$parentModelID = $p['brandModelParent'];
				if ($parentModelID < 1){
					$parentModel = db_seloTruckModel(array('lft'=>1));
				}else{
					$parentModel = db_selTruckModel(array('truckModelID'=>$parentModelID));
				}
				if (($parentModel != false) && is_array($parentModel) && (count($parentModel) > 0)){
					$parentModel = $parentModel[0];
					if (($parentModel['truckBrandID'] == $truckModel['truckBrandID'])
						|| ($parentModel['lft'] == 1)){
						//It is not allowed that parent is a child of actual node
						if(!($parentModel['lft']>$truckModel['lft']) || !($parentModel['rgt']<$truckModel['rgt'])){
							
							//select all child elements
							$truckModels = db_selTruckModel(array('lftBEq'=>$truckModel['lft']
															, 'rgtLEq' => $truckModel['rgt']));
															
							//set lft and rgt values of children to 0
							if ($truckModels != false){
								/*
								foreach ($truckModels as $cm){								
									$lft = 0;
									db_updTruckModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																							, 'rgt'=>$lft)
														, System_Properties::SQL_WHERE => array('truckModelID'=>$cm['truckModelID'])));								
								}
								*/							
									$lft = 0;
									db_updTruckModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																							, 'rgt'=>$lft)
														, System_Properties::SQL_WHERE => array('lftBEq'=>$truckModel['lft']
																								, 'rgtLEq' => $truckModel['rgt'])));								
								
							}
													
							//Erase model from actual parent model
							//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
							$lft = ($truckModel['children'] +1) *2;
							db_updTruckModel(array(System_Properties::SQL_SET => array('decLft' => $lft)
												, System_Properties::SQL_WHERE => array('lftB' => $truckModel['rgt'])
												));			
							db_updTruckModel(array(System_Properties::SQL_SET => array('decRgt' => $lft)
												, System_Properties::SQL_WHERE => array('rgtB' => $truckModel['rgt'])
												));		
												
							//The information from actual parent model changed, so fetch the actual data
							if ($parentModelID < 1){
								$parentModel = db_seloTruckModel(array('lft'=>1));
							}else{
								$parentModel = db_selTruckModel(array('truckModelID'=>$parentModelID));
							}
							if (($parentModel != false) && is_array($parentModel) && (count($parentModel) > 0)){
								$brandModelParent = $parentModel[0];
								//Parent has children
								if (($brandModelParent['rgt'] - $brandModelParent['lft']) > 1){
									//Fetch all children on the same level
									if ($brandModelParent['lft'] == 1){
										$brandModelChild = db_selTruckModel(array('level' => 1, 'truckBrandID' => $truckModel['truckBrandID']));
									}else{
										$brandModelChild = db_selTruckModel(array('level' => $brandModelParent['level']+1
																			, 'truckBrandID' => $truckModel['truckBrandID']
																			, 'lftBE'=>$brandModelParent['lft']
																			, 'rgtLE'=>$brandModelParent['rgt']));
									}
									
									//Sort all childs and find right position
									if (($brandModelChild != false) && is_array($brandModelChild) && (count($brandModelChild) > 0)){
										$brandModelChildH = array($truckModel['truckModelName'] => $truckModel['truckModelName']);
										foreach ($brandModelChild as $bmc){
											$brandModelChildH[$bmc['truckModelName']] = $bmc;
										}
										ksort($brandModelChildH);
										$oldKey = false;
										$nextKey = false;
										foreach ($brandModelChildH as $key=>$val){
											if ($truckModel['truckModelName'] == $key){
												if (isset($brandModelChildH[$oldKey])){
													$brandModelParent = $brandModelChildH[$oldKey];
													$brandModelParent['rgt'] = $brandModelParent['rgt']+1;
													break;
												}else{
													$nextKey = true;
												}
											}
											elseif($nextKey == true){
												$brandModelParent = $val;
												$brandModelParent['rgt'] = $brandModelParent['lft'];
												break;								
											}
											else{
												$oldKey = $key;
											}
										}
									}
								}
								//raise space for new nodes	
									/*		
								print_r($parentModel);echo '<br>';
								print_r($brandModelParent);echo '<br>';
								print_r(db_selTruckModel());
								*/
								$lft = ($truckModel['children'] +1) *2;
								if ($brandModelParent['lft'] == $brandModelParent['rgt']){
									db_updTruckModel(array(System_Properties::SQL_SET => array('incLft' => $lft)
														, System_Properties::SQL_WHERE => array('lftBEq' => $brandModelParent['rgt'])
														));
									db_updTruckModel(array(System_Properties::SQL_SET => array('incRgt' => $lft)
														, System_Properties::SQL_WHERE => array('rgtBEq' => $brandModelParent['rgt'])
														));
									//Now move the selectd truck model to the desired parent node
									if ($truckModels != false){
										
										$truckModelsH = array();
										foreach($truckModels as $cm){
											$cm['diff'] = $cm['rgt'] - $cm['lft'];
											$truckModelsH[$cm['lft']] = $cm;
										}
										ksort($truckModelsH);
										$truckModels = $truckModelsH;
									
										$lftStart = $brandModelParent['rgt'];
										$prevKey = null;
										foreach ($truckModels as $key=>$cm){
											//increment the left start value
											if ($prevKey != null){
												$lftStart += $key - $prevKey;
											}				
											
											$lft = $lftStart;
											$rgt = $lft + $cm['diff'];
											db_updTruckModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																									, 'rgt'=>$rgt)
																, System_Properties::SQL_WHERE => array('truckModelID'=>$cm['truckModelID'])
																));				
											$prevKey = $key;										
										}
									}
								}else{
									db_updTruckModel(array(System_Properties::SQL_SET => array('incLft' => $lft)
														, System_Properties::SQL_WHERE => array('lftBEq' => $brandModelParent['rgt'])
														));			
									db_updTruckModel(array(System_Properties::SQL_SET => array('incRgt' => $lft)
														, System_Properties::SQL_WHERE => array('rgtBEq' => $brandModelParent['rgt'])
														));	
															
									//Now move the selectd truck model to the desired parent node
									if ($truckModels != false){
										$truckModelsH = array();
										foreach($truckModels as $cm){
											$cm['diff'] = $cm['rgt'] - $cm['lft'];
											$truckModelsH[$cm['lft']] = $cm;
										}
										ksort($truckModelsH);
										$truckModels = $truckModelsH;
									
										$lftStart = $brandModelParent['rgt'];
										$prevKey = null;
										foreach ($truckModels as $key=>$cm){
											//increment the left start value
											if ($prevKey != null){
												$lftStart += $key - $prevKey;
											}				
											
											$lft = $lftStart;
											$rgt = $lft + $cm['diff'];
											db_updTruckModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																									, 'rgt'=>$rgt)
																, System_Properties::SQL_WHERE => array('truckModelID'=>$cm['truckModelID'])
																));				
											$prevKey = $key;										
										}
									}				
								}	
								$this -> view -> info = $lang['AINFO_10'];			
							}					
						}
					}else{
						$this -> view -> error = $lang['AERR_29'];
					}
				}
				$truckModel = db_selTruckModel(array('truckModelID' => $mID));
				if ($truckModel != false) {
					$truckModel = $truckModel[0];
				}
			}
			//delete truck model
			elseif(isset($p['modelDel'])) {
				$truckAds = db_selTruckAds(array('truckModel'=>$truckModel['truckModelID']
												//, 'print' => true
												));
				$modelChild = db_selTruckModel(array('lftBE' => $truckModel['lft']
													, 'rgtLE'=>$truckModel['rgt']
													//, 'p'=>true
													));
													
				$this -> view -> delTruckAds = $truckAds;
				$this -> view -> delModelChild = $modelChild;
			}elseif(isset($p['modelDel2'])){
				db_updTruckModel(array(System_Properties::SQL_SET => array('erased'=>1)
									, System_Properties::SQL_WHERE => array('lftBEq' => $truckModel['lft']
																			, 'rgtLEq' => $truckModel['rgt'])
									));
				$truckModel = db_selTruckModel(array('truckModelID' => $truckModel['truckModelID']));
				if ($truckModel != false) {
					$truckModel = $truckModel[0];
				}
				
				$this -> view -> info = $lang['AINFO_11'];
			}
			
			if( ($truckModel != false) && is_array($truckModel) 
				&& isset($truckModel['erased']) && ($truckModel['erased'] != 1)){			
				$level = $truckModel['level'];
				if ($level > 1){
					$level--;
				}			
				$truckModelParent = db_selTruckModel(array('lftLE' => $truckModel['lft']
													, 'rgtBE' => $truckModel['rgt']
													, 'level' => $level
														));
				if (($truckModelParent != false) && is_array($truckModelParent) && (count($truckModelParent) > 0)){
					$truckModelParent = $truckModelParent[0];
					$this -> view -> truckModelParent = $truckModelParent;			
				}													
				$truckBrand = db_selTruckBrand(array('active' => 1
											, 'truckBrandID' => $truckModel['truckBrandID'])
											);
										
				if (($truckBrand != false) && is_array($truckBrand) && (count($truckBrand) > 0)){
					$truckBrand = $truckBrand[0];
					$allTruckModel = db_selTruckModel(array('truckBrandID' => $truckBrand['truckBrandID']));
					$this -> view -> truckBrand = $truckBrand;
					$this -> view -> truckModel = $truckModel;
					$this -> view -> allTruckModel = $allTruckModel;
				}
			}else{
				$this -> _forward('truckbrandmodel');
			}
		}else{		
			$this -> _forward('brandidx');
		}
	}
	
	public function bikebrandmodelAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		
		$bikeBrand = db_selBikeBrand(array('active' => 1
										, 'orderby'=>array(array('col' => 'brandName'))
										));	
										
		$req = $this -> getRequest();
		$p = $req -> getParams();
		if (($bikeBrand == false) || !is_array($bikeBrand) || (count($bikeBrand) == 0)){		
			$this -> _forward('brandidx');
		}
		else{
			//Check if brand id is set
			if (isset($p['bid'])){
				//Get brand details
				$bikeBrandDetail = db_selBikeBrand(array('brandID' => $p['bid'], 'active' => 1));
			}else{
				$bikeBrandDetail = $bikeBrand;
			}
		
			if (($bikeBrandDetail == false) || !is_array($bikeBrandDetail) || (count($bikeBrandDetail) == 0)){		
				$this -> _forward('brandidx');
			}
			else{			
				$bikeBrandDetail = $bikeBrandDetail[0];
				$p['bikeBrandDetail'] = $bikeBrandDetail;
				
				//create a new brand model
				if (isset($p['bikeBrandModelNew'])){
					$p = $this -> insertbikebrandmodelAction($p);
					
					if (isset($p['error'])) {
						$this -> view -> error = $p['error'];
					}
					if (isset($p['info'])) {
						$this -> view -> info = $p['info'];
					}		
				}
				
				
				//get car brand details
				$bikeModel = db_selBikeModel(array('bikeBrandID' => $bikeBrandDetail['bikeBrandID']
												, 'orderby' => array(array('col' => 'lft')
																	, array('col' => 'bikeModelName')
																	)
												));
												
				if (isset($p['brandModelParent'])){
					$this -> view -> brandModelParent = $p['brandModelParent'];
				}												
				
				$this -> view -> bikeModel = $bikeModel;
				$this -> view -> brand = $bikeBrandDetail;
				$this -> view -> bikeBrand = $bikeBrand;
			}
		}		
		
	}
	private function insertbikebrandmodelAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_delBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_seloBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeModel.php');
		
		$lang = $this -> lang;		
		$fCheckSpace = new FilterIsEmptyString();
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		//Determine bikeBrandDetail
		if (!isset($p['bikeBrandDetail'])){
			$bb = db_selBikeBrand(array('brandID' => $p['bid']));			
			if (($bb != false) && is_array($bb) && (count($bb) > 0)){
				$bikeBrandDetail = $bb[0];
			}
		}else{
			$bikeBrandDetail = $p['bikeBrandDetail'];
		}
		
		//Determine brandModelParent
		if (!isset($p['brandModelParent'])){
			$p['brandModelParent'] = -1;
		}
		
		if ($fCheckSpace -> filter($p['brandModelName']) != false){
			$this -> view -> error = $lang['AERR_30'];
		}
		elseif (($bikeBrandDetail != false) && is_array($bikeBrandDetail) && (count($bikeBrandDetail) > 0)){
			//Determine parent of brand model
			if (isset($p['brandModelParent']) && ($p['brandModelParent'] != -1)){
				$brandModelParent = db_selBikeModel(array('bikeModelID' => $p['brandModelParent']
														, 'bikeBrandID' => $bikeBrandDetail['bikeBrandID']));
			}else{
				$brandModelParent = db_seloBikeModel(array('lft' => 1));
				if ($brandModelParent == false){
					db_insBikeModel(array('bikeModelName' => 'root', 'bikeBrandID' => 0, 'lft' => 1, 'rgt' => 2));
					$brandModelParent = db_seloBikeModel(array('lft' => 1));
				}
			}
						
			if (($brandModelParent != false) && is_array($brandModelParent) && (count($brandModelParent) > 0)){
				$brandModelParent = $brandModelParent[0];
			
				//Parent has children
				if (($brandModelParent['rgt'] - $brandModelParent['lft']) > 1){
					//Fetch all children on the same level
					if ($brandModelParent['lft'] == 1){
						$brandModelChild = db_selBikeModel(array('level' => 1, 'bikeBrandID' => $bikeBrandDetail['bikeBrandID']));
					}else{
						$brandModelChild = db_selBikeModel(array('level' => $brandModelParent['level']+1, 'bikeBrandID' => $bikeBrandDetail['bikeBrandID']));
					}
					
					//Sort all childs and find right position
					if (($brandModelChild != false) && is_array($brandModelChild) && (count($brandModelChild) > 0)){
						$brandModelChildH = array($p['brandModelName'] => $p['brandModelName']);
						foreach ($brandModelChild as $bmc){
							$brandModelChildH[$bmc['bikeModelName']] = $bmc;
						}
						ksort($brandModelChildH);
						$oldKey = false;
						$nextKey = false;
						foreach ($brandModelChildH as $key=>$val){
							if ($p['brandModelName'] == $key){
								if (isset($brandModelChildH[$oldKey])){
									$brandModelParent = $brandModelChildH[$oldKey];
									$brandModelParent['rgt'] = $brandModelParent['rgt']+1;
									break;
								}else{
									$nextKey = true;
								}
							}
							elseif($nextKey == true){
								$brandModelParent = $val;
								$brandModelParent['rgt'] = $brandModelParent['lft'];
								break;								
							}
							else{
								$oldKey = $key;
							}
						}
					}
				}
			}		
			$bikeModelDetail = db_selBikeModel(array('bikeModelName' => $p['brandModelName']));
			if (($bikeModelDetail == false) 
				|| (is_array($bikeModelDetail) && (count($bikeModelDetail) > 0) && ($bikeModelDetail[0]['bikeBrandID'] != $bikeBrandDetail['bikeBrandID'])) ){					
				$bikeModelID = db_insBikeModel(array('bikeModelName' => $p['brandModelName']
													, 'bikeBrandID' => $bikeBrandDetail['bikeBrandID']
													, 'lft' => $brandModelParent['rgt']
													, 'rgt' => ($brandModelParent['rgt'] +1)));
				if ($bikeModelID != false){
					$bikeModelDetail = db_selBikeModel(array('bikeModelID' => $bikeModelID));
					if (($bikeModelDetail != false) && is_array($bikeModelDetail) && (count($bikeModelDetail) > 0)){
						$bikeModelDetail = $bikeModelDetail[0];
						db_updBikeModel(array(System_Properties::SQL_SET => array('incRgt'=>2
																				)
											, System_Properties::SQL_WHERE => array('notBikeModelID' => $bikeModelDetail['bikeModelID']
																				, 'rgtBEq' => $brandModelParent['rgt'])
											));
						db_updBikeModel(array(System_Properties::SQL_SET => array('incLft'=>2
																				)
											, System_Properties::SQL_WHERE => array('notBikeModelID' => $bikeModelDetail['bikeModelID']
																				, 'lftBEq' => $brandModelParent['rgt'])
											));
												
						$p['info'] = $lang['AINFO_7'];
					}else{
						db_delBikeModel(array('bikeModelID' => $bikeModelID));
						$p['error'] = $lang['AERR_25'];
					}
				}else{
					$p['error'] = $lang['AERR_25'];
				}
			}
			elseif (is_array($bikeModelDetail) && (count($bikeModelDetail) > 0) && ($bikeModelDetail[0]['bikeBrandID'] == $bikeBrandDetail['bikeBrandID'])){
				$p['error'] = $lang['AERR_26'];
			}
		}
		return $p;
	}
	
	/**
	 * @param 	mid: 	this variable specify the bike model id
	 */
	public function bikebrandmodeleditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeAds.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_seloBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeModel.php');
		
		$req = $this -> getRequest();
		$p = $req -> getParams();
		$mID = $req -> getParam('mid');
		$lang = $this -> lang;
		
		$bikeModel = db_selBikeModel(array('bikeModelID' => $mID));
		if ($bikeModel != false) {
			$bikeModel = $bikeModel[0];
			
			//Update bikeModel details
			if (isset($p['modelEdit'])){
				//Update bike brand model name
				$modelName = $p['brandModelName'];				
				$bikeModelCheck = db_selBikeModel(array('bikeModelName' => $modelName
														, 'bikeBrandID' => $bikeModel['bikeBrandID'] 
													));
				if ($bikeModelCheck == false){
					$db_updBikeModel = db_updBikeModel(array(System_Properties::SQL_SET => array('bikeModelName' => $modelName)
															, System_Properties::SQL_WHERE => array('bikeModelID' => $bikeModel['bikeModelID'])
															));
					if ($db_updBikeModel != false){
						$bikeModel = db_selBikeModel(array('bikeModelID' => $bikeModel['bikeModelID']));
						if ($bikeModel != false) {
							$bikeModel = $bikeModel[0];
						}
					}
				}
				
				//update bike model parent.
				$parentModelID = $p['brandModelParent'];
				if ($parentModelID < 1){
					$parentModel = db_seloBikeModel(array('lft'=>1));
				}else{
					$parentModel = db_selBikeModel(array('bikeModelID'=>$parentModelID));
				}
				if (($parentModel != false) && is_array($parentModel) && (count($parentModel) > 0)){
					$parentModel = $parentModel[0];
					if (($parentModel['bikeBrandID'] == $bikeModel['bikeBrandID'])
						|| ($parentModel['lft'] == 1)){
						//It is not allowed that parent is a child of actual node
						if(!($parentModel['lft']>$bikeModel['lft']) || !($parentModel['rgt']<$bikeModel['rgt'])){
							
							//select all child elements
							$bikeModels = db_selBikeModel(array('lftBEq'=>$bikeModel['lft']
															, 'rgtLEq' => $bikeModel['rgt']));
															
							//set lft and rgt values of children to 0
							if ($bikeModels != false){
								/*
								foreach ($bikeModels as $cm){								
									$lft = 0;
									db_updBikeModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																							, 'rgt'=>$lft)
														, System_Properties::SQL_WHERE => array('bikeModelID'=>$cm['bikeModelID'])));								
								}
								*/							
									$lft = 0;
									db_updBikeModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																							, 'rgt'=>$lft)
														, System_Properties::SQL_WHERE => array('lftBEq'=>$bikeModel['lft']
																								, 'rgtLEq' => $bikeModel['rgt'])));								
								
							}
													
							//Erase model from actual parent model
							//decrement LFT and RGT values which are bigger than the the selected node RGT-value 
							$lft = ($bikeModel['children'] +1) *2;
							db_updBikeModel(array(System_Properties::SQL_SET => array('decLft' => $lft)
												, System_Properties::SQL_WHERE => array('lftB' => $bikeModel['rgt'])
												));			
							db_updBikeModel(array(System_Properties::SQL_SET => array('decRgt' => $lft)
												, System_Properties::SQL_WHERE => array('rgtB' => $bikeModel['rgt'])
												));		
												
							//The information from actual parent model changed, so fetch the actual data
							if ($parentModelID < 1){
								$parentModel = db_seloBikeModel(array('lft'=>1));
							}else{
								$parentModel = db_selBikeModel(array('bikeModelID'=>$parentModelID));
							}
							if (($parentModel != false) && is_array($parentModel) && (count($parentModel) > 0)){
								$brandModelParent = $parentModel[0];
								//Parent has children
								if (($brandModelParent['rgt'] - $brandModelParent['lft']) > 1){
									//Fetch all children on the same level
									if ($brandModelParent['lft'] == 1){
										$brandModelChild = db_selBikeModel(array('level' => 1, 'bikeBrandID' => $bikeModel['bikeBrandID']));
									}else{
										$brandModelChild = db_selBikeModel(array('level' => $brandModelParent['level']+1
																			, 'bikeBrandID' => $bikeModel['bikeBrandID']
																			, 'lftBE'=>$brandModelParent['lft']
																			, 'rgtLE'=>$brandModelParent['rgt']));
									}
									
									//Sort all childs and find right position
									if (($brandModelChild != false) && is_array($brandModelChild) && (count($brandModelChild) > 0)){
										$brandModelChildH = array($bikeModel['bikeModelName'] => $bikeModel['bikeModelName']);
										foreach ($brandModelChild as $bmc){
											$brandModelChildH[$bmc['bikeModelName']] = $bmc;
										}
										ksort($brandModelChildH);
										$oldKey = false;
										$nextKey = false;
										foreach ($brandModelChildH as $key=>$val){
											if ($bikeModel['bikeModelName'] == $key){
												if (isset($brandModelChildH[$oldKey])){
													$brandModelParent = $brandModelChildH[$oldKey];
													$brandModelParent['rgt'] = $brandModelParent['rgt']+1;
													break;
												}else{
													$nextKey = true;
												}
											}
											elseif($nextKey == true){
												$brandModelParent = $val;
												$brandModelParent['rgt'] = $brandModelParent['lft'];
												break;								
											}
											else{
												$oldKey = $key;
											}
										}
									}
								}
								//raise space for new nodes	
									/*		
								print_r($parentModel);echo '<br>';
								print_r($brandModelParent);echo '<br>';
								print_r(db_selBikeModel());
								*/
								$lft = ($bikeModel['children'] +1) *2;
								if ($brandModelParent['lft'] == $brandModelParent['rgt']){
									db_updBikeModel(array(System_Properties::SQL_SET => array('incLft' => $lft)
														, System_Properties::SQL_WHERE => array('lftBEq' => $brandModelParent['rgt'])
														));
									db_updBikeModel(array(System_Properties::SQL_SET => array('incRgt' => $lft)
														, System_Properties::SQL_WHERE => array('rgtBEq' => $brandModelParent['rgt'])
														));
									//Now move the selectd bike model to the desired parent node
									if ($bikeModels != false){
										
										$bikeModelsH = array();
										foreach($bikeModels as $cm){
											$cm['diff'] = $cm['rgt'] - $cm['lft'];
											$bikeModelsH[$cm['lft']] = $cm;
										}
										ksort($bikeModelsH);
										$bikeModels = $bikeModelsH;
									
										$lftStart = $brandModelParent['rgt'];
										$prevKey = null;
										foreach ($bikeModels as $key=>$cm){
											//increment the left start value
											if ($prevKey != null){
												$lftStart += $key - $prevKey;
											}				
											
											$lft = $lftStart;
											$rgt = $lft + $cm['diff'];
											db_updBikeModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																									, 'rgt'=>$rgt)
																, System_Properties::SQL_WHERE => array('bikeModelID'=>$cm['bikeModelID'])
																));				
											$prevKey = $key;										
										}
									}
								}else{
									db_updBikeModel(array(System_Properties::SQL_SET => array('incLft' => $lft)
														, System_Properties::SQL_WHERE => array('lftBEq' => $brandModelParent['rgt'])
														));			
									db_updBikeModel(array(System_Properties::SQL_SET => array('incRgt' => $lft)
														, System_Properties::SQL_WHERE => array('rgtBEq' => $brandModelParent['rgt'])
														));	
															
									//Now move the selectd bike model to the desired parent node
									if ($bikeModels != false){
										$bikeModelsH = array();
										foreach($bikeModels as $cm){
											$cm['diff'] = $cm['rgt'] - $cm['lft'];
											$bikeModelsH[$cm['lft']] = $cm;
										}
										ksort($bikeModelsH);
										$bikeModels = $bikeModelsH;
										
									
										$lftStart = $brandModelParent['rgt'];
										$prevKey = null;
										foreach ($bikeModels as $key=>$cm){
											//increment the left start value
											if ($prevKey != null){
												$lftStart += $key - $prevKey;
											}				
											
											$lft = $lftStart;
											$rgt = $lft + $cm['diff'];
											db_updBikeModel(array(System_Properties::SQL_SET => array('lft'=>$lft
																									, 'rgt'=>$rgt)
																, System_Properties::SQL_WHERE => array('bikeModelID'=>$cm['bikeModelID'])
																));				
											$prevKey = $key;										
										}
									}							
								}	
								$this -> view -> info = $lang['AINFO_10'];
							}					
						}
					}else{
						$this -> view -> error = $lang['AERR_29'];
					}
				}
				$bikeModel = db_selBikeModel(array('bikeModelID' => $mID));
				if ($bikeModel != false) {
					$bikeModel = $bikeModel[0];
				}
			}
			//delete bike model
			elseif(isset($p['modelDel'])) {
				$bikeAds = db_selBikeAds(array('bikeModel'=>$bikeModel['bikeModelID']));
				$modelChild = db_selBikeModel(array('lftBE' => $bikeModel['lft'], 'rgtLE'=>$bikeModel['rgt']));
				
				$this -> view -> delBikeAds = $bikeAds;
				$this -> view -> delModelChild = $modelChild;
			}
			//confirm deletion of bike model
			elseif(isset($p['modelDel2'])){
				db_updBikeModel(array(System_Properties::SQL_SET => array('erased'=>1)
									, System_Properties::SQL_WHERE => array('lftBEq' => $bikeModel['lft']
																			, 'rgtLEq' => $bikeModel['rgt'])
									));
				$bikeModel = db_selBikeModel(array('bikeModelID' => $mID));
				if ($bikeModel != false) {
					$bikeModel = $bikeModel[0];
				}									
				$this -> view -> info = $lang['AINFO_11'];
			}
						
			if( ($bikeModel != false) && is_array($bikeModel) 
				&& isset($bikeModel['erased']) && ($bikeModel['erased'] != 1)){
			
				$level = $bikeModel['level'];
				if ($level > 1){
					$level--;
				}			
				$bikeModelParent = db_selBikeModel(array('lftLE' => $bikeModel['lft']
													, 'rgtBE' => $bikeModel['rgt']
													, 'level' => $level
														));
				if (($bikeModelParent != false) && is_array($bikeModelParent) && (count($bikeModelParent) > 0)){
					$bikeModelParent = $bikeModelParent[0];
					$this -> view -> bikeModelParent = $bikeModelParent;			
				}													
				$bikeBrand = db_selBikeBrand(array('active' => 1
											, 'bikeBrandID' => $bikeModel['bikeBrandID'])
											);
										
				if (($bikeBrand != false) && is_array($bikeBrand) && (count($bikeBrand) > 0)){
					$bikeBrand = $bikeBrand[0];
					$allBikeModel = db_selBikeModel(array('bikeBrandID' => $bikeBrand['bikeBrandID']));
					$this -> view -> bikeBrand = $bikeBrand;
					$this -> view -> bikeModel = $bikeModel;
					$this -> view -> allBikeModel = $allBikeModel;
				}
			}else{
				$this -> _forward('bikebrandmodel');
			}
		}else{		
			$this -> _forward('brandidx');
		}
	}
	
	/**
	 * Action controller for vehicle extras overview
	 */
	public function vextAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selLang.php');
		
		$req  = $this -> getRequest();
		$p = $this -> getRequest() -> getParams();
		
		$posLang = db_selLang();
		$p['POS_LANG'] = $posLang;
		
		//establish a new vehicle extra
		if(isset($p['vextNew'])){
			$this -> insertvextAction($p);
		}
	
		$ret = $this -> readvextfileAction($p);
		if (is_array($ret) && isset($ret['return']) && is_array($ret['return'])){
			foreach ($ret['return'] as $key => $value){
				$this -> lang[$key] = $value;
			}
			$this -> view -> lang = $this -> lang;
		}
		/*
		if (is_array($posLang)){
			foreach ($posLang as $key => $value){
				$fileName = '../app/modules/default/lang/lang_vextra_'.strtolower($value['langAbrv']).'.php';
				
				if (file_exists($fileName)){
					$file = file($fileName);					
					if (is_array($file)){
						$lang_var = array();
						foreach($file as $key => $row){
							if (strpos($row, 'V_EXTRA')!= false){
								array_push($lang_var, $row);
							}
						}
						//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
						eval(implode('',$lang_var));
						ksort($lang['V_EXTRA']);
						$this -> lang['V_EXTRA_'.strtoupper($value['langAbrv'])] = $lang['V_EXTRA'];
					}
				}
			}
			$this -> view -> lang = $this -> lang;	
		}
		*/
		$vext = db_selVExtra();
		$this -> view -> posLang = $posLang;
		$this -> view -> vext = $vext;
		
		/*
		$carExt = db_selCarExt();
		$bikeExt = db_selBikeExt();
		$truckExt = db_selTruckExt();
		*/
		/*
		$this -> view -> carExt = $carExt;
		$this -> view -> bikeExt = $bikeExt;
		$this -> view -> truckExt = $truckExt;
		*/
		
		$this -> view -> vextParam = $p;		
	}
	
	private function insertvextAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_insVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarExt.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeExt.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckExt.php');
		
		$lang = $this -> lang;
		$check_insert = true;
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		//cehck possible languages
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			foreach($p['POS_LANG'] as $key => $value){
				if (!isset($value['langAbrv'])){
					$check_insert = false;
				}
				elseif (!isset($p['lang_'.strtolower($value['langAbrv'])])){
					$check_insert = false;
				}
			}
		}		
		
		if ($check_insert == true){
			$vextID = db_insVExtra(array('erased' => 0));
			if (($vextID != false) && is_numeric($vextID)){
				$p['vextID'] = $vextID;
				$this -> writeInsertVextFileAction($p);		
			
				//check if car extra should be inserted, parameter from screen
				if (isset($p['carExtActive'])){
					if (isset($p['carExtParent']) && ($p['carExtParent'] != -1)){
						$carExtParent = db_selCarExt(array('carExtID' => $p['carExtParent']));
					}
					else{
						$carExtParent = db_selCarExt(array('lft' => 1));
						if ($carExtParent == false){
							//insert root node if not exist 
							db_insCarExt(array('lft'=>1, 'rgt'=>2
												, 'active'=>1, 'vextID'=>0
											));
						}
						$carExtParent = db_selCarExt(array('lft' => 1));
					}
					
					if (($carExtParent != false) && (count($carExtParent) == 1)){
						$carExtParent = $carExtParent[0];
						$carExtChildren = db_selCarExt(array('lftBEq' => $carExtParent['lft']
															, 'rgtLEq' => $carExtParent['rgt']
															, 'level' => ($carExtParent['level']+1)
															));
						db_updCarExt(array(	System_Properties::SQL_SET => array('incRgt'=>2)
											, System_Properties::SQL_WHERE=>array('rgtBEq'=>$carExtParent['rgt'])
											));
						db_updCarExt(array(	System_Properties::SQL_SET => array('incLft'=>2)
											, System_Properties::SQL_WHERE=>array('lftBEq'=>$carExtParent['rgt'])
											));
						db_insCarExt(array('vextID' => $p['vextID']
											, 'active' => $carExtParent['active']
											, 'lft' => $carExtParent['rgt']
											, 'rgt' => $carExtParent['rgt']+1
										));
					}				
				}
				
				//check if bike extra should be inserted
				if (isset($p['bikeExtActive'])){
					if (isset($p['bikeExtParent']) && ($p['bikeExtParent'] != -1)){
						$bikeExtParent = db_selBikeExt(array('bikeExtID' => $p['bikeExtParent']));
					}
					else{
						$bikeExtParent = db_selBikeExt(array('lft' => 1));
						if ($bikeExtParent == false){
							//insert root node if not exist 
							db_insBikeExt(array('lft'=>1, 'rgt'=>2
												, 'active'=>1, 'vextID'=>0
											));
						}
						$bikeExtParent = db_selBikeExt(array('lft' => 1));
					}
					
					if (($bikeExtParent != false) && (count($bikeExtParent) == 1)){
						$bikeExtParent = $bikeExtParent[0];
						$bikeExtChildren = db_selBikeExt(array('lftBEq' => $bikeExtParent['lft']
															, 'rgtLEq' => $bikeExtParent['rgt']
															, 'level' => ($bikeExtParent['level']+1)
															));
						db_updBikeExt(array(	System_Properties::SQL_SET => array('incRgt'=>2)
											, System_Properties::SQL_WHERE=>array('rgtBEq'=>$bikeExtParent['rgt'])
											));
						db_updBikeExt(array(	System_Properties::SQL_SET => array('incLft'=>2)
											, System_Properties::SQL_WHERE=>array('lftBEq'=>$bikeExtParent['rgt'])
											));
						db_insBikeExt(array('vextID' => $p['vextID']
											, 'active' => $bikeExtParent['active'] 
											, 'lft' => $bikeExtParent['rgt']
											, 'rgt' => $bikeExtParent['rgt']+1
										));
					}				
				}
				//check if truck extra should be inserted
				if (isset($p['truckExtActive'])){					
					if (isset($p['truckExtParent']) && ($p['truckExtParent'] != -1)){
						$truckExtParent = db_selTruckExt(array('truckExtID' => $p['truckExtParent']));
					}
					else{
						$truckExtParent = db_selTruckExt(array('lft' => 1));
						if ($truckExtParent == false){
							//insert root node if not exist 
							db_insTruckExt(array('lft'=>1, 'rgt'=>2
												, 'active'=>1, 'vextID'=>0
											));
						}
						$truckExtParent = db_selTruckExt(array('lft' => 1));
					}
					
					if (($truckExtParent != false) && (count($truckExtParent) == 1)){
						$truckExtParent = $truckExtParent[0];
						$truckExtChildren = db_selTruckExt(array('lftBEq' => $truckExtParent['lft']
															, 'rgtLEq' => $truckExtParent['rgt']
															, 'level' => ($truckExtParent['level']+1)
															));
						db_updTruckExt(array(	System_Properties::SQL_SET => array('incRgt'=>2)
											, System_Properties::SQL_WHERE=>array('rgtBEq'=>$truckExtParent['rgt'])
											));
						db_updTruckExt(array(	System_Properties::SQL_SET => array('incLft'=>2)
											, System_Properties::SQL_WHERE=>array('lftBEq'=>$truckExtParent['rgt'])
											));
						db_insTruckExt(array('vextID' => $p['vextID']
											, 'active' => $truckExtParent['active'] 
											, 'lft' => $truckExtParent['rgt']
											, 'rgt' => $truckExtParent['rgt']+1
										));
					}				
				}
			}
		}else{
			$this -> view -> error = $lang['AERR_28'];
		}
	}
	
	/**
	 * This function insert an vehicle extra in the corresponding language files
	 */
	private function writeInsertVextFileAction($p){
		if (isset($p['vextID']) && isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			//$posLang = db_selLang(array('langID'=>$p['lang']));
			foreach ($p['POS_LANG'] as $key => $posLang){
				if (isset($posLang['langAbrv'])){
					$langVextID  = 'lang_'.strtolower($posLang['langAbrv']);
					$fileName = '../app/modules/default/lang/lang_vextra_'.strtolower($posLang['langAbrv']).'.php';
					if (file_exists($fileName)){
						$file = file($fileName);
						//separate V_EXTRA lines from rest
						foreach ($file as $key => $val) {
							$file[$key] = str_ireplace('?>', '', $val);
						}
						//push new element at th end of the V_EXTRA array
						if(isset($p[$langVextID])){
							array_push($file, "\$lang['V_EXTRA'][".$p['vextID']."] = '".$p[$langVextID]."';\r\n?>");
						}else{
							array_push($file, "\$lang['V_EXTRA'][".$p['vextID']."] = '';\r\n?>");
						}
											
						//write the new array into file
						$fp = fopen($fileName, 'w');
						if($fp != false){
							fwrite($fp, implode("", $file));		
							fclose($fp);			
							$this -> lang['V_EXTRA'][$p['vextID']] = $p[$langVextID];
							$this -> view -> lang = $this -> lang;
						}
					}
				}
			}
		}
	}
	/**
	 * Handle editing process of an vehicle category
	 */
	private function editvcatAction($p){
		
		$vcatID = null;
		if (isset($p['vid']) && is_numeric($p['vid'])){
			$vcatID = $p['vid'];
		}
		
		//check if each possible language should be updated
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG']) && ($vcatID != null)) {
			foreach($p['POS_LANG'] as $key => $value){
				if (isset($value['langAbrv'])){
					//lang_XXX is parameter from screen
					if (isset($p['lang_'.strtolower($value['langAbrv'])])){
						//read V_CAT from file
						$return = $this -> readvcatfileAction(array('POS_LANG' => $value['langAbrv']));
						if (isset($return['return']['V_CAT_'.strtoupper($value['langAbrv'])])){
							$langVCat = $return['return']['V_CAT_'.strtoupper($value['langAbrv'])];
							
							//update vcat name
							$langVCat[$vcatID] = $p['lang_'.strtolower($value['langAbrv'])];
							$p['V_CAT_'.strtoupper($value['langAbrv'])] = $langVCat;
						}
					}
				}
			}						
			
			$this -> writeUpdateVcatfileAction($p);
		}
	}
	
	/**
	 * This function update an vehicle extra in all language files
	 */
	
	private function writeUpdateVextfileAction($p){
		
		//check if each possible language should be updated
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])) {
			foreach($p['POS_LANG'] as $lKey => $lVal){
				if (isset($lVal['langAbrv'])){
					//lang_XXX is parameter from screen
					if (isset($p['V_EXTRA_'.strtoupper($lVal['langAbrv'])]) 
						&& is_array($p['V_EXTRA_'.strtoupper($lVal['langAbrv'])])){
						$langVextID  = 'lang_'.strtolower($lVal['langAbrv']);
						$fileName = '../app/modules/default/lang/lang_vextra_'.strtolower($lVal['langAbrv']).'.php';
						if (file_exists($fileName)){
							$fileOri = file($fileName);
							$file = array();							
							//separate V_EXTRA lines from rest
							foreach ($fileOri as $fKey => $fValue) {
								if ((stristr($fValue, 'V_EXTRA') == false) 
									&& (stristr($fValue, '?>') == false)){
									array_push($file, $fValue);
								}
							}
							
							array_push($file, "\$lang['V_EXTRA'] = Array();\r\n");
							foreach ($p['V_EXTRA_'.strtoupper($lVal['langAbrv'])] as $vKey => $vValue){
								array_push($file, "\$lang['V_EXTRA'][".$vKey."] = '".$vValue."';\r\n");
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
	
	public function vexteditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selLang.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');		
		
		$p = $this -> getRequest() -> getParams();
		
		//determine and set vextID
		$vextID = null;
		if (isset($p['vid'])){
			$vextID = $p['vid'];	
		}
		
		if ($vextID != null) {
			
			$vext = db_selVExtra(array('vextID'=>$vextID));
			if ($vext != false){				
				$vext = $vext[0];
				$posLang = db_selLang();		
				$p['POS_LANG'] = $posLang;
				
			
				//edit an existing extra
				if(isset($p['vextEdit'])){
					$this -> editvextAction($p);
					$this -> view -> info = $this -> lang['AINFO_8'];
				}
				
				//read vehicle extra file
				$ret = $this -> readvextfileAction($p);
				if (is_array($ret) && isset($ret['return']) && is_array($ret['return'])){
					foreach ($ret['return'] as $key => $value){
						$this -> lang[$key] = $value;
					}
					$this -> view -> lang = $this -> lang;
				}
		
				$this -> view -> vext = $vext;
				$this -> view -> posLang = $posLang;
			}else{
				$this -> _forward('vext'); 
			}
		}else{
			$this -> _forward('vext'); 
			//$this -> vextAction();
		}
	}	
	
	private function readvextfileAction($p){
		$posLang = false;
		if (isset($p['POS_LANG'])){
			$posLang = $p['POS_LANG'];
		}
		if (is_array($posLang)){
			foreach ($posLang as $key => $value){
				$fileName = '../app/modules/default/lang/lang_vextra_'.strtolower($value['langAbrv']).'.php';
				
				if (file_exists($fileName)){
					$file = file($fileName);					
					if (is_array($file)){
						$lang_var = array();
						foreach($file as $key => $row){
							if (strpos($row, 'V_EXTRA')!= false){
								array_push($lang_var, $row);
							}
						}
						//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
						eval(implode('',$lang_var));
						ksort($lang['V_EXTRA']);
						$p['return']['V_EXTRA_'.strtoupper($value['langAbrv'])] = $lang['V_EXTRA'];
					}
				}
			}
			//$this -> view -> lang = $this -> lang;	
		}
		elseif (is_string($posLang)){
			$fileName = '../app/modules/default/lang/lang_vextra_'.strtolower($posLang).'.php';
				
			if (file_exists($fileName)){
				$file = file($fileName);					
				if (is_array($file)){
					$lang_var = array();
					foreach($file as $key => $row){
						if (strpos($row, 'V_EXTRA')!= false){
							array_push($lang_var, $row);
						}
					}
					//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
					eval(implode('',$lang_var));
					ksort($lang['V_EXTRA']);
					$p['return']['V_EXTRA_'.strtoupper($posLang)] = $lang['V_EXTRA'];
				}
			}	
		}
		return $p;
	}
	
	/**
	 * Handle editing process of an vehicle extra
	 */
	private function editvextAction($p){
		
		$vextraID = null;
		if (isset($p['vid']) && is_numeric($p['vid'])){
			$vextraID = $p['vid'];
		}
		
		//check if each possible language should be updated
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG']) && ($vextraID != null)) {
			foreach($p['POS_LANG'] as $key => $value){
				if (isset($value['langAbrv'])){
					//lang_XXX is parameter from screen
					if (isset($p['lang_'.strtolower($value['langAbrv'])])){
						//read V_EXTRA from file
						$return = $this -> readvextfileAction(array('POS_LANG' => $value['langAbrv']));
						if (isset($return['return']['V_EXTRA_'.strtoupper($value['langAbrv'])])){
							$langVExtra = $return['return']['V_EXTRA_'.strtoupper($value['langAbrv'])];
							
							//update vextra name
							$langVExtra[$vextraID] = $p['lang_'.strtolower($value['langAbrv'])];
							$p['V_EXTRA_'.strtoupper($value['langAbrv'])] = $langVExtra;
						}
					}
				}
			}						
			
			$this -> writeUpdateVextfileAction($p);
		}
	}
	
	/**
	 * process the deletion screen
	 */
	public function  vexteraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');	
		
		//Fetch parameter
		$p = $this -> getRequest() -> getParams();
				
		//determine vextID
		$vextID = null;		
		if (isset($p['vid'])) {
			$vextID = $p['vid'];
		}
		
		if ($vextID != null){		
		
			//accomplish the real vext deletion
			if (isset($p['vextErase'])){
				$this -> erasevextAction($p);
			}elseif (isset($p['vextEraseCancel'])){
				$this -> _forward('vext');
			}
			
			
			$vext = db_selVExtra(array('vextID'=>$vextID));
			if (($vext != false) && is_array($vext)){
				$vext = $vext[0];
				$this -> view -> vext = $vext;
				$this -> view -> carExt = db_selCarExt(array('vextID' => $vext['vextID']));		
				$this -> view -> bikeExt = db_selBikeExt(array('vextID' => $vext['vextID']));	
				$this -> view -> truckExt = db_selTruckExt(array('vextID' => $vext['vextID']));		
			}
			else{
				$this -> _forward('vext');
			}
		}
		else{
			$this -> _forward('vext');
		}
	}
	
	/**
	 * Handle the real erase process of an vehicle extra
	 */
	private function erasevextAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVExtra.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_updVExtra.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckExt.php');
		
		$vextraID = null;
		if (isset($p['vid']) && is_numeric($p['vid'])){
			$vextraID = $p['vid'];
		}
		
		if ($vextraID != null) {
			$vextra = db_selVExtra(array('vextID'=>$vextraID));
			if (($vextra != false) && is_array($vextra)){
				$vextra = $vextra[0];
				
				//update VEXT table				
				db_updVExtra(array(System_Properties::SQL_SET => array('erased' => 1)
									, System_Properties::SQL_WHERE => array('vextID' => $vextra['vextID'])));
				
				//delte vehicle extra from car extra
				$carExt = db_selCarExt(array('vextID' => $vextra['vextID']));
				if (($carExt != false) && is_array($carExt)){
					$carExt = $carExt[0];
					db_updCarExt(array(System_Properties::SQL_SET => array('active'=>0)
										, System_Properties::SQL_WHERE => array('lftBEq' => $carExt['lft']
																				, 'rgtLEq' => $carExt['rgt'])
										));
				}
				
				//delete vehicle extra from bike extra
				$bikeExt = db_selBikeExt(array('vextID' => $vextra['vextID']));
				if (($bikeExt != false) && is_array($bikeExt)){
					$bikeExt = $bikeExt[0];
					db_updBikeExt(array(System_Properties::SQL_SET => array('active'=>0)
										, System_Properties::SQL_WHERE => array('lftBEq' => $bikeExt['lft']
																				, 'rgtLEq' => $bikeExt['rgt'])
										));
				}
				
				//delte vehicle from truck extra
				$truckExt = db_selTruckExt(array('vextID' => $vextra['vextID']));
				if (($truckExt != false) && is_array($truckExt)){
					$truckExt = $truckExt[0];
					db_updTruckExt(array(System_Properties::SQL_SET => array('active'=>0)
										, System_Properties::SQL_WHERE => array('lftBEq' => $truckExt['lft']
																				, 'rgtLEq' => $truckExt['rgt'])
										));
				}
			}
		}
	}
	
	/**
	 * This method manage the vehicle categories
	 */
	public function vcatAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selLang.php');
		
		$req  = $this -> getRequest();
		$p = $this -> getRequest() -> getParams();
		
		$posLang = db_selLang();
		$p['POS_LANG'] = $posLang;
		
		//establish a new vehicle extra
		if(isset($p['vcatNew'])){
			$this -> insertvcatAction($p);
		}
	
		$ret = $this -> readvcatfileAction($p);
		if (is_array($ret) && isset($ret['return']) && is_array($ret['return'])){
			foreach ($ret['return'] as $key => $value){
				$this -> lang[$key] = $value;
			}
			$this -> view -> lang = $this -> lang;
		}
		
		$vcat = db_selVCat();
		$this -> view -> posLang = $posLang;
		$this -> view -> vcat = $vcat;
		
		$this -> view -> vcatParam = $p;	
		
	}
	
	private function insertvcatAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_insVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarExt.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeExt.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckExt.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckExt.php');
		
		$lang = $this -> lang;
		$check_insert = true;
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		//cehck possible languages
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			foreach($p['POS_LANG'] as $key => $value){
				if (!isset($value['langAbrv'])){
					$check_insert = false;
				}
				elseif (!isset($p['lang_'.strtolower($value['langAbrv'])])){
					$check_insert = false;
				}
			}
		}		
		
		if ($check_insert == true){
			$vcatID = db_insVCat(array('erased' => 0));
			if (($vcatID != false) && is_numeric($vcatID)){
				$p['vcatID'] = $vcatID;
				$this -> writeInsertVcatFileAction($p);		
			
				//check if car categories should be inserted, parameter from screen
				if (isset($p['carCatActive'])){
					if (isset($p['carCatParent']) && ($p['carCatParent'] != -1)){
						$carCatParent = db_selCarCat(array('carCatID' => $p['carCatParent']));
					}
					else{
						$carCatParent = db_selCarCat(array('lft' => 1));
						if ($carExtParent == false){
							//insert root node if not exist 
							db_insCarCat(array('lft'=>1, 'rgt'=>2
											, 'active'=>1, 'vcatID'=>0
											));
						}
						$carCatParent = db_selCarCat(array('lft' => 1));
					}
					
					if (($carCatParent != false) && (count($carCatParent) == 1)){
						$carCatParent = $carCatParent[0];
						$carCatChildren = db_selCarCat(array('lftBEq' => $carCatParent['lft']
															, 'rgtLEq' => $carCatParent['rgt']
															, 'level' => ($carCatParent['level']+1)
															));
						db_updCarCat(array(	System_Properties::SQL_SET => array('incRgt'=>2)
											, System_Properties::SQL_WHERE=>array('rgtBEq'=>$carCatParent['rgt'])
											));
						db_updCarCat(array(	System_Properties::SQL_SET => array('incLft'=>2)
											, System_Properties::SQL_WHERE=>array('lftBEq'=>$carCatParent['rgt'])
											));
						db_insCarCat(array('vcatID' => $p['vcatID']
											, 'active' => $carCatParent['active']
											, 'lft' => $carCatParent['rgt']
											, 'rgt' => $carCatParent['rgt']+1
										));
					}				
				}
				
				//check if bike category should be inserted
				if (isset($p['bikeCatActive'])){
					if (isset($p['bikeCatParent']) && ($p['bikeCatParent'] != -1)){
						$bikeCatParent = db_selBikeCat(array('bikeCatID' => $p['bikeCatParent']));
					}
					else{
						$bikeCatParent = db_selBikeCat(array('lft' => 1));
						if ($bikeCatParent == false){
							//insert root node if not exist 
							db_insBikeCat(array('lft'=>1, 'rgt'=>2
												, 'active'=>1, 'vcatID'=>0
											));
						}
						$bikeCatParent = db_selBikeCat(array('lft' => 1));
					}
					
					if (($bikeCatParent != false) && (count($bikeCatParent) == 1)){
						$bikeCatParent = $bikeCatParent[0];
						$bikeCatChildren = db_selBikeCat(array('lftBEq' => $bikeCatParent['lft']
															, 'rgtLEq' => $bikeCatParent['rgt']
															, 'level' => ($bikeCatParent['level']+1)
															));
						db_updBikeCat(array(	System_Properties::SQL_SET => array('incRgt'=>2)
											, System_Properties::SQL_WHERE=>array('rgtBEq'=>$bikeCatParent['rgt'])
											));
						db_updBikeCat(array(	System_Properties::SQL_SET => array('incLft'=>2)
											, System_Properties::SQL_WHERE=>array('lftBEq'=>$bikeCatParent['rgt'])
											));
						db_insBikeCat(array('vcatID' => $p['vcatID']
											, 'active' => $bikeCatParent['active'] 
											, 'lft' => $bikeCatParent['rgt']
											, 'rgt' => $bikeCatParent['rgt']+1
										));
					}				
				}
				//check if truck category should be inserted
				if (isset($p['truckCatActive'])){					
					if (isset($p['truckCatParent']) && ($p['truckCatParent'] != -1)){
						$truckCatParent = db_selTruckCat(array('truckCatID' => $p['truckCatParent']));
					}
					else{
						$truckCatParent = db_selTruckCat(array('lft' => 1));
						if ($truckCatParent == false){
							//insert root node if not exist 
							db_insTruckCat(array('lft'=>1, 'rgt'=>2
												, 'active'=>1, 'vcatID'=>0
											));
						}
						$truckCatParent = db_selTruckCat(array('lft' => 1));
					}
					
					if (($truckCatParent != false) && (count($truckCatParent) == 1)){
						$truckCatParent = $truckCatParent[0];
						$truckCatChildren = db_selTruckCat(array('lftBEq' => $truckCatParent['lft']
															, 'rgtLEq' => $truckCatParent['rgt']
															, 'level' => ($truckCatParent['level']+1)
															));
						db_updTruckCat(array(	System_Properties::SQL_SET => array('incRgt'=>2)
											, System_Properties::SQL_WHERE=>array('rgtBEq'=>$truckCatParent['rgt'])
											));
						db_updTruckCat(array(	System_Properties::SQL_SET => array('incLft'=>2)
											, System_Properties::SQL_WHERE=>array('lftBEq'=>$truckCatParent['rgt'])
											));
						db_insTruckCat(array('vcatID' => $p['vcatID']
											, 'active' => $truckCatParent['active'] 
											, 'lft' => $truckCatParent['rgt']
											, 'rgt' => $truckCatParent['rgt']+1
										));
					}				
				}
			}
		}else{
			$this -> view -> error = $lang['AERR_28'];
		}
	}
	
	public function vcateditAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/default/db_selLang.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');		
		
		$p = $this -> getRequest() -> getParams();
		
		//determine and set vcatID
		$vcatID = null;
		if (isset($p['vid'])){
			$vcatID = $p['vid'];	
		}
		
		if ($vcatID != null) {
			
			$vcat = db_selVCat(array('vcatID'=>$vcatID));
			if ($vcat != false){
				$vcat = $vcat[0];
				$posLang = db_selLang();		
				$p['POS_LANG'] = $posLang;
				
			
				//edit an existing category
				if(isset($p['vcatEdit'])){
					$this -> editvcatAction($p);
					$this -> view -> info = $this -> lang['AINFO_9'];
				}
				
				//read vehicle category file
				$ret = $this -> readvcatfileAction($p);
				if (is_array($ret) && isset($ret['return']) && is_array($ret['return'])){
					foreach ($ret['return'] as $key => $value){
						$this -> lang[$key] = $value;
					}
					$this -> view -> lang = $this -> lang;
				}
		
				$this -> view -> vcat = $vcat;
				$this -> view -> posLang = $posLang;
			}else{
				$this -> _forward('vcat'); 
			}
		}else{
			$this -> _forward('vcat'); 
			//$this -> vextAction();
		}
	}	
	
	/**
	 * This function insert a vehicle category in the corresponding language files
	 */
	private function writeInsertVcatFileAction($p){
		if (isset($p['vcatID']) && isset($p['POS_LANG']) && is_array($p['POS_LANG'])){
			//$posLang = db_selLang(array('langID'=>$p['lang']));
			foreach ($p['POS_LANG'] as $key => $posLang){
				if (isset($posLang['langAbrv'])){
					$langVcatID  = 'lang_'.strtolower($posLang['langAbrv']);
					$fileName = '../app/modules/default/lang/lang_vcat_'.strtolower($posLang['langAbrv']).'.php';
					if (file_exists($fileName)){
						$file = file($fileName);
						//separate V_CAT lines from rest
						foreach ($file as $key => $val) {
							$file[$key] = str_ireplace('?>', '', $val);
						}
						//push new element at th end of the V_CAT array
						if(isset($p[$langVcatID])){
							array_push($file, "\$lang['V_CAT'][".$p['vcatID']."] = '".$p[$langVcatID]."';\r\n?>");
						}else{
							array_push($file, "\$lang['V_CAT'][".$p['vcatID']."] = '';\r\n?>");
						}
											
						//write the new array into file
						$fp = fopen($fileName, 'w');
						if($fp != false){
							fwrite($fp, implode("", $file));		
							fclose($fp);			
							$this -> lang['V_CAT'][$p['vcatID']] = $p[$langVcatID];
							$this -> view -> lang = $this -> lang;
						}
					}
				}
			}
		}
	}
	
	private function readvcatfileAction($p){
		$posLang = false;
		if (isset($p['POS_LANG'])){
			$posLang = $p['POS_LANG'];
		}
		if (is_array($posLang)){
			foreach ($posLang as $key => $value){
				$fileName = '../app/modules/default/lang/lang_vcat_'.strtolower($value['langAbrv']).'.php';
				
				if (file_exists($fileName)){
					$file = file($fileName);					
					if (is_array($file)){
						$lang_var = array();
						foreach($file as $key => $row){
							if (strpos($row, 'V_CAT')!= false){
								array_push($lang_var, $row);
							}
						}
						//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
						eval(implode('',$lang_var));
						ksort($lang['V_CAT']);
						$p['return']['V_CAT_'.strtoupper($value['langAbrv'])] = $lang['V_CAT'];
					}
				}
			}
			//$this -> view -> lang = $this -> lang;	
		}
		elseif (is_string($posLang)){
			$fileName = '../app/modules/default/lang/lang_vcat_'.strtolower($posLang).'.php';
				
			if (file_exists($fileName)){
				$file = file($fileName);					
				if (is_array($file)){
					$lang_var = array();
					foreach($file as $key => $row){
						if (strpos($row, 'V_CAT')!= false){
							array_push($lang_var, $row);
						}
					}
					//$lang_var = str_ireplace('V_EXTRA_'.strtoupper($value['langAbrv']), '', $lang_var);
					eval(implode('',$lang_var));
					ksort($lang['V_CAT']);
					$p['return']['V_CAT_'.strtoupper($posLang)] = $lang['V_CAT'];
				}
			}	
		}
		return $p;
	}
	
	/**
	 * This function update a vehicle category in all language files
	 */	
	private function writeUpdateVcatfileAction($p){
		//check if each possible language should be updated
		if (isset($p['POS_LANG']) && is_array($p['POS_LANG'])) {
			foreach($p['POS_LANG'] as $lKey => $lVal){
				if (isset($lVal['langAbrv'])){
					//lang_XXX is parameter from screen
					if (isset($p['V_CAT_'.strtoupper($lVal['langAbrv'])])
						&& is_array($p['V_CAT_'.strtoupper($lVal['langAbrv'])])){
						$langVextID  = 'lang_'.strtolower($lVal['langAbrv']);
						$fileName = '../app/modules/default/lang/lang_vcat_'.strtolower($lVal['langAbrv']).'.php';
						if (file_exists($fileName)){
							$fileOri = file($fileName);
							$file = array();							
							//separate V_CAT lines from rest
							foreach ($fileOri as $fKey => $fValue) {
								if ((stristr($fValue, 'V_CAT') == false) 
									&& (stristr($fValue, '?>') == false)){
									array_push($file, $fValue);
								}
							}
							
							array_push($file, "\$lang['V_CAT'] = Array();\r\n");
							foreach ($p['V_CAT_'.strtoupper($lVal['langAbrv'])] as $vKey => $vValue){
								array_push($file, "\$lang['V_CAT'][".$vKey."] = '".$vValue."';\r\n");
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
	
	/**
	 * manage the deletion of vehicle categories
	 */
	public function  vcateraseAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');	
		
		//Fetch parameter
		$p = $this -> getRequest() -> getParams();
				
		//determine vextID
		$vcatID = null;		
		if (isset($p['vid'])) {
			$vcatID = $p['vid'];
		}
		
		if ($vcatID != null){
			
		
			//accomplish the real vext deletion
			if (isset($p['vcatErase'])){
				$this -> erasevcatAction($p);
			}elseif (isset($p['vcatEraseCancel'])){
				$this -> _forward('vext');
			}
			
			
			$vcat = db_selVCat(array('vcatID'=>$vcatID));
			if (($vcat != false) && is_array($vcat)){
				$vcat = $vcat[0];
				$this -> view -> vcat = $vcat;
				$this -> view -> carCat = db_selCarCat(array('vcatID' => $vcat['vcatID']));		
				$this -> view -> bikeCat = db_selBikeCat(array('vcatID' => $vcat['vcatID']));	
				$this -> view -> truckCat = db_selTruckCat(array('vcatID' => $vcat['vcatID']));		
			}
			else{
				$this -> _forward('vcat');
			}
		}
		else{
			$this -> _forward('vcat');
		}
	}
	

	
	/**
	 * Handle the real erase process of an vehicle category
	 */
	private function erasevcatAction($p){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selVCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_updVCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckCat.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_updCarCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_updBikeCat.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_updTruckCat.php');
		
		$vcatID = null;
		if (isset($p['vid']) && is_numeric($p['vid'])){
			$vcatID = $p['vid'];
		}
		
		if ($vcatID != null) {
			$vcat = db_selVCat(array('vcatID'=>$vcatID));
			if (($vcat != false) && is_array($vcat)){
				$vcat = $vcat[0];
				
				//delete category from vcat table
				db_updVCat(array(System_Properties::SQL_SET => array('erased' => 1)
								, System_Properties::SQL_WHERE => array('vcatID' => $vcat['vcatID'])));
				
				//delte vehicle category from car category
				$carCat = db_selCarCat(array('vcatID' => $vcat['vcatID']));
				if (($carCat != false) && is_array($carCat)){
					$carCat = $carCat[0];
					db_updCarCat(array(System_Properties::SQL_SET => array('active'=>0)
										, System_Properties::SQL_WHERE => array('lftBEq' => $carCat['lft']
																				, 'rgtLEq' => $carCat['rgt'])
										));
				}
				
				//delete vehicle category from bike category
				$bikeCat = db_selBikeCat(array('vcatID' => $vcat['vcatID']));
				if (($bikeCat != false) && is_array($bikeCat)){
					$bikeCat = $bikeCat[0];
					db_updBikeCat(array(System_Properties::SQL_SET => array('active'=>0)
										, System_Properties::SQL_WHERE => array('lftBEq' => $bikeCat['lft']
																				, 'rgtLEq' => $bikeCat['rgt'])
										));
				}
				
				//delte vehicle from truck cat
				$truckCat = db_selTruckCat(array('vcatID' => $vcat['vcatID']));
				if (($truckCat != false) && is_array($truckCat)){
					$truckCat = $truckCat[0];
					db_updTruckCat(array(System_Properties::SQL_SET => array('active'=>0)
										, System_Properties::SQL_WHERE => array('lftBEq' => $truckCat['lft']
																				, 'rgtLEq' => $truckCat['rgt'])
										));
				}
			}
		}
	}
	
//******** OLD ***************************************************************************************	
	

	
	/**
	 * This action controller function afford the management of brand models
	 *//*
	public function branddetailAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');		
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		
		$req = $this -> getRequest();
		$p = $req -> getParams();
		//Check if brand id is set
		if (isset($p['bid'])){
			//Get brand details
			$brandDetail = db_selBrand(array('brandID' => $p['bid']));
			if ($brandDetail == false){				
				$this -> _forward('index');
			}else{
				$brandDetail = $brandDetail[0];
				$this -> view -> brand = $brandDetail;
				
				//create a new brand model
				if (isset($p['brandModelNew'])){
					$p = $this -> insertbrandmodelAction($p);
					
					if (isset($p['error'])) {
						$this -> view -> error = $p['error'];
					}
					if (isset($p['info'])) {
						$this -> view -> info = $p['info'];
					}		
				}
				
				
				//get car brand details
				$carBrandDetail = db_selCarBrand(array('brandID' => $p['bid']));
				if ($carBrandDetail != false){
					$carBrandDetail = $carBrandDetail[0];
					$carModel = db_selCarModel(array('carBrandID' => $carBrandDetail['carBrandID']
													, 'orderby' => array(array('col' => 'carModelName'))
													));
					$this -> view -> carModel = $carModel;
					$this -> view -> carBrand = $carBrandDetail;
				}
				
				//get bike brand details
				$bikeBrandDetail = db_selBikeBrand(array('brandID' => $p['bid']));
				if ($bikeBrandDetail != false){
					$bikeBrandDetail = $bikeBrandDetail[0];
					$bikeModel = db_selBikeModel(array('bikeBrandID' => $bikeBrandDetail['bikeBrandID']
													, 'orderby' => array(array('col' => 'bikeModelName'))
													));
					$this -> view -> bikeModel = $bikeModel;
					$this -> view -> bikeBrand = $bikeBrandDetail;
				}
				
				//get truck brand details
				$truckBrandDetail = db_selTruckBrand(array('brandID' => $p['bid']));
				if ($truckBrandDetail != false){
					$truckBrandDetail = $truckBrandDetail[0];
					$truckModel = db_selTruckModel(array('truckBrandID' => $truckBrandDetail['truckBrandID']
													, 'orderby' => array(array('col' => 'truckModelName'))
													));
					$this -> view -> truckModel = $truckModel;
					$this -> view -> truckBrand = $truckBrandDetail;
				}
			}
		}else{
			$this -> _forward('index');
		}
	}
	
	private function insertbrandmodelAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		if (isset($p['bid']) && isset($p['brandModelName']) && isset($p['vType'])) {
			$brandDetail = db_selBrand(array('brandID' => $p['bid']));
			if (($brandDetail != false) && is_array($brandDetail)){
				$brandDetail = $brandDetail[0];
				$vType = $p['vType'];
				
				switch ($vType){
					case System_Properties::CAR_ABRV : $p = $this -> insertcarmodelAction($p);
														break;														
					case System_Properties::BIKE_ABRV : $p = $this -> insertbikemodelAction($p);
														break;														
					case System_Properties::TRUCK_ABRV : $p = $this -> inserttruckmodelAction($p);
														break;														
				}
			}	
		}
		return $p;
	}
	private function insertcarmodelAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_selCarModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/car/db_insCarModel.php');
		
		$lang = $this -> lang;		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$carBrandDetail = db_selCarBrand(array('brandID' => $p['bid']));
		if (($carBrandDetail != false) && is_array($carBrandDetail) && (count($carBrandDetail) > 0)){
			$carBrandDetail = $carBrandDetail[0];
			$carModelDetail = db_selCarModel(array('carModelName' => $p['brandModelName']));
			if ($carModelDetail == false){
				if (db_insCarModel(array('carModelName' => $p['brandModelName'], 'carBrandID' => $carBrandDetail['carBrandID'])) != false){
					$p['info'] = $lang['AINFO_7'];
				}else{
					$p['error'] = $lang['AERR_25'];
				}
			}
			elseif (is_array($carModelDetail) && (count($carModelDetail) > 0)){
				$carModelDetail = $carModelDetail[0];
				if ($carModelDetail['carBrandID'] != $carBrandDetail['carBrandID']){					
				
					if (db_insCarModel(array('carModelName' => $p['brandModelName'], 'carBrandID' => $carBrandDetail['carBrandID'])) != false){
						$p['info'] = $lang['AINFO_7'];
					}else{
						$p['error'] = $lang['AERR_25'];
					}
				}else{
					$p['error'] = $lang['AERR_26'];
				}
			}
		}
		return $p;
	}
	
	private function insertbikemodelAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_selBikeModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/bike/db_insBikeModel.php');
		
		$lang = $this -> lang;		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$bikeBrandDetail = db_selBikeBrand(array('brandID' => $p['bid']));
		if (($bikeBrandDetail != false) && is_array($bikeBrandDetail) && (count($bikeBrandDetail) > 0)){
			$bikeBrandDetail = $bikeBrandDetail[0];
			$bikeModelDetail = db_selBikeModel(array('bikeModelName' => $p['brandModelName']));
			if ($bikeModelDetail == false){
				if (db_insBikeModel(array('bikeModelName' => $p['brandModelName'], 'bikeBrandID' => $bikeBrandDetail['bikeBrandID'])) != false){
					$p['info'] = $lang['AINFO_7'];
				}else{
					$p['error'] = $lang['AERR_25'];
				}
			}
			elseif (is_array($bikeModelDetail) && (count($bikeModelDetail) > 0)){
				$bikeModelDetail = $bikeModelDetail[0];
				if ($bikeModelDetail['bikeBrandID'] != $bikeBrandDetail['bikeBrandID']){					
				
					if (db_insBikeModel(array('bikeModelName' => $p['brandModelName'], 'bikeBrandID' => $bikeBrandDetail['bikeBrandID'])) != false){
						$p['info'] = $lang['AINFO_7'];
					}else{
						$p['error'] = $lang['AERR_25'];
					}
				}else{
					$p['error'] = $lang['AERR_26'];
				}
			}
		}
		return $p;
	}
	
	private function inserttruckmodelAction($p = null){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckBrand.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_selTruckModel.php');
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/truck/db_insTruckModel.php');
		
		$lang = $this -> lang;		
		if ($p == null){
			$p = $this -> getRequest() -> getParams();
		}
		
		$truckBrandDetail = db_selTruckBrand(array('brandID' => $p['bid']));
		if (($truckBrandDetail != false) && is_array($truckBrandDetail) && (count($truckBrandDetail) > 0)){
			$truckBrandDetail = $truckBrandDetail[0];
			$truckModelDetail = db_selTruckModel(array('truckModelName' => $p['brandModelName']));
			if ($truckModelDetail == false){
				if (db_insTruckModel(array('truckModelName' => $p['brandModelName'], 'truckBrandID' => $truckBrandDetail['truckBrandID'])) != false){
					$p['info'] = $lang['AINFO_7'];
				}else{
					$p['error'] = $lang['AERR_25'];
				}
			}
			elseif (is_array($truckModelDetail) && (count($truckModelDetail) > 0)){
				$truckModelDetail = $truckModelDetail[0];
				if ($truckModelDetail['truckBrandID'] != $truckBrandDetail['truckBrandID']){					
				
					if (db_insTruckModel(array('truckModelName' => $p['brandModelName'], 'truckBrandID' => $truckBrandDetail['truckBrandID'])) != false){
						$p['info'] = $lang['AINFO_7'];
					}else{
						$p['error'] = $lang['AERR_25'];
					}
				}else{
					$p['error'] = $lang['AERR_26'];
				}
			}
		}
		return $p;
	}
	*/
	
	/**
	 * This action controller facilitate the spreading of a new model
	 *//*
	public function brandmodelnewAction(){
		include_once(System_Properties::ADMIN_MOD_PATH.'/models/system/db_selBrand.php');
		
		$req = $this -> getRequest();
		$p = $req -> getParams();
		//Check if brand id is set
		if (isset($p['bid'])){
			//Get brand details
			$brandDetail = db_selBrand(array('brandID' => $p['bid']));
			if ($brandDetail == false){				
				$this -> _forward('index');
			}else{
				$brandDetail = $brandDetail[0];
				$this -> view -> brand = $brandDetail;
			}
		}else{
			$this -> _forward('index');
		}
	}
	*/
}
?>