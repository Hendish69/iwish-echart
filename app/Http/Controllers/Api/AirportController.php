<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Airport;
use App\Models\Api\ArptAuth;
use App\Models\Api\AirportAdc;
use App\Models\Api\AirportTaTl;
use App\Models\Api\PdfFile;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;
use Image;
use File;

class AirportController extends Controller
{
	public $path; 
    public function __construct()
    {
        //DEFINISIKAN PATH
		$this->path = public_path('upload/publication/aip/'); 
    }
	
    public function index(Request $request, RequestParamHandler $rpm)
	{
		
		$builder = Airport::query()
		->with(['country'])
		->with([
			'runways' => function($query) {
				return $query->select('arpt_rwy.*','cod_rwy_surface.definition')->leftjoin('cod_rwy_surface','cod_rwy_surface.id','arpt_rwy.surface')->where('deleted',0)->with([
					'physicals' => function($query) {
						return $query->with('lighting');
					}
				]);
			}
		])
		->with([
			'runwaystemp' => function($query) {
				return $query->select('arpt_rwy_temp.*','cod_rwy_surface.definition')->leftjoin('cod_rwy_surface','cod_rwy_surface.id','arpt_rwy_temp.surface')->where('deleted',0)->with([
					'physicals' => function($query) {
						return $query->with('lighting');
					}
				]);
			}
		])
		->with(['auth'])->where('geom','!=',null)
		->with(['tatl']);

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
	}

	
	public function getairportorecast(Request $request,string $id)
	{
		
		$frq ="SELECT arpt_ident from arpt where ST_contains(ST_GeomFromText('$id'),geom) and deleted=0";
		
		$results=DB::select(DB::raw($frq));
		return ApiResponse::success($results);
	}

	

	public function auth(Request $request, RequestParamHandler $rpm)
	{
		$builder = ArptAuth::query()
			->with([
				'airport' => function($query) {
					return $query->where('deleted',0);
					}
			])
			->with(['users']);

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
    }

	public function airportchart(Request $request, RequestParamHandler $rpm)
	{
		$builder = PdfFile::query();
		
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }

	public function allchart(Request $request, RequestParamHandler $rpm)
	{
		$builder = PdfFile::query()->select('pdf_file.*','pdf_file.seq as urut','bm_chart.scale as bm_scale','arpt.icao','arpt.arpt_name','arpt.city_name','cod_chart_types.*')
					->leftjoin('arpt','arpt.arpt_ident','pdf_file.arpt_ident')
					->leftjoin('propchart','propchart.chart_id','pdf_file.chart_id')
					->leftjoin('bm_chart','bm_chart.chart_id','propchart.bm_id')
					->leftjoin('cod_chart_types','cod_chart_types.code','pdf_file.chart_code')
					->where('pdf_file.deleted',0)
					->orderby('arpt.city_name','asc')
					->orderby('cod_chart_types.seq','asc');
		
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }

	public function airportchartsave(Request $request)
	{
		
		$ret_msg='';
		
		if ($request->file('files') !== null){
            $files = [];
            
            foreach ($request->file('files') as $file) {
                if ($file->isValid()) {
                    $fileName = time().'_'.$file->getClientOriginalName();
                    $fileName1 = $file->getClientOriginalName();
                    $file->move($this->path,$fileName); 
                    // $files[] = [
                    //     'filename' => $fileName,
                    //     'path_file' => 'upload/publication/',
                    //     'cod_filename' => $fileName1,
                    // ]; 
                }
            }
			$request->merge([
				'path_file' => $fileName,
				'chart_name' => $fileName1,
			]);
        }
		// dd($request);
		//'upload/publication/' +
		// $request->request[4]=$fileName;
		// $request->request[14]=$fileName1;
		
		if ($request->status=='R'){
			$id=$request->arptchart_id;
			$airport = PdfFile::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{	
			PdfFile::create($request->all());
			$ret_msg ='Insert Data Success';
		}
		
		
		return back()->with(['msg'=>$ret_msg]);
    }
	public function airportchartremove(Request $request, string $id)
	{
		$airport = PdfFile::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return back();
	}

	public function search(Request $request)
	{
        // dd($request);
        $data = Airport::whereRaw("(icao LIKE '%".$request->get('q')."%' OR arpt_name LIKE '%".$request->get('q')."%' or city_name LIKE '%".$request->get('q')."%' ) and ctry='ID' and deleted=0")
                ->get();
        return response()->json($data);
    
    }

	public function list(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Airport::query()->select('arpt.*','country.country','arpt_tl_ta.tl','arpt_tl_ta.ta','arpt_auth.name', 'arpt_auth.class', 'arpt_auth.otban', 'arpt_auth.pia', 'arpt_auth.address', 'arpt_auth.remarks', 'arpt_auth.pia_address', 'arpt_auth.pic_otban', 'arpt_auth.pic_pia')
						->leftjoin('arpt_tl_ta','arpt_tl_ta.arpt_ident','arpt.arpt_ident')
						->leftjoin('arpt_auth','arpt_auth.id','arpt.auth')
						->join('country','country.ident','arpt.ctry')
						->where('arpt.deleted', 0));
                        // ->orderby('arpt_name'));

		return ApiResponse::success($results);
	}

	public function listputa_x(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Airport::query()->select('icao','arpt_name', 'type', 'elev', 'geom')
						->where('arpt.deleted', 0)->where('ctry','ID'));
                        // ->orderby('arpt_name'));

		return ApiResponse::success($results);
	}
	


	public function save(Request $request)
	{
		// dd($request);
	

			Airport::create($request->all());
		
		return redirect()->back()->with('alert','Data Save');
	}

	public function update(Request $request, string $id)
	{
		$airport = Airport::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function remove(Request $request, string $id)
	{
		$airport = Airport::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
	}

	public function saveauth(Request $request)
	{
		ArptAuth::create($request->all());
	}

	public function updateauth(Request $request, string $id)
	{
		$airport = ArptAuth::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function removeauth(Request $request, string $id)
	{
		$ats = ArptAuth::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
	}

	public function listadc(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, AirportAdc::query()
						->where('deleted', 0)
                        ->orderby('layer'));

		return ApiResponse::success($results);
	}

	public function listadcaixm(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, AirportAdc::query()
		->wherein('layer',['apron','twy','building','rwy','taxi','taxilane'])
						->where('deleted', 0)
                        ->orderby('layer'));

		return ApiResponse::success($results);
	}

	public function airportsearch(Request $request,string $id)
	{
    $results=Airport::query()->select('arpt.*','country.country')
                        ->join('country','country.ident','=','arpt.ctry')
                        ->where('deleted', 0)
                        ->where('geom','!=',null)
                        ->where('icao','like',"{$id}%")
                        ->orwhere('arpt_name','like',"{$id}%")
                        // ->like('wpt_name',$cari)
                        ->get();

		return ApiResponse::success($results);
    }

	public function savetlta(Request $request)
	{
		AirportTaTl::create($request->all());
	}

	public function updatetlta(Request $request, string $id)
	{
		$airport = AirportTaTl::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	

}
