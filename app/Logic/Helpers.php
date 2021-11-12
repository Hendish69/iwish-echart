<?php 
use \Illuminate\Support\Facades\Request as Req;
use Illuminate\Support\Facades\DB;
use App\Models\Api\RawdataPub as RawPub;
use App\Models\Api\EaipChartContentTemp as eAip;
use App\Models\Api\ArptransegTemp as Transeg;
use App\Models\Api\ArptransTemp as Trans;
use Carbon\Carbon;
use GuzzleHttp\Client;
$prevdata=[];

if(!function_exists('toWgs')){
    function toWgs($dec,$type) {
    
    if ($type=='LAT'){
        if ( $dec>0){
            $head='N';
        }else{
            $head='S';
            $dec *=-1;
        }
    }else{
        if ( $dec>0){
            $head='E';
        }else{
            $head='W';
            $dec *=-1;
        }
    }
    // dd($dec);
    $vars = explode('.', $dec,2);
    $deg = $vars[0];
    // var_dump(sprintf('%03d',$deg));
    if(count($vars)==2){
        $tempma = '0.'.$vars[1];
    }else{
        $tempma =0;
    }
    // var_dump($dec,$vars[1]);
    // var_dump($dec,$deg,$tempma);
    $tempma = $tempma * 3600;
    // var_dump($tempma);
    $min1 = floor($tempma / 60);
    $min=sprintf('%02d',$min1);
    
    $sec1 = $tempma - ($min1 * 60);
    //  var_dump(number_format($sec1,2));
    $det = explode('.', number_format($sec1,2));
    $det1 = explode('.', number_format($sec1,1));
    $sec=sprintf('%02d',$sec1);
    if ($type=='LAT'){
        $deg=sprintf('%02d',$deg);
    }
    if ($type=='LON'){
        $deg=sprintf('%03d',$deg);
    }
    $hasil=[];
        $dt['Database']=$deg.$min.$sec.$det[1].$head;
        $dt['ENR']=$deg.$min.sprintf('%02d',round($sec1)).$head;
        $dt['IAC']=$deg.$min.$sec.'.'.$det1[1].$head;
        // $dt['IAC']=$deg.$min.sprintf('%02d',round($sec1,1)).$head;
        $dt['FIR']=$deg.sprintf('%02d',round($min1)).$head;
        $dt['NONFIR']=$deg.$min.$sec.'.'.$det[1].$head;
        $dt['VIEW']=$deg.'°'.$min."'".$sec.'.'.$det[1].'"'.$head;
        $dt['IAC VIEW']=$deg.'°'.$min."'".$sec.'.'.$det1[1].'"'.$head;
        $dt['ENR VIEW']=$deg.'°'.$min."'".sprintf('%02d',round($sec1)).'"'.$head;
        array_push($hasil,$dt);
    // return $deg.$min.$sec.$det[1].$head;
    return $hasil; //$deg.$min.$sec.'.'.$det[1].$head;
    
    // return array('deg' => $deg, 'min' => $min, 'sec' => $sec);
    }
}
if(!function_exists('toDecimal')){
    function toDecimal($corvalue,$degminsec=false) {
    
        $head;
        $mark;
        $deg;
        $Min;
        $sec;
        
        $reslt;
        $head = strtoupper(substr($corvalue,strlen($corvalue)-1));
        // dd($head);
        if ($head == "E" || $head == "N" ) {
            $mark = 1;
        } else if ($head == "W" || $head == "S") {
            $mark = -1;
        }
       
        // dd( $corvalue,$head,$mark,strlen($corvalue) );
        if ($head == "E" || $head == "W") {
            $deg = (int)(substr($corvalue,0, 3));
            $Min = (int)( substr($corvalue, 3, 2 ) );
            if (substr($corvalue,5, 1) == ".") {
                $sec = (int)("0." + substr($corvalue,6, (strlen($corvalue) - 7))) * 60;
            } else {
                if (strlen($corvalue) < 10) {
                    $sec = (int)(substr($corvalue,5, 2));
                } else {
                    // $corvalue = str_replace($corvalue,".", "");
                    $sec = (float)(substr($corvalue,5, 4))/100;
                }
            }
            // console.log( $deg, $Min, $sec )
            $afS =  $sec / 60;

        } else if ($head == "N" || $head == "S") {
            $deg = (int)(substr($corvalue,0, 2));
            $Min = (int)(substr($corvalue,2, 2));
            if (substr($corvalue,4, 1) == ".") {
                $sec = (int)("0." + substr($corvalue,5, (strlen($corvalue) - 5))) * 60;
            } else {
                if (strlen($corvalue) < 9) {
                    $sec = (int)(substr($corvalue,4,2));
                } else {
                    // $corvalue = str_replace($corvalue,".", "");
                    $sec = (float)(substr($corvalue,4, 4))/100;
                }
            }
            // console.log( $deg, $Min, $sec )
            $afS =  $sec / 60;
        }

        $minsec = ( $Min + $afS ) / 60;
       
        $reslt = ($deg + $minsec) * $mark;
        if($degminsec){
            $reslt = array('deg' => $deg * $mark , 'min' => $Min, 'sec' => $sec);

        }
        // dd($degminsec,$reslt);
        return $reslt;

    }
}
if(!function_exists('Airspacefreq')){
    function Airspacefreq($freq,$unit,$real=null){
    // dd($freq,$unit);
    if ( $unit == 'V' ) {
        if ( $real == null ) {
            $f= ($freq/1000000).' MHz';
        } else {
            $f = $freq / 1000000;
        }
    } else {
        if ( $real == null ) {
            $f= ($freq/1000).' kHz';
        } else {
            $f = $freq / 1000;
        }
    }
                // dd($f);
    return $f;
    }
}
if(!function_exists('FreqFormat')){
    function FreqFormat( $freq, $navtype, $usefor ) {

    if ( $freq == '' ) {
        $rslt = 'NIL';
    } else if ( $navtype == '3' || $navtype == '9' ) {
        $rslt = $freq;
    } else {
        $rplc = ["M", "K", " "];
        $frq = str_replace($rplc, '', $freq);
        switch ( $navtype ) {
            case '5':
            case '7':
            case '10':
                if ( $frq >= 100000 ) {
                    $rslt = $frq / 1000;
                } else {
                    $rslt = $frq;
                }
                if ( $usefor == 'DATA' ) {
                    $rslt =number_format($rslt,2);//numeral($rslt).format('0.00');
                } else {
                    $rslt =number_format($rslt,0).'kHz';
                }
                break;
            default:
            // var_dump($frq);
                if ($frq < 200){
                    $rslt =$frq.'MHz';
                }else{

                    if ( $frq >= 1000000 ) {
                        if ( $usefor == 'DATA' ) {
                            $rslt =number_format($frq / 10000,2); //numeral($frq / 10000).format('0.00') //format( "####.00", $frq / 10000 )
                        } else {
                            $rslt = number_format($frq / 10000,2).'MHz';//numeral($frq / 10000).format('0.0[00]') + 'MHz' //format( "####.0##", $frq / 10000 ) + 'MHz'
                        }
                    } else if ( $frq < 1000000 && $frq > 100000 ) {
                        if ( $usefor == 'DATA' ) {
                            $rslt =number_format($frq / 10000,2);// numeral($frq / 1000).format('0.00') //format( "####.00", $frq / 1000 )
                        } else {
                            $rslt =number_format($frq / 10000,2).'MHz'; //numeral($frq / 1000).format('0.0[00]') + 'MHz' // format( "####.###", $frq / 1000 ) + 'MHz'
                        }
                    } else {
                        if ( $usefor == 'DATA' ) {
                            $rslt = number_format($frq / 10000,2);//numeral($frq).format('0.00') //format( "###.00", $frq )
                        } else {
                            $rslt =number_format($frq / 10000,2).'MHz';// numeral($frq).format('0.0[00]')+ 'MHz' //format( "###.0##", $frq ) + 'MHz'
                        }
                    }
                }
                break;
        }
    }
    // console.log($rslt);
    return $rslt;
    }
}
if(!function_exists('getDataApi')){
    function getDataApi($originalInput, $url, $method='GET'){
        $request = Req::create($url);
        Req::replace($request->input());
        $instance = json_decode(Route::dispatch($request)->getContent());
        Req::replace($originalInput);
        if($instance) return $instance->data;
    }
}
// example parsing array data ->
    // $data = [
    //                 'tablename'     =>'GEN',
    //                 'fieldname'     =>'sub_id',
    //                 'fieldid'       => $fieldid,
    //                 'status_raw'    =>0,
    //                 'ori_change_pic'=>$user->id
    //         ];
    // example array condition
    // $condition = [
    //                  'field'     => 'status_raw',
    //                  'operand'   => '<',
    //                  'value'     => 100,
    // ];
if(!function_exists('saveDataRaw')){
    function saveDataRaw($data, $condition=null){
        $ret=false;
        $exist = RawPub::where('tablename', '=', $data['tablename'])
                        ->where('fieldname','=', $data['fieldname'])
                        ->where('fieldid', '=', $data['fieldid'])
                        ->where('status_raw','!=', '100')
                        // ->where($condition['field'],$condition['operand'],$condition['value'])
                        ->first();
                        // dd($exist);
        if ($exist === null) {
            $dat = new RawPub;        
            $dat->tablename         = $data['tablename'] ;
            $dat->fieldname         = $data['fieldname'] ;
            $dat->fieldid           = $data['fieldid'] ;
            $dat->status_raw        = $data['status_raw'] ;
            $dat->ori_change_pic    = $data['ori_change_pic'] ;
            $dat->save();
            $ret=true;
        }else{
            $exist->ori_change_pic = $data['ori_change_pic'];
            // $ori=$data['ori_change_pic'];
            $exist->update(); 
            $ret=true; 
        } 
        return $ret;
    }
}

if(!function_exists('savePagePdf')){
    function savePagePdf($data){
        $ret=false;
        $exist = eAip::where('category_id', '=', $data['category_id'])
                        ->where('arpt_ident','=', $data['arpt_ident'])
                        ->first();
                        // dd($exist);
        if ($exist === null) {
        
        }else{
            $exist->page = $data['page'];
            // $exist->src_id = $data['src_id'];
            $exist->update(); 
            $ret=true; 
        } 
        return $ret;
    }
}

if(!function_exists('ConvertTime')){
    function ConvertTime($data){
        $hrs=floor($data / 3600);
        $min= floor(($data / 60) % 60);
        $seconds = $data % 60;
        if ($hrs > 24){
            $day=floor($hrs / 24) ;
            $hrs = $hrs % 24;
            return $day .' days '.$hrs.' hour '.$min.' minutes '.$seconds.' seconds';
        }else{
            return $hrs.' hour '.$min.' minutes '.$seconds.' seconds';
        }
   }
}
if(!function_exists('listUser')){
    function listUser($reff=''){  
        $sql = "SELECT *, A.id as u_id FROM users A 
                  LEFT JOIN org B ON A.org_id=B.org_id
                  LEFT JOIN country C ON A.user_country=C.ident
                WHERE A.user_status='1' AND A.email != '' ORDER BY A.org_id, A.name";
        $users =DB::select(DB::raw($sql));
        // dd($users);
        $orgname="";
        $option=""; 
        foreach($users as $row) {
            $selected = '';
            if($row->org_name_en!=$orgname){
               if($orgname==''){
                  $option.='<optgroup label="'.$row->org_name_en.'">';
               }else{
                  $option.='</optgroup><optgroup label="'.$row->org_name_en.'">';
               }
            }
            if($row->u_id==$reff)
               $selected = 'selected="selected"';

            $option .='<option '.$selected.' contact="<b>Mobile :</b><br>'.$row->user_phone.'<br><b>Email &nbsp; :</b><br>'.$row->email.'" designation="'.($row->user_position!='' ? $row->user_position.'<br>' : '').($row->user_unit!='' ? $row->user_unit.'<br>' : '').($row->org_name_en!='' ? $row->org_name_en.'<br>' : '').($row->country_name!='' ? '<b>'.strtoupper($row->country_name).'</b><br>' : '').'" value="'.$row->u_id.'"> &nbsp;  &nbsp;  &nbsp; '.$row->name.'</option>';
            $orgname = $row->org_name_en;
         }
         return $option;
          
      }
}
if(!function_exists('listCountry')){
    function listCountry($reff=''){  
        $sql = "SELECT ident as id, country_name as name FROM country ";
        $ctrys =DB::select(DB::raw($sql));
        
        $option=""; 
        foreach($ctrys as $row) {
            $selected = '';
            if($row->id==$reff)
               $selected = 'selected="selected"';
            $option .='<option '.$selected.' value="'.$row->id.'">'.$row->name.'</option>';
         }
         echo $option;
      }
}
if(!function_exists('getCountry')){
    function getCountry($reff=''){
        $ret = '';   
        if($reff!=''){
            $ctry = DB::table('country')->where('ident','=',$reff)->first();
            $ret = $ctry->country_name; 
        }
        echo $ret; 
    }
} 
if(!function_exists('clearStr')){
    function clearStr($str, $slash=true){
        $text = preg_replace("/[\r\n]+/", " ", trim($str));
        $text = nl2br($text);
        if($slash){
            $text = addslashes($text);
        }
        return $text;
    }
}
if(!function_exists('remSpecialChar')){
    function remSpecialChar($str){
        return preg_replace("~[^A-Za-z0-9!@#$%^&*()_+-={}|<>?\;,./ :]~i", "", $str); 
    }
}
if(!function_exists('ConverNumChart')){
function ConverNumChart($TextString) {
    $hsl = "";
    $txt = trim($TextString);
    $jm = strlen($txt);
        for ($i = 0; $i < $jm; $i++ ){
            $tmid = substr($txt,$i,1);
            // var_dump( $tmid);
            if ( $hsl == '' ) {
                if (is_numeric($tmid)==1){
                    $hsl = NumAlpabet( $tmid );
                } else {
                    $hsl = Alphabet( $tmid );
                }
            } else {
                if (is_numeric($tmid)==1){
                    // var_dump($tmid);
                    $hsl =$hsl.' ' .NumAlpabet( $tmid );
                } else {
                    $hsl =$hsl.' ' . Alphabet( $tmid );
                }
            }
            
        }

    return strtoupper($hsl);
}
}
if(!function_exists('alphanumeric')){
    function alphanumeric($inputtxt)
    { 
        // var Exp = /((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i;
        //unutk mengencek numerik atau alphabet, jika nilai TRUE = Numerik, else = Alpaabet
        // $Exp = /((^[0-9]+))+$/i;
        // var_dump(is_numeric($inputtxt));
        if(is_numeric($inputtxt)==1)
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
}
if(!function_exists('Alphabet')){
    function Alphabet( $Aplhabet )
    {
        $Bet= '';
        switch ( $Aplhabet ) {
            
            case "A":
                $Bet = "Alpha";
                break;
            case "B":
                $Bet = "Bravo";
                break;
            case "C":
                $Bet = "Charlie";
                break;
            case "D":
                $Bet = "Delta";
                break;
            case "E":
                $Bet = "Echo";
                break;
            case "F":
                $Bet = "Foxtrot";
                break;
            case "G":
                $Bet = "Golf";
                break;
            case "H":
                $Bet = "Hotel";
                break;
            case "I":
                $Bet = "India";
                break;
            case "J":
                $Bet = "Juliet";
                break;
            case "K":
                $Bet = "Kilo";
                break;
            case "L":
                $Bet = "Lima";
                break;
            case "M":
                $Bet = "Mike";
                break;
            case "N":
                $Bet = "November";
                break;
            case "O":
                $Bet = "Oscar";
                break;
            case "P":
                $Bet = "Papa";
                break;
            case "Q":
                $Bet = "Quebec";
                break;
            case "R":
                $Bet = "Romeo";
                break;
            case "S":
                $Bet = "Sierra";
                break;
            case "T":
                $Bet = "Tango";
                break;
            case "U":
                $Bet = "Uniform";
                break;
            case "V":
                $Bet = "Victor";
                break;
            case "W":
                $Bet = "Whiskey";
                break;
            case "X":
                $Bet = "X-ray";
                break;
            case "Y":
                $Bet = "Yankee";
                break;
            case "Z":
                $Bet = "Zulu";
                break;
        }
            return $Bet;
    }
}
if(!function_exists('NumAlpabet')){  
    function NumAlpabet( $Numer )
    {
        $Bet = "";
        switch ( $Numer ) {
            case "0":
                $Bet = "Zero";
                break;
            case "1":
                $Bet = "One";
                break;
            case "2":
                $Bet = "Two";
                break;
            case "3":
                $Bet = "Three";
                break;
            case "4":
                $Bet = "Four";
                break;
            case "5":
                $Bet = "Five";
                break;
            case "6":
                $Bet = "Six";
                break;
            case "7":
                $Bet = "Seven";
                break;
            case "8":
                $Bet = "Eight";
                break;
            case "9":
                $Bet = "Nine";
                break;
        }
        return $Bet;
    }
}
if(!function_exists('getdistance')){     
    function getdistance( $latitude1, $longitude1, $latitude2, $longitude2 ) {
        $hasil = '';
        if (is_nan($latitude1)==true || is_nan($longitude1)==true || is_nan($latitude2)==true || is_nan($longitude2)==true ) {
            echo "<script> console.log( 'getdist cannot proses ', ".$latitude1.",".$longitude1.",".$latitude2.",".$longitude2.")</script>";
        } else {
            $newVal = new stdClass();//create a new
            $newVal->a = $longitude1;
            $newVal->b = $latitude1;
            $newVal->c = $longitude2;
            $newVal->d = $latitude2;
            $val = $newVal;
            $hhs = array();
            $kk = '';
            $rslt = array();
            $rslt = Getbearing( $latitude1, $longitude1, $latitude2, $longitude2 );
        }
        return $rslt;
    }
}

if(!function_exists('Getbearing')){
    function Getbearing( $latitude1, $longitude1, $latitude2, $longitude2,$magvar=true ){
        $R = 6371.01; //' // earth's mean radius in km
        $nm  = 0.5399568035;
        $a  = 6378137;
        $b  = 6356752.314245;
        $f  = 1 / 298.257223563 ;
        // var f = 1 / 298.257223563;
        // var f = 0.00335281068118 ;0.00335281066474

        $epoch = date('Y-m-d');
        $alt=0;
        // MV.GetMagvar( longitude1, latitude1, epoch );
        // $mv=GetMagvar($lon,$lat,$date1,$alt,);
        $mgvar1=0; $mgvar2=0;
        if ($magvar==true){
            $mv = GetMagvar( $longitude1, $latitude1, $epoch,$alt);
            // dd($mv->dec);
            // // console.log('hasil magvar', mv );
            $mgvar1 = $mv->dec;
            // $mgvar1 =0;
            // // console.log(MV.result)
            $mv1 = GetMagvar($longitude2, $latitude2, $epoch,$alt);
            $mgvar2 = $mv1->dec;
            
        }
        //  dd($mv1->dec);
        // $mgvar2 = 0;
        // console.log(MV.result)
        // console.log(mgvar1 + '  ' + mgvar1)





        $trkoutT; $trkoutM; $trkinT; $trkinM; $midX; $midY; $trackoutM; $trackinM; $trackoutT; $trackinT;

        for ( $i = 0; $i < 2; $i++ ) {
            $lat1 = deg2rad( $latitude1 );
            $lat2 = deg2rad( $latitude2 );
            $lon1 = deg2rad( $longitude1 );
            $lon2 = deg2rad( $longitude2 );
            if ( $i == 1 ) {
                $lat1 = deg2rad( $latitude2 );
                $lat2 = deg2rad( $latitude1 );
                $lon1 = deg2rad( $longitude2 );
                $lon2 = deg2rad( $longitude1 );
            }
            $dLat; $dLon;
            if ( $lat1 > $lat2 ) {
                $dLat = $lat1 - $lat2;  // deg2rad below
            } else {
                $dLat =$lat2 - $lat1;  // deg2rad below
            }
            if ( $lon1 > $lon2 ) {
                $dLon = $lon1-$lon2; 
            } else {
                $dLon = $lon2-$lon1; 
            }
            $L = $lon2 - $lon1;

            $bx = cos( $lat2 ) * cos( $L );
            $by = cos( $lat2 ) * sin( $L );
            $lat3 = atan2( sin( $lat1 ) + sin( $lat2 ), sqrt( ( cos( $lat1 ) + $bx ) * ( cos( $lat1 ) + $bx ) + $by * $by ) );
            $lon3 = $lon1 + atan2( $by, cos( $lat1 ) + $bx );
            $midX = rad2deg( $lon3 );
            $midY = rad2deg( $lat3 );

           
            $iterations = 0;

            $tanU1 = ( 1 - $f ) * tan( $lat1 );
            $cosU1 = 1 / sqrt( ( 1 + $tanU1 * $tanU1 ) );
            $sinU1 = $tanU1 * $cosU1;
            $tanU2 = ( 1 - $f ) * tan( $lat2 );
            $cosU2 = 1 / sqrt( ( 1 + $tanU2 * $tanU2 ) );
            $sinU2 = $tanU2 * $cosU2;

            $sinλ=0; $cosλ=0; $sinSqσ=0; $sinσ=0; $cosσ=0; $σ=0; $sinα=0; $cosSqα=0; $cos2σM=0; $C=0;
            $λ = $L;
            $λ1 = 0.0;

            while ( (abs( $λ - $λ1 ) > 0.000000000001) && ($iterations++ < 200) ) {
                $sinλ = sin( $λ );
                $cosλ = cos( $λ );
                $sinSqσ = ( $cosU2 * $sinλ ) * ( $cosU2 * $sinλ ) + ( $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosλ ) * ( $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosλ );
                $sinσ = sqrt( $sinSqσ );
                // '   MsgBox(sinσ)
                // '  If (sinσ = 0) Then Return 0 '  // co-incident points
                $cosσ = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosλ;
                $σ = atan2($sinσ, $cosσ); //distance
                $sinα = $cosU1 * $cosU2 * $sinλ / $sinσ;
                $cosSqα = 1 - $sinα * $sinα;
                $cos2σM = $cosσ - 2 * $sinU1 * $sinU2 / $cosSqα;
                if ( is_nan( $cos2σM ) ) {
                    $cos2σM = 0;
                }
                // ' // equatorial line: cosSqα=0 (§6)
                $C = $f / 16 * $cosSqα * ( 4 + $f * ( 4 - 3 * $cosSqα ) );
                $λ1 = $λ;
                $λ = $L + ( 1 - $C ) * $f * $sinα * ( $σ + $C * $sinσ * ( $cos2σM + $C * $cosσ * ( -1 + 2 * $cos2σM * $cos2σM ) ) );
            }
            // var isNaN = function(value) {
            //     return Number.isNaN(Number(value));
            //     }
            if ( $iterations >= 200 ) {
                echo '<script> alert( "Formula failed to converge" )</script>';
            }
            $uSq = $cosSqα * ($a * $a - $b * $b) / ($b * $b);
            $AA = 1 + $uSq / 16384 * ( 4096 + $uSq * ( -768 + $uSq * ( 320 - 175 * $uSq ) ) );
            $BB = $uSq / 1024 * ( 256 + $uSq * ( -128 + $uSq * ( 74 - 47 * $uSq ) ) );
            $Δσ = $BB * $sinσ * ( $cos2σM + $BB / 4 * ( $cosσ * ( -1 + 2 * $cos2σM * $cos2σM ) - $BB / 6 * $cos2σM * ( -3 + 4 * $sinσ * $sinσ ) * ( -3 + 4 * $cos2σM * $cos2σM ) ) );

            $s  = $b * $AA * ($σ - $Δσ);
        //    console.log('TESTTTT',s,σ)
            $α1 = atan2( $cosU2 * $sinλ, $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosλ );
            $α2 = atan2( $cosU1 * $sinλ, - $sinU1 * $cosU2 + $cosU1 * $sinU2 * $cosλ );

            // $α1 = rad2deg( ( $α1 + 2 * pi() ) % ( 2 * pi() ) ); // normalise to 0..360
            // $α2 = rad2deg( ( $α2 + 2 * pi() ) % ( 2 * pi() ) ); // normalise to 0..360
            $α1= rad2deg(fmod(( $α1 + 2 * pi() ), ( 2 * pi() )));
            $α2= rad2deg(fmod(( $α2 + 2 * pi() ), ( 2 * pi() )));

            // console.log(α1 + '  ' + α2)
            // if ( $lon2 == $lon1 ) {
            //     if ( $lat1 > $lat2 ) {
            //         $α1 = $α2;
            //     }
            // }

            if ( $i == 0 ) {
                // format("####.0##",frq/10000)
                // console.log(α1 , ' track out ' , α2 , ' i ' , i )
                $trkoutT = $α1;
                $trackoutT = number_format( ( round( $trkoutT * 100 ) / 100) , 2,'.','' );
                $trkoutM = $trkoutT - $mgvar1;
                if ( $trkoutM < 0 ) {        
                    $trkoutM = ( 360 + $trkoutT ) - $mgvar1;
                }
                $trackoutM =number_format( ( round( $trkoutM * 100 ) / 100), 2,'.','' ); //format( "000", trkoutM.toFixed() )
            } else {
                // console.log(α1 , ' track in ' , α2 , ' i ' , i )
                $trkinT = $α2;
                $trackinT =number_format( ( round( $trkinT * 100 ) / 100 ), 2,'.','' );
                $trkinM = $trkinT - $mgvar2;
                if ( $trkinM < 0 ) {
                    $trkinM = ( 360 + $trkinT ) - $mgvar2;
                }
                $trackinM = number_format( ( round( $trkinM * 100 ) / 100 ),2,'.','' );//format( "000", trkinM.toFixed() )
            }
        }
        $d =  m2Nm($s);
        $dsnm = number_format($d ,2,'.','');
        $dist = round($s);
        // console.log( ' hasil  ' ,dist,s,trackoutT,trackoutM,trackinT,trackinM)
        $ret = (object)[
            'TrackOutTrue' => $trackoutT,
            'TrackOutMag' => $trackoutM,
            'TrackInTrue' => $trackinT,
            'TrackInMag' => $trackinM,
            'TrackOutReal' => $trkoutT,
            'TrackOutMagReal' => $trkoutM,
            'TrackInReal' => $trkinT,
            'TrackInMagReal' => $trkinM,
            'Midlat' => $midY,
            'Midlon' => $midX,
            'DistinNM' => $dsnm,
            'Distance' => $dist,
            'DistanceReal' => $d,
            'DistinMeter' => $s
        ];
        // dd($ret);
        return  $ret;
    }
}

if(!function_exists('GetMagvar')){ 
    function GetMagvar(&...$args) {
        $result = array();
        $lat=''; $lon=''; $tgl=''; $alt='';
        // dd($args);
        if ( is_object($args[0]) ) {
            $lat = $args[ 0 ][ 1 ];
            $lon = $args[ 0 ][ 0 ];
            $alt = $args[ 2 ];
            $tgl = $args[ 1 ];
        } else if ( is_numeric($args[ 0 ])  && is_numeric($args[ 1 ]) ) {
            $lat = $args[ 1 ];
            $lon = $args[ 0 ];
            $tgl = isset($args[2]) ? $args[2] : date('Y-m-d');
            $alt = $args[ 3 ];
        }
        $time=strtotime($tgl);
        $yr=(int)date("Y",$time);
        
        // $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        // if (strpos($_SERVER['HTTP_HOST'], 'iwish.dephub.go.id') !== false) {
        //     $protocol ='https://';  //fixed some bugs
        // }
        // $url = $protocol . $_SERVER['HTTP_HOST'];

        // $path= $url;
        
        $coffile = public_path('/images/wmm/WMM2020.COF');
        if ( ($yr > 2014) && ($yr < 2020) ) {

            $coffile = public_path('/images/wmm/WMM2015.COF');
        } else if ( $yr < 2015 ) {
            $coffile = public_path('/images/wmm/WMM2010.COF');

        }

        $cof = file( $coffile );
        $newGeomag = new Geomag( $cof );
        $result = $newGeomag->mag($lat, $lon, $alt, $tgl);
        // $json_string = json_encode($result, JSON_PRETTY_PRINT);
        // echo $json_string;die;   
        return $result;
    }
}
if(!function_exists('syncXHR')){
    function syncXHR( $url ) {
        $ch = curl_init();
        $data = '';
        // Set curl options
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1, // Return information from server
            CURLOPT_URL => $url,
            CURLOPT_POST => 1, // Normal HTTP post 
            CURLOPT_POSTFIELDS => $data
        ));

        // Execute curl and return result to $response
        $response = curl_exec($ch);
        // Close request
        curl_close($ch);

        return $response;
    }
}
 
class Geomag {
    public $wmm;
    public $maxord = 12;
    public $a = 6378.137; // WGS 1984 Equatorial axis (km)
    public $b = 6356.7523142; // WGS 1984 Polar axis (km)
    public $re = 6371.2;
    public $a2 ;
    public $b2 ;
    public $c2 ;
    public $a4 ;
    public $b4 ;
    public $c4 ;
    public $z = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
    public $unnormalizedWMM;

    function __construct($model=null){
        $this->a2 = $this->a * $this->a;
        $this->b2 = $this->b * $this->b;
        $this->c2 = $this->a2 - $this->b2;
        $this->a4 = $this->a2 * $this->a2;
        $this->b4 = $this->b2 * $this->b2;
        $this->c4 = $this->a4 - $this->b4;
         if ( $model !== null ) { // initialize
            if ( is_array($model)) { // WMM.COF file
                $this->parseCof( $model );
                $this->unnormalize( $this->wmm );
            } else if ( is_object($model) ) { // unnorm obj
                $this->setUnnorm( $model );
            } else {
                throw new Exception( "Invalid argument type" );
            }
        }
    }   


    function parseCof( $cof ) {
        $ret = '';
        $callwmm =  (function() use ($cof) {
            $modelLines = $cof;
                $cwmm = array();
                $i; $vals; $epoch; $model; $modelDate;
                foreach ($modelLines as  $i => $val) {
                    if ( property_exists( (object) $modelLines, $i ) ) {
                        $vals =  preg_split('/\s+/', trim($modelLines[ $i ]));
                        if ( count($vals) == 3 ) {
                            $epoch = floatval( $vals[ 0 ] );
                            $model = $vals[ 1 ];
                            $modelDate = $vals[ 2 ];
                        } else if ( count($vals) == 6 ) {
                            array_push($cwmm , (object)[
                                'n'   => intval($vals[ 0 ], 10),
                                'm'    => intval($vals[ 1 ], 10),
                                'gnm'  => floatval($vals[ 2 ]),
                                'hnm'  => floatval($vals[ 3 ]),
                                'dgnm' => floatval($vals[ 4 ]),
                                'dhnm' => floatval($vals[ 5 ]),
                            ]);
                        }
                    }      
                } 
                $ret = (object)[
                    'epoch' => $epoch,
                    'model' => $model,
                    'modelDate' => $modelDate,
                    'wmm' => $cwmm
                ];
            return $ret;
        });     
        $ret = $callwmm();
        $this->wmm = $ret;  
    }
    function unnormalize( $wmm ) {
 
            $i; $j; $m; $n; $D2; $flnmj;
            $z = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
 
                $c = [      $z, $z, $z, $z, $z, $z,
                            $z, $z, $z, $z, $z, $z,
                            $z
                ];

                $cd = [      $z, $z, $z, $z, $z, $z,
                            $z, $z, $z, $z, $z, $z,
                            $z
                ];
                $k = [      $z, $z, $z, $z, $z, $z,
                            $z, $z, $z, $z, $z, $z,
                            $z
                ];
                $snorm = [  $z, $z, $z, $z, $z, $z,
                            $z, $z, $z, $z, $z, $z,
                            $z
                ];
                $model = $wmm->wmm;
                // print("<pre>".print_r($model,true)."</pre>");        die;    
            foreach ( $model as $i => $val ) {
                if ( property_exists( (object) $model, $i ) ) {
                    // var_dump('expression');die;
                    if ( $model[ $i ]->m <= $model[ $i ]->n ) {
                        $c[ $model[ $i ]->m ][ $model[ $i ]->n ] = $model[ $i ]->gnm;
                        $cd[ $model[ $i ]->m ][ $model[ $i ]->n ] = $model[ $i ]->dgnm;
                        if ( $model[ $i ]->m != 0 ) {
                            $c[ $model[ $i ]->n ][ $model[ $i ]->m - 1 ] = $model[ $i ]->hnm;
                            $cd[ $model[ $i ]->n ][ $model[ $i ]->m - 1 ] = $model[ $i ]->dhnm;
                        }
                    }
                }
            }
            // var_dump($c);die;

            /* CONVERT SCHMIDT NORMALIZED GAUSS COEFFICIENTS TO UNNORMALIZED */
            $snorm[ 0 ][ 0 ] = 1;

            for ( $n = 1; $n <= $this->maxord; $n++ ) {
                $snorm[ 0 ][ $n ] = $snorm[ 0 ][ $n - 1 ] * ( 2 * $n - 1 ) / $n;
                $j = 2;

                for ( $m = 0, $D2 = ( $n - $m + 1 ); $D2 > 0; $D2--, $m++ ) {
                    $k[ $m ][ $n ] = ( ( ( $n - 1 ) * ( $n - 1 ) ) - ( $m * $m ) ) /
                        ( ( 2 * $n - 1 ) * ( 2 * $n - 3 ) );
                    if ( $m > 0 ) {
                        $flnmj = ( ( $n - $m + 1 ) * $j ) / ( $n + $m );
                        $snorm[ $m ][ $n ] = $snorm[ $m - 1 ][ $n ] * sqrt( $flnmj );
                        $j = 1;
                        $c[ $n ][ $m - 1 ] = $snorm[ $m ][ $n ] * $c[ $n ][ $m - 1 ];
                        $cd[ $n ][ $m - 1 ] = $snorm[ $m ][ $n ] * $cd[ $n ][ $m - 1 ];
                    }
                    $c[ $m ][ $n ] = $snorm[ $m ][ $n ] * $c[ $m ][ $n ];
                    $cd[ $m ][ $n ] = $snorm[ $m ][ $n ] * $cd[ $m ][ $n ];
                }
            }
            // print("<pre>".print_r($c,true)."</pre>");        die;   //same wit js
            // print("<pre>".print_r($cd,true)."</pre>");       die;    //same wit js
            
            $k[ 1 ][ 1 ] = 0.0;
            $epoch = $wmm->epoch;
            
            $this->unnormalizedWMM = (object)[
                'epoch'=> $epoch,
                'k'=> $k,
                'c'=> $c,
                'cd'=> $cd
            ];
            // print("<pre>".print_r($this->unnormalizedWMM,true)."</pre>"); //same wit js
            
        }

