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
		$this->title = filter_var($json->title, FILTER_SANITIZE_SPECIAL_CHARS);
		$this->exactlocation = filter_var($json->exactlocation, FILTER_SANITIZE_SPECIAL_CHARS);
		$this->description = filter_var($json->description, FILTER_SANITIZE_SPECIAL_CHARS);
		$this->latitude = floatval($json->latitude);
		$this->longitude = floatval($json->longitude);
		$this->category = intval($json->category);
		$this->subcategory = filter_var($json->subcategory, FILTER_SANITIZE_SPECIAL_CHARS);
		$this->createddate = filter_var($json->createddate, FILTER_SANITIZE_SPECIAL_CHARS);
		
		//rensa bort /date()/ och tidszon från json-svaret gör om milisekunder till sekunder
		$createddate = explode('+', preg_replace('/[^0-9,.+]/', '', $json->createddate));
		$this->createddate = date('Y-m-d H:i:s', intval($createddate[0]/1000));
	}
}