<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Req;
use App\Models\User;
use App\Models\VfrPlanning;
use App\Models\Api\Ats;
use App\Models\Api\AtsTemp;
use App\Models\Airport;
use App\ApiResponse;
use Exception;
use \Illuminate\Support\Facades\Request;
use Auth;
use \Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Log;

class VfrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $originalInput=Request::input();
        $user = Auth::user(); 
        // $data['waypoints']= $this->getWpts();
        $data['airports']= $this->airportList(); 
        return view('pages.vfr.index', $data);
    }
    public function getWpts($depart=null,$destiny=null){
        // $depart = '106.66111111111111, -6.123611111111111';
        // $destiny = '112.786388888889,-7.38083333333333';
        //5 nm = 0.0833333
        $range = 0.0833333 * 8;
        // $wpts = Ats::select(DB::raw('ats.ats_id, ats.ats_ident, ats.seq_424, ats.point, waypoint.wpt_name, waypoint.geom geom, ats.geom a_geom, ats.track_out, ats.dist, ats.maa, ats.bidirect, ats.point2'))
        //             ->leftJoin('waypoint', 'waypoint.wpt_id', '=', 'ats.point')
        //             ->whereRaw("ST_WITHIN ( waypoint.geom, 
        //                                     ST_Buffer ( 
        //                                         ST_GeomFromText( 
        //                                             ST_AsText ( 
        //                                                 ST_MakeLine( ST_MakePoint( $depart ), ST_MakePoint ( $destiny ) ) 
        //                                             )
        //                                         ),$range, 'endcap=flat join=round'
        //                                     )
        //                                 )
        //                         ")
        //             ->whereRaw("ats.type='V'")
        //             // ->whereRaw("(SELECT RIGHT(ats.ctry, 2) ) = 'ID'")
        //             ->orderByRaw("ats.ats_ident, ats.seq_424")
        //             ->get();
        // return $wpts;
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
                    ->whereRaw("ats_temp.type = 'V'")
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
        foreach (Airport::select(DB::raw("arpt.arpt_ident,arpt.icao,arpt.arpt_name,arpt.city_name,ST_X(arpt.geom) as lng, ST_Y(arpt.geom) as lat "))->where('ctry', 'ID')->whereRaw("LENGTH(icao)=4")->orderby('icao')->get() as $arpt) { 
            $results[] = [
                'value' => $arpt->lng.', '.$arpt->lat,
                'label' => $arpt->icao.' - '.$arpt->arpt_name.' - '.$arpt->city_name,
            ];
        } 
        return ApiResponse::success($results);
    }
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Req $request)
    {
         
        $input = $request; 
        dd($input);die;

        $user = Auth::user(); 
        $validator = Validator::make($request->all(), [
            'route' => 'required|string',
            // 'wpt'   => 'required|string',
            'depart_' => 'required|string',
            'destiny_' => 'required|string',
            // 'aircraft' => 'required|string',
            // 'atd' => 'required',
            // 'ata' => 'required',
            // 'pic' => 'required|string',  
        ]);
        $request->wpt_id    = 'WPT'.$request->wpt_name.'1_1';
        $request->desc_name = $request->wpt_name;
        $request->ctry      ='ID';
        $request->type      = '2';
        $request->usage_cd  = '4';
        $request->editor    = $user->id;


        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors());
        }

        try {
            DB::beginTransaction();

            $master = new PostflightReport();
            $master->fill($request->all());

            $master->save();

            if ($request->has('services')) {
                foreach ($request->services as $service) {
                    $service['postflight_report_id'] = $master->id;
                    $model = new PostflightService();

                    $model->fill($service);

                    $model->save();
                }
            }

            if ($request->has('navigations')) {
                foreach ($request->navigations as $navigation) {
                    $navigation['postflight_report_id'] = $master->id;
                    $model = new PostflightNavigation();
                    $model->fill($navigation);

                    $model->save();
                }
            }

            if ($request->has('lightnings')) {
                foreach ($request->lightnings as $lightning) {
                    $lightning['postflight_report_id'] = $master->id;
                    $model = new PostflightLightning();
                    $model->fill($lightning);

                    $model->save();
                }
            }

            DB::commit();

            return ApiResponse::success(true);
        } catch (Exception $e) {
            DB::rollback();
            return ApiResponse::error('query_error', $e->getMessage());
        }
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
    public function getRoute()
    { 
        $depart  = Request::input('departure');
        $destiny = Request::input('destination');
        $data['waypoints'] = $this->getWpts($depart,$destiny);
        // return $data;
        $response = array(
            'status' => 'success',
            'msg' => $data,
        );
      return response()->json($response); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getInfoArpt($icao){
        $url='';
        if($icao != ''){
            $data = Airport::select('arpt_ident')->where('icao',$icao)->first();
            // dd($data['arpt_ident']);
            $url = '/airportinfo/'.$data['arpt_ident'].'@interaktif';
        }
        return redirect($url);
    }
}