        public function setCof ( $cof ) {
            $this->parseCof( $cof );
            $this->unnormalize( $this->wmm );
        }
        public function getWmm () {
            return $this->wmm;
        }
        public function setUnnorm ( $val ) {
            $this->unnormalizedWMM = $val;
        }
        public function getUnnorm () {
            return $this->unnormalizedWMM;
        }
        public function getEpoch () {
            return $this->unnormalizedWMM->epoch;
        }
        public function setEllipsoid ( $e ) {
            $this->a = $e->a;
            $this->b = $e->b;
            $this->re = 6371.2;
            $this->a2 = $this->a * $this->a;
            $this->b2 = $this->b * $this->b;
            $this->c2 = $this->a2 - $this->b2;
            $this->a4 = $this->a2 * $this->a2;
            $this->b4 = $this->b2 * $this->b2;
            $this->c4 = $this->a4 - $this->b4;
        }
        public function getEllipsoid () {
            return (object)[
                 'a'=> $this->a,
                'b'=> $this->b
            ];
        }
        public function calculate ( $glat, $glon, $h, $date=null ) {
            // print("<pre>".print_r($this->unnormalizedWMM,true)."</pre>");
            // dd($glat, $glon);
            if ( $this->unnormalizedWMM == null ) { 
                throw new Exception("A World Magnetic Model has not been set.");
            }
            // if ( $glat == null || $glon == null ) {
            //     throw new Exception( "Latitude and longitude are required arguments." );
            // }
            if(!function_exists('decimalDate')){
                function decimalDate( $date=null ) {
                    if(empty($date)){
                        $date = date('Y-m-d');   
                        }
                        $time=strtotime($date);
                        $year=(int)date("Y",$time);
                        // var_dump($time);

                    $daysInYear = 365 +
                        ( ( ( $year % 400 == 0 ) || ( $year % 4 == 0 && ( $year % 100 > 0 ) ) ) ? 1 : 0 );
                    $msInYear = $daysInYear * 24 * 60 * 60 * 1000;
                    $year1 = strtotime($date)*1000 ."<br>";
                    $date  = new DateTime($year."-01-01 00:00:00 GMT+0700");

                    $year0= $date->getTimestamp() * 1000;
                    $yearrt = (float)(( (float)$year1 - (float)$year0 ) / (float)$msInYear);
                    // print("<pre>".print_r($year + $yearrt,true)."</pre>") ; //same with js
                    return $year + $yearrt;
                }
            }
            if(!function_exists('format')){
                function format( $num, $targetLength ) {
                    return str_pad($num, $targetLength, "0", STR_PAD_LEFT); 
                }
            }

            $epoch = $this->unnormalizedWMM->epoch;
                $k = $this->unnormalizedWMM->k;
                $c = $this->unnormalizedWMM->c;
                $cd = $this->unnormalizedWMM->cd;
                $alt = isset($h) ? ((float)$h / 3280.8399) : 0; // convert h (in feet) to kilometers (default, 0 km
                $dt = decimalDate( $date ) - $epoch;
                $rlat = deg2rad((float)$glat );
                $rlon = deg2rad((float)$glon );
                $srlon = sin( $rlon );
                $srlat = sin( $rlat );
                $crlon = cos( $rlon );
                $crlat = cos( $rlat );
                $srlat2 = $srlat * $srlat;
                $crlat2 = $crlat * $crlat;
                $q;
                $q1;
                $q2;
                $ct;
                $st;
                $r2;
                $r;
                $d;
                $ca;
                $sa;
                $aor;
                $ar;
                $br = 0.0;
                $bt = 0.0;
                $bp = 0.0;
                $bpp = 0.0;
                $dotx = 0.0;
                $doty = 0.0;
                $dotz = 0.0;
                $par;
                $temp1;
                $temp2;
                $temp3;
                $temp4;
                $parp;
                $D4;
                $m;
                $n;
                $fn = [ 0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13 ];
                $fm = [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ];
                $z = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
                $z = $this->z;
                $tc = [     $z, $z, $z, $z, $z, $z,
                            $z, $z, $z, $z, $z, $z,
                            $z
                ];
                $sp = $z;
                $cp = $z;
                $pp = $z;
                $p = [      $z, $z, $z, $z, $z, $z,
                            $z, $z, $z, $z, $z, $z,
                            $z
                ];

                $dp = [ $z, $z, $z, $z, $z, $z,
                        $z, $z, $z, $z, $z, $z,
                        $z
                ];
                $td = [ $z, $z, $z, $z, $z, $z,
                        $z, $z, $z, $z, $z, $z,
                        $z
                ];
                $te = [ $z, $z, $z, $z, $z, $z,
                        $z, $z, $z, $z, $z, $z,
                            $z
                ];
                $bx;
                $by;
                $bz;
                $bh;
                $ti;
                $dec;
                $dip;
                $dotdecy;
                $dotdecx;
                $gv=0;
            $sp[ 0 ] = 0.0;
            $sp[ 1 ] = $srlon;
            $cp[ 1 ] = $crlon;
            $tc[ 0 ][ 0 ] = 0;
            $td[ 0 ][ 0 ] = 0;
            $te[ 0 ][ 0 ] = 0;
            $cp[ 0 ] = 1.0;
            $pp[ 0 ] = 1.0;
            $p[ 0 ][ 0 ] = 1;

            /* CONVERT FROM GEODETIC COORDS. TO SPHERICAL COORDS. */

            $q = sqrt( $this->a2 - $this->c2 * $srlat2 );
            $q1 = $alt * $q;
            $q2 = ( ( $q1 + $this->a2 ) / ( $q1 + $this->b2 ) ) * ( ( $q1 + $this->a2 ) / ( $q1 + $this->b2 ) );
            $ct = $srlat / sqrt( $q2 * $crlat2 + $srlat2 );
            $st = sqrt( 1.0 - ( $ct * $ct ) );
            $r2 = ( $alt * $alt ) + 2.0 * $q1 + ( $this->a4 - $this->c4 * $srlat2 ) / ( $q * $q );
            $r = sqrt( $r2 );
            $d = sqrt( $this->a2 * $crlat2 + $this->b2 * $srlat2 );
            $ca = ( $alt + $d ) / $r;
            $sa = $this->c2 * $crlat * $srlat / ( $r * $d );
            
            for ( $m = 2; $m <= $this->maxord; $m++ ) {
                $sp[ $m ] = $sp[ 1 ] * $cp[ $m - 1 ] + $cp[ 1 ] * $sp[ $m - 1 ];
                $cp[ $m ] = $cp[ 1 ] * $cp[ $m - 1 ] - $sp[ 1 ] * $sp[ $m - 1 ];
            }

            $aor = $this->re / $r;
            $ar = $aor * $aor;
 
            for ( $n = 1; $n <= $this->maxord; $n++ ) {
                $ar = $ar * $aor;
                for ( $m = 0, $D4 = ( $n + $m + 1 ); $D4 > 0; $D4--, $m++ ) {

                    /*
                            COMPUTE UNNORMALIZED ASSOCIATED LEGENDRE POLYNOMIALS
                            AND DERIVATIVES VIA RECURSION RELATIONS
                    */
                    if ( $n == $m ) {
                        $p[ $m ][ $n ] = $st * $p[ $m - 1 ][ $n - 1 ];
                        $dp[ $m ][ $n ] = $st * $dp[ $m - 1 ][ $n - 1 ] + $ct * $p[ $m - 1 ][ $n - 1 ];
                    } else if ( $n == 1 && $m == 0 ) {
                        $p[ $m ][ $n ] = $ct * $p[ $m ][ $n - 1 ];
                        $dp[ $m ][ $n ] = $ct * $dp[ $m ][ $n - 1 ] - $st * $p[ $m ][ $n - 1 ];
                    } else if ( $n > 1 && $n != $m ) {
                        if ( $m > $n - 2 ) {
                            $p[ $m ][ $n - 2 ] = 0;
                        }
                        if ( $m > $n - 2 ) {
                            $dp[ $m ][ $n - 2 ] = 0.0;
                        }
                        $p[ $m ][ $n ] = $ct * $p[ $m ][ $n - 1 ] - $k[ $m ][ $n ] * $p[ $m ][ $n - 2 ];
                        $dp[ $m ][ $n ] = $ct * $dp[ $m ][ $n - 1 ] - $st * $p[ $m ][ $n - 1 ] - $k[ $m ][ $n ] * $dp[ $m ][ $n - 2 ];
                    } 
                    /*
                            TIME ADJUST THE GAUSS COEFFICIENTS
                    */

                    $tc[ $m ][ $n ] = $c[ $m ][ $n ] + $dt * $cd[ $m ][ $n ];
                    $td[ $m ][ $n ] = $cd[ $m ][ $n ] * $sp[ $m ];
                    $te[ $m ][ $n ] = $cd[ $m ][ $n ] * $cp[ $m ];
                    if ( $m !== 0 ) {
                        $tc[ $n ][ $m - 1 ] = $c[ $n ][ $m - 1 ] + $dt * $cd[ $n ][ $m - 1 ];
                        $td[ $n ][ $m - 1 ] = $cd[ $n ][ $m - 1 ] * $cp[ $m ];
                        $te[ $n ][ $m - 1 ] = $cd[ $n ][ $m - 1 ] * $sp[ $m ];
                    } 
                    /*
                            ACCUMULATE TERMS OF THE SPHERICAL HARMONIC EXPANSIONS
                    */
                    $par = $ar * $p[ $m ][ $n ];
                    if ( $m == 0 ) {
                        $temp1 = $tc[ $m ][ $n ] * $cp[ $m ];
                        $temp2 = $tc[ $m ][ $n ] * $sp[ $m ];
                        $temp3 = $te[ $m ][ $n ];
                        $temp4 = $td[ $m ][ $n ];
                    } else {
                        $temp1 = $tc[ $m ][ $n ] * $cp[ $m ] + $tc[ $n ][ $m - 1 ] * $sp[ $m ];
                        $temp2 = $tc[ $m ][ $n ] * $sp[ $m ] - $tc[ $n ][ $m - 1 ] * $cp[ $m ];
                        $temp3 = $te[ $m ][ $n ] + $te[ $n ][ $m - 1 ];
                        $temp4 = $td[ $m ][ $n ] - $td[ $n ][ $m - 1 ];
                    }
 
                    $dotx += $temp3 * $dp[ $m ][ $n ];
                    $doty += $m * $temp4 * $p[ $m ][ $n ];
                    $dotz += $temp3 * $p[ $m ][ $n ];
                     
                    $bt = $bt - $ar * $temp1 * $dp[ $m ][ $n ];
                    $bp += ( $fm[ $m ] * $temp2 * $par );
                    $br += ( $fn[ $n ] * $temp1 * $par );
           
                    /*
                                SPECIAL CASE:  NORTH/SOUTH GEOGRAPHIC POLES
                    */
                    if ( $st == 0.0 && $m == 1 ) {
                        if ( $n == 1 ) {
                            $pp[ $n ] = $pp[ $n - 1 ];
                        } else {
                            $pp[ $n ] = $ct * $pp[ $n - 1 ] - $k[ $m ][ $n ] * $pp[ $n - 2 ];
                        }
                        $parp = $ar * $pp[ $n ];
                        $bpp += ( $fm[ $m ] * $temp2 * $parp );

                    }

                }
            }
            $bp = (($st == 0.0) ? $bpp : ( $bp / $st ));

            /*
            ROTATE MAGNETIC VECTOR COMPONENTS FROM SPHERICAL TO
            GEODETIC COORDINATES
        */ 
            $bx = -$bt * $ca - $br * $sa;
            $by = $bp;
            $bz = $bt * $sa - $br * $ca;
 
            /*
                COMPUTE DECLINATION (DEC), INCLINATION (DIP) AND
                TOTAL INTENSITY (TI)
            */

            $bh = sqrt( ( $bx * $bx ) + ( $by * $by ) );
            $ti = sqrt( ( $bh * $bh ) + ( $bz * $bz ) );
 
            $dec = rad2deg( atan2( $by, $bx ) );
 
            $dip = rad2deg( atan2( $bz, $bh ) );
 
            $dotx = $dotx * -1.0;
            $doty = $doty / $st;
            $dotz = -1.0 * $dotz;
            $dotdecx = $dotx * $ca - $dotz * $sa;
            if($bh >0){
                        $dotdecy = ( $bx * $doty - $by * $dotdecx ) / pow( $bh, 2 );
                        $dotdecy = rad2deg( $dotdecy );
                    }
 

            /*
                COMPUTE MAGNETIC GRID VARIATION IF THE CURRENT
                GEODETIC POSITION IS IN THE ARCTIC OR ANTARCTIC
                (I.E. GLAT > +55 DEGREES OR GLAT < -55 DEGREES)
                OTHERWISE, SET MAGNETIC GRID VARIATION TO -999.0
            */

            if ( abs( $glat ) >= 55.0 ) {
                if ( $glat > 0.0 && $glon >= 0.0 ) {
                    $gv = $dec - $glon;
                } else if ( $glat > 0.0 && $glon < 0.0 ) {
                    $gv = $dec + abs( $glon );
                } else if ( $glat < 0.0 && $glon >= 0.0 ) {
                    $gv = $dec + $glon;
                } else if ( $glat < 0.0 && $glon < 0.0 ) {
                    $gv = $dec - abs( $glon );
                }
                if ( $gv > 180.0 ) {
                    $gv -= 360.0;
                } else if ( $gv < -180.0 ) {
                    $gv += 360.0;
                }
            }
            
            $mgEW = 'E';
            $EW = '';
            $aip = abs(round($dec));
            $nav = abs(round($dec,1));
            $dd = intval( $dec );
            if ( $dec == 0 ) {
                $mgEW = "0°";
                $aip = "0°";
            } else if ( $dec > 0 ) {
                $mgEW = 'E' . format( $dd, 3 ) . format( ( round(  ( $dec - $dd ) * 60 , 1) * 10 ), 3 );
                $aip .= "°E";
                $nav.= "°E";
                $EW = "E";
            } else if ( $dec < 0 ) {
                $mgEW = 'W' . format( ( $dd * -1 ), 3 ) . format( ( round( ( $dec - $dd ) * -1 * 60 , 1 ) * 10 ), 3 );
                $aip .= "°W";
                $nav.= "°W";
                $EW = "W";
            }
             
            $time=strtotime($date);
            $yr=(int)date("Y",$time); 
            $month=(int)date("m",$time); 

            $mth = sprintf("%02d", $month);
 
            // var ddy = parseInt( dotdecy )
            $mgEW = $mgEW . ' '. $mth .  substr($yr,-2);
            $aip = $aip . ' (' .  $yr . ')';
            $nav = $nav . ' (' .  $yr . ')';
           
            $pyear = '';
            $peryear = '';
            $decinc = '';
            $ddc = $dotdecy;
            // console.log(ddy,dotdecy,ddc)
            if ( $dotdecy > 0 ) {
                $ddc = round( $ddc * 60 );
                $pyear =  round(abs($dotdecy),2) . "°"; // dd + "°" + ( ( dotdecy - ddy ) * 60 ).toFixed()
                $peryear = $pyear."'E";
                if ( $EW == 'E' ) {
                    $decinc = $pyear . ' Increasing';
                } else {
                    $decinc = $pyear . ' Decreasing';
                }
            } else {
                $pyear =  round(abs($dotdecy),2) . "°"; // ( ddy * -1 ) + "°" + ( ( dotdecy - ddy ) * -1 * 60 ).toFixed()
                $peryear = $pyear . "'W";
                $ddc = round( ( $ddc * 60 ) * -1 );
                if ( $EW == 'E' ) {
                    $decinc = $pyear . ' Decreasing';
                } else {
                    $decinc = $pyear . ' Increasing';
                }
            }
            if ( $ddc == 0 ) {
                $peryear = '0';
            }
            $nav = $nav;
            $aip = $aip . ' / ' . $decinc;
 
            // return { dec: dec, magvar: mgEW, tanggal: tgl, dip: dip, ti: ti, bh: bh, bx: bx, by: by, bz: bz, lat: glat, lon: glon, gv: gv };
            $ret = (object)[
                'dec'=> $dec,
                'magvar'=> $mgEW,
                'aip'=> $aip,
                'nav'=> $nav,
                'cy'=> $dotdecy,
                'py'=> $peryear,
                'dip'=> $dip,
                'ti'=> $ti,
                'bh'=> $bh,
                'bx'=> $bx,
                'by'=> $by,
                'bz'=> $bz,
                'lat'=> $glat,
                'lon'=> $glon,
                'gv'=> $gv
            ];
            // var_dump($ret);
            return $ret;
        }
        public function calc ($glat, $glon, $h, $date) {
            return $this->calculate($glat, $glon, $h, $date);
        }
        public function mag ($glat, $glon, $h, $date) {
            return $this->calculate($glat, $glon, $h, $date);
        }
}
/*
how to use Geomag

$newGeomag =  new Geomag();

$myMag = $newGeomag->mag($glat, $glon, $h, $date);  // calculate   

*/ 
class GeoidHeights
    {
        private const l_value = 65341;
        private const nmax = 360;

        public $drts;
        public $dirt;
        
        private $cc=[];
        private $cs=[];
        private $hc=[];
        private $hs=[];

        public function __construct(){
            include(app_path() . '/Logic/coef.php');   
            $this->GeoidHeights();
            // var_dump($this->drts);die;
        }
        public function GeoidHeights(){
            $nmx2p = 2 * self::nmax + 1;

            for ($n = 1; $n <= $nmx2p; $n++)
            {
                $this->drts[$n] = sqrt($n);
                $this->dirt[$n] = 1 / $this->drts[$n];
            }
        } 
        public function undulation($degLat, $degLon)
        {
            // var_dump($this->drts);die;   
            $lat = $degLat * pi() / 180;
            $lon = $degLon * pi() / 180;

            $rlat; $gr; $re;
            $i; $j; $m;
            $k = self::nmax + 1;
            $p = array();
            $sinml = array();
            $cosml = array();
            
            $r_ret = self::radgra($lat, $lon);
            // var_dump($r_ret);die;
            $rlat = pi() / 2 - $r_ret['rlat'];
            $rleg = array(361 + 1);

            for ($j = 1; $j <= $k; $j++)
            {
                $m = $j - 1;
                $l_ret = self::legfdn($m, $rlat, $rleg);
                // var_dump($l_ret);die;
                for ($i = $j; $i <= $k; $i++)
                    $p[($i - 1) * $i / 2 + $m + 1] = $l_ret[$i];
            }

            $d_ret = self::dscml($lon, $sinml, $cosml);

            return self::hundu($p, $d_ret['sinml'], $d_ret['cosml'], $r_ret['gr'], $r_ret['re']);
        }

        private static function radgra($lat, $lon)
        /*this subroutine computes geocentric distance to the point,
        the geocentric latitude,and
        an approximate value of normal gravity at the point based
        the constants of the wgs84(g873) system are used*/
        {
            $a = 6378137;
            $e2 = .00669437999013;
            $geqt = 9.7803253359;
            $k = .00193185265246;

            $n;
            $t1 = sin($lat) *sin($lat); $t2; $x; $y; $z;
            $n = $a / sqrt(1 - $e2 * $t1);
            $t2 = $n * cos($lat);
            $x = $t2 * cos($lon);
            $y = $t2 * sin($lon);
            $z = ($n * (1 - $e2)) * sin($lat);
            $re = sqrt($x * $x + $y * $y + $z * $z);/*compute the geocentric radius*/
            $rlat =atan($z / sqrt($x * $x + $y * $y));/*compute the geocentric latitude*/
            $gr = $geqt * (1 + $k * $t1) / sqrt(1 - $e2 * $t1);/*compute normal gravity:units are m/sec**2*/
            $ret =[
                'rlat' => $rlat,
                'gr' => $gr,
                're' => $re
            ];
            return $ret;
        }

        private function legfdn($m, $theta, $rleg)
        /*this subroutine computes  all normalized legendre function
        in "rleg". order is always
        m, and colatitude is always theta  (radians). maximum deg
        is  nmx. all calculations in double precision.
        ir  must be set to zero before the first call to this sub.
        the dimensions of arrays  rleg must be at least equal to  nmx+1.
        Original programmer :Oscar L. Colombo, Dept. of Geodetic Science
        the Ohio State University, August 1980
        ineiev: I removed the derivatives, for they are never computed here*/
        {
            // var_dump($this->drts);die;

            $cothet;
            $sithet;
            $rlnn = array();
            $nmx1 = self::nmax + 1;
            $m1 = $m + 1;
            $m2 = $m + 2;
            $m3 = $m + 3;
            $n; $n1; $n2;

            $cothet = cos($theta);
            $sithet = sin($theta);
            
            /*compute the legendre functions*/
            $rlnn[1] = 1;
            $rlnn[2] = $sithet * $this->drts[3];
            for ($n1 = 3; $n1 <= $m1; $n1++)
            {
                $n = $n1 - 1;
                $n2 = 2 * $n;
                $rlnn[$n1] = $this->drts[$n2 + 1] * $this->dirt[$n2] * $sithet * $rlnn[$n];
            }
            switch ($m)
            {
                case 1:
                    $rleg[2] = $rlnn[2];
                    $rleg[3] = $this->drts[5] * $cothet * $rleg[2];
                    break;
                case 0:
                    $rleg[1] = 1;
                    $rleg[2] = $cothet * $this->drts[3];
                    break;
            }
            $rleg[$m1] = $rlnn[$m1];
            if ($m2 <= $nmx1)
            {
                $rleg[$m2] = $this->drts[$m1 * 2 + 1] * $cothet * $rleg[$m1];
                if ($m3 <= $nmx1)
                    for ($n1 = $m3; $n1 <= $nmx1; $n1++)
                    {
                        $n = $n1 - 1;
                        if (($m == 0 && $n < 2) || ($m == 1 && $n < 3)) continue;
                        $n2 = 2 * $n;
                        $rleg[$n1] = $this->drts[$n2 + 1] * $this->dirt[$n + $m] * $this->dirt[$n - $m] *
                                ($this->drts[$n2 - 1] * $cothet * $rleg[$n1 - 1] - $this->drts[$n + $m - 1] * $this->drts[$n - $m - 1] * $this->dirt[$n2 - 3] * $rleg[$n1 - 2]);
                    }
            }
            return $rleg;
        }

        private function hundu($p, $sinml, $cosml, $gr, $re)
        {
            /*constants for wgs84(g873);gm in units of m**3/s**2*/
            $gm = .3986004418e15;
            $ae = 6378137;
            $arn; $ar; $ac; $a; $sum; $sumc; $tempc; $temp;
            $k; $n ;$m;
            $ar = $ae / $re;
            $arn = $ar;
            $ac = $a = 0;
            $k = 3;
            for ($n = 2; $n <= self::nmax; $n++)
            {
                $arn *= $ar;
                $k++;
                $sum = $p[$k] * $this->hc[$k];
                $sumc = $p[$k] * $this->cs[$k];
                for ($m = 1; $m <= $n; $m++)
                {
                    $k++;
                    $tempc = $this->cs[$k] * $cosml[$m] + $this->cs[$k] * $sinml[$m];
                    $temp = $this->hc[$k] * $cosml[$m] + $this->hs[$k] * $sinml[$m];
                    $sumc += $p[$k] * $tempc;
                    $sum += $p[$k] * $temp;
                }
                $ac += $sumc;
                $a += $sum * $arn;
            }
            $ac += $this->cs[1] + $p[2] * $this->cs[2] + $p[3] * ($this->cs[3] * $cosml[1] + $this->cs[3] * $sinml[1]);
            /*add haco=ac/100 to convert height anomaly on the ellipsoid to the undulation
            add -0.53m to make undulation refer to the wgs84 ellipsoid.*/
            return $a * $gm / ($gr * $re) + $ac / 100 - .53;
        }

        private function dscml($rlon, $sinml, $cosml)
        {
            $a; $b;
            $m;
            $a = sin($rlon);
            $b = cos($rlon);
            $sinml[1] = $a;
            $cosml[1] = $b;
            $sinml[2] = 2 * $b * $a;
            $cosml[2] = 2 * $b * $b - 1;
            for ($m = 3; $m <= self::nmax; $m++)
            {
                $sinml[$m] = 2 * $b * $sinml[$m - 1] - $sinml[$m - 2];
                $cosml[$m] = 2 * $b * $cosml[$m - 1] - $cosml[$m - 2];
            }
            
            $ret =[
                'sinml' => $sinml,
                'cosml' => $cosml,
            ];
            return $ret; 
        }
    }
    if(!function_exists('GeoHi')){
        // function GeoHi($lat, $lon){
        //     // var_dump($lat, $lon);
        //     if(!is_numeric($lat) && !is_numeric($lon)){
        //         $lat = toDecimal($lat);
        //         $lon = toDecimal($lon);
        //     }
        //     $geoid  = new GeoidHeights();
        //     $hi= $geoid->undulation($lat, $lon);
        //     return $hi;                 
        //     // $geoid  = new GeoidHeights();
        //     // $hi= $geoid->undulation($lat, $lon);
        //     // return $hi;                 
        // }
        function GeoHi($latin,$lonin){
            $lat = toDecimal($latin,true);
            $lon = toDecimal($lonin,true);
            
            $field = array( "latDegrees"=>$lat["deg"], 
                            "latMinutes"=>$lat["min"], 
                            "latSeconds"=>$lat["sec"],
                            "lonDegrees"=>$lon["deg"], 
                            "lonMinutes"=>$lon["min"], 
                            "lonSeconds"=>$lon["sec"],
                            "convertToFeet" => false
                        );
            $post_field = json_encode($field);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://earth-info.gs.mil/geoid/egm08/degminsec',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS =>$post_field,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $ret = json_decode($response,true);
            return $ret['height']; 
        }
    }

     /* how to use geoid undulation

    $geoid  = new GeoidHeights();
    $hi= $geoid->undulation(-7.5133269, 110.7470487);
    echo $hi;
    */
    // function GeoHi($latin,$lonin){
    //     $lat = toDecimal($latin,true);
    //     $lon = toDecimal($lonin,true);

    //     $field = array( "latDegrees"=>$lat["deg"], 
    //                     "latMinutes"=>$lat["min"], 
    //                     "latSeconds"=>$lat["sec"],
    //                     "lonDegrees"=>$lon["deg"], 
    //                     "lonMinutes"=>$lon["min"], 
    //                     "lonSeconds"=>$lon["sec"],
    //                     "convertToFeet" => false
    //                 );
    //     $post_field = json_encode($field);

    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //       CURLOPT_URL => 'https://earth-info.gs.mil/geoid/egm08/degminsec',
    //       CURLOPT_RETURNTRANSFER => true,
    //       CURLOPT_ENCODING => '',
    //       CURLOPT_MAXREDIRS => 10,
    //       CURLOPT_TIMEOUT => 0,
    //       CURLOPT_FOLLOWLOCATION => true,
    //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //       CURLOPT_CUSTOMREQUEST => 'PUT',
    //       CURLOPT_POSTFIELDS =>$post_field,
    //       CURLOPT_HTTPHEADER => array(
    //         'Content-Type: application/json'
    //       ),
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);
    //     $ret = json_decode($response,true);
    //     return $ret['height']; 
    // }
    if(!function_exists('CreateApSegment')){
        function CreateApSegment($data)
        {
            $hasil = '';
            if(!empty($data)){
                for ($i=0; $i < count($data); $i++) { 
                    // dd($data[$i]);
                    $pointgeom1 = '';
                    $pointgeom2 = '';
                    $centgeom = '';
                    if ( $data[$i]->shap == 'C' ) {
                        $acrlat = toDecimal($data[$i]->arc_lat);
                        $acrlon = toDecimal($data[$i]->arc_long );
                        // dd($acrlat,$acrlon,(float)$data[$i]->arc_dist);
                        $hasil=GetCircle($acrlat,$acrlon,(float)$data[$i]->arc_dist);
                    } else if ( $data[$i]->shap == "G" || $data[$i]->shap == "H" || $data[$i]->shap == "B" ||  $data[$i]->shap == "E" ) {
                        $acrlat1 = toDecimal($data[$i]->point1_lat);
                        $acrlon1 = toDecimal($data[$i]->point1_long);
                            if ( $i == 0 ) {
                                $hasil = $acrlon1.' '.$acrlat1;
                            } else {
                                $hasil = $hasil.','.$acrlon1.' '.$acrlat1;
                            }
                    } else {
                        $centlat = toDecimal($data[$i]->arc_lat);
                        $centlon = toDecimal($data[$i]->arc_long);
                        $pnt1lat = toDecimal($data[$i]->point1_lat);
                        $pnt1lon = toDecimal($data[$i]->point1_long);
                        $pnt2lat = toDecimal($data[$i+1]->point1_lat);
                        $pnt2lon = toDecimal($data[$i+1]->point1_long);
                        // dd($data[$i]->arc_lat, $data[$i]->arc_long, $data[$i]->point1_lat, $data[$i]->point1_long,$data[$i+1]->point1_lat, $data[$i+1]->point1_long,$data[$i]->shap);
                        $arccord= GetArc( $centlat,$centlon , $data[$i]->arc_dist, $pnt1lat, $pnt1lon, $pnt2lat, $pnt2lon, $data[$i]->shap);
                            if ( $i == 0 ) {
                                // awalcord=$pointgeom1.Decimal.longitude + ' ' + $pointgeom1.Decimal.latitude
                                $hasil=$arccord;
                            } else {
                                $hasil= $hasil.','.$arccord;
                            }

                    } 
                }
            }
            return 'POLYGON(('.$hasil.'))';
        }
    }
    if(!function_exists('GetArc')){
    function GetArc($centlat,$centlon,$radius,$point1lat,$point1lon,$point2lat,$point2lon,$rotation)
    {
        $hasil = '';$lXvs1;
        // console.log(centlat,centlon,radius,point1lat,point1lon,point2lat,point2lon,rotation)
        $trk1 = Getbearing( $centlat, $centlon, $point1lat, $point1lon,false );
        $trk2 = Getbearing( $centlat, $centlon, $point2lat, $point2lon,false);
        // dd($radius,$trk2->DistanceReal);
        // $radius=$trk2->DistanceReal;
        // var_dump($trk1,$trk2);
        // dd($trk1,$trk2, $rotation);
        $track1 = $trk1->TrackOutReal;
        $track2 = $trk2->TrackOutReal;
        // dd('TRACK ',$track1,$track2);
        if ( $rotation == 'R' ) {
            if ( $track1 > $track2 ) {
                $track2 += 360;
            }
            $lXvs1 = $track1 - $track2;
            if ( $lXvs1 < 0 ) {
                $lXvs1 = $lXvs1 * -1;
            }
            if ( $lXvs1 > 5 ) {
                $track1 = round($track1) + 1;
                $track2 = round($track2) - 1;
                $hasil = $point1lon.' '.$point1lat;
                for ($brg = $track1; $brg <= $track2; $brg++) {
                    $bbr=$brg;
                    if ($brg>360){
                        $bbr=$brg - 360;
                    }
                    $hsl = GetPoint2( $centlat, $centlon, $bbr, $radius );
                    $hasil = $hasil.','.$hsl['longitude'].' '.$hsl['latitude'];
                    // var_dump($hasil);
                }
                $hasil =$hasil.','.$point2lon.' '.$point2lat;
                // dd($hasil);
            } else {
                $hasil = $point1lon.' '.$point1lat;
            }
            // console.log(hasil)
        } else if ( $rotation == 'L' ) {
            // dd($track1,$track2);
            if ( $track1 < $track2 ) {
                $track1 += 360;
            }
            $lXvs1 = $track1 - $track2;
            if ( $lXvs1 < 0 ) {
                $lXvs1 = $lXvs1 * -1;
            }
            // dd(round($track1));
            if ( $lXvs1 > 5 ) {
                $track1 = round($track1) + 1;
                $track2 = round($track2) - 1;
                $hasil = $point1lon.' '.$point1lat;
                for ($brg = $track1; $brg >= $track2; $brg-- ) {
                    $bbr=$brg;
                    if ($brg>360){
                        $bbr=$brg - 360;
                    }
                    $hsl = GetPoint2( $centlat, $centlon, $brg, $radius );
                    // dd($hsl['longitude']);
                    $hasil = $hasil.','.$hsl['longitude'].' '.$hsl['latitude'];
                }
                $hasil = $hasil.','.$point2lon.' '.$point2lat;
            } else {
                $hasil = $point1lon.' '.$point1lat;
            }
        }
    //    dd($hasil);
        return  $hasil;
    }
    }
    if(!function_exists('GetRadial')){
        function GetRadial($bearing)
        {
            if ($bearing > 180){
                return $bearing-180 ;
            }else{
                $hsl=$bearing + 180;
                // if ($hsl==360){
                //     $hsl=0;
                // }
                return $hsl ;
            }
        }
    }
    if(!function_exists('GetCircle')){
        function GetCircle($lat,$lon,$radius)
        {
            $hasil = '';
            for ($brg = 0; $brg <= 360; $brg++) {
                $hsl = GetPoint2( $lat, $lon, $brg, $radius );
                if ( $brg == 0 ) {
                    $hasil=$hsl['longitude'].' '.$hsl['latitude'];
                } else {
                    $hasil =$hasil. ','.$hsl['longitude'].' '.$hsl['latitude'];
                }
                // console.log(hasil)
            }
            // console.log(hasil)
            return $hasil ;
        }
    }
    if(!function_exists('GetPoint2')){
        function GetPoint2($latitude1, $longitude1, $bearing, $distance ) {
            // console.log('MYFUNCT GETPOINT 2', latitude1, longitude1, bearing, distance)
            $a  = 6378137;
            $b = 6356752.314245;
            $f = 1 / 298.257223563;
            $Lat1= deg2rad($latitude1);
            $Lon1= deg2rad($longitude1);
            $α1= deg2rad($bearing);
            $s= Nm2Km($distance) * 1000;

            $sinα1= sin( $α1);
            $cosα1=cos( $α1);

            $tanU1= (1 - $f) * tan($Lat1);
            $cosU1= 1 / sqrt((1 + $tanU1 * $tanU1));
            $sinU1= $tanU1 * $cosU1;
            $σ1= atan2($tanU1, $cosα1);
            $sinα= $cosU1 * $sinα1;
            $cosSqα= 1 - $sinα * $sinα;
            $uSq= $cosSqα * ($a * $a - $b * $b) / ($b * $b);
            $AA= 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
            $BB= $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));

            $cos2σM=0; $sinσ=0; $cosσ=0; $Δσ=0;

            $σ = $s / ($b * $AA);
            $σA=0;
            $iterations= 0;
            While (abs($σ - $σA) > 0.000000000001 && ++$iterations < 200){
                $cos2σM = cos(2 * $σ1 + $σ);
                $sinσ = sin($σ);
                $cosσ = cos($σ);
                $Δσ = $BB * $sinσ * ($cos2σM + $BB / 4 * ($cosσ * (-1 + 2 * $cos2σM * $cos2σM) -
                    $BB / 6 * $cos2σM * (-3 + 4 * $sinσ * $sinσ) * (-3 + 4 * $cos2σM * $cos2σM)));
                $σA = $σ;
                $σ = $s / ($b * $AA) + $Δσ;

            }

        If ($iterations >= 200) {
            alert("Formula failed to converge");

        }
        // dd(11.326062870123 % 3.1415926535898);

       $x= $sinU1 * $sinσ - $cosU1 * $cosσ * $cosα1;
       $Lt2= atan2($sinU1 * $cosσ + $cosU1 * $sinσ * $cosα1, (1 - $f) * sqrt($sinα * $sinα + $x * $x));
       $Lon= atan2($sinσ * $sinα1, $cosU1 * $cosσ - $sinU1 * $sinσ * $cosα1);
       $C= $f / 16 * $cosSqα * (4 + $f * (4 - 3 * $cosSqα));
       $L= $Lon - (1 - $C) * $f * $sinα *
            ($σ + $C * $sinσ * ($cos2σM + $C * $cosσ * (-1 + 2 * $cos2σM * $cos2σM)));
            // var_dump($L);
        $Ln2=fmod(($Lon1 + $L + 3 * pi()), (2 * pi()) - pi());
    //    $Ln2=(float)($Lon1 + $L + 3 * pi()) % (2 * pi()) - pi(); // normalise to -180..+180
       
        // var_dump($Ln2,($Lon1 + $L + 3 * pi()),(2 * pi()) - pi());
    //    $α2= Math.Atan2(sinα, -x)
    //     α2 = (α2 + 2 * Math.PI) Mod (2 * Math.PI)   ' // normalise to 0..360
        $Lat2= rad2deg($Lt2);
        $Lon2= rad2deg($Ln2);

        return array('latitude' => $Lat2 , 'longitude' =>$Lon2);
                
                
            // this.Getbearing( latitude1, longitude1, Lat2, Lon2 )

        }
    }
    if(!function_exists('nm2km')){
        function nm2km($value ) {
            return $value / 0.5399568035;
        }
    }
    if(!function_exists('m2Nm')){
        function m2Nm( $value ) {
            return $value * 0.000539957;
        }
    }
    if(!function_exists('GetCenterAsp')){
    function GetCenterAsp($seg,$asptype,$circle=false){
        $gg='';
        if ( ($seg->arpt_ident == null || $seg->arpt_ident == '') && ($seg->nav_id == null || $seg->nav_id == '') ) { 
            $hsl1 = toDecimal($seg->arc_lat);
            $hsl2 = toDecimal($seg->arc_long);
            $cord1=toWgs($hsl1,'LAT');
            $cord2=toWgs($hsl2,'LON');
            if ($asptype == "FIR" ||$asptype == "UIR" ) {
                $gg ='('. $cord1[0]['VIEW']." ".$cord2[0]['VIEW'].')';
            } else {
                $gg ='('. $cord1[0]['VIEW']." ".$cord2[0]['VIEW'].')';
            }
        } else {
            if ($seg->navaid == null || $seg->navaid == '') {
                $garpt =' ARP';
                $cord1=toWgs($seg->airport[0]->geom->coordinates[1],'LAT');
                $cord2=toWgs($seg->airport[0]->geom->coordinates[0],'LON');
                // dd($cord1,$cord2);
                $garpt =" ARP (".$cord1[0]['VIEW']." ".$cord2[0]['VIEW'].")";
                if ($circle==true){
                    $gg =$garpt;
                }else{
                    $gg =$garpt." to";
                    
                }
            } else {
                $cord1=toWgs($seg->navaid[0]->geom->coordinates[1],'LAT');
                $cord2=toWgs($seg->navaid[0]->geom->coordinates[0],'LON');
                $gnav =$seg->navaid[ 0 ]->nav_ident ." " .$seg->navaid[ 0 ]->definition." (".$cord1[0]['VIEW']." ".$cord2[0]['VIEW'].")";
                if ($circle==true){
                    $gg = $gnav;
                }else{
                    $gg = $gnav ." to";
                    
                }
                
            }
        }
        return $gg;
    }
    }
    if(!function_exists('GetSegmentText')){
    function GetSegmentText($asp,$type){
        $aspseg=$asp->boundary;
        $garpt='';$gnav='';$gen='';
        $gg = ''; $asptype = $type;
        for ($i = 0; $i < count($aspseg); $i++ ){
                // console.log( '$aspseg[$i]->shap', $aspseg[$i]->shap, aspseg[ i ])
                // console.log(gg)
                if ( $aspseg[$i]->shap == "C" ) {
                    $gg = "A circle with radius of ".$aspseg[$i]->arc_dist." NM centered at ";
                    $gg =$gg.GetCenterAsp($aspseg[$i],$asptype,true);

                } else if ( $aspseg[$i]->shap == "L" ) {
                    $hsl1 = toDecimal($aspseg[$i]->point1_lat);
                    $hsl2 = toDecimal($aspseg[$i]->point1_long);
                    $cord1=toWgs($hsl1,'LAT');
                    $cord2=toWgs($hsl2,'LON');

                    if ($asptype == "FIR" ||$asptype == "UIR" ) {
                        if ( $gg == "" ) {
                            $gg = $cord1[0]['FIR']." ".$cord1[0]['FIR'];
                        }else{
                            $gg =$gg.' - '.$cord1[0]['FIR']." ".$cord1[0]['FIR'];
                        }
                        $gg = $gg. " thence anti-clockwise along the arc of a circle radius ".$aspseg[$i]->arc_dist ." NM centered at ";
                        $gg =$gg.GetCenterAsp($aspseg[$i],$asptype);
                    } else {
                        if ( $gg == "" ) {
                            $gg = $cord1[0]['ENR']." ".$cord1[0]['ENR'];
                        }else{
                            $gg =$gg.' - '.$cord1[0]['ENR']." ".$cord1[0]['ENR'];
                        }
                        $gg = $gg. " thence anti-clockwise along the arc of a circle radius ".$aspseg[$i]->arc_dist ." NM centered at ";
                        $gg =$gg.GetCenterAsp($aspseg[$i],$asptype);
                    }
                    
                } else if ( $aspseg[$i]->shap == "R" ) {
                    $hsl1 = toDecimal($aspseg[$i]->point1_lat);
                    $hsl2 = toDecimal($aspseg[$i]->point1_long);
                    $cord1=toWgs($hsl1,'LAT');
                    $cord2=toWgs($hsl2,'LON');

                    if ($asptype == "FIR" ||$asptype == "UIR" ) {
                        if ( $gg == "" ) {
                            $gg = $cord1[0]['FIR']." ".$cord1[0]['FIR'];
                        }else{
                            $gg =$gg.' - '.$cord1[0]['FIR']." ".$cord1[0]['FIR'];
                        }
                        $gg = $gg. " thence clockwise along the arc of a circle radius ".$aspseg[$i]->arc_dist." NM centered at ";
                        $gg =$gg.GetCenterAsp($aspseg[$i],$asptype);
                    } else {
                        if ( $gg == "" ) {
                            $gg = $cord1[0]['ENR']." ".$cord1[0]['ENR'];
                        }else{
                            $gg =$gg.' - '.$cord1[0]['ENR']." ".$cord1[0]['ENR'];
                        }
                        $gg = $gg. " thence clockwise along the arc of a circle radius ".$aspseg[$i]->arc_dist." NM centered at ";
                        $gg =$gg.GetCenterAsp($aspseg[$i],$asptype);
                    }
                    
                } else if ( $aspseg[$i]->shap == "G" ) {
                    if ($aspseg[$i]->remarks== null || $aspseg[$i]->remarks==''){
                        // $gg=$gg.'---';
                    }else{
                        if ( $gen == "" ) {
                            if ( $aspseg[$i]->remarks !== "" || $aspseg[$i]->remarks !== null ) {
                                $gen = $aspseg[$i]->remarks;
                                $gg =$gg." ".$gen;
                            }
                        } else {
                            if ( $aspseg[$i]->remarks == "" || $aspseg[$i]->remarks == null ) {
                                // console.log( 'gen ', gen )
                            } else {
                                if ( $gen !== $aspseg[$i]->remarks ) {
                                    $gen = $aspseg[$i]->remarks;
                                    $gg =$gg." ".$gen;
                                }
                            }
                        }
                    }

                } else {
                    $hsl1 = toDecimal($aspseg[$i]->point1_lat);
                    $hsl2 = toDecimal($aspseg[$i]->point1_long);
                    $cord1=toWgs($hsl1,'LAT');
                    $cord2=toWgs($hsl2,'LON');
                    // dd($cord1,$cord2);
                    if ($asptype == "FIR" || $asptype == "UIR") {
                        $lat = $cord1[0]['FIR'];
                    }else{
                        $lat = $cord1[0]['ENR'];
                    }

                    if( $hsl1  == 0){
                        $lat = "equator";
                    }
                    if ($asptype == "FIR" ||$asptype == "UIR" ) {
                        // if ( i == 0 ) {
                        if ( $gg == '' ) {
                            $gg = $lat ." ".$cord2[0]['FIR'];
                        } else {
                            $gg = $gg." - ".$lat." ".$cord2[0]['FIR'];
                        }
                    } else {
                        // if ( i == 0 ) {
                        if ( $gg == '' ) {
                            $gg = $lat ." ".$cord2[0]['ENR'];
                        } else {
                            $gg = $gg." - ".$lat." ".$cord2[0]['ENR'];
                        }
                    }

                }
                // console.log('gg',gg)
                $gg =str_replace("to - ", "to ",(str_replace("until - ","until ",$gg)));
            }
            // console.log('HASIL SEG',gg)
            return $gg;
        }
    }
    // function generateBarcodeNumber() {
    //     $number = mt_rand(1000000000, 9999999999); 
 
    //     if (barcodeNumberExists($number)) {
    //         return generateBarcodeNumber();
    //     }
    //     return $number;
    // }

    // function barcodeNumberExists($number) { 
    //     return User::whereBarcodeNumber($number)->exists();
    // }

if(!function_exists('DiffDate')){
    function DiffDate($now,$last){
        $t1 = Carbon::parse($now);
        $t2 = Carbon::parse($last);
        $diff = $t1->diff($t2);
        return $diff;    
    }
}
if(!function_exists('acronym')){
    function acronym ($string){
        $words = explode(" ", $string);
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= $w[0];
        }
        echo $acronym; 
    }
}
if(!function_exists('getrwyopposite')){
    function getrwyopposite($rwy_arr,$rwyident){
        $rwylength=strlen($rwyident);$pref='';
        $rid=substr($rwyident,0,2);$ridopp='';$result=[];
        if ((int)$rid > 18){
            $ridopp=(int)$rid - 18;
        }else{
            $ridopp=(int)$rid + 18;
        }
        $pref=sprintf("%02d", $ridopp);
        // dd($rwylength,$rwyident,substr($rwyident,2,1));
        // var_dump($rwyident,$rid,(int)$rid,$ridopp);
            if ($rwylength==3){
                switch (substr($rwyident,2,1)) {
                    case 'L':
                        $pref=  $ridopp.'R';
                        break;
                    case 'R':
                        $pref=$ridopp.'L';
                        break;
                    case 'C':
                        $pref=$ridopp.'C';
                        break;
                    
                }
            }
            // dd($pref);
            for ($i=0; $i < count($rwy_arr) ; $i++) { 
                # code...
                foreach ($rwy_arr[$i]->physicals as $key => $val) {
                    if ($val->rwy_ident==$pref){
                        $result=$val;
                        break;
                        // dd($val->geom->coordinates[0]);
                        // var_dump($val->rwy_ident,$val->geom);
                        // $lat1=$val->geom->getlat();$lon1=$val->geom->getlng();
                        // $val->geom->getlng();
                    }
                }
            }
        // dd($pref,$rwy_arr,$result);
        return $result;
    }
}
if(!function_exists('drawtransition')){
    function drawtransition($proc_id){
        $originalInput=Request::input();
        $trans = getDataApi($originalInput, 'api/transition/temp?proc_id='.$proc_id.'&deleted=0');
        // dd($trans,$proc_id);
        $arptident=$trans[0]->arpt_ident;
        $chart_type=$trans[0]->chart_type;
        $arpt = getDataApi($originalInput,'/api/airports?arpt_ident='.$arptident);
        if ($chart_type=='46' && $trans[0]->definition=='Runway Transition'){
            // dd($arpt[0]->runwaystemp,$trans[0]->rwy_trans);
            $rwyopp= getrwyopposite($arpt[0]->runwaystemp,$trans[0]->rwy_trans);
            $prevdata['lat']=$rwyopp->geom->coordinates[1];
            $prevdata['lon']=$rwyopp->geom->coordinates[0];
            $prevdata['bearing']=$rwyopp->mag_brg;
            $prevdata['path']=$trans[0]->segment[0]->path_term;
            $prevdata['course']=$trans[0]->segment[0]->mag_crs;
            $prevdata['fix_lat']=$rwyopp->geom->coordinates[1];
            $prevdata['fix_lon']=$rwyopp->geom->coordinates[0];
            $prevdata['hasil']=[];
        }else{
            $rslt= getfixedcoordinate($trans[0]->segment[0]);

                $prevdata['lat']=$rslt['lat'];
                $prevdata['lon']=$rslt['lon'];
                $prevdata['fix']=$rslt['fix'];

            $prevdata['bearing']=$trans[0]->segment[0]->mag_crs;
            $prevdata['path']=$trans[0]->segment[0]->path_term;
            $prevdata['course']='';
            $prevdata['hasil']=[];
        }
        $tseg=$trans[0]->segment;
        usort($tseg, fn($a, $b) => strnatcmp($a->seq_num, $b->seq_num));
        // usort($tseg, function($a, $b) {return strcmp($a->seq_num, $b->seq_num);});
        // dd($tseg);
        $hasil='';$update=true;
        foreach ($tseg as $key => $val) {
            // $pathterm=$val->path_term;
            // unset($rslt);
            // var_dump($key);
            $rslt= pathterm($prevdata,$val,$tseg,$key,count($tseg),$chart_type);
            if ( $rslt==''){
                // dd($prevdata,$val,$tseg,$key,count($tseg),$chart_type);
                $update=false;
                break;
            }else{
                if ($hasil==''){
                    $hasil=$rslt[0];
                }else{
                    // var_dump($rslt[1]);
                    $hasil=$hasil.','. $rslt[0];
                }
                unset($prevdata);
                $prevdata=$rslt[1];
                
            }
        
        }
        // die();
        // dd(strstr($hasil,',,'));
        if ( $update==true){
            $lanjut=true;
            if (strstr($hasil,',,')==true){
                $lanjut=false;
            }; 
            if ( $lanjut==true){
                $trgeom='MULTILINESTRING(('.$hasil.'))';
                updatetransseg($proc_id,$trans[0]->id,$trgeom);
            }

        }
        // $arptident=$trans[0]->arpt_ident;
    }
}
if(!function_exists('pathterm')){
    function pathterm($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        // dd($data->path_term,'$data->path_term');
        $result='';
        switch ($data->path_term) {
            case 'IF':
                $result= IFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                
                break;
            case 'VR':
            case 'CR':
                $result= VRLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'VI':
            case 'CI':
                $result= VILEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'VA':
            case 'CA':
                $result= VALEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'CF':
                $result= CFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'FC':
                // $result= FCLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                // break;
            case 'FD':
            case 'CD':
            case 'VD':
                $result=CDLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'RF':
              
                $result=RFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'AF':
                $result=AFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'HM':
            case 'HA':
            case 'HF':
                $result= HMLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                break;
            case 'DF':
                $result= DFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                // dd($result);
                break;
            case 'TF':
                $result= TFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type);
                // dd('TF',$prevd);
                break;

        }
        return $result;
        // dd($prevd,$data);
    }
}

