@extends('layouts.app')

@section('template_title')
    ILS 
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title" id="ilstitle"></h6>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tabItem1"><span>ILS/LLZ</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabItem2"><span>Marker Beacon</span></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tabItem1">
                    <form action="api/ils/save" method="post"  enctype="multipart/form-data" id="ilsform">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="ils_id" id="ils_id">
                        <input type="hidden" name="nav_id" id="nav_id">
                        <input type="hidden" name="status" id="status">
                        <input type="hidden" name="geom" id="geom">
                        <input type="hidden" name="arpt_ident" id="arpt_ident">
                        <input type="hidden" name="gs_geom" id="gs_geom">
                        <input type="hidden" name="stat_decl" id="stat_decl">
                        <input type="hidden" name="parent" id="parent" value="{{$parent}}">
                        <input type="hidden" name="parentid" id="parentid" value="{{$parentid}}">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>ICAO</strong>
                                <br>
                                <input id="icao" type="text" style="text-transform:uppercase" class="form-control">
                            </div>
                            <template v-if="isarpt">
                                <section class="content">
                                    <ArptSearch-component navsearch="navsearch" ref="nav" status="ats" @finished="finished" />
                                </section>
                            </template>
                            <div class="col-md-2">
                                <strong>Runway</strong>
                                <br>
                                <select id="rwy_id" class="form-control" name="rwy_id">
                                
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong>Ident</strong>
                                <br>
                                <input id="ils_ident" ref="ils" type="text" maxlength="4" style="text-transform:uppercase" class="form-control" name="ils_ident">
                            </div>
                            <div class="col-md-2">
                                <strong>Frequency</strong>
                                <br>
                                <input id="freq" name="freq" style="text-transform:uppercase" type="text" class="form-control" onfocusout="checkfreq(this.id)">
                            </div>
                            <div class="col-md-4">
                                <strong>Name</strong>
                                <br>
                                <input id="ils_name" name="ils_name" style="text-transform:uppercase" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>Latitude</strong>
                                <br>
                                <input id="lat" name="lat" onfocusout="CheckCoordinateFormat(this.id,'LAT')" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" placeholder="06300000S">
                            </div>
                            <div class="col-md-2">
                                <strong>Longitude</strong>
                                <br>
                                <input id="lon" name="lon" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('lat','lon');calmagvar()" style="text-transform:uppercase" maxlength="10" type="text" class="form-control"  placeholder="106300000E">
                            </div>
                            <div class="col-md-2">
                                <strong>Category</strong>
                                <br>
                                <select id="ils_cat" class="form-control" name="ils_cat">
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong>GS Freq</strong>
                                <br>
                                <input type="text" class="form-control" id="gs_freq" name="gs_freq">
                            </div>
                            <div class="col-md-2">
                                <strong>GS Latitude</strong>
                                <br>
                                <input id="gs_lat" name="gs_lat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" id="gslat" placeholder="06300000S" onfocusout="CheckCoordinateFormat(this.id,'LAT')">
                            </div>
                            <div class="col-md-2">
                                <strong>GS Longitude</strong>
                                <br>
                                <input id="gs_lon" name="gs_lon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" id="gslon" placeholder="106300000E" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('gs_lat','gs_lon')">
                            </div>
                            <div class="col-md-2">
                                <strong>ILS Elevation</strong>
                                <br>
                                <input type="number" class="form-control" name="gs_hgt" id="gs_hgt">
                            </div>
                            <div class="col-md-2">
                                <strong>GS Angle</strong>
                                <br>
                                <input id="gs_angle" type="text" class="form-control" name="gs_angle">
                            </div>
                            <div class="col-md-2">
                                <strong>GS Elevation</strong>
                                <br>
                                <input type="number" class="form-control" name="gs_elev"  id="gs_elev">
                            </div>
                            <div class="col-md-2">
                                <strong>Opr. Hours</strong>
                                <br>
                                <input id="opr_hrs" style="text-transform:uppercase" name="opr_hrs" type="text" class="form-control" id="opr_hrs">
                            </div>
                            <div class="col-md-2">
                                <strong>T-DME</strong>
                                <br>
                                <select id="dme_avail" onchange="hideoff()" class="form-control" name="dme_avail">
                                    <option value="N">NO</option>
                                    <option value="Y">YES</option>
                                </select>
                            </div>
                        
                            <div id="wrapper">
                                <div class="card-inner" id="coldme" style="visibility:hidden">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong>Channel</strong>
                                            <br>
                                            <input style="text-transform:uppercase" type="text" class="form-control" id="channel" name="channel">
                                        </div>
                                        <div class="col-md-2">
                                            <strong>DME Latitude</strong>
                                            <br>
                                            <input id="dmelat"  name="dme_lat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control"  placeholder="06300000S" onfocusout="CheckCoordinateFormat(this.id,'LAT')">
                                        </div>
                                        <div class="col-md-2">
                                            <strong>DME Longitude</strong>
                                            <br>
                                            <input id="dmelon" name="dme_lon" ref="dmelon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" placeholder="106300000E" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('dmelat','dmelon')">
                                        </div>
                                        <div class="col-md-2">
                                            <strong>DME Range</strong>
                                            <br>
                                            <input id="dme_range" type="text" name="dme_range" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <strong>DME Elevation</strong>
                                            <br>
                                            <input type="number" class="form-control" id="dme_elev" name="dme_elev">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <strong>Remarks</strong>
                                <br>
                                <textarea type="text" class="form-control" name="remarks" id="remarks"></textarea>
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
                            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                &nbsp;
                            <a onclick="setMapPoint()" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Set Point</a>&nbsp;
                            <a onclick="update()" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</a>
                            
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem2">
                    <form action="api/ils/marker/save" method="post"  enctype="multipart/form-data" id="markerform">
                        <input type="hidden" name="_token" id="mrkrtoken" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="mrkreditor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" id="mrkrid">
                        <input type="hidden" name="mrkr_id" id="mrkrmrkr_id">
                        <input type="hidden" name="ils_id" id="mrkrils_id">
                        <input type="hidden" name="status" id="mrkrstatus">
                        <input type="hidden" name="geom" id="mrkrgeom">
                        <input type="hidden" name="arpt_ident" id="mrkrarpt_ident">
                        <input type="hidden" name="parent" id="parent" value="{{$parent}}">
                        <input type="hidden" name="parentid" id="parentid" value="{{$parentid}}">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Marker</strong>
                                <br>
                                <select id="mrkr_id" name="mrkr_id" onclick="selectmarker()" class="form-control">
                                    
                                </select>
                            </div>
                            <div class="col-md-4">
                                <strong>Marker Type</strong>
                                <br>
                                <select id="mrkr_mrkr_type" name="mrkr_type" class="form-control">
                                
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong>Latitude</strong>
                                <br>
                                <input id="mrkr_lat" name="mrkr_lat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control"  placeholder="06300000S" onfocusout="CheckCoordinateFormat(this.id,'LAT')">
                            </div>
                            <div class="col-md-2">
                                <strong>Longitude</strong>
                                <br>
                                <input id="mrkr_lon" name="mrkr_lon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control"  placeholder="106300000E" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('mrkr_lat','mrkr_lon')">
                            </div>
                            <div class="col-md-2">
                                <strong>Frequency</strong>
                                <br>
                                <input id="mrkr_freq" name="freq" style="text-transform:uppercase" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>Elevation</strong>
                                <br>
                                <input id="mrkr_elev" name="elev" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>Opr. Hours</strong>
                                <br>
                                <input id="mrkr_opr_hrs" name="opr_hrs" style="text-transform:uppercase" ref="oprhrs" type="text" class="form-control">
                            </div>
                            <!-- <div class="col-md-2">
                                <strong>Col. Locator</strong>
                                <br>
                                <select id="mrkr_co_loc" name="co_loc" onchange="Colloc()" class="form-control">
                                    <option value="N">NO</option>
                                    <option value="Y">YES</option>
                                </select>
                            </div> -->
                            <!-- <div class="card-inner" id="mrkr_coldme" style="visibility:hidden">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Point 1</strong>
                                        <br>
                                        <b><p id="point_1"></p></b>
                                    </div>
                                    <div class="col-md-12" id="search1" style="visibility: hidden">
                                        <select name="select2" id="select21" class="form-control select2">
                                    </div>
                                    <div class="col-md-12" style="visibility: hidden">
                                        <strong>ID</strong>
                                        <br>
                                        <input style="visibility: hidden" type="text" class="form-control"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Loc. Ident</strong>
                                        <br>
                                        <input id="mrkr_loc_id" name="loc_id" style="text-transform:uppercase" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Loc Lat</strong>
                                        <br>
                                        <input id="mrkr_loclat" name="loclat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control"  placeholder="06300000S" onfocusout="CheckCoordinateFormat(this.id,'LAT')">
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Loc Lon</strong>
                                        <br>
                                        <input id="mrkr_loclon" ref="loclon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control"  placeholder="106300000E" onfocusout="CheckCoordinateFormat(this.id,'LON')">
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Loc Freq</strong>
                                        <br>
                                        <input id="mrkr_locfreq" name="locfreq" style="text-transform:uppercase" type="text" class="form-control">
                                    </div>
                                </div> -->
                            <!-- </div> -->
                            <div class="col-md-12">
                                <strong>Remarks</strong>
                                <br>
                                <textarea type="text" class="form-control" id="mrkr_remarks" name="remarks"></textarea>
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
                            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                &nbsp;
                            <a onclick="setMapPointmarker()" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Set Point</a>&nbsp;
                            <a onclick="updatemarker()" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a onclick="newmarker()" class="btn btn-dim btn-secondary"><i class="icon ni ni-plus"></i> Add Marker</a>
                            
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
        <div class="row">
            <div class="col-md-12" align="right">
                <i style="color:red" align="right">RED Color = Data change request</i>
                <br>
                <i style="color:darkgrey" id="arptidname"></i>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$('#coldme').hide();
