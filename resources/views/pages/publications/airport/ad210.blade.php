@extends('layouts.app')

@section('template_title')
    AD 2.10
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title" id="contentitle"></h6>
        </div>
        <div id="mainobst" style="visibilty:visible">
            <div class="panel-body mt-3">
                <div class="row mt-1">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr>
                                    <th <button class="btn btn-dim btn-light" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" style="text-align:center" aria-hidden="true"></i> Add</button></th>
                                    <th style="text-align:center">No</th>
                                    <th style="text-align:center">Types</th>
                                    <th style="text-align:center">Lighted</th>
                                    <th style="text-align:center">Elev</th>
                                    <th style="text-align:center">Height</th>
                                    <th style="text-align:center">Position</th>
                                    <th style="text-align:center">Coordinates</th>
                                    <!-- <th style="text-align:center">Notes</th> -->
                                </tr>
                            </thead>
                            <tbody id="obslist">
                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-heading mt-3" id="backid" style="visibility: visible">
                <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                &nbsp;
                <button onclick="setMapPoint()" class="btn btn-sm btn-dim btn-success"><i class="icon ni ni-map"></i> Show</button>
            </div>
        </div>
        <div class="col-md-12 mt-3" id="editobst" style="visibility:hidden">
            <form action="api/eaip/obstacle/save" method="POST" id="formulir">
            <div class="row">
                @csrf
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="geom" id="geom">
                <input type="hidden" name="arpt_ident" id="arpt_ident">
                <div class="col-md-4">
                    <strong>Obstacle Type</strong>
                    <br>
                    <select selected="selected" class="form-control" id="obs_type" name="obs_type">
                        @foreach($cod as $cd)
                            <option value="{{$cd->id}}">{{ $cd->definition }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <strong>Elevation (ft)</strong>
                    <br>
                    <input type="number" class="form-control" id="elev_ft" name="elev_ft">
                </div>
                    <div class="col-md-2">
                    <strong>Height (ft)</strong>
                    <br>
                    <input type="number" class="form-control" id="hgt" name="hgt">
                </div>
                <div class="col-md-2">
                    <strong>Lighted</strong>
                    <br>
                    <select selected="selected" class="form-control" id="lighted" name="lighted">
                        <option value="Y">YES</option>
                        <option value="N">NO</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <strong>Group</strong>
                    <br>
                    <select selected="selected" class="form-control" id="obs_group" name="obs_group">
                        <option value="Y">YES</option>
                        <option value="N">NO</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <strong>Position</strong>
                    <br>
                    <select selected="selected" class="form-control" id="position" name="position">
                        <option value="In Area 2">In Area 2</option>
                        <option value="In Area 3">In Area 3</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <strong>Latitude</strong>
                    <br>
                    <input id="latitude" name="latitude" style="text-transform:uppercase" onfocusout="CheckCoordinateFormat(this.id,'LAT')" maxlength="9" type="text" class="form-control" placeholder="06300000S">
                </div>
                <div class="col-md-2">
                    <strong>Longitude</strong>
                    <br>
                    <input id="longitude" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('latitude','longitude');checkdouble()" name="longitude" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" placeholder="106300000E">
                </div>
                <div class="col-md-12">
                    <strong>Remarks</strong>
                    <br>
                    <textarea type="text" class="form-control" id="notes" name="notes"></textarea>
                </div>
            </div>
            </form>
                <div class="card-inner col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <button onclick="backtomain()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                            &nbsp;
                            <button id="btn_formulir" class="btn btn-dim btn-dark"></button>
                            
                        </div>
                        <div class="col-md-6" align="right">
                            <i style="color:red" align="right">RED Color = Data change request</i>
                            <br>
                            <i style="color:darkgrey" id="arptidname"></i>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$('#editobst').hide();

var arpt =@json($airport);arp=arpt[0];
var obs =@json($obstacles);cod=@json($cod);
var obscurr =@json($obstaclescurrent);
// console.log(obs,obscurr);
var fld=['id','arpt_ident','obs_type', 'lighted', 'obs_group', 'elev_ft','hgt','position', 'notes','latitude','longitude']
var no=0;
obs.forEach(ob=>{
    no++;
    // console.log(a);
    var hgt=ob.hgt;elev=ob.elev_ft;posisi=ob.position;notes=ob.notes;
    if (hgt==null){
        hgt='NIL';
    }
    if (elev==null){
        elev='NIL';
    }
    if (posisi==null || posisi==''){
        posisi='NIL';
    }
    if (notes==null || notes==''){
        notes='NIL';
    }
    var cord=SetCoordinatebyGeom(ob.geom)
    hasil='<tr>'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-xs">'+
                        '<ul class="link-list-plain">'+
                            '<li><a class="btn btn-dim btn-dark" id="'+ ob.id +'" onclick="obstedit(this.id)"><i class="icon ni ni-edit"></i> Edit</a></li>'+
                            '<li><a class="btn btn-dim btn-danger" id="'+ ob.id +'" onclick="remove(this.id)"><i class="icon ni ni-delete"></i>Delete</a></li>'+
                        '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+no+'</td>'+
            '<td>'+ob.definition+'</td>'+
            '<td>'+ob.lighted+'</td>'+
            '<td>'+elev+'</td>'+
            '<td>'+hgt+'</td>'+
            '<td>'+posisi+'</td>'+
            '<td>'+cord.WGS[1] + ' ' + cord.WGS[0] +'</td>'+
            // '<td>'+notes +'</td>'+
        '</tr>';
        $("#obslist").append(hasil);
})
$('#btn_formulir').click(function() {
    $('#formulir').submit();
});
// console.log(aprons);
// console.log(twy);
// console.log(ps);
// console.log(pb);
var editform=false;editps=false;
var ttl=arp.icao + ' AD 2.10 AERODROME OBSTACLES';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);

