<?php

namespace App\Providers;

use App\Repositories\AuthorsRepository;
use App\Repositories\EloquentAuthorsRepository;
use Illuminate\Support\ServiceProvider;

class AuthorsRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        AuthorsRepository::class => EloquentAuthorsRepository::class
    ];
}