$('#mrkr_coldme').hide();

var ilscat= [{
    key: '1',
    value: 'CAT I'
}, {
    key: '2',
    value: 'CAT II'
}, {
    key: '3',
    value: 'CAT IIIA'
}, {
    key: '4',
    value: 'CAT IIIB'
}, {
    key: '5',
    value: 'CAT IIIC'
}, {
    key: '6',
    value: 'CAT IIID'
}];
var markertype= [{
                key: 'OM',
                value: 'Outer Marker'
            }, {
                key: 'LOM',
                value: 'Locator Outer Marker'
            }, {
                key: 'MM',
                value: 'Middle Marker'
            }, {
                key: 'IM',
                value: 'Inner Marker'
            }, {
                key: 'BC',
                value: 'Back Course Marker'
            }]
var parent=@json($parent);
var parentid=@json($parentid);
var id=@json($id);
var ils =@json($ils)[0];
var ilstemp =@json($ilstemp)[0];
var ident =@json($ident);
var arp =@json($arpt)[0];

var no=0;
var ch =@json($channel);frq='';channel='';marker=''
// console.log(ilstemp);
ilscat.forEach(i=>{
    var hs='<option value="'+ i.key +'">'+ i.value +'</option>'
    $("#ils_cat").append(hs)
})
markertype.forEach(i=>{
    var hs='<option value="'+ i.key +'">'+ i.value +'</option>'
    $("#mrkr_mrkr_type").append(hs)
})
var fld=['rwy_id', 'ils_ident', 'ils_name', 'ils_cat', 'freq','stat_decl', 'gs_freq', 'gs_angle', 'gs_hgt','gs_elev', 'id','ils_id', 'nav_id', 'opr_hrs', 'remarks','lat','lon','gs_lat','gs_lon']
var fldnav=['nav_id','channel', 'dme_elev','dmelat','dmelon'];
var fldmkr = ['id','mrkr_id','ils_id','loc_id', 'mrkr_type','freq','co_loc','geom', 'bear', 'elev','opr_hrs', 'remarks'];

