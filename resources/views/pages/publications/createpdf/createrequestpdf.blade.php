@extends('layouts.app')

@section('template_title')
    Create PDF
@endsection

@section('head')
@endsection

@section('content')
<div class="col-lg-12">
    <div class="card card-bordered h-100">
        <div class="card-inner" id="listreq" style="visibility:visible">
            <div class="card-title-group">
                <div class="card-title">
                    <h6 class="title">Created eAIP PDF</h6>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark" align="center">
                            <tr>
                                <th>No</th>
                                <th>AIP Sub</th>
                                <th>Name</th>
                                <th>Request Date</th>
                            </tr>
                        </thead>
                        <tbody id="arptlist">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-inner" id="genid" style="visibility:hidden">
            <div class="card-title-group">
                <div class="card-title">
                    <h6 class="title">Created eAIP PDF</h6>
                </div>
            </div>
            <div class="panel panel-primary mt-3">
                <div class="panel-heading">
                    <h6 id="arpttitle"></h6>
                </div>
                <form id="TheForm" method="post" action="/pdf" target="TheWindow">
                    @csrf
                    <input type="hidden" name="arptid" id="arptid" />
                    <input type="hidden" name="eaipdata" id="eaipdata"/>
                    <input type="hidden" name="table" id="table"/>
                <div class="panel-body mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Header</strong>
                                <br>
                                <input type="text" class="form-control" id="header" name="header" placeholder='AIP INDONESIA' value="AIP INDONESIA">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Footer</strong>
                                <br>
                                <input type="text" class="form-control" id="footer" name="footer" placeholder='Directorate General of Civil Aviation' value="Directorate General of Civil Aviation">
                            </div>
                        </div>
                        <div class="row col-md-12" id="request" style="visibility: hidden">
                            <div class="col-md-4">
                                <strong>Source</strong>
                                <br>
                                <select selected="selected" class="form-control" id="source" name="source">
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Number</strong>
                                    <br>
                                    <input type="text" class="form-control" id="nr" name="nr" value='XX'>
                                </div>
                            </div>
                            <div class="row" id="hidedulu" style="visibility: hidden">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <strong>Paper Size</strong>
                                    <br>
                                    <select selected="selected" class="form-control" id="paper" name="paper">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Width</strong>
                                    <br>
                                    <input type="text" class="form-control" id="width" name="width" value="160">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>High</strong>
                                    <br>
                                    <input type="text" class="form-control" id="high" name="high" value="210">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <strong>Page Number</strong>
                                <br>
                                <input type="number" class="form-control" id="pagenr" name="pagenr" value='1'>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Line of Page</strong>
                                    <br>
                                    <input type="number" class="form-control" id="linenr" name="linenr" value='1'>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Attach Page</strong>
                                    <br>
                                    <input type="text" class="form-control" id="attachpg" name="attachpg" value='A'>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <strong>Publication Date</strong>
                                    <br>
                                    <input id="pubdate" type="date" class="form-control" id="pubdate" name="pubdate">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <strong>Effective Date</strong>
                                    <br>
                                    <input id="effdate" type="date" class="form-control" id="effdate" name="effdate">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>File Name</strong>
                                <br>
                                <input type="text" class="form-control" id="filenm" name="filenm" value="test">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Watermark</strong>
                                <br>
                                <input type="text" class="form-control" id="wtrmark" name="wtrmark" value="D R A F T">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        &nbsp;
                        <div class="col-md-12">
                            &nbsp;
                            <a onclick="backlist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                            &nbsp;
                            &nbsp;
                            <a id="btn_formulir" class="btn btn-dim btn-dark"><i class="icon ni ni-file-pdf"></i> Generate</a>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- .col -->
@endsection
@section('footer_scripts')

<script type="text/javascript">
$('#genid').hide();
$('#request').hide();
$('#hidedulu').hide();


