@extends('layouts.app')

@section('template_title')
    AD 2.8
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title" id="contentitle"></h6>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs" id="tabMenu">
                <li class="nav-item">
                    <a class="nav-link active" onclick="tabclick('apron')" data-toggle="tab" href="#tabItem5"><span>Apron</span></a>
                </li>
                <li class="nav-item tab-pane{{old('tab') == 'tabItem6' ? ' active' : null}}">
                    <a class="nav-link"  onclick="tabclick('twy')" data-toggle="tab" href="#tabItem6"><span>Taxiway</span></a>
                </li>
                <li class="nav-item tab-pane{{old('tab') == 'tabItem7' ? ' active' : null}}">
                    <a class="nav-link"  onclick="tabclick('ps')" data-toggle="tab" href="#tabItem7"><span>Parking Stand</span></a>
                </li>
                <li class="nav-item tab-pane{{old('tab') == 'tabItem8' ? ' active' : null}}">
                    <a class="nav-link"  onclick="tabclick('pb')" data-toggle="tab" href="#tabItem8"><span>Pushback Procedures</span></a>
                </li>
            </ul>
            <div class="tab-content" id="mainlist" style="visibility: visible">
                <div class="tab-pane active" id="tabItem5">
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <table class="datatable-init table table-bordered table-hover" id="table-content">
                                <thead class="thead-dark">
                                    <tr>
                                        <th <button class="btn btn-dim btn-light" onclick="NewDataApron()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</button></th>
                                        <th style="text-align:center">No</th>
                                        <th style="text-align:center">Name</th>
                                        <th style="text-align:center">Surface</th>
                                        <th style="text-align:center">Strength</th>
                                        <th style="text-align:center">Dimension</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($aprontwytemp as $index => $ap)
                                    @if($ap->type=='A')
                                    <tr>
                                        <td class="tb-tnx-action">
                                            <div class="dropdown">
                                                <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-left dropdown-menu-xs">
                                                    <ul class="link-list-plain">
                                                        <li><a class="btn btn-dim btn-dark" onclick="appronedit({{ $ap->id }})"><i class='icon ni ni-edit'></i> Edit</a></li>
                                                        <li><a class="btn btn-dim btn-danger" onclick="remove({{ $ap->id }},'APRON')"><i class='icon ni ni-delete'></i> Remove</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $ap->sequence }}</td>
                                        <td>{{ $ap->name }}</td>
                                        <td>{{ $ap->surface }}</td>
                                        <td>{{ $ap->strength }} </td>
                                        <td>{{ $ap->dimension }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane"  id="tabItem6">
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <table class="datatable-init table table-bordered table-hover" id="table-content">
                                <thead class="thead-dark">
                                    <tr>
                                        <th <button v-if="show" class="btn btn-dim btn-light" onclick="NewDataTwy()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</button></th>
                                        <th style="text-align:center">No</th>
                                        <th style="text-align:center">Name</th>
                                        <th style="text-align:center">Surface</th>
                                        <th style="text-align:center">Strength</th>
                                        <th style="text-align:center">Dimension</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($aprontwytemp as $index => $ap)
                                    @if($ap->type=='B')
                                        <tr v-bind:key="twy.id">
                                            <td v-if="show" class="tb-tnx-action">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            <li><a class="btn btn-dim btn-dark" onclick="twyedit({{ $ap->id }})"><i class='icon ni ni-edit'></i> Edit</a></li>
                                                            <li><a class="btn btn-dim btn-danger" onclick="remove({{ $ap->id }},'TWY')"><i class='icon ni ni-delete'></i> Remove</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $ap->sequence }}</td>
                                            <td>{{ $ap->name }}</td>
                                            <td>{{ $ap->surface }}</td>
                                            <td>{{ $ap->strength }} </td>
                                            <td>{{ $ap->dimension }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem7">
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <table class="datatable-init table table-bordered table-hover" id="table-content">
                                <thead class="thead-dark">
                                    <tr>
                                        <th <button v-if="show" class="btn btn-dim btn-light" onclick="NewDataPs()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</button></th>
                                        <!-- <a  v-if="show" class="btn btn-dim btn-dark" v-on:click="NewData('EDIT')"><i class="fa fa-plus" align="right" aria-hidden="true"></i> Add</a> -->
                                        <th style="text-align:center">No</th>
                                        <th style="text-align:center">Apron Name</th>
                                        <th style="text-align:center">NR</th>
                                        <th style="text-align:center">Coordinates</th>
                                        <th style="text-align:center">Capacity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(!empty($parkingstandtemp))
                                    @foreach($parkingstandtemp as $index => $ap)
                                        <tr>
                                            <td v-if="show" class="tb-tnx-action">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            <li><a class="btn btn-dim btn-dark" onclick="parkingedit({{ $ap->id }})"><i class='icon ni ni-edit'></i> Edit</a></li>
                                                            <!-- <li><a class="btn btn-dim btn-light" v-on:click="NewData('EDIT', ps.sequence)"><i class='icon ni ni-plus'></i>Insert</a></li> -->
                                                            <li><a class="btn btn-dim btn-danger" onclick="remove({{ $ap->id }},'PS')"><i class='icon ni ni-delete'></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $ap->sequence }}</td>
                                            @if(!empty($ap->apron))
                                            <td>{{ $ap->apron[0]->name }}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            <td>{{ $ap->no_gate }}</td>
                                            <td>{{ $ap->gate_lat }} {{ $ap->gate_lon }} </td>
                                            <td>{{ $ap->aircraft_type }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem8">
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <table class="datatable-init table table-bordered table-hover" id="table-content">
                                <thead class="thead-dark">
                                    <tr>
                                        <th <button v-if="show" class="btn btn-dim btn-light" onclick="NewDataPb()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</button></th>
                                        <th style="text-align:center">No</th>
                                        <th style="text-align:center">Aircraft Stand</th>
                                        <th style="text-align:center">Apron</th>
                                        <th style="text-align:center">Procedures</th>
                                        <th style="text-align:center">Phraseology</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($pushbacktemp as $index => $ap)
                                    <tr>
                                        <td class="tb-tnx-action">
                                            <div class="dropdown">
                                                <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-left dropdown-menu-xs">
                                                    <ul class="link-list-plain">
                                                        <li><a class="btn btn-dim btn-dark" onclick="pbedit({{ $ap->id }})"><i class='icon ni ni-edit'></i> Edit</a></li>
                                                        <!-- <li><a class="btn btn-dim btn-light" v-on:click="NewData('EDIT', ps.sequence)"><i class='icon ni ni-plus'></i>Insert</a></li> -->
                                                        <li><a class="btn btn-dim btn-danger"onclick="remove({{ $ap->id }},'PB')"><i class='icon ni ni-delete'></i>Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $ap->sequence }}</td>
                                        <td>{{ $ap->no_gate }}</td>
                                        <td>{{ $ap->ramp_name }}</td>
                                        <td>{{ $ap->procedure }} </td>
                                        <td>{{ $ap->radio }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading mt-3" id="backid" style="visibility: visible">
                <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                &nbsp;
                <button onclick="setMapPoint()" class="btn btn-sm btn-dim btn-success"><i class="icon ni ni-map"></i> Show</button>
            </div>
        </div>
        <div class="col-md-12 mt-3" id="mainedit" style="visibility: hidden">
            <form action="aprontwy/update" method="post"  enctype="multipart/form-data" id="formAprontwy">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="apronid" id="apronid">
                    <input type="hidden" name="status" id="apronstatus">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="apronpage" value="28">
                    <input type="hidden" name="arpt_ident" id="arpt_ident">
                    <input type="hidden" name="tab" id="aprontab">
                    <input type="hidden" name="group" id="group">
                <div class="row">
                    <div class="col-md-4">
                        <strong id="jdl2"></strong>
                        <br>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="col-md-2">
                        <strong>Sequence</strong>
                        <br>
                        <input type="number" class="form-control" id="sequence" name="sequence">
                    </div>
                    <div class="col-md-2">
                        <strong id="jdl3"></strong>
                        <br>
                        <select selected="selected" class="form-control" id="surface" name="surface">
                            @foreach($surface as $index => $surf)
                                <option value="{{$surf->definition}}">{{$surf->definition}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <strong id="jdl4"></strong>
                        <br>
                        <input type="text" class="form-control" id="strength" name="strength" v-bind:style="{'color': ad28color51}" v-show="ad28color51">
                    </div>
                    <div class="col-md-2">
                        <strong id="jdl5"></strong>
                        <br>
                        <input type="text" class="form-control" id="dimension" name="dimension" v-bind:style="{'color': ad28color61}" v-show="ad28color61">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Altimeter checkpoint location and elevation</strong>
                                <br>
                                <textarea type="text" class="form-control" id="ad53" name="ad53" v-bind:style="{'color': ad28color1}" v-show="ad28color1"></textarea>
                            </div>
                            <div class="col-md-6">
                                <strong>INS checkpoints</strong>
                                <br>
                                <textarea type="text" class="form-control" id="ad156" name="ad156" v-bind:style="{'color': ad28color3}" v-show="ad28color3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <strong>VOR checkpoints</strong>
                                <br>
                                <textarea type="text" class="form-control" id="ad57" name="ad57" v-bind:style="{'color': ad28color2}" v-show="ad28color2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <strong>Remarks</strong>
                                <br>
                                <textarea type="text" class="form-control" id="ad59" name="ad59" v-bind:style="{'color': ad28color4}" v-show="ad28color4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-3" id="parkingedit" style="visibility: hidden">
            <form action="parkingstand/update" method="post"  enctype="multipart/form-data" id="formParkingstand">
            <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="psid" id="psid">
                    <input type="hidden" name="status" id="psstatus">
                    <input type="hidden" name="parkingpage" value="28">
                    <input type="hidden" name="arpt_ident_gate" id="ps_arpt_ident_gate">
                    <input type="hidden" name="tab" id="pstab" value="tabItem7">
                    <div class="col-md-4">
                        <strong id="jdlps1"></strong>
                        <br>
                        <select selected="selected" class="form-control" id="ps_apron_id"  name="apron_id">
                            @foreach($aprontwytemp as $index => $surf)
                            @if($surf->type=='A')
                                <option value="{{$surf->id}}">{{$surf->name}}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-2">
                        <strong id="jdlps2"></strong>
                        <br>
                        <input type="text" class="form-control" id="ps_no_gate" name="no_gate">
                    </div>
                    <div class="col-md-2">
                        <strong>Sequence</strong>
                        <br>
                        <input type="number" class="form-control" id="ps_sequence" name="sequence">
                    </div>
                    <div class="col-md-2">
                        <strong>Latitude</strong>
                        <br>
                        <input id="ps_gate_lat" name="gate_lat" ref="gate_lat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" placeholder="06300000S">
                    </div>
                    <div class="col-md-2">
                        <strong>Longitude</strong>
                        <br>
                        <input id="ps_gate_lon" name="gate_lon" ref="gate_lon" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('ps_gate_lat','ps_gate_lon')"  placeholder="106300000E">
                    </div>
                    <!-- <div class="col-md-2">
                        <strong>Elevation</strong>
                        <br>
                        <input id="elevation" name="elevation" ref="elevation" style="text-transform:uppercase" maxlength="10" type="number" class="form-control">
                    </div> -->
                    <div class="col-md-12">
                        <strong id="jdlps5"></strong>
                        <br>
                        <textarea type="text" class="form-control" id="ps_aircraft_type" name="aircraft_type"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-3" id="pbedit" style="visibility: hidden">
            <form action="pushback/update" method="post"  enctype="multipart/form-data" id="formPushback">
                    <input type="hidden" name="_token"  value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="pbid" id="pbid">
                    <input type="hidden" name="status" id="pbstatus">
                    <input type="hidden" name="pushbackpage" value="28">
                    <input type="hidden" name="arpt_ident_pushback" id="pb_arpt_ident_pushback">
                    <input type="hidden" name="tab" id="pbtab" value="tabItem8">
                <div class="row">
                    <div class="col-md-5">
                        <strong>Apron</strong>
                        <br>
                        <select selected="selected" class="form-control" id="pb_apron_id"  name="apron_id">
                            @foreach($aprontwytemp as $index => $surf)
                                @if($surf->type=='A')
                                <option value="{{$surf->id}}">{{$surf->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <!-- <input type="number" class="form-control" id="ad2831" v-bind:style="{'color': ad28color31}" v-show="ad28color31"> -->
                    </div>
                    <div class="col-md-5">
                        <strong>Aircraft Stand</strong>
                        <br>
                        <select class="js-example-basic-multiple selected" onchange="isiparkingstand(this)" id='pb_ac_stand'>
                        
                        </select>
                    </div>
                    <div class="col-md-2">
                        <strong>Sequence</strong>
                        <br>
                        <input type="number" class="form-control" id="pb_sequence" name="sequence">
                    </div>
                    <div class="col-md-6">
                        <strong>Aircraft Stand</strong>
                        <br>
                        <textarea type="text" onfocusout="checkisiparkingstand(this)" class="form-control" id="pb_no_gate" name="no_gate"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>Procedures</strong>
                        <br>
                        <textarea type="text" class="form-control" id="pb_procedure" name="procedure"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>Phraseology</strong>
                        <br>
                        <textarea type="text" class="form-control" id="pb_radio" name="radio"></textarea>
                    </div>
                    <div class="col-md-6">
                        <strong>Remarks</strong>
                        <br>
                        <textarea type="text" class="form-control" id="pb_remarks" name="remarks"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-2" id="buttonsave" style="visibility: hidden">
            <div class="col-md-6">
                <button onclick="backtomain()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                &nbsp;
                <button type="submit" id="btn_update" class="btn btn-sm btn-dim btn-dark"> Update</button>
            </div>
            <div class="col-md-6" align="right">
                <i style="color:red" align="right">RED Color = Data change request</i>
                <br>
                <i style="color:darkgrey" id="arptidname"></i>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$(document).ready(function () {
       
});
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show');
});
$('#mainedit').hide();
$('#parkingedit').hide();
$('#pbedit').hide();
$('#buttonsave').hide();
var arpt =@json($airport);arp=arpt[0];
var contents =@json($content);
var aprons =@json($aprontwy);
var ps =@json($parkingstand);
var pb =@json($pushback);
var apronstemp =@json($aprontwytemp);apronstempidx=[];
var pstemp =@json($parkingstandtemp);pstempidx=[];
// console.log(pstemp)
var pbtemp =@json($pushbacktemp);pbtempidx=[];
var isApron=true;isTwy=false;isPs=false;isPb=false;psget=[],aprontwyget=[];isigate='';
var fieldapron=['arpt_ident', 'name', 'dimension', 'surface','strength','type','group','sequence'];
var fieldps=['arpt_ident_gate', 'no_gate', 'gate_lat', 'gate_lon','aircraft_type','sequence', 'apron_id'];
var fieldpb=['arpt_ident_pushback', 'no_gate', 'procedure', 'radio', 'sequence','remarks']
// console.log(apronstemp);
var ad2811='Apron'
var editform=false;editps=false;editpb=false;
var ttl=arp.icao + ' AD 2.8 APRONS, TAXIWAYS AND CHECK LOCATIONS/POSITIONS DATA';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);


// console.log(pbtemp);
$('#btn_update').click(function() {
    // var vol =$('#formAprontwy:visible').length;
    var checkrwy=false;
    // console.log(vol,$("#formParkingstand").is(":visible"),$("#formAprontwy").is(":visible"))
    if ($("#formParkingstand").is(":visible")){
        checkrwy=false;
        if ($("#psstatus").val()=='N'){
            checkrwy =checknewdata(fieldps,'ps');
        }else{
            checkrwy =checkupdatedata(fieldps,pstempidx,'ps');
        }
        console.log("#psstatus",checkrwy)
        if  (checkrwy==true){
            $('#formParkingstand').submit();
        }
        // alert("The paragraph  is visible.");
    } else if ($("#formAprontwy").is(":visible")){
        checkrwy=false;
        if ($("#apronstatus").val()=='N'){
            checkrwy =checknewdata(fieldapron);
        }else{
            checkrwy =checkupdatedata(fieldapron,apronstempidx);
        }
        console.log("#apronstatus",checkrwy)
        if  (checkrwy==true){
            $('#formAprontwy').submit();
        }
    } else if ($("#formPushback").is(":visible")){
        checkrwy=false;
        if ($("#pbstatus").val()=='N'){
            checkrwy =checknewdata(fieldpb);
        }else{
            checkrwy =checkupdatedata(fieldpb,pbtempidx,'pb');
            
        }
        console.log("#formPushback",checkrwy)
        if  (checkrwy==true){
            $('#formPushback').submit();
        }
    }
    
});


function setMapPoint() {
    if (isApron==true){
        this.url = '/map.php?table=apron&id=' + arp.arpt_ident
    }
    if (isTwy==true){
        this.url = '/map.php?table=twy&id=' +arp.arpt_ident
    }
    if (isPs==true){
        this.url = '/map.php?table=parkingstand&id=' + arp.arpt_ident
    }
    

    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open(this.url, 'Set Latitude and Longitude', params)
}

var fields = [53, 57, 156, 59];
var values = ["ad53", "ad57", "ad156", "ad59"];
for (let i=0;i<fields.length;i++){
    idx= contents.findIndex( x => x.category_id === fields[i] );
    var sts='U'
    if (idx==-1){
        var isi='NIL';
    }else{
        var isi =checkisicontain(contents[idx].content);
        sts=contents[idx].status;
    }
    
    // console.log(sts,isi,contents[idx]);
    isidata(values[i],sts)
    $("#" + values[i] ).val(isi);
    
}
function remove(id,tbl){
    console.log(id,tbl)
   
    Swal.fire({
        title: 'Deleted',
        text: "The data status will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'
           
    }).then((result) => {
        if (result.value) {
            var rawid='';sql='';
            switch (tbl) {
                case 'APRON':
                    dtsrcraw={
                        _token:"{{ csrf_token() }}",
                        deleted:'1',
                        tab:'tabItem5',
                    }

                    sql='api/eaip/apron/remove/' + id;
                    
                    break;
                case 'TWY':
                    dtsrcraw={
                        _token:"{{ csrf_token() }}",
                        deleted:'1',
                        tab:'tabItem6',
                    }

                    sql='api/eaip/apron/remove/' + id;
                    
                    break;
                case 'PS':
                    dtsrcraw={
                        _token:"{{ csrf_token() }}",
                        deleted:'1',
                        tab:'tabItem7',
                    }
                    sql='api/eaip/parkingstand/remove/' + id;
                    
                break;
                case 'PB':
                    dtsrcraw={
                        _token:"{{ csrf_token() }}",
                        deleted:'1',
                        tab:'tabItem8',
                    }
                    sql='api/eaip/pushback/remove/' + id;
            }
            // console.log('Berhasil masuk YES ', "{{ URL::to('/') }}/DataRequest/save",sql, id,dtsrcraw)
            $.ajax({
                url:  sql, //'/DataRequest/save',
                type: "POST",
                data: JSON.stringify(dtsrcraw),
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
                    // window.location.href = window.location.href + '#tabItem7';
                    location.reload()//+ '#tabItem7';
                }
            });

            
        }else{
            location.reload();

        }
    })
}
function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;

}
function backtomain(){
    
    window.scroll(0,0);
    if (editform==true){
        editform=false;
        aboutvol('mainedit');
        aboutvol('mainlist');
        aboutvol('backid');
        aboutvol('buttonsave');
    }
    if (editps==true){
        editps=false;
        aboutvol('mainlist');
        aboutvol('backid');
        aboutvol('parkingedit');
        aboutvol('buttonsave');
    }
    if (editpb==true){
        editpb=false;
        aboutvol('mainlist');
        aboutvol('backid');
        aboutvol('pbedit');
        aboutvol('buttonsave');
    }

    // aboutvol('mainedit');
    // aboutvol('mainlist');
    // aboutvol('backid');
    // aboutvol('parkingedit');
}
function tabclick(data){
    isApron=false;isTwy=false;isPs=false;isPb=false;
    switch(data){
        case "apron":
            isApron=true;
            break;
        case "twy":
            isTwy=true;
            break;
        case "ps":
            isPs=true;
            break;
        case "pb":
            isPb=true;
            break;
    }
    if (editform==true){
        editform=false;
        aboutvol('mainedit');
        aboutvol('mainlist');
        aboutvol('backid');
        aboutvol('buttonsave');
    }
    if (editps==true){
        editps=false;
        aboutvol('mainlist');
        aboutvol('backid');
        aboutvol('parkingedit');
        aboutvol('buttonsave');
    }
    if (editpb==true){
        editpb=false;
        aboutvol('mainlist');
        aboutvol('backid');
        aboutvol('pbedit');
        aboutvol('buttonsave');
    }

}
function NewDataApron(){
    window.scroll(0,0);
    editform=true;
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Save')
    $("#jdl2").html('Apron Name');
    $("#jdl3").html('Surface');
    $("#jdl4").html('Strength');
    $("#jdl5").html('Dimension');
    $("#group").val('Apron');
    $("#apronstatus").val('N')
    $("#type").val('A')
    $("#aprontab").val('tabItem5')
    $("#arpt_ident").val(arp.arpt_ident)
    aboutvol('mainedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
}
function NewDataTwy(){
    window.scroll(0,0);
    editform=true;
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Save')
    $("#jdl2").html('TWY Name');
    $("#jdl3").html('Surface');
    $("#jdl4").html('Strength');
    $("#jdl5").html('Width');
    $("#group").val('Taxiway');
    $("#apronstatus").val('N')
    $("#type").val('B')
    $("#aprontab").val('tabItem6')
    $("#arpt_ident").val(arp.arpt_ident)

    aboutvol('mainedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
}
function NewDataPs(){
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Save')
    aboutvol('parkingedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
    window.scroll(0,0);
    editps=true;
    $("#jdlps1").html('Apron Name');
    $("#jdlps2").html('NR');
    $("#jdlps5").html('Capacity');
    $("#psstatus").val('N')
    $("#ps_arpt_ident_gate").val(arp.arpt_ident)
  
}
function NewDataPb(){
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Save')
    aboutvol('pbedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
    window.scroll(0,0);
    editpb=true;
    $("#pbstatus").val('N')
    $("#arpt_ident_pushback").val(arp.arpt_ident)
  
}
function parkingedit(id){
    console.log(id);
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Update')
    aboutvol('parkingedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
    editps=true;
    var idx= pstemp.findIndex( x => x.id ===id );
    var apr=pstemp[idx];
    pstempidx=apr;
    psget=apr;
    var ix= ps.findIndex( x => x.id ===id );
    var pstmp=ps[ix];
    // console.log(apr);
    window.scroll(0,0);
    $("#jdlps1").html('Apron Name');
    $("#jdlps2").html('NR');
    $("#jdlps5").html('Capacity');
    $("#psstatus").val('R')
    // $("#arpt_ident_gate").val(arp.arpt_ident)
    $("#psid").val(apr.id)
    // var fieldps=['arpt_ident_gate', 'no_gate', 'gate_lat', 'gate_lon','aircraft_type','ramp_name','elevation','sequence', 'apron_id'];
    compareisidata(fieldps,apr,pstmp,'ps');
    
}
function twyedit(id){
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Update')
    window.scroll(0,0);
    aboutvol('mainedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
   
    // console.log(id);
    var idx= apronstemp.findIndex( x => x.id ===id );
    var twytmp=apronstemp[idx];
    apronstempidx=twytmp;
    var ix= aprons.findIndex( x => x.id ===id );
    var twycurr=aprons[ix];
    aprontwyget=twytmp;
    $("#jdl1").html('Main TWY');
    $("#jdl2").html('TWY Name');
    $("#jdl3").html('Surface');
    $("#jdl4").html('Strength');
    $("#jdl5").html('Width');
    editform=true;
    $("#apronstatus").val('R')
    $("#aprontab").val('tabItem6')
    $("#arpt_ident").val(arp.arpt_ident)
    $("#apronid").val(twytmp.id)

    compareisidata(fieldapron,twytmp,twycurr);
   


}
function appronedit(id){
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Update')
    window.scroll(0,0);
    aboutvol('mainedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
    // console.log(id);
    var idx= apronstemp.findIndex( x => x.id ===id );
    var apr=apronstemp[idx];
    apronstempidx=apr;
    var ix= apronstemp.findIndex( x => x.id ===id );
    var aprcurr=aprons[idx];
    aprontwyget=apr;
    // console.log(apronstemp[ix]);
    $("#jdl1").html('Main Apron');
    $("#jdl2").html('Apron Name');
    $("#jdl3").html('Surface');
    $("#jdl4").html('Strength');
    $("#jdl5").html('Dimension');
    editform=true;
    $("#apronstatus").val('R')
    $("#aprontab").val('tabItem5')
    $("#arpt_ident").val(arp.arpt_ident)
    $("#apronid").val(apr.id)
    compareisidata(fieldapron,apr,aprcurr);


}
function pbedit(id){
    $('#btn_update').html('<i class="icon ni ni-save-fill"></i> Update')
    window.scroll(0,0);
    aboutvol('pbedit');
    aboutvol('mainlist');
    aboutvol('backid');
    aboutvol('buttonsave');
    // console.log(id,pbtemp);
    var idx= pbtemp.findIndex( x => x.id ===id );
    var apr=pbtemp[idx];
    pbtempidx=apr;
    var ix= pb.findIndex( x => x.id ===id );
    var aprcurr=pb[idx];
    editpb=true;
    $("#pbstatus").val('R')
    $("#pbid").val(apr.id)
    compareisidata(fieldpb,apr,aprcurr,'pb');
    isicomboparkingstand();

}

function isicomboparkingstand(){
    var hisip=$("#pb_no_gate").val();
    // console.log(hisip)
    $("#pb_ac_stand").empty();
    var hasil='<option value=""></option>';
    $("#pb_ac_stand").append(hasil);
    pstemp.forEach(ps=>{
        if (hisip.includes(ps.no_gate)==false){
            hasil ='<option value="'+ps.no_gate+'">'+ps.no_gate+'</option>';
            $("#pb_ac_stand").append(hasil);
        }
    })
}

function isiparkingstand(id){
    // console.log(id.value)
    var jj=$("#pb_no_gate").val().replace( /\s/g, '' );
    // console.log('JJJJ',jj)
    var gg= jj.split(",");
    gg.push(id.value)
    // gg.sort()
    gg.sort((a,b) => (a > b) ? 1 : ((b > a) ? -1 : 0));
    $("#pb_no_gate").val(gg);
    isicomboparkingstand()
}

function checkisiparkingstand(id){
    // console.log(id.value)
    var ggt=id.value.split(",");
    // console.log(ggt)
    for (let i=0;i<ggt.length;i++){
        var chk=pstemp.findIndex(x=>x.no_gate===ggt[i])
        $("#pb_no_gate").attr('style', "border-radius: 4px; border:#dbdfea 1px solid;");
        if (chk==-1){
    
            $("#pb_no_gate" ).attr( 'style', "border-radius: 5px; border:#FF0000 2px solid;" );
            Swal.fire(
                'No Data <i> '+ ggt[i]+ ' </i> parking stand with number',
                'Please check data',
                'info'
                )
        }
        // console.log(chk,ggt[i])
    }
    var jj=$("#pb_no_gate").val().replace( /\s/g, '' );
    // console.log('JJJJ',jj)
    var gg= jj.split(",");
    $("#pb_no_gate").val(gg);
    isicomboparkingstand()

}
function checkchangedata(currentdata,newdata){
    console.log('checkchangedata',currentdata,newdata)
        if (currentdata == newdata){
            return false;
        }else{
            return true;
        }
}

function checkdata(id,value,tempdata){
    var isid=checkisicontain(value);
    var sts='U';
        if (value!==tempdata){
            sts = 'R'
        }
    
    $("#" + id).val(value);
    isidata(id,sts);
}



function backtolist(){
    history.back();
}

</script>
@endsection
