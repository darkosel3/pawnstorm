<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Game;
use App\Models\Friendship;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{
    //CREATE
    public function store(Request $request){
         $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:players',
        'username' => 'required|string|max:255|unique:players',
        'password' => 'required|min:6',
        'user_type_id' => 'required|integer',
    ]);
        $validatedData['password'] = bcrypt($validatedData['password']);
        $player = Player::create($validatedData);

         return response()->json([
        'status' => 'success',
        'data' => $player
         ], 201);;
    }

    public function index(){
         return response()->json([
        'status' => 'success',
        'data' => Player::all(),
    ], 200);
    }

    //READ
    public function show(Player $player)
    {

        if(!$player){
            return response()->json([
                'status' => 'error',
                'data' => 'Player not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $player,
        ], 200);

    }
    //UPDATE
    public function update(Request $request, Player $player){
        if(!$player){
            return response()->json([
                'status' => 'error',
                'message' =>'Player not found.'
            ], 404);
        }
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:players,email,' . $player->player_id . ',player_id',
            'username' => 'sometimes|string|max:255|unique:players,username,' . $player->player_id . ',player_id',
            'password' => 'sometimes|min:6',
            'user_type_id' => 'sometimes|integer',
        ]);

        $player->update($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Player updated successfully.',
            'data' => $player
        ],200);

    }


    //DELETE
    public function destroy(Player $player){
        $playerData = $player;
        $deleted = $player -> delete();
        return response()->json([
            'status' => $deleted ? 'success' : 'error',
            'message' => $deleted ? 'Player deleted successfully.' : 'Player deletion failed',
            'data' => $playerData], 
            200);
    }


    public function games(Player $player)
{
    $games = Game::where('white_player_id', $player->player_id)
        ->orWhere('black_player_id', $player->player_id)
        ->with(['player_white', 'player_black', 'gameType', 'result'])
        ->orderBy('played_at', 'desc')
        ->paginate(20);

    return response()->json([
        'status' => 'success',
        'data' => $games->items(),
        'pagination' => [
            'current_page' => $games->currentPage(),
            'total_pages' => $games->lastPage(),
            'total_games' => $games->total(),
            'per_page' => $games->perPage()
        ]
    ]);
}

/**
 * Get all friends for a specific player
 */
public function friends(Player $player)
{
    // Get accepted friendships where player is either sender or receiver
    $sentFriendships = Friendship::where('player1_id', $player->player_id)
        ->where('status', 'accepted')
        ->with('receiver')
        ->get();

    $receivedFriendships = Friendship::where('player2_id', $player->player_id)
        ->where('status', 'accepted')
        ->with('sender')
        ->get();

    // Extract friend data
    $friends = collect();
    
    foreach ($sentFriendships as $friendship) {
        $friends->push([
            'friendship_id' => $friendship->friendship_id,
            'friend' => $friendship->receiver,
            'created_at' => $friendship->created_at
        ]);
    }
    
    foreach ($receivedFriendships as $friendship) {
        $friends->push([
            'friendship_id' => $friendship->friendship_id,
            'friend' => $friendship->sender,
            'created_at' => $friendship->created_at
        ]);
    }

    return response()->json([
        'status' => 'success',
        'data' => $friends->sortByDesc('created_at')->values(),
        'total_friends' => $friends->count()
    ]);
}

/**
 * Get statistics for a specific player
 */
public function stats(Player $player)
{
    // Total games
    $totalGames = Game::where('white_player_id', $player->player_id)
        ->orWhere('black_player_id', $player->player_id)
        ->count();

    // Games as white
    $gamesAsWhite = Game::where('white_player_id', $player->player_id)->get();
    $gamesAsBlack = Game::where('black_player_id', $player->player_id)->get();

    // Calculate wins, losses, draws
    $wins = 0;
    $losses = 0;
    $draws = 0;

    // Count results as white player
    foreach ($gamesAsWhite as $game) {
        if ($game->result) {
            switch ($game->result->name) {
                case '1-0': // White wins
                    $wins++;
                    break;
                case '0-1': // Black wins (white loses)
                    $losses++;
                    break;
                case '1/2-1/2': // Draw
                    $draws++;
                    break;
            }
        }
    }

    // Count results as black player  
    foreach ($gamesAsBlack as $game) {
        if ($game->result) {
            switch ($game->result->name) {
                case '0-1': // Black wins
                    $wins++;
                    break;
                case '1-0': // White wins (black loses)
                    $losses++;
                    break;
                case '1/2-1/2': // Draw
                    $draws++;
                    break;
            }
        }
    }

    // Calculate win percentage
    $completedGames = $wins + $losses + $draws;
    $winPercentage = $completedGames > 0 ? round(($wins / $completedGames) * 100, 1) : 0;

    // Games by type
    $gamesByType = Game::where('white_player_id', $player->player_id)
        ->orWhere('black_player_id', $player->player_id)
        ->join('game_types', 'games.game_type_id', '=', 'game_types.game_type_id')
        ->selectRaw('game_types.naziv, COUNT(*) as count')
        ->groupBy('game_types.naziv', 'game_types.game_type_id')
        ->get();

    // Recent games (last 10)
    $recentGames = Game::where('white_player_id', $player->player_id)
        ->orWhere('black_player_id', $player->player_id)
        ->with(['player_white', 'player_black', 'result'])
        ->orderBy('played_at', 'desc')
        ->limit(10)
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => [
            'player' => $player,
            'overview' => [
                'total_games' => $totalGames,
                'wins' => $wins,
                'losses' => $losses,
                'draws' => $draws,
                'win_percentage' => $winPercentage,
                'games_as_white' => $gamesAsWhite->count(),
                'games_as_black' => $gamesAsBlack->count()
            ],
            'games_by_type' => $gamesByType,
            'recent_games' => $recentGames,
            'total_friends' => Friendship::where(function($query) use ($player) {
                $query->where('player1_id', $player->player_id)
                      ->orWhere('player2_id', $player->player_id);
            })->where('status', 'accepted')->count()
        ]
    ]);
}

}