var arp =@json($request);arpt='';
var menu=@json($menu);
var type =@json($type);
var roles="{{Auth::user()->roles[0]->id}}";
var piaid= null
if (roles=="18" || roles=="19"){
    if ("{{Auth::user()->pia_id}}"==''){
        var piaid= null
    }else{
        var piaid= JSON.parse("{{Auth::user()->pia_id}}");
    }
}
console.log(type,piaid,roles);
// var piaid=JSON.parse("{{Auth::user()->pia_id}}");
var no=0;
if (arp){

    if (arp.length > 0){
        arp.sort( ( a, b ) => ( a.tablename > b.tablename ) ? -1 : ( ( b.tablename > a.tablename ) ? 1 : 0 ) );
        arp.forEach(a=>{
            // console.log(a);
            if (a.tablename=='arpt' || a.tablename=='GEN' || a.tablename=='ENR')
            // if (type=='publication' || piaid==null){
                var abb=true;
                if (type=='publication'){
                    abb=(a.status_raw == '100');
                    aboutvol('request');
                    // console.log($('#request').is(':hidden'));
                    if($('#request').is(':hidden')==true){
                        aboutvol('request');
                    }
                }else{
                    if($('#request').is(':visible')==true){
                        $('#request').hide();
                    }
                    abb=false;
                    if (a.airport.length > 0 && a.tablename=='arpt'){
                        
                        if ( a.airport[0].auth.length > 0){
                            if (a.airport[0].auth[0].id==piaid || roles=="20" || roles =="1"){
                                // console.log(a.airport[0].auth[0].id,piaid)
                                abb=(a.status_raw !== '100');
                            }else{
                                abb=false;
                            }
                        }else{
                            if (roles=='20' || roles =="1"){
                                abb=(a.status_raw !== '100');
                            }else{
                                abb=false;
                            }
                        }
                    }else{
                        if (roles=='24' || roles =="1"){
                                abb=(a.status_raw !== '100');
                            }else{
                                abb=false;
                            }
                    }
                    
                }
            
                if (abb){
                    no++;pubid=''
                    if (a.tablename=='arpt'){
                        icao='AD';
                        arptname=a.airport[0].icao + ' ' + a.airport[0].arpt_name;
                        pubid=a.rawdata_id;
                    }else{
                        icao=a.tablename;
                        let xx= menu.findIndex(x => x.sub_id===a.fieldid);
                        arptname=a.fieldid + ' ' + menu[xx].definition;
                        // console.log(menu[xx])
                        pubid=a.rawdata_id;
                    }
                    hasil = '<tr class="nk-tb-item"><td style="cursor:pointer" id='+pubid+' onclick="Createpdf(this.id)">'+no+'</td><td style="cursor:pointer" id='+pubid+' onclick="Createpdf(this.id)">' + icao + '</td><td style="cursor:pointer" id='+pubid+' onclick="Createpdf(this.id)">' + arptname + '</td><td style="cursor:pointer" id='+pubid+' onclick="Createpdf(this.id)">' + DateFormat(new Date(a.create_date))   + '</td></tr>'
                    
                    $("#arptlist").append(hasil);
        
                }
            // }
        })
    }
}

