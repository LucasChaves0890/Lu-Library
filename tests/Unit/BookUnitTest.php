<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use PHPUnit\Framework\TestCase;

class BookUnitTest extends TestCase
{
    private $book;
    public function setUp(): void
    {
        $author = new Author(["name" => 'Lucas', "sex" => 'masculino']);

        $data = [
            "title" => 'livro bacana',
            "description" => 'nossa que legal',
            "gender" => 'distopia',
            "price" => 5,
            "author_id" => $author->id
        ];

        $this->book = new Book($data);
    }


    public function testBookCanBeCreated(): void
    {
        $book = $this->book;

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals('livro bacana', $book->title);
    }
}
