@extends('layouts.app')

@section('template_title')
    Publication
@endsection

@section('head')
<style>
marquee {
	margin-top: 5px;
	width: 100%;
}

.runtext-container {
background-color:#ddddff;
*background-color:#ccf;
background-image:-moz-linear-gradient(top,#ccf,#fff);
background-image:-webkit-gradient(linear,0 0,0 100%,from(#ccf),to(#fff));
background-image:-webkit-linear-gradient(top,#ccf,#fff);
background-image:-o-linear-gradient(top,#ccf,#fff);
background-image:linear-gradient(to bottom,#ccf,#fff);
background-repeat:repeat-x;
	border: 4px solid #000000;
	box-shadow:0 5px 20px rgba(0, 0, 0, 0.9);

width: 850px;
overflow-x: hidden;
overflow-y: visible;
margin: 0 60px 0 30px;
padding:0 3px 0 3px;
}

.main-runtext {margin: 0 auto;
overflow: visible;
position: relative;
height: 40px;
}

.runtext-container .holder {
position: relative;
overflow: visible;
display:inline;
float:left;

}

.runtext-container .holder .text-container {
	display:inline;
}

.runtext-container .holder a{
	text-decoration: none;
	font-weight: bold;
	color:#ff0000;
	text-shadow:0 -1px 0 rgba(0,0,0,0.25);
	line-height: -0.5em;
	font-size:16px;
}

.runtext-container .holder a:hover{
	text-decoration: none;
	color:#6600ff;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div id="base" class="nk-content-wrapper">
                <div class="panel panel-default">
                    <h6>Data Updating</h6>
                    <input type="hidden" id="backto" value="{{Session::get('backto')}}">
                </div> <!-- <div class="form-check bg-black"> -->
                <!-- <div class="runtext-container">
                    <div class="main-runtext">
                        <marquee direction="" onmouseover="this.stop();" onmouseout="this.start();">

                        <div class="holder">

                            <div class="text-container">
                        &nbsp; &nbsp;  &nbsp; <a data-fancybox-group="gallery" class="fancybox" title="THE ELECTRIC LIGHTING ACT: section 35">THE ELECTRIC LIGHTING ACT makes it mandatory to use the services of a Licensed Electrician</a>
                            </div>
                        </div>

                        </marquee>
                    </div>
                </div> -->
                <div class="row mt" id="listamdt" style="visibility: hidden">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>Publication</th>
                                    <th>Number</th>
                                    <th>Publication Date</th>
                                    <th>Effective Date</th>
                                    <!-- <th>Effective Date</th> -->
                                </tr>
                            </thead>
                            <tbody id="publist">

                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="DataRequest/remove" method="post"  enctype="multipart/form-data" id="dataremove">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="status" id="status">
                </form>
                <div class="mt-2" id="modalform" style="visibility: hidden"></div>
                <div class="mt-2" id="notamform" style="visibility: hidden"></div>
                <div class="mt-2" id="publicationfileform" style="visibility: hidden"></div>
                <div class="row mt-3"  id="datalistarpt" style="visibility: visible">
                    <div class="card-tools" id='backtoamdt'>
                        <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                    </div>
                    <div class="col-md-12 mt-3">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark" align="center">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Req. Date</th>
                                    <th>Nr</th>
                                    <th>Pub. Date</th>
                                    <th>Eff. Date</th>
                                </tr>
                            </thead>
                            <tbody id="arptlist">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footer_scripts')

<script type="text/javascript">
var sss=$("#backto").val();
// console.log(sss);
$("#modalform").hide();$("#notamform").hide();$("#publicationfileform").hide();$("#listamdt").hide();
var rollback=false;showbutton=true;airac=true;valaction='';pic='',qc='',drafter='';notams=[];publicationattach=[];
var userid={{Auth::user()->id}};backto=@json($backto);
var arp =@json($request); pubdate=[];tablerequest='';
var tbreff =@json($tbreff);
var piapusat =@json($piapusat);dnp =@json($dnp);airnav =@json($airnav);
var roles="{{Auth::user()->roles[0]->id}}";isoriginator=false;ispiawilayah=false;ispiapusat=false;isdnp=false;isAirnavpusat=false;

var piaid= null;collectchart=[];
if (roles=="18" || roles=="19"){
    if ("{{Auth::user()->pia_id}}"==''){
        var piaid= null
    }else{
        var piaid= JSON.parse("{{Auth::user()->pia_id}}");
    }
}
// console.log('bACKKKKKK TOOOOO',backto)
if (sss){
    aboutvol("listamdt");aboutvol("datalistarpt")
    actionnext(sss);
    
}else{
console.log('TIDAK ADA BACK TO');
}
// console.log(arp)
//  JSON.parse("{{Auth::user()->pia_id}}");
//18=Originator, 19=PIA wilayah 20=pia pusat 21 DNP
// console.log('ROLE ID',JSON.parse("{{Auth::user()->roles[0]->id}}".replace(/&quot;/g,'"')),'user id',userid,'pia pusat',piapusat,'dnp',dnp,JSON.parse("{{Auth::user()->pia_id}}"));
// console.log("{{Auth::user()->pia_id}}")

var icao='';arptname='';city='';actionreq='ACTION';actionreqid='';ismajor=true,rawarpt='';actionback='';
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
if (arp){
    if (roles==20){
        arp.sort((a,b) => (a.nr > b.nr) ? 1 : ((b.nr > a.nr) ? -1 : 0));
        aboutvol("listamdt");aboutvol("datalistarpt")
        var nnr='';
        arp.forEach(v=>{
           
            if (nnr !== v.nr){
                var nr=v.nr;pubtype=v.pub_type;pubdt=v.pub_date;eff=v.eff_date;
                if (v.nr==null){
                    nr='-';eff='-';pubdt='-';pubtype='Not submitted yet';
                }
                hasil = '<tr class="nk-tb-item">'+
                        '<td style="cursor:pointer" id='+nr+' onclick="listdata(this.id)">'+pubtype+'</td>'+
                        '<td style="cursor:pointer" id='+nr+' onclick="listdata(this.id)">'+nr+'</td>'+
                        '<td style="cursor:pointer" id='+nr+' onclick="listdata(this.id)">'+pubdt+'</td>'+
                        '<td style="cursor:pointer" id='+nr+' onclick="listdata(this.id)">'+eff+'</td>'+
                    '</tr>';
                    $("#publist").append(hasil);
            }
            nnr=v.nr
        
        });
    }else{
        $("#backtoamdt").hide()
        listdata()
        
    }
    // console.log(arp)

}
function backtolist(){
    if ($("#listamdt").is(':visible')==false){
        aboutvol('listamdt');
    }
    if ($("#datalistarpt").is(':visible')==true){
        aboutvol('datalistarpt');
    }
}

function listdata(id=null){
    if ($("#listamdt").is(':visible')==true){
        aboutvol('listamdt');
    }
    if ($("#datalistarpt").is(':visible')==false){
        aboutvol('datalistarpt');
    }
    var cond='';
    $("#arptlist").empty();
//    console.log(id)
    arp.forEach(a=>{
        switch (id) {
            case null:
                cond=true;
                break;
            case '-':
                cond= a.nr == null
                break;
        
            default:
                cond= a.nr == id
                break;
        }
        if (cond){
            var nr='';pubdate='';effdate='';reqd=DateFormat(new Date(a.create_date),false,true);
            if (a.source.length > 0){
                if (a.source[0].src_id !== null){
                    nr=a.source[0].src_id;
                }
                pubdate= DateFormat(new Date(a.source[0].pub_date),false,true);
                effdate= DateFormat(new Date(a.source[0].eff_date),false,true);
            }

            tablerequest=a.tablename;
            if (a.status_raw == 100){
                    showbutton=false
            }else{
                    showbutton=true
                    var showreq=true;
                    if (roles==20){
                        if (a.nr == null){
                            showbutton=false;
                            showreq=false;
                        }
                    }
                // if (a.airport[0].auth.length>0){
                //     if (a.airport[0].auth[0].id !==piaid){
                //         showreq=false;
                //     }
                // }
                //  console.log('roles',roles);
                var detaillength=0;buttonenable='';tampil=false;
                if (showreq==true || piaid==null){
                    // console.log('AIRPORT',a);
            
                    var status='';city='';
                    if (a.tablename=='arpt' && a.fieldid !== null ){
                        if (a.airport.length > 0  ){
                            var arpt=a.airport[0];
                            if (arpt.auth.length > 0){
                                if (arpt.auth[0].id==piaid || roles=="20" || roles=="21" || roles =="1"){
                                    tampil=true;
                                    icao=arpt.icao;
                                    arptname=arpt.icao + ' ' + arpt.city_name + '/' +arpt.arpt_name;
                                    city='Airport';
                                    detaillength=a.detail.length;buttonenable='enable';
                                    rollback=false;
                                    // console.log(detaillength,'detaillength',roles);
                                    if ( icao.length == 2 ||  icao==''){
                                        icao='';
                                    }
        
                                }
        
                            }else{
                                if (roles=="20" || roles=="21" || roles =="1"){
                                    tampil=true;
                                    icao=arpt.icao;
                                    arptname=arpt.icao + ' ' + arpt.city_name + '/' +arpt.arpt_name;
                                    city='Airport';
                                    detaillength=a.detail.length;buttonenable='enable';
                                    rollback=false;
                                    // console.log(detaillength,'detaillength',roles);
                                    if ( icao.length == 2 ||  icao==''){
                                        icao='';
                                    }
        
                                }
                            }
                            
                        }
    
                    }
                    var  arpttable=true;
                    if ((a.tablename=='GEN' || a.tablename=='ENR') && (roles=="20" || roles=="21" || roles =="1" || roles =="24") ){
                        // console.log(a)
                        tampil=true;
                        icao=a.tablename;
                        arptname=a.fieldid;
                        city=a.fieldid;
                        detaillength=a.detail.length;buttonenable='enable';
                        rollback=false;
                        arpttable=false;
                        isAirnavpusat=true;
                            // console.log(detaillength,'detaillength',roles);
                            // if ( icao.length == 2 ||  icao==''){
                            //     icao='';
                            // }
                    }
                        if (detaillength == 0){
    
                            actionreq=tbreff[1].reff_name
                            actionreqid=tbreff[1].reff_code
                            status=tbreff[0].reff_name
                            isoriginator=true;
                            if  (arpttable==false){
                                actionreq=tbreff[9].reff_name
                                actionreqid=tbreff[9].reff_code
                                status=tbreff[0].reff_name
                                buttonenable='enable';
                            }else{
                                if (roles !== '18'){
                                    buttonenable='disabled';
                                }
                                
                            }
                            // console.log(actionreq,'SDJSDLJSALDJLAS',a.tablename,tbreff)
                        }else{
                            // check status terakhir dari data raw data
                            let actid=a.status_raw;
                            buttonenable=checkbuttonenable(actid,a);
                            console.log('status terakhir',actid,'actid',a);
                            let ix = tbreff.findIndex(x => x.reff_code===actid);
                            // console.log('ix',ix,actid,a)
                            status=tbreff[ix].reff_name;
                
                            if (tbreff[ix].reff_code < 100){
                                actionreq=tbreff[ix+1].reff_name;
                                actionreqid=tbreff[ix+1].reff_code;
                                switch (actid) {
                                    case "41":
                                        actionreq=tbreff[4].reff_name;
                                        actionreqid=tbreff[4].reff_code;
                                        break;
                                    case "42":
                                        actionreq=tbreff[12].reff_name;
                                        actionreqid=tbreff[12].reff_code;
                                        break;
                                }
                            }
                            // buttonenable=checkbuttonenable(actid,a);
                            //untuk membuat menu action selanjutnya
                            CheckstatusRollback(actid);
                        }
                    if (tampil==true){
                    // console.log('actionnext',actionreq+'$'+ actionreqid + '$'+ a.rawdata_id);
                        hasil = '<tr class="nk-tb-item"><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12"> <ul class="link-list-plain">';
                        // '<a class="btn btn-dim btn-secondary" id="'+ a.fieldid  + '" onclick="checkstatus(this.id)"><em class="icon ni ni-alarm-alt"></em>Check Status</a>';
                        if (showbutton==true){
                            // hasil += '<a class="btn btn-dim btn-light '+buttonenable+'" id="'+ actionreq+'$'+ actionreqid + '$'+ a.rawdata_id + '" onclick="actionnext(this.id)"><em class="icon ni ni-activity-round-fill"></em>'+ actionreq +'</a>';
                        //     if (rollback==true){
                        //         hasil +=  '<a class="btn btn-dim btn-light '+buttonenable+'" id="'+ actionback+'$'+ actionreqid + '$'+ a.rawdata_id + '" onclick="actionrollback(id)"><em class="icon ni ni-activity-round-fill"></em>'+ actionback +'</a>';
                                
                        //     }
                            hasil +='<a class="btn btn-dim btn-dark" id="'+ a.fieldid  + '@' + a.tablename+ '" onclick="viewdetail(id)"><em class="icon ni ni-package-fill"></em>View</a>'+
                            '<a class="btn btn-dim btn-danger" id="'+ a.rawdata_id+ '" onclick="Remove(id)"><em class="icon ni ni-delete-fill"></em> Remove</a>';
                
                        }
                        if (userid==752){
                            hasil +='<a class="btn btn-dim btn-success" id="'+ a.fieldid  + '@' + a.tablename+ '" onclick="UpdateData(id)"><em class="icon ni ni-save-fill"></em>Update</a>';
                        }
                            hasil +='</ul></div></div></td><td>' + arptname + '</td><td>' + status + '</td><td>' + reqd   + '</td></td>' + nr   + '<td><td>' + pubdate  + '</td><td>' + effdate  + '</td></tr>'
                        $("#arptlist").append(hasil);
                    }
                }
            }
        }
            // console.log('AIRPORT',a);
            
        })
}
function UpdateData(id){
    console.log(id)
    window.location.href = '/updatealldata/' + id;
    
}
function Remove(id){
    console.log(id)
  
    $("#id").val(id) 
    $("#status").val('D')

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
            $("#dataremove").submit();
        }else{
            location.reload();
        }
    })
    
}
function checkrolesactio(actionid){
    let idx = tbreff.findIndex(x => x.reff_code===actionid);
    var hhsl=false;
    console.log('checkrolesactio',idx)
    // isoriginator=false;ispiawilayah=false;ispiapusat=false;isdnp=false;
    if (idx==21){
        if (roles=='18'){
            hhsl=true;
            isoriginator=true;
        }
    }
    if (idx < 11 || idx==22){
        if (roles=='19'){
            hhsl=true;
            ispiawilayah=true;
        }
    }
    if ((idx > 8 && idx < 18) || idx==23 ){
        if (roles=='20'){
            hhsl=true;
            ispiapusat=true;
            
        }
    }
    if ((idx > 16 && idx < 21)){
        if (roles=='21'){
            hhsl=true;
            isdnp=true;
        }
    }
    // for (let i=0;i < 24;i++){
        // console.log(hhsl,'checkrolesactio')
    // }
    return hhsl;
}
function checkbuttonenable(actionid,arpt){
    // console.log('checkbuttonenable',roles,actionid,arpt.pia_wilayah_drafter, userid,arpt.pia_wilayah_pic)
    if (checkrolesactio(actionid)==false){
        return 'disabled';
    }else{

            // console.log(tbreff[21].reff_code,'tbreff[i].reff_code')
        // }
        switch (actionid) {
            case '01':
            case '11':
            if (arpt.pia_wilayah_drafter == userid){
                return 'enabled';
            }else{
                return 'disabled';
            }
                break;
            case '02':
            case '12':
            if (arpt.pia_pusat_drafter == userid){
                return 'enabled';
            }else{
                return 'disabled';
            }
                break;
            case '200':
                if (arpt.originator_pic == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '20':
                if ( arpt.originator_pic == userid){
                    return 'disabled';
                }else{
                    if (arpt.pia_wilayah_pic == userid || arpt.pia_wilayah_pic == null){
                        return 'enabled';
                    }else{
                        return 'disabled';

                    }
                }
                break;
            case '21':
            case '31':
            if (arpt.pia_wilayah_qc == userid){
                return 'enabled';
            }else{
                return 'disabled';
            }
                break;
            case '22':
            case '32':
            if (arpt.pia_pusat_qc == userid){
                return 'enabled';
            }else{
                return 'disabled';
            }
                break;
            
            case '41':
               
                if (arpt.pia_wilayah_drafter == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '42':
               
                if (arpt.pia_pusat_drafter == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '30':
            case '51':
                if (arpt.pia_wilayah_pic == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '52':
                if (arpt.pia_pusat_pic == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '50':
                if (roles == 20){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '60':
                if (arpt.pia_pusat_pic == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '70':
                if (roles == 21){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '300':
                if (arpt.pia_wilayah_pic == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
            case '400':
                if (arpt.pia_pusat_pic == userid){
                    return 'enabled';
                }else{
                    return 'disabled';
                }
                break;
        
            default:
                return 'enabled';
                break;
        }
    }

}
function CheckstatusRollback(actionid){
    console.log('CheckstatusRollback',actionid);
        rollback=true; 
    switch (actionid) {
        case '30':
            actionback="Send back to Originator";
            actionreqid='200';
            break;
        case '31':
        case '32':
            actionback=actionreq;
            actionreq="Data Completed";
            break;
        case '60':
            if (tablerequest=='arpt'){
                actionback="Send back to PIA Wilayah";

            }else{
                actionback="Send back to Originator";
            }
            actionreqid='300';
            break;
        case '80':
            actionback="Send back to PIA Pusat";
            actionreqid='400';
            break;
        case '200':
            actionreq=tbreff[1].reff_name;
            actionreqid="20";
            rollback=false;
            break;
        case '300':
            actionreq=tbreff[11].reff_name;
            // actionreqid=tbreff[ix+1].reff_code;
            actionback="Send back to Originator";
            actionreqid="200";
            break;
        case '400':
            actionreq=tbreff[11].reff_name;
            actionback="Send back to PIA Wilayah";
            actionreqid="300";
            break;
        default:
            rollback=false;
            break;
    }
    
}

function checkstatus(id){
    window.scrollTo(0,0);
    window.location.href = '/timeline/' + id;
}
function viewdetail(id){
    window.scrollTo(0,0);
    console.log('viewdetail',id)
    window.location.href = '/requestview/' + id;
}
function modalclose(){
    $("#modalform").empty();
    aboutvol('modalform');
    aboutvol('datalistarpt');
    window.scrollTo(0,0);
    // console.log(rawarpt);
}

function actionrollback(action){
    aboutvol('datalistarpt');
    var acrec=action.split("$");
    // console.log('actionrollback',action,'actionreqid',acrec[0],acrec[1],acrec[2]);
    var formshow=false,qcaction=[],req_action='',req_remark='';
    req_action='';
    req_remark='';
    formshow=true;
    // switch(acrec[1]){
    //     case '41':
    //         action=acrec[0]+'$01$'+ acrec[2]
    //     break;
    //     case '42':
    //         action=acrec[0]+'$02$'+ acrec[2]
    //     break;
    // }
    showmodalform(action);
}
function getlistchartaffect(raddataid){
   
    let idx = arp.findIndex(x => x.rawdata_id===Number(raddataid));
    console.log(arp[idx],raddataid,tablerequest);
   
    collectchart=[];
    var chart=[];
    chart['type']=arp[idx].pub_type +'_' + arp[idx].nr;
    chart['sub_id']=arp[idx].pub_type +' ' + arp[idx].nr +' PREFACE';
    chart['name']=arp[idx].pub_type +' ' + arp[idx].nr +' PREFACE';
    collectchart.push(chart)
    chart=[];
    chart['type']=arp[idx].pub_type +'_' + arp[idx].nr;
    chart['sub_id']='AD 0.4';
    chart['name']='AD 0.4 CHECKLIST OF AIP';
    collectchart.push(chart)
    if (arp[idx].tablename !=='arpt'){
        chart=[];
        chart['type']=arp[idx].pub_type +'_' + arp[idx].nr;
        chart['sub_id']=arp[idx].fieldid;
        chart['name']=arp[idx].fieldid;
        collectchart.push(chart)
    }else if (arp[idx].tablename=='arpt'){
    var rearpt=arp[idx].airport[0];
    chart=[];
    chart['type']=arp[idx].pub_type +'_' + arp[idx].nr;
    chart['sub_id']=rearpt.arpt_ident;
    chart['name']='AD 2.1 - 24 '+rearpt.icao + ' - ' +rearpt.city_name +'/'+ rearpt.arpt_name;
    collectchart.push(chart)
    }
    var nom=0;
        $.ajax({
                url: '../api/pub/rawdatachart',
                data: {'rawdataid' : raddataid},
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        chart=[];
                        chart['type']=arp[idx].pub_type +'_' + arp[idx].nr;
                        chart['sub_id']=v.chart[0].aip_sub_id;
                        chart['name']=v.chart[0].chart_name;
                        collectchart.push(chart)
                        // console.log(v.chart);
                    })
                }
        })
    
}

function actionnext(action){
    
    console.log('action',action)
    var newaction=action;
    aboutvol('datalistarpt');
    var acrec=action.split("$");
    if (acrec[1]=='70' && tablerequest=='arpt'){
        getlistchartaffect(acrec[2]);
    }
    // console.log('actionnext',action,'actionreqid',acrec[0],acrec[1],acrec[2]);
    var formshow=false,qcaction=[],req_action='',req_remark='';
    switch(acrec[1]){
        case '20':
        case '01':
        case '02':
        case '21':
        case '22':
        case '50':
        case '70':
        case '90':
        case '100':
        // case '400':
            req_action='';
            req_remark='';
            formshow=true;
            break;
        case '41':
            newaction = acrec[0]+'$51$'+ acrec[2]
            req_action='';
            req_remark='';
            formshow=true;
            break;
        case '42':
            newaction = acrec[0]+'$52$'+ acrec[2]
            req_action='';
            req_remark='';
            formshow=true;
            break;
        case '200':
            newaction = acrec[0]+'$01$'+ acrec[2]
            req_action='';
            req_remark='';
            formshow=true;
            break;
        case '300':
            newaction = acrec[0]+'$02$'+ acrec[2]
            req_action='';
            req_remark='';
            formshow=true;
            break;
        case '400':
            newaction = acrec[0]+'$90$'+ acrec[2]
            req_action=''
            req_remark=''
            formshow=true;
            break;
        // case '32':
        //     newaction = acrec[0]+'$52$'+ acrec[2]
        //     req_action=''
        //     req_remark=''
        //     formshow=true;
        //     break;
        // case '41':
        case '30':
            dtsrc={
                _token:"{{ csrf_token() }}",
                rawdataid:acrec[2],
                req_action:acrec[1],
                status_action:'1',
                status_remarks:'Data received',
                status_date: "{{ date('Y-m-d H:i:s') }}",
                status_pic: "{{ Auth::user()->id }}",
                pia_wilayah_pic:"{{ Auth::user()->id }}",
                status_raw:acrec[1],
                update_date: "{{ date('Y-m-d H:i:s') }}",
            }
            dtsrcraw={
                _token:"{{ csrf_token() }}",
                status_raw:acrec[1],
                pia_wilayah_pic:"{{ Auth::user()->id }}",
                update_date: "{{ date('Y-m-d H:i:s') }}",
            }
            updatestatus(dtsrc,dtsrcraw,acrec[2])
            break;
        case '60':
            dtsrc={
                _token:"{{ csrf_token() }}",
                rawdataid:acrec[2],
                req_action:acrec[1],
                status_action:'1',
                status_remarks:'Data received',
                status_date: "{{ date('Y-m-d H:i:s') }}",
                status_pic: "{{ Auth::user()->id }}",
                pia_pusat_pic:"{{ Auth::user()->id }}",
                status_raw:acrec[1],
                update_date: "{{ date('Y-m-d H:i:s') }}",
            }
            dtsrcraw={
                _token:"{{ csrf_token() }}",
                status_raw:acrec[1],
                pia_pusat_pic:"{{ Auth::user()->id }}",
                update_date: "{{ date('Y-m-d H:i:s') }}",
            }
            updatestatus(dtsrc,dtsrcraw,acrec[2])
            break;
        case '10':
        case '11':
        case '12':
        case '31':
        case '32':
        case '51':
        case '80':    
        
       
        // console.log("{{ csrf_token() }}");
            dtsrc={
                _token:"{{ csrf_token() }}",
                rawdataid:acrec[2],
                req_action:acrec[1],
                status_action:'1',
                status_remarks:'Data received',
                status_date: "{{ date('Y-m-d H:i:s') }}",
                status_pic: "{{ Auth::user()->id }}",
                status_raw:acrec[1],
                update_date: "{{ date('Y-m-d H:i:s') }}",
            }
            dtsrcraw={
                _token:"{{ csrf_token() }}",
                status_raw:acrec[1],
                update_date: "{{ date('Y-m-d H:i:s') }}",
            }
            updatestatus(dtsrc,dtsrcraw,acrec[2])
            break;
        }
    if (formshow==true){
        showmodalform(newaction);
    }
    window.scrollTo(0,0);
}

function showmodalform(action){
    aboutvol("modalform");
    valaction=action;
    var acrec=action.split("$");publication=false;
    let idx = arp.findIndex(x => x.rawdata_id===Number(acrec[2]));
    var rawdatareq=arp[idx];
    // console.log(rawdatareq,'rawdatareq')
  
    var arpreq=arp[idx].airport[0];isDrafterQc=false;isNotam=false;lsuser='';qcemail='';drafteremail='';
    var topia_id= '';publicationinfo=''
    if (rawdatareq.tablename=='arpt'){
        topia_id=arpreq.auth[0].id;
        publicationinfo= arpreq.icao + ' - ' +arpreq.city_name +'/'+ arpreq.arpt_name;
    }else{
        publicationinfo= rawdatareq.tablename + ' - '+ rawdatareq.fieldid;
    }
    var emailsubject='';notams=[];
    // console.log('showmodalform','arpreq',arpreq,airac,action,ispiawilayah,ispiapusat)
    if (ispiawilayah==true){
        if (arpreq.auth[0].users.length >0){
            arpreq.auth[0].users.forEach(u=>{
                // console.log(u)
                if (u.roleuser.role_id=='19'){
                    if (u.id !==userid){
                        lsuser +='<option value="'+ u.id +'">'+ u.name + ' ' + u.email  +'</option>';
                    }             

                }
                //belum di set emailnya, baru id nya doang 
            })
        }
        
    }
    if (ispiapusat==true){
        if (piapusat.length >0){
            piapusat.forEach(u=>{
            //    console.log(u);
                if (u.id !==userid){
                    lsuser +='<option value="'+ u.id +'">'+ u.name + ' ' + u.email +'</option>';
                }             
                //belum di set emailnya, baru id nya doang 
            })
        }
        
    }
    if (isAirnavpusat==true){
        if (airnav.length >0){
            airnav.forEach(u=>{
            //    console.log(u);
                if (u.id !==userid){
                    lsuser +='<option value="'+ u.id +'">'+ u.name + ' ' + u.email +'</option>';
                }             
                //belum di set emailnya, baru id nya doang 
            })
        }
        
    }
    // console.log('lsuser',lsuser);
    rawarpt=arp[idx];puboriginator=false;showrunningtext=true;
    switch (acrec[1]) {
        case '20':
            showrunningtext=false;
            isNotam=true;
            emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="Originator '+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="Originator '+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="originator_pic" id="originator_pic" value="'+ userid + '">';
            if (rawarpt.pia_wilayah_pic !== null){
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="Originator '+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="Originator '+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_wilayah_pic" id="pia_wilayah_pic" value="'+ rawarpt.pia_wilayah_pic + '">';
                // emailsubject +='<input type="hidden" name="pia_wilayah_pic" id="pia_wilayah_pic" value="'+ rawarpt.pia_wilayah_pic + '">';
            }
            publication = true;puboriginator=true;
            if (airac){
                $("#pub_date").empty();
                let dd = new Date()
                Airac(dd,'major')
            }
            break;
        // case '30':
        //     isNotam=true;
        //     emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="[no-reply] Request for Publication '+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
        //                 '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
        //                 '<input type="hidden" name="pia_wilayah_pic" id="pia_wilayah_pic" value="'+ userid + '">';
                        
        //     break;

        case '50':
            // console.log(rawdatareq)
            if (rawdatareq.tablename=='arpt'){
                topia_id=arpreq.auth[0].id;
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA '+ arpreq.auth[0].name + '">'+
                            '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                            '<input type="hidden" name="pia_pusat_pic" id="pia_pusat_pic" value="'+ userid + '">'+
                            '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA '+arpreq.auth[0].name +'">';

            }else{
                topia_id=userid;
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="Originator '+rawdatareq.tablename + ' - '+ rawdatareq.fieldid + '">'+
                            '<input type="hidden" name="subject" id="subject" value="'+rawdatareq.tablename + ' - '+ rawdatareq.fieldid +'">'+
                            '<input type="hidden" name="pia_pusat_pic" id="pia_pusat_pic" value="'+ userid + '">'+
                            '<input type="hidden" name="emailsender" id="emailsender" value="Originator '+rawdatareq.tablename + ' - '+ rawdatareq.fieldid +'">';
            }
            publication = true;puboriginator=false;
           
            
            break;
        case '01':
            pic='pia_wilayah_pic';
            qc='pia_wilayah_qc';
            drafter='pia_wilayah_drafter';
            emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA '+ arpreq.auth[0].name + ' to Drafter">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA '+arpreq.auth[0].name +'">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">';
            isDrafterQc=true;
            // untuk status kalau dari QC send back to drafter, select option drafter jangan di munculkan dan pilihan drafter n QC di kosongkan
            if (rawarpt.pia_wilayah_drafter !== null &&  rawarpt.pia_wilayah_qc !== null){
                lsuser=''
                isDrafterQc=false;
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="QC PIA '+ arpreq.auth[0].name + ' to Drafter">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="QC PIA '+arpreq.auth[0].name +'">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_wilayah_drafter" id="pia_wilayah_drafter" value="'+ rawarpt.pia_wilayah_drafter + '">';
            }
            break;
        case '41':
                lsuser=''
                isDrafterQc=false;
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="QC PIA '+ arpreq.auth[0].name + ' to Drafter">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="QC PIA '+arpreq.auth[0].name +'">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_wilayah_drafter" id="pia_wilayah_drafter" value="'+ rawarpt.pia_wilayah_drafter + '">';
            
            break;
        case '42':
                lsuser=''
                isDrafterQc=false;
                if (rawdatareq.tablename=='arpt'){
                    emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="QC PIA Pusat to Drafter">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="QC PIA Pusat">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_pusat_drafter" id="pia_pusat_drafter" value="'+ rawarpt.pia_pusat_drafter + '">';

                }else{
                    emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="QC PIA Pusat to Drafter">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="QC PIA Pusat">'+
                        '<input type="hidden" name="subject" id="subject" value="'+rawdatareq.tablename + ' - '+ rawdatareq.fieldid +'">'+
                        '<input type="hidden" name="pia_pusat_drafter" id="pia_pusat_drafter" value="'+ rawarpt.pia_pusat_drafter + '">';

                }
               
            
            break;
        case '02':
            pic='pia_pusat_pic';
            qc='pia_pusat_qc';
            drafter='pia_pusat_drafter';
            if (rawdatareq.tablename=='arpt'){
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA Pusat to Drafter">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA Pusat to Drafter">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">';

            }else{
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA Pusat to Drafter">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA Pusat to Drafter">'+
                        '<input type="hidden" name="subject" id="subject" value="'+rawdatareq.tablename + ' - '+ rawdatareq.fieldid +'">';

            }
           
            isDrafterQc=true;
            if (rawarpt.pia_pusat_drafter !== null &&  rawarpt.pia_pusat_qc !== null){
                lsuser=''
                isDrafterQc=false;
                emailsubject +='<input type="hidden" name="pia_pusat_drafter" id="pia_pusat_drafter" value="'+ rawarpt.pia_pusat_drafter + '">';
            }
            break;
        case '21':
            emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="Drafter PIA '+ arpreq.auth[0].name + ' to QC">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_wilayah_qc" id="pia_wilayah_qc" value="'+ rawarpt.pia_wilayah_qc + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="Drafter PIA '+arpreq.auth[0].name +'">';

            break;
        case '22':
            if (rawdatareq.tablename=='arpt'){
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="Drafter PIA Pusat to QC">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_pusat_qc" id="pia_pusat_qc" value="'+ rawarpt.pia_pusat_qc + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="Drafter PIA Pusat">';

            }else{
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="Drafter PIA Pusat to QC">'+
                        '<input type="hidden" name="subject" id="subject" value="'+rawdatareq.tablename + ' - '+ rawdatareq.fieldid +'">'+
                        '<input type="hidden" name="pia_pusat_qc" id="pia_pusat_qc" value="'+ rawarpt.pia_pusat_qc + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="Drafter PIA Pusat">';
            }
           

            break;
        case '51':
            emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="QC to PIC PIA '+ arpreq.auth[0].name + '">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_wilayah_pic" id="pia_wilayah_pic" value="'+ rawarpt.pia_wilayah_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="QC PIA '+arpreq.auth[0].name +'">';

            break;
        case '52':
            if (rawdatareq.tablename=='arpt'){
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="QC to PIC PIA Pusat">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_pusat_pic" id="pia_pusat_pic" value="'+ rawarpt.pia_pusat_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="QC PIA Pusat">';

            }else{
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="QC to PIC PIA Pusat">'+
                        '<input type="hidden" name="subject" id="subject" value="'+rawdatareq.tablename + ' - '+ rawdatareq.fieldid +'">'+
                        '<input type="hidden" name="pia_pusat_pic" id="pia_pusat_pic" value="'+ rawarpt.pia_pusat_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="QC PIA Pusat">';

            }
            

            break;
        case '70':
            if (rawdatareq.tablename=='arpt'){
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA Pusat">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_pusat_pic" id="pia_pusat_pic" value="'+ rawarpt.pia_pusat_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA Pusat">';

            }else{
                emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA Pusat">'+
                        '<input type="hidden" name="subject" id="subject" value="'+rawdatareq.tablename + ' - '+ rawdatareq.fieldid +'">'+
                        '<input type="hidden" name="pia_pusat_pic" id="pia_pusat_pic" value="'+ rawarpt.pia_pusat_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA Pusat">';
            }
          

            break;
        case '200':
            emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA '+ arpreq.auth[0].name + ' to Originator">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="originator_pic" id="originator_pic" value="'+ rawarpt.originator_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA '+arpreq.auth[0].name +'">';

            break;
        case '300':
            emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="PIC PIA Pusat to PIA '+ arpreq.auth[0].name + '">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_wilayah_pic" id="pia_wilayah_pic" value="'+ rawarpt.pia_wilayah_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="PIC PIA Pusat">';

            break;
        case '400':
            emailsubject='<input type="hidden" name="emailsubject" id="emailsubject" value="DNP to PIC PIA Pusat ">'+
                        '<input type="hidden" name="subject" id="subject" value="'+arpreq.icao + ' - '+ arpreq.arpt_name +'">'+
                        '<input type="hidden" name="pia_pusat_pic" id="pia_pusat_pic" value="'+ rawarpt.pia_pusat_pic + '">'+
                        '<input type="hidden" name="emailsender" id="emailsender" value="DNP">';

            break;
        default:
            break;
    }
    
    // console.log('isDrafterQc',isDrafterQc,'publication',publication);
    if (rawdatareq.pub_date == null){
        showrunningtext=false;
    }
    $("#pub_date").empty();
    modal='<div class="modal-dialog-lg" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header bg-gray">'+
                    '<h5 class="modal-title text-white">'+ acrec[0] +'</h5>'+
                    '<a onclick="modalclose()" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<em class="icon ni ni-cross"></em>'+
                    '</a>'+
                '</div>'+
                '<div class="modal-body">';
                // if (showrunningtext==true){
                //     modal += '<div class="runtext-container mb-3">'+
                //         '<div class="main-runtext">'+
                //             '<marquee direction="" onmouseover="this.stop();" onmouseout="this.start();">'+
                //             '<div class="holder">'+
                //                 '<div class="text-container">'+
                //             '&nbsp; &nbsp;  &nbsp;<a data-fancybox-group="gallery" class="fancybox" href="template/images/logo.png" id="marq_00"></a>'+
                //                 '</div>'+
                //                 '<div class="text-container">'+
                //             '&nbsp; &nbsp;  &nbsp;<img src="template/images/logo.png"> &nbsp; <a data-fancybox-group="gallery" class="fancybox" ref="template/images/logo.png" id="marq_3"></a>'+
                //                 '</div>'+
                //                 '<div class="text-container">'+
                //             '&nbsp; &nbsp;  &nbsp; <a data-fancybox-group="gallery" id="marq_4" class="fancybox"></a>'+
                //                 '</div>'+
                //                 '<div class="text-container">'+
                //             '&nbsp; &nbsp;  &nbsp;<img src="template/images/logo.png"> &nbsp; <a data-fancybox-group="gallery" class="fancybox" href="template/images/logo.png" id="marq_0"></a>'+
                //                 '</div>'+
                //                 '<div class="text-container">'+
                //             '&nbsp; &nbsp;  &nbsp; <img src="template/images/logo.png"> &nbsp;<a data-fancybox-group="gallery" class="fancybox" ref="template/images/logo.png" id="marq_1"></a>'+
                //                 '</div>'+
                //                 '<div class="text-container">'+
                //             '&nbsp; &nbsp;  &nbsp; <a data-fancybox-group="gallery" class="fancybox" id="marq_2"></a>'+
                //                 '</div>'+
                //             '</div>'+
                //             '</marquee>'+
                //         '</div>'+
                //     '</div>';
                    
                // }
                modal +=  '<form action="/DataRequest/save" method="post"  enctype="multipart/form-data" id="form_">'+
                        '<div class="form-group">'+
                        '<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">'+
                        '<input type="hidden" name="rawdataid" id="rawdataid" value="'+ acrec[2] +'">'+
                        '<input type="hidden" name="req_action" id="req_action" value="'+ acrec[1] +'">'+
                        '<input type="hidden" name="status_raw" id="status_raw" value="'+ acrec[1] +'">'+
                        '<input type="hidden" name="status_pic" id="status_pic" value="'+ userid +'">'+
                        '<input type="hidden" name="status_date" id="status_date" value="{{ date('Y-m-d H:i:s') }}">'+
                        '<input type="hidden" name="update_date" id="update_date" value="{{ date('Y-m-d H:i:s') }}">'+
                        '<input type="hidden" name="raw_type" id="raw_type">'+
                        '<input type="hidden" name="pia_id" id="pia_id" value="'+ topia_id+'">'+
                        '<input type="hidden" name="publication" id="publication" value="'+action+'">'+
                        emailsubject;
                        // '<input type="hidden" name="email" id="email" value="vhamdillah@yahoo.com">';
                        if (isDrafterQc==true){
                            modal+= '<input type="hidden" name="'+pic+'" id="'+pic+'" value="{{ Auth::user()->id }}">'+
                                    '<div class="row">'+
                                    '<div class="col-md-6">'+
                                    '<label class="form-label" for="'+drafter+'">Drafter</label>'+
                                    '<select id="'+drafter+'" name="'+drafter+'" type="text" onchange="comparedraftqc()" class="form-control">'+
                                    lsuser+
                                    '</select>'+
                                    '</div>'+
                                    '<div class="col-md-6">'+
                                    '<label class="form-label" for="'+qc+'">QC</label>'+
                                    '<select id="'+qc+'" name="'+qc+'" type="text" onchange="comparedraftqc()"  class="form-control">'+
                                    lsuser+
                                    '</select>'+
                                    '</div></div>';
                            
                        }
                        if (publication==true){
                            modal+='<div class="row mt-3">';
                                    if (airac==true && puboriginator==true){
                                        modal+= '<div class="form-check col-md-3">'+
                                                '<input class="form-check-input pubchecktype" onclick="selectradio()" type="radio" id="major" value="major" name="generate">'+
                                                '<label class="form-check-label" for="major">Major Changes</label>'+
                                                '</div>'+
                                                '<div class="form-check col-md-3">'+
                                                '<input class="form-check-input pubchecktype" onclick="selectradio()" type="radio" id="minor" value="minor" name="generate">'+
                                                '<label class="form-check-label" for="minor">Minor Changes</label>'+
                                                '</div>';
                                    }
                                    modal+='</div>'+
                                            '<div class="row mt-2">'+
                                            '<div class="col-md-3">'+
                                            '<label class="form-label" for="source">Publication Type</label>'+
                                            '<select class="form-control" onchange="changesource()" name="pub_type" id="pub_type">';
                                            sourcelist.forEach(s=>{
                                                modal+= '<option key="'+s.key+'" value="'+s.value+'">'+s.value+'</option>';
                                            })
                            modal+= '</select>'+
                                    '</div>'+
                                    '<div class="col-md-3" id="pubnumberid">'+
                                    '<label class="form-label" for="nr">Number</label>'+
                                    '<input type="text" onfocusout="checknumberpub()" class="form-control" id="nr" name="nr">'+
                                    '</div>';

                                    if (airac==true){
                                        if (puboriginator==true){
                                        modal+='<div class="col-md-3">'+
                                                '<label class="form-label" for="pub_date">Publication Date</label>'+
                                                '<select id="pub_date" name="pub_date" type="date" onchange="getAirac()" class="form-control">'+
                                                '</select>'+
                                                '</div>';
                                        }else{
                                            modal+='<div class="col-md-3">'+
                                                    '<label class="form-label" for="pub_date">Publication Date</label>'+
                                                    '<input id="pub_date" name="pub_date"  type="text" class="form-control">'+
                                                    '</div>';
                                        }
                                        modal+= '<div class="col-md-3">'+
                                                '<label class="form-label" for="eff_date">Effective Date</label>'+
                                                '<input id="eff_date" name="eff_date"  type="text" class="form-control">'+
                                                '</div>'+
                                                '</div>';
                                    }else{
                                        modal+='<div class="col-md-3">'+
                                                '<label class="form-label" for="pub_date">Publication Date</label>'+
                                                '<input id="pub_date" name="pub_date" type="date" class="form-control" value="{{ date('Y-m-d') }}">'+
                                                '</select>'+
                                                '</div>'+
                                                '<div class="col-md-3">'+
                                                '<label class="form-label" for="eff_date">Effective Date</label>'+
                                                '<input id="eff_date" name="eff_date"  type="date" class="form-control" value="{{ date('Y-m-d') }}">'+
                                                '</div>'+
                                                '</div>';
                                    }
                        }
                        modal+='<label class="form-label" for="status_remarks">Remarks</label>'+
                                '<div class="form-control-wrap">'+
                                '<textarea type="text" class="form-control" name="status_remarks" id="status_remarks"></textarea>'+
                                '</div>'+
                                '<div class="row col-md-12 mt-3">'+
                                '<div class="col-md-6">'+
                                '<strong>Attachment</strong>'+
                                '<br>'+
                                '<input type="file" accept=".gif,.jpg,.jpeg,.png,.pdf" name="files[]" id="files" ref="files" multiple @change="filesSelected" class="form-control-file">'+
                                '</div>';
                            if (acrec[1]=='70'){
                                modal+= '<div class="col-md-6">'+
                                        '<strong>Publication Files</strong>'+
                                        '<br>'+
                                        '<a onclick="showpublicationfile()" class="btn btn-dim btn-light">Upload Files</a>'+
                                        '</div>';
                            }
                            if (isNotam==true){
                                modal+='<div class="col-md-6">'+
                                        '<strong>NOTAM</strong>'+
                                        '<br>'+
                                        '<a id="'+arpreq.icao+'" onclick="checknotam(this.id)" class="btn btn-dim btn-light">Check Notam</a>'+
                                        '</div>';
                            }
                            modal+='</div>'+
                                    '</div>'+
                                    '<br>'+
                                    '<div class="row">'+
                                    '<div class="col-md-6">'+
                                        '<button onclick="modalclose()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Cancel</button>'+
                                        '&nbsp;'+
                                        '<button onclick="insertnotam()" type="submit" class="btn btn-dim btn-dark" id="btn_save"><i class="icon ni ni-save-fill"></i> Update</button>'+
                                    '</div>'+
                                '</div>'+
                            '</form>'+
                        '</div>'+
                    '<div class="modal-footer bg-light">'+
                    '<span class="sub-text">'+publicationinfo+'</span>'+
                '</div>'+
            '</div>'+
        '</div>'
        $("#modalform").append(modal);
        if (isDrafterQc==true){
            $("#" + drafter).val('');
            $("#" + qc).val('');

        }
        // if (publication == true && puboriginator==false){
        //         $("#marq_00").html('Please provide for publication number !');
        //     }
        // if (rawdatareq.pub_date !== null){
        //     var ddt=new Date(rawdatareq.pub_date);
        //     var ldnp = getIntervalInDays(ddt,-6);
        //     var nnow = GetIntervalinDate(ldnp,new Date());
        //     var pdate='Publication Date : ' + DateFormat(new Date(rawdatareq.pub_date),false,true);
        //     var edate='Effective Date : ' + DateFormat(new Date(rawdatareq.eff_date),false,true);
        //     var ddate='The last delivery to DNP : ' + DateFormat(new Date(ldnp),false,true);
        //     // console.log(publication == true && puboriginator==false,publication , puboriginator)
          
        //     $("#marq_0").html(rawdatareq.raw_type + ' Publication');
        //     $("#marq_1").html(pdate)
        //     $("#marq_2").html(edate)
        //     $("#marq_3").html(ddate)
        //     $("#marq_4").html('Remaining days : ' + nnow + ' days' )
        // }
        if(puboriginator==true){
            $("#pubnumberid").hide();
        }else{
            var air=DateFormat(new Date(rawdatareq.pub_date),false,true);
            var air1=DateFormat(new Date(rawdatareq.eff_date),false,true);
            $("#eff_date").val(air1);
            $("#pub_date").val(air);
            console.log(air,air1, $("#eff_date").val());
        }
        if (acrec[1]=='90' || acrec[1]=='70'){
            // console.log(arp[idx].pub_date);
            var pair=DateFormat(new Date(arp[idx].pub_date),false,true);
            // console.log(arp[idx].pub_date,pair);
            $("#pubdate").val(pair);
            $("#effdate").val(DateFormat(new Date(arp[idx].eff_date)));
            $("#source").val(arp[idx].pub_type);
            $("#sourcenr").val(arp[idx].nr);
        }
}
function checknumberpub(){
    var nrpub=$("#nr").val()
    var ix=arp.findIndex(x=>x.nr===nrpub)
    if (ix !== -1){
        var app=arp[ix]
        $("#pub_type").val(app.pub_type);
        let todate = new Date();
        var nnow = getIntervalInDays(todate,14);
        var ndt = dateToJulianNumber(nnow);
        var ppdate=new Date(app.pub_date)
        var ndt1 = dateToJulianNumber(ppdate);
        console.log('checknumberpub',ndt1,ndt)
        if (ndt1 < ndt){
                Swal.fire({
                title: 'Time is too short',
                text: "The time for the publication process is too short, please use the Next AIRAC",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Use Next Airac!'
                
            }).then((result) => {
                if (result.value) {
                    $("#nr").val(Number(nrpub)+1) 
                }
            })
        }else{
            var air=DateFormat(new Date(app.pub_date),false,true);
            $("#pub_date").val(air);
            var air1=DateFormat(new Date(app.eff_date),false,true);
            $("#eff_date").val(air1);
            
        }
    }

    console.log('checknumberpub bawah',nrpub,arp[ix])
}
function comparedraftqc(){
    var drf=$("#" + drafter).val();uqc=$("#" + qc).val();
    // console.log(drf,uqc);
    if (drf==uqc){
        Swal.fire(
            'Warning!',
            'Drafter and QC can not be the same user',
            'warning'
        );
        $("#" + drafter).val('')
        $("#" + qc).val('')
    }
}
function shownotamform(){
    aboutvol("notamform");
    aboutvol("modalform");
    // console.log('show notam form',valaction)
    var acrec=valaction.split("$");publication=false;
    let idx = arp.findIndex(x => x.rawdata_id===Number(acrec[2]));
    var arpreq=arp[idx].airport[0];isDrafterQc=false;isNotam=false;
    rawarpt=arp[idx];
    $("#notamform").empty();
    modal='<div class="modal-dialog-lg" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header bg-gray">'+
                    '<h5 class="modal-title text-white">NOTAM</h5>'+
                    '<a onclick="notamclose()" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<em class="icon ni ni-cross"></em>'+
                    '</a>'+
                '</div>'+
                '<div class="modal-body">'+
                    // '<form action="/notam/save" method="post"  enctype="multipart/form-data" id="form_">'+
                    '<form>'+
                        '<div class="form-group">'+
                        '<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">'+
                        '<input type="hidden" name="rawdataid" id="rawdataid" value="'+ acrec[2] +'">'+
                        '<input type="hidden" name="status_pic" id="status_pic" value="{{ Auth::user()->id }}">'+
                        '<label class="form-label" for="notam_nr">NOTAM Nr.</label>'+
                            '<div class="form-control-wrap">'+
                                '<input type="text" class="form-control" name="notam_nr" id="notam_nr"></input>'+
                            '</div>'+
                            '<label class="form-label" for="notam_content">Content</label>'+
                            '<div class="form-control-wrap">'+
                                '<textarea type="text" class="form-control" name="notam_content" id="notam_content"></textarea>'+
                            '</div>'+
                        '</div>'+
                        '<br>'+
                        '<div class="row">'+
                            '<div class="col-md-6">'+
                                '<a onclick="notamclose()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Close</a>'+
                                '&nbsp;'+
                                '<button onclick="notamcolect()" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</button>'+
                            '</div>'+
                        '</div>'+
                    '</form>'+
                '</div>'+
                '<div class="modal-footer bg-light">'+
                    '<span class="sub-text">'+arpreq.icao + ' - ' +arpreq.city_name +'/'+ arpreq.arpt_name+'</span>'+
                '</div>'+
            '</div>'+
        '</div>'
        $("#notamform").append(modal);
}
function showpublicationfile(){
    // valaction adalah  id untuk balik ke form publikasi
    aboutvol("publicationfileform");
    aboutvol("modalform");
    console.log('show notam form',valaction)
    var acrec=valaction.split("$");publication=false;
    let idx = arp.findIndex(x => x.rawdata_id===Number(acrec[2]));
    var arpreq=arp[idx].airport[0];isDrafterQc=false;isNotam=false;
    rawarpt=arp[idx];
    var collchart=rawarpt.attach
    console.log(arp)
    $("#publicationfileform").empty();
    modal='<div class="modal-dialog-lg" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header bg-gray">'+
                    '<h5 class="modal-title text-white">Upload Publication Files</h5>'+
                    '<a onclick="pubuploadclose()" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<em class="icon ni ni-cross"></em>'+
                    '</a>'+
                '</div>'+
                '<div class="modal-body">'+
                    '<form action="/publication/upload" method="post" enctype="multipart/form-data" id="form_attach">'+
                        '<div class="form-group">'+
                        '<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">'+
                        '<input type="hidden" name="rawdataid" id="rawdataid" value="'+ acrec[2] +'">'+
                        '<input type="hidden" name="status_pic" id="status_pic" value="{{ Auth::user()->id }}">'+
                        '<input type="hidden" name="pub_nr" id="pub_nr" value="'+rawarpt.pub_type+'_'+rawarpt.nr+'">'+
                        '<input type="hidden" name="file_att" id="file_att" value="P">'+
                        '<input type="hidden" name="name" id="pub_name">'+
                        '<input type="hidden" name="sub_id" id="sub_id">'+
                        '<input type="hidden" name="backto" id="backto"  value="'+valaction+'">'+
                            '<div class="form-control-wrap">'+
                            '<select class="form-control" id="subid">';
                            collectchart.forEach(s=>{
                                // console.log(s,collchart)
                                var iix=collchart.findIndex(x=>x.sub_id===s.sub_id)
                                if (iix ==-1){
                                    modal+= '<option value="'+s.sub_id+'@'+s.name+'">'+s.name+'</option>';
                                }
                            })
                            modal+= '</select>'+
                            '</div>'+
                            '<label class="form-label" for="notam_content">Files</label>'+
                            '<div class="form-control-wrap">'+
                           '<input type="file" accept=".gif,.jpg,.jpeg,.png,.pdf" name="filespub[]" id="filespub" ref="filespub" multiple @change="filesSelected" class="form-control-file">'+
                            '</div>'+
                        '</div>'+
                        '<br>'+
                        '</form>'+
                        '<div class="row">'+
                            '<div class="col-md-6">'+
                                '<a onclick="pubuploadclose()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Close</a>'+
                                '&nbsp;'+
                                '<button onclick="publicationpush()" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</button>'+
                            '</div>'+
                        '</div>'+
                '</div>'+
                '<div class="modal-footer bg-light">'+
                    '<span class="sub-text">'+publicationinfo+'</span>'+
                '</div>'+
            '</div>'+
        '</div>'
        $("#publicationfileform").append(modal);
}
function notamclose() {
    aboutvol("notamform");
    aboutvol("modalform");
}
function pubuploadclose(){
    aboutvol("publicationfileform");
    aboutvol("modalform");
}
function notamcolect(){
   
    var notm={
        _token:"{{ csrf_token() }}",
        rawdataid:$("#rawdataid").val(),
        notam_nr:$("#notam_nr").val(),
        notam_content:$("#notam_content").val(),
        status_pic:$("#status_pic").val()
    }
    notams.push(notm);
    // console.log('notamcolect',notams)
    Swal.fire(
                'Save!',
                'NOTAM has been Save.',
                'success'
            );
    // notamclose();
    shownotamform()
}
function publicationpush(){
    var ssb=$("#subid").val().split('@');
    $("#pub_name").val(ssb[1]);
    $("#sub_id").val(ssb[0]);
   
    
    console.log( $("#back_to").val())
    var public={
        _token:"{{ csrf_token() }}",
        rawdataid:$("#rawdataid").val(),
        pub_nr:$("#pub_nr").val(),
        sub_id:ssb[0],
        name:ssb[1],
        file_att:'P',
    }
    publicationattach.push(public);

    $("#form_attach").submit()
    // // console.log('notamcolect',notams)
    // Swal.fire(
    //             'Save!',
    //             'Attachment has been Save.',
    //             'success'
    //         );
   // notamclose();
    // showpublicationfile()
}

function insertnotam(){
    if (notams.length >0){
        notams.forEach(nt=>{
            // console.log(nt)
            $.ajax({
                url: "/notam/save",
                type: "POST",
                data: JSON.stringify(nt),
                cache: false,
                contentType: 'application/json; charset=utf-8',
                processData: false,
                success: function (response)
                {
                    location.reload();
                }
            });

        })

    }
}
function checknotam(icao){
    shownotamform()
    var linknotam = 'http://aim-jakarta.co.id/searchpib/?link=view2&aero1=' +icao
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open(linknotam, 'Set Latitude and Longitude', params)
}
function updatestatus(update,rawdata,id) {
    // console.log('updatestatus ',"{{ URL::to('/') }}/DataRequest/save",  id,JSON.stringify(rawdata))
    Swal.fire({
        title: 'Update Status',
        text: "The data status will be updated!",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, updated it!'
           
    }).then((result) => {
        if (result.value) {
            var rawid=''
            // console.log('Berhasil masuk YES ', "{{ URL::to('/') }}/DataRequest/save", id,update,rawdata)
            $.ajax({
                url:  "/DataRequest/save", //'/DataRequest/save',
                type: "POST",
                data: JSON.stringify(update),
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
                        'Data Status has been updated.',
                        'success'
                    );
                    // $.ajax({
                    //     url: "{{ URL::to('/') }}/api/pub/rawdata/update/" + id,
                    //     type: "POST",
                    //     data: JSON.stringify(rawdata),
                    //     cache: false,
                    //     contentType: 'application/json; charset=utf-8',
                    //     processData: false,
                    //     success: function (response)
                    //     {
                    //         Swal.fire(
                    //             'Updates!',
                    //             'Raw Data Status has been updated.',
                    //             'success'
                    //         );
                    //         location.reload();
                    //     }
                    // });
                    location.reload();
                }
            });

            
        }else{
            location.reload();

        }
    })

}
function changesource(){
    aboutvol("modalform");
    $sair=$("#pub_type").val();
    switch ($sair.substr(0,5)) {
        case "AIRAC":
            airac=true;
            break;
        default:
            airac=false;
            break;
    }
    $("#modalform").empty();
    showmodalform(valaction)
    $("#pub_type").val($sair);
    // console.log($("#pub_type").val(),valaction,airac);
}
function selectradio(e) {
    let dd = new Date();
    $('.pubchecktype:radio:checked').each(function(i){
        // console.log($(this).val())
        $("#pub_date").empty();
        if ($(this).val()=='minor'){
            $("#raw_type").val('MINOR');
            Airac(dd,'minor');
            ismajor=false;
        }else{
            $("#raw_type").val('MAJOR');
            ismajor=true;
            Airac(dd,'major');
        }
        // reloadAtslines($(this).val());
    });
    // isminor=false;
    // ismajor=false
    // if (e.target.id == "major" && e.target.checked == true) {
    //     ismajor=true
    //     const dd = new Date()
    //     Airac(dd,'major')
    // } else if (e.target.id == "minor" && e.target.checked == true) {
    //     isminor=true
    //     const dd = new Date()
    //     Airac(dd,'minor')
    // }
    // console.log(e)
}
function Airac(date,sel) {
    $("#pub_date").empty();
    var nnow = getIntervalInDays(date,56);
    // var hhr = date;
    // var hh1 = new Date(hhr)
    // hh1.setDate(hh1.getDate() + 56)
    // // const diffInMs = Math.abs( date1 + day );
    // console.log('AIRAC MAYOR ',hh1)

    var ndt = dateToJulianNumber(nnow);
    // console.log(date,nnow,ndt)
    var nom=0;
    var pathdetail= pathpop()   + '/api/airac';
        $.ajax({
                url: pathdetail,
                data: {'sort' : 'eff_date:asc'},
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        // console.log(v);
                        let dti;
                        if (sel=='major'){
                            var nnow = getIntervalInDays(date,56);
                            var ndt = dateToJulianNumber(nnow);
                            dti = dateToJulianNumber(new Date(v.maj_pub));
                        }else{
                            var nnow = getIntervalInDays(date,40);
                            var ndt = dateToJulianNumber(nnow);
                            dti = dateToJulianNumber(new Date(v.min_pub));
                        }
                        // console.log(dti,ndt)
                        if (dti > ndt){
                            nom++
                            if (nom < 15){
                                var air;
                                if (sel=='major'){
                                    air=DateFormat(new Date(v.maj_pub),false,true);
                                    // air=v.maj_pub;
                                }else{
                                    air=DateFormat(new Date(v.min_pub),false,true);
                                    // air=v.min_pub;
                                }
                                hasil= '<option key="'+v.id+'" value="'+air+'">'+air+'</option>';
                                $("#pub_date").append(hasil);
                                if (nom==1){
                                    getAirac()
                                }
                                // pubdate.push(air)
                            }
                            // console.log('INI YG DIAMBIL',pubdate)
                        }
                    })
                }
        })
    
}
function getAirac() {
    let dt = document.getElementById("pub_date").value;
    // $("#sourcenr").val('');
    var pathdetail= pathpop()   + '/api/airac',qry={};
    if (ismajor==true){
        qry ={'maj_pub': dt};
    }else{
        qry ={'min_pub': dt};
    }
   
    $.ajax({
            url: pathdetail,
            data: qry,
            type: "json",
            method: "GET",

            success: function (result) {
                $.each(result.data, function (k, tbl) {
                    // console.log(tbl);
                    // var today = new Date(tbl.eff_date);
                    // // console.log(new Date(tbl.eff_date))
                    // // var dd = String(today.getDate()).padStart(2, '0');
                    // // var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    // var yyyy = today.getFullYear().toString().substr( -2 );
                    air=DateFormat(new Date(tbl.eff_date),false,true);
                                    // air=v.min_pub;
                                
                                // hasil= '<option key="'+v.id+'" value="'+air+'">'+air+'</option>';
                    // // // today = dd + '/' + mm + '/' + yyyy;
                    // let src = document.getElementById("sourcenr").value;
                    // $("#sourcenr").val(src.substr(0,2) + '/' + yyyy);
                    $("#eff_date").val(air);
                    // effdate=tbl.eff_date
                })
        }
    })
}
</script>
@endsection