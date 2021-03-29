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

Route::group(['prefix' => 'users'], function () {
    Route::get('', 'Api\UserController@index');
    Route::get('id/{id}', 'Api\UserController@show')->where('id', '[0-9]+');
    Route::get('extract/{id}', 'Api\UserController@extract')->where('id', '[0-9]+');
    Route::get('balance/{id}', 'Api\UserController@balance')->where('id', '[0-9]+');

    Route::post('', 'Api\UserController@store');
    Route::put('', 'Api\UserController@update');
    Route::delete('{id}', 'Api\UserController@destroy')->where('id', '[0-9]+');
});


Route::group(['prefix' => 'transactions'], function () {
    Route::get('', 'Api\TransactionController@index');
    Route::get('id/{id}', 'Api\TransactionController@show')->where('id', '[0-9]+');

    Route::post('', 'Api\TransactionController@store');
    Route::delete('{id}', 'Api\TransactionController@destroy')->where('id', '[0-9]+');
});