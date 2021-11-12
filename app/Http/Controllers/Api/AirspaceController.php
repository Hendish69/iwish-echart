<?php
namespace App\Http\Controllers\Api;
use \Illuminate\Support\Facades\Request as Req;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Airspace;
use App\Models\Api\AirspaceClass;
use App\Models\Api\AirspaceSegment;
use App\Models\Api\FreqUsage;
use App\Models\Api\AirspaceTemp;
use App\Models\Api\AirspaceClassTemp;
use App\Models\Api\AirspaceSegmentTemp;
use App\Models\Api\EaipChartContentTemp as eaip;
use App\Models\Api\FreqUsageTemp;
use App\Models\Api\RawdataPub as Raw_Pub;
use App\Models\Api\Airport as arpt;
use App\Models\Api\LocIndicatorTemp;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class AirspaceController extends Controller
{

    public function list(Request $request,  RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, Airspace::query()->select('airspace.*','country.country')
                        ->join('country','country.ident','airspace.ctry')
                        ->where('airspace.deleted', 0)
                        ->orderby('airspace_type','asc')
                        // ->orderby('icao_reg','desc')
                        ->orderby('airspace_name','asc')
                        ->with(['boundary' => function($query) {
                                return $query->with(['navaid'])->with('airport');
                            }
                            // return $query->with('airport');
                        ])
                        ->with(['class'])
                        ->with([
                            'freq' => function($query) {
                                return $query->with(['callsign' => function($query) {
                                    return $query->with(['segment' => function($query){
                                        return $query->with('value');
                                        }
                                    ]);
                                    }
                                ]);
                            }
                        ]));
                        // ->with([
                        //     'freq' => function($query) {
                        //         return $query->with([
                        //             'callsign' => function($query) {
                        //                 return $query->with(['segment'  => function($query) {
                        //                     return $query->with('value');
                        //                     }
                        //                 ]);
                        //             }
                        //         ]);
                        //     }
                        // ]));
                                // ->with(['freq'=> function($query){
                                //     return $query->with([
                                //         'callsign'])
                                //     }
                                // ]);
                                
        // $results=$results::with('aspclass')->get();
		return ApiResponse::success($results);
    }

    public function listputa_x(Request $request,  RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, Airspace::query()->select('airspace_name', 'airspace_type', 'icao_acc','ats_unit', 'geom','airspace_class.upper')
                        ->join('airspace_class','airspace_class.asp_id','airspace.ats_airspace_id')
                        ->where('airspace.deleted', 0)
                        ->where('airspace.ctry', 'ID')
                        ->orderby('airspace_type','asc')
                        ->orderby('airspace_name','asc'));
		return ApiResponse::success($results);
    }

    public function listtemp(Request $request,  RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, AirspaceTemp::query()->select('airspace_temp.*','country.country')
                        ->join('country','country.ident','airspace_temp.ctry')
                        ->where('airspace_temp.deleted', 0)
                        ->orderby('airspace_type','asc')
                        // ->orderby('icao_reg','desc')
                        ->orderby('airspace_name','asc')
                        ->with(['boundary' => function($query) {
                                return $query->with(['navaid'])->with('airport');
                            }
                            // return $query->with('airport');
                        ])
                        ->with(['class'])
                        ->with([
                            'freq' => function($query) {
                                return $query->with(['callsign' => function($query) {
                                    return $query->with(['segment' => function($query){
                                        return $query->with('value');
                                        }
                                    ]);
                                    }
                                ]);
                            }
                        ]));
                        // ->with([
                        //     'freq' => function($query) {
                        //         return $query->with([
                        //             'callsign' => function($query) {
                        //                 return $query->with(['segment'  => function($query) {
                        //                     return $query->with('value');
                        //                     }
                        //                 ]);
                        //             }
                        //         ]);
                        //     }
                        // ]));
                                // ->with(['freq'=> function($query){
                                //     return $query->with([
                                //         'callsign'])
                                //     }
                                // ]);
                                
        // $results=$results::with('aspclass')->get();
		return ApiResponse::success($results);
    }

    public function AspClass(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, AirspaceClass::query()
        ->where('deleted',0));

        return ApiResponse::success($results);
    }
    public function AspClasstemp(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, AirspaceClassTemp::query()
        ->where('deleted',0));

        return ApiResponse::success($results);
    }

    public function AspSeg(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, AirspaceSegment::query()->select('airspace_seg.*','airspace.airspace_name','airspace.airspace_type','cod_ats_shap.definition',DB::raw("(CASE when airspace_seg.nav_id isnull THEN (select CONCAT(icao,' ',arpt_name) from arpt where arpt_ident=airspace_seg.arpt_ident)
        else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=airspace_seg.nav_id) end) as ref_point"))
        ->join('cod_ats_shap','cod_ats_shap.id','airspace_seg.shap')
        ->join('airspace','airspace.ats_airspace_id','airspace_seg.asp_id')
        ->orderBy('air_seq','asc'));


        return ApiResponse::success($results);
    }

    public function AspSegtemp(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, AirspaceSegmentTemp::query()->select('airspace_seg_temp.*','airspace_temp.airspace_name','airspace_temp.airspace_type','cod_ats_shap.definition',DB::raw("(CASE when airspace_seg_temp.nav_id isnull THEN (select CONCAT(icao,' ',arpt_name) from arpt where arpt_ident=airspace_seg_temp.arpt_ident)
        else (select CONCAT(nav_ident,' ',definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=airspace_seg_temp.nav_id) end) as ref_point"))
        ->join('cod_ats_shap','cod_ats_shap.id','airspace_seg_temp.shap')
        ->join('airspace_temp','airspace_temp.ats_airspace_id','airspace_seg_temp.asp_id')
        ->orderBy('airspace_temp.airspace_name','asc'));


        return ApiResponse::success($results);
    }

    public function AspFreq(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, FreqUsage::query()->select('freq.*','freq_value.freq_id','freq_value.id as idd','freq_value.freq','freq_value.unit',
                                    DB::raw("(CASE when freq_value.unit ='V' THEN round(cast(freq_value.freq as decimal) / 1000000,3)
                                    else round(cast(freq_value.freq as decimal) / 1000,1) end) as freq_real"),)
        ->join('freq','freq.id','freq_used.freqid')
        ->join('freq_seg','freq_seg.call_sign','freq.id')
        ->join('freq_value','freq_value.freq_id','freq_seg.freq_id'));

        return ApiResponse::success($results);
    }
    public function AspFreqtemp(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, FreqUsageTemp::query()->select('freq_temp.*','freq_value_temp.freq_id','freq_value_temp.id as idd','freq_value_temp.freq','freq_value_temp.unit',
                                    DB::raw("(CASE when freq_value_temp.unit ='V' THEN round(cast(freq_value_temp.freq as decimal) / 1000000,3)
                                    else round(cast(freq_value_temp.freq as decimal) / 1000,1) end) as freq_real"),)
        ->join('freq_temp','freq_temp.id','freq_used_temp.freqid')
        ->join('freq_seg_temp','freq_seg_temp.call_sign','freq_temp.id')
        ->join('freq_value_temp','freq_value_temp.freq_id','freq_seg_temp.freq_id'));

        return ApiResponse::success($results);
    }

    public function AspRemarks(Request $request,string $pid)
	{
        $ats=DB::table('ats_rem')->select('ats_rem.remarks as asp_rem')
        ->where('tbl','airspace')
        ->where('airspace_id',$pid)
        ->get();

    return ApiResponse::success($ats);
    }
    public function AspMenu(Request $request)
	{
        $ats=DB::table('airspace')->select('airspace_type')
        ->where('ctry','ID')
        ->groupBy('airspace_type')
        ->orderBy('airspace_type')
        ->get();

    return ApiResponse::success($ats);
    }

    public function findingAts(Request $request)
    {

        foreach ($request->all() as $field => $find) {
            // echo $find;
			if ($field == 'a')  {
				$a=$find;
			}else if ($field == 'b'){
                $b=$find;
            }else if ($field == 'c'){
                $c=$find;
            }else if ($field == 'd'){
                $d=$find;
            }else if ($field == 'e'){
                $e=$find;
            }else if ($field == 'f'){
                $f=$find;
            }


        }

        $sqry = "select * from (select a.ats_airspace_id, Case when substring(b.upper from 1 for 2) = 'FL' then cast(substring(b.upper from 3 for 3)as float)  * 100
                when b.upper= 'SFC' then 0
                when b.upper= '' then 0
                when b.upper= null then 0
                else (substring(b.upper, '^[0-9]+')) :: float
                End  as upper1,
                Case
                when substring(b.lower from 1 for 2) = 'FL' then cast(substring(b.lower from 3 for 3)as float)  * 100
                when b.lower='' or b.lower=null or b.lower= 'GND' or b.lower= 'SFC' or b.lower= 'GND/Water' or b.lower= 'GND/WATER' then 0
                else (substring(b.lower, '^[0-9]+')) :: float
                End  as lower1,a.ats_unit||' ('||a.airspace_name||')' as Name,a.airspace_type as Type,b.upper as Upper,b.lower as Lower,b.asp_class as Class,b.asp_sector as Sector
                from airspace_temp a inner join airspace_class_temp b
                on b.asp_id=a.ats_airspace_id
                inner join cod_airspace_code g on a.airspace_code=g.id
                where (ST_crosses(st_geomfromtext('LINESTRING($a $b,$c $d)'),a.geom) or ST_Within(st_geomfromtext('LINESTRING($a $b,$c $d)'),a.geom)) and a.deleted=0 and a.ctry='ID'
                order by g.seq, a.airspace_code, a.airspace_name) as aa
                where lower1 is not null and ($f < lower1 or $f < upper1)";

        $sqry = DB::select(DB::raw($sqry));
        return ApiResponse::success($sqry);

    }

    public function update(Request $request, string $id)
	{
		$ats = Airspace::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }


    public function save(Request $request)
	{
        // dd($request);
        $ret_msg='';$parent='ENR2.1';$redire='';
    //belum di sambungkan dengan AD 2.17 jika type asp = AFIZ,ATZ,CTR
        if ($request->status=='R'){
            $id=$request->id;
            $asp = AirspaceTemp::find($id);

            $asp->update($request->all());
			$ret_msg='Update Data Success';
        }else if ($request->status=='D'){
            $id=$request->id;
            $aspr = AirspaceTemp::find($id);
            $aspr->deleted = 1;
            if ($request->parent =='ENR2.1'){
                $redire='listairpace/edit';
            }else{
                $redire='edit217/'.$request->arpt_ident;
            }
            $aspr->save();
        }else{
            $last = AirspaceTemp::latest('id')->first();
            $icao=substr($request->icao_acc,0,2);
            $aspidlast = AirspaceTemp::where('ats_airspace_id','like',$icao.'%')
            ->orderby('ats_airspace_id', 'desc')
            ->limit(1)->first();
            if ($aspidlast ==null){
                $aspid=substr($icao,0,2).'00001';
            }else{
                $str = $aspidlast->ats_airspace_id;
                preg_match_all('!\d+!', $str, $matches);
                $hmt = (int)$matches[0][0];
                $hmt++;
                $aspid= $icao.sprintf("%05d", $hmt);
                // dd($hmt,$matches[0][0],$aspid);
            }
            // dd($aspidlast,$last,$icao,$aspid);
            $request->id = $last->id + 1;
            $request->ats_airspace_id=$aspid;
            $request->merge([
                'id' => $last->id + 1,
                'ats_airspace_id' => $aspid
            ]);
			AirspaceTemp::create($request->all());
        }
        // dd($request->airspace_type);
        switch ($request->airspace_type) {
            case 'AFIZ':
            case 'ATZ':
            case 'CTR':
                $rawdata['tablename']='arpt';
                $rawdata['fieldname']='arpt_ident';
                $rawdata['fieldid']=$request->arpt_ident;
                $rawdata['status_raw']=0;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

                // $raw_dat = Raw_Pub::where('tablename', 'arpt')
                // ->where('fieldname', 'arpt_ident')
                // ->where('fieldid',$request->arpt_ident)
                // ->where('status_raw','<=',70)
                // // ->where('status_raw','<', 100)
                // ->first();
                //     if ($raw_dat === null) {
                //         $raw_dat = new Raw_Pub;
                //         $raw_dat->tablename = 'arpt';
                //         $raw_dat->fieldname = 'arpt_ident';
                //         $raw_dat->fieldid = $request->arpt_ident;
                //         $raw_dat->status_raw = 0;
                //     }
                //     // dd($raw_dat);
                //     $raw_dat->ori_change_pic = $request->editor;
                //     $raw_dat->save();

                    $this->createeaipad217($request->arpt_ident,$request->editor);
                
                // dd($asplist,$eaip96->content,$eaip220->content,$eaip221->content,$eaip222->content,$eaip236->content);


                break;
            case 'TIBA':
                    break;
            default:
                $rawdata['tablename']='ENR';
                $rawdata['fieldname']='sub_id';
                $rawdata['fieldid']='ENR 2.1';
                $rawdata['status_raw']=50;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

                if ($request->airspace_type=='FIR'){
                    $locind = LocIndicatorTemp::where('loc_arptident','=',$request->ats_airspace_id)
                    ->where('tbl', 'ASP')
                    ->first();
                    if($exist){
							$exist->city= $request->airspace_name.' '.$request->airspace_type; 
							$exist->status ='R';
							$exist->editor=$request->editor;
							// dd('exist-> exist = '. $exist);
							$exist->save();

                            $rawdata['tablename']='GEN';
                            $rawdata['fieldname']='sub_id';
                            $rawdata['fieldid']='GEN 2.4';
                            $rawdata['status_raw']=50;
                            $rawdata['ori_change_pic']= $request->editor;
                            saveDataRaw($rawdata);
                    }
                    
                }
                // $raw_dat = Raw_Pub::where('tablename', 'ENR')
                // ->where('fieldname', 'sub_id')
                // ->where('fieldid','ENR 2.1')
                // ->where('status_raw','<=',70)
                // // ->where('status_raw','<', 100)
                // ->first();
                //     if ($raw_dat === null) {
                //         $raw_dat = new Raw_Pub;
                //         $raw_dat->tablename = 'ENR';
                //         $raw_dat->fieldname = 'sub_id';
                //         $raw_dat->fieldid = 'ENR 2.1';
                //         $raw_dat->status_raw = 50;
                //     }
                //     // dd($raw_dat);
                //     $raw_dat->ori_change_pic = $request->editor;
                //     $raw_dat->save();
                break;
        }
        if ($request->parent !=='ENR2.1'){
            return redirect('/edit217/'.$request->arpt_ident);
        }else{
            if ($request->status=='D'){
                return redirect($redire);
            }else{
                return redirect('/airspace/'.$request->ats_airspace_id.'@edit@'.$parent);
                
            }
        }

    }
    function createeaipad217($arptident,$editor){
                $originalInput=Req::input();
                $asplist = getDataApi($originalInput,'api/airspace/temp/list?arpt_ident='.$arptident.'&deleted=0&sort=airspace_type:asc');
                $ccount=count($asplist);
                // dd(count($asplist));
                $nm='';$vlim='';$acls='';$aunit='';$ahrs='';$cls='';
                foreach ($asplist as $key => $asp) {
                    if ($nm==''){
                        $nm=$asp->airspace_name.' '.$asp->airspace_type.' : ';
                    }else{
                        $nm=$nm.PHP_EOL.$asp->airspace_name.' '.$asp->airspace_type.' : ';
                    }
                    $textasp=GetSegmentText($asp,$asp->airspace_type).PHP_EOL;
                    $nm=$nm.PHP_EOL.$textasp;
                    if ($asp->class){
                        $lower=strtoupper($asp->class[0]->lower);strtoupper($upper=$asp->class[0]->upper);
                        if ($asp->class[0]->lower=='GND' || $asp->class[0]->lower=='SFC' || $asp->class[0]->lower=='GND/WATER'){
                            $lower='GND/Water up to ';
                        }else{
                            $lower=$asp->class[0]->lower.'ft up to ';
                        }
                        if (substr($upper,0,2) !== 'FL'){
                            $upper=$asp->class[0]->upper.'ft';
                        }
                        if ($vlim==''){
                            if ($ccount==1){
                                $vlim= $lower.$upper;
                            }else{
                                $vlim= $asp->airspace_name.' '.$asp->airspace_type.' : '.$lower.$upper;
                            }
                        
                        }else{
                            $vlim=$vlim.PHP_EOL.$asp->airspace_name.' '.$asp->airspace_type.' : '.$lower.$upper;
                        }
                        if ($acls=='' || $cls == $asp->class[0]->asp_class){
                            $acls= $asp->class[0]->asp_class;
                        }else{
                            $acls=$acls.PHP_EOL.$asp->class[0]->asp_class;
                        }
                        $cls= $asp->class[0]->asp_class;
                    }
                    // dd($asp->freq);
                    if ($asp->freq){
                        if ($aunit==''){
                            if ($ccount==1){
                                $aunit= $asp->freq[0]->callsign[0]->call_sign;
                            }else{
                                $aunit= $asp->airspace_name.' '.$asp->airspace_type.' : '.$asp->freq[0]->callsign[0]->call_sign;
                            }
                        
                        }else{
                            $aunit=$aunit.PHP_EOL.$asp->airspace_name.' '.$asp->airspace_type.' : '.$asp->freq[0]->callsign[0]->call_sign;
                        }
                    }else{
                        $aunit=$asp->ats_unit;
                    }
                    // $asplist = AirspaceSegmentTemp::where('asp_id','=',$asp->ats_airspace_id)->orderby('air_seq', 'asc')->get();
                    
                }
                // dd($nm);
                $eaip96=eaip::where('arpt_ident','=',$arptident)->where('category_id','=',96)->first();
                if ($eaip96==null){
                    $eaip96 = new eaip;
                    $eaip96->category_id=96;
                    $eaip96->arpt_ident=$arptident;
                    $eaip96->content=$nm;
                    $eaip96->sequence=0;
                    $eaip96->editor = $editor;
                    $eaip96->status='N';
                }else{
                    $eaip96->content=$nm;
                    $eaip96->editor = $editor;
                    $eaip96->status='R';
                }
                $eaip96->save();

                $eaip220=eaip::where('arpt_ident','=',$arptident)->where('category_id','=',220)->first();
                if ($eaip220==null){
                    $eaip220 = new eaip;
                    $eaip220->category_id=220;
                    $eaip220->arpt_ident=$arptident;
                    $eaip220->content=$vlim;
                    $eaip220->sequence=0;
                    $eaip220->editor = $editor;
                    $eaip220->status='N';
                }else{
                    $eaip220->content=$vlim;
                    $eaip220->editor = $editor;
                    $eaip220->status='R';
                }
                $eaip220->save();

                $eaip221=eaip::where('arpt_ident','=',$arptident)->where('category_id','=',221)->first();
                if ($eaip221==null){
                    $eaip221 = new eaip;
                    $eaip221->category_id=221;
                    $eaip221->arpt_ident=$arptident;
                    $eaip221->content=$acls;
                    $eaip221->sequence=0;
                    $eaip221->editor = $editor;
                    $eaip221->status='N';
                }else{
                    $eaip221->content=$acls;
                    $eaip221->editor = $editor;
                    $eaip221->status='R';
                }
                $eaip221->save();

                $eaip222=eaip::where('arpt_ident','=',$arptident)->where('category_id','=',222)->first();
                if ($eaip222==null){
                    $eaip222 = new eaip;
                    $eaip222->category_id=222;
                    $eaip222->arpt_ident=$arptident;
                    $eaip222->content=$aunit;
                    $eaip222->sequence=0;
                    $eaip222->editor = $editor;
                    $eaip222->status='N';
                }else{
                    $eaip222->content=$aunit;
                    $eaip222->editor = $editor;
                    $eaip222->status='R';
                }
                $eaip222->save();

                $eaip223=eaip::where('arpt_ident','=',$arptident)->where('category_id','=',223)->first();
                if ($eaip223==null){
                    $eaip223 = new eaip;
                    $eaip223->category_id=223;
                    $eaip223->arpt_ident=$arptident;
                    $eaip223->content='English';
                    $eaip223->sequence=0;
                    $eaip223->editor = $editor;
                    $eaip223->status='N';
                }else{
                    $eaip223->content='English';
                    $eaip223->editor = $editor;
                    $eaip223->status='R';
                }
                $eaip223->save();
                $arpt224=arpt::where('arpt_ident','=',$arptident)->first();
                $lon1=$arpt224->geom->getlng();
                $tatl='11 000 ft / FL130';
                if ($lon1 > 135){
                    $tatl='18 000 ft / FL180';
                }
                $eaip224=eaip::where('arpt_ident','=',$arptident)->where('category_id','=',224)->first();
                if ($eaip224==null){
                    $eaip224 = new eaip;
                    $eaip224->category_id=224;
                    $eaip224->arpt_ident=$arptident;
                    $eaip224->content=$tatl;
                    $eaip224->sequence=0;
                    $eaip224->editor = $editor;
                    $eaip224->status='N';
                }else{
                    $eaip224->content=$tatl;
                    $eaip224->editor = $editor;
                    $eaip224->status='R';
                }
                $eaip224->save();
                // dd($lon1);
                // dd($arpt224);
                $eaip236=eaip::where('arpt_ident','=',$arptident)->where('category_id','=',236)->first();
                // dd($asplist,$eaip96->content,$eaip220->content,$eaip221->content,$eaip222->content,$eaip236->content);
    }
    public function asptemp1(Request $request,  RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Temp1::query());
                

        return ApiResponse::success($results);
    }
    public function savetemp1(Request $request)
	{
        Temp1::create($request->all());

    }
    public function removetemp1(Request $request, string $id)
	{
        $ats = Temp1::find($id);

		if (null === $ats) {
			return ''; //ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function remove(Request $request, string $id)
	{
        $airport = Airspace::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();
    }

    public function updateseg(Request $request, string $id)
	{
		$ats = AirspaceSegment::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }


    public function saveseg(Request $request)
	{
        $ret_msg='';
        // dd($request);
        if ($request->status=='R'){
            $id=$request->id;
            $asp = AirspaceSegmentTemp::find($id);
            $asp->update($request->all());
            $ret_msg='Update Data Success';
            $seg = AirspaceSegmentTemp::where('asp_id', $request->asp_id)
            ->orderby('air_seq', 'asc')->get();
            $akh=count($seg)-1;
            if ($seg[0]->point1_lat !== $seg[$akh]->point1_lat || $seg[0]->point1_long !== $seg[$akh]->point1_long){
                if ($seg[0]->point1_lat == $request->point1_lat || $seg[0]->point1_long == $request->point1_long){
                    $asp_dat = AirspaceSegmentTemp::where('asp_seg_id', $seg[$akh]->asp_seg_id)->first();
                }else{
                    $asp_dat = AirspaceSegmentTemp::where('asp_seg_id', $seg[0]->asp_seg_id)->first();
                }
                $asp_dat->point1_lat = $seg[0]->point1_lat;
                $asp_dat->point1_long = $seg[0]->point1_long;
                $asp_dat->save();
            }
            if ( $request->shap !== 'C'){

                if ($request->latlama !== $request->point1_lat || $request->lonlama !== $request->point1_long || $request->arclatlama !== $request->arc_lat || $request->arclonlama !== $request->arc_long){
                    unset($seg);
                    $seg = AirspaceSegmentTemp::where('asp_id', $request->asp_id)
                    ->orderby('air_seq', 'asc')->get();
                    // dd($seg[0]->point1_lat,$seg[0]->point1_long,$seg[$akh]->point1_lat,$seg[$akh]->point1_long);
                    // dd($seg);
                    $hasil=CreateApSegment($seg);
                    $aspgeom = AirspaceTemp::where('ats_airspace_id', $request->asp_id)->first();
                
                    $aspgeom->geom = $hasil;
                    $aspgeom->status ='R';
                    $aspgeom->save();
                    if ($request->saveother=='Y'){
                        $allseg = AirspaceSegmentTemp::where('point1_lat', $request->latlama)->where('point1_long', $request->lonlama)
                        ->orderby('air_seq', 'asc')->get();
                        foreach ($allseg as $key => $value) {
                            $suas_dat = AirspaceSegmentTemp::where('asp_seg_id', $value->asp_seg_id)->first();
                            $suas_dat->point1_lat = $request->point1_lat;
                            $suas_dat->point1_long = $request->point1_long;
                            $suas_dat->save();
                        }
        
                        foreach ($allseg as $key => $value) {
                            $suas_seg = AirspaceSegmentTemp::where('asp_id', $value->asp_id)->orderby('air_seq', 'asc')->get();
                            unset($akh);
                            $akh=count($suas_seg)-1;
                            if ($suas_seg[0]->point1_lat !== $suas_seg[$akh]->point1_lat && $suas_seg[0]->point1_long !== $suas_seg[$akh]->point1_long){
                                unset($asp_dat);
                                $asp_dat = AirspaceSegmentTemp::where('asp_seg_id', $suas_seg[$akh]->asp_seg_id)->first();
                                $asp_dat->point1_lat = $suas_seg[0]->point1_lat;
                                $asp_dat->point1_long = $suas_seg[0]->point1_long;
                                $asp_dat->save();
                                $suas_seg = AirspaceSegmentTemp::where('asp_id', $value->asp_id)->orderby('air_seq', 'asc')->get();
                            }
                            $hasilother=CreateApSegment($suas_seg);
                            $suas_temp = AirspaceTemp::where('ats_airspace_id', $value->asp_id)->first();
                            $suas_temp->geom = $hasilother;
                            $suas_temp->status = 'R';
                            $suas_temp->save();
                        }
                        
                    }
                    // dd($allseg,$hasil);
                }
            }
            
        }else if ($request->status=='N'){
            $last = AirspaceSegmentTemp::latest('id')->first();
            $request->id = $last->id + 1;
  
            $request->merge([
                'id' => $last->id + 1,
            ]);
            AirspaceSegmentTemp::create($request->all());
            // sleep(10);
            unset($seg);
            $seg = AirspaceSegmentTemp::where('asp_id', $request->asp_id)
            ->orderby('air_seq', 'asc')->get();
            dd($request,$seg);
            // dd($seg);
                unset($seq1);
                $seq1=100000;
                foreach ($seg as $key => $value) {
                    $segdata = AirspaceSegmentTemp::where('asp_seg_id', $value->asp_seg_id)->first();
                    $segdata->air_seq = $seq1;
                    $segdata->asp_seg_id ='BDRY_'. $request->asp_id.'_'.sprintf("%06d", $seq1);
                    // var_dump($segdata);
                    $segdata->update();
                    $seq1+=10;
                }
                sleep(5);
                unset($seg);
                $seg = AirspaceSegmentTemp::where('asp_id', $request->asp_id)
                ->orderby('air_seq', 'asc')->get();
                unset($seq);
                $seq=10;
                foreach ($seg as $key => $value) {
                    $segdata = AirspaceSegmentTemp::where('asp_seg_id', $value->asp_seg_id)->first();
                    $segdata->air_seq = $seq;
                    $segdata->asp_seg_id ='BDRY_'. $request->asp_id.'_'.sprintf("%06d", $seq);
                    // var_dump($segdata);
                    $segdata->update();
                    $seq+=10;
                }
                sleep(5);
                $hasil=CreateApSegment($seg);
                $aspgeom = AirspaceTemp::where('ats_airspace_id', $request->asp_id)->first();
                //  dd($aspgeom);
                $aspgeom->geom = $hasil;
                $aspgeom->status ='R';
                $aspgeom->save();

            // $seg = AirspaceSegmentTemp::where('asp_id', $request->asp_id)
            //     ->orderby('air_seq', 'asc')->get();
            //     $hasil=CreateApSegment($seg);
            //     $aspgeom = AirspaceTemp::where('ats_airpspace_id', $request->asp_id)->first();
        
            //     $aspgeom->geom = $hasil;
            //     $aspgeom->status ='R';
            //     $aspgeom->save();
        }else if ($request->status=='D'){
            $id=$request->id;
            $navaid =AirspaceSegmentTemp::query()->where('id',$id)->delete();

            $seg = AirspaceSegmentTemp::where('asp_id', $request->asp_id)
            ->orderby('air_seq', 'asc')->get();
            // dd($seg);
                $hasil=CreateApSegment($seg);
                $aspgeom = AirspaceTemp::where('ats_airspace_id', $request->asp_id)->first();
                //  dd($aspgeom);
                $aspgeom->geom = $hasil;
                $aspgeom->status ='R';
                $aspgeom->save();
                $no=10;
                foreach ($seg as $key => $value) {
                    $segdata = AirspaceSegmentTemp::where('asp_seg_id', $value->asp_seg_id)->first();
                    
                    $seq=$no;
                    $segdata->air_seq = $seq;
                    $segdata->asp_seg_id ='BDRY_'. $request->asp_id.'_'.sprintf("%06d", $seq);
                    $segdata->update();
                    $no+=10;
                }
        }else if ($request->status=='DELFREQ'){
            $navaid =FreqUsageTemp::query()->where('asp_id',$request->asp_id)->delete(); 
        }
        // dd($request);

        // $aspgeom = AirspaceTemp::where('ats_airspace_id', $request->asp_id)->first();

        // $aspgeom->geom = $hasil;
        // $aspgeom->status ='R';
        // $aspgeom->save();
        $pprent='';
        switch ($request->airspace_type) {
            case 'AFIZ':
            case 'ATZ':
            case 'CTR':
                $pprent= $request->arpt_ident_seg;

                $rawdata['tablename']='arpt';
                $rawdata['fieldname']='arpt_ident';
                $rawdata['fieldid']=$pprent;
                $rawdata['status_raw']=0;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

                // $raw_dat = Raw_Pub::where('tablename', 'arpt')
                // ->where('fieldname', 'arpt_ident')
                // ->where('fieldid',$pprent)
                // ->where('status_raw','<=',70)
                // // ->where('status_raw','<', 100)
                // ->first();
                //     if ($raw_dat === null) {
                //         $raw_dat = new Raw_Pub;
                //         $raw_dat->tablename = 'arpt';
                //         $raw_dat->fieldname = 'arpt_ident';
                //         $raw_dat->fieldid = $pprent;
                //         $raw_dat->status_raw = 0;
                //     }
                //     // dd($raw_dat);
                //     $raw_dat->ori_change_pic = $request->editor;
                //     $raw_dat->save();

                    $this->createeaipad217($pprent,$request->editor);

                break;
            case 'TIBA':
                break;
            default:
            $pprent= 'ENR2.1';
            $rawdata['tablename']='ENR';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']='ENR 2.1';
            $rawdata['status_raw']=50;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);

                // $raw_dat = Raw_Pub::where('tablename', 'ENR')
                // ->where('fieldname', 'sub_id')
                // ->where('fieldid','ENR 2.1')
                // ->where('status_raw','<=',70)
                // // ->where('status_raw','<', 100)
                // ->first();
                //     if ($raw_dat === null) {
                //         $raw_dat = new Raw_Pub;
                //         $raw_dat->tablename = 'ENR';
                //         $raw_dat->fieldname = 'sub_id';
                //         $raw_dat->fieldid = 'ENR 2.1';
                //         $raw_dat->status_raw = 50;
                //     }
                //     // dd($raw_dat);
                //     $raw_dat->ori_change_pic = $request->editor;
                //     $raw_dat->save();
                break;
        }
        if ($request->arpt_ident=='ENR2.1'){
            $parent='/airspace/'.$request->asp_id.'@edit@ENR2.1';
        }else{
            $parent='/edit217/'.$pprent;
        }
        return redirect('/airspace/'.$request->asp_id.'@edit@'.$pprent);
            // return redirect('/listairpace/edit');
        
        // $temp = AirspaceSegment::create($request->all());

        // return ApiResponse::success($temp->id);


    }

    public function removeseg(Request $request, string $id)
	{
        
        
        $ats = AirspaceSegment::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function updateclass(Request $request, string $id)
	{
		$ats = AirspaceClass::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }


    public function saveclass(Request $request)
	{
        
        // dd($request);
        $ret_msg='';
    
        if ($request->status=='R'){
            $id=$request->id;
            $asp = AirspaceClassTemp::find($id);
            $asp->update($request->all());
			$ret_msg='Update Data Success';
        }else{
            $last = AirspaceClassTemp::latest('id')->first();
            $request->merge([
                'id' => $last->id + 1,
            ]);
            // dd($request);
			AirspaceClassTemp::create($request->all());
        }

        $aspgeom = AirspaceTemp::where('ats_airspace_id', $request->asp_id)->first();
        $aspgeom->status ='R';
        $aspgeom->save();
        if ($request->parent=='ENR2.1'){
            $parent='/airspace/'.$request->asp_id.'@edit@ENR2.1';
        }else{
            $parent='/edit217/'.$request->arpt_ident;
        }
        switch ($request->airspace_type) {
            case 'AFIZ':
            case 'ATZ':
            case 'CTR':
                $rawdata['tablename']='arpt';
                $rawdata['fieldname']='arpt_ident';
                $rawdata['fieldid']=$request->arpt_ident;
                $rawdata['status_raw']=0;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

                // $raw_dat = Raw_Pub::where('tablename', 'arpt')
                // ->where('fieldname', 'arpt_ident')
                // ->where('fieldid',$request->arpt_ident)
                // ->where('status_raw','<=',70)
                // // ->where('status_raw','<', 100)
                // ->first();
                //     if ($raw_dat === null) {
                //         $raw_dat = new Raw_Pub;
                //         $raw_dat->tablename = 'arpt';
                //         $raw_dat->fieldname = 'arpt_ident';
                //         $raw_dat->fieldid = $request->arpt_ident;
                //         $raw_dat->status_raw = 0;
                //     }
                //     // dd($raw_dat);
                //     $raw_dat->ori_change_pic = $request->editor;
                //     $raw_dat->save();
                    $this->createeaipad217($request->arpt_ident,$request->editor);
                    

                break;
            case 'TIBA':
                break;
            default:
                $rawdata['tablename']='ENR';
                $rawdata['fieldname']='sub_id';
                $rawdata['fieldid']='ENR 2.1';
                $rawdata['status_raw']=50;
                $rawdata['ori_change_pic']= $request->editor;
                saveDataRaw($rawdata);

                // $raw_dat = Raw_Pub::where('tablename', 'ENR')
                // ->where('fieldname', 'sub_id')
                // ->where('fieldid','ENR 2.1')
                // ->where('status_raw','<=',70)
                // // ->where('status_raw','<', 100)
                // ->first();
                //     if ($raw_dat === null) {
                //         $raw_dat = new Raw_Pub;
                //         $raw_dat->tablename = 'ENR';
                //         $raw_dat->fieldname = 'sub_id';
                //         $raw_dat->fieldid = 'ENR 2.1';
                //         $raw_dat->status_raw = 50;
                //     }
                //     // dd($raw_dat);
                //     $raw_dat->ori_change_pic = $request->editor;
                //     $raw_dat->save();
                break;
        }
      
            
            return redirect($parent);
        
        // $temp = AirspaceClass::create($request->all());

        // return ApiResponse::success($temp->id);


    }

    public function removeclass(Request $request, string $id)
	{        
        $ats = AirspaceClassTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function updatefreq(Request $request, string $id)
	{
		$ats = AirspaceFreq::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }


    public function savefreq(Request $request)
	{
        $temp = AirspaceFreq::create($request->all());

        return ApiResponse::success($temp->id);


    }

    public function removefreq(Request $request, string $id)
	{
        
        
        $ats = AirspaceFreq::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }
}
