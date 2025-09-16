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
use Illuminate\Support\Facades\DB;


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




Route::apiResource('players', PlayerController::class);
Route::apiResource('games', GameController::class);
Route::apiResource('friendships', FriendController::class); // dodaj ovo

Route::get('/players/{player}/games', [PlayerController::class, 'games']); // 
Route::get('/players/{player}/friends', [PlayerController::class, 'friends']); 
Route::get('/players/{player}/stats', [PlayerController::class, 'stats']); // 

Route::post('/games/{game}/moves', [GameController::class, 'saveMove']);
Route::get('/users/search', function (Request $request) {
    $query = $request->get('q');
    $currentUserId = Auth::id();
    
    if (!$query) {
        return response()->json([]);
    }
    
    $players = DB::table('players')
        ->where('username', 'like', '%' . $query . '%')
        ->where('player_id', '!=', $currentUserId) // ne prikazuj sebe
        ->select(
            'player_id as id',
            'username',
            'name', 
            DB::raw('COALESCE(rating, 0) as rating'),
            'updated_at as lastSeen'
        )
        ->get()
        ->map(function ($player) use ($currentUserId) {
            // Proverava da li je već prijatelj
            $isFriend = DB::table('friendships')
                ->where(function($query) use ($currentUserId, $player) {
                    $query->where('player1_id', $currentUserId)
                          ->where('player2_id', $player->id);
                })->orWhere(function($query) use ($currentUserId, $player) {
                    $query->where('player1_id', $player->id)
                          ->where('player2_id', $currentUserId);
                })
                ->where('status', 'accepted')
                ->exists();

            // Proverava da li je zahtev već poslat
            $requestSent = DB::table('friendships')
                ->where('player1_id', $currentUserId)
                ->where('player2_id', $player->id)
                ->where('status', 'pending')
                ->exists();

            return [
                'id' => $player->id,
                'username' => $player->username,
                'name' => $player->name,
                'rating' => $player->rating,
                'lastSeen' => $player->lastSeen,
                'isOnline' => false,
                'isFriend' => $isFriend,
                'requestSent' => $requestSent
            ];
        });
    
    return response()->json($players);
});

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/my-games', [GameController::class, 'myGames']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);
    // Friends sistem - DODATI
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendController::class, 'index']);
        Route::get('/pending', [FriendController::class, 'pending']);
        Route::post('/request', [FriendController::class, 'sendRequest']);
        Route::put('/{id}/accept', [FriendController::class, 'accept']);
        Route::post('/friends/cancel', [FriendController::class, 'cancel']);
        Route::delete('/friends/{id}', [FriendController::class, 'remove']);


    });
    

    // Game types & Results
    Route::get('/game-types', function() {
        return \App\Models\GameType::all();
    });

    Route::get('/results', function() {
        return \App\Models\Result::all();
    });
});

