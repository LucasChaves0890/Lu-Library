<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\BookRating;
use App\Models\BookToRead;
use App\Models\User;
use App\Models\UserFavoriteBook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BookshelfApiTest extends TestCase
{
    protected $user;
    protected $token;
    protected $ratedBook;
    protected $favoriteBook;
    protected $readedBook;
    protected $readingBook;

    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('token', ['*'])->plainTextToken;

        $this->ratedBook = Book::factory()->create();
        BookRating::factory()->create(['book_id' => $this->ratedBook->id, 'user_id' => $this->user->id]);

        $this->favoriteBook = Book::factory()->create();
        UserFavoriteBook::factory()->create(['user_id' => $this->user->id, 'book_id' => $this->favoriteBook->id]);

        $this->readedBook = Book::factory()->create();
        BookToRead::factory()->create(['user_id' => $this->user->id, 'book_id' => $this->readedBook->id, 'pages_read' => $this->readedBook->number_of_pages]);

        $this->readingBook = Book::factory()->create();
        BookToRead::factory()->create(['user_id' => $this->user->id, 'book_id' => $this->readingBook->id]);
    }


    public function testBookshelfStructure(): void
    {
        $id = $this->user->id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/bookshelf/$id");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'bookshelf' => [
                '*' => [
                    'title',
                    'description',
                    'gender',
                    'book_cover',
                    'price',
                    'author_id',
                    'number_of_pages',
                    'pages_read',
                    'ratingData' => [
                        'bookRatingsQty',
                    ],
                    'favorite' => [
                        'favorite',
                        'favorites',
                    ],
                    'read' => [
                        'readed',
                        'booksReadQty',
                        'booksReadingQty',
                        'pagesRead',
                        'percentageRead',
                    ],
                ],
            ],
        ]);
    }

    public function testBookshelf(): void
    {
        $id = $this->user->id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/bookshelf/$id");

        $response->assertStatus(200);
        
        $response->assertJson([
            'bookshelf' => [
                [
                    'title' => $this->favoriteBook->title,
                    'description' => $this->favoriteBook->description,
                    'gender' => $this->favoriteBook->gender,
                    'book_cover' => null,
                    'price' => $this->favoriteBook->price,
                    'author_id' => $this->favoriteBook->author_id,
                    'number_of_pages' => $this->favoriteBook->number_of_pages,
                    'pages_read' => null,
                    'ratingData' => [
                        'bookRatingsQty' => 0,
                    ],
                    'favorite' => [
                        'favorite' => [
                            'user_id' => $this->user->id,
                            'book_id' => $this->favoriteBook->id,
                        ],
                        'favorites' => 1,
                    ],
                    'read' => [
                        'readed' => false,
                        'booksReadQty' => 0,
                        'booksReadingQty' => 0,
                        'pagesRead' => 0,
                        'percentageRead' => 0,
                    ],
                ],
                [
                    'title' => $this->readedBook->title,
                    'description' => $this->readedBook->description,
                    'gender' => $this->readedBook->gender,
                    'book_cover' => null,
                    'price' => $this->readedBook->price,
                    'author_id' => $this->readedBook->author_id,
                    'number_of_pages' => $this->readedBook->number_of_pages,
                    'pages_read' => null,
                    'ratingData' => [
                        'bookRatingsQty' => 0,
                    ],
                    'favorite' => [
                        'favorite' => null,
                        'favorites' => 0,
                    ],
                    'read' => [
                        'readed' => true,
                        'booksReadQty' => 1,
                        'booksReadingQty' => 0,
                        'pagesRead' => $this->readedBook->number_of_pages,
                        'percentageRead' => 100,
                    ],
                ],
                [
                    'title' => $this->readingBook->title,
                    'description' => $this->readingBook->description,
                    'gender' => $this->readingBook->gender,
                    'book_cover' => null,
                    'price' => $this->readingBook->price,
                    'author_id' => $this->readingBook->author_id,
                    'number_of_pages' => $this->readingBook->number_of_pages,
                    'pages_read' => null,
                    'ratingData' => [
                        'bookRatingsQty' => 0,
                    ],
                    'favorite' => [
                        'favorite' => null,
                        'favorites' => 0,
                    ],
                    'read' => [
                        'readed' => false,
                        'booksReadQty' => 0,
                        'booksReadingQty' => 1,
                    ],
                ],
                [
                    'title' => $this->ratedBook->title,
                    'description' => $this->ratedBook->description,
                    'gender' => $this->ratedBook->gender,
                    'book_cover' => null,
                    'price' => $this->ratedBook->price,
                    'author_id' => $this->ratedBook->author_id,
                    'number_of_pages' => $this->ratedBook->number_of_pages,
                    'pages_read' => null,
                    'ratingData' => [
                        'bookRatingsQty' => 1,
                    ],
                    'favorite' => [
                        'favorite' => null,
                        'favorites' => 0,
                    ],
                    'read' => [
                        'readed' => false,
                        'booksReadQty' => 0,
                        'booksReadingQty' => 0,
                        'pagesRead' => 0,
                        'percentageRead' => 0,
                    ],
                ],
            ],
        ]);
    }
}
