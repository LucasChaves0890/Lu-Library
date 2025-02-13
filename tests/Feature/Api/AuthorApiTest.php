<?php

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorApiTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $token;
    protected $author;
    protected $authorCreated;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->token = $this->adminUser->createToken('token', ['*'])->plainTextToken;

        $this->author = Author::factory()->make();
        $this->authorCreated = Author::factory()->create();
    }

    public function testSearchAuthors()
    {
        Author::factory()->create(['name' => 'Machado de Assis']);


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/author/search?name=Machado de Assis");

        $response->assertStatus(200);
        $response->assertJson([
            [
                'name' => 'Machado de Assis'
            ]
        ]);
    }

    public function testAuthorIndex()
    {
        $authors = Author::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('api/admin/author');

        $response->assertStatus(200);
        $response->assertJson([
            'authors' => [
                [
                    'name' => $this->authorCreated->name,
                    'sex' => $this->authorCreated->sex,
                    'description' => $this->authorCreated->description,
                    'nacionality' => $this->authorCreated->nacionality,
                ],
                [
                    'name' => $authors[0]->name,
                    'sex' => $authors[0]->sex,
                    'description' => $authors[0]->description,
                    'nacionality' => $authors[0]->nacionality,
                ],
                [
                    'name' => $authors[1]->name,
                    'sex' => $authors[1]->sex,
                    'description' => $authors[1]->description,
                    'nacionality' => $authors[1]->nacionality,
                ],
                [
                    'name' => $authors[2]->name,
                    'sex' => $authors[2]->sex,
                    'description' => $authors[2]->description,
                    'nacionality' => $authors[2]->nacionality,
                ],
            ]
        ]);
    }

    public function testAuthorCanBeCreated(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/admin/author', [
                'name' => $this->author->name,
                'sex' => $this->author->sex,
                'description' => $this->author->description,
                'nacionality' => $this->author->nacionality,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('authors', [
            'name' => $this->author->name,
            'sex' => $this->author->sex,
            'description' => $this->author->description,
            'nacionality' => $this->author->nacionality,
        ]);
    }

    public function testShowAuthor()
    {
        $id = $this->authorCreated->id;
        $books = Book::factory()->count(3)->create(['author_id' => $this->authorCreated->id]);


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("api/author/$id");

        $response->assertStatus(200);
        $response->assertJson([
            'author' => [
                'name' => $this->authorCreated->name,
                'sex' => $this->authorCreated->sex,
                'description' => $this->authorCreated->description,
                'nacionality' => $this->authorCreated->nacionality,
            ],
            'books' => [
                [
                    'title' => $books[0]->title,
                    'description' => $books[0]->description,
                    'price' => $books[0]->price,
                    'number_of_pages' => $books[0]->number_of_pages
                ],
                [
                    'title' => $books[1]->title,
                    'description' => $books[1]->description,
                    'price' => $books[1]->price,
                    'number_of_pages' => $books[1]->number_of_pages
                ],
                [
                    'title' => $books[2]->title,
                    'description' => $books[2]->description,
                    'price' => $books[2]->price,
                    'number_of_pages' => $books[2]->number_of_pages
                ],
            ]
        ]);
    }

    public function testNonAdminCannotCreateAuthor()
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = $user->createToken('token', ['*'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('api/admin/author', [
                'name' => $this->author->name,
                'sex' => $this->author->sex,
                'description' => $this->author->description,
                'nacionality' => $this->author->nacionality,
            ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthorized'
        ]);
        $this->assertDatabaseMissing('authors', [
            'name' => $this->author->name,
            'sex' => $this->author->sex,
            'description' => $this->author->description,
            'nacionality' => $this->author->nacionality,
        ]);
    }

    public function testAuthorValidation()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('api/admin/author', [
                'name' => 'a',
                'sex' => 'cabrito',
                'description' => 5,
                'nacionality' => 4,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'sex', 'description', 'nacionality']);
        $response->assertJson([
            'message' => 'O campo "nome" deve ter pelo menos 3 caracteres. (and 3 more errors)',
            'errors' => [
                'name' => ['O campo "nome" deve ter pelo menos 3 caracteres.'],
                'sex' => ['O campo "sexo" deve ser "feminino" ou "masculino".'],
                'description' => ['O campo "descrição" deve conter apenas caracteres válidos.'],
                'nacionality' => ['O campo "nacionalidade" deve conter apenas caracteres válidos.']
            ]
        ]);
    }

    public function testAuthorCanBeUpdated(): void
    {
        $this->assertDatabaseHas('authors', ['id' => $this->authorCreated->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/admin/author/{$this->authorCreated->id}", [
                'name' => 'teste',
                'sex' => 'masculino',
                'description' => 'teste',
                'nacionality' => 'teste'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('authors', [
            'id' => $this->authorCreated->id,
            'name' => 'teste',
            'sex' => 'masculino',
            'description' => 'teste',
            'nacionality' => 'teste',
        ]);
    }

    public function testAuthorCanBeDeleted(): void
    {
        $this->assertDatabaseHas('authors', [
            'id' => $this->authorCreated->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/admin/author/{$this->authorCreated->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('authors', ['id' => $this->authorCreated->id]);
    }
}
