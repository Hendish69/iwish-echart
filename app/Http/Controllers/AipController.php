<?php

namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as Req;
use Auth;
use Session;
// use App\Models\Api\CodEaip;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Route;
use App\Models\Api\EaipChartContentTemp as CC_Temp;
use App\Models\Api\EaipChartContent as CC_Curr;
use App\Models\Api\EaipApronTwy;
use App\Models\Api\AirportTaTl as tatl;
use App\Models\Api\EaipArptGate;
use App\Models\Api\EaipPushback;
use App\Models\Api\EaipApronTwyTemp;
use App\Models\Api\EaipArptGateTemp;
use App\Models\Api\EaipPushbackTemp;
use App\Models\Api\RawdataPub as Raw_Pub;
use App\Models\Api\Airport as Arpt;
use App\Models\Api\TempUpdate as TempU;
use App\Models\Api\TempUpdateDetail as TempUD;
use App\Models\Api\Obstacle as Obst;
use App\Models\Api\ObstacleTemp as ObstTemp;
use App\Models\Api\LocIndicator;
use App\Models\Api\LocIndicatorTemp;
use App\Models\Api\Ats;
use App\Models\Api\AtsRemarks;
use App\Models\Api\AtsTemp;
use App\Models\Api\AtsRemarksTemp;
use App\Models\Api\Waypoint;
use App\Models\Api\WaypointTemp;
use App\Models\Api\Navaid;
use App\Models\Api\NavaidTemp;
use App\Models\Api\Ils;
use App\Models\Api\IlsTemp;
use App\Models\Api\IlsMarker;
use App\Models\Api\IlsMarkerTemp;
use App\Models\Api\ArptNav;
use App\Models\Api\ArptNavTemp;
use App\Models\Api\Runway;
use App\Models\Api\RunwayTemp;
use App\Models\Api\RwyPhysical;
use App\Models\Api\RwyPhysicalTemp;
use App\Models\Api\Rwylgt;
use App\Models\Api\RwylgtTemp;
use App\Models\Api\Frequency;
use App\Models\Api\FrequencyTemp;
use App\Models\Api\FreqUsage;
use App\Models\Api\FreqSeg;
use App\Models\Api\FreqValue;
use App\Models\Api\FreqUsageTemp;
use App\Models\Api\FreqSegTemp;
use App\Models\Api\FreqValueTemp;
use App\Models\Api\AirspaceTemp;
use App\Models\Api\Airspace;
use App\Models\Api\AirspaceClass;
use App\Models\Api\AirspaceSegment;
use App\Models\Api\AirspaceClassTemp;
use App\Models\Api\AirspaceSegmentTemp;
use App\Models\Api\Td_aip;





class AipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function airportlist($id)
    {
        $originalInput = Request::input();
        $user = Auth::user();
        $pia =$user->pia_id;
        $sql='api/airports?ctry=ID&deleted=0&sort=arpt_name:asc';
        if (!is_null($pia)){
            $sql.= '&auth='.$pia;
        }
        $data['id']= $id;
        $data['airport']= getDataApi($originalInput, $sql);
        // dd($data);
        // if ($id=='html' || $id=='iac' || $id=='sid' || $id=='star'){
        //         return view('pages.publications.eaip.airporthtml',$data);
        // }else 
        if ($id=='edit'){
            // $data['airport']= getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
            $data['codaip']= getDataApi($originalInput, 'api/cod/list/cod_aip');
            $data['arptypes']= getDataApi($originalInput, 'api/cod/list/cod_arpt_types');
            $data['countries']= getDataApi($originalInput, 'api/cod/list/country');
            $data['pia']= getDataApi($originalInput, 'api/auth?sort=id:asc');
            $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=arpt');
            return view('pages.publications.airport.listairport',$data);

        }else{
            return view('pages.publications.eaip.airporthtml',$data);
        }
    }
    public function listsourcenr(){
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['source']= getDataApi($originalInput, 'api/sourcenr?publish=Y&sort=pub_date:desc');
        // dd($data);
        return view('pages.publications.sourcenr',$data);
    }
    public function listchart($id,$tbl)
    {
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // dd($id,$tbl);
        if ($id=='enr'){
            $data['tbl']=$tbl;
            $data['chart']= getDataApi($originalInput, 'api/proc/chart?chart_type=11&sort=seq:asc');
            $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');
            $data['paper']= getDataApi($originalInput, 'api/cod/list/cod_paper_size');
            return view('pages.publications.procedures.enrchartproperties',$data);
        }else{

            $data['tbl']=$tbl;
            $data['bm']= getDataApi($originalInput, 'api/frame/chart?arpt_ident='.$id);
            $data['chart']= getDataApi($originalInput, 'api/proc/chart?chart_arpt_ident='.$id.'&sort=seq:asc');
            $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');
            $data['airport']= getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
            $data['paper']= getDataApi($originalInput, 'api/cod/list/cod_paper_size');
            return view('pages.publications.procedures.chartproperties',$data);
        }
        
        
        
    }

    public function listchart_prop($id,$arptident)
    {
        $originalInput=Request::input();
        $user = Auth::user();
// dd($id,$arptident);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        if ($arptident=='enr'){
            if ($id=='new'){
                $data['edit']='new';
                $data['chart']= [];
            }else{
                $data['edit']='edit';
                $data['chart']= getDataApi($originalInput, 'api/proc/chart?id='.$id.'&sort=seq:asc');

            }
            $data['paper']= getDataApi($originalInput, 'api/cod/list/cod_paper_size');
            $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');
            return view('pages.publications.procedures.chartframeenr',$data);
        }else{
            if ($id=='new'){
                $data['edit']='new';
                $data['chart']= getDataApi($originalInput, 'api/proc/chart?chart_arpt_ident='.$arptident.'&sort=seq:asc');
            }else{
                $data['edit']='edit';
                $data['chart']= getDataApi($originalInput, 'api/proc/chart?id='.$id.'&sort=seq:asc');
            }
            $data['bm']= getDataApi($originalInput, 'api/frame/chart?arpt_ident='.$arptident);
            $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');
            $data['airport']= getDataApi($originalInput, 'api/airports?arpt_ident='.$arptident);
            $data['proc']= getDataApi($originalInput, 'api/procedures/temp?arpt_ident='.$arptident);
            $data['wptdesc']=DB::table('cod_wpt_desc')->orderby('id','asc')->get();
            $data['elev'] = getDataApi($originalInput, 'api/eaip/contenttemp?arpt_ident='.$arptident.'&category_id=228');
            $data['arptfreq'] = getDataApi($originalInput, 'api/freqarpt/temp/'.$arptident);
            $data['freq'] = getDataApi($originalInput, 'api/chartfreq/temp?arpt_ident='.$arptident);
            $data['rawdata']=getDataApi($originalInput, 'api/pub/rawdata?fieldid='.$arptident);
            $data['navaids'] = getDataApi($originalInput, 'api/navarpt/temp?arpt_ident='.$arptident);
            return view('pages.publications.procedures.chartpropertiesform',$data);

        }
            // dd($data);
       
        
    }

    public function listchart_frame($id,$arptident)
    {
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        if ($id=='new'){
            $data['edit']='new';
            $data['bm']= getDataApi($originalInput, 'api/frame/chart?arpt_ident='.$arptident);
        }else{
            $data['edit']='edit';
            $data['bm']= getDataApi($originalInput, 'api/frame/chart?id='.$id);
        }
       
        $data['chart']= getDataApi($originalInput, 'api/proc/chart?chart_arpt_ident='.$arptident.'&sort=seq:asc');
        $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');
        $data['airport']= getDataApi($originalInput, 'api/airports?arpt_ident='.$arptident);
        $data['proc']= getDataApi($originalInput, 'api/procedures/temp?arpt_ident='.$arptident);
        // if ($tbl=='pro'){
        //     $data['wptdesc']=DB::table('cod_wpt_desc')->orderby('id','asc')->get();
        //     $data['elev'] = getDataApi($originalInput, 'api/eaip/contenttemp?arpt_ident='.$id.'&category_id=228');
        //     $data['arptfreq'] = getDataApi($originalInput, 'api/freqarpt/temp/'.$id);
        //     $data['freq'] = getDataApi($originalInput, 'api/chartfreq/temp?arpt_ident='.$id);
        //     $data['rawdata']=getDataApi($originalInput, 'api/pub/rawdata?fieldid='.$id);
        //     $data['navaids'] = getDataApi($originalInput, 'api/navarpt/temp?arpt_ident='.$id);
        //     // dd($data);
        //     return view('pages.publications.procedures.chartproperties',$data);
        // }else{
            $data['paper']= getDataApi($originalInput, 'api/cod/list/cod_paper_size');
          
            return view('pages.publications.procedures.chartframe',$data);
        // }
        
    }

    public function aoc($id,$tbl)
    {
        $originalInput=Request::input();
        $user = Auth::user();
// dd($tbl);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['tbl']=$tbl;
        $data['cod']= getDataApi($originalInput, 'api/cod/list/cod_chart_types');
        $data['airport']= getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $data['obst']= getDataApi($originalInput, 'api/eaip/obstacletemp?arpt_ident='.$id);
        if ($tbl=='adc'){
            $data['adc']= getDataApi($originalInput, 'api/airport/list/adc?arpt_ident='.$id.'&sort=layer:asc');
            $data['ps']= getDataApi($originalInput, 'api/arpt/parkingstand?arpt_ident_gate='.$id);
                return view('pages.publications.procedures.adc',$data);
        }else{

          
                return view('pages.publications.procedures.aoc',$data);
        }
        
    }
    
    public function listholding($info)
    {
        // dd($id);
        $tt=explode('@',$info);
        $id=$tt[0];
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // dd(count($tt));
        $data['parent']='';
        $data['parentid']='';
        if (count($tt)>2){
            $data['parent']='listtranssegment/'.$tt[2];
            $data['parentid']=$tt[3];
        }
            $data['holdingtemp']= getDataApi($originalInput, 'api/holding/list/temp?hld_type='.$id);
            $data['holding']= getDataApi($originalInput, 'api/holding/list?hld_type='.$id);
            
        
        $data['arptident']=$id;
        $data['edit']=$tt[1];
        return view('pages.publications.procedures.holding',$data);
        
    }
    public function listmsa()
    {
        // dd($id);
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['msa']= getDataApi($originalInput, 'api/msa/list?');
       
        
        return view('pages.publications.procedures.msa',$data);
        
    }
    public function gencontent($id)
    {
        // dd($id);
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['airport']= getDataApi($originalInput, 'api/airports?ctry=ID&deleted=0&sort=arpt_name:asc');
        
        if ($id=='html'){
            return view('pages.publications.eaip.airporthtml',$data);
        }if ($id=='edit'){
                return view('pages.publications.airport.listairport',$data);
        }
    }

    public function editairport($id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // $data['airport']= getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $arpt= getDataApi($originalInput, 'api/airports?arpt_ident='.$id);

        // $hi=GeoHi($arpt[0]->geom->coordinates[1], $arpt[0]->geom->coordinates[0]);
        // $hasil=round($hi).'m/'.round(($hi * 3.28084)).'ft';
        // $mergeData[] = (object) ['undulaton' => $hasil];
        // $aaaa = (object) array_merge((array) $arpt, (array)$mergeData);

        $data['airport']=$arpt;
        $data['content'] = getDataApi($originalInput, 'api/airport/content/eaip_chart_content_temp?arpt_ident='.$id);
        // dd($data['content']);
        // $hi= $geoid->undulation();
        // echo $hi;
        $data['codaip']= getDataApi($originalInput, 'api/cod/list/cod_aip');
        $data['arptypes']= getDataApi($originalInput, 'api/cod/list/cod_arpt_types');
        $data['countries']= getDataApi($originalInput, 'api/cod/list/country');
        $data['pia']= getDataApi($originalInput, 'api/auth?sort=id:asc');
        $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=arpt&fieldid='.$id);
       
        // dd($data);
        return view('pages.publications.airport.ad21',$data);
    }
    // heru
    public function aipedit($page, $id=null){ 
        $pages =array('28', '210', '212','213','214','217', '218', '219','220','221','222','223','224');
        $originalInput=Request::input(); 
        if ( !in_array($page, $pages) ) { 
            $user = Auth::user();
            // dd($id);
            if ($user->isAdmin()) {
                // return view('pages.admin.home');
            }
            $data['content'] = getDataApi($originalInput, 'api/airport/content/eaip_chart_content_temp?arpt_ident='.$id);
            $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
            return view('pages.publications.airport.ad'.$page,$data);
        }else{
            if ($page=='212' || $page=='213' || $page=='214'){

                return redirect('/edit'.$page.'/'.$page.'/'.$id);
            }else{
                return redirect('/edit'.$page.'/'.$id);
            }
            
        }
        
    }
    public function updatearpt(Req $request,$id){
        $request= $request->except('_token');
        // $request=Request::input(); 
        $user = Auth::user();
        $next = false;
        $message = '';
        $locind =  LocIndicatorTemp::where('loc_arptident','=',$id)
        ->where('tbl','=','ARPT')
        ->first();
        if ($request['arpta'] !== null && $request['arptl'] !== null ){
            $tatl =  tatl::where('arpt_ident','=',$id)
            ->first();
            if ($tatl){
                $tatl->tl=$request['arptl'];
                $tatl->ta=$request['arpta'];
                $tatl->language='ENGLISH';
                $tatl->editor=$user->id;
                // dd('exist-> exist = '. $exist);
                $tatl->save(); 
            }else{
                $last = tatl::latest('id')->first();
                // $request->id = $last->id + 1;
                // $request->merge([
                //     'id' => $last->id + 1
                // ]);
                $arptatl = new tatl;                         
                $arptatl->id =$last->id + 1 ;
                $arptatl->arpt_ident = $id ;
                $arptatl->ta=$request['arpta'];
                $arptatl->tl=$request['arptl'];
                $arptatl->language='ENGLISH';
                $arptatl->editor = $user->id;
                // dd('not exist-> dat = '. $dat);
                $arptatl->save(); 
                // dd($request,$locind,$tatl);
            }
            
        }
        $toGen24=false;
        if ($locind->indicator==$request['ad229'] || $locind->name==$request['ad231'] || $locind->city==$request['ad232'] ){
            $toGen24=true;
            if($locind){
                $locind->indicator=$request['ad229'];
                $locind->name=$request['ad231'];
                $locind->city=$request['ad232'];
                $locind->status ='R';
                $locind->editor=$user->id;
                // dd('exist-> exist = '. $exist);
                $locind->save(); 
            }else{
                $datloc = new LocIndicatorTemp;                         
                $datloc->tbl   = 'ARPT';
                $datloc->loc_arptident    = $id ;
                $datloc->indicator=$request['ad229'];
                $datloc->name=$request['ad231'];
                $datloc->city=$request['ad232'];
                $datloc->status        = 'N';
                $datloc->editor        = $user->id;
                // dd('not exist-> dat = '. $dat);
                $datloc->save(); 
            }

            $rawdata['tablename']='GEN';
            $rawdata['fieldname']='sub_id';
            $rawdata['fieldid']='GEN 2.4';
            $rawdata['status_raw']=50;
            $rawdata['ori_change_pic']= $user->id;
            saveDataRaw($rawdata);
        }
       
        foreach ($request as $rk => $rval ){  
            $value=$rval; 
            if ($rk !== 'arpta' && $rk !== 'arptl'){
                // dd($rk);

                if($value=='NIL') $value='';   
                    $cat_id = substr($rk, 2);
                    if ( $cat_id > 3330){   
                        // $arpt_dat= Arpt::where('arpt_ident','=',$id)
                        // ->first();
                        // if ($arpt_dat !==null){
                        //     $arpt_dat->mag_var=$request->ad3331;
                        //     $arpt_dat->type=$request->ad3332;
                        //     $arpt_dat->pia=$request->ad3333;
                        //     $arpt_dat->save();
                        // }
                    }else{
                       

                        $exist =  CC_Temp::where('category_id','=',$cat_id)
                                        ->where('arpt_ident','=',$id)
                                        ->first();
            
                        if($exist){
                            if( preg_replace("/\s+/", "",$exist->content) != preg_replace("/\s+/", "",$value) ){
                                $exist->content= $value; 
                                $exist->status ='R';
                                $exist->editor=$user->id;
                                // dd('exist-> exist = '. $exist);
                                $exist->save(); 
                                // update rawdata_pub
                                if(!is_null($exist)){
                                    $rawdata['tablename']='arpt';
                                    $rawdata['fieldname']='arpt_ident';
                                    $rawdata['fieldid']=$id;
                                    $rawdata['status_raw']=0;
                                    $rawdata['ori_change_pic']= $user->id;
                                    saveDataRaw($rawdata);
    
                                    
                                } 
                            }
                            $status=true;
                        }else{
                            $dat = new CC_Temp;                         
                            $dat->category_id   = $cat_id;
                            $dat->arpt_ident    = $id ;
                            $dat->content       = $value; 
                            $dat->status        = 'N';
                            $dat->editor        = $user->id;
                            // dd('not exist-> dat = '. $dat);
                            $dat->save(); 
                            // update rawdata_pub
                            if($dat){ 
                                $rawdata['tablename']='arpt';
                                $rawdata['fieldname']='arpt_ident';
                                $rawdata['fieldid']=$id;
                                $rawdata['status_raw']=0;
                                $rawdata['ori_change_pic']= $user->id;
                                saveDataRaw($rawdata);
    
                            }
                            $status=true;
                        }
                       
                        
                        
                        
                    }
            }
        } 
        if ($status) { 
            $message = 'Your Request data has been saved!!';
            Session::flash('status', 'success');
        }
        
        return redirect('editairport/'.$id);
    }
    
    public function updatedata(Req $request, $page, $id){
        $request= $request->except('_token');
        $user = Auth::user();
        $message = '';
        $status = false;
        $dat=null;
       
            if($page=='22') {
                $request['ad2'] = $request['ad20'] .' '. $request['ad21'];
                unset ($request['ad20']);
                unset ($request['ad21']);
            }
            foreach ($request as $rk => $rval ){  
                $value=$rval; 
                if($value=='NIL') $value='';   
                $cat_id = substr($rk, 2); 
                if ($cat_id==2){
                    $adcorddbl =  CC_Temp::where('category_id','=',$cat_id)
                    ->where('arpt_ident','=',$id)
                    ->get();
                    // dd($adcorddbl);
                        if (count($adcorddbl) > 1){
                            foreach ($adcorddbl as $key => $val) {
                                $deldbl =  CC_Temp::where('id','=',$val->id)
                                ->first();
                                $deldbl->delete();
                            }
                        }
                    }
                    $exist =  CC_Temp::where('category_id','=',$cat_id)
                                    ->where('arpt_ident','=',$id)
                                    ->first();
                    // dd($exist);
                    if($exist){
                        if( preg_replace("/\s+/", "",$exist->content) != preg_replace("/\s+/", "",$value) ){
                            $exist->content= $value; 
                            $exist->status ='R';
                            $exist->editor=$user->id;
                            // dd('exist-> exist = '. $exist);
                            $exist->save(); 
                            // update rawdata_pub
                            if(!is_null($exist)){ 
                                $rawdata['tablename']='arpt';
                                $rawdata['fieldname']='arpt_ident';
                                $rawdata['fieldid']=$id;
                                $rawdata['status_raw']=0;
                                $rawdata['ori_change_pic']= $user->id;
                                saveDataRaw($rawdata);

                                // $raw_dat = Raw_Pub::where('tablename', 'arpt')
                                //                     ->where('fieldname', 'arpt_ident')
                                //                     ->where('fieldid', $id)
                                //                     ->where('status_raw','<=',70)
                                //                     // ->where('status_raw','<', 100)
                                //                     ->first();
                                // if ($raw_dat === null) {
                                //     $raw_dat = new Raw_Pub;
                                //     $raw_dat->tablename = 'arpt';
                                //     $raw_dat->fieldname = 'arpt_ident';
                                //     $raw_dat->fieldid = $id;
                                //     $raw_dat->status_raw = 0;
                                // } 
                                // $raw_dat->ori_change_pic = $user->id;
                                // $raw_dat->save(); 
                            } 
                        }
                        $status=true;
                    }else{
                        $dat = new CC_Temp;                         
                        $dat->category_id   = $cat_id;
                        $dat->arpt_ident    = $id ;
                        $dat->content       = $value; 
                        $dat->status        = 'N';
                        $dat->editor        = $user->id;
                        // dd('not exist-> dat = '. $dat);
                        $dat->save(); 
                        // update rawdata_pub
                        if($dat){ 
                            $rawdata['tablename']='arpt';
                            $rawdata['fieldname']='arpt_ident';
                            $rawdata['fieldid']=$id;
                            $rawdata['status_raw']=0;
                            $rawdata['ori_change_pic']= $user->id;
                            saveDataRaw($rawdata);

                            // $raw_dat = Raw_Pub::where('tablename', 'arpt')
                            //                         ->where('fieldname', 'arpt_ident')
                            //                         ->where('fieldid', $id)
                            //                         ->where('status_raw','<=',70)
                            //                         // ->where('status_raw','<', 100)
                            //                         ->first();
                            // if ($raw_dat === null) {
                            //     $raw_dat = new Raw_Pub;
                            //     $raw_dat->tablename = 'arpt';
                            //     $raw_dat->fieldname = 'arpt_ident';
                            //     $raw_dat->fieldid = $id;
                            //     $raw_dat->status_raw = 0;
                            // }
                            // $raw_dat->ori_change_pic = $user->id;
                            // $raw_dat->save();
                        }
                        $status=true;
                    } 
            } 
            if ($status) { 
                $message = 'Your Request data has been saved!!';
                Session::flash('status', 'success');
            }
            return redirect('aipedit/'.$page.'/'.$id)->with('message',$message);
    
    }

    public function edit28($id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
       
        $data['content'] = getDataApi($originalInput,'api/airport/content/eaip_chart_content_temp?arpt_ident='.$id);
        $data['airport'] = getDataApi($originalInput,'api/airports?arpt_ident='.$id);
        $data['aprontwy'] = getDataApi($originalInput,'api/arpt/aprontwy?arpt_ident=' .$id);
        $data['parkingstand'] = getDataApi($originalInput,'api/arpt/parkingstand?arpt_ident_gate=' .$id);
        $data['pushback'] = getDataApi($originalInput,'api/arpt/pushback?arpt_ident_pushback=' .$id);
        $data['surface'] = getDataApi($originalInput,'api/cod/list/cod_rwy_surface');
        $data['parkingstandtemp'] = getDataApi($originalInput,'api/arpt/temp/parkingstand?arpt_ident_gate=' .$id);
        $data['aprontwytemp'] = getDataApi($originalInput,'api/arpt/temp/aprontwy?arpt_ident=' .$id);
        $data['pushbacktemp'] = getDataApi($originalInput,'api/arpt/temp/pushback?arpt_ident_pushback=' .$id);
        $data['chart'] = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&deleted=0&arpt_pdf_type=CHART&sort=seq:asc');
        // dd($data);
        return view('pages.publications.airport.ad28',$data);
    }
  
    public function edit210($id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // dd($user);
        $data['obstacles'] = getDataApi($originalInput, 'api/eaip/obstacletemp?arpt_ident='.$id. '&deleted=0&sort=position:asc');
        $data['obstaclescurrent'] = getDataApi($originalInput, 'api/eaip/obstacle?arpt_ident='.$id. '&deleted=0&sort=position:asc');
        $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $data['cod'] = getDataApi($originalInput, 'api/cod/list/cod_obs_type');
        $data['chart'] = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&deleted=0&arpt_pdf_type=CHART&sort=seq:asc');


        
        // dd($data);
      
        
        return view('pages.publications.airport.ad210',$data);
    }

    public function edit212($page,$id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id,$page);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['adelev'] = getDataApi($originalInput, 'api/eaip/contenttemp?arpt_ident='.$id.'&category_id=228');
        $data['surface'] = getDataApi($originalInput, 'api/cod/list/cod_rwy_surface');
        $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $data['chart'] = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&deleted=0&arpt_pdf_type=CHART&sort=seq:asc');
        switch ($page) {
            case '212':
                $data['judul'] =$data['airport'][0]->icao.' AD 2.12 RUNWAY PHYSICAL CHARACTERISTICS';
                break;
            case '213':
                $data['judul'] =$data['airport'][0]->icao.' AD 2.13 DECLARED DISTANCES';
                break;
            case '214':
                $data['judul'] =$data['airport'][0]->icao.' AD 2.14 APPROACH AND RUNWAY LIGHTING';
                break;
            default:
                # code...
                break;
        }
        if ($page=='214'){
            return view('pages.publications.airport.ad214',$data);
        }else{
            return view('pages.publications.airport.ad212',$data);
        }
        
       
    }
    public function edit217($id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['airspace'] = getDataApi($originalInput, 'api/airspace/temp/list?arpt_ident='.$id);
        // dd($data['airspace'],$id);
        $data['cod'] = getDataApi($originalInput,'api/eaip/type?id=8');
        // $data['content'] = getDataApi($originalInput, 'api/airport/content/eaip_chart_content_temp?arpt_ident='.$id);
        $arpt = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $data['titel']=$arpt[0]->icao.' AD 2.17 ATS AIRSPACE';
        $data['parent']=$arpt[0]->arpt_ident;
        $data['subid']=$arpt[0]->arpt_ident;
        $data['onrequest']= getDataApi($originalInput, 'api/pub/rawdata/onprocess?tablename=arpt&fieldid='.$id);
        // return view('pages.publications.airport.ad217',$data);
        return view('pages.publications.airspace.airspace',$data);
    }
    public function show217($page,$id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['content'] = getDataApi($originalInput, 'api/airport/content/eaip_chart_content_temp?arpt_ident='.$id);
        $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        return view('pages.publications.airport.ad217',$data);
        // return view('pages.publications.airspace.airspace',$data);
    }
    public function edit218($id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['freq'] = getDataApi($originalInput, 'api/freqarpt/temp/'.$id);
        $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $data['chart'] = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&deleted=0&arpt_pdf_type=CHART&sort=seq:asc');
        
        return view('pages.publications.airport.ad218',$data);
    }
    public function edit219($id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['navaids'] = getDataApi($originalInput, 'api/navarpt/temp?arpt_ident='.$id);
        $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $data['channel'] = getDataApi($originalInput, 'api/nav/channel');
        
        return view('pages.publications.airport.ad219',$data);
    }
    public function edit220($id)
    {
        return redirect('gen/99/'.$id.'/AD 2.20 LOCAL TRAFFIC REGULATIONS');
    }
    public function edit221($id)
    {
        return redirect('gen/108/'.$id.'/AD 2.21 NOISE ABATEMENT PROCEDURES');
    }
    public function edit222($id)
    {
        return redirect('gen/109/'.$id.'/AD 2.22 FLIGHT PROCEDURES');
    }
    public function edit223($id)
    {
        return redirect('gen/110/'.$id.'/AD 2.23 ADDITIONAL INFORMATION');
    }

    public function edit224($id)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['chart'] = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&deleted=0&arpt_pdf_type=CHART&sort=seq:asc');
        $data['airport'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $data['codchart'] = getDataApi($originalInput, 'api/cod/chart');
        //  dd($data);
        return view('pages.publications.airport.ad224',$data);
    }
    function airspace($info){
        $originalInput=Request::input();
        $user = Auth::user();
        $infox=explode('@',$info);
        $id=$infox[0];
        $data['parent']=$infox[2];
        if ($infox[1] ==='edit'){
            $data['airspacetemp'] = getDataApi($originalInput,'api/airspace/temp/list?ats_airspace_id='.$id);
            $data['airspace'] = getDataApi($originalInput,'api/airspace/list?ats_airspace_id='.$id);

        }else if ($infox[1] ==='newdata'){
            $data['airspacetemp'] = [];
            $data['airspace'] =[];
        }
        $data['id'] =$infox[1];
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        $data['cod'] = getDataApi($originalInput,'api/eaip/type?id=8');
        $data['shap'] = getDataApi($originalInput,'/api/cod/list/cod_ats_shap');
        
        if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.airspace.airspaceform',$data);
        }else{
            return abort('403');
        }   
    }
    function suas($info){
        $originalInput=Request::input();
        $user = Auth::user();
        $infox=explode('@',$info,2);
        $id=$infox[0];
        // dd($infox[1]);
        if ($infox[1] ==='edit'){
            $data['suastemp'] = getDataApi($originalInput,'api/suas/temp/list?suas_id='.$id);
            $data['suas'] = getDataApi($originalInput,'api/suas/list?suas_id='.$id);

        }else if ($infox[1] ==='newdata'){
            $data['suastemp'] = [];
            $data['suas'] = [];
        }
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        $data['cod'] = getDataApi($originalInput,'/api/cod/list/cod_suas_types');
        $data['status'] = $infox[1];
        $data['shap'] = getDataApi($originalInput,'/api/cod/list/cod_ats_shap');
        // dd($data);
        if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.airspace.suasform',$data);
        }else{
            return abort('403');
        }   
    }
    public function navaid($info)
    {
        $infox= explode('@',$info,5);
        $id=$infox[0];
        $originalInput=Request::input();
        $user = Auth::user();
        $data['parent']=$infox[2];
        $data['parentid']=$infox[3];
        $data['atsstatus']=$infox[4];
        if ($id=='new'){
            $data['navaidstemp'] =[];
            $data['navaids'] = [];
        }else{
            $data['navaidstemp'] = getDataApi($originalInput,'/api/navaid/temp?nav_id='.$id);
            $data['navaids'] = getDataApi($originalInput,'/api/navaid?nav_id='.$id);
        }
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        // $data['ils'] = getDataApi($originalInput,'/api/ils/list?ils_id=ID');
        $data['channel'] = getDataApi($originalInput,'/api/nav/channel');
        $data['navtypes'] = getDataApi($originalInput,'/api/cod/list/cod_nav_types');
        // dd($data);
        if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.navaidwaypoint.navaidedit',$data);
        }else{
            return abort('403');
        }   
    }
    public function ils($info)
    {
        $infox= explode('@',$info,4);
        $id=$infox[0];
        $originalInput=Request::input();
        $user = Auth::user();
        $data['parent']=$infox[2];
        $data['parentid']=$infox[3];
        $data['ident'] = $infox[0];
        $data['id'] = $infox[1];
        if ($infox[1]=='new'){
            $data['ilstemp'] =[];
            $data['ils'] = [];
        }else{
            $data['ilstemp'] = getDataApi($originalInput,'/api/ils/temp?ils_id='.$id);
            $data['ils'] = getDataApi($originalInput,'/api/ils?ils_id='.$id);
        }
        $data['arpt'] = getDataApi($originalInput,'/api/airports?arpt_ident='.$infox[3]);
        // $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
       
        $data['channel'] = getDataApi($originalInput,'/api/nav/channel');
        // dd($data);
        if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.navaidwaypoint.ilsedit',$data);
        }else{
            return abort('403');
        }   
    }
    public function waypoint($info)
    {
        // dd($info);
        $infox= explode('@',$info,5);
        $id=$infox[0];
        $originalInput=Request::input();
        $user = Auth::user();
        $data['parent']=$infox[2];
        $data['parentid']=$infox[3];
        $data['atsstatus']=$infox[4];
        if ($id=='new'){
            $data['waypointstemp'] =[];
            $data['status'] ='N';
            $data['waypoints'] = [];
        }else{
            $data['status'] ='R';
            $data['waypointstemp'] = getDataApi($originalInput,'/api/waypoint/temp/list?wpt_id='.$id);
            $data['waypoints'] = getDataApi($originalInput,'/api/waypoint/list?wpt_id='.$id);
        }

        // $originalInput=Request::input();
        // $user = Auth::user();
        
        // $data['waypoints'] = getDataApi($originalInput,'/api/waypoint/list?wpt_id='.$id);
        $data['countries'] = getDataApi($originalInput,'api/cod/list/country');
        $data['wptypes'] = getDataApi($originalInput,'/api/cod/list/cod_wpt_types');
        $data['usage'] = getDataApi($originalInput,'/api/cod/list/cod_wpt_usage');
        // dd($data);
        if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.publications.navaidwaypoint.waypointedit',$data);
        }else{
            return abort('403');
        }   
    }
    public function updatealldata($id){
        $originalInput=Request::input();
        $user = Auth::user();
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $idd=explode('@',$id);
        // dd($idd);
        if ($idd[1]=='arpt'){
            $this->updateairporttemp($idd[0]);
        }else{
            $this->UpdateGenEnrTem($idd[0]);
        }
        return redirect('/DataRequest');
    }
    function UpdateGenEnrTemGen24(){
        $originalInput=Request::input();
    
        $loctemp = getDataApi($originalInput, 'api/gen/locindicator/temp?status=R&or=status:N');
        foreach ($loctemp as $key => $value) {
            unset($cur);
            $cur=LocIndicator::where('loc_id','=',$value->loc_id)->first();
            if ($cur==null){
                $cur = new LocIndicator;
                $cur->loc_id =$value->loc_id;
                $cur->indicator = $value->indicator;
                $cur->city = $value->city;
                $cur->name = $value->name;
                $cur->ctry = $value->ctry;
                $cur->tbl = $value->tbl;
                $cur->loc_arptident = $value->loc_arptident;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $cur->status ='U';
                $cur->save();
            }

            $arpt=Arpt::where('arpt_ident','=',$value->loc_arptident)->first();
            if ($arpt==null){
                $last = Arpt::latest('id')->first();
                $cur = new Arpt;
                $cur->id =$last->id + 1;
                $cur->arpt_ident = $value->loc_arptident;
                $cur->icao = $value->indicator;
                $cur->city_name = $value->city;
                $cur->arpt_name = $value->name;
                $cur->ctry = $value->ctry;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $arpt->icao =$value->indicator;
                $arpt->arpt_name =$value->name;
                $arpt->city_name =$value->city;
                $arpt->update();
            }

        }
        
    }
    function UpdateGenEnrTem($id){
        //ats
        // dd($id);
        $originalInput=Request::input();
        switch ($id) {
            case 'GEN 2.4':
                $raw=Raw_Pub::where('fieldid','=',$id)->where('status_raw','<>','100')->first();
                if ($raw){
                    // dd($raw);
                    $raw->delete();
                }
                $this->UpdateGenEnrTemGen24();
                break;
            case 'ENR 3.1':
            case 'ENR 3.2':
            case 'ENR 3.3':
            case 'ENR 3.4':
                $raw=Raw_Pub::where('fieldid','=',$id)->where('status_raw','<>','100')->first();
                if ($raw){
                    // dd($raw);
                    $raw->delete();
                }
                $this->UpdateGenEnrAts();
                break;
            
            case 'ENR 2.1':
                $raw=Raw_Pub::where('fieldid','=',$id)->where('status_raw','<>','100')->first();
                if ($raw){
                    // dd($raw);
                    $raw->delete();
                }
               
                $this->Updateenr21();
                break;
            }
            return redirect('/DataRequest');
        }
    function Updateenr21(){   
        $originalInput=Request::input();
        $asp= getDataApi($originalInput, '/api/airspace/temp/list?status=R&or=status:N');
        // dd($asp);
        if ($asp !== null){
            foreach ($asp as $key => $value) {
                if ($value->airspace_type== 'ATZ' || $value->airspace_type== 'CTR' || $value->airspace_type== 'AFIZ'){
                    /// ini di update oleh airport
                }else{
                    // dd($value);
                    $this->updateairspace($value);
                    
                }
                   
            }

        }

    }

    function updateairspace($data){
        // $data=$asp[0];
        $asp=Airspace::where('ats_airspace_id','=',$data->ats_airspace_id)->first();
        // dd($data);
        $geom=null;
        if ($data->geom !== null){
            foreach ($data->geom->coordinates[0] as $key => $value) {
                if ($geom==null){
                    $geom=strval($value[0]).' '.strval($value[1]);
                }else{
                    $geom =$geom.','.strval($value[0]).' '.strval($value[1]);
                }
            }
            
            // dd($geom);
            $geom = 'POLYGON(('.$geom.'))';
        }
       
        // strval($value[0])
        if ($asp === null) {
            $asp = new Airspace;
            $asp->id =$data->id;
            $asp->ats_airspace_id = $data->ats_airspace_id;
            $asp->airspace_name = $data->airspace_name;
            $asp->airspace_type = $data->airspace_type;
            $asp->airspace_code = $data->airspace_code;
            $asp->airspace_rnp = $data->airspace_rnp;
            $asp->rvsm = $data->rvsm;
            $asp->ctry = 'ID';
            $asp->icao_acc = $data->icao_acc;
            $asp->icao_reg = $data->icao_reg;
            $asp->rvsm_upper = $data->rvsm_upper;
            $asp->rvsm_lower = $data->rvsm_lower;
            $asp->ats_unit = $data->ats_unit;
            $asp->editor = $data->editor;
            $asp->arpt_ident = $data->arpt_ident;

            $asp->geom = $geom;
           
           
            $asp->status = $data->status;
            $asp->save();
        }else{
            $asp->airspace_name = $data->airspace_name;
            $asp->airspace_type = $data->airspace_type;
            $asp->airspace_code = $data->airspace_code;
            $asp->airspace_rnp = $data->airspace_rnp;
            $asp->rvsm = $data->rvsm;
            $asp->icao_acc = $data->icao_acc;
            $asp->icao_reg = $data->icao_reg;
            $asp->rvsm_upper = $data->rvsm_upper;
            $asp->rvsm_lower = $data->rvsm_lower;
            $asp->ats_unit = $data->ats_unit;
            $asp->editor = $data->editor;
            $asp->arpt_ident = $data->arpt_ident;
            $asp->geom = $geom;
            $asp->status = $data->status;
            $asp->update();
        }

        $aspt = AirspaceTemp::find($data->id);
        $aspt->status='U';
        $aspt->update();
        if ($data->boundary !==null){
            // $bdry=AirspaceSegment::where('asp_id','=',$data->ats_airspace_id)->first();
            // if ($bdry !== null){
            //     $bdry->delete();
            // }
            
            foreach ($data->boundary as $key => $value) {
                $bdry=AirspaceSegment::where('id','=',$value->id)->first();
                // dd($value,$bdry);
                if ($bdry === null) {
                    $bdry = new AirspaceSegment;
                    $bdry->id =$value->id;
                    $bdry->asp_seg_id = $value->asp_seg_id;
                    $bdry->asp_id = $value->asp_id;
                    $bdry->air_seq = $value->air_seq;
                    $bdry->point1_lat = $value->point1_lat;
                    $bdry->point1_long = $value->point1_long;
                    $bdry->shap = $value->shap;
                    $bdry->nav_id = $value->nav_id;
                    $bdry->arpt_ident = $value->arpt_ident;
                    $bdry->rwy_id = $value->rwy_id;
                    $bdry->arc_dist = $value->arc_dist;
                    $bdry->arc_lat = $value->arc_lat;
                    $bdry->arc_long = $value->arc_long;
                    $bdry->status = $value->status;
                    $bdry->remarks = $value->remarks;
                    $bdry->save();
                }else{
                    $bdry->air_seq = $value->air_seq;
                    $bdry->point1_lat = $value->point1_lat;
                    $bdry->point1_long = $value->point1_long;
                    $bdry->shap = $value->shap;
                    $bdry->nav_id = $value->nav_id;
                    $bdry->arpt_ident = $value->arpt_ident;
                    $bdry->rwy_id = $value->rwy_id;
                    $bdry->arc_dist = $value->arc_dist;
                    $bdry->arc_lat = $value->arc_lat;
                    $bdry->arc_long = $value->arc_long;
                    $bdry->status = $value->status;
                    $bdry->remarks = $value->remarks;
                    $bdry->update();
                }
               
    
                $atst = AirspaceSegmentTemp::find($value->id);
                $atst->status='U';
                $atst->update();
            }

        }
        if ($data->class !== null){
            // $cls=AirspaceClass::where('asp_id','=',$data->ats_airspace_id)->where('asp_class','=',$data->asp_class)->first();
            
            foreach ($data->class as $key => $value) {
                $cls=AirspaceClass::where('id','=',$value->id)->first();
                if ($cls === null) {
                    $cls = new AirspaceClass;
                    $cls->id =$value->id;
                    $cls->asp_id = $value->asp_id;
                    $cls->asp_class = $value->asp_class;
                    $cls->asp_sector = $value->asp_sector;
                    $cls->upper = $value->upper;
                    $cls->lower = $value->lower;
                    $cls->editor = $value->editor;
                    $cls->status = $value->status;
                    $cls->remarks = $value->remarks;
                    $cls->save();
                }else{
                    $cls->asp_class = $value->asp_class;
                    $cls->asp_sector = $value->asp_sector;
                    $cls->upper = $value->upper;
                    $cls->lower = $value->lower;
                    $cls->editor = $value->editor;
                    $cls->status = $value->status;
                    $cls->remarks = $value->remarks;
                    $cls->update();
                }
                $atst = AirspaceClassTemp::find($value->id);
                $atst->status='U';
                $atst->update();
            }
            
        }
        if ($data->freq !== null){

            // $frq=FreqUsage::where('asp_id','=',$data->ats_airspace_id)->first();
            // if ($frq !== null){
            //     $frq->delete();
            // }
            foreach ($data->freq as $key => $value) {
                $frq=FreqUsage::where('id','=',$value->id)->first();
                if ($frq === null) {
                    $frq = new FreqUsage;
                    $frq->id =$value->id;
                    $frq->asp_id = $value->asp_id;
                    $frq->freqid = $value->freqid;
                    $frq->seq = $value->seq;
                    $frq->editor = $value->editor;
                    $frq->status = $value->status;
                    $frq->save();
                }else{
                    $frq->freqid = $value->freqid;
                    $frq->seq = $value->seq;
                    $frq->editor = $value->editor;
                    $frq->status = $value->status;
                    $frq->update();
                }
                $atst = FreqUsageTemp::find($value->id);
                $atst->status='U';
                $atst->update();
            }
        }
        
        // use App\Models\Api\AirspaceTemp;
        // use App\Models\Api\Airspace;
        // use App\Models\Api\AirspaceClass;
        // use App\Models\Api\AirspaceSegment;
        // use App\Models\Api\AirspaceClassTemp;
        // use App\Models\Api\AirspaceSegmentTemp;

    }


    function UpdateGenEnrAts(){   
        $originalInput=Request::input();
        $atstemp = getDataApi($originalInput, 'api/atsall?status=R&or=status:N');
        $atremtemp = getDataApi($originalInput, 'api/atsremarkall?status=R&or=status:N');
        
        // dd($atstemp);
        foreach ($atstemp as $key => $value) {
            unset($cur);
            $cur=Ats::where('id','=',$value->id)->first();
            $lat1=$value->geom->coordinates[0][1];
            $lon1=$value->geom->coordinates[0][0];
            $lat2=$value->geom->coordinates[1][1];
            $lon2=$value->geom->coordinates[1][0];

            if ($cur === null) {
                $cur = new Ats;
                $cur->id =$value->id;
                $cur->ats_id = $value->ats_id;
                $cur->ats_ident = $value->ats_ident;
                $cur->ctry = $value->ctry;
                $cur->icao_fir = $value->icao_fir;
                $cur->seq_424 = $value->seq_424;
                $cur->dir_424 = $value->dir_424;
                $cur->direction = $value->direction;
                $cur->type = $value->type;
                $cur->rnp_type = $value->rnp_type;
                $cur->point = $value->point;
                $cur->wpt_type = $value->wpt_type;
                $cur->point2 = $value->point2;
                $cur->wpt_type2 = $value->wpt_type2;
               
                $cur->geom = 'LINESTRING('.$lon1.' '.$lat1.','.$lon2.' '.$lat2.')';
                $cur->track_out = $value->track_out;
                $cur->track_in = $value->track_in;
                $cur->dist = $value->dist;
                $cur->maa = $value->maa;
                $cur->mfa = $value->mfa;
                $cur->mea_out = $value->mea_out;
                $cur->cruise_level = $value->cruise_level;
                $cur->bidirect = $value->bidirect;
                $cur->seg_use = $value->seg_use;
                $cur->level = $value->level;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $cur->ats_id = $value->ats_id;
                $cur->ats_ident = $value->ats_ident;
                $cur->ctry = $value->ctry;
                $cur->icao_fir = $value->icao_fir;
                $cur->seq_424 = $value->seq_424;
                $cur->dir_424 = $value->dir_424;
                $cur->direction = $value->direction;
                $cur->type = $value->type;
                $cur->rnp_type = $value->rnp_type;
                $cur->point = $value->point;
                $cur->wpt_type = $value->wpt_type;
                $cur->point2 = $value->point2;
                $cur->wpt_type2 = $value->wpt_type2;
            
                $cur->geom = 'LINESTRING('.$lon1.' '.$lat1.','.$lon2.' '.$lat2.')';
                $cur->track_out = $value->track_out;
                $cur->track_in = $value->track_in;
                $cur->dist = $value->dist;
                $cur->maa = $value->maa;
                $cur->mfa = $value->mfa;
                $cur->mea_out = $value->mea_out;
                $cur->cruise_level = $value->cruise_level;
                $cur->bidirect = $value->bidirect;
                $cur->seg_use = $value->seg_use;
                $cur->level = $value->level;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->update();
                
            }
            
            $atst = AtsTemp::find($value->id);
            $atst->status='U';
            $atst->update();
            // dd($value,$cur);
        }

        foreach ($atremtemp as $key => $value) {
            unset($cur);
            $cur=AtsRemarks::where('id','=',$value->id)->first();
            // dd($value,$cur);
            if ($cur === null) {
                $cur = new AtsRemarks;
                $cur->id =$value->id;
                $cur->ats_id = $value->ats_id;
                $cur->remarks = $value->remarks;
                $cur->asp_id = $value->asp_id;
                $cur->tbl = $value->tbl;
                $cur->airspace_id = $value->airspace_id;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $cur->ats_id = $value->ats_id;
                $cur->remarks = $value->remarks;
                $cur->asp_id = $value->asp_id;
                $cur->tbl = $value->tbl;
                $cur->airspace_id = $value->airspace_id;
                $cur->status = $value->status;
                $cur->update();
                
            }
            
            $atremt = AtsRemarksTemp::find($value->id);
            $atremt->status='U';
            $atremt->update();
            // dd($value,$cur);
        }

        //=== WAYPOINT NAVAID
 //ats
        $wpttemp = getDataApi($originalInput, 'api/waypoint/temp?status=R&or=status:N');
        foreach ($wpttemp as $key => $value) {
            unset($cur);
            $cur=Waypoint::where('id','=',$value->id)->first();
            $lat1=$value->geom->coordinates[1];
            $lon1=$value->geom->coordinates[0];
            // dd($value,$cur,'POINT('.$lon1.' '.$lat1.')');

            if ($cur === null) {
                $cur = new Waypoint;
                $cur->id =$value->id;
                $cur->wpt_id = $value->wpt_id;
                $cur->wpt_name = $value->wpt_name;
                $cur->desc_name = $value->desc_name;
                $cur->ctry = $value->ctry;
                $cur->type = $value->type;
                $cur->usage_cd = $value->usage_cd;
                $cur->mag_var = $value->mag_var;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->status_vld = $value->status_vld;
                $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $cur->wpt_id = $value->wpt_id;
                $cur->wpt_name = $value->wpt_name;
                $cur->desc_name = $value->desc_name;
                $cur->ctry = $value->ctry;
                $cur->type = $value->type;
                $cur->usage_cd = $value->usage_cd;
                $cur->mag_var = $value->mag_var;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->status_vld = $value->status_vld;
                $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->update();
                
            }
            
            $wptp = WaypointTemp::find($value->id);
            $wptp->status='U';
            $wptp->update();
            // dd($value,$cur);
        }
        $navtemp = getDataApi($originalInput, 'api/navaidall?status_vld=R&or=status_vld:N');
        foreach ($navtemp as $key => $value) {
            unset($cur);
            $cur=Navaid::where('id','=',$value->id)->first();
            $lat1=$value->geom->coordinates[1];
            $lon1=$value->geom->coordinates[0];
            $dmegeom=null;
            if ($value->dmegeom){
                $dmegeom='POINT('.$value->dmegeom->coordinates[0].' '.$value->dmegeom->coordinates[1].')';
            }
            // dd($value,$cur,'POINT('.$lon1.' '.$lat1.')');

            if ($cur === null) {
                $cur = new Navaid;
                $cur->id =$value->id;
                $cur->nav_id = $value->nav_id;
                $cur->nav_name = $value->nav_name;
                $cur->nav_ident = $value->nav_ident;
                $cur->type = $value->type;
                $cur->col_dme = $value->col_dme;
                $cur->freq = $value->freq;
                $cur->icao = $value->icao;
                $cur->range = $value->range;
                $cur->altitude = $value->altitude;
                $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                $cur->dmegeom = $dmegeom;
                $cur->mag_var = $value->mag_var;
                $cur->channel = $value->channel;
                $cur->dme_range = $value->dme_range;
                $cur->dme_elev = $value->dme_elev;
                $cur->ctry = $value->ctry;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->opr_hrs = $value->opr_hrs;
                $cur->remarks = $value->remarks;
                $cur->editor = $value->editor;
                $cur->status_vld = $value->status_vld;
                $cur->save();
            }else{
                $cur->nav_id = $value->nav_id;
                $cur->nav_name = $value->nav_name;
                $cur->nav_ident = $value->nav_ident;
                $cur->type = $value->type;
                $cur->col_dme = $value->col_dme;
                $cur->freq = $value->freq;
                $cur->icao = $value->icao;
                $cur->range = $value->range;
                $cur->altitude = $value->altitude;
                $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                $cur->dmegeom = $dmegeom;
                $cur->mag_var = $value->mag_var;
                $cur->channel = $value->channel;
                $cur->dme_range = $value->dme_range;
                $cur->dme_elev = $value->dme_elev;
                $cur->ctry = $value->ctry;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->opr_hrs = $value->opr_hrs;
                $cur->remarks = $value->remarks;
                $cur->editor = $value->editor;
                $cur->status_vld = $value->status_vld;
                // dd($valup,$cur);
                $cur->update();
                
            }
            
            $nav = NavaidTemp::find($value->id);
            $nav->status_vld='U';
            $nav->update();
            // dd($value,$cur);
        }
        return redirect('/DataRequest');
    }
