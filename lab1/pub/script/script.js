var scraper = {
	
	init : function(){
		self = this;
		this.bind();
	},
	
	bind : function(){
		$('#scraper-form').on('submit', function(e){
			self.startScrape(e)
		});
		$('#url-list-stats .update-links').on('click', function(e){
			self.updateLinks(e);
		});
	},
	
	startScrape : function(e){
		e.preventDefault();
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
	},
	
	scrapeDone : function(json){
		console.log(json);
	},
	
	updateLinks : function(e){
		e.preventDefault();
		var action = $(e.target).data('ajax-action');
		$.ajax({
			type: 'POST',
			url: 'controllers/scrape_controller.php?ajax=' + action,
			dataType : 'json'
		}).done(function(response) {
				self.scrapeDone(response);
		});
	},
	
	updateLinksDone : function(json){
		console.log(json)
		/*if(json.error){
			
		}
		*/
	}
};

$(document).ready(function(){
	scraper.init();
});