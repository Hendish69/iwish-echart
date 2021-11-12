@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <!-- HEADER -->
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title" id="navid"></h5>
                </div>
            </div>
            <div class="panel-body mt-3">
                <div class="col-md-12">
                    <div class="row">
                        @foreach($ils as $n)
                        <div class="col-md-2">
                            <span>Ident</span>
                            <br>
                            <b style="color:#0A4EAB; font-weight:bolder;">{{$n->ils_ident}}</b>
                        </div>
                        <div class="col-md-2">
                            <span>Type</span>
                            <br>
                            <b style="color:#0A4EAB; font-weight:bolder;">ILS/LLZ</b>
                        </div>
                        <div class="col-md-2">
                            <span>Name</span>
                            <br>
                            <b style="color:#0A4EAB; font-weight:bolder;">{{$n->ils_name}}</b>
                        </div>
                        <div class="col-md-2">
                            <span>frequency</span>
                            <br>
                            <b id="freqid" style="color:#0A4EAB; font-weight:bolder;">{{$n->freq}}</b>
                        </div>
                        <div class="col-md-2">
                            <span>Latitude</span>
                            <br>
                            <b id="latid" style="color:#0A4EAB; font-weight:bolder;"></b>
                        </div>
                        <div class="col-md-2">
                            <span>Longitude</span>
                            <br>
                            <b id="lonid" style="color:#0A4EAB; font-weight:bolder;"></b>
                        </div>
                        <div class="col-md-2">
                            <span>GP Freq</span>
                            <br>
                            <b id="gpfreq" style="color:#0A4EAB; font-weight:bolder;">{{$n->gs_freq}}</b>
                        </div>
                        <div class="col-md-2">
                            <span>GP Latitude</span>
                            <br>
                            <b id="gplatid" style="color:#0A4EAB; font-weight:bolder;"></b>
                        </div>
                        <div class="col-md-2">
                            <span>GP Longitude</span>
                            <br>
                            <b id="gplonid" style="color:#0A4EAB; font-weight:bolder;"></b>
                        </div>
                        <div class="col-md-2">
                            <span>Magvar</span>
                            <br>
                            <b style="color:#0A4EAB; font-weight:bolder;">{{$n->stat_decl}}</b>
                        </div>
                        <div class="col-md-2">
                            <span>Opr. Hours</span>
                            <br>
                            <b style="color:#0A4EAB; font-weight:bolder;">{{$n->opr_hrs}}</b>
                        </div>
                        <div class="col-md-12">
                            <span>Remarks</span>
                            <br>
                            <span style="color:#0A4EAB; font-weight:bolder;">{{$n->remarks}}</span>
                        </div>
                
                        <div class="col-md-12">
                            <br>
                        </div>
                        @if (!is_null($n->nav_id ))
                        <div class="col-md-12">
                            <div class="panel-heading">
                                <h6 class="panel-title">DME Information </h6>
                            </div>
                            <div  class="row">
                                <div class="col-md-2">
                                    <span>Channel</span>
                                    <br>
                                    <b id="chid" style="color:#0A4EAB; font-weight:bolder;"></b>
                                </div>
                                <div class="col-md-2">
                                    <span>DME Latitude</span>
                                    <br>
                                    <b id="dmelatid" style="color:#0A4EAB; font-weight:bolder;"></b>
                                </div>
                                <div class="col-md-2">
                                    <span>DME Longitude</span>
                                    <br>
                                    <b id="dmelonid" style="color:#0A4EAB; font-weight:bolder;"></b>
                                </div>
                                <div class="col-md-2">
                                    <span>Range</span>
                                    <br>
                                    <b id="dme_range" name="dme_range" style="color:#0A4EAB; font-weight:bolder;"></b>
                                </div>
                                <div class="col-md-2">
                                    <span>Elevation</span>
                                    <br>
                                    <b id="dme_elev" name="dme_elev" style="color:#0A4EAB; font-weight:bolder;"></b>
                                </div>
                            </div>
                        </div>
                        @endif
            
                        @endforeach
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark" id="judultable">
                        </thead>
                        <tbody id="markerlist">
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                        <button id="btnnavedit" style="visibility: hidden" onclick="navaidedit()" class="btn btn-sm btn-dim btn-dark"><i class="icon ni ni-edit"></i> Edit</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="row mt-12">
                    <div class="col-md-12" id="proctable" style="visibility: hidden">
                        <h6>Used in Procedure Charts</h6>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>Procedure Name</th>
                                </tr>
                            </thead>
                            <tbody id="procinfo">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12"  id="noused" style="visibility: hidden">
                    <h6>this data has not been used in ATS Routes, Procedures and Airspaces</h6>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('footer_scripts')
