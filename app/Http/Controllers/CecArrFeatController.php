<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api\CecArrFeature;
use Illuminate\Support\Facades\{Auth, View, Validator};
use Illuminate\Http\Response;

class CecArrFeatController extends Controller
{
     private $page;
    
    public function __construct()
    {
        $this->page = (object)['title'=>'Arrival Features'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page'] = $this->page;
        return View::make('pages.inavcec.arrfeat',$data);
    } 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){ 
        $rules = array (
            // 'reductionmin' => 'required',
            // 'reductionmax' => 'required',
            'name'      => 'required',
            'fcode'     => 'required',
            'reduction'     => 'required',
        );
        $validator = Validator::make ( $request->all(), $rules );
        if ($validator->fails ())
        return  response()->json ([
            'error' => $validator->getMessageBag ()->toArray()
        ], 406 );
        else {
            $svData = new CecArrFeature; 
            
            $svData->name           = $request->name;
            $svData->reduction      = $request->reduction;
            $svData->description    = $request->description;
            $svData->fcode          = $request->fcode;
            $svData->reductionmin   = $request->reductionmin;
            $svData->reductionmax   = $request->reductionmax;
            $svData->effect         = $request->effect;

            $svData->save();
            return response ()->json ( $svData );
        } 
    }
    public function update(Request $request, $id)
    {
        $rules = array (
            // 'reductionmin' => 'required',
            // 'reductionmax' => 'required',
            'name'      => 'required',
            'fcode'     => 'required',
            'reduction' => 'required'
        );
        $validator = Validator::make ( $request->all(), $rules );
        if ($validator->fails ())
        return  response()->json ([
            'error' => $validator->getMessageBag ()->toArray()
        ], 406 );
        else {
            $upData = CecArrFeature::firstOrNew(['id' =>  $id]); 
            
            $upData->name           = $request->name;
            $upData->reduction      = $request->reduction;
            $upData->description    = $request->description;
            $upData->fcode          = $request->fcode;
            $upData->reductionmin   = $request->reductionmin;
            $upData->reductionmax   = $request->reductionmax;
            $upData->effect         = $request->effect;

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
        $delData = CecArrFeature::find($id);
        $delData->delete();
    }
    // AJAX request
    
    public function getArrFeat(Request $request){ 
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
     $totalRecords = CecArrFeature::select('count(*) as allcount')->count();

     $totalRecordswithFilter = CecArrFeature::select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

     // Fetch records
     $records = CecArrFeature::orderBy($columnName,$columnSortOrder)
       ->where('cec_arrfeature.name', 'like', '%' .$searchValue . '%')
       ->select('cec_arrfeature.*')
       ->skip($start)
       ->take($rowperpage)
       ->get();   
     $data_arr = array(); 
     foreach($records as $record){
        $id             = $record->id;
        $name           = $record->name;
        $reduction      = $record->reduction;
        $description    = $record->description;
        $fcode          = $record->fcode; 
        $reductionmin   = $record->reductionmin;
        $reductionmax   = $record->reductionmax; 
        $effect         = $record->effect; 
        $created_at     = $record->created_at->format('d-m-Y H:i:s');
        $updated_at     = $record->updated_at->format('d-m-Y H:i:s');
        $tooli          = '';
        $data_arr[] = array(
            "id"            => $id,
            "name"          => $name,
            "reduction"     => $reduction,
            "description"   => $description,
            "fcode"         => $fcode,
            "reductionmin"  => $reductionmin,
            "reductionmax"  => $reductionmax,
            "effect"        => $effect,
            "created_at"    => $created_at,
            "updated_at"    => $updated_at,
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
