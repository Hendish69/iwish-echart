@extends('layouts.app')

@section('template_title')
@switch($chart)
    @case (47)
        STAR
        @break
    @case ("46")
        SID
        @break
    @case (45)
        IAP
        @break
@endswitch
@endsection

@section('head')

@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="panel-title" id="judullist"></h6>
                    </div>
                </div>
                <div class="panel-heading mt-1">
                    <button onclick="backtomenu()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                </div>
                <div class="col-md-4">
                    <strong>ICAO</strong>
                    <br>
                    <input type="text" class="form-control" onfocusout="searchats()" name="icao" id="icao" placeholder= "search Airport by location indicator..."">
                </div>
            </div>
                <ul class="nav nav-tabs" id="tabMenu">
                    <li class="nav-item">
                        <a id="app_judul" class="nav-link active" data-toggle="tab" href="#tabItem1"><span>Transition</span></a>
                    </li>
                    <li class="nav-item tab-pane{{old('tab') == 'tabItem2' ? ' active' : null}}">
                        <a class="nav-link"  data-toggle="tab" href="#tabItem2"><span>Procedures</span></a>
                    </li>
                </ul>
            <div class="tab-content tabairspace" id="tabasp">
                <div class="tab-pane active" id="tabItem1">
                    <div class="col-md-12 mt-3">
                        <div class="row" id="rwytrans">
                        
                        </div>
                    </div>
                    <div class="row" id="datalisttrans" style="visibility: visible">
                        <div class="col-md-12 mt-3">
                            <table class="table table-bordered table-hover" id="table-content">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                        <th>Name</th>
                                        <th>RNAV</th>
                                        <th>Runway</th>
                                        <th>Transition Routes</th>
                                    </tr>
                                </thead>
                                <tbody id="translist">
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem2">
                    <div class="col-md-12 mt-3">
                        <div class="row" id="rwyproc">
                        
                        </div>
                    </div>
                    <div class="row" id="datalistproc" style="visibility: visible">
                        <div class="col-md-12 mt-3">
                            <table class="table table-bordered table-hover" id="table-content">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewDataproc()"><i class="icon ni ni-plus"></i> Add</a></th>
                                        <th>Name</th>
                                        <th>Procedure Text</th>
                                        <th>RNAV</th>
                                        <th>Runway</th>
                                    </tr>
                                </thead>
                                <tbody id="proclist">
                                </tbody>
                               
                            </table>
                        </div>
                    </div>
                </div>
                <form action="../api/transition/temp/save" method="POST" id="trans_delete">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="arpt_ident" id="arpt_ident">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="proc_id" id="proc_id">
                        <input type="hidden" name="status" id="status">
                        <input type="hidden" name="chart_type" id="chart_type">
                </form>
            <div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show');
});

var transtemp =@json($transtemp);
var proctemp =@json($proctemp);
var codchart=@json($chart);arpt=@json($arpt);
// console.log(arpt)
$("#app_judul").html('Transition')
var chrt='';
    switch (codchart) {
        case '45':
            chrt='iac'
            $("#app_judul").html('Approach')
            break;
        case '46':
            chrt='sid'
            break;
        case '47':
            chrt='star'
            break;
    }
// console.log('TRANS',transtemp,codchart);
// console.log('PROC',proctemp);
$("#judullist").html(arpt[0].icao + ' ' + arpt[0].arpt_name + ' ' + chrt.toUpperCase() +' Procedures')

