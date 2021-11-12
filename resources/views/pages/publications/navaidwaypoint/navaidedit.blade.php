@extends('layouts.app')

@section('template_title')
    Navaid
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="panel-heading mt-3">
        <h6 class="panel-title" id ="navtitle"></h6>
    </div>
    <div class="panel-body mt-3">
    <form action="api/navaid/save" method="post"  enctype="multipart/form-data" id="navform">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="nav_id" id="nav_id">
        <input type="hidden" name="status_vld" id="status_vld">
        <input type="hidden" name="geom" id="geom">
        <input type="hidden" name="freq" id="freq">
        <input type="hidden" name="parent" id="parent" value="{{$parent}}">
        <input type="hidden" name="parentid" id="parentid" value="{{$parentid}}">
        <input type="hidden" name="atsstatus" id="atsstatus" value="{{$atsstatus}}">
        <div class="row">
            <div class="col-md-3">
                <strong>Country</strong>
                <br>
                <select class="form-control" id="ctry"  name="ctry">
                    @foreach($countries as $n)
                        <option value="{{$n->ident}}">{{$n->country}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <strong>Type</strong>
                <br>
                <select class="form-control" id="type" name="type">
                    @foreach($navtypes as $n)
                        <option value="{{$n->id}}">{{$n->definition}}</option>
                    @endforeach
                
                </select>
            </div>
            <div class="col-md-2">
                <strong>Ident</strong>
                <br>
                <input id="nav_ident" ref="nav_ident" type="text" style="text-transform:uppercase" class="form-control" name="nav_ident">
            </div>
            <div class="col-md-5">
                <strong>Name</strong>
                <br>
                <input id="nav_name" ref="nav_name"  style="text-transform:uppercase" type="text" class="form-control" name="nav_name">
            </div>
            <div class="col-md-2">
                <strong>Frequency</strong>
                <br>
                <input id="freq_real" onfocusout="checkfreq(this.id)" type="text" class="form-control">
            </div>
            <div class="col-md-3">
                <strong>Epoch</strong>
                <br>
                <input id="year" type="date" onchange="calmagvar(this.id)" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <strong>Latitude</strong>
                <br>
                <input id="navlat" name="navlat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" placeholder="06300000S">
            </div>
            <div class="col-md-2">
                <strong>Longitude</strong>
                <br>
                <input id="navlon" name="navlon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('navlat','navlon')" placeholder="106300000E">
            </div>
            <div class="col-md-3">
                <strong>Magvar</strong>
                <br>
                <input type="text" class="form-control" id="mag_var"  name="mag_var" readonly>
            </div>
            <div class="col-md-2">
                <strong>Range</strong>
                <br>
                <input type="number" class="form-control" id="range" name="range">
            </div>
            <div class="col-md-2">
                <strong>Altitude</strong>
                <br>
                <input type="number" class="form-control" id="altitude" name="altitude">
            </div>
            <div class="col-md-2">
                <strong>Opr. Hours</strong>
                <br>
                <input id="opr_hrs" style="text-transform:uppercase" ref="oprhrs" type="text" class="form-control" name="opr_hrs">
            </div>
            <div class="col-md-2">
                <strong>Collocated DME</strong>
                <br>
                <select selected="selected" onchange="hideoff()" class="form-control" id="col_dme" name="col_dme">
                        <option value="N">NO</option>
                        <option value="Y">YES</option>
                </select>
                <!-- <span>Yang dipilih :  isTampil </span> -->
            </div>
                <div class="card-inner" id="coldme" style="visibility:hidden">
                    <div class="panel-heading">
                        <h6 class="panel-title">DME Information</h6>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <strong>DME Latitude</strong>
                            <br>
                            <input id="dmelat" ref="dmelat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" name="dmelat" placeholder="06300000S">
                        </div>
                        <div class="col-md-3">
                            <strong>DME Longitude</strong>
                            <br>
                            <input id="dmelon" ref="dmelon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('dmelat','dmelon')" name="dmelon" placeholder="106300000E">
                        </div>
                        <div class="col-md-2">
                            <strong>Channel</strong>
                            <br>
                            <input style="text-transform:uppercase" type="text" class="form-control" id="channel"  name="channel" readonly>
                        </div>
                        <div class="col-md-2">
                            <strong>Range</strong>
                            <br>
                            <input id="dme_range" type="number" ref="dmerange" class="form-control" name="dme_range">
                        </div>
                        <div class="col-md-2">
                            <strong>Elevation</strong>
                            <br>
                            <input type="number" class="form-control" id="dme_elev" name="dme_elev">
                        </div>
                    </div>
                </div>
            <div class="col-md-12">
                <strong>Remarks</strong>
                <br>
                <textarea type="text" class="form-control" id="remarks" name="remarks"></textarea>
            </div>
        </div>
        </form>
        <div class="row">
                <div class="col-md-12">
                    <br>
                </div>
            </div>
        <div class="row">
            <div class="col-md-12">
                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                    &nbsp;
                <a onclick="setMapPoint()" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Set Point</a>&nbsp;
                <button onclick="update()" type="submit" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</button>
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" align="right">
                <i style="color:red" align="right">RED Color = Data change request</i>
                <br>
                <i style="color:darkgrey" id="arptidname"></i>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')
<script type="text/javascript">
$('#coldme').hide();
$('#navedit').hide();
var navaid =@json($navaids);
var navtemp =@json($navaidstemp);
var nav =navaid[0];ntemp=navtemp[0];
var no=0;
var ch =@json($channel);frq='';channel='';
var fld=['nav_id','ctry','type', 'nav_ident','nav_name',  'col_dme', 'freq', 'range','altitude', 'mag_var', 'channel', 'dme_range', 'dme_elev', 'opr_hrs', 'remarks','navlat','navlon','dmelat','dmelon'
    ];
var fldenr=['nav_ident','navlat','navlon'];
// console.log(navtemp)
if (navtemp.length > 0){
    var crd=SetCoordinatebyGeom(ntemp.geom)
    var ttl=ntemp.nav_ident + ' '+ ntemp.definition+ ' - ' + ntemp.nav_name + ' Information';
    $("#arptidname").html(ntemp.nav_ident + ' '+ ntemp.definition+ ' - ' + ntemp.nav_name);
    var frqd=FreqFormat(ntemp.freq,ntemp.type,'DATA');
    if (ntemp.type=='4'){
        channel =ch.find( x => x.definition === frqd ).id;
        frq = frqd;
    }else if (ntemp.type=='20'){
        frq='';
        tps=ntemp.nav_name;
        ident='';
    }else{
        frq = frqd;
    }
    // console.log(ntemp,nav)
    $("#navtitle").html(ttl);
    $("#status_vld").val('R');
    $("#id").val(ntemp.id);
        var cord=SetCoordinatebyGeom(ntemp.geom)
        ntemp['navlat']=cord.Database[1];
        ntemp['navlon']=cord.Database[0];
        if (ntemp.dmegeom==null){
            ntemp['dmelat']=ntemp.dme_wgs_lat;
            ntemp['dmelon']=ntemp.dme_wgs_long;
        }else{
            cord=SetCoordinatebyGeom(ntemp.dmegeom)
            ntemp['dmelat']=cord.Database[1];
            ntemp['dmelon']=cord.Database[0];
        }
        if (typeof nav !== 'undefined'){
            cord=SetCoordinatebyGeom(nav.geom)
            nav['navlat']=cord.Database[1];
            nav['navlon']=cord.Database[0];
            if (nav.dmegeom){
                cord=SetCoordinatebyGeom(nav.dmegeom)
                nav['dmelat']=cord.Database[1];
                nav['dmelon']=cord.Database[0];
            }
        }
    
    // console.log(fld,ntemp,nav)
    compareisidata(fld,ntemp,nav);
    $("#freq_real").val(frqd);
    $("#channel").val(channel)

    if (nav.col_dme=='Y'){
        aboutvol('coldme');
    }
}else{
    var ttl='New Data';
    $("#navtitle").html(ttl);
    $("#status_vld").val('N');
    $("#col_dme").val('N');
    $("#type").val('4');
    $("#ctry").val('ID')
}
function hideoff(){
    aboutvol('coldme')
}
function  checkfreq(id) {
    console.log(id)
    var nfreq=$("#"+id).val();
    var ntype=$("#type").val();
    var frqd=FreqFormat(nfreq,ntype,'DATA');
    if (ntype == '5' || ntype == '7' || ntype == '10') {
        if (parseFloat(frqd) < 190 || parseFloat(frqd) > 1750) {
            Swal.fire(
                'Data Failed!',
                'this frequency Not support for NDB',
                'error'
            )
        }else{
            $("#freq").val(parseFloat(frqd) * 1000)
            $("#freq_real").val(frqd)
        }

    } else if (ntype == '1' || ntype == '2' || ntype == '4') { 
        var idx =ch.findIndex( x => x.definition === frqd );
        if (idx == -1){
            Swal.fire(
                'Data Failed!',
                'this frequency Not support for VOR',
                'error'
            )
        }else{
            if (ch[idx].ils_yes=='Y'){
                Swal.fire(
                    'Data Failed!',
                    'this frequency is support for ILS',
                    'error'
                )
            }else{
                $("#channel").val(ch[idx].id)
                $("#freq").val(parseFloat(frqd) * 10000)
                $("#freq_real").val(frqd)
            }
        }

    } else if (ntype == '11') { 
        var idx =ch.findIndex( x => x.definition === frqd );
        if (idx == -1){
            Swal.fire(
                'Data Failed!',
                'this frequency Not support for ILS',
                'error'
            )
        }else{
            if (ch[idx].ils_yes !=='Y'){
                Swal.fire(
                    'Data Failed!',
                    'this frequency is support for VOR',
                    'error'
                )
            }else{
                $("#channel").val(ch[idx].id)
                $("#gs_freq").val(ch[idx].gs_freq)
                $("#freq").val(parseFloat(frqd) * 10000)
            }
        }
    }
}
function setMapPoint() {
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=navaid&id=' + nav.nav_id, 'Set Latitude and Longitude', params)
}
function calmagvar(id){
    // console.log(id,$("#"+id).val())
    var epoch = new Date($("#"+id).val()).toISOString().substr( 0, 10 );
    var lat1= $("#navlat").val();
    var lon1 =$("#navlon").val();
    // console.log(epoch,lat1,lon1);
    var crd=SetCoordinate(lat1,lon1);
    var mv =GetMagvar( crd.Decimal[0], crd.Decimal[1], epoch );
    // console.log(crd,mv);
    $("#mag_var").val(mv.magvar)
    // var mv = GetMagvar( longitude1, latitude1, epoch );

}
function NewData(){
    aboutvol('rwyedit');
    aboutvol('rwytable');
    $("#status_vld").val('N')
    $("#ctry").val('ID')
}

function update(){
    console.log('update')
    var checkrwy=false;
    if  ($("#status_vld").val()=='N'){
        checkrwy =checknewdata(fld);
    
    }else if ($("#status_vld").val()=='R'){
        checkrwy =checkupdatedata(fld,ntemp);
        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#navform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        backtolist();
    }
   
}
function backtomain(){
    
    window.scroll(0,0);
    aboutvol('rwyedit');
    aboutvol('rwytable');
}


function backtolist(){
    history.back();
}

</script>
@endsection