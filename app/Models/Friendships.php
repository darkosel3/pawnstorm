<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendships extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['player2', 'status', 'player_id'];

    protected $searchableFields = ['*'];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }
}
