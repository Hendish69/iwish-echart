@extends('layouts.app')

@section('template_title')
    AD 2.9
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
                    <strong>Use of Aircraft Stand ID signs, TWY Guidelines and Visual Docking / Parking Guidance System of Aircraft Stands</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad60" name="ad60"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>RWY and TWY Markings and LGT</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad61"   name="ad61"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Stop bars</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad62"  name="ad62"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Other runway protection measures</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad149" name="ad149"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad63"  name="ad63"></textarea>
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
var ttl=arp.icao + ' AD 2.9 SURFACE MOVEMENT GUIDANCE AND CONTROL SYSTEM AND MARKINGS';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
var cord=SetCoordinatebyGeom(arp.geom)
$("#contentitle").html(ttl);
// console.log(a);
// console.log(contents);

for (let i=60;i<64;i++){

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
    idx= contents.findIndex( x => x.category_id === 149 );
    if (idx==-1){
        var isi='';
        var sts='U';
    }else{
        var sts=contents[idx].status;
        var isi =checkisicontain(contents[idx].content);
    }
   
    $("#ad149").val(isi);

$('#btn_formulir').click(function() {
    $('#formulir').submit();
});

function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection