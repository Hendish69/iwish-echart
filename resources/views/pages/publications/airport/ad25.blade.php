@extends('layouts.app')

@section('template_title')
    PASSENGER FACILITIES
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
                    <strong>Hotels</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad34" name="ad34" v-bind:style="{'color': ad24color1}" v-show="ad24color1"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Restaurants</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad35" name="ad35" v-bind:style="{'color': ad24color2}" v-show="ad24color2"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Transportation</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad36" name="ad36" v-bind:style="{'color': ad24color3}" v-show="ad24color3"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Medical facilities</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad37" name="ad37" v-bind:style="{'color': ad24color4}" v-show="ad24color4"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Bank and Post Office</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad38" name="ad38" v-bind:style="{'color': ad24color5}" v-show="ad24color5"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Tourist Office</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad39" name="ad39" v-bind:style="{'color': ad24color6}" v-show="ad24color6"></textarea>
                </div>
                <div class="col-md-12">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad41" name="ad41" v-bind:style="{'color': ad24color7}" v-show="ad24color7"></textarea>
                </div>
            </div>
            </form>
            <div class="card-inner">
                <div class="row">
                    <div class="col-md-6">
                        <button onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                        &nbsp;
                       
                            <button  id="btn_formulir" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Update</button>
                        
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
var ttl=arp.icao + ' AD 2.5 PASSENGER FACILITIES';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);
// console.log(a);
// console.log(contents);
var idx;

for (let i=34;i<42;i++){
    if (i !== 40){
        
        idx= contents.findIndex( x => x.category_id === i );
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
}




function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection