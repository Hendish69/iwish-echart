@extends('layouts.app')

@section('template_title')
    {{$aipcode}}
@endsection

@section('head')
@endsection

@section('content')

<div class="container-fluid">
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
                    <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                </li>
            </ul>
            <div class="tab-content tabairspace" id="tabasp">
                <div class="tab-pane" id="tabItem1">
                    <div class="nk-content-body mt-3" id="freetext">
                    </div>
                </div>
                <div class="tab-pane  active" id="tabItem2">
                    <div id="iframe-wrapper">

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- <a class="btn btn-dim btn-secondary mt-2" onclick="backtolist()"><i class="icon ni ni-reply-fill"></i> Back</a>
    <div class="nk-content-inner">
        <div class="nk-content-body mt-3" id="freetext">
        </div>
    </div> -->
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
var airspace =@json($airspace);
var cd=@json($aipcode);
var chart =@json($chart);

remove("iframepdf")
var fl = chart[0].path_file.replace('images/','');
    var pathdetail= pathpop()+ '/upload/publication/aip/' + fl;
var div =document.getElementById("iframe-wrapper")
// console.log(div)
div.innerHTML = "<iframe id='iframepdf-gen' src='" + pathdetail + "' type='application/pdf' width='100%' height='650px'/>"
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
if (cd=='ENR 2.1'){

var asptype =[{id:'FIR',def:'FLIGHT INFORMATION REGIONS (FIR) :'}, {id:'UTA',def:'UPPER CONTROL AREAS (UTA)<br>WITHIN JAKARTA & UJUNG PANDANG FIRS : '}, {id:'SECTOR',def:'SECTORS WITHIN FLIGHT<br>INFORMATION REGION (FIR) : '}, {id:'CTA',def:'CONTROL AREA (CTA) : <br> CTA WITHIN JAKARTA FIR :'}, {id:'MTCA',def:''},{id:'TMA',def:'TERMINAL CONTROL AREAS (TMA)<br> WITHIN JAKARTA & UJUNG PANDANG FIRS : '}];


this.asplist=[];
for (let i=0;i<asptype.length;i++){
    airspace.forEach( asp =>
        {
            if (asp.airspace_type==asptype[i].id){
                // console.log(asp)
                var ass=[]
                // if (asp.airspace_type=='UTA'){
                //     if (asp.icao_reg=='WI'){
                //         ass[ 'asptype' ] = asptype[i].def + '<br>JAKARTA ' +asp.airspace_type +'  : ';
                //     }else{
                //         ass[ 'asptype' ] = asptype[i].def + '<br>UJUNG PANDANG ' +asp.airspace_type +'  : ';
                //     }
                // }else{
                    ass[ 'asptype' ] = asptype[i].def
                // }
                
                ass[ 'seq' ] = asp.codseq
                ass[ 'name' ] =  asp.airspace_name  + ' ' + asp.airspace_type
                ass[ 'text' ] = getsegmenttext(asp)
                ass[ 'upper' ] = asp.class[ 0 ].upper
                ass[ 'lower' ] = asp.class[ 0 ].lower
                var cls=''
                if ( asp.class[ 0 ].asp_class == '' || asp.class[ 0 ].asp_class == null ) {
                    cls=''
                } else {
                    cls='Airspace Classification : ' + asp.class[0].asp_class
                }
                ass[ 'cls' ] = cls
                ass[ 'unit' ] = asp.ats_unit
                ass[ 'csign' ] = asp.freq[ 0 ].callsign[ 0 ].call_sign 
                ass[ 'lang' ] = 'English'
                ass[ 'oprhrs' ] = asp.freq[ 0 ].callsign[ 0 ].segment[ 0 ].opr_hrs
                var frq=''
                for ( let x = 0; x < asp.freq[ 0 ].callsign[ 0 ].segment.length; x++){
                    var f =''
                    if ( asp.freq[ 0 ].callsign[ 0 ].segment[ x ].value[ 0 ].unit == 'V' ) {
                        f= asp.freq[ 0 ].callsign[ 0 ].segment[ x ].value[ 0 ].freq/1000000 + ' MHz'
                    } else {
                        f= asp.freq[ 0 ].callsign[ 0 ].segment[ x ].value[ 0 ].freq/1000 + ' kHz'
                    }
                    if ( frq == '' ) {
                        frq=f
                    } else {
                        frq +=', ' + f
                    }
                }
                ass[ 'frq' ] = frq
                if ( asp.freq[ 0 ].callsign[ 0 ].remarks == null ) {
                    ass[ 'rem' ] =''
                } else {
                    ass[ 'rem' ] = asp.freq[ 0 ].callsign[ 0 ].remarks
                }
                this.asplist.push(ass)
            }

        } )
    }
    
if (this.asplist.length > 0){

        this.isi ='<h5 class="title" style="text-align:center;color:brown">ENR 2  AIR TRAFFIC SERVICE AIRSPACE</h5>'
        this.isi +='<h6 class="title" style="color:brown" align="center">2.1 FIR, UTA, CTA, TMA</h6>'
        this.isi += this.tblasp()
this.asptype="";

    this.asplist.forEach( asp =>
    {  
        // console.log(asp)
        
        this.isi +='<tr align="center" style="color:brown;background-color:#f0f0f0;border-color: #999999">'
        this.isi +='<td align="left" valign="top" colspan="1" rowspan="1">'
        if (asp.asptype !== this.asptype){
            this.isi +='<p align="center" style="text-align:center"><b>' + asp.asptype +'</b></p>'
        }
        
        this.isi +='<p><b>' + asp.name +'</b></p>'
        this.isi +='<p align="justify">' + asp.text +'</p>'
        this.isi +='<p align="center">' + asp.cls +'</p>'
        this.isi +='<p align="center">'
        this.isi +='<u>' + asp.upper +'</u><br>'
        this.isi += asp.lower
        this.isi +='</p>'
        this.isi +='</td>'
        this.isi +='<td  align="center" valign="top" colspan="1" rowspan="1">'
        this.isi +='<p>'+ asp.unit +'</p>'
        this.isi +='</td>'
        this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
        this.isi +='<p>'+ asp.csign +'<br>'
        this.isi += asp.lang + '<br>'
        this.isi += asp.oprhrs +'</p>'
        this.isi +='</td>'
        this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
        this.isi +='<span>'+ asp.frq  +'</span>'
        this.isi +='</td>'
        this.isi +='</td>'
        this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
        this.isi +='<span>'+ asp.rem +'</span>'
        this.isi +='</td>'
        this.isi +='</tr>'
        this.asptype=asp.asptype;
    })
        this.isi += '</tbody>'
        this.isi += '</table>'

    $("#freetext").append( this.isi);
}




}else if (cd=='ENR 5.1'){ 
   
    var subid='ENR 5.1 PROHIBITED, RESTRICTED AND DANGER AREAS';
    var asptype =[{id:'D',def:'DANGER AREA'}, {id:'P',def:'PROHIBITED AREA'}, {id:'R',def:'RESTRICTED AREA'}];
    this.asplist=[];
    airspace.sort((a,b) => (a.suas_ident > b.suas_ident) ? 1 : ((b.suas_ident > a.suas_ident) ? -1 : 0));
    for (let i=0;i<asptype.length;i++){
        airspace.forEach( asp =>
        {
            if (asp.suas_type==asptype[i].id){
                // console.log(asp)
                var ass=[]
                ass[ 'asptype' ] = asptype[i].def

                
                ass[ 'seq' ] = asp.codseq
                ass[ 'name' ] =  asp.suas_ident  + ' ' + asp.suas_name
                ass[ 'text' ] = getsegmenttext(asp)
                ass[ 'upper' ] = asp.upper
                ass[ 'lower' ] = asp.lower
                var remm='';
                if (asp.remarks.length >0){
                    
                    for (let i=0; i < asp.remarks.length; i++) {
                        if (remm==''){
                            $remm=asp.remarks[i].remarks;
                        }else{
                            $remm +=asp.remarks[i].remarks;

                        }
                    }
                }else{
                    remm=asp.eff_times;
                }
                ass[ 'rem' ] = remm
                
                this.asplist.push(ass)
            }
        })

    } 
    
    
    enr51(subid)
}else{
    // console.log(airspace)
    var subid='5.2 MILITARY EXERCISE AND TRAINING AREAS AND AIR DEFENSE IDENDTIFICATION ZONE (ADIZ)';
    var asptype =[{id:'M',def:'MILITARY EXERCISE'}, {id:'T',def:'TRAINING AREAS'}];
    this.asplist=[];
    airspace.sort((a,b) => (a.suas_ident > b.suas_ident) ? 1 : ((b.suas_ident > a.suas_ident) ? -1 : 0));
    for (let i=0;i<asptype.length;i++){
        airspace.forEach( asp =>
        {
            if (asp.suas_type==asptype[i].id){
                // console.log(asp)
                var ass=[]
                ass[ 'asptype' ] = asptype[i].def

                
                ass[ 'seq' ] = asp.codseq
                ass[ 'name' ] =  asp.suas_ident  + ' ' + asp.suas_name
                ass[ 'text' ] = getsegmenttext(asp)
                ass[ 'upper' ] = asp.upper
                ass[ 'lower' ] = asp.lower
                var remm='';
                if (asp.remarks.length >0){
                    
                    for (let i=0; i < asp.remarks.length; i++) {
                        if (remm==''){
                            $remm=asp.remarks[i].remarks;
                        }else{
                            $remm +=asp.remarks[i].remarks;

                        }
                    }
                }else{
                    remm=asp.eff_times;
                }
                ass[ 'rem' ] = remm
                
                this.asplist.push(ass)
            }
        })

    } 
    
    
    enr51(subid)
}
function Pretextenr51(){
    var hasil='<div class="row"><span><b>1. INTRODUCTION</b></span><br>'+
                '<span>1.1 </span>'+
                '<span>All airspace in which a potential hazard to aircraft operations may exist and all areas over which the operation of civil aircraft may, for one reason or another be restricted either temporarily or permanently, are classified according to the following three types of areas as defined by ICAO.</span></div>'; 
  
        // $j2='DANGER AREA';
        // $j3='PROHIBITED AREA';
        // $j4='RESTRICTED AREA';
       
        // $j11='1.1';
        // $j21='2.1';
        // $j31='3.1';
        // $j12='All airspace in which a potential hazard to aircraft operations may exist and all areas over which the operation of civil aircraft may, for one reason or another be restricted either temporarily or permanently, are classified according to the following three types of areas as defined by ICAO.';
        // $j22='An airspace of defined dimensions within which activities dangerous to the flight of aircraft may exist at specified times. This term is used only when the potential danger to aircraft has not led the designation of the airspace as restricted or prohibited. The effect of the creation of the danger areas is to caution operators or pilots of aircraft that it is necessary for them to assess the angers in relation to their responsibility for the safety of their aircraft.';
        // $j32='An airspace of defined dimensions, above the land areas or territorial waters of a State, within the flight of aircraft is prohibited. This term is used only when the flight of civil aircraft within the designated airspace is not permitted at any time under any circumstances.';
        // $j42='An airspace of defined dimensions, above the land areas or territorial waters of a State, within which the flight of aircraft is restricted in accordance with certain specified conditions. This term is used whenever the flight of civil aircraft within the designated airspace is not absolutely prohibited but may be made only if specified conditions are complied whit. Thus, prohibition of flight except at certain meteorological conditions. Similarly, prohibition of flight unless special Permission had been obtained, leads to the designation of restricted area.'. PHP_EOL .'However, conditions of flight imposed as a result of application of rules of the air traffic service practice or procedures (for example, compliance with minimum safe heights or with rules stemming from the establishment of controlled airspace) do not constitute conditions calling for designation as a restricted area.';
        // $j5='Each area is numbered and single series of numbers is used for all Areas, regardless of type to ensure that a numbers is never duplicated.';
        // $j6='The type of area involved is indicated by the letter “P” for Prohibited, “R” for Restricted and “D” for Danger. For example, areas are assigned numbers and letters in the following manner – WAD1, WAR2, WAP3, WID4 etc.';
        // $j7='Each area is described in the tabulation found in page ENR 5.1-2 – to 5.1-8. Which indicates its lateral and vertical limits, the type of restriction or hazard involved, the times at which it applies and other pertinent information.';
        return hasil;
}
function enr51(subid){
    this.isi ='<h5 class="title" style="text-align:center;color:brown">ENR 5 NAVIGATION WARNING</h5>'
        this.isi +='<h6 class="title" style="color:brown" align="center">'+subid+'</h6>'
        // this.isi +=Pretextenr51();
        this.isi += this.tbl51()
this.asptype="";

    this.asplist.forEach( asp =>
    {  
        // console.log(asp)
        
        this.isi +='<tr align="center" style="color:brown;background-color:#f0f0f0;border-color: #999999">'
        this.isi +='<td align="left" valign="top" colspan="1" rowspan="1">'
        if (asp.asptype !== this.asptype){
            this.isi +='<p align="left" style="text-align:left"><b><u>' + asp.asptype +'</u></b></p>'
        }
        
        this.isi +='<p><b>' + asp.name +'</b></p>'
        this.isi +='<p align="justify">' + asp.text +'</p>'
        this.isi +='<td align="center">'
        this.isi +='<u>' + asp.upper +'</u><br>'
        this.isi += asp.lower
        this.isi +='</td>'
        this.isi +='<td>'
        this.isi +='<span>'+ asp.rem +'</span>'
        this.isi +='</td>'
        this.isi +='</tr>'
        this.asptype=asp.asptype;
    })
        this.isi += '</tbody>'
        this.isi += '</table>'

    $("#freetext").append( this.isi);
}

