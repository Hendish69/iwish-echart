@extends('layouts.app')

@section('template_title')
    Holding
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body mt-3">
            <div class="row mt" id="dataholding" style="visibility: visible">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <h5 class="panel-title" id="titleholding">Holding</h5>
                    </div>
                </div>
                <div class="panel-heading col-md-12">
                    <button onclick="backtomenu()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
                </div>
                <div class="col-md-4 mt-3">
                    <strong>ICAO</strong>
                    <br>
                    <input type="text" class="form-control" onfocusout="searchats()" name="icao" id="icao" placeholder= "search Airport by location indicator..."">
                </div>
                <div class="col-md-12 mt-3">
                    <table class="table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th>Fix Point</th>
                                <th>Inbound</th>
                                <th>Turn</th>
                                <th>Min. Alt</th>
                                <th>Max. Alt</th>
                            </tr>
                        </thead>
                        <tbody id="holdinglist">

                        </tbody>
                    </table>
                </div>
            </div>
            <div id="dataholdingdetail" style="visibility: hidden">
                <div class="nk-block-between">
                    <h5 class="panel-title" id="holdingedit">Holding</h5>
                </div>
                <div class="panel-body mt-3">
                    <form action="api/holding/save" method="post"  enctype="multipart/form-data" id="holdingremove">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="status" id="status">
                        <input type="hidden" name="fix_id" id="fix_id">
                        <input type="hidden" name="fix_cd" id="fix_cd">
                        <input type="hidden" name="hld_type" id="hld_type" value="{{$arptident}}">
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="parent" id="parent" value="{{$parent}}">
                        <input type="hidden" name="parentid" id="parentid" value="{{$parentid}}">
                        <input type="hidden" name="lon" id="lon">
                        <input type="hidden" name="poly" id="poly">
                        <div class="card-inner table-bordered mt-1">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Ref. Fix Point</strong>
                                <br>
                                <select id="refpoint" name="refpoint" onchange="changerefpoint(this)" class="form-control" >
                                
                                </select>
                            </div>
                            <div class="col-md-4">
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
                                <strong>Inbound</strong>
                                <br>
                                <input id="crs" type="text" onfocusout="checkinbound('crs')" class="form-control" name="crs">
                            </div>
                            <div class="col-md-2">
                                <strong>RNAV</strong>
                                <br>
                                <select selected="selected" class="form-control" id="mag" name="mag">
                                    <option value="N">NO</option>
                                    <option value="Y">YES</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <strong>Turn</strong>
                                <br>
                                <select selected="selected" class="form-control" id="turn" name="turn">
                                    <option value="L">LEFT</option>
                                    <option value="R">RIGHT</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-2">
                                <strong>Length (nm)</strong>
                                <br>
                                <input id="leg_length" type="number" class="form-control" name="leg_length">
                            </div> -->
                            <div class="col-md-2">
                                <strong>Timing (minute)</strong>
                                <br>
                                <input id="leg_time" type="text" onfocusout="checkinbound('time')" class="form-control" name="leg_time">
                            </div>
                            <div class="col-md-2">
                                <strong>Min Alt</strong>
                                <br>
                                <input id="min_alt" type="text" class="form-control" name="min_alt">
                            </div>
                            <div class="col-md-2">
                                <strong>Max Alt</strong>
                                <br>
                                <input id="max_alt" type="text" class="form-control" name="max_alt">
                            </div>
                            <div class="col-md-2">
                                <strong>Speed</strong>
                                <br>
                                <input id="speed" type="text" class="form-control" name="speed">
                            </div>
                            <div class="col-md-12">
                                <strong>Notes</strong>
                                <br>
                                <textarea type="text" class="form-control" id="notes"  name="notes"></textarea>
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
                            <button onclick=updateholding() id="btn_save" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
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
var hold =@json($holdingtemp);holdcurr =@json($holding);$("#search1").hide();$("#dataholdingdetail").hide();htemp=[];hcurr=[];arptident=@json($arptident);
var fld = ['id','hld_type','fix_id','fix_cd','crs','turn','leg_time','min_alt','max_alt','speed','notes','mag'];edit =@json($edit);
var Refpoint= [{
    id: 'NAV',
    definition: 'Navaid'
},{
    id: 'WPT',
    definition: 'Waypoint'
}];
if (edit=='new'){
    NewData();
}
$('#fixpoint').html(Symbolnewpoint('Navaid','spoint1'))
Refpoint.forEach(t=>{
    var rrf='<option value="'+t.id+'">'+ t.definition +'</option>';
    $("#refpoint").append(rrf);
});
// $("#dataholdingdetail").hide();
hold.forEach(v=>{
var trn='RIGHT';crs=numeral(v.crs/10).format('000.0')+'°';
if (v.turn=='L'){
    trn='LEFT'
}
var malt=v.min_alt;
if (malt==null){
    malt='';
}
var  hsl= '<tr>'+
        '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                    '<ul class="link-list-plain">'+
                        '<a class="btn btn-dim btn-primary col-md-12" id='+ v.id +' onclick="View(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                        '<a class="btn btn-dim btn-info col-md-12" id='+ v.id +' onclick="setMapPoint(this.id)"><i class="icon ni ni-map"></i> Show</a>'+
                        '<a class="btn btn-dim btn-danger col-md-12" id='+ v.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Remove</a>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</td>'+
        '<td>'+ v.fix_point +'</td>'+
        '<td>'+ crs +'</td>'+
        '<td>'+ trn +'</td>'+
        '<td>'+ malt +'</td>'+
        '<td>'+ v.max_alt +'</td>'+
    '</tr>';
    $("#holdinglist").append(hsl);
});

