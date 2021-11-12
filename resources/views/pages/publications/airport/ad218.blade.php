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
            <div class="panel-heading mt-3" id="backid" style="visibility: visible">
                <button onclick="backtolist()" class="btn btn-sm btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>
            </div>
            <!-- <div class="panel-heading">
                <button onclick="NewDataFreq()" class="btn btn-sm btn-dim btn-info"><i class="icon ni ni-plus"></i> Add</button>
            </div> -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr>
                                <th><a class="btn btn-sm btn-dim btn-dark" onclick="NewDataFreq()"><i class="icon ni ni-plus"></i> Add</a></th>
                                <th style="text-align:center">No</th>
                                <th style="text-align:center">Service designation</th>
                                <th style="text-align:center">Call sign</th>
                                <th style="text-align:center">Channel</th>
                                <th style="text-align:center">Hours of Operation</th>
                            </tr>
                        </thead>
                        <tbody id="commlist">
            
                        </tbody>
                    </table>
                </div>
            </div>
            <form action="api/freq/usage/save" method="post"  enctype="multipart/form-data" id="freqform">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="high_editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="status" id="status">
                    <input type="hidden" name="freqid" id="freqid">
                    <input type="hidden" name="arpt_ident" id="arpt_ident">
                    <input type="hidden" name="seq" id="seq">
            </form>
            <form action="api/freq/usage/update" method="post"  enctype="multipart/form-data" id="Seqform">
                    <input type="hidden" name="_token" id="seq_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="seq_editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="id" id="seq_id">
                    <input type="hidden" name="arpt_ident" id="seq_arpt_ident">
                    <input type="hidden" name="seq" id="seq_seq">
            </form>
            <form action="api/freq/usage/remove" method="post"  enctype="multipart/form-data" id="freqremove">
                    <input type="hidden" name="_token" id="high_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="editor" id="remove_editor" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="frequsedid" id="frequsedid">
            </form>
            <div class="row">
                <div class="col-md-12">
                    <br>
                </div>
            </div>
            <div class="col-md-12" id="newfreq" style="visibility: hidden" >
                <strong>Call Sign</strong>
                <br />
                <select name="call_sign" class="form-control select2" id="call_sign">
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

$('#newfreq').hide();
$('#rwyedit').hide();
var arpt =@json($airport);arp=arpt[0];
var no=0;
var freq =@json($freq);
// console.log(freq);
var sama='';
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Select Call Sign',
        minimumInputLength: 3,
        ajax: {
            url: "<?=url('/api/freq/search');?>",
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
                                text:  item.call_sign + ' - ' + item.types,
                                id: item.id
                            }
                        })
                };
            },
            cache: true
        },
        templateSelection: function (selection) {
            var result = selection.text.split('-');
            return result[0];
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
            var r = confirm("do you want to create a new frequency?");
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
            $("#freqid").val(e.params.data.id);
            $("#arpt_ident").val(arp.arpt_ident);
            $("#seq").val(0);
            Swal.fire({
                title: 'Insert Data',
                text: "The Frequency will be inserted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, inserted it!'

            }).then((result) => {
                    if (result.value) {
                        $("#freqform").submit();

                        
                    }else{
                        location.reload();

                    }
            })
        }
    });
});
function Editseq(data){
    $("#status").val('R')   
    console.log(data)
    $("#seq_id").val(data)
    $("#seq_arpt_ident").val(arp.arpt_ident)  
    Swal.fire({
        title: "Sequence",
        text : 'Updated Sequence',
        input: 'number',
        showCancelButton: true,
    }).then((result) => {
        if (result.value) {
            $("#seq_seq").val(result.value)
            $("#Seqform").submit();
        }else{
            location.reload();
        }
    });
}
function remove(id){
    console.log(id)
    $("#frequsedid").val(id)
    var dtsrcraw={
        _token:"{{ csrf_token() }}",
        deleted:'1',
    }

    Swal.fire({
        title: 'Deleted',
        text: "The data chart will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'

    }).then((result) => {
        if (result.value) {
            $("#freqremove").submit();

            
        }else{
            location.reload();

        }
    })
}

freq.forEach(fr=>{
    // console.log(a)
    var types=fr.types;cs=fr.call_sign;frq=fr.freq;
    if (fr.status == "Secondary"){
        frq +=  '*'
    }
    var nn;
    if (fr.id == sama){
        types='';cs='';nn='';
    }else{
        no++
        nn=no;
    }
   
    if (nn==''){
        hasil ='<tr>'+
            '<td></td>';
    }else{
        hasil ='<tr>'+
            '<td class="tb-tnx-action">'+
            '<div class="dropdown">'+
                '<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                '<div class="dropdown-menu dropdown-menu-left dropdown-menu-md">'+
                    '<ul class="link-list-plain">'+
                        '<li><a class="btn btn-dim btn-dark" id="'+ fr.id+'" onclick="editfreq(this.id)"><i class="icon ni ni-edit"></i> Edit</a></li>'+
                        '<li><a class="btn btn-dim btn-light" id="'+ fr.frequsedid+'" onclick="Editseq(this.id)"><i class="icon ni ni-pen2"></i> Edit Sequence</a></li>'+
                        '<li><a class="btn btn-dim btn-danger" id="'+ fr.frequsedid+'" onclick="remove(this.id)"><i class="icon ni ni-delete"></i>Delete</a></li>'+
                    '</ul>'+
                '</div>'+
            '</div></td>';
    }
   
    hasil +='<td>'+nn+'</td>'+
            '<td>'+types+'</td>'+
            '<td>'+cs + '</td>'+
            '<td>'+frq+'</td>'+
            '<td>'+fr.opr_hrs+'</td>'+
        '</tr>';
        $("#commlist").append(hasil);
        sama=fr.id;
})

// console.log(aprons);
// console.log(twy);
// console.log(ps);
// console.log(pb);
var ad2811='Apron'
var editform=false;editps=false;
var ttl=arp.icao + ' AD 2.18 ATS COMMUNICATION FACILITIES';
$("#arptidname").html(arp.icao + ' ' + arp.city_name +'/'+ arp.arpt_name);
$("#contentitle").html(ttl);

function NewDataFreq(){
    aboutvol("newfreq")
    
}
function NewData(){
    window.location.href = '/frequency/new@arpt@' + arp.arpt_ident;
}
function editfreq(id){
    window.scrollTo(0,0);
    window.location.href = '/frequency/'+ id +"@arpt@" + arp.arpt_ident;
}


function backtolist(){
    window.location.href = '/editairport/' + arp.arpt_ident;
}

</script>
@endsection