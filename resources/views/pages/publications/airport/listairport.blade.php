@extends('layouts.app')

@section('template_title')
    Airport
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Airport</h3>
                    </div>
                </div>
                <div class="panel-heading mt-3">
                    <button onclick="backtomenu()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                </div>
            </div>
            <div class="row" id="datalistarpt" style="visibility: visible">
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th>ICAO</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody id="arptlist">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="datanewarpt" style="visibility: hidden">

                <div class="panel-heading mt-1">
                    <h6 class="panel-title"></h6>
                </div>
                <div class="panel-body">
                <form action="api/airport/save" method="POST" id="formulir">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="arpt_ident" id="arpt_ident">
                <input type="hidden" name="status" id="status" value="N">
                <input type="hidden" name="geom" id="geom">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>ICAO</strong>
                            <br>
                            <input id="icao" onkeyup="this.value = this.value.toUpperCase();" style="text-transform: uppercase" type="text" onfocusout="Checkdouble(this.id)" maxlength="4" class="form-control" name="icao">
                        </div>
                        <div class="col-md-2">
                            <strong>IATA</strong>
                            <br>
                            <input id="iata" onkeyup="this.value = this.value.toUpperCase();" style="text-transform: uppercase" type="text" onfocusout="Checkdouble(this.id)" maxlength="3" class="form-control" name="iata">
                        </div>
                        <div class="col-md-5">
                            <strong>Name</strong>
                            <br>
                            <input id="arpt_name" onkeyup="this.value = this.value.toUpperCase();" style="text-transform: uppercase" type="text" onfocusout="Checkdouble(this.id)" class="form-control" name="arpt_name">
                        </div>
                        <div class="col-md-3">
                            <strong>City</strong>
                            <br>
                            <input id="city_name" onkeyup="this.value = this.value.toUpperCase();" style="text-transform: uppercase" type="text" class="form-control" name="city_name">
                        </div>
                        <div class="col-md-3">
                            <strong>Latitude</strong>
                            <br>
                            <input id="latitude"  ref="latitude" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" name="latitude" onfocusout="CheckCoordinateFormat(this.id,'LAT')"  placeholder="06300000S">
                        </div>
                        <div class="col-md-3">
                            <strong>Longitude</strong>
                            <br>
                            <input id="longitude" ref="longitude" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" name="longitude" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('latitude','longitude');calmagvar('year')" placeholder="106300000E">
                        </div>
                        <div class="col-md-3">
                            <strong>Epoch</strong>
                            <br>
                            <input id="year" type="date" onchange="calmagvar(this.id)" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <strong>Magvar</strong>
                            <br>
                            <input id="mag_var" name="mag_var" readonly type="text" class="form-control" >
                        </div>
                        <div class="col-md-4">
                            <strong>Country</strong>
                            <br>
                            <select id="ctry" name="ctry" selected="selected" class="form-control" >
                            </select>
                        </div>
                        <div class="col-md-3">
                            <strong>Type</strong>
                            <br>
                            <select id="type" name="type" selected="selected" class="form-control" >
                            </select>
                        </div>
                        <div class="col-md-3">
                            <strong>Volume</strong>
                            <br>
                            <select id="vol" name="vol"selected="selected" class="form-control" >
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>UTC</strong>
                            <br>
                            <input id="time" name="time" type="text" style="text-transform:uppercase" class="form-control" >
                        </div>
                        <div class="col-md-2">
                            <strong>Trans Alt</strong>
                            <br>
                            <input id="ta" name="ta" type="text" style="text-transform:uppercase" maxlength="3" class="form-control" >
                        </div>
                        <div class="col-md-2">
                            <strong>Trans Level</strong>
                            <br>
                            <input id="tl" name="tl" type="text" style="text-transform:uppercase" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <strong>PIA Wilayah</strong>
                            <br>
                            <select id="auth" name="auth" selected="selected"  class="form-control" >
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div>
                </form>
                    <div class="row">
                        <div class="col-md-6">
                            <button onclick="isback()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                            &nbsp;
                            <button id="btn_formulir" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
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
$("#datanewarpt").hide();
var arpt =@json($airport);
var atypes =@json($arptypes);
var acode =@json($codaip);
var ctry =@json($countries);
var pia =@json($pia);
var onReq =@json($onrequest);

