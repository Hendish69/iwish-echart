@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
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
                    <div class="nk-content-body" id="enrtable"></div>
                </div>
                <div class="tab-pane  active" id="tabItem2">
                    <div id="iframe-wrapper">

                    </div>
                </div>

            </div>
        </div>
    </div>
        <!-- <div class="nk-content-inner">
        <a class="btn btn-dim btn-secondary mt-2" onclick="backtolist()"><i class="icon ni ni-reply-fill"></i> Back</a>
            <div class="nk-content-body" id="enrtable"></div>
        </div> -->
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">

var enr=@json($enr);
var cod=@json($codeaip);
var chart=@json($chart);
var subid=@json($subid);

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
// console.log(enr);
// console.log(cod);
// console.log(subid);
let idx = cod.findIndex(x => x.id===Number(subid));
// console.log(cod[idx]);
var pathdetail= pathpop()   + '/api/ats';
var subpart=cod[idx];
            // console.log( subid )
            var ats = [];
            enr.forEach( ( dt ) =>
            {
                $.ajax({
                    url: pathdetail,
                    data: {ctry: dt.ctry,sort:'seq_424:asc'},
                    type: "json",
                    method: "GET",

                    success: function (result) {
                        var no = 0, atscount = result.data.length,track='',dist='',uplow='',altcls='',rnp='';
                        $.each(result.data, function (k, atsdata) {
                            // console.log(atsdata);
                            this.enrdata = atsdata;
                            no++
                            if ( track == atsdata.track_out + '$' + atsdata.track_in ) {
                                this.enrdata[ 'track' ] = ''
                            }else{
                                this.enrdata[ 'track' ] = atsdata.track_out + '$' + atsdata.track_in
                            }
    
                            if ( dist == atsdata.dist  ) {
                                this.enrdata[ 'dist' ] = ''
                            }else{
                                this.enrdata[ 'dist' ] = atsdata.dist
                            }
                            
                            if ( uplow == atsdata.maa + '$' + atsdata.mfa ) {
                                this.enrdata[ 'upplow' ] = ''
                            }else{
                                this.enrdata[ 'upplow' ] = atsdata.maa + '$' + atsdata.mfa
                            }
    
                            if ( altcls == atsdata.mea_out + '$' + atsdata.seg_use ) {
                                this.enrdata[ 'altclass' ] = ''
                            }else{
                                this.enrdata[ 'altclass' ] = atsdata.mea_out + '$' + atsdata.seg_use
                            }
    
                            // if ( rnp == atsdata.rnp_type ) {
                                // this.enrdata[ 'rnp' ] = ''
                            // }else{
                                this.enrdata[ 'rnp' ] = atsdata.rnp_type
                            // }
    
                            if (atsdata.nav1.length !== 0 ) {
                                this.hsl =SetCoordinatebyGeom(atsdata.nav1[0].geom) //this.GetCord( atsdata.nav1[0].geom.coordinates[ 1 ], atsdata.nav1[0].geom.coordinates[ 0 ] );
                                // console.log(this.hsl);
                                this.enrdata[ 'lat1' ] = this.hsl.ADText[1];
                                this.enrdata[ 'lon1' ] =  this.hsl.ADText[0];
                            }else{
                                this.hsl = SetCoordinatebyGeom(atsdata.wpt1[0].geom) //this.GetCord( atsdata.wpt1[0].geom.coordinates[ 1 ], atsdata.wpt1[0].geom.coordinates[ 0 ] );
                                // console.log(this.hsl);
                                this.enrdata[ 'lat1' ] = this.hsl.ADText[1];
                                this.enrdata[ 'lon1' ] =  this.hsl.ADText[0];
                            }
    
                            if (atsdata.nav2.length !== 0 ) {
                                this.hsl =SetCoordinatebyGeom(atsdata.nav2[0].geom) // this.GetCord( atsdata.nav2[0].geom.coordinates[ 1 ], atsdata.nav2[0].geom.coordinates[ 0 ] );
                                this.enrdata[ 'lat2' ] = this.hsl.ADText[1];
                                this.enrdata[ 'lon2' ] =  this.hsl.ADText[0];
                            }else{
                                this.hsl =  SetCoordinatebyGeom(atsdata.wpt2[0].geom) //this.GetCord( atsdata.wpt2[0].geom.coordinates[ 1 ], atsdata.wpt2[0].geom.coordinates[ 0 ] );
                                this.enrdata[ 'lat2' ] = this.hsl.ADText[1];
                                this.enrdata[ 'lon2' ] = this.hsl.ADText[0];
                            }
                            // console.log( ' enr', no, atscount)
                            if ( atscount == no ) {
                                no = 0
                                this.enrdata[ 'endpoint' ] = 'yes'
                            }else{
                                this.enrdata[ 'endpoint' ] = 'no'
                            }
    
                                this.enrdata[ 'symb1' ] = getsymb( atsdata.wpt_type );
                                this.enrdata[ 'symb2' ] = getsymb( atsdata.wpt_type2 );
                                this.enrdata[ 'panahkebawah' ] =getsymb('down');
                                this.enrdata[ 'panahkeatas' ] = getsymb('up');
                            
                            
                            // this.data ['rem']= atsdata.remarks[0].remarks + '$' + atsdata.remarks[0].asp_id
                            ats.push( this.enrdata )
                            track = atsdata.track_out + '$' + atsdata.track_in;
                            dist = atsdata.dist;
                            uplow = atsdata.maa + '$' + atsdata.mfa
                            altcls = atsdata.mea_out + '$' + atsdata.seg_use
                            rnp = atsdata.rnp_type 
                        })
                        var atsident
                        this.isi='<h5 class="title mt-5" style="color:brown" align="center">ENR 3 ATS ROUTES</h5>'
                        this.isi +='<h6 class="title" style="color:brown" align="center">' + subpart.sub_id + ' ' + subpart.definition + '</h6>'
                        this.isi += tbltas(true)
                            ats.forEach( asp =>
                            {  
                                // console.log(asp)
                                if (asp.ats_ident !== atsident){
                                    this.isi +='<tr>'
                                    this.isi +='<td valign="top" colspan="10" rowspan="1">'
                                    this.isi +='<p><b>' + asp.ats_ident +'</b><br>'
                                    if (asp.ats_ident.length < 7){
        
                                        this.isi +='<b><u>' + ConverNumChart(asp.ats_ident) + '</u></b></p>'
                                    }
                                    this.isi +='</td>'
                                    this.isi +='</tr>'
                                }
                                this.isi +='<tr>'
                                this.isi +='<td align="left" valign="top" colspan="1" rowspan="1">'
                                
                                this.isi +='<img src="' + asp.symb1 +'" style="width:50px;height:10px" >'
                                this.isi +='</td>'
                                this.isi +='<td align="left" valign="top" colspan="1" rowspan="1">'
                                this.isi +='<p><u>' + asp.point_1 +'</u><br>'
                                this.isi += asp.lat1 +'<br>'
                                this.isi += asp.lon1 +'</p>'
                                this.isi +='</p>'
                                this.isi +='</td>'
                                this.isi +='<td align="left" valign="top" colspan="1" rowspan="1">'
                                this.isi +='<p align="center">'
                                if (asp.track_in =='' || asp.track_in==null){
                                    this.isi += asp.track_out +'°'
                                }else if (asp.track_out =='' || asp.track_out==null){
                                    this.isi += asp.track_in +'°'
                                }else{
                                    this.isi +='<u>' + asp.track_out +'°</u><br>'
                                    this.isi += asp.track_in + '°'
                                }
                                this.isi +='</p>'
                                this.isi +='</td>'
                                this.isi +='<td  align="center" valign="top" colspan="1" rowspan="1">'
                                this.isi +='<p>'+ asp.dist +'</p>'
                                this.isi +='</td>'
                                this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
                                var upper, lower,mea;
                                    if ( asp.maa == '' || asp.maa == null ) {
                                        upper = ''
                                    } else {
                                        if ( asp.maa.substr( 0, 2 ) == 'FL' ) {
                                            upper = 'FL ' + asp.maa.substr( 2, asp.maa.length - 2 )
                                        } else {
                                            upper = formatalt( asp.maa ) + ' ft'
                                        }
                                    }
                                    if ( asp.mfa == '' || asp.mfa == null ) {
                                        lower = ''
                                    } else {
                                        if ( asp.mfa.substr( 0, 2 ) == 'FL' ) {
                                            lower = 'FL ' + asp.mfa.substr( 2, asp.mfa.length - 2 )
                                        } else if ( asp.mfa == 'GND' || asp.mfa == 'SFC' ) {
                                            lower = 'GND/WATER'
                                        } else {
                                            lower = formatalt( asp.mfa ) + ' ft'
                                        }
                                    }
                                    if ( asp.mea_out == '' || asp.mea_out == null ) {
                                        mea = ''
                                    } else {
                                        if ( asp.mea_out.substr( 0, 2 ) == 'FL' ) {
                                            mea = 'FL ' + asp.mfa.substr( 2, asp.mea_out.length - 2 )
                                        } else if ( asp.mea_out == 'GND' || asp.mea_out == 'SFC' ) {
                                            mea = 'GND/WATER'
                                        } else {
                                            mea = formatalt( asp.mea_out ) + ' ft'
                                        }
                                    }
                                this.isi +='<p><u>'+ upper +'</u><br>'
                                this.isi += lower + '<br>'
                                this.isi +='</p>'
                                this.isi +='</td>'
                                this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
                                this.isi +='<p>'+ mea  +'<br>'
                                this.isi += asp.seg_use  +'</p>'
                                this.isi +='</td>'
                                this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
                                this.isi +='<span>'+ asp.rnp +'</span>'
                                this.isi +='</td>'
                                    //             If trko > 179 And trko < 359 Then
                                    //     PosOddEven1 = "EVEN"
                                    // Else
                                    //     PosOddEven1 = "ODD"
                                    // End If
                                if ( asp.track_out > 179 && asp.track_out < 359){
                                    this.isi +='<td colspan="1" align="center" valign="bottom" rowspan="1">'
                                    if (asp.track_in =='' || asp.track_in==null){
                                        this.isi +='<span></span>'
                                    }else{
                                        this.isi +='<img src="' + asp.panahkeatas +'" width="10" height="30">'
                                    }
                                    this.isi +='</td>'
                                    this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
                                    if (asp.track_out =='' || asp.track_out==null){
                                        this.isi +='<span></span>'
                                    }else{
                                        this.isi +='<img src="' + asp.panahkebawah +'" width="10" height="30">'
                                    }
                                    this.isi +='</td>'
                                }else{
                                    this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
                                    if (asp.track_out =='' || asp.track_out==null){
                                        this.isi +='<span></span>'
                                    }else{
                                        this.isi +='<img src="' + asp.panahkebawah +'" width="10" height="30">'
                                    }
                                    
                                    this.isi +='</td>'
                                    this.isi +='<td colspan="1" align="center" valign="bottom" rowspan="1">'
                                    if (asp.track_in =='' || asp.track_in==null){
                                        this.isi +='<span></span>'
                                    }else{
                                        this.isi +='<img src="' + asp.panahkeatas +'" width="10" height="30">'
                                    }
                                    this.isi +='</td>'
                                }
                                // this.isi +='<span>'+ asp.rnp +'</span>'
                                this.isi +='<td colspan="1" align="center" valign="top" rowspan="1">'
                                if (asp.remarks.length !== 0){
                                    this.isi +='<span>'+ asp.remarks[0].remarks +'</span>'
                                }else{
                                    this.isi +='<span></span>'
                                }
                                this.isi +='</td>'
                                this.isi +='</tr>'
                                if ( asp.endpoint == 'yes' ) {
                                    this.isi +='<tr>'
                                    this.isi +='<td align="left" valign="top" colspan="1" rowspan="1">'
                                    // img =this.getsymb( asp.wpt_type2 );
                                    this.isi +='<img src="' + asp.symb2 +'" width="35" height="10">'
                                    this.isi +='</td>'
                                    this.isi +='<td align="left" valign="top" colspan="9" rowspan="1">'
                                    this.isi +='<p><u>' + asp.point_2 +'</u><br>'
                                    this.isi += asp.lat2 +'<br>'
                                    this.isi += asp.lon2 +'</p>'
                                    this.isi +='</p>'
                                    this.isi +='</td>'
                                    this.isi +='</tr>'
                                }
                                
                                atsident = asp.ats_ident;
                            })
                            this.isi += '</tbody>'
                            this.isi += '</table>'
                            $("#enrtable").html(this.isi);
                    }
                });
                        window.scrollTo(0,0);
            } )
