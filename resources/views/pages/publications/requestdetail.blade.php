@extends('layouts.app')

@section('template_title')
    Welcome  {{Auth::user()->name}}
@endsection

@section('head')
@endsection

@section('content')
<div class="col-lg-12">
    <div class="card card-bordered h-100">
        <div class="card-inner border-bottom">
            <div class="card-title-group">
                <div class="card-title">
                    <h6 class="title" id="arpttitle"></h6>
                </div>
            </div>
        </div>
        <div class="card-tools">
            <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
        </div>
        <div  class="card-inner">
            <div class="panel-heading mt-3">
    
            </div>
            <div class="panel-body mt-3">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark" align="center">
                                <tr>
                                    <!-- <th></th> -->
                                    <th>No</th>
                                    <th>AIP</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Current Value</th>
                                    <th>Request of change</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="detaillist">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
                <div class="card-tools">
                <a onclick="backtolist()" class="btn btn-dim btn-light"><i class="icon ni ni-reply-fill"></i> Back</a>
            </div>
        </div>
    </div><!-- .card -->
</div><!-- .col -->
@endsection
@section('footer_scripts')

<script type="text/javascript">

var ret =@json($data);enr=[];enrtemp=[];
// console.log(ret)



var isi=[];viewcontent=[];
switch (ret.table) {
    case 'arpt':
        airport()
        break;
    case 'navaid':
        navaid(ret)
        break;
    case 'waypoint':
        waypoint(ret)
        break;
    case 'ENR':
    case 'GEN':
        if (ret.id=='ENR 4.1' || ret.id=='GEN 2.5'){
            // console.log(ret.id);
            navlist(ret)
        }else  if (ret.id=='ENR 4.3'){
            // console.log(ret.id);
            wptlist(ret)
        }else  if (ret.id=='ENR 2.1'){
            // console.log(ret.id);
            asplist(ret)
        }else  if (ret.id=='GEN 2.4'){
            // console.log(ret.id);
            loclist(ret)
        }else  if (ret.id=='ENR 5.1' || ret.id=='ENR 5.2'){
            // console.log(ret.id);
            suaslist(ret)
        }else{
            ats(ret);
        }
        break;
    default:
        break;
}
// console.log('temp',temp);
// console.log('content',content);
// console.log(arpt);
// console.log('apron',apron);
// console.log('aprontemp',aprontemp);
// console.log(obs);
// console.log('ps',ps);
// console.log('pstemp',pstemp);
// console.log(rwy);
// console.log(thr);
// console.log(rwylgt);
// console.log(comm);
// console.log(nav);
function navlist(data){
    var no=0;
    // console.log(data)
    data.navtemp.forEach(n=>{
        var fldgen25=[
		'ctry','type', 'nav_ident','nav_name'
        ];
        fldgen25.forEach( a =>
        {
            var ix= data.nav.findIndex(x=>x.nav_id==n.nav_id)
            // console.log(a,ix,data.nav[ix][a]);
            var sts='U';
            var c=[];fcurr='';
            if (ix !== -1){
                c=data.nav[ix];
                fcurr=c[a];
            }
           
            var ftem=n[a];
            if (n[a]==null || n[a]=='NIL'){
                ftem='';
            }
            if (fcurr==null || fcurr=='NIL'){
                fcurr='';
            }
            if (typeof fcurr=='undefined'){
                sts='R';
            }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            }
            if (sts=='R'){
                // console.log(n)
                no++;
                hasil = '<tr><td>'+no+'</td><td>'+data.id +'</td><td>' + n.nav_ident +' '+  n.definition +'</td><td>' + a + '</td><td>' + fcurr + '</td><td>' + ftem + '</td><td>' + n.status_vld + '</td></tr>'
                $("#detaillist").append(hasil);
                
            }
        })

           
    })
    data.ilstemp.forEach(n=>{
        // console.log(n)
        var fldgen25=[
		'ils_ident','ils_name'
        ];
        fldgen25.forEach( a =>
        {
            var ix= data.ils.findIndex(x=>x.ils_id==n.ils_id)
            // console.log(a,ix,data.nav[ix][a]);
            var sts='U';
            var c=[];fcurr='';
            if (ix !== -1){
                c=data.ils[ix];
                fcurr=c[a];
            }
           
            var ftem=n[a];
            if (n[a]==null || n[a]=='NIL'){
                ftem='';
            }
            if (fcurr==null || fcurr=='NIL'){
                fcurr='';
            }
            if (typeof fcurr=='undefined'){
                sts='R';
            }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            }
            if (sts=='R'){
                // console.log(n)
                no++;
                hasil = '<tr><td>'+no+'</td><td>'+data.id +'</td><td>' + n.ils_ident + ' ILS/LLZ</td><td>' + a + '</td><td>' + fcurr + '</td><td>' + ftem + '</td><td>' + n.status + '</td></tr>'
                $("#detaillist").append(hasil);
                
            }
        })

           
    })
}

