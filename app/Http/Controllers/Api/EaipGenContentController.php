<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\RequestParamHandler;
use App\ApiResponse;
use App\Models\Api\EaipGenContent;
use App\Models\Api\EaipChartContentTemp as CC_Temp;
use App\Models\Api\AirspaceTemp;
use App\Models\Api\LocIndicator;
use App\Models\Api\LocIndicatorTemp;
use Auth;
use Session;


class EaipGenContentController extends Controller
{
	public function index(Request $request, RequestParamHandler $rpm)
	{
		return ApiResponse::success($rpm->process($request, EaipGenContent::query()));
	}

	public function locindicator(Request $request, RequestParamHandler $rpm)
	{
		return ApiResponse::success($rpm->process($request, LocIndicator::query()
		->with(['country']))
		);
	}
	public function locindicatortemp(Request $request, RequestParamHandler $rpm)
	{
		return ApiResponse::success($rpm->process($request, LocIndicatorTemp::query()
		->with(['country']))
		);
	}
	public function locindicatorsave(Request $request)
	{
		
		$ret_msg='';
		// dd($request);
		if ($request->status=='R'){
			$id=$request->loc_id;
			$airport = LocIndicatorTemp::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
			if ($request->tbl =='ARPT'){
				$fld=[229,231,232,233];
				for ($i=0; $i < count($fld); $i++) { 
					$exist =  CC_Temp::where('category_id','=',$fld[$i])
					->where('arpt_ident','=',$request->loc_arptident)
					->first();
					switch ($fld[$i]) {
						case 229:
							$value=$request->indicator;
							break;
						case 231:
							$value=$request->name;
							break;
						case 232:
							$value=$request->city;
							break;
						case 233:
							$value=$request->ctry;
							break;
						
					}
	
					if($exist){
						if( preg_replace("/\s+/", "",$exist->content) != preg_replace("/\s+/", "",$value) ){
							$exist->content= $value; 
							$exist->status ='R';
							$exist->editor=$request->editor;
							// dd('exist-> exist = '. $exist);
							$exist->save(); 
							// update rawdata_pub
							if(!is_null($exist)){
								$rawdata['tablename']='arpt';
								$rawdata['fieldname']='arpt_ident';
								$rawdata['fieldid']=$request->loc_arptident;
								$rawdata['status_raw']=0;
								$rawdata['ori_change_pic']= $request->editor;
								saveDataRaw($rawdata);
								
								$rawdata['tablename']='GEN';
								$rawdata['fieldname']='sub_id';
								$rawdata['fieldid']='GEN 2.4';
								$rawdata['status_raw']=50;
								$rawdata['ori_change_pic']= $request->editor;
								saveDataRaw($rawdata);
							} 
						}
						$status=true;
					}else{
						$dat = new CC_Temp;                         
						$dat->category_id   = $fld[$i];
						$dat->arpt_ident    = $request->loc_arptident ;
						$dat->content       = $value; 
						$dat->status        = 'N';
						$dat->editor        = $request->editor;
						// dd('not exist-> dat = '. $dat);
						$dat->save(); 
						// update rawdata_pub
						if($dat){ 
							$rawdata['tablename']='arpt';
							$rawdata['fieldname']='arpt_ident';
							$rawdata['fieldid']=$request->loc_arptident;
							$rawdata['status_raw']=0;
							$rawdata['ori_change_pic']= $request->editor;
							saveDataRaw($rawdata);
	
							$rawdata['tablename']='GEN';
							$rawdata['fieldname']='sub_id';
							$rawdata['fieldid']='GEN 2.4';
							$rawdata['status_raw']=50;
							$rawdata['ori_change_pic']= $request->editor;
							saveDataRaw($rawdata);	
						}
						$status=true;
					} 
				}
			}else if ($request->tbl =='ASP'){
				$exist =  AirspaceTemp::where('ats_airspace_id','=',$request->loc_arptident)
				->first();
				if($exist){
					$exist->airspace_name= $request->city; 
					$exist->status ='R';
					$exist->editor=$request->editor;
					// dd('exist-> exist = '. $exist);
					$exist->save(); 

							$rawdata['tablename']='ENR';
							$rawdata['fieldname']='sub_id';
							$rawdata['fieldid']='ENR 2.1';
							$rawdata['status_raw']=50;
							$rawdata['ori_change_pic']= $request->editor;
							saveDataRaw($rawdata);
							
							$rawdata['tablename']='GEN';
							$rawdata['fieldname']='sub_id';
							$rawdata['fieldid']='GEN 2.4';
							$rawdata['status_raw']=50;
							$rawdata['ori_change_pic']= $request->editor;
							saveDataRaw($rawdata);
						

				}
				
			}


		}else{	
			$last = LocIndicatorTemp::latest('loc_id')->first();
			$request->merge([
				'loc_id' => $last->loc_id + 1
			]);
            // $request->loc_id = $last->loc_id + 1;
			// dd($request);
			LocIndicatorTemp::create($request->all());
			$ret_msg ='Insert Data Success';
		}
		
		
		return back();
    }

	public function locindicatorremove(Request $request,$id)
	{
		
		$airport = LocIndicatorTemp::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return back();
    }
	public function text(Request $request, string $subId)
	{
		$text = null;

		$contents = EaipGenContent::query()
				->where('sub_id', $subId)
				->orderBy('seq', 'ASC')
				->get();

		foreach ($contents as $content) {
			if ($content->content == '') {
				$text .= '<br><br>';

				continue;
			}
			

			$text .= $content->content;
		}

		return ApiResponse::success($text);
	}
}