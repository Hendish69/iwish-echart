@extends('layouts.app')

@section('template_title')
    VFR
@endsection

@section('head') 
 
    <style> 
	  #info-pane {
	    position: absolute;
	    bottom: 30px;
	    right: 30px;
	    z-index: 400;
	    padding: 1em;
	    background: white;
	  }
	  .select2-dropdown.select2-dropdown--below{
	  	min-width: 300px!important;
	  }
	  button:disabled {
	      cursor: not-allowed;
	      opacity: 0.5;
	  }
	  .hideEl{
	  	display:none;" 
	  }
	</style>

@endsection

@section('content')
<?php
// dd($airports->content());
?>
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
        	<div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">VFR Planning</h3>
                    </div><!-- .nk-block-head-content -->
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                   <!--  <li>
                                        <div class="drodown">
                                            <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">Last</span> 30 Days</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#"><span>Last 30 Days</span></a></li>
                                                    <li><a href="#"><span>Last 6 Months</span></a></li>
                                                    <li><a href="#"><span>Last 1 Years</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li> -->
                                    <!-- <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li> -->
                                </ul>
                            </div>
                        </div>
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head --> 
        	<div class="nk-block">    
            	<div class="row g-gs" style="min-height: 100%;">
		        	<div class="col-xxl-4 col-md-12 col-lg-5 mr-0" style="min-height:600px!important;padding-right:unset!important">
			            <div class="card h-100">
			                <div class="card-inner p-2 pt-3">
			                     <form action="" method="POST" id="planningForm">
			                     	@csrf
			                     	<div class="row">
			                     		<div class="col-6">
	                                    	<div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="departure"><a href="/vfr_planning" id="lbl_depart">Departure</a></label>
		                                        <div class="col-sm-7"> 
	                                        		<div class="form-control-wrap"  x-data="{{ $airports->content() }}" >
													  <select x-model="data" class="form-select form-control form-control-lg airportlist" data-search="on" name="departure" id="departure">
													     <option value="" selected="selected"></option>
													    <template x-for='airport in data'>
													      <option :value="airport.value" x-text="airport.label"></option>
													    </template>
													  </select>   
                                                    </div> 
		                                        </div>
		                                    </div>
		                                    <div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="etd">ETD(Z)</label>
		                                        <div class="col-sm-7">
		                                            <input type="text" class="form-control" name="etd" id="etd" readonly>
		                                        </div>
		                                    </div>	
		                                    <div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="aircraft">Aircraft</label>
		                                        <div class="col-sm-7">
		                                            <input type="text" class="form-control" name="aircraft">
		                                        </div>
		                                    </div>	
	                                    </div>
	                                    <div class="col-6">
	                                    	<div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="destiny"><a href="/vfr_planning" id="lbl_destiny">Destination</a></label>
		                                        <div class="col-sm-7">
		                                            <div class="form-control-wrap" x-data="{{ $airports->content() }}">
													  <select x-model="data" class="form-select form-control form-control-lg airportlist" data-search="on" name="destination" id="destination">
													     <option value="" selected="selected"></option>
													    <template x-for="airport in data">
													      <option :value="airport.value" x-text="airport.label"></option>
													    </template>
													  </select>  
													  
                                                    </div>
		                                        </div>
		                                    </div>
		                                    <div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="etd">ETA(Z)</label>
		                                        <div class="col-sm-7">
		                                            <input type="text" class="form-control" name="eta" id="eta" readonly>
		                                        </div>
		                                    </div>	
		                                    <div class="mb-3 row">
		                                        <label class="col-sm-5 form-label" for="etd">Speed</label>
		                                        <div class="col-sm-7">
		                                            <input type="number" step="0.1" class="form-control" name="speed" id="speed" value="377.96">
		                                        </div>
		                                    </div>	
	                                    </div> 
			                     	</div>
			                     	<div class="form-group pt-2 text-center">
                                        <button  id="planning_btn" class="btn btn-sm btn-primary" >Planning</button>
                                    </div>
                                </form>
                                	<!-- <div class="form-group pt-2 text-center">
                                        <button type="button" id="planning_btn" class="btn btn-sm btn-primary" onclick="drawPoly(flightPlanCoordinates)">Planning</button>
                                    </div> -->
			                    
			                </div><!-- .card-inner -->
			                <div class="nk-tb-list mt-n2 p-2"> 
			                    <div class="row pt-2" style="display:none;" id="vfr_table"> 
									<div class="col">
										<table class="table table-bordered align-items-center table-sm" id="vfr_table_">
									  <thead class="thead-light">
									   <tr>
									     <th scope="col">#</th> 
									     <th scope="col">Route</th>
									     <th scope="col" class="hideEl">Coordinates</th>
									     <th scope="col">Distance(Nm)</th>
									     <th scope="col">Time</th>
									    </tr>
									  </thead>
									  <tbody id="vfr_table_body"> 
									     
									  </tbody>
									   
									</table>
									<div class="col-md-12 text-right mt-1">
										<button type="button" id="csv" class="btn btn-sm btn-primary"  >SAVE DETAIL</button>
										<button type="button" id="pdf" class="btn btn-sm btn-primary"  >TO PDF</button>
									</div>
									</div>
								</div>
									 
		                        <div x-data="{ open: false }">
							    	<button x-on:click="open = ! open"  class="btn btn-sm btn-primary mt-2">New Route</button>
							    	<div x-show="open">
							    		<div class="row pt-2">
						                    <form x-data="handler()" @submit.prevent="submitData" id="frmRoute">
						                    	<div class="mb-3 row p-2">
						                    		@csrf
				                                    <label class="col-sm-5 form-label" for="route">Route Name</label>
				                                    <div class="col-sm-7">
				                                        <input type="text" class="form-control" name="route" id="route">
				                                        <input type="hidden" class="form-control" name="depart_" id="depart_">
				                                        <input type="hidden" class="form-control" name="destiny_" id="destiny_">
				                                    </div>
				                                </div>	
												<div class="col">
													<table class="table table-bordered align-items-center table-sm">
												  <thead class="thead-dark">
												   <tr>
												     <th scope="col">#</th>
												     <th scope="col">Waypoint</th><th scope="col">Latitude</th><th scope="col">Longitude</th>
												     <th scope="col">Action</th>
												    </tr>
												  </thead>
												  <tbody>
												    <template x-for="(field, index) in fields" :key="index">
												     <tr>
												      <th scope="row" x-text="index + 1" style="vertical-align: middle;"></th>
												      <td style="display:none"><input x-model="field.wpt_id" type="text" name="wpt_id[]" class="form-control"></td>
												      <td><input x-model="field.wpt" type="text" name="wpt[]" class="form-control" x-on:change="handleWpt($event, index)" ></td>
												      <td><input x-model="field.lat" type="text" name="lat[]" class="form-control" x-on:change="handleLat($event)"></td>
												      <td><input x-model="field.lng" type="text" name="lng[]" class="form-control"></td>
												       <td><button type="button" class="btn btn-danger btn-small" @click="removeField(index)">&times;</button></td>
												    </tr>
												   </template>
												  </tbody>
												  <tfoot>
												     <tr>
												     	<td colspan="2" class="text-left">
												     		<!-- <button type="button" class="btn btn-info" @click="saveRoute()" id="saveRoute">Save</button> -->
												     		<button class="btn bg-success disabled:opacity-50 text-white w-full" x-text="buttonLabel" :disabled="loading"></button>
												     	</td>
												       <td colspan="3" class="text-right"><button type="button" class="btn btn-info" @click="addNewField()" id="addNewField">+</button></td>
												    </tr>
												  </tfoot>
													</table>
														<p x-text="message"></p>
												</div>
											</form>
										</div> 
									</div>
								</div>
		                    </div> 
			            </div><!-- .card -->
			        </div><!-- .col -->
		        	<div class="col-xxl-8  col-md-12 col-lg-7" style="min-height:400px!important">
		                <div class="card card-full"> 
                			<div class="card-body">
                				<div  id="map" class="h-100"></div>
                				<!-- <div id="info-pane" class="leaflet-bar">Hover to inspect</div> -->
                			</div>    
		                </div><!-- .card -->
		            </div>
            	</div>
 				<!-- <div id="mapid" style="width:100%; height:100% !important; min-height:600px !important;z-index:auto" class="site-content mt-0">
                </div> -->
    		</div>
	    </div>
	</div>
