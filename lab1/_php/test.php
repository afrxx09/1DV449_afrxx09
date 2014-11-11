<?php
require_once('scrape.php');
require_once('course_scrape.php');

$logFileDir = '..' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;

$url = isset($_POST['url']) ? $_POST['url'] : null;

$courses = isset($_POST['courses']) ? $_POST['courses'] : null;
//$programs = isset($_POST['programs']) ? $_POST['programs'] : null;
//$projects = isset($_POST['projects']) ? $_POST['projects'] : null;
//$subjects = isset($_POST['subjects']) ? $_POST['subjects'] : null;

/**
*	Get all urls from all pages
*/
$allNodes = getAllNodes($url);

/**
*	Filter and split urls into categories
*/
$courses = filterNodes($allNodes, '/.se\/kurs\//');
$programs = filterNodes($allNodes, '/.se\/program\//');
$projects = filterNodes($allNodes, '/.se\/projekt\//');
$subjects = filterNodes($allNodes); /** Remaining urls are put in  "subjects"-category */

$coursesInformation = array();
foreach($courses as $url){
	$url = rtrim($url, '/') . '/';

	$scrape = new Scrape();
	$html = $scrape->get($url);
	$dd = new DomDocument();
	libxml_use_internal_errors(true);
	$dd->loadHTML('<meta charset="UTF-8" />'.$html);
	libxml_clear_errors();
	
	$xpath = new DOMXPath($dd);
	
	$courseInfo = array();
	$courseInfo['header'] = getHeader($xpath);
	$courseInfo['url'] = getURL($xpath);
	$courseInfo['courseCode'] = getCourseCode($xpath);
	$courseInfo['syllabus'] = getSyllabus($xpath);
	$courseInfo['infoText'] = getInfoText($xpath);
	$courseInfo['lastPost'] = getLastPost($xpath);
	
	$coursesInformation[] = $courseInfo;
	
}

/*
$file = fopen($logFileDir . $logFileNames['courses'], 'w');
$content = json_encode($coursesInformation, JSON_PRETTY_PRINT);
fwrite($file, $content);
fclose($file);
exit;
*/


function getAllNodes($url){
	$scrape = new Scrape();
	$pageCount = getPageCount($url);
	$allNodes = array();
	for($i = 0; $i < 5; $i++){
		$pageUrl = $url . '?bpage=' . ($i+1);
		$pageHTML = $scrape->get($pageUrl);
		
		$pageNodes = getCourseListFromPage($pageHTML);
		$allNodes = array_merge($allNodes, $pageNodes);
	}
	return $allNodes;
}

function getPageCount($url){
	$scrape = new Scrape();
	$html = $scrape->get($url);
	$dd = new DomDocument();
	$dd->loadHTML($html);
	
	$xpath = new DOMXPath($dd);
	$q = '//li[@id="blogs-all"]//a//span';
	$nodeList = $xpath->query($q);
	$count = intval($nodeList->item(0)->nodeValue);
	return intval(floor(($count / 20) + 1));
}

function getCourseListFromPage($html){
	$dd = new DomDocument();
	$dd->loadHTML($html);
	
	$xpath = new DOMXPath($dd);
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

function filterNodes(&$allNodes, $filter = null){
	$urls = array();
	foreach($allNodes as $key => $url){
		if($filter === null || preg_match($filter, $url)){
			unset($allNodes[$key]);
			$urls[] = $url;
		}
	}
	return $urls;
}