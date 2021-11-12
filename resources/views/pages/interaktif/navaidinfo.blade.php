@extends('layouts.app')

@section('template_title')
    Navaid Information
@endsection

@section('head')
<link href="{{ asset('template/assets/css/v-modal.css') }}" rel="stylesheet" >
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title" id="navid"></h5>
                    </div>
                </div>
                
        <div class="panel-body mt-3">
            <div class="row">
                @foreach($nav as $n)
                <div class="col-md-2">
                    <span>Ident</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->nav_ident}}</b>
                </div>
                <div class="col-md-2">
                    <span>Type</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->definition}}</b>
                </div>
                <div class="col-md-2">
                    <span>Name</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->nav_name}}</b>
                </div>
                <div class="col-md-2">
                    <span>frequency</span>
                    <br>
                    <b id="freqid" style="color:#0A4EAB; font-weight:bolder;"></b>
                </div>
                <div class="col-md-2">
                    <span>Latitude</span>
                    <br>
                    <b id="latid" style="color:#0A4EAB; font-weight:bolder;"></b>
                </div>
                <div class="col-md-2">
                    <span>Longitude</span>
                    <br>
                    <b id="lonid" style="color:#0A4EAB; font-weight:bolder;"></b>
                </div>
                <div class="col-md-2">
                    <span>Magvar</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->mag_var}}</b>
                </div>
                <div class="col-md-2">
                    <span>Opr. Hours</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->opr_hrs}}</b>
                </div>
                <div class="col-md-12">
                    <span>Remarks</span>
                    <br>
                    <span style="color:#0A4EAB; font-weight:bolder;">{{$n->remarks}}</span>
                </div>
                <div class="col-md-12">
                    <br>
                </div>
                @if ($n->col_dme == 'Y')
                <div class="col-md-12">
                    <div class="panel-heading">
                        <h6 class="panel-title">DME Information </h6>
                    </div>
                    <div  class="row">
                        <div class="col-md-2">
                            <span>Channel</span>
                            <br>
                            <b id="chid" style="color:#0A4EAB; font-weight:bolder;"></b>
                        </div>
                        <div class="col-md-2">
                            <span>DME Latitude</span>
                            <br>
                            <b id="dmelatid" style="color:#0A4EAB; font-weight:bolder;"></b>
                        </div>
                        <div class="col-md-2">
                            <span>DME Longitude</span>
                            <br>
                            <b id="dmelonid" style="color:#0A4EAB; font-weight:bolder;"></b>
                        </div>
                        <div class="col-md-2">
                            <span>Range</span>
                            <br>
                            <b style="color:#0A4EAB; font-weight:bolder;">{{$n->dme_range}}</b>
                        </div>
                        <div class="col-md-2">
                            <span>Elevation</span>
                            <br>
                            <b style="color:#0A4EAB; font-weight:bolder;">{{$n->dme_elev}}</b>
                        </div>

                    </div>
                </div>
                @endif

                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                    <button id="btnnavedit" style="visibility: hidden" onclick="navaidedit()" class="btn btn-sm btn-dim btn-dark"><i class="icon ni ni-edit"></i> Edit</button>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="row">
                    <div class="col-md-6" id="procasp" style="visibility: hidden">
                        <div id="proctable" style="visibility: hidden">
                            <h6>Used in Procedure Charts</h6>
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th>#</th>
                                        <th>Procedure Name</th>
                                    </tr>
                                </thead>
                                <tbody id="procinfo">
                                </tbody>
                            </table>
                        </div>
                        <div id="asptable" style="visibility: hidden">
                            <h6>Used in Airspace</h6>
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th>#</th>
                                        <th>Airspace Name</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody id="aspinfo">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6" id="atstable" style="visibility: hidden">
                        <h6>Used in ATS Routes</h6>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>Ident</th>
                                    <th>Point1</th>
                                    <th>Point2</th>
                                </tr>
                            </thead>
                            <tbody id="atsinfo">
                            </tbody>
                        </table>
                    </div>
                   
                </div>
                    <div class="col-md-12"  id="noused" style="visibility: hidden">
                        <h6>this data has not been used in ATS Routes, Procedures and Airspaces</h6>
                    </div>
                </div>
            </div>
            </div>
        </div>
    

