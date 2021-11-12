@extends('layouts.app')

@section('template_title')
NAME CODE DESIGNATORS FOR SIGNIFICANT POINTS
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
            <!-- <div class="nk-block-head nk-block-head-sm"> -->
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="nk-block-title">NAME CODE DESIGNATORS FOR SIGNIFICANT POINTS</h6>
                    </div>
                </div>
            <!-- </div> -->
           
            <!-- MAP -->
            <div id="mapid" style="width:100%; height:100% !important; min-height:550px !important;" class="site-content"></div>
            <div class="nk-block-head-content mt-1" id="mycheckbox">
                <!-- <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="ausot" value="ausot">
                    <label class="form-check-label" for="ausot">AUSOT</label>
                </div> -->
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" checked="checked" type="checkbox" id="enrwpt" value="enrwpt">
                    <label class="form-check-label" for="enrwpt">En-Route WPT</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="enrtermwpt" value="enrtermwpt">
                    <label class="form-check-label" for="enrtermwpt">Enr-Route and Terminal WPT</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="termwpt" value="termwpt">
                    <label class="form-check-label" for="termwpt">Terminal WPT</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" type="checkbox" id="vfrwpt" value="vfrwpt">
                    <label class="form-check-label" for="vfrwpt">VFR WPT</label>
                </div>
            </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Usage</th>
                                </tr>
                            </thead>
                            <tbody id="wptlist">
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
var lstausot = [];
var lstenrwpt = [];
var lstenrtermwpt = [];
var lsttermwpt = [];
var lstvfrwpt = [];
var markers = []; // Create a marker array to hold your markers
var waypoints=[];circlemarker=[];
var wpts =@json($waypoints);
var pathdetail=pathpop() + '/api/waypoint/list/'
// console.log('pathdetail',pathdetail)
if (wpts.length >0){
            wpts.forEach(v=>{

                if (v.geom !== null){
                    // console.log(k,v,v.geom.coordinates[1],v.geom.coordinates[0])
                    waypoints.push(v)
                    if ( v.usage_cd == "5" ) {
                        lstausot.push( v );
                    }
                    if ( v.usage_cd == "1" ) {
                        lstenrwpt.push( v );
                    }
                    if ( v.usage_cd == "3" ) {
                        lstenrtermwpt.push( v );
                    }
                    if ( v.usage_cd == "2" ) {
                        lsttermwpt.push( v );
                    }
                    if ( v.usage_cd == "4" ) {
                        lstvfrwpt.push( v );
                    }
                }
            
                
            });
            initMap();
            // Drawline(tma,'TMA');
        }

$('#mycheckbox :checkbox').change(function() {
    initMap()
    reloadMarkers(waypoints)
    // this will contain a reference to the checkbox   
    if (this.checked) {
        // the checkbox is now checked 
    } else {
        // the checkbox is now no longer checked
    }
});

function showusagewpt(data){

window.scrollTo(0,0);
window.location.href = '/waypointinfo/' + data + '@info@interaktif@' +data + '@';
    
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
    // $('.checkbox:checkbox:checked').each(function(i){
    //     showMarkers($(this).val());
    // });
  }
  // map.setZoom(5);
  // map.setCenter(center);
  // window.alert("Map was clicked!");
  });

  map.addListener('zoom_changed', function() {
      reloadMarkers(waypoints);
      if (map.getZoom() >= 6){
            Showlabelpoint();
        }else{
            removelabel()
        }
  });
 
//   setMarkersWpt(lstenrwpt)
  

}

    window.addEventListener('load', (event) => {
        reloadMarkers(waypoints);
    });
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
function loaddetail(arpt){
alert('buka windows baru dengan isi HTML airport info')
}

function showdetailwpt(id){
    console.log(id);
    for (var i=0; i<circlemarker.length; i++) {
        circlemarker[i].setMap(null);
    }
    circlemarker = [];
    let idx = pntmarkers.findIndex(x => x.id ===id);
        zoomshow=true
        console.log(id,pntmarkers[idx].id)
        var cntrvol= pntmarkers[idx].position; 
    // for (var i=0; i<markers.length; i++) {

    //     console.log(markers[i].id)
    //    if (markers[i].id==id){
    //     zoomshow=true
    //     var cntrvol= markers[i].position; 
        map.setZoom(10);
        map.setCenter(cntrvol);
        window.scrollTo(0,0);
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
            infowindow.setContent(pntmarkers[i].info);
            infowindow.setPosition(cntrvol);
            infowindow.open(map);
    //    }
    // }

}

  
// Create markers.


function reloadMarkers(data) {
$("#wptlist tr").remove();
 // Loop through markers and set map to null for each
 for (var i=0; i<markers.length; i++) {
        markers[i].setMap(null);
 }
 markers = [];
 $('.checkbox:checkbox:checked').each(function(i){
        showMarkers($(this).val());
  });
}
function showMarkers(id) {
  switch(id) {
    case "ausot": setMarkersWpt(lstausot); break;
    case "enrwpt": setMarkersWpt(lstenrwpt); break;
    case "enrtermwpt": setMarkersWpt(lstenrtermwpt); break;
    case "termwpt": setMarkersWpt(lsttermwpt); break;
    case "vfrwpt": setMarkersWpt(lstvfrwpt); break;
  }
}





initMap();

function showinfo(id){
  $('#' + id).toggle(); 
}


</script>
@endsection
