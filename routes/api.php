<?php

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

// user (complainant) route
Route::post('/guest/complains/create', 'RecordController@store');

// auth routes
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout')->middleware('auth:sanctum');

// authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', 'UserController@index');
    Route::post('/user/account/{account}', 'UserAccountController@update');
    Route::apiResource('records', 'RecordController')->except(['store']);
    Route::get('/exports/records', 'ExportController@records');
});