if(!function_exists('IFLEG')){
    function IFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $hasil=$prevd;
        $lat=$prevd['lat'];
        $lon=$prevd['lon'];
        $prevdata['lat']=$lat;
        $prevdata['lon']=$lon;
        $prevdata['fix_lat']=$lat;
        $prevdata['fix_lon']=$lon;
        $prevdata['bearing']=$proc[$idx+1]->mag_crs;
        $prevdata['path']=$data->path_term;
        $prevdata['course']=$proc[$idx+1]->mag_crs;
        $prevdata['hasil']=[];

        // dd($prevdata,$proc[$idx+1]);
        $hasil=$lon.' '.$lat;
    return array($hasil,$prevdata);
    }
}
if(!function_exists('TFLEG')){
    function TFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $hasil=$prevd;
        $rslt= getfixedcoordinate($data);
        if ($rslt['lat']=='' || $prevd==''){
            $hasil='';$prevdata='';
        }else{
            // dd($prevd);
            $lat=$prevd['lat'];
            $lon=$prevd['lon'];
            $fix_lat=$rslt['lat'];
            $fix_lon=$rslt['lon'];
            $hasil=$lon.' '.$lat;
            
            $hasil=$hasil.','.GetEndPointFB($idx,$lat,$lon,$fix_lat,$fix_lon,$proc);
            $akh=explode(',',$hasil);
            $jml=count($akh)-1;
            $lastCrs=GetLastCourse($hasil);
            $akh1=explode(' ',$akh[$jml]);
            $prevdata['lat']=$akh1[1];
            $prevdata['lon']=$akh1[0];
            $prevdata['fix_lat']=$fix_lat;
            $prevdata['fix_lon']=$fix_lon;
            $prevdata['bearing']=$lastCrs;
            $prevdata['path']=$data->path_term;
            $prevdata['course']=$lastCrs;
            $prevdata['hasil']=[];
            
        }

       
    return array($hasil,$prevdata);
    }
}
if(!function_exists('VALEG')){
    function VALEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $hasil=$prevd['lon'].' '.$prevd['lat'];
        $rsl= calculate_va($prevd['lat'],$prevd['lon'],$data->mag_crs,$data->alt1);
        $lat=$rsl['latitude'];
        $lon=$rsl['longitude'];
        // dd($prevd,$data,$seq,$rsl);
        $currenfix=$lon.' '.$lat;
        $hasil = $hasil.','.$currenfix;
        // var_dump( $hasil);
        // $hasil = $hasil.','.$currenfix;
        $prevdata['lat']=$lat;
        $prevdata['lon']=$lon;
        $prevdata['fix_lat']=$lat;
        $prevdata['fix_lon']=$lon;
        $prevdata['bearing']=$data->mag_crs;
        $prevdata['path']=$data->path_term;
        $prevdata['course']=$data->mag_crs;
        $prevdata['hasil']=[];
        return array($hasil,$prevdata);
    }
}
if(!function_exists('VRLEG')){
    function VRLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $lat=$prevd['lat'];
        $lon=$prevd['lon'];
        $fix_lat=$lat;
        $fix_lon=$lon;
        $pMagCrs=$prevd['course'];
        $pCrs =$data->mag_crs;
        $pCrsReal=GetCourseTrue(true,$lat,$lon,$pCrs);
        if ($pCrsReal < 0){
            $pCrsReal += 360;
        }
        $pTurn=$data->turn_dir;
        if ($data->turn_dir==null){
            $pTurn= GetTurn($pMagCrs, $pCrsReal);
        }
        $bear=$data->theta;
        $prad=2;$hasil='';
        // dd($pMagCrs, $pCrsReal,$pTurn);
        $rrslt = CreateArcTurn($pTurn, $lat,$lon, $pMagCrs, $pCrsReal, $prad);
        foreach ($rrslt as $key => $value) {
            if ($key==0){
                $hasil=$value;
            }else{
                $hasil=$hasil.','.$value;

            }
        }
        $jml=count($rrslt)-1;
        $arr1=explode(' ',$rrslt[$jml]);
        // dd($arr1);
        $lon= $arr1[0];
        $lat= $arr1[1];

        $rslt= getrecdnav($data,'1');
        $recd1_lat=$rslt['lat'];
        $recd1_lon=$rslt['lon'];
        $pCUrrentFix = GetIntersection($recd1_lat, $recd1_lon, $bear, $lat,$lon,  $pCrsReal);
        // var_dump($lat,$lon);
        $fix_lat=$pCUrrentFix[0];
        $fix_lon=$pCUrrentFix[1];


        // dd($lat,$lon);
        // var_dump($hasil);
        $rslt=GetEndPointFB($idx,$lat,$lon,$fix_lat,$fix_lon,$proc);
        $hasil=$hasil.','.$rslt;
        $akh=explode(',',$hasil);
        // dd($hasil);
        $jml=count($akh)-1;
        $lastCrs=GetLastCourse($hasil);
        $akh1=explode(' ',$akh[$jml]);
        $prevdata['lat']=$akh1[1];
        $prevdata['lon']=$akh1[0];
        $prevdata['fix_lat']=$fix_lat;
        $prevdata['fix_lon']=$fix_lon;
        $prevdata['bearing']=$lastCrs;
        $prevdata['path']=$data->path_term;
        $prevdata['course']=$lastCrs;
        $prevdata['hasil']=[];
        // dd($hasil,$prevdata);

    return array($hasil,$prevdata);
    }
}

