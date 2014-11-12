<?php

class ScrapePageModel{
	private $type;
	private $fileName;
	private $sort;
	private $limit;
	private $URLs;
	private $xpath;

	public function __construct($pageType, $sort = false, $limit = 0){
		$this->type = $pageType;
		$this->fileName = $pageType . '.json';
		$this->sort = $sort;
		$this->limit = $limit;
		$this->URLs = $this->getURLs();
	}

	private function getURLs(){
		$linkListLog = '..\logs\link_list.json';
		if(file_exists($linkListLog)){
			$data = json_decode(file_get_contents($linkListLog));
			$urls = $data->links->{$this->type};
			return $urls;
		}
		return null;
	}

	public function start(){
		$start = microtime(true);
		$count = 0;
		$limit = ($this->limit == 0 || $this->limit > count($this->URLs)) ? count($this->URLs) : $this->limit;
		$data = array();
		for($i = 0; $i < $limit; $i++){
			$url = $this->URLs[$i];
			$data[] = $this->scrapePage($url);
			$count++;
		}

		$end = microtime(true);
		$time = round($end - $start, 6);

		if($this->sort){
			$data = $this->sortData($data);
		}

		$scrapeResult = array(
			'lastUpdated' => date("Y-m-d H:i:s"),
			'scrapingTime' => $time,
			'requestCount' => $count,
			'data' => $data
		);
		$this->saveData($scrapeResult);
	}

	private function scrapePage($url){
		$html = $this->getPageContent($url);
		
		$dd = new DomDocument();
		libxml_use_internal_errors(true);
		$dd->loadHTML('<meta charset="UTF-8" />'.$html);
		libxml_clear_errors();
		
		$this->xpath = new DOMXPath($dd);

		$courseInfo = array();
		$courseInfo['header'] = $this->getCourseHeader();
		$courseInfo['url'] = $this->getCourseURL();
		$courseInfo['courseCode'] = $this->getCourseCode();
		$courseInfo['syllabus'] = $this->getCourseSyllabus();
		$courseInfo['infoText'] = $this->getCourseInfoText();
		$courseInfo['lastPost'] = $this->getCourseLastPost();
		return $courseInfo;
	}

	private function sortData($data){
		usort($data, function($a, $b){
    		return strcmp($a["header"], $b["header"]);
		});
		return $data;
	}

	private function saveData($result){
		$file = fopen('..\logs\\' . $this->fileName, 'w');
		try{
			$content = json_encode($result, JSON_PRETTY_PRINT);
		}
		catch(Exception $e){
			$content = json_encode($result);
		}
		fwrite($file, $content);
		fclose($file);
		return true;
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

	private function getCourseCode(){
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