<?php

namespace App\Providers;

use App\Repositories\BookRatingsRepository;
use App\Repositories\EloquentBookRatingsRepository;
use App\Repositories\EloquentBooksToReadRepository;
use Illuminate\Support\ServiceProvider;

class BookRatingRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        BookRatingsRepository::class => EloquentBookRatingsRepository::class,
    ];
}
