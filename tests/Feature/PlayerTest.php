<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Game;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_get_all_players()
    {
        $player = Player::factory()->create();
        Player::factory()->count(3)->create();
        Sanctum::actingAs($player);

        $response = $this->getJson('/api/players');

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                ])
                ->assertJsonCount(4, 'data');
    }

    /** @test */
    public function authenticated_user_can_create_player()
    {
        $player = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->postJson('/api/players', [
            'name' => 'New Player',
            'email' => 'new@example.com',
            'username' => 'newplayer',
            'password' => 'password123',
            'user_type_id' => 1,
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'status' => 'success',
                ]);

        $this->assertDatabaseHas('players', [
            'email' => 'new@example.com',
            'username' => 'newplayer',
        ]);
    }

    /** @test */
    public function authenticated_user_can_get_specific_player()
    {
        $player = Player::factory()->create();
        $targetPlayer = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->getJson("/api/players/{$targetPlayer->player_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'player_id' => $targetPlayer->player_id,
                        'email' => $targetPlayer->email,
                    ]
                ]);
    }

    /** @test */
    public function authenticated_user_can_update_player()
    {
        $player = Player::factory()->create();
        $targetPlayer = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->putJson("/api/players/{$targetPlayer->player_id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Player updated successfully.',
                ]);

        $this->assertDatabaseHas('players', [
            'player_id' => $targetPlayer->player_id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function authenticated_user_can_delete_player()
    {
        $player = Player::factory()->create();
        $targetPlayer = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->deleteJson("/api/players/{$targetPlayer->player_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Player deleted successfully.',
                ]);

        $this->assertDatabaseMissing('players', [
            'player_id' => $targetPlayer->player_id,
        ]);
    }

    /** @test */
    public function authenticated_user_can_get_player_games()
    {
        $player = Player::factory()->create();
        $targetPlayer = Player::factory()->create();
        
        // Create games for the target player
        Game::factory()->count(3)->create(['white_player_id' => $targetPlayer->player_id]);
        Game::factory()->count(2)->create(['black_player_id' => $targetPlayer->player_id]);
        
        Sanctum::actingAs($player);

        $response = $this->getJson("/api/players/{$targetPlayer->player_id}/games");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                ])
                ->assertJsonStructure([
                    'status',
                    'data',
                    'pagination' => [
                        'current_page',
                        'total_pages',
                        'total_games',
                        'per_page'
                    ]
                ]);
    }

    /** @test */
    public function authenticated_user_can_get_player_friends()
    {
        $player = Player::factory()->create();
        $targetPlayer = Player::factory()->create();
        $friend = Player::factory()->create();
        
        // Create accepted friendship
        Friendship::factory()->create([
            'player1_id' => $targetPlayer->player_id,
            'player2_id' => $friend->player_id,
            'status' => 'accepted'
        ]);
        
        Sanctum::actingAs($player);

        $response = $this->getJson("/api/players/{$targetPlayer->player_id}/friends");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                ])
                ->assertJsonStructure([
                    'status',
                    'data',
                    'total_friends'
                ]);
    }

    /** @test */
    public function authenticated_user_can_get_player_stats()
    {
        $player = Player::factory()->create();
        $targetPlayer = Player::factory()->create();
        
        Sanctum::actingAs($player);

        $response = $this->getJson("/api/players/{$targetPlayer->player_id}/stats");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                ])
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'player',
                        'overview' => [
                            'total_games',
                            'wins',
                            'losses',
                            'draws',
                            'win_percentage',
                            'games_as_white',
                            'games_as_black'
                        ],
                        'games_by_type',
                        'recent_games',
                        'total_friends'
                    ]
                ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_player_routes()
    {
        $player = Player::factory()->create();

        $this->getJson('/api/players')->assertStatus(401);
        $this->postJson('/api/players')->assertStatus(401);
        $this->getJson("/api/players/{$player->player_id}")->assertStatus(401);
        $this->putJson("/api/players/{$player->player_id}")->assertStatus(401);
        $this->deleteJson("/api/players/{$player->player_id}")->assertStatus(401);
        $this->getJson("/api/players/{$player->player_id}/games")->assertStatus(401);
        $this->getJson("/api/players/{$player->player_id}/friends")->assertStatus(401);
        $this->getJson("/api/players/{$player->player_id}/stats")->assertStatus(401);
    }
}
