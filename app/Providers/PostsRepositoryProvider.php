<?php

namespace App\Providers;

use App\Repositories\PostsRepository;
use App\Repositories\EloquentPostRepository;
use Illuminate\Support\ServiceProvider;

class PostsRepositoryProvider extends ServiceProvider
{
   public array $bindings = [
      PostsRepository::class => EloquentPostRepository::class
   ];
}
