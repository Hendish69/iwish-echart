@extends('layouts.app')

@section('template_title')
    RESCUE AND FIRE FIGHTING SERVICES
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
                <div class="col-md-4">
                    <strong>AD Category for Fire Fighting</strong>
                    <br>
                    <select selected="selected" class="form-control" id="ad42" name="ad42">
                            <option value="Category 1">Category 1</option>
                            <option value="Category 2">Category 2</option>
                            <option value="Category 3">Category 3</option>
                            <option value="Category 4">Category 4</option>
                            <option value="Category 5">Category 5</option>
                            <option value="Category 6">Category 6</option>
                            <option value="Category 7">Category 7</option>
                            <option value="Category 8">Category 8</option>
                            <option value="Category 9">Category 9</option>
                    </select>
                    <!-- <textarea type="text" class="form-control" id="ad42" name="ad42" v-bind:style="{'color': ad24color1}" v-show="ad24color1"></textarea> -->
                </div>
                <div class="col-md-12">
                    <strong>Rescue Equipment</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad43" name="ad43" v-bind:style="{'color': ad24color2}" v-show="ad24color2"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Capability for Removal of Disabled Aircraft</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad44" name="ad44" v-bind:style="{'color': ad24color3}" v-show="ad24color3"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad45" name="ad45" v-bind:style="{'color': ad24color4}" v-show="ad24color4"></textarea>
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
var ttl=arp.icao + ' AD 2.6 RESCUE AND FIRE FIGHTING SERVICES';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);
// console.log(a);
// console.log(contents);

for (let i=42;i<46;i++){
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