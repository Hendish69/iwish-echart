@extends('layouts.app')

@section('template_title')
Volcano
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <a type="button" onclick="aboutvol('aboutvolcano')" data-toggle="modal">
                                <h3>Volcano</h3>
                            </a>
                            <h6 class="nk-block-des text-soft">Volcano Observatory Notice for Aviation (VONA)</h6>
                        </div>
                         
                        <div class="nk-block-head-content" id="overlaybtn" style="visibility: hidden">
                            <div class="custom-control custom-checkbox">
                                <label class="form-check-label"><strong>Overlay</strong></label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input arptoptioncheckbox" type="radio" name="flexRadioDefault" id="arptoverlay">
                                <label class="form-check-label" for="arptoverlay"><strong>Airport</strong></label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input atsoptioncheckbox" type="radio" name="flexRadioDefault" id="atsoverlay">
                                <label class="form-check-label" for="atsoverlay"><strong>En-Route</strong></label>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block-between">
                        <div class="nk-block-head-content" id="reloadcheckbox" style="visibility: hidden">
                            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                        </div>
                        <div class="nk-block-head-content" id="atscheckbox" style="visibility: hidden">
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxats" checked="checked" type="checkbox" name="ats" id="atsdom" value="atsdom">
                                <label class="form-check-label" for="atsdom">Domestic Routes</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxats" type="checkbox" id="atsintl" name="ats" value="atsintl">
                                <label class="form-check-label" for="atsintl">International Routes</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxats" type="checkbox" id="atsrnav" name="ats" value="atsrnav">
                                <label class="form-check-label" for="atsrnav">RNAV Routes</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxats" type="checkbox" id="atsvfr" name="ats" value="atsvfr">
                                <label class="form-check-label" for="atsvfr">VFR/Helicopter Routes</label>
                            </div>
                        </div>
                        <div class="nk-block-head-content" id="arpcheckbox" style="visibility: hidden">
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxarp" checked="checked" type="checkbox" name="arpt" id="arptvol2" value="arptvol2">
                                <label class="form-check-label" for="arptvol2">VOL 2</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxarp" type="checkbox" id="arptvol3" name="arpt" value="arptvol3">
                                <label class="form-check-label" for="arptvol3">VOL 3</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxarp" type="checkbox" id="arptvol4" name="arpt" value="arptvol4">
                                <label class="form-check-label" for="arptvol4">VOL 4</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input checkboxarp" type="checkbox" id="arptvol5" name="arpt" value="arptvol5">
                                <label class="form-check-label" for="arptvol5">VOL 5</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-dialog-lg" role="document" id="aboutvolcano" style="visibility: hidden">
                        <div class="modal-content">
                            <div class="modal-footer bg-gray">
                                <h6 class="modal-title text-black-50">:: Volcano Observatory Notice for Aviation ::</h6>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    <li align="justify"><strong>VONA</strong> stands for Volcano Observatory Notice for Aviation. It issues reports for changes, both increases and decreases, in volcanic activities, providing a description on the nature of the unrest or eruption, potential or current hazards as well as likely outcomes. See the following link (USGS) for further details. The Center for Volcanology and Geological Hazard Mitigation (CVGHM) under the Geological Agency of the Indonesian Ministry of Energy and Mineral Resources produced VONA's reports based on analysis of data from the agency's monitoring networks as well as from direct observations. VONA's alert levels are color-coded to indicate the different types of notifications addressing specific informative needs. The reports are disseminated via email to national and international stakeholders in the aviation sector. Other interested parties can avail of them through email subscription. All notifications are publicly available online.</li>
                                </ul>
                            </div>
                            <div class="modal-footer bg-light">
                                <span class="sub-text">&copy; 2020 IWISHIndonesia.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- MAP -->
                <div id="mapid" style="width:100%; height:100% !important; min-height:600px !important;z-index:auto" class="site-content mt-0">
                </div>
                
                <div class="nk-block-between">
                    <div class="nk-block-head-content"  id="forecastbutton">

                    </div>
                    <div class="nk-block-head-content">
                        <div class="custom-control custom-checkbox">
                            <input class="form-check-input checkbox" checked="checked" type="checkbox" id="vgreen" value="vgreen">
                            <label class="form-check-label" for="vgreen"><strong style="color:#179638; font-weight:bolder;">GREEN</strong></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="form-check-input checkbox" checked="checked" type="checkbox" id="vyellow" value="vyellow">
                            <label class="form-check-label" for="vyellow"> <strong style="color:#e7d107; font-weight:bolder;">YELLOW</strong></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="form-check-input checkbox" checked="checked" type="checkbox" id="vorange" value="vorange">
                            <label class="form-check-label" for="vorange"><strong style="color:#FF9100; font-weight:bolder;">ORANGE</strong></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="form-check-input checkbox" checked="checked" type="checkbox" id="vred" value="vred">
                            <label class="form-check-label" for="vred"> <strong style="color:#CC0505; font-weight:bolder;">RED</strong></label>
                        </div>
                    </div>
                </div>
                <div class="row mt-1" id="listofvolcano" style="visibility:visible">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark" id="judultable">
                            </thead>
                            <tbody id="vollist">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-1" id="listofforecast" style="visibility:hidden">
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
$('#aboutvolcano').hide();
$('#overlaybtn').hide();
$('#arpcheckbox').hide();
$('#atscheckbox').hide();
$("#reloadcheckbox").hide();
$("#listofforecast").hide();
var map; var center,zoomshow=false,modalvol=false;
var green = [];
var yellow = [];
var orange = [];
var red = [];
var markers = []; // Create a marker array to hold your markers
var dom = [];
var intl = [];
var rnav = [];
var vfr = [];
var vol2 = [];
var vol3 = [];
var vol4 = [];
var vol5 = [];
var cntr=[];
var offsetpoly=[];lstarpt=[];
var path=pathpop();cr=[];jdl='';arrats=[]
var volcanoes=[];forecastpoly=[];
var vol =@json($volcanoes);
var allenr =@json($alldataenr);
var airports =@json($airports);
var sigmet=@json($sigmet);
// console.log(sigmet)
jdl ='<tr align="center">' +

