@extends('layouts.app')

@section('template_title')
@switch($chart)
    @case (47)
        STAR
        @break
    @case ("46")
        SID
        @break
    @case (45)
        IAP
        @break
@endswitch
@endsection

@section('head')

@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="panel-heading">
                <h5 class="panel-title" id="arptname"></h5>
                <h6 class="panel-title" id="judullist"></h6>
            </div>
            <div class="row" id="datatranssegment" style="visibility: visible">
                <div class="col-md-12 mt-3">
                    <div class="panel-heading">
                        <button onclick="backtomenu()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                    </div>
                    <table class="table table-bordered table-hover mt-3" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th></th>
                                <th>Trans Ident</th>
                                <th>Sequence</th>
                                <th>Path Term</th>
                                <th>Fix</th>
                                <th>Turn</th>
                                <th>Cource</th>
                                <th>Distance</th>
                                <th>Alt</th>
                            </tr>
                        </thead>
                        <tbody id="seglist">
                            
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <button onclick="showmap()" class="btn btn-dim btn-info"><em class="icon ni ni-map"></em> Show</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="datatransegdetail" style="visibility: hidden">
                <div class="panel-body mt-3">
                    <form action="../api/transition/temp/save" method="POST" id="trans_form">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="backto" id="backto" value="{{ $backto }}">
                        <input type="hidden" name="arpt_ident" id="arpt_ident">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="trans_id" id="trans_id">
                        <input type="hidden" name="proc_id" id="proc_id">
                        <input type="hidden" name="seq_num" id="seq_num">
                        <input type="hidden" name="fix_id" id="fix_id">
                        <input type="hidden" name="dist_cal" id="dist_cal">
                        <input type="hidden" name="dist_to_thr" id="dist_to_thr">
                        <input type="hidden" name="status" id="status">
                        <input type="hidden" name="geom" id="geom">
                        <input type="hidden" id="recd_nav" name="recd_nav">
                        <input type="hidden" id="recd_nav1" name="recd_nav1">
                        <input type="hidden" id="rwy_trans" name="rwy_trans">
                        <div class="row table-bordered">
                            <div class="col-md-12">
                                <div class="card-inner table-bordered mt-1">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong>Trans Name</strong>
                                            <br>
                                            <input id="trans_ident" type="text" onfocusout="checktransname()"  class="form-control" name="trans_ident" style="text-transform:uppercase">
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Chart Type</strong>
                                            <br>
                                            <select id="chart_type" name="chart_type" onchange="transcombo();wptdesc(false)" selected="selected" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Route</strong>
                                            <br>
                                            <select id="sub_chart_type" name="sub_chart_type" onchange="subcharttype()" selected="selected" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Nav Spec</strong>
                                            <br>
                                            <select id="nav_spec" name="nav_spec" onchange="navspecttype()" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>RWY</strong>
                                            <br>
                                            <select id="rwy_id" name="rwy_id" onchange="runways(this)"selected="selected" class="form-control" >
                                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-inner table-bordered mt-1">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <strong>Transition</strong>
                                            <br>
                                            <select id="rt_type" name="rt_type" onchange="routetype()" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>RNAV</strong>
                                            <br>
                                            <select id="rnav" name="rnav" class="form-control" >
                                                <option value="Y">YES</option>
                                                <option value="N">NO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Path Termination</strong>
                                            <br>
                                            <select id="path_term" name="path_term" onchange="inputpaththerm()" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Ref. Fix Point</strong>
                                            <br>
                                            <select id="refpoint" name="refpoint" onchange="changerefpoint(this)" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Fix Point</strong>
                                            <br>
                                            <p id="fixpoint"></p>
                                            <select id="fixrwyid" onchange="runwayspoint()" style="visibility: hidden" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12" id="search1" style="visibility: hidden">
                                            <select name="select2" id="select11" class="form-control select2">
                                        </div>
                                        <div class="col-md-6" style="visibility: hidden">
                                            <strong>ID</strong>
                                            <br>
                                            <input style="visibility: hidden" type="text" class="form-control"/>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Point Desc 1</strong>
                                            <br>
                                            <select id="wd1" name="wd1" selected="selected" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Point Desc 2</strong>
                                            <br>
                                            <select id="wd2" name="wd2" selected="selected" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Point Desc 3</strong>
                                            <br>
                                            <select id="wd3" name="wd3" selected="selected" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Point Desc 4</strong>
                                            <br>
                                            <select id="wd4" name="wd4" selected="selected" onchange="checkcodwptdesc4()" class="form-control" >
                                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-inner table-bordered mt-1">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong id="radlabel">Mag Course</strong>
                                            <br>
                                            <input id="mag_crs" type="number" onfocusout="getpoint2()"name="mag_crs" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <strong>True Course</strong>
                                            <br>
                                            <input id="true_crs" type="number" onfocusout="getpoint2()"name="true_crs" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Turn</strong>
                                            <br>
                                            <select id="turn_dir" name="turn_dir" selected="selected" class="form-control" >
                                            
                                            </select>
                                            
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Ref. NAV 1</strong>
                                            <br>
                                            <select id="refrecdnav1" onchange="changerefpoint(this)" class="form-control" >
                                                <option value=""></option>
                                                <option value="ILS">ILS</option>
                                                <option value="NAV">Navaid</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Rec. Nav 1</strong>
                                            <br>
                                            <p id="recdnav"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Theta 1</strong>
                                            <br>
                                            <input id="theta" name="theta"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Rho 1</strong>
                                            <br>
                                            <input id="rho" name="rho"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-12" id="search2" style="visibility: hidden">
                                            <select name="select2" id="select22" class="form-control select2">
                                        </div>
                                        <div class="col-md-6" style="visibility: hidden">
                                            <strong>ID</strong>
                                            <br>
                                            <input style="visibility: hidden" type="text" class="form-control"/>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Ref. NAV 2</strong>
                                            <br>
                                            <select id="refrecdnav2" onchange="changerefpoint(this)" class="form-control" >
                                                <option value=""></option>
                                                <option value="ILS">ILS</option>
                                                <option value="NAV">Navaid</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Rec. Nav 2</strong>
                                            <br>
                                            <p id="recdnav1"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Theta 2</strong>
                                            <br>
                                            <input id="theta1" name="theta1"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Rho 2</strong>
                                            <br>
                                            <input id="rho1" name="rho1"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-12" id="search3" style="visibility: hidden">
                                            <select name="select3" id="select33" class="form-control select2">
                                        </div>
                                        <div class="col-md-6" style="visibility: hidden">
                                            <strong>ID</strong>
                                            <br>
                                            <input style="visibility: hidden" type="text" class="form-control"/>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>RNP</strong>
                                            <br>
                                            <input id="rnp" name="rnp"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Dist.(leg)</strong>
                                            <br>
                                            <input id="rt_dist_from" name="rt_dist_from"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Timing</strong>
                                            <br>
                                            <input id="leg_time" name="leg_time"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Altitude Desc.</strong>
                                            <br>
                                            <select id="alt_desc" name="alt_desc" selected="selected" class="form-control" >
                                                
                                                </select>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Alt. 1</strong>
                                            <br>
                                            <input id="alt1" name="alt1"  type="text" class="form-control" >
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Alt. 2</strong>
                                            <br>
                                            <input id="alt2" name="alt2"  type="text" class="form-control" >
                                        </div>
                                        <div class="col-md-4">
                                            <strong>MOCA</strong>
                                            <br>
                                            <input id="moca" name="moca"  type="number" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card-inner table-bordered mt-1">
                                    <div class="row">
                                    <div class="col-md-2">
                                            <strong>TCH</strong>
                                            <br>
                                            <input id="tch" name="tch"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-2">
                                            <strong id="va_id">PDG</strong>
                                            <br>
                                            <input id="vert_angle" name="vert_angle"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Speed</strong>
                                            <br>
                                            <input id="sp_lim" name="sp_lim"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-6" id="holdingid" style="visibility: visible">
                                            <strong id="cf_id">Center Fix</strong>
                                            <br>
                                            <input type="hidden" id="center_fix" name="center_fix" type="text" class="form-control">
                                            <select id="center_fix_name" onchange="changeholdingpoint(this)" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-2" id="arcid" style="visibility: hidden">
                                            <strong>Arc Radius</strong>
                                            <br>
                                            <input id="arc_rad" name="arc_rad"  type="number" class="form-control" >
                                        </div>
                                        <div class="col-md-4" id="centerid" style="visibility: hidden">
                                            <strong>Ref. Center Point</strong>
                                            <br>
                                            <select id="refpoint3" name="refpoint3" onchange="changerefpoint(this)" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12" id="fixinfo" align="center" style="visibility: hidden">
                                            <strong>Arc Center</strong>
                                            <br>
                                            <p id="fixpoint3"></p>
                                        </div>
                                        <div class="col-md-12" id="search4" style="visibility: hidden">
                                            <select name="select2" id="select44" class="form-control select2">
                                        </div>
                                        <div class="col-md-6" style="visibility: hidden">
                                            <strong>ID</strong>
                                            <br>
                                            <input style="visibility: hidden" type="text" class="form-control"/>
                                        </div>
                                        <div class="col-md-12">
                                            <strong></strong>
                                            <br>
                                            <p align="center" id="holdingpoint"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <br>
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
                            <button onclick=updatetrans() id="btn_save_trans" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
