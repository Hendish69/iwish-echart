@extends('layouts.app')

@section('template_title')
    {{$aipcode}}
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <!-- <a class="btn btn-dim btn-secondary mt-2" onclick="history.back()"><i class="icon ni ni-reply-fill"></i> Back</a> -->
    
    <div class="nk-wrap">
        <div class="panel-body mt-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabItem1"><span>HTML</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  active" data-toggle="tab" href="#tabItem2"><span>PDF</span></a>
                </li>
                <li class="nav-item">
                    <a onclick="history.back()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                </li>
            </ul>
            <div class="tab-content tabairspace" id="tabasp">
                <div class="tab-pane" id="tabItem1">
                    <div id="sortid" class ="row" style="visibility: hidden">
                        <a class="btn" id="byid" onclick="sortdata(this.id)">BY IDENTIFICATION ( ID )</a>
                        <a class="btn" id="bystation" onclick="sortdata(this.id)">BY STATION</a>
                    </div>
                    <br>
                <div class="nk-content-inner">
                    <div class="nk-content-body mt-3" id="freetext">
                    </div>
                    <div class="nk-content-body mt-3" id="abbr" style="visibility: hidden">
                    </div>
                </div>
                </div>
                <div class="tab-pane  active" id="tabItem2">
                    <div id="iframe-wrapper">

                    </div>
                </div>

            </div>
        </div>
    </div>
   
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">

var gen =[];
gen = @json($nav);
var chart=@json($chart);
var cod = @json($cod);
var cod_id = @json($id);
console.log(cod,cod_id);
$("#sortid").hide();
$("#abbr").hide();
remove("iframepdf")
var fl = chart[0].path_file.replace('images/','');
    var pathdetail= pathpop()+ '/upload/publication/aip/' + fl;
var div =document.getElementById("iframe-wrapper")
// console.log(div)
div.innerHTML = "<iframe id='iframepdf-gen' src='" + pathdetail + "' type='application/pdf' width='100%' height='650px'/>"
switch (cod_id) {
    case '33':
        aboutvol("sortid");
        gen.sort((a,b) => (a.ident > b.ident) ? 1 : ((b.ident > a.ident) ? -1 : 0));
        sortdata('byid')
        break;
    case '66':
    case '68':
        enr4(cod_id);
        break;
    case '30':
        gen22();
        break;
    case '32':
        gen24();
        break;
    case '34':
        gen26();
        break;
    case '17':
    case '18':
        gen02(cod_id);
        break;
}


