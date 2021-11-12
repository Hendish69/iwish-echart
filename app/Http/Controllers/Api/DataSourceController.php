<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\DataSource;
use App\Models\Api\SourceNr;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class DataSourceController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, DataSource::query()->where('deleted', 0));

		return ApiResponse::success($results);
    }
	public function source(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, SourceNr::query());

		return ApiResponse::success($results);
    }

	public function list(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, DataSource::query()
        ->join('cod_eaip','cod_eaip.id','datasource.sub_section')
        ->leftjoin('arpt','arpt.arpt_ident','datasource.arpt_ident')
        ->where('datasource.deleted', 0)
        ->orderby('nr'));

		return ApiResponse::success($results);
    }
	public function codeaip(Request $request, string $pid)
	{
		$menus = DB::table('cod_eaip')
        ->where('id',$pid)
		->get();

		return ApiResponse::success($menus);
	}
	
    public function section(Request $request, string $pid)
	{
        $menus = DB::table('cod_eaip')
        ->where('sub_id','like',"{$pid}%")
        ->where('level', 0)
        ->orderby('id')->get();

		return ApiResponse::success($menus);
    }
    public function subsection(Request $request, string $pid)
	{
        $menus = DB::table('cod_eaip')->where('level', 2)
                ->where('parentid', $pid)
                ->orderby('seq');
        $menus=$menus->get();

                return ApiResponse::success($menus);
    }

	public function save(Request $request)
	{
        $temp = DataSource::create($request->all());

        return ApiResponse::success($temp->id);
	}

	public function update(Request $request, string $id)
	{
		$airport = DataSource::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function remove(Request $request, string $id)
	{
		$airport = DataSource::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
	}

}