// $("#datatransegdetail").hide();
$("#datatransegdetail").hide();$("#search1").hide();$("#search2").hide();$("#search3").hide();$("#fixrwyid").hide();$("#centerid").hide();$("#arcid").hide();$("#fixinfo").hide();$("#search4").hide();
var fld= ['id','proc_id', 'seq_num','fix_id','wd1','wd2','wd3','wd4','turn_dir','rnp','path_term','recd_nav','theta','rho','mag_crs','true_crs','rt_dist_from','alt_desc','alt1','alt2','sp_lim','vert_angle','center_fix','arc_rad','moca','leg_time','dist_cal','dist_to_thr','recd_nav1','theta1','rho1','tch'];
var trfld= ['id','proc_id', 'arpt_ident','chart_type','rnav','sub_chart_type','trans_ident','rwy_id','rwy_trans','rt_type','vnav','nav_spec'];

var trans =@json($trans);transtemp =@json($transtemp);transcode =@json($transcode);rt=@json($rt);pt=@json($pterm);
var codchart=@json($chart);arpt=@json($arpt);
var ifback='';altdesc=@json($altdesc);wdesc=@json($wptdesc);trseg=[];trsegcurr=[];transB=[];transseg=[];
var lastpt='';backto=@json($backto);holding=@json($holding);fixid='';ils=@json($ils);
// console.log('TRANS',transtemp);
var crd1=[];crd2=[];index=0;

if (transtemp.length==0){
    $("#judullist").html('New Transition');
    NewData();
}else{
    $("#judullist").html(transtemp[0].trans_ident + ' RWY ' + transtemp[0].rwy_trans + ' ' + transtemp[0].definition)
    edit()
}
$("#arptname").html(arpt[0].icao + ' ' + arpt[0].arpt_name)
switch (codchart) {
    case '45':
        ifback='iac';
        break;
    case '46':
        ifback='sid';
        break;
    case '47':
        ifback='sta';
        break;
}
var charttype= [{
    id: '45',
    definition: 'IAC'
}, {
    id: '46',
    definition: 'SID'
}, {
    id: '47',
    definition: 'STAR'
}];
var turndir= [{
    id: '',
    definition: 'None'
}, {
    id: 'L',
    definition: 'LEFT'
}, {
    id: 'R',
    definition: 'RIGHT'
}];

var Refpoint= [{
    id: '',
    definition: 'None'
}, {
    id: 'ARPT',
    definition: 'Airport'
},{
    id: 'ILS',
    definition: 'ILS'
},{
    id: 'MRK',
    definition: 'Marker'
},{
    id: 'NAV',
    definition: 'Navaid'
},{
    id: 'RWY',
    definition: 'Runway'
}, {
    id: 'WPT',
    definition: 'Waypoint'
}];

$("#rwy_id_proc").append('<option value=""></option>');
$("#rwy_id").append('<option value=""></option>');
arpt[0].runwaystemp.forEach(r=>{
    // var all=r.physicals[0].rwy_ident+'/'+r.physicals[1].rwy_ident;
    $("#rwy_id").append('<option value="'+r.physicals[0].rwy_key+'">'+r.physicals[0].rwy_ident+'</option>');
    $("#rwy_id").append('<option value="'+r.physicals[1].rwy_key+'">'+r.physicals[1].rwy_ident+'</option>');
    
    $("#fixrwyid").append('<option value=""></option>');
    $("#fixrwyid").append('<option value="'+r.physicals[0].rwy_key+'">RWY '+r.physicals[0].rwy_ident+'</option>');
    $("#fixrwyid").append('<option value="'+r.physicals[1].rwy_key+'">RWY '+r.physicals[1].rwy_ident+'</option>');
    $("#rwy_id_proc").append('<option value="'+r.physicals[0].rwy_key+'">RWY '+r.physicals[0].rwy_ident+'</option>');
    $("#rwy_id_proc").append('<option value="'+r.physicals[1].rwy_key+'">RWY '+r.physicals[1].rwy_ident+'</option>');
  
    // console.log(r)
})
$("#rwy_id").append('<option value="ALL">ALL</option>');
$("#rwy_id_proc").append('<option value="ALL">ALL</option>');
var navspec=["","RNAV 1","RNAV 2",
            "RNAV 4",
            "RNAV 5",
            "RNAV 10",
            "RNP APCH",
            "RNP AR APCH",
            "RNP 1",
            "RNP 2",
            "RNP 4",
            "RNP 5",
            "RNP 10"
]
// console.log(transcode)
Refpoint.forEach(t=>{
    $("#refpoint").append('<option value="'+t.id+'">'+ t.definition +'</option>');
    if (t.id=='' || t.id=='NAV' || t.id=='WPT'){

        $("#refpoint3").append('<option value="'+t.id+'">'+ t.definition +'</option>');
    }
    
})
$("#alt_desc").append('<option value=""></option>');
altdesc.forEach(t=>{
    $("#alt_desc").append('<option value="'+t.id+'">'+t.id+' ' + t.definition +'</option>');
})
$("#path_term").append('<option value=""></option>');
pt.forEach(t=>{
    $("#path_term").append('<option value="'+t.id+'">'+t.id+' ' + t.definition +'</option>');
})
transcombo("45")
wptdesc(false,'45')
subcharttype('451')
turndir.forEach(t=>{
    $("#turn_dir").append('<option value="'+t.id+'">'+t.definition+'</option>');
})
charttype.forEach(a=>{
    $("#chart_type").append('<option value="'+a.id+'">'+a.definition+'</option>');
    $("#chart_type_proc").append('<option value="'+a.id+'">'+a.definition+'</option>');
})

