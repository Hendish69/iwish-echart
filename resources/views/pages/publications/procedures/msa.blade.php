@extends('layouts.app')

@section('template_title')
    MSA
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body mt-3">
            <div class="row mt" id="msalist" style="visibility: visible">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5>Minimum Sector Altitude (MSA)</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th>Fix Point</th>
                                <th>Radius</th>
                            </tr>
                        </thead>
                        <tbody id="holdinglist">

                        </tbody>
                    </table>
                </div>
            </div>
            <div id="msadetail" style="visibility: hidden">
                <div class="nk-block-between">
                    <h5 class="panel-title" id="msaedit">MSA</h5>
                </div>
                <div class="panel-body mt-3">
                    <form action="api/msa/save" method="post"  enctype="multipart/form-data" id="msa_form">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="msa_id" id="msa_id">
                        <input type="hidden" name="ident" id="ident">
                        <input type="hidden" name="nav_id" id="nav_id">
                        <input type="hidden" name="arpt_ident" id="arpt_ident">
                        <input type="hidden" name="wpt_id" id="wpt_id">
                        <input type="hidden" name="center_id" id="center_id">
                        <input type="hidden" name="cen_lat" id="cen_lat">
                        <input type="hidden" name="cen_lon" id="cen_lon">
                        <input type="hidden" name="status" id="status">
                        <input type="hidden" name="remove" id="remove">
                        <div class="card-inner table-bordered mt-1">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Ref. Fix Point</strong>
                                    <br>
                                    <select id="refpoint" onchange="changerefpoint(this)" class="form-control" >
                                    
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <strong>Fix Point</strong>
                                    <br>
                                    <p id="fixpoint"></p>
                                </div>
                                <div class="col-md-12" id="search1" style="visibility: hidden">
                                    <select name="select2" id="select11" class="form-control select2">
                                </div>
                                <div class="col-md-6" style="visibility: hidden">
                                    <strong>ID</strong>
                                    <br>
                                    <input style="visibility: hidden" type="text" class="form-control"/>
                                </div>
                                <div class="col-md-2">
                                    <strong>MSA Radius (nm)</strong>
                                    <br>
                                    <input id="rad" type="number" class="form-control" name="rad">
                                </div>
                                <div class="col-md-2">
                                    <strong>Amount of Area</strong>
                                    <br>
                                    <input id="jumlaharea" name="jumlaharea" onfocusout="Changearea()" type="number" class="form-control">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <table class="table table-bordered table-hover" id="table-area">
                                        <thead class="thead-dark">
                                            <tr align="center">
                                                <th>#</th>
                                                <th>Area</th>
                                                <th>Altitude</th>
                                                <th>Radial 1</th>
                                                <th>Radial 2</th>
                                                <th>Radius</th>
                                            </tr>
                                        </thead>
                                        <tbody id="msaarealist">

                                        </tbody>
                                    </table>
                                </div>
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
                            <button onclick="isback()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                            &nbsp;
                            <button onclick=savemsa() id="btn_save" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3" id="charttable" style="visibility: hidden">
                        <h6>Used in Charts</h6>
                        <table class="table table-bordered table-hover" id="table-area">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th>No</th>
                                    <th>Chart</th>
                                </tr>
                            </thead>
                            <tbody id="msaused">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$("#msadetail").hide();$("#search1").hide();$("#charttable").hide();
var msamain = ['id','msa_id','ident','nav_id','arpt_ident','wpt_id','rad'];
var msaarea = ['id','msa_area_id','msa_id','area','alt','geom'];
var msaseg = ['id','msa_area_id','seq','center_id','bearing','radius','shap','bearing1'];
var area=[];mseg=[];msaareaid='';msaid='';jumlahrow=0;
var msa =@json($msa);
var Refpoint= [{
    id: 'ARP',
    definition: 'Airport'
},{
    id: 'NAV',
    definition: 'Navaid'
},{
    id: 'WPT',
    definition: 'Waypoint'
}];
var shap=[{
    id: 'C',
    definition: 'CIRCLE'
},{
    id: 'R',
    definition: 'CLOCKWISE ARC'
},{
    id: 'P',
    definition: 'LINE'
}];