function  remove(iframe){
    this.iframeLoaded = false;
    var frame = document.getElementById(iframe);
    if (frame !== null){
        frame.src = ''; 
        // try{ 
        //     frame.contentWindow.document.write(''); 
        //     frame.contentWindow.document.clear(); 
        // }catch(e){
        //     console.log('err')
        // } 
        this.iframeLoaded = true;
        frame.parentNode.removeChild(frame);
    }
}
function gen24(){


this.isi='<h5 class="title" style="color:brown" align="center">GEN 2 TABLES AND CODES</h5>'
this.isi +='<h6 class="title" style="color:brown" align="center">' + cod[0].sub_id + ' ' + cod[0].definition + '</h6>'
        this.isi += tbl32();
        this.isi += '</table></div></div>'

$("#freetext").append(this.isi);
}
function gen26(){


this.isi='<h5 class="title" style="color:brown" align="center">GEN 2 TABLES AND CODES</h5>'
this.isi +='<h6 class="title" style="color:brown" align="center">' + cod[0].sub_id + ' ' + cod[0].definition + '</h6>'
        this.isi += tblgen26();
        this.isi += '</table></div></div>'

$("#freetext").append(this.isi);
}
function gen02(id){
    var cisi='AMDT';
if (id=='18'){
    cisi='SUPP';
}

this.isi ='<h5 class="title" style="color:brown" align="center">' + cod[0].sub_id + ' ' + cod[0].definition + '</h5>'
        this.isi += tblgen02(cisi);
        this.isi += '</table></div></div>'

$("#freetext").append(this.isi);
}
function tblgen26(){
    var jml=gen.length;
    var bagi
        if (jml%2==0){
            bagi=jml/2;
        }else{
            bagi=(jml-1)/2 +1;
        }
    // console.log(gen)
            var tablegen=''
            tablegen = '<div class="row">'+
                        '<div class="column col-md-3">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td colspan=2>NM to Km <br>1 NM = 1.852 Km </td>'+
                            '</tr>'+
                            '<tr align="center" valign="middle">'+
                                '<td>NM</td>'+
                                '<td>Km</td>'+
                            '</tr>'+
                            '<thead>';
                            var no=2;no1=2;fix=4
                            for (let i=1;i < 38;i++){
                                var c=i/10;
                                if (i >10  && i < 20){
                                    c=i-9;
                                    fix=3;
                                }
                                if (i >19 && i < 29){
                                    c=(no*10);
                                    no++;
                                    fix=2;
                                }
                                if (i >28){
                                    c=(no1*100);
                                    no1++;
                                    fix=1;
                                }

                                var cty=  (c * 1.852 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
            tablegen +='</table>'+
                        '</div>';
            tablegen += '<div class="column col-md-3">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td colspan=2>Km to NM<br>1 Km = 0.53996 NM</td>'+
                            '</tr>'+
                            '<tr align="center" valign="middle">'+
                                '<td>Km</td>'+
                                '<td>NM</td>'+
                            '</tr>'+
                            '<thead>';
                            var no=2;no1=2;fix=4
                            for (let i=1;i < 38;i++){
                                var c=i/10;
                                if (i >10  && i < 20){
                                    c=i-9;
                                    fix=3;
                                }
                                if (i >19 && i < 29){
                                    c=(no*10);
                                    no++;
                                    fix=2;
                                }
                                if (i >28){
                                    c=(no1*100);
                                    no1++;
                                    fix=1;
                                }

                                var cty=  (c * 0.53996 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td colspan=2>FT to M<br>1 FT = 0.3048 M</td>'+
                            '</tr>'+
                            '<tr align="center" valign="middle">'+
                                '<td>FT</td>'+
                                '<td>M</td>'+
                            '</tr>'+
                            '<thead>';
                            var no=2;no1=2;fix=4
                            for (let i=1;i < 38;i++){
                                var c=i;
                                if (i >10  && i < 20){
                                    c=(i-9)*10;
                                    fix=3;
                                }
                                if (i >19 && i < 29){
                                    c=(no*100);
                                    no++;
                                    fix=3;
                                }
                                if (i >28){
                                    c=(no1*1000);
                                    no1++;
                                    fix=3;
                                }

                                var cty=  (c * 0.3048 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td colspan=2>M to FT<br>1 M = 3.281 M</td>'+
                            '</tr>'+
                            '<tr align="center" valign="middle">'+
                                '<td>M</td>'+
                                '<td>FT</td>'+
                            '</tr>'+
                            '<thead>';
                            var no=2;no1=2;fix=4
                            for (let i=1;i < 38;i++){
                                var c=i/10;
                                if (i >10  && i < 20){
                                    c=(i-9)*10;
                                    fix=3;
                                }
                                if (i >19 && i < 29){
                                    c=(no*100);
                                    no++;
                                    fix=2;
                                }
                                if (i >28){
                                    c=(no1*1000);
                                    no1++;
                                    fix=1;
                                }

                                var cty=  (c * 3.281 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';

                        tablegen += '<div class="column col-md-12"><p>From decimal minutes of an arc to secondsof an arc<p></div><div class="column col-md-3">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td>MIN</td>'+
                                '<td>SEC</td>'+
                            '</tr>'+
                            '<thead>';
                            var fix=1
                            for (let i=1;i < 26;i++){
                                var c=i/100;
                                

                                var cty=  (c * 60 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3"">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td>MIN</td>'+
                                '<td>SEC</td>'+
                            '</tr>'+
                            '<thead>';
                            for (let i=26;i < 51;i++){
                                var c=i/100;
                                var cty=  (c * 60 ).toFixed(fix);
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3"">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td>MIN</td>'+
                                '<td>SEC</td>'+
                            '</tr>'+
                            '<thead>';
                            for (let i=51;i < 76;i++){
                                var c=i/100;
                                var cty=  (c * 60 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3"">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td>MIN</td>'+
                                '<td>SEC</td>'+
                            '</tr>'+
                            '<thead>';
                            for (let i=76;i < 101;i++){
                                var c=i/100;
                                var cty=  (c * 60 ).toFixed(fix);
                                if (i==100){
                                    c='-'; cty='-';
                                }
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-12"><p>From seconds of an arc to decimal minutes of an arc<p></div><div class="column col-md-3">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td>SEC</td>'+
                                '<td>MIN</td>'+
                            '</tr>'+
                            '<thead>';
                            var fix=2
                            for (let i=1;i < 16;i++){
                                var c=i;
                                

                                var cty=  (c / 60 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3"">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                            '<td>SEC</td>'+
                                '<td>MIN</td>'+
                            '</tr>'+
                            '<thead>';
                            for (let i=16;i < 31;i++){
                                var c=i;
                                var cty=  (c / 60 ).toFixed(fix);
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3"">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                            '<td>SEC</td>'+
                                '<td>MIN</td>'+
                            '</tr>'+
                            '<thead>';
                            for (let i=31;i < 46;i++){
                                var c=i;
                                var cty=  (c / 60 ).toFixed(fix);
                               
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>';
                        tablegen += '<div class="column col-md-3"">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                            '<td>SEC</td>'+
                                '<td>MIN</td>'+
                            '</tr>'+
                            '<thead>';
                            for (let i=46;i < 61;i++){
                                var c=i;
                                var cty=  (c / 60 ).toFixed(fix);
                                if (i==60){
                                    c='-'; cty='-';
                                }
                                tablegen +='<tr align="center" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+ c + '</td>'+
                                            '<td>'+ cty + '</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div>'+
                        '</div>';        
            return tablegen
        
}

function tbl32(){
    var jml=gen.length;
    var bagi
        if (jml%2==0){
            bagi=jml/2;
        }else{
            bagi=(jml-1)/2 +1;
        }
    // console.log(gen)
            var tablegen=''
            tablegen = '<div class="row">'+
                        '<div class="column col-md-6">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td>No</td>'+
                                '<td>location</td>'+
                                '<td>Indicator</td>'+
                            '</tr>'+
                            '<tr align="center" valign="middle">'+
                                '<td>1</td>'+
                                '<td>2</td>'+
                                '<td>3</td>'+
                            '</tr>'+
                            '<thead>'
                            for (let i=0;i < bagi;i++){
                                var c=gen[i];
                                var cty= c.city + '/'+ c.name;
                                if (c.city=='' || c.city==null || c.city=='NIL'){
                                    cty=  c.name;
                                }
                                if (c.name=='' || c.name==null || c.name=='NIL'){
                                    cty=  c.city;
                                }
                                tablegen +='<tr align="left" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+(Number(i) + 1) + '</td>'+
                                            '<td>'+ cty +'</td>'+
                                            '<td>'+ c.indicator+'</td>'+
                                            '<tr>';
                            }
            tablegen +='</table>'+
                        '</div>';
            tablegen += '<div class="column col-md-6"">'+
                            '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                            '<thead>'+
                            '<tr align="center" valign="middle">'+
                                '<td>No</td>'+
                                '<td>location</td>'+
                                '<td>Indicator</td>'+
                            '</tr>'+
                            '<tr align="center" valign="middle">'+
                                '<td>1</td>'+
                                '<td>2</td>'+
                                '<td>3</td>'+
                            '</tr>'+
                            '<thead>';
                            for (let i=bagi;i < jml;i++){
                                var c=gen[i];
                                var cty= c.city + '/'+ c.name;
                                if (c.city=='' || c.city==null || c.city=='NIL'){
                                    cty=  c.name;
                                }
                                if (c.name=='' || c.name==null || c.name=='NIL'){
                                    cty=  c.city;
                                }
                                tablegen +='<tr align="left" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                                            '<td>'+(Number(i) + 1) + '</td>'+
                                            '<td>'+ cty +'</td>'+
                                            '<td>'+ c.indicator+'</td>'+
                                            '<tr>';
                            }
                            tablegen +='</table>'+
                        '</div></div>';        
            return tablegen
        
}
function gen22(id){
    aboutvol("abbr")
    var abgrp=[];
    var hrf='';
    gen.forEach(g=>{
        if (g.pref!==hrf){
            abgrp.push(g.pref);
        }
        hrf=g.pref;
    })
    this.isi ='<h5 class="title" style="color:brown" align="center">GEN 2 TABLES AND CODES</h5>'
    this.isi +='<h6 class="title" style="color:brown" align="center">' + cod[0].sub_id + ' ' + cod[0].definition + '</h6>'
    this.isi +='<span align="center" style="color:brown">* Abbreviations which are different from or not contained in the ICAO PANS-ABC (Doc.8400)</span><br>'
    abgrp.forEach(g=>{
        this.isi +='<a id="'+ g +'"class="btn btn-sm" onclick="show(this.id)" style="color:blue" align="center"><u>'+ g + '</u></a>';
    })
    show('A')
    $("#freetext").append(this.isi);
}
function show(id){
    var coll=[];
    gen.forEach(g=>{
        if (g.pref ==id){
            coll.push(g);
        }
    })
    var ix=coll.length;
    var tablegen='';
    $("#abbr").empty();
    var jml
        if (ix%2==0){
            jml=ix/2;
        }else{
            jml=(ix-1)/2 +1;
        }
        
    // console.log(ix,jml,coll)

        tablegen += '<div class="row">'+
                '<div class="column col-md-6">';
                // console.log(nav)
                for (let i=0;i < jml;i++){
                    var c=coll[i];
                    // console.log(c.ident)
                    tablegen +='<div class="row"><div class="column col-md-2">'+
                                '<p>'+ c.ident + '</p>'+
                                '</div>'+
                                '<div class="column col-md-10">'+
                                '<p>'+ c.definition +'</p>'+
                                '</div></div>';

                    
                   
                }
            tablegen +='</div>'+
                        '<div class="column col-md-6">';
                for (let i=jml;i < ix;i++){
                    var c=coll[i];
                        tablegen +='<div class="row"><div class="column col-md-2">'+
                                    '<p>'+c.ident + '</p>'+
                                    '</div>'+
                                    '<div class="column col-md-10">'+
                                    '<p>'+ c.definition +'</p>'+
                                    '</div></div>';

                    
                }
            tablegen +='</div></div>';
            // console.log(tablegen)
            tablegen +='<br>* The National Abbreviation<br>'+
        '+  When the Radiotelephony is used, abbreviation and terms are transmitted as spoken words.<br>'+
        '++  When the Radiotelephony is used, abbreviation and terms are transmitted using the individual letters in non-phonetic form.'
            $("#abbr").append(tablegen);

    // }
}
function enr4(id){
    this.isi ='<h5 class="title" style="color:brown" align="center">ENR 4. RADIO NAVIGATION AIDS/SYSTEMS</h5>'
    this.isi +='<h6 class="title" style="color:brown" align="center">' + cod[0].sub_id + ' ' + cod[0].definition + '</h6>'
    this.isi +='<br>'
    if (id=='68'){
        this.isi += tbl43();
        // console.log(gen)
    }else {
        gen.sort((a,b) => (a.station > b.station) ? 1 : ((b.station > a.station) ? -1 : 0));
        this.isi += tbl41();
        // console.log(gen)
    }
    this.isi += '</table></div></div>'
    $("#freetext").append(this.isi);
}
function tbl43(){
    var jml =gen.length /2 ;
    var tablegen=''
    tablegen = '<div class="row">'+
                '<div class="column col-md-6">'+
                '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                '<thead>'+
                '<tr align="center" valign="middle">'+
                        '<td>No</td>'+
                        '<td>Name-Code Designator</td>'+
                        '<td>Coordinates</td>'+
                        '<td>ATS route or<br>other route</td>'+
                '</tr><thead>'+
                '<tr align="center" valign="middle">'+
                                '<td>1</td>'+
                                '<td>2</td>'+
                                '<td>3</td>'+
                                '<td>4</td>'+
                '</tr>';
                // console.log(nav)
                for (let i=0;i < jml;i++){
                    var c=gen[i];
                   
                    tablegen +='<tr style="color:brown ;background-color:#f0f0f0" align="center" valign="middle">'+
                                '<td>'+(Number(i) + 1) + '</td>'+
                                '<td>'+ c.ident +'</td>'+
                                '<td>'+ c.lat + ' ' + c.lon +'</td>'+
                                '<td>'+ c.ats +'</td>'+
                                '<tr>';
                }
            tablegen +='</table></div>'+
                        '<div class="column col-md-6">'+
                '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                '<thead>'+
                '<tr align="center" valign="middle">'+
                        '<td>No</td>'+
                        '<td>Name-Code Designator</td>'+
                        '<td>Coordinates</td>'+
                        '<td>ATS route or<br>other route</td>'+
                '</tr><thead>'+
                '<tr align="center" valign="middle">'+
                                '<td>1</td>'+
                                '<td>2</td>'+
                                '<td>3</td>'+
                                '<td>4</td>'+
                '</tr>';
                for (let i=jml;i < gen.length;i++){
                    var c=gen[i];
                   
                    tablegen +='<tr style="color:brown ;background-color:#f0f0f0" align="center" valign="middle">'+
                                '<td>'+(Number(i) + 1) + '</td>'+
                                '<td>'+ c.ident +'</td>'+
                                '<td>'+ c.lat + ' ' + c.lon +'</td>'+
                                '<td>'+ c.ats +'</td>'+
                                '<tr>';
                }
            tablegen +='</table></div></div>';
            
            return tablegen
        
}

function tbl41(){
    var tablegen=''
    tablegen = 
                '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                '<thead>'+
                '<tr align="center" valign="middle">'+
                        '<td>No</td>'+
                        '<td>STATION</td>'+
                        '<td>ID</td>'+
                        '<td>FREQ/CH</td>'+
                        '<td>HOUR<br>OF SVC</td>'+
                        '<td>COORD</td>'+
                        '<td>ELEV</td>'+
                        '<td>REMARKS</td>'+
                '</tr><thead>'+
                '<tr align="center" valign="middle">'+
                                '<td>1</td>'+
                                '<td>2</td>'+
                                '<td>3</td>'+
                                '<td>4</td>'+
                                '<td>5</td>'+
                                '<td>6</td>'+
                                '<td>7</td>'+
                                '<td>8</td>'+
                '</tr>';



                // console.log(nav)
                var sttn='';stn='';
                for (let i=0;i < gen.length;i++){
                    var c=gen[i];
                    if (sttn==c.station){
                        stn=c.type;
                    }else{
                        stn=c.station +'<br>'+c.type;
                    }
                    
                    tablegen +='<tr style="color:brown ;background-color:#f0f0f0" valign="top">'+
                                '<td>'+(Number(i) + 1) + '</td>'+
                                '<td>'+ stn+'</td>'+
                                '<td>'+ c.ident +'</td>'+
                                '<td>'+ c.freq +'</td>'+
                                '<td>'+ c.hrs +'</td>'+
                                '<td>'+ c.lat + ' ' + c.lon +'</td>'+
                                '<td>'+ c.elev +'</td>'+
                                '<td>'+ c.remarks +'</td>'+
                                '<tr>';
                    sttn=c.station;
                }
            tablegen +='</table>';
            
            return tablegen
        
}

function tblgen02(type){
    var tablegen=''
    tablegen = 
                '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                '<thead>'+
                '<tr align="center" valign="middle">'+
                        '<td>AMENDMENT NUMBER</td>'+
                        '<td>PUBLIATION DATE</td>'+
                        '<td>EFFECTIVE DATE</td>'+
                        '<td>DATE INSERTED</td>'+
                        '<td>INSERTED BY</td>'+
                '</tr><thead>';
                // console.log(nav)
                var sttn='';stn='';
                for (let i=0;i < gen.length;i++){
                    var c=gen[i];
                    // console.log(c)
                    var  pd=DateFormat(new Date(c.pub_date),false,true);
                    var  ed=DateFormat(new Date(c.eff_date),false,true);
                    if (c.publish=='Y' && c.src_type.includes(type)){
                        stn=c.src_id.split('/');
                        sttn=c.src_type +' '+ stn[0];
                        
                        
                        tablegen +='<tr style="color:brown ;background-color:#f0f0f0" valign="top">'+
                                    '<td>'+ sttn+'</td>'+
                                    '<td>'+ pd +'</td>'+
                                    '<td>'+ ed +'</td>'+
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<tr>';
                        sttn=c.station;
                        
                    }
                }
            tablegen +='</table>';
            
            return tablegen
        
}

function sortdata(id){

   

    console.log(id)
    $("#freetext").empty()
    this.isi ='<h5 class="title" style="color:brown" align="center">GEN 2 TABLES AND CODES</h5>'
    this.isi +='<h6 class="title" style="color:brown" align="center">' + cod[0].sub_id + ' ' + cod[0].definition + '</h6>'

    if (id=='byid'){
        this.isi +='<h6 id="sortby" class="title" style="color:brown" align="center">BY IDENTIFICATION ( ID )</h6>'
        gen.sort((a,b) => (a.ident > b.ident) ? 1 : ((b.ident > a.ident) ? -1 : 0));
    }else{
        this.isi +='<h6 id="sortby" class="title" style="color:brown" align="center">BY STATION</h6>'
        gen.sort((a,b) => (a.station > b.station) ? 1 : ((b.station > a.station) ? -1 : 0));
    }

    this.isi +='<br>'
    this.isi += tbl33(id);
    this.isi += '</table></div></div>'
    $("#freetext").append(this.isi);
}

function tbl33(id){
    var tablegen=''
    tablegen = 
                '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                '<thead>';
                if (id=='byid'){
                    tablegen +='<tr align="center" valign="middle">'+
                        '<td>No</td>'+
                        '<td>ID</td>'+
                        '<td>STATION NAME</td>'+
                        '<td>AID</td>'+
                        '<td>PURPOSE</td>';
                    '</tr>';

                }else{
                    tablegen +='<tr align="center" valign="middle">'+
                        '<td>No</td>'+
                        '<td>STATION NAME</td>'+
                        '<td>ID</td>'+
                        '<td>AID</td>'+
                        '<td>PURPOSE</td>';
                    '</tr>';
                }
                
                tablegen += '<thead>'
                // console.log(nav)
                for (let i=0;i < gen.length;i++){
                    var c=gen[i];
                   
                    tablegen +='<tr style="color:brown ;background-color:#f0f0f0" align="center" valign="middle">'+
                                '<td>'+(Number(i) + 1) + '</td>';
                                if (id=='byid'){
                                tablegen +='<td>'+ c.ident +'</td>'+
                                    '<td>'+ c.station+'</td>';
                                }else{
                                tablegen +=   '<td>'+ c.station +'</td>'+
                                    '<td>'+ c.ident+'</td>';

                                }
                                tablegen +=  '<td>'+ c.facility+'</td>'+
                                '<td>'+ c.purpose+'</td>'+
                                '<tr>';
                }
            tablegen +='</table>';
            
            return tablegen
        
}

</script>
@endsection