</div>
@endsection
@section('footer_scripts') 
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> 
<script src="{{ asset ('/images/marker/geolib.js') }}" type="text/javascript"></script>

<script type="text/javascript">
var map;
var markerIndex=[];
var markers=[];
var polys=[];
var ddd = 0;
// const formToJSON = (elements) =>
//   [].reduce.call(
//     elements,
//     (data, element) => {
//       	data[element.name] = element.value; 
// 	 	return data; 
//     },
//     {},
//   );
 
function objectifyForm(formArray) {
    //serialize data function
    var returnArray = {};
    for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}
let updateNewWp = function (index, LatLng){ 
		var coord = LatLng.toJSON();  
	    var _lat = $('[name="lat[]"]').eq(index);
	    if (_lat.length == 0){
		    	 //1 - get google
		    var first = function(){
		        return new Promise(function(resolve){
		        	$('#addNewField').click();
		            resolve();
		        });
		    }
		  	var second = function(){
		        return new Promise(function(resolve){
		            var _lat = $('[name="lat[]"]').eq(index);
		    		var _lng = $('[name="lng[]"]').eq(index);
			    		_lat.val(coord.lat).change();
			    		_lng.val(coord.lng).change();
		            resolve();
		        });
		    }  	
		    first().then(second);
	    }else{
	    	 var _lng = $('[name="lng[]"]').eq(index);
	    	 // console.log('coord.lat :' + coord.lat);
	    		_lat.val(coord.lat).change();
	    		_lng.val(coord.lng).change();
	    }
	}