$("#icao").val(arp.icao);
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#arpt_ident").val(arp.arpt_ident);
$("#mrkrarpt_ident").val(arp.arpt_ident);
arp.runwaystemp.forEach(rwy=>{
        var thr1=rwy.physicals[0];
        var thr2=rwy.physicals[1];
        // console.log(rwy)
        $("#rwy_id").append('<option value="'+ thr1.rwy_key +'">RWY '+ thr1.rwy_ident +'</option>')
        $("#rwy_id").append('<option value="'+ thr2.rwy_key +'">RWY '+ thr2.rwy_ident +'</option>')
        // console.log(rwy)
    })
if (id=='edit'){
    
    var crd=SetCoordinatebyGeom(ilstemp.geom)
    var gs_crd=SetCoordinatebyGeom(ilstemp.gs_geom)
    var ttl=ilstemp.ils_ident + ' ILS/LLZ - ' + ilstemp.ils_name + ' Information';
    $("#ilstitle").html(ttl);
    
    $("#status").val('R');
   
    ilstemp['lat']=crd.Database[1];
    ilstemp['lon']=crd.Database[0];
    ilstemp['gs_lat']=gs_crd.Database[1];
    ilstemp['gs_lon']=gs_crd.Database[0];
    $("#dme_avail").val('N');
    $("#mrkrils_id").val(ilstemp.ils_id);
    var frqd=FreqFormat(ilstemp.freq,'11','DATA');
    $("#freq").val(frqd);
    channel =ch.find( x => x.definition === frqd ).id;
    if (ils){
        crd=SetCoordinatebyGeom(ils.geom)
        gs_crd=SetCoordinatebyGeom(ils.gs_geom)
        ils['lat']=crd.Database[1];
        ils['lon']=crd.Database[0];
        ils['gs_lat']=gs_crd.Database[1];
        ils['gs_lon']=gs_crd.Database[0];
        
    }else{
        ils=[]
    }
    compareisidata(fld,ilstemp,ils);
    if (ilstemp.navaid.length >0 ){
        $("#dme_avail").val('Y');
        aboutvol('coldme');
        crd=SetCoordinatebyGeom(ilstemp.navaid[0].geom)
        ilstemp.navaid[0]['dmelat']=crd.Database[1];
        ilstemp.navaid[0]['dmelon']=crd.Database[0];
        var inav=[];
        if (ils.length !==0){
            if (ils.navaid.length >0 ){
                inav=ils.navaid[0];
                crd=SetCoordinatebyGeom(ils.navaid[0].geom)
                ils.navaid[0]['dmelat']=crd.Database[1];
                ils.navaid[0]['dmelon']=crd.Database[0];
    
            }
            
        }
        compareisidata(fldnav,ilstemp.navaid[0],inav);
        $("#channel").val(channel);
    }
//    console.log(ilstemp.marker)
    if (ilstemp.marker.length==0){
            $("#mrkrstatus").val('N');
    }else{

        ilstemp.marker.forEach(mkr=>{
            // console.log(mkr)
            // selectmarker(mkr);
            var opt='<option value="'+ mkr.mrkr_id+'">'+ mkr.mrkr_type +'</option>';
            $("#mrkr_id").append(opt);
        
        })
        
    }

        // console.log(frqd,channel)
  
}else if (id=='new'){
    var ttl='New '+ ident + ' ILS/LLZ';
    var newid='ILS_' + arp.arpt_ident + '_' + ident + '_1'
    $("#status").val('N');
    $("#ils_name").val(arp.arpt_name);
    $("#ils_id").val(newid);
    $("#ils_ident").val(ident);
    $("#dme_avail").val('N');
    $("#ilstitle").html(ttl);
}
function newmarker(){
    $("#mrkrstatus").val('N');
    var fldmkrnew = ['freq','elev','opr_hrs', 'remarks','mrkr_lat','mrkr_lon'];
    clearinput(fldmkrnew,'mrkr')
    $("#mrkr_freq").val('75');
}
function selectmarkertype(){
    var mt=$("#mrkr_mrkr_type").val();
    if (mt=='LOM'){
        $("#mrkr_co_loc").val('Y');
       
    }else{
        $("#mrkr_co_loc").val('N');
    }
    Colloc();
    $('#point_1').html(Symbolnewpoint('New Point 1','spoint1'))
}

