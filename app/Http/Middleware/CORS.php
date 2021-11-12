<?php

namespace App\Http\Middleware;

use Closure;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		// Preparing headers
		header("Access-Control-Allow-Origin: *"); // This will allow request from all origin
		
		$headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'X-Requested-With, Content-Type, X-Auth-Token, Origin'
        ];
	
	
		// Response to preflight request
		if($request->getMethod() == "OPTIONS") {
			return Response::make('OK', 200, $headers);
		}
		
		// Process the request further
		$response = $next($request);
		
		// Add headers in our response
        foreach($headers as $key => $value) {
            $response->header($key, $value);
		}
		
        return $response;
    }
}
