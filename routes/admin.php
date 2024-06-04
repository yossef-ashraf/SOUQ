<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\OrderReturnController;
use App\Http\Controllers\Admin\ProductSizeController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::prefix('admin')->group(function () {
    // Auth cycle
    Route::crud('users',UserController::class , true , true , true);
    Route::crud('role',RoleController::class , true , true );
    // Product cycle
    Route::showAuth('contact',ContactController::class);
    Route::post('contact/destroy',[ContactController::class,'destroy']);
    Route::crud('slider',SliderController::class , true , true );
    Route::crud('blog',BlogController::class, true , true , true);
    Route::crud('brand',BrandController::class, true , true , true);
    Route::crud('category',CategoryController::class, true , true , true);
    Route::crud('product',ProductController::class, true , true , true);
    Route::operationAuth('product/detail',ProductDetailController::class, true );
    Route::operationAuth('product/size',ProductSizeController::class, true );
    Route::operationAuth('product/image',ProductImageController::class );
    // order cycle
    Route::crud('shipping',ShippingController::class , true , true );
    Route::crud('promo',PromoController::class, true , true , true);
    Route::crud('order',OrderController::class, true , true , true);
    Route::crud('order/return',OrderReturnController::class, true , true , true);

});