if(!function_exists('VILEG')){
    function VILEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $lat=$prevd['lat'];
        $lon=$prevd['lon'];
        $fix_lat=$lat;
        $fix_lon=$lon;
        $pMagCrs=$prevd['course'];
        $pCrs =$data->mag_crs;
        $pCrsReal=GetCourseTrue(true,$lat,$lon,$pCrs);
        if ($pCrsReal < 0){
            $pCrsReal += 360;
        }
        $pTurn=$data->turn_dir;
        $prad=2;$hasil='';
        if ($data->turn_dir==null){
            $pTurn= GetTurn($pMagCrs, $pCrsReal);
        }
        
    //    dd($pMagCrs, $pCrsReal);
        $rrslt = CreateArcTurn($pTurn, $lat,$lon, $pMagCrs, $pCrsReal, $prad);
        foreach ($rrslt as $key => $value) {
            if ($key==0){
                $hasil=$value;
            }else{
                $hasil=$hasil.','.$value;

            }
     
    }
        $jml=count($rrslt)-1;
        $arr1=explode(' ',$rrslt[$jml]);
        // dd($arr1);
        $lon= $arr1[0];
        $lat= $arr1[1];

        $rslt= getrecdnav($data,'1');
        if ($rslt==null){
            $recd= getfixedcoordinate($proc[$idx+1]);
            $recd1_lat=$recd['lat'];
            $recd1_lon=$recd['lon'];
            $bear=$proc[$idx+1]->mag_crs;
            // dd($recd,$bear);
        }else{
            $bear=$data->theta;
            $recd1_lat=$rslt['lat'];
            $recd1_lon=$rslt['lon'];
        }
        // dd($rslt);

        $pCUrrentFix = GetIntersection($recd1_lat, $recd1_lon, $bear, $lat,$lon,  $pCrsReal);
        // var_dump($lat,$lon);
        $fix_lat=$pCUrrentFix[0];
        $fix_lon=$pCUrrentFix[1];


        // dd($lat,$lon);
        // var_dump($hasil);
        $rslt1=GetEndPointFB($idx,$lat,$lon,$fix_lat,$fix_lon,$proc);
        $hasil=$hasil.','.$rslt1;
        $akh=explode(',',$hasil);
        // dd($hasil);
        $jml=count($akh)-1;
        $lastCrs=GetLastCourse($hasil);
        $akh1=explode(' ',$akh[$jml]);
        $prevdata['lat']=$akh1[1];
        $prevdata['lon']=$akh1[0];
        $prevdata['fix_lat']=$fix_lat;
        $prevdata['fix_lon']=$fix_lon;
        $prevdata['bearing']=$lastCrs;
        $prevdata['path']=$data->path_term;
        $prevdata['course']=$lastCrs;
        $prevdata['hasil']=[];
        // dd($hasil,$prevdata);

    return array($hasil,$prevdata);
    }
}

if(!function_exists('CFLEG')){
    function CFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        // dd($data,$prevd,'CFFFFFF');
        $hasil='';$lat='';$lon='';
        $rslt= getfixedcoordinate($data);
        if ($rslt['lat']=='' || $prevd==''){
            $hasil='';$prevdata='';
        }else{
            $lat=$prevd['lat'];
            $lon=$prevd['lon'];
            $prevfix_lat=$prevd['lat'];
            $prevfix_lon=$prevd['lon'];
            $fix_lat=$rslt['lat'];
            $fix_lon=$rslt['lon'];
            $currenfix=$fix_lon.' '.$fix_lat;
            $ppath = '';
            if ($idx > 0){
                $ppath = $proc[$idx - 1]->path_term;
            }
            $hasil=$lon.' '.$lat;
            $pPrevCrs =$prevd['course'];
            $pMagcrs =$data->mag_crs; 
            $pturn = $data->turn_dir;$CFArc = false; $prad=1;
            // dd($ppath,$pturn);
            if (($ppath == 'CD' || $ppath == 'VD') && ($pturn =='L' || $pturn =='R') && $chart_type == '45'){
                $CFArc = true;
                $pDist = $data->rt_dist_from;
                $hsl = GetPoint2( $fix_lat, $fix_lon, GetRadial($pMagcrs), $pDist );
                $prevfix_lat=$hsl['latitude'];
                $prevfix_lon=$hsl['longitude'];
            
                $trk=Getbearing($lat, $lon,$prevfix_lat,$prevfix_lon);
                $prad = $trk->DistanceReal / 2;
                $cent_lat=$trk->Midlat;
                $cent_lon=$trk->Midlon;
                // dd('CF ',$ppath);

            }
            $ctrk=Getbearing($prevfix_lat, $prevfix_lon,$fix_lat, $fix_lon);
            $fb = false;
            if ($CFArc == true){
                $fb = true;
                $arccord= GetArc( $cent_lat,$cent_lon , $prad,$lat,$lon, $prevfix_lat,$prevfix_lon, $pturn);
                $hasil=$hasil.','.$arccord;
            }else{
                if ($ppath == 'AF' || $ppath == 'RF' || $ppath == ''){
                    $fb = false;
                }else{
                    $fb = true;
                    if (abs($pPrevCrs - $pMagcrs) > 20 && $chart_type == '45'){
                        if ($pturn==null || $pturn==''){
                            $pturn= GetTurn($pPrevCrs, $pMagcrs);
                            $prad = GetJrkOff($lat,$lon,$fix_lat,$fix_lon);
                            $rrslt = CreateArcTurn($pturn, $lat,$lon, $pPrevCrs, $pMagcrs, $prad);
                            foreach ($rrslt as $key => $value) {
                            
                                    $hasil=$hasil.','.$value;
                            
                            }
                            // dd($pPrevCrs - $pMagcrs,$pPrevCrs , $pMagcrs);
                            // $hasil=$hasil.','.$rrslt;
                        }
                    }
                }
            }
            $arr=explode(',',$hasil);
            $jml=count($arr)-1;
            $arr1=explode(' ',$arr[$jml]);
            // dd($arr,$arr1);
            $lon= $arr1[0];
            $lat= $arr1[1];
            if ($fb == true){
                $rslt=GetEndPointFB($idx,$lat,$lon,$fix_lat,$fix_lon,$proc);
                $hasil=$hasil.','.$rslt;
                $akh=explode(',',$hasil);
                // dd($hasil);
                $jml=count($akh)-1;
            
                $akh1=explode(' ',$akh[$jml]);
                $fix_lat=$akh1[1];
                $fix_lon=$akh1[0];
            }else{
                $hasil=$hasil.','.$currenfix;
            }
            $lastCrs=GetLastCourse($hasil);

            $prevdata['lat']=$fix_lat;
            $prevdata['lon']=$fix_lon;
            $prevdata['fix_lat']=$fix_lat;
            $prevdata['fix_lon']=$fix_lon;
            $prevdata['bearing']=$lastCrs;
            $prevdata['path']=$data->path_term;
            $prevdata['course']=$lastCrs;
            $prevdata['hasil']=[];
        }
            // dd($hasil,$prevdata);
        return array($hasil,$prevdata);
    }
}


if(!function_exists('RFLEG')){
    function RFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $rslt= getfixedcoordinate($data);
        if ($rslt['lat']=='' || $prevd==''){
            $hasil='';$prevdata='';
        }else{
            $lat=$prevd['lat'];
            $lon=$prevd['lon'];
            $fix_lat=$rslt['lat'];
            $fix_lon=$rslt['lon'];
            $pMagCrs=$prevd['course'];
            $pTurn=$data->turn_dir;
            if ($data->turn_dir==null){
                $pTurn='';
            }
            $rad = $data->arc_rad;
            $Centcord = FindCircleCircleIntersections($lat,$lon, $rad / 60, $fix_lat,$fix_lon, $rad / 60, $pTurn, $pMagCrs);
            $rrslt =  GetArc( $Centcord['latitude'], $Centcord['longitude'],$rad,$lat,$lon, $fix_lat,$fix_lon, $pTurn);
            // dd($proc[$idx],$Centcord,$rrslt);
        
            $hasil=$rrslt;
            $akh=explode(',',$hasil);
            // dd($hasil);
            $jml=count($akh)-1;
            $lastCrs=GetLastCourse($hasil);
            $akh1=explode(' ',$akh[$jml]);
            $prevdata['lat']=$akh1[1];
            $prevdata['lon']=$akh1[0];
            $prevdata['fix_lat']=$fix_lat;
            $prevdata['fix_lon']=$fix_lon;
            $prevdata['bearing']=$lastCrs;
            $prevdata['path']=$data->path_term;
            $prevdata['course']=$lastCrs;
            $prevdata['hasil']=[];
         }

    return array($hasil,$prevdata);
    }
}
if(!function_exists('HMLEG')){
    function HMLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
      
        $rslt= getfixedcoordinate($data);
        if ($rslt['lat']=='' || $prevd==''){
            $hasil='';$prevdata='';
        }else{
            $lat=$prevd['lat'];
            $lon=$prevd['lon'];
            $fix_lat=$rslt['lat'];
            $fix_lon=$rslt['lon'];
            $pMagCrs=$prevd['course'];
            $pTurn=$data->turn_dir;
            $hasil = $fix_lon.' '.$fix_lat;
            $prevdata['lat']=$fix_lat;
            $prevdata['lon']=$fix_lon;
            $prevdata['fix_lat']=$fix_lat;
            $prevdata['fix_lon']=$fix_lon;
            $prevdata['bearing']=$lastCrs;
            $prevdata['path']=$data->path_term;
            $prevdata['course']=$lastCrs;
            $prevdata['hasil']=[];
         }

    return array($hasil,$prevdata);
       
    }
}
if(!function_exists('AFLEG')){
    function AFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $rslt= getfixedcoordinate($data);
        if ($rslt['lat']=='' || $prevd==''){
            $hasil='';$prevdata='';
        }else{
            $lat=$prevd['lat'];
            $lon=$prevd['lon'];
            $fix_lat=$rslt['lat'];
            $fix_lon=$rslt['lon'];
            $pCourse=$data->mag_crs;
            $PcrsRad = GetRadial($pCourse);
           
            if ($data->turn_dir=='R'){
                $pTurn='L';
            }else{
                $pTurn='R';
            }
            $rad = $data->rho;
            $recd= getrecdnav($data,'1');
            // $Centcord =  Getbearing( $lat, $lon, $fix_lat, $fix_lon,false);
            $rrslt =  GetArc( $recd['lat'],  $recd['lon'],$rad,$lat,$lon,$fix_lat,$fix_lon, $pTurn);
            // dd($proc[$idx],$Centcord,$rrslt);
        
            $hasil=$rrslt;
            $akh=explode(',',$hasil);
            // dd($hasil);
            $jml=count($akh)-1;
            $lastCrs=GetLastCourse($hasil);
            $akh1=explode(' ',$akh[$jml]);
            $prevdata['lat']=$akh1[1];
            $prevdata['lon']=$akh1[0];
            $prevdata['fix_lat']=$fix_lat;
            $prevdata['fix_lon']=$fix_lon;
            $prevdata['bearing']=$lastCrs;
            $prevdata['path']=$data->path_term;
            $prevdata['course']=$lastCrs;
            $prevdata['hasil']=[];
         }

    return array($hasil,$prevdata);
    }
}

