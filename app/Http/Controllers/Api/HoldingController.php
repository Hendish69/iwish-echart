<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Holding;
use App\Models\Api\HoldingTemp;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class HoldingController extends Controller
{
	public function listtemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, HoldingTemp::query()->select('holding_temp.*','arpt.icao','arpt.arpt_name','arpt.city_name',
			DB::raw("(CASE when (substring(fix_id,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where ctry='ID' and wpt_id=fix_id)
			else (select concat(nav_ident,' ',definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=fix_id and ctry='ID') end) as fix_point"))
			->join('arpt','arpt.arpt_ident','holding_temp.hld_type')
			->where('hld_type','!=','ENRT')
			->where('holding_temp.deleted',0)
			->orderby('fix_point')
			->with(['airport'])
			->with(['navaid'])
			->with(['waypoint']));

		return ApiResponse::success($results);
	}
	public function listcurr(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Holding::query()->select('holding.*','arpt.icao','arpt.arpt_name','arpt.city_name',
			DB::raw("(CASE when (substring(fix_id,1,3)) ='WPT' THEN (select desc_name from waypoint where ctry='ID' and wpt_id=fix_id)
			else (select concat(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=fix_id and ctry='ID') end) as fix_point"))
			->join('arpt','arpt.arpt_ident','holding.hld_type')
			->where('hld_type','!=','ENRT')
			->where('holding.deleted',0)
			->orderby('fix_point')
			->with(['airport'])
			->with(['navaid'])
			->with(['waypoint']));

		return ApiResponse::success($results);
	}

	public function index(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Holding::query()
		->with(['airport'])
		->with(['navaid'])
		->with(['waypoint']));

		return ApiResponse::success($results);
	}
	public function indextemp(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, HoldingTemp::query()
		->with(['airport'])
		->with(['navaid'])
		->with(['waypoint']));

		return ApiResponse::success($results);
	}


	public function search(Request $request,string $id)
	{

    $results=Holding::query()->select('waypoint.*','country.country')
                        ->join('country','country.ident','=','waypoint.ctry')
                        ->where('wpt_name','like',"{$id}%")
                        ->where('deleted', 0)
                        // ->like('wpt_name',$cari)
                        ->get();

		return ApiResponse::success($results);
    }

	public function save(Request $request)
	{
		$ret_msg='';
        // dd($request);
        // $request['geom']='POINT('.$lon.' '.$lat.')';
      
		
        $request['geom']='POINT('.$request->lon.' '.$request->lat.')';
        // dd($request);
		if ($request->status=='R'){
            
            
			$id=$request->id;
			$airport = HoldingTemp::find($id);
        
            // dd($airport,$fld);
			$airport->update($request->all());
			$ret_msg='Update Data Success';

		}else if ($request->status=='P'){
			$id=$request->id;
			$airport = HoldingTemp::find($id);
			$airport->poly=$request->poly;
			$airport->update();
			$ret_msg='Update Data Success';
		}else if ($request->status=='N'){
			$last = HoldingTemp::latest('id')->first();
			$request->merge([
				'id' => $last->id + 1,
			]);
			HoldingTemp::create($request->all());
				$ret_msg='Update Data Success';
        
		}else if ($request->status=='D'){
			$id=$request->id;
            $holddel = HoldingTemp::find($id);
            $holddel->delete();

         
			$ret_msg ='Insert Data Success';
		}
    
		//save data to raw data pub, utk request data
      
            if ($request->parent){
				return redirect('/'.$request->parent.'/'.$request->parentid.'@procedure_'.$request->hld_type.'_'.$request->parentid);
			}else{

				return redirect('/holding/'.$request->hld_type.'@edit');
			}
    }


    public function remove(Request $request, string $id)
	{
		$airport = Holding::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
    }

}
