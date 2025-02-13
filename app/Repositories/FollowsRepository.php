<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface FollowsRepository
{
    public function getExistingFollow(int $followingId, int $followedId);

    public function create(int $followingId, int $followedId): void;

    public function getFollowCountByFollowingId(int $followingId): int;

    public function delete($existingFollow);

    public function getFollowingIdsByUserId(int $userId): Collection;

}
