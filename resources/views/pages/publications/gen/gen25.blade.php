@extends('layouts.app')

@section('template_title')
    GEN 2.5
@endsection

@section('head')
<style>
    .text-center {
    text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="panel-heading mt-3">
                <h5 class="panel-title text-center"">GEN 2.5 LIST OF RADIO NAVIGATION AIDS BY IDENTIFICATION ( ID )</h5>
            </div>
            <div class="panel-heading mt-3">
                <h6 class="panel-title text-center" style="color:red" id="infoid"></h6>
            </div>
            <div class="row" id="datalistarpt" style="visibility: visible">
                <div class="row">
                    <div class="col-md-12">
                        <button onclick="backtomenu()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                    </div>
                </div>
                <div class="col-md-12  mt-3">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th><a class="btn btn-sm btn-dim btn-dark mb-1" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</a></th>
                                <th>ID</th>
                                <th>Station</th>
                                <th>Facility</th>
                                <th>Purpose</th>
                            </tr>
                        </thead>
                        <tbody id="arptlist">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="datanewarpt" style="visibility: hidden">
                <div class="panel-heading mt-1">
                    <h6 class="panel-title"></h6>
                </div>
                <div class="panel-body">
                <form action="api/navarpt/save" method="POST" id="formulir">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editor" id="editor" value="{{ Auth::user()->id }}">
                <input type="hidden" name="arpt_ident" id="arpt_ident">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="nav_id" id="nav_id">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>ID</strong>
                            <br>
                            <select name="nav_id" class="form-control select2">
                        </div>
                        <div class="col-md-6" style="visibility: hidden">
                            <strong>ID</strong>
                            <br>
                            <input style="visibility: hidden" type="text" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <strong>Station</strong>
                            <br />
                            <select name="arpt_ident" class="arpt_ident form-control">
                        </div>
                        <div class="col-md-6" style="visibility: hidden">
                            <strong>ID</strong>
                            <br>
                            <input style="visibility: hidden" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div>
                </form>
                    <div class="row">
                        <div class="col-md-6">
                            <button onclick="isback()" class="btn btn-dim btn-light"><em class="icon ni ni-reply-fill"></em> Back</button>
                            &nbsp;
                            <button id="btn_formulir" class="btn btn-dim btn-dark"><em class="icon ni ni-save-fill"></em> Save</button>
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
$("#datanewarpt").hide();
var navtemp =@json($navaidstemp);
var navats =@json($nav);
console.log(navats.length);
var loc=[];fld= [
    'arpt_ident', 'nav_id','ils_id','status','seq'];
// console.log(navtemp)
// var cord=SetCoordinatebyGeom(arpt[0].geom)
// var arptident=a.arpt_ident;
var onreq=@json($onrequest);subid=@json($subid);
console.log(onreq)
var showedit=true;info='';
if (onreq.length > 0){
    var iic=onreq.findIndex(c=>c.fieldid===subid)
    if (iic !== -1){
        showedit=false;
        info ="Data is in the process of publication";
    }
}
$("#infoid").html(info)
var npush=[];npsuhcurr=[];
navtemp.forEach(n=>{
    // console.log(n.ils.length)
    var nnn={};
    if (n.navaid.length > 0){
        if (n.navaid[0].type === '20' || n.navaid[0].type === '9' || n.navaid[0].type === '11'){
        }else{
            nnn['id']=n.id
            nnn['nav_id']=n.navaid[0].nav_id
            nnn['arpt_ident']=n.airport[0].arpt_ident
            // navats[0].point.forEach(a=>{
            //     console.log(a)
            // })
            var x=navats[0].findIndex(x=>x.point2===n.navaid[0].nav_id)
            if (x==-1){
                nnn['purpose']='A';

            }else{
                nnn['purpose']='AE';

            }
        //     $.ajax({
        //         url:  'api/getpoint/ats/temp/' +n.navaid[0].nav_id,
        //         type: "json",
        //         method: "GET",

        //         success: function (result) {
        //             $.each(result.data, function (k, v) {
        //                 nnn['purpose']='AE';
                       
        //             })
        //         }
        // })
           
            nnn['ident']=n.navaid[0].nav_ident
            nnn['station']=n.airport[0].city_name + '/' + n.airport[0].arpt_name;
            nnn['facility']=n.navaid[0].definition;
           
            npush.push(nnn)

        }
    } 
    if (n.ils.length > 0){
        // console.log(n.ils)
        nnn['id']=n.id
        nnn['nav_id']=n.ils[0].ils_id
        nnn['arpt_ident']=n.airport[0].arpt_ident
        nnn['ident']=n.ils[0].ils_ident
        nnn['station']=n.airport[0].city_name + '/' + n.airport[0].arpt_name;
        nnn['facility']='ILS/LLZ';
        nnn['purpose']='A';
        npush.push(nnn)
    }
   
})
npush.sort( ( a, b ) => ( a.ident > b.ident ) ? 1 : ( ( b.ident > a.ident ) ? -1 : 0 ) );
npush.forEach(n=>{

// console.log(n)
        // console.log(loc.id)
        var  hsl= '<tr>'+
            '<td class="tb-tnx-action">'+
                '<div class="dropdown">'+
                    '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                    '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                        '<ul class="link-list-plain">';
                        if (showedit==true){
                            hsl +=  '<a class="btn btn-dim btn-primary col-md-12" id='+ n.nav_id +' onclick="edit(this.id)"><i class="icon ni ni-edit-alt-fill"></i> Edit</a>'+
                                '<a class="btn btn-dim btn-danger col-md-12" id='+ n.id +' onclick="remove(this.id)"><i class="icon ni ni-delete-fill"></i> Removed</a>';
                            
                        }
                        hsl += '</ul>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td>'+ n.ident +'</td>'+
            '<td>'+ n.station +'</td>'+
            '<td>'+ n.facility +'</td>'+
            '<td>'+ n.purpose +'</td>'+
        '</tr>';
        $("#arptlist").append(hsl)
    
})
function backtomenu(){
    window.location.href = '/aipsubmission/edit';
}
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Select Navaid...',
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
                                text:  item.nav_ident + ' - ' + item.nav_name + ' / ' + item.definition,
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
             Swal.fire({
                title: 'New Data',
                text: "Create New Navaid",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, created it!'

            }).then((result) => {
                    if (result.value) {
                        NewNavaid()
      
                    }else{
                        location.reload();

                    }
            })
            // var r = confirm("do you want to create a new frequency?");
            // if (r == true) {
            //     NewData()
            //     $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
            // }
            // else
            // {
            //     $('.select2-selection__choice:last').remove();
            //     $('.select2-search__field').val(e.params.data.text).focus()
            // }
        }else{
            $("#status").val('N');
            $("#nav_id").val(e.params.data.id);
            var hsl= navtemp.findIndex( x => x.nav_id === e.params.data.id )
            // console.log(navtemp,hsl,e.params.data.id)
            if (hsl !== -1 ){
                // console.log(navtemp[hsl])
                $("#nav_id").val('');
                $("#nav_id").focus();
                Swal.fire(
                    'Data Double',
                    'The data already exists '+ navtemp[hsl].airport[0].icao + ' - ' + navtemp[hsl].airport[0].city_name + '/' + navtemp[hsl].airport[0].arpt_name ,
                    'info'
                    )
            
            }
            // $("#freqid").val(e.params.data.id);
            // $("#arpt_ident").val(a.arpt_ident);
            // $("#seq").val(0);
            // Swal.fire({
            //     title: 'Insert Data',
            //     text: "The Frequency will be inserted!",
            //     icon: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#d33',
            //     confirmButtonText: 'Yes, inserted it!'

            // }).then((result) => {
            //         if (result.value) {
            //             $("#freqform").submit();

                        
            //         }else{
            //             location.reload();

            //         }
            // })
        }
    });
});

