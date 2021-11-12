@extends('layouts.app')

@section('template_title')
    Charts
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body mt-3">
            <div class="nk-block-between">
                <h5 class="panel-title" id="holdingedit">Chart Properties</h5>
            </div>
            <ul class="nav nav-tabs" id="tabMenu">
                <!-- <li class="nav-item"> -->
                <li class="nav-item tab-pane{{old('tab') == 'tabItem1' ? ' active' : null}}">
                    <a class="nav-link active" data-toggle="tab" href="#tabItem1"><span>Properties</span></a>
                </li>
                <li class="nav-item tab-pane{{old('tab') == 'tabItem2' ? ' active' : null}}">
                    <a class="nav-link"  data-toggle="tab" href="#tabItem2"><span>Frequency</span></a>
                </li>
                <li id="minimaid" class="nav-item tab-pane{{old('tab') == 'tabItem3' ? ' active' : null}}">
                    <a class="nav-link"  data-toggle="tab" href="#tabItem3"><span>Minima</span></a>
                </li>
                <li id="codingtableid" class="nav-item tab-pane{{old('tab') == 'tabItem4' ? ' active' : null}}">
                    <a class="nav-link"  data-toggle="tab" href="#tabItem4"><span>Coding table</span></a>
                </li>
            </ul>
            <div class="tab-content tabairspace" id="tabasp">
                <div class="tab-pane active" id="tabItem1">
                    <div class="panel-body mt-3">
                        <form action="../../api/chartprop/save" method="post"  enctype="multipart/form-data" id="holdingremove">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="listproc" id="listproc">
                            <input type="hidden" name="listfreq" id="listfreq">
                            <input type="hidden" name="chart_id" id="chart_id">
                            <input type="hidden" name="chart_arpt_ident" id="chart_arpt_ident" value="{{$airport[0]->arpt_ident}}">
                            <input type="hidden" name="save_status" id="status">
                            <input type="hidden" name="deg" id="deg">
                            <input type="hidden" name="mapt" id="mapt">
                            <input type="hidden" name="precision" id="precision">
                            <input type="hidden" name="faf" id="faf">
                            <input type="hidden" name="sn" id="sn">
                            <div class="card-inner table-bordered mt-1">
                                <div class="row">
                                    <div class="col-md-8">
                                        <strong>Chart Type</strong>
                                        <br>
                                        <select id="chart_type" name="chart_type" onchange="changechartcode()" class="form-control" >
                                        <option value="">None</option>
                                        @foreach ($cod as $k)
                                            @if($k->code !== null)
                                                <option value="{{$k->id}}">{{$k->description}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Planview Frame</strong>
                                        <br>
                                        <select id="bm_id" name="bm_id" onchange="viewframe()" class="form-control" >
                                    
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Left Top Title</strong>
                                        <br>
                                        <input id="customer" type="text" class="form-control" name="customer" value="AIP INDONEISA">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Left Bottom Title</strong>
                                        <br>
                                        <input id="footer" type="text" class="form-control" name="footer" value="Directorate General of Civil Aviation">
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Page</strong>
                                        <br>
                                        <input id="page" type="text" style="text-transform:uppercase" onfocusout="chartname()" class="form-control" name="page">
                                    </div>
                                    <div class="col-md-2">
                                        <strong>RNAV</strong>
                                        <br>
                                        <select onchange="changechartcode()" class="form-control" id="rnav" name="rnav">
                                            <option value="N">NO</option>
                                            <option value="Y">YES</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>RWY</strong>
                                        <br>
                                        <select id="rwy" name="rwy" onchange="changerwy()" class="form-control" >
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>MSA</strong>
                                        <br>
                                        <select id="msa_id" name="msa_id" class="form-control" >
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <strong id="refid">Reference</strong>
                                        <br>
                                        <select id="nav" name="nav" onchange="chartname()" class="form-control" >
                                    
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <strong id="catid">Category</strong>
                                        <br>
                                        <select id="cat" name="cat" onchange="ocacategory()" class="form-control" >
                                        </select>
                                    </div>
                                    <!-- <div class="col-md-12">
                                        <strong>Notes</strong>
                                        <br>
                                        <input type="text" class="form-control" id="remarks"  name="remarks"></input>
                                    </div> -->
                                    <div class="col-md-12 mt-1" id="btn_changepubdate">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Source</strong>
                                        <br>
                                        <select class="form-control" onchange="sourcechange()" id="source" name="source">
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Nr</strong>
                                        <br>
                                        <input type="text" style="text-transform:uppercase" onfocusout="getsourcenr()" class="form-control" id="srcnum" name="srcnum">
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Pub Date</strong>
                                        <br>
                                        <select class="form-control" onchange="getAirac()" id="publish_date" name="publish_date">
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Eff Date</strong>
                                        <br>
                                        <input type="text" style="text-transform:uppercase" onchange="chartname()" class="form-control" id="eff_date" name="eff_date" >
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Sequence</strong>
                                        <br>
                                        <input id="seq" type="text" class="form-control" name="seq">
                                    </div>
                                    <div class="col-md-10">
                                        <strong>Chart Name</strong>
                                        <br>
                                        <input id="chart_name" type="text" class="form-control" name="chart_name">
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
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover mt-3" id="table-proc">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th class="active">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th>Name</th>
                                <th>RWY</th>
                                <th>Proc. Text</th>
                            </tr>
                        </thead>
                        <tbody id="proclist">

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tabItem2">
                    <table class="table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th class="active">
                                    <input type="checkbox" class="select-freq checkbox" name="select-freq" />
                                </th>
                                <th></th>
                                <th>No</th>
                                <th>Type</th>
                                <th>Call Sign</th>
                                <th>Frequency</th>
                            </tr>
                        </thead>
                        <tbody id="freqlist">

                        </tbody>
                    </table>
                    <div class="row">
                            <div class="col-md-12">
                                <br>
                            </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <button onclick="isbacktomain()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem3">
                    <div class="row mt-3">
                        <form action="../../api/minima/save" method="post"  enctype="multipart/form-data" id="minimaform">
                            <input type="hidden" name="_token" id="mintoken" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="mineditor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="minid">
                            <input type="hidden" name="chart_id" id="minchart_id">
                            <input type="hidden" name="chart_arpt_ident" id="minchart_arpt_ident" value="{{$airport[0]->arpt_ident}}">
                            <input type="hidden" name="save_status" id="minstatus">
                            <input type="hidden" name="deg" id="deg">
                            <input type="hidden" name="mapt" id="mapt">
                            <input type="hidden" name="precision" id="precision">
                            <input type="hidden" name="faf" id="faf">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2" style="text-align:center">
                                        <span>RWY</span>
                                        <br>
                                        <input type="text" style="text-align:center" class="form-control" id="rwy_ident"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <span>THR Elev (ft)</span>
                                        <br>
                                        <input type="text" style="text-align:center" class="form-control" id="thr_elev"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <span>AD Elev (ft)</span>
                                        <br>
                                        <input type="text" style="text-align:center" class="form-control" id="ad_elev"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <span>Approach Type</span>
                                        <input type="text" style="text-align:center" class="form-control" id="approach_type"/>
                                    </div>
                                    <div class="col-md-4" style="text-align:center">
                                        <span>App. Light Type</span>
                                        <br>
                                        <input type="text" style="text-align:center" class="form-control" id="app_light"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <strong>Type</strong>
                                        <input type="text" style="text-align:center" class="form-control" id="app_type" name="app_type"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <strong>Length</strong>
                                        <input type="number" style="text-align:center" class="form-control" id="app_len" name="app_len"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <strong>FAF/FAP alt.</strong>
                                        <input type="number" style="text-align:center" class="form-control" id="faf_alt" name="faf_alt"/>
                                    </div>
                                    <div class="col-md-3" style="text-align:center">
                                        <strong>Dist. FAF/FAP to THR</strong>
                                        <input type="number" style="text-align:center" class="form-control" id="faf_to_thr" name="faf_to_thr"/>
                                    </div>
                                    <div class="col-md-3" style="text-align:center">
                                        <strong>FAF/FAP to MAPt</strong>
                                        <input type="number" style="text-align:center" class="form-control" id="dist" name="dist"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <strong>RDH</strong>
                                        <input type="number" style="text-align:center" class="form-control" id="rdh" name="rdh"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <strong>Descend Angle</strong>
                                        <input type="number" style="text-align:center" class="form-control" id="descend" name="descend"/>
                                    </div>
                                    <div class="col-md-2" style="text-align:center">
                                        <strong>Precison</strong>
                                        <select class="form-control" id="precicion" name="precicion">
                                            <option value="F">NO</option>
                                            <option value="T">YES</option>
                                        </select>
                                    </div>
                                    <!-- <div class="col-md-2" style="text-align:center">
                                        <strong>Dist Crossing alt</strong>
                                        <input type="number" style="text-align:center" class="form-control" id="descend" name="descend"/>
                                    </div> -->
                                </div>
                            <!-- </div> -->
                        </div>
                        <div class="col-md-12 mt-3">
                            <h5 style="text-align:center">O C A ( H ) - Obstacle Clearance Altitude ( Height )</h5>
                            <table class="table table-bordered table-hover" style="width:100%" id="table-content">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th>ACFT Cat.</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>D</th>
                                    </tr>
                                </thead>
                                <tbody id="oca">

                                </tbody>
                                <tbody id="gpinop">

                                </tbody>
                                <tbody id="circling">

                                </tbody>
                            </table>
                        </div>
                        <div id="dist_alt" style="visibility: hidden">
                            <div class="col-md-12">
                                <h5 style="text-align:center">Distance and Altitude</h5>
                                    <table class="table table-bordered table-hover" style="width:100%" id="table-content">
                                        <tbody id="nmto">

                                        </tbody>
                                    </table>
                            </div>
                            <div class="col-md-12 mt-1">
                                    <strong>Distance : </strong>
                                    <input type="text" class="form-control" id="dist_rem" name="dist_rem"/>
                            </div>
                            <div class="col-md-12 mt-2">
                                <h5 style="text-align:center">Ground Speed Rate of Desence</h5>
                                <table class="table table-bordered table-hover" style="width:100%" id="table-content">
                                    <tbody id="rod">

                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-12 mt-1">
                                        <strong>Notes : </strong>
                                        <textarea type="text" class="form-control" id="noted" name="noted"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                <br>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-12">
                                <button onclick="isbacktomain()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                                &nbsp;
                                <button onclick=updateminima() id="btn_save" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
                            </div>
                        </div>
                </div>
                <div class="tab-pane" id="tabItem4">
                    <form id="TheForm" method="post" action="/pdf" target="TheWindow">
                        @csrf
                        <input type="hidden" name="eaipdata" id="eaipdata" />
                        <input type="hidden" name="arptid" id="arptid" />
                        <input type="hidden" name="chart" id="chart"/>
                        <input type="hidden" name="table" id="table" value="codingtable"/>
                        <input type="hidden" name="header" id="pdf_header"/>
                        <input type="hidden" name="footer" id="pdf_footer"/>
                        <input type="hidden" name="width" id="width"/>
                        <input type="hidden" name="high" id="high"/>
                        <input type="hidden" name="source" id="pdf_source"/>
                        <input type="hidden" name="pubdate" id="pdf_pubdate"/>
                        <input type="hidden" name="effdate" id="pdf_effdate"/>
                        <input type="hidden" name="nr" id="nr"/>
    
                        <div class="panel-body mt-3">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <strong>File Name</strong>
                                        <br>
                                        <input type="text" class="form-control" id="filenm" name="filenm">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <strong>Watermark</strong>
                                        <br>
                                        <input type="text" class="form-control" id="wtrmark" name="wtrmark" value="D R A F T">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                &nbsp;
                                <div class="col-md-12">
                                    &nbsp;
                                    <a onclick="isbacktomain()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                    &nbsp;
                                    &nbsp;
                                    <a id="btn_formulir" class="btn btn-dim btn-dark"><i class="icon ni ni-file-pdf"></i> Generate</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="changeseq" style="visibility:hidden">
                <form action="../../api/freqchart/seq" method="post"  enctype="multipart/form-data" id="changeseq_form">
                    <input type="hidden" name="_token" id="change_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="change_editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="id" id="change_id">
                    <input type="hidden" name="freqid" id="change_freqid">
                    <input type="hidden" name="arpt_ident" id="change_arpt_ident">
                    <input type="hidden" name="seq" id="change_seq">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
var charts =@json($chart);arpt=@json($airport);cod=@json($cod);bm=@json($bm);proc=@json($proc);freq=@json($freq);arptfreq=@json($arptfreq);elev=@json($elev);codwpt=@json($wptdesc);rwylist=[];edit=@json($edit);
// console.log(arpt)
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show');
});
if (elev.length>0){
        if (elev[0].content=='' ||elev[0].content==null ){
            $("#ad_elev").val(arpt[0].elev);
        }else{
            $("#ad_elev").val(elev[0].content);
        
        }
    }else{
    $("#ad_elev").val(arpt[0].elev);
    }

