<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Request;
use Auth;
use \Illuminate\Support\Facades\Route;

class InteraktifController extends Controller
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

    public function airport()
    {
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['airport']= getDataApi($originalInput,'api/airports?ctry=ID&deleted=0&sort=arpt_name:asc');
      
        // dd($data);
        
        return view('pages.interaktif.airport',$data);
    }
    public function airspace()
    {
        // $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        
        
        return view('pages.interaktif.airspace');
    }
    public function airspace3d()
    { 
        $user = Auth::user(); 
        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        } 
        return view('pages.interaktif.airspace3d');
    }
    public function enroute()
    {
        $originalInput=Request::input();
        $user = Auth::user();
        $data['enroute'] = getDataApi($originalInput,'api/ats/list/XX?ctry=ID');
        $data['alldataenr']= getDataApi($originalInput,'/api/ats/listall/');
        $data['sigmet'] = getDataApi($originalInput, 'api/getsigmet');
        // dd($data);

            return view('pages.interaktif.ats', $data);
       
    }

    public function navaid()
    {
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['navaids'] = getDataApi($originalInput, 'api/navaid/list?ctry=ID');
        $data['ils'] = getDataApi($originalInput, 'api/ils/list?ctry=ID');
        $data['channel'] = getDataApi($originalInput, 'api/nav/channel');

        return view('pages.interaktif.navaid',$data);

    }
    public function waypoint()
    {
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['waypoints'] = getDataApi($originalInput, 'api/waypoint/list?ctry=ID');
       
        // if ($page==null){
            return view('pages.interaktif.waypoint',$data);
        // }else{
        //     return view('pages.publications.navaidwaypoint.waypoints',$data);
        // }
    }
    public function arptinfo($arptid)
    {
        // echo $id; // ok kebaca
        $spl=explode('@',$arptid);
        $id=$spl[0];
        // dd($id);
        // dd($data);
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['eaiplist'] = getDataApi($originalInput, '/api/eaip/codaipsub/');
        $data['apronlist'] = getDataApi($originalInput, 'api/arpt/aprontwy?arpt_ident=' .$id. '&type=A');
        $data['twylist'] = getDataApi($originalInput, 'api/arpt/aprontwy?arpt_ident=' .$id. '&type=B');
        $data['obstacle'] = getDataApi($originalInput, 'api/eaip/obstacle?arpt_ident='.$id.'&deleted=0&sort=position:asc');
        $data['chart'] = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&deleted=0&sort=seq:asc');
        $data['rwylist'] = getDataApi($originalInput, 'api/rwy?arpt_ident='.$id);
        $data['rwylighting'] = getDataApi($originalInput, 'api/rwyarpt/'.$id);
        $data['airportcontent'] = getDataApi($originalInput, 'api/eaip/content?arpt_ident='.$id.'&sort=sequence:asc');
        $data['codaip'] = getDataApi($originalInput, 'api/eaip/codaip');
        $data['freetext'] = getDataApi($originalInput, 'api/eaip?arpt_ident='.$id.'&sort=category_id:asc&sort=sequence:asc');
        $data['freq'] = getDataApi($originalInput, 'api/freq/usage?arpt_ident='.$id.'&deleted=0&sort=seq:asc');
        $data['navaid'] = getDataApi($originalInput, 'api/navarpt?arpt_ident='.$id);
        $data['channel'] = getDataApi($originalInput, 'api/nav/channel');
        $data['arpt'] = getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $icao= $data['arpt'][0]->icao;
        // $method='GET';
        // $request = Request::create('/api/airports?arpt_ident='.$id, $method,);
        // Request::replace($request->input());
        // $instance = json_decode(Route::dispatch($request)->getContent());
        // Request::replace($originalInput);

        // if($instance->status=='success'){
        //     $icao= $instance->data[0]->icao;
        //     $data['arpt'] = $instance->data;
        // }
        if ($spl[1]=='interaktif'){
            $data['notam'] = getDataApi($originalInput, 'api/getnotam/'.$icao);
            $data['metar'] = getDataApi($originalInput, 'api/getmetar/'.$icao);
            $data['speci'] = getDataApi($originalInput, 'api/getspeci/'.$icao);
            $data['taf'] = getDataApi($originalInput, 'api/gettaf/'.$icao);
        }
         // arpturl = '/api/eaip?arpt_ident=' + arptident + '&category_id=' + codid + '&sort=sequence:asc'
        // dd($data);
        
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            if ($spl[1]=='interaktif'){
                return view('pages.interaktif.airportinfo', $data);
            }else if ($spl[1]=='html'){
                return view('pages.publications.eaip.eaiphtml', $data);
            }else if ($spl[1]=='pdf'){
                $params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
                // window.open('/pdf', 'Set Latitude and Longitude', params)
                // return redirect(@"('/pdf', 'Set Latitude and Longitude', $params)");
                // return redirect('/pdf/'.$id);//view('pages.publications.createpdf.generatepdf', $data);
                return view('pages.publications.createpdf.generatepdf', $data);
            }
        // }else{
        //     return abort('403');
        // }   
        
    }

    public function navinfo($info)
    {
       
        $infox= explode('@',$info);
        $id=$infox[0];
        // dd($infox[0]);
        // dd($infox);
        $originalInput=Request::input();
        $user = Auth::user();

        $data['parent'] = $infox[2];
        $data['parentid'] = $infox[3];
        $data['atsstatus'] = $infox[4];
        if ($infox[1] =='edit'){
            // dd($infox);
            $data['ats'] = getDataApi($originalInput,'/api/getpoint/ats/temp/'.$id);
            $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/temp/'.$id);
            $data['asp'] = getDataApi($originalInput,'/api/getpoint/asp/temp/'.$id);
            // $data['navtemp'] = getDataApi($originalInput,'/api/navaid/temp/list?nav_id='.$id);
            $data['nav'] = getDataApi($originalInput,'/api/navaid/temp/list?nav_id='.$id);
        }else {
            $data['ats'] = getDataApi($originalInput,'/api/getpoint/ats/'.$id);
            $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/'.$id);
            $data['asp'] = getDataApi($originalInput,'/api/getpoint/asp/'.$id);
            // $data['navtemp'] =[];
            $data['nav'] = getDataApi($originalInput,'/api/navaid/list?nav_id='.$id);
        }
        $data['id'] = $infox[1];
        $data['channel'] = getDataApi($originalInput,'/api/nav/channel');
        $data['navs'] = getDataApi($originalInput,'/api/cod/list/cod_nav_types');
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.interaktif.navaidinfo', $data);
        // }else{
        //     return abort('403');
        // }   
        
    }
    public function wptinfo($info)
    {
        $infox= explode('@',$info,5);
        $id=$infox[0];
        // dd($id);
        $originalInput=Request::input();
        $user = Auth::user();
        $data['parent'] = $infox[2];
        $data['parentid'] = $infox[3];
        $data['atsstatus'] = $infox[4];
        if ($infox[1] =='edit'){
            // dd($infox);
            $data['ats'] = getDataApi($originalInput,'/api/getpoint/ats/temp/'.$id);
            $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/temp/'.$id);
            // $data['navtemp'] = getDataApi($originalInput,'/api/navaid/temp/list?nav_id='.$id);
            $data['wpt'] = getDataApi($originalInput,'/api/waypoint/temp/list?wpt_id='.$id);
        }else{
            $data['ats'] = getDataApi($originalInput,'/api/getpoint/ats/'.$id);
            $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/'.$id);
            // $data['navtemp'] =[];
            $data['wpt'] = getDataApi($originalInput,'/api/waypoint/list?wpt_id='.$id);
        }
        // $data['ats'] = getDataApi($originalInput,'/api/getpoint/ats/'.$id);
        // $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/'.$id);
        // $data['wpt'] = getDataApi($originalInput,'/api/waypoint/list?wpt_id='.$id);
        $data['id'] = $infox[1];
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.interaktif.waypointinfo', $data);
        // }else{
        //     return abort('403');
        // }   
        
    }

    public function ilsinfo($info)
    {
        $originalInput=Request::input();
        $user = Auth::user();
        $infox= explode('@',$info,4);
        $id=$infox[0];

        $data['parent'] = $infox[2];
        $data['parentid'] = $infox[3];
        if ($infox[1] =='edit'){
            // dd($infox);
            $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/temp/'.$id);
            $data['ils'] = getDataApi($originalInput,'/api/ils/temp?ils_id='.$id);
        }else{
            $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/'.$id);
            $data['ils'] = getDataApi($originalInput,'/api/ils?ils_id='.$id);
        }
        // $data['proc'] = getDataApi($originalInput,'/api/getpoint/trans/'.$id);
        // $data['ils'] = getDataApi($originalInput,'/api/ils?ils_id='.$id);
        $data['id'] = $infox[1];
        $data['channel'] = getDataApi($originalInput,'/api/nav/channel');
        
        // $data['id'] = $id;
        // dd($data);
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.interaktif.ilsinfo', $data);
        // }else{
        //     return abort('403');
        // }   
        
    }
    

    public function gpsraim()
    {
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $data['airport'] = getDataApi($originalInput,'api/airport/list?raim=1&sort=arpt_name:asc');
        
        
        return view('pages.raim.raim',$data);
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
