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
                    <a type="button" onclick="showinfo('aboutvolcano')" data-toggle="modal">
                    <h3>Volcano</h3>
                    </a>
                      <h6 class="nk-block-des text-soft">Volcano Observatory Notice for Aviation</h6>
                    </div>
                    <div class="nk-block-head-content" id="customSwitch2">
                <!-- <div class="custom-control custom-checkbox">
                    <input class="form-check-input checkbox" onclick="checked" type="checkbox" id="adsb" value="adsb">
                    <label class="form-check-label" for="adsb">ADS-B</label>
                </div> -->
                      <div class="custom-control custom-switch">
                          <input class="custom-control-input checkbox" type="checkbox" id="ils" value="ils">
                          <label class="custom-control-label" for="ils">Auto Refresh</label>
                      </div>
                      <!-- <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input checkbox">
                          <label class="custom-control-label" for="customSwitch2">Auto Refresh</label>
                      </div> -->
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
              <div id="mapid" style="width:100%; height:100% !important; min-height:400px !important;" class="site-content"></div>
              <div class="nk-block-between">
                  <form class="form-inline col-sm-6 mt-3">
                      <div class="form-group">
                          {{-- <div class="form-control-wrap">
                              <div class="form-icon form-icon-left">
                                  <em class="icon ni ni-search"></em>
                              </div>
                              <input type="text" class="form-control form-round" v-model="search" id="default-03" placeholder="Search..">
                          </div> --}}
                      </div>
                      &nbsp;
                  </form>
                  <div class="nk-block-head-content">
                      <div class="custom-control custom-checkbox">
                          <input class="form-check-input checkbox" checked="checked" v-on:click="displayonoff" type="checkbox" id="vgreen" value="vgreen">
                          <label class="form-check-label" for="volcanogreen"><strong style="color:#179638; font-weight:bolder;">GREEN</strong></label>
                      </div>
                      <div class="custom-control custom-checkbox">
                          <input class="form-check-input checkbox" checked="checked" v-on:click="displayonoff" type="checkbox" id="vyellow" value="vyellow">
                          <label class="form-check-label" for="volcanoyellow"> <strong style="color:#e7d107; font-weight:bolder;">YELLOW</strong></label>
                      </div>
                      <div class="custom-control custom-checkbox">
                          <input class="form-check-input checkbox" checked="checked" v-on:click="displayonoff" type="checkbox" id="vorange" value="vorange">
                          <label class="form-check-label" for="volcanoorange"><strong style="color:#FF9100; font-weight:bolder;">ORANGE</strong></label>
                      </div>
                      <div class="custom-control custom-checkbox">
                          <input class="form-check-input checkbox" checked="checked" v-on:click="displayonoff" type="checkbox" id="vred" value="vred">
                          <label class="form-check-label" for="volcanoRed"> <strong style="color:#CC0505; font-weight:bolder;">RED</strong></label>
                      </div>
                  </div>
              </div>
              <div class="row mt-1">
                  <div class="col-md-12">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <table class="datatable-init table" >
                          <thead class="thead-dark">
                              <tr align="center">
                                  <th>#</th>
                                  <th class="nk-tb-col"> No</th>
                                  <th class="nk-tb-col"> Name</th>
                                  <th class="nk-tb-col"><span class="d-none d-sm-inline">Status</span></th>
                                  <th class="nk-tb-col" >Region</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($volcanoes as $volcano)
                                  <tr class="nk-tb-item">
                                      <td class="nk-tb-item">
                                          <div class="dropdown">
                                              <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                              <div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">
                                                  <ul class="link-list-plain">
                                                      <a class="btn btn-dim btn-light" onclick="Loadcdmlog({{ json_encode($volcano) }})"><i class="icon ni ni-chat"></i>CDM</a>
                                                      <a class="btn btn-dim btn-secondary" onclick="loaddetail({{ json_encode($volcano) }})"><i class="icon ni ni-view-grid"></i>Detail</a>
                                                      <!-- <a class="btn btn-dim btn-info" onclick="showdetail({{ $volcano->va_lat.','.$volcano->va_lon }})"><i class="icon ni ni-map"></i>Show</a> -->
                                                      <a class="btn btn-dim btn-info" onclick="showdetail({{ $volcano->va_no }})"><i class="icon ni ni-map"></i>Show</a>
                                                  </ul>
                                              </div>
                                          </div>
                                      </td>
                                      <td class="nk-tb-col" align="left">{{ $volcano->va_no }}</td>
                                      <td class="nk-tb-col">{{ $volcano->va_name }}</td>
                                      <td class="nk-tb-col" align="center"> 
                                          @if ($volcano->va_status ==4)
                                              <span class="tb-odr-status">
                                                  <strong style="color:#CC0505; font-weight:bolder;">RED</strong>
                                              </span>
                                          @endif
                                          @if ($volcano->va_status ==3)
                                              <strong style="color:#FF9100; font-weight:bolder;">ORANGE</strong>
                                          @endif
                                          @if ($volcano->va_status ==2)
                                              <span class="tb-odr-status">
                                                  <strong style="color:#e7d107; font-weight:bolder;">YELLOW</strong>
                                              </span>
                                              @endif
                                          @if ($volcano->va_status ==1)
                                              <strong style="color:#179638; font-weight:bolder;">GREEN</strong>
                                              @endif
                                      </td>
                                      <td class="nk-tb-col">{{ $volcano->va_subregion }}</td>
                                  </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
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
$('#aboutvolcano').hide();
var map; var center,zoomshow=false,modalvol=false;
var green_vas = [];
var yellow_vas = [];
var orange_vas = [];
var red_vas = [];
var markers = []; // Create a marker array to hold your markers
// console.log(path,window.location.href)

