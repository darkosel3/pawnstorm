<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendship extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['player2', 'status', 'player1'];

    protected $searchableFields = ['*'];

    protected $primaryKey = null;

    public $incrementing = TRUE;

    public function player()
    {
        return $this->belongsTo(Player::class, 'player1', 'player_id');
    }
    public function secondPlayer()
    {
        return $this->belongsTo(Player::class, 'player2', 'player_id');
    }
}
