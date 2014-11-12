<?php

require_once('ajax_controller.php');
require_once('..' . DS . 'models' . DS . 'scrape_model.php');
require_once('..' . DS . 'models' . DS . 'scrape_page_model.php');
require_once('..' . DS . 'views' . DS . 'scrape_view.php');

class ScrapeController extends Ajax{
	private $scrapeModel;
	private $scrapeView;
	
	public function __construct(){
		$this->scrapeModel = new ScrapeModel();
		$this->scrapeView = new ScrapeView();
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
			case 'scrape':
				return $this->scrape();
				break;
			case 'get_file_list_stats':
				return $this->getFileListStats();
				break;
			case 'scrape_my_pages':
				return $this->scrapeMyPages();
				break;
			default:
				return $this->defaultError();
				break;
		}
	}
	
	private function getLinkListStats(){
		$info = $this->scrapeModel->getLinkListInfo();
		if($info === false){
			return array('error' => true, 'errorMessage' => 'Det finns ingen fil med länkar.');
		}
		return array('html' => $this->scrapeView->RenderLinkListInfo($info));
	}
	
	private function updateLinkList(){
		return $this->scrapeModel->updateLinkList();
	}

	private function scrape(){
		$pageTypes = $this->scrapeView->getPageTypes();
		$sort = $this->scrapeView->getPost('sort');
		$limit = $this->scrapeView->getPost('scrapeLimit');
		foreach($pageTypes as $pageType){
			$pageScraper = new ScrapePageModel($pageType, $sort, $limit);
			$pageScraper->start();
		}
		
		return $this->getFileListStats();
		
	}

	private function getFileListStats(){
		$scrapeFilesInfo = $this->scrapeModel->getScrapeFilesInfo();
		return array('html' => $this->scrapeView->renderScrapeFileList($scrapeFilesInfo));
	}

	private function scrapeMyPages(){
		$username = $this->scrapeView->getPost('username');
		$password = $this->scrapeView->getPost('password');
		$mpsm = new MyPagesScraperModel($username, $password);
	}
}

new ScrapeController();