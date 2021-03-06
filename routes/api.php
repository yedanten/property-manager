<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('/user/login', 'UserController@login');
Route::middleware('auth:api')->group(function () {
    Route::post('/user', 'UserController@add');
    Route::get('/user/current', 'UserController@current');
    Route::get('/user/search', 'UserController@find');
    Route::patch('/user/password', 'UserController@resetPassword');
    Route::resource('/apartment', 'ApartmentController')->except(['edit', 'create']);
    Route::resource('/workorder', 'WorkorderController')->except(['edit', 'create', 'update']);
    Route::resource('/bill', 'BillController')->except(['edit', 'create', 'destroy', 'update']);
    Route::get('/system', 'SystemController@index');
    Route::patch('/system', 'SystemController@update');
    Route::get('/system/backdb', 'SystemController@backupdb');
});