var nav=@json($navaids);rawdata=@json($rawdata);changechartname=true;ismajor=true; pOca = 0;
$("#table-proc").hide();$("#dist_alt").hide(); $("#search1").hide();$("#bmdetail").hide();$("#customid").hide(); htemp=null;hcurr=null;
// var fld=['bm_id','chart_id','chart_name','chart_type','customer','footer','msa_id','sn','seq','rwy','nav','cat','page','rnav','remarks'];
var fld=['bm_id','chart_id','chart_name','chart_type','customer','footer','msa_id','sn','seq','rwy','nav','cat','page','rnav'];
// console.log(charts)




function isbacktomain(){
  
    // chartprop/ID00046/pro
    // console.log('tab')
    $('#tabMenu a[href="#tabItem1"]').tab('show');
    
}
// console.log(arptfreq,freq)
$('#btn_formulir').click(function() {
    $("#arptid").val(arpt[0].arpt_ident)
    $("#chart").val($("#chart_type").val())
    $("#pdf_header").val($("#customer").val())
    $("#pdf_footer").val( $("#footer").val())
    $("#width").val()
    $("#high").val()
    $("#pdf_source").val($("#source").val())
    $("#pdf_pubdate").val( $("#publish_date").val())
    $("#pdf_effdate").val($("#eff_date").val())
    $("#nr").val($("#srcnum").val())

    $("#eaipdata").val(htemp.chart_id);
 
    var f = document.getElementById('TheForm');
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // console.log(something,additional,misc)
    window.open('', 'TheWindow',params);
    f.submit();
    // $('#lat').val();
    // $('#TheForm').submit();
});


var navcode= [{
                id: '1',
                value: 'RNAV'
            }, {
                id: '2',
                value: 'RNP'
            }]
var cate= [{
                id: '1',
                value: 'CAT A/B/C/D'
            }, {
                id: '2',
                value: 'CAT A/B/C'
            }, {
                id: '3',
                value: 'CAT A/B'
            }, {
                id: '4',
                value: 'CAT A'
            }, {
                id: '5',
                value: 'CAT C/D'
            }, {
                id: '6',
                value: 'CAT C'
            }, {
                id: '7',
                value: 'CAT D'
            }]
var sourcelist= [{
        key: 'AIRAC AIP AMDT',
        value: 'AIRAC AIP AMDT'
        }, {
            key: 'AIC',
            value: 'AIC'
        }, {
            key: 'AIRAC AIP SUPP',
            value: 'AIRAC AIP SUPP'
        }, {
            key: 'AIP AMDT',
            value: 'AIP AMDT NON AIRAC'
        }, {
            key: 'AIP SUPP',
            value: 'AIP SUPP NON AIRAC'
        }];

listcate(cate);
listnav()

arpt[0].runwaystemp.forEach(r=>{
    rwylist.push(r);
    var hsl= '<option value="'+r.thr_low+'">'+r.thr_low+'</option>'+
                '<option value="'+r.thr_high+'">'+r.thr_high+'</option>'+
                '<option value="'+r.thr_low+'/'+r.thr_high+'">'+r.thr_low+'/'+r.thr_high+'</option>';
    $("#rwy").append(hsl);
})
$("#rwy").append('');

