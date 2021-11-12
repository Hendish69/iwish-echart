@extends('layouts.app')

@section('template_title')
	Airport
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
                    <h3 class="nk-block-title page-title">Airport</h3>
                </div>
            </div>
        </div>
        <div class="nk-block-head-content" id="mycheckbox">
            <div class="custom-control custom-checkbox">
                <input class="form-check-input checkbox" checked="checked" type="checkbox" id="arptvol2" value="arptvol2">
                <label class="form-check-label" for="arptvol2">VOL 2</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input class="form-check-input checkbox" type="checkbox" id="arptvol3" value="arptvol3">
                <label class="form-check-label" for="arptvol3">VOL 3</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input class="form-check-input checkbox" type="checkbox" id="arptvol4" value="arptvol4">
                <label class="form-check-label" for="arptvol4">VOL 4</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input class="form-check-input checkbox" type="checkbox" id="arptvol5" value="arptvol5">
                <label class="form-check-label" for="arptvol5">VOL 5</label>
            </div>
        </div>
                

            <!-- MAP -->
            <div id="mapid" style="width:100%; height:100% !important; min-height:550px !important;" class="site-content">

            </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>ICAO</th>
                                    <th>Name</th>
                                    <th>City</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                </tr>
                            </thead>
                            <tbody id="arptlist">

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
var vol2 = [];
var vol3 = [];
var vol4 = [];
var vol5 = [];
var markers = []; // Create a marker array to hold your markers
var airports = [];circlemarker=[];
var arp =@json($airport);
if (arp==null)arp=0;
// var pathdetail=pathpop() + '/api/airports/'
// console.log('pathdetail',pathdetail)
// $.ajax({
//         url: pathdetail,
//         data: {ctry: 'ID', deleted: 0,sort:'arpt_name:asc'},
//         type: "json",
//         method: "GET",

//         success: function (result) {
    if (arp.length>0){
            arp.forEach(v=>{
                if (v.geom !== null){
                    // console.log(k,v,v.geom.coordinates[1],v.geom.coordinates[0])
                    airports.push(v)
                    if ( v.vol == 2 ) {
                        vol2.push( v );
                    }
                    if ( v.vol == 3 ) {
                        vol3.push( v );
                    }
                    if ( v.vol == 4 ) {
                        vol4.push( v );
                    }
                    if ( v.vol == 5 ) {
                        vol5.push( v );
                    }
                    
                }
                
            });
            // Drawline(tma,'TMA');
        }

    // });





const iconBase =
    "{{ URL::to('/') }}/images/marker/";
const icons = {
    2: {
        icon: iconBase + "vol2.png",
    },
    3: {
        icon: iconBase + "vol3.png",
    },
    4: {
        icon: iconBase + "vol4.png",
    },
    5: {
        icon: iconBase + "vol5.png",
    },
    6: {
        icon: iconBase + "vol4.png",
    },
    7: {
        icon: iconBase + "vol4.png",
    },
};
function getcord(x,y){
    let cord = SetCoordinatebyDecimal(x,y);
    // $('#latitude').val(cord.Database[1]);
    //                 $('#longitude').val(cord.Database[0]);
    return cord.ADText[1] + ' ' + cord.ADText[0]
}
  var markerGroups = {
    "arpt2": [],
    "arpt3": [],
    "arpt4": [],
    "arpt5": []
};
var indo={lat:-2.85,lng: 118};
window.addEventListener('load', (event) => {
    reloadMarkers(airports);
    });
function initMap() {
    var mapOptions = {
        center: new google.maps.LatLng(-2.85, 118),
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
        // $('.checkbox:checkbox:checked').each(function(i){
        //     showMarkers($(this).val());
        // });
    }
   
    // map.setZoom(5);
    // map.setCenter(center);
    // window.alert("Map was clicked!");
    });
    map.addListener('zoom_changed', function() {
        reloadMarkers(airports);
        if (map.getZoom() >= 6){
            // Showlabelpoint();PLG
        }else{
            removelabel()
        }
    });
    

}

// infowindow
// var bounds = new google.maps.LatLngBounds();
var  infowindow = new google.maps.InfoWindow();


function loaddetail(arpt){
alert('buka windows baru dengan isi HTML airport info')
}


function reloadMarkers(data) {
 // Loop through markers and set map to null for each
    $("#arptlist tr").remove();
    for (var i=0; i<arptmarkers.length; i++) {
        arptmarkers[i].setMap(null);
    }
    arptmarkers = [];
    $('.checkbox:checkbox:checked').each(function(i){
            showMarkers($(this).val());
    });
}
function showMarkers(id) {
    switch(id) {
        case "arptvol2": setMarkersArpt(vol2); break;
        case "arptvol3": setMarkersArpt(vol3); break;
        case "arptvol4": setMarkersArpt(vol4); break;
        case "arptvol5": setMarkersArpt(vol5); break;      
    }
}


function unshowMarkers(id){
    for (var i=0; i < arptmarkers.length; i++) {
        arptmarkers[i].setMap(null);
    }
    arptmarkers=[];
    $("#arptlist tr").remove();
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


initMap();

function showinfo(id){
  $('#' + id).toggle(); 
}


</script>
@endsection
