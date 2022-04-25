<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\Authcontroller;

use App\Http\Controllers\Departmentcontroller;
use App\Http\Controllers\Categorycontroller;
use App\Http\Controllers\Productcontroller;

use App\Http\Controllers\Cartcontroller;
use App\Http\Controllers\Ordercontroller;

use App\Http\Controllers\Commentcontroller;
use App\Http\Controllers\Complaintcontroller;
use App\Http\Controllers\Title_complaintcontroller;





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
Route::get('/departments',[Departmentcontroller::class,'departments']);
Route::post('/categorys',[Categorycontroller::class,'categorys']);
Route::post('/productsForUser',[Productcontroller::class,'productsForUser']);
Route::post('/productComments',[Commentcontroller::class,'productComments']);

Route::group(['middleware' => 'jwtauth'], function(){

//
Route::get('/auth',[Authcontroller::class,'auth']);

//
Route::get('/users',[Usercontroller::class,'users']);
Route::post('/updateuser',[Usercontroller::class,'updateuser']);
Route::post('/updateuserByAdmin',[Usercontroller::class,'updateuserByAdmin']);
Route::post('/deleteuser',[Usercontroller::class,'deleteuser']);

//
Route::get('/departmentForAdmin',[Departmentcontroller::class,'departmentForAdmin']);
Route::post('/adddepartment',[Departmentcontroller::class,'adddepartment']);
Route::post('/deletedepartment',[Departmentcontroller::class,'deletedepartment']);
Route::post('/updatedepartmentByAdmin',[Departmentcontroller::class,'updatedepartmentByAdmin']);

//
Route::get('/categoryForAdmin',[Categorycontroller::class,'categoryForAdmin']);
Route::post('/addcategory',[Categorycontroller::class,'addcategory']);
Route::post('/deletecategory',[Categorycontroller::class,'deletecategory']);
Route::post('/updatecategoryByAdmin',[Categorycontroller::class,'updatecategoryByAdmin']);

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
Route::post('/chekout',[Ordercontroller::class,'chekout']);
Route::get('/allOrder',[Ordercontroller::class,'allOrder']);
Route::post('/OrdersDone',[Ordercontroller::class,'OrdersDone']);

//
Route::get('/Comments',[Commentcontroller::class,'Comments']);
Route::get('/userComments',[Commentcontroller::class,'userComments']);
Route::post('/addToComment',[Commentcontroller::class,'addToComment']);
Route::post('/UpdateComment',[Commentcontroller::class,'UpdateComment']);
Route::post('/deleteFromComment',[Commentcontroller::class,'deleteFromComment']);
Route::post('/deleteFromCommentByAdmin',[Commentcontroller::class,'deleteFromCommentByAdmin']);

//
Route::get('/Complaints',[Complaintcontroller::class,'Complaints']);
Route::post('/updateComplaint',[Complaintcontroller::class,'updateComplaint']);
Route::post('/addToComplaint',[Complaintcontroller::class,'addToComplaint']);
Route::post('/deleteFromComplaint',[Complaintcontroller::class,'deleteFromComplaint']);

Route::get('/Title_complaints',[Title_complaintcontroller::class,'Title_complaints']);
Route::post('/addToTitle_complaint',[Title_complaintcontroller::class,'addToTitle_complaint']);
Route::post('/updateTitle_complaintByAdmin',[Title_complaintcontroller::class,'updateTitle_complaintByAdmin']);
Route::post('/deleteFromTitle_complaintByAdmin',[Title_complaintcontroller::class,'deleteFromTitle_complaintByAdmin']);

});


