<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', App::getLocale()) }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Show Map</title>
	
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
	<style>
	#map {
		width: 100%;
	}
	#loader {
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
	}
	.leaflet-label {
    width: 100px;
    line-height: 12px;
    text-align: center;
	}
	.leaflet-label .pct{
	font-size: 20px;
	font-weight: 600;
	}
	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}

/* Add animation to "page content" */
	.animate-bottom {
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
	}

	#myDiv {
	display: none;
	text-align: center;
	}
</style>
<!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
</head>
<body onload="myFunction()" style="margin:0;">

	<div id="loader"></div>
	
	<div style="display:none;" id="myDiv" class="animate-bottom">
	  <!-- <h2>Tada!</h2>
	  <p>Some text in my newly loaded page..</p> -->
	</div>
	<div id="latitude" id="latitude" style="display: none;">
	
	</div>
	<div id="longitude" style="display: none;">
		<input id="name"  name="name" value="name"/>
	</div>
	<div id="map" class="leaflet-container leaflet-fade-anim leaflet-grab leaflet-touch-drag" tabindex="0" style="position: relative;">
		<div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(0px, 0px, 0px);">
			<div class="leaflet-pane leaflet-tile-pane">
				<div class="leaflet-layer " style="z-index: 3; opacity: 1;">
						
				</div>
			</div>
		</div>
	</div>
				
		

	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/leaflet.geodesic"></script>
	<script src="https://cdn.jsdelivr.net/npm/leaflet.path.drag@0.0.6/src/Path.Drag.min.js"></script>
	<script>
		var _freshenUrlAfter = ['googleAnalytics', 'pardot', 'hubspot'];
		</script>
		<script src="//fast.wistia.net/labs/fresh-url/v1.js" async></script>
		<script>
			var myVar;
			
			function myFunction() {
				myVar = setTimeout(showPage, 3000);
			}
			
			function showPage() {
				document.getElementById("loader").style.display = "none";
				document.getElementById("myDiv").style.display = "block";
			}
            </script>
</body>
</html>
	<script>

		<?php
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			
			if (strpos($_SERVER['HTTP_HOST'], 'iwish.dephub.go.id') !== false) {
			    $protocol ='https://';
			}

			$url = $protocol . $_SERVER['HTTP_HOST'];
		?>

		var height = window.innerHeight ;
		// var baseurl= API_URL;
		// var baseurl='https://iwish.dephub.go.id/api/'
		var host = "<?php echo $url ?>";
		var baseurl= host+'/api/';
		// var img='/image/'
		
		document.getElementById('map').style.height = height+'px'


var map = L.map('map', {
	center: [0, 120],
	zoom: 5
});
var bm= 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
var map_id = 'OpenStreetMap';
// var basemaps = {
// 	OpenStreetMap: L.tileLayer.wms('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
// 		layers: 'OpenStreetMap'
// 	}),

// 	Places: L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
// 		layers: 'TOPO-OSM-WMS'
// 	}),

// 	'SRTM30-Colored-Hillshade': L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
// 		layers: 'SRTM30-Colored-Hillshade'
// 	}),

// 	'Places, then topography': L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
// 		layers: 'OSM-Overlay-WMS,TOPO-WMS'
// 	})
// };
        L.tileLayer(bm,
        {
            minZoom: 2,
            id: map_id,
        }
        ).addTo(map);
// L.control.layers(basemaps, {}, {collapsed: false}).addTo(map);

// basemaps.OpenStreetMap.addTo(map);


// earth = new WE.map('earth_div');
// WE.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//           attribution: 'OSM'
// }).addTo(earth);
	// 	var map = L.map('map', {
	// 			minZoom: 3,
	// 			maxZoom: 100,
	// 			}).setView([0,120],5)
	// 			L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
    // layers: 'SRTM30-Colored-Hillshade'
	// 		// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	// 		// 		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	// 		}).addTo(map);
		

			var table = findGetParameter('table');
			var tableid =findGetParameter('id')
			var cord =findGetParameter('point')
			// var latitude= findGetParameter('lat');
			// var longitude= findGetParameter('lon');
			// var latitude1= findGetParameter('lat1');
			// var longitude1= findGetParameter('lon1');
			// var latitude2= findGetParameter('lat2');
			// var longitude2= findGetParameter('lon2');
			findGetParameter()

		var url=''
		function findGetParameter(parameterName) {
			var result = null,
				tmp = [];
			location.search
				.substr(1)
				.split("&")
				.forEach(function (item) {
				tmp = item.split("=");
				// console.log(item)
				if (tmp[0] === 'pointnewlat'){
					pointnewlat=decodeURIComponent(tmp[1])
				}
				if (tmp[0] === 'pointnewlon'){
					pointnewlon=decodeURIComponent(tmp[1])
					
				}
				if (tmp[0] === 'pointlat'){
					pointlat=decodeURIComponent(tmp[1])
				}
				if (tmp[0] === 'pointlon'){
					pointlon=decodeURIComponent(tmp[1])
					PlotairspaceAffect(pointlat,pointlon,pointnewlat,pointnewlon)
				}
				if (tmp[0] === 'lat'){
					lat=decodeURIComponent(tmp[1])
				}
				if (tmp[0] === 'lon'){
					lon=decodeURIComponent(tmp[1])
					Plotpoint(lat,lon)
				}
				if (tmp[0] === 'lat1'){
					lat1=decodeURIComponent(tmp[1])
				}
				if (tmp[0] === 'lon1'){
					lon1=decodeURIComponent(tmp[1])
					
				}
				if (tmp[0] === 'lat2'){
					lat2=decodeURIComponent(tmp[1])
				}
				if (tmp[0] === 'lon2'){
					lon2=decodeURIComponent(tmp[1])
					plotline(lat1,lon1,lat2,lon2)
				}
				if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
				
				});
			return result;
		}
		
		// if (latitude !== ''){
		// 	Plotpoint(latitude,longitude)
		// }



		switch (table) {
			
			case 'chart':
				showchart(tableid,'')
                break;
			case 'newframe':
				newframechart(tableid,'')
                break;
			case 'fpl':
				fligtplan(tableid)
                break;
            case '_fpl':
            	_fligtplan()
            	break;
			case 'frame':
				framechart(tableid,'')
                break;
			case 'msaarea':
				var ddid=tableid.split('@');
				Msa(ddid[0],ddid[1],'byarea')
                break;
			case 'msa':
				Msa(tableid,'','all')
                break;
			case 'holding':
				holding(tableid)
                break;
			case 'trans':
				ArptTrans(tableid,'')
                break;
			case 'proc':
				ArptProc(tableid,'')
                break
            case 'navaid':
				DrawNavaid(tableid,'')
				break;
			case 'waypoint':
				DrawNavaid(tableid,'')
				break;
			case 'arpt':
				Airport(tableid)
				break;
			case 'parkingstand':
				Parkingstand(tableid)
				break;
			case 'twy':
				Parkingstand(tableid)
				break;
			case 'apron':
				Parkingstand(tableid)
				break;
			case 'obstacle':
				
				Obstacle(tableid)
				break;
			case 'ils':
				DrawNavaid(tableid,'')
				// url= baseurl + 'ils?ils_id=' + tableid
				break;
			case 'rwy':
				url= baseurl + 'navaid/list?nav_id=' + tableid
				break;
			case 'atsaffect':
				AirwaysAffect(tableid)
				break;
			case 'atslist':
				Airways(tableid,table)
				break;
			case 'atsseg':
				Airways(tableid,table)
				break;
			case 'airspace':
				Airspace(tableid,table)
				break;
			case 'airspacepoint':
				Airspace(tableid,table,true)
				// this.crd=cord.split(',')
				// Plotpoint(this.crd[0],this.crd[1])
				break;
			case 'asparpt':
				AspArpt(tableid,'arpt')
				break;
			case 'aspall':
				AspArpt(tableid,'airspace')
				break;
			case 'asplist':
				AspList()
				break;
			case 'suas':
				Airspace(tableid,table)
				break;
			case 'suasall':
				AspArpt(tableid,'suas')
				break;
		}
		
// 		const geodesicLine = L.geodesic().addTo(map);   // lower-case, w/o new-keyword
// const geodesicCircle = L.geodesiccircle().addTo(map);   // lower-case, w/o new-keyword
// Make sure you add the geodesic-object to the map. It won't display otherwise.

// Each constructor is defined as:

