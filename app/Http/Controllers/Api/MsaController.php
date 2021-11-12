<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Msa;
use App\Models\Api\MsaArea;
use App\Models\Api\MsaSegment;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class MsaController extends Controller
{
	public function list(Request $request, RequestParamHandler $rpm)
	{
        $results = $rpm->process($request, Msa::query()
        ->with(['chart' => function($query) {
            return $query->with(['aip']);
                }
            ])
        ->with(['navaid'])
        ->with(['airport'])
        ->with(['waypoint'])
        ->with(['area' => function($query) {
            return $query->with([
                'segment' => function($query) {
                    return $query->with('airport')->with('navaid');
                }
            ]);
        }])
                        -> select('msa.*',
                        DB::raw("(CASE when arpt_ident isnull THEN (select concat(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=msa.nav_id) else (select concat(arpt_name,' ARP') from arpt where arpt_ident=msa.arpt_ident) end) as fix_point"))
                        ->where('msa.deleted',0)
                        ->orderby('msa.ident'));

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
	public function search(Request $request,string $id)
	{

    $results=Msa::query()->select('waypoint.*','country.country')
                        ->join('country','country.ident','=','waypoint.ctry')
                        ->where('wpt_name','like',"{$id}%")
                        ->where('deleted', 0)
                        // ->like('wpt_name',$cari)
                        ->get();

		return ApiResponse::success($results);
    }

    public function save(Request $request)
	{
        $jml=$request->jumlaharea;
        
        // if ($request->status !=='D'){
        //     if ($jml==1){
        //         $geom=GetCircle($request->cen_lat,$request->cen_lon,$request->rad);
                
        //     }else{

        //         for ($i=1; $i <= $jml ; $i++) {
        //             $msaareaid=$request['msa_area_id_'.$i];
        //             $msaarea=MsaArea::where('msa_area_id','=',$msaareaid)->first();
        //             $alt=$request['alt_'.$i];
        //             $area=$request['area_'.$i];
        //             $radius=$request['radius_'.$i];
        //             $bearing1=$request['bearing1_'.$i];
        //             $bearing2=$request['bearing2_'.$i];
        //             $brg1=GetRadial($bearing1);
        //             $brg2=GetRadial($bearing2);
        //             unset($pnt1);unset($pnt2);
        //             $pnt1=GetPoint2($request->cen_lat,$request->cen_lon,$brg1,$radius);
        //             $pnt2=GetPoint2($request->cen_lat,$request->cen_lon,$brg2,$radius);
        //             $hasil=GetArc($request->cen_lat,$request->cen_lon,$radius,$pnt1['latitude'],$pnt1['longitude'],$pnt2['latitude'],$pnt2['longitude'],'R');
        //             $hasil='POLYGON(('.$hasil.'))';
        //             var_dump($brg1,$brg2);
        //         }
        //     }
        // }
        
        // dd($hasil);
        // // dd(GetRadial(318));
        // dd($request);


        if ($request->status=='R'){
            $msa_dat = Msa::find($request->id);
            $msa_dat->update($request->all());
            
            for ($i=1; $i <= $jml ; $i++) {
                $msaareaid=$request['msa_area_id_'.$i];
                $msaarea=MsaArea::where('msa_area_id','=',$msaareaid)->first();
                $alt=$request['alt_'.$i];
                $area=$request['area_'.$i];
                $radius=$request['radius_'.$i];
                unset($bearing1);unset($bearing2);
                $bearing1=$request['bearing1_'.$i];
                $bearing2=$request['bearing2_'.$i];
                // dd($msaarea);
                if ($jml==1){
                    unset($aa);unset($geom);
                    $geom=GetCircle($request->cen_lat,$request->cen_lon,$request->rad);
                    $aa=explode(',',$geom);
                    $geom='POLYGON(('.$geom.','.$aa[0].'))';
                }else{
                    unset($brg1);unset($brg2);
                    $brg1=GetRadial($bearing1);
                    $brg2=GetRadial($bearing2);
                    unset($pnt1);unset($pnt2);
                    $cent=$request->cen_lon.' '.$request->cen_lat;
                    $pnt1=GetPoint2($request->cen_lat,$request->cen_lon,$brg1,$radius);
                    $pnt2=GetPoint2($request->cen_lat,$request->cen_lon,$brg2,$radius);
                    // dd($brg1,$brg2,$request->cen_lat,$request->cen_lon,$pnt1,$pnt2);
                    unset($hasil);unset($geom);
                    $hasil=GetArc($request->cen_lat,$request->cen_lon,$radius,$pnt1['latitude'],$pnt1['longitude'],$pnt2['latitude'],$pnt2['longitude'],'R');
                    $geom='POLYGON(('.$cent.','.$hasil.','.$cent.'))';

                    // var_dump($brg1,$brg2);
                }
                if ($msaarea==null){
                    $msaarea=new MsaArea;
                    $msaarea->msa_area_id=$msaareaid;
                    $msaarea->msa_id=$request->msa_id;
                    $msaarea->geom=$geom;
                    $msaarea->area=$area;
                    $msaarea->alt=$alt;
                    $msaarea->save();
                }else{
                    $msaarea->alt=$alt;
                    $msaarea->geom=$geom;
                    $msaarea->area=$area;
                    $msaarea->save();
                }
                if ($jml==1){
                    $msaseg=MsaSegment::where('msa_area_id','=',$msaareaid)
                    ->where('center_id','=',$request->center_id)
                    ->where('seq','=',10)->first();
                    if ($msaseg==null){
                        $msaseg=new MsaSegment;
                        $msaseg->msa_area_id=$msaareaid;
                        $msaseg->seq=10;
                        $msaseg->center_id=$request->center_id;
                        $msaseg->shap='C';
                        $msaseg->radius=$radius;
                        $msaseg->bearing='0';
                        $msaseg->bearing1='0';
                        $msaseg->save();
                    }else{
                        $msaseg->center_id=$request->center_id;
                        $msaseg->seq=10;
                        $msaseg->shap='C';
                        $msaseg->radius=$radius;
                        $msaseg->bearing='0';
                        $msaseg->bearing1='0';
                        $msaseg->save();
                    }
                }else{
                    for ($s=1; $s < 4 ; $s++) {
                        switch ($s) {
                            case 1:
                                $bear1='0';
                                $bear2=$bearing1;
                                $shap='P';
                                break;
                            case 2:
                                $bear1=$bearing1;
                                $bear2=$bearing2;
                                $shap='R';
                                break;
                            case 3:
                                $bear1=$bearing2;
                                $bear2=$bearing2;
                                $shap='P';
                                break;
                        }
                        $msaseg=MsaSegment::where('msa_area_id','=',$msaareaid)
                        ->where('center_id','=',$request->center_id)
                        ->where('seq','=',$s*10)->first();
                        // dd($msaseg);
                        if ($msaseg==null){
                            $msaseg=new MsaSegment;
                            $msaseg->msa_area_id=$msaareaid;
                            $msaseg->seq=$s*10;
                            $msaseg->center_id=$request->center_id;
                            $msaseg->shap=$shap;
                            $msaseg->radius=$radius;
                            $msaseg->bearing=$bear1;
                            $msaseg->bearing1=$bear2;
                            $msaseg->save();
                        }else{
                            $msaseg->center_id=$request->center_id;
                            $msaseg->seq=$s*10;
                            $msaseg->shap=$shap;
                            $msaseg->radius=$radius;
                            $msaseg->bearing=$bear1;
                            $msaseg->bearing1=$bear2;
                            $msaseg->save();
                        }
    
                    }
                }
            
                // dd($request['alt_'.$i]);
            }
            
        }else if ($request->status=='N'){
            $newid = Msa::where('nav_id','=',$request->center_id)
            ->orwhere('arpt_ident','=',$request->center_id)
            ->orwhere('wpt_id','=',$request->center_id)->get();
            $ccm=count($newid)+1;
            // $request->msa_id=$request->msa_id.'_'.$ccm;
            
            $last = Msa::latest('id')->first();
            $request->merge([
                'id' => $last->id + 1,
                'msa_id' =>$request->msa_id.'_'.$ccm
            ]);
            // dd($request);
            // $request->id = $last->id + 1;
            Msa::create($request->all());
            for ($i=1; $i <= $jml ; $i++) {
                $msaareaid=$request->msa_id.'_AREA_'.$i;
                $alt=$request['alt_'.$i];
                $area=$request['area_'.$i];
                $radius=$request['radius_'.$i];
                $bearing1=$request['bearing1_'.$i];
                $bearing2=$request['bearing2_'.$i];
                if ($jml==1){
                    unset($aa);unset($geom);
                    $geom=GetCircle($request->cen_lat,$request->cen_lon,$request->rad);
                    $aa=explode(',',$geom);
                    $geom='POLYGON(('.$geom.','.$aa[0].'))';
                }else{
                    unset($brg1);unset($brg2);
                    $brg1=GetRadial($bearing1);
                    $brg2=GetRadial($bearing2);
                    unset($pnt1);unset($pnt2);
                    $cent=$request->cen_lon.' '.$request->cen_lat;
                    $pnt1=GetPoint2($request->cen_lat,$request->cen_lon,$brg1,$radius);
                    $pnt2=GetPoint2($request->cen_lat,$request->cen_lon,$brg2,$radius);
                    unset($hasil);unset($geom);
                    $hasil=GetArc($request->cen_lat,$request->cen_lon,$radius,$pnt1['latitude'],$pnt1['longitude'],$pnt2['latitude'],$pnt2['longitude'],'R');
                    $geom='POLYGON(('.$cent.','.$hasil.','.$cent.'))';

                    // var_dump($brg1,$brg2);
                }
                $last = MsaArea::latest('id')->first();
                $request->id = $last->id + 1;
                    $msaarea=new MsaArea;
                    $msaarea->msa_area_id=$msaareaid;
                    $msaarea->msa_id=$request->msa_id;
                    $msaarea->geom=$geom;
                    $msaarea->area=$area;
                    $msaarea->alt=$alt;
                    $msaarea->save();
                if ($jml==1){
                    $last = MsaSegment::latest('id')->first();
                    $request->id = $last->id + 1;
                    $msaseg=new MsaSegment;
                    $msaseg->msa_area_id=$msaareaid;
                    $msaseg->seq=10;
                    $msaseg->center_id=$request->center_id;
                    $msaseg->shap='C';
                    $msaseg->radius=$radius;
                    $msaseg->bearing='0';
                    $msaseg->bearing1='0';
                    $msaseg->save();
                }else{

                    for ($s=1; $s < 4 ; $s++) {
                        switch ($s) {
                            case 1:
                                $bear1='0';
                                $bear2=$bearing1;
                                $shap='P';
                                break;
                            case 2:
                                $bear1=$bearing1;
                                $bear2=$bearing2;
                                $shap='R';
                                break;
                            case 3:
                                $bear1=$bearing2;
                                $bear2=$bearing2;
                                $shap='P';
                                break;
                        }
                            $last = MsaSegment::latest('id')->first();
                            $request->id = $last->id + 1;
                            $msaseg=new MsaSegment;
                            $msaseg->msa_area_id=$msaareaid;
                            $msaseg->seq=$s*10;
                            $msaseg->center_id=$request->center_id;
                            $msaseg->shap=$shap;
                            $msaseg->radius=$radius;
                            $msaseg->bearing=$bear1;
                            $msaseg->bearing1=$bear2;
                            $msaseg->save();
                    }
                }
            
                // dd($request['alt_'.$i]);
            }

        }else if ($request->status=='D'){
            if ($request->remove=='area'){
                $msaarea=MsaArea::find($request->id);
                $msaareaid = $msaarea->msa_area_id;
                $msaarea->delete();
            }else if ($request->remove=='msa'){
                $msa=Msa::find($request->id);
                $msa->delete();
            }

        }
        return redirect('/msa');

    }



    public function remove(Request $request, string $id)
	{
		$airport = Msa::find($id);

		if (null === $airport) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$airport->deleted = 1;

		$airport->save();

		return ApiResponse::success(null);
    }

}
