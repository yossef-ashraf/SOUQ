<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\Cartcontroller;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\Ordercontroller;
use App\Http\Controllers\Productcontroller;




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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/login',[Authcontroller::class,'login']);
Route::post('/register',[Authcontroller::class,'register']);
Route::get('/productsForUser',[Productcontroller::class,'productsForUser']);

Route::group(['middleware' => 'jwtauth'], function(){

//
Route::get('/auth',[Authcontroller::class,'auth']);

//
Route::get('/users',[Usercontroller::class,'users']);
Route::post('/updateuser',[Usercontroller::class,'updateuser']);
Route::post('/updateuserByAdmin',[Usercontroller::class,'updateuserByAdmin']);
Route::post('/deleteuser',[Usercontroller::class,'deleteuser']);

//
Route::get('/products',[Productcontroller::class,'products']);
Route::post('/addproduct',[Productcontroller::class,'addproduct']);
Route::post('/deleteproduct',[Productcontroller::class,'deleteproduct']);
Route::post('/updateproductByAdmin',[Productcontroller::class,'updateproductByAdmin']);

//
Route::get('/userCart',[Cartcontroller::class,'userCart']);
Route::post('/addToCart',[Cartcontroller::class,'addToCart']);
Route::post('/UpdateCart',[Cartcontroller::class,'UpdateCart']);
Route::post('/deleteFromCart',[Cartcontroller::class,'deleteFromCart']);

//
Route::get('/allOrderForAdmin',[Ordercontroller::class,'allOrderForAdmin']);
Route::get('/allOrderForUser',[Ordercontroller::class,'allOrderForUser']);
Route::get('/OrderCheckout',[Ordercontroller::class,'OrderCheckout']);
Route::get('/OrdersDone',[Ordercontroller::class,'OrdersDone']);
Route::get('/addOrder',[Ordercontroller::class,'addOrder']);
Route::get('/chekout',[Ordercontroller::class,'chekout']);
Route::post('/addToOrder',[Ordercontroller::class,'addToOrder']);
Route::post('/DoneaddOrder',[Ordercontroller::class,'DoneaddOrder']);
Route::post('/deleteFromOrder',[Ordercontroller::class,'deleteFromOrder']);

});
