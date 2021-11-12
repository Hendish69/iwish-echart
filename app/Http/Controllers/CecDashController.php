<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, View, Validator};
use Illuminate\Http\Response;

use App\Models\Api\CecFpl as Fpl;
use App\Models\Api\CecEmission as Emission;
use DB;
class CecDashController extends Controller
{
    
    private $page;

    public function __construct()
    {
        $this->page = (object)['title'=>'DASHBOARD'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page']  = $this->page;
        $data['graph'] = $this->getLastEmTotal();
        return View::make('pages.inavcec.dashboard',$data);
    }
    public function getLastEmTotal(){
        $data = DB::select("
            with days as (
                SELECT date_trunc('day', d)::date as day
                FROM generate_series(CURRENT_DATE-6, CURRENT_DATE, '1 day'::interval) d ),
            counts as (
                select 
                    days.day,
                    coalesce(round(SUM (cec_emission.emissiontotal)/1000 ,2),0) emton

                FROM days
                left join cec_emission on cec_emission.created_at::timestamp::date = days.day
                group by days.day
            )
            select
                day,
                emton,
                concat(emton, ' ton') as labelton
            from counts
            order by day
            ");
        return $data;
        // echo json_encode($data);die;
    }
    public function getCityPair(Request $request){
          
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $search_arr = $request->get('search');
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = FPL::select('count(*) as allcount')
                        ->selectRaw("CONCAT(adep_id, '-', ades_id) AS city_pair, SUM (cec_emission.emissiontotal) as sub_total")
                        ->leftJoin('cec_emission', function ($join) {
                            $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                         })
                        ->where('cec_emission.emissiontotal','>',0)
                        ->groupBy(DB::raw('GROUPING SETS ( (adep_id , ades_id) )')) 
                        ->count();

        $totalRecordswithFilter = FPL::select('count(*) as allcount')
                                ->selectRaw("CONCAT(adep_id, '-', ades_id) AS city_pair, SUM (cec_emission.emissiontotal) as sub_total")
                                ->leftJoin('cec_emission', function ($join) {
                                    $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                                 })
                                ->where('cec_emission.emissiontotal','>',0) 
                                ->where('adep_id', 'like', '%' .$searchValue . '%')
                                ->groupBy(DB::raw('GROUPING SETS ( (adep_id , ades_id) )'))
                                ->count();

        // Fetch records
        $records =  FPL::orderBy($columnName,$columnSortOrder)
                    ->selectRaw("CONCAT(adep_id, '-', ades_id) AS city_pair, SUM (cec_emission.emissiontotal) as sub_total")
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     })
                    ->where('cec_emission.emissiontotal','>',0)
                    ->groupBy(DB::raw('GROUPING SETS ( (adep_id , ades_id) )'))
                    ->skip($start)
                    ->take($rowperpage)
                    ->get();   
        // var_dump($records);
        $data_arr = array(); 
        foreach($records as $record){ 
            $city_pair  = $record->city_pair;
            $sub_total  = $record->sub_total;
            $data_arr[] = array(
                "city_pair" => $city_pair,
                "sub_total" => $sub_total
            );
        } 
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit; 
    }
    public function getEmDomInter(Request $request){
          
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $search_arr = $request->get('search');
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = FPL::select('count(*) as allcount')->count();

        $totalRecordswithFilter = FPL::select('count(*) as allcount')->where('ftype', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        // $records =  FPL::orderBy($columnName,$columnSortOrder)
        //             ->selectRaw("CASE
        //                             WHEN ftype = 'D' THEN 'Domestic'
        //                             WHEN ftype = 'I' THEN 'International'
        //                          END typeofflight,   
        //                          SUM (cec_emission.emissiontotal) as sub_total")
        //             ->leftJoin('cec_emission', function ($join) {
        //                 $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
        //              })
        //             ->groupBy('ftype')
        //             ->skip($start)
        //             ->take($rowperpage)
        //             ->get();  
        $records = DB::select("
                    with regs as ( 
                    select 'I' reg union select 'D' reg), 
                    counts as (
                        select 
                             coalesce(ftype,'I','I') ftype,
                             CASE
                                WHEN regs.reg = 'I' THEN 'International'
                                WHEN regs.reg = 'D' THEN 'Domestic'
                             END typeofflight,   
                            coalesce(round(SUM (cec_emission.emissiontotal),2),0) sub_total
                        FROM regs
                        left join cec_fpl on cec_fpl.ftype = regs.reg
                        left join cec_emission on cec_emission.fpl_id = cec_fpl.id 
                        group by regs.reg, ftype
                    )
                select
                    ftype,
                    typeofflight,
                    sub_total
                from counts
                order by ftype"
        ); 
        $data_arr = array(); 
        foreach($records as $record){ 
            $typeofflight  = $record->typeofflight;
            $sub_total     = $record->sub_total;
            $data_arr[]    = array(
                "typeofflight" => $typeofflight,
                "sub_total"    => $sub_total
            );
        } 
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit; 
    }
    
    public function getLastData(Request $request){
        ## Read value
         
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = FPL::select('count(*) as allcount')
                        ->leftJoin('cec_emission', function ($join) {
                            $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                         })
                        ->where('cec_emission.emissiontotal','>',0)
                        ->count();

        $totalRecordswithFilter = FPL::select('count(*) as allcount')
                                ->leftJoin('cec_emission', function ($join) {
                                    $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                                 })
                                ->where('cec_emission.emissiontotal','>',0)
                                ->where('acid', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records =  FPL::orderBy($columnName,$columnSortOrder)
                    ->where('cec_fpl.acid', 'like', '%' .$searchValue . '%')
                    ->select('acid', 'adep_id', 'atd', 'ades_id', 'ata', 'cec_emission.*')
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     })
                    ->where('cec_emission.emissiontotal','>',0)
                    ->skip($start)
                    ->take($rowperpage)
                    ->get();   
        // var_dump($records);
        $data_arr = array(); 
        foreach($records as $record){ 
        $fpl_id             = $record->fpl_id;
        $acid               = $record->acid;
        $adep_id            = $record->adep_id;
        $atd                = $record->atd;
        $ades_id            = $record->ades_id;
        $ata                = $record->ata;
        $emissionstart      = $record->emissionstart;
        $emissiontaxiout    = $record->emissiontaxiout;
        $emissiongndholding = $record->emissiongndholding;
        $emissiontakeoff    = $record->emissiontakeoff;
        $emissionclimb      = $record->emissionclimb;
        $emissioncruise     = $record->emissioncruise;
        $emissiondescend    = $record->emissiondescend;
        $emissionholding    = $record->emissionholding;
        $emissionapproach   = $record->emissionapproach;
        $emissionlanding    = $record->emissionlanding;
        $emissiontaxiin     = $record->emissiontaxiin;
        $emissiontotal      = $record->emissiontotal;
 
        $data_arr[] = array(
            "fpl_id"            => $fpl_id,
            "acid"              => $acid,
            "adep_id"           => $adep_id,
            "atd"               => $atd,
            "ades_id"           => $ades_id,
            "ata"               => $ata,
            "emissionstart"     => $emissionstart,
            "emissiontaxiout"   => $emissiontaxiout,
            "emissiongndholding"=> $emissiongndholding,
            "emissiontakeoff"   => $emissiontakeoff,
            "emissionclimb"     => $emissionclimb,
            "emissioncruise"    => $emissioncruise,
            "emissiondescend"   => $emissiondescend,
            "emissionholding"   => $emissionholding,
            "emissionapproach"  => $emissionapproach,
            "emissionlanding"   => $emissionlanding,
            "emissiontaxiin"    => $emissiontaxiin,
            "emissiontotal"     => $emissiontotal
        );
        } 
        $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
        );

        echo json_encode($response);
        exit; 
    }

}
