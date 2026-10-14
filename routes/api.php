<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ElephpantController;
use App\Http\Controllers\Api\HerdController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RankingController;
use App\Http\Controllers\Api\TokenController;

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

Route::post('/tokens', [TokenController::class, 'store'])->middleware('throttle:5,1')->name('api.tokens.store');

Route::middleware(['throttle:api', 'auth:sanctum'])->group(function (): void {
    Route::get('/me/herd', [HerdController::class, 'me'])->name('api.me.herd.show');
    Route::put('/me/herd/{elephpant}', [AdoptionController::class, 'update'])->name('api.me.herd.update');
    Route::get('/messages', [MessageController::class, 'index'])->name('api.messages.index');
    Route::get('/messages/{username}', [MessageController::class, 'show'])->name('api.messages.show');
    Route::post('/messages/{username}', [MessageController::class, 'store'])->middleware('throttle:10,1')->name('api.messages.store');
});

// etag answers a repeated read with 304 and no body. Endpoints that also know when
// their subject last changed set Last-Modified themselves, which the same middleware
// honours, so a caller can use either header.
Route::middleware(['throttle:api', 'cache.headers:etag'])->group(function (): void {
    Route::get('/herd/{username}', [HerdController::class, 'show'])->name('api.herds.show');

    Route::get('/elephpants', [ElephpantController::class, 'index'])->name('api.elephpants.index');
    Route::get('/elephpants/{elephpant}', [ElephpantController::class, 'show'])->name('api.elephpants.show');

    Route::get('/ranking', [RankingController::class, 'index'])->name('api.ranking.index');

    Route::get('/countries', [CountryController::class, 'index'])->name('api.countries.index');
});
