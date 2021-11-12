@extends('layouts.app')

@section('template_title')
    Frequency 
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading mt-3">
            <h6 class="panel-title" id="freqtitle"></h6>
        </div>
        <div class="panel-body mt-3"  id="mainfreq" style="visibility: visible">
            <div class="row">
            <form action="/api/freq/save" method="post"  enctype="multipart/form-data" id="freqmain">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="ctry" id="ctry">
                    <input type="hidden" name="status" id="status">
                    <input type="hidden" name="parent" id="parent" value="{{$parent}}">
                    <input type="hidden" name="parentid" id="parentid" value="{{$parentid}}">
                    <div class="row g-gs col-md-12">
                        <div class="col-md-4">
                            <strong>Types</strong>
                            <select selected="selected" class="form-control" id="types" name="types">
                            @foreach($cod as $c)
                                <option value="{{$c->types}}"> {{$c->definition}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <strong>Call Sign</strong>
                            <br />
                            <input class="cari form-control" style="text-transform:uppercase" id="call_sign" name="call_sign"/>
                        </div>
                        <div class="col-md-3">
                            <strong>Sector</strong>
                            <br />
                            <input type="text" class="form-control" style="text-transform:uppercase" id="sector" name="sector"/>
                        </div>
                        <div class="col-md-12">
                            <strong>Remarks</strong>
                            <br />
                            <textarea type="text" class="form-control" id="remarks" name="remarks"></textarea>
                        </div>
                    </div>
                </form>
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
                    <a onclick="savemain()" id="btn_save" class="btn btn-dim btn-dark"></a>
                </div>
            </div>
            <div class="row mt-3">

           
            
            <div class="col-md-12">
                @if ($freqid !=='new')
                <h6 class="panel-title">Frequency</h6>
                <table class="table table-stripped table-bordered" id="table-content">
                    <thead class="thead-dark">
                        <tr>
                            <th><a class="btn btn-sm btn-dim btn-dark" onclick="newdata()"><i class="icon ni ni-plus"></i> Add</a></th>
                            <th>NO</th>
                            <th>Frequency</th>
                            <th>Opr. Hours</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($freqstemp)
                        @foreach($freqstemp[0]->segment as $i=>$u)
                            <tr v-bind:key="enr.use_on">
                                <td class="tb-tnx-action">
                                    <div class="dropdown">
                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-left dropdown-menu-md">
                                            <ul class="link-list-plain">
                                                <a class="btn btn-dim btn-primary col-md-12" id="{{ $u->id }}" onclick="editvalue(this.id)"><i class="icon ni ni-edit"></i> Edit</a>
                                                <a class="btn btn-dim btn-danger col-md-12" id="{{ $u->id }}" onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $i+1 }}</td>
                                <td>{{Airspacefreq($u->value[0]->freq,$u->value[0]->unit,'DATA')  }}</td>
                                <td>{{ $u->opr_hrs }}</td>
                                @switch($u->level)
                                    @case(1)
                                    <td>PRIMARY</td>
                                    @break
                                    @case(2)
                                    <td>SECONDARY</td>
                                    @break
                                    @case(3)
                                    <td>EMERGENCY</td>
                                    @break
                                    @case(4)
                                    <td>SAR</td>
                                    @break
                                    @default
                                    <td></td>
                                    @break
                                @endswitch
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @endif
            </div>
            
            <div class="col-md-12">
            @if ($freqstemp)
                <h6 class="panel-title">Usage</h6>
                <table class="table table-stripped table-bordered" id="table-content">
                    <thead class="thead-dark">
                        <tr>
                            <th>NO</th>
                            <th>Type</th>
                            <th>Usage</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach($freqstemp[0]->usage as $i=>$u)
                            <tr v-bind:key="enr.use_on">
                                <td>{{ $i+1 }}</td>
                                @if (count($u->airport) > 0)
                                <td>Airport</td>
                                <td>{{ $u->airport[0]->icao }} {{ $u->airport[0]->city_name }} / {{ $u->airport[0]->arpt_name }}</td>
                                @elseif (count($u->airspace) > 0)
                                <td>Airspace</td>
                                <td>{{ $u->airspace[0]->airspace_name }} {{ $u->airspace[0]->airspace_type }}</td>
                                @endif
                                
                            </tr>
                        @endforeach
                       
                    </tbody>
                </table>
                @endif
            </div>
            </div>
        </div>
        <div class="panel-body mt-3" id="mainedit" style="visibility: hidden">
            <div>
                <form action="../api/freq/seg/save" method="post"  enctype="multipart/form-data" id="freqsegment">
                    <input type="hidden" name="_token" id="seg_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="seg_editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="seg_id" id="seg_id">
                    <input type="hidden" name="val_id" id="val_id">
                    <input type="hidden" name="seg_status" id="seg_status">
                    <input type="hidden" name="val_status" id="val_status">
                    <input type="hidden" name="freq_id" id="seg_freq_id">
                    <input type="hidden" name="call_sign" id="seg_call_sign">
                    <input type="hidden" name="freq" id="val_freq">
                    <input type="hidden" name="parent"  value="{{$parent}}">
                    <input type="hidden" name="parentid" value="{{$parentid}}">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Frequency</strong>
                            <br>
                            <input type="number" onfocusout="CheckFreqValue(this.id)" class="form-control" id="freqview"/>
                        </div>
                        <div class="col-md-4">
                            <strong>Band</strong>
                            <br>
                            <select selected="selected" class="form-control" id="val_unit" name="unit">
                            </select>
                        </div>
                        <div class="col-md-4">
                            <strong>Priority</strong>
                            <br>
                            <select class="form-control" id="seg_level" name="level">
                            </select>
                        </div>
                        <div class="col-md-4">
                            <strong>SATVOICE number(s)</strong>
                            <br>
                            <input type="text" class="form-control" id="seg_satcom" name="satcom"/>
                        </div>
                            <div class="col-md-4">
                            <strong>Logon Address</strong>
                            <br>
                            <input type="text" class="form-control" id="seg_logon" name="logon"/>
                        </div>
                            <div class="col-md-4">
                            <strong>Operation Hours</strong>
                            <br>
                            <input type="text" class="form-control" id="seg_opr_hrs" name="opr_hrs"/>
                        </div>
                        <div class="col-md-12">
                            <strong>Remarks</strong>
                            <br>
                            <textarea type="text" class="form-control" id="seg_remarks" name="remarks"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                </div>
            </div>
            <form action="../api/freq/seg/save" method="post"  enctype="multipart/form-data" id="freqformdelete">
                <input type="hidden" name="_token" id="tokenfreq" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="high_editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id_delete">
                <input type="hidden" name="seg_status" id="status_delete" value="D">
                <input type="hidden" name="freq_id" id="freq_id_delete" value="{{$freqid}}">
                <input type="hidden" name="parent" id="parent" value="{{$parent}}">
                <input type="hidden" name="parentid" id="parentid" value="{{$parentid}}">
            </form>
            <div class="row">
                <div class="col-md-12">
                    <a onclick="backtomain()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                    &nbsp;
                    <a onclick="savevalue()" id="btn_save_value" class="btn btn-dim btn-dark"><i class="icon ni ni-save"></i> Save</a>
                </div>
            </div>
        </div>
    </div>
