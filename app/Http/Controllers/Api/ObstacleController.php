<?php
namespace App\Http\Controllers\Api;
use \Illuminate\Support\Facades\Request as Req;
use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Obstacle;
use App\Models\Api\ObstacleTemp;
use App\Models\Api\RawdataPub as Raw_Pub;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class ObstacleController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Obstacle::query()->select('arpt_obstacle.*','cod_obs_type.definition')
                        ->leftjoin('cod_obs_type','cod_obs_type.id','arpt_obstacle.obs_type'));

		return ApiResponse::success($results);
    }

	public function indextemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, ObstacleTemp::query()->select('arpt_obstacle_temp.*','cod_obs_type.definition')
                        ->leftjoin('cod_obs_type','cod_obs_type.id','arpt_obstacle_temp.obs_type'));

		return ApiResponse::success($results);
    }
	public function getobstacleaoc(Request $request,string $id)
	{
		
		$frq ="SELECT id from arpt_obstacle_temp where ST_contains(ST_GeomFromText('$id'),geom) and deleted=0";
		
		$results=DB::select(DB::raw($frq));
		return ApiResponse::success($results);
	}

    public function update(Request $request, string $id)
	{
		$navaid = Obstacle::find($id);

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
        $lat = toDecimal($request->latitude);
        $lon=toDecimal($request->longitude);
        $request['geom']='POINT('.$lon.' '.$lat.')';
        // dd($request);
		if ($request->status=='R'){
            $originalInput=Req::input();
            $user = Auth::user();
			$id=$request->id;
			$airport = ObstacleTemp::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{
            $last = ObstacleTemp::latest('id')->first();
            $request->id = $last->id + 1;
			$request->merge([
				'id' => $last->id + 1,
			]);
			// dd( $request);
			ObstacleTemp::create($request->all());
		}
			$rawdata['tablename']='arpt';
			$rawdata['fieldname']='arpt_ident';
			$rawdata['fieldid']=$request->arpt_ident;
			$rawdata['status_raw']= 0;
			$rawdata['ori_change_pic']= $request->editor;
			saveDataRaw($rawdata);

		// $raw_dat = Raw_Pub::where('tablename', 'arpt')
		// ->where('fieldname', 'arpt_ident')
		// ->where('fieldid', $request->arpt_ident)
		// ->where('status_raw','<=',70)
		// // ->where('status_raw','<', 100)
		// ->first();
		// 	if ($raw_dat === null) {
		// 		$raw_dat = new Raw_Pub;
		// 		$raw_dat->tablename = 'arpt';
		// 		$raw_dat->fieldname = 'arpt_ident';
		// 		$raw_dat->fieldid = $request->arpt_ident;
		// 		$raw_dat->status_raw = 0;
		// 	}
		// 	// dd($raw_dat);
		// 	$raw_dat->ori_change_pic = $request->editor;
		// 	$raw_dat->save();
		//save data to raw data pub, utk request data
 
            return redirect('/edit210/'.$request->arpt_ident);
        

    }

    public function remove(Request $request, string $id)
	{
        
        
        $obs = ObstacleTemp::find($id);

		if (null === $obs) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$obs->deleted = 1;

		$obs->save();

		return ApiResponse::success(null);
	}
	

}
