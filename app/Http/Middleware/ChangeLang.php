<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangeLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('lang') === 'en') {
            app()->setLocale('en');
        } elseif ($request->header('lang') === 'ar') {
            app()->setLocale('ar');
        } else {
            session(['getLocaleStop' => true]); // if lang is not 'en' or 'ar'
        }

        return $next($request);
    }
}
