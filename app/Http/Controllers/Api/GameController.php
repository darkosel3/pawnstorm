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
        return response()->json([
            'status' => 'succes',
            'data'=> $game
        ], 200);
    
    }
    public function create(Request $request){
        return response()->json([Game::create($request->all())]);
    }

    public function destroy(Game $game){

        $deleted = $game -> delete();
        return response()->json([
            'status' => $game ? 'success' : 'error',
            'message' => $game ? 'Player deleted successfully.' : 'Player deletion failed',
            'data' => $game], 
            200);
    }

    
}
