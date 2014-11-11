<?php

require_once('ajax_controller.php');
require_once('..\models\scrape_model.php');

class ScrapeController extends Ajax{
	private $scrapeModel;
	
	public function __construct(){
		parent::__construct();
		$this->scrapeModel = new ScrapeModel();
	}
	
	public function doAction(){
		switch($this->action){
			case 'asd':
				return $this->asd();
				break;
			case 'qwe':
				return $this->qwe();
				break;
			default:
				return $this->defaultError();
				break;
		}
	}
	
	private function asd(){
		return array('min_key' => 'asd');
	}
	
	private function qwe(){
		return array('error' => true, 'errorMessage' => 'qwe-fail.');
	}
}

new ScrapeController();