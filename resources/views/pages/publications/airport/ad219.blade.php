@extends('layouts.app')

@section('template_title')
Airport 
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
            <div class="panel-heading">
                <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
            </div>
            <div class="row mt-1">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr>
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewData()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th style="text-align:center">No</th>
                                <th style="text-align:center">Type of Aid</th>
                                <th style="text-align:center">ID</th>
                                <th style="text-align:center">Hours of Operation</th>
                                <th style="text-align:center">Freq</th>
                                <th style="text-align:center">Position of Antenna Coordinates</th>
                                <th style="text-align:center">Elev of DME Antenna</th>
                                <th style="text-align:center">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="commlist">
            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <form action="api/navarpt/save" method="POST" id="formulir">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="arpt_ident" id="arpt_ident">
                <input type="hidden" name="nav_id" id="nav_id">
                <input type="hidden" name="ils_id" id="ils_id">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="seq" id="seq">
        </form>
        <form action="api/navarpt/temp/remove" method="POST" id="formulirremove">
                <input type="hidden" name="_token" id="rem_token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="rem_editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="arpt_ident" id="rem_arpt_ident">
                <input type="hidden" name="nav_id" id="rem_nav_id">
                <input type="hidden" name="ils_id" id="rem_ils_id">
        </form>
        <div class="col-md-12" id="Newdata" style="visibility: hidden" >
            <p>New Data</p>
            <div class="custom-control custom-checkbox">
                <input class="form-check-input" type="radio" id="navbaru" name="ats" onclick="shownewdata(this.id)" value="atsrnav">
                <label class="form-check-label" for="atsrnav">Navaid</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input class="form-check-input" type="radio" id="ilsbaru" name="ats" onclick="shownewdata(this.id)" value="atsvfr">
                <label class="form-check-label" for="atsvfr">ILS</label>
            </div>
        </div>
        <div>
            <div class="col-md-12" id="NewNavaid" style="visibility: hidden" >
                <strong>Navaid</strong>
                <br />
                <select name="nav_id" class=" nav_id form-control" id="nav_id">
            </div>
            <div class="col-md-6" style="visibility: hidden">
                <strong>ID</strong>
                <br>
                <input style="visibility: hidden" type="text" class="form-control"/>
            </div>
            <div class="col-md-12" id="NewIls" style="visibility: hidden" >
                <strong>ILS</strong>
                <br />
                <select name="ils_id" class="ils_id form-control" id="ils_id">
            </div>
            <div class="col-md-6" style="visibility: hidden">
                <strong>ID</strong>
                <br>
                <input style="visibility: hidden" type="text" class="form-control"/>
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

$("#Newdata").hide();
$("#NewNavaid").hide();
$("#NewIls").hide();
var arpt =@json($airport);arp=arpt[0];
var no=0;
var nav =@json($navaids);
var ch=@json($channel);nil='NIL';
// console.log(nav);
var sama='';
nav.forEach(n=>{
    // console.log(n)
    var nav;def='';navid='';oh='';frq='';cord='';elev='';rem='';
    if (n.navaid.length > 0){
        nav =n.navaid[0];
        
        // console.log(nav.type);
        if (nav.type == 9 || nav.type == 11){
        }else{
            no++
            isitablenav(nav,'NAV',no,n.id)
        }
 
    }
    if (n.ils.length > 0){
        // console.log(a.ils[0]);
        no++
        nav =n.ils[0];
        isitablenav(n.ils[0],'ILS',no,n.id)
        no++
        isitablenav(nav,'GP',no,nav.id)
        if (nav.nav_id == null || nav.nav_id == 'NIL' || nav.nav_id == ''){
        }else{
            no++
            // console.log(nav.navaid[0],'DME')
            isitablenav(nav,'DME',no,n.id)
        }
        if (n.ils[0].thr.length >0){
            tps=' RWY '+ n.ils[0].thr[0].rwy_ident;
        }else{
            tps='';
        }
        nav.marker.forEach(m=>{
            no++
            isitablenav(m,m.mrkr_type,no,n.id,tps)

        });
    }
    

 

})

