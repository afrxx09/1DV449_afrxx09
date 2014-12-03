<?php

class TraficInfoView{
	public function index($traficInfo, $lastUpdated){
		$r = '';
		foreach($traficInfo as $ti){
			$r .= '
				<div>
					<p>id: ' . $ti->id . '</p>
					<p>prioritet: ' . $ti->priority . '</p>
				</div>
			';
		}
		return '
			<div>
				<h3>
					Senast uppdaterad<br />
					' . $lastUpdated . '
				</h3>
				<div>
					' . $r . '
				</div>
			</div>
		';
	}
}