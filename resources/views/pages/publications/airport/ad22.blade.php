@extends('layouts.app')

@section('template_title')
    AERODROME 
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
                <div class="col-md-3">
                    <strong>Epoch</strong>
                    <br>
                    <input id="year" type="date" onchange="calmagvar()" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <strong>Latitude</strong>
                    <br>
                    <input style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT');geoid()" id="ad20" name="ad20" placeholder="06300000S" v-bind:style="{'color': ad2color}" v-show="ad2color">
                </div>
                <div class="col-md-2">
                    <strong>Longitude</strong>
                    <br>
                    <input style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('ad20','ad21');geoid();calmagvar()" id="ad21" name="ad21" placeholder="106300000E" v-bind:style="{'color': ad2color}" v-show="ad2color">
                </div>
                <div class="col-md-5">
                    <strong>magvar <i> changeyear </i></strong>
                    <br>
                    <input type="text" class="form-control" id="ad5" name="ad5" v-bind:style="{'color': ad5color}" v-show="ad5color">
                </div>
                <div class="col-md-5">
                    <strong>Direction and distance from (City)</strong>
                    <br>
                    <input type="text" class="form-control" id="ad3" name="ad3" v-bind:style="{'color': ad3color}" v-show="ad3color">

                </div>
                <div class="col-md-3">
                    <strong>Geoid undulation</strong>
                    <br>
                    <input type="text" class="form-control" id="ad212" name="ad212" v-bind:style="{'color': ad212color}" v-show="ad212color">
                </div>
                <div class="col-md-2">
                    <strong>Elevation (ft)</strong>
                    <br>
                    <input type="text" id="ad228" name="ad228" class="form-control" onfocusout="Checkelev(this.id)" v-bind:style="{'color': ad228color}" v-show="ad228color">
                </div>
                <div class="col-md-2">
                    <strong>Ref. Temp. (°C)</strong>
                    <br>
                    <input type="text" id="ad4" name="ad4" class="form-control" v-bind:style="{'color': ad4color}" v-show="ad4color">
                </div>
                <div class="col-md-6">
                    <strong>AD Administration</strong>
                    <br>
                    <textarea type="text" id="ad6" name="ad6" class="form-control" v-bind:style="{'color': ad6color}" v-show="ad6color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Address</strong>
                    <br>
                    <textarea type="text" id="ad7" name="ad7" class="form-control" v-bind:style="{'color': ad7color}" v-show="ad7color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Telephone</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad8" name="ad8" v-bind:style="{'color': ad8color}" v-show="ad8color"></textarea>
                </div>
                <div class="col-md-6">
                    <strong>Telefax</strong>
                    <br>
                    <textarea type="text" class="form-control" id="ad9" name="ad9" v-bind:style="{'color': ad9color}" v-show="ad9color"></textarea>
                </div>
                <!-- <div class="col-md-6">
                    <strong>Telex</strong>
                    <br>
                    <textarea type="text" class="form-control" v-model="ad10" v-bind:style="{'color': ad10color}" v-show="ad10color"></textarea>
                </div> -->
                <div class="col-md-6">
                    <strong>e-mail</strong>
                    <br>
                    <input type="email" class="form-control" placeholder="name@example.com" id="ad11" name="ad11">
                </div>
                <div class="col-md-6">
                    <strong>AFS</strong>
                    <br>
                    <input type="text" class="form-control" id="ad12" name="ad12">
                </div>
                <div class="col-md-6">
                    <strong>Website</strong>
                    <br>
                    <input type="text" class="form-control" id="ad227" name="ad227">
                </div>
                <div class="col-md-6">
                    <strong>Type of traffic</strong>
                    <br>
                     <select class="form-control" id="ad13" name="ad13">
                    </select>
                </div>
                <div class="col-md-12">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" id="ad14" name="ad14"  class="form-control"></textarea>
                </div>
            </div>
            </form>
            
            <div class="card-inner">
                <div class="row">
                    <div class="col-md-6">
                        <button onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                        &nbsp;
                            <button class="btn btn-dim btn-dark" id="btn_formulir"><i class="icon ni ni-save-fill"></i> Update</button>
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
@endsection
@section('footer_scripts') 
<script type="text/javascript">
$('#btn_formulir').click(function() {
    $('#formulir').submit();
});
$('#editarp').hide();
var sts='U';
var traffic= [{
                value: 'IFR'
            }, {
                value: 'IFR/VFR'
            }, {
                value: 'VFR'
            },{
                value: 'NIL'
            }];
