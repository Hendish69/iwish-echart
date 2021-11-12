@extends('layouts.app')

@section('template_title')
	En-Routes
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
                        <h3 class="nk-block-title page-title">En-Routes</h3>
                    </div>
                </div>
            </div>
            <div class="nk-block-head-content" id="mycheckbox">
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" checked="checked" type="checkbox" id="atsdom" value="atsdom">
                    <label class="form-check-label" for="atsdom">Domestic Routes</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="atsintl" value="atsintl">
                    <label class="form-check-label" for="atsintl">International Routes</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="atsrnav" value="atsrnav">
                    <label class="form-check-label" for="atsrnav">RNAV Routes</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="atsvfr" value="atsvfr">
                    <label class="form-check-label" for="atsvfr">VFR/Helicopter Routes</label>
                </div>
            </div>
            <!-- MAP -->
            <div id="mapid" style="width:100%; height:100% !important; min-height:550px !important;" class="site-content"></div>
                <!-- <div class="nk-block-between">
                    <form class="form-inline col-sm-6 mt-3">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-search"></em>
                                </div>
                                <input type="text" class="form-control form-round" v-model="search" id="default-03" placeholder="Search..">
                            </div>
                        </div>
                        &nbsp;
                    </form>
                </div> -->
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>Route Name</th>
                                    <th>Start Point</th>
                                    <th>End Point</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody id="atslist">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
var map; var center,zoomshow=false,modalvol=false;
var dom = [];
var intl = [];
var rnav = [];
var vfr = [];
var enr =@json($enroute);
var allenr =@json($alldataenr);
var sigmet =@json($sigmet);

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
            Drawpolygon(lines,raw,contentString,0.5,0.5)
            
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
window.addEventListener('load', (event) => {
    reloadMarkers(enr);
    });
