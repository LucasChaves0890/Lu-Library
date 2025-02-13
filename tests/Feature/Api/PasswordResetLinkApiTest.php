<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetLinkApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    public function setUp(): void
    {
        parent::setUp();
        
        DB::beginTransaction();
        $this->user = User::factory()->create([
            "password" => "password"
        ]);
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    public function testUserCanResetTheirPassword(): void
    {
        $response = $this->postJson('api/reset', [
            'email' => $this->user->email
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $this->user->email
        ]);

        $response->assertJsonStructure([
            'status'
        ]);

        $token = DB::table('password_reset_tokens')->where('email', $this->user->email)->first();
        $this->assertNotNull($token, 'Password reset token is missing.');
        $this->assertNotNull($token->token, 'Password reset token is missing');
    }
}
