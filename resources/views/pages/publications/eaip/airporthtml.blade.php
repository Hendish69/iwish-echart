@extends('layouts.app')

@section('template_title')
    Airports
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
    @if ($id=='html')
    <a class="btn btn-dim btn-secondary mt-2" onclick="backtolist()"><i class="icon ni ni-reply-fill"></i> Back</a>
    @endif
        <div class="nk-content-body mt-3">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Airport</h3>
                    </div>
                </div>
            </div>
            <div class="row mt">
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>ICAO</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody id="arptlist">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
var arpt =@json($airport);
var cod_id =@json($id);

var airports = [];
    arpt.forEach(v=>{
        // if (v.geom !== null){
            // var cord=SetCoordinatebyGeom(v.geom)
    hasil = '<tr class="nk-tb-item">'+
            '<td style="cursor:pointer" id='+v.arpt_ident+' onclick="createhtml(this.id)">'+v.icao+'</td>'+
            '<td style="cursor:pointer" id='+v.arpt_ident+' onclick="createhtml(this.id)">'+v.arpt_name+'</td>'+
            '<td style="cursor:pointer" id='+v.arpt_ident+' onclick="createhtml(this.id)">'+v.city_name+'</td>'+
            '<td style="cursor:pointer" id='+v.arpt_ident+' onclick="createhtml(this.id)"> VOL '+v.vol+'</td>'+
        '</tr>';
    $("#arptlist").append(hasil);
            
        // }
    });

function createhtml(id) {
    window.scrollTo(0,0);
    switch (cod_id) {
        case 'html':
            window.location.href = '/airportinfo/' + id + '@html' ;
            break;
        case 'iac':
            window.location.href = '/procedure/' + id + '/45';
            break;
        case 'sid':
            window.location.href = '/procedure/' + id + '/46';
            break;
        case 'star':
            window.location.href = '/procedure/' + id + '/47';
            break;
        case 'holding':
            window.location.href = '/holding/' + id + '@edit' ;
            break;
        case 'msa':
            window.location.href = '/msa/' + id ;
            break;
        case 'chartprop':
            window.location.href = '/chartprop/' + id +'/pro';
            break;
        case 'frame':
            window.location.href = '/chartprop/' + id +'/frm';
            break;
        case 'adc':
        case 'aoc':
            window.location.href = '/aoc/' + id +'/'+cod_id;
            break;
        default:
            break;
    }

}
function backtolist(){
    window.location.href="{{url('/')}}/electronicaip";

}



</script>
@endsection