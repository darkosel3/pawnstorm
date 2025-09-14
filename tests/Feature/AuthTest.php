<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;


class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => 'password123',
            'user_type_id' => 3,
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'status' => 'success',
                ])
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'player_id',
                        'name',
                        'email',
                        'username',
                        'created_at',
                        'updated_at',
                    ],
                    'token'
                ]);

        $this->assertDatabaseHas('players', [
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $player = Player::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                ])
                ->assertJsonStructure([
                    'status',
                    'data',
                    'token'
                ]);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function authenticated_user_can_get_profile()
    {
        $player = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->getJson('/api/me');

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'player_id' => $player->player_id,
                        'email' => $player->email,
                    ]
                ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/me');
        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $player = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Successfully logged out'
                ]);
    }

    /** @test */
    public function registration_validates_required_fields()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'username', 'password', 'user_type_id']);
                
    }
}
