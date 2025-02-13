<?php

namespace App\Services;

use App\Http\Requests\UserFavoriteBookFormRequest;
use App\Models\UserFavoriteBook;
use App\Repositories\UserFavoriteBooksRepository;

class UserFavoriteBooksService
{
    public function __construct(
        private UserFavoriteBooksRepository $repository,
        private FormatBookService $formatBookService,
    ) {}

    public function toggleUserFavoriteBook(UserFavoriteBookFormRequest $request): array
    {
        $userId = $request->get('user_id');
        $bookId = $request->get('book_id');

        $isFavorite = $this->repository->findUserFavoriteBook($userId, $bookId);

        if ($isFavorite) {
            return $this->removeFavorite($isFavorite, $bookId);
        }

        return $this->addFavorite($bookId, $userId);
    }

    private function addFavorite(int $bookId, $userId): array
    {
        $this->repository->create([
            'book_id' => $bookId,
            'user_id' => $userId
        ]);

        $favorites = $this->repository->getFavoriteCount($bookId);

        return [
            'favorite' => true,
            'favorites' => $favorites
        ];
    }

    private function removeFavorite(UserFavoriteBook $isFavorite, int $bookId): array
    {
        $isFavorite->delete();
        $favorites = $this->repository->getFavoriteCount($bookId);

        return [
            'favorite' => false,
            'favorites' => $favorites
        ];
    }

    public function getFavoriteStatus($userId, $bookId): array
    {
        $favorite = $this->repository->findUserFavoriteBook($userId, $bookId);

        $favorites = $this->repository->getFavoriteCount($bookId);

        return [
            'favorite' => $favorite,
            'favorites' => $favorites
        ];
    }

    public function searchUserFavoriteBooks(int $userId, $title)
    {
        return $this->repository->searchUserFavoriteBooks($userId, $title);
    }

    public function getUserFavoriteBooks(int $userId)
    {
        $userFavoriteBooks = $this->repository->getUserFavoriteBookByUserId($userId);

        return $userFavoriteBooks->map(fn($userFavoriteBook) => $userFavoriteBook->book);
    }

    public function getUserFavoriteBooksCount(int $userId): int
    {
        return $this->getUserFavoriteBooks($userId)->count();
    }
}
