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
    public function definition(): array
    {
        $status = $this->faker->randomElement(['A', 'P']);
        return [
            'status' => $status,
            'player1' => Player::factory(),
            'player2' => Player::factory()
        ];
    }
}
