<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Analyze\AnalyzeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => ['ChangeLang' ] ], function(){
    Route::prefix('analyze')->controller(AnalyzeController::class)->group(function () {
        Route::get('/age', 'age');
        Route::get('/gender', 'gender');

        Route::get('/best/categories', 'best_sell_categories');

        Route::get('/best/delivered/order', 'best_sell_delivered_order');
        Route::get('/best/addresses/order', 'best_sell_order_addresses');
        Route::get('/best/products/order/basetime', 'best_sell_products_order_base_time');

        Route::get('/best-bad/products', 'best_and_bad_products');
        Route::get('/best-views/products', 'best_views_products');

        Route::get('/total_amount', 'total_amount');
    });

});


