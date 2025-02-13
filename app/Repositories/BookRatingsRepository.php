<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface BookRatingsRepository
{
    public function createOrUpdate(array $data): void;

    public function getBooksRatedByUser(int $userId): Collection;
    
    public function getAverageRatingByBookId(int $bookId): ?string;

    public function getUserRatingForBook(int $bookId, int $userId): ?int;

    public function countRatingsForBook(int $bookId): ?int;

    public function searchBooksRatedByUser(int $userId, $title): Collection;

}
