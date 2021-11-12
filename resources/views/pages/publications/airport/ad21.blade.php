@extends('layouts.app')

@section('template_title')
    Airport   
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="panel panel-default" id="viewarp" style="visibility: visible">
            <div class="panel-heading">
                <h6 class="panel-title" id="judul"></h6>
            </div>
            <div class="panel-body mt-3">
                <div class="row">
                    <div class="col-md-2">
                        <strong>ICAO</strong>
                        <br>
                        <input type="text" id="icao" onfocusout="getairport()" style="text-transform:uppercase" maxlength="4" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <strong>IATA</strong>
                        <br>
                        <p id="iata"></p>
                    </div>
                    <div class="col-md-3">
                        <strong>Name</strong>
                        <br>
                        <p id="arpt_name"></p>
                    </div>
                    <div class="col-md-3">
                        <strong>City</strong>
                        <br>
                        <p id="city_name"></p>
                    </div>
                    <div class="col-md-2">
                        <strong>Elevation</strong>
                        <br>
                        <p id="elev"></p>
                    </div>
                    <div class="col-md-2">
                        <strong>Latitude</strong>
                        <br>
                        <p id="lat1"></p>
                    </div>
                    <div class="col-md-2">
                        <strong>Longitude</strong>
                        <br>
                        <p id="lon1"></p>
                    </div>
                    <div class="col-md-3">
                        <strong>Mag Var</strong>
                        <br>
                        <p id="mag_var"></p>
                    </div>
                    <div class="col-md-12 mt-3" id='listad'>
                        <strong>Aerodrome Info</strong>
                        <br>
                        <select selected="selected" onchange="getadinfo()" class="form-control" id="info">
                            <option value="">Select Aerodrome info ...</option>
                            @foreach($codaip as $index => $cod)
                                <option value="{{ $cod->id }}">{{ $cod->id }} {{ $cod->definition }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="card-inner">
                        <button onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                        &nbsp;
                        <button onclick="edit()" id="btn_edit" class="btn btn-dim btn-secondary"><em class="icon ni ni-edit"></em> Edit</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12" align="right">
                    <i style="color:red" align="right" id='onrequest'></i>
            </div>

        </div>
        <div class="panel panel-default" id="editarp" style="visibility: hidden">
            <div class="panel-heading">
                <h6 class="panel-title" id="arpttitel"></h6>
            </div>
            <div class="panel-body mt-3">
            <form action="" method="POST" id="formulir">
                @csrf
                <input type="hidden" name="ad2" id="ad2">
                <div class="row">
                    <div class="col-md-2">
                        <strong>ICAO</strong>
                        <br>
                        <input type="text" name="ad229" style="text-transform:uppercase" maxlength="4" class="form-control" id="ad229">
                    </div>
                    <div class="col-md-2">
                        <strong>IATA</strong>
                        <br>
                        <input type="text" name="ad230" style="text-transform:uppercase" maxlength="3" class="form-control" id="ad230">
                    </div>
                    <div class="col-md-5">
                        <strong>Name</strong>
                        <br>
                        <input type="text" name="ad231" style="text-transform:uppercase" class="form-control" id="ad231">
                    </div>
                    <div class="col-md-3">
                        <strong>City</strong>
                        <br>
                        <input type="text" style="text-transform:uppercase" class="form-control" id="ad232" name="ad232">
                    </div>
                    <div class="col-md-3">
                        <strong>Epoch</strong>
                        <br>
                        <input onchange="calmagvar()" type="date" class="form-control" id="epoch" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <strong>Latitude</strong>
                        <br>
                        <input id="lat" ref="latitude" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" placeholder="06300000S">
                    </div>
                    <div class="col-md-3">
                        <strong>Longitude</strong>
                        <br>
                        <input id="lon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control"  onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('lat','lon');calmagvar()" placeholder="106300000E">
                    </div>
                    <div class="col-md-3">
                        <strong>Magvar</strong>
                        <br>
                        <input type="text" class="form-control" id="magvar" name="ad3331">
                        <i>changeyear</i>
                    </div>

                    <div class="col-md-3">
                        <strong>Country</strong>
                        <br>
                        <select selected="selected" id="ad233" name="ad233" class="form-control">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <strong>Type</strong>
                        <br>
                        <select selected="selected" class="form-control" id="arpttype" name="ad3332">
                            @foreach($arptypes as $index => $tps)
                                <option value="{{$tps->id}}"> {{$tps->definition}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <strong>Volume</strong>
                        <br>
                        <select selected="selected" class="form-control" id="ad234" name="ad234">
                        
                        </select>
                    </div>
                    <div class="col-md-2">
                        <strong>Trans Alt</strong>
                        <br>
                        <input type="text" style="text-transform:uppercase" maxlength="3" class="form-control" id="arpta" name="arpta">
                    </div>
                    <div class="col-md-2">
                        <strong>Trans Level</strong>
                        <br>
                        <input type="text" style="text-transform:uppercase" class="form-control" id="arptl" name="arptl">
                    </div>
                    <!-- <div class="col-md-4">
                        <strong>PIA Wilayah</strong>
                        <br>
                        <select selected="selected" class="form-control" id="pia" name="pia">
                        </select>
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                    </div>
                </div>
            </form>
                <div class="row">
                    <div class="col-md-6">
                        <button onclick="backtoview()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                        &nbsp;
                        <button class="btn btn-dim btn-dark" id="btn_formulir"><em class="icon ni ni-save-fill"></em> Update</button>
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
var pia = @json($pia);ctry = @json($countries);


var volume= [{
                id: 2,
                definition: 'VOL II'
            }, {
                id: 3,
                definition: 'VOL III'
            }, {
                id: 4,
                definition: 'VOL IV'
            }, {
                id: 5,
                definition: 'VOL V'
            }];
var arpt =@json($airport);
// console.log(pia,arpt);
var arp=arpt[0];
var atypes =@json($arptypes);
var acode =@json($codaip);
var ctry =@json($countries);
var content =@json($content);


$('#btn_formulir').click(function() {
    $("#ad2").val($("#lat").val() + ' '+ $("#lon").val());
    var fld=['ad229','ad230','ad231','ad232','ad233','ad234','arpta','arptl'];
    var chkarpt=false;
    for (let i=229;i<235;i++){
        var idx= content.findIndex( x => x.category_id === i );
        // console.log(idx)
        var cnt='';
        if (idx !==-1){
            cnt= content[idx].content
        }
        // if ($("#ad"+i).val() !== null) {

            changetouppercase(fld);
        // }
        if ($("#ad"+i).val() !== cnt){
            chkarpt=true
            break;
        }
        // if (idx==-1){
        //     var isi='';
        //     var sts='U';
        // }else{
        //     var sts=contents[idx].status;
        //     var isi =checkisicontain(contents[idx].content);
        // }
        // isidata("ad" +i ,sts);
        // // console.log(isi,i);
        // $("#ad" +i ).val(isi);
    }
    // var chkarpt =checkupdatedata(fld,content,'ad');
    if (chkarpt==true){
        $('#formulir').submit();

    }else{
        location.reload();
    }
});
$('#editarp').hide();


function getairport(){
    var asparpt=$("#icao").val();
    if ( asparpt.length==4){
        console.log('check wpt name')
        var icaoacc=$("#icao").val().toUpperCase();
        if (icaoacc.length !== 4){
            Swal.fire(
                'No Airport data!',
                'Please re-enter the airport code' ,
                'warning'
            )
        }else{

            $.ajax({
                    url: '/api/airports',
                    data: {'icao' : icaoacc},
                    type: "json",
                    method: "GET",
    
                    success: function (result) {
                        var jmlwpt=result.data.length
                        // console.log(jmlwpt,'jmlwpt');
                        if (jmlwpt == 0){
                            Swal.fire(
                                'No Airport data!',
                                'Please re-enter the airport code' ,
                                'warning'
                            )
                        }
    
                        $.each(result.data, function (k, v) {
                            // console.log(v)
                            window.scrollTo(0,0);
                            window.location.href = '/editairport/' + v.arpt_ident;
                        })
                    }
            })
        }
    }
}

    var ix=content.findIndex(x=>x.category_id==229)
    if (ix == -1){
        $("#icao").val(arp.icao);
    }else{
        $("#icao").val(content[ix].content);
    }
    
    ix=content.findIndex(x=>x.category_id==230)
    if (ix == -1){
        $("#iata").html(arp.iata);
    }else{
        $("#iata").html(content[ix].content);
    }
    
    ix=content.findIndex(x=>x.category_id==231)
    if (ix == -1){
        $("#arpt_name").html(arp.arpt_name);
    }else{
        $("#arpt_name").html(content[ix].content);
    }
    ix=content.findIndex(x=>x.category_id==232)
    if (ix == -1){
        $("#city_name").html(arp.city_name);
    }else{
        $("#city_name").html(content[ix].content);
    }
    
    var cord=SetCoordinatebyGeom(arp.geom)
    ix= content.findIndex( x => x.category_id === 2 );
    // console.log(content[ix].content,ix)
    if (ix==-1){
        $("#lat1").html(cord.WGS[1]);
        $("#lon1").html(cord.WGS[0]);
    }else{
       
        if (content[ix].content.trim()=='' || content[ix].content==null){
            $("#lat1").html(cord.WGS[1]);
            $("#lon1").html(cord.WGS[0]);
        }else{
            cord=content[ix].content.split(' ')
            $("#lat1").html(cord[0]);
            $("#lon1").html(cord[1]);
        }
    }
    ix=content.findIndex(x=>x.category_id==228)
    if (ix == -1){
        $("#elev").html(arp.elev);
    }else{
        $("#elev").html(content[ix].content);
    }
   
    
    
        $("#mag_var").html(arp.mag_var);

var jdlairport=$("#icao").html() + ' ' + $("#city_name").html() +'/'+ $("#arpt_name").html();
    $("#judul").html(jdlairport);
var onRequest =@json($onrequest);
// console.log(content,'content')
var onReq=false;
if (onRequest.length > 0){
    var btn= document.getElementById("btn_edit")
    var btn1= document.getElementById("info")
    // console.log(btn)
    btn.disabled = true
    btn1.disabled = true
    onReq=true;
    $("#onrequest").html('<b>'+arp.arpt_name + '</b> Airport is not editable,<br> it is in the publication process')
}
// console.log(onRequest,onRequest.length,onReq)
// var cord=SetCoordinatebyGeom(arp.geom)
var arptident=arp.arpt_ident;


ctry.forEach(v=>{
    hasil='<option value="'+v.ident+'">'+v.country+'</option>';
    $("#ad233").append(hasil);
})

volume.forEach(v=>{
    hasil='<option value="'+v.id+'">'+v.definition+'</option>';
    $("#ad234").append(hasil);
})

pia.forEach(v=>{
    // console.log(v)
    hasil='<option value="'+v.id+'">'+v.name + ' ' + v.pia+'</option>';
    $("#pia").append(hasil);
})
// console.log(a);
// console.log(atypes);
// console.log(acode);
// console.log(ctry);
function backtoview(){
    aboutvol('editarp');
    aboutvol('viewarp');
}
function edit(){
    aboutvol('editarp');
    aboutvol('viewarp');
   
    for (let i=229;i<=234;i++){
        var idx= content.findIndex( x => x.category_id === i );
        // console.log(idx)
        if (idx==-1){
            var isi='';
            var sts='U';

        }else{
            var sts=content[idx].status;
            var isi =checkisicontain(content[idx].content);
        }
        isidata("ad" +i ,sts);
    
        // console.log(isi,i,a);
        $("#ad" +i ).val(isi);
        if (isi=='' || isi=='NIL'){
            switch (i) {
                case 229:
                    $("#ad229").val(arp.icao); 
                    break;
                case 230:
                    $("#ad230").val(arp.iata); 
                    break;
                case 231:
                    $("#ad231").val(arp.arpt_name); 
                    break;
                case 232:
                    $("#ad232").val(arp.city_name); 
                    break; 
                case 233:
                    $("#ad233").val('ID'); 
                    break;
                case 234:
                    $("#ad234").val(arp.vol); 
                    break; 
            }

        }
      
        // $("#ad230").val(arp.iata);
        // $("#ad231").val(arp.arpt_name);
        // $("#ad232").val(arp.city_name);
        // $("#ad233").val(arp.ctry);
        // $("#ad234").val(arp.vol);
    }
    sts='U';
ix= content.findIndex( x => x.category_id === 2 );
    if (ix==-1){
        $("#lat").val(cord.Database[1]);
        $("#lon").val(cord.Database[0]);
    }else{
        if (content[ix].content.trim()=='' || content[ix].content==null){
            // console.log('cord.Database[1]',cord.Database[1])
            $("#lat").val(cord.Database[1]);
            $("#lon").val(cord.Database[0]);
        }else{
            sts=content[idx].status;
            $("#lat").val(cord[0]);
            $("#lon").val(cord[1]);
            isidata("lat" ,sts);
            isidata("lon" ,sts);
        }
    // console.log('CC',content[ix].content)
   
   
    }
    if (arpt[0].auth.length > 0){
        // console.log(arpt[0].auth[0].id)
        $("#pia").val(arpt[0].auth[0].id.toString());
    }
    // if (idx==-1){
    //     $("#lat").val(cord.Database[1]);
    //     $("#lon").val(cord.Database[0]);
    // }else{
    //     cord=content[idx].content.split(' ')
    //     sts=content[idx].status;
    //     $("#lat").val(cord[0]);
    //     $("#lon").val(cord[1]);
    //     isidata("lat" ,sts);
    //     isidata("lon" ,sts);
    // }
    // $("#arpttitel").html(arp.arpt_name + ' ' + arp.city_name);
    $("#arpttitel").html(jdlairport);

   
    // console.log(cord);
    // $("#lat").val(cord.Database[1]);
    // $("#lon").val(cord.Database[0]);
   
    $("#magvar").val(arp.mag_var);
    if (arp.tatl.length==0){

    }else{
        
        $("#arpta").val(arp.tatl[0].ta);
        $("#arptl").val(arp.tatl[0].tl);
    }

    $("#arptidname").html(jdlairport);
   
   
    var gvol=getvol(arp.vol);
    var tps=getarpttype(arp.type);
    // console.log(tps);Na
    // $("#volisi").val(arp.vol);
    $("#arpttype").val(arp.type);
    if (arp.auth.length > 0){
        $("#pia").val(arp.auth[0].name)
    }
}
function calmagvar(){
    var epoch = new Date($("#epoch").val()).toISOString().substr( 0, 10 );
    var lat1= $("#lat").val();
    var lon1 =$("#lon").val();
    // console.log(epoch,lat1,lon1);
    var crd=SetCoordinate(lat1,lon1);
    var mv =GetMagvar( crd.Decimal[0], crd.Decimal[1], epoch );
    // console.log(crd,mv);
    $("#magvar").val(mv.magvar)
    if ( crd.Decimal[0] > 135){
        $("#arpta").val('18000')
        $("#arptl").val('FL180')
    }else{
        $("#arpta").val('13000')
        $("#arptl").val('FL130')
    }
    // var mv = GetMagvar( longitude1, latitude1, epoch );

}

function getvol(data){
    // console.log(data);
    var hsl;
    switch(data){
        case 2:
            hsl="VOL II";
            break;
        case "VOL II":
            hsl=2;
            break;
        case 3:
            hsl="VOL III"
            break;
        case "VOL III":
            hsl=3;
            break;
        case 4:
            hsl="VOL IV";
            break;
        case "VOL IV":
            hsl=4;
            break;
        case 5:
            hsl="VOL V";
            break;
        case "VOL V":
            hsl=5;
            break;
}
return hsl;
}
function getarpttype(data){
    // console.log(data);
    var hsl;
    switch(data){
        case "1":
            hsl="Airport of Entry";
            break;
        case "Airport of Entry":
            hsl="1";
            break;
        case "2":
            hsl="Local Public Airport";
            break;
        case "Local Public Airport":
            hsl=2;
            break;
        case "3":
            hsl="Military Airport"
            break;
        case "Military Airport":
            hsl="3";
            break;
        case "4":
            hsl="Joint Civil / Military Airport";
            break;
        case "Joint Civil / Military Airport":
            hsl="4";
            break;
        case "5":
            hsl="Non-Certified Airport";
            break;
        case "Non-Certified Airport":
            hsl="5";
            break;
        case "6":
            hsl="Helicopter";
            break;
        case "Helicopter":
            hsl="6";
            break;
}
return hsl;
}
function backtolist(){
    window.location.href="{{url('/')}}/listairport/edit";
}
function getadinfo(){
    let sub = document.getElementById("info").value;
    let page = sub.split('.').join("").split(" ");
    window.location.href="{{ url('aipedit') }}/" + page[1] + "/" + arptident;
    window.scrollTo(0,0);
    
    $("#info").html('Select Aerodrome info ...');
    
    // console.log(sub[1]);
}
</script>
@endsection