<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;

class SecurityController extends Controller
{
    public function authenticate(Request $request)
    {
        // echo $request->input("initial");
        $validator = Validator::make($request->all(), [
            'initial' => 'required|string',
            'password' => 'required|string', 
        ]);
        $password = $request->input('password');
            
            if ($validator->fails()) {
                return ApiResponse::fail($validator->errors());
            }
            
            $initial = User::where('user_login',$request->initial)->first();

            // echo $initial -> 'user_id';

            // if ($initial && Hash::check($request->user_pass, md5($password))) {
            if ($initial && $initial->user_pass == md5($password)) {
                $token = Str::random(40); //GENERATE TOKEN BARU
                // echo $token;
                $initial->update(['device_id' => $token]); //UPDATE USER TERKAIT
                // $initial->update($upp->all()); //UPDATE USER TERKAIT
                //DAN KEMBALIKAN TOKENNYA UNTUK DIGUNAKAN PADA CLIENT
                // return response()->json(['status' => 'success', 'data' => $initial]);
                return ApiResponse::success($initial->fresh());
            }

            // // echo $initial;
            if (null === $initial) {
                return ApiResponse::error('user_not_found');
            }
            
        //bypass all password
        // if ($request->password == '0000') {
        //     return ApiResponse::success($initial);
        // }

        if ($initial->user_pass !== md5($password)) {
            return ApiResponse::error('password_missmatch');
        }
        // else{
        //     // $token  = $this->generateRandomString();
        //     $token = Str::random(40); //GENERATE TOKEN BARU
        //     $initial->update(['token' => $token]);
        //     // echo $token;
            
        // }

        // return ApiResponse::success($initial);
    }

    public function forceLogin(Request $request)
    {
        $email = $request->email;
        $user = User::whereUserEmail($email)->first();

        return ApiResponse::success($user);
    }

    // public function update(Request $request)
    // {
    //     $airport = User::find($id);

	// 	if (null === $airport) {
	// 		return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
	// 	}

	// 	$airport->update($request->all());

	// 	return ApiResponse::success($airport->fresh());
    // }

    function generateRandomString($length = 80)
    {
        $karakkter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakkter);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $karakkter[rand(0, $panjang_karakter - 1)];
        }
        return $str;
    }
    // public function getuser(Request $request, RequestParamHandler $rpm)
	// {
	// 	$results = $rpm->process($request, User::query()
    //                     ->with(['org'])
    //                     ->with(['usergroup']));

	// 	return ApiResponse::success($results);
	// }
}