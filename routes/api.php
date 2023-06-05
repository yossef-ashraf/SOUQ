<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ShippingController;
use Illuminate\Routing\Route as RoutingRoute;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Auth Routes
Route::group(['middleware'=>'Lang'],function(){
    // Route::get('/CategoryController',[CategoryController::class,'Categories']);

        Route::post('/login',[AuthController::class,'login']);
        Route::post('/register',[AuthController::class,'register']);
        Route::post('/forget_password',[AuthController::class,'forget_password']);
        Route::post('/check_otp',[AuthController::class,'check_otp']);
        Route::post('/check_forget_password',[AuthController::class,'check_forget_password']);

        // Data web Site Routes
        Route::get('/Categories',[CategoryController::class,'Categories']);
        Route::post('/Category',[CategoryController::class,'Category']);
        Route::post('/Categories/Search',[CategoryController::class,'Search']);

        Route::get('/Products',[ProductController::class,'Products']);
        Route::get('/Product/{id}',[ProductController::class,'Product']);
        Route::get('/Products/new',[ProductController::class,'NewProducts']);
        Route::post('/Products/Search',[ProductController::class,'Search']);
        Route::post('/ProductByCategory',[ProductController::class,'ProductByCategory']);

        Route::post('/AddContact',[ContactController::class,'AddContact']);

        Route::get('/Shipping',[ShippingController::class,'Shipping']);

        Route::post('/Discount',[DiscountController::class,'Discount']);

     Route::group(['middleware'=>'Jwt'],function(){

        Route::get('/logout',[AuthController::class,'logout']);
        Route::get('/auth',[AuthController::class,'auth']);
        Route::post('/UpdateUser',[AuthController::class,'UpdateUser']);

        Route::get('/Cart',[CartController::class,'Cart']);
        Route::post('/AddCart',[CartController::class,'AddCart']);
        Route::post('/UpdateCart',[CartController::class,'UpdateCart']);
        Route::post('/DeleteCart',[CartController::class,'DeleteCart']);

        Route::get('/Orders',[OrderController::class,'Orders']);
        Route::post('/CheckOut',[OrderController::class,'CheckOut']);
        Route::post('/DiscountOrderUser',[OrderController::class,'DiscountOrderUser']);
        Route::post('/DeleteOrderUser',[OrderController::class,'DeleteOrderUser']);

        Route::post('/Comments',[CommentController::class,'Comments']);
        Route::get('/MyComments',[CommentController::class,'MyComments']);
        Route::post('/AddComment',[CommentController::class,'AddComment']);
        Route::post('/DeleteComment/User',[CommentController::class,'DeleteCommentByUser']);

 });
// admin Routes
        Route::group(['middleware'=>'Admin'],function(){

            // Auth Route
            Route::get('/Users',[AuthController::class,'User']);
            Route::post('/updateAuthUser',[AuthController::class,'updateAuthUser']);
            Route::post('/DeleteUser',[AuthController::class,'DeleteUser']);

            // Category Route
            Route::post('/AddCategory',[CategoryController::class,'AddCategory']);
            Route::post('/UpdateCategory',[CategoryController::class,'UpdateCategory']);
            Route::post('/DeleteCategory',[CategoryController::class,'DeleteCategory']);

            // Product Route
            Route::get('/AdminProduct',[ProductController::class,'AdminProduct']);
            Route::post('/AddProduct',[ProductController::class,'AddProduct']);
            Route::post('/UpdateProduct',[ProductController::class,'UpdateProduct']);
            Route::post('/DeleteProduct',[ProductController::class,'DeleteProduct']);

            // ProductSize Route
            Route::get('/AdminProductSize',[ProductController::class,'AdminProductSize']);
            Route::post('/AddProductSize',[ProductController::class,'AddProductSize']);
            Route::post('/UpdateProductSize',[ProductController::class,'UpdateProductSize']);
            Route::post('/DeleteProductSize',[ProductController::class,'DeleteProductSize']);

            // Shipping Route
            Route::get('/AdminShipping',[ShippingController::class,'AdminShipping']);
            Route::post('/AddShipping',[ShippingController::class,'AddShipping']);
            Route::post('/UpdateShipping',[ShippingController::class,'UpdateShipping']);
            Route::post('/DeleteShipping',[ShippingController::class,'DeleteShipping']);

            // Discount Route
            Route::get('/AdminDiscount',[DiscountController::class,'AdminDiscount']);
            Route::post('/AddDiscount',[DiscountController::class,'AddDiscount']);
            Route::post('/UpdateDiscount',[DiscountController::class,'UpdateDiscount']);
            Route::post('/DeleteDiscount',[DiscountController::class,'DeleteDiscount']);

            // Order Route
            Route::get('/AdminOrder',[OrderController::class,'AdminOrder']);
            Route::post('/OrderState',[OrderController::class,'OrderState']);
            Route::post('/DeleteOrder',[OrderController::class,'DeleteOrder']);

            // Comment Route
            Route::get('/AdminComment',[CommentController::class,'AdminComment']);
            Route::post('/CommentState',[CommentController::class,'CommentState']);
            Route::post('/DeleteComment/Admin',[CommentController::class,'DeleteCommentByAdmin']);

            // Contact Route
            Route::get('/AdminContact',[ContactController::class,'ContactAdmin']);
            Route::post('/DeleteContact',[ContactController::class,'DeleteContact']);

        });

    });

