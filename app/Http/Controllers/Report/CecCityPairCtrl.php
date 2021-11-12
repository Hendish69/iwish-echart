<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, View, Validator};
use Illuminate\Http\Response;

use App\Models\Api\CecFpl as Fpl;
use App\Models\Api\CecEmission as Emission;
use DB;

class CecCityPairCtrl extends Controller
{
    private $page;

    public function __construct()
    {
        $this->page = (object)['title'=>'Emissions Report - City Pair'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page']  = $this->page;
        return View::make('pages.inavcec.emcitypair',$data);
    }
    
    public function cpgetData(Request $request){
        ## Read value
        $type = $request->get('type');
        
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

        $_where = '';
        switch ($type) {
        	case 'daily'; 
    			$day 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy-mm-dd') = '".$day."'";
        	break;
        	case 'monthly'; 
    			$mon 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy-mm') = '".$mon."'";
        	break;
        	case 'annual'; 
    			$year 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy') = '".$year."'";
        	break;
        	case 'range'; 
    			$day 	= $request->get('reqs') ;
    			$day1 	= $request->get('reqs1') ;
    			$_where = "atd::date between '".$day."' and '".$day1."'";
        	break;
        }

        // Total records
        $totalRecords = FPL::selectRaw('count(*) as allcount')
                        ->leftJoin('cec_emission', function ($join) {
                            $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                         })
                         ->whereRaw($_where)
                         ->where('cec_emission.emissiontotal','>',0) 
                        ->groupByRaw('GROUPING SETS ( (adep_id, ades_id) )')
                        ->get()->count();

        $totalRecordswithFilter = FPL::selectRaw('count(*) as allcount')
                                ->leftJoin('cec_emission', function ($join) {
                                    $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                                 })
                                 ->whereRaw($_where)
                                 ->where('acid', 'like', '%' .$searchValue . '%')
                                ->where('cec_emission.emissiontotal','>',0)
                                ->groupByRaw('GROUPING SETS ( (adep_id, ades_id) )')
                                ->get()->count();
        $records =  FPL::whereRaw($_where)
                    ->selectRaw('   
                                    CONCAT(adep_id, \'-\', ades_id) AS "City_Pair",
                                    count(acft_id) as "Flights",
                                    coalesce(round(SUM (cec_emission.emissionstart),2),0) "Start",
                                    coalesce(round(SUM (cec_emission.emissiontaxiout),2),0) "TaxiOut",
                                    coalesce(round(SUM (cec_emission.emissiongndholding),2),0) "GndHolding",
                                    coalesce(round(SUM (cec_emission.emissiontakeoff),2),0) "TakeOff",
                                    coalesce(round(SUM (cec_emission.emissionclimb),2),0) "Climb",
                                    coalesce(round(SUM (cec_emission.emissioncruise),2),0) "Cruise",
                                    coalesce(round(SUM (cec_emission.emissiondescend),2),0) "Descend",
                                    coalesce(round(SUM (cec_emission.emissionholding),2),0) "Holding",
                                    coalesce(round(SUM (cec_emission.emissionapproach),2),0) "Approach",
                                    coalesce(round(SUM (cec_emission.emissionlanding),2),0) "Landing",
                                    coalesce(round(SUM (cec_emission.emissiontaxiin),2),0) "TaxiIn",
                                    coalesce(round(SUM (cec_emission.emissiontotal),2),0) "Total"
                                ')
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     }) 
                    ->where('cec_emission.emissiontotal','>',0)
                    ->groupByRaw('GROUPING SETS (
                            (adep_id, ades_id)
                        )')
                    ->skip($start)
                    ->take($rowperpage)
                    ->get();   
        // Fetch records
        
        $data_arr = array(); 
        foreach($records as $record){ 
	        $City_Pair  = $record->City_Pair;
	        $Flights    = floatval($record->Flights); 
	        $Start      = floatval($record->Start);
	        $TaxiOut    = floatval($record->TaxiOut);
	        $GndHolding = floatval($record->GndHolding);
	        $TakeOff    = floatval($record->TakeOff);
	        $Climb      = floatval($record->Climb);
	        $Cruise     = floatval($record->Cruise);
	        $Descend    = floatval($record->Descend);
	        $Holding    = floatval($record->Holding);
	        $Approach   = floatval($record->Approach);
	        $Landing    = floatval($record->Landing);
	        $TaxiIn     = floatval($record->TaxiIn);
	        $Total      = floatval($record->Total); 
	        $data_arr[] = array(
                "City_Pair"     => $City_Pair,
                "Flights"       => $Flights,
                "Start"         => $Start,
                "TaxiOut"       => $TaxiOut,
                "GndHolding"    => $GndHolding,
                "TakeOff"       => $TakeOff,
                "Climb"         => $Climb,
                "Cruise"        => $Cruise,
                "Descend"       => $Descend,
                "Holding"       => $Holding,
                "Approach"      => $Approach,
                "Landing"       => $Landing,
                "TaxiIn"        => $TaxiIn,
                "Total"         => $Total
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

    public function cpTabelPie(Request $request){
        
        ## Read value
        $type = $request->get('type');
        
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

        $_where = '';
        switch ($type) {
            case 'daily'; 
                $day    = $request->get('reqs') ;
                $_where = "to_char(atd::DATE,'yyyy-mm-dd') = '".$day."'";
            break;
            case 'monthly'; 
                $mon    = $request->get('reqs') ;
                $_where = "to_char(atd::DATE,'yyyy-mm') = '".$mon."'";
            break;
            case 'annual'; 
                $year   = $request->get('reqs') ;
                $_where = "to_char(atd::DATE,'yyyy') = '".$year."'";
            break;
            case 'range'; 
                $day    = $request->get('reqs') ;
                $day1   = $request->get('reqs1') ;
                $_where = "atd::date between '".$day."' and '".$day1."'";
            break;
        }
        // Total records
        $totalRecords = FPL::selectRaw(' count(*) as allcount')
                        ->leftJoin('cec_emission', function ($join) {
                                $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                        }) 
                        ->whereRaw($_where.' and cec_emission.emissiontotal > 0')
                        ->groupByRaw('GROUPING SETS (
                            (adep_id, ades_id)
                        )')->get()->count();
        $totalRecordswithFilter =   FPL::selectRaw(' count(*) as allcount')
                                        ->leftJoin('cec_emission', function ($join) {
                                            $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                                         }) 
                                        ->whereRaw($_where)
                                        ->where('acid', 'like', '%' .$searchValue . '%')
                                        ->where('cec_emission.emissiontotal','>',0)
                                        ->groupByRaw('GROUPING SETS (
                                                (adep_id, ades_id)
                                            )')
                                        ->get()->count();
        
        // Fetch records
        $records =  FPL::selectRaw('	
                    				CONCAT(adep_id, \'-\', ades_id) AS "City_Pair",    
    								SUM (cec_emission.emissiontotal) AS "Amount"
							  ')
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     }) 
                    ->whereRaw($_where)
                    ->where('cec_emission.emissiontotal','>',0)
                    ->groupByRaw('GROUPING SETS (
					        (adep_id, ades_id)
					    )')
                    ->skip($start)
                    ->take($rowperpage)
                    ->get()->toArray();  

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );

        echo json_encode($response);
        exit; 
    }

    public function cpPieChart(Request $request){
    	$type = $request->get('type');
    	$_where = '';
        switch ($type) {
        	case 'daily'; 
    			$day 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy-mm-dd') = '".$day."'";
        	break;
        	case 'monthly'; 
    			$mon 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy-mm') = '".$mon."'";
        	break;
        	case 'annual'; 
    			$year 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy') = '".$year."'";
        	break;
        	case 'range'; 
    			$day 	= $request->get('reqs') ;
    			$day1 	= $request->get('reqs1') ;
    			$_where = "atd between '".$day."' and '".$day1."'";
        	break;
        }
        // Fetch records
        $records =  FPL::whereRaw($_where)
                    ->selectRaw('	
                    				CONCAT(adep_id, \'-\', ades_id) AS "name",    
    								SUM (cec_emission.emissiontotal) AS "y"
								')
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                    })
                    ->where('cec_emission.emissiontotal','>',0)
                    ->orderby('y','desc')
                    ->groupByRaw('GROUPING SETS (
					        (adep_id, ades_id)
					    )')
                    ->get();
     	$data_arr = array(); 
        foreach($records as $record){ 
	        $name = $record->name;
	        $y    = $record->y; 
	        $data_arr[] = array(
	            "name" => $name,
	            "y"    => floatval($y)
	        );
        } 
		echo json_encode($data_arr); 
        exit; 
    }
    public function cpgetBarChart(Request $request){
    	$type = $request->get('type');
    	$_where = '';
        switch ($type) {
        	case 'daily'; 
    			$day 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy-mm-dd') = '".$day."'";
        	break;
        	case 'monthly'; 
    			$mon 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy-mm') = '".$mon."'";
        	break;
        	case 'annual'; 
    			$year 	= $request->get('reqs') ;
    			$_where = "to_char(atd::DATE,'yyyy') = '".$year."'";
        	break;
        	case 'range'; 
    			$day 	= $request->get('reqs') ;
    			$day1 	= $request->get('reqs1') ;
    			$_where = "atd between '".$day."' and '".$day1."'";
        	break;
        }
         // Fetch records
        $records =  FPL::whereRaw($_where)
                    ->selectRaw('	
                    				CONCAT(adep_id, \'-\', ades_id) AS "City_Pair",
							    	count(acft_id) as "Flights",
							    	coalesce(round(SUM (cec_emission.emissionstart),2),0) "Start",
									coalesce(round(SUM (cec_emission.emissiontaxiout),2),0) "TaxiOut",
									coalesce(round(SUM (cec_emission.emissiongndholding),2),0) "GndHolding",
									coalesce(round(SUM (cec_emission.emissiontakeoff),2),0) "TakeOff",
									coalesce(round(SUM (cec_emission.emissionclimb),2),0) "Climb",
									coalesce(round(SUM (cec_emission.emissioncruise),2),0) "Cruise",
									coalesce(round(SUM (cec_emission.emissiondescend),2),0) "Descend",
									coalesce(round(SUM (cec_emission.emissionholding),2),0) "Holding",
									coalesce(round(SUM (cec_emission.emissionapproach),2),0) "Approach",
									coalesce(round(SUM (cec_emission.emissionlanding),2),0) "Landing",
									coalesce(round(SUM (cec_emission.emissiontaxiin),2),0) "TaxiIn",
									coalesce(round(SUM (cec_emission.emissiontotal),2),0) "Total"
								')
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     })
                     ->where('cec_emission.emissiontotal','>',0) 
                    ->groupByRaw('GROUPING SETS (
					        (adep_id, ades_id)
					    )')
                    ->get();
        $datasBar = [];
        
        foreach($records as $record){ 
	        $name = $record->City_Pair;
	        $y    = $record->Total; 
	        array_push($datasBar,array( $name, floatval($y)));
        }  
         
        $datamBar = []; 
        $mName = [ "City Pair", "Start","TaxiOut","GndHolding","TakeOff","Climb","Cruise","Descend","Holding","Approach","Landing","TaxiIn" ];
 
		foreach ($records as $row) {
			array_push($datamBar, [$row->City_Pair, floatval($row->Start), floatval($row->TaxiOut), floatval($row->GndHolding), floatval($row->TakeOff), floatval($row->Climb), floatval($row->Cruise), floatval($row->Descend), floatval($row->Holding), floatval($row->Approach), floatval($row->Landing), floatval($row->TaxiIn)]);
		} 
        array_unshift($datamBar,$mName); 
        $res['datasBar']  = $datasBar;  
        $res['datamBar'] = $datamBar;  
		echo json_encode($res); 
        exit; 
    }
}