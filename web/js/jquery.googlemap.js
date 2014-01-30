$(function() {
    /*
     * Initialisation de la Map
     */
    $.fn.googleMap = function(params) {
        params = $.extend( {
                zoom: 10,
                coords: [50.850200,4.341600],
                type: "ROADMAP"
        }, params);

        // VÃ©rification du type de la carte
        switch(params.type) {
                case 'ROADMAP':
                case 'SATELLITE':
                case 'HYBRID':
                case 'TERRAIN':
                        params.type = google.maps.MapTypeId[params.type];
                        break;
                default:
                        params.type = google.maps.MapTypeId.ROADMAP;
                        break;
        }

        // VÃ©rification des coordonnÃ©es du centre de la carte

        this.each(function() {

            var map = new google.maps.Map(this, {
                zoom: params.zoom,
                center: new google.maps.LatLng(params.coords[0], params.coords[1]),
                mapTypeId: params.type
            });

            $(this).data('googleMap',    map);
            $(this).data('googleMarker', new Array());
            $(this).data('googleBound',  new google.maps.LatLngBounds());
        });
        
        return this;
    }
    
    /*
     * Ajouter un point
     */
    $.fn.addMarker = function(params) {
		params = $.extend( {
			coords  : false,
			address : false,
			url     : false,
			id      : false,
			icon    : false,
			title   : "",
			text    : "",
			success : function() {}
		}, params);
		
		this.each(function() {
			$this = $(this);
			
        	if(!$(this).data('googleMap')) {
	        	console.log("jQuery googleMap : Unable to add a marker where there is no map !");
	        	return false;
        	}
        	
        	if(!params.coords && !params.address) {
	        	console.log("jQuery googleMap : Unable to add a marker if you don't tell us where !");
	        	return false;
        	}
        	        	
        	if(params.address && typeof params.address == "string") {
	        	geocoder = new google.maps.Geocoder();
	        	
			    geocoder.geocode({
			    	address  : params.address,
			    	bounds   : $this.data('googleBound'),
			    	language : 'french'
			    }, function(results, status) {
			        if (status == google.maps.GeocoderStatus.OK) {
			        
			        	$this.data('googleBound').extend(results[0].geometry.location);
			            
			            if(params.icon) {
			        		var marker = new google.maps.Marker({
				                map: $this.data('googleMap'),
				                position: results[0].geometry.location,
				                title: params.title,
				                icon: params.icon
				            });
		        		} else {
		        			
			        		var marker = new google.maps.Marker({
				                map: $this.data('googleMap'),
				                position: results[0].geometry.location,
				                title: params.title
				            });
		        		}
			            
			            if(params.title != "" && params.text != "" && !params.url) {
				            var infowindow = new google.maps.InfoWindow({
					            content: "<h1>"+params.title+"</h1>"+params.text
					        });
					        
					        var map = $this.data('googleMap');
					        google.maps.event.addListener(marker, 'click', function() {
						        infowindow.open(map, marker);
					        });
			            } else if(params.url) {
				            google.maps.event.addListener(marker, 'click', function() {
				                document.location = params.url;
				            });
			            }
			            
			            if(!params.id) {
			           		$this.data('googleMarker').push(marker);
			            } else {
				            $this.data('googleMarker')[params.id] = marker;
			            }
			            
			            if($this.data('googleMarker').length == 1) {
				            $this.data('googleMap').setCenter(results[0].geometry.location);
				            $this.data('googleMap').setZoom(9);
			            } else {
				        	$this.data('googleMap').fitBounds($this.data('googleBound'));
			            }
			            			            
			            params.success({
				            lat: results[0].geometry.location.Xa,
				            lon: results[0].geometry.location.Ya
			            });
			
			        } else {
			            console.log("jQuery googleMap : Unable to find the place asked for the marker ("+status+")");
			        }
			    });
        	} else {
        	
        		$this.data('googleBound').extend(new google.maps.LatLng(params.coords[0], params.coords[1]));
        		
        		
        		if(params.icon) {
	        		var marker = new google.maps.Marker({
		                map: $this.data('googleMap'),
		                position: new google.maps.LatLng(params.coords[0], params.coords[1]),
		                title: params.title,
		                icon: params.icon
		            });
        		} else {
	        		var marker = new google.maps.Marker({
		                map: $this.data('googleMap'),
		                position: new google.maps.LatLng(params.coords[0], params.coords[1]),
		                title: params.title
		            });
        		}
	        	
	            
	            if(params.title != "" && params.text != "" && !params.url) {
		            var infowindow = new google.maps.InfoWindow({
			            content: "<h1>"+params.title+"</h1>"+params.text
			        });
			        
			        var map = $this.data('googleMap');
			        google.maps.event.addListener(marker, 'click', function() {
				        infowindow.open(map, marker);
			        });
	            } else if(params.url) {
		            google.maps.event.addListener(marker, 'click', function() {
		                document.location = params.url;
		            });
	            }
	            
	            if(!params.id) {
	           		$this.data('googleMarker').push(marker);
	            } else {
		            $this.data('googleMarker')[params.id] = marker;
	            }

	            if($this.data('googleMarker').length == 1) {
		            $this.data('googleMap').setCenter(new google.maps.LatLng(params.coords[0], params.coords[1]));
		            $this.data('googleMap').setZoom(9);
	            } else {
		        	$this.data('googleMap').fitBounds($this.data('googleBound'));
	            }
	            
	            params.success({
		            lat: params.coords[0],
		            lon: params.coords[1]
	            });
        	}
        });
        
        return this;
	}
	
    /*
     * Générer un itinéraire
     */
    $.fn.addWay = function(params) {
        params = $.extend( {
                start   : false,
                end     : false,
                route   : false,
                langage : 'french',
                waypoints: false,
                optimizeWaypoints: false
        }, params);

        var direction = new google.maps.DirectionsService({
            region: "fr"
        });

        var way       = new google.maps.DirectionsRenderer({
            draggable: false,
            map:       $(this).data('googleMap'),
            panel:     document.getElementById(params.route),
            provideTripAlternatives: true
        });
        
        if(typeof params.end != "object") {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                address  : params.end,
                bounds   : $(this).data('googleBound'),
                language : params.langage
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    var request = {
                        origin: new google.maps.LatLng(params.start[0], params.start[1]),
                        destination: results[0].geometry.location,
                        waypoints: params.waypoints,
                        optimizeWaypoints: params.optimizeWaypoints,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING,
                        unitSystem: google.maps.DirectionsUnitSystem.METRIC,
                        region: "fr"
                    };

                    direction.route(request, function(response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            way.setDirections(response);
                        } else {
                            alert("Address not found");
                        }
                    });

                } else {
                    console.log("jQuery googleMap : Unable to find the place asked for the route ("+status+")");
                }
            });
        } else {
            var request = {
                origin: new google.maps.LatLng(params.start[0], params.start[1]),
                destination: new google.maps.LatLng(params.end[0], params.end[1]),
                waypoints: params.waypoints,
                optimizeWaypoints: params.optimizeWaypoints,
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
                unitSystem: google.maps.DirectionsUnitSystem.METRIC,
                region: "fr"
            };
            direction.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    way.setDirections(response);
                    
                    
                    var route = response.routes[0];
                    var distance = 0,
                        time_taken = 0,
                        calc_distance = 0;
                    for(var i=0; i<route.legs.length; i++){
                        distance += route.legs[i].distance.value; 
                        time_taken += route.legs[i].duration.value; 
                        calc_distance += route.legs[i].distance.value; 
                    }
                    distance = Math.round(distance/1000);
                    var heures= Math.floor(time_taken / 3600);
                    var minutes= Math.round((time_taken /60) % 60);
                    var time =heures+" heure "+minutes+" minutes";
                    //var coordString = JSON.stringify(route.overview_path);
                    var coordString = [];
                    var etapesString = JSON.stringify(params.waypoints);
                    for(var i=0;i<route.overview_path.length;i++){
                        var $lat = route.overview_path[i].lat();
                        var $lng = route.overview_path[i].lng();
                        coordString.push({lat:$lat,lng:$lng});
                    }
                    
                    //var heure_depart = $("#input_heure_depart").val();
                    $("#input_coord").val(JSON.stringify(coordString));
                    $("#distance").text(distance);
                    $("#input_distance").val(calc_distance);
                    $("#duree").text(time);
                    $("#input_duree").val(time_taken);
                    $("#input_etapes").val(etapesString);
                    $("#recalculer_prix span").click();
                } else {
                    alert("Address not found");
                }
            });
        }
       return this;
    }
    
});