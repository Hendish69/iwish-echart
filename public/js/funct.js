
// const { map } = require( "jquery" );

// const { forEach } = require( "lodash" );

var platDecimal; PrevData = [];
var plonDecimal; pntmarkers = []; atslines = []; arptmarkers = []; marks = []; points = [];

var proctext = '';
const pathpop= function() {
    var pathArr = window.location.href.split( "/" );
    // console.log(pathArr,pathArr.length);
    var path=pathArr[0] + "//" + pathArr[2]
    // pathArr.pop();
    // if (pathArr.length ==4){
    //     var path = pathArr.join( "/" );
    // } else if (pathArr.length ==5){
    //     var path = pathArr.join( "/" );
    //     pathArr = path.split( "/" );
    //     pathArr.pop();
    //     // console.log(pathArr)
    //     var path = pathArr.join( "/" );
    // }
//  console.log(path)
    return path
}

function propercase(str){
    
    str=str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        // console.log(str,letter.toUpperCase())
        // return str.toLowerCase().replace(/\b[a-z]/g)
        return letter.toUpperCase();
    });
    return str.replace('Ii','II').replace('Dr','DR').replace('H.As.','H.AS.').replace('(Imip)','(IMIP)');
}
function setMarkersWpt( wpt )
{
    var ctn=[];
    for (let i = 0; i < wpt.length; i++) {
        var ttl = ''; wtype = '2'; wptid='';
        var ident = ''; nm = ''; def = '';
        wptid = wpt[ i ].wpt_id;
            ident=wpt[i].wpt_name;
            nm=wpt[i].desc_name;
            def=wpt[i].definition;
            ttl=wpt[i].wpt_name;
    if (wpt[i].type=='' || wpt[i].type==' ' || wpt[i].type==null){
        wtype='2';
    }else{
        wtype=wpt[i].type;
    }
    ctn[i]= wptinfo(wpt[i]);
    var ic = setIcon(wptymbol[wtype].icon);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng({lat: wpt[i].geom.coordinates[1],lng: wpt[i].geom.coordinates[0]}),
            icon: ic,
            title: ttl,
            map: map,
            clickable:true,
            id:wptid,
            info:ctn[i]
        });
        
        var cord = SetCoordinatebyGeom( wpt[ i ].geom )
                hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-var btn-secondary" id="'+ wptid + '" onClick="showusagewpt(this.id)"><i class="icon ni ni-view-grid"></i>Detail</a><a class="btn btn-var btn-info" id="'+ wptid + '" onclick="showdetailwpt(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + ident + '</td><td>' + nm + '</td><td>' + cord.WGSAIP[1] + '</td><td>' + cord.WGSAIP[0] + '</td><td>' + def + '</td></tr>'
                $("#wptlist").append(hasil);
       
        // ctn[i] = "<strong>"+"Mountain Name : "+data_vas[i].va_name+"</strong><br>";
        makeInfoWindow(map, infowindow, ctn[i], marker);
        pntmarkers.push( marker );
        wpt[ i ][ 'pointtype' ] = 'WPT';
        points.push( wpt[ i ] );
  }  
  return pntmarkers
}
function setArptIcon( uri, vol )
{
    var pix = map.getZoom(); sz = 8;

    // var sz=15;
    // console.log( pix );
    switch(vol){
        case 2:sz=(pix * 4);break;
        case 3:sz=(pix * 2.5);break;
        case 4:sz=(pix * 2);break;
        case 5:sz=(pix * 2);break;
    }
    var image = {
            url: uri, 
            size: new google.maps.Size(sz, sz), 
            origin: new google.maps.Point(0, 0), 
            anchor: new google.maps.Point(sz/2, sz/2),
            scaledSize: new google.maps.Size(sz, sz),
        };
    return image;
}
function setArptIconx(path, color, vol, degree=0 )
{
    var pix = map.getZoom(); sz = 8;

    // var sz=15;
    // console.log( pix );
    switch(vol){
        case 2:sz=(pix * .013);break;
        case 3:sz=(pix * .0040);break;
        case 4:sz=(pix * .01);break;
        case 5:sz=(pix * .005);break;

    }
    var icon = {
            path: path,
            fillColor: color,
            fillOpacity: .6,
            strokeWeight: 0,
            scale: sz,
            rotation: degree,
            // size: new google.maps.Size(sz, sz),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(200, 200),
            //origin: new google.maps.Point(25, 25),
            // anchor: new google.maps.Point(sz/2, sz/2),
            //scaledSize: new google.maps.Size(sz, sz),
        };
    return icon;
}
function Drawline(ats){
    var clrline='';opac='';
    var atsid='';point1=[];point2=[];
    ats.forEach(a=>{
        var clrline='';
        switch(a.atstype){
            case "DOM":
                clrline="#db7734";
            break;
            case "INTL":
                clrline="#0e0702";
            break;
            case "RNAV":
                clrline="#0000b3";
            break;
            case "VFR":
                clrline="#00b300";
            break;
        }
        var ppp=[]
        if (a.nav1.length==0){
            ppp=a.wpt1
            ppp['pointtype']='WPT';
            setMarkersWpt(a.wpt1);
        }else{
            ppp=a.nav1
            ppp['pointtype']='NAV';
            setMarkerNav(a.nav1);
        }

        if (a.nav2.length==0){
            ppp=a.wpt2
            ppp['pointtype']='WPT';
            setMarkersWpt(a.wpt2);
        }else{
            ppp=a.nav2
            ppp['pointtype']='NAV';
            setMarkerNav(a.nav2);
        }
        point1.push(ppp);


        var contentString = createatsattr(a);
        let lines = TranslateLine(a.geom.coordinates);
        const atsroutes = new google.maps.Polyline({
            path: lines,
            geodesic: true,
            strokeColor: clrline,
            strokeOpacity: 1.0,
            strokeWeight: 1.5,
            id:a.ctry,
            titel:a.ats_ident,
            info:contentString,

        });
      
        let atsnm=a.ats_ident + ' (' + a.point_1 + ' to ' + a.point_2 + ')';
       
       
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < atsroutes.getPath().getLength(); i++) {
                bounds.extend(atsroutes.getPath().getAt(i));
        }
        google.maps.event.addListener(atsroutes, 'click', function(event) {
            
            infowindow.setContent(contentString);
            infowindow.setPosition(event.latLng);
            infowindow.open(map);
        });
    

        var  infotitle = new google.maps.InfoWindow();
        google.maps.event.addListener(atsroutes, 'mouseover', mousefn);
        google.maps.event.addListener( atsroutes, 'mouseout', function ( evt )
        {
            this.setOptions({strokeColor: clrline,strokeWeight: 1.5});
            infotitle.close();
            infotitle.opened = false;
        });
        function mousefn( evt )
        {
            // console.log(evt)
            // infowindow.setContent("polygon<br>coords:" + bounds.getCenter().toUrlValue(6));
            this.setOptions({strokeColor: "#FF0000",strokeWeight: 5});
            // atsroutes.strokeColor = "#a00000";
            infotitle.setContent(contentString);
            infotitle.setPosition(evt.latLng); // or evt.latLng
            infotitle.open(map);
        }
        // for (var i = 0; i < airspace.getPath().getLength(); i++) {
        //     bounds.extend(airspace.getPath().getAt(i));
        // }
        atsroutes.setMap(map);
        atslines.push( atsroutes );

    })
    // initMap();
}

function createatsattr(ats){
    var id=ats.id;
    var nm=ats.ats_ident;
    var dist=ats.dist + 'nm';
    var trackout=ats.track_out;
    var trackin=ats.track_in;
    var mea=ats.mea_out;
    var frq = ats.point_1 + ' - ' + ats.point_2
    var upper = ats.maa; lower = ats.mfa; cls = ats.seg_use; latlim = ats.rnp_type;
    if (ats.dir_424=='F'){
        trackout=ats.track_out + '°';
        trackin='';
    } else if ( ats.dir_424 == 'B' ) {
        frq = ats.point_2 + ' - ' + ats.point_1
        trackout=ats.track_in + '°';
        trackin='';
    }else{
        trackout=ats.track_out + '°';
        trackin=ats.track_in + '°';
    }


    return '<div class="popover-content" width="200px !important; padding:30px 30px 30px 30px;"><b style="font-size:20px;">' + nm  + '</b><br><b style="font-weight:bold;font-size:15px;">' + frq  + '</b><br><br><table width="200px !important;">'+
        '<tr>'+
        '<td align="left" width="80" style="font-weight:bold;"><b>Track Out</b></td>'+
        '<td>:</td>'+
        '<td>'+ trackout + '</td>'+
        '</tr>'+
        '<tr>'+
            '<td align="left" style="font-weight:bold;"><b>Track in</b></td>'+
            '<td>:</td>' +
            '<td>'+ trackin +'</td>'+
        '</tr>'+
        '<tr>'+
            '<td align="left" style="font-weight:bold;"><b>Distance</b></td>'+
            '<td>:</td>'+
            '<td>'+ dist +'</td>'+
        '</tr>'+
        '<tr>'+
            '<td align="left" style="font-weight:bold;"><b>Upper</b></td>'+
            '<td>:</td>'+
            '<td>'+upper +'</td>'+
        '</tr>' +
        '<tr>'+
            '<td align="left" style="font-weight:bold;"><b>Lower</b></td>'+
            '<td>:</td>'+
            '<td>'+ lower +'</td>'+
        '</tr>' +
        '<tr>'+
            '<td align="left" style="font-weight:bold;"><b>Min. Alt</b></td>'+
            '<td>:</td>'+
            '<td>'+ mea +'</td>'+
        '</tr>'+
        '<tr>'+
            '<td align="left" style="font-weight:bold;"><b>Airspace Class</b></td>'+
            '<td>:</td>'+
            '<td>'+ cls +'</td>'+
        '</tr>' +
        '<tr>'+
            '<td align="left" style="font-weight:bold;"><b>Lateral Limit</b></td>'+
            '<td>:</td>'+
            '<td>'+ latlim +'</td>'+
        '</tr>'+
        '</table>'//+
           // '<br><br>' + btncdm  + btndetail + btnforecast;
}
function removelabel() {
    for(var i = 0; i < marks.length; i++) {
        marks[i].setMap(null);
    }
}
function showlabel()
{
    for (var i=0; i < atslines.length; i++) {
        if (i%2==0){
        var bounds = new google.maps.LatLngBounds();
            for (var x = 0; x < atslines[i].getPath().getLength(); x++) {
                    bounds.extend(atslines[i].getPath().getAt(x));
            }
            var marker = new MarkerWithLabel({
                icon: { path: "M 0.00,0.00 C 0.00,0.00 1.00,0.00 1.00,0.00 1.00,0.00 0.00,1.00 0.00,1.00 0.00,1.00 0.00,0.00 0.00,0.00 Z"},
                position: bounds.getCenter(),
                map: map,
                draggable: true,
                raiseOnDrag: true,
                labelContent: atslines[i].titel,
                labelAnchor: new google.maps.Point(5,-5),
                labelClass: "labels", // the CSS class for the label
                labelInBackground: false,
                rotation:45,

            });
        marks.push(marker)

        }
}

for (var i=0; i < point1.length; i++) {
    var ttl='';
    // console.dir(point1[i][0]);
    if (point1[i][0].nav_id == null){
        ttl=point1[i][0].wpt_name;
    }else{
        ttl=point1[i][0].nav_ident;
    }
    
        var mrk = new MarkerWithLabel({
            icon: { path: "M 0.00,0.00 C 0.00,0.00 1.00,0.00 1.00,0.00 1.00,0.00 0.00,1.00 0.00,1.00 0.00,1.00 0.00,0.00 0.00,0.00 Z"},
            clickable:true,
            position: new google.maps.LatLng({lat: point1[i][0].geom.coordinates[1],lng: point1[i][0].geom.coordinates[0]}),
            map: map,
            draggable: true,
            raiseOnDrag: true,
            labelContent: ttl,
            labelAnchor: new google.maps.Point(5,-5),
            labelClass: "labels", // the CSS class for the label
            labelInBackground: false,

        });
        // var mrk = new MarkerWithLabel({
        //     icon: { path: "M 0.00,0.00 C 0.00,0.00 1.00,0.00 1.00,0.00 1.00,0.00 0.00,1.00 0.00,1.00 0.00,1.00 0.00,0.00 0.00,0.00 Z"},
        //     position: new google.maps.LatLng({lat: point1[i][0].geom.coordinates[1],lng: point1[i][0].geom.coordinates[0]}),
        //     map: map,
        //     draggable: true,
        //     raiseOnDrag: true,
        //     labelContent: ttl,
        //     labelAnchor: new google.maps.Point(0, 0),
        //     labelClass: "labels", // the CSS class for the label
        //     labelInBackground: false,
        // });
    marks.push(mrk)
}
}

function Showlabelpoint()
{
    // console.log('Showlabelpoint')
    for (var i=0; i < points.length; i++) {
    var ttl='';
    if (points[i].pointtype == 'WPT'){
        ttl=points[i].wpt_name;
    }else if (points[i].pointtype == 'NAV'){
        ttl = points[ i ].nav_ident;
    }else if (points[i].pointtype == 'ARP'){
        ttl=points[i].arpt_name;
    }
    // console.log(points)
        var mrk = new MarkerWithLabel({
            icon: { path: "M 0.00,0.00 C 0.00,0.00 1.00,0.00 1.00,0.00 1.00,0.00 0.00,1.00 0.00,1.00 0.00,1.00 0.00,0.00 0.00,0.00 Z"},
            clickable:true,
            position: new google.maps.LatLng({lat: points[i].geom.coordinates[1],lng: points[i].geom.coordinates[0]}),
            map: map,
            draggable: true,
            raiseOnDrag: true,
            labelContent: ttl,
            labelAnchor: new google.maps.Point(10,-10),
            labelClass: "labels", // the CSS class for the label
            labelInBackground: false,

        });
    marks.push(mrk)
}
}
function Getsymbol(pointtype,tbl) {
    var hasil = ''; img = '';
    // console.log( pointtype, tbl );
    if (tbl == 'navaid'){
        switch (pointtype) {
            case '1':
                ttp='VOR'
                img = '/images/marker/VOR.svg'
                imgS=[50, 50]
                break;
            case '2':
                ttp='VORTAC'
                img = '/images/marker/VORTAC.svg'
                imgS=[50, 50]
                break;
            case '3':
                ttp='TACAN'
                img = '/images/marker/TACAN.svg'
                imgS=[50, 50]
                break;
            case '4':
                ttp='VOR/DME'
                img = '/images/marker/VORDME.svg'
                imgS=[50, 50]
                break;
            case '5':
                ttp='NDB'
                img = '/images/marker/NDB.svg'
                imgS=[30, 30]
                break;
            case '7':
                ttp='NDB/DME'
                img = '/images/marker/NDB.svg'
                imgS=[30, 30]
                break;
            case '10':
                ttp='LOC'
                img = '/images/marker/NDB.svg'
                imgS=[30, 30]
                break;
            default:
                ttp='RADAR'
                img = '/images/marker/NCRP.svg'
                imgS=[30, 30]
                break;
        }

    } else if (tbl=='waypoint'){
        switch (pointtype) {
            case '1':
                ttp='CRP'
                img = '/images/marker/CRP.svg'
                imgS=[50, 50]
                break;
            case '2':
                ttp='NCRPC'
                img = '/images/marker/NCRP.svg'
                imgS=[50, 50]
                break;
            case '3':
                ttp='MRP'
                img = '/images/marker/MRP.svg'
                imgS=[50, 50]
                break;
            case '4':
                ttp='MRP'
                img = '/images/marker/MRP.svg'
                imgS=[50, 50]
                break;
            case '5':
                ttp='RNAV'
                img = '/images/marker/RNAVC.svg'
                imgS=[50, 50]
                break;
            default:
                ttp='NCRP'
                img = '/images/marker/NCRP.svg'
                imgS=[50, 50]
                break;
        }

    } else if (tbl=='arpt'){
        switch (pointtype) {
            case '1':
                ttp='1'
                img = '/images/marker/ARPT_1.svg'
                imgS=[50, 50]
                break;
            case '2':
                ttp='2'
                img = '/images/marker/ARPT_2.svg'
                imgS=[50, 50]
                break;
            case '3':
                ttp='3'
                img = '/images/marker/ARPT_3.svg'
                imgS=[50, 50]
                break;
            case '4':
                ttp='4'
                img = '/images/marker/ARPT_4.svg'
                imgS=[50, 50]
                break;
            case '5':
                ttp='5'
                img = '/images/marker/ARPT_5.svg'
                imgS=[30, 30]
                break;
            default:
                ttp='2'
                img = '/images/marker/ARPT_2.svg'
                imgS=[30, 30]
                break;
        }

    } else if (tbl=='obst'){
        switch (pointtype) {
            case '1':
                ttp='1'
                img = '/images/marker/obst.svg'
                imgS=[30, 30]
                break;
            case '2':
                ttp='2'
                img = '/images/marker/obst_l.svg'
                imgS=[30, 30]
                break;
            case '3':
                ttp='3'
                img = '/images/marker/obst_g.svg'
                imgS=[30, 30]
                break;
            case '4':
                ttp='4'
                img = '/images/marker/obst_g_l.svg'
                imgS=[30, 30]
                break;
            case '5':
                ttp='5'
                img = '/images/marker/obst_abv.svg'
                imgS=[30, 30]
                break;
            case '6':
                ttp='6'
                img = '/images/marker/obst_abv_l.svg'
                imgS=[30, 30]
                break;
            default:
                ttp='7'
                img = '/images/marker/obst.svg'
                imgS=[30, 30]
                break;
        }

    }
    hasil = img;
    // console.log(hasil,'hasil getsymbol')
    return hasil
}
function loadadc(id){
   
    var pathdetail= pathpop()   + '/api/airport/list/adc';
    var routes=[];
        $.ajax({
                url: pathdetail,
                data: {arpt_ident : id},
                type: "json",
                method: "GET",

                success: function (result) {
                    $.each(result.data, function (k, v) {
                        // console.log(v,v.geom.type)
                        if (v.geom.type == 'Polygon'){
                            if (v.layer == "strip" || v.layer == 'coastline'){
                            }else{
                                
                                let lines = TranslatePoly(v.geom.coordinates[0]);
                                var clrline=getadccolor(v.layer);
                                // console.log(v.layer)
                                var contentString = v.layer;
                                const adc = new google.maps.Polygon({
                                    id:v.gid,
                                    // popup:contentString,
                                    paths: lines,
                                    geodesic: true,
                                    strokeColor: clrline,
                                    strokeOpacity: .5,
                                    strokeWeight: 1,
                                    fillColor: clrline,
                                    name:v.layer ,
                                    fillOpacity: .5,
                                });
                                var bounds = new google.maps.LatLngBounds();
                                    for (var i = 0; i < adc.getPath().getLength(); i++) {
                                            bounds.extend(adc.getPath().getAt(i));
                                    }
                                    google.maps.event.addListener(adc, 'click', function(event) {
                                        
                                        infowindow.setContent(contentString);
                                        infowindow.setPosition(event.latLng);
                                        infowindow.open(map);
                                    });
                                    var  infotitle = new google.maps.InfoWindow();
                                    google.maps.event.addListener(adc, 'mouseover', mousefn);
                                    google.maps.event.addListener(adc, 'mouseout', function(evt) {
                                        this.setOptions({fillOpacity: .5});
                                        infotitle.close();
                                        infotitle.opened = false;
                                    });
                                    function mousefn(evt) {
                                        // infowindow.setContent("polygon<br>coords:" + bounds.getCenter().toUrlValue(6));
                                        // console.log(evt)
                                        this.setOptions({fillOpacity: 0});
                                        infotitle.setContent(contentString);
                                        infotitle.setPosition(evt.latLng); // or evt.latLng
                                        infotitle.open(map);
                                    }
                                adc.setMap(map);
                            }
                    }
                    })
                    
                }
        })
}

