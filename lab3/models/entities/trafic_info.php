<?php

class TraficInfo{
	public $id;
	public $priority;
	public $createddate;
	public $title;
	public $exactlocation;
	public $description;
	public $latitude;
	public $longitude;
	public $category;
	public $subcategory;
	
	public function __construct($json){
		$this->id = intval($json->id);
		$this->priority = intval($json->priority);
		$this->title = $json->title;
		$this->exactlocation = $json->exactlocation;
		$this->description = $json->description;
		$this->latitude = floatval($json->latitude);
		$this->longitude = floatval($json->longitude);
		$this->category = intval($json->category);
		$this->subcategory = $json->subcategory;
		$this->createddate = $json->createddate;
		
		//rensa bort /date()/ och tidszon från json-svaret gör om milisekunder till sekunder
		$createddate = explode('+', preg_replace('/[^0-9,.+]/', '', $json->createddate));
		$this->createddate = date('Y-m-d H:i:s', intval($createddate[0]/1000));
	}
}