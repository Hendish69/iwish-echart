@extends('layouts.app')

@section('template_title')
    AD 2.24
@endsection

@section('head')
@endsection

@section('content')
<style>
      /* table {
        border-spacing: 0px;
        table-layout: fixed;
        margin-left: auto;
        margin-right: auto;
        width: 310px;
      } */
      td {
        border: 1px solid #666;
        word-break: break-all;
      }
    </style>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title" id="contentitle"></h6>
        </div>
        <div class="panel-body mt-3" id="charttable" style="visibility: visible">
            <div class="panel-heading">
                <button onclick="NewData()" class="btn btn-sm btn-dim btn-info"><i class="icon ni ni-plus"></i> Add</button>
            </div>
            <div class="row mt-1">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th style="text-align:center;" >No</th>
                                <th style="text-align:center;">Chart Name</th>
                                <!-- <th style="text-align:center;">Eff Date</th> -->
                            </tr>
                        </thead>
                        <tbody id="chartlist">
            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-heading mt-3" id="backid" style="visibility: visible">
            <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
        </div>
        <div class="panel-body mt-3" id="chartedit" style="visibility: hidden">
            <div class="panel-body mt-3">
                <form action="api/airport/chart/save" method="post"  enctype="multipart/form-data" id="chartform">
                    <input type="hidden" name="_token" id="high_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="high_editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="aip_sub" id="aip_sub" value='AD'>
                    <input type="hidden" name="aip_sub_id" id="aip_sub_id" value='AD 2.24'>
                    <input type="hidden" name="arpt_pdf_type" id="arpt_pdf_type" value='CHART'>
                    <input type="hidden" name="arptchart_id" id="arptchart_id">
                    <input type="hidden" name="arpt_ident" id="arpt_ident">
                    <input type="hidden" name="status" id="status">
                    <div class="row">
                        <div class="col-md-5">
                            <strong>Chart Type</strong>
                            <br>
                            <select class="form-control" onChange="chartname()"  id="chart_code" name="chart_code">
                                    <option selected></option>
                                @foreach ($codchart as $cod)
                                    <option value="{{$cod->code}}">{{$cod->description}}  </option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="col-md-1">
                            <strong>Page</strong>
                            <br>
                            <input type="text" style="text-transform:uppercase"  onkeyup="chartname()" class="form-control" id="chart_page" name="chart_page">
                        </div>
                        <div class="col-md-2">
                            <strong>RWY</strong>
                            <br>
                            <select  class="form-control" onChange="chartname()" id="chart_rwy" name="chart_rwy">
                                    <option selected></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Seq.</strong>
                            <br>
                            <input type="number" class="form-control" id="seq" name="seq">
                        </div>
                        <div class="col-md-2">
                            <strong>Navaid</strong>
                            <br>
                            <select class="form-control" onChange="chartname()" id="chart_nav" name="chart_nav">
                                <option selected></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <strong>Source</strong>
                            <br>
                            <select class="form-control" id="source" name="source">
                                <option selected></option>
                            </select>
                            <!-- <input type="text" style="text-transform:uppercase" class="form-control" id="source" name="source"> -->
                        </div>
                        <div class="col-md-2">
                            <strong>Nr</strong>
                            <br>
                            <input type="text" style="text-transform:uppercase" class="form-control" id="nr_yr" name="nr_yr">
                        </div>
                        <div class="col-md-3">
                            <strong>Pub Date</strong>
                            <br>
                            <input type="date" style="text-transform:uppercase" class="form-control" id="pub_date" name="pub_date" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-md-3">
                            <strong>Eff Date</strong>
                            <br>
                            <input type="date" style="text-transform:uppercase" onChange="chartname()" class="form-control" id="eff_date" name="eff_date" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-md-8">
                            <strong>Chart Name</strong>
                            <br>
                            <input type="text" style="text-transform:uppercase" class="form-control" id="chart_name" name="chart_name">
                        </div>
                        <div class="col-md-4">
                            <strong>Chart File</strong>
                            <br>
                            <input type="file" accept=".pdf" name="files[]" id="files" ref="files" multiple @change="filesSelected" class="form-control-file">
                            <!-- <input type="file" accept=".pdf" name="path_file" id="path_file" ref="path_file" @change="filesSelected" class="form-control-file"> -->
                        </div>
                        <div class="col-md-10">
                            <strong>Chart Procedure</strong>
                            <br>
                            <select class="form-control" onChange="showprocedure()" id="chart_id" name="chart_id">
                                <option selected></option>
                            </select>
                            <!-- <input type="text" style="text-transform:uppercase" class="form-control" id="source" name="source"> -->
                        </div>
                        <div class="col-md-2">
                            <strong>Scale</strong>
                            <br>
                            <input type="text" style="text-transform:uppercase" class="form-control" id="scale" name="scale">
                        </div>
                        <div class="col-md-12">
                            <strong>Remarks</strong>
                            <br>
                            <textarea type="text" style="text-transform:uppercase" class="form-control" id="remarks" name="remarks"></textarea>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button onclick="back()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                        &nbsp;
                        <button id="btn_update" type="submit" class="btn btn-dim btn-dark"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" align="right">
                <i style="color:red" align="right">RED Color = Data change request</i>
                <br>
                <i style="color:darkgrey" id="arptidname"></i>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$('#chartedit').hide();