function update(){
    console.log('update')
    var checkrwy=false;
    var sts=$("#status").val();
    console.log(sts)
    if  (sts=='N'){
        var fldn=['rwy_id', 'ils_ident', 'ils_name', 'freq', 'gs_freq', 'dme_avail', 'ils_id', 'lat','lon','gs_lat','gs_lon']
        checkrwy =checknewdata(fldn);
        setinputtoupper(fldn);
    }else if (sts=='R'){

        checkrwy =checkupdatedata(fld,ilstemp);
        setinputtoupper(fld);
        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#ilsform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        // backtolist();
    }
   
}

function updatemarker(){
    
    console.log('update')
    var checkrwy=false;
    var sts=$("#mrkrstatus").val();
    var fldmkrn = ['mrkr_type','freq','opr_hrs','mrkr_lat','mrkr_lon'];
    if  (sts=='N'){
        var hh=$("#mrkr_opr_hrs").val();
       if (hh !== null || hh !== ''){
            hh=hh.toUpperCase();
            $("#mrkr_opr_hrs").val(hh);
       }
        var mkrid='MRK_'+$("#mrkrarpt_ident").val()+'_'+$("#ils_ident").val()+'_'+$("#mrkr_mrkr_type").val();
        $("#mrkrmrkr_id").val(mkrid);
        checkrwy =checknewdata(fldmkrn,"mrkr");
        
    }else if (sts=='R'){
        checkrwy=true;
        // var fldmkru = ['mrkr_type','co_loc', 'elev','opr_hrs', 'remarks'];
        // var idmrk= $("#mrkr_id").val();
        // var ip= ilstemp.marker.findIndex(x=>x.mrkr_id=idmrk);
        // var mkrtemp=ilstemp.marker[ip];
        // // console.log(mkrtemp,idmrk,ip)

        // checkrwy =checkupdatedata(fldmkru,mkrtemp,"mrkr");
        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#markerform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        // backtolist();
    }
   
}
function calmagvar(){
    var epoch = new Date().toISOString().substr( 0, 10 );
    var lat1= $("#lat").val();
    var lon1 =$("#lon").val();
    // console.log(epoch,lat1,lon1);
    var crd=SetCoordinate(lat1,lon1);
    var mv =GetMagvar( crd.Decimal[0], crd.Decimal[1], epoch );
    // console.log(crd,mv);
    $("#stat_decl").val(mv.magvar)
    // var mv = GetMagvar( longitude1, latitude1, epoch );

}
function hideoff(){
    var yn=$("#dme_avail").val();
    console.log(yn)
    if (yn=='N'){
        if ($("#coldme").is(':visible')==true){
            aboutvol('coldme');
        }
        
    }else{
        if ($("#coldme").is(':visible')==false){
            aboutvol('coldme');
        }
    }

}
function Colloc(){
    var yn=$("#mrkr_co_loc").val();
    console.log(yn)
    if (yn=='N'){
        if ($("#mrkr_coldme").is(':visible')==true){
            aboutvol('mrkr_coldme');
        }
        
    }else{
        if ($("#mrkr_coldme").is(':visible')==false){
            aboutvol('mrkr_coldme');
        }
    }
    // aboutvol('mrkr_coldme');
}
function selectmarker(mkr=null){
    // if ( $("#mrkrstatus").val()=='R'){
    //    console.log(mkr)
   
        if (mkr==null){
            var mkrid=$("#mrkr_id").val();
            var ix =ilstemp.marker.findIndex( x => x.mrkr_id === mkrid );
            mkr=ilstemp.marker[ix]
            console.log(mkr,'asdadasdasdasdas')
        }
        // $("#mrkr_id").val(mkr.mrkr_type);
        $("#mrkrmrkr_id").val(mkr.mrkr_id);
        $("#mrkr_mrkr_type").val(mkr.mrkr_type);
        $("#mrkrid").val(mkr.id);
        $("#mrkrils_id").val(mkr.ils_id);
        $("#mrkrstatus").val('R');
        var cr=SetCoordinatebyGeom(mkr.geom)
        $("#mrkr_lat").val(cr.Database[1]);
        $("#mrkr_lon").val(cr.Database[0]);
        $("#mrkr_freq").val(mkr.freq);
        $("#mrkr_elev").val(mkr.elev);
        $("#mrkr_remarks").val(mkr.remarks);
        $("#mrkr_opr_hrs").val(mkr.opr_hrs);
        if (mkr.navaid.length >0){
            $("#mrkr_co_loc").val('Y');
        }else{
            $("#mrkr_co_loc").val('N');
        }
        
    // }
    // console.log(mkr)
//  console.log(mkr)
}
function setMapPoint() {
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=navaid&id=' + ils.ils_id, 'Set Latitude and Longitude', params)
}

