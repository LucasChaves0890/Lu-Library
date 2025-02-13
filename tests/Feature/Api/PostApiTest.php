<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $post;

    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;
        $this->post = Post::factory()->create();
    }

    public function testpostCanBeCreated(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/post', [
                'user_id' => $this->post->user_id,
                'book_id' => $this->post->book_id,
                'body' => $this->post->body,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', [
            'user_id' => $this->post->user_id,
            'book_id' => $this->post->book_id,
            'body' => $this->post->body,
        ]);
    }

    public function testpostCanBeDeleted(): void
    {
        $this->assertDatabaseHas('posts', [
            'body' => $this->post->body,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/post/{$this->post->id}/{$this->post->user_id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Post excluído com sucesso.'
        ]);
        $this->assertDatabaseMissing('posts', [
            'id' => $this->post->id
        ]);
    }
}