navspec.forEach(a=>{
    $("#nav_spec").append('<option value="'+a+'">'+a+'</option>');
})
function showmap(){
   var procid= transtemp[0].proc_id+"@"+transtemp[0].rt_type
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=trans&id='+procid, 'Set Latitude and Longitude', params)
}
function routetype(){
    var chid=$("#rt_type").val();
    // console.log(chid,$("#chart_type").val(),$("#sub_chart_type").val())
    if (chid=='I'){
        wptdesc(true)
    }else{
        wptdesc(false)
    }

    if ($("#chart_type").val() == "45"){
        $("#va_id").html('VPA');
        if (chid=='R'){
            $("#rnav").val('Y');
        }else{
            $("#rnav").val('N');
        }
    }else{
        $("#va_id").html('PDG');
        if ($("#sub_chart_type").val()=='462' || $("#sub_chart_type").val()=='472'){
            $("#rnav").val('Y');
        }else{
            $("#rnav").val('N');
        }
    }

}
function navspecttype(){

    if ($("#nav_spec").val()==''){
        $("#rnav").val('N')
    }else{
        $("#rnav").val('Y')
    }
}
function subcharttype(trans=null){
    var chid=trans;
    if (trans==null){
        chid=$("#sub_chart_type").val();
    }
    if(chid=='462' || chid=='472'){
        $("#rnav").val('Y');
    }else{
        $("#rnav").val('N');
    }
    $("#rt_type").empty()
    rt.forEach(a=>{
        // console.log(a)
        if (a.trans_code==chid){
            $("#rt_type").append('<option value="'+a.trans_types+'">'+a.definition+'</option>');
            
        }
    }) 
//    console.log(rt)
}
function wptdesc(ils,chart=null){
    var chid=chart;
    var wdescchart='iac';
    if (chart==null){
        chid=$("#chart_type").val();
        switch (chid) {
            case '45':
                wdescchart='iac';
                break;
        
            case '46':
                wdescchart='sid';
                break;
            case '47':
                wdescchart='star';
                break;
        }
    }
    
    $("#wd1").empty()
    $("#wd2").empty()
    $("#wd3").empty()
    $("#wd4").empty()
    $("#wd1").append('<option value=""></option>');
    $("#wd2").append('<option value=""></option>');
    $("#wd3").append('<option value=""></option>');
    $("#wd4").append('<option value=""></option>');
    wdesc.forEach(a=>{
        var d40='<option value="'+a.d40+'">'+a.definition +'</option>';
        var d41='<option value="'+a.d41+'">'+a.definition+'</option>';
        var d42='<option value="'+a.d42+'">'+a.definition+'</option>';
        if (a.descr !== null){
            var d43='<option value="'+a.d43+'">'+a.definition + ' (' + a.descr +')</option>';

        }else{
            var d43='<option value="'+a.d43+'">'+a.definition + '</option>';
        }
        if (a.d40 !==null && a[wdescchart]=='1'){
            $("#wd1").append(d40)
        }
        if (a.d41 !==null && a[wdescchart]=='1'){
            $("#wd2").append(d41);
            
        }
        if (a.d42 !==null && a[wdescchart]=='1'){
            $("#wd3").append(d42);
            
        }
        if (a.d43 !==null && a[wdescchart]=='1'){
            if (ils==true){
                if (a.d43 !== 'I' && a.d43 !== 'F'){
                    // console.log(a[wdescchart],a.d43,a.definition,'ILS TRUE')
                    $("#wd4").append(d43);
                }
            }else{
                if (a.d43 !== 'E'){
                    // console.log(a[wdescchart],a.d43)
                    $("#wd4").append(d43);
                }
            }
            
        }
    }) 
}
function transcombo(chart=null){
    var chid=chart;
    if (chart==null){
        chid=$("#chart_type").val();
        switch (chid) {
            case '45':
                subcharttype('451')
                break;
        
            case '46':
                subcharttype('461')
                break;
            case '47':
                subcharttype('471')
                break;
        }
    }
    
    $("#sub_chart_type").empty()
    $("#sub_chart_type_proc").empty()
    transcode.forEach(a=>{
        if (a.chart_code==chid){
            $("#sub_chart_type").append('<option value="'+a.trans_code+'">'+a.definition+'</option>');
            $("#sub_chart_type_proc").append('<option value="'+a.trans_code+'">'+a.definition+'</option>');
            
        }
    }) 
}
function viewsegproc(id){
    aboutvol("dataprocsegdetail")
    var ip=proctemp.findIndex(x=>x.id===Number(id))
    var pr=proctemp[ip]
    var rwy=pr.segment[0].transition[0].rwy_id
   
    // console.log(pr.segment[0].transition[0].rwy_id)
    $("#proc_name").val(pr.proc_name)
    $("#chart_type_proc").val(pr.chart_type)
    $("#rwy_id_proc").val(rwy)
    $("#proc_text").val(pr.proc_text)
    $("#note").val(pr.note)
    $("#remarks").val(pr.remarks)
    pr.segment.forEach(p=>{
        listoftrans(p.transition,'translist')
    })

    
}

function backtomenu(){
    window.location.href = '/'+backto.split('_').join('/');//procedure/'+arpt[0].arpt_ident+'/'+codchart;
}
function setMapPointtrans(id) {
    var ix =transtemp.findIndex(x=>x.id===Number(id));
    // console.log(transtemp,ix,id)
    setMapPoint(transtemp[ix].proc_id+"@"+transtemp[ix].rt_type,'trans')
}
function setMapPointproc(id) {
    var ix =proctemp.findIndex(x=>x.id===Number(id));
    setMapPoint(proctemp[ix].proc_id,'proc')
}

function setMapPoint(procid,tbl) {
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table='+tbl+'&id='+procid, 'Set Latitude and Longitude', params)
}