'<th>No</th>'+
'<th>Number</th>'+
        '<th>Name</th>'+
        '<th>Status</th>'+
        '<th>Region</th>'+
        '<th>Last Update</th>'+
    '</tr>'
    $("#judultable").append(jdl);

    vol.forEach(v=>{

        volcanoes.push(v);
        switch(v.va_status){
            case "1":
                green.push(v);
                break;
            case "2":
                yellow.push(v);
                break;
            case "3":
                orange.push(v);
                break;
            case "4":
                red.push(v);
                break;
        }
        // console.log('ada',v)
        
    });


function setMarkersvolcano(vol){
    $("#vollist").empty();
    var ctn=[];
   
    for (let i = 0; i < vol.length; i++) {
        var va=vol[i];lastd='---';	
	//console.dir(va);
        if (va.va_last_update){
            // console.log('ada',vol[i])
            lastd=DateFormat(new Date(va.va_last_update)) + ' ' + new Date(va.va_last_update).toLocaleTimeString();
        }

        var ttl = '';sts='';
        var ident='';nm='';def=''
            ident=vol[i].va_no;
            nm=vol[i].va_name;
            def=vol[i].va_subregion + ', ' + vol[i].va_state;
            ttl=vol[i].va_name;
            sts=getvolcanocolor(vol[i].va_status);
        var clat=SetWgs(vol[i].va_lat_deg,vol[i].va_lat_min,vol[i].va_lat_sec,vol[i].va_lat_ns);
        var clon=SetWgs(vol[i].va_lon_deg,vol[i].va_lon_min,vol[i].va_lon_sec,vol[i].va_lon_ew);
        cr=SetCoordinatebyDecimal(clon,clat);
        var cntrvol= new google.maps.LatLng({lat: clat,lng: clon});
        ctn[i]= volcanoinfo(vol[i]);
        var ic = setvolIcon(volsymbol[vol[i].va_status].icon);
        var marker = new google.maps.Marker({
            position: cntrvol,
            icon: ic,
            title: ttl,
            map: map,
            clickable:true,
            id:vol[i].va_no,
            info:ctn[i]
        });

        var hasil='';
       
        // if(vol[i].cdm == null){
        //     hasil = '<tr><td>' + ident + '</td><td>' + nm + '</td><td>' + sts + '</td><td>' + def + '</td><td>' + lastd + '</td></tr>'
        // }else{
        //     if (vol[i].ashtam.length==0){
        //         hasil = '<tr><td>' + ident + '</td><td>' + nm + '</td><td>' + sts + '</td><td>' + def + '</td><td>' + lastd + '</td></tr>'
        //     }else{

                hasil = '<tr class="nk-tb-item"><td style="cursor:pointer"><a onclick="showdetail('+ ident +')">' + Number(i+1) + '</a></td><td style="cursor:pointer"><a onclick="showdetail('+ ident +')">' + ident + '</a></td><td style="cursor:pointer"><a onclick="showdetail('+ ident +')">' + nm + '</a></td><td style="cursor:pointer"><a onclick="showdetail('+ ident +')">' + sts + '</a></td><td style="cursor:pointer"><a onclick="showdetail('+ ident +')">' + def + '</a></td><td style="cursor:pointer"><a onclick="showdetail('+ ident +')">' + lastd + '</a></td></tr>'
        //     }
        // }
            $("#vollist").append(hasil);
    
        // ctn[i] = "<strong>"+"Mountain Name : "+data_vas[i].va_name+"</strong><br>";
        makeInfoWindow(map, infowindow, ctn[i], marker);
        markers.push(marker);
  }  

  sigmet.forEach(s=>{
    if (s.geometry && s.sigmet_type=='vulcanic_ash'){
        // if (s.geometry){
        //   console.log(s)
        var geo=s.geometry.coordinates;
        viewallsigmet(geo,s.raw,s.sigmet_type)
        
    }
    })

}

function setvolIcon(uri){
    var image = {
        url: uri, 
        size: new google.maps.Size(35, 35), 
        origin: new google.maps.Point(0, 0), 
        anchor: new google.maps.Point(16, 16),
        scaledSize: new google.maps.Size(35, 35)
        };
    return image;
}