function getsymb(pntype){
    var hsl= "{{ URL::to('/') }}/images/Enr/";
//    console.log(hsl);
    switch(pntype){
        case '1':
            hsl +='crp_text.png';
            break;
        case '2':
            hsl +='ncrp_text.png';
            break;
        case '3':
            hsl +='m_crp_text.png';
            break;
        case '4':
            hsl+='m_ncrp_text.png';
            break;
        case "down":
            hsl +='ArrB.png';
            break;
        case "up":
            hsl +='ArrA.png';
            break;
        default:
            hsl +='crp_text.png';
            break;
    }
    // console.log(hsl);
    return hsl;//wptymbol(pntype).icon;

}
function tbltas(ifr){
    var atstable=''
    atstable = '<table class="table table-bordered" style="background-color:#f0f0f0;border-color: #999999;border-style: solid;border-width: 1px;" cellspacing="0:width: 100%">'
    if (ifr==true){

        atstable +='<colgroup>'
        atstable +='<col span="1" style="width: 7%;">'
        atstable +='<col span="1" style="width: 20%;">'
        atstable +='<col span="1" style="width: 4%;">'
        atstable +='<col span="1" style="width: 4%;">'
        atstable +='<col span="1" style="width: 12%;">'
        atstable +='<col span="1" style="width: 10%;">'
        atstable +='<col span="1" style="width: 10%;">'
        atstable +='<col span="1" style="width: 10%;">'
        atstable +='<col span="1" style="width: 10%;">'
        atstable +='<col span="1" style="width: 18%;">'
        atstable +='</colgroup>'
        atstable +='<thead>'
        atstable +='<tr align="center" valign="middle" class="colsep-1 rowsep-1">'
        atstable +='<td rowspan="2" colspan="2">Route designator<br>significant points<br>Coordinates</td>'
        // atstable +='<td rowspan="2" colspan="1"></td>'
        atstable +='<td rowspan="2" colspan="1">Track<br>True<br>(°)</td>'
        atstable +='<td rowspan="2" colspan="1">DIST<br>(NM)</td>'
        atstable +='<td rowspan="2" colspan="1">'
        atstable +='<p><u>Upper</u><br>'
        atstable +='Lower</p></td>'
        atstable +='<td rowspan="1" colspan="1">MNM FLT<br>ALT</td>'
        atstable +='<td rowspan="2" colspan="1">Lateral limits<br>(NM)</td>'
        atstable +='<td colspan="2" rowspan="1">Direction of<br>cruising levels</td>'
        atstable +='<td rowspan="2" colspan="1">Remarks Controlling unit Frequency</td>'
        atstable +='</tr>'
        atstable +='<tr align="center" valign="middle">'
        atstable +='<td colspan="1" rowspan="1">Airspace classification</td>'
        // atstable +='</tr>'
        // atstable +='<tr align="center" valign="middle">'
        atstable +='<td colspan="1" rowspan="1">Odd</td>'
        atstable +='<td colspan="1" rowspan="1">Even</td>'
        atstable +='</tr>'
        atstable +='<tr align="center">'
        atstable +='<td align="center" colspan="2" rowspan="1">1</td>'
        atstable +='<td colspan="2" align="center" rowspan="1">2</td>'
        atstable +='<td align="center" colspan="2" rowspan="1">3</td>'
        atstable +='<td align="center" colspan="1" rowspan="1">4</td>'
        atstable +='<td colspan="2" align="center" rowspan="1">5</td>'
        atstable +='<td align="center" colspan="1" rowspan="1">6</td>'
        atstable +='</tr>'
        atstable +='</thead>'
    }
    return atstable
}
function backtolist(){
    window.location.href="{{url('/')}}/electronicaip";

}




</script>
@endsection