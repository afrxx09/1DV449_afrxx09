<?php

class CourseScrape extends Scrape{
	
	private $logFile = $this->logDir . 'courses.json';
	private $linkIdentifier = '/.se\/kurs\//';
	
	private $url;
	private $html;
	private $dd;
	private $xpath;
	
	private $courseInformation = array();
	
	public function __construct($url){
		$this->url = $url;
		$this->html = $this->get($this->url);
		
		$this->dd = new DomDocument();
		libxml_use_internal_errors(true);
		$this->dd->loadHTML('<meta charset="UTF-8" />'.$this->html);
		libxml_clear_errors();
		
		$this->xpath = new DOMXPath($this->dd);
	}
	
	private function getCourseHeader(){
		$q = '//div[@id="header-wrapper"]//h1//a';
		$nodeList = $this->xpath->query($q);
		return ($nodeList->length > 0) ?  trim($nodeList->item(0)->nodeValue) : 'no information';
	}

	private function getCourseURL(){
		$q = '//div[@id="header-wrapper"]//h1//a';
		$nodeList = $this->xpath->query($q);
		return ($nodeList->length > 0) ? trim($nodeList->item(0)->getAttribute('href')) : 'no information';
	}

	private function getCourseCourseCode(){
		$q = '//div[@id="header-wrapper"]//ul/li[last()]/a';
		$nodeList = $this->xpath->query($q);
		return ($nodeList->length > 0) ? trim($nodeList->item(0)->nodeValue) : 'no information';
	}

	private function getCourseSyllabus(){
		$q = '//*[@id="navigation"]//ul[@class="menu"]//a';
		$nodeList = $this->xpath->query($q);
		foreach($nodeList as $node){
			$href = $node->getAttribute('href');
			if(preg_match('/templatetype\=coursesyllabus/', $href)){
				return trim($href);
			}
		}
		return 'no information';
	}

	private function getCourseInfoText(){
		$q = '//*[@id="content"]//*[@class="entry-content"]';
		$nodeList = $this->xpath->query($q);
		return ($nodeList->length > 0) ? trim($nodeList->item(0)->textContent) : 'no information';
	}

	private function getCourseLastPost(){
		$q = '//*[@id="content"]//*[contains(@class, "type-post")]//*[@class="entry-title"]';
		$nodeList = $this->xpath->query($q);
		$head = ($nodeList->length > 0) ? $nodeList->item(0)->textContent : 'no information';
		
		$q = '//*[@id="content"]//*[contains(@class, "type-post")]//*[@class="entry-byline"]';
		$nodeList = $this->xpath->query($q);
		if($nodeList->length > 0){
			$firstPostBy = $nodeList->item(0);
			$date = trim($firstPostBy->firstChild->textContent);
			preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}/', $date, $matches);
			$date = $matches[0];
			$name = $firstPostBy->firstChild->nextSibling->textContent;
			return array('title' => $head, 'date' => $date, 'by' => $name);
		}
		
		return array('title' => $head, 'date' => 'no information', 'by' => 'no information');
	}
}