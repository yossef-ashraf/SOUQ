<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::macro('crud',function($prefix ,$class , $showAuth = false , $operationAuth = false ,$trashed = false ){
            if ($showAuth)
                Route::showAuth($prefix ,$class ,$trashed);
                else
                Route::show($prefix ,$class ,$trashed);

                if ($operationAuth)
                Route::operationAuth($prefix ,$class ,$trashed);
                else
                Route::operation($prefix ,$class ,$trashed);


        });

        Route::macro('show',function($prefix ,$class ,$trashed = false){
            Route::prefix($prefix)->controller($class)->group(function () use($trashed) {
                Route::group(['middleware' => ['ChangeLang' ] ], function() use($trashed){
                Route::get('show', 'show'); // without Trashed
                Route::post('one', 'showOne');
                if ($trashed) {
                    Route::get('all', 'showAll');// with Trashed
                    Route::get('trashed', 'showTrashed'); // Trashed only
                }
            });
            });
        });

        Route::macro('showAuth',function($prefix ,$class ,$trashed = false){
            Route::prefix($prefix)->controller($class)->group(function () use($trashed) {
                Route::group(['middleware' => ['jwtauth' ] ], function() use($trashed){
                    Route::group(['middleware' => ['ChangeLang' ] ], function() use($trashed){
                    Route::get('show', 'show'); // without Trashed
                    Route::post('one', 'showOne');
                    if ($trashed) {
                        Route::get('all', 'showAll');// with Trashed
                        Route::get('trashed', 'showTrashed'); // Trashed only
                    }
              });
            });
        });
        });

        Route::macro('operation',function($prefix ,$class ,$trashed = false){
            Route::prefix($prefix)->controller($class)->group(function () use($trashed) {
                Route::group(['middleware' => ['ChangeLang' ] ], function() use($trashed){
                Route::post('store', 'store');
                Route::post('update', 'update');
                Route::post('destroy', 'destroy');
                if ($trashed) {
                    Route::post('restore', 'restore');// when Trashed
                    Route::post('forceDelete', 'forceDelete');// when Trashed
                }
            });
        });
        });

        Route::macro('operationAuth',function($prefix ,$class ,$trashed = false){
            Route::prefix($prefix)->controller($class)->group(function () use($trashed) {
                Route::group(['middleware' => ['jwtauth' ,'ChangeLang'] ], function() use($trashed){

                        Route::post('store', 'store');
                        Route::post('update', 'update');
                        Route::post('destroy', 'destroy');
                        if ($trashed) {
                            Route::post('restore','restore');
                            Route::post('forceDelete','forceDelete');
                        }

            });
        });
        });

    }
}
