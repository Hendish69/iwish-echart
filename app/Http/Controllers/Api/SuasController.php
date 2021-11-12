<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Suas;
use App\Models\Api\SuasRemarks;
use App\Models\Api\SuasSegment;
use App\Models\Api\SuasTemp;
use App\Models\Api\SuasRemarksTemp;
use App\Models\Api\SuasSegmentTemp;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;


class SuasController extends Controller
{

	public function list(Request $request, RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, Suas::query()->select('suas.*','country.country','cod_table_types.definition')
                        ->leftjoin('cod_table_types','cod_table_types.code','=','suas.suas_type')
                        ->leftjoin('country','country.ident','=','suas.ctry')
                        ->where('deleted', 0)
                        ->with(['boundary' => function($query) {
                                return $query->with(['navaid'])->with('airport');
                            }
                      // return $query->with('airport');
                        ])
                        ->with(['remarks']));
                      
    // dd($results);
		return ApiResponse::success($results);
    }

    public function listputa_x(Request $request, RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, Suas::query()->select('suas_ident', 'suas_sector', 'suas_name', 'suas_type', 'upper', 'lower', 'geom')
                        ->where('ctry', 'ID')
                        ->where('suas_type', '<>','T')
                        ->where('deleted', 0));
                      
    // dd($results);
		return ApiResponse::success($results);
    }

    public function listtemp(Request $request, RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, SuasTemp::query()->select('suas_temp.*','country.country','cod_table_types.definition')
                        ->leftjoin('cod_table_types','cod_table_types.code','=','suas_temp.suas_type')
                        ->leftjoin('country','country.ident','=','suas_temp.ctry')
                        ->where('deleted', 0)
                        ->with(['boundary' => function($query) {
                                return $query->with(['navaid'])->with('airport');
                            }
                      // return $query->with('airport');
                        ])
                        ->with(['remarks']));
                      
    // dd($results);
		return ApiResponse::success($results);
    }

    public function getRemarks(Request $request,string $pid)
	{
        $ats=DB::table('suas_rmk')
        ->where('suas_id',$pid)
        ->orderBy('note_nbr','asc')
        ->get();

		return ApiResponse::success($ats);
    }
    public function SuasSegtemp(Request $request,  RequestParamHandler $rpm)
    {
          $results = $rpm->process($request, SuasSegmentTemp::query()->select('suas_seg_temp.*','suas_temp.suas_ident','suas_temp.suas_name','suas_temp.suas_type','cod_suas_types.definition')
          ->join('suas_temp','suas_temp.suas_id','suas_seg_temp.suas_id')
          ->join('cod_suas_types','cod_suas_types.id','suas_temp.suas_type')
          ->orderBy('suas_temp.suas_name','asc'));
  
  
          return ApiResponse::success($results);
      }
    public function getBoundary(Request $request,string $pid)
	{
        $ats=DB::table('suas_seg')
        ->join('cod_ats_shap','cod_ats_shap.id','suas_seg.shap')
        ->leftJoin('navaid','navaid.nav_id','suas_seg.nav_id')
        ->where('suas_id',$pid)
        ->orderBy('suas_seq','asc')
        ->get();

		return ApiResponse::success($ats);
    }

    public function update(Request $request, string $id)
    {
      $ats = Suas::find($id);
  
      if (null === $ats) {
        return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
      }
  
      $ats->update($request->all());
  
      return ApiResponse::success($ats->fresh());
    }
  
  
    public function save(Request $request)
    {
      // dd($request);
      $ret_msg='';
      $suascode='';
      if ($request->status=='R'){
        $id=$request->id;
        $asp = SuasTemp::find($id);
        $asp->update($request->all());
        $ret_msg='Update Data Success';
      }else{
        $last = SuasTemp::latest('id')->first();
        // $request->id = $last->id + 1;
        $request->merge([
          'id' => $last->id + 1,
          'suas_id'=>'SUA_'.$request->suas_ident
          ]);
          // $request->suas_id = 'SUA_'.$request->suas_ident;
          // dd($request);
            SuasTemp::create($request->all());
        }
        switch ($request->suas_type) {
            case 'P':
            case 'R':
            case 'D':
              $suascode=70;
              $rawdata['tablename']='ENR';
              $rawdata['fieldname']='sub_id';
              $rawdata['fieldid']='ENR 5.1';
              $rawdata['status_raw']= 50;
              $rawdata['ori_change_pic']= $request->editor;
              saveDataRaw($rawdata);
            
                break;
            
            default:
              $suascode=71;
              $rawdata['tablename']='ENR';
              $rawdata['fieldname']='sub_id';
              $rawdata['fieldid']='ENR 5.2';
              $rawdata['status_raw']= 50;
              $rawdata['ori_change_pic']= $request->editor;
              saveDataRaw($rawdata);
               
                break;
        }
    
        return redirect('/suas/'. $request->suas_id.'@edit');
  
    }

    public function removesegment(Request $request)
	{
        
        // dd($request);
        $id=$request->id;
        $navaid =SuasSegmentTemp::query()->where('id',$id)->delete();
        $seg = SuasSegmentTemp::where('suas_id', $request->suas_id)
        ->orderby('suas_seq', 'asc')->get();
        $hasil=CreateApSegment($seg);
        $aspgeom = SuasTemp::where('suas_id', $request->suas_id)->first();
        
        $aspgeom->geom = $hasil;
        $aspgeom->status ='R';
        $aspgeom->save();
            $no=10;
            foreach ($seg as $key => $value) {
              $segdata = SuasSegmentTemp::where('suas_seg_id', $value->suas_seg_id)->first();
              
              $seq=$no;
              $segdata->suas_seq = $seq;
              $segdata->suas_seg_id ='BDRY_'. $request->suas_id.'_'.sprintf("%06d", $seq);
              $segdata->update();
              $no+=10;
            }
        switch ($request->suas_type) {
          case 'P':
          case 'R':
          case 'D':
            $suascode=70;
            $rawdata['tablename']='ENR';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']='ENR 5.1';
            $rawdata['status_raw']= 50;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);
              break;
          
          default:
          $suascode=71;
          $rawdata['tablename']='ENR';
          $rawdata['fieldname']='sub_id';
          $rawdata['fieldid']='ENR 5.2';
          $rawdata['status_raw']= 50;
          $rawdata['ori_change_pic']= $request->editor;
          saveDataRaw($rawdata);

              break;
      }

        return redirect('/suas/'. $request->suas_id.'@edit');
    }
  
  
      public function savesegment(Request $request)
    {

      $ret_msg='';

      
      // dd($mth);

       
      // dd($request);

      if ($request->status=='R'){
          $id=$request->id;
          $asp = SuasSegmentTemp::find($id);
          $asp->update($request->all());
          $ret_msg='Update Data Success';
          $seg = SuasSegmentTemp::where('suas_id', $request->suas_id)
            ->orderby('suas_seq', 'asc')->get();
            $akh=count($seg)-1;
            // dd( $seg);
          if ($seg[0]->point1_lat !== $seg[$akh]->point1_lat || $seg[0]->point1_long !== $seg[$akh]->point1_long){
            if ($seg[0]->point1_lat == $request->point1_lat || $seg[0]->point1_long == $request->point1_long){
                $asp_dat = SuasSegmentTemp::where('suas_seg_id', $seg[$akh]->suas_seg_id)->first();
            }else{
                $asp_dat = SuasSegmentTemp::where('suas_seg_id', $seg[0]->suas_seg_id)->first();
            }
            $asp_dat->point1_lat = $seg[0]->point1_lat;
            $asp_dat->point1_long = $seg[0]->point1_long;
            $asp_dat->save();
        }

          if ($request->latlama !== $request->point1_lat || $request->lonlama !== $request->point1_long || $request->arclatlama !== $request->arc_lat || $request->arclonlama !== $request->arc_long){
              $seg = SuasSegmentTemp::where('suas_id', $request->suas_id)
              ->orderby('suas_seq', 'asc')->get();
              $hasil=CreateApSegment($seg);
              $aspgeom = SuasTemp::where('suas_id', $request->suas_id)->first();

              
              $aspgeom->geom = $hasil;
              $aspgeom->status ='R';
              $aspgeom->save();

              // dd($aspgeom,$hasil);
              // $aspgeom->geom = $hasil;
              // $aspgeom->status ='R';
              // $aspgeom->update();

              

              $allseg = SuasSegmentTemp::where('point1_lat', $request->latlama)->where('point1_long', $request->lonlama)
              ->orderby('suas_seq', 'asc')->get();
              foreach ($allseg as $key => $value) {
                $suas_dat = SuasSegmentTemp::where('suas_seg_id', $value->suas_seg_id)->first();
                $suas_dat->point1_lat = $request->point1_lat;
                $suas_dat->point1_lat = $request->point1_lat;
                $suas_dat->save();
              }

              foreach ($allseg as $key => $value) {
                $suas_seg = SuasSegmentTemp::where('suas_id', $value->suas_id)->orderby('suas_seq', 'asc')->get();
                $hasilother=CreateApSegment($suas_seg);
                $suas_temp = SuasTemp::where('suas_id', $value->suas_id)->first();
                $suas_temp->geom = $hasilother;
                $suas_temp->save();
              }
              // dd($allseg,$hasil);
          }
          
      }else{
          $last = SuasSegmentTemp::latest('id')->first();
          $request->id = $last->id + 1;

          // dd($last);
          $request->merge([
            'id' => $last->id + 1,
          ]);
          SuasSegmentTemp::create($request->all());

          $seg = SuasSegmentTemp::where('suas_id', $request->suas_id)
          ->orderby('suas_seq', 'asc')->get();
          // dd($seg);
              $hasil=CreateApSegment($seg);
              $aspgeom = SuasTemp::where('suas_id', $request->suas_id)->first();
              //  dd($aspgeom);
              $aspgeom->geom = $hasil;
              $aspgeom->status ='R';
              $aspgeom->save();
              $no=10;
              foreach ($seg as $key => $value) {
                $segdata = SuasSegmentTemp::where('suas_seg_id', $value->suas_seg_id)->first();
                
                $seq=$no;
                $segdata->suas_seq = $seq;
                $segdata->suas_seg_id ='BDRY_'. $request->suas_id.'_'.sprintf("%06d", $seq);
                $segdata->update();
                $no+=10;
              }

          SuasSegmentTemp::create($request->all());

          $seg = SuasSegmentTemp::where('suas_id', $request->suas_id)
              ->orderby('suas_seq', 'asc')->get();
              $hasil=CreateApSegment($seg);
              $aspgeom = SuasTemp::where('suas_id', $request->suas_id)->first();
        
              $aspgeom->geom = $hasil;
              $aspgeom->status ='R';
              $aspgeom->save();
      }


      switch ($request->suas_type) {
        case 'P':
        case 'R':
        case 'D':
          $suascode=70;
          $rawdata['tablename']='ENR';
          $rawdata['fieldname']='sub_id';
          $rawdata['fieldid']='ENR 5.1';
          $rawdata['status_raw']= 50;
          $rawdata['ori_change_pic']= $request->editor;
          saveDataRaw($rawdata);

          
            break;
        
        default:
        $suascode=71;
        $rawdata['tablename']='ENR';
        $rawdata['fieldname']='sub_id';
        $rawdata['fieldid']='ENR 5.2';
        $rawdata['status_raw']= 50;
        $rawdata['ori_change_pic']= $request->editor;
        saveDataRaw($rawdata);

  
            break;
    }

    return redirect('/suas/'. $request->suas_id.'@edit');
      // SuasSegment::create($request->all());
  
    }

    public function updateremarks(Request $request, string $id)
    {
      $ats = SuasRemarks::find($id);
  
      if (null === $ats) {
        return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
      }
  
      $ats->update($request->all());
  
      return ApiResponse::success($ats->fresh());
    }
  
  
      public function saveremarks(Request $request)
    {
      SuasRemarks::create($request->all());
  
    }


}
