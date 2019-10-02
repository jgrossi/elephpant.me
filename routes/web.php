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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/elephpants', 'ElephpantController@index')->name('elephpants.index');
Route::get('/herd/{username}', 'HerdController@show')->name('herds.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/my-herd', 'HerdController@edit')->name('herds.edit');
});
