<?php

namespace App\Repositories;

use App\Models\UserFavoriteBook;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserFavoriteBooksRepository implements UserFavoriteBooksRepository
{
   
    public function create(array $data): void
    {
        UserFavoriteBook::create($data);
    }

    public function findUserFavoriteBook(int $userId, int $bookId)
    {
        return UserFavoriteBook::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();
    }

    public function getUserFavoriteBookByUserId(int $userId): Collection
    {
        return UserFavoriteBook::where('user_id', $userId)
            ->get();
    }

    public function getFavoriteCount(int $bookId): int
    {
        return UserFavoriteBook::where('book_id', $bookId)->get()->count();
    }

    public function searchUserFavoriteBooks(int $userId, $title)
    {
        return UserFavoriteBook::join('books', 'user_favorites_books.book_id', '=', 'books.id')
            ->where('user_favorites_books.user_id', $userId)
            ->where('books.title', 'ILIKE', '%' . $title . '%')
            ->select('books.*') 
            ->get();
    }
    
}
