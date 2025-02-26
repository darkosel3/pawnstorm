<?php

namespace Database\Factories;

use App\Models\Friendships;
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
        return [
            'status' => $this->faker->text(20),
            'player_id' => \App\Models\Player::factory(),
        ];
    }
}