function volcanoinfo(va){
   
    // console.dir(cr);
    
            let txtcolor, btncdm='',btnforecast='',btndetail='';
            txtcolor=getvolcanocolor(va.va_status)
            if(va.cdm !== null){
                if(this.$piagroup =='1'){
                    btncdm='<button class="btn btn-sm btn-primary tocdm" data-va_no= '+ va.va_no +'">CDM</button> ';	 
                }else{
                    btncdm='<button class="tocdm btn btn-sm btn-primary" onclick="tocdm('+ va.va_no +')"">CDM</button> ';
                }
                // btndetail='<button class="btn  btn-sm btn-success" onclick="">Info</button> ';
            }
                if (va.ashtam.length > 0){
                    btnforecast='<button class="showforecast btn  btn-sm btn-warning" onclick="showforecast('+ va.va_no +')""">Forecast</button> ';
                }

        return '<div width="300px !important; padding:30px 30px 30px 30px;"><b style="font-size:20px;">' + va.va_name + '</b><br><br><table width="300px !important;">'+
                    '<tr>'+
                    '<td align="left" width="130" style="font-weight:bold;"><b>Status</b></td>'+
                    '<td>:</td>'+
                    '<td style="font-weight:bold">'+ txtcolor + '</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Volcano Number</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.va_no+'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Volcano Location</b></td>'+
                    '<td>:</td>' +
                    '<td>'+ cr.WGSSIDSTAR[1] + ' ' + cr.WGSSIDSTAR[0] +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Area</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.va_subregion + ', ' + va.va_state +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Summit Elevation</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.va_summit_elevm +' M</td>'+
                '</tr>'+
            '</table>'+
            '<br><br>' + btncdm  + btnforecast;
  }
  var markerGroups = {
    "green": [],
    "yellow": [],
    "orange": [],
    "red": []
};

function initMap() {
  
    var mapOptions = {
        center: new google.maps.LatLng(-2.548926, 114.0148634),
        zoom: 5,
        mapTypeId: "terrain",
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
                stylers: [{ visibility: "off" }],
            },
            { featureType: "administrative.country",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "administrative.locality",
                elementType: "labels",
                stylers: [ { "visibility": "off" } ]
            },
        ]
    }
    map = new google.maps.Map(document.getElementById("mapid"), mapOptions);

    google.maps.event.addDomListener(map, "click", () => {
    if (zoomshow==true){
        initMap();
    }
    });
   
    $("#vollist tr").remove();
    $("#forecastbutton").html("");
    map.addListener('zoom_changed', function() {
        reloadMarkers(volcanoes);
        // untuk merubah ukuran symbol wpt and navaid sesaui zoom factor
        unshowAtsMarkers();
        // if (map.getZoom() >= 6){
        //     showlabel();
        // }else{
        //     removelabel()
        // }
    });
    setMarkersvolcano(volcanoes); 
   

}

function viewallsigmet(line,raw,type){
    // console.log(line,raw);
    var pnt1='';pnt2='';pnt3='';pnt4='';asnote='';flength='';
    line.forEach(a=>{
        // console.log(a);
            var plat='';plon='';plat1='';plon1='';
            let lines =[];volpoints='POLYGON((';
            // plat=a[1];plon=a[0];
            for (let i =0;i < a.length;i++){
                if (i==0){
                    plat1= a[i][1];
                    plon1= a[i][0];
                }
                plat= a[i][1];
                plon= a[i][0];

                    lines.push({lat: + plat,lng: +plon});
                    if (volpoints =='POLYGON(('){
                        volpoints += plon + ' ' + plat;
                    }else{
                        volpoints += ',' + plon + ' ' + plat;
                    }
                // console.log(sp[i],sp[i].length);
            }
            if (plon !== plon1 && plat !== plat1){
                volpoints += ',' + plon1 + ' ' + plat1 +'))';
            }else{
                volpoints += '))';
            }
            // console.log(raw)
            // if (flength==67 || flength==82){
            //     asnote=a.ashtam_desc.substr(0,9);
            // }else{
            //     asnote=a.ashtam_desc.substr(0,18);

            // }
            var contentString = sigmettinfo(raw,type);
            Drawpolygonsigmet(lines,raw,contentString,0.5,0.5)
            
            // getoffset(volpoints);
    });

    window.scrollTo(0,0);
}
function sigmettinfo(raw,type){
    var infox='';
            infox= '<div width="500px !important; padding:10px 10px 10px 10px;"><b style="font-size:20px;">SIGMET INFO</b><br><b style="font-size:12px;"> SIGMET TYPE : '+type + '</b><br><table width="500px !important;">'+'<tr>'+
                '<td><b>RAW DATA : </b></td>'+
            '</tr>';;
    raw.forEach(r=>{
        // console.log(r)
        infox += 
            '<tr>'+
                '<td>' + r+'</td>'+
            '</tr>';
             
    })
 

          return infox;

}
function Drawpolygonsigmet(poly,polyid,polyinfo,strokeopac,fillopac){
    const polygon = new google.maps.Polygon({
        id:polyid,
        popup:polyinfo,
        paths: poly,
        geodesic: true,
        strokeColor: "#872929",
        strokeOpacity:strokeopac,
        strokeWeight: 2,
        fillColor: "#872929",
        name:'airspace_nameairspace_type' ,
        fillOpacity: fillopac,
    });
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < polygon.getPath().getLength(); i++) {
                bounds.extend(polygon.getPath().getAt(i));
        }
            
            // map.fitBounds(bounds);
            polygon.setMap(map);
            // forecastpoly.push(polygon);
            // console.log(map.getZoom())
            var  infotitle = new google.maps.InfoWindow();
            google.maps.event.addListener(polygon, 'click', mousefn);
            google.maps.event.addListener(polygon, 'mouseout', function(evt) {
                // this.setOptions({fillOpacity: fillopac,strokeOpacity:strokeopac});
                infotitle.close();
                infotitle.opened = false;
            });
            function mousefn(evt) {
                // this.setOptions({fillOpacity: 0,strokeOpacity:2});
                infotitle.setContent(polyinfo);
                infotitle.setPosition(evt.latLng); // or evt.latLng
                infotitle.open(map);
            }
            // map.setZoom(map.getZoom()-2)
}

