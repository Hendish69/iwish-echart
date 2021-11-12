<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api\CecAcft;
use Illuminate\Support\Facades\{Auth, View, Validator};
use Illuminate\Http\Response;

class CecAcftController extends Controller
{
    private $page;
    
    public function __construct()
    {
        $this->page = (object)['title'=>'Aircraft'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page'] = $this->page;
        return View::make('pages.inavcec.acft',$data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CecAcft $cecacft)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CecAcft $cecacft)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        
        $rules = array (
            'erfull' => 'required'
        );
        $validator = Validator::make ( $request->all(), $rules );
        if ($validator->fails ())
        return  response()->json ([
            'error' => $validator->getMessageBag ()->toArray()
        ], 406 );
        else {
            $updateDta = CecAcft::firstOrNew(['icao' =>  $id]);
            
            $updateDta->eridle      =$request->eridle;
            $updateDta->erfull      =$request->erfull;
            $updateDta->ertaxi      =$request->ertaxi;
            $updateDta->erclimb     =$request->erclimb;
            $updateDta->erdescend   =$request->erdescend;
            $updateDta->erholding   =$request->erholding;
            $updateDta->ercruise    =$request->ercruise;
            $updateDta->description =$request->description;
            $updateDta->erlanding   =$request->erlanding;
            $updateDta->tstartup    =$request->tstartup;
            $updateDta->ttakeoff    =$request->ttakeoff;
            $updateDta->tlanding    =$request->tlanding;
            $updateDta->rateclimb   =$request->rateclimb;
            $updateDta->ratedescend =$request->ratedescend;
            $updateDta->tidle       =$request->tidle;
            $updateDta->erapch      =$request->erapch;

            $updateDta->save();
            return response ()->json ( $updateDta );
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
        $delData = CecAcft::find($id);
        $delData->delete();
    }
     /*
   AJAX request
   */
    public function getAcft(Request $request){ 
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
     $totalRecords = CecAcft::select('count(*) as allcount')->count();

     $totalRecordswithFilter = CecAcft::select('count(*) as allcount')->where('icao', 'like', '%' .$searchValue . '%')->count();

     // Fetch records
     $records = CecAcft::orderBy($columnName,$columnSortOrder)
       ->where('cec_acft.icao', 'like', '%' .$searchValue . '%')
       ->select('cec_acft.*')
       ->skip($start)
       ->take($rowperpage)
       ->get();  
       
     $data_arr = array(); 
     foreach($records as $record){ 
        $icao       = $record->icao;
        $eridle     = $record->eridle;
        $erfull     = $record->erfull;
        $ertaxi     = $record->ertaxi;
        $erclimb    = $record->erclimb;
        $erdescend  = $record->erdescend;
        $erholding  = $record->erholding;
        $ercruise   = $record->ercruise;
        $description    = $record->description;     
        $erlanding      = $record->erlanding;   
        $tstartup       = $record->tstartup;    
        $ttakeoff       = $record->ttakeoff;    
        $tlanding       = $record->tlanding;    
        $rateclimb      = $record->rateclimb;   
        $ratedescend    = $record->ratedescend;     
        $tidle          = $record->tidle;   
        $erapch         = $record->erapch;  

        // $tooli = '<ul class="nk-tb-actions gx-1">
        //         <li class="nk-tb-action">
        //             <button onclick="edit_action(this,\''.$icao .'\')" class="btn btn-trigger btn-icon"  data-toggle="modal" data-placement="top" title="Edit" data-toggle="modal" data-target="#modal_edit">
        //                 <em class="icon ni ni-edit-fill"></em>
        //             </button>
        //         </li>
        //         <li class="nk-tb-action">
        //             <button onclick="delete_action(\''.$icao .'\')" class="btn btn-trigger btn-icon"  data-toggle="modal"  data-placement="top" title="Delete" data-toggle="modal" data-target="#modal_delete">
        //                 <em class="icon ni ni-trash-fill"></em>
        //             </button>
        //         </li> 
        //     </ul>';
        $tooli = '';
        $data_arr[] = array(
            "icao"        => $icao,
            "eridle"      => $eridle,
            "erfull"      => $erfull,
            "ertaxi"      => $ertaxi,
            "erclimb"     => $erclimb,
            "erdescend"   => $erdescend,
            "erholding"   => $erholding,
            "ercruise"    => $ercruise,
            "description" => $description,
            "erlanding"   => $erlanding,
            "tstartup"    => $tstartup,
            "ttakeoff"    => $ttakeoff,
            "tlanding"    => $tlanding,
            "rateclimb"   => $rateclimb,
            "ratedescend" => $ratedescend,
            "tidle"       => $tidle,
            "erapch"      => $erapch,
            "tooli"       => $tooli
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
