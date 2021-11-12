@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
<link href="{{ asset('template/assets/css/v-modal.css') }}" rel="stylesheet" >
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-wrap">
        <div class="panel-body mt-3">
            <ul class="nav nav-tabs" id="tabMenu">
                <li class="nav-item tab-pane{{old('tab') == 'tabItem1' ? ' active' : null}}">
                    <a class="nav-link" data-toggle="tab" href="#tabItem1"><span>HTML</span></a>
                </li>
                <li class="nav-item tab-pane{{old('tab') == 'tabItem2' ? ' active' : null}}">
                    <a class="nav-link active" id="tab_pdf" data-toggle="tab" href="#tabItem2"><span>PDF</span></a>
                </li>
                <li  class="nav-item tab-pane{{old('tab') == 'tabItem3' ? ' active' : null}}">
                    <a class="nav-link"  data-toggle="tab" href="#tabItem3"><span>Charts</span></a>
                </li>
                <li class="nav-item">
                    <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                </li>
            </ul>
            
            <div class="tab-content tabairspace" id="tabasp">
                <div class="tab-pane" id="tabItem1">
                    <div class="nk-content-body" id="arptinfo"></div>
                </div>
                <div class="tab-pane  active" id="tabItem2">
                    <div id="iframe-wrapper">

                    </div>
                </div>
                <div class="tab-pane" id="tabItem3">
                    <div class="row" >
                        <div class="col-md-12" id="listofcharts">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')
<script src="{{ asset('template/assets/js/v-modal.js') }}"></script>

<script type="text/javascript">
$(document).ready(function () {
    $.wmBox();
});
// $('#hideinfo').hide();
var ar=@json($arpt);
var codaip=@json($codaip);
var airportcontent =@json($airportcontent);
var eaiplist =@json($eaiplist);
var apronlist =@json($apronlist);
var twylist =@json($twylist);
var chart =@json($chart);
var obstacle =@json($obstacle);
var rwylist =@json($rwylist);
var rwylighting =@json($rwylighting);
var freetext =@json($freetext);
var freqlist =@json($freq);
var navarptlist=@json($navaid);
var ch =@json($channel);

var arpt=ar[0];nil='NIL';obs3=[];obs2=[];
var content;icao=arpt.icao;

var akh='';no=0;

var isimetar=[];isispeci=[];isitaf=[];mm={};
// console.log(getarah('35007KT'),getarah('VRB02KT'),getarah('24006KT'),getarah('08002KT'),getarah('27006KT'),getarah('15004KT'),getarah('04003KT'))
// console.log(chart)
// showpdftext();
no=0;
// location.reload();
var fl ='';
if (chart.length > 0){
    chart.forEach(a=>{
    if (a.arpt_pdf_type=='TEXT'){
        // console.log(a)
        fl = a.path_file.replace('images/','');
        var pathdetail= pathpop()+ '/upload/publication/aip/' + fl;
        // console.log(pathdetail,'pathdetail')
        var div =document.getElementById("iframe-wrapper")
        // console.log(div)
        div.innerHTML = "<iframe id='iframepdf-gen' src='" + pathdetail + "' type='application/pdf' width='100%' height='650px'/>"
    }
    })
}
$('#tab_pdf').click(function(){
    let exists = fl;
    if(exists != ''){
        var pathdetail= pathpop()+ '/upload/publication/aip/' + fl;
        var div =document.getElementById("iframe-wrapper");
        div.querySelectorAll('*').forEach(n => n.remove());
        div.innerHTML = "<iframe id='iframepdf-gen' src='" + pathdetail + "' type='application/pdf' width='100%' height='650px'/>"   
    };
})

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
var content='';no=1;
chart.forEach(a=>{
    if (a.arpt_pdf_type=='CHART'){
        // console.log(a.path_file)
        if (a.path_file !== null){

            var fl = a.path_file.replace('images/','');  //upload/publication/aip/
            var filepath = '/upload/publication/aip/'+fl;
            // dt = '<span style="cursor:pointer;"  collapse="0" id="'+ fl +'" onclick="showpdf(this.id)" target="">'+no+ ' . ' + a.chart_name + '</span><br>';
            dt = '<span style="cursor:pointer;" id="'+ filepath +'" onclick="vModal(this.id)">'+no+ ' . ' + a.chart_name + '</span><br>'
            if (dt==''){
                content += '<p align="center"><i>Reserved</i></p><br>';
            }else{
                content += dt;
            }
            content += '<p></p>'
            no++;
        }
        // console.log(content);
    }
    
})
$("#listofcharts").append(content)

// function showpdf(file){
//     var collapse = document.getElementById(file).getAttribute('collapse');
//     var div =document.getElementById(file)
//     remove("trpdf")
//     remove("tdpdf")
//     var pathdetail= pathpop()+ '/upload/publication/aip/' + file;
// // console.log(collapse,div,'jkjasdlajsdl')
//     if(collapse =='0'){
//         var newElement = document.createElement('tr');
//         newElement.setAttribute('id', 'trpdf');
//         // newElement.setAttribute('collapse', '1');
//         // newElement.insertCell(0).innerHTML = '<td colSpan="6"><iframe style="border: 0; padding: 0; width: 100%; height: 650px" src="'+ this.pdfLink +'"></iframe></td>'
//         div.parentNode.insertBefore(newElement, div.nextSibling );
//         var newtd = document.createElement('td');
//         newtd.setAttribute('colSpan', '1');
//         newtd.setAttribute('id', 'tdpdf');
//         newtd.innerHTML = '<iframe frameborder="0" allowfullscreen style="top:0;left:0;width:100%;height:100%;" src="'+ pathdetail +'"></iframe>'
//         div.parentNode.insertBefore(newtd, newElement.nextSibling );
//         div.setAttribute('collapse', '1');
//         // document.getElementsByName("opentr").setAttribute('collapse', '1');
//         // // document.getElementsByClassName("opentr").setAttribute('collapse', '1');
//         // document.getElementById('td' + menu).colSpan="6"
//         //    var row = document.createElement('<tr class="opentr"><td colspan="5"><iframe style="border: 0; padding: 0; width: 100%; height: 800px" src="'+ this.pdfLink +'"></iframe></td></tr>').insertRow(1);
//         //    console.log(row)
//             // newElement(menu).setAttribute('collapse', '1');
//             // div.appendChild(row)
            
//     }else{
//         remove("trpdf")
//         remove("tdpdf")
//         div.setAttribute('collapse', '0');
//             // div('.opentr').remove();
//             // div(menu).setAttribute('collapse', '0');
//     }
//     // var pathdetail= 'upload/publication/aip/' + file;
//     // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
//     // window.open("{{URL::to('/')}}/" + pathdetail, 'airportcontent', params)
// }