function listnav(){
    $("#nav").empty();
    var hsl= '<option value=""></option>';
        $("#nav").append(hsl);
    navcode.forEach(r=>{
        hsl= '<option value="'+r.value+'">'+r.value+'</option>';
        $("#nav").append(hsl);
    })
    $("#msa_id").empty();
    getmsa(arpt[0].arpt_ident)
    nav.forEach(r=>{
        // console.log(r)
        var nval='';ntype='';
        if (r.navaid.length >0){
            if (r.navaid[0].type == '5' || r.navaid[0].type == '7' || r.navaid[0].type == '4' || r.navaid[0].type == '1' || r.navaid[0].type == '10')
            nval=r.navaid[0].definition + ' - ' + r.navaid[0].nav_ident
            ntype=r.navaid[0].definition;
            getmsa(r.navaid[0].nav_id)
        }else if (r.ils.length >0){
            nval='ILS - ' + r.ils[0].ils_ident
            ntype ='ILS';
        }
        if (nval !==''){
        // console.log(nval)

            var hsl= '<option value="'+nval+'">'+nval+'</option>';
            $("#nav").append(hsl);
    
        }
    })
    
}
function sourcechange(){
    var src=$("#source").val();
    var srcnr=$("#srcnum").val();
    var ccst='AIP INDONESIA';
    switch (src) {
        case 'AIRAC AIP SUPP':
            ccst='AIRAC AIP SUPPLEMENT ' + srcnr;
            break;
        case 'AIP SUPP':
            ccst='AIP SUPPLEMENT ' + srcnr;
            break;
    }
    $("#customer").val(ccst)
}
function listcate(data){
    $("#cat").empty();
    var hsl= '<option value=""></option>';
        $("#cat").append(hsl);
    data.forEach(r=>{
        
        var hsl= '<option value="'+r.value+'">'+r.value+'</option>';
        $("#cat").append(hsl);
    })
    
}
bm.forEach(r=>{
    var hsl= '<option value="'+r.chart_id+'">'+r.chart_id+'</option>';
                $("#bm_id").append(hsl);
})
sourcelist.forEach(r=>{
    var hsl= '<option value="'+r.key+'">'+r.value+'</option>';
    $("#source").append(hsl);
})
var hrf=['a','b','c','d']
var  hsl= '<tr>'+
            '<td id="straightin">Straight In</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="oca'+hrf[idx]+'" name="oca'+hrf[idx]+'" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Visibility ALS</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="oca'+hrf[idx]+'1" name="oca'+hrf[idx]+'1" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Visibility No ALS</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="noals'+hrf[idx]+'" name="noals'+hrf[idx]+'" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr>';
            $("#oca").append(hsl);
            hsl = '</tr><tr><td colspan="5"></td></tr><tr>'+
            '<td id="gpinoplbl">LNAV</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="gpinop'+hrf[idx]+'" name="gpinop'+hrf[idx]+'" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Visibility ALS</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="gp'+hrf[idx]+'1" name="gp'+hrf[idx]+'1" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Visibility No ALS</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="noalsgp'+hrf[idx]+'" name="noalsgp'+hrf[idx]+'" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr>';
            $("#gpinop").append(hsl);

        hsl = '</tr><tr><td colspan="5"></td></tr><tr>'+
            '<td>Circling</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="circ_'+hrf[idx]+'" name="circ_'+hrf[idx]+'" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Visibility ALS</td>';
            for (let idx = 0; idx < 4; idx++) {
                hsl += '<td><input id="circ_'+hrf[idx]+'_val" name="circ_'+hrf[idx]+'_val" type="number" onkeyup="ocavalue(this.id)" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr>';


        $("#circling").append(hsl);

        hsl = '<tr>'+
            '<td id="nm_to" ></td>';
            for (let idx = 1; idx < 8; idx++) {
                hsl += '<td><input id="dme'+idx+'" name="dme'+idx+'" type="number" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Altitude</td>';
            for (let idx = 1; idx < 8; idx++) {
                hsl += '<td><input id="dme_alt'+idx+'" name="dme_alt'+idx+'" type="number" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr>';


        $("#nmto").append(hsl);

        var  hsl= '<tr id="rod">'+
            '<td>Ground Speed (knots)</td>';
            for (let idx = 1; idx < 8; idx++) {
                hsl += '<td><input id="gs'+idx+'" name="gs'+idx+'" type="number" onkeyup="rodvalue()" style="text-align: center; vertical-align: middle;width:100%" /></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Time (min : sec)</td>';
            for (let idx = 1; idx < 8; idx++) {
                hsl += '<td><input id="t'+idx+'" name="t'+idx+'" type="text" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr><tr>'+
            '<td>Rate of Descent (ft / min)</td>';
            for (let idx = 1; idx < 8; idx++) {
                hsl += '<td><input id="rod'+idx+'" name="rod'+idx+'" type="number" style="text-align: center; vertical-align: middle;width:100%"/></td>';
                
            }
            hsl += '</tr>';
        $("#rod").append(hsl);
        
        
        
if (edit=='edit'){
    EditChart();
}else{
    NewData();
}   

function ocavalue(id){
    var ocaval=$("#" + id).val();
    // oca_visibility(ocaval,false)
    // oca_visibility(265,false)
    // oca_visibility(720,true)
    switch (id) {
        case 'ocaa':
            $("#ocab").val(ocaval)
            $("#ocac").val(ocaval)
            $("#ocad").val(ocaval)
            oca_visibility(id,false)
            break;
        case 'ocab':
            $("#ocac").val(ocaval)
            $("#ocad").val(ocaval)
            oca_visibility(id,false)
            break;
        case 'ocac':
            $("#ocad").val(ocaval)
            oca_visibility(id,false)
            break;
        case 'ocaa1':
            $("#ocab1").val(ocaval)
            $("#ocac1").val(ocaval)
            $("#ocad1").val(ocaval)
            break;
        case 'ocab1':
            $("#ocac1").val(ocaval)
            $("#ocad1").val(ocaval)
            break;
        case 'ocac1':
            $("#ocad1").val(ocaval)
            break;
        case 'noalsa':
            $("#noalsb").val(ocaval)
            $("#noalsc").val(ocaval)
            $("#noalsd").val(ocaval)
            break;
        case 'noalsb':
            $("#noalsc").val(ocaval)
            $("#noalsd").val(ocaval)
            break;
        case 'noalsc':
            $("#noalsd").val(ocaval)
            break;
        case 'gpinopa':
            $("#gpinopb").val(ocaval)
            $("#gpinopc").val(ocaval)
            $("#gpinopd").val(ocaval)
            oca_visibility(id,false)
            break;
        case 'gpinopb':
            $("#gpinopc").val(ocaval)
            $("#gpinopd").val(ocaval)
            oca_visibility(id,false)
            break;
        case 'gpinopc':
            $("#gpinopd").val(ocaval)
            oca_visibility(id,false)
            break;
        case 'gpa1':
            $("#gpb1").val(ocaval)
            $("#gpc1").val(ocaval)
            $("#gpd1").val(ocaval)
            break;
        case 'gpb1':
            $("#gpc1").val(ocaval)
            $("#gpd1").val(ocaval)
            break;
        case 'gpc1':
            $("#gpd1").val(ocaval)
            break;
        case 'noalsgpa':
            $("#noalsgpb").val(ocaval)
            $("#noalsgpc").val(ocaval)
            $("#noalsgpd").val(ocaval)
            break;
        case 'noalsgpb':
            $("#noalsgpc").val(ocaval)
            $("#noalsgpd").val(ocaval)
            break;
        case 'noalsgpc':
            $("#noalsgpd").val(ocaval)
            break;
        case 'circ_a':
            $("#circ_b").val(ocaval)
            $("#circ_c").val(ocaval)
            $("#circ_d").val(ocaval)
            oca_visibility(id,true)
            break;
        case 'circ_b':
            $("#circ_c").val(ocaval)
            $("#circ_d").val(ocaval)
            oca_visibility(id,true)
            break;
        case 'circ_c':
            $("#circ_d").val(ocaval)
            oca_visibility(id,true)
            break;
        case 'circ_a_val':
            $("#circ_b_val").val(ocaval)
            $("#circ_c_val").val(ocaval)
            $("#circ_d_val").val(ocaval)
            break;
        case 'circ_b_val':
            $("#circ_c_val").val(ocaval)
            $("#circ_d_val").val(ocaval)
            break;
        case 'circ_c_val':
            $("#circ_d_val").val(ocaval)
            break;
    }
    // console.log(id)
    ocacategory();
}
function ocacategory(){
    var  hrf=['a,','b','c','d'];
    var gs=[70,80,100,120,140,160,180];

    // console.log(pOca)
    // $('#ocaa, #ocab, #ocac, #ocad').val(pOca);
    var cato=$("#cat").val()
    // console.log(cato,'ocacategory')
    switch (cato) {
        case 'CAT A/B/C/D':
            $('#ocaa, #ocab, #ocac, #ocad').prop('disabled', false);
            $('#ocaa1, #ocab1, #ocac1, #ocad1').prop('disabled', false);
            $('#noalsa, #noalsb, #noalsc, #noalsd').prop('disabled', false);

            $('#gpinopa, #gpinopb, #gpinopc, #gpinopd').prop('disabled', false);
            $('#gpa1, #gpb1, #gpc1, #gpd1').prop('disabled', false);
            $('#noalsgpa, #noalsgpb, #noalsgpc, #noalsgpd').prop('disabled', false);

            $('#circ_a, #circ_b, #circ_c, #circ_d').prop('disabled', false);
            $('#circ_a_val, #circ_b_val, #circ_c_val, #circ_d_val').prop('disabled', false);
            break;
        case 'CAT A/B/C':
            $('#ocaa, #ocab, #ocac').prop('disabled', false);
            $('#ocad').prop('disabled', true);
            $('#ocaa1, #ocab1, #ocac1').prop('disabled', false);
            $('#ocad1').prop('disabled', true);
            $('#noalsa, #noalsb, #noalsc').prop('disabled', false);
            $('#noalsd').prop('disabled', true);

            $('#ocad').val('');
            $('#noalsd').val('');
            $('#ocad1').val('');

            $('#gpinopa, #gpinopb, #gpinopc').prop('disabled', false);
            $('#gpa1, #gpb1, #gpc1').prop('disabled', false);
            $('#noalsgpa, #noalsgpb, #noalsgpc').prop('disabled', false);
            $('#gpinopd').prop('disabled', true);
            $('#gpd1').prop('disabled', true);
            $('#noalsgpd').prop('disabled', true);
            $('#gpinopd').val('');
            $('#gpd1').val('');
            $('#noalsgpd').val('');

            $('#circ_a, #circ_b, #circ_c').prop('disabled', false);
            $('#circ_d').prop('disabled', true);
            $('#circ_d').val('');

            $('#circ_a_val, #circ_b_val, #circ_c_val').prop('disabled', false);
            $('#circ_d_val').prop('disabled', true);
            $('#circ_d_val').val('');
            break;
        case 'CAT A/B':
            gs=[70,80,90,100,110,120,130];
            $('#ocaa, #ocab').prop('disabled', false);
            $('#ocac, #ocad').prop('disabled', true);
            $('#ocaa1, #ocab1').prop('disabled', false);
            $('#ocac1, #ocad1').prop('disabled', true);
            $('#noalsa, #noalsb').prop('disabled', false);
            $('#noalsc, #noalsd').prop('disabled', true);

            $('#ocac, #ocad').val('');
            $('#noalsc, #noalsd').val('');
            $('#ocac1, #ocad1').val('');

            $('#gpinopa, #gpinopb').prop('disabled', false);
            $('#gpa1, #gpb1').prop('disabled', false);
            $('#noalsgpa, #noalsgpb').prop('disabled', false);
            $('#gpinopc,#gpinopd').prop('disabled', true);
            $('#gpd1, #gpc1').prop('disabled', true);
            $('#noalsgpd,#noalsgpc').prop('disabled', true);
            $('#gpinopc,#gpinopd').val('');
            $('#gpc1,#gpd1').val('');
            $('#noalsgpc,#noalsgpd').val('');

            $('#circ_a, #circ_b').prop('disabled', false);
            $('#circ_c, #circ_d').prop('disabled', true);
            $('#circ_c, #circ_d').val('');

            $('#circ_a_val, #circ_b_val').prop('disabled', false);
            $('#circ_c_val,#circ_d_val').prop('disabled', true);
            $('#circ_c_val,#circ_d_val').val('');

            break;
        case 'CAT A':
            gs=[70,80,90,100,110,120,130];
            $('#ocaa').prop('disabled', false);
            $('#ocab, #ocac, #ocad').prop('disabled', true);
            $('#ocaa1').prop('disabled', false);
            $('#ocab1, #ocac1, #ocad1').prop('disabled', true);
            $('#noalsa').prop('disabled', false);
            $('#noalsb, #noalsc, #noalsd').prop('disabled', true);

            $('#ocab, #ocac, #ocad').val('');
            $('#ocab1, #ocac1, #ocad1').val('');
            $('#noalsb, #noalsc, #noalsd').val('');

            $('#gpinopa').prop('disabled', false);
            $('#gpinopb, #gpinopc, #gpinopd').prop('disabled', true);
            $('#gpa1').prop('disabled', false);
            $('#gpb1, #gpc1, #gpd1').prop('disabled', true);
            $('#noalsgpa').prop('disabled', false);
            $('#noalsgpb, #noalsgpc, #noalsgpd').prop('disabled', true);

            $('#gpinopb, #gpinopc, #gpinopd').val('');
            $('#gpb1, #gpc1, #gpd1').val('');
            $('#noalsgpb, #noalsgpc, #noalsgpd').val('');

            $('#circ_a').prop('disabled', false);
            $('#circ_b,#circ_c, #circ_d').prop('disabled', true);
            $('#circ_b,#circ_c, #circ_d').val('');

            $('#circ_a_val').prop('disabled', false);
            $('#circ_b_val,#circ_c_val,#circ_d_val').prop('disabled', true);
            $('#circ_b_val,#circ_c_val,#circ_d_val').val('');

            break;
        case 'CAT C/D':
            gs=[100,120,130,140,150,160,180];
            $('#ocaa, #ocab').prop('disabled', true);
            $('#ocaa1, #ocab1').prop('disabled', true);
            $('#ocac, #ocad').prop('disabled', false);
            $('#ocac1, #ocad1').prop('disabled', false);
            $('#noalsa, #noalsb').prop('disabled', true);
            $('#noalsc, #noalsd').prop('disabled', false);

            $('#ocaa, #ocab').val('');
            $('#noalsa, #noals').val('');
            $('#ocaa1, #ocab1').val('');

            $('#gpinopc,#gpinopd').prop('disabled', false);
            $('#gpinopa, #gpinopb').prop('disabled', true);
            $('#gpc1, #gpd1').prop('disabled', false);
            $('#gpa1, #gpb1').prop('disabled', true);
            $('#noalsgpc, #noalsgpd').prop('disabled', false);
            $('#noalsgpa, #noalsgpb').prop('disabled', true);

            $('#gpinopa, #gpinopb').val('');
            $('#gpa1, #gpb1').val('');
            $('#noalsgpa, #noalsgpb').val('');

            $('#circ_c, #circ_d').prop('disabled', false);
            $('#circ_a,#circ_b').prop('disabled', true);
            $('#circ_d,#circ_b').val('');

            $('#circ_c_val, #circ_d_val').prop('disabled', false);
            $('#circ_a_val,#circ_b_val').prop('disabled', true);
            $('#circ_a_val,#circ_b_val').val('');

            break;
        case 'CAT C':
            gs=[100,120,130,140,150,160,180];
            $('#ocaa, #ocab, #ocad').prop('disabled', true);
            $('#ocac').prop('disabled', false);
            $('#ocaa1, #ocab1, #ocad1').prop('disabled', true);
            $('#ocac1').prop('disabled', false);
            $('#noalsa, #noalsb, #noalsd').prop('disabled', true);
            $('#noalsc').prop('disabled', false);
            $('#ocaa, #ocab, #ocad').val('');
            $('#ocaa1, #ocab1, #ocad1').val('');
            $('#noalsa, #noalsb, #noalsd').val('');

            $('#gpinopc').prop('disabled', false);
            $('#gpinopa, #gpinopb,#gpinopd').prop('disabled', true);
            $('#gpc1').prop('disabled', false);
            $('#gpa1, #gpb1, #gpd1').prop('disabled', true);
            $('#noalsgpc').prop('disabled', false);
            $('#noalsgpa, #noalsgpb, #noalsgpd').prop('disabled', true);

            $('#gpinopa, #gpinopb, #gpinopd').val('');
            $('#gpa1, #gpb1, #gpd1').val('');
            $('#noalsgpa, #noalsgpb, #noalsgpd').val('');

            $('#circ_c').prop('disabled', false);
            $('#circ_a,#circ_b, #circ_d').prop('disabled', true);
            $('#circ_d,#circ_b, #circ_d').val('');

            $('#circ_c_val').prop('disabled', false);
            $('#circ_a_val,#circ_b_val, #circ_d_val').prop('disabled', true);
            $('#circ_a_val,#circ_b_val, #circ_d_val').val('');

            break;
        case 'CAT D':
            gs=[100,120,130,140,150,160,180];
            $('#ocaa, #ocab, #ocac').prop('disabled', true);
            $('#ocad').prop('disabled', false);
            $('#ocaa1, #ocab1, #ocac1').prop('disabled', true);
            $('#ocad1').prop('disabled', false);
            $('#noalsa, #noalsb, #noalsc').prop('disabled', true);
            $('#noalsd').prop('disabled', false);
            $('#ocaa, #ocab, #ocac').val('');
            $('#ocaa1, #ocab1, #ocac1').val('');
            $('#noalsa, #noalsb, #noalsc').val('');

            $('#gpinopd').prop('disabled', false);
            $('#gpinopa, #gpinopb,#gpinopc').prop('disabled', true);
            $('#gpd1').prop('disabled', false);
            $('#gpa1, #gpb1, #gpc1').prop('disabled', true);
            $('#noalsgpd').prop('disabled', false);
            $('#noalsgpa, #noalsgpb, #noalsgpc').prop('disabled', true);

            $('#gpinopa, #gpinopb, #gpinopc').val('');
            $('#gpa1, #gpb1, #gpc1').val('');
            $('#noalsgpa, #noalsgpb, #noalsgpc').val('');

            $('#circ_d').prop('disabled', false);
            $('#circ_a,#circ_b, #circ_c').prop('disabled', true);
            $('#circ_d,#circ_b, #circ_c').val('');

            $('#circ_d_val').prop('disabled', false);
            $('#circ_a_val,#circ_b_val, #circ_c_val').prop('disabled', true);
            $('#circ_a_val,#circ_b_val, #circ_c_val').val('');

            break;
        
    }
    for (let index = 1; index < 8; index++) {
        $("#gs" + index).val(gs[index-1]);
        var rod=calculaterod("gs" + index);
        // $("#rod" + index).val(rod);
            
    }
}
function rodvalue(){
    for (let index = 1; index < 8; index++) {
      
        var rod=calculaterod("gs" + index);
        // $("#rod" + index).val(rod);
            
    }
}
function oca_visibility(id,circling=false){
    var oca= Number($("#" + id).val());
    var och=0;
    var dist_req=160;
    var slope=Number($("#descend").val());
    var tch=Number($("#rdh").val());
    var apll=Number($("#app_len").val());
    var thr_elev=Number($("#thr_elev").val());
    var ad_elev=Number($("#ad_elev").val());
    var ft=0.3048;
    if (slope==''){
        slope=2.9;
    }
    if (tch==''){
        tch=50;
    }
    if (apll==''){
        apll=0;
    }
    if (circling==true){
        slope=3.5;
        apll=0;
    }
    if (slope > 5){
        var radangle=slope/100;
    }else{
        var radangle=Math.tan(deg2rad(slope));

    }
    var ref_elev=ad_elev;
    if (circling==false){
        if ($("#precicion").val()=='T'){
            ref_elev=thr_elev;
        }else{
            if (Math.abs(ad_elev - thr_elev) > 7){
                ref_elev=thr_elev;
            }
        }
    }
    och=oca-ref_elev;


    var hasil=(dist_req + ((och-tch)/radangle * ft)-apll).toFixed();

    // console.log(mod_ratus,'mod_ratus',hasil)
    var mod_ratus=hasil % 100;
    hasil =Number(hasil)+ (100 - mod_ratus);
    var vis_no_als=hasil + apll;
        // console.log(id,hasil,dist_req ,oca, (och-ref_elev),och,ref_elev,radangle,apll,'visibility')
    if (hasil>5000){
        hasil=5000;
    }else if ( hasil < 800){
        hasil=800;
    }
    if (vis_no_als > 5000){
        vis_no_als=5000;
    }
    switch (id) {
        case 'ocaa':
            if (apll > 0){
                $("#ocaa1").val(hasil)
                $("#ocab1").val(hasil)
                $("#ocac1").val(hasil)
                $("#ocad1").val(hasil)
                $("#noalsa").val(vis_no_als)
                $("#noalsb").val(vis_no_als)
                $("#noalsc").val(vis_no_als)
                $("#noalsd").val(vis_no_als)
                
            }else{
                $("#ocaa1").val('')
                $("#ocab1").val('')
                $("#ocac1").val('')
                $("#ocad1").val('')
                $("#noalsa").val(vis_no_als)
                $("#noalsb").val(vis_no_als)
                $("#noalsc").val(vis_no_als)
                $("#noalsd").val(vis_no_als)
            }

            break;
        case 'ocab':
            if (apll > 0){
                $("#ocab1").val(hasil)
                $("#ocac1").val(hasil)
                $("#ocad1").val(hasil)
                $("#noalsb").val(vis_no_als)
                $("#noalsc").val(vis_no_als)
                $("#noalsd").val(vis_no_als)
                
            }else{
                $("#ocab1").val('')
                $("#ocac1").val('')
                $("#ocad1").val('')
                $("#noalsb").val(vis_no_als)
                $("#noalsc").val(vis_no_als)
                $("#noalsd").val(vis_no_als)
            }

            break;
        case 'ocac':
            if (apll > 0){
                $("#ocac1").val(hasil)
                $("#ocad1").val(hasil)
                $("#noalsc").val(vis_no_als)
                $("#noalsd").val(vis_no_als)
                
            }else{
                $("#ocac1").val('')
                $("#ocad1").val('')
                $("#noalsc").val(vis_no_als)
                $("#noalsd").val(vis_no_als)
            }
            break;
        case 'gpinopa':
            if (apll > 0){
                $("#gpa1").val(hasil)
                $("#gpb1").val(hasil)
                $("#gpc1").val(hasil)
                $("#gpd1").val(hasil)

                $("#noalsgpa").val(vis_no_als)
                $("#noalsgpb").val(vis_no_als)
                $("#noalsgpc").val(vis_no_als)
                $("#noalsgpd").val(vis_no_als)

            }else{
                $("#gpa1").val('')
                $("#gpb1").val('')
                $("#gpc1").val('')
                $("#gpd1").val('')

                $("#noalsgpa").val(vis_no_als)
                $("#noalsgpb").val(vis_no_als)
                $("#noalsgpc").val(vis_no_als)
                $("#noalsgpd").val(vis_no_als)
            }
            
            break;
        case 'gpinopb':
            if (apll > 0){
               
                $("#gpb1").val(hasil)
                $("#gpc1").val(hasil)
                $("#gpd1").val(hasil)

               
                $("#noalsgpb").val(vis_no_als)
                $("#noalsgpc").val(vis_no_als)
                $("#noalsgpd").val(vis_no_als)

            }else{
               
                $("#gpb1").val('')
                $("#gpc1").val('')
                $("#gpd1").val('')

                $("#noalsgpb").val(vis_no_als)
                $("#noalsgpc").val(vis_no_als)
                $("#noalsgpd").val(vis_no_als)
            }
            break;
        case 'gpinopc':
            if (apll > 0){
            $("#gpc1").val(hasil)
            $("#gpd1").val(hasil)
            
            $("#noalsgpc").val(vis_no_als)
            $("#noalsgpd").val(vis_no_als)

        }else{
            $("#gpc1").val('')
            $("#gpd1").val('')
            
            $("#noalsgpc").val(vis_no_als)
            $("#noalsgpd").val(vis_no_als)
        }
            break;
        case 'circ_a':

            $("#circ_a_val").val(vis_no_als)
            $("#circ_b_val").val(vis_no_als)
            $("#circ_c_val").val(vis_no_als)
            $("#circ_d_val").val(vis_no_als)

           
            break;
        case 'circ_b':
            $("#circ_b_val").val(vis_no_als)
            $("#circ_c_val").val(vis_no_als)
            $("#circ_d_val").val(vis_no_als)

            break;
        case 'circ_c':
            $("#circ_c_val").val(vis_no_als)
            $("#circ_d_val").val(vis_no_als)
            break;
    }

}
function calculaterod(id){
    var idx=id.substr(-1);
    // console.log(idx,'idx',id);
    var speed=Number($("#" + id).val());
    var distfaftothr=Number($("#faf_to_thr").val());
    var distfaftomapt=Number($("#dist").val());
    var desangle=Number($("#descend").val());
    if (desangle > 5){
        var radangle=desangle/100;
    }else{
        var radangle=Math.tan(deg2rad(desangle));

    }
    var distm=distfaftomapt * 1852/0.3048;
    var nmspeed=distfaftomapt/speed * 60;
    var rrod = distm * (radangle / nmspeed);
    var menit=Math.floor(nmspeed);
    var detik=((nmspeed-menit)*60).toFixed(0);
    var rodtime=menit+':'+numeral(detik).format('00');

        $("#rod" + idx).val(rrod.toFixed())
        $("#t" + idx).val(rodtime)
    // return rrod.toFixed();
}

function getminima(chartid){
//    console.log(arpt[0]);
// $("#gpinop").hide()
   var circ = ['circ_a','circ_b','circ_c','circ_d','circ_a_val','circ_b_val','circ_c_val','circ_d_val']
   if (arpt[0].tatl.length > 0){
    var ccr=arpt[0].tatl[0];
    // console.log(ccr)
        compareisidata(circ,ccr,ccr);
   }
    var fldmin = ['ocaa','ocab','ocac','ocad','gpinopa','gpinopb','gpinopc','gpinopd','gs1','gs2','gs3','gs4','gs5','gs6','ocaa1','ocab1','ocac1','ocad1','faf','mapt','app_type','app_len','deg','rdh','noted','precision','descend','gpa1','gpb1','gpc1','gpd1','dist_rem','noalsa','noalsb','noalsc','noalsd','noalsgpa','noalsgpb','noalsgpc','noalsgpd','rod1','rod2','rod3','rod4','rod5','rod6','dme1','dme2','dme3','dme4','dme5','dme6','dme_alt1','dme_alt2','dme_alt3','dme_alt4','dme_alt5','dme_alt6','gs7','rod7','dme7','dme_alt7','faf_alt','dist','faf_to_thr','precision'];rr=[];
    $.ajax({
        url: '../../api/chartminima',
        data: {chart_id:chartid},
        type: "json",
        method: "GET",

        success: function (result) {
            $.each(result.data, function (k, r) {
                // console.log(r,'minima');
                $("#app_len").val(r.app_len);
                $("#app_type").val(r.app_type);
                $("#nm_to").html(r.nm_to);
                $("#minid").val(r.id);
                compareisidata(fldmin,r,r);
                rodvalue();
            
                
            })
        }
    })
}
function getmsa(id){
    var dt='';
    if (id.substr(0,3)=='NAV'){
        dt={'nav_id':id}
    }else{
        dt={'arpt_ident':id}
    }
    $.ajax({
        url: '../../api/msa/list',
        data: dt,
        type: "json",
        method: "GET",

        success: function (result) {
            $.each(result.data, function (k, r) {
                // console.log(r);
                var hsl= '<option value="'+r.msa_id+'">'+r.ident+'</option>';
                $("#msa_id").append(hsl);
                
            })
        }
    })
}
    
function Airac(date,sel) {
    $("#publish_date").empty();
    var nnow = getIntervalInDays(date,56);
    // var hhr = date;
    // var hh1 = new Date(hhr)
    // hh1.setDate(hh1.getDate() + 56)
    // // const diffInMs = Math.abs( date1 + day );
    // console.log('AIRAC MAYOR ',hh1)

    var ndt = dateToJulianNumber(nnow);
    // console.log(date,nnow,ndt)
    var nom=0;
    var pathdetail= pathpop()   + '/api/airac';
        $.ajax({
                url: pathdetail,
                data: {'sort' : 'eff_date:asc'},
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        // console.log(v);
                        let dti;
                        if (sel=='major'){
                            var nnow = getIntervalInDays(date,56);
                            var ndt = dateToJulianNumber(nnow);
                            dti = dateToJulianNumber(new Date(v.maj_pub));
                        }else{
                            var nnow = getIntervalInDays(date,40);
                            var ndt = dateToJulianNumber(nnow);
                            dti = dateToJulianNumber(new Date(v.min_pub));
                        }
                        // console.log(dti,ndt)
                        if (dti > ndt){
                            nom++
                            if (nom < 5){
                                var air;
                                if (sel=='major'){
                                    air=DateFormat(new Date(v.maj_pub),false,true);
                                    // air=v.maj_pub;
                                }else{
                                    air=DateFormat(new Date(v.min_pub),false,true);
                                    // air=v.min_pub;
                                }
                                hasil= '<option key="'+v.id+'" value="'+air+'">'+air+'</option>';
                                $("#publish_date").append(hasil);
                                if (nom==1){
                                    getAirac()
                                }
                                // pubdate.push(air)
                            }
                            // console.log('INI YG DIAMBIL',pubdate)
                        }
                    })
                }
        })
    
}
function getAirac() {
    var dt = document.getElementById("publish_date").value;
    // $("#sourcenr").val('');
    var pathdetail= pathpop()   + '/api/airac',qry={};
    if (ismajor==true){
        qry ={'maj_pub': dt};
    }else{
        qry ={'min_pub': dt};
    }

   
    $.ajax({
            url: pathdetail,
            data: qry,
            type: "json",
            method: "GET",

            success: function (result) {
                $.each(result.data, function (k, tbl) {
                    // console.log(tbl);
                    // var today = new Date(tbl.eff_date);
                    // // console.log(new Date(tbl.eff_date))
                    // // var dd = String(today.getDate()).padStart(2, '0');
                    // // var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    // var yyyy = today.getFullYear().toString().substr( -2 );
                    air=DateFormat(new Date(tbl.eff_date),false,true);
                                    // air=v.min_pub;
                                
                                // hasil= '<option key="'+v.id+'" value="'+air+'">'+air+'</option>';
                    // // // today = dd + '/' + mm + '/' + yyyy;
                    // let src = document.getElementById("sourcenr").value;
                    // $("#sourcenr").val(src.substr(0,2) + '/' + yyyy);
                    $("#eff_date").val(air);
                    // effdate=tbl.eff_date
                })
        }
    })
    
}


if (arpt.length > 0){
    $("#titleholding").html(arpt[0].icao + ' ' + arpt[0].city_name + '/'+ arpt[0].arpt_name + ' Charts')
}
function showhideminima(){
    var nn=$("#nav").val();
    if (nn.includes('NDB') == true){
        $("#dist_alt").hide();
    }else{
        if ($("#dist_alt").is(':visible')==false){
            aboutvol('dist_alt');
        }
    }

}
function chartname(){
        // if (newdata == true){
        this.rwyt='';
        this.navc='';
        this.pgc='';
        var skillsSelect = document.getElementById("chart_type");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        //sel.options[sel.selectedIndex].text
        this.codec=selectedText.toUpperCase();

        var Chartcd=$("#chart_type").val();
        // console.log(Chartcd)
        
        if (Chartcd !== ''){
            var cdchart=cod.find(x=>x.id===Number(Chartcd)).code;

        }

    var dteff = ", Dated " +  $("#eff_date").val();
    if (Chartcd == ""){
        $("#chart_name").val('')
    }else{
        if ($("#rwy").val() !== null ){
            this.rwyt = " RWY " + $("#rwy").val()
            listproc(Chartcd);
        }
        if (Chartcd=='45'){
            if ($("#nav").val() !== null ){
                var nn=$("#nav").val().split(' - ');
                this.navc = ' ' + nn[0];
                showhideminima();
            }
            
        }
        if ($("#page").val() == '' || $("#page").val() == 'NIL' ){
        }else{
           
        this.pgc = $("#page").val().toUpperCase()
        var pgccoding=this.pgc.substr(0,1)+'2';
        }
        if (changechartname==true){

            $("#chart_name").val(arpt[0].icao + ' ' + cdchart + this.pgc + ', ' + this.codec +  this.navc + this.rwyt + dteff)
            $("#filenm").val(arpt[0].icao + ' ' + cdchart +pgccoding + ', ' + this.codec +  this.navc + this.rwyt + dteff)
        }

    }

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

function EditChart(){

    var btnch='<a onclick="changepubdat()" value="change" class="btn btn-dim btn-success"><em class="icon ni ni-exchange"></em> Change Publication Date</a>'
    $("#btn_changepubdate").html(btnch) 
    $("#status").val('R')
    $("#minstatus").val('R')
    htemp=charts[0];hcurr=charts[0];
    // console.log(htemp,'htemp')
    $("#id").val(htemp.id)
    $("#chart_id").val(htemp.chart_id)
    $("#minchart_id").val(htemp.chart_id)
    $("#chart_type").val(htemp.chart_type)
    $("#customer").val(htemp.customer)
    $("#footer").val(htemp.footer)
    
    var crn=[];tnm='';tid='';
    // console.log(htemp)
    $("#holdingedit").html('Chart Properties')
    
    // var flde=['bm_id','chart_name','chart_type','customer','footer','rwy','rnav','nav','seq','source','cat','page','msa_id','srcnum','remarks'];
    var flde=['bm_id','chart_name','chart_type','customer','footer','rwy','rnav','nav','seq','source','cat','page','msa_id','srcnum','sn'];
    compareisidata(flde,htemp,hcurr);
    settonullinput(flde)
    listfreq($("#chart_type").val())
    changechartcode()
    if (htemp.msa.length>0){
        $("#msa").val(htemp.msa[0].ident)
    }
    changechartname=true;
    oripubdate();
    
    // listproc(htemp.chart_type) 
    if (htemp.chart_type=='45'){
        attminima(htemp)
        getminima(htemp.chart_id)
       
        var fnav=htemp.nav.substr(0,3)
        $("#gpinop").hide();
        switch (fnav) {
            case 'ILS':
                if ($("#gpinop").is(':visible')==false){
                    aboutvol('gpinop');
                }
            $("#gpinoplbl").html('LOC only')
                break;
            case 'VOR':
            case 'NDB':
                $("#gpinop").hide();
                $("#straightin").html('Straihgt in')
                break;
            default:
                if ($("#gpinop").is(':visible')==false){
                    aboutvol('gpinop');
                }
                $("#straightin").html('LNAV/VNAV')
                $("#gpinoplbl").html('LNAV')
                
                break;
        }
        ocacategory();
        showhideminima()

    }
    if (htemp.source.length > 0){
        $("#source").val(htemp.source[0].src_type)
        $("#srcnum").val(htemp.source[0].src_id)
        effd=DateFormat(new Date(htemp.source[0].eff_date),false,true);
        pubd=DateFormat(new Date(htemp.source[0].pub_date),false,true);
        // console.log(effd,pubd,'htemp.source.length > 0)')
        $("#eff_date").val(effd)
        // $("#publish_date").val(pubd)
        // pubd=DateFormat(new Date(c.pub_date),false,true);
        hasil= '<option key="'+pubd+'" value="'+pubd+'">'+pubd+'</option>';
        $("#publish_date").append(hasil);
    }
    window.scroll(0,0);
}
function attminima(data){
    // console.log(data,'data attminima')
    $("#rwy_ident").val(data.rwy)
    $("#approach_type").val(data.nav)
    // console.log(rwylist)
    rwylist.forEach(r=>{
        var ix=r.physicals.findIndex(v=>v.rwy_ident===data.rwy)
        if (ix !== -1){
            $("#thr_elev").val(r.physicals[ix].thr_elev )
            $("#app_light").val(r.physicals[ix].lighting[0].apch_lgt_type_len)
            
        }
    })
    // var rr=arpt
}
function oripubdate(){
    if (htemp.aip.length >0){
        // changechartname=false;
        $("#page").val(htemp.aip[0].chart_page)
        $("#chart_name").val(htemp.aip[0].chart_name)
        $("#source").val(htemp.aip[0].source)
        $("#srcnum").val(htemp.aip[0].nr_yr)
        effd=DateFormat(new Date(htemp.aip[0].eff_date),false,true);
        pubd=DateFormat(new Date(htemp.aip[0].pub_date),false,true);
        if (htemp.aip[0].pub_date==null){
            pubd =effd;
        }
        $("#publish_date").val(pubd)
        $("#eff_date").val(effd)
            
        
    }else{
        // $("#source").val(htemp.source)
        // $("#srcnum").val(htemp.srcnum)
        effd=DateFormat(new Date(htemp.eff_date),false,true);
        pubd=DateFormat(new Date(htemp.publish_date),false,true);
        $("#publish_date").val(pubd)
        $("#eff_date").val(effd)
        
    }
    chartname();
}
 function changepubdat(){
    var sts=$("#btn_changepubdate").text().trim()
    var btnch='';
    if (sts=='Cancel'){
        oripubdate()
        btnch='<a onclick="changepubdat()" value="change" class="btn btn-dim btn-success"><em class="icon ni ni-exchange"></em> Change Publication Date</a>'
    }else{
        btnch='<a onclick="changepubdat()" value="cancel" class="btn btn-dim btn-primary"><em class="icon ni ni-reply-fill"></em> Cancel</a>'
        Publicationdate()
    }
   
  
    $("#btn_changepubdate").html(btnch) 
    chartname()
   
}

function Publicationdate(){

        if (rawdata.length >0){
            if (rawdata[0].source.length > 0){
                var hh=rawdata[0].source[0]
                $("#source").val(hh.src_type)
                $("#srcnum").val(hh.src_id)
                if(hh.src_id==null){
                    $("#srcnum").val('XX')
                }
                effd=DateFormat(new Date(hh.eff_date),false,true);
                pubd=DateFormat(new Date(hh.pub_date),false,true);
                hasil= '<option key="'+pubd+'" value="'+pubd+'">'+pubd+'</option>';
                $("#publish_date").append(hasil);
                                    
                // $("#publish_date").append(pubd)
                $("#eff_date").val(effd)
            }else{
                var dd = new Date();
                ismajor=true;
                Airac(dd,'major');
            
            }
                
        }else{
            var dd = new Date();
                ismajor=true;
                Airac(dd,'major');
        }
  
   
}


function listproc(charttype){
    if ($("#table-proc").is(':visible')==true){
        aboutvol('table-proc');
    }
    var rrwy=$("#rwy").val();
    // console.log(proc,rrwy,htemp)
        $("#proclist").empty();adaprocbaru=false;
        proc.forEach(p=>{
            if (p.chart_type==charttype){
                // console.log(p)
                var chk='';p_rwy=p.rwy;
                if (p_rwy== null){
                    p_rwy='';
                }
                if (htemp){
                    htemp.procedure.forEach(hp=>{
                        if (hp.proc_id===p.proc_id){
                            // console.log(hp.proc_id,'HPPPPPP')
                            // console.log(p.proc_id,'PROOOOOOOO')
                            chk='checked';
                        }
                    })
                    
                }
                var showp=true;
                // if (p.rwy==rrwy || p.rwy==null){

                    if ($("#status").val()=='N'){
                        charts.forEach(c=>{
                            if (c.procedure.length >0){
                                // showp=true;
                                var pp=c.procedure
                                // console.log(pp,'c.procedure',p.proc_id)
                                var ix=pp.findIndex(x=>x.proc_id===p.proc_id)
                                if (ix !== -1){
                                    showp=false;
                                }
                                // console.log(pp,ix,'lissssssss',p.proc_id)

                            }
                        })
                        // var cari=chart
                    }
                    if (showp ==true){
                        adaprocbaru=true
                        if ($("#table-proc").is(':visible')==false){
                                aboutvol('table-proc');
                        }
                        var  hsl='<tr>'+
                            '<td class="active">'+
                                '<input type="checkbox" class="select-item checkbox" onclick="getproc()" name="select-item" value="'+p.proc_id+'"'+chk+'/>'+
                            '</td>'+
                            '</td>'+
                            '<td>'+ p.proc_name +'</td>'+
                            '<td>'+ p_rwy +'</td>'+
                            '<td>'+ p.proc_text +'</td>'+
                        '</tr>';
                        $("#proclist").append(hsl);

                    }

                // }
            }
        })
        if ($("#status").val()=='N' &&  adaprocbaru ==false){
            Swal.fire(
                'No Procedure!',
                'All procedures have been used in the chart !!!',
                'info'
            )
            location.reload()
        }
}
function getproc(){
    var idchart=$("#chart_type").val();
    if (idchart=='45'){
        var procid= $('input[name="select-item"]:checked').val()
        if (typeof procid !== 'undefined'){
            var iix=proc.findIndex(x=>x.proc_id===procid)
            var segp=proc[iix].segment;wpt_mapt=[];recdnav=[];recdils=[];faf_dist=0;mapt_dist=1;
            segp.forEach(p=>{
                // console.log(p.rt_type,'p.rt_type')
                if (p.rt_type !=='Z'){
                    if (p.rt_type == 'N'){
                        $("#dist_alt").hide();
                    }else{
                        if ($("#dist_alt").is(':visible')==false){
                            aboutvol('dist_alt');
                        }
                    }
                    if (p.rt_type == 'R'){
                        $("#rnav").val('Y')
                        if ($("#codingtableid").is(':visible')==false){
                            aboutvol('codingtableid');
                        }
                    }else{
                        $("#rnav").val('N')
                        $("#codingtableid").hide();
                    }
                    var tr=p.transition[0].segment;faftomap=0;maptid='';p_faf='';
                    // console.log(p.transition[0],'p.transition[0]')
                    var rttype=p.transition[0].rt_type;
                    $("#precicion").val('F');
                    showhideminima();
                    tr.forEach(t=>{
                        // console.log(t,rttype)
                        switch (t.wd4) {
                            case 'F': 
                            case 'I': 
                                $("#faf_alt").val(t.alt1)
                                if (rttype=='R'){
                                    p_faf= t.waypoint[0].wpt_name + ' to '
                                }else{
                                    p_faf='FAF to MAPt'
                                    recdnav=t.recdnav1;
                                    faf_dist=Number(t.rho);
                                }
                                faftomap=0;
                                break;
                            case 'E':
                                $("#faf_alt").val(t.alt1)
                                faftomap=0;
                                recdils=t.recdils1;
                                recdnav=t.recdnav1;
                                faf_dist=Number(t.rho);
                                p_faf='FAP/FAF to MAPt'
                                $("#precicion").val('T')
                                break;
                            case 'M':
                                $("#rdh").val(t.tch)
                                $("#descend").val(t.vert_angle)
                                $("#ocaa").val(t.alt1)
                                ocavalue("ocaa");
                                maptid=t.fix_id
                                faftomap += Number(t.rt_dist_from);
                                wpt_mapt= getmappoint(t)
                                // wpt_mapt=t.waypoint;
                                mapt_dist=Number(t.rho);
                                break;
                            case 'S':
                                faftomap += Number(t.rt_dist_from);
                                
                                break;
                        }
                        

                    })
                    $("#dist").val(faftomap.toFixed(1));disttothr=1;
                    if (maptid.substr(0,3)=='RWY'){
                        $("#dist_rem").val(p_faf += ' RWY' + $("#rwy_ident").val() + ' : ' + $("#dist").val() + ' NM ; MAPt at THR RWY ' + $("#rwy_ident").val() )
                        $("#faf_to_thr").val(faftomap)
                    }else{
                        var rrw=$("#rwy_ident").val();
                        arpt[0].runwaystemp.forEach(r=>{
                            if (r.thr_high == rrw || r.thr_low == rrw ){
                                var ic= r.physicals.findIndex(r=>r.rwy_ident===rrw)
                                var ttrk = Getbearing( wpt_mapt[0].geom.coordinates[ 1 ],wpt_mapt[0].geom.coordinates[ 0 ], r.physicals[ic].geom.coordinates[ 1 ], r.physicals[ic].geom.coordinates[ 0 ] )
                                disttothr=ttrk.DistanceReal;
                                if (isNaN(ttrk.DistanceReal)){
                                    disttothr=0;
                                    // console.log(wpt_mapt[0].geom,'ttrk',r.physicals[ic].geom,ttrk,disttothr,faftomap)
                                }
                                faftomap +=disttothr
                                $("#faf_to_thr").val(faftomap.toFixed(1))
                                if (rttype=='R'){
                                    $("#dist_rem").val(p_faf +=  wpt_mapt[0].wpt_name + ' : ' + $("#dist").val() + ' NM');
                                }else{
                                    $("#dist_rem").val(p_faf += ' : ' + $("#dist").val() + ' NM');

                                }
                                // console.log(r,'rwy_ident',wpt_mapt)
                            }
                        // rwylist.push(r);
                       
                        })
                    }
                    // console.log( faftomap,' faftomap',Math.floor(faftomap),disttothr.toFixed())
                    rodvalue();
                    var s_alt=Math.floor(faf_dist);e_alt=mapt_dist.toFixed();dno=1;
                    if (rttype=='R'){
                        s_alt=faftomap.toFixed()-1;e_alt=disttothr.toFixed()
                        $("#nm_to").html('NM to THR RWY ' + $("#rwy_ident").val())
                    }else if (rttype=='I'){
                        if (recdils.length > 0){
                            $("#nm_to").html('NM to ' + recdils[0].ils_ident + ' DME')

                        }else{
                            $("#nm_to").html('NM to ' + recdnav[0].nav_ident + ' VOR/DME')
                        }
                    }else{
                        $("#nm_to").html('NM to ' + recdnav[0].nav_ident + ' VOR/DME')
                    }
                    for (let index =1; index < 8; index++) {
                        $("#dme" + index).val('')
                        $("#dme_alt" + index).val('')
                        
                    }
                    for (let index = s_alt; index > e_alt; index--) {
                        // console.log(s_alt,e_alt,'e_alt',index)
                        $("#dme" + dno).val(index)
                        var rod=calculatedmealtitude("dme" + dno,faf_dist,rttype);
                        dno++;
                    }
                    // console.log(procid,proc[iix].segment,p.transition[0].segment,faftomap);

                }
            })

        }

    }

}
function getmappoint(proc){
    if (proc.waypoint.length > 0){
        return proc.waypoint
    }else if (proc.arpt.length > 0){
        return proc.arpt
    }else if (proc.marker.length > 0){
        return proc.marker
    }else if (proc.navaid.length > 0){
        return proc.navaid
    }
    

}
function calculatedmealtitude(id,fafdist,rttype){
    var idx=id.substr(-1);
    // console.log(idx,'idx',id);
    var speed=Number($("#" + id).val());
    var distfaftothr=Number($("#faf_to_thr").val());
    var distfaftomapt=Number($("#dist").val());
    var desangle=Number($("#descend").val());
    if (desangle > 5){
        var radangle=desangle/100;
    }else{
        var radangle=Math.tan(deg2rad(desangle));

    }
    var distm=distfaftomapt * 1852/0.3048;
    var nmspeed=distfaftomapt/speed * 60;
    var rrod = distm * (radangle / nmspeed);
    // console.log(distm,distfaftomapt,nmspeed,radangle,deg2rad(desangle),'distfaftomapt')

    var hslforAlt=Number(distfaftothr/speed) * 60;
    var hasilmenit=Number(distfaftomapt / speed) * 60;
    var menit=Math.floor(hasilmenit);
    var detik=((hasilmenit-menit)*60).toFixed(0);
    var rodtime=menit+':'+numeral(detik).format('00');
    // console.log(hasilmenit,menit,detik,'MENITTT')
    // console.log((distfaftothr/speed) * 60,hslforAlt)
    var altfaf=Number($("#faf_alt").val());thre=Number($("#thr_elev").val());rdh=Number($("#rdh").val());
    // var krng=Number(altfaf - thre - rdh) 
    // rrod = Number(krng / hslforAlt)
    // console.log(rrod,altfaf,thre,rdh,krng)
   
    // console.log(idx,'idx',id);
        if (rttype=='R'){
            var dme_a=speed * 1852/0.3048;
            var dme_alt=dme_a * radangle + thre + rdh;
            // console.log(dme_a,speed,'speed DME',dme_alt)
            
        }else{
            var dme_a=(fafdist - Math.floor(fafdist)) * 1852/0.3048 ;
            var dme1=((1 * 1852/0.3048) * radangle);
            var dme_alt=altfaf-(dme_a * radangle);
            if (idx>1){
            //     dme_alt -=dme1;
            // }else{
                var lidx=Number(idx-1);
                dme_alt=Number($("#dme_alt" + lidx).val())-dme1
                // console.log(dme_a,speed,'speed DME',dme_alt,dme1,$("#dme_alt" + lidx).val())
            }
        }
        $("#dme_alt" + idx).val(dme_alt.toFixed())
    
    // return rrod.toFixed();
}
function updateminima(){
    $("#minimaform").submit();
}
function updateholding(){
    var listchart=[];listfreq=[];
    $("input.select-item").each(function (index,item) {
        if (item.checked==true){
            listchart.push(item.value)
            console.log(index,item)

        }
                // item.checked = checked;
    });
    $("input.select-item-freq").each(function (index,item) {
        if (item.checked==true){
            listfreq.push(item.value)

        }
                // item.checked = checked;
    });
    $("#listproc").val(listchart);
    $("#listfreq").val(listfreq);

    if ($("#status").val()=='N'){
        var today = new Date();
        var date = today.getFullYear() +'_'+ (today.getMonth()+1) +'_'+ today.getDate();
        date += '_'+ today.getHours() +'_'+ today.getMinutes() +'_'+ today.getSeconds();
        // console.log(date);
        var chtnewid=arpt[0].arpt_ident + '_' +  $("#chart_type").val() + '_' +  date;
        $("#chart_id").val(chtnewid)
    }
    var fld=['chart_id','cat','page'];
    setinputtoupper(fld)
    $("#holdingremove").submit();
}
function NewData(){
   
    if ($("#table-proc").is(':visible')==true){
        aboutvol('table-proc');
    }
    if ($("#minimaid").is(':visible')==true){
        aboutvol('minimaid');
    }
    if ($("#codingtableid").is(':visible')==true){
        aboutvol('codingtableid');
    }
    $("#proclist").empty()
    $("#holdingedit").html(arpt[0].icao + ' ' + arpt[0].arpt_name +' New Chart')
    $("#status").val('N')
    $("#rnav").val('Y');
    htemp=null;
    var flde=['bm_id','chart_name','chart_type','rwy','rnav','nav','source','cat','page','msa_id','srcnum','remarks'];
    clearinput(flde);
    Publicationdate()
    
}
function isback(){
    window.location.href = '/chartprop/' + arpt[0].arpt_ident + '/pro' ;
    // if (tbl=='pro'){
    //     aboutvol("dataholdingdetail");aboutvol("dataholding");

    // }else{
    //     aboutvol("bmdetail");aboutvol("dataholding");
    // }
}


function backtomenu(){
    window.location.href = '/listairport/chartprop';
}
function remove(id){
    // console.log(id)
    $("#id").val(id) 
    $("#status").val('D')
    $("#chart_arpt_ident").val(arpt[0].arpt_ident)
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
            $("#holdingremove").submit();
        }else{
            location.reload();
        }
    })
}
function viewframe(id=null){
    var idcht='';
    if (id==null){
        var chrtid=$('#bm_id').val();
        idcht=bm.find(x=>x.chart_id===chrtid).id;

    }else{
        idcht=id;
    }
    console.log(idcht)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=frame&id='+idcht, 'Set Latitude and Longitude', params)
}