function initMap() {
	var mapOptions = {
        center: new google.maps.LatLng(-2.85, 118.1),
         // center: { lat: 0, lng: -180 },
        // center: new google.maps.LatLng(41.133659, -73.945259),
        zoom: 5,
        mapTypeId: "roadmap",
        mapTypeControl: true,
        mapTypeControlOptions: {
        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
        position: google.maps.ControlPosition.TOP_RIGHT,
        },
        zoomControl: true,
        zoomControlOptions: {
        position: google.maps.ControlPosition.LEFT_CENTER,
        },
        scaleControl: true,
        streetViewControl: true,
        streetViewControlOptions: {
        position: google.maps.ControlPosition.LEFT_BOTTOM,
        },
        fullscreenControl: true,
         styles: [
            { featureType: "landscape.natural",
                elementType: "labels.icon",
                stylers: [{ visibility: "on" }],
            },
            { featureType: "administrative.country",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "administrative.locality",
                elementType: "labels",
                stylers: [ { "visibility": "on" } ]
            },
        ]
    }
  map = new google.maps.Map(document.getElementById('map'), mapOptions); 

  // map.data.addGeoJson(jsonData);
   
  map.data.setStyle({
    strokeColor: "#FF0000",
    strokeOpacity: 0.8,
    strokeWeight: 5,
  });
  poly = new google.maps.Polyline({
    strokeColor: "#000000",
    strokeOpacity: 1.0,
    strokeWeight: 3,
  });
  polys.push(poly);
  poly.setMap(map);
  // Add a listener for the click event
  // map.addListener("click", addLatLng);

  google.maps.event.addListener(map, "rightclick", function (event) {
        //event.preventDefault();
       
        var markerIndex = poly.getPath().length;
        poly.setMap(map);
        var isFirstMarker = markerIndex === 0;
        var marker = new google.maps.Marker({
        	id : markerIndex,
            map: map,
            position: event.latLng,
            draggable: true
        });
        updateNewWp(markerIndex, event.latLng);
        markers.push(marker);
        //google.maps.event.addListener(marker, 'dragend', function () {
        //          Hypothetical function - see above
        //          updateTxtPolygon();
        //      });
        
        // if (isFirstMarker) {
        //     google.maps.event.addListener(marker, 'click', function () {
        //         var path = polyLine.getPath();
        //         polyGon.setPath(path);
        //         polyGon.setMap(Map);
        //     });
        //     // we were setting different colored icons so you could tell which was the last point set
        //     //    marker.setIcon(blueIcon);
        // } else {
        //     //    marker.setIcon(yellowIcon);
        // }
        
        
        google.maps.event.addListener(poly, 'click', function(clickEvent){
            //did you want to do something here??
            // alert('dddd');
        });
        
        poly.getPath().push(event.latLng);
        
        //different colored markers so user can tell which was the first marker the placed
        //if(markerIndex > 1)
        //    outlineMarkers[markerIndex-1].setIcon(blueIcon);
        
        // outlineMarkers.push(marker);
                
        google.maps.event.addListener(marker, 'drag', function (dragEvent) {
            poly.getPath().setAt(markerIndex, dragEvent.latLng);
            updateNewWp(markerIndex, dragEvent.latLng);
        });
        google.maps.LatLng.prototype.kmTo = function(a){ 
		    var e = Math, ra = e.PI/180; 
		    var b = this.lat() * ra, c = a.lat() * ra, d = b - c; 
		    var g = this.lng() * ra - a.lng() * ra; 
		    var f = 2 * e.asin(e.sqrt(e.pow(e.sin(d/2), 2) + e.cos(b) * e.cos 
		    (c) * e.pow(e.sin(g/2), 2))); 
		    return f * 6378.137; 
		}

		google.maps.Polyline.prototype.inKm = function(n){ 
		    var a = this.getPath(n), len = a.getLength(), dist = 0; 
		    for (var i=0; i < len-1; i++) { 
		       dist += a.getAt(i).kmTo(a.getAt(i+1)); 
		    }
		    return dist; 
		}
        // updateDistance(outlineMarkers);
    });   
}

// function addLatLng(event) {
// 	markerIndex = poly.getPath().length;
// 	const path = poly.getPath();
// 	// Because path is an MVCArray, we can simply append a new coordinate
// 	// and it will automatically appear.
// 	path.push(event.latLng);

// 	// Add a new marker at the new plotted point on the polyline.
// 	var isFirstMarker = markerIndex === 0;
// 	var marker = new google.maps.Marker({
// 	position: event.latLng,
// 	title: "#" + path.getLength(),
// 	map: map,
// 	draggable: true
// 	});
//     google.maps.event.addListener(marker, 'drag', function (dragEvent) {
//         poly.getPath().setAt(markerIndex, dragEvent.latLng);
//             // updateDistance(outlineMarkers);
//     });
// }
// define function to add marker at given lat & lng
function addMarker(latLng) {
	let marker = new google.maps.Marker({
	    map: map,
	    position: latLng,
	    draggable: true
	});

	//store the marker object drawn on map in global array
	markers.push(marker);
}

function drawMarker(ttl, latLng,colr) {
	let icon='';
	if(colr=='airport'){
			icon = "{{ url('/') }}/images/marker/airport.png";}
	else{
			icon = '//maps.google.com/intl/en_us/mapfiles/ms/micons/'+colr+'-dot.png';}
	
	let marker = new google.maps.Marker({
		position: latLng,
		map,
		title: ttl,
		icon:icon, 
	});
	markers.push(marker);
}
function drawMarkers(marks){
	//Create LatLngBounds object.
    var latlngbounds = new google.maps.LatLngBounds();

    for (var i = 0; i < marks.length; i++) {
        var data = marks[i]
        var myLatlng = new google.maps.LatLng(data.lat, data.lng);
        let uri='';

		if( marks[i].id === 'ORI' || marks[i].id === 'DST'){
			uri = '/images/marker/airport.png';}
		else{
			uri = '/images/marker/NCRP.svg';}

        var icon = {
			url: uri, 
		    scaledSize: new google.maps.Size(10, 10), // scaled size
		};

        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: data.name,
            icon:icon
        });
        markers.push(marker); // push to data markers to remove that
        (function (marker, data) {
        	let ori_linkInfo='';
        	let dst_linkInfo='';
        	if(data.id=='ORI'){
        		let link = $('select[name="departure"]').find('option:selected').text().split(' - ');
        		data.name = link[1];
        		data.id  = link[0];
        		var lbl_dep = document.getElementById('lbl_depart');
 				lbl_dep.setAttribute('href', '{{ url("/") }}/get_infoarpt/'+link[0]);
        		ori_linkInfo = "<a href='{{ url('/') }}/get_infoarpt/"+link[0]+"' class='btn btn-sm btn-primary'>Info</a>";
        	}
        	if(data.id=='DST'){
        		let link = $('select[name="destination"]').find('option:selected').text().split(' - ');
        		data.name = link[1];
        		data.id  = link[0];
        		var lbl_des = document.getElementById('lbl_destiny');
 				lbl_des.setAttribute('href', '{{ url("/") }}/get_infoarpt/'+link[0]);
        		dst_linkInfo = "<a href='get_infoarpt/"+link[0]+"' class='btn btn-sm btn-primary'>Info</a>";
        	}
            google.maps.event.addListener(marker, "click", function (e) {
                infowindow.setContent("<div style = 'max-width:400px;min-height:40px ;'class='p-1'>" +
                						"<b>"+data.id+"</b><br></br>"+	
                						"<table>" +
                							"<tr>" +
                								"<td>Name<td><td>:<td>" +
                								"<td>" +data.name+ "<td>" +
                							"<tr>"+
                							"<tr>" +
                								"<td>Lat<td><td>:<td>" +
                								"<td> " +data.lat+ "<td>" +
                							"<tr>"+
                							"<tr>" +
                								"<td>Lng<td><td>:<td>" +
                								"<td>" +data.lng+ "<td>" +
                							"<tr>"+
                						"</table>"+ 
                						"<br>"+ori_linkInfo+
                						""+dst_linkInfo+
                					  "</div>");
                infowindow.open(map, marker);
            });
        })(marker, data);

        //Extend each marker's position in LatLngBounds object.
        latlngbounds.extend(marker.position);
    }

    //Get the boundaries of the Map.
    var bounds = new google.maps.LatLngBounds();

    //Center map and adjust Zoom based on the position of all markers.
    map.setCenter(latlngbounds.getCenter());
    map.fitBounds(latlngbounds);
}
function reloadMap() {
    let info_Window = new google.maps.InfoWindow();
    info_Window.close();
    setMapOnAll(null);
    for (var i = 0; i < polys.length; i++) {
        polys[i].setMap(null);
    }
    markers.length = 0;
    polys.length = 0;
}   
// function drawPolyline() {
//       let markersPositionArray = [];
//       // obtain latlng of all markers on map
//       markersArray.forEach(function(e) {
//         markersPositionArray.push(e.getPosition());
//       });

