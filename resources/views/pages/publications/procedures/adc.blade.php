@extends('layouts.app')

@section('template_title')
    ADC
@endsection

@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
	<style>
	#map {
		width: 100%;
	}
    svg{
        height: unset!important;
    }
	/* #loader {
		position: absolute;
		left: 50%;
		top: 50%;
		z-index: 1;
		width: 150px;
		height: 150px;
		margin: -75px 0 0 -75px;
		border: 16px solid #f3f3f3;
		border-radius: 50%;
		border-top: 16px solid #3498db;
		width: 120px;
		height: 120px;
		-webkit-animation: spin 2s linear infinite;
		animation: spin 2s linear infinite;
	} */
	.leaflet-label {
    width: 100px;
    line-height: 12px;
    text-align: center;
	}
	.leaflet-label .pct{
	font-size: 20px;
	font-weight: 600;
	}
	/* @-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	} */

/* Add animation to "page content" */
	/* .animate-bottom {
		position: relative;
		-webkit-animation-name: animatebottom;
		-webkit-animation-duration: 1s;
		animation-name: animatebottom;
		animation-duration: 1s
	}

	@-webkit-keyframes animatebottom {
		from { bottom:-100px; opacity:0 } 
		to { bottom:0px; opacity:1 }
	}

	@keyframes animatebottom { 
		from{ bottom:-100px; opacity:0 } 
		to{ bottom:0; opacity:1 }
	} */


</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        
        <div class="row p-4" id="mapid">
            <button onclick="backto()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                                &nbsp;
            <div id="map" class="leaflet-container leaflet-fade-anim leaflet-grab leaflet-touch-drag mt-2" tabindex="0" style="position: relative;">
                <div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(0px, 0px, 0px);">
                    <div class="leaflet-pane leaflet-tile-pane">
                        <!-- <div class="leaflet-layer " style="z-index: 3; opacity: 1;">
                                
                        </div> -->
                    </div>
                </div>
            </div>
            <button onclick="backto()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                                &nbsp;
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet.geodesic"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/leaflet.path.drag@0.0.6/src/Path.Drag.min.js"></script>  -->
<script src="{{ asset ('/template/assets/js/L.Path.Transform.js') }}" type="text/javascript"></script> 
<!-- <script type="text/javascript" src="//raw.githubusercontent.com/w8r/Leaflet.Path.Transform/master/dist/L.Path.Transform.js"></script> -->
<script type="text/javascript">
 var height = window.innerHeight - 20;
    document.getElementById('map').style.height = height+'px' 
var map = L.map('map', {
	center: [0, 120],
	zoom: 5
});

var arpt=@json($airport);cod=@json($cod);tbl=@json($tbl);obs=@json($obst);adc=@json($adc);ps=@json($ps);
var oldgeom='';geompoly='';  geommove='';geomrot='';oldpaper='';oldscale=''; mline=[];listchart=[];
var arp=[];
// console.log(arpt)
// initmap()
// function initmap(){
   
var bm= 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
var map_id = 'OpenStreetMap';
L.tileLayer(bm,
        {
            minZoom: 2,
            id: map_id,
        }
        ).addTo(map);
        viewrwy()
// }