// infowindow
// var bounds = new google.maps.LatLngBounds();
var  infowindow = new google.maps.InfoWindow();
function makeInfoWindowEvent(map, infowindow, info, marker) {
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(info);
    // infowindow.position(position);
    infowindow.open(map, marker);
  });
}
function showdetail(id){ 
    // initMap();

//     let idx = markers.findIndex(x => x.id===id.toString());
//   console.log(markers[0],idx);
    for (var i=0; i<markers.length; i++) {
    // console.log(markers[i].id,id);
        if (markers[i].id==id){
            zoomshow=true
        
            var cntrvol= markers[i].position; 
            map.setZoom(10);
            map.setCenter(cntrvol);
            window.scrollTo(0,0);
            // circle = new google.maps.Circle({
            //         map: map,
            //         clickable: false, 
            //         radius: 10000,
            //         fillColor: '#fff',
            //         fillOpacity: .35,
            //         strokeColor: '#313131',
            //         strokeOpacity: .4,
            //         strokeWeight: .8,
            //         center: cntrvol,
            //     });
            infowindow.setContent(markers[i].info);
            infowindow.setPosition(cntrvol);
            infowindow.open(map);
        }
    }
    setMarkersvolcano(volcanoes); 
}


// Create markers.
function setMarkers(data_vas){
    var ctn=[];
    for (let i = 0; i < data_vas.length; i++) {
        var ic = setIcon(icons[data_vas[i].type].icon);
        ctn[i]= volattribute(data_vas[i]);
        var marker = new google.maps.Marker({
            position: data_vas[i].position,
            icon: ic,
            // icon: icons[volcanoes[i].type].icon, 
            title: data_vas[i].va_name,
            map: map,
            clickable:true,
            id:data_vas[i].va_no,
            info:ctn[i]
        });
        // bounds.extend(marker.position);
        
        // ctn[i] = "<strong>"+"Mountain Name : "+data_vas[i].va_name+"</strong><br>";
        makeInfoWindowEvent(map, infowindow, ctn[i], marker);
        markers.push(marker);
    }  
}

function reloadarpt() {
    for (var i=0; i<arptmarkers.length; i++) {
        arptmarkers[i].setMap(null);
    }
    arptmarkers = [];
}

function reloadatsline() {
    removelabel();
    for (var i=0; i<pntmarkers.length; i++) {
        pntmarkers[i].setMap(null);
    }
    pntmarkers = [];
    
    for (var i=0; i<atslines.length; i++) {
        atslines[i].setMap(null);
    }
    atslines = [];
}
function reloadforecast() {
    $('#overlaybtn').hide();
    $('#arpcheckbox').hide();
    $('#atscheckbox').hide();
    $("#reloadcheckbox").hide();
    uncheckall();
    reloadatsline();
    reloadarpt();
    
 // Loop through markers and set map to null for each
    for (var i=0; i<forecastpoly.length; i++) {
        forecastpoly[i].setMap(null);
    }
    forecastpoly = [];

}
function uncheckall(){
    $('#arpcheckbox').prop('checked', false);
    $('#atscheckbox').prop('checked', false);
    $('#arptvol3').prop('checked', false);
    $('#arptvol4').prop('checked', false);
    $('#arptvol5').prop('checked', false);
    $('#atsintl').prop('checked', false);
    $('#atsrnav').prop('checked', false);
    $('#atsvfr').prop('checked', false);
    
}