function editproc(data){
    window.scrollTo(0,0);
    // console.log(proctemp,proc)
}
function edit(data) {
    window.scrollTo(0,0);
    // console.log(transtemp,trans,data)
    $("#seglist").empty();
    // console.log(ix,proc[ix])
    trseg=transtemp[0];
    if (trans.length>0){
        trsegcurr=trans[0];

    }
    trseg.segment.sort((a,b) => (Number(a.seq_num) > Number(b.seq_num)) ? 1 : ((Number(b.seq_num) > Number(a.seq_num)) ? -1 : 0));
    trseg.segment.forEach(t=>{
        var fixid_trans=t.fix_id;turn='';crs='';alt='';dist='';
        if (t.turn_dir){
            turn=t.turn_dir;
        }
        if (t.rt_dist_from){
            dist=t.rt_dist_from;
        }
        if (t.mag_crs){
            crs=t.mag_crs;
        }
        if (t.alt1){
            if (t.alt_desc){
                alt=t.alt_desc + t.alt1;
            }else{
                alt=t.alt1;
            }
        }
        if (t.alt2){
            alt = '-'+ t.alt2 + '<br>+'+t.alt1;
        }
        if (fixid_trans){
            if (t.navaid.length > 0){
                fixid_trans=t.navaid[0].nav_ident + ' ' + t.navaid[0].definition
            }else if (t.waypoint.length > 0){
                fixid_trans=t.waypoint[0].desc_name
            }else if (t.marker.length > 0){
                fixid_trans=t.marker[0].mrkr_type
            }else if (t.arpt.length > 0){
                fixid_trans=t.arpt[0].icao
            }else if (t.rwy.length > 0){
                fixid_trans='RWY ' + t.rwy[0].rwy_ident
            }
        }else{
            fixid_trans='';
        }
        var  hsl= '<tr v-bind:key="arp.arpt_ident">'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-primary col-md-12" id='+ t.id +' onclick="editsegment(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                            '<a class="btn btn-dim btn-info col-md-12" id='+ t.id +' onclick="NewDataInsert(this.id)"><i class="icon ni ni-plus"></i> Insert</a>'+
                            '<a class="btn btn-dim btn-danger col-md-12" id='+ t.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                        '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ trseg.trans_ident +'</td>'+
            '<td>'+ Number(t.seq_num) +'</td>'+
            '<td>'+ t.path_term +'</td>'+
            '<td>'+ fixid_trans +'</td>'+
            '<td>'+ turn +'</td>'+
            '<td>'+ crs +'</td>'+
            '<td>'+ dist +'</td>'+
            '<td>'+ alt +'</td>'+
        '</tr>';
        $("#seglist").append(hsl)
    })

   
}
function getpoint2(){
    if (transB.length >0){
        var fixidb=transB.fix_id;
        if (fixidb){
            if (transB.navaid.length > 0){
                crd1=SetCoordinatebyGeom(transB.navaid[0].geom);
            }else if (transB.waypoint.length > 0){
                crd1=SetCoordinatebyGeom(transB.waypoint[0].geom);
            }
        }
        pend1=getpendicularcourse(315,'L');
        pend2=getpendicularcourse(315,'R');
        pend3=getpendicularcourse(90,'L');
        pend4=getpendicularcourse(90,'R');
        // console.log(pend1,pend2,pend3,pend4,transB)
    //   var hasil=  getpoint2coord(crd1.Decimal[1],crd1.Decimal[0],$("#mag_crs").val(),$("#rt_dist_from").val())
        var  hasil= getarccoord(crd1.Decimal[1],crd1.Decimal[0],pend1,pend2,$("#rt_dist_from").val())
                    
    }else{

    //    console.log(arpt[0].runwaystemp)
    }
    // var  hasil1= getarccoord(crd1.Decimal[1],crd1.Decimal[0],180,360,$("#rt_dist_from").val())
    // hasil.forEach(b=>{
    //     console.log(b)
    // })
    // console.log(hasil,hasil1)
}
function editsegment(id){
    aboutvol("datatranssegment")
    aboutvol("datatransegdetail")
    // console.log('trseg',trseg)
    var ix=trseg.segment.findIndex(a=>a.id===Number(id))
    index=ix;
    lastpt='';
    if (ix > 0){
        transB=trseg.segment[ix-1];
        lastpt=transB.path_term;

    }
    // console.log(trseg.segment[ix],'trseg.segment[ix]',ix,transB)
    transseg=trseg.segment[ix];trcur=[];
    if (trsegcurr.length > 0){
        var ixx=trsegcurr.segment.findIndex(a=>a.id===Number(id))
        trcur=trsegcurr.segment[ixx]
    }

    $("#trans_id").val(trseg.id) 
    $("#trans_ident").val(trseg.trans_ident)
    $("#chart_type").val(trseg.chart_type);
    $("#rwy_id").val(trseg.rwy_id);
    $("#status").val('R');
    $("#arpt_ident").val(trseg.arpt_ident);
    transcombo();
    $("#sub_chart_type").val(trseg.sub_chart_type);
    subcharttype(trseg.sub_chart_type)
    $("#rt_type").val(trseg.rt_type);
    routetype();
    $("#refpoint").val('')
    $("#nav_spec").val(trseg.nav_spec)
    navspecttype();
    fixid=transseg.fix_id;fixname='';
   
    if (fixid){
        if (transseg.navaid.length > 0){
            crd2=SetCoordinatebyGeom(transseg.navaid[0].geom);
            fixname=transseg.navaid[0].nav_ident + ' ' + transseg.navaid[0].definition
            $("#refpoint").val('NAV')
        }else if (transseg.waypoint.length > 0){
            crd2=SetCoordinatebyGeom(transseg.waypoint[0].geom);
            fixname=transseg.waypoint[0].desc_name
            $("#refpoint").val('WPT')
        }else if (transseg.arpt.length > 0){
            crd2=SetCoordinatebyGeom(transseg.arpt[0].geom);
            fixname=transseg.arpt[0].icao
            $("#refpoint").val('ARPT')
        }else if (transseg.marker.length > 0){
            crd2=SetCoordinatebyGeom(transseg.marker[0].geom);
            fixname=transseg.marker[0].mrkr_type
            $("#refpoint").val('MRK')
        }else if (transseg.rwy.length > 0){
            crd2=SetCoordinatebyGeom(transseg.rwy[0].geom);
            fixname='RWY '+ transseg.rwy[0].rwy_ident
            $("#refpoint").val('RWY')
        }
       
        if(fixname !==''){
            fixname += ' ('+ crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
            $('#fixpoint').html(Symbolpoint(fixname,fixid,'spoint1'))

        }
    }else{
        fixid='';
        $('#fixpoint').html('')
    }
    
    var recdnav='';
    if (transseg.recdnav1.length > 0){
        recdnav=transseg.recdnav1[0].nav_ident +' '+transseg.recdnav1[0].definition;
        $('#recdnav').html(Symbolpoint(recdnav,transseg.recdnav1[0].nav_id,'spoint2'))
        $('#refrecdnav1').val('NAV') 
    }
    if (transseg.recdils1.length > 0){
        recdnav=transseg.recdils1[0].ils_ident + ' ILS/LLZ'
        $('#recdnav').html(Symbolpoint(recdnav,transseg.recdils1[0].ils_id,'spoint2'))
        $('#refrecdnav1').val('ILS') 
    }
    if (transseg.recdnav2.length > 0){
        recdnav=transseg.recdnav2[0].nav_ident +' '+transseg.recdnav2[0].definition;
        $('#recdnav1').html(Symbolpoint(recdnav,transseg.recdnav2[0].nav_id,'spoint3'))
        $('#refrecdnav2').val('ILS') 
    }
    if (transseg.recdils2.length > 0){
        recdnav=transseg.recdils2[0].ils_ident + ' ILS/LLZ'
        $('#recdnav1').html(Symbolpoint(recdnav,transseg.recdils2[0].ils_id,'spoint3'))
        $('#refrecdnav2').val('NAV') 

    }
    // console.log(transseg,trcur)
    compareisidata(fld,transseg,trcur);
    settonullinput(fld);
    inputpaththerm();
    //hitung ulang
    if (transB && fixid){
        var fixidb=transB.fix_id;
        if (fixidb){
            if (transB.navaid.length > 0){
                crd1=SetCoordinatebyGeom(transB.navaid[0].geom);
            }else if (transB.waypoint.length > 0){
                crd1=SetCoordinatebyGeom(transB.waypoint[0].geom);
            }
            // console.log(transB,'transB',crd1,crd2)
            calculate(crd1,crd2)
        }
                    
    }
    checkcodwptdesc4();
    if ((transseg.path_term=='RF' || transseg.path_term=='AF' ) && transseg.center_fix !==null){
        var ftype=transseg.center_fix.substr(0,3);
        if (ftype=='WPT'){
            $('#refpoint3').val('WPT');
           
        } else  if (ftype=='NAV'){
            $('#refpoint3').val('NAV');
        }
        showcenterfix(transseg.center_fix,ftype);
    }
}
function showcenterfix(data,type){
    if (type=='WPT'){
        url='../api/waypoint/temp'
        dtreq={'wpt_id':data}
    }else{
        url='../api/navaid/temp'
        dtreq={'nav_id':data}
    }
    aboutvol("fixinfo");
        // var dtreq={'trans_ident':$("#trans_ident").val(),'arpt_ident':arpt[0].arpt_ident}
        $.ajax({
                url: url,
                data: dtreq,
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        var cordf=SetCoordinatebyGeom(v.geom);
                        if (type=='WPT'){
                            txtp=v.desc_name+ '(' + cordf.WGS[1] + ' ' + cordf.WGS[0] + ')';
                        }else{
                            txtp=v.nav_ident + ' ' + v.definition + '(' + cordf.WGS[1] + ' ' + cordf.WGS[0] + ')';
                        } 
                        $('#fixpoint3').html(Symbolpoint(txtp,data,'spoint4'));
                        
                    //    console.log(v)
                    
                    })

                }
        })

}
function NewData(){
    aboutvol("datatranssegment")
    aboutvol("datatransegdetail")
    $("#trans_ident").val()
    $("#chart_type").val();
    $("#seq_num").val('10');
    $("#status").val('N');
    $("#arpt_ident").val(arpt[0].arpt_ident);
    transcombo();wptdesc();
    $("#refpoint").val('')

    
}

function NewDataInsert(id){
    aboutvol("datatranssegment")
    aboutvol("datatransegdetail")
    var ix=trseg.segment.findIndex(a=>a.id===Number(id))
    lastpt='';
    transseg=trseg.segment[ix];
    transB=trseg.segment[ix];
    lastpt=transB.path_term;
    index +=1;
    console.log('transB',transB,trseg)
    $("#rwy_id").val(trseg.rwy_id) 
    $("#proc_id").val(trseg.proc_id) 
    $("#trans_ident").val(trseg.trans_ident)
    $("#chart_type").val(trseg.chart_type);
    $("#status").val('I');
    $("#seq_num").val(Number(transseg.seq_num)+1);
    $("#arpt_ident").val(trseg.arpt_ident);
    transcombo();
    $("#sub_chart_type").val(trseg.sub_chart_type);
    subcharttype(trseg.sub_chart_type)
    $("#rt_type").val(trseg.rt_type);
    routetype();
    $("#refpoint").val('')
    $("#nav_spec").val(trseg.nav_spec);
    navspecttype();

    
}

