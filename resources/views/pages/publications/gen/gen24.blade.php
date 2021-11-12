@extends('layouts.app')

@section('template_title')
    GEN 2.4
@endsection

@section('head')
<style>
    .text-center {
    text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="panel-heading mt-3">
                <h5 class="panel-title text-center">GEN 2.4 LOCATION INDICATORS</h5>
            </div>
            <div class="panel-heading mt-3">
                <h6 class="panel-title text-center" style="color:red" id="infoid"></h6>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button onclick="backtomenu()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                </div>
            </div>
            <div class="row mt-3" id="datalistarpt" style="visibility: visible">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-dim btn-info mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</button>
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>#</th>
                                <th>Indicator</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody id="arptlist">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="datanewarpt" style="visibility: hidden">
                <div class="panel-heading mt-1">
                    <h6 class="panel-title"></h6>
                </div>
                <div class="panel-body">
                <form action="api/gen/locindicator/save" method="POST" id="formulir">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="loc_id" id="loc_id">
                <input type="hidden" name="loc_arptident" id="loc_arptident">
                <input type="hidden" name="status" id="status">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>Facility</strong>
                            <br>
                            <select class="form-control"  name="tbl" id="tbl">
                                <option  value="ARPT">Airport</option>
                                <option  value="ASP">Airspace</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Indicator</strong>
                            <br>
                            <input id="indicator" onkeyup="this.value = this.value.toUpperCase();" style="text-transform: uppercase" type="text" onfocusout="Checkdouble(this.id)" maxlength="4" class="form-control" name="indicator">
                        </div>
                        <div class="col-md-4">
                            <strong id="jdlloc">City</strong>
                            <br>
                            <input id="city" onkeyup="this.value = this.value.toUpperCase();" style="text-transform: uppercase" type="text" class="form-control" name="city">
                        </div>
                        <div class="col-md-4" id="nameloc">
                            <strong>Name</strong>
                            <br>
                            <input id="name" type="text" style="text-transform: uppercase" class="form-control" name="name">
                        </div>
                        <div class="col-md-12">
                            <strong>Country</strong>
                            <br>
                            <select selected="selected" class="form-control" id="ctry" name="ctry">
                            @foreach($countries as $index => $ctry)
                                <option value="{{$ctry->ident}}"> {{$ctry->country }} </option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div>
                </form>
                    <div class="row">
                        <div class="col-md-6">
                            <button onclick="isback()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                            &nbsp;
                            <button id="btn_formulir" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$("#datanewarpt").hide();
var indtemp =@json($indicatortemp);
var indic =@json($indicator);
var loc=[];fld= ['loc_arptident','tbl','indicator', 'city', 'name', 'ctry'];
// console.log(indtemp)
// var cord=SetCoordinatebyGeom(arpt[0].geom)
// var arptident=a.arpt_ident;
var onreq=@json($onrequest);subid=@json($subid);
console.log(onreq)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)

indtemp.forEach(loc=>{
// console.log(loc.indicator.length,loc.indicator)
    if (loc.indicator.length == 4 ){
        var location='';
        if (loc.name==null){
            location=loc.city 
        }else{
            location=loc.city + '/' + loc.name
        }
        // console.log(loc.id)
        var  hsl= '<tr>'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">';
                        if (showedit==true){
                            hsl +=   '<a class="btn btn-dim btn-primary col-md-12" id='+ loc.loc_id +' onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                            '<a class="btn btn-dim btn-danger col-md-12" id='+ loc.loc_id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Removed</a>';

                        }
                        hsl +='</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ loc.indicator +'</td>'+
            '<td>'+ location +'</td>'+
        '</tr>';
        $("#arptlist").append(hsl)
    }
})
function backtomenu(){
    window.location.href = '/aipsubmission/edit';
}

function edit(data) {
    console.log(data)
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");
   
    var ix=indtemp.findIndex(x=>x.loc_id===Number(data));
    loc = indtemp[ix];
    var iix=indic.findIndex(x=>x.loc_id===Number(data));
    var loccurr=[];
    if (iix !==-1){

        loccurr=indic[iix];
    }
    console.log(loc,loccurr);
    $("#loc_id").val(loc.loc_id);
    $("#status").val('R');
    compareisidata(fld,loc,loccurr);
    if ($("#tbl").val()=='ARPT'){
        $("#jdlloc").html('City')
    }else{
        $("#jdlloc").html('Location')
        $("#nameloc").hide()
    }
    window.scrollTo(0,0);
    // $("#indicator").val(loc.indicator);
    // $("#city").val(loc.city);
    // $("#name").val(loc.name);
    // $("#ctry").val(loc.ctry);
}
function NewData(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");
    $("#status").val('N');

    
}
function remove(id){
    // console.log(id)
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
                        url: 'api/gen/locindicator/remove/' + id,
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
function isback(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");

}
function Checkdouble(id){
    var val=$("#"+id).val().toUpperCase();
    console.log(id)
    var hsl=''
    switch (id) {
        case 'indicator':
            hsl= indic.findIndex( x => x.indicator === val )
            break;
        case 'name':
            hsl= indic.findIndex( x => x.name === val )
            break;
 
    }
    if (hsl !== -1 && val !== ''){
        console.log(arpt[hsl])
        $("#"+id).val('');
        $("#"+id).focus();
        Swal.fire(
            'Data Double',
            'The data already exists '+ arpt[hsl].name + ' Airport ' + arpt[hsl].city ,
            'info'
            )
       
    }
   

    // console.log(val,hsl)
}
$('#btn_formulir').click(function() {
    var checkrwy=false;
    changetouppercase(fld);
    if ($("#city").val()==''){
        Swal.fire(
            'Incomplete data',
            'please complete the data first' ,
            'info'
            )
    }else{
        checkrwy =checkupdatedata(fld,loc);
        if (checkrwy==true){

            $('#formulir').submit();
        }else{
            Swal.fire(
                'No Data change',
                'Your data has been updated.',
                'success'
                )
            location.reload();
        }
    }
       });

    // this.isList=false;
    // this.authlist=pia;
    // this.arpttype=atypes;





</script>
@endsection