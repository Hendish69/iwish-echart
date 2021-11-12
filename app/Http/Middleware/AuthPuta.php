<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
// use App\ApiResponse;
use Response;
use Illuminate\Support\Facades\Auth;

class AuthPuta
{
	public function handle($request, Closure $next, $guard = null)
	{
		$token = $request->bearerToken();  
		if (null === $token ){
			// return ApiResponse::error('Provide their API token as a Bearer token in the Authorization header');
			return Response::json([
                    'error'     => 500,
                    'message'   => 'Provide their API token as a Bearer token in the Authorization header.',
                ], 500);
		}

		$user = User::where('api_token', $token)->first(); 
		if (null === $user) {
			// return ApiResponse::error('unauthorized');
			 return Response::json([
                    'error'     => 401,
                    'message'   => 'Invalid or expired refresh token',
                ], 401);
            
		}else{ 
			Auth::setUser($user); 
			return $next($request);	
		}
	}
}