<?php
require_once(ROOT_DIR . 'models' . DS . 'entities' . DS . 'trafic_info.php');

class TraficInfoService{
	private $format = 'json';
	private $size = 5;
	private $url = 'http://api.sr.se/api/v2/traffic/messages';
	private $file = 'trafic_info.json';
	private $rawData;
	private $jsonData;
	
	public function getTraficInfo(){
		$this->getData();
		$this->parseData();
		return $this->buildData();
	}
	
	public function getData(){
		$file = ROOT_DIR . 'data' . DS . $this->file;
		if(file_exists($file) && ((time() - filemtime($file) < 600) )){
			$this->rawData = file_get_contents($file);
		}
		else{
			var_dump('skapar ny');
			$url = $this->url . '?format=' . $this->format . '&size=' . $this->size;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$this->rawData = curl_exec($ch);
			curl_close($ch);
			
			$fp = fopen($file, 'w');
			fwrite($fp, $this->rawData);
			fclose($fp);
		}
	}
	
	public function parseData(){
		$this->jsonData = json_decode($this->rawData);
	}
	
	public function buildData(){
		$r = array();
		foreach($this->jsonData->messages as $m){
			$r[] = new TraficInfo($m);
		}
		
		usort($r, function($a, $b){
		    return strcmp($b->id, $a->id);
		});
		return $r;
	}
	
	public function getLastUpdated(){
		$time = filemtime(ROOT_DIR . 'data' . DS . $this->file);
		return date('Y-m-d H:i:s', $time);
	}
}