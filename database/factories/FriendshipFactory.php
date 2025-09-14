<?php

namespace Database\Factories;

use App\Models\Friendship;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Friendship::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition()
    {
    return [
        'player1_id' => Player::factory(),
        'player2_id' => Player::factory(),
        'status' => 'pending',
    ];
    }
}
