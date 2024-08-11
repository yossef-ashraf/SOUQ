<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\FavController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\RateController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\BrandController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\SliderController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Controllers\Web\AddressController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ShippingController;

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

// Auth cycle
Route::group(['middleware' => ['ChangeLang' ] ], function(){
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register','register');
        Route::post('/login','login');
        Route::post('/user/forget/password', 'forget_password');
        Route::post('/user/change/otp', 'check_otp');
        Route::post('/user/change/forget/password', 'check_forget_password');
        Route::get('/verify', 'verifying');
        Route::get('email/verify/{id}', 'verifys')->name('verification.verify');
        Route::get('email/resend', 'resend')->name('verification.resend');
    });
        Route::group(['middleware' => ['jwtauth' ] ], function(){
            Route::controller(AuthController::class)->group(function () {
                Route::get('/logout', 'logout');
                Route::get('/me', 'me');
                Route::get('/refresh', 'refresh');
                Route::post('/user/update', 'update');
                Route::post('/user/change/password', 'change_password');

            });

            Route::prefix('wallet')->controller(WalletController::class)->group(function () {
                Route::get('/show', 'show');
                Route::get('/showOne', 'showOne');
                Route::post('/update', 'update');
            });

        });

});

 // product cycle
Route::show('slider',SliderController::class);
Route::show('category',CategoryController::class);
Route::show('brand',BrandController::class);
Route::show('blog',BlogController::class);
Route::crud('fav',FavController::class , true , true );
Route::crud('rate',RateController::class , true , true );
Route::crud('comment',CommentController::class , true , true );
Route::show('product',ProductController::class);

// order cycle
Route::show('shipping',ShippingController::class);
Route::crud('address',AddressController::class , true , true );
Route::crud('cart',CartController::class , true , true );
Route::crud('order',OrderController::class , true , true );
Route::crud('order/return',OrderController::class , true , true );

// spicfic url
Route::group(['middleware' => ['ChangeLang' ] ], function(){

    Route::prefix('category')->controller(CategoryController::class)->group(function () {
        Route::get('/showNew', 'showNew');
        Route::get('/showOffer', 'showOffer');
    });

    Route::prefix('brand')->controller(BrandController::class)->group(function () {
        Route::get('/showNew', 'showNew');
        Route::get('/showOffer', 'showOffer');
    });

    Route::prefix('product')->controller(ProductController::class)->group(function () {
        Route::get('/showNew', 'showNew');
        Route::get('/showOffer', 'showOffer');
        Route::post('/showCategory', 'showCategory');
        Route::post('/showBrand', 'showBrand');
        Route::post('/search', 'search');
    });
});



require __DIR__.'/general.php';
require __DIR__.'/admin.php';
require __DIR__.'/crm.php';
