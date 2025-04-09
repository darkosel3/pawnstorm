<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{
    //CREATE
    public function create(Request $request){
        return response()->json([Player::create($request->all())]);
    }

    public function index(){
        return Player::all();
    }

    //READ
    public function show(Player $player)
    {

        if(!$player){
            return response()->json([
                'status' => 'error',
                'message' => 'Player not found.'
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

        $player->patch($validatedData);
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


}
