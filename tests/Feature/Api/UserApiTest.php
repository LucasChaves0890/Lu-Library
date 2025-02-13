<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function Laravel\Prompts\password;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => 'password'
        ]);
        $this->token = $this->user->createToken("token", ['*'])->plainTextToken;
    }

    public function testUserCannotBeUpdatedWithInvalidToken(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . 'InvalidToken')
            ->putJson("api/user/{$this->user->id}", [
                'username' => 'Kentaro Miura',
                'email' => 'Kentaro@gmail.com',
                'sex' => 'masculino',
                'description' => 'Autor de mangá',
                'role' => 'user'
            ]);

        $response->assertStatus(401);
    }

    public function testUserCanBeUpdated(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("api/user/{$this->user->id}", [
                'username' => 'Kentaro Miura',
                'email' => 'Kentaro@gmail.com',
                'sex' => 'masculino',
                'description' => 'Autor de mangá',
                'role' => 'user'
            ]);
            
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'username' => 'Kentaro Miura',
            'email' => 'Kentaro@gmail.com',
            'sex' => 'masculino',
            'description' => 'Autor de mangá',
            'role' => 'user'
        ]);
    }

    public function testUserCanLogin(): void
    { 
        $response = $this->postJson("api/login", [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = $user->createToken('token')->plainTextToken;

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('api/logout');

        $response->assertStatus(200);
    }
}
