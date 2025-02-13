<?php

namespace App\Providers;

use App\Repositories\EloquentUserFavoriteBooksRepository;
use App\Repositories\UserFavoriteBooksRepository;
use Illuminate\Support\ServiceProvider;

class UserFavoriteBooksRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        UserFavoriteBooksRepository::class => EloquentUserFavoriteBooksRepository::class
    ];
}