function loclist(data){
    $('#arpttitle').html(data.id);
    var no=0;
    data.indicatortemp.forEach(n=>{
        var fldgen25=[
		'indicator','name', 'city'
        ];
        fldgen25.forEach( a =>
        {
            var ix= data.indicator.findIndex(x=>x.loc_id==n.loc_id)
            // console.log(a,ix,data.nav[ix][a]);
            var sts='U';
            var c=[];fcurr='';
            if (ix !== -1){
                c=data.indicator[ix];
                fcurr=c[a];
            }
           
            var ftem=n[a];
            if (n[a]==null || n[a]=='NIL'){
                ftem='';
            }
            if (fcurr==null || fcurr=='NIL'){
                fcurr='';
            }
            if (typeof fcurr=='undefined'){
                sts='R';
            }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            }
            if (sts=='R'){
                // console.log(n)
                no++;
                hasil = '<tr><td>'+no+'</td><td>'+data.id +'</td><td>' + n.indicator +' '+  n.name +'</td><td>' + a + '</td><td>' + fcurr + '</td><td>' + ftem + '</td><td>' + n.status + '</td></tr>'
                $("#detaillist").append(hasil);
                
            }
        })

           
    })
}
function asplist(data){
    $('#arpttitle').html(data.id);
    var no=0;
    // console.log(data)
    aspdet=[];
    data.asptemp.forEach(n=>{
        if (n.airspace_type== 'AFIZ' || n.airspace_type=='ATZ' || n.airspace_type=='CTR'){

        }else{

            var fld=[
            'airspace_name', 'airspace_type', 'airspace_rnp', 'rvsm', 'icao_acc', 'ctry', 'rvsm_upper', 'rvsm_lower', 'ats_unit']
            var fldnm=['Airspace Name', 'Type', 'RNP', 'RVSM', 'ICAO', 'Country', 'RVSM UPPER', 'RVSM LOWER', 'ATS UNIT']
            var dcurr=null;
            var idx=0;
            fld.forEach( a =>
            {
                var ix= data.asp.findIndex(x=>x.ats_airspace_id==n.ats_airspace_id)
                // console.log(a,ix);
                var sts='U';
                var c=[];fcurr='';
                if (ix == -1){
                    sts='N';
                }else{
                    c=data.asp[ix];
                    dcurr=c;
                    fcurr=c[a];
                   
                }
                var ftem=n[a];
                if (ftem==null || ftem=='NIL'){
                    ftem='';
                }
                if (fcurr==null || fcurr=='NIL'){
                    fcurr='';
                }
                if ( sts =='U'){
                    if (typeof fcurr=='undefined'){
                        sts='R';
                    }else{
                        if (ftem !== fcurr){
                            sts='R';
                        }
                    }
                    
                }
                if (ftem=='' && sts=='N'){
                    sts='U';
                }
                // console.log(ftem,fcurr)
                if (sts !=='U'){
                    no++;
                    isi=[{category:'ENR 2.1'},{id:n.airspace_name +' ' + n.airspace_type },{field:a},{item:fldnm[idx]},{curvalue:fcurr},{reqvalue:ftem},{status:sts},{seq:no},{arpt_ident:n.ats_airspace_id},{table:'airspace_temp'}]
                   
                    
                }
                idx++
            })
            if (n.class.length >0){
                var cls=n.class[0];
                var fld=['asp_class', 'asp_sector', 'upper', 'lower','remarks']
                var fldnm=['Class', 'Sector', 'Upper', 'Lower','Remarks']
                var idx=0;
                    fld.forEach( a =>{
                        var sts='U';
                        var ctem=cls[a]; ccurr='';
                        if (dcurr !== null){
                            ccurr=dcurr.class[0][a]
                        }
                        if (ctem==null || ctem=='NIL'){
                            ctem='';
                        }
                        if (ccurr==null || ccurr=='NIL'){
                            ccurr='';
                        }
                       
                        if ( sts =='U'){
                            if (typeof ccurr=='undefined'){
                                sts='R';
                            }else{
                                if (ctem !== ccurr){
                                    sts='R';
                                }
                            }
                            
                        }
                        if (sts !=='U'){
                            no++;
                            isi=[{category:'ENR 2.1'},{id:n.airspace_name +' ' + n.airspace_type },{field:a},{item:fldnm[idx]},{curvalue:ccurr},{reqvalue:ctem},{status:sts},{seq:no},{arpt_ident:n.ats_airspace_id},{table:'airspace_class_temp'}]
                            aspdet.push(isi)
                           
                            // hasil = '<tr><td>'+no+'</td><td>'+data.id +'</td><td>' + n.airspace_name +' ' + n.airspace_type + '</td><td>' + fldnm[idx] + '</td><td>' + ccurr + '</td><td>' + ctem + '</td><td>' + sts + '</td></tr>'
                            // $("#detaillist").append(hasil);
                    
                        }
                        idx++
                    })
                    // console.log(n,dcurr)
                
                }
                if (n.boundary.length >0){
                    var cls=n.boundary;bdry=[];
                    var bcurr=dcurr;
    
                    if (bcurr !== null){
                        if (bcurr.boundary.length >0){
                            bdry=dcurr.boundary;
                        }
                    }
                    // console.log(bcurr,'dcurr.boundary')
                    cls.forEach(b=>{
                        if (b.shap !== 'G'){
    
                            // console.log(b)
                            var fld=['point1_lat', 'point1_long', 'shap', 'arc_dist', 'arc_lat', 'arc_long']
                            var fldnm=['Latitude', 'Longitude', 'Shap', 'Radius', 'Center Lat', 'Center Lon']
                            var idx=0;
                                fld.forEach( a =>{
                                
                                var ix= bdry.findIndex(x=>x.id==Number(b.id))
                                // console.log(bdry,'bdry');
                                var sts='U';
                                var c=[];fcurr='';
                                if (ix == -1){
                                    sts='N';
                                }else{
                                    c=bdry[ix];
                                    fcurr=c[a];
                                
                                }
                                var ftem=b[a];
                                if (ftem==null || ftem=='NIL'){
                                    ftem='';
                                }
                                if (fcurr==null || fcurr=='NIL'){
                                    fcurr='';
                                }
                                // console.log(ftem,fcurr,n.airspace_name,'n.airspace_name');
    
                                if ( sts =='U'){
                                    if (typeof fcurr=='undefined'){
                                        sts='R';
                                    }else{
                                        if (ftem !== fcurr){
                                            sts='R';
                                        }
                                    }
                                    
                                }
                                if (ftem=='' && sts=='N'){
                                    sts='U';
                                }
                                // console.log(ftem,fcurr)
                                if (sts !=='U'){
                                    no++;
                                    isi=[{category:'ENR 2.1'},{id:n.airspace_name +' ' + n.airspace_type },{field:a},{item:fldnm[idx]},{curvalue:fcurr},{reqvalue:ftem},{status:sts},{seq:b.air_seq},{arpt_ident:b.id},{table:'airspace_seg_temp'}]
                                    aspdet.push(isi)
                                    // hasil = '<tr><td>'+no+'</td><td>'+data.id +'</td><td>' + n.airspace_name +' ' + n.airspace_type + '</td><td>' +  fldnm[idx] + '</td><td>' + fcurr + '</td><td>' + ftem + '</td><td>' + sts + '</td></tr>'
                                    // $("#detaillist").append(hasil);
                                    
                                }
                                idx++
                            })
                        }
                    })
                    // console.log(n,dcurr)
                
                }
        }
    })
    var no=1;id=''
    aspdet.forEach(a=>{
        // console.log(a)
        if (a[1].id !==id){
            id=a[1].id;
        }else{
            id=''; 
        }
        var sts='Request'
        if (a[6].status=='N'){
            sts='New Data'
        }
        hasil = '<tr><td>'+no+'</td><td>'+a[0].category +'</td><td>' + id + '</td><td>' +  a[3].item + '</td><td>' + a[4].curvalue + '</td><td>' + a[5].reqvalue + '</td><td>' + sts + '</td></tr>'
        $("#detaillist").append(hasil);
        id=a[1].id;
        no++
    })
}

