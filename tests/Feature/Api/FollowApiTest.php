<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FollowApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $otherUser;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;
        $this->otherUser = User::factory()->create();
    }

    public function testUserCanfollow()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/follow', [
                'following_user_id' => $this->user->id,
                'followed_user_id' => $this->otherUser->id
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('follows', [
            'following_user_id' => $this->user->id,
            'followed_user_id' => $this->otherUser->id
        ]);

        $response->assertJson([
            'message' => 'success',
            'follow' => true,
            'following' => 1
        ]);
    }


    public function testUserCanUnfolow()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/follow', [
                'following_user_id' => $this->user->id,
                'followed_user_id' => $this->otherUser->id
            ]);

        $response->assertJson([
            'message' => 'success',
            'follow' => true,
            'following' => 1
        ]);

        $id = $response->json('follow.id');

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/follow', [
                'following_user_id' => $this->user->id,
                'followed_user_id' => $this->otherUser->id
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'success',
            'follow' => false,
            'following' => 0
        ]);
        $this->assertDatabaseMissing('follows', [
            'id' => $id
        ]);
    }
}
