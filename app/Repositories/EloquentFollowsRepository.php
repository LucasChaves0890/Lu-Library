<?php

namespace App\Repositories;

use App\Models\Follow;
use Illuminate\Database\Eloquent\Collection;

class EloquentFollowsRepository implements FollowsRepository
{


    public function getExistingFollow(int $followingId, int $followedId)
    {
        return Follow::where('following_user_id', $followingId)
            ->where('followed_user_id', $followedId)
            ->first();
    }

    public function create(int $followingId, int $followedId): void
    {
        Follow::create([
            'following_user_id' => $followingId,
            'followed_user_id' => $followedId
        ]);
    }

    public function getFollowCountByFollowingId(int $followingId): int
    {
        return Follow::where('following_user_id', $followingId)->count();
    }

    public function delete($existingFollow)
    {
        $existingFollow->delete();
    }

    public function getFollowingIdsByUserId(int $userId): Collection
    {
        return new Collection(
            Follow::where('following_user_id', $userId)
                ->with('followedUser')
                ->pluck('following_user_id')
        );
    }
}
