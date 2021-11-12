@extends('layouts.app')

@section('template_title')
    Welcome  {{Auth::user()->name}}
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
                    <div class="nk-content-inner">
                        <div class="nk-content-body mt-3" id="freetext">
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

var gen =@json($gen);
var chart=@json($chart);
var codes =@json($cod);
var id =@json($id);
var allgen =@json($allgen);
var allenr =@json($allenr);
// console.log(allgen,allenr);
// console.log(id);
let idx = codes.findIndex(x => x.id===Number(id));
var cod=codes[idx];
let ix = codes.findIndex(x => x.id===Number(cod.parentid));
parent=codes[ix]
var ic = chart.findIndex(x => x.aip_sub_id===cod.sub_id);
console.log(cod.sub_id,ic)
if (ic !== -1){
    remove("iframepdf")
    var fl = chart[ic].path_file.replace('images/','');
        var pathdetail= pathpop()+ '/upload/publication/aip/' + fl;
    var div =document.getElementById("iframe-wrapper")
    // console.log(div)
    div.innerHTML = "<iframe id='iframepdf-gen' src='" + pathdetail + "' type='application/pdf' width='100%' height='650px'/>"
    
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
function backtolist(){
    window.location.href="{{url('/')}}/electronicaip";

}
this.isi='';
console.log(id)
switch (id) {
    case "16":
        this.isi='<h5 class="title" style="color:brown" align="center">PART 1 – GENERAL (GEN)</h5>';
        Genhtml(id);
        break;
    case "21":
        genenrcontent(allgen)
        break;
    case "37":
       
        Genhtml(id)
       
        break;
    case "44":
        genenrcontent(allenr)
        break;

    default:
        Genhtml(id);
        break;
}
// function grptblgen32(){
//     var chart=@json($codchart);
//     var abgrp=[];
//     console.log(chart);
//     chart.forEach(g=>{
//             abgrp.push(g.definition);
       
//     })
//     abgrp.push('ANC')
//     abgrp.push('ENRC')
//     abgrp.push('WAC')
//     var hsil='';
//     abgrp.forEach(g=>{
//         hsil +='<a id="'+ g +'"class="btn btn-sm" onclick="show(this.id)" style="color:blue" align="center"><u>'+ g + '</u></a>';
//     })
//     return hsil;
// }
function tblgen32(){
   
    var codchart=@json($codchart);
    // console.log(chart,codchart)
    var tablegen=''
    tablegen = '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'+
                '<colgroup>'+
                '<col span="1" style="width: 5%;">'+
                '<col span="1" style="width: 15%;">'+
                '<col span="1" style="width: 50%;">'+
                '<col span="1" style="width: 5%;">'+
                '<col span="1" style="width: 15%;">'+
                '<col span="1" style="width: 15%;">'+
                '</colgroup>'+
                    '<thead>'+
                    '<tr align="center" valign="middle">'+
                        '<td>Title<br>of<br>Series</td>'+
                        '<td>Scale</td>'+
                        '<td>Name and/or number</td>'+
                        '<td>Price<br>per<br>sheet</td>'+
                        '<td>Latest<br>Publication</td>'+
                        '<td>Date of<br>Latest<br>revision</td>'+
                    '</tr>'+
                    '<thead>'
    var chcode='';
    codchart.forEach(cod=>{
        chart.forEach(c=>{
            if (cod.id==c.id && c.arpt_pdf_type=='CHART'){
                var chc='';scl='NOT TO SCALE';
                if (c.definition !== chcode){
                    chc=c.definition;
                }
                if (c.scale !== null){
                    scl='1 : '+ numeral(c.scale).format('0,000,000') ;
                }
                tablegen +='<tr align="left" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                            '<td>'+ chc + '</td>'+
                            '<td>'+ scl +'</td>'+
                            '<td>'+ c.city_name +'/'+c.arpt_name+'<br>'+c.chart_name+'</td>'+
                            '<td>In AIP</td>'+
                            '<td>'+ c.source+'<br>'+c.nr_yr+'</td>'+
                            '<td>'+ c.eff_date+'</td>'+
                            '<tr>';
                chcode=c.definition;
                
            }
        })
        
    })
    chcode='';
    chart.forEach(c=>{
            if (c.aip_sub=='ENR' && c.arpt_pdf_type=='CHART'){
                var chc='';scl='NOT TO SCALE';
                if (c.aip_sub !== chcode){
                    chc=c.aip_sub;
                }
                if (c.scale !== null){
                    scl='1 : '+ numeral(c.scale).format('0,000,000') ;
                }
                tablegen +='<tr align="left" valign="middle" style="color:brown;background-color:#f0f0f0;border-color: #999999">'+
                            '<td>'+ chc + '</td>'+
                            '<td>'+ scl +'</td>'+
                            '<td>'+c.aip_sub_id+' '+ c.chart_name+'</td>'+
                            '<td>In AIP</td>'+
                            '<td>'+ c.source+'<br>'+c.nr_yr+'</td>'+
                            '<td>'+ c.eff_date+'</td>'+
                            '<tr>';
                chcode=c.aip_sub;
                
            }
        })

    
    tablegen +='</table>';
            return tablegen
        
}
function genenrcontent(data){
    this.isi+='<h5 class="title" style="color:brown" align="center">' + parent.sub_id + ' ' + parent.definition + '</h5>'
    this.isi +='<h6 class="title" style="color:brown" align="center">' + cod.sub_id + ' ' + cod.definition + '</h6>'
    this.isi += '<table class="table table-borderless">'
    this.isi +='<colgroup>'
    this.isi +='<col span="1" style="width: 10%;">'
    this.isi +='<col span="1" style="width: 15%;">'
    this.isi +='<col span="1" style="width: 75%;">'
    this.isi +='</colgroup>'

            data.forEach( ( isi ) =>
            {
                // console.log( ' data.subtitle.id', isi )
                        this.isi +='<tr>'
                        this.isi +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                        this.isi +='<td align="left" valign="top" colspan="1" rowspan="1">'+ isi.sub_id +'</td>'
                        this.isi +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                        this.isi +='<p>'+ isi.definition +'</p>'
                        this.isi +='</td>'
                        this.isi +='</tr>'
            
            } )
            this.isi += '</table>'
            $("#freetext").append(this.isi);
        
}
function showpdf(file){
    var pathdetail= 'upload/publication/aip/' + file;
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    window.open("{{URL::to('/')}}/" + pathdetail, 'airportcontent', params)
}
function Genhtml(id){

this.isi+='<h5 class="title" style="color:brown" align="center">' + parent.sub_id + ' ' + parent.definition + '</h5>'

if (cod.sub_id.substr(0,5)=='ENR 6'){
    var chart=@json($chart);
    this.isi +='<p></p>';
    var dt='';endp=false;
    chart.forEach(c=>{
        if (c.aip_sub_id.substr(0,5)=='ENR 6'){
            // console.log(chart)
            // $("#Attach").hide();
            // var pathdetail= pathpop()
                var fl = c.path_file.replace('images/','');
              
                // console.log(pathdetail)
               
 
                dt = '<span style="cursor:pointer;" id="'+ fl +'" onclick="showpdf(this.id)" target="">- ' +   c.aip_sub_id + ' '+  c.chart_name + '</span><br>'

                if (dt==''){
                    this.isi += '<p align="center"><i>Reserved</i></p><br>';
                }else{
                    this.isi += dt;
                }
            
            // this.isi += '<p></p>'
            // this.isi +='<div class="row">'+
            //             '<div class="col-md-2">'+
            //                 '<td>'+ '</td>'+
            //                 '</div>'+
            //                 '<div class="col-md-10">'+
            //                 '<td>'+ c.chart_name +'</td>'+
            //             '</div></div>';
               
                
            }
        })
}else{
    this.isi +='<h6 class="title" style="color:brown" align="center">' + cod.sub_id + ' ' + cod.definition + '</h6>'
    if (gen.length==0){
        this.isi += '<br>'
        this.isi += '<p align="center"><b><i>RESERVED</i></b></p><br>'
    }else{
        // console.dir(gen[0]['body']);
        if(gen.length == 1){
            // console.dir(gen[0]['body']);
              this.isi += gen['0']['body'];  
        }else{
            gen.forEach(a=>{
                if (a.content.includes("^INSERT_DATA")){
                    this.hsl = a.content.split('$')[3].replace('^','') //MyFunct.GetInfoInsertData(gen.content)
                }else{
                    this.hsl =a.content;
                }
                
                if (a.font=='B'){
                    this.isi += '<span style="color:brown"><b>' + this.hsl + '</b></span><br>'
                }else{
    
                    this.isi += '<span style="color:brown">' + this.hsl + '</span><br>'
                }
                
            }); 
        }
        if (id=='37'){
            this.isi += '<span style="color:brown">5.  List of aeronautical charts available</span><br>';
            // this.isi +=grptblgen32();
            this.isi +=  tblgen32();
            this.isi +='<span style="color:brown">6.  Topographical Chart</span><br>';
            this.isi +='<span style="color:brown"><i>Reserved</i></span><br>'
            this.isi +='<span style="color:brown">7.  Correction to chart not contained in the AIP</span><br>';
    
        }
            // console.log(gen)
        
    }
}

$("#freetext").append(this.isi);
}



</script>
@endsection