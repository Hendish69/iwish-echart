@extends('layouts.app')

@section('template_title')
    ENR 4.3
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-wrap">
            <h6 class="text-center"> ENR 4. RADIO NAVIGATION AIDS/SYSTEMS</h6>
            <h6 class="text-center">ENR 4.3 NAME CODE DESIGNATORS FOR SIGNIFICANT POINTS</h6>
            <div class="text-container">
                <h6 class="text-center" style="color:red" id="infoid"></h6>
            </div>
            <div class="card-tools">
                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
            </div>
            <div class="card-title">
            <div class="col-md-12" name="Vol-Data" id="Vol-Data" onclick="navcehck()"></div>
            </div>
            <div class="row mt-1" id="atsmain" style="visibility: visible">
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover">
                        <thead class="thead-dark" id="judultable">
                        </thead>
                        <tbody id="navlist">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">

var wpt =@json($waypoints);
var wptenr =@json($waypoint);
var onreq=@json($onrequest);subid=@json($subid);
// console.log(wpt,subid)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)
    $("#judultable").empty();
    jdl ='<tr align="center">' +
        '<th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>'+
        '<th>Ident</th>'+
        '<th>Type</th>'+
        '<th>Name</th>'+
        '<th>Coordinates</th>'+
    '</tr>'
    $("#judultable").append(jdl);
    wpt.sort( ( a, b ) => ( a.wpt_name > b.wpt_name ) ? 1 : ( ( b.wpt_name > a.wpt_name ) ? -1 : 0 ) );
    wpt.forEach(a=>{;
        var ix = wptenr.findIndex(x=>x.point==a.wpt_id)
        if (ix !== -1){
            var c1= SetCoordinatebyGeom(a.geom);
            var cord =c1.WGS[1] + '<br>' +c1.WGS[0];
            hasil = '<tr class="nk-tb-item">'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                        '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                        '<ul class="link-list-plain">';
                        if (showedit==true){
                            hasil +=  '<a class="btn btn-dim btn-primary col-md-12" id='+ a.wpt_id +' onclick="EditWpt(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Info</a>';

                        }
                        hasil += '<a class="btn btn-dim btn-info col-md-12" id='+ a.wpt_id +' onclick="showmap(this.id)"><i class="icon ni ni-map"></i> Show</a>';
                        if (showedit==true){
                            hasil += '<a class="btn btn-dim btn-danger col-md-12" id='+ a.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>';}
                hasil +=    '</ul>'+
                        '</div>'+
                '</div>'+
            '</td>'+
            '<td>' + a.wpt_name + '</td><td>' + a.definition + '</td><td>' + a.desc_name + '</td><td>' + cord + '</td></tr>';
            $("#navlist").append(hasil);
        }
    });

   

function NewData(){
    window.scrollTo(0,0);
    window.location.href = '/waypoint/new@new@enr43@@';
}

function EditWpt(key){
    window.scrollTo(0,0);
    window.location.href = '/waypointinfo/' + key + '@edit@enr43@' +key + '@';
}

function backtolist(){
    window.location.href = '/aipsubmission/edit';
}
function remove(data) {
            // console.log(data,' NAVAID')
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
                url: 'api/waypoint/remove/' + data,
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
function showmap(id){
    //go to eaip.js
    showdetail(id+'$waypoint');
    // console.log( id );
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // window.open('/map.php?table=atslist&id='+id, 'Set Latitude and Longitude', params)
}
</script>
@endsection