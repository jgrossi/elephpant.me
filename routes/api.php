<?php

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ElephpantController;
use App\Http\Controllers\Api\HerdController;
use App\Http\Controllers\Api\RankingController;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// etag answers a repeated read with 304 and no body. Endpoints that also know when
// their subject last changed set Last-Modified themselves, which the same middleware
// honours, so a caller can use either header.
Route::middleware('cache.headers:etag')->group(function (): void {
    Route::get('/herd/{username}', [HerdController::class, 'show'])->name('api.herds.show');

    Route::get('/elephpants', [ElephpantController::class, 'index'])->name('api.elephpants.index');
    Route::get('/elephpants/{elephpant}', [ElephpantController::class, 'show'])->name('api.elephpants.show');

    Route::get('/ranking', [RankingController::class, 'index'])->name('api.ranking.index');

    Route::get('/countries', [CountryController::class, 'index'])->name('api.countries.index');
});
