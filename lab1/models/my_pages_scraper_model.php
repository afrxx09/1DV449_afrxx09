<?php
require_once('scrape_page_mode.php');

class MyPagesScraperModel extends ScrapePageModel{
	private $username;
	private $password;
	
	public function __construct($u, $p){
		$this->username = $u;
		$this->password = $p;
	}
}