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
            <div class="panel-heading">
                <h5 class="panel-title" id="arptname"></h5>
                <h6 class="panel-title" id="judullist"></h6>
            </div>
            <div class="panel-heading mt-3">
                <button onclick="backtomenu()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
            </div>
           
            <div id="datatransegdetail">
                <div class="panel-body mt-3">
                    <form action="../api/procedure/temp/save" method="POST" id="trans_form">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="arpt_ident" id="arpt_ident">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="proc_id" id="proc_id">
                        <input type="hidden" name="trans_id" id="trans_id">
                        <input type="hidden" name="status" id="status">
                        <input type="hidden" name="listtrans" id="listtrans">
                        <input type="hidden" name="rwy" id="rwy_proc">
                        <input type="hidden" name="geom" id="geom">
                        <div class="row table-bordered">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>Chart Type</strong>
                                        <br>
                                        <select id="chart_type" name="chart_type" onchange="transcombo()" selected="selected" class="form-control" >
                                        
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Route</strong>
                                        <br>
                                        <select id="sub_chart_type" name="sub_chart_type" onchange="subcharttype();Addtrans(true)" selected="selected" class="form-control" >
                                        
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Transition</strong>
                                        <br>
                                        <select id="rt_type" name="rt_type" onchange="routetype();Addtrans(true)" class="form-control" >
                                        
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>RWY</strong>
                                        <br>
                                        <select id="rwy_id" name="rwy_id" onchange="runways(this);Addtrans(true)" selected="selected" class="form-control" >
                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="datatranssegment" style="visibility: visible">
                                    <div class="col-md-12 mt-3">
                                        <table class="table table-bordered table-hover mt-3" id="table-content">
                                            <thead class="thead-dark">
                                                <tr align="center">
                                                    <th id="btn_add"></th>
                                                    <th>Name</th>
                                                    <th>RWY</th>
                                                    <!-- <th>RNAV</th> -->
                                                    <th>Transition</th>
                                                </tr>
                                            </thead>
                                            <tbody id="seglist">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-inner table-bordered mt-1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Procedure Name</strong>
                                            <br>
                                            <input id="proc_name" type="text" style="text-transform: uppercase" onfocusout="titelproc()" name="proc_name" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Transition Name</strong>
                                            <br>
                                            <select id="trans_name" name="trans_name" selected="selected" class="form-control" >
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Procedure Text</strong>
                                            <br>
                                            <textarea id="proc_text" type="text"  name="proc_text" class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Procedure Text Notes</strong>
                                            <br>
                                            <textarea id="note" type="text"  name="note" class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Planview Notes</strong>
                                            <br>
                                            <textarea id="remarks" type="text" name="remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-md-12">
                                    <br>
                                </div>
                            </div>
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
                            <button onclick="backtomenu()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                            &nbsp;
                            <button onclick="proctext()" id="btn_create_proctext" class="btn btn-dim btn-primary"><em class="icon ni ni-text2"></em> Procedure Text</button>
                            &nbsp;
                            <button onclick="updatetrans()" id="btn_save_trans" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
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

var fld= ['id','proc_id', 'arpt_ident', 'proc_name', 'remarks', 'proc_text','chart_type','note', 'status'];


var procs =@json($proc);procstemp =@json($proctemp);transcode =@json($transcode);transtemp=@json($transtemp);
var codchart=@json($chart);arpt=@json($arpt);rt=@json($rt);
var ifback='';trseg=[];trsegcurr=[];transB=[];transseg=[];newproc=[];
var lastpt='';proc=procs[0];proctemp=procstemp[0];
// console.log('PROC',proctemp);
var ats=@json($ats);
// console.log('TRANS',ats);
switch (codchart) {
    case '45':
        ifback='iac';
        break;
    case '46':
        ifback='sid';
        break;
    case '47':
        ifback='sta';
        break;
}
var charttype= [{
    id: '45',
    definition: 'IAC'
}, {
    id: '46',
    definition: 'SID'
}, {
    id: '47',
    definition: 'STAR'
}];

transcombo("45")
subcharttype('451')

