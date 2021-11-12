<?php
namespace App\Http\Controllers\Api;


use \Illuminate\Support\Facades\Request as Req;
use Auth;
use Session;
// use App\Models\Api\CodEaip;
use \Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Navaid;
use App\Models\Api\NavaidTemp;
use App\Models\Api\ArptNav;
use App\Models\Api\ArptNavTemp;
use App\Models\Api\IlsTemp;
use App\Models\Api\AtsTemp;
use App\Models\Api\Chfreq;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class NavaidController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Navaid::query()->select('navaid.*','country.country','cod_nav_types.definition')
        ->join('cod_nav_types','cod_nav_types.id','=','navaid.type')
        ->leftjoin('country','country.ident','=','navaid.ctry'));

		return ApiResponse::success($results);
    }
    public function indextemp(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, NavaidTemp::query()->select('navaid_temp.*','country.country','cod_nav_types.definition')
        ->join('cod_nav_types','cod_nav_types.id','=','navaid_temp.type')
        ->leftjoin('country','country.ident','=','navaid_temp.ctry'));
        // ->with(['transition'])
        // ->with(['ats'])
        // ->with(['ats2']));
        // dd($results);
		return ApiResponse::success($results);
    }

    public function channel(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Chfreq::query());

		return ApiResponse::success($results);
    }

    public function getarptnav(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, ArptNav::query()
        ->with(['airport'])
        ->with(['navaid'])
        ->with([
            'ils' => function($query) {
                return $query->with(['marker'])->with(['navaid'])->with(['thr']);
            }
        ])
        ->where('arpt_ident','like','ID%')
        ->orderby('seq','asc'));

		return ApiResponse::success($results);
    }

    public function getarptnavtemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, ArptNavTemp::query()
        ->with(['airport'])
        ->with(['navaid'])
        ->with([
            'ils' => function($query) {
                return $query->with(['marker'])->with(['navaid'])->with(['thr']);
            }
        ])
        ->where('arpt_ident','like','ID%')
        ->orderby('seq','asc'));

		return ApiResponse::success($results);
    }

	public function list(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Navaid::query()->select('navaid.*','country.country','cod_nav_types.definition')
                        ->join('cod_nav_types','cod_nav_types.id','=','navaid.type')
                        ->leftjoin('country','country.ident','=','navaid.ctry')
                        ->where('deleted', 0)
                        ->where('geom','!=',null)
                        ->whereNotIn('type', ['11','13'])
                        ->orderby('type','asc')
                        ->orderby('nav_ident','asc'));

		return ApiResponse::success($results);
    }

    public function listtemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, NavaidTemp::query()->select('navaid_temp.*','country.country','cod_nav_types.definition')
                        ->with(['ats2'])
                        ->join('cod_nav_types','cod_nav_types.id','=','navaid_temp.type')
                        ->leftjoin('country','country.ident','=','navaid_temp.ctry')
                        ->where('deleted', 0)
                        ->where('geom','!=',null)
                        ->whereNotIn('type', ['11','13'])
                        ->orderby('type','asc')
                        ->orderby('nav_ident','asc'));

		return ApiResponse::success($results);
    }

    public function navaidlisttemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, NavaidTemp::query()
                        ->where('deleted', 0)
                        ->whereNotIn('type', ['11','13'])
                        );

		return ApiResponse::success($results);
    }

    
    
    public function listaixm(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Navaid::query()->select('navaid.*','country.country','cod_nav_types.definition','arpt_nav.arpt_ident as airportid')
                        ->join('cod_nav_types','cod_nav_types.id','=','navaid.type')
                        ->join('country','country.ident','=','navaid.ctry')
                        ->leftjoin('arpt_nav','arpt_nav.nav_id','=','navaid.nav_id')
                        ->where('deleted', 0)
                        ->where('geom','!=',null)
                        ->whereNotIn('type', ['11','13'])
                        ->orderby('type','asc')
                        ->orderby('nav_ident','asc'));

		return ApiResponse::success($results);
    }
    
    public function listnavarpt(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, ArptNav::query()
                        ->orderby('seq','asc'));

		return ApiResponse::success($results);
	}
    public function navsearch(Request $request)
	{
        // dd($request);
        $data = NavaidTemp::whereRaw("(nav_ident LIKE '%".$request->get('q')."%') and ctry='ID' and type not in ('9','11','20') and deleted=0")->join('cod_nav_types','cod_nav_types.id','=','navaid_temp.type')
                ->get();
        return response()->json($data);
    
    }
	public function search(Request $request)
	{
        $fld1='';
        $b='';
        foreach ($request->all() as $field => $find) {
            if ($field == 'nav_ident')  {
                $fld='nav_ident';
                $a=$find;
            }else if ($field == 'nav_name'){
                $fld='nav_name';
                $a=$find;
            }else if ($field == 'ctry'){
                $fld1='ctry';
                $b=$find;
            }
        }
    $results=Navaid::query()->select('navaid.*','country.country','cod_nav_types.definition')
                        ->join('cod_nav_types','cod_nav_types.id','=','navaid.type')
                        ->join('country','country.ident','=','navaid.ctry')
                        ->where($fld,'like',"{$a}%")
                        ->where('deleted', 0);
                        if ($fld1 == 'ctry')  {
                            $results=$results->where('ctry',$b);
                        }                // ->like('wpt_name',$cari)
                        $results=$results->get();

		return ApiResponse::success($results);
    }

    public function update(Request $request, string $id)
	{
        dd($request);
		$navaid = NavaidTemp::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }

    public function updatearptnav(Request $request, string $id)
	{
		$navaid = ArptNav::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }


    public function save(Request $request)
	{
    
        $ret_msg='';
        // $date1 = date('Y-m-d');
        // $alt=0;
        $lat = toDecimal($request->navlat);
        $lon=toDecimal($request->navlon);
        // $mv=GetMagvar($lon,$lat,$date1,$alt,);
        // dd($mv);
        // dd($request);
        $request['geom']='POINT('.$lon.' '.$lat.')';
        if ($request->dmelat && $request->dmelon){
            if ($request->dmelat !== 'NIL' || $request->dmelon !== 'NIL'){
                $latdme = toDecimal($request->dmelat);
                $londme=toDecimal($request->dmelon);
                $request['dmegeom']='POINT('.$londme.' '.$latdme.')';

            }else{
                $request['dmegeom']=null;
            }

        }else{
            $request['dmegeom']=null;
        }
        // dd($request);
		if ($request->status_vld=='R'){
            $originalInput=Req::input();
            $user = Auth::user();
            // $data['ats'] = getDataApi($originalInput,'/api/getpoint/ats/temp/'.$request->nav_id);
            // $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/temp/'.$request->nav_id);
            // $data['asp'] = getDataApi($originalInput,'/api/getpoint/asp/temp/'.$request->nav_id);
            // dd($data);

			$id=$request->id;
            $curr= Navaid::find($id);
			$airport = NavaidTemp::find($id);
            $fld=[
                'ctry','type', 'nav_ident','nav_name',  'col_dme', 'freq', 'range','altitude', 'channel', 'dme_range', 'dme_elev', 'opr_hrs', 'remarks','geom','dmegeom'
            ];
            $fldenr=[
                'nav_ident','geom'
            ];
           $fldgen25=[
                'ctry','type', 'nav_ident','nav_name'
            ];
            $fldenr41=[
                'ctry','type','nav_ident','col_dme', 'freq', 'channel', 'opr_hrs', 'remarks','geom',
            ];
            foreach ($fldgen25 as $k => $f) {
                if ($curr[$f] !==$request[$f] ){
                    $rawdata['tablename']='GEN';
                    $rawdata['fieldname']='sub_id';
                    $rawdata['fieldid']='GEN 2.5';
                    $rawdata['status_raw']= 50;
                    $rawdata['ori_change_pic']= $request->editor;
                    saveDataRaw($rawdata);

                        
                }
                    // var_dump($curr[$f],$airport[$f]);
            }

            foreach ($fldenr41 as $k => $f) {
                if ($curr[$f] !==$request[$f] ){
                    $rawdata['tablename']='ENR';
                    $rawdata['fieldname']='sub_id';
                    $rawdata['fieldid']='ENR 4.1';
                    $rawdata['status_raw']= 50;
                    $rawdata['ori_change_pic']= $request->editor;
                    saveDataRaw($rawdata);

                        
                }
                    // var_dump($curr[$f],$airport[$f]);
            }
                # code...
        
            foreach ($fldenr as $k => $f) {
                if ($curr[$f] !==$request[$f] ){
                    $atstemp = AtsTemp::selectRaw('id,ats_id,type,ats_ident,ctry,seq_424')
                    ->where('point', $request->nav_id)->orwhere('point2', $request->nav_id)->get();
                    // dd($atstemp);
                    foreach ($atstemp as $ats){
                        if ($f=='geom'){
                            /// track out/in dan distance harus langsung di update
                            $originalInput = Req::input();
                            $atemp= getDataApi($originalInput, '/api/ats/temp?ats_id='.$ats->ats_id);
                            $lat1='';$lon1='';
                            if ($atemp[0]->point===$request->nav_id){
                                if(!empty($atemp[0]->wpt2)){
                                    // dd($atemp[0]->wpt2[0]->geom->coordinates[0]);
                                    $lat1=$atemp[0]->wpt2[0]->geom->coordinates[1];
                                    $lon1=$atemp[0]->wpt2[0]->geom->coordinates[0];
                                }else{
                                    $lat1=$atemp[0]->nav2[0]->geom->coordinates[1];
                                    $lon1=$atemp[0]->nav2[0]->geom->coordinates[0];
                                    // dd($atemp[0]->nav2);
                                }
                                $trk= getbearing($lat,$lon,$lat1, $lon1);
                                $geom='LINESTRING ('.$lon.' '.$lat.','.$lon1.' '.$lat1.')';
                                // var_dump($atemp,'POINT1');
                            }else{
                                if(!empty($atemp[0]->wpt1)){
                                    $lat1=$atemp[0]->wpt1[0]->geom->coordinates[1];
                                    $lon1=$atemp[0]->wpt1[0]->geom->coordinates[0];
                                }else{
                                    $lat1=$atemp[0]->nav1[0]->geom->coordinates[1];
                                    $lon1=$atemp[0]->nav1[0]->geom->coordinates[0];
                                }
                                $trk= getbearing($lat1,$lon1,$lat, $lon);
                                $geom='LINESTRING ('.$lon1.' '.$lat1.','.$lon.' '.$lat.')';
                                // var_dump($atemp,'POINT2');
                               
                            }
//                             +"TrackOutReal": 346.58151214934
//   +"TrackOutMagReal": 346.47257974996
//   +"TrackInReal": 166.58151214934
//   +"TrackInMagReal": 166.62670092957
//   +"Midlat": -1.4618361208396
//   +"Midlon": 101.88816583044
                        $atsupd = AtsTemp::where('ats_id',$ats->ats_id)->first();
                            if($atsupd->dir_424=='F'){
                                $atsupd->track_out=round($trk->TrackOutMagReal);
                                $atsupd->track_in=null;
                            }else if($atsupd->dir_424=='B'){
                                $atsupd->track_out=null;
                                $atsupd->track_in=round($trk->TrackInMagReal);
                            }else{
                                $atsupd->track_out=round($trk->TrackOutMagReal);
                                $atsupd->track_in=round($trk->TrackInMagReal);
                            }
                            $atsupd->dist=round($trk->DistanceReal,1);
                            $atsupd->geom=$geom;
                            // dd($atsupd);
                            $atsupd->save();
                            
                        }
                    
                        switch ($ats->type) {
                            case 'R':
                                $enr='ENR 3.3';
                                break;
                            case 'V':
                                $enr='ENR 3.4';
                                break;
                            default:
                                if (substr($ats->ats_ident,0,1)=='W'){
                                    $enr='ENR 3.1';
                                }else{
                                    $enr='ENR 3.2';
                                }
                                break;
                        }
                        $atemp = AtsTemp::find($ats->id);
                        $atemp->status = 'R';
                        $atemp->save();

                        $rawdata['tablename']='ENR';
                        $rawdata['fieldname']='sub_id';
                        $rawdata['fieldid']=$enr;
                        $rawdata['status_raw']= 50;
                        $rawdata['ori_change_pic']= $request->editor;
                        saveDataRaw($rawdata);

                        
                    }
                    // var_dump($curr[$f],$airport[$f]);
                }
                # code...
            }
            // dd($airport,$fld);
			$airport->update($request->all());
			$ret_msg='Update Data Success';

        
            $arpnav = ArptNavTemp::where('nav_id', $request->nav_id)->first();

            if ($arpnav){
                $arptident=$arpnav->arpt_ident;
                $arpnav->status = 'R';
                $arpnav->save();

                $rawdata['tablename']='arpt';
                $rawdata['fieldname']='arpt_ident';
                $rawdata['fieldid']=$arptident;
                $rawdata['status_raw']= 0;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

             


            }
		}else{
            $last = NavaidTemp::latest('id')->first();
            $request->id = $last->id + 1;
			NavaidTemp::create($request->all());

                $rawdata['tablename']='GEN';
                $rawdata['fieldname']='sub_id';
                $rawdata['fieldid']='GEN 2.5';
                $rawdata['status_raw']= 50;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

         
			$ret_msg ='Insert Data Success';
		}
    
		//save data to raw data pub, utk request data
        if ($request->parent=='gen25'){
            return redirect('/gen25');
        }else if ($request->parent=='enr41'){
            return redirect('/navaid');
        }else if ($request->parent=='edit219'){
            return redirect('/edit219/'.$request->parentid);
        }else{
            
            return redirect('/'.$request->parent.'/'.$request->parentid.'@edit@'.$request->atsstatus);
        }
       

    }

    public function remove(Request $request, string $id)
	{
        
        
        $navaid = NavaidTemp::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->deleted = 1;

		$navaid->save();

		return ApiResponse::success(null);
    }

    public function removearptnav(Request $request)
	{
        
        foreach ($request->all() as $field => $find) {
            if ($field == 'arpt_ident')  {
                $fld1='arpt_ident';
                $a=$find;
            }else if ($field == 'nav_id'){
                $fld2='nav_id';
                $b=$find;
            }
        }
        $navaid =ArptNav::query()->where($fld1,$a)->where($fld2,$b)->delete();
	
		return ApiResponse::success(null);
    }
    public function removearptnavtemp(Request $request)
	{
        // dd($request);
        $arptident=$request->arpt_ident;
        if ($request->nav_id==null){
            $navaid =ArptNavTemp::query()->where('arpt_ident',$arptident)->where('ils_id',$request->ils_id)->delete();
            $nav = IlsTemp::where('ils_id', $request->ils_id)->first();
            $nav->status = 'R';
            $nav->editor = $request->editor;
            $nav->save();
        }else{
            $navaid =ArptNavTemp::query()->where('arpt_ident',$arptident)->where('nav_id',$request->nav_id)->delete();
            $nav = NavaidTemp::where('nav_id', $request->nav_id)->first();
            $nav->status_vld = 'R';
            $nav->editor = $request->editor;
            $nav->save();
            
        }

        $rawdata['tablename']='arpt';
        $rawdata['fieldname']='arpt_ident';
        $rawdata['fieldid']=$arptident;
        $rawdata['status_raw']= 0;
        $rawdata['ori_change_pic']= $request->editor;
        saveDataRaw($rawdata);

      
        $rawdata['tablename']='GEN';
        $rawdata['fieldname']='sub_id';
        $rawdata['fieldid']='GEN 2.5';
        $rawdata['status_raw']= 50;
        $rawdata['ori_change_pic']= $request->editor;
        saveDataRaw($rawdata);

          
			$ret_msg ='Insert Data Success';
        return redirect('edit219/'.$arptident);
		// return ApiResponse::success(null);
    }

    public function savearptnav(Request $request)
	{
        // dd($request);
        $msg=[];
        if ($request->status=='R'){
            $id=$request->id;
			$airport = ArptNavTemp::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
        }else if ($request->status=='N'){
        // dd($request);
            if ($request->nav_id==null){
                $check= ArptNavTemp::where('ils_id',$request->ils_id)->where('arpt_ident',$request->arpt_ident)->first();
            }else{
                $check= ArptNavTemp::where('nav_id',$request->nav_id)->where('arpt_ident',$request->arpt_ident)->first();
            }
          
            if ($check){
                $msg=['alert','Data Double'];
            }else{
    
                $last = ArptNavTemp::latest('id')->first();
                $seq = ArptNavTemp::latest('seq')->where('arpt_ident',$request->arpt_ident)->first();
                // dd($seq);
                // $seqid=1;
                if ($seq){
                    $seqid = $seq->seq + 1;
                }else{
                    $seqid=1;
                }
                $request->merge([
                    'id' => $last->id + 1,
                    'seq' => $seqid
                ]);
                // dd($request);
                ArptNavTemp::create($request->all());
            }
        }
        // dd($msg);

        return back()->with($msg);;
    }

    public function AirportNavaid(Request $request,string $id)
	{

    $frq = "select b.id, a.nav_id,a.nav_ident,c.definition, a.nav_name, st_y(a.geom) As Lat,st_x(a.geom) As Lon,a.freq,a.channel,a.dme_elev as elev,a.opr_hrs as opr_hrs,a.remarks from navaid a inner join arpt_nav b On b.nav_id=a.nav_id inner join cod_nav_types c On c.id=a.type where b.arpt_ident='$id' and a.type <> '9' 
    union all select f.id, f.ils_id,f.ils_ident,'ILS' || ' RWY ' || g.rwy_ident,f.ils_name, st_y(f.geom) As Lat,st_x(f.geom) As Lon, f.freq,f.gs_freq,f.gs_elev as elev,f.opr_hrs as opr_hrs,f.remarks From arpt_ils f  inner join arpt_rwy_physical g On g.rwy_key=f.rwy_id where f.arpt_ident='$id' and f.deleted=0";

        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }

    



}
