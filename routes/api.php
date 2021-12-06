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

Route::post('/signup', 'App\Http\Controllers\UserController@store')->middleware('guest');
Route::post('/login', 'App\Http\Controllers\UserController@login')->middleware('guest');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/store/my', 'App\Http\Controllers\StoreController@getMyStore');
    Route::get('/store/{store}', 'App\Http\Controllers\StoreController@getStore');
    Route::post('/items/{item}/buy', 'App\Http\Controllers\ItemController@buyItem');
    Route::apiResource('/items', 'App\Http\Controllers\ItemController');
    Route::post('/users/{user}/addBalance', 'App\Http\Controllers\UserController@addBalance');
    Route::get('/users/{user}', 'App\Http\Controllers\UserController@show');
    Route::get('/info/{user}', 'App\Http\Controllers\UserController@getInfo');
    Route::post('/findSearch', 'App\Http\Controllers\UserController@findSearch');
    Route::post('/items/{item}/add', 'App\Http\Controllers\ItemController@addItem');
    Route::get('/history', 'App\Http\Controllers\PaymentController@getPaymentHistory');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
