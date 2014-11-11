<?php

class Scrape{
	protected $logDir = '..' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
	
	public function post($url, $postVars){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
		
		return $this->execute($ch);
	}
	
	
	public function get($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		return $this->execute($ch);
	}
	
	public function execute($ch){
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Andreas Fridlund, afrxx09');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}