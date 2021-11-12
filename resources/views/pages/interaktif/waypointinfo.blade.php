@extends('layouts.app')

@section('template_title')
    Waypoint Information
@endsection

@section('head')
<link href="{{ asset('template/assets/css/v-modal.css') }}" rel="stylesheet" >
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title" id="wptid"></h5>
                    </div>
                </div>
                
        <div class="panel-body mt-3">
            <div class="row">
                @foreach($wpt as $n)
                <div class="col-md-2">
                    <span>Ident</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->wpt_name}}</b>
                </div>
                <div class="col-md-2">
                    <span>Type</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->definition}}</b>
                </div>
                <div class="col-md-2">
                    <span>Name</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->desc_name}}</b>
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
                    <span>Magvar</span>
                    <br>
                    <b style="color:#0A4EAB; font-weight:bolder;">{{$n->mag_var}}</b>
                </div>
                <div class="col-md-12">
                    <br>
                </div>

                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Close</button>
                    <button id="btnwptedit" style="visibility: hidden" onclick="waypointedit()" class="btn btn-sm btn-dim btn-dark"><i class="icon ni ni-edit"></i> Edit</button>
                    <!-- <button id="btnwptdelete" style="visibility: hidden" onclick="waypointdelete()" class="btn btn-sm btn-dim btn-danger"><i class="icon ne ni-delete-fill"></i> Remove</button> -->
                </div>
            </div>
                <div class="row mt-1">
                    <div class="col-md-6" id="proctable" style="visibility: hidden">
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
                    <div class="col-md-6" id="atstable" style="visibility: hidden">
                        <h6>Used in ATS Routes</h6>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>#</th>
                                    <th>Ident</th>
                                    <th>Point1</th>
                                    <th>Point2</th>
                                </tr>
                            </thead>
                            <tbody id="atsinfo">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12"  id="noused" style="visibility: hidden">
                        <h6>this data has not been used in ATS Routes and Procedures</h6>
                    </div>
                </div>
            </div>
            </div>
        </div> 
 
</div>
@endsection
@section('footer_scripts')

<script src="{{ asset('template/assets/js/v-modal.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $.wmBox();
});
</script>
<script type="text/javascript">
var ats =@json($ats);
var proc =@json($proc);
var wpt =@json($wpt);
var parent =@json($parent);
var parentid =@json($parentid);
var atsstatus =@json($atsstatus);
var showedit=@json($id);
$('#atstable').hide();
$('#proctable').hide();
$('#asptable').hide();
$('#noused').hide();
$('#btnwptedit').hide();
// $('#btnwptdelete').hide();

if (showedit=="edit"){
    aboutvol('btnwptedit');
    // aboutvol('btnwptdelete');
    
}
// console.dir(ats)
// console.dir(proc)
// console.dir(wpt)
if (ats.length == 0 && proc.length == 0){
    aboutvol('noused');
    hasil ='<h6 align="center" style="color:#0A4EAB; font-weight:bolder;">:: ' + wpt[0].wpt_name + ' ' + wpt[0].definition + ' is not used in ATS Routes and Procedures ::</h6>'
    $("#noused").html(hasil);
}
// console.dir(wpt)
$('#wptid').html(wpt[0].wpt_name + ' ' + wpt[0].definition + ' Information' );
var wptcord= SetCoordinatebyGeom(wpt[0].geom)
$('#latid').html(wptcord.WGS[1]);$('#lonid').html(wptcord.WGS[0]);

if (ats.length > 0){
    aboutvol('atstable');
 var hasil='';
        ats.forEach(a=>{
            // console.log(a);
            hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-info" id="'+ a.ctry +'$atslist' + '" onClick="showmapdetail(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + a.ats_ident + '</td><td>' + a.point1  + '</td><td>' + a.point2 + '</td></tr>'
            $("#atsinfo").append(hasil);
            // hasil = '<tr><td> <div class="custom-control custom-checkbox">'+
            //         '<input type="checkbox" class="custom-control-input" id="'+a.ctry +'">'+
            //         '<label class="custom-control-label" for="'+a.ctry +'"></label>'+
            //     '</div></td><td>' + a.ats_ident + '</td><td>' + a.point1  + '</td><td>' + a.point2 + '</td></tr>'
            // $("#atsinfo").append(hasil);
        })
    
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
function waypointedit(){
    // console.dir(wpt[0].wpt_id)
    window.scrollTo(0,0);
    window.location.href = '/waypoint/' + wpt[0].wpt_id + '@edit@' + parent + '@' + parentid + '@'+atsstatus;
    // window.location.href = '/navaid/' + nav[0].nav_id;
    //     window.scrollTo(0,0);
}
function waypointdelete(){
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
                url: 'api/waypoint/remove/' + wpt[0].wpt_id,
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
    // console.log(id)
    var ddd = id.split( '$' );
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
                // window.open('/map.php?table='+ddd[1]+'&id='+ddd[0], 'Set Latitude and Longitude', params);
    vModal('/map.php?table='+ddd[1]+'&id='+ddd[0],"mdSize");
}

function backtolist(){
    history.back();

}




</script>
@endsection