function isitablenav(nav,type,no,idnav,rwy=''){
    var tps='';ident='';frq='';hrs='';cord='';elev='';gbas='';rem='';navid='';
    var crd=[];
    crd=SetCoordinatebyGeom(nav.geom)
    cord=crd.WGS[1] + '<br>' + crd.WGS[0];
    var menu=true;
    switch(type){
        case "NAV":
            navid=nav.nav_id
            tps=nav.definition;
            ident=nav.nav_ident;
            if (nav.type=='4'){
                var frqd=FreqFormat(nav.freq,nav.type,'DATA');
                frq = FreqFormat(nav.freq,nav.type,'')+ '/' + 'CH-' +ch.find( x => x.definition === frqd ).id;
            }else if (nav.type=='20'){
                frq='';
                tps=nav.nav_name;
                ident='';
            }else{
                frq = FreqFormat(nav.freq,nav.type,'');
            }

            if (nav.opr_hrs==null || nav.opr_hrs==''){
                hrs=nil;
            }else{
                hrs=nav.opr_hrs;
            }
            elev=nav.dme_elev;
            if (elev==null || elev==''){
                elev='NIL';
            }
            rem=nav.remarks;
            if (rem==null || rem=='NIL'){
                rem='';
            }
            break;
        case "ILS":
            navid=nav.ils_id
            if (nav.thr.length >0){
                tps='ILS/LLZ RWY '+ nav.thr[0].rwy_ident;
            }else{
                tps='ILS/LLZ';
            }
            console.log(nav,'ILS');
            elev=nav.gs_hgt;
            ident=nav.ils_ident;
            frq = FreqFormat(nav.freq,'11','');
            if (nav.opr_hrs==null || nav.opr_hrs==''){
                hrs=nil;
            }else{
                hrs=nav.opr_hrs;
            }
            // elev='';
            rem=nav.remarks;
            if (rem==null || rem=='NIL'){
                rem='';
            }
            break;
        case "GP":
            menu=false;
            navid=nav.ils_id
            if (nav.thr.length >0){
                tps=type + ' RWY '+ nav.thr[0].rwy_ident;
            }else{
                tps=type;
            }
           
            ident='';
            crd=SetCoordinatebyGeom(nav.gs_geom)
            cord=crd.WGS[1] + '<br>' + crd.WGS[0];
            var frqd=FreqFormat(nav.freq,'11','DATA');
            frq = ch.find( x => x.definition === frqd ).gs_freq;
            if (nav.opr_hrs==null || nav.opr_hrs==''){
                hrs=nil;
            }else{
                hrs=nav.opr_hrs;
            }
            elev=nav.gs_elev;
            if (elev==null){
                elev='NIL';
            }
            rem=nav.remarks;
            if (rem==null || rem=='NIL'){
                rem='';
            }
            break;
        case "DME":
            menu=false;
            navid=nav.ils_id
            if (nav.thr.length >0){
                tps=type + ' RWY '+ nav.thr[0].rwy_ident;
            }else{
                tps=type;
            }
           
            ident='';
            crd=SetCoordinatebyGeom(nav.navaid[0].geom)
            cord=crd.WGS[1] + '<br>' + crd.WGS[0];
            // console.log(nav)
            var frqd=FreqFormat(nav.freq,'11','DATA');
            frq = 'CH-' + ch.find( x => x.definition === frqd ).id;
            if (nav.opr_hrs==null || nav.opr_hrs==''){
                hrs=nil;
            }else{
                hrs=nav.opr_hrs;
            }
            elev=nav.gs_elev;
            if (elev==null){
                elev='NIL';
            }
            rem=nav.navaid[0].remarks;
            if (rem==null || rem=='NIL'){
                rem='';
            }
            break;
        default:
            menu=false;
            navid=nav.mrkr_id;
           
                tps=type + rwy;
            
            // tps=type;
            ident='';
            frq = nav.freq;
            if (nav.opr_hrs==null || nav.opr_hrs==''){
                hrs=nil;
            }else{
                hrs=nav.opr_hrs;
            }
            elev='';
            rem=nav.remarks;
            if (rem==null || rem=='NIL'){
                rem='';
            }
            break;
    }
   
    if  (menu==false){
        hasil='<tr>'+
            '<td></td>';
    }else{
        hasil='<tr>'+
            '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                    '<ul class="link-list-plain">'+
                        '<li><a class="btn btn-dim btn-dark" id="'+ navid+'" onclick="navedit(this.id)"><i class="icon ni ni-edit"></i> Edit</a></li>'+
                        '<li><a class="btn btn-dim btn-light" id="'+idnav+'@'+navid+'" onclick="Editseq(this.id)"><i class="icon ni ni-pen2"></i> Edit Sequence</a></li>'+
                        '<li><a id="'+ navid+'" class="btn btn-dim btn-danger" onclick="remove(this.id)"><i class="icon ni ni-delete"></i>Delete</a></li>'+
                    '</ul>'+
                '</div>'+
            '</div>';
    }
   
    hasil += '<td>'+no+'</td>'+
            '<td>'+tps+'</td>'+
            '<td>'+ident + '</td>'+
            '<td>'+hrs+'</td>'+
            '<td>'+frq+'</td>'+
            '<td>'+cord+'</td>'+
            '<td>'+elev+'</td>'+
            '<td>'+rem+'</td>'+
        '</tr>';
        $("#commlist").append(hasil);
}
// console.log(aprons);
// console.log(twy);
// console.log(ps);
// console.log(pb);

var editform=false;editps=false;
var ttl=arp.icao + ' AD 2.19 RADIO NAVIGATION AND LANDING AIDS';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);

