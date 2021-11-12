<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Ats;
use App\Models\Api\AtsRemarks;
use App\Models\Api\AtsTemp;
use App\Models\Api\AtsRemarksTemp;
use App\Models\Api\RawdataPub as Raw_Pub;
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\Services\RequestParamHandler;

class AtsController extends Controller
{
    public function index(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Ats::query()->with(['remarks'])->with(['nav1'])->with(['wpt1'])->with(['nav2'])->with(['wpt2'])->select('ats.*',
                        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                        else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                        DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
                        ->join('cod_ats_types','cod_ats_types.id','ats.type'));
                        
                        // ->with(['remarks']);

		return ApiResponse::success($results);
    }

    public function atsall(Request $request, RequestParamHandler $rpm)
	{
		$ats = $rpm->process($request, AtsTemp::query());
        return ApiResponse::success($ats);
    }
    public function atsremarkall(Request $request, RequestParamHandler $rpm)
	{
		$ats = $rpm->process($request, AtsRemarksTemp::query());
        return ApiResponse::success($ats);
    }

    public function indextemp(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, AtsTemp::query()->with(['remarks'])->with(['nav1'])->with(['wpt1'])->with(['nav2'])->with(['wpt2'])
        ->select('ats_temp.*',
                        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point)
                        else (select CONCAT(nav_ident||' ' ||definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                        DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point2) else (select CONCAT(nav_ident||' ' ||definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
                        ->join('cod_ats_types','cod_ats_types.id','ats_temp.type')
                        );

		return ApiResponse::success($results);
    }

    public function getatsbypoint(Request $request, string $pid)
	{
		$results = Ats::query()->select('ats.*',
        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
        else (select CONCAT(nav_ident||' ' ||definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
        DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident||' ' ||definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats.type')
        ->with(['remarks'])->with(['nav1'])->with(['wpt1'])->with(['nav2'])->with(['wpt2'])
        ->where('point',$pid)->orwhere('point2',$pid)->orderby('type','desc')->orderby('ats_ident','asc')->orderby('seq_424')->get();

		return ApiResponse::success($results);
    }
    public function getatsbypointtemp(Request $request, string $pid)
	{
		$results = AtsTemp::query()->select('ats_temp.*',
        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point)
        else (select CONCAT(nav_ident||' ' ||definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
        DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point2) else (select CONCAT(nav_ident||' ' ||definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats_temp.type')
        ->with(['remarks'])->with(['nav1'])->with(['wpt1'])->with(['nav2'])->with(['wpt2'])
        ->where('point',$pid)->orwhere('point2',$pid)->orderby('type','desc')->orderby('ats_ident','asc')->orderby('seq_424')->get();

		return ApiResponse::success($results);
    }

	public function list(Request $request,string $pid)
	{
        $fld='';
        foreach ($request->all() as $field => $find) {
            if ($field == 'ctry')  {
                $fld='ctry';
                $a=$find;
            }
        }
        $ats=DB::table('ats')->select(DB::raw("DISTINCT on (ats_ident) *"),
                                DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                                else (select CONCAT(nav_ident ||' ' ||definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident||' ' ||definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
                                ->join('cod_ats_types','cod_ats_types.id','ats.type')
                                ->join('country','country.ident',DB::raw('right(ats.ctry,2)'));
        if  ($fld != ''){
            $ats=$ats->where('ctry','like',"%{$a}");
        }
        $ats=$ats->groupBy('ats_ident','ats_id','cod_ats_types.id','country.id','country.ident')
        ->orderBy('ats_ident','asc')
        ->orderBy('seq_424','asc');


        switch ($pid) {
        case '61':
            $ats=$ats->where('ats_ident','like','W%')
            ->where('type','W');
            break;
        case '62':
            $ats=$ats->where('ats_ident','not like','W%')
                ->where('type','W');
            break;
        case '63':
            $ats=$ats->where('type','R');
            break;
        case '64':
            $ats=$ats->where('type','V');
            break;
        case 'XX':
            $ats=$ats->where('type','!=','X');
            break;
        default:
            $ats=$ats->where('ats_ident',$pid);
            break;
        }
        $ats=$ats->get();
    return ApiResponse::success($ats);
    }

    public function listall(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, Ats::query()->with(['remarks'])->with(['nav1'])->with(['wpt1'])->with(['nav2'])->with(['wpt2'])->select('ats.*',
                        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                        else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                        DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
                        ->join('cod_ats_types','cod_ats_types.id','ats.type')
                        ->where('ctry','like','%ID')
                        ->where('type','<>','X'));

		return ApiResponse::success($results);
    }

    public function getatsforecast(Request $request,string $id)
	{
		// $results =  Ats::query()->with(['remarks'])->with(['nav1'])->with(['wpt1'])->with(['nav2'])->with(['wpt2'])->select('ats.*',
        //                 DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
        //                 else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
        //                 DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        //                 ->join('cod_ats_types','cod_ats_types.id','ats.type');
        //                 DB::raw("where ST_Intersects(ST_GeomFromText('$id'),'geom')");

        // return ApiResponse::success($results);
        // ST_Intersects(""geom"",ST_GeomFromText(" & ply & ")
		// $frq ="SELECT ctry from ats where ST_Intersects(ST_GeomFromText('$id'),geom) and deleted=0 and ctry like '%ID' group by ctry";
        $frq ="SELECT ats.*,(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                    else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1,(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2)
                    else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2 from ats where ST_Intersects(ST_GeomFromText('$id'),geom) and deleted=0 and ctry like '%ID' and type <> 'X' order by ats_ident";
		
		$results=DB::select(DB::raw($frq));
		return ApiResponse::success($results);
    }
    
    public function listtemp(Request $request,string $pid)
	{
        $fld='';
        foreach ($request->all() as $field => $find) {
            if ($field == 'ctry')  {
                $fld='ctry';
                $a=$find;
            }
        }

        $ats=DB::table('ats_temp')->select(DB::raw("DISTINCT on (ats_ident) *"),
                                DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point)
                                else (select CONCAT(nav_ident,' ',definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
                                ->join('cod_ats_types','cod_ats_types.id','ats_temp.type')
                                ->join('country','country.ident',DB::raw('right(ats_temp.ctry,2)'));
       
        if  ($fld != ''){
            $ats=$ats->where('ctry','like',"%{$a}");
        }
        $ats=$ats->groupBy('ats_ident','ats_id','cod_ats_types.id','country.id','country.ident')
        ->orderBy('ats_ident','asc')
        ->orderBy('seq_424','asc');


        switch ($pid) {
        case '61':
            $ats=$ats->where('ats_ident','like','W%')
            ->where('type','W');
            break;
        case '62':
            $ats=$ats->where('ats_ident','not like','W%')
                ->where('type','W');
            break;
        case '63':
            $ats=$ats->where('type','R');
            break;
        case '64':
            $ats=$ats->where('type','V');
            break;
        case 'XX':
                $ats=$ats->where('type','!=','X');
                break;
        default:
            $ats=$ats->where('ats_ident',$pid);
            break;
        }
        $ats=$ats->get();
        // dd($ats);
    return ApiResponse::success($ats);
    }

    public function listaixm(Request $request,string $pid)
	{

        $ats= Ats::query()->select('ats.*','cod_ats_types.definition', DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select wpt_name from waypoint where wpt_id=point)
        else (select nav_ident from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
        DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select wpt_name from waypoint where wpt_id=point2) else (select nav_ident from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats.type')
        ->where('ctry','like',"%{$pid}")
        ->where('deleted',0)
        ->orderBy('ats_ident','asc')
        ->orderBy('seq_424','asc');


        $ats=$ats->get();
    return ApiResponse::success($ats);
    }



    public function searchByIdent(Request $request)
	{
        $fld='';
        foreach ($request->all() as $field => $find) {
            if ($field == 'ctry')  {
                $fld='ctry';
                $a=$find;
            } else if ($field == 'ats_ident')  {
                $fld1='ats_ident';
                $b=$find;
            }
        }

        $ats=DB::table('ats')->select(DB::raw("DISTINCT on (ctry) *"),
        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                                else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats.type')
        ->join('country','country.ident',DB::raw('right(ats.ctry,2)'))
        ->where($fld1,'like',"{$b}%");
        if  ($fld != ''){
            $ats=$ats->where('ctry','like',"%{$a}");
        }
        // $ats=$ats->groupBy('ats_ident','ats_id','cod_ats_types.id','country.id','country.ident')
        $ats=$ats->groupBy('ctry','ats_id','cod_ats_types.id','country.id','country.ident')
        ->orderBy('ctry','asc')
        ->orderBy('seq_424','asc');

        $ats=$ats->get();
    return ApiResponse::success($ats);
    }

    public function listbyident(Request $request)
	{
        $fld2='';
        foreach ($request->all() as $field => $find) {
            if ($field == 'ats_ident')  {
                $fld1='ats_ident';
                $a=$find;
            }else if ($field == 'ctry'){
                $fld2='ctry';
                $b=$find;
            }
        }

        $ats=DB::table('ats')->select('ats.*','country.ident','country.country',DB::raw('st_asewkt(geom) as cord'),
        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                                else (select nav_ident from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select nav_ident from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats.type')
        ->join('country','country.ident',DB::raw('right(ats.ctry,2)'))
        ->where($fld1,$a);
        if ($fld2 =='ctry'){
            $ats=$ats->where('ctry','like',"%{$b}");
        }
        $ats=$ats->orderBy('seq_424','asc')
        ->get();

    return ApiResponse::success($ats);

    }

    public function listbyidenttemp(Request $request)
	{
        $fld2='';
        foreach ($request->all() as $field => $find) {
            if ($field == 'ats_ident')  {
                $fld1='ats_ident';
                $a=$find;
            }else if ($field == 'ctry'){
                $fld2='ctry';
                $b=$find;
            }
        }

        $ats=DB::table('ats_temp')->select('ats_temp.*','country.ident','country.country',DB::raw('st_asewkt(geom) as cord'),
        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                                else (select nav_ident from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select nav_ident from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats_temp.type')
        ->join('country','country.ident',DB::raw('right(ats_temp.ctry,2)'))
        ->where($fld1,$a);
        if ($fld2 =='ctry'){
            $ats=$ats->where('ctry','like',"%{$b}");
        }
        $ats=$ats->orderBy('seq_424','asc')
        ->get();

    return ApiResponse::success($ats);

    }

    public function nextdata(Request $request)
	{
        foreach ($request->all() as $field => $find) {
            $fld3='';

            if ($field == 'ats_ident')  {
                $fld1='ats_ident';
                $a=$find;
            }else if ($field == 'ctry'){
                $fld4='ctry';
                $e=$find;
            }else if ($field == 'seq_424'){
                $fld2='seq_424';
                $b=$find;
            }else if ($field == 'next'){
                $fld3='next';
                $c=$find;
            }else if ($field == 'prev'){
                $fld3='prev';
                $d=$find;
            }
        }
        // echo $request;
        $ats=DB::table('ats')->select('ats.*',DB::raw('st_asewkt(geom) as cord '),DB::raw('right(ats.ctry,2) as ctryid'),
        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                                else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats.type')
        ->where($fld1,$a)
        ->where($fld4,'like',"%{$e}");


        if ($fld3 =='next'){
            $ats=$ats->where('seq_424' ,'>',$b)
                        ->orderBy('seq_424','asc')
                        ->limit(1);
        }else if ($fld3 =='prev'){
            $ats=$ats->where('seq_424' ,'<',$b)
                        ->orderBy('seq_424','desc')
                        ->limit(1);
        }else if ($fld3 ==''){
            $ats=$ats->where($fld2 ,$b)
                    ->orderBy('seq_424','asc');
        }

        $ats=$ats->get();

    return ApiResponse::success($ats);

    }

    public function nextdatatemp(Request $request)
	{
        foreach ($request->all() as $field => $find) {
            $fld3='';

            if ($field == 'ats_ident')  {
                $fld1='ats_ident';
                $a=$find;
            }else if ($field == 'ctry'){
                $fld4='ctry';
                $e=$find;
            }else if ($field == 'seq_424'){
                $fld2='seq_424';
                $b=$find;
            }else if ($field == 'next'){
                $fld3='next';
                $c=$find;
            }else if ($field == 'prev'){
                $fld3='prev';
                $d=$find;
            }
        }
        // echo $request;
        $ats=DB::table('ats_temp')->select('ats_temp.*',DB::raw('st_asewkt(geom) as cord '),DB::raw('right(ats_temp.ctry,2) as ctryid'),
        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point)
                                else (select CONCAT(nav_ident,' ',definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
        ->join('cod_ats_types','cod_ats_types.id','ats_temp.type')
        ->where($fld1,$a)
        ->where($fld4,'like',"%{$e}");


        if ($fld3 =='next'){
            $ats=$ats->where('seq_424' ,'>',$b)
                        ->orderBy('seq_424','asc')
                        ->limit(1);
        }else if ($fld3 =='prev'){
            $ats=$ats->where('seq_424' ,'<',$b)
                        ->orderBy('seq_424','desc')
                        ->limit(1);
        }else if ($fld3 ==''){
            $ats=$ats->where($fld2 ,$b)
                    ->orderBy('seq_424','asc');
        }

        $ats=$ats->get();

    return ApiResponse::success($ats);

    }

    public function getpoint(Request $request,string $pid)
	{

    if ($request->has('sort')) {
			$sorts = explode(':', $request->get('sort'));

	}

    $ats = DB::table("ats")->select("ats_id","ats_ident","status","src_id","page",
                                DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                                else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"),
                                'track_out','track_in','dist','maa' ,'mfa' ,'mea_out','min_out','min_in','bidirect','level')
                                ->where('ctry', $pid)
                                // ->where('ctry','like','%ID')
                                ->orderby($sorts[0], $sorts[1])
                                ->limit(1)
                                ->get();

	return ApiResponse::success($ats);
    }

    public function getpointtemp(Request $request,string $pid)
	{

    if ($request->has('sort')) {
			$sorts = explode(':', $request->get('sort'));

	}

    $ats = DB::table("ats_temp")->select("ats_id","ats_ident","status","src_id","page",
                                DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point)
                                else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                                DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint where wpt_id=point2) else (select CONCAT(nav_ident,' ',definition) from navaid a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"),
                                'track_out','track_in','dist','maa' ,'mfa' ,'mea_out','min_out','min_in','bidirect','level')
                                ->where('ctry', $pid)
                                // ->where('ctry','like','%ID')
                                ->orderby($sorts[0], $sorts[1])
                                ->limit(1)
                                ->get();

	return ApiResponse::success($ats);
    }

    public function AtsRemarks(Request $request,string $pid)
	{
        $ats=DB::table('ats_rem')->select('id','ats_id','ats_rem.remarks as ats_remarks','ats_rem.asp_id','ats_rem.status')
        ->where('tbl','ats')
        ->where('ats_id',$pid)
        ->get();

    return ApiResponse::success($ats);
    }

    public function AtsRemarkstemp(Request $request,string $pid)
	{
        $ats=DB::table('ats_rem_temp')->select('id','ats_id','ats_rem_temp.remarks as ats_remarks','ats_rem_temp.asp_id','ats_rem_temp.status')
        ->where('tbl','ats')
        ->where('ats_id',$pid)
        ->get();

    return ApiResponse::success($ats);
    }

    public function SaveRemarksaspid(Request $request)
	{
        AtsRemarks::create($request->all());

    }

    public function SaveRemarksaspidtemp(Request $request)
	{
        AtsRemarksTemp::create($request->all());

    }

    public function UpdateRemarksaspid(Request $request,string $id)
	{
        $ats = AtsRemarks::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }

    public function UpdateRemarksaspidtemp(Request $request,string $id)
	{
        $ats = AtsRemarksTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }

	public function update(Request $request, string $id)
	{
		$ats = Ats::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }

    public function updatetemp(Request $request, string $id)
	{
		$ats = AtsTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }


    public function save(Request $request)
	{

        if ($request->status=='R'){
            $id=$request->id;
            $asp = Ats::find($id);
            $asp->update($request->all());
			$ret_msg='Update Data Success';
        }else{
            $last = Ats::latest('id')->first();
            $request->id = $last->id + 1;
			Ats::create($request->all());
        }
        
            return redirect('/listats/'.$page);
      
    }

    public function savetemp(Request $request)
	{
        // dd($request);
       

        // dd($dat_rem);

        $page=$request->ctry;
        $return='/atsdetail/'.$page;
        if ($request->status=='R'){
            $id=$request->id;
            switch ($request->insert) {
                case 'curr':
                    $curr = AtsTemp::where('id', $id)->first();
                    $lat='';$lon='';
                    if ($curr->point !==$request->point){
                       //yg di ubah adalah point 2 di ats prev sebelumnya
                        //merubah ats_id utk current ats

                        $prev= AtsTemp::where('ctry', $curr->ctry)->where('point2', $curr->point)->where('seq_424','<', $curr->seq_424)->limit(1)->first();
                        if (!empty($prev)){
                        $lat=$request->lat1;$lon=$request->lon1;
                        $lat1=$prev->geom[0]->getlat();$lon1=$prev->geom[0]->getlng();
                        $trk= getbearing($lat1,$lon1,$lat, $lon);
                        $geom='LINESTRING ('.$lon1.' '.$lat1.','.$lon.' '.$lat.')';

                        // $atsupd = AtsTemp::where('ats_id',$prev->ats_id)->first();
                        if($prev->dir_424=='F'){
                            $prev->track_out=round($trk->TrackOutMagReal);
                            $prev->track_in=null;
                        }else if($prev->dir_424=='B'){
                            $prev->track_out=null;
                            $prev->track_in=round($trk->TrackInMagReal);
                        }else{
                            $prev->track_out=round($trk->TrackOutMagReal);
                            $prev->track_in=round($trk->TrackInMagReal);
                        }
                        $prev->dist=round($trk->DistanceReal,1);
                        $prev->point2=$request->point;
                        $prev->geom=$geom;
                        $prev->status='R';
                        // dd($prev,$curr);
                        $prev->save();
                    }
                        // dd('PREV',$curr,$prev);
                    }
                    if ($curr->point2 !==$request->point2){
                       //yg di ubah adalah point 1 di ats next
                        $next= AtsTemp::where('ctry', $curr->ctry)->where('point', $curr->point2)->where('seq_424','>', $curr->seq_424)->limit(1)->first();
                        if (!empty($next)){
                            
                            $lat=$request->lat2;$lon=$request->lon2;
                            $lat1=$next->geom[1]->getlat();$lon1=$next->geom[1]->getlng();
                        
                            $trk= getbearing($lat,$lon,$lat1, $lon1);
                            $geom='LINESTRING ('.$lon.' '.$lat.','.$lon1.' '.$lat1.')';

                                if($next->dir_424=='F'){
                                    $next->track_out=round($trk->TrackOutMagReal);
                                    $next->track_in=null;
                                }else if($next->dir_424=='B'){
                                    $next->track_out=null;
                                    $next->track_in=round($trk->TrackInMagReal);
                                }else{
                                    $next->track_out=round($trk->TrackOutMagReal);
                                    $next->track_in=round($trk->TrackInMagReal);
                                }
                                $next->dist=round($trk->DistanceReal,1);
                                $next->point=$request->point2;
                                $next->ats_id='ATS_'.$curr->ctry.'_'.$next->seq_424.'_'.$request->point2;
                                $next->geom=$geom;
                                $next->status='R';
                            //    dd($next,$curr);
                                $next->save();
                        }
                            //    dd('NEXT',$curr,$next->geom[0]->getlng());

                    }

                    $asp = AtsTemp::find($id);
                    $asp->update($request->all());
                    $ret_msg='Update Data Success';
                    break;
                case 'bp1':
                    //  di arahkan ke status = N
                    break;
                case 'ap1':
                    $last = AtsTemp::latest('id')->first();
                    $asp = AtsTemp::find($id);
                    $seq1=$request->seq_424 + 1;
                    $lat=$request->lat2;$lon=$request->lon2;
                    $lat1=$asp->geom[1]->getlat();$lon1=$asp->geom[1]->getlng();
                    $trk= getbearing($lat,$lon,$lat1, $lon1);
                    if($asp->dir_424=='F'){
                        $track_out=round($trk->TrackOutMagReal);
                        $track_in=null;
                    }else if($asp->dir_424=='B'){
                        $track_out=null;
                        $track_in=round($trk->TrackInMagReal);
                    }else{
                        $track_out=round($trk->TrackOutMagReal);
                        $track_in=round($trk->TrackInMagReal);
                    }
                    $geom='LINESTRING ('.$lon.' '.$lat.','.$lon1.' '.$lat1.')';

                    $user = AtsTemp::create([
                        // $next = new AtsTemp;
                        'id' => $last->id + 1,
                        'ats_id'=>'ATS_'.$request->ctry.'_'.$seq1.'_'.$request->point2,
                        'ats_ident' => $request->ats_ident,
                        'ctry' => $request->ctry,
                        'point' => $request->point2,
                        'point2' => $asp->point2,
                        'type' => $request->type,
                        'wpt_type' => $request->wpt_type,
                        'wpt_type2' => $asp->wpt_type2,
                        'bidirect' => $request->bidirect,
                        'dir_424' => $request->dir_424,
                        'seq_424' => $request->seq_424 + 1,
                        'rnp_type' => $request->rnp_type,
                        'maa' => $request->maa,
                        'mfa' => $request->mfa,
                        'mea_out' => $request->mea_out,
                        'seg_use' => $request->seg_use,
                        'track_out' =>$track_out,
                        'track_in' =>$track_in,
                        'dist'=> round($trk->DistanceReal,1),
                        'geom'=> $geom,
                        'status' =>'N',
                        ]);
                        $user->save();
                        
                    
                        $asp->update($request->all());
                        $ret_msg='Update Data Success';
                        // AtsTemp::create($next->all());
                        
                    $seg = AtsTemp::where('ctry', $request->ctry)
                    ->orderby('seq_424', 'asc')->get();
                    // dd($seg);
                        $no=10;
                        foreach ($seg as $key => $value) {
                            $segdata = AtsTemp::where('id', $value->id)->first();
                            $seq=$no;
                            $segdata->seq_424 = $seq;
                            $segdata->ats_id ='ATS_'. $segdata->ctry.'_'.$seq.'_'.$segdata->point;
                            // dd($segdata);
                            $segdata->update();
                            $no+=10;
                        }
                   
                   

                    break;
                    case 'ap2':
                        $last = AtsTemp::latest('id')->first();
                        $request->id = $last->id + 1;
                        // dd($last);
                        $request->merge([
                            'id' => $last->id + 1,
                        ]);
                        AtsTemp::create($request->all());
                        $next= AtsTemp::where('ctry', $request->ctry)->where('point', $request->point)->where('seq_424','>', $request->seq_424)->limit(1)->first();
                        // dd($next);
                        if ($next !== null) {
                            $lat=$request->lat2;$lon=$request->lon2;
                            $lat1=$next->geom[1]->getlat();$lon1=$next->geom[1]->getlng();
                            $trk= getbearing($lat,$lon,$lat1, $lon1);
                            if($next->dir_424=='F'){
                                $track_out=round($trk->TrackOutMagReal);
                                $track_in=null;
                            }else if($next->dir_424=='B'){
                                $track_out=null;
                                $track_in=round($trk->TrackInMagReal);
                            }else{
                                $track_out=round($trk->TrackOutMagReal);
                                $track_in=round($trk->TrackInMagReal);
                            }
                            $geom='LINESTRING ('.$lon.' '.$lat.','.$lon1.' '.$lat1.')';
                            $next->update([
                                'ats_id' => 'ATS_'.$request->ctry.'_'.$next->seq_424.'_'.$request->point2,
                                'point' => $request->point2,
                                'track_out' =>$track_out,
                                'track_in' =>$track_in,
                                'dist'=> round($trk->DistanceReal,1),
                                'geom'=> $geom,
                                'status' =>'R',
                                ]);
                            // $next->save();
                            // $next->update($request->all());
                            $ret_msg='Update Data Success';
                        }
                        $seg = AtsTemp::where('ctry', $request->ctry)
                        ->orderby('seq_424', 'asc')->get();
                        // dd($seg);
                        $no=10;
                        foreach ($seg as $key => $value) {
                            $segdata = AtsTemp::where('id', $value->id)->first();
                            $seq=$no;
                            $segdata->seq_424 = $seq;
                            $segdata->ats_id ='ATS_'. $segdata->ctry.'_'.$seq.'_'.$segdata->point;
                            // dd($segdata);
                            $segdata->update();
                            $no+=10;
                        }
                        break;
                    case 'rp1':
                        $prev= AtsTemp::where('ctry', $request->ctry)->where('point2', $request->point)->where('seq_424','<', $request->seq_424)->limit(1)->first();
                        // dd($prev,$request);
                        if ($prev !== null) {
                            $lat=$request->lat2;$lon=$request->lon2;
                            $lat1=$prev->geom[0]->getlat();$lon1=$prev->geom[0]->getlng();
                            $trk= getbearing($lat1,$lon1,$lat, $lon);
                            if($prev->dir_424=='F'){
                                $track_out=round($trk->TrackOutMagReal);
                                $track_in=null;
                            }else if($prev->dir_424=='B'){
                                $track_out=null;
                                $track_in=round($trk->TrackInMagReal);
                            }else{
                                $track_out=round($trk->TrackOutMagReal);
                                $track_in=round($trk->TrackInMagReal);
                            }
                            $geom='LINESTRING ('.$lon1.' '.$lat1.','.$lon.' '.$lat.')';
                            $prev->update([
                                'point2' => $request->point2,
                                'track_out' =>$track_out,
                                'track_in' =>$track_in,
                                'dist'=> round($trk->DistanceReal,1),
                                'geom'=> $geom,
                                'status' =>'R',
                                ]);
                            // $next->save();
                            // $next->update($request->all());
                            $ret_msg='Update Data Success';
                        }
                        $asp = AtsTemp::find($id);
                        $asp->delete();
                        $seg = AtsTemp::where('ctry', $request->ctry)
                        ->orderby('seq_424', 'asc')->get();
                        // dd($seg);
                        $no=10;
                        foreach ($seg as $key => $value) {
                            $segdata = AtsTemp::where('id', $value->id)->first();
                            $seq=$no;
                            $segdata->seq_424 = $seq;
                            $segdata->ats_id ='ATS_'. $segdata->ctry.'_'.$seq.'_'.$segdata->point;
                            // dd($segdata);
                            $segdata->update();
                            $no+=10;
                        }
                        break;
                        case 'rp2':
                            $next= AtsTemp::where('ctry', $request->ctry)->where('point', $request->point2)->where('seq_424','>', $request->seq_424)->limit(1)->first();
                            // dd($next,$request);
                            if ($next !== null) {
                                $lat=$request->lat1;$lon=$request->lon1;
                                $lat1=$next->geom[1]->getlat();$lon1=$next->geom[1]->getlng();
                                $trk= getbearing($lat,$lon,$lat1, $lon1);
                                if($next->dir_424=='F'){
                                    $track_out=round($trk->TrackOutMagReal);
                                    $track_in=null;
                                }else if($next->dir_424=='B'){
                                    $track_out=null;
                                    $track_in=round($trk->TrackInMagReal);
                                }else{
                                    $track_out=round($trk->TrackOutMagReal);
                                    $track_in=round($trk->TrackInMagReal);
                                }
                                $geom='LINESTRING ('.$lon.' '.$lat.','.$lon1.' '.$lat1.')';
                                $next->update([
                                    'point' => $request->point,
                                    'track_out' =>$track_out,
                                    'track_in' =>$track_in,
                                    'dist'=> round($trk->DistanceReal,1),
                                    'geom'=> $geom,
                                    'status' =>'R',
                                    ]);
                                // $next->save();
                                // $next->update($request->all());
                                $ret_msg='Update Data Success';
                            }
                            $asp = AtsTemp::find($id);
                            $asp->delete();
                            $seg = AtsTemp::where('ctry', $request->ctry)
                            ->orderby('seq_424', 'asc')->get();
                            // dd($seg);
                            $no=10;
                            foreach ($seg as $key => $value) {
                                $segdata = AtsTemp::where('id', $value->id)->first();
                                $seq=$no;
                                $segdata->seq_424 = $seq;
                                $segdata->ats_id ='ATS_'. $segdata->ctry.'_'.$seq.'_'.$segdata->point;
                                // dd($segdata);
                                $segdata->update();
                                $no+=10;
                            }
                            break;
                        case 'remove':
                            $remove= AtsTemp::where('ctry', $request->ctry)->get();
                            foreach ($remove as $key => $value) {
                                $segdata = AtsTemp::where('id', $value->id)->first();
                                // $segdata->ats_ident = $request->ats_ident;
                                // $segdata->status ='R';
                                $segdata->delete();
                                // $segdata->update();
                            }
                            $ppg='';
                            switch ($request->subid) {
                                case 'ENR 3.1':
                                    $ppg='61';
                                    break;
                                case 'ENR 3.2':
                                    $ppg='62';
                                    break;
                                case 'ENR 3.3':
                                    $ppg='63';
                                    break;
                                case 'ENR 3.4':
                                    $ppg='64';
                                    break;
                              
                            }
                            $return='/listats/'.$ppg;
                            break;
                        case 'rename':
                            $rename= AtsTemp::where('ctry', $request->ctry)->get();
                            // dd($rename,$request);
                            foreach ($rename as $key => $value) {
                                $segdata = AtsTemp::where('id', $value->id)->first();
                                $segdata->ats_ident = $request->ats_ident;
                                $segdata->status ='R';
                                // dd($segdata);
                                $segdata->update();
                            }
                            
                            // $rename->update([
                            //     'ats_ident' => $request->ats_ident,
                            //     'status' =>'R',
                            //     ]);
                            break;
            }


        }else{
            $last = AtsTemp::latest('id')->first();
            $request->id = $last->id + 1;
            if ($request->geom == null){
                $lat=$request->lat1;$lon=$request->lon1;
                $lat1=$request->lat2;$lon1=$request->lon2;
                $geom='LINESTRING ('.$lon.' '.$lat.','.$lon1.' '.$lat1.')';

            }else{
                $geom=$request->geom;
            }
            $request->merge([
              'id' => $last->id + 1,
              'geom' => $geom,
            ]);
			AtsTemp::create($request->all());
            $seg = AtsTemp::where('ctry', $request->ctry)
            ->orderby('seq_424', 'asc')->get();
            // dd($seg);
            $no=10;
            foreach ($seg as $key => $value) {
                $segdata = AtsTemp::where('id', $value->id)->first();
                $seq=$no;
                $segdata->seq_424 = $seq;
                $segdata->ats_id ='ATS_'. $segdata->ctry.'_'.$seq.'_'.$segdata->point;
                // dd($segdata);
                $segdata->update();
                $no+=10;
            }
        }
    
        $subid='';
        switch ($request->type) {
            case 'W':
                if (substr($request->ats_ident,0,1)=='W'){
                    $subid='ENR 3.1';
                }else{
                    $subid='ENR 3.2';
                }
                break;
            case 'R':
                $subid='ENR 3.3';
                break;
            case 'V':
                $subid='ENR 3.4';
                break;
            

        }
            $rawdata['tablename']='ENR';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']=$subid;
            $rawdata['status_raw']=50;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);
            // $raw_dat = Raw_Pub::where('tablename', 'ENR')
            // ->where('fieldname', 'sub_id')
            // ->where('fieldid', $subid)
            // ->where('status_raw','<', 100)
            // ->first();
            //     if ($raw_dat === null) {
        
            //         $raw_dat = new Raw_Pub;
            //         $raw_dat->tablename = 'ENR';
            //         $raw_dat->fieldname = 'sub_id';
            //         $raw_dat->fieldid =  $subid;
            //         $raw_dat->status_raw = 0;
            //     }
                // dd($raw_dat);
                // $raw_dat->ori_change_pic = $request->editor;
                // $raw_dat->save();
        if ($request->ats_remarks !== null){
            $nats=AtsTemp::where('ctry','=',$request->ctry)->where('point','=',$request->point)->where('point2','=',$request->point2)->first();
            // dd($nats);
            $dat_rem = AtsRemarksTemp::where('ats_id', $nats->ats_id)->first();
            // dd($dat_rem,'check');
            if ($dat_rem === null) {
                $dat_rem = new AtsRemarksTemp;
                $dat_rem->ats_id = $nats->ats_id;
                $dat_rem->remarks = $request->ats_remarks;
                $dat_rem->status =  'N';
                $dat_rem->tbl = 'ats';
            }else{
                $dat_rem->remarks = $request->ats_remarks;
                $dat_rem->status =  'R';
            }
            // dd($raw_dat);
            $dat_rem->save();

        }
            return redirect($return);

    }

    public function remove(Request $request, string $id)
	{
		$ats = Ats::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function removetemp(Request $request, string $id)
	{
		$ats = AtsTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function removeatsident(Request $request)
	{

        foreach ($request->all() as $field => $find) {

			if ($field == 'ctry')  {
                $fld1='ctry';
                $a=$find;
            }
        }
            $navaid = Ats::where($fld1,$a)
            ->delete();

            if (null === $navaid) {
                return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
            }



            return ApiResponse::success();


        
    }

    public function removeatsidenttemp(Request $request)
	{

        foreach ($request->all() as $field => $find) {

			if ($field == 'ctry')  {
                $fld1='ctry';
                $a=$find;
            }
        }
            $navaid = AtsTemp::where($fld1,$a)
            ->delete();

            if (null === $navaid) {
                return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
            }



            return ApiResponse::success();


        
    }

}