<script type="text/javascript">
var proc =@json($proc);
var ils =@json($ils)[0];
var ch =@json($channel);
var showedit=@json($id);
var parent=@json($parent);
var parentid=@json($parentid);
// console.log(ils)
$('#proctable').hide();
$('#noused').hide();
$('#btnnavedit').hide();
if (showedit=="edit"){
    aboutvol('btnnavedit');
}
function navaidedit(){
    // console.dir(ils.ils_id)
    window.location.href = '/ils/' + ils.ils_id + '@edit@'+parent +'@' + parentid;
        window.scrollTo(0,0);
}
if (ils.marker.length >0){
    $("#judultable").empty();
    jdl ='<tr align="center">' +
        '<th></th>'+
        '<th>Type</th>'+
        '<th>freq</th>'+
        '<th>Coordinates</th>'+
        '<th>Remarks</th>'+
    '</tr>'
    $("#judultable").append(jdl);
    ils.marker.forEach(a=>{;
            // console.log(a)
            var c1= SetCoordinatebyGeom(a.geom);
            var cord =c1.WGS[1] + ' ' +c1.WGS[0];
            var frq = FreqFormat(a.freq,a.type,'DATA');
            var c='';
            hasil = '<tr>'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                        '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                            '<ul class="link-list-plain">'+
                                '<a class="btn btn-dim btn-danger" id="'+ a.id +'" onclick="remove(this.id)">Delete</a>'+
                            '</ul>'+
                        '</div>'+
                '</div>'+
            '</td>'+
            '<td>' + a.mrkr_type + '</td><td>' + a.freq + '</td><td>' + cord + '</td><td>' + a.remarks + '</td></tr>';
            $("#markerlist").append(hasil);
        
    });
}
// console.dir(ats)
// console.dir(proc)
// console.dir(asp)
if (proc.length == 0){
    aboutvol('noused');
    hasil ='<h6 align="center" style="color:#0A4EAB; font-weight:bolder;">:: ' + ils.ils_ident + ' ILS/LLZ  - ' + ils.ils_name + ' is not used in ATS Routes, Procedures and Airspaces ::</h6>'
    $("#noused").html(hasil);
}
$('#navid').html(ils.ils_ident + ' ILS/LLZ - ' + ils.ils_name + ' Information' );
var navcord= SetCoordinatebyGeom(ils.geom)
$('#latid').html(navcord.WGS[1]);$('#lonid').html(navcord.WGS[0]);
var navcord= SetCoordinatebyGeom(ils.gs_geom)
$('#gplatid').html(navcord.WGS[1]);$('#gplonid').html(navcord.WGS[0]);

    if (ils.navaid.length > 0){
        console.log('DME',ils.navaid)
        frq = FreqFormat(ils.freq,'11','DATA')
        $('#chid').html('CH-' + ch.find( x => x.definition ===frq ).id)
        if (ils.navaid[0].geom==null){
            var dmecord= SetCoordinate(ils.navaid[0].dme_wgs_lat,ils.navaid[0].dme_wgs_long);
        }else{
            // console.log(ils.dmegeom)
            var dmecord= SetCoordinatebyGeom(ils.navaid[0].geom);
        }
        
        
        $('#dmelatid').html(dmecord.WGS[1]);$('#dmelonid').html(dmecord.WGS[0]);
        $('#dme_range').html(ils.navaid[0].dme_range);
        $('#dme_elev').html(ils.navaid[0].dme_elev);
    }
    


if (proc.length > 0){
    aboutvol('proctable');
 var hasil='';
        proc.forEach(a=>{
            // console.log(a);
            hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-info" id="'+ a.proc_id +'$proc' + '" onClick="showmapdetail(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + a.procedure_name  + '</td></tr>'
            $("#procinfo").append(hasil);
        })
    
    
}
function remove(id){
    console.log(id)
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
                url: 'api/ils/marker/remove/' + id,
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
function showmapdetail(id){
    var ddd=id.split('$');
    console.log(id)
    var ddd = id.split( '$' );
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
                window.open('/map.php?table='+ddd[1]+'&id='+ddd[0], 'Set Latitude and Longitude', params)
}
function backtolist(){
    history.back();

}




</script>
@endsection