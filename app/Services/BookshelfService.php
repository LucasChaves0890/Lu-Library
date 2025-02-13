<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class BookshelfService
{
    public function __construct(
        private BooksToReadService $booksToReadService,
        private BookRatingsService $bookRatingsService,
        private UserFavoriteBooksService $userFavoriteBooksService,
        private FormatBookService $formatBookService
    ) {}

    public function getBookshelf(int $userId): array
    {
        $books = $this->mergeBooksByUser($userId);
        $totalOfReadPages = $this->booksToReadService->getTotalPagesReadByUserId($userId);

        return [
            'bookshelf' => $this->formatBookshelf($books, $userId),
            'totalOfReadPages' => $totalOfReadPages,
            'booksQty' => $books->count()
        ];
    }

    public function searchBookShelfByTitle(int $userId, string $title): array
    {
        $books = $this->mergeBooksByUser($userId, $title);

        return $this->formatBookshelf($books, $userId);
    }

    private function mergeBooksByUser(int $userId, ?string $title = null): Collection
    {
        $favoriteBooks = $this->getFavoriteBooks($userId, $title);
        $readBooks = $this->getReadBooks($userId, $title);
        $ratedBooks = $this->getRatedBooks($userId, $title);

        return $this->normalizeBooks([$favoriteBooks, $readBooks, $ratedBooks]);
    }

    private function formatBookshelf(Collection $books, int $userId): array
    {
        return $books->map(function ($book) use ($userId) {
            $ratingData = $this->bookRatingsService->getRatingsData($book['id'], $userId);
            $favorite = $this->userFavoriteBooksService->getFavoriteStatus($userId, $book['id']);
            $read = $this->booksToReadService->getReadData($userId, $book['id']);

            return $this->formatBookService->formatBook($book, $ratingData, $favorite, $read);
        })->toArray();
    }

    private function getFavoriteBooks(int $userId, ?string $title = null): Collection
    {
        return $title
            ? $this->userFavoriteBooksService->searchUserFavoriteBooks($userId, $title)
            : $this->userFavoriteBooksService->getUserFavoriteBooks($userId);
    }

    private function getReadBooks(int $userId, ?string $title = null): Collection
    {
        return $title
            ? $this->booksToReadService->searchUserReadedBooks($userId, $title)
            : $this->booksToReadService->getBooksFromReadListByUserId($userId);
    }

    private function getRatedBooks(int $userId, ?string $title = null): Collection
    {
        return $title
            ? $this->bookRatingsService->searchBooksRatedByUser($userId, $title)
            : $this->bookRatingsService->getBooksRatedByUser($userId);
    }

    private function normalizeBooks(array $booksList): Collection
    {
        $mergedBooks = collect();

        foreach ($booksList as $books) {
            $mergedBooks = $mergedBooks->merge($books);
        }

        return new Collection($mergedBooks->unique('id')->values());
    }


    public function getUserReadedBooksFormatted(int $userId)
    {
        $readedBooks = $this->booksToReadService->getReadedBooksByUserId($userId);

        return $readedBooks->map(function ($book) use ($userId) {
            $ratingData = $this->bookRatingsService->getRatingsData($book['book']['id'], $userId);
            $favorite = $this->userFavoriteBooksService->getFavoriteStatus($userId, $book['book']['id']);
            $read = $this->booksToReadService->getReadData($userId, $book['book']['id']);

            return $this->formatBookService->formatBook($book['book'], $ratingData, $favorite, $read);
        });
    }

    public function getUserReadingBooksFormatted(int $userId)
    {
        $readingBooks = $this->booksToReadService->getReadingBooksByUserId($userId);

        return  $readingBooks->map(function ($book) use ($userId) {
            $ratingData = $this->bookRatingsService->getRatingsData($book['book']['id'], $userId);
            $favorite = $this->userFavoriteBooksService->getFavoriteStatus($userId, $book['book']['id']);
            $read = $this->booksToReadService->getReadData($userId, $book['book']['id']);

            return $this->formatBookService->formatBook($book['book'], $ratingData, $favorite, $read);
        });
    }

    public function getUserFavoriteBooksFormatted(int $userId)
    {
        $favoriteBooks = $this->userFavoriteBooksService->getUserFavoriteBooks($userId);

        return $favoriteBooks->map(function ($book) use ($userId) {
            $ratingData = $this->bookRatingsService->getRatingsData($book['id'], $userId);
            $favorite = $this->userFavoriteBooksService->getFavoriteStatus($userId, $book['id']);
            $read = $this->booksToReadService->getReadData($userId, $book['id']);

            return $this->formatBookService->formatBook($book, $ratingData, $favorite, $read);
        });
    }
}
