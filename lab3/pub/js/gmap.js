var gmap = {
	mapOptions : {
		'center': { lat: 63.0987472, lng: 16.5279964},
		'zoom': 4,
		'streetViewControl': false,
		'panControl': false,
		'overviewMapControl': false,
		'zoomControlOptions': {
			'position': google.maps.ControlPosition.LEFT_TOP
		},
		mapTypeControlOptions: {
			'position': google.maps.ControlPosition.TOP_LEFT
		}
	},
	styles : [
		{
			stylers:[
				{ hue: "#44e" },
				{ saturation : 0}
			]
		},
		{
			featureType : 'road',
			stylers:[
				{ hue: "#00f" },
				{ saturation : 100}
			]
		}
	],

};