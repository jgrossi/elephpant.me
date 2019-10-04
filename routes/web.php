<?php

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/elephpants', 'ElephpantController@index')->name('elephpants.index');
Route::get('/herd/{username}', 'HerdController@show')->name('herds.show');
Route::get('/ranking', 'RankingController@index')->name('rankings.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/my-herd', 'HerdController@edit')->name('herds.edit');
    Route::get('/my-herd/stats', 'HerdController@stats')->name('herds.stats');
    Route::get('/trade', 'TradeController@index')->name('trades.index');
    Route::put('/adoption/{elephpant}', 'AdoptionController@update')->name('adoptions.update');
});
