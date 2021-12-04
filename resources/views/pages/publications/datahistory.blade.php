@extends('layouts.app')

@section('template_title')
    Data History
@endsection

@section('head')
<style>
.container {
  position: relative;
  width: 100%;
  overflow: hidden;
  padding-top: 56.25%; /* 16:9 Aspect Ratio */
}

.responsive-iframe {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  width: 100%;
  height: 400px;
  border: none;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div id="base" class="nk-content-wrapper">
                <div class="panel panel-default">
                    <h6>Data History</h6>
                    <input type="hidden" id="backto" value="{{Session::get('backto')}}">
                </div>
                <div class="row mt" id="listamdt" style="visibility: visible">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>No</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody id="publist">

                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="DataRequest/remove" method="post"  enctype="multipart/form-data" id="dataremove">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="status" id="status">
                </form>
                <div class="row mt" id="listsrc" style="visibility: hidden">
                    <div class="col-md-12">
                        <table class="datatable-init table table-bordered table-hover" id="table-content_chart">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>No</th>
                                    <th>ICAO</th>
                                    <th>Chart</th>
                                    <th>Effective Date</th>
                                </tr>
                            </thead>
                            <tbody id="chartlist">

                            </tbody>
                        </table>
                    </div>
                    <div class="card-tools mt-3">
                        <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    

@endsection
@section('footer_scripts')
<script src="{{ asset('template/assets/js/v-modal.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    $.wmBox();
});
    $("#listsrc").hide();
var src =@json($source);listofchart=[];
// console.log(src)
var no=1;
src.forEach(s=>{
    var sid=s.nr_yr.replace(/ /g,"_");
    hasil = '<tr class="nk-tb-item">'+
                '<td style="cursor:pointer" id='+sid+' onclick="listdata(this.id)">'+no+'</td>'+
                '<td style="cursor:pointer" id='+sid+' onclick="listdata(this.id)">'+s.nr_yr+'</td>'+
            '</tr>';
        $("#publist").append(hasil);
        no++

})
function listdata(id){
    var sid=id.replace(/_/g," ");nn=1;
    console.log(sid,$("#listsrc").is(':visible'))
    if ($("#listsrc").is(':visible')==false){
        aboutvol('listsrc');
    }
    if ($("#listamdt").is(':visible')==true){
        aboutvol('listamdt');
    }
    // console.log(sid,$("#listsrc").is(':visible'))
    // aboutvol("listamdt");
    $("#chartlist").empty();
    //  $data['lstchart'] = getDataApi($originalInput, 'api/eaip/aip?id_aip_induk='.$idkode.'&is_active=1&sort=no_urut:asc');
    $.ajax({
                url: '../api/eaip/aip',
                data: {'nr_yr' : sid},
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        if (v.kd_aip_type=='2'){
                           
                            listofchart.push(v)
                            if (v.url_file !== null){
                                var fl = v.url_file.replace('images/','');  //upload/publication/aip/
                                var filepath = '/upload/publication/aip/'+fl;
                                
                                // console.log(filepath)
                                hasil = '<tr class="nk-tb-item">'+
                                        '<td style="cursor:pointer" id="'+ filepath +'" onclick="showachart(this.id)">'+nn+'</td>'+
                                        '<td style="cursor:pointer" id="'+ filepath +'" onclick="showachart(this.id)">'+v.icao_code+'</td>'+
                                        '<td style="cursor:pointer" id="'+ filepath +'" onclick="showachart(this.id)">'+v.name+'</td>'+
                                        '<td style="cursor:pointer" id="'+ filepath +'" onclick="showachart(this.id)">'+v.affected+'</td>'+
                                    '</tr>';
                                $("#chartlist").append(hasil);
                                nn++
                            }

                        }
                    })
                }
        })

}
function showachart(id){
    console.log(id);
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    window.open("{{URL::to('/')}}" + id, 'airportcontent', params)
}
function backtolist(){
    if ($("#listsrc").is(':visible')==true){
        $("#chartlist").empty();
        aboutvol('listsrc');
    }
    if ($("#listamdt").is(':visible')==false){
        aboutvol('listamdt');
    }
}
</script>
@endsection