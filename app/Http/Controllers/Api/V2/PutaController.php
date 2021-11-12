<?php
namespace App\Http\Controllers\Api\V2;

use app\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Airport;
use App\Models\Api\Airspace;
use App\Models\Api\Suas;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;
use Image;
use File;

class PutaController extends Controller
{
	public $path; 
    public function __construct()
    {
        //DEFINISIKAN PATH
		$this->path = public_path('upload/publication/aip/'); 
    }
	
    public function airport(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Airport::query()->select('icao','arpt_name', 'type', 'elev', 'geom')
						->where('arpt.deleted', 0)->where('ctry','ID'));
                        // ->orderby('arpt_name'));
		return ApiResponse::success($results);
	}
	public function airspace(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Airspace::query()->select('airspace_name', 'airspace_type', 'icao_acc','ats_unit', 'geom','airspace_class.upper')
                        ->join('airspace_class','airspace_class.asp_id','airspace.ats_airspace_id')
                        ->where('airspace.deleted', 0)
                        ->where('airspace.ctry', 'ID')
                        ->orderby('airspace_type','asc')
                        ->orderby('airspace_name','asc'));
		return ApiResponse::success($results);
    }
    public function suas(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Suas::query()->select('suas_ident', 'suas_sector', 'suas_name', 'suas_type', 'upper', 'lower', 'geom')
                        ->where('ctry', 'ID')
                        ->where('suas_type', '<>','T')
                        ->where('deleted', 0));
		return ApiResponse::success($results);
    }

}