var input = document.getElementById("icao");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        searchats();
        // document.getElementById("ats_ctry").onchange();
    }
});
// function radiorwy(){
   
    var hsl='<div class="form-check col-md-2">'+
            '<input class="form-check-input pubchecktype" checked="checked" type="radio" id="ALL" value="ALL" onclick="selectradio()" name="generate">'+
            '<label class="form-check-label" for="ALL">ALL</label>'+
        '</div>';rwypertama='ALL';
    no=0
    arpt[0].runwaystemp.forEach(r=>{
    hsl += '<div class="form-check col-md-2">'+
            '<input class="form-check-input pubchecktype" type="radio" id="'+ r.thr_low +'" value="'+ r.thr_low +'" onclick="selectradio()" name="generate">'+
            '<label class="form-check-label" for="'+ r.thr_low +'">RWY '+ r.thr_low +'</label>'+
        '</div>'+
        '<div class="form-check col-md-2">'+
            '<input class="form-check-input pubchecktype" type="radio" id="'+ r.thr_high +'" value="'+ r.thr_high +'" onclick="selectradio()" name="generate">'+
            '<label class="form-check-label" for="'+ r.thr_high +'">RWY '+ r.thr_high +'</label>'+
        '</div>';
        no +=1;
    })
    $("#rwytrans").html(hsl)
        var hsl='<div class="form-check col-md-2">'+
                '<input class="form-check-input procchecktype" checked="checked" type="radio" id="ALL" value="ALL" onclick="selectradio()" name="procradio">'+
                '<label class="form-check-label" for="ALL">ALL</label>'+
            '</div>';rwypertama='ALL';
        no=0
        arpt[0].runwaystemp.forEach(r=>{
        hsl += '<div class="form-check col-md-2">'+
                '<input class="form-check-input procchecktype" type="radio" id="'+ r.thr_low +'" value="'+ r.thr_low +'" onclick="selectradio()" name="procradio">'+
                '<label class="form-check-label" for="'+ r.thr_low +'">RWY '+ r.thr_low +'</label>'+
            '</div>'+
            '<div class="form-check col-md-2">'+
                '<input class="form-check-input procchecktype" type="radio" id="'+ r.thr_high +'" value="'+ r.thr_high +'" onclick="selectradio()" name="procradio">'+
                '<label class="form-check-label" for="'+ r.thr_high +'">RWY '+ r.thr_high +'</label>'+
            '</div>';
            no +=1;
        })
    $("#rwyproc").html(hsl)
// }
listtrans('');
listproc('');
function selectradio(e) {

    $('.pubchecktype:radio:checked').each(function(i){
        // console.log($(this).val())
        listtrans($(this).val())
      
    });
    $('.procchecktype:radio:checked').each(function(i){
        // console.log($(this).val())
        listproc($(this).val())
      
    });
}
function listtrans(rwy){
    $("#translist").empty();
    transtemp.forEach(a=>{
        var shotr=false;
        if (rwy=='' || rwy=='ALL'){
            shotr=true;
        }else{
            if(a.rwy_trans==rwy || a.rwy_trans=='ALL' || a.rwy_trans==null){
                shotr=true;
            }
        }
        var rnv=a.rnav;
        if (rnv==null){
            rnv='';
        }
        // console.log(a,rwy)
        if(shotr==true){
            hasil='<tr>'+
                    '<td class="tb-tnx-action">'+
                            '<div class="dropdown">'+
                                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                                    '<ul class="link-list-plain">'+
                                        '<a class="btn btn-dim btn-primary col-md-12" id="'+a.proc_id+'" onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Segment</a>'+
                                        '<a class="btn btn-dim btn-info col-md-12" id="'+a.id+'" onclick="setMapPointtrans(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                                        '<a class="btn btn-dim btn-danger col-md-12" id="'+a.proc_id+'" onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                                    '</ul>'+
                                '</div>'+
                            '</div>'+
                        '</td>'+
                        '<td>'+a.trans_ident+'</td>'+
                        '<td>'+rnv+'</td>'+
                        '<td>RWY '+a.rwy_trans+'</td>'+
                        '<td>'+a.definition+'</td>'+
                    '</tr>';
                $("#translist").append(hasil);

        }
        
    });
}

