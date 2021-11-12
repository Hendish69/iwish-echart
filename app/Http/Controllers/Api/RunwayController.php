<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Runway;
use App\Models\Api\RwyPhysical;
use App\Models\Api\Rwylgt;
use App\Models\Api\RunwayTemp;
use App\Models\Api\RwyPhysicalTemp;
use App\Models\Api\RwylgtTemp;
use App\Models\Api\RawdataPub as Raw_Pub;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class RunwayController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Runway::query()->select('arpt_rwy.*','cod_rwy_surface.definition')
		->leftjoin('cod_rwy_surface','cod_rwy_surface.id','arpt_rwy.surface')
		->with([
				'physicals' => function($query){
						return $query->orderby('rwy_ident')->with('lighting');
					}
				])
			);


		return ApiResponse::success($results);
    }

	public function indextemp(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, RunwayTemp::query()->select('arpt_rwy_temp.*','cod_rwy_surface.definition')
		->leftjoin('cod_rwy_surface','cod_rwy_surface.id','arpt_rwy_temp.surface')
		->with([
				'physicals' => function($query){
						return $query->orderby('rwy_ident')->with('lighting');
					}
				])
			);


		return ApiResponse::success($results);
    }

	public function list(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Runway::query()
						->join('arpt_rwy_physical','arpt_rwy_physical.rwy_id','arpt_rwy.rwy_id')
						->where('arpt_rwy.deleted', 0)
                        ->orderby('arpt_rwy.rwy_ident'));

		return ApiResponse::success($results);
	}

	public function save(Request $request)
	{
	
		$temp = Runway::create($request->all());

        return ApiResponse::success($temp->id);


	}
	public function savetemp(Request $request)
	{
	
		$rwyfld=['id','rwy_id', 'arpt_ident', 'rwy_ident', 'length', 'width', 'pcn', 'surface','strip_l', 'strip_w','thr_low', 'thr_high'];
		$thrffld=['rwy_key', 'rwy_id', 'rwy_ident', 'lat','lon', 'mag_brg', 'resa_l', 'resa_w', 'thr_elev', 'tdz_elev', 'disp_thr_length', 'swy_length', 'cwy_length','slope', 'disp_thr_elev', 'disp_lat','disp_lon','geom','tora', 'toda', 'asda', 'lda', 'remarks','true_brg', 'cwy_width', 'swy_width', 'slope1','geoid'];
		$th='high_'.$thrffld[0];
		// dd($request->$th);
		$ret_msg='';$rwy;
		// dd($request);


		$arptident=$request->arpt_ident;
		if ($request->status=='R'){
			if ($request->mainsave=='YES'){
				$rwyfldu=['rwy_ident', 'length', 'width', 'pcn', 'surface','strip_l', 'strip_w','thr_low', 'thr_high','status','editor'];
				for ($i=0; $i < count($rwyfldu) ; $i++) {
					$val=$rwyfldu[$i];
					$rwy[$rwyfldu[$i]]=$request->$val;
				}
				// dd($rwy);
				$id=$request->id;
				$airport = RunwayTemp::find($id);
				$airport->update($rwy);
				$ret_msg='Update Data Success';

			}else{
				$airport = RunwayTemp::find($request->id);
				$airport->status='R';
				$airport->update();
			}
			$thrffldup=['rwy_ident', 'mag_brg', 'resa_l', 'resa_w', 'thr_elev', 'tdz_elev', 'disp_thr_length', 'swy_length', 'cwy_length','slope', 'disp_thr_elev', 'disp_lat','disp_lon','tora', 'toda', 'asda', 'geom','lda', 'remarks','true_brg', 'cwy_width', 'swy_width', 'slope1','status','editor','geoid'];
			if ($request->lowsave=='YES'){
				for ($i=0; $i < count($thrffldup) ; $i++) {
					$tlow='low_'.$thrffldup[$i];
					$thrlow[$thrffldup[$i]]=$request->$tlow;
				}
				$id=$request->low_id;
				$rwylow = RwyPhysicalTemp::find($id);
				
				$rwylow->update($thrlow);
			}
			if ($request->highsave=='YES'){
				for ($i=0; $i < count($thrffldup) ; $i++) {
					$thigh='high_'.$thrffldup[$i];
					$thrhigh[$thrffldup[$i]]=$request->$thigh;
				}
				$id=$request->high_id;
				$rwyhigh = RwyPhysicalTemp::find($id);
				$rwyhigh->update($thrhigh);
			}
		}else if ($request->status=='N'){
			$last = RunwayTemp::latest('id')->first();
			
			$request->merge([
				'id' => $last->id + 1,
			]);
			RunwayTemp::create($request->all());
			// if ($request->lowsave=='YES'){
				for ($i=0; $i < count($thrffld) ; $i++) {
					$tlow='low_'.$thrffld[$i];
					$thrlow[$thrffld[$i]]=$request->$tlow;
				}
				$lastlow = RwyPhysicalTemp::latest('id')->first();
			// dd($lastlow);
				$thrlow['id']=$lastlow->id + 1;
					
				
				// dd($thrlow);
				RwyPhysicalTemp::create($thrlow);
			// }
			// if ($request->highsave=='YES'){
				for ($i=0; $i < count($thrffld) ; $i++) {
					$thigh='high_'.$thrffld[$i];
					$thrhigh[$thrffld[$i]]=$request->$thigh;
				}
				$lasthigh = RwyPhysicalTemp::latest('id')->first();
			
				$thrhigh['id']= $lasthigh->id + 1;
				// dd($thrhigh);
				RwyPhysicalTemp::create($thrhigh);
			// }
			$ret_msg ='Insert Data Success';
		}else if ($request->status=='D'){
			// dd($request);
			$airport = RunwayTemp::find($request->id);
			// dd($airport,$request->id);
			$airport->delete();
			if($request->low_id){
				$dlow = RwyPhysicalTemp::find($request->low_id);
				$dlow->delete();
			}
			if($request->high_id){
				$dhigh = RwyPhysicalTemp::find($request->high_id);
				$dhigh->delete();
			}
		}
			$rawdata['tablename']='arpt';
			$rawdata['fieldname']='arpt_ident';
			$rawdata['fieldid']=$arptident;
			$rawdata['status_raw']= 0;
			$rawdata['ori_change_pic']= $request->editor;
			saveDataRaw($rawdata);
		// window.location.href="{{ url('aipedit') }}/212" +"/" + a.arpt_ident;
		return redirect('/aipedit/212/'.$request->arpt_ident);
		// return ['msg'=>$ret_msg];

	}

	public function savephysicaltemp(Request $request)
	{
		$ret_msg='';
		$arptident=$request->arpt_ident;
		if ($request->status=='R'){
			$id=$request->id;
			$airport = RwyPhysicalTemp::find($id);
			// dd($airport);
			
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{
			RwyPhysicalTemp::create($request->all());
			$ret_msg ='Insert Data Success';
		}
		//save data to raw data pub, utk request data
		// $rawdata = new EaipController;
	
			$rawdata['tablename']='arpt';
			$rawdata['fieldname']='arpt_ident';
			$rawdata['fieldid']=$arptident;
			$rawdata['status_raw']= 0;
			$rawdata['ori_change_pic']= $request->editor;
			saveDataRaw($rawdata);
		// return $rawdata->saveupdaterawdata();
		// $datax = getDataApi($rawdata,'api/rawdata/save');

		// $raw_dat = Raw_Pub::where('tablename', 'arpt')
		// 						->where('fieldname', 'arpt_ident')
		// 						->where('fieldid', $arptident)
		// 						->where('status_raw','<=',70)
		// 						// ->where('status_raw','<', 100)
		// 						->first();
		// if ($raw_dat === null) {
		// 	$raw_dat = new Raw_Pub;
		// 	$raw_dat->tablename = 'arpt';
		// 	$raw_dat->fieldname = 'arpt_ident';
		// 	$raw_dat->fieldid = $arptident;
		// 	$raw_dat->status_raw = 0;
		// }
		// // dd($raw_dat);
		// $raw_dat->ori_change_pic = $request->editor;
		// $raw_dat->save();
		return redirect('/aipedit/212/'.$request->arpt_ident);
		// return response()->json(['return' => 'some data']);
		// return ApiResponse::success();
		// return ['msg'=>$ret_msg];


	}
	public function savephysical(Request $request)
	{
	
		$temp = RwyPhysical::create($request->all());

        return ApiResponse::success($temp->id);


	}

	public function update(Request $request, string $id)
	{
		$airport = Runway::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function updatephysical(Request $request, string $id)
	{
		$airport = RwyPhysical::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function remove(Request $request, string $id)
	{
		$airport = Runway::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
	}
	
	public function GetThr(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, RwyPhysical::query());
						// ->join('arpt_rwy','arpt_rwy.rwy_id','arpt_rwy_physical.rwy_id'));

		return ApiResponse::success($results);
	}
	public function GetThrTemp(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, RwyPhysicalTemp::query());
						// ->join('arpt_rwy','arpt_rwy.rwy_id','arpt_rwy_physical.rwy_id'));

		return ApiResponse::success($results);
	}

	public function GetRwyLgt(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Rwylgt::query());
						// ->join('arpt_rwy','arpt_rwy.rwy_id','arpt_rwy_physical.rwy_id'));

		return ApiResponse::success($results);
	}

	public function AirportRwy(Request $request,string $id)
	{

    $frq ="select a.thr_low,a.thr_high,b.rwy_key,a.rwy_id,a.arpt_ident,b.rwy_ident,a.length,a.width,a.pcn,d.definition,b.true_brg,b.tora,b.toda,b.asda,b.lda,apch_lgt_type_len, thr_lgt_clr_wbar, vasis_meht_papi, tdz_lgt_len, rwy_ctrln_lgt_length_spc_clr, rwy_edge_lgt_len_spc_clr, rwy_end_lgt_clr_wbar, swy_lgt_len_clr,c.remark from arpt_rwy a inner join cod_rwy_surface d on d.id=a.surface left join arpt_rwy_physical b on b.rwy_id=a.rwy_id left join eaip_rwy_lgt c on c.rwy_id=b.rwy_key where a.arpt_ident='$id' order by a.rwy_id, b.rwy_ident";

        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
	}
	
	public function savelgt(Request $request)
	{
		$ret_msg='';
		// dd($request);
		$arptident=$request->arpt_ident;
		if ($request->status=='R'){
			$id=$request->id;
			$airport = RwylgtTemp::find($id);
			// dd($airport);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{
			$last = RwylgtTemp::latest('id')->first();
			$request->merge([
				'id' => $last->id + 1,
			]);
			// dd($request,$last->id);
			RwylgtTemp::create($request->all());
			$ret_msg ='Insert Data Success';
		}
		$rawdata['tablename']='arpt';
		$rawdata['fieldname']='arpt_ident';
		$rawdata['fieldid']=$arptident;
		$rawdata['status_raw']= 0;
		$rawdata['ori_change_pic']= $request->editor;
		saveDataRaw($rawdata);
		// $raw_dat = Raw_Pub::where('tablename', 'arpt')
		// 						->where('fieldname', 'arpt_ident')
		// 						->where('fieldid', $arptident)
		// 						->where('status_raw','<=',70)
		// 						// ->where('status_raw','<', 100)
		// 						->first();
		// if ($raw_dat === null) {
		// 	$raw_dat = new Raw_Pub;
		// 	$raw_dat->tablename = 'arpt';
		// 	$raw_dat->fieldname = 'arpt_ident';
		// 	$raw_dat->fieldid = $arptident;
		// 	$raw_dat->status_raw = 0;
		// }
		// // dd($raw_dat);
		// $raw_dat->ori_change_pic = $request->editor;
		// $raw_dat->save();

		return redirect('/aipedit/214/'.$request->arpt_ident);
		


	}

	public function updatelgt(Request $request, string $id)
	{
		$airport = Rwylgt::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
	}

	public function removelgt(Request $request, string $id)
	{
		$airport = Rwylgt::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
	}


}
