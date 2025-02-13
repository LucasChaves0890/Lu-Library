<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
class EloquentUserRepository implements UsersRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public function findUser(int $userId): ?User
    {
        return User::find($userId);
    }

    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function searchUserByName(string $query): Collection
    {
        return User::where('username', 'ILIKE', '%' . $query . '%')->get();
    }
}