$(document).ready(function() {

    $('.nav_id').select2({
        placeholder: 'Select ID',
        minimumInputLength: 1,
        ajax: {
            url: "<?=url('/api/navaid/search');?>",
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
                                text:  item.nav_ident + ' - ' + item.nav_name + ' ' + item.definition,
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
                window.location.href = '/navaid/new@new data@ad219@' + arp.arpt_ident+'@';
                // $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
            }
            else
            {
                $('.select2-selection__choice:last').remove();
                $('.select2-search__field').val(e.params.data.text).focus()
            }
        }else{
            $("#nav_id").val(e.params.data.id);
            $("#arpt_ident").val(arp.arpt_ident);
            $("#seq").val(0);
            Swal.fire({
                title: 'Insert Data',
                text: "The Navaid will be inserted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, inserted it!'

            }).then((result) => {
                    if (result.value) {
                        $("#formulir").submit();
                    }else{
                        location.reload();

                    }
            })
        }
    });

    $('.ils_id').select2({
        placeholder: 'Select ID',
        minimumInputLength: 1,
        ajax: {
            url: "<?=url('/api/ils/search');?>",
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
                                text:  item.ils_ident + ' - ' + item.ils_name,
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
        if(e.params.data.isNew){
            Swal.fire({
                title: 'Create New Data',
                text: "New ILS will be inserted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, created it!'

            }).then((result) => {
                    if (result.value) {
                        window.location.href = '/ils/'+ e.params.data.text +'@new@ad219@'+ arp.arpt_ident;
                    }else{
                        location.reload();

                    }
            })
            // var r = confirm("do you want to create a new navaid?");
            // if (r == true) {
            //     window.location.href = '/ils/'+ e.params.data.text +'@new@ad219@'+ arp.arpt_ident;
            //     // $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
            // }
            // else
            // {
            //     $('.select2-selection__choice:last').remove();
            //     $('.select2-search__field').val(e.params.data.text).focus()
            // }
        }else{
            $("#ils_id").val(e.params.data.id);
            $("#arpt_ident").val(arp.arpt_ident);
            $("#seq").val(0);
            Swal.fire({
                title: 'Insert Data',
                text: "The Navaid will be inserted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, inserted it!'

            }).then((result) => {
                    if (result.value) {
                        $("#formulir").submit();
                    }else{
                        location.reload();

                    }
            })
        }
    });
});


function Editseq(data){
    $("#status").val('R')
    var dd=data.split('@');
    // console.log(nav,data)
   
    var ix=nav.findIndex(x=>x.id===Number(dd[0]));


   
    // console.log(ix);



    $("#id").val(nav[ix].id)
    $("#arpt_ident").val(nav[ix].arpt_ident)
    if (dd[1].substr(0,3)=='ILS'){
        $("#ils_id").val(dd[1]) 
    }else{
        $("#nav_id").val(dd[1]) 
    }
    
    console.log(nav[ix],ix)
    Swal.fire({
        title: "Sequence",
        text : 'Updated Sequence',
        input: 'number',
        showCancelButton: true,
    }).then((result) => {
        if (result.value) {
            $("#seq").val(result.value)
            $("#formulir").submit();
        }else{
            location.reload();
        }
    });
}
function setMapPoint() {
    this.url = '/map.php?table=obstacle&id=' + arp.arpt_ident
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open(this.url, 'Set Latitude and Longitude', params)
}
function shownewdata(id){
console.log(id)
if (id=='navbaru'){
    if ($("#NewIls").is(':visible')==true){
        aboutvol('NewIls');
    }
    aboutvol("NewNavaid")
}else{
    if ($("#NewNavaid").is(':visible')==true){
        aboutvol('NewNavaid');
    }
    aboutvol("NewIls")
}
}
function NewData(){
    $("#status").val('N') 
    aboutvol("Newdata")
   
}
function navedit(id){
    switch(id.substr(0,3)){
        case "NAV":
            // window.scrollTo(0,0);
            window.location.href = '/navaidinfo/'+ id +"@edit@edit219@" + arp.arpt_ident+'@';
            // window.location.href = '/navaidinfo/' + id + '@edit';
            break;
        case "ILS":
            window.location.href = '/ilsinfo/'+ id +"@edit@edit219@" + arp.arpt_ident;
            break;
        case "MRK":
            break;
    }

console.log(id)
    
}
function remove(id){
    console.log(id)
    $("#rem_arpt_ident").val(arp.arpt_ident);
    if (id.substr(0,3)=='ILS'){
        $("#rem_ils_id").val(id);
    }else{
        $("#rem_nav_id").val(id);
    }
   
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
            $("#formulirremove").submit();
        }else{
            location.reload();
        }
        
    })
    
  
}
function backtomain(){
    
    window.scroll(0,0);
    aboutvol('rwyedit');
    aboutvol('rwytable');
}


function backtolist(){
    window.location.href = '/editairport/'+ arp.arpt_ident;
    // editairport/ID00050
    // history.back();
}

</script>
@endsection