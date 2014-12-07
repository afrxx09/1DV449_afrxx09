var TI = {
	map : null,
	infoWindow : null,
	locations : [],
	categoryFilter : -1,
	initDone : false,
	//priorityColors : ['#BA2C00', '#D97F00', '#DEB800', '#AADB00', '#00BA23'],
	priorityColors : ['red', 'orange', 'yellow', 'green', 'blue'],
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
		this.initDone = true;
	},
	createMarker : function(location){
		var self = this;
		var icon = 'http://maps.google.com/mapfiles/ms/icons/' + this.priorityColors[location.priority - 1] + '.png';
		var latlng = new google.maps.LatLng(location.latitude, location.longitude);
		var marker = new google.maps.Marker({
			position : latlng,
			animation : google.maps.Animation.DROP,
			icon: new google.maps.MarkerImage(icon)
		});
		google.maps.event.addListener(marker,'click', function(){
			self.openInfoWindow(this, location);
			self.toggleActiveMarker(this, location);
		});
		return marker;
	},
	openInfoWindow : function(marker, location){
		var newContent = this.getInfoWindowContent(location);
		if(this.infoWindow.getContent() == null || this.infoWindow.getContent() != newContent){
			this.infoWindow.close();
			this.infoWindow.setContent(newContent);
			this.infoWindow.open(this.map, marker);
		}
	},
	getInfoWindowContent : function(location){
		var contentString = '' +
			'<div class="info-window">' +
				'<h3 class="info-window-header">' + location.title + '</h3>' +
				'<p class="info-window-date">' + location.createddate + '</p>' +
				'<p class="info-window-description">' + location.description + '</p>' +
				'<p class="info-window-exact-location">' + location.exactlocation + '</p>' +
			'</div>'
			'';
		return contentString;
	},
	toggleActiveMarker : function(marker, location){
			for(var i = 0; i < this.locations.length; i++){
				if(this.locations[i].marker !== marker){
					this.locations[i].marker.setAnimation(null);
					this.locations[i].marker.setIcon('http://maps.google.com/mapfiles/ms/icons/' + this.priorityColors[this.locations[i].priority - 1] + '.png');
				}
			}
			if(marker.getAnimation() === null){
				marker.setAnimation(google.maps.Animation.BOUNCE);
				marker.setIcon('http://maps.google.com/mapfiles/ms/icons/pink.png');
			}
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
		if(!this.initDone){
			var self = this;
			(function(j){
				setTimeout(function(){
					self.locations[i].marker.setMap(self.map);
				}, j * Math.floor((Math.random() * 25) + 1));
			})(i);
		}
		else{
			this.locations[i].marker.setMap(this.map);
		}
	}
};

google.maps.event.addDomListener(window, 'load', function(){
	TI.init();
});