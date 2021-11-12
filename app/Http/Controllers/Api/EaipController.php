<?php
namespace App\Http\Controllers\Api;

use \Illuminate\Support\Facades\Request as Req;
use Auth;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\RequestParamHandler;
use App\Models\Api\Airport;
use App\Models\Api\EaipChartContentTemp;
use App\Models\Api\EaipChartContent;
use App\Models\Api\EaipGenContentTemp;
use App\Models\Api\EaipGenContent;
use App\Models\Api\TempUpdate;
use App\Models\Api\TempUpdateDetail;
use App\Models\Api\CodEaip;
use App\Models\Api\Abbr;
use App\Models\Api\AbbrTemp;
use App\Models\Api\Content;
use App\Models\Api\SysMenu;
use App\ApiResponse;
use Bosnadev\Database\Schema\Builder;
use Doctrine\DBAL\Schema\Table;

class EaipController extends Controller
{
    public function sysmenu(Request $request, RequestParamHandler $rpm)
	{
		$builder = SysMenu::query();

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
    }

    public function menu(Request $request, RequestParamHandler $rpm)
	{
		$builder = CodEaip::query();

		$results = $rpm->process($request, $builder);

		return ApiResponse::success($results);
    }
    
	public function levelOneMenu(Request $request)
	{
		$menus = DB::table('cod_eaip')->where('level', 0)->orderby('id')->get();

		return ApiResponse::success($menus);
	}

	public function levelTwoMenu(Request $request, string $pid)
	{
        $menus =DB::table('cod_eaip')->where('level', 1)->where('parentid', $pid)
        ->orderby('seq')
        ->get();
        // var_dump( $menus,$pid);
        
        return ApiResponse::success($menus);
    }

    public function levelThreeMenu(Request $request, string $pid)
	{
        
        $menus = DB::table('cod_eaip')->where('level', 2)
                ->where('parentid', $pid)
                ->orderby('seq');
        $menus=$menus->get();

                return ApiResponse::success($menus);
    }

    public function levelTwoMenulist(Request $request, string $pid)
	{
        $menus = DB::table('cod_table_types')->where('tbl', $pid)
                ->orderby('code')
                ->get();

		return ApiResponse::success($menus);
    }
    public function menuall(Request $request, string $pid)
	{
        $menus = DB::table('cod_eaip')->where('sub_id','like',"{$pid}%")
                ->orderby('seq')
                ->get();

		return ApiResponse::success($menus);
    }

    public function CodTypes(Request $request,string $table)
	{
        // dd($table);
        $results= DB::table($table);
        if ($table == 'cod_chart_types'){
            $results=$results->orderby('seq','asc');
        }else{
            foreach ($request->all() as $field => $find) {
                $results->where($field, $find)
                        ->orderby($field,'asc');
            }
    
            if ($table == 'cod_aip'){
                $results=$results->where('seq','!=','1')
                                ->orderby('seq','asc');
            }

        }           // ->orderby('id','asc');
                    // ->get();

       

        $results=$results->get();
        // dd($results);
        return ApiResponse::success($results);
    }

    public function GetContent(Request $request,string $table)
	{

        $results= DB::table($table)->select($table.'.*');
        foreach ($request->all() as $field => $find) {
        $results->join('eaip_cod_category','eaip_cod_category.id',$table.'.category_id')
                ->join('cod_aip','cod_aip.des','eaip_cod_category.category')
                ->where($field, $find)
                ->orderby('cod_aip.seq','asc')
                ->orderby('eaip_cod_category.sequence','asc');
        }

        $results=$results->get();
        return ApiResponse::success($results);
    }

