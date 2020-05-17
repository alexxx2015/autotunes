<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This class contains system properties
 *********************************************************************************/

class System_Authority{
	
	const ROOT_ACCESS = 'ROOT_ACCESS';
	
	//car authorities
	const CAR_CREATE = 'CAR_CREATE';
	const CAR_DELETE = 'CAR_DELETE';
	const CAR_EDIT = 'CAR_EDIT';
	
	//bike authorities
	const BIKE_CREATE = 'BIKE_CREATE';
	const BIKE_DELETE = 'BIKE_DELETE';
	const BIKE_EDIT = 'BIKE_EDIT';
	
	//lkw authorities
	const TRUCK_CREATE = 'TRUCK_CREATE';
	const TRUCK_DELETE = 'TRUCK_DELETE';
	const TRUCK_EDIT = 'TRUCK_EDIT';
	
	//Group authorities
	const GROUP_CREATE = 'GROUP_CREATE';
	const GROUP_DELETE = 'GROUP_DELETE';
	const GROUP_EDIT = 'GROUP_EDIT';
	const GROUP_SHOW = 'GROUP_SHOW';
	
	//user authorities
	const USER_CREATE = 'USER_CREATE';
	const USER_EDIT = 'USER_EDIT';
	const USER_DELETE = 'USER_DELETE';
	const USER_SHOW = 'USER_SHOW';
	
	//brand authorities
	const BRAND_CREATE = 'BRAND_CREATE';
	const BRAND_EDIT = 'BRAND_EDIT';
	const BRAND_DELETE = 'BRAND_DELETE';
	
	//car modell authority
	const CAR_MODEL_CREATE = 'CAR_MODEL_CREATE';
	const CAR_MODEL_EDIT = 'CAR_MODEL_EDIT';
	const CAR_MODEL_DELETE = 'CAR_MODEL_DELETE';
	
	//bike modell authority
	const BIKE_MODEL_CREATE = 'BIKE_MODEL_CREATE';
	const BIKE_MODEL_EDIT = 'BIKE_MODEL_EDIT';
	const BIKE_MODEL_DELETE = 'BIKE_MODEL_DELETE';
	
	//lkw modell authority
	const TRUCK_MODEL_CREATE = 'TRUCK_MODEL_CREATE';
	const TRUCK_MODEL_EDIT = 'TRUCK_MODEL_EDIT';
	const TRUCK_MODEL_DELETE = 'TRUCK_MODEL_DELETE';
	
	//VCAT authority
	const VEXT_CREATE = 'VEXT_CREATE';
	const VEXT_EDIT = 'VEXT_EDIT';
	const VEXT_DELETE = 'VEXT_DELETE';
	
	//CAR_EXTRA authority
	const CAR_EXTRA_CREATE = 'CAR_EXTRA_CREATE';
	const CAR_EXTRA_EDIT = 'CAR_EXTRA_EDIT';
	const CAR_EXTRA_DELETE = 'CAR_EXTRA_DELETE';
	
	//BIKE_EXTRA authority
	const BIKE_EXTRA_CREATE = 'BIKE_EXTRA_CREATE';
	const BIKE_EXTRA_EDIT = 'BIKE_EXTRA_EDIT';
	const BIKE_EXTRA_DELETE = 'BIKE_EXTRA_DELETE';
	
	//TRUCK_EXTRA authority
	const TRUCK_EXTRA_CREATE = 'TRUCK_EXTRA_CREATE';
	const TRUCK_EXTRA_EDIT = 'TRUCK_EXTRA_EDIT';
	const TRUCK_EXTRA_DELETE = 'TRUCK_EXTRA_DELETE';
	
	//Admin authorities
	const ADMIN_ACCESS = 'ADMIN_ACCESS';
	const ADMIN_SYS_EDIT = 'ADMIN_SYS_EDIT';
	const ADMIN_SEND_USER_MAIL = 'ADMIN_SEND_USER_MAIL';
	
	
	//VCAT authority
	const VCAT_CREATE = 'VCAT_CREATE';
	const VCAT_EDIT = 'VCAT_EDIT';
	const VCAT_DELETE = 'VCAT_DELETE';
	
	//CAR_CAT authority
	const CAR_CAT_CREATE = 'CAR_CAT_CREATE';
	const CAR_CAT_EDIT = 'CAR_CAT_EDIT';
	const CAR_CAT_DELETE = 'CAR_CAT_DELETE';
	
	//BIKE_CAT authority
	const BIKE_CAT_CREATE = 'BIKE_CAT_CREATE';
	const BIKE_CAT_EDIT = 'BIKE_CAT_EDIT';
	const BIKE_CAT_DELETE = 'BIKE_CAT_DELETE';
	
	//TRUCK_CAT authority
	const TRUCK_CAT_CREATE = 'TRUCK_CAT_CREATE';
	const TRUCK_CAT_EDIT = 'TRUCK_CAT_EDIT';
	const TRUCK_CAT_DELETE = 'TRUCK_CAT_DELETE';
}
?>