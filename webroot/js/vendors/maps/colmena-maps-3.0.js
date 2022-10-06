/*
GOOGLE MAPS LOADER V 3.0
DEVELOPED BY CARLOS MEDINA & BORJA RODRIGUEZ
2022 © ALL RIGHTS RESERVED

REQUIREMENTS NOTE
THIS PLUGIN REQUIRES GOOGLE MAPS API
*/

(function ($) {
	$.fn.mapsLoader = function (options) {
		var parent = this;
		var canvas = this[0];

		if (typeof canvas === "undefined") {
			return;
		}

		var settings = $.extend({
			canvas: parent,
			map: null,
			geocoder: null,
			bounds: new google.maps.LatLngBounds(),
			markers: [],
			infoWindows: [],
			// These are the defaults.
			address: null,                 // Address to geocode
			latitude: null,                // Latitude of marker point
			longitude: null,               // Longitude of marker point
			clatitude: 43.362162,          // Longitude of map center
			clongitude: -5.848417,         // Longitude of map center
			zoom: 5,                       // Default zoom of map
			coordinatesClass: ".address",  // Default class where leave coordinates of geocode
			image: 'img/logo/gmaps.png',   // Default marker image
			multipleMarkers: false,        // Allow or not multiple markers in the same map
			draggableMarker: false,        // Allow dragging marker
			infoWindowContent: null        // Set infoWindow content if wanted
		}, options);

		if ((options.clatitude == null && options.clongitude == null) ||
			(options.clatitude == "" && options.clongitude == "")) {
			settings.clatitude = 43.362162;
			settings.clongitude = -5.848417;
		}

		initialize(canvas, settings);

		// Draw marker directly from latitude and longitude
		drawMarker(settings);

		// Draw marker from address
		codeAddress(settings);
	};

	function initialize(canvas, settings) {
		// Create center of the map
		var latlng = new google.maps.LatLng(settings.clatitude, settings.clongitude);
		// Only initialize map if is not already initialized
		if (settings.map == null) {
			settings.geocoder = new google.maps.Geocoder();

			// Set map options according to settings
			var mapOptions = {
				zoom: settings.zoom,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			settings.map = new google.maps.Map(canvas, mapOptions);
		} else {
			settings.map.setZoom(settings.zoom);
			settings.map.setCenter(latlng);
		}

		// If we don't allow multiple markers, clear previous
		if (!settings.multipleMarkers) {
			clearMarkers(settings);
		}
	}

	function codeAddress(settings) {
		// If address is a valid address, perform geocode
		if (settings.address != null && settings.address != "") {
			settings.geocoder.geocode({ 'address': settings.address }, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {

					settings.latitude = results[0].geometry.location.lat();
					settings.longitude = results[0].geometry.location.lng();

					drawMarker(settings);

					settings.canvas.parents(settings.coordinatesClass).find(".input .latitude").val(settings.latitude);
					settings.canvas.parents(settings.coordinatesClass).find(".input .longitude").val(settings.longitude);
				} else {
					throw 'Google Maps Geocode was not successful for the following reason: ' + status;
				}
			});
		}
	}

	function drawMarker(settings) {
		// If latitude and longitude are valid values continue
		// If not, avoid this method
		if (settings.latitude != null && settings.longitude != null &&
			settings.latitude != "" && settings.longitude != "") {

			var latlngdir = new google.maps.LatLng(settings.latitude, settings.longitude);

			// Create marker and add it to markers array
			var marker = new google.maps.Marker({
				map: settings.map,
				position: latlngdir,
				animation: google.maps.Animation.DROP,
				draggable: settings.draggableMarker
				//icon: settings.image
			});

			if (settings.draggableMarker) {
				google.maps.event.addListener(marker, 'dragend', function () {
					var point = marker.getPosition();
					settings.map.panTo(point);
					settings.canvas.parents(settings.coordinatesClass).find(".input .latitude").val(point.lat());
					settings.canvas.parents(settings.coordinatesClass).find(".input .longitude").val(point.lng());
				});
			}

			settings.markers.push(marker);

			// Draw infoWindow according to settings
			drawInfoWindow(settings, marker, settings.infoWindowContent);

			// Refresh map to fit markers bounds
			refreshMap(settings, latlngdir, settings.zoom);
		}
	}

	function drawInfoWindow(settings, marker, infoWindowContent) {
		if (infoWindowContent != null && infoWindowContent != "") {
			var infowindow = new google.maps.InfoWindow();

			google.maps.event.addListener(marker, 'click', (function (marker) {
				return function () {
					closeInfoWindows(settings);
					infowindow.setContent(infoWindowContent);
					infowindow.open(settings.map, marker);
				}
			})(marker));

			settings.infoWindows.push(infowindow);
		}
	}

	function clearMarkers(settings) {
		for (var i = 0; i < settings.markers.length; i++) {
			settings.markers[i].setMap(null);
		};

		settings.markers = [];

	}

	function closeInfoWindows(settings) {
		for (var i = 0; i < settings.infoWindows.length; i++) {
			settings.infoWindows[i].close();
		};
	}

	function refreshMap(settings, latlng, maxzoom) {
		settings.map.setCenter(latlng);

		// Only change bounds if there are 2 or more points
		if (settings.markers.length > 1) {
			settings.bounds.extend(latlng);
			settings.map.fitBounds(settings.bounds);
		}

		if (settings.map.getZoom() > maxzoom) {
			settings.map.setZoom(maxzoom);
		}
	}

}(jQuery));
