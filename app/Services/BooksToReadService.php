<?php

namespace App\Services;

use App\Http\Requests\BookToReadFormRequest;
use App\Models\Book;
use App\Models\BookToRead;
use App\Repositories\BooksToReadRepository;
use Illuminate\Database\Eloquent\Collection;

class BooksToReadService
{
    public function __construct(
        private BooksToReadRepository $repository,
        private FormatBookService $formatBookService,
        private BookRatingsService $bookRatingsService,
        private UserFavoriteBooksService $userFavoriteBooksService,
    ) {}

    public function toggleBookReadStatus(BookToReadFormRequest $request): array
    {
        $userId = $request->get('user_id');
        $bookId = $request->get('book_id');
        $bookToRead = $this->firstOrNewRead($userId, $bookId);

        return $this->processReadStatus($bookToRead);
    }

    private function processReadStatus(BookToRead $bookToRead): array
    {
        $numberOfPages = $bookToRead->getBooksPageAttribute();

        if ($bookToRead->exists && $bookToRead->pages_read < $numberOfPages) {
            $this->removeBookFromReadList($bookToRead);

            return $this->addBookToReadList($bookToRead, $numberOfPages);
        } else if ($bookToRead->exists) {
            return $this->removeBookFromReadList($bookToRead);
        }

        return $this->addBookToReadList($bookToRead, $numberOfPages);
    }

    private function firstOrNewRead(int $userId, int $bookId)
    {
        $bookToRead = $this->repository->find($userId, $bookId);

        if (!$bookToRead) {
            $bookToRead = $this->repository->create($userId, $bookId);
        }

        return $bookToRead;
    }

    private function removeBookFromReadList(BookToRead $bookToRead): array
    {
        $booksReadingQty = $this->getReadingBooksCount($bookToRead->book->id);
        $bookToRead->delete();
        $booksReadQty = $this->getReadedBooksCount($bookToRead->book->id);


        return [
            'readed' => false,
            'booksReadQty' => $booksReadQty,
            'booksReadingQty' => $booksReadingQty
        ];
    }

    private function addBookToReadList(BookToRead $bookToRead, $numberOfPages): array
    {
        $bookToRead->pages_read = $numberOfPages;
        $bookToRead->save();

        $booksReadQty = $this->getReadedBooksCount($bookToRead->book->id);
        $booksReadingQty = $this->getReadingBooksCount($bookToRead->book->id);

        return [
            'readed' => true,
            'booksReadQty' => $booksReadQty,
            'booksReadingQty' => $booksReadingQty
        ];
    }

    public function updateOrCreateBookRead(BookToReadFormRequest $request): array
    {
        $userId = $request->get('user_id');
        $bookId = $request->get('book_id');
        $pagesRead = $request->get('pages_read') ?? 0;

        $this->updateOrCreateRead($bookId, $userId, $pagesRead);

        return $this->getReadData($userId, $bookId);
    }

    private function updateOrCreateRead(int $bookId, int $userId, int $pagesRead): void
    {
        $numberOfPages = Book::find($bookId, ['number_of_pages']);

        $pagesRead = min($pagesRead, $numberOfPages['number_of_pages']);

        $data = [
            'book_id' => $bookId,
            'user_id' => $userId,
            'pages_read' => $pagesRead
        ];

        $bookToRead = $this->repository->find($userId, $bookId);

        if ($bookToRead !== null) {
            $bookToRead->update($data);
            return;
        }

        $this->repository->updateOrCreate($data);
    }

    private function getReadedBooksCount(int $bookId): int
    {
        return $this->getReadedBooks($bookId)
            ->count();
    }

    private function getReadedBooks(int $bookId): Collection
    {
        return $this->repository->getBooksToReadByBookId($bookId)
            ->filter(fn($data) => $data->book->number_of_pages === $data->pages_read);
    }

    private function getReadingBooksCount(int $bookId): int
    {
        return $this->getReadingBooks($bookId)
            ->count();
    }

    private function getReadingBooks(int $bookId): Collection
    {
        return $this->repository->getBooksToReadByBookId($bookId)
            ->filter(fn($data) => $data->book->number_of_pages !== $data->pages_read);
    }

    public function getReadData(int $userId, int $bookId): array
    {
        $book = Book::find($bookId);
        $numberOfPages = $book->number_of_pages;
        $bookToRead = $this->repository->find($userId, $bookId);
        $pagesRead = $bookToRead->pages_read ?? 0;

        return [
            'readed' => $pagesRead == $numberOfPages,
            'booksReadQty' =>  $this->getReadedBooksCount($bookId),
            'booksReadingQty' => $this->getReadingBooksCount($bookId),
            'pagesRead' => $pagesRead,
            'percentageRead' => $this->calculatePercentageRead($pagesRead, $numberOfPages)
        ];
    }

    private function calculatePercentageRead(int $pagesRead, int $numberOfPages): float|int
    {
        return $numberOfPages > 0
            ? round(($pagesRead / $numberOfPages) * 100)
            : 0;
    }

    public function getReadById(int $userId): array
    {
        $readedBooksQty = $this->getReadedBooksCountByUserId($userId);
        $readingBooksQty = $this->getReadingBooksCountByUserId($userId);
        $booksPagesReaded = $this->getTotalPagesReadByUserId($userId);
        $favorites = $this->userFavoriteBooksService->getUserFavoriteBooksCount($userId);

        return [
            'readedBooksQty' => $readedBooksQty,
            'readingBooksQty' => $readingBooksQty,
            'booksPagesReaded' => $booksPagesReaded,
            'favorites' => $favorites
        ];
    }

    public function getReadedBooksByUserId(int $userId): Collection
    {
        return $this->repository->getBooksToReadByUserId($userId)
            ->filter(fn($data) => $data->book->number_of_pages === $data->pages_read);
    }

    private function getReadedBooksCountByUserId(int $userId): int
    {
        return $this->getReadedBooksByUserId($userId)
            ->count();
    }

    public function getReadingBooksByUserId(int $userId): Collection
    {
        return $this->repository->getBooksToReadByUserId($userId)
            ->filter(fn($data) => $data->book->number_of_pages !== $data->pages_read);
    }

    private function getReadingBooksCountByUserId(int $userId): int
    {
        return $this->getReadingBooksByUserId($userId)
            ->count();
    }

    public function getBooksFromReadListByUserId(int $userId): Collection
    {
        return $this->repository->getBooksToReadByUserId($userId)
            ->map(fn($data) => $data->book);
    }

    public function getTotalPagesReadByUserId(int $userId): int
    {
        return $this->repository->getBooksToReadByUserId($userId)
            ->sum(['pages_read']);
    }

    public function searchUserReadedBooks(int $userId, string $title): Collection
    {
        return $this->repository->searchUserReadedBooks($userId, $title);
    }

}
