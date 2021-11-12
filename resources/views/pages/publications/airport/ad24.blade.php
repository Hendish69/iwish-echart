@extends('layouts.app')

@section('template_title')
    HANDLING SERVICES AND FACILITIES
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
                    <strong>Cargo-handling facilities</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad27" name="ad27"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Fuel/oil types</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad28" name="ad28"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Fuelling facilities/capacity</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad29" name="ad29"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>De-icing facilities</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad30" name="ad30"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Hangar space for visiting aircraft</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad31" name="ad31"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Repair facilities for visiting aircraft</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad32" name="ad32"></textarea>
                </div>
                <div class="col-md-12">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad33" name="ad33"></textarea>
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
var ttl=arp.icao + ' AD 2.4 HANDLING SERVICES AND FACILITIES';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);

// console.log(a);
// console.log(contents);
var idx;

for (let i=27;i<34;i++){
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




function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection