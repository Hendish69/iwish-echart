@extends('layouts.app')

@section('template_title')
    AIXM 5.1
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
                <div id="base" class="nk-content-wrapper">
                    <div class="panel panel-default">
                        <a class="btn" onclick="showmodal()"><h6>:: Aeronautical Information Exchange Model (AIXM) ::</h6></a>
                        <div id="aixmtitle" style="visibility:hidden">
                            <div class="modal-dialog-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-footer bg-gray">
                                        <h6 class="modal-title text-black-50">:: Aeronautical Information Exchange Model (AIXM) ::</h6>
                                        <a onclick="showmodal()" class="close" data-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <div class="field field-name-field-basic-body field-type-text-long field-label-hidden">
                                            <div class="field-items">
                                                <div class="field-item even">
                                                    <h5>Aeronautical Information Exchange Model</h5>
                                                    <p>The objective of the Aeronautical Information Exchange Model (<strong>AIXM</strong>) is to enable the provision in digital format of the aeronautical information that is in the scope of Aeronautical Information Services (AIS). The AIS information/data flows that are increasingly complex and made up of interconnected systems. They involve many actors including multiple suppliers and consumers. There is also a growing need in the global Air Traffic Management (ATM) system for high data quality and for cost efficiency.</p>
                                                    <p>In order to meet the requirements of this increasingly automated environment, AIS is moving from the provision of paper products and messages to the collection and provision of digital data. AIXM supports this transition by enabling the collection, verification, dissemination and transformation of digital aeronautical data throughout the data chain, in particular in the segment that connects AIS with the next intended user.</p>
                                                    <p>The following main information areas are in the scope of AIXM:</p>
                                                        <span class="badge badge-dot badge-dot-xs badge-black">Aerodrome</span><br>
                                                        <span class="badge badge-dot badge-dot-xs badge-black">Airspace structures</span><br>
                                                        <!-- <span class="badge badge-dot badge-dot-xs badge-black">Organisations and units, including services</span><br> -->
                                                        <span class="badge badge-dot badge-dot-xs badge-black">Points and Navaids</span><br>
                                                        <!-- <span class="badge badge-dot badge-dot-xs badge-black">Procedures</span><br> -->
                                                        <span class="badge badge-dot badge-dot-xs badge-black">Routes</span><br>
                                                        <!-- <span class="badge badge-dot badge-dot-xs badge-black">Flying restrictions</span><br> -->
                                                    <p>AIXM takes advantages of established information engineering standards and supports current and future aeronautical information system requirements.</p>

                                                    <p>This web site provides complete documentation for the AIXM versions in use, including information about coding guidelines, support for implementation and links towards other relevant resources.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <span class="sub-text">&copy; 2020 IWISHIndonesia.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block-head nk-block-head-sm mt-3">
                        <form id="TheForm" method="post" action="/aixm" target="TheWindow">
                            @csrf
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <div class="form-inline">
                                        <span class="btn my-2 my-sm-0" for="xmlfile">File Name</span>
                                        <input class="form-control mr-sm-2" type="text" v-model="filename" name="xmlfile" placeholder='xml file'>
                                    </div>
                                </div>
                            </div><!-- .nk-block-between -->
                            <div class="card card-bordered mt-3">
                                <div class="card-inner-group">
                                    <div class=" row card-inner card-inner-md">
                                        <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Airport" id="Airport" value="Airport">
                                            <label class="form-check-label" for="Airport">Airport</label>
                                        </div>
                                        <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Airspace" id="Airspace" value="Airspace">
                                            <label class="form-check-label" for="Airspace">Airspace</label>
                                        </div>
                                        <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Airways" id="Airways" value="Airways">
                                            <label class="form-check-label" for="Airways">Airways</label>
                                        </div>
                                        <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Navaid" id="Navaid" value="Navaid">
                                            <label class="form-check-label" for="Navaid">Navaid</label>
                                        </div>
                                        <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Waypoint" id="Waypoint" value="Waypoint">
                                            <label class="form-check-label" for="Waypoint">Waypoint</label>
                                        </div>
                                        <!-- <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Holding" id="Holding" value="Holding">
                                            <label class="form-check-label" for="Holding">Holding</label>
                                        </div> -->
                                        <!-- <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Procedure" id="Procedure" value="Procedure">
                                            <label class="form-check-label" for="Procedure">Procedure</label>
                                        </div> -->
                                        <!-- <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Communication" id="Communication" value="Communication">
                                            <label class="form-check-label" for="Communication">Communication</label>
                                        </div> -->
                                        <!-- <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="Obstacles" id="Obstacles" value="Obstacles">
                                            <label class="form-check-label" for="Obstacles">Obstacles</label>
                                        </div> -->
                                        <div class="form-check col-md-4">
                                            <input class="form-check-input checkboxaixm" type="checkbox" name="checkall" id="checkall" value="checkall">
                                            <label class="form-check-label" for="checkall">Select All</label>
                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <a id="btn_formulir" class="btn btn-dim btn-dark"><i class="icon ni ni-file-plus"></i> Generate</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript"> 
