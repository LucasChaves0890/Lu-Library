<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

interface BooksRepository
{
    public function create(array $request): Book;

    public function update(Book $book, array $request): Book;
    
    public function delete(Book $book): void;
    
    public function findBook(int $bookId): Book;

    public function getBookWithAuthor(int $book): ?Book;

    public function getBooksWithAuthor(): array;

    public function getBookRecommendations(Book $book): Collection;

    public function getAdditionalBooks($excludedIds, int $recommendationCount): Collection;

    public function searchBookByTitle(string $title): Collection;

}
