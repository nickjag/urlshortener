<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Shortener Route

Route::get('/u/{code}', ['uses' => 'RedirectController@index'])->where('code', '[0-9A-Za-z]+');

// Test Routes

Route::group(['prefix' => 'tests'], function () {

	Route::get('store', ['uses' => 'TestsController@store']);

	Route::get('update', ['uses' => 'TestsController@update']);

});