function checktransname(){
    var lgttrans=$("#trans_ident").val().length;
    if (lgttrans > 5){
        Swal.fire(
            'Invalid Data!',
            'Transition Name maximum 5 characters',
            'error'
        )
    }else{
        var trnnm=$("#trans_ident").val();
        //    console.log(codchart)
        var dtreq={'trans_ident':trnnm.toUpperCase(),'arpt_ident':arpt[0].arpt_ident,'chart_type':codchart}
        var trname=[];
        // var dtreq={'trans_ident':$("#trans_ident").val(),'arpt_ident':arpt[0].arpt_ident}
        $.ajax({
                url: '../api/transition/temp',
                data: dtreq,
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        trname.push(v)
                        
                    //    console.log(v)
                    
                    })
                    showtransidentdoubel(trname);
                }
        })

    }
    // console.log(lgttrans)
}
function showtransidentdoubel(data){
    // console.log(data)
    if (data.length > 0){
        var dt='';
        data.forEach(d=>{
            var ddd=d.trans_ident + ' ' + d.definition + ' RWY ' + d.rwy_trans;
            if (dt==''){
                dt=ddd;
            }else{
                dt = dt+ '<br>'+ ddd;
            }
        })
        // console.log(dt)
        Swal.fire(
            'Transition Name already used in : ',
            dt + '<br><br>Check the existence of the Transition Name' ,
            'info'
        )
    }
}
function updatetrans(){
    var trrr= ['trans_ident'];
    changetouppercase(trrr);
    var pt_curr=$("#path_term").val();
   
    if ($("#refpoint").val()==''){
        $("#fix_id").val('');
    }
    
    if ($("#wd1").val()=='P'){
        $("#fix_id").val('');
    }
    var sel = document.getElementById("rwy_id");
//   console.log(sel);
     runways(sel);
        if ($("#center_fix_name").val() !== null){
            $("#center_fix").val($("#center_fix_name").val())

        }
     // $("#rwy_trans").val(x);
     //   document.getElementById("demo").innerHTML = "You selected: " + x;
    //  console.log($("#wd1").val(),$("#wd2").val(),$("#wd3").val(),$("#wd4").val());
     if ($("#status").val()=='N'){
         changetouppercase(fld);
        var prcid=$("#arpt_ident").val()+'_'+ $("#chart_type").val()+'_'+ $("#sub_chart_type").val()+'_'+ $("#trans_ident").val()+'_'+ $("#rwy_trans").val()+'_'+ $("#rt_type").val();
        $("#proc_id").val(prcid);
    }
    if (pathterm_validasi(lastpt,pt_curr)==false){
        Swal.fire(
            'Invalid Data!',
            pt_curr + ' PATH TERM is not permitted within individual procedure routes after ' + lastpt,
            'error'
        )
        // MsgBox(CurPathTerm & " PATH TERM is not permitted within individual procedure routes after " & LastPathTerm)
    }else{
    
        $("#trans_form").submit();
    }
}
function changeholdingpoint(id){
    var hid=$("#center_fix_name").val();
    var ih = holding.findIndex(x=>x.id===Number(hid))
    $("#center_fix_name").val(holding[ih].id)
    $("#center_fix").val(holding[ih].id)
    $("#holdingpoint").html(infoholding(holding[ih]))
}
function checkcodwptdesc4(){
    var wpd4=$("#wd4").val();
    var fixhold=$("#center_fix").val();
    $("#holdingpoint").html('')
    $("#cf_id").html('Center Fix');
    $("#center_fix_name").empty();
    var ix=wdesc.findIndex(x=>x.d43==wpd4)
    if (ix !== -1){
        var wp=wdesc[ix];
        if (wp.descr=="MAPt"){
            if ( $("#tch").val()==''){
                $("#tch").val(50);
            }
            if ( $("#vert_angle").val()==''){
                $("#vert_angle").val(2.9);
            }
        }
        // console.log(holding,'holding')
        if (wp.holding=="Y"){
            holding.forEach(t=>{
                // console.log(t,'HOLDING')
                $("#center_fix_name").append('<option value="'+t.id+'">'+t.fix_point + ' (' + t.crs + ')' +'</option>');
            })
            $("#cf_id").html('Holding Fix');
            var ih =-1;
            // console.log(fixhold,'fixhold')
            if (fixhold){
                ih = holding.findIndex(x=>x.id===Number(fixhold))
                if (holding[ih].fix_id == $("#fix_id").val()){
                    $("#center_fix_name").val(holding[ih].id)
                    $("#center_fix").val(holding[ih].id)
                    $("#holdingpoint").html(infoholding(holding[ih]))
                }else{
                    Swal.fire({
                        title: 'Change the Holding Point?',
                        text: "The Holding Point different with Fixed point!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.value) {
                            is = holding.findIndex(x=>x.fix_id===$("#fix_id").val())
                            // console.log(is,holding[is],'(holding[is]',$("#fix_id").val())
                            if (is !==-1){
                                $("#center_fix").val(holding[is].id)
                                $("#center_fix_name").val(holding[is].id)
                                $("#holdingpoint").html(infoholding(holding[is]))

                            }
                        }
                    })
                }
            }else{
                var fixp= $("#fix_id").val();
                ih=holding.findIndex(x=>x.fix_id===fixp)
                // console.log(fixp,ih,'fixp')
                if (ih ==-1){
                    Swal.fire({
                        title: 'No Holding Data',
                        text: "Do you want to create new Holding!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, create it!'
                    }).then((result) => {
                        if (result.value) {
       
                            window.location.href = '/holding/' + arpt[0].arpt_ident +'@new@'+transtemp[0].proc_id + '@'+codchart;
                        }
                    })
                    $("#wd4").val('')
                }else{
                    $("#center_fix_name").val(holding[ih].id)
                    $("#center_fix").val(holding[ih].id)
                    $("#holdingpoint").html(infoholding(holding[ih]))
                    
                    // console.log(holding[ih])
                    // console.log('HOLDING ADA',holding)
                }
            }
        }else{
            $("#center_fix_name").val('')
            $("#cf_id").html('Center Fix');
        }

    }
}
function infoholding(hold){
    crs=numeral(hold.crs/10).format('000') + '°';
    if (hold.turn=='R'){
        turn='RIGHT';
    }else{
        turn='LEFT';
    }
    max_alt= hold.max_alt + 'ft'
    crdhold=SetCoordinatebyGeom(hold.geom);
    return 'Course : <b>' + crs + '</b> Turn : <b>' + turn + '</b> Alt : <b>' +max_alt + '</b><br>(' + crdhold.WGS[1] + ' ' + crdhold.WGS[0] + ')';
}

