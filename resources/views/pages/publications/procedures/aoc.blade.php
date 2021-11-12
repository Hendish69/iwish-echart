@extends('layouts.app')

@section('template_title')
    AOC
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
        <div class="nk-content-body mt-3">
            <div class="nk-block-between">
                <h5 class="panel-title" id="bmedit"></h5>
            </div>
            <div class="panel-body mt-3" id="bmdetail">
                <div class="card-inner table-bordered mt-1">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>ICAO</strong>
                            <br>
                            <input id="icao" type="text" onfocusout="searchats()" class="form-control" name="icao">
                        </div>
                        <div class="col-md-4">
                            <strong>Airport Name</strong>
                            <br>
                            <input id="arpt_name" type="text" class="form-control" name="arpt_name">
                        </div>
                        <div class="col-md-4">
                            <strong>City</strong>
                            <br>
                            <input id="city_name" type="text" class="form-control" name="city">
                        </div>
                        <div class="col-md-2">
                            <strong>Runway</strong>
                            <br>
                            <select id="rwy_id" name="rwy_id" class="form-control" >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-12">
                        <button onclick="isback()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                        &nbsp;
                        <button onclick="Drawall()" class="btn btn-dim btn-info"><em class="icon ni ni-map"></em> Show</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4" id="mapid" style="visibility:hidden">
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

var arpt=@json($airport);cod=@json($cod);tbl=@json($tbl);obs=@json($obst);
var oldgeom='';geompoly='';  geommove='';geomrot='';oldpaper='';oldscale=''; mline=[];listchart=[];
var arp=[];

initmap()
function initmap(){
   
var bm= 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
var map_id = 'OpenStreetMap';
L.tileLayer(bm,
        {
            minZoom: 2,
            id: map_id,
        }
        ).addTo(map);
}

$("#mapid").hide();

var input = document.getElementById("icao");
// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        searchats();
        // document.getElementById("ats_ctry").onchange();
    }
});
function searchats(){
    var ident=$("#icao").val().toUpperCase()
    window.scrollTo(0,0);
    // window.location.href = '/atsdetail/' + ident;
    $.ajax({
        url: '/api/airports',
        data: {'icao' : ident},
        type: "json",
        method: "GET",

            success: function (result) {
                var jmlwpt=result.data.length
                    // console.log(jmlwpt)
                $.each(result.data, function (k, v) {
                    arp=v;
                    $("#arpt_name").val(v.arpt_name)
                    $("#city_name").val(v.city_name)
                    $("#icao").val(v.icao)
                    rwylist(v.runwaystemp)
                })
            }
    })

}

    arp=arpt[0];
    // console.log(arp)
    $("#icao").val(arp.icao)
    $("#arpt_name").val(arp.arpt_name)
    $("#city_name").val(arp.city_name)
    $("#bmedit").html('AERODROME OBSTACLE CHART - ICAO TYPE A')
    rwylist(arp.runwaystemp)

    function rwylist(rwy){
        $("#rwy_id").empty();
        $("#rwy_id").append('<option value=""></option>');
        for (let index = 0; index < rwy.length; index++) {
            var r = rwy[index];
            // console.log(r)
            $("#rwy_id").append('<option value="'+r.rwy_id+'">'+r.rwy_ident+'</option>');
            // console.log(r)
        
            
        }

    }

    function Drawall(){
  
   
    if ($("#rwy_id").val()=='' ){
        Swal.fire(
            'Invalid Data!',
            'Runway cannot be empty !!!',
            'error'
            )
            // location.reload()
    }else{
        map.eachLayer(function (layer) {
            // console.log(layer)
            map.removeLayer(layer);
        });
        initmap();
        viewrwy();
        viewrwyarea('H');
        viewrwyarea('L');
       
        
    }


    
}
function backto (){
    aboutvol("mapid"); aboutvol("bmdetail")
}
function viewrwy(){
    if ($("#mapid").is(':visible')==false){
        aboutvol('mapid');
    }
    if ($("#bmdetail").is(':visible')==true){
        aboutvol('bmdetail');
    }
    var rwy=arp.runwaystemp
    var rk=$("#rwy_id").val().split('@')
    // console.log(rk,rwy)
    var ix=rwy.findIndex(x=>x.rwy_id===rk[0])
    // console.log(rwy[ix])
   
    var aar=arpt[0];
    proctext = aar.icao + ' - ' + aar.city_name + ' / ' + aar.arpt_name
        thrl_x=rwy[ix].physicals[0].geom.coordinates[0]; thrl_y=rwy[ix].physicals[0].geom.coordinates[1];
        thrh_x=rwy[ix].physicals[1].geom.coordinates[0]; thrh_y=rwy[ix].physicals[1].geom.coordinates[1];

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
			map.fitBounds(polygon.getBounds());
            polygon.addTo(map);
			// polygon.bindTooltip(proctext,
			// 		{permanent: true, direction:"center"}
			// 		).openTooltip()
	
          
    
}