traffic.forEach(v=>{
    hasil='<option id="volisi" value="'+v.value+'">'+v.value+'</option>';
    $("#ad13").append(hasil);
})
var arpt =@json($airport);arp=arpt[0];
var contents =@json($content);
var ttl=arp.icao + ' AD 2.2 AERODROME GEOGRAPHICAL AND ADMINISTRATIVE DATA';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
var cord=SetCoordinatebyGeom(arp.geom)
$("#contentitle").html(ttl);


var idx= contents.findIndex( x => x.category_id === 228 );
if (idx !== -1){
    $("#ad228").val(checkisicontain(contents[idx].content));
    sts=contents[idx].status;
    isidata("ad228" ,sts);

}
// console.log(contents[idx].content);
sts='U';
idx= contents.findIndex( x => x.category_id === 2 );
if (idx==-1){
    $("#ad20").val(cord.Database[1]);
    $("#ad21").val(cord.Database[0]);
}else{
    cord=contents[idx].content.split(' ')
    sts=contents[idx].status;
    $("#ad20").val(cord[0]);
    $("#ad21").val(cord[1]);
    isidata("ad20" ,sts);
    isidata("ad21" ,sts);
}

for (let i=3;i<15;i++){
    sts='U';
    idx= contents.findIndex( x => x.category_id === i );
    if (idx==-1){
        var isi='';
    }else{
        var isi =checkisicontain(contents[idx].content);
        sts=contents[idx].status;
    }
    
    // console.log(sts,isi,contents[idx]);
    
    // console.log(isi,i);
    $("#ad"+i ).val(isi);
    if (i !== 10){
        isidata("ad"+i,sts)

    }
}
function Checkelev(id){
    var ell=$("#"+id).val();
    var ttgr=[];
    arp.runwaystemp.forEach(e=>{
        ttgr.push(Number(e.physicals[0].thr_elev))
        ttgr.push(Number(e.physicals[0].tdz_elev))
        ttgr.push(Number(e.physicals[1].thr_elev))
        ttgr.push(Number(e.physicals[1].tdz_elev))
    })
    var mmx=Math.max(...ttgr);
    if (mmx > ell){
        Swal.fire(
            'Incorrect value!',
            'The highest value of rwy is ' + mmx ,
            'warning'
        )
        // alert('sdfsdfsdfdsf')
        $("#"+id).val(mmx);
    }
    // console.log(ttgr,Math.max(...ttgr))

}
function geoid(){
    var ad21 = $('#ad21').val();
    var ad20 = $('#ad20').val();
    var ajaxurl = '/GeoHi/'+ad20+'/'+ad21;
    $.ajax({
        url: ajaxurl,
        type: "GET",
        success: function(data){
            // var hasil=Math.round(data) +'m / ' + Math.round((data * 3.28084))+'ft';
            var hasil= Math.round((data * 3.28084))+'ft';
            $("#ad212" ).val(hasil);
            // console.log(hasil);
        }
    });
}
$('#ad21').change(function() {
    geoid()
});
idx= contents.findIndex( x => x.category_id === 212 );
if (idx !== -1){
    sts='U';
    sts=contents[idx].status;
    $("#ad212").val(checkisicontain(contents[idx].content));
    isidata("ad212" ,sts);

}
idx= contents.findIndex( x => x.category_id === 227 );
if (idx !== -1){
    sts='U';
    sts=contents[idx].status;
    $("#ad227").val(checkisicontain(contents[idx].content));
    isidata("ad227" ,sts);
    // console.log(contents[idx].content);

}

function calmagvar(){
    // console.log(id,$("#year").val())
    var epoch = new Date($("#year").val()).toISOString().substr( 0, 10 );
    var lat1= $("#ad20").val();
    var lon1 =$("#ad21").val();
    // console.log(epoch,lat1,lon1);
    var crd=SetCoordinate(lat1,lon1);
    var mv =GetMagvar( crd.Decimal[0], crd.Decimal[1], epoch );
    // console.log(crd,mv);
    $("#ad5").val(mv.aip)
    // var mv = GetMagvar( longitude1, latitude1, epoch );

}
function backtolist(){ 
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection