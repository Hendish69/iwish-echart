@extends('layouts.app')

@section('template_title')
    ENR 2.1
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
                <h6 class="text-center" id="titleasp">{{$titel}}</h6>
                <h6 class="text-center" id="titleasp2"></h6>
                <div class="text-container">
                    <h6 class="text-center" style="color:red" id="infoid"></h6>
                </div>
            <div class="card-tools">
                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
            </div>
            <!-- <div class="col-md-12" name="Vol-Data" id="Vol-Data" onclick="codchecked()"></div> -->
            <div class="row mt-1" id="aspmain" style="visibility: visible">
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover">
                        <thead class="thead-dark" id="judultable">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody id="asplist">
                        </tbody>
                    </table>
                </div>
            </div>
            <form action="api/airspace/save" method="post"  enctype="multipart/form-data" id="aspform">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="airspace_type" id="airspace_type">
                <input type="hidden" name="ats_airspace_id" id="ats_airspace_id">
                <input type="hidden" name="arpt_ident" id="arpt_ident">
                <input type="hidden" name="parent" id="parent" value="{{$parent}}">
                <input type="hidden" name="status" id="status" value='D'>
            </form>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
var asp =@json($airspace);
var cod =@json($cod);parent=@json($parent);
var onreq=@json($onrequest);subid=@json($subid);
console.log(onreq,parent)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)
if (parent=='ENR2.1'){
    $("#titleasp2").html('ENR 2.1 FIR, UTA, CTA, TMA')  
}
asp.forEach(a=>{
    var show=true;
    if (parent=='ENR2.1'){
        if (a.airspace_type == 'AFIZ' || a.airspace_type == 'ATZ' || a.airspace_type == 'CTR'){
            show=false;
        }
    }
    // console.log(a)
    if (show){
        hasil =
                '<tr class="nk-tb-item">'+
        '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                        '<ul class="link-list-plain">'+
                        '<a class="btn btn-dim btn-info" id="'+ a.ats_airspace_id +'" onclick="showmap(this.id)"><i class="icon ni ni-map"></i> Show</a>';
                            if (showedit){
                                hasil += '<a class="btn btn-dim btn-primary" id="'+ a.ats_airspace_id +'" onclick="editsegment(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Detail</a>';
                            }
                            
                            if (parent !== 'ENR2.1'){
                                hasil +=  '<a class="btn btn-dim btn-success" id="'+ parent +'" onclick="ShowText(this.id)"> <i class="icon ni ni-text-a"></i> Show Text</a>';
                            }
                            if (showedit){
                            hasil +=  '<a class="btn btn-dim btn-warning" id="'+ a.ats_airspace_id +'" onclick="remove(this.id)"> <i class="icon ni ni-delete-fill"></i> Remove</a>';
                            }
                            hasil += '</ul>'+
                    '</div>'+
            '</div>'+
        '</td>'+
        '<td>' + a.airspace_name + '</td><td>' + a.airspace_type + '</td><td>' + a.ats_unit + '</td></tr>';
        $("#asplist").append(hasil);
    }
    // console.log(a)
    
});

// }
function ShowText(id){
    window.scrollTo(0,0);
    window.location.href = '/show217/217/'+parent;
}
function codchecked()
{
    $("#judultable").empty();
    $("#asplist").empty();
    $( '#isi-table tr' ).empty();
    jdl = 
            '<tr align="center">' +
                '<th>#</th>'+
                '<th>Name</th>'+
                '<th>Type</th>'+
                '<th>Unit</th>'+
            '</tr>';
            $("#judultable").append(jdl); 
    $( '.checkbox:checkbox:checked' ).each( function ( i )
    {
        console.log('AspChecked', $( this ).val() );
        listairspace($(this).val());
    })
   
}
function NewData(){
    window.scrollTo(0,0);
    window.location.href = '/airspace/new@newdata@'+parent;
}
function editsegment(id){
    console.log(id)
    window.scrollTo(0,0);
    window.location.href = '/airspace/'+ id +'@edit@'+parent;
}
function backtolist(){
    if (parent=='ENR2.1'){
        window.location.href = 'aipsubmission/edit';

    }else{
        window.location.href = 'editairport/'+parent;
    }
   
}
function remove(id){
    Swal.fire({
        title: 'Delete Data',
        text: "The Airspace will be deleted!",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'
    }).then((result) => {
        if (result.value) {
            var idx=asp.findIndex(a=>a.ats_airspace_id===id);
            console.log(asp[idx]);
            var aspr=asp[idx];
            $("#id").val(aspr.id);
            $("#airspace_type").val(aspr.airspace_type);
            $("#arpt_ident").val(aspr.arpt_ident);
            $("#ats_airspace_id").val(id);
            $("#aspform").submit();

            
        }else{
            location.reload();

        }
    })
    //go to eaip.js
    
    // removeobject(id);
}
function showmap(id){
    //go to eaip.js
    showdetail(id+'$airspace');
    // console.log( id );
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // window.open('/map.php?table=asplist&id='+id, 'Set Latitude and Longitude', params)
}
</script>
@endsection