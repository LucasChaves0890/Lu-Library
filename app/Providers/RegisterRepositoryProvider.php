<?php

namespace App\Providers;

use App\Repositories\EloquentRegisterRepository;
use App\Repositories\RegisterRepository;
use Illuminate\Support\ServiceProvider;

class RegisterRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        RegisterRepository::class => EloquentRegisterRepository::class
    ];
}