$(document).ready(function() {

    
    $('.arpt_ident').select2({
        placeholder: 'Select Station',
        minimumInputLength: 3,
        ajax: {
            url: "<?=url('/api/airport/search');?>",
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
                                text:  item.icao + ' - ' + item.city_name + ' / ' + item.arpt_name,
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
       
    }).on("arpt_ident:select", function(e) {
        if(e.params.data.isNew){
            var r = confirm("do you want to create a new frequency?");
            if (r == true) {
                $(this).find('[value="'+e.params.data.id+'"]').replaceWith('<option selected value="'+e.params.data.id+'">'+e.params.data.text+'</option>');
            }
            else
            {
                $('.select2-selection__choice:last').remove();
                $('.select2-search__field').val(e.params.data.text).focus()
            }
        }else{
            $("#arpt_ident").val(e.params.data.id);
        }
    });
// }
});
function edit(data) {
   var ix= npush.findIndex(x=>x.nav_id===data)
    if (data.substr(0,3)=='NAV'){
        window.location.href = '/navaidinfo/'+ data +"@edit@gen25@" + npush[ix].arpt_ident + '@';
        // window.location.href = '/navaidinfo/' + key + '@edit';
    }else if (data.substr(0,3)=='ILS'){
        console.log('goto ILS')
        window.location.href = '/ilsinfo/'+ data +"@edit@gen25@"+ npush[ix].arpt_ident;
        // window.location.href = '/ilsinfo/' + key + '@edit';
    }


    // $("#indicator").val(loc.indicator);
    // $("#city").val(loc.city);
    // $("#name").val(loc.name);
    // $("#ctry").val(loc.ctry);
}
function NewData(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");
    $("#status").val('N');

    
}
function NewNavaid(){
    window.location.href = '/navaid/new@new data@gen25@a e r o s s';
    window.scrollTo(0,0);
}
function remove(id){
    console.log(id)
    dtsrcraw={
        _token:"{{ csrf_token() }}",
        deleted:1,
        editor:"{{ Auth::user()->id }}",
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
                    $.ajax({
                        type: 'POST',
                        url: 'api/gen/locindicator/remove/' + id,
                        data: JSON.stringify(dtsrcraw),
                        success: response => {
                            
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                                )
                            location.reload();
                                // this.loadNavaidList(this.volradio)
                        }
                    })
                }
            })

}
function isback(){
    aboutvol("datanewarpt");
    aboutvol("datalistarpt");

}
function Checkdouble(id){
    console.log(id)
    var hsl= navtemp.findIndex( x => x.nav_id === id )

 
    
    if (hsl !== -1 ){
        console.log(navtemp[hsl])
        $("#"+id).val('');
        $("#"+id).focus();
        Swal.fire(
            'Data Double',
            'The data already exists '+ arpt[hsl].name + ' Airport ' + arpt[hsl].city ,
            'info'
            )
       
    }
   

    // console.log(val,hsl)
}
$('#btn_formulir').click(function() {
    if ($("#city").val()==''){
        Swal.fire(
            'Incomplete data',
            'please complete the data first' ,
            'info'
            )
    }else{
           $('#formulir').submit();
    }
       });

    // this.isList=false;
    // this.authlist=pia;
    // this.arpttype=atypes;





</script>
@endsection