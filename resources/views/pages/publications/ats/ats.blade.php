@extends('layouts.app')

@section('template_title')
    {{$judul}}
@endsection

@section('head')
<style>
    .text-center {
    text-align: center;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-wrap">
            <h6 class="text-center">ENR 3 ATS ROUTES</h6>
            <h6 class="text-center">{{$judul}}</h6>
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 id='atsident'></h4>
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div>
            <div class="card-tools">
                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
            </div>
            <!-- <div class="runtext-container">
                <div class="main-runtext">
                    <marquee direction="" onmouseover="this.stop();" onmouseout="this.start();">
                    <div class="holder"> -->
                        <div class="text-container">
                            <h6 class="text-center" style="color:red" id="infoid"></h6>
                        </div>
                    <!-- </div>
                </div>
            </div> -->


            <div class="col-md-12  mt-3" id="atsmain" style="visibility: visible">
                <table class="datatable-init table table-bordered table-hover" id="table-content">
                    <thead class="thead-dark">
                        <tr align="center">
                            <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                            <th>Ident</th>
                            <th>Start Point</th>
                            <th>End Point</th>
                            <th>Level</th>
                        </tr>
                    </thead>
                    <tbody id="atslist">
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div id="removeats" style="visibility:hidden">
            <form action="api/ats/save/temp" method="post"  enctype="multipart/form-data" id="atsremoveform">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="ats_id" id="ats_id">
                <input type="hidden" name="ctry" id="ctry">
                <input type="hidden" name="seq_424" id="seq_424">
                <input type="hidden" name="point" id="point">
                <input type="hidden" name="point2" id="point2">
                <input type="hidden" name="insert" id="insert">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="type" id="type">
                <input type="hidden" name="ats_ident" id="ats_ident">
                <input type="hidden" name="lat1" id="lat1">
                <input type="hidden" name="lon1" id="lon1">
                <input type="hidden" name="lat2" id="lat2">
                <input type="hidden" name="lon2" id="lon2">
                <input type="hidden" name="subid" id="subid">
                </form>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
var ats =@json($atss);isdetail=false;atsdet=[];
var onreq=@json($onrequest);subid=@json($subid);
// console.log(subid)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)
    ats.forEach(a=>{;
    // function atsfunction(a,idx){
        // console.log(a)
        hasil = '<tr class="nk-tb-item">'+
        '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                        '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-info" id="'+ a.ctry +'" onclick="showmap(this.id)"><i class="icon ni ni-map"></i> Show</a>';
                            if (showedit==true){
                                hasil += '<a class="btn btn-dim btn-primary" id="'+ a.ctry +'" onclick="toatsdetail(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Detail</a>'+
                                '<a class="btn btn-dim btn-warning" id="'+ a.ctry +'$'+ats.ats_ident+'" onclick="rename(this.id)"><i class="icon ni ni-edit"></i> Rename '+ a.ident +'</a>'+
                                '<a class="btn btn-dim btn-danger" id="'+ a.ctry +'" onclick="removeident(this.id)"> <i class="icon ni ni-delete-fill"></i> Remove</a>';
                            }
                    hasil +='</ul>'+
                    '</div>'+
            '</div>'+
        '</td>'+
        '<td>' + a.ats_ident + '</td><td>' + a.point_1 + '</td><td>' + a.point_2 + '</td><td>' + a.level + '</td></tr>';
        $("#atslist").append(hasil);
        
    });
function removeident(id){
   
    Swal.fire({
        title: 'Delete Data',
        text: "The Enroute will be deleted!",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'
    }).then((result) => {
        if (result.value) {
            var idx = ats.findIndex(x => x.ctry===id);
            // console.log(ats[idx])

                if (idx !== -1){
                    
                    var ad=ats[idx];
                    console.log(ats[idx]);
    
                    $("#id").val(ad.id);
                    $("#ats_id").val(ad.ats_id);
                    $("#subid").val(subid);
                    $("#status").val('R');
                    $("#ctry").val(ad.ctry);
                    $("#seq_424").val(ad.seq_424);
                    $("#ats_ident").val(ad.ats_ident);
                    $("#type").val(ad.type);
                    $("#point").val(ad.point);
                    $("#point2").val(ad.point2);
                    $("#insert").val('remove');
                    $("#atsremoveform").submit();
                }
            

                    // Swal.fire(
                    //     'Delete!',
                    //     'Data Status has been Deleted.',
                    //     'success'
                    // );
            
        }else{
            location.reload();

        }
    })
}
function rename(id){
    var inputOptions =''; // Define like this!
    // console.log(id)
    var idd=id.split('$');
    var idx = ats.findIndex(x => x.ctry===idd[0]);
    // console.log(ats[idx])

    Swal.fire({
        title: "Rename",
        text : 'Change Enroute ident',
        input: 'text',
        inputOptions: inputOptions,
        showCancelButton: true,
    }).then((result) => {
        if (result.value){

            Swal.fire({
                title: 'Are you sure?',
                text: "Ident will be rename",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Renamed!'
            }).then((yesno) => {
                if (yesno.value) {
                    // console.log(this.frequpdate,result.value)
                    var ad=ats[idx];
                    // console.log(ats[idx]);
    
                    $("#id").val(ad.id);
                    $("#ats_id").val(result.value);
                    $("#status").val('R');
                    $("#ctry").val(ad.ctry);
                    $("#ats_ident").val(result.value);
                    $("#type").val(ad.type);
                    $("#insert").val('rename');
                    $("#atsremoveform").submit();
                }
            })
        }

    });
}
function toatsdetail(key){
    window.location.href = '/atsdetail/'+ key; 
}

// function editsegment(id){
//     var idd=id.split('$');
//     // console.log(idd);
//     window.location.href = '/editats/'+ idd[0] + '@edit@'+idd[1];
// }
function NewData(){
    window.scrollTo(0,0);
    window.location.href = '/editats/new@new@';
}

function setMapPoint(id){
    showdetail(id+'$atsseg');
}

function backtolist(){
   
        window.scrollTo(0,0);
        window.location.href = '/aipsubmission/edit';
    
}
function remove(id){
    var idd=id.split('$');
    let idx=-1;
    let ctid='';
    Swal.fire({
        title: 'Delete Data',
        text: "The data status will be deleted!",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'
    }).then((result) => {
        if (result.value) {
            idx = atsdet.findIndex(x => x.ats_id===idd[0]);
            // console.log(id,ats,atsdet,idd[0],idx)
            if (atsdet.length==1){
                Swal.fire(
                        'Cannot Deleted!',
                        'Cannot be deleted because the data is only one segment.',
                        'info'
                    );
                    location.reload();
            }else{

                if (idx !== -1){
                    
                    var ad=atsdet[idx];
                    console.log(atsdet[idx]);
               
                    ctid=ad.ctry;
                    var pnt1geom,pnt2geom;
                    if (ad.wpt1.length >0){
                        pnt1geom=ad.wpt1[0].geom;
                    }else{
                        pnt1geom=ad.nav1[0].geom;
                    }
                    if (ad.wpt2.length >0){
                        pnt2geom=ad.wpt2[0].geom;
                    }else{
                        pnt2geom=ad.nav2[0].geom;
                    }
                    crd1=SetCoordinatebyGeom(pnt1geom);
                    crd2=SetCoordinatebyGeom(pnt2geom);
                    $("#lat1").val(crd1.Decimal[1]);
                    $("#lon1").val(crd1.Decimal[0]);
                    $("#lat2").val(crd2.Decimal[1]);
                    $("#lon2").val(crd2.Decimal[0]);
    
                    $("#id").val(ad.id);
                    $("#ats_id").val(ad.ats_id);
                    $("#status").val('R');
                    $("#ctry").val(ad.ctry);
                    $("#seq_424").val(ad.seq_424);
                    $("#ats_ident").val(ad.ats_ident);
                    $("#type").val(ad.type);
                    $("#point").val(ad.point);
                    $("#point2").val(ad.point2);
                    $("#insert").val(idd[1]);
                    $("#atsremoveform").submit();
                    showmap(ctid)
                }
            }

                    // Swal.fire(
                    //     'Delete!',
                    //     'Data Status has been Deleted.',
                    //     'success'
                    // );
            
        }else{
            location.reload();

        }
    })
   
}
function showmap(id){
    //go to eaip.js
    showdetail(id+'$atslist');
    // console.log( id );
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // window.open('/map.php?table=atslist&id='+id, 'Set Latitude and Longitude', params)
}
</script>
@endsection