function Drawpolygon(poly,polyid,polyinfo,strokeopac,fillopac){
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
var pathatsident= pathpop()   + '/api/ats/list/XX/';
var dataString = {'ctry': 'ID', 'deleted': 0};
var routes=[];type='';atslines=[];colormark=[];markerspoint=[];point1=[];point2=[];
// if (enr.length > 0){
    // enr.forEach(v=>{
    //     if ( v.ats_ident.substr(0,1) == "W" && v.type == 'W' ) {
    //             v['atstype']='DOM';
    //             dom.push( v );
    //         }
    //         if ( v.ats_ident.substr(0,1) !== "W" && v.type == 'W' ) {
    //             v['atstype']='INTL';
    //             intl.push( v );
    //         }
    //         if ( v.type == 'R' ) {
    //             v['atstype']='RNAV';
    //             rnav.push( v );
    //         }
    //         if (  v.type == 'V' ) {
    //             v['atstype']='VFR';
    //             vfr.push( v );
    //         }
        
    //     })

// }
$.ajax({
        url: pathatsident,
        data: {ctry: 'ID', deleted: 0},
        type: "json",
        method: "GET",

        success: function (result) {
            $.each(result.data, function (k, v) {
               
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
            });
            listtable(dom);;
        }

    });
var pathdetail= pathpop()   + '/api/ats/listall/';
var linesdom = []; linesintl=[]; linesrnav=[]; linesvfr = [];
$.ajax({
        url: pathdetail,
        // data: {'ctry' : 'ID'},
        type: "json",
        method: "GET",

        success: function (result) {
            $.each(result.data, function (k, v) {
                // console.dir(v)
                routes.push(v)
                        if ( v.ats_ident.substr(0,1) == "W" && v.type == 'W' ) {
                            v['atstype']='DOM';
                            linesdom.push( v );
                        }
                        if ( v.ats_ident.substr(0,1) !== "W" && v.type == 'W' ) {
                            v['atstype']='INTL';
                            linesintl.push( v );
                        }
                        if ( v.type == 'R' ) {
                            v['atstype']='RNAV';
                            linesrnav.push( v );
                        }
                        if (  v.type == 'V' ) {
                            v['atstype']='VFR';
                            linesvfr.push( v );
                        }
                    
                   
                // let lines = [
                //     {lat: + v.geom.coordinates[0][1],lng: + v.geom.coordinates[0][0]},
                //     {lat: + v.geom.coordinates[1][1],lng: + v.geom.coordinates[1][0]}];
                // console.log('flightPlanCoordinates',lines);
                
                // console.dir(lines);
            });
            Drawline(linesdom);
        }

    });
    

function Label(opt_options) {
    // Initialization
    this.setValues(opt_options);

    // Label specific
    var span = this.span_ = document.createElement('span');
    span.style.cssText = 'position: relative; left: -50%; top: -8px; ' +
                        'white-space: nowrap; border: 1px solid blue; ' +
                        'padding: 2px; background-color: white';

    var div = this.div_ = document.createElement('div');
    div.appendChild(span);
    div.style.cssText = 'position: absolute; display: none';
}
Label.prototype = new google.maps.OverlayView();

function listtable(data){
    $("#atslist").empty();
    data.forEach(a=>{
        // console.log(a)
    // function atsfunction(a,idx){
        // console.log(a)
        hasil = '<tr class="nk-tb-item">'+
        '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                        '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-info" id="'+ a.ctry +'" onclick="showdetail(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                    '</ul>'+
                    '</div>'+
            '</div>'+
        '</td>'+
        '<td>' + a.ats_ident + '</td><td>' + a.point_1 + '</td><td>' + a.point_2 + '</td><td>' + a.level + '</td></tr>';
        $("#atslist").append(hasil);
        // hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-info" id="'+ a.ctry + '" onClick="showdetail(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + a.ats_ident + '</td><td>' + a.point_1 + '</td><td>' + a.point_2 + '</td></tr>'
        // $("#atslist").append(hasil);

    })
}


function showdetail(id){
    // console.log(id)
    for (var i=0; i<colormark.length; i++) {
        colormark[i].setMap(null);
    }
    colormark = [];
    reloadMarkers(routes);
    
    for (var i = 0; i < atslines.length; i++) {
            if(atslines[i].id == id) //Or whatever that you require
            {
                // console.dir(atslines[i].LatLngBounds(),id)
                let atspp = new google.maps.Polyline(atslines[i]);
                var bounds = new google.maps.LatLngBounds();
                for (var x = 0; x < atspp.getPath().getLength(); x++) {
                        bounds.extend(atspp.getPath().getAt(x));
                }
                    atspp.strokeColor= "#FF0000",
                    atspp.strokeOpacity= 1,
                    atspp.strokeWeight=5,

                    infowindow.setContent(atspp.titel);
                    infowindow.setPosition(bounds.getCenter());
                    infowindow.open(map);
                    colormark.push(atspp);
                // map.fitBounds(bounds);
                // map.center(bounds.getCenter())
                window.scrollTo(0,0);
                // map.setCenter(bounds.getCenter());
                // map.setZoom(6);
                // atspp.setMap(map);
            }
    }
}
function unshowMarkers(id){
    for (var i=0; i < atslines.length; i++) {
        atslines[i].setMap(null);
    }
    atslines=[];
    $("#atslist tr").remove();
    $('.checkbox:checkbox:checked').each(function(i){
        showMarkers($(this).val());
    });
}

$(".checkbox").change(function() {
    initMap();
    // if(this.checked) {
    //   showMarkers(this.id);
    // }else{
    unshowMarkers(this.id)
    // }  
});

function reloadMarkers(data) {
 // Loop through markers and set map to null for each
 for (var i=0; i<atslines.length; i++) {
    atslines[i].setMap(null);
 }
 atslines = [];
 $('.checkbox:checkbox:checked').each(function(i){
//    console.log($(this).val())
      showMarkers($(this).val());
  });
}
function showMarkers(id) {
  switch(id) {
    case "atsdom":
        Drawline(linesdom);
        listtable(dom);
    break;
    case "atsintl":
        Drawline(linesintl);
        listtable(intl);
    break;
    case "atsrnav":
        Drawline(linesrnav);
        listtable(rnav);
    break;
    case "atsvfr":
        Drawline(linesvfr);
        listtable(vfr);
    break;
  }
}

function initMap() {
  
  var mapOptions = {
    center: new google.maps.LatLng(-2.85, 118.1),
    zoom: 5,
    // mapTypeId: "terrain",
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
  $("#atslist tr").remove();
  map.addListener('zoom_changed', function() {
    reloadMarkers(routes);
    // console.log(map.getZoom())
   
    // if (map.getZoom() >= 6){
    //     showlabel();
    
    // }else{
    //     removelabel()
    // }
  });
  
  
  sigmet.forEach(s=>{
    //   console.log(s)
    if (s.geometry){
        var geo=s.geometry.coordinates;
        viewallsigmet(geo,s.raw,s.sigmet_type)
        
    }
    // console.log(s);
})


}

// infowindow
// var bounds = new google.maps.LatLngBounds();
var marks=[];
var  infowindow = new google.maps.InfoWindow();
initMap();
</script>
@endsection
