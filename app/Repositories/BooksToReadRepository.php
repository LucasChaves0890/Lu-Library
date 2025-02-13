<?php

namespace App\Repositories;

use App\Models\BookToRead;
use Illuminate\Database\Eloquent\Collection;

interface BooksToReadRepository
{
    public function create(int $userId, $bookId): BookToRead;

    public function updateOrCreate(array $data): void;

    public function find(int $userId, $bookId): ?BookToRead;

    public function getBooksToReadByUserId(int $userId): Collection;

    public function getBooksToReadByBookId(int $bookId): Collection;

    public function searchUserReadedBooks(int $userId, string $title): Collection;

    public function searchUserReadingBooks(int $userId, $title): Collection;

}
