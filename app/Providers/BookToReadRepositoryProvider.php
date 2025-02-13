<?php

namespace App\Providers;

use App\Repositories\BooksToReadRepository;
use App\Repositories\EloquentBooksToReadRepository;
use Illuminate\Support\ServiceProvider;

class BookToReadRepositoryProvider extends ServiceProvider
{
   public array $bindings = [
        BooksToReadRepository::class => EloquentBooksToReadRepository::class
   ];
}