function GetIntervalinDate(date1,date2)
{
    var date1 = new Date(date1);
    var date2 = new Date(date2);
    var diffTime = Math.abs(date2 - date1);
    var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
    // console.log(diffTime + " milliseconds");
    // console.log( diffDays + " days" );
    return diffDays;
}
function getIntervalInDays( date1, day )
{
    const today = new Date(date1)
    const tomorrow = new Date(today)
    tomorrow.setDate(tomorrow.getDate() + day)
    // const diffInMs = Math.abs( date1 + day );
    // console.log('diffInMs',tomorrow)
    return tomorrow
}
function showarptadc(id){

    // console.log('id arpt',id,arptmarkers)
    // for (var i=0; i<circlemarker.length; i++) {
    //     circlemarker[i].setMap(null);
    // }
    // circlemarker = [];
    let idx = arptmarkers.findIndex(x => x.id ===id);
        zoomshow=true
        // console.log(id,arptmarkers[idx].id)
        var cntrvol= arptmarkers[idx].position; 
        map.setZoom(15);
        map.setCenter(cntrvol);
        window.scrollTo(0,0);
            loadadc(id);

}
function getadccolor(id) {
    var hasil;
    switch(id){
        case "strip":
            hasil='#f0f0f0';
            break;
        case "rwymarking":
            hasil='white';
            break;
        case "rwy":
            hasil='black';
            break;
        case "roads":
            hasil='#de2d26';
            break;
        case "building":
            hasil='#3182bd';
            break;
        case "twy":
            hasil='#f0f0f0';
            break;
        case "apron":
            hasil='#636363';
            break;
        case "taxilane":
            hasil='#fff7bc';
            break;
        case "centerline":
            hasil='#f0f0f0';
            break;
        case "papi":
            hasil='#24F70F';
            break;
        case "cwy":
        case "resa":
            hasil='#F4F8F3';
            break;
    }

        return hasil

}
function formatalt( alt )
{
    if ( alt.substr( 0, 1 ) == '0' ) {
        alt = alt.substr( 1, alt.length - 1 )
    }
    if ( alt.length == 3 ) {
        return alt
    } else if ( alt.length == 4 ) {
        return alt.substr( 0, 1 ) + ' ' + alt.substr( 1, 3 )
    } else if ( alt.length == 5 ) {
        return alt.substr( 0, 2 ) + ' ' + alt.substr( 2, 3 )
    } else if ( alt.length == 6 ) {
        return alt.substr( 0, 1 ) + ' ' + alt.substr( 1, 3 ) + ' ' + alt.substr( 3, 3 )
    }

}
function ConverNumChart(TextString) {
    var hsl = ""
    var txt = TextString.trim().toUpperCase();
    var jm = txt.length
    // console.log(txt,jm)
        for ( let i = 0; i < jm; i++ ){
            var tmid = txt.substr(i, 1 )
            if ( hsl == '' ) {
                if ( alphanumeric( tmid) == true ){
                    hsl = NumAlpabet( tmid )
                } else {
                    hsl = Alphabet( tmid )
                }
            } else {
                if ( alphanumeric( tmid) == true ){
                    hsl += ' ' + NumAlpabet( tmid )
                } else {
                    hsl += ' ' + Alphabet( tmid )
                }
            }
            
        }
        // console.log(txt,jm,hsl)
    return hsl.toUpperCase()
}
function alphanumeric(inputtxt)
{ 
    // var Exp = /((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i;
    //unutk mengencek numerik atau alphabet, jika nilai TRUE = Numerik, else = Alpaabet
    var Exp = /((^[0-9]+))+$/i;
    // console.log(inputtxt)
    if(inputtxt.match(Exp))
    {
    // alert('Your registration number have accepted : you can try another');
    // document.form1.text1.focus();
    return true;
    }
    else
    {
        // alert('Please input alphanumeric characters only');
    return false;
    }
}
function Alphabet( Aplhabet )
{
    
    var Bet= ''
    switch ( Aplhabet ) {
        
        case "A":
            Bet = "Alpha"
            break;
        case "B":
            Bet = "Bravo"
            break;
        case "C":
            Bet = "Charlie"
            break;
        case "D":
            Bet = "Delta"
            break;
        case "E":
            Bet = "Echo"
            break;
        case "F":
            Bet = "Foxtrot"
            break;
        case "G":
            Bet = "Golf"
            break;
        case "H":
            Bet = "Hotel"
            break;
        case "I":
            Bet = "India"
            break;
        case "J":
            Bet = "Juliet"
            break;
        case "K":
            Bet = "Kilo"
            break;
        case "L":
            Bet = "Lima"
            break;
        case "M":
            Bet = "Mike"
            break;
        case "N":
            Bet = "November"
            break;
        case "O":
            Bet = "Oscar"
            break;
        case "P":
            Bet = "Papa"
            break;
        case "Q":
            Bet = "Quebec"
            break;
        case "R":
            Bet = "Romeo"
            break;
        case "S":
            Bet = "Sierra"
            break;
        case "T":
            Bet = "Tango"
            break;
        case "U":
            Bet = "Uniform"
            break;
        case "V":
            Bet = "Victor"
            break;
        case "W":
            Bet = "Whiskey"
            break;
        case "X":
            Bet = "X-ray"
            break;
        case "Y":
            Bet = "Yankee"
            break;
        case "Z":
            Bet = "Zulu"
            break;
    }
        return Bet
}

function NumAlpabet( Numer )
{
    var Bet = ""
    switch ( Numer ) {
        case "0":
            Bet = "Zero"
            break;
        case "1":
            Bet = "One"
            break;
        case "2":
            Bet = "Two"
            break;
        case "3":
            Bet = "Three"
            break;
        case "4":
            Bet = "Four"
            break;
        case "5":
            Bet = "Five"
            break;
        case "6":
            Bet = "Six"
            break;
        case "7":
            Bet = "Seven"
            break;
        case "8":
            Bet = "Eight"
            break;
        case "9":
            Bet = "Nine"
            break;
    }
    return Bet

    
}
function toarptinfo(id){
    // console.log(id);
    window.location.href = '/airportinfo/' + id+'@interaktif' ;
    // history.back(1);
   
  }
function setMarkersArpt( arpt )
{
    var ctn = [];
    for ( let i = 0; i < arpt.length; i++ ) {
        // console.log(' arpt[ i ]', arpt[ i ])
        ctn[ i ] = arptinfo( arpt[ i ] );

        let degree = 0;
        if(arpt[ i ].runways.length > 0) {
            let run_id = arpt[ i ].runways[0];
            if( typeof run_id.rwy_ident !== "undefined" ){
                run_id = run_id.rwy_ident;
                let thr = run_id.split('-');
                if((parseInt(thr[0]) * 10 ) > 90 )
                    degree = parseInt(thr[1]) * 10;
                else
                    degree = parseInt(thr[0]) * 10;

            }else{
                degree = 0;
                return false;
            }
        }

        // console.log(degree);
        var ic = setArptIconx( arpticons[ arpt[ i ].vol ].icon, arpticons[ arpt[ i ].vol ].color, arpt[ i ].vol, degree );
        var marker = new google.maps.Marker( {
            position: new google.maps.LatLng( { lat: arpt[ i ].geom.coordinates[ 1 ], lng: arpt[ i ].geom.coordinates[ 0 ] } ),
            icon: ic,
            title: arpt[ i ].city_name + '/' + arpt[ i ].arpt_name,
            map: map,
            clickable: true,
            id: arpt[ i ].arpt_ident,
            info: ctn[ i ]

        } );
        var cord=SetCoordinatebyGeom(arpt[i].geom)
        hasil = '<tr><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-var btn-secondary" id='+ arpt[i].arpt_ident + ' onclick="toarptinfo(this.id)">><i class="icon ni ni-view-grid"></i>Info</a><a class="btn btn-var btn-info" id='+ arpt[i].arpt_ident + ' onclick="showarptadc(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + arpt[i].icao + '</td><td>' + arpt[i].arpt_name + '</td><td>' + arpt[i].city_name + '</td><td>' + cord.WGSAIP[1] + '</td><td>' + cord.WGSAIP[0] + '</td></tr>'
        $("#arptlist").append(hasil);
        
        makeInfoWindow( map, infowindow, ctn[ i ], marker );
        arptmarkers.push( marker );
        arpt[ i ][ 'pointtype' ] = 'ARP';
        points.push( arpt[ i ] );
        
    }
}

function setMarkerNav( nav )
{
    
    var ctn=[];
    for ( let i = 0; i < nav.length; i++ ) {

        var ttl='';
        var ident = ''; nm = ''; def = ''; navid = ''; frq = FreqFormat(nav[i].freq,nav[i].type,'');
        if (nav[i].type=="11"){
            ident=nav[i].ils_ident;
            navid=nav[i].ils_id;
            nm=nav[i].ils_name;
            def="ILS";
            ttl=nav[i].ils_ident + ' (' + nav[i].ils_name +') ILS for RWY '  + nav[i].rwy_ident;
        }else{
            navid=nav[i].nav_id;
            ident=nav[i].nav_ident;
            nm=nav[i].nav_name;
            def=nav[i].definition;
            ttl = nav[ i ].nav_ident + ' (' + nav[ i ].nav_name + ') ' + nav[ i ].definition;
            if ( nav[ i ].type == "20" ) {
                frq = "NIL";
            }
        } 
        // console.log( frq );
        ctn[i]= navinfo(nav[i]);
        var ic = setIcon(navsymbol[nav[i].type].icon);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng({lat: nav[i].geom.coordinates[1],lng: nav[i].geom.coordinates[0]}),
            icon: ic,
            title: ttl,
            map: map,
            clickable:true,
            id:navid,
            info:ctn[i],
        });
        
        var cord=SetCoordinatebyGeom(nav[i].geom)
                hasil = '<tr class="nk-tb-item"><td class="tb-tnx-action"><div class="dropdown"><a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a><div class="dropdown-menu dropdown-menu-left dropdown-menu-sm"><ul class="link-list-plain"><a class="btn btn-var btn-secondary" id="'+ navid + '" onClick="showusage(this.id)"><i class="icon ni ni-view-grid"></i>Info</a><a class="btn btn-var btn-info" id="'+ navid + '" onClick="showdetail(this.id)"><i class="icon ni ni-map"></i>Show</a></ul></div></div></td><td>' + ident + '</td><td>' + def + '</td><td>' + nm + '</td><td>' + cord.WGSAIP[1] + '</td><td>' + cord.WGSAIP[0] + '</td><td>' + frq + '</td></tr>'
                $("#navlist").append(hasil);
       
        // ctn[i] = "<strong>"+"Mountain Name : "+data_vas[i].va_name+"</strong><br>";
        makeInfoWindow(map, infowindow, ctn[i], marker);
        pntmarkers.push( marker );
        nav[ i ][ 'pointtype' ] = 'NAV';
        points.push( nav[ i ] );
    }  
        return pntmarkers
}