$('#fixpoint').html(Symbolnewpoint('Navaid','spoint1'))
Refpoint.forEach(t=>{
    var rrf='<option value="'+t.id+'">'+ t.definition +'</option>';
    $("#refpoint").append(rrf);
});
shap.forEach(t=>{
    var rrf='<option value="'+t.id+'">'+ t.definition +'</option>';
    $("#shap").append(rrf);
});
msa.forEach(v=>{
    // console.log(v)
    var  hsl= '<tr v-bind:key="arp.arpt_ident">'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-primary col-md-12" id='+ v.id +' onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                            '<a class="btn btn-dim btn-info col-md-12" id='+ v.id +' onclick="setMapPoint(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                            '<a class="btn btn-dim btn-danger col-md-12" id='+ v.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                            '<a class="btn btn-dim btn-info col-md-12" id='+ v.id +' onclick="showfpl(this.id)"><i class="icon ni ni-map"></i> SHOW FPL</a>'+
                        '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ v.ident + ' MSA ('+ v.fix_point  +')</td>'+
            '<td>'+ v.rad +'</td>'+
        '</tr>';
        $("#holdinglist").append(hsl)
        
    });
function isback(){
    aboutvol("msalist");aboutvol("msadetail");
    $("td:nth-of-type(2)").show();
}
function NewData(){
    aboutvol("msalist");aboutvol("msadetail");
    $("#rad").val('25');
    $("#status").val('N');
    

}
function Changearea(){
    var jml= $("#jumlaharea").val();
    var sts= $("#status").val();
    if (jumlahrow  < jml ){
        if (jml==1 || sts=='N'){
            createtblarea();
        }else{
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change the number of area!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.value) {
                    createtblarea();
                }else{
                    location.reload();
                }
            })
        }

        }
   
}
function savemsa(){

    $("#msa_form").submit();
}
function maparea(id){
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=msaarea&id='+id, 'Set Latitude and Longitude', params)
}


