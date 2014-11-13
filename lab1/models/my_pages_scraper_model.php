<?php
class MyPagesScraperModel extends ScrapePageModel{
	private $username;
	private $password;
	
	public function __construct($u, $p){
		$this->username = $u;
		$this->password = $p;
		$this->login();
		$this->scrapeMyPages();
		
	}
	
	private function login(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://coursepress.lnu.se/wp-login.php');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('log' => $this->username, 'pwd' => $this->password));
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Scraper by: Andreas Fridlund, afrxx09@student.lnu.se');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_exec($ch);
		curl_close($ch);
	}
	
	private function getMyPagesContent(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://coursepress.lnu.se/medlemmar/' . $this->username . '/kurser');
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Scraper by: Andreas Fridlund, afrxx09@student.lnu.se');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$result = curl_exec($ch);
		
		curl_close($ch);
		return $result;
	}
	
	private function scrapeMyPages(){
		$myPagesContent = $this->getMyPagesContent();
		$dd = new DomDocument();
		$dd->loadHTML($myPagesContent);
		$xpath = new DOMXPath($dd);
		
		$urls = $this->getMyURLs($xpath);
		
		var_dump($urls);
		exit;
	}
	
	private function getMyURLs($xpath){
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
	
}