const iconpath =
    pathpop() + "/images/marker/";
    const arpticons = {
    2: {
    icon : "M150.6645,120.5738H115.7417v29.2944l34.9228-18.8062Zm168.5068,0H284.249V131.062l34.9223,18.8062Zm61.8526,33.5512H346.101v10.24509l34.9229,18.80671ZM174.88522,125.3907,114.8735,157.7075a3.2182,3.2182,0,0,1-1.8516.9981L93.9243,168.9893a3.25669,3.25669,0,0,1-.6797.3662l-39.8843,21.478a3.23939,3.23939,0,0,1-2.7163,1.4771l-.0225-.002L0,219.56889l7.269,27.5078,172.0474-44.0928C177.5981,177.22708,176.02,150.542,174.88522,125.3907ZM88.812,154.125H53.8896v29.0518L88.812,164.37008Zm295.47949,38.1836-.0225.002a3.23778,3.23778,0,0,1-2.71578-1.4771l-39.88482-21.478a3.25728,3.25728,0,0,1-.6797-.3662l-19.0971-10.2837a3.2183,3.2183,0,0,1-1.8521-.9981l-60.0127-32.3173c-1.1347,25.1508-2.7138,51.83689-4.43058,77.5937l172.0478,44.0928,7.269-27.5078Zm-146.1411,149.9849c.17182-1.8652,17.1567-187.6358,17.1567-282.88179C255.30709,33.2232,237.48579,0,217.45549,0c-20.02928,0-37.8506,33.2232-37.8506,59.4117,0,95.25,16.9859,281.01659,17.1578,282.88179a3.24623,3.24623,0,0,1-1.5552,3.0762l-50.5894,30.5317v19.9805l72.042-18.2154a3.26355,3.26355,0,0,1,1.5913,0l72.0435,18.2154v-19.9805l-50.5894-30.5317A3.24689,3.24689,0,0,1,238.15039,342.29349Z",
        color: '#3a64af',
    },
    3: {
        icon: "M 451.00,1250.00 C 451.00,1250.00 451.00,1195.00 451.00,1195.00 451.00,1192.69 450.78,1189.37 452.02,1187.38 453.75,1184.61 469.20,1176.10 473.00,1173.80 473.00,1173.80 545.00,1130.20 545.00,1130.20 545.00,1130.20 597.00,1098.81 597.00,1098.81 603.35,1094.92 612.99,1091.20 613.89,1083.00 613.89,1083.00 611.17,1048.00 611.17,1048.00 611.17,1048.00 606.17,990.00 606.17,990.00 606.17,990.00 599.83,910.00 599.83,910.00 599.83,910.00 593.91,835.00 593.91,835.00 593.91,835.00 591.09,802.00 591.09,802.00 591.09,802.00 582.09,678.00 582.09,678.00 582.09,678.00 578.91,625.00 578.91,625.00 578.91,625.00 575.96,579.00 575.96,579.00 575.96,579.00 575.00,568.00 575.00,568.00 575.00,568.00 573.00,530.00 573.00,530.00 573.00,530.00 572.00,520.00 572.00,520.00 572.00,520.00 572.00,510.00 572.00,510.00 572.00,510.00 571.00,495.00 571.00,495.00 571.00,495.00 570.00,480.00 570.00,480.00 570.00,480.00 569.00,463.00 569.00,463.00 569.00,463.00 568.00,436.00 568.00,436.00 568.00,436.00 567.04,426.00 567.04,426.00 567.04,426.00 567.04,415.00 567.04,415.00 567.04,415.00 566.00,400.00 566.00,400.00 566.00,400.00 564.96,377.00 564.96,377.00 564.96,377.00 564.96,365.00 564.96,365.00 564.96,365.00 564.00,355.00 564.00,355.00 564.00,355.00 564.00,341.00 564.00,341.00 564.00,341.00 563.00,327.00 563.00,327.00 563.00,327.00 563.00,310.00 563.00,310.00 563.00,310.00 562.00,294.00 562.00,294.00 562.00,294.00 562.00,271.00 562.00,271.00 562.00,271.00 561.00,254.00 561.00,254.00 561.00,254.00 561.00,188.00 561.00,188.00 561.16,174.77 564.89,155.96 568.13,143.00 576.96,107.66 593.02,73.70 616.87,46.00 632.24,28.14 656.31,11.08 681.00,12.04 706.79,13.04 727.95,29.17 744.13,48.00 766.97,74.57 782.91,110.15 791.37,144.00 794.40,156.12 797.98,174.67 798.00,187.00 798.00,187.00 798.00,264.00 798.00,264.00 798.00,264.00 797.00,279.00 797.00,279.00 797.00,279.00 797.00,302.00 797.00,302.00 797.00,302.00 796.00,319.00 796.00,319.00 796.00,319.00 795.00,358.00 795.00,358.00 795.00,358.00 794.04,370.00 794.04,370.00 794.04,370.00 794.04,382.00 794.04,382.00 794.04,382.00 793.04,394.00 793.04,394.00 793.04,394.00 793.04,407.00 793.04,407.00 793.04,407.00 791.96,420.00 791.96,420.00 791.96,420.00 791.96,431.00 791.96,431.00 791.96,431.00 791.00,443.00 791.00,443.00 791.00,443.00 790.00,465.00 790.00,465.00 790.00,465.00 789.00,486.00 789.00,486.00 789.00,486.00 788.04,496.00 788.04,496.00 788.04,496.00 788.04,504.00 788.04,504.00 788.04,504.00 787.00,521.00 787.00,521.00 787.00,521.00 786.00,531.00 786.00,531.00 786.00,531.00 786.00,541.00 786.00,541.00 786.00,541.00 785.00,555.00 785.00,555.00 785.00,555.00 784.04,566.00 784.04,566.00 784.04,566.00 781.09,613.00 781.09,613.00 781.09,613.00 778.91,652.00 778.91,652.00 778.91,652.00 767.91,805.00 767.91,805.00 767.91,805.00 764.09,851.00 764.09,851.00 764.09,851.00 758.17,925.00 758.17,925.00 758.17,925.00 752.83,993.00 752.83,993.00 752.83,993.00 747.83,1051.00 747.83,1051.00 747.83,1051.00 745.23,1084.00 745.23,1084.00 746.49,1091.02 756.35,1095.41 762.00,1098.80 762.00,1098.80 811.00,1128.40 811.00,1128.40 811.00,1128.40 885.00,1173.00 885.00,1173.00 889.04,1175.42 905.15,1184.31 906.98,1187.33 908.22,1189.38 908.00,1192.65 908.00,1195.00 908.00,1195.00 908.00,1250.00 908.00,1250.00 908.00,1250.00 886.00,1245.37 886.00,1245.37 886.00,1245.37 849.00,1235.87 849.00,1235.87 849.00,1235.87 731.00,1206.13 731.00,1206.13 731.00,1206.13 697.00,1197.63 697.00,1197.63 697.00,1197.63 680.00,1193.62 680.00,1193.62 680.00,1193.62 662.00,1197.63 662.00,1197.63 662.00,1197.63 626.00,1206.63 626.00,1206.63 626.00,1206.63 565.00,1222.13 565.00,1222.13 565.00,1222.13 520.00,1233.42 520.00,1233.42 520.00,1233.42 477.00,1244.37 477.00,1244.37 477.00,1244.37 451.00,1250.00 451.00,1250.00 Z M 22.00,785.00 C 22.00,785.00 5.13,721.00 5.13,721.00 3.98,716.46 -1.19,702.05 1.04,698.39 2.28,696.34 10.47,692.26 13.00,690.86 13.00,690.86 46.00,673.22 46.00,673.22 46.00,673.22 154.00,615.04 154.00,615.04 154.00,615.04 162.00,611.84 162.00,611.84 162.00,611.84 171.00,605.80 171.00,605.80 171.00,605.80 222.00,578.31 222.00,578.31 222.00,578.31 424.00,469.69 424.00,469.69 424.00,469.69 502.00,427.69 502.00,427.69 502.00,427.69 547.00,404.00 547.00,404.00 547.00,404.00 548.00,421.00 548.00,421.00 548.00,421.00 549.00,442.00 549.00,442.00 549.00,442.00 551.00,485.00 551.00,485.00 551.00,485.00 551.96,495.00 551.96,495.00 551.96,495.00 551.96,503.00 551.96,503.00 551.96,503.00 553.00,521.00 553.00,521.00 553.00,521.00 554.00,531.00 554.00,531.00 554.00,531.00 554.00,541.00 554.00,541.00 554.00,541.00 555.00,554.00 555.00,554.00 555.00,554.00 555.91,565.00 555.91,565.00 555.91,565.00 557.09,590.00 557.09,590.00 557.09,590.00 560.04,635.00 560.04,635.00 560.04,635.00 561.00,648.00 561.00,648.00 561.00,648.00 517.00,659.12 517.00,659.12 517.00,659.12 435.00,680.12 435.00,680.12 435.00,680.12 326.00,707.88 326.00,707.88 326.00,707.88 174.00,746.87 174.00,746.87 174.00,746.87 119.00,761.12 119.00,761.12 119.00,761.12 82.00,770.42 82.00,770.42 82.00,770.42 45.00,779.88 45.00,779.88 45.00,779.88 22.00,785.00 22.00,785.00 Z M 812.00,404.00 C 812.00,404.00 833.00,414.58 833.00,414.58 833.00,414.58 861.00,429.69 861.00,429.69 861.00,429.69 965.00,485.69 965.00,485.69 965.00,485.69 1178.00,600.31 1178.00,600.31 1178.00,600.31 1190.91,607.33 1190.91,607.33 1190.91,607.33 1197.09,611.58 1197.09,611.58 1197.09,611.58 1205.00,614.90 1205.00,614.90 1205.00,614.90 1230.00,628.31 1230.00,628.31 1230.00,628.31 1308.00,670.31 1308.00,670.31 1308.00,670.31 1360.00,698.00 1360.00,698.00 1360.00,698.00 1351.15,732.00 1351.15,732.00 1351.15,732.00 1342.71,764.00 1342.71,764.00 1342.71,764.00 1338.84,779.00 1338.84,779.00 1338.84,779.00 1335.58,784.39 1335.58,784.39 1335.58,784.39 1324.00,782.42 1324.00,782.42 1324.00,782.42 1298.00,775.87 1298.00,775.87 1298.00,775.87 1195.00,749.37 1195.00,749.37 1195.00,749.37 1045.00,710.88 1045.00,710.88 1045.00,710.88 932.00,682.12 932.00,682.12 932.00,682.12 853.00,661.85 853.00,661.85 853.00,661.85 798.00,648.00 798.00,648.00 798.00,648.00 802.00,593.00 802.00,593.00 802.00,593.00 802.00,584.00 802.00,584.00 802.00,584.00 805.00,538.00 805.00,538.00 805.00,538.00 806.00,524.00 806.00,524.00 806.00,524.00 808.00,484.00 808.00,484.00 808.00,484.00 809.00,466.00 809.00,466.00 809.00,466.00 810.00,450.00 810.00,450.00 810.00,450.00 811.00,432.00 811.00,432.00 811.00,432.00 812.00,404.00 812.00,404.00 Z M 252.00,449.00 C 252.00,449.00 362.00,449.00 362.00,449.00 362.00,449.00 362.00,474.00 362.00,474.00 362.00,476.27 362.22,479.66 360.98,481.61 359.64,483.71 356.18,485.13 354.00,486.26 354.00,486.26 338.00,494.86 338.00,494.86 338.00,494.86 285.00,523.42 285.00,523.42 278.32,527.19 258.25,538.73 252.00,540.00 252.00,540.00 252.00,449.00 252.00,449.00 Z M 997.00,449.00 C 997.00,449.00 1107.00,449.00 1107.00,449.00 1107.00,449.00 1107.00,540.00 1107.00,540.00 1107.00,540.00 1076.00,524.31 1076.00,524.31 1076.00,524.31 1022.00,495.26 1022.00,495.26 1022.00,495.26 1006.00,486.74 1006.00,486.74 1006.00,486.74 997.99,481.39 997.99,481.39 997.99,481.39 997.00,474.00 997.00,474.00 997.00,474.00 997.00,449.00 997.00,449.00 Z",
        color: '#000000',
    },
    4: {
        icon: "M99.218,7.37893a19.09056,19.09056,0,0,1,4.31495-1.7317A54.72784,54.72784,0,0,0,88.80307,3.76583c-7.93913,0-14.20114,1.50088-16.911,2.62737,2.56739.66166,8.27123,1.41846,16.911,1.41846C92.96612,7.81166,96.44081,7.635,99.218,7.37893Zm1.01552,3.0559a16.07406,16.07406,0,0,0-7.85273,13.793V45.67393h32.16738V24.22782A16.076,16.076,0,0,0,116.695,10.43435c-.94886-.095-1.85573-.20076-2.6938-.32054a18.68234,18.68234,0,0,1-5.53709-1.39862,18.66019,18.66019,0,0,1-5.53612,1.39862C102.08965,10.23359,101.18259,10.33939,100.23349,10.43483Zm27.89-2.62317c8.64044,0,14.345-.7568,16.9127-1.41846-2.71035-1.12649-8.97284-2.62737-16.9127-2.62737a54.71444,54.71444,0,0,0-14.72791,1.88141,19.07511,19.07511,0,0,1,4.3149,1.73194C120.48784,7.63523,123.96114,7.81166,128.12346,7.81166ZM2.43365,48.81414V79.97356L90.607,87.424c.01582-.00212.02956-.00778.04514-.00919a1.54429,1.54429,0,0,1,.6361.06662l17.17582,1.4513,17.17228-1.45086a1.56467,1.56467,0,0,1,.64012-.06706c.01587.00141.02956.00707.04489.00919l88.17387-7.45042V48.81414ZM118.59908,170.88849a19.9307,19.9307,0,0,1-.6959,5.04244l35.39,4.27925V163.65633l-33.11527-8.34757ZM108.5964,92.07317q-.066.00566-.13231.00566c-.04393,0-.08809-.00189-.132-.00566L92.5674,90.74093l8.89486,79.908c.00518.05244.008.10532.008.15824,0,6.9253,4.80268,12.99245,6.9938,15.39948,2.19064-2.40582,6.99453-8.47321,6.99453-15.39948a1.54048,1.54048,0,0,1,.008-.15824l8.89466-79.908ZM96.75,155.301l-33.14386,8.35536v16.55384l35.41833-4.28278a19.94781,19.94781,0,0,1-.69469-5.0389Z",
        color: '#520c28',
    },
    5: {
        icon: "M 298.00,117.00 C 298.00,112.30 297.48,105.10 299.93,101.04 303.96,94.39 315.14,94.13 318.83,101.04 320.56,104.31 320.00,113.02 320.00,117.00 320.00,117.00 497.00,117.00 497.00,117.00 500.74,117.01 504.58,116.77 507.81,119.01 513.48,122.92 513.48,133.08 507.81,136.99 504.58,139.23 500.74,138.99 497.00,139.00 497.00,139.00 122.00,139.00 122.00,139.00 118.62,138.99 115.13,139.19 112.04,137.55 105.41,134.02 104.41,124.46 110.22,119.65 113.70,116.78 117.79,117.01 122.00,117.00 122.00,117.00 298.00,117.00 298.00,117.00 Z M 341.00,374.00 C 341.00,374.00 306.00,374.00 306.00,374.00 279.06,374.00 261.65,374.16 240.00,354.83 226.49,342.77 214.69,323.82 205.86,308.00 203.15,303.15 194.51,285.99 191.79,283.18 188.08,279.35 176.22,273.58 171.00,271.45 152.92,264.07 133.02,258.72 114.00,254.35 114.00,254.35 84.00,248.06 84.00,248.06 77.80,247.37 74.37,254.01 70.00,257.68 66.21,260.86 64.13,261.49 61.35,266.01 57.70,271.97 54.15,283.65 47.98,286.83 44.79,288.47 36.81,288.06 33.00,288.00 23.03,287.83 21.05,283.08 21.00,274.00 21.00,274.00 21.00,268.00 21.00,268.00 20.81,258.07 15.87,260.23 8.68,251.00 2.38,242.92 0.02,235.09 0.00,225.00 -0.01,217.30 0.03,213.26 3.31,206.00 4.78,202.75 9.00,196.73 9.25,194.00 9.47,191.67 7.78,187.33 7.00,185.00 7.00,185.00 0.76,166.00 0.76,166.00 -0.47,161.14 -0.69,155.27 3.34,151.63 5.94,149.29 13.47,146.38 17.00,144.77 20.14,143.34 25.75,140.70 29.00,140.21 42.02,138.27 44.53,149.56 49.25,159.00 49.25,159.00 62.63,184.62 62.63,184.62 62.63,184.62 72.00,192.09 72.00,192.09 81.02,200.49 77.10,201.98 88.00,202.00 88.00,202.00 232.00,202.00 232.00,202.00 242.66,201.98 243.62,198.38 251.00,191.00 251.00,191.00 271.00,171.00 271.00,171.00 276.98,165.02 280.05,160.16 289.00,160.00 289.00,160.00 318.00,160.00 318.00,160.00 321.80,160.02 324.60,159.91 327.67,162.57 331.58,165.95 340.35,185.21 343.25,191.00 344.77,194.05 347.36,200.29 350.21,201.98 352.15,203.12 360.75,203.23 364.00,203.75 374.14,205.40 384.21,207.33 394.00,210.52 429.07,221.95 463.38,243.93 487.42,272.00 496.08,282.11 508.67,298.81 511.48,312.00 512.15,315.14 512.03,319.71 512.00,323.00 511.64,353.52 475.16,372.75 448.00,373.00 448.00,373.00 448.00,394.00 448.00,394.00 460.45,394.00 472.22,392.11 483.00,385.29 490.25,380.71 496.87,368.75 506.90,374.57 516.27,380.01 511.19,392.02 504.96,397.71 493.16,408.50 472.97,415.98 457.00,416.00 457.00,416.00 249.00,416.00 249.00,416.00 245.59,415.99 242.03,416.26 239.06,414.26 233.10,410.25 232.81,401.38 238.34,396.85 242.05,393.81 246.50,394.01 251.00,394.00 251.00,394.00 341.00,394.00 341.00,394.00 341.00,394.00 341.00,374.00 341.00,374.00 Z M 358.00,225.00 C 356.66,229.08 358.41,232.02 359.58,236.00 359.58,236.00 366.28,258.00 366.28,258.00 369.88,269.98 374.41,291.51 383.04,299.91 390.46,307.13 399.01,308.98 409.00,309.00 409.00,309.00 486.00,309.00 486.00,309.00 469.05,278.80 430.30,248.37 398.00,235.81 389.27,232.42 381.12,229.99 372.00,227.89 372.00,227.89 358.00,225.00 358.00,225.00 Z M 426.00,374.00 C 426.00,374.00 363.00,374.00 363.00,374.00 363.00,374.00 363.00,394.00 363.00,394.00 363.00,394.00 426.00,394.00 426.00,394.00 426.00,394.00 426.00,374.00 426.00,374.00 Z",
        color:'#000000',     
    },
    6: {
        icon: "M99.218,7.37893a19.09056,19.09056,0,0,1,4.31495-1.7317A54.72784,54.72784,0,0,0,88.80307,3.76583c-7.93913,0-14.20114,1.50088-16.911,2.62737,2.56739.66166,8.27123,1.41846,16.911,1.41846C92.96612,7.81166,96.44081,7.635,99.218,7.37893Zm1.01552,3.0559a16.07406,16.07406,0,0,0-7.85273,13.793V45.67393h32.16738V24.22782A16.076,16.076,0,0,0,116.695,10.43435c-.94886-.095-1.85573-.20076-2.6938-.32054a18.68234,18.68234,0,0,1-5.53709-1.39862,18.66019,18.66019,0,0,1-5.53612,1.39862C102.08965,10.23359,101.18259,10.33939,100.23349,10.43483Zm27.89-2.62317c8.64044,0,14.345-.7568,16.9127-1.41846-2.71035-1.12649-8.97284-2.62737-16.9127-2.62737a54.71444,54.71444,0,0,0-14.72791,1.88141,19.07511,19.07511,0,0,1,4.3149,1.73194C120.48784,7.63523,123.96114,7.81166,128.12346,7.81166ZM2.43365,48.81414V79.97356L90.607,87.424c.01582-.00212.02956-.00778.04514-.00919a1.54429,1.54429,0,0,1,.6361.06662l17.17582,1.4513,17.17228-1.45086a1.56467,1.56467,0,0,1,.64012-.06706c.01587.00141.02956.00707.04489.00919l88.17387-7.45042V48.81414ZM118.59908,170.88849a19.9307,19.9307,0,0,1-.6959,5.04244l35.39,4.27925V163.65633l-33.11527-8.34757ZM108.5964,92.07317q-.066.00566-.13231.00566c-.04393,0-.08809-.00189-.132-.00566L92.5674,90.74093l8.89486,79.908c.00518.05244.008.10532.008.15824,0,6.9253,4.80268,12.99245,6.9938,15.39948,2.19064-2.40582,6.99453-8.47321,6.99453-15.39948a1.54048,1.54048,0,0,1,.008-.15824l8.89466-79.908ZM96.75,155.301l-33.14386,8.35536v16.55384l35.41833-4.28278a19.94781,19.94781,0,0,1-.69469-5.0389Z",
        color: '#520c28',
    },
    7: {
        icon: "M99.218,7.37893a19.09056,19.09056,0,0,1,4.31495-1.7317A54.72784,54.72784,0,0,0,88.80307,3.76583c-7.93913,0-14.20114,1.50088-16.911,2.62737,2.56739.66166,8.27123,1.41846,16.911,1.41846C92.96612,7.81166,96.44081,7.635,99.218,7.37893Zm1.01552,3.0559a16.07406,16.07406,0,0,0-7.85273,13.793V45.67393h32.16738V24.22782A16.076,16.076,0,0,0,116.695,10.43435c-.94886-.095-1.85573-.20076-2.6938-.32054a18.68234,18.68234,0,0,1-5.53709-1.39862,18.66019,18.66019,0,0,1-5.53612,1.39862C102.08965,10.23359,101.18259,10.33939,100.23349,10.43483Zm27.89-2.62317c8.64044,0,14.345-.7568,16.9127-1.41846-2.71035-1.12649-8.97284-2.62737-16.9127-2.62737a54.71444,54.71444,0,0,0-14.72791,1.88141,19.07511,19.07511,0,0,1,4.3149,1.73194C120.48784,7.63523,123.96114,7.81166,128.12346,7.81166ZM2.43365,48.81414V79.97356L90.607,87.424c.01582-.00212.02956-.00778.04514-.00919a1.54429,1.54429,0,0,1,.6361.06662l17.17582,1.4513,17.17228-1.45086a1.56467,1.56467,0,0,1,.64012-.06706c.01587.00141.02956.00707.04489.00919l88.17387-7.45042V48.81414ZM118.59908,170.88849a19.9307,19.9307,0,0,1-.6959,5.04244l35.39,4.27925V163.65633l-33.11527-8.34757ZM108.5964,92.07317q-.066.00566-.13231.00566c-.04393,0-.08809-.00189-.132-.00566L92.5674,90.74093l8.89486,79.908c.00518.05244.008.10532.008.15824,0,6.9253,4.80268,12.99245,6.9938,15.39948,2.19064-2.40582,6.99453-8.47321,6.99453-15.39948a1.54048,1.54048,0,0,1,.008-.15824l8.89466-79.908ZM96.75,155.301l-33.14386,8.35536v16.55384l35.41833-4.28278a19.94781,19.94781,0,0,1-.69469-5.0389Z",
        color: '#520c28',
    }

}

    const volsymbol = {
        1: {
            icon: iconpath + "va_green.png",
        },
        2: {
            icon: iconpath + "va_yellow.png",
        },
        3: {
            icon: iconpath + "va_orange.png",
        },
        4: {
            icon: iconpath + "va_red.png",
        },
    };

const wptymbol = {
    "1": {
        icon: iconpath + "CRP.svg",
    },
    "2": {
        icon: iconpath + "NCRP.svg",
    },
    "3": {
        icon: iconpath + "M_CRP.jpg",
    },
    "4": {
        icon: iconpath + "M_NCRP.jpg",
    },
    "5": {
        icon: iconpath + "RNAVC.svg",
    },
    "6": {
        icon: iconpath + "NCRP.svg",
    },
    "7": {
        icon: iconpath + "NCRP.svg",
    },
    "8": {
        icon: iconpath + "NCRP.svg",
    },
    
};
const navsymbol = {
    "1": {
        icon: iconpath + "VOR.svg",
    },
    "2": {
        icon: iconpath + "VORTAC.svg",
    },
    "3": {
        icon: iconpath + "TACAN.svg",
    },
    "4": {
        icon: iconpath + "VORDME.svg",
    },
    "5": {
        icon: iconpath + "NDB.svg",
    },
    "7": {
        icon: iconpath + "NDB.svg",
    },
    "10": {
        icon: iconpath + "NDB.svg",
    },
    "11": {
        icon: iconpath + "ILS.svg",
    },
    "20": {
        icon: iconpath + "ILS.svg",
    },
    "21": {
        icon: iconpath + "ILS.svg",
    },
};
function makeInfoWindow(map, infowindow, info, marker) {
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(info);
    // infowindow.position(position);
    infowindow.open(map, marker);
  });
}
function setIcon( uri )
{
    var pix = map.getZoom();
    var sz = 2 * pix; szs = sz / 2;
    // if ( map.getZoom() > 5 ) {
    //     sz = 12; szs = 6;
    // };
    // console.log('sz',sz,pix)
    var image = {
            url: uri, 
            size: new google.maps.Size(sz,sz), 
            origin: new google.maps.Point(0, 0), 
            anchor: new google.maps.Point(szs,szs),
            scaledSize: new google.maps.Size(sz,sz),
        };
    return image;
}

function arptinfo(va){
    //   console.dir(va);
      var rr='';dim='';surf='';pcn='';
      va.runways.forEach(rw=>{
          
        if (rr==''){
            rr = rw.rwy_ident;
            dim =rw.length + ' x ' + rw.width;
            surf =rw.definition;
            pcn =rw.pcn;
        }else{
            rr += ' / '+ rw.rwy_ident;
            dim +=' / '+rw.length + ' x ' + rw.width;
            surf +=' / '+rw.definition;
            pcn +=' / '+rw.pcn;
        }
      })
    var iata = va.iata;
   
    // console.log(pcn.length)
        if (va.iata=='' || va.iata==''){
            iata='NIL';
        }
        var rww='<tr><td align="left" style="font-weight:bold;"><b>Runway</b></td><td>:</td>'+
                '<td style="font-weight:bold">'+ rr +'</td></tr>'+
                '<tr><td align="left" style="font-weight:bold;"><b>varension </b></td><td>:</td><td>'+dim  +'m</td></tr>'+
                '<tr><td align="left" style="font-weight:bold;"><b>Surface</b></td><td>:</td><td>'+surf +'</td></tr>'+
                '<tr><td align="left" style="font-weight:bold;"><b>Strength</b></td><td>:</td><td>'+ pcn +'</td></tr>';
        var cord =getcord(va.geom.coordinates[0],va.geom.coordinates[1])
        let btncdm = ''; btninfo = '';
                btncdm='<button class="btn btn-sm btn-primary" id="'+ va.arpt_ident + '" onClick="toarptinfo(this.id)">Info</button> ';
                btninfo='<button class="btn  btn-sm btn-success" id="'+ va.arpt_ident + '" onClick="showarptadc(this.id)">Show</button> ';
                // btnforecast='<button class="btn  btn-sm btn-warning" onclick="">Forecast</button> ';

        return '<div width="400px !important; padding:20px 20px 20px 20px;"><b style="font-size:20px;">' + va.arpt_name  + '</b><br><br><table width="400px !important;">'+
                    '<tr>'+
                    '<td align="left" width="120" style="font-weight:bold;"><b>ICAO/IATA</b></td>'+
                    '<td>:</td>'+
                    '<td style="font-weight:bold">'+ va.icao + ' / ' + iata + '</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Location</b></td>'+
                    '<td>:</td>' +
                    '<td>'+ cord  +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>City</b></td>'+
                    '<td>:</td>'+
                    '<td>'+va.city_name +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Elevation</b></td>'+
                    '<td>:</td>'+
                    '<td>'+ va.elev +' ft</td>'+
                '</tr>'+ rww +
            '</table>'+
            '<br><br>' + btncdm + btninfo;
  }