function changerefpoint(id){
    if ($("#fixrwyid").is(':visible')==true){
            aboutvol('fixrwyid');
        }
        if ($("#fixpoint").is(':visible')==false){
            aboutvol('fixpoint');
        }
        if ($("#fixinfo").is(':visible')==true){
            aboutvol('fixinfo');
        }
    switch (id.id) {
        case 'refpoint':
            if ($("#refpoint").val()==''){
                $('#fixpoint').html('')
                $('#wd1').val('P')
            }else{
                var x = id.options[id.selectedIndex].text;
                switch ($("#refpoint").val()) {
                    case 'WPT':
                        $('#wd1').val('E')
                        break;
                    case 'ARPT':
                        $('#wd1').val('A')
                        break;
                    case 'RWY':
                        aboutvol("fixpoint");
                        aboutvol("fixrwyid");
                        $("#fixrwyid").empty()
                        $("#fixrwyid").append('<option value=""></option>');
                        arpt[0].runwaystemp.forEach(r=>{
                            var all=r.physicals[0].rwy_ident+'/'+r.physicals[1].rwy_ident;
                            $("#fixrwyid").append('<option value="'+r.physicals[0].rwy_key+'">RWY '+r.physicals[0].rwy_ident+'</option>');
                            $("#fixrwyid").append('<option value="'+r.physicals[1].rwy_key+'">RWY '+r.physicals[1].rwy_ident+'</option>');
                            // console.log(r)
                        })
                        $('#wd1').val('G')
                        break;
                    case 'ILS':
                        aboutvol("fixpoint");
                        aboutvol("fixrwyid");
                        $("#fixrwyid").empty()
                        $("#fixrwyid").append('<option value=""></option>');
                        ils.forEach(r=>{
                            // console.log(r)
                            $("#fixrwyid").append('<option value="'+r.ils_id+'">'+r.ils_ident+'</option>');
                        })
                        $('#wd1').val('V')
                        break;
                    case 'MRK':
                        aboutvol("fixpoint");
                        aboutvol("fixrwyid");
                        $("#fixrwyid").empty()
                        $("#fixrwyid").append('<option value=""></option>');
                        ils.forEach(r=>{
                            // console.log(r)
                            var il=r.ils_ident;
                            r.marker.forEach(m=>{
                                $("#fixrwyid").append('<option value="'+m.mrkr_id+'">'+m.mrkr_type + '(' + il + ' ILS)</option>');

                            })
                            // var all=r.physicals[0].rwy_ident+'/'+r.physicals[1].rwy_ident;
                        })
                        
                        $('#wd1').val('V')
                        break;
                    default:
                    $('#wd1').val('V')
                        break;
                }
                // console.log(x)
                // document.getElementById("demo").innerHTML = "You selected: " + x;
                $('#fixpoint').html(Symbolnewpoint(x,'spoint1'))
            }
            break;
        case 'refrecdnav1':
            if ($("#refrecdnav1").val()==''){
                $('#recdnav').html('')
            }else{
                var x = id.options[id.selectedIndex].text;
                // console.log(x)
                // document.getElementById("demo").innerHTML = "You selected: " + x;
                $('#recdnav').html(Symbolnewpoint(x,'spoint2'))
            }
            break;
        case 'refrecdnav2':
            if ($("#refrecdnav2").val()==''){
                $('#recdnav1').html('')
            }else{
                var x = id.options[id.selectedIndex].text;
                // console.log(x)
                // document.getElementById("demo").innerHTML = "You selected: " + x;
                $('#recdnav1').html(Symbolnewpoint(x,'spoint3'))
            }
            break;
        case 'refpoint3':
            if ($("#fixinfo").is(':visible')==false){
            aboutvol('fixinfo');
            }
            if ($("#refpoint3").val()==''){
                $('#fixpoint3').html('')
            }else{
                var x = id.options[id.selectedIndex].text;
                // console.log(x)
                // document.getElementById("demo").innerHTML = "You selected: " + x;
                $('#fixpoint3').html(Symbolnewpoint(x,'spoint4'))
            }
            break;
    }
    
    // if ($("#refrecdnav1").val()==''){
    //     $('#recdnav').html('')
    // }else{
    //     var xx = id.options[id.selectedIndex].text;
    //     $('#recdnav').html(Symbolnewpoint(xx,'recdnav'))
    // }
    // if ($("#refrecdnav2").val()==''){
    //     $('#recdnav1').html('')
    // }else{
    //     var xxx = id.options[id.selectedIndex].text;
    //     $('#recdnav1').html(Symbolnewpoint(xxx,'recdnav1'))
    // }

}
function remove(id){
    $("#id").val(id) 
    $("#status").val('D')
    $("#proc_id").val(trseg.proc_id) 
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
                    $("#trans_form").submit();
                }else{
                    location.reload();
                }
            })

}
function isback(){
    console.log(transseg,'procedure/ID00035/46',codchart,arpt[0])
    if (transseg.length==0){
        window.location.href = '/procedure/'+arpt[0].arpt_ident+'/'+codchart;
    }else{

        aboutvol("datatransegdetail");
        aboutvol("datatranssegment");
        window.scrollTo(0,0);
    }
}
function runways(selTag) {
    var x = selTag.options[selTag.selectedIndex].text;
    // console.log(selTag);
    $("#rwy_trans").val(x);
    if ($("#rwy_id").val()=='ALL'){
        $("#rwy_trans").val('ALL');
    }
//   document.getElementById("demo").innerHTML = "You selected: " + x;
}
function runwayspoint(){
    var x = $("#fixrwyid").val();
    aboutvol("fixpoint");
    aboutvol("fixrwyid");
    switch (x.substr(0,3)) {
        case 'RWY':
            arpt[0].runwaystemp.forEach(r=>{
                var i = r.physicals.findIndex(x=>x.rwy_key===$("#fixrwyid").val())
                if (i !==-1){
                    crdr=SetCoordinatebyGeom(r.physicals[i].geom);
                    $("#fixpoint").html(x + ' ('+crdr.WGS[1]+' '+crdr.WGS[0]+')');
                    $("#fix_id").val(r.physicals[i].rwy_key);
                    if (transB){
                        var fixid_search=transB.fix_id;
                        if (fixid_search){
                            if (transB.navaid.length > 0){
                                crd1=SetCoordinatebyGeom(transB.navaid[0].geom);
                            }else if (transB.waypoint.length > 0){
                                crd1=SetCoordinatebyGeom(transB.waypoint[0].geom);
                            }
                            calculate(crd1,crdr)
                        }
                    }
                }
            })
            break;
        case 'ILS':
            ils.forEach(r=>{
                if (r.ils_id ==x){
                    crdr=SetCoordinatebyGeom(r.geom);
                    $("#fixpoint").html(r.ils_ident + ' ('+crdr.WGS[1]+' '+crdr.WGS[0]+')');
                    $("#fix_id").val(r.ils_id);
                }
            })
            break;
        case 'MRK':
            ils.forEach(r=>{
                r.marker.forEach(m=>{
                    if (m.mrkr_id ==x){
                        crdr=SetCoordinatebyGeom(m.geom);
                        $("#fixpoint").html(m.mrkr_type + ' ('+crdr.WGS[1]+' '+crdr.WGS[0]+')');
                        $("#fix_id").val(m.mrkr_id);
                    }
                    
                })
            })
            break;
  

    }
   
    // var i = arpt[0].runwaystemp.findIndex(x=>x.rwyKey===$("#fixrwyid").val())
    // console.log( arpt[0].runwaystemp[i])
    
   
}

