@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="panel-heading mt-3">
                <h6 class="panel-title">List of PIA Cluster</h6>
            </div>
            <div class="panel-body mt-3" id="mainpia" style="visibility:visible">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark" align="center">
                                <tr>
                                    <button class="btn btn-dim btn-info" v-on:click="NewDatapia('EDIT')"><i class="icon ni ni-plus-circle-fill" align="left" aria-hidden="true"></i> Add</button>
                                    <th>No</th>
                                    <th>Cluster</th>
                                    <th>City</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pia as $idx=>$auth)
                                    <tr>
                                        <td class="tb-tnx-action">
                                            <div class="dropdown">
                                                <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">
                                                    <ul class="link-list-plain">
                                                        <a class="btn btn-dim btn-primary" id="{{ $auth->id }}" onclick="editpia(this.id)"><em class="icon ni ni-pen-fill"></em> Edit</a>
                                                        <a class="btn btn-dim btn-danger" id="{{ $auth->id }}" onclick="remove()"><em class="icon ni ni-delete"></em>Remove</a>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $auth->name }}</td>
                                        <td>{{ $auth->pia }}</td>
                                        <td>{{ $auth->pia_address }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-body mt-3" id="editpia" style="visibility:hidden">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Cluster</strong>
                        <br>
                        <select id="name" class="form-control" name="name">
                            @foreach($pia as $idx=>$auth)
                                <option value="{{ $auth->id }}">{{ $auth->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <!-- <template v-if="volradio=='P'"> -->
                    <div class="col-md-4">
                        <strong>Kota</strong>
                        <br>
                        <input type="text" class="form-control" id="pia" name="pia">
                    </div>
                    <div class="col-md-4">
                        <strong>PIC</strong>
                        <br>
                        <select id="pic_pia" class="form-control" name="pic_pia">
                        
                        </select>
                    </div>
                    <div class="col-md-12">
                        <strong>Alamat</strong>
                        <br>
                        <textarea type="text" class="form-control" id="pia_address" name="pia_address"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <button onclick="backtolist()" class="btn btn-dim btn-primary"><i class="incon ni ni-curve-down-left"></i> Back</button>&nbsp;
                            <button  onclick="update()" class="btn btn-dim btn-success"><i class="icon ni ni-save"></i> Update</button>&nbsp;
                            <button onclick="NewData()" class="btn btn-dim btn-info"><i class="icon ni ni-plus-circle-fill"></i> Add Airport</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12" id="arptlist">
                    
                    </div>
                </div>
            </div>
            <div class="mt-2" id="modalform"></div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
var pia =@json($pia);
var arppia =@json($airport);
var piad='';
$('#editpia').hide();

function editpia(id){
    window.scrollTo(0,0);
    aboutvol('mainpia');
    aboutvol('editpia');
    let idx = pia.findIndex(x => x.id===Number(id));
    piad=pia[idx];
    console.log(pia[idx]);
    $("#name").val(piad.id);
    $("#pia").val(piad.pia);
    $("#pia_address").val(piad.pia_address);
    $("#pic_pia").empty();
    piad.users.forEach(u=>{
        hsl='<option value="'+ u.id +'">'+ u.name + ' ' + u.first_name + ' ' + u.last_name +'</option>';
        $("#pic_pia").append(hsl);
    })
    $("#pic_pia").val(piad.pic_pia);
    var hasil='';$("#arptlist").empty();
    if (piad.airport.length > 0){
        hasil='<table class="datatable-init table table-bordered table-hover" id="table-content">'+
                '<thead class="thead-dark">'+
                    '<tr align="center">'+
                        '<th>No</th>'+
                        '<th>ICAO</th>'+
                        '<th>Name</th>'+
                        '<th>City</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody id="arptload">'+

                '</tbody>'+
           '</table>';
        $("#arptlist").append(hasil);
    }
    var no=1;
    piad.airport.forEach(v=>{
    hasil = '<tr>'+
            '<td>'+no+'</td>'+
            '<td>'+v.icao+'</td>'+
            '<td>'+v.arpt_name+'</td>'+
            '<td>'+v.city_name+'</td>'+
        '</tr>';
    $("#arptload").append(hasil);
        no++;
    });
    // userid
   console.log(id)
}
function backtolist(){
    aboutvol('mainpia');
    aboutvol('editpia');
    // history.back()
}
function modalclose(){
    aboutvol('editpia');
    $("#modalform").empty();
}
function NewData(){
    aboutvol('editpia');
    modal='<div class="modal-dialog-lg" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header bg-gray">'+
                    '<h5 style="cursor:pointer" onclick="modalclose()" class="modal-title text-white">Add Airport to '+piad.name+'</h5>'+
                    '<a onclick="modalclose()" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<em class="icon ni ni-cross"></em>'+
                    '</a>'+
                '</div>'+
                '<div class="modal-body">'+
                    '<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">'+
                    '<div class="row mt-2">'+
                        '<div class="col-md-12">'+
                            '<table class="datatable-init table table-bordered table-hover" id="myTable">'+
                                '<thead class="thead-dark">'+
                                    '<tr align="center">'+
                                        '<th>ICAO</th>'+
                                        '<th>Name</th>'+
                                        '<th>City</th>'+
                                        '<th>Coordinates</th>'+
                                        '<th>PIA</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="listnonpia">'+
                                '</tbody>'+
                            '</table>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="modal-footer bg-light">'+
                    '<span class="sub-text"></span>'+
                '</div>'+
            '</div>'+
        '</div>';

        $("#modalform").append(modal);
        arppia.forEach(v=>{
            var cord=SetCoordinatebyGeom(v.geom);
            var adapia=false;piaid='';masuk=true;colo='#1A34CA';
            if (v.auth.length >0){
                colo='black';
                adapia=true;
                piaid=v.auth[0].id;
                if (v.auth[0].id == piad.id){
                    masuk=false
                }
            }
            if (masuk){
                // console.log(v)
                hasil='<tr>'+
                '<td style="cursor:pointer;color:'+ colo +'"><a id="'+v.arpt_ident+'" onclick="Addairport(this.id)">'+v.icao+'</td>'+
                '<td style="cursor:pointer;color:'+ colo +'"><a id="'+v.arpt_ident+'" onclick="Addairport(this.id)">'+v.arpt_name+'</td>'+
                '<td style="cursor:pointer;color:'+ colo +'"><a id="'+v.arpt_ident+'" onclick="Addairport(this.id)">'+v.city_name+'</td><td>'+cord.WGSAIP[1] +' ' + cord.WGSAIP[0]+'</td>';
                if (adapia == true){
                    hasil +=  '<td style="cursor:pointer;color:'+ colo +'"><a id="'+v.arpt_ident+'" onclick="Addairport(this.id)">'+v.auth[0].name+'</td></tr>';
                }else{
                    hasil += '<td style="cursor:pointer;color:'+ colo +'"><a id="'+v.arpt_ident+'" onclick="Addairport(this.id)"></td></tr>';
                }
                
                
                $("#listnonpia").append(hasil);
                
            }
        });

        window.scrollTo(0,0);
}
function myFunction() {
  // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        // td = tr[i].getElementsByTagName("td");
        td = tr[i].getElementsByTagName("td")[0];
        td1 = tr[i].getElementsByTagName("td")[1];
        td2 = tr[i].getElementsByTagName("td")[2];
        // console.log(td);
        if (td || td1 || td2) {
            txt = td.textContent || td.innerText;
            txt1 = td1.textContent || td1.innerText;
            txt2 = td2.textContent || td2.innerText;
            if (txt.toUpperCase().indexOf(filter) > -1 || txt1.toUpperCase().indexOf(filter) > -1 || txt2.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
function Addairport(id){
    let ix = arppia.findIndex(x => x.arpt_ident===id);
    var garpt=arppia[ix];
    Swal.fire({
        title: 'Are you sure?',
        text: garpt.icao+' '+ garpt.arpt_name +' '+ garpt.city_name + " will be added to " + piad.name,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, add it!'
        }).then((yesno) => {
            if (yesno.value) {
            dtpub={
                _token:"{{ csrf_token() }}",
                auth:piad.id,
            }
            console.log('rawdata update',dtpub);
            $.ajax({
                url: "{{ URL::to('/') }}/api/airport/update/" + id,
                type: "POST",
                data: JSON.stringify(dtpub),
                cache: false,
                contentType: 'application/json; charset=utf-8',
                processData: false,
                success: function (response)
                {
                    Swal.fire(
                        'Updates!',
                        'Airport PIA '+ piad.name +' has been updated.',
                        'success'
                    );
                    location.reload()
                    editpia(piad.id);
                }
            });
            }
        })
    console.log(id)
}
    function removelist(data){
            this.frequpdate = {
                raim: 0,
            }
            Swal.fire({
                title: 'Are you sure?',
                text: data.arpt_name + " will be removed from GPS RAIM list",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, removed it!'
            }).then((yesno) => {
                if (yesno.value) {
                    ApiManager.request('POST', 'airport/update/' + data.arpt_ident, this.frequpdate, (response) => {
                        if (response.isSuccess()) {
                            Swal.fire(
                                'Data updated!',
                                'GPS RAIM data has been updated',
                                'success'
                            )
                            this.loadAirportList()
                        }
                    })
                }
            })
    }
    function addarptraim(){
            // this.modaladd=true;
            // console.log('edit seq data',data)
           var inputOptions = {}; // Define like this!
            this.airportnonList.forEach(cd=>{
                inputOptions[cd.arpt_ident] = cd.icao + '-' + cd.city_name + '/' + cd.name;
            })
            Swal.fire({
                title: "GPS RAIM ",
                text : 'Added GPS RAIM Airport',
                input: 'select',
                inputOptions: inputOptions,
                showCancelButton: true,
            }).then((result) => {
                if (result.value){
                    // console.log(result.value)
                    this.frequpdate = {
                            raim: 1,
                        }
    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "this Airport will be added",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, add!'
                    }).then((yesno) => {
                        if (yesno.value) {
                            // console.log(this.frequpdate,result.value)
                            ApiManager.request('POST', 'airport/update/' + result.value, this.frequpdate, (response) => {
                                if (response.isSuccess()) {
                                    Swal.fire(
                                        'Data updated!',
                                        'GPS RAIM data has been updated',
                                        'success'
                                    )
                                    this.loadAirportList()
                                }
                            })
                        }
                    })
                }

            });
        }


</script>
@endsection
