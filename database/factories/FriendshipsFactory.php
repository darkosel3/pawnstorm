<?php

namespace Database\Factories;

use App\Models\Friendships;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Friendships::class;

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
