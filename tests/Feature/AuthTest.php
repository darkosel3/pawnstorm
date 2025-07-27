<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_user_can_register(){
        $response = $this->postJson('/api/register', [
            'name' => 'Test Player',
            'email' => 'test1@example.com',
            'username' => 'Tester1',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'user' => ['player_id', 'name','email'],
                    'token'
                 ]);
            // Provera u bazi
        $this->assertDatabaseHas('players', [
            'email' => 'test1@example.com'
        ]);
    }
    public function test_user_can_login(){
        Player::create([
           'name' => 'Test Player',
            'username' => 'Tester1',
            'email' => 'test2@example.com',
            'password' => Hash::make('password123')
        ]);


        $response = $this->postJson('/api/login',[
            'email' => 'test2@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'user' => ['player_id', 'name', 'email'],
                    'token'
                 ]);
    }

    public function test_user_can_fetch_auth_user(){
        $player = Player::create([
            'name' => 'AuthUser',
            'username' => 'AuthUser1',
            'email' => 'authuser@gmail.com',
            'password' => Hash::make('authpassword123')
        ]);

        $response = $this->postJson('/api/login',[
            'email' => 'authuser@gmail.com',
            'password' => 'authpassword123'
        ]);
        
        $token = $response['token'];
        
        $meResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $meResponse->assertStatus(200)
                  ->assertJson([
                    'name' => 'AuthUser',
                    'username' => 'AuthUser1',
                    'email' => 'authuser@gmail.com',
                  ]);

    }

    public function test_user_can_logout(){
        
        $player = Player::create([
        'name' => 'Test Logout',
        'email' => 'logout@example.com',
        'username' => 'LogoutUser',
        'password' => Hash::make('password123'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'logout@example.com',
            'password'=> 'password123'
        ]);

        $token = $loginResponse['token'];
    
        $logoutResponse = $this->withHeaders(['Authorization' => 'Bearer ' . $token,])->postJson('/api/logout');
        $logoutResponse->assertStatus(200)
                      ->assertJson(['message' => 'Successfully logged out']);
                      

        $meResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');
            
        $meResponse->assertStatus(401);
    }


}
