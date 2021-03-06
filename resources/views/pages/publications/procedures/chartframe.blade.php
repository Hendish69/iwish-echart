@extends('layouts.app')

@section('template_title')
    Charts
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
            <!-- <div class="row mt" id="dataholding" style="visibility: visible">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <h5 class="panel-title" id="titleholding">Chart Properties</h5>
                    </div>
                </div>
                <div class="panel-heading col-md-12">
                    <button onclick="backtomenu()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                </div>
               
                <div class="col-md-4 mt-3">
                    <strong>ICAO</strong>
                    <br>
                    <input type="text" class="form-control" onfocusout="searchats()" name="icao" id="icao" placeholder= "search Airport by location indicator..."">
                </div>
                <div class="col-md-12 mt-3">
                    <table class="table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th>Frame</th>
                                <th>Used Chart</th>
                                <th>Scale</th>
                                <th>Grid</th>
                            </tr>
                        </thead>
                        <tbody id="holdinglist">

                        </tbody>
                    </table>
                </div>
            </div> -->
            <!-- <div id="bmdetail" style="visibility: hidden"> -->
                <div class="nk-block-between">
                    <h5 class="panel-title" id="bmedit">Frame Properties</h5>
                </div>
                <div class="panel-body mt-3">
                    <form action="../../api/frame/save" method="post"  enctype="multipart/form-data" id="frameform_">
                        <input type="hidden" name="_token" id="frm_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="frm_editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" id="frm_id">
                        <input type="hidden" name="frame_size" id="frame_size">
                        <input type="hidden" name="status" id="frm_status">
                        <input type="hidden" name="arpt_ident" id="arpt_ident" value="{{$airport[0]->arpt_ident}}">
                        <input type="hidden" name="listchart" id="listchart">
                        <input type="hidden" name="frame" id="frame">
                        <input type="hidden" name="area" id="area">
                        <div class="card-inner table-bordered mt-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Chart Type</strong>
                                    <br>
                                    <select id="frm_chart_type" name="chart_type" onchange="changechartcode()" class="form-control" >
                                    <option value="">None</option>
                                    @foreach ($cod as $k)
                                            <option value="{{$k->id}}">{{$k->description}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <!-- <div class="col-md-6">
                                    <strong>Chart Name</strong>
                                    <br>
                                    <select id="charts" name="charts" class="form-control" >
                                    </select>
                                </div> -->
                                <div class="col-md-6">
                                    <strong>Frame Name</strong>
                                    <br>
                                    <input id="frm_chart_id" type="text" class="form-control" name="chart_id">
                                </div>
                                <div class="col-md-6">
                                    <strong>Paper Size</strong>
                                    <br>
                                    <select id="frm_paper_size" name="paper_size" onchange="paperchange()" class="form-control" >
                                
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <strong>Scale</strong>
                                    <br>
                                    <input id="frm_scale" type="number" onkeyup="GetGrid()" class="form-control" name="scale">
                                </div>
                                <div class="col-md-3">
                                    <strong>Grid Interval</strong>
                                    <br>
                                    <input id="frm_grid" type="number" class="form-control" name="grid">
                                </div>
                                <!-- <div class="col-md-2">
                                    <strong>Radius</strong>
                                    <br>
                                    <input id="frm_radius" type="text" class="form-control">
                                <div> -->
                            </div>
                            <div class="row" id="overlapid" style="visibility: hidden">
                                <div class="col-md-6">
                                    <strong>Overlap</strong>
                                    <br>
                                    <select id="overlap" onchange="viewframe()" class="form-control" >
                                
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <strong>Size</strong>
                                    <br>
                                    <input id="frm_size" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="card-inner table-bordered mt-1" id="customid" style="visibility: hidden">
                        <p>Customize Size</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card-inner table-bordered">
                                        <p class="card-title" style="text-align:center"><strong>Panel Size</strong></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong >Length (m)</strong>
                                                <input type="number" class="form-control" id="frm_length_panel" name="length_panel" value="148"/>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Width (m)</strong>
                                                <input type="number" class="form-control" id="frm_width_panel" name="width_panel" value="210"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-inner table-bordered">
                                        <p class="card-title" style="text-align:center"><strong>Number of Panels</strong></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong >Right</strong>
                                                <input type="number" class="form-control" onkeyup="calcpaper(this.id)" id="frm_panel_hor" name="panel_hor"/>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Down</strong>
                                                <input type="number" class="form-control" onkeyup="calcpaper(this.id)" id="frm_panel_ver" name="panel_ver"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-inner table-bordered">
                                        <p class="card-title" style="text-align:center"><strong>Paper Size Chart</strong></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong >Length (m)</strong>
                                                <input type="number" class="form-control"  id="frm_length" name="length" />
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Width (m)</strong>
                                                <input type="number" class="form-control" id="frm_width" name="width"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <button onclick="isback()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                            &nbsp;
                            <button onclick=updateholding() id="btn_save" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
                            &nbsp;
                            <button onclick="Drawall()" class="btn btn-dim btn-info"><em class="icon ni ni-map"></em> Show</button>
                        </div>
                    </div>
                </div>
                <div id="usedbychart">
                    <div class="nk-block-between mt-3">
                        <h5 class="panel-title" id="bmedit">Used By</h5>
                    </div>
                    <table class="table table-bordered table-hover mt-3" id="table-proc">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th class="active">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th>No</th>
                                <th>Chart</th>
                            </tr>
                        </thead>
                        <tbody id="usedchart">

                        </tbody>
                    </table>
                </div>
            <!-- </div> -->
        </div>
        <div class="row p-4" id="mapid">
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
        <!-- <div id="mapid" style="visibility: hidden" class="leaflet-container leaflet-fade-anim leaflet-grab leaflet-touch-drag" tabindex="0" style="position: relative;">
        </div> -->
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

var charts =@json($chart);arpt=@json($airport);cod=@json($cod);bm=@json($bm);paper=@json($paper);proc=@json($proc);edit=@json($edit);
var oldgeom='';geompoly='';  geommove='';geomrot='';oldpaper='';oldscale=''; mline=[];listchart=[];
// console.log(cod,paper,tbl)
document.getElementById("btn_save").disabled = true;
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

$("#mapid").hide();$("#usedbychart").hide();
$("#search1").hide();$("#bmdetail").hide();$("#dataholdingdetail").hide();$("#customid").hide();$("#overlapid").hide(); htemp=[];hcurr=[];
var frmfld=['chart_id','grid','idx','scale','width_panel','length_panel','frame_size'];


var overlap=[{
            key: 'none',
            value: 'None'
        },{
            key: 'all',
            value: 'ALL'
        },{
            key: 'tr',
            value: 'Top Right'
        },{
            key: 'tl',
            value: 'Top Left'
        },{
            key: 'tb',
            value: 'Top Bottom'
        },{
            key: 'lb',
            value: 'Left Bottom'
        },{
            key: 'rb',
            value: 'Right Bottom'
        },{
            key: 'lr',
            value: 'Left Right'
        }]
        overlap.forEach(o=>{
            var hsl= '<option value="'+o.key+'">'+o.value+ '</option>';
                $("#overlap").append(hsl);
        })
        //default paper size group by definition
        papersize()

bm.forEach(r=>{
    var hsl= '<option value="'+r.chart_id+'">'+r.chart_id+'</option>';
                $("#bm_id").append(hsl);
})

// console.log(bm,cod,arpt,edit)
if (edit=='new'){
    NewData();
}else{
    Editframe();
}
function calcpaper(id){
    console.log(id)
    var ppj=$("#"+id).val();lgh=$("#frm_length_panel").val();wdt=$("#frm_width_panel").val();hsll=0;hslw=0;
    if (id=='frm_panel_hor'){
        hsll=ppj*lgh;
        $("#frm_length").val(hsll)
    }else{
        hslw=ppj*wdt;
        $("#frm_width").val(hslw)
    }
    $("#frame_size").val(hsll+'X'+hslw)
}

    // bm.forEach(v=>{
    // // console.log(v.chart)
    //     var cht='Not Used';
    //     if (v.chart.length > 0){
    //         cht='Used by '+ v.chart.length + ' charts'
    //     }
    
    //     var  hsl= '<tr>'+
    //         '<td class="tb-tnx-action">'+
    //             '<div class="dropdown">'+
    //                 '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
    //                 '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
    //                     '<ul class="link-list-plain">'+
    //                         '<a class="btn btn-dim btn-primary col-md-12" id='+ v.id +' onclick="Editframe(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
    //                         '<a class="btn btn-dim btn-info col-md-12" id='+ v.id +' onclick="viewframe(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
    //                         '<a class="btn btn-dim btn-danger col-md-12" id='+ v.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
    //                     '</ul>'+
    //                 '</div>'+
    //             '</div>'+
    //         '</td>'+
    //         '<td>'+ v.chart_id +'</td>'+
    //         '<td>'+ cht +'</td>'+
    //         '<td>'+ v.scale +'</td>'+
    //         '<td>'+ v.grid +'</td>'+
    //     '</tr>';
    //     $("#holdinglist").append(hsl);
    // });
    
// $("#dataholdingdetail").hide();

// var input = document.getElementById("icao");
// // Execute a function when the user releases a key on the keyboard
// input.addEventListener("keyup", function(event) {
//     if (event.keyCode === 13) {
//         // Cancel the default action, if needed
//         event.preventDefault();
//         searchats();
//         // document.getElementById("ats_ctry").onchange();
//     }
// });

if (arpt.length > 0){
    $("#titleholding").html(arpt[0].icao + ' ' + arpt[0].city_name + '/'+ arpt[0].arpt_name + ' Chart Frames')
}
function NewData(){
    // if ($("#bmdetail").is(':visible')==false){
    //     aboutvol('bmdetail');
    // }
    // if ($("#dataholding").is(':visible')==true){
    //     aboutvol('dataholding');
    // }
    htemp=null;
    document.getElementById("btn_save").disabled = true;
    $("#holdingedit").html('New Holding')
    $("#frm_status").val('N')
    $("#frm_chart_id").val('')
    $("#frm_scale").val('')
    $("#frm_grid").val('')
    $("#frm_chart_type").val('')
    $("#frm_paper_size").val('')

    if ($("#usedbychart").is(':visible')==true){
        aboutvol('usedbychart');
    }
    $("#usedchart").empty()

  
}
function Editframe(){
    // if ($("#bmdetail").is(':visible')==false){
    //     aboutvol('bmdetail');
    // }
    // if ($("#dataholding").is(':visible')==true){
    //     aboutvol('dataholding');
    // }
    window.scroll(0,0);
    document.getElementById("btn_save").disabled = false;
    $("#frm_status").val('R')

    htemp=bm[0];hcurr=bm[0]; mline=[];
    var crd=htemp.frame.coordinates[0]
        // console.log(htemp,crd)
        var gg='';lata='';lona='';
        for (let i=0;i<crd.length;i++){
            var lat1=crd[i][1];
            var lon1=crd[i][0];
            if (i==0){
                lata=lat1
                lona=lon1
                gg=lon1 + ' ' + lat1
            }else{
                gg += ','+ lon1 + ' ' + lat1
            }
            mline.push([lat1,lon1]);
            
        }
        gg +=',' + lona + ' ' + lata;
        geompoly='POLYGON(('+gg+'))';
        oldgeom='POLYGON(('+gg+'))';
    $("#frm_id").val(htemp.id)
    $("#frm_chart_id").val(htemp.chart_id)
    $("#frm_scale").val(htemp.scale)
    oldscale=htemp.scale;
    $("#frame_size").val(htemp.frame_size)
    $("#frm_grid").val(htemp.grid)
    if (htemp.chart.length >0){
        $("#frm_chart_type").val(htemp.chart[0].chart_type)
    }
    if (htemp.frame_size !== null || htemp.frame_size !==''){
        var ppz=htemp.frame_size.split('X');
        var px=ppz[0];py=ppz[1];
        var ix=paper.findIndex(x=>x.pg_x_dim===px && x.pg_y_dim===py)
        if (ix !== -1){
            ppr=paper[ix];
            oldpaper=ppr.definition;
            $("#frm_paper_size").val(ppr.definition)
        
        }

    }
    changechartcode();
   
}
function listusedchart(charttype){
    if ($("#usedbychart").is(':visible')==false){
        aboutvol('usedbychart');
    }
    $("#usedchart").empty();no=1;
    charts.forEach(p=>{
        
        var cht='';
        // if (p.nav==null && p.cat==null && p.rwy==null){
        //     cht=p.chart_id
        // }else  if (p.nav==null){
        //     cht=p.chart_id + ' RWY '+ p.rwy + ' ' +  p.cat
        // }else{
        //     cht=p.chart_id + ' RWY '+ p.rwy + ' ' + p.nav + ' ' + p.cat
        // }
        cht=p.chart_name
        if (p.chart_type==charttype){
            var iix=-1;
            // console.log(htemp,htemp !=='')
            if (htemp){

                iix= htemp.chart.findIndex(x=>x.id===p.id)

            }
            // console.log(iix,p)
            var chk='';
            if (iix !==-1){
                chk='checked';
            }
            var  hsl='<tr>'+
                        '<td class="active">'+
                            '<input type="checkbox" class="select-item checkbox" name="select-item" value="'+p.id+'"'+chk+'/>'+
                        '</td>'+
                        '</td>'+
                        '<td>'+ no +'</td>'+
                        '<td>'+ cht +'</td>'+
                    '</tr>';
                    $("#usedchart").append(hsl);
                    no++
        }
    });
    // console.log(htemp.chart)

}
function checkinbound(type){
    if (type=='crs'){
        ccrs=$("#crs").val();
        if (ccrs==''){
            Swal.fire(
            'Invalid Data!',
            'Inbound cannot be empty !!!',
            'error'
            )
        } else if (Number(ccrs) > 360){
            Swal.fire(
            'Invalid Data!',
            'Inbound is not correct !!!',
            'error'
            )
        }
    }else if (type=='time'){
        ccrs=$("#leg_time").val();
        if (ccrs==''){
            $("#leg_time").val('1.0')
        } 
    }

}
function remove(id){
    // console.log(id)
    $("#frm_id").val(id) 
    $("#frm_status").val('D')
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $("#frameform_").submit()
        }else{
            location.reload();
        }
    })
}

