@extends('layouts.app')

@section('template_title')
    Welcome  {{Auth::user()->name}}
@endsection

@section('head')
<style>
marquee {
	margin-top: 5px;
	width: 100%;
}

.runtext-container {
    overflow: hidden!important;
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

    /*width: 850px;*/
    width: 100%;
    overflow-x: hidden;
    overflow-y: visible;
    /*margin: 0 60px 0 30px;*/
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
<div class="col-lg-12">
    <div class="runtext-container mb-3" id="runningid" style="visibility:hidden">
        <div class="main-runtext">
            <marquee direction="" onmouseover="this.stop();" onmouseout="this.start();">
            <div class="holder">
            <div class="text-container">
            &nbsp; &nbsp;  &nbsp;<img src="template/images/logo.png"> &nbsp; <a data-fancybox-group="gallery" class="fancybox" ref="template/images/logo.png" id="marq_3"></a>
                </div>
                <div class="text-container">
            &nbsp; &nbsp;  &nbsp; <a data-fancybox-group="gallery" id="marq_4" class="fancybox"></a>
                </div>
                <div class="text-container">
            &nbsp; &nbsp;  &nbsp;<img src="template/images/logo.png"> &nbsp; <a data-fancybox-group="gallery" class="fancybox" href="template/images/logo.png" id="marq_0" title="THE ELECTRIC LIGHTING ACT: section 35"></a>
                </div>
                <div class="text-container">
            &nbsp; &nbsp;  &nbsp; <img src="template/images/logo.png"> &nbsp;<a data-fancybox-group="gallery" class="fancybox" ref="template/images/logo.png" id="marq_1" title="THE ELECTRIC LIGHTING ACT: section 35"></a>
                </div>
                <div class="text-container">
            &nbsp; &nbsp;  &nbsp; <a data-fancybox-group="gallery" class="fancybox" id="marq_2"></a>
                </div>
               
            </div>

            </marquee>
        </div>
    </div>
    <div class="card card-bordered h-100">
        <div class="card-inner border-bottom">
            <div class="card-title-group">
                <div class="card-title">
                    <h6 class="title" id="timetitle"></h6>
                </div>
            </div>
        </div>
        <div class="card-tools">
            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
        </div>
        <div  class="card-inner">
            <div id="maintime" class="timeline" style="visibility:visible">
                <h6 class="timeline-head" id="arpttitle"></h6>
                <ul class="timeline-list" id="maintime"></ul>

            </div>
            <div id="attachlist" style="visibility:hidden">
                <h6 class="timeline-head" id="arpttitle"></h6>
                <div class="row col-md-12">
                    <div class="col-md-6">
                        <h6>Supporting Files</h6>
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>No</th>
                                    <th>File Name</th>
                                </tr>
                            </thead>
                            <tbody id="Attach">
                            
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Publication Files</h6>
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>No</th>
                                    <th>File Name</th>
                                </tr>
                            </thead>
                            <tbody id="Attach1">
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            <div class="card-tools">
            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
        </div>
    </div><!-- .card -->
</div><!-- .col -->
@endsection
@section('footer_scripts')

<script type="text/javascript">
$("#runningid").hide();
var gray= '#dfd0d0';
var black= '#000005';
var trk =@json($timeline);var track =trk[0];
var tbreff =@json($tbreff);

$("#attachlist").hide();
// console.dir(track);
if (track.pub_date !== null){
    aboutvol("runningid");
    var ddt=new Date(track.pub_date);
    var ldnp = getIntervalInDays(ddt,-6);
    var nnow = GetIntervalinDate(ldnp,new Date()); 
    var pdate='Publication Date : ' + DateFormat(new Date(track.pub_date),false,true);
    var edate='Effective Date : ' + DateFormat(new Date(track.eff_date),false,true);
    var ddate='The last delivery to DNP : ' + DateFormat(new Date(ldnp),false,true);
    // console.log(ddt,'DDDDDDDTTTTTT',nnow,ldnp)
    $("#marq_0").html(track.raw_type + ' Publication');
    $("#marq_1").html(pdate)
    $("#marq_2").html(edate)
    $("#marq_3").html(ddate)
    $("#marq_4").html('Remaining days : ' + nnow + ' days' )
}
var tracking=[];tbrefflength=tbreff.length;idtime='0';
var dtraw;no=1;trrem='';pic='';trtime='';rshort='';trcolor=gray;adadata=false;main=true;reffname='';reffcode='';isAttach=false;tablearpt=true;
$("#timetitle").html('Publication Timeline')
// console.log(track.airport); 
if (track.airport.length > 0){
    $('#arpttitle').html(track.airport[0].icao + ' ' + track.airport[0].city_name + '/' + track.airport[0].arpt_name)
}else{
    tablearpt=false;
    $('#arpttitle').html(track.tablename + ' ' + track.fieldid)
}
Loadtimeline(idtime)
function Loadtimeline(cod){
    switch (cod) {
        case '1':
            $("#timetitle").html('Publication Timeline in PIA Wilayah');
            break;
        case '2':
            $("#timetitle").html('Publication Timeline in PIA Pusat');
            break;
        default:
            $("#timetitle").html('Publication Timeline');
            break;
    }
    $("#maintime").empty();
    tracking=[]; tracks={};
    adadata=true;
    idtime=cod;
        dtraw=DateFormat(new Date(track.create_date));
        trrem='Submission of data changes from the originator';
        rshort='Data changed by';
        // console.dir(track);
        if (track.users.length > 0){
            pic=track.users[0].first_name + ' ' + track.users[0].last_name + '(' + track.users[0].email+')';
        }
        trtime=new Date(track.create_date).toLocaleTimeString();
        trcolor=black;
        if (cod=='0'){
            tracks['adadata']=adadata;
            tracks['attach']=[];
            tracks['reffname']='Raw Data';
            tracks['reff_code']=reffcode;
            tracks['dtraw']=dtraw;
            tracks['rshort']=rshort;
            tracks['pic']=pic;
            tracks['trtime']=trtime;
            tracks['trcolor']=trcolor;
            tracks['trrem']=trrem;
            tracking.push(tracks);
        }
    if (track.detail.length == 0){
    // jika data masih di Originator(Raw Data)
        tbreff.forEach(a=>{
            var tampilref=true;
            if (tablearpt==false){
                if (a.reff_code=='20' || a.reff_code=='30'){
                    tampilref=false;
                }
            }
            if (tampilref==true){

                if (a.reff_code.substr(1,1)==cod && a.reff_code <= 100 ){
                    reffname=a.reff_name;
                    reffcode=a.reff_code;
                    if (a.reff_code !=="10"){
                
                        adadata=false;
                        dtraw = '---';
                        trrem='';
                        pic='';
                        trtime='';
                        rshort='';
                        trcolor=gray;
    
                        tracks={};
                        tracks['adadata']=adadata;
                        tracks['attach']=[];
                        tracks['reffname']=reffname;
                        tracks['reff_code']=reffcode;
                        tracks['dtraw']=dtraw;
                        tracks['rshort']=rshort;
                        tracks['pic']=pic;
                        tracks['trtime']=trtime;
                        tracks['trcolor']=trcolor;
                        tracks['trrem']=trrem;
                        tracking.push(tracks);
                    }
                }
            }
        })

    }else{
        var pjg=track.detail.length;
         // jika data sudah di proses untuk pengajuan publikasi
        for (let i=0;i<pjg;i++){
        // track.detail.forEach(a=>{
            var trackd=track.detail[i];
            var b=track.detail[i+1];
            // console.log('a',trackd.status_date,DateFormat(new Date(trackd.status_date)));
            // console.log('b',b);
            if (trackd.req_action.substr(1,1)==cod){
                // console.log(a);
                index = tbreff.findIndex( x => x.reff_code === trackd.reff_code);
                reffname=tbreff[index].reff_name;// + a.reff_code;
                reffcode=trackd.reff_code
                if (trackd.reff_code=='200'){
                    reffname='Raw Data ('+ tbreff[index].reff_name +')';//+a.reff_code;
                    reffcode='10';
                }
                // console.log(index,tbreff[index].reff_name)
                adadata=true;
                if ( i < pjg && cod==0){
                    if (trackd.reff_code=='200' || trackd.reff_code=='300' ){
                        adadata=false;
                    }
                }
                dtraw='';trtime='';
                // console.dir(trackd);
                let datee = trackd.status_date;
                dtraw =DateFormat(new Date(datee.substring(0, 19)));
                // dtraw =a.status_date;
                // console.log(trackd.status_date);
                trrem=trackd.status_remarks;
                rshort=trackd.reff_short;
                pic=trackd.users[0].first_name + ' ' + trackd.users[0].last_name + '(' + trackd.users[0].email+')';
                trtime=new Date(datee.substring(0, 19)).toLocaleTimeString();
                trcolor=black;
                tracks={};
                tracks['adadata']=adadata;
                if (track.attach.length==0){
                    tracks['attach']=[];
                }else{
                    tracks['attach']=track.attach;
                }
                tracks['reffname']=reffname;
                tracks['reff_code']=reffcode;
                tracks['dtraw']=dtraw;
                tracks['rshort']=rshort;
                tracks['pic']=pic;
                tracks['trtime']=trtime;
                tracks['trcolor']=trcolor;
                tracks['trrem']=trrem;
                tracking.push(tracks);
            }
        }
        var idx = tbreff.findIndex(x => x.reff_code===reffcode);
        // console.log(reffcode,idx);
        for (let i =idx+1;i < tbrefflength;i++){
            var tbref = tbreff[i];
            if (tbref.reff_code.substr(1,1)==cod && tbref.reff_code <= 100 ){
                reffname=tbref.reff_name;
                reffcode=tbref.reff_code;
                adadata=false;
                dtraw = '---';
                trrem='';
                pic='';
                trtime='';
                rshort='';
                trcolor=gray;

                
                tracks={};
                tracks['adadata']=adadata;
                tracks['attach']=[];
                tracks['reffname']=reffname;
                tracks['reff_code']=reffcode;
                tracks['dtraw']=dtraw;
                tracks['rshort']=rshort;
                tracks['pic']=pic;
                tracks['trtime']=trtime;
                tracks['trcolor']=trcolor;
                tracks['trrem']=trrem;
                tracking.push(tracks);
            }
        }
    }
    Showdetailtime(tracking);
    
        
}
function Showdetailtime(data){
    window.scrollTo(0,0);
    data.forEach(a=>{
        console.log(a);
            hasil='<li class="timeline-item">'+
                    '<div class="timeline-status bg-light is-outline"></div>'+
                    '<div style="color:'+ a.trcolor +'" class="timeline-date">'+ a.dtraw + '</div>'+
                    '<div class="timeline-data">';
                    if (a.adadata==true && (a.reff_code=="30" || a.reff_code=="60")){
                        hasil +=  '<a onclick="tabscreen('+a.reff_code+')">'+
                            '<h6 data-toggle="tooltip" data-placement="right" title="View Timeline Detail" style="cursor:pointer;"color:'+ a.trcolor +'" class="timeline-title"> '+ a.reffname+ '</h6>'+
                            '</a><br>';
                    }else{
                        hasil += '<h6 style="color:'+ a.trcolor +'" class="timeline-title">'+ a.reffname+ '</h6>';
                    }
                    if (a.attach.length > 0){
                        // a.attach.forEach(f=>{
                        //     // console.log(f);
                            // hasil += '<div class="attach-files">'+
                                // '<ul class="attach-list">'+
                                // '<li class="attach-item">'+
                                hasil +='<a class="download" style="cursor:pointer;color:#0F31EF;" onclick="showattachfile(this.id)"><em class="icon ni ni-clip"></em><span>Attachment</span></a><br>';
                                // '</li>'+
                                // '</ul>'+
                                // '</div>';

                        // })
                    }
                    if (track.notam.length > 0 && a.adadata==true){
                        // a.attach.forEach(f=>{
                        //     // console.log(f);
                            // hasil += '<div class="attach-files">'+
                                // '<ul class="attach-list">'+
                                // '<li class="attach-item">'+
                                hasil +='<a class="download" style="cursor:pointer;color:#0F31EF;" onclick="showatnotam()"><em class="icon ni ni-clip"></em><span>NOTAM</span></a><br>';
                                // '</li>'+
                                // '</ul>'+
                                // '</div>';

                        // })
                    }
                    hasil += '<div class="timeline-des mt-3">'+
                            '<p style="font-size:15px;color:red"><b>'+ a.trrem +'</b></p><br>'+
                            '<span style="color:'+ a.trcolor +'" class="time">'+a.rshort + ' ' + a.pic+'</span>'+
                            '<span style="color:'+ a.trcolor +'" class="time">'+ a.trtime+'</span>'+
                        '</div>'+
                    '</div>'+
                '</li>';
                $("#maintime").append(hasil);
        
    })
    
    
}
function backtolist(){
   var backid='0'; 
    // console.log(main,idtime);
    if (isAttach==true){
        main=false;
        backid =idtime;
        isAttach=false;
        aboutvol("maintime");
        aboutvol("attachlist");
    }
    if (main==true){
        history.back();
    }else{
       if (backid=="0"){ main=true};
        Loadtimeline(backid);
    }
}
function showattachfile(){
    isAttach=true;
    $("#Attach").empty();
    aboutvol("maintime");
    aboutvol("attachlist");
    var no=1;
    track.attach.forEach(f=>{
       if (f.file_att=='S'){
        hasil = '<tr class="nk-tb-item">'+
        '<td style="cursor:pointer" id='+f.path_file + f.filename+' onclick="showattach(this.id)">'+no+'</td>'+
        '<td><a class="download" id="'+f.path_file + f.filename+'" style="cursor:pointer;color:#0F31EF;" onclick="showattach(this.id)">'+no +') '+'<em class="icon ni ni-clip"></em><span>'+ f.cod_filename+'</span></a></td>'
        '</tr>';
        // $("#arptlist").append(hasil);
        //    hasil ='<a class="download" id="'+f.path_file + f.filename+'" style="cursor:pointer;color:#0F31EF;" onclick="showattach(this.id)">'+no +') '+'<em class="icon ni ni-clip"></em><span>'+ f.cod_filename+'</span></a><br><br>';
           no++;
           $("#Attach").append(hasil);
           
       }
    })
    no=1;
    track.attach.forEach(f=>{
        if (f.file_att=='P'){
            hasil = '<tr class="nk-tb-item">'+
            '<td style="cursor:pointer" id='+f.path_file + f.filename+' onclick="showattach(this.id)">'+no+'</td>'+
            '<td><a class="download" id="'+f.path_file + f.filename+'" style="cursor:pointer;color:#0F31EF;" onclick="showattach(this.id)">'+no +') '+'<em class="icon ni ni-clip"></em><span>'+ f.name+'</span></a></td>'
            // '<td style="cursor:pointer" id='+f.path_file + f.filename+' onclick="showattach(this.id)">'+f.name+'</td>'+
        '</tr>';
           no++;
           $("#Attach1").append(hasil);
           
       }
    })
    // $("#Attach").hide();
    // console.log(id);
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    // window.open("{{URL::to('/')}}/" + id, 'airportcontent', params)
}
function showatnotam(id){
    isAttach=true;
    $("#Attach").empty();
    aboutvol("maintime");
    aboutvol("attachlist");
    var no=1;
    track.notam.forEach(f=>{
        // console.log(f);
       hasil='<div class="panel-body mt-3">'+
                '<div class="row col-md-12">'+
                    '<div class="col-md-4">'+
                        '<strong>Notam Nr</strong>'+
                        '<br>'+
                        '<pre>'+f.notam_nr+'</pre>'+
                    '</div>'+
                    '<div class="col-md-12">'+
                        '<strong>Content</strong>'+
                        '<br>'+
                        '<pre>'+f.notam_content+'</pre>'+
                    '</div>'+
                '</div>'+
            '</div>';
        $("#Attach").append(hasil);
    })
    // $("#Attach").hide();
    // console.log(id);
    // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    // window.open("{{URL::to('/')}}/" + id, 'airportcontent', params)
}
function showattach(id){
    console.log(id);
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    window.open("{{URL::to('/')}}/" + id, 'airportcontent', params)
}
function tabscreen(reffcode){
    main=false;
// console.log(reffcode)
if (reffcode=="30"){
    Loadtimeline("1")
}else{
    Loadtimeline("2")
}
}


</script>
@endsection