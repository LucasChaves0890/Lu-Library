<?php

namespace App\Services;

use App\Http\Requests\BookRatingFormRequest;
use App\Repositories\BookRatingsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookRatingsService
{
    public function __construct(
        private BookRatingsRepository $repository
    ) {}

    public function createOrUpdateRating(BookRatingFormRequest $request): array
    {
        $data = $this->createData($request);

        DB::transaction(function () use ($data) {
            $this->repository->createOrUpdate($data);
        });

        return [
            'rating' => $data['rating'],
            'averageRating' => $this->calculateAverageRating($request->get('book_id'))
        ];
    }

    private function createData(BookRatingFormRequest $request)
    {
        return [
            'user_id' => $request->get('user_id'),
            'book_id' => $request->get('book_id'),
            'rating' => $request->get('rating')
        ];
    }

    private function calculateAverageRating(int $bookId): string
    {
        $averageRating = $this->repository->getAverageRatingByBookId($bookId);

        return rtrim(rtrim(number_format($averageRating, 2), '0'), '.');
    }

    public function getRatingsData(int $bookId, int $userId): array
    {
        $averageRating = $this->calculateAverageRating($bookId);
        $userRating = $this->repository->getUserRatingForBook($bookId, $userId);
        $bookRatingsQty = $this->repository->countRatingsForBook($bookId);

        return [
            'averageRating' => $averageRating,
            'userRating' => $userRating,
            'bookRatingsQty' => $bookRatingsQty
        ];
    }

    public function getBooksRatedByUser(int $userId): Collection
    {
        return $this->repository->getBooksRatedByUser($userId);
    }

    public function searchBooksRatedByUser(int $userId, string $title): Collection
    {
        return $this->repository->searchBooksRatedByUser($userId, $title);
    }
}
