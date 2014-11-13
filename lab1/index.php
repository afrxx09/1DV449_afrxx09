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
					<div>
						<h2>Skrapa innehåll</h2>
						<p>
							Skrapa information om kurser, program, projekt mm från
							<a href="https://coursepress.lnu.se/kurser/" target="_blank">https://coursepress.lnu.se/kurser/</a>.
						</p>
					</div>
					<div id="form-container">
						<form id="scraper-form" action="">
							<input type="hidden" name="ajax" value="scrape" />

							<div class="row">
								<p>Välj typer av sidor att skrapa</p>
								<div class="col mini">
									<input type="checkbox" name="courses" id="courses" checked="checked" /><label for="courses">Kurser</label> 
								</div>
								<div class="col mini">
									<input type="checkbox" name="programs" id="programs" /><label for="programs">Program</label> 
								</div>
								<div class="col mini">
									<input type="checkbox" name="subjects" id="subjects" /><label for="subjects">Ämnen</label> 
								</div>
								<div class="col mini">
									<input type="checkbox" name="projects" id="projects" /><label for="projects">Projekt</label> 
								</div>
								<div class="col mini">
									<input type="checkbox" name="other" id="other" /><label for="other">Övriga</label> 
								</div>
							</div>

							<div class="row">
								<p>Övriga inställningar</p>
								<div class="col medium">
									<label for="scrape-limit">Begränsa antalet sidor som skrapas</label>
									<select name="scrape-limit" id="scrape-limit">
										<option value="0">Alla</option>
										<option value="5">5</option>
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
									</select>
								</div>
								<div class="col medium">
									<input type="checkbox" name="sort-course" id="sort-courses" /><label>Sortera kurser alfabetiskt</label>
								</div>
							</div>

							<div class="row">
								<div class="col mini">
									<span id="scrape-form-submit" class="button">Skrapa</span>
								</div>
							</div>

							<div class="clear"></div>

							<div id="my-page-form">
								<div class="row">
									<h3>Skrapa mina sidor</h3>
									<p>
										Om "Mina sidor" sidor ska skrapas måste ett användarnamn och lösenord anges.<br />
									</p>
								</div>
								<div class="row">
									<div class="col mini">
										<label for="my-pages-username">Användarnamn</label>
									</div>
									<div class="col mini">
										<input type="text" name="my-pages-username" id="my-pages-username" />
									</div>
								</div>
								<div class="row">
									<div class="col mini">
										<label for="my-pages-password">Lösenord</label>
									</div>
									<div class="col mini">
										<input type="password" name="my-pages-password" id="my-pages-password" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<span id="scrape-my-pages-submit" class="button">Skrapa mina sidor</span>
								</div>
							</div>
							<div class="clear"></div>
						</form>
					</div>
					
					
					<div id="info">
						<div id="url-list-info">
							<h2>URL - lista</h2>
							<p>URL-lista till alla kurser, program, projekt m.m. på Coursepress. </p>
							<p class="error"></p>
							<div id="url-list-loading">
								<p>Laddar URL-lista....</p>
							</div>
							<div id="url-list-container">
								
							</div>
						</div>
						<div>
							<span id="update-link-list" class="button" data-ajax-action="update_link_list">Uppdatera länkar</span>
							<span id="link-list-loading">Laddar...</span>
						</div>
					</div>
					<div id="files">
						<div id="file-list-info">
							<h2>Skrapade Filer</h2>
							<p>Här finns information om de filer som skrapats.</p>
							<p class="error"></p>
							<div id="file-list-loading">
								<p>Laddar filer....</p>
							</div>
							<div id="file-list-container">
								
							</div>
						</div>
					</div>
					<div class="clear"></div>
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