<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\Frequency;
use App\Models\Api\FrequencyTemp;
use App\Models\Api\FreqUsage;
use App\Models\Api\FreqSeg;
use App\Models\Api\FreqValue;
use App\Models\Api\FreqUsageTemp;
use App\Models\Api\FreqSegTemp;
use App\Models\Api\FreqValueTemp;
use App\Models\Api\AirspaceTemp;
use App\Models\Api\ChartFreq;
use App\Models\Api\ChartFreqTemp;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class FreqController extends Controller
{
	public function list(Request $request, RequestParamHandler $rpm)
	{
        $freq = $rpm->process($request, Frequency::query()
        ->where('freq.deleted',0)
        ->orderBy('freq.types','asc')
        ->orderBy('freq.call_sign','asc')
        ->orderBy('freq.sector','asc')
        ->with(['usage'=> function($query) {
            return $query->with(['airport'])->with(['airspace']);
                }
            ])
        ->with([
            'segment' => function($query) {
                return $query->with(['value']);
            }
        ]));
    return ApiResponse::success($freq);

    }

    public function chartfreqlist(Request $request, RequestParamHandler $rpm)
	{
        // dd($request);
        $arptident=$request->arpt_ident;
        $charttype='';
        if ($request->chart_type){
            $charttype="and f.chart_types='".$request->chart_type."'";

        }
        $freq ="select a.id as frequsedid,f.seq, f.id,d.types,f.chart_types, case when c.unit='V' then c.freq/1000000::real else c.freq/1000::real End as Freq,d.call_sign,d.sector,case when b.status='1' then 'Primary' when b.status='2' then 'Secondary'  else '-' end as Status, b.opr_hrs from freq_chart_temp f inner join freq_used_temp a on a.id=f.freqid inner join freq_temp d on d.id=a.freqid inner join freq_seg_temp b on b.call_sign=d.id inner join freq_value_temp c on c.freq_id=b.freq_id where a.arpt_ident='".$arptident."'".$charttype." order by f.seq, d.types,d.call_sign,b.status";
        $results=DB::select(DB::raw($freq));
		return ApiResponse::success($results);
// $rpm->process($request, ChartFreqTemp::query()
//         ->with(['callsign']));
       
//     return ApiResponse::success($freq);

    }

    public function listtemp(Request $request, RequestParamHandler $rpm)
	{
        $freq = $rpm->process($request, FrequencyTemp::query()
        ->where('freq_temp.deleted',0)
        ->orderBy('freq_temp.types','asc')
        ->orderBy('freq_temp.call_sign','asc')
        ->orderBy('freq_temp.sector','asc')
        ->with(['usage'=> function($query) {
            return $query->with(['airport'])->with(['airspace']);
                }
            ])
        ->with([
            'segment' => function($query) {
                return $query->with(['value']);
            }
        ]));
    return ApiResponse::success($freq);

    }

    public function getfreqvalue(Request $request, RequestParamHandler $rpm)
	{
        $freq = $rpm->process($request, FreqValue::query());

    return ApiResponse::success($freq);

    }


    public function listsegment(Request $request,string $id)
		{
        $ats=DB::table('freq_seg')->select('freq_seg.id','opr_hrs','freq_seg.freq_id','satcom','logon',
            DB::raw("(CASE when freq_value.unit ='V' THEN round(cast(freq_value.freq as decimal) / 1000000,3)
                                else round(cast(freq_value.freq as decimal) / 1000,1) end) as freq_real"),
                                DB::raw("(CASE when freq_seg.status ='1' THEN 'PRIMARY' when freq_seg.status ='2' THEN 'SECONDARY' else 'NONE' end) as priority"),'freq_value.unit','freq_value.freq','status')
        ->join('freq_value','freq_value.freq_id','freq_seg.freq_id')
        ->where('call_sign',$id)
        ->where('deleted',0)
        ->orderBy('priority','asc')
        ->orderBy('freq_value.freq','asc')
        ->get();
    return ApiResponse::success($ats);
    }


    public function UseOn(Request $request,string $id)
	{
        $ats=DB::table('freq_used')->select(DB::raw("(CASE when arpt_ident NOTNULL THEN (select CONCAT('Airport',' - ',icao,' - ',arpt_name) from arpt where arpt_ident=freq_used.arpt_ident)
        when asp_id NOTNULL THEN (select CONCAT('Airspace',' - ',airspace_name,' - ',airspace_type) from airspace where ats_airspace_id=freq_used.asp_id) end) as use_on"))
        ->where('freqid',$id)
        ->get();
    return ApiResponse::success($ats);
    }

    public function FreqUsage(Request $request, RequestParamHandler $rpm)
	{
        $freq = $rpm->process($request, FreqUsage::query()
        ->with([
            'callsign' => function($query) {
                return $query->with([
                    'segment' => function($query) {
                        return $query->with('value');
                    }
                ]);
            }
        ]));
        // ->where('freq.deleted',0)
        // ->orderBy('freq_used.seq','asc'));
    return ApiResponse::success($freq);

    }

    public function FreqUsagetemp(Request $request, RequestParamHandler $rpm)
	{
        $freq = $rpm->process($request, FreqUsageTemp::query()
        ->with([
            'callsign' => function($query) {
                return $query->with([
                    'segment' => function($query) {
                        return $query->with('value');
                    }
                ]);
            }
        ]));
        // ->where('freq.deleted',0)
        // ->orderBy('freq_used.seq','asc'));
    return ApiResponse::success($freq);

    }


    public function AirportFreq(Request $request,string $id)
	{

    $frq ="select d.id,a.id as frequsedid,b.satcom,b.logon, a.id ||'_'||c.freq_id as freqid,c.unit,d.types,a.seq, case when c.unit='V' then c.freq/1000000::real else c.freq/1000::real End as Freq,d.call_sign,d.sector, case when b.level='1' then 'Primary' when b.level='2' then 'Secondary'  else '-' end as Status, b.opr_hrs,d.remarks from freq_used a left join freq d on d.id=a.freqid left join freq_seg b on b.call_sign=d.id left join freq_value c on c.freq_id=b.freq_id where a.arpt_ident='$id' order by a.seq, d.types,d.call_sign,b.level";

        $frq= DB::select(DB::raw($frq));
    return ApiResponse::success($frq);
    }

    public function AirportFreqTemp(Request $request,string $id)
	{

    $frq ="select d.id,a.id as frequsedid,b.satcom,b.logon, a.id ||'_'||c.freq_id as freqid,c.unit,d.types,a.seq, case when c.unit='V' then c.freq/1000000::real else c.freq/1000::real End as Freq,d.call_sign,d.sector, case when b.level='1' then 'Primary' when b.level='2' then 'Secondary'  else '-' end as Status, b.opr_hrs,d.remarks from freq_used_temp a left join freq_temp d on d.id=a.freqid left join freq_seg_temp b on b.call_sign=d.id left join freq_value_temp c on c.freq_id=b.freq_id where a.arpt_ident='$id' order by a.seq, d.types,d.call_sign,b.level";

        $frq= DB::select(DB::raw($frq));
    return ApiResponse::success($frq);
    }


    public function listcode(Request $request)
	{
        $ats=DB::table('freq')->select('freq.types','cod_comm_types.definition','cod_comm_types.comm_box_type')
        ->join('cod_comm_types','cod_comm_types.id','freq.types')
        ->where('freq.ctry','ID')
        ->where('freq.deleted',0)
        ->groupBy('freq.types','cod_comm_types.id')
        ->orderBy('freq.types','asc')
        ->get();
    return ApiResponse::success($ats);
    }
   
    public function getfreqonly(Request $request, RequestParamHandler $rpm)
	{
        $freq = $rpm->process($request, FrequencyTemp::query()->select('freq_temp.id','freq_temp.call_sign','cod_comm_types.comm_box_type')
        ->join('cod_comm_types','cod_comm_types.id','freq_temp.types')->where('call_sign','<>',''));

        return ApiResponse::success($freq);

    }
    public function search(Request $request)
	{
        // dd($request);
        $data = FrequencyTemp::whereRaw("(call_sign LIKE '%".$request->get('q')."%') and ctry='ID'")->where('deleted',0)
                ->get();
        return response()->json($data);
    
    }


    public function removefreqused(Request $request)
	{
     
        // dd($id);
        $id=$request->frequsedid;
        $airport = FreqUsageTemp::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		// $airport->deleted = 1;

		$airport->delete();

		return back();
    }

    public function removeseg($id)
	{
     
        // dd($id);
        $id=$request->frequsedid;
        $airport = FreqSegTemp::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		// $airport->deleted = 1;

		$airport->delete();

		return back();
    }

    
  
    public function changesequence(Request $request)
	{
        $id=$request->id;
        // dd($request);
       
        $arptident=$request->arpt_ident;
        // dd($id);
        $airport = ChartFreqTemp::find($id);
		if (null === $airport) {
            return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}
        
		$airport->update($request->all());
        // dd( $airport);

        return redirect('/chartprop/'.$arptident.'/pro');
    }

    public function updatefreqused(Request $request)
	{
        $id=$request->id;
       
        $arptident=$request->arpt_ident;
        // dd($id);
        $airport = FreqUsageTemp::find($id);
        // dd( $airport);
		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

        return redirect('/edit218/'.$arptident);
    }
   

    public function insertfreqused(Request $request)
	{
        // dd($request,$request->arpt_ident);
        $redir='';$affarpt=true;
        if ($request->arpt_ident !==null){
            $arptident=$request->arpt_ident;
            $redir='/edit218/'.$arptident;
            $last = FreqUsageTemp::latest('id')->first();
            $seq = FreqUsageTemp::latest('seq')->first();
            $freq_dat = new FreqUsageTemp;
            $freq_dat->id = $last->id + 1;
            $freq_dat->freqid = $request->freqid;
            $freq_dat->arpt_ident = $arptident;
            $freq_dat->status = 'N';
            $freq_dat->seq = $seq->id + 1;;
            $freq_dat->editor = $request->editor;
            $freq_dat->save();
        }else{
            $arptident=$request->asp_airport;
            $redir='airspace/'.$request->asp_id.'@edit@'.$request->asp_id;
            $last = FreqUsageTemp::latest('id')->first();
            $seq = FreqUsageTemp::latest('seq')->first();
            $freq_dat = new FreqUsageTemp;
            $freq_dat->id = $last->id + 1;
            $freq_dat->freqid = $request->freqid;
            $freq_dat->asp_id = $request->asp_id;
            $freq_dat->status = 'N';
            $freq_dat->seq = $seq->id + 1;;
            $freq_dat->editor = $request->editor;
            $freq_dat->save();
            if ($request->asp_type=='AFIZ' || $request->asp_type=='ATZ' || $request->asp_type=='CTR'){
                $affarpt=true;
            }else{
                $affarpt=false;
            }
        }
        $tbl='';$fld='';$fldid='';$sts_raw=0;
        if ($affarpt==true){
            $tbl='arpt';
            $fld='arpt_ident';
            $fldid=$arptident;
        }else{
            $tbl='ENR';
            $fld='sub_id';
            $fldid='ENR 2.1';
            $sts_raw=50;
        }

            $rawdata['tablename']=$tbl;
            $rawdata['fieldname']=$fld;
            $rawdata['fieldid']=$fldid;
            $rawdata['status_raw']= $sts_raw;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);

        
        return redirect($redir);
    }
    public function save(Request $request)
	{
        // dd($request);
        $ret_msg='';$temp='';
		if ($request->status=='R'){
			$id=$request->id;
			$airport = FrequencyTemp::find($id);
            // dd($airport);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
		}else{
            $last = FrequencyTemp::latest('id')->first();
            $request->merge([
				'id' => $last->id + 1,
			]);
            $temp =	FrequencyTemp::create($request->all());
			$ret_msg ='Insert Data Success';
		}
       
        $redirec='/airspace/'.$request->parentid.'@edit@ENR2.1';
        $arptident=$request->parentid;
        if ($request->parent=='arpt'){
            $redirec='/edit218/'.$request->parentid;

            $rawdata['tablename']='arpt';
            $rawdata['fieldname']='arpt_ident';
            $rawdata['fieldid']=$arptident;
            $rawdata['status_raw']= 0;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);

         
            
                    $freq_dat = FreqUsageTemp::where('arpt_ident',$arptident)->first();
                    if ($freq_dat === null) {
                        $last = FreqUsageTemp::latest('id')->first();
                        $freq_dat = new FreqUsageTemp;
                        $freq_dat->id = $last->id + 1;
                        $freq_dat->freqid = $temp->id;
                        $freq_dat->arpt_ident = $arptident;
                        $freq_dat->status = 'N';
                    }else{
                        
                        $freq_dat->status = 'R';
                    }
                    $freq_dat->editor = $request->editor;
                    $freq_dat->save();

               
                
        }else  if ($request->parent=='ENR2.1'){
            $redirec='/airspace/'.$request->parentid.'@edit@ENR2.1';

            $rawdata['tablename']='ENR';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']='ENR 2.1';
            $rawdata['status_raw']= 50;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);

          

                    $freq_dat = FreqUsageTemp::where('asp_id',$arptident)->first();
                   
                if ($freq_dat === null) {
                    $last = FreqUsageTemp::latest('id')->first();
                    $freq_dat = new FreqUsageTemp;
                    $freq_dat->id = $last->id + 1;
                    $freq_dat->freqid = $temp->id;
                    $freq_dat->asp_id = $arptident;
                    $freq_dat->status = 'N';
                }else{
                    $freq_dat->status = 'R';
                }
                $freq_dat->editor = $request->editor;
                $freq_dat->save();


            }
            return redirect($redirec);
        // $temp = FrequencyTemp::create($request->all());

        // return ApiResponse::success($temp->id);
    }
    public function saveseg(Request $request)
	{
        // dd($request);
        
       

        $ret_msg='';
		if ($request->seg_status=='R'){
			$id=$request->seg_id;
            $request->merge([
                'status' => 'R',
			]);
            // $request->status='R';
			$airport = FreqSegTemp::find($id);
			$airport->update($request->all());
			$ret_msg='Update Data Success';
        }else if ($request->seg_status=='D'){
            $airport = FreqSegTemp::find($request->id);
            $airport->delete();
		}else{
            $last = FreqSegTemp::latest('id')->first();
            $request->merge([
				'id' => $last->id + 1,
                'status' => 'N',
			]);
			FreqSegTemp::create($request->all());
			$ret_msg ='Insert Data Success';
		}
        $newval=FreqValueTemp::where('freq_id','=',$request->freq_id)->first();
        if ($newval==null){
            $last = FreqValueTemp::latest('id')->first();
            $newval= new FreqValueTemp;
            $newval->id=$last->id + 1;
            $newval->freq_id=$request->freq_id;
            $newval->freq=$request->freq;
            $newval->unit=$request->unit;
            $newval->status='N';
            $newval->save();
        }
       
       
        if ($request->parent=='arpt'){
            $arptident=$request->parentid;

            $rawdata['tablename']='arpt';
            $rawdata['fieldname']='arpt_ident';
            $rawdata['fieldid']=$arptident;
            $rawdata['status_raw']=0;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);
                $redirec='/edit218/'.$request->parentid;
           // return redirect('/edit218/'.$request->parentid);
        } else  if ($request->parent=='ENR21'){
            $newval=AirspaceTemp::where('ats_airspace_id','=',$request->parentid)->first();
            $newval->status='R';
            $newval->save();
            $freq_dat = FreqUsageTemp::where('asp_id',$request->parentid)->first();
            $freq_dat->status='R';
            $freq_dat->editor=$request->editor;
            $freq_dat->save();

            $redirec='/airspace/'.$request->parentid.'@edit@ENR2.1';
            $rawdata['tablename']='ENR';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']='ENR 2.1';
            $rawdata['status_raw']= 50;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);
        }
            // $redirec='/airspace/'.$request->parentid;
            return redirect($redirec);
            // return back()->with(['msg'=>$ret_msg]);
        
        


    }
    public function update(Request $request, string $id)
	{
		$airport = Frequency::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

    return ApiResponse::success($airport->fresh());
    }

    public function remove(Request $request, string $id)
	{
        
        
        $ats = Frequency::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

   

    public function updateseg(Request $request, string $id)
	{
		$airport = FreqSeg::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

    return ApiResponse::success($airport->fresh());
    }

    public function removeusage(Request $request, string $id)
	{
        

        $airport = FreqUsageTemp::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		// $airport->deleted = 1;

		$airport->delete();

		return back();
    }
    public function savevalue(Request $request)
	{
        $temp = FreqValue::create($request->all());
        return ApiResponse::success($temp->id);

    }

    public function updatevalue(Request $request, string $id)
	{
		$airport = FreqValue::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

    return ApiResponse::success($airport->fresh());
    }

    public function removevalue(Request $request, string $id)
	{
        
        
        $ats = FreqValue::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }



}