function reloadMarkers(data) {
 // Loop through markers and set map to null for each
    for (var i=0; i<markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $("#vollist tr").remove();
    $('.checkbox:checkbox:checked').each(function(i){
    // console.log($(this).val())
        showMarkers($(this).val());
    });
}
function showMarkers(id) {
    switch(id) {
        case "vgreen": setMarkersvolcano(green); break;
        case "vyellow": setMarkersvolcano(yellow); break;
        case "vorange": setMarkersvolcano(orange); break;
        case "vred": setMarkersvolcano(red); break;      
    }
}


function reloadAtsMarkers(id){
    for (var i=0; i < pntmarkers.length; i++) {
        pntmarkers[i].setMap(null);
    }
    pntmarkers=[];
}

function unshowAtsMarkers(id){
    reloadAtsMarkers();
    // removelabel();
    for (var i=0; i < atslines.length; i++) {
        atslines[i].setMap(null);
    }
    atslines=[];
    $('.checkboxats:checkbox:checked').each(function(i){
        reloadAtslines($(this).val());
    });
}

function reloadAtslines(id) {
    console.log('reloadAtslines',id)
   
  switch(id) {
    case "atsdom":
        Drawline(dom);
        // showlabel();
    break;
    case "atsintl":
        Drawline(intl);
        // showlabel();
    break;
    case "atsrnav":
        Drawline(rnav);
        // showlabel();
    break;
    case "atsvfr":
        Drawline(vfr);
        // showlabel();
    break;
  }
}



function unshowArptMarkers(){
    for (var i=0; i < arptmarkers.length; i++) {
        arptmarkers[i].setMap(null);
    }
    arptmarkers=[];
    $('.checkboxarp:checkbox:checked').each(function(i){
        reloadArptMarkers($(this).val());
    });
}

function reloadArptMarkers(id) {
  switch(id) {
    case "arptvol2": setMarkersArpt(vol2); break;
    case "arptvol3": setMarkersArpt(vol3); break;
    case "arptvol4": setMarkersArpt(vol4); break;
    case "arptvol5": setMarkersArpt(vol5); break;      
  }
}

$(".checkbox").change(function() {
    $('#overlaybtn').hide();
    $('#arpcheckbox').hide();
    $('#atscheckbox').hide();
    $("#reloadcheckbox").hide();
    initMap();
    reloadMarkers(this.id);
});

function backtolist() {
    $('#overlaybtn').hide();
    $('#arpcheckbox').hide();
    $('#atscheckbox').hide();
    $("#reloadcheckbox").hide();
    initMap();
    reloadMarkers();
}

$(".arptoptioncheckbox").change(function() {
    arpsub();
    unshowArptMarkers();
});
$(".atsoptioncheckbox").change(function() {
    atssub();
    unshowAtsMarkers(this.id);
});
$(".checkboxats").change(function() {
    // console.log(this)
    unshowAtsMarkers(this.id);
});
$(".checkboxarp").change(function() {
    // console.log(this)
    unshowArptMarkers(this.id);
});
$('.checkboxarp:checkbox:checked').each(function(i){
    reloadArptMarkers($(this).val());
});
$('.checkboxats:checkbox:checked').each(function(i){
    reloadAtslines($(this).val());
});


initMap();



function showinfo(id){
    console.log('INFO DETAIL VOLCANO(CDM LOG)')
}

function tocdm(va_no){

    window.scrollTo(0,0);
    window.location.href = '/cdmlogdetail/' + va_no;
  // return view('pages.volcano.cdmdetail');
//   return view('daftarPeriksa')->with(['req' => $req]);
//   window.location.href = '/cdmlog/';
//  qcdmdetail(va_no)
 // history.back(1);

}
function isHidden(el) {

    if ($('#'+el).css('display') == 'none') {
        return true;
    }else{
       
        return false;
    }
}
function closeforecast(){
    $("#forecastbutton").html("");
    // initMap();
    // reloadMarkers();
    aboutvol("listofforecast")
    aboutvol("listofvolcano")
    setMarkersvolcano(volcanoes);
    window.scrollTo(0,0);
}
function showforecast(vanmbr){
    infowindow.close()
    var disp=isHidden("listofforecast");
    // console.log(disp)
    $("#forecast tr").remove();
    if (disp==true){
        aboutvol("listofforecast")
        aboutvol("listofvolcano")
        $("#forecastbutton").html("");
        jdl ='<div class="col-md-12">'+
                '<btn class="btn btn-dim btn-sm btn-dark" onclick="closeforecast()">Close</btn>'+
                '<table class="datatable-init table table-bordered table-hover" id="table-content">'+
                    '<thead class="thead-dark">'+
                        '<tr align="center">' +
                            '<th>Volcano</th>'+
                            '<th>Update Time</th>'+
                            '<th>Number</th>'+
                            '<th>Alert Code</th>'+
                            '<th>Direction</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody id="forecast">'+
                    '</tbody>'+
                '</table>'+
                '</div>';
                $("#listofforecast").append(jdl);
    }
   
    
    index = volcanoes.findIndex(x => x.va_no ===vanmbr.toString());
    // console.log(index);
    // console.log(volcanoes[index])
    var noasthamid='';nono=0;
    volcanoes[index].ashtam.forEach(a=>{
        if (a.ashtam_ahve !== "NIL"){
            var n = a.ashtam_ahve.search("VA NOT IDENTIFIABLE");
            if (n == -1){
                if (nono==0){
                    noasthamid=a.ashtam_id;
                }
                nono++
// console.log(a.ashtam_id,noasthamid)
                let isitbl = '<tr class="nk-tb-item"><td style="cursor:pointer"><a onclick="forecastdetail('+ a.ashtam_id + ',' + index + ')">' + a.ashtam_volcano + '</a></td><td style="cursor:pointer"><a onclick="forecastdetail('+ a.ashtam_id + ',' + index + ')">' + DateFormat(new Date(a.ashtam_update_time)) + ' ' + new Date(a.ashtam_update_time).toLocaleTimeString() + '</a></td><td style="cursor:pointer"><a onclick="forecastdetail('+ a.ashtam_id + ',' + index + ')">' + a.ashtam_number + '</a></td><td style="cursor:pointer"><a onclick="forecastdetail('+ a.ashtam_id + ',' + index + ')">' + a.ashtam_alert_code + '</a></td><td>' + a.ashtam_ash_direction + '</a></td></tr>'
                $("#forecast").append(isitbl);
                // console.log(a);
            }
        }
    });
    if (noasthamid !== ''){
        // console.log(noasthamid,index)
        forecastdetail(noasthamid,index);

    }

}


function Drawpolygon(poly,polyid,polyinfo,strokeopac,fillopac){
    const polygon = new google.maps.Polygon({
        id:polyid,
        popup:polyinfo,
        paths: poly,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity:strokeopac,
        strokeWeight: 2,
        fillColor: "#FF0000",
        name:'airspace_nameairspace_type' ,
        fillOpacity: fillopac,
    });
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < polygon.getPath().getLength(); i++) {
                bounds.extend(polygon.getPath().getAt(i));
        }
            
            map.fitBounds(bounds);
            polygon.setMap(map);
            forecastpoly.push(polygon);
            // console.log(map.getZoom())
            var  infotitle = new google.maps.InfoWindow();
            google.maps.event.addListener(polygon, 'click', mousefn);
            google.maps.event.addListener(polygon, 'mouseout', function(evt) {
                // this.setOptions({fillOpacity: fillopac,strokeOpacity:strokeopac});
                infotitle.close();
                infotitle.opened = false;
            });
            function mousefn(evt) {
                // this.setOptions({fillOpacity: 0,strokeOpacity:2});
                infotitle.setContent(polyinfo);
                infotitle.setPosition(evt.latLng); // or evt.latLng
                infotitle.open(map);
            }
            map.setZoom(map.getZoom()-2)
}
function getvolcanocolor(status){
    let color=''
    switch(status){
        case "4":
            color='<strong style="color:#CC0505; font-weight:bolder;">RED</strong>'
            break;
        case "3":
            color='<strong style="color:#FF9100; font-weight:bolder;">ORANGE</strong>'
            break;
        case "2":
            color='<strong style="color:#e7d107; font-weight:bolder;">YELLOW</strong>'
            break;
        case "1":
            color='<strong style="color:#179638; font-weight:bolder;">GREEN</strong>'
            break;
    }
    return color
}
function getats(poly,draw){
// console.log(poly,offsetpoly[0])
    var pathdetail= pathpop()   + '/api/getcontainsats/' + poly;//offsetpoly[0];
    var rts=[];
    var infox ='';
        $.ajax({
                url: pathdetail,
                // data: {'geom' : poly},
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        rts.push(v);
                    })
                    if (draw==true){
                        drawats(rts);
                    }
                }
        })
     
    return rts;

    // console.log('offsetpoly',offsetpoly)
}
function drawats(data){

    var pathdetail= pathpop()   + '/api/ats';
    var routes=[];
    dom=[];intl=[]; rnav=[];vfr=[];
    data.forEach(a=>{
        allenr.forEach(v=>{
            if (v.ctry == a.ctry){
                if ( v.ats_ident.substr(0,1) == "W" && v.type == 'W' ) {
                    v['atstype']='DOM';
                    dom.push( v );
                }
                if ( v.ats_ident.substr(0,1) !== "W" && v.type == 'W' ) {
                    v['atstype']='INTL';
                    intl.push( v );
                }
                if ( v.type == 'R' ) {
                    v['atstype']='RNAV';
                    rnav.push( v );
                }
                if (  v.type == 'V' ) {
                    v['atstype']='VFR';
                    vfr.push( v );
                }
                // routes.push(v);
            }
        })
        

    });

}

