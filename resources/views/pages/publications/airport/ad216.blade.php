@extends('layouts.app')

@section('template_title')
    AD 2.16
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
                        <strong>CoordinatesTLOF or THR of FATO</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad89" name="ad89" v-bind:style="{'color': ad2171color}" v-show="ad2171color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>Geoid undulation </strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad219" name="ad219" v-bind:style="{'color': ad2172color}" v-show="ad2172color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>TLOF and/or FATO elevation M/FT</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad90" name="ad90" v-bind:style="{'color': ad2173color}" v-show="ad2173color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>TLOF and FATO area dimensions, surface,strength, marking</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad91" name="ad91" v-bind:style="{'color': ad2174color}" v-show="ad2174color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>True BRG of FATO</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad92" name="ad92" v-bind:style="{'color': ad2175color}" v-show="ad2175color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>Declared distance available</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad93" name="ad93" v-bind:style="{'color': ad2176color}" v-show="ad2176color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>APP and FATO lighting</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad94" name="ad94" v-bind:style="{'color': ad2177color}" v-show="ad2177color"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>Remarks</strong>
                        <br>
                        <textarea type="text" class="form-control" id="ad95" name="ad95" v-bind:style="{'color': ad2178color}" v-show="ad2178color"></textarea>
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
var ttl=arp.icao + ' AD 2.16 HELICOPTER LANDING AREA';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
var cord=SetCoordinatebyGeom(arp.geom)
$("#contentitle").html(ttl);
$("#lon1").html(cord.WGS[0]);
// console.log(a);
// console.log(contents);
var fields = [89, 219, 90, 91, 92, 93, 94, 95];
var values = ["ad89", "ad219", "ad90", "ad91", "ad92", "ad93", "ad94", "ad95"];
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