@extends('layouts.app')

@section('template_title')
    Create PDF
@endsection

@section('head')
@endsection

@section('content')

<div class="col-lg-12">
    <div class="card card-bordered h-100">
        <div class="card-inner">
            <div class="panel panel-primary mt-3"></div>

            <div class="card-title-group">
                <div class="card-title">
                    <h6 class="title">Created eAIP PDF</h6>
                </div>
            </div>
            <div class="panel panel-primary mt-3">
                <div class="panel-heading">
                    <h6 id="arpttitle"></h6>
                </div>
                <form action="" method="POST" id="TheForm" target="TheWindow">
                @csrf
                <input type="hidden" name="something" value="something" />
                <input type="hidden" name="more" value="something" />
                <input type="hidden" name="other" value="something" />
                <div class="panel-body mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Header</strong>
                                <br>
                                <input type="text" class="form-control" id="header" placeholder='AIP INDONESIA' value="AIP INDONESIA">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Footer</strong>
                                <br>
                                <input type="text" class="form-control" id="footer" placeholder='Directorate General of Civil Aviation' value="Directorate General of Civil Aviation">
                            </div>
                        </div>
                        <div id="request" style="visibility: hidden">
                            <div class="col-md-3">
                                <strong>Source</strong>
                                <br>
                                <select selected="selected" class="form-control" id="source">
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Number</strong>
                                    <br>
                                    <input type="text" class="form-control" id="nr" value='XX'>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <strong>Paper Size</strong>
                                    <br>
                                    <select selected="selected" class="form-control" id="paper">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Width</strong>
                                    <br>
                                    <input type="text" class="form-control" id="width" value="160">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>High</strong>
                                    <br>
                                    <input type="text" class="form-control" id="high" value="210">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <strong>Page Number</strong>
                                <br>
                                <input type="number" class="form-control" id="pagenr" value='1'>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Line of Page</strong>
                                    <br>
                                    <input type="number" class="form-control" id="linenr" value='1'>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Attach Page</strong>
                                    <br>
                                    <input type="text" class="form-control" id="attachpg" value='A'>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <strong>Publication Date</strong>
                                    <br>
                                    <input id="pubdate" type="date" class="form-control" id="pubdate">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <strong>Effective Date</strong>
                                    <br>
                                    <input id="effdate" type="date" class="form-control" id="effdate">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>File Name</strong>
                                <br>
                                <input type="text" class="form-control" id="filenm" value="test">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Watermark</strong>
                                <br>
                                <input type="text" class="form-control" id="wtrmark" value="DRAFT">
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
                            <a onclick="generate()" id="btn_formulir" class="btn btn-dim btn-dark"><i class="icon ni ni-file-pdf"></i> Generate</a>
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
$('#request').hide();
var arpt=@json($arpt)[0];
var codaip=@json($codaip);
var airportcontent =@json($airportcontent);
var eaiplist =@json($eaiplist);
var apronlist =@json($apronlist);
var twylist =@json($twylist);
var obstacle =@json($obstacle);
var rwylist =@json($rwylist);
var rwylighting =@json($rwylighting);
var freetext =@json($freetext);
var freqlist =@json($freq);
var navarptlist=@json($navaid);
var ch =@json($channel);
function backlist(){
    history.back();
}
console.log(arpt)
$('#arpttitle').html(arpt.icao + ' - ' + arpt.city_name + '/' + arpt.arpt_name)
$("#filenm").val(arpt.icao + '_' + arpt.arpt_ident );
$("#pubdate").val(DateFormat(new Date()));
$("#effdate").val(DateFormat(new Date()));
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
                key: 'AIP AMDT',
                value: 'AIP AMDT NON AIRAC'
            }, {
                key: 'AIP SUPP',
                value: 'AIP SUPP NON AIRAC'
//             }];
// var sourcelist= [{
// key: '1',
// value: 'AIC'
// }, {
//     key: '2',
//     value: 'AIRAC AIP AMDT'
// }, {
//     key: '3',
//     value: 'AIRAC SUPP'
// }, {
//     key: '4',
//     value: 'AMDT'
// }, {
//     key: '5',
//     value: 'SUPP'
// }, {
//     key: '6',
//     value: '8th Edition'
// }, {
//     key: '7',
//     value: 'OTHER'
// }];
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
sourcelist.forEach(v=>{
    hasil='<option id="src" value="'+v.value+'">'+v.value+'</option>';
    $("#source").append(hasil);
});
$("#source").val(v.pub_type);
papersize.forEach(v=>{
    hasil='<option id="src" value="'+v.value+'">'+v.value+'</option>';
    $("#paper").append(hasil);
});
$('#btn_formulir').click(function() {
    // $('#lat').val();
    $('#TheForm').submit();
});
// function generate(something, additional, misc){
//     var f = document.getElementById('TheForm');
//     f.something.value = something;
//     f.more.value = additional;
//     f.other.value = misc;
//     window.open('', 'TheWindow');
//     f.submit();
//     // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
//     // window.open('/pdf/'+arpt.arpt_ident, 'airportcontent', params)
// }
// arpttitle
</script>
@endsection