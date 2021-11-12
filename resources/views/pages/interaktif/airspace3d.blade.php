@extends('layouts.app')

@section('template_title')
    Airspace 3D
@endsection

@section('head') 
<!-- <link href="//cesiumjs.org/releases/1.77/Build/Cesium/Widgets/widgets.css?v=123" rel="stylesheet">  -->
  <link href="https://cesium.com/downloads/cesiumjs/releases/1.82/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
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
                <div class="card card-preview" >
                    <div class="card-inner">
                        <div class="row">
                            <div class="col-lg-12 h-75" > 
                                <div id="cesiumContainer" class="fullSize" style=" width: 100%; height: 100%;min-height:600px!important">
                                    <div id="canvasCsm" style="width: 100%"></div>
                                </div>  
                                <div id="toolbar"> 
                                    <select class="cesium-button" id="ddview">
                                        <option value="0" disabled selected><i class="icon ni ni-b-si"></i>Options</option>
                                        <option value="1">Reset View</option>
                                        <option value="2">Optimize Graphic</option>
                                        <option value="3">Disable Fog</option>
                                        <option value="4">Hide Building</option>
                                    </select>
                                    
                                    @php
                                        $arr_type = array('afiz'=>'#008000','atz'=>'#DCDCDC','cta'=>'#FFA500','ctr'=>'#87CEEB','fir'=>'#FF0000','sector'=>'#F0E68C','mtca'=>'#D2691E','tiba'=>'#9370DB','tma'=>'#808000','uta'=>'#00FFFF')
                                    @endphp
                                    @foreach ($arr_type as $type=>$color) 
                                        <div class="custom-control custom-control-sm custom-checkbox pr-2" style="background-color:{{$color}}; color:black;font-weight:bold;text-shadow: 0px -1px 0px rgba(255,255,255,.5);">
                                            <input class="custom-control-input checkbox" type="checkbox" id="{{$type}}" value="{{$type}}">
                                            <label class="custom-control-label" for="{{$type}}">{{strtoupper($type)}}</label>
                                        </div>        
                                    @endforeach     
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="card-inner" id="ctnTable"> </div> 
                </div>  
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')
<!-- <script src="//cesiumjs.org/releases/1.77/Build/Cesium/Cesium.js?v=123"></script> -->
  <script src="https://cesium.com/downloads/cesiumjs/releases/1.82/Build/Cesium/Cesium.js"></script>
 

