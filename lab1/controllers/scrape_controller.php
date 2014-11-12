<?php

require_once('ajax_controller.php');
require_once('..' . DS . 'models' . DS . 'scrape_model.php');

class ScrapeController extends Ajax{
	private $scrapeModel;
	
	public function __construct(){
		$this->scrapeModel = new ScrapeModel();
		parent::__construct();
	}
	
	public function doAction(){
		switch($this->action){
			case 'get_link_list_stats':
				return $this->getLinkListStats();
				break;
			case 'update_link_list':
				return $this->updateLinkList();
				break;
			default:
				return $this->defaultError();
				break;
		}
	}
	
	private function getLinkListStats(){
		return $this->scrapeModel->getLinkListStats();
	}
	
	private function updateLinkList(){
		return $this->scrapeModel->updateLinkList();
	}
}

new ScrapeController();