function GetCord(lat,lon){
    if (isNaN(lat)==false && isNaN(lon)==false ){
        // console.log(lat,lon);
        return SetCoordinatebyDecimal(lon,lat);
    }else{
        return SetCoordinate(lat,lon);
    }
    // console.log(SetCoordinate(lat,lon))
    

}
function  tblasp(){
            var asptable=''
            asptable = '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
            asptable +='<colgroup>'
            asptable +='<col span="1" style="width: 40%;">'
            asptable +='<col span="1" style="width: 15%;">'
            asptable +='<col span="1" style="width: 15%;">'
            asptable +='<col span="1" style="width: 15%;">'
            asptable +='<col span="1" style="width: 15%;">'
            asptable +='</colgroup>'
            asptable +='<thead>'
            asptable +='<tr align="center" valign="middle">'
            asptable +='<td>Name Lateral Limits Vertical Limits Class of Airspace</td>'
            asptable +='<td>Unit Providing Service</td>'
            asptable +='<td>Call Sign Language Hour of Service Condition of Use hours of service</td>'
            asptable +='<td>Frequency / Purpose</td>'
            asptable +='<td>Remarks</td>'
            asptable +='</tr>'
            asptable +='<tr align="center" valign="middle">'
            asptable +='<td>1</td>'
            asptable +='<td>2</td>'
            asptable +='<td>3</td>'
            asptable +='<td>4</td>'
            asptable +='<td>5</td>'
            asptable +='</tr>'
            asptable +='</thead>'
            return asptable
        }
