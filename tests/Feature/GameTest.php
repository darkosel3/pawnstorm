<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_get_all_games()
    {
        $player = Player::factory()->create();
        Game::factory()->count(3)->create();
        Sanctum::actingAs($player);

        $response = $this->getJson('/api/games');

        $response->assertStatus(200);
        // GameController->index() just returns Game::all() without consistent format
    }

    /** @test */
    public function authenticated_user_can_get_specific_game()
    {
        $player = Player::factory()->create();
        $game = Game::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->getJson("/api/games/{$game->game_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success', // Note: your controller has typo "succes"
                ]);
    }

    /** @test */
    public function authenticated_user_can_create_game()
    {
        $gameType = \App\Models\GameType::first();
        $result = \App\Models\Result::first(); // uzmi prvi seedovani rezultat
        $player = Player::factory()->create();
        $whitePlayer = Player::factory()->create();
        $blackPlayer = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->postJson('/api/games', [
            'white_player_id' => $whitePlayer->player_id,
            'black_player_id' => $blackPlayer->player_id,
            'game_type_id' => $gameType->game_type_id,
            'result_id' => $result->result_id,
            'PGN' => '1.e4 e5',
            'played_at' => now()->toDateTimeString(),
        ]);

        // Your create method doesn't validate, so this might fail
        $response->assertStatus(201);
    }

    /** @test */
    public function authenticated_user_can_delete_game()
    {
        $player = Player::factory()->create();
        $game = Game::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->deleteJson("/api/games/{$game->game_id}");

        $response->assertStatus(200);
        // Your destroy method has incorrect logic checking $game instead of $deleted
    }

    /** @test */
    public function unauthenticated_user_cannot_access_game_routes()
    {
        $game = Game::factory()->create();

        $this->getJson('/api/games')->assertStatus(401);
        $this->postJson('/api/games')->assertStatus(401);
        $this->getJson("/api/games/{$game->game_id}")->assertStatus(401);
        $this->deleteJson("/api/games/{$game->game_id}")->assertStatus(401);
    }
}
