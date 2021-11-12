<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, View, Validator};
use Illuminate\Http\Response;

use App\Models\Api\CecFpl as Fpl;
use App\Models\Api\CecEmission as Emission;
use DB;

class CecDomInCtrl extends Controller
{
    private $page;

    public function __construct()
    {
        $this->page = (object)['title'=>'Emissions Report - Domestic and International'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page']  = $this->page;
        return View::make('pages.inavcec.emdomin',$data);
    }
    
    public function domgetData(Request $request){
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

        // Total records
        $totalRecords = FPL::select('count(*) as allcount')
                        ->leftJoin('cec_emission', function ($join) {
                            $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                         }) 
                        ->groupByRaw('GROUPING SETS ( (adep_id, ades_id) )')
                        ->count();

        $totalRecordswithFilter = FPL::select('count(*) as allcount')->where('acid', 'like', '%' .$searchValue . '%')
                                ->leftJoin('cec_emission', function ($join) {
                                    $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                                 }) 
                                ->groupByRaw('GROUPING SETS ( (adep_id, ades_id) )')
                                ->count();
        $_where = '';
        switch ($type) {
        	case 'daily'; 
    			$day 	= $request->get('reqs') ;
    			$_where = "to_char(cf.atd::DATE,'yyyy-mm-dd') = '".$day."'";
        	break;
        	case 'monthly'; 
    			$mon 	= $request->get('reqs') ;
    			$_where = "to_char(cf.atd::DATE,'yyyy-mm') = '".$mon."'";
        	break;
        	case 'annual'; 
    			$year 	= $request->get('reqs') ;
    			$_where = "to_char(cf.atd::DATE,'yyyy') = '".$year."'";
        	break;
        	case 'range'; 
    			$day 	= $request->get('reqs') ;
    			$day1 	= $request->get('reqs1') ;
    			$_where = "cf.atd between '".$day."' and '".$day1."'";
        	break;
        }
        // $records =  DB::select("
        //                 with regs as ( 
        //                 select 'I' reg union select 'D' reg), 
        //                 counts as (
        //                     select 
        //                         coalesce(nullif(ftype,'I'),'I','D') ftype,
        //                         CASE
        //                             WHEN regs.reg = 'I' THEN 'International'
        //                             WHEN regs.reg = 'D' THEN 'Domestic'
        //                         END typeofflight,   
        //                         coalesce(round(SUM (ce.emissionstart),2),0) \"Start\",
        //                         coalesce(round(SUM (ce.emissiontaxiout),2),0) \"TaxiOut\",
        //                         coalesce(round(SUM (ce.emissiongndholding),2),0) \"GndHolding\",
        //                         coalesce(round(SUM (ce.emissiontakeoff),2),0) \"TakeOff\",
        //                         coalesce(round(SUM (ce.emissionclimb),2),0) \"Climb\",
        //                         coalesce(round(SUM (ce.emissioncruise),2),0) \"Cruise\",
        //                         coalesce(round(SUM (ce.emissiondescend),2),0) \"Descend\",
        //                         coalesce(round(SUM (ce.emissionholding),2),0) \"Holding\",
        //                         coalesce(round(SUM (ce.emissionapproach),2),0) \"Approach\",
        //                         coalesce(round(SUM (ce.emissionlanding),2),0) \"Landing\",
        //                         coalesce(round(SUM (ce.emissiontaxiin),2),0) \"TaxiIn\",
        //                         coalesce(round(SUM (ce.emissiontotal),2),0) \"Total\",
        //                         cec_fpl.ata as \"Ata\" 
        //                     FROM regs
        //                     left join cec_fpl  on cec_fpl.ftype = regs.reg
        //                     left join cec_emission ce on ce.fpl_id = cec_fpl.id
        //                     group by regs.reg, ftype, \"Ata\"
        //                 )
        //             select
        //                 \"Ata\",ftype,typeofflight,\"Start\",\"TaxiOut\",\"GndHolding\",\"TakeOff\",\"Climb\",\"Cruise\",\"Descend\",\"Holding\",\"Approach\",\"Landing\",\"TaxiIn\",\"Total\"
        //             from counts 
        //             where ".$_where." 
        //             order by ftype
        //             ");   
        // Fetch records
        $records =  DB::select("
                    select 'Domestic' ft, 
                    tab1. * from ( select 
                                        coalesce(round(SUM (ce.emissionstart),2),0) \"Start\",
                                        coalesce(round(SUM (ce.emissiontaxiout),2),0) \"TaxiOut\",
                                        coalesce(round(SUM (ce.emissiongndholding),2),0) \"GndHolding\",
                                        coalesce(round(SUM (ce.emissiontakeoff),2),0) \"TakeOff\",
                                        coalesce(round(SUM (ce.emissionclimb),2),0) \"Climb\",
                                        coalesce(round(SUM (ce.emissioncruise),2),0) \"Cruise\",
                                        coalesce(round(SUM (ce.emissiondescend),2),0) \"Descend\",
                                        coalesce(round(SUM (ce.emissionholding),2),0) \"Holding\",
                                        coalesce(round(SUM (ce.emissionapproach),2),0) \"Approach\",
                                        coalesce(round(SUM (ce.emissionlanding),2),0) \"Landing\",
                                        coalesce(round(SUM (ce.emissiontaxiin),2),0) \"TaxiIn\",
                                        coalesce(round(SUM (ce.emissiontotal),2),0) \"Total\"
                                    from cec_fpl cf 
                                    left join cec_emission ce on ce.fpl_id = cf.id 
                                    where cf.ftype ='D' 
                                    and ".$_where."
                                    group by cf.ftype 
                                ) tab1
                    union
                    select 'International' ft, 
                    tab2. * from ( select 
                                        coalesce(round(SUM (ce.emissionstart),2),0) \"Start\",
                                        coalesce(round(SUM (ce.emissiontaxiout),2),0) \"TaxiOut\",
                                        coalesce(round(SUM (ce.emissiongndholding),2),0) \"GndHolding\",
                                        coalesce(round(SUM (ce.emissiontakeoff),2),0) \"TakeOff\",
                                        coalesce(round(SUM (ce.emissionclimb),2),0) \"Climb\",
                                        coalesce(round(SUM (ce.emissioncruise),2),0) \"Cruise\",
                                        coalesce(round(SUM (ce.emissiondescend),2),0) \"Descend\",
                                        coalesce(round(SUM (ce.emissionholding),2),0) \"Holding\",
                                        coalesce(round(SUM (ce.emissionapproach),2),0) \"Approach\",
                                        coalesce(round(SUM (ce.emissionlanding),2),0) \"Landing\",
                                        coalesce(round(SUM (ce.emissiontaxiin),2),0) \"TaxiIn\",
                                        coalesce(round(SUM (ce.emissiontotal),2),0) \"Total\"
                                    from cec_fpl cf 
                                    left join cec_emission ce on ce.fpl_id = cf.id 
                                    where cf.ftype ='I' 
                                    and ".$_where."
                                    group by cf.ftype 
                                ) tab2
                    ");
        $data_arr = array(); 
        foreach($records as $record){ 
	        $TypeOFlight  = $record->ft; 
	        $Start        = floatval($record->Start);
	        $TaxiOut      = floatval($record->TaxiOut);
	        $GndHolding   = floatval($record->GndHolding);
	        $TakeOff      = floatval($record->TakeOff);
	        $Climb        = floatval($record->Climb);
	        $Cruise       = floatval($record->Cruise);
	        $Descend      = floatval($record->Descend);
	        $Holding      = floatval($record->Holding);
	        $Approach     = floatval($record->Approach);
	        $Landing      = floatval($record->Landing);
	        $TaxiIn       = floatval($record->TaxiIn);
	        $Total        = floatval($record->Total); 
	        $data_arr[] = array(
                "TypeOFlight"   => $TypeOFlight, 
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

    public function domTabelPie(Request $request){
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

        // Total records
        $totalRecords = FPL::select('count(*) as allcount')->count();

        $totalRecordswithFilter = FPL::select('count(*) as allcount')->where('acid', 'like', '%' .$searchValue . '%')->count();
        $_where = '';
        switch ($type) {
        	case 'daily'; 
    			$day 	= $request->get('reqs') ;
    			$_where = "to_char(cf.atd::DATE,'yyyy-mm-dd') = '".$day."'";
        	break;
        	case 'monthly'; 
    			$mon 	= $request->get('reqs') ;
    			$_where = "to_char(cf.atd::DATE,'yyyy-mm') = '".$mon."'";
        	break;
        	case 'annual'; 
    			$year 	= $request->get('reqs') ;
    			$_where = "to_char(cf.atd::DATE,'yyyy') = '".$year."'";
        	break;
        	case 'range'; 
    			$day 	= $request->get('reqs') ;
    			$day1 	= $request->get('reqs1') ;
    			$_where = "cf.atd::date between '".$day."' and '".$day1."'";
        	break;
        }
        // Fetch records
        $records =  DB::select("
                    select 'Domestic' typeofflight, 
                    coalesce (  ( select coalesce(round(SUM (ce.emissiontotal),2),0) sub_total
                                from cec_fpl cf 
                                left join cec_emission ce on ce.fpl_id = cf.id 
                                where cf.ftype ='D' 
                                and ".$_where."
                                group by cf.ftype ), 0 ) amount 
                    union 
                    select 'International' typeofflight, 
                    coalesce (  ( select coalesce(round(SUM (ce.emissiontotal),2),0) sub_total
                                from cec_fpl cf 
                                left join cec_emission ce on ce.fpl_id = cf.id 
                                where cf.ftype ='I' 
                                and ".$_where."
                                group by cf.ftype ), 0 ) amount	
                    			");  

        $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $records
        );

        echo json_encode($response);
        exit; 
    }

    public function domPieChart(Request $request){
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
    			$_where = "atd::date between '".$day."' and '".$day1."'";
        	break;
        }
        // Fetch records
       $records =  DB::select("
                    select 'Domestic' as \"name\", 
                    coalesce (  ( select coalesce(round(SUM (ce.emissiontotal),2),0) sub_total
                                from cec_fpl cf 
                                left join cec_emission ce on ce.fpl_id = cf.id 
                                where cf.ftype ='D' 
                                and ".$_where."
                                group by cf.ftype ), 0 ) y 
                    union 
                    select 'International' \"name\", 
                    coalesce (  ( select coalesce(round(SUM (ce.emissiontotal),2),0) sub_total
                                from cec_fpl cf 
                                left join cec_emission ce on ce.fpl_id = cf.id 
                                where cf.ftype ='I' 
                                and ".$_where."
                                group by cf.ftype ), 0 ) y 
                                ");  
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
    public function domgetBarChart(Request $request){
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
    			$_where = "atd::date between '".$day."' and '".$day1."'";
        	break;
        }
         // Fetch records
        $records =  DB::select("
                    select 'Domestic' ft, 
                    tab1. * from ( select 
                                        coalesce(round(SUM (ce.emissionstart),2),0) \"Start\",
                                        coalesce(round(SUM (ce.emissiontaxiout),2),0) \"TaxiOut\",
                                        coalesce(round(SUM (ce.emissiongndholding),2),0) \"GndHolding\",
                                        coalesce(round(SUM (ce.emissiontakeoff),2),0) \"TakeOff\",
                                        coalesce(round(SUM (ce.emissionclimb),2),0) \"Climb\",
                                        coalesce(round(SUM (ce.emissioncruise),2),0) \"Cruise\",
                                        coalesce(round(SUM (ce.emissiondescend),2),0) \"Descend\",
                                        coalesce(round(SUM (ce.emissionholding),2),0) \"Holding\",
                                        coalesce(round(SUM (ce.emissionapproach),2),0) \"Approach\",
                                        coalesce(round(SUM (ce.emissionlanding),2),0) \"Landing\",
                                        coalesce(round(SUM (ce.emissiontaxiin),2),0) \"TaxiIn\",
                                        coalesce(round(SUM (ce.emissiontotal),2),0) \"Total\"
                                    from cec_fpl cf 
                                    left join cec_emission ce on ce.fpl_id = cf.id 
                                    where cf.ftype ='D' 
                                    and ".$_where."
                                    group by cf.ftype 
                                ) tab1
                    union
                    select 'International' ft, 
                    tab2. * from ( select 
                                        coalesce(round(SUM (ce.emissionstart),2),0) \"Start\",
                                        coalesce(round(SUM (ce.emissiontaxiout),2),0) \"TaxiOut\",
                                        coalesce(round(SUM (ce.emissiongndholding),2),0) \"GndHolding\",
                                        coalesce(round(SUM (ce.emissiontakeoff),2),0) \"TakeOff\",
                                        coalesce(round(SUM (ce.emissionclimb),2),0) \"Climb\",
                                        coalesce(round(SUM (ce.emissioncruise),2),0) \"Cruise\",
                                        coalesce(round(SUM (ce.emissiondescend),2),0) \"Descend\",
                                        coalesce(round(SUM (ce.emissionholding),2),0) \"Holding\",
                                        coalesce(round(SUM (ce.emissionapproach),2),0) \"Approach\",
                                        coalesce(round(SUM (ce.emissionlanding),2),0) \"Landing\",
                                        coalesce(round(SUM (ce.emissiontaxiin),2),0) \"TaxiIn\",
                                        coalesce(round(SUM (ce.emissiontotal),2),0) \"Total\"
                                    from cec_fpl cf 
                                    left join cec_emission ce on ce.fpl_id = cf.id 
                                    where cf.ftype ='I' 
                                    and ".$_where."
                                    group by cf.ftype 
                                ) tab2
                    ");
        $datasBar = [];
        
        foreach($records as $record){ 
	        $name = $record->ft;
	        $y    = $record->Total; 
	        array_push($datasBar,array( $name, floatval($y)));
        }  
         
        $datamBar = []; 
        $mName = [ "Type of Flight", "Start","TaxiOut","GndHolding","TakeOff","Climb","Cruise","Descend","Holding","Approach","Landing","TaxiIn" ];
 
		foreach ($records as $row) {
			array_push($datamBar, [$row->ft, floatval($row->Start), floatval($row->TaxiOut), floatval($row->GndHolding), floatval($row->TakeOff), floatval($row->Climb), floatval($row->Cruise), floatval($row->Descend), floatval($row->Holding), floatval($row->Approach), floatval($row->Landing), floatval($row->TaxiIn)]);
		} 
        array_unshift($datamBar,$mName); 
        $res['datasBar']  = $datasBar;  
        $res['datamBar'] = $datamBar;  
		echo json_encode($res); 
        exit; 
    }
}