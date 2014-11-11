<?php
require_once('i_action_switch.php');

abstract class Ajax implements IActionSwitch{
	protected $action;
	protected $error = false;
	protected $errorMessage;
	
	public function __construct(){
		$this->action = $this->getAjaxAction();
		$result = $this->doAction();
		$this->render($result);
	}
	
	public function getAjaxAction(){
		return isset($_GET['ajax']) ? $_GET['ajax'] : null;
	}
	
	public function render($result){
		$result = (!is_array($result)) ? array('content' => $result) : $result;
		echo json_encode($result);
		exit;
	}
	
	public function defaultError(){
		return array('error' => true, 'errorMessage' => 'Could not perform ajax request.');
	}
}