<?php

class LayoutView{

	public function render($html){
		echo '
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">

				<head>
					<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
					<title>Test application build with framework</title>
				</head>

				<body>

					<div id="wrap">
						
						<div id="header">
							<h1>Trafikinfo</h1>
						</div>
						
						<div id="content">
							' . $html . '
						</div>

						<div id="footer">
							<p>footer</p>
						</div>
						
					</div>

				</body>

			</html>
		';
	}

}