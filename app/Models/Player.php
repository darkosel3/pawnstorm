<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Scopes\Searchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Player extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use Searchable;
    use HasApiTokens;

    protected $primaryKey = 'player_id';

    public $incrementing = 'int';

    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'rating',
        'user_type_id',
    ];

    protected $searchableFields = ['*'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function games_white()
    {
        return $this->hasMany(Game::class, 'white_player_id', 'player_id');
    }

    public function games_black()
    {
        return $this->hasMany(Game::class, 'black_player_id', 'player_id');
    }

    public function allFriendships()
    {
        return $this->hasMany(Friendships::class, 'player1', 'player_id');
    }

    public function receivedFriendships()
    {
        // Get friendships where the player is player2
        return $this->hasMany(Friendships::class, 'player2', 'player_id');
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->email, config('auth.super_admins'));
    }
}
