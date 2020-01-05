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

// Auth
Route::group(['prefix' => 'auth'], function () {
    Route::get('{provider}/redirect', 'SocialController@redirect'); // Link <A>
    Route::get('{provider}/callback', 'SocialController@callback'); // Callback
});