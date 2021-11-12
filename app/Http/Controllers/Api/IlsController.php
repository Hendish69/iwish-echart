<?php
namespace App\Http\Controllers\Api;

use \Illuminate\Support\Facades\Request as Req;
use Auth;
use Session;
// use App\Models\Api\CodEaip;
use \Illuminate\Support\Facades\Route;

use App\Models\Api\Navaid;
use App\Models\Api\NavaidTemp;
use App\Models\Api\ArptNav;
use App\Models\Api\ArptNavTemp;
use App\Models\Api\RawdataPub as Raw_Pub;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Ils;
use App\Models\Api\IlsMarker;
use App\Models\Api\IlsTemp;
use App\Models\Api\IlsMarkerTemp;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class IlsController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Ils::query());

		return ApiResponse::success($results);
    }
	public function list(Request $request, RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, Ils::query()->select('arpt_ils.*','arpt.icao','arpt.ctry','arpt.arpt_name','arpt_rwy_physical.rwy_ident')
                        ->join('arpt','arpt.arpt_ident','arpt_ils.arpt_ident')
                        ->leftjoin('arpt_rwy_physical','arpt_rwy_physical.rwy_key','arpt_ils.rwy_id')
                        ->where('arpt_ils.deleted', 0)
                        ->where('arpt_ils.geom','!=',null)
                        ->orderby('ils_ident','asc'));
           

		return ApiResponse::success($results);
    }
    public function listtemp(Request $request, RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, IlsTemp::query()->select('arpt_ils_temp.*','arpt.icao','arpt.ctry','arpt.arpt_name','arpt_rwy_physical_temp.rwy_ident')
                        ->join('arpt','arpt.arpt_ident','arpt_ils_temp.arpt_ident')
                        ->leftjoin('arpt_rwy_physical_temp','arpt_rwy_physical_temp.rwy_key','arpt_ils_temp.rwy_id')
                        ->where('arpt_ils_temp.deleted', 0)
                        ->where('arpt_ils_temp.geom','!=',null)
                        ->orderby('ils_ident','asc'));
           

		return ApiResponse::success($results);
    }

    public function listaixm(Request $request)
	{
      
        $fld1='';
        $b='';
        foreach ($request->all() as $field => $find) {
            if ($field == 'ctry')  {
                $fld='ctry';
                $a=$find;
            }
        }
        $results = Ils::query()->select('arpt_ils.*','arpt.icao','arpt.ctry','arpt.arpt_name','arpt_rwy_physical.rwy_ident','navaid.nav_ident','navaid.nav_name','navaid.type','navaid.geom as dmegeom','navaid.dme_elev','navaid.channel')
        ->join('arpt','arpt.arpt_ident','arpt_ils.arpt_ident')
        ->leftjoin('arpt_rwy_physical','arpt_rwy_physical.rwy_key','arpt_ils.rwy_id')
        ->leftjoin('navaid','navaid.nav_id','arpt_ils.nav_id')
        ->where('arpt_ils.deleted', 0)
        ->where('arpt_ils.geom','!=',null)
        ->where('arpt.ctry','=',$a)
        ->orderby('ils_ident','asc');
           
        $results=$results->get();
		return ApiResponse::success($results);
    }
    
    public function getils(Request $request, RequestParamHandler $rpm)
	{

      
        $results = $rpm->process($request, Ils::query()
        ->with([
			'airport' => function($query) {
				return $query->with(['runways' => function($query) { 
                    return $query->leftjoin('cod_rwy_surface','cod_rwy_surface.id','arpt_rwy.surface')->where('deleted',0)->with(['physicals']);
                }
                ]);
                }
            ])
        ->with(['navaid'])
        ->with(['marker'  => function($query) {
            return $query->with(['navaid']);
            }
        ]));

		return ApiResponse::success($results);
    }
    public function getilstemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, IlsTemp::query()
        ->with([
			'airport' => function($query) {
				return $query->with(['runwaystemp' => function($query) { 
                    return $query->leftjoin('cod_rwy_surface','cod_rwy_surface.id','arpt_rwy_temp.surface')->where('deleted',0)->with(['physicals']);
                }
                ]);
                }
            ])
        ->with(['navaid'])
        ->with(['marker'  => function($query) {
            return $query->with(['navaid']);
            }
        ]));

		return ApiResponse::success($results);
    }
    public function getmarker(Request $request, RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, IlsMarker::query()->select('arpt_marker.*','navaid.id as idd','navaid.nav_ident','navaid.nav_name','navaid.type','navaid.freq as locfreq','navaid.geom as locgeom')
                        ->leftjoin('navaid','navaid.nav_id','arpt_marker.loc_id')
                        ->where('arpt_marker.deleted', 0)
                        ->orderby('mrkr_type','asc'));

		return ApiResponse::success($results);
	}

    public function getmarkertemp(Request $request, RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, IlsMarkerTemp::query()->select('arpt_marker_temp.*','navaid_temp.id as idd','navaid_temp.nav_ident','navaid_temp.nav_name','navaid_temp.type','navaid_temp.freq as locfreq','navaid_temp.geom as locgeom')
                        ->leftjoin('navaid_temp','navaid_temp.nav_id','arpt_marker_temp.loc_id')
                        ->where('arpt_marker_temp.deleted', 0)
                        ->orderby('mrkr_type','asc'));

		return ApiResponse::success($results);
	}


    public function update(Request $request, string $id)
	{
		$navaid = Ils::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }

    public function save(Request $request)
	{
        // dd($request);
        $ret_msg='';
        $lat = toDecimal($request->lat);
        $lon=toDecimal($request->lon);
        $request['geom']='POINT('.$lon.' '.$lat.')';
        $gslat = toDecimal($request->gs_lat);
        $gslon=toDecimal($request->gs_lon);
        $request['gs_geom']='POINT('.$gslon.' '.$gslat.')';
        $arptident=$request->arpt_ident;
        $ilsid=$request->ils_id;
        // dd($request);
		if ($request->status=='R'){
            $originalInput=Req::input();
            $user = Auth::user();

            // update ILS
			$id=$request->id;
            $curr= Ils::find($id);
			$airport = IlsTemp::find($id);
            $airport->update($request->all());
			// update DME
            if ($curr){
                $check=($curr['ils_ident'] !==$request['ils_ident'] );
            }else{
                $check=true;
            }
                if ($check){
                    $rawdata['tablename']='GEN';
                    $rawdata['fieldname']='sub_id';
                    $rawdata['fieldid']='GEN 2.5';
                    $rawdata['status_raw']= 50;
                    $rawdata['ori_change_pic']= $request->editor;
                    saveDataRaw($rawdata);

                    // $raw_dat = Raw_Pub::where('tablename', 'GEN')
                    //         ->where('fieldname', 'sub_id')
                    //         ->where('fieldid','GEN 2.5')
                    //         ->where('status_raw','<', 100)
                    //         ->first();
                    // if ($raw_dat === null) {
                    //     $raw_dat = new Raw_Pub;
                    //     $raw_dat->tablename = 'GEN';
                    //     $raw_dat->fieldname = 'sub_id';
                    //     $raw_dat->fieldid = 'GEN 2.5';
                    //     $raw_dat->status_raw = 0;
                    // }
                    // // dd($raw_dat);
                    // $raw_dat->ori_change_pic = $request->editor;
                    // $raw_dat->save();
                        
                }
                    // var_dump($curr[$f],$airport[$f]);
            
        
            
		}else{
            $last = IlsTemp::latest('id')->first();
            // $request->id = $last->id + 1;
            $request->merge([
                'id' => $last->id + 1,
                'status' => 'N'
            ]);
			IlsTemp::create($request->all());

            $rawdata['tablename']='GEN';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']='GEN 2.5';
            $rawdata['status_raw']= 50;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);

            // $raw_dat = Raw_Pub::where('tablename', 'GEN')
            // ->where('fieldname', 'sub_id')
            // ->where('fieldid','GEN 2.5')
            // ->where('status_raw','<', 100)
            // ->first();
            // if ($raw_dat === null) {
            //     $raw_dat = new Raw_Pub;
            //     $raw_dat->tablename = 'GEN';
            //     $raw_dat->fieldname = 'sub_id';
            //     $raw_dat->fieldid = 'GEN 2.5';
            //     $raw_dat->status_raw = 0;
            // }
            // // dd($raw_dat);
            // $raw_dat->ori_change_pic = $request->editor;
            // $raw_dat->save();
			$ret_msg ='Insert Data Success';
		}

        if ($request->dme_avail=='Y'){
            if ($request->dme_lat !== 'NIL' || $request->dme_lon !== 'NIL'){
                $dlat = toDecimal($request->dme_lat);
                $dlon=toDecimal($request->dme_lon);
                $lastd= NavaidTemp::latest('id')->first();
                $dme = NavaidTemp::where('nav_id', $request->nav_id)
                                    ->first();
                    // $dme->status = 'R';
                    if ($dme === null) {
                        $dme = new NavaidTemp;
                        $dme->id = $lastd->id + 1;
                        $dme->nav_id ='NAV_'.$request->ils_ident.'_9_ID_1';
                        $dme->ctry = 'ID';
                        $dme->type = '9';
                        $dme->status_vld = 'N';
                        
                    }
                        $dme->nav_ident = $request->ils_ident;
                        $dme->nav_name = $request->ils_name;
                        $dme->freq = $request->channel;
                        $dme->dme_range = $request->dme_range;
                        $dme->dme_elev = $request->dme_elev;
                        $dme->opr_hrs = $request->opr_hrs;
                        $dme->geom = 'POINT('.$dlon.' '.$dlat.')';
                        $dme->status_vld = 'R';
                        $dme->editor = $request->editor;
                        $dme->save();
                $ilsy = IlsTemp::where('ils_id', $request->ils_id)->first();;
                $ilsy->nav_id = $dme->nav_id;
                $ilsy->save();

            }

        }

                $lastt = ArptNavTemp::latest('id')->first();
                $arpnav = ArptNavTemp::where('ils_id', $ilsid)->first();
            if ($arpnav === null) {
                $arpnav = new ArptNavTemp;
                $arpnav->id = $lastt->id + 1;
                $arpnav->arpt_ident = $arptident;
                $arpnav->ils_id = $ilsid;
                $arpnav->status = 'N';
            }else{
                $arpnav->status = 'R';
            }
                $arpnav->save();
            
                $rawdata['tablename']='arpt';
                $rawdata['fieldname']='arpt_ident';
                $rawdata['fieldid']=$arptident;
                $rawdata['status_raw']= 0;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

            // $raw_dat = Raw_Pub::where('tablename', 'arpt')
            // ->where('fieldname', 'arpt_ident')
            // ->where('fieldid', $arptident)
            // ->where('status_raw','<=',70)
            // // ->where('status_raw','<', 100)
            // ->first();
            //     if ($raw_dat === null) {
            //     $raw_dat = new Raw_Pub;
            //     $raw_dat->tablename = 'arpt';
            //     $raw_dat->fieldname = 'arpt_ident';
            //     $raw_dat->fieldid = $arptident;
            //     $raw_dat->status_raw = 0;
            //     }
            //     // dd($raw_dat);
            //     $raw_dat->ori_change_pic = $request->editor;
            //     $raw_dat->save();
		//save data to raw data pub, utk request data
        if ($request->parent=='gen25'){
            return redirect('/gen25');
        }else{
            return redirect('/edit219/'.$arptident);
        }


    }

    public function remove(Request $request, string $id)
	{
        $navaid = Ils::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->deleted = 1;

		$navaid->save();

		return ApiResponse::success(null);
    }

    public function updatemarker(Request $request, string $id)
	{
		$navaid = IlsMarker::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }

    public function savemarker(Request $request)
	{
        // dd( $request);
        $originalInput=Req::input();
        $user = Auth::user();
        $ret_msg='';
        $lat = toDecimal($request->mrkr_lat);
        $lon=toDecimal($request->mrkr_lon);
        $request['geom']='POINT('.$lon.' '.$lat.')';
        $arptident=$request->arpt_ident;
        $ilsid=$request->ils_id;
        // dd($request);
		if ($request->status=='R'){
            // update ILS
			$id=$request->id;
			$airport = IlsMarkerTemp::find($id);
            //  dd($airport);
            $airport->update($request->all());
            
		}else if ($request->status=='N'){
            $last = IlsMarkerTemp::latest('id')->first();
            // $request->id = $last->id + 1;
            $request->merge([
                'id' => $last->id + 1,
            ]);
			IlsMarkerTemp::create($request->all());
			$ret_msg ='Insert Data Success';
        }else if ($request->status=='D'){
            $airport = IlsMarkerTemp::find($id);
            $airport->delete();
			$ret_msg ='Insert Data Success';
		}
      
            return redirect('/ils/'.$request->ils_id.'@edit@edit219@'.$arptident);
        
    }
    public function ilssearch(Request $request)
	{
        // dd($request);
        $data = IlsTemp::whereRaw("(ils_ident LIKE '%".$request->get('q')."%' or ils_name LIKE '%".$request->get('q')."%') and deleted=0")->get();
        return response()->json($data);
    
    }
    public function markersearch(Request $request)
	{
        // dd($request);
        $data = IlsMarkerTemp::whereRaw("(ils_id LIKE '%".$request->get('q')."%' or mrkr_type LIKE '%".$request->get('q')."%') and deleted=0")
        ->get();
        return response()->json($data);
    
    }
    public function removemarker(Request $request, string $id)
	{
        $navaid = IlsMarkerTemp::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		// $navaid->deleted = 1;

		$navaid->delete();
        return back();
		// return ApiResponse::success(null);
    }

}