if(!function_exists('DFLEG')){
    function DFLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){

        $rslt= getfixedcoordinate($data);
        $lat=$prevd['lat'];
        $lon=$prevd['lon'];
        $fix_lat=$rslt['lat'];
        $fix_lon=$rslt['lon'];
        $pMagCrs=$prevd['course'];
        $pTurn=$data->turn_dir;
        if ($data->turn_dir==null){
            $pTurn='';
        }
        $currenfix=$fix_lon.' '.$fix_lat;
        //  dd($prevd,$data,$fix_lat,$fix_lon);
        $trk = Getbearing( $lat, $lon, $fix_lat, $fix_lon,false);
        $Heading=$trk->TrackOutReal;$rsltUlang=true;
        if ($prevd['path']=='IF'){
            $pMagCrs=$trk->TrackInReal;
        }
        // dd($trk,'$ttk',abs($pMagCrs - $Heading),$pMagCrs,$Heading,$prevd);
        if(abs($pMagCrs - $Heading) <= 3 || $pTurn==''){
            $rsltUlang=false;
            $hasil =$currenfix;
        // }else  if((abs($pMagCrs - $Heading) >= 145 && abs($pMagCrs - $Heading) <= 150) || (abs($pMagCrs - $Heading) >= 210 && abs($pMagCrs - $Heading) <= 215) ){
        }else  if((abs($pMagCrs - $Heading) >= 178 && abs($pMagCrs - $Heading) <= 182) ){
            $tt=Getbearing( $lat, $lon, $fix_lat, $fix_lon,false);
            $pdist=$tt->DistanceReal;
            $ppen=GetPendicularCourse($pMagCrs, $pTurn);
            
            unset($tt);
            $tt = GetPoint2($lat, $lon, $ppen, 3.3 );
            $ttk=Getbearing( $tt['latitude'], $tt['longitude'], $fix_lat, $fix_lon,false);
            $ptemp=$ttk->TrackInReal;
            unset($tt);
            $tt = GetPoint2( $fix_lat, $fix_lon, $ptemp, $pdist );
            unset($ttk);
            $ttk=Getbearing($lat, $lon, $tt['latitude'], $tt['longitude'],false);
            $prad=$ttk->DistanceReal/2;
            // dd($ttk,'$ttk',$tt);
            $rrslt =  GetArc($ttk->Midlat, $ttk->Midlon,$prad,$lat,$lon,  $tt['latitude'], $tt['longitude'], $pTurn);
            // $rrslt = CreateArcTurn($pTurn, $ttk->Midlat,$ttk->Midlon, $pMagCrs, $Heading, $prad, "DF");
            $hasil=$rrslt;

        }else{
            $prad=GetJrkOff($lat,$lon,$fix_lat,$fix_lon);
            // dd($prad);
            $rrslt = CreateArcTurn($pTurn, $lat,$lon, $pMagCrs, $Heading, $prad);
            foreach ($rrslt as $key => $value) {
                if ($key==0){
                    $hasil=$value;
                }else{
                    $hasil=$hasil.','.$value;
                }
                
                $ccr=explode(' ',$value);
                $lat=$ccr[1];
                $lon=$ccr[0];
            }
        }
        // var_dump($lat,$lon);
        if ($rsltUlang==true){
            $rr=explode(',',$hasil);$result='';$tlat1='';$tlon1='';$tlat2='';$tlon2='';
            for ($i=0; $i < count($rr) ; $i++) { 
                if ($i==0){
                    $awl=explode(' ',$rr[$i]);
                    $tlat1=(float)$awl[1];$tlon1=(float)$awl[0];
                    $result=$rr[$i];
                }else{
                    $awl=explode(' ',$rr[$i]);
                    $tlat2=$awl[1];$tlon2=$awl[0];
                    unset($ttk); unset($ttk1);
                    $ttk=Getbearing((float)$tlat1,(float)$tlon1,(float) $tlat2, (float)$tlon2,false);
                    $ttk1=Getbearing((float)$tlat2,(float)$tlon2, (float)$fix_lat, (float)$fix_lon,false);
                    // dd($ttk1->TrackOutReal - $ttk->TrackOutReal,$ttk1 , $ttk,$result,(float)$tlat1,(float)$tlon1,(float) $tlat2, (float)$tlon2, (float)$fix_lat, (float)$fix_lon);
                    // if (abs($ttk1->TrackOutReal - $ttk->TrackOutReal) <= 0.5){
                    //     $result=$result.','.$rr[$i];
                    //     $lat=(float)$tlat2;
                    //     $lon=(float)$tlon2;
                    //     break;
                    // }else{
                    //     $result=$result.','.$rr[$i];

                    // }
                    $tlat1=(float)$tlat2;$tlon1=(float)$tlon2;
                    $lat=(float)$tlat2;
                    $lon=(float)$tlon2;
                }
                # code...
            }
            // dd($result);
            // $hasil=$result;
        }
        // dd($lat,$lon);
        // var_dump($hasil);
        $rslt=GetEndPointFB($idx,$lat,$lon,$fix_lat,$fix_lon,$proc);
        $hasil=$hasil.','.$rslt;
        $akh=explode(',',$hasil);
        // dd($hasil);
        $jml=count($akh)-1;
        $lastCrs=GetLastCourse($hasil);
        $akh1=explode(' ',$akh[$jml]);
        $prevdata['lat']=$akh1[1];
        $prevdata['lon']=$akh1[0];
        $prevdata['fix_lat']=$fix_lat;
        $prevdata['fix_lon']=$fix_lon;
        $prevdata['bearing']=$lastCrs;
        $prevdata['path']=$data->path_term;
        $prevdata['course']=$lastCrs;
        $prevdata['hasil']=[];
        // dd($hasil,$prevdata);

    return array($hasil,$prevdata);

    }
}
if(!function_exists('CDLEG')){
    function CDLEG($prevd,$data,$proc,$idx,$jumproc,$chart_type){
        $hasil='';$lat='';$lon='';
        $rslt= getrecdnav($data,'1');
        $lat=$prevd['lat'];
        $lon=$prevd['lon'];
        $prevfix_lat=$prevd['lat'];
        $prevfix_lon=$prevd['lon'];
        $recd1_lat=$rslt['lat'];
        $recd1_lon=$rslt['lon'];
        $pmagcrs =$prevd['course'];
        $CFArc = false; $baseturn ='';$CatBT=0;$hasil='';
        $pturn = $data->turn_dir;
        $hasil=$lon.' '.$lat;
        $fix_lon= '';
        $fix_lat= '';
        if ($data->rho <> "0"){
            $Rho = $data->rho;
        }else{
            $Rho = $data->rt_dist_from;
        }
        $pCourse =$data->mag_crs; $hsl=[];$FixCUrrent='';
        if ($proc[$idx+1]->path_term =='CD' && ($proc[$idx+1]->turn_dir !== null || $proc[$idx+1]->leg_time !== null)){
            $baseturn = "T";$CatBT=1;
            if (abs($pCourse - $proc[$idx+1]->mag_crs) < 160){
                $CatBT=1.5;
            }
        }

        if (($pturn == "L" || $pturn == "R") && abs($pCourse - $pmagcrs) > 45 ){
            // dd($pCourse , $pmagcrs, $pturn);
            if ($proc[$idx-1]->leg_time <> ""){
                $baseturn = "D";
            } 
            $CFArc = true;
            $hsl = GetPoint2( $recd1_lat, $recd1_lon, GetRadial($pCourse), $Rho );
            $prevfix_lat=$hsl['latitude'];
            $prevfix_lon=$hsl['longitude'];
            $trk=Getbearing($lat, $lon,$prevfix_lat,$prevfix_lon);
            $prad = $trk->DistanceReal / 2;
            $cent_lat=$trk->Midlat;
            $cent_lon=$trk->Midlon;

        }

        if ($CFArc == true){
            // dd($cent_lat,$cent_lon , $prad,$lat,$lon,$prevfix_lat,$prevfix_lon, $pturn);
            $arccord= GetArc( $cent_lat,$cent_lon,$prad,$lat,$lon,$prevfix_lat,$prevfix_lon, $pturn);
            $hasil=$hasil.','.$arccord;
            $arr=explode(',',$arccord);
            $jml=count($arr)-1;
            $arr1=explode(' ',$arr[$jml]);
            $fix_lon= $arr1[0];
            $fix_lat= $arr1[1];
            // dd( $hasil);
        }else{

            $trk=Getbearing($lat, $lon,$recd1_lat,$recd1_lon);
            if (abs($trk->TrackOutReal - $pCourse) <=2){
                $pCourse=$trk->TrackOutReal;
            }
            if ($data->theta == null || $data->theta == '' || $data->theta == '0' ){
                $hsl2 = GetPoint2( $lat, $lon, $pCourse, 30 );
                unset($hhg);
                $hhg = GetIntLinewithCircle($recd1_lat,$recd1_lon,$Rho,$lat,$lon, $hsl2['latitude'],$hsl2['longitude']);
                $nilai = isset ($hhg['longitude']) ?$hhg['longitude']:'';
                $fix_lon= $nilai;
                $nilai1 = isset ($hhg['latitude']) ? $hhg['latitude']:'';
                $fix_lat= $nilai1;
                $FixCUrrent= $nilai.' '.$nilai1;
                
                
                // dd($FixCUrrent);
                // $fix_lat= $fixD['latitude'];
                // $fix_lon= $fixD['longitude'];
                // dd($hhg);
            }else{
                $hsl2 = GetPoint2( $recd1_lat, $recd1_lon, $data->theta, $Rho );
                $FixCUrrent= $hsl2['longitude'].' '.$hsl2['latitude'];
                $fix_lat =$hsl2['latitude'];
                $fix_lon= $hsl2['longitude'];
            }
            if ($FixCUrrent=='0 0'){
                $FixCUrrent=$lon.' '.$lat;
                $fix_lat = $lat;
                $fix_lon= $lon;
            }
            // dd($FixCUrrent);
            // $fix_lon= $FixCUrrent['longitude'];
            // $fix_lat= $FixCUrrent['latitude'];
            // var_dump($hasil,$arrfix[1],$fix_lat);
            $hasil=$hasil.','.$FixCUrrent;
        }

        $prevdata['lat']=$fix_lat;
        $prevdata['lon']=$fix_lon;
        $prevdata['fix_lat']=$fix_lat;
        $prevdata['fix_lon']=$fix_lon;
        $prevdata['bearing']=$pCourse;
        $prevdata['path']=$data->path_term;
        $prevdata['course']=$pCourse;
        $prevdata['hasil']=[];

        // dd($hasil,$prevdata);
    return array($hasil,$prevdata);
       
        
    }
}

if(!function_exists('GetJrkOff')){
    function GetJrkOff($prev_lat,$prev_lon,$curr_lat,$curr_lon){
        $hsl=2;
        if ($curr_lat==null ||$curr_lon==null ||$curr_lat=='' || $curr_lon=='' ){
            $hsl=2;
        }else{
            $trk=Getbearing($prev_lat, $prev_lon,$curr_lat,$curr_lon);
            if ($trk->DistanceReal >=2){
                $hsl=2;
            }else{
                $hsl=$trk->DistanceReal/3;
            }
        }
        return $hsl;
    }
}



