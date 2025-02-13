<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

class EloquentBooksRepository implements BooksRepository
{

    public function create(array $request): Book
    {
        return Book::create($request);
    }

    public function update(Book $book, array $request): Book
    {
        $book->update($request);

        return $book;
    }

    public function delete(Book $book): void
    {
        $book->delete();
    }

    public function findBook(int $bookId): Book
    {
        return Book::find($bookId);
    }

    public function getBookWithAuthor(int $book): ?Book
    {
        return Book::with('author')->find($book);
    }

    public function getBooksWithAuthor(): array
    {
        $books = Book::with(['author:id,name'])->get();

        return [
            'books' => $books,
            'books_count' => $books->count(),
        ];
    }

    public function getBookRecommendations(Book $book): Collection
    {
        return Book::whereNot('id', $book->id)
            ->where('author_id', $book->author->id)->take(7)
            ->get(['book_cover', 'id']);
    }

    public function getAdditionalBooks($excludedIds, int $recomendationCount): Collection
    {
        return Book::whereNotIn('id', $excludedIds)
            ->take(7 - $recomendationCount)
            ->get(['book_cover', 'id']);
    }

    public function searchBookByTitle(string $title): Collection
    {
        return Book::where('title', 'ILIKE', '%' . $title . '%')->get();
    }
}
