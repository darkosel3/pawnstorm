<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Player::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique->email(),
            'email_verified_at' => now(),
            'username' => $this->faker->username(15),
            'password' => \Hash::make('password'),
            'remember_token' => Str::random(10),
            'rating' => $this->faker->numberBetween(1,2000),
            'user_type_id' => 3
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
