<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, View, Validator};
use Illuminate\Http\Response;

use App\Models\Api\CecFpl as Fpl;
use App\Models\Api\CecEmission as Emission;
use DB;

class CecEmPeriodicCtrl extends Controller
{
    private $page;

    public function __construct()
    {
        $this->page = (object)['title'=>'Emissions Report - Periodic'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page']  = $this->page;
        return View::make('pages.inavcec.emperiodic',$data);
    }
    public function getData(Request $request){
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
    			$_where = "atd between '".$day."' and '".$day1."'";
        	break;
        }
         // Total records
        $totalRecords = FPL::select('count(*) as allcount')
				        ->leftJoin('cec_emission', function ($join) {
				                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
				                     })
                    	->where('cec_emission.emissiontotal','>',0)
                    	->whereRaw($_where)
        				->count();

        $totalRecordswithFilter = FPL::select('count(*) as allcount')->where('acid', 'like', '%' .$searchValue . '%')
        			->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     })
                    ->where('cec_emission.emissiontotal','>',0)
                    ->whereRaw($_where)
                    ->count();
        // Fetch records
        $records =  FPL::whereRaw($_where)
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
    public function emTablePie(Request $request){
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
                    				sum(emissionstart) "Start",
									sum(emissiontaxiout) "TaxiOut", 
									sum(emissiongndholding) "GndHolding", 
									sum(emissiontakeoff) "TakeOff",
									sum(emissionclimb) "Climb", 
									sum(emissioncruise) "Cruise",
									sum(emissiondescend) "Descend", 
									sum(emissionholding) "Holding",
									sum(emissionapproach) "Approach", 
									sum(emissionlanding) "Landing", 
									sum(emissiontaxiin) "TaxiIn" 
								')
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     })                     
                    ->skip($start)
                    ->take($rowperpage)
                    ->get();  
        
        $pieName = [ 'Start','TaxiOut','GndHolding','TakeOff','Climb','Cruise','Descend','Holding','Approach','Landing','TaxiIn' ];

     	$data_arr = array();
     	 
     	foreach($pieName as $name){
	        foreach($records as $record){
	        	$iname 	= $name;
		        $y    	= $record->$name; 
		        $data_arr[] = array(
		            "name" => $iname,
		            "y"    => floatval($y) 
		        );
	        } 
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
    public function getPieTotal(Request $request){
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
        $records =  FPL::whereRaw($_where)
                    ->selectRaw('	
                    				sum(emissionstart) "Start",
									sum(emissiontaxiout) "TaxiOut", 
									sum(emissiongndholding) "GndHolding", 
									sum(emissiontakeoff) "TakeOff",
									sum(emissionclimb) "Climb", 
									sum(emissioncruise) "Cruise",
									sum(emissiondescend) "Descend", 
									sum(emissionholding) "Holding",
									sum(emissionapproach) "Approach", 
									sum(emissionlanding) "Landing", 
									sum(emissiontaxiin) "TaxiIn" 
								')
                    ->leftJoin('cec_emission', function ($join) {
                        $join->on('cec_emission.fpl_id', '=', 'cec_fpl.id');
                     }) 
                    ->get();
                    // dd($records);
        $pieName = [ 'Start','TaxiOut','GndHolding','TakeOff','Climb','Cruise','Descend','Holding','Approach','Landing','TaxiIn' ];

     	$data_arr = array();
     	 
     	foreach($pieName as $name){
	        foreach($records as $record){
	        	$iname 	= $name;
		        $y    	= $record->$name; 
		        $data_arr[] = array(
		            "name" => $iname,
		            "y"    => floatval($y) 
		        );
	        } 
	    }
		echo json_encode($data_arr); 
        exit; 
    }
    public function getEmChart(Request $request){
    	$type = $request->get('type') ;
    	$req  = $request->get('reqs') ;
    	$data;
    	if($type=='daily'){ // get hourly if type is daily

	    	$data = DB::select("
					with hours as (
					        select to_char(g,'HH24') as hour 
					        from generate_series(date_trunc('day','".$req."'::date),date_trunc('day','".$req."'::date)+'23 hours'::interval,'1 hour'::interval) g ),
					    counts as (
					        select 
					            hours.hour,
					            coalesce(round(SUM (cec_emission.emissionstart),2),0) \"Start\",
								coalesce(round(SUM (cec_emission.emissiontaxiout),2),0) \"TaxiOut\",
								coalesce(round(SUM (cec_emission.emissiongndholding),2),0) \"GndHolding\",
								coalesce(round(SUM (cec_emission.emissiontakeoff),2),0) \"TakeOff\",
								coalesce(round(SUM (cec_emission.emissionclimb),2),0) \"Climb\",
								coalesce(round(SUM (cec_emission.emissioncruise),2),0) \"Cruise\",
								coalesce(round(SUM (cec_emission.emissiondescend),2),0) \"Descend\",
								coalesce(round(SUM (cec_emission.emissionholding),2),0) \"Holding\",
								coalesce(round(SUM (cec_emission.emissionapproach),2),0) \"Approach\",
								coalesce(round(SUM (cec_emission.emissionlanding),2),0) \"Landing\",
								coalesce(round(SUM (cec_emission.emissiontaxiin),2),0) \"TaxiIn\",
								coalesce(round(SUM (cec_emission.emissiontotal),2),0) \"Total\"
					        FROM hours
							left join cec_emission on 
							TO_CHAR(cec_emission.created_at,'HH') = hours.hour
							and cec_emission.created_at::timestamp::date = '".$req."'::date
					        group by hours.hour
					    )
					select
					    hour as \"time\",\"Start\",\"TaxiOut\",\"GndHolding\",\"TakeOff\",\"Climb\",\"Cruise\",
						\"Descend\",\"Holding\",\"Approach\",\"Landing\",\"TaxiIn\",\"Total\"
					from counts
					order by hour
	    		");
	    	}
	    	else if($type =='monthly'){ // get daily if type is monthly
	    	// add 01 to request
	    	$data = DB::select("
		    		with days as (
				        select to_char(d,'DD') as day 
				        FROM generate_series( date_trunc('month', '".$req."-01'::date),(date_trunc('MONTH', '".$req."-01'::date) + INTERVAL '1 MONTH - 1 day')::DATE, INTERVAL '1 day'  ) d ),
				    counts as (
				        select 
				            days.day,
				            coalesce(round(SUM (cec_emission.emissionstart),2),0) \"Start\",
							coalesce(round(SUM (cec_emission.emissiontaxiout),2),0) \"TaxiOut\",
							coalesce(round(SUM (cec_emission.emissiongndholding),2),0) \"GndHolding\",
							coalesce(round(SUM (cec_emission.emissiontakeoff),2),0) \"TakeOff\",
							coalesce(round(SUM (cec_emission.emissionclimb),2),0) \"Climb\",
							coalesce(round(SUM (cec_emission.emissioncruise),2),0) \"Cruise\",
							coalesce(round(SUM (cec_emission.emissiondescend),2),0) \"Descend\",
							coalesce(round(SUM (cec_emission.emissionholding),2),0) \"Holding\",
							coalesce(round(SUM (cec_emission.emissionapproach),2),0) \"Approach\",
							coalesce(round(SUM (cec_emission.emissionlanding),2),0) \"Landing\",
							coalesce(round(SUM (cec_emission.emissiontaxiin),2),0) \"TaxiIn\",
							coalesce(round(SUM (cec_emission.emissiontotal),2),0) \"Total\"
				        FROM days
						left join cec_emission on 
						TO_CHAR(cec_emission.created_at,'DD') = days.day
						and TO_CHAR(cec_emission.created_at,'YYYY-MM') = '".$req."'
				        group by days.day
				    )
				select
				    day as \"time\",\"Start\",\"TaxiOut\",\"GndHolding\",\"TakeOff\",\"Climb\",\"Cruise\",
					\"Descend\",\"Holding\",\"Approach\",\"Landing\",\"TaxiIn\",\"Total\"
				from counts
				order by day
				");
	    	}
	    	else if($type =='annual'){ // get monthly if type is annual
	    	// add 01 to request
	    	$data = DB::select("
		    		with months as (
				        select to_char(m,'MM') as month
				        FROM generate_series( date_trunc('year', '".$req."-12-31'::date),(date_trunc('year', '".$req."-01-01'::date) + INTERVAL '1 year - 1 month')::DATE, INTERVAL '1 month'  ) m ),
				    counts as (
				        select 
				            months.month,
				            coalesce(round(SUM (cec_emission.emissionstart),2),0) \"Start\",
							coalesce(round(SUM (cec_emission.emissiontaxiout),2),0) \"TaxiOut\",
							coalesce(round(SUM (cec_emission.emissiongndholding),2),0) \"GndHolding\",
							coalesce(round(SUM (cec_emission.emissiontakeoff),2),0) \"TakeOff\",
							coalesce(round(SUM (cec_emission.emissionclimb),2),0) \"Climb\",
							coalesce(round(SUM (cec_emission.emissioncruise),2),0) \"Cruise\",
							coalesce(round(SUM (cec_emission.emissiondescend),2),0) \"Descend\",
							coalesce(round(SUM (cec_emission.emissionholding),2),0) \"Holding\",
							coalesce(round(SUM (cec_emission.emissionapproach),2),0) \"Approach\",
							coalesce(round(SUM (cec_emission.emissionlanding),2),0) \"Landing\",
							coalesce(round(SUM (cec_emission.emissiontaxiin),2),0) \"TaxiIn\",
							coalesce(round(SUM (cec_emission.emissiontotal),2),0) \"Total\"
				        FROM months
						left join cec_emission on 
						TO_CHAR(cec_emission.created_at,'MM') = months.month
						and TO_CHAR(cec_emission.created_at,'YYYY') = '".$req."'
				        group by months.month
				    )
				select
				    month as \"time\",\"Start\",\"TaxiOut\",\"GndHolding\",\"TakeOff\",\"Climb\",\"Cruise\",
					\"Descend\",\"Holding\",\"Approach\",\"Landing\",\"TaxiIn\",\"Total\"
				from counts
				order by month;
				");
	    	}
	    $datasBar = [];
       
	    
        foreach($data as $dt){ 
	        $name = $dt->time;
	        $y    = $dt->Total; 
	        array_push($datasBar,array( $name, floatval($y)));
        }  
        $datamBar = []; 
	    $mName = [ null,"Start","TaxiOut","GndHolding","TakeOff","Climb","Cruise","Descend","Holding","Approach","Landing","TaxiIn" ];
	    
	    foreach ($data as $row) {
			array_push($datamBar, [$row->time, floatval($row->Start), floatval($row->TaxiOut), floatval($row->GndHolding), floatval($row->TakeOff), floatval($row->Climb), floatval($row->Cruise), floatval($row->Descend), floatval($row->Holding), floatval($row->Approach), floatval($row->Landing), floatval($row->TaxiIn)]);
		} 
        array_unshift($datamBar,$mName); 
	    $res['datasBar']  = $datasBar;  
	    $res['datamBar'] = $datamBar;  
    	echo json_encode($res);
        exit; 
    }
}