<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api\CecAirport;
use App\Models\Api\CecDepFeature;
use App\Models\Api\CecArrFeature;
use Illuminate\Support\Facades\{Auth, View, Validator};
use Illuminate\Http\Response;

class CecAirportController extends Controller
{
    private $dep_features = [];
    private $arr_features = [];
    private $page;

    public function __construct(){
        $this->page = (object)['title'=>'Airport'];
        $depFet = CecDepFeature::select('id','name')->get()->toArray();
        $this->dep_features = $depFet;
        $arrFet = CecArrFeature::select('id','name')->get()->toArray();
        $this->arr_features = $arrFet;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $data['page'] = $this->page;
         $data['depFeat'] = $this->dep_features;
         $data['arrFeat'] = $this->arr_features;
         
         return View::make('pages.inavcec.airport',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $rules = array (
            'icao' => 'required',
        );
        // var_dump($request->dep_features);
        $validator = Validator::make ( $request->all(), $rules );
        if ($validator->fails ())
        return  response()->json ([
            'error' => $validator->getMessageBag ()->toArray()
        ], 406 );
        else {
            $upData = CecAirport::firstOrNew(['icao' =>  $id]); 
            $upData->icao           = $request->icao;
            $upData->taxiout        = $request->taxiout;
            $upData->gndholding     = $request->gndholding;
            $upData->arrholding     = $request->arrholding;
            $upData->approach       = $request->approach;
            $upData->taxiin         = $request->taxiin;
            // $upData->dep_features   = $request->dep_features;
            // $upData->arr_features   = $request->arr_features;
            $upData->location       = $request->location;
            // $upData->dep_features   = implode(",", $request->dep_features);
            $upData->dep_features   = json_encode($request->dep_features);
            $upData->arr_features   = json_encode($request->arr_features);
            // $data['tags'] = json_encode($request->tags);
            $upData->save();
            return response ()->json ( $upData );
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delData = CecAirport::find($id);
        $delData->delete();
    }
    public function getAirport(Request $request){ 
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
     $totalRecords = CecAirport::select('count(*) as allcount')->count();

     $totalRecordswithFilter = CecAirport::select('count(*) as allcount')->where('icao', 'like', '%' .$searchValue . '%')->count();

     // Fetch records
     $records = CecAirport::orderBy($columnName,$columnSortOrder)
       ->where('cec_airport.icao', 'like', '%' .$searchValue . '%')
       ->select('cec_airport.*')
       ->skip($start)
       ->take($rowperpage)
       ->get();   

     $data_arr = array(); 
     foreach($records as $record){ 
        $icao           = $record->icao;
        $taxiout        = $record->taxiout;
        $gndholding     = $record->gndholding;
        $arrholding     = $record->arrholding;
        $approach       = $record->approach;
        $taxiin         = $record->taxiin;
        $dep_features   = $record->dep_features;
        $arr_features   = $record->arr_features;
        $location       = $record->location;
        $tooli          = '';
        $data_arr[] = array(
            "icao"          => $icao,
            "taxiout"       => $taxiout,
            "gndholding"    => $gndholding,
            "arrholding"    => $arrholding,
            "approach"      => $approach,
            "taxiin"        => $taxiin,
            "dep_features"  => $dep_features,
            "arr_features"  => $arr_features,
            "location"      => $location,
            "tooli"         => $tooli
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
