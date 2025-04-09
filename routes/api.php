<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlayerController;
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
Route::post('player/',[PlayerController::class, 'create']);
//READ
Route::get('/players',[PlayerController::class, 'index']);
Route::get('/player/{player}',[PlayerController::class, 'show']);
//UPDATE
Route::patch('player/{player}',[PlayerController::class, 'update']);
//DELETE
Route::delete('/player/{player}',[PlayerController::class, 'destroy']);


Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {});