//       // check if there is already polyline drawn on map
//       // remove the polyline from map before we draw new one
//       if (polyline !== null) {
//         polyline.setMap(null);
//       }

//       // draw new polyline at markers' position
//       polyline = new google.maps.Polyline({
//         map: map,
//         path: markersPositionArray,
//         strokeOpacity: 0.4
//       });
//     }
	  
function getDist(lat1,lon1,lat2,lon2){
  var R = 6378137; // Radius of the earth in m
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2); 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in m

  // var X = cos(lat2) * sin(4.38101);
  // var Y =  ;
  // var β = atan2(X,Y);

  return {
  	distance : d,
  }
}

function deg2rad(deg) {
  return deg * (Math.PI/180);
} 
function PythagorasEquirectangular(lat1, lon1, lat2, lon2) {
  lat1 = deg2rad(lat1);
  lat2 = deg2rad(lat2);
  lon1 = deg2rad(lon1);
  lon2 = deg2rad(lon2);
  var R = 6371; // km
  var x = (lon2 - lon1) * Math.cos((lat1 + lat2) / 2);
  var y = (lat2 - lat1);
  var d = Math.sqrt(x * x + y * y) * R;
  return d;
}
function NearestPoint(latitude, longitude, data_arr) {
  var minDif = 99999;
  var closest;
  let index = 0;  
  while (index < data_arr.length) {
    var dif = PythagorasEquirectangular(latitude, longitude, data_arr[index].geom.coordinates[1], data_arr[index].geom.coordinates[0]); 
    if (dif < minDif) {
      closest = index;
      minDif = dif;
    }
    index++;
  }   
 return data_arr[closest];
}
function NearestPoints(latitude, longitude, data_arr) {
  var minDif = 99999;
  var closest;
  let index = 0;  
  while (index < data_arr.length) {
    var dif = PythagorasEquirectangular(latitude, longitude, data_arr[index].geom.coordinates[1], data_arr[index].geom.coordinates[0]);
    // console.log('ats_ident:'+data_arr[index].ats_ident+' dif : '+dif);
    data_arr[index].dif = dif;
    if(dif==0){
    	data_arr.splice(index, 1);
    }
    // if (dif < minDif) {
    //   closest = index;
    //   minDif = dif;
    // }
    index++;
  }
  let ret = data_arr.sort(compareValues('dif')); 
 // return data_arr[closest];
 return ret;
}
function compareValues(key, order = 'asc') {
  return function innerSort(a, b) {
    if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) { 
      return 0;
    }

    const varA = (typeof a[key] === 'string')
      ? a[key].toUpperCase() : a[key];
    const varB = (typeof b[key] === 'string')
      ? b[key].toUpperCase() : b[key];

    let comparison = 0;
    if (varA > varB) {
      comparison = 1;
    } else if (varA < varB) {
      comparison = -1;
    }
    return (
      (order === 'desc') ? (comparison * -1) : comparison
    );
  };
}
function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}
$(function() { 
	var previousOption = null;
	$('.airportlist').on('change', function(){
		const selectedOption = $(this).find('option:selected').val();

		$(`[value="${previousOption}"]:disabled`).attr('disabled', false);
		previousOption = null;
		$(`[value="${selectedOption}"]:not(:selected)`).attr('disabled', true);
	})
	$('.airportlist').on('click', function(){
		previousOption = $(this).find('option:selected').val();
	});
	$('#departure').on('change', function(){
		let link = $(this).find('option:selected').text().split(' - ');
		var lbl_dep = document.getElementById('lbl_depart');
			lbl_dep.setAttribute('href', '{{ url("/") }}/get_infoarpt/'+link[0]);
	});
	$('#destination').on('change', function(){
		let link = $(this).find('option:selected').text().split(' - ');
		var lbl_des = document.getElementById('lbl_destiny');
			lbl_des.setAttribute('href', '{{ url("/") }}/get_infoarpt/'+link[0]);
	});

	let avg;

	$('#planningForm').on('submit', function(e) {
		var formdata = $(this).serialize();
		var depart = $('select[name="departure"]').val().split(',');
		
		var destiny = $('select[name="destination"]').val().split(','); 
	 
	    e.preventDefault();
	    $.ajax({
	        type: "POST",
	        url: "{{ url('/') }}/get_route",
	        data: formdata,
	        success: function(response) {
	        	if(response.status=='success'){
	        		$('#vfr_table_body').empty();
	        		
	        		let wp = response.msg.waypoints; 
	        		let _from_lat 	= parseFloat(depart[1]);
					let _from_lon 	= parseFloat(depart[0]);
					let _to_lat 	= parseFloat(destiny[1]);
					let _to_lon 	= parseFloat(destiny[0]);
					let geo = window.geolib;
					let rum = geo.getRhumbLineBearing(
					    { latitude: _from_lat, longitude: _from_lon },
					    { latitude: _to_lat, longitude: _to_lon }
					);
					let rum_ = geo.getRhumbLineBearing(
					    { latitude: _to_lat, longitude: _to_lon },
					    { latitude: _from_lat, longitude: _from_lon }
					);
					
					let up_rum = 10 + rum;
					if(up_rum > 360) up_rum = up_rum - 360;
					let lo_rum = rum - 10;
					if(lo_rum < 0) lo_rum = 360 - (-1 * lo_rum);
					
					// console.log('rum : '+rum);

					let up_rum_ = 10 + rum_;
					if(up_rum_ > 360) up_rum_ = up_rum_ - 360;
					let lo_rum_ = rum_ - 10;
					if(lo_rum_ < 0) lo_rum_ = 360 - (-1 * lo_rum_);
					
					// console.log('rum_ : '+rum_);

					let _dist_start_end = geo.getDistance(
					    { latitude: _from_lat, longitude: _from_lon },
					    { latitude: _to_lat, longitude: _to_lon }
					);
					// insert to table
	        		let group =[];
	 				let a=0;
	 				let _prev_dist;
	 				let _prev2next_dist;
	 				let _curr2next_dist;
	 				let _next_dist;
		            while ( a < wp.length) { 
						 
						let _dist 	 = geo.getDistance(
							{ latitude: _to_lat, longitude: _to_lon },
					    	{ latitude: wp[a].geom.coordinates[1], longitude: wp[a].geom.coordinates[0] }
						); 
						if(a > 0){ // check if have previous point
							_prev_dist 	 = geo.getDistance(
								{ latitude: wp[a-1].geom.coordinates[1], longitude: wp[a-1].geom.coordinates[0] },
						    	{ latitude: wp[a].geom.coordinates[1], longitude: wp[a].geom.coordinates[0] }
							);
							console.log('_prev_dist : '+_prev_dist)
							if(typeof wp[a+1] !== 'undefined') {
							    console.log('have next');
							    _prev2next_dist 	 = geo.getDistance(
									{ latitude: wp[a-1].geom.coordinates[1], longitude: wp[a-1].geom.coordinates[0] },
							    	{ latitude: wp[a+1].geom.coordinates[1], longitude: wp[a+1].geom.coordinates[0] }
								);
								_curr2next_dist = geo.getDistance(
									{ latitude: wp[a].geom.coordinates[1], longitude: wp[a].geom.coordinates[0] },
							    	{ latitude: wp[a+1].geom.coordinates[1], longitude: wp[a+1].geom.coordinates[0] }
								);
								console.log('_next_dist : '+_curr2next_dist)
							}  
						}
						 
						let rumi = geo.getRhumbLineBearing(
						    { latitude: wp[a].geom.coordinates[1], longitude: wp[a].geom.coordinates[0] },
						    { latitude: _to_lat, longitude: _to_lon },
						    
						);
						wp[a].dist_point_to_end = _dist;
						// 23/10/2021 jam 14:25 
						if(rumi >= lo_rum && rumi <= up_rum && _dist < _dist_start_end ){
							if(a > 0){ // check if have previous point
								if( _dist < _prev_dist && _prev2next_dist > _curr2next_dist){
									// console.log('prevtonext :'+prev_dist +'currtonext :'+_next_dist )
									wp[a].rumi_from = rumi;	
								}else{
									wp.splice(a,1);		
								}
							}else{
								wp[a].rumi_from = rumi;	
							}
						}else{
							wp.splice(a,1);
						}
						a++;
					}
					// console.dir('wp'+wp);
					// console.log('wp : '+JSON.stringify(wp,null,4));
					let wps = NearestPoints(_from_lat, _from_lon, wp);
					// console.dir('wps'+wps);
					// console.log('wps : '+JSON.stringify(wps,null,4));
					// sort by ats_ident, dist_to_from
					wps.sort(compareValues('ats_ident'));
					// wps.sort(compareValues('dist_point_to_end'));

					// wps.sort(compareValues('dist_to_from'));
	 				// console.log('wps : '+JSON.stringify(wps,null,4));
	 				
	 				wps.reduce(function(res, value) {
					  if (!res[value.ats_ident]) {
					    res[value.ats_ident] = { ats_ident: value.ats_ident, dist: 0, count: 0 };
					    group.push(res[value.ats_ident])
					  }
					  res[value.ats_ident].dist += parseFloat(value.dist);
					  res[value.ats_ident].count += 1;
					  return res;
					}, {});

	        		// $.each(group, function(i, item) { 
		        		// if(item.count > 1){ 
					        // var $tr = $('<tr>').append(
					        // 	$('<td>').html('<input type="checkbox" name="cek[]">'),
					        //     $('<td>').text(item.ats_ident),
					        //     $('<td>').text(item.dist),
					        //     $('<td>').text(0)
					        // ).appendTo('#vfr_table_body');
					    // } 
				    // });
	        		// $('#vfr_table').show();

	        		// redraw map
	        		reloadMap();

	        		let draw_group = wps.reduce((r, a) => { 
						r[a.ats_ident] = [...r[a.ats_ident] || [], a]; 
						return r;
					}, {});  

					// drawMarker('Departure Airport',dp_latlng,'airport');
					// drawMarker('Destination Airport',ds_latlng,'airport');

		            let groups = [];
		            $.each(wps, function(i, item) {  
		            	groups.push(item);
		            }); 
		            let i=0;
		            let marks= [];
		   //          while ( i < groups.length) {  
					// 	newLatLng = { id:groups[i].ats_ident, name: groups[i].wpt_name,  lat:groups[i].geom.coordinates[1], lng:groups[i].geom.coordinates[0] };
					// 	marks.push(newLatLng);	
					// 	i++;
					// }

					
					// console.log('draw_group : '+JSON.stringify(draw_group));
		            let idx=0; 
		            let curr_idx=0; 
		            let polys= [];
		            avg=0;
					$.each(draw_group, function(i, fitem) { 
						// console.log('i : '+i);
						let cur_route	= '';
						let prev_route	= ''; 
						let cur_dist	= 0;
						let prev_dist	= 0;  
						let _poly= [];
						let newLatLng	= null; 
						let dp_latlng 	= '';
						// console.log('fitem : '+JSON.stringify(fitem,null,4));

						if(fitem.length > 1){ // bila terdiri dari lebih dari 0 wpt
							dp_latlng = { id: 'ORI', name:'[ ' , lat: _from_lat , lng: _from_lon }; 
							_poly.push(dp_latlng); //head 
							marks.push(dp_latlng);
							let fr_wpt;
							let cr_wpt;
							let to_wpt;
							let far = 30;
							

							fitem.forEach(function (item, index, grp){  
								// console.log('grp  : '+JSON.stringify(grp,null,4));
								// console.log('grp['+index+'] : '+JSON.stringify(grp[index],null,4));

							 	to_wpt = grp[index].wpt_name; 
								if(index == 0 || index == fitem.length -1){    
									newLatLng = { id:grp[index].ats_ident, name: grp[index].wpt_name, lat:grp[index].geom.coordinates[1], lng:grp[index].geom.coordinates[0] };  
								}else{   
									// let same_path = ( grp[index-1].point2 == grp[index].point) ? true: false; 
									let same_path = grp.findIndex(obj => obj.point2 == grp[index-1].point2);
									 
									let prev_dist = [grp[index-1].geom.coordinates[1], grp[index-1].geom.coordinates[0], getDist( _from_lat, _from_lon , grp[index-1].geom.coordinates[1], grp[index-1].geom.coordinates[0] )];
									let curr_dist = [grp[index].geom.coordinates[1], grp[index].geom.coordinates[0], getDist( _from_lat, _from_lon , grp[index].geom.coordinates[1], grp[index].geom.coordinates[0] )]; 

									if( same_path > -1 ){ 
										let next_dist = [grp[same_path].geom.coordinates[1], grp[same_path].geom.coordinates[0], getDist( _from_lat, _from_lon , grp[same_path].geom.coordinates[1], grp[same_path].geom.coordinates[0] )]; 
											curr_dist = next_dist;

										if ( prev_dist[2] > curr_dist[2] ) { console.log('same_path but must me rotate'); }
										else{
											// console.log('same_path ');
											newLatLng = { id:grp[index].ats_ident, name: grp[index].wpt_name, lat:grp[index].geom.coordinates[1], lng:grp[index].geom.coordinates[0] }; 
										}
									}else{ 
										// console.log('current wpt_name : '+grp[index].wpt_name ); 
										// console.log('get_next +Route: '+i);
										
										
										var get_next = function (data_arr, fr_wpt, to_wpt){ 
											// console.log('cur_wpto: '+fr_wpt+ ' to_wpto : '+to_wpt );
											let groupi =  NearestPoint(fr_wpt[0],fr_wpt[1], data_arr);
											
											if(parseInt(groupi.length) > 1){ 
												let first =  [groupi[0].geom.coordinates[1], groupi[0].geom.coordinates[0], groupi[0].dist_to_from];
												let next  =  [groupi[1].geom.coordinates[1], groupi[1].geom.coordinates[0], groupi[1].dist_to_from];
											// console.log('groupi[0].dif : '+groupi[0].dif);
											// console.log('far       : '+far);
											// console.log('first[2]  : '+first[2]);
											// console.log('next[2]   : '+next[2]);
											// console.log('to_wpt[2] : '+to_wpt[2]);
											// console.log('groupi.length :' +groupi.length);		
											// console.log('newGroups : '+JSON.stringify(groupi,null,4));
											//&& first[2] < next[2] && next[2] < to_wpt[2] 
													if(groupi[0].dif < far ) {
														newLatLng = { id:groupi[0].ats_ident, name: groupi[0].wpt_name, lat:groupi[0].geom.coordinates[1], lng:groupi[0].geom.coordinates[0] };
														_poly.push(newLatLng);
														marks.push(newLatLng);
														newLatLng = null;
													}  
													// groupi.shift(); 
													// cr_wpt = [groups[i+1].geom.coordinates[1+1], groups[i].geom.coordinates[0], groups[i+1].dist_to_from]; 
													get_next(groupi, first, to_wpt ); 
										 	}else{
										 		newLatLng = { id:groupi[0].ats_ident, name: groupi[0].wpt_name, lat:groupi[0].geom.coordinates[1], lng:groupi[0].geom.coordinates[0] };
										 	}
											// while( i <  groups.length -1) {
											// 	// cr_wpt = [groups[i].geom.coordinates[1], groups[i].geom.coordinates[0], groups[i].dist_to_from]; 
											// 	dif = groups[i].dif;
											// 	if( fr_wpt[2] < cr_wpt[2] && cr_wpt[2] < to_wpt[2] && nextdif > dif ){ 
													// newLatLng = { id:groups[i].ats_ident, name: groups[i].wpt_name, lat:groups[i].geom.coordinates[1], lng:groups[i].geom.coordinates[0] };
													// _poly.push(newLatLng);
													// newLatLng = null;
													// cr_wpt = [groups[i+1].geom.coordinates[1+1], groups[i].geom.coordinates[0], groups[i+1].dist_to_from]; 
													// get_next(groups, cr_wpt, to_wpt );
											// 	}else if( cr_wpt[2] == to_wpt[2] ){ 
											// 		newLatLng = { id:groups[i].ats_ident, name: groups[i].wpt_name, lat:groups[i].geom.coordinates[1], lng:groups[i].geom.coordinates[0] }; 
											// 	}
											// 	nextdif = dif;
											// 	i++;
											// }

										}
										// var uniqueRoute = [];
										// var removeThis=[];
										// $.each(groups, function(a, el){
										//     if ($.inArray(el.wpt_name, uniqueRoute) === -1) uniqueRoute.push(el.wpt_name);
										//     else removeThis = a; 
										// });
										// groups.splice(a,1); // remove duplicate or same waypoint 
										
										get_next(groups, prev_dist, curr_dist ); 
									}	 
							 	}
								if(newLatLng!=null)	{_poly.push(newLatLng);marks.push(newLatLng);}
							}); 
							
							// let ds_latlng = { id:i, lat: _to_lat, lng: _to_lon };
							let ds_latlng = { id:'DST', name:' ]', lat: _to_lat, lng: _to_lon };
							_poly.push(ds_latlng); //tail
							marks.push(ds_latlng);
							// console.log('_poly' +idx+' '+_poly);
							let paths = [];
							let ids = '';
							let points ='';
							for (let i = 0; i < _poly.length; i++) {
								ids += ' '+_poly[i].name;
								points += '[ '+ _poly[i].lat +', '+ _poly[i].lng+' ],';
							  	paths.push ({ latitude : _poly[i].lat, longitude: _poly[i].lng }); 
							}
							points = points.slice(0, -1);
							// console.log('paths' +idx+' '+JSON.stringify(paths,null,4));
							let _dist_bt_points = geo.getPathLength( paths );
							_dist_bt_points = (_dist_bt_points / 1852).toFixed(2);
							let _speed =  _dist_bt_points / $('#speed').val() ;
							avg += _speed; 

							var $tr = $('<tr>').append(
					        	$('<th scope="row">').html('<input type="checkbox" name="cek[]">'),
					            $('<td>').text(ids),
					            $('<td class="hideEl">').text(points),
					            $('<td>').text(_dist_bt_points),
					            
					            $('<td>').text(convertH2M(_speed))
					        ).appendTo('#vfr_table_body');

							// console.log('_dist_bt_points '+_dist_bt_points);
							// console.log('paths '+JSON.stringify(paths,null,4));
							// console.log('_poly' +idx+' '+JSON.stringify(_poly,null,4));
							curr_idx++;
						}
						 

						drawPoly(_poly,'green');
						// 
						// }
						// else{
						// 	let mLatLng = {lat:  item[0].geom.coordinates[1], lng: item[0].geom.coordinates[0]};
						// 	drawMarker(item[0].wpt_name,mLatLng,'red');
						// }
						// console.log('idx : '+idx); 
						idx++;

					}); // end loop draw_group
					$('#vfr_table').show();
					// drawMarkers(marks);
					drawMarkers(marks);
					console.log('avg : ',avg);
					console.log('idx : ',curr_idx);
					avg = cvrtH2M((avg / curr_idx).toFixed(2));
					etd2eta($('#etd').val(),avg);
					
	        	} 
	        },
	        error: function() {
	            alert('Error');
	        }
	    });
	    return false;
	});
});

