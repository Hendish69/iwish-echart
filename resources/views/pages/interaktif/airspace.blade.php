@extends('layouts.app')

@section('template_title')
    Airspace
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
                        <h3 class="nk-block-title page-title">Airspace</h3>
                    </div>
                </div>
            </div>
           
            <!-- MAP -->
            <div id="mapid" style="width:100%; height:100% !important; min-height:550px !important;" class="site-content"></div>
            <div class="nk-block-head-content" id="mycheckbox">
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="afiz" value="afiz">
                    <label class="form-check-label" for="afiz">AFIZ</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="atz" value="atz">
                    <label class="form-check-label" for="atz">ATZ</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="cta" value="cta">
                    <label class="form-check-label" for="cta">CTA</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox"  type="checkbox" id="ctr" value="ctr">
                    <label class="form-check-label" for="ctr">CTR</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" checked="checked" type="checkbox" id="fir" value="fir">
                    <label class="form-check-label" for="fir">FIR</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="fss" value="fss">
                    <label class="form-check-label" for="fss">SECTOR</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="mtca" value="mtca">
                    <label class="form-check-label" for="mtca">MTCA</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="tiba" value="tiba">
                    <label class="form-check-label" for="tiba">TIBA</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="tma" value="tma">
                    <label class="form-check-label" for="tma">TMA</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="uta" value="uta">
                    <label class="form-check-label" for="uta">UTA</label>
                </div>
            </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Icao</th>
                                    <th>ATS Unit</th>
                                </tr>
                            </thead>
                            <tbody id="asplist">
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
var afiz = [];
var atz = [];
var cta = [];
var ctr = [];
var fir = [];
var fss = [];
var mtca = [];
var tiba = [];
var tma = [];
var uta = [];

var pathdetail= pathpop()   + '/api/airspace/list/';
var dataString = {'ctry': 'ID', 'deleted': 0};
var tempdom = []; tempintl=[]; temprnav=[]; tempvfr = [];airspaces=[];type='';airspacepolygon=[];colormark=[];
$.ajax({
        url: pathdetail,
        data: {ctry: 'ID', deleted: 0},
        type: "json",
        method: "GET",

        success: function (result) {
            $.each(result.data, function (k, v) {
                // console.dir(v)
                // console.dir(lines)
                airspaces.push(v)
                        type=v.airspace_type
                        
                        switch(v.airspace_type){
                            case "AFIZ":
                                // console.log(v)
                                afiz.push(v);
                                break;
                            case "ATZ":
                                atz.push( v );
                                break;
                            case "CTA":
                                cta.push(v );
                                break;
                            case "CTR":
                                ctr.push(v );
                                break;
                            case "FIR":
                                fir.push( v );
                                break;
                            case "SECTOR":
                                fss.push( v);
                                break;
                            case "MTCA":
                                mtca.push( v );
                                break;
                            case "TIBA":
                                tiba.push( v );
                                break;
                            case "TMA":
                                tma.push( v );
                                break;
                            case "UTA":
                                uta.push( v );
                                break;
                        }
            });
            Drawline(fir);
            // Drawline(tma,'TMA');
        }

    });
    function Drawline(asp){
    var clrline='';opac=0.3;
   
    asp.forEach(a=>{
        // console.log(a);
        switch(a.airspace_type){
        case "UTA":
            clrline="#2f9b98";
            // opac=0.3;
        break;
        case "AFIZ":
            clrline="#670067";
            // opac=0.6;
        break;
        case "ATZ":
            clrline="#4e004e";
            // opac=0.5;
        break;
        case "CTA":
            clrline="#6dc6e9";
            // opac=0.3;
        break;
        case "CTR":
            clrline="#13A835";
            // opac=0.4;
        break;
        case "FIR":
            clrline="#0e0702";
            // opac=0.1;
        break;
        case "FSS":
            clrline="#a00000";
            // opac=0.1;
        break;
        case "MTCA":
            clrline="#6dc6e9";
            // opac=0.5;
        break;
        case "TIBA":
            clrline="#D8F374";
            // opac=0.5;
        break;
        case "TMA":
            clrline="#ec9f12";
            // opac=0.5;
        break;
    }
        let aspnm=a.airspace_name + ' ' + a.airspace_type;
        // console.log(a.geom)
        if (a.geom !== null){

            let lines = TranslatePoly(a.geom.coordinates[0]);
            var contentString = createattr(a);
            const airspace = new google.maps.Polygon({
                id:a.id,
                popup:contentString,
                paths: lines,
                geodesic: true,
                strokeColor: clrline,
                strokeOpacity: opac,
                strokeWeight: 1,
                fillColor: clrline,
                name:a.airspace_name + ' ' + a.airspace_type ,
                fillOpacity: opac,
            });
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0; i < airspace.getPath().getLength(); i++) {
                    bounds.extend(airspace.getPath().getAt(i));
            }
            google.maps.event.addListener(airspace, 'click', function(event) {
                
                infowindow.setContent(contentString);
                infowindow.setPosition(event.latLng);
                infowindow.open(map);
            });
            var  infotitle = new google.maps.InfoWindow();
            google.maps.event.addListener(airspace, 'mouseover', mousefn);
            google.maps.event.addListener(airspace, 'mouseout', function(evt) {
                this.setOptions({fillOpacity: opac});
                infotitle.close();
                infotitle.opened = false;
            });
            function mousefn(evt) {
                // infowindow.setContent("polygon<br>coords:" + bounds.getCenter().toUrlValue(6));
                // console.log(evt)
                this.setOptions({fillOpacity: 0});
                infotitle.setContent(aspnm);
                infotitle.setPosition(evt.latLng); // or evt.latLng
                infotitle.open(map);
            }
            // for (var i = 0; i < airspace.getPath().getLength(); i++) {
            //     bounds.extend(airspace.getPath().getAt(i));
            // }
            airspace.setMap(map);
            airspacepolygon.push(airspace)
        }

                hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-secondary"><i class="icon ni ni-view-grid"></i>Detail</a><a class="btn btn-dim btn-info" onclick="showdetail('+ a.id + ')"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + a.airspace_name + '</td><td>' + a.airspace_type + '</td><td>' + a.icao_acc + '</td><td>' + a.ats_unit + '</td></tr>'
                $("#asplist").append(hasil);

    })
    // initMap();
}
function showdetail(id){
    for (var i=0; i<colormark.length; i++) {
        colormark[i].setMap(null);
    }
    colormark = [];
    reloadMarkers(airspaces);
    let idx = airspacepolygon.findIndex(x => x.id ===id);
    // for (var i = 0; i < airspacepolygon.length; i++) {
        // console.log(airspacepolygon[i].id,id)
    // if(airspacepolygon[i].id == id) //Or whatever that you require
    // {
        let asppp = new google.maps.Polygon(airspacepolygon[idx]);
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < asppp.getPath().getLength(); i++) {
                bounds.extend(asppp.getPath().getAt(i));
        }
        // console.log(asppp.strokeColor)
            asppp.fillColor = "#FF0000";
            asppp.fillOpacity=0.8;
            infowindow.setContent(asppp.popup);
            infowindow.setPosition(bounds.getCenter());
            infowindow.open(map);
            colormark.push(asppp);
        map.fitBounds(bounds);
        // map.setZoom();
        asppp.setMap(map);
        window.scrollTo(0,0);
    // }
