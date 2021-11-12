@extends('layouts.app')

@section('template_title')
	RADIO NAVIGATION AIDS/SYSTEMS
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="nk-block-title">RADIO NAVIGATION AIDS/SYSTEMS</h6>
                    </div>
                </div>
               
                <!-- MAP -->
                <div id="mapid" style="width:100%; height:100% !important; min-height:550px !important;" class="site-content"></div>
                <div class="nk-block-head-content mt-1" id="mycheckbox">
                    <!-- <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" onclick="checked" type="checkbox" id="adsb" value="adsb">
                        <label class="form-check-label" for="adsb">ADS-B</label>
                    </div> -->
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="ils" value="ils">
                        <label class="form-check-label" for="ils">ILS</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="loc" value="loc">
                        <label class="form-check-label" for="loc">Locator</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="ndb" value="ndb">
                        <label class="form-check-label" for="ndb">NDB</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="ndbdme" value="ndbdme">
                        <label class="form-check-label" for="ndbdme">NDB/DME</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="radar" value="radar">
                        <label class="form-check-label" for="radar">RADAR HEAD</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="tacan" value="tacan">
                        <label class="form-check-label" for="tacan">TACAN</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="vor" value="vor">
                        <label class="form-check-label" for="vor">VOR</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" checked="checked" type="checkbox" id="vordme" value="vordme">
                        <label class="form-check-label" for="vordme">VOR/DME</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input checkbox" type="checkbox" id="vortac" value="vortac">
                        <label class="form-check-label" for="vortac">VORTAC</label>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>Ident</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Frequency</th>
                                </tr>
                            </thead>
                            <tbody id="navlist">
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
$('#navinfo').hide();$('#navinfotable').hide();
var map; var center,zoomshow=false,modalvol=false;
var lstadsb = [];
var lstils = [];
var lstloc = [];
var lstndb = [];
var lstndbdme = [];
var lstradar = [];
var lsttacan = [];
var lstvor = [];
var lstvordme = [];
var lstvortac = [];
var markers = []; // Create a marker array to hold your markers
var circlemarker=[];
var navaids=[];
var nav =@json($navaids);
var ils =@json($ils);
var ch =@json($channel);ats=[];proc=[];asp=[];
// console.log(nav);

    nav.forEach(v=>{
        if (v.geom !== null){
            // console.log(k,v.nav_ident,v.geom.coordinates[1],v.geom.coordinates[0])
           
            if ( v.type == "1" ) {
                lstvor.push( v );
            }
            if ( v.type == "2" ) {
                lstvortac.push( v );
            }
            if ( v.type == "3" ) {
                lsttacan.push( v );
            }
            if ( v.type == "4" ) {
                frq = FreqFormat(v.freq,'4','DATA')
                v['channel'] ='CH-' + ch.find( x => x.definition === frq ).id
                // console.log(frq,ch.find( x => x.definition === frq ).id)
                lstvordme.push( v );
            }
            if ( v.type == "5" ) {
                lstndb.push( v );
            }
            if ( v.type == "7" ) {
                lstndbdme.push( v );
            }
            if ( v.type == "10" ) {
                lstloc.push( v );
            }
            if ( v.type == "20" ) {
                lstradar.push( v );
            }
            if ( v.type == "21" ) {
                lstadsb.push( v );
            }
            navaids.push(v)
        }
    });
            

    ils.forEach(v=>{
        if (v.geom !== null){
            // console.log(k,v,v.geom.coordinates[1],v.geom.coordinates[0])
                v['type']="11";
                lstils.push( v );
        }
    });

function backtolist() {
    $('#navinfo').hide();

}

function initMap() {
    var mapOptions = {
        center: new google.maps.LatLng(-2.85, 118),
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
    // console.log(map.getZoom())
    map.addListener('zoom_changed', function() {
        reloadMarkers(navaids);
        if (map.getZoom() >= 6){
                Showlabelpoint();
            }else{
                removelabel()
            }
    });


}
    window.addEventListener('load', (event) => {
    reloadMarkers(navaids);
    });

function showusage(data){
console.log(data)
window.scrollTo(0,0);
if (data.substr(0,3)=='NAV'){
    
    window.location.href = '/navaidinfo/' + data + '@info@interaktif@ @';
}else if (data.substr(0,3)=='ILS'){
    window.location.href = '/ilsinfo/' + data + '@info@interaktif@';
}
    
}

// infowindow
// var bounds = new google.maps.LatLngBounds();
var infowindow = new google.maps.InfoWindow();

function makeInfoWindowEvent(map, infowindow, info, marker) {
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(info);
    // infowindow.position(position);
    infowindow.open(map, marker);
  });
}

function loaddetail(arpt){
alert('buka windows baru dengan isi HTML airport info')
}

function showdetail(id){
    // console.log('id nav',id)
    for (var i=0; i<circlemarker.length; i++) {
        circlemarker[i].setMap(null);
    }
    circlemarker = [];
    let idx = markers.findIndex(x => x.id ===id);
        zoomshow=true
        console.log(id,markers[idx].id)
        var cntrvol= markers[idx].position; 
        map.setZoom(10);
        map.setCenter(cntrvol);
        window.scrollTo(0,0);
        infowindow.setContent(markers[idx].info);
        infowindow.setPosition(cntrvol);
        infowindow.open(map);
        circle = new google.maps.Circle({
                map: map,
                clickable: false, 
                radius: 10000,
                fillColor: '#fff',
                fillOpacity: .35,
                strokeColor: '#313131',
                strokeOpacity: .4,
                strokeWeight: .8,
                center: cntrvol,
            });
            circlemarker.push(circle);
}


function reloadMarkers(data) {
 // Loop through markers and set map to null for each
    for (var i=0; i<pntmarkers.length; i++) {
        pntmarkers[i].setMap(null);
    }
    pntmarkers = [];
    $('.checkbox:checkbox:checked').each(function(i){
        showMarkers($(this).val());
    });
}
function showMarkers(id) {
    switch(id) {
        case "adsb": setMarkerNav(lstadsb); break;
        case "ils": setMarkerNav(lstils); break;
        case "loc": setMarkerNav(lstloc); break;
        case "ndb": setMarkerNav(lstndb); break;
        case "ndbdme": setMarkerNav(lstndbdme); break;
        case "radar": setMarkerNav(lstradar); break;
        case "tacan": setMarkerNav(lsttacan); break;
        case "vor": setMarkerNav(lstvor); break;
        case "vordme": setMarkerNav(lstvordme); break;
        case "vortac": setMarkerNav(lstvortac); break;    
    }
    markers=pntmarkers;
}


function unshowMarkers(id){
    for (var i=0; i < pntmarkers.length; i++) {
        pntmarkers[i].setMap(null);
    }
    pntmarkers=[];
    $("#navlist tr").remove();
    $('.checkbox:checkbox:checked').each(function(i){
        showMarkers($(this).val());
    });
}

$(".checkbox").change(function() {
    initMap();
    unshowMarkers(this.id)
});


initMap();
reloadMarkers(navaids);
function showinfo(id){
    $('#' + id).toggle(); 
}

</script>
@endsection
