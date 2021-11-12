<?php
namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Volcano;
use App\Models\Api\TxVona;
use App\Models\Api\TxAshtam;
use App\Models\Api\RawdataPub;
use App\Models\Api\SourceNr;
use App\Models\Api\TxCdmLog;
use App\Models\Api\TxCdmChat;
use App\Models\Api\TxCdmUser;
use App\Models\Api\TxCdm;
use App\Models\Api\TbReff;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class VolcanoController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Volcano::query()
		// ->with([
		// 	'cdm' => function($query) {
		// 		return $query->with(['log'])
		// 		->with([
		// 			'chat' =>function($query) {
		// 			return $query->with([
		// 				'user'=> function($query) {
		// 					return $query->with(['org']);
		// 				}
		// 					]);
		// 			}
		// 		]);
		// 	}
		// ])
		// ->select('tm_volcano.*','tx_cdm.cdm_id')->leftjoin('tx_cdm','tx_cdm.va_no','tm_volcano.va_no')->orderBy('va_name')
		// ->with(['cdm'])
		->with(['ashtam'=> function($query) {
			return $query->with(['forecast']);
				}
			])
		->with(['vona']));
//		->whereNotNull('va_last_update');
		// dd()($results);
		return ApiResponse::success($results);
	}
	public function volcanoupdate(Request $request, string $id)
	{
		$volcano = Volcano::find($id);

		if (null === $volcano) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$volcano->update($request->all());

		return ApiResponse::success($volcano->fresh());
	}
	public function txcdm(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TxCdm::query()
				->leftjoin('tm_volcano','tm_volcano.va_no','tx_cdm.va_no'));
				// ->with(['volcano'])
				// ->with(['chat'])
				// ->with(['log']));
		return ApiResponse::success($results);
	}


	public function getoffsetforecast(Request $request,string $id)
	{
		
		$frq ="SELECT ST_AsText(ST_Buffer(ST_GeomFromText('$id'),1,'join=mitre mitre_limit=5.0'))";
		// echo $frq;
		$results=DB::select(DB::raw($frq));
		return ApiResponse::success($results);
	}
	public function getcenterforecast(Request $request,string $id)
	{
		
		$frq ="SELECT ST_AsText(ST_Centroid(ST_GeomFromText('$id')))";
		// echo $frq;
		$results=DB::select(DB::raw($frq));
		return ApiResponse::success($results);
	}

	public function txcdmsave(Request $request)
	{
		TxCdm::create($request->all());
	}

	public function txcdmupdate(Request $request, string $id)
	{
		$volcano = TxCdm::find($id);

		if (null === $volcano) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$volcano->update($request->all());

		return ApiResponse::success($volcano->fresh());
	}

	public function lastvona(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TxVona::query()->with(['volcano']));

		return ApiResponse::success($results);
	}

	public function savevona(Request $request)
	{
		TxVona::create($request->all());
	}
	
	
	public function saveashtam(Request $request)
	{
		TxAshtam::create($request->all());
	}


	public function lastashtam(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TxAshtam::query()
						->with(['forecast']));

		return ApiResponse::success($results);
	}
	
	public function lastrequest(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, RawdataPub::query()->join('arpt','arpt.arpt_ident','rawdata_pub.fieldid'));

		return ApiResponse::success($results);
	}
	
	public function lastpublication(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, SourceNr::query());

		return ApiResponse::success($results);
	}
	public function cdmlog(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TxCdmLog::query()
						->leftjoin('tx_cdm','tx_cdm.cdm_id','tx_cdm_log.cdm_id')
						->leftjoin('tb_reff','tb_reff.reff_code','tx_cdm_log.cdm_type')
						->where('tx_cdm_log.user_id','!=',0)
						->where('tb_reff.reff_group','0002')
						->with(['user' => function($query) {
							return $query->with(['usergroup'])->with('org');
							}
						]));
						// ->with(['chat']));

		return ApiResponse::success($results);
    }
	// public function cdmlogindex(Request $request, RequestParamHandler $rpm)
	// {
	// 	$results = $rpm->process($request, TxCdmLog::query()
	// 					->with(['user' => function($query) {
	// 						return $query->with(['usergroup'])->with('org');
	// 						}
	// 					]));
	// 					// ->with(['chat']));

	// 	return ApiResponse::success($results);
    // }
	public function cdmchat(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TxCdmChat::query()->leftjoin('tb_reff','tb_reff.reff_code','tx_cdm_chat.chat_type')
		->where('tx_cdm_chat.user_id','!=',0)
		->where('tb_reff.reff_group','0002')
		->with(['user' => function($query) {
			return $query->with(['usergroup'])->with('org');
			}
		]));
		return ApiResponse::success($results);
	}
	public function cdmuser(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TxCdmUser::query()
		->where('tx_cdm_users.user_id','!=',0)
		->with(['user' => function($query) {
			return $query->orderby('first_name','asc')->with(['usergroup'])->with('org');
			}
		]));
		// ->with(['chat']));
		return ApiResponse::success($results);
	}

	public function tblreff(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TbReff::query());

		return ApiResponse::success($results);
	}

}