var volcanoes = [
    @foreach($volcanoes as $volcano)
      {
        position: new google.maps.LatLng({{$volcano->va_lat}}, {{$volcano->va_lon}}),
        type: "{{$volcano->va_status}}",
        va_no: "{{$volcano->va_no}}",
        va_name: "{{$volcano->va_name}}",
        va_location:"{{sprintf('%02d',$volcano->va_lat_deg)}}" + '°' + "{{sprintf('%02d',$volcano->va_lat_min)}}" + "'" + "{{sprintf('%02d',$volcano->va_lat_sec)}}" + '"' + "{{$volcano->va_lat_ns}}" + ' ' + "{{sprintf('%03d',$volcano->va_lon_deg)}}" + '°' + "{{sprintf('%02d',$volcano->va_lon_min)}}" + "'" + "{{sprintf('%02d',$volcano->va_lon_sec)}}" + '"' + "{{$volcano->va_lon_ew}}",
        va_area: "{{$volcano->va_subregion}}" + ', ' + "{{$volcano->va_state}}",
        va_elev: "{{$volcano->va_summit_elevm}}",
        
      },
    @endforeach
    ];
var tempGreen = []; tempYellow=[]; tempOrange=[]; tempRed = [];
for ( var index=0; index<volcanoes.length; index++ ) {
    if ( volcanoes[index].type == "1" ) {
      tempGreen.push( volcanoes[index] );
    }
    if ( volcanoes[index].type == "2" ) {
      tempYellow.push( volcanoes[index] );
    }
    if ( volcanoes[index].type == "3" ) {
      tempOrange.push( volcanoes[index] );
    }
    if ( volcanoes[index].type == "4" ) {
      tempRed.push( volcanoes[index] );
    }
}
green_vas = tempGreen;
yellow_vas = tempYellow;
orange_vas = tempOrange;
red_vas = tempRed;

