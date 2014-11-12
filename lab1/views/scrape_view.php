<?php
class ScrapeView{
	
	public function getPost($key){
		return isset($_POST[$key]) ? $this->setVarType($_POST[$key]) : null;
	}

	public function setVarType($var){
		if($var === 'false') { return false;}
		if($var === 'true') { return true;}
		//if(intval($var) == $var) { return intval($var); }
		return $var;
	}
	public function getPageTypes(){
		$pageTypes = $this->getPost('pageTypes');
		return explode(',', $pageTypes);
	}

	public function RenderLinkListInfo($info){
		return '
			<div class="row">
				<div class="label">Senast updaterad:</div>
				<div class="data last-updated">' . $info['lastUpdated'] . '</div>
			</div>
			<div class="row">
				<div class="label">Tidsåtgång:</div>
				<div class="data scraping-time">' . $info['scrapingTime'] . ' sekunder</div>
			</div>
			<div class="row">
				<div class="label">Antal requests:</div>
				<div class="data scraping-time">' . $info['requestCount'] . '</div>
			</div>
			<div class="row">
				<div class="label">Totalt antal länkar:</div>
				<div class="data total-links">' . $info['totalCount'] . '</div>
			</div>
			<div class="row">
				<div class="label">Kurs-länkar:</div>
				<div class="data course-links">' . $info['courseCount'] . '</div>
			</div>
			<div class="row">
				<div class="label">Program-länkar:</div>
				<div class="data program-links">' . $info['programCount'] . '</div>
			</div>
			<div class="row">
				<div class="label">Projekt-länkar:</div>
				<div class="data project-links">' . $info['projectCount'] . '</div>
			</div>
			<div class="row">
				<div class="label">Ämnes-länkar:</div>
				<div class="data subject-links">' . $info['subjectCount'] . '</div>
			</div>
			<div class="row">
				<div class="label">Övriga länkar:</div>
				<div class="data other-links">' . $info['otherCount'] . '</div>
			</div>
			<div class="row">
				<div class="label">Länk till fil:</div>
				<div class="data file-path"><a href="' . $info['filePath'] . '" target="_blank">Link list json-file</a></div>
			</div>
			<div class="clear"></div>
		';
	}

	public function renderScrapeFileList($scrapeFilesInfo){
		$html = '';
		foreach($scrapeFilesInfo as $key => $fileInfo){
			$html .= '
				<div class="group">
					<div class="row">
						<h3>' . $key . '</h3>
					</div>
					<div class="row">
						<div class="label">Senast updaterad:</div>
						<div class="data last-updated">' . $fileInfo['lastUpdated'] . '</div>
					</div>
					<div class="row">
						<div class="label">Tidsåtgång:</div>
						<div class="data scraping-time">' . $fileInfo['scrapingTime'] . ' sekunder</div>
					</div>
					<div class="row">
						<div class="label">Antal requests:</div>
						<div class="data scraping-time">' . $fileInfo['requestCount'] . '</div>
					</div>
					<div class="row">
						<div class="label">Länk till fil:</div>
						<div class="data file-path"><a href="' . $fileInfo['filePath'] . '" target="_blank">Link list json-file</a></div>
					</div>
				</div>
			';
		}
		return $html;
	}
}