function  tbl51(){
    var asptable=''
    asptable = '<table class="table table-bordered" style="color:white;background-color:#999999;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
    asptable +='<colgroup>'
    asptable +='<col span="1" style="width: 65%;">'
    asptable +='<col span="1" style="width: 15%;">'
    asptable +='<col span="1" style="width: 20%;">'
    asptable +='</colgroup>'
    asptable +='<thead>'
    asptable +='<tr align="center" valign="middle">'
    asptable +='<td>Identification, Name and Lateral Limits</td>'
    asptable +='<td>Upper Limit<br>Lower Limit</td>'
    asptable +='<td>Remarks<br>(time of activity, type of nature of hazard, risk of interception)</td>'
    asptable +='</tr>'
    asptable +='<tr align="center" valign="middle">'
    asptable +='<td>1</td>'
    asptable +='<td>2</td>'
    asptable +='<td>3</td>'
    asptable +='</tr>'
    asptable +='</thead>'
    return asptable
}
function getsegmenttext(asp){
        var aspseg=asp.boundary
        var garpt='',gnav='',gen='';
        var gg = '', asptype = asp.airspace_type;
        for ( let i = 0; i < aspseg.length; i++ ){
                // console.log( 'aspseg[ i ].shap', aspseg[ i ].shap, aspseg[ i ])
                // console.log(gg)
                if ( aspseg[ i ].shap == "C" ) {
                    gg = "A circle with a radius " + aspseg[ i ].arc_dist + " NM centered at "
                    if ( (aspseg[ i ].arpt_ident == null || aspseg[ i ].arpt_ident == '') && (aspseg[ i ].nav_id == null || aspseg[ i ].nav_id == '') ) { 

                        this.hsl = GetCord(aspseg[ i ].arc_lat, aspseg[ i ].arc_long)
                        if (asptype == "FIR" ||asptype == "UIR" ) {
                            gg +=  this.hsl.FIR[1] + " " + this.hsl.FIR[0]
                        } else {
                            gg += this.hsl.NonFIR[1] + " " + this.hsl.NonFIR[0]
                        }
                    } else {
                        if (aspseg[ i ].navaid == null || aspseg[ i ].navaid == '') { 
                            this.hsl = GetCord(aspseg[ i ].airport[0].geom.coordinates[1], aspseg[ i ].airport[0].geom.coordinates[0])
                            garpt =" ARP " + " (" + this.hsl.ADText[1] + " " + this.hsl.ADText[0] + ")"
                            gg += garpt
                        } else {
                            this.hsl = GetCord( aspseg[ i ].navaid[ 0 ].geom.coordinates[ 1 ], aspseg[ i ].navaid[ 0 ].geom.coordinates[ 0 ] )
                            gnav =aspseg[ i ].navaid[ 0 ].definition + " " + aspseg[ i ].navaid[ 0 ].nav_ident + " (" + this.hsl.WGS[1] + " " + this.hsl.WGS[0] + ")"
                            gg += gnav
                            // gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                        }
                        // if (aspseg[ i ].arpt_ident !== null) {
                        //     gg += await this.Centerof(aspseg[ i ].arpt_ident)
                        // } else {
                        //     gg += await this.Centerof(aspseg[ i ].nav_id)
                        // }
                    }

                } else if ( aspseg[ i ].shap == "L" ) {
                    this.hsl = GetCord(aspseg[ i ].point1_lat, aspseg[ i ].point1_long)
                    if (asptype == "FIR" ||asptype == "UIR" ) {
                        if ( gg == "" ) {
                            gg =  this.hsl.FIR[1] + " " + this.hsl.FIR[0]
                        } else {
                            gg +=  " - " + this.hsl.FIR[1] + " " + this.hsl.FIR[0]
                        }
                        gg +=  " thence anti-clockwise along the arc of a circle radius " + aspseg[ i ].arc_dist + " NM centered at "

                        if ( (aspseg[ i ].arpt_ident == null || aspseg[ i ].arpt_ident == '') && (aspseg[ i ].nav_id == null || aspseg[ i ].nav_id == '') ) {    
                            this.hsl = GetCord(aspseg[ i ].arc_lat, aspseg[ i ].arc_long)
                            if ( aspseg[ i ].remarks == null || aspseg[ i ].remarks == '') {
                                gg +=  " (" + this.hsl.WGS[1] + " " + this.hsl.WGS[0] + ") to"
                            }else {
                                gg +=  aspseg[ i ].remarks.toUpperCase() + " ("  + this.hsl.WGS[1] + " " +this.hsl.WGS[0] + ") to"
                            }
                        } else {
                            if (aspseg[ i ].navaid == null || aspseg[ i ].navaid == '') { 
                                this.hsl = GetCord(aspseg[ i ].airport[0].geom.coordinates[1], aspseg[ i ].airport[0].geom.coordinates[0])
                                garpt =" ARP " + " (" + this.hsl.ADText[1] + " " + this.hsl.ADText[0] + ")"
                                gg += garpt + " to"
                            } else {
                                this.hsl = GetCord( aspseg[ i ].navaid[ 0 ].geom.coordinates[ 1 ], aspseg[ i ].navaid[ 0 ].geom.coordinates[ 0 ] )
                                gnav =aspseg[ i ].navaid[ 0 ].definition + " " + aspseg[ i ].navaid[ 0 ].nav_ident + " (" + this.hsl.WGS[1] + " " + this.hsl.WGS[0] + ")"
                                gg += gnav + " to"
                                // gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                            }
                            // if (aspseg[ i ].arpt_ident !== null) { 
                            //     gg += await this.Centerof(aspseg[ i ].arpt_ident) + " to"
                            // } else {
                            //     gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                            // }
                        }
                    } else {
                        if ( gg == "" ) {
                            gg = this.hsl.NonFIR[1] + " " + this.hsl.NonFIR[0]
                        } else {
                            gg +=  " - " + this.hsl.NonFIR[1] + " " + this.hsl.NonFIR[0]
                        }

                        gg +=  " thence anti-clockwise along the arc of a circle radius " + aspseg[ i ].arc_dist + " NM centered at "

                        if ( (aspseg[ i ].arpt_ident == null || aspseg[ i ].arpt_ident == '') && (aspseg[ i ].nav_id == null || aspseg[ i ].nav_id == '') ) {    
                            this.hsl = GetCord(aspseg[ i ].arc_lat, aspseg[ i ].arc_long)
                            if ( aspseg[ i ].remarks == null || aspseg[ i ].remarks == '') {
                                gg +=  " (" + this.hsl.WGS[1] + " " +this.hsl.WGS[0] + ") to"
                            }else {
                                gg +=  aspseg[ i ].remarks.toUpperCase() + " ("  + this.hsl.WGS[1] + " " +this.hsl.WGS[0] + ") to"
                            }
                        } else {
                            if (aspseg[ i ].navaid == null || aspseg[ i ].navaid == '') { 
                                this.hsl = GetCord(aspseg[ i ].airport[0].geom.coordinates[1], aspseg[ i ].airport[0].geom.coordinates[0])
                                garpt =" ARP " + " (" + this.hsl.ADText[1] + " " + this.hsl.ADText[0] + ")"
                                gg += garpt + " to"
                            } else {
                                this.hsl = GetCord( aspseg[ i ].navaid[ 0 ].geom.coordinates[ 1 ], aspseg[ i ].navaid[ 0 ].geom.coordinates[ 0 ] )
                                gnav =aspseg[ i ].navaid[ 0 ].definition + " " + aspseg[ i ].navaid[ 0 ].nav_ident + " (" + this.hsl.WGS[1] + " " + this.hsl.WGS[0] + ")"
                                gg += gnav + " to"
                                // gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                            }
                        }
                    }
                } else if ( aspseg[ i ].shap == "R" ) {
                    
                    this.hsl = GetCord(aspseg[ i ].point1_lat, aspseg[ i ].point1_long)
                    if (asptype == "FIR" ||asptype == "UIR" ) {
                        if ( gg == "" ) {
                            gg =  this.hsl.FIR[1] + " " + this.hsl.FIR[0]
                        } else {
                            gg +=  " - " + this.hsl.FIR[1] + " " + this.hsl.FIR[0]
                        }
                        gg += " thence clockwise along the arc of a circle radius " + aspseg[ i ].arc_dist + " NM centered at "

                        if ( (aspseg[ i ].arpt_ident == null || aspseg[ i ].arpt_ident == '') && (aspseg[ i ].nav_id == null || aspseg[ i ].nav_id == '') ) { 
                            this.hsl = GetCord(aspseg[ i ].arc_lat, aspseg[ i ].arc_long)
                            if ( aspseg[ i ].remarks == null || aspseg[ i ].remarks == '') {
                                gg+=  " (" + this.hsl.WGS[1] + " " + this.hsl.WGS[0] + ") to"
                            }else {
                                gg +=  aspseg[ i ].remarks.toUpperCase() + " ("  + this.hsl.WGS[1] + " " +this.hsl.WGS[0] + ") to"
                            }
                        } else {
                            if (aspseg[ i ].navaid == null || aspseg[ i ].navaid == '') { 
                                this.hsl = GetCord(aspseg[ i ].airport[0].geom.coordinates[1], aspseg[ i ].airport[0].geom.coordinates[0])
                                garpt =" ARP " + " (" + this.hsl.ADText[1] + " " + this.hsl.ADText[0] + ")"
                                gg += garpt + " to"
                            } else {
                                this.hsl = GetCord( aspseg[ i ].navaid[ 0 ].geom.coordinates[ 1 ], aspseg[ i ].navaid[ 0 ].geom.coordinates[ 0 ] )
                                gnav =aspseg[ i ].navaid[ 0 ].definition + " " + aspseg[ i ].navaid[ 0 ].nav_ident + " (" + this.hsl.WGS[1] + " " + this.hsl.WGS[0] + ")"
                                gg += gnav + " to"
                                // gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                            }
                            // if (aspseg[ i ].arpt_ident !== null) { 
                            //     gg += await this.Centerof(aspseg[ i ].arpt_ident) + " to"
                            // } else {
                            //     gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                            // }
                        }
                    } else {
                        if ( gg == "" ) {
                            gg = this.hsl.NonFIR[1] + " " + this.hsl.NonFIR[0]
                        } else {
                            gg +=  " - " + this.hsl.NonFIR[1] + " " + this.hsl.NonFIR[0]
                        }

                        gg +=  " thence clockwise along the arc of a circle radius " + aspseg[ i ].arc_dist + " NM centered at "

                        if ( (aspseg[ i ].arpt_ident == null || aspseg[ i ].arpt_ident == '') && (aspseg[ i ].nav_id == null || aspseg[ i ].nav_id == '') ) {  
                            this.hsl = GetCord(aspseg[ i ].arc_lat, aspseg[ i ].arc_long)
                            if ( aspseg[ i ].remarks == null || aspseg[ i ].remarks == '') {
                                gg +=  " (" + this.hsl.WGS[1] + " " +this.hsl.WGS[0] + ") to"
                            }else {
                                gg +=  aspseg[ i ].remarks.toUpperCase() + " ("  + this.hsl.WGS[1] + " " +this.hsl.WGS[0] + ") to"
                            }
                        } else {
                            if (aspseg[ i ].navaid == null || aspseg[ i ].navaid == '') { 
                                this.hsl = GetCord(aspseg[ i ].airport[0].geom.coordinates[1], aspseg[ i ].airport[0].geom.coordinates[0])
                                garpt =" ARP " + " (" + this.hsl.ADText[1] + " " + this.hsl.ADText[0] + ")"
                                gg += garpt + " to"
                            } else {
                                this.hsl = GetCord( aspseg[ i ].navaid[ 0 ].geom.coordinates[ 1 ], aspseg[ i ].navaid[ 0 ].geom.coordinates[ 0 ] )
                                gnav =aspseg[ i ].navaid[ 0 ].definition + " " + aspseg[ i ].navaid[ 0 ].nav_ident + " (" + this.hsl.WGS[1] + " " + this.hsl.WGS[0] + ")"
                                gg += gnav + " to"
                                // gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                            }
                            // if (aspseg[ i ].arpt_ident !== null) { 
                            //     gg += await this.Centerof(aspseg[ i ].arpt_ident) + " to"
                            // } else {
                            //     gg += await this.Centerof(aspseg[ i ].nav_id) + " to"
                            // }
                        }
                    }
                } else if ( aspseg[ i ].shap == "G" ) {
                    if ( gen == "" ) {
                        if ( aspseg[ i ].remarks !== "" || aspseg[ i ].remarks !== null ) {
                            gen = aspseg[ i ].remarks
                            gg += " " + gen
                        }
                        // console.log('gen___ ',gen,aspseg[ i ].remarks,i,gg)
                    } else {
                        if ( aspseg[ i ].remarks == "" || aspseg[ i ].remarks == null ) {
                            // console.log( 'gen ', gen )
                        } else {
                            if ( gen !== aspseg[ i ].remarks ) {
                                gen = aspseg[ i ].remarks
                                gg += " " + gen
                                // console.log( 'gen ', gen, aspseg[ i ].remarks, i, gg )
                            }
                        }
                    }

                } else {
                    var lat
                    this.hsl = GetCord( aspseg[ i ].point1_lat, aspseg[ i ].point1_long )
                    // console.log(this.hsl)
                    if (asptype == "FIR" || asptype == "UIR") {
                        lat = this.hsl.FIR[1]
                    }else{
                        lat = this.hsl.NonFIR[1]
                    }

                    if( this.hsl.Decimal[1] == 0){
                        lat = "equator"
                    }
                    if (asptype == "FIR" ||asptype == "UIR" ) {
                        // if ( i == 0 ) {
                        if ( gg == '' ) {
                            gg = lat + " " + this.hsl.FIR[0]
                        } else {
                            gg +=  " - " + lat + " " + this.hsl.FIR[0] 
                        }
                    } else {
                        // if ( i == 0 ) {
                        if ( gg == '' ) {
                            gg = lat + " " + this.hsl.NonFIR[0]
                        } else {
                            gg +=  " - " + lat + " " + this.hsl.NonFIR[0]
                        }
                    }

                }
                // console.log('gg',gg)
                gg = gg.replace( "until - ", "until " ).replace( "to - ", "to " )
            }
            // console.log('HASIL SEG',gg)
            return gg
        }
function backtolist(){
    window.location.href="{{url('/')}}/electronicaip";

}



</script>
@endsection