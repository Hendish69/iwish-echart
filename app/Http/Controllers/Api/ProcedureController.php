<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Arptprocedure;
use App\Models\Api\Arptprocedureseg;
use App\Models\Api\ArptprocedureTemp;
use App\Models\Api\ArptproceduresegTemp;
use App\Models\Api\Arptrans;
use App\Models\Api\ArptransTemp;
use App\Models\Api\Arptranseg;
use App\Models\Api\ArptransegTemp;
use App\Models\Api\ChartProperties as pchart;
use App\Models\Api\ChartProcedureTemp as arptchart;
use App\Models\Api\ChartBasemap;
use App\Models\Api\ChartFreqTemp;
use App\Models\Api\ChartMinimaTemp;
use App\Models\Api\AirportTaTl;
use App\Models\Api\CecFpl as Fpl;

use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class ProcedureController extends Controller
{
	public function chartprop(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, pchart::query()
        ->with(['aip'])
        ->with(['msa'])
        ->with(['source'])
        ->with(['basemap'])
        ->with([
			'procedure' => function($query) {
				return $query->select('chart_proc_temp.*','arpt_proc_temp.proc_name')->leftjoin('arpt_proc_temp','arpt_proc_temp.proc_id','chart_proc_temp.proc_id')->orderby('arpt_proc_temp.proc_name','asc')->with([
					'segment' => function($query) {
						return $query->with(['segment'=> function($query) {
                            return $query->select('arpt_proc_seg_temp.*','arpt_trans_temp.rt_type')->leftjoin('arpt_trans_temp','arpt_trans_temp.proc_id','arpt_proc_seg_temp.trans_id')->orderby('arpt_trans_temp.rt_type','asc')->with(['transition' => function($query) {
                                return $query->with(['runway'])->with(['segment' =>function($query) {
                                    return $query->with(['navaid'])->with(['waypoint'])->with(['arpt'])->with(['marker'])->with('rwy')->with(['recdnav1'])->with('recdnav2')->with(['recdils1'])->with(['recdils2']);
                                    }
                                ]);
                            } 
                        ]);
                        }
                    ]);
					}
				]);
			}
		]));

        // transition
		return ApiResponse::success($results);
    }

    public function chartminima(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, ChartMinimaTemp::query());
    

		return ApiResponse::success($results);
    }

    public function getfpl(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Fpl::query());
    

		return ApiResponse::success($results);
    }
    
    public function framechartsave(Request $request)
	{
        $charts=explode(',',$request->listchart);
        // dd($request,$charts,count($charts));
        if ($request->status=='R'){
            $bm = ChartBasemap::find($request->id);
            $bm->update($request->all());
        } else if ($request->status=='N'){
            $last = ChartBasemap::latest('id')->first();
            $request->merge([
                'id' => $last->id + 1,
            ]);
            ChartBasemap::create($request->all());

        }else if ($request->status=='D'){
            // $msa=pchart::find($request->id);
            // $msa->delete();
            $chrt= ChartBasemap::find($request->id);
            $chrt->delete();
        }
        if ($request->status !=='D'){
            for ($i=0; $i < count($charts); $i++) { 
                $chart_data=pchart::where('id','=',$charts[$i])->first();
                if ($chart_data && $request->chart_id){
                    $chart_data->bm_id=$request->chart_id;
                    $chart_data->save();
                }
                // dd($chart_data);
            }

        }


    

        return redirect('/chartprop/'.$request->arpt_ident.'/frm');
    }

    public function chartpropsave(Request $request)
	{
        $charts=explode(',',$request->listproc);
        $freq=explode(',',$request->listfreq);
        // dd($request,count($charts),count($freq));
        if ($request->save_status=='R'){
            $bm = pchart::find($request->id);
            $bm->update($request->all());

            $chart_data=arptchart::where('chart_id','=',$request->chart_id)->get();
            // dd($chart_data,$charts,count($chart_data));
            if ($request->listproc !== null){
                for ($i=0; $i < count($charts); $i++) { 
                    $ada=false;
                    // dd($chart_data,$charts[$i] );
                    // $kky = array_search($charts[$i], array_column($chart_data, 'proc_id'));
                    // dd($kky);
                    // for ($x=0; $x < count($chart_data); $x++) { 
                    foreach ($chart_data as $key => $cht) {
                        // var_dump($cht->proc_id);
                        $prcid=$cht->proc_id;
                        if($prcid ==$charts[$i]){
                            // dd($chart_data[$x]->proc_id,$charts[$i] );
                            $ada=true;
                            break;
                        }
                    }
                    if ($ada==false){
                        $last = arptchart::latest('id')->first();
                        $chart_data= new arptchart;
                        $chart_data->id=$last->id + 1;
                        $chart_data->chart_id=$request->chart_id;
                        $chart_data->proc_id=$charts[$i];
                        $chart_data->status='N';
                        $chart_data->editor=$request->editor;
                        $chart_data->save();
                        // var_dump($charts[$i],'ada');
                        
                    }
                }
                
            }
    
            foreach ($chart_data as $key => $value) {
                $ada1=false;
                if ($request->listproc !== null){
                    for ($i=0; $i < count($charts); $i++) { 
                        if($value->proc_id==$charts[$i]){
                            $ada1=true;
                            break;
                        }
                    }
                }
                if ($ada1==false){
                    //delete dari arpt_proc
                    $chart_data=arptchart::where('proc_id','=',$value->proc_id)->where('chart_id','=',$request->chart_id)->first();
                    $chart_data->delete();
                    var_dump($value->proc_id,'ada1');
                    
                }
            }


        } else if ($request->save_status=='N'){
            $last = pchart::latest('id')->first();
            $request->merge([
                'id' => $last->id + 1,
            ]);
            pchart::create($request->all());
            for ($i=0; $i < count($charts); $i++) { 
                $last = arptchart::latest('id')->first();
                $chart_data= new arptchart;
                $chart_data->id=$last->id + 1;
                $chart_data->chart_id=$request->chart_id;
                $chart_data->proc_id=$charts[$i];
                $chart_data->status='N';
                $chart_data->editor=$request->editor;
                $chart_data->save();
            
            }
        }else if ($request->save_status=='D'){
            // $msa=pchart::find($request->id);
            // $msa->delete();
            $chrt= pchart::find($request->id);
            $chrt->delete();
        }
        
        if ($request->listfreq !== null){
            for ($i=0; $i < count($freq); $i++) { 
                $fchart = ChartFreqTemp::where('freqid','=',$freq[$i])->first();
                if ($fchart==null){
                    $last = ChartFreqTemp::latest('id')->first();
                    $lseq = ChartFreqTemp::latest('seq')->first();
                    $fchart= new ChartFreqTemp;
                    $fchart->id=$last->id + 1;
                    $fchart->arpt_ident=$request->chart_arpt_ident;
                    $fchart->seq=$lseq->id + 1;
                    $fchart->freqid=$freq[$i];
                    $fchart->chart_types=$request->chart_type;
                    $fchart->status='N';
                    $fchart->editor=$request->editor;
                    $fchart->save();
                    
                }
            
            }
            
        }

        return back();//redirect('/chartprop/'.$request->chart_arpt_ident.'/pro');
    }
    public function framechart(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, ChartBasemap::query()->with(['chart']));
        
    

		return ApiResponse::success($results);
    }

    public function arptproc(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Arptprocedureseg::query()
        ->with([
			'transition' => function($query) {
				return $query->with([
					'segment'
				]);
			}
		]));
    
        

		return ApiResponse::success($results);
    }

    public function procedure(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, ArptprocedureTemp::query()
        ->with(['chart'])
        ->with(['airport'])
        ->with(['segment'=>function($query) {
            return $query->with([
                'transition'=>function($query) {
                    return $query->with('segment');
                    }
            ]);
        }
                ])
        );

		return ApiResponse::success($results);
    }

    public function list(Request $request, RequestParamHandler $rpm)
	{
        $builder = Arptprocedure::query()
        ->join('cod_chart_types','cod_chart_types.id',DB::raw("cast(arpt_proc.chart_type as int)"))
        ->where('arpt_proc.deleted', 0)
        ->orderby('chart_type','asc')
        ->orderby('proc_name','asc')
        ->with(['airport']);
        // ->with(['procseg']);

        // ->with([
        //     'segment' => function($query) {
        //         return $query->with([
        //             'transition' => function($query) {
        //                 return $query->with('transitionsegment');
        //             }
        //         ]);
        //     }
        // ]);
        // ->with(['adc']);

    $results = $rpm->process($request, $builder);

    return ApiResponse::success($results);
	}

    public function listtrans(Request $request,string $pid)
	{
        $ats=DB::table('arpt_proc')->select('arpt_trans.*')
        ->join('arpt_proc_seg','arpt_proc_seg.proc_id','arpt_proc.proc_id')
        ->join('arpt_trans','arpt_trans.proc_id','arpt_proc_seg.trans_id')
        ->where('arpt_proc.proc_id',$pid)
        ->where('arpt_trans.deleted',0)
        ->orderBy('rt_type','asc')
        ->get();

    return ApiResponse::success($ats);


    }
    public function transition(Request $request, RequestParamHandler $rpm)
	{
        
        $builder = Arptrans::query()->select('arpt_trans.*','cod_trans_types.definition')
        ->join('cod_trans_types', function ($join) {
            $join->on('cod_trans_types.trans_code','=','arpt_trans.sub_chart_type')
            ->on('cod_trans_types.trans_types','=','arpt_trans.rt_type');
        })
        ->with(['segment'=>function($query) {
            return $query->with(['navaid'])->with(['waypoint'])->with(['arpt'])->with(['marker'])->with('rwy')->with(['recdnav1'])->with('recdnav2')->with(['recdils1'])->with('recdils2');
            }
                ])
        ->with(['airport'])->orderby('chart_type','asc')->orderby('rt_type','asc')->orderby('trans_ident','asc')->orderby('rwy_trans','asc');
		
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }

    public function transitiontemp(Request $request, RequestParamHandler $rpm)
	{
        
        $builder = ArptransTemp::query()->select('arpt_trans_temp.*','cod_trans_types.definition')
        ->join('cod_trans_types', function ($join) {
            $join->on('cod_trans_types.trans_code','=','arpt_trans_temp.sub_chart_type')
            ->on('cod_trans_types.trans_types','=','arpt_trans_temp.rt_type');
        })
        ->with(['segment'=>function($query) {
            return $query->orderBy('seq_num','asc')->with(['navaid'])->with(['waypoint'])->with(['arpt'])->with(['marker'])->with('rwy')->with(['recdnav1'])->with('recdnav2')->with(['recdils1'])->with('recdils2');
            }
                ])
        ->with(['airport'])
        ->orderBy('rwy_trans','asc')->orderby('rt_type','asc')->orderby('trans_ident','asc');
        // ->orderby('chart_type','asc')->orderby('rt_type','asc')->orderby('trans_ident','asc')->orderby('rwy_trans','asc');
		
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }

   

    public function procedures(Request $request, RequestParamHandler $rpm)
	{
        
        $builder = Arptprocedure::query()
        ->with(['airport'])
        ->with([
			'segment' => function($query) {
				return  $query->select('arpt_proc_seg.*','arpt_trans.trans_ident','arpt_trans.rt_type')->join('arpt_trans', function ($join) {
                    $join->on('arpt_trans.proc_id','=','arpt_proc_seg.trans_id')->orderby('arpt_trans.rt_type','asc');
                    
                })
                ->with(['transition' => function($query) {
                        return $query->select('arpt_trans.*','cod_trans_types.definition')->join('cod_trans_types', function ($join) {
                            $join->on('cod_trans_types.trans_code','=','arpt_trans.sub_chart_type')
                            ->on('cod_trans_types.trans_types','=','arpt_trans.rt_type');
                            
                        })->with(['segment'=>function($query) {
                            return $query->with(['navaid'])->with(['waypoint'])->with(['arpt'])->with(['marker'])->with('rwy')->with(['recdnav1'])->with('recdnav2')->with(['recdils1'])->with('recdils2');
                            }
                        ]);
                }
                ]);
			}
		]);
		
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }
    public function procedurestemp(Request $request, RequestParamHandler $rpm)
	{
        
        $builder = ArptprocedureTemp::query()
        ->with(['chart'])
        ->with(['airport'])
        ->with(['segment' => function($query) {
				return  $query->select('arpt_proc_seg_temp.*','arpt_trans_temp.trans_ident','arpt_trans_temp.rt_type')->join('arpt_trans_temp', function ($join) {
                    $join->on('arpt_trans_temp.proc_id','=','arpt_proc_seg_temp.trans_id')->orderby('arpt_trans_temp.rt_type','asc');
                    
                })
                ->with(['transition' => function($query) {
                        return $query->select('arpt_trans_temp.*','cod_trans_types.definition')->join('cod_trans_types', function ($join) {
                            $join->on('cod_trans_types.trans_code','=','arpt_trans_temp.sub_chart_type')
                            ->on('cod_trans_types.trans_types','=','arpt_trans_temp.rt_type');
                            
                        })->with(['segment'=>function($query) {
                            return $query->orderby('seq_num','asc')->with(['navaid'])->with(['waypoint'])->with(['arpt'])->with(['rwy'])->with(['marker' ])->with(['recdnav1'])->with('recdnav2')->with(['recdils1'])->with('recdils2');
                            }
                        ]);
                }
                ]);
			}
		])
        ->orderby('rwy','asc')
        ->orderby('proc_name','asc');
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }
    public function getnoteprocedurestemp(Request $request, RequestParamHandler $rpm)
	{
        
        $builder = ArptproceduresegTemp::query()->select('arpt_proc_temp.*')
        ->leftjoin('arpt_proc_temp','arpt_proc_temp.proc_id','arpt_proc_seg_temp.proc_id');

        
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }
	public function search(Request $request,string $id)
	{
    // $results=DB::table('waypoint')->select(DB::raw(("DISTINCT on (wpt_id) *")))
    //                     ->join('ats',function($ats){
    //                         $ats-> on('ats.point','waypoint.wpt_id')
    //                             ->on('ats.point2','waypoint.wpt_id');
    //                     })
    //                     ->join('arpt_trans_seg','arpt_trans_seg.fix_id','waypoint.wpt_id')
    //                     ->join('country','country.ident','=','waypoint.ctry')
    //                     ->where('waypoint.deleted', 0)
    //                     ->orderby('wpt_id','asc')
    //                     ->groupby('wpt_id','ats.ats_id','country.ident','arpt_trans_seg.fix_id','arpt_trans_seg.id')
    //                     ->get();
    // $results = Waypoint::query()
    //                     ->join('country','country.ident','=','waypoint.ctry')
    //                     ->where('deleted', 0);
    //                     if ($request->has('wpt_name')){
    //                         $results->where('wpt_name',$request->input('wpt_name'));
    //                     }
    //                     if ($request->has('desc_name')){
    //                         $results->where('desc_name',$request->input('desc_name'));
    //                     }

    //                     $results->get();
    $results=Arptprocedure::query()->select('waypoint.*','country.country')
                        ->join('country','country.ident','=','waypoint.ctry')
                        ->where('wpt_name','like',"{$id}%")
                        ->where('deleted', 0)
                        // ->like('wpt_name',$cari)
                        ->get();

		return ApiResponse::success($results);
    }

    public function update(Request $request, string $id)
	{
		$airport = Arptprocedure::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->update($request->all());

		return ApiResponse::success($airport->fresh());
    }

    public function transitionsegtemp(Request $request, RequestParamHandler $rpm)
	{
        
        $builder = ArptransegTemp::query()->rightJoin('arpt_trans_temp','arpt_trans_temp.proc_id','arpt_trans_seg_temp.proc_id')
      ->with(['navaid'])->with('waypoint')->with(['recdnav1'])->with('recdnav2')->with(['recdils1'])->with('recdils2');
            
                
       
		
		$results = $rpm->process($request, $builder);
		// dd($results);

		return ApiResponse::success($results);
    }


    public function saveprocedure(Request $request)
	{
        $updategeom=false;
       
        // dd($request);
        // return back()->withInput(['tab'=>$tab])->with('message',$message);
        // $redirec=redirect('/procedure/'.$request->arpt_ident.'/'.$request->chart_type)->withInput(['tab'=>'tabItem2']);
        $redirec=back()->withInput(['tab'=>'tabItem2']);
        // $redirec = back()->withInput(['tab'=>'tabItem2']);
        if ($request->status=='R'){
            
            // $airport = NavaidTemp::find($id);
            $existseg = ArptprocedureTemp::find($request->id);
            $existseg->update($request->all());
            
        }else if ($request->status=='N'){
            $exist = ArptprocedureTemp::where('proc_id', '=', $request->proc_id)
            ->first();
            if ($exist === null) {
                $last = ArptprocedureTemp::latest('id')->first();
                $request->merge([
                    'id' => $last->id + 1,
                ]);
            
                ArptprocedureTemp::create($request->all());
                // $dat_trans->save();
            }else{
                $exist->update($request->all());
            }
            $trans=explode(',',$request->listtrans);
            if ($request->listtrans !== null){
                for ($i=0; $i < count($trans); $i++) { 
                        $last = ArptproceduresegTemp::latest('id')->first();
                        $chart_data= new ArptproceduresegTemp;
                        $chart_data->id=$last->id + 1;
                        $chart_data->proc_id=$request->proc_id;
                        $chart_data->trans_id=$trans[$i];
                        $chart_data->status='N';
                        // $chart_data->editor=$request->editor;
                        $chart_data->save();
                        // var_dump($charts[$i],'ada');
                        
                    
                }
                
            }
            $redirec=redirect('/listprocsegment/'.$request->proc_id.'/'.$request->chart_type);
        }else if ($request->status=='I'){
            $last = ArptproceduresegTemp::latest('id')->first();
            $request->merge([
                'id' => $last->id + 1,
                'status' => 'N',
            ]);
            ArptproceduresegTemp::create($request->all());

            // $redirec='/listprocsegment/'.$request->proc_id.'/'.$request->chart_type;
            $redirec=redirect('/listprocsegment/'.$request->proc_id.'/'.$request->chart_type);

        }else if ($request->status=='D'){
            $trdel = ArptproceduresegTemp::find($request->id);
            $trdel->delete();
            $redirec=redirect('/listprocsegment/'.$request->proc_id.'/'.$request->chart_type);
            // listprocsegment/ID00046-SID-RABOL%203A/46

        }

        $seg = ArptproceduresegTemp::where('proc_id', $request->proc_id)
        ->get();
        // dd($seg);
        $no=10;
        foreach ($seg as $key => $value) {
            if ($value->wd2=='E' || $value->wd2=='B'){
                $updategeom=true;
                break;
            }
        }
        if ($updategeom==true){
            // drawtransition($request->proc_id);
            // dd('UPDATE GEOM',$request);
        }
        // dd($exist); return back()->withInput(['tab'=>$tab])->with('message',$message);
        // return redirect($redirec)->withInput(['tab'=>'tabItem2']);
        return $redirec;
        // ArptransTemp::create($request->all());

    }

    public function savetrans(Request $request)
	{
        $updategeom=false;

        
        // dd($request);
        if ($request->status=='DELETE_TRANS'){
            $existseg = ArptransTemp::find($request->id);
            // dd($existseg);
            $existseg->delete();
            $trdel = ArptproceduresegTemp::where('trans_id', $request->proc_id)->first();
            // $trdel = ArptproceduresegTemp::find($request->id);
            //  dd($trdel);
            if ($trdel !== null){

                $trdel->delete();
            }
            $redirec='/procedure/'.$request->arpt_ident.'/'.$request->chart_type;
        }else if ($request->status=='DELETE_PROC'){
            $trdelseg = ArptproceduresegTemp::where('proc_id', $request->proc_id)->get();
            // dd($trdelseg);
            if ($trdelseg !== null){
                foreach ($trdelseg as $key => $value) {
                    $segdata = ArptproceduresegTemp::where('id', $value->id)->first();
                    $segdata->delete();
                }
            }
            $existseg = ArptprocedureTemp::find($request->id);
            $trdel = arptchart::where('proc_id', $request->proc_id)->first();
            
            // dd($existseg,$trdel);
            $existseg->delete();
            $redirec='/procedure/'.$request->arpt_ident.'/'.$request->chart_type;
        }else{

            if ($request->status=='R'){
                $exist = ArptransTemp::where('id', '=', $request->trans_id)
                ->first();
                // $dat = new ArptransTemp;      
                // $exist->proc_id         = $request->proc_id;
                $exist->chart_type         = $request->chart_type;
                $exist->rnav               = $request->rnav;
                $exist->sub_chart_type     = $request->sub_chart_type;
                $exist->trans_ident        = $request->trans_ident;
                $exist->rwy_id             = $request->rwy_id;
                $exist->rwy_trans          = $request->rwy_trans;
                $exist->rt_type            = $request->rt_type;
                $exist->nav_spec           = $request->nav_spec;
                $exist->status             = $request->status;
                $exist->editor             = $request->editor;
                // $dat->geom               = $request->geom;
                // dd($exist);
                $exist->update(); 
                // $airport = NavaidTemp::find($id);
                $existseg = ArptransegTemp::find($request->id);
                $existseg->update($request->all());
                
            }else if ($request->status=='N'){
                $exist = ArptransTemp::where('proc_id', '=', $request->proc_id)
                ->first();
                if ($exist === null) {
                    $last = ArptransTemp::latest('id')->first();
                    $request->merge([
                        'id' => $last->id + 1,
                    ]);
                   
                    ArptransTemp::create($request->all());
                    // $dat_trans->save();
                }else{
                    $exist->update($request->all());
                }
                $lastseg = ArptransegTemp::latest('id')->first();
                $request->merge([
                    'id' => $lastseg->id + 1,
                ]);
                ArptransegTemp::create($request->all());
               
            }else if ($request->status=='I'){
                $last = ArptransegTemp::latest('id')->first();
                $request->merge([
                    'id' => $last->id + 1,
                    'status' => 'N',
                ]);
                ArptransegTemp::create($request->all());
    
                $seg = ArptransegTemp::where('proc_id', $request->proc_id)
                ->orderby('seq_num', 'asc')->get();
                // dd($seg);
                $no=10;
                foreach ($seg as $key => $value) {
                    $segdata = ArptransegTemp::where('id', $value->id)->first();
                    $seq=$no;
                    $segdata->seq_num = $seq;
                    // dd($segdata);
                    $segdata->update();
                    $no+=10;
                }
                
    
            }else if ($request->status=='D'){
                $trdel = ArptransegTemp::find($request->id);
                $trdel->delete();
    
                $seg = ArptransegTemp::where('proc_id', $request->proc_id)
                ->orderby('seq_num', 'asc')->get();
                // dd($seg);
                $no=10;
                foreach ($seg as $key => $value) {
                    $segdata = ArptransegTemp::where('id', $value->id)->first();
                    $seq=$no;
                    $segdata->seq_num = $seq;
                    // dd($segdata);
                    $segdata->update();
                    $no+=10;
                }
    
            }
           
            $seg = ArptransegTemp::where('proc_id', $request->proc_id)
            ->orderby('seq_num', 'asc')->get();
            // dd($seg);
            $no=10;
            foreach ($seg as $key => $value) {
                if ($value->wd2=='E' || $value->wd2=='B'){
                    $updategeom=true;
                    break;
                }
            }
            if ($updategeom==true){
                drawtransition($request->proc_id);
                // dd('UPDATE GEOM',$request);
            }
            $redirec='/listtranssegment/'.$request->proc_id.'/'.$request->chart_type.'@procedure_'.$request->arpt_ident.'_'.$request->chart_type;
        }
        
        // dd($exist);
        return redirect($redirec);
        // ArptransTemp::create($request->all());

    }
    public function saveminima(Request $request)
	{
        $existseg = ChartMinimaTemp::where('chart_id',$request->chart_id)->first();
        $status=$request->save_status;
        if ($existseg==null){
            $status='N';
        }
        // dd($request,$existseg,$status);
        if ($status=='R'){
            
            $existseg = ChartMinimaTemp::find($request->id);
            // dd( $existseg);
            $existseg->update($request->all());
            
        }else if ($status=='N'){
           
            $lastseg = ChartMinimaTemp::latest('id')->first();
            $request->merge([
                'id' => $lastseg->id + 1,
            ]);
            ChartMinimaTemp::create($request->all());
           
        }
        $airport = AirportTaTl::find($request->chart_arpt_ident);
        // $exist = AirportTaTl::where('arpt_ident', '=', $request->chart_arpt_ident)
        // ->first();
        if ($airport === null) {
            // var_dump('BARUUUU');
            $last = AirportTaTl::latest('id')->first();
            $request->merge([
                'id' => $last->id + 1,
            ]);
           
            AirportTaTl::create($request->all());
            // $dat_trans->save();
        }else{
            // var_dump('ADA');
            $airport->circ_a=$request->circ_a;
            $airport->circ_b=$request->circ_b;
            $airport->circ_c=$request->circ_c;
            $airport->circ_d=$request->circ_d;
            $airport->circ_a_val=$request->circ_a_val;
            $airport->circ_b_val=$request->circ_b_val;
            $airport->circ_c_val=$request->circ_c_val;
            $airport->circ_d_val=$request->circ_d_val;
            $airport->editor=$request->editor;
            // dd('exist-> exist = '. $exist);
            $airport->save(); 
            // $airport->update($request->all());
        }
        

        // $redirec='/chartprop/'.$request->chart_arpt_ident.'/pro';
        return back()->withInput(['tab'=>'tabItem3']);
        
    }
    public function remove(Request $request, string $id)
	{
		$airport = Arptprocedure::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
    }

}
