<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\BookToRead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookToReadApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $bookToRead;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;
        $this->bookToRead = BookToRead::factory()->make();
    }

    public function testGetUserReadedBooksById()
    {
        $userId = $this->user->id;
        $book = Book::factory()->create();

        BookToRead::factory()->create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'pages_read' => $book->number_of_pages
        ]);


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/readed/$userId");

        $response->assertStatus(200);

        $response->assertJson([
            [
                'title' => $book->title,
                'description' => $book->description,
                'gender' => $book->gender,
                'number_of_pages' => $book->number_of_pages
            ]
        ]);
    }
    
    public function testGetUserReadingBooksById()
    {
        $userId = $this->user->id;
        $book = Book::factory()->create();

        BookToRead::factory()->create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'pages_read' => 1
        ]);


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/reading/$userId");

        $response->assertStatus(200);

        $response->assertJson([
            [
                'title' => $book->title,
                'description' => $book->description,
                'gender' => $book->gender,
            ]
        ]);
    }

    public function testUserCanMarkBookAsRead()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/read', [
                'user_id' => $this->bookToRead->user_id,
                'book_id' => $this->bookToRead->book_id,
            ]);

        $response->assertStatus(201);

        $response->assertJson(['readed' => true]);

        $this->assertDatabaseHas('books_to_read', [
            'user_id' => $this->bookToRead->user_id,
            'book_id' => $this->bookToRead->book_id,
        ]);
    }

    public function testUserCanUnmarkBookAsRead()
    {
        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/read', [
                'user_id' => $this->bookToRead->user_id,
                'book_id' => $this->bookToRead->book_id,
            ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/read', [
                'user_id' => $this->bookToRead->user_id,
                'book_id' => $this->bookToRead->book_id,
            ]);

        $response->assertStatus(201);

        $response->assertJson(['readed' => false]);
    }

    public function testUserCanUpdatePagesReadForABook()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('api/read', [
                'user_id' => $this->bookToRead->user_id,
                'book_id' => $this->bookToRead->book_id,
                'pages_read' => 100
            ]);

        $response->assertStatus(200);

        $response->assertJson([
            'readed' => false,
            'booksReadQty' => 0,
            'booksReadingQty' => 1,
            'pagesRead' => 100
        ]);
    }

    public function testUserCanCompleteReadingABook()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('api/read', [
                'user_id' => $this->bookToRead->user_id,
                'book_id' => $this->bookToRead->book_id,
                'pages_read' => 95,
            ]);

        $response->assertStatus(200);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('api/read', [
                'user_id' => $this->bookToRead->user_id,
                'book_id' => $this->bookToRead->book_id,
                'pages_read' => $this->bookToRead->pages_read,
            ]);

        $response->assertStatus(200);

        $response->assertJson([
            'readed' => true,
            'booksReadQty' => 1,
            'booksReadingQty' => 0,
            'pagesRead' => $this->bookToRead->pages_read,
        ]);
    }
}
