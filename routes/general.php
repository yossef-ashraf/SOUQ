<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\GeneralApiController;


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
    Route::prefix('general')->controller(GeneralApiController::class)->group(function () {
        Route::get('/showAllCategory', 'showAllCategory');
        Route::get('/showAllBrand', 'showAllBrand');
        Route::post('/showAllProduct', 'showAllProduct');

        Route::get('/showAllCategoryTrashed', 'showAllCategoryTrashed')->middleware('jwtauth');
        Route::get('/showAllBrandTrashed', 'showAllBrandTrashed')->middleware('jwtauth');
        Route::post('/showAllProductTrashed', 'showAllProductTrashed')->middleware('jwtauth');

        Route::post('contact/store', 'contactStore');
        Route::post('promo/check', 'promoCheck');
    });

});


