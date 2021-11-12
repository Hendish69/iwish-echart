@extends('layouts.app')

@section('template_title')
    Nr
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body mt-3">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Publication Number</h3>
                    </div>
                </div>
            </div>
            <div class="row mt" id="srclist" style="visibility: visible">
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th>Nr</th>
                                <th>Types</th>
                                <th>Publication Date</th>
                                <th>Effective Date</th>
                            </tr>
                        </thead>
                        <tbody id="arptlist">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt" id="srcform" style="visibility: visible">
                <div class="col-md-12">
                <form action="api/sourcenr/save" method="post"  enctype="multipart/form-data" id="sourcenrform">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="publish" id="publish" value='Y'>
                <input type="hidden" name="status" id="status">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>Number</strong>
                            <br>
                            <input id="src_id" name="src_id" onfocusout="checkwptdouble()" type="text" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <strong>Type</strong>
                            <br>
                            <select id="src_type" name="src_type" class="form-control">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <strong>Publication Date</strong>
                            <br>
                            <input id="pub_date" name="pub_date" onchange="getairac()" type="date" class="form-control"  value="{{ date('Y-m-d') }}">
                        </div>
                    
                        <div class="col-md-3">
                            <strong>Effective date</strong>
                            <br>
                            <input id="eff_date" name="eff_date" type="date"  class="form-control" value="{{ date('Y-m-d') }}">
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
                        <a onclick="update()" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</a>
                        
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
$("#srcform").hide();
var fld = ['id','src_id', 'src_type', 'pub_date','eff_date','publish'];srct=[];
var sourcelist= [{
                key: 'AIRAC AIP AMDT',
                value: 'AIRAC AIP AMDT'
            }, {
                key: 'AIC',
                value: 'AIC'
            }, {
                key: 'AIRAC AIP SUPP',
                value: 'AIRAC AIP SUPP'
            }, {
                key: 'AIP AMDT',
                value: 'AIP AMDT NON AIRAC'
            }, {
                key: 'AIP SUPP',
                value: 'AIP SUPP NON AIRAC'
            }];
sourcelist.forEach(t=>{
    $("#src_type").append('<option value="'+t.key+'">'+t.value+'</option>');
})
var src =@json($source);
    src.forEach(v=>{
       var  pd=DateFormat(new Date(v.pub_date),false,true);
       var  ed=DateFormat(new Date(v.eff_date),false,true);
        var  hsl= '<tr>'+
        '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                    '<ul class="link-list-plain">'+
                        '<a class="btn btn-dim btn-primary col-md-12" id='+ v.id +' onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                        '<a class="btn btn-dim btn-danger col-md-12" id='+ v.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Removed</a>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</td>'+
        '<td >'+ v.src_id +'</td>'+
        '<td >'+ v.src_type +'</td>'+
        '<td >'+ pd +'</td>'+
        '<td >'+ ed +'</td>'+
    '</tr>';
    $("#arptlist").append(hsl);
            
        
    });
function update(){
    var checkrwy=false;
    var fldbew = ['src_id','src_type', 'pub_date','eff_date'];
    if  ($("#status").val()=='N'){
        checkrwy =checknewdata(fldbew);
    
    }else if ($("#status").val()=='R'){
        console.log(srct)
        checkrwy =checkupdatedata(fldbew,srct);
        console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#sourcenrform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        backtolist();
    }
   
}
function edit(id){
    aboutvol('srcform'); aboutvol('srclist');
    $("#status").val('R');
    var ix =src.findIndex(x=>x.id===Number(id))
    srct=src[ix]; srcc=src[ix]
    console.log(id)
    compareisidata(fld,srct,srcc);
   
}
function backtolist(){
    aboutvol('srcform'); aboutvol('srclist');
}

function NewData(){
    aboutvol('srcform'); aboutvol('srclist');
    $("#src_id").val('');
    $("#status").val('N');
    $("#publish").val('Y');
}
function checkwptdouble(){
    var si= $("#src_id").val();
    var ix =src.findIndex(x=>x.src_id===si)
    if (ix !== -1){
        Swal.fire(
            'Warning!',
            'Data Double',
            'warning'
        );
    }
}
function getairac() {
    let dt = document.getElementById("pub_date").value;
    // $("#sourcenr").val('');

    // if (ismajor==true){
        qry ={'maj_pub': dt};
    // }else{
    //     qry ={'min_pub': dt};
    // }
//    console.log(qry)
    $.ajax({
            url: 'api/airac',
            data: qry,
            type: "json",
            method: "GET",

            success: function (result) {
                // console.log(result)
                if (result.data.length ==0){
                    qry ={'min_pub': dt};
                    $.ajax({
                        url: 'api/airac',
                        data: qry,
                        type: "json",
                        method: "GET",

                        success: function (result) {
                            $.each(result.data, function (k, tbl) {
                                // console.log(tbl);
                                $("#eff_date").val(tbl.eff_date);
                            })
                        }
                    })
                }
                $.each(result.data, function (k, tbl) {
                    // console.log(tbl);
                    $("#eff_date").val(tbl.eff_date);
                })
        }
    })
}
</script>
@endsection