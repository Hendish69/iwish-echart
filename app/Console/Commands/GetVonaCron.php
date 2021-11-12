<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Api\TxVona;
use App\Models\Api\TxCdm;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VonaMail;

class GetVonaCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getvona:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hourly update Volcano Status';

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
    /**
     * Execute the console command.
     *
     * @return int
     */
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
    private function getData(){
        $ret = $this->_getData();
        return $ret;
    }
    private function _getData(){

        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbWFnbWEuZXNkbS5nby5pZFwvYXBpXC9sb2dpblwvc3Rha2Vob2xkZXIiLCJpYXQiOjE2MTIxNzMyMjcsImV4cCI6MTY0MzcwOTIyNywibmJmIjoxNjEyMTczMjI3LCJqdGkiOiIxM1d6MTBTMmx4TmN3UmRpIiwic3ViIjo0LCJwcnYiOiI0YTlkOWEyZDI2ODAyYzMxMmU4ZTVhNWJlNjBmZjI2ZjBmYzYzZDdkIiwic291cmNlIjoiTUFHTUEgSW5kb25lc2lhIiwiYXBpX3ZlcnNpb24iOiJ2MSIsImRheXNfcmVtYWluaW5nIjozNjQsImV4cGlyZWRfYXQiOiIyMDIyLTAyLTAxIDAwOjAwOjAwIn0.cci5Izzlr01ivgDrWnQLqNz87X1zliV4CUmdemT9rf8";
        $url   = "https://magma.esdm.go.id/api/v1/vona?token=".$token;
        $response = $this->_initCurl($url);
        return json_decode($response,true);
    }
    public function handle()
    { 
        $vona = $this->getData();
        $tanggal = date('Y-m-d'); 
        
        // $tanggal = \DB::table('tx_vona')->orderBy('issued', 'DESC')->first();
        // \Log::info(substr($tanggal->issued,0,10));die;
        // $vona = get_object_vars($vona);
        $len = isset($vona['data']) ? count($vona['data']) : 0;
        \Log::Info('paling atas jml issued: '.$len);
        for($i=0; $i < $len; $i++){

            $vona_data = $vona['data'][$i];
            \Log::Info(substr($vona_data['issued'],0,10).'<--->'.$tanggal.'vona uuid'.$vona_data['uuid']);
          
            
            $fsqlcek = DB::table('tx_vona')->selectRaw('count(*) jml')
            ->where("uuid", $vona_data['uuid'] )->first();
            // if(substr($vona_data['issued'],0,10)==$tanggal){
            // if($fsqlcek->jml < 1){
                // $fsqlcek->next(); 
                \Log::Info('jml : '.$fsqlcek->jml);
                \Log::Info('Issued->'.$vona_data['uuid'].' Volcano->'.$vona_data['volcano']);
                if( $fsqlcek->jml < 1 ){
                    \Log::Info('insert txvona');
                    $tx_vona = TxVona::create(
                        [                    
                            "uuid"           =>    $vona_data['uuid'], 
                            "noticenumber"   =>    $vona_data['noticenumber'], 
                            "issued"         =>    $vona_data['issued'], 
                            "code_id"        =>    $vona_data['code_id'],
                            "smithsonian_id" =>    $vona_data['smithsonian_id'], 
                            "volcano"        =>    $vona_data['volcano'],
                            "cu_code"        =>    $vona_data['cu_code'],
                            "prev_code"      =>    $vona_data['prev_code'],
                            "location"       =>    $vona_data['location'],
                            "vas"            =>    $vona_data['vas'],
                            "vch_summit"     =>    $vona_data['vch_summit'], 
                            "vch_asl"        =>    $vona_data['vch_asl'],
                            "vch_other"      =>    $vona_data['vch_other'],
                            "remarks"        =>    $vona_data['remarks'],
                            "issued_utc"     =>    $vona_data['issued_utc'],
                            "created_date"   =>    date('Y-m-d H:i:s')
                        ]
                    );
                    
                    $VONA_ID = $tx_vona->vona_id;
                    if( !empty($VONA_ID) ){    
                        \Log::info("insert into tx_vona success ID = (".$VONA_ID.") ");
                    }else{
                        \Log::info('Failed to insert data tx_vona row into database.');
                    }
                    if(strtoupper($vona_data['cu_code'])=='RED'){
                        $status = '4';
                    }
                    if(strtoupper($vona_data['cu_code'])=='ORANGE'){
                        $status = '3';
                    }
                    if(strtoupper($vona_data['cu_code'])=='YELLOW'){
                        $status = '2';
                    }
                    if(strtoupper($vona_data['cu_code'])=='GREEN'){
                        $status = '1';
                    }
                    // \Log::Info("Update tm_volcano status successdddd");
                    $upVolcano = DB::table('tm_volcano')
                                            ->where('va_no', $vona_data['smithsonian_id'])
                                            ->where('va_last_update','<', $vona_data['issued'])
                                            ->update(
                                                [
                                                    'va_status' => $status,
                                                    'va_last_update' => $vona_data['issued']
                                                ]
                                            );
                    // $upVolcano=$upVolcano->save();
                    \Log::Info("Update tm_volcano status success".$upVolcano);
                    if($upVolcano){
                        \Log::Info("Update tm_volcano ".$vona_data['smithsonian_id']." status success");
                    }
                    $cdmcek = DB::table('tx_cdm')->where("va_no", $vona_data['smithsonian_id'] )->first();
                    \Log::Info('cdm_id'. $cdmcek->cdm_id);
                    $CDM_ID =$cdmcek->cdm_id; 
                    // $dm_id = DB::table('tx_cdm')->select('va_no')->where('va_no',$vona_data['smithsonian_id'])->first();
                    // \Log::Info('va_no ->'. $dm_id);
                    if($CDM_ID==''){
                        $update = TxCdm::create(
                            [
                                "va_no"         => $vona_data['smithsonian_id'], 
                                "va_status"     => $status, 
                                "cdm_date"      => $vona_data['issued'], 
                                "cdm_status"    => $status, 
                                "cdm_admin"     => 1
                            ]
                        );
                        $CDM_ID = '';
                        $CDM_ID = $update->cdm_id; 
                        if($update){
                            \Log::Info("Insert tx_cdm success ") ;
                            
                            #AUTOINVITE
                            $fsqlusers = User::selectRaw('users.id,email,first_name,last_name, role_user.role_id')
                            ->rightJoin('role_user', function ($join) {
                                $join->on('role_user.user_id', '=', 'users.id')
                                        ->where('role_user.role_id', '=', '1');
                            })         
                            ->where('user_status','1')->get();
                            
                            foreach ($fsqlusers as $fsqluser){

                                $USER_EMAIL = strtolower($fsqluser->email);
                                $USER_FULLNAME = $fsqluser->first_name .' '. $fsqluser->last_name;
                                $USER_ID = $fsqluser->id;
                                
                                unset($vale); 
                                $vale = [ $CDM_ID, $USER_ID ];  
                                DB::insert('insert into tx_cdm_users (cdm_id, user_id)values(?,?)',$vale);

                                $mailto = strtolower($USER_EMAIL);
                                if(env('APP_ENV')=='local')
                                    $mailto = "hendi.sh@gmail.com";//for dev only
                                $name  = $USER_FULLNAME;
                                $details = [
                                    'title' => '[no-reply] Collaborative Decision Making (CDM) - '.$vona_data['volcano'],
                                    'body'  => 'Welcome to the Collaborative Decision Making (CDM) group of Volcanic Activity Impact Handling.<br>
                                                Every information and coordination in this group only related to <b>Volcano '.$vona_data['volcano'].'</b> eruption.<br>
                                                Please access the following link to start your participation <a href="https://iwish.dephub.go.id/" target="_blank">https://iwish.dephub.go.id/</a><br><br>
                                                This message is sent automatically, do not reply to this message.<br>If you need help, please contact us via email iwish@aimindonesia.dephub.go.id<br><br>'
                                ];
                                \Log::Info('sent mail to : '.$mailto);
                                Mail::to($mailto)->send(new VonaMail($details));
                                // if(Mail::failures()){
                                //     \Log::info('email can\'t be sent to'. $mailto);   
                                // }				
                            }
                            
                        }
                    }else{		
                        $upCdm = DB::table('tx_cdm')
                        ->where('cdm_id', $CDM_ID)
                        ->where('cdm_date','<', $vona_data['issued'])
                        ->update(
                            [
                                'va_status' => $status,
                                "cdm_date"      => "'".$vona_data['issued']."'", 
                                "cdm_status"    => $status, 
                            ]
                        );

                        if($upCdm){
                            \Log::Info("Update tx_cdm status success");
                        }
                    }
                    
                    $fsqllog = DB::table('tx_cdm_log')->selectRaw('count(*) jml')
                                                ->where('cdm_id',$CDM_ID)
                                                ->where('cdm_type','10')
                                                ->where('cdm_noticenumber',$vona_data['noticenumber'])->first();
                    if ($fsqllog->jml < 1){
                                                    
                        $getlastid = DB::table('tx_cdm_log')->latest('cdm_log_id')->first();
                        $latesid=$getlastid->cdm_log_id + 1;
                        \Log::Info('tx_cdm_log ID terakhir'.$latesid);
                        $fsql = DB::table('tx_cdm_log')->selectRaw('MAX(cdm_issued) cdm_issued')
                        ->where('cdm_id',$CDM_ID)
                        ->whereNotIn('cdm_type',['12'])->first();

                        $waktu=time();
                        // $wksekarang = date("Y-m-d H:i:s",$waktu);
                        $wksekarang = $this->wksekarang();

                        if($fsql->cdm_issued !=''){    
                            $from_time 	= strtotime($fsql->cdm_issued);
                            $to_time 	= strtotime($wksekarang);
                            $RESPONSE	= $to_time - $from_time;
                        }
    
                        unset($vale); 
                        $vale = [$latesid, $CDM_ID, '10', $VONA_ID, 'CVGHM', 2 ,$wksekarang, $RESPONSE, $vona_data['noticenumber'], $vona_data['smithsonian_id'] , 1];  // end($volvano) referer to ?
                        $fsql = DB::insert('insert into tx_cdm_log (cdm_log_id,cdm_id, cdm_type,data_id,cdm_stakeholder,cdm_stakeholder_id,cdm_issued,cdm_response,cdm_noticenumber,cdm_volcano,user_id)values(?,?,?,?,?,?,?,?,?,?,?)',$vale);
        
                        if($fsql){
                            \Log::Info(' insert into tx_cdm_log success');
                        }
                    }
                    $fsqlvollog = DB::table('tx_volcano_log')->selectRaw('count(*) jml')
                                                ->where('va_no',$vona_data['smithsonian_id'])
                                                ->where('log_type','10')
                                                ->where('log_no',$vona_data['noticenumber'])->first();
                    if ($fsqlvollog->jml < 1){

                        $getlastid = DB::table('tx_volcano_log')->latest('log_id')->first();
                        $latesid=$getlastid->log_id + 1;
                        \Log::Info('tx_volcano_log ID terakhir'.$latesid);
                        unset($vale); 
                        $vale = [ $latesid,  
                            $vona_data['smithsonian_id'], $vona_data['noticenumber'], 
                                    $VONA_ID, '10',
                                    $wksekarang, '2',
                                    '2', $vona_data['volcano'].' - <b style="color:'.$vona_data['cu_code'].'">'.strtoupper($vona_data['cu_code']).'</b>'
                                ];  
                        $fsql = DB::insert('insert into tx_volcano_log (log_id,va_no, log_no, data_id, log_type, log_date, org_id, user_id, log_shortdesc)values(?,?,?,?,?,?,?,?,?)',$vale);
        
                        if($fsql){
                            \Log::Info(' insert into tx_volcano_log success');
                        }
                    }
                }else{
                    \Log::Info('Issued-> 1 Found');
                }	
            // }
        }
       
        // SEND NOTIF
        $fsqls = DB::table('tx_cdm_log')->selectRaw("tx_cdm_log.cdm_log_id, tx_cdm_log.cdm_id, tx_cdm_log.cdm_stakeholder, tb_reff.reff_name, tm_volcano.va_name")
        ->leftJoin('tb_reff', function ($join) {
            $join->on('tb_reff.reff_code', '=', 'tx_cdm_log.cdm_type')
                 ->where('tb_reff.reff_group', '=', '\'0002\'');
        })
        ->leftJoin('tx_cdm', 'tx_cdm.cdm_id', '=', 'tx_cdm_log.cdm_id')
        ->leftJoin('tm_volcano', 'tm_volcano.va_no', '=', 'tx_cdm.va_no')
        ->whereRaw('tx_cdm_log.cdm_notif is null')->first();
       
        if(isset($fsql->cdm_log_id) != ''){
            $CDM_LOG_ID = isset($fsql->cdm_log_id)? $fsql->cdm_log_id : '' ;
            $CDM_ID = isset($fsql->cdm_id) ? $fsql->cdm_id : '';
            $CDM_STAKEHOLDER = isset($fsql->cdm_stakeholder) ? $fsql->cdm_stakeholder :'';
            $REFF_NAME = isset($fsql->reff_name) ? $fsql->reff_name : '';
            $VA_NAME = isset($fsql->va_name) ? $fsql->va_name : '' ;
            
            $upSql = DB::table('tx_cdm_log')
                            ->where('cdm_log_id', $CDM_LOG_ID)
                            ->update( [
                                    'cdm_notif' => 1
                                    ] );
        }
        // \Log::Info('cdm_log_id -> '. isset($fsql['cdm_log_id']));
        // foreach ($fsqls as $fsql){
        //     $CDM_LOG_ID = isset($fsql->cdm_log_id)? $fsql->cdm_log_id : '' ;
        //     $CDM_ID = isset($fsql->cdm_id) ? $fsql->cdm_id : '';
        //     $CDM_STAKEHOLDER = isset($fsql->cdm_stakeholder) ? $fsql->cdm_stakeholder :'';
        //     $REFF_NAME = isset($fsql->reff_name) ? $fsql->reff_name : '';
        //     $VA_NAME = isset($fsql->va_name) ? $fsql->va_name : '' ;
        //     if($CDM_LOG_ID != ''){
        //         $upSql = DB::table('tx_cdm_log')
        //                 ->where('cdm_log_id', $CDM_LOG_ID)
        //                 ->update( [
        //                         'cdm_notif' => 1
        //                         ] );
        //         \Log::info("Update tx_cdm_log => cdm_notif = 1 ");
        //     }  
        // } 
        \Log::info("Update Volcano status Done");
    }
}
