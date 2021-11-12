@extends('layouts.app')

@section('template_title')
    GEN 2.2
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
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="panel-heading mt-3">
                <h5 class="panel-title text-center">GEN 2.2 ABBREVIATIONS USED IN AIS PUBLICATIONS</h5>
            </div>
            <div class="panel-heading mt-3">
                <h6 class="panel-title text-center" style="color:red" id="infoid"></h6>
            </div>
            <div class="row" id="datalistarpt" style="visibility: visible">
                <div class="row">
                    <div class="col-md-12">
                        <button onclick="backtomenu()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                    </div>
                </div>
                <div class="col-md-12  mt-3">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                                <th>ID</th>
                                <th>Definition</th>
                            </tr>
                        </thead>
                        <tbody id="arptlist">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="datanewarpt" style="visibility: hidden">
                <div class="panel-body mt-3">
                <form action="api/abbr/save" method="POST" id="formulir">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="status" id="status">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>ID</strong>
                            <br>
                            <input id="ident" name="ident" onfocusout="checkdata(this.id)" type="text" class="form-control"/>
                        </div>
                        <div class="col-md-10">
                            <strong>Definition</strong>
                            <br />
                            <input name="definition" id="definition" onfocusout="checkdata(this.id)" type ="text" class="form-control">
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
var abbrtemp =@json($abbrtemp);
var abbr =@json($abbr);
var fld= ['id','ident', 'definition'];
var identlama='';deflama='';
var onreq=@json($onrequest);subid=@json($subid);
console.log(onreq)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)
// console.log(navtemp)
// var cord=SetCoordinatebyGeom(arpt[0].geom)
// var arptident=a.arpt_ident;
var npush=[];npsuhcurr=[];
abbrtemp.forEach(n=>{
        var  hsl= '<tr>'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">';
                        if (showedit==true){
                            hsl +=  '<a class="btn btn-dim btn-primary col-md-12" id='+ n.id +' onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                            '<a class="btn btn-dim btn-danger col-md-12" id='+ n.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Removed</a>';

                        }
                        hsl += '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ n.ident +'</td>'+
            '<td>'+ n.definition +'</td>'+
        '</tr>';
        $("#arptlist").append(hsl)
    
})
function backtomenu(){
    window.location.href = '/aipsubmission/edit';
}
function checkdata(id){
    var check=false;
    var dtid=$("#" + id).val();
    var datq='';
    if ($("#status").val()=='N'){
        check=true;
    }else{
        if (id=='ident'){
            datq={'ident':dtid}
            if (dtid !==identlama){
                check=true; 
            }
        }else{
            datq={'definition':dtid}
            if (dtid !==deflama){
                check=true; 
            }
        }

    }
    if (check==true){
    
        $.ajax({
                url: 'api/abbr/temp',
                data:datq,
                type: "json",
                method: "GET",

                success: function (result) {
                    console.log(result.data.length)
                    $.each(result.data, function (k, v) {
                        Swal.fire(
                        'Data Double !',
                        'Data has been avaliable :'+ v.ident + ' = '+ v.definition ,
                        'info'
                        )
                            // console.log('INI YG DIAMBIL',v)
                        
                    })
                }
            })
    }
}
function edit(data) {
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");
    $("#status").val('R');
    var idx=abbrtemp.findIndex(x=>x.id===Number(data))
    var stemp=abbrtemp[idx]
    idx=abbr.findIndex(x=>x.id===Number(data))
    var scurr=abbr[idx]
    compareisidata(fld,stemp,scurr);
    identlama=$("#ident").val();
    deflama==$("#definition").val();;
    window.scrollTo(0,0);
}
function NewData(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");
    $("#status").val('N');
    clearinput(fld);
    
}

function remove(id){
    $("#id").val(id);
    $("#status").val('D');
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
                    $('#formulir').submit();
                }
            })

}
function isback(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");

}

$('#btn_formulir').click(function() {
    if ($("#ident").val()=='' && $("#definition").val()==''){
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





</script>
@endsection