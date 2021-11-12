<?php

namespace App\Http\Controllers;

use App\Models\Api\TxCdm;
use App\Models\Api\TxCdmChat;
use App\Models\Api\UserGroup as group;
use App\Models\Api\TxCdmUser;
use App\Models\Api\TxCdmLog as log;
use App\Models\Api\Volcano as va;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as MasterRequest;
use App\Models\User;
use App\Models\Room;
use App\ApiResponse;
use App\Managers\FileManager;
 
use Illuminate\Support\Facades\Mail;
use App\Mail\VonaMail;

class VolcanoController extends Controller
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

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $method='GET';
        $data['volcanoes'] = getDataApi($originalInput,'api/volcano?sort=va_last_update:desc');
         
        $data['alldataenr'] = getDataApi($originalInput,'/api/ats/listall/');
 
        $data['airports'] = getDataApi($originalInput,'/api/airports?ctry=ID');
        $data['sigmet'] = getDataApi($originalInput, 'api/getsigmet');
        // dd($data);
        // dd($data);
        // if ($user->hasPermission('view.volcano') || $user->isAdmin()) {
            return view('pages.volcano.volcano',$data);
        // }else{
        //     return abort('403');
        // }   
        
        
    }
    public function cdm()
    {
        $originalInput=Request::input();
        $user = Auth::user();
        $data['cdms'] = getDataApi($originalInput,'api/vol/txcdm?sort=cdm_date:desc');
        $frq = "select * from tm_volcano order by va_name asc";
        $data['volcanos'] =DB::select(DB::raw($frq));
        $data['users'] = getDataApi($originalInput,'api/user?org_id=1');
        // $method='GET';
        // $request = Request::create('api/vol/txcdm?sort=cdm_date:desc', $method,);
        // Request::replace($request->input());
        // $instance = json_decode(Route::dispatch($request)->getContent());
        // Request::replace($originalInput);
        
        // if($instance->status=='success'){
        //     $data['cdms'] = $instance->data;
        // }
        // dd($data);

        if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.volcano.cdm',$data);
        }else{
            return abort('403');
        }   
        
    }
    public function cdmlog($id)
    {
        // echo $id; // ok kebaca
        // dd($id);
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $cdmid= TxCdm::query()    
            ->where('va_no',$id)->first();
            // dd(!empty($cdmid),$cdmid);
        $data['volcano'] = va::query()->where('va_no',$id)->get();
        if (!empty($cdmid)){
            $data['cdmlog'] = log::query()->where('cdm_id',$cdmid->cdm_id)->orderby('cdm_issued','desc')->get();
            $data['cdmuser'] = getDataApi($originalInput,'api/vol/cdmuser?cdm_id='.$cdmid->cdm_id);
            $cdm_id=$cdmid->cdm_id;
            $sql = "SELECT MIN(cdm_issued) MIN_ISSUED, MAX(cdm_issued) MAX_ISSUED FROM tx_cdm_log WHERE CDM_ID=$cdm_id";
            // $sqll = "select a.cdm_log_id, a.cdm_response, a.cdm_issued, a.cdm_stakeholder, a.cdm_type, A.data_id, c.chat_content
            // 	from tx_cdm_log A 
            // 		left join tx_cdm_chat c ON a.chat_id=c.chat_id
            // 	WHERE a.cdm_id=$cdm_id AND a.cdm_type='12' AND a.cdm_type IS NOT NULL
            // 	ORDER BY a.cdm_issued desc";
            $data['time']=DB::select(DB::raw($sql));
        }else{
            $data['cdmlog']=[];
            $data['cdmuser']=[];
            $data['time']=[];
        }
                
        // $data['cdmlog'] = log::query()->selectRaw('max (cdm_log_id) cdm_log_id, cdm_type, cdm_issued, cdm_response, cdm_stakeholder')
        //                     ->where('cdm_id',$cdmid[0]->cdm_id)
        //                     ->groupBy('cdm_type')
        //                     ->groupBy('cdm_issued')
        //                     ->groupBy('cdm_response')
        //                     ->groupBy('cdm_stakeholder')
        //                     ->orderby('cdm_issued','desc')
        //                     ->get();
       
        // $data['cdmlog'] = getDataApi($originalInput,'api/vol/cdmlog?cdm_id='.$cdmid[0]->cdm_id);
        $data['cdmchat']=TxCdmChat::with('user.organization', 'user.profile')->where('va_no', $id)->orderBy('chat_id', 'desc')->paginate(25);
        // $data['cdmchat'] = getDataApi($originalInput,'api/vol/cdmchat?cdm_id='.$cdmid[0]->cdm_id.'&sort=chat_date:desc');
        $data['rooms']=Room::query()->where('va_no', $id)->orderBy('created_at', 'desc')->paginate(10);

       
        $data['ashtam'] = getDataApi($originalInput,'api/vol/ashtam?ashtam_volcano_number='.$id); 
        $data['vona'] = getDataApi($originalInput,'api/vol/vona?smithsonian_id='.$id.'&sort=issued:desc'); 
        $data['tbl'] = getDataApi($originalInput,'api/tblreff?reff_group=0002&sort=reff_order:asc');
        $frq = "select * from user_group where group_id >= 1 and group_id <= 16 and group_id <> 8 order by group_name asc";
       
        $data['stkholder'] =DB::select(DB::raw($frq));
        $frq = "select * from user_group where group_player = '1' order by group_order";
        $data['stakeholder'] =DB::select(DB::raw($frq));
        //SELECT cdm_log_id, cdm_id, data_id, cdm_type, cdm_stakeholder, cdm_stakeholder_id, cdm_issued, cdm_response, cdm_noticenumber, cdm_volcano, cdm_code, user_id, chat_id, cdm_notif, cdm_email FROM public.tx_cdm_log;
//SELECT cdm_id, chat_id, va_no, user_id, chat_id_reply, chat_type, chat_content, chat_file_path, chat_file_name, chat_file_ext, chat_file_size, chat_date, chat_status FROM public.tx_cdm_chat;

        // dd($fsql);
        // $ISSUED=substr($fsql[0]->min_issued,0,13).':00:00';
        // $MIN_ISSUED=substr($fsql[0]->min_issued,0,13).':00:00';
        // $MAX_ISSUED=substr($fsql[0]->max_issued,0,13).':00:00';
        // dd($ISSUED,$MIN_ISSUED,$MAX_ISSUED);
        // if($instance->status=='success'){
        //     $data['volcanoes'] = $instance->data;
        // }
        // dd($data);
        // $data['id'] = $id;
        
        // if ($user->hasPermission('view.cdm') || $user->isAdmin()) {
            return view('pages.volcano.cdmdetail', $data);
        // }else{
        //     return abort('403');
        // }   
        
    }

    public function older(Request $request, string $vaid, string $chatid)
    {
        $chats = TxCdmChat::with('user.organization', 'user.profile')
            ->where('va_no', $vaid)
            ->where('chat_id', '<', $chatid)
            ->orderBy('chat_id', 'desc')
            ->limit(25)
            ->get();

        return ApiResponse::success($chats);
    }

    public function refresh(Request $request, string $vaid, string $chatid)
    {
        $chats = TxCdmChat::with('user.organization', 'user.profile')
            ->where('va_no', $vaid)
            ->where('chat_id', '>', $chatid)
            ->where('user_id', '<>', Auth::user()->id)
            ->get();

        return ApiResponse::success($chats);
    }
  
    public function participants(Request $request, string $vano)
    {
        $cdm = TxCdm::where('va_no', $vano)->first();

        $participants = TxCdmUser::with('masteruser.profile')
            ->where('cdm_id', $cdm->cdm_id)
            ->get();

        return ApiResponse::success($participants);
    }

    public function chatList(MasterRequest $request, string $id)
    {
        $chats = TxCdmChat::with('user.organization', 'user.profile')->where('va_no', $id)->orderBy('chat_id', 'desc')->limit(25)->get();

        return ApiResponse::success($chats);
    }

    public function postChat(MasterRequest $request, FileManager $fm, string $id)
    {
        $cdm = TxCdm::with([
            'volcano' => function($query) {
                return $query->select('va_no', 'va_name');
            }
        ])->where('va_no', $id)->orderBy('cdm_id', 'desc')->first();

        if (null === $cdm) {
            return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
        }

        $poster = TxCdmUser::where('cdm_id', $cdm->cdm_id)->where('user_id', Auth::user()->id)->first();

        if (null === $poster) {
           return ApiResponse::error('not_member');
        }

        $validator = Validator::make($request->all(), [
            'text' => 'string',
            'file' => 'file',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()); 
        }

        $chat = new TxCdmChat();

        $last = DB::table('tx_cdm_chat')->orderBy('chat_id', 'desc')->first();

        if (null === $last) {
            $chat->chat_id = 1;
        } else {
            $chat->chat_id = $last->chat_id+1;
        }

        $chat->cdm_id = $cdm->cdm_id;
        $chat->va_no = $cdm->volcano->va_no;
        $chat->chat_content = $request->text;
        $chat->chat_type = $request->type;

        if ($request->has('file')) {
            $chat->chat_file_path = $request->file->getClientOriginalName();
            $chat->chat_file_name = $request->file->getClientOriginalName();
            $chat->chat_file_ext = $request->file->extension();
            $chat->chat_file_size = $request->file->getSize();

            $request->file->move(app()->basePath('public/upload/chat'), $request->file->getClientOriginalName());
        }

        $chat->save();

        $chat = TxCdmChat::with('user.organization', 'user.profile')->where('chat_id', $chat->chat_id)->first();

        return ApiResponse::success($chat);
    }
    public function editCdmGrp(MasterRequest $request, string $cdm_id){
        $data['cdm_id']=$cdm_id;
        $cdm_desc = TxCdm::select('cdm_desc')->where('cdm_id', $cdm_id)->first();
        
        $data['cdm_desc']=$cdm_desc->cdm_desc;
        $va_no = $request->va_no;
        $va = "select va_no, va_name, va_status from tm_volcano order by va_name asc";
        $volcanoes =DB::select(DB::raw($va));
        $list_va = '';
        $reff = "SELECT * FROM tb_reff WHERE REFF_GROUP='0001' ORDER BY REFF_CODE";
        $arr_sts =DB::select(DB::raw($reff));
        $list_va_sts = '';
        $va_status = 1;
        foreach ($volcanoes as $volcano) {
            $selected='';
            if($volcano->va_no==$va_no){
                $selected = 'selected="selected"';
                $va_status = $volcano->va_status;
            }
            $list_va .= '<option '.$selected.' value="'.$volcano->va_no.'"">'.$volcano->va_name.'</option>';
        }
        
        foreach ($arr_sts as $arr) {
            $selected = '';
            if($arr->reff_code==$va_status)
                $selected = 'selected="selected"';
            $list_va_sts .= '<option '.$selected.' value='.$arr->reff_code.'>'.$arr->reff_name.'</option>';
        }

        $data['list_va'] = $list_va;
        $data['list_va_status'] = $list_va_sts;
 
        $sql = "  SELECT *, C.id as u_id FROM tx_cdm A
                     LEFT JOIN tx_cdm_users B ON A.cdm_id=CAST (B.cdm_id as integer)
                     LEFT JOIN users C ON B.user_id=C.id
                     LEFT JOIN org D ON C.org_id=D.org_id
                     LEFT JOIN country E ON C.user_country=E.ident
                  WHERE A.CDM_ID='".$cdm_id."' AND C.email != '' AND C.user_status='1' ";
 

        $content_table = DB::select(DB::raw($sql));
        $no=0;
        $trows='';
        foreach($content_table as $row) {
            $no++;
            $button = '<button onclick="removeUser(\''.$no.'\')" type="button" class="btn btn-danger"><em class="icon ni ni-trash-alt"></em></button>';
            $trows .='<tr id="user_row'.$no.'">
                    <td><select class="form-control chosen" name="user_id[]" id="user_id'.$no.'" onchange="setUser(\'user_id'.$no.'\', '.$no.')" style="width:250px;"><option value="">Choose..</option>'.listUser($row->u_id).'</select></td>
                    <td id="designation'.$no.'">'.($row->user_position!='' ? $row->user_position.'<br>' : '').($row->user_unit!='' ? $row->user_unit.'<br>' : '').($row->org_name_en!='' ? $row->org_name_en.'<br>' : '').($row->country_name!='' ? '<b>'.strtoupper($row->country_name).'</b><br>' : '').'</td>
                    <td id="contact'.$no.'"><b>Mobile :</b><br>'.$row->user_phone.'<br><b>Email &nbsp; :</b><br>'.$row->email.'</td>
                    <td>'.$button.'</td>
                    </tr>';
        }
        if($no < 1){
                $trows = 
                    '<tr>
                       <td><select class="form-control" name="user_id[]" id="user_id1" onchange="setUser(\'user_id1\', 1)" style="width:250px;"><option value="">Choose..</option>'.listUser().'</select></td>
                       <td id="designation1">-</td>
                       <td id="contact1">-</td>
                       <td><button onclick="addUser()" type="button" class="btn btn-success"><em class="icon ni ni-user-add-fill"></em></button></td>
                    </tr>';
 
            }
        $data['no'] = $no;       
        $data['table_content']=$trows;
        return view('pages.volcano.partials.cdm-form-group',$data);
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
    public function storeCdmGrp(MasterRequest $request)
    { 
        
        $va = va::query()->where('va_no',$request->va_no)->first();
        if (null === $va) {
           return ApiResponse::error('Volcano not listed');
        }else { $va_name = $va->va_name; }

        $validator = Validator::make($request->all(), [
            'va_no' => 'required|string',
            'va_status' => 'required|string',
            'cdm_desc' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()); 
        }
        
        $field = array( "va_no"     => $request->va_no, 
                        "va_status" => $request->va_status,
                        "cdm_desc"  => $request->cdm_desc,
                        "cdm_date"  => date('Y-m-d H:i:s'),
                        "cdm_status"=> $request->va_status);
       

        $CDM_ID  = '';
        if($request->cdm_id==''){
            // check for twice
            $cdmcek = TxCdm::where("va_no", $request->va_no )->first(); 
            $cek = $cdmcek->cdm_id; 

            if($cek==''){  
                $stm = TxCdm::create($field);
                $id = $stm->cdm_id;        
            }else{
                $stm = TxCdm::where('cdm_id',$cek)
                    ->update($field);  
                $id = $cek;     
            } 
            $CDM_ID = $id;
        }else{
           $stm = TxCdm::where('cdm_id',$request->cdm_id)
                    ->update($field);   
           $CDM_ID = $request->cdm_id;
        }
        $USER_ID = $request->user_id;
        #AUTOINVITE
        $fsqlusers = User::selectRaw('users.id,email,first_name,last_name, role_user.role_id')
        ->rightJoin('role_user', function ($join) {
            $join->on('role_user.user_id', '=', 'users.id')
                    ->where('role_user.role_id', '=', '1');
        })         
        ->where('user_status','1')
        ->whereIn('users.id', $USER_ID)->get();
        // dd($fsqlusers);
        foreach ($fsqlusers as $fsqluser){

            $USER_EMAIL = strtolower($fsqluser->email);
            $USER_FULLNAME = $fsqluser->first_name .' '. $fsqluser->last_name;
            $USER_ID = $fsqluser->id;
            
            unset($vale); 
            $vale = [ $CDM_ID, $USER_ID ];  
            DB::insert('insert into tx_cdm_users (cdm_id, user_id)values(?,?)',$vale);

            $mailto = strtolower($USER_EMAIL);
            if(env('APP_ENV')=='local')
                $mailto = "heru4nomo@gmail.com";//for dev only
            $name  = $USER_FULLNAME;
            $details = [
                'title' => '[no-reply] Collaborative Decision Making (CDM) - '.$va_name,
                'body'  => 'Welcome to the Collaborative Decision Making (CDM) group of Volcanic Activity Impact Handling.<br>
                            Every information and coordination in this group only related to <b>Volcano '.$va_name.'</b> eruption.<br>
                            Please access the following link to start your participation <a href="https://iwish.dephub.go.id/" target="_blank">https://iwish.dephub.go.id/</a><br><br>
                            This message is sent automatically, do not reply to this message.<br>If you need help, please contact us via email iwish@aimindonesia.dephub.go.id<br><br>'
            ];

           Mail::to($mailto)->send(new VonaMail($details));                
        }

        unset($field);
        $field = array( "va_status" => $request->va_status,
                        "va_last_update" => date('Y-m-d H:i:s')
                );
        va::where('va_no',$request->va_no)
            ->update($field);
        
        echo $request->va_no;

    }
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