function viewrwyarea(thr){

    var rwy=arp.runwaystemp
    var rk=$("#rwy_id").val().split('@')
    // console.log(rk,rwy)
    var ix=rwy.findIndex(x=>x.rwy_id===rk[0])
    var thr_x=0; thr_y=0;thrh_x=0; thrh_y=0;
    var jrk1 = m2Nm(6480); jrk2 = m2Nm(10000 - 6480);  //' 12000 menjadi 10000 sesuai annex,jika pake 12000 slope harus pake 1.0
    var Side1 = m2Nm(90); Side2 = m2Nm(900); jExtraSlope = m2Nm(300);CwyL=0;
    var aar=arpt[0];
        thr_l=rwy[ix].physicals[0];thr_h=rwy[ix].physicals[1];
        // console.log(thr_l,thr_h)
        mline=[];
        if (thr=='H'){
            rotRwy=Number(thr_l.true_brg);
            thr_x=thr_h.geom.coordinates[0]; thr_y=thr_h.geom.coordinates[1];
            thrh_x=thr_x; thrh_y=thr_y;
            if (thr_h.cwy_length > 0){
                CwyL = m2Nm(Number(thr_l.cwy_length))
                gTrk = getpoint2coord(thrh_y,thrh_x,rotRwy,CwyL);
                thrh_x=gTrk.Decimal[0]; thrh_y=gTrk.Decimal[1];
            }
            ThrElev=Number(thr_h.thr_elev) * 0.3048;
        }else if (thr=='L'){
            rotRwy=Number(thr_h.true_brg);
            thr_x=thr_l.geom.coordinates[0]; thr_y=thr_l.geom.coordinates[1];
            thrh_x=thr_x; thrh_y=thr_y;
            if (thr_l.cwy_length > 0){
                CwyL = m2Nm(Number(thr_l.cwy_length))
                gTrk = getpoint2coord(thrh_y,thrh_x,rotRwy,CwyL);
                thrh_x=gTrk.Decimal[0]; thrh_y=gTrk.Decimal[1];
            }
            ThrElev=Number(thr_l.thr_elev) * 0.3048;
        }

        Sdt11 = rotRwy - 90
        Sdt12 = rotRwy + 90

      
        // console.log(Sdt11 , rotRwy)
        p1 = getpoint2coord(thrh_y,thrh_x,Sdt11,Side1)
        p2 = getpoint2coord(thrh_y,thrh_x,Sdt12,Side1)

        gjrk = getpoint2coord(thrh_y,thrh_x,rotRwy,jrk1)

        p3 =  getpoint2coord(gjrk.Decimal[1],gjrk.Decimal[0],Sdt11,Side2)
        p4 =  getpoint2coord(gjrk.Decimal[1],gjrk.Decimal[0],Sdt12,Side2)

        gjrk1 = getpoint2coord(gjrk.Decimal[1],gjrk.Decimal[0],rotRwy,jrk2)

        p5 =  getpoint2coord(gjrk1.Decimal[1],gjrk1.Decimal[0],Sdt11,Side2)
        p6 =  getpoint2coord(gjrk1.Decimal[1],gjrk1.Decimal[0],Sdt12,Side2)
        mline.push([p1.Decimal[1],p1.Decimal[0]]);
        mline.push([p3.Decimal[1],p3.Decimal[0]]);
        mline.push([p5.Decimal[1],p5.Decimal[0]]);
        mline.push([p6.Decimal[1],p6.Decimal[0]]);
        mline.push([p4.Decimal[1],p4.Decimal[0]]);
        mline.push([p2.Decimal[1],p2.Decimal[0]]);
        mline.push([p1.Decimal[1],p1.Decimal[0]]);

        var gg=p1.Decimal[0] + ' ' + p1.Decimal[1] +',' + p3.Decimal[0] + ' ' + p3.Decimal[1] +',' + p5.Decimal[0] + ' ' + p5.Decimal[1] +',' + p6.Decimal[0] + ' ' + p6.Decimal[1] +',' + p4.Decimal[0] + ' ' + p4.Decimal[1] +',' + p2.Decimal[0] + ' ' + p2.Decimal[1] +',' + p1.Decimal[0] + ' ' + p1.Decimal[1]
        geompoly='POLYGON(('+gg+'))';
        // console.log(geompoly)
	// console.log(geompoly,'AASLI')
            var clr= {
                color: 'red',
                weight: 2,
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
			map.fitBounds(polygon.getBounds());
            polygon.addTo(map);
            



    $.ajax({
        url: '/api/eaip/obstacleaoc/' + geompoly,
        // data: {'geom' : poly},
        type: "json",
        method: "GET",
        success: function (result) {
            $.each(result.data, function (k, o) {
                obs.forEach(v=>{
                    
                    if (v.id==o.id){
                        console.log(o,v)
                        var elev=''
                        if (v.elev_ft == '0' || v.elev_ft == null){
                            Hdobs = (Number(v.hgt * 0.3048)) - ThrElev
                            elev='Height : ' + v.hgt + 'ft'
                        }else{
                            Hdobs = (Number(v.elev_ft * 0.3048)) - ThrElev
                            elev='Elevation : ' + v.elev_ft + 'ft'
                        }
                        var thrident='';
                        if (thr=='H'){
                            rotRwy=Number(thr_l.true_brg);
                            thrident='RWY ' + thr_h.rwy_ident
                            thr_x=thr_h.geom.coordinates[0]; thr_y=thr_h.geom.coordinates[1];
                            thrh_x=thr_x; thrh_y=thr_y;
                            if (thr_h.cwy_length > 0){
                                CwyL = m2Nm(Number(thr_l.cwy_length))
                                gTrk = getpoint2coord(thrh_y,thrh_x,rotRwy,CwyL);
                                thrh_x=gTrk.Decimal[0]; thrh_y=gTrk.Decimal[1];
                            }
                            ThrElev=Number(thr_h.thr_elev) * 0.3048;
                        }else if (thr=='L'){
                            rotRwy=Number(thr_h.true_brg);
                            thrident='RWY ' + thr_l.rwy_ident
                            thr_x=thr_l.geom.coordinates[0]; thr_y=thr_l.geom.coordinates[1];
                            thrh_x=thr_x; thrh_y=thr_y;
                            if (thr_l.cwy_length > 0){
                                CwyL = m2Nm(Number(thr_l.cwy_length))
                                gTrk = getpoint2coord(thrh_y,thrh_x,rotRwy,CwyL);
                                thrh_x=gTrk.Decimal[0]; thrh_y=gTrk.Decimal[1];
                            }
                            ThrElev=Number(thr_l.thr_elev) * 0.3048;
                        }
                        var gTrak=Getbearing(thrh_y,thrh_x,v.geom.coordinates[1],v.geom.coordinates[0]);
                        var throbst=Getbearing(thr_y,thr_x,v.geom.coordinates[1],v.geom.coordinates[0]);
                        var SD= gTrak.DistanceReal * 1852;
                        var HD = (Hdobs / SD) * 100;
                        var cord = SetCoordinatebyDecimal(v.geom.coordinates[0],v.geom.coordinates[1])
                        // console.log(cord)
                        var cordlat=cord.WGSAIP[1]
                        var cordlon=cord.WGSAIP[0]
                        // console.log(Hdobs,v,'Hdobs,gTrak',SD,HD)
                        if (HD > 1.2){
                            // console.log(gTrak,throbst,'Hdobs,gTrak',thrh_y,thrh_x,thr_y,thr_x,cordlat,cordlon)

                            var nm=v.definition + '<br>' + elev + '<br>' + cordlat + ' ' + cordlon + '<br>Distance : ' + throbst.Distance + 'm from ' + thrident;
                            var nmt=v.definition+ '<br>' + elev;
                            var lat,lon,symb= null;
                                    lat =  v.geom.coordinates[1]
                                    lon = v.geom.coordinates[0]
                                    symb = Getsymbol(this.typ,'obst')
                                    // console.log(symb)
                                    var myIcon = L.icon({
                                                iconUrl: symb,
                                                iconSize: [25,25],
                                            })
                                    marker = L.marker([lat,lon],{icon: myIcon})
                                        .addTo(map)
                                        // map.setView([lat,lon],10)
                                        marker.bindTooltip(nmt)
                                        marker.bindPopup(nm)
                        }

                    }

                })
            })
            
        }

        })
        
          
           
    
}

function isback(){
    window.location.href = '/listairport/aoc';
}

function viewframe(id=null){
    var idcht='';
    if (id==null){
        var chrtid=$('#bm_id').val();
        idcht=bm.find(x=>x.chart_id===chrtid).id;

    }else{
        idcht=id;
    }
    console.log(idcht)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=frame&id='+idcht, 'Set Latitude and Longitude', params)
}




</script>
@endsection