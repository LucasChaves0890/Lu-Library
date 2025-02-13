<?php

namespace App\Repositories;

use App\Models\BookToRead;
use Illuminate\Database\Eloquent\Collection;

class EloquentBooksToReadRepository implements BooksToReadRepository
{

    public function create(int $userId, $bookId): BookToRead
    {
        return new BookToRead(['user_id' => $userId, 'book_id' => $bookId]);
    }

    public function updateOrCreate(array $data): void
    {
        BookToRead::updateOrCreate($data);
    }

    public function find(int $userId, $bookId): ?BookToRead
    {
        return BookToRead::where('book_id', $bookId)
            ->where('user_id', $userId)
            ->first();
    }

    public function getBooksToReadByUserId(int $userId): Collection
    {
        return BookToRead::where('user_id', $userId)->get();
    }

    public function getBooksToReadByBookId(int $bookId): Collection
    {
        return BookToRead::where('book_id', $bookId)->get();
    }


    public function searchUserReadedBooks(int $userId, string $title): Collection
    {
        return BookToRead::join('books', 'books_to_read.book_id', '=', 'books.id')
            ->where('books_to_read.user_id', $userId)
            ->where('title', 'ILIKE', '%' . $title . '%')
            ->whereColumn('pages_read', 'books.number_of_pages')
            ->get();
    }

    // Pesquisa livros em leitura pelo título
    public function searchUserReadingBooks(int $userId, $title): Collection
    {
        return BookToRead::join('books', 'books_to_read.book_id', '=', 'books.id')
            ->where('books_to_read.user_id', $userId)
            ->where('pages_read', '>', 0)
            ->where('title', 'ILIKE', '%' . $title . '%')
            ->whereColumn('pages_read', '<', 'books.number_of_pages')
            ->get();
    }
}