@endsection
@section('footer_scripts') 
<script src="{{ asset('template/assets/js/v-modal.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $.wmBox();
});
</script>
<script type="text/javascript">
var ats =@json($ats);
var asp =@json($asp);
var proc =@json($proc);
var nav =@json($nav);
var ch =@json($channel);
var parent =@json($parent);
var parentid =@json($parentid);
var atsstatus =@json($atsstatus);
var showedit=@json($id);
$('#atstable').hide();
$('#proctable').hide();
$('#asptable').hide();
$('#noused').hide();
$('#procasp').hide();
$('#btnnavedit').hide();
if (showedit=="edit"){
    aboutvol('btnnavedit');
}
function navaidedit(){
    // console.dir(nav[0].nav_id)
    window.location.href = '/navaid/' + nav[0].nav_id + '@edit@' + parent + '@' + parentid + '@'+atsstatus;
        window.scrollTo(0,0);
}
// console.dir(ats)
// console.dir(proc)
// console.dir(asp)
if (ats.length == 0 && asp.length==0 && proc.length == 0){
    aboutvol('noused');
    hasil ='<h6 align="center" style="color:#0A4EAB; font-weight:bolder;">:: ' + nav[0].nav_ident + ' ' + nav[0].definition + ' - ' + nav[0].nav_name + ' is not used in ATS Routes, Procedures and Airspaces ::</h6>'
    $("#noused").html(hasil);
}
// console.log(nav[0])
$('#freqid').html(FreqFormat(nav[0].freq,nav[0].type,''));
$('#navid').html(nav[0].nav_ident + ' ' + nav[0].definition + ' - ' + nav[0].nav_name + ' Information' );
var navcord= SetCoordinatebyGeom(nav[0].geom)
$('#latid').html(navcord.WGS[1]);$('#lonid').html(navcord.WGS[0]);
if (nav[0].type=='4'){
    if (nav[0].col_dme=='Y'){
        frq = FreqFormat(nav[0].freq,nav[0].type,'DATA')
        $('#chid').html('CH-' + ch.find( x => x.definition === frq ).id)
        if (nav[0].dmegeom==null){
            var dmecord= SetCoordinate(nav[0].dme_wgs_lat,nav[0].dme_wgs_long);
        }else{
            // console.log(nav[0].dmegeom)
            var dmecord= SetCoordinatebyGeom(nav[0].dmegeom);
        }
        
        // console.dir(nav)
        $('#dmelatid').html(dmecord.WGS[1]);$('#dmelonid').html(dmecord.WGS[0]);
    }
    
}
if (asp.length > 0 || proc.length > 0){
    aboutvol('procasp');
}
if (ats.length > 0){
    aboutvol('atstable');
 var hasil='';
        ats.forEach(a=>{
            // console.log(a);
            hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-info" id="'+ a.ctry +'$atslist'+ '" onClick="showmapdetail(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + a.ats_ident + '</td><td>' + a.point1  + '</td><td>' + a.point2 + '</td></tr>'
            $("#atsinfo").append(hasil);
        })
    
}
if (proc.length > 0){
    aboutvol('proctable');
 var hasil='';
        proc.forEach(a=>{
            // console.log(a);
            hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-info" id="'+ a.proc_id +'$proc' + '" onClick="showmapdetail(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + a.procedure_name  + '</td></tr>'
            $("#procinfo").append(hasil);
        })
    
    
}
if (asp.length > 0){
    aboutvol('asptable');
 var hasil='';
        asp.forEach(a=>{
            // console.log(a);
            hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-info" id="'+ a.ats_airspace_id +'$airspace' + '" onClick="showmapdetail(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + a.airspace_name  + '</td><td>' + a.airspace_type  + '</td></tr>'
            $("#aspinfo").append(hasil);
        })
    
    
}
function showmapdetail(id){
    var ddd=id.split('$');
    // console.log(id)
    var ddd = id.split( '$' );
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // window.open('/map.php?table='+ddd[1]+'&id='+ddd[0], 'Set Latitude and Longitude', params);
    vModal('/map.php?table='+ddd[1]+'&id='+ddd[0],"mdSize");
}
function backtolist(){
    history.back();

}




</script>
@endsection