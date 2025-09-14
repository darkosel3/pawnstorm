<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\FriendController;
use App\Models\Player;
use App\Models\GameType;
use App\Models\Result;


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

//CRUD Player
//CREATE
Route::bind('player', function ($value) {
    return Player::where('player_id', $value)->firstOrFail();
});

Route::bind('game_type', function ($value) {
    return \App\Models\GameType::where('game_type_id', $value)->firstOrFail();
});

Route::bind('result', function ($value) {
    return \App\Models\Result::where('result_id', $value)->firstOrFail();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Players (ograničiti pristup)
    Route::apiResource('players', PlayerController::class);
    
    // Games - sada zaštićeno
    Route::apiResource('games', GameController::class);
    Route::get('/players/{player}/games', [PlayerController::class, 'games']); // Get player's games
    Route::get('/players/{player}/friends', [PlayerController::class, 'friends']); // Get player's friends
    Route::get('/players/{player}/stats', [PlayerController::class, 'stats']); // Get player statistics

    Route::post('/games/{game}/moves', [GameController::class, 'saveMove']);
    
    Route::apiResource('game', GameController::class);

    // Friends sistem - DODATI
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendController::class, 'index']);
        Route::get('/pending', [FriendController::class, 'pending']);
        Route::post('/request', [FriendController::class, 'sendRequest']);
        Route::put('/{id}/accept', [FriendController::class, 'accept']);
        Route::put('/{id}/decline', [FriendController::class, 'decline']);
    });
    
    // Game types & Results
    Route::get('/game-types', function() {
        return \App\Models\GameType::all();
    });

    Route::get('/results', function() {
        return \App\Models\Result::all();
    });
});

