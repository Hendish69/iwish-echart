@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
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
            <div id="mapid" style="width:100%; height:100% !important; min-height:400px !important;" class="site-content">
                <!-- <div class="ol-attribution ol-unselectable ol-control ol-uncollapsible">
                    <ul>
                    <li style=""><a href="https://openlayers.org/"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAAHGAAABxgEXwfpGAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAhNQTFRF////AP//AICAgP//AFVVQECA////K1VVSbbbYL/fJ05idsTYJFtbbcjbJllmZszWWMTOIFhoHlNiZszTa9DdUcHNHlNlV8XRIVdiasrUHlZjIVZjaMnVH1RlIFRkH1RkH1ZlasvYasvXVsPQH1VkacnVa8vWIVZjIFRjVMPQa8rXIVVkXsXRsNveIFVkIFZlIVVj3eDeh6GmbMvXH1ZkIFRka8rWbMvXIFVkIFVjIFVkbMvWH1VjbMvWIFVlbcvWIFVla8vVIFVkbMvWbMvVH1VkbMvWIFVlbcvWIFVkbcvVbMvWjNPbIFVkU8LPwMzNIFVkbczWIFVkbsvWbMvXIFVkRnB8bcvW2+TkW8XRIFVkIlZlJVloJlpoKlxrLl9tMmJwOWd0Omh1RXF8TneCT3iDUHiDU8LPVMLPVcLPVcPQVsPPVsPQV8PQWMTQWsTQW8TQXMXSXsXRX4SNX8bSYMfTYcfTYsfTY8jUZcfSZsnUaIqTacrVasrVa8jTa8rWbI2VbMvWbcvWdJObdcvUdszUd8vVeJaee87Yfc3WgJyjhqGnitDYjaarldPZnrK2oNbborW5o9bbo9fbpLa6q9ndrL3ArtndscDDutzfu8fJwN7gwt7gxc/QyuHhy+HizeHi0NfX0+Pj19zb1+Tj2uXk29/e3uLg3+Lh3+bl4uXj4ufl4+fl5Ofl5ufl5ujm5+jmySDnBAAAAFp0Uk5TAAECAgMEBAYHCA0NDg4UGRogIiMmKSssLzU7PkJJT1JTVFliY2hrdHZ3foSFhYeJjY2QkpugqbG1tre5w8zQ09XY3uXn6+zx8vT09vf4+Pj5+fr6/P39/f3+gz7SsAAAAVVJREFUOMtjYKA7EBDnwCPLrObS1BRiLoJLnte6CQy8FLHLCzs2QUG4FjZ5GbcmBDDjxJBXDWxCBrb8aM4zbkIDzpLYnAcE9VXlJSWlZRU13koIeW57mGx5XjoMZEUqwxWYQaQbSzLSkYGfKFSe0QMsX5WbjgY0YS4MBplemI4BdGBW+DQ11eZiymfqQuXZIjqwyadPNoSZ4L+0FVM6e+oGI6g8a9iKNT3o8kVzNkzRg5lgl7p4wyRUL9Yt2jAxVh6mQCogae6GmflI8p0r13VFWTHBQ0rWPW7ahgWVcPm+9cuLoyy4kCJDzCm6d8PSFoh0zvQNC5OjDJhQopPPJqph1doJBUD5tnkbZiUEqaCnB3bTqLTFG1bPn71kw4b+GFdpLElKIzRxxgYgWNYc5SCENVHKeUaltHdXx0dZ8uBI1hJ2UUDgq82CM2MwKeibqAvSO7MCABq0wXEPiqWEAAAAAElFTkSuQmCC"></a>
                    </li>
                    </ul>
                </div> -->
            </div>
                <div class="nk-block-between">
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
                </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th class="sorting" @click="sort('icao')"><i class='icon ni ni-sort-v'></i>ICAO</th>
                                    <th class="sorting" tabindex="0"  @click="sort('va_name')" aria-sort="ascending" aria-label="arpt_name: activate to sort column descending"><i class='icon ni ni-sort-v'></i>Name</th>
                                    <th class="sorting" @click="sort('va_status')"><i class='icon ni ni-sort-v'></i><span class="d-none d-sm-inline">City</span></th>
                                    <th class="sorting" @click="sort('va_subregion')"><i class='icon ni ni-sort-v'></i>Latitude</th>
                                    <th class="sorting" @click="sort('va_subregion')"><i class='icon ni ni-sort-v'></i>Longitude</th>
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
{{-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vuex"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
<script type="text/javascript">
var map; var center,zoomshow=false,modalvol=false;
var vol2 = [];
var vol3 = [];
var vol4 = [];
var vol5 = [];
var markers = []; // Create a marker array to hold your markers
var airports = [];circlemarker=[];
var pathdetail=pathpop() + '/api/airports/'
console.log('pathdetail',pathdetail)
$.ajax({
        url: pathdetail,
        data: {ctry: 'ID', deleted: 0,sort:'arpt_name:asc'},
        type: "json",
        method: "GET",

        success: function (result) {
            $.each(result.data, function (k, v) {
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
            setMarkers(vol2);
            // Drawline(tma,'TMA');
        }

    });





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
        reloadMarkers(airports);
    });
    var mrk = new google.maps.Marker({
            icon: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAAHGAAABxgEXwfpGAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAhNQTFRF////AP//AICAgP//AFVVQECA////K1VVSbbbYL/fJ05idsTYJFtbbcjbJllmZszWWMTOIFhoHlNiZszTa9DdUcHNHlNlV8XRIVdiasrUHlZjIVZjaMnVH1RlIFRkH1RkH1ZlasvYasvXVsPQH1VkacnVa8vWIVZjIFRjVMPQa8rXIVVkXsXRsNveIFVkIFZlIVVj3eDeh6GmbMvXH1ZkIFRka8rWbMvXIFVkIFVjIFVkbMvWH1VjbMvWIFVlbcvWIFVla8vVIFVkbMvWbMvVH1VkbMvWIFVlbcvWIFVkbcvVbMvWjNPbIFVkU8LPwMzNIFVkbczWIFVkbsvWbMvXIFVkRnB8bcvW2+TkW8XRIFVkIlZlJVloJlpoKlxrLl9tMmJwOWd0Omh1RXF8TneCT3iDUHiDU8LPVMLPVcLPVcPQVsPPVsPQV8PQWMTQWsTQW8TQXMXSXsXRX4SNX8bSYMfTYcfTYsfTY8jUZcfSZsnUaIqTacrVasrVa8jTa8rWbI2VbMvWbcvWdJObdcvUdszUd8vVeJaee87Yfc3WgJyjhqGnitDYjaarldPZnrK2oNbborW5o9bbo9fbpLa6q9ndrL3ArtndscDDutzfu8fJwN7gwt7gxc/QyuHhy+HizeHi0NfX0+Pj19zb1+Tj2uXk29/e3uLg3+Lh3+bl4uXj4ufl4+fl5Ofl5ufl5ujm5+jmySDnBAAAAFp0Uk5TAAECAgMEBAYHCA0NDg4UGRogIiMmKSssLzU7PkJJT1JTVFliY2hrdHZ3foSFhYeJjY2QkpugqbG1tre5w8zQ09XY3uXn6+zx8vT09vf4+Pj5+fr6/P39/f3+gz7SsAAAAVVJREFUOMtjYKA7EBDnwCPLrObS1BRiLoJLnte6CQy8FLHLCzs2QUG4FjZ5GbcmBDDjxJBXDWxCBrb8aM4zbkIDzpLYnAcE9VXlJSWlZRU13koIeW57mGx5XjoMZEUqwxWYQaQbSzLSkYGfKFSe0QMsX5WbjgY0YS4MBplemI4BdGBW+DQ11eZiymfqQuXZIjqwyadPNoSZ4L+0FVM6e+oGI6g8a9iKNT3o8kVzNkzRg5lgl7p4wyRUL9Yt2jAxVh6mQCogae6GmflI8p0r13VFWTHBQ0rWPW7ahgWVcPm+9cuLoyy4kCJDzCm6d8PSFoh0zvQNC5OjDJhQopPPJqph1doJBUD5tnkbZiUEqaCnB3bTqLTFG1bPn71kw4b+GFdpLElKIzRxxgYgWNYc5SCENVHKeUaltHdXx0dZ8uBI1hJ2UUDgq82CM2MwKeibqAvSO7MCABq0wXEPiqWEAAAAAElFTkSuQmCC",
    });
    const centerControlDiv = document.createElement("div");
    CenterControl(centerControlDiv, map);

  map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);
  centerControlDiv.index = 1;
  centerControlDiv.style.paddingTop = "10px";
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);
    mrk.setMap(map);
    // $("#arptlist tr").remove();
    // setMarkers(vol2)
    // setMarkers(airports); 

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
function loaddetail(arpt){
alert('buka windows baru dengan isi HTML airport info')
}
function showdetail(id){
    for (var i=0; i<circlemarker.length; i++) {
        circlemarker[i].setMap(null);
    }
    circlemarker = [];
    for (var i=0; i<markers.length; i++) {
       if (markers[i].id==id){
        zoomshow=true
        // console.log(id)
        var cntrvol= markers[i].position; 
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
            infowindow.setContent(markers[i].info);
            infowindow.setPosition(cntrvol);
            infowindow.open(map);
       }
    }
    // zoomshow=true
    // // console.log(vol)
    // var cntrvol= new google.maps.LatLng(lat,lon); 
    // map.setZoom(10);
    // map.setCenter(cntrvol);
    // window.scrollTo(0,0);
    // circle = new google.maps.Circle({
    //         map: map,
    //         clickable: false, 
    //         radius: 10000,
    //         fillColor: '#fff',
    //         fillOpacity: .35,
    //         strokeColor: '#313131',
    //         strokeOpacity: .4,
    //         strokeWeight: .8,
    //         center: { lat: lat, lng: lon },
    //     }); 
}
function setIcon(uri,vol){
    var sz=15;
    switch(vol){
        case 2:sz=25;break;
        case 3:sz=20;break;
        case 4:sz=15;break;
        case 5:sz=15;break;
    }
    var image = {
            url: uri, 
            size: new google.maps.Size(sz, sz), 
            origin: new google.maps.Point(0, 0), 
            anchor: new google.maps.Point(sz/2, sz/2),
            scaledSize: new google.maps.Size(sz, sz),
        };
    return image;
}
  function volattribute(va){
    //   console.dir(va);
      var rr='';dim='';surf='';pcn='';
      va.runways.forEach(rw=>{
          
        if (rr==''){
            rr = rw.rwy_ident;
            dim =rw.length + ' x ' + rw.width;
            surf =rw.definition;
            pcn =rw.pcn;
        }else{
            rr += ' / '+ rw.rwy_ident;
            dim +=' / '+rw.length + ' x ' + rw.width;
            surf +=' / '+rw.definition;
            pcn +=' / '+rw.pcn;
        }
      })
      var iata=va.iata;
      if (va.iata=='' || va.iata==''){
        iata='NIL';
      }
      var rww='<tr><td align="left" style="font-weight:bold;"><b>Runway</b></td><td>:</td>'+
                '<td style="font-weight:bold">'+ rr +'</td></tr>'+
                '<tr><td align="left" style="font-weight:bold;"><b>Dimension </b></td><td>:</td><td>'+dim  +'m</td></tr>'+
                '<tr><td align="left" style="font-weight:bold;"><b>Surface</b></td><td>:</td><td>'+surf +'</td></tr>'+
                '<tr><td align="left" style="font-weight:bold;"><b>Strength</b></td><td>:</td><td>'+ pcn +'</td></tr>';
      var cord =getcord(va.geom.coordinates[0],va.geom.coordinates[1])
            let txtcolor, btncdm='',btnforecast='',btndetail='';
                btncdm='<button class="tocdm btn btn-sm btn-primary " onclick="tocdm('+ va.arpt_ident +')"">Info</button> ';
                // btndetail='<button class="btn  btn-sm btn-success" onclick="">Info</button> ';
                // btnforecast='<button class="btn  btn-sm btn-warning" onclick="">Forecast</button> ';

        return '<div width="300px !important; padding:30px 30px 30px 30px;"><b style="font-size:20px;">' + va.arpt_name  + '</b><br><br><table width="300px !important;">'+
                    '<tr>'+
                    '<td align="left" width="130" style="font-weight:bold;"><b>ICAO/IATA</b></td>'+
                    '<td>:</td>'+
                    '<td style="font-weight:bold">'+ va.icao + ' / ' + iata + '</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Location</b></td>'+
                    '<td>:</td>' +
                    '<td>'+ cord  +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>City</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.city_name +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Elevation</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.elev +' ft</td>'+
                '</tr>'+ rww +
            '</table>'+
            '<br><br>' + btncdm  + btndetail + btnforecast;
  }
  
