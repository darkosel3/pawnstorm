<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'black_player_id',
        'white_player_id',
        'result_id',
        'game_type_id',
        'played_at',
        'PGN',
    ];

    protected $searchableFields = ['*'];
    protected $primaryKey = null;

    public $incrementing = false;

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function player_white()
    {
        return $this->belongsTo(Player::class, 'white_player_id', 'player_id');
    }

    public function player_black()
    {
        return $this->belongsTo(Player::class, 'black_player_id', 'player_id');
    }
}