function showdetail(dt,type){
    $('#hideinfo').empty();
    var data=[];
    switch(type){
        case "METAR":
            data=isimetar;
            break;
        case "SPECI":
            data=isispeci;
            break;
        case "TAF":
            data=isitaf;
            break;
    }
    // console.dir(isimetar)
    // hPa to incHg = 33.863886666667
//knot to m/s = 0.5;SKY SKY CLEAR few (1-2 oktas) scattered SCT(3-4 oktas) broken BKN(5-7 oktas) overcast (8 oktas)
    console.log(dt);
    let ix = data.findIndex(x => x.mdate ===dt);
    let sp = data[ix];
    var tt=dt
    var dd = tt.substr(0,2);
    var mm = getMonth(tt.substr(3,2));
    var yy= tt.substr(6,4) //DateFormat(new Date(sp[0]),true);
    var tgl = dd + ' ' + mm + ' ' + yy + ' ' + tt.substr(11,5) + ' UTC';
    // console.log(datestd.toLocaleString('default', { month: 'long' }));
    var hdr = sp.hdr;bulnmb=hdr.substr(5,2);
    var isi = sp.misi.split(' ');obtype='Manual Observation';wind='';visib='';presure='';temp='';dew='';
    var ttl= type + ' OF ' + arpt.arpt_name + ' AIRPORT'
    if (isi[3]=='AUTO'){
        obtype='AUTO (Automatic Observation)';
    }
    hasil = SetHeader(ttl,tgl)+
        '<div class="row">'+
            // '<div class="col-2"><b>'+ sp.id +') Condition at</b></div>'+
            '<div class="col-2"><b>Condition at</b></div>'+
            '<div class="col-10"><b>: ' + arpt.arpt_name + ' - ' + arpt.city_name + ' (' + icao + ')' +'</b></div>'+
        '</div>'+
        '<hr size="1px">'+
        '<div class="row">'+
            '<div class="col-2"><b>Header</b></div>'+
            '<div class="col-10">: ' + hdr + '</div>'+
        '</div>'+
        // '<hr size="1px">'+
        '<div class="row">'+
            '<div class="col-2">Report type</div>'+
            '<div class="col-10">: Standard Meteorological Report</div>'+
        '</div>'+
        '<div class="row">'+
            '<div class="col-2">Data type</div>'+
            '<div class="col-10">: Aerodrome Routine Meteorological Report</div>'+
        '</div>'+
        '<div class="row">'+
            '<div class="col-2">Region of report</div>'+
            '<div class="col-10">: '+ arpt.country[0].country +'</div>'+
        '</div>'+
        '<div class="row">'+
            '<div class="col-2">Bulletin number</div>'+
            '<div class="col-10">: '+bulnmb+'</div>'+
        '</div>'+
        '<div class="row">'+
            '<div class="col-2">Centre of report</div>'+
            '<div class="col-10">: ' + arpt.arpt_name + ' - ' + arpt.city_name +'</div>'+
        '</div>'+
        '<div class="row">'+
            '<div class="col-2">Filling time</div>'+
            '<div class="col-10">: '+ tgl +'</div>'+
        '</div>'+
        '<hr size="1px">'+
        '<div class="row">'+
            '<div class="col-2"><b>Raw data</b></div>'+
            '<div class="col-10">: ' + sp.misi + '</div>'+
        '</div>'+
        '<div class="row">'+
            '<div class="col-2">Observation type</div>'+
            '<div class="col-10">: '+ obtype +'</div>'+
        '</div>'+
        '<div class="row">'+
            '<div class="col-2">Observed time</div>'+
            '<div class="col-10">: '+ tgl +'</div>'+
        '</div>';
var adarvr=false;adaclds=false;
    for (let i=2;i <isi.length;i++){
        
        var n = isi[i].indexOf("KT");
        if (n !== -1){
            var nn = isi[i+1].indexOf("V");ww='';vv='';
            if (nn !== -1){
                ww=isi[i+1];
                vv=isi[i+2];
            }else{
                vv=isi[i+1];
            }
            if (vv=="9999"){
                    visib = '≥10 km';
            }else{
                var vsb=Number(vv/1000);
                visib = vsb.toFixed(1) +' km';
                // if (vsb < 1){
                //     visib = vsb * 1000 + ' m';
                // }
                // console.log(vsb);
            }
            wind=getWind(isi[i],ww);
            hasil += IsiRowData('Winds',wind);
            hasil += IsiRowData('Visibility',visib);

           
            
        }

        n = isi[ i ].indexOf( "/" );
        if ( isi[ i ].substr( 0, 1 ) == 'R' && n !== -1 && adarvr==false) {
            var hhs=getRVR(isi);
            hasil += IsiRowData('RVR',hhs);
            // console.log('GET RVR', hhs)
            adarvr=true;
        }
        var clsub =  isi[ i ].substr( 0, 3 );
        if ( clsub == 'SCT' || clsub == 'FEW' || clsub == 'BKN' || clsub == 'OVC' ) {
            var clds = getClouds(isi);
            if (clds !== '' && adaclds==false){
                adaclds=true;
                hasil += IsiRowData('Clouds',clds);
            }
        }

        // console.log(n,isi[i])
        n = isi[i].indexOf("Q");
        if (n !== -1){
            presure=getPressure(isi[i]);
            hasil += IsiRowData('Pressure',presure);
        }
        n = isi[i].indexOf("/");
        if (n !== -1 && isi[i].substr(0,1) !== 'R'){

            // T(°F) = T(°C) × 9/5 + 32 or T(°F) = T(°C) × 1.8 + 32
        // Example
        // Convert 20 degrees Celsius to degrees Fahrenheit:

        // T(°F) = 20°C × 9/5 + 32 = 68 °F
        
            var tspl=isi[i].split('/');
            var ftemp=(tspl[0] * 9/5) + 32;
            var fdew=(tspl[1] * 9/5) + 32;
            if (tspl[0].substr(0,1)=='M'){
                tspl[0] = tspl[0].replace('M','-');
            }
            if (tspl[1].substr(0,1)=='M'){
                tspl[1] = tspl[1].replace('M','-');
            }
            temp=tspl[0] + ' °C (' + ftemp + ' °F)';
            dew=tspl[1] + ' °C (' + fdew + ' °F)';
            hasil += IsiRowData('Temperature',temp);
            hasil += IsiRowData('Dew point',dew);
            // console.log('presure',presure)
        }
        n = isi[i].indexOf("NOSIG");
        if (n !== -1 && isi[i].substr(0,1) !== 'R')
        {
            hasil +='<hr size="1px">'+
                '<div class="row">'+
                    '<div class="col-2"><b>Raw data</b></div>'+
                    '<div class="col-10">: NOSIG</div>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col-2">Trend type</div>'+
                    '<div class="col-10">: NOSIG (no significant change)</div>'+
                '</div>';
        }
        WeatherCode(isi[i])
// console.log(isi[i],i);
        

    }


    hasil += SetFooter(arpt.arpt_name,arpt.city_name);

    // console.log(isi);
    aboutvol('showinfo');
    aboutvol('hideinfo');
    $("#hideinfo").append(hasil);

    window.scrollTo(0,0);
}
    

obstacle.forEach(o=>{
    var cord=SetCoordinatebyGeom(o.geom)
    var obs = o
    obs['latwgs'] = cord.NonFIR[1];
    obs['lonwgs'] = cord.NonFIR[0];
    obs['lat'] =cord.Database[1];
    obs['lon'] = cord.Database[0];
    if (o.position == 'In Area 3'){
        obs3.push( obs)
    }else{
        obs2.push(obs)
    }
})
function backtolist(){
    history.back();
}



