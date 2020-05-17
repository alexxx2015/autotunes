<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for the administration INDEX
 *********************************************************************************/
include_once('classes/AbstractController.php');
class Admin_CarmatchController extends AbstractController{
	
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
}
?>