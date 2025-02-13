<?php

namespace App\Providers;

use App\Repositories\AdminsRepository;
use App\Repositories\EloquentAdminRepository;
use Illuminate\Support\ServiceProvider;

class AdminsRepositoryProvider extends ServiceProvider
{
   public array $bindings = [
    AdminsRepository::class => EloquentAdminRepository::class
   ];
}
