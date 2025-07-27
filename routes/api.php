<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\GameController;
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
Route::prefix('players')->group(function () {
    Route::post('/', [PlayerController::class, 'create'])->name('api.players.create');
    Route::get('/', [PlayerController::class, 'index'])->name('api.players.index');
    Route::get('/{player}', [PlayerController::class, 'show'])->name('api.players.show');
    Route::patch('/{player}', [PlayerController::class, 'update'])->name('api.players.update');
    Route::delete('/{player}', [PlayerController::class, 'destroy'])->name('api.players.destroy');
});
Route::prefix('games')->group(function(){

    Route::post('/',[GameController::class,'create'])->name('api.games.create');
    Route::get('/',[GameController::class,'index'])->name('api.games.index');
    Route::get('/{game}',[GameController::class,'show'])->name('api.games.show');
    Route::delete('/{game}',[GameController::class,'destroy'])->name('api.games.destroy');

});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);
Route::middleware('auth:api')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {});
