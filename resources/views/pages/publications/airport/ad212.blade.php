@extends('layouts.app')

@section('template_title')
    AD 2.12
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title" id="contentitle"></h6>
        </div>
        <div class="panel-body mt-3" id="rwytable" style="visibility: visible">
            <div class="panel-heading">
                <button onclick="NewData()" class="btn btn-sm btn-dim btn-info"><i class="icon ni ni-plus"></i> Add</button>
            </div>
            <div class="row mt-1">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th style="text-align:center">No</th>
                                <th style="text-align:center">Designations RWY NR </th>
                                <th style="text-align:center">Dimensions of RWY (M)</th>
                                <th style="text-align:center">Strength (PCN)</th>
                                <th style="text-align:center">Surface</th>
                                <th style="text-align:center">Strip dimensions (M)</th>
                            </tr>
                        </thead>
                        <tbody id="rwylist">
            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-heading mt-3" style="visibility: visible">
                <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
            </div>
        </div>
        <div class="panel-body mt-3" id="rwyedit" style="visibility: hidden">
            <form action="../api/rwy/temp/save" method="post"  enctype="form-data" id="rwymain">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="rwy_id" id="rwy_id">
                <input type="hidden" name="rwy_ident" id="rwy_ident">
                <input type="hidden" name="arpt_ident" id="arpt_ident">
                <input type="hidden" name="thr_low" id="thr_low">
                <input type="hidden" name="thr_high" id="thr_high">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="mainsave" id="mainsave">
                <div class="row g-gs table-bordered mt-3">
                    <div class="col-md-4">
                        <div class="card-inner table-bordered">
                            <p class="card-title" style="text-align:center"><strong>RWY Dimension</strong></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong >Length (m)</strong>
                                    <input type="number" class="form-control" onkeyup="recalrwy()" id="length" name="length" />
                                </div>
                                <div class="col-md-6">
                                    <strong>Width (m)</strong>
                                    <input type="number" class="form-control" id="width" name="width"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-inner table-bordered">
                            <p class="card-title" style="text-align:center"><strong>STRIP Dimension</strong></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Length (m)</strong>
                                    <input type="number" class="form-control" id="strip_l" name="strip_l"/>
                                </div>
                                <div class="col-md-6">
                                    <strong>Width (m)</strong>
                                    <input type="number" class="form-control" id="strip_w" name="strip_w"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <strong>Strength (PCN)</strong>
                        <input type="text" class="form-control" id="pcn" name="pcn"/>
                        <strong>Surface</strong>
                        <select selected="selected" class="form-control" id="surface" name="surface">
                            @foreach($surface as $index => $surf)
                                <option value="{{$surf->id}}"> {{$surf->definition}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            <!-- </form> -->
            <div class="panel-body mt-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="thrlow"  data-toggle="tab" href="#tabItem1"><span>THR Low</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="thrhigh" data-toggle="tab" href="#tabItem2"><span>THR High</span></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tabItem1">
                        <!-- <form action="../api/rwy/physical/temp/save" method="post"  enctype="multipart/form-data" id="lowthr"> -->
                            <input type="hidden" name="_token" id="low_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="low_editor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="low_rwy_key" id="low_rwy_key">
                            <input type="hidden" name="low_id" id="low_id">
                            <input type="hidden" name="low_rwy_id" id="low_rwy_id">
                            <input type="hidden" name="low_arpt_ident" id="low_arpt_ident">
                            <input type="hidden" name="low_geom" id="low_geom">
                            <input type="hidden" name="low_status" id="low_status">
                            <input type="hidden" name="lowsave" id="lowsave">
                            <input type="hidden" name="low_mag_brg" id="low_mag_brg">
                            
                            <div class="row table-bordered">
                                <div class="col-md-2">
                                    <strong>THR Low</strong>
                                    <br />
                                    <input type="text" style="text-transform:uppercase" class="form-control" onkeyup="checkthrident(this.id,'low')" id="low_rwy_ident" name="low_rwy_ident"/>
                                </div>
                                <div class="col-md-2" v-if="iscalculate">
                                    <br />
                                    <button onclick="recalculate()" class="btn btn-dim btn-dark"><i class="icon ni ni-calc"></i> Recalculate</button>
                                </div>
                                <div class="row g-gs col-md-12">
                                    <div class="col-md-2">
                                        <strong>Latitude</strong>
                                        <br />
                                        <input style="text-transform:uppercase" maxlength="9" type="text" class="form-control"  id="low_lat" name="low_lat" onfocusout="CheckCoordinateFormat(this.id,'LAT');geoid(this.id)" placeholder="06300000S"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Longitude</strong>
                                        <br />
                                        <input style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('low_lat','low_lon');geoid(this.id)" id="low_lon" name="low_lon" placeholder="106300000E"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>True bearing</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_true_brg" name="low_true_brg"/>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <strong>Mag bearing</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_mag_brg" name="low_mag_brg"/>
                                    </div> -->

                                    <div class="col-md-2">
                                        <strong>THR Elev (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" onfocusout="checkhigestelev(this.id)" id="low_thr_elev" name="low_thr_elev"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>TDZ Elev (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" onfocusout="checkhigestelev(this.id)" id="low_tdz_elev" name="low_tdz_elev"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Geoid (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_geoid" name="low_geoid"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Slope Long</strong>
                                        <br />
                                        <input type="text" class="form-control" id="low_slope" name="low_slope"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Slope Trans</strong>
                                        <br />
                                        <input type="text" class="form-control" id="low_slope1" name="low_slope1"/>
                                    </div>

                                    <div class="col-md-2">
                                        <strong>SWY Len (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_swy_length"  name="low_swy_length"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>SWY Wid (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_swy_width"  name="low_swy_width"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>CWY Len (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_cwy_length" name="low_cwy_length"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>CWY Wid (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_cwy_width" name="low_cwy_width"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>RESA Len (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_resa_l" name="low_resa_l"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>RESA Wid (m)</strong>
                                        <input type="number" class="form-control" id="low_resa_w" name="low_resa_w"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Displaced (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_disp_thr_length" name="low_disp_thr_length"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Disp. Elev (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="low_disp_thr_elev" name="low_disp_thr_elev"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Displaced Lat</strong>
                                        <br />
                                        <input type="text" class="form-control" id="low_disp_lat" name="low_disp_lat" onfocusout="CheckCoordinateFormat(this.id,'LAT')"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Displaced Lon</strong>
                                        <br />
                                        <input type="text" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('low_disp_lat','low_disp_lon')" class="form-control" id="low_disp_lon" name="low_disp_lon"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>TORA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="low_tora" name="low_tora"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>TODA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="low_toda" name="low_toda"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>ASDA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="low_asda" name="low_asda"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>LDA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="low_lda" name="low_lda"/>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Remarks</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_remarks" name="low_remarks"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <br>
                                </div>
                            </div>
                        <!-- </form> -->
                    </div>
                    <div class="tab-pane"  id="tabItem2">
                        <!-- <form action="../api/rwy/physical/temp/save" method="post"  enctype="multipart/form-data" id="highthr"> -->
                            <input type="hidden" name="_token" id="high_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="high_editor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="high_rwy_key" id="high_rwy_key">
                            <input type="hidden" name="high_id" id="high_id">
                            <input type="hidden" name="high_rwy_id" id="high_rwy_id">
                            <input type="hidden" name="high_arpt_ident" id="high_arpt_ident">
                            <input type="hidden" name="high_geom" id="high_geom">
                            <input type="hidden" name="high_status" id="high_status">
                            <input type="hidden" name="highsave" id="highsave">
                             <input type="hidden" name="high_mag_brg" id="high_mag_brg">
                            <div class="row table-bordered">
                                <div class="col-md-2">
                                    <strong>THR High</strong>
                                    <br />
                                    <input type="text" style="text-transform:uppercase" class="form-control" onkeyup="checkthrident(this.id,'high')" id="high_rwy_ident" name="high_rwy_ident"/>
                                </div>
                                <div class="col-md-2" v-if="iscalculate">
                                    <br />
                                    <button onclick="recalculate()" class="btn btn-dim btn-dark"><i class="icon ni ni-calc"></i> Recalculate</button>
                                </div>
                                <div class="row g-gs col-md-12">
                                    <div class="col-md-2">
                                        <strong>Latitude</strong>
                                        <br />
                                        <input style="text-transform:uppercase" maxlength="9" type="text" class="form-control"  id="high_lat" name="high_lat" onfocusout="CheckCoordinateFormat(this.id,'LAT');geoid(this.id)" placeholder="06300000S"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Longitude</strong>
                                        <br />
                                        <input style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('high_lat','high_lon');geoid(this.id)" id="high_lon" name="high_lon" placeholder="106300000E"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>True bearing</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_true_brg" name="high_true_brg"/>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <strong>Mag bearing</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_mag_brg" name="high_mag_brg"/>
                                    </div> -->

                                    <div class="col-md-2">
                                        <strong>THR Elev (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" onfocusout="checkhigestelev(this.id)" id="high_thr_elev" name="high_thr_elev"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>TDZ Elev (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" onfocusout="checkhigestelev(this.id)" id="high_tdz_elev" name="high_tdz_elev"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Geoid (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_geoid" name="high_geoid"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Slope Long</strong>
                                        <br />
                                        <input type="text" class="form-control" id="high_slope" name="high_slope"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Slope Trans</strong>
                                        <br />
                                        <input type="text" class="form-control" id="high_slope1" name="high_slope1"/>
                                    </div>

                                    <div class="col-md-2">
                                        <strong>SWY Len (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_swy_length"  name="high_swy_length"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>SWY Wid (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_swy_width"  name="high_swy_width"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>CWY Len (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_cwy_length" name="high_cwy_length"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>CWY Wid (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_cwy_width" name="high_cwy_width"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>RESA Len (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_resa_l" name="high_resa_l"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>RESA Wid (m)</strong>
                                        <input type="number" class="form-control" id="high_resa_w" name="high_resa_w"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Displaced (m)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_disp_thr_length" name="disp_thr_length"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Disp. Elev (ft)</strong>
                                        <br />
                                        <input type="number" class="form-control" id="high_disp_thr_elev" name="high_disp_thr_elev"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Displaced Lat</strong>
                                        <br />
                                        <input type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" id="high_disp_lat" name="high_disp_lat"/>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Displaced Lon</strong>
                                        <br />
                                        <input type="text" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('high_disp_lat','high_disp_lon')" class="form-control" id="high_disp_lon" name="high_disp_lon"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>TORA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="high_tora" name="high_tora"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>TODA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="high_toda" name="high_toda"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>ASDA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="high_asda" name="high_asda"/>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>LDA (m)</strong>
                                        <br />
                                        <input type="text" class="form-control" id="high_lda" name="high_lda"/>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Remarks</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_remarks" name="high_remarks"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <br>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel-heading mt-3" id="backid" style="visibility: visible">
                <button onclick="backtomain()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                &nbsp;
                <button type="submit" onclick="savedata()" id="btn_save" class="btn btn-sm btn-dim btn-dark"></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" align="right">
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
$('#rwyedit').hide();
var arpt =@json($airport);arp=arpt[0];chart=@json($chart);elev=@json($adelev);
var no=0; thrlowtemp=[];thrhightemp=[];rtemp=[];
var rwyfield=['id','rwy_id', 'arpt_ident', 'rwy_ident', 'length', 'width', 'pcn', 'surface','strip_l', 'strip_w','thr_low', 'thr_high'];
var thrfieldthr=['id','rwy_key', 'rwy_id', 'rwy_ident', 'lat','lon', 'mag_brg', 'resa_l', 'resa_w', 'thr_elev', 'tdz_elev', 'disp_thr_length', 'swy_length', 'cwy_length','slope', 'disp_thr_elev', 'disp_lat','disp_lon','tora', 'toda', 'asda', 'lda', 'remarks','true_brg', 'cwy_width', 'swy_width', 'slope1','geoid'];
//  console.log(elev);
window.scroll(0,0);
var editform=false;editps=false;
var ttl=@json($judul);
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);
arp.runwaystemp.forEach(rw=>{
    // console.log(a);
    no++;
    hasil='<tr>'+
            '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-xs">'+
                    '<ul class="link-list-plain">'+
                        '<li><a class="btn btn-dim btn-dark" id="'+ rw.rwy_id+'" onclick="rwyedit(this.id)"><i class="icon ni ni-edit"></i> Edit</a></li>'+
                        '<li><a class="btn btn-dim btn-danger" id="'+ rw.id+'" onclick="remove(this.id)"><i class="icon ni ni-delete"></i>Delete</a></li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
            '<td>'+no+'</td>'+
            '<td>'+rw.rwy_ident+'</td>'+
            '<td>'+rw.length + ' x ' + rw.width +'</td>'+
            '<td>'+rw.pcn+'</td>'+
            '<td>'+rw.definition+'</td>'+
            '<td>'+rw.strip_l+ ' x ' + rw.strip_w +'</td>'+
        '</tr>';
        $("#rwylist").append(hasil);
        window.scroll(0,0);
})

function recalrwy(){
    var x = document.getElementById('length');
    var rwyl=Number(x.value);
    var swyl=$("#low_swy_length").val();swyh=$("#high_swy_length").val();
    var cwyl=$("#low_cwy_length").val();cwyh=$("#high_cwy_length").val();
    var displ=$("#low_disp_thr_length").val();disph=$("#high_disp_thr_length").val();
    if (displ=='' || displ==null || displ=='NIL'){
        displ=0;
    }else{
        displ=Number(displ);
    }
    if (disph=='' || disph==null || disph=='NIL'){
        disph=0;
    }else{
        disph=Number(disph);
    }
    
    var rwyll=rwyl;rwylh=rwyl;
    // console.log(swyl,swyh,cwyl,cwyh,displ,disph);
    $("#low_tora").val(rwyll)
    $("#high_tora").val(rwylh)
    $("#low_toda").val(rwyll+Number(cwyl));
    $("#high_toda").val(rwylh+Number(cwyh));
    $("#low_asda").val(rwyll+Number(swyl));
    $("#high_asda").val(rwylh+Number(swyh));
    $("#low_lda").val(rwyll - Number(displ));
    $("#high_lda").val(rwylh-Number(disph));
    // $("#length").val(rwylh+Number(disph));
}
// console.log(aprons);
// console.log(twy);
// console.log(ps);
// console.log(pb);
function checkhigestelev(id){
    var relev=[];
    var lthr=Number($("#low_thr_elev").val());
    var ltdz=Number($("#low_tdz_elev").val());
    var hthr=Number($("#high_thr_elev").val());
    var htdz=Number($("#high_tdz_elev").val());
    relev.push(lthr,ltdz,hthr,htdz)
    var mmx=Math.max(...relev);
  
       console.log(relev,mmx)


var ell=Number($("#"+id).val());
var adelev=Number(arpt[0].elev);
if (elev.length >0){
    adelev=Number(elev[0].content)
}
if (ell > adelev ){
    Swal.fire(
        'Attention!',
        'THR elevation is higher than AD elevation ( ' + adelev + 'ft ) <br>Please adjust also for the AD Elevation' ,
        'info'
    )
}
if (mmx < adelev ){
    Swal.fire(
        'Attention!',
        'AD elevation ( ' + adelev + 'ft ) is higher than THR elevation <br>Please adjust also for the AD Elevation' ,
        'info'
    )
}

// console.log(ell,elev[0],adelev)
    
}
function geoid(id){
    var ad21='';ad20 ='';idgeoid='';
    if (id.substr(0,3)=='low'){
        ad20=$("#low_lat").val();
        ad21=$("#low_lon").val();
        idgeoid="low_geoid";
    }else{
        ad20=$("#high_lat").val();
        ad21=$("#high_lon").val();
        idgeoid="high_geoid";
    }
   
    var ajaxurl = '/GeoHi/'+ad20+'/'+ad21;
    $.ajax({
        url: ajaxurl,
        type: "GET",
        success: function(data){
            // var hasil=Math.round(data) +'m / ' + Math.round((data * 3.28084))+'ft';
            var hasil= Math.round((data * 3.28084));
            $("#"+idgeoid ).val(hasil);
            // console.log(hasil);
        }
    });
}
function remove(id){
var iix=arp.runwaystemp.findIndex(x=>x.id===Number(id))
var rry=arp.runwaystemp[iix];
// console.log(rry)
    $("#status").val('D');
   
    $("#id").val(id);
    $("#arpt_ident").val(rry.arpt_ident);
    $("#low_id").val();
    $("#high_id").val();
    if (rry.physicals.length > 0){
        $("#low_status").val('D');
        $("#high_status").val('D');
        $("#low_id").val(rry.physicals[0].id);
        $("#high_id").val(rry.physicals[1].id);
    }

    Swal.fire({
        title: 'Delete Data',
        text: "The Runway will be deleted!",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'
    }).then((result) => {
        if (result.value) {
            $("#rwymain").submit()
        }else{
            location.reload();

        }
    })
}
function NewData(){
    aboutvol('rwyedit');
    aboutvol('rwytable');
    $("#status").val('N');
    $("#low_status").val('N');
    $("#high_status").val('N');
    $("#arpt_ident").val(arp.arpt_ident);
    $("#low_arpt_ident").val(arp.arpt_ident);
    $("#high_arpt_ident").val(arp.arpt_ident);
    $("#btn_save").html('<i class="icon ni ni-save"></i> Save')
    window.scroll(0,0);
}
function rwyedit(id){
    aboutvol('rwyedit');
    aboutvol('rwytable');
    $("#btn_save").html('<i class="icon ni ni-save"></i> Update')
    // console.log(arp.runwaystemp)
    var idx= arp.runwaystemp.findIndex( x => x.rwy_id ===id );
    rtemp=arp.runwaystemp[idx];
    var ix= arp.runways.findIndex( x => x.rwy_id ===id );
    var rcurr=[];
    if (ix !==-1){
        var rcurr=arp.runways[idx];
        // console.log('rcurr',rcurr)
    }
    compareisidata(rwyfield,rtemp,rcurr);
    $("#status").val('R');
    $("#low_status").val('R');
    $("#high_status").val('R');
    $("#thrlow").html('THR ' + rtemp.thr_low);
    $("#thrhigh").html('THR ' + rtemp.thr_high);
    $("#arpt_ident").val(rtemp.arpt_ident);
    $("#low_arpt_ident").val(rtemp.arpt_ident);
    $("#high_arpt_ident").val(rtemp.arpt_ident);
    //runway ident
    //thr low
    // console.log(rtemp)
    thrlowtemp=rtemp.physicals[0];
    var cord=SetCoordinatebyGeom(thrlowtemp.geom)
    thrlowtemp['lat']=cord.Database[1];
    thrlowtemp['lon']=cord.Database[0];
    if (thrlowtemp.disp_geom){
        cord=SetCoordinatebyGeom(thrlowtemp.disp_geom)
        thrlowtemp['disp_lat']=cord.Database[1];
        thrlowtemp['disp_lon']=cord.Database[0];
    }
    thrhightemp=rtemp.physicals[1];
    
   
    cord=SetCoordinatebyGeom(thrhightemp.geom)
    thrhightemp['lat']=cord.Database[1];
    thrhightemp['lon']=cord.Database[0];
    if (thrhightemp.disp_geom){
        cord=SetCoordinatebyGeom(thrhightemp.disp_geom)
        thrhightemp['disp_lat']=cord.Database[1];
        thrhightemp['disp_lon']=cord.Database[0];
    }
    if (rcurr.length >0){
        
        var thrlowcurr=rcurr.physicals[0];
        cord=SetCoordinatebyGeom(thrlowcurr.geom)
        thrlowcurr['lat']=cord.Database[1];
        thrlowcurr['lon']=cord.Database[0];
        if (thrlowcurr.disp_geom){
            cord=SetCoordinatebyGeom(thrlowcurr.disp_geom)
            thrlowcurr['disp_lat']=cord.Database[1];
            thrlowcurr['disp_lon']=cord.Database[0];
        }
        var thrhighcurr=rcurr.physicals[1];
        cord=SetCoordinatebyGeom(thrhighcurr.geom)
        thrhighcurr['lat']=cord.Database[1];
        thrhighcurr['lon']=cord.Database[0];
        if (thrhighcurr.disp_geom){
            cord=SetCoordinatebyGeom(thrhighcurr.disp_geom)
            thrhighcurr['disp_lat']=cord.Database[1];
            thrhighcurr['disp_lon']=cord.Database[0];
        }
    }
    compareisidata(thrfieldthr,thrlowtemp,thrlowcurr,'low');
    compareisidata(thrfieldthr,thrhightemp,thrhighcurr,'high');
    settonullinput(thrfieldthr,'low');
    settonullinput(thrfieldthr,'high');

    // thrshow(rtemp.physicals[0],'low');
    // thrshow(rtemp.physicals[1],'high');
    window.scroll(0,0);
}



function savedata(){
    var dataok=false;
    var llat = $('#low_lat').val();
    var llon = $('#low_lon').val();
    var dllat = $('#low_disp_lat').val();
    var dllon = $('#low_disp_lon').val();
    var hlat = $('#high_lat').val();
    var hlon = $('#high_lon').val();
    var dhlat = $('#high_disp_lat').val();
    var dhlon = $('#high_disp_lon').val();
    $('#mainsave').val('NO');
    $('#lowsave').val('NO');
    $('#highsave').val('NO');

    if (llat=='' || llon=='' || hlat=='' || hlon=='' ){
        Swal.fire("The data is not complete","Please complete the data first", "warning");
    }else{

        var crd=SetCoordinate(llat,llon);
        $("#low_geom").val(crd.Point)
    
        if (dllat !=='' || dllat !=='NIL'){
            crd=SetCoordinate(dllat,dllon);
            $("#low_disp_geom").val(crd.Point)
        }
    
        crd=SetCoordinate(hlat,hlon);
        $("#high_geom").val(crd.Point)
    
        if (dhlat !=='' || dhlat !=='NIL'){
            crd=SetCoordinate(dhlat,dhlon);
            $("#high_disp_geom").val(crd.Point)
        }
    
        if  ($("#status").val()=='N'){
            var thrlownew=$("#low_rwy_ident").val();
            var thrhighnew=$("#high_rwy_ident").val();
            var rwyident=thrlownew + '-' +thrhighnew;
            var rwyid=arp.arpt_ident + '_' + rwyident;

            $("#rwy_id").val(rwyid)
            $("#low_rwy_id").val(rwyid)
            $("#high_rwy_id").val(rwyid)
            $("#low_rwy_key").val('RWY_' + arp.arpt_ident + '_' +thrlownew )
            $("#high_rwy_key").val('RWY_' + arp.arpt_ident + '_' +thrhighnew )
            $("#rwy_ident").val(rwyident)
            $("#thr_low").val(thrlownew);
            $("#thr_high").val(thrhighnew);
            var rwyfieldnew=[ 'rwy_id', 'arpt_ident', 'rwy_ident', 'length', 'width', 'pcn', 'surface','thr_low', 'thr_high'];
            var thrfieldnew=['rwy_key', 'rwy_id', 'rwy_ident', 'lat','lon', 'mag_brg', 'tora', 'toda', 'asda', 'lda', 'true_brg'];
            var checkrwy =checknewdata(rwyfieldnew);
            var checthrlow =checknewdata(thrfieldnew,'low');
            var checthrhigh =checknewdata(thrfieldnew,'high');
            if (checkrwy==true && checthrlow==true && checthrhigh==true){
                dataok=true;
                console.log('Data Valid')
            }else{
                dataok=false;
                console.log('Data tidak Valid')
            }
    
        }else if ($("#status").val()=='R'){
            dataok=true;
            var rwyfieldupd=['rwy_id', 'arpt_ident', 'rwy_ident', 'length', 'width', 'pcn', 'surface','strip_l', 'strip_w','thr_low', 'thr_high'];
            var thrfieldthrup=['rwy_key', 'rwy_id', 'rwy_ident', 'lat','lon', 'mag_brg', 'resa_l', 'resa_w', 'thr_elev', 'tdz_elev', 'disp_thr_length', 'swy_length', 'cwy_length','slope', 'disp_thr_elev', 'disp_lat','disp_lon','tora', 'toda', 'asda', 'lda', 'remarks','true_brg', 'cwy_width', 'swy_width', 'slope1','geoid'];
            var checkrwy =checkupdatedata(rwyfieldupd,rtemp);
            var checkthrlow =checkupdatedata(thrfieldthrup,thrlowtemp,'low');
            var checkthrhigh =checkupdatedata(thrfieldthrup,thrhightemp,'high');
            // console.log('RWY',checkrwy,'THR LOW',checthrlow,'THR HIGH',checthrhigh)
        };
        if (checkrwy===true ){
            $('#mainsave').val('YES');
        }
        if (checkthrlow===true){
            $('#lowsave').val('YES');
        }
        if (checkthrhigh===true){
            $('#highsave').val('YES');
        }
        
   
   
        if ((checkrwy===true  || checkthrlow===true || checkthrhigh===true) &&  dataok==true){
            $("#rwymain").submit()
        }
        // if (dataok===true){
        //     if (checkrwy===true){
        //         // $('#rwymain').submit();
        //         // console.log('update data RWY_form');
        //         // console.dir($('#lowthr').serialize()); 
        //         $.ajax({
        //             url: '../api/rwy/temp/save',
        //             type: 'post',
        //             data:$('#rwymain').serialize(),
        //             success:function(retn){
        //                 // console.dir(retn);
        //             }
        //         }); 
        //     }
        //     if (checkthrlow===true){
                
        //         $('#lowthr').submit();
        //         console.log('update data THR Low form');
        //         // console.dir($('#lowthr').serialize()); 
        //         $.ajax({
        //             url: '../api/rwy/physical/temp/save',
        //             type: 'post',
        //             data:$('#lowthr').serialize(),
        //             success:function(retn){
        //                 // console.dir(retn);
        //             }
        //         }); 
        //     }
        //     if (checkthrhigh===true){
            
        //         // $('#highthr').submit();
        //         console.log('update data THR High form');
        //         // // console.dir($('#lowthr').serialize()); 
        //         $.ajax({
        //             url: '../api/rwy/physical/temp/save',
        //             type: 'post',
        //             data:$('#highthr').serialize(),
        //             success:function(retn){
        //                 // console.dir(retn);
        //             }
        //         }); 
        //     }
        // }
        
        // window.location.href="{{ url('aipedit') }}/212" +"/" + arp.arpt_ident;
    }

    // window.scrollTo(0,0);
}
function backtomain(){
    
    window.scroll(0,0);
    aboutvol('rwyedit');
    aboutvol('rwytable');
}

function checkthrident(id, pos) {
    var val = $("#"+ id).val();
    switch (val.length) {
        case 1:
            if (pos == 'low') {
                if (val > 1) {
                    Swal.fire("Incorrect data entered!", "Please Entry the Rwy Low ident (01 - 18)", "warning");
                }
            } else {
                if (val > 3) {
                    Swal.fire("Incorrect data entered!", "Please Entry the Rwy Low ident (19 - 36)", "warning");
                }
            }
            break;
        case 2:
            if (pos == 'low') {
                if (val > 18) {
                    Swal.fire("Incorrect data entered!", "Please Entry the Rwy Low ident (01 - 18)", "warning");
                } else {
                    var nilai1 = Number(val) + 18
                    $("#high_rwy_ident").val(nilai1);
                    $("#thrlow").html('THR ' +val);
                    $("#thrhigh").html('THR ' + nilai1);
                    $("#rwy_ident").val(val+'-' + nilai1);
                    $("#thr_low").val(val);
                    $("#thr_high").val(nilai1);
                }

            } else {
                if (val < 19 || val > 36) {
                    Swal.fire("Incorrect data entered!", "Please Entry the Rwy Low ident (19 - 36)", "warning");
                } else {
                    var nilai =numeral(Number(val)-18).format('00');
                    $("#low_rwy_ident").val(nilai);
                    $("#thrlow").html('THR ' +nilai);
                    $("#thrhigh").html('THR ' + val);
                    $("#rwy_ident").val(nilai+'-' + val);
                    $("#thr_low").val(nilai);
                    $("#thr_high").val(val);
                }

            }
            break;
        case 3:
            // var dataok = false;
            var thr = parseInt(val.substr(0, 2))
            // console.log(thr, ' THR', typeof thr)
            var pref = val.substr(val.length - 1).toUpperCase()
            var opp = '';
            switch (pref) {
                case 'R':
                    opp = 'L'
                    break;
                case 'L':
                    opp = 'R'
                    break;
                case 'C':
                    opp = 'C'
                    break;
            }
            if (pos == 'low') {
                if (thr > 18 || val > 18) {
                    Swal.fire("Incorrect data entered!", "Please Entry the Rwy Low ident (01 - 18)", "warning");
                } else {
                    // dataok = true
                    // console.log(opp)
                    var nilai3 = (Number(thr) + 18) +opp
                    $("#high_rwy_ident").val(nilai3);
                    $("#thrlow").html('THR ' +val);
                    $("#thrhigh").html('THR ' + nilai3);
                    $("#rwy_ident").val(val+'-' + nilai3);
                    $("#thr_low").val(val);
                    $("#thr_high").val(nilai3);

                }

            } else {
                if (thr < 19 || thr > 36 || val > 36) {
                    Swal.fire("Incorrect data entered!", "Please Entry the Rwy Low ident (19 - 36)", "warning");
                } else {
                    // dataok = true
                    // var nilai2 = Number(thr) - 18
                    var nilai2 =numeral(Number(thr)-18).format('00') + opp;
                    $("#low_rwy_ident").val(nilai);
                    $("#thrlow").html('THR ' +nilai);
                    $("#thrhigh").html('THR ' + val);
                    $("#rwy_ident").val(nilai+'-' + val);
                    $("#thr_low").val(nilai);
                    $("#thr_high").val(val);


                }

            }
            break;
        default:
            Swal.fire("Incorrect data entered!", "Please check for naming rules", "warning");

    }

}
function recalculate(){
var lat1=$("#low_lat").val();lon1=$("#low_lon").val();lat2=$("#high_lat").val();lon2=$("#high_lon").val();
    if ( lat1=='' && lon1 == '' && lat2 == '' && lon2 == '') {
                Swal.fire("Data incomplete!", "Coordinate data is incomplete.", "warning");
    } else {
        var crd1=SetCoordinate(lat1,lon1);crd2=SetCoordinate(lat2,lon2);
        // console.log(crd1,crd2)
        var ddist = getdistance(crd1.Decimal[1], crd1.Decimal[0], crd2.Decimal[1], crd2.Decimal[0])
        this.plotline(crd1.Decimal[1], crd1.Decimal[0], crd2.Decimal[1], crd2.Decimal[0])
        console.log('distance',ddist)
        $("#length").val(ddist.Distance);
        $("#low_true_brg").val(ddist.TrackOutTrue);
        $("#low_mag_brg").val(ddist.TrackOutMag);
        $("#high_true_brg").val(ddist.TrackInTrue);
        $("#high_mag_brg").val(ddist.TrackInMag);


    }
}
function plotline(lat1, lon1, lat2, lon2) {
    this.url = '/map.php?lat1=' + lat1 + '&lon1=' + lon1 + '&lat2=' + lat2 + '&lon2=' + lon2
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open(this.url, 'Set Latitude and Longitude', params)
}
function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection