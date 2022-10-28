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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('jwt.auth')->group(function () {
    Route::apiResource('brand', 'App\Http\Controllers\BrandController');
    Route::apiResource('car-model', 'App\Http\Controllers\CarModelController');
    Route::apiResource('car', 'App\Http\Controllers\CarController');
    Route::apiResource('customer', 'App\Http\Controllers\CustomerController');
    Route::apiResource('lease', 'App\Http\Controllers\LeaseController');

    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});

Route::post('login', 'App\Http\Controllers\AuthController@login');


