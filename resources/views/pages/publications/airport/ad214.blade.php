@extends('layouts.app')

@section('template_title')
    AD 2.14
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
        <!-- </div>
        <div class="panel-heading mt-3" id="backid" style="visibility: visible"> -->
            <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
        </div>
        <div class="panel-body mt-3" id="rwyeditform" style="visibility: hidden">
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
                        <form action="../api/rwy/lgt/save" method="post"  enctype="multipart/form-data" id="lowthr">
                            <input type="hidden" name="_token" id="low_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="low_editor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="rwy_id" id="low_rwy_id">
                            <input type="hidden" name="id" id="low_id">
                            <input type="hidden" name="arpt_ident" id="low_arpt_ident">
                            <input type="hidden" name="status" id="low_status">
                            <div class="row table-bordered">
                                <div class="row g-gs col-md-12">
                                    <div class="col-md-6">
                                        <strong>APCH LGT type LEN INTST</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_apch_lgt_type_len" name="apch_lgt_type_len"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>THR LGT colour WBAR</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_thr_lgt_clr_wbar" name="thr_lgt_clr_wbar"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>VASIS (MEHT) PAPI </strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_vasis_meht_papi" name="vasis_meht_papi"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>TDZ, LGT LEN</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_tdz_lgt_len" name="tdz_lgt_len"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>RWY Centre Line LGT LEN, spacing,colour, INTST</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_rwy_ctrln_lgt_length_spc_clr" name="rwy_ctrln_lgt_length_spc_clr"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>RWY Edge LGT LEN, spacing colour INTST</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_rwy_edge_lgt_len_spc_clr" name="rwy_edge_lgt_len_spc_clr"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>RWY End LGT colour WBAR</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_rwy_end_lgt_clr_wbar" name="rwy_end_lgt_clr_wbar"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>SWY LGT LEN (M) Colour</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_swy_lgt_len_clr" name="swy_lgt_len_clr"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Remarks</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="low_remark" name="remark"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane"  id="tabItem2">
                        <form action="../api/rwy/lgt/save" method="post"  enctype="multipart/form-data" id="highthr">
                            <input type="hidden" name="_token" id="high_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="editor" id="high_editor" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" id="high_id">
                            <input type="hidden" name="rwy_id" id="high_rwy_id">
                            <input type="hidden" name="arpt_ident" id="high_arpt_ident">
                            <input type="hidden" name="status" id="high_status">
                            <div class="row table-bordered">
                                <div class="row g-gs col-md-12">
                                    <div class="col-md-6">
                                        <strong>APCH LGT type LEN INTST</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_apch_lgt_type_len" name="apch_lgt_type_len"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>THR LGT colour WBAR</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_thr_lgt_clr_wbar" name="thr_lgt_clr_wbar"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>VASIS (MEHT) PAPI </strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_vasis_meht_papi" name="vasis_meht_papi"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>TDZ, LGT LEN</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_tdz_lgt_len" name="tdz_lgt_len"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>RWY Centre Line LGT LEN, spacing,colour, INTST</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_rwy_ctrln_lgt_length_spc_clr" name="rwy_ctrln_lgt_length_spc_clr"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>RWY Edge LGT LEN, spacing colour INTST</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_rwy_edge_lgt_len_spc_clr" name="rwy_edge_lgt_len_spc_clr"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>RWY End LGT colour WBAR</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_rwy_end_lgt_clr_wbar" name="rwy_end_lgt_clr_wbar"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>SWY LGT LEN (M) Colour</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_swy_lgt_len_clr" name="swy_lgt_len_clr"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Remarks</strong>
                                        <br />
                                        <textarea type="text" class="form-control" id="high_remark" name="remark"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel-heading mt-3" id="backlist" style="visibility: visible">
                <button onclick="backtomain()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                &nbsp;
                <button type="submit" id="btn_update" class="btn btn-sm btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Update</button>
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
$('#rwyeditform').hide();
var arpt =@json($airport);arp=arpt[0];
var no=0; rtemp=[];thrlow=[];thrhigh=[];
var ttl=@json($judul);
// console.log(a)
var ad2811='Apron'
var editform=false;editps=false;