function Createpdf(id){
    // console.log('IDDD',id)
    sourcelist.forEach(v=>{
        hasil='<option value="'+v.key+'">'+v.value+'</option>';
        $("#source").append(hasil);
    // window.scrollTo(0,0);
    });

    papersize.forEach(v=>{
        hasil='<option value="'+v.value+'">'+v.value+'</option>';
        $("#paper").append(hasil);
    });

    aboutvol("genid");
    aboutvol("listreq");
    var ttl='';flnm='';raw=[];

    let ix = arp.findIndex(x => x.rawdata_id===Number(id));
    // console.log(ix,arp[ix],menu)
    raw=arp[ix];

    if (raw.tablename=='arpt'){
        arpt=raw.airport[0];
        ttl=arpt.icao + ' - ' + arpt.city_name + '/' + arpt.arpt_name;
        flnm=ttl //arpt.icao + '_' + arpt.arpt_ident;
        $("#arptid").val(arpt.arpt_ident);
    }else{
        let xx= menu.findIndex(x => x.sub_id===arp[ix].fieldid);
        ttl=menu[xx].sub_id + ' ' + menu[xx].definition;
        // let ix = arp.findIndex(x => x.fieldid===menu[xx].sub_id);
        flnm=ttl;
        $("#arptid").val(menu[xx].id);
    }
    // console.log(raw)
    // if (Number(id)){
    //     // console.log(id,'YEEEEEEE')
    //     let xx= menu.findIndex(x => x.id===Number(id));
    //     // console.log(menu[xx])
    //     ttl=menu[xx].sub_id + ' ' + menu[xx].definition;
    //     let ix = arp.findIndex(x => x.fieldid===menu[xx].sub_id);
    //     flnm=ttl;
    //     raw=arp[ix];
    //     $("#arptid").val(arpt.arpt_ident);
    // }else{
    //     let ix = arp.findIndex(x => x.fieldid===id);
    //     // console.log(arp[ix])
    //     arpt=arp[ix].airport[0];
    //     raw=arp[ix];
    //     ttl=arpt.icao + ' - ' + arpt.city_name + '/' + arpt.arpt_name;
    //     flnm=arpt.icao + '_' + arpt.arpt_ident;
       
    // }
   

    $("#table").val(raw.tablename);
    // $("#arptid").val(id);
    $("#eaipdata").val(type);
    $('#arpttitle').html(ttl)
    $("#filenm").val(flnm);
    $("#pubdate").val(DateFormat(new Date()));
    $("#effdate").val(DateFormat(new Date()));
    // console.log(raw.pub_type,type,raw.nr);
    var muncul=false;
    if($('#request').is(':visible')==true){
            aboutvol('request');
    };
    if (raw.nr !== null){
        muncul=true;
        $("#pubdate").val(DateFormat(new Date(raw.pub_date)));
        $("#effdate").val(DateFormat(new Date(raw.eff_date)));
        $("#source").val(raw.pub_type);
        $("#nr").val(raw.nr);
    }
    if (muncul==true){
        if($('#request').is(':hidden')==true){
            aboutvol('request');
        };
    }

}

function backlist(){
    window.scrollTo(0,0);
    aboutvol("genid");
    aboutvol("listreq");
}

var wtrmark= 'D R A F T';ctry= 'Indonesia';customer= 'AIP INDONESIA';footer= 'Directorate General of Civil Aviation';
var papersize= [{
                key: '35',
                value: 'A5 AIP'
            }, {
                key: '34',
                value: 'A5 P'
            }, {
                key: '28',
                value: 'A5 L'
            }, {
                key: '33',
                value: 'A4 AIP'
            }, {
                key: '32',
                value: 'A4 P'
            }, {
                key: '29',
                value: 'A4 L'
            }, {
                key: '31',
                value: 'A3 P'
            }, {
                key: '30',
                value: 'A3 L'
            }, {
                key: '99',
                value: 'Custom'
            }];
var width= 160;high= 210;paper= 'A5 AIP';pagenr= 1;linenr= 1;source= 'AIRAC AIP AMDT';volradio= 'AMD';
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
    key: 'AIP AMDT NON AIRAC',
    value: 'AIP AMDT'
}, {
    key: 'AIP SUPP NON AIRAC',
    value: 'AIP SUPP'
}, {
    key: '8th Edition',
    value: '8th Edition'
}, {
    key: 'OTHER',
    value: 'OTHER'
}];
var customerlist= [{
    key: 'A',
    value: 'AIP INDONESIA'
}, {
    key: 'S',
    value: 'AIRAC AIP SUPPLEMENT XX/XX'
}, {
    key: 'O',
    value: 'OTHER'
}];
// customerlist.forEach(v=>{
//     hasil='<option id="cust" value="'+v.value+'">'+v.value+'</option>';
//     $("#customer").append(hasil);
// });
$('#btn_formulir').click(function() {
    var f = document.getElementById('TheForm');
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    // f.something.value = something;
    // f.more.value = additional;
    // f.other.value = misc;
   
    // console.log(something,additional,misc)
    window.open('', 'TheWindow',params);
    f.submit();
    // $('#lat').val();
    // $('#TheForm').submit();
});

// function generate(something, additional, misc){
//     var f = document.getElementById('TheForm');
//     let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
//     f.something.value = something;
//     f.more.value = additional;
//     f.other.value = misc;
//     // console.log(something,additional,misc)
//     window.open('/pdf/'+arpt.arpt_ident, 'TheWindow',params);
//     // f.submit();
//     // window.open('/pdf/'+arpt.arpt_ident, 'airportcontent', params)
// }

// function generate(){
//     let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
//     window.open('/pdf/'+arpt.arpt_ident, 'airportcontent', params)
// }
</script>
@endsection