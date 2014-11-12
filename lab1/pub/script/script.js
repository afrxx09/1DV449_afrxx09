var scraper = {
	
	init : function(){
		self = this;
		this.bind();
		this.updateLinkStats();
	},
	
	bind : function(){
		$('#scraper-form').on('submit', function(e){
			self.startScrape(e)
		});
		$('#update-link-list').on('click', function(e){
			self.updateLinks(e);
		});
	},
	
	
	startScrape : function(e){
		e.preventDefault();
		alert('disabled');
		/*
		var url = $('#url').val();
		var courses = $('#courses').is('checked');
		$.ajax({
			type: 'POST',
			url: 'php/ajax.php',
			data: { 'url': url, 'courses': courses },
			dataType : 'json'
		}).done(function(response) {
				self.scrapeDone(response);
		});
		*/
	},
	
	/*
	scrapeDone : function(json){
		console.log(json);
	},
	*/
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
		$('#url-list-info .error').hide();
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
	}
};

$(document).ready(function(){
	scraper.init();
});