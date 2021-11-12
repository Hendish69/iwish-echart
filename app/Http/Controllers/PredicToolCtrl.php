<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Req;
use App\Models\User;
use App\Models\Api\Ats;
use App\Models\Api\AtsTemp;
use App\Models\Airport;
use App\Models\Api\CecAirport;
use App\Models\Api\CecAcft as Aircraft;
use App\Models\Api\CecFpl as FPL;
use App\ApiResponse;
use Exception;
use \Illuminate\Support\Facades\Request;
use Auth;
use \Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Log;

class PredicToolCtrl extends Controller
{
    
    private $page;

    public function __construct()
    {
        $this->page = (object)['title'=>'INAVCEC Prediction Tool'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page']  = $this->page;
        // app('App\Http\Controllers\Api\V2\AftnController')->calculateEmission('',['adep'=>'WII']); //use another controller 
        $originalInput=Request::input();
        $user = Auth::user(); 
        // $data['waypoints']= $this->getWpts();
        $data['airports']= $this->airportList();
        $data['aircrafts']= $this->aircraftList(); 
        // dd($data);
        return view('pages.inavcec.tools.predict', $data);
    }
    
    public function calcEmission(Req $params) { 
        $acft = Aircraft::where('icao','=',$params->acft)->first();
        $adep = CecAirport::where('icao','=',$params->adep)->first();
        $ades = CecAirport::where('icao','=',$params->ades)->first();
        $em     = (object)[];
        $atd    = $params->atd;
        
        $rfl = $params->cruise_fl ? $params->cruise_fl : 0 ; 
        $tClimb         = round(($rfl * 100) / $acft->rateclimb,2); // $flt->rfl inputan cruise flight level
        $tTakeOff       = $tClimb < $acft->ttakeoff ? $tClimb : $acft->ttakeoff;
        $tCleanClimb    = $tClimb < $acft->ttakeoff ? 0 : $tClimb - $tTakeOff;
        $atReach        = $this->addMinutesToDate($atd, $tClimb); // ~*
        $tDescend       = round(($rfl * 100) / $acft->ratedescend,2);
        $tCleanDescend  = $tDescend < $ades->approach ? 0 : $tDescend - $ades->approach;
        $minDescend     = -1 * abs($tDescend);

        // skip 
        // $atDescend      = $this->addMinutesToDate($ata, $minDescend); // ~*        
        // $tCruise        = $this->getDuration($atReach, $atDescend); // Route Distance / Speed 
        // end skip
        $tCruise                = round(($params->distance / $params->cruise_spd) * 60,2); //hour to minute x 60 
        // Emissions
        $em->emissionstart      = round($acft->tstartup * $acft->eridle,2);
        $em->emissiontaxiout    = round($adep->taxiout * $acft->ertaxi,2);
        $em->emissiongndholding = round(($adep->gndholding + $acft->tidle) * $acft->eridle,2);
        $em->emissiontakeoff    = round($acft->ttakeoff * $acft->erfull,2);
        $em->emissionclimb      = round($tCleanClimb * $acft->erclimb,2);
        $em->emissioncruise     = round($tCruise * $acft->ercruise,2);
        $em->emissiondescend    = round($tCleanDescend * $acft->erdescend,2);
        $em->emissionholding    = round($ades->arrholding * $acft->erholding,2);
        $em->emissionapproach   = round($ades->approach * $acft->erapch,2);
        $em->emissionlanding    = round($acft->tlanding * $acft->erlanding,2);
        $em->emissiontaxiin     = round($ades->taxiin * $acft->ertaxi,2);

        $emissiontotal          =   floatval($em->emissionstart) + floatval($em->emissiontaxiout) + 
                                    floatval($em->emissiongndholding) + floatval($em->emissiontakeoff) + 
                                    floatval($em->emissionclimb) + floatval($em->emissioncruise) + 
                                    floatval($em->emissiondescend) + floatval($em->emissionholding) + 
                                    floatval($em->emissionapproach) + floatval($em->emissionlanding) + floatval($em->emissiontaxiin);
        $em->emissiontotal = $emissiontotal; 
        echo json_encode($em);
    }
    public function getWpts($depart=null,$destiny=null){
        // $depart = '106.66111111111111, -6.123611111111111';
        // $destiny = '112.786388888889,-7.38083333333333';
        //5 nm = 0.0833333
        $range = 0.0833333 * 8;
        $wpts = AtsTemp::query()->with(['remarks'])->with(['nav1'])->with(['wpt1'])->with(['nav2'])->with(['wpt2'])
                ->select(
                        DB::raw('ats_temp.ats_id, ats_temp.ats_ident, ats_temp.seq_424, ats_temp.point, waypoint.wpt_name, waypoint.geom geom, ats_temp.geom::json a_geom, ats_temp.track_out, ats_temp.dist, ats_temp.maa, ats_temp.bidirect, ats_temp.point2'),
                        DB::raw("(CASE when (substring(point,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point)
                        else (select CONCAT(nav_ident||' ' ||definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point) end) as point_1"),
                        DB::raw("(CASE when (substring(point2,1,3)) ='WPT' THEN (select desc_name from waypoint_temp where wpt_id=point2) else (select CONCAT(nav_ident||' ' ||definition) from navaid_temp a inner join cod_nav_types b on b.id=a.type where nav_id=point2) end) as point_2"))
                    ->join('cod_ats_types','cod_ats_types.id','ats_temp.type')

                    ->rightJoin('waypoint', 'waypoint.wpt_id', '=', 'ats_temp.point')
                    ->whereRaw("ST_WITHIN ( waypoint.geom, 
                                            ST_Buffer ( 
                                                ST_GeomFromText( 
                                                    ST_AsText ( 
                                                        ST_MakeLine( ST_MakePoint( $depart ), ST_MakePoint ( $destiny ) ) 
                                                    )
                                                ),$range, 'endcap=flat join=round'
                                            )
                                        )
                                ")
                    // ->whereRaw('')
                    ->whereRaw("ats_temp.type <> 'V'")
                    // ->whereRaw("(SELECT RIGHT(ats.ctry, 2) ) = 'ID'")
                    ->orderByRaw("ats_temp.seq_424 asc")
                    ->get();
        return $wpts;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function haversineGreatCircleDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
    public function airportList()
    {
        $results = []; 
        // foreach(CCARPT::select(''))
        foreach (Airport::select(DB::raw("arpt.arpt_ident,arpt.icao,arpt.arpt_name,arpt.city_name,ST_X(arpt.geom) as lng, ST_Y(arpt.geom) as lat "))
                ->rightJoin('cec_airport',function($jn){
                    $jn->on('cec_airport.icao','=','arpt.icao');
                })
                ->where('ctry', 'ID')
                ->whereRaw("LENGTH(arpt.icao)=4")
                ->orderby('arpt.icao')->get() 
                as $arpt
        ) { 
            $results[] = [
                'value' => $arpt->icao,
                'label' => $arpt->icao.' - '.$arpt->arpt_name.' - '.$arpt->city_name,
            ];
        } 
        return ApiResponse::success($results);
    }
    public function aircraftList()
    {
        $results = []; 
        
        foreach (Aircraft::select(DB::raw("cec_acft.icao, cec_acft.description"))->get() as $acft
        ) { 
            $results[] = [
                'value' => $acft->icao,
                'label' => $acft->icao. ' ' . $acft->description,
            ];
        } 
        return ApiResponse::success($results);
    }
      
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id=null)
    {
        return 'nice';
    }
    // public function getRoute()
    // { 
    //     $depart  = Request::input('departure');
    //     $destiny = Request::input('destination');
    //     $data['waypoints'] = $this->getWpts($depart,$destiny);
    //     // return $data;
    //     $response = array(
    //         'status' => 'success',
    //         'msg' => $data,
    //     );
    //   return response()->json($response); 
    // }
    public function getRoute()
    { 
        $depart  = Request::input('departure');
        $destiny = Request::input('destination');
        
        $data['routes'] = FPL::selectRaw('split_part(route,\'/\',1) as nroute')
                            ->whereRaw("adep_id = '".$depart."' and ades_id = '".$destiny."'")
                            ->whereRaw('route is not null')
                            ->groupBy('nroute')
                            ->get();
        // dd($data);
        // return $data;
        $response = array(
            'status' => 'success',
            'msg' => $data,
        );
      return response()->json($response); 
    }
 
    private function addMinutesToDate($theDate, $theMinutes) {
       $datetime = date($theDate);
        // Convert datetime to Unix timestamp
        $timestamp = strtotime($datetime);
        //  add time 
        $theMinutes = (int) $theMinutes;
        
        $time_ = $timestamp + ($theMinutes * 60); 
    
        $to_time   =  date("Y-m-d H:i:s", $time_);  
        return $to_time;
    }
    function getDuration($old, $lat){
        $date1 = date_create($old); 
        $date2 = date_create($lat); 
        $difference = date_diff($date1, $date2);     
        $minutes = $difference->days * 24 * 60;
        $minutes += $difference->h * 60;
        $minutes += $difference->i;
        return $minutes;
    }
}