// $("#mapid").hide();



    
 

   
function backto (){
    window.location.href = '/listairport/adc/';
    
}
function viewrwy(){
    arp=arpt[0];
    // console.log(adc)
    adc.forEach(a=>{
        nm = a.layer
				// if ( data.layer == 'apron' || data.layer == 'twy' || data.layer == 'building' || data.layer == 'rwy' || data.layer == 'taxi' || data.layer == 'taxilane' ) {
            if ( a.layer !== 'roads' && a.layer !== 'strip') {
                    
            
                if (a.geom.type !== 'Point'){
                    //  console.log(data.layer)
                    // L.CRS.EPSG4326
                    var cord = reverse(a.geom.coordinates)
                    var clr = arptstyle(a.layer)
                    
                    if (a.geom.type == 'Polygon'){
                        
                        var polygon = L.polygon(cord,clr )
                                        .addTo(map)
                                        .bindPopup(nm)
                                        .bindTooltip(nm)
                                        .openPopup();
                    } else {
                        var polyline = L.polyline(cord, clr)
                                        .addTo(map)
                                        .bindPopup(nm)
                                        .bindTooltip(nm)
                                        .openPopup();
                    }
    
                }
            }
    })

    ps.forEach(pr => {
            // console.log(pr)
            zoom = 5
            var pcord=SetCoordinate(pr.gate_lat,pr.gate_lon)
            var lat =  pcord.Decimal[1];
            var lon = pcord.Decimal[0];
            // console.log(pcord)
            var cordlat=pcord.WGS[1];
            var cordlon=pcord.WGS[0];
            if (pr.no_gate.substr(0,1)=='H'){
                nm ='Heliport ' + pr.no_gate + '<br>' + cordlat + '<br>' + cordlon
            }else{
                // nm ='NR ' + pr.no_gate + '<br> Capacity = ' + pr.aircraft_type + '<br>' + cordlat[2] + '<br>' + cordlon[2];
                nm ='NR ' + pr.no_gate + '<br> Apron = ' + pr.name + '<br> Capacity = ' + pr.aircraft_type + '<br>' + cordlat + '<br>' + cordlon;
            }
        
        
        Getsymbol('3','arpt')
        var myIcon = L.icon({
            iconUrl: img,
            iconSize: [20,20],
        })
        
        var marker = L.marker([lat,lon],{icon: myIcon})
            .addTo(map)
            .bindPopup(nm)
            .bindTooltip(nm)
            .openPopup()
    })

    var lat = '';
        var lon = '';
        var nm ='';
    
        zoom = 5
        var a_cord=SetCoordinatebyGeom(arp.geom)
        // console.log(a_cord)
        
        // var cordlat=ToWgs(arp.geom.coordinates[1],'LAT')
        // var cordlon=ToWgs(arp.geom.coordinates[0],'LON')
            // console.log(cordlat)
            // nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
        nm = arp.icao + ' - ' + arp.city_name + ' / ' + arp.arpt_name + ' <br>' + a_cord.WGSSIDSTAR[1] + '<br>' + a_cord.WGSSIDSTAR[0]
        lat =  arp.geom.coordinates[1]
        lon = arp.geom.coordinates[0]
        Getsymbol(arp.type,'arpt')
					
	
			var myIcon = L.icon({
							iconUrl: img,
							iconSize: [25,25],
						})
						
			var marker = L.marker([lat,lon],{icon: myIcon})
					.addTo(map)
					.bindPopup(nm)
                    .bindTooltip(nm)
					.openPopup()

			map.setView([lat,lon],15)

    arp.runwaystemp.forEach(r=>{
        // console.log(r,'RWY')
        proctext = 'RWY ' + r.rwy_ident;
        thrl_x=r.physicals[0].geom.coordinates[0]; thrl_y=r.physicals[0].geom.coordinates[1];
        thrh_x=r.physicals[1].geom.coordinates[0]; thrh_y=r.physicals[1].geom.coordinates[1];

        mline=[];
        
        mline.push([thrl_y,thrl_x]);
        mline.push([thrh_y,thrh_x]);
    

	// console.log(geompoly,'AASLI')
            var clr= {
                color: 'black',
                weight: 5,
                opacity: 1,
                smoothFactor: 1,
				draggable: true,
				transform: true,
				fillOpacity: 0,
			};

            // var markers = L.layerGroup();
            // markers.clearLayers();
          
            var polygon = L.polyline(mline,clr)
			.addTo(map)
			.bindPopup(proctext)
			.bindTooltip(proctext)
			// map.fitBounds(polygon.getBounds());
            polygon.addTo(map);
    })
   
   
			// polygon.bindTooltip(proctext,
			// 		{permanent: true, direction:"center"}
			// 		).openTooltip()
	
          
    
}



function isback(){
    window.location.href = '/listairport/aoc';
}




</script>
@endsection