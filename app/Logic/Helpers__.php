<?php 
use \Illuminate\Support\Facades\Request as Req;
use Illuminate\Support\Facades\DB;
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
    $vars = explode('.', $dec);
    $deg = $vars[0];
    // var_dump(sprintf('%03d',$deg));
    $tempma = '0.'. $vars[1];
    // var_dump($dec,$deg,$tempma);
    $tempma = $tempma * 3600;
    // var_dump($tempma);
    $min1 = floor($tempma / 60);
    $min=sprintf('%02d',$min1);
    
    $sec1 = $tempma - ($min1 * 60);
    //  var_dump(number_format($sec1,2));
    $det = explode('.', number_format($sec1,2));
    $sec=sprintf('%02d',$sec1);
    if ($type=='LAT'){
        $deg=sprintf('%02d',$deg);
    }
    if ($type=='LON'){
        $deg=sprintf('%03d',$deg);
    }
    
    // return $deg.$min.$sec.$det[1].$head;
    return $deg.$min.$sec.'.'.$det[1].$head;
    
    // return array('deg' => $deg, 'min' => $min, 'sec' => $sec);
    }
}
if(!function_exists('toDecimal')){
    function toDecimal($corvalue) {
    
        $head;
        $mark;
        $deg;
        $Min;
        $sec;
        
        $reslt;
        $head = substr($corvalue,strlen($corvalue)-1);
        if ($head == "E" || $head == "N") {
            $mark = 1;
        } else if ($head == "W" || $head == "S") {
            $mark = -1;
        }
       
        // console.log( $corvalue,$head,$mark,strlen($corvalue) );
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
    // dd($head,$deg,$Min,$sec,$afS,$reslt);
    // console.log( 'HASIL ' , reslt );
    return $reslt;
    
    // return array('$deg' => $deg, 'min' => $min, 'sec' => $sec);
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
?>