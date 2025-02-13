<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LikeApiTest extends TestCase
{
    protected $user;
    protected $token;
    protected $postId;
    protected $commentId;

    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;
        $this->postId = Post::factory()->create()->id;
        $this->commentId = Comment::factory()->create()->id;
    }

    public function testpostCanBeLiked()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/post/$this->postId/like");

        $response->assertStatus(200);
        $response->assertJson([
            'likes' => 1,
            'dislikes' => 0,
            'liked' => true,
            'disliked' => false
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->postId,
            'liked' => true
        ]);
    }

    public function testpostCanBeDisliked()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/post/$this->postId/dislike");

        $response->assertStatus(200);
        $response->assertJson([
            'likes' => 0,
            'dislikes' => 1,
            'liked' => false,
            'disliked' => true
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->postId,
            'liked' => false
        ]);
    }

    public function testcommentCanBeLiked()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/comment/$this->commentId/like");

        $response->assertStatus(200);
        $response->assertJson([
            'likes' => 1,
            'dislikes' => 0,
            'liked' => true,
            'disliked' => false
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->commentId,
            'liked' => true
        ]);
    }

    public function testcommentCanBeDisliked()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/comment/$this->commentId/dislike");

        $response->assertStatus(200);
        $response->assertJson([
            'likes' => 0,
            'dislikes' => 1,
            'liked' => false,
            'disliked' => true
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->commentId,
            'liked' => false
        ]);
    }
}