function suaslist(data){
    $('#arpttitle').html(data.id);
    var no=0;
    // console.log(data)
    data.suastemp.forEach(n=>{
        var fld=[
            'suas_ident', 'suas_sector', 'suas_name', 'suas_type', 'ctry', 'upper', 'lower']
        fld.forEach( a =>
        {
            var ix= data.suas.findIndex(x=>x.suas_id==n.suas_id)
            // console.log(a,ix,data.asp[ix][a]);
            var sts='U';
            var c=[];fcurr='';
            if (ix !== -1){
                c=data.suas[ix];
                fcurr=c[a];
            }
            var ftem=n[a];
            if (ftem==null || ftem=='NIL'){
                ftem='';
            }
            if (fcurr==null || fcurr=='NIL'){
                fcurr='';
            }
            if (typeof fcurr=='undefined'){
                sts='R';
            }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            }
            
            // console.log(ftem,fcurr)
            if (sts=='R'){
                no++;
                hasil = '<tr><td>'+no+'</td><td>'+data.id +'</td><td>' + n.suas_ident +' ' + n.suas_name + '</td><td>' +  a + '</td><td>' + fcurr + '</td><td>' + ftem + '</td><td>' + n.status + '</td></tr>'
                $("#detaillist").append(hasil);

                // n.boundary.forEach(b=>{
                //     console.log(b);
                // })
                
            }
        })

           
    })
}
function wptlist(data){
    $('#arpttitle').html(data.id);
    var no=0;
    // console.log(data)
    data.wpttemp.forEach(n=>{
        var fldgen25=[
		'ctry','type', 'wpt_name','desc_name'
        ];
        fldgen25.forEach( a =>
        {
            var ix= data.wpt.findIndex(x=>x.wpt_id==n.wpt_id)
            // console.log(a,ix,data.wpt[ix][a]);
            var sts='U';
            var c=[];fcurr='';
            if (ix !== -1){
                c=data.wpt[ix];
                fcurr=c[a];
            }
            var ftem=n[a];
            if (n[a]==null || n[a]=='NIL'){
                ftem='';
            }
            if (fcurr==null || fcurr=='NIL'){
                fcurr='';
            }
            if (typeof fcurr=='undefined'){
                sts='R';
            }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            }
            if (sts=='R'){
                var status='Request'
                if (n.status=='N'){
                    status='New Data'
                }
                // console.log(n)
                no++;
                hasil = '<tr><td>'+no+'</td><td>'+data.id +'</td><td>' + n.wpt_name + '</td><td>' +  a + '</td><td>' + fcurr + '</td><td>' + ftem + '</td><td>' + status + '</td></tr>'
                $("#detaillist").append(hasil);
                
            }
        })

           
    })
}
function ats(data){
    $('#arpttitle').html(data.id);
    var no=1;
    var atstemp=[];atscurr=[];
    
    data.ats.forEach(b=>{
        atscurr.push(b[0]);
    })
    // console.log(atscurr)

    data.atstemp.forEach(a=>{
        // console.log(a)
        var isi=false;
        switch (data.id) {
            case 'ENR 3.1':
                if (a.type=='W' && a.ats_ident.substr(0,1)=='W'){
                    isi=true;
                }
                
                break;
                case 'ENR 3.2':
                    if (a.type=='W' && a.ats_ident.substr(0,1) !=='W'){
                        isi=true;
                    }
                    break;
                case 'ENR 3.3':
                    if (a.type=='R'){
                        isi=true;
                    }
                break;
            case 'ENR 3.4':
                if (a.type=='V'){
                    isi=true;
                }
                break;
        
          
        }
        
        if (isi==true){
            var at=[];

            if (atscurr.length > 0){
                // console.log(data.ats[0])
                var ix =atscurr.findIndex(x=>x.id===a.id)
                // console.log(a.ats_id,ix,a.status)
               
                if (ix !== -1){
                    var at=data.ats[ix];
                    // console.log(at,a)

                }
                
            }
           
            // no++;
            no=tampillistats(a,at,no)

           
        }
    })
}
function tampillistats(temp,curr,nom){
    var a=temp;at=curr;
    
    // console.log(temp,curr)
    
    var a=temp;aipsub='';
    var no=nom;
    var fld=['dir_424','direction','type', 'rnp_type', 'nav1','nav2','wpt1','wpt2','wpt_type','wpt_type2', 'track_out', 'track_in', 'dist', 'maa', 'mfa', 'mea_out', 'bidirect','level','remarks'];
    var fldname=['dir_424','direction','Type', 'Lateral Limit', 'nav1','nav2','wpt1','wpt2','wpt_type','wpt_type2', 'Track Out', 'Track In', 'Distance', 'Upper', 'Lower', 'Min Alt', 'Bidirect','Level','Remarks'];
            switch (a.type) {
            case 'W':
                if (a.ats_ident.substr(0,1)=='W'){
                    aipsub='ENR 3.1';
                }else{
                    aipsub='ENR 3.2';
                }

                break;
            case 'R':
                aipsub='ENR 3.3';
                break;
            case 'V':
                aipsub='ENR 3.4';
                break;
        }
            // var ix =curr.findIndex(x=>x[0].ats_id===a.ats_id)
            // var at=curr;
            // console.log(ix,curr[ix][0])
            // console.log(at)
            var iidx=-1;
            fld.forEach(f=>{
                iidx++
                // console.log(at.length)
                var w2='';
                if (f=='nav1' || f=='nav2' || f=='wpt1' || f=='wpt2' || f=='remarks'){
                    var fnav=['nav_ident','lat','lon'];
                    var fwpt=['wpt_name','lat','lon'];
                    if (f=='nav1' && a.nav1.length > 0){
                        var crdtemp=SetCoordinatebyGeom(a.nav1[0].geom)
                        a.nav1[0]['lat']=crdtemp.Database[1]
                        a.nav1[0]['lon']=crdtemp.Database[0]
                        var nav1=false;
                        if (at.length>0){
                            if (at[0].nav1.length > 0){
                                nav1=true;
                                crdtemp=SetCoordinatebyGeom(at[0].nav1[0].geom)
                                at[0].nav1[0]['lat']=crdtemp.Database[1]
                                at[0].nav1[0]['lon']=crdtemp.Database[0]
                            }
                        }
                        fnav.forEach(fn=>{
                            var isinav1='';status='Request'
                            if ( nav1==true){
                            // console.log(a.nav1[0][fn] , at[0].nav1[0][fn],a.nav1[0][fn] !== at[0].nav1[0][fn])
                                // if (a.nav1[0][fn] !== at[0].nav1[0][fn]){
                                    isinav1=at[0].nav1[0][fn]
                                // }
                            }else{
                                status='New Data'
                            }
                            // console.log(a.nav1[0][fn] , isinav1,a.nav1[0][fn] !== isinav1)
                            if (a.nav1[0][fn] !== isinav1){
                                hasil = '<tr><td>'+no+'</td><td>'+aipsub +'</td><td>' + a.ats_ident + ' ('+ a.point_1 + '-' + a.point_2 +')</td><td>Point 1</td><td>' + isinav1 + '</td><td>' + a.nav1[0][fn] + '</td><td>' + status + '</td></tr>'
                                $("#detaillist").append(hasil);
                                no++;
                                // console.log('beda',f)
                            }
                        })
                    // console.log('beda',a.nav1[0])
                    }else if (f=='nav2' && a.nav2.length > 0){
                        var crdtemp=SetCoordinatebyGeom(a.nav2[0].geom)
                        a.nav2[0]['lat']=crdtemp.Database[1]
                        a.nav2[0]['lon']=crdtemp.Database[0]
                        w2='';
                        var nav2=false;
                        if (at.length>0){
                           
                            if (at[0].nav2.length > 0){
                                nav2=true;
                                crdtemp=SetCoordinatebyGeom(at[0].nav2[0].geom)
                                at[0].nav2[0]['lat']=crdtemp.Database[1]
                                at[0].nav2[0]['lon']=crdtemp.Database[0]
                            }
                        }
                        fnav.forEach(fn=>{
                            var isinav2='';status='Request'
                            if ( nav2==true){
                               
                                // if (a.nav2[0][fn] !== at[0].nav2[0][fn]){
                                    isinav2=at[0].nav2[0][fn]
                                // }
                            }else{
                                status='New Data'
                            }
                            if (a.nav2[0][fn] !== isinav2){
                                hasil = '<tr><td>'+no+'</td><td>'+aipsub +'</td><td>' + a.ats_ident + ' ('+ a.point_1 + '-' + a.point_2 +')</td><td>Point 2</td><td>' + isinav2 + '</td><td>' + a.nav2[0][fn] + '</td><td>' + status + '</td></tr>'
                                $("#detaillist").append(hasil);
                                no++;
                                // console.log('beda',f)
                            }
                        })
                    }else if (f=='wpt1' && a.wpt1.length > 0){
                        var crdtemp=SetCoordinatebyGeom(a.wpt1[0].geom)
                        a.wpt1[0]['lat']=crdtemp.Database[1]
                        a.wpt1[0]['lon']=crdtemp.Database[0]
                        w2='';
                        var wpt1=false;
                        if (at.length>0){
                           
                            if (at[0].wpt1.length > 0){
                                wpt1=true;
                                crdtemp=SetCoordinatebyGeom(at[0].wpt1[0].geom)
                                at[0].wpt1[0]['lat']=crdtemp.Database[1]
                                at[0].wpt1[0]['lon']=crdtemp.Database[0]
                            }
                        }
                        fwpt.forEach(fn=>{
                            var isiwpt1='';status='Request'
                            if ( wpt1==true){
                               
                                // if (a.wpt1[0][fn] !== at[0].wpt1[0][fn]){
                                    isiwpt1=at[0].wpt1[0][fn]
                                // }
                            }else{
                                status='New Data'
                            }
                            if (a.wpt1[0][fn] !== isiwpt1){
                                hasil = '<tr><td>'+no+'</td><td>'+aipsub +'</td><td>' + a.ats_ident + ' ('+ a.point_1 + '-' + a.point_2 +')</td><td>Point 1</td><td>' + isiwpt1 + '</td><td>' + a.wpt1[0][fn] + '</td><td>' + status + '</td></tr>'
                                $("#detaillist").append(hasil);
                                no++;
                                // console.log('beda',f)
                            }
                        })
                        // console.log('beda',a.wpt1[0])
                    }else if (f=='wpt2' && a.wpt2.length > 0){
                        var crdtemp=SetCoordinatebyGeom(a.wpt2[0].geom)
                        a.wpt2[0]['lat']=crdtemp.Database[1]
                        a.wpt2[0]['lon']=crdtemp.Database[0]
                        w2='';
                        var wpt2=false;
                        if (at.length>0){
                           
                            if (at[0].wpt2.length > 0){
                                wpt2=true;
                                crdtemp=SetCoordinatebyGeom(at[0].wpt2[0].geom)
                                at[0].wpt2[0]['lat']=crdtemp.Database[1]
                                at[0].wpt2[0]['lon']=crdtemp.Database[0]
                            }
                        }
                        fwpt.forEach(fn=>{
                            var isiwpt2='';status='Request'
                            if ( wpt2==true){
                              
                                // if (a.wpt2[0][fn] !== at[0].wpt2[0][fn]){
                                    isiwpt2=at[0].wpt2[0][fn]
                                // }
                            }else{
                                status='New Data'
                            }
                            if (a.wpt2[0][fn] !== isiwpt2){
                                hasil = '<tr><td>'+no+'</td><td>'+aipsub +'</td><td>' + a.ats_ident + ' ('+ a.point_1 + '-' + a.point_2 +')</td><td>Point 2</td><td>' + isiwpt2 + '</td><td>' + a.wpt2[0][fn] + '</td><td>' + status + '</td></tr>'
                                $("#detaillist").append(hasil);
                                no++;
                                // console.log('beda',f)
                            }
                        })
                    }else if (f=='remarks' && a.remarks.length > 0){

                        var rmks =a.remarks[0].remarks;oldrmks='';
                     
                        if (at.length>0){
                           
                            if (at[0].remarks.length > 0){
                                oldrmks=at[0].remarks[0].remarks
                            }
                        }
                        if (rmks !== oldrmks){
                                hasil = '<tr><td>'+no+'</td><td>'+aipsub +'</td><td>' + a.ats_ident + ' ('+ a.point_1 + '-' + a.point_2 +')</td><td>Remarks</td><td>' + oldrmks + '</td><td>' + rmks + '</td><td>' + status + '</td></tr>'
                                $("#detaillist").append(hasil);
                                no++;
                                // console.log('beda',f)
                            }

                    }
                }else{
                    var isiats='';status='Request';isireq=a[f];
                    if (isireq==null){
                        isireq='';
                    }
                    // console.log('a.status',a.status)
                    // if (a.status=='N'){
                    //     status='New Data'
                    // }
                    if(at.length>0){
                        isiats=at[0][f];
                        if (isiats==null){
                            isiats='';
                        }
                    }else{
                        status='New Data'
                    }
                    if (isireq !== isiats){
                        // console.log('beda',f)
                        hasil = '<tr><td>'+no+'</td><td>'+aipsub +'</td><td>' + a.ats_ident + ' ('+ a.point_1 + '-' + a.point_2 +')</td><td>' +  fldname[iidx] + '</td><td>' + isiats + '</td><td>' + isireq + '</td><td>' + status + '</td></tr>'
                        $("#detaillist").append(hasil);
                        no++;
                    }
                }
            })
            return no;
}
function waypoint(temp){
    // console.log(temp)
    var no=0;
    var fld=[
        'wpt_name', 'desc_name', 'ctry', 'type', 'usage_cd', 'mag_var', 'latitude','longitude'
    ];
    
    var fld43=[
		'wpt_name', 'desc_name','latitude','longitude'
    ];
    var fldenr=[
		'wpt_name', 'desc_name','latitude','longitude'
    ];
    var ncurr=[];
    var ntemp=temp.wpttemp[0];
    $('#arpttitle').html(ntemp.wpt_name + ' ' + ntemp.definition )
    var crdtemp=SetCoordinatebyGeom(ntemp.geom)
    ntemp['latitude']=crdtemp.Database[1]
    ntemp['longitude']=crdtemp.Database[0]
    if (temp.wptcurr){
        ncurr=temp.wptcurr[0];
        crdcurr=SetCoordinatebyGeom(ncurr.geom)
        ncurr['latitude']=crdcurr.Database[1]
        ncurr['longitude']=crdcurr.Database[0]
    }else{
        ncurr['latitude']='';
        ncurr['longitude']='';
    }
    var ad219=[];enr41=[];gen25=[];
    enr=[];enrtemp=[];
    fld.forEach( a =>
    {

        var sts='U';
        var ftem=ntemp[a];fcurr=ncurr[a];
        if (ntemp[a]==null || ntemp[a]=='NIL'){
            ftem='';
        }
        if (ncurr[a]==null || ncurr[a]=='NIL'){
            fcurr='';
        }
        if (typeof ncurr=='undefined'){
            sts='R';
        }else{
            if (ftem !== fcurr){
                sts='R';
            }
        }
        if (sts=='R'){
            var isi=[];
            isi['ident']=ncurr.wpt_name;
            isi['name']=ncurr.desc_name;
            isi['type']=ncurr.definition;
            isi['oldvalue']=ncurr[a];
            isi['newvalue']= ntemp[a];
            isi['status']= ntemp.status;

            if (fld43.includes(a)==true){
                ad219.push(isi);
                no++;
                hasil = '<tr><td>'+no+'</td><td>ENR 4.3</td><td>' + ncurr.wpt_name + '</td><td>' +  ncurr.definition + '</td><td>' + ncurr[a] + '</td><td>' + ntemp[a] + '</td><td>' + ntemp.status + '</td></tr>'
                $("#detaillist").append(hasil);
            }
    
            if (fldenr.includes(a)==true){
                // console.log(temp.ats[0]);
                temp.atstemp.forEach(e=>{
                    no++
                    var ix = temp.ats.findIndex(x=>x.ats_id===e.ats_id)

                    var c=temp.ats[ix];
                    // console.log(e,c,ix)

                    tampillistats(e,c,no);
                })
              
            }

        
        
        }
    })

    //    console.log(ad219)

   
}
function navaid(temp){
    // console.log(temp)
    var no=0;
    var fld=[
		'ctry','type', 'nav_ident','nav_name',  'col_dme', 'freq', 'range','altitude', 'channel', 'dme_range', 'dme_elev', 'opr_hrs', 'remarks','latitude','longitude','dme_latitude','dme_longitude'
    ];
    
    var fldenr=[
		'nav_ident','latitude','longitude'
    ];
    var fldgen25=[
		'ctry','type', 'nav_ident','nav_name'
    ];
    var fldenr41=[
		'ctry','type', 'nav_ident','nav_name',  'col_dme', 'freq', 'channel', 'opr_hrs', 'remarks','latitude','longitude',
    ];
    var ncurr=[];
    var ntemp=temp.navtemp[0];
    $('#arpttitle').html(ntemp.nav_ident + ' ' + ntemp.definition + ' - ' + ntemp.nav_name )
    var crdtemp=SetCoordinatebyGeom(ntemp.geom)
    var frqt=FreqFormat(ntemp.freq,ntemp.type,'DATA');
    ntemp['latitude']=crdtemp.Database[1]
    ntemp['longitude']=crdtemp.Database[0]
    if (ntemp.dmegeom){
        // c=ntemp.dmegeom.replace('POINT(','').replace(')','').split(' ')
        // console.log(c)
        crdtemp=SetCoordinatebyGeom(ntemp.dmegeom)
        ntemp['dme_latitude']=crdtemp.Database[1]
        ntemp['dme_longitude']=crdtemp.Database[1]
    }else{
        ntemp['dme_latitude']='';
        ntemp['dme_longitude']='';

    }
    if (temp.navcurr){
        ncurr=temp.navcurr[0];
        // c=ncurr.geom.replace('POINT(','').replace(')','').split(' ')
        // console.log(c)
        crdcurr=SetCoordinatebyGeom(ncurr.geom)
        ncurr['latitude']=crdcurr.Database[1]
        ncurr['longitude']=crdcurr.Database[0]
        var frqc=FreqFormat(ncurr.freq,ncurr.type,'DATA');
        if (ntemp.dmegeom){
            // c=ncurr.dmegeom.replace('POINT(','').replace(')','').split(' ')
            // console.log(c)
            crdcurr=SetCoordinatebyGeom(ntemp.dmegeom)
            // crdcurr=SetCoordinatebyGeom(ncurr.dmegeom)
            ncurr['dme_latitude']=crdcurr.Database[1]
            ncurr['dme_longitude']=crdcurr.Database[1]
        }else{
            ncurr['dme_latitude']='';
            ncurr['dme_longitude']='';

        }
    }else{
        ncurr['latitude']='';
        ncurr['longitude']='';
        ncurr['dme_latitude']='';
        ncurr['dme_longitude']='';
    }
    var ad219=[];enr41=[];gen25=[];
    enr=[];enrtemp=[];
    fld.forEach( a =>
    {

        var sts='U';
        var ftem=ntemp[a];fcurr=ncurr[a];
        if (ntemp[a]==null || ntemp[a]=='NIL'){
            ftem='';
        }
        if (ncurr[a]==null || ncurr[a]=='NIL'){
            fcurr='';
        }
        if (typeof ncurr=='undefined'){
            sts='R';
        }else{
            if (ftem !== fcurr){
                sts='R';
            }
        }
        if (sts=='R'){
            var isi=[];
            isi['ident']=ncurr.nav_ident;
            isi['name']=ncurr.nav_name;
            isi['type']=ncurr.definition;
            isi['oldvalue']=ncurr[a];
            isi['newvalue']= ntemp[a];
            isi['status']= ntemp.status_vld;

            if (fldenr41.includes(a)==true){
                ad219.push(isi);
                // console.log('ada ENR41')
                no++;
                hasil = '<tr><td>'+no+'</td><td>AD 2.19</td><td>' + ncurr.nav_ident + '/'+ ncurr.nav_name + '</td><td>' +  ntemp.definition + '</td><td>' + ncurr[a] + '</td><td>' + ntemp[a] + '</td><td>' + ntemp.status_vld + '</td></tr>'
                $("#detaillist").append(hasil);
                no++;
                hasil = '<tr><td>'+no+'</td><td>ENR 4.1</td><td>' + ncurr.nav_name + '</td><td>' +  ncurr.definition + '</td><td>' + ncurr[a] + '</td><td>' + ntemp[a] + '</td><td>' + ntemp.status_vld + '</td></tr>'
                $("#detaillist").append(hasil);
            }
           
            if (fldgen25.includes(a)==true){
                // console.log('ada GEN25')
                gen25.push(isi);
                no++;
                hasil = '<tr><td>'+no+'</td><td>GEN 2.5</td><td>' + ncurr.nav_ident + '/'+ ncurr.nav_name + '</td><td>' +  ntemp.definition + '</td><td>' + ncurr[a] + '</td><td>' + ntemp[a] + '</td><td>' + ntemp.status_vld + '</td></tr>'
                $("#detaillist").append(hasil);
            }
            if (fldenr.includes(a)==true){
                temp.atstemp.forEach(e=>{
                    no++;
                    var ix = temp.ats.findIndex(x=>x.ats_id===e.ats_id)
                    var c=temp.ats[ix];
                    tampillistats(e,c,no);
                })
            }
            
        
        
        
        }
    })

}

