<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserFavoriteBookApiTest extends TestCase
{
    use RefreshDatabase;

    protected $book;

    protected $user;

    protected $token;

    protected $userFavoriteBook;

    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;
        $this->book = Book::factory()->create();
    }

    public function testGetUserFavoriteBooks()
    {
        $userId = $this->user->id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/favorite', [
                'user_id' => $this->user->id,
                'book_id' => $this->book->id
            ]);

        $response->assertStatus(200);
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/favorites/$userId");

        $response->assertStatus(200);

        $response->assertJson([
            [
                'title' => $this->book->title,
                'description' => $this->book->description,
            ],
        ]);
    }

    public function testUserFavoriteBookCanBeCreated()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/favorite', [
                'user_id' => $this->user->id,
                'book_id' => $this->book->id
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_favorites_books', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);
    }

    public function testUserFavoriteBookCanBeDeleted()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/favorite', [
                'user_id' => $this->user->id,
                'book_id' => $this->book->id
            ]);

        $response->assertJson([
            'favorite' => true,
            'favorites' => 1
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/favorite', [
                'user_id' => $this->user->id,
                'book_id' => $this->book->id
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'favorite' => false,
            'favorites' => 0
        ]);
    }
}