function wptinfo(va){
    var cord=SetCoordinatebyGeom(va.geom)
    var ident='';nm='';
            ident=va.wpt_name;
            nm=va.wpt_name;
    let btncdm='';
    btncdm='<button class="btn btn-sm btn-primary"  id="'+ va.wpt_id + '" onClick="showusagewpt(this.id)">Info</button> ';

    return '<div width="250px !important; padding:30px 30px 30px 30px;"><b style="font-size:20px;">' + ident  + '</b><br><br><table width="250px !important;">'+
                    '<tr>'+
                    '<td align="left" width="60" style="font-weight:bold;"><b>Name</b></td>'+
                    '<td>:</td>'+
                    '<td style="font-weight:bold">'+ va.desc_name + '</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Location</b></td>'+
                    '<td>:</td>' +
                    '<td>'+ cord.WGSAIP[1] + ' ' + cord.WGSAIP[0] +'</td>'+
                '</tr>'+
            '</table>'+
            '<br>' + btncdm
  }
 function navinfo(va){
    var cord=SetCoordinatebyGeom(va.geom)
     var ident = ''; nm = ''; frq = FreqFormat( va.freq, va.type, '' ); navid = '';
        if (va.type=="11"){
            ident=va.ils_ident + ' ILS for RWY ' + va.rwy_ident;
            nm = va.ils_name;
            navid = va.ils_id;
        } else {
            navid = va.nav_id;
            if (va.definition=='L'){
                ident=va.nav_ident + ' Locator';
            }else{
                ident=va.nav_ident + ' ' + va.definition;
            }
            nm = va.nav_name;
            if ( va.type == "20" ) {
                frq = "NIL";
            }
            if ( va.type == "4" ) {
                frq += "/" + va.channel;
            }
        }
    let btncdm='';
    btncdm='<button class="btn btn-sm btn-primary" id="'+ navid + '" onClick="showusage(this.id)">Info</button> ';
                // btndetail='<button class="btn  btn-sm btn-success" onclick="">Info</button> ';
                // btnforecast='<button class="btn  btn-sm btn-warning" onclick="">Forecast</button> ';

    return '<div width="250px !important; padding:30px 30px 30px 30px;"><b style="font-size:20px;">' + ident  + '</b><br><br><table width="250px !important;">'+
                    '<tr>'+
                    '<td align="left" width="70" style="font-weight:bold;"><b>Name</b></td>'+
                    '<td>:</td>'+
                    '<td style="font-weight:bold">'+ nm + '</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Location</b></td>'+
                    '<td>:</td>' +
                    '<td>'+ cord.WGSAIP[1] + ' ' + cord.WGSAIP[0] +'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td align="left" style="font-weight:bold;"><b>Frequency</b></td>'+
                    '<td>:</td>'+
                    '<td>'+ frq +'</td>'+
                '</tr>'+
            '</table>'+
            '<br>' + btncdm;
  }

function Airspacefreq(freq,unit,real=null){
    var f = '';

        if ( unit == 'V' ) {
            if ( real == null ) {
                f = freq / 1000000 + ' MHz';
            } else {
                f = freq / 1000000;
            }
        } else {
            if ( real == null ) {
                f = freq / 1000 + ' kHz';
            } else {
                f = freq / 1000;
            }
        }
   
                
    return f
}
 function FreqFormat( freq, navtype, usefor ) {
//http://numeraljs.com/
//link di atas utk format number ke string
        // console.log( freq, navtype, usefor );
        if ( freq == '' ) {
            this.rslt = 'NIL';
        } else if ( navtype == '3' || navtype == '9' ) {
            this.rslt = freq
        } else {
            this.frq = parseFloat( freq.replace( /M|K|Z|z|' '/g, '' ) );
            switch ( navtype ) {
                case '5':
                case '7':
                case '10':
                    if ( this.frq >= 100000 ) {
                        this.rslt = this.frq / 1000;
                    } else {
                        this.rslt = this.frq
                    }
                    if ( usefor == 'DATA' ) {
                        this.rslt =numeral(this.rslt).format('0.00')// format( "####.00", this.rslt )
                    } else {
                        this.rslt =numeral(this.rslt).format('0') + ' kHz' // format( "####.###", this.rslt) + 'kHz'
                    }
                    break;
                default:
                    if ( this.frq >= 1000000 ) {
                        if ( usefor == 'DATA' ) {
                            this.rslt = numeral(this.frq / 10000).format('0.00') //format( "####.00", this.frq / 10000 )
                        } else {
                            this.rslt = numeral(this.frq / 10000).format('0.0[00]') + ' MHz' //format( "####.0##", this.frq / 10000 ) + 'MHz'
                        }
                    } else if ( this.frq < 1000000 && this.frq > 100000 ) {
                        if ( usefor == 'DATA' ) {
                            this.rslt = numeral(this.frq / 1000).format('0.00') //format( "####.00", this.frq / 1000 )
                        } else {
                            this.rslt =numeral(this.frq / 1000).format('0.0[00]') + ' MHz' // format( "####.###", this.frq / 1000 ) + 'MHz'
                        }
                    } else {
                        if ( usefor == 'DATA' ) {
                            this.rslt = numeral(this.frq).format('0.00') //format( "###.00", this.frq )
                        } else {
                            this.rslt = numeral(this.frq).format('0.0[00]')+ ' MHz' //format( "###.0##", this.frq ) + 'MHz'
                        }
                    }
                    break;
            }
        }
        // console.log(this.rslt);
        return this.rslt;
    }

const TranslatePoint = function(coordinates) {
    return ['lat:' + coordinates[1], 'lng:' + coordinates[0]]
}

const TranslateLine = function(coordinates) {
    var results = []
    // console.log(coordinates)

    coordinates.forEach(coor => {
        results.push({lat: + coor[1],lng: + coor[0]})
    })
    return results
}

const TranslatePoly = function(coordinates) {
    var results = []
    // console.log(coordinates)

    coordinates.forEach(coor => {
        results.push({lat: + coor[1],lng: + coor[0]})
    })
    return results
}

function getcord(x,y){
    let cord = SetCoordinatebyDecimal(x,y);
    // $('#latitude').val(cord.Database[1]);
    //                 $('#longitude').val(cord.Database[0]);
    return cord.Database[1] + ' ' + cord.Database[0]
}
function arptstyle(feature) {
    if (feature=='strip'){
        return {
            weight: 2,
            opacity: 1,
            color: getColor(feature),
            dashArray: '3 5',
            fillOpacity: 0.5
        };
        
    }else{
        return {
            fillColor: getColor(feature),
            weight: 2,
            opacity: 1,
            color: getColor(feature),
            fillOpacity: 0.7
        };

    }
}

function getColor(d) {
	// console.log(d)
		return d == 'FIR' ? '#636363' :
			d == 'UTA'  ? '#efedf5' :
			d == 'FSS'  ? '#fff7bc' :
			d == 'CTA'  ? '#7fcdbb' :
			d == 'MTCA'  ? '#3182bd' :
			d == 'TMA'   ? '#bcbddc' :
			d == 'CTR'   ? '#2ca25f' :
			d == 'ATZ'   ? '#756bb1' :
			d == 'AFIZ'   ? '#756bb1' :
			d == 'D'   ? '#e20e0e' :
			d == 'P'   ? '#e20e0e' :
			d == 'R'   ? '#e20e0e' :
			d == 'T'   ? '#bdbdbd' :
			d == 'M'   ? '#b30d0d' :
			d == 'W'   ? '#fec44f' :
			d == 'A'   ? '#c994c7' :
			d == 'strip'   ? '#f0f0f0' :
			d == 'rwymarking'   ? 'white' :
			d == 'rwy'   ? 'black' :
			d == 'roads'   ? '#de2d26' :
			d == 'building'   ? '#3182bd' :
			d == 'apron'   ? '#636363' :
			d == 'twy'   ? '#bdbdbd' :
			d == 'taxilane'   ? '#fff7bc' :
			d == 'centerline'   ? '#f0f0f0' :
						'#f0f0f0';

}


function reverse(cord){
	var rslt=[]
	// console.log('ASLI ' ,cord)
	
	for (let i = 0;i < cord.length;i++){
		var xx=[]
		// console.log('BEFORE ' ,cord[i])
		for (let x = 0;x < cord[i].length;x++){
			// console.log(cord[i][x])
		// 	// if (x==0){
			 	xx = [cord[i][x][1],cord[i][x][0]]
		// 	// }else{
		// 	// 	xx += [cord[i][x][1],cord[i][x][0]]
		// 	// }
		// console.log(xx)
			rslt.push(xx)

		}
	}
		


	
	// console.log('HASIL ' ,rslt)
return [rslt]
}
function SetCoordinatebyGeom(geom) {
   return Coordinates= SetCoordinatebyDecimal(geom.coordinates[0],geom.coordinates[1]);
}
function checkisicontain( content,blank=false ) {
    // console.log( 'checkisicontain ', content,typeof content,content.trim())
    if ( content == '' || content == null || typeof content == 'undefined' ) {
        if ( blank == true ) {
            return '';
        } else {
            return 'NIL';
        }
    } else {
        return content;
    }
}
function getvolcanocolor(status){
    let color=''
    switch(status){
        case "4":
            color='<strong style="color:#CC0505; font-weight:bolder;">RED</strong>'
            break;
        case "3":
            color='<strong style="color:#FF9100; font-weight:bolder;">ORANGE</strong>'
            break;
        case "2":
            color='<strong style="color:#e7d107; font-weight:bolder;">YELLOW</strong>'
            break;
        case "1":
            color='<strong style="color:#179638; font-weight:bolder;">GREEN</strong>'
            break;
    }
    return color
}
function SetCoordinatebyDecimal(X, Y) {
    // console.log('ToWgs', X, Y );
    var lathasil = ToWgs(Y, "LAT");
    var lonhasil = ToWgs(X, "LON");

        function ToWgs(cor, LatOrLon) {
            var secInHr = 3600;
            var secInMn = 60;
            var corInSec;
            var tag = LatOrLon.toUpperCase();
            var deg;
            var Min;
            var pMin;
            var pMinFir;
            var psecTMA;
            var sec;
            var dSec;
            var dSec10;
            var dSecAIP;
            var Header;
            var hasil;

            if (tag == "LAT") {
                platDecimal = cor;
                if (platDecimal == 0) {
                    Header = "";
                } else if (platDecimal > 0) {
                    Header = "N";
                } else if (platDecimal < 0) {
                    Header = "S";
                }
            } else if (tag == "LON") {
                pLonDecimal = cor
                if (pLonDecimal == 0) {
                    Header = "";
                } else if (pLonDecimal > 0) {
                    Header = "E";
                } else if (pLonDecimal < 0) {
                    Header = "W";
                }
            }

        // console.log('TAG ' + tag + ' Cord ' + cor)
        // function Mmod(theValue, AgValue) {
        //     return theValue - AgValue * parseInt(theValue / AgValue);
        // }
            var corfixed = Math.abs( cor );
            // console.log( 'corfixed', corfixed );
            deg =  parseInt( corfixed);
            // console.log( 'deg', deg );
            var mindec = ( corfixed - deg ) * secInMn;
            // console.log( 'mindec', corfixed - deg, mindec);
            Min = parseInt( mindec );
            // console.log( 'Min', Min );
            pMinFir = Math.round( mindec, 0 );
            // console.log( 'pMinFir', pMinFir );
            pMin =mindec.toFixed(1);
            var secdec = ( ( mindec - Min ) * secInMn );

            sec = parseInt( secdec );//secdec.toFixed( 2 );
            // sec =secdec.toFixed( 2 );
            // console.log( 'secdec', mindec , Min, mindec - Min,secdec ,secdec < 0);
            // console.log( 'sec',sec,parseInt(secdec),secdec.toFixed( 2 ) );
            // dSec10 = secdec.toFixed( 1 );
            dSec10 = (secdec-sec).toFixed( 1 )*10;
            // console.log( 'dSec10',dSec10 );
            psecTMA =secdec.toFixed(0);
            // console.log( 'psecTMA',psecTMA );
            // CSec = Math.round( ( secdec - sec ) * secInMn, 1 );
            dSec =  Math.round( ( secdec - sec ) * 100 );
            // console.log( 'dSec', secdec - sec, ( secdec - sec ) * secInMn );
            // console.log( 'cor', cor, 'deg', deg,'mindec',mindec, 'Min', Min, 'secdec', secdec, 'pMinFir', pMinFir, 'pMin', pMin, 'psecTMA', psecTMA,mindec - Min )
            // console.log( 'cor', cor,'corfixed',corfixed,'secdec',secdec, 'sec', sec, 'dSec', dSec, 'dSec10', dSec10 );
            // if ( CSec == 60 ) {
            //     sec += 1;
            //     CSec = 0;
            // }
            // psecTMA= CSec;


            
            // console.log(sec,CSec );
          
            // dSec10 =Math.round(dSec / 10,1);
            if ( dSec >= 100 ) {
                sec += 1;
                dSec = 0;
            }
            if ( dSec < 0 ) {
                dSec = 0;
                sec -= 1;
            }
            
            if ( sec == 60 ) {
                Min += 1;
                sec = 0;
            }
            // if ( CSec < 0 ) {
            //     CSec = 0;
            // }
            
            // console.log( 'cor', cor, 'deg', deg,'mindec',mindec, 'Min', Min, 'secdec', secdec, 'pMinFir', pMinFir, 'pMin', pMin, 'psecTMA', psecTMA )
            // console.log( 'cor', cor, 'sec', sec,'CSec',CSec, 'dSec', dSec, 'dSec10', dSec10 );

            if (tag == "LAT") {
                if (deg == 0 && Min == 0) {
                    pLatGrid = "0°";
                } else if (Min == 0) {
                    pLatGrid = Header + Format(deg, 2) + "°00'";
                } else {
                    pLatGrid = Header + Format(deg, 2) + "°" + Format(Min, 2) + "'";
                }
                var LatDecimal = platDecimal;
                var   Latitude = Format(deg, 2) + Format(Min, 2) + Format(sec, 2) + Format(dSec, 2) + Header;
                var   LatforFIR = Format(deg, 2) + Format(pMinFir, 2) + Header;
                var   LatforTMA = Format(deg, 2) + Format(Min, 2) + Format(psecTMA, 2) + Header;
                // var  pLatRwyAnal = Format(deg, 2) + Format(Min, 2) + Format(sec, 2) + Format(dSec, 2) + Header;
                var  LatWgsAIP = Format(deg, 2) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec, 2) + "''" + Header;
                // var pLatGridGND = Format(deg, 2) + Format(Min, 2) + Format(sec, 2) + ".00" + Header;
                var  LatWgsIAC = Format(deg, 2) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec10, "0") + "''" + Header;
                var  LatitudeWgs = Format(deg, 2) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec, 2) + "''" + Header;
                // var   LatforPrint = Format(deg, 2) + "°" + Format(Min, 2) + "'" + Format(CSec, 2) + "''" + Header;
                var   LatforADText = Format(deg, 2) + Format(Min, 2) + Format(psecTMA, 2) + Header;
                var   LatWgsSIDSTAR = Format(deg, 2) + "°" + Format(Min, 2) + "'" + Format(psecTMA, 2) + "''" + Header;
                // var  LatPrintTextAIP = Format(deg, 2) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec10, "0") + "''" + Header;
                hasil = [LatDecimal, Latitude, LatitudeWgs, LatforADText, LatWgsAIP, LatWgsIAC, LatWgsSIDSTAR, LatforFIR, LatforTMA];
            } else if (tag == "LON") {
                if (deg == 0 && Min == 0) {
                    pLonGrid = "0°";
                    pLonIso = "0°";
                }else if (Min == 0){
                    pLonGrid = Header + Format(deg, 3) + "°00'";
                    pLonIso = deg + "°" + Header;
                } else {
                    pLonGrid = Header + Format(deg, 3) + "°" + Format(Min, 2) + "'";
                    pLonIso = deg + "°" + Format(Min, 3) + "'" + Header;
                }
                var LonDecimal = pLonDecimal;
                var Longitude = Format(deg, 3) + Format(Min, 2) + Format(sec, 2) + Format(dSec, 2) + Header;
                var LonforFIR = Format(deg, 3) + Format(pMinFir, 2) + Header;
                var LonforTMA = Format(deg, 3) + Format(Min, 2) + Format(psecTMA, 2) + Header;
                var LonWgsIAC = Format(deg, 3) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec10, "0") + "''" + Header;
                // var pLonRwyAnal = Format(deg, 3) + Format(Min, 2) + Format(sec, 2) + Format(dSec, 2) + Header;
                var LonWgsAIP = Format(deg, 3) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec, 2) + "''" + Header;
                // var pLonGridGND = Format(deg, 3) + Format(Min, 2) + Format(sec, 2) + ".00" + Header;
                var LongitudeWgs = Format(deg, 3) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec, 2) + "''" + Header;
                // var pLonPrint = Format(deg, 3) + "°" + Format(Min, 2) + "'" + Format(CSec, 2) + "''" + Header;
                var LonforADText = Format(deg, 3) + Format(Min, 2) + Format(psecTMA, 2) + Header;
                var LonWgsSIDSTAR = Format(deg, 3) + "°" + Format(Min, 2) + "'" + Format(psecTMA, 2) + "''" + Header;
                // var LonPrintTextAIP = Format(deg, 3) + "°" + Format(Min, 2) + "'" + Format(sec, 2) + "." + Format(dSec10, "0") + "''" + Header;
                hasil = [LonDecimal, Longitude, LongitudeWgs, LonforADText, LonWgsAIP, LonWgsIAC, LonWgsSIDSTAR, LonforFIR, LonforTMA];
            }
            return hasil;
        //console.log('Coordinate LAT ' + Lat + ' LON ' + Lon)
        }

//         // ppoint.SetPoint(platDecimal, pLonDecimal)

   
    function Format( num, targetLength )
    {
            
        return num.toString().padStart(targetLength, 0);
    }


        function Mmod(theValue, AgValue) {
            return theValue - AgValue * parseInt(theValue / AgValue);
        }

     function CordObjec(lonhasil, lathasil) {
            var hasil = {
                'Decimal': [lonhasil[0], lathasil[0]], 'Database': [lonhasil[1], lathasil[1]],
                'WGS': [lonhasil[2], lathasil[2]], 'WGSAIP': [lonhasil[4], lathasil[4]],
                'ADText': [lonhasil[3], lathasil[3]], 'WGSIAC': [lonhasil[5], lathasil[5]], 'WGSSIDSTAR': [lonhasil[6], lathasil[6]],
                'FIR': [lonhasil[7], lathasil[7]], 'NonFIR': [lonhasil[8], lathasil[8]],'Point':'POINT(' + lonhasil[0] + ' ' +  lathasil[0] + ')'
            };
            return hasil;
     }

    return Coordinates = CordObjec(lonhasil, lathasil);
}