codaip.forEach(cod=>{
    var gg = cod.id.split( '#' );
                var idSub = gg[ 0 ];
                // console.log(cod)

                switch ( idSub ) {
                    case 'AD 2.1':
                        // content = '<a class="btn btn-dim btn-secondary" onclick="backtolist()"><i class="icon ni ni-reply-fill"></i> Back</a>'
                        var cate='AD 2.1 AERODROME LOCATION INDICATOR AND NAME'
                        content ='<h5 class="title mt-5" style="color:brown" align="center">AD 2 AERODROMES</h5>'
                        content +='<div class=mt-3><h6 class="Title" style="color:brown" align="center"><span>' + icao + ' ' + cate + '</span></h6></div><br>'
                        content +='<h5  align="center"><span>'+ icao + ' - ' +  arpt.city_name + ' / ' + propercase(arpt.arpt_name) +'</span></h5><br>'
                        break;
                    case 'AD 2.2':
                        var g = cod.id.replace( '#', ' ' );
                        var ttl = icao + ' ' + g;
                        content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                        ad22( content, cod.id );
                        // console.log(hsl);
                        break;
                    case 'AD 2.3':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.4':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );

                        break;
                    case 'AD 2.5':
                    
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>' + ttl + '</span></h6></div>'
                            ad23( content, cod.id );

                        break;
                    case 'AD 2.6':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.7':
                        
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.8':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad28( content, cod.id );
                        break;
                    case 'AD 2.9':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.10':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad210( content, cod.id );
                        break;
                    case 'AD 2.11':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.12':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad212( content, cod.id );
                        break;
                    case 'AD 2.13':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>' + ttl + '</span></h6></div>'
                            ad213( content, cod.id );
                        break;
                    case 'AD 2.14':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad214( content, cod.id );
                        break;
                
                    case 'AD 2.15':
                        
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.16':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.17':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad23( content, cod.id );
                        break;
                    case 'AD 2.18':
                        
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad218( content, cod.id );
                        break;
                    case 'AD 2.19':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad219( content, cod.id );
                        break;
                    case 'AD 2.20':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad220(arpt.arpt_ident,99 );
                        break;
                    case 'AD 2.21':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad220(arpt.arpt_ident,108 );
                        break;
                    case 'AD 2.22':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad220(arpt.arpt_ident,109 );
                        break;
                    case 'AD 2.23':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad220(arpt.arpt_ident, 110 );
                        break;
                    case 'AD 2.24':
                            var g = cod.id.replace( '#', ' ' );
                            var ttl = icao + ' ' + g;
                            content +='<div><h6 class="Title" style="color:brown" align="center"><span>'  + ttl + '</span></h6></div>'
                            ad224();
                        break;
                    default:
                }

               
                $('#arptinfo').html(content);

})
function ad22()
        {
          
            content += '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
            content +='<colgroup>'
            content +='<col span="1" style="width: 5%;">'
            content +='<col span="1" style="width: 35%;">'
            content +='<col span="1" style="width: 15%;">'
            content +='<col span="1" style="width: 45%;">'
            content +='</colgroup>'
            var isi;
            var no=1
            eaiplist.forEach( ( cod ) =>
            {
            // console.log( cod )
                if ( cod.subid == 'AD 2.2' ) {

                    if ( cod.form_type == 'A' || cod.form_type == 'D' ) {
                        var nn = no++
                        if (cod.form_type == 'D'){
                            nn=''
                            no--
                        }
                        if ( typeof chk == 'undefined' ) {
                            isi = nil;
                        } else {
                            isi = airportcontent.find( x => x.category_id === 6 ).content;
                        }
            
                        if ( isi == '' || isi == null ) {
                            isi = nil;
                        }

                        if ( cod.id == 235 ) {
                            var chk = airportcontent.find( x => x.category_id === 228 );
                            if ( typeof chk == 'undefined' ) {
                                isi = nil;
                            } else {
                                isi = airportcontent.find( x => x.category_id === 228 ).content;
                            }
                
                            if ( isi == '' || isi == null ) {
                                isi = nil;
                            }
                        }else if ( cod.id == 2 ) {
                            var chk = airportcontent.find( x => x.category_id === 2 );
                            if ( typeof chk == 'undefined' ) {
                                isi = nil;
                            } else {
                                var ccrd=airportcontent.find( x => x.category_id === 2 ).content
                                var crd=ccrd.split(' ');
                                var cord=SetCoordinate(crd[0],crd[1]);
                                //   console.log(cord)
                                isi = cord.ADText[1]+ ' ' + cord.ADText[0];
                            }
                
                            if ( isi == '' || isi == null ) {
                                isi = nil;
                            }

                        } else {
                            chk = airportcontent.find( x => x.category_id === cod.id );
                            if ( typeof chk == 'undefined' ) {
                                isi = nil;
                            } else {
                                isi = airportcontent.find( x => x.category_id === cod.id ).content;
                            }
                
                            if ( isi == '' || isi == null ) {
                                isi = nil;
                            }
                                
                        }

                        // console.log('isi data',content,cod.id)
                        switch ( cod.id ) {
                            case 2:
                                content +='<tr>'
                                content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ nn +'</td>'
                                content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                                content +='<p>'+ cod.item +'</p>'
                                content +='</td>'
                                content +='<td colspan="3" align="left" valign="top" rowspan="1">'
                                content +='<span>'+ isi +'</span>'
                                content +='</td>'
                                content +='</tr>'
                                break;
                            case 3:
                            case 212:
                            case 5:
                            case 13:
                            case 14:
                                content +='<tr>'
                                content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ nn +'</td>'
                                content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                                content +='<p>'+ cod.item +'</p>'
                                content +='</td>'
                                content +='<td colspan="3" align="left" valign="top" rowspan="1">'
                                content +='<span>'+ isi +'</span>'
                                content +='</td>'
                                content +='</tr>'
                                break;
                            case 235:
                                chk = airportcontent.find( x => x.category_id === 4 );
                                // console.log( 'content.find( x => x.category_id === cod.id )', content.find( x => x.category_id === cod.id ),chk )
                                var isi1;
                                if ( typeof chk == 'undefined' ) {
                                    isi1 = nil;
                                } else {
                                    isi1 = airportcontent.find( x => x.category_id === 4 ).content + '°C';
                                }
                    
                                if ( isi1 == '' || isi1 == null ) {
                                    isi1 = nil;
                                }
                                isi += 'ft / ' + isi1;

                                content +='<tr>'
                                content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ nn +'</td>'
                                content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                                content +='<p>'+ cod.item +'</p>'
                                content +='</td>'
                                content +='<td colspan="3" align="left" valign="top" rowspan="1">'
                                content +='<span>'+ isi +'</span>'
                                content +='</td>'
                                content +='</tr>'
                                break;
                            case 7:
                                chk = airportcontent.find( x => x.category_id === 6 );
                                // console.log( 'content.find( x => x.category_id === cod.id )', content.find( x => x.category_id === cod.id ),chk )
                                if ( typeof chk == 'undefined' ) {
                                    isi = nil;
                                } else {
                                    isi = airportcontent.find( x => x.category_id === 6 ).content;
                                }
                    
                                if ( isi == '' || isi == null ) {
                                    isi = nil;
                                }

                                content +='<tr>'
                                content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ nn +'</td>'
                                content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                                content +='<p>'+ cod.item +'</p>'
                                content +='</td>'
                                content +='<td colspan="3" align="left" valign="top" rowspan="1">'
                                content +='<span>'+ isi +'</span>'
                                content +='</td>'
                                content +='</tr>'
                                chk = airportcontent.find( x => x.category_id === 7 );
                                // console.log( 'content.find( x => x.category_id === cod.id )', content.find( x => x.category_id === cod.id ),chk )
                                if ( typeof chk == 'undefined' ) {
                                    isi = nil;
                                } else {
                                    isi = airportcontent.find( x => x.category_id === 7 ).content;
                                }
                    
                                if ( isi == '' || isi == null ) {
                                    isi = nil;
                                }
        
                                    content +='<tr>'
                                    content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                                    content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                                    content +='<p></p>'
                                    content +='</td>'
                                    content +='<td colspan="3" align="left" valign="top" rowspan="1">'
                                    content +='<span>'+ isi +'</span>'
                                    content +='</td>'
                                    content +='</tr>'
                                // }
                                break;
                        }
                    } else if ( cod.form_type == 'B' ) {
                        chk = airportcontent.find( x => x.category_id === cod.id );
                        // console.log( 'content.find( x => x.category_id === cod.id )', content.find( x => x.category_id === cod.id ),chk )
                        if ( typeof chk == 'undefined' ) {
                            isi = nil;
                        } else {
                            isi = airportcontent.find( x => x.category_id === cod.id ).content;
                        }
            
                        if ( isi == '' || isi == null ) {
                            isi = nil;
                        }
                        no=6
                        // console.log('cod.form_type == B', no--,cod.form_type,cod.item,content)
                                content +='<tr>'
                                content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                                content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                                content +='<p></p>'
                                content +='</td>'
                                content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                                content +='<span>'+ cod.item + ' : </span>'
                                content +='</td>'
                                content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                                content +='<span>'+  isi +'</span>'
                                content +='</td>'
                                content +='</tr>'
                    } else {
                        content +='<tr>'
                        content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ no++ +'</td>'
                        content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                        content +='<p>'+ cod.item +'</p>'
                        content +='</td>'
                        content +='</td>'
                        content +='<td colspan="3" align="left" valign="top" rowspan="1">'
                        content +='<span></span>'
                        content +='</td>'
                        content +='</tr>'
                    }
                    // console.log( 'cod.item', cod.item,  posY)
                    // 
                }
            } )
                content += '</tbody>'
                content += '</table>'
                // console.log(content,'AD2')
                return content
        }
        function ad23(doc, sub )
        {
        
            var g = sub.replace( '#', ' ' )
            // var ttl = icao + ' ' + g;
            // content +='<div><h6 class="Title" style="color:brown" align="center"><span>' + icao + ' ' + ttl + '</span></h6></div>'
            content += '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%";line-height=1";font-size=0.7rem">'
            content +='<colgroup>'
            content +='<col span="1" style="width: 5%;">'
            content +='<col span="1" style="width: 35%;">'
            content +='<col span="1" style="width: 60%;">'
            content +='</colgroup>'
            var isi, no=1;
            eaiplist.forEach( ( cod ) =>
            {
                if ( cod.des == g ) {
                    // console.log( cod )
                    var chk = airportcontent.find( x => x.category_id === cod.id );
                    // console.log( 'content.find( x => x.category_id === cod.id )', content.find( x => x.category_id === cod.id ),chk )
                    var sts='U';
                    if ( typeof chk == 'undefined' ) {
                        isi = nil;
                    } else {
                        isi = airportcontent.find( x => x.category_id === cod.id ).content;
                        sts= airportcontent.find( x => x.category_id === cod.id ).status;
                    }
                    // console.log( sts )
                    if ( isi == '' || isi == null ) {
                        isi = nil;
                    }
                    content +='<tr>'
                    content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ no++ +'</td>'
                    content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                    content +='<p>'+ cod.item +'</p>'
                    content +='</td>'
                    content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                    if (sts=='U'){
                        content +='<span>'+ isi +'</span>'

                    }else{

                        content +='<span style="color:#d97034" ><i>'+ isi +'</i></span>'
                    }
                    content +='</td>'
                    content +='</tr>'
                }
            } )
                content += '</tbody>'
                content += '</table>'
                return content
        }
        function ad28(doc,sub)
        {
            var g = sub.replace( '#', ' ' )
            // var ttl = icao + ' ' + g;
            // content +='<div><h6 class="Title" style="color:brown" align="center"><span>' + icao + ' ' + ttl + '</span></h6></div>'
            content += '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
            content +='<colgroup>'
            content +='<col span="1" style="width: 5%;">'
            content +='<col span="1" style="width: 35%;">'
            content +='<col span="1" style="width: 15%;">'
            content +='<col span="1" style="width: 45%;">'
            content +='</colgroup>'
            content +='<tr>'
            content +='<td align="left" valign="top" colspan="1" rowspan="1">1</td>'
            content +='<td align="left" valign="top" colspan="1" rowspan="1">'
            content +='<p>Apron surface and strength</p>'
            content +='</td>'
            var no=1
            apronlist.forEach( ( cod ) =>
            {
                // console.log(cod)
                if (no==1){
                    content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                    content +='<span>'+  cod.name +'</span>'
                    content +='</td>'
                    content +='</tr>'

                }else{
                    content +='<tr>'
                    content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                    content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                    content +='<p></p>'
                    content +='</td>'
                    content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                    content +='<span>'+  cod.name +'</span>'
                    content +='</td>'
                    content +='</tr>'
                }
                

                content +='<tr>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                content +='<p></p>'
                content +='</td>'
                content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                content +='<span>Surface :</span>'
                content +='</td>'
                content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                content +='<span>'+  cod.surface +'</span>'
                content +='</td>'
                content +='</tr>'

                content +='<tr>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                content +='<p></p>'
                content +='</td>'
                content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                content +='<span>Strength :</span>'
                content +='</td>'
                content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                content +='<span>'+  cod.strength +'</span>'
                content +='</td>'
                content +='</tr>'

                content +='<tr>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                content +='<p></p>'
                content +='</td>'
                content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                content +='<span>Dimension :</span>'
                content +='</td>'
                content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                content +='<span>'+  cod.dimension +'</span>'
                content +='</td>'
                content +='</tr>'
                no++
                // console.log( cod)
            })
            content +='<tr>'
            content +='<td align="left" valign="top" colspan="1" rowspan="1">2</td>'
            content +='<td align="left" valign="top" colspan="1" rowspan="1">'
            content +='<p>Taxiway width, surface and strength</p>'
            content +='</td>'
            no=1
            twylist.forEach( ( cod ) =>
            {
                if (no==1){
                    content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                    content +='<span>'+  cod.name +'</span>'
                    content +='</td>'
                    content +='</tr>'

                }else{
                    content +='<tr>'
                    content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                    content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                    content +='<p></p>'
                    content +='</td>'
                    content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                    content +='<span>'+  cod.name +'</span>'
                    content +='</td>'
                    content +='</tr>'
                }

                content +='<tr>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                content +='<p></p>'
                content +='</td>'
                content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                content +='<span>Surface :</span>'
                content +='</td>'
                content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                content +='<span>'+  cod.surface +'</span>'
                content +='</td>'
                content +='</tr>'

                content +='<tr>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                content +='<p></p>'
                content +='</td>'
                content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                content +='<span>Strength :</span>'
                content +='</td>'
                content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                content +='<span>'+  cod.strength +'</span>'
                content +='</td>'
                content +='</tr>'

                content +='<tr>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1"></td>'
                content +='<td align="left" valign="top" colspan="1" rowspan="1">'
                content +='<p></p>'
                content +='</td>'
                content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                content +='<span>Width :</span>'
                content +='</td>'
                content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                content +='<span>'+  cod.dimension +'</span>'
                content +='</td>'
                content +='</tr>'
                no++
                // console.log( cod)
            })
                content += '</tbody>'
                content += '</table>'
                content += '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
                content +='<colgroup>'
                content +='<col span="1" style="width: 5%;">'
                content +='<col span="1" style="width: 35%;">'
                content +='<col span="1" style="width: 60%;">'
                content +='</colgroup>'
                var isi;
                no=4;
                eaiplist.forEach( ( cod ) =>
                {
                // console.log( cod.id )
                    if ( cod.des == g && cod.id > 52 ) {
                        var chk = airportcontent.find( x => x.category_id === cod.id );
                        // console.log( 'content.find( x => x.category_id === cod.id )', content.find( x => x.category_id === cod.id ),chk )
                        if ( typeof chk == 'undefined' ) {
                            isi = nil;
                        } else {
                            isi = airportcontent.find( x => x.category_id === cod.id ).content;
                        }

                        if ( isi == '' || isi == null ) {
                            isi = nil;
                        }
                        content +='<tr>'
                        content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ no++ +'</td>'
                        content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                        content +='<p>'+ cod.item +'</p>'
                        content +='</td>'
                        content +='<td colspan="2" align="left" valign="top" rowspan="1">'
                        content +='<span>'+ isi +'</span>'
                        content +='</td>'
                        content +='</tr>'
                    }
                } )
                content += '</tbody>'
                content += '</table>'
           
        }
        function tblobst(area){
            var obstbl=''
            obstbl = '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
            obstbl +='<colgroup>'
            obstbl +='<col span="1" style="width: 15%;">'
            obstbl +='<col span="1" style="width: 15%;">'
            obstbl +='<col span="1" style="width: 20%;">'
            obstbl +='<col span="1" style="width: 15%;">'
            obstbl +='<col span="1" style="width: 15%;">'
            obstbl +='<col span="1" style="width: 15%;">'
            obstbl +='</colgroup>'
            obstbl +='<thead>'
            obstbl +='<tr align="center">'
            obstbl +='<th valign="top" colspan="6" rowspan="1">Area ' + area +'</th>'
            obstbl +='</tr>'
            obstbl +='<tr align="center" valign="middle">'
            obstbl +='<th>OBST ID/ Designation</th>'
            obstbl +='<th>OBST Type</th>'
            obstbl +='<th>OBST Position</th>'
            obstbl +='<th>ELEV/HGT</th>'
            obstbl +='<th>Markings/Type, colour</th>'
            obstbl +='<th>Remarks</th>'
            obstbl +='</tr>'
            obstbl +='<tr align="center" valign="middle">'
            obstbl +='<th>1</th>'
            obstbl +='<th>2</th>'
            obstbl +='<th>3</th>'
            obstbl +='<th>4</th>'
            obstbl +='<th>5</th>'
            obstbl +='<th>6</th>'
            obstbl +='</tr>'
            obstbl +='</thead>'
            return obstbl
        }
        function ad210()
        {
        //  console.log( obs2)
            // console.log( obs3)
            // var g = sub.replace( '#', ' ' )
            // var ttl = icao + ' ' + g;
            // content +='<div><h6 class="Title" style="color:brown" align="center"><span>' + icao + ' ' + ttl + '</span></h6></div>
            content += tblobst(2)
            if (obs2.length == 0){
                content +='<tr align="center">'
                content +='<th valign="top" colspan="6" rowspan="1">NIL</th>'
                content +='</tr>'
            }else{
                var no=1;
                obs2.forEach( ( cod ) =>
                {
                    // console.log(cod)
                        content +='<tr align="center">'
                        content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ no++ +'</td>'
                        content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                        content +='<p>'+ cod.definition +'</p>'
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        content +='<span>'+ cod.lat + ' ' + cod.lon +'</span>'
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        content +='<span>'+ Math.round(cod.elev_ft,0) + ' ft ' +'</span>'
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        if (cod.lighted=='Y'){
                            content +='<span>YES</span>'
                        }else{
                            content +='<span>NIL</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        content +='<span>'+ cod.remarks +'</span>'
                        content +='</td>'
                        content +='</tr>'
                    
                } )
                    content += '</tbody>'
                    content += '</table>'

            }
            content += '<p></p>'
            content += tblobst(3)
            if (obs3.length == 0){
                content +='<tr align="center">'
                content +='<th valign="top" colspan="6" rowspan="1">NIL</th>'
                content +='</tr>'
            }else{
                no=1;
                obs3.forEach( ( cod ) =>
                {
                        content +='<tr align="center">'
                        content +='<td align="left" valign="top" colspan="1" rowspan="1">'+ no++ +'</td>'
                        content +='<td  align="left" valign="top" colspan="1" rowspan="1">'
                        content +='<p>'+ cod.definition +'</p>'
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        content +='<span>'+ cod.lat + ' ' + cod.lon +'</span>'
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        content +='<span>'+ Math.round(cod.elev_ft,0) + ' ft ' +'</span>'
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        if (cod.lighted=='Y'){
                            content +='<span>YES</span>'
                        }else{
                            content +='<span>NIL</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="left" valign="top" rowspan="1">'
                        content +='<span>'+ cod.remarks +'</span>'
                        content +='</td>'
                        content +='</tr>'
                    
                } )
            }
                content += '</tbody>'
                content += '</table>'
        }
        function tblrwy(rwy){
            var tblrwy=''
            tblrwy = '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
            tblrwy +='<colgroup>'
            tblrwy +='<col span="1" style="width: 20%;">'
            tblrwy +='<col span="1" style="width: 20%;">'
            tblrwy +='<col span="1" style="width: 20%;">'
            tblrwy +='<col span="1" style="width: 20%;">'
            tblrwy +='<col span="1" style="width: 20%;">'
            tblrwy +='</colgroup>'
            tblrwy +='<thead>'
            if (rwy=="1"){
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>Designations RWY NR</th>'
                tblrwy +='<th>True BRG</th>'
                tblrwy +='<th>Dimensions of RWY (M)</th>'
                tblrwy +='<th>Strength (PCN) and surface of RWY and SWY</th>'
                tblrwy +='<th>THR coordinates RWY end coordinates THR geoid undulation</th>'
                tblrwy +='</tr>'
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>1</th>'
                tblrwy +='<th>2</th>'
                tblrwy +='<th>3</th>'
                tblrwy +='<th>4</th>'
                tblrwy +='<th>5</th>'
                tblrwy +='</tr>'

            }else if (rwy=="2"){
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>THR Elevation and Highest Elevation of TDZ of Precision APP RWY</th>'
                tblrwy +='<th>Slope of RWY - SWY</th>'
                tblrwy +='<th>SWY Dimensions (M)</th>'
                tblrwy +='<th>CWY Dimensions (M)</th>'
                tblrwy +='<th>Strip Dimensions (M)</th>'
                tblrwy +='</tr>'
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>6</th>'
                tblrwy +='<th>7</th>'
                tblrwy +='<th>8</th>'
                tblrwy +='<th>9</th>'
                tblrwy +='<th>10</th>'
                tblrwy +='</tr>'
            
            }else if (rwy=="3"){
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>RESA Dimensions (M)</th>'
                tblrwy +='<th>Location and Description of Arresting System</th>'
                tblrwy +='<th>OFZ</th>'
                tblrwy +='<th>Remarks</th>'
                tblrwy +='</tr>'
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>11</th>'
                tblrwy +='<th>12</th>'
                tblrwy +='<th>13</th>'
                tblrwy +='<th>14</th>'
                tblrwy +='</tr>'
            }else if (rwy=="4"){
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>RWY Designator</th>'
                tblrwy +='<th>TORA (M)</th>'
                tblrwy +='<th>TODA (M)</th>'
                tblrwy +='<th>ASDA (M)</th>'
                tblrwy +='<th>LDA (M)</th>'
                tblrwy +='<th>Remarks</th>'
                tblrwy +='</tr>'
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>1</th>'
                tblrwy +='<th>2</th>'
                tblrwy +='<th>3</th>'
                tblrwy +='<th>4</th>'
                tblrwy +='<th>5</th>'
                tblrwy +='<th>6</th>'
                tblrwy +='</tr>'
            } else if (rwy=="5"){
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>RWY Designator</th>'
                tblrwy +='<th>APCH LGT type LEN INTST</th>'
                tblrwy +='<th>THR LGT colour WBAR</th>'
                tblrwy +='<th>VASIS (MEHT) PAPI</th>'
                tblrwy +='<th>TDZ, LGT LEN</th>'
                tblrwy +='</tr>'
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>1</th>'
                tblrwy +='<th>2</th>'
                tblrwy +='<th>3</th>'
                tblrwy +='<th>4</th>'
                tblrwy +='<th>5</th>'
                tblrwy +='</tr>'
            }else if (rwy=="6"){
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>RWY Centre Line LGTLEN, spacing, colour, INTST</th>'
                tblrwy +='<th>RWY Edge LGT LEN, spacing colour INTST</th>'
                tblrwy +='<th>RWY End LGT colour WBAR</th>'
                tblrwy +='<th>SWY LGT LEN (M) Colour</th>'
                tblrwy +='<th>Remarks</th>'
                tblrwy +='</tr>'
                tblrwy +='<tr align="center" valign="middle">'
                tblrwy +='<th>6</th>'
                tblrwy +='<th>7</th>'
                tblrwy +='<th>8</th>'
                tblrwy +='<th>9</th>'
                tblrwy +='<th>10</th>'
                tblrwy +='</tr>'
            }
            tblrwy +='</thead>'
            return tblrwy
        }
        function ad212()
        {
            // var g = sub.replace( '#', ' ' )
            // console.log('rwylist',rwylist)
            content += tblrwy("1")
            rwylist.forEach( ( rwy ) =>
                {
                    // console.log('RWY',rwy)
                    rwy.physicals.forEach( ( thr ) =>
                    {
                        // console.log('THR',thr)
                        content +='<tr align="center">'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">'+ thr.rwy_ident +'</td>'
                        content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                        content +='<p>'+ thr.true_brg +'°</p>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        content +='<span>'+ rwy.length + ' x ' + rwy.width +'</span>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        content +='<span>'+ rwy.definition + ' ' + rwy.pcn +'</span>'
                        content +='</td>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        hsl = SetCoordinatebyGeom(thr.geom);
                        content +='<span>'+ hsl.WGSAIP[1] + ' ' + hsl.WGSAIP[0] +'</span>'
                        content +='</td>'
                        content +='</tr>'
                    })
                } )
                    content += '</tbody>'
                    content += '</table>'
            content += '<p></p>'
            content += tblrwy("2")
            rwylist.forEach( ( rwy ) =>
                {
                    // console.log('RWY',rwy)
                    rwy.physicals.forEach( ( thr ) =>
                    {
                        // console.log('THR',thr)
                        content +='<tr align="center">'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">'+ thr.thr_elev +' ft</td>'
                        content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                        content +='<p>'+ thr.slope +'</p>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (thr.swy_length== null || thr.swy_length=='' || thr.swy_length=='0'){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.swy_length + ' x ' + thr.swy_width +'</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (thr.cwy_length== null || thr.cwy_length==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.cwy_length + ' x ' + thr.cwy_width +'</span>'
                        }
                        content +='</td>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (rwy.strip_l== null || thr.strip_l==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ rwy.strip_l + ' x ' + rwy.strip_w +'</span>'
                        }
                        content +='</td>'
                        content +='</tr>'
                    })
                } )
                    content += '</tbody>'
                    content += '</table>'
            content += '<p></p>'
            content += tblrwy("3")
            rwylist.forEach( ( rwy ) =>
                {
                    // console.log('RWY',rwy)
                    rwy.physicals.forEach( ( thr ) =>
                    {
                        // console.log('THR',thr)
                        content +='<tr align="center">'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (thr.resa_l== null || thr.resa_l==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.resa_l + ' x ' + thr.resa_w +'</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                        content +='</td>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        content +='<span>NIL</span>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (thr.remarks== null || thr.remarks==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.remarks + '</span>'
                        }
                        
                        content +='</td>'
                        content +='</tr>'
                    })
                } )
                    content += '</tbody>'
                    content += '</table>'

        }
        function ad213()
        {
            content += tblrwy("4")
            rwylist.forEach( ( rwy ) =>
                {
                    // console.log('RWY',rwy)
                    rwy.physicals.forEach( ( thr ) =>
                    {
                        // console.log('THR',thr)
                        content +='<tr align="center">'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">'+ thr.rwy_ident +'</td>'
                        content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                        if (thr.tora== null || thr.tora==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.tora + '</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (thr.toda== null || thr.toda==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.toda + '</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (thr.asda== null || thr.asda==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.asda + '</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        
                        if (thr.lda== null || thr.lda==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ thr.lda + '</span>'
                        }
                        content +='</td>'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        content +='<span>NIL</span>'
                        content +='</td>'
                        content +='</tr>'
                    })
                } )
                    content += '</tbody>'
                    content += '</table>'
        }
        function ad214()
        {
            content += tblrwy("5")
            rwylist.forEach( ( rwy ) =>
                {
                    // console.log('RWY',rwy)
                    rwy.physicals.forEach( ( thr ) =>
                    {
                        // console.log('THR',thr)
                        content +='<tr align="center">'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">'+ thr.rwy_ident +'</td>'
                        content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                        if (thr.lighting.length==0){
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                        }else{

                            if (thr.lighting[0].apch_lgt_type_len== null || thr.lighting[0].apch_lgt_type_len==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].apch_lgt_type_len + '</span>'
                            }
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            if (thr.lighting[0].thr_lgt_clr_wbar== null || thr.lighting[0].thr_lgt_clr_wbar==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].thr_lgt_clr_wbar + '</span>'
                            }
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            if (thr.lighting[0].vasis_meht_papi== null || thr.lighting[0].vasis_meht_papi==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].vasis_meht_papi + '</span>'
                            }
                            content +='</td>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            if (thr.lighting[0].tdz_lgt_len== null || thr.lighting[0].tdz_lgt_len==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].tdz_lgt_len + '</span>'
                            }
                        }
                        content +='</td>'
                        content +='</tr>'
                    })
                } )
                    content += '</tbody>'
                    content += '</table>'
                    content += '<p></p>'
                    content += tblrwy("6")
                    rwylist.forEach( ( rwy ) =>
                {
                    // console.log('RWY',rwy)
                    rwy.physicals.forEach( ( thr ) =>
                    {
                        // console.log('THR',thr)
                        if (thr.lighting.length==0){
                            content +='<tr align="center">'
                            content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>NIL</span>'
                            content +='</td>'
                            content +='</tr>'
                        }else{
                            content +='<tr align="center">'
                            content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                            if (thr.lighting[0].rwy_ctrln_lgt_length_spc_clr== null || thr.lighting[0].rwy_ctrln_lgt_length_spc_clr==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].rwy_ctrln_lgt_length_spc_clr + '</span>'
                            }
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            if (thr.lighting[0].rwy_edge_lgt_len_spc_clr== null || thr.lighting[0].rwy_edge_lgt_len_spc_clr==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].rwy_edge_lgt_len_spc_clr + '</span>'
                            }
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            if (thr.lighting[0].rwy_end_lgt_clr_wbar== null || thr.lighting[0].rwy_end_lgt_clr_wbar==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].rwy_end_lgt_clr_wbar + '</span>'
                            }
                            content +='</td>'
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            if (thr.lighting[0].swy_lgt_len_clr== null || thr.lighting[0].swy_lgt_len_clr==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].swy_lgt_len_clr + '</span>'
                            }
                            content +='</td>'
                            content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            if (thr.lighting[0].remark== null || thr.lighting[0].remark==''){
                                content +='<span>NIL</span>'
                            }else{
                                content +='<span>'+ thr.lighting[0].remark + '</span>'
                            }
                            content +='</td>'
                            content +='</tr>'
                        }
                    })
                } )
                    content += '</tbody>'
                    content += '</table>'
        }
        function tblcomnav(comm){
        var commtbl=''
        commtbl = '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
        commtbl +='<colgroup>'
        commtbl +='<col span="1" style="width: 3%;">'
        commtbl +='<col span="1" style="width: 22%;">'
        commtbl +='<col span="1" style="width: 25%;">'
        commtbl +='<col span="1" style="width: 25%;">'
        commtbl +='<col span="1" style="width: 25%;">'
        commtbl +='</colgroup>'
        commtbl +='<thead>'
        if (comm=='1'){
            commtbl +='<tr align="center" valign="middle">'
            commtbl +='<th colspan="2">Service designation</th>'
            commtbl +='<th>Call sign</th>'
            commtbl +='<th>Channel</th>'
            commtbl +='<th>SATVOICE Number (s)</th>'
            commtbl +='</tr>'
            commtbl +='<tr align="center" valign="middle">'
            commtbl +='<th colspan="2">1</th>'
            commtbl +='<th>2</th>'
            commtbl +='<th>3</th>'
            commtbl +='<th>4</th>'
            commtbl +='</tr>'
        } else if (comm=='2'){
            commtbl +='<tr align="center" valign="middle">'
            commtbl +='<th colspan="2">Logon address</th>'
            commtbl +='<th>Hours of operation</th>'
            commtbl +='<th>Remarks</th>'
            commtbl +='</tr>'
            commtbl +='<tr align="center" valign="middle">'
            commtbl +='<th colspan="2">5</th>'
            commtbl +='<th>6</th>'
            commtbl +='<th>7</th>'
            commtbl +='</tr>'
        }else if (comm=='3'){
            commtbl +='<tr align="center" valign="middle" style="width:100%">'
            commtbl +='<th colspan="2" >Type of aids, Magnetic variation, and Type of supported operation for ILS/MLS, Basic GNSS, SBAS, and GBAS, and for VOR/ILS/MLS lso Station declination used for technical line-up of the aid</th>'
            commtbl +='<th>ID</th>'
            commtbl +='<th>Frequency(ies), Channel number(s), Service provider and Reference Path Identifier(s) (RPI)</th>'
            commtbl +='<th>Hours of operation</th>'
            commtbl +='</tr>'
            commtbl +='<tr align="center" valign="middle">'
            commtbl +='<th colspan="2">1</th>'
            commtbl +='<th>2</th>'
            commtbl +='<th>3</th>'
            commtbl +='<th>4</th>'
            commtbl +='</tr>'
        }else if (comm=='4'){
            commtbl +='<tr align="center" valign="middle">'
            commtbl +='<th colspan="2">Geographical coordinates of the position of the transmitting antennad</th>'
            commtbl +='<th>Elevation of the transmitting antenna of DME, of DME/P, Elevation of GBAS reference point, and The ellipsoid height of the point. For SBAS, The ellipsoid height of the landing threshold point (LTP) or The fictitious threshold point (FTP)</th>'
            commtbl +='<th>Service volume radius from the GBAS reference point</th>'
            commtbl +='<th>Remarks</th>'
            commtbl +='</tr>'
            commtbl +='<tr align="center" valign="middle">'
            commtbl +='<th colspan="2">5</th>'
            commtbl +='<th>6</th>'
            commtbl +='<th>7</th>'
            commtbl +='<th>8</th>'
            commtbl +='</tr>'
        }
        return commtbl
        }
        function ad218()
        {
            // console.log('freqlist',freqlist)
            // console.log('navarptlist',navarptlist)
            // freqlist.sort((a,b) => (a.types > b.types) ? 1 : ((b.types > a.types) ? -1 : 0));
            var no =1;
            content += tblcomnav("1")
                freqlist.forEach( ( freq ) =>
                {
                    var tp=freq.callsign[0].types;frq='';
                    switch(tp){
                        case "SSB":
                            tp='RADIO';
                            break;
                        case "AFI":
                            tp='AFIS';
                            break;
                        case "ATI":
                            tp='ATIS';
                            break;
                    }
                    if (freq.callsign.length > 0){
                        freq.callsign[0].segment.forEach( ( f ) =>{
                            var sts='';
                            if (f.level=='2'){sts='(SRY)'};
                                
                            var fval=Airspacefreq(f.value[0].freq,f.value[0].unit) + sts;
                            if (frq==''){
                                frq=fval;
                            }else{
                                frq+= ', ' + fval;
                            }
                        })
                        content +='<tr align="center">'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">' + no +'</td>'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">'+ tp +'</td>'
                        content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                        if (freq.callsign[0].call_sign== null || freq.callsign[0].call_sign==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ freq.callsign[0].call_sign + '</span>'
                        }
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        if (frq==''){
                            content +='<span>NIL</span>'
                        }else{
                            // if (f.status=='U'){
                            //     content +='<span>'+ frq +'</span>'

                            // }else{

                            //     content +='<span style="color:#d97034" ><i>'+ frq +'</i></span>'
                            // }
                            content +='<span>'+ frq + '</span>'
                        }
                        content +='</td>'
                        var satcom = '';
                        if (freq.callsign[0].segment.length > 0){
                            if (freq.callsign[0].segment[0].satcom == null || freq.callsign[0].segment[0].satcom == ''){
                                satcom = nil;
                            }else{
                                satcom = freq.callsign[0].segment[0].satcom;
                            }

                        }
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                            content +='<span>'+ satcom + '</span>'
                        content +='</td>'
                        content +='</tr>'
                        no++
                    }
                })
                    content += '</tbody>'
                    content += '</table>'
                    content += '<p></p>'


            content += tblcomnav("2")
            no=1;
            freqlist.forEach( ( freq ) =>
                {
                    var logon = '';opr_hrs=''
                    if (freq.callsign.length > 0){
                        if (freq.callsign[0].segment.length > 0){

                            if (freq.callsign[0].segment[0].logon == null || freq.callsign[0].segment[0].logon == ''){
                                logon = nil;
                            }else{
                                logon = freq.callsign[0].segment[0].logon;
                            }
                            if (freq.callsign[0].segment[0].opr_hrs== null || freq.callsign[0].segment[0].opr_hrs==''){
                                opr_hrs ='NIL'
                            }else{
                                opr_hrs = freq.callsign[0].segment[0].opr_hrs;
                            }
                        }
                        content +='<tr align="center">'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">' + no +'</td>'
                        content +='<td align="center" valign="top" colspan="1" rowspan="1">' + logon + '</td>'
                        content +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                        content +='</td>'
                        content +='<td colspan="1" align="center" valign="top" rowspan="1">'
                        content +='<span>'+opr_hrs+'</span>'
                        if (freq.callsign[0].remarks== null || freq.callsign[0].remarks==''){
                            content +='<span>NIL</span>'
                        }else{
                            content +='<span>'+ freq.callsign[0].remarks + '</span>'
                        }
                        content +='</td>'
                        content +='</tr>'
                        no++
                    }
                })
            content += '</tbody>'
            content += '</table>'
        }
        function ad219content1(nav,type,no){
            var tps='';ident='';frq='';hrs='';cord='';elev='';gbas='';rem='';
            switch(type){
                case "NAV":
                    tps=nav.definition;
                    ident=nav.nav_ident;
                    if (nav.type=='4'){
                        var frqd=FreqFormat(nav.freq,nav.type,'DATA');
                        frq = FreqFormat(nav.freq,nav.type,'')+ '/' + 'CH-' +ch.find( x => x.definition === frqd ).id;
                    }else if (nav.type=='20'){
                        frq=nil;
                        ident=nav.nav_name;
                    }else{
                        frq = FreqFormat(nav.freq,nav.type,'');
                    }

                    if (nav.opr_hrs==null || nav.opr_hrs==''){
                        hrs=nil;
                    }else{
                        hrs=nav.opr_hrs;
                    }
                    break;
                case "ILS":
                    tps='ILS/LLZ';
                    ident=nav.ils_ident;
                    frq = FreqFormat(nav.freq,'11','');
                    if (nav.opr_hrs==null || nav.opr_hrs==''){
                        hrs=nil;
                    }else{
                        hrs=nav.opr_hrs;
                    }
                    break;
                case "GP":
                    tps=type;
                    ident='';
                    var frqd=FreqFormat(nav.freq,'11','DATA');
                    frq = ch.find( x => x.definition === frqd ).gs_freq;
                    if (nav.opr_hrs==null || nav.opr_hrs==''){
                        hrs=nil;
                    }else{
                        hrs=nav.opr_hrs;
                    }
                    break;
                case "DME":
                    tps=type;
                    ident='';
                    var frqd=FreqFormat(nav.freq,'11','DATA');
                    frq = 'CH-' + ch.find( x => x.definition === frqd ).id;
                    if (nav.opr_hrs==null || nav.opr_hrs==''){
                        hrs=nil;
                    }else{
                        hrs=nav.opr_hrs;
                    }
                    break;
                default:
                    tps=type;
                    ident='';
                    frq = nav.freq;
                    if (nav.opr_hrs==null || nav.opr_hrs==''){
                        hrs=nil;
                    }else{
                        hrs=nav.opr_hrs;
                    }
                    break;
            }
            

            var cont='';
            cont ='<tr align="center">'
            cont +='<td align="center" valign="top" colspan="1" rowspan="1">' + no +'</td>'
            cont +='<td align="center" valign="top" colspan="1" rowspan="1">'+ tps +'</td>'
            cont +='<td  align="center" valign="top" colspan="1" rowspan="1">'
            cont +='<span>'+ ident + '</span>'
            
            cont +='</td>'
            cont +='<td colspan="1" align="center" valign="top" rowspan="1">'
            cont +='<span>'+ frq + '</span>'
            cont +='</td>'
            cont +='<td colspan="1" align="center" valign="top" rowspan="1">'
            
            cont +='<span>'+ hrs + '</span>'
            
            cont +='</td>'
            cont +='</tr>'
            return cont
        }
        function ad219content2(nav,type,no){
            var cord='';elev='';gbas='';rem='';
                // console.log(nav)
                switch(type){
                    case "GP":
                    case "DME":
                        var c=SetCoordinatebyGeom(nav.gs_geom)
                        cord=c.WGS[1] + ' ' + c.WGS[0];
                        if (nav.remarks== null || nav.remarks==''){
                            rem=nil;
                        }else{
                            rem=nav.remarks;
                        }
                        break;
                    default:
                    var c=SetCoordinatebyGeom(nav.geom)
                    cord=c.WGS[1] + ' ' + c.WGS[0];
                    if (nav.remarks== null || nav.remarks==''){
                        rem=nil;
                    }else{
                        rem=nav.remarks;
                    }
                        break;
                }
                if (type=='NAV' || type == 'ILS'){
                    
                   
                }
           

            var cont='';
            cont +='<tr align="center">'
            cont +='<td align="center" valign="top" colspan="1" rowspan="1">' + no +'</td>'
            cont +='<td align="center" valign="top" colspan="1" rowspan="1">'+ cord +'</td>'
            cont +='<td  align="center" valign="top" colspan="1" rowspan="1">'
            cont +='<span>NIL</span>'
            cont +='</td>'
            cont +='<td colspan="1" align="center" valign="top" rowspan="1">'
            cont +='<span>NIL</span>'
            cont +='</td>'
            cont +='<td colspan="1" align="center" valign="top" rowspan="1">'
            cont +='<span>'+ rem + '</span>'
            cont +='</td>'
            cont +='</tr>'
            return cont
        }

        function ad219()
        {
            content += tblcomnav("3")
            // console.log('navarptlist',navarptlist)
            // navarptlist.sort((a,b) => (a.types > b.types) ? 1 : ((b.types > a.types) ? -1 : 0));
            var no=1;
            navarptlist.forEach( ( nav ) =>
                {
                    // console.log(nav)
                   
                    var tps='';ident='';frq='';hrs='';cord='';elev='';gbas='';rem='';
                    if (nav.navaid.length > 0){
                        if (nav.navaid[0].type == '9'){
                            no--
                        }else{
                            content += ad219content1(nav.navaid[0],'NAV',no);

                        }
                    }else if (nav.ils.length > 0){
                        content += ad219content1(nav.ils[0],'ILS',no);
                        no++
                        content += ad219content1(nav.ils[0],'GP',no);
                        if (nav.ils[0].nav_id !== null){
                            no++
                            content += ad219content1(nav.ils[0],'DME',no);

                        }
                        nav.ils[0].marker.forEach(m=>{
                            no++
                            tps=m.mrkr_type;
                            content += ad219content1(m,m.mrkr_type,no);

                        });

                    }
                    no++;
                })
            content += '</tbody>'
            content += '</table>'
            content += '<p></p>'


            content += tblcomnav("4")
            no=1;
            navarptlist.forEach( ( nav ) =>
                {
                    var cord='';elev='';gbas='';rem='';
                    if (nav.navaid.length > 0){
                        if (nav.navaid[0].type == '9'){
                            no--
                        }else{
                            content += ad219content2(nav.navaid[0],'NAV',no);
                        }
                        
                    }else if (nav.ils.length > 0){
                        content += ad219content2(nav.ils[0],'ILS',no);
                        no++
                        content += ad219content2(nav.ils[0],'GP',no);
                        if (nav.ils[0].nav_id !== null){
                            no++
                            content += ad219content2(nav.ils[0],'DME',no);

                        }
                        nav.ils[0].marker.forEach(m=>{
                            no++
                            tps=m.mrkr_type;
                            content += ad219content2(m,m.mrkr_type,no);

                        });
                        
                    }
                    no++;
                })
            content += '</tbody>'
            content += '</table>'
        }
        function ad220(arptident, codid)
        {
            content +='<p></p>';
            var dt='';endp=false;
            freetext.forEach(a=>{
                if (a.category_id=== codid){
                    if (a.font=='B'){
                        dt += '<span><b>' + a.content + '</b></span><br>'
                    }else{
                        dt += '<span>' +a.content+ '</span><br>'
                    }
                }
            })

                if (dt==''){
                    content += '<p align="center"><i>Reserved</i></p><br>';
                }else{
                    content += dt;
                }
            
            content += '<p></p>'
        
        }
        function ad224()
        {
            content +='<p></p>';
            var dt='';endp=false;
            chart.forEach(a=>{
                // console.log(a)
            // $("#Attach").hide();
            if (a.path_file !== null && a.aip_sub_id=='AD 2.24'){
                
                var fl = a.path_file.replace('images/','');
                var pathdetail= 'upload/publication/aip/' + fl;
                // console.log(pathdetail)
                // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
                // window.open("{{URL::to('/')}}/" + pathdetail, 'airportcontent', params)
 
                dt = '<span style="cursor:pointer;" onclick="vModal(\''+pathdetail+'\')" target="">- ' + a.chart_name + '</span><br>'

                if (dt==''){
                    content += '<p align="center"><i>Reserved</i></p><br>';
                }else{
                    content += dt;
                }
            }
            })
            content += '<p></p>'
            content += '<br><a class="btn btn-dim btn-secondary mt-2" onclick="backtolist()"><i class="icon ni ni-reply-fill"></i> Back</a>'
                
        
        
        }
function backtolist(){
    history.back();

}




</script>
@endsection
