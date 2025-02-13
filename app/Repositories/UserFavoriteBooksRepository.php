<?php

namespace App\Repositories;

use App\Models\UserFavoriteBook;
use Illuminate\Database\Eloquent\Collection;

interface UserFavoriteBooksRepository
{
    public function create(array $data): void;

    public function findUserFavoriteBook(int $userId, int $bookId);

    public function getUserFavoriteBookByUserId(int $userId): Collection;

    public function getFavoriteCount(int $bookId): int;

    public function searchUserFavoriteBooks(int $userId, $title);
}
