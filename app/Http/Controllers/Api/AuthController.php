<?php
namespace App\Http\Controllers\Api;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $player = Player::where('email', $request->email)->firstOrFail();

        $token = $player->createToken('auth-token');

        return response()->json([
            'status' => 'success',
            'data' => $player,
            'token' => $token->plainTextToken,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:players',
            'username' => 'required|string|max:255|unique:players',
            'password' => 'required|min:6',
            'user_type_id' => 'required|integer',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        
        $player = Player::create($validatedData);

        $token = $player->createToken('auth-token');

        return response()->json([
            'status' => 'success',
            'data' => $player,
            'token' => $token->plainTextToken,
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }
}