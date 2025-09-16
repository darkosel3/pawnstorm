<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;

use App\Models\Game;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;


class GameController extends Controller
{
    public function index(){

    $games = Game::with(['player_white', 'player_black', 'result'])
        ->get()
        ->map(function($game) {
            return [
                'id' => $game->game_id,
                'white_player' => $game->player_white->username ?? 'Unknown',
                'black_player' => $game->player_black->username ?? 'Unknown', 
                'status' => $game->result ? 'completed' : 'active',
                'duration' => $game->duration ?? null,
                'created_at' => $game->created_at
            ];
        });
        
    return response()->json($games);
}
    public function show(Game $game){
        return response()->json([
            'status' => 'success',
            'data'=> $game
        ], 200);

    }
    public function store(Request $request)
    {
        $game = Game::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $game,
        ], 201); // eksplicitno postavi 201
    }

    public function destroy(Game $game){

        $deleted = $game -> delete();
        return response()->json([
            'status' => $game ? 'success' : 'error',
            'message' => $game ? 'Player deleted successfully.' : 'Player deletion failed',
            'data' => $game], 
            200);
    }

    public function myGames(Request $request)
{
    $user = $request->user();
    Log::info('User je:', [$request->user()]);

    // if (!$user) {
    //     return response()->json([
    //         'error' => 'Unauthorized'
    //     ], 401);
    // }$user = App\Models\Player::find(26); // uzmi user-a sa ID 1


    $query = Game::where('white_player_id', $user->player_id)
        ->orWhere('black_player_id', $user->player_id)
        ->with(['player_white', 'player_black', 'result'])
        ->orderBy('played_at', 'desc');

    if ($request->has('limit')) {
        $query->limit($request->get('limit'));
    }

    $games = $query->get()->map(function ($game) use ($user) {
        $opponent = $game->white_player_id === $user->player_id
            ? $game->player_black
            : $game->player_white;

        return [
            'game_id'   => $game->game_id,
            'played_at' => $game->played_at,
            'result'    => $game->result->naziv ?? null,
            'winner'    => $game->result->winner_id ?? null,
            'opponent'  => $opponent ? [
                'player_id' => $opponent->player_id,
                'name'      => $opponent->name,
            ] : null,
        ];
    });

    return response()->json($games);
}
}
