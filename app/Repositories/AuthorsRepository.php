<?php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

interface AuthorsRepository
{
    public function create(array $request): Author;

    public function update(Author $author, array $request): Author;

    public function delete(Author $author): void;

    public function findAuthor(int $authorId): Author;

    public function listAuthorsWithBookCount(): Collection;

    public function searchAuthorsByName($name): Collection;
}
