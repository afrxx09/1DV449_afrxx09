var TI = {
	map : null,
	infoWindow : null,
	locations : [],
	categoryFilter : -1,
	
	init : function(){
		this.map = new google.maps.Map(document.getElementById('map-container'), gmap.mapOptions);
		this.map.setOptions({styles: gmap.styles});
		this.infoWindow = new google.maps.InfoWindow();
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
			var markerId = parseInt($(this).data('marker-id'));
			google.maps.event.trigger(self.locations[markerId].marker, 'click');
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
			location.marker = this.createMarker(location);
			this.locations.push(location);
		}
		this.renderList();
	},
	createMarker : function(location){
		var self = this;
		var latlng = new google.maps.LatLng(location.latitude, location.longitude);
		var marker = new google.maps.Marker({
			position : latlng,
			animation : google.maps.Animation.DROP
		});
		google.maps.event.addListener(marker,'click', function(){
			self.openInfoWindow(this, location);
		});
		return marker;
	},
	openInfoWindow : function(marker, location){
		this.infoWindow.close();
		this.infoWindow.setContent(this.getInfoWindowContent(location));
		this.infoWindow.open(this.map, marker);
	},
	getInfoWindowContent : function(location){
		var contentString = '' +
			'<h3>' + location.title + '</h3>' +
			'<p>' + location.createddate + '</p>' +
			'<p>' + location.description + '</p>' +
			'<p>' + location.exactlocation + '</p>' +
			'';
		return contentString;
	},
	renderList : function(){
		var locationList = '';
		var self = this;
		for(var i = 0; i < this.locations.length; i++){
			var l = this.locations[i];
			l.marker.setMap(null);
			if(this.categoryFilter === -1 || this.categoryFilter === l.category){
				locationList += '<li data-marker-id="'+ i +'">' +  l.title + '</li>';
				self.addMarker(i);
			}
		}
		$('#location-list').html(locationList);
	},
	addMarker : function(i){
		var self = this;
		(function(j){
			setTimeout(function(){
				self.locations[i].marker.setMap(self.map);
			}, j * 20);
		})(i);
	}
};

google.maps.event.addDomListener(window, 'load', function(){
	TI.init();
});