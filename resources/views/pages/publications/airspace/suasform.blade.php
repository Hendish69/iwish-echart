@extends('layouts.app')

@section('template_title')
@if ($suastemp)
    @if ($suastemp[0]->suas_type=='P' || $suastemp[0]->suas_type=='R' || $suastemp[0]->suas_type=='D')
        ENR 5.1
    @else
        ENR 5.2
    @endif
    @else
        NEW DATA
    @endif
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-wrap">
        <div class="panel-heading mt-3">
            <h6 class="panel-title" id="asptitle"></h6>
        </div>
        <div class="panel-body mt-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tabItem1"><span>Information</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabItem3"><span>Boundary</span></a>
                </li>
            </ul>
            <div class="tab-content tabairspace" id="tabasp">
                <div class="tab-pane active" id="tabItem1">
                    <div id="viewasp" style="visibility:visible">
                        @foreach($suastemp as $asp)
                        <div class="row col-md-12">
                            <div class="col-md-4">
                                <strong>Name</strong>
                                <br>
                                <p>{{$asp->suas_ident}}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Type</strong>
                                <br>
                                <p>{{$asp->definition}}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Sector</strong>
                                <br>
                                <p>{{$asp->suas_sector}}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Name</strong>
                                <br>
                                <p>{{$asp->suas_name}}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Lower</strong>
                                <br>
                                <p>{{$asp->lower}}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Upper</strong>
                                <br>
                                <p>{{$asp->upper}}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Call Sign</strong>
                                <br>
                                <p>{{$asp->call_sign}}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Country</strong>
                                <br>
                                <p>{{$asp->country}}</p>
                            </div>
                        </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                    &nbsp;
                                <a onclick="setMapPoint()" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Set Point</a>&nbsp;
                                <a onclick="editasp()" class="btn btn-dim btn-dark"><i class="icon ni ni-edit"></i> Edit</a>
                            </div>
                        </div>
                    </div>
                    <div id="editasp" style="visibility:hidden">
                        <form action="api/suas/save" method="post"  enctype="multipart/form-data" id="suasform">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="suas_id" id="suas_id">
                            <input type="hidden" name="status" id="status">
                        <div class="row col-md-12">
                            <div class="col-md-3">
                                <strong>Ident</strong>
                                <br>
                                <input id="suas_ident" name="suas_ident" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <strong>Type</strong>
                                <br>
                                <select class="form-control" name="suas_type" id="suas_type">
                                @foreach($cod as $l)
                                        <option  value="{{$l->id}}">{{ $l->definition }} </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <strong>Sector</strong>
                                <br>
                                <input id="suas_sector" name="suas_sector" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <strong>Name</strong>
                                <br>
                                <input id="suas_name" name="suas_name" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>Lower</strong>
                                <br>
                                <input id="lower" name="lower" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>Upper</strong>
                                <br>
                                <input id="upper" name="upper" type="text" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <strong>Call Sign</strong>
                                <br>
                                <input id="call_sign" name="call_sign" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <strong>Country</strong>
                                <br>
                                <select class="form-control" name="ctry" id="ctry">
                                    @foreach($countries as $l)
                                        <option  value="{{$l->ident}}">{{ $l->country }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <strong>Ef Times</strong>
                                <br>
                                <textarea id="eff_times" name="eff_times" type="text" class="form-control"></textarea>
                            </div>
                        </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a onclick="backtoview()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                    &nbsp;
                                <a onclick="SaveSuas()" id="btsave" class="btn btn-dim btn-dark"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem3">
                    <div class="row" id="viewsuasseg" style="visibility:visible">
                        <div class="col-md-12">
                            <table class="datatable-init table table-stripped table-bordered">
                                <thead class="thead-dark">
                                        <tr>
                                        @if (empty($suastemp))
                                            <th></th>
                                        @else
                                            @if (empty($suastemp[0]->boundary))
                                            <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewDataBoundary()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                                            @else
                                            <th></th>
                                            @endif
                                        @endif
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Shap</th>
                                            <th>Ref Point</th>
                                            <th>Arc Dist</th>
                                            <th>Arc Lat</th>
                                            <th>Arc Lon</th>
                                        </tr>
                                </thead>
                                <tbody id="suasseg">
                               
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                    &nbsp;
                            </div>
                        </div>
                    </div>
                    <div id="removesuasseg" style="visibility:hidden">
                        <form action="api/suas/segment/remove" method="post"  enctype="multipart/form-data" id="suassegremoveform">
                            <input type="hidden" name="_token" id="tokenremove" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="editorrem" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="idremove">
                            <input type="hidden" name="suas_id" id="suas_idremove">
                            <input type="hidden" name="suas_type" id="suas_typeremove">
                            </form>
                    </div>
                    <div id="editsuasseg" style="visibility:hidden">
                        <form action="api/suas/segment/save" method="post"  enctype="multipart/form-data" id="suassegform">
                            <input type="hidden" name="_token" id="tokenseg" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="editorseg" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="idseg">
                            <input type="hidden" name="suas_id" id="suas_idseg">
                            <input type="hidden" name="suas_type" id="suas_typeseg">
                            <input type="hidden" name="status" id="statusseg">
                            <input type="hidden" name="suas_seg_id" id="suas_seg_id">
                            <input type="hidden" name="latlama" id="latlama">
                            <input type="hidden" name="lonlama" id="lonlama">
                            <input type="hidden" name="arclatlama" id="arclatlama">
                            <input type="hidden" name="arclonlama" id="arclonlama">
                            <input type="hidden" name="nav_id" id="nav_id">
                            <input type="hidden" name="arpt_ident" id="arpt_ident">

                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Sequence</strong>
                                    <br>
                                    <input type="number" class="form-control" id="suas_seq" name="suas_seq">
                                </div>
                                <div class="col-md-4">
                                    <strong>Shap</strong>
                                    <br>
                                    <select onchange="shapcode()" class="form-control" id="shap" name="shap">
                                    @foreach($shap as $l)
                                            <option value="{{$l->id}}">{{$l->definition}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <strong>Latitude</strong>
                                    <br>
                                    <input id="point1_lat" name="point1_lat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" placeholder="06300000S">
                                </div>
                                <div class="col-md-3">
                                    <strong>Longitude</strong>
                                    <br>
                                    <input id="point1_long" name="point1_long" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');checkothercord();checkotherairspace()" placeholder="106300000E">
                                </div>
                                <div class="card-inner col-md-12" id="refcenter" style="visibility:hidden">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">Reference of a point</h6>
                                        </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <strong>Radius</strong>
                                            <br>
                                            <input type="number" id="arc_dist" name="arc_dist" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Ref. Point</strong>
                                            <br>
                                            <select id="refpoint" onchange="changepoint(this.id)" class="form-control">
                                                <strong>select ref point</strong>
                                                    <option value="">Select Ref Point</option>
                                                    <option value="ARPT">Airport</option>
                                                    <option value="NAV" >Navaid</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Point</strong>
                                            <br>
                                            <input id="point1" style="text-transform:uppercase" class="form-control" >
                                        </div>

                                        <div class="col-md-2">
                                            <strong>Arc Latitude</strong>
                                            <br>
                                            <input id="arc_lat" name="arc_lat" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" placeholder="06300000S">
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Arc Longitude</strong>
                                            <br>
                                            <input id="arc_long" name="arc_long" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('arc_lat','arc_long')" placeholder="106300000E">
                                        </div>
                                        <div class="col-md-12" id="search1" style="visibility: hidden">
                                            <select name="select2" id="select21" class="form-control select2">
                                        </div>
                                        <div class="col-md-12" style="visibility: hidden">
                                            <strong>ID</strong>
                                            <br>
                                            <input style="visibility: hidden" type="text" class="form-control"/>
                                        </div>
                                        <div class="col-md-12" id="search2" style="visibility: hidden">
                                            <select name="select2" id="select22" class="form-control select2">
                                        </div>
                                        <div class="col-md-6" style="visibility: hidden">
                                            <strong>ID</strong>
                                            <br>
                                            <input style="visibility: hidden" type="text" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <strong>Remarks</strong>
                                    <br>

                                    <textarea type="text" class="form-control" id="remarks" name="remarks"></textarea>

                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button onclick="backtoview()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                                    &nbsp;
                                <button id="btn_saveseg" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Update</button>
                            </div>
                        </div>
                    </div>
                    <div id="affect" class="col-md-6 mt-3" style="visibility: hidden">
                        <div class="panel-heading">
                            <h6 class="panel-title">Affect to</h6>
                        </div>
                        <table class="table table-stripped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Airspace</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody id="aspaffect">
                            </tbody>
                        </table>
                    </div>    
                </div>
                <!-- <div class="row">
                <div class="col-md-12">
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                        &nbsp;
                    <a onclick="editasp()" class="btn btn-dim btn-dark"><i class="icon ni ni-edit"></i> Edit</a>
                </div>
            </div> -->
                </div>
            </div>
            
        </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')
<script type="text/javascript">
var suas =@json($suas);
var suastemp =@json($suastemp);scurr=[];stemp=[];; affhasil=[];
var fld= ['id','suas_id','suas_ident', 'suas_sector', 'suas_name', 'suas_type', 'ctry', 'upper', 'lower', 'call_sign', 'eff_times'];

var fldseg=['suas_seg_id', 'suas_seq', 'point1_lat', 'point1_long', 'shap', 'nav_id','arpt_ident', 'arc_dist','arc_lat', 'arc_long', 'remarks'];

var sts= @json($status);
// console.log(suastemp);
$("#editasp").hide();
$("#editsuasseg").hide();$("#refcenter").hide();
$("#search1").hide();
$("#search2").hide();
$("#affect").hide();
$("#removesuasseg").hide();


if (sts=='newdata'){
    aboutvol("viewasp")
    aboutvol("editasp")
    $("#btsave").html('<i class="icon ni ni-save-fill"></i> Save')
    $("#status").val('N');
    $("#ctry").val('ID');
    $('#asptitle').html('New Data');
}else{
    $('#asptitle').html(suastemp[0].suas_ident+ ' ' +  suastemp[0].definition + ' information');
    suastemp[0].boundary.forEach(a=>{
        // console.log(a);
        hasil = '<tr class="nk-tb-item">'+
        '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                        '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-primary" id="'+ a.id +'" onclick="editsegment(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                            '<a class="btn btn-dim btn-info" id="'+ a.id +'" onclick="insert(this.id)"><i class="icon ni ni-plus"></i> Insert</a>'+
                            '<a class="btn btn-dim btn-danger" id="'+ a.id +'" onclick="remove(this.id)"> <i class="icon ni ni-delete-fill"></i> Delete</a>'+
                        '</ul>'+
                    '</div>'+
            '</div>'+
        '</td>'+
        '<td>' + a.point1_lat + '</td><td>' + a.point1_long + '</td><td>' + a.shap + '</td>';
        if (a.airport.length > 0){
            hasil +='<td>' + a.airport[0].icao + ' '+ a.airport[0].arpt_name + '</td><td>' + a.arc_dist + '</td><td>' + a.arc_lat + '</td><td>' + a.arc_long + '</td></tr>';
        }else if (a.navaid.length > 0){
            hasil +='<td>' + a.navaid[0].nav_ident + ' '+ a.navaid[0].definition + '</td><td>' + a.arc_dist + '</td><td>' + a.arc_lat + '</td><td>' + a.arc_long + '</td></tr>';
        }else{
            hasil +='<td></td><td></td><td></td><td></td></tr>';
        }
        $("#suasseg").append(hasil);
    })

}
// console.log(sts)
function editsegment(id){
    // console.log(id)
    aboutvol("viewsuasseg")
    aboutvol("editsuasseg")
    $("#statusseg").val('R');
    $("#suas_idseg").val(suastemp[0].suas_id);
    $("#idseg").val(id);
    $("#suas_typeseg").val(suastemp[0].suas_type);
    var ix = suastemp[0].boundary.findIndex(x=>x.id==Number(id))
    stemp=suastemp[0].boundary[ix];
    var idx = suas[0].boundary.findIndex(x=>x.id==Number(id))
    // console.log(stemp)
    if (idx !== -1){
        scurr=suas[0].boundary[idx];
    }
    if (stemp.nav_id !== null && stemp.nav_id !== 'NIL'){
        if (stemp.navaid.length > 0){
            $("#nav_id").val(stemp.navaid[0].nav_id)
            $("#point1").val(stemp.navaid[0].nav_ident + ' '+ stemp.navaid[0].definition)

        }
    }

    if (stemp.arpt_ident !== null && stemp.arpt_ident !== 'NIL'){
        if (stemp.airport.length > 0){
            $("#arpt_ident").val(stemp.airport[0].arpt_ident)
            $("#point1").val(stemp.airport[0].icao)
        }
    }
    
    latlama=stemp.point1_lat;
    lonlama=stemp.point1_long;
    $("#latlama").val(latlama);
    $("#lonlama").val(lonlama);
    $("#arclatlama").val(stemp.arc_lat);
    $("#arclonlama").val(stemp.arc_long);
    compareisidata(fldseg,stemp,scurr);
    shapcode()
    
}
function NewDataBoundary(){
    aboutvol("viewsuasseg")
    aboutvol("editsuasseg")
    $("#statusseg").val('N');
    //BDRY_SUA_MATANG_00010
    var sseq=numeral(10).format('000000')
    var bdryid='BDRY_' + suastemp[0].suas_id + '_' +sseq;
    $("#suas_seg_id").val(bdryid);
    $("#suas_seq").val(10);
    $("#suas_idseg").val(suastemp[0].suas_id);
    $("#suas_typeseg").val(suastemp[0].suas_type);
   
    shapcode()
}
function insert(id){
    // console.log(id)
    aboutvol("viewsuasseg")
    aboutvol("editsuasseg")
    $("#statusseg").val('N');
    var ix = suastemp[0].boundary.findIndex(x=>x.id==Number(id))
    stemp=suastemp[0].boundary[ix];
    //BDRY_SUA_MATANG_00010
    var sseq=numeral(stemp.suas_seq+1).format('000000')
    var bdryid='BDRY_' + suastemp[0].suas_id + '_' +sseq;
    $("#suas_seg_id").val(bdryid);
    $("#suas_seq").val(stemp.suas_seq+1);
    $("#suas_idseg").val(suastemp[0].suas_id);
    $("#suas_typeseg").val(suastemp[0].suas_type);
   
    shapcode()
    
}
function remove(id){
   
    $("#idremove").val(id);
    $("#suas_idremove").val(suastemp[0].suas_id);
    $("#suas_typeremove").val(suastemp[0].suas_type);
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $("#suassegremoveform").submit();
        }
    })
}
$('#btn_saveseg').click(function() {
//     $('#formulir').submit();
// });
// function updateseg(){
    var checkrwy=false;
    if  ($("#statusseg").val()=='N'){
        var fldsnew=shapcode()
        checkrwy =checknewdata(fldsnew);
        settonullinput(fldsnew);
    }else if ($("#statusseg").val()=='R'){
        var fldup=['suas_seg_id', 'point1_lat', 'point1_long', 'shap', 'nav_id','arpt_ident', 'arc_dist','arc_lat', 'arc_long', 'remarks'];
        checkrwy =checkupdatedata(fldup,stemp);
        settonullinput(fldup);
    };
    if (checkrwy==true ){
        $("#suassegform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        // backtolist();
    }

})

function SaveSuas(){
    console.log('update')
    var checkrwy=false;
    if  ($("#status").val()=='N'){
        var fldnew= ['suas_ident', 'suas_name', 'suas_type', 'ctry', 'upper', 'lower', 'call_sign'];
        checkrwy =checknewdata(fldnew);
    
    }else if ($("#status").val()=='R'){
        var fldUp=[
            'suas_ident', 'suas_sector', 'suas_name', 'suas_type', 'ctry', 'upper', 'lower', 'call_sign', 'eff_times']
        checkrwy =checkupdatedata(fldUp,stemp);

        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#suasform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        backtolist();
    }
   
}
function backtoview(){
    if (sts=='newdata'){
        backtolist();
    }else{
        
        if ($("#affect").is(':visible')==true){
            aboutvol('affect');
        }
        if ($("#editsuasseg").is(':visible')==true){
            aboutvol('viewsuasseg');
            aboutvol('editsuasseg');
        }
       
        if ($("#editasp").is(':visible')==true){
            aboutvol('viewasp');
            aboutvol('editasp');
        }
    }
    
}
function backtolist(){
    history.back()
}
function setMapPoint(){
    if (suastemp.length>0){
        showdetail(suastemp[0].suas_id+'$suas');
    }
}
function editasp(id){
    console.log('AspChecked', $('.active.tab-pane')[0].id);
    if($('.active.tab-pane')[0].id == 'tabItem4'){
        alert("Frequency");
    } else {
        $("#btsave").html('<i class="icon ni ni-save-fill"></i> Update')
        aboutvol("viewasp")
        aboutvol("editasp")
        $("#status").val('R');
    if (suastemp.length>0){
        stemp=suastemp[0];
    }
    if (suas.length >0){
        scurr=suas[0];
    }
    compareisidata(fld,stemp,scurr);
    }

    
}
function shapcode(){
    if ($("#refcenter").is(':visible')==true){
            aboutvol('refcenter');
    }

    var fldsnew='';

    $("#point1_lat").prop('disabled', false);
    $("#point1_long").prop('disabled', false);
    var key=$("#shap").val()
    // console.log(key)
    switch (key) {
        case "C":
            aboutvol('refcenter');
            $("#point1_lat").prop('disabled', true);
            $("#point1_long").prop('disabled', true);

            fldsnew=['suas_seg_id', 'suas_seq','shap', 'arc_dist','arc_lat', 'arc_long'];

            break;
        case "L":
        case "R":
            aboutvol('refcenter');
            fldsnew=['suas_seg_id', 'suas_seq','shap', 'point1_lat', 'point1_long', 'arc_dist','arc_lat', 'arc_long'];
            break;
    
        default:
        fldsnew=['suas_seg_id', 'suas_seq','shap', 'point1_lat', 'point1_long'];
            break;
    }
    return fldsnew;
}
function changepoint(id){
    
    var refsearch=$("#" + id).val();
    console.log(refsearch)
    if (refsearch=='NAV'){
        if ($("#search2").is(':visible')==true){
            aboutvol('search2');
        }
        
        aboutvol('search1');
    }else{
        if ($("#search1").is(':visible')==true){
            aboutvol('search1');
        }
        console.log('search2')
        aboutvol('search2');
    }
// console.log(refsearch,referensi)
if (refsearch=='NAV'){
    $('.select2').select2({
        placeholder: 'select navaid ...',
        minimumInputLength: 1,
        ajax: {
            url: 'api/navaid/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                    return {
                        q: params.term.toUpperCase()
                        //tambahkan parameter lainnya di sini jika ada
                    }
            },
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                            return {
                                text:  item.nav_ident + ' ' + item.definition,
                                geom:item.geom,
                                id: item.nav_id
                            }
                        })
                };
            },
            cache: true
        },
        templateSelection: function (selection) {
            var result = selection.text;
            return result;
        },
        
    }).on("select2:select", function(e) {
            $("#nav_id").val(e.params.data.id);
            $('#point1').val(e.params.data.text);
            crd1=SetCoordinatebyGeom(e.params.data.geom);
            $("#arc_lat").val(crd1.Database[1])
            $("#arc_long").val(crd1.Database[0])
            if ($("#search2").is(':visible')==true){
                aboutvol('search2');
            }
            if ($("#search1").is(':visible')==true){
                aboutvol('search1');
            }
        
    });
}else if (refsearch=='ARPT'){
        $('.select2').select2({
        placeholder: 'select airport ...',
        minimumInputLength: 3,
        ajax: {
            url: 'api/airport/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                    return {
                        q: params.term.toUpperCase()
                        //tambahkan parameter lainnya di sini jika ada
                    }
            },
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                            return {
                                text:  item.icao + ' ' +  item.arpt_name,
                                icao:  item.icao,
                                geom:item.geom,
                                id: item.arpt_ident
                            }
                        })
                };
            },
            cache: true
        },
        templateSelection: function (selection) {
            var result = selection.text;
            return result;
        },
    
        }).on("select2:select", function(e) {

                $("#arpt_ident").val(e.params.data.id);
                $('#point1').val(e.params.data.icao);
                crd1=SetCoordinatebyGeom(e.params.data.geom);
                console.log(crd1.Database[1])
                $("#arc_lat").val(crd1.Database[1])
                $("#arc_long").val(crd1.Database[0])
                // crd2=SetCoordinatebyGeom(pnt2geom);
                if ($("#search2").is(':visible')==true){
                    aboutvol('search2');
                }
                if ($("#search1").is(':visible')==true){
                    aboutvol('search1');
                }
           
        });
    }
}
function checkothercord(){
    var shp=$("#shap").val()
    var seq=$("#suas_seq").val()
    // console.log(shp)
    if (shp !=='C' || shp !=='E'){
        var lat =$("#point1_lat").val();
        var lon =$("#point1_long").val();
        var ix = suastemp[0].boundary.findIndex(x=>x.point1_long==lon && x.point1_lat==lat)
        if (ix !== -1){
            var ddbl=suastemp[0].boundary[ix];
            if (Number(seq) !== ddbl.suas_seq){
                Swal.fire(
                    'Coordinate Double',
                    'The coordinates already used (seqence '+ ddbl.suas_seq + ')',
                    'warning'
                    )
            }
        }
    }
}