function createtblarea(){

    var jml= $("#jumlaharea").val();
    var no=1;
    var mms = $("#msa_id").val();
    var brg1='';brg2='';
    if (jml==1){
        brg1='0';brg2='0';
    }

    for (let index = jumlahrow; index < jml; index++) {
        no=index+1
        var  hsl= '<tr v-bind:key="arp.arpt_ident">'+
            '<td></td>'+
            '<td><input type="hidden" id= "msa_area_id_'+ no +'"  name="msa_area_id_'+ no +'" value="'+ mms + '_AREA_'+no+'"/></td>'+
            '<td><input type="text" id= "area_'+ no +'"  name="area_'+ no +'" value="AREA '+no+'" /></td>'+
            '<td><input type="text" id="alt_'+ no +'" name="alt_'+ no +'" /></td>'+
            '<td><input type="number" id="bearing1_'+ no +'"  name="bearing1_'+ no +'" value="'+brg1+'" /></td>'+
            '<td><input type="number" id="bearing2_'+ no +'" name="bearing2_'+ no +'"  value="'+brg2+'" /></td>'+
            '<td><input type="number" id="radius_'+ no +'" name="radius_'+ no +'" value="25"/></td>'+
           
            // '<td>'+ s.area +'</td>'+
            // '<td>'+ s.alt + '</td>'+

        '</tr>';
        $("#msaarealist").append(hsl)
        $("td:nth-of-type(2)").hide();
    }
}
function checkbearing(id){
    var bbr=$("#"+id).val()
    if (Number(bbr)>360){
        Swal.fire(
            'Invalid Data!',
            'Maximum radial value is 360',
            'error'
        )
        $("#"+id).val('')
    }
    
    var jml=Number(id.substr(-1));

    if (id.includes('bearing2')==true){
        if (jml < jumlahrow ){
            var nxt=Number(jml)+1;
            console.log(bbr,jml,jumlahrow,nxt)
            $("#bearing1_"+nxt).val(bbr)
        }
        if (jml == jumlahrow ){
            $("#bearing2_"+jml).val( $("#bearing1_1").val())
        }
    }
    

}
function edit(id) {
    msaid=id;
    aboutvol("msalist");aboutvol("msadetail");
    var ix = msa.findIndex(x=>x.id===Number(id));
    // console.log(msa[ix]);
    var msad=msa[ix];
    area=msad.area;
    hcurr=[];
    $("#status").val('R');
    $("#jumlaharea").val(msad.area.length)
    jumlahrow=msad.area.length;
    $("#id").val(msad.id)
    $("#msa_id").val(msad.msa_id)
    $("#msaarealist").empty();
   var jml= $("#jumlaharea").val();
    var no=1;marea=msad.area;
    for (let index = 0; index < jml; index++) {
        var s=marea[index];
        var brg1='';brg2='';radius=s.segment[0].radius;
        if (jml==1){
            brg1='0';brg2='0';
        }else{
            brg1=s.segment[0].bearing1;brg2=s.segment[1].bearing1;
        }
        var  hsl= '<tr v-bind:key="arp.arpt_ident">'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">'+
                            '<a class="btn btn-dim btn-info col-md-12" id='+ id+'@'+s.id +' onclick="maparea(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                            '<a class="btn btn-dim btn-danger col-md-12" id='+ s.id +' onclick="removearea(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                        '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td><input type="hidden" id= "msa_area_id_'+ no +'"  name="msa_area_id_'+ no +'" value="'+ s.msa_area_id+'"/></td>'+
            '<td style="width:10%"><input type="text" id= "area_'+ no +'"  name="area_'+ no +'" value="'+ s.area+'"/></td>'+
            '<td style="width:10%"><input type="text" id="alt_'+ no +'" name="alt_'+ no +'" value="'+ s.alt+'"/></td>'+
            '<td style="width:10%"><input type="number" onfocusout="checkbearing(this.id)" id="bearing1_'+ no +'"  name="bearing1_'+ no +'" value="'+ brg1+'"/></td>'+
            '<td style="width:10%"><input type="number" onfocusout="checkbearing(this.id)" id="bearing2_'+ no +'" name="bearing2_'+ no +'" value="'+ brg2+'"/></td>'+
            '<td style="width:10%"><input type="number" id="radius_'+ no +'" name="radius_'+ no +'" value="'+ radius+'"/></td>'+
            no++
            // '<td>'+ s.area +'</td>'+
            // '<td>'+ s.alt + '</td>'+

        '</tr>';
        $("#msaarealist").append(hsl)
        $("td:nth-of-type(2)").hide();
    }
    // msad.area.forEach(s=>{
    //     console.log(s)
        
    // })
    $("#msaedit").html(msad.fix_point+' MSA')
    $("#rad").val(msad.rad)
    var crn=[];tnm='';tid='';
   
    if (msad.navaid.length > 0){
        $("#refpoint").val('NAV');
        tid=msad.navaid[0].nav_id
        $("#nav_id").val(tid);
        $("#ident").val(msad.navaid[0].nav_ident);
        $("#cen_lat").val(msad.navaid[0].geom.coordinates[1]);
        $("#cen_lon").val(msad.navaid[0].geom.coordinates[0]);
        crn=SetCoordinatebyGeom(msad.navaid[0].geom);
        tnm=msad.navaid[0].nav_ident + ' (' + crn.WGS[1] + ' ' + crn.WGS[0] + ')';
        
    }else if (msad.airport.length > 0){
        $("#refpoint").val('ARP');
        tid=msad.airport[0].arpt_ident
        $("#arpt_ident").val(tid);
        $("#ident").val(msad.airport[0].icao);
        $("#cen_lat").val(msad.airport[0].geom.coordinates[1]);
        $("#cen_lon").val(msad.airport[0].geom.coordinates[0]);
        crn=SetCoordinatebyGeom(msad.airport[0].geom);
        tnm=msad.airport[0].icao + ' (' + crn.WGS[1] + ' ' + crn.WGS[0] + ')';
    }else{
        $("#refpoint").val('WPT');
        tid=msad.waypoint[0].wpt_id
        $("#wpt_id").val(tid);
        $("#ident").val(msad.waypoint[0].wpt_name);
        $("#cen_lat").val(msad.waypoint[0].geom.coordinates[1]);
        $("#cen_lon").val(msad.waypoint[0].geom.coordinates[0]);
        crn=SetCoordinatebyGeom(msad.waypoint[0].geom);
        tnm=msad.waypoint[0].desc_name + ' (' + crn.WGS[1] + ' ' + crn.WGS[0] + ')';
    }
    // console.log($("#cen_lat").val(),'$("#cen_lat").val()');
    $("#center_id").val(tid);
    $('#fixpoint').html(Symbolpoint(tnm,tid,'spoint1'));
    no=1;
    if (msad.chart.length == 0){
        if ($("#charttable").is(':visible')==true){
        aboutvol('charttable');
        }
    }else{
        if ($("#charttable").is(':visible')==false){
        aboutvol('charttable');
        }
        $("#msaused").empty();
        msad.chart.forEach(c=>{
            var chnm=c.chart_name + c.page;
            if (c.aip.length>0){
                chnm=c.aip[0].chart_name;
            }
            var  hsl= '<tr>'+
            '<td>'+  no + '</td>'+
            '<td>'+ chnm+'</td>'+
            no++
        '</tr>';
        $("#msaused").append(hsl)
        })
    }
    // compareisidata(msamain,msad,hcurr);
    // settonullinput(msamain);
window.scroll(0,0);


}
function remove(id){
    console.log(id)
    $("#id").val(id) 
    $("#status").val('D')
    $("#remove").val('msa')
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
            $("#msa_form").submit();
        }else{
            location.reload();
        }
    })
}
function removearea(id){
    console.log(id)
    $("#id").val(id) 
    $("#status").val('D')
    $("#remove").val('area')
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
            $("#msa_form").submit();
        }else{
            location.reload();
        }
    })
}
function setMapPoint(id) {
   
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=msa&id='+id, 'Set Latitude and Longitude', params)
}
function showfpl(id) {
   
   let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
   window.open('/map.php?table=fpl&id=385', 'Set Latitude and Longitude', params)
}
function changerefpoint(id){
    var x = id.options[id.selectedIndex].text;
    $('#fixpoint').html(Symbolnewpoint(x,'spoint1'))
}
function Symbolnewpoint(point,vis){
    return '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown">'+point+'</a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">'+
                    '<ul class="link-list-plain">'+
                    '<a id="'+ vis +'" onclick="changepoint(this.id)" class="btn btn-dim btn-success col-md-12"><i class="icon ni ni-plus"></i> New '+point +'</a>'+
                    '</ul></div>'+
            '</div>';
}
function Symbolpoint(point,id,vis){
    return '<div class="dropdown">'+
            '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown">'+point+'</a>'+
            '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">'+
            '<ul class="link-list-plain">'+
            // '<a class="btn btn-dim btn-secondary col-md-12" id="'+ id +'" onclick="editpoint(this.id)"><i class="icon ni ni-edit"></i> Edit </a>'+
            '<a id="'+ vis +'" onclick="changepoint(this.id)" class="btn btn-dim btn-success col-md-12"><i class="icon ni ni-exchange"></i> Change</a>'+
            '</ul></div>'+
            '</div>';
}
function changepoint(id){
    var referensi='';
        // console.log('search1')
        aboutvol('search1');
        refsearch=$("#refpoint").val();
    if (refsearch=='NAV'){
        $('.select2').select2({
            placeholder: 'select navaid ...',
            minimumInputLength: 1,
            ajax: {
                url: '../api/navaid/search',
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
                                    id: item.nav_id,
                                    ident:  item.nav_ident
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
                var r = confirm("do you want to create a new navaid?");
                if (r == true) {
                    window.location.href = '/navaid/new@new@listtranssegment@' +transseg.proc_id + '@' + insert;
                    // $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
                }
                else
                {
                    $('.select2-selection__choice:last').remove();
                    $('.select2-search__field').val(e.params.data.text).focus()
                }
            }else{
                    $("#nav_id").val(e.params.data.id);
                    $("#center_id").val(e.params.data.id);
                    $("#ident").val(e.params.data.ident);
                    $("#msa_id").val('MSA_'+e.params.data.id);
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    $("#cen_lat").val(crd2.Decimal[1]);$("#cen_lon").val(crd2.Decimal[0]);
                    var txtp=e.params.data.text + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    aboutvol('search1');
                referensi='';
            }
        });
    }
    if (refsearch=='ARP'){
        $('.select2').select2({
        placeholder: 'select airport ...',
        minimumInputLength: 3,
        ajax: {
            url: '../api/airport/search',
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
            $("#arpt_ident").val(e.params.data.id);
            $("#center_id").val(e.params.data.id);
            $("#ident").val(e.params.data.icao);
            $("#msa_id").val('MSA_'+e.params.data.id);
            crd2=SetCoordinatebyGeom(e.params.data.geom);
            $("#cen_lat").val(crd2.Decimal[1]);$("#cen_lon").val(crd2.Decimal[0]);
            var txtp=e.params.data.text + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
            $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
            aboutvol('search1');
        }
    });
    }
    if (refsearch=='WPT'){
        $('.select2').select2({
        placeholder: 'select waypoint ...',
        minimumInputLength: 3,
        ajax: {
            url: '../api/waypoint/search',
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
                                text:  item.wpt_name + ' ' + item.definition ,
                                geom:item.geom,
                                id: item.wpt_id,
                                ident:  item.wpt_name
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
            // console.log(e.params.data)
            if(e.params.data.isNew){
                var r = confirm("do you want to create a new waypoint?");
                if (r == true) {
                        window.location.href = '/waypoint/new@new@listtranssegment@' +transseg.proc_id + '@' + codchart;
                }
                else
                {
                    $('.select2-selection__choice:last').remove();
                    $('.select2-search__field').val(e.params.data.text).focus()
                }
            }else{
            // console.log(referensi)
                var ppp=e.params.data.text.split(' ');
                    $("#wpt_id").val(e.params.data.id);
                    fixid=e.params.data.id;
                    $("#center_id").val(e.params.data.id);
                    $("#msa_id").val('MSA_'+e.params.data.id);
                    $("#ident").val(e.params.data.ident);
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    $("#cen_lat").val(crd2.Decimal[1]);$("#cen_lon").val(crd2.Decimal[0]);
                    var txtp=ppp[0] + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    aboutvol('search1');
                }
                referensi='';
            
        });
    }
}
</script>
@endsection