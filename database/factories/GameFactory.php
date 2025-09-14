<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use App\Models\GameType;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    $results = ['1-0', '0-1', '1/2-1/2', '*'];
    $selectedResult = $this->faker->randomElement($results);
    
    return [
    'white_player_id' => Player::factory(),
    'black_player_id' => Player::factory(),
    'result_id' => Result::query()->inRandomOrder()->value('result_id') 
               ?? Result::factory(),
    'game_type_id' => GameType::inRandomOrder()->first()->game_type_id ?? GameType::factory(),
    'played_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
    'PGN' => $this->generateSimplePGN($selectedResult),
];
}

private function generateSimplePGN($result): string
{
    $moves = [
        '1.e4 e5 2.Nf3 Nc6 3.Bb5',
        '1.d4 d5 2.c4 c6 3.Nf3',
        '1.e4 c5 2.Nf3 d6 3.d4',
        '1.Nf3 Nf6 2.g3 g6 3.Bg2'
    ];
    
    $selectedMoves = $this->faker->randomElement($moves);
    
    return '[Event "Test Game"]
[Site "PawnStorm.com"]
[Date "' . now()->format('Y.m.d') . '"]
[White "Player1"]
[Black "Player2"]
[Result "' . $result . '"]

' . $selectedMoves . ' ' . $result;
}
}