<script id="cesium_sandcastle_script">
    var eArSp = [];
	var eArSpAll = [];
	
	var aArSpMarker = [];
	var aArSpMarkerAll = [];
	
	var orangePolygonAll;
	var cesiumPolygonAllDisp = [];
			
	var orangePolygon = [];
    var viewer ;
    var prnt = []; 
    var osmBuildingsTileset;
    var handler ;
    var resetCameraFunction;
    var colorByMaterial;
    var showByBuildingType;
    var show_cc;
    function startup(Cesium) {
        'use strict';
        Cesium.Ion.defaultAccessToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzYTc1MWIwYS1iZjRmLTQ1MTMtOGNiYy05YTE2ZTc1YjBjYzUiLCJpZCI6NDExMzksImlhdCI6MTYwOTgwNjE0Nn0.ejHBLHCLBP41Hs4rKvpydSg_UO9cl1dNgo2_VArUfKI";
        var clockViewModel = new Cesium.ClockViewModel();
        var worldTerrain = Cesium.createWorldTerrain({
            requestWaterMask: false,
            requestVertexNormals: false,
        });

        var options = {
            homeButton: false,
            fullscreenButton: false,
            sceneModePicker: true,
            clockViewModel: clockViewModel,
            infoBox: true,
            geocoder: true,
            sceneMode: Cesium.SceneMode.SCENE3D,
            navigationHelpButton: false,
            animation: false,
            baseLayerPicker : false,
            imageryProvider : Cesium.createWorldImagery({
                style : Cesium.IonWorldImageryStyle.AERIAL
            }), 
            terrainProvider: worldTerrain,
        };
        viewer = new Cesium.Viewer('canvasCsm', options); 
        handler = new Cesium.ScreenSpaceEventHandler(viewer.scene.canvas);
    
        // Add Cesium OSM buildings to the scene as our example 3D Tileset.
        osmBuildingsTileset = Cesium.createOsmBuildings();
        viewer.scene.primitives.add(osmBuildingsTileset);
    
        var scene = viewer.scene; 
        resetCameraFunction = function () {
            var geocode = viewer.geocoder.viewModel;
            geocode.searchText = "Indonesia";
            geocode.flightDuration = 1.5;
            geocode.search();
        };
        resetCameraFunction();
        // Color by material checks for null values since not all
        // buildings have the material property.
        colorByMaterial = function () {
        osmBuildingsTileset.style = new Cesium.Cesium3DTileStyle({
            defines: {
            material: "${feature['building:material']}",
            },
            color: {
            conditions: [
                ["${material} === null", "color('white')"],
                ["${material} === 'glass'", "color('skyblue', 0.5)"],
                ["${material} === 'concrete'", "color('grey')"],
                ["${material} === 'brick'", "color('indianred')"],
                ["${material} === 'stone'", "color('lightslategrey')"],
                ["${material} === 'metal'", "color('lightgrey')"],
                ["${material} === 'steel'", "color('lightsteelblue')"],
                ["true", "color('white')"], // This is the else case
            ],
            },
        });
        }  
        showByBuildingType = function (buildingType) {
            switch (buildingType) {
                case "hide":
                osmBuildingsTileset.style = new Cesium.Cesium3DTileStyle({
                    show: "${feature['building']} === ''",
                });  break;
                case "show":
                    colorByMaterial();
                break;
                default:
                break;
            }
        }
    }
    
    // remove the left click input event for selecting a central location
    function removeCoordinatePickingOnLeftClick() {
        document.querySelector(".infoPanel").style.visibility = "hidden";
        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
    }
 
  
    if (typeof Cesium !== 'undefined') {
        window.startupCalled = true;
        startup(Cesium); 
        colorByMaterial();
        $('.cesium-widget-credits').css('display','none'); 
        $("#ddview").on('change',function(){ 
            if(this.selectedIndex == 1) {
                resetCameraFunction(); 
            }else if (this.selectedIndex == 2){
                let disena = this.options[this.selectedIndex].text;
                if(disena == 'Optimize Speed'){
                    viewer.terrainProvider = Cesium.createWorldTerrain({
                        requestWaterMask: false,
                        requestVertexNormals: false,
                    });
                    this.options[this.selectedIndex].innerHTML = 'Optimize Graphic'; 
                }else{
                    viewer.terrainProvider = Cesium.createWorldTerrain({
                        requestWaterMask: true,
                        requestVertexNormals: true,
                    });
                    this.options[this.selectedIndex].innerHTML = 'Optimize Speed'; 
                }  
            }else if (this.selectedIndex == 3){
                let disena = this.options[this.selectedIndex].text;
                if(disena == 'Enable Fog'){
                    viewer.scene.fog.enabled = true;
                    this.options[this.selectedIndex].innerHTML = 'Disable Fog'; 
                }else{
                    viewer.scene.fog.enabled = false;
                    this.options[this.selectedIndex].innerHTML = 'Enable Fog'; 
                } 
            }
            else if (this.selectedIndex == 4){
                let disena = this.options[this.selectedIndex].text;
                if(disena == 'Hide Building'){
                    showByBuildingType("hide");
                    this.options[this.selectedIndex].innerHTML = 'Show Building'; 
                }else{
                    showByBuildingType("show");
                    this.options[this.selectedIndex].innerHTML = 'Hide Building'; 
                } 
            }
            
            this.selectedIndex = 0;
        });   
    }
    function view_airspace(id)
	{   
		// $("#loading_search").show();
        // load_data_airspace(id);		
        var heading = Cesium.Math.toRadians(0);
        var pitch = Cesium.Math.toRadians(-20);
        var entity = viewer.entities.getById(id); 
        // var getPosition = viewer.camera.positionCartographic;
    //     viewer.camera.setView({
    //         destination: Cesium.Cartesian3.fromDegrees(121.06494161474049, -0.5997111874090058, 7000000)
    //     });
        
        orangePolygon.forEach(el => {
            el.show=true;
            if(el.id!==id){
                el.show=false;
            }
        });
        viewer.zoomTo(entity, new Cesium.HeadingPitchRange(heading, pitch));
        viewer.flyTo(entity).then(function(result){ 
            if (result) {
                viewer.selectedEntity = entity;
            }
        });
	}
    function view_airspaces(id_in)
	{    

        let idin =  id_in;
        // console.dir(orangePolygon);
        orangePolygon.forEach(el => {
            let elid = el.id.toString();
            if(idin.indexOf(elid) > -1){ 
                el.show=true;
            }else{ 
                el.show=false; 
            }
        });
	}
    function load_data_airspace(id){ 
        i = airspaces.findIndex(x => x.id === id);   
        drawAirspace(airspaces[i]);  
	}
    function load_data_airspaces(type_in){ 
        airspaces.forEach(function airspace(val,idx) {
            if(airspace.airspace_type==type_in){
                drawAirspace(airspaces[idx]);        
            }
        }); 
          
	}
    function drawAirspace(data) { 
		var geom_lat="0";
		var geom_lon="0";
		var WSGCor = "";
        // console.dir(data);
        // console.dir(data.geom.coordinates[0]);
		if(data.geom !== null)
		{
			var options;
			var mAirCoorAll = data.geom.coordinates[0];
			
			var upperLevel = data.class[0].upper.substring(0,2);
			if(upperLevel ==='FL') {
				upperLevel = parseInt(data.class[0].upper.replace( /^\D+/g, '')) * 100;
			} else {
				upperLevel = parseInt(data.class[0].upper.replace( /^\D+/g, ''));
			}
			var lowerLevel = data.class[0].lower.substring(0,2);
			if(lowerLevel ==='FL') {
				lowerLevel = parseInt(data.class[0].lower.replace( /^\D+/g, '')) * 100;
			} else {
				lowerLevel = parseInt(data.class[0].lower.replace( /^\D+/g, ''));
			}			
			var airSpaceElevation = upperLevel * 0.3048;
            var airSpaceElevationLo = lowerLevel * 0.3048;  

			// var airSpaceElevationLo = parseInt(data.class[0].lower.replace( /^\D+/g, '')) * 10;
            // console.log(airSpaceElevationLo);
			var poly ={};
			
			if(airSpaceElevation){ 
				if(airSpaceElevation < 100) {
					airSpaceElevation = 100;
				}
				
				if(isNaN(airSpaceElevation)) {
					airSpaceElevation = 100;
				}
			} else {
				airSpaceElevation = 100;
			}
           
            if (isNaN(airSpaceElevationLo)){
                airSpaceElevationLo = 0.0;
            }
			var _height = airSpaceElevationLo;
            var extrudePoly = airSpaceElevation - airSpaceElevationLo;
			let cesiumPolygon = [];
			for(var cor = 0; cor<mAirCoorAll.length; cor++)
			{
				cesiumPolygon.push(mAirCoorAll[cor][0]);
				cesiumPolygon.push(mAirCoorAll[cor][1]);
				cesiumPolygon.push(_height);
			}
            switch(data.airspace_type){
            case "TIBA":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.PURPLE.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.PURPLE.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "AFIZ":
                poly = {
                    hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                    perPositionHeight: true,
                    extrudedHeight: extrudePoly,
                    material: Cesium.Color.GREEN.withAlpha(0.3),
                    outline: true,
                    outlineColor: Cesium.Color.GREEN.withAlpha(0.7),
                    arcType: Cesium.ArcType.RHUMB,
                    heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                } 
                break;
            case "ACC":
                poly = {
					hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
					perPositionHeight: true,
					extrudedHeight: extrudePoly,
					material: Cesium.Color.BLUE.withAlpha(0.3),
					outline: true,
					outlineColor: Cesium.Color.BLUE.withAlpha(0.7),
                    arcType: Cesium.ArcType.RHUMB,
                    heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
				} 
                break;
            case "ATZ":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.GAINSBORO.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.GAINSBORO.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "CTA":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.ORANGE.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.ORANGE.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "CTR":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.SKYBLUE.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.SKYBLUE.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "FIR":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.RED.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.RED.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "SECTOR":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.KHAKI.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.KHAKI.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "MTCA":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.CHOCOLATE.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.CHOCOLATE.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "TMA":
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.OLIVE.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.OLIVE.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            case "UTA": 
            // console.dir(cesiumPolygon);
                poly = {
                        hierarchy: Cesium.Cartesian3.fromDegreesArrayHeights(cesiumPolygon),
                        perPositionHeight: true,
                        extrudedHeight: extrudePoly,
                        material: Cesium.Color.AQUA.withAlpha(0.3),
                        outline: true,
                        outlineColor: Cesium.Color.AQUA.withAlpha(0.7),
                        arcType: Cesium.ArcType.RHUMB,
                      //  heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    } 
                break;
            }
			  
			var mAirPop ="";
			mAirPop += "<b><h4>" + data.airspace_name + " - " + data.airspace_type + "</h4></b>";
			var asp_map_detail_msg ="";
			asp_map_detail_msg+="<table>";
			asp_map_detail_msg+="<tr><td class='popup_td1'>Name</td><td class='popup_td2'>: "+data.airspace_name+"</td></tr>";
			asp_map_detail_msg+="<tr><td class='popup_td1'>Type</td><td class='popup_td2'>: "+data.airspace_type+"</td></tr>";
			if (data.airspace_type === 'FIR') {
				asp_map_detail_msg+="<tr><td class='popup_td1'>RVSM</td><td class='popup_td2'>: "+data.rvsm+"</td></tr>";
				asp_map_detail_msg+="<tr><td class='popup_td1'>RVSM Upper Limit</td><td class='popup_td2'>: "+data.rvsm_upper+"</td></tr>";
				asp_map_detail_msg+="<tr><td class='popup_td1'>RVSM Lower Limit</td><td class='popup_td2'>: "+data.rvsm_lower+"</td></tr>";
			}
			asp_map_detail_msg+="<tr><td class='popup_td1'>ICAO Acc</td><td class='popup_td2'>: "+data.icao_acc+"</td></tr>";
			asp_map_detail_msg+="<tr><td class='popup_td1'>ATS Unit</td><td class='popup_td2'>: "+data.ats_unit+"</td></tr>";
			// asp_map_detail_msg+="<tr><td class='popup_td1'>Ident</td><td class='popup_td2'>: "+data.class[0].asp_id+" </td></tr>";
			asp_map_detail_msg+="<tr><td class='popup_td1'>Upper Limit</td><td class='popup_td2'>: "+data.class[0].upper+"</td></tr>";
			asp_map_detail_msg+="<tr><td class='popup_td1'>Lower Limit</td><td class='popup_td2'>: "+data.class[0].lower+"</td></tr>";
			asp_map_detail_msg+="</table>"; 
			
			orangePolygon[data.id] = viewer.entities.add({ 
                id:data.id,
                name: data.airspace_name,
				polygon: 
					poly
				,
			});
			orangePolygon[data.id].description = asp_map_detail_msg;
          
			// viewer.zoomTo(viewer.entities); 
			// $("#loading_search").hide(); 
		}
	}
    </script>
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
var head_tab, _head_tab, foot_tab;
head_tab ='<table class="table" style="width:100%" id="tabel_init">'+
                    '<thead class="thead-dark">'+
                    '<tr align="center">'+
                        // '<th>#</th>'+
                        '<th class="sorting_disabled">'+ 
                            '<div class="custom-control custom-control-sm custom-checkbox notext">'+
                            '<input type="checkbox" class="custom-control-input" id="all_cc_view" value="all" name="all_cc_view">'+
                            '<label class="custom-control-label" for="all_cc_view" ><button id="all_look" class="btn btn-sm btn-primary" onclick="allLook();">Show</button>'+
                            '</div>'+
                        '</th>'+
                        '<th>Name</th>'+
                        '<th>Type</th>'+
                        '<th><span class="d-none d-sm-inline">Icao</span></th>'+
                        '<th>ATS Unit</th>'+
                    '</tr>'+
                '</thead><tbody class="asplist">';
_head_tab ='<table class="table" style="width:100%;display:none" id="_tabel_init">'+
                '<thead class="thead-dark">'+
                '<tr align="center">'+
                    // '<th>#</th>'+
                    '<th>'+ 
                    '</th>'+
                    '<th>Name</th>'+
                    '<th>Type</th>'+
                    '<th><span class="d-none d-sm-inline">Icao</span></th>'+
                    '<th>ATS Unit</th>'+
                '</tr>'+
            '</thead><tbody class="asplist">';

foot_tab = '</tbody></table>';
var ctnTable = $('#ctnTable'); 
$.ajax({
        url: pathdetail,
        data: {ctry: 'ID', deleted: 0},
        type: "json",
        method: "GET",

        success: function (result) {
            $.each(result.data, function (k, v) { 
                airspaces.push(v);
                type=v.airspace_type; 
                switch(v.airspace_type){
                    case "AFIZ": afiz.push(v); break;
                    case "ATZ": atz.push( v ); break;
                    case "CTA": cta.push(v ); break;
                    case "CTR": ctr.push(v ); break;
                    case "FIR": fir.push( v ); break;
                    case "SECTOR": fss.push( v); break;
                    case "MTCA": mtca.push( v ); break;
                    case "TIBA": tiba.push( v ); break;
                    case "TMA": tma.push( v ); break;
                    case "UTA": uta.push( v ); break;
                }
            });  
        } 
    });

function Drawline(asp){   
    var hasil ='';
    asp.forEach(a=>{   
        // draw poly
        load_data_airspace(a.id);
        // generate table detail 
        hasil += '<tr>'+
                    // '<td class="tb-tnx-action">'+
                    //     '<div class="dropdown">'+
                    //         '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    //         '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                    //             '<ul class="link-list-plain">'+
                    //                 // '<a class="btn btn-dim btn-secondary"><i class="icon ni ni-view-grid"></i>Detail</a>'+
                    //                 '<a class="btn btn-dim btn-info" onclick="view_airspace('+ a.id +')"><i class="icon ni ni-map"></i>Show</a>'+
                    //             '</ul>'+
                    //         '</div>'+
                    //     '</div>'+
                    // '</td>'+
                    '<td class="nk-tb-col nk-tb-col-check">'+
                        '<div class="custom-control custom-control-sm custom-checkbox notext">'+
                            '<input type="checkbox" class="custom-control-input cc_view" value="'+a.id+'" id="'+a.id+'">'+
                            '<label class="custom-control-label" for="'+a.id+'"></label>'+
                        '</div>'+
                    '</td>'+
                    '<td>' + a.airspace_name + '</td>'+
                    '<td>' + a.airspace_type + '</td>'+
                    '<td>' + a.icao_acc + '</td>'+
                    '<td>' + a.ats_unit + '</td>'+
                    '</tr>'; 
    }) ;
    var tabel_full = head_tab + hasil + foot_tab;
    var _tabel_full = _head_tab + hasil + foot_tab; 
    if ( $( "#tabel_init" ).length < 1) {
        ctnTable.append(tabel_full);
        ctnTable.append(_tabel_full);
        $('#tabel_init').DataTable({
            columnDefs: [{
                    orderable: false, 
                    className: 'select-checkbox',
                    targets: 0
                }],  
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
            "dom": '<"row justify-between g-2" <"col-7 col-sm-6 text-left" f><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
        }).rows().invalidate('data').draw('false');
    }
    else{
        let oldCtn = $('#_tabel_init > tbody').html();
        var newCtn = oldCtn + hasil; 
        ctnTable.empty();
        ctnTable.append(head_tab + newCtn + foot_tab);
        ctnTable.append(_head_tab + newCtn + foot_tab); 
        $('#tabel_init').DataTable({
            columnDefs: [{
                    orderable: false, 
                    className: 'select-checkbox',
                    targets: 0
                }], 
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
            "dom": '<"row justify-between g-2" <"col-7 col-sm-6 text-left" f><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
        }).rows().invalidate('data').draw('false');    
    }
} 
 
$(".checkbox").change(function() {
    // clear list table asp
    var hasil='';
    let type = this.id.toUpperCase(); 
    try {
        airspaces.forEach(airspace => {
            if(airspace.airspace_type == type){
                let ob = orangePolygon[airspace.id];
                if(this.checked && airspace.id != '') {
                    if(ob === undefined) throw airspace.airspace_type;
                    if(ob !== undefined) {
                        ob.show=true;
                        hasil += '<tr>'+
                                    // '<td class="tb-tnx-action">'+
                                        // '<div class="dropdown">'+
                                        //     '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                        //     '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                                        //         '<ul class="link-list-plain">'+
                                        //             // '<a class="btn btn-dim btn-secondary"><i class="icon ni ni-view-grid"></i>Detail</a>'+
                                        //             '<a class="btn btn-dim btn-info" onclick="view_airspace('+ airspace.id +')"><i class="icon ni ni-map"></i>Show</a>'+
                                        //         '</ul>'+
                                        //     '</div>'+
                                        // '</div>'+
                                    // '</td>'+
                                    '<td class="nk-tb-col nk-tb-col-check">'+
                                        '<div class="custom-control custom-control-sm custom-checkbox notext">'+
                                            '<input type="checkbox" class="custom-control-input _cc_view" value="'+airspace.id+'" id="'+airspace.id+'">'+
                                            '<label class="custom-control-label" for="'+airspace.id+'"></label>'+
                                        '</div>'+
                                    '</td>'+
                                    '<td>' + airspace.airspace_name + '</td>'+
                                    '<td>' + airspace.airspace_type + '</td>'+
                                    '<td>' + airspace.icao_acc + '</td>'+
                                    '<td>' + airspace.ats_unit + '</td>'+
                                    '</tr>'; 
                    }
                }else{
                    ob.show = false;
                    $('.asplist tr').each(function(){
                        var tr = $(this); 
                        if (tr.find('td:eq(2)').text()== airspace.airspace_type ) tr.remove();
                    });
                }   
            }
        });
    } catch (e) {
        showPoly(e);
    }
    // redraw tabel
    if($('#_tabel_init').length > 0 ){
        let oldCtn = $('#_tabel_init > tbody').html();
        var newCtn = oldCtn + hasil;
        ctnTable.empty();
        ctnTable.append(head_tab + newCtn + foot_tab); 
        $('#tabel_init').DataTable({ 
            columnDefs: [{
                    orderable: false, 
                    className: 'select-checkbox',
                    targets: 0
                }],  
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
            "dom": '<"row justify-between g-2" <"col-7 col-sm-6 text-left" f><"col-5 col-sm-6 text-right"l> > <"datatable-wrap my-3"t> <"row align-items-center" <"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>><"clear">',
        }).rows().invalidate('data').draw('false');
        ctnTable.append(_head_tab + newCtn + foot_tab);
    }
});  

function showPoly(id) {
    switch(id) {
        case "FIR": Drawline(fir); break;
        case "CTA": Drawline(cta); break;
        case "SECTOR": Drawline(fss); break;
        case "MTCA": Drawline(mtca); break;
        case "TIBA": Drawline(tiba); break;
        case "UTA": Drawline(uta); break;  
        case "TMA": Drawline(tma); break;
        case "CTR": Drawline(ctr); break;
        case "AFIZ": Drawline(afiz); break;
        case "ATZ": Drawline(atz); break;
    }
}
ctnTable.on("change", "th.select-checkbox", function() {
    let sele = $("th.select-checkbox").hasClass("selected");
    let ceks = $(this).closest('table').find('td input');
     
    if (sele===true) { 
        $("th.select-checkbox").removeClass("selected");
    } else { 
        $("th.select-checkbox").addClass("selected");
    }
    for (var i=0; i<ceks.length; i++) { 
        ceks[i].checked = !ceks[i].checked; // toggle the checkbox 
    } 
}); 
function allLook(){
    let arr_id =[];
    let ceks = $('.asplist').find('td input:checkbox'); 
    for (var i=0; i<ceks.length; i++) { 
        if(ceks[i].checked){
            arr_id.push(ceks[i].value); 
        } 
    } 
    view_airspaces(arr_id);
} 
</script>
@endsection
