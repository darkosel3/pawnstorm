<?php

namespace Tests\Unit;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function player_has_correct_fillable_attributes()
    {
        $fillable = ['name', 'email', 'username', 'password','user_type_id'];
        // 
        $player = new Player();
        
        $this->assertEquals($fillable, $player->getFillable());
    }

    /** @test */
    public function player_has_correct_hidden_attributes()
    {
        $hidden = ['password', 'remember_token'];
        $player = new Player();
        
        $this->assertEquals($hidden, $player->getHidden());
    }

    /** @test */
    public function player_uses_custom_primary_key()
    {
        $player = new Player();
        
        $this->assertEquals('player_id', $player->getKeyName());
    }

    /** @test */
    public function player_can_create_tokens()
    {
        $player = Player::factory()->create();
        $token = $player->createToken('test-token');
        
        $this->assertNotNull($token);
        $this->assertNotEmpty($token->plainTextToken);
    }
}
