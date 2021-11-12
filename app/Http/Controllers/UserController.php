<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use jeremykenedy\LaravelLogger\App\Models\Activity;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user(); 
        $dummyOrg = explode("@", $user->email);
        
        $visitors = Activity::select('userType')
                    ->where('userType','=','Guest')->count();
        $data['visitors']= $visitors; 

        $ActiveUsers = User::select('id')
                    ->where('activated','=',1)->count();
        $data['ActiveUsers']= $ActiveUsers;

        $OnlineUsers = User::select('id')
                    ->where('status_login','=',1)->count();
        $data['OnlineUsers']= $OnlineUsers;

        if($dummyOrg[1]=='iwish.id'){
            return redirect()->route('orgNotif');    
        }
        
        if ($user->isAdmin()) {
            return view('pages.admin.home',$data);
        }

        return view('pages.user.home',$data);
    }
}