const iconBase =
    "{{ URL::to('/') }}/images/marker/mini/";
  const icons = {
    1: {
      icon: iconBase + "va_green.png",
    },
    2: {
      icon: iconBase + "va_yellow.png",
    },
    3: {
      icon: iconBase + "va_orange.png",
    },
    4: {
      icon: iconBase + "va_red.png",
    },
  };
  var markerGroups = {
    "green": [],
    "yellow": [],
    "orange": [],
    "red": []
};
$('#customSwitch2 :checkbox').change(function() {
    console.log('this.checked',this.checked)
    // this will contain a reference to the checkbox   
    if (this.checked) {
      loadvona()
    } else {
        // the checkbox is now no longer checked
    }
});
function loadvona() {
  this.url='https://magma.esdm.go.id/api/v1/vona?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbWFnbWEuZXNkbS5nby5pZFwvYXBpXC9sb2dpblwvc3Rha2Vob2xkZXIiLCJpYXQiOjE1NzkzMTQ5NTMsImV4cCI6MTYxMDkzNzM1MywibmJmIjoxNTc5MzE0OTUzLCJqdGkiOiJvS2xUR0FzeFRsWjdha0twIiwic3ViIjo0LCJwcnYiOiI0YTlkOWEyZDI2ODAyYzMxMmU4ZTVhNWJlNjBmZjI2ZjBmYzYzZDdkIiwic291cmNlIjoiTUFHTUEgSW5kb25lc2lhIiwiYXBpX3ZlcnNpb24iOiJ2MSIsImRheXNfcmVtYWluaW5nIjozNjUsImV4cGlyZWRfYXQiOiIyMDIxLTAxLTE4IDAwOjAwOjAwIn0.uasdZ-aTUgS5HVv4chefheoDfzYq3PqSK8CP0ObpNd8'
            
  
  $.ajax({
    url: url,
    // data: {ctry: 'ID', deleted: 0},
    type: "json",
    method: "GET",
      success: function (result) {
          $.each(result.data, function (k, v) {
            var vonaid=v.uuid;
            var vona=v; // console.log('vona.uuid',vonaid,v)
            $.ajax({
                  url: path + '/api/vol/vona/',
                  data: {uuid: vonaid},
                  type: "json",
                  method: "GET",

                success: function (result) {
                  console.log(result.data.length,vonaid,vona)
                  if (result.data.length == 0){
                    $.ajax({
                      url: path + '/api/vol/vona/save/',
                      data: {data:vona},
                      type: "POST",
                      cache: false,
                      success: function (result) {
                        console.log('data save')
                      }
                    });
                  }
                  // $.each(result.data, function (s, r) {
                  //   console.log('ada',r)
                  // });
                }
            });
          });
        }
  });
}
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
    styles: [{
    featureType: "landscape.natural",
        elementType: "labels.icon",
        stylers: [{ visibility: "off" }],
    }],
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
    reloadMarkers(volcanoes);
  });
  
  setMarkers(volcanoes); 

}
// infowindow
var bounds = new google.maps.LatLngBounds();
var  infowindow = new google.maps.InfoWindow();

