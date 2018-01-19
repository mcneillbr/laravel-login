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
Auth::routes();
Route::get('/', 'HomeController@index');
Route::get('/usr', 'HomeController@home');
Route::get('/api/v1/users', 'UserController@index');
Route::match(['POST'],'/api/v1/user/create', 'UserController@store');
Route::put('/api/v1/user/{id}', 'UserController@update');
Route::delete('/api/v1/user/{id}/delete', 'UserController@destroy');

Route::post('/api/v1/login', 'LoginBasicController@login')->name('usr.login');
Route::get('/api/v1/logout', 'LoginBasicController@logout')->name('usr.logout');



Route::get('/home', 'HomeController@index')->name('home');
