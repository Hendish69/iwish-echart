@extends('layouts.app')

@section('template_title')
    @if ($id=='70')
        ENR 5.1
    @else
        ENR 5.2
    @endif
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
                <h6 class="text-center">ENR 5 NAVIGATION WARNING</h6>
                <h6 class="text-center" id='aspident'></h6>
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
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                                <th>Ident</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Lower</th>
                                <th>Upper</th>
                            </tr>
                        </thead>
                        <tbody id="asplist">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">
var asp =@json($suas);
var cod =@json($cod);
var codid =@json($id);codis=[];
var onreq=@json($onrequest);subid=@json($aipcode);
// console.log(onreq)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)
// console.log(cod,asp);
if (codid==70){
    $("#aspident").html('ENR 5.1 PROHIBITED, RESTRICTED AND DANGER AREAS')
    codis=[cod[1],cod[3],cod[4]];
}else{
    $("#aspident").html('ENR 5.2 MILITARY EXERCISE AND TRAINING AREAS')
    codis=[cod[0],cod[2],cod[5],cod[6]];
}
for (let i=0;i<codis.length;i++){
    asp.forEach(a=>{;
        if (a.definition == codis[i]){
            // console.log(a)
            hasil =
                    '<tr class="nk-tb-item">'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                        '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                            '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-info" id="'+ a.suas_id +'" onclick="showmap(this.id)"><i class="icon ni ni-map"> </i> Show</a>';
                            if (showedit){
                                hasil +=  '<a class="btn btn-dim btn-primary" id="'+ a.suas_id +'" onclick="editsegment(this.id)"><i class="icon ni ni-edit"></i> Detail</a>'+
                                '<a class="btn btn-dim btn-warning" id="'+ a.suas_id +'" onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>';

                            }
                            hasil += '</ul>'+
                        '</div>'+
                '</div>'+
            '</td>'+
            '<td>' + a.suas_ident + '</td><td>' + a.suas_name + '</td><td>' + a.definition + '</td><td>' + a.lower + '</td><td>' + a.upper + '</td></tr>';
            $("#asplist").append(hasil);
        }
        // console.log(a)
        
    });
}

// codchecked();


   


// function codchecked()
// {
//     $("#judultable").empty();
//     $("#asplist").empty();
//     $( '#isi-table tr' ).empty();
//     jdl = 
//             '<tr align="center">' +
//                 '<th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>'+
//                 '<th>Ident</th>'+
//                 '<th>Name</th>'+
//                 '<th>Type</th>'+
//                 '<th>Lower</th>'+
//                 '<th>Upper</th>'+
//             '</tr>';
//             $("#judultable").append(jdl); 
//     $( '.checkbox:checkbox:checked' ).each( function ( i )
//     {
//         console.log('AspChecked', $( this ).val() );
//         listairspace($( this ).val());
//     })
   
// }


function NewData(){
    window.scrollTo(0,0);
    window.location.href = '/suas/new@newdata';
}
function editsegment(id){
    window.scrollTo(0,0);
    window.location.href = '/suas/'+ id +'@edit';
}
function backtolist(){
 
        // window.scrollTo(0,0);
        window.location.href = '/aipsubmission/edit';
    
}
function remove(id){
    //go to eaip.js
    removeobject(id);
}
function showmap(id){
    //go to eaip.js
    showdetail(id+'$suas');
    // console.log( id );
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // window.open('/map.php?table=asplist&id='+id, 'Set Latitude and Longitude', params)
}
</script>
@endsection