function updateairporttemp($arptident){
    $originalInput=Request::input();
    $user = Auth::user();
    if ($user->isAdmin()) {
        // return view('pages.admin.home');
    }
    // di query berdasarkan airport

    $asp= getDataApi($originalInput, '/api/airspace/temp/list?arpt_ident='.$arptident.'&status=R&or=status:N');
    // dd(!empty($asp));
    if (!empty($asp)==true){
        // dd($asp,'DATA ASP NO EMPTY');
        $this->updateairspace($asp[0]);
        
    }
    // dd($asp,'DATA ASP');
    $freq = getDataApi($originalInput, 'api/freq/temp/usage?status=R&or=status:N');
    foreach ($freq as $key => $fq) {
        
        if ($fq->arpt_ident==$arptident){
            unset($fucur);
            $fucur=FreqUsage::where('id','=',$fq->id)->first();
            // dd($fq,$fucur);
            if($fucur==null){
                $fucur=new FreqUsage;
                $fucur->id = $fq->id;
                $fucur->arpt_ident = $fq->arpt_ident;
                $fucur->freqid = $fq->freqid;
                $fucur->seq = $fq->seq;
                $fucur->status = $fq->status;
                $fucur->editor = $fq->editor;
                $fucur->save();
            }else{
                $fucur->freqid = $fq->freqid;
                $fucur->seq = $fq->seq;
                $fucur->status = $fq->status;
                $fucur->editor = $fq->editor;
                $fucur->save();
            }
            $nav = FreqUsageTemp::find($fq->id);
            $nav->status='U';
            $nav->update();

            $csign=$fq->callsign;
            foreach ($csign as $key => $cs) {
                unset($fcur);
                $fcur=Frequency::where('id','=',$cs->id)->first();
                if($fcur==null){
                    $fcur=new Frequency;
                    $fcur->id = $cs->id;
                    $fcur->types = $cs->types;
                    $fcur->call_sign = $cs->call_sign;
                    $fcur->ctry = $cs->ctry;
                    $fcur->ats_unit = $cs->ats_unit;
                    $fcur->name = $cs->name;
                    $fcur->sector = $cs->sector;
                    $fcur->freq_id = $cs->freq_id;
                    $fcur->remarks = $cs->remarks;
                    $fcur->status = $cs->status;
                    $fcur->editor = $cs->editor;
                    $fcur->save();
                }else{
                    $fcur->types = $cs->types;
                    $fcur->call_sign = $cs->call_sign;
                    $fcur->ctry = $cs->ctry;
                    $fcur->ats_unit = $cs->ats_unit;
                    $fcur->name = $cs->name;
                    $fcur->sector = $cs->sector;
                    $fcur->freq_id = $cs->freq_id;
                    $fcur->remarks = $cs->remarks;
                    $fcur->status = $cs->status;
                    $fcur->editor = $cs->editor;
                    $fcur->save();
                }

                $frtemp = FrequencyTemp::find($cs->id);
                $frtemp->status='U';
                $frtemp->update();

                $fseg=$cs->segment;
                foreach ($fseg as $key => $fs) {
                    unset($fscur);
                    $fscur=FreqSeg::where('id','=',$fs->id)->first();
                    if($fscur==null){
                        $fscur=new FreqSeg;
                        $fscur->id = $fs->id;
                        $fscur->freq_id = $fs->freq_id;
                        $fscur->call_sign = $fs->call_sign;
                        $fscur->level = $fs->level;
                        $fscur->opr_hrs = $fs->opr_hrs;
                        $fscur->remarks = $fs->remarks;
                        $fscur->satcom = $fs->satcom;
                        $fscur->logon = $fs->logon;
                        $fscur->status = $fs->status;
                        $fscur->editor = $fs->editor;
                        $fscur->save();
                    }else{
                        $fscur->freq_id = $fs->freq_id;
                        $fscur->call_sign = $fs->call_sign;
                        $fscur->level = $fs->level;
                        $fscur->opr_hrs = $fs->opr_hrs;
                        $fscur->remarks = $fs->remarks;
                        $fscur->satcom = $fs->satcom;
                        $fscur->logon = $fs->logon;
                        $fscur->status = $fs->status;
                        $fscur->editor = $fs->editor;
                        $fscur->save();

                    }
                    $fstemp = FreqSegTemp::find($fs->id);
                    $fstemp->status='U';
                    $fstemp->update();

                    $fvf=$fs->value[0];
                    $fvcurr=FreqValue::where('id','=',$fvf->id)->first();
                    if($fvcurr==null){
                        $fvcurr=new FreqValue;
                        $fvcurr->id = $fvf->id;
                        $fvcurr->freq_id = $fvf->freq_id;
                        $fvcurr->freq = $fvf->freq;
                        $fvcurr->unit = $fvf->unit;
                        $fvcurr->status = $fvf->status;
                        $fvcurr->save();
                    }else{
                        $fvcurr->freq_id = $fvf->freq_id;
                        $fvcurr->freq = $fvf->freq;
                        $fvcurr->unit = $fvf->unit;
                        $fvcurr->status = $fvf->status;
                        $fvcurr->save();
                    }
                    $fvtemp = FreqValueTemp::find($fvf->id);
                    $fvtemp->status='U';
                    $fvtemp->update();
                }
            }
        }
    }
    
    $rwy = getDataApi($originalInput, 'api/rwy/temp?arpt_ident='.$arptident.'&status=R&or=status:N');
    // use App\Models\Api\RwyPhysicalTemp;
    // use App\Models\Api\Rwylgt;
    // use App\Models\Api\RwylgtTemp;

    // dd($rwy);

    foreach ($rwy as $key => $rw) {
       
        unset($rcur);
        $rcur=Runway::where('id','=',$rw->id)->first();
        if ($rcur==null){
            $rcur=new Runway;
            $rcur->id =$rw->id;
            $rcur->rwy_id =$rw->rwy_id;
            $rcur->arpt_ident =$rw->arpt_ident;
            $rcur->rwy_ident = $rw->rwy_ident;
            $rcur->length = $rw->length;
            $rcur->width = $rw->width;
            $rcur->pcn = $rw->pcn;
            $rcur->surface = $rw->surface;
            $rcur->strip_l = $rw->strip_l;
            $rcur->strip_w = $rw->strip_w;
            $rcur->thr_low = $rw->thr_low;
            $rcur->thr_high = $rw->thr_high;
            $rcur->editor = $rw->editor;
            $rcur->status = $rw->status;
            $rcur->save();

        }else{
            $rcur->rwy_ident = $rw->rwy_ident;
            $rcur->length = $rw->length;
            $rcur->width = $rw->width;
            $rcur->pcn = $rw->pcn;
            $rcur->surface = $rw->surface;
            $rcur->strip_l = $rw->strip_l;
            $rcur->strip_w = $rw->strip_w;
            $rcur->thr_low = $rw->thr_low;
            $rcur->thr_high = $rw->thr_high;
            $rcur->editor = $rw->editor;
            $rcur->status = $rw->status;
            $rcur->save();
        }
        $nav = RunwayTemp::find($rw->id);
        $nav->status='U';
        $nav->update();
    
        if ($rw->physicals){
            $thr=$rw->physicals;
            foreach ($thr as $key => $th) {
                unset($tcurr);
                $tcurr=RwyPhysical::where('id','=',$th->id)->first();
                $tlat1=$th->geom->coordinates[1];
                $tlon1=$th->geom->coordinates[0];
                $dispgeom=null;
                if ($th->disp_geom){
                    $dispgeom='POINT('.$th->disp_geom->coordinates[0].' '.$th->disp_geom->coordinates[1].')';
                }
                // dd($th,$tcurr);
                if ($tcurr==null){
                    $tcurr=new RwyPhysical;
                    $tcurr->id =$th->id;
                    $tcurr->rwy_key =$th->rwy_key;
                    $tcurr->rwy_id =$th->rwy_id;
                    $tcurr->rwy_ident =$th->rwy_ident;
                    $tcurr->mag_brg =$th->mag_brg;
                    $tcurr->true_brg =$th->true_brg;
                    $tcurr->thr_elev =$th->thr_elev;
                    $tcurr->tdz_elev =$th->tdz_elev;
                    $tcurr->swy_length =$th->swy_length;
                    $tcurr->swy_width =$th->swy_width;
                    $tcurr->cwy_length =$th->cwy_length;
                    $tcurr->cwy_width =$th->cwy_width;
                    $tcurr->geom = 'POINT('.$tlon1.' '.$tlat1.')';
                    $tcurr->disp_geom = $dispgeom;
                    $tcurr->slope =$th->slope;
                    $tcurr->slope1 =$th->slope1;
                    $tcurr->resa_l =$th->resa_l;
                    $tcurr->resa_w =$th->resa_w;
                    $tcurr->disp_thr_elev =$th->disp_thr_elev;
                    $tcurr->disp_thr_length =$th->disp_thr_length;
                    $tcurr->tora =$th->tora;
                    $tcurr->toda =$th->toda;
                    $tcurr->asda =$th->asda;
                    $tcurr->lda =$th->lda;
                    $tcurr->remarks =$th->remarks;
                    $tcurr->status =$th->status;
                    $tcurr->editor =$th->editor;
                    $tcurr->save();

                }else{
                    $tcurr->rwy_ident =$th->rwy_ident;
                    $tcurr->mag_brg =$th->mag_brg;
                    $tcurr->true_brg =$th->true_brg;
                    $tcurr->thr_elev =$th->thr_elev;
                    $tcurr->tdz_elev =$th->tdz_elev;
                    $tcurr->swy_length =$th->swy_length;
                    $tcurr->swy_width =$th->swy_width;
                    $tcurr->cwy_length =$th->cwy_length;
                    $tcurr->cwy_width =$th->cwy_width;
                    $tcurr->geom = 'POINT('.$tlon1.' '.$tlat1.')';
                    $tcurr->disp_geom = $dispgeom;
                    $tcurr->slope =$th->slope;
                    $tcurr->slope1 =$th->slope1;
                    $tcurr->resa_l =$th->resa_l;
                    $tcurr->resa_w =$th->resa_w;
                    $tcurr->disp_thr_elev =$th->disp_thr_elev;
                    $tcurr->disp_thr_length =$th->disp_thr_length;
                    $tcurr->tora =$th->tora;
                    $tcurr->toda =$th->toda;
                    $tcurr->asda =$th->asda;
                    $tcurr->lda =$th->lda;
                    $tcurr->remarks =$th->remarks;
                    $tcurr->status =$th->status;
                    $tcurr->editor =$th->editor;
                    $tcurr->save();
                }
                $ttemp=RwyPhysicalTemp::find($th->id);
                $ttemp->status ='U';
                $ttemp->update();

                if ($th->lighting){
                    unset($lgt);
                    $lgt=$th->lighting[0];
                    unset($lcur);
                    $lcur=Rwylgt::where('id','=',$lgt->id)->first();
                    // dd($lcur,$lgt);
                    if ($lcur==null){
                        $lcur=new Rwylgt;
                        $lcur->id =$lgt->id;
                        $lcur->rwy_id =$lgt->rwy_id;
                        $lcur->apch_lgt_type_len =$lgt->apch_lgt_type_len;
                        $lcur->thr_lgt_clr_wbar =$lgt->thr_lgt_clr_wbar;
                        $lcur->vasis_meht_papi =$lgt->vasis_meht_papi;
                        $lcur->tdz_lgt_len =$lgt->tdz_lgt_len;
                        $lcur->rwy_ctrln_lgt_length_spc_clr =$lgt->rwy_ctrln_lgt_length_spc_clr;
                        $lcur->rwy_edge_lgt_len_spc_clr =$lgt->rwy_edge_lgt_len_spc_clr;
                        $lcur->rwy_end_lgt_clr_wbar =$lgt->rwy_end_lgt_clr_wbar;
                        $lcur->swy_lgt_len_clr =$lgt->swy_lgt_len_clr;
                        $lcur->remark =$lgt->remark;
                        $lcur->editor =$lgt->editor;
                        $lcur->status =$lgt->status;
                        $lcur->save();

                    }else{
                        $lcur->apch_lgt_type_len =$lgt->apch_lgt_type_len;
                        $lcur->thr_lgt_clr_wbar =$lgt->thr_lgt_clr_wbar;
                        $lcur->vasis_meht_papi =$lgt->vasis_meht_papi;
                        $lcur->tdz_lgt_len =$lgt->tdz_lgt_len;
                        $lcur->rwy_ctrln_lgt_length_spc_clr =$lgt->rwy_ctrln_lgt_length_spc_clr;
                        $lcur->rwy_edge_lgt_len_spc_clr =$lgt->rwy_edge_lgt_len_spc_clr;
                        $lcur->rwy_end_lgt_clr_wbar =$lgt->rwy_end_lgt_clr_wbar;
                        $lcur->swy_lgt_len_clr =$lgt->swy_lgt_len_clr;
                        $lcur->remark =$lgt->remark;
                        $lcur->editor =$lgt->editor;
                        $lcur->status =$lgt->status;
                        $lcur->save();
                    }
                    $ltemp=RwylgtTemp::find($lgt->id);
                    $ltemp->status ='U';
                    $ltemp->update();
                }
            }
        }

    }



    $arptnav = getDataApi($originalInput, 'api/navarpt/temp?status=R&or=status:N');
    foreach ($arptnav as $key => $anav) {
        if ($anav->arpt_ident==$arptident){
            // dd('ADA',$anav,$anav->navaid, !empty($anav->navaid),!empty($anav->ils));
            unset($acur);
            $acur=ArptNav::where('id','=',$anav->id)->first();
            if ($acur === null) {
                $acur = new ArptNav;
                $acur->id =$anav->id;
                $acur->arpt_ident =$anav->arpt_ident;
                $acur->nav_id =$anav->nav_id;
                $acur->seq = $anav->seq;
                $acur->status = $anav->status;
                $acur->save();
            }else{
                $acur->nav_id =$anav->nav_id;
                $acur->seq = $anav->seq;
                $acur->status = $anav->status;
                $acur->update();
                
            }
            
            $nav = ArptNavTemp::find($anav->id);
            $nav->status='U';
            $nav->update();
            //navaid
          
            if (!empty($anav->navaid)){
                $value=$anav->navaid[0];
                unset($cur);
                // dd('ADA',$value->id,$value);
                $cur=Navaid::where('id','=',$value->id)->first();
                $lat1=$value->geom->coordinates[1];
                $lon1=$value->geom->coordinates[0];
                $dmegeom=null;
                if ($value->dmegeom){
                    $dmegeom='POINT('.$value->dmegeom->coordinates[0].' '.$value->dmegeom->coordinates[1].')';
                }
                // dd($value,$cur,'POINT('.$lon1.' '.$lat1.')');
    
                if ($cur === null) {
                    // dd('KOSOSOOOOOOOONG NAVAID');
                    $cur = new Navaid;
                    $cur->id =$value->id;
                    $cur->nav_id = $value->nav_id;
                    $cur->nav_name = $value->nav_name;
                    $cur->nav_ident = $value->nav_ident;
                    $cur->type = $value->type;
                    $cur->col_dme = $value->col_dme;
                    $cur->freq = $value->freq;
                    $cur->icao = $value->icao;
                    $cur->range = $value->range;
                    $cur->altitude = $value->altitude;
                    $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                    $cur->dmegeom = $dmegeom;
                    $cur->mag_var = $value->mag_var;
                    $cur->channel = $value->channel;
                    $cur->dme_range = $value->dme_range;
                    $cur->dme_elev = $value->dme_elev;
                    $cur->ctry = $value->ctry;
                    $cur->arpt_ident = $value->arpt_ident;
                    $cur->opr_hrs = $value->opr_hrs;
                    $cur->remarks = $value->remarks;
                    $cur->editor = $value->editor;
                    $cur->status_vld = $value->status_vld;
                    $cur->save();
                }else{
                    // dd('UPATEEEEEEEE NAVAID');
                    // $cur->nav_id = $value->nav_id;
                    $cur->nav_name = $value->nav_name;
                    $cur->nav_ident = $value->nav_ident;
                    $cur->type = $value->type;
                    $cur->col_dme = $value->col_dme;
                    $cur->freq = $value->freq;
                    $cur->icao = $value->icao;
                    $cur->range = $value->range;
                    $cur->altitude = $value->altitude;
                    $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                    $cur->dmegeom = $dmegeom;
                    $cur->mag_var = $value->mag_var;
                    $cur->channel = $value->channel;
                    $cur->dme_range = $value->dme_range;
                    $cur->dme_elev = $value->dme_elev;
                    $cur->ctry = $value->ctry;
                    $cur->arpt_ident = $value->arpt_ident;
                    $cur->opr_hrs = $value->opr_hrs;
                    $cur->remarks = $value->remarks;
                    $cur->editor = $value->editor;
                    $cur->status_vld = $value->status_vld;
                    // dd($cur);
                    $cur->update();
                    
                }
                
                $nav = NavaidTemp::find($value->id);
                $nav->status_vld='U';
                $nav->update();
            }
            //ils
            if (!empty($anav->ils)){
            // if ($anav->ils !== null){
                $value=$anav->ils[0];
                unset($cur);
                // dd('ADA ILS',$value->id,$value);
                $cur=Ils::where('id','=',$value->id)->first();
                $lat1=$value->geom->coordinates[1];
                $lon1=$value->geom->coordinates[0];
                $gsgeom=null;
               
                if ($value->gs_geom){
                    $gsgeom='POINT('.$value->gs_geom->coordinates[0].' '.$value->gs_geom->coordinates[1].')';
                }
                // dd($value,$cur,'POINT('.$lon1.' '.$lat1.')');
    
                if ($cur === null) {
                    $cur = new Ils;
                    $cur->id =$value->id;
                    $cur->ils_id =$value->ils_id;
                    $cur->nav_id =$value->nav_id;
                    $cur->arpt_ident = $value->arpt_ident;
                    $cur->rwy_id = $value->rwy_id;
                    $cur->ils_ident = $value->ils_ident;
                    $cur->ils_name = $value->ils_name;
                    $cur->ils_cat = $value->ils_cat;
                    $cur->freq = $value->freq;
                    $cur->gs_freq = $value->gs_freq;
                    $cur->gs_angle = $value->gs_angle;
                    $cur->gs_hgt = $value->gs_hgt;
                    $cur->gs_elev = $value->gs_elev;
                    $cur->gs_freq = $value->gs_freq;
                    $cur->ch = $value->ch;
                    $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                    $cur->gs_geom = $gsgeom;
                    $cur->opr_hrs = $value->opr_hrs;
                    $cur->remarks = $value->remarks;
                    $cur->editor = $value->editor;
                    $cur->status = $value->status;
                    $cur->save();
                }else{
                    $cur->arpt_ident = $value->arpt_ident;
                    $cur->rwy_id = $value->rwy_id;
                    $cur->ils_ident = $value->ils_ident;
                    $cur->ils_name = $value->ils_name;
                    $cur->ils_cat = $value->ils_cat;
                    $cur->freq = $value->freq;
                    $cur->gs_freq = $value->gs_freq;
                    $cur->gs_angle = $value->gs_angle;
                    $cur->gs_hgt = $value->gs_hgt;
                    $cur->gs_elev = $value->gs_elev;
                    $cur->gs_freq = $value->gs_freq;
                    $cur->ch = $value->ch;
                    $cur->geom = 'POINT('.$lon1.' '.$lat1.')';
                    $cur->gs_geom = $gsgeom;
                    $cur->opr_hrs = $value->opr_hrs;
                    $cur->remarks = $value->remarks;
                    $cur->editor = $value->editor;
                    $cur->status = $value->status;
                    // dd($valup,$cur);
                    $cur->update();
                    
                }
                
                $nav = IlsTemp::find($value->id);
                $nav->status='U';
                $nav->update();
                // dd($value,$cur);
                 // ILS Marker
                 if (!empty($value->marker)){
                // if ($value->marker !== null){
                    $marker=$value->marker;
                    foreach ($marker as $key => $mrk) {
                        unset($mcur);
                        $mcur=IlsMarker::where('id','=',$mrk->id)->first();
                        // dd($mcur,$mrk);
                        $mlat1=$mrk->geom->coordinates[1];
                        $mlon1=$mrk->geom->coordinates[0];
                        if ($mcur === null) {
                            $mcur = new IlsMarker;
                            $mcur->id =$value->id;
                            $mcur->loc_id =$value->loc_id;
                            $mcur->mrkr_id =$value->mrkr_id;
                            $mcur->ils_id =$value->ils_id;
                            $mcur->mrkr_type =$value->mrkr_type;
                            $mcur->geom = 'POINT('.$mlon1.' '.$mlat1.')';
                            $mcur->freq =$value->freq;
                            $mcur->co_loc =$value->co_loc;
                            $mcur->remarks =$value->remarks;
                            $mcur->status =$value->status;
                            $mcur->save();
                        }else{
                            // $mcur->mrkr_type =$value->mrkr_type;
                            $mcur->geom = 'POINT('.$mlon1.' '.$mlat1.')';
                            $mcur->freq =$value->freq;
                            $mcur->co_loc =$value->co_loc;
                            $mcur->remarks =$value->remarks;
                            $mcur->status =$value->status;
                            $mcur->save();
                        }
                        $mnav = IlsmarkerTemp::find($value->id);
                        $mnav->status='U';
                        $mnav->update();
                    }
                    // dd($value->marker,$cur);
                }
                // DME navaid
                if (!empty($anav->navaid)){
                // if ($value->navaid !== null){
                    // dd($value->navaid,$cur);
                    $dme=$value->navaid;
                    foreach ($dme as $key => $mrk) {
                        unset($mcur);
                        $ncur=Navaid::where('id','=',$mrk->id)->first();
                        $nlat1=$mrk->geom->coordinates[1];
                        $nlon1=$mrk->geom->coordinates[0];
                        if ($cur === null) {
                            $ncur = new Navaid;
                            $ncur->id =$value->id;
                            $ncur->nav_id = $value->nav_id;
                            $ncur->nav_name = $value->nav_name;
                            $ncur->nav_ident = $value->nav_ident;
                            $ncur->type = $value->type;
                            $ncur->col_dme = $value->col_dme;
                            $ncur->freq = $value->freq;
                            $ncur->icao = $value->icao;
                            $ncur->range = $value->range;
                            $ncur->altitude = $value->altitude;
                            $ncur->geom = 'POINT('.$lon1.' '.$lat1.')';
                            $ncur->dmegeom = $dmegeom;
                            $ncur->mag_var = $value->mag_var;
                            $ncur->channel = $value->channel;
                            $ncur->dme_range = $value->dme_range;
                            $ncur->dme_elev = $value->dme_elev;
                            $ncur->ctry = $value->ctry;
                            $ncur->arpt_ident = $value->arpt_ident;
                            $ncur->opr_hrs = $value->opr_hrs;
                            $ncur->remarks = $value->remarks;
                            $ncur->editor = $value->editor;
                            $ncur->status_vld = $value->status_vld;
                            $ncur->save();
                        }else{
                            $ncur->nav_id = $value->nav_id;
                            $ncur->nav_name = $value->nav_name;
                            $ncur->nav_ident = $value->nav_ident;
                            $ncur->type = $value->type;
                            $ncur->col_dme = $value->col_dme;
                            $ncur->freq = $value->freq;
                            $ncur->icao = $value->icao;
                            $ncur->range = $value->range;
                            $ncur->altitude = $value->altitude;
                            $ncur->geom = 'POINT('.$lon1.' '.$lat1.')';
                            $ncur->dmegeom = $dmegeom;
                            $ncur->mag_var = $value->mag_var;
                            $ncur->channel = $value->channel;
                            $ncur->dme_range = $value->dme_range;
                            $ncur->dme_elev = $value->dme_elev;
                            $ncur->ctry = $value->ctry;
                            $ncur->arpt_ident = $value->arpt_ident;
                            $ncur->opr_hrs = $value->opr_hrs;
                            $ncur->remarks = $value->remarks;
                            $ncur->editor = $value->editor;
                            $ncur->status_vld = $value->status_vld;
                            // dd($valup,$cur);
                            $ncur->update();
                            
                        }
                        
                        $nnav = NavaidTemp::find($value->id);
                        $nnav->status_vld='U';
                        $nnav->update();
                    }
                    // dd($value->marker,$cur);
                }
    
            }
        }

    }

  


 // dd($atstemp);
        //===
        $contenttemp = getDataApi($originalInput, 'api/eaip/contenttemp?arpt_ident='.$arptident.'&status=R&or=status:N');
        $aprontemp = getDataApi($originalInput, 'api/arpt/temp/aprontwy?arpt_ident='.$arptident.'&status=R&or=status:N');
        $pstemp = getDataApi($originalInput, 'api/arpt/temp/parkingstand?arpt_ident_gate='.$arptident.'&status=R&or=status:N');
        $pbtemp = getDataApi($originalInput, 'api/arpt/temp/pushback?arpt_ident_pushback='.$arptident.'&status=R&or=status:N');
        $obstacle = getDataApi($originalInput, 'api/eaip/obstacletemp?arpt_ident='.$arptident.'&status=R&or=status:N');
       
        // dd($aprontemp,$pstemp,$pbtemp);
        $arptup=[];
        foreach ($contenttemp as $key => $value) {
            unset($cur);
            if ($value->category_id==2){
                $adcorddbl =  CC_Temp::where('category_id','=',2)
                    ->where('arpt_ident','=',$value->arpt_ident)
                    ->get();
                    // dd($adcorddbl);
                if (count($adcorddbl) > 1){
                    foreach ($adcorddbl as $key => $val) {
                        $deldbl =  CC_Temp::where('id','=',$val->id)
                        ->first();
                        $deldbl->delete();
                    }
                }

            }
            $cur=CC_Curr::where('category_id','=',$value->category_id)
                        ->where('arpt_ident','=',$value->arpt_ident)->first();
            // dd($value,$cur);
            if ($cur === null) {
                $cur = new CC_Curr;
                $cur->category_id =$value->category_id;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->content = $value->content;
                $cur->sequence = $value->sequence;
                $cur->editor = $value->editor;
                $cur->font = $value->font;
                $cur->tab = $value->tab;
                $cur->status = $value->status;
                $cur->id = $value->id;
                $cur->save();
            }else{
                $cur->content=$value->content;
                $cur->status=$value->status;
                $cur->editor=$value->editor;
                $cur->save();
                
            }
            
            $contemp = CC_Temp::find($value->id);
            $contemp->status='U';
            $contemp->update();
            // dd($value,$cur);
            $arptcurr=Arpt::where('arpt_ident','=',$value->arpt_ident)->first();
            $loctemp=LocIndicatorTemp::where('loc_arptident','=',$value->arpt_ident)->first();
            if ($loctemp){
                $loctemp->status=$value->status;
                $loctemp->save();
            }

            $loc=LocIndicator::where('loc_arptident','=',$value->arpt_ident)->first();
            switch ($value->category_id) {
                case 228:
                    $arptcurr->elev=$value->content;
                    $arptcurr->save();
                    break;
                case 229:
                    $arptcurr->icao=$value->content;
                    $arptcurr->save();
                    $loc->indicator=$value->content;
                    $loc->status=$value->status;
                    $loc->save();
                    break;
                case 230:
                    $arptcurr->iata=$value->content;
                    $arptcurr->save();
                    break;
                case 231:
                    $arptcurr->arpt_name=$value->content;
                    $arptcurr->save();
                    $loc->name=$value->content;
                    $loc->status=$value->status;
                    $loc->save();
                    break;
                case 232:
                    $arptcurr->city_name=$value->content;
                    $arptcurr->save();
                    $loc->city=$value->content;
                    $loc->status=$value->status;
                    $loc->save();
                    break;
                case 233:
                    $arptcurr->ctry=$value->content;
                    $arptcurr->save();
                    $loc->ctry=$value->content;
                    $loc->status=$value->status;
                    $loc->save();
                    break;
                case 234:
                    $arptcurr->vol=$value->content;
                    $arptcurr->save();
                    break;
            }
        }
       

        foreach ($aprontemp as $key => $value) {
            unset($cur);
            $cur=EaipApronTwy::where('id','=',$value->id)->first();
            // dd($value,$cur,$aprontemp[$key]);
            if ($cur === null) {
                $cur = new EaipApronTwy;
                $cur->id =$value->id;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->name = $value->name;
                $cur->dimension = $value->dimension;
                $cur->surface = $value->surface;
                $cur->strength = $value->strength;
                $cur->type = $value->type;
                $cur->group = $value->group;
                $cur->sequence = $value->sequence;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $cur->arpt_ident = $value->arpt_ident;
                $cur->name = $value->name;
                $cur->dimension = $value->dimension;
                $cur->surface = $value->surface;
                $cur->strength = $value->strength;
                $cur->type = $value->type;
                $cur->group = $value->group;
                $cur->sequence = $value->sequence;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->update();
            }
            
            $aptemp = EaipApronTwyTemp::find($value->id);
            $aptemp->status='U';
            $aptemp->update();
            // dd($value,$cur);
        }

        foreach ($pstemp as $key => $value) {
            unset($cur);
            $cur=EaipArptGate::where('id','=',$value->id)->first();
            // dd($value,$cur);

            if ($cur === null) {
                $cur = new EaipArptGate;
                $cur->id =$value->id;
                $cur->apron_id =$value->apron_id;
                $cur->arpt_ident_gate = $value->arpt_ident_gate;
                $cur->no_gate = $value->no_gate;
                $cur->gate_lat = $value->gate_lat;
                $cur->gate_lon = $value->gate_lon;
                $cur->aircraft_type = $value->aircraft_type;
                $cur->ramp_name = $value->ramp_name;
                $cur->sequence = $value->sequence;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $cur->apron_id =$value->apron_id;
                $cur->arpt_ident_gate = $value->arpt_ident_gate;
                $cur->no_gate = $value->no_gate;
                $cur->gate_lat = $value->gate_lat;
                $cur->gate_lon = $value->gate_lon;
                $cur->aircraft_type = $value->aircraft_type;
                $cur->ramp_name = $value->ramp_name;
                $cur->sequence = $value->sequence;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->update();
            }
            
            $pst = EaipArptGateTemp::find($value->id);
            $pst->status='U';
            $pst->update();
            // dd($value,$cur);
        }

        foreach ($pbtemp as $key => $value) {
            unset($cur);
            $cur=EaipPushback::where('id','=',$value->id)->first();
            $valup[]=$value;
            if ($cur === null) {
                $cur = new EaipPushback;
                $cur->id =$value->id;
                $cur->arpt_ident_pushback =$value->arpt_ident_pushback;
                $cur->no_gate = $value->no_gate;
                $cur->ramp_name = $value->ramp_name;
                $cur->procedure = $value->procedure;
                $cur->radio = $value->radio;
                $cur->sequence = $value->sequence;
                $cur->remarks = $value->remarks;
                $cur->nbr = $value->nbr;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->save();
            }else{
                $cur->arpt_ident_pushback =$value->arpt_ident_pushback;
                $cur->no_gate = $value->no_gate;
                $cur->ramp_name = $value->ramp_name;
                $cur->procedure = $value->procedure;
                $cur->radio = $value->radio;
                $cur->sequence = $value->sequence;
                $cur->remarks = $value->remarks;
                $cur->nbr = $value->nbr;
                $cur->editor = $value->editor;
                $cur->status = $value->status;
                $cur->update();
            }
            
            $pbt = EaipPushbackTemp::find($value->id);
            $pbt->status='U';
            $pbt->update();
            // dd($value,$cur);
        }
        foreach ($obstacle as $key => $value) {
            unset($cur);
            $cur=Obst::where('id','=',$value->id)->first();
            // dd($valup,$cur);
            if ($cur === null) {
                $cur = new Obst;
                $cur->id =$value->id;
                $cur->obs_type = $value->obs_type;
                $cur->lighted = $value->lighted;
                $cur->obs_group = $value->obs_group;
                // 'POINT('.$lon.' '.$lat.')';
                $cur->geom ='POINT('.$value->geom->coordinates[0].' '.$value->geom->coordinates[0].')';
                $cur->elev_ft = $value->elev_ft;
                $cur->editor = $value->editor;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->hgt = $value->hgt;
                $cur->notes = $value->notes;
                $cur->position = $value->position;
                $cur->status = $value->status;
                $cur->save();
                // Obst::updateOrCreate($valup);
            }else{
                $cur->obs_type = $value->obs_type;
                $cur->lighted = $value->lighted;
                $cur->obs_group = $value->obs_group;
                // 'POINT('.$lon.' '.$lat.')';
                $cur->geom ='POINT('.$value->geom->coordinates[0].' '.$value->geom->coordinates[0].')';
                $cur->elev_ft = $value->elev_ft;
                $cur->editor = $value->editor;
                $cur->arpt_ident = $value->arpt_ident;
                $cur->hgt = $value->hgt;
                $cur->notes = $value->notes;
                $cur->position = $value->position;
                $cur->status = $value->status;
                $cur->update();
            }
            
            $obt = ObstTemp::find($value->id);
            $obt->status='U';
            $obt->update();
            // dd($value,$cur);
        }
        
        $raw=Raw_Pub::where('fieldid','=',$arptident)->where('status_raw','<>','100')->first();
        if ($raw){
            // dd($raw);
            $raw->delete();
        }
        // $data['content'] = getDataApi($originalInput, 'api/airport/content/eaip_chart_content_temp');

        return redirect('/DataRequest');
    }
    public function listprocedure($id,$chart)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        // $data['transcode']=DB::table('cod_trans')->get();
        // $data['rt']=DB::table('cod_trans_types')->orderby('trans_types','asc')->get();
        // $data['pterm']=DB::table('cod_path_term')->orderby('id','asc')->get();
        // $data['ptval']=DB::table('cod_pt_val')->get();
        // $data['ptvalue']=DB::table('cod_pt_value')->get();
        // $data['altdesc']=DB::table('cod_alt_desc')->get();
        // $data['wptdesc']=DB::table('cod_wpt_desc')->orderby('id','asc')->get();
        $data['proctemp'] = getDataApi($originalInput, 'api/procedures/temp?arpt_ident='.$id.'&chart_type='.$chart.'&deleted=0');
     
        // $data['trans'] = getDataApi($originalInput, 'api/transition?arpt_ident='.$id.'&chart_type='.$chart.'&deleted=0&sort=chart_type:asc');
        $data['transtemp'] = getDataApi($originalInput, 'api/transition/temp?arpt_ident='.$id.'&chart_type='.$chart.'&deleted=0');
        // $data['codchart'] = getDataApi($originalInput, 'api/cod/chart');
        $data['arpt'] = getDataApi($originalInput,'/api/airports?arpt_ident='.$id);
        $data['chart']=$chart;
        //  dd($data);
        return view('pages.publications.procedures.listproc',$data)->withInput(['tab'=>'tabItem2']);
    }
    public function listproceduresegment($id,$chart){
        $originalInput=Request::input();
        $user = Auth::user();
        // dd($id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['transcode']=DB::table('cod_trans')->get();
        $data['rt']=DB::table('cod_trans_types')->orderby('trans_types','asc')->get();
        // $data['pterm']=DB::table('cod_path_term')->orderby('id','asc')->get();
        // $data['ptval']=DB::table('cod_pt_val')->get();
        // $data['ptvalue']=DB::table('cod_pt_value')->get();
        // $data['altdesc']=DB::table('cod_alt_desc')->get();
        // $data['wptdesc']=DB::table('cod_wpt_desc')->orderby('id','asc')->get();
        // dd($chart);
        if (substr($id,0,3)=='new'){
            $ii=explode("@",$id);
            $data['proctemp'] = [];
            $data['proc'] = [];
            $arptident=$ii[1];
            $data['ats'] = [];
        }else{
            $data['proctemp'] = getDataApi($originalInput, 'api/procedures/temp?proc_id='.$id.'&deleted=0');
            $data['proc'] = getDataApi($originalInput, 'api/procedures?proc_id='.$id.'&deleted=0');
            $arptident=$data['proctemp'][0]->arpt_ident;
            // dd( $data['proctemp']);
            if ($data['proctemp'][0]->segment[0]->transition && ($chart=='46' || $chart=='47')){
                if ($chart=='47'){
                    $fixid = $data['proctemp'][0]->segment[0]->transition[0]->segment[0]->fix_id;

                }else if ($chart=='46'){
                    $ssg=$data['proctemp'][0]->segment;
                    usort($ssg, fn($a, $b) => strnatcmp($a->rt_type, $b->rt_type));
                    $cs=count($ssg)-1;
                    $cst=count($ssg[$cs]->transition[0]->segment)-1;
                    // dd($ssg[$cs],$cs,$cst);

                    $fixid = $ssg[$cs]->transition[0]->segment[$cst]->fix_id;
                }
                // dd($data['proctemp'], $fixid);
                $arrats = getDataApi($originalInput, 'api/ats/point/temp/'.$fixid);
                $ats1='';$aat='';
                foreach ($arrats as $rk => $ats ){
                    if ($ats->type !== 'V'){

                        if ( $ats1==''){
                            $ats1=$ats->ats_ident;
                        }else{
                            if ($ats->ats_ident !==$aat ){
                                $ats1 =$ats1.'/'. $ats->ats_ident; 
                            }
                        }
                        // dd($rk,$value);
                        $aat=$ats->ats_ident;
                    }
                }
                $data['ats']= $ats1;

            }else{
                $data['ats']='';
            }

        }
        $data['transtemp'] = getDataApi($originalInput, 'api/transition/temp?arpt_ident='.$arptident.'&chart_type='.$chart.'&deleted=0&sort=chart_type:asc');
        $data['arpt'] = getDataApi($originalInput,'/api/airports?arpt_ident='.$arptident);
        // dd( $data['ats']);
       
        // $data['holding'] = getDataApi($originalInput,'/api/holding/list?hld_type='.$arptident);
        // $data['proctemp'] = getDataApi($originalInput, 'api/procedures/temp?arpt_ident='.$id.'&chart_type='.$chart.'&deleted=0&sort=chart_type:asc');
        // $data['proc'] = getDataApi($originalInput, 'api/procedures?arpt_ident='.$id.'&chart_type='.$chart.'&deleted=0&sort=chart_type:asc');
        //  dd($data);
        $data['codchart'] = getDataApi($originalInput, 'api/cod/chart');
        $data['chart']=$chart;
       
        return view('pages.publications.procedures.listprocetail',$data);
    }
    public function listtransitionsegment($id,$info)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        $dd=explode('@',$info);
        $chart=$dd[0];
        $backto=$dd[1];
        // dd($info,$backto,$id);
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['transcode']=DB::table('cod_trans')->get();
        $data['rt']=DB::table('cod_trans_types')->orderby('trans_types','asc')->get();
        $data['pterm']=DB::table('cod_path_term')->orderby('id','asc')->get();
        $data['ptval']=DB::table('cod_pt_val')->get();
        $data['ptvalue']=DB::table('cod_pt_value')->get();
        $data['altdesc']=DB::table('cod_alt_desc')->get();
        $data['wptdesc']=DB::table('cod_wpt_desc')->orderby('id','asc')->get();
        if (substr($id,0,3)=='new'){
            $ii=explode("@",$id);
            $data['trans'] = [];
            $data['transtemp'] = [];
            $arptident=$ii[1];
        }else{
            $data['trans'] = getDataApi($originalInput, 'api/transition?proc_id='.$id.'&deleted=0&sort=chart_type:asc');
            $data['transtemp'] = getDataApi($originalInput, 'api/transition/temp?proc_id='.$id.'&deleted=0&sort=chart_type:asc');
            // dd($data['transtemp'],$id);
            $arptident=$data['transtemp'][0]->arpt_ident;
            
        }
        $data['ils'] = getDataApi($originalInput, 'api/ils/temp?arpt_ident='.$arptident);
        $data['arpt'] = getDataApi($originalInput,'/api/airports?arpt_ident='.$arptident);
        $data['holding'] = getDataApi($originalInput,'/api/holding/list/temp?hld_type='.$arptident);
        // $data['proctemp'] = getDataApi($originalInput, 'api/procedures/temp?arpt_ident='.$id.'&chart_type='.$chart.'&deleted=0&sort=chart_type:asc');
        // $data['proc'] = getDataApi($originalInput, 'api/procedures?arpt_ident='.$id.'&chart_type='.$chart.'&deleted=0&sort=chart_type:asc');
        //  dd($data['transtemp']);
        $data['codchart'] = getDataApi($originalInput, 'api/cod/chart');
        $data['chart']=$chart;
        $data['backto']=$backto;
        return view('pages.publications.procedures.listtransdetail',$data);
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
}
