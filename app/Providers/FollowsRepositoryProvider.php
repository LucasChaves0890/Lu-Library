<?php

namespace App\Providers;

use App\Repositories\EloquentFollowsRepository;
use App\Repositories\FollowsRepository;
use Illuminate\Support\ServiceProvider;

class FollowsRepositoryProvider extends ServiceProvider
{
   public array $bindings = [
        FollowsRepository::class => EloquentFollowsRepository::class
   ];
}
