@extends('layouts.app')

@section('template_title')
    Waypoint
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="panel panel-default mt-3">
        <div class="panel-heading">
            <h6 class="panel-title" id="wpttitle"></h6>
        </div>
        <div class="panel-body mt-3">
            <form action="api/waypoint/save" method="post"  enctype="multipart/form-data" id="waypointform">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="wpt_id" id="wpt_id">
            <input type="hidden" name="status" id="status" value="{{$status}}">
            <input type="hidden" name="geom" id="geom">
            <input type="hidden" name="parent" id="parent" value="{{$parent}}">
            <input type="hidden" name="parentid" id="parentid" value="{{$parentid}}">
            <input type="hidden" name="atsstatus" id="atsstatus" value="{{$atsstatus}}">
                <div class="row g-gs table-bordered mt-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Country</strong>
                                <br>
                                <select id="ctry" name="ctry"  selected="selected" class="form-control">
                                
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <strong>Name</strong>
                                <br>
                                <input id="wpt_name" name="wpt_name" onfocusout="checkwptdouble()" type="text" maxlength="5" style="text-transform:uppercase" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <strong>Desc. Name</strong>
                                <br>
                                <input id="desc_name" name="desc_name" type="text" style="text-transform:uppercase" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <strong>Usage</strong>
                                <br>
                                <select id="usage_cd" name="usage_cd" v-on:click="getusage('def',wptdata.definition)" selected="wptdata.definition" class="form-control" v-model="wptdata.definition">
                                    @foreach($usage as $ctry)
                                        <option value="{{$ctry->id}}">{{ $ctry->definition }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <strong>Type</strong>
                                <br>
                                <select id="type"  name="type" class="form-control">
                                    @foreach($wptypes as $ctry)
                                        <option value="{{$ctry->id}}">{{ $ctry->definition }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-inner table-bordered">
                            <div class="row">
                                <div class="form-check col-md-4">
                                    <input class="form-check-input pubchecktype" checked="checked" onclick="selectradio()" type="radio" id="coordinate" value="coordinate" name="generate">
                                    <label class="form-check-label" for="coordinate">Coordinate</label>
                                </div>
                                <div class="form-check col-md-4">
                                    <input class="form-check-input pubchecktype" onclick="selectradio()" type="radio" id="refnav" value="refnav" name="generate">
                                    <label class="form-check-label" for="refnav">Reference Point</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="refpoint" style="visibility: hidden">
                        <div class="card-inner table-bordered">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Distance (NM)</strong>
                                    <br>
                                    <input id="distance" name="distance" type="number" style="text-transform:uppercase" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <strong>Ref. Fix Point</strong>
                                    <br>
                                    <select id="ref1point" name="ref1point" onchange="changerefpoint(this)" class="form-control" >
                                    
                                    </select>
                                </div>
                                <div class="col-md-6">
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
                            </div>
                        </div>
                        <div class="card-inner table-bordered">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Bearing</strong>
                                    <br>
                                    <input id="bearing" name="bearing" type="number" style="text-transform:uppercase" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <strong>Ref. Fix Point</strong>
                                    <br>
                                    <select id="ref2point" name="ref2point" onchange="changerefpoint(this)" class="form-control" >
                                    
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Fix Point</strong>
                                    <br>
                                    <p id="fix2point"></p>
                                </div>
                                <div class="col-md-12" id="search2" style="visibility: hidden">
                                    <select name="select2" id="select22" class="form-control select2">
                                </div>
                                <div class="col-md-6" style="visibility: hidden">
                                    <strong>ID</strong>
                                    <br>
                                    <input style="visibility: hidden" type="text" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <strong>Latitude</strong>
                        <br>
                        <input id="latitude"  name="latitude" ref="latitude" style="text-transform:uppercase" maxlength="9" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LAT')" placeholder="06300000S">
                    </div>
                    <div class="col-md-2">
                        <strong>Longitude</strong>
                        <br>
                        <input id="longitude" name="longitude" ref="longitude" style="text-transform:uppercase" maxlength="10" type="text" class="form-control" onfocusout="CheckCoordinateFormat(this.id,'LON');plotpoint('latitude','longitude');checkcoordinate()" placeholder="106300000E">
                    </div>
                    <div class="col-md-4">
                        <strong>Epoch</strong>
                        <br>
                        <input id="epoch" type="date" onfocusout="calmagvar(this.id)" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <strong>Magvar</strong>
                        <br>
                        <input id="mag_var" name="mag_var" type="text" class="form-control" v-model="wptdata.mag_var">
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
                    <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                        &nbsp;
                    <a onclick="setMapPoint()" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Set Point</a>&nbsp;
                    <a onclick="update()" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</a>
                    
                </div>
            </div>
        </div>
        <div class="row mt-3" id="waptsearch" style="visibility: hidden">
            <div class="col-md-12">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr align="center">
                            <th></th>
                            <th>Name</th>
                            <th>Usage</th>
                            <th>Distance</th>
                            <th>Coordinates</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody id="navlist">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
$('#navedit').hide();$('#waptsearch').hide();$('#refpoint').hide();$('#search1').hide();$('#search2').hide();
var wpttemp =@json($waypointstemp)[0];
var wpt =@json($waypoints);oldname='';ctry =@json($countries);
var fld = ['id','wpt_id', 'wpt_name', 'desc_name','latitude','longitude', 'ctry', 'type', 'usage_cd', 'mag_var'];
var no=0;crd1=[];crd2=[];
var parent =@json($parent);
var parentid =@json($parentid);
var atsstatus =@json($atsstatus);
var Refpoint= [{
    id: '',
    definition: 'None'
}, {
    id: 'ARPT',
    definition: 'Airport'
},{
    id: 'ILS',
    definition: 'ILS'
},{
    id: 'NAV',
    definition: 'Navaid'
},{
    id: 'WPT',
    definition: 'Waypoint'
}];
console.log(parent,parentid,atsstatus)
ctry.sort((a,b) => (a.country > b.country) ? 1 : ((b.country > a.country) ? -1 : 0));
ctry.forEach(t=>{
    $("#ctry").append('<option value="'+t.ident+'">'+t.country+'</option>');
})
Refpoint.forEach(t=>{
    $("#ref1point").append('<option value="'+t.id+'">'+ t.definition +'</option>');
    $("#ref2point").append('<option value="'+t.id+'">'+ t.definition +'</option>');
})
if (wpttemp){
    var crd=SetCoordinatebyGeom(wpttemp.geom)
    wpttemp['latitude']=crd.Database[1];
    wpttemp['longitude']=crd.Database[0];
    if (wpt.length > 0){
        wpt=wpt[0];
        crd=SetCoordinatebyGeom(wpt.geom)
        wpt['latitude']=crd.Database[1];
        wpt['longitude']=crd.Database[0];
    }else{
        wpt['latitude']='';
        wpt['longitude']='';
    }
    oldname=wpttemp.wpt_name;
    var ttl=wpttemp.desc_name + ' ' + wpttemp.definition + ' Information';
    $("#status").val('R');
    $("#wpttitle").html(ttl);
    compareisidata(fld,wpttemp,wpt);

}else{
    
    var ttl='New Data';
    $("#wpttitle").html(ttl);
    $("#status").val('N');
    $("#type").val('1');
    $("#ctry").val('ID')
}
function selectradio(e) {
    let dd = new Date();
    $('.pubchecktype:radio:checked').each(function(i){
        // console.log($(this).val())
        if ($(this).val()=='coordinate'){
            if ($("#refpoint").is(':visible')==true){
                aboutvol('refpoint');
            }
        }else{
            if ($("#refpoint").is(':visible')==false){
                aboutvol('refpoint');
            }
        }
        // reloadAtslines($(this).val());
    });
}
function checkwptdouble(){
    if ( $("#status").val()=='N' ||  $("#wpt_name").val() !==oldname ){
        console.log('check wpt name')
        var newname=$("#wpt_name").val().toUpperCase();
        $("#wpt_name").val(newname);
        // var lat =  $("#latitude").val();lon =  $("#longitude").val();
        $("#wpttitle").html('New ' + newname)
       
            // $("#desc_name").val(newname);
        
        $("#navlist").empty();
        $.ajax({
                url: '/api/waypoint/temp/list',
                data: {'wpt_name' : newname},
                type: "json",
                method: "GET",

                success: function (result) {
                    var jmlwpt=result.data.length
                    if (jmlwpt > 0){
                        if ($("#waptsearch").is(':visible')==false){
                            aboutvol('waptsearch');
                        }
                    }else{
                        if ($("#waptsearch").is(':visible')==true){
                            aboutvol('waptsearch');
                        }
                    }
                    if ($("#status").val()=='N'){
                        var wptid='WPT_' + newname + '_' + $("#usage_cd").val() + '_' + Number(jmlwpt + 1);
                        $("#wpt_id").val(wptid);
                        console.log(jmlwpt,wptid)
                    }
                    $.each(result.data, function (k, v) {
                        // console.log(v);
                        var c1= SetCoordinatebyGeom(v.geom);
                        var cord =c1.WGS[1] + '<br>' +c1.WGS[0];
                        // if (lat !== '' && lon !==''){
                        //     var c2= SetCoordinate(lat,lon);
                        //     // console.log(c2)
                        //     var dist= getdistance(c2.Decimal[1],c2.Decimal[0],c1.Decimal[1],c1.Decimal[0])
                        //     // console.log(dist)
                        //     if (dist.DistanceReal < 1000){
                        //         Swal.fire(
                        //             'Data Double!',
                        //             'The closest distance to the same waypoint is ='+dist.DistinNM + 
                        //             '<br>' + v.wpt_name + '<br>' +  cord ,
                        //             'warning'
                        //         )
                        //     }
                        // }
                        hasil = '<tr class="nk-tb-item">'+
                        '<td class="tb-tnx-action">'+
                            '<div class="dropdown">'+
                                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                                    '<ul class="link-list-plain">'+
                                         '<a class="btn btn-dim btn-primary col-md-12" id='+ v.wpt_id +' onclick="EditWpt(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+

                                   
                                '</ul>'+
                                    '</div>'+
                            '</div>'+
                        '</td>'+
                        '<td>' + v.wpt_name + '</td><td>' + v.definition + '</td><td></td><td>' + cord + '</td><td>' +v.country + '</td></tr>';
                        $("#navlist").append(hasil);
                        // hasil = '<tr class="nk-tb-item">'+
            
                        // '<td>' + v.wpt_name + '</td><td>' + v.definition + '</td><td>' + v.desc_name + '</td><td>' + cord + '</td><td>' +v.country + '</td></tr>';
                        // $("#navlist").append(hasil);
                      
                    })
                }
        })
    }
}
function checkcoordinate(){
// buat query yg X and Y nya like degree
    // if ($("#wpt_name").val() !==oldname ){
        var lat =  $("#latitude").val();lon =  $("#longitude").val();volpoints='';
        if (lat !== '' && lon !==''){
            var c2= SetCoordinate(lat,lon);
            cmin=1/60;
            clat=c2.Decimal[1]; clon=c2.Decimal[0];
            var p1 =(clon-cmin) + ' ' + (clat+cmin); p2=(clon+cmin) + ' ' + (clat+cmin);p3=(clon+cmin) + ' ' + (clat-cmin);p4=(clon-cmin) + ' ' + (clat-cmin)
            volpoints ='POLYGON(('+p1 + ',' + p2+ ',' + p3+ ',' + p4+ ',' + p1 + '))';
            // console.log(volpoints,clat,clon,cmin)
        }
        $("#navlist").empty();
        $.ajax({
                url: '/api/waypoint/nearest/'+volpoints,
                type: "json",
                method: "GET",

                success: function (result) {
                    var jmlwpt=result.data.length
                    if (jmlwpt > 0){
                        if ($("#waptsearch").is(':visible')==false){
                            aboutvol('waptsearch');
                        }
                    }else{
                        if ($("#waptsearch").is(':visible')==true){
                            aboutvol('waptsearch');
                        }
                    }
                    
                    $.each(result.data, function (k, v) {
                        // console.log(v);
                        var c1= SetCoordinatePoint(v.st_asewkt);
                        var cord =c1.WGS[1] + '<br>' +c1.WGS[0];
                        // console.log(c1);

                        if (lat !== '' && lon !==''){
                            var c2= SetCoordinate(lat,lon);
                            // console.log(c2)
                            var dist= getdistance(c2.Decimal[1],c2.Decimal[0],c1.Decimal[1],c1.Decimal[0])
                            var jarak =dist.DistinNM ;
                            if (isNaN(jarak)){
                                jarak=0.0;
                            }
                            if (dist.DistanceReal < 1000){
                                Swal.fire(
                                    'Closest Waypoint!',
                                    'The closest distance to the same waypoint is ='+jarak + 
                                    '<br>' + v.wpt_name + '<br>' +  cord ,
                                    'warning'
                                )
                            }
                        }
                        hasil = '<tr class="nk-tb-item">'+
                        '<td class="tb-tnx-action">'+
                            '<div class="dropdown">'+
                                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">'+
                                    '<ul class="link-list-plain">'+
                                        '<a class="btn btn-dim btn-primary col-md-12" id='+ v.wpt_id +' onclick="EditWpt(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                                '</ul>'+
                                    '</div>'+
                            '</div>'+
                        '</td>'+
                        '<td>' + v.wpt_name + '</td><td>' + v.definition + '</td><td>' + jarak + ' nm</td><td>' + cord + '</td><td>' +v.country + '</td></tr>';
                        $("#navlist").append(hasil);
                        // hasil = '<tr class="nk-tb-item">'+
            
                        // '<td>' + v.wpt_name + '</td><td>' + v.definition + '</td><td>' + v.desc_name + '</td><td>' + cord + '</td><td>' +v.country + '</td></tr>';
                        // $("#navlist").append(hasil);
                      
                    })
                }
        })
    // }
}
function EditWpt(key){
    window.scrollTo(0,0);
    window.location.href = '/waypoint/' + key + '@edit@' + parent + '@' + parentid + '@'+atsstatus;
    // window.scrollTo(0,0);
    // window.location.href = '/waypointinfo/' + key + '@edit@enr43@' +key + '@';
}
function update(){
    console.log('update')
    var checkrwy=false;
    if  ($("#status").val()=='N'){
        var fldbew = ['wpt_name', 'desc_name','latitude','longitude', 'ctry', 'type'];
        changetouppercase(fldbew);
        checkrwy =checknewdata(fldbew);
    
    }else if ($("#status").val()=='R'){
        var fldup = ['wpt_id', 'wpt_name', 'desc_name','latitude','longitude', 'ctry', 'type', 'usage_cd', 'mag_var'];
        changetouppercase(fldup);
        checkrwy =checkupdatedata(fldup,wpttemp);
        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#waypointform").submit();
        console.log('Data Valid')
    }else{
        console.log('Tidak ada perubahan data')
        backtolist();
    }
   
}
function calmagvar(id){
    console.log(id,$("#"+id).val())
    var epoch = new Date($("#"+id).val()).toISOString().substr( 0, 10 );
    var lat1= $("#latitude").val();
    var lon1 =$("#longitude").val();
    // console.log(epoch,lat1,lon1);
    var crd=SetCoordinate(lat1,lon1);
    var mv =GetMagvar( crd.Decimal[0], crd.Decimal[1], epoch );
    // console.log(crd,mv);
    $("#mag_var").val(mv.magvar)
    // var mv = GetMagvar( longitude1, latitude1, epoch );

}





function setMapPoint() {
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table=waypoint&id=' + wpt.wpt_id, 'Set Latitude and Longitude', params)
}

function NewData(){
    aboutvol('rwyedit');
    aboutvol('rwytable');
}

function backtomain(){
    window.scroll(0,0);
    aboutvol('rwyedit');
    aboutvol('rwytable');
}


function backtolist(){
    history.back();
}
function changerefpoint(id){
    if ($("#fixpoint").is(':visible')==false){
        aboutvol('fixpoint');
    }
    switch (id.id) {
        case 'ref1point':
            if ($("#ref1point").val()==''){
                $('#fixpoint').html('')
            }else{
                var x = id.options[id.selectedIndex].text;
                // console.log(x)
                // document.getElementById("demo").innerHTML = "You selected: " + x;
                $('#fixpoint').html(Symbolnewpoint(x,'spoint1'))
            }
            break;
        case 'ref2point':
            if ($("#ref2point").val()==''){
                $('#fix2point').html('')
            }else{
                var x = id.options[id.selectedIndex].text;
                // console.log(x)
                // document.getElementById("demo").innerHTML = "You selected: " + x;
                $('#fix2point').html(Symbolnewpoint(x,'spoint2'))
            }
            break;
    }
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
            '<a class="btn btn-dim btn-secondary col-md-12" id="'+ id +'" onclick="editpoint(this.id)"><i class="icon ni ni-edit"></i> Edit </a>'+
            '<a id="'+ vis +'" onclick="changepoint(this.id)" class="btn btn-dim btn-success col-md-12"><i class="icon ni ni-exchange"></i> Change</a>'+
            '</ul></div>'+
            '</div>';
}
function changepoint(id){
    var referensi='';
    var dist =  $('#distance').val();
    var bear =  $('#bearing').val();

    if (id=='spoint1'){
        referensi='pointsatu'
        if ($("#search2").is(':visible')==true){
            aboutvol('search2');
        }
        refsearch=$("#ref1point").val();
        // console.log('search1')
        aboutvol('search1');
    }else if (id=='spoint2'){
        referensi='pointdua'
        if ($("#search1").is(':visible')==true){
            aboutvol('search1');
        }
        refsearch=$("#ref2point").val();
        // console.log('search2')
        aboutvol('search2');
    }
        // console.log(refsearch,referensi)
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
            // console.log(referensi)
            if (referensi=='pointsatu'){
                    crd1=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=e.params.data.text + '(' + crd1.WGS[1] + ' ' + crd1.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    aboutvol('search1');
                }else if (referensi=='pointdua'){
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    Calc(crd1,dist,crd2,bear);
                    var txtp=e.params.data.text + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fix2point').html(Symbolpoint(txtp,e.params.data.id,'spoint2'));
                    aboutvol('search2');
                }
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
                                text:  item.desc_name + ' (' + item.wpt_name + ') ' + item.definition ,
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
                // console.log(ppp);
                if (referensi=='pointsatu'){
                    crd1=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=ppp[0] + '(' + crd1.WGS[1] + ' ' + crd1.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    aboutvol('search1');
                }else if (referensi=='pointdua'){
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    console.log(crd1,'crd1');
                    console.log(crd2,'crd2')
      
                    Calc(crd1,dist,crd2,bear);
                    var txtp=ppp[0] + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fix2point').html(Symbolpoint(txtp,e.params.data.id,'spoint2'));
                    aboutvol('search2');
                }
            }
                referensi='';
            
        });
    }

  
    if (refsearch=='ILS'){
        $('.select2').select2({
        placeholder: 'select ils ...',
        minimumInputLength: 3,
        ajax: {
            url: '../api/ils/search',
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
                                text:  item.ils_ident + ' ' + item.ils_name ,
                                geom:item.geom,
                                id: item.ils_id
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
                var r = confirm("do you want to create a new ils?");
                if (r == true) {
                    if ($("#status").val()=='R'){
                        window.location.href = '/waypoint/new@new@editats@' +ats.ats_id + '@' + insert;
                    }else{
                        window.location.href = '/waypoint/new@new@editats@@insert';

                    }
                    // $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
                }
                else
                {
                    $('.select2-selection__choice:last').remove();
                    $('.select2-search__field').val(e.params.data.text).focus()
                }
            }else{
                // console.log(referensi)
                var ppp=e.params.data.text.split(' ');
                // console.log(ppp);
                if (referensi=='pointsatu'){
                    crd1=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=ppp[0] + '(' + crd1.WGS[1] + ' ' + crd1.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    aboutvol('search1');
                }else if (referensi=='pointdua'){
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    Calc(crd1,dist,crd2,bear);
                    var txtp=ppp[0] + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fix2point').html(Symbolpoint(txtp,e.params.data.id,'spoint2'));
                    aboutvol('search2');
                }
                referensi='';
            }
        });
    }
    if (refsearch=='ARPT'){
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
                if (referensi=='pointsatu'){
                    crd1=SetCoordinatebyGeom(e.params.data.geom);
                    var txtp=e.params.data.icao + '(' + crd1.WGS[1] + ' ' + crd1.WGS[0] + ')';
                    $('#fixpoint').html(Symbolpoint(txtp,e.params.data.id,'spoint1'));
                    aboutvol('search1');
                }else if (referensi=='pointdua'){
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    Calc(crd1,dist,crd2,bear);
                    var txtp=e.params.data.icao + '(' + crd2.WGS[1] + ' ' + crd2.WGS[0] + ')';
                    $('#fix2point').html(Symbolpoint(txtp,e.params.data.id,'spoint2'));
                    aboutvol('search2');
                }
                    referensi='';
        
                // }
            }
        });

                // crd2=SetCoordinatebyGeom(pnt2geom);
                
        // });
           
    }
   
   
    
}
function Calc(crd1,dist,crd2,bearing){
   
            FixCUrrent = GetPointWithIntersectionLinewithCircle(crd2, bearing, crd1,dist)
            // console.log(FixCUrrent,'FixCUrrent')
            $("#latitude").val(FixCUrrent.Database[1])
            $("#longitude").val(FixCUrrent.Database[0])
            if ($("#refpoint").is(':visible')==true){
                aboutvol('refpoint');
            }
        // TxtLon.Text = FixCUrrent.Longitude
 }
</script>
@endsection