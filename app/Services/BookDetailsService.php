<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\BooksRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class BookDetailsService
{
    public function __construct(
        private BooksRepository $repository,
        private BookRatingsService $bookRatingsService,
        private UserFavoriteBooksService $userFavoriteBooksService,
        private BooksToReadService $booksToReadService,
        private PostsService $postsService
    ) {}

    public function getBookDetails(int $bookId, $authUser): array
    {
        $book =  $this->repository->getBookWithAuthor($bookId);

        return [
            'book' => $book,
            'ratingData' => $this->bookRatingsService->getRatingsData($book->id, $authUser->id),
            'posts' => $this->getFormattedPostsAboutBook($book, $authUser),
            'favorite' => $this->userFavoriteBooksService->getFavoriteStatus($authUser->id, $book->id),
            'read' => $this->booksToReadService->getReadData($authUser->id, $book->id),
            'recomendation' => $this->getRecommendedBooks($book)
        ];
    }

    private function getFormattedPostsAboutBook(Book $book, $authUser): SupportCollection
    {
        $posts = $book->posts()->take(3)->get();

        return $posts->map(function ($post) use ($authUser): array {
            return $this->postsService->formatPost($post, $authUser);
        });
    }

    private function getRecommendedBooks(Book $book): Collection
    {
        $recommendation = $this->repository->getBookRecommendations($book);

        if ($recommendation->count() < 7) {
            $excludedIds = $recommendation->pluck('id')->push($book->id);
            $additionalBooks = $this->repository->getAdditionalBooks($excludedIds, $recommendation->count());
            $recommendation = $recommendation->concat($additionalBooks);
        }

        return $recommendation;
    }
}
