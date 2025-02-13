<?php

namespace App\Repositories;

use App\Models\BookRating;
use Illuminate\Database\Eloquent\Collection;

class EloquentBookRatingsRepository implements BookRatingsRepository
{

    public function createOrUpdate(array $data): void
    {
        BookRating::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'book_id' => $data['book_id']
            ],
            [
                'rating' => $data['rating']
            ]
        );
    }

    public function getBooksRatedByUser(int $userId): Collection
    {
        return new Collection(
            BookRating::where('user_id', $userId)
                ->with('book')
                ->get()
                ->pluck('book')
        );
    }

    public function getAverageRatingByBookId(int $bookId): ?string
    {
        return BookRating::where('book_id', $bookId)->avg('rating');
    }

    public function getUserRatingForBook(int $bookId, int $userId): ?int
    {
        return BookRating::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->value('rating');
    }

    public function countRatingsForBook(int $bookId): ?int
    {
        return BookRating::where('book_id', $bookId)->count();
    }

    public function searchBooksRatedByUser(int $userId, $title): Collection
    {
        return BookRating::join('books', 'book_ratings.book_id', '=', 'books.id')
            ->where('book_ratings.user_id', $userId)
            ->where('books.title', 'ILIKE', '%' . $title . '%')
            ->select('books.*')
            ->get();
    }
}
