<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameType extends Model
{
    use HasFactory;
    use Searchable;

    protected $primaryKey = 'game_type_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'naziv',
        'time_format',
        'increment',
    ];

    protected $searchableFields = ['*'];

    public function games()
    {
        return $this->hasMany(Game::class, 'game_type_id', 'game_type_id');
    }
}