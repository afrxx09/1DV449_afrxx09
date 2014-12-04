<?php
require_once(ROOT_DIR . 'models' . DS . 'trafic_info_model.php');
require_once(ROOT_DIR . 'views' . DS . 'trafic_info_view.php');

class TraficInfoController{
	private $model;
	private $view;
	
	public function __construct(){
		$this->model = new TraficInfoModel();
		$this->view = new TraficInfoView();
	}
	
	public function getAll(){
		return $this->model->getTraficInfo();
	}

	public function create(){
		return 'Create';
	}
}