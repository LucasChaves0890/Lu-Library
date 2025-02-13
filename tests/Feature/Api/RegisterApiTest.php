<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->make();
    }

    public function testUserCanBeCreated(): void
    {
        $response = $this->postJson('api/register', [
            'username' => $this->user->username,
            'email'=> $this->user->email,
            'sex' => $this->user->sex,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $createdUser = User::where('email', $this->user->email)->firstOrFail();

        $this->assertDatabaseHas('users', [
            'id' => $createdUser->id,
            'username' => $this->user->username,
            'email' => $this->user->email,
            'sex' => $this->user->sex,
        ]);

        $freshUser = User::find($createdUser->id);
        $this->assertTrue(Hash::check('password', $freshUser->password));
        $this->assertAuthenticated();
    }

    public function testUserCannotBeCreatedWithinvalidArguments(): void
    {
        $response = $this->postJson('api/register', [
            'username' => "x",
            'email' => 'lumbrud#da.cpm',
            'sex' => 'Hétero',
            'password' => '',
            'password_confirmation' => 'paia'
        ]);

        $errors = $response->json("errors");

        $response->assertStatus(422);

        $expectedFields = ['username', 'email', 'sex', 'password'];

        foreach($expectedFields as $field) {
            $this->assertArrayHasKey($field, $errors);
            $this->assertNotEmpty($errors[$field]);
        }
    }
}
