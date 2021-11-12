<?php
namespace App\Http\Controllers\Api;
use \Illuminate\Support\Facades\Request as Req;
use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Waypoint;
use App\Models\Api\WaypointTemp;
use App\Models\Api\AtsTemp;
use App\Models\Api\RawdataPub as Raw_Pub;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class WaypointController extends Controller
{
	public function list(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Waypoint::query()->select('waypoint.*','country.country','cod_wpt_usage.definition')
                        ->join('cod_wpt_usage','cod_wpt_usage.id','=','waypoint.usage_cd')
                        ->join('country','country.ident','=','waypoint.ctry')
                        ->where('deleted', 0)
                        ->orderby('usage_cd','asc')
                        ->orderby('wpt_name','asc'));

		return ApiResponse::success($results);
    }

    public function listtemp(Request $request, RequestParamHandler $rpm)
	{
        // dd($request);
        $results = $rpm->process($request, WaypointTemp::query()->select('waypoint_temp.*','country.country','cod_wpt_usage.definition')
                        ->leftjoin('cod_wpt_usage','cod_wpt_usage.id','=','waypoint_temp.usage_cd')
                        ->join('country','country.ident','=','waypoint_temp.ctry')
                        ->where('deleted', 0)
                        ->orderby('usage_cd','asc')
                        ->orderby('wpt_name','asc'));

		return ApiResponse::success($results);
    }

    public function indextemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, WaypointTemp::query()->select('waypoint_temp.*','cod_wpt_usage.definition')
                        ->join('cod_wpt_usage','cod_wpt_usage.id','=','waypoint_temp.usage_cd'));

		return ApiResponse::success($results);
    }
    
    public function listaixm(Request $request, string $id)
	{
        $results = Waypoint::query()->select('waypoint.*','cod_wpt_types.definition')
        ->join('cod_wpt_types','cod_wpt_types.id','=','waypoint.type')
        ->whereIn('wpt_id', function($query){
            $query->select('point')
                ->from('ats')
                ->where('ctry', 'like', '%ID')
                ->where('type','!=','X');
            })
        ->orwhereIn('wpt_id', function($query){
            $query->select('point2')
                    ->from('ats')
                    ->where('ctry', 'like', '%ID')
                    ->where('type','!=','X');
            })
        ->orwhereIn('wpt_id', function($query){
            $query->select('fix_id')
                    ->from('arpt_trans_seg');
            })
        ->orderby('wpt_name','asc')
        ->get();
        // whereIn( 'email', function ( $query ) {
        //     // dummy example just to demonstrate issue
        //     $query->select( 'email' )
        //         ->from( 'users' )
        //         ->where( 'id', '>', 1 );
        // } );

        return ApiResponse::success($results);

    }
    public function getwaypointnearest(Request $request,string $id)
	{
		// dd($id);
        //->leftjoin('cod_wpt_usage','cod_wpt_usage.id','=','waypoint_temp.usage_cd')
        // ->join('country','country.ident','=','waypoint_temp.ctry')
		$frq ="SELECT a.*,st_asewkt(geom),b.country,c.definition from waypoint_temp a inner join country b on b.ident=a.ctry left join cod_wpt_usage c on c.id=a.usage_cd  where ST_contains(ST_GeomFromText('$id'),geom) and deleted=0";
		$results=DB::select(DB::raw($frq));
		// dd($results);
		return ApiResponse::success($results);
	}
    public function listaixm___(Request $request, string $id)
	{
        $frq = "SELECT *,st_asewkt(geom) as geom FROM waypoint where (wpt_id in (select point from ats where ctry like '%$id' and type <> 'X') 
        or wpt_id in (select point2 from ats where ctry like '%$id' and type <> 'X') 
        or wpt_id in (select fix_id from arpt_trans_seg)) and deleted=0 ORDER BY wpt_id";
    
    $frq= DB::select(DB::raw($frq));
    return ApiResponse::success($frq);

	}
    public function wptsearch(Request $request)
	{
        // dd($request);
        $data = WaypointTemp::whereRaw("(wpt_name LIKE '%".$request->get('q')."%' OR  desc_name LIKE '%".$request->get('q')."%') and ctry='ID' and deleted=0")->join('cod_wpt_usage','cod_wpt_usage.id','=','waypoint_temp.usage_cd')
                ->get();
        return response()->json($data);
    
    }
	public function search(Request $request)
	{
        foreach ($request->all() as $field => $find) {
            if ($field == 'wpt_name')  {
                $fld='wpt_name';
                $a=$find;
            }else if ($field == 'desc_name'){
                $fld='desc_name';
                $a=$find;
            }
        }

    $results=Waypoint::query()->select('waypoint.*','country.country')
                        ->join('country','country.ident','=','waypoint.ctry')
                        ->where($fld,'like',"{$a}%")
                        // ->where('wpt_name','like',"{$id}%")
                        ->where('deleted', 0)
                        // ->like('wpt_name',$cari)
                        ->get();

		return ApiResponse::success($results);
    }

    public function update(Request $request, string $id)
	{
		$airport = Waypoint::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
    }

    public function save(Request $request)
	{
        $ret_msg='';
        $lat = toDecimal($request->latitude);
        $lon=toDecimal($request->longitude);
        $request['geom']='POINT('.$lon.' '.$lat.')';
        // dd($request,$lat,$lon,$request->usage_cd);
		if ($request->status=='R'){
            $originalInput=Req::input();
            $user = Auth::user();
            // $data['ats'] = getDataApi($originalInput,'/api/getpoint/ats/temp/'.$request->wpt_id);
            // $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/temp/'.$request->wpt_id);
            // $data['asp'] = getDataApi($originalInput,'/api/getpoint/asp/temp/'.$request->wpt_id);
            // dd($data);

			$id=$request->id;
            $curr= Waypoint::find($id);
			$airport = WaypointTemp::find($id);
            if ($request->usage_cd !=='2'){
                    $fld=[
                        'wpt_id', 'wpt_name', 'desc_name', 'ctry', 'type', 'usage_cd', 'mag_var', 'geom','status'
                    ];
                    $fldenr=[
                        'wpt_name','geom'
                    ];
                    $fldenr43=[
                        'wpt_name', 'desc_name', 'ctry', 'type', 'usage_cd', 'geom'
                    ];

                foreach ($fldenr43 as $k => $f) {
                    $bool=true;
                    if (!empty($curr)){
                        $bool=$curr[$f] !==$request[$f];
                    }
                    if ($bool==true ){
                        $rawdata['tablename']='ENR';
                        $rawdata['fieldname']='sub_id';
                        $rawdata['fieldid']='ENR 4.3';
                        $rawdata['status_raw']= 50;
                        $rawdata['ori_change_pic']= $request->editor;
                        saveDataRaw($rawdata);
                        // $raw_dat = Raw_Pub::where('tablename', 'ENR')
                        //         ->where('fieldname', 'sub_id')
                        //         ->where('fieldid','ENR 4.3')
                        //         ->where('status_raw','<', 100)
                        //         ->first();
                        // if ($raw_dat === null) {
                        //     $raw_dat = new Raw_Pub;
                        //     $raw_dat->tablename = 'ENR';
                        //     $raw_dat->fieldname = 'sub_id';
                        //     $raw_dat->fieldid = 'ENR 4.3';
                        //     $raw_dat->status_raw = 0;
                        // }
                        // // dd($raw_dat);
                        // $raw_dat->ori_change_pic = $request->editor;
                        // $raw_dat->save();
                            
                    }
                        // var_dump($curr[$f],$airport[$f]);
                }
                    # code...
            
                foreach ($fldenr as $k => $f) {
                    $bool=true;
                    if (!empty($curr)){
                        $bool=$curr[$f] !==$request[$f];
                    }
                    if ($bool==true ){
                        $atstemp = AtsTemp::selectRaw('id,ats_id,type,ats_ident,ctry,seq_424')
                        ->where('point', $request->wpt_id)->orwhere('point2', $request->wpt_id)->get();
                        // dd($atstemp);
                        foreach ($atstemp as $ats){
                            if ($f=='geom'){
                                /// track out/in dan distance harus langsung di update
                                $originalInput = Req::input();
                                $atemp= getDataApi($originalInput, '/api/ats/temp?ats_id='.$ats->ats_id);
                                $lat1='';$lon1='';
                                if ($atemp[0]->point===$request->wpt_id){
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
                                // dd($trk);
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

                            // $raw_dat = Raw_Pub::where('tablename', 'ENR')
                            //         ->where('fieldname', 'sub_id')
                            //         ->where('fieldid',$enr)
                            //         ->where('status_raw','<', 100)
                            //         ->first();
                            // if ($raw_dat === null) {
                            //     $raw_dat = new Raw_Pub;
                            //     $raw_dat->tablename = 'ENR';
                            //     $raw_dat->fieldname = 'sub_id';
                            //     $raw_dat->fieldid = $enr;
                            //     $raw_dat->status_raw = 0;
                            // }
                            // // dd($raw_dat);
                            // $raw_dat->ori_change_pic = $request->editor;
                            // $raw_dat->save();
                            
                        }
                        // var_dump($curr[$f],$airport[$f]);
                    }
                    # code...
                }
            }
            // dd($airport,$fld);
			$airport->update($request->all());
			$ret_msg='Update Data Success';

            // $raw_dat = Raw_Pub::where('tablename', 'waypoint')
            // ->where('fieldname', 'wpt_id')
            // ->where('fieldid', $request->wpt_id)
            // ->where('status_raw','<', 100)
            // ->first();
            //     if ($raw_dat === null) {
            //         $raw_dat = new Raw_Pub;
            //         $raw_dat->tablename = 'waypoint';
            //         $raw_dat->fieldname = 'wpt_id';
            //         $raw_dat->fieldid = $request->wpt_id;
            //         $raw_dat->status_raw = 0;
            //     }
            //     // dd($raw_dat);
            //     $raw_dat->ori_change_pic = $request->editor;
            //     $raw_dat->save();
        
		}else{
            $last = WaypointTemp::latest('id')->first();
            // $request->id = $last->id + 1;
            $request->merge([
                'id' => $last->id + 1,
            ]);
			WaypointTemp::create($request->all());
		}
    // dd($request);
		//save data to raw data pub, utk request data
        if ($request->parent=='enr43'){
            return redirect('/waypoint');
        }else if ($request->parent=='terminalwaypoint'){
            return redirect('/terminalwaypoint');
        }else if ($request->parent=='listtranssegment'){
            $procc=explode('-',$request->parentid);
                return redirect('/'.$request->parent.'/'.$request->parentid.'/'. $request->atsstatus.'@procedure_'.$procc[0].'_'.$request->atsstatus);
        }else{
            if ($request->parentid==null){
                return redirect('/'.$request->parent.'/new@new@insert');
            }else{
                return redirect('/'.$request->parent.'/'.$request->parentid.'@edit@'. $request->atsstatus);
            }

        }
        // $temp = Waypoint::create($request->all());

        // return ApiResponse::success($temp->id);

    }



    public function remove(Request $request, string $id)
	{
		$airport = WaypointTemp::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
    }

}
