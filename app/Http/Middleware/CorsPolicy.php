<?php

namespace App\Http\Middleware;
use Closure;

class CorsPolicy
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
		header("Access-Control-Allow-Origin: *");
        $headers = [    
            // 'Access-Control-Allow-Origin: *' => '',
            'Access-Control-Allow-Methods' => 'GET,POST',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, Origin, Authorization',
        ];
        $response = $next($request);
        foreach ($headers as $key => $value) {
            if(get_class($response) !== "Symfony\Component\HttpFoundation\BinaryFileResponse" ){
                $response->header($key, $value);
            }
        }
        return $response;
    }
}