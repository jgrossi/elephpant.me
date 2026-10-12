<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ElephpantController;
use App\Http\Controllers\Api\HerdController;
use App\Http\Controllers\Api\RankingController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\MessageController;

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

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/me/herd', [HerdController::class, 'me'])->name('api.me.herd.show');
    Route::put('/me/herd/{elephpant}', [AdoptionController::class, 'update'])->name('api.me.herd.update');
    Route::post('/messages', [MessageController::class, 'store'])->middleware('throttle:10,1')->name('api.messages.store');
});

Route::get('/herd/{username}', [HerdController::class, 'show'])->name('api.herds.show');

Route::get('/elephpants', [ElephpantController::class, 'index'])->name('api.elephpants.index');
Route::get('/elephpants/{elephpant}', [ElephpantController::class, 'show'])->name('api.elephpants.show');

Route::get('/ranking', [RankingController::class, 'index'])->name('api.ranking.index');

Route::get('/countries', [CountryController::class, 'index'])->name('api.countries.index');
