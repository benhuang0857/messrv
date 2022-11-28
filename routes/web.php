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
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/ajax/predicttime', 'AjaxController@PredictTime');
Route::get('/ajax/process_start', 'AjaxController@ProcessStart');
Route::get('/ajax/process_starthold', 'AjaxController@StartProcessHold');
Route::get('/ajax/process_endhold', 'AjaxController@EndProcessHold');
Route::get('/ajax/process_complete', 'AjaxController@ProcessComplete');

Route::get('/ajax/change_hold_reason', 'AjaxController@ChangeHoldReason');

Route::get('/line', 'LoginController@pageLine');
Route::get('/callback/login', 'LoginController@lineLoginCallBack');

Route::get('/api/orders', 'OrderController@all');
Route::get('/api/orders/get', 'OrderController@get');
Route::get('/api/order/update', 'OrderController@update');

Route::get('/api/login', 'LoginController@loginApi');