function chartselectedattr(cid){
    var drwy =  document.getElementById("rwy"); 
    var dnav =   document.getElementById("nav"); 
    var dcat =  document.getElementById("cat"); 
    var dmsa =  document.getElementById("msa_id");
    var drnav =  document.getElementById("rnav");
    var newcate= [];
    var refid= $("#refid");//document.getElementById("refid");
    var catid=$("#catid");//document.getElementById("catid");
    // listcate(cate);
    // listnav()
    var nhsl= '<option value="VOR and DME Required">VOR and DME Required</option>';

    var textproc='';
    // if ($("#rnav").val()=='Y'){
    //     textproc='RNP ';
    //     nhsl= '<option value="RNP 1">RNP 1</option>';
    // }
    if ($("#rwy").val() !== null){
        textproc += 'RWY ' + $("#rwy").val();
    }
    // console.log($("#rnav").val(),$("#rwy").val())
    refid.html('Reference')
    catid.html('Category')
    // $("#refid").val('Reference');
    // $("#caeid").val('Reference');
    drwy.disabled=false;
    dnav.disabled=false;
    dcat.disabled=false;
    dmsa.disabled=false;
    drnav.disabled=false;
    switch (cid) {
        case '10':
        case '62':
        case '63':
            drwy.disabled=true;
            dnav.disabled=true;
            dcat.disabled=true;
            dmsa.disabled=true;
            drnav.disabled=true;
            break;
        case '60':
            dnav.disabled=true;
            dcat.disabled=true;
            dmsa.disabled=true;
            break;
        case '45':
            refid.html('NAV/ILS Ref.')
            catid.html('ACFT Cat.')
            break;
        case '46':
            catid.html('Proc. Name')
            newcate= [{
                id: '1',
                value: textproc +' DEPARTURE'
            }]
            // $("#nav").empty();
            // $("#nav").append(nhsl);
            listcate(newcate);
            break;
        case '47':
            newcate= [{
                id: '1',
                value: textproc +' ARRIVAL'
            }]
            listcate(newcate);
            // $("#nav").empty();
            // $("#nav").append(nhsl);
            catid.html('Proc. Name')
            break;
        default:
            break;
    }
    
}
function listfreq(cht){
    no=1;$("#freqlist").empty();atype='';cllsgn='X';shownumber=true;
    // console.log(freq,arptfreq)
    freq.forEach(p=>{
        // console.log(p)
        if (p.chart_types == cht){
            var afreq=p.freq;acall=p.call_sign;tpy=p.types;
            if (p.status =="Secondary"){
                afreq += ' (SRY)'
            }
            if (p.sector !==''){
                if (p.sector !==null){
                    if (p.sector !== 'NIL'){
                        acall += ' (' + p.sector+')';
                    }
                }
            }
            shownumber=true;
            if (cllsgn == acall){
                shownumber=false;
                acall='';
                if (atype == tpy){
                    tpy='';
                }
            }
            if (tpy=='ATI'){
                tpy='ATIS'
            }
            var  chk='checked';
                // arptfreq.forEach(hp=>{
                //     if (hp.frequsedid===p.frequsedid){
                //         // console.log(hp.proc_id,'HPPPPPP')
                //         // console.log(p.proc_id,'PROOOOOOOO')
                //         chk='checked';
                //     }
                    
                // })
                // console.log(shownumber,'shownumber')
            
                if (shownumber==true){
                    var  hsl='<tr>'+
                    '<td class="active">'+
                        '<input type="checkbox" class="select-item-freq checkbox" id='+ p.frequsedid +' onclick="addfreq(this.id)" name="select-item-freq" value="'+p.frequsedid+'"'+chk+'/>'+
                    '<td class="tb-tnx-action">'+
                    '<div class="dropdown">'+
                        '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                        '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                            '<ul class="link-list-plain">'+
                                '<a class="btn btn-dim btn-primary col-md-12" id='+ p.id +' onclick="editseq(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit Sequence</a>'+
                            '</ul>'+
                        '</div>'+
                    '</div>'+
                '</td>'+
                    '<td>'+ no +'</td>';
                        no++
                }else{
                        var  hsl='<tr>'+
                        '<td class="active">'+
                        '</td>'+
                        '<td></td>'+
                        '<td></td>';
                    
                }
                    hsl += '<td>'+ tpy +'</td>'+
                    '<td>'+ acall +'</td>'+
                    '<td>'+ afreq +'</td>'+
                '</tr>';
                $("#freqlist").append(hsl);
            
            cllsgn = acall;
            atype=p.types;
        }
    })
    atype='';cllsgn='';
    arptfreq.forEach(p=>{
        // console.log(p,'asdasd')
            var afreq=p.freq;acall=p.call_sign;tpy=p.types;
            if (p.status =="Secondary"){
                afreq += ' (SRY)'
            }
            if (p.sector !==''){
                if (p.sector !==null){
                    acall += ' (' + p.sector+')'
                }
            }
            shownumber=true;
            if (cllsgn == acall){
                shownumber=false;
                acall='';
                if (atype == tpy){
                    tpy='';
                }
            }
            if (tpy=='ATI'){
                tpy='ATIS'
            }
            var  chk='';show=true;
            var ix=freq.findIndex(x=>x.frequsedid===p.frequsedid && x.chart_types===cht)
            if (ix !== -1){
                show=false;
            }
                
            if (show==true){
                if (shownumber==true){
                    var  hsl='<tr>'+
                    '<td class="active">'+
                        '<input type="checkbox" class="select-item-freq checkbox" id='+ p.frequsedid +' onclick="addfreq(this.id)" name="select-item-freq" value="'+p.frequsedid+'"'+chk+'/>'+
                    '</td>'+
                    '<td></td>'+
                    '<td>'+ no +'</td>';
                        no++
                }else{
                        var  hsl='<tr>'+
                        '<td class="active">'+
                        '</td>'+
                        '<td></td>'+
                        '<td></td>';
                    
                }
                    hsl += '<td>'+ tpy +'</td>'+
                    '<td>'+ acall +'</td>'+
                    '<td>'+ afreq +'</td>'+
                '</tr>';
                $("#freqlist").append(hsl);

            }
            
            cllsgn = acall;
            atype=p.types;
        
    })
}
function addfreq(id){
$("input.select-item-freq").each(function (index,item) {
        if (item.checked==true){
            console.log(item.value,item,'CHECK')
            // listchart.push(item.value)

        }else{
            console.log(item.value,item,'NO CHECK')
        }
                // item.checked = checked;
    });
}
function editseq(id){
    var idx = freq.findIndex(x => x.id===Number(id));
    // var inputOptions =freq[idx].seq; // Define like this!
    console.log(freq[idx],id)
    $("#change_id").val(id);
    $("#change_arpt_ident").val(arpt[0].arpt_ident);
    $("#change_freqid").val(freq[idx].frequsedid);

    Swal.fire({
        title: "Change Sequence",
        text : "Old Sequence : " + freq[idx].seq + ' Changed to New Sequence',
        input: 'number',
        inputOptions: inputOptions,
        showCancelButton: true,
    }).then((result) => {
        if (result.value){

            $("#change_seq").val(result.value); // console.log(this.frequpdate,result.value)
            
            $("#changeseq_form").submit();
                
            
        }

    });
}
function changerwy(){
    var rrw=$("#rwy").val();
    $("#rwy_ident").val(rrw);
    arpt[0].runwaystemp.forEach(r=>{
        if (r.thr_high == rrw || r.thr_low == rrw ){
            var ic= r.physicals.findIndex(r=>r.rwy_ident===rrw)
            $("#thr_elev").val( r.physicals[ic].thr_elev);
            if (r.physicals[ic].lighting.length > 0){
                var lgt= r.physicals[ic].lighting[0].apch_lgt_type_len
                if (lgt=='' || lgt=='NIL'){
                    $("#app_light").val('NIL');
                    $("#app_type").val('NIL');
                    $("#app_len").val(0);
                }else{
                    var lgt_pals=lgt.includes('PALS');
                    var lgt_mals=lgt.includes('MALS');
                    if (lgt_pals==true){
                        $("#app_type").val('PALS');
                        $("#app_len").val(900);
                    }else if (lgt_mals==true){
                        $("#app_type").val('MALS');
                        $("#app_len").val(420);
                    }else{
                        if (r.length >= 2500){
                            $("#app_type").val('PALS');
                            $("#app_len").val(900);

                        }else{
                            $("#app_type").val('MALS');
                            $("#app_len").val(420);
                        }
                    }
                    console.log(lgt_pals,lgt_mals,'lgt_pals lgt_mals')
                    $("#app_light").val(lgt);
                }
            }else{
                $("#app_light").val('NIL');
                $("#app_type").val('NIL');
                $("#app_len").val(0);
            }
           
            // console.log(r,'rwy_ident')
        }
    // rwylist.push(r);
    
    })
  
    
}
function changechartcode(){
    var idc=$("#chart_type").val();
    // listcate(cate);
  
    chartname();
    listproc(idc);
    chartselectedattr(idc);
   
    aboutvol('minimaid');
    // console.log('changechartcode',idc)
    if (idc=='45'){
        // listnav()
        if ($("#minimaid").is(':visible')==false){
        aboutvol('minimaid');
        }
    }else{
        $("#minimaid").hide();
    }
    var rnav=$("#rnav").val();
    // console.log('changechartcode',rnav)
    if (rnav=='Y'){
        if ($("#codingtableid").is(':visible')==false){
        aboutvol('codingtableid');
        }
    }else{
        $("#codingtableid").hide();
    }
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
       
        $("#select-freq").click(function () {
            var all = $("input.select-freq")[0];
            all.checked = !all.checked
            var checked = all.checked;
            $("input.select-item-freq").each(function (index,item) {
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
        $("input.select-freq").click(function () {
            var checked = this.checked;
            $("input.select-item-freq").each(function (index,item) {
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
        $("input.select-item-freq").click(function () {
            var checked = this.checked;
            var all = $("input.select-freq")[0];
            var total = $("input.select-item-freq").length;
            var len = $("input.select-item-freq:checked:checked").length;
            all.checked = len===total;
        });
        
});
function getsourcenr(){
    $("#publish_date").empty();
    var srcid=$("#srcnum").val();
    $.ajax({
        url: '../../api/sourcenr',
        type: 'get',
        data:{src_id:srcid},
        success: response => {
            response.data.forEach(c => {
                effd=DateFormat(new Date(c.eff_date),false,true);
                pubd=DateFormat(new Date(c.pub_date),false,true);
                hasil= '<option key="'+pubd+'" value="'+pubd+'">'+pubd+'</option>';
                $("#publish_date").append(hasil);
                $("#publish_date").val(pubd);
                $("#eff_date").val(effd);
                $("#source").val(c.src_type);
                $("#sn").val(c.id);
                chartname()
            
            })
            // console.log(datachart)
            // if (datachart !== null){
               
            // }
        }
    }); 
    sourcechange();
}

</script>
@endsection