function makeInfoWindowEvent(map, infowindow, info, marker) {
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(info);
    // infowindow.position(position);
    infowindow.open(map, marker);
  });
}
function showdetail(id){ 
  // console.log(id);
  for (var i=0; i<markers.length; i++) {
    // console.log(markers[i].id,id);
       if (markers[i].id==id){
        zoomshow=true
       
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
            infowindow.setContent(markers[i].info);
            infowindow.setPosition(cntrvol);
            infowindow.open(map);
       }
    }
  
}
function setIcon(uri){
  var image = {
              url: uri, 
              size: new google.maps.Size(35, 35), 
              origin: new google.maps.Point(0, 0), 
              anchor: new google.maps.Point(18, 18),
              scaledSize: new google.maps.Size(35, 35)
            };
  return image;
}
function checkcdm(vano){
  var pathdetail=pathpop() + '/api/vol/txcdm/'
  $.ajax({
          url: pathdetail,
          data: {va_no: vano},
          type: "json",
          method: "GET",
  
          success: function (result) {
            if (result.data.length==0){
              return false;
            }else{
              return true;
            }
          }
          
  
      });
}
  function volattribute(va){
    // console.dir(va.cdm_id,va.va_no);
    // checkcdm(va.va_no)
            // this.volshow=true
            // this.lat =  va.va_geom.coordinates[1]
            // this.lon = va.va_geom.coordinates[0]
            // this.lat =  va.va_lat
            // this.lon = va.va_lon
            // this.lattxt=va.va_lat_deg + '°' + '00',va.va_lat_min + "'" + va.va_lat_sec + "''" + va.va_lat_ns
            // this.lontxt=va.va_lon_deg + '°' + va.va_lon_min + "'" + va.va_lon_sec + "''" + va.va_lon_ew
            // this.volloc=this.lat + ' ' + this.lon
            let txtcolor, btncdm='',btnforecast='',btndetail='';
            txtcolor=getColor(va.type)
            if(va.cdm_id !== ''){
                if(this.$piagroup =='1'){
                    btncdm='<button class="btn btn-sm btn-primary tocdm" data-va_no= '+ va.va_no +'">CDM</button> ';	 
                }else{
                  btncdm='<button class="tocdm btn btn-sm btn-primary " onclick="tocdm('+ va.va_no +')"">CDM</button> ';
                    // $sqlcekcdm = "SELECT CDM_ID FROM tx_cdm_users WHERE CDM_ID='".$fsql->get('CDM_ID')."' AND USER_ID='".$_SESSION['USER_ID']."'";
                    // $conn->connect();
                    // $fsqlcekcdm=$conn->query($sqlcekcdm);
                    // $fsqlcekcdm->next();
                    // if($fsqlcekcdm->get('CDM_ID')!=''){
                    //     $marker.='<button class="btn btn-sm btn-primary" onclick="gotoCDM(' + this.cdmchat() + ')">Go to CDM</button> ';								
                    // }
                }
            }
                btndetail='<button class="btn  btn-sm btn-success" onclick="">Info</button> ';
                btnforecast='<button class="btn  btn-sm btn-warning" onclick="">Forecast</button> ';

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
                    '<td>'+ va.va_location +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Area</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.va_area +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Summit Elevation</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.va_elev+' M</td>'+
                '</tr>'+
            '</table>'+
            '<br><br>' + btncdm  + btndetail + btnforecast;
  }
  
  function getColor(status){
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

function reloadMarkers(data) {
 // Loop through markers and set map to null for each
 for (var i=0; i<markers.length; i++) {
      markers[i].setMap(null);
 }
 markers = [];
 $('.checkbox:checkbox:checked').each(function(i){
   console.log($(this).val())
      showMarkers($(this).val());
  });
}
function showMarkers(id) {
  switch(id) {
    case "vgreen": setMarkers(green_vas); break;
    case "vyellow": setMarkers(yellow_vas); break;
    case "vorange": setMarkers(orange_vas); break;
    case "vred": setMarkers(red_vas); break;      
  }
}


function unshowMarkers(id){
  for (var i=0; i < markers.length; i++) {
     markers[i].setMap(null);
  }
  $('.checkbox:checkbox:checked').each(function(i){
      showMarkers($(this).val());
  });
}

$(".checkbox").change(function() {
    if(this.checked) {
      showMarkers(this.id);
    }else{
      unshowMarkers(this.id)
    }  
});


initMap();

function showinfo(id){
  var vol = document.getElementById(id);
    vol.style.visibility = 'visible';
  $('#' + id).toggle(); 
}

function tocdm(va_no){
  window.location.href = '/cdmlogdetail/' + va_no;
  // return view('pages.volcano.cdmdetail');
//   return view('daftarPeriksa')->with(['req' => $req]);
//   window.location.href = '/cdmlog/';
//  qcdmdetail(va_no)
 // history.back(1);

}

</script>
@endsection