$(document).ready(function(){
	var v_map = new map();
	v_map.init();
	
});

function map(){
	var _this;
	var geocoder;
	var map;
	
	this.init = function(){
		//getScript();
		_this = this;
		$('a[name="showMap"]').click(function(){
			var _mapLayer = $('<div id="mapLayer"><div id="mapContainer"><div class="close">X</div><div id="map"></div></div></div>');
			$('body').append(_mapLayer);
			_this.loadMap();
		});
	};
	
	this.loadMap = function(){
		_this.displayMap();
	};
	
	this.displayMap = function(){
		
		var geocoder_opt = {
				'address': $gl_mapLocPLZ
				, 'region': $gl_mapLocCountry
		}
		
		var latlng = new google.maps.LatLng(48.338844, 9.9464176);
	    var myOptions = {
	      zoom: 12,
	      center: latlng,
	      mapTypeId: google.maps.MapTypeId.ROADMAP  
	    };
	    
		geocoder = new google.maps.Geocoder();
	    map = new google.maps.Map(document.getElementById("map"), myOptions);
		geocoder.geocode(geocoder_opt, function(results, status){
			if(status == google.maps.GeocoderStatus.OK){				
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
			}
		});
		
		$('#mapContainer .close').click(function(){
			$('#mapLayer').remove();
		});

	};
}