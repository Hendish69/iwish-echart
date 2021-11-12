@extends('layouts.app')

@section('template_title')
    AD 2.15 
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
                    <strong>ABN/IBN location, characteristics and hours of operation</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad83" name="ad83" v-bind:style="{'color': ad2171color}" v-show="ad2171color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>hours of operation</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad217" name="ad217" v-bind:style="{'color': ad2175color}" v-show="ad2175color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>LDI location and LGT</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad84" name="ad84" v-bind:style="{'color': ad2172color}" v-show="ad2172color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Anemometer location and LGT</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad218" name="ad218" v-bind:style="{'color': ad2176color}" v-show="ad2176color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>TWY edge and centre line lighting</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad86" name="ad86" v-bind:style="{'color': ad2173color}" v-show="ad2173color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Secondary power supply/switch-over time</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad87" name="ad87" v-bind:style="{'color': ad2174color}" v-show="ad2174color"></textarea>
                </div>
                <div class="col-md-12">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad88" name="ad88" v-bind:style="{'color': ad2177color}" v-show="ad2177color"></textarea>
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
var ttl=arp.icao + ' AD 2.15 OTHER LIGHTING, SECONDARY POWER SUPPLY';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
var cord=SetCoordinatebyGeom(arp.geom)
$("#contentitle").html(ttl);
$("#lon1").html(cord.WGS[0]);
// console.log(a);
// console.log(contents);
var fields = [83,217,84,218,86,87,88];
var values = ["ad83", "ad217", "ad84", "ad218", "ad86", "ad87", "ad88"];
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