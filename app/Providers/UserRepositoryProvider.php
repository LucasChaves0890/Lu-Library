<?php

namespace App\Providers;

use App\Repositories\EloquentUserRepository;
use App\Repositories\UsersRepository;
use Illuminate\Support\ServiceProvider;

class UserRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        UsersRepository::class => EloquentUserRepository::class
    ];
}