charttype.forEach(a=>{
    $("#chart_type").append('<option value="'+a.id+'">'+a.definition+'</option>');
})
arpt[0].runwaystemp.forEach(r=>{
    var all=r.physicals[0].rwy_ident+'/'+r.physicals[1].rwy_ident;
    $("#rwy_id").append('<option value="'+r.physicals[0].rwy_key+'">'+r.physicals[0].rwy_ident+'</option>');
    $("#rwy_id").append('<option value="'+r.physicals[1].rwy_key+'">'+r.physicals[1].rwy_ident+'</option>');
    $("#rwy_id").append('<option value="ALL">'+all+'</option>');
    $("#rwy_id_proc").append('<option value="'+r.physicals[0].rwy_key+'">'+r.physicals[0].rwy_ident+'</option>');
    $("#rwy_id_proc").append('<option value="'+r.physicals[1].rwy_key+'">'+r.physicals[1].rwy_ident+'</option>');
    $("#rwy_id_proc").append('<option value="ALL">'+all+'</option>');
    // console.log(r)
})
if (procstemp.length==0){
    $("#judullist").html('New Procedure');
    NewData();
}else{
    $("#judullist").html(proctemp.proc_name + ' Procedure')
    edit()
}
$("#arptname").html(arpt[0].icao + ' ' + arpt[0].arpt_name)

function titelproc(){
    var ttl=$("#proc_name").val();
    checkprocname(ttl.toUpperCase());
    var tt=ttl.split(" ");tproc='';tpname='';
    if ($("#chart_type").val()=='45'){
        $("#proc_text").val('MISSED APPROACH :' + '\n')
    }else{
        if ($("#chart_type").val()=='46'){
            tproc =" DEPARTURE";
        }else{
            tproc =" ARRIVAL";
        }
        if (tt.length ==1){
            Swal.fire({
                    title: 'Only Name',
                    text: "Are you sure the procedure name doesn't use a number",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Without number!'
                    
                }).then((result) => {
                    if (result.value) {
                        tpname=  ttl + tproc;
                        tpname=tpname.toUpperCase();
                        $("#proc_text").val(tpname + '\n')
                    }
                })
            
        }else if (tt.length ===2){
            tpname=tt[0] + ' '+ ConverNumChart(tt[1]) + tproc;
            // console.log(tpname)
            var ptt = $("#proc_text").val();
            tprocname=tpname.toUpperCase();
            if (ptt==''){
                $("#proc_text").val(tprocname + '\n')
            }else{
                numptt = ptt.split("\n");newtpoc='';
                // console.log(numptt,numptt[0])
                for (let index = 0; index < numptt.length; index++) {
                    if (index==0){
                        newtpoc=tprocname + '\n';
                    }else{
                        if (numptt[index] !=='')
                        newtpoc +=numptt[index] + '\n';
                    }
                    
                }
                $("#proc_text").val(newtpoc)
            }
        }
    }


  
    
}
function proctext(){
    if ( $("#status").val()=='N'){
        // console.log(newproc,proctemp)
        hasil= DrawProcedure(newproc,'',true)
    }else{
        // console.log(newproc,proctemp)
        hasil= DrawProcedure(proctemp,ats)

    }
    var ptt = $("#proc_text").val();
        if (ptt==''){
            $("#proc_text").val(hasil)
        }else{
            numptt = ptt.split("\n");newtpoc='';
            // console.log(numptt,numptt[0],numptt.length,hasil)
            if (numptt.length <=2){
                newtpoc = numptt[0]+ '\n'+ hasil;
            }else{
                for (let index = 0; index < numptt.length; index++) {
                    if (index==0){
                        newtpoc=numptt[index] + '\n';
                    }else{
                        if (numptt[index] !== ''){
                            newtpoc +=hasil;
                        }
                    }
                    
                }
                
            }
        }
        // console.log(newtpoc,'newtpoc')
            $("#proc_text").val(newtpoc)
    // console.log(hasil)
}
function routetype(){
    var chid=$("#rt_type").val();
    // console.log(chid,$("#chart_type").val(),$("#sub_chart_type").val())
    

    if ($("#chart_type").val() == "45"){
        if (chid=='R'){
            $("#rnav").val('Y');
        }else{
            $("#rnav").val('N');
        }
    }else{
        if ($("#sub_chart_type").val()=='462' || $("#sub_chart_type").val()=='472'){
            $("#rnav").val('Y');
        }else{
            $("#rnav").val('N');
        }
    }

}
function subcharttype(trans=null){
    var chid=trans;
    if (trans==null){
        chid=$("#sub_chart_type").val();
    }
    $("#rt_type").empty()
    $("#rt_type").append('<option value=""></option>');
    rt.forEach(a=>{
        // console.log(a)
        if (a.trans_code==chid){
            $("#rt_type").append('<option value="'+a.trans_types+'">'+a.definition+'</option>');
            
        }
    }) 
    
    // console.log(chid)
    checkNotes();
//    console.log(rt)
}
function checkNotes(){
    chid=$("#sub_chart_type").val();
    chid1=$("#rt_type").val();
    note=$("#remarks").val();
    if (codchart=='45'){
        if (chid1=='R'){
            if (note==''){
                $("#remarks").val('Note : \n' + '- RNP APCH\n' + '- GNSS Required\n')
            }
        }else{
            $("#remarks").val('')
        }
    }else{
        if (chid=='462' || chid=='472'){
            if (note=='VOR and DME Required'){
                $("#remarks").val('Note : GNSS Required')
            }
            
        }else{
            if (note==''){
                $("#remarks").val('VOR and DME Required')
            }
        }
    }
    
}
function transcombo(chart=null){
    var chid=chart;
    if (chart==null){
        chid=$("#chart_type").val();
        switch (chid) {
            case '45':
                subcharttype('451')
                break;
        
            case '46':
                subcharttype('461')
                break;
            case '47':
                subcharttype('471')
                break;
        }
    }
    
    $("#sub_chart_type").empty()
    transcode.forEach(a=>{
        if (a.chart_code==chid){
            $("#sub_chart_type").append('<option value="'+a.trans_code+'">'+a.definition+'</option>')
            
        }
    }) 
}