var input = document.getElementById("icao");
// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        searchats();
        // document.getElementById("ats_ctry").onchange();
    }
});

if (hold.length > 0){
    $("#titleholding").html(hold[0].icao + ' ' + hold[0].city_name + '/'+ hold[0].arpt_name + ' Holdings')
}
function checkinbound(type){
    if (type=='crs'){
        ccrs=$("#crs").val();
        if (ccrs==''){
            Swal.fire(
            'Invalid Data!',
            'Inbound cannot be empty !!!',
            'error'
            )
        } else if (Number(ccrs) > 360){
            Swal.fire(
            'Invalid Data!',
            'Inbound is not correct !!!',
            'error'
            )
        }
    }else if (type=='time'){
        ccrs=$("#leg_time").val();
        if (ccrs==''){
            $("#leg_time").val('1.0')
        } 
    }

}
function setMapPoint(id){
    var ix=hold.findIndex(x=>x.id===Number(id))
    htemp=hold[ix];
//    console.log(htemp.poly)
    // if (htemp.poly==null){
    //     var hasil=createholding(htemp.geom.coordinates[1],htemp.geom.coordinates[0],htemp.crs/10,htemp.turn)
    //     var pll='MULTILINESTRING((';
    //     hasil.forEach(a=>{
    //             if (pll=='MULTILINESTRING(('){
    //                 pll +=a 
    //             }else{
    //                 pll +=','+a ;
    //             }
    //         })
    //         pll +='))';
    //         $("#id").val(id) 
        
    //         $("#hld_type").val(arptident)
    //         $("#poly").val(pll)
    //         $("#status").val('P')
    //         $("#holdingremove").submit();
    // }
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=holding&id='+htemp.id, 'Set Latitude and Longitude', params)
    // console.log(htemp)
   
}
function View(id){
    aboutvol("dataholdingdetail");aboutvol("dataholding");
    $("#status").val('R')
    var ix=hold.findIndex(x=>x.id===Number(id))
    htemp=hold[ix];
    ix=holdcurr.findIndex(x=>x.id===Number(id))
    if (ix !==-1){
        hcurr= holdcurr[ix];
    }
    
    $("#holdingedit").html(htemp.fix_point+' Holding')
    var crn=[];tnm='';tid='';
    // console.log(htemp,hcurr);
    if (htemp.navaid.length > 0){
        $("#refpoint").val('NAV');
        tid=htemp.navaid[0].nav_id
        crn=SetCoordinatebyGeom(htemp.navaid[0].geom);
        tnm=htemp.navaid[0].nav_ident + ' (' + crn.WGS[1] + ' ' + crn.WGS[0] + ')';
        
    }else{
        $("#refpoint").val('WPT');
        tid=htemp.waypoint[0].wpt_id
        crn=SetCoordinatebyGeom(htemp.waypoint[0].geom);
        tnm=htemp.waypoint[0].desc_name + ' (' + crn.WGS[1] + ' ' + crn.WGS[0] + ')';
    }
    $("#lat").val(crn.Decimal[1]);$("#lon").val(crn.Decimal[0]);
    $('#fixpoint').html(Symbolpoint(tnm,tid,'spoint1'));
    compareisidata(fld,htemp,hcurr);
    settonullinput(fld);
    crs=numeral(htemp.crs/10).format('000.0');
    if (htemp.leg_time.length==1){
        tim=numeral(htemp.leg_time).format('0.0');
    }else{
        tim=numeral(htemp.leg_time/10).format('0.0');
    }
    
    $("#leg_time").val(tim);$("#crs").val(crs);
    window.scroll(0,0);
}
function updateholding(){
    tim=$("#leg_time").val();crs=$("#crs").val();
    $("#leg_time").val()
        tim=numeral(tim*10).format('00');
        crs=numeral(crs*10).format('0000');
    
    $("#leg_time").val(tim);$("#crs").val(crs);
    turn= $("#turn").val()
    lat= $("#lat").val(); lon= $("#lon").val();
    if ( $("#status").val()=='R'){
        bearing=htemp.crs/10;
    }else  if ( $("#status").val()=='N'){
        bearing=$("#crs").val();
    }
    var hasil=createholding(Number(lat),Number(lon),Number(bearing),turn)
        var pll='MULTILINESTRING((';
        hasil.forEach(a=>{
                if (pll=='MULTILINESTRING(('){
                    pll +=a 
                }else{
                    pll +=','+a ;
                }
            })
            pll +='))';
        $("#poly").val(pll)
    if  ($("#status").val()=='N'){
        var fldn = ['hld_type','fix_id','fix_cd','crs','turn','max_alt','mag'];
        checkrwy =checknewdata(fldn);
    
    }else if ($("#status").val()=='R'){
        var fldup = ['hld_type','fix_id','fix_cd','crs','turn','leg_time','min_alt','max_alt','speed','notes','mag'];
        checkrwy =checkupdatedata(fldup,htemp);
        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#holdingremove").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        isback();
    }
}
function NewData(){
    aboutvol("dataholdingdetail");aboutvol("dataholding");
    $("#holdingedit").html('New Holding')
    $("#status").val('N')
    $('#fixpoint').html(Symbolnewpoint('Navaid','spoint1'))
    var fldup = ['crs','turn','leg_time','min_alt','max_alt','speed','notes','mag'];
    clearinput(fldup);
}
function isback(){
    aboutvol("dataholdingdetail");aboutvol("dataholding");
}
function searchats(){
    var ident=$("#icao").val().toUpperCase()
    window.scrollTo(0,0);
    // window.location.href = '/atsdetail/' + ident;
    $.ajax({
        url: '/api/airports',
        data: {'icao' : ident},
        type: "json",
        method: "GET",

            success: function (result) {
                var jmlwpt=result.data.length
               
                    console.log(jmlwpt)
                
                $.each(result.data, function (k, v) {

                    // console.log(v,cod);
                  
                        window.location.href = '/holding/' + v.arpt_ident +'@edit';
                    

                    // dt.push(v)
                  
                    
                })
            }
    })

}

function backtomenu(){
    
    window.location.href = '/listairport/holding';
}
function remove(id){
    console.log(id)
    $("#id").val(id) 
    $("#status").val('D')
    $("#hld_type").val(arptident)
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
                                    type:  item.type
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
                    $("#fix_id").val(e.params.data.id);
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    $("#lat").val(crd2.Decimal[1]);$("#lon").val(crd2.Decimal[0]);
                    var txtp=e.params.data.text + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    $('#fix_cd').val('3');
                    aboutvol('search1');
                referensi='';
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
                                id: item.wpt_id
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
                    $("#fix_id").val(e.params.data.id);
                    fixid=e.params.data.id;
                    // $('#fixpoint').html(Symbolpoint(ppp[0],e.params.data.id,'spoint1'));
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    $("#lat").val(crd2.Decimal[1]);$("#lon").val(crd2.Decimal[0]);
                    var txtp=ppp[0] + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    $('#fix_cd').val('1');
                    aboutvol('search1');
                }
                referensi='';
            
        });
    }
}
</script>
@endsection