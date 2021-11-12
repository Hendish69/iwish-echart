@extends('layouts.app')

@section('template_title')
    Airways
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-wrap">
            <div class="panel-heading mt-3">
                <h6 class="panel-title" id="atstitle"></h6>
            </div>
            <div class="panel-body mt-3">
                <form action="api/ats/save/temp" method="post"  enctype="multipart/form-data" id="atsform">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="seq_424" id="seq_424">
                <input type="hidden" name="ats_id" id="ats_id">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="point" id="point">
                <input type="hidden" name="point2" id="point2">
                <input type="hidden" name="ctry" id="ctry">
                <input type="hidden" name="insert" id="insert">
                <input type="hidden" name="affeft" id="affeft">
                <input type="hidden" name="lat1" id="lat1">
                <input type="hidden" name="lon1" id="lon1">
                <input type="hidden" name="lat2" id="lat2">
                <input type="hidden" name="lon2" id="lon2">
                <input type="hidden" name="geom" id="geom">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Country</strong>
                        <br>
                        <select selected="selected" class="form-control" id="country" name="country">
                           
                        </select>
                    </div>
                    <div class="col-md-2">
                        <strong>Ident</strong>
                        <br>
                        <input id="ats_ident" name="ats_ident" type="text" style="text-transform:uppercase" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <strong>Type</strong>
                        <br>
                        <select class="form-control" id="type" name="type">
                            @foreach($cod as $l)
                                <option  value="{{$l->id}}">{{ $l->definition }} </option>
                            @endforeach
                        </select>
                        <!-- <input type="text" class="form-control" v-model="currentats.type"> -->
                    </div>
                    <div class="col-md-2">
                        <strong>Level</strong>
                        <br>
                        <select class="form-control" id="level" name="level">
                            @foreach($level as $l)
                                <option  value="{{$l->id}}">{{ $l->definition }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mt-1">
                        <div class="card-inner table-bordered">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Ref. Point 1</strong>
                                    <br>
                                    <select class="form-control" id="refpoint1">
                                        <option  value="NA">NAVAID</option>
                                        <option  value="WP">WAYPOINT</option>
                                        <option  value="ID">AIRPORT</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Point 1 Type</strong>
                                    <br>
                                    <select class="form-control" name="wpt_type" id="wpt_type" >
                                        @foreach($wpttype as $l)
                                            <option  value="{{$l->id}}">{{ $l->definition }} </option>
                                        @endforeach
                                    </select>
                                    <!-- <input type="text" class="form-control" v-model="currentats.wpt_type"> -->
                                </div>
                                <div class="col-md-6">
                                    <strong>Point 1</strong>
                                    <br>
                                    <b><p id="point_1"></p></b>
                                </div>
                                <div class="col-md-6">
                                    <br>
                                    <p style="font-style:italic" id="cord1"></p>
                                </div>
                                <div class="col-md-12" id="search1" style="visibility: hidden">
                                    <select name="select2" id="select21" class="form-control select2">
                                </div>
                                <div class="col-md-12" style="visibility: hidden">
                                    <strong>ID</strong>
                                    <br>
                                    <input style="visibility: hidden" type="text" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-1">
                        <div class="card-inner table-bordered">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Ref. Point 2</strong>
                                    <br>
                                    <select class="form-control" id="refpoint2">
                                        <option  value="NA">NAVAID</option>
                                        <option  value="WP">WAYPOINT</option>
                                        <option  value="ID">AIRPORT</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Point 2 Type</strong>
                                    <br>
                                    <select class="form-control" name="wpt_type2" id="wpt_type2">
                                        @foreach($wpttype as $l)
                                            <option  value="{{$l->id}}">{{ $l->definition }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Point 2</strong>
                                    <br>
                                    <b><p id="point_2"></p></b>
                                </div>
                                <div class="col-md-6">
                                <br>
                                    <p style="font-style:italic" id="cord2"></p>
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
                        <strong>Bidirect</strong>
                        <br>
                        <select class="form-control" onchange="changebidirect()" name="bidirect" id="bidirect">
                            <option  value="N">NO</option>
                            <option  value="Y">YES</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <strong>Direction</strong>
                        <br>
                        <select class="form-control"  onchange="changedir424()" name="dir_424" id="dir_424">
                            <option  value="B">BACKWARD</option>
                            <option  value="F">FORWARD</option>
                            <option  value="">TWO WAY</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <strong>Track OUT</strong>
                        <br>
                        <input type="text" class="form-control" name="track_out" id="track_out" >
                    </div>
                    <div class="col-md-2">
                        <strong>Track IN</strong>
                        <br>
                        <input type="number" class="form-control" name="track_in" id="track_in" >
                    </div>
                    <div class="col-md-2">
                        <strong>Distance</strong>
                        <br>
                        <input type="number" class="form-control" name="dist" id="dist" >
                    </div>
                    <div class="col-md-2">
                        <strong>Lateral Limit</strong>
                        <br>
                        <input type="number" class="form-control" name="rnp_type" id="rnp_type">
                    </div>
                    <div class="col-md-2">
                        <strong>Upper</strong>
                        <br>
                        <input onfocusout="GetSearchasp()" type="text" class="form-control" maxlength="5" style="text-transform:uppercase" name="maa" id="maa">
                    </div>
                    <div class="col-md-2">
                        <strong>Lower</strong>
                        <br>
                        <input onfocusout="GetSearchasp()" type="text" class="form-control" maxlength="5" style="text-transform:uppercase" name="mfa" id="mfa">
                    </div>
                    <div class="col-md-2">
                        <strong>Mea</strong>
                        <br>
                        <input type="text" class="form-control" style="text-transform:uppercase" maxlength="5" name="mea_out" id="mea_out">
                    </div>
                    <div class="col-md-2">
                        <strong>Airspace Class</strong>
                        <br>
                        <input type="text" class="form-control" style="text-transform:uppercase" name="seg_use" id="seg_use">
                    </div>

                    <div class="col-md-12">
                        <strong>Remarks</strong>
                        <br>
                        <textarea type="text" class="form-control"  name="ats_remarks" id="ats_remarks"></textarea>
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
                        <a onclick="backtolist()" id="btn_backtolist" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                        &nbsp;
                        <a onclick="setMapPoint()" class="btn btn-dim btn-info"><i class="icon ni ni-map"></i> Set Point</a>
                        &nbsp;
                        <a onclick="update()" id="btn_update" class="btn btn-dim btn-dark"><i class="icon ni ni-save-fill"></i> Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="affectform" class="col-md-12 mt-3" style="visibility: hidden">
            <div class="panel-heading">
                <h6 class="panel-title">Controlling unit</h6>
            </div>
            <table class="table table-stripped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Airspace</th>
                        <th>Type</th>
                        <th>Lower</th>
                        <th>Upper</th>
                    </tr>
                </thead>
                <tbody id="aspaffect">
                </tbody>
            </table>
        </div>     
    </div>
    

@endsection
@section('footer_scripts')
<script type="text/javascript">
$("#search2").hide();
$("#search1").hide();
$("#affectform").hide();

var datacurr =@json($atscurr);refsearch='';crd1='',crd2='';crdtemp1='',crdtemp2='';ctry =@json($countries);
var datatemp =@json($ats);pnt1='';pnt2='';insert=@json($insert);
var statusdata=@json($status);trackout='';trackin='';distance='';atscurr=[];ats=[];
var prev=@json($atsprev);next=@json($atsnext);
ctry.sort((a,b) => (a.country > b.country) ? 1 : ((b.country > a.country) ? -1 : 0));
ctry.forEach(t=>{
    $("#country").append('<option value="'+t.ident+'">'+t.country+'</option>');
})
var fld=['id','ats_id','ctry','ats_ident','seq_424','dir_424','type', 'rnp_type','point','point2', 'point_1', 'wpt_type', 'wpt_type2', 'track_out', 'track_in', 'dist', 'maa', 'mfa', 'mea_out', 'bidirect','seg_use', 'level'];
var fldUp=['ats_ident', 'dir_424','type', 'rnp_type', 'point', 'wpt_type','point2', 'wpt_type2', 'track_out', 'track_in', 'dist', 'maa', 'mfa', 'mea_out', 'bidirect','seg_use', 'level','ats_remarks'];

if (statusdata=='R'){
    if (datacurr.length >0){
        atscurr=datacurr[0];
    }
    if (datatemp.length >0){
        ats=datatemp[0];
    }
    atsid=ats.ats_id;
    if (ats.ats_ident.length > 7){
        $('#atstitle').html(ats.ats_ident+ '<br>from '+ ats.point_1 + ' to ' + ats.point_2);

    }else{

        $('#atstitle').html(ats.ats_ident+ '<br>(' + ConverNumChart(ats.ats_ident)+') from '+ ats.point_1 + ' to ' + ats.point_2);
    }
    $('#country').val(ats.ctry.substr(ats.ctry.length -2));
    $('#status').val('R');
    $('#insert').val(insert);

    switch (insert) {
            case 'curr':
                $('#point_1').html(Symbolpoint(ats.point_1,ats.point,'spoint1'))
                $('#point_2').html(Symbolpoint(ats.point_2,ats.point2,'spoint2'))
                pnt1=ats.point;
                pnt2=ats.point2;
                var pnt1text,pnt2text,pnt1geom,pnt2geom;
                if (ats.wpt1.length >0){
                    pnt1text=ats.wpt1[0].wpt_name;
                    pnt1geom=ats.wpt1[0].geom;
                }else{
                    pnt1text=ats.nav1[0].nav_ident;
                    pnt1geom=ats.nav1[0].geom;
                }
                if (ats.wpt2.length >0){
                    pnt2text=ats.wpt2[0].wpt_name;
                    pnt2geom=ats.wpt2[0].geom;
                }else{
                    pnt2text=ats.nav2[0].nav_ident;
                    pnt2geom=ats.nav2[0].geom;
                }
                crd1=SetCoordinatebyGeom(pnt1geom);
                crd2=SetCoordinatebyGeom(pnt2geom);
                crdtemp1=SetCoordinatebyGeom(pnt1geom);
                crdtemp2=SetCoordinatebyGeom(pnt2geom);
                $("#lat1").val(crd1.Database[1]);
                $("#lon1").val(crd1.Database[0]);
                $("#lat2").val(crd2.Database[1]);
                $("#lon2").val(crd2.Database[0]);
                $('#cord1').html(crd1.WGS[1] + '  ' +crd1.WGS[0] );
                $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                    // calculate()
                break;
            case 'bp1':
                fld=['id','ats_id','ctry','ats_ident','type', 'rnp_type','wpt_type', 'wpt_type2', 'maa', 'mfa', 'mea_out', 'bidirect','seg_use', 'level'];
                $('#seq_424').val(ats.seq_424 - 1),
                // $('#ats_id').val('ATS_'+ats.ctry+'_'+ats.seq_424 - 1+'_'+ats.point);
                $('#point_1').html(Symbolnewpoint('New Point 1','spoint1'))
                $('#point_2').html(Symbolnewpoint(ats.point_1,ats.point,'spoint2'))
                pnt2=ats.point;
               
                if (ats.wpt1.length >0){
                    pnt2text=ats.wpt1[0].wpt_name;
                    pnt2geom=ats.wpt1[0].geom;
                }else{
                    pnt2text=ats.nav1[0].nav_ident;
                    pnt2geom=ats.nav1[0].geom;
                }
                crd2=SetCoordinatebyGeom(pnt2geom);
                crdtemp2=SetCoordinatebyGeom(pnt2geom);
                $('#point2').val(pnt2);
                $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                $("#lat2").val(crd2.Database[1]);
                $("#lon2").val(crd2.Database[0]);
                $('#status').val('N');
                break;
            case 'ap1':
                fld=['id','ats_id','ctry','ats_ident','type', 'rnp_type','wpt_type', 'wpt_type2', 'maa', 'mfa', 'mea_out', 'bidirect','seg_use', 'level'];
                $('#seq_424').val(ats.seq_424 + 1),
                $('#point_1').html(Symbolpoint(ats.point_1,ats.point,'spoint1'))
                $('#point_2').html(Symbolnewpoint('New Point 2','spoint2'))
                pnt1=ats.point;
                $('#point').val(pnt1);
                var pnt1text,pnt2text,pnt1geom,pnt2geom;
                if (ats.wpt1.length >0){
                    pnt1text=ats.wpt1[0].wpt_name;
                    pnt1geom=ats.wpt1[0].geom;
                }else{
                    pnt1text=ats.nav1[0].nav_ident;
                    pnt1geom=ats.nav1[0].geom;
                }
                crd1=SetCoordinatebyGeom(pnt1geom);
                crdtemp1=SetCoordinatebyGeom(pnt1geom);
                $('#cord1').html(crd1.WGS[1] + '  ' +crd1.WGS[0] );
                $("#lat1").val(crd1.Database[1]);
                $("#lon1").val(crd1.Database[0]);
                break;
            case 'ap2':
            var atsnext=[];
                fld=['id','ctry','ats_ident','type','rnp_type','wpt_type', 'wpt_type2', 'maa', 'mfa', 'mea_out', 'bidirect','seg_use', 'level'];
                var seq4=ats.seq_424 + 1;
                $('#seq_424').val(seq4);
                $('#ats_id').val('ATS_' + ats.ctry+'_'+seq4+'_'+ats.point2);
                $('#point_1').html(Symbolpoint(ats.point_2,ats.point2,'spoint1'))
                $('#point_2').html(Symbolnewpoint('New Point 2','spoint2'))
                pnt1=ats.point2;
                $('#point').val(pnt1);
                var pnt1text,pnt2text,pnt1geom,pnt2geom;
                if (ats.wpt2.length >0){
                    pnt1text=ats.wpt2[0].wpt_name;
                    pnt1geom=ats.wpt2[0].geom;
                }else{
                    pnt1text=ats.nav2[0].nav_ident;
                    pnt1geom=ats.nav2[0].geom;
                }
                crd1=SetCoordinatebyGeom(pnt1geom);
                crdtemp1=SetCoordinatebyGeom(pnt1geom);
                $('#cord1').html(crd1.WGS[1] + '  ' +crd1.WGS[0] );
                $("#lat1").val(crd1.Database[1]);
                $("#lon1").val(crd1.Database[0]);
                break;
        }
//    console.log(ats)
   compareisidata(fld,ats,atscurr);
    calculate()
  
    if (ats.dir_424==null || ats.dir_424==' '){
        $('#dir_424').val('');
    }
    $('#refpoint1').val(pnt1.substr(0,2));
    $('#refpoint2').val(pnt2.substr(0,2));
    
    
   
    // console.log(ats)
    // $('#pointid1').html('<i class="icon ni ni-edit"></i>&nbsp;' +pnt1text);
    // $('#pointid2').html('<i class="icon ni ni-edit"></i>&nbsp;' +pnt2text);
    
    
    if (ats.remarks.length >0){
    
        $('#ats_remarks').val(ats.remarks[0].remarks);
    }
}else{
    $('#atstitle').html('New Data');
    $('#country').val('ID');
    $('#type').val('W');
    $('#status').val('N');
    $('#seq_424').val('10');
    $('#point_1').html(Symbolnewpoint('New Point 1','spoint1'))
    $('#point_2').html(Symbolnewpoint('New Point 2','spoint2'))

    

 
    if (ats.dir_424==null || ats.dir_424==' '){
        $('#dir_424').val('');
    }
    $('#refpoint1').val('WP');
    $('#refpoint2').val('NA');
}
function calculate(){
       
    if (crd2 && crd1){
        var dist=getdistance(crd1.Decimal[1],crd1.Decimal[0],crd2.Decimal[1],crd2.Decimal[0])
        var trko=dist.TrackOutMagReal.toFixed();trkin=dist.TrackInMagReal.toFixed();
        trackout=numeral(trko).format('000');trackin=numeral(trkin).format('000');distance=dist.DistanceReal.toFixed(1);
        // console.log('CALCULATE.... ',trackout,trackin)
        $("#dist").val(distance);
        // console.log($("#dir_424").val(),'($("#dir_424").val()')
        switch ($("#dir_424").val()) {
            case '':
            case null:
                $("#track_out").val(trackout)
                $("#track_in").val(trackin)
                break;
            case 'F':
                $("#track_out").val(trackout)
                $("#track_in").val('')
                break;
            case 'B':
                $("#track_out").val('')
                $("#track_in").val(trackin)
                break;
        }
        var geom='LINESTRING(' + crd1.Decimal[0] + ' ' + crd1.Decimal[1] + ',' + crd2.Decimal[0] + ' ' + crd2.Decimal[1] + ')';
        $("#geom").val(geom)
       

    }
    // console.log(trackout,trackin,distance)
}
function changepoint(id){
    var referensi='';
    if (id=='spoint1'){
        referensi='pointsatu'
        if ($("#search2").is(':visible')==true){
            aboutvol('search2');
        }
        refsearch=$("#refpoint1").val();
        console.log('search1')
        aboutvol('search1');
    }else{
        referensi='pointdua'
        if ($("#search1").is(':visible')==true){
            aboutvol('search1');
        }
        refsearch=$("#refpoint2").val();
        console.log('search2')
        aboutvol('search2');
    }
// console.log(refsearch,referensi)
if (refsearch=='NA'){
    $('.select2').select2({
        placeholder: 'select navaid ...',
        minimumInputLength: 1,
        ajax: {
            url: 'api/navaid/search',
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
                                id: item.nav_id
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
                if ($("#status").val()=='R'){
                        window.location.href = '/navaid/new@new@editats@' +ats.ats_id + '@' + insert;
                    }else{
                        window.location.href = '/navaid/new@new data@editats@@';

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
            if (referensi=='pointsatu'){
                $("#point").val(e.params.data.id);
                $('#point_1').html(Symbolpoint(e.params.data.text,e.params.data.id,'spoint1'));
                crd1=SetCoordinatebyGeom(e.params.data.geom);
                $('#cord1').html(crd1.WGS[1] + '  ' +crd1.WGS[0] );
                $("#lat1").val(crd1.Decimal[1]);
                $("#lon1").val(crd1.Decimal[0]);
                $("#ats_id").val('ATS_'+ats.ctry+'_'+ats.seq_424+'_'+e.params.data.id);
                calculate()
                // console.log(e.params.data.id,'e.params.data.id')
                // console.log(e.params.data.text,'e.params.data.text')
                aboutvol('search1');
            }else if (referensi=='pointdua'){
                $("#point2").val(e.params.data.id);
                $('#point_2').html(Symbolpoint(e.params.data.text,e.params.data.id,'spoint2'));
                crd2=SetCoordinatebyGeom(e.params.data.geom);
                $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                $("#lat2").val(crd2.Decimal[1]);
                $("#lon2").val(crd2.Decimal[0]);
                calculate()
                aboutvol('search2');
            }
            referensi='';
        }
    });
}
    if (refsearch=='WP'){
        $('.select2').select2({
        placeholder: 'select waypoint ...',
        minimumInputLength: 3,
        ajax: {
            url: 'api/waypoint/search',
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
            console.log(e.params.data)
            if(e.params.data.isNew){
                var r = confirm("do you want to create a new waypoint?");
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
                    $("#point").val(e.params.data.id);
                    $('#point_1').html(Symbolpoint(ppp[0],e.params.data.id,'spoint1'));
                    crd1=SetCoordinatebyGeom(e.params.data.geom);
                    $('#cord1').html(crd1.WGS[1] + '  ' +crd1.WGS[0] );
                    $("#lat1").val(crd1.Decimal[1]);
                    $("#lon1").val(crd1.Decimal[0]);
                    $("#ats_id").val('ATS_'+ats.ctry+'_'+ats.seq_424+'_'+e.params.data.id);
                    calculate()
                    aboutvol('search1');
                }else if (referensi=='pointdua'){
                    $("#point2").val(e.params.data.id);
                    $('#point_2').html(Symbolpoint(ppp[0],e.params.data.id,'spoint2'));
                    // $("#point_2").html(e.params.data.text);
                    crd2=SetCoordinatebyGeom(e.params.data.geom);
                    $('#cord2').html(crd2.WGS[1] + '  ' +crd2.WGS[0] );
                    $("#lat2").val(crd2.Decimal[1]);
                    $("#lon2").val(crd2.Decimal[0]);
                    calculate()
                    aboutvol('search2');
                }
                referensi='';
            }
        });
    }
}
function Symbolnewpoint(point,vis){
    return '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown">'+point+'</a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-12">'+
                    '<ul class="link-list-plain">'+
                    '<a id="'+ vis +'" onclick="changepoint(this.id)" class="btn btn-dim btn-success col-md-12"><i class="icon ni ni-plus"></i> New Point</a>'+
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
function urayatsident(id){
    var ii=id.split(" ");hasil='';
    if (ii.length > 0){
        for (let i = 0; i < ii.length; i++) {
            var el='';
            if (ii[i].length > 4){
                el = ii[i];
            }else{
                el = ii[i].substr(0,1);
    
            }
            if (hasil==''){
                hasil = el;
            }else{
                hasil += el;
            }
            
        }

    }else{
        hasil=id;
    }
return hasil
}
function update(){
    console.log('update')
    var checkrwy=false;
    // $fld=['dir_424','direction','type', 'rnp_type', 'nav1','nav2','wpt1','wpt2','wpt_type','wpt_type2', 'track_out', 'track_in', 'dist', 'maa', 'mfa', 'mea_out', 'bidirect','level'];
    // var fldUp=['ats_ident', 'dir_424','type', 'rnp_type', 'point', 'wpt_type','point2', 'wpt_type2', 'track_out', 'track_in', 'dist', 'maa', 'mfa', 'mea_out', 'bidirect','seg_use', 'level'];\

    if ($("#mfa").val().toUpperCase()=='GND/WATER'){
        $("#mfa").val('GND')
    }
    if ($("#mea_out").val().toUpperCase()=='GND/WATER'){
        $("#mea_out").val('GND')
    }

    if  ($("#status").val()=='N'){
        var fldnew=['ats_id','ctry','ats_ident','seq_424','type', 'point','point2', 'wpt_type', 'wpt_type2', 'dist', 'maa', 'mfa', 'mea_out', 'bidirect','level'];
        var aiden= $("#ats_ident").val();
        if (aiden.length > 10){
            aiden= urayatsident(aiden);
        }
        var ain= aiden.replace('/', '_');
        var ctry= $("#country").val()
        $("#ctry").val(ain+'_'+ctry)
        var atsid='ATS_'+ain+'_'+ctry+'_'+$('#seq_424').val()+'_'+$("#point").val();
        $("#ats_id").val(atsid)
        changetouppercase(fldnew);
        checkrwy =checknewdata(fldnew);
    
    }else if ($("#status").val()=='R'){
        // console.log(ats.geom,$("#geom").val())
        checkrwy =checkupdatedata(fldUp,ats);
        if (ats.point !== atscurr.point || crd1 !== crdtemp1){
            console.log('beda')
            console.log('beda',ats.point , atscurr.point)
            console.log('beda', crd1 , crdtemp1)
        }

        // console.log('NAVUPDATE',checkrwy)
    };
    if (checkrwy==true ){
        $("#atsform").submit();
        console.log('Data Valid')
    }else{
        if (($("#status").val()=='N')){
            Swal.fire(
                'Incomplete data!',
                'Please complete the data first !',
                'success'
                )
        }else{
            
            backtolist();
        }
        console.log('Tidak ada perubahan data')
    }
   
}
function changebidirect(){
    // console.log()
    // trackout=numeral(dist.TrackOutMagReal.toFixed()).format('000'));trackin=numeral(dist.TrackInMagReal.toFixed().format('000'));
    if ($("#bidirect").val()=='Y'){
        $("#dir_424").val('')
        $("#track_out").val(numeral(trackout).format('000'))
        $("#track_in").val(numeral(trackin).format('000'))
    }else{
        $("#dir_424").val('F')
        $("#track_out").val(numeral(trackout).format('000'))
        $("#track_in").val('')
    }
}
function changedir424(){
    if ($("#dir_424").val()==''){
        $("#bidirect").val('Y')
        $("#track_out").val(numeral(trackout).format('000'))
        $("#track_in").val(numeral(trackin).format('000'))
    }else{
        $("#bidirect").val('N')
        if ($("#dir_424").val()=='F'){
            $("#track_out").val(numeral(trackout).format('000'))
            $("#track_in").val('')
        }else if ($("#dir_424").val()=='B'){
            $("#track_out").val('')
            $("#track_in").val(numeral(trackin).format('000'))
        }
    }
}
function backtolist(){
    // console.log('asda',ats)
    if  ($("#status").val()=='N'){
        history.back();
    }else{
        window.location.href = '/atsdetail/' + ats.ctry;
    }
    // if (ats.length > 0){
    //     window.location.href = '/atsdetail/' + ats[0].ctry;
    // }else{
       
    // }
}
function setMapPoint(){
    
    showdetail(atsid+'$atsseg');
}
function editpoint(id){
    // console.log(id,pnt1,pnt2,pnt1.substr(0,3));
    // if (id=='pointid1'){
        if (id.substr(0,3)=='WPT'){
            console.log('goto wpt',id)
            window.scrollTo(0,0);
            window.location.href = '/waypointinfo/' + id + '@edit@editats@' +ats.ats_id + '@' + insert;
            // window.scrollTo(0,0);
            // window.location.href = '/waypoint/' + pnt1 ;
        }else{
            window.scrollTo(0,0);
            window.location.href = '/navaidinfo/' + id + '@edit@editats@' +ats.ats_id  + '@' + insert;
            console.log('goto navaid',id)
        }
    // }else{
    //     if (pnt2.substr(0,3)=='WPT'){
    //         window.scrollTo(0,0);
    //         window.location.href = '/waypointinfo/' + pnt2 + '@edit@editats@' +ats.ats_id;
    //         // window.location.href = '/waypoint/' + pnt2;
    //     }else{
    //         window.scrollTo(0,0);
    //         window.location.href = '/navaidinfo/' + pnt2 + '@edit@editats@' +ats.ats_id;
    //         console.log('goto navaid',pnt2)
    //     }
    // }
    
}
function GetSearchasp() {
    var maa=$("#maa").val();
    var mfa=$("#mfa").val();
 

    if (maa !=='' && mfa !=='' ){
        // console.log(maa,mfa,crd1,crd2)
        this.upp = Fl2feet(maa.toUpperCase())
        this.low = Fl2feet(mfa.toUpperCase())
        this.navsearch = []
        this.resultsearch = []
        this.val = [crd1.Decimal[0], crd1.Decimal[1], crd2.Decimal[0], crd2.Decimal[1], this.upp, this.low]
        // console.log(this.val)
        this.rslt = false
        if (this.upp > this.low && this.low != null) {
            this.url = 'airspace/ats?a=' + this.val[0] + '&b=' + this.val[1] + '&c=' + this.val[2] + '&d=' + this.val[3] + '&e=' + this.val[4] + '&f=' + this.val[5]
            $("#aspaffect").empty();
        $.ajax({
                url: '/api/airspace/ats',
                data: {'a' : this.val[0],'b':this.val[1],'c':this.val[2],'d':this.val[3],'e': this.val[4],'f':this.val[5]},
                type: "json",
                method: "GET",

                success: function (result) {
                    // console.log(result)
                    if (result.data.length > 0){
                        if ($("#affectform").is(':visible')==false){
                                aboutvol('affectform');
                        }
                    }
                    $.each(result.data, function (k, v) {
                        if (v.type == 'FSS' || v.type == 'FIR'){
                        }else{
                            var hsl= '<tr><td>' + v.name + '</td><td>' + v.type + '</td><td>' + v.lower + '</td><td>' + v.upper + '</td></tr>'
                           $("#aspaffect").append(hsl);
                            // console.log(v);
                        }
                      
                        
                      
                    })
                }
        })
           
            // this.ats='ats'
        } else if (this.upp < this.low) {
            Swal.fire(
                'incorrect data!',
                'UPPER must be greater than LOWER !',
                'error'
                )
            // this.$alert('UPPER must be greater than LOWER', 'incorrect data', 'error');
        }
    }
           
    
}
</script>
@endsection