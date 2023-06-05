<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;

//code

class AuthAdmin
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd(Auth::user()->auth == "admin");
        try {
            if ( Auth::user()->auth == "admin") {
                return $next($request);
            }
        } catch (\Exception $th) {
            return $this->apiResponse(403,"you not admin");
        }



        return $this->apiResponse(403,"you not admin");
    }
}
