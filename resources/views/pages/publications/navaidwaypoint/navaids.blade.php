@extends('layouts.app')

@section('template_title')
    ENR 4.1
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
            <h6 class="text-center"> ENR 4. RADIO NAVIGATION AIDS/SYSTEMS</h6>
            <h6 class="text-center">ENR 4.1 RADIO NAVIGATION AIDS - ENROUTE</h6>
            <div class="text-container">
                <h6 class="text-center" style="color:red" id="infoid"></h6>
            </div>
            <div class="card-tools">
                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
            </div>
            <div class="card-title mt-3">
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                                    <th>Ident</th>
                                    <th>type</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>frequency</th>
                                </tr>
                            </thead>
                            <tbody id="navlist">
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')
<script type="text/javascript">
var nav =@json($navaids);
var ch =@json($channel);
var navid =@json($navid);
var onreq=@json($onrequest);subid=@json($subid);
console.log(onreq,subid)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)
// console.log(navid)
nav.sort( ( a, b ) => ( a.nav_ident > b.nav_ident ) ? 1 : ( ( b.nav_ident > a.nav_ident ) ? -1 : 0 ) );
nav.forEach(n => {
    var ix = navid.findIndex(x=>x.point==n.nav_id)
    if (ix !== -1){
        
        this.hsl =SetCoordinatebyGeom(n.geom)
        this.cord = n
        this.cord['latwgs'] = this.hsl.WGS[0]
        this.cord['lonwgs'] = this.hsl.WGS[1]
        this.cord['lat'] = this.hsl.WGS[1]
        this.cord['lon'] = this.hsl.WGS[0]
        var frq = FreqFormat(n.freq,n.type,'DATA');
        var c='';
        if (n.type=='4'){
            c ='CH-' + ch.find( x => x.definition === frq ).id
            frq += '/' + c;
        }
        var  hsl= '<tr>'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">';
                       
                            if (showedit==true){
                                hsl+= '<a class="btn btn-dim btn-primary col-md-12" id='+ n.nav_id +' onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Info</a>';
                            }
                            hsl+= '<a class="btn btn-dim btn-info col-md-12" id='+ n.nav_id +' onclick="showmap(this.id)"><i class="icon ni ni-map"></i> Show</a>';
                            if (showedit==true){
                                hsl+= '<a class="btn btn-dim btn-danger col-md-12" id='+ n.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>';}
                hsl+=  '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ n.nav_ident +'</td>'+
            '<td>'+ n.definition +'</td>'+
            '<td>'+ n.nav_name +'</td>'+
            '<td>'+ this.hsl.WGS[1] + '<br>' + this.hsl.WGS[0] +'</td>'+
            '<td>'+ frq +'</td>'+
        '</tr>';
        $("#navlist").append(hsl)
    }
})



function NewData() {
    window.location.href = '/navaid/new@new data@enr41@new@a e r o s s';
}
    
function edit(data) {
    window.location.href = '/navaidinfo/'+ data +"@edit@enr41@" + data + '@';
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
                url: 'api/navaid/remove/' + data,
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



function backtolist(){
    window.location.href = '/aipsubmission/edit';
}

function showmap(id){
    //go to eaip.js
    showdetail(id+'$navaid');
    // console.log( id );
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // window.open('/map.php?table=atslist&id='+id, 'Set Latitude and Longitude', params)
}
</script>
@endsection