<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\EaipApronTwy;
use App\Models\Api\EaipArptGate;
use App\Models\Api\EaipApronTwyTemp;
use App\Models\Api\EaipArptGateTemp;
use App\Models\Api\EaipPushback;
use App\Models\Api\EaipPushbackTemp;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class EaipApronTwyController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, EaipApronTwy::query());

		return ApiResponse::success($results);
    }
	
	public function ApronTwylist(Request $request, RequestParamHandler $rpm)
	{
		$builder = EaipApronTwy::query()
			->where('deleted',0)
			->orderBy('sequence','asc');
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
    }
	public function ApronTwylisttemp(Request $request, RequestParamHandler $rpm)
	{
		$builder = EaipApronTwyTemp::query()
		
			->where('deleted',0)
			->orderBy('sequence','asc');
		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
    //     // $freq = $rpm->process($request, EaipApronTwy::query()
    //     // ->orderBy('sequence','asc'));
    // return ApiResponse::success($freq);
    }

    public function ParkingStandlist(Request $request, RequestParamHandler $rpm)
	{
		$freq = $rpm->process($request, EaipArptGate::query()
		->with(['apron'])
		// ->select('eaip_arpt_gate.*','eaip_apron_twy.name', 'eaip_apron_twy.dimension', 'eaip_apron_twy.surface','eaip_apron_twy.strength')
		// ->leftjoin('eaip_apron_twy','eaip_apron_twy.id','eaip_arpt_gate.apron_id')
		->where('deleted',0)
        ->orderBy('sequence','asc'));
    return ApiResponse::success($freq);
	}
	public function ParkingStandlisttemp(Request $request, RequestParamHandler $rpm)
	{
		$freq = $rpm->process($request, EaipArptGateTemp::query()
		->with(['apron'])
		// ->select('eaip_arpt_gate_temp.*','eaip_apron_twy_temp.name', 'eaip_apron_twy_temp.dimension', 'eaip_apron_twy_temp.surface','eaip_apron_twy_temp.strength')
		// ->leftjoin('eaip_apron_twy_temp','eaip_apron_twy_temp.id','eaip_arpt_gate_temp.apron_id')
		->where('deleted',0)
        ->orderBy('sequence','asc'));
		return ApiResponse::success($freq);
	}

	public function Pushbacklist(Request $request, RequestParamHandler $rpm)
	{
		$freq = $rpm->process($request, EaipPushback::query()
		->where('deleted',0)
        ->orderBy('sequence','asc'));

    return ApiResponse::success($freq);
	}

	public function Pushbacklisttemp(Request $request, RequestParamHandler $rpm)
	{
		$freq = $rpm->process($request, EaipPushbackTemp::query()
		->where('deleted',0)
        ->orderBy('sequence','asc'));

    return ApiResponse::success($freq);
	}

	public function ParkingPushbacklist(Request $request, RequestParamHandler $rpm)
	{
		$freq = $rpm->process($request, EaipParkingPushback::query()
        ->orderBy('sequence','asc'));
    return ApiResponse::success($freq);
	}
	
    public function updateapron(Request $request, string $id)
	{
		$navaid = EaipApronTwy::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }

    public function saveapron(Request $request)
	{
		$temp = EaipApronTwy::create($request->all());
		return ApiResponse::success($temp->id);

    }

    public function removeapron(Request $request, string $id)
	{
        // var_dump($request);
		$tab=$request->tab;
		$obs = EaipApronTwyTemp::find($id);

		if (null === $obs) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		// $obs->deleted = 1;
		$obs->delete();
		// $obs->save();

		// return ApiResponse::success(null);
        // $navaid = EaipApronTwyTemp::find($id);

		// if (null === $navaid) {
		// 	return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		// }
		// $navaid->deleted=1;

		// $navaid->save();
		return back()->withInput(['tab'=>$tab]);
		// return ApiResponse::success($navaid->fresh());
	}
	
	public function updateparkingstand(Request $request, string $id)
	{
		$navaid = EaipArptGate::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }

    public function saveparkingstand(Request $request)
	{
		$temp = EaipArptGate::create($request->all());
		return ApiResponse::success($temp->id);

    }

    public function removeparkingstand(Request $request, string $id)
	{
		$tab=$request->tab;
		$obs = EaipArptGateTemp::find($id);

		if (null === $obs) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$obs->delete();

		// $obs->save();

		// return ApiResponse::success(null);
        // $navaid = EaipApronTwyTemp::find($id);

		// if (null === $navaid) {
		// 	return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		// }
		// $navaid->deleted=1;

		// $navaid->save();
		return back()->withInput(['tab'=>$tab]);
        // $ats = EaipArptGate::find($id);

		// if (null === $ats) {
		// 	return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		// }

		// $ats->delete();

		// return ApiResponse::success($ats->fresh());

	}
	
	public function updatepushback(Request $request, string $id)
	{
		$navaid = EaipPushback::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }

    public function savepushback(Request $request)
	{
		$temp = EaipPushback::create($request->all());
		return ApiResponse::success($temp->id);

    }

    public function removepushback(Request $request, string $id)
	{
        $ats = EaipPushback::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());

	}
	
	public function updateparkingpushback(Request $request, string $id)
	{
		$navaid = EaipParkingPushback::find($id);

		if (null === $navaid) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$navaid->update($request->all());

		return ApiResponse::success($navaid->fresh());
    }

    public function saveparkingpushback(Request $request)
	{
		$temp = EaipParkingPushback::create($request->all());
		return ApiResponse::success($temp->id);

    }

    public function removeparkingpushback(Request $request, string $id)
	{
        $ats = EaipParkingPushback::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());

    }

   

}
