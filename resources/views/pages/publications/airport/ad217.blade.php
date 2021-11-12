@extends('layouts.app')

@section('template_title')
    AD 2.17 
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
                    <div class="col-md-12">
                        <strong>Designation and lateral limits </strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad96" name="ad96" v-bind:style="{'color': ad2171color}" v-show="ad2171color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>Vertical limits</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad220" name="ad220" v-bind:style="{'color': ad2172color}" v-show="ad2172color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>ATS unit call sign</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad222" name="ad222" v-bind:style="{'color': ad2174color}" v-show="ad2174color"></textarea>
                    </div>
                    <div class="col-md-3">
                        <strong>Airspace classification</strong>
                        <br>
                        <input type="text" class="form-control" id="ad221" name="ad221" v-bind:style="{'color': ad2173color}" v-show="ad2173color">
                    </div>
                    <div class="col-md-3">
                        <strong>Language(s)</strong>
                        <br>
                        <input type="text" class="form-control" id="ad223" name="ad223" v-bind:style="{'color': ad2175color}" v-show="ad2175color">
                    </div>
                    <div class="col-md-3">
                        <strong>Transition altitude</strong>
                        <br>
                        <input type="text" class="form-control" id="ad224" name="ad224" v-bind:style="{'color': ad2176color}" v-show="ad2176color">
                    </div>
                    <div class="col-md-3">
                        <strong>Hours of applicability</strong>
                        <br>
                        <input type="text" class="form-control" id="ad236" name="ad236" v-bind:style="{'color': ad2178color}" v-show="ad2178color">
                    </div>
                    <div class="col-md-12">
                        <strong>Remarks</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad225"  name="ad225" v-bind:style="{'color': ad2177color}" v-show="ad2177color"></textarea>
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
var ttl=arp.icao + ' AD 2.17 ATS AIRSPACE';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
var cord=SetCoordinatebyGeom(arp.geom)
$("#contentitle").html(ttl);
$("#lon1").html(cord.WGS[0]);

// console.log(a);
// console.log(contents);
var fields = [96, 220, 221, 222, 223, 224,236, 225];
var values = ["ad96", "ad220", "ad221", "ad222", "ad223", "ad224", "ad236","ad225"];
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
    window.location.href="{{url('/')}}/edit217/"+ arp.arpt_ident;
}

</script>
@endsection