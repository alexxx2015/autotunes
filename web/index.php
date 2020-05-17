<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the bootstrap file
 *********************************************************************************/

//Start session
include_once('Zend/Session.php');
Zend_Session::start();

$t = microtime();
error_reporting(E_ALL);
ini_set('include_path', ini_get('include_path').';./../app;./../app/modules');
//ini_set('display_errors', 1);

//Front Controller
include_once('Zend/Controller/Front.php');
//Router
include_once('Zend/Controller/Router/Route.php');
//system properties
include_once('classes/System_Properties.php');

$frontCtrl = Zend_Controller_Front::getInstance();

//Set the module directory
$frontCtrl -> setControllerDirectory(array(
	'default' => '../app/modules/default/controllers',
	'admin' => '../app/modules/admin/controllers'
));

//Set Router definition
$route = new Zend_Controller_Router_Route(
	'car/:carID',
	array(	'controller' => 'car',
			'action' => 'detail'),
	array(	'carID' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('carDetail',$route);

//car
$route = new Zend_Controller_Router_Route(
	'car/:carID/:page',
	array(	'controller' => 'car',
			'action' => 'detail'),
	array(	'carID' => '\d+',
			'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('carDetailPage',$route);

$route = new Zend_Controller_Router_Route(
	'car/:carID/:page/:car_desc',
	array(	'controller' => 'car',
			'action' => 'detail'),
	array(	'carID' => '\d+',
			'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('carDetailPageDesc',$route);

$route = new Zend_Controller_Router_Route(
	'car/:carID/:page/pid/:pid',
	array(	'controller' => 'car',
			'action' => 'detail'),
	array(	'carID' => '\d+',
			'page' => '\d+',
			'pid' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('carDetailPic',$route);


$route = new Zend_Controller_Router_Route(
	'car/search/:page',
	array(	'controller' => 'car',
			'action' => 'search'),
	array(	'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('carSearch',$route);


$route = new Zend_Controller_Router_Route(
	'member/mycarads/:page',
	array(	'controller' => 'member',
			'action' => 'mycarads'),
	array(	'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('myCarAds',$route);


//bike 
$route = new Zend_Controller_Router_Route(
	'bike/:bikeID',
	array(	'controller' => 'bike',
			'action' => 'detail'),
	array(	'bikeID' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('bikeDetail',$route);

$route = new Zend_Controller_Router_Route(
	'bike/:bikeID/:page',
	array(	'controller' => 'bike',
			'action' => 'detail'),
	array(	'bikeID' => '\d+',
			'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('bikeDetailPage',$route);

$route = new Zend_Controller_Router_Route(
	'bike/:bikeID/:page/:bike_desc',
	array(	'controller' => 'bike',
			'action' => 'detail'),
	array(	'bikeID' => '\d+',
			'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('bikeDetailPageDesc',$route);

$route = new Zend_Controller_Router_Route(
	'bike/search/:page',
	array(	'controller' => 'bike',
			'action' => 'search'),
	array(	'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('bikeSearch',$route);

//truck 
$route = new Zend_Controller_Router_Route(
	'truck/:truckID',
	array(	'controller' => 'truck',
			'action' => 'detail'),
	array(	'truckID' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('truckDetail',$route);

$route = new Zend_Controller_Router_Route(
	'truck/:truckID/:page',
	array(	'controller' => 'truck',
			'action' => 'detail'),
	array(	'truckID' => '\d+',
			'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('truckDetailPage',$route);

$route = new Zend_Controller_Router_Route(
	'truck/:truckID/:page/:truck_desc',
	array(	'controller' => 'truck',
			'action' => 'detail'),
	array(	'truckID' => '\d+',
			'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('truckDetailPageDesc',$route);

$route = new Zend_Controller_Router_Route(
	'truck/search/:page',
	array(	'controller' => 'truck',
			'action' => 'search'),
	array(	'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('truckSearch',$route);

//Dealer
$route = new Zend_Controller_Router_Route(
	'dealer/:dealerID',
	array(	'controller' => 'Dealer',
			'action' => 'index'),
	array(	'dealerID' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('dealerIndex',$route);

$route = new Zend_Controller_Router_Route(
	'dealer/:dealerID/car',
	array(	'controller' => 'Dealer',
			'action' => 'car'),
	array(	'dealerID' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('dealerCar',$route);

$route = new Zend_Controller_Router_Route(
	'dealer/:dealerID/car/:page',
	array(	'controller' => 'Dealer',
			'action' => 'car'),
	array(	'dealerID' => '\d+'
			, 'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('dealerCarPage',$route);

$route = new Zend_Controller_Router_Route(
	'dealer/:dealerID/bike',
	array(	'controller' => 'Dealer',
			'action' => 'bike'),
	array(	'dealerID' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('dealerBike',$route);

$route = new Zend_Controller_Router_Route(
	'dealer/:dealerID/bike/:page',
	array(	'controller' => 'Dealer',
			'action' => 'bike'),
	array(	'dealerID' => '\d+'
			, 'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('dealerBikePage',$route);

$route = new Zend_Controller_Router_Route(
	'dealer/:dealerID/truck',
	array(	'controller' => 'dealer',
			'action' => 'truck'),
	array(	'dealerID' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('dealerTruck',$route);

$route = new Zend_Controller_Router_Route(
	'dealer/:dealerID/truck/:page',
	array(	'controller' => 'dealer',
			'action' => 'truck'),
	array(	'dealerID' => '\d+'
			, 'page' => '\d+')
);
$router = $frontCtrl -> getRouter() -> addRoute('dealerTruckPage',$route);

$frontCtrl -> throwExceptions(false);

// Only for development
include_once('Zend/Session/Namespace.php');

/*
include_once('admin/models/user/db_selUser.php');
$user = db_selUser(array('userID' => 1));
$userNS = new Zend_Session_Namespace(System_Properties::USER_NS);
$userNS->userData = $user[0];
$userNS->userLogged = true;
*/



try {
	$frontCtrl -> dispatch();	
} catch (Exception $e) {
	echo $e -> getTraceAsString().'<br>'.$e->getMessage();
}
//echo microtime().'<br>';
//echo microtime()-$t;

?>