function setMapPoint() {
    this.url = '/map.php?table=obstacle&id=' + arp.arpt_ident
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open(this.url, 'Set Latitude and Longitude', params)
}
function obstedit(id){
    aboutvol("mainobst");
    aboutvol("editobst");
    $("#btn_formulir").html('<i class="icon ni ni-save-fill"></i> Update')
    idx= obs.findIndex( x => x.id === Number(id) );
    var ob=obs[idx];
    var cord=SetCoordinatebyGeom(ob.geom)
    ob['latitude']=cord.Database[1];
    ob['longitude']=cord.Database[0];
    var curr=[];
    curr['latitude']='';
    curr['longitude']='';
    if (obscurr.length > 0){
        idx= obscurr.findIndex( x => x.id === Number(id) );
        curr=obscurr[idx];
        cord=SetCoordinatebyGeom(curr.geom)
        curr['latitude']=cord.Database[1];
        curr['longitude']=cord.Database[0];
    }
    $("#status").val('R');
    compareisidata(fld,ob,curr);

    window.scrollTo(0,0);
}
function NewData(){
    aboutvol("mainobst");
    aboutvol("editobst");
    $("#btn_formulir").html('<i class="icon ni ni-save-fill"></i> Save')
    $("#arpt_ident").val(arp.arpt_ident);
    $("#status").val('N');

}
function checkdouble(){
    var lat=$("#latitude").val();
    var lon=$("#longitude").val();
    
   
}
function remove(id){
    // console.log(id)
    dtsrcraw={
        _token:"{{ csrf_token() }}",
        deleted:'1',
    }

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
            var rawid=''
            // console.log('Berhasil masuk YES ', "{{ URL::to('/') }}/DataRequest/save", id,update,rawdata)
            $.ajax({
                url:  "api/eaip/obstacle/remove/" + id, //'/DataRequest/save',
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
                    location.reload();
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
    aboutvol("mainobst");
    aboutvol("editobst");
}



</script>
@endsection