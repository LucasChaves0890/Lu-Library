<?php

namespace App\Services;

use App\Http\Requests\BookFormRequest;
use App\Models\Book;
use App\Repositories\BooksRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookService
{
    public function __construct(
        private BooksRepository $repository,
        private ImageService $imageService,
        private FormatBookService $formatBookService,
        private BookDetailsService $bookDetailsService,
        private BookshelfService $bookshelfService
    ) {}

    public function createBook(BookFormRequest $request): Book
    {
        return DB::transaction(function () use ($request): Book {
            $request['book_cover'] = $this->uploadBookCover($request);

            return  $this->repository->create($request->all());
        });
    }

    private function uploadBookCover(BookFormRequest $request): string|null
    {
        if (!$request->has('book_cover')) {
            return null;
        }

        return $this->imageService->updateImage(
            $request->input('book_cover'),
            'book_cover',
            'book_cover.jpg',
            null
        );
    }

    public function updateBook(Book $book, BookFormRequest $request): Book
    {
        return DB::transaction(function () use ($book, $request) {
            $request['book_cover'] = $this->updateBookCoverIfNeeded($book, $request);

            return $this->repository->update($book, $request->all());
        });
    }

    private function updateBookCoverIfNeeded(Book $book, BookFormRequest $request)
    {
        if ($request->has('book_cover') && !empty($request->input('book_cover'))) {
            return $this->imageService->updateImage(
                $request->input('book_cover'),
                'book_cover',
                'book_cover.jpg',
                $book->book_cover
            );
        }
    }

    public function deleteBook(Book $book): void
    {
        DB::transaction(function () use ($book) {
            $this->deleteBookCoverIfNeeded($book);
            $this->deletePostsFromBookIfExists($book);
            $this->repository->delete($book);
        });
    }

    private function deleteBookCoverIfNeeded(Book $book): void
    {
        if ($book->book_cover) {
            $coverPath = $book->book_cover;

            $this->imageService->deleteImages(dirname($coverPath), basename($coverPath));
            $book->update(['book_cover' => null]);
        }
    }

    private function deletePostsFromBookIfExists(Book $book)
    {
        if ($book->posts()->exists()) {
            $book->posts()->delete();
        }

        if ($book->favoritedByUsers()->exists()) {
            $book->favoritedByUsers()->delete();
        }

        if ($book->favoritedByUsers()->exists()) {
            $book->favoritedByUsers()->delete();
        }

        if ($book->ratings()->exists()) {
            $book->ratings()->delete();
        }
    }
    
    public function getBooksWithAuthor(): array
    {
        return $this->repository->getBooksWithAuthor();
    }

    public function searchBookByTitle(string $title): Collection
    {
        return $this->repository->searchBookByTitle($title);
    }

    public function getBookDetails(int $bookId, $authUser): array
    {
        return $this->bookDetailsService->getBookDetails($bookId, $authUser);
    }
}