function SetCoordinate( latstring, lonstring )
{
  
    // latstring = '05574018S';
    // lonstring = '107020753E';



    // platDecimal= ToDecimal( latstring );
    // plonDecimal = ToDecimal( lonstring );
    

   return SetCoordinatebyDecimal(ToDecimal( lonstring ), ToDecimal( latstring ));
}
function SetScale(ScaleChart )
{
    return ScaleChart / 111120000;
}

function Getpapersize(ScaleChart, PaperSize)
{
    var myLenght=0; scCal = 0;
    scCal = SetScale( ScaleChart );
    myLenght = PaperSize * scCal;
    return myLenght;
    
}
function ToDecimal( corvalue )
{
        // console.log(corvalue)
        var head;
        var mark;
        var deg;
        var Min;
        var sec;
        
        var reslt;
        head = corvalue.substr(corvalue.length-1, 1).toUpperCase();
        if (head == "E" || head == "N") {
            mark = 1;
        } else if (head == "W" || head == "S") {
            mark = -1;
        }
        // console.log( corvalue,head,mark,corvalue.length );
        if (head == "E" || head == "W") {
            deg = Number(corvalue.substr(0, 3));
            Min = Number( corvalue.substr( 3, 2 ) );
            if (corvalue.substr(5, 1) == ".") {
                sec = Number("0." + corvalue.substr(6, (corvalue.length - 7))) * 60;
            } else {
                if (corvalue.length < 10) {
                    sec = Number(corvalue.substr(5, 2));
                } else {
                    corvalue = corvalue.replace(".", "");
                    sec = parseFloat(corvalue.substr(5, 2) + "." + corvalue.substr(7, 2));
                }
            }
            // console.log( deg, Min, sec )
            afS =  sec / 60;

        } else if (head == "N" || head == "S") {
            deg = Number(corvalue.substr(0, 2));
            Min = Number(corvalue.substr(2, 2));
            if (corvalue.substr(4, 1) == ".") {
                sec = Number("0." + corvalue.substr(5, (corvalue.length - 5))) * 60;
            } else {
                if (corvalue.length < 9) {
                    sec = Number(corvalue.substr(4, 2));
                } else {
                    corvalue = corvalue.replace(".", "");
                    sec = parseFloat(corvalue.substr(4, 2) + "." + corvalue.substr(6, 2));
                }
            }
            // console.log( deg, Min, sec )
            afS =  sec / 60;
        }
   
    var minsec = ( Min + afS ) / 60;
    // console.log( 'minsec ' , afS,minsec );
        // afS = (Number(sec)/60) + Min;
    // reslt = ( deg + ( ( ( afS / 60 ) + Min ) / 60 ) ) * mark;
    reslt = (deg + minsec) * mark;

    // console.log( 'HASIL ' , reslt );
    return reslt;
    }
