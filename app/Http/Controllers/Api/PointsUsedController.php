<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ApiResponse;
use Carbon\Traits\Cast;
use phpDocumentor\Reflection\Types\Integer;

class PointsUsedController extends Controller
{
	public function UseInATS(Request $request, string $pid)
	{
        $menus = DB::table("ats")->select("ats_id","ats_ident","country","ctry",
                DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                else (select nav_ident from navaid where nav_id=point) end) as point1"),
                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select nav_ident from navaid where nav_id=point2) end) as point2"),
                'track_out','track_in','dist','maa' ,'mfa' ,'mea_out','min_out','min_in','bidirect','level')
                ->join('country','country.ident','=',DB::raw('right(ats.ctry,2)'))
                ->where('point', $pid)
                ->orwhere('point2', $pid)
                ->orderby('ats_ident')->get();
        return ApiResponse::success($menus);
        }

        public function UseInATSTemp(Request $request, string $pid)
	{
        $menus = DB::table("ats_temp")->select("ats_id","ats_ident","country","ctry",
                DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point)
                else (select nav_ident from navaid_temp where nav_id=point) end) as point1"),
                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point2) else (select nav_ident from navaid_temp where nav_id=point2) end) as point2"),
                'track_out','track_in','dist','maa' ,'mfa' ,'mea_out','min_out','min_in','bidirect','level')
                ->join('country','country.ident','=',DB::raw('right(ats_temp.ctry,2)'))
                ->where('point', $pid)
                ->orwhere('point2', $pid)
                ->orderby('ats_ident')->get();
        return ApiResponse::success($menus);
        }
        
	public function UseInTrans(Request $request, string $pid)
	{
        $menus = DB::table('arpt_proc')->DISTINCT()->select(DB::raw("CONCAT(arpt.icao,' ',arpt.arpt_name,' Procedure ',proc_name,' ',cod_chart_types.definition,' RWY ',arpt_trans.rwy_trans) as procedure_name"),'arpt_proc.proc_id','arpt.arpt_ident')
                ->join('arpt', 'arpt.arpt_ident','=','arpt_proc.arpt_ident')
                ->join('arpt_proc_seg', 'arpt_proc_seg.proc_id','=','arpt_proc.proc_id')
                ->join('arpt_trans', 'arpt_trans.proc_id','=','arpt_proc_seg.trans_id')
                ->join('arpt_trans_seg', 'arpt_trans_seg.proc_id','=','arpt_trans.proc_id')
                ->join('cod_chart_types', 'cod_chart_types.id','=',DB::raw("cast(arpt_trans.chart_type as int)"))
                ->join('cod_trans_types', function($join){
                $join->on('cod_trans_types.trans_types','=','arpt_trans.rt_type')
                        ->on('cod_trans_types.trans_code','=','arpt_trans.sub_chart_type');
                })
                ->where('arpt_trans_seg.fix_id', $pid)
                ->orwhere('arpt_trans_seg.recd_nav', $pid)
                ->where('arpt_trans.deleted', 0)
                ->orderby('procedure_name')
                ->get();
        return ApiResponse::success($menus);
        }

        public function UseInTransTemp(Request $request, string $pid)
	{
        $menus = DB::table('arpt_proc_temp')->DISTINCT()->select(DB::raw("CONCAT(arpt_temp.icao,' ',arpt_temp.arpt_name,' Procedure ',proc_name,' ',cod_chart_types.definition,' RWY ',arpt_trans_temp.rwy_trans) as procedure_name"),'arpt_proc_temp.proc_id','arpt_temp.arpt_ident')
                ->join('arpt_temp', 'arpt_temp.arpt_ident','=','arpt_proc_temp.arpt_ident')
                ->join('arpt_proc_seg_temp', 'arpt_proc_seg_temp.proc_id','=','arpt_proc_temp.proc_id')
                ->join('arpt_trans_temp', 'arpt_trans_temp.proc_id','=','arpt_proc_seg_temp.trans_id')
                ->join('arpt_trans_seg_temp', 'arpt_trans_seg_temp.proc_id','=','arpt_trans_temp.proc_id')
                ->join('cod_chart_types', 'cod_chart_types.id','=',DB::raw("cast(arpt_trans_temp.chart_type as int)"))
                ->join('cod_trans_types', function($join){
                $join->on('cod_trans_types.trans_types','=','arpt_trans_temp.rt_type')
                        ->on('cod_trans_types.trans_code','=','arpt_trans_temp.sub_chart_type');
                })
                ->where('arpt_trans_seg_temp.fix_id', $pid)
                ->orwhere('arpt_trans_seg_temp.recd_nav', $pid)
                ->where('arpt_trans_temp.deleted', 0)
                ->orderby('procedure_name')
                ->get();
                // dd($menus);
        return ApiResponse::success($menus);
        }
        
        public function UseInASP(Request $request, string $pid)
	{
        $menus = DB::table("airspace")->select("airspace.*","airspace_seg.asp_seg_id","airspace_seg.id as idd","airspace_seg.air_seq","airspace_seg.air_seq","airspace_seg.shap","airspace_seg.point1_lat","airspace_seg.point1_long","airspace_seg.arc_dist","country")
                ->join('airspace_seg','airspace_seg.asp_id','airspace.ats_airspace_id')
                ->join('country','country.ident','airspace.ctry')
                ->where('airspace_seg.nav_id', $pid)
                ->where('airspace.deleted',0)
                ->orderby('airspace.ats_airspace_id','asc')
                ->orderby('air_seq')->get();
        return ApiResponse::success($menus);
	}
        public function UseInASPTemp(Request $request, string $pid)
	{
        $menus = DB::table("airspace_temp")->select("airspace_temp.*","airspace_seg_temp.asp_seg_id","airspace_seg_temp.id as idd","airspace_seg_temp.air_seq","airspace_seg_temp.air_seq","airspace_seg_temp.shap","airspace_seg_temp.point1_lat","airspace_seg_temp.point1_long","airspace_seg_temp.arc_dist","country")
                ->join('airspace_seg_temp','airspace_seg_temp.asp_id','airspace_temp.ats_airspace_id')
                ->join('country','country.ident','airspace_temp.ctry')
                ->where('airspace_seg_temp.nav_id', $pid)
                ->where('airspace_temp.deleted',0)
                ->orderby('airspace_temp.ats_airspace_id','asc')
                ->orderby('air_seq')->get();
        return ApiResponse::success($menus);
	}
}
