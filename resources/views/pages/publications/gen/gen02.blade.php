@extends('layouts.app')

@section('template_title')
    {{$subid}}
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
                        <h6>{{$judul}}</h6>
                    </div>
                </div>
            </div>
            <div class="row mt" id="srclist" style="visibility: hidden">
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
            <div id="gen26" style="visibility: hidden">
                <div class="nk-content-inner">
                    <div class="nk-content-body mt-3" id="freetext">
                    </div>
                </div>
            </div>
            <div class="row mt" id="srcform" style="visibility: hidden">
                <div class="col-md-12">
                <form action="../api/sourcenr/save" method="post"  enctype="multipart/form-data" id="sourcenrform">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="publish" id="publish" value='Y'>
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="status_supp" id="status_supp">
                <input type="hidden" name="id_supp" id="id_supp">
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
                        <div class="col-md-12" id="suppsubject" style="visibility: hidden">
                            <strong>Subject</strong>
                            <br>
                            <textarea id="subject" name="subject" type="text"  class="form-control"></textarea>
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
$("#srcform").hide();$("#suppsubject").hide();$("#gen26").hide();$("#srclist").hide();
var fld = ['id','src_id', 'src_type', 'pub_date','eff_date','publish'];srct=[];
var supp = ['id', 'src_id','vol','subject','affected_to','period_start', 'period_end','period_start_time', 'period_end_time','cancel_record','date_inserted', 'inserted_by'];
var sourcelist= [{
                key: 'AIRAC AIP AMDT',
                value: 'AIRAC AIP AMDT'
            }, {
                key: 'AIC',
                value: 'AIC'
            }, {
                key: 'AIP AMDT',
                value: 'AIP AMDT NON AIRAC'
            },{
                key: 'AIRAC AIP SUPP',
                value: 'AIRAC AIP SUPP'
            },{
                key: 'AIP SUPP',
                value: 'AIP SUPP NON AIRAC'
            }];

sourcelist.forEach(t=>{
    var isi='<option value="'+t.key+'">'+t.value+'</option>';
    $("#src_type").append(isi);
    
})

var src =@json($source);aipid=@json($id);
console.log(aipid,'aipid')
if (aipid=='34'){
    aboutvol("gen26");
    $("#srclist").hide();

        this.isi = '<br>'
        this.isi += '<p align="center"><b><i>See e-AIP GEN 2.6 CONVERSION TABLE</i></b></p><br>'
    $("#freetext").append(this.isi);
}else{
    aboutvol("srclist");
    if (aipid=='17'){
        asup='AMDT';
    }else if (aipid=='18'){
        asup='SUPP';
    }
    
        src.forEach(v=>{
            if (v.src_type.includes(asup)){
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
    
            }
                
            
        });
}
function update(){
    var checkrwy=false;
    var fldbew = ['src_id','src_type', 'pub_date','eff_date'];
    if (aipid=='18'){
        fldbew = ['src_id','src_type', 'pub_date','eff_date','subject'];
    }
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
    console.log(id,aipid)
    var ix =src.findIndex(x=>x.id===Number(id))
    srct=src[ix]; srcc=src[ix]
    aboutvol('srclist');
    aboutvol('srcform');
    $("#status").val('R');
    console.log(id)
    compareisidata(fld,srct,srcc);
   if (aipid=='18'){
    if ($("#suppsubject").is(':visible')==false){
        aboutvol('suppsubject');
    }
       
        $("#status_supp").val('R');
        console.log(id,srct);
        if (srct.note.length == 0){
            $("#subject").val('')
            $("#status_supp").val('N');
        }else{
            $("#id_supp").val(srct.note[0].id)
            $("#subject").val(srct.note[0].subject)
        }
        // compareisidata(supp,srct,srcc);
    }
   window.scroll(0,0);
}
function backtolist(){
    if ($("#srcform").is(':visible')==true){
            aboutvol('srcform');
        }
    if ($("#suppsubject").is(':visible')==true){
        aboutvol('suppsubject');
    }
    if ($("#srclist").is(':visible')==false){
        aboutvol('srclist');
    }
   
}

function NewData(){
    $("#status").val('N');
    aboutvol('srcform'); aboutvol('srclist');
    $("#src_id").val('');
    $("#publish").val('Y');
    if (aipid=='18'){
        aboutvol('suppsubject');
        $("#status_supp").val('N');
    }
   
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
            url: '../api/airac',
            data: qry,
            type: "json",
            method: "GET",

            success: function (result) {
                // console.log(result)
                if (result.data.length ==0){
                    qry ={'min_pub': dt};
                    $.ajax({
                        url: '../api/airac',
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