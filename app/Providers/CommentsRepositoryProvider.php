<?php

namespace App\Providers;

use App\Repositories\CommentsRepository;
use App\Repositories\EloquentCommentsRepository;
use Illuminate\Support\ServiceProvider;

class CommentsRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        CommentsRepository::class => EloquentCommentsRepository::class
    ];
}
