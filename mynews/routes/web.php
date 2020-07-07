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

Route::group(['prefix' => 'admin'], function() {
    Route::get('news/create', 'Admin\NewsController@add')->middleware('auth');
    Route::post('news/create', 'Admin\NewsController@create')->middleware('auth');
    Route::get('news', 'Admin\NewsController@index')->middleware('auth'); // 追記
});

Route::group(['prefix' => 'admin'], function() {
  Route::get('profile/create', 'Admin\ProfileController@add');
  Route::post('profile/create', 'Admin\profileController@create'); # 追記
});

Route::group(['prefix' => 'admin'], function() {
  Route::get('profile/edit', 'Admin\ProfileController@edit');
  Route::post('profile/create', 'Admin\profileController@edit'); # 追記
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');