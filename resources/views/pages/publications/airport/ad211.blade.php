@extends('layouts.app')

@section('template_title')
    AD 2.11
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
    <div class="panel-heading">
            <h6 class="panel-title" id="contentitle"></h6>
        </div>
        <div class="panel-body mt-3">
        <form action="" method="POST" id="formulir">
                @csrf
            <div class="row">
                <div class="col-md-6">
                    <strong>Associated MET Office</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2171" name="ad70"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Hours of service</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2172" name="ad71"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>MET Office outside hours</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2173" name="ad213"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Office responsible for TAF preparation</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2174" name="ad72"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Periods of validity</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2175" name="ad214"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Trend forecast</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2176" name="ad73"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Interval of issuance</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2177" name="ad215"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Briefing/consultation provided</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2178" name="ad74"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Flight documentation</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad2179" name="ad75"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Language(s) used</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad21710" name="ad216"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Charts and other information available for briefing or consultation</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad21711" name="ad76"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Supplementary equipment available for providing information</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad21712" name="ad77"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>ATS units provided with information</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad21713" name="ad78"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Additional information (limitation of service,etc.)</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad21714" name="ad79"></textarea>
                </div>
            </div>
            </form>
            <div class="card-inner">
                <div class="row">
                    <div class="col-md-6">
                        <button onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                        &nbsp;
                       
                        <button id="btn_formulir" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Update</button>
                        
                    </div>
                    <div class="col-md-6" align="right">
                        <i style="color:red" align="right">RED Color = Data change request</i>
                        <br>
                        <i style="color:darkgrey" id="arptidname"></i>
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

var arpt =@json($airport);arp=arpt[0];
var contents =@json($content);
var ttl=arp.icao + ' AD 2.11 METEOROLOGICAL INFORMATION PROVIDED';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
var cord=SetCoordinatebyGeom(arp.geom)
$("#contentitle").html(ttl);
$("#lon1").html(cord.WGS[0]);
// console.log(a);
// console.log(contents);
var fields = [70, 71, 213, 72, 214, 73, 215, 74, 75, 216, 76, 77, 78, 79];
var values = ["ad2171", "ad2172", "ad2173", "ad2174", "ad2175", "ad2176", "ad2177", "ad2178", "ad2179", "ad21710", "ad21711", "ad21712", "ad21713", "ad21714"];
for (let i=0;i<fields.length;i++){
        idx= contents.findIndex( x => x.category_id === fields[i] );
        if (idx==-1){
            var isi='';
            var sts='U';
        }else{
            var sts=contents[idx].status;
            var isi =checkisicontain(contents[idx].content);
        }
        isidata(values[i] ,sts);
        // console.log(isi,i);
        $("#" + values[i] ).val(isi);
    
}

$('#btn_formulir').click(function() {
    $('#formulir').submit();
});

function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection