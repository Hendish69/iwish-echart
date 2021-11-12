@extends('layouts.app')

@section('template_title')
    Publication
@endsection

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body mt-3">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 id='judul' class="nk-block-title page-title"></h3>
                    </div>
                </div>
            </div>
            <div class="row mt" id="listamdt" style="visibility: visible">
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>Publication</th>
                                <th>Number</th>
                                <th>Publication Date</th>
                                <th>Effective Date</th>
                                <!-- <th>Effective Date</th> -->
                            </tr>
                        </thead>
                        <tbody id="arptlist">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt" id="listamdtdetail" style="visibility: hidden">
            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
                <div class="col-md-12">
                    <table class="datatable-init table table-bordered table-hover" id="table-content">
                        <thead class="thead-dark">
                            <tr align="center">
                                    <th>No</th>
                                    <th>Name</th>
                                    <!-- <th>Facility</th> -->
                            </tr>
                        </thead>
                        <tbody id="amdtdetail">

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
$("#listamdtdetail").hide();
var amdt =@json($amdt);
var page =@json($page).toUpperCase();
// console.log(amdt,page);
$("#judul").html('List of ' + page );amdtnr='';
amdt.forEach(v=>{
    if (v.pub_type.includes(page)){
        if (v.nr !== amdtnr){
            // console.log('ada',amdtnr,v.nr);
            hasil = '<tr class="nk-tb-item">'+
                    '<td style="cursor:pointer" id='+v.nr+' onclick="showdetailamdt(this.id)">'+v.pub_type+'</td>'+
                    '<td style="cursor:pointer" id='+v.nr+' onclick="showdetailamdt(this.id)">'+v.nr+'</td>'+
                    '<td style="cursor:pointer" id='+v.nr+' onclick="showdetailamdt(this.id)">'+v.pub_date+'</td>'+
                    '<td style="cursor:pointer" id='+v.nr+' onclick="showdetailamdt(this.id)">'+v.eff_date+'</td>'+
                '</tr>';
                $("#arptlist").append(hasil);
            }
            amdtnr=v.nr;
    }
        
    });

function backtolist(){
    aboutvol("listamdt");aboutvol("listamdtdetail");
    $("#judul").html('List of ' + page );
    // location.reload();
}
function showdetailamdt(nr){
    aboutvol("listamdt");aboutvol("listamdtdetail");
    $("#amdtdetail").empty();
   
    var pubt='';pubtype='';nr='';
    amdt.sort((a,b) => (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0));
    var nno=1;
    amdt.forEach(v=>{
        if (pubt!==v.name){
            pubtype=v.pub_type;nr=v.nr;
            hasil = '<tr class="nk-tb-item">'+
                        // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+p.sub_id+'</td>'+
                        '<td style="cursor:pointer" id='+v.rawdata_att_id+' onclick="showattachfile(this.id)">'+nno+'</td>'+
                        '<td style="cursor:pointer" id='+v.rawdata_att_id+' onclick="showattachfile(this.id)">'+v.name+'</td>'+
                        '</tr>';
                        // hasil = '<tr class="nk-tb-item">'+
                        // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+id+'</td>'+
                        // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+name+'</td>'+
                        // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+fac+'</td>'+
                        // '</tr>';
                        $("#amdtdetail").append(hasil);
                        nno++
        }


        pubt=v.name;

        // console.log('DATAAMDT',v)
        // if (v.nr ==nr){
        //     pubt=v.pub_type;
        //     var id='';name='';fac='';
        //     if (v.tablename=='arpt'){
        //         id= v.airport[0].icao;
        //         name= v.airport[0].city_name + '/' + v.airport[0].arpt_name ;
        //         fac= 'Airport';
        //     }else{
        //         id= v.tablename;
        //         name= v.fieldid ;
        //         fac= v.fieldid ;
        //     }
        //     v.attach.forEach(p=>{
        //         console.log(p)
        //         if (p.file_att=='P'){
        //             hasil = '<tr class="nk-tb-item">'+
        //             // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+p.sub_id+'</td>'+
        //             '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+p.name+'</td>'+
        //             '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+fac+'</td>'+
        //             '</tr>';
        //             // hasil = '<tr class="nk-tb-item">'+
        //             // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+id+'</td>'+
        //             // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+name+'</td>'+
        //             // '<td style="cursor:pointer" id='+v.rawdata_id+' onclick="showattachfile(this.id)">'+fac+'</td>'+
        //             // '</tr>';
        //             $("#amdtdetail").append(hasil);
        //         }
        //     })
            
            // console.log('ada');
           
        // }
        
    });
    $("#judul").html('List of ' + pubtype  + ' ' +nr)
}
function showattachfile(id){
    // console.log(id);
    let idx = amdt.findIndex(x => x.rawdata_att_id===Number(id));
    // console.log(idx,amdt)
    // var ix =amdt[idx].attach.length-1;
    var att=amdt[idx]
    // console.log(att);
    var dt=att.path_file + '/'+ att.filename;
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=300`;
    window.open("{{URL::to('/')}}/" + dt, 'airportcontent', params)
}


</script>
@endsection