function getarpt(poly){
    var pathdetail= pathpop()   + '/api/getcontainsarpt/' + poly;//offsetpoly[0];

    vol2 = [];vol3 = [];vol4 = [];vol5 = [];lstarpt=[];
    $.ajax({
        url: pathdetail,
        // data: {'geom' : poly},
        type: "json",
        method: "GET",
        success: function (result) {
            $.each(result.data, function (k, v) {
                airports.forEach(a=>{
                    if (v.arpt_ident == a.arpt_ident){
                        lstarpt.push(a);
                        if ( a.vol == 2 ) {
                            vol2.push( a );
                        }
                        if ( a.vol == 3 ) {
                            vol3.push( a );
                        }
                        if ( a.vol == 4 ) {
                            vol4.push( a );
                        }
                        if ( a.vol == 5 ) {
                            vol5.push( a );
                        }
                    }

                })
            })
            
        }

        })
    // console.log('offsetpoly',offsetpoly)
}

function getcenter(poly){
    var pathdetail= pathpop()   + '/api/getcenter/' + poly;
    $.ajax({
        url: pathdetail,
        // data: {'geom' : poly},
        type: "json",
        method: "GET",

        success: function (result) {
            var ccc;
            $.each(result.data, function (k, v) {
                
               cntr =v.st_astext.replace('POINT(','').replace(')','').split(' ')
                // Drawpolygon(offlines,'offset60','Offset 60 nm',0.1,0.1);
            });
            // hh=ccc
            // hh =ccc.replace('POINT(','').replace(')','').split(' ')
            // console.log(hh)
        }

    });
    // setTimeout(() => {
        
    //     return hh
    // }, 1000);
}

