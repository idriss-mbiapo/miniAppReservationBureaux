<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * verifier si l'utilisateur peut etre connecté en utilisant JWT
     */

    public function test_user_can_login_with_jwt()
    {
        User::factory()->create([
            'email' => 'idriss@gmail.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'idriss@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in','user']);
    }
}
