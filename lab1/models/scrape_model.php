<?php

class ScrapeModel{
	private $url = 'https://coursepress.lnu.se/kurser/';
	private $logDir;
	private $logPath = 'logs/';
	private $linkListFile = 'link_list.json';
	private $linkSort;
	
	public function __construct(){
		$this->logDir = '..' . DS . 'logs' . DS;
		$this->linkSort = array(
			'courses' => '/.se\/kurs\//',
			'programs' => '/.se\/program\//',
			'projects' => '/.se\/projekt\//',
			'subjects' => '/.se\/subject\//',
			'other' => null
		);
	}
	
	public function getLinkListInfo(){
		if(file_exists($this->logDir . $this->linkListFile)){
			$data = json_decode(file_get_contents($this->logDir . $this->linkListFile));
			return array(
				'lastUpdated' => $data->lastUpdated,
				'scrapingTime' => $data->scrapingTime,
				'requestCount' => $data->requestCount,
				'totalCount' => $data->totalCount,
				'courseCount' => $data->courseCount,
				'programCount' => $data->programCount,
				'projectCount' => $data->projectCount,
				'subjectCount' => $data->subjectCount,
				'otherCount' => $data->otherCount,
				'filePath' => $this->logPath . $this->linkListFile
			);
		}
		return false;
		
	}
	
	public function getScrapeFilesInfo(){
		$scrapeFilesInfo = array();
		foreach($this->linkSort as $type => $regex){
			$logFile = $this->logDir . $type . '.json';
			if(file_exists($logFile)){
				$data = json_decode(file_get_contents($logFile));
				$scrapeFilesInfo[$type] = array(
					'lastUpdated' => $data->lastUpdated,
					'scrapingTime' => $data->scrapingTime,
					'requestCount' => $data->requestCount,
					'filePath' => 'logs/' . $type . '.json'
				);
			}
			else{
				$scrapeFilesInfo[$type] = array(
					'lastUpdated' => 'unknown',
					'scrapingTime' => 'unknown',
					'requestCount' => 'unknown',
					'filePath' => 'no file'
				);
			}
		}
		return $scrapeFilesInfo;
	}

	public function updateLinkList(){
		try{
			$start = microtime(true);
			$count = 0;
			$links = $this->scrapeLinks($this->url, $count);
			$end = microtime(true);
			$time = round($end - $start, 6);
			if($this->saveLinkList($links, $time, $count)){
				return $this->getLinkListInfo();
			}
		}
		catch(Exception $e){
			return array('error' => true, 'errorMessage' => 'Det gick inte uppdatera URL-listan');
		}
	}
	
	private function scrapeLinks($url ,&$count){
		$count++;
		$html = $this->getPageContent($url);
		
		$dd = new DomDocument();
		$dd->loadHTML($html);
		$xpath = new DOMXPath($dd);
		
		$links = $this->getCourseLinksFromPage($xpath);
		
		$nextPageURL = $this->getNextPageURL($xpath);
		
		if($nextPageURL !== null){
			$nextPageLinks = $this->scrapeLinks($nextPageURL, $count);
			$links = array_merge($links, $nextPageLinks);
			
		}
		return $links;
	}
	
	private function getPageContent($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Scraper by: Andreas Fridlund, afrxx09@student.lnu.se');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	private function getCourseLinksFromPage($xpath){
		$q = '//ul[@id="blogs-list"]//div[@class="item-title"]//a';
		$nodeList = $xpath->query($q);

		$nodes = array();
		foreach($nodeList as $node){
			$nodeUrl = '';
			foreach($node->attributes as $attribute){
				if(strtolower($attribute->name) === 'href'){
					$nodeUrl = $attribute->value;
				}
			}
			$nodes[] = $nodeUrl;
		}
		return $nodes;
	}
	
	private function getNextPageURL($xpath){
		$q = '//div[@id="blog-dir-pag-top"]//a[contains(@class, "next")]';
		$nextLink = $xpath->query($q);
		if($nextLink->length === 0){
			return null;
		}
		foreach($nextLink->item(0)->attributes as $attribute){
			if(strtolower($attribute->name) === 'href'){
				return $this->url . str_replace('/kurser/', '', $attribute->value);
			}
		}
		return null;
	}
	
	private function saveLinkList($links, $time, $count){
		$sortedLinks = $this->sortLinks($links);
		
		$linkList = array(
			'lastUpdated' => date("Y-m-d H:i:s"),
			'scrapingTime' => $time,
			'requestCount' => $count,
			'totalCount' => count($links),
			'courseCount' => count($sortedLinks['courses']),
			'programCount' => count($sortedLinks['programs']),
			'projectCount' => count($sortedLinks['projects']),
			'subjectCount' => count($sortedLinks['subjects']),
			'otherCount' => count($sortedLinks['other']),
			'links' => $sortedLinks
		);
		
		$file = fopen($this->logDir . $this->linkListFile, 'w');
		try{
			$content = json_encode($linkList, JSON_PRETTY_PRINT);
		}
		catch(Exception $e){
			$content = json_encode($linkList);
		}
		fwrite($file, $content);
		fclose($file);
		return true;
	}
	
	private function sortLinks($links){
		$sortedLinks = array();
		foreach($this->linkSort as $name => $regex){
			foreach($links as $key => $link){
				if($regex === null || preg_match($regex, $link)){
					unset($links[$key]);
					$sortedLinks[$name][] = $link;
				}
			}
		}
		return $sortedLinks;
	}
}