function getoffset(poly){
    var pathdetail= pathpop()   + '/api/getoffset/' + poly;
    var offlines=[];offsetpoly=[];
    $.ajax({
        url: pathdetail,
        // data: {'geom' : poly},
        type: "json",
        method: "GET",

        success: function (result) {
            
            $.each(result.data, function (k, v) {
                offsetpoly.push(v.st_astext);
                var hh =v.st_astext.replace('POLYGON((','').replace('))','').split(',')
                // console.log(hh);
                for (let i =0;i < hh.length;i++){
                    var oll=hh[i].split(' ');
                    offlines.push({lat: + oll[1],lng: + oll[0]});
                }
                // Drawpolygon(offlines,'offset60','Offset 60 nm',0.1,0.1);
            });
        
            getats(offsetpoly,true);
            getarpt(offsetpoly);
            $("#mycheckbox").html("")
            aboutvol('overlaybtn');
            aboutvol('reloadcheckbox');
            // console.log(arpls)
            // $("#overlaybtn").html(hsl);
        

        }

    });

}

function atssub(){
    $('#arpcheckbox').hide();
    $('#atscheckbox').hide();
    aboutvol('atscheckbox');

}
function arpsub(){
    $('#atscheckbox').hide();
    $('#arpcheckbox').hide();
    aboutvol('arpcheckbox');
}

function forecastdetail(id,idx){
    reloadforecast();
    index = volcanoes[idx].ashtam.findIndex(x => x.ashtam_id ===id);
    // console.log(index,id,idx);
    // console.log(volcanoes[idx].ashtam[index],volcanoes[idx])
    var hsl= '<div class="custom-control custom-checkbox">'+
            '<label class="form-check-label"><strong>Show Forecast</strong></label>'+
        '</div>'
    volcanoes[idx].ashtam[index].forecast.forEach(a=>{
            hsl +='<a onclick="viewforecast(' + a.ashtam_idd + ',' + index +  ',' + idx +')" class="btn btn-dim btn-round btn-sm btn-danger">+' + numeral(a.ashtam_fcst_hr).format('00') +'</a>'
            // console.log(a);
        
    });
    hsl +='<a onclick="viewallforecast(all,' + index +  ',' + idx +')" class="btn btn-dim btn-round btn-sm btn-danger">All Forecast</a>'
    $("#forecastbutton").html(hsl);
    window.scrollTo(0,0);

}
function forecastinfo(ashtam,forecast,dampak,dampak1){
  
    var lat= cntr[1];lon= cntr[0]
    var alt = forecast.ashtam_desc.split(' ')
    var ix = vol.findIndex(x => x.va_no ===ashtam.ashtam_volcano_number);
    
    // console.log('volcavo',vol[ix])
    var altforc=alt[0] +' ' + alt[1]
   if (forecast.ashtam_fcst_hr == 0) {
    altforc=alt[0]
   }
   var infox='';
   infox= '<div width="500px !important; padding:10px 10px 10px 10px;"><b style="font-size:20px;">'+ashtam.ashtam_number + '</b><br><b>Update Time : '+ ashtam.ashtam_update_time +'</b><br><br><table width="500px !important;">'+
                '<tr>'+
                    '<td align="left" width="130">Ash direction</td>'+
                    '<td>:</td>'+
                    '<td><b>' + ashtam.ashtam_ash_direction+'</b></td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left">Source</td>'+
                    '<td>:</td>' +
                    '<td><b>' + ashtam.ashtam_source+'</b></td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left">Altitude</td>'+
                    '<td>:</td>'+
                    '<td><b>' + altforc +'</b></td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left">Forecast hours</td>'+
                    '<td>:</td>'+
                    '<td><b>' + forecast.ashtam_fcst_hr+' hrs</b></td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" width="130">Affected routes</td>'+
                    '<td>:</td>';
                    if (dampak.length > 0){
                        infox+='<table class="datatable-init table table-bordered table-hover" id="table-content">'+
                                '<thead class="thead-dark">'+
                                    '<tr align="center">'+
                                        '<th>Routes</th>'+
                                        '<th>Segment</th>'+
                                        '<th>Distance</th>'+
                                        '<th>Track</th>'+
                                        '<th>Direction</th>'+
                                        '<th>MEA</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="loglist">'
                        dampak.forEach(aa=>{
                        // console.log('AAAAAAAAAAAA',aa)
                            var trk =aa.track_out +'° - '+ aa.track_in +'°';direct='Two Way';mea=aa.mea_out;
                            if (aa.track_in==null){
                                trk =aa.track_out +'°';
                                direct='One Way';
                            }
                            // if (mea==null){
                            //     mea='';
                            // }

                            infox+=  '<tr><td>'+aa.ats_ident+'</td>'+
                                    '<td>'+aa.point_1+' - '+aa.point_2+'</td>'+
                                    '<td>'+aa.dist+'nm</td>'+
                                    '<td>'+trk+'</td>'+
                                    '<td>'+direct+'</td>'+
                                    '<td>'+mea +'</td></tr>';

                        })        
                        infox+= '</tbody>'+
                                '</table>';
                    }else{
                        infox+=  '<td> NIL </td></tr> </table>';
                    }

                    if (dampak1.length > 0){
                        infox+='<table class="datatable-init table table-bordered table-hover" id="table-content">'+
                                '<thead class="thead-dark">'+
                                    '<tr align="center">'+
                                        '<th>Airport</th>'+
                                        '<th>Direction/Distance from '+vol[ix].va_name +'</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="loglist">'
                        dampak1.forEach(aa=>{
                            var hll=''
                            if (aa.vol==5){
                                hll=' (Heliport)'
                            }
                            var icao =aa.icao +' '+ aa.city_name +'/'+ aa.arpt_name + hll;
                            var distarpt = getdistance(Number(vol[ix].va_lat),Number(vol[ix].va_lon), aa.geom.coordinates[1], aa.geom.coordinates[0])
                            var distt='Direction ' +  distarpt.TrackOutTrue + '° and Distance ' +  distarpt.DistinNM +'nm';
                            // console.log(distarpt)
                            infox+=  '<tr><td>'+icao+'</td>'+
                                    '<td>'+distt+'</td>';
                        })        
                        infox+= '</tbody>'+
                                '</table>';
                    }else{
                        infox+=  '<td> NIL </td></tr> </table>';
                    }
                   

            return infox;
  
}
function viewforecast(id,ashidx,volidx){
    // console.log(id,ashidx,volidx);
    var pnt1='';pnt2='';pnt3='';pnt4='';asnote='';flength='';volpoints='POLYGON((';
    reloadforecast();
    // reloadAtslines();
    // reloadArptMarkers();
    // initMap();
   
    // infowindow.position(position);
 

    // console.log(volcanoes[volidx].ashtam[ashidx]);
    volcanoes[volidx].ashtam[ashidx].forecast.forEach(a=>{
        
        flength=a.ashtam_desc.length;
        if (a.ashtam_idd == id){
           
            var sp=a.ashtam_desc.split(' ');
            var psh=false;plat='';plon='';plat1='';plon1='';
            let lines =[];
            for (let i =0;i < sp.length;i++){
                if (sp[i].length == 5){
                    plat= ToDecimalforecast(sp[i]);
                    psh=false;
                }
                if (sp[i].length == 6){
                    plon=  ToDecimalforecast(sp[i]);
                    psh=true;
                }
                if ( psh==true){
                    lines.push({lat: + plat,lng: +plon});
                    if (volpoints =='POLYGON(('){
                        volpoints += plon + ' ' + plat;
                        plat1=plat;plon1=plon;
                    }else{
                        volpoints += ',' + plon + ' ' + plat;
                    }
                    psh=false;plat='';plon='';
                }
                // console.log(sp[i],sp[i].length);
            }
            volpoints += ',' + plon1 + ' ' + plat1 +'))';
            if (flength==67 || flength==82){
                asnote=a.ashtam_desc.substr(0,9);
            }else{
                asnote=a.ashtam_desc.substr(0,18);

            }
            
            getoffset(volpoints);
            getcenter(volpoints);
            // var lsats=getats(volpoints,false);
            var dampak =getats(volpoints,false);
           console.log('cntr',lines)
            setTimeout(() => {
                // console.log('HASIL GET',dampak,lstarpt)
                var info = forecastinfo(volcanoes[volidx].ashtam[ashidx],a,dampak,lstarpt);
                var contentString = info;//asnote + ' (' + numeral(a.ashtam_fcst_hr).format('00') + ' hrs)';
                Drawpolygon(lines,a.ashtam_idd,contentString,0.5,0.5)
            }, 3000);
           
           
            // dampak['arpt'] =getarpt(volpoints);
            // console.log(info)
            
        }
    });
    window.scrollTo(0,0);
    
}