    public function GetEaipContent(Request $request,RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, EaipChartContent::query());
                        // ->join('eaip_cod_category','eaip_cod_category.id','eaip_chart_content.category_id')
                        // ->join('cod_aip','cod_aip.des','eaip_cod_category.category')
                        // ->orderby('cod_aip.seq','asc')
                        // ->orderby('eaip_cod_category.sequence','asc'));
        return ApiResponse::success($results);
    }

    public function Getabbr(Request $request,RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, Abbr::query());
        return ApiResponse::success($results);
    }

    public function Getabbrtemp(Request $request,RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, AbbrTemp::query());
        return ApiResponse::success($results);
    }
    public function saveabbr(Request $request){
        $ret_msg='';
        // dd($request);
		if ($request->status=='R'){
            $originalInput=Req::input();
            $user = Auth::user();
			$id=$request->id;

			$airport = AbbrTemp::find($id);       
			$airport->update($request->all());
			$ret_msg='Update Data Success';

		}else if ($request->status=='N'){
            $last = AbbrTemp::latest('id')->first();
            $request->merge([
                'id' => $last->id + 1,
            ]);
			AbbrTemp::create($request->all());
        
			$ret_msg ='Insert Data Success';
        }else if ($request->status=='D'){
            $originalInput=Req::input();
            $user = Auth::user();
			$id=$request->id;

			$airport = AbbrTemp::find($id);       
			$airport->deleted=1;
            $airport->status='R';
            $airport->update();
			$ret_msg='Update Data Success';
		}
            $rawdata['tablename']='GEN';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']='GEN 2.2';
            $rawdata['status_raw']=50;
            $rawdata['ori_change_pic']= $request->editor;
            saveDataRaw($rawdata);

            // $raw_dat = Raw_Pub::where('tablename', 'GEN')
            // ->where('fieldname', 'sub_id')
            // ->where('fieldid','GEN 2.2')
            // ->where('status_raw','<=', 70)
            // ->first();
            // if ($raw_dat === null) {
            //     $raw_dat = new Raw_Pub;
            //     $raw_dat->tablename = 'GEN';
            //     $raw_dat->fieldname = 'sub_id';
            //     $raw_dat->fieldid = 'GEN 2.2';
            //     $raw_dat->status_raw = 50;
            // }
            // // dd($raw_dat);
            // $raw_dat->ori_change_pic = $request->editor;
            // $raw_dat->save();
		//save data to raw data pub, utk request data
            return redirect('gen22/edit');
        
    }

    public function saveupdaterawdata(Request $request){

        dd($request);

    }
    public function GetEaipContenttemp(Request $request,RequestParamHandler $rpm)
	{

        $results = $rpm->process($request, EaipChartContentTemp::query());
                        // ->join('eaip_cod_category','eaip_cod_category.id','eaip_chart_content_temp.category_id')
                        // ->join('cod_aip','cod_aip.des','eaip_cod_category.category')
                        // ->orderby('cod_aip.seq','asc')
                        // ->orderby('eaip_cod_category.sequence','asc'));
        return ApiResponse::success($results);

        
    }
    

    public function CheckFreq(Request $request)
	{
        $results= DB::table('ch_freq');
                    // ->where($fld,$id)
                    // ->get();
        foreach ($request->all() as $field => $find) {
			$results->where($field, $find);
		}

        $results=$results->get();
        return ApiResponse::success($results);
    }

