<?php
require_once(ROOT_DIR . 'models' . DS . 'services' . DS . 'trafic_info_service.php');

class TraficInfoModel{
	private $service;
	
	public function __construct(){
		$this->service = new TraficInfoService();
	}
	
	public function getTraficInfo(){
		return $this->service->getTraficInfo();
	}
	
	public function getLastUpdated(){
		return $this->service->getLastUpdated();
	}
}