<?php

namespace Tests\Feature\Api;

use App\Models\BookRating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookRatingApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $bookRating;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;
        $this->bookRating = BookRating::factory()->make();
    }

    public function testbookRatingCanBeCreated()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/rate-book', [
                'user_id' => $this->bookRating->user_id,
                'book_id' => $this->bookRating->book_id,
                'rating' => $this->bookRating->rating
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('book_ratings', [
            'user_id' => $this->bookRating->user_id,
            'book_id' => $this->bookRating->book_id,
            'rating' => $this->bookRating->rating
        ]);
    }
}
