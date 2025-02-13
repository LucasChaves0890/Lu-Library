<?php

namespace App\Services;

use App\Http\Requests\AuthorFormRequest;
use App\Models\Author;
use App\Repositories\AuthorsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AuthorsService
{
    public function __construct(
        private AuthorsRepository $repository,
        private ImageService $imageService
    ) {}

    public function createAuthor(AuthorFormRequest $request): Author
    {
        return DB::transaction(function () use ($request) {
            $request['image'] = $this->uploadAuthorImage($request);

            return $this->repository->create($request->all());
        });
    }

    private function uploadAuthorImage(AuthorFormRequest $request): ?string
    {
        if (!$request->has('image')) {
            return null;
        }

        return $this->imageService->updateImage(
            $request->input('image'),
            'author_image',
            'author_image.jpg',
            null
        );
    }

    public function updateAuthor(Author $author, AuthorFormRequest $request): Author
    {
        return DB::transaction(function () use ($author, $request) {
            $request['image'] = $this->updateAuthorImageIfNeeded($author, $request);;

            return $this->repository->update($author, $request->all());
        });
    }

    private function updateAuthorImageIfNeeded(Author $author, AuthorFormRequest $request)
    {
        if ($request->has('image') && !empty($request->input('image'))) {
            return $this->imageService->updateImage(
                $request->input('image'),
                'author_image',
                'author_image.jpg',
                $author->image
            );
        }

        return $author->image;
    }

    public function deleteAuthor(Author $author)
    {
        return DB::transaction(function () use ($author) {
            $this->deleteAuthorImageIfNeeded($author);
            $this->repository->delete($author);
        });
    }

    private function deleteAuthorImageIfNeeded(Author $author): void
    {
        if ($author->image) {
            $imagePath = $author->image;

            $this->imageService->deleteImages(dirname($imagePath), basename($imagePath));
        }
    }

    public function getAuthorDetailsWithBooks(int $authorId): array
    {
        $author = $this->repository->findAuthor($authorId);

        return [
            'author' => $author,
            'books' => $this->formatAuthorBooks($author->books()->get())
        ];
    }

    private function formatAuthorBooks($books)
    {
        return $books->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'book_cover' => $book->book_cover,
                'number_of_pages' => $book->number_of_pages,
                'price' => $book->price,
                'rate' => $book->averageRating()
            ];
        });
    }

    public function listAuthorsWithBookCount(): Collection
    {
        return $this->repository->listAuthorsWithBookCount();
    }

    public function searchAuthorsByName($name): Collection
    {
        return $this->repository->searchAuthorsByName($name);
    }
}
