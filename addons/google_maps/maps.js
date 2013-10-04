// 7.1.2-41-gdae363e


//TODO: Convert this into a jQuery plugin

var addon_google_maps = {
	useMarkerIcon : false,
	
	markerIcon : {
		path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
		fillColor: "#77f",
		fillOpacity: 0.3,
		scale: 10,
		strokeColor: "black",
		strokeWeight: 3
	},
	
	defaultMapOptions : {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	},
	
	init : function (lat, longitude, location, canvas) {
		if (!lat || !longitude) {
			return;
		}
		
		//alert('lat: '+lat+' - long: '+longitude);
		
		var myLatlng = new google.maps.LatLng(lat, longitude);
		var mapOptions = addon_google_maps.defaultMapOptions;
		
		mapOptions.center = myLatlng;
		
		if (!mapOptions.mapTypeId) {
			//just to make sure it is set correctly
			mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
		}
		
		var map = new google.maps.Map(document.getElementById(canvas),
				mapOptions);
		
		var markerOptions = {
			position: myLatlng,
			map: map//,
			//Do NOT do title at this point, since it's just a hover thingy.. Will need to re-visit later
			//title: '{$location|escape_js}'
		};
		if (addon_google_maps.useMarkerIcon) {
			markerOptions.icon = addon_google_maps.markerIcon;
		}
		
		var marker = new google.maps.Marker (markerOptions);
		//return the marker in case it is useful for custom stuff
		return marker;
	}
};