//   }
}
function unshowMarkers(id){
    for (var i=0; i < airspacepolygon.length; i++) {
        airspacepolygon[i].setMap(null);
    }
    airspacepolygon=[];
    $("#asplist tr").remove();
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
function createattr(asp){
    // console.log(asp);
    var id=asp.ats_airspace_id;
    var nm=asp.airspace_name + ' ' + asp.airspace_type;
    var unit=asp.ats_unit;
    var aspcls=asp.class[0].asp_class;
    var upper=asp.class[0].upper;
    var lower=asp.class[0].lower;
    var frq=''
    if (asp.freq.length > 0){
        asp.freq[0].callsign[0].segment.forEach(f=>{
            var f = Airspacefreq(f.value[0].freq,f.value[0].unit)
            if (frq==''){
                frq=f;
            }else{
                frq = frq + ',' + f; 
            }
            // console.log(f)
        });

    }

    let txtcolor, btncdm='',btnforecast='',btndetail='';
    // btncdm='<button class="tocdm btn btn-sm btn-primary " onclick="tocdm('+ id +')"">Info</button> ';
                // btndetail='<button class="btn  btn-sm btn-success" onclick="">Info</button> ';
                // btnforecast='<button class="btn  btn-sm btn-warning" onclick="">Forecast</button> ';

    return '<div width="300px !important; padding:30px 30px 30px 30px;"><b style="font-size:20px;">' + nm  + '</b><br><br><table width="300px !important;">'+
                    '<tr>'+
                    '<td align="left" width="130" style="font-weight:bold;"><b>Airspace Class</b></td>'+
                    '<td>:</td>'+
                    '<td>'+ aspcls + '</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Upper</b></td>'+
                    '<td>:</td>' +
                    '<td>'+ upper +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Lower</b></td>'+
                    '<td>:</td>'+
                    '<td>'+ lower +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Unit Providing Service</b></td>'+
                    '<td>:</td>'+
                    '<td>'+ unit +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Frequency</b></td>'+
                    '<td>:</td>'+
                    '<td>'+ frq +'</td>'+
                '</tr>'+
            '</table>'//+
           // '<br><br>' + btncdm  + btndetail + btnforecast;
    }
function reloadMarkers(data) {
 // Loop through markers and set map to null for each
    for (var i=0; i<airspacepolygon.length; i++) {
        airspacepolygon[i].setMap(null);
    }
    airspacepolygon = [];
    $('.checkbox:checkbox:checked').each(function(i){
    //    console.log($(this).val())
        showMarkers($(this).val());
    });
}
function showMarkers(id) {
    switch(id) {
        case "fir":
            Drawline(fir);
        break;
        case "cta":
            Drawline(cta);
        break;
        case "fss":
            Drawline(fss);
        break;
        case "mtca":
            Drawline(mtca);
        break;
        case "tiba":
            Drawline(tiba);
        break;
        case "uta":
            Drawline(uta);
        break;  
        case "tma":
            Drawline(tma);
        break;
        case "ctr":
            Drawline(ctr);
        break;
        case "afiz":
            Drawline(afiz);
        break;
        case "atz":
            Drawline(atz);
        break;
    }
}

function initMap() {

    var mapOptions = {
        center: new google.maps.LatLng(-2.85, 120),
        zoom: 4,
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
    $("#asplist tr").remove();
    map.addListener('zoom_changed', function() {
        reloadMarkers(airspaces);
    });
    



}

// infowindow
// var bounds = new google.maps.LatLngBounds();
var  infowindow = new google.maps.InfoWindow();
initMap();
</script>
@endsection
