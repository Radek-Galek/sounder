<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\OpenAIController;

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

Route::post('/sanctum/token', TokenController::class);

Route::get('/spotify/track/{id}', [SpotifyController::class, 'getTrack']);
Route::get('/callback', 'AuthController@handleSpotifyCallback');
Route::get('/spotify/most-listened', [SpotifyController::class, 'getMostListenedTrack']);
Route::get('/spotify/account-details', [SpotifyController::class, 'getAccountDetails']);
Route::get('/spotify/most-listened-tracks', [SpotifyController::class, 'getMostListenedTracks']);
Route::post('/spotify/create-playlist', [SpotifyController::class, 'createPlaylist']);

Route::post('/spotify/token', [SpotifyController::class, 'exchangeCodeForToken']);
Route::post('/spotify/find-song', [SpotifyController::class, 'findSong']);


Route::post('/openai/similar-songs', [OpenAIController::class, 'generateSimilarSongs']);
