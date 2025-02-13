<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UsersRepository
{
    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function findUser(int $userId): ?User;

    public function getAllUsers(): Collection;

    public function searchUserByName(string $query):Collection;
}
