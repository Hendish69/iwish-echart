<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use App\Models\Api\TxAshtam;
use App\Models\Api\TxAshtamForecast;
use App\Models\Api\TxCdm;
use App\Models\Api\TxCdmLog;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VonaMail;


class GetAshtamCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getashtam:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hourly update Ashtam API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    private function wksekarang(){
        date_default_timezone_set("UTC");
        return date('Y-m-d H:i:s'); 
    }
    private function clearStr($str, $slash=true){
        $text = preg_replace("/[\r\n]+/", " ", trim($str));
        $text = nl2br($text);
        if($slash){
            $text = addslashes($text);
        }
        return $text;
    }
    private function _initCurl($url, $token=null){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        curl_close($curl);
       
        return $response;
    }
    private function getData($url){
        $ret = $this->_getData($url);
        return $ret;
    }
    private function _getData($url){
        // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbWFnbWEuZXNkbS5nby5pZFwvYXBpXC9sb2dpblwvc3Rha2Vob2xkZXIiLCJpYXQiOjE1NzkzMTQ5NTMsImV4cCI6MTYxMDkzNzM1MywibmJmIjoxNTc5MzE0OTUzLCJqdGkiOiJvS2xUR0FzeFRsWjdha0twIiwic3ViIjo0LCJwcnYiOiI0YTlkOWEyZDI2ODAyYzMxMmU4ZTVhNWJlNjBmZjI2ZjBmYzYzZDdkIiwic291cmNlIjoiTUFHTUEgSW5kb25lc2lhIiwiYXBpX3ZlcnNpb24iOiJ2MSIsImRheXNfcmVtYWluaW5nIjozNjUsImV4cGlyZWRfYXQiOiIyMDIxLTAxLTE4IDAwOjAwOjAwIn0.uasdZ-aTUgS5HVv4chefheoDfzYq3PqSK8CP0ObpNd8";
        $response = $this->_initCurl($url);        
        return json_decode($response);
    }
    private function processing($ashtam){
        // $ashtam = get_object_vars($ashtam);
        \Log::info(print_r($ashtam));
        $len = isset($ashtam) ? count($ashtam) : 0;
        \Log::Info('jml issued: '.$len);
        for($i=0; $i < $len; $i++){
            // $ashtam_data = $ashtam[$i];
            $ashtam_data = $ashtam[$i];
            $volcano = explode(' ',$ashtam_data['C']);
            // $latlon= preg_split('/(?<=\D)(?=\d)|\d+\K/', $ashtam_data['D']);

		    $latlon = explode(' ',$ashtam_data['D']);
            $latlon[1] = isset($latlon[1]) ? $latlon[1] : '';
            // if(substr($ashtam_data['issued'],0,10)==$tanggal){ 
                $fsqlcek = DB::table('tx_ashtam')->selectRaw('count(*) jml')
                                ->where('ashtam_id', trim($ashtam_data['id']) )->get();
                // $fsqlcek->next(); 
                \Log::Info('Issued->'.$fsqlcek[0]->jml);
                if( $fsqlcek[0]->jml < 1 ){
                    $tx_ashtam = TxAshtam::create(
                        [                    
                            "ashtam_id"				=> $ashtam_data['id'], 
                            "ashtam_number"			=> $ashtam_data['VA'] . $ashtam_data['state'] . $ashtam_data['serial_nr'], 
                            "ashtam_update_time"	=> $ashtam_data['tgl'] .' '. $ashtam_data['jam'], 
                            "ashtam_fir"			=> $ashtam_data['A'], 
                            "ashtam_utc"			=> $ashtam_data['issued'], 
                            "ashtam_utc_issued"		=> $ashtam_data['B'], 
                            "ashtam_volcano"		=> trim(str_replace(end($volcano),'',$ashtam_data['C'])), 
                            "ashtam_volcano_number"	=> end($volcano), 
                            "ashtam_navaid_lon_dms"	=> $latlon[0], 
                            "ashtam_navaid_lat_dms"	=> $latlon[1], 
                            "ashtam_alert_code"		=> str_replace('      ','',$this->clearStr($ashtam_data['E'])), 
                            "ashtam_ahve"			=> str_replace('      ','',$this->clearStr($ashtam_data['F'])), 
                            "ashtam_ash_direction"	=> str_replace('      ','',$this->clearStr($ashtam_data['G'])),
                            "ashtam_affected_route"	=> str_replace('      ','',$this->clearStr($ashtam_data['H'])), 
                            "ashtam_air_space"		=> str_replace('      ','',$this->clearStr($ashtam_data['I'])), 
                            "ashtam_source"			=> str_replace('      ','',$this->clearStr($ashtam_data['J'])), 
                            "ashtam_plain_language"	=> str_replace('      ','',$this->clearStr($ashtam_data['K'])),
                            "ashtam_remarks"		=> '', 
                            "ashtam_date_created"	=> date("Y-m-d H:i:s", time())
                        ]
                    );
                    
                    $ASHTAM_ID = $tx_ashtam->ashtam_id;
                    if( !empty($ASHTAM_ID) ){    
                        \Log::info("insert into tx_ashtam success ID = (".$ASHTAM_ID.") ");
                    }else{
                        \Log::info('Failed to insert data tx_ashtam row into database.');
                    }

                    #HR 0
                    if (strpos(str_replace('      ','',$this->clearStr($ashtam_data['F'])), 'VA NOT IDENTIFIABLE') == false) {
                        $tx_ashfore = TxAshtamForecast::create(
                            [
                                "ashtam_id"         => $ashtam_data['id'], 
                                "ashtam_fcst_hr"    => 0, 
                                "ashtam_desc"       => trim(str_replace('  ',' ',$this->clearStr($ashtam_data['F'])))
                            ]
                            );
                        $ASHTAM_FORE_ID = $tx_ashfore->ashtam_id;
                        if( !empty($ASHTAM_FORE_ID) ){    
                            \Log::info("insert into tx_ashtam_forecast success ID = (".$ASHTAM_FORE_ID.") ");
                        }else{
                            \Log::info('Failed to insert data tx_ashtam_forecast row into database.');
                        }

                        
                    }
                    
                    #FORECAST
                    $forecast = explode('FCST VA CLD',str_replace('      ','',$this->clearStr($ashtam_data['K'])));
                    for($f=1; $f<count($forecast); $f++){
                        $forecast_arr = explode('RMK:',$forecast[$f]);
                        $forecast_arr = explode('HR:',$forecast_arr[0]);
                        $hr = str_replace(' ','',str_replace('+','',$forecast_arr[0]));
                        if (strpos($forecast_arr[1], 'NOT AVBL') == false && strpos($forecast_arr[1], 'NO VA EXP') == false) {
                            $tx_ashfore2 = TxAshtamForecast::create(
                                [
                                    "ashtam_id"         => $ashtam_data['id'], 
                                    "ashtam_fcst_hr"    => $hr, 
                                    "ashtam_desc"       => str_replace('  ',' ',trim($forecast_arr[1]))
                                ]
                                );
                            $ASHTAM_FORE_ID = $tx_ashfore2->ashtam_id;
                            if( !empty($ASHTAM_FORE_ID) ){    
                                \Log::info("insert into tx_ashtam_forecast 2 success ID = (".$ASHTAM_FORE_ID.") ");
                            }else{
                                \Log::info('Failed to insert data tx_ashtam_forecast 2 row into database.');
                            }
                        }
                    }

                    $CDM_ID =''; 
                    $cdmid = DB::table('tx_cdm')->where('va_no',trim(end($volcano)))->first();
                    $CDM_ID=$cdmid->cdm_id;
                    \Log::Info('cdm_id - >'.$CDM_ID);
                    if($CDM_ID !=''){
                        $cdm_log_id =''; 
                        // $cdm_log_id = DB::table('tx_cdm_log')->selectRaw('SELECT MAX(cdm_issued) cdm_issued')->where('cdm_id',$CDM_ID)->whereNotIn('cdm_type',['12'])->first();
                        // $fsqllog = DB::table('tx_cdm_log')->selectRaw('count(*) jml')
                        // ->where('cdm_id',$CDM_ID)
                        // ->where('cdm_type','9')
                        // ->where('cdm_noticenumber',$ashtam_data['VA'].$ashtam_data['state'].$ashtam_data['serial_nr'])->first();
                        // if ($fsqllog->jml < 1){
                                                    
                            $getlastid = DB::table('tx_cdm_log')->latest('cdm_log_id')->first();
                            $latesid=$getlastid->cdm_log_id + 1;
                            \Log::Info('tx_cdm_log ID terakhir'.$latesid);
                            $cdmlogid = DB::table('tx_cdm_log')->selectRaw('MAX(cdm_issued) cdm_issued')->where('cdm_id',$CDM_ID)->whereNotIn('cdm_type',['12'])->first();
                            $cdm_log_id =$cdmlogid->cdm_issued;
                            \Log::Info('cdm_log_id - >'. $cdm_log_id);
                            $waktu=time();
                            $wksekarang = $this->wksekarang();
                            $RESPONSE='';
                            if($cdm_log_id!=''){
                                $from_time 	= strtotime($cdm_log_id);
                                $to_time 	= strtotime($wksekarang);
                                $RESPONSE	= $to_time - $from_time;		
                            }

                            unset($vale); 
                            $vale = [ 
                                $latesid,$CDM_ID, '9', $ashtam_data['id'], 'NOF', '10', 
                                $wksekarang, $RESPONSE, $ashtam_data['VA'].$ashtam_data['state'].$ashtam_data['serial_nr'], end($volcano), '1' 
                            ];  
                            $fsql = DB::insert('insert into tx_cdm_log (cdm_log_id,cdm_id, cdm_type, data_id, cdm_stakeholder, cdm_stakeholder_id,cdm_issued, cdm_response, cdm_noticenumber, cdm_volcano, user_id )values(?,?,?,?,?,?,?,?,?,?,?)',$vale);    
                            if($fsql){
                                \Log::Info(' insert into tx_cdm_log success');
                            }
                        // }
                        // $fsqlvollog = DB::table('tx_volcano_log')->selectRaw('count(*) jml')
                        // ->where('va_no',end($volcano))
                        // ->where('log_type','9')
                        // ->where('log_shortdesc',$ashtam_data['C'].' '.$ashtam_data['VA'].$ashtam_data['state'].$ashtam_data['serial_nr'])->first();
                        // if ($fsqlvollog->jml < 1){

                            $getlastid = DB::table('tx_volcano_log')->latest('log_id')->first();
                            $latesid=$getlastid->log_id + 1;
                            \Log::Info('tx_volcano_log ID terakhir'.$latesid);
                            unset($vale);
                            $vale = [ 
                                $latesid,end($volcano), '9', $wksekarang, $ashtam_data['id'], '5', '2', 
                                $ashtam_data['C'].' '.$ashtam_data['VA'].$ashtam_data['state'].$ashtam_data['serial_nr'] 
                            ];  
                            $fsqll = DB::insert('insert into tx_volcano_log (log_id, va_no, log_type, log_date, data_id, org_id, user_id, log_shortdesc )
                                        values(?,?,?,?,?,?,?,?)',$vale);    
                            if($fsqll){
                                \Log::Info(' insert into tx_volcano_log success');
                            }
                        // }
                      
                    }
                }else{
                    \Log::Info('Issued-> 1 Found');
                }	
            // }
        }
    }
    public function handle()
    {
        $date = date('Y-m-d');
        // $date = '2021-04-04';
        $url   = "http://36.67.210.229:8899/S041RN4V/WAAF/".$date;

        $ashtam = $this->getData($url);
        $ashtam = json_decode(json_encode($ashtam),true);
        
        $this->processing($ashtam);

        $date1 = date('Y-m-d');
        // $date1 = '2021-04-04';
        $url1   = "http://36.67.210.229:8899/S041RN4V/WIIF/".$date1;

        $ashtam1 = $this->getData($url1);
        $ashtam1 = json_decode(json_encode($ashtam1),true);
        
        $this->processing($ashtam1);
                
    }
}
