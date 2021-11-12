<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthMobile
{
	public function handle($request, Closure $next)
	{
		$token = $request->bearerToken();

		$user = User::where('api_token', $token)->first();

		if (null === $user) {
			return ApiResponse::error('unauthorized');
		}

		Auth::setUser($user);

		return $next($request);
	}
}