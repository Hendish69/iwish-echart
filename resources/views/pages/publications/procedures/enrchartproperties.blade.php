@extends('layouts.app')

@section('template_title')
    Charts
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body mt-3">
            <div class="nk-block-between">
                <h5 class="panel-title" id="titleholding">Chart Properties</h5>
            </div>
            <div class="panel-heading col-md-12 mt-3">
                <button onclick="backtomenu()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
            </div>
            <div class="row mt">
                <div class="col-md-12 mt-3">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th>No</th>
                                <th>Chart</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody id="holdinglist">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-body mt-3">
                <form action="../api/frameenr/save" method="post"  enctype="multipart/form-data" id="holdingremove">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="chart_id" id="chart_id">
                    <input type="hidden" name="status" id="save_status">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
var charts =@json($chart);cod=@json($cod);tbl=@json($tbl);chtid='';

    $("#titleholding").html('En-Routes Charts')
    chtid='enr';



// var fld=['bm_id','chart_id','chart_name','chart_type','customer','footer','msa_id','sn','seq','rwy','nav','cat','page','rnav','remarks'];
var fld=['bm_id','chart_id','chart_name','chart_type','customer','footer','msa_id','sn','seq','rwy','nav','cat','page','rnav'];

function isbacktomain(){
    // console.log('tab')
    $('#tabMenu a[href="#tabItem1"]').tab('show');
    
}
// console.log(arptfreq,freq)



no=1;
// console.log(charts);
charts.sort((a,b) => (a.chart_type > b.chart_type) ? 1 : ((b.chart_type > a.chart_type) ? -1 : 0));
    charts.forEach(v=>{
        
        var gc=cod.find(c=>c.id===Number(v.chart_type)).definition
        var trn=v.rwy;crs=v.chart_id;chnm=v.chart_name
        if (v.aip.length >0){
            chnm=v.aip[0].chart_name
        }
        if (trn==null){
            trn='';
        }
        var  hsl= '<tr>'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-primary col-md-12" id='+ v.id +' onclick="EditChart(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                            '<a class="btn btn-dim btn-info col-md-12" id='+ v.id +' onclick="viewchart(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                            '<a class="btn btn-dim btn-danger col-md-12" id='+ v.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                        '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ no +'</td>'+
            '<td>'+ gc +'</td>'+
            '<td>'+ chnm +'</td>'+
        '</tr>';
        $("#holdinglist").append(hsl);
        no++
    });

  





function EditChart(id){
    console.log(id)
    window.location.href = '/chartprop/edit/' + id + '/' + chtid ;
}

function NewFrameData(){
    window.location.href = '/chartframe/edit/new/' + chtid ;
}
function NewData(){
    window.location.href = '/chartprop/edit/new/' + chtid ;
    
}



function backtomenu(){
    window.location.href = '/listairport/chartprop';
}
function remove(id){
    console.log(id)
    $("#id").val(id) 
    $("#save_status").val('D')
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
            $("#holdingremove").submit();
        }else{
            location.reload();
        }
    })
}
function viewframe(id=null){
    var idcht='';
    if (id==null){
        var chrtid=$('#bm_id').val();
        idcht=bm.find(x=>x.chart_id===chrtid).id;

    }else{
        idcht=id;
    }
    console.log(idcht)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=frame&id='+idcht, 'Set Latitude and Longitude', params)
}

function viewchart(id){
    // var ix =charts.findIndex(x=>x.id==Number(id));
    // console.log(charts[ix],id)
    
    // // var idcht='';
    // // if (id==null){
    // //     var chrtid=$('#bm_id').val();
    // //     idcht=bm.find(x=>x.chart_id===chrtid).id;

    // // }else{
    // //     idcht=id;
    // // }
    // console.log(idcht)
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=chart&id='+id, 'Set Latitude and Longitude', params)
}



</script>
@endsection