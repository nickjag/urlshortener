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

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
*/

// Index - List all urls

Route::get('/urls', ['uses' => 'InterfaceController@index']);

// Show - List all urls by user

Route::get('/urls/{user}', ['uses' => 'InterfaceController@show'])->where('user', '[0-9]+');

// Store - Save target and produce short url

Route::post('/urls', ['uses' => 'InterfaceController@store']);

// Update - Modify target url by short url + user + device type

Route::put('/urls', ['uses' => 'InterfaceController@update']);
