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
	
	/*
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
	*/
	updateLinks : function(e){
		console.log('update');
		var action = $(e.target).data('ajax-action');
		$('#url-list-stats .loading').fadeIn(100);
		$(e.target).hide();
		$.ajax({
			type: 'POST',
			url: 'controllers/scrape_controller.php?ajax=' + action,
			dataType : 'json'
		}).done(function(response) {
				self.updateLinksDone(response);
		});
	},
	
	updateLinksDone : function(json){
		console.log('done');
		$('#url-list-stats .loading').hide();
		$('#url-list-stats .update-links').fadeIn(100);
		if(json.error){
			console.log('error');
			$('#url-list-stats .error').html(json.errorMessage);
			$('#url-list-stats .error').show();
		}
		else{
			console.log('success');
			self.updateLinkStats();
		}
	},
	
	updateLinkStats : function(){
		$('#url-list-stats .error').hide();
		$('#url-list-stats .last-updated').html('<p>laddar ...<p>');
		$('#url-list-stats .total-links').html('<p>laddar ...<p>');
		$('#url-list-stats .course-links').html('<p>laddar ...<p>');
		$('#url-list-stats .program-links').html('<p>laddar ...<p>');
		$('#url-list-stats .project-links').html('<p>laddar ...<p>');
		$('#url-list-stats .other-links').html('<p>laddar ...<p>');
		$('#url-list-stats .file-path').html('<p>laddar ...<p>');

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
			$('#url-list-stats .error').html(json.errorMessage);
			$('#url-list-stats .error').show();
			$('#url-list-stats .last-updated').html('<p>Ingen info<p>');
			$('#url-list-stats .total-links').html('<p>Ingen info<p>');
			$('#url-list-stats .course-links').html('<p>Ingen info<p>');
			$('#url-list-stats .program-links').html('<p>Ingen info<p>');
			$('#url-list-stats .project-links').html('<p>Ingen info<p>');
			$('#url-list-stats .other-links').html('<p>Ingen info<p>');
			$('#url-list-stats .file-path').html('<p>Ingen info<p>');
		}
		else{
			$('#url-list-stats .last-updated').html(json.lastUpdated);
			$('#url-list-stats .total-links').html(json.totalCount);
			$('#url-list-stats .course-links').html(json.courseCount);
			$('#url-list-stats .program-links').html(json.programCount);
			$('#url-list-stats .project-links').html(json.projectCount);
			$('#url-list-stats .other-links').html(json.otherCount);
			$('#url-list-stats .file-path').html('<a href="' + json.filePath + '" target="_blank">Link list file</a>');
		}
	}
};

$(document).ready(function(){
	scraper.init();
});