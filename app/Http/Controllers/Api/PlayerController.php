<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{
    public function index(){
        return Player::all();
    }


    public function show(string $id): View
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }


}
