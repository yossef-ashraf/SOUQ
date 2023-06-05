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
        // $pp=isset($request->header('lang'));
        // app()->setLocale('es');
        // app()->setLocale('ar');
        app()->setLocale('en');

        // // dd(app()->getLocale(),$request->header('lang'));
        if($request->header('lang') == 'ar' )
            app()->setLocale('ar');
            elseif($request->header('lang') == 'admin' )
            app()->setLocale('admin');

        return $next($request);
    }


}