if(!function_exists('checkwpt_desc2')){
    function checkwpt_desc2($wptdesc){
        if ($wptdesc=='E' || $wptdesc=='B' || $wptdesc=='Y'){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('updatetransseg')){
    function updatetransseg($procid,$transid,$geom){
        $tr=Trans::where('id','=',$transid)->first();
        $tr->geom=$geom;
        $tr->update();
        // use App\Models\Api\ArptransTemp as Trans;
    }
}

if(!function_exists('calculate_va')){
    function calculate_va($lat,$lon,$bearing,$altitude){
        if ($altitude.substr(0,2)=='FL'){
            $Altc = preg_replace("/[^0-9]/", "", $altitude);
        }else{
            $Altc = $altitude;
        }

        $dist = $Altc / 500;
        $hsl = GetPoint2( $lat, $lon, $bearing, $dist );
        return $hsl;
    }
}

if(!function_exists('GetPendicularCourse')){
    function GetPendicularCourse($course,$turn){
        if ($turn=='R'){
            $hsl=$course + 90;
            if ($hsl > 360){}
            $hsl -= 360;
        }else{
            $hsl=$course - 90;
            if ($hsl < 0)
            $hsl += 360;
        }

        return $hsl;
    }
}

if(!function_exists('getfixedcoordinate')){
    function getfixedcoordinate($data){
        if ($data->waypoint){
            $lon=$data->waypoint[0]->geom->coordinates[0];
            $lat=$data->waypoint[0]->geom->coordinates[1];
            $fixname=$data->waypoint[0]->wpt_name;
        }else  if ($data->navaid){
            $lon=$data->navaid[0]->geom->coordinates[0];
            $lat=$data->navaid[0]->geom->coordinates[1];
            $fixname=$data->navaid[0]->nav_ident;
        }else  if ($data->marker){
            $lon=$data->marker[0]->geom->coordinates[0];
            $lat=$data->marker[0]->geom->coordinates[1];
            $fixname=$data->marker[0]->mrkr_type;
        }else  if ($data->rwy){
            $lon=$data->rwy[0]->geom->coordinates[0];
            $lat=$data->rwy[0]->geom->coordinates[1];
            $fixname='RWY'.$data->rwy[0]->rwy_ident;
        }else  if ($data->arpt){
            $lon=$data->arpt[0]->geom->coordinates[0];
            $lat=$data->arpt[0]->geom->coordinates[1];
            $fixname=$data->arpt[0]->icao;
        }else{
            $lon='';
            $lat='';
            $fixname='';
        }
        return array('lat' => $lat , 'lon' =>$lon, 'fix' =>$fixname);
    }
}
if(!function_exists('getrecdnav')){
    function getrecdnav($data,$recdnav){
        $return=null;$ada=false;
        if ($recdnav=='1'){
            if ($data->recdnav1){
                $ada=true;
                $lon=$data->recdnav1[0]->geom->coordinates[0];
                $lat=$data->recdnav1[0]->geom->coordinates[1];
                $fixname=$data->recdnav1[0]->nav_ident;
            }else  if ($data->recdils1){
                $ada=true;
                $lon=$data->recdils1[0]->geom->coordinates[0];
                $lat=$data->recdils1[0]->geom->coordinates[1];
                $fixname=$data->recdils1[0]->ils_ident;
            }
        }else if ($recdnav=='2') {
            if ($data->recdnav2){
                $ada=true;
                $lon=$data->recdnav2[0]->geom->coordinates[0];
                $lat=$data->recdnav2[0]->geom->coordinates[1];
                $fixname=$data->recdnav2[0]->nav_ident;
            }else  if ($data->recdils2){
                $ada=true;
                $lon=$data->recdils2[0]->geom->coordinates[0];
                $lat=$data->recdils2[0]->geom->coordinates[1];
                $fixname=$data->recdils2[0]->ils_ident;
            }
        }
        if ($ada==true){
            $return=array('lat' => $lat , 'lon' =>$lon, 'fix' =>$fixname);

        };
        return $return;
    }
}


if(!function_exists('CreateArcTurn')){
    function CreateArcTurn($turn,$lat,$lon,$bearing,$heading,$radius,$pathterm=''){

        if ($turn=='R'){
            $rTurn='L';
        }else{
            $rTurn='R';
        }
        $bear1 = GetPendicularCourse($bearing, $turn);
        // dd($turn,$lat,$lon,$bearing,$heading,$radius,$pathterm,$bear1);
        $hsl = GetPoint2( $lat, $lon, $bear1, $radius*2 );
        $trk=Getbearing($lat, $lon,$hsl['latitude'],$hsl['longitude']);
        $cent_lat=$trk->Midlat;
        $cent_lon=$trk->Midlon;
        if ($pathterm=='DF'){
            if ($turn=='R'){
                $br= $bear1 - 2;
            }else{
                $br= $bear1 + 2;
            }
            $bear2= GetRadial($br);
            // $end_lat=$hsl['latitude'];
            // $end_lon=$hsl['longitude'];
        }else{
            $bear2= GetPendicularCourse($heading,$rTurn);
        }
        // $bear2= GetPendicularCourse($heading,$rTurn);
        $hsl1 = GetPoint2( $cent_lat, $cent_lon, $bear2, $radius);
        $end_lat=$hsl1['latitude'];
        $end_lon=$hsl1['longitude'];
        $result=[];
        // $result=CirclceCord($cent_lat,$cent_lon,)





        $arccord= GetArc( $cent_lat,$cent_lon , $radius,$lat,$lon, $end_lat, $end_lon, $turn);
        // $arccord= GetArc( $cent_lat,$cent_lon , $radius,$lat,$lon, $hsl['latitude'],$hsl['longitude'], $turn);
        $arr=explode(',',$arccord);
       
        for ($i=0; $i < count($arr) ; $i++) { 
            array_push($result,$arr[$i]);
        }
        // dd($hsl,$trk,$arccord,$end_lat,$end_lon);
        return $result;
    }
}
if(!function_exists('CirclceCord')){
    function CirclceCord($cent_lat,$cent_lon,  $radius,$brg1 = 0, $brg2 = 360,$stp= 1) {
        $Rslt=[];
        for ($i=$brg1; $i <$brg2 ; $i+=$stp) { 
            $hsl1 = GetPoint2( $cent_lat, $cent_lon, $i, $radius);
            $cord=$hsl1['longitude'].' '.$hsl1['latitude'];
           array_push( $Rslt,$cord);
        }
    
        return $Rslt;

    }
}
if(!function_exists('GetEndPointFB')){
    function GetEndPointFB($Sequence,$prevlat,$prevlon,$fixlat,$fixlon,$proc){
        $ctrk=Getbearing($prevlat, $prevlon,$fixlat,$fixlon);
        $pproc=$proc[$Sequence];
        $akhir=count($proc)-1;
        $next=$Sequence;
        if ($Sequence < $akhir){
            $next=$Sequence+1;
        }
        // dd($Sequence,$next,$pproc,$proc[$next],count($proc));
        $distO = $ctrk->DistanceReal;
        if ($distO <= 1){
            $DisOfFB = $distO / 3;
        }else{
            $DisOfFB = 1;
        }
        $pCourse = $ctrk->TrackOutReal;
        $Wpt2ID=$pproc->wd2;
        // dd($ctrk,$pproc,$proc,checkwpt_desc2($Wpt2ID));
        if (checkwpt_desc2($Wpt2ID)==true){
            $hasil=$fixlon.' '.$fixlat;
        }else{
        
                if ($proc[$next]->path_term == "AF" || $proc[$next]->path_term == "RF"){
                    $hasil=$fixlon.' '.$fixlat;
                }else{
                    $nextfix= getfixedcoordinate($proc[$next]);
                    $jj = GetJrkOff($fixlat,$fixlon, $nextfix['lat'], $nextfix['lon']);
                    $pNextcrs=(float)$proc[$next]->mag_crs;
                    $pNextPT=$proc[$next]->path_term;
                    $JrkOff=true;$jrk=0;
                    if ($jj <= 1){
                        $JrkOff=false;
                        $jrk=$jj;
                    }
    
                    $rslt=FBorFO($prevlat,$prevlon,$fixlat,$fixlon,$pCourse,$pNextcrs,$pNextPT,$Wpt2ID,$JrkOff,$jrk);
                    foreach ($rslt as $key => $value) {
                        if ($key==0){
                            $hasil=$value;
                        }else{
                            $hasil =$hasil.','.$value;
                        }
                    }
                }

            

        }
        // dd($hasil);
        return $hasil;
    }
}
if(!function_exists('FBorFO')){
    function FBorFO($prevlat,$prevlon,$fixlat,$fixlon,$pCourse,$pNextcrs,$pNextPT,$FlyByOrOver,$JrkOff,$jrk){
        $pradial=GetRadial($pCourse);$FlyBy=true;$pRad=1;$hasil=[];
        // $ctrk=Getbearing($fixlat,$fixlon,$prevlat, $prevlon);
        if ($FlyByOrOver=='Y' || $FlyByOrOver=='B'){
            $FlyBy=false;
        }
        if ($JrkOff==false){
            $pRad=$jrk;
        }
        // dd($fixlat,$fixlon,$pradial,$pRad);
        if ($pNextcrs=='' || $pNextPT=='DF'){
            array_push($hasil,$fixlon.' '.$fixlat);
        }else{
            if (abs($pNextcrs - $pCourse) < 10){
                if ($FlyBy==true){
                    $point2=GetPoint2($fixlat,$fixlon,$pradial,$pRad);
                    array_push($hasil,$point2['longitude'].' '.$point2['latitude']);
                }
                array_push($hasil,$fixlon.' '.$fixlat);
            }else{
                if ($FlyBy==true){
                    $point1=GetPoint2($fixlat,$fixlon,$pradial,$pRad);
                    $point2=GetPoint2($fixlat,$fixlon,$pNextcrs,$pRad);
                    $hasil=DF($point1['latitude'],$point1['longitude'],$point2['latitude'],$point2['longitude'],$fixlat,$fixlon);
                }else{
                    $point1=GetPoint2($fixlat,$fixlon,$pCourse,$pRad);
                    $point2=GetPoint2($fixlat,$fixlon,$pNextcrs,$pRad);
                    $hasil=DF($fixlat,$fixlon,$point2['latitude'],$point2['longitude'],$point1['latitude'],$point1['longitude']);
                }

            }

        }
        return $hasil;
    }
}
if(!function_exists('GetTurn')){
    function GetTurn($currentcourse,$NextCourse){
        if ($currentcourse>$NextCourse){
            return 'L';
        }else{
            return 'R';
        }
    }
}
if(!function_exists('DF')){
    function DF($star_lat,$star_lon,$end_lat,$end_lon,$cent_lat,$cent_lon){
        $hasil=[];
        // dd($star_lat,$star_lon,$end_lat,$end_lon,$cent_lat,$cent_lon);
        for ($t=0; $t < 1; $t+=0.1) { 
            $X = (1 - $t) * (1 - $t) * $star_lon + 2 * (1 - $t) * $t * $cent_lon + $t * $t * $end_lon;
            $Y = (1 - $t) * (1 - $t) * $star_lat + 2 * (1 - $t) * $t * $cent_lat + $t * $t * $end_lat;
            array_push($hasil,$X.' '.$Y);
            
        }
        // dd($hasil);
        return $hasil;
    }
}

if(!function_exists('GetLastCourse')){
    function GetLastCourse($array){
        $arr=explode(',',$array);
        if (count($arr)==1){
            $jm=0;
            $pjm=0;
        }else{
            $jm=count($arr)-1;
            $pjm=$jm-1;
        }
       
        // dd($arr,$array,count($arr));
        $prevcord=$arr[$pjm];
        $lastcord=$arr[$jm];
        $pc=explode(' ',$prevcord);
        $lc=explode(' ',$lastcord);
        $ctrk=Getbearing($pc[1],$pc[0],$lc[1], $lc[0]);

        return $ctrk->TrackOutReal;
    }
}
if(!function_exists('GetIntLinewithCircle')){
    function GetIntLinewithCircle($cent_lat,$cent_lon,$radius,$lat1,$lon1,$lat2,$lon2){
        // dd($cent_lat,$cent_lon,$radius,$lat1,$lon1,$lat2,$lon2);
        $arr=FindLineCircleIntersections($cent_lat,$cent_lon,$radius,$lat1,$lon1,$lat2,$lon2);
        $ctrk=Getbearing($lat1,$lon1,$lat2,$lon2,false);
        $bearing = $ctrk->TrackOutReal;$hasil=[];
        foreach ($arr as $key => $value) {
            $ttrk1=Getbearing($lat1,$lon1,$value['latitude'],$value['longitude'],false);
            if ($ttrk1->TrackOutReal >= ($bearing-1) && $ttrk1->TrackOutReal <= ($bearing+1)){
                // var_dump($value);
                $hasil=$value;
            }
        }
        
            return $hasil;

      
        // dd($arr,$hasil);
    }
}

if(!function_exists('FindLineCircleIntersections')){
    function FindLineCircleIntersections($cent_lat,$cent_lon,$radius,$lat1,$lon1,$lat2,$lon2){
        $cx =(float) $cent_lon;
        $cy = (float)$cent_lat;
        $x1 =(float)$lon1;
        $y1 = (float)$lat1;
        $x2 =(float) $lon2;
        $y2 = (float)$lat2;
        $rad = (float)$radius / 60;
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        $result=[];
        $A = $dx * $dx + $dy * $dy;
        $B = 2 * ($dx * ($x1 - $cx) + $dy * ($y1 - $cy));
        $C = ($x1 - $cx) * ($x1 - $cx) + ($y1 - $cy) * ($y1 - $cy) - $rad * $rad;
        $ddet = $B * $B - 4 * $A * $C;
        $sqrtd=sqrt($ddet);
        // dd($dx,$dy,$A,$B,$C,$ddet,sqrt($ddet));
        if ($A <= 0.0000001 ||  $ddet < 0){
            $result=[];
        }else  if ($ddet = 0){
            $t =-($B) / (2 * $A);
            $ix1 = $x1 + $t * $dx;
            $iy1 = $y1 + $t * $dy;
        
            $hh= array('latitude' => $iy1 , 'longitude' =>$ix1);
            array_push($result,$hh);
        }else{
            // dd('T PETAMA xxxx',$sqrtd,$ddet);

            $t =(-$B + $sqrtd) / (2 * $A);
            $ix1 = $x1 + $t * $dx;
            $iy1 = $y1 + $t * $dy;
            $hh= array('latitude' => $iy1 , 'longitude' =>$ix1);
            array_push($result,$hh);
            // dd('T PETAMA',$t,$sqrtd,$ddet);
            unset( $t);
            $t =(-($B) - $sqrtd) / (2 * $A);
            $ix2 = $x1 + $t * $dx;
            $iy2 = $y1 + $t * $dy;
            $hh= array('latitude' => $iy2 , 'longitude' =>$ix2);
            array_push($result,$hh);
            // array_push($result,$ix2.' '.$iy2);
            // dd($t,$ix1,$iy1,$ix2,$iy2);
        }
        return $result;
        
    }
}
if(!function_exists('GetCourseTrue')){
    function GetCourseTrue($rnav,$lat,$lon,$course){
        if($rnav==true){
            $epoch = date('Y-m-d');
            $alt=0;
            $mv = GetMagvar( $lon, $lat, $epoch,$alt);
            return $course + $mv->dec;
        }
        
          

      
        // dd($arr,$hasil);
    }
}
if(!function_exists('GetIntersection')){
    function GetIntersection($recnavlat,$recnavlon,$Course1, $lat,$lon,$Course2){
        // Dim hsl As New ccordinate
        // Dim csLop As New csloope, csLop1 As New csloope, cNextPnt As New cPointXY
        // Dim ttrk As New cTrack
        $slope1 = setSloopeByCourseP1($recnavlat,$recnavlon, $Course1);
        $slope2 = setSloopeByCourseP1($lat,$lon,$Course2);
        // dd($slope1,$slope2);
        $cNextPnt = FindInterceptOfSloops($slope1, $slope2);
        // dd($cNextPnt);
        // hsl = New ccordinate
        // hsl.SetCoordinatebyDecimal(cNextPnt.Ypoint, cNextPnt.Xpoint)

        return $cNextPnt;
    }
}
if(!function_exists('setSloopeByCourseP1')){
    function setSloopeByCourseP1($lat,$lon,$Course){
        $nmt=1852;
        $proCourse;$PendCourse;
        unset($refx); unset($refy);
        if ($Course == 0 || $Course == 360){
            $refx = 0;
            $refy = 1;
        } else if($Course > 0 && $Course < 90){
            $refx = 1;
            $refy = 1;
        } else if ($Course == 90){
            $refx = 1;
            $refy = 0;
        } else if ($Course > 90 && $Course < 180){
            $refx = 1;
            $refy = -1;
        } else if ($Course == 180){
            $refx = 0;
            $refy = -1;
        } else if ($Course > 180 && $Course < 270){
            $refx = -1;
            $refy = -1;
        } else if ($Course == 270){
            $refx = -1;
            $refy = 0;
        } else if ($Course > 270 && $Course < 360){
            $refx = -1;
            $refy = 1;
        }
        $pBValue=0;$pBValueP1=0;
        $proCourse = AtoC($Course);
        // var_dump($proCourse,$Course);
      
        if ($proCourse == 90 || $proCourse == 270){
            $pSloope = 0;
            $pPendicularSloope = 0;
            $pAxistDifer = "X";
        } else if ($proCourse == 0 || $proCourse == 360 || $proCourse == 180){
            $pSloope = 0;
            $pPendicularSloope = 0;
            $pAxistDifer = "Y";
        }else{
            $pAxistDifer = "Z";
            $pSloope = tan(deg2rad($proCourse));
            $pBValue = $lat - ($pSloope * $lon);
            if ($proCourse > 180){
                $PendCourse = $proCourse - 90;
            } else if ($proCourse < 180){
                $PendCourse = $proCourse + 90;
            }
            $pPendicularSloope = tan(deg2rad($PendCourse));
            $pBValueP1 = $lon - ($pPendicularSloope * $lon);
        }

        $tempX=0;$tempY =0;
        // pPoint2 = New cPointXY
        if ($pSloope == 0){
            if ($refx == 0){
                $tempX =$lon;
                $tempY = $lat + ($refy * $nmt);
            } else if ($refy == 0){
                $tempX =$lon + ($refx * $nmt);
                $tempY = $lat;
            }
        } else if ($pSloope <> 0){
            $aa=$refx;
            // dd($aa,$refx);
            $tempX =$lon + ($aa * $nmt);
            $tempY = ($tempX * $pSloope) + $pBValue;
        }
        // dd($refx,$proCourse,$Course,$pSloope );
        // Call pPoint2.SetPoint(tempY, tempX)
        $pBvalueP2 = $lat - ($pPendicularSloope * $lon);
        $hasil=[];
            $dt['Sloope']=$pSloope;
            $dt['BValue']=$pBValue;
            $dt['PendiCularSloope']=$pPendicularSloope;
            $dt['bValuP1']= $pBValueP1;
            $dt['BValueP2']=$pBvalueP2;
            $dt['POINT1_LAT']=$lat;
            $dt['POINT1_LON']=$lon;
            $dt['POINT2_LAT']=$tempY;
            $dt['POINT2_LON']= $tempX;
            $dt['FreeAxist']=$pAxistDifer;
            $dt['dirX']=$refx;
            $dt['dirY']=$refy;
            $rrsl=setDir($lat,$lon,$tempY,$tempX,$pSloope);
            $dt['Angle']=$rrsl[0];
            $dt['Course']=$rrsl[1];
            array_push($hasil,$dt);
            // dd($hasil);
    // return $deg.$min.$sec.$det[1].$head;
    return $hasil; 
      
        // return setDir($lat,$lon,$tempY,$tempX,$pSloope);
        // pDirX = refx
        // pDirY = refy

}
}
if(!function_exists('AtoC')){
    function AtoC($aeroDeg){
        $result=0;
        $inPt =$aeroDeg;
        if ($inPt > 360){
            $inPt = $inPt - 360;
        }

        if ($inPt < 0){
            $inPt = absAng($inPt);
        }

        if ($inPt <= 90){
            $result = ($inPt - 90) * -1;
        } else if ($inPt > 90 && $inPt <= 180){
            $result = 360 - ($inPt - 90);
        } else if ($inPt > 180 && $inPt <= 270){
            $result = 270 - ($inPt - 180);
        } else if ($inPt > 270 && $inPt <= 360 ){
            $result = 180 - ($inPt - 270);
        // } else if ($inPt < 0 || $inPt > 360){
        //     $AtoC = 0;
        }
        return $result;
    }
}
if(!function_exists('absAng')){
    function absAng($ang ){
       $result;
        if ($ang > 0 && $ang <= 360){
            $result = $ang;
        } else if ($ang <= 0){
            $result = $ang + 360;
        } else if ($ang > 360){
            $result = $ang - 360;
        } else if ($ang < -360){
            $result = 360 - (abs($ang) - 360);
         }
        return $result;
    }
}
if(!function_exists('setDir')){
    function setDir($y1,$x1,$y2,$x2,$pSloope){
        if ($x1 > $x2){
            $pDirX = -1;
        } else if ($x1 < $x2){
            $pDirX = 1;
        } else if ($x1 == $x2){
            $pDirX = 0;
        }

        if ($y1 > $y2){
            $pDirY = -1;
        } else if ($y1 < $y2){
            $pDirY = 1;
        } elseif ($y1 == $y2){
            $pDirY = 0;
        }


        return getAng($pSloope,$pDirY,$pDirX);
}
}

if(!function_exists('getAng')){
    function getAng($pSloope,$pDirY,$pDirX){
        // var_dump($pSloope,$pDirY,$pDirX);
        if ($pSloope > 0 && $pDirY > 0){
            $pAng =deg2rad(atan($pSloope));
        } else if ($pSloope > 0 && $pDirY < 0 ){
            $pAng = deg2rad(atan($pSloope)) + 180;
        } else if ($pSloope < 0 && $pDirY > 0){
            $pAng =deg2rad(atan($pSloope)) + 180;
        } else if ($pSloope < 0 && $pDirY < 0){
            $pAng = deg2rad(atan($pSloope)) + 360;
        } else if ($pSloope == 0 && $pDirY == 0){
            if ($pDirX > 0){
                $pAng = 360;
            }else{
                $pAng = 180;
            }
        } else if ($pSloope == 0 && $pDirX == 0 ){
            if ($pDirY < 0 ){
                $pAng = 270;
            }else{
                $pAng = 90;
            }
        }
        // var_dump($pAng);
        return array($pAng,AtoC($pAng));
    }
}
if(!function_exists('FindInterceptOfSloops')){
    function FindInterceptOfSloops($slopIn1,$slopIn2){
        $calB;$calSlop;$myPt;$tempX;$tempY; $slopIn1;
        $slp1=$slopIn1[0];$slp2=$slopIn2[0];
        // dd($slopIn1,$slp1['Sloope']);
        if($slp1['Sloope'] <> 0 && $slp2['Sloope'] <> 0){
            $calB =$slp1['BValue']- $slp2['BValue'];
            $calSlop = $slp2['Sloope'] - $slp1['Sloope'];
            $tempX =  $calB / $calSlop;
            $tempY = ($slp1['Sloope'] * $tempX) + $slp1['BValue'];
        }else if($slp1['Sloope'] == 0 && $slp2['Sloope'] <> 0){
            if ($slp1['dirX'] == 0){
                $tempX =  $slp1['POINT1_LON'];
                $tempY = ($slp2['Sloope'] * $tempX) + $slp2['BValue'];
            }else if ($slp1['dirY'] == 0){
                $tempY =  $slp1['POINT1_LAT'];
                $tempX = ( $tempY -  $slp2['BValue']) / $slp2['Sloope'];
            }
        }else if($slp1['Sloope'] <> 0 && $slp2['Sloope'] == 0){
            if ($slp2['dirX'] == 0){
                $tempX =  $slp1['POINT1_LON'];
                $tempY = ($slp1['Sloope'] * $tempX) + $slp1['BValue'];
            }else if ($slp2['dirY'] == 0){
                $tempY =  $slp2['POINT1_LAT'];
                $tempX = ( $tempY -  $slp1['BValue']) / $slp1['Sloope'];
            }
        }else if($slp1['Sloope'] == 0 && $slp2['Sloope'] == 0){
            if ($slp1['dirX'] <> 0){
                $tempY =  $slp1['POINT1_LAT'];
                $tempX =  $slp2['POINT1_LON'];
            }else if ($slp1['dirY'] <> 0){
                $tempY =  $slp2['POINT1_LAT'];
                $tempX =  $slp1['POINT1_LON'];
            }else if ($slp1['dirY'] == $slp2['dirY'] && $slp1['dirX'] == $slp2['dirX']  ){
                $tempY = 0;
                $tempX = 0;
            }
        }else if($slp1['Sloope'] <> 0 && $slp1['Sloope'] == $slp2['Sloope']){
                $tempY = 0;
                $tempX = 0;
            
        }
        // dd($slopIn1,$slopIn2,(float)$tempY,(float)$tempX);
        return array((float)$tempY,(float)$tempX);
    }
}

if(!function_exists('FindCircleCircleIntersections')){
    function FindCircleCircleIntersections($Awallat,$Awallon,$radius0,$Akhirlat,$Akhirlon, $radius1,$Turn,$prevcrs = 0){
    $cx0 = $Awallon;
    $cy0 = $Awallat;
    $cx1  =$Akhirlon;
    $cy1 = $Akhirlat;
    $dx  =  $cx0 - $cx1;
    $dy = $cy0 -$cy1;
    $dist = sqrt($dx * $dx + $dy * $dy);
    $strk=Getbearing($cy0,$cx0,$cy1,$cx1,false);

    if ($dist >= $radius0 + $radius1){
        $hsl= array('latitude' => $strk->Midlat , 'longitude' =>$strk->Midlon);
    } else if ($dist < abs($radius0 - $radius1)){
        $int1lat =(float)NAN;
        $int1lon =(float)NAN;
        $int2lat =(float)NAN;
        $int2lon =(float)NAN;

    } else if (($dist == 0) && ($radius0 == $radius1)){

        $int1lat =(float)NAN;
        $int1lon =(float)NAN;
        $int2lat =(float)NAN;
        $int2lon =(float)NAN;

    } else{
        $a = ($radius0 * $radius0 - $radius1 * $radius1 + $dist * $dist) / (2 * $dist);
        $h = sqrt($radius0 * $radius0 - $a * $a);

        $cx2  = $cx0 + $a * ($cx1 - $cx0) / $dist;
        $cy2  = $cy0 + $a * ($cy1 - $cy0) / $dist;
        $int1lat =(float)($cy2 - $h * ($cx1 - $cx0) / $dist);
        $int1lon =(float)($cx2 + $h * ($cy1 - $cy0) / $dist);
        $int2lat =(float)($cy2 + $h * ($cx1 - $cx0) / $dist);
        $int2lon =(float)($cx2 - $h * ($cy1 - $cy0) / $dist);
        // $intersection1 = New PointF(CSng($cx2 + $h * ($cy1 - $cy0) / $dist), CSng($cy2 - $h * ($cx1 - $cx0) / $dist));
        // $intersection2 = New PointF(CSng($cx2 - $h * ($cy1 - $cy0) / $dist), CSng($cy2 + $h * ($cx1 - $cx0) / $dist));

        if ($Turn == 'R'){
            $hsl= array('latitude' => $int1lat , 'longitude' =>$int1lon);
        } else if ($Turn == 'L'){
            if ($prevcrs <> 0){
                if (abs($prevcrs - $strk->TrackOutReal) > 90 && abs($prevcrs - $strk->TrackOutReal) < 180){
                    $hsl= array('latitude' => $int1lat , 'longitude' =>$int1lon);
                } else{
                    $hsl= array('latitude' => $int2lat , 'longitude' =>$int2lon);
                }
            } else{
                $hsl= array('latitude' => $int2lat , 'longitude' =>$int2lon);
            }

        } else{
            $hsl= array('latitude' => $int1lat , 'longitude' =>$int1lon);
        }

    }
    return $hsl;
    }
}
if(!function_exists('toUtc')){
    function toUtc($date){
        $dt = new DateTime($date, new DateTimezone('Asia/Jakarta'));
        $dt->setTimeZone(new DateTimezone('UTC'));
        return $dt->format('Y-m-d H:i:s');
    }
}
if(!function_exists('get_IP')){
    function get_IP(){ 
        $client = new Client();
 
        $endpoint = 'https://api.ipify.org/';   
        $response = $client->request('GET', $endpoint, ['query' => [
            'format' => 'json'
        ]]); 

        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody(), true);
 
        return $content['ip']; 
    }
}
