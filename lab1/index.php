<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title></title>
		
		<link rel="stylesheet" href="pub/css/style.css" type="text/css" />
		
		<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>
	</head>

	<body>
		
		<div id="wrap">
		
			<div id="head">
				<div class="content">
					<h1>Webskrapa</h1>
				</div>
			</div>
			
			<div id="main">
				<div class="content">
					
					<div id="form-container">
						<form id="scraper-form" action="#" method="post">
							<input type="hidden" name="ajax" value="scrape" />
							<div class="row">
								<div class="col mini">
									<input type="checkbox" name="courses" id="courses" checked="checked" /><label for="courses">Kurser</label> 
								</div>
							
								<div class="col mini">
									<input type="checkbox" name="programs" id="programs" disabled="disabled" /><label for="programs">Program</label> 
								</div>
								<div class="col mini">
									<input type="checkbox" name="subjects" id="subjects" disabled="disabled" /><label for="subjects">Ämnen</label> 
								</div>
								<div class="col mini">
									<input type="checkbox" name="projects" id="projects" disabled="disabled" /><label for="projects">Projekt</label> 
								</div>
							</div>
							<div class="row">
								<div class="col micro"></div>
								<div class="col">
									<input type="submit" value="Skrapa!" />
								</div>
							</div>
							<div class="clear"></div>
						</form>
					</div>
					
					
					<div id="stats">
						<div id="url-list-info">
							<h2>URL - lista</h2>
							<p>URL-lista till alla kurser, program, projekt m.m. på Coursepress. </p>
							<p class="error"></p>
							<div id="url-list-loading">
								<p>Laddar ....</p>
							</div>
							<div id="url-list-container">
								
							</div>
						</div>
						<div>
							<span id="update-link-list" class="button" data-ajax-action="update_link_list">Uppdatera länkar</span>
							<span id="link-list-loading">Laddar...</span>
						</div>
					</div>
				
				</div>
				
				
				
			</div>
			
			<div id="footerpush"></div>
			
		</div>
		
		
		<div id="footer">
			<p>1dv449 lnu.se</p>
			<p>Andreas Fridlund - afrxx09</p>
		</div>
		
		
		<script src="pub/script/jquery-1.11.1.min.js" type="text/javascript"></script>
		<script src="pub/script/script.js" type="text/javascript"></script>
	</body>

</html>