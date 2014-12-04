<?php
$api_key = file_get_contents('data' . DIRECTORY_SEPARATOR . 'google-maps-api-key.txt');
?>
<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>Test application build with framework</title>
		<link href="//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic,500,500italic,300,300italic" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="pub/style.css" />
		
	</head>

	<body>

		<div id="wrap">
			
			<div id="header">
				<h1>Trafikinfo</h1>
			</div>
			
			<div id="content">
				<div id="content-left">
					left
				</div>
				<div id="content-right">
					<div id="map-container">

					</div>
				</div>
				<div class="clear"></div>
			</div>

			<div id="footer">
				<p>footer</p>
			</div>
			
		</div>
		
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= $api_key ?>"></script>
		<script type="text/javascript" src="pub/js/gmap.js"></script>
		<script type="text/javascript" src="pub/js/trafic_info.js"></script>
	</body>

</html>