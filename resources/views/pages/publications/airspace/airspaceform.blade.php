@extends('layouts.app')

@section('template_title')
    ENR 2.1 
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
                <ul class="nav nav-tabs" id="tabMenu">
                    <li class="nav-item tab-pane{{old('tab') == 'tabItem1' ? ' active' : null}}">
                        <a class="nav-link active" data-toggle="tab" href="#tabItem1"><span>Airspace</span></a>
                    </li>
                    <li class="nav-item tab-pane{{old('tab') == 'tabItem2' ? ' active' : null}}">
                        <a class="nav-link"  data-toggle="tab" href="#tabItem2"><span>Class</span></a>
                    </li>
                    <li  class="nav-item tab-pane{{old('tab') == 'tabItem3' ? ' active' : null}}">
                        <a class="nav-link"  data-toggle="tab" href="#tabItem3"><span>Boundary</span></a>
                    </li>
                    <li class="nav-item tab-pane{{old('tab') == 'tabItem4' ? ' active' : null}}">
                        <a class="nav-link"  data-toggle="tab" href="#tabItem4"><span>Frequency</span></a>
                    </li>
                </ul>
                <div class="tab-content tabairspace" id="tabasp">
                    <div class="tab-pane active" id="tabItem1">
                        <div id="viewasp" style="visibility:visible">
                            @if (count($airspacetemp))
                            @foreach($airspacetemp as $asp)
                            <div class="row col-md-12">
                                <div class="col-md-4">
                                    <strong>Name</strong>
                                    <br>
                                    <p>{{$asp->airspace_name}}</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>Type</strong>
                                    <br>
                                    <p>{{$asp->airspace_type}}</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>ICAO</strong>
                                    <br>
                                    <p>{{$asp->icao_acc}}</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>Country</strong>
                                    <br>
                                    <p>{{$asp->country}}</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>RNP</strong>
                                    <br>
                                    <p>{{$asp->airspace_rnp}}</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>RVSM</strong>
                                    <br>
                                    <p>{{$asp->rvsm}}</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>RVSM Lower</strong>
                                    <br>
                                    <p>{{$asp->rvsm_lower}}</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>RVSM Upper</strong>
                                    <br>
                                    <p>{{$asp->rvsm_upper}}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>ATS Unit</strong>
                                    <br>
                                    <p>{{$asp->ats_unit}}</p>
                                </div>
                            </div>
                            @endforeach
                            @endif
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
                    <form action="api/airspace/save" method="post"  enctype="multipart/form-data" id="aspform">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="parent" id="parent" value="{{$parent}}">
                        <input type="hidden" name="ats_airspace_id" id="ats_airspace_id">
                        <input type="hidden" name="arpt_ident" id="arpt_ident">
                        <input type="hidden" name="status" id="status">
                        <div class="row col-md-12">
                            <div class="col-md-4">
                                <strong>Name</strong>
                                <br>
                                <input id="airspace_name" onfocusout="checkairspace()" name="airspace_name" type="text" style="text-transform:uppercase" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>Type</strong>
                                <br>
                                <select id="airspace_type" class="form-control" name="airspace_type">
                                @foreach($cod as $l)
                                    <option  value="{{$l}}">{{ $l}} </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong>ICAO</strong>
                                <br>
                                <input id="icao_acc" onfocusout="checkairport()" name="icao_acc" type="text" style="text-transform:uppercase" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <strong>Country</strong>
                                <br>
                                <select id="ctry" class="form-control" name="ctry">
                                @foreach($countries as $l)
                                    <option  value="{{$l->ident}}">{{ $l->country }} </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong>RNP</strong>
                                <br>
                                <input id="airspace_rnp" name="airspace_rnp" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>RVSM</strong>
                                <br>
                                <select id="rvsm" onchange="rvsmchange()" class="form-control" name="rvsm">
                                    <option  value="Y">YES</option>
                                    <option  value="N">NO</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong>RVSM Lower</strong>
                                <br>
                                
                                <input id="rvsm_lower" name="rvsm_lower" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <strong>RVSM Upper</strong>
                                <br>
                                <input id="rvsm_upper" name="rvsm_upper" type="text" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <strong>ATS Unit</strong>
                                <br>
                                <input id="ats_unit" name="ats_unit" type="text" style="text-transform:uppercase" class="form-control">
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
                                <a onclick="update()" id="btn_mainsave" class="btn btn-dim btn-dark"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem2">
                    <div id="viewaspclass" style="visibility:visible">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-stripped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewDataClass()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                                            <th>Class</th>
                                            <th>Sector</th>
                                            <th>Lower</th>
                                            <th>Upper</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($airspacetemp))
                                    @if(count($airspacetemp[0]->class))
                                    @foreach($airspacetemp[0]->class as $asp)
                                        <tr v-bind:key="airspace.id">
                                            <td class="tb-tnx-action">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">
                                                        <ul class="link-list-plain">
                                                            <a class="btn btn-dim btn-secondary" onclick="EditClass()"><i class="icon ni ni-edit"></i> Edit</a>
                                                            <a class="btn btn-dim btn-danger" id="{{ $asp->id }}" onclick="removelass(this.id)"><i class="icon ni ni-delete"></i> Remove</a>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $asp->asp_class }}</td>
                                            <td>{{ $asp->asp_sector }} </td>
                                            <td>{{ $asp->lower }} </td>
                                            <td>{{ $asp->upper }} </td>
                                            <td>{{ $asp->remarks }} </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        @endif
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
                                    <a onclick="isbacktomain()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                        &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="editaspclass" style="visibility:hidden">
                        <form action="api/airspace/class/save" method="post"  enctype="multipart/form-data" id="aspclassform">
                            <input type="hidden" name="_token" id="tokenclass" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="editorclass" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="idclass">
                            <input type="hidden" name="asp_id" id="asp_id">
                            <input type="hidden" name="arpt_ident" id="arpt_ident_class">
                            <input type="hidden" name="parent" id="parent_class" value="{{$parent}}">
                            <input type="hidden" name="airspace_type" id="airspace_typeclass">
                            <input type="hidden" name="status" id="statusclass">
                            <div class="row col-md-12">
                                <div class="col-md-3">
                                    <strong>Class</strong>
                                    <br>
                                    <input id="asp_class" name="asp_class" type="text" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <strong>Sector</strong>
                                    <br>
                                    <input id="asp_sector" name="asp_sector" type="text" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <strong>Lower</strong>
                                    <br>
                                    <input id="lower" name="lower" type="text" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <strong>Upper</strong>
                                    <br>
                                    <input id="upper" name="upper" type="text" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <strong>Remarks</strong>
                                    <br>
                                    <textarea id="remarks" name="remarks" type="text" class="form-control"></textarea>
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
                                <a onclick="updateclass()"  id="btn_mainclass" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Update</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabItem3">
                    <div id="viewaspseg" style="visibility:visible">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-stripped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="insert()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th></th>
                                            <th>sequence</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Shap</th>
                                            <th>Ref Point</th>
                                            <th>Arc Dist</th>
                                            <th>Arc Lat</th>
                                            <th>Arc Lon</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listboundary">
                                   
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
                                    <a onclick="isbacktomain()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                                        &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="editaspseg" style="visibility:hidden">
                        <form action="api/airspace/seg/save" method="post"  enctype="multipart/form-data" id="aspsegform">
                            <input type="hidden" name="_token" id="tokenseg" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="editorseg" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="idseg">
                            <input type="hidden" name="asp_id" id="asp_idseg">
                            <input type="hidden" name="airspace_type" id="airspace_typeseg">
                            <input type="hidden" name="status" id="statusseg">
                            <input type="hidden" name="parent" id="parent_seg" value="{{$parent}}">
                            <input type="hidden" name="asp_seg_id" id="asp_seg_id">
                            <input type="hidden" name="latlama" id="latlama">
                            <input type="hidden" name="lonlama" id="lonlama">
                            <input type="hidden" name="nav_id" id="nav_id">
                            <input type="hidden" name="arpt_ident_seg" id="arpt_ident_seg">
                            <input type="hidden" name="arpt_ident" id="arpt_identseg">
                            <input type="hidden" name="createpolygon" id="createpolygon">
                            <input type="hidden" name="saveother" id="saveother">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Sequence</strong>
                                    <br>
                                    <input type="number" class="form-control" id="air_seq" name="air_seq">
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
                                            <select name="select21" id="select21" class="form-control select21">
                                        </div>
                                        <div class="col-md-12" style="visibility: hidden">
                                            <strong>ID</strong>
                                            <br>
                                            <input style="visibility: hidden" type="text" class="form-control"/>
                                        </div>
                                        <div class="col-md-12" id="search2" style="visibility: hidden">
                                            <select name="select22" id="select22" class="form-control select22">
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
                                    <textarea type="text" class="form-control" id="seg_remarks" name="remarks"></textarea>
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
                                <button onclick="updateseg()" id="btn_mainseg" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Update</button>
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
                <div class="tab-pane" id="tabItem4">
                    <div class="col-md-12">
                        @if (count($airspacetemp))
                        @if (count($airspacetemp[0]->freq))
                        <div class="panel-body mt-3">
                            @foreach($airspacetemp[0]->freq[0]->callsign as $freq)
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Call Sign</strong>
                                        <br>
                                        {{ $freq->call_sign }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Sector</strong>
                                        <br>
                                        {{ $freq->sector }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Service</strong>
                                        <br>
                                        {{$freq->types }}
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Remarks</strong>
                                        <br>
                                        {{ $freq->remarks }}
                                    </div>
                                </div>
                                <br>
                                <div class="row mt-3">
                                    <h6 class="panel-title">Frequency</h6>
                                    <table class="table table-stripped table-bordered" id="table-content">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>NO</th>
                                                <th>Frequency</th>
                                                <th>Opr. Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($freq->segment as $i=> $u)
                                                <tr v-bind:key="enr.use_on">
                                                    <td>{{ $i+1 }}</td>
                                                    @if ($u->level=='2')
                                                    <td>{{Airspacefreq($u->value[0]->freq,$u->value[0]->unit,'DATA')  }} (SRY)</td>
                                                    @else
                                                    <td>{{Airspacefreq($u->value[0]->freq,$u->value[0]->unit,'DATA')  }}</td>
                                                    @endif
                                                    <td>{{ $u->opr_hrs }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                <br>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <buton onclick="isbacktomain()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</buton>
                                        &nbsp;
                                    <buton onclick="editasp()" class="btn btn-dim btn-dark"><i class="icon ni ni-edit"></i> Edit</buton>&nbsp;
                                    <buton onclick="Removefreqasp()" class="btn btn-dim btn-danger"><i class="icon ni ni-edit"></i> Remove</buton>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <buton onclick="isbacktomain()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</buton>
                                    &nbsp;
                                    <buton onclick="editasp()" class="btn btn-dim btn-dark"><i class="icon ni ni-plus"></i> add Frequency </buton>
                                </div>
                            </div>
                        </div>
                        <form action="api/freq/usage/save" method="post"  enctype="multipart/form-data" id="freqform">
                            <input type="hidden" name="_token" id="tokenfreq" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="high_editor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="status" id="statusfreq">
                            <input type="hidden" name="freqid" id="freqid">
                            <input type="hidden" name="asp_id" id="asp_idfreq">
                            <input type="hidden" name="asp_type" id="asp_type">
                            <input type="hidden" name="asp_airport" id="asp_airport">
                            <input type="hidden" name="seq" id="seq">
                        </form>
                        <div class="col-md-12" id="newfreq" style="visibility: hidden" >
                            <strong>Call Sign</strong>
                            <br />
                            <select name="call_sign" class="form-control select2" id="call_sign">
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts')
<script type="text/javascript">
var asp =@json($airspace);pnt1='';pnt2='';aspid=asp.asp_id;
var asptemp =@json($airspacetemp);idstatus=@json($id);
var cd =@json($cod); affhasil=[];parent=@json($parent);
var latlama='',lonlama='';
console.log('parent',parent,asptemp);
var fld=[
        'id','ats_airspace_id', 'airspace_name', 'airspace_type', 'airspace_rnp','arpt_ident', 'rvsm', 'icao_acc', 'ctry', 'rvsm_upper', 'rvsm_lower', 'ats_unit']
var fldclass =[
        'asp_id', 'asp_class', 'asp_sector', 'upper', 'lower','remarks','id'];
var fldseg=[
        'asp_seg_id', 'air_seq', 'point1_lat', 'point1_long', 'shap', 'arc_dist', 'arc_lat', 'arc_long','remarks' ];

$("#editasp").hide();
$("#editaspclass").hide();
$("#editaspseg").hide();
$("#search1").hide();
$("#search2").hide();
$("#refcenter").hide();
$("#affect").hide();
$("#newfreq").hide();
window.scrollTo(0,0);
function isbacktomain(){
    window.scrollTo(0,0);
    $('#tabMenu a[href="#tabItem1"]').tab('show');
    
}
if (asptemp.length > 0){
    $('#asptitle').html(asptemp[0].airspace_name+ ' ' +  asptemp[0].airspace_type + ' information');
    $("#arpt_ident_class").val(asptemp[0].arpt_ident);
    $("#arpt_identseg").val(asptemp[0].arpt_ident);
    $("#listboundary").empty();
    var no=1;
    asptemp[0].boundary.forEach(b=>{
       
        var ident='';adist='';alat='';alon='';                   
        if (b.navaid.length > 0){
            ident=b.navaid[0].nav_ident
        }else if (b.airport.length > 0){
            ident=b.airport[0].icao
        }
        adist=b.arc_dist;alat=b.arc_lat;alon=b.arc_long;
        if (b.arc_dist==null || b.arc_dist=='NIL' || b.arc_dist==''){
            adist=''
        }
        if (b.arc_lat==null || b.arc_lat=='NIL' || b.arc_lat==''){
            alat=''
        }
        if (b.arc_long==null || b.arc_long=='NIL' || b.arc_long==''){
            alon=''
        }
        if (b.shap=='G'){
            if (b.remarks !== null){
                console.log(b.remarks)
                hasil ='<tr class="nk-tb-item">'+
                '<td colspan="8">'+b.remarks +'</td></tr>';
            }else{
                hasil =''; 
            }
        }else{
            hasil =
                    '<tr class="nk-tb-item">'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                        '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                            '<ul class="link-list-plain">'+
                                ' <a class="btn btn-dim btn-secondary" id="'+ b.id +'" onclick="editsegment(this.id)"><i class="icon ni ni-edit"></i>Edit</a>'+
                                '<a class="btn btn-dim btn-secondary" id="'+ b.id +'" onclick="insert(this.id)"><i class="icon ni ni-plus"></i> Insert</a>'+
                                '<a id="'+ b.id +'" class="btn btn-dim btn-danger" onclick="removesegment(this.id)"><i class="icon ni ni-delete"></i>Remove</a>'+
                                '</ul>'+
                        '</div>'+
                '</div>'+
            '</td>'+
            '<td>' + no + '</td><td>' + b.point1_lat + '</td><td>' + b.point1_long + '</td><td>' + b.shap + '</td><td>' + ident + '</td><td>' + adist + '</td><td>' + alat + '</td><td>' + alon + '</td></tr>';
            no++
        }
        if (hasil !==''){

            $("#listboundary").append(hasil);
        }
    })
}
$("#btn_mainsave").html('<i class="icon ni ni-save-fill"></i> Update');
if (idstatus=='newdata'){
    aboutvol("viewasp")
    aboutvol("editasp")
    $('#asptitle').html('New Data');
    $("#status").val('N');
    $("#ctry").val('ID');
    $("#btn_mainsave").html('<i class="icon ni ni-save-fill"></i> Save');
        
}
function rvsmchange(){
    if ($("#rvsm").val()=='Y'){
        $("#rvsm_lower").prop('disabled', false);
        $("#rvsm_upper").prop('disabled', false);

    }else{
        $("#rvsm_lower").prop('disabled', true);
        $("#rvsm_upper").prop('disabled', true);
    }
}
function Removefreqasp(){
    $("#statusseg").val('DELFREQ');
    $("#asp_idseg").val(asptemp[0].ats_airspace_id);
    console.log('REMOVE FREQ',$("#asp_idseg").val(),asptemp[0].ats_airspace_id);
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
           
            $("#aspsegform").submit();
        }
    })
}
function removesegment(id){
    console.log(id)
    $("#statusseg").val('D');
    $("#asp_idseg").val(asptemp[0].ats_airspace_id);
    $("#idseg").val(id);
    $("#airspace_typeseg").val(asptemp[0].airspace_type);

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
            $("#aspsegform").submit();
        }
    })
}
function checkothercord(){
    var shp=$("#shap").val()
    var seq=$("#air_seq").val()
    // console.log(shp)
    if (shp !=='C' || shp !=='E'){
        var lat =$("#point1_lat").val();
        var lon =$("#point1_long").val();
        var ix = asptemp[0].boundary.findIndex(x=>x.point1_long==lon && x.point1_lat==lat)
        if (ix !== -1){
            var ddbl=asptemp[0].boundary[ix];
        //    console.log(seq,ddbl.air_seq)
            if (Number(seq) !== ddbl.air_seq){
                Swal.fire(
                    'Coordinate Double',
                    'The coordinates already used (seqence '+ ddbl.air_seq + ')',
                    'warning'
                    )
            }
        }
    }
}
function plotarea(latN,lonN,latL,lonL){
    this.url = '/map.php?pointnewlat=' + latN + '&pointnewlon=' + lonN + '&pointlat=' + latL + '&pointlon=' + lonL
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open(this.url, 'Set Latitude and Longitude', params)
}
function checkotherairspace(){
    var lat =$("#point1_lat").val();
    var lon =$("#point1_long").val();
    var asspid= $("#asp_idseg").val();
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
                    affhasil=[];
                    var sql = 'api/airspace/list/temp/seg';
                    $.ajax({
                        url: sql,
                        data: {'point1_lat' : latlama,'point1_long' :  lonlama},
                        type: "json",
                        method: "GET",
                
                        success: function (result) {
                            console.log(result.data);
                            $.each(result.data, function (k, v) {
                                if (v.asp_id !== asspid){
                                    if ($("#affect").is(':visible')==false){
                                            aboutvol('affect');
                                    }
                                    var t = affhasil.findIndex(x=>x.asp_id===v.asp_id)
                                    if (t==-1){
                                        var hsl= '<tr><td>' + v.airspace_name + '</td><td>' + v.airspace_type + '</td></tr>'
                                        $("#aspaffect").append(hsl)
                                    }
                                    affhasil.push(v)
                                    // console.log(v)
                
                                }
                
                                        // console.log('INI YG DIAMBIL',pubdate)
                                    
                            })
                        }
                    })
                }
            })
        }
    }
}
function checkairspace(){
    // console.log('check wpt name')
    var icaoacc=$("#airspace_name").val().toUpperCase();
    $.ajax({
            url: '/api/airspace/temp/list',
            data: {'airspace_name' : icaoacc},
            type: "json",
            method: "GET",

            success: function (result) {
                // var jmlwpt=result.data.length
                // console.log(jmlwpt,'jmlwpt');
                $.each(result.data, function (k, v) {
                    // console.log(v)
                   
                    Swal.fire({
                        title: 'Data Double?',
                        text: 'Data already exists ' + v.airspace_name + ' '+ v.airspace_type,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Created it!'
                    }).then((result) => {
                        if (result.value) {
                        }else{
                            $("#airspace_name").val('');
                        }
                    })
                })
            }
    })
}
function checkairport(){
    var asparpt=$("#airspace_type").val();
    if ( asparpt=='ATZ' || asparpt  =='AFIZ' || asparpt=='CTR' ){
        console.log('check wpt name')
        var icaoacc=$("#icao_acc").val().toUpperCase();
        if (icaoacc.length !== 4){
            Swal.fire(
                'No Airport data!',
                'Please re-enter the airport code' ,
                'warning'
            )
        }else{

            $.ajax({
                    url: '/api/airports',
                    data: {'icao' : icaoacc},
                    type: "json",
                    method: "GET",
    
                    success: function (result) {
                        var jmlwpt=result.data.length
                        console.log(jmlwpt,'jmlwpt');
                        if (jmlwpt == 0){
                            Swal.fire(
                                'No Airport data!',
                                'Please re-enter the airport code' ,
                                'warning'
                            )
                        }
    
                        $.each(result.data, function (k, v) {
                            console.log(v)
                            $("#arpt_ident").val(v.arpt_ident);
                        })
                    }
            })
        }
    }
}
function shapcode(){
    if ($("#refcenter").is(':visible')==true){
            aboutvol('refcenter');
    }
    $("#point1_lat").prop('disabled', false);
    $("#point1_long").prop('disabled', false);
    var key=$("#shap").val()
    // console.log(key)
    switch (key) {
        case "C":
            aboutvol('refcenter');
            $("#point1_lat").prop('disabled', true);
            $("#point1_long").prop('disabled', true);
            break;
        case "L":
        case "R":
            aboutvol('refcenter');
            break;
        default:
            $("#arc_dist").val('');
            $("#arc_lat").val('');
            $("#arc_long").val('');
            $("#nav_id").val('');
            $("#point1").val('');
            $('#arpt_identseg').val('');
            break;
    }
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
        // console.log('search2')
        aboutvol('search2');
    }