function etd2eta(etd,addtime){
	let tim = etd.split(":");
	let dec_h = parseFloat(tim[0]); 
	// convert minute to decimal
	let dec_m = (tim[1] / 60).toFixed(2);
	console.log(dec_h);
	console.log(dec_m);
	console.log(addtime/60);
	  
	$('#eta').val( convertH2M(dec_h + parseFloat(dec_m) + parseFloat(addtime/60)) );
}
var infowindow = new google.maps.InfoWindow();
    function drawPoly(path=[], colr='#ff0000', icn=[]){
		var path = path;
	    var icn = icn;
      	var lineSymbol = {
		    path: 'M 0,-1 0,1',
		    strokeOpacity: 1,
		    strokeWeight: 2,
		    scale: 3
		};
		var doubleLine = {
			path: 'M 0.5,-1 0.5,1 M -0.5,-1 -0.5,1',
			strokeOpacity: 1,
			strokeWeight: 1,
			scale: 3
		}; 
		var color = ["#FF0000", "#FF00FF", "#0000FF"];
		var icons = [
		[{
		  icon: lineSymbol,
		  offset: '0%',
		  repeat: '6px'
		}],    [{
		  icon: lineSymbol,
		  offset: '50%',
		  repeat: '15px'
		}],
		[{
		  icon: doubleLine,
		  offset: '0%',
		  repeat: '6px'
		}]
		];
      	const poly = new google.maps.Polyline({
		    path: path,
		    icon:icons[icn],
		    geodesic: true,
		    strokeColor: colr,
		    strokeOpacity: 1.0,
		    strokeWeight: 1,
		});
      	polys.push(poly);
	    poly.setMap(map);
	    poly.addListener('mouseover', function(event) {
          this.setOptions({
            strokeColor: '#000',
			strokeOpacity: 0.6,
			strokeWeight: 3,
          });
        });

        poly.addListener('mouseout', function(event) {
         this.setOptions({
            strokeColor: colr,
			strokeOpacity: 1,
			strokeWeight: 1, 
          });
        }); 
        // console.log($('[name="cek[]"]'));
		// console.log('polys '+ JSON.stringify(polys,null,4));
        var _cek = $('[name="cek[]"]').eq(polys.length-1);
       
        _cek.click(function () {
        	console.log($(this));
		    if ($(this).is(':checked')) {
		        poly.setOptions({
		            strokeColor: '#000',
					strokeOpacity: 0.6,
					strokeWeight: 3,
		        });
		    } else {
		        poly.setOptions({
		            strokeColor: '#ff0000',
					strokeOpacity: 1,
					strokeWeight: 1,
		        });
		    }
		});
	    // google.maps.event.addListener(poly, 'click', function(clickEvent){
     //        //did you want to do something here??
     //        alert('eeee');
     //    }); 
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < poly.getPath().getLength(); i++) {
			bounds.extend(poly.getPath().getAt(i));
		}
		map.fitBounds(bounds);
		 
		google.maps.event.addListener(poly, 'mouseover', function(event) {
			// console.log(event);
			var minDist = Number.MAX_VALUE;
			for (var i = 0; i < this.getPath().getLength(); i++) {
			  var distance = google.maps.geometry.spherical.computeDistanceBetween(event.latLng, this.getPath().getAt(i));
			  if (distance < minDist) {
			    minDist = distance;
			    index = i;
			  }
			  
			}
			infowindow.setContent("<br>Route : " + path[index].id);
			// infowindow.setContent("<br>Route : " + path[index].id + "<br>Distance: "+distance);
			infowindow.setPosition(this.getPath().getAt(index));
			infowindow.open(map); 
		});
	}
	    // for(var i=0; i<plane.Cot.length; i++){
	    //     var loc = plane.Cot[i].toString().split(',', 2);
	    //     console.log('loc:'+loc);  
	    //     path.push(new google.maps.LatLng(loc[0], loc[1])); 
	    //     poly.getPath();      
	    // }
 	// }
google.maps.event.addDomListener(window, "load", initMap);
// table
	function handler() { 
		let idx = 0;
	    return {
	     fields: [],   
	      addNewField() {
	      	  // let rn = $('#route').val();
	      	  // console.log(rn);
	      	  let dep = $('select[name="departure"]').find('option:selected').text().split(" - ");
			  let des = $('select[name="destination"]').find('option:selected').text().split(" - ");
			  $('#depart_').val(dep[0]);
			  $('#destiny_').val(des[0]);

	          this.fields.push({
	              wpt_id:'',
	              wpt: '',
	              lat: '',
	              lng: ''
	           });
	        },
	        removeField(index) {
	           this.fields.splice(index, 1);
	           // markers[index].setMap(null);
	        }, 
	        handleWpt(e, idex) { 
		     	let val = e.target.value;
		     	if(val.length > 5){
		     		Swal.fire(
			            "Typos!", 
			            "Waypoint Name Max 5 characters ("+e.target.value+")", 
			            "warning"
			        );
			        val = val.substring(0, 5);
			        this.fields[idex].wpt = val;
		     	}else{ 
		     		this.fields[idex].wpt_id = 'WPT_'+val+'_'+(idex+1)+'_'+(idex+1);
		     	}
		    },
		    handleLat(e){
		    	console.log(e);
		    }, 
		    loading: false,
			buttonLabel: 'Save',
			message: '',
			submitData() {
				this.buttonLabel = 'Saving...'
				this.loading = true;
				this.message = ''
			    
				fetch('/vfr_planning', {
					method: 'POST',
					headers: { 	'Content-Type': 'application/json', 
								'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content 
							},
					body: JSON.stringify($('#frmRoute').serializeArray())
					// body: JSON.stringify(this.fields)
				})
				.then(() => {
			    	this.message = 'Route sucessfully submitted!'
			    })
				.catch(() => {
					this.message = 'Ooops! Something went wrong!'
				})
				.finally(() => {
					this.loading = false;
					this.buttonLabel = 'Save'
				})
			}
	      }
	 } 