// Geodesic(latlngs?: L.LatLngExpression[] | L.LatLngExpression[][], options?: GeodesicOptions)
// GeodesicCircle(center?: L.LatLngExpression, options?: GeodesicOptions)
	function PlotairspaceAffect(plat,plon){
		var sql = 'airspace/list/temp/seg?point1_lat=' + plat + '&point1_long=' + plon;
		var xhttp = new XMLHttpRequest()
		xhttp.open("GET", baseurl + sql ,false)
		xhttp.send()
			var asp= JSON.parse(xhttp.responseText)
			asp.data.forEach(data => {
				Airspace(data.asp_id,'airspace')
			})
	}


	function DrawNavaid(navid,type='',alt='') {
		this.url=''
		this.tbl=''
		this.gs=false
		if (navid.substr(0,3) =='WPT'){
			this.tbl='waypoint'
			this.url = baseurl + 'waypoint/temp/list?wpt_id=' + navid
		}else if (navid.substr(0,3) =='NAV'){
			this.url = baseurl + 'navaid/temp/list?nav_id=' + navid
			this.tbl='navaid'
		}else if (navid.substr(0,3) =='ILS'){
			this.url = baseurl + 'ils/temp?ils_id=' + navid
			this.gs=true
			// console.log(this.url)
			this.tbl='ils' // di set hanya utk mengambil symbol saja utk ILS ambil symbol arpt military (bulat)
		}else if (navid.substr(0,3) =='RWY'){
			this.url = baseurl + 'rwy/thr/temp?rwy_key=' + navid
			type='5';
			this.tbl='rwy' // di set hanya utk mengambil symbol saja utk ILS ambil symbol arpt military (bulat)
		}else if (navid.substr(0,3) =='MRK'){
			this.url = baseurl + 'ils/temp/marker?mrkr_id=' + navid
			type='1';
			this.tbl='marker' // di set hanya utk mengambil symbol saja utk ILS ambil symbol arpt military (bulat)
		}else {
			this.url = baseurl + 'airports?arpt_ident=' + navid
			type='1';
			this.tbl='arpt' // di set hanya utk mengambil symbol saja utk ILS ambil symbol arpt military (bulat)
		}

		// console.log('NAVAID FUNCTION ',navid,this.url)
		var nmgs=''
		var pane = map.createPane('myPane');
		var marker = L.marker({pane: 'myPane'});
		pane.style.zIndex = 10;
		var xnav = new XMLHttpRequest()
		xnav.open("GET", this.url ,false)
		xnav.send()
		var hasil= JSON.parse(xnav.responseText)
			hasil.data.forEach(navaid => {
				var cordlat=ToWgs( navaid.geom.coordinates[1],'LAT')
				var cordlon=ToWgs(navaid.geom.coordinates[0],'LON')
							// console.log(cordlat)
							// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
				if (this.tbl == 'navaid'){
					var freq =FreqFormat(navaid.freq,navaid.type)
					nmt = navaid.nav_ident + ' ' + navaid.definition + '<br>' + alt
					nm = '<p class="text-center">' + navaid.nav_name + '<br><b>' + navaid.definition + ' ' + freq + '<br>' + navaid.nav_ident + '</b><br>' + cordlat[2] + '<br>' + cordlon[2] + '</p>'
				}else if (this.tbl == 'ils'){
					nmt =navaid.ils_ident + '<br>' + alt
                    nm = '<br>' + navaid.ils_ident + '</br>for RWY ' + navaid.rwy_ident + '<br>' + cordlat[2] + '<br>' + cordlon[2] 
                }else if (this.tbl == 'rwy'){
                    this.tbl='waypoint';
					nmt ='RWY ' + navaid.rwy_ident + '<br>' + alt
                    nm = '<b>' + navaid.rwy_ident + '</b><br>' + cordlat[2] + '<br>' + cordlon[2] 
                }else if (this.tbl == 'marker'){
                    this.tbl='waypoint';
					nmt = navaid.mrkr_type + '<br>' + alt
					nm = '<b>' + navaid.mrkr_type + '</b><br>' + cordlat[2] + '<br>' + cordlon[2] 
				}else if (this.tbl == 'waypoint'){
                    this.tbl='waypoint';
					nmt = navaid.desc_name + '<br>' + alt
					nm = '<b>' + navaid.desc_name + '</b><br>' + cordlat[2] + '<br>' + cordlon[2] 
				}else{
					nmt =navaid.icao + '<br>' + alt
					nm = '<b>' + navaid.icao + '</b><br>' + cordlat[2] + '<br>' + cordlon[2] 
				}

				
				this.typ=''
				// console.log('TYPE ',type)
				if (type ==''){
					if (this.tbl == 'arpt'){
					this.typ='3'
					}else{
						this.typ=navaid.type
					}
				}else{
					this.typ=type
				}
				var lat,lon,symb= null
				lat =  navaid.geom.coordinates[1]
				lon = navaid.geom.coordinates[0]
				symb = Getsymbol(this.typ,this.tbl)
				// console.log(symb)
				var myIcon = L.icon({
							iconUrl: symb,
							iconSize: [25,25],
						})
				marker = L.marker([lat,lon],{icon: myIcon})
					.addTo(map)
					map.setView([lat,lon],10)
					marker.bindTooltip(nmt)
					marker.bindPopup(nm)


				if (this.gs == true){
					var cordlat=ToWgs(navaid.gs_geom.coordinates[1],'LAT')
					var cordlon=ToWgs(navaid.gs_geom.coordinates[0],'LON')
								// console.log(cordlat)
								// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
					nmgs= 'GS ' + navaid.gs_freq + '<br>' + cordlat[2] + '<br>' + cordlon[2] 
					var lat,lon,symb= null
					lat =  navaid.gs_geom.coordinates[1]
					lon = navaid.gs_geom.coordinates[0]
					symb = Getsymbol(this.typ,this.tbl)
					var gs = L.marker([lat,lon],{icon: myIcon})
						.addTo(map)
						.bindPopup(nmgs)
						.openPopup()
						map.setView([lat,lon],10)
				}
			})
			// marker.openTooltip()
			// marker.openPopup()

	}

	function plotline(lat1,lon1,lat2,lon2){
		var point1=[parseFloat(lat1),parseFloat(lon1)]
		var point2=[parseFloat(lat2),parseFloat(lon2)]
				// console.log(lat1,lon1,lat2,lon2,typeof lon1)
			const geodesic = new L.Geodesic([point1,point2],{color:'black'}).addTo(map);

			var bounds = new L.LatLngBounds(point1,point2);
			map.fitBounds(bounds);
			
		// map.fitBounds(this.pnt1,this.pnt2);
		// map.bounds(this.pnt1,this.pnt2);
	}

	function Plotpoint(lat,lon) {
		var cordlat=ToWgs(lat,'LAT')
		var cordlon=ToWgs(lon,'LON')
							// console.log(cordlat)
							// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
		var marker = L.marker([lat,lon])
					.addTo(map)
					.bindPopup('Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] )
					.openPopup()
					map.setView([lat,lon],14)

	}
	function AspArpt(tableid,tablename){
		var xhttp = new XMLHttpRequest();
		var url,tbl;
		if (tablename=='arpt'){
			tbl='airspace'
			url='airspace/temp/list?arpt_ident=' + tableid
		}else if (tablename=='airspace') {
			tbl='airspace'
			url='airspace/temp/list?ctry=ID&airspace_type=' + tableid
		}else if (tablename=='suas') {
			tbl='suas'
			url='suas/temp/list?ctry=ID&suas_type=' + tableid
		}
				xhttp.open("GET", baseurl + url ,false)
				xhttp.send()
				var rwy= JSON.parse(xhttp.responseText)
				rwy.data.forEach(data => {
					if (tbl=='airspace'){

						this.Airspace(data.ats_airspace_id,tbl)
					}else{
						this.Airspace(data.suas_id,tbl)

					}
				})
    }
    function holding(tableid,drawpoint=true){
		// console.log(tableid);
		var xhttp = new XMLHttpRequest();
		var url,tbl,procname,proctext,lat1,lon1,rttypes,rtcharttype,subchart;
        var mline=[];pointsproc=[];
			url='holding/temp?id=' + tableid;
		
				xhttp.open("GET", baseurl + url ,false)
				// xhttp.onreadystatechange = function (oEvent) {
				// 	if (xhttp.readyState === 4) {
				// 		console.log(xhttp.responseText)
				// 		if (xhttp.status === 200) {
				// 			console.log(xhttp.responseText)
							xhttp.send()
							// console.log(xhttp.responseText,'HOLDIIIIIIIggggggggggggIG');
							var proc= JSON.parse(xhttp.responseText)
							// console.log(proc,'HOLDIIIIIIIIG');
							var crs =proc.data[0].crs/10 + '°';
							var lat='';lon='';
							if (proc.data[0].min_alt == null){
								procname=proc.data[0].max_alt + "'";
							}else{

								procname=proc.data[0].min_alt + "' - " + proc.data[0].max_alt + "'";
							}
							if (proc.data[0].navaid.length >0){
								proctext=proc.data[0].navaid[0].nav_ident +' '+proc.data[0].navaid[0].definition+ '<br>Inbound : ' + crs + '<br>Alt : ' + procname + '<br>Leg : ' + proc.data[0].leg_time/10 + ' Min';
								lat=proc.data[0].navaid[0].geom.coordinates[1];
								lon=proc.data[0].navaid[0].geom.coordinates[0];
								if (drawpoint==true){

									DrawNavaid(proc.data[0].fix_id,'',proc.data[0].max_alt)
								}
								
							}else{
								proctext=proc.data[0].waypoint[0].desc_name + '<br>Inbound : ' + crs + '<br>Alt : ' + procname + '<br>Leg : ' + proc.data[0].leg_time/10 + ' Min';
							
								var wptsym='1';
								if (proc.data[0].mag=='N'){
									wptsym='5';
								}
								if (drawpoint==true){
									DrawNavaid(proc.data[0].fix_id,wptsym,proc.data[0].max_alt)
								}
								
								lat=proc.data[0].waypoint[0].geom.coordinates[1];
								lon=proc.data[0].waypoint[0].geom.coordinates[0];
							}
				

				
							// var hasil= createholding(lat,lon,proc.data[0].crs/10,proc.data[0].turn);
							mline=[];
							var gm=proc.data[0].poly;
							if (gm !== null){
								
								var crd=gm.coordinates[0];
								for (let i=0;i<crd.length;i++){
									lat1=crd[i][1];
									lon1=crd[i][0];
									mline.push([lat1,lon1]);
				
								}
								var clr;
									clr= {
										weight: 3,
										opacity: 1,
										color: 'black',
										fillOpacity: 0,
									}
								
								// console.log(mline);
								var polyline = L.polygon(mline,clr)
								.addTo(map)
								.bindPopup(proctext)
								.bindTooltip(procname)
								map.fitBounds(polyline.getBounds());
								polyline.bindTooltip(procname,
											{permanent: true, direction:"center"}
											).openTooltip()
							}
			// 		} else {
			// 			console.log("Error", xhttp.statusText);
			// 		}
			// 	}
			// };

				

                    
                    // console.log('APP',tr.trans[0].rt_type);
                    // pointsproc.forEach(a=>{
                    //     if (a.substr(0,3)=='WPT'){
                    //         DrawNavaid(a,'1')
                    //     }else{
                    //         DrawNavaid(a,'')
                    //     }// co
                    // })
                    // }
                // plotline()
					
                // })
                
	}

	function ArptTrans(tableid,tablename){
		var ttb=tableid.split("@");
		// console.log(ttb,tableid)
		var xhttp = new XMLHttpRequest();
		var url,tbl,procname,proctext,lat1,lon1,rttypes,rtcharttype,subchart;
        var mline=[];pointsproc=[];
			url='transition/temp?proc_id=' + ttb[0] + '&rt_type='+ttb[1];
		
				xhttp.open("GET", baseurl + url ,false)
				xhttp.send()
                var proc= JSON.parse(xhttp.responseText)
                // console.log(proc.data[0]);
				
                proctext= proc.data[0].trans_ident + ' ' + proc.data[0].airport[0].icao + ' ' + proc.data[0].airport[0].arpt_name + ' ' +  proc.data[0].definition;
                procname=proc.data[0].trans_ident + ' Transition RWY ' + proc.data[0].rwy_trans;
                // console.log(proc.data[0].procseg[0].trans[0],'proc.data[0].procseg[0]');
                rtcharttype=proc.data[0].rt_type;
				// proc.data[0].forEach(tr => {
					
					var trpnt=proc.data[0].segment;
                    rttypes=proc.data[0].rt_type;
                    subchart=proc.data[0].sub_chart_type;
                    mline=[];
                    var gm=proc.data[0].geom;
					if (gm !== null){
						
						var crd=gm.coordinates[0];
						for (let i=0;i<crd.length;i++){
							lat1=crd[i][1];
							lon1=crd[i][0];
							mline.push([lat1,lon1]);
		
						}
					}
                    for (let i=0;i<trpnt.length;i++){
                        if (trpnt[i].fix_id=='' || trpnt[i].fix_id==null){

                        }else{
							if (trpnt[i].center_fix !== null){
								if (trpnt[i].path_term == 'RF' || trpnt[i].path_term == 'AF'){
									if (trpnt[i].path_term == 'AF' && trpnt[i].wd4=='H' ){
										// console.log(trpnt[i].wd4,'wd4')
										holding(trpnt[i].center_fix,false)
									}

								}else{
									// if (trpnt[i].wd4 == 'H' || trpnt[i].wd4 == 'C'){
										// console.log(trpnt[i].center_fix,'trpnt[i].center_fix')
										holding(trpnt[i].center_fix,false)
									// }

								}
							}
                            var altpnt;
                            var altdesc=trpnt[i].alt_desc;
                            switch(altdesc){
                                case "+":
                                    altpnt = 'At or above ' + trpnt[i].alt1;
                                    break;
                                case "-":
                                    altpnt = 'At or below ' + trpnt[i].alt1;
                                    break;
                                case "@":
                                    altpnt = 'At ' + trpnt[i].alt1;
                                    break;
                                default:
                                    altpnt = trpnt[i].alt1;
                                    break;

                            }
                                if (altpnt==null){
                                    altpnt='';
                                }
                                // console.log('rtcharttype',rtcharttype,rttypes,subchart)
                                if (trpnt[i].fix_id.substr(0,3)=='WPT'){
                                    var wptsym='1';
                                    if (rttypes=='R' || rtcharttype=='R' || subchart=='462' || subchart=='472'){
                                        wptsym='5';
                                    }
                                        DrawNavaid(trpnt[i].fix_id,wptsym,altpnt)
                                    
                                }else{
                                    DrawNavaid(trpnt[i].fix_id,'',altpnt)
                                }//
                            
                            // pointsproc.push(trpnt[i].fix_id);
                        }
                        if (trpnt[i].recd_nav=='' || trpnt[i].recd_nav==null){

                        }else{
                            DrawNavaid(trpnt[i].recd_nav,'')
                        }
    
                    }
                    // console.log('APP',tr.trans[0].rt_type);
                    var clr;
                    if (rttypes=='Z'){
                        clr= {
                            weight: 4,
                            opacity: 0.5,
                            color: '#732924',
                            dashArray: '5 5',
                        };
                    }else{
                        clr= {
                            weight: 3,
                            opacity: 1,
                            color: 'black',
                        }
                    }
                    // console.log(mline);
                    var polyline = L.polyline(mline,clr)
                    .addTo(map)
                    .bindPopup(proctext)
                    .bindTooltip(procname)
                    map.fitBounds(polyline.getBounds());
                    // pointsproc.forEach(a=>{
                    //     if (a.substr(0,3)=='WPT'){
                    //         DrawNavaid(a,'1')
                    //     }else{
                    //         DrawNavaid(a,'')
                    //     }// co
                    // })
                    // }
                // plotline()
					
                // })
                
	}

	function showchart(tableid,tablename){
		var xhttp = new XMLHttpRequest();
		var url,tbl,procname,proctext,lat1,lon1,rttypes,rtcharttype,subchart;
        var mline=[];pointsproc=[];
			url='proc/chart?id=' + tableid
		
				xhttp.open("GET", baseurl + url ,false)
				xhttp.send()
                var proc= JSON.parse(xhttp.responseText)
                console.log(proc.data[0]);
				if (proc.data[0].basemap.length > 0){
					framechart(proc.data[0].basemap[0].id,'',2);
				}
				switch (proc.data[0].chart_type) {
					case '10':
						table='arpt';
						Airport(proc.data[0].chart_arpt_ident)
						break;
				
					default:
					if (proc.data[0].procedure.length > 0){
						proc.data[0].procedure.forEach(p=>{
						ArptProc(p.proc_id)
					})
					// framechart(proc.data[0].bm_id,'');
				}
						break;
				}
				
               
                
	}

	function ArptProc(tableid,tablename){
		var xhttp = new XMLHttpRequest();
		var url,tbl,procname,proctext,lat1,lon1,rttypes,rtcharttype,subchart;
        var mline=[];pointsproc=[];
			url='proc?proc_id=' + tableid
		
				xhttp.open("GET", baseurl + url ,false)
				xhttp.send()
                var proc= JSON.parse(xhttp.responseText)
                // console.log(proc.data[0]);
                procname= proc.data[0].proc_name + ' ' + proc.data[0].airport[0].icao + ' ' + proc.data[0].airport[0].arpt_name + ' ' +  proc.data[0].chart[0].definition + ' Procedure';
                proctext=proc.data[0].proc_text;
                // console.log(proc.data[0].procseg[0].trans[0],'proc.data[0].procseg[0]');
                rtcharttype=proc.data[0].segment[0].transition[0].rt_type;
				proc.data[0].segment.forEach(tr => {
					
					if (tr.transition.length > 0){

						var gm=tr.transition[0].geom;
						var trpnt=tr.transition[0].segment;
						var crd=gm.coordinates[0];
						rttypes=tr.transition[0].rt_type;
						subchart=tr.transition[0].sub_chart_type;
						mline=[];
						for (let i=0;i<crd.length;i++){
							lat1=crd[i][1];
							lon1=crd[i][0];
							mline.push([lat1,lon1]);
		
						}
						for (let i=0;i<trpnt.length;i++){
							if (trpnt[i].fix_id=='' || trpnt[i].fix_id==null){
	
							}else{
								if (trpnt[i].center_fix !== null){
									if (trpnt[i].path_term=='RF' || trpnt[i].path_term=='AF' ){
										if (trpnt[i].path_term == 'AF' && trpnt[i].wd4=='H' ){
											// console.log(trpnt[i].wd4,'wd4')
											holding(trpnt[i].center_fix,false)
										}
									}else{
										// console.log(trpnt[i].center_fix,'trpnt[i].center_fix')
										holding(trpnt[i].center_fix,false)
									}
								}
								var altpnt;
								var altdesc=trpnt[i].alt_desc;
								switch(altdesc){
									case "+":
										altpnt = 'At or above ' + trpnt[i].alt1;
										break;
									case "-":
										altpnt = 'At or below ' + trpnt[i].alt1;
										break;
									case "@":
										altpnt = 'At ' + trpnt[i].alt1;
										break;
									default:
										altpnt = trpnt[i].alt1;
										break;
	
								}
									if (altpnt==null){
										altpnt='';
									}
									// console.log('rtcharttype',rtcharttype,rttypes,subchart)
									if (trpnt[i].fix_id.substr(0,3)=='WPT'){
										var wptsym='1';
										if (rttypes=='R' || rtcharttype=='R' || subchart=='462' || subchart=='472'){
											wptsym='5';
										}
											DrawNavaid(trpnt[i].fix_id,wptsym,altpnt)
										
									}else{
										DrawNavaid(trpnt[i].fix_id,'',altpnt)
									}//
								
								// pointsproc.push(trpnt[i].fix_id);
							}
							if (trpnt[i].recd_nav=='' || trpnt[i].recd_nav==null){
	
							}else{
								DrawNavaid(trpnt[i].recd_nav,'')
							}
		
						}
						// console.log('APP',tr.trans[0].rt_type);
						var clr;
						if (rttypes=='Z'){
							clr= {
								weight: 4,
								opacity: 0.5,
								color: '#732924',
								dashArray: '5 5',
							};
						}else{
							clr= {
								weight: 3,
								opacity: 1,
								color: 'black',
							}
						}
						// console.log(mline);
						var polyline = L.polyline(mline,clr)
						.addTo(map)
						.bindPopup(proctext)
						.bindTooltip(procname)
						map.fitBounds(polyline.getBounds());
					}
                    // pointsproc.forEach(a=>{
                    //     if (a.substr(0,3)=='WPT'){
                    //         DrawNavaid(a,'1')
                    //     }else{
                    //         DrawNavaid(a,'')
                    //     }// co
                    // })
                    // }
                // plotline()
					
                })
                
	}
	function newframechart(id){
		var xx=id.split('@');

		url= baseurl + 'airports?arpt_ident=' + xx[0]
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
				var lat = '';
				var lon = '';
				var nm ='';
				var hasil= JSON.parse(xhttp.responseText)
					hasil.data.forEach(navaid => {
						// console.log(navaid)
						zoom = 5
						var cordlat=ToWgs(navaid.geom.coordinates[1],'LAT')
						var cordlon=ToWgs(navaid.geom.coordinates[0],'LON')
							// console.log(cordlat)
							// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
						proctext = navaid.icao + ' - ' + navaid.city_name + ' / ' + navaid.arpt_name + ' <br> Scale 1 : ' + xx[3];
						lat =  navaid.geom.coordinates[1]
						lon = navaid.geom.coordinates[0]
						DimX=Number(xx[1]);DimY=Number(xx[2]);
						pMinX = lon
						pMaxY = lat
						pMaxX = pMinX + DimX
						pMinY = pMaxY - DimY
						mline=[];
		
						mline.push([pMaxY,pMinX]);
						mline.push([pMaxY,pMaxX]);
						mline.push([pMinY,pMaxX]);
						mline.push([pMinY,pMinX]);
						mline.push([pMaxY,pMinX]);
	
		var clr;
	console.log(mline)
			clr= {
				weight: 2,
				opacity: 1,
				draggable: true,
				transform: true,
				color: 'blue',
				smoothFactor: 1,
				fillOpacity: 0,
			};

	
			// console.log(proctext);
			// var polygon = L.polygon(cord,clr)
			var polyline = L.polygon(mline,clr)
			.addTo(map)
			.bindPopup(proctext)
			.bindTooltip(proctext)
			map.fitBounds(polyline.getBounds());
			polyline.bindTooltip(proctext,
					{permanent: true, direction:"center"}
					).openTooltip()
	
	
			polyline.on('dragend', function(e) {
				console.log(e.target._latlngs[0][0]);
			});
			// polyline.transform.enable();
// or partially:
			// polyline.transform.enable({rotation: true, scaling: false});
			// // or, on an already enabled handler:
			// polyline.transform.setOptions({rotation: true, scaling: false});
	
						// this.responseText;
					})
	
			
	}
	function framechart(tableid,tablename,weight=1){
		var xhttp = new XMLHttpRequest();
		var url,tbl,procname,proctext,lat1,lon1,rttypes,rtcharttype,subchart;
        var mline=[];pointsproc=[];
			url='frame/chart?id=' + tableid
		
				xhttp.open("GET", baseurl + url ,false)
				xhttp.send()
                var proc= JSON.parse(xhttp.responseText)
				// console.log(proc)
					proc.data.forEach(tr => {
					var info =tr.chart_id+ '<br> Scale 1 : '+ tr.scale
					drawmsa(tr.area,info,'blue',weight);
					})
				
	}
	function Msa(tableid,tablename,draw){
		
		var xhttp = new XMLHttpRequest();
		var url,tbl,procname,proctext,lat1,lon1,rttypes,rtcharttype,subchart;
        var mline=[];pointsproc=[];
			url='msa/list?id=' + tableid
		
				xhttp.open("GET", baseurl + url ,false)
				xhttp.send()
                var proc= JSON.parse(xhttp.responseText)
				try {
					console.log(proc)
					
					proc.data[0].area.forEach(tr => {
						console.log(tr.segment[0].center_id)
						// if (tr.segment[0].center_id.substr(0,3)=='NAV'){
							DrawNavaid(tr.segment[0].center_id,'')
						// }else{

						// }
						if (draw=='all'){
							
							drawmsa(tr.geom,tr.alt.toString(),'#FF0023',6);

						}else{
							if (tablename==tr.id){
								drawmsa(tr.geom,tr.alt.toString(),'#FF0023',6);
								throw BreakException
							}

						}
								});
				} catch (e) {
					if (e !== BreakException) throw e
				}
				
                
	}
	function drawmsa(tr,info,color,weight){
		proctext=info;
		
		var gm=tr;
		var crd=gm.coordinates[0];
		mline=[];
		for (let i=0;i<crd.length;i++){
			lat1=crd[i][1];
			lon1=crd[i][0];
			mline.push([lat1,lon1]);

		}
		var clr;
		if (weight==1){
			clr= {
				weight: weight,
				opacity: 1,
				draggable: true,
				transform: true,
				color: color,
				smoothFactor: 1,
				fillOpacity: 0,
			};

		}else{
			clr= {
				weight: weight,
				opacity: 1,
				color: color,
				fillOpacity: 0,
			};
		}
			// console.log(proctext);
			// var polygon = L.polygon(cord,clr)
			var polyline = L.polygon(mline,clr)
			.addTo(map)
			.bindPopup(proctext)
			.bindTooltip(proctext)
			map.fitBounds(polyline.getBounds());
			polyline.bindTooltip(proctext,
					{permanent: true, direction:"center"}
					).openTooltip()
		// var polygon = new L.Polygon([mline], {
		// draggable: true,
		// color: '#810541',
		// fillColor: '#D462FF',
		// fillOpacity: 0.5,
		// }).addTo(map);
		if (weight==1){
			polyline.on('dragend', function(e) {
				console.log(e.target._latlngs[0][0]);
			});
			// polyline.transform.enable();
// or partially:
			// polyline.transform.enable({rotation: true, scaling: false});
			// // or, on an already enabled handler:
			// polyline.transform.setOptions({rotation: true, scaling: false});
		}
                
	}
	function AspList(){
		var xhttp = new XMLHttpRequest();
				xhttp.open("GET", baseurl + 'airspace/temp1?A=HSH' ,false)
				xhttp.send()
				var rwy= JSON.parse(xhttp.responseText)
				rwy.data.forEach(data => {
					this.Airspace(data.B,'airspace')
				})
	}
	function Airspace(dataid,tblname,point=false){
		var info='';
		// console.log(tblname)
		if (tblname =='suas'){
			url= baseurl + 'suas/temp/list?suas_id=' + dataid
		}else{
			var xhttp = new XMLHttpRequest();
			xhttp.open("GET", baseurl + 'airspace/temp/list/class?asp_id=' + dataid ,false)
			xhttp.send()
					var rwy= JSON.parse(xhttp.responseText)
					rwy.data.forEach(data => {
						info=data.upper + '<br>' + data.lower
					})
			var xhttp = new XMLHttpRequest();
			xhttp.open("GET", baseurl + 'airspace/temp/list/freq?asp_id=' + dataid ,false)
			xhttp.send()
			var calls=''
			var freq=''
					var rwy= JSON.parse(xhttp.responseText)
					rwy.data.forEach(data => {
						if (calls==''){
							calls = data.call_sign
						}
						if (freq ==''){
							freq=data.freq_real
						}else{
							freq= freq + ',' + data.freq_real
						}

						// console.log(data)
						// info=data.upper + '<br>' + data.lower
					})


			url= baseurl + 'airspace/temp/list?ats_airspace_id=' + dataid

		}
				
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
				var rwy= JSON.parse(xhttp.responseText)
				var no =0
				var ltline=''
				var pnt
				rwy.data.forEach(data => {
					// console.log(data)
					if (tblname == 'suas'){
						nm = data.suas_ident + ' - ' + data.suas_name + '<br>' + data.lower + ' to ' + data.upper
						clr =style(data.suas_type)
					}else{
						nm = data.airspace_type + '<br><b>' + data.airspace_name + '</b><br>' + info + '<br>' + calls + '<br>' + freq
						clr =style(data.airspace_type)
					}
					
					// clr={color: 'grey',dashArray: "5 5", weight: 3}
					// clr =style(data.airspace_type)
					// L.geoJson(statesData, {style: style}).addTo(map);
				
					// console.log(cord.length,cord)
					if(point==true){
						// for (let index = 0; index < cord.length; index++) {
						// 	console.log(cord[index][0])
					
						// }
						var affhasil=[];asspid=data.ats_airspace_id;atype=data.airspace_type;
						data.boundary.forEach(c=>{
							if (c.shap !== 'G'){
								var latb=ToDecimal(c.point1_lat);lonb=ToDecimal(c.point1_long)
								url= baseurl + 'airspace/list/temp/seg?point1_lat=' + c.point1_lat + '&point1_long=' + c.point1_long
								// console.log(lonb,latb)
								Plotpoint(latb,lonb)
								var xhttp = new XMLHttpRequest();
								xhttp.open("GET", url ,false)
								xhttp.send()
										var aseg= JSON.parse(xhttp.responseText)
										aasp=aseg.data;
										// aasp.sort((a,b) => (a.asp_id > b.asp_id) ? 1 : ((b.asp_id > a.asp_id) ? -1 : 0));
										affhasil.push(asspid);
										
										aasp.forEach(d => {
											// if (d.asp_id !== ssp){
												// if (atype=='FIR'){
												// 	if (d.airspace_type=='FIR'){
												// 		affhasil.push(d.asp_id)
												// 	}
												// }else{
													if (d.airspace_type == atype){
														
														var t = affhasil.findIndex(x=>x.asp_id===d.asp_id)
														if (t==-1){
															// console.log(d.airspace_type , atype,d)
															affhasil.push(d)
														}
													}
												// }
												// console.log(v)
							
											// }
											
											// console.log(d)
										})

							}
						})
						affhasil.sort((a,b) => (a.asp_id > b.asp_id) ? 1 : ((b.asp_id > a.asp_id) ? -1 : 0));
						var ssp='';
						affhasil.forEach(a=>{
							if (a.asp_id !== ssp){
								// console.log(a.asp_id)
								Airspace(a.asp_id,'airpsace')
								
							}
							ssp=a.asp_id;
						})
						// console.log(affhasil)
					}else{
						var cord=	reverse(data.geom.coordinates)
						var polygon = L.polygon(cord,clr)
											.addTo(map)
											.bindPopup(nm)
											.openPopup();
						map.fitBounds(polygon.getBounds());
					}

					

						
					
				
				})
				
}

function Airways(dataid,tblname){
	if (tblname =='atslist'){
		url= baseurl + 'ats/temp?ctry=' + dataid + '&sort=seq_424:asc'
	}else{
		url= baseurl + 'ats/temp?ats_id=' + dataid
	}
	// console.log(url)
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	var rwy= JSON.parse(xhttp.responseText)
	var no =0
	var ltline=''
	var pnt

	// console.log('panjang data ',rwy.data.length)
	this.pnt1=''
	this.pnt2=''
	this.colpnt=[]
	var polygon
	rwy.data.forEach(data => {
		// console.log('DATA ATS ',data)
		if (no == 0){
			this.pnt1=[data.geom.coordinates[0][1],data.geom.coordinates[0][0]]
		}

		this.cord=[]
		if (data.point.substr(0,3)=='WPT'){
			if (data.type=='R'){
				DrawNavaid(data.point,'5')
			}else{
				DrawNavaid(data.point,data.wpt_type)
			}
			
		}else{
			DrawNavaid(data.point,'')
		}
		
		this.colpnt.push(data.point)
// console.log(no, rwy.data.length)
		if (no == rwy.data.length -1){
			if (data.point2.substr(0,3)=='WPT'){
				DrawNavaid(data.point2,data.wpt_type2)
			}else{
				DrawNavaid(data.point2,'')
			}// console.log('MASUK TERAKHIT')
			this.colpnt.push(data.point2)
			this.pnt2=[data.geom.coordinates[1][1],data.geom.coordinates[1][0]]
		}
		nmt=data.ats_ident + '<br>' + data.point_1 + ' - ' + data.point_2
		if (data.dir_424 =='B'){
			nm = data.ats_ident + '<br>From ' + data.point_1 + ' to ' + data.point_2 + '<br>Track Out = -<br>Track In = ' + data.track_in + '°<br>Dist = ' + data.dist + ' nm' + '<br>Lower/Upper = ' + data.mfa + ' / ' + data.maa + '<br>MEA = ' + data.mea_out
		} else if (data.dir_424 =='F'){
			nm = data.ats_ident + '<br>From ' + data.point_1 + ' to ' + data.point_2 + '<br>Track Out = ' + data.track_out + '°<br>Track In = -<br>Dist = ' + data.dist + ' nm' + '<br>Lower/Upper = ' + data.mfa + ' / ' + data.maa + '<br>MEA = ' + data.mea_out
		}else{
			nm = data.ats_ident + '<br>From ' + data.point_1 + ' to ' + data.point_2 + '<br>Track Out = ' + data.track_out + '°<br>Track In = ' + data.track_in + '°<br>Dist = ' + data.dist + ' nm' + '<br>Lower/Upper = ' + data.mfa + ' / ' + data.maa + '<br>MEA = ' + data.mea_out
		}
		
		if (data.type=='W'){
			if (data.ats_ident.substr(0,1) == 'W'){
				clr={color: 'brown', weight: 4}
			}else{
				clr={color: 'black', weight: 4}
			}
		} else if (data.type=='R'){
			clr={color: 'blue', weight: 4}
		} else{
			clr={color: 'green', weight: 4}
		}
		
		
		this.cord.push([data.geom.coordinates[0][1],data.geom.coordinates[0][0]])
		this.cord.push([data.geom.coordinates[1][1],data.geom.coordinates[1][0]])
		// console.log(this.cord)
		polygon = new L.Geodesic(this.cord,clr )
								.addTo(map)
								.bindPopup(nm)
								.bindTooltip(nmt)
								// map.fitBounds(polygon.getBounds());
	no += 1
	
	
	})
	// polygon.openTooltip()
	// polygon.openPopup()
		var bounds = new L.LatLngBounds(this.pnt1,this.pnt2);
			map.fitBounds(bounds);
	
}
function getAirport(icao){
	url= baseurl + 'airports?icao=' + icao
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	
			// console.log(table ,tableid)
			var zoom=10
				var lat = '';
				var lon = '';
				var nm ='';hasil=[];
				var hasil= JSON.parse(xhttp.responseText)
					hasil.data.forEach(navaid => {
						hasil=navaid.geom.coordinates;
						zoom = 5
						var cordlat=ToWgs(navaid.geom.coordinates[1],'LAT')
						var cordlon=ToWgs(navaid.geom.coordinates[0],'LON')
							// console.log(cordlat)
							// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
						nm = navaid.icao + ' - ' + navaid.city_name + ' / ' + navaid.arpt_name + ' <br>' + cordlat[2] + '<br>' + cordlon[2]
						lat =  navaid.geom.coordinates[1]
						lon = navaid.geom.coordinates[0]
						Getsymbol(navaid.type,'arpt')
						// this.responseText;
					})
	
			var myIcon = L.icon({
							iconUrl: img,
							iconSize: [25,25],
						})
						
			var marker = L.marker([lat,lon],{icon: myIcon})
					.addTo(map)
					.bindPopup(nm)
					.openPopup()

			map.setView([lat,lon],15)
	return hasil;
			
		}
function _fligtplan(){
	let params = new URLSearchParams(window.location.search);
	let acft = params.get("acft");
	let adep = params.get("adep");
	let ades = params.get("ades");
	let routes = params.get("route");
	let idroute = params.get("id"); 
	var no =0
	var colpnt='';route=''
	var pnt='';
	var collats=[];ori=[];dep=[];
	if(routes !== null){
		
		route='<br>Aircraft ' + acft + '<br>From ' + adep + ' to ' + ades 
		ori=getAirport(adep);
		dep=getAirport(ades);
		if (route !== null){
			// var ex="SBR W43 MUBOT W42 NMA DCT LB"
			var ex=routes;
			// var rr=data.route.split(' ');
			var rr=ex.split(' ');
			// console.log(rr,'SPLIT data',route)
			for (let idx = 0; idx < rr.length; idx++) {
				pnt=rr[idx];
				if (rr[idx].length > 5){
					var p_pnt=rr[idx].split('/');
					pnt=p_pnt[0];
				}
				// pnt=rr[idx].split('/');
				// console.log('DCT', pnt[0])
				if (checkroute(pnt)==true){
					var pnt_p=rr[idx-1];pnt_n=rr[idx+1];
					if (rr[idx-1].length > 5){
						var pnt_pp=rr[idx-1].split('/')
						pnt_p=pnt_pp[0]
					}
					if (rr[idx+1].length > 5){
						var pnt_pp=rr[idx+1].split('/')
						pnt_n=pnt_pp[0]
					}

					var hsil=getAirways(rr[idx]+ '_ID',pnt_p,pnt_n);

					collats += ',' + hsil
					// console.log('airways',hsil,pnt_p,pnt_n)
				}else if (rr[idx] =='DCT'){
					console.log('DCT', rr[idx])
				}else{					
					if (rr[idx].length > 5){
						var p_pnt=rr[idx].split('/');
						pnt=p_pnt[0];
					}
					if (collats == ''){
						collats =pnt;
					}else{
						collats += ',' + pnt;
					}
					
				}
				
			}
			no += 1
			
		}else{
            // Swal.fire({
            //   position: 'top-end',
            //   icon: 'info',
            //   title: 'FPL Route not found',
            //   showConfirmButton: false,
            //   timer: 2500
            // })
            console.log('FPL Route not found');
		}
	}
	var hhs=collats.split(',');
	// console.log(hhs,'WPT ROUTE');
	var p1='';cord=[];atstype='W';
	cord.push([ori[1],Number(ori[0])])
	for (let i = 0; i < hhs.length; i++) {
		if (hhs[i] == ''){
		}else{
			if (hhs[i] !== p1 ){
				var pt=hhs[i].split('#');wtype='1';
				if (pt.length > 1){
					atstype=pt[1];
				}
					// console.log(atstype,'WPT ROUTE')
				if (atstype== 'R'){
					wtype='5';
				}
				var ppt='';wpt=false;
				if (pt[0].length==5){
					wpt=true;
						// console.log('WAYPOINT', rr[idx])
					ppt = getnavaidbyident( pt[0],'WPT');
				}else{
					ppt = getnavaidbyident( pt[0],'NAV');
				}
				// console.log(ppt,'PPT')
				if (ppt !== ''){

					var g=ppt.split('@');
					var cr=g[1].split('$');
					// console.log('NAVAID', g[1],cr)
					cord.push([Number(cr[1]),Number(cr[0])])
					if (wpt==true){
						DrawNavaid(g[0],wtype)

					}else{
						DrawNavaid(g[0],'')

					}

				}
			// console.log(g[0])
			}
		}
		p1=hhs[i];
		
	}
	cord.push([dep[1],Number(dep[0])])
	var amount=0;
	// console.log(cord[1]);
	for (let i = 0; i < cord.length - 1; i++) {
		const curr = new L.LatLng(cord[i][0], cord[i][1]);
		const next = new L.LatLng(cord[i+1][0], cord[i+1][1]);
		const line = new L.Geodesic();
		const distance = line.distance(curr, next);
		amount += Math.floor(distance/1000) ;// KM

	} 
	amount = (0.539957 * amount).toFixed(2); //nautical mile	
	const message =JSON.stringify({
		id : idroute,
		message:'Send Back Distance',
		data : amount
	});
	window.parent.postMessage(message,'*');

	
	var clr={color: 'black', weight: 4}
	var polygon = new L.Geodesic(cord,clr )
								.addTo(map)
								.bindPopup(route)
								.openPopup();

	map.fitBounds(polygon.getBounds());
}
function fligtplan(dataid){

	url= baseurl + 'getfpl?id=' + dataid 
	// url= baseurl + 'getfpl?id=734'
	
	console.log(url,dataid)
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	var rwy= JSON.parse(xhttp.responseText)
	var no =0
	var colpnt='';route=''
	var pnt='';
	var collats=[];ori=[];dep=[];
	rwy.data.forEach(data => {
		route=data.acid + '<br>Aircraft ' + data.acft_id + '<br>From ' + data.adep_id + ' to ' + data.ades_id 
		ori=getAirport(data.adep_id);
		dep=getAirport(data.ades_id);
		if (data.route !== null){
			// var ex="SBR W43 MUBOT W42 NMA DCT LB"
			var ex=data.route;
			// var rr=data.route.split(' ');
			var rr=ex.split(' ');
			// console.log(rr,'SPLIT data',data.route)
			for (let idx = 0; idx < rr.length; idx++) {
				pnt=rr[idx];
				if (rr[idx].length > 5){
					var p_pnt=rr[idx].split('/');
					pnt=p_pnt[0];
				}
				// pnt=rr[idx].split('/');
				// console.log('DCT', pnt[0])
				if (checkroute(pnt)==true){
					var pnt_p=rr[idx-1];pnt_n=rr[idx+1];
					if (rr[idx-1].length > 5){
						var pnt_pp=rr[idx-1].split('/')
						pnt_p=pnt_pp[0]
					}
					if (rr[idx+1].length > 5){
						var pnt_pp=rr[idx+1].split('/')
						pnt_n=pnt_pp[0]
					}

					
					
					var hsil=getAirways(rr[idx]+ '_ID',pnt_p,pnt_n);

					collats += ',' + hsil
					// console.log('airways',hsil,pnt_p,pnt_n)
				}else if (rr[idx] =='DCT'){
					console.log('DCT', rr[idx])
				}else{
					
					if (rr[idx].length > 5){
						var p_pnt=rr[idx].split('/');
						pnt=p_pnt[0];
					}
					
					if (collats == ''){
						collats =pnt;
					}else{
						collats += ',' + pnt;
					}
					
				}
				
			}
			no += 1
			
		}else{
            // Swal.fire({
            //   position: 'top-end',
            //   icon: 'info',
            //   title: 'FPL Route not found',
            //   showConfirmButton: false,
            //   timer: 2500
            // })
            console.log('FPL Route not found');
		}
		
	
	
	
	})
	var hhs=collats.split(',');
	// console.log(hhs,'WPT ROUTE');
	var p1='';cord=[];atstype='W';
	cord.push([ori[1],Number(ori[0])])
	for (let i = 0; i < hhs.length; i++) {
		if (hhs[i] == ''){
		}else{
			if (hhs[i] !== p1 ){
				var pt=hhs[i].split('#');wtype='1';
				if (pt.length > 1){
					atstype=pt[1];
				}
					// console.log(atstype,'WPT ROUTE')
				if (atstype== 'R'){
					wtype='5';
				}
				var ppt='';wpt=false;
				if (pt[0].length==5){
					wpt=true;
						// console.log('WAYPOINT', rr[idx])
					ppt = getnavaidbyident( pt[0],'WPT');
				}else{
					ppt = getnavaidbyident( pt[0],'NAV');
				}
				// console.log(ppt,'PPT')
				if (ppt !== ''){

					var g=ppt.split('@');
					var cr=g[1].split('$');
					// console.log('NAVAID', g[1],cr)
					cord.push([Number(cr[1]),Number(cr[0])])
					if (wpt==true){
						DrawNavaid(g[0],wtype)

					}else{
						DrawNavaid(g[0],'')

					}

				}
			// console.log(g[0])
			}
		}
		p1=hhs[i];
		
	}
	cord.push([dep[1],Number(dep[0])])
	// console.log(cord)
	var clr={color: 'black', weight: 4}
	var polygon = new L.Geodesic(cord,clr )
								.addTo(map)
								.bindPopup(route)
								.openPopup();
						map.fitBounds(polygon.getBounds());
	
}
function getAirways(dataid,point1,point2){
	url= baseurl + 'ats/temp?ctry=' + dataid + '&sort=seq_424:asc'

	// console.log(url)
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	var rwy= JSON.parse(xhttp.responseText)
	var no =0
	var colpnt='';
	var isid='';ix1=0;ix2=0;
	rwy.data.forEach(ats => {
		// console.log(ats,' ATS DATA')
		if (ats.point_1.includes(point1)==true){
			ix1=no;
		}
		if (ats.point_2.includes(point2)==true){
			ix2=no;
		}
		// if (isid =='P1' && no==0){
		if (no==0){
			var p1= ats.point_1.split(' ');
			var p2= ats.point_2.split(' ');
			colpnt =p1[0]+'#'+ats.type + ',' + p2[0]+'#'+ats.type;
		}else{
			var p= ats.point_2.split(' ')
			colpnt +=','+ p[0]+'#'+ats.type
		}
		
		no++
	})
	var colp=colpnt.split(',');
	if (ix1 > ix2){
		colp.reverse();
		// colpnt=colp.toString();
		// kalau route kebalikannya point awalnya 
		// getAirways(dataid,point1,point2,urut=false)
		// console.log(colp,ix1,ix2,'ATS',colpnt)
	}
	// console.log(colp,ix1,ix2,'ATS',colpnt)
	var hasilats='';
	for (let i = 0; i < colp.length; i++) {
		var ff=colp[i].split('#');
		if (ff[0] == point1){
			// console.log(colp[i],'colp[i]')
			isid='P1'
		}
		if (ff[0] == point2){
			isid='P2'
			
		}
		if (isid =='P1'){
			if (hasilats==''){
				hasilats=colp[i];
			}else{
				hasilats += ','+ colp[i];
			}
		}
	}
	if (isid == 'P1' || isid== ''){
		console.log(hasilats,'hasilats',isid)
		// hasilats=point1 + ',' + point2

	}
	return hasilats;
}
function getnavaidbyident(ident,type){
	if (type=='WPT'){
		url= baseurl + 'waypoint/temp?wpt_name=' + ident +'&ctry=ID';
	}else{
		url= baseurl + 'navaid/temp?nav_ident=' + ident +'&ctry=ID';

	}
	// api/navaid/temp?nav_ident=CKG&ctry=ID
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	var rwy= JSON.parse(xhttp.responseText)
	
	var pnt=''

	rwy.data.forEach(data => {
		// console.log(data)
		if (type=='WPT'){
			pnt= data.wpt_id+ '@' +data.geom.coordinates[0]+'$'+ data.geom.coordinates[1];
			// DrawNavaid(data.wpt_id,'')
		}else{
			pnt= data.nav_id+ '@' +data.geom.coordinates[0]+'$'+ data.geom.coordinates[1];
			// DrawNavaid(data.nav_id,'')
		}
	})
	return pnt;
}
function checkroute(txt){
	var hasil=false;
	var jm = txt.length
	for ( let i = 0; i < jm; i++ ){
		var tmid = txt.substr(i, 1 )
		if ( alphanumeric( tmid) == true ){
			hasil=true;
			break;
		}
		
	}
	return hasil;
}
function alphanumeric(inputtxt)
{ 
    // var Exp = /((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i;
    //unutk mengencek numerik atau alphabet, jika nilai TRUE = Numerik, else = Alpaabet
    var Exp = /((^[0-9]+))+$/i;
    // console.log(inputtxt)
    if(inputtxt.match(Exp))
    {
    // alert('Your registration number have accepted : you can try another');
    // document.form1.text1.focus();
    return true;
    }
    else
    {
        // alert('Please input alphanumeric characters only');
    return false;
    }
}
function AirwaysAffect(dataid){
	
	url= baseurl + 'ats/temp?point=' + tableid 
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	var rwy= JSON.parse(xhttp.responseText)
	var collats=[]
	rwy.data.forEach(data => {
		collats.push(data.ctry)
	})

	url= baseurl + 'airspace/temp1?A=HSH'  
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	var asp= JSON.parse(xhttp.responseText)
	var collasp=[]
	asp.data.forEach(data => {
		collasp.push(data.B)
	})

	collasp.forEach(ats => {
		// console.log(ats)
		Airspace(ats,'airspace')
	})
	collats.forEach(ats => {
		// console.log(ats)
		Airways(ats,'atslist')
	})


}

function Airport(arptident){
	url= baseurl + 'airports?arpt_ident=' + arptident
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	
			// console.log(table ,tableid)
			var zoom=10
				var lat = '';
				var lon = '';
				var nm ='';
				var hasil= JSON.parse(xhttp.responseText)
					hasil.data.forEach(navaid => {
						// console.log(navaid)
						zoom = 5
						var cordlat=ToWgs(navaid.geom.coordinates[1],'LAT')
						var cordlon=ToWgs(navaid.geom.coordinates[0],'LON')
							// console.log(cordlat)
							// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
						nm = navaid.icao + ' - ' + navaid.city_name + ' / ' + navaid.arpt_name + ' <br>' + cordlat[2] + '<br>' + cordlon[2]
						lat =  navaid.geom.coordinates[1]
						lon = navaid.geom.coordinates[0]
						Getsymbol(navaid.type,table)
						// this.responseText;
					})
	
			var myIcon = L.icon({
							iconUrl: img,
							iconSize: [25,25],
						})
						
			var marker = L.marker([lat,lon],{icon: myIcon})
					.addTo(map)
					.bindPopup(nm)
					.openPopup()

			map.setView([lat,lon],15)
	
			var xht = new XMLHttpRequest();
			url= baseurl + 'rwy/temp?arpt_ident=' + arptident
			xht.open("GET", url ,false)
			xht.send()
			var rwy= JSON.parse(xht.responseText)
			var no =0
			var ltline=''
			var pnt
			rwy.data.forEach(data => {
				nm = data.thr_low + '/' + data.thr_high + '<br>RWY DIM = ' + data.length +  ' x ' + data.width
				+ '<br>PCN = ' + data.pcn +  '  SURFACE= ' + data.surface
				var rr=data.physicals;
				rr.forEach(r => {
					// console.log(r)
					if (r.geom.coordinates[1] !== 0 && r.geom.coordinates[0] !==0){
						if (no==0){
							pnt=[r.geom.coordinates[1],r.geom.coordinates[0]]
						}else {
							ltline =[pnt,[r.geom.coordinates[1],r.geom.coordinates[0]]]
							// console.log(ltline)
							var polyline = L.polyline(ltline, {color: 'black'})
											.addTo(map)
											.bindPopup(nm)
											.openPopup();
							map.fitBounds(polyline.getBounds());
							ltline='';
							pnt='';
							no=-1
						}

					}
					no += 1
				})
				// this.responseText;
			})
			xht = new XMLHttpRequest()
			url= baseurl + 'airport/list/adc?arpt_ident=' + arptident + '&sort=layer:asc'
			xht.open("GET", url ,false)
			xht.send()
			var rwy= JSON.parse(xht.responseText)
			var no =0
			var ltline=''
			var pnt
			
			rwy.data.forEach(data => {
			//  console.log(data)
				nm = data.layer
				// if ( data.layer == 'apron' || data.layer == 'twy' || data.layer == 'building' || data.layer == 'rwy' || data.layer == 'taxi' || data.layer == 'taxilane' ) {
				if ( data.layer !== 'roads' && data.layer !== 'strip') {
                       
             
					if (data.geom.type !== 'Point'){
						//  console.log(data.layer)
						// L.CRS.EPSG4326
						var cord = reverse(data.geom.coordinates)
						var clr = arptstyle(data.layer)
						
						if (data.geom.type == 'Polygon'){
							
							var polygon = L.polygon(cord,clr )
											.addTo(map)
											.bindPopup(nm)
											.openPopup();
						} else {
							var polyline = L.polyline(cord, clr)
											.addTo(map)
											.bindPopup(nm)
											.openPopup();
						}
		
					}
				}
			
			})
			function arptstyle(feature) {
				if (feature=='strip'){
					return {
						fillColor: getColor(feature),
						weight: 2,
						opacity: 1,
						color: getColor(feature),
						fillOpacity: 0.7,
						dashArray: '3 7',
					};
					
				}else{
					return {
						fillColor: getColor(feature),
						weight: 2,
						opacity: 1,
						color: getColor(feature),
						fillOpacity: 0.7
					};

				}
}


}

function Parkingstand(arptident){

	url= baseurl + 'arpt/parkingstand?arpt_ident_gate=' + arptident
	var xhttp = new XMLHttpRequest();
		xhttp.open("GET", url ,false)
		xhttp.send()
	
			// console.log(table ,tableid)
		if (table=='parkingstand'){
			var zoom=10
				var lat = '';
				var lon = '';
				var nm ='';
				var hasil= JSON.parse(xhttp.responseText)
					hasil.data.forEach(navaid => {
						console.log(navaid)
							zoom = 5
							lat =  ToDecimal(navaid.gate_lat);
							lon = ToDecimal(navaid.gate_lon);
							var cordlat=ToWgs(lat,'LAT')
							var cordlon=ToWgs(lon,'LON')
							// console.log(cordlat,cordlon)
							if (navaid.no_gate.substr(0,1)=='H'){
								nm ='Heliport ' + navaid.no_gate + '<br>' + cordlat[2] + '<br>' + cordlon[2]
							}else{
								// nm ='NR ' + navaid.no_gate + '<br> Capacity = ' + navaid.aircraft_type + '<br>' + cordlat[2] + '<br>' + cordlon[2];
								nm ='NR ' + navaid.no_gate + '<br> Apron = ' + navaid.name + '<br> Capacity = ' + navaid.aircraft_type + '<br>' + cordlat[2] + '<br>' + cordlon[2];
							}
						
						
						Getsymbol('3','arpt')
						var myIcon = L.icon({
							iconUrl: img,
							iconSize: [20,20],
						})
						
						var marker = L.marker([lat,lon],{icon: myIcon})
							.addTo(map)
							.bindPopup(nm)
							.openPopup()
					})
				map.setView([lat,lon],15)
		}
		xht = new XMLHttpRequest()
		if (table=='twy'){
			url= baseurl + 'airport/list/adc?arpt_ident=' + tableid + '&layer=twy&sort=layer:asc'
		}

		if (table=='apron' || table=='parkingstand'){
			url= baseurl + 'airport/list/adc?arpt_ident=' + tableid + '&layer=apron&sort=layer:asc'
		}

			// console.log(url)
			
			
			xht.open("GET", url ,false)
			xht.send()
			var rwy= JSON.parse(xht.responseText)
			var no =0
			var ltline=''
			var pnt
			
			rwy.data.forEach(data => {
			//  console.log(data)
				nm = data.layer 
					//  console.log(data.layer)
					// L.CRS.EPSG4326
				var cord = reverse(data.geom.coordinates)
				var clr = arptstyle(data.layer)
				
				if (data.geom.type == 'Polygon'){
					
					var polygon = L.polygon(cord,clr )
									.addTo(map)
									.bindPopup(nm)
									.openPopup();
									map.fitBounds(polygon.getBounds());
				} else {
					var polyline = L.polyline(cord, clr)
									.addTo(map)
									.bindPopup(nm)
									.openPopup();
									map.fitBounds(polyline.getBounds());
				}
				
			
			})

			function arptstyle(feature) {
				if (feature=='strip'){
					return {
						weight: 2,
						opacity: 1,
						color: getColor(feature),
						dashArray: '3 5',
						fillOpacity: 0.5
					};
					
				}else{
					return {
						fillColor: getColor(feature),
						weight: 2,
						opacity: 1,
						color: getColor(feature),
						fillOpacity: 0.7
					};

				}
}


}


function Obstacle(arptident){
	
url= baseurl + 'eaip/obstacletemp?arpt_ident=' + arptident + '&deleted=0'
var xhttp = new XMLHttpRequest();
	xhttp.open("GET", url ,false)
	xhttp.send()

		console.log(table ,tableid)
		var zoom=10
		var lat = '';
		var lon = '';
		var nm ='';
		var hasil= JSON.parse(xhttp.responseText)
			hasil.data.forEach(obst => {
				var cordlat=ToWgs(obst.geom.coordinates[1],'LAT')
				var cordlon=ToWgs(obst.geom.coordinates[0],'LON')
				var elev=obst.elev_ft + ' ft';hgt=obst.hgt + ' ft';
				if (obst.elev_ft==null){
					elev='NIL';
				}
				if (obst.hgt==null){
					hgt='NIL';
				}
				if (obst.definition == 'Other'){
					nm = obst.remarks + '<br>Elev : ' + elev + '<br>Height : ' + hgt + '<br>' + cordlat[2] + '<br>' + cordlon[2] + '<br>Position : ' + obst.position
				}else{
					nm = obst.definition + '<br>Elev : ' + elev + '<br>Height : ' + hgt + '<br>' + cordlat[2] + '<br>' + cordlon[2] + '<br>Position : ' + obst.position
				}
				
				lat =  obst.geom.coordinates[1]
				lon = obst.geom.coordinates[0]
				if (obst.elev_ft >= 1000){
					if (obst.lighted == 'Y'){
						Getsymbol('6','obst')
					}else{
						Getsymbol('5','obst')
					}
				}else{
					if (obst.obs_group == 'Y'){
						if (obst.lighted == 'Y'){
							Getsymbol('4','obst')
						}else{
							Getsymbol('3','obst')
						}

					}else{
						if (obst.lighted == 'Y'){
							Getsymbol('2','obst')
						}else{
							Getsymbol('1','obst')
						}

					}
				}
				
				var myIcon = L.icon({
					iconUrl: img,
					iconSize: [20,20],
				})
				
				var marker = L.marker([lat,lon],{icon: myIcon})
					.addTo(map)
					.bindPopup(nm)
					.openPopup()
			})
			map.setView([lat,lon],15)
			Airport(arptident)
	

			
}
		
function getColor(d) {
	// console.log(d)
		return d == 'FIR' ? '#636363' :
			d == 'UTA'  ? '#efedf5' :
			d == 'FSS'  ? '#fff7bc' :
			d == 'CTA'  ? '#7fcdbb' :
			d == 'MTCA'  ? '#3182bd' :
			d == 'TMA'   ? '#bcbddc' :
			d == 'CTR'   ? '#2ca25f' :
			d == 'ATZ'   ? '#756bb1' :
			d == 'AFIZ'   ? '#756bb1' :
			d == 'D'   ? '#e20e0e' :
			d == 'P'   ? '#e20e0e' :
			d == 'R'   ? '#e20e0e' :
			d == 'T'   ? '#bdbdbd' :
			d == 'M'   ? '#b30d0d' :
			d == 'W'   ? '#fec44f' :
			d == 'A'   ? '#c994c7' :
			d == 'strip'   ? '#f0f0f0' :
			d == 'rwymarking'   ? 'white' :
			d == 'rwy'   ? 'black' :
			d == 'roads'   ? '#de2d26' :
			d == 'building'   ? '#3182bd' :
			d == 'apron'   ? '#636363' :
			d == 'twy'   ? '#bdbdbd' :
			d == 'taxilane'   ? '#fff7bc' :
			d == 'centerline'   ? '#f0f0f0' :
						'#f0f0f0';

}

function style(feature) {
    return {
        fillColor: getColor(feature),
        weight: 2,
        opacity: 1,
        color: 'black',
        dashArray: '3 5',
        fillOpacity: 0.7
    };
}

function reverse(cord){
	var rslt=[]
	// console.log('ASLI ' ,cord)
	
	for (let i = 0;i < cord.length;i++){
		var xx=[]
		// console.log('BEFORE ' ,cord[i])
		for (let x = 0;x < cord[i].length;x++){
			// console.log(cord[i][x])
		// 	// if (x==0){
			 	xx = [cord[i][x][1],cord[i][x][0]]
		// 	// }else{
		// 	// 	xx += [cord[i][x][1],cord[i][x][0]]
		// 	// }
		// console.log(xx)
			rslt.push(xx)

		}
	}
		


	
	// console.log('HASIL ' ,rslt)
return [rslt]
}

	function Getsymbol(pointtype,tbl) {
		// console.log(pointtype,tbl)
		var hasil=''
		if (tbl == 'navaid'){
			switch (pointtype) {
				case '1':
					ttp='VOR'
					img = '/images/marker/VOR.svg'
					imgS=[50, 50]
					break;
				case '2':
					ttp='VORTAC'
					img = '/images/marker/VORTAC.svg'
					imgS=[50, 50]
					break;
				case '3':
					ttp='TACAN'
					img = '/images/marker/TACAN.svg'
					imgS=[50, 50]
					break;
				case '4':
					ttp='VOR/DME'
					img = '/images/marker/VORDME.svg'
					imgS=[50, 50]
					break;
				case '5':
					ttp='NDB'
					img = '/images/marker/NDB.svg'
					imgS=[30, 30]
					break;
				case '7':
					ttp='NDB/DME'
					img = '/images/marker/NDB.svg'
					imgS=[30, 30]
					break;
				case '10':
					ttp='LOC'
					img = '/images/marker/NDB.svg'
					imgS=[30, 30]
					break;
				default:
					ttp='RADAR'
					img = '/images/marker/NCRP.svg'
					imgS=[30, 30]
					break;
			}
		} else if (tbl=='ils'){
			ttp='ILS'
			img = '/images/marker/ILS.svg'
			imgS=[20, 20]
			
		} else if (tbl=='waypoint'){
			switch (pointtype) {
				case '1':
					ttp='CRP'
					img = '/images/marker/CRP.svg'
					imgS=[50, 50]
					break;
				case '2':
					ttp='NCRPC'
					img = '/images/marker/NCRP.svg'
					imgS=[50, 50]
					break;
				case '3':
					ttp='MRP'
					img = '/images/marker/MRP.svg'
					imgS=[50, 50]
					break;
				case '4':
					ttp='MRP'
					img = '/images/marker/MRP.svg'
					imgS=[50, 50]
					break;
				case '5':
					ttp='RNAV'
					img = '/images/marker/RNAVC.svg'
					imgS=[50, 50]
					break;
				default:
					ttp='NCRP'
					img = '/images/marker/NCRP.svg'
					imgS=[50, 50]
					break;
			}

		} else if (tbl=='arpt'){
			switch (pointtype) {
				case '1':
					ttp='1'
					img = '/images/marker/ARPT_1.svg'
					imgS=[50, 50]
					break;
				case '2':
					ttp='2'
					img = '/images/marker/ARPT_2.svg'
					imgS=[50, 50]
					break;
				case '3':
					ttp='3'
					img = '/images/marker/ARPT_3.svg'
					imgS=[50, 50]
					break;
				case '4':
					ttp='4'
					img = '/images/marker/ARPT_4.svg'
					imgS=[50, 50]
					break;
				case '5':
					ttp='5'
					img = '/images/marker/ARPT_5.svg'
					imgS=[30, 30]
					break;
				default:
					ttp='2'
					img = '/images/marker/ARPT_2.svg'
					imgS=[30, 30]
					break;
			}

		} else if (tbl=='obst'){
			switch (pointtype) {
				case '1':
					ttp='1'
					img = '/images/marker/obst.svg'
					imgS=[30, 30]
					break;
				case '2':
					ttp='2'
					img = '/images/marker/obst_l.svg'
					imgS=[30, 30]
					break;
				case '3':
					ttp='3'
					img = '/images/marker/obst_g.svg'
					imgS=[30, 30]
					break;
				case '4':
					ttp='4'
					img = '/images/marker/obst_g_l.svg'
					imgS=[30, 30]
					break;
				case '5':
					ttp='5'
					img = '/images/marker/obst_abv.svg'
					imgS=[30, 30]
					break;
				case '6':
					ttp='6'
					img = '/images/marker/obst_abv_l.svg'
					imgS=[30, 30]
					break;
				default:
					ttp='7'
					img = '/images/marker/obst.svg'
					imgS=[30, 30]
					break;
			}

		}
		hasil = img;
		return hasil
	}
	function ToDecimal(corvalue) {
        this.head;
        this.mark;
        this.deg;
        this.Min;
        this.sec;
        this.afS;
        this.reslt;
        // console.log(corvalue);
        this.head = corvalue.substr(corvalue.length - 1).toUpperCase();
        if (this.head == "E" || this.head == "N") {
            this.mark = 1;
        } else if (this.head == "W" || this.head == "S") {
            this.mark = -1;
        }
        // console.log('corvalue.length ' + corvalue.length +  ' this.mark ' + this.mark) 
        if (this.head == "E" || this.head == "W") {
            this.deg = Number(corvalue.substr(0, 3));
            this.Min = Number(corvalue.substr(3, 2));
            // console.log('this.deg ' + this.deg +  ' this.Min ' + this.Min) 
            if (corvalue.substr(5, 1) == ".") {
                this.sec = Number("0." + corvalue.substr(6, (corvalue.length - 7))) * 60;
            } else {
                if (corvalue.length == 10) {
                    this.sec = parseFloat(corvalue.substr(5, 2) + "." + corvalue.substr(7, 2));
                    // this.sec = Number(corvalue.substr(7, 2));
                } else {
                    corvalue = corvalue.Replace(/./g, '');
                    this.sec = parseFloat(corvalue.substr(5, 2) + "." + corvalue.substr(7, 2));
                }
            }

        } else if (this.head == "N" || this.head == "S") {
            this.deg = Number(corvalue.substr(0, 2));
            this.Min = Number(corvalue.substr(2, 2));
            if (corvalue.substr(4, 1) == ".") {
                this.sec = Number("0." + corvalue.substr(5, (corvalue.length - 5))) * 60;
            } else {
                if (corvalue.length == 9) {
                    // this.sec = Number(corvalue.substr(6, 2));
                    this.sec = parseFloat(corvalue.substr(4, 2) + "." + corvalue.substr(6, 2));
                } else {
                    corvalue = corvalue.Replace(/./g, '');
                    this.sec = parseFloat(corvalue.substr(4, 2) + "." + corvalue.substr(6, 2));
                }
            }
        }

        this.afS = Number(this.sec);
        // console.log(this.afS)
        this.reslt = (this.deg + (((this.afS / 60) + this.Min) / 60)) * this.mark;
            // if (this.head == "N" || this.head == "S") {
            //     this.platDecimal = this.reslt;
            // } else if (this.head == "W" || this.head == "E") {
            //     this.pLonDecimal = this.reslt;
            // }
        // console.log(this.reslt)
        return this.reslt;
	}

	function ToWgs(cor, LatOrLon) {
            this.secInHr = 3600;
            this.secInMn = 60;
            this.tag = LatOrLon.toUpperCase();
    
            if (this.tag == "LAT") {
                this.platDecimal = cor;
                if (this.platDecimal == 0) {
                    this.Header = "";
                } else if (this.platDecimal > 0) {
                    this.Header = "N";
                } else if (this.platDecimal < 0) {
                    this.Header = "S";
                }
            } else if (this.tag == "LON") {
                this.pLonDecimal = cor
                if (this.pLonDecimal == 0) {
                    this.Header = "";
                } else if (this.pLonDecimal > 0) {
                    this.Header = "E";
                } else if (this.pLonDecimal < 0) {
                    this.Header = "W";
                }
            }

        // console.log('TAG ' + tag + ' Cord ' + cor)

            this.corInSec = Math.abs(cor * this.secInHr);
            // console.log('corInSec ' + this.corInSec)
            this.deg = parseInt((this.corInSec - (this.Mmod(this.corInSec, this.secInHr))) / this.secInHr);
            // console.log('deg ' + this.deg)
            this.Min = parseInt(parseInt((this.Mmod(this.corInSec, this.secInHr) / this.secInMn)));
            // console.log('Min ' + this.Min)
            this.pMinFir = (this.Mmod(this.corInSec, this.secInHr) / this.secInMn).toFixed();
            // console.log('pMinFir ' + this.pMinFir)
            this.pMin = (this.Mmod(this.corInSec, this.secInHr) / this.secInMn).toFixed(1);
                    //   console.log('pMin ' + pMin)
            this.psecTMA = parseInt(this.Mmod(this.Mmod(this.corInSec, this.secInHr), this.secInMn).toFixed(1));
            //  console.log('psecTMA ' + psecTMA)
            // this.sec = parseInt(parseInt(this.Mmod(this.Mmod(this.corInSec, this.secInHr), this.secInMn)));
            this.secasli = this.Mmod(this.Mmod(this.corInSec, this.secInHr), this.secInMn)
            // console.log('sec ' + this.secasli)
            this.sec = parseInt(this.secasli);
            // this.sec = this.Mmod(this.Mmod(this.corInSec, this.secInHr), this.secInMn);
            // console.log('sec ' + this.sec)
            this.dSec = ((this.secasli - this.sec) * 100).toFixed();
            // console.log('dSec ' + this.dSec)
            this.dSec10 = (this.dSec / 10).toFixed() ;
                // console.log('dSec10 ' + this.dSec10)
            this.Cmin, this.Cdeg, this.CSec, this.CdSec, this.CSeciac, this.CdSeciac;
            if (this.dSec == 100) {
                this.sec = this.sec + 1;
                this.dSec = 0;
            }
            this.Cmin = this.Min;
            this.Cdeg = this.deg;
            this.CSec = this.sec;
            this.CdSec = this.dSec;
            this.CSeciac = this.sec;
            this.CdSeciac = this.dSec10;

            if (this.sec == 60) {
                this.sec = 0;
                this.Min = this.Min + 1;
            }

            if (this.Min == 60) {
                this.Min = 0;
                this.deg = this.deg + 1;
            }

            if (this.dSec >= 50) {
                this.CSec = this.CSec + 1;
                this.CdSec = 0;
            }

            if (this.CSec == 60) {
                this.CSec = 0;
                this.Cmin = this.Cmin + 1;
            }
            if (this.Cmin == 60) {
                this.Cmin = 0;
                this.Cdeg = this.Cdeg + 1;
            }

            this.aSec = this.sec;
            this.aDSec = this.dSec / 100;
            this.dSecAIP = this.dSec / 100;
            this.ssc = this.aDSec.toFixed(1);
            if (this.ssc == 1) {
                this.aSec = this.aSec + 1;
                this.dSecAIP = 0;
            } else {
                this.dSecAIP = this.ssc * 10;
            }

            if (this.tag == "LAT") {
                if (this.deg == 0 && this.Min == 0) {
                    this.pLatGrid = "0°";
                } else if (this.Min == 0) {
                    this.pLatGrid = this.Header + this.Format(this.deg, 2) + "°00'";
                } else {
                    this.pLatGrid = this.Header + this.Format(this.deg, 2) + "°" + this.Format(this.Min, 2) + "'";
                }
                this.LatDecimal = this.platDecimal;
                this.Latitude = this.Format(this.deg, 2) + this.Format(this.Min, 2) + this.Format(this.sec, 2) + this.Format(this.dSec, 2) + this.Header;
                this.LatforFIR = this.Format(this.deg, 2) + this.Format(this.pMinFir, 2) + this.Header;
                this.LatforTMA = this.Format(this.deg, 2) + this.Format(this.Min, 2) + this.Format(this.psecTMA, 2) + this.Header;
                this.pLatRwyAnal = this.Format(this.deg, 2) + this.Format(this.Min, 2) + this.Format(this.sec, 2) + this.Format(this.dSec, 2) + this.Header;
                this.LatWgsAIP = this.Format(this.deg, 2) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.sec, 2) + "." + this.Format(this.dSec, 2) + "''" + this.Header;
                this.pLatGridGND = this.Format(this.deg, 2) + this.Format(this.Min, 2) + this.Format(this.sec, 2) + ".00" + this.Header;
                this.LatWgsIAC = this.Format(this.deg, 2) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.aSec, 2) + "." + this.Format(this.dSecAIP, "0") + "''" + this.Header;
                this.LatitudeWgs = this.Format(this.deg, 2) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.sec, 2) + "." + this.Format(this.dSec, 2) + "''" + this.Header;
                this.LatforPrint = this.Format(this.Cdeg, 2) + "°" + this.Format(this.Cmin, 2) + "'" + this.Format(this.CSec, 2) + "''" + this.Header;
                this.LatforADText = this.Format(this.Cdeg, 2) + this.Format(this.Cmin, 2) + this.Format(this.CSec, 2) + this.Header;
                this.LatWgsSIDSTAR = this.Format(this.Cdeg, 2) + "°" + this.Format(this.Cmin, 2) + "'" + this.Format(this.CSec, 2) + "''" + this.Header;
                this.LatPrintTextAIP = this.Format(this.deg, 2) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.aSec, 2) + "." + this.Format(this.dSecAIP, "0") + "''" + this.Header;
                this.hasil = [this.LatDecimal, this.Latitude, this.LatitudeWgs, this.LatforADText, this.LatWgsAIP, this.LatWgsIAC, this.LatWgsSIDSTAR, this.LatforFIR, this.LatforTMA];
            } else if (this.tag == "LON") {
                if (this.deg == 0 && this.Min == 0) {
                    this.pLonGrid = "0°";
                    this.pLonIso = "0°";
                }else if (this.Min == 0){
                    this.pLonGrid = this.Header + this.Format(this.deg, 3) + "°00'";
                    this.pLonIso = this.deg + "°" + this.Header;
                } else {
                    this.pLonGrid = this.Header + this.Format(this.deg, 3) + "°" + this.Format(this.Min, 2) + "'";
                    this.pLonIso = this.deg + "°" + this.Format(this.Min, 3) + "'" + this.Header;
                }
                this.LonDecimal = this.pLonDecimal;
                this.Longitude = this.Format(this.deg, 3) + this.Format(this.Min, 2) + this.Format(this.sec, 2) + this.Format(this.dSec, 2) + this.Header;
                this.LonforFIR = this.Format(this.deg, 3) + this.Format(this.pMinFir, 2) +this. Header;
                this.LonforTMA = this.Format(this.deg, 3) + this.Format(this.Min, 2) + this.Format(this.psecTMA, 2) + this.Header;
                this.LonWgsIAC = this.Format(this.deg, 3) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.aSec, 2) + "." + this.Format(this.dSecAIP, "0") + "''" + this.Header;
                this.pLonRwyAnal = this.Format(this.deg, 3) + this.Format(this.Min, 2) + this.Format(this.sec, 2) + this.Format(this.dSec, 2) + this.Header;
                this.LonWgsAIP = this.Format(this.deg, 3) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.sec, 2) + "." + this.Format(this.dSec, 2) + "''" + this.Header;
                this.pLonGridGND = this.Format(this.deg, 3) + this.Format(this.Min, 2) + this.Format(this.sec, 2) + ".00" + this.Header;
                this.LongitudeWgs = this.Format(this.deg, 3) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.sec, 2) + "." + this.Format(this.dSec, 2) + "''" + this.Header;
                this.pLonPrint = this.Format(this.Cdeg, 3) + "°" + this.Format(this.Cmin, 2) + "'" + this.Format(this.CSec, 2) + "''" + this.Header;
                this.LonforADText = this.Format(this.Cdeg, 3) + this.Format(this.Cmin, 2) + this.Format(this.CSec, 2) + this.Header;
                this.LonWgsSIDSTAR = this.Format(this.Cdeg, 3) + "°" + this.Format(this.Cmin, 2) + "'" + this.Format(this.CSec, 2) + "''" + this.Header;
                this.LonPrintTextAIP = this.Format(this.deg, 3) + "°" + this.Format(this.Min, 2) + "'" + this.Format(this.aSec, 2) + "." + this.Format(this.dSecAIP, "0") + "''" + this.Header;
                this.hasil = [this.LonDecimal, this.Longitude, this.LongitudeWgs, this.LonforADText, this.LonWgsAIP, this.LonWgsIAC, this.LonWgsSIDSTAR, this.LonforFIR, this.LonforTMA];
            }
			return this.hasil
			

    }

	function Format(num, targetLength) {
            return num.toString().padStart(targetLength, 0);
    }
	function FreqFormat( freq, navtype) {

		if ( freq == '' ) {
			this.rslt = 'NIL';
		} else if ( navtype == '3' || navtype == '9' ) {
			this.rslt = 'CH-' + freq
		} else {
			this.frq = parseFloat( freq.replace( /M|K|' '/g, '' ) );
			switch ( navtype ) {
				case '5':
				case '7':
				case '10':
					if ( this.frq >= 100000 ) {
						this.rslt = this.frq / 1000;
					} else {
						this.rslt = this.frq
					}
					break;
				default:
					if ( this.frq >= 1000000 ) {
						this.rslt = this.frq / 10000 
					} else if ( this.frq < 1000000 && this.frq > 100000 ) {
						this.rslt = this.frq / 1000 
					} else {
						this.rslt = this.frq
					}
					break;
			}
		}
			// console.log(this.rslt);
		return this.rslt;
		}

    function Mmod(theValue, AgValue) {
            return theValue - AgValue * parseInt(theValue / AgValue);
    }
	var currentLayer = null
	map.on('click', (event) => {
		var cordlat=ToWgs(event.latlng.lat,'LAT')
		var cordlon=ToWgs(event.latlng.lng,'LON')
		// console.log(cordlat)
		nm='You clicked at <br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
			var marker = L.marker(event.latlng)
						.addTo(map)
						.bindPopup(nm)
						.openPopup()

		// console.log( event.latlng.lat)
			if (currentLayer) {
				map.removeLayer(currentLayer)
			}

			// document.getElementById('latitude').innerHTML = event.latlng.lat
			// document.getElementById('longitude').innerHTML = event.latlng.lng

			map.addLayer(marker)			
			currentLayer = marker
		})
		document.getElementsByClassName( 'leaflet-control-attribution' )[0].style.display = 'none';
	</script>
	<!-- <script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-535854-7']);
		_gaq.push(['_setDomainName', 'wistia.com']);
		_gaq.push(['_trackPageview']);
		// If the client supports replaceState, strip utm tags out of the query string
		// after GA loads.
	//   console.log( window.location.search,document.location.protocol,window.location.pathname)
		_gaq.push(function() {
			if (!window.history.replaceState) return;

			var cleanSearch = window.location.search
			.replace(/utm_[^&]+&?/g, '')
			.replace(/&$/, '')
			.replace(/^\?$/, '');
		//   window.history.replaceState({}, '', window.location.pathname + cleanSearch);
			window.history.replaceState({}, '', window.location.pathname);
		});

		(function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.src =
				('http:' == document.location.protocol ? 'http://ssl' : 'http://www') +
				'.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
	</script> -->

