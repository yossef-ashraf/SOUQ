<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

//code

class ChangeLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        app()->setLocale('en');

        if($request->header('lang') == 'ar' )
            app()->setLocale('ar');
        elseif ($request->header('lang')=='du')
             app()->setLocale('du');


        return $next($request);
    }


}