</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript" src="{{ asset ('/js/tableToCsv.js') }}"></script>
<script type="text/javascript">
	$(function (){
		var date = new Date;
		var seconds = date.getSeconds();
		var minutes = date.getMinutes();
		var hour = date.getHours();
		var hm = pad(hour,2) +':'+ pad(minutes,2);
		$('#etd').val(hm);
	});
	function cvrtH2M(timeInHour){
        let to_min = Math.floor(timeInHour * 60); 
        return to_min;
    }
	function convertH2M(timeInHour){
        let to_min = Math.floor(timeInHour * 60); 
        return timeConvert(to_min);
    }
    function timeConvert(n) {
        var num = n;
        var hours = (num / 60);
        var rhours = Math.floor(hours);
        var minutes = (hours - rhours) * 60;
        var rminutes = Math.round(minutes);
        return pad(rhours,2) + ":" + pad(rminutes,2);
    }
    function pad (str, max) {
	  str = str.toString();
	  return str.length < max ? pad("0" + str, max) : str;
	}
	function acronym(str){
		var matches = str.match(/\b(\w)/g); 
		var acronym = matches.join(''); 
		return acronym;
	}
	$('#pdf').on('click',function(){
		// var element = $('#vfr_table_')[0].cloneNode(true);
		var element = $('#vfr_table_')[0];
		// $("#vfr_table_>tr>td.active").removeClass("active");
		// console.dir(element);
  		// element.classList.remove("hideEl");
		 html2canvas(element, { 
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }],
                        pageSize: 'A4',
		                pageOrientation: 'portrait',
		                // pageMargins: [ 5, 5, 5, 5 ],
                    };
                    pdfMake.createPdf(docDefinition).download("vfr_planning.pdf");
                }
            });
	});
	$('#csv').on('click',function(){
		$('#vfr_table_').tableToCsv({
		  filename: 'vfr_planning.csv',
		  separator: ';',
		});
	});
</script>
@endsection