function inputpaththerm(){
    var pt_curr=$("#path_term").val();
    inputrequired(pt_curr);
}
function inputrequired(currentpt){
//    var fl= ['fix_id','recd_nav','theta','rho','mag_crs','rt_dist_from','alt_desc','alt1','alt2','sp_lim','vert_angle','center_fix','arc_rad','recd_nav1','theta1','rho1','tch'];
    var fl= ['alt1','alt2','fixpoint','alt_desc','arc_rad','center_fix','mag_crs','rt_dist_from','center_fix','trans_ident','recd_nav','rho','theta','sp_lim','vert_angle','tch','turn_dir'];
    var fll= ['alt1','alt2','wpid','altd','arad','cfix','crs','dist','hld','pt','rmd','rho','the','lmt','vang','tdv','td'];
    var pval=@json($ptvalue);
    var iv=pval.findIndex(x=>x.id===currentpt);
        var pv=pval[iv];
        // console.log('pv',pv,currentpt)
    for (let i = 0; i < fl.length; i++) {
        const el = fl[i];
        $( "#" + fl[i] ).attr('style', "border-radius: 5px; border:#dbdfea 1px solid;");
        if (pv[fll[i]]=='X' || pv[fll[i]]=='+'){
            $( "#" + fl[i] ).attr('style', "border-radius: 5px; border:#FF2222 2px solid;");
        }
        if (pv[fll[i]]=='O'){
            $( "#" + fl[i] ).attr('style', "border-radius: 5px; border:#FF2 2px solid;");
        }
        // $("#"+fl[i]).style.border-color = "red";
            // console.log(pv[fll[i]],'ISI')
    }
    changetouppercase(fl);
        if ($("#centerid").is(':visible')==true){
            aboutvol('centerid');
        }
        if ($("#arcid").is(':visible')==true){
            aboutvol('arcid');
        }
        if ($("#holdingid").is(':visible')==false){
            aboutvol('holdingid');
        }
        var firstleg=false;
        //untuk mengisi automatic course SID runway transition
        if (codchart=='46' && (transseg.length==0 || index==0)){
            firstleg=true;
            rw=arpt[0].runwaystemp;rk=$("#rwy_id").val();
            console.log(rw)
            rw.forEach(r=>{
                if (r.physicals[0].rwy_key==rk){
                    rmag=numeral(r.physicals[0].mag_brg.fixed()).format('000');
                    rtrue=numeral(r.physicals[0].true_brg.fixed(1)).format('000.0');
                    $("#mag_crs").val(rmag)
                    $("#true_crs").val(rtrue)
                }else if(r.physicals[1].rwy_key==rk){
                    rmag=numeral(r.physicals[1].mag_brg.fixed()).format('000');
                    rtrue=numeral(r.physicals[1].true_brg.fixed(1)).format('000.0');
                    $("#mag_crs").val(rmag)
                    $("#true_crs").val(rtrue)
                }
            })
            
        }
    //    console.log(codchart,transseg.length,arpt[0].runwaystemp,$("#rwy_id").val())
    switch (currentpt) {
        case 'RF':
        if ($("#centerid").is(':visible')==false){
            aboutvol('centerid');
        }
        if ($("#arcid").is(':visible')==false){
            aboutvol('arcid');
        }
        if ($("#holdingid").is(':visible')==true){
            aboutvol('holdingid');
        }
            break;
        case 'AF':
            $("#radlabel").html('Radial')
            break;
        case 'IF':
            $("#mag_crs").val('');
            $("#rt_dist_from").val('');
            break;
        case 'CF':
            
            // $("#rt_dist_from").val('0');
            break;
        case 'CA':
        case 'VI':
        case 'VD':
        case 'VR':
        case 'CD':
        case 'CI':
        case 'CR':
        case 'VA':
            $("#refpoint").val('')
            $('#fixpoint').html('')
            $('#wd1').val('P')
            break;
        default:
            $("#radlabel").html('Mag Course')
            break;
    }
}
function pathterm_validasi(lastpt,currentpt){
    // inputrequired(currentpt)
        var hPT= true;
        var crpt=currentpt.toLowerCase();
        if(lastpt == ''){
            hPT = true;
        }else{
            var ptval=@json($ptval);
            var iv=ptval.findIndex(x=>x.id===lastpt);
            var pv=ptval[iv];
            // console.log(pv,crpt,pv[crpt])
            // Dim ssq As String = "select " & StrConv(CurrentPT, VbStrConv.Lowercase) & " from cod_pt_val where id='" & LastPT & "'"
            // Dim sql As String = GetInDB(ssq)
            // '  MsgBox(sql & "  " & ssq)
            if (pv[crpt]== "1"){
                hPT = false;
            } 
        }

        return hPT;
}
function Symbolnewpoint(point,vis){
    return '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown">'+point+'</a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">'+
                    '<ul class="link-list-plain">'+
                    '<a id="'+ vis +'" onclick="changepoint(this.id)" class="btn btn-dim btn-success col-md-12"><i class="icon ni ni-plus"></i> New '+point +'</a>'+
                    '</ul></div>'+
            '</div>';
}
function editpoint(id){
   
    if ($("#refpoint").val()=='WPT'){
        // console.log("WAYPOINT",id)
        window.scrollTo(0,0);
            window.location.href = '/waypointinfo/' + id + '@edit@listtranssegment@' +transseg.proc_id + '@' + codchart;

    }else if ($("#refpoint").val()=='NAV'){
        // console.log("NAVAID",id)
        window.location.href = '/navaidinfo/' + id + '@edit@listtranssegment@' +transseg.proc_id + '@' + codchart;
    }
}
function Symbolpoint(point,id,vis){
    return '<div class="dropdown">'+
            '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown">'+point+'</a>'+
            '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">'+
            '<ul class="link-list-plain">'+
            '<a class="btn btn-dim btn-secondary col-md-12" id="'+ id +'" onclick="editpoint(this.id)"><i class="icon ni ni-edit"></i> Edit </a>'+
            '<a id="'+ vis +'" onclick="changepoint(this.id)" class="btn btn-dim btn-success col-md-12"><i class="icon ni ni-exchange"></i> Change</a>'+
            '</ul></div>'+
            '</div>';
}
function getsamepoint(id){
//    console.log(id)
    var dtreq={'fix_id':id,'arpt_ident':trseg.arpt_ident,'chart_type':trseg.chart_type,'sub_chart_type':trseg.sub_chart_type}
        $.ajax({
                url: '../api/transition/seg/temp',
                data: dtreq,
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        $("#alt_desc").val(v.alt_desc)
                        $("#alt1").val(v.alt1)
                        $("#alt2").val(v.alt2)
                    
                    })
                }
        })
}
function changepoint(id){
    var referensi='';
    if (id=='spoint1'){
        referensi='pointsatu'
        if ($("#search2").is(':visible')==true){
            aboutvol('search2');
        }
        if ($("#search3").is(':visible')==true){
            aboutvol('search3');
        }
        if ($("#search4").is(':visible')==true){
            aboutvol('search4');
        }
        refsearch=$("#refpoint").val();
        // console.log('search1')
        aboutvol('search1');
    }else if (id=='spoint2'){
        referensi='pointdua'
        if ($("#search1").is(':visible')==true){
            aboutvol('search1');
        }
        if ($("#search3").is(':visible')==true){
            aboutvol('search3');
        }
        if ($("#search4").is(':visible')==true){
            aboutvol('search4');
        }
        refsearch=$("#refrecdnav1").val();
        // console.log('search2')
        aboutvol('search2');
    }else if (id=='spoint3'){
        referensi='pointtiga'
        if ($("#search1").is(':visible')==true){
            aboutvol('search1');
        }
        if ($("#search2").is(':visible')==true){
            aboutvol('search2');
        }
        if ($("#search4").is(':visible')==true){
            aboutvol('search4');
        }
        refsearch=$("#refrecdnav2").val();
        // console.log('search3')
        aboutvol('search3');
    }else if (id=='spoint4'){
        referensi='pointempat'
        if ($("#search1").is(':visible')==true){
            aboutvol('search1');
        }
        if ($("#search2").is(':visible')==true){
            aboutvol('search2');
        }
        if ($("#search3").is(':visible')==true){
            aboutvol('search3');
        }
        refsearch=$("#refpoint3").val();
        // console.log('search3')
        aboutvol('search4');
    }
        // console.log(refsearch,referensi)
    if (refsearch=='NAV'){
        $('.select2').select2({
            placeholder: 'select navaid ...',
            minimumInputLength: 1,
            ajax: {
                url: '../api/navaid/search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                        return {
                            q: params.term.toUpperCase()
                            //tambahkan parameter lainnya di sini jika ada
                        }
                },
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                                return {
                                    text:  item.nav_ident + ' ' + item.definition,
                                    geom:item.geom,
                                    id: item.nav_id,
                                    type:  item.type
                                }
                            })
                    };
                },
                cache: true
            },
            templateSelection: function (selection) {
                var result = selection.text;
                return result;
            },
            tags: true,
            tokenSeparators: [",", " "],
            createTag: function (tag) {
                return {
                    id: tag.term,
                    text: tag.term,
                    isNew : true
                };
            }
            
        }).on("select2:select", function(e) {
            if(e.params.data.isNew){
                var r = confirm("do you want to create a new navaid?");
                if (r == true) {
                    window.location.href = '/navaid/new@new@listtranssegment@' +transseg.proc_id + '@' + insert;
                    // $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
                }
                else
                {
                    $('.select2-selection__choice:last').remove();
                    $('.select2-search__field').val(e.params.data.text).focus()
                }
            }else{
            // console.log(referensi)
                if (referensi=='pointsatu'){
                    getsamepoint(e.params.data.id)
                    $("#fix_id").val(e.params.data.id);
                    if (e.params.data.type=='5' || e.params.data.type=='10' || e.params.data.type=='7'){
                        $("#wd1").val('N');
                    }else{
                        $("#wd1").val('V');
                    }
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=e.params.data.text + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    checkcodwptdesc4()
                    if (transB){
                        var fixid_search=transB.fix_id;
                        if (fixid_search){
                            if (transB.navaid.length > 0){
                                crd1=SetCoordinatebyGeom(transB.navaid[0].geom);
                            }else if (transB.waypoint.length > 0){
                                crd1=SetCoordinatebyGeom(transB.waypoint[0].geom);
                            }
                            calculate(crd1,crd2)
                        }
                    }
                    // $('#cord1').html(crd1.WGS[1] + '  ' +crd1.WGS[0] );
                    // $("#lat1").val(crd1.Decimal[1]);
                    // $("#lon1").val(crd1.Decimal[0]);
                
                    // console.log(e.params.data.id,'e.params.data.id')
                    // console.log(e.params.data.text,'e.params.data.text')
                    aboutvol('search1');
                }else if (referensi=='pointdua'){
                    $("#recd_nav").val(e.params.data.id);
                    $('#recdnav').html(Symbolpoint(e.params.data.text,e.params.data.id,'spoint2'));
                    var crd3=SetCoordinatebyGeom(e.params.data.geom);
                    if (crd2 !== []){

                        var crd4=crd2;
                        // $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                        // $("#lat2").val(crd2.Decimal[1]);
                        // $("#lon2").val(crd2.Decimal[0]);
                        calculate(crd3,crd4,'RECDNAV1')
                    }

                    aboutvol('search2');
                }else if (referensi=='pointtiga'){
                    $("#recd_nav1").val(e.params.data.id);
                    $('#recdnav1').html(Symbolpoint(e.params.data.text,e.params.data.id,'spoint3'));
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                    $("#lat2").val(crd2.Decimal[1]);
                    $("#lon2").val(crd2.Decimal[0]);

                    aboutvol('search3');
                }else if (referensi=='pointempat'){
                    $("#center_fix").val(e.params.data.id);
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    var txt=e.params.data.text+ '('+crd2.WGS[1] + '  ' +crd2.WGS[0]+')';
                    $('#fixpoint3').html(Symbolpoint(txt,e.params.data.id,'spoint4'));
                    

                    aboutvol('search4');
                }
                referensi='';
            }
        });
    }
    if (refsearch=='WPT'){
        $('.select2').select2({
        placeholder: 'select waypoint ...',
        minimumInputLength: 3,
        ajax: {
            url: '../api/waypoint/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                    return {
                        q: params.term.toUpperCase()
                        //tambahkan parameter lainnya di sini jika ada
                    }
            },
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                            return {
                                text:  item.desc_name + ' (' + item.wpt_name + ') ' + item.definition ,
                                geom:item.geom,
                                id: item.wpt_id
                            }
                        })
                };
            },
            cache: true
        },
        templateSelection: function (selection) {

            var result = selection.text;
            return result;
        },
        tags: true,
        tokenSeparators: [",", " "],
        createTag: function (tag) {
            return {
                id: tag.term,
                text: tag.term,
                isNew : true
            };
        }
    
        }).on("select2:select", function(e) {
            // console.log(e.params.data)
            if(e.params.data.isNew){
                var r = confirm("do you want to create a new waypoint?");
                if (r == true) {
                        window.location.href = '/waypoint/new@new@listtranssegment@' +transseg.proc_id + '@' + codchart;
                }
                else
                {
                    $('.select2-selection__choice:last').remove();
                    $('.select2-search__field').val(e.params.data.text).focus()
                }
            }else{
            // console.log(referensi)
                var ppp=e.params.data.text.split(' ');
                // console.log(ppp);
                if (referensi=='pointsatu'){
                    getsamepoint(e.params.data.id)
                    $("#fix_id").val(e.params.data.id);
                    fixid=e.params.data.id;
                    // $('#fixpoint').html(Symbolpoint(ppp[0],e.params.data.id,'spoint1'));
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=ppp[0] + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    checkcodwptdesc4()
                    if (transB){
                        var fixid_search=transB.fix_id;
                        if (fixid_search){
                            if (transB.navaid.length > 0){
                                crd1=SetCoordinatebyGeom(transB.navaid[0].geom);
                            }else if (transB.waypoint.length > 0){
                                crd1=SetCoordinatebyGeom(transB.waypoint[0].geom);
                            }
                            calculate(crd1,crd2)
                        }
                    }
                    // $('#cord1').html(crd1.WGS[1] + '  ' +crd1.WGS[0] );
                    // $("#lat1").val(crd1.Decimal[1]);
                    // $("#lon1").val(crd1.Decimal[0]);
                    aboutvol('search1');
                }else if (referensi=='pointempat'){
                    $("#center_fix").val(e.params.data.id);
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=ppp[0] + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint3').html(Symbolpoint(txtp,e.params.data.id,'spoint4'));
                    

                    aboutvol('search4');
                }
                referensi='';
            }
        });
    }

  
    if (refsearch=='ILS'){
        $('.select2').select2({
        placeholder: 'select ils ...',
        minimumInputLength: 3,
        ajax: {
            url: '../api/ils/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                    return {
                        q: params.term.toUpperCase()
                        //tambahkan parameter lainnya di sini jika ada
                    }
            },
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        // console.log(item,'ILS ITEM')
                            return {
                                text:  item.ils_ident + ' ' + item.ils_name ,
                                geom:item.gs_geom,
                                id: item.ils_id
                            }
                        })
                };
            },
            cache: true
        },
        templateSelection: function (selection) {

            var result = selection.text;
            return result;
        },
        tags: true,
        tokenSeparators: [",", " "],
        createTag: function (tag) {
            return {
                id: tag.term,
                text: tag.term,
                isNew : true
            };
        }
    
        }).on("select2:select", function(e) {
            // console.log(e.params.data)
            if(e.params.data.isNew){
                var r = confirm("do you want to create a new ils?");
                if (r == true) {
                    if ($("#status").val()=='R'){
                        window.location.href = '/waypoint/new@new@editats@' +ats.ats_id + '@' + insert;
                    }else{
                        window.location.href = '/waypoint/new@new@editats@@insert';

                    }
                    // $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
                }
                else
                {
                    $('.select2-selection__choice:last').remove();
                    $('.select2-search__field').val(e.params.data.text).focus()
                }
            }else{
            // console.log(referensi)
                var ppp=e.params.data.text.split(' ');
                // console.log(ppp);
                if (referensi=='pointsatu'){
                    $("#fix_id").val(e.params.data.id);
                    // $('#fixpoint').html(Symbolpoint(ppp[0],e.params.data.id,'spoint1'));
                    $("#wd1").val('V');
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=ppp[0] + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    checkcodwptdesc4()
                    if (transB){
                        var fixid_search=transB.fix_id;
                        if (fixid_search){
                            if (transB.navaid.length > 0){
                                crd1=SetCoordinatebyGeom(transB.navaid[0].geom);
                            }else if (transB.waypoint.length > 0){
                                crd1=SetCoordinatebyGeom(transB.waypoint[0].geom);
                            }
                            calculate(crd1,crd2)
                        }
                    }
                    aboutvol('search1');
                }else if (referensi=='pointdua'){
                    $("#recd_nav").val(e.params.data.id);
                    $('#recdnav').html(Symbolpoint(ppp[0],e.params.data.id,'spoint2'));
                    // $("#point_2").html(e.params.data.text);
                    var crd3=SetCoordinatebyGeom(e.params.data.geom);
                    // $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                    // $("#lat2").val(crd2.Decimal[1]);
                    // $("#lon2").val(crd2.Decimal[0]);
                    calculate(crd3,crd2,'RECDNAV1')
                    aboutvol('search2');
                }else if (referensi=='pointtiga'){
                    $("#recd_nav1").val(e.params.data.id);
                    $('#recdnav1').html(Symbolpoint(ppp[0],e.params.data.id,'spoint3'));
                    // $("#point_2").html(e.params.data.text);
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                    $("#lat2").val(crd2.Decimal[1]);
                    $("#lon2").val(crd2.Decimal[0]);

                    aboutvol('search3');
                }
                referensi='';
            }
        });
    }
    if (refsearch=='ARPT'){
        $('.select2').select2({
        placeholder: 'select airport ...',
        minimumInputLength: 3,
        ajax: {
            url: '../api/airport/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                    return {
                        q: params.term.toUpperCase()
                        //tambahkan parameter lainnya di sini jika ada
                    }
            },
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                            return {
                                text:  item.icao + ' ' +  item.arpt_name,
                                icao:  item.icao,
                                geom:item.geom,
                                id: item.arpt_ident
                            }
                        })
                };
            },
            cache: true
        },
        templateSelection: function (selection) {
            var result = selection.text;
            return result;
        },
    
        }).on("select2:select", function(e) {
            // console.log(e)
            if(e.params.data.isNew){
            var r = confirm("do you want to create a new Airport?");
            if (r == true) {
                NewData()
                $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
            }
            else
            {
                $('.select2-selection__choice:last').remove();
                $('.select2-search__field').val(e.params.data.text).focus()
            }
        }else{
            $("#fix_id").val(e.params.data.id);
            // $('#fixpoint').html(Symbolpoint(e.params.data.icao,e.params.data.id,'spoint1'));
                crd2=SetCoordinatebyGeom(e.params.data.geom);
                var txtp=e.params.data.icao + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    checkcodwptdesc4()
                if (transB){
                        var fixid_search=transB.fix_id;
                        if (fixid_search){
                            if (transB.navaid.length > 0){
                                crd1=SetCoordinatebyGeom(transB.navaid[0].geom);
                            }else if (transB.waypoint.length > 0){
                                crd1=SetCoordinatebyGeom(transB.waypoint[0].geom);
                            }
                            calculate(crd1,crd2)
                        }
                    }
                $("#arc_lat").val(crd1.Database[1])
                $("#arc_long").val(crd1.Database[0])
            // if ($("#search2").is(':visible')==true){
            //     aboutvol('search2');
            // }
            // if ($("#search1").is(':visible')==true){
                aboutvol('search1');
            // }
        }
    });

                // crd2=SetCoordinatebyGeom(pnt2geom);
                
        // });
           
    }
}

function calculate(crd1,crd2,posisi=''){
    // console.log(crd1 , crd2)
    if (crd1.length !==0 && crd2.length !==0){
        var dist=getdistance(crd1.Decimal[1],crd1.Decimal[0],crd2.Decimal[1],crd2.Decimal[0])
        var trko=dist.TrackOutMagReal.toFixed();
        var trki=dist.TrackInMagReal.toFixed();
        var trkt=dist.TrackOutReal.toFixed(1);
        
        trackout=numeral(trko).format('000');distance=dist.DistanceReal.toFixed(1);
        trackouttrue=numeral(trkt).format('000.0');distance=dist.DistanceReal.toFixed(1);
        trackinreal=numeral(trki).format('000');
        if (posisi==''){
            // console.log('CALCULATE.... ',dist)
            $("#rt_dist_from").val(distance);
            $("#mag_crs").val(trackout);
            $("#true_crs").val(trackouttrue);

        }else if (posisi=='RECDNAV1'){
            // console.log(distance,'RECDNAV1')
            $("#rho").val(distance);
            $("#theta").val(trackout);
        }
        
        
    }
}
</script>
@endsection