</div>
    

@endsection
@section('footer_scripts')

<script type="text/javascript">


var frq =@json($freqs);fvlist=@json($freqvalue);csign=@json($callsign);
var frqtemp =@json($freqstemp);freqreal='';parent=@json($parentid);prnid=@json($parentid);freqnew=@json($freqid);
var fshtemp=[];
console.log(frqtemp,frq)

$("#mainedit").hide();


var fldfreq=['types', 'call_sign', 'ctry', 'remarks', 'sector'];
var fldse=['freq_id', 'level', 'opr_hrs', 'remarks', 'satcom', 'logon'];
var fldval=['freq', 'unit'];
var frequnit= [{
        id: 'H',
        definition: 'HF'
    }, {
        id: 'V',
        definition: 'VHF'
    }, {
        id: 'U',
        definition: 'UHF'
    }]
var level= [{
        id: '1',
        definition: 'PRIMARY'
    }, {
        id: '2',
        definition: 'SECONDARY'
    }, {
        id: '3',
        definition: 'EMERGENCY'
    }, {
        id: '4',
        definition: 'SAR'
    }, {
        id: '9',
        definition: 'NONE'
    }]
    level.forEach(l=>{
        var isi='<option value="'+l.id+'">' + l.definition + '</option>';
        $("#seg_level").append(isi);
    });
    // console.log(frequnit)
    frequnit.forEach(l=>{
        var isi='<option value="'+l.id+'">' + l.definition + '</option>';
        $("#val_unit").append(isi);
    });
// console.log(frqtemp.length,'frqtemp.length',frqtemp)
if (frqtemp.length==0){
    $("#freqtitle").html('New Frequency  Information')
    $("#status").val('N')
    $("#ctry").val('ID')
    $("#btn_save").html('<i class="icon ni ni-save"></i> Save')
}else{
    // frq=frq[0];frqtemp=frqtemp[0];
    // console.log(frq,frqtemp)
    var freqcurr=[];
    if (frq.length > 0){
        freqcurr=frq[0];
    }
   
    var freqtemp=frqtemp[0];
    $("#freqtitle").html(freqtemp.call_sign + ' ' + freqtemp.types +' Information')
    $("#id").val(freqtemp.id)
    compareisidata(fldfreq,freqtemp,freqcurr);
    $("#status").val('R')
    $("#btn_save").html('<i class="icon ni ni-save"></i> Update')
    
}