function updateholding(){

    if (geomrot !== '' || geommove !=='' ){
        if (geomrot !== ''){
            geompoly='POLYGON(('+geomrot+'))';
        }else{
            geompoly='POLYGON(('+geommove+'))';
        }
    }
    $("input.select-item").each(function (index,item) {
        if (item.checked==true){
            listchart.push(item.value)

        }
                // item.checked = checked;
    });
    $("#listchart").val(listchart);
    $("#area").val(geompoly)
    $("#frame").val(geompoly)
    // console.log(listchart.length,'listchart.length')
    if ($("#frm_chart_id").val()=='' || listchart.length == 0 ){
        Swal.fire(
            'Invalid Data!',
            'frame name or Chart selection cannot be empty !!!',
            'error'
            )
            // location.reload()
    }else{
        
        $("#frameform_").submit()
    }

    
}
function backto (){
    aboutvol("mapid"); aboutvol("bmdetail")
}
function viewframearea(){
    if ($("#mapid").is(':visible')==false){
        aboutvol('mapid');
    }
    if ($("#bmdetail").is(':visible')==true){
        aboutvol('bmdetail');
    }
   
   
    var scl=$("#frm_scale").val();
    var ppr = $("#frm_paper_size").val()
    // console.log(oldscale , scl , oldpaper , ppr)
    if (ppr=='custom'){
        pl=$("#frm_length").val();
        pw=$("#frm_width").val();
    }else{
        var ix=paper.findIndex(x=>x.definition===ppr)
        if (ix !== -1){
            pl=paper[ix].pg_x_dim
            pw=paper[ix].pg_y_dim
            
            }
            
    }
    var aar=arpt[0];
    proctext = aar.icao + ' - ' + aar.city_name + ' / ' + aar.arpt_name + ' <br> Scale 1 : ' + scl + ' <br> Frame Size : ' + pl + ' X ' + pw
    // console.log(oldscale , scl , oldpaper , ppr)
    if (oldscale !== scl || oldpaper !== ppr){
        mline=[];
        var dimX=Getpapersize(Number(scl),pl)
        var dimY=Getpapersize(Number(scl),pw)
        
        lat =  aar.geom.coordinates[1]
        lon = aar.geom.coordinates[0]
        pMinX = lon - (dimX/2)
        pMaxY = lat + (dimY/2)
        pMaxX = pMinX + dimX
        pMinY = pMaxY - dimY
        
        mline.push([pMaxY,pMinX]);
        mline.push([pMaxY,pMaxX]);
        mline.push([pMinY,pMaxX]);
        mline.push([pMinY,pMinX]);
        mline.push([pMaxY,pMinX]);
        var gg=pMinX + ' ' + pMaxY +',' + pMaxX + ' ' + pMaxY +',' + pMaxX + ' ' + pMinY +',' + pMinX + ' ' + pMinY +',' + pMinX + ' ' + pMaxY 
        geompoly='POLYGON(('+gg+'))';
        console.log(geompoly)
    }

	// console.log(geompoly,'AASLI')
            var clr= {
                color: 'blue',
                weight: 3,
                opacity: 0.5,
                smoothFactor: 1,
				draggable: true,
				transform: true,
				fillOpacity: 0,
			};

            // var markers = L.layerGroup();
            // markers.clearLayers();
          
            var polygon = L.polygon(mline,clr)
			.addTo(map)
			.bindPopup(proctext)
			.bindTooltip(proctext)
			map.fitBounds(polygon.getBounds());
            polygon.addTo(map);
			polygon.bindTooltip(proctext,
					{permanent: true, direction:"center"}
					).openTooltip()
	
                    polygon.on('dragend', function(e) {
                        var ce=e.target._latlngs[0];
                        geomrot='';
                        geommove=ce[0].lng + ' ' + ce[0].lat +',' + ce[1].lng + ' ' + ce[1].lat +',' + ce[2].lng + ' ' + ce[2].lat +',' + ce[3].lng + ' ' + ce[3].lat +',' + ce[0].lng + ' ' + ce[0].lat
                    
				// console.log(ce[0].lng,'MOVE');
                // console.log(e.target);
			});
            polygon.on('rotateend', function(e) {
                var ce=e.target._latlngs[0];
                geommove='';
                geomrot=ce[0].lng + ' ' + ce[0].lat +',' + ce[1].lng + ' ' + ce[1].lat +',' + ce[2].lng + ' ' + ce[2].lat +',' + ce[3].lng + ' ' + ce[3].lat +',' + ce[0].lng + ' ' + ce[0].lat;
               
                // geomrot='';
                // geommove=pMinX + ' ' + pMaxY +',' + pMaxX + ' ' + pMaxY +',' + pMaxX + ' ' + pMinY +',' + pMinX + ' ' + pMinY +',' + pMinX + ' ' + pMaxY 
                // console.log(e.target._latlngs[0][0],'ROTATE 1');
                // console.log(e.target._latlngs[0][1],'ROTATE 2');
                // console.log(e.target._latlngs[0][2],'ROTATE 3');
                // console.log(e.target._latlngs[0][3],'ROTATE 4');
            });
            // polygon.dragging.enable();
            polygon.transform.enable();
            polygon.transform.enable({rotation: true, scaling: false});
           
    
}