function checkotherairspace(){
    var lat =$("#point1_lat").val();
    var lon =$("#point1_long").val();
    var asspid= $("#suas_idseg").val();
    // console.log(latlama !==lat || lonlama !==lon)
    // console.log(latlama ,lat , lonlama ,lon)
    // console.log(asspid)
    if ($("#statusseg").val()=='R'){

        $("#aspaffect").empty();
        if (latlama !==lat || lonlama !==lon){
            Swal.fire({
            title: 'Be careful !!!',
            text: "Changes in this data will affect the surrounding area!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, it has been confirmed!'
        }).then((result) => {
            if (result.value) {
                var sql = 'api/suas/temp/list/seg';
            $.ajax({
                url: sql,
                data: {'point1_lat' : latlama,'point1_long' :  lonlama},
                type: "json",
                method: "GET",
        
                success: function (result) {
                    $.each(result.data, function (k, v) {
    
                        if (v.suas_id !== asspid){
                            if ($("#affect").is(':visible')==false){
                                    aboutvol('affect');
                            }
                            var t = affhasil.findIndex(x=>x.suas_id===v.suas_id)
                            if (t==-1){
                                var hsl= '<tr><td>' + v.suas_ident + ' - ' + v.suas_name +'</td><td>' + v.definition + '</td></tr>'
                                $("#aspaffect").append(hsl)
                            }
                            affhasil.push(v)
       
                        }
                            
                    })
                }
            })
    
            }
        })
    
            // plotarea(lat,lon,latlama,lonlama)
           
        }

    }
}
function removelass(id){
console.log(id);
dtsrcraw={
        _token:"{{ csrf_token() }}",
        deleted:1,
        editor:"{{ Auth::user()->id }}",
    }
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: 'api/airspace/class/remove/' + id,
                data: JSON.stringify(dtsrcraw),
                success: response => {
                    
                    Swal.fire(
                        'Deleted!',
                        'Your data has been deleted.',
                        'success'
                        )
                        location.reload();
                        // this.loadNavaidList(this.volradio)
                }
            })
            
        }
    })
}
</script>
@endsection