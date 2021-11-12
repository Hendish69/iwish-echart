


function listSection() {
$(document).on('change', '#section', function (e) {
  e.preventDefault();
    let x = document.getElementById("section").value;
   Visibility("List-Data",'True');
    let vol = document.getElementById("Vol-Data");
    console.log('section id = ' + x);
     switch (x) {
         case '15':
             ArrayList(x)
            vol.style.visibility = 'visible';
             break;

        default:
            vol.style.visibility = 'hidden';
            break;
    };


  timeout: 10000;
    $.ajax({
        url: 'app/eaiplist',
        data: {id : x},
        type: "json",
        method: "get",
    success: function(result){
        $('#subsection').empty();
        $('#subsection').append("<option selected>Select Subsection</option>");
        $.each(result, function() {
            $.each(this, function(k, v) {
            $('#subsection').append("<option value="+v.id+">"+v.sub_id + ' ' + v.definition+"</option>")
                console.log(v.definition)
               });
            });
          }
     });
});
}

function Visibility(params,hide) {
    //  let vol = document.getElementById(params);
   // console.log(document.getElementById(params).style.visibility);
    // console.log(vol);
     if (hide == 'True') {
     // let xx = document.getElementById("List-Data");
        //  if (document.getElementById(params).style.visibility != 'hidden') {
        document.getElementById(params).style.visibility = 'hidden'
    // };
    // $(params).css('visibility', function (i, v) {
    //     console.log('Visibility ' + v);
    //     if (hide == 'True') {
    //         // if (v == 'visible') {
    //          return v == 'hidden';
    //     // }
     } else {
        //  if (document.getElementById(params).style.visibility != 'visible') {
             document.getElementById(params).style.visibility = 'visible'
        //  };
    //         // if (v == 'hidden') {
    //             // return v == 'hidden' ? 'visible' : 'hidden';
    //             return v == 'visible';
    //     // }
    //     }


    };
}




