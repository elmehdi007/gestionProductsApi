<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class GuestLangMiddleware
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
        $acceptlanguage = Str::of($request->header('accept-language',"en-US,en;q=0.9,fr;q=0.8"))->split('/;/')[0]??'';
        $acceptlanguage = Str::of($acceptlanguage)->split('/,/');
        $acceptlanguage = (isset($acceptlanguage[1]))?$acceptlanguage[1]:'en';
        App::setLocale($acceptlanguage);
        
        return $next($request);
    }
}