function SetCoordinatePoint( cord )
{
    var ccc = cord.replace( /POINT|[,\/#!$%\^&\*;:{}=\_`~()]/g, '' );
    // console.log( ccc,'hasil replace' );
    var ccr=ccc.split(' ')
    // console.log('lathasil ' + lathasil + ' lonhasil ' + lonhasil)
    return SetCoordinatebyDecimal(Number(ccr[0]), Number(ccr[1]));
}
function SetCoordinateforecast( cord )
{
    // console.log('cord',cord)
    var ccr=cord.split(' ')
    var lathasil=   ToDecimalforecast(ccr[0]);
    var lonhasil = ToDecimalforecast(ccr[1]);
   
    // console.log('lathasil ' + lathasil + ' lonhasil ' + lonhasil)
   return SetCoordinatebyDecimal(lonhasil, lathasil);
}
function ToDecimalforecast( corvalue )
{
    // console.log('corvalue',corvalue)
        var head;
        var mark;
        var deg;
        var Min;
        var reslt;
    head = corvalue.substr( 0, 1 ).toUpperCase();
    // console.log( head );
        if (head == "E" || head == "N") {
            mark = 1;
        } else if (head == "W" || head == "S") {
            mark = -1;
        }

        if (head == "E" || head == "W") {
            deg = Number(corvalue.substr(1, 3));
            Min = Number(corvalue.substr(4, 2));

        } else if (head == "N" || head == "S") {
            deg = Number(corvalue.substr(1, 2));
            Min = Number(corvalue.substr(3, 2));

        }
        // console.log( 'deg', deg ,Min,corvalue.substr(1, 3));
    reslt = ( deg + ( Min / 60 ) ) * mark;
    // console.log( 'resltresltreslt', reslt ,deg,Min);
    return reslt;
}
function SetWgs( deg, min, sec, head )
{
    var mark;
    if (head == "E" || head == "N") {
        mark = 1;
    } else if (head == "W" || head == "S") {
        mark = -1;
    }
    var d = Number( deg );m = Number( min );s = Number( sec )

    // console.log( deg, min, sec, head );
    reslt = (d + (((s / 60) + m) / 60)) * mark;
    
    // console.log('platDecimal ' + platDecimal + ' pLonDecimal ' + pLonDecimal)
    return reslt;
}

function ConvertTime(data){
    // console.log(data);
    let hour, hours, minute, minutes, second, seconds, day,days;
    hours = Math.floor(data / 3600);
    minutes = Math.floor((data / 60) % 60);
    seconds = data % 60;
    if (hours > 24 || hours < -24 ){
        day= Math.floor(hours / 24) ;
        hour = hours % 24
        return day + ' days ' + hour +  ' hour ' + minutes + ' minutes ' + seconds + ' seconds';
    }else{
        return hours +  ' hour ' + minutes + ' minutes ' + seconds + ' seconds';
    }
    
}
function isidata( id, status )
{
    //untuk merubah waran text kalau status = request (R)
    // console.log('isidata',id,status)
    var clr='black';
    if (status=='N' || status == 'R'){
        clr='red';
    }
    document.getElementById(id).style.color = clr;
}

function searchairport(url,placeholder,valmin,element) {
    $('.select2').select2({
        placeholder: placeholder,
        minimumInputLength: valmin,
        ajax: {
            url: url,
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
       
    }).on("select2:select", function(e) {
        $("#"+element).val(e.params.data.id);
    });

}
function searchwaypoint(url,placeholder,valmin,element) {
    $('.select2').select2({
        placeholder: placeholder,
        minimumInputLength: valmin,
        ajax: {
            url: url,
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
       
    }).on("select2:select", function(e) {
       
            $("#"+element).val(e.params.data.id);

    });

}
function searchnavaid(url,placeholder,valmin,element) {
    $('.select2').select2({
        placeholder: placeholder,
        minimumInputLength: valmin,
        ajax: {
            url: url,
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
       
    }).on("select2:select", function(e) {
        $("#"+element).val(e.params.data.id);
    });

}
function aboutvol( id )
{
   
    var vol = document.getElementById(id);
    vol.style.visibility = 'visible';
    $('#' + id).toggle(); 
}
function elementvisibility(id){
    var vol = document.getElementById( id );
    // console.log( vol );
    // vol.style.visibility = 'visible';
    return vol.style.visibility
}
function dateToJulianNumber(d) {
    // convert a Gregorian Date to a Julian number. 
    //    S.Boisseau / BubblingApp.com / 2014
    var x = Math.floor((14 - d.getMonth())/12);
    var y = d.getFullYear() + 4800 - x;
    var z = d.getMonth() - 3 + 12 * x;

    var n = d.getDate() + Math.floor(((153 * z) + 2)/5) + (365 * y) + Math.floor(y/4) + Math.floor(y/400) - Math.floor(y/100) - 32045;

    return n;
}

// assert September 30 2014 -> 2456931
// console.log(dateToJulianNumber(new Date(2014,9,30)).toString());

function julianIntToDate(n) {
    // convert a Julian number to a Gregorian Date.
    //    S.Boisseau / BubblingApp.com / 2014
    var a = n + 32044;
    var b = Math.floor(((4*a) + 3)/146097);
    var c = a - Math.floor((146097*b)/4);
    var d = Math.floor(((4*c) + 3)/1461);
    var e = c - Math.floor((1461 * d)/4);
    var f = Math.floor(((5*e) + 2)/153);

    var D = e + 1 - Math.floor(((153*f) + 2)/5);
    var M = f + 3 - 12 - Math.round(f/10);
    var Y = (100*b) + d - 4800 + Math.floor(f/10);

    return new Date(Y,M,D);
}

function isValidDate(d) {
  return d instanceof Date && !isNaN(d);
}
function DateFormat(params,indonesia=false,pub=false) {
    // console.log('validate: '+isValidDate( params ));
    const bulan = [ "JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC" ];
    // console.log('param:'+params);
    // if ( isValidDate( params )==true ) {
    //     return params;
    // } else {
        var today = new Date();
        
        if (params != undefined) {
            today=params
        }
        
        var dd = today.getDate();
    
        var mm = today.getMonth()+1;
        var yyyy = today.getFullYear();
        if(dd<10)
        {
        dd='0'+dd;
        }
    
        if(mm<10)
        {
        mm='0'+mm;
        }
        
        var month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";
        var n = month[ today.getMonth() ];
        var nn = bulan[today.getMonth()];
        today = yyyy+'-'+mm+'-'+dd;
        if ( indonesia == true ) {
            today = dd +'/'+ n +'/'+ yyyy + ' ' + new Date(today).toLocaleTimeString();
        }
        if ( pub == true ) {
            today = dd +' '+ nn +' '+ yyyy;
        }
        
        // console.log(today);
        return today;
    // }
}

function CenterControl(controlDiv, map) {
  // Set CSS for the control border.
  const controlUI = document.createElement("div");
  controlUI.style.backgroundColor = "#fff";
  controlUI.style.border = "2px solid #fff";
  controlUI.style.borderRadius = "3px";
  controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
  controlUI.style.cursor = "pointer";
  controlUI.style.marginBottom = "22px";
  controlUI.style.textAlign = "center";
  controlUI.title = "Click to recenter the map";
  controlDiv.appendChild(controlUI);
  // Set CSS for the control interior.
  const controlText = document.createElement("div");
  controlText.style.color = "rgb(25,25,25)";
  controlText.style.fontFamily = "Roboto,Arial,sans-serif";
  controlText.style.fontSize = "16px";
  controlText.style.lineHeight = "20px";
  controlText.style.paddingLeft = "20px";
  controlText.style.paddingRight = "20px";
  controlText.innerHTML = "Overlay";
  controlUI.appendChild(controlText);
  // Setup the click event listeners: simply set the map to Chicago.
  controlUI.addEventListener("click", () => {
    map.setCenter(chicago);
  });
}

function SetHeader(title,subtitle){
    var hd1='';hd2='';hd3='';
    if (subtitle !==''){
        hd2= '<p class="col-4 text-white" align="right">'+subtitle+'</p>'
    }

        hd1= '<div class="modal-dialog-lg" role="document">'+
        '<div class="modal-content">'+
        '<div class="modal-header bg-gray">'+
        '<a type="button" onclick="showabout()" data-toggle="modal">'+
        '<h5 class="modal-title text-white" align="center">'+title+'</h5></a>'
        
        hd3='</a></div><div class="modal-body">'
    //'<form action="#" class="form-validate is-alter" novalidate="novalidate">'
        return hd1 + hd2 + hd3
}
function SetFooter(volcano,volnumber){
    return '<br>'+
            '<div class="row">'+
            '<div class="col-md-6">'+
            '<button onclick="showabout()" class="btn btn-var btn-light"><i class="icon ni ni-reply-fill"></i> Back</button>'+
            '</div>'+
            '</div>'+
          //  '</form>'+
            '</div>'+
            '<div class="modal-footer bg-light">'+
            '<span class="sub-text">' + volcano + ' - ' + volnumber +'</span>'+
            '</div>'+
            '</div>'+
            '</div>'
}
function getPressure( pressure )
{ 
    var pp = pressure.substr(1, pressure.length - 1 ).replace('=','');
    // console.log('getPressure', pp );
    return pp + ' hPa ('+ (pp /33.863886666667).toFixed(2) + ' incHg)'
}
function getRVR( rvr )
{
    // console.log(rvr)
    var awal = false; hsil = '';
    for ( let i = 3; i < rvr.length; i++ ) {
        
        n = rvr[ i ].indexOf( "/" );
        if ( rvr[ i ].substr( 0, 1 ) == 'R' && n !== -1 ) {
          
            var pp = rvr[i].substr( 1, rvr.length - 1 ).split( '/' ); rvis = '';
            if ( pp[ 1 ].substr( 0, 1 ) == 'P' ) {
                rvis = '≥' + pp[ 1 ].substr( 1, pp[ 1 ].length - 1 ) + ' m';
            } else if ( pp[ 1 ].substr( 0, 1 ) == 'M' ) {
                rvis = '≤' + pp[ 1 ].substr( 1, pp[ 1 ].length - 1 ) + ' m';
            } else {
                rvis = pp[ 1 ] + ' m';
            }
            var hh='Runway ' + pp[ 0 ] + ' ' + rvis;
            if ( awal == false ) {
                awal = true;
                hsil = hh ;
            } else {
                hsil += ',<br>&nbsp;&nbsp;' +hh;
            }
            // console.log( 'getRVR haillll', hsil );
        }
        
        
    }
    return hsil;
}
function getClouds( clouds )
{
    // console.log(SCTFEWBKNOVC)
    //knot to m/s = 0.5;SKY SKY CLEAR few (1-2 oktas) scattered SCT(3-4 oktas) broken BKN(5-7 oktas) overcast (8 oktas)
    var awal = false; hsil = ''; ret = '';
    for ( let i =2; i < clouds.length; i++ ) {
        var cl = clouds[ i ]; clsub = cl.substr( 0, 3 );clsalt = cl.substr( 3, 3 );
        if ( clsub == 'SCT' || clsub == 'FEW' || clsub == 'BKN' || clsub == 'OVC' ) {
            clsalt *= 100;
            var cc = cl.indexOf( "CB" ); tcu = cl.indexOf( "TCU" ); ccn = '';
            if ( cc !== -1 ) {
                ccn=' with cumulonimbus'
            }
            if ( tcu !== -1 ) {
                ccn=' with towering cumulus'
            }
            if ( clsub == 'SCT' ) {
                hsil = 'SCATTERED cloud layer (3-4 oktas) at ' + ' ' + clsalt + 'ft' + ccn;
            } else if ( clsub == 'FEW' ) {
                hsil = 'FEW clouds layer (1-2 oktas) at ' + ' ' + clsalt  + 'ft'+ ccn;
            } else if ( clsub == 'BKN' ) {
                hsil = 'BROKEN cloud layer (5-7 oktas) at ' + ' ' + clsalt + 'ft'+ ccn;
            } else if ( clsub == 'OVC' ) {
                hsil = 'OVERCAST cloud layer (8 oktas) at ' + ' ' + clsalt + 'ft'+ ccn;
            }

            if ( awal == false ) {
                awal = true;
                ret = hsil ;
            } else {
                ret += ',<br>&nbsp;&nbsp;' +hsil;
            }
            // console.log( 'getClouds', hsil );
        }
        
        
    }
    return ret;
}
function IsiRowData( titel, isi )
{
    
    return  '<div class="row">'+
        '<div class="col-2">' + titel + '</div>' +
        '<div class="col-10">: '+ isi +'</div>'+
        '</div>';
    }
function getWind(wind,between)
{
    var divi = 0.514444;
    var hasil = '';
    var arah = wind.substr( 0, 3 ); gust = '';
    var bet = '';
    if ( between !== '' ) {
        var bb = between.split( 'V' );
        bet = 'between (' + bb[0]  + '° and ' + bb[1]  + '°)'; 
    }
    var n = wind.indexOf( "G" );
    if ( n !== -1 ) {
        gust =' with gust peaking at ' + wind.substr( 6, 2 ) +' knots';
    }
    // var angka = 5.3428419;
    // hasil = angka.toFixed(4);
    // document.write(hasil);
    var spp = wind.substr( 3, 2 );
    var speed = Number(spp).toFixed(1);
    var spms= (speed*divi).toFixed(2);
    // console.log(wind,arah,speed,spms,speed*divi)
    if ( arah == 'VRB' ) {
        hasil = 'variable ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)' + gust;
        //0,514444
    }else 
    if ( arah =="000") {
        hasil = 'Calm (a wind speed of less than 1.0 knots (0.5 m/s))';
    }else 
    if ( arah >= 250 && arah <= 290) {
        hasil = 'from the West (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)'  + gust;
    }else
    if ( (arah >= 340 && arah <= 360) || (arah >= 0 && arah <= 2)) {
        hasil = 'from the North (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)'  + gust;
    }else
    if ( arah >= 70 && arah <= 110) {
        hasil = 'from the East (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)'  + gust;
    }else
    if ( arah >= 160 && arah <= 200) {
        hasil = 'from the South (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)' + gust;
    }else
    if ( arah < 250 && arah > 200) {
        hasil = 'from the South West (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)' + gust;
    }else
    if ( arah < 340 && arah > 290) {
        hasil = 'from the North West (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)' + gust;
    }else
    if ( arah < 160 && arah > 110) {
        hasil = 'from the South East (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)' + gust;
    }else
    if ( arah < 70 && arah > 2) {
        hasil = 'from the North East (' + arah + '°) ' + bet + ' blowing at ' + speed + ' knots (' + spms + ' m/s)' + gust;
    }
    
    return hasil;
}
function getMonth( mm )
{
    var hsl = '';
    switch ( mm ) {
        case "01":
            hsl="January";
            break;
        case "02":
            hsl="February";
            break;
        case "03":
            hsl="March";
            break;
        case "04":
            hsl="April";
            break;
        case "05":
            hsl="May";
        break;
        case "06":
            hsl="June";
        break;
        case "07":
            hsl="June";
        break;
        case "08":
            hsl="August";
        break;
        case "09":
            hsl="September";
        break;
        case "10":
            hsl="October";
        break;
        case "11":
            hsl="November";
        break;
        case "12":
            hsl="December";
        break;

    }
    return hsl;
}

function WeatherCode(code){

var arr = ["-","+","BC", "BL", "BR","DR", "DS", "DU","DZ", "FG", "FC","FU", "FZ", "GR","GS", "HZ", "IC","MI", "PL", "PO","RA", "SA", "SG","SH", "SN", "SQ","SS","TS","VA","VC","UP"]
    // console.log( code );
   
//     var result = arr.every( function ( word )
//     {
//     console.log(code.indexOf( word ),word,code)
//     // indexOf(word) returns -1 if word is not found in string
//     // or the value of the index where the word appears in string
//     // return code.indexOf( word ) > -1;
// })
// console.log(result) // true

    var hhsl = ''; pre = '';
    arr.forEach( a =>
    {
        var ada = code.indexOf( a );
        if ( ada == 0 && a == '-' ) {
            hhsl += 'slight ' + getCode( a.substr( 1, 2 ) );
        } else if ( ada == 0 && a == '+' ) {
            hhsl += 'Heavy ' + getCode( a.substr( 1, 2 ) );
        }else if ( ada == 0 ) { 
            hhsl += ' ' + getCode( a );
        }
        // console.log(code.indexOf( a ),a,code);
    } )
    console.log( hhsl );
    return hhsl;
//     - = slight   + = Heavy   BC = Patches    BL = Blowing
// BR = Mist    DR = Low Drifting   DS = Dust Storm DU = Widespread Dust
// DZ = Drizzle FG = Fog    FC = Funnel Cloud (e.g. Tornado)    FU = Smoke
// FZ = Freezing    GR = Hail   GS = Small Hail HZ = Haze
// IC = Ice Crystals    MI = Shallow    PL = Ice Pellets    PO = Dust Devils
// RA = Rain    SA = Sand   SG = Snow Grains    SH = Shower
// SN = Snow    SQ = Squall SS = Sandstorm  TS = Thunderstorm
// VA = Volcanic Ash    VC = In the vicinity (nearby)   UP = Unidentified Precipitation RE = Recent
}
function getCode(cd)
{
    var h = '';
    switch ( cd ) {
        case "BC":
            h = 'Patches';
            break;
        case "BL":
            h = 'Blowing';
            break;
        case "BR":
            h = 'Mist';
            break;
        case "DR":
            h = 'Low Drifting';
            break;
        case "DS":
            h = 'Dust Storm';
            break;
        case "DU":
            h = 'Widespread Dust';
            break;
        case "DZ":
            h = 'Drizzle';
            break;
        case "FG":
            h = 'Fog';
            break;
        case "FC":
            h = 'Funnel Cloud( e.g.Tornado )';
            break;
        case "FU":
            h = 'Smoke';
            break;
        case "FZ":
            h = 'Freezing';
            break;
        case "GR":
            h = 'Hail';
            break;
        case "GS":
            h = 'Small Hail';
            break;
        case "HZ":
            h = 'Haze';
            break;
        case "IC":
            h = 'Ice Crystals';
            break;
        case "MI":
            h = 'Shallow';
            break;
        case "PL":
            h = 'Ice Pellets';
            break;
        case "PO":
            h = 'Dust Devils';
        case "RA":
            h = 'Rain';
            break;
        case "SA":
            h = 'Sand';
            break;
        case "SG":
            h = 'Snow Grains';
            break;
        case "SH":
            h = 'Shower';
            break;
        case "SN":
            h = 'Snow';
            break;
        case "SQ":
            h = 'Squall';
            break;
        case "SS":
            h = 'Sandstorm';
            break;
        case "TS":
            h = 'Thunderstorm';
            break;
        case "VA":
            h = 'Volcanic Ash';
            break;
        case "VC":
            h = 'In the vicinity( nearby )';
            break;
        case "UP":
            h = 'Unidentified Precipitation';
            break;
        case "RE":
            h = Recent;
            break;
    }
    return h;
}
function getdistance( latitude1, longitude1, latitude2, longitude2 )
{
    var hasil = '';
    if (isNaN(latitude1)==true || isNaN(longitude1)==true || isNaN(latitude2)==true || isNaN(longitude2)==true ) {
        console.log( 'getdist cannot proses ', latitude1 + ',' + longitude1 + ',' + latitude2 + ',' + longitude2 );
    } else {
        this.val = { a: longitude1, b: latitude1, c: longitude2, d: latitude2 }
        this.hhs = [];
        this.kk = '';
        this.rslt = [];
        this.rslt = Getbearing( latitude1, longitude1, latitude2, longitude2 );
        // var pathdetail= pathpop()   + '/api/getdistance';
        // $.ajax({
        //         url: pathdetail,
        //         data: this.val,
        //         type: "json",
        //         method: "GET",

        //         success: function (result) {
        //             $.each(result.data, function (k, v) {
        //                 console.log( 'jarak,', v.dist )
        //                 this.dst1 = km2nm( v.dist ) / 1000;
        //                 this.dst2 = ( km2nm( v.dist ) / 1000 ).toFixed( 1 );
            
        //                 // this.hhs.push( v );
        //             })
        //             this.rslt['Distance'] = this.dst2;
        //             this.rslt['DistanceReal'] = this.dst1;
        //         }
        // })
        // ApiManager.request( 'GET', 'getdistance?' + this.val, null, ( response ) => {
        //     response.getData().forEach( hasil => {
        //         this.hhs[ 'distnm' ] = this.km2nm( hasil.dist ) / 1000;
        //         this.hhs[ 'distv' ] = ( this.km2nm( hasil.dist ) / 1000 ).toFixed( 1 );
        //         this.hhs.push( hasil );
        //     } )
        //     this.kk = this.hhs.distnm;
        //     this.rslt.Distance = this.hhs.distv;
        //     this.rslt.DistanceReal = this.hhs.distnm
        //     // console.log(this.kk)
        // } )
    }
        // console.log('hasil...',this.rslt);

        return this.rslt;
    

}
function getpoint2coord( latitude1, longitude1, bearing, distance )
{
    var bear =360- Number( bearing );
    var rEarth = 6371.01 // Earth's average radius in km
    var epsilon = 0.000001 //# threshold for floating-point equality
    var rlat1 = deg2rad( latitude1 );
    var rlon1 = deg2rad( longitude1 );
    var rbearing = deg2rad( bear );
    // var rdistance = distance / rEarth;
    var rdistance = nm2km( distance ) / rEarth;
    // console.log(latitude1, longitude1, bearing,rbearing, distance,rdistance)
    // rlat1 = deg2rad(lat1)
    // rlon1 = deg2rad(lon1)
    // rbearing = deg2rad(bearing)
    //# normalize linear distance to radian angle

    rlat = Math.asin( Math.sin(rlat1) * Math.cos(rdistance) + Math.cos(rlat1) * Math.sin(rdistance) * Math.cos(rbearing) )

    if ( Math.cos( rlat ) == 0 || Math.abs( Math.cos( rlat ) ) < epsilon ) { //: # Endpoint a pole
        rlon = rlon1;
    } else {
        rlon = ( ( rlon1 - Math.asin( Math.sin( rbearing ) * Math.sin( rdistance ) / Math.cos( rlat ) ) + Math.PI ) % ( 2 * Math.PI ) ) - Math.PI;
    }
    lat = rad2deg(rlat)
    lon = rad2deg(rlon)
    // console.log(lon+','+lat)
    return SetCoordinatebyDecimal(  lon ,lat);
    
}

function getarccoord( latitude1, longitude1, bearing1, bearing2,radius,turn )
{
    var hasil = [];
    if ( turn == 'R' ) {
        if ( bearing1 > bearing2 ) {
            bearing2 += 360;
            for ( let i = bearing1; i <= bearing2; i++ ) {
                var bbr = i;
                if ( bbr > 360 ) {
                    bbr -= 360;
                }
                var crd = getpoint2coord( latitude1, longitude1, bbr, radius )
                hasil.push(crd)
                
            }
        } else {
            // console.log('positif')
            for ( let i = bearing1; i <= bearing2; i++ ) {
                // console.log(i)
                var crd = getpoint2coord( latitude1, longitude1, i, radius )
                hasil.push(crd)
                
            }
        }
    } else if ( turn == 'L' ){
        if ( bearing1 < bearing2 ) {
            bearing1 += 360;
            for ( let i = bearing1; i >= bearing2; i-- ) {
                var bbr = i;
                if ( bbr > 360 ) {
                    bbr -= 360;
                }
                var crd = getpoint2coord( latitude1, longitude1, bbr, radius )
                hasil.push(crd)
                
            }
        } else {
            // console.log('positif')
            for ( let i = bearing1; i >= bearing2; i-- ) {
                // console.log(i)
                var crd = getpoint2coord( latitude1, longitude1, i, radius )
                hasil.push(crd)
                
            }
        }
        
    }

    return hasil;
    
}
function getpendicularcourse( Course, Turn )
{
    var hsl=''
    if ( Turn == 'R' ) {
        hsl = Course + 90;
        if ( hsl > 360 ) {
            hsl -= 360;
        }
    } else {
        hsl = Course - 90;
        if ( hsl < 0 ) {
            hsl += 360
            
        }
    }

    return hsl;
}
function getradial( course )
{
    if ( course > 180 ) {
        return course - 180;
    } else {
        return course + 180;
    }
}
function createholding( lat,lon,inbound, turn )
{
    var leg = 2;
    var inb = getradial( inbound );
    var pend = getpendicularcourse( inbound, turn );
    var pend2 = getradial(pend);
    var point1 = getpoint2coord( lat, lon, pend, leg )
    var point2 = getpoint2coord( point1.Decimal[ 1 ], point1.Decimal[ 0 ], inb, leg * 1.5 )
    var point3 = getpoint2coord( point2.Decimal[ 1 ], point2.Decimal[ 0 ], pend2, leg )
    console.log(lon+','+lat, inbound ,turn,inb,pend,pend2)
    
    // console.log(lon+','+lat)
    // console.log( lat, lon, pend, point1 ,point2,point3)
    var beer = Getbearing( lat, lon, point1.Decimal[ 1 ], point1.Decimal[ 0 ] )
    // console.log( beer.Midlon + ',' + beer.Midlat )
    var beer1 = Getbearing( point2.Decimal[ 1 ], point2.Decimal[ 0 ], point3.Decimal[ 1 ], point3.Decimal[ 0 ] )
    // console.log( beer.Midlon + ',' + beer.Midlat )
    var arc1 = getarccoord( beer.Midlat, beer.Midlon, pend2, pend, leg/2 ,turn)
    var arc2=getarccoord(beer1.Midlat,beer1.Midlon,pend,pend2,leg/2,turn)
    // console.log( beer1.Midlon + ',' + beer1.Midlat )
    var result = [];
    arc1.forEach( a =>
    {
        result.push(a.Decimal[0]+' '+a.Decimal[1])
        // console.log(a.Decimal[0]+','+a.Decimal[1])
    })
    result.push( point2.Decimal[ 0 ] + ' ' + point2.Decimal[ 1 ] )
    arc2.forEach( a =>
        {
            result.push(a.Decimal[0]+' '+a.Decimal[1])
        })
        result.push(lon+' '+lat)
        
        // console.log(result)

    return result;
}

function Getbearing( latitude1, longitude1, latitude2, longitude2 )
{
   
    const a = 6378137;
    const b = 6356752.314245;
    const f = 1 / 298.257223563;
    // var f = 1 / 298.257223563;
    // var f = 0.00335281068118 ;0.00335281066474

    var epoch = new Date().toISOString().substr( 0, 10 );
    // MV.GetMagvar( longitude1, latitude1, epoch );
    var mv = GetMagvar( longitude1, latitude1, epoch,0 );
    // console.log('hasil magvar', mv );
    var mgvar1 = mv.dec;
    // console.log(MV.result)
    mv = GetMagvar( longitude2, latitude2, epoch,0 );
    var mgvar2 = mv.dec;
    // console.log(MV.result)
    // console.log(mgvar1 + '  ' + mgvar1)
    var trkoutT, trkoutM, trkinT, trkinM, midX, midY, trackoutM, trackinM, trackoutT, trackinT;
//     const R = 6371e3; // metres
//     const φ1 = latitude1 * Math.PI/180; // φ, λ in radians
//     const φ2 = latitude2 * Math.PI/180;
//     const Δφ = (latitude2-latitude1) * Math.PI/180;
//     const Δλ = (longitude2-longitude1) * Math.PI/180;
    
//     const aa = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
//               Math.cos(φ1) * Math.cos(φ2) *
//               Math.sin(Δλ/2) * Math.sin(Δλ/2);
//     const cc = 2 * Math.atan2(Math.sqrt(aa), Math.sqrt(1-aa));
    
//     const ddd = R * cc; // in metres
// console.log('disttttttttttt',ddd)
    for ( let i = 0; i < 2; i++ ) {
        var lat1 = deg2rad( latitude1 ),
            lat2 = deg2rad( latitude2 ),
            lon1 = deg2rad( longitude1 ),
            lon2 = deg2rad( longitude2 );
        if ( i == 1 ) {
            lat1 = deg2rad( latitude2 ),
            lat2 = deg2rad( latitude1 ),
            lon1 = deg2rad( longitude2 ),
            lon2 = deg2rad( longitude1 );
        }
        var dLat, dLon;
        if ( lat1 > lat2 ) {
            dLat = lat1 - lat2;  // deg2rad below
        } else {
            dLat =lat2 - lat1;  // deg2rad below
        }
        if ( lon1 > lon2 ) {
            dLon = lon1-lon2; 
        } else {
            dLon = lon2-lon1; 
        }
       
        // var R = 6371; // Radius of the earth in km
        

        // var aa = 
        //     Math.sin(dLat/2) * Math.sin(dLat/2) +
        //     Math.cos(lat1) * Math.cos(lat2) * 
        //     Math.sin(dLon/2) * Math.sin(dLon/2)
        //     ;
        // var c = 2 * Math.atan2(Math.sqrt(aa), Math.sqrt(1-aa)); 
       
        // console.log('Distance in km',d*1000)
        var bx = Math.cos( lat2 ) * Math.cos( L ),
            by = Math.cos( lat2 ) * Math.sin( L ),
            lat3 = Math.atan2( Math.sin( lat1 ) + Math.sin( lat2 ), Math.sqrt( ( Math.cos( lat1 ) + bx ) * ( Math.cos( lat1 ) + bx ) + by * by ) ),
            lon3 = lon1 + Math.atan2( by, Math.cos( lat1 ) + bx );
        xx = ( lon1 + lon2 ) / 2;
        yy = ( lat1 + lat2 ) / 2;
        // console.log(rad2deg( xx )+','+rad2deg( yy ))
        midX = rad2deg( xx );
        midY = rad2deg( yy );

        var L = lon2 - lon1,
        iterations = 0;

        var tanU1 = ( 1 - f ) * Math.tan( lat1 ),
            cosU1 = 1 / Math.sqrt( ( 1 + tanU1 * tanU1 ) ),
            sinU1 = tanU1 * cosU1,
            tanU2 = ( 1 - f ) * Math.tan( lat2 ),
            cosU2 = 1 / Math.sqrt( ( 1 + tanU2 * tanU2 ) ),
            sinU2 = tanU2 * cosU2;

        var sinλ, cosλ, sinSqσ, sinσ, cosσ, σ, sinα, cosSqα, cos2σM, C,
            λ = L,
            λ1 = 0.0;

        while ( Math.abs( λ - λ1 ) > 0.000000000001 && ++iterations < 200 ) {
            sinλ = Math.sin( λ );
            cosλ = Math.cos( λ );
            sinSqσ = ( cosU2 * sinλ ) * ( cosU2 * sinλ ) + ( cosU1 * sinU2 - sinU1 * cosU2 * cosλ ) * ( cosU1 * sinU2 - sinU1 * cosU2 * cosλ );
            sinσ = Math.sqrt( sinSqσ );
            // '   MsgBox(sinσ)
            // '  If (sinσ = 0) Then Return 0 '  // co-incident points
            cosσ = sinU1 * sinU2 + cosU1 * cosU2 * cosλ;
            σ = Math.atan2(sinσ, cosσ); //distance
            sinα = cosU1 * cosU2 * sinλ / sinσ;
            cosSqα = 1 - sinα * sinα;
            cos2σM = cosσ - 2 * sinU1 * sinU2 / cosSqα;
            if ( isNaN( cos2σM ) ) {
                cos2σM = 0
            }
            // ' // equatorial line: cosSqα=0 (§6)
            C = f / 16 * cosSqα * ( 4 + f * ( 4 - 3 * cosSqα ) );
            λ1 = λ;
            λ = L + ( 1 - C ) * f * sinα * ( σ + C * sinσ * ( cos2σM + C * cosσ * ( -1 + 2 * cos2σM * cos2σM ) ) );
        }
        // var isNaN = function(value) {
        //     return Number.isNaN(Number(value));
        //     }
        if ( iterations >= 200 ) {
            alert( "Formula failed to converge" )
        }
        var uSq = cosSqα * (a * a - b * b) / (b * b);
        var AA = 1 + uSq / 16384 * ( 4096 + uSq * ( -768 + uSq * ( 320 - 175 * uSq ) ) );
        var BB = uSq / 1024 * ( 256 + uSq * ( -128 + uSq * ( 74 - 47 * uSq ) ) );
        var Δσ = BB * sinσ * ( cos2σM + BB / 4 * ( cosσ * ( -1 + 2 * cos2σM * cos2σM ) -
            BB / 6 * cos2σM * ( -3 + 4 * sinσ * sinσ ) * ( -3 + 4 * cos2σM * cos2σM ) ) );

        var s  = b * AA * (σ - Δσ)
    //    console.log('TESTTTT',s,σ)
        var α1 = Math.atan2( cosU2 * sinλ, cosU1 * sinU2 - sinU1 * cosU2 * cosλ );
        var α2 = Math.atan2( cosU1 * sinλ, -sinU1 * cosU2 + cosU1 * sinU2 * cosλ );

        α1 = rad2deg( ( α1 + 2 * Math.PI ) % ( 2 * Math.PI ) ) // normalise to 0..360
        α2 = rad2deg( ( α2 + 2 * Math.PI ) % ( 2 * Math.PI ) ) // normalise to 0..360
        // console.log(α1 + '  ' + α2)
        if ( lon2 == lon1 ) {
            if ( lat1 > lat2 ) {
                α1 = α2
            }
        }

        if ( i == 0 ) {
            // format("####.0##",frq/10000)
            // console.log(α1 , ' track out ' , α2 , ' i ' , i )
            trkoutT = α1;
            trackoutT = ( Math.round( trkoutT * 100 ) / 100).toFixed( 2 );
            trkoutM = trkoutT - mgvar1;
            if ( trkoutM < 0 ) {
                trkoutM = ( 360 + trkoutT ) - mgvar1
            }
            trackoutM =( Math.round( trkoutM * 100 ) / 100).toFixed( 2 ); //format( "000", trkoutM.toFixed() )
        } else {
            // console.log(α1 , ' track in ' , α2 , ' i ' , i )
            trkinT = α2;
            trackinT =( Math.round( trkinT * 100 ) / 100 ).toFixed( 2 );
            trkinM = trkinT - mgvar2;
            if ( trkinM < 0 ) {
                trkinM = ( 360 + trkinT ) - mgvar2
            }
            trackinM = ( Math.round( trkinM * 100 ) / 100 ).toFixed( 2 );//format( "000", trkinM.toFixed() )
        }
    }
    var d =  m2Nm(s);
    var dsnm = d.toFixed(2);
    dist = Math.round(s);
    // console.log( ' hasil  ' ,dist,s,trackoutT,trackoutM,trackinT,trackinM)
    return {
        TrackOutTrue: trackoutT,
        TrackOutMag: trackoutM,
        TrackInTrue: trackinT,
        TrackInMag: trackinM,
        TrackOutReal: trkoutT,
        TrackOutMagReal: trkoutM,
        TrackInReal: trkinT,
        TrackInMagReal: trkinM,
        Midlat: midY,
        Midlon: midX,
        DistinNM: dsnm,
        Distance: dist,
        DistanceReal: d,
        DistinMeter: s
    };
}

function rad2deg( rad ) {
    return rad *  180 / Math.PI ;
}
function deg2rad( deg )
{
    // angle*pi/180
    return deg *  Math.PI / 180 ;
}
function nm2km( value ) {
    return value / 0.5399568035;
}
function km2nm( value ) {
    return value * 0.5399568035;
}
function m2Nm( value ) {
    return value * 0.000539957;
}
function plotpoint( lat, lon )
{
    var y = document.getElementById( lat );
    var x = document.getElementById( lon );
    var isY = this.CheckCoordinateFormat( lat, 'LAT' )
    var isX = this.CheckCoordinateFormat( lon, 'LON' )
    if ( isY == true && isX == true ) {
        var cord= SetCoordinate( y.value, x.value )
        this.url = '/map.php?lat=' + cord.Decimal[1] + '&lon=' + cord.Decimal[0]
        let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
        window.open(this.url, 'Set Latitude and Longitude', params)
        
    }
}
function CheckCoordinateFormat(id,note) {
    var x = document.getElementById(id);
    x.value = x.value.toUpperCase();
    var value = x.value;
    if ( value == '' ) {
        this.result = true
    } else {
        
        if ( note == 'LAT' ) {
            this.cordin = 'latitude';
        } else {
            this.cordin = 'longitude';
        }
            this.plat = value.replace( /\s/g, '' )
            // console.log(value)
            // console.log(this.plat.lastIndexOf('S'))
            this.head = value.substr( value.length - 1 ).toUpperCase();
            // console.log(this.head + ' this.head',Number(this.plat),this.plat.length)
            
            if ( this.head != 'S' && this.head != 'N' && note=='LAT') {
                // console.log(this.head + ' setelah if this.head')
                this.result = false
            } else if ( this.head != 'E' && this.head != 'W'  && note=='LON') {
                this.result = false
            } else if ( this.plat.length == 0 ) {
                this.result = false
            } else {
                if (isNaN(this.plat)==false) {
                    // console.log('ANGKA',Number(this.plat))
                    this.plat = parseFloat( this.plat )
                    if ( this.plat > 90 || this.plat < -90 ) {
                        this.result = false
                    } else {
                        this.result = true
                    }
                } else {
                    // console.log(this.head + ' stringggg setelah if this.head')
                    if ( this.head == "S" || this.head == "N" ) {
                        if ( this.plat.lastIndexOf( 'S' ) == 8 || this.plat.lastIndexOf( 'N' ) == 8 ) {
                            if ( this.plat.length == 9 ) {
                                this.deg = Number( this.plat.substr( 0, 2 ) );
                                this.min = Number( this.plat.substr( 2, 2 ) );
                                this.det = Number( this.plat.substr( 4, 4 ) ) / 100;
                                this.sec = Number( this.plat.substr( 6, 2 ) );
                                // console.log(this.deg + ' ' + this.min + ' ' + this.det )
                                if ( this.deg < 90 && this.min < 60 && this.det < 60 ) {
                                    this.result = true
                                } else {
                                    this.result = false
                                }
                            } else {
                                this.result = false
                            }
                        } else if ( this.plat.substr( 4, 1 ) == '.' ) {
                            this.plat = this.plat.Replace( /S|N/g, "0" )
                            this.deg = Number( this.plat.substr( 0, 2 ) );
                            this.min = Number( this.plat.substr( 2, ( this.plat.length - 2 ) ) );
                            if ( this.deg < 90 && this.min < 60 ) {
                                this.result = true
                            } else {
                                this.result = false
                            }
                        } else {
                            this.result = false
                        }
                    } else {
                        if ( this.plat.lastIndexOf( 'E' ) == 9 || this.plat.lastIndexOf( 'W' ) == 9 ) {
                            if ( this.plat.length == 10 ) {
                                this.deg = Number( this.plat.substr( 0, 3 ) );
                                this.min = Number( this.plat.substr( 3, 2 ) );
                                this.det = Number( this.plat.substr( 5, 2 ) );
                                this.sec = Number( this.plat.substr( 7, 2 ) );
                                //  console.log(this.deg + ' ' + this.min + ' ' + this.det )
                                if ( this.deg < 180 && this.min < 60 && this.det < 60 ) {
                                    this.result = true
                                } else {
                                    this.result = false
                                }
                            } else {
                                this.result = false
                            }
                        } else if ( this.plat.substr( 5, 1 ) == '.' ) {
                            this.plat = this.plat.Replace( /E|W/g, "0" )
                            this.deg = Number( this.plat.substr( 0, 3 ) );
                            this.min = Number( this.plat.substr( 3, ( this.plat.length - 2 ) ) );
                            if ( this.deg < 180 && this.min < 60 ) {
                                this.result = true
                            } else {
                                this.result = false
                            }
                        } else {
                            this.result = false
                        }
                    }
                }
            }
    }
    
    $("#"+id).attr('style', "border-radius: 4px; border:#dbdfea 1px solid;");
    if ( this.result == false ) {
        $( "#" + id ).attr( 'style', "border-radius: 5px; border:#FF0000 2px solid;" );
        // $( "#" + id ).select();
        // $( "#" + id ).focus();
        Swal.fire(
            'Error!',
            'The '+ this.cordin + ' coordinate '+ this.plat +' do not match the WGS format',
            'error'
        );
    }
        return this.result
}
    
function  compareisidata(arr_field,datatemp,datacurr,pos=null){
    // console.log(datacurr,'datacurr compareisidata',arr_field)
    arr_field.forEach( a =>
    {
        var isid=checkisicontain(datatemp[a]);
        var sts='U';
        var dt=a;
        if (pos !== null){
            dt=pos + "_" + a;
        }
        if (typeof datacurr=='undefined'){
            sts='R';
        }else{
            if (datatemp[a] !== datacurr[a]){
                sts='R';
            }
        }
        // console.log(a,dt,datatemp[a] , datacurr[a],isid)
        $("#" + dt).val(isid)
        isidata( dt, sts )
    })

}

function  clearinput(arr_field,pos=null){
    // console.log(datacurr)
    arr_field.forEach( a =>
    {
        var dt=a;
        if (pos !== null){
            dt=pos + "_" + a;
        }
        $("#" + dt).val('')
    })

}

function  settonullinput(arr_field,pos=null){
    // console.log(datacurr)
    arr_field.forEach( a =>
    {
        var dt = a;
        if ( pos !== null ) {
            dt = pos + "_" + a;
        }
        var isi = $( "#" + dt ).val()
        if ( isi == 'NIL' || isi == null ) {
            $("#" + dt).val('')
        }
    })

}
function  setinputtoupper(arr_field,pos=null){
    // console.log(datacurr)
    arr_field.forEach( a =>
    {
        var dt=a;
        if (pos !== null){
            dt=pos + "_" + a;
        }
        var isi = $( "#" + dt ).val()
        $("#" + dt).val(isi.toUpperCase())
        
    })

}
function Fl2feet( Altitude ) {
    let hsl;
    var Alt = Altitude.toUpperCase()
    if ( Alt.substr( 0, 2 ) == "FL" ) {
        if ( Alt.length < 6 ) {
            hsl = Alt.substr( 2, Alt.length - 2 ) * 100;
        } else {
            hsl = 0;
        }
        // console.log(Alt.substr(0, 2) )
        // console.log('this.GetNumeric(Alt)  ' + this.GetNumeric(Alt))

    } else if ( Number( Alt ) ) {
        hsl = Alt;
    } else {
        hsl = 0;
    }
    return hsl;
}
function checkupdatedata(arr_field,datatemp,pos=null){
    var hasil = false;
    // console.log(arr_field,datatemp)
    for (let i=0;i < arr_field.length;i++){
        var a=arr_field[i];
        var dt=a;
        if (pos !== null){
            dt=pos + "_" + a;
        }
        var dtlama=datatemp[a];
        if (dtlama==null){
            dtlama='';
        }
        var val = $( "#" + dt ).val();
        if ( val == 'NIL' ) {
            val = '';
        }
        var vv =''; dtll ='';
        // console.log( 'Field ',dtlama )
        if ( typeof dtlama == 'number' ) {
            // dtll = dtlama;
            // vv = val
            var val1 = val;
            var dtlama1 = dtlama;
        } else {
            dtll = dtlama.indexOf( ' ' );
            vv = val.indexOf( ' ' );
            if ( vv.length > 0 || dtll.length > 0 ) {
                var val1 = val.replace( /\s|\n/g, '' ).trim();
                var dtlama1 = dtlama.replace( /\s|\n/g, '' ).trim();
                
            } else {
                var val1 = val;
                var dtlama1 = dtlama;
            }
        }

        // var vv = val.indexOf( ' ' ); dtll = dtlama.indexOf( ' ' );
        // console.log('Field ',vv ,dtll,dtlama)

        
        // console.log('OLD DATA ', dtlama1)
        //     console.log('NEW DATA',val1)
        // console.log(dt,'compare ',dtlama1.localeCompare(val1),dtlama1 !== val1 )
        if (dtlama1 !== val1){
            // console.log('Field ',dt )
            // console.log('OLD DATA ', dtlama)
            // console.log('NEW DATA',val)
            hasil=true;
            break;
        }
    }
    return hasil;
}
function checknewdata(arr_field,pos=null){
    var hasil=true;
    for (let i=0;i < arr_field.length;i++){
        var a=arr_field[i];
        var dt=a;
        if (pos !== null){
            dt=pos + "_" + a;
        }
        
        var val = $("#" + dt).val();
        console.log(a, val)
        if (val == ''){
            hasil=false;
            break;
        }
    }
    return hasil;
}

function changetouppercase(arr_field,pos=null){

    for (let i=0;i < arr_field.length;i++){
        var a=arr_field[i];
        var dt=a;
        if (pos !== null){
            dt=pos + "_" + a;
        }
        
        var val = $("#" + dt).val();
        console.log(a, val)
        if ( val !== '' ) {
            if (val !== null)
            $("#" + dt).val(val.toUpperCase());
        }
    }
}

var getAbsoluteUrl = (function() {
    var a;

    return function(url) {
        if(!a) a = document.createElement('a');
        a.href = url;

        return a.href;
    };
})();

function toogleFullscreen(el){
    var fullscreenElement = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement;
    if(fullscreenElement){
        exitFullscreen();  
    }else{
        if(el.requestFullScreen) {
            el.requestFullScreen();
        } else if(el.mozRequestFullScreen) {
            el.mozRequestFullScreen();
        } else if(el.webkitRequestFullScreen) {
            el.webkitRequestFullScreen();
        } 
        Cookies.set('fullscreen',true);
    }
}
function exitFullscreen() {
    var fullscreenElement = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement; 
    if (fullscreenElement) {
        if(document.exitFullscreen) {
            document.exitFullscreen();
          } else if(document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
            } else if(document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        Cookies.remove('fullscreen');
    }
}
function GetWPTDesc4Desc(codwpt, WptDesc4, Symbol = false, Rnav = false )
{
    var hsl = "";
    if ( Rnav == true ) {
        if ( WptDesc4 == "B" || WptDesc4 == "Y" ) {
            hsl = "RNAV_WPT_C_FO"
        }
    } else {
        var ix = codwpt.findIndex( x => x.d43 == WptDesc4 )
        if ( ix !== -1 ) {
            if ( Symbol == true ) {
                hsl=codwpt[ix].symbol
            }else{
                hsl=codwpt[ix].descr
            }
        }

    }
    return hsl;
}
function DrawProcedure( procedure,ats ,newdata=false)
{
    proctext = '';
    if ( newdata == true ) {
        // console.log(procedure)
        procedure.forEach( p =>
        {
            // console.log( p,'DrawProcedure')
                proctext += DrawTransition( p,ats )
            
        } )
    } else {
        seg = procedure.segment;
        // console.log( procedure );
        for (let ix = 0; ix < seg.length; ix++) {
            // console.log( seg[ix].transition[ 0 ] ,'TRANS')
            var atsdraw = false; ptext = false;
            if ( procedure.chart_type == '46' ) {
                ptext = true;
                if ( ix == seg.length - 1 ) {
                    atsdraw = true;
                }
            } else if ( procedure.chart_type == '47' ) {
                ptext = true;
                if ( ix == 0 ) {
                    atsdraw = true;
                }
            }
            if ( ptext == true || ( procedure.chart_type == '45' && seg[ ix ].transition[ 0 ].rt_type == 'Z' ) ) {
                proctext += DrawTransition( seg[ix].transition[0],ats ,atsdraw,procedure.chart_type)
            }
            
        }
        if ( procedure.chart_type == '45' ) {
            proctext += ' or as instructed by ATC.' 
        }
        // seg.forEach( p =>
        // {
        //     console.log(p.transition[0])
        //     proctext += DrawTransition( p.transition[0],ats )
            
        // } )
        
        
    }
    console.log( proctext );
    return proctext;
}

function DrawTransition(transition,ats,drawats,charttype)
{
    var Result = []; awl = []; akh = []; bAts = false;
    var seg = transition.segment; pTtext = '';
    var jml = seg.length;
    for (let ix = 0; ix < jml; ix++) {
        var s = seg;
        if ( charttype == '47' && ix > 0 ) {
            drawats = false;
        }
        // console.log(s)
        switch ( s[ix].path_term ) {
            case 'IF':
                if (charttype !=='45')
                pTtext = IFLeg(s,ix,jml,ats,transition,drawats);
                break;
            case 'CF':
                pTtext += CFLeg(s,ix,jml,ats,transition,drawats,charttype);

                break;
            case 'TF':
                pTtext += TFLeg(s,ix,jml,ats,transition,drawats,charttype);
                break;
            case 'CA':
                pTtext += CALeg(s,ix,jml,ats,transition,drawats,charttype);
                break;
            case 'VA':
                pTtext += VALeg(s,ix,jml,ats,transition,drawats,charttype);
                break;
            case 'DF':
                pTtext += DFLeg(s,ix,jml,ats,transition,drawats,charttype);
                break;
            case 'VI':
                pTtext += VILeg(s,ix,jml,ats,transition,drawats,charttype);
                break;
            case 'VR':
                pTtext += VRLeg(s,ix,jml,ats,transition,drawats);
                break;
            case 'CD':
            case 'VD':
            case 'FD':
                pTtext += CDLeg(s,ix,jml,ats,transition,drawats);
                break;
            case 'AF':
                pTtext += AFLeg(s,ix,jml,ats,transition,drawats);
                break;
            case 'RF':
                pTtext += RFLeg(s,ix,jml,ats,transition,drawats);
                break;
            default:
                break;
        }
    }

   
    return pTtext
}
function FixDescProctext( data,trans )
{
    var ret = '';
    // console.log(data,trans)
    if ( data.navaid.length > 0 ) {
        if ( trans.rnav == 'Y' ) {
            ret = data.navaid[ 0 ].nav_ident
        } else {
            ret = data.navaid[ 0 ].nav_ident + ' '+ data.navaid[ 0 ].definition
        }
        
    } else if ( data.waypoint.length > 0 ) {
        ret = data.waypoint[ 0 ].desc_name.trim();
    } else if ( data.arpt.length > 0 ) {
        ret = data.arpt[ 0 ].arpt_name;
    }

    return ret;
}
function FixRecdnavProctext( data )
{
    var ret = '';
    if ( data.recdnav1.length > 0 ) {
        ret = data.recdnav1[ 0 ].nav_ident
        // if ( radial == false ) {
        //     ret = data.recdnav1[ 0 ].nav_ident + ' ' + data.recdnav1[ 0 ].definition
        // }
       
    } else if ( data.recdils1.length > 0 ) {
        ret = data.recdils1[ 0 ].ils_ident;
    } else if ( data.recdnav2.length > 0 ) {
        ret = data.recdnav2[ 0 ].nav_ident;
        // if (  radial == false ) {
        //     ret = data.recdnav1[ 0 ].nav_ident + ' ' + data.recdnav1[ 0 ].definition
        // }
    } else if ( data.recdils2.length > 0 ) {
        ret = data.recdils2[ 0 ].ils_ident;
    }

    return ret;
}
function IFLeg( Pproc,idx,jml,atsident,trans,drawats)
{
   
    var rslt = '';
    if ( proctext == '' ) {
        if ( atsident == '' ) {
            rslt = 'Arrival  from ' + FixDescProctext( Pproc[ idx ] ,trans);
        } else {
            rslt = 'Arrival  from ' + atsident + ' to ' + FixDescProctext( Pproc[ idx ],trans );
        }
            if ( Pproc[ idx + 1 ].path_term == 'CD' || Pproc[ idx + 1 ].path_term == 'FD' || Pproc[ idx + 1 ].path_term == 'VD') {
                rslt +=  ' intercept RDL ' + numeral(Pproc[idx].theta).format('000') + ' '+ FixRecdnavProctext( Pproc[idx] );
            }
            
        
        if ( Pproc[ idx + 1 ].path_term == 'CD' ) {
            rslt += ' intercept RDL ' + numeral( Pproc[ idx ].theta ).format( '000' ) + ' ' + FixRecdnavProctext( Pproc[ idx ] );
        }
    } else {
            rslt = '';
    }
   return rslt;
   
}


function CFLeg( Pproc,idx,jml,atsident,trans,drawats,charttype)
{
    var rslt = '';
    // console.log('CF', idx , jml)
    if ( charttype == '45' ) {
        if ( idx == 0 ) {
            return 'Climb on course ' + Pproc[idx].mag_crs + '° to ' + FixDescProctext( Pproc[idx],trans );
        } else {
            var hsi= ' proceed to ' + FixDescProctext( Pproc[ idx ], trans );
            if ( Pproc[ idx ].alt1 !== null ) {
                hsi += ' ' + Pproc[ idx ].alt1 + ' ft';
            }
            var wdes = Pproc[ idx ].wd4;
            if ( wdes == 'D' || wdes == 'H' || wdes == 'U' || wdes == 'C' || wdes == 'G' ) {
                hsi += ' for holding';
            }
            return hsi;
        }
    } else {
        if ( idx == 0 ) {
            return 'Climb on course ' + Pproc[ idx ].mag_crs + '° to ' + FixDescProctext( Pproc[ idx ], trans );
        } else {
            rslt = ' to ' + FixDescProctext( Pproc[ idx ], trans );
            if ( drawats == true ) {
                rslt = ' proceed to ' + FixDescProctext( Pproc[ idx ], trans );
                if ( atsident !== '' ) {
                    rslt += ' join ' + atsident + '.';
                
                }
            }
        }
        return rslt;
    }
   
}

function CDLeg( Pproc,idx,jml,atsident,trans,drawats)
{
    // console.log( Pproc[idx] ,'CD PATH');
    // this.rslt = numeral(this.frq).format('0.0[00]')+ ' MHz' //format( "###.0##", this.frq ) + 'MHz'
    var altdd = '';
    switch (Pproc[idx].alt_desc) {
        case '+':
            altd=' at or above '
            break;
        case '-':
            altd=' at or below '
            break;
        case '@':
            altd=' at '
            break;
        default:
            altd=''
            break;
    }
    var pdg = '';
    if ( Pproc[ idx ].sp_lim !== null ) {
        pdg=' (IAS Max '+Pproc[ idx ].sp_lim+ ' KT,'
    }
    if ( Pproc[ idx ].vert_angle !== null ) {
        pva ='min PDG '+ Pproc[ idx ].vert_angle +'%) '
        if ( pdg == '' ) {
            pdg = ' (' + pva;
        } else {
            pdg += pva;
            
        }
    }
    // if ( proctext == '' ) {
    if ( Pproc[ idx ].alt1 !== null ) {
        altdd = altd + Pproc[ idx ].alt1 + ' ft';
    }
    rslt =  ' intercept RDL ' + numeral(Pproc[idx].theta).format('000') + ' '+ FixRecdnavProctext( Pproc[idx] );
        return rslt + pdg + ', at  D' + numeral(Pproc[idx].rho).format('00.0') + ' '+ FixRecdnavProctext( Pproc[idx] ) +  altdd ;
    // } else {
    //     return ''
    // }
    // console.log('CF',rslt)
   
}
function VRLeg( Pproc,idx,jml,atsident,trans,drawats)
{
    // console.log( Pproc[idx] ,'CD PATH');
    // this.rslt = numeral(this.frq).format('0.0[00]')+ ' MHz' //format( "###.0##", this.frq ) + 'MHz'
    var altdd = ''; turn = ''; 
    if ( Pproc[idx].turn_dir == 'R' ) {
        turn = ' turn RIGHT'
    } else if ( Pproc[idx].turn_dir == 'L' ) {
        turn = ' turn LEFT'
    }
    var pdg = '';
    if ( Pproc[ idx ].sp_lim !== null ) {
        pdg=' (IAS Max '+Pproc[ idx ].sp_lim+ ' KT,'
    }
    if ( Pproc[ idx ].vert_angle !== null ) {
        pva ='min PDG '+ Pproc[ idx ].vert_angle +'%) '
        if ( pdg == '' ) {
            pdg = ' (' + pva;
        } else {
            pdg += pva;
            
        }
    }
    
    // if ( proctext == '' ) {
    if ( Pproc[ idx ].alt1 !== null ) {
        altdd = altd + Pproc[ idx ].alt1 + ' ft';
    }
    if ( Pproc[ idx + 1 ].path_term == 'CD' || Pproc[ idx + 1 ].path_term == 'VD' ) {
        rslt = turn + ' track  ' + numeral(Pproc[idx].mag_crs).format('000') + '°';
    } else {
        rslt = turn + ' track  ' + numeral(Pproc[idx].mag_crs).format('000') + '° to intercept RDL ' + numeral(Pproc[idx].theta).format('000') + ' '+ FixRecdnavProctext( Pproc[idx] ) + pdg + ', at  D' + numeral(Pproc[idx].rho).format('00.0') + ' '+ FixRecdnavProctext( Pproc[idx] );
    }
   
    return rslt  +  altdd ;
}

function VILeg( Pproc,idx,jml,atsident,trans,drawats,charttype)
{
    // console.log( Pproc[idx] ,'CD PATH');
    // this.rslt = numeral(this.frq).format('0.0[00]')+ ' MHz' //format( "###.0##", this.frq ) + 'MHz'
    var altdd = ''; turn = ''; 
    if ( Pproc[idx].turn_dir == 'R' ) {
        turn = ' turn RIGHT'
    } else if ( Pproc[idx].turn_dir == 'L' ) {
        turn = ' turn LEFT'
    }
    var pdg = '';
    if ( Pproc[ idx ].sp_lim !== null ) {
        pdg=' (IAS Max '+Pproc[ idx ].sp_lim+ ' KT,'
    }
    if ( Pproc[ idx ].vert_angle !== null ) {
        pva ='min PDG '+ Pproc[ idx ].vert_angle +'%) '
        if ( pdg == '' ) {
            pdg = ' (' + pva;
        } else {
            pdg += pva;
            
        }
    }
    
    // if ( proctext == '' ) {
    if ( Pproc[ idx ].alt1 !== null ) {
        altdd = altd + Pproc[ idx ].alt1 + ' ft';
    }
    if ( charttype == '45' && trans.rnav !== 'Y' ) {
        rslt = turn + ' intercept RDL ' + numeral(Pproc[idx].theta).format('000') + ' '+ FixRecdnavProctext( Pproc[idx] ) + pdg ;
    } else {
        
        rslt = turn + ' track  ' + numeral(Pproc[idx].mag_crs).format('000') + '° to intercept RDL ' + numeral(Pproc[idx].theta).format('000') + ' '+ FixRecdnavProctext( Pproc[idx] ) + pdg + ', at  D' + numeral(Pproc[idx].rho).format('00.0') + ' '+ FixRecdnavProctext( Pproc[idx] );
    }
    return rslt  +  altdd ;
    // rslt = turn + ' track  ' + numeral(Pproc[idx].mag_crs).format('000') + '° '  +  altdd + pdg; ;
    // return rslt ;
    // } else {
    //     return ''
    // }
    // console.log('CF',rslt)
   
}

function AFLeg( Pproc,idx,jml,atsident,trans,drawats)
{
    // console.log( Pproc[idx] );
    var rslt = ''; turn=''
    if ( Pproc[idx].turn_dir == 'R' ) {
        turn = ' turn RIGHT join'
    } else if ( Pproc[idx].turn_dir == 'L' ) {
        turn = ' turn LEFT join'
    }
    var altdd = turn + ' D' + numeral(Pproc[idx].rho).format('00.0') + ' '+ FixRecdnavProctext( Pproc[idx] ) + ' DME Arc proceed to ' + FixDescProctext( Pproc[idx],trans );
    if ( drawats==true ) {
        if ( atsident !== '' ) {
            altdd += ' join ' + atsident + '.';
            
        } else {
            altdd += '.';
        }
    }
    if ( proctext == '' ) {
        return altdd;
    } else {
        return ''
    }
    // console.log('CF',rslt)
   
}

function TFLeg( Pproc, idx ,jml,atsident,trans,drawats,charttype)
{
    var rslt = '';
    // console.log( Pproc[ idx ], trans,idx ,jml )
    rslt = ' to ' + FixDescProctext( Pproc[ idx ],trans );
    if ( trans.rnav == 'N' && idx == (jml-1) && trans.definition=='Runway Transition') {
        rslt = ' proceed to ' + FixDescProctext( Pproc[ idx ], trans );
        if ( Pproc[ idx ].alt1 !== null ) {
            rslt += ' '+ Pproc[ idx ].alt1 + ' ft'
        }
    }
    if ( idx < jml - 1 ) {
        if ( Pproc[ idx + 1 ].path_term == 'CD' || Pproc[ idx + 1 ].path_term == 'FD' || Pproc[ idx + 1 ].path_term == 'VD') {
            rslt +=  ' intercept RDL ' + numeral(Pproc[idx].theta).format('000') + ' '+ FixRecdnavProctext( Pproc[idx] );
        }
        
    }
    if ( charttype == '45' ) {
        switch (Pproc[idx].alt_desc) {
            case '+':
                altd=' at or above '
                break;
            case '-':
                altd=' at or below '
                break;
            case '@':
                altd=' at '
                break;
            default:
                altd = '';
                break;
        }
        rslt = ' to ' + FixDescProctext( Pproc[ idx ], trans ) + ' ' + altd + Pproc[ idx ].alt1 + ' ft';
        var wdes = Pproc[ idx ].wd4;
        if ( wdes == 'D' || wdes == 'H' || wdes == 'U' || wdes == 'C' || wdes == 'G' ) {
            rslt += ' for holding';
        }
    }
    if ( drawats==true ) {

        if ( atsident !== '' ) {
            rslt += ' join ' + atsident + '.';
            
        } 
    }
    return rslt;
    
}
function VALeg( Pproc,idx,jml,atsident,trans,drawats,charttype )
{
    // console.log(trans)
    if ( proctext == '' ) {
        switch (Pproc[idx].alt_desc) {
            case '+':
                altd=' at or above '
                break;
            case '-':
                altd=' at or below '
                break;
            case '@':
                altd=' at '
                break;
            default:
                altd=' to '
                break;
        }
        if ( trans.rnav == 'Y' ) {
            var hsl='Climb on heading ' + Pproc[ idx ].mag_crs + '° ' + altd + Pproc[ idx ].alt1 + ' ft';
            
        } else {
            var hsl='Take off Runway ' + trans.rwy_trans + ' ' + altd + Pproc[ idx ].alt1 + ' ft';
        }
        if ( charttype == '45' ) {
            if ( altd == ' to ' ) {
                var hsl='Climb straight ahead until ' + Pproc[ idx ].alt1 + ' ft';
            } else {
                
                var hsl='Climb straight ahead ' + altd + Pproc[ idx ].alt1 + ' ft';
            }
        }
        return hsl;
    } else {
        return ''
    }
   
    
}

function CALeg( Pproc,idx,jml,atsident,trans,drawats,charttype )
{
    if ( proctext == '' ) {
        switch (Pproc[idx].alt_desc) {
            case '+':
                altd=' at or above '
                break;
            case '-':
                altd=' at or below '
                break;
            case '@':
                altd=' at '
                break;
            default:
                altd=' to '
                break;
        }
        if ( charttype == '45') {
            if ( trans.rnav == 'Y' ) {
                return 'Climb on course ' + Pproc[idx].mag_crs + '° until ' + Pproc[idx].alt1 + ' ft';
            } else {
                if ( altd == ' to ' ) {
                    return 'Climb straight ahead until ' + Pproc[ idx ].alt1 + ' ft';
                } else {
                    
                    return 'Climb straight ahead ' + altd + Pproc[ idx ].alt1 + ' ft';
                }
                // return 'Climb straight ahead ' + altd + Pproc[ idx ].alt1 + ' ft';
            }
        } else {
            return 'Climb on course ' + Pproc[idx].mag_crs + '° ' + altd + Pproc[idx].alt1 + ' ft';
            
        }
    } else {
        return ''
    }
   
    
}

function DFLeg( Pproc,idx,jml,atsident,trans,drawats,charttype )
{
    var rslt = ''; turn = ''
    switch (Pproc[idx].alt_desc) {
        case '+':
            altd=' at or above '
            break;
        case '-':
            altd=' at or below '
            break;
        case '@':
            altd=' at '
            break;
        default:
            altd=''
            break;
    }
    if ( Pproc[idx].turn_dir == 'R' ) {
        turn = ' turn RIGHT'
    } else if ( Pproc[idx].turn_dir == 'L' ) {
        turn = ' turn LEFT'
    }

    var pdg = '';
    if ( Pproc[ idx ].sp_lim !== null ) {
        pdg=' (IAS Max '+Pproc[ idx ].sp_lim+ ' KT, '
    }
    if ( Pproc[ idx ].vert_angle !== null ) {
        pva ='min PDG '+ Pproc[ idx ].vert_angle +'%) '
        if ( pdg == '' ) {
            pdg = ' (' + pva;
        } else {
            pdg += pva;
            
        }
    }

    if ( trans.rnav == 'Y' ) {
        
        rslt=turn + ' direct to ' + FixDescProctext( Pproc[idx] ,trans) +  pdg;
    } else {
        rslt=turn + pdg + ' to overhead ' + FixDescProctext( Pproc[idx] ,trans);
    }
    if ( drawats==true ) {
        if ( atsident !== '' ) {
            rslt += ' join ' + atsident + '.';
            
        } 
    }
    // console.log(Pproc[ idx-1 ].path_term,'Pproc[ idx-1 ].path_temp')
    if ( charttype == '45' ) {
        if (Pproc[ idx - 1 ].path_term == 'IF') {
            rslt = 'At MAPt' + turn + ' direct to ' + FixDescProctext( Pproc[ idx ], trans );
            
        } else {
            if ( idx == jml - 1 ) {
                rslt =  ' to intercept RDL ' + numeral(Pproc[idx].theta).format('000') + ' '+ FixRecdnavProctext( Pproc[idx] ) + ' proceed to ' + FixDescProctext( Pproc[ idx ], trans );
                
            } else {
                if ( Pproc[ idx ].wd2 == 'Y' ) {
                    rslt =  turn + ' to overhead ' + FixDescProctext( Pproc[ idx ], trans );
                } else {
                    rslt =  turn + ' direct to ' + FixDescProctext( Pproc[ idx ], trans );
                    
                }
            }
        }
        
    }
    if ( Pproc[ idx ].alt1 !== null ) {
        rslt +=' ' + altd + Pproc[idx].alt1 + ' ft'
    }
    if (charttype == '45' ) {
        var wdes = Pproc[ idx ].wd4;
        if ( wdes == 'D' || wdes == 'H' || wdes == 'U' || wdes == 'C' || wdes == 'G' ) {
            rslt += ' for holding';
        }
    }
    if ( Pproc[ idx ].sp_lim !== null  && charttype == '45' ) {
        rslt +=' (IAS Max '+Pproc[ idx ].sp_lim+ ' KT during turning missed approach)'
    }
    return rslt;
   
    
}

function RFLeg( Pproc,idx,jml,atsident,trans,drawats )
{
    var rslt = ''; turn=''
    if ( Pproc[idx].turn_dir == 'R' ) {
        turn = ', then turn RIGHT'
    } else if ( Pproc[idx].turn_dir == 'L' ) {
        turn = ', then turn LEFT'
    }
    turn  += ' to ' + FixDescProctext( Pproc[idx] ,trans);
    if ( drawats==true ) {
        if ( atsident !== '' ) {
            turn += ' join ' + atsident + '.';
            
        } 
    }
    return turn;
   
    
}

function GetPointWithIntersectionLinewithCircle(cord1,Bearing , recdnavcord, Dist )
{
    // console.log( cord1, Bearing, recdnavcord, Dist );
    var Refcord = getpoint2coord( cord1.Decimal[1],  cord1.Decimal[0], Bearing, 30 );
    // console.log( cord1,Refcord );
    var hsl = GetIntLinewithCircle( recdnavcord, Dist, cord1, Refcord );
    return hsl
}

function GetIntLinewithCircle( CenterofPoint, Radius, Poin1Line, Poin2Line )
{
    var hsl = '';
    var ttrk = Getbearing( Poin1Line.Decimal[ 1 ], Poin1Line.Decimal[ 0 ], Poin2Line.Decimal[ 1 ], Poin2Line.Decimal[ 0 ] )
    var hasil = FindLineCircleIntersections( CenterofPoint, Radius, Poin1Line, Poin2Line )
    // Dim ttrk As New cTrack
    // ttrk.GetBearingByCoord( Poin1Line, Poin2Line )
    var bearing = ttrk.TrackOutReal;
    hasil.forEach( p =>
        {
            var cc = p.split( ' ' );
            // console.log( cc ,'cc ',bearing);

        var ttrk1 = Getbearing( Poin1Line.Decimal[ 1 ], Poin1Line.Decimal[ 0 ], parseFloat( cc[ 0 ] ), parseFloat( cc[ 1 ] ) )
        var ttrk2 = Getbearing( CenterofPoint.Decimal[ 1 ], CenterofPoint.Decimal[ 0 ], parseFloat( cc[ 0 ] ), parseFloat( cc[ 1 ] ) )
        if ( ttrk1.TrackOutReal >= ( bearing - 1 ) && ttrk1.TrackOutReal <= ( bearing + 1 ) ) {
            hsl = SetCoordinatebyDecimal( parseFloat( cc[ 1 ] ), parseFloat( cc[ 0 ] ) )
            // hsl = p;
            // console.log(p,ttrk1,'ttrk1',ttrk2)
        }
    })
    // For Each cc As ccordinate In lstc
    //     Dim ttrk1 As New cTrack
    //     ttrk1.GetBearingByCoord( Poin1Line, cc )
    //     If ttrk1.TrackOutReal >= ( Bearing - 1 ) And ttrk1.TrackOutReal <= ( Bearing + 1 ) Then
    //     hsl = New ccordinate
    //     hsl = cc
    //     End If
    // Next
    return hsl;
}
function FindLineCircleIntersections(circleCord,radius, Point1, Point2){
    var Hsl = [];
    var ix1; ix2; iy1; iy2;
    var rad = radius / 60;
    var cx = circleCord.Decimal[0];
    var cy = circleCord.Decimal[1];
    var x1 = Point1.Decimal[0];
    var y1 = Point1.Decimal[1];
    var x2 = Point2.Decimal[0];
    var y2 = Point2.Decimal[1];
   

    var dx = x2 - x1;
    var dy = y2 - y1;

    var A = dx * dx + dy * dy;
    var B = 2 * ( dx * ( x1 - cx ) + dy * ( y1 - cy ) );
    var C = ( x1 - cx ) * ( x1 - cx ) + ( y1 - cy ) * ( y1 - cy ) - rad * rad;

    var det = B * B - 4 * A * C;
    if ( ( A <= 0.0000001 ) || ( det < 0 ) ) {
        // ' No real solutions.
        return Hsl;
    } else if ( det == 0 ) {
        // ' One solution.
        var t = -B / ( 2 * A )
        var ix1 = x1 + t * dx
        var iy1 = y1 + t * dy
        //    var Cnt1 As New ccordinate
        //     Cnt1.SetCoordinatebyDecimal( iy1, ix1 )

        Hsl.push( iy1 +' '+ ix1 );

    } else {
        // ' Two solutions.
        var t = ( -B + Math.sqrt( det ) ) / ( 2 * A );
        var ix1 = x1 + t * dx;
        var iy1 = y1 + t * dy;
        Hsl.push( iy1 +' '+ ix1 );

        var t = ( -B - Math.sqrt( det ) ) / ( 2 * A );
        var ix2 = x1 + t * dx;
        var iy2 = y1 + t * dy;
        Hsl.push( iy2 +' '+ ix2 );
        // console.log(ix1,iy1,ix2,iy2)
    }
return Hsl
}
// var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
function humanDate(dt,param=null){
  if(dt.length > 0){
      let ret;
      const d = new Date(dt);
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      switch(param){
        case 'y' : ret= `${ye}` ;break;
        case 'm' : ret= `${mo} ${ye}` ;break;
        case 'd' : ret= `${mo} ${da}, ${ye}` ;break;
      }
    return ret;
  } 
}
// globe animation
function runGlobe() {
    var urlThree = "js/threejs.js"; 
    $.getScript(urlThree).done(function(){ 
        $.getScript( "js/orbit-controls.js" ).done(function(){ 
            const container = document.getElementById("globe");
            const canvas = container.getElementsByTagName("canvas")[0];

            const globeRadius = 100;
            const globeWidth  = 4098 / 2;
            const globeHeight = 1968 / 2;

            function convertFlatCoordsToSphereCoords(x, y) {
                let latitude = ((x - globeWidth) / globeWidth) * -180;
                let longitude = ((y - globeHeight) / globeHeight) * -90;
                latitude = (latitude * Math.PI) / 180;
                longitude = (longitude * Math.PI) / 180;
                const radius = Math.cos(longitude) * globeRadius;

                return {
                    x: Math.cos(latitude) * radius,
                    y: Math.sin(longitude) * globeRadius,
                    z: Math.sin(latitude) * radius
                };
            }

            function makeMagic(points) {
                const {
                    width,
                    height
                } = container.getBoundingClientRect();

                // 1. Setup scene
                const scene = new THREE.Scene();
                // 2. Setup camera
                const camera = new THREE.PerspectiveCamera(45, width / height);
                // 3. Setup renderer
                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: true
                });
                renderer.setSize(width, height);
                // 4. Add points to canvas
                // - Single geometry to contain all points.
                const mergedGeometry = new THREE.Geometry();
                // - Material that the dots will be made of.
                const pointGeometry = new THREE.SphereGeometry(0.5, 1, 1);
                const pointMaterial = new THREE.MeshBasicMaterial({
                    color: "#989db5",
                });

                for (let point of points) {
                    const {
                        x,
                        y,
                        z
                    } = convertFlatCoordsToSphereCoords(
                        point.x,
                        point.y,
                        width,
                        height
                    );

                    if (x && y && z) {
                        pointGeometry.translate(x, y, z);
                        mergedGeometry.merge(pointGeometry);
                        pointGeometry.translate(-x, -y, -z);
                    }
                }

                const globeShape = new THREE.Mesh(mergedGeometry, pointMaterial);
                scene.add(globeShape);

                container.classList.add("peekaboo");

                // Setup orbital controls
                camera.orbitControls = new THREE.OrbitControls(camera, canvas);
                camera.orbitControls.enableKeys = false;
                camera.orbitControls.enablePan = false;
                camera.orbitControls.enableZoom = false;
                camera.orbitControls.enableDamping = false;
                camera.orbitControls.enableRotate = true;
                camera.orbitControls.autoRotate = true;
                camera.position.z = -265;

                function animate() {
                    // orbitControls.autoRotate is enabled so orbitControls.update
                    // must be called inside animation loop.
                    camera.orbitControls.update();
                    idGlobe = requestAnimationFrame(animate);
                    renderer.render(scene, camera);
                }
                animate();
            }

            function hasWebGL() {
                const gl =
                    canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
                if (gl && gl instanceof WebGLRenderingContext) {
                    return true;
                } else {
                    return false;
                }
            }

            function initGlobe() {
                if (hasWebGL()) {
                    window
                    window.fetch(
                            '/js/points.json'
                            )
                        .then(response => response.json())
                        .then(data => {
                            makeMagic(data.points);
                        });
                }
            }
            initGlobe();
        }); 
    });
}