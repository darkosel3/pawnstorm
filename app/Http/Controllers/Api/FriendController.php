<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        return Friendship::where('status', 'accepted')
            ->where(function($q) {
                $q->where('player1_id', Auth::id())
                  ->orWhere('player2_id', Auth::id());
            })->get();
    }

    public function pending()
    {
        return Friendship::where('status', 'pending')
            ->where('player2_id', Auth::id())
            ->get();
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:players,player_id',
        ]);

        $friendship = Friendship::create([
            'player1_id' => Auth::id(),
            'player2_id' => $request->receiver_id,
            'status' => 'pending',
        ]);

        return response()->json($friendship, 201);
    }

    public function accept($id)
    {
        $friendship = Friendship::findOrFail($id);
        if ($friendship->player2_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $friendship->update(['status' => 'accepted']);
        return response()->json(['message' => 'Friend request accepted']);
    }

    public function decline($id)
    {
        $friendship = Friendship::findOrFail($id);
        if ($friendship->player2_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $friendship->update(['status' => 'declined']);
        return response()->json(['message' => 'Friend request declined']);
    }
}