var TI = {
	map : null,

	init : function(){
		this.map = new google.maps.Map(document.getElementById('map-container'), gmap.mapOptions);
		this.map.setOptions({styles: gmap.styles});

		this.binds();
		this.getAll();
	},
	binds : function(){

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
		for(var i = 0; i < json.length; i++){
			var location= json[i];
			var ll = new google.maps.LatLng(location.latitude, location.longitude);
			var contentString = '<p>' + location.title + '</p>';
			var infoWindow = new google.maps.InfoWindow({
				content: contentString
			});
			var marker = new google.maps.Marker({
				position: ll,
				map: this.map,
				title: 'asd'
			});
			google.maps.event.addListener(marker, 'click', function(){
				infoWindow.open(self.map, marker);
			});
		}
	}
};

google.maps.event.addDomListener(window, 'load', function(){
	TI.init();
});