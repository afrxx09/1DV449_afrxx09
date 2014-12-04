var TI = {
	map : null,
	markers : [],
	locations : [],
	categoryFilter : -1,
	
	init : function(){
		this.map = new google.maps.Map(document.getElementById('map-container'), gmap.mapOptions);
		this.map.setOptions({styles: gmap.styles});

		this.binds();
		this.getAll();
	},
	binds : function(){
		var self = this;
		$('#category-filter').on('click', 'li', function(e){
			$(this).siblings('.active').removeClass('active');
			$(this).addClass('active');
			self.categoryFilter = parseInt($(this).data('category-filter'));
			self.renderList();
		});
		$('#location-list').on('click', 'li', function(){
			$(this).siblings('.active').removeClass('active');
			$(this).addClass('active');
		});
	},
	getAll : function() {
		var self = this;
		$.ajax({
            type:'POST',
            url:'ajax.php',
            data:{'action':'getAll'},
            dataType:'json'
        }).done(function(json){
        	self.getAllDone(json);
        });
	},
	getAllDone : function(json){
		var self = this;
		var i, location, contentString, latlng;
		for(i = 0; i < json.length; i++){
			location = json[i];
			
			latlng = new google.maps.LatLng(location.latitude, location.longitude);
			location.marker = new google.maps.Marker({
				position: latlng,
				title: 'asd'
			});
			
			/*
			google.maps.event.addListener(location.marker, 'click', function(){
				location.infoWindow.open(self.map, this);
			});
			*/
			contentString = '' +
				'<h3>' + location.title + '</h3>' +
				'<p>' + location.createddate + '</p>' +
				'<p>' + location.description + '</p>' +
				'<p>' + location.exactlocation + '</p>' +
				'';
			infoWindow = new google.maps.InfoWindow();
			google.maps.event.addListener(location.marker,'click', (function(m, cs, iw){
				return function() {
					if(iw){iw.close();}
					iw.setContent(cs);
					iw.open(self.map, m);
				};
			})(location.marker, contentString, infoWindow)); 
			
			this.locations.push(location);
		}
		this.renderList();
	},
	renderList : function(){
		var locationList = '';
		for(var i = 0; i < this.locations.length; i++){
			var l = this.locations[i];
			l.marker.setMap(null);
			if(this.categoryFilter === -1 || this.categoryFilter === l.category){
				locationList += '<li>' +  l.title + '</li>';
				l.marker.setMap(this.map);
			}
		}
		$('#location-list').html(locationList);
	}
};

google.maps.event.addDomListener(window, 'load', function(){
	TI.init();
});