function savemain(){
    console.log($("#status").val())
    var checkrwy=false;
    var csign=$("#call_sign").val().toUpperCase();
    $("#call_sign").val(csign);
    
    if  ($("#status").val()=='N'){
        var newfld=['types', 'call_sign', 'ctry'];
            checkrwy =checknewdata(newfld);
    }else if ($("#status").val()=='R'){
            checkrwy =checkupdatedata(fldfreq,frqtemp);
    };
        if (checkrwy==true){
             var id = $('#freqmain').submit();
             console.log(id)
        }else{
            dataok=false;
            console.log('Data tidak Valid')
        }
}
function savevalue(){
    var checkrwy=false;
    if  ($("#seg_status").val()=='N'){
            checkrwy =checknewdata(fldse);
    }else if ($("#seg_status").val()=='R'){
        console.log(fshtemp)
            checkrwy =checkupdatedata(fldse,fshtemp,'seg');
    };
    if (checkrwy==true){
            $('#freqsegment').submit();
    }else{
        dataok=false;
        backtomain()
        console.log('Data tidak Valid')
    }

}


function editvalue(id){
    window.scrollTo(0,0);
    $("#seg_status").val('R')
    // console.log(id)
    aboutvol("mainfreq")
    aboutvol("mainedit")
    var fseg=frqtemp[0].segment;

    var idx=fseg.findIndex(x=>x.id===Number(id));
    fshtemp=fseg[idx]
    var valtemp=fseg[idx].value[0]
    var fsegcur=[];
    var fsh=[];
    if (frq.length > 0){
        fsegcur=frq[0].segment
        idx=fsegcur.findIndex(x=>x.id===Number(id));
        var val='';
        if(idx !== -1){
            fsh=fsegcur[idx]
            val=fsegcur[idx].value[0]
        }
    }
    // console.log(fsh,fshtemp)
    compareisidata(fldse,fshtemp,fsh,'seg');
    compareisidata(fldval,valtemp,val,'val');
    var freal= Airspacefreq(valtemp.freq,valtemp.unit,'DATA')
    $("#freqview").val(freal);
    $("#seg_id").val(fshtemp.id)
    $("#seg_call_sign").val(fshtemp.call_sign)
    

}
function CheckFreqValue(id) {
 var data = $("#"+id).val();
// console.log(data)
    if (data >= 3000) {
        $("#val_unit").val('H')
        data *= 1000
    } else if (data < 3000) {
        if (data >= 1000) {
            data *= 100000
        } else {
            data *= 1000000;
            // console.log(hasil,'VVVVVVVVVVVVV')
        }
        $("#val_unit").val('V')

    }
    data=data.toFixed();
    $("#val_freq").val(data);

    if (data == 121500000) {
        $("#seg_level").val('3')
        Swal.fire(
            'Emergency Frequency!',
            'this is the frequency for emergencies',
            'info'
        )
    }
    // console.log(data)
    if (data == 123100000) {
        $("#seg_level").val('4')
        Swal.fire(
            'SAR Frequency!',
            'this is the frequency for SAR',
            'info'
        )
    }
    var ix = fvlist.findIndex(x=>x.freq===data);
    if (ix==-1){
        $("#val_status").val('N')
        $("#seg_freq_id").val($("#val_unit").val()+'_'+data);
    }else{
        $("#val_status").val('R')
        var fval=fvlist[ix];
        $("#seg_freq_id").val(fval.freq_id);
        
    }
    // console.log( $("#seg_freq_id").val(),$("#val_status").val())

}
function backtomain(){
    window.scrollTo(0,0);
    aboutvol("mainfreq")
    aboutvol("mainedit")
}
function newdata(){
    window.scrollTo(0,0);
    // console.log(frqtemp[0])
    $("#seg_status").val('N')
    $("#seg_call_sign").val(frqtemp[0].id)
    aboutvol("mainfreq")
    aboutvol("mainedit")
}
function remove(id){
    $("#id_delete").val(id)
    console.log(id)

    Swal.fire({
        title: 'Deleted',
        text: "The Frequency will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'

    }).then((result) => {
        if (result.value) {
          
            $('#freqformdelete').submit();
            
        }else{
            location.reload();

        }
    })
}
function backtolist(){
    window.scrollTo(0,0);
    history.back()
}

</script>
@endsection