// console.log(arpt)
// var cord=SetCoordinatebyGeom(arpt[0].geom)
// var arptident=a.arpt_ident;
var volume= [{
    id: '2',
    definition: 'VOL II'
}, {
    id: '3',
    definition: 'VOL III'
}, {
    id: '4',
    definition: 'VOL IV'
}, {
    id: '5',
    definition: 'VOL V'
}];
arpt.forEach(arp=>{

    var info=''

    var idx=onReq.findIndex(x=>x.fieldid===arp.arpt_ident)
    // console.log(idx)
    if (idx !== -1){
        info ='title="Data is in the process of publication" style="color:#ede2df; font-weight:bolder;"'
        // console.log(arp)
    }
    // console.log(arp,onReq)
 
        // var crd =SetCoordinatebyGeom(arp.geom)
        // acord=crd.WGSAIP[1] +' ' + crd.WGSAIP[0]
        acord='VOL '+ arp.vol;
   
  var  hsl= '<tr v-bind:key="arp.arpt_ident">'+
        '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em ' +info+' class="icon ni ni-more-h"></em></a>'+
                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                    '<ul class="link-list-plain">'+
                        '<a class="btn btn-dim btn-primary col-md-12" id='+ arp.arpt_ident +' onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                        '<a class="btn btn-dim btn-info col-md-12" id='+ arp.arpt_ident +' onclick="setMapPoint(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                        '<a class="btn btn-dim btn-danger col-md-12" id='+ arp.arpt_ident +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Removed</a>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</td>'+
        '<td '+info+'>'+ arp.icao +'</td>'+
        '<td '+info+'>'+ arp.arpt_name +'</td>'+
        '<td '+info+'>'+ arp.city_name +'</td>'+
        '<td '+info+'>'+  acord +'</td>'+
    '</tr>';
    $("#arptlist").append(hsl)
})

                            
function backtomenu(){
    window.location.href = '/aipsubmission/edit';
}
function setMapPoint(data) {
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=arpt&id='+data, 'Set Latitude and Longitude', params)
}
function edit(data) {
    console.log(data)
    window.scrollTo(0,0);
    window.location.href = '/editairport/' + data;
}
function NewData(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");
    ctry.forEach(c=>{
        $("#ctry").append('<option value="'+c.ident+'">'+c.country+'</option>');
    })
    atypes.forEach(a=>{
        $("#type").append('<option value="'+arp.id+'">'+arp.definition+'</option>');
    })
    pia.forEach(a=>{
        $("#auth").append('<option value="'+arp.id+'">'+arp.name+'</option>');
    })
    volume.forEach(a=>{
        $("#vol").append('<option value="'+arp.id+'">'+a.definition+'</option>');
    })
    $("#ctry").val('ID');                 
    this.isList=false;
    this.authlist=pia;
    this.arpttype=atypes;
    
}
function remove(id){
    // console.log(id)
    dtsrcraw={
        _token:"{{ csrf_token() }}",
        deleted:1,
        editor:"{{ Auth::user()->id }}",
    }
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'api/airport/remove/' + id,
                        data: JSON.stringify(dtsrcraw),
                        success: response => {
                            
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                                )
                            location.reload();
                                // this.loadNavaidList(this.volradio)
                        }
                    })
                }
            })

}
function isback(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");

}
function Checkdouble(id,id1){
    var val=$("#"+id).val().toUpperCase();
    // console.log(id,id1)
    var hsl=''
    switch (id) {
        case 'icao':
            hsl= arpt.findIndex( x => x.icao === val )
            break;
        case 'iata':
            hsl= arpt.findIndex( x => x.iata === val )
            break;
        case 'arpt_name':
            var arptsort=arpt;
            arptsort.sort( ( a, b ) => ( a.arpt_ident > b.arpt_ident ) ? 1 : ( ( b.arpt_ident > a.arpt_ident ) ? -1 : 0 ) );
            var pjg=arptsort.length
            var idd=Number(arptsort[pjg-1].arpt_ident.replace('ID',''))+1
            var arptid='ID' + numeral(idd).format('00000')
            $("#arpt_ident").val(arptid)
            // console.log(arptsort[pjg-1],idd,arptid)
            hsl= arpt.findIndex( x => x.arpt_name === val )
            break;
 
    }
    if (hsl !== -1 && val !== ''){
        // console.log(arpt[hsl])
        $("#"+id).val('');
        $("#"+id).focus();
        Swal.fire(
            'Data Double',
            'The data already exists '+ arpt[hsl].arpt_name + ' Airport ' + arpt[hsl].city_name ,
            'info'
            )
       
    }
   

    // console.log(val,hsl)
}
$('#btn_formulir').click(function() {
    if ($("#arpt_name").val()=='' || $("#geom").val()=='' ){
        Swal.fire(
            'Incomplete data',
            'please complete the data first' ,
            'info'
            )
    }else{
           $('#formulir').submit();
    }
       });

    // this.isList=false;
    // this.authlist=pia;
    // this.arpttype=atypes;

function calmagvar(id){
    

    var epoch = new Date($("#"+id).val()).toISOString().substr( 0, 10 );
    var lat1= $("#latitude").val();
    var lon1 =$("#longitude").val();
    // console.log(epoch,lat1,lon1);
    var crd=SetCoordinate(lat1,lon1);
    if (crd.Decimal[0] > 135){
        $("#ta").val('18000');
        $("#tl").val('FL180');
    }else{
        $("#ta").val('11000');
        $("#tl").val('FL130');
    }
//    console.log(crd.Decimal[0], crd.Decimal[1])
    var hlat= arpt.findIndex( x => x.latitude === lat1 )
    var hlon= arpt.findIndex( x => x.longitude === lon1 )
    // console.log(hlat,hlon)
    if (hlat == hlon && hlat > -1 && hlon > -1){
      
        Swal.fire(
            'Data Double',
            'The data already exists '+ arpt[hlat].arpt_name + ' Airport ' + arpt[hlat].city_name ,
            'info'
            )
    }
    var mv =GetMagvar( crd.Decimal[0], crd.Decimal[1], epoch );
    // console.log(crd,mv);
    $("#mag_var").val(mv.magvar)
    $("#geom").val(crd.Point)
    var valutc = 'a=' + crd.Decimal[1] + '&b=' + crd.Decimal[0]
    var pathdetail= pathpop()   + '/api/eaip/getutc?' + valutc;//offsetpoly[0];
    var rts=[];
    var infox ='';
        $.ajax({
                url: pathdetail,
                // data: {'geom' : poly},
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        $("#time").val(Number(v.name))
                        // rts.push(v);
                    })
                   
                }
        })
     
   
    // var mv = GetMagvar( longitude1, latitude1, epoch );

}



</script>
@endsection