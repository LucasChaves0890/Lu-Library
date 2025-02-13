<?php

namespace Tests\Unit;

use App\Models\Author;
use PHPUnit\Framework\TestCase;

class AuthorUnitTest extends TestCase
{
    private $author;

    public function setUp(): void
    {
        $data = [
            "name" => 'George Orwell',
            "sex" => 'masculino',
            "description" => 'Autor distopista',
            "nacionality" => 'Britânico'
        ];

        $this->author = new Author($data);
    }

    public function testAuthorCreation(): void
    {
        $author = $this->author;

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('George Orwell', $author->name);    
        $this->assertEquals('masculino', $author->sex);    
        $this->assertEquals('Autor distopista', $author->description);
        $this->assertEquals('Britânico', $author->nacionality);    
    }
}
