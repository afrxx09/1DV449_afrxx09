<?php

class TraficInfoView{
	public function index($traficInfo){
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
				' . $r . '
			</div>
		';
	}
}