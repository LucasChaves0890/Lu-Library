<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $comment;
    protected $subComment;


    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;
        $this->comment = Comment::factory()->create();
        $this->subComment = Comment::factory()->create();
    }

    public function testcommentCanBeCreated()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/comment', [
                'user_id' => $this->comment->user_id,
                'post_id' => $this->comment->post_id,
                'body' => $this->comment->body
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', [
            'user_id' => $this->comment->user_id,
            'post_id' => $this->comment->post_id,
            'body' => $this->comment->body
        ]);
    }

    public function testsubCommentCanBeCreated()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/comment', [
                'user_id' => $this->comment->user_id,
                'post_id' => $this->comment->post_id,
                'body' => $this->comment->body
            ]);

        $response->assertStatus(201);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/comment', [
                'user_id' => $this->subComment->user_id,
                'post_id' => $this->subComment->post_id,
                'parent_id' => $this->comment->id,
                'body' => $this->subComment->body
            ]);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Comentário enviado com sucesso.',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->subComment->user_id,
            'post_id' => $this->subComment->post_id,
            'parent_id' => $this->comment->id,
            'body' => $this->subComment->body
        ]);
    }
}
