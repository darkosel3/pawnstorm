<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Scopes\Searchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;



class Player extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;
    use Searchable;
    

    protected $primaryKey = 'player_id';

    public $incrementing = TRUE;

    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'user_type_id',
    ];

    protected $searchableFields = ['*'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    public function games_white()
    {
        return $this->hasMany(Game::class, 'white_player_id', 'player_id');
    }

    public function games_black()
    {
        return $this->hasMany(Game::class, 'black_player_id', 'player_id');
    }

    public function allFriendship()
    {
        return $this->hasMany(Friendship::class, 'player1', 'player_id');
    }

    public function receivedFriendship()
    {
        // Get friendship where the player is player2
        return $this->hasMany(Friendship::class, 'player2', 'player_id');
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->email, config('auth.super_admins'));
    }
    public function getJWTIdentifier()
    {
        return $this->getKey(); // player_id
    }
    public function getJWTCustomClaims(){
        return []; // dodatni podaci u token, nije potrebno ali moramo da ga implementiramo zbog nasledjivanja
    }


}