function airport(){
// console.log(ret)
    $('#arpttitle').html(ret.airport[0].icao + ' - ' + ret.airport[0].city_name + '/' + ret.airport[0].arpt_name)
    ret.content.forEach(t=>{
        // console.log(t);
        var idx= ret.content.findIndex(x => x.category_id===t.category_id);
        var ix= ret.content_curr.findIndex(x => x.category_id===t.category_id);
        var oldval='';sts='';
        isi=[];
        if (idx==-1 || ix==-1){
            // console.log(t.category_id)
            sts='N';
            oldval='NIL';
        }else{
            sts='R';
            // console.log(ret.content_curr[ix])
            oldval=ret.content_curr[ix].content;
            if (oldval==null || oldval==''){
                oldval='NIL';
            }
        }
        if (t.content==null || t.content==''){
            t.content='NIL';
        }
            var pch=t.category.split(' '); var pcha=pch[1].split('.')
            // console.log(pch)
        if (oldval !== t.content ){
            isi=[{category:t.category},{id:''},{field:t.category_id},{item:t.item},{curvalue:oldval},{reqvalue:t.content},{status:sts},{seq:Number(pcha[1])},{arpt_ident:t.arpt_ident},{table:'eaip_chart_content_temp'}]
            viewcontent.push(isi)

        }
    })
    console.log(viewcontent);

   
    ret.apron.forEach(t=>{
        var rwyfield=['name', 'dimension', 'surface','strength','type','group',];
        var ix= ret.apron_curr.findIndex(x => x.id===t.id);
        var ncurr= []; ident='';
        var subid='AD 2.8 APRONS, TAXIWAYS AND CHECK LOCATIONS/POSITIONS DATA';
        if (ix !== -1){
            ncurr= ret.apron_curr[ix]
        }
        var pcha='';
        rwyfield.forEach( a =>
            {
                // console.log(t)
                ident=t.name;
                if (t.type=='B'){
                    pcha=8.2;
                }else{
                    // ident='APRON';
                    pcha=8.1;
                }
                var sts='U';fcurr='';
                var ftem=t[a];
                if (ncurr){
                    fcurr=ncurr[a];
                }

                if (t[a]==null || t[a]==''){
                    ftem='NIL';
                }
                if (fcurr==null ||fcurr=='' ){
                    fcurr='NIL';
                }
                if (ftem !== fcurr){
                    sts='R';
                }
                // }
                if (sts=='R'){
                    isi=[{category:subid},{id:ident},{field:t.id},{item:a},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:pcha},{arpt_ident:t.arpt_ident},{table:'eaip_apron_twy_temp'}]
                    viewcontent.push(isi)
                
                }
            })
    })

    ret.parkingstand.forEach(t=>{
        var rwyfield=['no_gate', 'gate_lat', 'gate_lon','aircraft_type','ramp_name','elevation'];
        var ix= ret.parkingstand_curr.findIndex(x => x.id===t.id);
        var ncurr= [];
        var subid='AD 2.8 APRONS, TAXIWAYS AND CHECK LOCATIONS/POSITIONS DATA';
        var ident='PS ' + t.no_gate;
        if (ix !== -1){
            ncurr= ret.parkingstand_curr[ix]
        }
        rwyfield.forEach( a =>
            {
                var sts='U';fcurr='';
                var ftem=t[a];
                if (ncurr){
                    fcurr=ncurr[a];
                }
                if (t[a]==null || t[a]==''){
                    ftem='NIL';
                }
                if (fcurr==null || fcurr=='' ){
                    fcurr='NIL';
                }
                if (ftem !== fcurr){
                    sts='R';
                }
                if (sts=='R'){
                    isi=[{category:subid},{id:ident},{field:t.id},{item:a},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:8.3},{arpt_ident:t.arpt_ident_gate},{table:'eaip_arpt_gate_temp'}]
                    viewcontent.push(isi)
                  
                }
            })
    })

    ret.pushback.forEach(t=>{
        var rwyfield=['no_gate', 'ramp_name', 'procedure', 'radio', 'nbr', 'remarks'];
        var ix= ret.pushback_curr.findIndex(x => x.id===t.id);
        var ncurr= [];
        var subid='PUSHBACK PROCEDURES';
        var ident=t.no_gate;
        if (ix !== -1){
            ncurr= ret.pushback_curr[ix]
        }

        rwyfield.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }
            // console.log(a,ftem,fcurr)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr=='' ){
                fcurr='NIL';
            }
            // if (ncurr.length>0){
            //     sts='R';
            // }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            // }
            if (sts=='R'){
                isi=[{category:subid},{id:ident},{field:t.id},{item:a},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:20},{arpt_ident:t.arpt_ident_pushback},{table:'eaip_pushback_temp'}]
                viewcontent.push(isi)
              
            }
        })
    })

    ret.obstacles.forEach(t=>{
        // console.log(t)
        var rwyfield=['obs_type', 'lighted', 'obs_group', 'elev_ft','hgt','position','lat','lon','remarks','notes'];
        var ix= ret.obstacles_curr.findIndex(x => x.id===t.id);
        var ncurr= [];
        var subid='AD 2.10 AERODROME OBSTACLES';
        var ident=t.obs_type;
        switch (t.obs_type) {
            case 'H':
                ident='Hill'
                break;
            case 'T':
                ident='Tree'
                break;
            case 'O':
                ident='Other'
                break;
            case 'S':
                ident='SpotHeight'
                break;
            case 'B':
                ident='Building'
                break;
            case 'A':
                ident='Antenna'
                break;
            default:
            ident='Unknown'
                break;
        }
        
        var c=t.obs_geom.replace('POINT(','').replace(')','').split(' ')
        var crdtemp=SetCoordinatebyDecimal(c[0],c[1])
        t['lat']=crdtemp.Database[1]
        t['lon']=crdtemp.Database[0]
        
        if (ix !== -1){
            ncurr= ret.obstacles_curr[ix];
            c=ncurr.obs_geom.replace('POINT(','').replace(')','').split(' ')
            crdtemp=SetCoordinatebyDecimal(c[0],c[1])
            ncurr['lat']=crdtemp.Database[1]
            ncurr['lon']=crdtemp.Database[0]
            // console.log(ncurr )
        }
        rwyfield.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }
            // var ftem=t[a];fcurr=ncurr[a];
            // console.log(a,ftem,fcurr,ncurr.length )
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr=='' ){
                fcurr='NIL';
            }
            // if (ncurr.length>0){
            //     sts='R';
            // }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            // }
            if (sts=='R'){
                isi=[{category:subid},{id:ident},{field:t.id},{item:a},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:10},{arpt_ident:t.arpt_ident},{table:'arpt_obstacle_temp'}]
                viewcontent.push(isi)
            
            }
        })

    })

    ret.rwy.forEach(t=>{
        var rwyfield=['rwy_ident', 'length', 'width', 'pcn', 'surface','strip_l', 'strip_w','thr_low', 'thr_high'];
        var ix= ret.rwy_curr.findIndex(x => x.id===t.id);
        var ncurr= [];subid='AD 2.12 RUNWAY PHYSICAL CHARACTERISTICS';
        if (ix !== -1){
            ncurr= ret.rwy_curr[ix]
        }

        rwyfield.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }
            // console.log(a,ftem,fcurr)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr=='' ){
                fcurr='NIL';
            }
            // if (ncurr.length>0){
            //     sts='R';
            // }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            // }
            if (sts=='R'){
                isi=[{category:subid},{id:'RWY ' + t.rwy_ident},{field:t.id},{item:a},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:12},{arpt_ident:t.arpt_ident},{table:'arpt_rwy_temp'}]
                viewcontent.push(isi)
                

            }
        })

    })

    ret.rwythr.forEach(t=>{
        var thrfieldthr=['rwy_ident', 'lat','lon', 'mag_brg','true_brg','thr_elev', 'tdz_elev','geoid','swy_length','swy_width','cwy_length','cwy_width','resa_l', 'resa_w',    'slope',  'slope1','disp_thr_length', 'disp_thr_elev', 'disp_lat','disp_lon','remarks'];
        var thrfield=['RWY ', 'LAT','LON', 'MAG BRG','TRUE BRG','THR ELEV', 'TDZ ELEV','GEOID','SWY LENGTH','SWY WIDTH','CWY LENGTH', 'CWY WIDTH','RESA LENGTH', 'RESA WIDTH',   'SLOPE LONGITUDE','SLOPE TRANS','DISP LENGTH',  'DISP ELEV', 'DISP LAT','DISP LON','REMARKS'];
        var ix= ret.rwythr_curr.findIndex(x => x.id===t.id);
        var ncurr= [];
        var subid='AD 2.12 RUNWAY PHYSICAL CHARACTERISTICS';
        var c=t.thr_geom.replace('POINT(','').replace(')','').split(' ')
        var crdtemp=SetCoordinatebyDecimal(c[0],c[1])
        t['lat']=crdtemp.Database[1]
        t['lon']=crdtemp.Database[0]
        if (t.disp_thr_geom !== null){
            c=t.disp_thr_geom.replace('POINT(','').replace(')','').split(' ')
            crdtemp=SetCoordinatebyDecimal(c[0],c[1])
            t['disp_lat']=crdtemp.Database[1]
            t['disp_lon']=crdtemp.Database[0]

        }else{
            t['disp_lat']='';
            t['disp_lon']='';
        }

        if (ix !== -1){
            // console.log(t)
            ncurr= ret.rwythr_curr[ix]
            c=ncurr.thr_geom.replace('POINT(','').replace(')','').split(' ')
            crdtemp=SetCoordinatebyDecimal(c[0],c[1])
            ncurr['lat']=crdtemp.Database[1]
            ncurr['lon']=crdtemp.Database[0]
            if (ncurr.disp_thr_geom !== null){
                c=ncurr.disp_thr_geom.replace('POINT(','').replace(')','').split(' ')
                crdtemp=SetCoordinatebyDecimal(c[0],c[1])
                ncurr['disp_lat']=crdtemp.Database[1]
                ncurr['disp_lon']=crdtemp.Database[0]
            }else{
                ncurr['disp_lat']='';
                ncurr['disp_lon']='';
            }
           
        }
        var nn=0;
        thrfieldthr.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }

            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }

            if (fcurr==null || fcurr=='' ){
                fcurr='NIL';
            }

            if (ftem !== fcurr){
                sts='R';
            }
            if (sts=='R'){
                isi=[{category:subid},{id:'RWY ' + t.rwy_ident},{field:t.id},{item:thrfield[nn]},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:12.1},{arpt_ident:t.arpt_ident},{table:'arpt_rwy_physical_temp'}]
                viewcontent.push(isi)
            
            }
            nn++
        })

    })
    ret.rwythr.forEach(t=>{
        var thrfieldthr=['rwy_ident','tora', 'toda', 'asda', 'lda'];
        var thrfield=['rwy_ident','TORA', 'TODA', 'ASDA', 'LDA'];
        var ix= ret.rwythr_curr.findIndex(x => x.id===t.id);
        var ncurr= [];
        var subid='AD 2.13 DECLARED DISTANCES';
        if (ix !== -1){
            ncurr= ret.rwythr_curr[ix]
        }
        var nt=0
        thrfieldthr.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }

            // console.log(a,ftem,fcurr)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr=='' ){
                fcurr='NIL';
            }

            if (ftem !== fcurr){
                sts='R';
            }

            if (sts=='R'){
                isi=[{category:subid},{id:'RWY ' + t.rwy_ident},{field:t.id},{item:thrfield[nt]},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:13},{arpt_ident:t.arpt_ident},{table:'arpt_rwy_physical_temp'}]
                viewcontent.push(isi)
            }
            nt++;
        })
    })

    ret.rwylgt.forEach(t=>{
        var thrfieldthr=['apch_lgt_type_len', 'thr_lgt_clr_wbar', 'vasis_meht_papi','tdz_lgt_len', 'rwy_ctrln_lgt_length_spc_clr', 'rwy_edge_lgt_len_spc_clr','rwy_end_lgt_clr_wbar', 'swy_lgt_len_clr', 'remark'];
        var fieldtrue=['APCH LGT type LEN INTST','THR LGT colour WBAR','VASIS (MEHT) PAPI','TDZ, LGT LEN','RWY Centre Line LGT Length, spacing, colour, INTST','RWY edge LGT LEN, spacing Colour INTST','RWY End LGT colour WBAR','SWY LGT LEN (M) colour','Remarks']
        var ix= ret.rwylgt_curr.findIndex(x => x.id===t.id);
        var ncurr= [];
        var subid='AD 2.14 APPROACH AND RUNWAY LIGHTING';
        if (ix !== -1){
            ncurr= ret.rwylgt_curr[ix]
        }
        
        var iix=0;
        thrfieldthr.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }
            // var ftem=t[a];fcurr=ncurr[a];
            // console.log(a,ftem,fcurr)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr=='' ){
                fcurr='NIL';
            }
            // if (ncurr.length>0){
            //     sts='R';
            // }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            // }
            if (sts=='R'){
                isi=[{category:subid},{id:'RWY ' + t.rwy_ident},{field:t.id},{item:fieldtrue[iix]},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:14},{arpt_ident:t.arpt_ident},{table:'eaip_rwy_lgt_temp'}]
                viewcontent.push(isi)
                iix++
            }
        })
    })

    ret.comm.forEach(t=>{
        var rwyfield=['types', 'call_sign', 'freq','opr_hrs','remarks','logon','sector','satcom'];
        var ix= ret.comm_curr.findIndex(x => x.id===t.id);
        var ncurr= [];
        var subid='AD 2.18 ATS COMMUNICATION FACILITIES';
        if (ix !== -1){
            ncurr= ret.comm_curr[ix]
            // console.log(ncurr)
        }
        
        rwyfield.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (a=='freq'){
                ftem=Airspacefreq(t[a],t['unit']);
            }
            if (ncurr){
                fcurr=ncurr[a];
                if (a=='freq'){
                    fcurr=Airspacefreq(fcurr,ncurr['unit'])
                }
            }

            
            // console.log(a,ftem,fcurr)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr=='' ){
                fcurr='NIL';
            }
            // if (ncurr.length>0){
            //     sts='R';
            // }else{
                if (ftem !== fcurr){
                    sts='R';
                }
            // }
            if (sts=='R'){
                isi=[{category:subid},{id: t.types},{field:t.id},{item:a},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:18},{arpt_ident:t.arpt_ident},{table:'freq_used_temp'}]
                viewcontent.push(isi)

            }
        })
    })
    // console.log(ret)
    ret.nav.forEach(t=>{
        console.log(t)
        var fldenr41=['ctry','type', 'nav_ident','nav_name',  'col_dme', 'freq', 'channel', 'opr_hrs', 'remarks','latitude','longitude'];
        var fldenr411=['CTRY','TYPE', 'IDENT','NAME',  'COL DME', 'FREQ', 'CHANNEL', 'OPR HRS', 'REMARKS','LAT','LON'];
        var ix= ret.nav_curr.findIndex(x => x.nav_id===t.nav_id);
        var subid='AD 2.19 RADIO NAVIGATION AND LANDING AIDS';
        var c=t.geom.replace('POINT(','').replace(')','').split(' ')
        var crdtemp=SetCoordinatebyDecimal(c[0],c[1])
        t['latitude']=crdtemp.Database[1]
        t['longitude']=crdtemp.Database[0]
        var ncurr=[];
        if (ix !== -1){
            ncurr= ret.nav_curr[ix]
            c=ncurr.geom.replace('POINT(','').replace(')','').split(' ')
            // console.log(c)
            crdtemp=SetCoordinatebyDecimal(c[0],c[1])
            // crdtemp=SetCoordinatebyGeom(ncurr.geom)
            ncurr['latitude']=crdtemp.Database[1]
            ncurr['longitude']=crdtemp.Database[0]
            // console.log(t,ncurr)
        }
        var ident=t.nav_ident + ' '+ t.definition;
      
        // navaid(t,ret.nav_curr[ix])
        no++;n=0;
        fldenr41.forEach( a =>
        {
            // console.log(a)
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }
          
            // console.log(a,ftem,fcurr,ident)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr==''){
                fcurr='NIL';
            }

            if (ftem !== fcurr){
                sts='R';
            }
            // var isi=[];
            if (sts=='R'){
                isi=[{category:subid},{id: ident},{field:t.id},{item:fldenr411[n]},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:19},{arpt_ident:t.arpt_ident},{table:'navaid_temp'}]
                viewcontent.push(isi)
            }
            n++
        })

    })
    // console.log(ret)
    ret.ils.forEach(t=>{
        var fldenr41=['ils_ident', 'ils_name', 'ils_cat', 'freq','ch','gs_freq','gs_hgt','gs_angle','gs_elev','lat','lon','gs_lat','gs_lon','dme_avail', 'opr_hrs', 'remarks'];
        var fldenr411=['IDENT', 'NAME', 'CATEGORY', 'FREQ','CHANNEL', 'GS FREQ','GS HEIGHT','GS ANGLE','GS ELEV','LAT','LON','GS LAT','GS LON','COL DME', 'OPR HOURS', 'REMARKS'];
        var ix= ret.ils_curr.findIndex(x => x.ils_id===t.ils_id);
        var subid='AD 2.19 RADIO NAVIGATION AND LANDING AIDS';
        var c=t.geom.replace('POINT(','').replace(')','').split(' ')
        var crdtemp=SetCoordinatebyDecimal(c[0],c[1])
        t['lat']=crdtemp.Database[1]
        t['lon']=crdtemp.Database[0]
        var c=t.gsgeom.replace('POINT(','').replace(')','').split(' ')
        var crdtemp=SetCoordinatebyDecimal(c[0],c[1])
        t['gs_lat']=crdtemp.Database[1]
        t['gs_lon']=crdtemp.Database[0]
        var ncurr=[];
        if (ix !== -1){
            ncurr= ret.ils_curr[ix]
            c=ncurr.geom.replace('POINT(','').replace(')','').split(' ')
            // console.log(c)
            crdtemp=SetCoordinatebyDecimal(c[0],c[1])
            // crdtemp=SetCoordinatebyGeom(ncurr.geom)
            ncurr['lat']=crdtemp.Database[1]
            ncurr['lon']=crdtemp.Database[0]
            c=ncurr.gsgeom.replace('POINT(','').replace(')','').split(' ')
            // console.log(c)
            crdtemp=SetCoordinatebyDecimal(c[0],c[1])
            // crdtemp=SetCoordinatebyGeom(ncurr.geom)
            ncurr['gs_lat']=crdtemp.Database[1]
            ncurr['gs_lon']=crdtemp.Database[0]
        }
        // console.log(t,ncurr)
        var ident= t.ils_ident + ' ILS/LLZ RWY '+ t.rwy_ident
        // navaid(t,ret.nav_curr[ix])
        no++;kk=0;
        fldenr41.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }
        
            // console.log(a,ftem,fcurr)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr==''){
                fcurr='NIL';
            }

            if (ftem !== fcurr){
                sts='R';
            }
            
            if (sts=='R'){
                isi=[{category:subid},{id:ident},{field:t.id},{item:fldenr411[kk]},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:19},{arpt_ident:t.arpt_ident},{table:'arpt_ils_temp'}]
                viewcontent.push(isi)
            }
            kk++;
        })

    })
    ret.marker.forEach(t=>{
        // console.log(t)
        var fldenr41=['mrkr_type','freq','elev','opr_hrs', 'remarks','lat','lon'];
        var fldenr411=['IDENT', 'FREQ', 'ELEV', 'OPR HOURS', 'REMARKS','LAT','LON'];

        var ix= ret.marker_curr.findIndex(x => x.mrkr_id===t.mrkr_id);
        var subid='AD 2.19 RADIO NAVIGATION AND LANDING AIDS';
        var c=t.geom.replace('POINT(','').replace(')','').split(' ')
        var crdtemp=SetCoordinatebyDecimal(c[0],c[1])
        t['lat']=crdtemp.Database[1]
        t['lon']=crdtemp.Database[0]
        var ncurr=[];
        if (ix !== -1){
            ncurr= ret.marker_curr[ix]
            c=ncurr.geom.replace('POINT(','').replace(')','').split(' ')
            // console.log(c)
            crdtemp=SetCoordinatebyDecimal(c[0],c[1])
            // crdtemp=SetCoordinatebyGeom(ncurr.geom)
            ncurr['lat']=crdtemp.Database[1]
            ncurr['lon']=crdtemp.Database[0]
        }
        // console.log(t,ncurr)
        var ident= t.mrkr_type + ' RWY '+ t.rwy_ident
        // navaid(t,ret.nav_curr[ix])
        no++;m=0;
        fldenr41.forEach( a =>
        {
            var sts='U';fcurr='';
            var ftem=t[a];
            if (ncurr){
                fcurr=ncurr[a];
            }
          
            // console.log(a,ftem,fcurr)
            if (t[a]==null || t[a]==''){
                ftem='NIL';
            }
            if (fcurr==null || fcurr==''){
                fcurr='NIL';
            }

            if (ftem !== fcurr){
                sts='R';
            }
            
            if (sts=='R'){
                isi=[{category:subid},{id:ident},{field:t.id},{item:fldenr411[m]},{curvalue:fcurr},{reqvalue:ftem},{status:t.status },{seq:19},{arpt_ident:t.arpt_ident},{table:'arpt_marker_temp'}]
                viewcontent.push(isi)
            }
            m++
        })

    })
    var no=0;subid='';
    viewcontent.sort((a,b) => (a[7].seq > b[7].seq) ? 1 : ((b[7].seq > a[7].seq) ? -1 : 0));
    console.log(viewcontent,'viewcontent');
    var idx=-1;
    viewcontent.forEach(v=>{
        idx++
        if (subid ==v[0].category ){
            subid='';
        }else{
            subid=v[0].category;
        }
        var sts='Request';
        if (v[6].status=='N'){
            sts='New Data';
        }
        no++;
        var  hasil= '<tr>'+
        '</td>'+
        '<td>'+no+'</td><td>' + subid + '</td><td>' + v[1].id + '</td><td>' + v[3].item + '</td><td>' + v[4].curvalue + '</td><td>' + v[5].reqvalue + '</td><td>' + sts + '</td></tr>'
        $("#detaillist").append(hasil);
        subid=v[0].category;
    })
}