// console.log(refsearch,referensi)
if (refsearch=='NAV'){
    $('.select21').select2({
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
        $('.select22').select2({
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
            // console.log(e)
            if(e.params.data.isNew){
            var r = confirm("do you want to create a new Airport?");
            if (r == true) {
                NewData()
                $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
            }
            else
            {
                $('.select2-selection__choice:last').remove();
                $('.select2-search__field').val(e.params.data.text).focus()
            }
        }else{
           
            $("#arpt_identseg").val(e.params.data.id);
                $('#point1').val(e.params.data.icao);
                crd1=SetCoordinatebyGeom(e.params.data.geom);
                console.log($("#arpt_identseg").val(),'$("#arpt_identseg").val')
                $("#arc_lat").val(crd1.Database[1])
                $("#arc_long").val(crd1.Database[0])
                 if ($("#search2").is(':visible')==true){
                aboutvol('search2');
            }
            if ($("#search1").is(':visible')==true){
                aboutvol('search1');
            }
        }
    });

                // crd2=SetCoordinatebyGeom(pnt2geom);
                
        // });
           
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

function backtolist(){
    // if($('.active.tab-pane')[0].id == 'tabItem1'){

        window.scrollTo(0,0);
        if (parent=='ENR2.1'){
            window.location.href = 'listairpace/edit';

        }else{
            window.location.href ='edit217/'+ parent;
        }
    // } else {
    //     console.log($('.active.tab-pane')[0].id);
    //     // $('.active.tab-pane')[0].id == 'tabItem1';
    //     // $('#tabItem1').click();
    //     $('#tabItem1 a[href="#tabItem1"]').tab('show');
    // }
    
    // window.history.back()
}
function backtoview(){
    if ($("#affect").is(':visible')==true){
        aboutvol('affect');
    }
    if ($("#editaspseg").is(':visible')==true){
        aboutvol('viewaspseg');
        aboutvol('editaspseg');
    }
    if ($("#editaspclass").is(':visible')==true){
        aboutvol('viewaspclass');
        aboutvol('editaspclass');
    }
    if ($("#editasp").is(':visible')==true){
        if (asptemp.length ==0){
            backtolist();
        }else{
            aboutvol('viewasp');
            aboutvol('editasp');
            
        }
    }
    
}
function setMapPoint(){
    
    showdetail(asptemp[0].ats_airspace_id+'$airspacepoint');
}
function NewDataClass(){
    aboutvol("viewaspclass")
    aboutvol("editaspclass")
    $("#statusclass").val('N');
    $("#arpt_ident_class").val(asptemp[0].arpt_ident);
    $("#airspace_typeclass").val(asptemp[0].airspace_type);
    $("#asp_id").val(asptemp[0].ats_airspace_id);
}

function insert(id){
    // console.log(id)
    aboutvol("viewaspseg")
    aboutvol("editaspseg")
    $("#statusseg").val('N');
    $("#shap").val('H');
    $("#arpt_ident_seg").val(asptemp[0].arpt_ident);
    $("#btn_mainseg").html('<i class="icon ni ni-save-fill"></i> Save');
    var aseq=10;
    if (asptemp[0].boundary.length > 0){
        
        var ix = asptemp[0].boundary.findIndex(x=>x.id==Number(id))
        stemp=asptemp[0].boundary[ix];
        aseq=Number(stemp.air_seq)+1;
    }
    //BDRY_SUA_MATANG_00010
    var sseq=numeral(aseq).format('000000')
    var bdryid='BDRY_' + asptemp[0].ats_airspace_id + '_' +sseq;
    $("#asp_seg_id").val(bdryid);
    $("#air_seq").val(aseq);
    $("#asp_idseg").val(asptemp[0].ats_airspace_id);
    $("#airspace_typeseg").val(asptemp[0].airspace_type);
   
    shapcode()
    
}

function editsegment(id){
    $("#btn_mainseg").html('<i class="icon ni ni-save-fill"></i> Update');
    aboutvol("viewaspseg")
    aboutvol("editaspseg")
    $("#arpt_ident_seg").val(asptemp[0].arpt_ident);
    $("#statusseg").val('R');
    $("#asp_idseg").val(asptemp[0].ats_airspace_id);
    $("#idseg").val(id);
    $("#airspace_typeseg").val(asptemp[0].airspace_type);
    var ix = asptemp[0].boundary.findIndex(x=>x.id==id)
    var sTemp=asptemp[0].boundary[ix];
    var sCurr=[];idx=-1;
    if (asp.length >0){
        if (asp[0].boundary.length > 0){
            idx = asp[0].boundary.findIndex(x=>x.id==id)
        }
    }
   
   
    if (idx !== -1){
        sCurr=asp[0].boundary[idx];
    }
    if (sTemp.nav_id !== null){
        $("#refpoint").val('NAV')
        $("#nav_id").val(sTemp.navaid[0].nav_id)
        $("#point1").val(sTemp.navaid[0].nav_ident + ' '+ sTemp.navaid[0].definition)
    }
    if (sTemp.arpt_ident !== null){
        $("#refpoint").val('ARPT')
        $("#arpt_identseg").val(sTemp.airport[0].arpt_ident)
        $("#point1").val(sTemp.airport[0].icao)
    }
    
    latlama=sTemp.point1_lat;
    lonlama=sTemp.point1_long;
    $("#latlama").val(latlama);
    $("#lonlama").val(lonlama);
    $("#arclatlama").val(sTemp.arc_lat);
    $("#arclonlama").val(sTemp.arc_long);
    compareisidata(fldseg,sTemp,sCurr);
    shapcode()
    settonullinput(fldseg);
   
    
}
function EditClass(id){

    // alert("Airspace");
    aboutvol("viewaspclass")
    aboutvol("editaspclass")
    $("#statusclass").val('R');
   
    $("#airspace_typeclass").val(asptemp[0].airspace_type);
    $("#idclass").val(asptemp[0].class[0].id);
    var aspcls=[];
    if (asp.length >0){
        if (asp[0].class.length > 0){
            aspcls=asp[0].class[0];

        }
    }
    compareisidata(fldclass,asptemp[0].class[0],aspcls);
    
}
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Select Call Sign',
        minimumInputLength: 3,
        ajax: {
            url: "<?=url('/api/freq/search');?>",
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
                                text:  item.call_sign + ' - ' + item.types,
                                id: item.id
                            }
                        })
                };
            },
            cache: true
        },
        templateSelection: function (selection) {
            var result = selection.text.split('-');
            return result[0];
        },
        tags: true,
        tokenSeparators: [",", " "],
        createTag: function (tag) {
            return {
                id: tag.term,
                text: tag.term,
                isNew : true
            };
        }
    }).on("select2:select", function(e) {
        if(e.params.data.isNew){
            var r = confirm("do you want to create a new frequency?");
            if (r == true) {
                NewData()
                $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
            }
            else
            {
                $('.select2-selection__choice:last').remove();
                $('.select2-search__field').val(e.params.data.text).focus()
            }
        }else{
            console.log('asptemp[0]',asptemp[0]);
            $("#freqid").val(e.params.data.id);
            $("#asp_idfreq").val(asptemp[0].ats_airspace_id);
            $("#statusfreq").val('N');
            $("#asp_airport").val(asptemp[0].arpt_ident);
            $("#asp_type").val(asptemp[0].airspace_type);
            $("#seq").val(0);
            Swal.fire({
                title: 'Insert Data',
                text: "The Frequency will be inserted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, inserted it!'

            }).then((result) => {
                    if (result.value) {
                        $("#freqform").submit();

                        
                    }else{
                        location.reload();

                    }
            })
        }
    });
});
function NewData(){
    window.location.href = '/frequency/new@ENR2.1@' + asptemp[0].ats_airspace_id;
}
function editasp(id){
    if($('.active.tab-pane')[0].id == 'tabItem4'){
        if (asptemp[0].freq.length==0){
            console.log('new data')
            aboutvol("newfreq");
        }else{
            console.log(asptemp[0].freq[0].freqid,'EDIT FREQ');
            window.scrollTo(0,0);
            window.location.href = '/frequency/'+ asptemp[0].freq[0].freqid+'@ENR21@' +asptemp[0].ats_airspace_id ;

        }
    } else {
      
        aboutvol("viewasp")
        aboutvol("editasp")
        $("#status").val('R');
        var aspmains=[];
        if (asp.length >0){
            aspmains=asp[0];

        }
        compareisidata(fld,asptemp[0],aspmains);
        settonullinput(fld);
        rvsmchange();
    }
}
function updatesegment(){
    var fldsegup=[
        'asp_seg_id', 'air_seq', 'point1_lat', 'point1_long', 'shap', 'arc_dist', 'arc_lat', 'arc_long','remarks' ];

    settonullinput(fldsegup);

        $("#aspsegform").submit();

    
}
function updateseg(){
    $("#saveother").val('N');
    if  (affhasil.length > 0){
        Swal.fire({
        title: 'Do You want to change the other airspace ?',
        text: "The system will automatically change the boundary that coincides with this point!",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.value) {
                $("#saveother").val('Y');
            }
            updatesegment();
        })
    
    }else{
        updatesegment();
    }
    // console.log(affhasil,affhasil.length)
    // $("#createpolygon").val('T');savedata=true;
    // if (asptemp[0].boundary.length > 1){
    //     var latawal=asptemp[0].boundary[0].point1_lat;
    //     var lonawal=asptemp[0].boundary[0].point1_long;
    //     var latakhir='';
    //     var lonakhir='';
    //     var ixx=asptemp[0].boundary.findIndex(x=>x.shap==='E')
    //     if (ixx==-1){
    //         $("#createpolygon").val('F');
    //     }else{
    //         latakhir=asptemp[0].boundary[ixx].point1_lat;
    //         lonakhir=asptemp[0].boundary[ixx].point1_long;
    //         if (latawal==lonakhir && lonawal==lonakhir){
    //             $("#createpolygon").val('T');
    //         }else{
    //             savedata=false;
    //             $("#createpolygon").val('F');
    //             Swal.fire(
    //             'Invalid data',
    //             'The first sequence of data must be the same as the last sequence (End of Line Shap)',
    //             'error'
    //             )
    //         }
    //     }

    // }

    // console.log(asptemp[0].boundary.length,asptemp[0].boundary)
    // asptemp[0].boundary.forEac
   

    // console.log(affhasil)
}
function updateclass(){
    var checkrwy=false;
   
    if  ($("#statusclass").val()=='N'){
        var fldclassnew =[
        'asp_id', 'asp_class', 'upper', 'lower'];
        checkrwy =checknewdata(fldclassnew);
        settonullinput(fldclassnew);
    }else if ($("#statusclass").val()=='R'){
        var fldclassUp =[
        'asp_id', 'asp_class', 'upper','asp_sector','lower','remarks'];
        checkrwy =checkupdatedata(fldclassUp,asptemp[0].class[0]);
        settonullinput(fldclassUp);
        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#aspclassform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        backtolist();
    }
}
function checktypeaspairport(type){
    console.log('checktypeaspairport',type)
    switch (type) {
        case 'AFIZ':
        case 'ATZ':
        case 'CTR':
            return true;
            break;
    
        default:
        return false;
            break;
    }

}
function update(){
    console.log('update')
    var checkrwy=false;
    if  ($("#status").val()=='N'){
        var fldnew=['airspace_name', 'airspace_type','icao_acc', 'ctry', 'ats_unit']
        if ($("#rvsm").val()=='Y'){
            fldnew=['airspace_name', 'airspace_type','icao_acc', 'ctry','rvsm_lower','rvsm_upper', 'ats_unit']
        }
        if (checktypeaspairport($("#airspace_type").val())==true){
            fldnew=['airspace_name', 'airspace_type','icao_acc','arpt_ident', 'ctry', 'ats_unit']
            if ($("#rvsm").val()=='Y'){
                fldnew=['airspace_name', 'airspace_type','icao_acc','arpt_ident', 'ctry', 'rvsm_lower','rvsm_upper','ats_unit']
            }
        }
        checkrwy =checknewdata(fldnew);
        settonullinput(fldnew);
        setinputtoupper(fldnew);
        if (checkrwy==false){
            Swal.fire(
            'Incomplete data',
            'Please complete the data first',
            'warning'
            )
        }else{
            $("#aspform").submit();
        }
    }else if ($("#status").val()=='R'){
        var fldUp=['airspace_name', 'airspace_type', 'airspace_rnp', 'rvsm', 'icao_acc', 'ctry', 'ats_unit']
        if ($("#rvsm").val()=='Y'){
            fldUp=['airspace_name', 'airspace_type', 'airspace_rnp', 'rvsm', 'icao_acc', 'ctry', 'rvsm_upper', 'rvsm_lower', 'ats_unit']
        }
        if (checktypeaspairport($("#airspace_type").val())==true){
            fldUp=['airspace_name', 'airspace_type', 'airspace_rnp', 'rvsm', 'icao_acc','arpt_ident', 'ctry', 'ats_unit']
            if ($("#rvsm").val()=='Y'){
                fldUp=['airspace_name', 'airspace_type', 'airspace_rnp', 'rvsm', 'icao_acc','arpt_ident', 'ctry', 'rvsm_upper', 'rvsm_lower','ats_unit']
            }
        }
        
        checkrwy =checkupdatedata(fldUp,asptemp[0]);
        settonullinput(fldUp);
        setinputtoupper(fldUp);
        // console.log('NAVUPDATE',checkrwy)
        if (checkrwy==true ){
            $("#aspform").submit();
            console.log('Data Valid')
        }else{
            console.log('Tidak ada perubahan data')
            backtolist();
        }
    };
   
}
</script>
@endsection