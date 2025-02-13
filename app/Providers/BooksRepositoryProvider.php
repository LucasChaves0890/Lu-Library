<?php

namespace App\Providers;

use App\Repositories\BooksRepository;
use App\Repositories\EloquentBooksRepository;
use Illuminate\Support\ServiceProvider;

class BooksRepositoryProvider extends ServiceProvider
{
  public array $bindings = [
    BooksRepository::class => EloquentBooksRepository::class,
  ];
}