function isback(){
    window.location.href = '/chartprop/' + arpt[0].arpt_ident + '/pro' ;
    // if (tbl=='pro'){
    //     aboutvol("dataholdingdetail");aboutvol("dataholding");

    // }else{
    //     aboutvol("bmdetail");aboutvol("dataholding");
    // }
}
function searchats(){
    var ident=$("#icao").val().toUpperCase()
    window.scrollTo(0,0);
    // window.location.href = '/atsdetail/' + ident;
    $.ajax({
        url: '../api/airports',
        data: {'icao' : ident},
        type: "json",
        method: "GET",

            success: function (result) {
                var jmlwpt=result.data.length
                    // console.log(jmlwpt)
                $.each(result.data, function (k, v) {
                    // console.log(v,cod);
                        window.location.href = '/chartprop/' + v.arpt_ident + '/' + tbl;
                    // dt.push(v)
                })
            }
    })

}

function backtomenu(){
    if (tbl=='pro'){
        window.location.href = '/listairport/chartprop';

    }else{
        window.location.href = '/listairport/frame';
    }
}

function viewframe(id=null){
    var idcht='';
    if (id==null){
        var chrtid=$('#bm_id').val();
        idcht=bm.find(x=>x.chart_id===chrtid).id;

    }else{
        idcht=id;
    }
    // console.log(idcht)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=frame&id='+idcht, 'Set Latitude and Longitude', params)
}
function createnamebmcode(){
    var id=  $("#frm_chart_type").val()
    var ppr = $("#frm_paper_size").val()
    var scccl = $("#frm_scale").val()
    // console.log(id,ppr,scccl)
    if (id !=='' && ppr !==null && scccl!== ''){
        if (ppr=='custom'){
            pl=$("#frm_length").val();
            pw=$("#frm_width").val();
        }else{
            var ix=paper.findIndex(x=>x.definition===ppr)
            if (ix !== -1){
                pl=paper[ix].pg_x_dim
                pw=paper[ix].pg_y_dim
                
                }
                
        }
        var aar=arpt[0];
        var il=cod.findIndex(c=>c.id===Number(id))
        var nm=aar.icao+'_'+cod[il].definition +'_'+pl +'x'+pw +'_'+id +'_'+scccl
        $("#frm_chart_id").val(nm);
        $("#frame_size").val(pl+'X'+pw);
        $("#frm_scale").val(scccl)
    }
}
function changechartcode(){
    var id=  $("#frm_chart_type").val()
    createnamebmcode()

    // console.log(id)
        // overlapshow(id);
        listusedchart(id)
        // var bmch='';
        // $("#charts").empty();
        // charts.forEach(r=>{
        //     if (r.chart_type==id.toString()){
        //         // console.log($("#frm_chart_id").val())
        //         if (r.basemap[0].chart_id==$("#frm_chart_id").val()){
        //             bmch=r.chart_id
        //         }
        //         var hsl= '<option value="'+r.chart_id+'">'+r.chart_id + ' RWY '+ r.rwy +'</option>';
        //         $("#charts").append(hsl);

        //     }
        // })
        // $("#charts").val(bmch);
    // var ix=proc.findIndex(p=>p.chart_type===id.toString())
    // if ($("#procidlist").is(':visible')==true){
    //     aboutvol('procidlist');
    // }
    // if (ix !==-1){
    //     if ($("#procidlist").is(':visible')==false){
    //         aboutvol('procidlist');
    //     }
    //     $("#proclist").empty();no=1;
    //     proc.forEach(p=>{
    //         if (p.chart_type==id){
    //             console.log(p)
    //             var  hsl='<tr>'+
    //                     '<td class="active">'+
    //                         '<input type="checkbox" class="select-item checkbox" name="select-item" value="'+p.id+'" />'+
    //                     '</td>'+
    //                     // '<td>'+
    //                     // '<div class="custom-control custom-checkbox">'+
    //                     //     '<input type="checkbox" class="custom-control-input" id="'+p.id+'">'+
    //                     //     '<label class="custom-control-label" for="'+p.id+'">'+no+'</label>'+
    //                     // '</div>'+
    //                     '</td>'+
    //                     '<td>'+ p.proc_name +'</td>'+
    //                     '<td>'+ p.proc_text +'</td>'+
    //                 '</tr>';
    //                 $("#proclist").append(hsl);
    //                 no++
    //         }
    //     })

    // }
    // var x = id.options[id.selectedIndex].text;
    // $('#fixpoint').html(Symbolnewpoint(x,'spoint1'))
}
$(function(){
        //button select all or cancel
        $("#select-all").click(function () {
            var all = $("input.select-all")[0];
            all.checked = !all.checked
            var checked = all.checked;
            $("input.select-item").each(function (index,item) {
                item.checked = checked;
            });
        });
        //column checkbox select all or cancel
        $("input.select-all").click(function () {
            var checked = this.checked;
            $("input.select-item").each(function (index,item) {
                item.checked = checked;
            });
        });
        //check selected items
        $("input.select-item").click(function () {
            var checked = this.checked;
            var all = $("input.select-all")[0];
            var total = $("input.select-item").length;
            var len = $("input.select-item:checked:checked").length;
            all.checked = len===total;
        });
        
    });
