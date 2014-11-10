var scraper = {
	
	init : function(){
		self = this;
		this.bind();
	},
	
	bind : function(){
		$('#scraper-form').on('submit', function(e){
			self.startScrape(e)
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
				console.log(response);
		});
	},
	
	endScrape : function(json){
		
	}
};

$(document).ready(function(){
	scraper.init();
});