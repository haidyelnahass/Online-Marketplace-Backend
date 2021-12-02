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

Route::post('/signup', 'App\Http\Controllers\UserController@store');
Route::post('/login', 'App\Http\Controllers\UserController@login');

Route::middleware('auth:sanctum')->post('/items', 'App\Http\Controllers\ItemController@store');
Route::middleware('auth:sanctum')->put('/items/{item}', 'App\Http\Controllers\ItemController@update');
Route::middleware('auth:sanctum')->delete('/items/{item}', 'App\Http\Controllers\ItemController@destroy');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