function ViewAD() {

    $(document).on('change', '#sub-ad', function (e) {
        e.preventDefault();
        let jdl1 = document.getElementById("icao").value;
        console.log(jdl1);

        let sub = document.getElementById("sub-ad").value;
        let vol = document.getElementById("AirportModal");
        vol.style.visibility = 'hidden';
        let subsplt = sub.split(' ');
        let subad = subsplt[0] + ' ' + subsplt[1]
        console.log('sub-ad ' + sub);
        switch (subad) {
            case 'AD 2.2':
                console.log('AD 2.2 sub-ad ' + sub);
                document.getElementById("modal22MdTitle").innerText = jdl1 + " " + sub
                jdl1 = '';
                $('#AD22Modal').modal('show');
                break;
            case 'AD 2.3':
                console.log('AD 2.3 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                jdl1 = '';
                $('#AD23Modal').modal('show')
                break;
            case 'AD 2.4':
                console.log('AD 2.4 sub-ad ' + sub);
                document.getElementById("modal24MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD24Modal').modal('show');
                break;
            case 'AD 2.5':
                console.log('AD 2.5 sub-ad ' + sub);
                document.getElementById("modal25MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD25Modal').modal('show');
                break;
            case 'AD 2.6':
                console.log('AD 2.6 sub-ad ' + sub);
                document.getElementById("modal26MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD26Modal').modal('show');
                break;
            case 'AD 2.7':
                console.log('AD 2.7 sub-ad ' + sub);
                document.getElementById("modal27MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD27Modal').modal('show');
                break;
            case 'AD 2.8':
                console.log('AD 2.8 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.9':
                console.log('AD 2.9 sub-ad ' + sub);
                document.getElementById("modal29MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD29Modal').modal('show');
                break;
            case 'AD 2.10':
                console.log('AD 2.10 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.11':
                console.log('AD 2.11 sub-ad ' + sub);
                document.getElementById("modal211MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD211Modal').modal('show');
                break;
            case 'AD 2.12':
                console.log('AD 2.12 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.13':
                console.log('AD 2.13 sub-ad ' + sub);
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.14':
                console.log('AD 2.14 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.15':
                console.log('AD 2.15 sub-ad ' + sub);
                document.getElementById("modal215MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD215Modal').modal('show');
                break;
            case 'AD 2.16':
                console.log('AD 2.16 sub-ad ' + sub);
                document.getElementById("modal216MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD216Modal').modal('show');
                break;
            case 'AD 2.17':
                console.log('AD 2.17 sub-ad ' + sub);
                document.getElementById("modal217MdTitle").innerHTML = jdl1 + " " + sub
                jdl1 = '';
                $('#AD217Modal').modal('show');
                break;
            case 'AD 2.18':
                console.log('AD 2.18 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.19':
                console.log('AD 2.19 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.20':
                console.log('AD 2.20 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.21':
                console.log('AD 2.21 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.22':
                console.log('AD 2.22 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.23':
                console.log('AD 2.23 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;
            case 'AD 2.24':
                console.log('AD 2.24 sub-ad ' + sub);
                document.getElementById("modal23MdTitle").innerText = jdl1 + " " + sub
                $('#AD23Modal').modal('show');
                break;

            default:
                break;
        }

    });
}


function AspChecked( cid )
{
    $( '#isi-table tr' ).empty();
    // console.log(cid)
    $( '.checkbox:checkbox:checked' ).each( function ( i )
    {
        console.log('AspChecked', $( this ).val() );
        var dd = $( this ).val().split( '$' );
        switch ( dd[ 1 ] ) {
            case "airspace":
                AirspaceList(dd[0]);
                break;
            case "arpt":
                AirportList(dd[0]);
                break;
            case "suas":
                SuasList(dd[0]);
                break;
            case "waypoint":
                SuasList(dd[0]);
                break;
            case "navaid":
                NavaidList(dd[0]);
                break;
        }
      
        console.log( $( this ).val() );
    });



}

function AtsList(id) {
    // console.log("MASUUUUUUK airport list")
   // let retval;
    let sub = id;
    $("#section").val('Select Section');
    // console.log('subsection' + sub);
    // timeout: 10000;
    $.ajax({
        url: 'api/ats/list/' + id,
        data: {ctry : 'ID'},
        type: "json",
        method: "get",

        success: function (result) {
            $('#row-judul').empty();
            $('#row-judul').append('<tr><th scope="col">#</th><th scope="col">No</th><th scope="col">Ident</th><th scope="col">Type</th></tr>');
            // let x = document.getElementById("List-Data");
            // x.style.visibility = 'visible';
             Visibility("List-Data", 'False');
            $.each(result, function () {
                $('#isi-table').html(result);
                $.each(this, function (k, v) {
                 let isi = [ v.ats_ident,  v.definition ];
                 $('#isi-table').append(tdtemplate(v.ctry+'$ats',(k + 1), isi, '2','atslist'));
                    // $('#isi-table').append("<tr><td>" + (k + 1) + "</td><td>" + v.ats_ident + "</td><td>" + v.definition + "</td><td><a href='' class='badge badge-primary'>New</a><a href='' class='badge badge-success'>edit</a><a href='' class='badge badge-danger'>delete</a></td>");
                    // // console.log(v.paginate);

                });
            });
            // $('#per-page').append(result=>link());
        }

    });
   // return retval;
}


function AirportList( id )
{
    var vol = 2;
   switch (id) {
        case 'VOL II':
           vol = 2;
            break;
        case 'VOL III':
            vol = 3;
            break;
        case 'VOL IV':
            vol = 4;
            break;
        case 'VOL V':
            vol = 5;
            break;

    }
    $.ajax({
        url: 'api/airport/list',
        data: {ctry:'ID',vol:vol,sort:'arpt_name:asc'},
        type: "json",
        method: "get",

        success: function (result) {
            $('#row-judul').empty();
            $('#row-judul').append('<tr><th scope="col"><button class="btn btn-sm btn-dim btn-info" onclick="NewData()"><i class="icon ni ni-plus-circle-fill" align="right" aria-hidden="true"></i> Add</button></th><th scope="col">No</th><th scope="col">ICAO</th><th scope="col">Airport Name</th><th scope="col">City</th></tr>');
            // let x = document.getElementById("List-Data");
            // x.style.visibility = 'visible';
             Visibility("List-Data", 'False');
            $.each(result, function () {
                $('#isi-table').html(result);
                $.each(this, function (k, v) {
                 let isi = [ v.icao,  v.arpt_name,  v.city_name ];
                 $('#isi-table').append(tdtemplate(v.arpt_ident+'$airport',(k + 1), isi, '3','arpt'));
                    // $('#isi-table').append("<tr><td>" + (k + 1) + "</td><td>" + v.icao + "</td><td>" + v.arpt_name + "</td>" +
                    //     "<td>" + v.city_name + "</td><td><button type='button' class='btn btn-primary row-cols-1 open_modal' value='"+ v.arpt_ident + "' onclick='ArptModal()'>edit</button><button type='button' class='btn btn-danger row-cols-1' data-toggle='modal' data-target='#exampleModal'>Delete</button></td></tr>");
                    // // console.log(v.paginate);

                });
            });
            // $('#per-page').append(result=>link());
        }

    });
   // return retval;
}

function NavaidList(typid) {
    let sub ='4';
    switch (typid) {
        case 'LOCATOR':
            sub = '10'
            break;
        case 'NDB':
            sub = '5'
            break;
        case 'RADAR HEAD':
            sub = '20'
            break;
        case 'ILS':
            sub = '11'
            break;
        case 'TACAN':
            sub = '3'
            break;
        case 'VOR':
            sub = '1'
            break;
        case 'VOR/DME':
            sub = '4'
            break;

    };
    typid = '';
       // document.getElementById("subsection").value;
    console.log('NavaidList ' + sub);
    timeout: 10000;
    $.ajax({
        url: 'api/navaid/list',
        data: {type : sub,ctry:'ID'},
        type: "json",
        method: "get",

        success: function (result) {
            $('#row-judul').empty();
            $('#row-judul').append('<tr><th scope="col">#</th><th scope="col">No</th><th scope="col">Ident</th><th scope="col">Freq</th><th scope="col">Type</th><th scope="col">Name</th></tr>');
            // let x = document.getElementById("List-Data");
            // x.style.visibility = 'visible';
             Visibility("List-Data", 'False');
            $.each(result, function () {
                $('#isi-table').html(result);
                $.each( this, function ( k, v )
                {
                    
                    let isi = [ v.nav_ident, v.freq,  v.definition,  v.nav_name ];
                    $( '#isi-table' ).append( tdtemplate( v.nav_id+'$navaid', ( k + 1 ), isi, '4', 'navaid' ) );
                    // console.log(FreqFormat(v.freq,v.type,''))

                });
            });
            // $('#per-page').append(result=>link());
        }

    });
   // return retval;
}



function WaypointList(typid) {
    //  console.log("MASUUUUUUK NavaidList " + id)
    let sub ='1';
    switch (typid) {
        case 'ENROUTE WPT':
            sub = '1'
            break;
        case 'VFR WPT':
            sub = '4'
            break;
        case 'TERMINAL WPT':
            sub = '2'
            break;
        case 'ENROUTE & TERMINAL WPT':
            sub = '3'
            break;
    };
    typid = '';
       // document.getElementById("subsection").value;
    console.log('WaypointList ' + sub);
    // timeout: 10000;
    $.ajax({
        url: 'api/waypoint/list',
        data: {usage : sub,ctry:'ID'},
        type: "json",
        method: "get",

        success: function (result) {
            $('#row-judul').empty();
            $('#row-judul').append('<tr><th scope="col">#</th><th scope="col">No</th><th scope="col">Name</th><th scope="col">Desc Name</th><th scope="col">Usage</th></tr>');
            // let x = document.getElementById("List-Data");
            // x.style.visibility = 'visible';
             Visibility("List-Data", 'False');
            $.each(result, function () {
                $('#isi-table').html(result);
                $.each(this, function (k, v) {
                    let isi = [ v.wpt_name,  v.desc_name,  v.definition ];
                    $('#isi-table').append(tdtemplate(v.wpt_id+'$waypoint',(k + 1), isi, '3','WaypointModal'));
                    // $('#isi-table').append("<tr><td>" + (k + 1) + "</td><td>" + v.wpt_name + "</td><td>" + v.desc_name + "</td>" +
                    //     "<td>" + v.definition + "</td><td><a href='' class='badge badge-primary'>New</a><a href='' class='badge badge-success'>edit</a><a href='' class='badge badge-danger'>delete</a></td>");
                    // console.log(v.paginate);

                });
            });
            // $('#per-page').append(result=>link());
        }

    });
   // return retval;
}

function AirspaceList( aspid )
{

    let aspsub='';
    if (aspid == 'undefined') {
        aspid = 'FIR';
    }
    aspsub = aspid;
       // document.getElementById("subsection").value;
    // console.log( 'AirspaceList ' + aspsub );
    $( '#isi-table' ).empty();
    // timeout: 10000;
    $.ajax({
        url: 'api/airspace/list',
        data: {'ctry' : 'ID','airspace_type':aspid},
        type: "json",
        method: "get",

        success: function (result) {
            $( '#row-judul' ).empty();
         
            $('#row-judul').append('<tr><th scope="col">#</th><th scope="col">No</th><th scope="col">Name</th><th scope="col">Type</th><th scope="col">Unit</th></tr>');
            // let x = document.getElementById("List-Data");
            //  x.style.visibility = 'visible';
             Visibility("List-Data", 'False');
             $.each(result.data, function (k, v) {
        
                // $.each(this, function (k, v) {
                    let isi = [ v.airspace_name,  v.airspace_type,  v.ats_unit ];
                    $('#isi-table').append(tdtemplate(v.ats_airspace_id+'$airspace',(k + 1), isi, '3','airspace'));
                //   $('#isi-table').append("<tr><td>" + (k + 1) + "</td><td>" + v.airspace_name + "</td><td>" + v.airspace_type + "</td>" +
                //         "<td>" + v.ats_unit + "</td><td><a href='' class='badge badge-primary'>New</a><a href='' class='badge badge-success'>edit</a><a href='' class='badge badge-danger'>delete</a></td>");
                //     // console.log(v.paginate);

                // });
            });
            // $('#per-page').append(result=>link());
        }

    });
   // return retval;
}

function SuasList(aspid) {
    let aspsub='';
    aspsub = aspid.substring(0,1);
       // document.getElementById("subsection").value;
    console.log('SuasList ' + aspsub);
    // timeout: 10000;
    $.ajax({
        url: 'api/suas/list',
        data: {suas_type : aspsub,ctry:'ID'},
        type: "json",
        method: "get",

        success: function (result) {
            $('#row-judul').empty();
            $('#row-judul').append('<tr><th scope="col">#</th><th scope="col">No</th><th scope="col">Ident</th><th scope="col">Name</th><th scope="col">Type</th></tr>');
            // let x = document.getElementById("List-Data");
            // x.style.visibility = 'visible';
            Visibility("List-Data", 'False');
            $.each(result, function () {
                $('#isi-table').html(result);
                $.each(this, function (k, v) {
                let isi = [ v.suas_ident,  v.suas_name,  v.definition ];
                $('#isi-table').append(tdtemplate(v.suas_id+'$suas',(k + 1), isi, '3','suas'));
                //   $('#isi-table').append("<tr><td>" + (k + 1) + "</td><td>" + v.suas_ident + "</td><td>" + v.suas_name + "</td>" +
                //         "<td>" + v.definition + "</td><td><a href='/suas/new/{{" + v.suas_id + "}}' class='badge badge-primary'>New</a><a href='/suas/edit/{{" + v.suas_id + "}}' class='badge badge-success'>edit</a><a href='/suas/delete/{{" + v.suas_id + "}}' class='badge badge-danger'>delete</a></td></tr>");
                //     // console.log(v.paginate);

                });
            });
            // $('#per-page').append(result=>link());
        }

    });
   // return retval;
}

function ArrayList(type,table) {

    $.ajax({
        url: 'api/eaip/type',
        data: {id : type},
        type: "json",
        method: "get",
    success: function(result){
        $( '#Vol-Data' ).empty();
        
        $.each(result.data, function (k, v) {
            var hsl = '<div class="custom-control custom-checkbox">';
                if (k == 0) {
                    hsl += '<input class="form-check-input checkbox" type="checkbox" checked="checked" id="' + v + '$' + table + '" value="' + v + '$' + table + '">';
                } else {
                    
                    hsl += '<input class="form-check-input checkbox" type="checkbox" id="' + v + '$' + table + '" value="' + v + '$' + table + '">';
                }
                hsl += '<label class="form-check-label" for="'+ v +'"><strong>'+ v +'</strong></label>' +
                '</div>';
            $( '#Vol-Data' ).append( hsl );
        });
    }
    });
}

function AirportModal() {
    $(document).on('click','.open_modal',function(){
        let arp_id = $(this).val();
        Source_nrList('#ad-nr');
        CountryList("#ctry");
        let nr;

    $.ajax({
        url: 'app/arptshow',
        data: {arpt_ident : arp_id},
        type: "json",
        method: "get",

        success: function (result) {
            $.each(result, function () {
                $.each(this, function (k, v) {
                    console.log('ARPT MODAL=');
                    console.log(v);
            // console.log(v.arpt_ident);
            // console.log(v.arpt_name);
            // console.log(v.icao);

                    document.getElementById("modalMdTitle").innerHTML="Edit " + v.icao + " " + v.arpt_name + ' Airport'
                    $('#icao').val(v.icao);
                    $('#iata').val(v.iata);
                    $('#arpt_name').val(v.arpt_name);
                    $('#arpt_city').val(v.city_name);

                    let cord = SetCoordinatebyGeom(v.geom);
                    $('#latitude').val(cord.Database[1]);
                    $('#longitude').val(cord.Database[0]);
                    myGeoMag = geoMag(cord.Decimal[1], cord.Decimal[0], 0);
                    console.log(myGeoMag.dec + '  ' + myGeoMag.magvar);
            // .append("<option value="+v.id+">"+v.sub_id + ' ' + v.definition+"</option>")
                    $('#ctry').append("<option selected='selected' value=" + v.ident + ">" + v.country + "</option>");
                    $('#ad-source').append("<option selected='selected' value=" + v.src_type + ">" + v.src_type + "</option>");
                    $('#ad-nr').append("<option selected='selected' value=" + v.id + ">" + v.src_id + "</option>");
                nr=v.id
            // console.log(v.vol);
            let vol_id;
            switch (v.vol) {
                case 2:
                    vol_id = "VOL II";
                    break;
                case 3:
                    vol_id = "VOL III";
                    break;
                case 4:
                    vol_id = "VOL IV";
                    break;
                case 5:
                    vol_id = "VOL V";
                    break;
                default:
                    break;
            }

            $('#ad-vol').append("<option selected='selected' value="+ v.vol +">"+ vol_id + "</option>");
            $('#btn-save').val("update");
            $('#AirportModal').modal('show');
            });
        });

        }
    });
        ArptADdata(arp_id,nr);

    });
    $.ajax({
            url: 'app/codaip/',
            type: "json",
            method: "get",
        success: function(result){
            $('#sub-ad').empty();
            $('#sub-ad').append("<option selected>Select Sub AD</option>");
            $.each(result, function() {
                $.each(this, function(k, v) {
                $('#sub-ad').append("<option value='"+ v.id + ' ' + v.definition + "'>"+v.id + ' ' + v.definition+"</option>")
            // console.log(v.definition)
                });
                });
            }
    });
}

function WaypointModal() {
    $(document).on('click','.open_modal',function(){
        let wpt_id = $(this).val();
         CountryList("#wptctry");
    $.ajax({
        url: 'app/wptshow',
        data: {wpt_id : wpt_id},
        type: "json",
        method: "get",

        success: function (result) {
            $.each(result, function () {
                $.each(this, function (k, v) {
                    console.log('WPT MODAL=');
                    console.log(v);
            // console.log(v.arpt_ident);
            // console.log(v.arpt_name);
            // console.log(v.icao);
                    // let wptDef = v.definition;
                    // if (wptDef == 'BOTH') {
                    //     wptDef = 'ENROUTE and TERMINAL';
                    // }
                    document.getElementById("modalWptMdTitle").innerHTML = "Edit " + v.wpt_name + ' (' + v.definition + ')';

                    $('#wptident').val(v.wpt_name);
                    $('#wptdesc').val(v.desc_name);
                    // $('#wpttype').val(v.type);
                    // $('#wptusage').val(v.definition);

                    let cord = SetCoordinatebyGeom(v.geom);
                    $('#wptlat').val(cord.Database[1]);
                    $('#wptlon').val(cord.Database[0]);
                    myGeoMag = geoMag(cord.Decimal[1], cord.Decimal[0], 0);
                    console.log(myGeoMag);
                    $('#wptmagvar').val(myGeoMag.magvar);
                    console.log(myGeoMag.tanggal);
                    // document.getElementById("wptyear").innerText = myGeoMag.tanggal;
                    $('#wptyear').val(myGeoMag.tanggal);
                    console.log(document.getElementById("wptyear").val);
                    $('#wptusage').append("<option selected='selected' value=" + v.usage_cd + ">" + v.definition + "</option>")
                    $('#wptctry').append("<option selected='selected' value=" + v.ident + ">" + v.country + "</option>");
            // console.log(v.vol);

                    let wpttype_id;
                    switch (v.type) {
                        case '1':
                            wpttype_id = "Compulsory";
                            break;
                        case '2':
                            wpttype_id = "Non Compulsory";
                            break;
                        case '3':
                            wpttype_id = "MET Compulsory";
                            break;
                        case '4':
                            wpttype_id = "MET Non Compulsory";
                            break;
                        case '5':
                            wpttype_id = "RNAV";
                            break;
                        case '6':
                            wpttype_id = "DME Fix";
                            break;
                        case '7':
                            wpttype_id = "Fix Point";
                            break;
                        case '8':
                            wpttype_id = "Change Over Point (COP)";
                            break;
                        default:
                            break;
                    }
                    console.log(v.type + '  ' +  wpttype_id)
                    $('#wpttype').append("<option selected='selected' value="+ v.type +">"+ wpttype_id + "</option>");
                    $('#btn-save').val("update");
                    $('#WaypointModal').modal('show');
            });
        });

        }
    });

    });

}

function NavaidModal() {
    $(document).on('click','.open_modal',function(){
        let nav_id = $(this).val();
        CountryList("#navctry");
        $.ajax({
            url: 'app/navshow',
            data: {nav_id : nav_id},
            type: "json",
            method: "get",

            success: function (result) {
                $.each(result, function () {
                    $.each(this, function (k, v) {
                        console.log('NAVAID MODAL=');
                        console.log(v);
                        document.getElementById("modalNavMdTitle").innerHTML = "Edit " + v.nav_ident + ' (' + v.definition + ')';

                        $('#navident').val(v.nav_ident);
                        $('#navname').val(v.nav_name);
                        let cord = SetCoordinatebyGeom(v.geom);
                        $('#navlat').val(cord.Database[1]);
                        $('#navlon').val(cord.Database[0]);
                        myGeoMag = geoMag(cord.Decimal[1], cord.Decimal[0], 0);
                        console.log(myGeoMag);
                        $('#navmagvar').val(myGeoMag.magvar);
                        console.log(myGeoMag.tanggal);
                        // document.getElementById("navyear").innerText = myGeoMag.tanggal;
                        $('#navyear').val(myGeoMag.tanggal);
                        $('#navfreq').val(v.freq);
                        $('#navrange').val(v.range);
                        $('#navalt').val(v.alt);
                        $('#navoprhrs').val(v.opr_hrs);
                        $('#navremarks').val(v.remarks);
                        console.log(v.col_dme);
                        let chk = document.getElementById("navdme");
                        if (v.col_dme == "Y") {
                            chk.checked = true
                            // $("#navdme").checked = true;
                        } else {
                            chk.checked = false;
                            // $("#navdme").checked = false;
                        }
                        // console.log(document.getElementById("navyear").val);
                        $('#navtype').append("<option selected='selected' value=" + v.type + ">" + v.definition + "</option>")
                        $('#navctry').append("<option selected='selected' value=" + v.ident + ">" + v.country + "</option>");
                        $('#btn-save').val("update");
                        $('#NavaidModal').modal('show');
                    });
                });
            }
        });
    });
}

function Isiadinfo(classform, arpt_ident, data, txt, checkdata, sourceid) {
    let adinfo = document.getElementsByName(classform);

    for (const key in checkdata) {
        console.log('key ' + checkdata[key]);
        // console.log('adinfo ' + no + adinfo[no].getAttribute('id'));
        // no += 1
        if (checkdata.hasOwnProperty(key)) {
            const element = checkdata[key];
          //  console.log(element);
            let hhsl = parsejsonTemp(element, arpt_ident);
           // console.log('NO....' + hhsl + ' ' + element)
            let hslcur = parsejsonCurrent(element, arpt_ident);
           // console.log('NO....' + hslcur + ' ' + element)
            $.each(data, function () {
                    let no = 0
                    $.each(this, function (k, v) {
                        console.log('NO....' + no + '  ' + v.category_id + ' ' + element)
                        // console.log('adinfo ' + no + adinfo[no].getAttribute('id'));
                    if (v.category_id == '2') {
                        if (hhsl.content != '' || hhsl.id != sourceid) {
                            let cr = v.content.split(' ')
                            $(adinfo[0]).val(cr[0]);
                            $(adinfo[1]).val(cr[1]);

                        }
                         no = 2;
                    } else {
                        if (hhsl.content != '' || hhsl.id != sourceid) {
                           $(adinfo[no]).val(v.content);
                        }

                        no += 1;
                    }
                    //  console.log(element)


                    });
                });
        }
    }




    // for (const key in checkdata) {
    //     if (checkdata.hasOwnProperty(key)) {
    //         const element = checkdata[key];
    //         let hhsl = parsejson(element, arpt_ident);

    //     }
    // }
}



function ArptADdata(arptident,srcnr) {

    $.ajax({
        url: 'app/arpt_ad',
        data: {id : arptident},
        type: "json",
        method: "get",
        success: function (result) {
            let txtarr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
            let dataarr = [2, 3, 228, 4, 5, 212, 6, 7, 8, 9, 10, 11, 12, 227, 13, 14]
            console.log(result);
            // Isiadinfo("ad21", arptident, result, txtarr, dataarr,srcnr);
            // $.each(result, function () {
            //     console.log('sebelum di pecah');
            //     console.log(result);
            //     $.each(this, function (k, v) {

            //        // console.log('ArptADdata ' + v)
            //         if (v.id == '2') {
            //             let hhsl = parsejson('2', arptident);

            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 let cr = v.content.split(' ')
            //                 $(adinfo[0]).val(cr[0]);
            //                 $(adinfo[1]).val(cr[1]);
            //                 // console.log('CONTENT ADA')
            //             }

            //         } else if (v.id == '3') {
            //             let hhsl = parsejson('3', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[2]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '228') {
            //             let hhsl = parsejson('228', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[3]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //           } else if (v.id == '4') {
            //             let hhsl = parsejson('4', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[4]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '5') {
            //             let hhsl = parsejson('5', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[5]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '212') {
            //             let hhsl = parsejson('212', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[7]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '6') {
            //             let hhsl = parsejson('6', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[8]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '7') {
            //             let hhsl = parsejson('7', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[9]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '8') {
            //             let hhsl = parsejson('8', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[10]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '9') {
            //             let hhsl = parsejson('9', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[11]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '10') {
            //             let hhsl = parsejson('10', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[12]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '11') {
            //             let hhsl = parsejson('11', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[13]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '12') {
            //             let hhsl = parsejson('12', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[14]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '227') {
            //             let hhsl = parsejson('227', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[15]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '13') {
            //             let hhsl = parsejson('13', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[16]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         } else if (v.id == '14') {
            //             let hhsl = parsejson('14', arptident);
            //             if (hhsl.content != '' || hhsl.id != srcnr) {
            //                 $(adinfo[17]).val(v.content);
            //                 // console.log('CONTENT ADA')
            //             }
            //         }

            //     });
            // });

        }
    });
}

function parsejsonTemp(idx, arptident) {

        let hhsl1 = CheckStatusTemp(idx, arptident);
        var json = JSON.parse(hhsl1);

    let jhsl = json.aipdata.replace('[', '')
     jhsl=jhsl.replace(']','')
   console.log('HASILLLLLLL parsejsonTemp');
    //console.log(json);
    console.log(jhsl);
    if (jhsl == ""){
        return jhsl;
    } else {
        return JSON.parse(jhsl);
    }

}

function parsejsonCurrent(idx, arptident) {

        let hhsl2 = CheckStatusCurrent(idx, arptident);
        var json = JSON.parse(hhsl2);
        let jhsl = json.aipdata.replace('[', '')
        jhsl=jhsl.replace(']','')
   console.log('HASILLLLLLL parsejsonCurrent');
    //console.log(json);
   console.log(jhsl);
    if (jhsl == "") {
        return jhsl;
    } else {
        return JSON.parse(jhsl);
    }
}

function CheckStatusTemp(idx, arptident) {

var rslt =   $.ajax({
        url: 'app/getcheck',
        data: { catid: idx, arpt_ident: arptident },
        type: "json",
        method: "get",
        async: false,
});
  //  console.log('HASIIL TEMP')
  //  console.log(rslt.responseText)
return rslt.responseText;
}



function CheckStatusCurrent(idx, arptident) {

var rsltc =   $.ajax({
        url: 'app/getcurrent',
        data: { catid: idx, arpt_ident: arptident },
        type: "json",
        method: "get",
        async: false,
});
  //   console.log('HASIIL CURRENT')
  //  console.log(rsltc.responseText)
return rsltc.responseText;
}

function Source_nrList(obj) {
   // console.log(obj);
    timeout: 10000;
    $.ajax({
        url: 'app/source_nr',
        type: "json",
        method: "get",
    success: function(result){
            // $('#ad-nr').empty();
            $.each(result, function() {
                $.each(this, function (k, v) {
                //    console.log(obj + "<option value="+v.id+">"+v.src_id + "</option>")
                $(obj).append("<option value="+v.id+">"+v.src_id + "</option>")
            // console.log(v.definition)
                });
                });
        }
    });
}

function CountryList(obj) {
   // console.log(obj);
    timeout: 10000;
    $.ajax({
        url: 'app/ctry',
        type: "json",
        method: "get",
    success: function(result){
            // $('#ad-nr').empty();
            $.each(result, function() {
                $.each(this, function (k, v) {
                //    console.log(obj + "<option value="+v.id+">"+v.src_id + "</option>")
                $(obj).append("<option value="+v.ident+">"+v.country + "</option>")
            // console.log(v.definition)
                });
                });
        }
    });
}
function showdetail( id )

{
    var dd = id.split( '$' );
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open('/map.php?table='+dd[1]+'&id='+dd[ 0 ], 'Set Latitude and Longitude', params)
    
    
}

function removeobject( id )

{
    console.log( id );
    Swal.fire({
        title: 'Delete Data',
        text: "The data status will be deleted!",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deleted it!'
    }).then((result) => {
        if (result.value) {
            // console.log('SAVE RAW DETAIL ',  id,"{{ URL::to('/') }}/api/pub/rawdata/update/")
           
                    Swal.fire(
                        'Delete!',
                        'Data Status has been Deleted.',
                        'success'
                    );
            
        }else{
            location.reload();

        }
    })
    
}

function editobject( id )

{
    var dd = id.split('$');
    console.log(dd[1])
    switch (dd[1]) {
        case 'ats':
            window.scrollTo(0,0);
            window.location.href = '/listats/'+dd[0];
            break;
        case 'airspace':
        
            break;
        case 'suas':
    
            break;
        case 'navaid':
            window.scrollTo(0,0);
            window.location.href = '/navaidinfo/' + dd[0] + '@edit';
            break;
        case 'waypoint':

            break;
        default:
            break;
    }
    console.log( id );
    
    
}

function tdtemplate(id,no, isi, jmlcol,modalshow) {
  //  console.log(no + ' ' + isi + ' ' + jmlcol)
    let hasil;
   
        hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-dim btn-primary" id="'+ id + '" onClick="editobject(this.id)"><i class="icon ni ni-edit"></i>Edit</a><a class="btn btn-dim btn-info" id="'+ id+'$'+ modalshow+ '" onClick="showdetail(this.id)"><i class="icon ni ni-map"></i>Show</a><a class="btn btn-dim btn-danger" id="'+ id + '" onClick="removeobject(this.id)"><i class="icon ni ni-delete"></i>Remove</a></ul></div></div></td>'




    if (jmlcol == '4') {
        hasil += "<td>" + no + "</td><td>" + isi[0] + "</td><td>" + isi[1] + "</td><td>" + isi[2] + "</td><td>" + isi[3] + "</td></tr>"
    } else if (jmlcol == '3') {
        hasil += "<td>" + no + "</td><td>" + isi[0] + "</td><td>" + isi[1] + "</td><td>" + isi[2] + "</td></tr>"
    } else if (jmlcol == '2') {
        hasil += "<td>" + no + "</td><td>" + isi[0] + "</td><td>" + isi[1] + "</td></tr>"
    } else if (jmlcol == '5') {
        hasil += "<td>" + no + "</td><td>" + isi[0] + "</td><td>" + isi[1] + "</td><td>" + isi[2] + "</td><td>" + isi[3] + "</td><td>" + isi[4] + "</td></tr>"
    }
   
    // hasil += "<td><button type='button' class='badge badge-pill badge-danger open_modal_delete f' value='" + id + "' onclick='DeleteModal()'>Delete</button><button type='button' class='badge badge-pill badge-primary open_modal f' value='" + id + "' onclick='"+ modalshow + "()'>edit</button></td></tr>"
return hasil
}
