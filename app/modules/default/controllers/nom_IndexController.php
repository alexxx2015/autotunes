<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100804
 * Desc:		This is the controller for INDEX
 *********************************************************************************/
include_once('classes/AbstractController.php');
class IndexController extends Zend_Controller_Action{
	
	public function preDispatch(){
		parent::preDispatch();
		
		$this -> view -> tmpl = $this->tmpl;
		$this -> view -> lang = $this -> lang;	
	}
	
	
	public function indexAction(){
		
	}
	
	public function offlineAction(){
	}	
}
?>