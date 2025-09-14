<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendship extends Model
{
    use HasFactory;
    use Searchable;



    protected $searchableFields = ['*'];

    protected $primaryKey = 'friendship_id'; 
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['player2_id', 'status', 'player1_id'];

    public function sender()
    {
        return $this->belongsTo(Player::class, 'player1_id', 'player_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Player::class, 'player2_id', 'player_id');
    }
}
