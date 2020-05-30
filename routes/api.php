<?php

use App\Http\Controllers\API\MovieController;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/movieList', [MovieController::class, 'index'])->name('movieList');
Route::get('/movieLink', [MovieController::class, 'getm3u8ByLink'])->name('movieLink');
Route::get('/episodeList', [MovieController::class, 'episodeList'])->name('episodeList');
Route::get('/search', [MovieController::class, 'search'])->name('search');
