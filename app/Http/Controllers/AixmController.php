<?php

namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Request as Req;
use Illuminate\Http\Request;
use Auth;
use \Illuminate\Support\Facades\Route;
// use App\Models\Api\Airport;
// use App\Models\Api\Airspace;
// use App\Models\Api\Airport;
// use App\Models\Api\Airport;
use Session;
use Image;
use File;

class AixmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $path;
    private $NumId,$gmlID,$pNIL,$pEndtime,$endfile,$xmlcontent,$rwy;
    public function __construct()
    {
        $this->NumId = 100000;
        $this->middleware('auth');
        $this->gmlID = "IWISHAerossID";
        $this->pNIL = " xsi:nil=".chr(34)."true".chr(34).">";
        $this->pEndtime= " indeterminatePosition=".chr(34)."unknown".chr(34).">";
        $this->endfile = "</aixm-message:AIXMBasicMessage>";
        $this->xmlcontent = '';
        $this->rwy=[];
                //DEFINISIKAN PATH
		$this->path = public_path('upload/publication/'); 
    }

    public function generateaixm()
    {

        $originalInput=Req::input();
        $user = Auth::user();
        $req=$originalInput;
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // dd($originalInput);
        $method='GET';
        if ($req['Airport']==true){
            $code=$this->BuatICaoCode();
            $arptlist=[];$rwylist=[];
            $arpt = getDataApi($originalInput,'/api/airports?ctry=ID&deleted=0&sort=arpt_name:asc');
            $wa=0;$wi=0; $txt = '';
            for ($i=0; $i < count($arpt) ; $i++) {
                if ($arpt[$i]->vol !== 5){
                    $arp=[];
                    $arp=$arpt[$i];
    
                    if ($arp->geom !== null){
                        // var_dump($arp);
                        unset($date1);unset($lat);unset($lon);unset($mv);
                        $date1 = date('Y-m-d');
                        $lat = $arp->geom->coordinates[1];
                        $lon=$arp->geom->coordinates[0];
                        if ($lat ==0 && $lon ==0){
                            $arp->magvar= 0;
                            $arp->changeyear=0;
                        }else{
                            $mv=GetMagvar($lon,$lat,$date1,$alt,);
                            // dd($mv);
                            $arp->magvar= round($mv->dec,2);
                            $arp->changeyear= round($mv->cy,2);
                        }
                    }
                
                    if ($arpt[$i]->icao=='WA'){
                            $arp->designator= $arpt[$i]->icao.$code[$wa];
    
                        $wa++;
                    }else  if ($arpt[$i]->icao=='WI'){
                            $arp->designator= $arpt[$i]->icao.$code[$wi];
                        $wi++;
                    
                    }else{
                        $arp->designator=$arpt[$i]->icao;
                    }
                
                
                    $fle = fopen('images/xml/airport.xml','r');
                    // var_dump(count($dt->runways),$dt->icao);
                    while ($line = fgets($fle)) {
                        $hh = $this->processreplace('arpt', $arp,$line, $this->NumId );
                        if ( $hh !== 'undefined' || $hh !== '' ) {
                            // console.log( hh )
                            $this->NumId += 1;
                            $NumT = $this->NumId;
                            if ( $hh == $line ) {
                                $this->NumId = $NumT;
                            }
                            if ( $txt == '' ) {
                                // $txt = $hh.'\n';
                                $txt = $hh;
                            } else {
                                // $txt = $txt.$hh.'\n';
                                $txt = $txt.$hh;
                            }
                            
                        }
                    }
                    fclose($fle);
                    if (!empty($arp->runways)) {
                        for ($x = 0; $x < count($arp->runways); $x++ ){
                            $fle = fopen('images/xml/rwy.xml','r+');
                            while ($line = fgets($fle)) {
                            // for ($i=0; $i < count($modelLines) ; $i++) { 
                                $hh = $this->processreplace('RWY', $arp->runways[$x],$line, $this->NumId );
                                // console.log( hh )
                                if ($hh !== '' ) {
                                    // console.log( hh )
                                    $this->NumId += 1;
                                    $NumT = $this->NumId;
                                    if ( $hh == $line ) {
                                        $this->NumId = $NumT;
                                    }
                                
                                    $txt =$txt.$hh;
                                    
                                    
                                }
                            }
                            fclose($fle);
                            if (!empty( $arp->runways[$x]->physicals)) {
                                $phy=$arp->runways[$x]->physicals;
                                for ($j = 0; $j < count($phy); $j++ ){
                                    $fle1 = fopen('images/xml/rwy_direct.xml','r+');
                                    while ($line = fgets($fle1)) {
                                        $hh = $this->processreplace( 'RWYDIR', $phy[$j], $line, $this->NumId );
                                        if ($hh !== '' ) {
                                            // console.log( hh )
                                            $this->NumId += 1;
                                            $NumT = $this->NumId;
                                            if ( $hh == $line ) {
                                                $this->NumId = $NumT;
                                            }
                                                $txt =$txt.$hh;
                                            
                                            
                                        }
                                    }
                                    fclose($fle1);
                                    $fle2 = fopen('images/xml/rwy_centerline.xml','r+');
                                    while ($line = fgets($fle2)) {
                                        $hh = $this->processreplace( 'RWYCENT', $phy[$j], $line, $this->NumId );
                                        if ($hh !== '' ) {
                                            // console.log( hh )
                                            $this->NumId += 1;
                                            $NumT = $this->NumId;
                                            if ( $hh == $line ) {
                                                $this->NumId = $NumT;
                                            }
                                                $txt =$txt.$hh;
                                            
                                            
                                        }
                                    }
                                    fclose($fle2);
                                }
                                
                            }

                                // }
                        }
                                // unset($fle);
                    }
                    // array_push($arptlist,$arp);
                }
                // var_dump($arpt[$i]->vol);
            }

            // unset($hasil); unset($fle);
            // $hasil='';
        
            // $fle = 'airport.xml';
            // $hasil= $this->processaixm($arptlist,$fle,'arpt');

            // $fle ='rwy.xml';
            // // dd(fread($fle,filesize('images/xml/rwy.xml')));
            // // dd( $fle);
            // $hasil =$hasil.$this->processaixm($rwylist,$fle,'RWY');


            //     $fle ='rwy_direct.xml';
            //     $hasil = $hasil.$this->processaixm($rwylist,$fle,'RWYDIR');
            //     // unset($fle);

                
            //     $fle ='rwy_centerline.xml';
            //     $hasil = $hasil.$this->xmlcontent.$this->processaixm($rwylist,$fle,'RWYCENT');

            $this->xmlcontent =$txt;
        }
       
        dd('hasilllll', $this->xmlcontent);
        // $instance->sortBy('va_name');
        // echo "<pre>"; print_r($instance); exit;
        // if($instance->status=='success'){
        //     $data['airspace'] = $instance->data;
        // }
        return view('pages.aixm.aixm',$data);
    }
    function getRwy($rwy,$file, $table){
        $hasil='';
        // dd(count($rwy));
        for ($x = 0; $x < count($rwy); $x++ ){
            $hhsl='';
            if ( $table == 'RWY' ) {
                $hhsl=$this->processRwy($rwy[$x],$file,$table);
            }else{
                // for ($j = 0; $j < 2; $j++ ){
                    $hhsl=$this->processRwy($rwy[$x]->physicals[0],$file,$table);
                    $hhsl=$hhsl.$this->processRwy($rwy[$x]->physicals[1],$file,$table);
                // }
            }
            if ($hasil==''){
                $hasil=$hhsl;
            }else{
                $hasil=$hasil.$hhsl;
            }
            
        }
        return $hasil;
    }
    function processRwy($rwy,$file, $table){
        $hasil='';
        $fle = fopen('images/xml/'.$file,'r');
        while ($line = fgets($fle)) {

            $hh = $this->processreplace( $table, $rwy,$line, $this->NumId );
            if ($hh !== '' ) {
                // console.log( hh )
                $this->NumId += 1;
                $NumT = $this->NumId;
                if ( $hh == $line ) {
                    $this->NumId = $NumT;
                }
                if ( $hasil == '' ) {
                    $hasil = $hh;
                } else {
                    $hasil =$hasil.$hh;
                }
                
            }
        }
        fclose($fle);
        return $hasil;
    }
    function processaixm( $objdata, $xmlfile, $table)
    {
        $txt = '';
        if ( $table == 'RWY' ) {
            // dd($this->rwy);
            foreach ($objdata as $key => $dt) {
                // if (!empty($dt->runways)) {
                    // $txt = $this->getRwy($dt->runways,$xmlfile, $table);
                    // for ($x = 0; $x < count($dt->runways); $x++ ){
                        // array_push($this->rwy,$dt->runways[$x]);
                        $fle = fopen('images/xml/'.$xmlfile,'r');
                        while ($line = fgets($fle)) {
                        // for ($i=0; $i < count($modelLines) ; $i++) { 
                            $hh = $this->processreplace( $table, $dt,$line, $this->NumId );
                            // console.log( hh )
                            if ($hh !== '' ) {
                                // console.log( hh )
                                $this->NumId += 1;
                                $NumT = $this->NumId;
                                if ( $hh == $line ) {
                                    $this->NumId = $NumT;
                                }
                                if ( $txt == '' ) {
                                    $txt = $hh;
                                } else {
                                    $txt =$txt.$hh;
                                }
                                
                            }
                        }
                        fclose($fle);
                    }
                // }
            // }
        } else if ( $table == 'RWYDIR' || $table == 'RWYCENT' ) {
            // dd($this->rwy);
            foreach ($objdata as $key => $dt) {
                
                    if (!empty($dt->physicals)) {
                        // for ($x = 0; $x < $dt->runways; $x++ ){
                            // dd ($dt->runways[$x]->physicals);
                            // $txt = $this->getRwy($dt->runways,$xmlfile, $table);
                            // if (!empty($dt->runways[$x]->physicals)){
                            //     // dd($dt->runways[$x]);
                                for ($j = 0; $j < count($dt->physicals); $j++ ){
                                    $fle = fopen('images/xml/'.$xmlfile,'r');
                                    while ($line = fgets($fle)) {
                                        $hh = $this->processreplace( $table, $dt->physicals[$j], $line, $this->NumId );
                                        if ($hh !== '' ) {
                                            // console.log( hh )
                                            $this->NumId += 1;
                                            $NumT = $this->NumId;
                                            if ( $hh == $line ) {
                                                $this->NumId = $NumT;
                                            }
                                            if ( $txt == '' ) {
                                                $txt = $hh;
                                            } else {
                                                $txt =$txt.$hh;
                                            }
                                            
                                        }
                                    }
                                    fclose($fle);
                                }

                            // }
                        }
                    }
                // }
            
        } else {
            foreach ($objdata as $key => $dt) {
                
               
                $fle = fopen('images/xml/'.$xmlfile,'r');
                // var_dump(count($dt->runways),$dt->icao);
                while ($line = fgets($fle)) {
                    $hh = $this->processreplace( $table, $dt,$line, $this->NumId );
                    if ( $hh !== 'undefined' || $hh !== '' ) {
                        // console.log( hh )
                        $this->NumId += 1;
                        $NumT = $this->NumId;
                        if ( $hh == $line ) {
                            $this->NumId = $NumT;
                        }
                        if ( $txt == '' ) {
                            // $txt = $hh.'\n';
                            $txt = $hh;
                        } else {
                            // $txt = $txt.$hh.'\n';
                            $txt = $txt.$hh;
                        }
                        
                    }
                }
                fclose($fle);
                
               

                // $modelLines = explode('\n',$xmlfile);
                // dd('FILEEEE',$modelLines);
                // for ($i=0;$i<count($modelLines);$i++) {
                //     dd($modelLines[$i]);
                   
                // }
            } 
            
        }
        return $txt;

    }
    function processreplace( $tbl, $datalist, $xmlstring, $number )
    {
        $hsl = '';
            switch ($tbl ) {
            case "header":
                $hsl = $this->replaceHeader($xmlstring);
                break;
            case "arpt":
                $hsl = $this->replaceXMLARPT( $xmlstring, $datalist, $number );
                break;
            case "wpt":
                $hsl = $this->replaceXMLWPT( $xmlstring, $datalist, $number );
                break;
            case "navaid":
                $hsl = $this->replaceXMLNavaid( $xmlstring, $datalist, $number );
                break;
            case "DME":
                $hsl = $this->replaceXMLDME( $xmlstring, $datalist, $number );
                break;
            case "ILSDME":
                $hsl = $this->replaceXMLILSDME( $xmlstring, $datalist, $number );
                break;
            case "ILS":
                $hsl = $this->replaceXMLILS( $xmlstring, $datalist, $number );
                break;
            case "LLZ":
                $hsl = $this->replaceXMLLOCATOR( $xmlstring, $datalist, $number );
                break;
            case "GP":
                $hsl = $this->replaceXMLGP( $xmlstring, $datalist, $number );
                break;
            case "NDB":
                $hsl = $this->replaceXMLNDB( $xmlstring, $datalist, $number );
                break;
            case "VOR":
                $hsl = $this->replaceXMLVOR( $xmlstring, $datalist, $number );
                break;
            case "TACAN":
                $hsl = $this->replaceXMLTACAN( $xmlstring, $datalist, $number );
                break;
            case "TWY":
                $hsl = $this->replaceXMLTWYElement( $xmlstring, $datalist, $number );
                break;
            case "RWY":
                $hsl = $this->replaceXMLRWY( $xmlstring, $datalist, $number );
                break;
            case "RWYDIR":
                $hsl = $this->replaceXMLRWYDirection( $xmlstring, $datalist, $number );
                break;
            case "RWYCENT":
                $hsl = $this->replaceXMLRWYTHR( $xmlstring, $datalist, $number );
                break;
            case "ASP":
                $hsl = $this->replaceXMLASP( $xmlstring, $datalist, $number );
                break;
            case "SUAS":
                $hsl = $this->replaceXMLSUAS( $xmlstring, $datalist, $number );
                break;
            case "ATS":
                $hsl = $this->replaceXMLRoutes( $xmlstring, $datalist, $number );
                break;
            default:
        }
        return $hsl;
    }
    function replaceXMLARPT( $errString, $pNav, $num )
    {
        // dd($errString);
        $inpt = $errString;
        $pTs = $pNav;
        // dd($pTs->designator,$inpt);
        $hsl='';
        $pelev = $pTs->elev;
        // if ( $pTs->Elev == '' ) {
        //     pelev = "0";

        if ( $pTs->eff_date == '' || $pTs->eff_date == null ) {
            $pEffDate =date('Y-m-d');
        } else {
            $pEffDate =  $pTs->eff_date;// new Date( $pTs->eff_date ).toISOString();
        }
            $yy = date('Y');
        if ( $pTs->geom == null || $pTs->geom == '' ) {
            $dlat = 0;
            $dlon = 0;
        } else {
            $dlon = $pTs->geom->coordinates[ 0 ];
            $dlat = $pTs->geom->coordinates[ 1 ];
        }
        $pCordNav = $dlon.' '.$dlat;
        if ( $pTs->type == '5' ) {
            $pJoin = "JOINT";
        } else if ( $pTs->type == '3' ) {
            $pJoin = "MIL";
        } else {
            $pJoin = "CIVIL";
        }

        if ( $pTs->ctry == 'ID' ) {
            if ( $dlon > 135 ) {
                $pUomTa = "FT";
                $pUomTl = "FL";
                $pTa = "18000";
                $pTl = "180";
            } else {
                $pUomTa = "FT";
                $pUomTl = "FL";
                $pTa = "11000";
                $pTl = "130";
            }
      
        } else {
            
            if (substr($pTs->ta, 0, 2 ) == 'FL' ) {
                $pUomTa = "FL";
                $pTa = substr($pTs->ta, - 2 );
            } else {
                $pUomTa = "FT";
                $pTa = $pTs->ta;
            }
    
            if (substr($pTs->tl, 0, 2 ) == 'FL' ) {
                $pUomTl = "FL";
                $pTl =substr($pTs->tl, - 2 );
            } else {
                $pUomTl = "FT";
                $pTl = $pTs->tl;
            }
        }

       $pTraffic = $pTs->type_of_traffic;
        if ( $pTraffic == "BOTH" || $pTraffic == "IFR/VFR" || $pTraffic == "IFR / VFR") { 
            $pTraffic = "ALL"; 
        }
        if ( $pTraffic == '' || $pTraffic == null ) { 
            $pTraffic = "VFR"; 
        }
        $pName =strtoupper($pTs->arpt_name);
    
        //$a = Guid.NewGuid().ToString
        // console.log(pTs)
        // dd($inpt);
        if ( strpos($inpt, "pAixm01ID" )) {
            $hsl = str_replace( "pAixm01ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixmID" )) {
            $hsl = str_replace( "pAixmID", $pTs->arpt_ident ,$inpt);
        } else if ( strpos($inpt, "pAixm02ID" )) {
            $hsl = str_replace( "pAixm02ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixm03ID" )) {
            $hsl = str_replace( "pAixm03ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixmTime" )) {
            $hsl = str_replace( "pAixmTime", $pEffDate ,$inpt);
        } else if ( strpos($inpt, "pAixmDesig" )) {
            // dd($pTs->designator);
            // if ( strlen($pTs->icao) == 2 ) {
                $hsl = str_replace( "pAixmDesig", $pTs->designator ,$inpt);
                // dd($hsl);
            // } else {
            //     $hsl = str_replace( "pAixmDesig", $pTs->icao ,$inpt);
            // }
        } else if ( strpos($inpt, "pAixmName" )) {
            $hsl = str_replace( "pAixmName", $pName ,$inpt);
        } else if ( strpos($inpt, "pAixmIcao" )) {
            if ( strlen($pTs->icao) == 2 ) {
                $hsl = str_replace( ">pAixmIcao",  $this->pNIL ,$inpt);
            } else {
                $hsl = str_replace( "pAixmIcao", $pTs->icao ,$inpt);
            }
            // $hsl = str_replace( "pAixmIcao", $pTs->icao )
        } else if ( strpos($inpt, "pAixmIata" )) {
            if ( $pTs->iata == '' || $pTs->iata == null ) {
                $hsl = str_replace( ">pAixmIata", $this->pNIL ,$inpt);
            } else {
                $hsl = str_replace( "pAixmIata", $pTs->iata ,$inpt);
            }
        } else if ( strpos($inpt, "pAixmType" )) {
            $hsl = str_replace( "pAixmType", "AD" ,$inpt);
        } else if ( strpos($inpt, "pAixmTemp" )) {
            $hsl = str_replace( ">pAixmTemp", $this->pNIL ,$inpt);
        } else if ( strpos($inpt, "pAixmYN1" )) {
            $hsl = str_replace( "pAixmYN1", "NO" ,$inpt);
        } else if ( strpos($inpt, "pAixmJoint" )) {
            $hsl = str_replace( "pAixmJoint", $pJoin ,$inpt);
        } else if ( strpos($inpt, "pAixmElev" )) {
            if ( $pelev == '' || $pelev == null ) {
                $hsl = str_replace( ">pAixmElev", $this->pNIL ,$inpt);
            } else {
                $hsl = str_replace( "pAixmElev", $pelev,$inpt);
            }
        } else if ( strpos($inpt, "pAixmDMag" )) {
            $hsl = str_replace( "pAixmDMag", $pTs->magvar ,$inpt);
        } else if ( strpos($inpt, "pAixmMagYear" ) ==true) {
            $hsl = str_replace( "pAixmMagYear", $yy ,$inpt);
        } else if ( strpos($inpt, "pAixmMagchange" ) ) {
            $hsl = str_replace( "pAixmMagchange", $pTs->changeyear ,$inpt);
        } else if ( strpos($inpt, "pAixmYN1" ) ) {
            $hsl = str_replace( "pAixmYN1", "YES" ,$inpt);
        } else if ( strpos($inpt, "paixmUomTA" ) ) {
           $hUp = str_replace( "paixmUomTA", $pUomTa ,$inpt);
            $hsl = str_replace( "pAixmTA", $pTa,$hUp );
        } else if ( strpos($inpt, "paixmUomTL" ) ) {
           $hUp1 = str_replace( "paixmUomTL", $pUomTl ,$inpt);
            $hsl = str_replace( "pAixmTL", $pTl,$hUp1 );
        } else if ( strpos($inpt, "pAixm04ID" ) ) {
            $hsl = str_replace( "pAixm04ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixmCity" ) ) {
            if ( $pTs->city_name == '' || $pTs->city_name == null ) {
                $hsl = str_replace( ">pAixmCity", $this->pNIL ,$inpt);
            } else {
                $hsl = str_replace( "pAixmCity",  strtoupper($pTs->city_name),$inpt);
            }
        } else if ( strpos($inpt, "pAixm05ID" ) ) {
            $hsl = str_replace( "pAixm05ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixmCord" ) ) {
            $hsl = str_replace( "pAixmCord", $pCordNav ,$inpt);
        } else if ( strpos($inpt, "pAixm06ID" ) ) {
            $hsl = str_replace( "pAixm06ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixm10ID" ) ) {
            $hsl = str_replace( "pAixm10ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixm07ID" ) ) {
            $hsl = str_replace( "pAixm07ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixm08ID" ) ) {
            $hsl = str_replace( "pAixm08ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixm09ID" ) ) {
            $hsl = str_replace( "pAixm09ID", $this->gmlID.$num ,$inpt);
        } else if ( strpos($inpt, "pAixmIFRVFR" ) ) {
            $hsl = str_replace( "pAixmIFRVFR", $pTraffic,$inpt);
        } else {
            $hsl = $inpt;
        }
    //    dd($hsl);
        return $hsl;
    }
    function replaceXMLTWYElement( $errString, $pNav, $num )
    {
       
        $inpt = $errString;
        $pTs = $pNav;
        $poly = $pTs->geom->coordinates;
        $hsl='';
        if ( $pTs->eff_date == '' || $pTs->eff_date == null ) {
            $pEffDate =date('Y-m-d');
        } else {
            $pEffDate =  $pTs->eff_date;// new Date( $pTs->eff_date ).toISOString();
        }
     
      
        if ( strpos($inpt, "paixm01gml" ) ) {
            $hsl = str_replace("paixm01gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmIdent" ) ) {
            $hsl = str_replace("pAixmIdent", $num,$inpt);
        } else if ( strpos($inpt, "paixm02gml" ) ) {
            $hsl = str_replace("paixm02gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "paixm03gml" ) ) {
            $hsl = str_replace("paixm03gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmTime" ) ) {
            $hsl = str_replace("pAixmTime", $pEffDate,$inpt);
        } else if ( strpos($inpt, "paixm04gml" ) ) {
            $hsl = str_replace("paixm04gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmArptId" ) ) {
            $hsl = str_replace("pAixmArptId", $pTs->arpt_ident,$inpt);
        } else if ( strpos($inpt, "pAixmLine" ) ) {
            $hsl = str_replace("pAixmLine", str_replace(',',' ',$poly),$inpt);
        } else if ( strpos($inpt, "pAixmElev" ) ) {
            $hsl = str_replace(">pAixmElev", $this->pNIL,$inpt);
        } else {
            $hsl = $inpt;
        }
        // console.log($hsl)pAixmArptId
        return $hsl;
    }
    

    function GetCharacter( $str )
    {
        $hsl = '';
        for ($i = 0; $i < strlen($str); $i++ ) {
            if ( is_integer( $str[ $i ]) == false ) {
                if ( $hsl == '' ) {
                    $hsl = $str[ $i ];
                } else {
                    $hsl += $str[ $i ];
                }
            }
        }
        return $hsl;
    }

    function replaceXMLRWYTHR( $errString, $pNav, $num)
    {
        $inpt  = $errString;
        $pTs = $pNav;
        $hsl='';
        $pRwyDirecId  = "CENT_".$pTs->rwy_key;
        $pIdent = $pTs->rwy_ident;
        if ( $pTs->geom == null ) {
            $Pcord = '0 0';
        } else {
           $Pcord = $pTs->geom->coordinates[ 0 ].' '.$pTs->geom->coordinates[ 1 ];
        }
        if ( $pTs->eff_date == '' || $pTs->eff_date == null ) {
            $pEffDate =date('Y-m-d');
        } else {
            $pEffDate =  $pTs->eff_date;// new Date( $pTs->eff_date ).toISOString();
        }
        $pElev  = $pTs->thr_elev;

        if ( strpos($inpt, "pAixm01Gml" ) ) {
            $hsl = str_replace("pAixm01Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmID" ) ) {
            $hsl = str_replace("pAixmID", $pRwyDirecId,$inpt);
        } else if ( strpos($inpt, "pAixm02Gml" ) ) {
            $hsl = str_replace("pAixm02Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm03Gml" ) ) {
            $hsl = str_replace("pAixm03Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmTime1" ) ) {
            $hsl = str_replace("pAixmTime1", $pEffDate,$inpt);
        } else if ( strpos($inpt, "pAixm04Gml" ) ) {
            $hsl = str_replace("pAixm04Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmTHR" ) ) {
            $hsl = str_replace("pAixmTHR", "THR",$inpt);
        } else if ( strpos($inpt, "pAixmDesg" ) ) {
            $hsl = str_replace("pAixmDesg", $pIdent,$inpt);
        } else if ( strpos($inpt, "pAixm05Gml" ) ) {
            $hsl = str_replace("pAixm05Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmCord" ) ) {
            $hsl = str_replace("pAixmCord", $Pcord,$inpt);
        } else if ( strpos($inpt, "pAixmElev" ) ) {
            if ( $pElev == "" || $pElev == null ) {
                $hsl = str_replace(">pAixmElev", $this->pNIL,$inpt);
            }else{
                $hsl = str_replace("pAixmElev", $pElev,$inpt);
            }
        } else if ( strpos($inpt, "pAixmGoid" ) ) {

            $hsl = str_replace(">pAixmGoid", $this->pNIL,$inpt);

        } else if ( strpos($inpt, "pAIXMRWYID" ) ) {
            $hsl = str_replace("pAIXMRWYID", "DIR_".$pTs->rwy_key,$inpt);
        } else if ( strpos($inpt, "pAixm06Gml" ) ) {
            $hsl = str_replace("pAixm06Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm07Gml" ) ) {
            $hsl = str_replace("pAixm07Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm08Gml" ) ) {
            $hsl = str_replace("pAixm08Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "AixmTora" ) ) {
            if ( $pTs->tora == "" || $pTs->tora == "-" || $pTs->tora == null || $pTs->tora == 'NU' ) {
                $hsl = str_replace(">AixmTora", $this->pNIL,$inpt);
            }else{
                $hsl = str_replace("AixmTora", $pTs->tora,$inpt);
            }
        } else if ( strpos($inpt, "pAixm09Gml" ) ) {
            $hsl = str_replace("pAixm09Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm10Gml" ) ) {
            $hsl = str_replace("pAixm10Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm11Gml" ) ) {
            $hsl = str_replace("pAixm11Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmToda" ) ) {
            if ( $pTs->toda == "" || $pTs->toda == "-" || $pTs->toda == null || $pTs->toda == 'NU' ) {
                $hsl = str_replace(">pAixmToda", $this->pNIL,$inpt);
            }else{
                $hsl = str_replace("pAixmToda", $pTs->toda,$inpt);
            }
        } else if ( strpos($inpt, "pAixm12Gml" ) ) {
            $hsl = str_replace("pAixm12Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm13Gml" ) ) {
            $hsl = str_replace("pAixm13Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm14Gml" ) ) {
            $hsl = str_replace("pAixm14Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmLDA" ) ) {
            if ( $pTs->lda == "" || $pTs->lda == "-" || $pTs->lda == null || $pTs->lda == 'NU' ) {
                $hsl = str_replace(">pAixmLDA", $this->pNIL,$inpt);
            }else{
                $hsl = str_replace("pAixmLDA", $pTs->lda,$inpt);
            }

        } else if ( strpos($inpt, "pAixm15Gml" ) ) {
            $hsl = str_replace("pAixm15Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm16Gml" ) ) {
            $hsl = str_replace("pAixm16Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm17Gml" ) ) {
            $hsl = str_replace("pAixm17Gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmAsda" ) ) {
            if ( $pTs->asda == "" || $pTs->asda == "-" || $pTs->asda == null || $pTs->asda == 'NU' ) {
                $hsl = str_replace(">pAixmAsda", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmAsda", $pTs->asda,$inpt);
            }

        }else{
            $hsl = $inpt;
        }

        return $hsl;
    }

    function replaceXMLRWYDirection( $errString, $pNav, $num)
    {
        $inpt = $errString;
        $pTs = $pNav;
        $hsl='';
        $pIdent =$pTs->rwy_ident;
        $ptrueb = $pTs->true_brg;
        $pmagbrg = $pTs->mag_brg;
        $pslope = $pTs->slope;
        $ptdzelev = $pTs->tdz_elev;
        

        if ( $pTs->eff_date == '' || $pTs->eff_date == null ) {
            $pEffDate =date('Y-m-d');
        } else {
            $pEffDate =  $pTs->eff_date;// new Date( $pTs->eff_date ).toISOString();
        }
        // $pCordNav = $pTs->geom.coordinates[ 0 ].' '.$pTs->geom.coordinates[ 1 ];
        // $yy = new Date().getFullYear()
        // $pCord1 = $pTs->ThrLow.Coordinate.LonDecimal & " " & $pTs->ThrLow.Coordinate.LatDecimal
        // $pCord2 = $pTs->ThrHigh.Coordinate.LonDecimal & " " & $pTs->ThrHigh.Coordinate.LatDecimal

    
        if ( strpos($inpt, "pAixm01gml" ) ) {
            $hsl = str_replace("pAixm01gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmTHRID" ) ) {
            $hsl = str_replace("pAixmTHRID", 'DIR_'.$pTs->rwy_key,$inpt);
        } else if ( strpos($inpt, "pAixm02gml" ) ) {
            $hsl = str_replace("pAixm02gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm03gml" ) ) {
            $hsl = str_replace("pAixm03gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmTime1" ) ) {
            $hsl = str_replace("pAixmTime1", $pEffDate,$inpt);
        } else if ( strpos($inpt, "pAixm04gml" ) ) {
            $hsl = str_replace("pAixm04gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmDesg" ) ) {
            $hsl = str_replace("pAixmDesg", $pIdent,$inpt);
        } else if ( strpos($inpt, "pAixmTrueBrg" ) ) {
            if ( $ptrueb == "" || $ptrueb == null) {
                $hsl = str_replace(">pAixmTrueBrg", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmTrueBrg", $ptrueb,$inpt);
            }
        } else if ( strpos($inpt, "pAixmMagBrg" ) ) {
            if ( $pmagbrg == "" || $pmagbrg == null ) {
                $hsl = str_replace(">pAixmMagBrg", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmMagBrg", $pmagbrg,$inpt);
            }
        } else if ( strpos($inpt, "paixmSlope" ) ) {
            if ( $pslope == "" || $pslope == null ) {
                $hsl = str_replace(">paixmSlope", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("paixmSlope", preg_replace('~[\\\\/:*?"<>|]~', '',$pslope),$inpt);
            }
        } else if ( strpos($inpt, "pAixmTDZElev" ) ) {
            if ( $ptdzelev == "" || $ptdzelev == null) {
                $hsl = str_replace(">pAixmTDZElev", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmTDZElev", $ptdzelev,$inpt);
            }
        } else if ( strpos($inpt, "pAIXMRWYID" ) ) {
            $hsl = str_replace("pAIXMRWYID", $pTs->rwy_id,$inpt);
        } else if ( strpos($inpt, "pAixm05gml" ) ) {
            $hsl = str_replace("pAixm05gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm06gml" ) ) {
            $hsl = str_replace("pAixm06gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmRemarks" ) ) {
            if ( $pTs->remarks == "" || $pTs->remarks == null ) {
                    $hsl = str_replace(">pAixmRemarks", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmRemarks",  $pTs->remarks,$inpt);
            }
        } else {
            $hsl = $inpt;
        }
        return $hsl;
    }
    function replaceXMLRWY( $errString, $pNav, $num)
    {
        $inpt = $errString;
        $pTs = $pNav;
        $hsl='';
        $pIdent =$pTs->thr_low."/".$pTs->thr_high;
        $pRl = $pTs->length;
        $pRw = $pTs->width;
        $pSl = $pTs->strip_l;
        $pSw = $pTs->strip_w;
        //.replace( /^\s+|\s+$/g, "" )
        $pRpcn =strtoupper(preg_replace('~[\\\\/:*?"<>|]~', '',$pTs->pcn));
        // $pRpcn = preg_replace('~[\\\\/:*?"<>|]~', ' ',$pRpcn);
        $puomW = "";$pcn = false;
        $prwyBrt = "0"; $prwyChar='';
        $prwyBrt = preg_match("/-?\d+\.?\d*/",$pRpcn);
        // prwyBrt = GetNumeric( pRpcn )
        $prwyChar = substr($pRpcn, - 4);
        $pPcn1 = "";
        $pPcn2 = "";
        $pPcn3 = "";
        $pPcn4 = "";
        $pPcn5 = "";
        $pPcn6 = "";
        switch ( substr($pRpcn, 0, 2 ) ) {
            case "KG":
            case "GS":
                $puomW = "KG";
                break;
            case "ON":
                $puomW = "TON";
                break;
            case "BS":
                $puomW = "LB";
                break;
            default:
                $puomW = "";
                $pcn = false;
                $pPcn5 = strtoupper(substr($pRpcn, - 1 ));
                if ( ($pPcn5 == "T" || $pPcn5 == "U") && ( substr($prwyChar, 0, 1 ) == "F" ||  substr($prwyChar, 0, 1 ) == "R") ) {
                    $pcn = true;
                    $pPcn1 = $prwyBrt;
                    if ( substr($prwyChar, 0, 1 ) == "F" ) {
                        $pPcn2 = "FLEXIBLE";
                    } else {
                        $pPcn2 = "RIGID";
                    }
                    $pPcn3 = substr($prwyChar, 1, 1 );
                    $pPcn4 = substr($prwyChar, 2, 1 );
                    if ( $pPcn5 == "T" ) {
                        $pPcn5 = "TECH";
                    } else {
                        $pPcn5 = "ACFT";
                    }
                } else {
                    $puomW = "LB";
                }
                break;
        }

        $pPcn6 = $pRpcn;
        $pSurf = $pTs->aixm_cod;

        if ( $pTs->eff_date == '' || $pTs->eff_date == null ) {
            $pEffDate =date('Y-m-d');
        } else {
            $pEffDate =  $pTs->eff_date;// new Date( $pTs->eff_date ).toISOString();
        }
        // $pCordNav = $pTs->geom.coordinates[ 0 ].' '.$pTs->geom.coordinates[ 1 ];
        // $yy = new Date().getFullYear()
        // $pCord1 = $pTs->ThrLow.Coordinate.LonDecimal & " " & $pTs->ThrLow.Coordinate.LatDecimal
        // $pCord2 = $pTs->ThrHigh.Coordinate.LonDecimal & " " & $pTs->ThrHigh.Coordinate.LatDecimal

    
        if ( strpos($inpt, "pAixm01gml" ) ) {
            $hsl = str_replace("pAixm01gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmID" ) ) {
            $hsl = str_replace("pAixmID", $pTs->rwy_id,$inpt);
        } else if ( strpos($inpt, "pAixm02gml" ) ) {
            $hsl = str_replace("pAixm02gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm03gml" ) ) {
            $hsl = str_replace("pAixm03gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmTime1" ) ) {
            $hsl = str_replace("pAixmTime1", $pEffDate,$inpt);
        } else if ( strpos($inpt, "pAixm04gml" ) ) {
            $hsl = str_replace("pAixm04gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmDesg" ) ) {
            $hsl = str_replace("pAixmDesg", $pIdent,$inpt);
        } else if ( strpos($inpt, "paixmLength" ) ) {
            if ( $pRl == "0" ||  $pRl == "" ||  $pRl == null ) {
                $hsl = str_replace(">paixmLength", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("paixmLength", $pRl,$inpt);
            }
        } else if ( strpos($inpt, "paixmWidth" ) ) {
            if ( $pRw == "0" || $pRw == "" || $pRw == null ) {
                $hsl = str_replace(">paixmWidth", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("paixmWidth", $pRw,$inpt);
            }
        } else if ( strpos($inpt, "pAixmStripL" ) ) {
            if ( $pSl == "0" || $pSl == "" || $pSl == null ) {
                $hsl = str_replace(">pAixmStripL", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmStripL", $pSl,$inpt);
            }
        } else if ( strpos($inpt, "pAixmStripW" ) ) {
            if ( $pSw == "0" || $pSw == "" || $pSw == null) {
                $hsl = str_replace(">pAixmStripW", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmStripW", $pSw,$inpt);
            }
        } else if ( strpos($inpt, "pAixm05gml" ) ) {
            $hsl = str_replace("pAixm05gml", $this->gmlID.$num,$inpt);

        } else if ( strpos($inpt, "pAixmConc" ) ) {
            if ( $pSurf == "U" ) {
                $hsl = str_replace(">pAixmConc", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmConc", $pSurf,$inpt);
            }
        } else if ( strpos($inpt, "getpcnRWY" ) ) {
            if ( $pcn == true ) {
                $hsl = $this->getRwyPcn( $pPcn1, $pPcn2, $pPcn3, $pPcn4, $pPcn5, false );
            } else {
                $hsl ='';
            }
        } else if ( strpos($inpt, "pAixmPCN5" ) ) {
            if ( $puomW == "" ) {
                if ( $pcn == true ) {
                    $hsl = str_replace("pAixmPCN5", $pPcn5,$inpt);
                } else {
                    $hsl = str_replace(">pAixmPCN5", $this->pNIL,$inpt);
                }
            } else {
                $hsl = str_replace(">pAixmPCN5", $this->pNIL,$inpt);
            }
        } else if ( strpos($inpt, "pAixm06gml" ) ) {
            $hsl = str_replace("pAixm06gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixm07gml" ) ) {
            $hsl = str_replace("pAixm07gml", $this->gmlID.$num,$inpt);
        } else if ( strpos($inpt, "pAixmPCN6" ) ) {
            if ( $pPcn6 == "" ) {
                $hsl = str_replace(">pAixmPCN6", $this->pNIL,$inpt);
            } else {
                $hsl = str_replace("pAixmPCN6", $pPcn6,$inpt);
            }

        } else if ( strpos($inpt, "parptId" ) ) {
            $hsl = str_replace("parptId", $pTs->arpt_ident,$inpt);
        } else {
            $hsl = $inpt;
        }
        return $hsl;
    }

    function getRwyPcn($pcn1,$pcn2,$pcn3,$pcn4, $pcn5,$Uom){
        $a; $b; $c; $d; $e; $f;
        if ( $Uom == true ) {
            $a = "                                <aixm:weightAUW uom=".chr(34).$pcn1.chr(34).">".$pcn2."</aixm:weightAUW>\r\n";
            $f = $a;
        }else{
            if ( $pcn1 == "" ) {
                $a = "                            <aixm:classPCN xsi:nil=".chr(34)."true".chr(34)."/>\r\n";
                $b = "                            <aixm:pavementTypePCN xsi:nil=".chr(34)."true".chr(34)."/>\r\n";
                $c = "                            <aixm:pavementSubgradePCN xsi:nil=".chr(34)."true".chr(34)."/>\r\n";
                $d = "                            <aixm:maxTyrePressurePCN xsi:nil=".chr(34)."true".chr(34)."/>\r\n";
                $e = "                            <aixm:evaluationMethodPCN xsi:nil=".chr(34)."true".chr(34)."/>\r\n";
            }else{
                $a = "                            <aixm:classPCN>".$pcn1."</aixm:classPCN>\r\n";
                $b = "                            <aixm:pavementTypePCN>".$pcn2."</aixm:pavementTypePCN>\r\n";
                $c = "                            <aixm:pavementSubgradePCN>".$pcn3."</aixm:pavementSubgradePCN>\r\n";
                $d = "                            <aixm:maxTyrePressurePCN>".$pcn4."</aixm:maxTyrePressurePCN>\r\n";
                $e = "                            <aixm:evaluationMethodPCN>".$pcn5."</aixm:evaluationMethodPCN>\r\n";
        }

            $f = $a.$b.$c.$d.$e;
        }
        return $f;
    }
    function BuatICaoCode() {
        $result = [];
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';


        for ($i = 0; $i <= 9; $i++) {
            for ($x=0; $x < strlen($str) ; $x++) { 
                $hsl= substr($str,$x,1);
                array_push($result,$i.$hsl);
            }
        }

        return $result;

    }
    public function createaixm()
    {
        $originalInput=Req::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $method='GET';
        $request = Req::create('/api/eaip/menu/one/', $method,);
        Req::replace($request->input());
        $instance = json_decode(Route::dispatch($request)->getContent());
        Req::replace($originalInput);
        // dd($instance->data);
        // $instance->sortBy('va_name');
        // echo "<pre>"; print_r($instance); exit;
        if($instance->status=='success'){
            $data['codeaip'] = $instance->data;
        }
       
        // dd($instance->data);
        // $instance->sortBy('va_name');
        // echo "<pre>"; print_r($instance); exit;
        // if($instance->status=='success'){
        //     $data['airspace'] = $instance->data;
        // }
        return view('pages.aixm.aixm',$data);
    }

    public function pib()
    {
        $originalInput=Req::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['airport']= getDataApi($originalInput, 'api/airport/list?ctry=ID&sort=arpt_name:asc');
        $data['airspace']= getDataApi($originalInput, 'api/airspace/list?airspace_type=FIR&ctry=ID&deleted=0&sort=airspace_name:asc');
       
        // dd($instance->data);
        // $instance->sortBy('va_name');
        // echo "<pre>"; print_r($instance); exit;
        // if($instance->status=='success'){
        //     $data['airspace'] = $instance->data;
        // }
        return view('pages.pib.pib',$data);
    }


}
