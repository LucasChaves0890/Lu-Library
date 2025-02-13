<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $token;
    protected $book;
    protected $bookCreated;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->token = $this->adminUser->createToken('token', ['*'])->plainTextToken;

        $this->book = Book::factory()->make();
        $this->bookCreated = Book::factory()->create();
    }

    public function testSearchBooks()
    {
        $book = Book::factory()->create(['title' => 'Berserk Vol.1']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('api/book/search?title=berserk');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'title' => $book->title,
                'description' => $book->description,
                'gender' => $book->gender,
                'price' => $book->price,
                'author_id' => $book->author_id,
                'number_of_pages' => $book->number_of_pages
            ]
        ]);
    }

    public function testIndexBook()
    {
        $books = Book::factory()->count(3)->create();


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('api/admin/book');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'books' => [
                    [
                        'title' => $this->bookCreated->title,
                        'description' => $this->bookCreated->description,
                        'gender' => $this->bookCreated->gender,
                        'price' => $this->bookCreated->price,
                        'author_id' => $this->bookCreated->author_id,
                        'number_of_pages' => $this->bookCreated->number_of_pages
                    ],
                    [
                        'title' => $books[0]->title,
                        'description' => $books[0]->description,
                        'gender' => $books[0]->gender,
                        'price' => $books[0]->price,
                        'author_id' => $books[0]->author_id,
                        'number_of_pages' => $books[0]->number_of_pages
                    ],
                    [
                        'title' => $books[1]->title,
                        'description' => $books[1]->description,
                        'gender' => $books[1]->gender,
                        'price' => $books[1]->price,
                        'author_id' => $books[1]->author_id,
                        'number_of_pages' => $books[1]->number_of_pages
                    ],
                    [
                        'title' => $books[2]->title,
                        'description' => $books[2]->description,
                        'gender' => $books[2]->gender,
                        'price' => $books[2]->price,
                        'author_id' => $books[2]->author_id,
                        'number_of_pages' => $books[2]->number_of_pages
                    ]
                ]
            ]
        ]);
    }

    public function testBookCanBeCreated(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/admin/book', [
                'title' => $this->book->title,
                'description' => $this->book->description,
                'gender' => $this->book->gender,
                'price' => $this->book->price,
                'author_id' => $this->book->author_id,
                'number_of_pages' => $this->book->number_of_pages
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', [
            'title' => $this->book->title,
            'description' => $this->book->description,
            'gender' => $this->book->gender,
            'price' => $this->book->price,
            'author_id' => $this->book->author_id,
            'number_of_pages' => $this->book->number_of_pages
        ]);
    }

    public function testNonAdminCannotCreateBook(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = $user->createToken('token', ['*'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('api/admin/book', [
                'title' => $this->book->title,
                'description' => $this->book->description,
                'gender' => $this->book->gender,
                'price' => $this->book->price,
                'author_id' => $this->book->author_id,
                'number_of_pages' => $this->book->number_of_pages
            ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthorized'
        ]);
        $this->assertDatabaseMissing('books', [
            'title' => $this->book->title,
            'description' => $this->book->description,
            'gender' => $this->book->gender,
            'price' => $this->book->price,
            'author_id' => $this->book->author_id,
            'number_of_pages' => $this->book->number_of_pages
        ]);
    }

    public function testBookValidation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/admin/book', [
                'title' => 'a',
                'description' => '',
                'gender' => '',
                'price' => '',
                'author_id' => 85,
                'number_of_pages' => 'a'
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description', 'gender', 'price', 'author_id', 'number_of_pages']);
        $response->assertJson([
            'message' => 'O campo "título" deve ter pelo menos 3 caracteres. (and 5 more errors)',
            'errors' => [
                'title' => ['O campo "título" deve ter pelo menos 3 caracteres.'],
                'description' => ['O campo "descrição" é obrigatório.'],
                'gender' => ['O campo "gênero" é obrigatório.'],
                'price' => ['O campo "preço" é obrigatório.'],
                'author_id' => ['O autor informado não existe.'],
                'number_of_pages' => ['O campo "número de páginas" deve ser um número inteiro.']
            ]
        ]);
    }

    public function testShowBook()
    {
        $id = $this->bookCreated->id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/book/$id");

        $response->assertStatus(200);
        $response->assertJson([
            'book' => [
                'title' => $this->bookCreated->title,
                'description' => $this->bookCreated->description,
                'gender' => $this->bookCreated->gender,
                'price' => $this->bookCreated->price,
                'author_id' => $this->bookCreated->author_id,
                'number_of_pages' => $this->bookCreated->number_of_pages
            ]
        ]);
    }

    public function testBookCanBeUpdated(): void
    {
        $this->assertDatabaseHas('books', ['id' => $this->bookCreated->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("api/admin/book/{$this->bookCreated->id}", [
                'title' => 'George Orwell',
                'description' => 'Livro Distopico do George Orwell',
                'gender' => 'Distopia',
                'price' => 59.90,
                'author_id' => $this->bookCreated->author_id,
                'number_of_pages' => 110
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'title' => 'George Orwell',
            'description' => 'Livro Distopico do George Orwell',
            'gender' => 'Distopia',
            'price' => 59.90,
            'author_id' => $this->bookCreated->author_id,
            'number_of_pages' => 110
        ]);
    }

    public function testBookCanBeDeleted(): void
    {
        $this->assertDatabaseHas('books', [
            'id' => $this->bookCreated->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/admin/book/{$this->bookCreated->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('books', ['id' => $this->bookCreated->id]);
    }
}