public function getutctime(Request $request){

    foreach ($request->all() as $field => $find) {
        // echo $find;
        if ($field == 'a')  {
            $a=$find;
        }else if ($field == 'b'){
            $b=$find;
        }

    }
    $cord1="wkb_geom,ST_GeomFromText('POINT($b $a)',4326)";
    $ssql = "Select name from time where ST_Intersects($cord1)";
// echo $ssql;
    $ssql= DB::select(DB::raw($ssql));
    return ApiResponse::success($ssql);
}

    public function Getdistance(Request $request)
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
            }


		}
        // echo $a & ' ' & $b;
        // $pnt = explode('!!', $id, 4);
        $wgs='"WGS 84"';
        // $cord1="ST_GeomFromText('POINT(106.52995 -6.187888888888889)',4326)";
        // $cord2="ST_GeomFromText('POINT(105.629833333333 -5.42353611111111)',4326)";
        $cord1="ST_GeomFromText('POINT($a $b)',4326)";
        $cord2="ST_GeomFromText('POINT($c $d)',4326)";
        $ssql = "SELECT CAST(ST_Distance_Spheroid($cord1,$cord2, 'SPHEROID[$wgs,6378137,298.257223563]')As numeric) As dist";


        $ssql= DB::select(DB::raw($ssql));
        return ApiResponse::success($ssql);
    }

    public function Getpaper(Request $request)
	{
        foreach ($request->all() as $field => $find) {
            // echo $find;
			if ($field == 'definition')  {
				$a=$find;
			}else if ($field == 'chart_type'){
                $b=$find;
            }


		}

        $menus = DB::table('cod_paper_size')
                ->where('definition', $a)
                ->where('chart_type', $b)
                ->get();
                return ApiResponse::success($menus);
    }

    public function Codaip(Request $request)
	{
        $frq = "select id || '#' || definition as id from cod_aip order by seq";
    
    $frq= DB::select(DB::raw($frq));
    return ApiResponse::success($frq);
    }

    public function Codcharttypes(Request $request)
	{
        $frq = "SELECT * FROM cod_chart_types where code notnull order by seq";
    
    $frq= DB::select(DB::raw($frq));
    return ApiResponse::success($frq);
    }


    public function Codaipsub(Request $request)
	{
        $menus = DB::table('cod_aip')->select('cod_aip.id as subid','definition','des','eaip_cod_category.id','eaip_cod_category.item','eaip_cod_category.format','eaip_cod_category.sequence','eaip_cod_category.form_type')
        ->join('eaip_cod_category','eaip_cod_category.category','cod_aip.des')
        ->where('deleted',0)
        ->orderby('seq','asc')
        ->orderby('sequence','asc');
      

    $menus=$menus->get();
    return ApiResponse::success($menus);

    }

    public function Codtableheader(Request $request)
	{
        $menus = DB::table('cod_aip_conf')
        ->orderby('tbl','asc')
        ->orderby('chart','asc')
        ->orderby('seq','asc');
      

    $menus=$menus->get();
    return ApiResponse::success($menus);

    }
   
    public function getnewid(Request $request,string $table)
	{
        $tbl = explode('!!', $table, 3);
        // echo($tbl[0]);
        //  echo($tbl[1]);
        //   echo($tbl[2]);
        switch ($tbl[0]) {
            case 'WPT':
            $menus = DB::table('waypoint')->select('wpt_id')
            ->where('wpt_id','like',"{$tbl[1]}%")
            ->orderby(DB::raw("substring(wpt_id, '^[0-9]+') :: int,substring(wpt_id, '[^0-9_].*$')"),'desc')->limit(1);
                break;
            case 'NAV':
            $menus = DB::table('navaid')->select('nav_id')
            ->where('nav_id','like',"{$tbl[1]}%")
            ->orderby(DB::raw("substring(nav_id, '^[0-9]+') :: int,substring(nav_id, '[^0-9_].*$')"),'desc')->limit(1);
                break;
            case 'ARP':
            $menus = DB::table('arpt')->select('arpt_ident')
            ->where('ctry',"{$tbl[1]}")
            ->orderby(DB::raw("substring(arpt_ident, '^[0-9]+') :: int,substring(arpt_ident, '[^0-9_].*$')"),'desc')->limit(1);
                break;
            case 'ASP':
            $menus = DB::table('airspace')->select('ats_airspace_id')
            ->where(DB::raw('substring(ats_airspace_id,1,2)'),"{$tbl[1]}")
            ->orderby(DB::raw("substring(ats_airspace_id, '^[0-9]+') :: int,substring(ats_airspace_id, '[^0-9_].*$')"),'desc')->limit(1);
                break;
            case 'ASP_SEG':
            $menus = DB::table('airspace_seg')->select('air_seq')
            ->where('asp_id',"{$tbl[1]}")
            ->orderby('air_seq','desc')->limit(1);
                break;
            case 'SUAS_SEG':
            $menus = DB::table('airspace_seg')->select('suas_seq')
            ->where('suas_id',"{$tbl[1]}")
            ->orderby('suas_seq','desc')->limit(1);
                break;
            case 'MSA':
            $menus = DB::table('msa')->select('msa_id')
            ->where('msa_id','like',"{$tbl[1]}%")
            ->orderby('msa_id','desc')->limit(1);
                break;
            case 'CLIENT':
            $menus = DB::table('cntrsys')->select('cast(md5(c)')
            ->where('a',"md5('client')")
            ->orderby('id','desc')->limit(1);
                break;
            case 'ARP_COM':
            $menus = DB::table('arpt_comm')->select('n_comm_key')
            ->where('arpt_ident',"{$tbl[1]}")
            ->where('type',"{$tbl[2]}")
            ->orderby(DB::raw("substring(n_comm_key, '^[0-9]+') :: int,substring(n_comm_key, '[^0-9_].*$')"),'desc')->limit(1);
                break;

            default:
                # code...
                break;
        }

        $menus=$menus->get();
        return ApiResponse::success($menus);
    }
    public function getcontentrequest(Request $request)
	{
        $frq ="SELECT DISTINCT b.arpt_ident FROM eaip_chart_content_temp a inner join arpt b on b.arpt_ident=a.arpt_ident where a.status in ('R','N') and b.deleted=0";

        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function getcontentrequestdetail(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq ="SELECT a.*,b.category,b.item,a.content from eaip_chart_content_temp a inner join eaip_cod_category b on b.id=a.category_id inner join cod_aip c on c.des = b.category where a.arpt_ident='$id' and a.status in ('R','N') order by c.seq,b.sequence";
        }else if ($tbl=='current'){
            $frq ="SELECT a.*,b.category,b.item,a.content from eaip_chart_content a inner join eaip_cod_category b on b.id=a.category_id inner join cod_aip c on c.des = b.category where a.arpt_ident='$id' order by c.seq,b.sequence";
        }
        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function getcontentrequestapron(Request $request,string $tbl,string $id)
	{
    
        if ($tbl=='temp'){
            $frq="select * from eaip_apron_twy_temp where status in ('R','N') and deleted=0 and arpt_ident='$id'";
        }else if ($tbl=='current'){
            $frq="select * from eaip_apron_twy where deleted=0 and arpt_ident='$id'";
        }
        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function getcontentrequestparkingstand(Request $request,string $tbl,string $id)
	{

        
        if ($tbl=='temp'){
            $frq="select * from eaip_arpt_gate_temp where status in ('R','N') and deleted=0 and arpt_ident_gate='$id'";
        }else if ($tbl=='current'){
            $frq="select * from eaip_arpt_gate where deleted=0 and arpt_ident_gate='$id'";
        }
        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }

    public function getcontentrequestpushback(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select * from eaip_pushback_temp where status in ('R','N') and deleted=0 and arpt_ident_pushback='$id'";
        }else if ($tbl=='current'){
            $frq="select * from eaip_pushback where deleted=0 and arpt_ident_pushback='$id'";
        }
        

        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function getcontentrequestobstacle(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select *,st_asewkt(geom) as obs_geom from arpt_obstacle_temp where status in ('R','N') and deleted=0 and arpt_ident='$id'";
        }else if ($tbl=='current'){
            $frq="select *,st_asewkt(geom) as obs_geom from arpt_obstacle where deleted=0 and arpt_ident='$id'";
        }
        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function getcontentrequestrwy(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select * from arpt_rwy_temp where status in ('R','N') and deleted=0 and arpt_ident='$id'";
        }else if ($tbl=='current'){
            $frq="select * from arpt_rwy where deleted=0 and arpt_ident='$id'";
        }
       


        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
	}

    public function getcontentrequestrwyphysical(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select *,st_asewkt(geom) as thr_geom,st_asewkt(disp_geom) as disp_thr_geom from arpt_rwy_physical_temp where status in ('R','N') and rwy_id like '%$id%'";
        }else if ($tbl=='current'){
            $frq="select *,st_asewkt(geom) as thr_geom,st_asewkt(disp_geom) as disp_thr_geom from arpt_rwy_physical where rwy_id like '%$id%'";
        }
            
        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function getcontentrequestrwylight(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select eaip_rwy_lgt_temp.*,arpt_rwy_physical_temp.rwy_ident from eaip_rwy_lgt_temp inner join arpt_rwy_physical_temp on arpt_rwy_physical_temp.rwy_key=eaip_rwy_lgt_temp.rwy_id where eaip_rwy_lgt_temp.status in ('R','N') and eaip_rwy_lgt_temp.rwy_id like '%$id%'";
        }else if ($tbl=='current'){
            $frq="select eaip_rwy_lgt.*,arpt_rwy_physical.rwy_ident from eaip_rwy_lgt inner join arpt_rwy_physical on arpt_rwy_physical.rwy_key=eaip_rwy_lgt.rwy_id where eaip_rwy_lgt.rwy_id like '%$id%'";
        }

        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }


    public function getcontentrequestcomm(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select a.id,a.freqid,b.remarks,a.seq,b.types,b.call_sign,b.sector,c.opr_hrs,c.level,c.satcom,c.logon,d.freq,d.unit,a.status as astatus,b.status as bstatus,c.status as cstatus ,d.status as dstatus from freq_used_temp a inner join freq_temp b on b.id=a.freqid inner join freq_seg_temp c on c.call_sign=a.freqid inner join freq_value_temp d on d.freq_id=c.freq_id where a.status in ('R','N') and arpt_ident ='$id' order by a.seq";
        }else if ($tbl=='current'){
            $frq="select a.id,a.freqid,b.remarks,a.seq,b.types,b.call_sign,b.sector,c.opr_hrs,c.level,c.satcom,c.logon,d.freq,d.unit,a.status as astatus,b.status as bstatus,c.status as cstatus ,d.status as dstatus from freq_used a inner join freq b on b.id=a.freqid inner join freq_seg c on c.call_sign=a.freqid inner join freq_value d on d.freq_id=c.freq_id where arpt_ident ='$id' order by a.seq";
        }


        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function getcontentrequestils(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select b.*,st_asewkt(b.geom) as geom,st_asewkt(b.gs_geom) as gsgeom from arpt_nav_temp a inner join arpt_ils_temp b on b.ils_id=a.ils_id inner join arpt_rwy_physical_temp c on c.rwy_key=b.rwy_id where a.status in ('R','N') and a.arpt_ident ='$id'";
        }else if ($tbl=='current'){
            $frq="select b.*,st_asewkt(b.geom) as geom,st_asewkt(b.gs_geom) as gsgeom from arpt_nav a inner join arpt_ils b on b.ils_id=a.ils_id inner join arpt_rwy_physical c on c.rwy_key=b.rwy_id where a.arpt_ident ='$id'";
        }

        

        $frq= DB::select(DB::raw($frq));
        // dd($frq);
        return ApiResponse::success($frq);
    }

    public function getcontentrequestmarker(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            $frq="select b.ils_ident,c.rwy_ident,d.*,st_asewkt(d.geom) as geom from arpt_nav_temp a inner join arpt_ils_temp b on b.ils_id=a.ils_id inner join arpt_rwy_physical_temp c on c.rwy_key=b.rwy_id inner join  arpt_marker_temp d on d.ils_id=b.ils_id where a.status in ('R','N') and a.arpt_ident ='$id'";
        }else if ($tbl=='current'){
            $frq="select b.ils_ident,c.rwy_ident,d.*,st_asewkt(d.geom) as geom  from arpt_nav a inner join arpt_ils b on b.ils_id=a.ils_id inner join arpt_rwy_physical c on c.rwy_key=b.rwy_id inner join arpt_marker d on d.ils_id=b.ils_id where a.arpt_ident ='$id'";
        }

        

        $frq= DB::select(DB::raw($frq));
        // dd($frq);
        return ApiResponse::success($frq);
    }

    public function getcontentrequestnavaid(Request $request,string $tbl,string $id)
	{
        if ($tbl=='temp'){
            
            $frq="select b.*,st_asewkt(b.geom) as geom,st_asewkt(b.dmegeom) as dmegeom,c.definition from arpt_nav_temp a inner join navaid_temp b on b.nav_id=a.nav_id inner join cod_nav_types c on c.id=b.type where status in ('R','N') and a.arpt_ident ='$id'";
        }else if ($tbl=='current'){
            $frq="select b.*,st_asewkt(b.geom) as geom,st_asewkt(b.dmegeom) as dmegeom,c.definition from arpt_nav a inner join navaid b on b.nav_id=a.nav_id inner join cod_nav_types c on c.id=b.type where a.arpt_ident ='$id'";
        }

        

        $frq= DB::select(DB::raw($frq));
        return ApiResponse::success($frq);
    }
    
    public function eaipcontent(Request $request, RequestParamHandler $rpm)
    {

        $results = $rpm->process($request, EaipChartContent::query());

		return ApiResponse::success($results);
    }

    public function eaipcontenttemp(Request $request, RequestParamHandler $rpm)
    {

        $results = $rpm->process($request, EaipChartContentTemp::query());

		return ApiResponse::success($results);
    }

    public function eaipchartcontenttempupdate(Request $request,string $id)
    {
        $ats = EaipChartContentTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }

    public function eaipchartcontentupdate(Request $request,string $id)
    {
        $ats = EaipChartContent::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }

    public function eaipchartcontenttempsave(Request $request)
    {
        dd($request->all());
        $temp = EaipChartContentTemp::create($request->all());

        return ApiResponse::success($temp->id);
    }

    public function eaipchartcontentsave(Request $request)
    {
        $temp = EaipChartContent::create($request->all());

        return ApiResponse::success($temp->id);
    }


    public function eaipchartcontenttempremove(Request $request,string $id)
    {

        $ats = EaipChartContentTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function eaipchartcontentremove(Request $request,string $id)
    {

        $ats = EaipChartContent::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function tempupdate(Request $request, RequestParamHandler $rpm)
	{

        $builder = TempUpdate::query()
		->with(['detail']);

		$results = $rpm->process($request, $builder);

        return ApiResponse::success($results);
        
        
    }

    public function Gettempupdate(Request $request, RequestParamHandler $rpm)
	{

      
        
		foreach ($request->all() as $field => $find) {

			if ($field == 'table_nm')  {
                $fld1='table_nm';
                $a=$find;
            }else if ($field == 'tableid'){
                $fld2='tableid';
                $b=$find;
			}
			// else if ($field == 'field'){
            //     $fld3='field';
            //     $c=$find;
            // }
        }
		$results = DB::table('temp_update')->select('temp_update.id','table_nm','tableid','temp_update_detail.id as idd','temp_update_detail.field','temp_update_detail.val','temp_update_detail.status')
							->leftjoin('temp_update_detail','temp_update_detail.refid','temp_update.id')
							->where($fld1, $a)
							->where($fld2, $b)
							// // ->where($fld3, $c)
							// ->whereIn('temp_update_detail.status',['R','N'])
							->where('temp_update.deleted',0)
							->orderby('seq','desc')
							// ->limit(1)
							->get();
		
		return ApiResponse::success($results);
    }
    
    public function gettempupdatefilter(Request $request, RequestParamHandler $rpm)
	{
		$results = $rpm->process($request, TempUpdateDetail::query());

		return ApiResponse::success($results);
    }



    public function updatetempupdate(Request $request, string $id)
	{
        $ats = TempUpdate::find($id);
        if (null === $ats) {
            return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
        }
        $ats->update($request->all());
        return ApiResponse::success($ats->fresh());
	}

    public function updatetempupdatedetail(Request $request, string $id)
	{
            $ats = TempUpdateDetail::find($id);
            if (null === $ats) {
                return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
            }
            $ats->update($request->all());
            return ApiResponse::success($ats->fresh());
    }
    
    public function savetempupdate(Request $request)
	{
        // TempUpdate::create($request->all());
        $temp = TempUpdate::create($request->all());

        return ApiResponse::success($temp->id);

	}

    public function savetempupdatedetail(Request $request)
	{    
        TempUpdateDetail::create($request->all());
    }

    public function removetempupdate(Request $request)
	{

        foreach ($request->all() as $field => $find) {

			if ($field == 'table_nm')  {
                $fld1='table_nm';
                $a=$find;
            }else if ($field == 'tableid'){
                $fld2='tableid';
                $b=$find;
            }
        }
            $navaid = TempUpdate::where($fld1,$a)
            ->where($fld2, $b)
            ->delete();

            if (null === $navaid) {
                return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
            }



            return ApiResponse::success();


        
    }

    public function removetempupdateallident(Request $request)
	{
        echo  $request;
        foreach ($request->all() as $field => $find) {

			if ($field == 'table_nm')  {
                $fld1='table_nm';
                $a=$find;
            }else if ($field == 'tableid'){
                $fld2='tableid';
                $b=$find;
            }
        }
            $navaid = TempUpdate::where('table_nm',$a)
            ->where('tableid','like',"%{$b}%")
            ->delete();

            if (null === $navaid) {
                return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
            }

            return ApiResponse::success();
    }

    public function eaipgencontent(Request $request, RequestParamHandler $rpm)
    {

        $results = $rpm->process($request, EaipGenContent::query());
        // dd($request);
		return ApiResponse::success($results);
    }
    // table content
    public function gencontent(Request $request, RequestParamHandler $rpm)
    {

        $results = $rpm->process($request, Content::query());
        // dd($request);
        return ApiResponse::success($results);
    }

    public function eaipgencontenttemp(Request $request, RequestParamHandler $rpm)
    {

        $results = $rpm->process($request, EaipGenContentTemp::query());

		return ApiResponse::success($results);
    }

    public function eaipgencontenttempupdate(Request $request,string $id)
    {
        $ats = EaipGenContentTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }

    public function eaipgencontentupdate(Request $request,string $id)
    {
        $ats = EaipGenContent::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->update($request->all());

		return ApiResponse::success($ats->fresh());
    }

    public function eaipgencontenttempsave(Request $request)
    {
        $temp = EaipGenContentTemp::create($request->all());

        return ApiResponse::success($temp->id);
    }

    public function eaipgencontentsave(Request $request)
    {
        $temp = EaipGenContent::create($request->all());

        return ApiResponse::success($temp->id);
    }


    public function eaipgencontenttempremove(Request $request,string $id)
    {

        $ats = EaipGenContentTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function eaipgencontentremove(Request $request,string $id)
    {

        $ats = EaipGenContentTemp::find($id);

		if (null === $ats) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$ats->delete();

		return ApiResponse::success($ats->fresh());
    }

    public function ambilarray(request $request)
    {
        $id = $request->input('id');
        $arr=[];
        switch ($id) {
            case '15':
                $arr = array("VOL II","VOL III","VOL IV","VOL V");
                break;
            case '8':
                $arr = array("AFIZ","ATZ","CTA","CTR","FIR","SECTOR","MTCA","TIBA","TMA","UTA");
                break;
            case '66':
                $arr = array("LOCATOR","ILS","NDB","RADAR HEAD","TACAN","VOR","VOR/DME");
                break;
            case '68':
                $arr = array("ENROUTE & TERMINAL WPT","ENROUTE WPT","TERMINAL WPT","VFR WPT");
                break;
            case '70':
            case '71':
                $arr = array("ALERT","DANGER","MILITARY OPERATIONS AREA","PROHIBITED","RESTRICTED","TRAINING AREAS","WARNING");
                break;
            default:
                # code...
                break;
        }


        return ApiResponse::success($arr);
    }
}