var arpt =@json($airport);a=arpt[0];
var no=0;newdata=false;
var chart =@json($chart);
// console.log(chart)
var navcode= [{
                id: '1',
                value: 'RNAV'
            }, {
                id: '2',
                value: 'ILS'
            }, {
                id: '3',
                value: 'VOR/DME'
            }, {
                id: '4',
                value: 'RNP'
            }, {
                id: '5',
                value: 'NDB'
            }]
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
// $("#chart_rwy").empty();

a.runways.forEach(r=>{
    var hsl= '<option value="'+r.thr_low+'">'+r.thr_low+'</option>'+
                '<option value="'+r.thr_high+'">'+r.thr_high+'</option>'+
                '<option value="'+r.thr_low+'/'+r.thr_high+'">'+r.thr_low+'/'+r.thr_high+'</option>';
                $("#chart_rwy").append(hsl);
})
$("#chart_rwy").append('');
navcode.forEach(r=>{
    var hsl= '<option value="'+r.value+'">'+r.value+'</option>';
                $("#chart_nav").append(hsl);
})
sourcelist.forEach(r=>{
    var hsl= '<option value="'+r.key+'">'+r.value+'</option>';
            $("#source").append(hsl);
})

function chartname(){
        // if (newdata == true){
            this.rwyt='';
            this.navc='';
            this.pgc='';
            var skillsSelect = document.getElementById("chart_code");
            var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
            //sel.options[sel.selectedIndex].text
            this.codec=selectedText;
            var Chartcd=$("#chart_code").val();

        var dteff = ", Dated " +  DateFormat(new Date($("#eff_date").val()),false,true);
        if (Chartcd == ""){
            $("#chart_name").val('')
        }else{
            if ($("#chart_rwy").val() !== '' ){
                this.rwyt = " RWY " + $("#chart_rwy").val()
            }
            if ($("#chart_nav").val() !== '' ){
                this.navc = ' ' + $("#chart_nav").val()
            }
            if ($("#chart_page").val() !== '' ){
                this.pgc = $("#chart_page").val()
            }
            $("#chart_name").val(a.icao + ' ' + Chartcd.toUpperCase() + this.pgc + ', ' + this.codec +  this.navc + this.rwyt + dteff)

        }
        // If ryw = True And nav = True Then
        //     TxtChartName.Text = pprp.Icao & " " & Chartcd & pg & ", " & chrtn & " " & navt & rwyt & dteff
        // ElseIf ryw = True And nav = False Then
        //     TxtChartName.Text = pprp.Icao & " " & Chartcd & pg & ", " & chrtn & " " & rwyt & dteff
        // Else
        //     TxtChartName.Text = pprp.Icao & " " & Chartcd & pg & ", " & chrtn & dteff
        // End If

    // }
}
chart.forEach(a=>{
    // console.log(a)
    no++
    var ab=a.chart_name
    // console.log(idx)
    // if (a.is_active=='1'){

        hasil='<tr>'+
                '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">'+
                            '<li><a class="btn btn-dim btn-dark" id="'+ a.arptchart_id+'" onclick="editchart(this.id)"><i class="icon ni ni-edit"></i> Edit</a></li>'+
                            '<li><a class="btn btn-dim btn-light download" id="'+ a.arptchart_id+'" onclick="showpdf(this.id)"><i class="icon ni ni-file-pdf"></i> Show</a></li>'+
                            '<li><a class="btn btn-dim btn-danger" id="'+ a.arptchart_id+'" onclick="remove(this.id)"><i class="icon ni ni-delete"></i> Remove</a></li>'+
                        '</ul>'+
                    '</div>'+
                '</div>'+
                '<td>'+no+'</td>'+
                '<td>'+ab +'</td>'+
            '</tr>';
            $("#chartlist").append(hasil);
    // }
        // console.log(a);

})
function showpdf(id){
    console.log(id)

    var idx= chart.findIndex( x => x.arptchart_id === Number(id) );
    rtemp=chart[idx];
    // $("#Attach").hide();
   var fl = rtemp.path_file.replace('images/','');
    var pathdetail= 'upload/publication/aip/' + fl;
    // console.log(pathdetail)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    window.open("{{URL::to('/')}}/" + pathdetail, 'airportcontent', params)
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    // window.open("{{URL::to('/')}}/" + pathdetail, 'airportcontent', params)
}
function back(){
    aboutvol('chartedit');
    aboutvol('charttable');
}
function remove(id){
    console.log(id)
    dtsrcraw={
        _token:"{{ csrf_token() }}",
        deleted:'1',
    }

    Swal.fire({
        title: 'Deleted',
        text: "The data chart will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'

    }).then((result) => {
        if (result.value) {
            var rawid=''
            // console.log('Berhasil masuk YES ', "{{ URL::to('/') }}/DataRequest/save", id,update,rawdata)
            $.ajax({
                url:  "api/airport/chart/remove/" + id, //'/DataRequest/save',
                type: "POST",
                data: JSON.stringify(dtsrcraw),
                // data: update,
                cache: false,
                contentType: 'application/json; charset=utf-8',
                processData: false,
                success: function (response)
                {
                    // console.log(response.success);
                    // alert(response.success);
                    Swal.fire(
                        'Updates!',
                        'Data Chart has been updated.',
                        'success'
                    );
                    location.reload();
                }
            });

            
        }else{
            location.reload();

        }
    })
}
var editform=false;editps=false;
var ttl=a.icao + ' AD 2.24 CHARTS RELATED TO AN AERODROME';
$("#arptidname").html(a.icao + ' ' + a.city_name +'/'+ a.arpt_name);
$("#contentitle").html(ttl);
$('#btn_update').click(function() {
    $("#chartform").submit();
})
function editchart(id) {
    newdata=false
    // console.log(id)
    $("#btn_update").html('<i class="icon ni ni-save-fill"></i> Update');
    aboutvol('chartedit');
    aboutvol('charttable');
    var idx= chart.findIndex( x => x.arptchart_id === Number(id) );
    rtemp=chart[idx];
    // console.log(rtemp);
    $("#arpt_ident").val(a.arpt_ident);
    $("#arptchart_id").val(rtemp.arptchart_id)
    $("#status").val('R')
    $("#chart_code").val(rtemp.chart_code)
    $("#chart_rwy").val(rtemp.chart_rwy)
    $("#chart_nav").val(rtemp.chart_nav)


    listchartid(rtemp.chart_code,rtemp.chart_id)
    
    $("#chart_page").val(rtemp.chart_page)
    $("#chart_name").val(rtemp.chart_name)
    $("#seq").val(rtemp.seq)
    $("#source").val(rtemp.source)
    $("#nr_yr").val(rtemp.nr_yr)
    $("#eff_date").val(rtemp.eff_date)
    $("#pub_date").val(rtemp.pub_date)
}
function listchartid(id,datachart=null){
    $("#chart_id").empty();
    var charttype='';
    switch (id) {
        case "AD 2.24-7":
            charttype='46';
            break;
        case "AD 2.24-9":
            charttype='47';
            break;
        case "AD 2.24-11":
            charttype='45';
            break;
        case "AD 2.24-1":
            charttype='10';
            break;
    
        default:
            break;
    }
    $.ajax({
        url: '../api/proc/chart',
        type: 'get',
        data:{chart_arpt_ident:a.arpt_ident,chart_type:charttype},
        success: response => {
            response.data.forEach(c => {
                // console.log(c)
                var proc='Proc : '
                c.procedure.forEach(p=>{
                    if (proc =='Proc : '){
                        proc += p.segment.proc_name;
                    }else{
                        proc += ', ' + p.segment.proc_name;
                    }

                   
                    // proc += p.segment.proc_name;
                })
                var idx= chart.findIndex( x => x.chart_id === c.chart_id );
                if (idx==-1 || datachart == c.chart_id){
                    var hsl= '<option value="'+c.chart_id+'">'+id + ' ' + c.cat + ' ('+ proc  +')</option>';
                    if (charttype=='45'){
                        hsl= '<option value="'+c.chart_id+'">'+id + ' ' + c.nav + ' ' + c.cat + ' ('+ proc  +')</option>';
                    }
                console.log(chart,'chart',c)
                    $("#chart_id").append(hsl);
                    $("#scale").val(c.basemap[0].scale);
                }
                // console.log(c)
            
            })
            // console.log(datachart)
            // if (datachart !== null){
                $("#chart_id").val(datachart)
            // }
        }
    }); 
    // if (chart.length > 0){
    //     chart.forEach(c=>{
    //         if (c.chart_code==id){
    //             var hsl= '<option value="'+c.arptchart_id+'">'+c.chart_name+'</option>';
    //             $("#chart_id").append(hsl);
    //         }
    //         console.log(c,id)
    //     })

    // }

}
function showprocedure(){

}
function NewData(){
    newdata=true;
    $("#btn_update").html('<i class="icon ni ni-save-fill"></i> Save');
    aboutvol('chartedit');
    aboutvol('charttable');
    
    $("#arpt_ident").val(a.arpt_ident);
    $("#status").val('N');
    $("#seq").val(chart.length+1)
}

function backtomain(){
    
    window.scroll(0,0);
    aboutvol('chartedit');
    aboutvol('charttable');
}


function backtolist(){
    window.location.href="{{ url('editairport') }}/" + a.arpt_ident;
}

</script>
@endsection