function viewallforecast(id,ashidx,volidx){
    // console.log(id,ashidx,volidx);
    var pnt1='';pnt2='';pnt3='';pnt4='';asnote='';flength='';
    reloadforecast();
    volcanoes[volidx].ashtam[ashidx].forecast.forEach(a=>{
        // console.log(a);
        flength=a.ashtam_desc.length;
            var sp=a.ashtam_desc.split(' ');
            var psh=false;plat='';plon='';
            let lines =[];volpoints='POLYGON((';
            for (let i =0;i < sp.length;i++){
                if (sp[i].length == 5){
                    plat= ToDecimalforecast(sp[i]);
                    psh=false;
                }
                if (sp[i].length == 6){
                plon=  ToDecimalforecast(sp[i]);
                    psh=true;
                }
                if ( psh==true){
                    lines.push({lat: + plat,lng: +plon});
                    if (volpoints =='POLYGON(('){
                        volpoints += plon + ' ' + plat;
                        plat1=plat;plon1=plon;
                    }else{
                        volpoints += ',' + plon + ' ' + plat;
                    }
                    psh=false;plat='';plon='';
                }
                // console.log(sp[i],sp[i].length);
            }
            volpoints += ',' + plon1 + ' ' + plat1 +'))';
            if (flength==67 || flength==82){
                asnote=a.ashtam_desc.substr(0,9);
            }else{
                asnote=a.ashtam_desc.substr(0,18);

            }
            var contentString = asnote + ' (' + numeral(a.ashtam_fcst_hr).format('00') + ' hrs)';
            Drawpolygon(lines,a.ashtam_idd,contentString,0.5,0.5)
            
            // getoffset(volpoints);
    });

    window.scrollTo(0,0);
}

</script>
@endsection
