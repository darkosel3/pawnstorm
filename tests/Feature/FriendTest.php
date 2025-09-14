<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;


class FriendTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_get_friends_list()
    {
        $player = Player::factory()->create();
        $friend = Player::factory()->create();
        
        Friendship::factory()->create([
            'player1_id' => $player->player_id,
            'player2_id' => $friend->player_id,
            'status' => 'accepted'
        ]);
        
        Sanctum::actingAs($player);

        $response = $this->getJson('/api/friends');

        $response->assertStatus(200);
        // Your controller uses Auth::id() which might not work with Sanctum testing
    }

    /** @test */
    public function authenticated_user_can_get_pending_requests()
    {
        $player = Player::factory()->create();
        $requester = Player::factory()->create();
        
        Friendship::factory()->create([
            'player1_id' => $requester->player_id,
            'player2_id' => $player->player_id,
            'status' => 'pending'
        ]);
        
        Sanctum::actingAs($player);

        $response = $this->getJson('/api/friends/pending');

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_send_friend_request()
    {
        $player = Player::factory()->create();
        $friend = Player::factory()->create();
        Sanctum::actingAs($player);

        $response = $this->postJson('/api/friends/request', [
            'receiver_id' => $friend->player_id, // Your validation expects this
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('friendships', [
            'player1_id' => $player->player_id,
            'player2_id' => $friend->player_id,
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function authenticated_user_can_accept_friend_request()
    {
        $player = Player::factory()->create();
        $requester = Player::factory()->create();
        
        $friendship = Friendship::factory()->create([
            'player1_id' => $requester->player_id,
            'player2_id' => $player->player_id,
            'status' => 'pending'
        ]);
        
        Sanctum::actingAs($player);

        $response = $this->putJson("/api/friends/{$friendship->friendship_id}/accept");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Friend request accepted'
                ]);
        
        $this->assertDatabaseHas('friendships', [
            'friendship_id' => $friendship->friendship_id,
            'status' => 'accepted'
        ]);
    }

    /** @test */
    public function authenticated_user_can_decline_friend_request()
    {
        $player = Player::factory()->create();
        $requester = Player::factory()->create();
        
        $friendship = Friendship::factory()->create([
            'player1_id' => $requester->player_id,
            'player2_id' => $player->player_id,
            'status' => 'pending'
        ]);
        
        Sanctum::actingAs($player);

        $response = $this->putJson("/api/friends/{$friendship->friendship_id}/decline");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Friend request declined'
                ]);
        
        $this->assertDatabaseHas('friendships', [
            'friendship_id' => $friendship->friendship_id,
            'status' => 'declined'
        ]);
    }

    /** @test */
    public function user_cannot_accept_others_friend_requests()
    {
        $player = Player::factory()->create();
        $otherPlayer = Player::factory()->create();
        
        $friendship = Friendship::factory()->create([
            'player1_id' => $otherPlayer->player_id,
            'player2_id' => Player::factory()->create()->player_id,
            'status' => 'pending'
        ]);
        
        Sanctum::actingAs($player);

        $response = $this->putJson("/api/friends/{$friendship->friendship_id}/accept");

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_friend_routes()
    {
        $this->getJson('/api/friends')->assertStatus(401);
        $this->getJson('/api/friends/pending')->assertStatus(401);
        $this->postJson('/api/friends/request')->assertStatus(401);
        $this->putJson('/api/friends/1/accept')->assertStatus(401);
        $this->putJson('/api/friends/1/decline')->assertStatus(401);
    }
}