function setMapPoint(procid) {
    // console.log(procid)
    pi=proc.findIndex(x=>x.id===Number(procid))
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=proc&id='+proc[pi].proc_id, 'Set Latitude and Longitude', params)
}
function Drawall(){
    document.getElementById("btn_save").disabled = false;
   
    if ($("#frm_scale").val()=='' || $("#frm_paper_size").val()=='' ){
        Swal.fire(
            'Invalid Data!',
            'Paper Size and scale cannot be empty !!!',
            'error'
            )
            // location.reload()
    }else{
        map.eachLayer(function (layer) {
            // console.log(layer)
            map.removeLayer(layer);
        });
        initmap()
        viewframearea()
        $("input.select-item").each(function (index,item) {
            if (item.checked==true){
                chts=Number(item.value);
                var ix=charts.findIndex(x=>x.id===chts)
                // console.log(ix,chts)
                if (ix !== -1){
                    var cc=charts[ix]
                    if (cc.procedure.length > 0){
                        cc.procedure.forEach(p=>{
                            if (p.proc_id !== ''){
                                // console.log(p,'ppppp')
                                // Drawproc(p.proc_id)
                                    var pi=proc.findIndex(x=>x.proc_id===p.proc_id)
                                    Drawproc(proc[pi])
                                
                            }
                        })
                    }
                }
                // Drawproc(proc[pi])
    
            }
                    // item.checked = checked;
        });
        
    }


    
}
function Drawproc(prc){
   
    if ($("#mapid").is(':visible')==false){
        aboutvol('mapid');
    }
    if ($("#bmdetail").is(':visible')==true){
        aboutvol('bmdetail');
    }
   
    
  
    // var proced = L.layerGroup();
    // map.removeLayer(proced)
    // aboutvol("mapid"); aboutvol("bmdetail")
    var pr=prc;
    // console.log(pr,'Drwaproc');
   var  pname= pr.proc_name + ' ' + pr.airport[0].icao + ' ' + pr.airport[0].arpt_name  + ' Procedure';
   var  ptext=pr.proc_text;
    // console.log(pr.procseg[0].trans[0],'pr.procseg[0]');
    rtcharttype=pr.segment[0].transition[0].rt_type;
    pr.segment.forEach(tr => {
        if (tr.transition[0].geom !== null){

            var gm=tr.transition[0].geom;
            var trpnt=tr.transition[0].segment;
            var crd=gm.coordinates[0];
            rttypes=tr.transition[0].rt_type;
            subchart=tr.transition[0].sub_chart_type;
            pline=[];
            for (let i=0;i<crd.length;i++){
                lat1=crd[i][1];
                lon1=crd[i][0];
                pline.push([lat1,lon1]);
    
            }
            for (let i=0;i<trpnt.length;i++){
                if (trpnt[i].fix_id=='' || trpnt[i].fix_id==null){
    
                }else{
                    if (trpnt[i].center_fix !== null){
                        holding(trpnt[i].center_fix)
                    }
                    var altpnt;
                    var altdesc=trpnt[i].alt_desc;
                    switch(altdesc){
                        case "+":
                            altpnt = 'At or above ' + trpnt[i].alt1;
                            break;
                        case "-":
                            altpnt = 'At or below ' + trpnt[i].alt1;
                            break;
                        case "@":
                            altpnt = 'At ' + trpnt[i].alt1;
                            break;
                        default:
                            altpnt = trpnt[i].alt1;
                            break;
    
                    }
                        if (altpnt==null){
                            altpnt='';
                        }
                        console.log('rtcharttype',rtcharttype,rttypes,subchart)
                        if (trpnt[i].fix_id.substr(0,3)=='WPT'){
                            var wptsym='1';
                            if (rttypes=='R' || rtcharttype=='R' || subchart=='462' || subchart=='472'){
                                wptsym='5';
                            }
                                DrawNavaid(trpnt[i].fix_id,wptsym,altpnt)
                            
                        }else{
                            DrawNavaid(trpnt[i].fix_id,'',altpnt)
                        }//
                    
                    // pointsproc.push(trpnt[i].fix_id);
                }
                if (trpnt[i].recd_nav=='' || trpnt[i].recd_nav==null){
    
                }else{
                    DrawNavaid(trpnt[i].recd_nav,'')
                }
    
            }
            // console.log('APP',tr.trans[0].rt_type);
            var clr;
            if (rttypes=='Z'){
                clr= {
                    weight: 4,
                    opacity: 0.5,
                    color: '#732924',
                    dashArray: '5 5',
                };
            }else{
                clr= {
                    weight: 3,
                    opacity: 1,
                    color: 'black',
                }
            }
            // console.log(mline);
            var polyline = L.polyline(pline,clr)
            .addTo(map)
            .bindPopup(ptext)
            .bindTooltip(pname)
            map.fitBounds(polyline.getBounds());
            map.addLayer(polyline);
           
        }

        // pointsproc.forEach(a=>{
        //     if (a.substr(0,3)=='WPT'){
        //         DrawNavaid(a,'1')
        //     }else{
        //         DrawNavaid(a,'')
        //     }// co
        // })
        // }
    // plotline()
        
    })
}
function holding(tableid,tablename){
		var url,tbl,procname,proctext,lat1,lon1,rttypes,rtcharttype,subchart;
        var mline=[];pointsproc=[];
    $.ajax({
        url: '../../api/holding/temp',
        data:  {'id': tableid},
        type: "json",
        method: "GET",

        success: function (result) {
            var jmlwpt=result.data.length
                // console.log(jmlwpt)
            $.each(result.data, function (k, v) {
				var crs =v.crs/10 + '??';
				var lat='';lon='';
				if (v.min_alt == null){
					procname=v.max_alt + "'";
				}else{

					procname=v.min_alt + "' - " + v.max_alt + "'";
				}
				if (v.navaid.length >0){
					proctext=v.navaid[0].nav_ident +' '+v.navaid[0].definition+ '<br>Inbound : ' + crs + '<br>Alt : ' + procname + '<br>Leg : ' + v.leg_time/10 + ' Min';
					lat=v.navaid[0].geom.coordinates[1];
					lon=v.navaid[0].geom.coordinates[0];
					DrawNavaid(v.fix_id,'',v.max_alt)
					
				}else{
					proctext=v.waypoint[0].desc_name + '<br>Inbound : ' + crs + '<br>Alt : ' + procname + '<br>Leg : ' + v.leg_time/10 + ' Min';
				
					var wptsym='1';
					if (v.mag=='N'){
						wptsym='5';
					}
					DrawNavaid(v.fix_id,wptsym,v.max_alt)
					lat=v.waypoint[0].geom.coordinates[1];
					lon=v.waypoint[0].geom.coordinates[0];
				}
				

				
				// var hasil= createholding(lat,lon,v.crs/10,v.turn);
                    mline=[];
					var gm=v.poly;
					if (gm !== null){
						
						var crd=gm.coordinates[0];
						for (let i=0;i<crd.length;i++){
							lat1=crd[i][1];
							lon1=crd[i][0];
							mline.push([lat1,lon1]);
		
						}
					}

                    
                    // console.log('APP',tr.trans[0].rt_type);
                    var clr;
                        clr= {
                            weight: 3,
                            opacity: 1,
                            color: 'black',
							fillOpacity: 0,
                        }
                    
                    // console.log(mline);
                    var polyline = L.polygon(mline,clr)
                    .addTo(map)
                    .bindPopup(proctext)
                    .bindTooltip(procname)
                    map.fitBounds(polyline.getBounds());
					polyline.bindTooltip(procname,
								{permanent: true, direction:"center"}
								).openTooltip()
                    // pointsproc.forEach(a=>{
                    //     if (a.substr(0,3)=='WPT'){
                    //         DrawNavaid(a,'1')
                    //     }else{
                    //         DrawNavaid(a,'')
                    //     }// co
                    // })
                    // }
                // plotline()
					
            })
        }
    });
                
}
function DrawNavaid(navid,type='',alt='') {
		this.url='';dt=''
	    var tbl=''
		var gs=false
		if (navid.substr(0,3) =='WPT'){
			tbl='waypoint'
			this.url ='../../api/waypoint/temp/list'
            dt={'wpt_id':navid};
		}else if (navid.substr(0,3) =='NAV'){
			this.url = '../../api/navaid/temp/list' 
            dt={'nav_id':navid};
			tbl='navaid'
		}else if (navid.substr(0,3) =='ILS'){
			this.url = '../../api/ils/temp'
            dt={'ils_id':navid};
			gs=true
			// console.log(this.url)
			tbl='arpt' // di set hanya utk mengambil symbol saja utk ILS ambil symbol arpt military (bulat)
		}else if (navid.substr(0,3) =='RWY'){
			this.url = '../../api/rwy/thr/temp'
            dt={'rwy_key':navid};
			type='5';
			tbl='rwy' // di set hanya utk mengambil symbol saja utk ILS ambil symbol arpt military (bulat)
		}else if (navid.substr(0,3) =='MRK'){
			this.url = '../../api/ils/temp/marker' 
            dt={'mrkr_id':navid};
			type='1';
			tbl='marker' // di set hanya utk mengambil symbol saja utk ILS ambil symbol arpt military (bulat)
		}

		// console.log('NAVAID FUNCTION ',navid,this.url)
		var nmgs=''
		var pane = map.createPane('myPane');
		var marker = L.marker({pane: 'myPane'});
		pane.style.zIndex = 10;
        $.ajax({
            url: this.url,
            data:  dt,
            type: "json",
            method: "GET",

            success: function (result) {
                var jmlwpt=result.data.length
                    // console.log(jmlwpt)
                $.each(result.data, function (k, v) {
                    // console.log(v,cod);
                    var cord = SetCoordinatebyDecimal(v.geom.coordinates[0],v.geom.coordinates[1])
                    // console.log(cord)
                    var cordlat=cord.WGSIAC[1]
                    var cordlon=cord.WGSIAC[0]
							// console.log(cordlat)
							// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
				if (tbl == 'navaid'){
					var freq =FreqFormat(v.freq,v.type)
					nmt = v.nav_ident + ' ' + v.definition + '<br>' + alt
					nm = '<p class="text-center">' + v.nav_name + '<br><b>' + v.definition + ' ' + freq + '<br>' + v.nav_ident + '</b><br>' + cordlat + '<br>' + cordlon + '</p>'
				}else if (tbl == 'arpt'){
					nmt =v.ils_ident + '<br>' + alt
                    nm = '<br>' + v.ils_ident + '</br>for RWY ' + v.rwy_ident + '<br>' + cordlat + '<br>' + cordlon 
                }else if (tbl == 'rwy'){
                    tbl='waypoint';
					nmt ='RWY ' + v.rwy_ident + '<br>' + alt
                    nm = '<b>' + v.rwy_ident + '</b><br>' + cordlat + '<br>' + cordlon 
                }else if (tbl == 'marker'){
                    tbl='waypoint';
					nmt = v.mrkr_type + '<br>' + alt
					nm = '<b>' + v.mrkr_type + '</b><br>' + cordlat + '<br>' + cordlon 
				}else{
					nmt =v.desc_name + '<br>' + alt
					nm = '<b>' + v.desc_name + '</b><br>' + cordlat + '<br>' + cordlon 
				}

				
				this.typ=''
				// console.log('TYPE ',type)
				if (type ==''){
					if (tbl == 'arpt'){
					this.typ='3'
					}else{
						this.typ=v.type
					}
				}else{
					this.typ=type
				}
				var lat,lon,symb= null;
				lat =  v.geom.coordinates[1]
				lon = v.geom.coordinates[0]
				symb = Getsymbol(this.typ,tbl)
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


				if (gs == true){
                    var cord = SetCoordinatebyDecimal(v.gs_geom.coordinates[0],v.gs_geom.coordinates[1])
                    // console.log(cord)
                    var cordlat=cord.WGSIAC[1]
                    var cordlon=cord.WGSIAC[0]
				
								// console.log(cordlat)
								// nm= + '<br>Lat ' + cordlat[2] + '<br>Lon ' + cordlon[2] 
					nmgs= 'GS ' + v.gs_freq + '<br>' + cordlat + '<br>' + cordlon 
					var lat,lon,symb= null
					lat =  v.gs_geom.coordinates[1]
					lon = v.gs_geom.coordinates[0]
					symb = Getsymbol(this.typ,tbl)
                    var myIcon = L.icon({
							iconUrl: symb,
							iconSize: [25,25],
						})
					var gs = L.marker([lat,lon],{icon: myIcon})
						.addTo(map)
						.bindPopup(nmgs)
						.openPopup()
						// map.setView([lat,lon],10)
				}
                    // dt.push(v)
                })
            }
        })
		
			// marker.openTooltip()
			// marker.openPopup()

	}