function setMapPointmarker() {
   var idmrk= $("#mrkr_id").val();
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=navaid&id=' + idmrk, 'Set Latitude and Longitude', params)
}

function NewData(){
    aboutvol('rwyedit');
    aboutvol('rwytable');
}
function  checkfreq(id) {
    var nfreq=$("#"+id).val();
    var ntype='11';
    var frqd=FreqFormat(nfreq,ntype,'DATA');
    
    // console.log(id,frqd)

    if (ntype == '5' || ntype == '7' || ntype == '10') {
        if (parseFloat(frqd) < 190 || parseFloat(frqd) > 1750) {
            Swal.fire(
                'Data Failed!',
                'this frequency Not support for NDB',
                'error'
            )
        }else{
            $("#freq").val(parseFloat(frqd) * 1000)
        }

    } else if (ntype == '1' || ntype == '2' || ntype == '4') { 
        var idx =ch.findIndex( x => x.definition === frqd );
        if (idx == -1){
            Swal.fire(
                'Data Failed!',
                'this frequency Not support for VOR',
                'error'
            )
        }else{
            if (ch[idx].ils_yes=='Y'){
                Swal.fire(
                    'Data Failed!',
                    'this frequency is support for ILS',
                    'error'
                )
            }else{
                $("#channel").val(ch[idx].id)
                $("#freq").val(parseFloat(frqd) * 10000)
            }
        }

    } else if (ntype == '11') { 
        var idx =ch.findIndex( x => x.definition === frqd );
        if (idx == -1){
            Swal.fire(
                'Data Failed!',
                'this frequency Not support for ILS',
                'error'
            )
        }else{
            if (ch[idx].ils_yes !=='Y'){
                Swal.fire(
                    'Data Failed!',
                    'this frequency is support for VOR',
                    'error'
                )
            }else{
                $("#channel").val(ch[idx].id)
                $("#gs_freq").val(ch[idx].gs_freq)
                $("#freq").val(frqd)
            }
        }
    }
}

function backtomain(){
    
    window.scroll(0,0);
    aboutvol('rwyedit');
    aboutvol('rwytable');
}


function backtolist(){
    history.back();
}

function Symbolnewpoint(point,vis){
    return '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown">'+point+'</a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">'+
                    '<ul class="link-list-plain">'+
                    '<a id="'+ vis +'" onclick="changepoint(this.id)" class="btn btn-dim btn-success col-md-12"><i class="icon ni ni-plus"></i> New Point</a>'+
                    '</ul></div>'+
            '</div>';
}

</script>
@endsection