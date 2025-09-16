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
    $currentUserId = Auth::id();
    
    $friends = Friendship::where('status', 'accepted')
        ->where(function($q) use ($currentUserId) {
            $q->where('player1_id', $currentUserId)
              ->orWhere('player2_id', $currentUserId);
        })
        ->with(['player1', 'player2'])
        ->get()
        ->map(function($friendship) use ($currentUserId) {
            // Vrati podatke o prijatelju (ne o trenutnom korisniku)
            return $friendship->player1_id === $currentUserId 
                ? $friendship->player2 
                : $friendship->player1;
        });
    
    return response()->json($friends);
}
public function pending()
{
    $currentUserId = Auth::id();
    
    $received = Friendship::where('status', 'pending')
        ->where('player2_id', $currentUserId)
        ->with('player1')
        ->get()
        ->map(fn($f) => $f->player1);
    
    $sent = Friendship::where('status', 'pending')
        ->where('player1_id', $currentUserId)
        ->with('player2')
        ->get()
        ->map(fn($f) => $f->player2);
    
    return response()->json([
        'received' => $received,
        'sent' => $sent
    ]);
}

    public function sendRequest(Request $request)
{
    $request->validate([
        'userId' => 'required|exists:players,player_id', // promeni na userId
    ]);

    $friendship = Friendship::create([
        'player1_id' => Auth::id(),
        'player2_id' => $request->userId, // promeni na userId
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