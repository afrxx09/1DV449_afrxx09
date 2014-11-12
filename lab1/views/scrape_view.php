<?php
class ScrapeView{
	
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
}