<?php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

class EloquentAuthorsRepository  implements AuthorsRepository
{
    public function create(array $request): Author
    {
        return Author::create($request);
    }

    public function update(Author $author, array $request): Author
    {
        $author->update($request);

        return $author;
    }

    public function delete(Author $author): void
    {
        $author->delete();
    }

    public function findAuthor(int $authorId): Author
    {
        return Author::find($authorId);
    }

    public function listAuthorsWithBookCount(): Collection
    {
        return Author::withCount('books')->get();
    }

    public function searchAuthorsByName($name): Collection
    {
        return Author::where('name', 'ILIKE', '%' . $name . '%')->get();
    }
}