function backtomenu(){
    window.location.href = '/procedure/'+arpt[0].arpt_ident+'/'+codchart;

}
function setMapPointtrans(id) {
    var ix =proctemp.findIndex(x=>x.id===Number(id));
    // console.log(transtemp,ix,id)
    setMapPoint(proctemp[ix].proc_id+"@"+proctemp[ix].rt_type,'trans')
}
function setMapPointproc(id) {
    var ix =proctemp.findIndex(x=>x.id===Number(id));
    setMapPoint(proctemp[ix].proc_id,'proc')
}

function setMapPoint(procid,tbl) {
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table='+tbl+'&id='+procid, 'Set Latitude and Longitude', params)
}
function Addtrans(newtrans){
    var transseg=[]
    if (newtrans==true){
        $("#btn_add").html('<a class="btn btn-sm btn-dim btn-dark" onclick="Addtrans(false)"><i class="icon ni ni-reply-fill"></i> Back</a>')
        transseg=transtemp;
        $("#seglist").empty();
        var subchart=$("#sub_chart_type").val();
        var rtype=$("#rt_type").val();
        var rw=$("#rwy_id").val();
        // console.log(subchart,rtype,rw)
        transseg.forEach(t=>{
            // console.log(t)
            if(t.rwy_id ==rw && t.rt_type==rtype && t.sub_chart_type==subchart){
                var  hsl= '<tr v-bind:key="arp.arpt_ident">'+
                    '<td class="tb-tnx-action">'+
                        '<div class="dropdown">'+
                            '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                            '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                                '<ul class="link-list-plain">'+
                                // '<a class="btn btn-dim btn-primary col-md-12" id='+ t.proc_id +' onclick="viewtrans(this.id)"><i class="icon ni ni-edit"></i> View</a>'+
                                    '<a class="btn btn-dim btn-primary col-md-12" id='+ t.id +' onclick="inserttrans(this.id)"><i class="icon ni ni-plus"></i> Add</a>'+
                                    '<a class="btn btn-dim btn-info col-md-12" id='+ t.id +' onclick="setMapPointtrans(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                                    // '<a class="btn btn-dim btn-danger col-md-12" id='+ t.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                                '</ul>'+
                            '</div>'+
                        '</div>'+
                    '</td>'+
                    '<td>'+ t.trans_ident +'</td>'+
                    '<td>RWY '+ t.rwy_trans +'</td>'+
                    // '<td>RWY '+ t.rnav +'</td>'+
                    '<td>'+ t.definition +'</td>'+
                '</tr>';
                $("#seglist").append(hsl)
            }
        })
    }else{
        edit()
    }
}
function inserttrans(id){
    console.log(id,'inserttrans',transtemp,$("#status").val())
        var idx= transtemp.findIndex(x=>x.id===Number(id))
        var tttemp=transtemp[idx];
    if ($("#status").val()=='N'){
        transB.push(tttemp.proc_id)
        newproc.push(tttemp)
        showtransition(newproc,true)
        $("#trans_name").append('<option value="'+tttemp.proc_id+'">'+tttemp.trans_ident+' RWY '+tttemp.rwy_trans+' '+tttemp.definition+'</option>');
    }else if ($("#status").val()=='R'){
        console.log(id)
        $("#id").val(id) 
        $("#status").val('I')
        $("#proc_id").val(trseg.proc_id)
        $("#trans_id").val(tttemp.proc_id)
        Swal.fire({
            title: 'Add Transition',
            text: "You will add Transition!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.value) {
                $("#trans_form").submit();
            }else{
                location.reload();
            }
        })

    }
}
function edit(data) {
    // console.log(proctemp.segment[0].transition[0].rwy_id)
    window.scrollTo(0,0);
    $("#btn_add").html('<a class="btn btn-sm btn-dim btn-dark" onclick="Addtrans(true)"><i class="icon ni ni-plus"></i> Add</a>')
    $("#id").val(proctemp.id)
    $("#proc_id").val(proctemp.proc_id)
    $("#arpt_ident").val(proctemp.arpt_ident)
    $("#status").val('R')
    $("#proc_name").val(proctemp.proc_name)
    $("#chart_type").val(proctemp.chart_type)
    transcombo(proctemp.chart_type)
    $("#sub_chart_type").val(proctemp.segment[0].transition[0].sub_chart_type)
    subcharttype($("#sub_chart_type").val())
    if (codchart=='45'){

        $("#rt_type").val(proctemp.segment[0].transition[0].rt_type)
    }
    $("#rwy_id").val(proctemp.segment[0].transition[0].rwy_id)
    $("#proc_text").val(proctemp.proc_text)
    $("#note").val(proctemp.note)
    $("#remarks").val(proctemp.remarks)
    // console.log(transtemp,trans,data)
    $("#seglist").empty();$("#trans_name").empty()
    trseg=proctemp;
    trsegcurr=proc;
    trseg.segment.sort((a,b) => (a.rt_type > b.rt_type) ? 1 : ((b.rt_type > a.rt_type) ? -1 : 0));
    // console.log(trseg.segment)
    showtransition(trseg.segment)
}