$("#aixmtitle").hide();
var  isarpt= false,
            isnavaid= false,
            isprocedure= false,
            isairspace= false,
            iswaypoint= false,
            iscomm= false,
            isairways= false,
            isholding= false,
            isobstacle= false,
            isall= false,
            isfinish= false;
function showmodal(){
    aboutvol("aixmtitle");
}
$(".checkboxaixm").change(function() {
    if ($(this).is(':checked')) {
        switch ($(this).val()) {
            case 'Airport':
                isarpt= true;
                break;
            case 'Navaid':
                isnavaid= true;
                break;
            case 'Airspace':
                isairspace= true;
                break;
            case 'Waypoint':
                iswaypoint= true;
                break;
            case 'Airways':
                isairways= true;
                break;
            case 'checkall':
                isarpt= true;
                isnavaid= true;
                isairspace= true;
                iswaypoint= true;
                isairways= true;
                break;
            default:
                break;
        }
        console.log('true',$(this).val());
    } else {
        console.log('false');
        switch ($(this).val()) {
            case 'Airport':
                isarpt= false;
                break;
            case 'Navaid':
                isnavaid= false;
                break;
            case 'Airspace':
                isairspace= false;
                break;
            case 'Waypoint':
                iswaypoint= false;
                break;
            case 'Airways':
                isairways= false;
                break;
            case 'checkall':
                isarpt= false;
                isnavaid= false;
                isairspace= false;
                iswaypoint= false;
                isairways= false;
                break;
        
            default:
                break;
        }
    }
    // console.log($(".checkboxaixm").not(this).prop('checked', this.checked));
    // console.log($(this).val().checked);

});
$("#checkall").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
$('#btn_formulir').click(function() {
    var table = [{
        all: isall,
        airport: isarpt,
        navaid: isnavaid,
        procedure: isprocedure,
        airspace: isairspace,
        waypoint: iswaypoint,
        comm: iscomm,
        airways: isairways,
        holding: isholding,
        obstacle: isobstacle
    }]
    var f = document.getElementById('TheForm');
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
        f.Airport.value = isarpt,
        f.Navaid.value = isnavaid,
        // f.Procedure.value = isprocedure,
        f.Airspace.value = isairspace,
        f.Waypoint.value = iswaypoint,
        // f.Comm.value = iscomm,
        f.Airways.value = isairways,
        // f.Holding.value = isholding,
        // f.Obstacle.value = isobstacle
    // console.log(something,additional,misc)
    window.open('', 'TheWindow',params);
    f.submit();
    // $('#lat').val();
    // $('#TheForm').submit();
});
function generate() {
            
    var table = [{
        all: isall,
        airport: isarpt,
        navaid: isnavaid,
        procedure: isprocedure,
        airspace: isairspace,
        waypoint: iswaypoint,
        comm: iscomm,
        airways: isairways,
        holding: isholding,
        obstacle: isobstacle
    }]
    console.log(table);
    // if (this.filename == '') {
    //     Swal.fire(
    //         'Data Incomplete!',
    //         'Please entry Country and Output file',
    //         'info'
    //     )
    // } else {
    //     isfinish=true
        // generate(table,'ID',this.filename + '.xml')
        

    // }
}
</script>
@endsection