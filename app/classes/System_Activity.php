<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This class contains system properties
 *********************************************************************************/

class System_Activity{
	
	//car activities
	const CAR_CREATE = 'CAR_CREATE';
	const CAR_DELETE = 'CAR_DELETE';
	const CAR_EDIT = 'CAR_EDIT';
	const CAR_PHOTO_UPLOAD = 'CAR_PHOTO_UPLOAD';
	const CAR_PHOTO_PHOTO_DELETE = 'CAR_PHOTO_UPLOAD_DELETE';
	const CAR_PHOTO_DELETE = 'CAR_PHOTO_DELETE';
	
	//bike activities
	const BIKE_CREATE = 'BIKE_CREATE';
	const BIKE_DELETE = 'BIKE_DELETE';
	const BIKE_EDIT = 'BIKE_EDIT';
	const BIKE_PHOTO_UPLOAD = 'BIKE_PHOTO_UPLOAD';
	const BIKE_PHOTO_PHOTO_DELETE = 'BIKE_PHOTO_UPLOAD_DELETE';
	const BIKE_PHOTO_DELETE = 'BIKE_PHOTO_DELETE';
	
	//lkw activities
	const LKW_CREATE = 'LKW_CREATE';
	const LKW_DELETE = 'LKW_DELETE';
	const LKW_EDIT = 'LKW_EDIT';
	const LKW_PHOTO_UPLOAD = 'LKW_PHOTO_UPLOAD';
	const LKW_PHOTO_PHOTO_DELETE = 'LKW_PHOTO_UPLOAD_DELETE';
	const LKW_PHOTO_DELETE = 'LKW_PHOTO_DELETE';
	
	//Group activities
	const GROUP_CREATE = 'GROUP_CREATE';
	const GROUP_DELETE = 'GROUP_DELETE';
	const GROUP_EDIT = 'GROUP_EDIT';
	const GROUP_SHOW = 'GROUP_SHOW';
	
	//user activities
	const USER_CREATE = 'USER_CREATE';
	const USER_EDIT = 'USER_EDIT';
	const USER_DELETE = 'USER_DELETE';
	const USER_SHOW = 'USER_SHOW';
	const USER_LOGGED = 'USER_LOGGED';
	const USER_SEND_EMAIL = 'USER_SEND_EMAIL';
	
	//brand activities
	const BRAND_CREATE = 'BRAND_CREATE';
	const BRAND_EDIT = 'BRAND_EDIT';
	const BRAND_DELETE = 'BRAND_DELETE';
	
	//car modell activities
	const CAR_MODEL_CREATE = 'CAR_MODEL_CREATE';
	const CAR_MODEL_EDIT = 'CAR_MODEL_EDIT';
	const CAR_MODEL_DELETE = 'CAR_MODEL_DELETE';
	
	//VEXT activities
	const VEXT_CREATE = 'VEXT_CREATE';
	const VEXT_EDIT = 'VEXT_EDIT';
	const VEXT_DELETE = 'VEXT_DELETE';
	
	//CAR_EXTRA activities
	const CAR_EXTRA_CREATE = 'CAR_EXTRA_CREATE';
	const CAR_EXTRA_EDIT = 'CAR_EXTRA_EDIT';
	const CAR_EXTRA_DELETE = 'CAR_EXTRA_DELETE';
	
	//BIKE_EXTRA activities
	const BIKE_EXTRA_CREATE = 'BIKE_EXTRA_CREATE';
	const BIKE_EXTRA_EDIT = 'BIKE_EXTRA_EDIT';
	const BIKE_EXTRA_DELETE = 'BIKE_EXTRA_DELETE';
	
	//TRUCK_EXTRA activities
	const TRUCK_EXTRA_CREATE = 'TRUCK_EXTRA_CREATE';
	const TRUCK_EXTRA_EDIT = 'TRUCK_EXTRA_EDIT';
	const TRUCK_EXTRA_DELETE = 'TRUCK_EXTRA_DELETE';
	
	//Admin activities
	const ADMIN_LOGGED = 'ADMIN_ACCESS';
	const ADMIN_SYS_EDIT = 'ADMIN_SYS_EDIT';
	const ADMIN_SEND_USER_MAIL = 'ADMIN_SEND_USER_MAIL';
	
}
?>