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
Route::get('/ajax/process_hold', 'AjaxController@ProcessHold');
Route::get('/ajax/process_complete', 'AjaxController@ProcessComplete');