<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/signup','App\Http\Controllers\UserController@store')->middleware('guest');
Route::post('/login','App\Http\Controllers\UserController@login')->middleware('guest');

    
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/items', 'App\Http\Controllers\ItemController');
    Route::post('/users/{user}/addBalance','App\Http\Controllers\UserController@addBalance');
    Route::get('/users/{user}','App\Http\Controllers\UserController@show');

    
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
