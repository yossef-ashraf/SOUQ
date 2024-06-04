<?php

use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;
use Illuminate\Http\Request;
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

Route::get('/', function(Request $request) {
    // // $location = new Location();
    // $record = Location::get(request()->ip());
    // // $record = Location::get('112.68.1.1');
    // dd($record,$request->ip());
    return view('welcome');
});

