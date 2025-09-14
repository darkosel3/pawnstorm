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
    protected $primaryKey = 'game_id';

    public $incrementing = TRUE;

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
    public function gameType()
{
    return $this->belongsTo(GameType::class, 'game_type_id', 'game_type_id');
}
    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id', 'result_id');
    }
}
