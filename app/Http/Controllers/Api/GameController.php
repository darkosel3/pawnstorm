<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;


class GameController extends Controller
{
    public function index(){
        return Game::all();
    }
    public function show(Game $game){
        if(!$game){
            return response()->json([
                'status' => 'error',
                'message' => 'Game not found'
            ],404);
        }
        return response()->json([
            'status' => 'succes',
            'message'=> $game
        ], 200);
    
    }
    public function create(Request $request){
        return response()->json([Game::create($request->all())]);
    }

    public function destroy(Game $game){
        $gameData = $game;
        $deleted = $game -> delete();
        return response()->json([
            'status' => $game ? 'success' : 'error',
            'message' => $game ? 'Player deleted successfully.' : 'Player deletion failed',
            'data' => $gameData], 
            200);
    }

    
}
