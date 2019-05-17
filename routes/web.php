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

Auth::routes();

//Dingo oauth2 验证
Route::get('/auth', 'Auth\LoginController@oauth');
Route::get('/auth/callback', 'Auth\LoginController@callback');
Route::get('/home', 'HomeController@index')->name('home');

