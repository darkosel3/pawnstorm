<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:55',
            'email' => 'required|string|email|unique:players',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $player = Player::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($player);

        return response()->json([
            'user' => $player,
            'token' => $token]
        , 201);
    }

    public function login(Request $request){
        // izvadi kredencijale iz req
        $credentials = $request->only('email', 'password');
        //ako nije authorizovano vrati error
        if(!$token = JWTAuth::attempt($credentials)){
            return response()->json(['error' => 'Unauthorized'],401);
        }
        // u suprotnom vrati usera i token
        return response()->json([
            'user' => auth()->user(),
            'token' => $token 
        ]);
    }

    public function logout(){
        try {
            auth('api')->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch(JWTException $e){
            
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }

    public function me(){
        return response()->json(auth('api')->user());
    }
}