function getcord(point){
    var c=point.replace('POINT(','').replace(')','').split(' ')
    // console.log(c)
    var crdtemp=SetCoordinatebyDecimal(c[0],c[1])
    ntemp['latitude']=crdtemp.Database[1]
    ntemp['longitude']=crdtemp.Database[0]
    if (ntemp.dmegeom){
        c=ntemp.dmegeom.replace('POINT(','').replace(')','').split(' ')
        // console.log(c)
        crdtemp=SetCoordinatebyDecimal(c[0],c[1])
        ntemp['dme_latitude']=crdtemp.Database[1]
        ntemp['dme_longitude']=crdtemp.Database[1]
    }else{
        ntemp['dme_latitude']='';
        ntemp['dme_longitude']='';

    }
    if (curr){
        ncurr=curr;
        c=ncurr.geom.replace('POINT(','').replace(')','').split(' ')
        // console.log(c)
        crdcurr=SetCoordinatebyDecimal(c[0],c[1])
        ncurr['latitude']=crdcurr.Database[1]
        ncurr['longitude']=crdcurr.Database[0]
        var frqc=FreqFormat(ncurr.freq,ncurr.type,'DATA');
        if (ntemp.dmegeom){
            c=ncurr.dmegeom.replace('POINT(','').replace(')','').split(' ')
            // console.log(c)
            crdcurr=SetCoordinatebyDecimal(c[0],c[1])
            // crdcurr=SetCoordinatebyGeom(ncurr.dmegeom)
            ncurr['dme_latitude']=crdcurr.Database[1]
            ncurr['dme_longitude']=crdcurr.Database[1]
        }else{
            ncurr['dme_latitude']='';
            ncurr['dme_longitude']='';

        }
    }else{
        ncurr['latitude']='';
        ncurr['longitude']='';
        ncurr['dme_latitude']='';
        ncurr['dme_longitude']='';
    }
}
function backtolist(){

    history.back();

}
</script>
@endsection