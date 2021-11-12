@extends('layouts.app')

@section('template_title')
    OPERATIONAL HOURS
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
                    <strong>Aerodrome operator</strong>
                    <br>
                    <input type="text" class="form-control" id="ad15" name="ad15" v-bind:style="{'color': ad23color15}" v-show="ad23color15">
                </div>
                <div class="col-md-6">
                    <strong>Customs and immigration</strong>
                    <br>
                    <input type="text" class="form-control" id="ad16" name="ad16" v-bind:style="{'color': ad23color16}" v-show="ad23color16">
                </div>
                <div class="col-md-6">
                    <strong>Health and sanitation</strong>
                    <br>
                    <input type="text" class="form-control" id="ad17" name="ad17" v-bind:style="{'color': ad23color17}" v-show="ad23color17">
                </div>
                <div class="col-md-6">
                    <strong>AIS Briefing Office</strong>
                    <br>
                    <input type="text" class="form-control" id="ad18" name="ad18" v-bind:style="{'color': ad23color18}" v-show="ad23color18">
                </div>
                <div class="col-md-6">
                    <strong>ATS Reporting Office (ARO)</strong>
                    <br>
                    <input type="text" class="form-control" id="ad19" name="ad19" v-bind:style="{'color': ad23color19}" v-show="ad23color19">
                </div>
                <div class="col-md-6">
                    <strong>MET Briefing Office</strong>
                    <br>
                    <input type="text" class="form-control" id="ad20" name="ad20" v-bind:style="{'color': ad23color20}" v-show="ad23color20">
                </div>
                <div class="col-md-6">
                    <strong>ATS</strong>
                    <br>
                    <input type="text" class="form-control" id="ad21" name="ad21" v-bind:style="{'color': ad23color21}" v-show="ad23color21">
                </div>
                <div class="col-md-6">
                    <strong>Fuelling</strong>
                    <br>
                    <input type="text" class="form-control" id="ad22" name="ad22" v-bind:style="{'color': ad23color22}" v-show="ad23color22">
                </div>
                <div class="col-md-4">
                    <strong>Handling</strong>
                    <br>
                    <input type="text" class="form-control" id="ad23" name="ad23" v-bind:style="{'color': ad23color23}" v-show="ad23color23">
                </div>
                <div class="col-md-4">
                    <strong>Security</strong>
                    <br>
                    <input type="text" class="form-control" id="ad24" name="ad24" v-bind:style="{'color': ad23color24}" v-show="ad23color24">
                </div>
                <div class="col-md-4">
                    <strong>De-icing</strong>
                    <br>
                    <input type="text" class="form-control" id="ad25" name="ad25" v-bind:style="{'color': ad23color25}" v-show="ad23color25">
                </div>
                <div class="col-md-12">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad26" name="ad26" v-bind:style="{'color': ad23color26}" v-show="ad23color26"></textarea>
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
$('#btn_formulir').click(function() {
    $('#formulir').submit();
});

var arpt =@json($airport);arp=arpt[0];
var contents =@json($content);
var ttl=arp.icao + ' AD 2.3 OPERATIONAL HOURS';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);

// console.log(a);
// console.log(contents);

for (let i=15;i<27;i++){
    var idx= contents.findIndex( x => x.category_id === i );
   
    if (idx==-1){
        var isi='';
        var sts='U';
    }else{
        var sts=contents[idx].status;
        var isi =checkisicontain(contents[idx].content);
    }
    isidata("ad" +i ,sts);
    // console.log(isi,i);
    $("#ad" +i ).val(isi);
}




function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection