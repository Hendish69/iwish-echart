<?php

namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Request as Req;
use Illuminate\Http\Request;
use Auth;
use App\Models\Api\RawdataPubDetail;
use App\Models\Api\RawdataPubNotam;
use App\Models\Api\RawdataPub;
use App\Models\Api\NavaidTemp;
use App\Models\Api\EaipApronTwyTemp as twytemp;
use App\Models\Api\EaipArptGateTemp as pstemp;
use App\Models\Api\EaipPushbackTemp as pbtemp;
use App\Models\Api\EaipChartContentTemp as CC_Temp;
use App\Models\Api\AtsTemp;
use App\Models\Api\Notify;
use App\Models\Api\User;
use App\Models\Api\SourceNr;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Route;
use App\Models\Api\RawdataPubAtt as Upload;
use App\Models\Api\PdfFile as chart;
use App\Models\Api\Td_aip as aip;
use App\Models\Api\RawdataPubChart as PubChart;
use Session;
use Image;
use File;
use Illuminate\Support\Facades\Mail;
use App\Mail\PublicationMail;

class PublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $path; 
    public function __construct()
    {
        $this->middleware('auth');
        //DEFINISIKAN PATH
		$this->path = public_path('upload/publication/'); 
    }

    public function aipsubmission($id)
    {
        $originalInput=Req::input();
        $user = Auth::user();

        $data['codeaip'] = getDataApi($originalInput,'/api/eaip/menu/one/');
        $data['id'] = $id;
        // if ($id=='pdf'){
            // $data['chart'] = getDataApi($originalInput, 'api/allchart');
            // $data['airport'] = getDataApi($originalInput, 'api/airports?ctry=ID&deleted=0&sort=arpt_name:asc');
            // $data['codchart'] = getDataApi($originalInput, 'api/cod/chart');
        // }
        
        return view('pages.publications.aipsubmission',$data);
    }

    public function eaiphtml()
    {
        $originalInput=Req::input();
        $user = Auth::user();
        $data['codeaip'] = getDataApi($originalInput,'/api/eaip/menu/one/');
        return view('pages.publications.eaip.eaipmenu',$data);
    }
    
    public function amdtlist($page)
    {
        $originalInput=Req::input();
        $user = Auth::user();
       
        $data['amdt']= Upload::selectRaw('rawdata_pub_att.*,rawdata_pub.*')
        ->rightJoin('rawdata_pub','rawdata_pub.rawdata_id','rawdata_pub_att.rawdataid')
        ->where('rawdata_pub.status_raw', '=', '100')
        ->where('rawdata_pub_att.file_att', '=', 'P')
            ->get();
            // dd($data);
        // $data['amdt']=  getDataApi($originalInput,'/api/pub/rawdata?status_raw=100&sort=update_date:desc');
        // $data['amdt']=  getDataApi($originalInput,'/api/pub/rawdata?status_raw=100&sort=update_date:desc');
        $data['page'] = $page;
        return view('pages.publications.eaip.amdtlist',$data);
    }

    public function enrhtml($id)
    {
        $originalInput=Req::input();
        $user = Auth::user();
        $data['enr'] = getDataApi($originalInput,'api/ats/list/'.$id.'?ctry=ID');
        $data['codeaip'] = getDataApi($originalInput,'/api/eaip/menu/two/9');
        switch ($id) {
            case '61':
                $data['aipcode']='ENR 3.1';
                break;
            
            case '62':
                $data['aipcode']='ENR 3.2';
                break;
            case '63':
                $data['aipcode']='ENR 3.3';
                break;
            case '64':
                $data['aipcode']='ENR 3.4';
                break;
        }
        $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id='.$data['aipcode']);
        $data['subid'] = $id; 
        // dd($data);
        return view('pages.publications.eaip.tablehtml',$data);
    }
    
    public function datahistory()
    {
        $originalInput=Req::input();
        $user = Auth::user();
       
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // dd($user);
        $data['source']= aip::selectRaw('nr_yr')
        ->where('nr_yr', 'like', '%AMDT%')
        ->groupby('nr_yr')
        ->orderby('nr_yr', 'desc')
            ->get();
// dd($data);
        return view('pages.publications.datahistory',$data);
    }
    public function request()
    {
        
        //    dd($data);
    
        $originalInput=Req::input();
        $user = Auth::user();
        $data['backto']='';
        $data['request'] = '';
        $data['tbreff'] = '';
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // dd($user);
        $data['request'] = getDataApi($originalInput, '/api/pub/rawdata?tablename=arpt&sort=update_date:desc');
        //    dd($data);

    $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');


        $data['tbreff']  = getDataApi($originalInput, '/api/tablereff?reff_group=0020&sort=reff_order:asc');
        $data['piapusat']= User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
                            ->rightJoin('role_user','role_user.user_id','users.id')
                                    ->where('role_user.role_id', '=', '20')
                            ->get();
        $data['dnp']= User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
        ->rightJoin('role_user','role_user.user_id','users.id')
        ->where('role_user.role_id', '=', '21')
            ->get();
        $data['airnav']= User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
        ->rightJoin('role_user','role_user.user_id','users.id')
        ->where('role_user.role_id', '=', '24')
            ->get();
        return view('pages.publications.datarequest',$data);
    }
    public function pialist()
    {
        $originalInput=Req::input();
        $user = Auth::user();
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // dd($user);
        $data['pia'] = getDataApi($originalInput, '/api/auth?sort=id:asc');
        $data['airport'] = getDataApi($originalInput, 'api/airports?ctry=ID&deleted=0&sort=auth:asc');
        
        return view('pages.admin.pia',$data);
    }
    
    public function requestview($info){
        $originalInput = Req::input();
        $user = Auth::user();
        $infox= explode('@',$info,2);
        $id=$infox[0];
        $data['table']=$infox[1];
       
        // dd($info,$id,$infox[1]);
        // Route::get('/eaip/getrequestdetail/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestdetail');
        // Route::get('/eaip/getrequestapron/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestapron');
        // Route::get('/eaip/getrequestparkingstand/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestparkingstand');
        // Route::get('/eaip/getrequestpushback/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestpushback');
        // Route::get('/eaip/getrequestobstacle/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestobstacle');
        // Route::get('/eaip/getrequestrwy/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestrwy');
        // Route::get('/eaip/getrequestrwyphysical/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestrwyphysical');
        // Route::get('/eaip/getrequestrwylight/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestrwylight');
        // Route::get('/eaip/getrequestcomm/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestcomm');
        // Route::get('/eaip/getrequestnavaid/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestnavaid');
        switch ($infox[1]) {
            case 'arpt':
                $init_arr = array(
                    'airport'          => '/api/airports?arpt_ident='.$id,
                    'content'          => '/api/eaip/getrequestdetail/temp/'.$id,
                    'apron'     => '/api/eaip/getrequestapron/temp/'.$id,
                    'parkingstand'  => '/api/eaip/getrequestparkingstand/temp/'.$id,
                    'pushback'  => '/api/eaip/getrequestpushback/temp/'.$id,
                    'obstacles'     => '/api/eaip/getrequestobstacle/temp/'.$id,
                    'rwy'           => '/api/eaip/getrequestrwy/temp/'.$id,
                    'rwythr'           => '/api/eaip/getrequestrwyphysical/temp/'.$id,
                    'rwylgt'           => '/api/eaip/getrequestrwylight/temp/'.$id,
                    'comm'           => '/api/eaip/getrequestcomm/temp/'.$id,
                    'nav'           => '/api/eaip/getrequestnavaid/temp/'.$id,
                    'ils'           => '/api/eaip/getrequestils/temp/'.$id,
                    'marker'           => '/api/eaip/getrequestmarker/temp/'.$id,

                );
                $init_arr1 = array(
                    'airport'          => '/api/airports?arpt_ident='.$id,
                    'content'          => '/api/eaip/getrequestdetail/current/'.$id,
                    'apron'     => '/api/eaip/getrequestapron/current/'.$id,
                    'parkingstand'  => '/api/eaip/getrequestparkingstand/current/'.$id,
                    'pushback'  => '/api/eaip/getrequestpushback/current/'.$id,
                    'obstacles'     => '/api/eaip/getrequestobstacle/current/'.$id,
                    'rwy'           => '/api/eaip/getrequestrwy/current/'.$id,
                    'rwythr'           => '/api/eaip/getrequestrwyphysical/current/'.$id,
                    'rwylgt'           => '/api/eaip/getrequestrwylight/current/'.$id,
                    'comm'           => '/api/eaip/getrequestcomm/current/'.$id,
                    'nav'           => '/api/eaip/getrequestnavaid/current/'.$id,
                    'ils'           => '/api/eaip/getrequestils/current/'.$id,
                    'marker'           => '/api/eaip/getrequestmarker/current/'.$id,
                    

                );
                $no=0;
                foreach($init_arr as $ky => $va){
                    $data[$ky]= getDataApi($originalInput, $va);
                    
                }
                foreach($init_arr1 as $ky => $va){
                    // var_dump($ky);
                    $data[$ky.'_curr']= getDataApi($originalInput, $va);
                }
                $icao= $data['airport'][0]->icao;
                if (strlen($icao)==2 || $icao==''){
                    $sql='api/eaip/aip?aerodrome='.$data['airport'][0]->arpt_name;
                }else{
                    if ($data['airport'][0]->vol=='2'){
                        $sql='api/eaip/aip?icao_code='.$icao.'&id_aip_induk=41';
                    }else  if ($data['airport'][0]->vol=='3'){
                        $sql='api/eaip/aip?icao_code='.$icao.'&id_aip_induk=45';
                    }else{
                        $sql='api/eaip/aip?icao_code='.$icao;
                    }
                    
                }
                $data['chart'] = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&deleted=0&sort=seq:asc');
                // dd(strlen($icao),$icao,$data['chart'],$sql);
                $data['pdfchart'] = getDataApi($originalInput,  $sql);
            // dd($data['pdfchart'],$sql);
            if (!empty($data['pdfchart'])){
                $idkode= $data['pdfchart'][0]->id_aip;
                $data['lstchart'] = getDataApi($originalInput, 'api/eaip/aip?id_aip_induk='.$idkode.'&is_active=1&sort=no_urut:asc');

            }else{
                $data['lstchart']=[];
            }
        
                $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');
                // $data['chart']= getDataApi($originalInput, '/api/airport/chart?arpt_ident='.$id); 
                // dd($data);
                break;
            case 'navaid':
                $init_arr = array(
                    'navtemp'       => '/api/navaid/temp?nav_id='.$id,
                    'navcurr'       => '/api/navaid?nav_id='.$id,
                    'ats'       => '/api/ats/point/'.$id,
                    'atstemp'       => '/api/ats/point/temp/'.$id
                );
                foreach($init_arr as $ky => $va){
                    $data[$ky]= getDataApi($originalInput, $va);
                    // dd($data[$ky]);
                };
                break;
            case 'waypoint':
                $init_arr = array(
                    'wpttemp'       => '/api/waypoint/temp/list?wpt_id='.$id,
                    'wptcurr'       => '/api/waypoint/list?wpt_id='.$id,
                    'ats'       => '/api/ats/point/'.$id,
                    'atstemp'       => '/api/ats/point/temp/'.$id
                );
                foreach($init_arr as $ky => $va){
                    $data[$ky]= getDataApi($originalInput, $va);
                    // dd($data[$ky]);
                };
                break;
            case 'ENR':
            case 'GEN':
                $data['id']= $id;
                if ($id=='ENR 4.1' || $id=='GEN 2.5'){
                    $data['navtemp']= getDataApi($originalInput, '/api/navaid/temp?status_vld=R&or=status_vld:N');
                    $data['nav']= getDataApi($originalInput, '/api/navaid?ctry=ID');
                    $data['ilstemp']= getDataApi($originalInput, '/api/ils/temp?status=R&or=status:N');
                    $data['ils']= getDataApi($originalInput, '/api/ils/list?ctry=ID');
                }else  if ($id=='ENR 4.3'){
                    $data['wpttemp']= getDataApi($originalInput, '/api/waypoint/temp/list?status=R&or=status:N');
                    $data['wpt']= getDataApi($originalInput, '/api/waypoint/list?ctry=ID');
                }else  if ($id=='GEN 2.4'){
                    $data['indicator'] = getDataApi($originalInput,'api/gen/locindicator?ctry=ID&deleted=0&sort=city:asc');
                    $data['indicatortemp'] = getDataApi($originalInput,'api/gen/locindicator/temp?status=R&or=status:N&deleted=0&sort=city:asc');
                }else  if ($id=='ENR 2.1'){
                    $data['asptemp']= getDataApi($originalInput, '/api/airspace/temp/list?status=R&or=status:N');
                    $data['asp']= getDataApi($originalInput, '/api/airspace/list?ctry=ID&deleted=0');
                }else  if ($id=='ENR 5.1' || $id=='ENR 5.2'){
                    $data['suastemp']= getDataApi($originalInput, '/api/suas/temp/list?status=R&or=status:N');
                    $data['suas']= getDataApi($originalInput, '/api/suas/list?ctry=ID&deleted=0&sort=suas_type:asc');
                }else{
                    // $ats=AtsTemp::query()->whereIn('status',['R','N'])->orderby('ats_ident')->orderby('seq_424')->get();
                    $data['atstemp']= getDataApi($originalInput, '/api/ats/temp?status=R&or=status:N&sort=ats_ident:asc');
                    // dd($ats[0],$data['atstemp']);
                    $dt=$data['atstemp'];
                    // $data['ats']=[];
                    $data['ats']=[];
                    // dd($dt);
                    foreach ($dt as $key => $value) {
                        // $at=getDataApi($originalInput,'api/ats?ctry='.$value->ctry.'&sort=seq_424:asc');
                        $at=getDataApi($originalInput,'api/ats?ats_id='.$value->ats_id);
                        if (!empty($at)){
                            array_push($data['ats'], $at);
                        }
                    }
                }

                break;
                
            }
            // dd($data['chart'],$data['lstchart']);
        $return['data']=$data;
    //    dd($data);
        return view('pages.publications.requestdetail',$return);
    }
    public function createpdf($id){    
        $originalInput=Req::input(); 
        $user = Auth::user();
    
        if ($id=='current'){
            $data['codeaip'] = getDataApi($originalInput,'/api/eaip/menu/one/');
            return view('pages.publications.createpdf.createcurrentpdf',$data);
        }else if ( $id=='request' || $id=='publication'){
            if ( $id=='request'){
                $data['type'] = 'request';
                $sql='/api/pub/rawdata?sort=update_date:desc';
            }else{
                $data['type'] = 'publication';
                $sql='/api/pub/rawdata?status_raw=100&sort=update_date:desc';
            }
            // $data['request'] = getDataApi($originalInput, '/api/pub/rawdata?sort=update_date:desc');
            $data['menu'] = getDataApi($originalInput,'/api/eaip/menu');
            $data['request'] = getDataApi($originalInput, $sql);
        //    dd($id,$data['request']);
            return view('pages.publications.createpdf.createrequestpdf',$data);
        }
    }
    public function navaidupdate(Request $request)
    { 
        $lat = toDecimal($request->navlat);
        $lon=toDecimal($request->navlon);
        $request['geom']='POINT('.$lon.' '.$lat.')';
        $navid=$request->navid;
        dd($request);
        $navaid = NavaidTemp::find($navid);
        // dd($airport,$dtsrc,$request);
        $navaid->update($request->all());
        return redirect('/navaid');
    }
    public function parkingstandupdate(Request $request)
    { 
    
        $navid=$request->psid;
        $page=$request->parkingpage;
        $arptident=$request->arpt_ident_gate;
        $tab=$request->tab;
        $message = '';
        $status = true;
        // dd($request);
        if ($request->status=='R'){
			$id=$request->psid;
			$airport = pstemp::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{
            $last = pstemp::latest('id')->first();
            $request->merge([
				'id' => $last->id + 1,
			]);
			pstemp::create($request->all());
			$ret_msg ='Insert Data Success';
		}
		//save data to raw data pub, utk request data
       
		$raw_dat = RawdataPub::where('tablename', 'arpt')
								->where('fieldname', 'arpt_ident')
								->where('fieldid', $arptident)
								->where('status_raw','<', 100)
								->first();
		if ($raw_dat === null) {
			$raw_dat = new RawdataPub;
			$raw_dat->tablename = 'arpt';
			$raw_dat->fieldname = 'arpt_ident';
			$raw_dat->fieldid = $arptident;
			$raw_dat->status_raw = 0;
		}
		// dd($raw_dat);
		$raw_dat->ori_change_pic = $request->editor;
		$raw_dat->save();
        // $ps = pstemp::find($navid);
        // // dd($ps);
        // $ps->update($request->all());
        if ($status) { 
            $message = 'Your data has been saved!!';
            Session::flash('status', 'success');
        }
        return back()->withInput(['tab'=>$tab])->with('message',$message);
        // return redirect('aipedit/'.$page.'/'.$arptident)->withInput(['tab'=>'tabItem8'])->with('message',$message);
        // return redirect('/navaid');
    }
    public function pushbackupdate(Request $request)
    { 
       
        $page=$request->pushbackpage;
        $arptident=$request->arpt_ident_pushback;
        $tab=$request->tab;
        $message = '';
        $ret_msg='';
        // dd($request);
		if ($request->status=='R'){
			$id=$request->pbid;
			$airport = pbtemp::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{
            $last = pbtemp::latest('id')->first();
            $request->merge([
				'id' => $last->id + 1,
			]);
			pbtemp::create($request->all());
			$ret_msg ='Insert Data Success';
		}
		//save data to raw data pub, utk request data
		$raw_dat = RawdataPub::where('tablename', 'arpt')
								->where('fieldname', 'arpt_ident')
								->where('fieldid', $arptident)
								->where('status_raw','<', 100)
								->first();
		if ($raw_dat === null) {
			$raw_dat = new RawdataPub;
			$raw_dat->tablename = 'arpt';
			$raw_dat->fieldname = 'arpt_ident';
			$raw_dat->fieldid = $arptident;
			$raw_dat->status_raw = 0;
		}
		// dd($raw_dat);
		$raw_dat->ori_change_pic = $request->editor;
		$raw_dat->save();
            $message = $ret_msg;
            Session::flash('status', 'success');
        // }
        return back()->withInput(['tab'=>$tab])->with('message',$message);

    }
    public function aprontwyupdate(Request $request)
    { 
        $page=$request->apronpage;
        $arptident=$request->arpt_ident;
        $tab=$request->tab;
        $message = '';
        $ret_msg='';
        $charts=[];
        // $fsqlusers =  User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
        // ->rightJoin('role_user', function ($join) {
        //     $join->on('role_user.user_id', '=', 'users.id')
        //         ->where('role_user.role_id', '=', '19');
        // })         
        // ->where('pia_id',$request->pia_id)->get();
       

        // dd($request);
		if ($request->status=='R'){
			$id=$request->apronid;
			$airport = twytemp::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{
            $last = twytemp::latest('id')->first();
            $request->merge([
				'id' => $last->id + 1,
			]);
            // dd($request->all());
			$data = twytemp::create($request->all());
            // dd($data->id);
			$ret_msg ='Insert Data Success';

		}
		//save data to raw data pub, utk request data
		$raw_dat = RawdataPub::where('tablename', 'arpt')
								->where('fieldname', 'arpt_ident')
								->where('fieldid', $arptident)
								->where('status_raw','<', 100)
								->first();
		if ($raw_dat === null) {
			$raw_dat = new RawdataPub;
			$raw_dat->tablename = 'arpt';
			$raw_dat->fieldname = 'arpt_ident';
			$raw_dat->fieldid = $arptident;
			$raw_dat->status_raw = 0;

           
		}
		// dd($raw_dat);
		$raw_dat->ori_change_pic = $request->editor;
		$raw_dat->save();

        $charts=chart::whereIn('chart_code',['AD 2.24-1','AD 2.24-2','AD 2.24-3'])
        ->where('arpt_ident', $arptident)->get();

        foreach ($charts as $key => $chart) {
            $chart_dat = PubChart::where('chartname_id', $chart->arptchart_id)
            ->where('rawdataid', $raw_dat->rawdata_id)
            ->first();
                if ($chart_dat === null) {
                    $chart_dat = new PubChart;
                    $chart_dat->rawdataid = $raw_dat->rawdata_id;
                    $chart_dat->chartname_id = $chart->arptchart_id;
                    $chart_dat->path_file = $chart->path_file;
                    $chart_dat->filename = $chart->chart_name;
                    $chart_dat->chart_filename = $chart->chart_name;
                }
                // dd($raw_dat);
                // $chart_dat->ori_change_pic = $request->editor;
                $chart_dat->save();
        }

        $arr=[$request->ad53,$request->ad156,$request->ad57,$request->ad59];
        $arr1=[53,156,57,59];

        foreach ($arr as $rk => $rval ){  
            $value=$rval; 
            // dd($rval,$arr1[$rk]);
            if($value=='NIL') $value='';   
                $cat_id =$arr1[$rk]; 
                $exist =  CC_Temp::where('category_id','=',$cat_id)
                                ->where('arpt_ident','=',$request->arpt_ident)
                                ->first();
    
                if($exist){
                    if( preg_replace("/\s+/", "",$exist->content) != preg_replace("/\s+/", "",$value) ){
                        $exist->content= $value; 
                        $exist->status ='R';
                        $exist->editor=$request->editor;
                        // dd('exist-> exist = '. $exist);
                        $exist->save(); 
                        // update rawdata_pub
                        if(!is_null($exist)){ 
                            $raw_dat = RawdataPub::where('tablename', 'arpt')
                                                ->where('fieldname', 'arpt_ident')
                                                ->where('fieldid', $request->arpt_ident)
                                                ->where('status_raw','<', 100)
                                                ->first();
                            if ($raw_dat === null) {
                                $raw_dat = new RawdataPub;
                                $raw_dat->tablename = 'arpt';
                                $raw_dat->fieldname = 'arpt_ident';
                                $raw_dat->fieldid = $request->arpt_ident;
                                $raw_dat->status_raw = 0;
                            } 
                            $raw_dat->ori_change_pic = $request->editor;
                            $raw_dat->save(); 
                        } 
                    }
                    $status=true;
                }else{
                    $dat = new CC_Temp;                         
                    $dat->category_id   = $cat_id;
                    $dat->arpt_ident    = $request->arpt_ident ;
                    $dat->content       = $value; 
                    $dat->status        = 'N';
                    $dat->editor        = $request->editor;
                    // dd('not exist-> dat = '. $dat);
                    $dat->save(); 
                    // update rawdata_pub
                    if($dat){ 
                        $raw_dat = RawdataPub::where('tablename', 'arpt')
                                                ->where('fieldname', 'arpt_ident')
                                                ->where('fieldid',$request->arpt_ident)
                                                ->where('status_raw','<', 100)
                                                ->first();
                        if ($raw_dat === null) {
                            $raw_dat = new RawdataPub;
                            $raw_dat->tablename = 'arpt';
                            $raw_dat->fieldname = 'arpt_ident';
                            $raw_dat->fieldid = $request->arpt_ident;
                            $raw_dat->status_raw = 0;
                        }
                        $raw_dat->ori_change_pic = $request->editor;
                        $raw_dat->save();
                    }
                    $status=true;
                } 
        } 


            $message = $ret_msg;
            Session::flash('status', 'success');
        // }
        return back()->withInput(['tab'=>$tab])->with('message',$message);
        // return redirect('aipedit/'.$page.'/'.$arptident)->withInput(['tab'=>$tab])->with('message',$message);
    }
    public function remove(Request $request)
    {
        // dd($request);
        $airport = RawdataPub::find($request->id);
        
        $airport->delete();
     
        return redirect('/DataRequest');
    }
    public function store(Request $request)
    {
       
        if (!is_null($request->pub_date) && !is_null($request->eff_date)){
            $yy=substr($request->pub_date,-2);
            // dd($yy,$request->pub_date);
            $nr=null;
            if (!is_null($request->nr)){
                $nr=$request->nr.'/'.$yy;
            }
            $src=SourceNr::where('pub_date','=',$request->pub_date)->where('eff_date','=',$request->eff_date)->first();
            $srcid=null;
            if ($src == null){
                $lst= SourceNr::latest('id')->first();
                $src=new SourceNr;
                $srcid=$lst->id + 1;
                $src->id=$srcid;
                $src->src_id=$nr;
                $src->src_type=$request->pub_type;
                $src->pub_date=$request->pub_date;
                $src->eff_date=$request->eff_date;
                $src->publish='N';
                $src->raw_type=$request->raw_type;
                $src->save();
            }else{
                $srcid=$src->id;
                if (is_null($src->src_id)){
                    $src->src_id=$nr;
                    $src->save();
                }
                if ($request->status_raw=='100'){
                    $src->publish='Y';
                    $src->save();
                }

            }
            $request->merge([
				'raw_src_id' => $srcid,
			]);
        }
        // dd($request);
        // $deff=date_create($request->effdate);
        // $dpub=date_create($request->pubdate);
        // $effdate=date_format($deff,"Y-m-d") ;
        // $pubdate= date_format($dpub,"Y-m-d");
        // $sourcenr= $request->sourcenr;
        // $source= $request->source;
        // $pubid= $request->pubid;
        $userid=$request->status_pic;
        $rawid= (int)$request->rawdataid;
        $drafter=[];$qc=[];
        if (!is_null($request->pia_wilayah_qc) && !is_null($request->pia_wilayah_drafter)){
            $drafter=explode("!!",$request->pia_wilayah_drafter);
            $qc=explode("!!",$request->pia_wilayah_qc);
            $request['pia_wilayah_drafter']=$drafter[0];
            $request['pia_wilayah_qc']=$qc[0];
            // dd($request->pia_wilayah_qc,$request->pia_wilayah_drafter);
        }
        if (!is_null($request->pia_pusat_qc) && !is_null($request->pia_pusat_drafter)){
            $drafter=explode("!!",$request->pia_pusat_drafter);
            $qc=explode("!!",$request->pia_pusat_qc);
            $request['pia_pusat_drafter']=$drafter[0];
            $request[']pia_pusat_qc']=$qc[0];
            // dd($request->pia_pusat_qc,$request->pia_pusat_drafter);
        }
        // dd($request);
       //disini ada email nya?? iya, email dan nama 1 email aja atau gimana pak? ada 2 Drafter dan QC //coba liat form nnya
    ///harus pake query user lagi  ga kusah pak di value select tadi isinya email jangan id, id nya kan buat simpan ke database // value nya dua pak id + email nanti saat save di explode biar g banyak query, ok
    //ada yg error saat sy accept drafter
    $fsqlusers=[];
    switch ($request->status_raw) {
        case '20':
        $fsqlusers =  User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
                    ->rightJoin('role_user', function ($join) {
                        $join->on('role_user.user_id', '=', 'users.id')
                            ->where('role_user.role_id', '=', '19');
                    })         
                    ->where('pia_id',$request->pia_id)->get();
            if (!is_null($request->pia_wilayah_pic)){
                $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                ->where('id',$request->pia_wilayah_pic)->get();
            }
            break;
        case '01':
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_wilayah_drafter)->get();
            break;
        case '02':
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_pusat_drafter)->get();
            break;
        case '21':
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_wilayah_qc)->get();
            break;
        case '22':
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_pusat_qc)->get();
                break;
        case '41':
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_wilayah_drafter)->get();
            break;
        case '42':
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_pusat_drafter)->get();
            break;
        case '51':
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_wilayah_pic)->get();
            break;
        case '52':
        case '400':
            // email sama ditujukan utk PIC PIA PUSAT
            $fsqlusers = User::selectRaw('id,email,first_name,last_name')
                        ->where('id',$request->pia_pusat_pic)->get();
            break;
        case '50':
            $fsqlusers =  User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
                ->join('role_user','role_user.user_id', '=', 'users.id')
                ->where('role_user.role_id', '=', '20')
                ->get();
            break;
        case '70':
            $fsqlusers =  User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
                ->join('role_user','role_user.user_id', '=', 'users.id')
                ->where('role_user.role_id', '=', '21')
                ->get();
            break;
        case '200':
            $fsqlusers =  User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
            ->join('role_user','role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', '=', '18')
            ->where('pia_id',$request->pia_id)
            ->get();
            break;
        case '300':
                $fsqlusers =  User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
                ->join('role_user','role_user.user_id', '=', 'users.id')
                ->where('role_user.role_id', '=', '19')
                ->where('pia_id',$request->pia_wilayah_pic)
                ->get();
                break;
        // case '400':
        //     $fsqlusers =  User::selectRaw('users.id,name,email,first_name,last_name, role_user.role_id')
        //     ->join('role_user','role_user.user_id', '=', 'users.id')
        //     ->where('role_user.role_id', '=', '20')
        //     ->where('pia_id',$request->pia_pusat_pic)
        //     ->get();
        //     break;
        default:
            $fsqlusers=[];
            break;
   }
        #AUTOINVITE // disini query user yg dituju pak, sy dah simpan di request gimana ?
       
        // dd(count($fsqlusers),$request->status_raw,$request->pia_id);
  
  foreach ($fsqlusers as $fsqluser){
    //    if (count($drafter) > 0){
        // dd($fsqluser);
        if (!is_null($fsqluser->email)){
            $USER_EMAIL = strtolower($fsqluser->email);
    
            $USER_FULLNAME = $fsqluser->first_name .' '. $fsqluser->last_name;
            $USER_ID = $fsqluser->id;

            // var_dump($fsqluser);

            $mailto = strtolower($USER_EMAIL);
            $name  = $USER_FULLNAME;
                // $mailto = strtolower($drafter[1]);
                if(env('APP_ENV')=='local')
                    $mailto = "hendi.sh@gmail.com";//for dev only
                // $name  =$drafter[2];// $USER_FULLNAME;
                $details = [
                    'title' => '[no-reply] Request for Publication '.$request->subject, //.' to '.strtolower($USER_EMAIL),
                    'body'  => 'Welcome to the IWISH Indonesia application for AIP Publication.<br>
                                There is a request for Publication from <b>'.$request->emailsubject.'</b><br>
                                Please access the following link to process the request: <a href="https://iwish.dephub.go.id/" target="_blank">https://iwish.dephub.go.id/</a><br><br>
                                This message is sent automatically, do not reply to this message.<br>If you need help, please contact us via email iwish@aimindonesia.dephub.go.id<br><br>
                                Note : <i>'.$request->status_remarks.'</i><br><br>',
                    'sender'  => $request->emailsender
                ];
                // dd($details);
                \Log::Info('sent mail to : '.$mailto);
                Mail::to($mailto)->send(new PublicationMail($details));
                if(Mail::failures()){
                    \Log::info('email can\'t be sent to'. $mailto);   
                }
                unset($vale); 
                $vale = [ $USER_ID,$userid, $details['title'], $details['body'],$request->status_date,0];  
                DB::insert('insert into notify (to_userid,from_userid, email_subject,email_content,create_at,tag)values(?,?,?,?,?,?)',$vale);	
        }
    }
     

    //     $airport->update($request->all());
    // }
        $airport = RawdataPub::find($rawid);
        // dd($airport,$dtsrc,$request);
        $airport->update($request->all());
        // $airport->save();
    // }
   
   
        
        $params = $request->except('_token');

       
		// $this->validate($request, [
		// 	// 'files.*' => 'required|file|max:2000',
        //     'rawdataid' => 'required|string',
        //     'req_action' => 'required',
        //     'status_date' => 'required',
        //     'status_pic'=>''
		// ]);
        $RawdataPubDetail = RawdataPubDetail::create($request->except(['_token', 'files']));
        // dd($request->file('files'));
        if ($request->file('files') !== null){
            $id_mst = $RawdataPubDetail->rawdata_detail_id;
            $id_raw = $RawdataPubDetail->rawdataid;
            // var_dump($id_mst,$RawdataPubDetail->rawdataid,$RawdataPubDetail->rawdata_detail_id);
    
            $files = [];
            
            foreach ($request->file('files') as $file) {
                if ($file->isValid()) {
                    $fileName = time().'_'.$file->getClientOriginalName();  
                    $fileName1 = $file->getClientOriginalName();
                    $file->move($this->path,$fileName); 
                    $files[] = [
                        'filename' => $fileName,
                        'rawdatadetailid' => $id_mst,
                        'path_file' => 'upload/publication/',
                        'cod_filename' => $fileName1,
                        'rawdataid' => $id_raw,
                    ]; 
                }
            }
            Upload::insert($files); 
        }


       
        return redirect('/DataRequest');
        //                 ->with('success','Request created successfully.');
    }

    public function savepublicationfile(Request $request)
    {
        // dd($request,$request->file('filespub'));
        $originalInput=Req::input();
        // $data['backto']=$request->backto;
        if ($request->file('filespub') !== null){
            $id_raw = $request->rawdataid;
    
            $files = [];
            
            foreach ($request->file('filespub') as $file) {
                if ($file->isValid()) {
                    $fileName = time().'_'.$file->getClientOriginalName();  
                    $fileName1 = $file->getClientOriginalName();
                    if (strlen($fileName) > 100){
                        $fileName= substr($fileName,0,100);
                    }
                    if (strlen($fileName1) > 100){
                        $fileName1= substr($fileName1,0,100);
                    }
                    $file->move($this->path,$fileName); 
                    $files[] = [
                        'filename' => $fileName,
                        'path_file' => 'upload/publication/',
                        'cod_filename' => $fileName1,
                        'rawdataid' => $id_raw,
                        'pub_nr' => $request->pub_nr,
                        'file_att' => $request->file_att,
                        'sub_id' => $request->sub_id,
                        'name' => $request->name,
                    ]; 
                }
            }
            //  dd($files);
            Upload::insert($files); 
        }
        
        // return back()->withInput($data);
        // return redirect('/DataRequest',301, $data)->with('message', 'State saved correctly!!!');
        // return back()->with(['data' => $data]);
        return back()->with(['backto'=>$request->backto]);
        // return redirect('/DataRequest',$request->backto);
    }
    public function savenotam(Request $request)
    {
        
        $params = $request->except('_token');
        $RawdataPubDetail = RawdataPubNotam::create($request->except(['_token']));
      
        // return redirect('/DataRequest');
        return redirect()->back()->withInput();
       
    }
   

    // public function updaterawdata(Request $request, string $id)
    // {
    //     $params = $request->except('_token');
	
    //     $RawdataPubDetail = RawdataPub::create($request->except(['_token', 'files']));
    //     // dd($request->file('files'));
    //     if ($request->file('files') !== null){
    //         $id_mst = $RawdataPubDetail->rawdata_detail_id;
    
    //         $files = [];
            
    //         foreach ($request->file('files') as $file) {
    //             if ($file->isValid()) {
    //                 $fileName = time().'_'.$file->getClientOriginalName();  
    //                 $file->move($this->path,$fileName); 
    //                 $files[] = [
    //                     'filename' => $fileName,
    //                     'rawdatadetailid' => $id_mst,
    //                     'path_file' => 'upload/publication/',
    //                 ]; 
    //             }
    //         }
    //         Upload::insert($files); 
    //     }
    //     return redirect('/DataRequest');
    //     //                 ->with('success','Request created successfully.');
    // }
    public function timeline($id)
    { 
        $originalInput=Req::input();
        $user = Auth::user();
        
        $data['timeline'] = getDataApi($originalInput,'api/pub/rawdata?fieldid='.$id);
        $data['tbreff']= getDataApi($originalInput,'/api/tablereff?reff_group=0020&sort=reff_order:asc');
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.pubtimeline', $data);
        // }else{
        //     return abort('403');
        // }   
        
    }
    function abbr($id){
        $originalInput=Req::input();
        $user = Auth::user();
        if ($id=='edit'){
            $data['abbrtemp'] = getDataApi($originalInput,'api/abbr/temp?deleted=0&sort=ident:asc');
            $data['abbr'] = getDataApi($originalInput,'api/abbr?deleted=0&sort=ident:asc');
            $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
            $data['subid']='GEN 2.2';
        }else{
            $data['id'] ='30';
            $data['cod'] = getDataApi($originalInput,'api/eaip/menu?id=30');
            $data['nav'] = getDataApi($originalInput,'api/abbr?deleted=0&sort=ident:asc');
            $data['aipcode']='GEN 2.2';
            $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=GEN 2.2');
        }
        
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
        if ($id=='html'){
            return view('pages.publications.eaip.gen25html',$data);
        }else{
            return view('pages.publications.gen.gen22',$data);
        }
    }
    function airspacelist($id){
        $originalInput=Req::input();
        $user = Auth::user();
        if ($id=='edit'){
            $data['airspace'] = getDataApi($originalInput,'api/airspace/temp/list?ctry=ID&deleted=0&sort=airspace_type:asc');
            $data['titel']='ENR 2  AIR TRAFFIC SERVICE AIRSPACE';
            $data['parent']='ENR2.1';
            $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
            $data['subid']='ENR 2.1';
        }else{
            $data['airspace'] = getDataApi($originalInput,'api/airspace/list?ctry=ID&deleted=0&sort=airspace_type:asc');
            $data['aipcode']='ENR 2.1';
            $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=ENR 2.1');
        }
        $data['cod'] = getDataApi($originalInput,'api/eaip/type?id=8');
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            if ($id=='html'){
                return view('pages.publications.eaip.asphtml',$data);

            }else{

                return view('pages.publications.airspace.airspace',$data);
            }
        // }else{
        //     return abort('403');
        // }   
    }
    function suaslist($page,$id){
        $originalInput=Req::input();
        $user = Auth::user();
        if ($page=="edit"){
            $data['suas'] = getDataApi($originalInput,'api/suas/temp/list?ctry=ID&deleted=0&sort=suas_type:asc');
            $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
            if ($id=='70'){
                $data['aipcode'] ='ENR 5.1';
            }else{
                $data['aipcode'] ='ENR 5.2';
            }
        }else if ($page=="html"){
            $data['airspace'] = getDataApi($originalInput,'api/suas/list?ctry=ID&deleted=0&sort=suas_type:asc');
            if ($id=='70'){
                $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=ENR 5.1');
                $data['aipcode'] ='ENR 5.1';
            }else{
                $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=ENR 5.2');
                $data['aipcode'] ='ENR 5.2';
            }
        }
        // $data['suas'] = getDataApi($originalInput,'api/suas/list?ctry=ID&deleted=0&sort=suas_type:asc');
        $data['cod'] = getDataApi($originalInput,'api/eaip/type?id='.$id);
       
        $data['id'] = $id;
        
        if ($page=='html'){
            return view('pages.publications.eaip.asphtml',$data);

        }else{

            return view('pages.publications.airspace.suas',$data);
           
        }
     
    }
    function gen02($id,$status){
        $originalInput=Req::input();
        $user = Auth::user();
        
        if ($status=='html') {
            $data['cod'] = getDataApi($originalInput,'api/eaip/menu?id='.$id);
            if ($id=='17'){
                $data['aipcode']='GEN 0.2';
                $data['judul']='GEN 0.2 RECORD OF AIP AMENDMENTS';
                $codid='GEN 0.2';
            }else if ($id=='18'){
                $codid='GEN 0.3';
                $data['aipcode']='GEN 0.3';
                $data['judul']='GEN 0.3 RECORD OF AIP SUPPLEMENTS';
            }else if ($id=='34'){
                $codid='GEN 2.6';
                $data['aipcode']='GEN 2.6';
                $data['judul']='GEN 2.6 CONVERSION TABLE';
            }
            $data['id'] = $id;
            $data['nav'] = getDataApi($originalInput,'api/sourcenr?sort=pub_date:asc');
            $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id='.$codid);
            return view('pages.publications.eaip.gen25html',$data);
        }else{
            $data['source'] = getDataApi($originalInput,'api/sourcenr?sort=pub_date:desc');
            $data['id'] = $id;
            $view='pages.publications.gen.gen02';
            if ($id=='17'){
                $data['subid']='GEN 0.2';
                $data['judul']='GEN 0.2 RECORD OF AIP AMENDMENTS';
            }else if ($id=='18'){
                $data['subid']='GEN 0.3';
                $data['judul']='GEN 0.3 RECORD OF AIP SUPPLEMENTS';
            }else if ($id=='34'){
                $codid='GEN 2.6';
                $data['subid']='GEN 2.6';
                $data['judul']='GEN 2.6 CONVERSION TABLE';
                // $view='pages.publications.eaip.gen25html';
            }
            return view($view,$data);
        }   
    }
    function gen24($id){
        $originalInput=Req::input();
        $user = Auth::user();
        
        if ($id=='html') {
            $data['cod'] = getDataApi($originalInput,'api/eaip/menu?id=32');
            $data['id'] = '32';
            $data['nav'] = getDataApi($originalInput,'api/gen/locindicator?ctry=ID&deleted=0&sort=city:asc');
            $data['aipcode'] ='GEN 2.4';
            $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=GEN 2.4');
            return view('pages.publications.eaip.gen25html',$data);
        }else{
            $data['indicator'] = getDataApi($originalInput,'api/gen/locindicator?ctry=ID&deleted=0&sort=city:asc');
            $data['indicatortemp'] = getDataApi($originalInput,'api/gen/locindicator/temp?ctry=ID&deleted=0&sort=city:asc');
            $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
            $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
            $data['subid']='GEN 2.4';
            return view('pages.publications.gen.gen24',$data);
        }   
    }
    function enr41($id){
        $originalInput=Req::input();
        $user = Auth::user();
        $lwpt=[];
        $data['id'] =$id;
        $data['cod'] = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        if ($id=='66'){
            $data['aipcode'] ='ENR 4.1';
            $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=ENR 4.1');
            $sql="SELECT nav_id  FROM navaid_temp a inner join ats_temp b on b.point=a.nav_id or b.point2=a.nav_id where a.ctry='ID' and b.type not in ('X','V') GROUP BY nav_id order by a.nav_name";
            $www =DB::select(DB::raw($sql));
            $waypoints=[];$proc=[];$ats=[];
            $cod = getDataApi($originalInput,'api/eaip/menu?id='.$id);
            $waypoints = getDataApi($originalInput,'/api/navaid/temp?ctry=ID&deleted=0&sort=nav_ident:asc');
            $navaids = getDataApi($originalInput,'api/navarpt/temp');
            // dd($waypoints);
            foreach ($waypoints as $key => $wp) {
                // dd($wp->nav_id);
                $searchword=$wp->nav_id;
                $stn='';
                foreach($navaids as $key => $value) {
                    if ($value->nav_id == $searchword) {
                        $stn=$value->airport[0]->city_name.'/'.ucwords(strtolower($value->airport[0]->arpt_name));
                            // $ats=$value->ats_ident;
                    }
                }
                $found = false;  
                    foreach($www as $key => $value) {
                        if ($value->nav_id == $searchword) {
                            $found = true;
                            break;
                        }
                    }
                    
                    if ($found){
                        $cord = toWgs($wp->geom->coordinates[0],'LON');
                        $cord1 = toWgs($wp->geom->coordinates[1],'LAT');
                        $nnn['id']=$searchword;
                        if ($stn==''){
                            $nnn['station']=$wp->nav_name;
                        }else{
                            $nnn['station']=$stn;
                        }
                       
                        $nnn['type']=$wp->definition;
                        $nnn['ident']=$wp->nav_ident;
                        if ($wp->dme_elev==null){
                            $nnn['elev']='NIL';
                        }else{
                            $nnn['elev']=$wp->dme_elev;
                        }
                        
                        $nnn['status']=$wp->status_vld;
                        if ($wp->remarks==null){
                            $nnn['remarks']='NIL';
                        }else{
                            $nnn['remarks']=$wp->remarks;
                        }
                        $freq=FreqFormat($wp->freq,$wp->type,'');
                        if ($wp->type=='4'){
                            $freq = $freq.' CH-'.$wp->channel;
                        }
                        $nnn['freq']=$freq;
                        if ($wp->opr_hrs==null){
                            $nnn['hrs']='NIL';
                        }else{
                            $nnn['hrs']=$wp->opr_hrs;
                        }
                        $nnn['lat']=$cord1[0]['NONFIR'];
                        $nnn['lon']=$cord[0]['NONFIR'];
                        array_push($lwpt, $nnn);
                    }
            }
        }else{
            $sql="SELECT wpt_id,ats_ident  FROM waypoint_temp a inner join ats_temp b on b.point=a.wpt_id or b.point2=a.wpt_id where a.ctry='ID' and b.type not in ('X','V') GROUP BY wpt_id,ats_ident order by b.ats_ident";
            $ww =DB::select(DB::raw($sql));

            $sql="SELECT wpt_id  FROM waypoint_temp a inner join ats_temp b on b.point=a.wpt_id or b.point2=a.wpt_id where a.ctry='ID' and b.type not in ('X','V') GROUP BY wpt_id order by a.wpt_name";
            $www =DB::select(DB::raw($sql));
            // dd($www);
            $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=ENR 4.3');
            $data['aipcode'] ='ENR 4.3';
            $waypoints=[];$proc=[];$ats=[];
            $waypoints = getDataApi($originalInput,'/api/waypoint/temp?ctry=ID&deleted=0&sort=wpt_name:asc');
        // dd($waypoints);
            foreach ($waypoints as $key => $wp) {
                $searchword=$wp->wpt_id;
                // print_r($searchword);
                $ats='';
                    foreach($ww as $key => $value) {
                        if ($value->wpt_id == $searchword) {
                            if($ats==''){
                                $ats=$value->ats_ident;
                            }else{
                                $ats=$ats.', '.$value->ats_ident;
                            }
                        }
                    }
                $found = false;  
                    foreach($www as $key => $value) {
                        if ($value->wpt_id == $searchword) {
                            $found = true;
                            break;
                        }
                    }
                    
                    if ($found){
                        $cord = toWgs($wp->geom->coordinates[0],'LON');
                        $cord1 = toWgs($wp->geom->coordinates[1],'LAT');
                        $nnn['id']=$searchword;
                        $nnn['ident']=$wp->wpt_name;
                        $nnn['status']=$wp->status;
                        $nnn['lat']=$cord1[0]['NONFIR'];
                        $nnn['lon']=$cord[0]['NONFIR'];
                        $nnn['ats']=$ats;
                        array_push($lwpt, $nnn);
                    }
            
            }
        }
        $data['nav']=$lwpt;
        $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
        $data['subid']='ENR 4.1';
        return view('pages.publications.eaip.gen25html',$data);
    }
    function gen25($id){
        $originalInput=Req::input();
        $user = Auth::user();
        if ($id=='html'){

            $data['id'] ='33';
            $data['aipcode'] ='GEN 2.5';
            $data['chart'] = getDataApi($originalInput, 'api/allchart?aip_sub_id=GEN 2.5');
            $data['cod'] = getDataApi($originalInput,'api/eaip/menu?id=33');
            $point1['point'] = AtsTemp::selectRaw("point")
            ->where('point','like', 'NAV%')
            ->where('ctry','like', '%ID')
            ->groupby('point')
            ->get();
            $point2['point'] = AtsTemp::selectRaw("point2")
            ->where('point2','like', 'NAV%')
            ->where('ctry','like', '%ID')
            ->groupby('point2')
            ->get();
            $result = array_merge($point1,$point2);
            $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
            //array is sorted on the bases of id
            sort( $result );
            $nav=$result[0];
            $navaids = getDataApi($originalInput,'api/navarpt/temp');
            // dd($navaids->ils);
            $npush=[];
            foreach ($navaids as $key => $nav) {
                // var_dump($nav);
                if (count($nav->navaid) > 0){
                    $n=$nav->navaid[0];
                    if ($n->type === '20' || $n->type === '9' || $n->type === '11'){
                    }else{
                        $nnn['id']=$n->id;
                        $nnn['nav_id']=$n->nav_id;
                        $nnn['status']=$n->status_vld;
                        $check=AtsTemp::selectRaw("ats_ident,point,point2")
                        ->where('point','=', $n->nav_id)->orwhere('point2','=', $n->nav_id)
                        ->where('ctry','like', '%ID')
                        ->get();
                        // var_dump(count($check));
                        // $x= in_array($n->nav_id, $nav,true);
                        // $x = array_search($n->nav_id, $nav,true); // $key = 2;
                        if (count($check)>0){
                            $nnn['purpose']='AE';
                        }else{
                            $nnn['purpose']='A';
                        }
                        $nnn['ident']=$n->nav_ident;
                        $nnn['station']=$nav->airport[0]->city_name.' / '.$nav->airport[0]->arpt_name;
                        $nnn['facility']=$n->definition;
                        array_push($npush, $nnn);
                    }
                }
                if (count($nav->ils) > 0){
                    $n=$nav->ils[0];
    
                    $nnn['id']=$n->id;
                    $nnn['nav_id']=$n->ils_id;
                    $nnn['status']=$n->status;
                    $nnn['purpose']='A';
                    $nnn['ident']=$n->ils_ident;
                    $nnn['station']=$nav->airport[0]->city_name.' / '.$nav->airport[0]->arpt_name;
                    $nnn['facility']='ILS/LLZ';
                    array_push($npush, $nnn);
                }
            }

            $data['nav']=$npush;
            return view('pages.publications.eaip.gen25html',$data);
        }else{

            $originalInput=Req::input();
            $user = Auth::user();
            $point1['point'] = AtsTemp::selectRaw("point")
            ->where('point','like', 'NAV%')
            ->where('ctry','like', '%ID')
            ->groupby('point')
            ->get();
            $point2['point'] = AtsTemp::selectRaw("point2")
            ->where('point2','like', 'NAV%')
            ->where('ctry','like', '%ID')
            ->groupby('point2')
            ->get();
            // while ($point1 || $point2) {
            //     $res[] = array_shift($point1);
            //     $res[] = array_shift($point2);
            // }
            $result = array_merge($point1,$point2);
            $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
            //array is sorted on the bases of id
            sort( $result );
            // $d = array_unique($c, SORT_REGULAR);
            // var_dump($d);
            // $rrr=array_unique(array_merge($point1,$point2));//array_merge($point1,$point2); // contains: array('1','2','3','5','6','7','9');
            $data['nav']=$result;
            // foreach ($rrr as $r){
            //     var_dump($r);
            // }
            // dd($result);
            // $data['navaids'] = getDataApi($originalInput,'/api/navaid/temp/list?ctry=ID');
            // $data['ils'] = getDataApi($originalInput,'/api/ils/temp/list?ctry=ID');
            // $data['channel'] = getDataApi($originalInput,'/api/nav/channel');
            // $data['airport'] = getDataApi($originalInput,'api/airport/list?ctry=ID');
            $data['navaidstemp'] = getDataApi($originalInput,'api/navarpt/temp');
            $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
            $data['subid']='GEN 2.5';
            // $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
            // dd($data);
            // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
                return view('pages.publications.gen.gen25',$data);
        }
        // }else{
        //     return abort('403');
        // }   
    }
    

    function genfreetext($id){
        $originalInput=Req::input();
        $user = Auth::user();
        // dd($id);
        $data['gen'] = getDataApi($originalInput,'api/eaip/gen?sub_id='.$id.'&sort=seq:asc');
        if(empty($data['gen'])){
            $data['gen'] = getDataApi($originalInput,'api/eaip/gen/content?section_id='.$id);

        }
        // dd($data['gen']);
        $data['cod'] = getDataApi($originalInput,'api/cod/list/cod_eaip');
        $data['allgen'] = getDataApi($originalInput,'api/eaip/menu/all/GEN');
        $data['allenr'] = getDataApi($originalInput,'api/eaip/menu/all/ENR');
        $data['id'] = $id;
        
        //for GEN 3.2
            $data['chart'] = getDataApi($originalInput, 'api/allchart');
            // $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
            $data['codchart'] = getDataApi($originalInput, 'api/cod/chart');
        ///

        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.eaip.genhtml',$data);
        // }else{
        //     return abort('403');
        // }   
    }
    function frequency($req){
        $originalInput=Req::input();
        $user = Auth::user();
        $dt=explode("@",$req);
        // dd($dt);
        $id=$dt[0];
        $data['cod'] = getDataApi($originalInput,'api/freq/code');
        $data['freqvalue'] = getDataApi($originalInput,'api/freq/value');
        $data['callsign'] = getDataApi($originalInput,'api/freq?ctry=ID&sort=call_sign:asc');
        $data['freqid'] = $dt[0];
        $data['parent'] = $dt[1];
        $data['parentid'] = $dt[2];
        if ($id=='new'){
            $data['freqs'] = [];
            $data['freqstemp'] =[];
        }else{
            $data['freqs'] = getDataApi($originalInput,'api/freq/list?id='.$id);
            $data['freqstemp'] = getDataApi($originalInput,'api/freq/temp/list?id='.$id);
        }
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.frequency.frequency',$data);
        // }else{
        //     return abort('403');
        // }   
    }
    
    public function atslist($id)
    { 
        $originalInput=Req::input();
        $user = Auth::user();
        switch ($id) {
            case '61':
                $data['judul']='3.1 Domestic ATS Routes';
                $data['subid']='ENR 3.1';
                break;
            case '62':
                $data['judul']='3.2 International ATS Routes';
                $data['subid']='ENR 3.2';
                break;
            case '63':
                $data['judul']='3.3 Area Navigation Routes';
                $data['subid']='ENR 3.3';
                break;
            case '64':
                $data['judul']='ENR 3.4 OTHER ATS ROUTES HELICOPTER / VFR ROUTES';
                $data['subid']='ENR 3.4';
                break;
            
        }
       
        $data['atss'] = getDataApi($originalInput,'api/ats/list/temp/'.$id.'?ctry=ID');
        $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
            $dt=$data['atss'];
            if (!empty($dt)){
                foreach ($dt as $key => $value) {
                    $detail = getDataApi($originalInput,'api/ats/temp?ctry='.$value->ctry.'&sort=seq_424:asc');
                    if (!is_null($detail)){
                        $lgt=count($detail)-1;
                        $dt[$key]->point_2=$detail[$lgt]->point_2;
                    }
                }

            }
            return view('pages.publications.ats.ats', $data);
    }


    public function atsedit($info){ 
        $originalInput=Req::input();
        $user = Auth::user();
        $infox= explode('@',$info,3);
        $id=$infox[0];
    //    dd($infox);
        
        if ($id=='new'){
            $data['status'] = 'N';
            $data['ats'] =[];
            $data['atscurr'] = [];
            $data['atsprev'] =[];
            $data['atsnext'] =[];
            $data['insert'] ='';
        }else{
            $data['insert'] =$infox[2];
            $data['status'] = 'R';
            $data['ats'] = getDataApi($originalInput,'api/ats/temp?ats_id='.$id);
            $seq=$data['ats'][0]->seq_424;
            $ident=$data['ats'][0]->ats_ident;
            $prev= $ident.'&ctry=ID&seq_424='.$seq.'&prev='.$seq;
            $next= $ident.'&ctry=ID&seq_424='.$seq.'&next='.$seq;
            $data['atsprev'] = getDataApi($originalInput,'api/ats/next/temp?ats_ident='.$prev);
            $data['atsnext'] = getDataApi($originalInput,'api/ats/next/temp?ats_ident='.$next);
            // dd($data);
            $data['atscurr'] = getDataApi($originalInput,'api/ats?ats_id='.$id);
        }

       
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        $data['cod'] = getDataApi($originalInput,'api/cod/list/cod_ats_types');
        $data['level'] = getDataApi($originalInput,'api/cod/list/cod_ats_level');
        $data['wpttype'] = getDataApi($originalInput,'api/cod/list/cod_wpt_types');
       
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.ats.atsform',$data);
        // }else{
        //     return abort('403');
        // }   
        
    }
    public function navaid()
    {
    
        $originalInput=Req::input();
        $user = Auth::user();
        $point1[''] = AtsTemp::selectRaw('point as point')
        ->where('point','like', 'NAV%')
        ->where('ctry','like', '%ID')
        ->where('type','!=', 'X')
        ->groupby('point')
        ->get();
        $point2[''] = AtsTemp::selectRaw('point2 as point')
        ->where('point2','like', 'NAV%')
        ->where('ctry','like', '%ID')
        ->where('type','!=', 'X')
        ->groupby('point2')
        ->get();
        // dd($point1,$point2);
        $result = array_merge($point1,$point2);
        $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
        //array is sorted on the bases of id
        sort( $result );
        // $data['navaids']=$result;
        $data['navid']=$result[0];
        // $data['nav']=$result;
        // dd($result[0][0]->point);
        // foreach ($dt as $k=>$f) {
        //     // print($f->point);// dd($f[$k]->point2);
        //     // printf(getDataApi($originalInput,'/api/navaid/temp/list?nav_id='.$f->point));
        //     $data['navaids'] = getDataApi($originalInput,'/api/navaid/temp?nav_id='.$f);
        //     // printf($data['navaids']);
        //     // var_dump( $data['navaids']);// dd($f[$k]->point2);
        //     // dd($data['navaids']); 
            
        // }
        // $data['navaids']=$dt;
        // dd($data['navaids']);        // $data['navaids'] = NavaidTemp::where('ats_temp','ats_temp.point','like','NAV%')->get();

        $data['navaids'] = getDataApi($originalInput,'/api/navaid/temp/list?ctry=ID');
        // // $data['navaids'] = getDataApi($originalInput,'/api/navaid/list?ctry=ID');
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        // $data['ils'] = getDataApi($originalInput,'/api/ils/temp/list?ctry=ID');
        // // $data['ils'] = getDataApi($originalInput,'/api/ils/list?ctry=ID');
        $data['channel'] = getDataApi($originalInput,'/api/nav/channel');
        $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
        $data['subid']='ENR 4.1';
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.navaidwaypoint.navaids',$data);
        // }else{
        //     return abort('403');
        // }   

    }
    public function terminalwaypoint()
    {
        $originalInput=Req::input();
        $user = Auth::user();
        $data['waypoints'] = getDataApi($originalInput,'/api/waypoint/temp/list?usage_cd=2&ctry=ID&deleted=0');
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        return view('pages.publications.navaidwaypoint.terminalwaypoints',$data);
    }

    public function waypoint()
    {
    
        $originalInput=Req::input();
        $user = Auth::user();
        $point1[''] = AtsTemp::selectRaw('point as point')
        ->where('point','like', 'WPT%')
        ->where('ctry','like', '%ID')
        ->where('type','!=', 'X')
        ->groupby('point')
        ->get();
        $point2[''] = AtsTemp::selectRaw('point2 as point')
        ->where('point2','like', 'WPT%')
        ->where('ctry','like', '%ID')
        ->where('type','!=', 'X')
        ->groupby('point2')
        ->get();
        $result = array_merge($point1,$point2);
        $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
       
        sort( $result );
        $dt= $result[0];
        $data['waypoint']=$result[0];
        // dd($data);
        // $hsl=[];
        //  foreach ($dt as $k=>$f) {
        //     // print($k);// dd($f[$k]->point2);
        //     // printf(getDataApi($originalInput,'/api/navaid/temp/list?nav_id='.$f->point));
        //     $hsl[$k] = getDataApi($originalInput,'/api/waypoint/temp/list?wpt_id='.$f->point);
        //     // printf($data['navaids']);
        //     // var_dump( $data['navaids']);// dd($f[$k]->point2);
        //     // dd($data['navaids']); 
            
        // }
        // $data['waypoints'] =$hsl;
        // dd($data);
        $data['waypoints'] = getDataApi($originalInput,'/api/waypoint/temp/list?ctry=ID&deleted=0');
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=ENR');
        $data['subid']='ENR 4.3';
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.navaidwaypoint.waypoints',$data);
        // }else{
        //     return abort('403');
        // }   
    }

    public function atsdetail($id)
    {
        $originalInput=Req::input();
        $user = Auth::user();
        $data['atstemp'] = getDataApi($originalInput,'/api/ats/temp?ctry='.$id.'&sort=seq_424:asc');
        $data['atscurr'] = getDataApi($originalInput,'/api/ats?ctry='.$id.'&sort=seq_424:asc');
        if ($data['atstemp']== null){
            echo '<script>alert("No Data ats")</script>'; 

                return redirect('editats/new@new@');
        }else{
            
            return view('pages.publications..ats.atsdetail',$data);
        }
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
        // }else{
        //     return abort('403');
        // }   
    }
    public function sendemail(Request $request){
        dd($request);
        $mailto = strtolower($email);
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
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
