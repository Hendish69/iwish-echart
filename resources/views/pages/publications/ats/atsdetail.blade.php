@extends('layouts.app')

@section('template_title')
    Airways
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
            <div class="col-md-6">
                <strong>Ats ident</strong>
                <br>
                <input type="text" class="form-control" onfocusout="searchats()" name="ats_ctry" id="ats_ctry" >
            </div>
            <div class="col-md-12  mt-3" id='maindetail'>
                <table class="table table-bordered table-hover" id="table-content">
                    <thead class="thead-dark">
                    <tr align="center">
                        <th>#</th>
                        <th>Point 1</th>
                        <th>Point 2</th>
                        <th>Distance</th>
                        <th>Track Out</th>
                        <th>Track in</th>
                        <th>Upper</th>
                        <th>Lower</th>
                    </tr>
                    </thead>
                    <tbody id="atsdetail">
                        
                    </tbody>
                </table>
            </div>
            <div class="card-tools">
                <buton  id="{{$atstemp[0]->ctry}}" onclick="showmap(this.id)" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Show</buton>
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
                </form>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
 window.scrollTo(0,0);
var ats =@json($atstemp);atscurr =@json($atscurr);
$("#ats_ctry").val(ats[0].ats_ident)
loaddata(ats)
function loaddata(data){
    $("#atsdetail").empty();

    data.forEach(function (a, idx){
        // console.log(a)
        if (a.ats_ident.length < 7){
            $('#atsident').html(a.ats_ident+ '<br>(' + ConverNumChart(a.ats_ident)+')');
        }else{
            $('#atsident').html(a.ats_ident+ '<br>');
        }
        if (a.track_in==null){
            a.track_in='';
        }
        if (a.track_out==null){
            a.track_out='';
        }
    
        hasil = '<tr>'+
        '<td class="tb-tnx-action">'+
        '<div class="dropdown">'+
        '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
        '<div class="dropdown-menu dropdown-menu-left dropdown-menu-lg">'+
        '<ul class="link-list-plain">'+
        '<a class="btn btn-dim btn-primary" id="'+ a.ats_id +'$curr" onclick="editsegment(this.id)">Edit</a>';
        if (idx==0){
            hasil +='<a class="btn btn-dim btn-light" id="'+ a.ats_id +'$bp1" onclick="editsegment(this.id)"><i class="icon ni ni-arrow-up-circle-fill"></i> Insert Before '+ a.point_1+'</a>';
        }
        
        hasil +='<a class="btn btn-dim btn-success" id="'+ a.ats_id +'$ap1" onclick="editsegment(this.id)"><i class="icon ni ni-arrow-right-circle-fill"></i> Insert After '+ a.point_1+'</a>'+
            '<a class="btn btn-dim btn-danger" id="'+ a.ats_id +'$rp1" onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove '+ a.point_1+'</a>&nbsp;'+
        '<a class="btn btn-dim btn-secondary"  id="'+ a.ats_id +'$ap2" onclick="editsegment(this.id)"><i class="icon ni ni-arrow-down-circle-fill"></i> Insert After '+ a.point_2+'</a>'+ 
        '<a id="'+ a.ats_id +'$rp2" class="btn btn-dim btn-danger" onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove '+ a.point_2+'</a>&nbsp;'+
        '<a id="'+ a.ats_id +'" onclick="setMapPoint(this.id)" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Show '+ a.point_1+' to '+ a.point_2+'</a>'+
        '</ul>'+
        '</div>'+
        '</div>'+
        '</td>'+
        '<td>' + a.point_1 + '</td><td>' + a.point_2 + '</td><td>' + a.dist + '</td><td>' + a.track_out + '</td><td>' + a.track_in + '</td><td>' + a.maa + '</td><td>' + a.mfa + '</td></tr>'
        $("#atsdetail").append(hasil);
    })
}
var input = document.getElementById("ats_ctry");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    searchats();
    // document.getElementById("ats_ctry").onchange();
  }
});
function searchats(){
    var ident=$("#ats_ctry").val().toUpperCase() + '_ID'
    window.scrollTo(0,0);
    window.location.href = '/atsdetail/' + ident;
    // var dt=[];
    // $.ajax({
    //     url: '/api/ats/temp',
    //     data: {'ctry' : ident,'sort':'seq_424:asc'},
    //     type: "json",
    //     method: "GET",

    //         success: function (result) {
    //             var jmlwpt=result.data.length
               
    //                 console.log(jmlwpt)
                
    //             $.each(result.data, function (k, v) {
    //                 // console.log(v);
    //                 dt.push(v)
    //                 loaddata(dt)
                    
    //             })
    //         }
    // })
    // window.scrollTo(0,0);
}


function editsegment(id){
    var idd=id.split('$');
    // console.log(idd);
    window.location.href = '/editats/'+ idd[0] + '@edit@'+idd[1];
}
function NewData(){
    window.scrollTo(0,0);
    window.location.href = '/editats/new@new@';
}

function setMapPoint(id){
    showdetail(id+'$atsseg');
}

function backtolist(){
    // console.log(ats[0].ats_ident)
    var page='61'
    switch (ats[0].type) {
        case 'V':
            page='64'
            break;
        case 'R':
            page='63'
            break;
        default:
            if (ats[0].ats_ident.substr(0,1) !== 'W'){
                page='62'
            }
            break;
    }
   
        // history.back();

        window.location.href = '/listats/'+page;
    
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
            idx = ats.findIndex(x => x.ats_id===idd[0]);
            // console.log(id,ats,atsdet,idd[0],idx)
            if (ats.length==1){
                Swal.fire(
                        'Cannot Deleted!',
                        'Cannot be deleted because the data is only one segment.',
                        'info'
                    );
                    location.reload();
            }else{

                if (idx !== -1){
                    
                    var ad=ats[idx];
                    console.log(ats[idx]);
               
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