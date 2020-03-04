<?php

Auth::routes();

Route::redirect('/home', '/');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/species', 'ElephpantController@index')->name('elephpants.index');
Route::get('/herd/{username}', 'HerdController@show')->name('herds.show');
Route::get('/ranking', 'RankingController@index')->name('rankings.index');
Route::get('/user/{username}', 'HerdController@show')->name('heard.show');
Route::get('/statistics', 'StatisticsController@index')->name('statistics.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/my-herd', 'HerdController@edit')->name('herds.edit');
    Route::get('/my-herd/stats', 'HerdController@stats')->name('herds.stats');
    Route::get('/trade', 'TradeController@index')->name('trades.index');
    Route::put('/adoption/{elephpant}', 'AdoptionController@update')->name('adoptions.update');
    Route::get('/photo/create', 'PhotoController@create')->name('photos.create');
    Route::post('/photo', 'PhotoController@store')->name('photos.store');
    Route::post('/message', 'MessageController@store')->name('messages.store');
    Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
});
