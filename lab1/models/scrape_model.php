<?php

class ScrapeModel{
	private $url = 'https://coursepress.lnu.se/kurser/';
	private $logDir;
	private $logPath = 'logs/';
	private $linkListFile = 'link_list.json';
	private $linkSort = array('courses' => '/.se\/kurs\//', 'programs' => '/.se\/program\//', 'projects' => '/.se\/projekt\//', 'other' => null);
	
	public function __construct(){
		$this->logDir = '..' . DS . 'logs' . DS;
	}
	
	public function getLinkListStats(){
		if(file_exists($this->logDir . $this->linkListFile)){
			$data = json_decode(file_get_contents($this->logDir . $this->linkListFile));
			return array(
				'lastUpdated' => $data->lastUpdated,
				'totalCount' => $data->totalCount,
				'courseCount' => $data->courseCount,
				'programCount' => $data->programCount,
				'projectCount' => $data->projectCount,
				'otherCount' => $data->otherCount,
				'filePath' => $this->logPath . $this->linkListFile
			);
		}
		return array('error' => true, 'errorMessage' => 'Det finns ingen fil med länkar.');
		
	}
	
	public function updateLinkList(){
		try{
			$links = $this->scrapeLinks($this->url);
			if($this->saveLinkList($links)){
				return $this->getLinkListStats();
			}
		}
		catch(Exception $e){
			return array('error' => true, 'errorMessage' => 'Det gick inte uppdatera URL-listan');
		}
	}
	
	private function scrapeLinks($url){
		$html = $this->getPageContent($url);
		
		$dd = new DomDocument();
		$dd->loadHTML($html);
		$xpath = new DOMXPath($dd);
		
		$links = $this->getCourseLinksFromPage($xpath);
		
		$nextPageURL = $this->getNextPageURL($xpath);
		
		if($nextPageURL !== null){
			$nextPageLinks = $this->scrapeLinks($nextPageURL);
			$links = array_merge($links, $nextPageLinks);
			
		}
		return $links;
	}
	
	private function getPageContent($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Andreas Fridlund, afrxx09');
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
	
	private function saveLinkList($links){
		$sortedLinks = $this->sortLinks($links);
		
		$linkList = array(
			'lastUpdated' => date("Y-m-d H:i:s"),
			'totalCount' => count($links),
			'courseCount' => count($sortedLinks['courses']),
			'programCount' => count($sortedLinks['programs']),
			'projectCount' => count($sortedLinks['projects']),
			'otherCount' => count($sortedLinks['other']),
			'links' => $sortedLinks
		);
		
		$file = fopen($this->logDir . $this->linkListFile, 'w');
		$content = json_encode($linkList, JSON_PRETTY_PRINT);
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