function getnoteprevprocedure(trans_id){
    var dtreq={'trans_id':trans_id}
    // console.log(dtreq)
        $.ajax({
                url: '../api/procedures/seg/temp',
                data: dtreq,
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        // console.log(v,'getnoteprevprocedure')
                        $("#remarks").val(v.remarks)
                        $("#note").val(v.note)
                        
                        //    console.log(v)
                    
                    })
                }
        })
}
function showtransition(data,newdata=false){
    console.log(data,'showtransition')
    $("#seglist").empty();
    data.forEach(t=>{
       
        var procid='';trident='';trrwy='';trdef='';rnav='';
        if (newdata==false){
            procid= t.transition[0].id;
            trident=t.transition[0].trans_ident;
            trrwy='RWY '+ t.transition[0].rwy_trans;
            trdef=t.transition[0].definition;
            rnav=t.transition[0].rnav;
            $("#trans_name").append('<option value="'+t.transition[0].proc_id+'">'+t.transition[0].trans_ident+' RWY '+t.transition[0].rwy_trans+' '+t.transition[0].definition+'</option>');
        }else{
            procid= t.id;
            trident=t.trans_ident;
            trrwy='RWY '+ t.rwy_trans;
            trdef=t.definition;
            rnav=t.rnav;
            getnoteprevprocedure(procid)
        }
        // console.log(t,'showtransition',newdata,procid)
        var  hsl= '<tr v-bind:key="arp.arpt_ident">'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">'+
                        // '<a class="btn btn-dim btn-primary col-md-12" id='+ t.id +' onclick="viewtrans(this.id)"><i class="icon ni ni-edit"></i> View</a>'+
                            '<a class="btn btn-dim btn-info col-md-12" id='+ t.id +' onclick="setMapProcSeg(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                            '<a class="btn btn-dim btn-danger col-md-12" id='+ t.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                        '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ trident +'</td>'+
            '<td>'+ trrwy +'</td>'+
            // '<td>'+ rnav +'</td>'+
            '<td>'+ trdef +'</td>'+
            
        '</tr>';
        $("#seglist").append(hsl)
    })
}
function setMapProcSeg(id) {
    if ( $("#status").val()=='N'){
        var ix =transtemp.findIndex(x=>x.id===Number(id));
    // console.log(transtemp[ix],ix,id)
    setMapPoint(transtemp[ix].proc_id+"@"+transtemp[ix].rt_type,'trans')
    }else{
        var ix =proctemp.segment.findIndex(x=>x.id===Number(id));
        setMapPoint(proctemp.segment[ix].trans_id+"@"+proctemp.segment[ix].transition[0].rt_type,'trans')
    }
    // console.log(proctemp.segment[ix],ix,id)
}