var rwyfiled=['id','rwy_id', 'apch_lgt_type_len', 'thr_lgt_clr_wbar', 'vasis_meht_papi','tdz_lgt_len', 'rwy_ctrln_lgt_length_spc_clr', 'rwy_edge_lgt_len_spc_clr','rwy_end_lgt_clr_wbar', 'swy_lgt_len_clr', 'remark'];
var rwyfiledup=['apch_lgt_type_len', 'thr_lgt_clr_wbar', 'vasis_meht_papi','tdz_lgt_len', 'rwy_ctrln_lgt_length_spc_clr', 'rwy_edge_lgt_len_spc_clr','rwy_end_lgt_clr_wbar', 'swy_lgt_len_clr', 'remark'];


$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);
$("#low_arpt_ident").val(arp.arpt_ident);
$("#high_arpt_ident").val(arp.arpt_ident);

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
                        '<li><a class="btn btn-dim btn-danger" onclick="remove(twy)"><i class="icon ni ni-delete"></i>Delete</a></li>'+
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
})



    
$('#btn_update').click(function() {
    var rwyfilededit=['rwy_id', 'apch_lgt_type_len', 'thr_lgt_clr_wbar', 'vasis_meht_papi','tdz_lgt_len', 'rwy_ctrln_lgt_length_spc_clr', 'rwy_edge_lgt_len_spc_clr','rwy_end_lgt_clr_wbar', 'swy_lgt_len_clr', 'remark'];
    let exec_low = checkupdatedata(rwyfilededit,thrlow,'low');
    if (exec_low===true){
        $('#lowthr').submit();
        console.log('update data low_form');
        // console.dir($('#lowthr').serialize()); 
        $.ajax({
            url: '../api/rwy/lgt/save',
            type: 'post',
            data:$('#lowthr').serialize(),
            success:function(retn){
                // console.dir(retn);
            }
        }); 
    }
    let exec_high = checkupdatedata(rwyfilededit,thrhigh,'high');
    if (exec_high===true){
        // console.log('HIGH UPDATE')
        $('#highthr').submit();
        console.log('update data high_form'); 
        $.ajax({
            url: '../api/rwy/lgt/save',
            type: 'post',
            data:$('#highthr').serialize(),
            success:function(retn){
                // console.dir(retn);
            }
        }); 

    }
    // window.scrollTo(0,0);
    // window.location.href="{{ url('aipedit') }}/214" +"/" + arp.arpt_ident;
})


function rwyedit(id){
    aboutvol('rwyeditform');
    aboutvol('rwytable');
    var idx= arp.runwaystemp.findIndex( x => x.rwy_id ===id );
    rtemp=arp.runwaystemp[idx];
    var idx= arp.runways.findIndex( x => x.rwy_id ===id );
    var rwy=arp.runways[idx];
    // console.log(rwy,rtemp)
   
    $("#rwystatus").val('R')
    $("#low_status").val('R')
    $("#high_status").val('R')
    $("#arpt_ident").val(arp.arpt_ident)
    $("#high_rwy_id").val(rtemp.physicals[1].rwy_key)
    $("#low_rwy_id").val(rtemp.physicals[0].rwy_key)
    //runway ident
    $("#thrlow").html('THR ' + rtemp.thr_low);
    $("#thrhigh").html('THR ' + rtemp.thr_high);
    //thr low
    for (let i=0;i<2;i++){
        if (rtemp.physicals[i].lighting.length == 0){
            if (i==0){
                $("#low_status").val('N')
            }else{
                $("#high_status").val('N')
            }
            
        }else{
            
            var thrtemp=rtemp.physicals[i].lighting[0];
            var currthr=rwy.physicals[i].lighting[0];
            // console.log('TEMP',thrtemp,'CURR',currthr)
            if (i==0){
                thrlow=thrtemp;
                compareisidata(rwyfiled,thrtemp,currthr,'low')
            }else{
                thrhigh=thrtemp;
                compareisidata(rwyfiled,thrtemp,currthr,'high')
            }
        }
    }
   
    

}


function backtomain(){
    window.scroll(0,0);
    aboutvol('rwyeditform');
    aboutvol('rwytable');
}

function backtolist(){
    window.location.href="{{url('/')}}/editairport/"+ arp.arpt_ident;
}

</script>
@endsection