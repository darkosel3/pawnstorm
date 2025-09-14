<?php
namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    use HasFactory;
    use Searchable;

    protected $primaryKey = 'result_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
    ];

    protected $searchableFields = ['*'];

    public function games()
    {
        return $this->hasMany(Game::class, 'result_id', 'result_id');
    }
}
