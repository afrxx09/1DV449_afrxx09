var scraper = {
	
	init : function(){
		self = this;
		this.bind();
		this.updateLinkStats();
		this.updateFileStats();
	},
	
	bind : function(){
		$('#scrape-form-submit').on('click', function(e){
			self.startScrape(e)
		});
		$('#update-link-list').on('click', function(e){
			self.updateLinks(e);
		});
		$('#scrape-my-pages-submit').on('click', function(){
			self.scrapeMyPages();
		});
	},
	
	
	startScrape : function(e){
		var pageTypesList = '';
		pageTypesList += ($('#courses').is(':checked')) ? ',courses': '';
		pageTypesList += ($('#subjects').is(':checked')) ? ',subjects': '';
		pageTypesList += ($('#programs').is(':checked')) ? ',programs': '';
		pageTypesList += ($('#projects').is(':checked')) ? ',projects': '';
		pageTypesList += ($('#other').is(':checked')) ? ',other': '';
		pageTypesList = pageTypesList.substring(1);
		var scrapeData = {
			pageTypes : pageTypesList,
			sort : $('#sort-courses').is(':checked'),
			scrapeLimit : $('#scrape-limit').val()
		}
		$('#file-list-loading').show();
		$('#file-list-container').hide();
		$.ajax({
			type: 'POST',
			url: 'controllers/scrape_controller.php?ajax=scrape',
			data: scrapeData,
			dataType : 'json'
		}).done(function(response) {
				self.scrapeDone(response);
		});
		
	},
	
	
	scrapeDone : function(json){
		self.updateFileStatsDone(json);
	},

	scrapeMyPages : function(){
		var myPages = {
			username : $('#my-pages-username').val(),
			password : $('#my-pages-password').val()
		};
		$.ajax({
			type: 'POST',
			url: 'controllers/scrape_controller.php?ajax=scrape_my_pages',
			data: myPages,
			dataType : 'json'
		}).done(function(response) {
				self.scrapeMyPagesDone(response);
		});
	},

	scrapeMyPagesDone : function(json){
		console.log(json);
	},
	
	updateLinks : function(e){
		var action = $(e.target).data('ajax-action');
		$('#link-list-loading').fadeIn(400);
		$(e.target).fadeOut(400, function(){
			$.ajax({
				type: 'POST',
				url: 'controllers/scrape_controller.php?ajax=' + action,
				dataType : 'json'
			}).done(function(response) {
					self.updateLinksDone(response);
			});
		});
	},
	
	updateLinksDone : function(json){
		$('#link-list-loading').fadeOut(400, function(){
			$('#update-link-list').fadeIn(400);
		});
		if(json.error){
			$('#url-list-info .error').html(json.errorMessage);
			$('#url-list-info .error').show();
		}
		else{
			self.updateLinkStats();
		}
	},
	
	updateLinkStats : function(){
		$('#url-list-loading').show();
		$('#url-list-container').hide();
		$.ajax({
			type: 'POST',
			url: 'controllers/scrape_controller.php?ajax=get_link_list_stats',
			dataType : 'json'
		}).done(function(response) {
				self.updateLinkStatsDone(response);
		});
	},
	
	updateLinkStatsDone : function(json){
		if(json.error){
			$('#url-list-info .error').html(json.errorMessage);
			$('#url-list-info .error').show();
		}
		else{
			$('#url-list-container').html(json.html);
			$('#url-list-loading').fadeOut(200, function(){
				$('#url-list-container').fadeIn(200);
			});
		}
	},

	updateFileStats : function(){
		$('#file-list-loading').show();
		$('#file-list-container').hide();
		$.ajax({
			type: 'POST',
			url: 'controllers/scrape_controller.php?ajax=get_file_list_stats',
			dataType : 'json'
		}).done(function(response) {
				self.updateFileStatsDone(response);
		});
	},
	
	updateFileStatsDone : function(json){
		if(json.error){
			$('#file-list-info .error').html(json.errorMessage);
			$('#file-list-info .error').show();
		}
		else{
			$('#file-list-container').html(json.html);
			$('#file-list-loading').fadeOut(200, function(){
				$('#file-list-container').fadeIn(200);
			});
		}
	}
};

$(document).ready(function(){
	scraper.init();
});