function setMapPointtrans(id) {
    var ix =transtemp.findIndex(x=>x.id===Number(id));
    // console.log(transtemp[ix],ix,id)
    setMapPoint(transtemp[ix].proc_id+"@"+transtemp[ix].rt_type,'trans')
}
function NewData(){

    $("#chart_type").val(codchart);
    $("#status").val('N');
    $("#arpt_ident").val(arpt[0].arpt_ident);
    transcombo();

    
}
function checkprocname(procname){
    var dtreq={'proc_name':procname}
    console.log(dtreq)
        $.ajax({
                url: '../api/procedures/temp',
                data: dtreq,
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        // console.log(v.proc_id,proctemp.proc_id)
                        if (v.proc_id !== proctemp.proc_id && v.chart_type !== '45'){
                            Swal.fire(
                                'Warning!',
                                'Procedure name already used at <br>'+ v.airport[0].icao + ' ' + v.airport[0].city_name + '/' + v.airport[0].arpt_name + ' airport',
                                'warning'
                            );

                        }
                        //    console.log(v)
                    
                    })
                }
        })
}
function updatetrans(){
    var xx =  document.getElementById("rwy_id")
    var ttl=$("#proc_name").val();
    $("#proc_name").val(ttl.toUpperCase());
    $("#rwy_proc").val(xx.options[xx.selectedIndex].text)
    var fldu= ['proc_name', 'remarks', 'proc_text','chart_type','note','rwy'];
    if  ($("#status").val()=='N'){
        $("#listtrans").val(transB)
        fldu= ['proc_id', 'arpt_ident', 'proc_name', 'proc_text','chart_type'];
        $("#proc_id").val(arpt[0].arpt_ident +'-'+codchart+'-'+ ttl.toUpperCase())
        checkrwy =checknewdata(fldu);
    
    }else if ($("#status").val()=='R'){
        checkrwy =true;
    };
    console.log('NAVUPDATE',checkrwy)

    if (checkrwy==true){
        $("#trans_form").submit();
    }
    
    
}
function viewtrans(data){
    var ix =transtemp.findIndex(x=>x.id===Number(data));
    console.log(data,'viewtrans',transtemp[ix])
    window.location.href = '/listtranssegment/'+data +'/'+codchart+ '@listprocsegment_'+proctemp.proc_id +'_'+codchart;
    // window.location.href = '/listtranssegment/'+data +'/'+codchart +'@procedure_'+arpt[0].arpt_ident+'_'+codchart;
}

function remove(id){
    $("#id").val(id) 
    $("#status").val('D')
    $("#chart_type").val(codchart);
    $("#proc_id").val(trseg.proc_id) 
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
            $("#trans_form").submit();
        }else{
            location.reload();
        }
    })

}

function runways(selTag) {
    var x = selTag.options[selTag.selectedIndex].text;
    // var xx =  document.getElementById("rwy_id")
    // console.log(selTag,xx,xx.options[xx.selectedIndex].text);
    $("#rwy_trans").val(x);
    if ($("#rwy_id").val()=='ALL'){
        $("#rwy_trans").val('ALL');
    }
//   document.getElementById("demo").innerHTML = "You selected: " + x;
}





</script>
@endsection