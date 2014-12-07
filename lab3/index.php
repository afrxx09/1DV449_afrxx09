<?php
$api_key = file_get_contents('data' . DIRECTORY_SEPARATOR . 'google-maps-api-key.txt');
?>
<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>Trafikinformation</title>
		<link href="//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic,500,500italic,300,300italic" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="pub/style.css" />
		
	</head>

	<body>

		<div id="wrap">
			
			<div id="header">
				<h1>Trafikinformation</h1>
			</div>
			
			<div id="category-filter">
				<ul>
					<li data-category-filter="-1" class="active">Alla</li>
					<li data-category-filter="0">Vägtrafik</li>
					<li data-category-filter="1">Kollektivtrafik</li>
					<li data-category-filter="2">Planerad störning</li>
					<li data-category-filter="3">Övrigt</li>
				</ul>
			</div>
			
			<div id="content">
				<div id="content-left">
				
					<ul id="location-list">
						
					</ul>
				</div>
				<div id="content-right">
					<div id="map-container">
					
					</div>
				</div>
				<div class="clear"></div>
			</div>

			<div id="footer">
				<p>Laboration 3, Webbteknik II (1DV449)</p>
				<p>Andreas Fridlund - afrxx09</p>
			</div>
			
		</div>
		
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= $api_key ?>"></script>
		<script type="text/javascript" src="pub/js/gmap.js"></script>
		<script type="text/javascript" src="pub/js/trafic_info.js"></script>
	</body>

</html>