function papersize(){
    $("#frm_paper_size").empty();
    var hsl= '<option value="custom">Custom</option>';
    $("#frm_paper_size").append(hsl);
    paper.sort((a,b) => (a.definition > b.definition) ? 1 : ((b.definition > a.definition) ? -1 : 0));
    var pdef='';
    paper.forEach(r=>{
        if (pdef !== r.definition){
            hsl = '<option value="'+r.definition+'">'+r.definition+ ' ('+  r.pg_x_dim+' x '+ r.pg_y_dim +')</option>';
            $("#frm_paper_size").append(hsl);

        }

            pdef=r.definition
    })

}
function paperchange(){
    if ($("#frm_paper_size").val()=='custom'){
        if ($("#customid").is(':visible')==false){
        aboutvol('customid');
        }
    }else{
        if ($("#customid").is(':visible')==true){
        aboutvol('customid');
        }
    }
    createnamebmcode()
   
}
function overlapshow(chartid){
    if (chartid=='20' || chartid=='23' || chartid=='24'){
        if ($("#overlapid").is(':visible')==false){
            aboutvol('overlapid');
        }
    }else{
        if ($("#overlapid").is(':visible')==true){
            aboutvol('overlapid');
        }
    }
}
function GetGrid(){

    var scl=Number($("#frm_scale").val());
    createnamebmcode()
    //    var hsl =0;rslt=0;
    //     hsl = (1000000 / scl)
    // console.log(scl / 2500000)
        if (scl / 1000000 > 1){
            rslt =(scl / 1000000).toFixed();
        } else{
            rslt =(scl / 1000000).toFixed(2);
            if (rslt < 0.2){
                rslt=0.1
            }else if (rslt > 0.2 && rslt < 0.5){
                rslt=0.25
            }else if (rslt >= 0.5 && rslt < 0.75){
                rslt=0.5
            }else{
                rslt=0.75
            }

        }
    $("#frm_grid").val(rslt)

}
</script>
@endsection