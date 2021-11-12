<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\ApiResponse;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    // overide login to md5
    // public function login(Request $request)
    // {
    //    $user = User::where('name', $request->email)
    //                 ->where('password',md5($request->password))
    //                 ->first();
    //    Auth::login($user);
    //    return redirect('/home');
    //     $username = $request->email;
    //     $password = $request->password;
    //     $postdata = ['name' => $username, 'password'=>$password];
    //     $ret = Auth::attempt($postdata);
    //         if($ret){
    //             return 'success';
    //         }
    //         return $username.$password;
       
    //     return redirect('/home');
    // }

    /**
     * Logout, Clear Session, and Return.
     *
     * @return void
     */
    public function logout()
    {
        $user = Auth::user();
        $UpdStatus = User::findOrFail($user->id);
        try { 
            $UpdStatus->status_login = 0 ;
            $UpdStatus->save();
            // return ApiResponse::success($UpdStatus->fresh());
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
        // Log::info('User Logged Out. ', [$user]);

        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
    protected function sendLoginResponse(Request $request)
    {
	$request->session()->regenerate();
	$previous_session = Auth::User()->session_id;
	if ($previous_session) {
		\Session::getHandler()->destroy($previous_session);
	}
	Auth::user()->session_id = \Session::getId();
	
    Auth::user()->status_login = 1 ; //update log status
    Auth::user()->device_id = $this->getMac();
    Auth::user()->save();
    
	$this->clearLoginAttempts($request);

	    return $this->authenticated($request, $this->guard()->user())
	            ?: redirect()->intended($this->redirectPath());
    }
    public function getMac()
    {
        $MAC = exec('getmac');
        $MAC = strtok($MAC, ' ');
        return $MAC;  
    }
}