// Create markers.
function setMarkers(arpt){
  var ctn=[];
  for (let i = 0; i < arpt.length; i++) {
    ctn[i]= volattribute(arpt[i]);
    var ic = setIcon(icons[arpt[i].vol].icon,arpt[i].vol);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng({lat: arpt[i].geom.coordinates[1],lng: arpt[i].geom.coordinates[0]}),
            icon: ic,
            title: arpt[i].city_name + '/' + arpt[i].arpt_name,
            map: map,
            clickable:true,
            id:arpt[i].id,
            info:ctn[i]
            
        });
        var bearing = 45;
        // console.log(bearing);
        var icon = marker.getIcon();
        icon.rotation = bearing;
        // console.log('icon.rotation',icon.rotation)
        marker.setIcon(icon);
        var cord=SetCoordinatebyGeom(arpt[i].geom)
                hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-secondary"><i class="icon ni ni-view-grid"></i>Detail</a><a class="btn btn-dim btn-info" onclick="showdetail('+ arpt[i].id + ')"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + arpt[i].icao + '</td><td>' + arpt[i].arpt_name + '</td><td>' + arpt[i].city_name + '</td><td>' + cord.WGSAIP[1] + '</td><td>' + cord.WGSAIP[0] + '</td></tr>'
                $("#arptlist").append(hasil);
        
        // ctn[i] = "<strong>"+"Mountain Name : "+data_vas[i].va_name+"</strong><br>";
        makeInfoWindowEvent(map, infowindow, ctn[i], marker);
        markers.push(marker);
  }  
}

function reloadMarkers(data) {
 // Loop through markers and set map to null for each
 $("#arptlist tr").remove();
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
    case "arptvol2": setMarkers(vol2); break;
    case "arptvol3": setMarkers(vol3); break;
    case "arptvol4": setMarkers(vol4); break;
    case "arptvol5": setMarkers(vol5); break;      
  }
}


function unshowMarkers(id){
  for (var i=0; i < markers.length; i++) {
     markers[i].setMap(null);
  }
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

function tocdm(va_no){
  console.log()
  window.location.href = '/cdmlog/';
  // history.back(1);
 
}
</script>
@endsection