function listproc(rwy){
    $("#proclist").empty();
    proctemp.forEach(a=>{
        // console.log(a)
        var shotr=false;rnav='';
        if (a.segment.length >0){
            rnav=a.segment[0].transition[0].rnav;
        }
        if ( rnav==null){
            rnav='';
        }
        if (rwy=='' || rwy==null || rwy=='ALL'){
            shotr=true;
        }else{
            if(a.rwy==rwy || a.rwy==null || a.rwy=='ALL' ){
                shotr=true;
            }
        }
        var rnv='RWY '+a.rwy;
        if (a.rwy==null){
            rnv='';
        }
        if(shotr==true){
            hasil='<tr>'+
                    '<td class="tb-tnx-action">'+
                            '<div class="dropdown">'+
                                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                                    '<ul class="link-list-plain">'+
                                        '<a class="btn btn-dim btn-primary col-md-12" id="'+a.proc_id+'" onclick="editproc(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Segment</a>'+
                                        '<a class="btn btn-dim btn-info col-md-12" id="'+a.id+'" onclick="setMapPointproc(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                                        '<a class="btn btn-dim btn-danger col-md-12" id="'+a.id+'" onclick="removeproc(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                                    '</ul>'+
                                '</div>'+
                            '</div>'+
                        '</td>'+
                        '<td>'+a.proc_name+'</td>'+
                        '<td>'+a.proc_text+'</td>'+
                        '<td>'+rnav+'</td>'+
                        '<td>'+rnv+'</td>'+
                    '</tr>';
                $("#proclist").append(hasil);

        }
        
    });
}
function searchats(){
    var ident=$("#icao").val().toUpperCase()
    window.scrollTo(0,0);
    // window.location.href = '/atsdetail/' + ident;
    $.ajax({
        url: '/api/airports',
        data: {'icao' : ident},
        type: "json",
        method: "GET",

            success: function (result) {
                var jmlwpt=result.data.length
               
                    console.log(jmlwpt)
                
                $.each(result.data, function (k, v) {

                    // console.log(v,cod);
                  
                        window.location.href = '/procedure/' + v.arpt_ident +'/'+codchart;
                    

                    // dt.push(v)
                  
                    
                })
            }
    })

}

function viewsegproc(id){
    aboutvol("dataprocsegdetail")
    var ip=proctemp.findIndex(x=>x.id===Number(id))
    var pr=proctemp[ip]
    var rwy=pr.segment[0].transition[0].rwy_id
   
    console.log(pr.segment[0].transition[0].rwy_id)
    $("#proc_name").val(pr.proc_name)
    $("#chart_type_proc").val(pr.chart_type)
    $("#rwy_id_proc").val(rwy)
    $("#proc_text").val(pr.proc_text)
    $("#note").val(pr.note)
    $("#remarks").val(pr.remarks)
    pr.segment.forEach(p=>{
        listoftrans(p.transition,'translist')
    })

    
}

function backtomenu(){
    
    window.location.href = '/listairport/'+chrt;
}
function setMapPointtrans(id) {
    var ix =transtemp.findIndex(x=>x.id===Number(id));
    // console.log(transtemp,ix,id)
    setMapPoint(transtemp[ix].proc_id+"@"+transtemp[ix].rt_type,'trans')
}
function setMapPointproc(id) {
    var ix =proctemp.findIndex(x=>x.id===Number(id));
    setMapPoint(proctemp[ix].proc_id,'proc')
}

function setMapPoint(procid,tbl) {
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table='+tbl+'&id='+procid, 'Set Latitude and Longitude', params)
}
function editproc(data){
    window.scrollTo(0,0);
    window.location.href = '/listprocsegment/'+data +'/'+codchart;
}
function edit(data) {

    window.location.href = '/listtranssegment/'+data +'/'+codchart +'@procedure_'+arpt[0].arpt_ident+'_'+codchart;
}

function NewData(){
    window.location.href = '/listtranssegment/new@'+arpt[0].arpt_ident+'/'+codchart+'@new';

    
}
function NewDataproc(){
    window.location.href = '/listprocsegment/new@'+arpt[0].arpt_ident+'/'+codchart;
    
}



function remove(id){
    console.log(id)
    // var codchart=@json($chart);arpt=@json($arpt);
    $("#arpt_ident").val(arpt[0].arpt_ident)
    var ix =transtemp.findIndex(x=>x.proc_id==id)
    console.log(ix)
    $("#id").val(transtemp[ix].id)
    $("#proc_id").val(id);
    $("#chart_type").val(codchart);
    $("#status").val('DELETE_TRANS');
    
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
                    $("#trans_delete").submit();
                }
            })

}
function removeproc(id){
    // console.log(id)
    // var codchart=@json($chart);arpt=@json($arpt);
    $("#arpt_ident").val(arpt[0].arpt_ident)
    var ix =proctemp.findIndex(x=>x.id==Number(id))
    // console.log(proctemp[ix])
    $("#id").val(id)
    $("#proc_id").val(proctemp[ix].proc_id);
    $("#chart_type").val(codchart);
    $("#status").val('DELETE_PROC');
    
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
                    $("#trans_delete").submit();
                }
            })

}
function isback(){
    aboutvol("datatransegdetail");
    aboutvol("datatranssegment");
    window.scrollTo(0,0);
}





</script>
@endsection